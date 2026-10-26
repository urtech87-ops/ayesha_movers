<?php
/**
 * WhatsApp quote form: the "ayesha/whatsapp-form" block. A short form (name, phone, service,
 * preferred date, message) that, in one click, saves the request under Enquiries, emails it to the
 * Business Info address (the same email as the quote form), then opens WhatsApp with the message
 * already typed to the business number (click-to-chat, wa.me; the visitor presses Send in WhatsApp).
 *
 * Same protection as the quote form (quote-form.php): nonce, honeypot, minimum fill time, the same
 * 5-per-hour limit, every field checked and escaped. Without JavaScript it works the same way.
 * Field markup and styles are the quote form's (quote-render.php, blocks/quote-form/style.css).
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'ayesha_core_register_whatsapp_form' );

/**
 * Register the block and its editor script (front-end style and script: the quote form's).
 */
function ayesha_core_register_whatsapp_form() {
	$dir = AYESHA_CORE_DIR . 'blocks/whatsapp-form/';
	wp_register_script(
		'ayesha-whatsapp-form-editor',
		AYESHA_CORE_URL . 'blocks/whatsapp-form/editor.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-i18n' ),
		AYESHA_CORE_VERSION . '.' . filemtime( $dir . 'editor.js' ),
		true
	);
	register_block_type( $dir, array( 'render_callback' => 'ayesha_core_whatsapp_form_render' ) );
}

/**
 * The services to choose from: the six services (as on the Home cards and the header menu).
 *
 * @return array<string, string>
 */
function ayesha_core_whatsapp_form_services() {
	return array(
		'house-shifting' => __( 'House, villa, flat and office shifting', 'ayesha-core' ),
		'packing'        => __( 'Packing and unpacking', 'ayesha-core' ),
		'furniture'      => __( 'Furniture dismantling and refitting', 'ayesha-core' ),
		'appliances'     => __( 'AC, TV and curtain removal', 'ayesha-core' ),
		'trucks'         => __( 'Truck hire: 8 hours or a full day', 'ayesha-core' ),
		'cargo'          => __( 'Container cargo to the GCC', 'ayesha-core' ),
	);
}

/**
 * A service's name ('' when unknown).
 *
 * @param string $key Service key.
 * @return string
 */
function ayesha_core_whatsapp_form_service_label( $key ) {
	return ayesha_core_whatsapp_form_services()[ $key ] ?? '';
}

/**
 * The block's labels, with emptied ones falling back to their original words.
 *
 * @param array $attributes Saved attributes.
 * @return array
 */
function ayesha_core_whatsapp_form_labels( array $attributes ) {
	$a = $attributes;
	foreach ( WP_Block_Type_Registry::get_instance()->get_registered( 'ayesha/whatsapp-form' )->attributes as $key => $schema ) {
		if ( 'string' === ( $schema['type'] ?? '' ) && 'note' !== $key && '' === trim( (string) ( $a[ $key ] ?? '' ) ) ) {
			$a[ $key ] = $schema['default'] ?? '';
		}
	}
	return $a;
}

