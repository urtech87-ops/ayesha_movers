<?php
/**
 * Quote form markup (server-rendered block "ayesha/quote-form").
 *
 * Labels above fields, hints and errors linked with aria-describedby, three fieldsets with
 * legends, an error summary that links to each field, and the "Quote request sent" panel.
 * Every label comes from the block's attributes (editable in the sidebar) and is escaped.
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render callback.
 *
 * @param array $attributes Block attributes (defaults from block.json filled in).
 * @return string
 */
function ayesha_core_quote_render( $attributes ) {
	// An emptied label falls back to its original words (the line under the button may be empty).
	$a = $attributes;
	foreach ( WP_Block_Type_Registry::get_instance()->get_registered( 'ayesha/quote-form' )->attributes as $key => $schema ) {
		if ( 'privacyNote' !== $key && '' === trim( (string) ( $a[ $key ] ?? '' ) ) ) {
			$a[ $key ] = $schema['default'] ?? '';
		}
	}
	$state = ayesha_core_quote_current_state();
	$page  = get_the_ID() ? get_permalink( get_the_ID() ) : '';
	$inner = ( $state && 'success' === ( $state['type'] ?? '' ) )
		? ayesha_core_quote_render_success( $a, $state, $page )
		: ayesha_core_quote_render_form( $a, $state && 'error' === ( $state['type'] ?? '' ) ? $state : null, $page );

	return '<div ' . get_block_wrapper_attributes( array( 'class' => 'ayesha-qf' ) ) . '>' . $inner . '</div>';
}

/**
 * "Quote request sent", the reference number and the WhatsApp hand-off.
 *
 * @param array  $a     Attributes.
 * @param array  $state Sent state.
 * @param string $page  This page's URL.
 * @return string
 */
function ayesha_core_quote_render_success( array $a, array $state, $page ) {
	$reference = (string) ( $state['reference'] ?? '' );
	$wa        = ayesha_core_whatsapp_url( ayesha_core_quote_whatsapp_message( $reference, (array) ( $state['values'] ?? array() ) ) );
	$html      = '<div class="ayesha-qf__result" id="ayesha-qf-result" tabindex="-1">'
		. '<h3 class="ayesha-qf__result-title">' . esc_html( $a['successTitle'] ) . '</h3>'
		. '<p class="ayesha-qf__ref"><span class="ayesha-qf__ref-label">' . esc_html__( 'Your reference', 'ayesha-core' ) . '</span> '
		. '<strong class="ayesha-qf__ref-number">' . esc_html( $reference ) . '</strong></p>'
		. '<p>' . esc_html( $a['successText'] ) . '</p>';
	if ( '' !== $wa ) {
		$html .= '<div class="wp-block-buttons"><div class="wp-block-button is-style-whatsapp">'
			. '<a class="wp-block-button__link wp-element-button" href="' . esc_url( $wa ) . '">' . esc_html( $a['whatsappText'] ) . '</a>'
			. '</div></div>';
	}
	if ( $page ) {
		$html .= '<p class="ayesha-qf__again"><a href="' . esc_url( $page . '#quote' ) . '">' . esc_html__( 'Send another quote request', 'ayesha-core' ) . '</a></p>';
	}
	return $html . '</div>';
}

/**
 * The form, with the values and errors of the previous attempt when there was one.
 *
 * @param array      $a     Attributes.
 * @param array|null $state Error state or null.
 * @param string     $page  This page's URL ('' in the editor preview).
 * @return string
 */
