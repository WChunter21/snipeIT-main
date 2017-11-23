<?php
use App\Importer\AccessoryImporter;
use App\Importer\AssetImporter;
use App\Importer\ConsumableImporter;
use App\Importer\LicenseImporter;
use App\Models\Accessory;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Category;
use App\Models\CustomField;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Hash;

class ImporterTest extends BaseTest
{
    /**
    * @var \UnitTester
    */
    protected $tester;

    public function testDefaultImportAssetWithCustomFields()
    {
        $csv = <<<'EOT'
Name,Email,Username,item Name,Category,Model name,Manufacturer,Model Number,Serial,Asset Tag,Location,Notes,Purchase Date,Purchase Cost,Company,Status,Warranty,Supplier,Weight
Bonnie Nelson,bnelson0@cdbaby.com,bnelson0,eget nunc donec quis,quam,massa id,Linkbridge,6377018600094472,27aa8378-b0f4-4289-84a4-405da95c6147,970882174-8,Daping,"Curabitur in libero ut massa volutpat convallis. Morbi odio odio, elementum eu, interdum eu, tincidunt in, leo. Maecenas pulvinar lobortis est.",2016-04-05,133289.59,Alpha,Undeployable,14,Blogspan,35
EOT;

        $this->initializeCustomFields();
        $this->import(new AssetImporter($csv));

        $this->tester->seeRecord('users', [
            'first_name' => 'Bonnie',
            'last_name' => 'Nelson',
            'email' => 'bnelson0@cdbaby.com',
        ]);
        $this->tester->seeRecord('categories', [
            'name' => 'quam'
        ]);

        $this->tester->seeRecord('models', [
            'name' => 'massa id',
            'model_number' => 6377018600094472
        ]);

        $this->tester->seeRecord('manufacturers', [
            'name' => 'Linkbridge'
        ]);

        $this->tester->seeRecord('locations', [
            'name' => 'Daping'
        ]);

        $this->tester->seeRecord('companies', [
            'name' => 'Alpha'
        ]);

        $this->tester->seeRecord('status_labels', [
            'name' => 'Undeployable'
        ]);

        $this->tester->seeRecord('suppliers', [
            'name' => 'Blogspan'
        ]);

        $this->tester->seeRecord('assets', [
            'name' => 'eget nunc donec quis',
            'serial' => '27aa8378-b0f4-4289-84a4-405da95c6147',
            'asset_tag' => '970882174-8',
            'notes' => "Curabitur in libero ut massa volutpat convallis. Morbi odio odio, elementum eu, interdum eu, tincidunt in, leo. Maecenas pulvinar lobortis est.",
            'purchase_date' => '2016-04-05 00:00:01',
            'purchase_cost' => 133289.59,
            'warranty_months' => 14,
            '_snipeit_weight_2' => 35
            ]);
    }

