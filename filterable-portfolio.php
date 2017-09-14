<?php
/**
 * Plugin Name:       Filterable Portfolio
 * Plugin URI:        https://wordpress.org/plugins/filterable-portfolio/
 * Description:       A WordPress plugin to display portfolio images with filtering.
 * Version:           1.1.0
 * Author:            Sayful Islam
 * Author URI:        http://sayfulit.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       filterable-portfolio
 * Domain Path:       /languages
 */

if( ! class_exists('Filterable_Portfolio')):

class Filterable_Portfolio
{
	private $plugin_name = 'filterable-portfolio';
	private $version = '1.1.0';
	private $plugin_path;
	private $plugin_url;

	public function __construct()
	{
		add_action( 'after_setup_theme', array( $this, 'add_image_size') );

		$this->includes();
	}

	public function add_image_size()
	{
		add_image_size( 'filterable-portfolio', 370, 370, true );
	}

	public function includes()
	{
		if ( is_admin() ) {
			$this->admin_includes();
		}

		if ( ! is_admin() ) {
			$this->frontend_includes();
		}
		
		include_once $this->plugin_path() . '/includes/Filterable_Portfolio_Admin.php';
		include_once $this->plugin_path() . '/includes/Filterable_Portfolio_Scripts.php';
		new Filterable_Portfolio_Scripts($this->plugin_url(), $this->get_option());
		new Filterable_Portfolio_Admin();
	}

	public function admin_includes()
	{
		include_once $this->plugin_path() . '/libraries/class-shaplatools-settings-api.php';
		include_once $this->plugin_path() . '/libraries/class-shaplatools-meta-box.php';
		include_once $this->plugin_path() . '/includes/Filterable_Portfolio_Metabox.php';
		include_once $this->plugin_path() . '/includes/settings.php';
	}

	public function frontend_includes()
	{
		include_once $this->plugin_path() . '/includes/Filterable_Portfolio_Shortcode.php';
		include_once $this->plugin_path() . '/includes/Filterable_Portfolio_Single_Post.php';

		new Filterable_Portfolio_Single_Post( $this->plugin_path(), $this->get_option() );
		new Filterable_Portfolio_Shortcode( $this->plugin_path(), $this->get_option() );
	}

	public function get_option()
	{
		$default = array(
			'columns' 					=> 'l4',
			'columns_desktop' 			=> 'm4',
			'columns_tablet' 			=> 's6',
			'columns_phone' 			=> 'xs12',
			'portfolio_theme' 			=> 'two',
			'image_size' 				=> 'filterable-portfolio',
			'show_related_projects' 	=> 1,
			'related_projects_number' 	=> 3,
			'related_projects_text' 	=> __('Related Projects', 'filterable-portfolio'),
			'custom_css' 				=> '',
		);

		return get_option( 'filterable_portfolio', $default );
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

/**
 * Flush the rewrite rules on activation
 */
function filterable_portfolio_activation() {
	include_once plugin_dir_url( __FILE__ ) . 'includes/Filterable_Portfolio_Admin.php';

	Filterable_Portfolio_Admin::post_type();
	Filterable_Portfolio_Admin::taxonomy();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'filterable_portfolio_activation' );
register_deactivation_hook( __FILE__, 'filterable_portfolio_activation' );