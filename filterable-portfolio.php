<?php
/**
 * Plugin Name:       Filterable Portfolio
 * Plugin URI:        https://wordpress.org/plugins/filterable-portfolio/
 * Description:       A WordPress plugin to display portfolio images with filtering.
 * Version:           1.3.1
 * Author:            Sayful Islam
 * Author URI:        https://sayfulislam.com
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       filterable-portfolio
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Filterable_Portfolio' ) ):

	class Filterable_Portfolio {
		private $plugin_name = 'filterable-portfolio';
		private $version = '1.3.1';
		private $options;
		protected static $instance;

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @since 1.2.3
		 * @return Filterable_Portfolio
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			$this->define_constants();
			$this->includes();

			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

			add_action( 'after_setup_theme', array( $this, 'add_image_size' ) );

			add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );

			do_action( 'filterable_portfolio_init' );
		}

		public function define_constants() {
			define( 'FILTERABLE_PORTFOLIO_VERSION', $this->version );
			define( 'FILTERABLE_PORTFOLIO_FILE', __FILE__ );
			define( 'FILTERABLE_PORTFOLIO_PATH', dirname( FILTERABLE_PORTFOLIO_FILE ) );
			define( 'FILTERABLE_PORTFOLIO_INCLUDES', FILTERABLE_PORTFOLIO_PATH . '/includes' );
			define( 'FILTERABLE_PORTFOLIO_TEMPLATES', FILTERABLE_PORTFOLIO_PATH . '/templates' );
			define( 'FILTERABLE_PORTFOLIO_WIDGETS', FILTERABLE_PORTFOLIO_PATH . '/widgets' );
			define( 'FILTERABLE_PORTFOLIO_LIBRARIES', FILTERABLE_PORTFOLIO_PATH . '/libraries' );
			define( 'FILTERABLE_PORTFOLIO_URL', plugins_url( '', FILTERABLE_PORTFOLIO_FILE ) );
			define( 'FILTERABLE_PORTFOLIO_ASSETS', FILTERABLE_PORTFOLIO_URL . '/assets' );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param  string $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * To be run when the plugin is activated
		 * @return void
		 */
		public function activation() {
			do_action( 'filterable_portfolio_activation' );
			flush_rewrite_rules();
		}

		/**
		 * To be run when the plugin is deactivated
		 * @return void
		 */
		public function deactivation() {
			do_action( 'filterable_portfolio_deactivation' );
			flush_rewrite_rules();
		}

		/**
		 * Add custom image size for portfolio
		 */
		public function add_image_size() {
			add_image_size( 'filterable-portfolio', 370, 370, true );
		}

		/**
		 * Include admin and front facing files
		 * @return void
		 */
		private function includes() {
			if ( is_admin() ) {
				include_once FILTERABLE_PORTFOLIO_LIBRARIES . '/Filterable_Portfolio_Setting_API.php';
				include_once FILTERABLE_PORTFOLIO_LIBRARIES . '/Filterable_Portfolio_MetaBox_API.php';
				include_once FILTERABLE_PORTFOLIO_INCLUDES . '/Filterable_Portfolio_Metabox.php';
				include_once FILTERABLE_PORTFOLIO_INCLUDES . '/Filterable_Portfolio_Setting.php';
			}

			include_once FILTERABLE_PORTFOLIO_INCLUDES . '/Filterable_Portfolio_Shortcode.php';
			include_once FILTERABLE_PORTFOLIO_INCLUDES . '/Filterable_Portfolio_Single_Post.php';
			include_once FILTERABLE_PORTFOLIO_INCLUDES . '/Filterable_Portfolio_Admin.php';
			include_once FILTERABLE_PORTFOLIO_INCLUDES . '/Filterable_Portfolio_Scripts.php';
			include_once FILTERABLE_PORTFOLIO_INCLUDES . '/Filterable_Portfolio_Widget.php';
			include_once FILTERABLE_PORTFOLIO_INCLUDES . '/Filterable_Portfolio_Shapla_Theme.php';

			new Filterable_Portfolio_Scripts( $this->get_option() );
			new Filterable_Portfolio_Single_Post( $this->get_option() );
			new Filterable_Portfolio_Shortcode( $this->get_option() );
		}

		/**
		 * Get portfolio options
		 * Merge with default values
		 *
		 * @return array
		 */
		public function get_option() {
			$default = array(
				// General Settings
				'portfolio_theme'          => 'two',
				'image_size'               => 'filterable-portfolio',
				'button_color'             => '#4cc1be',
				'order'                    => 'DESC',
				'orderby'                  => 'ID',
				'posts_per_page'           => - 1,
				'portfolio_filter_script'  => 'isotope',
				'all_categories_text'      => __( 'All', 'filterable-portfolio' ),
				'details_button_text'      => __( 'Details', 'filterable-portfolio' ),
				// Responsive Settings
				'columns'                  => 'l4',
				'columns_desktop'          => 'm4',
				'columns_tablet'           => 's6',
				'columns_phone'            => 'xs12',
				// Single Portfolio Settings
				'show_related_projects'    => 1,
				'related_projects_number'  => 3,
				'related_projects_text'    => __( 'Related Projects', 'filterable-portfolio' ),
				'project_description_text' => __( 'Project Description', 'filterable-portfolio' ),
				'project_details_text'     => __( 'Project Details', 'filterable-portfolio' ),
				'project_skills_text'      => __( 'Skills Needed:', 'filterable-portfolio' ),
				'project_categories_text'  => __( 'Categories:', 'filterable-portfolio' ),
				'project_url_text'         => __( 'Project URL:', 'filterable-portfolio' ),
				'project_date_text'        => __( 'Project Date:', 'filterable-portfolio' ),
				'project_client_text'      => __( 'Client:', 'filterable-portfolio' ),
			);

			$this->options = wp_parse_args( get_option( 'filterable_portfolio' ), $default );

			return $this->options;
		}

		/**
		 * Add custom footer text on plugins page.
		 *
		 * @param string $text
		 *
		 * @return string
		 */
		public function admin_footer_text( $text ) {
			global $post_type, $hook_suffix;

			$footer_text = sprintf( __( 'If you like %1$s Filterable Portfolio %2$s please leave us a %3$s rating. A huge thanks in advance!',
				'filterable-portfolio' ), '<strong>', '</strong>',
				'<a href="https://wordpress.org/support/view/plugin-reviews/filterable-portfolio?filter=5#postform" target="_blank" data-rated="Thanks :)">&starf;&starf;&starf;&starf;&starf;</a>' );

			if ( $post_type == 'portfolio' || $hook_suffix == 'portfolio_page_fp-settings' ) {
				return $footer_text;
			}

			return $text;
		}

		/**
		 * Add custom links on plugins page.
		 *
		 * @param mixed $links
		 *
		 * @return array
		 */
		public function action_links( $links ) {
			$plugin_links = array(
				'<a href="' . admin_url( 'edit.php?post_type=portfolio&page=fp-settings' ) . '">' . __( 'Settings',
					'filterable-portfolio' ) . '</a>'
			);

			return array_merge( $plugin_links, $links );
		}
	}

endif;

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
Filterable_Portfolio::instance();
