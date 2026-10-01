/**
 * Editor side of the "ayesha/business" Block Bindings source.
 * Shows the real Business Info values inside bound blocks and lists the fields
 * in the block's Attributes panel. Values are edited on Settings > Business Info.
 */
( function ( blocks, data ) {
	if ( ! blocks || ! blocks.registerBlockBindingsSource || ! data ) {
		return;
	}

	var kinds = {};
	data.fields.forEach( function ( field ) {
		kinds[ field.key ] = field.kind;
	} );

	function stripTags( html ) {
		var doc = new window.DOMParser().parseFromString( html || '', 'text/html' );
		return doc.body.textContent || '';
	}

	function waUrl( message ) {
		if ( ! data.whatsappDigits ) {
			return '';
		}
		var text = typeof message === 'string' ? message : data.whatsappMessage;
		return 'https://wa.me/' + data.whatsappDigits + ( text ? '?text=' + encodeURIComponent( text ) : '' );
	}

	function valueFor( args, attribute ) {
		var key = args && args.key;
		if ( ! key || ! ( key in data.values ) ) {
			return undefined;
		}
		var value = data.values[ key ];
		if ( key === 'whatsapp_url' && args.message !== undefined ) {
			value = waUrl( args.message );
		}
		// Custom link text for *_link keys: the editor shows the label as plain text.
		if ( args.label && kinds[ key ] === 'html' && /_link$/.test( key ) ) {
			value = args.label;
		}
		if ( attribute === 'url' ) {
			return kinds[ key ] === 'url' ? value : undefined;
		}
		if ( attribute === 'text' ) {
			return stripTags( value );
		}
		return value;
	}

	blocks.registerBlockBindingsSource( {
		name: 'ayesha/business',
		label: wp.i18n.__( 'Business Info', 'ayesha-core' ),
		getValues: function ( props ) {
			var out = {};
			Object.keys( props.bindings ).forEach( function ( attribute ) {
				var v = valueFor( props.bindings[ attribute ].args, attribute );
				if ( v !== undefined ) {
					out[ attribute ] = v;
				}
			} );
			return out;
		},
		getFieldsList: function () {
			return data.fields.map( function ( field ) {
				return { label: field.label, type: 'string', args: { key: field.key } };
			} );
		},
	} );
} )( window.wp && window.wp.blocks, window.ayeshaBusiness );
