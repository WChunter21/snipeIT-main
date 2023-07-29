<?php

namespace App\Observers;

use App\Models\Actionlog;
use App\Models\Asset;
use App\Models\Setting;
use Auth;
use Carbon\Carbon;

class AssetObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  Asset  $asset
     * @return void
     */
    public function updating(Asset $asset)
    {
        $attributes = $asset->getAttributes();
        $attributesOriginal = $asset->getRawOriginal();
        $same_checkout_counter = false;
        $same_checkin_counter = false;

        if (array_key_exists('checkout_counter', $attributes) && array_key_exists('checkout_counter', $attributesOriginal)){
            $same_checkout_counter = (($attributes['checkout_counter'] == $attributesOriginal['checkout_counter']));
        }

        if (array_key_exists('checkin_counter', $attributes)  && array_key_exists('checkin_counter', $attributesOriginal)){
            $same_checkin_counter = (($attributes['checkin_counter'] == $attributesOriginal['checkin_counter']));
        }

        // If the asset isn't being checked out or audited, log the update.
        // (Those other actions already create log entries.)
	if (($attributes['assigned_to'] == $attributesOriginal['assigned_to']) 
	    && ($same_checkout_counter) && ($same_checkin_counter)
            && ((isset( $attributes['next_audit_date']) ? $attributes['next_audit_date'] : null) == (isset($attributesOriginal['next_audit_date']) ? $attributesOriginal['next_audit_date']: null))
            && ($attributes['last_checkout'] == $attributesOriginal['last_checkout']))
        {
            $changed = [];

            foreach ($asset->getRawOriginal() as $key => $value) {
                if ($asset->getRawOriginal()[$key] != $asset->getAttributes()[$key]) {
                    $changed[$key]['old'] = $asset->getRawOriginal()[$key];
                    $changed[$key]['new'] = $asset->getAttributes()[$key];
                }
	    }

	    if (empty($changed)){
	        return;
	    }

            $logAction = new Actionlog();
            $logAction->item_type = Asset::class;
            $logAction->item_id = $asset->id;
            $logAction->created_at = date('Y-m-d H:i:s');
            $logAction->user_id = Auth::id();
            $logAction->log_meta = json_encode($changed);
            $logAction->logaction('update');
        }
    }

    /**
     * Listen to the Asset created event, and increment
     * the next_auto_tag_base value in the settings table when i
     * a new asset is created.
     *
     * @param  Asset  $asset
     * @return void
     */
    public function created(Asset $asset)
    {
        if ($settings = Setting::getSettings()) {
            $settings->increment('next_auto_tag_base');
            $settings->save();
        }

        $logAction = new Actionlog();
        $logAction->item_type = Asset::class;
        $logAction->item_id = $asset->id;
        $logAction->created_at = date('Y-m-d H:i:s');
        $logAction->user_id = Auth::id();
        $logAction->logaction('create');
    }

    /**
     * Listen to the Asset deleting event.
     *
     * @param  Asset  $asset
     * @return void
     */
    public function deleting(Asset $asset)
    {
        $logAction = new Actionlog();
        $logAction->item_type = Asset::class;
        $logAction->item_id = $asset->id;
        $logAction->created_at = date('Y-m-d H:i:s');
        $logAction->user_id = Auth::id();
        $logAction->logaction('delete');
    }
   
    public function saving(Asset $asset)
    {
        ray('THIS FUCKING SHIT DOESNT WORK'); 
        //determine if calculated eol and then calculate it - this should only happen on a new asset
        if(is_null($asset->asset_eol_date) && !is_null($asset->asset_purchase_date) && !is_null($asset->model->eol)){
            $asset->asset_eol_date = $asset->purchase_date->addMonths($asset->model->eol)->format('Y-m-d');  
            $asset->eol_explicit = false; 
        } 

       //determine if explicit and set eol_explit to true 
      //conditions might need more work 
       if(!is_null($asset->asset_eol_date) && !is_null($asset->purchase_date)) { 
            if($asset->model->eol) { 
                $months = Carbon::parse($asset->asset_eol_date)->diffInMonths($asset->purchase_date); 
                if($months != $asset->model->eol) {
                    $asset->eol_explicit = true;
                } 
            } else {
                $asset->eol_explicit = true;
            }
       } 
      

    }
}
