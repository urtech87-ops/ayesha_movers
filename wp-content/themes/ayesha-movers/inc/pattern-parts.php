<?php
/**
 * Block markup shared by more than one pattern file (the "Why people book us" statements
 * and the Questions list appear on their own and side by side).
 *
 * @package AyeshaMovers
 */

defined( 'ABSPATH' ) || exit;

/**
 * "Why people book us": heading and four short statements (2 x 2 on wide screens).
 * From the client's selling points; no icons, no stats, no numbers.
 *
 * @return string Block markup.
 */
function ayesha_theme_reasons_markup() {
	$reasons = array(
		array( 'Low rates, with labour included', 'The price we give you includes the crew who pack, carry and load. No separate bill for labour.' ),
		array( 'Door to door', 'We collect from your door and deliver to the door of your new place.' ),
		array( 'One team for everything', 'Labour, trucks and carpenters all come from us, so you deal with one team from start to finish.' ),
		array( 'Careful with your things', 'Fragile items and crockery get special packing, and we clear away the packing debris before we leave.' ),
	);
	$items = array();
	foreach ( $reasons as $reason ) {
		$items[] = '<!-- wp:group {"className":"ayesha-reason","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-reason"><!-- wp:heading {"level":3,"fontSize":"lead"} -->
<h3 class="wp-block-heading has-lead-font-size">' . esc_html( $reason[0] ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html( $reason[1] ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->';
	}
	return '<!-- wp:heading -->
<h2 class="wp-block-heading">Why people book us</h2>
<!-- /wp:heading -->

<!-- wp:group {"metadata":{"name":"Reasons"},"className":"ayesha-reasons","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-reasons">' . implode( "\n\n", $items ) . '</div>
<!-- /wp:group -->';
}

/**
 * "Questions": heading and Details blocks, answered only with client facts.
 * (No pricing question: how prices are worked out is still an open question for the client.)
 *
 * @param array<int, array{0: string, 1: string}>|null $faq Question/answer pairs; null = the Home page's six.
 * @return string Block markup.
 */
function ayesha_theme_questions_markup( $faq = null ) {
	$faq   = $faq ?? array(
		array( 'Do you take furniture apart and put it back together?', 'Yes. Our carpenters dismantle your furniture before the move and fit them back together in your new home or office. They also take down and put up curtains, and set up new furniture.' ),
		array( 'Can I hire a truck for 8 hours or a full day?', 'Yes. You can hire a Dyna or a 6-wheel truck for 8 hours or for a full day. We also do runs to Mina Salman, Khalifa Bin Salman Port, the airport and courier depots such as DHL, Aramex and GLS.' ),
		array( 'Can you move my things to Saudi Arabia?', 'Yes. We move households and offices to Saudi Arabia and the other GCC countries: the UAE, Kuwait, Qatar and Oman. We also handle the customs documents.' ),
		array( 'Can you move us at night?', 'Yes. We work 24 hours, day and night. Tell us the time that suits you.' ),
		array( 'Do you remove and refit air conditioners and TVs?', 'Yes. We take down and fit split units and other air conditioners, LCD and LED TVs, and curtains and blinds.' ),
		array( 'Do you load shipping containers?', 'Yes. We load and unload 20ft and 40ft containers and prepare the customs documents for moves to Saudi Arabia and the rest of the GCC.' ),
	);
	$items = array();
	foreach ( $faq as $qa ) {
		$items[] = '<!-- wp:details -->
<details class="wp-block-details"><summary>' . esc_html( $qa[0] ) . '</summary><!-- wp:paragraph -->
<p>' . esc_html( $qa[1] ) . '</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->';
	}
	return '<!-- wp:heading -->
<h2 class="wp-block-heading">Questions</h2>
<!-- /wp:heading -->

<!-- wp:group {"metadata":{"name":"Questions"},"className":"ayesha-faq","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-faq">' . implode( "\n\n", $items ) . '</div>
<!-- /wp:group -->';
}

/**
 * A full-width section wrapper.
 *
 * @param string $name    Name shown in the editor's List View.
 * @param string $classes Extra CSS classes.
 * @param string $inner   Inner block markup.
 * @return string Block markup.
 */
function ayesha_theme_section_markup( $name, $classes, $inner ) {
	return '<!-- wp:group {"metadata":{"name":"' . esc_attr( $name ) . '"},"tagName":"section","align":"full","className":"' . esc_attr( $classes ) . '","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ' . esc_attr( $classes ) . '" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">' . $inner . '</section>
<!-- /wp:group -->';
}

/**
 * One section of the Our Services page: H2 with the id the Home page links to, a short paragraph,
 * an optional photo, the "What's included" checklist and an "Ask about this on WhatsApp" button
 * whose prefilled message names the service (in the button's binding, so the client can edit it).
 *
 * @param array{id: string, title: string, text: string, items: string[], message: string, image?: string} $service Section content.
 * @return string Block markup.
 */
function ayesha_theme_service_section_markup( $service ) {
	$classes = 'ayesha-svc__section';
	$items   = array();
	foreach ( $service['items'] as $item ) {
		$items[] = '<!-- wp:list-item -->
<li>' . esc_html( $item ) . '</li>
<!-- /wp:list-item -->';
	}
	$button = array(
		'className' => 'is-style-whatsapp',
		'metadata'  => array(
			'bindings' => array(
				'url' => array(
					'source' => 'ayesha/business',
					'args'   => array(
						'key'     => 'whatsapp_url',
						'message' => $service['message'],
					),
				),
			),
			'name'     => 'WhatsApp: ' . $service['title'],
		),
	);
	$section = array(
		'metadata'  => array( 'name' => 'Service: ' . $service['title'] ),
		'tagName'   => 'section',
		'className' => $classes,
		'style'     => array(
			'spacing' => array(
				'padding' => array(
					'top'    => 'var:preset|spacing|50',
					'bottom' => 'var:preset|spacing|50',
				),
			),
		),
		'layout'    => array( 'type' => 'default' ),
	);
	return '<!-- wp:group ' . serialize_block_attributes( $section ) . ' -->
<section class="wp-block-group ' . esc_attr( $classes ) . '" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:heading -->
<h2 class="wp-block-heading" id="' . esc_attr( $service['id'] ) . '">' . esc_html( $service['title'] ) . '</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html( $service['text'] ) . '</p>
<!-- /wp:paragraph -->' . ( empty( $service['image'] ) ? '' : "\n\n" . $service['image'] ) . '

<!-- wp:paragraph {"className":"ayesha-svc__label"} -->
<p class="ayesha-svc__label">What\'s included</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"ayesha-checklist"} -->
<ul class="wp-block-list ayesha-checklist">' . implode( "\n\n", $items ) . '</ul>
<!-- /wp:list -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button ' . serialize_block_attributes( $button ) . ' -->
<div class="wp-block-button is-style-whatsapp"><a class="wp-block-button__link wp-element-button" href="https://wa.me/97334448236">Ask about this on WhatsApp</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></section>
<!-- /wp:group -->';
}

/**
 * An Image block for a Media Library photo at a given size, with the attachment's alt text.
 *
 * @param int    $id        Attachment ID.
 * @param string $size      Image size slug.
 * @param string $class     Extra CSS class ('' for none).
 * @param string $name      Name shown in the editor's List View ('' for none).
 * @return string Block markup, or '' when the photo is missing.
 */
function ayesha_theme_image_markup( $id, $size, $class = '', $name = '' ) {
	$url = wp_get_attachment_image_url( $id, $size );
	if ( ! $url ) {
		return '';
	}
	$attrs = array(
		'id'              => $id,
		'sizeSlug'        => $size,
		'linkDestination' => 'none',
	);
	if ( '' !== $name ) {
		$attrs['metadata'] = array( 'name' => $name );
	}
	if ( '' !== $class ) {
		$attrs['className'] = $class;
	}
	$alt = (string) get_post_meta( $id, '_wp_attachment_image_alt', true );
	return '<!-- wp:image ' . serialize_block_attributes( $attrs ) . ' -->
<figure class="wp-block-image size-' . esc_attr( $size ) . ( '' === $class ? '' : ' ' . esc_attr( $class ) ) . '"><img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '" class="wp-image-' . (int) $id . '"/></figure>
<!-- /wp:image -->';
}
