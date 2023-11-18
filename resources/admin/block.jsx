import {registerBlockType} from '@wordpress/blocks';
import {__} from '@wordpress/i18n';
import React from 'react';
import Edit from "./block/Edit.jsx";

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

registerBlockType('filterable-portfolio/projects', {
    apiVersion: 2,
    title: __('Filterable Portfolio', 'filterable-portfolio'),
    icon: icon,
    category: 'widgets',
    edit: Edit,

    save: () => null
});
