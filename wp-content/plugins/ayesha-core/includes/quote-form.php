<?php
/**
 * Quote form: the "ayesha/quote-form" block, its fields, validation and the POST handler.
 *
 * Works without JavaScript: the form posts to the page it is on, the handler checks it
 * (nonce, honeypot, minimum fill time, 5 per hour per IP, every field), then redirects back
 * with a short-lived token (?quote=…) that shows either the errors with the typed values, or
 * "Quote request sent" with the reference number. Markup: quote-render.php. Emails: quote-mail.php.
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

/** Seconds a person needs at least to fill in the form; faster posts are treated as bots. */
const AYESHA_CORE_QUOTE_MIN_SECONDS = 3;

/** Successful requests allowed per IP address per hour. */
const AYESHA_CORE_QUOTE_PER_HOUR = 5;

/** Longest "Tell us about your move" message, in characters. */
const AYESHA_CORE_QUOTE_MESSAGE_MAX = 1000;

/**
 * Empty values for every field of parts 2 and 3 (used when those parts are switched off).
 *
 * @return array<string, mixed>
 */
function ayesha_core_quote_move_defaults() {
	return array(
		'move_type'     => '',
		'from'          => '',
		'to'            => '',
		'date'          => '',
		'flexible'      => false,
		'size'          => '',
		'pickup_floor'  => '',
		'pickup_lift'   => '',
		'dropoff_floor' => '',
		'dropoff_lift'  => '',
		'services'      => array(),
		'items'         => '',
		'truck'         => 'none',
		'truck_time'    => '',
	);
}

/**
 * The quote form block's settings on a page, read from the saved page (so a visitor can't switch
 * required fields off by editing the form): "Show parts 2 and 3" and "Ask 'Best way to reply'".
 * Both are on when there is no form on the page or a setting was never changed.
 *
 * @param int $post_id Page with the form.
 * @return array{full: bool, reply: bool}
 */
function ayesha_core_quote_form_settings( $post_id ) {
	$attrs = ayesha_core_quote_form_attrs( $post_id );
	return array(
		'full'  => ! array_key_exists( 'showMoveParts', $attrs ) || ! empty( $attrs['showMoveParts'] ),
		'reply' => ! array_key_exists( 'askReply', $attrs ) || ! empty( $attrs['askReply'] ),
	);
}

/**
 * The saved attributes of the first quote form block on a page (empty when there is none).
 *
 * @param int $post_id Page.
 * @return array
 */
function ayesha_core_quote_form_attrs( $post_id ) {
	$find = static function ( array $blocks ) use ( &$find ) {
		foreach ( $blocks as $block ) {
			if ( 'ayesha/quote-form' === ( $block['blockName'] ?? '' ) ) {
				return $block;
			}
			$inner = $find( $block['innerBlocks'] ?? array() );
			if ( $inner ) {
				return $inner;
			}
		}
		return null;
	};
	$block = $post_id ? $find( parse_blocks( (string) get_post_field( 'post_content', $post_id, 'raw' ) ) ) : null;
	return $block ? (array) $block['attrs'] : array();
}

/**
 * Whether an enquiry came from the short form (part 1 only).
 *
 * @param array $data Values.
 * @return bool
 */
function ayesha_core_quote_is_short( array $data ) {
	return 'short' === ( $data['parts'] ?? '' );
}

add_action( 'init', 'ayesha_core_register_quote_block' );

/**
 * Register the block, its editor script and its (deferred) front-end script.
 */
