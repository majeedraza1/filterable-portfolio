<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Very simple WordPress Settings API wrapper class
 *
 * WordPress Option Page Wrapper class that implements WordPress Settings API and
 * give you easy way to create multi tabs admin menu and
 * add setting fields with build in validation.
 *
 * @version 	1.0.0 (Oct 28, 2016)
 * 
 * @author     	Sayful Islam <sayful.islam001@gmail.com>
 * @link 		www.sayfulit.com Sayful IT
 */
if ( !class_exists( 'ShaplaTools_Settings_API' ) ):
class ShaplaTools_Settings_API
{
	/**
     * Settings options array
     */
    private $options = array();

	/**
     * Settings menu fields array
     */
	private $menu_fields = array();

	/**
     * Settings fields array
     */
	private $fields = array();

	/**
     * Settings tabs array
     */
	private $tabs = array();

	/**
     * Initialization or class
     */
	public function __construct() {
		if(is_admin()){
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_init', array( $this, 'admin_init' ) );
		}
	}

	/**
	 * Add new admin menu
	 *
	 * This method is accessible outside the class for creating menu
	 * @param array $menu_fields
	 */
	public function add_menu( array $menu_fields)
	{
		if (!isset($menu_fields['page_title'], $menu_fields['menu_title'], $menu_fields['menu_slug'])) {
			throw new Exception('Required key is not set properly for creating menu.');
		}

		$this->menu_fields = $menu_fields;
	}

	/**
	 * Add new settings field
	 *
	 * This method is accessible outside the class for creating settings field
	 * @param array $field
	 */
	public function add_field(array $field)
	{
		if (!isset($field['id'], $field['name'])) {
			throw new Exception('Required key is not set properly for creating tab.');
		}

		$this->fields[] = $field;
	}

	/**
	 * Add setting page tab
	 *
	 * This method is accessible outside the class for creating page tab
	 * @param array $tab
	 */
	public function add_tab(array $tab)
	{
		if (!isset($tab['id'], $tab['title'])) {
			throw new Exception('Required key is not set properly for creating tab.');
		}

		$this->tabs[] = $tab;
	}

    /**
     * Register setting and its sanitization callback.
     * @return void
     */
    public function admin_init()
    {
        register_setting(
        	$this->menu_fields['option_name'],
        	$this->menu_fields['option_name'],
        	array( $this, 'sanitize_callback' )
        );
    }

    /**
     * Create admin menu
     */
	public function admin_menu() {
		$page_title = $this->menu_fields['page_title'];
		$menu_title = $this->menu_fields['menu_title'];
		$menu_slug 	= $this->menu_fields['menu_slug'];
		$capability = isset($this->menu_fields['capability']) ? $this->menu_fields['capability'] : 'manage_options';
		$parent_slug = isset($this->menu_fields['parent_slug']) ? $this->menu_fields['parent_slug'] : null;

		if ($parent_slug){
			add_submenu_page(
				$parent_slug,
				$page_title,
				$menu_title,
				$capability,
				$menu_slug,
				array($this, 'page_content' )
			);
		} else {
			add_menu_page(
				$page_title,
				$menu_title,
				$capability,
				$menu_slug,
				array($this, 'page_content' )
			);
		}
	}