/**
 * Render callback.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function ayesha_core_whatsapp_form_render( $attributes ) {
	$a     = ayesha_core_whatsapp_form_labels( $attributes );
	$state = ayesha_core_quote_current_state();
	$state = ( $state && 'whatsapp' === ( $state['form'] ?? '' ) && 'error' === ( $state['type'] ?? '' ) ) ? $state : null;
	$page  = get_the_ID() ? get_permalink( get_the_ID() ) : '';

	ayesha_core_quote_id_prefix( 'awf-' );
	$v = wp_parse_args(
		(array) ( $state['values'] ?? array() ),
		array(
			'name'    => '',
			'phone'   => '',
			'service' => '',
			'date'    => '',
			'message' => '',
		)
	);
	$e = (array) ( $state['errors'] ?? array() );

	$html  = $state ? ayesha_core_quote_render_summary( $state, $e, 'ayesha-wf-summary' ) : '';
	$html .= '<form class="ayesha-qf__form ayesha-wf__form" method="post" action="' . esc_url( $page ) . '" novalidate>'; // No #fragment: the browser would carry it on to WhatsApp.
	// The fields on the same grey panel as the quote form's parts (no legend: one short part).
	$html .= '<div class="ayesha-qf__step ayesha-wf__fields">';
	$html .= ayesha_core_quote_input( 'name', $a['labelName'], '', $v['name'], $e, true, array( 'autocomplete' => 'name' ) );
	$html .= ayesha_core_quote_input(
		'phone',
		$a['labelPhone'],
		$a['hintPhone'],
		$v['phone'],
		$e,
		true,
		array(
			'type'         => 'tel',
			'autocomplete' => 'tel',
			'inputmode'    => 'tel',
		)
	);
	$html .= ayesha_core_quote_select( 'service', $a['labelService'], ayesha_core_whatsapp_form_services(), $v['service'] );
	$html .= ayesha_core_quote_input(
		'date',
		$a['labelDate'],
		'',
		$v['date'],
		$e,
		false,
		array(
			'type' => 'date',
			'min'  => ayesha_core_quote_today(),
		)
	);
	$html .= ayesha_core_quote_textarea( 'message', $a['labelMessage'], $a['hintMessage'], $v['message'], $e, AYESHA_CORE_QUOTE_MESSAGE_MAX );
	$html .= '</div>';

	$html .= '<input type="hidden" name="ayesha_wf" value="1">'
		. '<input type="hidden" name="_aqnonce" value="' . esc_attr( wp_create_nonce( 'ayesha_whatsapp_form' ) ) . '">'
		. '<input type="hidden" name="aq_t" value="' . esc_attr( ayesha_core_quote_timer_token() ) . '">'
		. ayesha_core_quote_honeypot();

	$html .= '<div class="ayesha-qf__submit"><button type="submit" class="wp-element-button ayesha-qf__button ayesha-wf__button">' . esc_html( $a['submitText'] ) . '</button>';
	if ( '' !== trim( (string) $a['note'] ) ) {
		$html .= '<p class="ayesha-qf__privacy">' . esc_html( $a['note'] ) . '</p>';
	}
	$html .= '</div></form>';
	ayesha_core_quote_id_prefix( 'aqf-' );

	return '<div ' . get_block_wrapper_attributes( array( 'class' => 'ayesha-qf ayesha-qf--single ayesha-wf' ) ) . '>' . $html . '</div>';
}

add_action( 'template_redirect', 'ayesha_core_whatsapp_form_handle', 1 );

/**
 * Handle a posted WhatsApp form: check → save → email → open WhatsApp.
 */
