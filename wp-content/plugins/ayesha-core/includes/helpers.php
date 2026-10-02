<?php
/**
 * Formatting helpers and the single value resolver used by bindings, shortcodes and schema.
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * E.164 form of a phone number as typed in settings ("+973 3444 8236" -> "+97334448236").
 *
 * @param string $phone Phone as entered.
 * @return string Empty string when there are no digits.
 */
function ayesha_core_e164( $phone ) {
	$phone  = trim( (string) $phone );
	$digits = preg_replace( '/\D/', '', $phone );
	if ( '' === $digits ) {
		return '';
	}
	if ( str_starts_with( $digits, '00' ) ) {
		$digits = substr( $digits, 2 );
	}
	return '+' . $digits;
}

/**
 * tel: link for a phone number.
 *
 * @param string $phone Phone as entered.
 * @return string
 */
function ayesha_core_tel_url( $phone ) {
	$e164 = ayesha_core_e164( $phone );
	return '' === $e164 ? '' : 'tel:' . $e164;
}

/**
 * Readable form of the WhatsApp number ("97334448236" -> "+973 3444 8236").
 *
 * @param string $digits Digits only.
 * @return string
 */
function ayesha_core_format_whatsapp( $digits ) {
	$digits = preg_replace( '/\D/', '', (string) $digits );
	if ( '' === $digits ) {
		return '';
	}
	// Bahrain: +973 then an 8-digit local number in two groups of 4.
	if ( 11 === strlen( $digits ) && str_starts_with( $digits, '973' ) ) {
		return '+973 ' . substr( $digits, 3, 4 ) . ' ' . substr( $digits, 7 );
	}
	return '+' . $digits;
}

/**
 * Click-to-chat link: https://wa.me/<digits>?text=<message>.
 *
 * @param string|null $message Prefilled message; null uses the settings default.
 * @return string
 */
function ayesha_core_whatsapp_url( $message = null ) {
	$digits = preg_replace( '/\D/', '', (string) ayesha_core_setting( 'whatsapp' ) );
	if ( '' === $digits ) {
		return '';
	}
	$message = null === $message ? (string) ayesha_core_setting( 'whatsapp_message' ) : (string) $message;
	$url     = 'https://wa.me/' . $digits;
	if ( '' !== trim( $message ) ) {
		$url .= '?text=' . rawurlencode( $message );
	}
	return $url;
}

/**
 * Instagram handle from the profile URL ("https://www.instagram.com/ayesha_movers_packers/" -> "@ayesha_movers_packers").
 *
 * @param string $url Profile URL.
 * @return string
 */
function ayesha_core_instagram_handle( $url ) {
	$path = trim( (string) wp_parse_url( (string) $url, PHP_URL_PATH ), '/' );
	$name = explode( '/', $path )[0] ?? '';
	return '' === $name ? '' : '@' . $name;
}

/**
 * Build an <a> tag. Text is escaped; the URL is escaped for its protocol.
 *
 * @param string $url   URL.
 * @param string $text  Link text.
 * @param bool   $blank Open in a new tab.
 * @return string
 */
function ayesha_core_link( $url, $text, $blank = false ) {
	if ( '' === $url || '' === $text ) {
		return esc_html( $text );
	}
	$target = $blank ? ' target="_blank" rel="noopener"' : '';
	return '<a href="' . esc_url( $url, array( 'tel', 'mailto', 'https', 'http' ) ) . '"' . $target . '>' . esc_html( $text ) . '</a>';
}

/**
 * Approximate width of a phone number in em, set in the display face (Archivo 800, wdth 62).
 * Advances measured from the font file in Phase 4, rounded up; unknown characters count wide.
 *
 * @param string $text Number as shown.
 * @return float
 */
function ayesha_core_number_em_width( $text ) {
	$advance = array(
		' ' => 0.12,
		'+' => 0.51,
		'-' => 0.24,
		'(' => 0.37,
		')' => 0.37,
	);
	$width   = 0.0;
	foreach ( str_split( (string) $text ) as $char ) {
		$width += ctype_digit( $char ) ? 0.43 : ( $advance[ $char ] ?? 0.6 );
	}
	return round( $width, 2 );
}

/**
 * The big two-part number: a tel: link with the country code and the local number in
 * separate spans, so CSS can put them on two lines on phones and one line on wide screens.
 * The em widths go into custom properties; CSS sizes the number from its container width.
 *
 * @param string $phone Phone as entered ("+973 3444 8236").
 * @return string Safe HTML.
 */
