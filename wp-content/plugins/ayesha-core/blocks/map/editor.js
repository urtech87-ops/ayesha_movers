/**
 * Editor side of the "Map (Business Info address)" block: the zoom in the sidebar and a preview of
 * the map (rendered by the server, as visitors see it). No build step; plain wp.element calls.
 */
( function ( wp ) {
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var be = wp.blockEditor;
	var c = wp.components;

	wp.blocks.registerBlockType( 'ayesha/map', {
		edit: function ( props ) {
			return el( 'div', be.useBlockProps(),
				el( be.InspectorControls, null,
					el( c.PanelBody, { title: __( 'Map', 'ayesha-core' ) },
						el( 'p', null, __( 'The address comes from Settings → Business Info → Business address.', 'ayesha-core' ) ),
						el( c.RangeControl, {
							label: __( 'Zoom (10 = all of Bahrain, 15 = streets, 18 = buildings)', 'ayesha-core' ),
							value: props.attributes.zoom,
							min: 8,
							max: 19,
							onChange: function ( value ) {
								props.setAttributes( { zoom: value } );
							},
							__nextHasNoMarginBottom: true,
						} )
					)
				),
				el( c.Disabled, null, el( wp.serverSideRender, { block: 'ayesha/map', attributes: props.attributes } ) )
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp );
