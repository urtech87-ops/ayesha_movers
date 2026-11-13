/**
 * Editor side of "Form tabs" and its "Form tab" panels. In the editor both panels show one after
 * the other (each under a small bar with its label), so every block in them can be edited.
 * Tab labels: select a tab (List View → Form tab) and change them in its sidebar. Which tab opens
 * first and the tab bar's name for screen readers: in the Form tabs sidebar. No build step.
 */
( function ( wp ) {
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var be = wp.blockEditor;
	var c = wp.components;

	var TEMPLATE = [
		[ 'ayesha/form-tab', { label: 'WhatsApp quote', shortLabel: 'WhatsApp', icon: 'whatsapp', panelId: 'whatsapp-form' }, [
			[ 'core/paragraph', { className: 'ayesha-form-tabs__intro', content: 'Fill in a few details and press Send via WhatsApp. Your request comes to us by email, and WhatsApp opens with your message ready to send.' } ],
			[ 'ayesha/whatsapp-form', {} ],
		] ],
		[ 'ayesha/form-tab', { label: 'Detailed quote request', shortLabel: 'Detailed quote', panelId: 'quote' }, [
			[ 'core/paragraph', { className: 'ayesha-form-tabs__intro', content: "Takes under a minute. We'll call or WhatsApp you back." } ],
			[ 'ayesha/quote-form', { showMoveParts: false, askReply: false } ],
		] ],
	];

	wp.blocks.registerBlockType( 'ayesha/form-tabs', {
		edit: function ( props ) {
			var tabs = wp.data.useSelect( function ( select ) {
				return select( 'core/block-editor' ).getBlocks( props.clientId );
			}, [ props.clientId ] );
			var options = tabs.map( function ( tab, i ) {
				return { value: String( i ), label: tab.attributes.label || 'Tab ' + ( i + 1 ) };
			} );
			return el( 'div', be.useBlockProps( { className: 'ayesha-form-tabs' } ),
				el( be.InspectorControls, null,
					el( c.PanelBody, { title: __( 'Tabs', 'ayesha-core' ) },
						el( 'div', { style: { display: 'grid', gap: '16px' } },
							el( c.SelectControl, {
								label: __( 'Opens first', 'ayesha-core' ),
								help: __( 'After a form is sent, the page opens on that form\'s tab instead.', 'ayesha-core' ),
								value: String( props.attributes.openFirst || 0 ),
								options: options,
								onChange: function ( value ) {
									props.setAttributes( { openFirst: parseInt( value, 10 ) || 0 } );
								},
								__nextHasNoMarginBottom: true,
							} ),
							el( c.TextControl, {
								label: __( 'Name of the tab bar (read out by screen readers)', 'ayesha-core' ),
								value: props.attributes.tablistLabel,
								onChange: function ( value ) {
									props.setAttributes( { tablistLabel: value } );
								},
								__nextHasNoMarginBottom: true,
							} ),
							el( 'p', null, __( 'To rename a tab, select it (List View → Form tab) and change "Tab label" in its sidebar.', 'ayesha-core' ) )
						)
					)
				),
				el( be.InnerBlocks, { allowedBlocks: [ 'ayesha/form-tab' ], template: TEMPLATE, templateLock: 'insert', orientation: 'vertical' } )
			);
		},
		save: function () {
			return el( be.InnerBlocks.Content );
		},
	} );

	wp.blocks.registerBlockType( 'ayesha/form-tab', {
		__experimentalLabel: function ( attributes ) {
			return attributes.label || undefined;
		},
		edit: function ( props ) {
			var a = props.attributes;
			function set( key ) {
				return function ( value ) {
					var change = {};
					change[ key ] = value;
					props.setAttributes( change );
				};
			}
			return el( 'div', be.useBlockProps( { className: 'ayesha-form-tabs__panel ayesha-form-tabs__panel--editor' } ),
				el( be.InspectorControls, null,
					el( c.PanelBody, { title: __( 'Tab', 'ayesha-core' ) },
						el( 'div', { style: { display: 'grid', gap: '16px' } },
							el( c.TextControl, { label: __( 'Tab label', 'ayesha-core' ), value: a.label, onChange: set( 'label' ), __nextHasNoMarginBottom: true } ),
							el( c.TextControl, { label: __( 'Short label on small phones (empty = the tab label)', 'ayesha-core' ), value: a.shortLabel, onChange: set( 'shortLabel' ), __nextHasNoMarginBottom: true } ),
							el( c.ToggleControl, {
								label: __( 'WhatsApp icon before the label', 'ayesha-core' ),
								checked: a.icon === 'whatsapp',
								onChange: function ( on ) {
									props.setAttributes( { icon: on ? 'whatsapp' : '' } );
								},
								__nextHasNoMarginBottom: true,
							} ),
							el( c.TextControl, {
								label: __( 'HTML anchor', 'ayesha-core' ),
								help: __( 'Links to the page with #this-word open this tab (e.g. #quote). Letters, numbers and dashes only.', 'ayesha-core' ),
								value: a.panelId,
								onChange: function ( value ) {
									props.setAttributes( { panelId: value.replace( /[^A-Za-z0-9_-]/g, '' ) } );
								},
								__nextHasNoMarginBottom: true,
							} )
						)
					)
				),
				el( 'p', { className: 'ayesha-form-tabs__editor-label' }, __( 'Tab:', 'ayesha-core' ) + ' ' + ( a.label || __( '(no label)', 'ayesha-core' ) ) ),
				el( be.InnerBlocks, { templateLock: false } )
			);
		},
		save: function () {
			return el( be.InnerBlocks.Content );
		},
	} );
} )( window.wp );
