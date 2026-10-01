<?php
/**
 * Search-engine title and description per page, edited in the block editor sidebar
 * (Page → "Search engines" panel). No SEO plugin.
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

const AYESHA_CORE_SEO_TITLE       = '_ayesha_seo_title';
const AYESHA_CORE_SEO_DESCRIPTION = '_ayesha_seo_description';

add_action( 'init', 'ayesha_core_register_seo_meta' );

/**
 * Register the two fields on pages and posts, editable through the REST API by people who can edit the post.
 */
function ayesha_core_register_seo_meta() {
	foreach ( array( 'page', 'post' ) as $post_type ) {
		foreach ( array( AYESHA_CORE_SEO_TITLE, AYESHA_CORE_SEO_DESCRIPTION ) as $key ) {
			register_post_meta(
				$post_type,
				$key,
				array(
					'type'              => 'string',
					'single'            => true,
					'default'           => '',
					'show_in_rest'      => true,
					'sanitize_callback' => 'ayesha_core_sanitize_seo_text',
					'auth_callback'     => static function ( $allowed, $meta_key, $post_id ) {
						return current_user_can( 'edit_post', $post_id );
					},
				)
			);
		}
	}
}

/**
 * One line of plain text.
 *
 * @param mixed $value Raw value.
 * @return string
 */
function ayesha_core_sanitize_seo_text( $value ) {
	return trim( sanitize_text_field( (string) $value ) );
}

/**
 * The SEO field for the page being viewed, or ''.
 *
 * @param string $key Meta key.
 * @return string
 */
function ayesha_core_seo_value( $key ) {
	if ( ! is_singular() ) {
		return '';
	}
	return (string) get_post_meta( get_queried_object_id(), $key, true );
}

add_filter( 'pre_get_document_title', 'ayesha_core_seo_document_title', 20 );

/**
 * Use the page's search-engine title when one is set.
 *
 * @param string $title Title so far ('' = let WordPress build it).
 * @return string
 */
function ayesha_core_seo_document_title( $title ) {
	$custom = ayesha_core_seo_value( AYESHA_CORE_SEO_TITLE );
	return '' === $custom ? $title : esc_html( $custom );
}

add_action( 'wp_head', 'ayesha_core_seo_meta_description', 2 );

/**
 * Meta description, when one is set.
 */
function ayesha_core_seo_meta_description() {
	$description = ayesha_core_seo_value( AYESHA_CORE_SEO_DESCRIPTION );
	if ( '' !== $description ) {
		printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $description ) );
	}
}

add_action( 'enqueue_block_editor_assets', 'ayesha_core_seo_editor_assets' );

/**
 * The "Search engines" panel in the page editor's sidebar (not the Site Editor).
 */
function ayesha_core_seo_editor_assets() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'post' !== $screen->base || ! in_array( $screen->post_type, array( 'page', 'post' ), true ) ) {
		return;
	}
	wp_enqueue_script(
		'ayesha-core-seo-panel',
		AYESHA_CORE_URL . 'assets/js/seo-panel.js',
		array( 'wp-plugins', 'wp-editor', 'wp-components', 'wp-data', 'wp-element', 'wp-i18n' ),
		AYESHA_CORE_VERSION,
		true
	);
}
