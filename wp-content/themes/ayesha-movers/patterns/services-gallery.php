<?php
/**
 * Title: Our Services: ads gallery (Seen on our Facebook & Instagram)
 * Slug: ayesha-movers/services-gallery
 * Categories: ayesha-movers
 * Keywords: gallery, ads, facebook, instagram
 * Description: The client's ads in a three-column gallery (not cropped, so the text in each ad stays whole) and a button to the Instagram page from Business Info. Hidden on the site until the gallery has at least one photo.
 *
 * @package AyeshaMovers
 */

?>
<!-- wp:group {"metadata":{"name":"Ads gallery: Seen on our Facebook and Instagram"},"tagName":"section","align":"full","className":"ayesha-section ayesha-gallery","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ayesha-section ayesha-gallery" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading -->
<h2 class="wp-block-heading">Seen on our Facebook &amp; Instagram</h2>
<!-- /wp:heading -->

<!-- wp:gallery {"columns":3,"imageCrop":false,"linkTo":"none","className":"ayesha-gallery__grid"} -->
<figure class="wp-block-gallery has-nested-images columns-3 ayesha-gallery__grid"></figure>
<!-- /wp:gallery -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline","metadata":{"bindings":{"text":{"source":"ayesha/business","args":{"key":"instagram_handle"}},"url":{"source":"ayesha/business","args":{"key":"instagram_url"}}},"name":"Instagram (from Business Info)"}} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="https://www.instagram.com/ayesha_movers_packers/">@ayesha_movers_packers</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></section>
<!-- /wp:group -->
