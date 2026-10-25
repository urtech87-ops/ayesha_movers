/*
 * "Our Services" dropdown keys. WordPress's Navigation block already opens the list with the arrow
 * button (Enter / Space / tap), on hover, and closes it with Esc. This adds Arrow Down on the menu
 * item or its arrow button (opens the list and moves to the first service) and Arrow Up / Down
 * inside the list.
 */
( function () {
	document.addEventListener( 'keydown', function ( e ) {
		if ( 'ArrowDown' !== e.key && 'ArrowUp' !== e.key ) {
			return;
		}
		// The item (li), not its list: the list (ul) carries the class wp-block-navigation-submenu too.
		var item = e.target.closest && e.target.closest( '.ayesha-header li.wp-block-navigation-submenu' );
		if ( ! item ) {
			return;
		}
		var toggle = item.querySelector( ':scope > .wp-block-navigation-submenu__toggle' );
		var links = Array.prototype.slice.call( item.querySelectorAll( ':scope > .wp-block-navigation__submenu-container > li > a' ) );
		if ( ! toggle || ! links.length ) {
			return;
		}
		var index = links.indexOf( e.target );
		e.preventDefault();
		if ( -1 === index ) {
			// On "Our Services" or its arrow button: open the list, then go to the first (or last) service.
			// After the click, WordPress shows the list at once but only notes that it is open a moment
			// later; moving focus into it before then closes it again. So wait briefly when we opened it.
			var target = links[ 'ArrowDown' === e.key ? 0 : links.length - 1 ];
			if ( 'true' === toggle.getAttribute( 'aria-expanded' ) ) {
				target.focus();
				return;
			}
			toggle.click();
			window.setTimeout( function () {
				target.focus();
			}, 80 );
			return;
		}
		if ( 'ArrowUp' === e.key && 0 === index ) {
			toggle.focus();
			return;
		}
		links[ Math.min( links.length - 1, index + ( 'ArrowDown' === e.key ? 1 : -1 ) ) ].focus();
	} );
} )();
