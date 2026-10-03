<?php
/**
 * Title: Our Services: the six services with the "Jump to" list
 * Slug: ayesha-movers/services-list
 * Categories: ayesha-movers
 * Keywords: services, shifting, packing, furniture, appliances, trucks, cargo
 * Description: One section per service (what it is, what's included, a WhatsApp button with the service already typed). The "Jump to a service" list wraps under the intro on phones and becomes a sticky menu on the left on computers. Each heading has an HTML anchor (Advanced panel) that the Home page and the list link to: keep them.
 *
 * @package AyeshaMovers
 */

$ayesha_message = static function ( $service ) {
	return "Hi AYESHA Movers & Packers, I'd like a price for {$service}.";
};

/*
 * Content from the client facts in CLAUDE.md only. The WhatsApp messages use the same wording
 * as the Home page's "What we do" links. Sections alternate grey and white.
 */
$ayesha_services = array(
	array(
		'id'      => 'house-shifting',
		'label'   => 'House shifting',
		'title'   => 'House, villa, flat and office shifting',
		'text'    => 'We move houses, villas, flats and offices from door to door, anywhere in Bahrain. The price includes the labour: the crew who pack, carry and load.',
		'items'   => array(
			'Packing your things',
			'Loading and unloading',
			'Setting up the rooms in your new home',
			'Taking away the packing debris',
		),
		'message' => $ayesha_message( 'house, villa, flat or office shifting' ),
	),
	array(
		'id'      => 'packing',
		'label'   => 'Packing and unpacking',
		'title'   => 'Packing and unpacking',
		'text'    => 'We pack your things to international standards before the move and unpack them at your new place.',
		'items'   => array(
			'International-standard packing',
			'Special packing for fragile items',
			'Special packing for crockery',
			'Unpacking at your new place',
		),
		'message' => $ayesha_message( 'packing and unpacking' ),
	),
	array(
		'id'      => 'furniture',
		'label'   => 'Furniture',
		'title'   => 'Furniture dismantling and refitting',
		'text'    => 'Our professional carpenters take your furniture apart before the move and fit it back together in your new home or office.',
		'items'   => array(
			'Dismantling furniture before the move',
			'Re-fixing furniture after the move',
			'Taking down and putting up curtains',
			'Setting up new furniture for homes and offices',
		),
		'message' => $ayesha_message( 'furniture dismantling and refitting' ),
	),
	array(
		'id'      => 'appliances',
		'label'   => 'Appliances',
		'title'   => 'Appliance removal and refitting',
		'text'    => 'We take down your air conditioners, TVs, curtains and blinds before the move and fit them again at your new place.',
		'items'   => array(
			'Split units and other air conditioners',
			'LCD and LED TVs',
			'Curtains and blinds',
		),
		'message' => $ayesha_message( 'AC, TV or curtain removal and fitting' ),
	),
	array(
		'id'      => 'trucks',
		'label'   => 'Truck hire',
		'title'   => 'Truck hire: 8 hours or a full day',
		'text'    => 'Hire a Dyna or a 6-wheel truck for 8 hours or for a full day, for a move or for runs to the ports, the airport and courier depots.',
		'items'   => array(
			'Dyna trucks and 6-wheel trucks',
			'Rented for 8 hours or a full day',
			'Runs to Mina Salman and Khalifa Bin Salman Port',
			'Runs to the airport',
			'Runs to courier depots: DHL, Aramex and GLS',
		),
		'message' => $ayesha_message( 'truck hire (Dyna or 6-wheel truck)' ),
		'image'   => ayesha_theme_image_markup( 14, 'ayesha-photo', 'ayesha-svc__photo' ),
	),
	array(
		'id'      => 'cargo',
		'label'   => 'GCC cargo',
		'title'   => 'GCC cargo',
		'text'    => 'We load and unload 20ft and 40ft containers and handle the customs documentation for moves to Saudi Arabia, the UAE, Kuwait, Qatar and Oman.',
		'items'   => array(
			'20ft and 40ft container loading',
			'Container unloading',
			'Customs documentation',
			'Moves to Saudi Arabia (KSA) and all GCC countries',
		),
		'message' => $ayesha_message( 'GCC cargo (20ft or 40ft container)' ),
	),
);

$ayesha_links = array();
foreach ( $ayesha_services as $ayesha_service ) {
	$ayesha_links[] = '<!-- wp:list-item -->
<li><a href="#' . esc_attr( $ayesha_service['id'] ) . '">' . esc_html( $ayesha_service['label'] ) . '</a></li>
<!-- /wp:list-item -->';
}
?>
<!-- wp:group {"metadata":{"name":"Services"},"align":"full","className":"ayesha-svc","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull ayesha-svc"><!-- wp:group {"className":"ayesha-svc__grid","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-svc__grid"><!-- wp:group {"metadata":{"name":"Jump to a service (sticky menu on computers)"},"tagName":"nav","className":"ayesha-jump","ariaLabel":"Services on this page","layout":{"type":"default"}} -->
<nav class="wp-block-group ayesha-jump" aria-label="Services on this page"><!-- wp:paragraph {"className":"ayesha-jump__label"} -->
<p class="ayesha-jump__label">Jump to a service</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"ayesha-jump__list"} -->
<ul class="wp-block-list ayesha-jump__list"><?php echo implode( "\n\n", $ayesha_links ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></ul>
<!-- /wp:list --></nav>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Service sections"},"className":"ayesha-svc__sections","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-svc__sections"><?php
echo implode( "\n\n", array_map( 'ayesha_theme_service_section_markup', $ayesha_services ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper.
?></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
