<?php
/**
 * Title: About Us: page heading, intro and photo
 * Slug: ayesha-movers/about-intro
 * Categories: ayesha-movers
 * Keywords: about, heading, intro, photo
 * Description: The top of the About Us page: the page heading (H1) and a lead paragraph, with a photo beside them on computers and below them on phones. Keep the photo's alt text a plain description of what is in it.
 *
 * @package AyeshaMovers
 */

?>
<!-- wp:group {"metadata":{"name":"Page heading"},"tagName":"section","align":"full","className":"ayesha-section ayesha-intro ayesha-about-intro","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ayesha-section ayesha-intro ayesha-about-intro" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:columns {"className":"ayesha-about-intro__columns","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns ayesha-about-intro__columns"><!-- wp:column {"width":"58.33%"} -->
<div class="wp-block-column" style="flex-basis:58.33%"><!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">About AYESHA Movers &amp; Packers</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"lead"} -->
<p class="has-lead-font-size">One team for labour, trucks and carpenters. We move homes and offices from door to door, across Bahrain and abroad, day or night.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"41.67%","className":"ayesha-about-intro__photo"} -->
<div class="wp-block-column ayesha-about-intro__photo" style="flex-basis:41.67%"><?php echo ayesha_theme_image_markup( 15, 'ayesha-photo' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper. ?></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
