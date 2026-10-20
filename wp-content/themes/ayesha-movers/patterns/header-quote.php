<?php
/**
 * Title: Header "Free quote" button
 * Slug: ayesha-movers/header-quote
 * Categories: ayesha-movers
 * Keywords: quote, header, button
 * Inserter: no
 * Description: The gold "Free quote" button on the right of the header. It links to the quote form on Contact Us (/contact-us/#quote). A pattern, so the link is built from the site's address.
 *
 * @package AyeshaMovers
 */

?>
<!-- wp:buttons {"className":"ayesha-header__quote","metadata":{"name":"Free quote button"}} -->
<div class="wp-block-buttons ayesha-header__quote"><!-- wp:button {"className":"is-style-yellow"} -->
<div class="wp-block-button is-style-yellow"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact-us/#quote' ) ); ?>"><?php echo esc_html__( 'Free quote', 'ayesha-movers' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
