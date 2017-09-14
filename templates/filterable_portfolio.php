<?php if ( count( $portfolios ) > 0 ): ?>
	<div id="filterable-portfolio" class="filterable-portfolio">

		<?php echo $this->get_terms_html(); ?>
		
		<div id="portfolio-items" class="grids portfolio-items">
			<?php foreach($portfolios as $portfolio): ?>
				<div
					id="id-<?php echo $portfolio->id; ?>"
					class="portfolio-item <?php echo $grid; ?>"
					data-groups='<?php echo $portfolio->terms; ?>'
				>
					<figure>
						<a href="<?php echo $portfolio->permalink; ?>" rel="bookmark">
							<?php echo get_the_post_thumbnail( $portfolio->id, $image_size ); ?>
						</a>
						<figcaption>
							<h4><?php echo $portfolio->title; ?></h4>
							<a href="<?php echo $portfolio->permalink; ?>" rel="bookmark" class="button"><?php _e( 'Details', 'filterable-portfolio' ); ?></a>
						</figcaption>
					</figure>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>
