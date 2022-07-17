import {registerBlockType} from '@wordpress/blocks';
import {ServerSideRender, PanelBody, ToggleControl, SelectControl} from '@wordpress/components';
import {useBlockProps, InspectorControls} from '@wordpress/block-editor';
import {__} from '@wordpress/i18n'
import React from 'react';

const icon = (
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" shapeRendering="geometricPrecision">
		<g transform="translate(0 28)">
			<g>
				<rect width="120" height="90" rx="0" ry="0" transform="translate(38 100)" fill="#D32F2F" strokeWidth="0"/>
				<rect width="120" height="90" rx="0" ry="0" transform="translate(196 100)" fill="#757575" strokeWidth="0"/>
				<rect width="120" height="90" rx="0" ry="0" transform="translate(354 100)" fill="#D32F2F" strokeWidth="0"/>
			</g>
			<g transform="translate(0 128)">
				<rect width="120" height="90" rx="0" ry="0" transform="translate(38 100)" fill="#757575" strokeWidth="0"/>
				<rect width="120" height="90" rx="0" ry="0" transform="translate(196 100)" fill="#2E7D32" strokeWidth="0"/>
				<rect width="120" height="90" rx="0" ry="0" transform="translate(354 100)" fill="#757575" strokeWidth="0"/>
			</g>
			<g transform="translate(0 256)">
				<rect width="120" height="90" rx="0" ry="0" transform="translate(38 100)" fill="#1976D2" strokeWidth="0"/>
				<rect width="120" height="90" rx="0" ry="0" transform="translate(196 100)" fill="#757575" strokeWidth="0"/>
				<rect width="120" height="90" rx="0" ry="0" transform="translate(354 100)" fill="#1976D2" strokeWidth="0"/>
			</g>
		</g>
		<g transform="translate(6 15)">
			<rect width="60" height="20" rx="0" ry="0" transform="translate(100 55)" fill="#757575" strokeWidth="0"/>
			<rect width="60" height="20" rx="0" ry="0" transform="translate(180 55)" fill="#D32F2F" strokeWidth="0"/>
			<rect width="60" height="20" rx="0" ry="0" transform="translate(260 55)" fill="#2E7D32" strokeWidth="0"/>
			<rect width="60" height="20" rx="0" ry="0" transform="translate(340 55)" fill="#1976D2" strokeWidth="0"/>
		</g>
	</svg>
);

registerBlockType('filterable-portfolio/projects', {
	apiVersion: 2,
	title: __('Filterable Portfolio', 'filterable-portfolio'),
	icon: icon,
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
