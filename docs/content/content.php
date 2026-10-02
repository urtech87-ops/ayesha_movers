<?php
/**
 * Build, export or import the page content (Home, About Us, Our Services, Contact Us) and the two synced patterns.
 *
 * Run from the WordPress root with WP-CLI:
 *   php wp-cli.phar eval-file <path>/docs/content/content.php build <page>   One page from the theme's patterns (first build; overwrites it). <page> = home, about, services or contact
 *   php wp-cli.phar eval-file <path>/docs/content/content.php export         Save every page + the synced patterns into docs/content/
 *   php wp-cli.phar eval-file <path>/docs/content/content.php import         Recreate them on another site from docs/content/
 *
 * Synced patterns are found by slug (ayesha-cta-band, ayesha-where-we-go), so running it twice updates
 * instead of duplicating. On import, pattern IDs and the site URL in the saved markup are mapped to
 * the new site. See docs/content/README.md.
 *
 * @package AyeshaMovers
 */

if ( ! defined( 'WP_CLI' ) ) {
	exit;
}

$ayesha_mode   = $args[0] ?? '';
$ayesha_dir    = __DIR__;
$ayesha_local  = 'http://localhost/ayesha-movers';
$ayesha_synced = array(
	'ayesha-cta-band'    => array( 'title' => 'CTA band', 'pattern' => 'cta-band' ),
	'ayesha-where-we-go' => array( 'title' => 'Where we go', 'pattern' => 'where-we-go' ),
);

/*
 * The pages: how to find each one, its backup file name, its template and the theme patterns it is built from.
 * Home is the static front page; the others are found by their address (slug).
 */
$ayesha_pages = array(
	'home'     => array(
		'id'       => (int) get_option( 'page_on_front' ),
		'file'     => 'home',
		'template' => '',
		'patterns' => array( 'hero', 'what-we-do', 'how-a-move-works', 'where-we-go', 'reviews', 'why-and-questions', 'cta-band' ),
	),
	'about'    => array(
		'id'       => (int) ( get_page_by_path( 'about-us' )->ID ?? 0 ),
		'file'     => 'about',
		'template' => 'page-sections',
		'patterns' => array( 'about-intro', 'about-team', 'where-we-go', 'cta-band' ),
	),
	'services' => array(
		'id'       => (int) ( get_page_by_path( 'our-services' )->ID ?? 0 ),
		'file'     => 'services',
		'template' => 'page-sections',
		'patterns' => array( 'services-intro', 'services-list', 'services-questions', 'cta-band' ),
	),
	'contact'  => array(
		'id'       => (int) ( get_page_by_path( 'contact-us' )->ID ?? 0 ),
		'file'     => 'contact',
		'template' => 'page-sections',
		'patterns' => array( 'contact-page' ),
	),
);

foreach ( $ayesha_pages as $ayesha_name => $ayesha_page ) {
	if ( ! $ayesha_page['id'] ) {
		WP_CLI::error( "Page \"$ayesha_name\" not found (Home must be the static front page; About Us, Our Services and Contact Us need the addresses about-us, our-services and contact-us)." );
	}
}

/**
 * Render a theme pattern file to block markup.
 *
 * @param string $slug File name without .php.
 * @return string
 */
$ayesha_pattern = static function ( $slug ) {
	$file = get_theme_file_path( 'patterns/' . $slug . '.php' );
	if ( ! file_exists( $file ) ) {
		WP_CLI::error( "Missing pattern file: $file" );
	}
	ob_start();
	include $file;
	return trim( ob_get_clean() );
};

/**
 * Create or update a synced pattern by slug.
 *
 * @param string $slug    Post slug.
 * @param string $title   Title shown under Patterns.
 * @param string $content Block markup.
 * @return int Post ID.
 */
$ayesha_save_synced = static function ( $slug, $title, $content ) {
	$existing = get_page_by_path( $slug, OBJECT, 'wp_block' );
	$postarr  = array(
		'post_type'    => 'wp_block',
		'post_status'  => 'publish',
		'post_name'    => $slug,
		'post_title'   => $title,
		'post_content' => $content,
	);
	if ( $existing ) {
		$postarr['ID'] = $existing->ID;
	}
	$id = wp_insert_post( wp_slash( $postarr ), true );
	if ( is_wp_error( $id ) ) {
		WP_CLI::error( $id->get_error_message() );
	}
	delete_post_meta( $id, 'wp_pattern_sync_status' ); // No value = synced.
	if ( taxonomy_exists( 'wp_pattern_category' ) ) {
		wp_set_object_terms( $id, 'Ayesha Movers', 'wp_pattern_category' );
	}
	WP_CLI::log( ( $existing ? 'Updated' : 'Created' ) . " synced pattern \"$title\" (ID $id)." );
	return (int) $id;
};

/**
 * Save a page's content and template.
 *
 * @param array  $page    Entry from $ayesha_pages.
 * @param string $content Block markup.
 */
$ayesha_save_page = static function ( $page, $content ) {
	$result = wp_update_post(
		wp_slash(
			array(
				'ID'            => $page['id'],
				'post_content'  => $content,
				'page_template' => $page['template'],
			)
		),
		true
	);
	if ( is_wp_error( $result ) ) {
		WP_CLI::error( $result->get_error_message() );
	}
	WP_CLI::log( 'Saved "' . get_the_title( $page['id'] ) . "\" (ID {$page['id']})." );
};