function ayesha_core_quote_render_form( array $a, $state, $page ) {
	$v = wp_parse_args(
		(array) ( $state['values'] ?? array() ),
		array(
			'name'          => '',
			'phone'         => '',
			'email'         => '',
			'reply'         => 'whatsapp',
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
		)
	);
	$e = (array) ( $state['errors'] ?? array() );
	$o = ayesha_core_quote_options();

	$html = $state ? ayesha_core_quote_render_summary( $state, $e ) : '';
	$html .= '<form class="ayesha-qf__form" method="post" action="' . esc_url( $page ? $page . '#quote' : '' ) . '" novalidate>';

	// 1. About you.
	$html .= '<fieldset class="ayesha-qf__step"><legend class="ayesha-qf__legend">' . esc_html( $a['legendYou'] ) . '</legend>';
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
	$html .= ayesha_core_quote_input(
		'email',
		$a['labelEmail'],
		$a['hintEmail'],
		$v['email'],
		$e,
		false,
		array(
			'type'         => 'email',
			'autocomplete' => 'email',
			'spellcheck'   => 'false',
		)
	);
	$html .= ayesha_core_quote_choices( 'reply', 'radio', $a['labelReply'], '', $o['reply'], array( $v['reply'] ), $e, false, 'row' );
	$html .= '</fieldset>';

	// 2. Your move.
	$html .= '<fieldset class="ayesha-qf__step"><legend class="ayesha-qf__legend">' . esc_html( $a['legendMove'] ) . '</legend>';
	$html .= ayesha_core_quote_choices( 'move_type', 'radio', $a['labelMoveType'], '', $o['move_type'], array( $v['move_type'] ), $e, true, 'pairs' );
	$html .= ayesha_core_quote_input( 'from', $a['labelFrom'], $a['hintFrom'], $v['from'], $e, true, array( 'autocomplete' => 'off' ) );
	$html .= ayesha_core_quote_input( 'to', $a['labelTo'], $a['hintTo'], $v['to'], $e, true, array( 'autocomplete' => 'off' ) );
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
		),
		'<div class="ayesha-qf__choice ayesha-qf__choice--after"><input class="ayesha-qf__check" type="checkbox" id="aqf-flexible" name="aq[flexible]" value="1"' . checked( ! empty( $v['flexible'] ), true, false ) . '><label for="aqf-flexible">' . esc_html( $a['labelFlexible'] ) . '</label></div>'
	);
	$html .= ayesha_core_quote_select( 'size', $a['labelSize'], $o['size'], $v['size'] );
	foreach ( array( 'pickup' => $a['labelPickup'], 'dropoff' => $a['labelDropoff'] ) as $where => $legend ) {
		$html .= '<fieldset class="ayesha-qf__field ayesha-qf__pair"><legend class="ayesha-qf__label">' . esc_html( $legend ) . '</legend><div class="ayesha-qf__pair-fields">'
			. ayesha_core_quote_select( $where . '_floor', $a['labelFloor'], $o['floor'], $v[ $where . '_floor' ] )
			. ayesha_core_quote_select( $where . '_lift', $a['labelLift'], $o['lift'], $v[ $where . '_lift' ] )
			. '</div></fieldset>';
	}
	$html .= '</fieldset>';

	// 3. What you need.
	$html .= '<fieldset class="ayesha-qf__step"><legend class="ayesha-qf__legend">' . esc_html( $a['legendNeeds'] ) . '</legend>';
	$html .= ayesha_core_quote_choices( 'services', 'checkbox', $a['labelServices'], $a['hintServices'], $o['services'], (array) $v['services'], $e, false, 'grid' );
	$html .= ayesha_core_quote_textarea( 'items', $a['labelItems'], $a['hintItems'], $v['items'], $e );
	$html .= ayesha_core_quote_choices( 'truck', 'radio', $a['labelTruck'], '', $o['truck'], array( $v['truck'] ), $e, false, 'row' );
	$html .= ayesha_core_quote_choices( 'truck_time', 'radio', $a['labelTruckTime'], '', $o['truck_time'], array( $v['truck_time'] ), $e, false, 'row' );
	$html .= '</fieldset>';

	// Spam checks: nonce, the time the form was opened, and a field people never see.
	$html .= '<input type="hidden" name="ayesha_qf" value="1">'
		. '<input type="hidden" name="_aqnonce" value="' . esc_attr( wp_create_nonce( 'ayesha_quote_form' ) ) . '">'
		. '<input type="hidden" name="aq_t" value="' . esc_attr( ayesha_core_quote_timer_token() ) . '">'
		. '<div class="ayesha-qf__hp" aria-hidden="true"><label for="aqf-website">' . esc_html__( 'Leave this field empty', 'ayesha-core' ) . '</label>'
		. '<input type="text" id="aqf-website" name="aq_website" value="" tabindex="-1" autocomplete="off"></div>';

	$html .= '<div class="ayesha-qf__submit"><button type="submit" class="wp-element-button ayesha-qf__button">' . esc_html( $a['submitText'] ) . '</button>';
	if ( '' !== trim( (string) $a['privacyNote'] ) ) {
		$html .= '<p class="ayesha-qf__privacy">' . esc_html( $a['privacyNote'] ) . '</p>';
	}
	$html .= '</div></form>';

	return $html;
}

/**
 * Error summary at the top: what went wrong and a link to each field.
 *
 * @param array $state  Error state.
 * @param array $errors Field errors.
 * @return string
 */