	/**
	 * Load page content
	 */
	public function  page_content() {
		ob_start(); ?>

		<div class="wrap">
			<h1><?php echo $this->menu_fields['page_title']; ?></h1>
			<?php $this->option_page_tabs(); ?>
			<form autocomplete="off" method="POST" action="options.php">
				<?php
					$this->get_options();
					settings_fields( $this->menu_fields['option_name'] );
					$this->setting_fields($this->filter_fields_by_tab());
					submit_button();
				?>
			</form>
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$(".colorpicker").wpColorPicker();
				$(".datepicker").datepicker({
				  	changeMonth: true,
			      	changeYear: true,
			      	showAnim: "slideDown"
			    });
			});
		</script>
		<?php
		echo ob_get_clean();
	}

    /**
     * Generate Option Page Tabs
     * @return string
     */
	private function option_page_tabs()
	{
		if (count($this->tabs) < 1) {
			return;
		}

		$current_tab = isset ($_GET['tab']) ? $_GET['tab'] : $this->tabs[0]['id'];
		$page = $this->menu_fields['menu_slug'];

	    echo '<h2 class="nav-tab-wrapper">';
	    foreach( $this->tabs as $tab ){
	        $class = ( $tab['id'] === $current_tab ) ? ' nav-tab-active' : '';
	        $page_url = esc_url( add_query_arg( array( 'page' => $page, 'tab' => $tab['id'] ), admin_url( $this->menu_fields['parent_slug'] ) ) );
	        echo sprintf('<a class="nav-tab%1$s" href="%2$s">%3$s</a>', $class, $page_url, $tab['title']);
	    }
	    echo '</h2>';
	}

	/**
	 * Filter settings fields by page tab
	 * @param  string $current_tab
	 * @return array
	 */
	public function filter_fields_by_tab( $current_tab = null ){

		if (count($this->tabs) < 1) {
			return $this->fields;
		}

		if ( ! $current_tab ) {
			$current_tab = isset ($_GET['tab']) ? $_GET['tab'] : $this->tabs[0]['id'];
		}

		$newarray = array();
		if(is_array($this->fields) && count($this->fields) >0 ) {
			foreach(array_keys($this->fields) as $key)
			{
				if (isset($this->fields[$key]['tab'])) {
					$temp[$key] = $this->fields[$key]['tab'];
					if ($temp[$key] == $current_tab){
						$newarray[$key] = $this->fields[$key];
					}
				} else {
					if ($current_tab == $this->tabs[0]['id']) {
						$newarray[$key] = $this->fields[$key];
					}
				}
			}
		}
		return $newarray;
    } 

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
	public function sanitize_callback(array $input)
	{
		$output_array 	= array();
		$fields 		= $this->fields;
		$options 		= (array) get_option( $this->menu_fields['option_name'] );

    	if (empty(array_filter($options))) {
    		$options = (array) $this->get_options();
    	}

    	if (count($this->tabs) > 0) {
    		parse_str( $_POST['_wp_http_referer'], $referrer );
    		$tab = isset( $referrer['tab'] ) ? $referrer['tab'] : $this->tabs[0]['id'];
    		$fields = $this->filter_fields_by_tab($tab);
    	}

		// Loop through each setting being saved and
    	// pass it through a sanitization filter
		foreach( $input as $key => $value ) {
			foreach ($fields as $field) {
    			if ($field['id'] == $key) {
    				$rule = empty($field['validate']) ? $field['type'] : $field['validate'];
    				$output_array[$key] = $this->validate($value, $rule);
    			}
    		}
		}

    	return array_merge($options, $output_array);
	}

	/**
	 * Get options parsed with default value
	 * @return array
	 */
	public function get_options()
	{
		$options_array = array();

		foreach ($this->fields as $value) {
        	$std_value = (isset($value['std'])) ? $value['std'] : '';
			$options_array[$value['id']] = $std_value;
		}

		$options = wp_parse_args(
			get_option( $this->menu_fields['option_name'] ),
			$options_array
		);

	   	return $this->options = $options;
	}

    /**
     * Validate the option's value
     *
     * @param  array   $input
     * @param  string   $validation_rule
     * @return mixed
     */
	private function validate($input, $validation_rule = 'text')
	{
    	switch ($validation_rule) {
    		case 'text':
    			return sanitize_text_field($input);
    			break;

    		case 'number':
    			return is_int( $input) ? trim($input) : intval( $input);
    			break;

    		case 'url':
    			return esc_url_raw(trim($input));
    			break;

    		case 'email':
    			return sanitize_email($input);
    			break;

    		case 'checkbox':
    			return ( $input == 1 ) ? 1 : 0;
    			break;

    		case 'multi_checkbox':
    			return $input;
    			break;

    		case 'radio':
    			return sanitize_text_field($input);
    			break;

    		case 'select':
    			return sanitize_text_field($input);
    			break;

    		case 'date':
    			return date('F d, Y', strtotime($input));
    			break;

    		case 'textarea':
    			return wp_filter_nohtml_kses($input);
    			break;

    		case 'inlinehtml':
    			return wp_filter_kses(force_balance_tags($input));
    			break;

    		case 'linebreaks':
    			return wp_strip_all_tags($input);
    			break;

    		case 'wp_editor':
    			return wp_kses_post($input);
    			break;

    		default:
    			return sanitize_text_field($input);
    			break;
		}
	}

	/**
	 * Settings fields
	 * @param  array $fields
	 * 
	 * @return string
	 */
	private function setting_fields($fields = null)
	{
		$fields = is_array($fields) ? $fields : $this->fields;

		$table  = "";
		$table .= "<table class='form-table'>";

		foreach( $fields as $field )
		{
			$name = sprintf('%s[%s]', $this->menu_fields['option_name'], $field['id']);
			$type = isset($field['type']) ? $field['type'] : 'text';
			$value = isset( $this->options[$field['id']] ) ? $this->options[$field['id']] : '';

			$table .= "<tr>";
			$table .= sprintf('<th scope="row"><label for="%1$s">%2$s</label></th>',$field['id'],$field['name']);
			$table .= "<td>";
			
			if (method_exists($this, $type )) {
				$table .= $this->$type($field, $name, $value);
			} else {
				$table .= $this->text($field, $name, $value);
			}

			if (!empty($field['desc'])) {
				$table .= sprintf('<p class="description">%s</p>', $field['desc']);
			}
			$table .= "</td>";
			$table .= "</tr>";
		}

		$table .= "</table>";
		echo $table;
	}

	public function section( $field, $name, $value )
	{
		return '';
	}

	/**
	 * text input field
	 * 
	 * @param  array $field
	 * @param  string $name
	 * @param  string $value
	 * @return string
	 */
	private function text($field, $name, $value)
	{
		return sprintf('<input type="text" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value, $field['id'], $name);
	}

	/**
	 * email input field
	 * 
	 * @param  array $field
	 * @param  string $name
	 * @param  string $value
	 * @return string
	 */
	private function email($field, $name, $value)
	{
		return sprintf('<input type="email" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value, $field['id'], $name);
	}

	/**
	 * password input field
	 * 
	 * @param  array $field
	 * @param  string $name
	 * @param  string $value
	 * @return string
	 */
	private function password($field, $name, $value)
	{
		return sprintf('<input type="password" class="regular-text" value="" id="%2$s" name="%3$s">', $value, $field['id'], $name);
	}

	/**
	 * number input field
	 * 
	 * @param  array $field
	 * @param  string $name
	 * @param  string $value
	 * @return string
	 */
	private function number($field, $name, $value)
	{
		return sprintf('<input type="number" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value, $field['id'], $name);
	}

	/**
	 * url input field
	 * 
	 * @param  array $field
	 * @param  string $name
	 * @param  string $value
	 * @return string
	 */
	private function url($field, $name, $value)
	{
		return sprintf('<input type="url" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value, $field['id'], $name);
	}

	/**
	 * color input field
	 * 
	 * @param  array $field
	 * @param  string $name
	 * @param  string $value
	 * @return string
	 */
	private function color($field, $name, $value)
	{
		$default_color = (isset($field['std'])) ? $field['std'] : "";
		return sprintf('<input type="text" class="colorpicker" value="%1$s" id="%2$s" name="%3$s" data-default-color="%4$s">', $value, $field['id'], $name, $default_color);
	}

	/**
	 * date input field
	 * 
	 * @param  array $field
	 * @param  string $name
	 * @param  string $value
	 * @return string
	 */
	private function date($field, $name, $value)
	{
		$value = date("F d, Y", strtotime($value));
        return sprintf('<input type="text" class="regular-text datepicker" value="%1$s" id="%2$s" name="%3$s">', $value, $field['id'], $name);
	}

	/**
	 * textarea input field
	 * 
	 * @param  array $field
	 * @param  string $name
	 * @param  string $value
	 * @return string
	 */
	private function textarea($field, $name, $value)
	{
        $rows = (isset($field['rows'])) ? $field['rows'] : 5;
		$cols = (isset($field['cols'])) ? $field['cols'] : 40;
		$placeholder = (isset($field['placeholder'])) ? sprintf('placeholder="%s"', esc_attr($field['placeholder'])) : '';
        return sprintf('<textarea id="%2$s" name="%3$s" rows="%4$s" cols="%5$s" %6$s>%1$s</textarea>', $value, $field['id'], $name, $rows, $cols, $placeholder);
	}

	/**
	 * checkbox input field
	 * 
	 * @param  array $field
	 * @param  string $name
	 * @param  string $value
	 * @return string
	 */
	private function checkbox($field, $name, $value)
	{
		$checked = ( 1 == $value ) ? 'checked="checked"' : '';
		$table  = sprintf( '<input type="hidden" name="%1$s" value="0">', $name );
        $table .= sprintf('<fieldset><legend class="screen-reader-text"><span>%1$s</span></legend><label for="%2$s"><input type="checkbox" value="1" id="%2$s" name="%4$s" %3$s>%1$s</label></fieldset>', $field['name'], $field['id'], $checked, $name);
        return $table;
	}

	/**
	 * multi checkbox input field
	 * 
	 * @param  array $field
	 * @param  string $name
	 * @param  string $value
	 * @return string
	 */
	private function multi_checkbox($field, $name, $value)
	{
		$table = "<fieldset>";
        $multicheck_name = $name. "[]";

		$table .= sprintf( '<input type="hidden" name="%1$s" value="0">', $multicheck_name );
        foreach ($field['options'] as $key => $label) {
			$multichecked = (in_array($key, $this->options[$field['id']])) ? 'checked="checked"' : '';
            $table .= sprintf('<label for="%1$s"><input type="checkbox" value="%1$s" id="%1$s" name="%2$s" %3$s>%4$s</label><br>', $key, $multicheck_name, $multichecked, $label);
		}
        $table .= "</fieldset>";
        return $table;
	}

	/**
	 * radio input field
	 * 
	 * @param  array $field
	 * @param  string $name
	 * @param  string $value
	 * @return string
	 */
	private function radio($field, $name, $value)
	{
		$table  = sprintf('<fieldset><legend class="screen-reader-text"><span>%1$s</span></legend><p>', $field['name']);

		foreach ($field['options'] as $key => $radio_label) {

			$radio_checked = ( $value == $key ) ? 'checked="checked"' : '';
            $table .= sprintf('<label><input type="radio" %1$s value="%2$s" name="%3$s">%4$s</label><br>', $radio_checked, $key, $name, $radio_label);
		}
		$table .= "</p></fieldset>";
        return $table;
	}

	/**
	 * select input field
	 * 
	 * @param  array $field
	 * @param  string $name
	 * @param  string $value
	 * @return string
	 */
	private function select($field, $name, $value)
	{
		$table = sprintf('<select id="%1$s" name="%2$s" class="regular-text">', $field['id'], $name);
		foreach ($field['options'] as $key => $select_label) {
			$selected = ( $value == $key ) ? 'selected="selected"' : '';
            $table .= sprintf('<option value="%1$s" %2$s>%3$s</option>', $key, $selected, $select_label);
		}
		$table .= "</select>";
        return $table;
	}

	public function image_sizes( $field, $name, $value )
	{

		global $_wp_additional_image_sizes;

		$sizes = array();

		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {

				$width 		= get_option( "{$_size}_size_w" );
				$height 	= get_option( "{$_size}_size_h" );
				$crop 		= (bool) get_option( "{$_size}_crop" ) ? 'hard' : 'soft';

				$sizes[$_size]   = "{$_size} - {$width}x{$height}";

			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

				$width 		= $_wp_additional_image_sizes[ $_size ]['width'];
				$height 	= $_wp_additional_image_sizes[ $_size ]['height'];
				$crop 		= $_wp_additional_image_sizes[ $_size ]['crop'] ? 'hard' : 'soft';

				$sizes[$_size]   = "{$_size} - {$width}x{$height}";
			}
		}

		$sizes = array_merge($sizes, array('full' => 'original uploaded image'));

		$table = sprintf('<select name="%2$s" id="%1$s" class="regular-text select2">', $field['id'], $name );
        foreach( $sizes as $key => $option ){
            $selected = ( $value == $key ) ? ' selected="selected"' : '';
            $table .= sprintf('<option value="%1$s" %3$s>%2$s</option>',$key, $option, $selected);
        }
        $table .= '</select>';

		return $table;
	}

	/**
	 * wp_editor input field
	 * 
	 * @param  array $field
	 * @param  string $name
	 * @param  string $value
	 * @return string
	 */
	private function wp_editor($field, $name, $value)
	{
		ob_start();
		echo "<div class='sp-wp-editor-container'>";
        wp_editor( $value, $field['id'], array(
            'textarea_name' => $name,
            'tinymce'       => false,
            'media_buttons' => false,
            'textarea_rows' => isset($field['rows']) ? $field['rows'] : 6,
            'quicktags'     => array("buttons"=>"strong,em,link,img,ul,li,ol"),
        ));
        echo "</div>";
        return ob_get_clean();
	}

	/**
	 * file input field
	 * 
	 * @param  array $field
	 * @param  string $name
	 * @param  string $value
	 * @return string
	 */
	private function file($field, $name, $value)
	{
        $multiple   = ( isset( $field['multiple'] ) ) ? true : false;
        $btn_browse = ( isset( $field['btn_browse'] ) ) ? $field['btn_browse'] : 'Browse';
        $btn_insert = ( isset( $field['btn_insert'] ) ) ? $field['btn_insert'] : 'Insert';
        $btn_id 	= $field['id'].'_button';
        ob_start();?>

        <input type="text" name="<?php echo $name;?>" id="<?php echo $field['id'];?>" value="<?php echo $value;?>" class="regular-text">
        <input type="button" class="button" id="<?php echo $btn_id; ?>" value="<?php echo $btn_browse; ?>">
		<script>
			jQuery(function($){
				var frame,
					isMultiple = "<?php echo $multiple; ?>";

				$('#<?php echo $btn_id; ?>').on('click', function(e) {
					e.preventDefault();

					var options = {
						state: 'insert',
						frame: 'post',
						multiple: isMultiple
					};

					frame = wp.media(options).open();

					frame.menu.get('view').unset('gallery');
					frame.menu.get('view').unset('featured-image');

					frame.toolbar.get('view').set({
						insert: {
							style: 'primary',
							text: '<?php echo $btn_insert; ?>',

							click: function() {
								var models = frame.state().get('selection'),
									attachment_id = models.first().attributes.id,
									files = [];

								if( isMultiple ) {
									models.map (function( attachment ) {
										attachment = attachment.toJSON();
										files.push(attachment.id);
										attachment_id = files;
									});
								}

								$('#<?php echo $field['id']; ?>').val( attachment_id );

								frame.close();
							}
						}
					});
				});
			});
		</script>
        <?php return ob_get_clean();
	}
}
endif;
