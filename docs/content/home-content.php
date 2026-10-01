<?php
/**
 * Build, export or import the Home page content and its two synced patterns.
 *
 * Run from the WordPress root with WP-CLI:
 *   php wp-cli.phar eval-file <path>/docs/content/home-content.php build    Home page from the theme's patterns (first build)
 *   php wp-cli.phar eval-file <path>/docs/content/home-content.php export   Save the current Home page + synced patterns into docs/content/
 *   php wp-cli.phar eval-file <path>/docs/content/home-content.php import   Recreate them on another site from docs/content/
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

$ayesha_mode    = $args[0] ?? '';
$ayesha_dir     = __DIR__;
$ayesha_synced  = array(
	'ayesha-cta-band'    => array( 'title' => 'CTA band', 'pattern' => 'cta-band' ),
	'ayesha-where-we-go' => array( 'title' => 'Where we go', 'pattern' => 'where-we-go' ),
);
$ayesha_order   = array( 'hero', 'what-we-do', 'how-a-move-works', 'where-we-go', 'reviews', 'why-and-questions', 'cta-band' );
$ayesha_home_id = (int) get_option( 'page_on_front' );
$ayesha_local   = 'http://localhost/ayesha-movers';

if ( ! $ayesha_home_id ) {
	WP_CLI::error( 'No static front page is set (Settings > Reading).' );
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

$ayesha_save_home = static function ( $content ) use ( $ayesha_home_id ) {
	$result = wp_update_post(
		wp_slash(
			array(
				'ID'           => $ayesha_home_id,
				'post_content' => $content,
			)
		),
		true
	);
	if ( is_wp_error( $result ) ) {
		WP_CLI::error( $result->get_error_message() );
	}
	WP_CLI::log( "Saved the Home page (ID $ayesha_home_id)." );
};

switch ( $ayesha_mode ) {
	case 'build':
		add_filter( 'ayesha_theme_use_synced_patterns', '__return_false' );
		foreach ( $ayesha_synced as $slug => $info ) {
			$ayesha_save_synced( $slug, $info['title'], $ayesha_pattern( $info['pattern'] ) );
		}
		remove_filter( 'ayesha_theme_use_synced_patterns', '__return_false' );
		$ayesha_save_home( implode( "\n\n", array_map( $ayesha_pattern, $ayesha_order ) ) );
		// Search-engine title and description (design plan section 7).
		update_post_meta( $ayesha_home_id, '_ayesha_seo_title', 'Movers and Packers in Bahrain, 24 Hours | AYESHA Movers' );
		update_post_meta( $ayesha_home_id, '_ayesha_seo_description', 'House, flat, villa and office shifting across Bahrain, with packing, carpenters and trucks. Cargo to KSA and GCC. Open 24 hours. WhatsApp +973 3444 8236.' );
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
		$seo = array(
			'title'       => get_post_meta( $ayesha_home_id, '_ayesha_seo_title', true ),
			'description' => get_post_meta( $ayesha_home_id, '_ayesha_seo_description', true ),
		);
		file_put_contents( "$ayesha_dir/home-seo.json", wp_json_encode( $seo, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		file_put_contents( "$ayesha_dir/home.html", get_post_field( 'post_content', $ayesha_home_id, 'raw' ) . "\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		WP_CLI::success( 'Exported docs/content/home.html, home-seo.json and patterns/.' );
		break;

	case 'import':
		$manifest = json_decode( (string) file_get_contents( "$ayesha_dir/patterns/manifest.json" ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		$home     = (string) file_get_contents( "$ayesha_dir/home.html" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		$site     = untrailingslashit( home_url() );
		$map      = array();
		foreach ( (array) $manifest as $slug => $info ) {
			$markup = str_replace( $ayesha_local, $site, (string) file_get_contents( "$ayesha_dir/patterns/$slug.html" ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
			$map[ (int) $info['id'] ] = $ayesha_save_synced( $slug, $info['title'], trim( $markup ) );
		}
		$home = preg_replace_callback(
			'/<!-- wp:block \{"ref":(\d+)\} \/-->/',
			static function ( $m ) use ( $map ) {
				return '<!-- wp:block {"ref":' . ( $map[ (int) $m[1] ] ?? (int) $m[1] ) . '} /-->';
			},
			str_replace( $ayesha_local, $site, $home )
		);
		$ayesha_save_home( trim( $home ) );
		$seo = json_decode( (string) file_get_contents( "$ayesha_dir/home-seo.json" ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		update_post_meta( $ayesha_home_id, '_ayesha_seo_title', (string) ( $seo['title'] ?? '' ) );
		update_post_meta( $ayesha_home_id, '_ayesha_seo_description', (string) ( $seo['description'] ?? '' ) );
		WP_CLI::warning( 'Check the hero photo: the saved markup uses Media Library ID 9. If the photo has another ID on this site, pick it again in the editor.' );
		break;

	default:
		WP_CLI::error( 'Say what to do: build, export or import.' );
}
