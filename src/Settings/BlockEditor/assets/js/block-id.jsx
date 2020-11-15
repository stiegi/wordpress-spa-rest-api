import assign from 'lodash.assign';

const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { InspectorControls } = wp.editor;
const { PanelBody, TextControl } = wp.components;
const { addFilter } = wp.hooks;
const { __ } = wp.i18n;

/**
 * Add spaId attribute to block.
 *
 * @param {object} settings Current block settings.
 * @returns {object} Modified block settings.
 */
const addSpaIdAttribute = ( settings ) => {

	settings.attributes = assign( settings.attributes, {
		spaId: {
			type: 'string',
			default: '',
		},
	} );
	return settings;
};

addFilter( 'blocks.registerBlockType', 'Spa/Settings/BlockEditor/BlockId/attribute/', addSpaIdAttribute );

const addSpaId = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {

		const { spaId } = props.attributes;

		return (
			<Fragment>
				<BlockEdit { ...props } />
				<InspectorControls>
					<PanelBody
						title={ __( 'SPA ID' ) }
						initialOpen={ true }
					>
						<TextControl
							value={ spaId }
							help = { 'Set an spaId for the selected block' }
							onChange={ ( newValue ) => {
								props.setAttributes( {
									spaId: newValue,
								} );
							} }
						/>
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	};
}, 'addSpaId' );

addFilter( 'editor.BlockEdit', 'Spa/Settings/BlockEditor/BlockId/add-spa-id', addSpaId );


