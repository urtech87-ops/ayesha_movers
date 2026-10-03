<?php
/**
 * Title: Call to action band (gold, shared)
 * Slug: ayesha-movers/cta-band
 * Categories: ayesha-movers
 * Keywords: call to action, cta, whatsapp, call, contact
 * Description: The gold band above the footer: "Moving soon? Message us now.", the main number and the WhatsApp and Call buttons. Shared: inserts the synced pattern "CTA band", so editing it once updates every page.
 *
 * @package AyeshaMovers
 */

$ayesha_ref = apply_filters( 'ayesha_theme_use_synced_patterns', true ) ? ayesha_theme_synced_pattern_id( 'ayesha-cta-band' ) : 0;
if ( $ayesha_ref ) {
	echo '<!-- wp:block {"ref":' . (int) $ayesha_ref . '} /-->';
	return;
}
?>
<!-- wp:group {"metadata":{"name":"CTA band"},"tagName":"section","align":"full","className":"is-style-yellow-panel ayesha-section ayesha-cta","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-yellow-panel ayesha-section ayesha-cta" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:columns {"verticalAlignment":"bottom","className":"ayesha-cta__columns","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns are-vertically-aligned-bottom ayesha-cta__columns"><!-- wp:column {"verticalAlignment":"bottom","width":"45%"} -->
<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:45%"><!-- wp:heading -->
<h2 class="wp-block-heading">Moving soon? Message us now.</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"ayesha/business","args":{"key":"hours"}}},"name":"Opening hours"}} -->
<p>Open 24 hours, every day</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"bottom","width":"55%"} -->
<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:55%"><!-- wp:paragraph {"className":"is-style-display-number","metadata":{"bindings":{"content":{"source":"ayesha/business","args":{"key":"phone_primary_big"}}},"name":"Big number (main phone)"}} -->
<p class="is-style-display-number"><a class="ayesha-number" href="tel:+97334448236"><span class="ayesha-number__code">+973</span> <span class="ayesha-number__local">3444 8236</span></a></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-whatsapp","metadata":{"bindings":{"url":{"source":"ayesha/business","args":{"key":"whatsapp_url"}}},"name":"WhatsApp us"}} -->
<div class="wp-block-button is-style-whatsapp"><a class="wp-block-button__link wp-element-button" href="https://wa.me/97334448236">WhatsApp us</a></div>
<!-- /wp:button -->

<!-- wp:button {"metadata":{"bindings":{"url":{"source":"ayesha/business","args":{"key":"phone_primary_url"}}},"name":"Call"}} -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="tel:+97334448236">Call</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