function ayesha_core_big_number_link( $phone ) {
	$phone = trim( (string) $phone );
	$url   = ayesha_core_tel_url( $phone );
	if ( '' === $phone || '' === $url ) {
		return esc_html( $phone );
	}
	$parts = preg_split( '/\s+/', $phone, 2 );
	if ( 2 === count( $parts ) && str_starts_with( $parts[0], '+' ) ) {
		list( $code, $local ) = $parts;
	} else {
		list( $code, $local ) = array( '', $phone );
	}
	$whole   = ayesha_core_number_em_width( $phone );
	$longest = max( ayesha_core_number_em_width( $code ), ayesha_core_number_em_width( $local ) );
	$inner   = ( '' === $code ? '' : '<span class="ayesha-number__code">' . esc_html( $code ) . '</span> ' )
		. '<span class="ayesha-number__local">' . esc_html( $local ) . '</span>';
	return sprintf(
		'<a class="ayesha-number" href="%1$s" style="--ayesha-number-w1:%2$s;--ayesha-number-w2:%3$s">%4$s</a>',
		esc_url( $url, array( 'tel' ) ),
		esc_attr( (string) $whole ),
		esc_attr( (string) $longest ),
		$inner
	);
}

/**
 * Every key the site can display, with a plain-English label for the editor.
 * "kind" says what the value is: text, url (for button/link targets) or html (a ready-made link for paragraphs).
 *
 * @return array<string, array{label: string, kind: string}>
 */
function ayesha_core_value_keys() {
	return array(
		'phone_primary'        => array( 'label' => __( 'Main phone number', 'ayesha-core' ), 'kind' => 'text' ),
		'phone_primary_url'    => array( 'label' => __( 'Main phone: call link (for buttons)', 'ayesha-core' ), 'kind' => 'url' ),
		'phone_primary_link'   => array( 'label' => __( 'Main phone, tap to call (for text)', 'ayesha-core' ), 'kind' => 'html' ),
		'phone_primary_big'    => array( 'label' => __( 'Main phone, tap to call, as the big number (hero and yellow band)', 'ayesha-core' ), 'kind' => 'html' ),
		'phone_secondary'      => array( 'label' => __( 'Second mobile number', 'ayesha-core' ), 'kind' => 'text' ),
		'phone_secondary_url'  => array( 'label' => __( 'Second mobile: call link (for buttons)', 'ayesha-core' ), 'kind' => 'url' ),
		'phone_secondary_link' => array( 'label' => __( 'Second mobile, tap to call (for text)', 'ayesha-core' ), 'kind' => 'html' ),
		'phone_office'         => array( 'label' => __( 'Office number', 'ayesha-core' ), 'kind' => 'text' ),
		'phone_office_url'     => array( 'label' => __( 'Office: call link (for buttons)', 'ayesha-core' ), 'kind' => 'url' ),
		'phone_office_link'    => array( 'label' => __( 'Office number, tap to call (for text)', 'ayesha-core' ), 'kind' => 'html' ),
		'whatsapp_number'      => array( 'label' => __( 'WhatsApp number', 'ayesha-core' ), 'kind' => 'text' ),
		'whatsapp_url'         => array( 'label' => __( 'WhatsApp: chat link (for buttons)', 'ayesha-core' ), 'kind' => 'url' ),
		'whatsapp_link'        => array( 'label' => __( 'WhatsApp number, tap to chat (for text)', 'ayesha-core' ), 'kind' => 'html' ),
		'email'                => array( 'label' => __( 'Email address', 'ayesha-core' ), 'kind' => 'text' ),
		'email_url'            => array( 'label' => __( 'Email: send link (for buttons)', 'ayesha-core' ), 'kind' => 'url' ),
		'email_link'           => array( 'label' => __( 'Email address, tap to write (for text)', 'ayesha-core' ), 'kind' => 'html' ),
		'hours'                => array( 'label' => __( 'Opening hours', 'ayesha-core' ), 'kind' => 'text' ),
		'instagram_handle'     => array( 'label' => __( 'Instagram name', 'ayesha-core' ), 'kind' => 'text' ),
		'instagram_url'        => array( 'label' => __( 'Instagram: page link (for buttons)', 'ayesha-core' ), 'kind' => 'url' ),
		'instagram_link'       => array( 'label' => __( 'Instagram name, tap to open (for text)', 'ayesha-core' ), 'kind' => 'html' ),
		'service_areas'        => array( 'label' => __( 'Service areas (one per line)', 'ayesha-core' ), 'kind' => 'html' ),
		'address'              => array( 'label' => __( 'Business address', 'ayesha-core' ), 'kind' => 'html' ),
		'copyright'            => array( 'label' => __( 'Copyright line with the current year', 'ayesha-core' ), 'kind' => 'text' ),
	);
}

