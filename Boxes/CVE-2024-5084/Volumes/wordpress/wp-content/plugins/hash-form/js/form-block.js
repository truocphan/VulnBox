'use strict';

const {createElement} = wp.element;
const {registerBlockType} = wp.blocks;
const {InspectorControls} = wp.blockEditor;
const {serverSideRender: ServerSideRender} = wp;
const {PanelBody, SelectControl, ToggleControl,TextControl,RadioControl, Placeholder} = wp.components;

const HashFormIcon = <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 117.66 152.27"><g><g><path d="M0,3.46A3.46,3.46,0,0,1,3.14,0h80A3.53,3.53,0,0,1,85.6,1l31,31a3.47,3.47,0,0,1,1,2.43V148.81a3.46,3.46,0,0,1-3.46,3.46H31.63a3.46,3.46,0,1,1,0-6.92h79.11V38.07H83.05a3.46,3.46,0,0,1-3.46-3.46V6.92H6.92V145.35H14a3.46,3.46,0,1,1,0,6.92H3.46A3.46,3.46,0,0,1,0,148.81ZM106,31.15,86.51,11.68V31.15Z"/><path d="M78.66,59.3H95.09v6.61H78.66V85.75H72.05V42.87h6.61Zm0,39.67v16.42H72.05V99H52.22V92.36H95.09V99ZM39,99H22.57V92.36H39V72.52h6.61V115.4H39ZM39,59.3V42.87h6.61V59.3H65.44v6.61H22.57V59.3Z"/></g></g></svg>;

registerBlockType('hash-form/form-selector', {
	title: hash_form_block_data.i18n.title,
	icon: HashFormIcon,
	category: 'widgets',
	keywords: hash_form_block_data.i18n.form_keywords,
	description: hash_form_block_data.i18n.description,
	attributes: {
		formId: {
			type: 'string',
		},
	},

	edit(props) {
		const {attributes: {formId = '', displayTitle = false, displayDescription = false}, setAttributes} = props;
		const formOptions = Object.entries(hash_form_block_data.forms).map(value => ({
			value: value[0],
			label: value[1]
		}));
		let jsx;

		formOptions.unshift({
			value: '',
			label: hash_form_block_data.i18n.form_select
		});

		function selectForm(value) {
			setAttributes({formId: value});
		}

		function toggleDisplayTitle(value) {
			setAttributes({displayTitle: value});
		}

		function toggleDisplayDescription(value) {
			setAttributes({displayDescription: value});
		}

		jsx = [
			<InspectorControls key="hash-form-selector-inspector-controls">
				<PanelBody title={hash_form_block_data.i18n.form_settings}>
					<SelectControl
						label={hash_form_block_data.i18n.form_selected}
						value={formId}
						options={formOptions}
						onChange={selectForm}
					/>
				</PanelBody>
			</InspectorControls>
		];

		if (formId) {
			jsx.push(
				<ServerSideRender
					key="hash-form-selector-server-side-renderer"
					block="hash-form/form-selector"
					attributes={props.attributes}
				/>
			);
		} else {
			jsx.push(
				<Placeholder
					key="hash-form-selector-wrap"
					icon={HashFormIcon}
					instructions={hash_form_block_data.i18n.title}
					className="hash-form-gutenberg-form-selector-wrap">
					<SelectControl
						key="hash-form-selector-select-control"
						value={formId}
						options={formOptions}
						onChange={selectForm}
					/>
				</Placeholder>
			);
		}
		return jsx;
	},
	save() {
		return null;
	},
});
