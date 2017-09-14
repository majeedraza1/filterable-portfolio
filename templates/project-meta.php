<?php
$id = get_the_ID();
$project_url = esc_url( get_post_meta( $id, '_project_url', true ) );
$client      = esc_attr( get_post_meta( $id, '_client_name', true ) );
// $categories  = get_the_term_list( $id, 'portfolio_cat', '', ', ', '' );
$date        = esc_attr( get_post_meta( $id, '_project_date', true ) );
$date        = date_i18n( get_option( 'date_format' ), strtotime( $date ) );
$terms 	= get_the_terms( $id, 'portfolio_cat' );
?>
<div class="project-meta grid s4">
	<ul>
	<?php
		// Skills
		if( is_array($terms) ){
			$terms = array_map(function( $term ){ return $term->name; }, $terms);
			echo sprintf('<li><strong>%1$s</strong><p>%2$s</p></li>', __('Skills:', 'filterable-portfolio'), implode(', ', $terms));
		}
		// Client
		if( ! empty($client) ){
			echo sprintf('<li><strong>%1$s</strong><p>%2$s</p></li>', __('Client:', 'filterable-portfolio'), $client);
		}
		// Project Date
		if( ! empty($date) ){
			echo sprintf('<li><strong>%1$s</strong><p>%2$s</p></li>', __('Project Date:', 'filterable-portfolio'), $date);
		}
		// Project URL
		if( ! empty($project_url) ){
			echo sprintf('<li><strong>%1$s</strong><p><a target="_blank" href="%2$s">%2$s</a></p></li>', __('Project URL:', 'filterable-portfolio'), $project_url );
		}
	?>
	</ul>
</div>