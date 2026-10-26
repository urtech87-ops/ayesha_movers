<?php
/**
 * Title: About Us: photo and who runs it
 * Slug: ayesha-movers/about-story
 * Categories: ayesha-movers
 * Keywords: about, intro, manager, photo
 * Description: Laid out like the Home page's About section: a photo with a gold edge and a badge with the opening hours (from Business Info) on one side; on the other the lead paragraph, "Who runs it" (the General Manager and the other trading name) and the buttons "WhatsApp us" and "Call us" (from Business Info). No founding year or "years of experience" until the client confirms one.
 *
 * @package AyeshaMovers
 */

$ayesha_photo = ayesha_theme_image_markup( 132, 'medium_large', 'ayesha-home-about__image', 'Photo (stock, replace with your own)' );
?>
<!-- wp:group {"metadata":{"name":"About: photo and who runs it"},"tagName":"section","align":"full","className":"is-style-concrete-panel ayesha-section ayesha-home-about ayesha-about-story","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-concrete-panel ayesha-section ayesha-home-about ayesha-about-story" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:columns {"verticalAlignment":"center","className":"ayesha-home-about__columns"} -->
<div class="wp-block-columns are-vertically-aligned-center ayesha-home-about__columns"><!-- wp:column {"verticalAlignment":"center","width":"50%","className":"ayesha-home-about__media"} -->
<div class="wp-block-column is-vertically-aligned-center ayesha-home-about__media" style="flex-basis:50%"><!-- wp:group {"metadata":{"name":"Photo and badge"},"className":"ayesha-home-about__photo","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-home-about__photo"><?php echo $ayesha_photo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped where built. ?>

<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"ayesha/business","args":{"key":"hours"}}},"name":"Badge: opening hours (from Business Info)"},"className":"ayesha-home-about__badge"} -->
<p class="ayesha-home-about__badge">Open 24 hours, every day</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"50%","className":"ayesha-home-about__text"} -->
<div class="wp-block-column is-vertically-aligned-center ayesha-home-about__text" style="flex-basis:50%"><!-- wp:paragraph {"className":"has-lead-font-size ayesha-about-story__lead"} -->
<p class="has-lead-font-size ayesha-about-story__lead">One team for labour, trucks and carpenters. We move homes and offices from door to door, across Bahrain and to Saudi Arabia and the GCC, day or night.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Who runs it</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>AYESHA Movers &amp; Packers is run by its General Manager, Mohammad Ayub Khokhear.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>The business also trades as AYESHA Cargo Handling.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"ayesha-home-about__buttons"} -->
<div class="wp-block-buttons ayesha-home-about__buttons"><!-- wp:button {"className":"is-style-whatsapp","metadata":{"bindings":{"url":{"source":"ayesha/business","args":{"key":"whatsapp_url"}}},"name":"WhatsApp us"}} -->
<div class="wp-block-button is-style-whatsapp"><a class="wp-block-button__link wp-element-button" href="https://wa.me/97334448236">WhatsApp us</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline","metadata":{"bindings":{"url":{"source":"ayesha/business","args":{"key":"phone_primary_url"}}},"name":"Call us"}} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="tel:+97334448236">Call us</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
