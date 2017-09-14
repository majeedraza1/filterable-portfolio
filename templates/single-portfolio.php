<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$project_images = get_post_meta( get_the_ID(), '_project_images', true );
$project_images = array_filter(explode(',', rtrim( $project_images, ',') ) );

$id 			= get_the_ID();
$project_url 	= esc_url( get_post_meta( $id, '_project_url', true ) );
$client_name    = esc_attr( get_post_meta( $id, '_client_name', true ) );
$project_date   = esc_attr( get_post_meta( $id, '_project_date', true ) );
$project_date   = date_i18n( get_option( 'date_format' ), strtotime( $project_date ) );
$terms 		 	= get_the_terms( $id, 'portfolio_cat' );
$skills 		= get_the_terms( $id, 'portfolio_skill' );

$categories_text 	= esc_attr( $this->options['project_categories_text'] );
$skills_text 		= esc_attr( $this->options['project_skills_text'] );
$url_text 			= esc_attr( $this->options['project_url_text'] );
$date_text 			= esc_attr( $this->options['project_date_text'] );
$client_text 		= esc_attr( $this->options['project_client_text'] );

if ( count($project_images) > 0 ): ?>
<div class="fp_slider">
	<ul id="fp_slides">
		<?php foreach ( $project_images as $image_id ): ?>
			<li><?php echo wp_get_attachment_image( $image_id, 'full' ); ?></li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>

<div class="grids">
	<div class="project-content grid s8">
		<h4><?php echo esc_attr( $this->options['project_description_text'] ); ?></h4>
		<?php echo get_the_content(); ?>
	</div>
	<div class="project-meta grid s4">
		<h4><?php echo esc_attr( $this->options['project_details_text'] ); ?></h4>
		<ul>
		<?php
			// Skills
			if( is_array($skills) ){
				$skills = array_map(function( $term ){ return $term->name; }, $skills);
				echo sprintf('<li><strong>%1$s</strong><p>%2$s</p></li>', $skills_text, implode('<br>', $skills));
			}
			// Categories
			if( is_array($terms) ){
				$terms = array_map(function( $term ){ return $term->name; }, $terms);
				echo sprintf('<li><strong>%1$s</strong><p>%2$s</p></li>', $categories_text, implode('<br>', $terms));
			}
			// Client
			if( ! empty($client_name) ){
				echo sprintf('<li><strong>%1$s</strong><p>%2$s</p></li>', $client_text, $client_name);
			}
			// Project Date
			if( ! empty($project_date) ){
				echo sprintf('<li><strong>%1$s</strong><p>%2$s</p></li>', $date_text, $project_date);
			}
			// Project URL
			if( ! empty($project_url) ){
				echo sprintf('<li><strong>%1$s</strong><p><a target="_blank" href="%2$s">%2$s</a></p></li>', $url_text, $project_url );
			}
		?>
		</ul>
	</div>
</div>