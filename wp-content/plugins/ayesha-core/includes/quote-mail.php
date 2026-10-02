<?php
/**
 * Quote form emails: the request to the business (HTML + plain text, Reply-To the customer)
 * and a short confirmation to the customer when they gave an email address.
 *
 * Everything the customer typed is escaped (HTML) or sent as plain text.
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Send both emails.
 *
 * @param array  $data      Clean values.
 * @param string $reference Reference number.
 * @param int    $post_id   Enquiry ID.
 * @return array{admin: bool, customer: bool|null} Whether each was handed over for sending (null: no customer email).
 */
function ayesha_core_quote_send_emails( array $data, $reference, $post_id ) {
	$to     = (string) ayesha_core_setting( 'email' );
	$result = array(
		'admin'    => false,
		'customer' => null,
	);

	if ( is_email( $to ) ) {
		$mail    = ayesha_core_quote_admin_email( $data, $reference, $post_id );
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		if ( ! empty( $data['email'] ) && is_email( $data['email'] ) ) {
			$headers[] = 'Reply-To: ' . $data['email'];
		}
		$result['admin'] = ayesha_core_quote_mail( $to, $mail['subject'], $mail['html'], $mail['text'], $headers );
	}

	if ( ! empty( $data['email'] ) && is_email( $data['email'] ) ) {
		$mail               = ayesha_core_quote_customer_email( $data, $reference );
		$headers            = array( 'Content-Type: text/html; charset=UTF-8' );
		$result['customer'] = ayesha_core_quote_mail( $data['email'], $mail['subject'], $mail['html'], $mail['text'], $headers );
	}

	return $result;
}

/**
 * wp_mail() with a plain-text alternative part.
 *
 * @param string   $to      Recipient.
 * @param string   $subject Subject (plain text).
 * @param string   $html    HTML body.
 * @param string   $text    Plain-text body.
 * @param string[] $headers Headers.
 * @return bool
 */
function ayesha_core_quote_mail( $to, $subject, $html, $text, array $headers ) {
	$alt = static function ( $phpmailer ) use ( $text ) {
		$phpmailer->AltBody = $text; // phpcs:ignore WordPress.NamingConventions.ValidVariableName
	};
	add_action( 'phpmailer_init', $alt );
	$sent = wp_mail( $to, ayesha_core_quote_subject_text( $subject ), $html, $headers );
	remove_action( 'phpmailer_init', $alt );
	return (bool) $sent;
}

/**
 * A subject line can't contain line breaks.
 *
 * @param string $subject Subject.
 * @return string
 */
function ayesha_core_quote_subject_text( $subject ) {
	return trim( preg_replace( '/[\r\n\t]+/', ' ', (string) $subject ) );
}

/**
 * Plain HTML wrapper with inline styles (email clients ignore stylesheets).
 *
 * @param string $title Heading (plain text).
 * @param string $body  Safe HTML.
 * @return string
 */
function ayesha_core_quote_email_html( $title, $body ) {
	return '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width"><title>' . esc_html( $title ) . '</title></head>'
		. '<body style="margin:0;padding:0;background:#e8ebe9;color:#0f4d4a;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:1.5">'
		. '<div style="max-width:600px;margin:0 auto;padding:24px 16px">'
		. '<div style="height:8px;background:#f2b705"></div>'
		. '<div style="background:#ffffff;padding:24px">'
		. '<h1 style="margin:0 0 16px;font-size:22px;line-height:1.25;color:#0f4d4a">' . esc_html( $title ) . '</h1>'
		. $body
		. '</div>'
		. '<p style="margin:16px 0 0;font-size:13px;color:#3f6f6b">' . esc_html( AYESHA_CORE_BUSINESS_NAME ) . '</p>'
		. '</div></body></html>';
}

/**
 * A button-like link for emails.
 *
 * @param string $url   URL.
 * @param string $label Text.
 * @param string $bg    Background colour.
 * @param string $color Text colour.
 * @return string
 */
function ayesha_core_quote_email_button( $url, $label, $bg, $color ) {
	return '<a href="' . esc_url( $url, array( 'tel', 'https', 'mailto' ) ) . '" style="display:inline-block;margin:0 8px 8px 0;padding:12px 18px;border-radius:4px;background:' . esc_attr( $bg ) . ';color:' . esc_attr( $color ) . ';font-weight:bold;text-decoration:none">' . esc_html( $label ) . '</a>';
}

/**
 * The request, to the business.
 *
 * @param array  $data      Values.
 * @param string $reference Reference.
 * @param int    $post_id   Enquiry ID.
 * @return array{subject: string, html: string, text: string}
 */
