<?php
/**
 * Home banner: block "ayesha/hero-slider" with up to 3 "ayesha/hero-slide" blocks.
 *
 * Each slide holds normal blocks (Image, paragraphs, heading, buttons), so the client edits
 * them in the editor like any other text. The server adds what visitors and screen readers
 * need: slide 1 shown and the others hidden (so the page works without JavaScript), the
 * slide labels, the controls, and the banner image sizes (WebP 768 / 1280 / 1920, slide 1
 * loaded first with high priority, the others only after the page has loaded).
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'ayesha_core_register_hero_slider' );

/**
 * Register both blocks, the editor script, the view script (deferred) and the styles.
 */
function ayesha_core_register_hero_slider() {
	$dir = AYESHA_CORE_DIR . 'blocks/hero-slider/';
	$url = AYESHA_CORE_URL . 'blocks/hero-slider/';
	wp_register_script(
		'ayesha-hero-slider-editor',
		$url . 'editor.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-data', 'wp-i18n' ),
		AYESHA_CORE_VERSION . '.' . filemtime( $dir . 'editor.js' ),
		true
	);
	wp_add_inline_script(
		'ayesha-hero-slider-editor',
		'window.ayeshaSlider = ' . wp_json_encode( array( 'quoteUrl' => home_url( '/contact-us/#quote' ) ) ) . ';',
		'before'
	);
	wp_register_script(
		'ayesha-hero-slider-view',
		$url . 'view.js',
		array(),
		AYESHA_CORE_VERSION . '.' . filemtime( $dir . 'view.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	wp_register_style( 'ayesha-hero-slider', $url . 'style.css', array(), AYESHA_CORE_VERSION . '.' . filemtime( $dir . 'style.css' ) );

	register_block_type( $dir, array( 'render_callback' => 'ayesha_core_hero_slider_render' ) );
	register_block_type( AYESHA_CORE_DIR . 'blocks/hero-slide/' );
}

/**
 * Banner image widths, in the order they go into srcset. Sizes are registered by the theme.
 *
 * @return int[]
 */
function ayesha_core_banner_widths() {
	return array( 768, 1280, 1920 );
}

/**
 * Render the slider: label the slides, hide all but the first, set the image loading, add controls.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    The rendered slides.
 * @param WP_Block $block      Block instance.
 * @return string
 */
function ayesha_core_hero_slider_render( $attributes, $content, $block ) {
	$count = 0;
	foreach ( $block->inner_blocks as $inner ) {
		if ( 'ayesha/hero-slide' === $inner->name ) {
			++$count;
		}
	}
	if ( 0 === $count ) {
		return '';
	}

	$id       = wp_unique_id( 'ayesha-slider-' );
	$label    = trim( (string) ( $attributes['label'] ?? '' ) );
	$label    = '' === $label ? __( 'Highlights', 'ayesha-core' ) : $label;
	$interval = max( 4000, min( 10000, (int) ( $attributes['interval'] ?? 6500 ) ) );
	$multi    = $count > 1;

	$tags  = new WP_HTML_Tag_Processor( $content );
	$index = -1;
	$image = false; // Whether the current slide's banner image has been handled.
	while ( $tags->next_tag() ) {
		if ( 'DIV' === $tags->get_tag() && $tags->has_class( 'wp-block-ayesha-hero-slide' ) ) {
			++$index;
			$image = false;
			$tags->set_attribute( 'id', $id . '-slide-' . ( $index + 1 ) );
			if ( $multi ) {
				$tags->set_attribute( 'role', 'group' );
				$tags->set_attribute( 'aria-roledescription', __( 'slide', 'ayesha-core' ) );
				/* translators: 1: slide number, 2: number of slides. */
				$tags->set_attribute( 'aria-label', sprintf( __( '%1$d of %2$d', 'ayesha-core' ), $index + 1, $count ) );
			}
			if ( 0 === $index ) {
				$tags->add_class( 'is-active' );
			} else {
				// Hidden without JavaScript too: only slide 1 shows, as a normal banner.
				$tags->set_attribute( 'aria-hidden', 'true' );
				$tags->set_attribute( 'inert', true );
			}
			continue;
		}
		if ( ! $image && $index >= 0 && 'IMG' === $tags->get_tag() ) {
			$image = true;
			ayesha_core_hero_slider_image( $tags, $index );
		}
	}

	$wrapper = array(
		'class' => 'ayesha-slider' . ( $multi ? ' ayesha-slider--multi' : '' ),
		'id'    => $id,
	);
	if ( $multi ) {
		$wrapper['aria-roledescription'] = __( 'carousel', 'ayesha-core' );
		$wrapper['aria-label']           = $label;
		$wrapper['data-interval']        = (string) $interval;
		$wrapper['data-autoplay']        = empty( $attributes['autoplay'] ) ? '0' : '1';
	}

	$html = '<section ' . get_block_wrapper_attributes( $wrapper ) . '><div class="ayesha-slider__track" id="' . esc_attr( $id . '-track' ) . '"' . ( $multi ? ' aria-live="off"' : '' ) . '>' . $tags->get_updated_html() . '</div>';
	if ( $multi ) {
		$html .= ayesha_core_hero_slider_controls( $id, $count, $interval );
	}
	return $html . '</section>';
}

/**
 * Banner image: the three WebP widths in srcset, sized to cover the banner. Slide 1 is the
 * largest thing on the first screen, so it loads straight away with high priority; the
 * other slides' images load only after the page has finished loading (view.js).
 *
 * @param WP_HTML_Tag_Processor $tags  Processor positioned on the <img>.
 * @param int                   $index Slide index (0 = first).
 */
function ayesha_core_hero_slider_image( $tags, $index ) {
	$class = (string) $tags->get_attribute( 'class' );
	$id    = preg_match( '/\bwp-image-(\d+)\b/', $class, $m ) ? (int) $m[1] : 0;

	$srcset = array();
	$src    = null;
	if ( $id ) {
		foreach ( ayesha_core_banner_widths() as $width ) {
			$image = wp_get_attachment_image_src( $id, 'ayesha-banner-' . $width );
			// Only real copies of that width (a smaller original would come back as itself).
			if ( $image && (int) $image[1] === $width ) {
				$srcset[ $width ] = $image[0] . ' ' . $width . 'w';
				$src              = $image;
			}
		}
	}
	if ( $srcset ) {
		$tags->set_attribute( 'src', $src[0] );
		$tags->set_attribute( 'srcset', implode( ', ', $srcset ) );
		$tags->set_attribute( 'width', (string) $src[1] );
		$tags->set_attribute( 'height', (string) $src[2] );
	}
	// The photo covers the banner, which is taller than the photo's shape on phones and tablets,
	// so the browser needs a wider copy there than the screen width (e.g. 1920 on a 2x phone).
	$tags->set_attribute( 'sizes', '(max-width: 599px) 256vw, (max-width: 959px) 125vw, 100vw' );

	if ( 0 === $index ) {
		$tags->remove_attribute( 'loading' );
		$tags->set_attribute( 'fetchpriority', 'high' );
		return;
	}
	$tags->set_attribute( 'loading', 'lazy' );
	$tags->remove_attribute( 'fetchpriority' );
	foreach ( array( 'src', 'srcset' ) as $attr ) {
		$value = $tags->get_attribute( $attr );
		if ( is_string( $value ) && '' !== $value ) {
			$tags->set_attribute( 'data-' . $attr, $value );
		}
		$tags->remove_attribute( $attr );
	}
	// A 1x1 transparent GIF until view.js swaps the real image in (nothing is downloaded before that).
	$tags->set_attribute( 'src', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' );
}

/**
 * Previous / next, one dot per slide and Pause. Hidden until view.js starts the slider.
 *
 * @param string $id       Slider id.
 * @param int    $count    Number of slides.
 * @param int    $interval Milliseconds per slide.
 * @return string
 */
function ayesha_core_hero_slider_controls( $id, $count, $interval ) {
	$arrow = static function ( $direction ) {
		$path = 'prev' === $direction ? 'M15 5l-7 7 7 7' : 'M9 5l7 7-7 7';
		return '<svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="' . $path . '" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square"/></svg>';
	};
	$dots = '';
	for ( $i = 1; $i <= $count; $i++ ) {
		$dots .= sprintf(
			'<button type="button" class="ayesha-slider__dot" aria-controls="%1$s" aria-label="%2$s"%3$s><span class="ayesha-slider__tape" aria-hidden="true"></span></button>',
			esc_attr( $id . '-slide-' . $i ),
			/* translators: 1: slide number, 2: number of slides. */
			esc_attr( sprintf( __( 'Show slide %1$d of %2$d', 'ayesha-core' ), $i, $count ) ),
			1 === $i ? ' aria-current="true"' : ''
		);
	}
	$track = esc_attr( $id . '-track' );
	return '<div class="ayesha-slider__controls" style="--ayesha-slide-time:' . (int) $interval . 'ms" hidden>'
		. '<button type="button" class="ayesha-slider__btn ayesha-slider__prev" aria-controls="' . $track . '" aria-label="' . esc_attr__( 'Previous slide', 'ayesha-core' ) . '">' . $arrow( 'prev' ) . '</button>'
		. '<div class="ayesha-slider__dots">' . $dots . '</div>'
		. '<button type="button" class="ayesha-slider__btn ayesha-slider__pause" aria-controls="' . $track . '" aria-label="' . esc_attr__( 'Pause slides', 'ayesha-core' ) . '" data-label-pause="' . esc_attr__( 'Pause slides', 'ayesha-core' ) . '" data-label-play="' . esc_attr__( 'Play slides', 'ayesha-core' ) . '">'
		. '<svg class="ayesha-slider__icon-pause" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path d="M7 5h3.5v14H7zM13.5 5H17v14h-3.5z" fill="currentColor"/></svg>'
		. '<svg class="ayesha-slider__icon-play" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path d="M8 5v14l11-7z" fill="currentColor"/></svg>'
		. '</button>'
		. '<button type="button" class="ayesha-slider__btn ayesha-slider__next" aria-controls="' . $track . '" aria-label="' . esc_attr__( 'Next slide', 'ayesha-core' ) . '">' . $arrow( 'next' ) . '</button>'
		. '</div>';
}
