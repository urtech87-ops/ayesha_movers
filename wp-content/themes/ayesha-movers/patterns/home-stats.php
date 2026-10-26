<?php
/**
 * Title: Stats (shared, hidden until "Show stats" is on)
 * Slug: ayesha-movers/home-stats
 * Categories: ayesha-movers
 * Keywords: stats, numbers, counter, years, moves
 * Description: Four numbers with a label (years in business, moves completed, trucks, team members) that count up when people scroll to them. Visitors can't see this section while "Show stats" is off in Settings > Business Info, and a number left empty or 0 never shows. Type only real numbers (for example 1,200+), then switch it on.
 *
 * @package AyeshaMovers
 */

// Shared: inserts the synced pattern (Home and About Us), so the numbers are typed once.
$ayesha_ref = apply_filters( 'ayesha_theme_use_synced_patterns', true ) ? ayesha_theme_synced_pattern_id( 'ayesha-stats' ) : 0;
if ( $ayesha_ref ) {
	echo '<!-- wp:block {"ref":' . (int) $ayesha_ref . '} /-->';
	return;
}

$ayesha_stats = array(
	'years'  => 'Years in business',
	'moves'  => 'Moves completed',
	'trucks' => 'Trucks',
	'team'   => 'Team members',
);
$ayesha_items = array();
foreach ( $ayesha_stats as $ayesha_key => $ayesha_label ) {
	$ayesha_items[] = '<!-- wp:group {"metadata":{"name":"Stat: ' . esc_attr( $ayesha_label ) . '"},"className":"ayesha-stat ayesha-stat--' . $ayesha_key . '","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-stat ayesha-stat--' . $ayesha_key . '"><!-- wp:paragraph {"placeholder":"Type the real number","className":"ayesha-stat__number"} -->
<p class="ayesha-stat__number"></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"ayesha-stat__label"} -->
<p class="ayesha-stat__label">' . esc_html( $ayesha_label ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->';
}
?>
<!-- wp:group {"metadata":{"name":"Stats (hidden until Show stats is on)"},"tagName":"section","align":"full","className":"ayesha-stats ayesha-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ayesha-stats ayesha-section" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:paragraph {"className":"ayesha-editor-note"} -->
<p class="ayesha-editor-note">Only you see this note, in the editor. Visitors can't see the numbers while "Show stats" is off in Settings → Business Info. Type real numbers only; a number left empty or 0 is never shown.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"metadata":{"name":"Stats row"},"className":"ayesha-stats__row","layout":{"type":"default"}} -->
<div class="wp-block-group ayesha-stats__row"><?php echo implode( "\n\n", $ayesha_items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
