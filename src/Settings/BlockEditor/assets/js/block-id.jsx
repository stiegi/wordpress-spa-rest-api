import assign from 'lodash.assign';

const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { InspectorControls } = wp.editor;
const { PanelBody, TextControl, CheckboxControl } = wp.components;
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
		stripHTML: {
			type: 'boolean',
			default: false
		}
	} );
	return settings;
};

addFilter( 'blocks.registerBlockType', 'Spa/Settings/BlockEditor/BlockId/attribute/', addSpaIdAttribute );

const addSpaId = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {

		const { spaId, stripHTML } = props.attributes;
		const help = JSON.parse(window._spa.settings.settings).General.spaIdHelp;

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
							help = { help }
							onChange={ ( newValue ) => {
								props.setAttributes( {
									spaId: newValue,
								} );
							} }
						/>
						{ props.name === 'core/paragraph' &&
						<CheckboxControl
							value={stripHTML}
							help='Strip HTML tags from paragraph'
							onChange={(newValue) => {
								props.setAttributes({
									stripHTML: newValue,
								});
							}}
						/>
						}
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	};
}, 'addSpaId' );

addFilter( 'editor.BlockEdit', 'Spa/Settings/BlockEditor/BlockId/add-spa-id', addSpaId );


