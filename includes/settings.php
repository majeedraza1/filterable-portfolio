<?php
$optionPage = new ShaplaTools_Settings_API;
$optionPage->add_menu(array(
	'page_title' 	=> __('Settings', 'filterable-portfolio'),
	'menu_title' 	=> __('Settings', 'filterable-portfolio'),
	'menu_slug' 	=> 'fp-settings',
	'parent_slug' 	=> 'edit.php?post_type=portfolio',
	'option_name' 	=> 'filterable_portfolio',
));
$optionPage->add_field(array(
	'id' 	=> 'columns',
	'type' 	=> 'select',
	'std' 	=> 'l4',
	'name' 	=> __('Columns', 'filterable-portfolio'),
	'desc' 	=> __('The number of items you want to see on the Large Desktop Layout.', 'filterable-portfolio'),
	'options' => array(
		'l12' => __('1 Column', 'filterable-portfolio'),
		'l6' => __('2 Columns', 'filterable-portfolio'),
		'l4' => __('3 Columns', 'filterable-portfolio'),
		'l3' => __('4 Columns', 'filterable-portfolio'),
		'l2' => __('6 Columns', 'filterable-portfolio'),
	),
));
$optionPage->add_field(array(
	'id' 	=> 'columns_desktop',
	'type' 	=> 'select',
	'std' 	=> 'm4',
	'name' 	=> __('Columns:Desktop', 'filterable-portfolio'),
	'desc' 	=> __('The number of items you want to see on the Desktop Layout (Screens size from 993 pixels DP to 1199 pixels DP)', 'filterable-portfolio'),
	'options' => array(
		'm12' => __('1 Column', 'filterable-portfolio'),
		'm6' => __('2 Columns', 'filterable-portfolio'),
		'm4' => __('3 Columns', 'filterable-portfolio'),
		'm3' => __('4 Columns', 'filterable-portfolio'),
	),
));
$optionPage->add_field(array(
	'id' 	=> 'columns_tablet',
	'type' 	=> 'select',
	'std' 	=> 's6',
	'name' 	=> __('Columns:Tablet', 'filterable-portfolio'),
	'desc' 	=> __('The number of items you want to see on the Tablet Layout (Screens size from 601 pixels DP to 992 pixels DP)', 'filterable-portfolio'),
	'options' => array(
		's12' => __('1 Column', 'filterable-portfolio'),
		's6' => __('2 Columns', 'filterable-portfolio'),
		's4' => __('3 Columns', 'filterable-portfolio'),
		's3' => __('4 Columns', 'filterable-portfolio'),
	),
));
$optionPage->add_field(array(
	'id' 	=> 'columns_phone',
	'type' 	=> 'select',
	'std' 	=> 'xs12',
	'name' 	=> __('Columns:Phone', 'filterable-portfolio'),
	'desc' 	=> __('The number of items you want to see on the Mobile Layout (Screens size from 320 pixels DP to 600 pixels DP)', 'filterable-portfolio'),
	'options' => array(
		'xs12' => __('1 Column', 'filterable-portfolio'),
		'xs6' => __('2 Columns', 'filterable-portfolio'),
		'xs4' => __('3 Columns', 'filterable-portfolio'),
		'xs3' => __('4 Columns', 'filterable-portfolio'),
	),
));
$optionPage->add_field(array(
	'id' 	=> 'portfolio_theme',
	'type' 	=> 'select',
	'std' 	=> 'two',
	'name' 	=> __('Portfolio Theme', 'filterable-portfolio'),
	'desc' 	=> __('Choose portfolio theme.', 'filterable-portfolio'),
	'options' => array(
		'one' => __('Theme One', 'filterable-portfolio'),
		'two' => __('Theme Two', 'filterable-portfolio'),
	),
));
$optionPage->add_field(array(
	'id' 	=> 'image_size',
	'type' 	=> 'image_sizes',
	'std' 	=> 'filterable-portfolio',
	'name' 	=> __('Image Size', 'filterable-portfolio'),
	'desc' 	=> __('Choose portfolio images size.', 'filterable-portfolio'),
));
$optionPage->add_field(array(
	'id' 	=> 'button_color',
	'type' 	=> 'color',
	'std' 	=> '#4cc1be',
	'name' 	=> __('Button Color', 'filterable-portfolio'),
	'desc' 	=> __('Choose color for filter buttons, border color and details buttons.', 'filterable-portfolio'),
));
$optionPage->add_field(array(
	'id' 	=> 'show_related_projects',
	'type' 	=> 'checkbox',
	'std' 	=> 1,
	'name' 	=> __('Show Related Projects', 'filterable-portfolio'),
	'desc' 	=> __('Enable to show related projects on portfolio single page.', 'filterable-portfolio'),
));
$optionPage->add_field(array(
	'id' 	=> 'related_projects_text',
	'type' 	=> 'text',
	'std' 	=> __('Related Projects', 'filterable-portfolio'),
	'name' 	=> __('Related Projects Text', 'filterable-portfolio'),
	'desc' 	=> __('Enter the text for related projects.', 'filterable-portfolio'),
));
$optionPage->add_field(array(
	'id' 	=> 'related_projects_number',
	'type' 	=> 'number',
	'std' 	=> 3,
	'name' 	=> __('# of Related Projects', 'filterable-portfolio'),
	'desc' 	=> __('How many related projects you want to show in single portfolio page.', 'filterable-portfolio'),
));
$optionPage->add_field(array(
	'id' 	=> 'custom_css',
	'type' 	=> 'textarea',
	'std' 	=> '',
	'name' 	=> __('Custom CSS', 'filterable-portfolio'),
	'desc' 	=> __('Quickly add some CSS to your theme by adding it to this block.', 'filterable-portfolio'),
	'placeholder' 	=> '.portfolio-items { font-size: 20px; }',
));