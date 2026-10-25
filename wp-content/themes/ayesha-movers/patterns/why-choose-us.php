<?php
/**
 * Title: Why people book us (six numbered cards)
 * Slug: ayesha-movers/why-choose-us
 * Categories: ayesha-movers
 * Keywords: why us, reasons, benefits
 * Description: Six reasons as cards on light grey (three across on computers): an icon in a ring, a faint number 01–06 in the corner (it counts itself), a title and one sentence. Only the client's own facts. The icon comes from the card's CSS class (Advanced): ayesha-why-card--tag, --team, --clock, --door, --shield, --pin.
 *
 * @package AyeshaMovers
 */

$ayesha_reasons = array(
	array( 'tag', 'Low rates, with labour included', 'The price we give you includes the crew who pack, carry and load. No separate bill for labour.' ),
	array( 'team', 'One team for everything', 'Labour, trucks and carpenters all come from us, so you deal with one team from start to finish.' ),
	array( 'clock', 'Day or night', 'We work 24 hours, so your move can happen at the time that suits you, including at night.' ),
	array( 'door', 'Door to door', 'We collect from your door and deliver to the door of your new place.' ),
	array( 'shield', 'Careful with your things', 'Fragile items and crockery get special packing, and we clear away the packing debris before we leave.' ),
	array( 'pin', 'Bahrain and the GCC', 'From your door to anywhere in Bahrain, and on to Saudi Arabia and the rest of the GCC.' ),
);
$ayesha_items = array();
foreach ( $ayesha_reasons as $ayesha_reason ) {
	$ayesha_items[] = '<!-- wp:group {"metadata":{"name":"Reason: ' . esc_attr( $ayesha_reason[1] ) . '"},"className":"ayesha-why-card ayesha-why-card--' . $ayesha_reason[0] . '","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-why-card ayesha-why-card--' . $ayesha_reason[0] . '"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . esc_html( $ayesha_reason[1] ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html( $ayesha_reason[2] ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->';
}
?>
<!-- wp:group {"metadata":{"name":"Why people book us"},"tagName":"section","align":"full","className":"is-style-concrete-panel ayesha-section ayesha-whyus","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-concrete-panel ayesha-section ayesha-whyus" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading -->
<h2 class="wp-block-heading">Why people book us</h2>
<!-- /wp:heading -->

<!-- wp:group {"metadata":{"name":"Reasons"},"className":"ayesha-whyus__grid","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-whyus__grid"><?php echo implode( "\n\n", $ayesha_items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
