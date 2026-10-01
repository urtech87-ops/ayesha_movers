/* Hide the mobile Call/WhatsApp bar while a form field has focus (the on-screen keyboard is open). */
( function () {
	if ( ! document.querySelector( '.ayesha-sticky-bar' ) ) {
		return;
	}
	var root = document.documentElement;
	var fields = 'input:not([type=checkbox]):not([type=radio]):not([type=button]):not([type=submit]):not([type=reset]):not([type=hidden]),textarea,select';
	document.addEventListener( 'focusin', function ( e ) {
		if ( e.target.matches && e.target.matches( fields ) ) {
			root.classList.add( 'ayesha-typing' );
		}
	} );
	document.addEventListener( 'focusout', function () {
		root.classList.remove( 'ayesha-typing' );
	} );
} )();
