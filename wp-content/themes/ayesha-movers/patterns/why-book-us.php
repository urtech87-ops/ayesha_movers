<?php
/**
 * Title: Why people book us
 * Slug: ayesha-movers/why-book-us
 * Categories: ayesha-movers
 * Keywords: why us, reasons, benefits
 * Description: Four short statements about how the team works (low rates with labour included, door to door, one team, careful packing). No icons, stats or numbers.
 *
 * @package AyeshaMovers
 */

echo ayesha_theme_section_markup( 'Why people book us', 'ayesha-section ayesha-why', ayesha_theme_reasons_markup() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped where built.