    public function testUpdateAssetIncludingCustomFields()
    {
        $csv = <<<'EOT'
Name,Email,Username,item Name,Category,Model name,Manufacturer,Model Number,Serial,Asset Tag,Location,Notes,Purchase Date,Purchase Cost,Company,Status,Warranty,Supplier,weight
Bonnie Nelson,bnelson0@cdbaby.com,bnelson0,eget nunc donec quis,quam,massa id,Linkbridge,6377018600094472,27aa8378-b0f4-4289-84a4-405da95c6147,970882174-8,Daping,"Curabitur in libero ut massa volutpat convallis. Morbi odio odio, elementum eu, interdum eu, tincidunt in, leo. Maecenas pulvinar lobortis est.",2016-04-05,133289.59,Alpha,Undeployable,14,Blogspan,95
EOT;

        $this->initializeCustomFields();
        $this->import(new AssetImporter($csv));
        $updatedCSV = <<<'EOT'
item Name,Category,Model name,Manufacturer,Model Number,Serial,Asset Tag,Location,Notes,Purchase Date,Purchase Cost,Company,Status,Warranty,Supplier
A new name,some other category,Another Model,Linkbridge 32,356,67433477,970882174-8,New Location,I have no notes,2018-04-05,25.59,Another Company,Ready To Go,18,Not Creative
EOT;
        $importer = new AssetImporter($updatedCSV);
        $importer->setUserId(1)
             ->setUpdating(true)
             ->setUsernameFormat('firstname.lastname')
             ->import();

        $this->tester->seeRecord('categories', [
            'name' => 'some other category'
        ]);

        $this->tester->seeRecord('models', [
            'name' => 'Another Model',
            'model_number' => 356
        ]);

        $this->tester->seeRecord('manufacturers', [
            'name' => 'Linkbridge 32'
        ]);

        $this->tester->seeRecord('locations', [
            'name' => 'New Location'
        ]);

        $this->tester->seeRecord('companies', [
            'name' => 'Another Company'
        ]);

        $this->tester->seeRecord('status_labels', [
            'name' => 'Ready To Go'
        ]);

        $this->tester->seeRecord('suppliers', [
            'name' => 'Not Creative'
        ]);

        $this->tester->seeRecord('assets', [
            'name' => 'A new name',
            'serial' => '67433477',
            'asset_tag' => '970882174-8',
            'notes' => "I have no notes",
            'purchase_date' => '2018-04-05 00:00:01',
            'purchase_cost' => 25.59,
            'warranty_months' => 18,
            '_snipeit_weight_2' => 95
        ]);
    }

    public function initializeCustomFields()
    {
            $customField = factory(App\Models\CustomField::class)->create(['name' => 'Weight']);
            $customFieldSet = factory(App\Models\CustomFieldset::class)->create(['name' => 'Default']);
            $customFieldSet->fields()->attach($customField, [
                'required' => false,
                'order' => 'asc']);

            $am = factory(App\Models\AssetModel::class)->create([
                'name' => 'massa id',
                'fieldset_id' => $customFieldSet->id
            ]);
    }

    public function testCustomMappingImport()
    {
        $csv = <<<'EOT'
Name,Email,Username,object name,Cat,Model name,Manufacturer,Model Number,Serial,Asset,Loc,Some Notes,Purchase Date,Purchase Cost,comp,Status,Warranty,Supplier
Bonnie Nelson,bnelson0@cdbaby.com,bnelson0,eget nunc donec quis,quam,massa id,Linkbridge,6377018600094472,27aa8378-b0f4-4289-84a4-405da95c6147,970882174-8,Daping,"Curabitur in libero ut massa volutpat convallis. Morbi odio odio, elementum eu, interdum eu, tincidunt in, leo. Maecenas pulvinar lobortis est.",2016-04-05,133289.59,Alpha,Undeployable,14,Blogspan
EOT;

        $customFieldMap = [
            'asset_tag' => 'Asset',
            'category' => 'Cat',
            'company' => 'comp',
            'item_name' => 'object name',
            'expiration_date' => 'expiration date',
            'location' => 'loc',
            'notes' => 'Some Notes',
            'asset_model' => "model name",
        ];

        $this->import(new AssetImporter($csv), $customFieldMap);
        // Did we create a user?

        $this->tester->seeRecord('users', [
            'first_name' => 'Bonnie',
            'last_name' => 'Nelson',
            'email' => 'bnelson0@cdbaby.com',
        ]);
        // Grab the user record for use in asserting assigned_to
        $createdUser = $this->tester->grabRecord('users', [
            'first_name' => 'Bonnie',
            'last_name' => 'Nelson',
            'email' => 'bnelson0@cdbaby.com',
        ]);

        $this->tester->seeRecord('categories', [
            'name' => 'quam'
        ]);

        $this->tester->seeRecord('models', [
            'name' => 'massa id',
            'model_number' => 6377018600094472
        ]);

        $this->tester->seeRecord('manufacturers', [
            'name' => 'Linkbridge'
        ]);

        $this->tester->seeRecord('locations', [
            'name' => 'Daping'
        ]);

        $this->tester->seeRecord('companies', [
            'name' => 'Alpha'
        ]);

        $this->tester->seeRecord('status_labels', [
            'name' => 'Undeployable'
        ]);

        $this->tester->seeRecord('suppliers', [
            'name' => 'Blogspan'
        ]);

        $this->tester->seeRecord('assets', [
            'name' => 'eget nunc donec quis',
            'serial' => '27aa8378-b0f4-4289-84a4-405da95c6147',
            'asset_tag' => '970882174-8',
            'notes' => "Curabitur in libero ut massa volutpat convallis. Morbi odio odio, elementum eu, interdum eu, tincidunt in, leo. Maecenas pulvinar lobortis est.",
            'purchase_date' => '2016-04-05 00:00:01',
            'purchase_cost' => 133289.59,
            'warranty_months' => 14,
            'assigned_to' => $createdUser['id'],
            'assigned_type' => User::class
        ]);
    }

