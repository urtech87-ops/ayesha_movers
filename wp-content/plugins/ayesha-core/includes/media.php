<?php
/**
 * Images: resized copies are saved as WebP (smaller files), when the server can write WebP.
 * Originals stay as uploaded. Only the first image on a page loads straight away.
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'image_editor_output_format', 'ayesha_core_webp_sub_sizes' );

/**
 * JPEG and PNG sub-sizes become WebP.
 *
 * @param array<string, string> $formats Source MIME type => output MIME type.
 * @return array<string, string>
 */
function ayesha_core_webp_sub_sizes( $formats ) {
	if ( ! wp_image_editor_supports( array( 'mime_type' => 'image/webp' ) ) ) {
		return $formats;
	}
	$formats['image/jpeg'] = 'image/webp';
	$formats['image/png']  = 'image/webp';
	return $formats;
}

add_filter( 'wp_omit_loading_attr_threshold', 'ayesha_core_eager_image_count' );

/**
 * Only the first content image (the Home hero photo) skips lazy loading; WordPress's default is 3.
 * Every other image gets loading="lazy", so an image hidden on phones (e.g. the photo under the
 * house-shifting card) is never downloaded there.
 *
 * @return int
 */
function ayesha_core_eager_image_count() {
	return 1;
}
