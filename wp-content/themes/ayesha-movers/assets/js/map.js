/*
 * Contact Us map: the page shows a drawing of Bahrain and a "Show map" button. Only when a visitor
 * clicks it is the Google map loaded (nothing goes to Google before that), in the drawing's place
 * and at the same size, so nothing moves. The place and zoom come from the button's link
 * (https://www.google.com/maps/@lat,lng,zoomz); the frame's title is the drawing's alt text.
 * Without JavaScript the button simply opens Google Maps in a new tab.
 */
( function () {
	Array.prototype.forEach.call( document.querySelectorAll( '.ayesha-map' ), function ( map ) {
		var link = map.querySelector( '.ayesha-map__show a' );
		var image = map.querySelector( 'img' );
		if ( ! link ) {
			return;
		}
		link.addEventListener( 'click', function ( event ) {
			var at = ( link.getAttribute( 'href' ) || '' ).match( /@(-?[\d.]+),(-?[\d.]+),([\d.]+)z/ );
			var frame = document.createElement( 'iframe' );
			event.preventDefault();
			frame.className = 'ayesha-map__frame';
			frame.src = 'https://maps.google.com/maps?ll=' + ( at ? at[ 1 ] + ',' + at[ 2 ] : '26.03,50.55' ) + '&z=' + ( at ? Math.round( at[ 3 ] ) : 10 ) + '&t=m&output=embed';
			frame.title = ( image && image.alt ) || 'Map of Bahrain';
			frame.setAttribute( 'loading', 'eager' );
			frame.setAttribute( 'referrerpolicy', 'no-referrer-when-downgrade' );
			frame.setAttribute( 'allowfullscreen', '' );
			map.classList.add( 'is-loaded' );
			map.textContent = '';
			map.appendChild( frame );
			frame.focus();
		} );
	} );
} )();
