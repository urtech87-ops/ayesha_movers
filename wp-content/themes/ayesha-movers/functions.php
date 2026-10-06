<?php
/**
 * AYESHA Movers theme.
 *
 * Presentation only. Business data and logic live in the AYESHA Core plugin.
 *
 * @package AyeshaMovers
 */

defined( 'ABSPATH' ) || exit;

define( 'AYESHA_THEME_VERSION', wp_get_theme( 'ayesha-movers' )->get( 'Version' ) );

require_once get_theme_file_path( 'inc/pattern-parts.php' );

add_action( 'after_setup_theme', 'ayesha_theme_setup' );

/**
 * Theme supports and editor styles.
 */
function ayesha_theme_setup() {
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/theme.css' );
	remove_theme_support( 'core-block-patterns' );
	// Photos are shown at their natural size (the originals are 640px wide): one 560px copy for the Home hero.
	add_image_size( 'ayesha-photo', 560, 0 );
}

add_filter( 'image_size_names_choose', 'ayesha_theme_image_size_names' );

/**
 * Offer the 560px copy in the Image block's size menu.
 *
 * @param array<string, string> $sizes Size slug => label.
 * @return array<string, string>
 */
function ayesha_theme_image_size_names( $sizes ) {
	$sizes['ayesha-photo'] = __( 'Photo (560px)', 'ayesha-movers' );
	return $sizes;
}

/**
 * Asset version: theme version plus the file's modified time, so browsers fetch edited files.
 *
 * @param string $file Path relative to the theme.
 * @return string
 */
function ayesha_theme_asset_version( $file ) {
	$path = get_theme_file_path( $file );
	return AYESHA_THEME_VERSION . ( file_exists( $path ) ? '.' . filemtime( $path ) : '' );
}

add_action( 'wp_enqueue_scripts', 'ayesha_theme_assets' );

/**
 * Front-end CSS and the tiny sticky-bar script (deferred, no jQuery).
 */
function ayesha_theme_assets() {
	wp_enqueue_style( 'ayesha-theme', get_theme_file_uri( 'assets/css/theme.css' ), array(), ayesha_theme_asset_version( 'assets/css/theme.css' ) );
	wp_enqueue_script(
		'ayesha-sticky-bar',
		get_theme_file_uri( 'assets/js/sticky-bar.js' ),
		array(),
		ayesha_theme_asset_version( 'assets/js/sticky-bar.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	// Our Services: marks the current service in the "Jump to a service" menu. Only on pages that have it.
	if ( is_singular() && str_contains( (string) get_post_field( 'post_content', get_queried_object_id() ), 'ayesha-jump' ) ) {
		wp_enqueue_script(
			'ayesha-service-rail',
			get_theme_file_uri( 'assets/js/service-rail.js' ),
			array(),
			ayesha_theme_asset_version( 'assets/js/service-rail.js' ),
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}
}

add_action( 'wp_head', 'ayesha_theme_head', -1 );

/**
 * Viewport with viewport-fit=cover (so the sticky bar can use the safe-area inset)
 * and a preload for the body font (Roboto 400; 500 and 700 load when used).
 */
function ayesha_theme_head() {
	remove_action( 'wp_head', '_block_template_viewport_meta_tag', 0 );
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />' . "\n";
	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( get_theme_file_uri( 'assets/fonts/roboto-latin-400.woff2' ) )
	);
}

add_action( 'init', 'ayesha_theme_register_styles_and_patterns' );

/**
 * Block styles (every one passes WCAG AA) and the pattern category.
 */
function ayesha_theme_register_styles_and_patterns() {
	$styles = array(
		'core/group'     => array(
			'teal-panel'     => __( 'Navy panel', 'ayesha-movers' ),
			'yellow-panel'   => __( 'Gold panel', 'ayesha-movers' ),
			'concrete-panel' => __( 'Light grey panel', 'ayesha-movers' ),
		),
		'core/button'    => array(
			'whatsapp'  => __( 'WhatsApp green', 'ayesha-movers' ),
			'yellow'    => __( 'Gold', 'ayesha-movers' ),
			'text-link' => __( 'Text link', 'ayesha-movers' ),
		),
		'core/paragraph' => array(
			'display-number' => __( 'Big phone number', 'ayesha-movers' ),
		),
		'core/spacer'    => array(
			'chevron-strip' => __( 'Gold line', 'ayesha-movers' ),
		),
	);
	foreach ( $styles as $block => $block_styles ) {
		foreach ( $block_styles as $name => $label ) {
			register_block_style(
				$block,
				array(
					'name'  => $name,
					'label' => $label,
				)
			);
		}
	}

	register_block_pattern_category(
		'ayesha-movers',
		array(
			'label'       => __( 'Ayesha Movers', 'ayesha-movers' ),
			'description' => __( 'Sections for the AYESHA Movers & Packers pages.', 'ayesha-movers' ),
		)
	);
}

/**
 * ID of a published synced pattern (Patterns > My patterns), found by its slug.
 * The CTA band and "Where we go" pattern files insert this synced copy when it exists,
 * so editing it once updates every page; on a fresh site they insert the plain blocks.
 *
 * @param string $slug Post slug of the synced pattern (wp_block).
 * @return int 0 when there is none.
 */
function ayesha_theme_synced_pattern_id( $slug ) {
	$post = get_page_by_path( $slug, OBJECT, 'wp_block' );
	return ( $post && 'publish' === $post->post_status ) ? (int) $post->ID : 0;
}

add_filter( 'render_block_core/image', 'ayesha_theme_lazy_footer_logo', 10, 2 );

/**
 * The footer logo is a theme file (no Media Library size), so WordPress doesn't lazy-load it.
 * It is far below the first screen: load it lazily. A logo picked from the Media Library
 * instead already gets this from WordPress.
 *
 * @param string $html  Rendered block.
 * @param array  $block Parsed block.
 * @return string
 */
function ayesha_theme_lazy_footer_logo( $html, $block ) {
	if ( ! str_contains( $block['attrs']['className'] ?? '', 'ayesha-footer__logo' ) ) {
		return $html;
	}
	$tags = new WP_HTML_Tag_Processor( $html );
	if ( $tags->next_tag( 'img' ) && null === $tags->get_attribute( 'loading' ) ) {
		$tags->set_attribute( 'loading', 'lazy' );
		$html = $tags->get_updated_html();
	}
	return $html;
}
