/*
 * Banner slider. Without this script slide 1 shows as a normal banner (the server hides the rest).
 * Arrows, dots and Pause are real buttons; Left/Right arrow keys work inside the banner.
 * Autoplay stops on hover, while anything in the banner has focus, while the tab is hidden, after
 * Pause, and never runs for visitors who ask for reduced motion.
 */
( function () {
	var reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' );

	function start( root ) {
		var slides = Array.prototype.slice.call( root.querySelectorAll( '.ayesha-slider__track > .wp-block-ayesha-hero-slide' ) );
		var controls = root.querySelector( '.ayesha-slider__controls' );
		if ( slides.length < 2 || ! controls ) {
			return;
		}
		var track = root.querySelector( '.ayesha-slider__track' );
		var dots = Array.prototype.slice.call( controls.querySelectorAll( '.ayesha-slider__dot' ) );
		var pause = controls.querySelector( '.ayesha-slider__pause' );
		var interval = parseInt( root.getAttribute( 'data-interval' ), 10 ) || 6500;
		var current = 0;
		var timer = null;
		var stopped = root.getAttribute( 'data-autoplay' ) !== '1' || reduce.matches; // by Pause, the block setting or reduced motion
		var holds = { hover: false, focus: false, hidden: false };

		function loadImage( slide ) {
			var img = slide.querySelector( 'img[data-src]' );
			if ( ! img ) {
				return;
			}
			if ( img.getAttribute( 'data-srcset' ) ) {
				img.setAttribute( 'srcset', img.getAttribute( 'data-srcset' ) );
				img.removeAttribute( 'data-srcset' );
			}
			img.setAttribute( 'src', img.getAttribute( 'data-src' ) );
			img.removeAttribute( 'data-src' );
		}

		function show( index, fromUser ) {
			index = ( index + slides.length ) % slides.length;
			if ( index === current ) {
				return;
			}
			loadImage( slides[ index ] );
			slides.forEach( function ( slide, i ) {
				var active = i === index;
				slide.classList.toggle( 'is-active', active );
				if ( active ) {
					slide.removeAttribute( 'aria-hidden' );
					slide.removeAttribute( 'inert' );
				} else {
					slide.setAttribute( 'aria-hidden', 'true' );
					slide.setAttribute( 'inert', '' );
				}
			} );
			dots.forEach( function ( dot, i ) {
				if ( i === index ) {
					dot.setAttribute( 'aria-current', 'true' );
				} else {
					dot.removeAttribute( 'aria-current' );
				}
			} );
			current = index;
			loadImage( slides[ ( index + 1 ) % slides.length ] );
			if ( fromUser ) {
				schedule();
			}
		}

		function running() {
			return ! stopped && ! holds.hover && ! holds.focus && ! holds.hidden;
		}

		// Restart the timer and the gold fill of the current dot (the fill shows the time left).
		function schedule() {
			window.clearTimeout( timer );
			root.classList.toggle( 'is-playing', running() );
			if ( running() ) {
				root.classList.remove( 'is-restart' );
				void root.offsetWidth; // restart the CSS fill animation
				root.classList.add( 'is-restart' );
				timer = window.setTimeout( function () {
					show( current + 1 );
					schedule();
				}, interval );
			}
		}

		function hold( reason, on ) {
			holds[ reason ] = on;
			schedule();
		}

		function setStopped( on ) {
			stopped = on;
			pause.setAttribute( 'aria-label', pause.getAttribute( on ? 'data-label-play' : 'data-label-pause' ) );
			root.classList.toggle( 'is-stopped', on );
			// Screen readers announce slide changes only while nothing changes by itself.
			track.setAttribute( 'aria-live', on ? 'polite' : 'off' );
			schedule();
		}

		controls.querySelector( '.ayesha-slider__prev' ).addEventListener( 'click', function () {
			show( current - 1, true );
		} );
		controls.querySelector( '.ayesha-slider__next' ).addEventListener( 'click', function () {
			show( current + 1, true );
		} );
		dots.forEach( function ( dot, i ) {
			dot.addEventListener( 'click', function () {
				show( i, true );
			} );
		} );
		pause.addEventListener( 'click', function () {
			setStopped( ! stopped );
		} );

		root.addEventListener( 'keydown', function ( e ) {
			if ( e.target.closest && e.target.closest( 'input, textarea, select' ) ) {
				return;
			}
			if ( 'ArrowLeft' === e.key || 'ArrowRight' === e.key ) {
				e.preventDefault();
				show( current + ( 'ArrowLeft' === e.key ? -1 : 1 ), true );
			}
		} );
		root.addEventListener( 'mouseenter', function () {
			hold( 'hover', true );
		} );
		root.addEventListener( 'mouseleave', function () {
			hold( 'hover', false );
		} );
		root.addEventListener( 'focusin', function () {
			hold( 'focus', true );
		} );
		root.addEventListener( 'focusout', function ( e ) {
			if ( ! e.relatedTarget || ! root.contains( e.relatedTarget ) ) {
				hold( 'focus', false );
			}
		} );
		document.addEventListener( 'visibilitychange', function () {
			hold( 'hidden', document.hidden );
		} );
		if ( reduce.addEventListener ) {
			reduce.addEventListener( 'change', function () {
				if ( reduce.matches ) {
					setStopped( true );
				}
			} );
		}

		// The other slides' photos load only after the page has finished loading.
		if ( 'complete' === document.readyState ) {
			loadImage( slides[ 1 ] );
		} else {
			window.addEventListener( 'load', function () {
				loadImage( slides[ 1 ] );
			} );
		}

		controls.hidden = false;
		root.classList.add( 'is-ready' );
		setStopped( stopped );
	}

	Array.prototype.forEach.call( document.querySelectorAll( '.ayesha-slider--multi' ), start );
} )();
