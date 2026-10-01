<?php
/**
 * Shortcodes (fallback for places where Block Bindings can't be used).
 *
 * [ayesha_phone]                    Main number as a tap-to-call link.
 * [ayesha_phone which="secondary"]  primary | secondary | office.
 * [ayesha_phone link="no"]          Number as plain text.
 * [ayesha_whatsapp_link]            WhatsApp number as a tap-to-chat link.
 * [ayesha_whatsapp_link text="Chat on WhatsApp" message="Hi, I'd like a price for packing."]
 * [ayesha_email]  [ayesha_email link="no"]
 * [ayesha_hours]
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'ayesha_core_register_shortcodes' );

/**
 * Register shortcodes.
 */
function ayesha_core_register_shortcodes() {
	add_shortcode( 'ayesha_phone', 'ayesha_core_shortcode_phone' );
	add_shortcode( 'ayesha_whatsapp_link', 'ayesha_core_shortcode_whatsapp' );
	add_shortcode( 'ayesha_email', 'ayesha_core_shortcode_email' );
	add_shortcode( 'ayesha_hours', 'ayesha_core_shortcode_hours' );
}

/**
 * Whether a shortcode "link" attribute is switched off.
 *
 * @param string $value Attribute value.
 * @return bool
 */
function ayesha_core_shortcode_no_link( $value ) {
	return in_array( strtolower( (string) $value ), array( 'no', 'false', '0' ), true );
}

/**
 * [ayesha_phone].
 *
 * @param array|string $atts Attributes.
 * @return string
 */
function ayesha_core_shortcode_phone( $atts ) {
	$atts  = shortcode_atts(
		array(
			'which' => 'primary',
			'link'  => 'yes',
			'text'  => '',
		),
		$atts,
		'ayesha_phone'
	);
	$which = in_array( $atts['which'], array( 'primary', 'secondary', 'office' ), true ) ? $atts['which'] : 'primary';
	$phone = (string) ayesha_core_value( 'phone_' . $which );
	if ( ayesha_core_shortcode_no_link( $atts['link'] ) ) {
		return esc_html( $phone );
	}
	return ayesha_core_link( ayesha_core_tel_url( $phone ), '' !== $atts['text'] ? $atts['text'] : $phone );
}

/**
 * [ayesha_whatsapp_link].
 *
 * @param array|string $atts Attributes.
 * @return string
 */
function ayesha_core_shortcode_whatsapp( $atts ) {
	$atts = shortcode_atts(
		array(
			'text'    => '',
			'message' => null,
		),
		$atts,
		'ayesha_whatsapp_link'
	);
	$args = array( 'label' => $atts['text'] );
	if ( null !== $atts['message'] ) {
		$args['message'] = $atts['message'];
	}
	return (string) ayesha_core_value( 'whatsapp_link', $args );
}

/**
 * [ayesha_email].
 *
 * @param array|string $atts Attributes.
 * @return string
 */
function ayesha_core_shortcode_email( $atts ) {
	$atts = shortcode_atts(
		array(
			'link' => 'yes',
			'text' => '',
		),
		$atts,
		'ayesha_email'
	);
	if ( ayesha_core_shortcode_no_link( $atts['link'] ) ) {
		return esc_html( (string) ayesha_core_value( 'email' ) );
	}
	return (string) ayesha_core_value( 'email_link', array( 'label' => $atts['text'] ) );
}

/**
 * [ayesha_hours].
 *
 * @return string
 */
function ayesha_core_shortcode_hours() {
	return esc_html( (string) ayesha_core_value( 'hours' ) );
}
