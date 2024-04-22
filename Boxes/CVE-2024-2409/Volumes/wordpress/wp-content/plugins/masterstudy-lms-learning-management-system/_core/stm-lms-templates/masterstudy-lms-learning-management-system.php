<?php if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

STM_LMS_Course::course_views( get_the_ID() );

get_header();

STM_LMS_Templates::show_lms_template( 'modals/preloader' );
// phpcs:ignoreFile
?>
<div class="<?php echo apply_filters( 'stm_lms_wrapper_classes', 'stm-lms-wrapper' ); ?>">
	<div class="container">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				do_action( 'stm-lms-content-' . get_post_type() );
			endwhile;
		endif;
		?>
	</div>
</div>
<?php
get_footer();
