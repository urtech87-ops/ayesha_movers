<?php
/**
 * Front-end output: floating WhatsApp button (desktop) and the reviews switch.
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * WhatsApp glyph (speech bubble with handset), coloured with currentColor.
 *
 * @return string
 */
function ayesha_core_whatsapp_icon() {
	return '<svg aria-hidden="true" focusable="false" width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 1.8a8.2 8.2 0 1 1-4.2 15.3l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 0 1 12 3.8Z"/><path d="M8.7 7.3c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.4s1 2.8 1.2 3c.1.2 2 3 4.9 4.3 2.4 1 2.9.8 3.4.8.5-.1 1.7-.7 1.9-1.4.2-.7.2-1.2.2-1.3-.1-.1-.3-.2-.6-.3l-1.9-.9c-.3-.1-.5-.1-.7.1l-.9 1.1c-.2.2-.3.2-.6.1-.3-.1-1.2-.4-2.3-1.4-.8-.7-1.4-1.7-1.6-1.9-.2-.3 0-.4.1-.6l.4-.5c.1-.2.2-.3.3-.5.1-.2 0-.4 0-.5l-.9-2.1c-.2-.5-.5-.5-.6-.5h-.5Z"/></svg>';
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
