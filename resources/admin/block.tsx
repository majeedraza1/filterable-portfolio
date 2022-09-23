// @ts-ignore
import {registerBlockType} from '@wordpress/blocks';
// @ts-ignore
import {ServerSideRender, PanelBody, ToggleControl, SelectControl, RangeControl} from '@wordpress/components';
// @ts-ignore
import {useBlockProps, InspectorControls} from '@wordpress/block-editor';
import {__} from '@wordpress/i18n'
import React from 'react';

const icon = (
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" shapeRendering="geometricPrecision">
		<rect width="512" height="512" rx="8" fill="#fff7ed"/>
		<g transform="translate(0 28)">
			<g>
				<rect width="120" height="90" rx="0" ry="0" transform="translate(38 100)" fill="#D32F2F"
					  strokeWidth="0"/>
				<rect width="120" height="90" rx="0" ry="0" transform="translate(196 100)" fill="#757575"
					  strokeWidth="0"/>
				<rect width="120" height="90" rx="0" ry="0" transform="translate(354 100)" fill="#D32F2F"
					  strokeWidth="0"/>
			</g>
			<g transform="translate(0 128)">
				<rect width="120" height="90" rx="0" ry="0" transform="translate(38 100)" fill="#757575"
					  strokeWidth="0"/>
				<rect width="120" height="90" rx="0" ry="0" transform="translate(196 100)" fill="#2E7D32"
					  strokeWidth="0"/>
				<rect width="120" height="90" rx="0" ry="0" transform="translate(354 100)" fill="#757575"
					  strokeWidth="0"/>
			</g>
			<g transform="translate(0 256)">
				<rect width="120" height="90" rx="0" ry="0" transform="translate(38 100)" fill="#1976D2"
					  strokeWidth="0"/>
				<rect width="120" height="90" rx="0" ry="0" transform="translate(196 100)" fill="#757575"
					  strokeWidth="0"/>
				<rect width="120" height="90" rx="0" ry="0" transform="translate(354 100)" fill="#1976D2"
					  strokeWidth="0"/>
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

const columnsOptions = [
	{label: __('Default (as Global Settings)', 'filterable-portfolio'), value: '0'},
	{label: __('1 Column', 'filterable-portfolio'), value: '12'},
	{label: __('2 Columns', 'filterable-portfolio'), value: '6'},
	{label: __('3 Columns', 'filterable-portfolio'), value: '4'},
	{label: __('4 Columns', 'filterable-portfolio'), value: '3'},
	{label: __('6 Columns', 'filterable-portfolio'), value: '2'},
];

registerBlockType('filterable-portfolio/projects', {
	apiVersion: 2,
	title: __('Filterable Portfolio', 'filterable-portfolio'),
	icon: icon,
	category: 'widgets',
	// @ts-ignore
	edit({attributes, setAttributes}) {
		const {
			isFeatured,
			showFilter,
			filterBy,
			theme,
			buttonsAlignment,
			limit,
			columnsPhone,
			columnsTablet,
			columnsDesktop,
			columnsWidescreen
		} = attributes
		const blockProps = useBlockProps();
		const InspectorControlsEl = (
			<InspectorControls key="setting">
				<PanelBody
					title={__('Portfolio Options', 'filterable-portfolio')}
					initialOpen={true}
				>
					<div className="filterable-portfolio-select-control">
						<SelectControl
							label={__('Theme', 'filterable-portfolio')}
							value={theme}
							options={[
								{label: __('One', 'filterable-portfolio'), value: 'one'},
								{label: __('Two', 'filterable-portfolio'), value: 'two'},
							]}
							onChange={(theme: string) => setAttributes({theme})}
						/>
					</div>
					<RangeControl
						label={__('Limit', 'filterable-portfolio')}
						help={__('Limit total items to show. To show all set -1.', 'filterable-portfolio')}
						value={limit}
						onChange={(limit: number) => setAttributes({limit})}
						min={-1}
						max={100}
					/>
					<ToggleControl
						label={__('Only show featured projects.', 'filterable-portfolio')}
						checked={isFeatured}
						onChange={() => setAttributes({isFeatured: !isFeatured})}
					/>
				</PanelBody>
				<PanelBody
					title={__('Filter Settings', 'filterable-portfolio')}
					initialOpen={false}
				>
					<ToggleControl
						label={__('Show filter buttons.', 'filterable-portfolio')}
						checked={showFilter}
						onChange={() => setAttributes({showFilter: !showFilter})}
					/>
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
					<div className="filterable-portfolio-select-control">
						<SelectControl
							label={__('Filter by', 'filterable-portfolio')}
							value={filterBy}
							options={[
								{label: __('Categories', 'filterable-portfolio'), value: 'categories'},
								{label: __('Skills', 'filterable-portfolio'), value: 'skills'},
							]}
							onChange={(filterBy: string) => setAttributes({filterBy})}
						/>
					</div>
				</PanelBody>
				<PanelBody
					title={__('Responsive Settings', 'filterable-portfolio')}
					initialOpen={false}
				>
					<SelectControl
						label={__('Columns:Phone', 'filterable-portfolio')}
						value={columnsPhone}
						onChange={(columnsPhone: number) => setAttributes({columnsPhone})}
						options={columnsOptions}
					/>
					<SelectControl
						label={__('Columns:Tablet', 'filterable-portfolio')}
						value={columnsTablet}
						onChange={(columnsTablet: number) => setAttributes({columnsTablet})}
						options={columnsOptions}
					/>
					<SelectControl
						label={__('Columns:Desktop', 'filterable-portfolio')}
						value={columnsDesktop}
						onChange={(columnsDesktop: number) => setAttributes({columnsDesktop})}
						options={columnsOptions}
					/>
					<SelectControl
						label={__('Columns:Widescreen', 'filterable-portfolio')}
						value={columnsWidescreen}
						onChange={(columnsWidescreen: number) => setAttributes({columnsWidescreen})}
						options={columnsOptions}
					/>
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
