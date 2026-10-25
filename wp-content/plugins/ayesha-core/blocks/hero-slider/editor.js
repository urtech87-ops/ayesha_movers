/**
 * Editor side of the "Banner slider" and "Banner slide" blocks. The slides are shown one under the
 * other so every word, button and photo can be clicked and changed. No build step; plain wp.element.
 */
( function ( wp ) {
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var be = wp.blockEditor;
	var c = wp.components;
	var MAX_SLIDES = 3;
	var quoteUrl = ( window.ayeshaSlider && window.ayeshaSlider.quoteUrl ) || '';

	// A new slide: photo, small line, heading (H2: only slide 1 has the page's H1), text, buttons, phone line.
	var slideTemplate = [
		[ 'core/image', { className: 'ayesha-slide__image', sizeSlug: 'ayesha-banner-1920', linkDestination: 'none' } ],
		[ 'core/group', { className: 'ayesha-slide__content', layout: { type: 'default' }, metadata: { name: __( 'Slide text', 'ayesha-core' ) } }, [
			[ 'core/paragraph', { className: 'ayesha-slide__eyebrow', placeholder: __( 'Small line above the heading', 'ayesha-core' ) } ],
			[ 'core/heading', { level: 2, className: 'ayesha-slide__title', placeholder: __( 'Heading', 'ayesha-core' ) } ],
			[ 'core/paragraph', { className: 'ayesha-slide__text', placeholder: __( 'One line about this slide', 'ayesha-core' ) } ],
			[ 'core/buttons', { className: 'ayesha-slide__buttons' }, [
				[ 'core/button', { className: 'is-style-yellow', text: __( 'Get a free quote', 'ayesha-core' ), url: quoteUrl } ],
				[ 'core/button', { className: 'is-style-whatsapp', text: __( 'WhatsApp us', 'ayesha-core' ), metadata: { name: 'WhatsApp us', bindings: { url: { source: 'ayesha/business', args: { key: 'whatsapp_url' } } } } } ],
			] ],
			[ 'core/group', { className: 'ayesha-slide__phone', layout: { type: 'flex', flexWrap: 'wrap' }, metadata: { name: __( 'Phone line', 'ayesha-core' ) } }, [
				[ 'core/paragraph', { content: __( 'Or call', 'ayesha-core' ) } ],
				[ 'core/paragraph', { metadata: { name: 'Main number (from Business Info)', bindings: { content: { source: 'ayesha/business', args: { key: 'phone_primary_link' } } } } } ],
			] ],
		] ],
	];

	wp.blocks.registerBlockType( 'ayesha/hero-slider', {
		edit: function ( props ) {
			var a = props.attributes;
			var count = wp.data.useSelect( function ( select ) {
				return select( 'core/block-editor' ).getBlockCount( props.clientId );
			}, [ props.clientId ] );
			var blockProps = be.useBlockProps( { className: 'ayesha-slider ayesha-slider--editor' } );
			var innerProps = be.useInnerBlocksProps( blockProps, {
				allowedBlocks: [ 'ayesha/hero-slide' ],
				template: [ [ 'ayesha/hero-slide' ] ],
				orientation: 'vertical',
				renderAppender: count < MAX_SLIDES ? be.InnerBlocks.ButtonBlockAppender : false,
			} );
			return el( wp.element.Fragment, null,
				el( be.InspectorControls, null,
					el( c.PanelBody, { title: __( 'Slides', 'ayesha-core' ) },
						el( 'p', null, __( 'Up to 3 slides. Visitors see slide 1 first. Keep slide 1\'s heading as the page\'s main heading (H1); the other slides use a smaller heading level (H2) but look the same.', 'ayesha-core' ) ),
						el( c.ToggleControl, {
							label: __( 'Change slides by themselves', 'ayesha-core' ),
							help: __( 'Stops while the mouse is over the banner or a button in it has focus, and never for visitors who ask their device for less motion. Visitors can also press Pause.', 'ayesha-core' ),
							checked: !! a.autoplay,
							onChange: function ( v ) { props.setAttributes( { autoplay: v } ); },
						} ),
						el( c.SelectControl, {
							label: __( 'Time per slide', 'ayesha-core' ),
							value: String( a.interval ),
							options: [
								{ label: __( '5 seconds', 'ayesha-core' ), value: '5000' },
								{ label: __( '6 seconds', 'ayesha-core' ), value: '6000' },
								{ label: __( '6.5 seconds', 'ayesha-core' ), value: '6500' },
								{ label: __( '7 seconds', 'ayesha-core' ), value: '7000' },
							],
							onChange: function ( v ) { props.setAttributes( { interval: parseInt( v, 10 ) } ); },
						} ),
						el( c.TextControl, {
							label: __( 'Name read out by screen readers', 'ayesha-core' ),
							value: a.label,
							onChange: function ( v ) { props.setAttributes( { label: v } ); },
						} )
					)
				),
				el( 'div', innerProps )
			);
		},
		save: function () {
			return el( be.InnerBlocks.Content );
		},
	} );

	wp.blocks.registerBlockType( 'ayesha/hero-slide', {
		edit: function () {
			var blockProps = be.useBlockProps( { className: 'ayesha-slide' } );
			return el( 'div', be.useInnerBlocksProps( blockProps, { template: slideTemplate } ) );
		},
		save: function () {
			return el( 'div', be.useInnerBlocksProps.save( be.useBlockProps.save( { className: 'ayesha-slide' } ) ) );
		},
	} );
} )( window.wp );