switch ( $ayesha_mode ) {
	case 'build':
		$ayesha_name = $args[1] ?? '';
		if ( ! isset( $ayesha_pages[ $ayesha_name ] ) ) {
			WP_CLI::error( 'Say which page to build: home, about, services or contact.' );
		}
		if ( 'home' === $ayesha_name ) {
			// The first build also creates the synced patterns from the plain theme blocks.
			add_filter( 'ayesha_theme_use_synced_patterns', '__return_false' );
			foreach ( $ayesha_synced as $slug => $info ) {
				$ayesha_save_synced( $slug, $info['title'], $ayesha_pattern( $info['pattern'] ) );
			}
			remove_filter( 'ayesha_theme_use_synced_patterns', '__return_false' );
		} else {
			foreach ( array_keys( $ayesha_synced ) as $slug ) {
				if ( ! get_page_by_path( $slug, OBJECT, 'wp_block' ) ) {
					WP_CLI::error( "Synced pattern $slug not found: build home first." );
				}
			}
		}
		$ayesha_save_page( $ayesha_pages[ $ayesha_name ], implode( "\n\n", array_map( $ayesha_pattern, $ayesha_pages[ $ayesha_name ]['patterns'] ) ) );
		if ( 'home' === $ayesha_name ) {
			// Search-engine title and description (design plan section 7). The other pages' are set in the editor's Search engines panel.
			update_post_meta( $ayesha_pages['home']['id'], '_ayesha_seo_title', 'Movers and Packers in Bahrain, 24 Hours | AYESHA Movers' );
			update_post_meta( $ayesha_pages['home']['id'], '_ayesha_seo_description', 'House, flat, villa and office shifting across Bahrain, with packing, carpenters and trucks. Cargo to KSA and GCC. Open 24 hours. WhatsApp +973 3444 8236.' );
		}
		break;

	case 'export':
		$manifest = array();
		wp_mkdir_p( $ayesha_dir . '/patterns' );
		foreach ( $ayesha_synced as $slug => $info ) {
			$post = get_page_by_path( $slug, OBJECT, 'wp_block' );
			if ( ! $post ) {
				WP_CLI::error( "Synced pattern $slug not found." );
			}
			file_put_contents( "$ayesha_dir/patterns/$slug.html", $post->post_content . "\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
			$manifest[ $slug ] = array(
				'title' => $post->post_title,
				'id'    => $post->ID,
			);
		}
		file_put_contents( "$ayesha_dir/patterns/manifest.json", wp_json_encode( $manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . "\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		foreach ( $ayesha_pages as $page ) {
			$seo = array(
				'title'       => get_post_meta( $page['id'], '_ayesha_seo_title', true ),
				'description' => get_post_meta( $page['id'], '_ayesha_seo_description', true ),
			);
			file_put_contents( "$ayesha_dir/{$page['file']}-seo.json", wp_json_encode( $seo, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
			file_put_contents( "$ayesha_dir/{$page['file']}.html", get_post_field( 'post_content', $page['id'], 'raw' ) . "\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		}
		WP_CLI::success( 'Exported docs/content/home, about, services and contact (.html + -seo.json) and patterns/.' );
		break;

	case 'import':
		$manifest = json_decode( (string) file_get_contents( "$ayesha_dir/patterns/manifest.json" ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		$site     = untrailingslashit( home_url() );
		$map      = array();
		foreach ( (array) $manifest as $slug => $info ) {
			$markup = str_replace( $ayesha_local, $site, (string) file_get_contents( "$ayesha_dir/patterns/$slug.html" ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
			$map[ (int) $info['id'] ] = $ayesha_save_synced( $slug, $info['title'], trim( $markup ) );
		}
		foreach ( $ayesha_pages as $page ) {
			$file = "$ayesha_dir/{$page['file']}.html";
			if ( ! file_exists( $file ) ) {
				WP_CLI::warning( "No backup for {$page['file']}; skipped." );
				continue;
			}
			$content = preg_replace_callback(
				'/<!-- wp:block \{"ref":(\d+)\} \/-->/',
				static function ( $m ) use ( $map ) {
					return '<!-- wp:block {"ref":' . ( $map[ (int) $m[1] ] ?? (int) $m[1] ) . '} /-->';
				},
				str_replace( $ayesha_local, $site, (string) file_get_contents( $file ) ) // phpcs:ignore WordPress.WP.AlternativeFunctions
			);
			$ayesha_save_page( $page, trim( $content ) );
			$seo = json_decode( (string) @file_get_contents( "$ayesha_dir/{$page['file']}-seo.json" ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions, WordPress.PHP.NoSilencedErrors
			update_post_meta( $page['id'], '_ayesha_seo_title', (string) ( $seo['title'] ?? '' ) );
			update_post_meta( $page['id'], '_ayesha_seo_description', (string) ( $seo['description'] ?? '' ) );
		}
		WP_CLI::warning( 'Check the photos: the saved markup uses Media Library IDs 9 and 12 (Home), 15 (About Us) and 14 (Our Services). If a photo has another ID on this site, pick it again in the editor.' );
		break;

	default:
		WP_CLI::error( 'Say what to do: build <page>, export or import.' );
}