function ayesha_core_quote_render_summary( array $state, array $errors ) {
	$problems = array(
		'expired' => __( 'The form was open for a long time and expired. Your answers are still here: check them and press the button again.', 'ayesha-core' ),
		'fast'    => __( 'The form was sent faster than a person can fill it in. Check your answers and press the button again.', 'ayesha-core' ),
		'blocked' => __( 'Your request could not be sent. Please message us on WhatsApp or call us instead.', 'ayesha-core' ),
		'limit'   => __( 'You have sent 5 quote requests in the last hour, so this one was not sent. Please message us on WhatsApp or call us instead.', 'ayesha-core' ),
		'save'    => __( 'Your request could not be saved. Please try again, or message us on WhatsApp.', 'ayesha-core' ),
	);
	$problem = (string) ( $state['problem'] ?? '' );

	$html = '<div class="ayesha-qf__summary" id="ayesha-qf-summary" role="alert" tabindex="-1" aria-labelledby="ayesha-qf-summary-title">'
		. '<h3 class="ayesha-qf__summary-title" id="ayesha-qf-summary-title">' . esc_html__( 'Your request was not sent', 'ayesha-core' ) . '</h3>';
	if ( isset( $problems[ $problem ] ) ) {
		$html .= '<p>' . esc_html( $problems[ $problem ] ) . '</p>';
		$wa    = ayesha_core_whatsapp_url();
		if ( in_array( $problem, array( 'blocked', 'limit', 'save' ), true ) && '' !== $wa ) {
			$html .= '<p><a href="' . esc_url( $wa ) . '">' . esc_html__( 'Message us on WhatsApp', 'ayesha-core' ) . '</a></p>';
		}
	}
	if ( $errors ) {
		$html .= '<p>' . esc_html__( 'Check these answers:', 'ayesha-core' ) . '</p><ul>';
		foreach ( ayesha_core_quote_error_targets() as $field => $target ) {
			if ( isset( $errors[ $field ] ) ) {
				$html .= '<li><a href="#' . esc_attr( $target ) . '">' . esc_html( $errors[ $field ] ) . '</a></li>';
			}
		}
		$html .= '</ul>';
	}
	return $html . '</div>';
}

/**
 * Label + hint + error lines shared by the fields.
 *
 * @param string $id       Field element id.
 * @param string $hint     Hint ('' for none).
 * @param string $error    Error ('' for none).
 * @return array{0: string, 1: string} HTML of the hint and error, and the aria-describedby value.
 */
function ayesha_core_quote_messages( $id, $hint, $error ) {
	$html     = '';
	$describe = array();
	if ( '' !== trim( (string) $hint ) ) {
		$html      .= '<p class="ayesha-qf__hint" id="' . esc_attr( $id . '-hint' ) . '">' . esc_html( $hint ) . '</p>';
		$describe[] = $id . '-hint';
	}
	if ( '' !== $error ) {
		$html      .= '<p class="ayesha-qf__error" id="' . esc_attr( $id . '-error' ) . '"><span class="screen-reader-text">' . esc_html__( 'Error:', 'ayesha-core' ) . ' </span>' . esc_html( $error ) . '</p>';
		$describe[] = $id . '-error';
	}
	return array( $html, implode( ' ', $describe ) );
}

/**
 * The "Required" tag after a label (hidden from screen readers, which announce the required attribute).
 *
 * @param bool $required Required.
 * @return string
 */
function ayesha_core_quote_required_tag( $required ) {
	return $required ? ' <span class="ayesha-qf__req" aria-hidden="true">' . esc_html__( 'Required', 'ayesha-core' ) . '</span>' : '';
}

/**
 * A text-like input.
 *
 * @param string $key      Field key.
 * @param string $label    Label.
 * @param string $hint     Hint.
 * @param string $value    Value.
 * @param array  $errors   Errors by field.
 * @param bool   $required Required.
 * @param array  $attrs    Extra attributes (type defaults to text).
 * @param string $after    HTML after the input.
 * @return string
 */
function ayesha_core_quote_input( $key, $label, $hint, $value, array $errors, $required, array $attrs = array(), $after = '' ) {
	$id    = 'aqf-' . $key;
	$error = (string) ( $errors[ $key ] ?? '' );
	list( $messages, $describe ) = ayesha_core_quote_messages( $id, $hint, $error );
	$attrs = array_merge( array( 'type' => 'text' ), $attrs );

	$extra = '';
	foreach ( $attrs as $name => $attr_value ) {
		$extra .= ' ' . $name . '="' . esc_attr( $attr_value ) . '"';
	}
	return '<div class="ayesha-qf__field' . ( $error ? ' is-invalid' : '' ) . '">'
		. '<label class="ayesha-qf__label" for="' . esc_attr( $id ) . '">' . esc_html( $label ) . ayesha_core_quote_required_tag( $required ) . '</label>'
		. $messages
		. '<input class="ayesha-qf__input" id="' . esc_attr( $id ) . '" name="aq[' . esc_attr( $key ) . ']" value="' . esc_attr( $value ) . '"' . $extra
		. ( $describe ? ' aria-describedby="' . esc_attr( $describe ) . '"' : '' )
		. ( $error ? ' aria-invalid="true"' : '' )
		. ( $required ? ' required' : '' ) . '>'
		. $after
		. '</div>';
}

