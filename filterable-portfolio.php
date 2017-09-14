<?php
/**
 * Plugin Name:       Filterable Portfolio
 * Plugin URI:        https://wordpress.org/plugins/filterable-portfolio/
 * Description:       A WordPress plugin to display portfolio images with filtering.
 * Version:           1.2.2
 * Author:            Sayful Islam
 * Author URI:        http://sayfulit.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       filterable-portfolio
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( ! class_exists('Filterable_Portfolio')):

class Filterable_Portfolio
{
	private $plugin_name = 'filterable-portfolio';
	private $version = '1.2.1';
	private $plugin_path;
	private $plugin_url;

	public function __construct()
	{
		$this->includes();

		register_activation_hook( __FILE__, array( $this, 'activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'activation' ) );
		add_action( 'after_setup_theme', array( $this, 'add_image_size') );
		add_action( 'init', array( 'Filterable_Portfolio_Admin', 'post_type' ) );
		add_action( 'init', array( 'Filterable_Portfolio_Admin', 'taxonomy' ) );

		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
	}

	/**
	 * Flush the rewrite rules on activation
	 */
	public function activation()
	{
		Filterable_Portfolio_Admin::post_type();
		Filterable_Portfolio_Admin::taxonomy();
		flush_rewrite_rules();
	}

	/**
	 * Add custom image size for portfolio
	 */
	public function add_image_size()
	{
		add_image_size( 'filterable-portfolio', 370, 370, true );
	}

	/**
	 * Include admin and front facing files
	 * @return void
	 */
	private function includes()
	{
		if ( is_admin() ) {
			$this->admin_includes();
		}

		if ( ! is_admin() ) {
			$this->frontend_includes();
		}
		
		include_once $this->plugin_path() . '/includes/Filterable_Portfolio_Admin.php';
		include_once $this->plugin_path() . '/includes/Filterable_Portfolio_Scripts.php';
		include_once $this->plugin_path() . '/includes/Filterable_Portfolio_Widget.php';
		include_once $this->plugin_path() . '/includes/Filterable_Portfolio_Shapla_Theme.php';
		new Filterable_Portfolio_Scripts($this->plugin_url(), $this->get_option());
	}

	/**
	 * Include admin files
	 * 
	 * @return void
	 */
	private function admin_includes()
	{
		include_once $this->plugin_path() . '/libraries/class-shaplatools-settings-api.php';
		include_once $this->plugin_path() . '/libraries/class-shaplatools-meta-box.php';
		include_once $this->plugin_path() . '/includes/Filterable_Portfolio_Metabox.php';
		include_once $this->plugin_path() . '/includes/settings.php';
	}

	/**
	 * Include front facing files
	 * 
	 * @return void
	 */
	private function frontend_includes()
	{
		include_once $this->plugin_path() . '/includes/Filterable_Portfolio_Shortcode.php';
		include_once $this->plugin_path() . '/includes/Filterable_Portfolio_Single_Post.php';

		new Filterable_Portfolio_Single_Post( $this->plugin_path(), $this->get_option() );
		new Filterable_Portfolio_Shortcode( $this->plugin_path(), $this->get_option() );
	}

	/**
	 * Get portfolio options
	 * Merge with default values
	 * 
	 * @return array
	 */
	public function get_option()
	{
		$default = array(
			// General Settings
			'portfolio_theme' 			=> 'two',
			'image_size' 				=> 'filterable-portfolio',
			'button_color' 				=> '#4cc1be',
			'order' 					=> 'DESC',
			'orderby' 					=> 'ID',
			'posts_per_page' 			=> -1,
			'portfolio_filter_script' 	=> 'isotope',
			'all_categories_text' 		=> __('All', 'filterable-portfolio'),
			'details_button_text' 		=> __('Details', 'filterable-portfolio'),
			// Responsive Settings
			'columns' 					=> 'l4',
			'columns_desktop' 			=> 'm4',
			'columns_tablet' 			=> 's6',
			'columns_phone' 			=> 'xs12',
			// Single Portfolio Settings
			'show_related_projects' 	=> 1,
			'related_projects_number' 	=> 3,
			'related_projects_text' 	=> __('Related Projects', 'filterable-portfolio'),
			'project_description_text' 	=> __('Project Description', 'filterable-portfolio'),
			'project_details_text' 		=> __('Project Details', 'filterable-portfolio'),
			'project_skills_text' 		=> __('Skills Needed:', 'filterable-portfolio'),
			'project_categories_text' 	=> __('Categories:', 'filterable-portfolio'),
			'project_url_text' 			=> __('Project URL:', 'filterable-portfolio'),
			'project_date_text' 		=> __('Project Date:', 'filterable-portfolio'),
			'project_client_text' 		=> __('Client:', 'filterable-portfolio'),
			// Advanced Settings
			'custom_css' 				=> '',
		);

		return wp_parse_args( get_option( 'filterable_portfolio' ), $default );
	}

	/**
	 * Add custom footer text on plugins page.
	 *
	 * @param string $text
	 */
	public function admin_footer_text( $text )
	{
		global $post_type, $hook_suffix;

		$footer_text = sprintf(__('If you like %1$s Filterable Portfolio %2$s please leave us a %3$s rating. A huge thanks in advance!', 'filterable-portfolio' ), '<strong>', '</strong>', '<a href="https://wordpress.org/support/view/plugin-reviews/filterable-portfolio?filter=5#postform" target="_blank" data-rated="Thanks :)">&starf;&starf;&starf;&starf;&starf;</a>');

		if ($post_type == 'portfolio' || $hook_suffix == 'portfolio_page_fp-settings') {
			return $footer_text;
		}

		return $text;
	}

	/**
	 * Add custom links on plugins page.
	 *
	 * @param mixed $links
	 */
	public function action_links( $links )
	{
		$plugin_links = array(
			'<a href="' . admin_url( 'edit.php?post_type=portfolio&page=fp-settings' ) . '">' . __( 'Settings', 'filterable-portfolio' ) . '</a>'
		);

		return array_merge( $plugin_links, $links );
	}

	/**
	 * Plugin path.
	 *
	 * @return string Plugin path
	 */
	private function plugin_path() {
		if ( $this->plugin_path ) return $this->plugin_path;

		return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Plugin url.
	 *
	 * @return string Plugin url
	 */
	private function plugin_url() {
		if ( $this->plugin_url ) return $this->plugin_url;

		return $this->plugin_url = untrailingslashit( plugin_dir_url( __FILE__ ) );
	}
}

endif;

new Filterable_Portfolio;
