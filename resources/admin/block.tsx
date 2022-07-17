import {registerBlockType} from '@wordpress/blocks';
import {ServerSideRender, PanelBody, ToggleControl, SelectControl} from '@wordpress/components';
import {useBlockProps, InspectorControls} from '@wordpress/block-editor';
import {__} from '@wordpress/i18n'
import React from 'react';

registerBlockType('filterable-portfolio/projects', {
	apiVersion: 2,
	title: __('Filterable Portfolio', 'filterable-portfolio'),
	icon: 'megaphone',
	category: 'widgets',
	edit({attributes, setAttributes}) {
		const {isFeatured, showFilter, theme, buttonsAlignment} = attributes
		const blockProps = useBlockProps();
		const InspectorControlsEl = (
			<InspectorControls key="setting">
				<PanelBody
					title={__('Portfolio Options', 'filterable-portfolio')}
					initialOpen={true}
				>
					<ToggleControl
						label={__('Show filter buttons.', 'filterable-portfolio')}
						checked={showFilter}
						onChange={() => setAttributes({showFilter: !showFilter})}
					/>
					<ToggleControl
						label={__('Only show featured projects.', 'filterable-portfolio')}
						checked={isFeatured}
						onChange={() => setAttributes({isFeatured: !isFeatured})}
					/>
					<div className="filterable-portfolio-select-control">
						<SelectControl
							label={__('Theme', 'filterable-portfolio')}
							value={theme}
							options={[
								{label: 'One', value: 'one'},
								{label: 'Two', value: 'two'},
							]}
							onChange={(theme: string) => setAttributes({theme})}
						/>
					</div>
					<div className="filterable-portfolio-select-control">
						<SelectControl
							label={__('Filter buttons alignment', 'filterable-portfolio')}
							value={buttonsAlignment}
							options={[
								{label: 'Left', value: 'start'},
								{label: 'Center', value: 'center'},
								{label: 'Right', value: 'end'},
							]}
							onChange={(buttonsAlignment: string) => setAttributes({buttonsAlignment})}
						/>
					</div>
				</PanelBody>
			</InspectorControls>
		)
		return (
			<div {...blockProps}>
				{InspectorControlsEl}
				<ServerSideRender
					block="filterable-portfolio/projects"
					attributes={attributes}
				/>
			</div>
		);
	},

	save() {
		return null;
	}
});
