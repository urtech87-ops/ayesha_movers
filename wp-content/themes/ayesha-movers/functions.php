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

add_action( 'after_setup_theme', 'ayesha_theme_setup' );

/**
 * Theme supports and editor styles.
 */
function ayesha_theme_setup() {
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/theme.css' );
	remove_theme_support( 'core-block-patterns' );
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
}

add_action( 'wp_head', 'ayesha_theme_head', -1 );

/**
 * Viewport with viewport-fit=cover (so the sticky bar can use the safe-area inset)
 * and a preload for the single font file.
 */
function ayesha_theme_head() {
	remove_action( 'wp_head', '_block_template_viewport_meta_tag', 0 );
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />' . "\n";
	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( get_theme_file_uri( 'assets/fonts/archivo-latin-var.woff2' ) )
	);
}

add_action( 'init', 'ayesha_theme_register_styles_and_patterns' );

/**
 * Block styles (every one passes WCAG AA) and the pattern category.
 */
function ayesha_theme_register_styles_and_patterns() {
	$styles = array(
		'core/group'     => array(
			'teal-panel'     => __( 'Teal panel', 'ayesha-movers' ),
			'yellow-panel'   => __( 'Yellow panel', 'ayesha-movers' ),
			'concrete-panel' => __( 'Grey panel', 'ayesha-movers' ),
		),
		'core/button'    => array(
			'whatsapp' => __( 'WhatsApp green', 'ayesha-movers' ),
			'yellow'   => __( 'Yellow', 'ayesha-movers' ),
		),
		'core/paragraph' => array(
			'display-number' => __( 'Big phone number', 'ayesha-movers' ),
		),
		'core/spacer'    => array(
			'chevron-strip' => __( 'Chevron strip', 'ayesha-movers' ),
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
