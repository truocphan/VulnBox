<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'stm_lms_single_course_start', get_the_ID() );

stm_lms_register_style( 'course' );

do_action( 'stm_lms_custom_content_for_single_course' );
$coming_soon_show_details = get_post_meta( get_the_ID(), 'coming_soon_show_course_details', true );
$is_course_coming_soon    = STM_LMS_Helpers::masterstudy_lms_is_course_coming_soon( get_the_ID() );
?>

<div class="row">
	<div class="col-md-9">
		<?php STM_LMS_Templates::show_lms_template( 'global/completed_label', array( 'course_id' => get_the_ID() ) ); ?>
		<h1 class="stm_lms_course__title"><?php the_title(); ?></h1>
		<?php
		if ( ! $is_course_coming_soon || $coming_soon_show_details ) {
			STM_LMS_Templates::show_lms_template( 'course/parts/panel_info' );
		}
		STM_LMS_Templates::show_lms_template(
			'global/coming_soon',
			array(
				'course_id' => get_the_ID(),
				'mode'      => 'course',
			),
		);
		STM_LMS_Templates::show_lms_template( 'course/parts/tabs' );

		if ( STM_LMS_Options::get_option( 'enable_related_courses', false ) ) {
			STM_LMS_Templates::show_lms_template( 'course/parts/related' );
		}
		?>
	</div>

	<div class="col-md-3">
		<?php STM_LMS_Templates::show_lms_template( 'course/sidebar' ); ?>
	</div>
</div>
<?php STM_LMS_Templates::show_lms_template( 'course/sticky/panel' ); ?>
