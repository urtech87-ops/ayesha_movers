<?php
/**
 * Title: What we do (six service cards)
 * Slug: ayesha-movers/what-we-do
 * Categories: ayesha-movers
 * Keywords: services, shifting, packing, furniture, trucks, cargo
 * Description: The six services as equal cards on light grey (three across on computers): an icon in a gold circle, the title linking to its section on Our Services, one line of text and an "Ask on WhatsApp" link that opens a chat with the service name already typed. A "See all services" button under the cards.
 *
 * @package AyeshaMovers
 */

$ayesha_services_url = home_url( '/our-services/' );

/*
 * One service card. The icon comes from the title's link (#house-shifting, #packing, ...), so nothing
 * extra is stored. The WhatsApp link's message is in the button's binding ("message"), so the client
 * can change it in the code editor (see docs/editing-guide.md).
 */
$ayesha_card = static function ( $anchor, $title, $text, $service ) use ( $ayesha_services_url ) {
	$message = "Hi AYESHA Movers \\u0026 Packers, I'd like a price for {$service}.";
	return '<!-- wp:group {"metadata":{"name":"Service: ' . esc_attr( $title ) . '"},"className":"ayesha-service-card","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-service-card"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><a href="' . esc_url( $ayesha_services_url . '#' . $anchor ) . '">' . esc_html( $title ) . '</a></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html( $text ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-text-link","metadata":{"bindings":{"url":{"source":"ayesha/business","args":{"key":"whatsapp_url","message":"' . $message . '"}}},"name":"WhatsApp: ' . esc_attr( $title ) . '"}} -->
<div class="wp-block-button is-style-text-link"><a class="wp-block-button__link wp-element-button" href="https://wa.me/97334448236">Ask on WhatsApp</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->';
};
?>
<!-- wp:group {"metadata":{"name":"What we do"},"tagName":"section","align":"full","className":"is-style-concrete-panel ayesha-section ayesha-services","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-concrete-panel ayesha-section ayesha-services" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading -->
<h2 class="wp-block-heading">What we do</h2>
<!-- /wp:heading -->

<!-- wp:group {"metadata":{"name":"Service cards"},"className":"ayesha-services__grid","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-services__grid"><?php
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- cards are escaped inside $ayesha_card.
echo $ayesha_card( 'house-shifting', 'House, villa, flat and office shifting', 'House shifting from door to door, anywhere in Bahrain. The price includes the crew who pack, carry and load.', 'house, villa, flat or office shifting' ) . "\n\n";
echo $ayesha_card( 'packing', 'Packing and unpacking', 'International-standard packing, with special packing for fragile items and crockery.', 'packing and unpacking' ) . "\n\n";
echo $ayesha_card( 'furniture', 'Furniture dismantling and refitting', 'Our carpenters take furniture and curtains down, put them back up, and set up new furniture for homes and offices.', 'furniture dismantling and refitting' ) . "\n\n";
echo $ayesha_card( 'appliances', 'AC, TV and curtain removal', 'Split units and other air conditioners, LCD and LED TVs, curtains and blinds: taken down and fitted again.', 'AC, TV or curtain removal and fitting' ) . "\n\n";
echo $ayesha_card( 'trucks', 'Truck hire: 8 hours or a full day', 'Dyna and 6-wheel trucks, including runs to Mina Salman, Khalifa Bin Salman Port, the airport and courier depots.', 'truck hire (Dyna or 6-wheel truck)' ) . "\n\n";
echo $ayesha_card( 'cargo', 'Container cargo to the GCC', '20ft and 40ft containers loaded and unloaded, with customs documents, to Saudi Arabia, the UAE, Kuwait, Qatar and Oman.', 'GCC cargo (20ft or 40ft container)' );
// phpcs:enable
?></div>
<!-- /wp:group -->

<!-- wp:buttons {"className":"ayesha-services__all"} -->
<div class="wp-block-buttons ayesha-services__all"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $ayesha_services_url ); ?>">See all services</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></section>
<!-- /wp:group -->
