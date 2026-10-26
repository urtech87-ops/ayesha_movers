/**
 * Editor side of the "WhatsApp quote form" block: a live preview (rendered by the server, exactly
 * as visitors see it) and every label in the sidebar. No build step; plain wp.element calls.
 */
( function ( wp ) {
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var be = wp.blockEditor;
	var c = wp.components;

	var fields = [
		[ 'labelName', __( 'Name label', 'ayesha-core' ) ],
		[ 'labelPhone', __( 'Phone label', 'ayesha-core' ) ],
		[ 'hintPhone', __( 'Phone help text', 'ayesha-core' ), true ],
		[ 'labelService', __( 'Service label', 'ayesha-core' ) ],
		[ 'labelDate', __( 'Date label', 'ayesha-core' ) ],
		[ 'labelMessage', __( 'Message label', 'ayesha-core' ) ],
		[ 'hintMessage', __( 'Message help text', 'ayesha-core' ), true ],
		[ 'submitText', __( 'Button text', 'ayesha-core' ) ],
		[ 'note', __( 'Line under the button (empty = none)', 'ayesha-core' ), true ],
	];

	wp.blocks.registerBlockType( 'ayesha/whatsapp-form', {
		edit: function ( props ) {
			function control( field ) {
				var Control = field[ 2 ] ? c.TextareaControl : c.TextControl;
				return el( Control, {
					key: field[ 0 ],
					label: field[ 1 ],
					value: props.attributes[ field[ 0 ] ],
					onChange: function ( value ) {
						var change = {};
						change[ field[ 0 ] ] = value;
						props.setAttributes( change );
					},
					__nextHasNoMarginBottom: true,
				} );
			}
			return el( 'div', be.useBlockProps(),
				el( be.InspectorControls, null,
					el( c.PanelBody, { title: __( 'Words on the form', 'ayesha-core' ) },
						el( 'p', null, __( 'The services in the list are fixed. Requests are saved under Enquiries and emailed to the address in Business Info; the WhatsApp number is the one in Business Info.', 'ayesha-core' ) ),
						el( 'div', { style: { display: 'grid', gap: '16px' } }, fields.map( control ) )
					)
				),
				el( c.Disabled, null, el( wp.serverSideRender, { block: 'ayesha/whatsapp-form', attributes: props.attributes, httpMethod: 'POST' } ) )
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp );
