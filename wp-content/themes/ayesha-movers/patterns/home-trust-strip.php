<?php
/**
 * Title: Home: trust strip (short facts under the banner)
 * Slug: ayesha-movers/home-trust-strip
 * Categories: ayesha-movers
 * Keywords: trust, facts, badges, ticker
 * Description: A navy strip right under the banner with six short facts, each with an icon. On computers it scrolls slowly sideways (it stops while the mouse is over it, with the Pause button, when "Page animations" is off, and for visitors who ask for less motion); on phones the facts sit in a still grid. Only true facts: edit the words in the list. The icon comes from the item's CSS class (Advanced): ayesha-trust--clock, --tag, --tools, --truck, --pin, --quote.
 *
 * @package AyeshaMovers
 */

$ayesha_facts = array(
	'clock' => 'Open 24 hours',
	'tag'   => 'Labour included in the price',
	'tools' => 'Carpenters on our team',
	'truck' => 'Dyna and 6-wheel trucks',
	'pin'   => 'Bahrain and the GCC',
	'quote' => 'Free quote',
);
$ayesha_items = array();
foreach ( $ayesha_facts as $ayesha_icon => $ayesha_fact ) {
	$ayesha_items[] = '<!-- wp:list-item {"className":"ayesha-trust--' . $ayesha_icon . '"} -->
<li class="ayesha-trust--' . $ayesha_icon . '">' . esc_html( $ayesha_fact ) . '</li>
<!-- /wp:list-item -->';
}
?>
<!-- wp:group {"metadata":{"name":"Trust strip"},"align":"full","className":"ayesha-trust","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull ayesha-trust"><!-- wp:list {"className":"ayesha-trust__list"} -->
<ul class="wp-block-list ayesha-trust__list"><?php echo implode( "\n\n", $ayesha_items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></ul>
<!-- /wp:list --></div>
<!-- /wp:group -->
