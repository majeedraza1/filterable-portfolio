<?php
/**
 * Plugin Name:       Filterable Portfolio
 * Plugin URI:        https://wordpress.org/plugins/filterable-portfolio/
 * Description:       A WordPress plugin to display portfolio images with filtering.
 * Version:           1.5.0
 * Author:            Sayful Islam
 * Author URI:        https://sayfulislam.com
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       filterable-portfolio
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Filterable_Portfolio' ) ) {
	/**
	 * Main Filterable_Portfolio Class.
	 *
	 * @class Filterable_Portfolio
	 */
	final class Filterable_Portfolio {

		/**
		 * Plugin unique slug
		 *
		 * @var string
		 */
		private $plugin_name = 'filterable-portfolio';

		/**
		 * Holds various class instances
		 *
		 * @var array
		 */
		private $container = array();

		/**
		 * Current version number
		 *
		 * @var string
		 */
		private $version = '1.5.0';

		/**
		 * Instance of this class
		 *
		 * @var self
		 */
		protected static $instance;

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @return Filterable_Portfolio
		 * @since 1.2.3
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();

				// Define plugin constants
				self::$instance->define_constants();

				// Includes plugin files
				self::$instance->include_files();

				// initialize plugin classes
				self::$instance->init_classes();

				register_activation_hook( __FILE__, array( self::$instance, 'activation' ) );
				register_deactivation_hook( __FILE__, array( self::$instance, 'deactivation' ) );

				add_action( 'after_setup_theme', array( self::$instance, 'add_image_size' ) );

				add_filter( 'admin_footer_text', array( self::$instance, 'admin_footer_text' ) );
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),
					array( self::$instance, 'action_links' ) );

				do_action( 'filterable_portfolio_init' );
			}

			return self::$instance;
		}

		/**
		 * Magic getter to bypass referencing plugin.
		 *
		 * @param string $property
		 *
		 * @return mixed
		 */
		public function __get( $property ) {
			if ( array_key_exists( $property, $this->container ) ) {
				return $this->container[ $property ];
			}

			return $this->{$property};
		}

		/**
		 * Define plugin constants
		 */
		public function define_constants() {
			define( 'FILTERABLE_PORTFOLIO_VERSION', $this->version );
			define( 'FILTERABLE_PORTFOLIO_FILE', __FILE__ );
			define( 'FILTERABLE_PORTFOLIO_PATH', dirname( FILTERABLE_PORTFOLIO_FILE ) );
			define( 'FILTERABLE_PORTFOLIO_INCLUDES', FILTERABLE_PORTFOLIO_PATH . '/includes' );
			define( 'FILTERABLE_PORTFOLIO_TEMPLATES', FILTERABLE_PORTFOLIO_PATH . '/templates' );
			define( 'FILTERABLE_PORTFOLIO_URL', plugins_url( '', FILTERABLE_PORTFOLIO_FILE ) );
			define( 'FILTERABLE_PORTFOLIO_ASSETS', FILTERABLE_PORTFOLIO_URL . '/assets' );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param string $name
		 * @param string|bool $value
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
		 * Includes files
		 */
		private function include_files() {
			spl_autoload_register( function ( $class ) {

				// If class already exists, not need to include it
				if ( class_exists( $class ) || false === strpos( $class, 'Filterable_Portfolio_' ) ) {
					return;
				}

				// Include our classes
				$file_name = 'class-' . strtolower( str_replace( '_', '-', $class ) ) . '.php';

				$class_path = FILTERABLE_PORTFOLIO_INCLUDES . '/' . $file_name;
				if ( file_exists( $class_path ) ) {
					require_once $class_path;
				}
			} );
		}

		/**
		 * Include admin and front facing files
		 * @return void
		 */
		private function init_classes() {
			$this->container['admin']   = Filterable_Portfolio_Admin::init();
			$this->container['scripts'] = Filterable_Portfolio_Scripts::init();

			if ( $this->is_request( 'admin' ) ) {
				$this->container['setting'] = Filterable_Portfolio_Setting::init();
				$this->container['metabox'] = Filterable_Portfolio_Metabox::init();
			}

			if ( $this->is_request( 'frontend' ) ) {
				$this->container['portfolio'] = Filterable_Portfolio_Single_Post::init();
				$this->container['shortcode'] = Filterable_Portfolio_Shortcode::init();
				$this->container['shapla']    = Filterable_Portfolio_Shapla_Theme::init();
				$this->container['rest']      = Filterable_Portfolio_REST_Controller::init();
			}

			add_action( 'widgets_init', array( 'Filterable_Portfolio_Widget', 'register' ) );
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


		/**
		 * What type of request is this?
		 *
		 * @param string $type admin, ajax, cron or frontend.
		 *
		 * @return bool
		 */
		private function is_request( $type ) {
			switch ( $type ) {
				case 'admin':
					return is_admin();
				case 'ajax':
					return defined( 'DOING_AJAX' );
				case 'cron':
					return defined( 'DOING_CRON' );
				case 'frontend':
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}

			return false;
		}
	}
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
Filterable_Portfolio::instance();
