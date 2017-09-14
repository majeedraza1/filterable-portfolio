<?php
if( ! class_exists('Filterable_Portfolio_Shortcode') ):

class Filterable_Portfolio_Shortcode
{
	private $plugin_path;
	private $options;

	public function __construct( $plugin_path, $options )
	{
		$this->plugin_path 	= $plugin_path;
		$this->options 		= $options;

		add_shortcode( 'filterable_portfolio', array( $this, 'shortcode' ) );
	}

	/**
	 * Filterable Portfolio shortcode.
	 * 
	 * @param  array $atts
	 * @param  null $content
	 */
	public function shortcode( $atts, $content = null )
	{
	    $portfolios = $this->get_portfolios();
	    $terms 		= get_terms("portfolio_cat");
	    $image_size = $this->options['image_size'];
	    $grid 		= sprintf('grid %1$s %2$s %3$s %4$s', $this->options['columns_phone'], $this->options['columns_tablet'], $this->options['columns_desktop'], $this->options['columns']);

		ob_start();
	    require $this->plugin_path . '/templates/filterable_portfolio.php';
	    $html = ob_get_contents();
	    ob_end_clean();

	    return apply_filters( 'filterable_portfolio', $html, $portfolios, $terms );
	}

	public function get_portfolios()
	{
		$args = array(
            'post_type' 		=> 'portfolio',
            'posts_per_page' 	=> -1,
            'post_status'    	=> 'publish',
        );

        $portfolios = get_posts( $args );

        $portfolios = array_map(function( $portfolio ){

        	$thumb_id = intval(get_post_thumbnail_id( $portfolio->ID ));

        	if ( ! $thumb_id ) {
        		return array();
        	}

        	$terms 	= get_the_terms( $portfolio->ID, 'portfolio_cat' );
        	if ( $terms && ! is_wp_error( $terms ) ){
        		$terms = array_map(function( $term ){
        			return $term->slug;
        		}, $terms);
        	}

        	$terms = $terms ? json_encode($terms) : json_encode(array());

        	return array(
        		'id' 		=> $portfolio->ID,
        		'title' 	=> esc_attr( $portfolio->post_title ),
				'permalink' => esc_url( get_permalink( $portfolio->ID ) ),
				'thumb_id' 	=> intval(get_post_thumbnail_id( $portfolio->ID )),
        		'modified' 	=> $portfolio->post_modified,
        		'created' 	=> $portfolio->post_date,
				'excerpt' 	=> wp_trim_words( strip_tags($portfolio->post_content), '19', ' ...' ),
				'terms' 	=> $terms,
        	);
        }, $portfolios);

        $portfolios = array_filter($portfolios);
        return json_decode(json_encode($portfolios), false);
	}

	public function get_terms_html()
	{
		$html = '';
		$terms = get_terms("portfolio_cat");
		if ( $terms && ! is_wp_error( $terms ) && count($terms) > 0 ){
			$html .= '<div id="filter" class="portfolio-terms"><div class="filter-options">';
			$html .= sprintf('<button class="active" data-group="all">%s</button>', __('All', 'filterable-portfolio'));
			foreach ( $terms as $term ){
				$html .= sprintf('<button data-group="%1$s">%2$s</button>', esc_attr( $term->slug ), esc_attr( $term->name ));
			}
			$html .= '</div></div>';
		}
		return $html;
	}
}

endif;