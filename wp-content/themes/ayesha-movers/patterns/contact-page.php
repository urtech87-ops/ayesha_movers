<?php
/**
 * Title: Contact Us: heading, contact rows and the quote form
 * Slug: ayesha-movers/contact-page
 * Categories: ayesha-movers
 * Keywords: contact, phone, whatsapp, email, quote, form
 * Description: The Contact Us page: the page heading (H1), the opening hours, a row for each way to reach the team (all from Settings → Business Info) and the quote request form under the heading "Request a detailed quote" (anchor "quote", which the Home page links to). On computers the contact rows stay on screen while the form scrolls.
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
 * @return string
 */
$ayesha_row = static function ( $name, $label, $key, $html, $link_text = '' ) {
	$classes = 'ayesha-contact__row' . ( 'whatsapp_link' === $key ? ' ayesha-contact__row--whatsapp' : '' ) . ( 'facebook_link' === $key ? ' ayesha-if-facebook' : '' );
	$args    = '"key":"' . esc_attr( $key ) . '"' . ( '' === $link_text ? '' : ',"label":"' . esc_attr( $link_text ) . '"' );
	return '<!-- wp:group {"metadata":{"name":"' . esc_attr( $name ) . '"},"className":"' . $classes . '","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group ' . $classes . '"><!-- wp:paragraph {"className":"ayesha-contact__label"} -->
<p class="ayesha-contact__label">' . esc_html( $label ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"ayesha-contact__value","metadata":{"bindings":{"content":{"source":"ayesha/business","args":{' . $args . '}}},"name":"' . esc_attr( $name ) . ' (from Business Info)"}} -->
<p class="ayesha-contact__value">' . $html . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->';
};

$ayesha_rows = array(
	$ayesha_row( 'WhatsApp', 'WhatsApp', 'whatsapp_link', '<a href="https://wa.me/97334448236">+973 3444 8236</a>' ),
	$ayesha_row( 'Mobile (main number)', 'Mobile', 'phone_primary_link', '<a href="tel:+97334448236">+973 3444 8236</a>' ),
	$ayesha_row( 'Mobile (second number)', 'Mobile', 'phone_secondary_link', '<a href="tel:+97336429850">+973 3642 9850</a>' ),
	$ayesha_row( 'Office', 'Office', 'phone_office_link', '<a href="tel:+97377360292">+973 7736 0292</a>' ),
	$ayesha_row( 'Email', 'Email', 'email_link', '<a href="mailto:ayeshamoversbh786@gmail.com">ayeshamoversbh786@gmail.com</a>' ),
	$ayesha_row( 'Instagram', 'Instagram', 'instagram_link', '<a href="https://www.instagram.com/ayesha_movers_packers/" target="_blank" rel="noopener">@ayesha_movers_packers</a>' ),
	$ayesha_row( 'Facebook', 'Facebook', 'facebook_link', '<a href="https://www.facebook.com/ayeshamoversbahrain/" target="_blank" rel="noopener" aria-label="AYESHA Movers on Facebook">AYESHA Movers</a>', 'AYESHA Movers' ),
);
?>
<!-- wp:group {"metadata":{"name":"Contact"},"tagName":"section","align":"full","className":"ayesha-section ayesha-intro ayesha-contact","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ayesha-section ayesha-intro ayesha-contact" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:columns {"className":"ayesha-contact__columns","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns ayesha-contact__columns"><!-- wp:column {"width":"41.67%","className":"ayesha-contact__aside"} -->
<div class="wp-block-column ayesha-contact__aside" style="flex-basis:41.67%"><!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Contact us</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ayesha-if-contact-details","fontSize":"lead","metadata":{"bindings":{"content":{"source":"ayesha/business","args":{"key":"hours"}}},"name":"Opening hours"}} -->
<p class="ayesha-if-contact-details has-lead-font-size">Open 24 hours, every day</p>
<!-- /wp:paragraph -->

<!-- wp:group {"metadata":{"name":"Contact rows (shown when Business Info > Show contact details is on)"},"className":"ayesha-contact__rows ayesha-if-contact-details","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-contact__rows ayesha-if-contact-details"><?php echo implode( "\n\n", $ayesha_rows ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built above. ?></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"58.33%","className":"ayesha-contact__form"} -->
<div class="wp-block-column ayesha-contact__form" style="flex-basis:58.33%"><!-- wp:heading -->
<h2 class="wp-block-heading" id="quote">Request a detailed quote</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Takes about 3 minutes. We reply on WhatsApp or by phone.</p>
<!-- /wp:paragraph -->

<!-- wp:ayesha/quote-form {"showMoveParts":false} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
