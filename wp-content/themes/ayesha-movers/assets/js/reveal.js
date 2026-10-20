/*
 * Scroll reveals: section headings and cards below the first screen fade and slide up once as
 * they come into view (a short stagger for cards in a row). Only with "Page animations" on
 * (class ayesha-motion on <body>) and never with reduced motion. The hiding class is added here,
 * so without JavaScript everything is simply visible. Forms, the phone bar and the WhatsApp
 * button are never animated; nothing animates again when scrolling back up.
 */
( function () {
	if ( ! document.body.classList.contains( 'ayesha-motion' ) || ! ( 'IntersectionObserver' in window ) || window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		return;
	}

	var targets = [
		'main h2',
		'.ayesha-service-card',
		'.ayesha-svc__section',
		'.ayesha-reason',
		'.ayesha-steps > li',
		'.ayesha-route > li',
		'.ayesha-jump__list > li',
		'.ayesha-review',
	].join( ',' );
	var never = '.ayesha-slider, form, .wp-block-ayesha-quote-form, .ayesha-sticky-bar, .ayesha-wa-float, .ayesha-contact';
	var cards = '.ayesha-service-card, .ayesha-svc__section, .ayesha-reason';

	var items = Array.prototype.filter.call( document.querySelectorAll( targets ), function ( el ) {
		if ( el.closest( never ) ) {
			return false;
		}
		// A card moves as a whole, so its own heading doesn't move again.
		if ( el.matches( 'h2' ) && el.closest( cards ) ) {
			return false;
		}
		// Only what starts below the first screen, so nothing visible blinks out when the page opens.
		return el.getBoundingClientRect().top > window.innerHeight;
	} );

	function done( e ) {
		var el = e.currentTarget;
		if ( e.target !== el ) {
			return;
		}
		el.removeEventListener( 'transitionend', done );
		el.classList.remove( 'ayesha-reveal', 'is-revealed' );
		el.style.removeProperty( 'transition-delay' );
	}

	var observer = new IntersectionObserver( function ( entries ) {
		var shown = 0;
		entries.forEach( function ( entry ) {
			if ( ! entry.isIntersecting ) {
				return;
			}
			var el = entry.target;
			observer.unobserve( el );
			// Cards that arrive together come in one after another (at most 4 steps of 90 ms).
			el.style.transitionDelay = Math.min( shown, 4 ) * 90 + 'ms';
			shown++;
			el.addEventListener( 'transitionend', done );
			el.classList.add( 'is-revealed' );
		} );
	}, { threshold: 0.15 } );

	items.forEach( function ( el ) {
		el.classList.add( 'ayesha-reveal' );
		observer.observe( el );
	} );
} )();