    public function testDefaultAccessoryImport()
    {
        $csv = <<<'EOT'
Item Name,Purchase Date,Purchase Cost,Location,Company,Order Number,Category,Requestable,Quantity
Walter Carter,09/01/2006,,metus. Vivamus,Macromedia,J935H60W,Customers,False,278
EOT;
        $this->import(new AccessoryImporter($csv));
        $this->tester->seeRecord('accessories', [
            'name' => 'Walter Carter',
            'purchase_date' => '2006-09-01 00:00:01',
            'order_number' => 'J935H60W',
            'requestable' => 0,
            'qty' => 278
        ]);

        $this->tester->seeRecord('locations', [
            'name' => 'metus. Vivamus'
        ]);

        $this->tester->seeRecord('companies', [
            'name' => 'Macromedia'
        ]);

        $this->tester->seeRecord('categories', [
            'name' => 'Customers'
        ]);
    }

    public function testDefaultAccessoryUpdate()
    {
        $csv = <<<'EOT'
Item Name,Purchase Date,Purchase Cost,Location,Company,Order Number,Category,Requestable,Quantity
Walter Carter,09/01/2006,,metus. Vivamus,Macromedia,J935H60W,Customers,False,278
EOT;
        $this->import(new AccessoryImporter($csv));
        $this->tester->seeNumRecords(1, 'accessories');


        $updatedCSV = <<<'EOT'
Item Name,Purchase Date,Purchase Cost,Location,Company,Order Number,Category,Requestable,Quantity
Walter Carter,09/01/2015,350,metus. Vivamus,Macromedia,35GGH,Customers,True,12
EOT;
        $importer = new AccessoryImporter($updatedCSV);
        $importer->setUserId(1)
             ->setUpdating(true)
             ->import();
        // At this point we should still only have one record.
        $this->tester->seeNumRecords(1, 'accessories');
        // But instead these.
        $this->tester->seeRecord('accessories', [
            'name' => 'Walter Carter',
            'purchase_date' => '2015-09-01 00:00:01',
            'order_number' => '35GGH',
            'requestable' => 1,
            'qty' => 12
        ]);
    }

    public function testCustomAccessoryImport()
    {
        $csv = <<<'EOT'
Name,Pur Date,Cost,Loc,Comp,Order Num,Cat,Request,Quan
Walter Carter,09/01/2006,,metus. Vivamus,Macromedia,J935H60W,Customers,False,278
EOT;

        $customFieldMap = [
            'category' => 'Cat',
            'company' => 'Comp',
            'item_name' => 'Name',
            'location' => 'Loc',
            'purchase_date' => 'Pur Date',
            'purchase_cost' => "Cost",
            'order_number' => 'Order Num',
            'requestable' => 'Request',
            'quantity' => 'Quan'
        ];
        $this->import(new AccessoryImporter($csv), $customFieldMap);
        // dd($this->tester->grabRecord('accessories'));
        $this->tester->seeRecord('accessories', [
            'name' => 'Walter Carter',
            'purchase_date' => '2006-09-01 00:00:01',
            'order_number' => 'J935H60W',
            'requestable' => 0,
            'qty' => 278
        ]);

        $this->tester->seeRecord('locations', [
            'name' => 'metus. Vivamus'
        ]);

        $this->tester->seeRecord('companies', [
            'name' => 'Macromedia'
        ]);

        $this->tester->seeRecord('categories', [
            'name' => 'Customers'
        ]);
    }

