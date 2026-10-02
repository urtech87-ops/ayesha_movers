/* Our Services: mark the service on screen in the "Jump to a service" menu (sticky on computers).
   The menu is plain in-page links and works without this script; it only adds aria-current. */
( function () {
	var links = document.querySelectorAll( '.ayesha-jump a[href^="#"]' );
	if ( ! links.length || ! ( 'IntersectionObserver' in window ) ) {
		return;
	}
	var sections = [];
	links.forEach( function ( link ) {
		var target = document.getElementById( decodeURIComponent( link.hash.slice( 1 ) ) );
		if ( target ) {
			sections.push( { el: target.closest( 'section' ) || target, link: link } );
		}
	} );
	var onScreen = new Set();
	var current = null;
	// A thin line a third of the way down the screen: the section crossing it is the current one.
	var observer = new IntersectionObserver( function ( entries ) {
		entries.forEach( function ( entry ) {
			onScreen[ entry.isIntersecting ? 'add' : 'delete' ]( entry.target );
		} );
		var match = null;
		for ( var i = 0; i < sections.length && ! match; i++ ) {
			if ( onScreen.has( sections[ i ].el ) ) {
				match = sections[ i ].link;
			}
		}
		if ( match === current ) {
			return;
		}
		if ( current ) {
			current.removeAttribute( 'aria-current' );
		}
		if ( match ) {
			match.setAttribute( 'aria-current', 'location' );
		}
		current = match;
	}, { rootMargin: '-33% 0px -66% 0px' } );
	sections.forEach( function ( section ) {
		observer.observe( section.el );
	} );
} )();
