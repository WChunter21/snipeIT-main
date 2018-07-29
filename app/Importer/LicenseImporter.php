<?php

namespace App\Importer;

use App\Helpers\Helper;
use App\Models\Asset;
use App\Models\Category;
use App\Models\LicenseModel;
use App\Models\Manufacturer;

class LicenseImporter extends ItemImporter
{
    public function __construct($filename)
    {
        parent::__construct($filename);
    }

    protected function handle($row)
    {
        // ItemImporter handles the general fetching.
        parent::handle($row);
        $this->createLicenseIfNotExists($row);
    }

    /**
     * Create the license if it does not exist.
     *
     * @author Daniel Melzter
     * @since 4.0
     * @param array $row
     * @return LicenseModel|mixed|null
     */
    public function createLicenseIfNotExists(array $row)
    {
        $editingLicense = false;
        $licenseModel = LicenseModel::where('name', $this->item['name'])
                    ->where('serial', $this->item['serial'])
                    ->first();
        if ($licenseModel) {
            if (!$this->updating) {
                $this->log('A matching License ' . $this->item['name'] . 'with serial ' . $this->item['serial'] . ' already exists');
                return;
            }

            $this->log("Updating License");
            $editingLicense = true;
        } else {
            $this->log("No Matching License, Creating a new one");
            $licenseModel = new LicenseModel;
        }
        $asset_tag = $this->item['asset_tag'] = $this->findCsvMatch($row, 'asset_tag'); // used for checkout out to an asset.
        $this->item['expiration_date'] = $this->findCsvMatch($row, 'expiration_date');
        $this->item['license_email'] = $this->findCsvMatch($row, "license_email");
        $this->item['license_name'] = $this->findCsvMatch($row, "license_name");
        $this->item['maintained'] = $this->findCsvMatch($row, 'maintained');
        $this->item['purchase_order'] = $this->findCsvMatch($row, 'purchase_order');
        $this->item['reassignable'] = $this->findCsvMatch($row, 'reassignable');
        $this->item['seats'] = $this->findCsvMatch($row, 'seats');
        $this->item['termination_date'] = $this->findCsvMatch($row, 'termination_date');

        if ($editingLicense) {
            $licenseModel->update($this->sanitizeItemForUpdating($licenseModel));
        } else {
            $licenseModel->fill($this->sanitizeItemForStoring($licenseModel));
        }
        //FIXME: this disables model validation.  Need to find a way to avoid double-logs without breaking everything.
        // $licenseModel->unsetEventDispatcher();
        if ($licenseModel->save()) {
            $licenseModel->logCreate('Imported using csv importer');
            $this->log('License ' . $this->item["name"] . ' with serial number ' . $this->item['serial'] . ' was created');

            // Lets try to checkout seats if the fields exist and we have seats.
            if ($licenseModel->seats > 0) {
                $checkout_target = $this->item['checkout_target'];
                $asset = Asset::where('asset_tag', $asset_tag)->first();
                $targetLicense = $licenseModel->licenses()->first();
                if ($checkout_target) {
                    $targetLicense->assigned_to = $checkout_target->id;
                    if ($asset) {
                        $targetLicense->asset_id = $asset->id;
                    }
                    $targetLicense->save();
                } elseif ($asset) {
                    $targetLicense->asset_id = $asset->id;
                    $targetLicense->save();
                }
            }
            return;
        }
        $this->logError($licenseModel, 'License "' . $this->item['name'].'"');
    }
}
