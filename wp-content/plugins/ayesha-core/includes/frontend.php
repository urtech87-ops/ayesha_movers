<?php
/**
 * Front-end output: floating WhatsApp button (desktop) and the reviews switch.
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * The WhatsApp glyph's path, read from assets/img/whatsapp.svg (Simple Icons, CC0; source and
 * licence in assets/img/whatsapp.txt). Every WhatsApp icon on the site comes from this one file.
 *
 * @return string Path data, or '' if the file is missing.
 */
function ayesha_core_whatsapp_glyph_path() {
	static $path = null;
	if ( null === $path ) {
		$svg  = (string) file_get_contents( AYESHA_CORE_DIR . 'assets/img/whatsapp.svg' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local plugin file.
		$path = preg_match( '/<path d="([^"]+)"/', $svg, $m ) ? $m[1] : '';
	}
	return $path;
}

/**
 * The glyph as an SVG document, coloured with currentColor.
 * The viewBox adds 2 units of empty space on each side of the glyph's 24-unit box (the shape is not
 * changed), so the icon shows at the same size as the previous one in the same space.
 *
 * @param string $attrs Extra attributes for the <svg> tag.
 * @return string
 */
function ayesha_core_whatsapp_svg( $attrs = '' ) {
	return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="-2 -2 28 28" fill="currentColor"' . $attrs . '><path d="' . esc_attr( ayesha_core_whatsapp_glyph_path() ) . '"/></svg>';
}

/**
 * Inline WhatsApp icon (floating button). Decorative: the link carries the accessible name.
 *
 * @param int $size Width and height in px.
 * @return string
 */
function ayesha_core_whatsapp_icon( $size = 28 ) {
	return ayesha_core_whatsapp_svg( sprintf( ' aria-hidden="true" focusable="false" width="%1$d" height="%1$d"', (int) $size ) );
}

/**
 * The same glyph as the CSS variable --ayesha-icon-whatsapp. The theme draws it as a mask filled
 * with the text colour (currentColor) on every WhatsApp button and on the Contact Us WhatsApp row.
 *
 * @return string CSS.
 */
function ayesha_core_whatsapp_icon_css() {
	if ( '' === ayesha_core_whatsapp_glyph_path() ) {
		return '';
	}
	return ':root{--ayesha-icon-whatsapp:url("data:image/svg+xml,' . rawurlencode( ayesha_core_whatsapp_svg() ) . '")}';
}

add_action( 'enqueue_block_assets', 'ayesha_core_icon_styles' );

/**
 * Print the icon variable on the site and inside the block editor, so both show the same icon.
 */
function ayesha_core_icon_styles() {
	wp_register_style( 'ayesha-core-icons', false, array(), AYESHA_CORE_VERSION );
	wp_enqueue_style( 'ayesha-core-icons' );
	wp_add_inline_style( 'ayesha-core-icons', ayesha_core_whatsapp_icon_css() );
}

add_action( 'wp_enqueue_scripts', 'ayesha_core_frontend_assets' );

/**
 * Styles for the floating button.
 */
function ayesha_core_frontend_assets() {
	if ( '' === ayesha_core_whatsapp_url() ) {
		return;
	}
	wp_enqueue_style( 'ayesha-core-frontend', AYESHA_CORE_URL . 'assets/css/frontend.css', array(), AYESHA_CORE_VERSION );
}

add_action( 'wp_footer', 'ayesha_core_floating_whatsapp' );

/**
 * Floating WhatsApp button. Shown at 960px and up only (CSS); phones use the sticky bar.
 */
function ayesha_core_floating_whatsapp() {
	$url = ayesha_core_whatsapp_url();
	if ( '' === $url ) {
		return;
	}
	printf(
		'<a class="ayesha-wa-float" href="%1$s" aria-label="%2$s">%3$s</a>' . "\n",
		esc_url( $url ),
		esc_attr__( 'Chat on WhatsApp', 'ayesha-core' ),
		ayesha_core_whatsapp_icon() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
	);
}

/**
 * Whether the WhatsApp number is the same as the main phone number.
 *
 * @return bool
 */
function ayesha_core_whatsapp_is_main_phone() {
	$main     = ltrim( ayesha_core_e164( ayesha_core_setting( 'phone_primary' ) ), '+' );
	$whatsapp = preg_replace( '/\D/', '', (string) ayesha_core_setting( 'whatsapp' ) );
	return '' !== $main && $main === $whatsapp;
}

add_filter( 'render_block', 'ayesha_core_whatsapp_conditional_blocks', 10, 2 );

/**
 * Blocks with the class "ayesha-if-whatsapp-same" show only when the WhatsApp number equals the
 * main number; "ayesha-if-whatsapp-differs" only when they differ. Lets the footer show one
 * "Call or WhatsApp" row instead of two rows with the same number.
 *
 * @param string $content Block HTML.
 * @param array  $block   Parsed block.
 * @return string
 */
function ayesha_core_whatsapp_conditional_blocks( $content, $block ) {
	$class = $block['attrs']['className'] ?? '';
	if ( '' === $class || false === strpos( $class, 'ayesha-if-whatsapp-' ) ) {
		return $content;
	}
	$classes = preg_split( '/\s+/', $class );
	$same    = ayesha_core_whatsapp_is_main_phone();
	if ( in_array( 'ayesha-if-whatsapp-same', $classes, true ) && ! $same ) {
		return '';
	}
	if ( in_array( 'ayesha-if-whatsapp-differs', $classes, true ) && $same ) {
		return '';
	}
	return $content;
}

add_filter( 'render_block', 'ayesha_core_hide_reviews', 10, 2 );

/**
 * Remove any block with the CSS class "ayesha-reviews" while "Show reviews section" is off,
 * so placeholder reviews never reach visitors.
 *
 * @param string $content Block HTML.
 * @param array  $block   Parsed block.
 * @return string
 */
function ayesha_core_hide_reviews( $content, $block ) {
	$class = $block['attrs']['className'] ?? '';
	if ( '' === $class || ! in_array( 'ayesha-reviews', preg_split( '/\s+/', $class ), true ) ) {
		return $content;
	}
	return ayesha_core_setting( 'show_reviews' ) ? $content : '';
}

add_filter( 'render_block', 'ayesha_core_contact_conditional_blocks', 10, 2 );

/**
 * "Show contact details on Contact Us" (Business Info): blocks with the class
 * "ayesha-if-contact-details" (the opening hours and the contact rows) show only while it is on.
 * The blocks stay in the page, so the client can switch the details back on at any time.
 *
 * @param string $content Block HTML.
 * @param array  $block   Parsed block.
 * @return string
 */
function ayesha_core_contact_conditional_blocks( $content, $block ) {
	$class = $block['attrs']['className'] ?? '';
	if ( '' === $class || ! in_array( 'ayesha-if-contact-details', preg_split( '/\s+/', $class ), true ) ) {
		return $content;
	}
	return ayesha_core_setting( 'show_contact' ) ? $content : '';
}

add_filter( 'render_block', 'ayesha_core_hide_empty_social', 10, 2 );

/**
 * A block bound to a Facebook or Instagram field is removed when that link is empty in
 * Business Info, so the site never shows an empty row or a dead link. Its parent row
 * (a group with the class "ayesha-if-facebook" / "ayesha-if-instagram") goes too.
 *
 * @param string $content Block HTML.
 * @param array  $block   Parsed block.
 * @return string
 */
function ayesha_core_hide_empty_social( $content, $block ) {
	$empty = array(
		'facebook'  => '' === (string) ayesha_core_setting( 'facebook_url' ),
		'instagram' => '' === (string) ayesha_core_setting( 'instagram_url' ),
	);
	$class = $block['attrs']['className'] ?? '';
	foreach ( $empty as $network => $is_empty ) {
		if ( $is_empty && '' !== $class && in_array( 'ayesha-if-' . $network, preg_split( '/\s+/', $class ), true ) ) {
			return '';
		}
	}
	foreach ( (array) ( $block['attrs']['metadata']['bindings'] ?? array() ) as $binding ) {
		$key = (string) ( $binding['args']['key'] ?? '' );
		foreach ( $empty as $network => $is_empty ) {
			if ( $is_empty && str_starts_with( $key, $network . '_' ) ) {
				return '';
			}
		}
	}
	return $content;
}
