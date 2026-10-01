<?php
/**
 * Images: resized copies are saved as WebP (smaller files), when the server can write WebP.
 * Originals stay as uploaded.
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
