<?php
/**
 * Title: How a move works (4 steps)
 * Slug: ayesha-movers/how-a-move-works
 * Categories: ayesha-movers
 * Keywords: steps, process, how it works
 * Description: The four steps of a move as numbered cards (one under the other on phones, four across on computers), then "WhatsApp us" and "Call now" (both from Business Info). The numbers count themselves, so moving a card renumbers it. A small badge under a step (Free, Labour included) is a paragraph you can edit or delete; only use true facts.
 *
 * @package AyeshaMovers
 */

$ayesha_steps = array(
	array( 'chat', 'Send your list or photos', "Tell us on WhatsApp what you're moving, from where to where, and when. Photos of the rooms help.", '' ),
	array( 'tag', 'Get your price', 'We reply with a price for the whole job. Labour is included.', 'Free' ),
	array( 'box', 'We pack and load', 'International-standard packing, with special care for fragile items and crockery. Our carpenters take the furniture apart.', 'Labour included' ),
	array( 'house', 'We unload, set up and clear up', 'We put the furniture back together, set up the rooms and take away the packing debris.', '' ),
);
$ayesha_items = array();
foreach ( $ayesha_steps as $ayesha_step ) {
	$ayesha_badge   = '' === $ayesha_step[3] ? '' : '

<!-- wp:paragraph {"className":"ayesha-badge"} -->
<p class="ayesha-badge">' . esc_html( $ayesha_step[3] ) . '</p>
<!-- /wp:paragraph -->';
	$ayesha_items[] = '<!-- wp:group {"metadata":{"name":"Step: ' . esc_attr( $ayesha_step[1] ) . '"},"className":"ayesha-step ayesha-step--' . $ayesha_step[0] . '","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-step ayesha-step--' . $ayesha_step[0] . '"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . esc_html( $ayesha_step[1] ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html( $ayesha_step[2] ) . '</p>
<!-- /wp:paragraph -->' . $ayesha_badge . '</div>
<!-- /wp:group -->';
}
?>
<!-- wp:group {"metadata":{"name":"How a move works"},"tagName":"section","align":"full","className":"ayesha-section ayesha-how","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ayesha-section ayesha-how" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading -->
<h2 class="wp-block-heading">How a move works</h2>
<!-- /wp:heading -->

<!-- wp:group {"metadata":{"name":"Steps"},"className":"ayesha-steps-grid","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-steps-grid"><?php echo implode( "\n\n", $ayesha_items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></div>
<!-- /wp:group -->

<!-- wp:buttons {"className":"ayesha-how__buttons"} -->
<div class="wp-block-buttons ayesha-how__buttons"><!-- wp:button {"className":"is-style-whatsapp","metadata":{"bindings":{"url":{"source":"ayesha/business","args":{"key":"whatsapp_url"}}},"name":"WhatsApp us"}} -->
<div class="wp-block-button is-style-whatsapp"><a class="wp-block-button__link wp-element-button" href="https://wa.me/97334448236">WhatsApp us</a></div>
<!-- /wp:button -->

<!-- wp:button {"metadata":{"bindings":{"url":{"source":"ayesha/business","args":{"key":"phone_primary_url"}}},"name":"Call now"}} -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="tel:+97334448236">Call now</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></section>
<!-- /wp:group -->
