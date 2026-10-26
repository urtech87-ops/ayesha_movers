<?php
/**
 * Title: Contact Us: quote intro, contact card and the quote form
 * Slug: ayesha-movers/contact-page
 * Categories: ayesha-movers
 * Keywords: contact, phone, whatsapp, email, quote, form
 * Description: "Request a free quote" and its line, then a navy card with a row for each way to reach the team, the service area and the opening hours (numbers, email, links and hours from Settings → Business Info), beside the quote request form on a white card. The form column has the HTML anchor "quote", which the Home page links to. On computers the contact card stays on screen while the form scrolls.
 *
 * @package AyeshaMovers
 */

/**
 * One contact row: a small label and the value, bound to Business Info. The whole row is the link.
 *
 * @param string $name  Name in the List View.
 * @param string $label Visible label.
 * @param string $key   Business Info key (a *_link key).
 * @param string $html  Fallback link HTML (what the editor stores; the site shows the live value).
 * @param string $link_text Own link words (binding "label"), or '' for the value itself.
 * @param string $extra     Extra classes, e.g. ayesha-if-whatsapp-same / ayesha-if-whatsapp-differs (same rule as the footer).
 * @param string $after     Extra block markup inside the row, after the value.
 * @return string
 */
$ayesha_row = static function ( $name, $label, $key, $html, $link_text = '', $extra = '', $after = '' ) {
	$classes = 'ayesha-contact__row' . ( 'whatsapp_link' === $key ? ' ayesha-contact__row--whatsapp' : '' ) . ( 'facebook_link' === $key ? ' ayesha-if-facebook' : '' ) . ( '' === $extra ? '' : ' ' . $extra );
	$args    = '"key":"' . esc_attr( $key ) . '"' . ( '' === $link_text ? '' : ',"label":"' . esc_attr( $link_text ) . '"' );
	return '<!-- wp:group {"metadata":{"name":"' . esc_attr( $name ) . '"},"className":"' . $classes . '","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group ' . $classes . '"><!-- wp:paragraph {"className":"ayesha-contact__label"} -->
<p class="ayesha-contact__label">' . esc_html( $label ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"ayesha-contact__value","metadata":{"bindings":{"content":{"source":"ayesha/business","args":{' . $args . '}}},"name":"' . esc_attr( $name ) . ' (from Business Info)"}} -->
<p class="ayesha-contact__value">' . $html . '</p>
<!-- /wp:paragraph -->' . $after . '</div>
<!-- /wp:group -->';
};

// The small "or call" link inside the combined row (tap the row = WhatsApp).
$ayesha_or_call = '

<!-- wp:paragraph {"className":"ayesha-contact__alt","metadata":{"bindings":{"content":{"source":"ayesha/business","args":{"key":"phone_primary_link","label":"or call"}}},"name":"Or call (main number, from Business Info)"}} -->
<p class="ayesha-contact__alt"><a href="tel:+97334448236">or call</a></p>
<!-- /wp:paragraph -->';

$ayesha_rows = array(
	$ayesha_row( 'Call or WhatsApp (shown when the WhatsApp number is the main number)', 'Call or WhatsApp', 'whatsapp_link', '<a href="https://wa.me/97334448236">+973 3444 8236</a>', '', 'ayesha-if-whatsapp-same', $ayesha_or_call ),
	$ayesha_row( 'WhatsApp (shown when it is a different number from the main number)', 'WhatsApp', 'whatsapp_link', '<a href="https://wa.me/97334448236">+973 3444 8236</a>', '', 'ayesha-if-whatsapp-differs' ),
	$ayesha_row( 'Mobile (main number) (shown when WhatsApp is a different number)', 'Mobile', 'phone_primary_link', '<a href="tel:+97334448236">+973 3444 8236</a>', '', 'ayesha-if-whatsapp-differs' ),
	$ayesha_row( 'Mobile (second number)', 'Mobile', 'phone_secondary_link', '<a href="tel:+97336429850">+973 3642 9850</a>' ),
	$ayesha_row( 'Office', 'Office', 'phone_office_link', '<a href="tel:+97377360292">+973 7736 0292</a>' ),
	$ayesha_row( 'Email', 'Email', 'email_link', '<a href="mailto:ayeshamoversbh786@gmail.com">ayeshamoversbh786@gmail.com</a>' ),
	$ayesha_row( 'Instagram', 'Instagram', 'instagram_link', '<a href="https://www.instagram.com/ayesha_movers_packers/" target="_blank" rel="noopener">@ayesha_movers_packers</a>' ),
	$ayesha_row( 'Facebook', 'Facebook', 'facebook_link', '<a href="https://www.facebook.com/ayeshamoversbahrain/" target="_blank" rel="noopener" aria-label="AYESHA Movers on Facebook">AYESHA Movers</a>', 'AYESHA Movers' ),
	// Not links: the service area (typed here) and the opening hours (from Business Info).
	'<!-- wp:group {"metadata":{"name":"Service area"},"className":"ayesha-contact__row ayesha-contact__row--area","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-contact__row ayesha-contact__row--area"><!-- wp:paragraph {"className":"ayesha-contact__label"} -->
<p class="ayesha-contact__label">Service area</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"ayesha-contact__value"} -->
<p class="ayesha-contact__value">All of Bahrain and the GCC</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->',
	'<!-- wp:group {"metadata":{"name":"Hours"},"className":"ayesha-contact__row ayesha-contact__row--hours","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-contact__row ayesha-contact__row--hours"><!-- wp:paragraph {"className":"ayesha-contact__label"} -->
<p class="ayesha-contact__label">Hours</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"ayesha-contact__value","metadata":{"bindings":{"content":{"source":"ayesha/business","args":{"key":"hours"}}},"name":"Opening hours (from Business Info)"}} -->
<p class="ayesha-contact__value">Open 24 hours, every day</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->',
);
?>
<!-- wp:group {"metadata":{"name":"Contact"},"tagName":"section","align":"full","className":"ayesha-section ayesha-contact","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ayesha-section ayesha-contact" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading -->
<h2 class="wp-block-heading">Request a free quote</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"has-lead-font-size ayesha-contact__intro"} -->
<p class="has-lead-font-size ayesha-contact__intro">Takes under a minute. We'll call or WhatsApp you back.</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"className":"ayesha-contact__columns"} -->
<div class="wp-block-columns ayesha-contact__columns"><!-- wp:column {"width":"41.67%","className":"ayesha-contact__aside"} -->
<div class="wp-block-column ayesha-contact__aside" style="flex-basis:41.67%"><!-- wp:group {"metadata":{"name":"Contact rows (shown when Business Info > Show contact details is on)"},"className":"ayesha-contact__rows ayesha-if-contact-details","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-contact__rows ayesha-if-contact-details"><?php echo implode( "

", $ayesha_rows ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built above. ?></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"58.33%","className":"ayesha-contact__form","anchor":"quote"} -->
<div id="quote" class="wp-block-column ayesha-contact__form" style="flex-basis:58.33%"><!-- wp:ayesha/quote-form {"showMoveParts":false,"askReply":false} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
