<?php
/**
 * Title: Why people book us + Questions (side by side)
 * Slug: ayesha-movers/why-and-questions
 * Categories: ayesha-movers
 * Keywords: why us, faq, questions
 * Description: The Home page version: "Why people book us" and the Questions side by side on wide screens, one after the other on phones.
 *
 * @package AyeshaMovers
 */

$ayesha_inner = '<!-- wp:columns {"className":"ayesha-why-faq__columns","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns ayesha-why-faq__columns"><!-- wp:column {"width":"50%","className":"ayesha-why"} -->
<div class="wp-block-column ayesha-why" style="flex-basis:50%">' . ayesha_theme_reasons_markup() . '</div>
<!-- /wp:column -->

<!-- wp:column {"width":"50%","className":"ayesha-questions"} -->
<div class="wp-block-column ayesha-questions" style="flex-basis:50%">' . ayesha_theme_questions_markup() . '</div>
<!-- /wp:column --></div>
<!-- /wp:columns -->';

echo ayesha_theme_section_markup( 'Why people book us + Questions', 'ayesha-section ayesha-why-faq', $ayesha_inner ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped where built.
