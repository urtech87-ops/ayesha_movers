/**
 * Editor side of the "Quote request form" block: a live preview (rendered by the server,
 * exactly as visitors see it) and the labels and button text in the sidebar.
 * No build step; plain wp.element calls.
 */
( function ( wp ) {
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var be = wp.blockEditor;
	var c = wp.components;

	// Sidebar panels: [panel title, [attribute, label, multiline?], ...].
	var panels = [
		[ __( 'Part 1: About you', 'ayesha-core' ), [
			[ 'legendYou', __( 'Heading of part 1', 'ayesha-core' ) ],
			[ 'labelName', __( 'Name label', 'ayesha-core' ) ],
			[ 'labelPhone', __( 'Phone label', 'ayesha-core' ) ],
			[ 'hintPhone', __( 'Phone help text', 'ayesha-core' ), true ],
			[ 'labelEmail', __( 'Email label', 'ayesha-core' ) ],
			[ 'hintEmail', __( 'Email help text', 'ayesha-core' ), true ],
			[ 'labelReply', __( '"Best way to reply" label', 'ayesha-core' ) ],
		] ],
		[ __( 'Part 2: Your move', 'ayesha-core' ), [
			[ 'legendMove', __( 'Heading of part 2', 'ayesha-core' ) ],
			[ 'labelMoveType', __( 'Type of move label', 'ayesha-core' ) ],
			[ 'labelFrom', __( '"Moving from" label', 'ayesha-core' ) ],
			[ 'hintFrom', __( '"Moving from" help text', 'ayesha-core' ) ],
			[ 'labelTo', __( '"Moving to" label', 'ayesha-core' ) ],
			[ 'hintTo', __( '"Moving to" help text', 'ayesha-core' ) ],
			[ 'labelDate', __( 'Date label', 'ayesha-core' ) ],
			[ 'labelFlexible', __( '"Flexible date" tick box', 'ayesha-core' ) ],
			[ 'labelSize', __( 'Property size label', 'ayesha-core' ) ],
			[ 'labelPickup', __( '"At pickup" label', 'ayesha-core' ) ],
			[ 'labelDropoff', __( '"At drop-off" label', 'ayesha-core' ) ],
			[ 'labelFloor', __( 'Floor label', 'ayesha-core' ) ],
			[ 'labelLift', __( 'Lift label', 'ayesha-core' ) ],
		] ],
		[ __( 'Part 3: What you need', 'ayesha-core' ), [
			[ 'legendNeeds', __( 'Heading of part 3', 'ayesha-core' ) ],
			[ 'labelServices', __( 'Services label', 'ayesha-core' ) ],
			[ 'hintServices', __( 'Services help text', 'ayesha-core' ) ],
			[ 'labelItems', __( '"Big or special items" label', 'ayesha-core' ) ],
			[ 'hintItems', __( '"Big or special items" help text', 'ayesha-core' ), true ],
			[ 'labelTruck', __( 'Truck hire label', 'ayesha-core' ) ],
			[ 'labelTruckTime', __( '"For how long" label', 'ayesha-core' ) ],
		] ],
		[ __( 'Button and messages', 'ayesha-core' ), [
			[ 'submitText', __( 'Button text', 'ayesha-core' ) ],
			[ 'privacyNote', __( 'Line under the button', 'ayesha-core' ), true ],
			[ 'successTitle', __( 'Heading after sending', 'ayesha-core' ) ],
			[ 'successText', __( 'Text after sending', 'ayesha-core' ), true ],
			[ 'whatsappText', __( 'WhatsApp button after sending', 'ayesha-core' ) ],
		] ],
	];

	wp.blocks.registerBlockType( 'ayesha/quote-form', {
		edit: function ( props ) {
			var blockProps = be.useBlockProps();
			var defaults = wp.blocks.getBlockType( 'ayesha/quote-form' ).attributes;

			function control( field ) {
				var key = field[ 0 ];
				return el( field[ 2 ] ? c.TextareaControl : c.TextControl, {
					key: key,
					label: field[ 1 ],
					value: props.attributes[ key ],
					rows: 3,
					// An emptied field goes back to the original words (except the optional line under the button).
					help: props.attributes[ key ] === '' && key !== 'privacyNote' ? __( 'Empty: the original text is used.', 'ayesha-core' ) : undefined,
					onChange: function ( value ) {
						var change = {};
						change[ key ] = value;
						props.setAttributes( change );
					},
					onBlur: function () {
						if ( props.attributes[ key ] === '' && key !== 'privacyNote' ) {
							var reset = {};
							reset[ key ] = defaults[ key ].default;
							props.setAttributes( reset );
						}
					},
					__nextHasNoMarginBottom: true,
					__next40pxDefaultSize: true,
				} );
			}

			return el(
				'div',
				blockProps,
				el(
					be.InspectorControls,
					null,
					el( c.PanelBody, { title: __( 'About this form', 'ayesha-core' ), initialOpen: true },
						el( 'p', null, __( 'Requests are saved under Enquiries in the dashboard menu and emailed to the address in Settings → Business Info. Change the words in the panels below; the choices in each list are fixed.', 'ayesha-core' ) )
					),
					panels.map( function ( panel, i ) {
						return el( c.PanelBody, { key: i, title: panel[ 0 ], initialOpen: false },
							el( 'div', { style: { display: 'grid', gap: '16px' } }, panel[ 1 ].map( control ) )
						);
					} )
				),
				el( c.Disabled, null, el( wp.serverSideRender, { block: 'ayesha/quote-form', attributes: props.attributes, httpMethod: 'POST' } ) )
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp );
