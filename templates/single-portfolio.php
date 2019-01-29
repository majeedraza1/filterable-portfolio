<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$template = FILTERABLE_PORTFOLIO_TEMPLATES . '/portfolio-slider.php';
load_template( $template, false );

$options = get_option( 'filterable_portfolio' );
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