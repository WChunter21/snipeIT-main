<?php

return array(

    'does_not_exist' => 'License does not exist.',
    'user_does_not_exist' => 'User does not exist.',
    'asset_does_not_exist' 	=> 'The asset you are trying to associate with this license does not exist.',
    'owner_doesnt_match_asset' => 'The asset you are trying to associate with this license is owned by somene other than the person selected in the assigned to dropdown.',
    'assoc_users'	 => 'This license is currently checked out to a user and cannot be deleted. Please check the license in first, and then try deleting again. ',


    'create' => array(
        'error'   => 'License was not created, please try again.',
        'success' => 'License created successfully.'
    ),

    'update' => array(
        'error'   => 'License was not updated, please try again',
        'success' => 'License updated successfully.'
    ),

    'delete' => array(
        'confirm'   => 'Are you sure you wish to delete this license?',
        'error'   => 'There was an issue deleting the license. Please try again.',
        'success' => 'The license was deleted successfully.'
    ),

    'checkout' => array(
        'error'   => 'There was an issue checking out the license. Please try again.',
        'success' => 'The license was checked out successfully'
    ),

    'checkin' => array(
        'error'   => 'There was an issue checking in the license. Please try again.',
        'success' => 'The license was checked in successfully'
    ),
    
    'checkout_help'  => 'You must check a license out to a hardware asset or a person. You can select both, but the owner of the asset must match the person you\'re checking the asset out to.',

);
