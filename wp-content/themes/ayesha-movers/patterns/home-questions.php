<?php
/**
 * Title: Home: questions (FAQ)
 * Slug: ayesha-movers/home-questions
 * Categories: ayesha-movers
 * Keywords: faq, questions
 * Description: The Home page's six questions as open-and-close Details blocks in one centred column. Add a question by duplicating one Details block.
 *
 * @package AyeshaMovers
 */

echo ayesha_theme_section_markup( 'Questions', 'ayesha-section ayesha-questions ayesha-home-faq', ayesha_theme_questions_markup() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped where built.
