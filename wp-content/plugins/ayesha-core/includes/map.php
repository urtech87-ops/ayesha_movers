<?php
/**
 * Block "ayesha/map": a Google map of the business address from Business Info, so the address is
 * typed once (the search-engine data uses the same field). The map is lazy-loaded (it sits below
 * the first screen) and nothing is shown while the address is empty.
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'ayesha_core_register_map_block' );

/**
 * Register the block and its editor script.
 */
function ayesha_core_register_map_block() {
	$dir = AYESHA_CORE_DIR . 'blocks/map/';
	wp_register_script(
		'ayesha-map-editor',
		AYESHA_CORE_URL . 'blocks/map/editor.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-i18n' ),
		AYESHA_CORE_VERSION . '.' . filemtime( $dir . 'editor.js' ),
		true
	);
	register_block_type( $dir, array( 'render_callback' => 'ayesha_core_map_render' ) );
}

/**
 * The address on one line ("Al Mutanabi Ave, Manama, Bahrain"), as typed in Business Info.
 *
 * @return string
 */
function ayesha_core_map_address() {
	return trim( (string) preg_replace( '/\s*\R\s*/', ', ', trim( (string) ayesha_core_setting( 'address' ) ) ) );
}

/**
 * Render the map.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function ayesha_core_map_render( $attributes ) {
	$address = ayesha_core_map_address();
	if ( '' === $address ) {
		return '';
	}
	$zoom = max( 8, min( 19, (int) ( $attributes['zoom'] ?? 15 ) ) );
	$src  = 'https://maps.google.com/maps?q=' . rawurlencode( $address ) . '&z=' . $zoom . '&t=m&output=embed';
	return sprintf(
		'<div %1$s><iframe class="ayesha-map__frame" src="%2$s" title="%3$s" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe></div>',
		get_block_wrapper_attributes( array( 'class' => 'ayesha-map' ) ),
		esc_url( $src ),
		/* translators: %s: the business address. */
		esc_attr( sprintf( __( 'Map: %s', 'ayesha-core' ), $address ) )
	);
}
