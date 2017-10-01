<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$_fp_class = 'grids portfolio-items';
$_fp_class .= ' fp-theme-' . $_theme;
?>
<?php if ( count( $portfolios ) > 0 ): ?>
    <div id="filterable-portfolio" class="filterable-portfolio">

		<?php if ( $terms && ! is_wp_error( $terms ) && count( $terms ) > 0 ): ?>
            <div id="filter" class="portfolio-terms">
                <div id="portfolio-filter" class="filter-options">
                    <button class="active" data-filter="*">
						<?php echo esc_attr( $this->options['all_categories_text'] ); ?>
                    </button>
					<?php foreach ( $terms as $term ): ?>
                        <button data-filter=".<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_attr( $term->name ); ?></button>
					<?php endforeach; ?>
                </div>
            </div>
		<?php endif; ?>

        <div id="portfolio-items" class="<?php echo $_fp_class; ?>">
			<?php foreach ( $portfolios as $portfolio ): ?>
                <div id="id-<?php echo $portfolio->id; ?>"
                     class="portfolio-item <?php echo $grid; ?> <?php echo implode( " ",
					     json_decode( $portfolio->terms ) ); ?>">
                    <figure>
                        <a href="<?php echo $portfolio->permalink; ?>" rel="bookmark">
							<?php echo get_the_post_thumbnail( $portfolio->id, $image_size ); ?>
                        </a>
                        <figcaption>
                            <h4><?php echo $portfolio->title; ?></h4>
                            <a href="<?php echo $portfolio->permalink; ?>" rel="bookmark"
                               class="button"><?php echo esc_attr( $this->options['details_button_text'] ); ?></a>
                        </figcaption>
                    </figure>
                </div>
			<?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
