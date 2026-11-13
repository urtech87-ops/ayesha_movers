/*
 * Form tabs (block "ayesha/form-tabs"), WAI-ARIA tabs with manual activation:
 * - Left/Right move between the tabs (wrapping round), Home/End to the first/last, Enter/Space
 *   (or a click) open the focused tab. Only the open tab is in the Tab order.
 * - A link on the page to a #place inside a tab (e.g. #quote) opens that tab first.
 * - Both forms stay on the page, so what was typed in one is still there after switching.
 * - A short fade when switching, only with Page animations on and without reduced motion.
 * The card already became tabs (and opened the tab of the address's #link) in the small script
 * printed after it; without JavaScript both forms simply show one after the other.
 */
( function () {
	var motion = document.body.classList.contains( 'ayesha-motion' ) && ! window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	Array.prototype.forEach.call( document.querySelectorAll( '.ayesha-form-tabs.is-tabbed' ), function ( root ) {
		var tabs = Array.prototype.slice.call( root.querySelectorAll( '.ayesha-form-tabs__bar > [role="tab"]' ) );
		var panels = tabs.map( function ( tab ) {
			return document.getElementById( tab.getAttribute( 'aria-controls' ) );
		} );
		if ( ! tabs.length || panels.indexOf( null ) !== -1 ) {
			return;
		}
		panels.forEach( function ( panel, i ) {
			panel.setAttribute( 'role', 'tabpanel' );
			panel.setAttribute( 'aria-labelledby', tabs[ i ].id );
			// The browser focuses the panel a #link points at: no ring for that (the next Tab still
			// starts from there); a ring again once it is reached with the keyboard.
			function quiet() {
				if ( window.location.hash.slice( 1 ) === panel.id && ! panel.ayeshaKeyboard ) {
					panel.classList.add( 'is-link-target' );
				}
			}
			panel.addEventListener( 'focus', quiet );
			panel.addEventListener( 'blur', function () {
				panel.classList.remove( 'is-link-target' );
			} );
			panel.tabIndex = 0;
			if ( document.activeElement === panel ) {
				quiet();
			}
		} );
		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Tab' ) {
				panels.forEach( function ( panel ) {
					panel.ayeshaKeyboard = true;
				} );
			}
		} );

		function current() {
			return tabs.findIndex( function ( tab ) {
				return tab.getAttribute( 'aria-selected' ) === 'true';
			} );
		}

		function open( index, fade ) {
			if ( index === current() ) {
				return;
			}
			tabs.forEach( function ( tab, i ) {
				var on = i === index;
				tab.setAttribute( 'aria-selected', on ? 'true' : 'false' );
				tab.tabIndex = on ? 0 : -1;
				panels[ i ].classList.toggle( 'is-active', on );
				panels[ i ].classList.remove( 'is-entering' );
			} );
			if ( fade && motion ) {
				panels[ index ].classList.add( 'is-entering' );
			}
		}

		root.addEventListener( 'animationend', function ( event ) {
			event.target.classList.remove( 'is-entering' );
		} );

		tabs.forEach( function ( tab, i ) {
			tab.addEventListener( 'click', function () {
				open( i, true );
			} );
			tab.addEventListener( 'keydown', function ( event ) {
				var to = { ArrowLeft: i - 1, ArrowRight: i + 1, Home: 0, End: tabs.length - 1 }[ event.key ];
				if ( to === undefined ) {
					return;
				}
				event.preventDefault();
				to = ( to + tabs.length ) % tabs.length;
				tabs.forEach( function ( other, j ) {
					other.tabIndex = j === to ? 0 : -1;
				} );
				tabs[ to ].focus();
			} );
		} );

		// Leaving the tab bar without opening the focused tab: Tab comes back to the open one.
		root.querySelector( '.ayesha-form-tabs__bar' ).addEventListener( 'focusout', function ( event ) {
			if ( ! event.currentTarget.contains( event.relatedTarget ) ) {
				tabs.forEach( function ( tab ) {
					tab.tabIndex = tab.getAttribute( 'aria-selected' ) === 'true' ? 0 : -1;
				} );
			}
		} );

		// Open the tab that holds the element with this id (no change when it is in neither).
		function openFor( id ) {
			var target = id && document.getElementById( id );
			var index = target ? panels.findIndex( function ( panel ) {
				return panel.contains( target );
			} ) : -1;
			if ( index !== -1 ) {
				open( index, false );
			}
		}

		function idOf( hash ) {
			try {
				return decodeURIComponent( hash.slice( 1 ) );
			} catch ( e ) {
				return '';
			}
		}

		window.addEventListener( 'hashchange', function () {
			openFor( idOf( window.location.hash ) );
		} );

		// A link to the same #place again (no hashchange), e.g. the header's "Free quote".
		document.addEventListener( 'click', function ( event ) {
			var link = event.target.closest && event.target.closest( 'a[href*="#"]' );
			if ( link && link.hash && link.pathname === window.location.pathname && link.host === window.location.host ) {
				openFor( idOf( link.hash ) );
			}
		} );
	} );
} )();