function ayesha_core_whatsapp_form_handle() {
	if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) || ! isset( $_POST['ayesha_wf'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- checked below.
		return;
	}
	$page_id = (int) get_queried_object_id();
	$back    = $page_id ? get_permalink( $page_id ) : home_url( '/' );

	// phpcs:disable WordPress.Security.NonceVerification.Missing -- the nonce is one of the checks below; every value is validated.
	$raw     = isset( $_POST['aq'] ) && is_array( $_POST['aq'] ) ? wp_unslash( $_POST['aq'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised field by field below.
	$nonce   = isset( $_POST['_aqnonce'] ) ? sanitize_key( wp_unslash( $_POST['_aqnonce'] ) ) : '';
	$honey   = isset( $_POST['aq_website'] ) ? trim( (string) wp_unslash( $_POST['aq_website'] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- only tested for emptiness.
	$seconds = ayesha_core_quote_seconds_open( isset( $_POST['aq_t'] ) ? sanitize_text_field( wp_unslash( $_POST['aq_t'] ) ) : '' );
	// phpcs:enable

	// Name, phone and message: the quote form's checks (short form, no "best way to reply", no email).
	unset( $raw['email'], $raw['reply'] );
	list( $data, $errors ) = ayesha_core_quote_validate( $raw, false, false );
	$data['via']           = 'whatsapp';
	$service               = isset( $raw['service'] ) && is_scalar( $raw['service'] ) ? (string) $raw['service'] : '';
	$data['service']       = '' !== ayesha_core_whatsapp_form_service_label( $service ) ? $service : '';
	$data['date']          = ayesha_core_quote_line( $raw['date'] ?? '', 10 );
	if ( '' !== $data['date'] ) {
		$date = DateTimeImmutable::createFromFormat( '!Y-m-d', $data['date'], wp_timezone() );
		if ( ! $date || $date->format( 'Y-m-d' ) !== $data['date'] ) {
			$errors['date'] = __( 'Enter a real date, or leave it empty', 'ayesha-core' );
			$data['date']   = '';
		} elseif ( $data['date'] < ayesha_core_quote_today() ) {
			$errors['date'] = __( 'Choose today or a date after today', 'ayesha-core' );
		}
	}

	$problem = '';
	if ( ! wp_verify_nonce( $nonce, 'ayesha_whatsapp_form' ) ) {
		$problem = 'expired';
	} elseif ( '' !== $honey ) {
		$problem = 'blocked';
	} elseif ( null === $seconds || $seconds < AYESHA_CORE_QUOTE_MIN_SECONDS ) {
		$problem = 'fast';
	} elseif ( count( ayesha_core_quote_recent_sends() ) >= AYESHA_CORE_QUOTE_PER_HOUR ) {
		$problem = 'limit';
	}

	$back_with = static function ( $problem, $errors ) use ( $data, $back ) {
		$token = ayesha_core_quote_store_state(
			array(
				'form'    => 'whatsapp',
				'type'    => 'error',
				'problem' => $problem,
				'errors'  => '' === $problem ? $errors : array(),
				'values'  => $data,
			),
			10 * MINUTE_IN_SECONDS
		);
		ayesha_core_quote_redirect( add_query_arg( 'quote', $token, $back ) . '#ayesha-wf-summary' );
	};
	if ( '' !== $problem || $errors ) {
		$back_with( $problem, $errors );
	}

	$reference = ayesha_core_enquiry_next_reference();
	$post_id   = ayesha_core_enquiry_save( $data, $reference );
	if ( is_wp_error( $post_id ) ) {
		$back_with( 'save', array() );
	}
	ayesha_core_quote_count_send();
	update_post_meta( $post_id, '_ayesha_mail', ayesha_core_quote_send_emails( $data, $reference, $post_id ) );

	$url = ayesha_core_whatsapp_url( ayesha_core_whatsapp_form_message( $reference, $data ) );
	if ( '' === $url ) {
		// No WhatsApp number in Business Info: show the quote form's "sent" panel instead.
		$token = ayesha_core_quote_store_state(
			array(
				'type'      => 'success',
				'reference' => $reference,
				'values'    => array_intersect_key( $data, array_flip( array( 'name', 'message', 'parts' ) ) ),
			),
			30 * MINUTE_IN_SECONDS
		);
		ayesha_core_quote_redirect( add_query_arg( 'quote', $token, $back ) . '#ayesha-qf-result' );
	}
	// wp_redirect() would strip the %0A line breaks from the message; this link is exactly
	// https://wa.me/<digits>?text=<rawurlencode output> (no raw line breaks), so it is safe as a header.
	if ( preg_match( '#^https://wa\.me/\d{8,15}(\?text=[A-Za-z0-9\-_.~%]*)?$#', $url ) ) {
		nocache_headers();
		header( 'Location: ' . $url, true, 303 );
		exit;
	}
	ayesha_core_quote_redirect( $back );
}

/**
 * The WhatsApp message: reference, name, phone, service, date and the message (plain text;
 * URL-encoded by ayesha_core_whatsapp_url()).
 *
 * @param string $reference Reference.
 * @param array  $data      Clean values.
 * @return string
 */
function ayesha_core_whatsapp_form_message( $reference, array $data ) {
	$lines = array(
		/* translators: %s: business name. */
		sprintf( __( 'Hi %s, I just sent a quote request on your website.', 'ayesha-core' ), AYESHA_CORE_BUSINESS_NAME ),
		/* translators: %s: reference number. */
		sprintf( __( 'Reference: %s', 'ayesha-core' ), $reference ),
		/* translators: %s: customer name. */
		sprintf( __( 'Name: %s', 'ayesha-core' ), $data['name'] ?? '' ),
		/* translators: %s: customer phone number. */
		sprintf( __( 'Phone: %s', 'ayesha-core' ), $data['phone'] ?? '' ),
	);
	$service = ayesha_core_whatsapp_form_service_label( (string) ( $data['service'] ?? '' ) );
	if ( '' !== $service ) {
		/* translators: %s: service. */
		$lines[] = sprintf( __( 'Service: %s', 'ayesha-core' ), $service );
	}
	if ( '' !== (string) ( $data['date'] ?? '' ) ) {
		/* translators: %s: preferred date. */
		$lines[] = sprintf( __( 'Date: %s', 'ayesha-core' ), ayesha_core_quote_date_text( $data ) );
	}
	$message = trim( (string) ( $data['message'] ?? '' ) );
	if ( '' !== $message ) {
		// Kept short: a wa.me link has to fit in a URL. The full message is in the email and in WordPress.
		$short = mb_strlen( $message ) > 300 ? rtrim( mb_substr( $message, 0, 300 ) ) . '…' : $message;
		/* translators: %s: the customer's message about the move. */
		$lines[] = sprintf( __( 'About my move: %s', 'ayesha-core' ), $short );
	}
	return implode( "\n", $lines );
}
