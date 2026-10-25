<?php
/**
 * Title: Home: we cover all of Bahrain (area tags)
 * Slug: ayesha-movers/home-coverage
 * Categories: ayesha-movers
 * Keywords: areas, coverage, service area, Bahrain, GCC
 * Description: A navy band with the heading "We cover all of Bahrain" and the places as tags of gold packing tape, ending with "Saudi Arabia and the GCC". Add or remove a place by editing the list (one place per item). The last item has the CSS class ayesha-areas__more, which draws it in solid gold.
 *
 * @package AyeshaMovers
 */

$ayesha_areas = array( 'Manama', 'Muharraq', 'Riffa', 'Isa Town', 'Hamad Town', 'Sitra', 'Juffair', 'Seef', 'Adliya', 'Budaiya', 'Saar', 'Hidd', 'Amwaj Islands', 'Zallaq', 'Awali', 'Mina Salman', 'Khalifa Bin Salman Port', 'Bahrain International Airport' );
$ayesha_items = array();
foreach ( $ayesha_areas as $ayesha_area ) {
	$ayesha_items[] = '<!-- wp:list-item -->
<li>' . esc_html( $ayesha_area ) . '</li>
<!-- /wp:list-item -->';
}
$ayesha_items[] = '<!-- wp:list-item {"className":"ayesha-areas__more"} -->
<li class="ayesha-areas__more">Saudi Arabia and the GCC</li>
<!-- /wp:list-item -->';
?>
<!-- wp:group {"metadata":{"name":"We cover all of Bahrain"},"tagName":"section","align":"full","className":"is-style-teal-panel ayesha-section ayesha-coverage","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-teal-panel ayesha-section ayesha-coverage" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading -->
<h2 class="wp-block-heading">We cover all of Bahrain</h2>
<!-- /wp:heading -->

<!-- wp:list {"className":"ayesha-areas"} -->
<ul class="wp-block-list ayesha-areas"><?php echo implode( "\n\n", $ayesha_items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></ul>
<!-- /wp:list --></section>
<!-- /wp:group -->
