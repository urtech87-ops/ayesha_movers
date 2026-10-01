<?php
/**
 * Title: Questions (FAQ)
 * Slug: ayesha-movers/questions
 * Categories: ayesha-movers
 * Keywords: faq, questions, answers
 * Description: Common questions as open-and-close Details blocks. Add a question by duplicating one Details block.
 *
 * @package AyeshaMovers
 */

echo ayesha_theme_section_markup( 'Questions', 'ayesha-section ayesha-questions', ayesha_theme_questions_markup() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped where built.
