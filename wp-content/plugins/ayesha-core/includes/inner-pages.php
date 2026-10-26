<?php
/**
 * Inner page banners (About Us, Our Services, Contact Us): the banner photo's loading and the
 * breadcrumb ("Home / page"), on screen and as BreadcrumbList JSON-LD.
 *
 * The banner is a core Cover block with the class "ayesha-banner" and the breadcrumb a core List
 * block with the class "ayesha-breadcrumb" inside it, so the client edits both in the editor like any
 * other block; this file only adds what browsers and search engines need.
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'render_block_core/cover', 'ayesha_core_inner_banner_image', 10, 2 );

/**
 * Banner photo: the WebP banner copies (768 / 1280 / 1920) in srcset, loaded straight away with high
 * priority (it is the largest thing on the first screen). On phones the browser is told the photo
 * is 400px wide, so even a 3x phone takes the 1280 copy at most, never the 1920 one.
 *
 * @param string $html  Rendered block.
 * @param array  $block Parsed block.
 * @return string
 */
function ayesha_core_inner_banner_image( $html, $block ) {
	$class = $block['attrs']['className'] ?? '';
	if ( ! in_array( 'ayesha-banner', preg_split( '/\s+/', $class ), true ) ) {
		return $html;
	}
	$tags = new WP_HTML_Tag_Processor( $html );
	if ( ! $tags->next_tag( array( 'tag_name' => 'img', 'class_name' => 'wp-block-cover__image-background' ) ) ) {
		return $html;
	}
	$id = (int) ( $block['attrs']['id'] ?? 0 );

	$srcset = array();
	$src    = null;
	if ( $id && function_exists( 'ayesha_core_banner_widths' ) ) {
		foreach ( ayesha_core_banner_widths() as $width ) {
			$image = wp_get_attachment_image_src( $id, 'ayesha-banner-' . $width );
			// Only real copies of that width (a smaller original would come back as itself).
			if ( $image && (int) $image[1] === $width ) {
				$srcset[] = $image[0] . ' ' . $width . 'w';
				$src      = $image;
			}
		}
	}
	if ( $srcset ) {
		$tags->set_attribute( 'src', $src[0] );
		$tags->set_attribute( 'srcset', implode( ', ', $srcset ) );
		$tags->set_attribute( 'width', (string) $src[1] );
		$tags->set_attribute( 'height', (string) $src[2] );
		$tags->set_attribute( 'sizes', '(max-width: 599px) 400px, 100vw' );
	}
	$tags->remove_attribute( 'loading' );
	$tags->set_attribute( 'fetchpriority', 'high' );
	$tags->set_attribute( 'decoding', 'async' );
	return $tags->get_updated_html();
}

add_filter( 'render_block_core/list', 'ayesha_core_breadcrumb_current', 10, 2 );

/**
 * The last breadcrumb item is the page you are on: marked aria-current="page" (it is plain text,
 * not a link, in the editor).
 *
 * @param string $html  Rendered block.
 * @param array  $block Parsed block.
 * @return string
 */
function ayesha_core_breadcrumb_current( $html, $block ) {
	$class = $block['attrs']['className'] ?? '';
	if ( ! in_array( 'ayesha-breadcrumb', preg_split( '/\s+/', $class ), true ) ) {
		return $html;
	}
	$last = strrpos( $html, '<li' );
	if ( false === $last ) {
		return $html;
	}
	$open  = strpos( $html, '>', $last );
	$close = strpos( $html, '</li>', $open );
	if ( false === $open || false === $close ) {
		return $html;
	}
	$inner = substr( $html, $open + 1, $close - $open - 1 );
	if ( str_contains( $inner, 'aria-current' ) ) {
		return $html;
	}
	return substr( $html, 0, $open + 1 ) . '<span aria-current="page">' . $inner . '</span>' . substr( $html, $close );
}

/**
 * The breadcrumb items of the current page, read from its "ayesha-breadcrumb" list.
 *
 * @return array<int, array{name: string, url: string}> Empty when the page has none.
 */
function ayesha_core_breadcrumb_items() {
	if ( ! is_singular() || is_front_page() ) {
		return array();
	}
	$post_id = get_queried_object_id();
	$list    = ayesha_core_find_block_with_class( parse_blocks( (string) get_post_field( 'post_content', $post_id ) ), 'core/list', 'ayesha-breadcrumb' );
	if ( ! $list ) {
		return array();
	}
	$items = array();
	foreach ( $list['innerBlocks'] as $item ) {
		$html = (string) $item['innerHTML'];
		$name = trim( html_entity_decode( wp_strip_all_tags( $html ), ENT_QUOTES, 'UTF-8' ) );
		if ( '' === $name ) {
			continue;
		}
		$url = '';
		$tags = new WP_HTML_Tag_Processor( $html );
		if ( $tags->next_tag( 'a' ) ) {
			$url = (string) $tags->get_attribute( 'href' );
		}
		$items[] = array(
			'name' => $name,
			'url'  => $url,
		);
	}
	if ( $items ) {
		// The last item is this page.
		$items[ count( $items ) - 1 ]['url'] = get_permalink( $post_id );
	}
	return $items;
}

/**
 * Find the first block of a type that has a CSS class, anywhere in a parsed block tree.
 *
 * @param array  $blocks Parsed blocks.
 * @param string $name   Block name.
 * @param string $class  CSS class.
 * @return array|null
 */
function ayesha_core_find_block_with_class( $blocks, $name, $class ) {
	foreach ( $blocks as $block ) {
		if ( $name === $block['blockName'] && in_array( $class, preg_split( '/\s+/', $block['attrs']['className'] ?? '' ), true ) ) {
			return $block;
		}
		$found = ayesha_core_find_block_with_class( $block['innerBlocks'], $name, $class );
		if ( $found ) {
			return $found;
		}
	}
	return null;
}

add_action( 'wp_head', 'ayesha_core_print_breadcrumb_schema', 21 );

/**
 * BreadcrumbList JSON-LD, from the same list visitors see (so the two always match).
 */
function ayesha_core_print_breadcrumb_schema() {
	$items = ayesha_core_breadcrumb_items();
	if ( count( $items ) < 2 ) {
		return;
	}
	$elements = array();
	foreach ( $items as $index => $item ) {
		$element = array(
			'@type'    => 'ListItem',
			'position' => $index + 1,
			'name'     => $item['name'],
		);
		if ( '' !== $item['url'] ) {
			$element['item'] = esc_url_raw( $item['url'] );
		}
		$elements[] = $element;
	}
	$json = wp_json_encode(
		array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $elements,
		),
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
	);
	if ( $json ) {
		echo "<script type=\"application/ld+json\" id=\"ayesha-breadcrumb-schema\">{$json}</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON with < and > hex-escaped.
	}
}
