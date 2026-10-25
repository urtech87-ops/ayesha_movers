/*
 * Scroll reveals: section headings, cards and other parts below the first screen come in once as
 * they come into view, each section in its own way (cards rise one after another, the About photo
 * slides in from its side, the area tags pop in, the questions fade, numbers count up). Also the
 * slow sideways ticker of the trust strip under the Home banner. Only with "Page animations" on
 * (class ayesha-motion on <body>) and never with reduced motion. The hiding classes are added
 * here, so without JavaScript everything is simply visible and still. Forms, the phone bar and the
 * WhatsApp button are never animated; nothing animates again when scrolling back up.
 */
( function () {
	if ( ! document.body.classList.contains( 'ayesha-motion' ) || ! ( 'IntersectionObserver' in window ) || window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		return;
	}

	/*
	 * What moves and how. kind = extra class (left, right, pop, fade; none = rise); step = delay in ms
	 * between items of the same kind that arrive together; max = how many steps at most.
	 */
	var groups = [
		{ sel: 'main h2' },
		{ sel: '.ayesha-home-about__photo', kind: 'left' },
		{ sel: '.ayesha-home-about__text', kind: 'right' },
		{ sel: '.ayesha-service-card, .ayesha-svc__section, .ayesha-why-card, .ayesha-plan, .ayesha-review, .ayesha-reason, .ayesha-stat', step: 90, max: 4 },
		{ sel: '.ayesha-step', step: 160, max: 3 },
		{ sel: '.ayesha-areas > li', kind: 'pop', step: 35, max: 20 },
		{ sel: '.ayesha-faq > .wp-block-details', kind: 'fade', step: 60, max: 6 },
		{ sel: '.ayesha-route > li, .ayesha-jump__list > li', step: 90, max: 4 },
	];
	var never = '.ayesha-slider, form, .wp-block-ayesha-quote-form, .ayesha-sticky-bar, .ayesha-wa-float, .ayesha-contact, .ayesha-trust';
	// A part that moves as a whole: its own heading doesn't move again.
	var wholes = '.ayesha-service-card, .ayesha-svc__section, .ayesha-reason, .ayesha-why-card, .ayesha-plan, .ayesha-home-about__text';

	var items = [];
	groups.forEach( function ( group, index ) {
		Array.prototype.forEach.call( document.querySelectorAll( group.sel ), function ( el ) {
			if ( el.closest( never ) || el.ayeshaGroup !== undefined ) {
				return;
			}
			if ( el.matches( 'h2' ) && el.closest( wholes ) ) {
				return;
			}
			// Only what starts below the first screen, so nothing visible blinks out when the page opens.
			if ( el.getBoundingClientRect().top <= window.innerHeight ) {
				return;
			}
			el.ayeshaGroup = index;
			items.push( el );
		} );
	} );

	function done( e ) {
		var el = e.currentTarget;
		if ( e.target !== el ) {
			return;
		}
		el.removeEventListener( 'transitionend', done );
		el.className = el.className.replace( /\s*\bayesha-reveal(--\w+)?\b|\s*\bis-revealed\b/g, '' );
		el.style.removeProperty( 'transition-delay' );
	}

	/* A stat's number counts up from 1 to the number typed in the editor (never shows 0). */
	function countUp( stat ) {
		var number = stat.querySelector( '.ayesha-stat__number' );
		var parts = number && number.textContent.match( /^(\D*)(\d[\d,]*)(.*)$/ );
		if ( ! parts || number.children.length ) {
			return;
		}
		var target = parseInt( parts[ 2 ].replace( /,/g, '' ), 10 );
		var commas = parts[ 2 ].indexOf( ',' ) !== -1;
		if ( ! ( target > 1 ) ) {
			return;
		}
		var original = number.textContent;
		var start = null;
		number.style.minWidth = number.offsetWidth + 'px';
		function frame( now ) {
			start = start || now;
			var t = Math.min( 1, ( now - start ) / 1400 );
			var value = Math.max( 1, Math.round( target * ( 1 - Math.pow( 1 - t, 3 ) ) ) );
			number.textContent = parts[ 1 ] + ( commas ? value.toLocaleString( 'en-US' ) : value ) + parts[ 3 ];
			if ( t < 1 ) {
				window.requestAnimationFrame( frame );
			} else {
				number.textContent = original;
				number.style.removeProperty( 'min-width' );
			}
		}
		window.requestAnimationFrame( frame );
	}

	var observer = new IntersectionObserver( function ( entries ) {
		var shown = {};
		entries.forEach( function ( entry ) {
			if ( ! entry.isIntersecting ) {
				return;
			}
			var el = entry.target;
			var group = groups[ el.ayeshaGroup ];
			var count = shown[ el.ayeshaGroup ] || 0;
			observer.unobserve( el );
			// Parts that arrive together come in one after another.
			el.style.transitionDelay = Math.min( count, group.max || 4 ) * ( group.step || 90 ) + 'ms';
			shown[ el.ayeshaGroup ] = count + 1;
			el.addEventListener( 'transitionend', done );
			el.classList.add( 'is-revealed' );
			if ( el.classList.contains( 'ayesha-stat' ) ) {
				countUp( el );
			}
		} );
	}, { threshold: 0.15 } );

	items.forEach( function ( el ) {
		el.classList.add( 'ayesha-reveal' );
		if ( groups[ el.ayeshaGroup ].kind ) {
			el.classList.add( 'ayesha-reveal--' + groups[ el.ayeshaGroup ].kind );
		}
		observer.observe( el );
	} );

	/*
	 * Trust strip ticker (640px and wider; on phones the facts stay a still grid). The facts are
	 * copied (hidden from screen readers) until one run is wider than the screen, then the whole run
	 * is doubled and slides left by half its width forever. Stops while the mouse is over it. (No
	 * button of its own: the banner's Pause button is the only one on the page, user decision.)
	 */
	Array.prototype.forEach.call( document.querySelectorAll( '.ayesha-trust' ), function ( strip ) {
		var list = strip.querySelector( '.ayesha-trust__list' );
		if ( ! list || ! window.matchMedia( '(min-width: 640px)' ).matches ) {
			return;
		}
		var facts = Array.prototype.slice.call( list.children );
		function copyRun() {
			facts.forEach( function ( li ) {
				var copy = li.cloneNode( true );
				copy.setAttribute( 'aria-hidden', 'true' );
				list.appendChild( copy );
			} );
		}
		strip.classList.add( 'is-ticking' );
		var runs = 1;
		while ( list.scrollWidth < window.innerWidth * 1.1 && runs < 6 ) {
			copyRun();
			runs++;
		}
		var oneRun = list.children.length;
		Array.prototype.slice.call( list.children, 0, oneRun ).forEach( function ( li ) {
			var copy = li.cloneNode( true );
			copy.setAttribute( 'aria-hidden', 'true' );
			list.appendChild( copy );
		} );
		// About 45 px a second, whatever the screen width.
		strip.style.setProperty( '--ayesha-ticker-time', Math.round( list.scrollWidth / 2 / 45 ) + 's' );
	} );
} )();
