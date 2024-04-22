<div class="stm_lms_course__image">
	<?php

	$img_size = '870-440';

	$image = apply_filters( 'stm_lms_bundle_image_url', ( function_exists( 'stm_get_VC_img' ) ) ? stm_get_VC_img( get_post_thumbnail_id( get_the_ID() ), $img_size ) : get_post_thumbnail_id( get_the_ID(), $img_size ) );

	if ( function_exists( 'stm_get_VC_img' ) ) {
		echo wp_kses_post( stm_lms_lazyload_image( $image ) );
	} else {
		the_post_thumbnail( 'img-870-440' );
	}
	?>
</div>

<div class="stm_lms_course__content">
	<?php the_content(); ?>
</div>
