<?php
/**
 * Title: Prices (shared, hidden until "Show prices" is on)
 * Slug: ayesha-movers/home-pricing
 * Categories: ayesha-movers
 * Keywords: prices, pricing, plans, cost, quote
 * Description: Four price cards by size of move (studio or 1-bedroom flat, 2–3 bedroom flat, villa, office), each with "Price on request", what is included and a "Get a free quote" button. Visitors can't see this section while "Show prices" is off in Settings > Business Info. Put in the real prices or wording first, then switch it on.
 *
 * @package AyeshaMovers
 */

// Shared: inserts the synced pattern (Home and Our Services), so the prices are typed once.
$ayesha_ref = apply_filters( 'ayesha_theme_use_synced_patterns', true ) ? ayesha_theme_synced_pattern_id( 'ayesha-prices' ) : 0;
if ( $ayesha_ref ) {
	echo '<!-- wp:block {"ref":' . (int) $ayesha_ref . '} /-->';
	return;
}

$ayesha_quote = home_url( '/contact-us/#quote' );
$ayesha_plans = array(
	'studio' => 'Studio or 1-bedroom flat',
	'flat'   => '2–3 bedroom flat',
	'villa'  => 'Villa',
	'office' => 'Office',
);
$ayesha_items = array();
foreach ( $ayesha_plans as $ayesha_key => $ayesha_name ) {
	$ayesha_items[] = '<!-- wp:group {"metadata":{"name":"Price card: ' . esc_attr( $ayesha_name ) . '"},"className":"ayesha-plan ayesha-plan--' . $ayesha_key . '","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-plan ayesha-plan--' . $ayesha_key . '"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . esc_html( $ayesha_name ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ayesha-plan__price"} -->
<p class="ayesha-plan__price">Price on request</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"ayesha-checklist"} -->
<ul class="wp-block-list ayesha-checklist"><!-- wp:list-item -->
<li>Labour included</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-yellow"} -->
<div class="wp-block-button is-style-yellow"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $ayesha_quote ) . '">Get a free quote</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->';
}
?>
<!-- wp:group {"metadata":{"name":"Prices (hidden until Show prices is on)"},"tagName":"section","align":"full","className":"ayesha-pricing ayesha-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ayesha-pricing ayesha-section" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading -->
<h2 class="wp-block-heading">Prices</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ayesha-editor-note"} -->
<p class="ayesha-editor-note">Only you see this note, in the editor. Visitors can't see this section while "Show prices" is off in Settings → Business Info. Put the real prices (or the wording you want) in every card before switching it on. Never type BHD 0.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"ayesha-pricing__intro"} -->
<p class="ayesha-pricing__intro">We reply with a price for the whole job. Labour is included.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"metadata":{"name":"Price cards"},"className":"ayesha-plans","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-plans"><?php echo implode( "\n\n", $ayesha_items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
