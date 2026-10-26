<?php
/**
 * Title: About Us: how we work (six photo cards)
 * Slug: ayesha-movers/about-values
 * Categories: ayesha-movers
 * Keywords: about, values, how we work, reasons
 * Description: Six cards on light grey (three across on computers): a photo, a gold tab with an icon, a title and one sentence. Only the client's own facts, worded differently from the Home page's "Why people book us". The icon comes from the card's CSS class (Advanced): ayesha-value-card--tag, --team, --door, --clock, --shield or --pin.
 *
 * @package AyeshaMovers
 */

/*
 * Every sentence is from CLAUDE.md or the About Us page's earlier "How we work" points.
 */
$ayesha_values = array(
	array( 'tag', 133, 'Labour included in the price', 'Packing, carrying, loading and unloading are done by our crew and covered by the price we quote you.' ),
	array( 'team', 134, 'One team, start to finish', 'Labour, trucks and carpenters all come from us, so you don\'t have to find a separate truck or carpenter.' ),
	array( 'door', 135, 'Door to door', 'We start at your door and finish at the door of your new home or office.' ),
	array( 'clock', 136, 'Open 24 hours', 'Available 24 hours, day and night.' ),
	array( 'shield', 137, 'Careful packing', 'International-standard packing, with special packing for fragile items and crockery.' ),
	array( 'pin', 138, 'Bahrain and the GCC', 'All of Bahrain, Mina Salman, Khalifa Bin Salman Port and the airport, and on to Saudi Arabia and the other GCC countries.' ),
);
$ayesha_items = array();
foreach ( $ayesha_values as $ayesha_value ) {
	$ayesha_class   = 'ayesha-service-card ayesha-value-card ayesha-value-card--' . $ayesha_value[0];
	$ayesha_items[] = '<!-- wp:group {"metadata":{"name":"' . esc_attr( $ayesha_value[2] ) . '"},"className":"' . $ayesha_class . '","layout":{"type":"default"}} -->
<div class="wp-block-group ' . $ayesha_class . '">' . ayesha_theme_image_markup( $ayesha_value[1], 'medium_large', 'ayesha-service-card__photo', 'Photo (stock, replace with your own)' ) . '

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . esc_html( $ayesha_value[2] ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html( $ayesha_value[3] ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->';
}
?>
<!-- wp:group {"metadata":{"name":"How we work"},"tagName":"section","align":"full","className":"ayesha-section ayesha-services ayesha-values","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ayesha-section ayesha-services ayesha-values" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading -->
<h2 class="wp-block-heading">How we work</h2>
<!-- /wp:heading -->

<!-- wp:group {"metadata":{"name":"Cards"},"className":"ayesha-services__grid","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-services__grid"><?php echo implode( "\n\n", $ayesha_items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
