<?php
/**
 * Title: Our Services: intro and six service cards
 * Slug: ayesha-movers/services-cards
 * Categories: ayesha-movers
 * Keywords: services, cards, intro
 * Description: The page's intro line and the six services as cards like the Home page's "What we do" (three across on computers): a photo with the service's icon on a gold tab, the title, one line of text, "Learn more" (jumps to that service further down this page) and "Ask on WhatsApp" (opens a chat with the service already typed). Replace a photo by selecting it and choosing Replace.
 *
 * @package AyeshaMovers
 */

/*
 * One card. The icon comes from the links (#house-shifting, #packing, ...), so nothing extra is
 * stored. Titles, texts and WhatsApp messages are the Home page's cards; the photos are other ones.
 */
$ayesha_card = static function ( $anchor, $title, $text, $service, $photo ) {
	$message = "Hi AYESHA Movers \\u0026 Packers, I'd like a price for {$service}.";
	$link    = '#' . $anchor;
	return '<!-- wp:group {"metadata":{"name":"Service: ' . esc_attr( $title ) . '"},"className":"ayesha-service-card","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-service-card">' . ayesha_theme_image_markup( $photo, 'medium_large', 'ayesha-service-card__photo', 'Photo (stock, replace with your own)' ) . '

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . esc_html( $title ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html( $text ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"ayesha-service-card__links"} -->
<div class="wp-block-buttons ayesha-service-card__links"><!-- wp:button {"className":"is-style-text-link ayesha-read-more"} -->
<div class="wp-block-button is-style-text-link ayesha-read-more"><a class="wp-block-button__link wp-element-button" href="' . esc_attr( $link ) . '">Learn more<span class="screen-reader-text"> about ' . esc_html( $title ) . '</span></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-text-link","metadata":{"bindings":{"url":{"source":"ayesha/business","args":{"key":"whatsapp_url","message":"' . $message . '"}}},"name":"WhatsApp: ' . esc_attr( $title ) . '"}} -->
<div class="wp-block-button is-style-text-link"><a class="wp-block-button__link wp-element-button" href="https://wa.me/97334448236">Ask on WhatsApp</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->';
};
?>
<!-- wp:group {"metadata":{"name":"What we do (cards)"},"tagName":"section","align":"full","className":"is-style-concrete-panel ayesha-section ayesha-services ayesha-services--page","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-concrete-panel ayesha-section ayesha-services ayesha-services--page" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading -->
<h2 class="wp-block-heading">What we do</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"has-lead-font-size ayesha-services__intro"} -->
<p class="has-lead-font-size ayesha-services__intro">Everything for a move in Bahrain or to the GCC: labour, trucks and carpenters, all from one team. Ask about any service on WhatsApp, day or night.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"metadata":{"name":"Service cards"},"className":"ayesha-services__grid","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-services__grid"><?php
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- cards are escaped inside $ayesha_card.
echo $ayesha_card( 'house-shifting', 'House, villa, flat and office shifting', 'House shifting from door to door, anywhere in Bahrain. The price includes the crew who pack, carry and load.', 'house, villa, flat or office shifting', 140 ) . "\n\n";
echo $ayesha_card( 'packing', 'Packing and unpacking', 'International-standard packing, with special packing for fragile items and crockery.', 'packing and unpacking', 141 ) . "\n\n";
echo $ayesha_card( 'furniture', 'Furniture dismantling and refitting', 'Our carpenters take furniture and curtains down, put them back up, and set up new furniture for homes and offices.', 'furniture dismantling and refitting', 142 ) . "\n\n";
echo $ayesha_card( 'appliances', 'AC, TV and curtain removal', 'Split units and other air conditioners, LCD and LED TVs, curtains and blinds: taken down and fitted again.', 'AC, TV or curtain removal and fitting', 143 ) . "\n\n";
echo $ayesha_card( 'trucks', 'Truck hire: 8 hours or a full day', 'Dyna and 6-wheel trucks, including runs to Mina Salman, Khalifa Bin Salman Port, the airport and courier depots.', 'truck hire (Dyna or 6-wheel truck)', 144 ) . "\n\n";
echo $ayesha_card( 'cargo', 'Container cargo to the GCC', '20ft and 40ft containers loaded and unloaded, with customs documents, to Saudi Arabia, the UAE, Kuwait, Qatar and Oman.', 'GCC cargo (20ft or 40ft container)', 145 );
// phpcs:enable
?></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
