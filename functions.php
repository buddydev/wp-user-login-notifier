<?php

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Get all settings
 *
 * @return array
 */
function buddydev_wpuln_get_settings() {

	$default = array(
		'notify_admin_on_fail'    => 'yes',
		'notify_admin_on_success' => 'yes',
		'notify_user_on_fail'     => 'yes',
		'notify_user_on_success'  => 'yes',
		'notify_extra_users'      => '', // added in version 1.0.1.
	);

	return get_option( 'wp_user_login_notifier_settings', $default );

}

/**
 * Get individual setting
 *
 * @param string $key option name.
 *
 * @return string
 */
function buddydev_wpuln_get_setting( $key ) {

	$settings = buddydev_wpuln_get_settings();

	if ( isset( $settings[ $key ] ) ) {
		return $settings[ $key ];
	}

	return '';
}

/**
 * Get an array of emails that may need to be notified
 */
function buddydev_wpuln_get_extra_emails() {

	$emails = buddydev_wpuln_get_setting( 'notify_extra_users' );

	if ( ! empty( $emails ) ) {
		$emails = explode( ',', $emails );

		// now trim it.
		$emails = array_map( 'trim', $emails );

		// now filter the list for invalid email.
		$emails = array_filter( $emails, 'is_email' );

	}

	return $emails;
}

/**
 * Get bcc header.
 *
 * @return array
 */
function buddydev_wpuln_get_bcc_header() {

	$headers = array();
	$emails  = buddydev_wpuln_get_extra_emails();

	if ( empty( $emails ) ) {
		return $headers;
	}
	// Loop through users.
	foreach ( $emails as $email ) {
		// add all other users as bcc.
		$headers[] = 'Bcc:' . $email;
	}

	return $headers;
}
