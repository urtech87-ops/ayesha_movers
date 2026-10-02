<?php
/**
 * Title: Our Services: questions (FAQ)
 * Slug: ayesha-movers/services-questions
 * Categories: ayesha-movers
 * Keywords: faq, questions, services
 * Description: Four questions about the services as open-and-close Details blocks, on grey. Add a question by duplicating one Details block.
 *
 * @package AyeshaMovers
 */

$ayesha_faq = array(
	array( 'Can you take my boxes to DHL, Aramex or GLS?', 'Yes. Our trucks do runs to courier depots such as DHL, Aramex and GLS, and to Mina Salman, Khalifa Bin Salman Port and the airport.' ),
	array( 'Do you pack fragile items and crockery?', 'Yes. Fragile items and crockery get special packing, on top of our international-standard packing for everything else.' ),
	array( 'Can your carpenters set up new furniture?', 'Yes. Besides taking furniture apart and fitting it back together for a move, our carpenters set up new furniture for homes and offices, and put up curtains.' ),
	array( 'Do you move things outside Bahrain?', 'Yes. We move to Saudi Arabia and all GCC countries, and to the UK, the USA, Canada and worldwide. We load and unload 20ft and 40ft containers and handle the customs documentation.' ),
);

echo ayesha_theme_section_markup( 'Questions', 'is-style-concrete-panel ayesha-section ayesha-questions', ayesha_theme_questions_markup( $ayesha_faq ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped where built.