function ayesha_core_quote_admin_email( array $data, $reference, $post_id ) {
	$move    = ayesha_core_quote_label( 'move_type', $data['move_type'] ?? '' );
	$subject = sprintf(
		/* translators: 1: reference, 2: type of move, 3: from, 4: to. */
		__( 'New quote request %1$s: %2$s, %3$s to %4$s', 'ayesha-core' ),
		$reference,
		$move,
		$data['from'] ?? '',
		$data['to'] ?? ''
	);
	$e164     = (string) ( $data['phone_e164'] ?? '' );
	$wa       = '' === $e164 ? '' : 'https://wa.me/' . ltrim( $e164, '+' );
	$edit_url = admin_url( 'post.php?post=' . (int) $post_id . '&action=edit' );
	$rows     = ayesha_core_quote_rows( $data );

	$html  = '<p style="margin:0 0 16px">' . esc_html(
		sprintf(
			/* translators: 1: customer name, 2: reference. */
			__( '%1$s sent a quote request from the website. Reference %2$s.', 'ayesha-core' ),
			$data['name'] ?? '',
			$reference
		)
	) . '</p><p style="margin:0 0 16px">';
	if ( '' !== $e164 ) {
		/* translators: %s: customer's phone number. */
		$html .= ayesha_core_quote_email_button( 'tel:' . $e164, sprintf( __( 'Call %s', 'ayesha-core' ), $e164 ), '#0f4d4a', '#ffffff' );
		$html .= ayesha_core_quote_email_button( $wa, __( 'WhatsApp the customer', 'ayesha-core' ), '#25d366', '#0f4d4a' );
	}
	$html .= '</p><table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse">';
	foreach ( $rows as $row ) {
		$html .= '<tr><th scope="row" style="padding:8px 12px 8px 0;border-top:1px solid #e8ebe9;text-align:left;vertical-align:top;width:38%;font-size:14px;color:#3f6f6b;font-weight:normal">' . esc_html( $row[0] ) . '</th>'
			. '<td style="padding:8px 0;border-top:1px solid #e8ebe9;vertical-align:top;font-weight:bold">' . nl2br( esc_html( $row[1] ), false ) . '</td></tr>';
	}
	$html .= '</table><p style="margin:16px 0 0;font-size:14px">';
	if ( ! empty( $data['email'] ) ) {
		$html .= esc_html__( 'Reply to this email to write to the customer.', 'ayesha-core' ) . ' ';
	}
	$html .= '<a href="' . esc_url( $edit_url ) . '" style="color:#0f4d4a">' . esc_html__( 'Open this enquiry in WordPress', 'ayesha-core' ) . '</a></p>';

	$text = sprintf(
		/* translators: 1: customer name, 2: reference. */
		__( '%1$s sent a quote request from the website. Reference %2$s.', 'ayesha-core' ),
		$data['name'] ?? '',
		$reference
	) . "\n\n";
	if ( '' !== $e164 ) {
		/* translators: %s: tel: link. */
		$text .= sprintf( __( 'Call: %s', 'ayesha-core' ), 'tel:' . $e164 ) . "\n";
		/* translators: %s: wa.me link. */
		$text .= sprintf( __( 'WhatsApp: %s', 'ayesha-core' ), $wa ) . "\n\n";
	}
	foreach ( $rows as $row ) {
		$text .= $row[0] . ': ' . str_replace( "\n", "\n  ", $row[1] ) . "\n";
	}
	/* translators: %s: admin URL. */
	$text .= "\n" . sprintf( __( 'Open this enquiry in WordPress: %s', 'ayesha-core' ), $edit_url ) . "\n";

	return array(
		'subject' => $subject,
		'html'    => ayesha_core_quote_email_html( $subject, $html ),
		'text'    => $text,
	);
}

/**
 * Short confirmation, to the customer.
 *
 * @param array  $data      Values.
 * @param string $reference Reference.
 * @return array{subject: string, html: string, text: string}
 */
function ayesha_core_quote_customer_email( array $data, $reference ) {
	/* translators: 1: reference, 2: business name. */
	$subject  = sprintf( __( 'We got your quote request %1$s (%2$s)', 'ayesha-core' ), $reference, AYESHA_CORE_BUSINESS_NAME );
	$phone    = (string) ayesha_core_setting( 'phone_primary' );
	$tel      = ayesha_core_tel_url( $phone );
	$wa_url   = ayesha_core_whatsapp_url( ayesha_core_quote_whatsapp_message( $reference, $data ) );
	$wa_label = ayesha_core_format_whatsapp( ayesha_core_setting( 'whatsapp' ) );
	/* translators: %s: customer name. */
	$hello = sprintf( __( 'Hello %s,', 'ayesha-core' ), $data['name'] ?? '' );
	/* translators: %s: reference number. */
	$got   = sprintf( __( 'Thank you. We got your quote request. Your reference is %s.', 'ayesha-core' ), $reference );
	$next  = __( 'We will reply on WhatsApp or by phone. If you want to send photos of your things, or talk to us sooner, use the links below. We are open 24 hours.', 'ayesha-core' );

	$html = '<p style="margin:0 0 12px">' . esc_html( $hello ) . '</p>'
		. '<p style="margin:0 0 12px">' . esc_html( $got ) . '</p>'
		. '<p style="margin:0 0 16px">' . esc_html( $next ) . '</p><p style="margin:0">';
	if ( '' !== $wa_url ) {
		/* translators: %s: WhatsApp number. */
		$html .= ayesha_core_quote_email_button( $wa_url, sprintf( __( 'WhatsApp %s', 'ayesha-core' ), $wa_label ), '#25d366', '#0f4d4a' );
	}
	if ( '' !== $tel ) {
		/* translators: %s: phone number. */
		$html .= ayesha_core_quote_email_button( $tel, sprintf( __( 'Call %s', 'ayesha-core' ), $phone ), '#0f4d4a', '#ffffff' );
	}
	$html .= '</p>';

	$text = $hello . "\n\n" . $got . "\n\n" . $next . "\n\n";
	if ( '' !== $wa_url ) {
		$text .= 'WhatsApp ' . $wa_label . ': ' . $wa_url . "\n";
	}
	if ( '' !== $tel ) {
		$text .= 'Call ' . $phone . ': ' . $tel . "\n";
	}
	$text .= "\n" . AYESHA_CORE_BUSINESS_NAME . "\n";

	return array(
		'subject' => $subject,
		'html'    => ayesha_core_quote_email_html( __( 'Quote request received', 'ayesha-core' ), $html ),
		'text'    => $text,
	);
}
