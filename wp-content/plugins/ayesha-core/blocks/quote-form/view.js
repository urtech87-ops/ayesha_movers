/*
 * Quote form enhancements. The form works without this file.
 * - After a failed send, move focus to the error summary; after a successful one, to the result.
 * - Stop a second click from sending the form twice.
 * - "For how long" only matters when a truck is picked.
 */
( function () {
	var form = document.querySelector( '.ayesha-qf__form' );
	var target = document.getElementById( 'ayesha-qf-summary' ) || document.getElementById( 'ayesha-qf-result' );
	if ( target ) {
		target.focus();
	}
	if ( ! form ) {
		return;
	}

	var button = form.querySelector( '.ayesha-qf__button' );
	form.addEventListener( 'submit', function ( event ) {
		if ( form.classList.contains( 'is-sending' ) ) {
			event.preventDefault();
			return;
		}
		form.classList.add( 'is-sending' );
		button.setAttribute( 'aria-disabled', 'true' );
	} );
	// Coming back with the browser's Back button: ready to send again.
	window.addEventListener( 'pageshow', function () {
		form.classList.remove( 'is-sending' );
		button.removeAttribute( 'aria-disabled' );
	} );

	var time = form.querySelector( '#aqf-truck_time-8h' );
	var group = time && time.closest( '.ayesha-qf__group' );
	function syncTruck() {
		var none = form.querySelector( '#aqf-truck-none' );
		var off = ! none || none.checked;
		group.hidden = off;
		group.querySelectorAll( 'input' ).forEach( function ( input ) {
			input.disabled = off;
		} );
	}
	if ( group ) {
		form.querySelectorAll( 'input[name="aq[truck]"]' ).forEach( function ( input ) {
			input.addEventListener( 'change', syncTruck );
		} );
		syncTruck();
	}
} )();
