<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<?php if ( count( $portfolios ) > 0 ): ?>
	<div id="filterable-portfolio" class="filterable-portfolio">

		<?php if ( $terms && ! is_wp_error( $terms ) && count($terms) > 0 ): ?>
			<div id="filter" class="portfolio-terms">
				<div class="filter-options">
					<button class="active" data-group="all">
						<?php echo esc_attr( $this->options['all_categories_text'] ); ?>
					</button>
					<?php foreach ( $terms as $term ): ?>
						<button data-group="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_attr( $term->name ); ?></button>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
		
		<div id="portfolio-items" class="grids portfolio-items">
			<?php foreach($portfolios as $portfolio): ?>
				<div id="id-<?php echo $portfolio->id; ?>" class="portfolio-item <?php echo $grid; ?>" data-groups='<?php echo $portfolio->terms; ?>'>
					<figure>
						<a href="<?php echo $portfolio->permalink; ?>" rel="bookmark">
							<?php echo get_the_post_thumbnail( $portfolio->id, $image_size ); ?>
						</a>
						<figcaption>
							<h4><?php echo $portfolio->title; ?></h4>
							<a href="<?php echo $portfolio->permalink; ?>" rel="bookmark" class="button"><?php echo esc_attr( $this->options['details_button_text'] ); ?></a>
						</figcaption>
					</figure>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>