    public function testDefaultConsumableImport()
    {
        $csv = <<<'EOT'
Item Name,Purchase Date,Purchase Cost,Location,Company,Order Number,Category,Requestable,Quantity
eget,01/03/2011,$85.91,mauris blandit mattis.,Lycos,T295T06V,Triamterene/Hydrochlorothiazide,No,322
EOT;
        $this->import(new ConsumableImporter($csv));

        $this->tester->seeRecord('consumables', [
            'name' => 'eget',
            'purchase_date' => '2011-01-03 00:00:01',
            'purchase_cost' => 85.91,
            'order_number' => 'T295T06V',
            'requestable' => 0,
            'qty' => 322
        ]);

        $this->tester->seeRecord('locations', [
            'name' => 'mauris blandit mattis.'
        ]);

        $this->tester->seeRecord('companies', [
            'name' => 'Lycos'
        ]);

        $this->tester->seeRecord('categories', [
            'name' => 'Triamterene/Hydrochlorothiazide'
        ]);
    }

    public function testDefaultConsumableUpdate()
    {
        $csv = <<<'EOT'
Item Name,Purchase Date,Purchase Cost,Location,Company,Order Number,Category,Requestable,Quantity
eget,01/03/2011,85.91,mauris blandit mattis.,Lycos,T295T06V,Triamterene/Hydrochlorothiazide,No,322
EOT;
        $this->import(new ConsumableImporter($csv));
        $this->tester->seeNumRecords(1, 'consumables');


        $updatedCSV = <<<'EOT'
Item Name,Purchase Date,Purchase Cost,Location,Company,Order Number,Category,Requestable,Quantity
eget,12/05/2016,35.45,mauris blandit mattis.,Lycos,3666FF,New Cat,Yes,15
EOT;
        $importer = new ConsumableImporter($updatedCSV);
        $importer->setUserId(1)
             ->setUpdating(true)
             ->import();
        // At this point we should still only have one record.
        $this->tester->seeNumRecords(1, 'consumables');
        // But instead these.
        $this->tester->seeRecord('consumables', [
            'name' => 'eget',
            'purchase_date' => '2016-12-05 00:00:01',
            'purchase_cost' => 35.45,
            'order_number' => '3666FF',
            'requestable' => 1,
            'qty' => 15
        ]);
    }

    public function testCustomConsumableImport()
    {
        $csv = <<<'EOT'
Name,pur Date,Pur Cost,Loc,Comp,Order Num,Kat,Request,Quan
eget,01/03/2011,85.91,mauris blandit mattis.,Lycos,T295T06V,Triamterene/Hydrochlorothiazide,No,322
EOT;

        $customFieldMap = [
            'category' => 'Kat',
            'company' => 'Comp',
            'item_name' => 'Name',
            'location' => 'Loc',
            'purchase_date' => 'Pur date',
            'purchase_cost' => "Pur Cost",
            'order_number' => 'Order Num',
            'requestable' => 'Request',
            'quantity' => 'Quan'
        ];
        $this->import(new ConsumableImporter($csv), $customFieldMap);
        $this->tester->seeRecord('consumables', [
            'name' => 'eget',
            'purchase_date' => '2011-01-03 00:00:01',
            'purchase_cost' => 85.91,
            'order_number' => 'T295T06V',
            'requestable' => 0,
            'qty' => 322
        ]);

        $this->tester->seeRecord('locations', [
            'name' => 'mauris blandit mattis.'
        ]);

        $this->tester->seeRecord('companies', [
            'name' => 'Lycos'
        ]);

        $this->tester->seeRecord('categories', [
            'name' => 'Triamterene/Hydrochlorothiazide'
        ]);
    }

