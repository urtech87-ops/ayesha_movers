<?php
/**
 * Title: What we do (lead service + 5 rows)
 * Slug: ayesha-movers/what-we-do
 * Categories: ayesha-movers
 * Keywords: services, shifting, packing, furniture, trucks, cargo
 * Description: House shifting as the large lead card with what's included and a photo under it, then the other five services as short rows. Each title links to its section on Our Services; each WhatsApp link opens a chat with the service name already typed.
 *
 * @package AyeshaMovers
 */

$ayesha_services_url = home_url( '/our-services/' );
$ayesha_photo_url    = wp_get_attachment_image_url( 12, 'full' );
$ayesha_photo_alt    = (string) get_post_meta( 12, '_wp_attachment_image_alt', true );

/*
 * One compact service row. The WhatsApp link's message is in the button's binding ("message"),
 * so the client can change it in the code editor (see docs/editing-guide.md).
 */
$ayesha_row = static function ( $anchor, $title, $text, $service ) use ( $ayesha_services_url ) {
	$message = "Hi AYESHA Movers \\u0026 Packers, I'd like a price for {$service}.";
	return '<!-- wp:group {"metadata":{"name":"Service: ' . esc_attr( $title ) . '"},"className":"ayesha-service-row","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-service-row"><!-- wp:heading {"level":3,"fontSize":"lead"} -->
<h3 class="wp-block-heading has-lead-font-size"><a href="' . esc_url( $ayesha_services_url . '#' . $anchor ) . '">' . esc_html( $title ) . '</a></h3>
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
<!-- wp:group {"metadata":{"name":"What we do"},"tagName":"section","align":"full","className":"is-style-teal-panel ayesha-section ayesha-services","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-teal-panel ayesha-section ayesha-services" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading -->
<h2 class="wp-block-heading">What we do</h2>
<!-- /wp:heading -->

<!-- wp:columns {"className":"ayesha-services__columns","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns ayesha-services__columns"><!-- wp:column {"width":"58%"} -->
<div class="wp-block-column" style="flex-basis:58%"><!-- wp:group {"metadata":{"name":"Lead service: house shifting"},"className":"is-style-concrete-panel ayesha-service-lead","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-concrete-panel ayesha-service-lead"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><a href="<?php echo esc_url( $ayesha_services_url . '#house-shifting' ); ?>">House, villa, flat and office shifting</a></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>House shifting from door to door, anywhere in Bahrain. The price includes the crew who pack, carry and load.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"ayesha-service-lead__label"} -->
<p class="ayesha-service-lead__label">Every move includes:</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"ayesha-checklist"} -->
<ul class="wp-block-list ayesha-checklist"><!-- wp:list-item -->
<li>Packing your things</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Loading and unloading</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Setting up the rooms in your new home</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Taking away the packing debris</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-whatsapp","metadata":{"bindings":{"url":{"source":"ayesha/business","args":{"key":"whatsapp_url","message":"Hi AYESHA Movers \u0026 Packers, I'd like a price for house, villa, flat or office shifting."}}},"name":"WhatsApp: house shifting"}} -->
<div class="wp-block-button is-style-whatsapp"><a class="wp-block-button__link wp-element-button" href="https://wa.me/97334448236">Ask about this on WhatsApp</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --><?php if ( $ayesha_photo_url ) : ?>

<!-- wp:image {"id":12,"sizeSlug":"full","linkDestination":"none","className":"ayesha-services__photo"} -->
<figure class="wp-block-image size-full ayesha-services__photo"><img src="<?php echo esc_url( $ayesha_photo_url ); ?>" alt="<?php echo esc_attr( $ayesha_photo_alt ); ?>" class="wp-image-12"/></figure>
<!-- /wp:image --><?php endif; ?></div>
<!-- /wp:column -->

<!-- wp:column {"width":"42%","className":"ayesha-services__rows"} -->
<div class="wp-block-column ayesha-services__rows" style="flex-basis:42%"><?php
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- rows are escaped inside $ayesha_row.
echo $ayesha_row( 'packing', 'Packing and unpacking', 'International-standard packing, with special packing for fragile items and crockery.', 'packing and unpacking' ) . "\n\n";
echo $ayesha_row( 'furniture', 'Furniture dismantling and refitting', 'Our carpenters take furniture and curtains down, put them back up, and set up new furniture for homes and offices.', 'furniture dismantling and refitting' ) . "\n\n";
echo $ayesha_row( 'appliances', 'AC, TV and curtain removal', 'Split units and other air conditioners, LCD and LED TVs, curtains and blinds: taken down and fitted again.', 'AC, TV or curtain removal and fitting' ) . "\n\n";
echo $ayesha_row( 'trucks', 'Truck hire: 8 hours or a full day', 'Dyna and 6-wheel trucks, including runs to Mina Salman, Khalifa Bin Salman Port, the airport and courier depots.', 'truck hire (Dyna or 6-wheel truck)' ) . "\n\n";
echo $ayesha_row( 'cargo', 'Container cargo to the GCC', '20ft and 40ft containers loaded and unloaded, with customs documents, to Saudi Arabia, the UAE, Kuwait, Qatar and Oman.', 'GCC cargo (20ft or 40ft container)' ) . "\n\n";
// phpcs:enable
?><!-- wp:paragraph {"className":"ayesha-services__all"} -->
<p class="ayesha-services__all"><a href="<?php echo esc_url( $ayesha_services_url ); ?>">See all services</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
