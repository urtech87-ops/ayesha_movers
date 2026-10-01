<?php
/**
 * Title: Reviews (placeholders, hidden until real)
 * Slug: ayesha-movers/reviews
 * Categories: ayesha-movers
 * Keywords: reviews, testimonials, customers
 * Description: Three placeholder cards for real customer reviews. Hidden from visitors while "Show reviews section" is off in Settings > Business Info. Replace every placeholder with a real review (customer's own words, name, area, date, and a link to the original on Google or Instagram) before switching it on.
 *
 * @package AyeshaMovers
 */

$ayesha_card = '<!-- wp:group {"metadata":{"name":"Review card (placeholder)"},"className":"is-style-concrete-panel ayesha-review","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-concrete-panel ayesha-review"><!-- wp:paragraph {"className":"ayesha-review__flag"} -->
<p class="ayesha-review__flag">Placeholder: replace with a real customer review</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"ayesha-review__quote"} -->
<p class="ayesha-review__quote">Paste the customer\'s own words here, exactly as they wrote them.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"ayesha-review__who"} -->
<p class="ayesha-review__who">Customer name, area</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"ayesha-review__source"} -->
<p class="ayesha-review__source">Month and year, on Google or Instagram (link to the original review)</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->';
?>
<!-- wp:group {"metadata":{"name":"Reviews (hidden until Show reviews section is on)"},"tagName":"section","align":"full","className":"ayesha-reviews ayesha-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ayesha-reviews ayesha-section" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading -->
<h2 class="wp-block-heading">What customers say</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ayesha-reviews__note"} -->
<p class="ayesha-reviews__note">Placeholder section. Visitors can't see it while "Show reviews section" is off in Settings → Business Info. Switch it on only after every card below holds a real review.</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"className":"ayesha-reviews__columns"} -->
<div class="wp-block-columns ayesha-reviews__columns"><!-- wp:column -->
<div class="wp-block-column"><?php echo $ayesha_card; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><?php echo $ayesha_card; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><?php echo $ayesha_card; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
