<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$tags 		= wp_get_post_terms( get_the_ID(), 'portfolio_cat' );
$tag_ids 	= array_map(function($tag){ return $tag->term_id; }, $tags);
$args = array(
	'post_type'      => 'portfolio',
	'posts_per_page' => intval($this->options['related_projects_number']),
	'post__not_in'   => array( get_the_ID() ),
	'tax_query'      => array(
		array(
			'taxonomy' => 'portfolio_cat',
			'field'    => 'id',
			'terms'    => $tag_ids
		)
	)
);
$portfolios = get_posts( $args );

if ( count( $portfolios ) < 1 ) return;

$image_size = $this->options['image_size'];
$rp_grid 	= sprintf('grid %1$s %2$s %3$s %4$s', $this->options['columns_phone'], $this->options['columns_tablet'], $this->options['columns_desktop'], $this->options['columns']);
?>
<h4 class="related-projects-title">
	<?php echo esc_attr($this->options['related_projects_text']); ?>
</h4>
<div class="grids related-projects portfolio-items">
	<?php foreach($portfolios as $portfolio): ?>
		<div id="id-<?php echo $portfolio->ID; ?>" class="portfolio-item <?php echo $rp_grid; ?>">
			<figure>
				<a href="<?php echo esc_url( get_permalink( $portfolio->ID ) ); ?>" rel="bookmark">
					<img src="<?php echo get_the_post_thumbnail_url( $portfolio->ID, $image_size ); ?>">
				</a>
				<figcaption>
					<h4><?php echo $portfolio->post_title; ?></h4>
					<a href="<?php echo esc_url( get_permalink( $portfolio->ID ) ); ?>" rel="bookmark" class="button"><?php _e( 'Details', 'filterable-portfolio' ); ?></a>
				</figcaption>
			</figure>
		</div>
	<?php endforeach; ?>
</div>