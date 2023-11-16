<?php

// Add a callback function for the wsf_expire_user action hook
add_action( 'wsf_expire_user', 'wsf_expire_user_function', 10, 2 );

// Callback function for the wsf_submit_export_csv_header filter hook
function wsf_expire_user_function( $form, $submit ) {

	if (
		// Check Expire Users plugin is installed
		!class_exists( 'Expire_Users' ) ||

		// Check user ID is present
		!is_object( $submit ) ||
		!property_exists( $submit, 'user_id' )
	) {
		return;
	}

	// Initiate an Expire_User_Settings instance
	$expire_user_settings = new Expire_User_Settings();

  // Get the expire settings from the Expire Users plugin
	$expire_settings = $expire_user_settings->get_default_expire_settings();

	// Build expire data
	$expire_data_keys = array(

		'expire_user_date_type' => 'expire_user_date_type',
		'expire_user_date_in_num' => 'expire_user_date_in_num',
		'expire_user_date_in_block' => 'expire_user_date_in_block',
		'expire_user_date_on_timestamp' => 'expire_timestamp',
		'expire_user_role' => 'expire_user_role',
		'expire_user_reset_password' => 'expire_user_reset_password',
		'expire_user_email' => 'expire_user_email',
		'expire_user_email_admin' => 'expire_user_email_admin',
		'expire_user_remove_expiry' => 'expire_user_remove_expiry'
	);

	$expire_data = array();

	foreach ( $expire_data_keys as $key_settings => $key_data ) {

		if ( isset( $expire_settings[$key_settings] ) ) { $expire_data[$key_data] = $expire_settings[$key_settings]; }
	}

	// Initiate an Expire_User instance
	$user = new Expire_User( absint( $submit->user_id ) );

	// Set the expire data
	$user->set_expire_data( $expire_data );

	// Save the user
	$user->save_user();
}