/**
 * Resolve a key to its display value.
 *
 * Optional args:
 * - label:   link text for the *_link keys (default: the value itself).
 * - message: prefilled WhatsApp message for whatsapp_url / whatsapp_link (default: the settings message).
 *
 * Text values are returned unescaped (callers escape for their context); *_link,
 * service_areas and address return safe HTML.
 *
 * @param string $key  Key from ayesha_core_value_keys().
 * @param array  $args Optional args.
 * @return string|null Null for an unknown key.
 */
function ayesha_core_value( $key, $args = array() ) {
	$s       = ayesha_core_settings();
	$label   = isset( $args['label'] ) && '' !== $args['label'] ? (string) $args['label'] : null;
	$message = isset( $args['message'] ) ? (string) $args['message'] : null;

	switch ( $key ) {
		case 'phone_primary':
		case 'phone_secondary':
		case 'phone_office':
			return (string) $s[ $key ];
		case 'phone_primary_url':
		case 'phone_secondary_url':
		case 'phone_office_url':
			return ayesha_core_tel_url( $s[ substr( $key, 0, -4 ) ] );
		case 'phone_primary_link':
		case 'phone_secondary_link':
		case 'phone_office_link':
			$phone = (string) $s[ substr( $key, 0, -5 ) ];
			return ayesha_core_link( ayesha_core_tel_url( $phone ), $label ?? $phone );
		case 'phone_primary_big':
			return ayesha_core_big_number_link( $s['phone_primary'] );
		case 'whatsapp_number':
			return ayesha_core_format_whatsapp( $s['whatsapp'] );
		case 'whatsapp_url':
			return ayesha_core_whatsapp_url( $message );
		case 'whatsapp_link':
			return ayesha_core_link( ayesha_core_whatsapp_url( $message ), $label ?? ayesha_core_format_whatsapp( $s['whatsapp'] ) );
		case 'email':
			return (string) $s['email'];
		case 'email_url':
			return '' === $s['email'] ? '' : 'mailto:' . $s['email'];
		case 'email_link':
			return ayesha_core_link( '' === $s['email'] ? '' : 'mailto:' . $s['email'], $label ?? (string) $s['email'] );
		case 'hours':
			return (string) $s['hours'];
		case 'instagram_handle':
			return ayesha_core_instagram_handle( $s['instagram_url'] );
		case 'instagram_url':
			return (string) $s['instagram_url'];
		case 'instagram_link':
			return ayesha_core_link( (string) $s['instagram_url'], $label ?? ayesha_core_instagram_handle( $s['instagram_url'] ), true );
		case 'service_areas':
			$lines = array_filter( array_map( 'trim', preg_split( '/\R/', (string) $s['service_areas'] ) ) );
			return implode( '<br>', array_map( 'esc_html', $lines ) );
		case 'address':
			return nl2br( esc_html( (string) $s['address'] ), false );
		case 'copyright':
			/* translators: 1: year, 2: business name. */
			return sprintf( __( '© %1$s %2$s', 'ayesha-core' ), wp_date( 'Y' ), AYESHA_CORE_BUSINESS_NAME );
	}
	return null;
}

/**
 * A wa.me link ready for an href attribute, keeping line breaks in the message.
 *
 * esc_url() removes "%0A", which would join a multi-line WhatsApp message into one line. A link
 * that is exactly https://wa.me/<digits>?text=<rawurlencode output> can't contain anything unsafe,
 * so it is only attribute-escaped; anything else goes through esc_url() as usual.
 *
 * @param string $url Link from ayesha_core_whatsapp_url().
 * @return string Escaped for an attribute.
 */
function ayesha_core_whatsapp_href( $url ) {
	if ( preg_match( '#^https://wa\.me/\d{8,15}(\?text=[A-Za-z0-9\-_.~%]*)?$#', (string) $url ) ) {
		return esc_attr( $url );
	}
	return esc_url( $url );
}