/**
 * A textarea.
 *
 * @param string $key    Field key.
 * @param string $label  Label.
 * @param string $hint   Hint.
 * @param string $value  Value.
 * @param array  $errors Errors.
 * @return string
 */
function ayesha_core_quote_textarea( $key, $label, $hint, $value, array $errors ) {
	$id    = 'aqf-' . $key;
	$error = (string) ( $errors[ $key ] ?? '' );
	list( $messages, $describe ) = ayesha_core_quote_messages( $id, $hint, $error );
	return '<div class="ayesha-qf__field' . ( $error ? ' is-invalid' : '' ) . '">'
		. '<label class="ayesha-qf__label" for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>'
		. $messages
		. '<textarea class="ayesha-qf__input ayesha-qf__textarea" id="' . esc_attr( $id ) . '" name="aq[' . esc_attr( $key ) . ']" rows="4"'
		. ( $describe ? ' aria-describedby="' . esc_attr( $describe ) . '"' : '' )
		. ( $error ? ' aria-invalid="true"' : '' ) . '>' . esc_textarea( $value ) . '</textarea>'
		. '</div>';
}

/**
 * A select with an empty first choice.
 *
 * @param string                $key     Field key.
 * @param string                $label   Label.
 * @param array<string, string> $options Choices.
 * @param string                $value   Selected key.
 * @return string
 */
function ayesha_core_quote_select( $key, $label, array $options, $value ) {
	$id   = 'aqf-' . $key;
	$html = '<div class="ayesha-qf__field"><label class="ayesha-qf__label" for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>'
		. '<select class="ayesha-qf__input ayesha-qf__select" id="' . esc_attr( $id ) . '" name="aq[' . esc_attr( $key ) . ']">'
		. '<option value="">' . esc_html__( 'Choose', 'ayesha-core' ) . '</option>';
	foreach ( $options as $option => $text ) {
		$html .= '<option value="' . esc_attr( $option ) . '"' . selected( (string) $value, (string) $option, false ) . '>' . esc_html( $text ) . '</option>';
	}
	return $html . '</select></div>';
}

/**
 * A group of radio buttons or checkboxes in a fieldset.
 *
 * @param string                $key      Field key.
 * @param string                $type     radio or checkbox.
 * @param string                $legend   Group label.
 * @param string                $hint     Hint.
 * @param array<string, string> $options  Choices.
 * @param string[]              $checked  Checked keys.
 * @param array                 $errors   Errors.
 * @param bool                  $required Required.
 * @param string                $layout   row (side by side when they fit), grid (two columns from 600px) or pairs (always two columns).
 * @return string
 */
function ayesha_core_quote_choices( $key, $type, $legend, $hint, array $options, array $checked, array $errors, $required, $layout ) {
	$id    = 'aqf-' . $key;
	$error = (string) ( $errors[ $key ] ?? '' );
	list( $messages, $describe ) = ayesha_core_quote_messages( $id, $hint, $error );
	$name  = 'checkbox' === $type ? 'aq[' . $key . '][]' : 'aq[' . $key . ']';

	$html = '<fieldset class="ayesha-qf__field ayesha-qf__group' . ( $error ? ' is-invalid' : '' ) . '"' . ( $describe ? ' aria-describedby="' . esc_attr( $describe ) . '"' : '' ) . '>'
		. '<legend class="ayesha-qf__label">' . esc_html( $legend ) . ayesha_core_quote_required_tag( $required ) . '</legend>'
		. $messages
		. '<div class="ayesha-qf__choices ayesha-qf__choices--' . esc_attr( $layout ) . '">';
	foreach ( $options as $option => $text ) {
		$option_id = $id . '-' . $option;
		$html     .= '<div class="ayesha-qf__choice"><input class="ayesha-qf__check" type="' . esc_attr( $type ) . '" id="' . esc_attr( $option_id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $option ) . '"'
			. ( in_array( (string) $option, array_map( 'strval', $checked ), true ) ? ' checked' : '' )
			. ( $error ? ' aria-invalid="true"' : '' )
			. ( $required ? ' required' : '' ) . '>'
			. '<label for="' . esc_attr( $option_id ) . '">' . esc_html( $text ) . '</label></div>';
	}
	return $html . '</div></fieldset>';
}
