/**
 * "Search engines" panel in the page editor sidebar: the title and description
 * shown in Google results. Saved with the page.
 */
( function ( wp ) {
	if ( ! wp || ! wp.plugins || ! wp.editor || ! wp.editor.PluginDocumentSettingPanel ) {
		return;
	}
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var sprintf = wp.i18n.sprintf;
	var TITLE = '_ayesha_seo_title';
	var DESCRIPTION = '_ayesha_seo_description';

	function help( value, max, empty ) {
		var length = ( value || '' ).length;
		/* translators: 1: characters used, 2: suggested maximum. */
		var count = sprintf( __( '%1$d of about %2$d characters.', 'ayesha-core' ), length, max );
		if ( length > max ) {
			count += ' ' + __( 'Longer text may be cut off in Google.', 'ayesha-core' );
		}
		return length ? count : empty;
	}

	function SearchEnginesPanel() {
		var meta = wp.data.useSelect( function ( select ) {
			return select( 'core/editor' ).getEditedPostAttribute( 'meta' );
		}, [] );
		var editPost = wp.data.useDispatch( 'core/editor' ).editPost;

		if ( ! meta || ! ( TITLE in meta ) ) {
			return null;
		}

		function update( key ) {
			return function ( value ) {
				var changes = {};
				changes[ key ] = value;
				editPost( { meta: changes } );
			};
		}

		return el(
			wp.editor.PluginDocumentSettingPanel,
			{ name: 'ayesha-search-engines', title: __( 'Search engines', 'ayesha-core' ) },
			el( wp.components.TextControl, {
				label: __( 'Title in search results', 'ayesha-core' ),
				value: meta[ TITLE ] || '',
				onChange: update( TITLE ),
				help: help( meta[ TITLE ], 60, __( 'Empty: the page name and site name are used.', 'ayesha-core' ) ),
				__next40pxDefaultSize: true,
				__nextHasNoMarginBottom: true,
			} ),
			el( 'div', { style: { height: '16px' } } ),
			el( wp.components.TextareaControl, {
				label: __( 'Description in search results', 'ayesha-core' ),
				value: meta[ DESCRIPTION ] || '',
				onChange: update( DESCRIPTION ),
				rows: 4,
				help: help( meta[ DESCRIPTION ], 155, __( 'One or two sentences about this page. Empty: Google picks text from the page.', 'ayesha-core' ) ),
				__nextHasNoMarginBottom: true,
			} )
		);
	}

	wp.plugins.registerPlugin( 'ayesha-search-engines', { render: SearchEnginesPanel } );
} )( window.wp );