function ayesha_core_register_quote_block() {
	$dir = AYESHA_CORE_DIR . 'blocks/quote-form/';
	$url = AYESHA_CORE_URL . 'blocks/quote-form/';
	wp_register_script(
		'ayesha-quote-form-editor',
		$url . 'editor.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-i18n' ),
		AYESHA_CORE_VERSION . '.' . filemtime( $dir . 'editor.js' ),
		true
	);
	wp_register_script(
		'ayesha-quote-form-view',
		$url . 'view.js',
		array(),
		AYESHA_CORE_VERSION . '.' . filemtime( $dir . 'view.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	wp_register_style( 'ayesha-quote-form', $url . 'style.css', array(), AYESHA_CORE_VERSION . '.' . filemtime( $dir . 'style.css' ) );
	register_block_type( $dir, array( 'render_callback' => 'ayesha_core_quote_render' ) );
}

/**
 * Choices for the fields that have them. Keys are stored; labels are shown and emailed.
 *
 * @return array<string, array<string, string>>
 */
function ayesha_core_quote_options() {
	$floors = array( 'ground' => __( 'Ground floor', 'ayesha-core' ) );
	foreach ( range( 1, 10 ) as $n ) {
		/* translators: %s: floor number with its ordinal suffix, e.g. 3rd. */
		$floors[ (string) $n ] = sprintf( __( '%s floor', 'ayesha-core' ), $n . ayesha_core_ordinal_suffix( $n ) );
	}
	$floors['11'] = __( '11th floor or higher', 'ayesha-core' );

	return array(
		'reply'      => array(
			'whatsapp' => __( 'WhatsApp', 'ayesha-core' ),
			'call'     => __( 'Phone call', 'ayesha-core' ),
			'email'    => __( 'Email', 'ayesha-core' ),
		),
		'move_type'  => array(
			'house'  => __( 'House', 'ayesha-core' ),
			'villa'  => __( 'Villa', 'ayesha-core' ),
			'flat'   => __( 'Flat', 'ayesha-core' ),
			'office' => __( 'Office', 'ayesha-core' ),
			'cargo'  => __( 'GCC cargo', 'ayesha-core' ),
			'truck'  => __( 'Truck hire only', 'ayesha-core' ),
			'other'  => __( 'Other', 'ayesha-core' ),
		),
		'size'       => array(
			'studio' => __( 'Studio', 'ayesha-core' ),
			'1'      => __( '1 bedroom', 'ayesha-core' ),
			'2'      => __( '2 bedrooms', 'ayesha-core' ),
			'3'      => __( '3 bedrooms', 'ayesha-core' ),
			'4'      => __( '4 bedrooms', 'ayesha-core' ),
			'5'      => __( '5 or more bedrooms', 'ayesha-core' ),
			'villa'  => __( 'Villa', 'ayesha-core' ),
			'office' => __( 'Office', 'ayesha-core' ),
		),
		'floor'      => $floors,
		'lift'       => array(
			'yes' => __( 'Yes, there is a lift', 'ayesha-core' ),
			'no'  => __( 'No lift', 'ayesha-core' ),
		),
		'services'   => array(
			'packing'   => __( 'Packing', 'ayesha-core' ),
			'unpacking' => __( 'Unpacking', 'ayesha-core' ),
			'fragile'   => __( 'Fragile and crockery packing', 'ayesha-core' ),
			'furniture' => __( 'Furniture dismantling and refitting', 'ayesha-core' ),
			'ac'        => __( 'AC removal and fitting', 'ayesha-core' ),
			'tv'        => __( 'TV removal and mounting', 'ayesha-core' ),
			'curtains'  => __( 'Curtains and blinds', 'ayesha-core' ),
			'debris'    => __( 'Removing the packing debris', 'ayesha-core' ),
			'container' => __( '20ft or 40ft container', 'ayesha-core' ),
			'customs'   => __( 'Customs documents', 'ayesha-core' ),
		),
		'truck'      => array(
			'none' => __( 'No truck hire', 'ayesha-core' ),
			'dyna' => __( 'Dyna truck', 'ayesha-core' ),
			'six'  => __( '6-wheel truck', 'ayesha-core' ),
		),
		'truck_time' => array(
			'8h'  => __( '8 hours', 'ayesha-core' ),
			'day' => __( 'Full day', 'ayesha-core' ),
		),
	);
}

/**
 * "st", "nd", "rd" or "th".
 *
 * @param int $n Number.
 * @return string
 */
function ayesha_core_ordinal_suffix( $n ) {
	if ( in_array( $n % 100, array( 11, 12, 13 ), true ) ) {
		return 'th';
	}
	return array( 1 => 'st', 2 => 'nd', 3 => 'rd' )[ $n % 10 ] ?? 'th';
}

/**
 * Label of a stored choice ('' when unknown or empty).
 *
 * @param string $field Options key (floor and lift share their lists between pickup and drop-off).
 * @param string $value Stored key.
 * @return string
 */
function ayesha_core_quote_label( $field, $value ) {
	return ayesha_core_quote_options()[ $field ][ (string) $value ] ?? '';
}

/**
 * One line of plain text: valid UTF-8, no control characters, single spaces, at most $max characters.
 * Tags are not stripped: the text is stored as typed and escaped wherever it is shown.
 *
 * @param mixed $value Raw value (already unslashed).
 * @param int   $max   Maximum length.
 * @return string
 */
function ayesha_core_quote_line( $value, $max ) {
	$value = wp_check_invalid_utf8( is_scalar( $value ) ? (string) $value : '' );
	$value = trim( preg_replace( '/[\p{Cc}\p{Cf}\p{Z}]+/u', ' ', $value ) );
	return mb_substr( $value, 0, $max );
}

/**
 * Several lines of plain text (same rules; line breaks kept, at most one blank line in a row).
 *
 * @param mixed $value Raw value.
 * @return string
 */
function ayesha_core_quote_lines( $value ) {
	$value = wp_check_invalid_utf8( is_scalar( $value ) ? (string) $value : '' );
	$lines = preg_split( '/\R/u', $value );
	$lines = array_map(
		static function ( $line ) {
			return trim( preg_replace( '/[\p{Cc}\p{Cf}\p{Z}]+/u', ' ', $line ) );
		},
		$lines
	);
	return trim( preg_replace( "/\n{3,}/", "\n\n", implode( "\n", $lines ) ) );
}

/**
 * A phone number in E.164 form (+97334448236), or false when it isn't one.
 *
 * Accepts: an 8-digit Bahrain number (3444 8236), +973 / 00973 / 973 in front of it,
 * a Saudi mobile written locally (05x xxx xxxx), and any number with a country code
 * (+ or 00, 8 to 15 digits). Spaces, dashes, dots and brackets are ignored.
 *
 * @param string $phone As typed.
 * @return string|false
 */
function ayesha_core_quote_phone_e164( $phone ) {
	$s = preg_replace( '/[\s\-().\/]/u', '', (string) $phone );
	if ( str_starts_with( $s, '00' ) ) {
		$s = '+' . substr( $s, 2 );
	}
	if ( ! preg_match( '/^\+?\d+$/', $s ) ) {
		return false;
	}
	$plus   = str_starts_with( $s, '+' );
	$digits = ltrim( $s, '+' );
	if ( ! $plus ) {
		if ( preg_match( '/^\d{8}$/', $digits ) ) {
			return '+973' . $digits;
		}
		if ( preg_match( '/^05\d{8}$/', $digits ) ) {
			return '+966' . substr( $digits, 1 );
		}
		if ( preg_match( '/^(973\d{8}|9665\d{8})$/', $digits ) ) {
			return '+' . $digits;
		}
		return false;
	}
	if ( str_starts_with( $digits, '973' ) ) {
		return preg_match( '/^973\d{8}$/', $digits ) ? '+' . $digits : false;
	}
	if ( str_starts_with( $digits, '966' ) ) {
		return preg_match( '/^966\d{9}$/', $digits ) ? '+' . $digits : false;
	}
	return preg_match( '/^[1-9]\d{7,14}$/', $digits ) ? '+' . $digits : false;
}

/**
 * Today in the site's time zone (Asia/Bahrain), Y-m-d.
 *
 * @return string
 */
function ayesha_core_quote_today() {
	return wp_date( 'Y-m-d' );
}

/**
 * Check and clean every field.
 *
 * With $full false (the block's "Show parts 2 and 3" is off), only part 1 is read: the move and
 * service fields are ignored, never required and stored empty, and the enquiry is marked
 * 'parts' => 'short' so the admin screen, the emails and the WhatsApp text leave them out.
 *
 * With $ask_reply false ("Ask 'Best way to reply'" is off), the reply choice is not asked: it is
 * stored empty and left out of the emails and the admin screen.
 *
 * @param array $raw       Unslashed $_POST['aq'].
 * @param bool  $full      Whether parts 2 and 3 are on the form.
 * @param bool  $ask_reply Whether "Best way to reply" is on the form.
 * @return array{0: array, 1: array<string, string>} Clean values (as typed where invalid, so the form can show them again) and errors by field.
 */
function ayesha_core_quote_validate( array $raw, $full = true, $ask_reply = true ) {
	$options = ayesha_core_quote_options();
	$errors  = array();
	$pick    = static function ( $key, $list ) use ( $raw, $options ) {
		$value = isset( $raw[ $key ] ) && is_scalar( $raw[ $key ] ) ? (string) $raw[ $key ] : '';
		return isset( $options[ $list ][ $value ] ) ? $value : '';
	};

	$data = array(
		'name'          => ayesha_core_quote_line( $raw['name'] ?? '', 100 ),
		'phone'         => ayesha_core_quote_line( $raw['phone'] ?? '', 40 ),
		'phone_e164'    => '',
		'email'         => ayesha_core_quote_line( $raw['email'] ?? '', 120 ),
		'reply'         => $pick( 'reply', 'reply' ),
		'message'       => ayesha_core_quote_lines( $raw['message'] ?? '' ),
		'parts'         => $full ? 'all' : 'short',
		'move_type'     => $pick( 'move_type', 'move_type' ),
		'from'          => ayesha_core_quote_line( $raw['from'] ?? '', 120 ),
		'to'            => ayesha_core_quote_line( $raw['to'] ?? '', 120 ),
		'date'          => ayesha_core_quote_line( $raw['date'] ?? '', 10 ),
		'flexible'      => ! empty( $raw['flexible'] ),
		'size'          => $pick( 'size', 'size' ),
		'pickup_floor'  => $pick( 'pickup_floor', 'floor' ),
		'pickup_lift'   => $pick( 'pickup_lift', 'lift' ),
		'dropoff_floor' => $pick( 'dropoff_floor', 'floor' ),
		'dropoff_lift'  => $pick( 'dropoff_lift', 'lift' ),
		'services'      => array(),
		'items'         => ayesha_core_quote_lines( $raw['items'] ?? '' ),
		'truck'         => $pick( 'truck', 'truck' ),
		'truck_time'    => $pick( 'truck_time', 'truck_time' ),
	);
	if ( ! $ask_reply ) {
		$data['reply'] = '';
	} elseif ( '' === $data['reply'] ) {
		$data['reply'] = 'whatsapp';
	}
	if ( '' === $data['truck'] ) {
		$data['truck'] = 'none';
	}
	if ( 'none' === $data['truck'] ) {
		$data['truck_time'] = '';
	}
	foreach ( (array) ( $raw['services'] ?? array() ) as $service ) {
		if ( is_scalar( $service ) && isset( $options['services'][ (string) $service ] ) && ! in_array( (string) $service, $data['services'], true ) ) {
			$data['services'][] = (string) $service;
		}
	}
	if ( ! $full ) {
		// Parts 2 and 3 are not on the form: whatever was posted for them is dropped.
		$data = array_merge( $data, ayesha_core_quote_move_defaults() );
	}

	if ( '' === $data['name'] ) {
		$errors['name'] = __( 'Enter your name', 'ayesha-core' );
	}

	if ( '' === $data['phone'] ) {
		$errors['phone'] = __( 'Enter a phone number so we can reply', 'ayesha-core' );
	} else {
		$e164 = ayesha_core_quote_phone_e164( $data['phone'] );
		if ( false === $e164 ) {
			$errors['phone'] = __( 'Enter a phone number we can reach: 8 digits for Bahrain, or the country code first for other countries, e.g. +966', 'ayesha-core' );
		} else {
			$data['phone_e164'] = $e164;
		}
	}

	if ( '' !== $data['email'] && ! is_email( $data['email'] ) ) {
		$errors['email'] = __( 'Enter an email address like name@example.com, or leave it empty', 'ayesha-core' );
	} elseif ( '' === $data['email'] && 'email' === $data['reply'] ) {
		$errors['email'] = __( 'Enter your email address, or choose WhatsApp or a phone call as the way to reply', 'ayesha-core' );
	}

	if ( mb_strlen( $data['message'] ) > AYESHA_CORE_QUOTE_MESSAGE_MAX ) {
		/* translators: %s: maximum number of characters. */
		$errors['message'] = sprintf( __( 'Keep this under %s characters. You can send photos and more details on WhatsApp afterwards', 'ayesha-core' ), number_format_i18n( AYESHA_CORE_QUOTE_MESSAGE_MAX ) );
		$data['message']   = mb_substr( $data['message'], 0, 2 * AYESHA_CORE_QUOTE_MESSAGE_MAX );
	}

	if ( ! $full ) {
		return array( $data, $errors );
	}

	if ( '' === $data['move_type'] ) {
		$errors['move_type'] = __( 'Choose the type of move', 'ayesha-core' );
	}
	if ( '' === $data['from'] ) {
		$errors['from'] = __( 'Enter the area or city you are moving from', 'ayesha-core' );
	}
	if ( '' === $data['to'] ) {
		$errors['to'] = __( 'Enter the area, city or country you are moving to', 'ayesha-core' );
	}

	if ( '' !== $data['date'] ) {
		$date = DateTimeImmutable::createFromFormat( '!Y-m-d', $data['date'], wp_timezone() );
		if ( ! $date || $date->format( 'Y-m-d' ) !== $data['date'] ) {
			$errors['date'] = __( 'Enter a real date, or leave it empty', 'ayesha-core' );
			$data['date']   = ''; // A date field can't show an impossible date anyway.
		} elseif ( $data['date'] < ayesha_core_quote_today() ) {
			$errors['date'] = __( 'Choose today or a date after today', 'ayesha-core' );
		}
	}

	if ( mb_strlen( $data['items'] ) > 1000 ) {
		$errors['items'] = __( 'Keep this under 1,000 characters. You can send photos and more details on WhatsApp afterwards', 'ayesha-core' );
		$data['items']   = mb_substr( $data['items'], 0, 2000 );
	}

	return array( $data, $errors );
}

/**
 * Field order for the error summary, and the element each error links to.
 *
 * @return array<string, string> Field => element id.
 */
function ayesha_core_quote_error_targets() {
	return array(
		'name'      => 'aqf-name',
		'phone'     => 'aqf-phone',
		'email'     => 'aqf-email',
		'message'   => 'aqf-message',
		'move_type' => 'aqf-move_type-house',
		'from'      => 'aqf-from',
		'to'        => 'aqf-to',
		'date'      => 'aqf-date',
		'items'     => 'aqf-items',
	);
}

/*
 * ---------------------------------------------------------------------------
 * Spam protection
 * ---------------------------------------------------------------------------
 */

/**
 * Signed timestamp for the hidden "form opened at" field.
 *
 * @param int|null $time Unix time (default now).
 * @return string
 */
function ayesha_core_quote_timer_token( $time = null ) {
	$time = $time ?? time();
	return $time . '.' . substr( wp_hash( 'ayesha_qf_time|' . $time, 'nonce' ), 0, 20 );
}

/**
 * Seconds since the form was opened, or null when the field is missing or was tampered with.
 *
 * @param string $token Posted value.
 * @return int|null
 */
function ayesha_core_quote_seconds_open( $token ) {
	if ( ! preg_match( '/^(\d{9,11})\.([a-f0-9]{20})$/', (string) $token, $m ) || ! hash_equals( ayesha_core_quote_timer_token( (int) $m[1] ), $token ) ) {
		return null;
	}
	return time() - (int) $m[1];
}

/**
 * Transient key for this visitor's rate limit. The IP address is hashed with a site secret and
 * never stored. (Behind a proxy or CDN at go-live, REMOTE_ADDR is the proxy: see docs/email-setup.md.)
 *
 * @return string
 */
function ayesha_core_quote_rate_key() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? (string) filter_var( wp_unslash( $_SERVER['REMOTE_ADDR'] ), FILTER_VALIDATE_IP ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- validated as an IP.
	return 'ayesha_qf_rl_' . substr( hash_hmac( 'sha256', $ip, wp_salt( 'nonce' ) ), 0, 32 );
}

/**
 * Times of this visitor's successful requests in the last hour.
 *
 * @return int[]
 */
function ayesha_core_quote_recent_sends() {
	$times = get_transient( ayesha_core_quote_rate_key() );
	$since = time() - HOUR_IN_SECONDS;
	return array_values( array_filter( is_array( $times ) ? $times : array(), static fn( $t ) => (int) $t > $since ) );
}

/**
 * Record a successful request for the rate limit.
 */
function ayesha_core_quote_count_send() {
	$times   = ayesha_core_quote_recent_sends();
	$times[] = time();
	set_transient( ayesha_core_quote_rate_key(), $times, HOUR_IN_SECONDS );
}

/*
 * ---------------------------------------------------------------------------
 * POST → validate → redirect
 * ---------------------------------------------------------------------------
 */

add_action( 'template_redirect', 'ayesha_core_quote_handle', 1 );

/**
 * Handle a posted quote form.
 */
function ayesha_core_quote_handle() {
	if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) || ! isset( $_POST['ayesha_qf'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- checked below.
		return;
	}
	$page_id = (int) get_queried_object_id();
	$back    = $page_id ? get_permalink( $page_id ) : home_url( '/' );

	// phpcs:disable WordPress.Security.NonceVerification.Missing -- the nonce is one of the checks below; every value is validated.
	$raw = isset( $_POST['aq'] ) && is_array( $_POST['aq'] ) ? wp_unslash( $_POST['aq'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised field by field in ayesha_core_quote_validate().
	$form                  = ayesha_core_quote_form_settings( $page_id );
	list( $data, $errors ) = ayesha_core_quote_validate( $raw, $form['full'], $form['reply'] );

	$nonce   = isset( $_POST['_aqnonce'] ) ? sanitize_key( wp_unslash( $_POST['_aqnonce'] ) ) : '';
	$honey   = isset( $_POST['aq_website'] ) ? trim( (string) wp_unslash( $_POST['aq_website'] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- only tested for emptiness.
	$seconds = ayesha_core_quote_seconds_open( isset( $_POST['aq_t'] ) ? sanitize_text_field( wp_unslash( $_POST['aq_t'] ) ) : '' );
	// phpcs:enable

	$problem = '';
	if ( ! wp_verify_nonce( $nonce, 'ayesha_quote_form' ) ) {
		$problem = 'expired';
	} elseif ( '' !== $honey ) {
		$problem = 'blocked';
	} elseif ( null === $seconds || $seconds < AYESHA_CORE_QUOTE_MIN_SECONDS ) {
		$problem = 'fast';
	} elseif ( count( ayesha_core_quote_recent_sends() ) >= AYESHA_CORE_QUOTE_PER_HOUR ) {
		$problem = 'limit';
	}

	if ( '' !== $problem || $errors ) {
		$token = ayesha_core_quote_store_state(
			array(
				'type'    => 'error',
				'problem' => $problem,
				// A form-level problem is shown on its own; field errors would only add noise.
				'errors'  => '' === $problem ? $errors : array(),
				'values'  => $data,
			),
			10 * MINUTE_IN_SECONDS
		);
		ayesha_core_quote_redirect( add_query_arg( 'quote', $token, $back ) . '#ayesha-qf-summary' );
	}

	$reference = ayesha_core_enquiry_next_reference();
	$post_id   = ayesha_core_enquiry_save( $data, $reference );
	if ( is_wp_error( $post_id ) ) {
		$token = ayesha_core_quote_store_state(
			array(
				'type'    => 'error',
				'problem' => 'save',
				'errors'  => array(),
				'values'  => $data,
			),
			10 * MINUTE_IN_SECONDS
		);
		ayesha_core_quote_redirect( add_query_arg( 'quote', $token, $back ) . '#ayesha-qf-summary' );
	}
	ayesha_core_quote_count_send();
	update_post_meta( $post_id, '_ayesha_mail', ayesha_core_quote_send_emails( $data, $reference, $post_id ) );

	$token = ayesha_core_quote_store_state(
		array(
			'type'      => 'success',
			'reference' => $reference,
			'values'    => array_intersect_key( $data, array_flip( array( 'name', 'move_type', 'from', 'to', 'date', 'flexible', 'email', 'message', 'parts' ) ) ),
		),
		30 * MINUTE_IN_SECONDS
	);
	ayesha_core_quote_redirect( add_query_arg( 'quote', $token, $back ) . '#ayesha-qf-result' );
}

/**
 * Redirect (303, so a reload doesn't post again) and stop.
 *
 * @param string $url Same-site URL.
 */
function ayesha_core_quote_redirect( $url ) {
	wp_safe_redirect( $url, 303 );
	exit;
}

/**
 * Keep the result for the page that follows the redirect.
 *
 * @param array $state State.
 * @param int   $ttl   Seconds.
 * @return string Token for the URL.
 */
function ayesha_core_quote_store_state( array $state, $ttl ) {
	$token = wp_generate_password( 20, false );
	set_transient( 'ayesha_qf_s_' . $token, $state, $ttl );
	return $token;
}

/**
 * The result for this page view, if the URL has a valid token. Error states are deleted once read
 * (they hold what the customer typed); "sent" states stay until they expire, so a reload still shows them.
 *
 * @return array|null
 */
function ayesha_core_quote_current_state() {
	static $state = false;
	if ( false !== $state ) {
		return $state;
	}
	$state = null;
	$token = isset( $_GET['quote'] ) ? (string) wp_unslash( $_GET['quote'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- checked against a strict pattern.
	if ( ! preg_match( '/^[A-Za-z0-9]{20}$/', $token ) ) {
		return $state;
	}
	$saved = get_transient( 'ayesha_qf_s_' . $token );
	if ( is_array( $saved ) ) {
		$state = $saved;
		if ( 'error' === ( $saved['type'] ?? '' ) ) {
			delete_transient( 'ayesha_qf_s_' . $token );
		}
	}
	return $state;
}

add_filter( 'wp_robots', 'ayesha_core_quote_robots' );

/**
 * Result pages (?quote=…) are never indexed.
 *
 * @param array $robots Directives.
 * @return array
 */
function ayesha_core_quote_robots( $robots ) {
	if ( isset( $_GET['quote'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$robots['noindex'] = true;
	}
	return $robots;
}

/*
 * ---------------------------------------------------------------------------
 * Shared text helpers (page, admin, emails, WhatsApp)
 * ---------------------------------------------------------------------------
 */

/**
 * "Sunday 12 October 2026", "… (flexible)", "Flexible" or "Not given".
 *
 * @param array $data Values.
 * @return string Plain text.
 */
function ayesha_core_quote_date_text( array $data ) {
	$text = '';
	if ( ! empty( $data['date'] ) ) {
		$date = DateTimeImmutable::createFromFormat( '!Y-m-d', (string) $data['date'], wp_timezone() );
		$text = $date ? wp_date( 'l j F Y', $date->getTimestamp() ) : (string) $data['date'];
	}
	if ( ! empty( $data['flexible'] ) ) {
		return '' === $text ? __( 'Flexible', 'ayesha-core' ) : $text . ' ' . __( '(flexible)', 'ayesha-core' );
	}
	return '' === $text ? __( 'Not given', 'ayesha-core' ) : $text;
}

/**
 * "3rd floor, no lift".
 *
 * @param string $floor Floor key.
 * @param string $lift  Lift key.
 * @return string
 */
function ayesha_core_quote_floor_text( $floor, $lift ) {
	$parts = array_filter(
		array(
			ayesha_core_quote_label( 'floor', $floor ),
			'yes' === $lift ? __( 'lift', 'ayesha-core' ) : ( 'no' === $lift ? __( 'no lift', 'ayesha-core' ) : '' ),
		)
	);
	return $parts ? implode( ', ', $parts ) : __( 'Not given', 'ayesha-core' );
}

/**
 * Every answer as label/value pairs of plain text, in form order (for the admin screen and emails).
 *
 * @param array $data Values.
 * @return array<int, array{0: string, 1: string}>
 */
function ayesha_core_quote_rows( array $data ) {
	$services = array_map( static fn( $s ) => ayesha_core_quote_label( 'services', $s ), (array) ( $data['services'] ?? array() ) );
	$truck    = ayesha_core_quote_label( 'truck', $data['truck'] ?? 'none' );
	if ( ! empty( $data['truck_time'] ) ) {
		$truck .= ', ' . ayesha_core_quote_label( 'truck_time', $data['truck_time'] );
	}
	$none = __( 'Not given', 'ayesha-core' );
	$rows = array(
		array( __( 'Name', 'ayesha-core' ), (string) ( $data['name'] ?? '' ) ),
		array( __( 'Phone / WhatsApp', 'ayesha-core' ), (string) ( $data['phone'] ?? '' ) ),
		array( __( 'Email', 'ayesha-core' ), '' !== ( $data['email'] ?? '' ) ? (string) $data['email'] : $none ),
	);
	// Only when the form asked it (old enquiries without the key always had it).
	$reply = array_key_exists( 'reply', $data ) ? (string) $data['reply'] : 'whatsapp';
	if ( '' !== $reply ) {
		$rows[] = array( __( 'Best way to reply', 'ayesha-core' ), ayesha_core_quote_label( 'reply', $reply ) );
	}
	// The message is optional: its row only appears when the customer wrote something.
	if ( '' !== (string) ( $data['message'] ?? '' ) ) {
		$rows[] = array( __( 'About the move', 'ayesha-core' ), (string) $data['message'] );
	}
	// Short form: parts 2 and 3 were not asked, so they are not listed at all.
	if ( ayesha_core_quote_is_short( $data ) ) {
		return $rows;
	}
	return array_merge(
		$rows,
		array(
			array( __( 'Type of move', 'ayesha-core' ), ayesha_core_quote_label( 'move_type', $data['move_type'] ?? '' ) ),
			array( __( 'Moving from', 'ayesha-core' ), (string) ( $data['from'] ?? '' ) ),
			array( __( 'Moving to', 'ayesha-core' ), (string) ( $data['to'] ?? '' ) ),
			array( __( 'Preferred date', 'ayesha-core' ), ayesha_core_quote_date_text( $data ) ),
			array( __( 'Property size', 'ayesha-core' ), ayesha_core_quote_label( 'size', $data['size'] ?? '' ) ?: $none ),
			array( __( 'At pickup', 'ayesha-core' ), ayesha_core_quote_floor_text( $data['pickup_floor'] ?? '', $data['pickup_lift'] ?? '' ) ),
			array( __( 'At drop-off', 'ayesha-core' ), ayesha_core_quote_floor_text( $data['dropoff_floor'] ?? '', $data['dropoff_lift'] ?? '' ) ),
			array( __( 'Services', 'ayesha-core' ), $services ? implode( ', ', $services ) : __( 'None ticked', 'ayesha-core' ) ),
			array( __( 'Big or special items', 'ayesha-core' ), '' !== ( $data['items'] ?? '' ) ? (string) $data['items'] : $none ),
			array( __( 'Truck hire', 'ayesha-core' ), $truck ),
		)
	);
}

/**
 * Tap-to-call, tap-to-WhatsApp and email links to the customer (safe HTML).
 *
 * @param array $data Values.
 * @return string
 */
function ayesha_core_quote_contact_links_html( array $data ) {
	$e164  = (string) ( $data['phone_e164'] ?? '' );
	$links = array();
	if ( '' !== $e164 ) {
		/* translators: %s: phone number. */
		$links[] = ayesha_core_link( 'tel:' . $e164, sprintf( __( 'Call %s', 'ayesha-core' ), $e164 ) );
		$links[] = ayesha_core_link( 'https://wa.me/' . ltrim( $e164, '+' ), __( 'WhatsApp the customer', 'ayesha-core' ), true );
	}
	if ( ! empty( $data['email'] ) && is_email( $data['email'] ) ) {
		/* translators: %s: email address. */
		$links[] = ayesha_core_link( 'mailto:' . $data['email'], sprintf( __( 'Email %s', 'ayesha-core' ), $data['email'] ) );
	}
	return implode( ' &nbsp;|&nbsp; ', $links );
}

/**
 * The message the customer can send on WhatsApp after the form: reference, name, move type,
 * from → to and date (plain text; URL-encoded by the caller).
 *
 * @param string $reference Reference.
 * @param array  $values    Values kept in the "sent" state.
 * @return string
 */
function ayesha_core_quote_whatsapp_message( $reference, array $values ) {
	$lines = array(
		/* translators: %s: business name. */
		sprintf( __( 'Hi %s, I just sent a quote request on your website.', 'ayesha-core' ), AYESHA_CORE_BUSINESS_NAME ),
		/* translators: %s: reference number. */
		sprintf( __( 'Reference: %s', 'ayesha-core' ), $reference ),
		/* translators: %s: customer name. */
		sprintf( __( 'Name: %s', 'ayesha-core' ), $values['name'] ?? '' ),
	);
	if ( ! ayesha_core_quote_is_short( $values ) ) {
		/* translators: %s: type of move. */
		$lines[] = sprintf( __( 'Move: %s', 'ayesha-core' ), ayesha_core_quote_label( 'move_type', $values['move_type'] ?? '' ) );
		/* translators: 1: from, 2: to. */
		$lines[] = sprintf( __( 'From %1$s to %2$s', 'ayesha-core' ), $values['from'] ?? '', $values['to'] ?? '' );
		/* translators: %s: preferred date. */
		$lines[] = sprintf( __( 'Date: %s', 'ayesha-core' ), ayesha_core_quote_date_text( $values ) );
	}
	$message = trim( (string) ( $values['message'] ?? '' ) );
	if ( '' !== $message ) {
		// Kept short: a wa.me link has to fit in a URL. The full message is in the email and in WordPress.
		$short = mb_strlen( $message ) > 300 ? rtrim( mb_substr( $message, 0, 300 ) ) . '…' : $message;
		/* translators: %s: the customer's message about the move. */
		$lines[] = sprintf( __( 'About my move: %s', 'ayesha-core' ), $short );
	}
	$lines[] = __( 'I can send photos of my things here.', 'ayesha-core' );
	return implode( "\n", $lines );
}
