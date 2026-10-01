<?php
/**
 * Block Bindings source "ayesha/business".
 *
 * Usage in block markup:
 *   <!-- wp:button {"metadata":{"bindings":{
 *     "text":{"source":"ayesha/business","args":{"key":"phone_primary"}},
 *     "url":{"source":"ayesha/business","args":{"key":"phone_primary_url"}}}}} -->
 *
 * Works with core/paragraph, core/heading, core/list-item (content) and core/button (text, url).
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'ayesha_core_register_bindings_source' );

/**
 * Register the server-side source.
 */
function ayesha_core_register_bindings_source() {
	if ( ! function_exists( 'register_block_bindings_source' ) ) {
		return;
	}
	register_block_bindings_source(
		'ayesha/business',
		array(
			'label'              => __( 'Business Info', 'ayesha-core' ),
			'get_value_callback' => 'ayesha_core_bindings_value',
		)
	);
}

/**
 * Value callback.
 *
 * @param array    $source_args    Binding args (key, label, message).
 * @param WP_Block $block_instance Block.
 * @param string   $attribute_name Attribute being bound.
 * @return string|null
 */
function ayesha_core_bindings_value( array $source_args, $block_instance, string $attribute_name ) {
	$key = isset( $source_args['key'] ) ? sanitize_key( $source_args['key'] ) : '';
	if ( '' === $key ) {
		return null;
	}
	$value = ayesha_core_value( $key, $source_args );
	if ( null === $value ) {
		return null;
	}

	// Button text is plain text: never put an <a> inside the button's own link.
	if ( 'text' === $attribute_name && 'core/button' === $block_instance->name ) {
		return wp_strip_all_tags( $value );
	}
	$kind = ayesha_core_value_keys()[ $key ]['kind'] ?? 'text';
	// Link targets only accept the *_url keys.
	if ( 'url' === $attribute_name ) {
		return 'url' === $kind ? $value : null;
	}
	// Plain-text values going into rich text must be escaped; HTML values are already safe.
	if ( in_array( $kind, array( 'text', 'url' ), true ) ) {
		return esc_html( $value );
	}
	return $value;
}

add_action( 'enqueue_block_editor_assets', 'ayesha_core_bindings_editor_assets' );

/**
 * Editor: show live values in bound blocks and list the fields in the Attributes panel.
 */
function ayesha_core_bindings_editor_assets() {
	wp_enqueue_script(
		'ayesha-core-bindings-editor',
		AYESHA_CORE_URL . 'assets/js/bindings-editor.js',
		array( 'wp-blocks', 'wp-i18n' ),
		AYESHA_CORE_VERSION,
		true
	);

	$values = array();
	$fields = array();
	foreach ( ayesha_core_value_keys() as $key => $meta ) {
		$values[ $key ] = ayesha_core_value( $key );
		$fields[]       = array(
			'key'   => $key,
			'label' => $meta['label'],
			'kind'  => $meta['kind'],
		);
	}

	wp_add_inline_script(
		'ayesha-core-bindings-editor',
		'window.ayeshaBusiness = ' . wp_json_encode(
			array(
				'values'          => $values,
				'fields'          => $fields,
				'whatsappDigits'  => preg_replace( '/\D/', '', (string) ayesha_core_setting( 'whatsapp' ) ),
				'whatsappMessage' => (string) ayesha_core_setting( 'whatsapp_message' ),
			),
			JSON_HEX_TAG | JSON_HEX_AMP
		) . ';',
		'before'
	);
}
