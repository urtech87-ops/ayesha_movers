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
		array( 'Labour is in the price', 'The price we give you includes the crew who pack, carry and load. No separate bill for labour.' ),
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
 * "Questions": heading and six Details blocks, answered only with client facts.
 * (No pricing question: how prices are worked out is still an open question for the client.)
 *
 * @return string Block markup.
 */
function ayesha_theme_questions_markup() {
	$faq   = array(
		array( 'Do you take furniture apart and put it back together?', 'Yes. Our carpenters dismantle your furniture before the move and fit them back together in your new home or office. They also take down and put up curtains, and set up new furniture.' ),
		array( 'Can I hire a truck for just a few hours?', 'Yes. You can hire a Dyna or a 6-wheel truck for 8 hours or for a full day. We also do runs to Mina Salman, Khalifa Bin Salman Port, the airport and courier depots such as DHL, Aramex and GLS.' ),
		array( 'Can you move my things to Saudi Arabia?', 'Yes. We move households and offices to Saudi Arabia and the other GCC countries, and to the UK, the USA, Canada and worldwide. We also handle the customs documents.' ),
		array( 'Can you move us at night?', 'Yes. We work 24 hours, day and night. Tell us the time that suits you.' ),
		array( 'Do you remove and refit air conditioners and TVs?', 'Yes. We take down and fit split units and other air conditioners, LCD and LED TVs, and curtains and blinds.' ),
		array( 'Do you load shipping containers?', 'Yes. We load and unload 20ft and 40ft containers and prepare the customs documents for moves abroad.' ),
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