    public function testDefaultLicenseImport()
    {
        $csv = <<<'EOT'
Name,Email,Username,Item name,serial,manufacturer,purchase date,purchase cost,purchase order,order number,Licensed To Name,Licensed to Email,expiration date,maintained,reassignable,seats,company,supplier,notes
Helen Anderson,cspencer0@privacy.gov.au,cspencer0,Argentum Malachite Athletes Foot Relief,1aa5b0eb-79c5-40b2-8943-5472a6893c3c,"Beer, Leannon and Lubowitz",07/13/2012,$79.66,53008,386436062-5,Cynthia Spencer,cspencer0@gov.uk,01/27/2016,false,no,80,"Haag, Schmidt and Farrell","Hegmann, Mohr and Cremin",Sed ante. Vivamus tortor. Duis mattis egestas metus.
EOT;
        $this->import(new LicenseImporter($csv));
        // dd($this->tester->grabRecord('licenses'));

        $this->tester->seeRecord('licenses', [
            'name' => 'Argentum Malachite Athletes Foot Relief',
            'purchase_date' => '2012-07-13 00:00:01',
            'seats' => 80,
            'license_email' => 'cspencer0@gov.uk',
            'order_number' => '386436062-5',
            'license_name' => 'Cynthia Spencer',
            'expiration_date' => '2016-01-27',
            'maintained' => 0,
            'notes' => 'Sed ante. Vivamus tortor. Duis mattis egestas metus.',
            'purchase_cost' => 79.66,
            'purchase_order' => "53008",
            'reassignable' => 0,
            'serial' => '1aa5b0eb-79c5-40b2-8943-5472a6893c3c',
        ]);

        $this->tester->seeRecord('manufacturers', [
            'name' => 'Beer, Leannon and Lubowitz'
        ]);

        $this->tester->seeRecord('suppliers', [
            'name' => "Hegmann, Mohr and Cremin",
        ]);

        $this->tester->seeRecord('companies', [
            'name' => 'Haag, Schmidt and Farrell'
        ]);

        $this->tester->seeNumRecords(80, 'license_seats');
    }

    public function testDefaultLicenseUpdate()
    {
        $csv = <<<'EOT'
Name,Email,Username,Item name,serial,manufacturer,purchase date,purchase cost,purchase order,order number,Licensed To Name,Licensed to Email,expiration date,maintained,reassignable,seats,company,supplier,notes
Helen Anderson,cspencer0@privacy.gov.au,cspencer0,Argentum Malachite Athletes Foot Relief,1aa5b0eb-79c5-40b2-8943-5472a6893c3c,"Beer, Leannon and Lubowitz",07/13/2012,$79.66,53008,386436062-5,Cynthia Spencer,cspencer0@gov.uk,01/27/2016,false,no,80,"Haag, Schmidt and Farrell","Hegmann, Mohr and Cremin",Sed ante. Vivamus tortor. Duis mattis egestas metus.
EOT;
        $this->import(new LicenseImporter($csv));
        $this->tester->seeNumRecords(1, 'licenses');


        $updatedCSV = <<<'EOT'
Item name,serial,manufacturer,purchase date,purchase cost,purchase order,order number,Licensed To Name,Licensed to Email,expiration date,maintained,reassignable,seats,company,supplier,notes
Argentum Malachite Athletes Foot Relief,1aa5b0eb-79c5-40b2-8943-5472a6893c3c,"Beer, Leannon and Lubowitz",05/15/2019,$1865.34,63 ar,18334,A Legend,Legendary@gov.uk,04/27/2016,yes,true,64,"Haag, Schmidt and Farrell","Hegmann, Mohr and Cremin",Sed ante. Vivamus tortor. Duis mattis egestas metus.
EOT;
        $importer = new LicenseImporter($updatedCSV);
        $importer->setUserId(1)
             ->setUpdating(true)
             ->import();
        // At this point we should still only have one record.
        $this->tester->seeNumRecords(1, 'licenses');
        // But instead these.
        // dd($this->tester->grabRecord('licenses'));
        $this->tester->seeRecord('licenses', [
            'name' => 'Argentum Malachite Athletes Foot Relief',
            'purchase_date' => '2019-05-15 00:00:01',
            'seats' => 64,
            'license_email' => 'Legendary@gov.uk',
            'order_number' => '18334',
            'license_name' => 'A Legend',
            'expiration_date' => '2016-04-27',
            'maintained' => 1,
            'notes' => 'Sed ante. Vivamus tortor. Duis mattis egestas metus.',
            'purchase_cost' => 1865.34,
            'purchase_order' => "63 ar",
            'reassignable' => 1,
            'serial' => '1aa5b0eb-79c5-40b2-8943-5472a6893c3c',
        ]);
        // License seats are soft deleted
        $this->tester->seeNumRecords(64, 'license_seats', ['deleted_at' => null]);
    }

