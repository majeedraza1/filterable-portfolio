import {InspectorControls, useBlockProps} from "@wordpress/block-editor";
import {PanelBody, RangeControl, SelectControl, ToggleControl} from "@wordpress/components";
import ServerSideRender from '@wordpress/server-side-render';
import {__} from "@wordpress/i18n";
import React from "react";

const columnsOptions = [
    {label: __('Default (as Global Settings)', 'filterable-portfolio'), value: '0'},
    {label: __('1 Column', 'filterable-portfolio'), value: '12'},
    {label: __('2 Columns', 'filterable-portfolio'), value: '6'},
    {label: __('3 Columns', 'filterable-portfolio'), value: '4'},
    {label: __('4 Columns', 'filterable-portfolio'), value: '3'},
    {label: __('6 Columns', 'filterable-portfolio'), value: '2'},
];

const orderByOptions = [
    {label: __('Portfolio ID', 'filterable-portfolio'), value: 'ID'},
    {label: __('Portfolio title', 'filterable-portfolio'), value: 'title'},
    {label: __('Portfolio date', 'filterable-portfolio'), value: 'date'},
    {label: __('Portfolio last modified date', 'filterable-portfolio'), value: 'modified'},
    {label: __('Random order', 'filterable-portfolio'), value: 'rand'},
];

const orderOptions = [
    {label: __('Ascending (lowest to highest)', 'filterable-portfolio'), value: 'ASC'},
    {label: __('Descending (highest to lowest)', 'filterable-portfolio'), value: 'DESC'},
];

export default function Edit({attributes, setAttributes}) {
    const {
        isFeatured, showFilter, filterBy, theme, buttonsAlignment, limit, columnsPhone, columnsTablet, columnsDesktop,
        columnsWidescreen, order, orderBy
    } = attributes
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
                        onChange={(theme) => setAttributes({theme})}
                    />
                </div>
                <RangeControl
                    label={__('Limit', 'filterable-portfolio')}
                    help={__('Limit total items to show. To show all set -1.', 'filterable-portfolio')}
                    value={limit}
                    onChange={(limit) => setAttributes({limit})}
                    min={-1}
                    max={100}
                />
                <ToggleControl
                    label={__('Only show featured projects.', 'filterable-portfolio')}
                    checked={isFeatured}
                    onChange={() => setAttributes({isFeatured: !isFeatured})}
                />
                <div className="filterable-portfolio-select-control">
                    <SelectControl
                        label={__('Order by', 'filterable-portfolio')}
                        value={orderBy}
                        options={orderByOptions}
                        onChange={(orderBy) => setAttributes({orderBy})}
                    />
                </div>
                <div className="filterable-portfolio-select-control">
                    <SelectControl
                        label={__('Order', 'filterable-portfolio')}
                        value={order}
                        options={orderOptions}
                        onChange={(order) => setAttributes({order})}
                    />
                </div>
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
                        onChange={(buttonsAlignment) => setAttributes({buttonsAlignment})}
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
                        onChange={(filterBy) => setAttributes({filterBy})}
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
                    onChange={(columnsPhone) => setAttributes({columnsPhone})}
                    options={columnsOptions}
                />
                <SelectControl
                    label={__('Columns:Tablet', 'filterable-portfolio')}
                    value={columnsTablet}
                    onChange={(columnsTablet) => setAttributes({columnsTablet})}
                    options={columnsOptions}
                />
                <SelectControl
                    label={__('Columns:Desktop', 'filterable-portfolio')}
                    value={columnsDesktop}
                    onChange={(columnsDesktop) => setAttributes({columnsDesktop})}
                    options={columnsOptions}
                />
                <SelectControl
                    label={__('Columns:Widescreen', 'filterable-portfolio')}
                    value={columnsWidescreen}
                    onChange={(columnsWidescreen) => setAttributes({columnsWidescreen})}
                    options={columnsOptions}
                />
            </PanelBody>
        </InspectorControls>
    )
    return (
        <div {...useBlockProps()}>
            {InspectorControlsEl}
            <ServerSideRender
                block="filterable-portfolio/projects"
                attributes={attributes}
            />
        </div>
    );
}