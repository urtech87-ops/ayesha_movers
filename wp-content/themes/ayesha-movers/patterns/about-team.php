<?php
/**
 * Title: About Us: who runs it + how we work
 * Slug: ayesha-movers/about-team
 * Categories: ayesha-movers
 * Keywords: about, manager, team, commitments
 * Description: Who runs the business (the General Manager and the other trading name) beside four short commitments about how the team works. Side by side on computers, one after the other on phones. No founding year and no "years of experience" until the client confirms them.
 *
 * @package AyeshaMovers
 */

$ayesha_commitments = array(
	array( 'Labour included in the price', 'Packing, carrying, loading and unloading are done by our crew and covered by the price we quote you.' ),
	array( 'Door to door', 'We start at your door and finish at the door of your new home or office.' ),
	array( 'One team, start to finish', 'Labour, trucks and carpenters all come from us, so you don\'t have to find a separate truck or carpenter.' ),
	array( 'Day or night', 'We work 24 hours, so your move can happen at the time that suits you, including at night.' ),
);

/*
 * Phones and tablets show the About photo here, after "How we work", instead of above "Who runs it",
 * so the man in it isn't read as the General Manager (open client question). Computers show the copy
 * beside the page heading. Both copies are lazy-loaded, so the hidden one is never downloaded.
 */
$ayesha_photo = ayesha_theme_image_markup( 15, 'ayesha-photo', 'ayesha-mobile-only ayesha-about-how__photo', 'Photo (phones and tablets only; computers show the copy beside the heading)' );

$ayesha_items = array();
foreach ( $ayesha_commitments as $ayesha_commitment ) {
	$ayesha_items[] = '<!-- wp:group {"className":"ayesha-reason","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-reason"><!-- wp:heading {"level":3,"fontSize":"lead"} -->
<h3 class="wp-block-heading has-lead-font-size">' . esc_html( $ayesha_commitment[0] ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html( $ayesha_commitment[1] ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->';
}

$ayesha_inner = '<!-- wp:columns {"className":"ayesha-about-team__columns","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns ayesha-about-team__columns"><!-- wp:column {"width":"41.67%","className":"ayesha-about-who"} -->
<div class="wp-block-column ayesha-about-who" style="flex-basis:41.67%"><!-- wp:heading -->
<h2 class="wp-block-heading">Who runs it</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>AYESHA Movers &amp; Packers is run by its General Manager, Mohammad Ayub Khokhear.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>The business also trades as AYESHA Cargo Handling.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"58.33%","className":"ayesha-about-how"} -->
<div class="wp-block-column ayesha-about-how" style="flex-basis:58.33%"><!-- wp:heading -->
<h2 class="wp-block-heading">How we work</h2>
<!-- /wp:heading -->

<!-- wp:group {"metadata":{"name":"Commitments"},"className":"ayesha-reasons","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-reasons">' . implode( "\n\n", $ayesha_items ) . '</div>
<!-- /wp:group -->' . ( '' === $ayesha_photo ? '' : "\n\n" . $ayesha_photo ) . '</div>
<!-- /wp:column --></div>
<!-- /wp:columns -->';

echo ayesha_theme_section_markup( 'Who runs it + How we work', 'is-style-concrete-panel ayesha-section ayesha-about-team', $ayesha_inner ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped where built.
