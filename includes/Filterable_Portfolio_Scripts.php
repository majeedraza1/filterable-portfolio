<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( ! class_exists('Filterable_Portfolio_Scripts') ):

class Filterable_Portfolio_Scripts
{
	private $plugin_name = 'filterable-portfolio';
	private $version = '1.1.0';
	private $plugin_url;
	private $options;

	public function __construct( $plugin_url, $options )
	{
		$this->plugin_url 	= $plugin_url;
		$this->options 		= $options;
		
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts') );
		
		add_action( 'wp_head', array( $this, 'inline_style'), 10 );
		add_action( 'wp_footer', array( $this, 'inline_script'), 30 );
	}

	public function admin_scripts( $hook )
	{
		global $post;
    	wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_media();

		wp_enqueue_style( $this->plugin_name, $this->plugin_url . '/assets/css/admin-style.css' , array(), $this->version, 'all' );
		wp_enqueue_script( $this->plugin_name, $this->plugin_url . '/assets/js/admin-script.js', array( 'jquery', 'wp-color-picker', 'jquery-ui-datepicker' ), $this->version, true);

		wp_localize_script( $this->plugin_name, 'FilterablePortfolio', array(
			'ajaxurl' 			=> admin_url( 'admin-ajax.php' ),
            'nonce' 			=> wp_create_nonce( 'fp_ajax_nonce' ),
            'post_id' 			=> $post ? $post->ID: '',
			'image_ids' 		=> $post ? get_post_meta( $post->ID, '_project_images', true ) : '',
			'create_btn_text' 	=> __('Create Gallery', 'filterable-portfolio'),
			'edit_btn_text' 	=> __('Edit Gallery', 'filterable-portfolio'),
			'progress_btn_text' => __('Saving...', 'filterable-portfolio'),
			'save_btn_text' 	=> __('Save Gallery', 'filterable-portfolio'),
		));
	}

	public function frontend_scripts()
	{
		wp_register_script( 'isotope', $this->plugin_url . '/assets/js/isotope.pkgd.min.js', array(), '3.0.3', true );
		wp_register_script( 'isotope-fp-custom', $this->plugin_url . '/assets/js/isotope-custom.js', array( 'isotope' ), $this->version, true );
		wp_register_script( 'shuffle', $this->plugin_url . '/assets/js/shuffle.min.js', array(), '4.0.2', true );
		wp_register_script( 'shuffle-fp-custom', $this->plugin_url . '/assets/js/shuffle-custom.js', array( 'shuffle' ), $this->version, true );
	}

	public function inline_style()
	{
		global $post;
		$theme 		= $this->options['portfolio_theme'];
		$btn_bg 	= ! empty($this->options['button_color']) ? $this->options['button_color'] : '#4cc1be';
		$slideArrow = $this->plugin_url. '/assets/img/themes.gif';
		
		$grids 		= file_get_contents($this->plugin_url . '/assets/css/grids.css');
		$terms 		= file_get_contents($this->plugin_url . '/assets/css/terms.css');
		$themeOne 	= file_get_contents($this->plugin_url . '/assets/css/theme-one.css');
		$themeTwo 	= file_get_contents($this->plugin_url . '/assets/css/theme-two.css');
		$slides 	= file_get_contents($this->plugin_url . '/assets/css/slides.css');
		$meta 		= file_get_contents($this->plugin_url . '/assets/css/project-meta.css');
		
		?><style type="text/css" id="filterable-portfolio-css">
		<?php
			if ($this->should_load_script( $post ) || is_singular( 'portfolio' ) || is_tax( 'portfolio_cat' ) || is_tax( 'portfolio_skill' ) ) {
				echo $grids;
				echo str_replace('#4cc1be', $btn_bg, $terms);

				if ( $theme == 'one' ) {
					echo str_replace('#4cc1be', $btn_bg, $themeOne);
				} else {
					echo str_replace('#4cc1be', $btn_bg, $themeTwo);
				}

				echo wp_strip_all_tags($this->options['custom_css']);
			}

			if ( is_singular( 'portfolio' ) ) {
				echo $meta;
				echo str_replace('../img/themes.gif', $slideArrow, $slides);
			}
		?>
		</style>
		<?php
	}

	public function inline_script()
	{
		if ( is_singular( 'portfolio' ) ) {
			wp_enqueue_script( 'jquery' );
			$responsiveslides = file_get_contents( $this->plugin_url . '/assets/js/responsiveslides.min.js');

			?><script type="text/javascript">
				<?php echo $responsiveslides; ?>
				
				jQuery(document).ready(function( $ ) {
					$("#fp_slides").responsiveSlides({
						auto: true,
						pager: true,
						nav: true,
						speed: 500,
						namespace: "fp_slides"
					});
				});
			</script>
			<?php
		}
	}

	public function should_load_script( $post )
	{
		global $post;
		$load_scripts = is_active_widget( false, false, 'widget_filterable_portfolio', true ) || ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'filterable_portfolio' ) );

		return apply_filters( 'filterable_portfolio_load_scripts', $load_scripts );
	}
}

endif;
