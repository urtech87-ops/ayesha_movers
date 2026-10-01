<?php
/**
 * Title: Home hero (yellow panel with the big number)
 * Slug: ayesha-movers/hero
 * Categories: ayesha-movers
 * Keywords: hero, phone, number, banner, home
 * Description: The top of the Home page: headline, the main phone number in large type (from Business Info), WhatsApp and Call buttons, a link to the quote form, the truck photo and the chevron strip. Delete the photo and the text takes the full width.
 *
 * @package AyeshaMovers
 */

$ayesha_photo = wp_get_attachment_image_url( 9, 'ayesha-photo' );
$ayesha_alt   = (string) get_post_meta( 9, '_wp_attachment_image_alt', true );
?>
<!-- wp:group {"metadata":{"name":"Hero"},"align":"full","className":"ayesha-hero","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull ayesha-hero"><!-- wp:group {"metadata":{"name":"Yellow panel"},"className":"is-style-yellow-panel ayesha-hero__panel","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-yellow-panel ayesha-hero__panel"><!-- wp:columns {"className":"ayesha-hero__columns","style":{"spacing":{"blockGap":{"top":"0","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns ayesha-hero__columns"><!-- wp:column {"metadata":{"name":"Hero text"},"className":"ayesha-hero__text"} -->
<div class="wp-block-column ayesha-hero__text"><!-- wp:heading {"level":1,"fontSize":"home-h1"} -->
<h1 class="wp-block-heading has-home-h-1-font-size">Movers and packers in Bahrain, day and night.</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"is-style-display-number","metadata":{"bindings":{"content":{"source":"ayesha/business","args":{"key":"phone_primary_big"}}},"name":"Big number (main phone)"}} -->
<p class="is-style-display-number"><a class="ayesha-number" href="tel:+97334448236"><span class="ayesha-number__code">+973</span> <span class="ayesha-number__local">3444 8236</span></a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"lead"} -->
<p class="has-lead-font-size">House, villa, flat and office moves in Manama and across Bahrain. Labour, trucks and carpenters from one team.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"ayesha-hero__buttons"} -->
<div class="wp-block-buttons ayesha-hero__buttons"><!-- wp:button {"className":"is-style-whatsapp","metadata":{"bindings":{"url":{"source":"ayesha/business","args":{"key":"whatsapp_url"}}},"name":"WhatsApp us"}} -->
<div class="wp-block-button is-style-whatsapp"><a class="wp-block-button__link wp-element-button" href="https://wa.me/97334448236">WhatsApp us</a></div>
<!-- /wp:button -->

<!-- wp:button {"metadata":{"bindings":{"url":{"source":"ayesha/business","args":{"key":"phone_primary_url"}}},"name":"Call"}} -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="tel:+97334448236">Call</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"className":"ayesha-hero__quote-link"} -->
<p class="ayesha-hero__quote-link"><a href="<?php echo esc_url( home_url( '/contact-us/#quote' ) ); ?>">Send a detailed quote request</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"metadata":{"name":"Hero photo (optional: delete it and the text takes the full width)"},"className":"ayesha-hero__photo"} -->
<div class="wp-block-column ayesha-hero__photo"><?php if ( $ayesha_photo ) : ?><!-- wp:image {"id":9,"sizeSlug":"ayesha-photo","linkDestination":"none"} -->
<figure class="wp-block-image size-ayesha-photo"><img src="<?php echo esc_url( $ayesha_photo ); ?>" alt="<?php echo esc_attr( $ayesha_alt ); ?>" class="wp-image-9"/></figure>
<!-- /wp:image --><?php endif; ?></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:spacer {"height":"12px","className":"is-style-chevron-strip"} -->
<div style="height:12px" aria-hidden="true" class="wp-block-spacer is-style-chevron-strip"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->