    public function testCustomLicenseImport()
    {
        $csv = <<<'EOT'
Name,Email,Username,Object name,serial num,manuf,pur date,pur cost,purc order,order num,Licensed To,Licensed Email,expire date,maint,reass,seat,comp,supplier,note
Helen Anderson,cspencer0@privacy.gov.au,cspencer0,Argentum Malachite Athletes Foot Relief,1aa5b0eb-79c5-40b2-8943-5472a6893c3c,"Beer, Leannon and Lubowitz",07/13/2012,$79.66,53008,386436062-5,Cynthia Spencer,cspencer0@gov.uk,01/27/2016,false,no,80,"Haag, Schmidt and Farrell","Hegmann, Mohr and Cremin",Sed ante. Vivamus tortor. Duis mattis egestas metus.
EOT;

        $customFieldMap = [
            'company' => 'Comp',
            'expiration_date' => 'expire date',
            'item_name' => 'Object Name',
            'license_email' => 'licensed email',
            'license_name' => 'licensed to',
            'maintained' => 'maint',
            'manufacturer' => 'manuf',
            'notes' => 'note',
            'order_number' => 'Order Num',
            'purchase_cost' => "Pur Cost",
            'purchase_date' => 'Pur date',
            'purchase_order' => "Purc Order",
            'quantity' => 'Quan',
            'reassignable' => 'reass',
            'requestable' => 'Request',
            'seats' => 'seat',
            'serial' => 'serial num',
        ];
        $this->import(new LicenseImporter($csv), $customFieldMap);
        // dd($this->tester->grabRecord('licenses'));
        $this->tester->seeRecord('licenses', [
            'name' => 'Argentum Malachite Athletes Foot Relief',
            'purchase_date' => '2012-07-13 00:00:01',
            'seats' => 80,
            'license_email' => 'cspencer0@gov.uk',
            'order_number' => '386436062-5',
            'license_name' => 'Cynthia Spencer',
            'expiration_date' => '2016-01-27',
            'maintained' => 0,
            'notes' => 'Sed ante. Vivamus tortor. Duis mattis egestas metus.',
            'purchase_cost' => 79.66,
            'purchase_order' => "53008",
            'reassignable' => 0,
            'serial' => '1aa5b0eb-79c5-40b2-8943-5472a6893c3c',
        ]);

        $this->tester->seeRecord('manufacturers', [
            'name' => 'Beer, Leannon and Lubowitz'
        ]);

        $this->tester->seeRecord('suppliers', [
            'name' => "Hegmann, Mohr and Cremin",
        ]);

        $this->tester->seeRecord('companies', [
            'name' => 'Haag, Schmidt and Farrell'
        ]);

        $this->tester->seeNumRecords(80, 'license_seats');
    }

    private function import($importer, $mappings = null)
    {
        if ($mappings) {
            $importer->setFieldMappings($mappings);
        }
        $importer->setUserId(1)
             ->setUpdating(false)
             ->setUsernameFormat('firstname.lastname')
             ->import();
    }
}
