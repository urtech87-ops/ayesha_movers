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
 * At most one content image skips lazy loading (WordPress's default is 3), and only when it is in
 * the page's first section, i.e. on screen when the page opens (the Home hero photo, the About Us
 * photo). Every other image gets loading="lazy", so an image hidden on phones (e.g. the photo
 * under the house-shifting card) is never downloaded there, and a photo far down a page (the
 * truck on Our Services) doesn't compete with the top of the page.
 *
 * @return int
 */
function ayesha_core_eager_image_count() {
	static $count = null;
	if ( null === $count ) {
		$count = 1;
		if ( is_singular() ) {
			$blocks = array_values( array_filter( parse_blocks( (string) get_post_field( 'post_content', get_queried_object_id() ) ), static fn( $block ) => null !== $block['blockName'] ) );
			$count  = ( $blocks && ayesha_core_block_has_image( $blocks[0] ) ) ? 1 : 0;
		}
	}
	return $count;
}

/**
 * Whether a parsed block is, or contains, an Image block.
 *
 * @param array $block Parsed block.
 * @return bool
 */
function ayesha_core_block_has_image( $block ) {
	if ( 'core/image' === $block['blockName'] ) {
		return true;
	}
	foreach ( $block['innerBlocks'] as $inner ) {
		if ( ayesha_core_block_has_image( $inner ) ) {
			return true;
		}
	}
	return false;
}
