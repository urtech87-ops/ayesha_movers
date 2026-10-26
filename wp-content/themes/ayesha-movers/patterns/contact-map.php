<?php
/**
 * Title: Contact Us: map of Bahrain (loads on click)
 * Slug: ayesha-movers/contact-map
 * Categories: ayesha-movers
 * Keywords: map, coverage, Bahrain, google maps
 * Description: A heading, one line and a drawn map of Bahrain with a "Show map" button. Google Maps loads only after a visitor clicks the button (nothing is sent to Google before that); without JavaScript the button opens Google Maps in a new tab. The map shows Bahrain only: no pin and no address. The button's link sets the place and zoom (…/maps/@26.03,50.55,10z).
 *
 * @package AyeshaMovers
 */

?>
<!-- wp:group {"metadata":{"name":"Map of Bahrain"},"tagName":"section","align":"full","className":"is-style-concrete-panel ayesha-section ayesha-map-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-concrete-panel ayesha-section ayesha-map-section" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading -->
<h2 class="wp-block-heading">Where we go</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ayesha-map-section__intro"} -->
<p class="ayesha-map-section__intro">From your door to anywhere in Bahrain, and on to Saudi Arabia and the rest of the GCC. Day or night.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"metadata":{"name":"Map (drawing until Show map is clicked)"},"className":"ayesha-map","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-map"><?php echo ayesha_theme_image_markup( 147, 'full', 'ayesha-map__image', 'Map drawing (its alt text becomes the Google map\'s title)' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper. ?>

<!-- wp:buttons {"className":"ayesha-map__buttons"} -->
<div class="wp-block-buttons ayesha-map__buttons"><!-- wp:button {"className":"is-style-yellow ayesha-map__show","linkTarget":"_blank","rel":"noreferrer noopener"} -->
<div class="wp-block-button is-style-yellow ayesha-map__show"><a class="wp-block-button__link wp-element-button" href="https://www.google.com/maps/@26.03,50.55,10z" target="_blank" rel="noreferrer noopener">Show map</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
