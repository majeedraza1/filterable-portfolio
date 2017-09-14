<?php

function filterableportfolio_controls() {

    $terms = get_terms("portfolio_cat");    //To get custom taxonomy catagory name
    $count = count($terms);

    $controls ='<div class="controls">';
    $controls .='<ul id="portfolio-filter">';

    if ($count > 0) {

        $controls .='<li><a href="#all">All</a></li>';

        foreach ( $terms as $term ) {

            $termname   = strtolower($term->name);
            $termname   = str_replace(' ', '-', $termname);
            $controls   .='<li><a href="#'.$termname.'" rel="'.$termname.'">'.$term->name.'</a></li>';

        }

    }

    $controls .='</ul>';
    $controls .='</div>';

    return $controls;
}


function filterableportfolio_contents( $thumbnail ) {

    global $post;

    $contents ='<div id="portfolio-list" class="myportfolio">';

        $loop = new WP_Query(array('post_type' => 'portfolio', 'posts_per_page' => -1));

        if($loop):

            while ( $loop->have_posts() ) :

                $loop->the_post();

                $terms = get_the_terms( $post->ID, 'portfolio_cat' );   //To get custom taxonomy catagory name
                                     
                if ( $terms && ! is_wp_error( $terms ) ) :
                        
                    $links = array();
 
                    foreach ( $terms as $term ){
                        $links[] = $term->name;
                    }
                    $links = str_replace(' ', '-', $links);
                    $tax = join( " ", $links );    
                else :
                    $tax = '';
                endif;

                $portfolio_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'portfolio-thumb' );
                $portfolio_full = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );

                $contents .= '<div class="portfolio_thumb_'.$thumbnail.' '.strtolower($tax).'">';

                $contents .= '<div class="portfolio_single">';
                $contents .= '<div class="portfolio_image">';
                $contents .= '<img src="'.$portfolio_thumbnail[0].'" alt="">';
                $contents .= '<div class="mask">';
                $contents .= '<a href="'.$portfolio_full[0].'" rel="prettyPhoto[gallery]"><i title="View original image" class="picture_icon fa fa-search"></i></a>';

                if (  get_post_meta( get_the_ID(), 'filterableportfolio_post_live_link', true ) ) {
                	$contents .= '<a target="_blank" href="'.get_post_meta($post->ID, 'filterableportfolio_post_live_link', true).'"><i title="Live view" class="link_icon fa fa-link"></i></a>';
                } else {
                	$contents .= '<a href="'.get_permalink().'"><i title="View detail" class="link_icon fa fa-plus"></i></a>';
                }

                $contents .= '<h1 class="portfolio_title"><a href="'.get_permalink().'">'.get_the_title().'</a></h1>';
                $contents .= '</div><!--.mask-->';
                $contents .= '</div><!--.portfolio_image-->';
                $contents .= '</div><!--.portfolio_single-->';

                $contents .= '</div><!--.filter .all-->';

            endwhile;

        else:
            $contents .= '<p>It seems we can&rsquo;t find what you&rsquo;re looking for.</p>';
        endif;

    $contents .='</div><!--#Grid-->';

    return $contents;
}


function filterableportfolio_shortcode( $atts, $content = null ) {

	extract(shortcode_atts(array(
        'thumbnail' =>'2',
        'prettyphoto_theme' =>'facebook'
    ), $atts));

    return filterableportfolio_controls().filterableportfolio_contents($thumbnail).'<script>jQuery(window).load(function() { jQuery("a[rel^=\'prettyPhoto\']").prettyPhoto({ theme: "'.$prettyphoto_theme.'" }); jQuery(\'#portfolio-list\').filterable(); });</script>';

}

add_shortcode('filterable_portfolio', 'filterableportfolio_shortcode');