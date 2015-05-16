<?php
/**
 * Get all settings
 * 
 * @return type
 */
function buddydev_wpuln_get_settings() {
	
	$default = array(
		'notify_admin_on_fail'		=> 'yes',
		'notify_admin_on_success'	=> 'yes',
		'notify_user_on_fail'		=> 'yes',
		'notify_user_on_success'	=> 'yes',
	);

	return get_option( 'wp_user_login_notifier_settings', $default );
	
}
/**
 * Get individual setting
 * 
 * @param type $key
 * @return string
 */
function buddydev_wpuln_get_setting( $key ) {
	
	$settings = buddydev_wpuln_get_settings();
	
	if( isset( $settings[$key] ) )
		return $settings[$key];
	return '';
	
}