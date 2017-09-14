<?php
$ids = get_post_meta( get_the_ID(), '_project_images', true );
$ids = array_filter(explode(',', rtrim( $ids, ',') ) );
if ( count($ids) < 1 ) return; ?>
<div class="fp_slider">
	<ul id="fp_slides">
		<?php foreach ( $ids as $id ): ?>
			<li><?php echo wp_get_attachment_image( $id, 'full' ); ?></li>
		<?php endforeach; ?>
	</ul>
</div>