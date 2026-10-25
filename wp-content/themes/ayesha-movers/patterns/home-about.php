<?php
/**
 * Title: Home: about us (photo and text)
 * Slug: ayesha-movers/home-about
 * Categories: ayesha-movers
 * Keywords: about, intro, team, photo
 * Description: A photo on one side with a small badge on its corner (the opening hours from Business Info), and on the other side a heading, two short paragraphs from the About Us page and the buttons "About us" and "Call us" (main number from Business Info).
 *
 * @package AyeshaMovers
 */

$ayesha_photo = ayesha_theme_image_markup( 120, 'medium_large', 'ayesha-home-about__image', 'Photo (stock, replace with your own)' );
?>
<!-- wp:group {"metadata":{"name":"About us"},"tagName":"section","align":"full","className":"ayesha-section ayesha-home-about","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ayesha-section ayesha-home-about" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:columns {"verticalAlignment":"center","className":"ayesha-home-about__columns"} -->
<div class="wp-block-columns are-vertically-aligned-center ayesha-home-about__columns"><!-- wp:column {"verticalAlignment":"center","width":"50%","className":"ayesha-home-about__media"} -->
<div class="wp-block-column is-vertically-aligned-center ayesha-home-about__media" style="flex-basis:50%"><!-- wp:group {"metadata":{"name":"Photo and badge"},"className":"ayesha-home-about__photo","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-home-about__photo"><?php echo $ayesha_photo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped where built. ?>

<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"ayesha/business","args":{"key":"hours"}}},"name":"Badge: opening hours (from Business Info)"},"className":"ayesha-home-about__badge"} -->
<p class="ayesha-home-about__badge">Open 24 hours, every day</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"50%","className":"ayesha-home-about__text"} -->
<div class="wp-block-column is-vertically-aligned-center ayesha-home-about__text" style="flex-basis:50%"><!-- wp:heading -->
<h2 class="wp-block-heading">About AYESHA Movers &amp; Packers</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"has-lead-font-size"} -->
<p class="has-lead-font-size">One team for labour, trucks and carpenters. We move homes and offices from door to door, across Bahrain and to Saudi Arabia and the GCC, day or night.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>AYESHA Movers &amp; Packers is run by its General Manager, Mohammad Ayub Khokhear.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"ayesha-home-about__buttons"} -->
<div class="wp-block-buttons ayesha-home-about__buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">About us</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline","metadata":{"bindings":{"url":{"source":"ayesha/business","args":{"key":"phone_primary_url"}}},"name":"Call us"}} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="tel:+97334448236">Call us</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
