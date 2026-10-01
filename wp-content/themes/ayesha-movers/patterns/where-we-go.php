<?php
/**
 * Title: Where we go (service area, shared)
 * Slug: ayesha-movers/where-we-go
 * Categories: ayesha-movers
 * Keywords: service area, coverage, route, Bahrain, Saudi, GCC
 * Description: The service area as a route from your door to the rest of the world. Shared: inserts the synced pattern "Where we go", so editing it once updates every page.
 *
 * @package AyeshaMovers
 */

$ayesha_ref = apply_filters( 'ayesha_theme_use_synced_patterns', true ) ? ayesha_theme_synced_pattern_id( 'ayesha-where-we-go' ) : 0;
if ( $ayesha_ref ) {
	echo '<!-- wp:block {"ref":' . (int) $ayesha_ref . '} /-->';
	return;
}
?>
<!-- wp:group {"metadata":{"name":"Where we go"},"tagName":"section","align":"full","className":"ayesha-section ayesha-where","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ayesha-section ayesha-where" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading -->
<h2 class="wp-block-heading">Where we go</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>From your door to anywhere in Bahrain, and on to Saudi Arabia, the rest of the GCC and the world. Day or night.</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"ayesha-route"} -->
<ul class="wp-block-list ayesha-route"><!-- wp:list-item -->
<li>Your door</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Manama and every city in Bahrain</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Mina Salman, Khalifa Bin Salman Port and the airport</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Saudi Arabia</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>All GCC countries</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>UK, USA, Canada and worldwide</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></section>
<!-- /wp:group -->
