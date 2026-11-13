<?php
/**
 * Form tabs: the "ayesha/form-tabs" block (the card) and its "ayesha/form-tab" panels. Each panel
 * holds normal blocks (a short line and a form), so the client edits everything in the editor.
 *
 * WAI-ARIA tabs: role="tablist" with a label, buttons with role="tab", aria-selected,
 * aria-controls and a roving tabindex; the panels get role="tabpanel" from the script.
 * Without JavaScript there is no tab bar: both panels show one after the other, each under its
 * label as a heading. A tiny script right after the card switches it to tabs before the page is
 * drawn (no layout shift) and opens the tab a #link points into; blocks/form-tabs/view.js does
 * the rest (clicks, keys, links on the page).
 *
 * Which tab opens: after a send, the tab of the form that was sent (the result's "form" tag:
 * whatsapp = the WhatsApp quote form, anything else = the quote form); otherwise "Opens first".
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'ayesha_core_register_form_tabs' );

/**
 * Register both blocks and their scripts and style.
 */
function ayesha_core_register_form_tabs() {
	$dir = AYESHA_CORE_DIR . 'blocks/form-tabs/';
	$url = AYESHA_CORE_URL . 'blocks/form-tabs/';
	wp_register_script(
		'ayesha-form-tabs-editor',
		$url . 'editor.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-data', 'wp-i18n' ),
		AYESHA_CORE_VERSION . '.' . filemtime( $dir . 'editor.js' ),
		true
	);
	wp_register_script(
		'ayesha-form-tabs-view',
		$url . 'view.js',
		array(),
		AYESHA_CORE_VERSION . '.' . filemtime( $dir . 'view.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	wp_register_style( 'ayesha-form-tabs', $url . 'style.css', array(), AYESHA_CORE_VERSION . '.' . filemtime( $dir . 'style.css' ) );
	register_block_type( $dir, array( 'render_callback' => 'ayesha_core_form_tabs_render' ) );
	register_block_type( AYESHA_CORE_DIR . 'blocks/form-tab/', array( 'render_callback' => 'ayesha_core_form_tab_render' ) );
}

/**
 * A tab's label ("Tab 2" when it was left empty).
 *
 * @param array $attributes Tab attributes.
 * @param int   $index      Position (0-based).
 * @return string
 */
function ayesha_core_form_tab_label( array $attributes, $index ) {
	$label = trim( (string) ( $attributes['label'] ?? '' ) );
	/* translators: %d: tab number. */
	return '' !== $label ? $label : sprintf( __( 'Tab %d', 'ayesha-core' ), $index + 1 );
}

/**
 * Whether a list of parsed blocks contains a block of this name (at any depth).
 *
 * @param array  $blocks Parsed blocks.
 * @param string $name   Block name.
 * @return bool
 */
function ayesha_core_form_tabs_has_block( array $blocks, $name ) {
	foreach ( $blocks as $block ) {
		if ( $name === ( $block['blockName'] ?? '' ) || ayesha_core_form_tabs_has_block( $block['innerBlocks'] ?? array(), $name ) ) {
			return true;
		}
	}
	return false;
}

/**
 * One panel: its label as a heading (shown only without the tab bar) and its blocks. The card
 * adds the id, the open state and the links to the tab.
 *
 * @param array  $attributes Attributes.
 * @param string $content    Rendered inner blocks.
 * @return string
 */
function ayesha_core_form_tab_render( $attributes, $content ) {
	$label = trim( (string) ( $attributes['label'] ?? '' ) );
	return '<div class="ayesha-form-tabs__panel">'
		. ( '' !== $label ? '<h3 class="ayesha-form-tabs__heading">' . esc_html( $label ) . '</h3>' : '' )
		. $content . '</div>';
}

/**
 * The card: the tab bar, the panels and the switch-to-tabs script.
 *
 * @param array    $attributes Attributes.
 * @param string   $content    Rendered panels.
 * @param WP_Block $block      This block.
 * @return string
 */
function ayesha_core_form_tabs_render( $attributes, $content, $block ) {
	$tabs = array();
	$used = array();
	foreach ( $block->inner_blocks as $index => $tab ) {
		$id = sanitize_html_class( (string) ( $tab->attributes['panelId'] ?? '' ) );
		if ( '' === $id || isset( $used[ $id ] ) ) {
			$id = 'ayesha-form-tab-' . ( $index + 1 );
		}
		$used[ $id ] = true;
		$tabs[]      = array(
			'id'     => $id,
			'label'  => ayesha_core_form_tab_label( $tab->attributes, $index ),
			'short'  => trim( (string) ( $tab->attributes['shortLabel'] ?? '' ) ),
			'icon'   => (string) ( $tab->attributes['icon'] ?? '' ),
			'blocks' => $tab->parsed_block['innerBlocks'] ?? array(),
		);
	}
	if ( ! $tabs ) {
		return '';
	}

	$open  = max( 0, min( count( $tabs ) - 1, (int) ( $attributes['openFirst'] ?? 0 ) ) );
	$state = ayesha_core_quote_current_state();
	if ( $state ) {
		$form = 'whatsapp' === ( $state['form'] ?? '' ) ? 'ayesha/whatsapp-form' : 'ayesha/quote-form';
		foreach ( $tabs as $index => $tab ) {
			if ( ayesha_core_form_tabs_has_block( $tab['blocks'], $form ) ) {
				$open = $index;
				break;
			}
		}
	}

	// The tab bar (hidden by the style sheet until the script below has run).
	$bar = '<div class="ayesha-form-tabs__bar" role="tablist" aria-label="' . esc_attr( trim( (string) ( $attributes['tablistLabel'] ?? '' ) ) ?: __( 'Ways to ask for a quote', 'ayesha-core' ) ) . '">';
	foreach ( $tabs as $index => $tab ) {
		$on    = $index === $open;
		$class = 'ayesha-form-tabs__tab' . ( 'whatsapp' === $tab['icon'] ? ' ayesha-form-tabs__tab--whatsapp' : '' ) . ( '' !== $tab['short'] ? ' has-short' : '' );
		$bar  .= '<button type="button" role="tab" class="' . esc_attr( $class ) . '" id="' . esc_attr( $tab['id'] . '-tab' ) . '" aria-controls="' . esc_attr( $tab['id'] ) . '" aria-selected="' . ( $on ? 'true' : 'false' ) . '" tabindex="' . ( $on ? '0' : '-1' ) . '">'
			. '<span class="ayesha-form-tabs__long">' . esc_html( $tab['label'] ) . '</span>'
			// The short label is only what phones show; the button's name stays the full label.
			. ( '' !== $tab['short'] ? '<span class="ayesha-form-tabs__short" aria-hidden="true">' . esc_html( $tab['short'] ) . '</span>' : '' )
			. '</button>';
	}
	$bar .= '</div>';

	// Give each panel its id and mark the open one.
	$panels = new WP_HTML_Tag_Processor( $content );
	$index  = 0;
	while ( isset( $tabs[ $index ] ) && $panels->next_tag( array( 'class_name' => 'ayesha-form-tabs__panel' ) ) ) {
		$panels->set_attribute( 'id', $tabs[ $index ]['id'] );
		if ( $index === $open ) {
			$panels->add_class( 'is-active' );
		}
		++$index;
	}

	// Runs as soon as the card is read, before it is drawn: tabs on, and a #link into a tab opens it.
	$script = '<script>(function(r){r.classList.add("is-tabbed");var t,h=location.hash.slice(1);try{t=h&&document.getElementById(decodeURIComponent(h))}catch(e){}'
		. 'var p=t&&t.closest(".ayesha-form-tabs__panel");if(!p||p.parentNode!==r)return;'
		. '[].forEach.call(r.children,function(x){if(x.classList.contains("ayesha-form-tabs__panel"))x.classList.toggle("is-active",x===p)});'
		. '[].forEach.call(r.querySelectorAll("[role=tab]"),function(b){var on=b.getAttribute("aria-controls")===p.id;b.setAttribute("aria-selected",on);b.tabIndex=on?0:-1})'
		. '})(document.currentScript.parentNode);</script>';

	return '<div ' . get_block_wrapper_attributes( array( 'class' => 'ayesha-form-tabs' ) ) . '>' . $bar . $panels->get_updated_html() . $script . '</div>';
}
