<?php
/**
 * Title: Footer logo (white and gold, for navy)
 * Slug: ayesha-movers/footer-logo
 * Categories: ayesha-movers
 * Keywords: logo, footer, brand
 * Inserter: no
 * Description: The logo version for navy backgrounds, used in the footer. The header uses the Site Logo block (the site's one logo, for white backgrounds); the footer needs the white and gold version, so it is an Image block. Replace it with Image → Replace in the Site Editor.
 *
 * @package AyeshaMovers
 */

?>
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"ayesha-footer__logo","metadata":{"name":"Footer logo (white and gold)"}} -->
<figure class="wp-block-image size-full ayesha-footer__logo"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/logo-footer.png' ) ); ?>" alt="<?php echo esc_attr__( 'AYESHA Movers & Packers', 'ayesha-movers' ); ?>"/></figure>
<!-- /wp:image -->
