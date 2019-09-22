<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

$options = Filterable_Portfolio_Helper::get_options();
?>
<div class="single-portfolio-content">
	<?php
	$template = FILTERABLE_PORTFOLIO_TEMPLATES . '/portfolio-slider.php';
	load_template( $template, false );
	?>
    <div class="grids">
        <div class="project-content grid s8">
            <h4><?php echo esc_attr( $options['project_description_text'] ); ?></h4>
			<?php echo get_the_content(); ?>
        </div>
        <div class="project-meta grid s4">
			<?php
			$template = FILTERABLE_PORTFOLIO_TEMPLATES . '/portfolio-meta.php';
			load_template( $template, false );
			?>
        </div>
    </div>
	<?php
	if ( isset( $options['show_related_projects'] ) && $options['show_related_projects'] ) {
		$template = FILTERABLE_PORTFOLIO_TEMPLATES . '/related-portfolio.php';
		load_template( $template, false );
	}
	?>
</div>