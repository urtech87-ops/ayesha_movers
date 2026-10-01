<?php
/**
 * Sender for emails the site sends (instead of wordpress@localhost).
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'wp_mail_from', 'ayesha_core_mail_from' );
add_filter( 'wp_mail_from_name', 'ayesha_core_mail_from_name' );

/**
 * From address: the Business Info email, when valid.
 *
 * @param string $from Default from address.
 * @return string
 */
function ayesha_core_mail_from( $from ) {
	$email = (string) ayesha_core_setting( 'email' );
	return is_email( $email ) ? $email : $from;
}

/**
 * From name.
 *
 * @param string $name Default from name.
 * @return string
 */
function ayesha_core_mail_from_name( $name ) {
	return is_email( (string) ayesha_core_setting( 'email' ) ) ? AYESHA_CORE_BUSINESS_NAME : $name;
}
