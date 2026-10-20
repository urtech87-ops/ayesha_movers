<?php
/**
 * Title: Home banner (slider with 3 slides)
 * Slug: ayesha-movers/hero
 * Categories: ayesha-movers
 * Keywords: hero, banner, slider, slides, home
 * Description: The top of the Home page: a full-width photo banner with up to 3 slides (block "Banner slider" from AYESHA Core). Each slide has a photo, a small line, a heading, one line of text, "Get a free quote" and "WhatsApp us", and the main number to call (from Business Info). Slide 1's heading is the page's H1. The gold line along its bottom edge is part of the banner.
 *
 * @package AyeshaMovers
 */

$ayesha_quote = home_url( '/contact-us/#quote' );

/**
 * One slide's block markup.
 *
 * @param array $slide id (Media Library photo), eyebrow, eyebrow_key (bound to Business Info instead), title, level, text, message (WhatsApp), name.
 * @param string $quote Quote form link.
 * @return string
 */
$ayesha_slide = static function ( $slide, $quote ) {
	$url = wp_get_attachment_image_url( $slide['id'], 'ayesha-banner-1920' );
	$alt = (string) get_post_meta( $slide['id'], '_wp_attachment_image_alt', true );

	$image = '';
	if ( $url ) {
		$image = '<!-- wp:image ' . serialize_block_attributes(
			array(
				'id'              => $slide['id'],
				'sizeSlug'        => 'ayesha-banner-1920',
				'linkDestination' => 'none',
				'className'       => 'ayesha-slide__image',
			)
		) . ' -->
<figure class="wp-block-image size-ayesha-banner-1920 ayesha-slide__image"><img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '" class="wp-image-' . (int) $slide['id'] . '"/></figure>
<!-- /wp:image -->

';
	}

	$eyebrow_attrs = array( 'className' => 'ayesha-slide__eyebrow' );
	if ( ! empty( $slide['eyebrow_key'] ) ) {
		$eyebrow_attrs = array(
			'metadata'  => array(
				'bindings' => array( 'content' => array( 'source' => 'ayesha/business', 'args' => array( 'key' => $slide['eyebrow_key'] ) ) ),
				'name'     => 'Opening hours (from Business Info)',
			),
			'className' => 'ayesha-slide__eyebrow',
		);
	}

	$heading_attrs = 1 === $slide['level'] ? array( 'level' => 1, 'className' => 'ayesha-slide__title' ) : array( 'className' => 'ayesha-slide__title' );
	$tag           = 'h' . (int) $slide['level'];

	$whatsapp_args = array( 'key' => 'whatsapp_url' );
	if ( ! empty( $slide['message'] ) ) {
		$whatsapp_args['message'] = $slide['message'];
	}
	$whatsapp = array(
		'className' => 'is-style-whatsapp',
		'metadata'  => array(
			'bindings' => array( 'url' => array( 'source' => 'ayesha/business', 'args' => $whatsapp_args ) ),
			'name'     => 'WhatsApp us',
		),
	);
	$phone    = array(
		'metadata' => array(
			'bindings' => array( 'content' => array( 'source' => 'ayesha/business', 'args' => array( 'key' => 'phone_primary_link' ) ) ),
			'name'     => 'Main number (from Business Info)',
		),
	);

	return '<!-- wp:ayesha/hero-slide ' . serialize_block_attributes( array( 'metadata' => array( 'name' => $slide['name'] ) ) ) . ' -->
<div class="wp-block-ayesha-hero-slide ayesha-slide">' . $image . '<!-- wp:group {"metadata":{"name":"Slide text"},"className":"ayesha-slide__content","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-slide__content"><!-- wp:paragraph ' . serialize_block_attributes( $eyebrow_attrs ) . ' -->
<p class="ayesha-slide__eyebrow">' . esc_html( $slide['eyebrow'] ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:heading ' . serialize_block_attributes( $heading_attrs ) . ' -->
<' . $tag . ' class="wp-block-heading ayesha-slide__title">' . esc_html( $slide['title'] ) . '</' . $tag . '>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ayesha-slide__text"} -->
<p class="ayesha-slide__text">' . esc_html( $slide['text'] ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"ayesha-slide__buttons"} -->
<div class="wp-block-buttons ayesha-slide__buttons"><!-- wp:button {"className":"is-style-yellow"} -->
<div class="wp-block-button is-style-yellow"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $quote ) . '">Get a free quote</a></div>
<!-- /wp:button -->

<!-- wp:button ' . serialize_block_attributes( $whatsapp ) . ' -->
<div class="wp-block-button is-style-whatsapp"><a class="wp-block-button__link wp-element-button" href="https://wa.me/97334448236">WhatsApp us</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:group {"metadata":{"name":"Phone line"},"className":"ayesha-slide__phone","layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group ayesha-slide__phone"><!-- wp:paragraph -->
<p>Or call</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph ' . serialize_block_attributes( $phone ) . ' -->
<p><a href="tel:+97334448236">+973 3444 8236</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:ayesha/hero-slide -->';
};

// Words reused from the site's own pages (hero, service cards, FAQ, "Why people book us").
$ayesha_slides = array(
	array(
		'name'        => 'Slide 1: Movers and packers in Bahrain',
		'id'          => 113,
		'eyebrow'     => 'Open 24 hours, every day',
		'eyebrow_key' => 'hours',
		'title'       => 'Movers and packers in Bahrain, day and night.',
		'level'       => 1,
		'text'        => 'House, villa, flat and office moves in Manama and across Bahrain. Labour, trucks and carpenters from one team.',
		'message'     => '',
	),
	array(
		'name'    => 'Slide 2: Truck hire',
		'id'      => 114,
		'eyebrow' => 'Dyna and 6-wheel trucks',
		'title'   => 'Truck hire: 8 hours or a full day',
		'level'   => 2,
		'text'    => 'We also do runs to Mina Salman, Khalifa Bin Salman Port, the airport and courier depots such as DHL, Aramex and GLS.',
		'message' => "Hi AYESHA Movers & Packers, I'd like a price for truck hire (Dyna or 6-wheel truck).",
	),
	array(
		'name'    => 'Slide 3: Packing',
		'id'      => 115,
		'eyebrow' => 'Packing and unpacking',
		'title'   => 'International-standard packing',
		'level'   => 2,
		'text'    => 'Fragile items and crockery get special packing, and we clear away the packing debris before we leave.',
		'message' => "Hi AYESHA Movers & Packers, I'd like a price for packing and unpacking.",
	),
);
?>
<!-- wp:ayesha/hero-slider {"metadata":{"name":"Banner"}} -->
<?php echo implode( "\n\n", array_map( static fn( $s ) => $ayesha_slide( $s, $ayesha_quote ), $ayesha_slides ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built and escaped above. ?>
<!-- /wp:ayesha/hero-slider -->
