<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //Exit if accessed directly ?>
<?php
if ( wp_is_mobile() ) {
	STM_LMS_Templates::show_lms_template( 'course/single' );
} else {
	$coming_soon_show_price   = get_post_meta( get_the_ID(), 'coming_soon_show_course_price', true );
	$coming_soon_show_details = get_post_meta( get_the_ID(), 'coming_soon_show_course_details', true );

	$is_course_coming_soon = false;
	if ( method_exists( 'STM_LMS_Helpers', 'masterstudy_lms_is_course_coming_soon' ) ) {
		$is_course_coming_soon = STM_LMS_Helpers::masterstudy_lms_is_course_coming_soon( get_the_ID() );
	}

	?>
	<?php stm_lms_register_style( 'course' ); ?>
	<?php stm_lms_register_style( 'classic_course' ); ?>

	<?php do_action( 'stm_lms_single_course_start', get_the_ID() ); ?>

	<div class="row classic_style">

		<div class="col-md-12 classic-col-md-12">

			<?php
			if ( ! $is_course_coming_soon || $coming_soon_show_details ) {
				STM_LMS_Templates::show_lms_template( 'course/classic/parts/panel_info' );
			}
			?>

			<div class="stm_lms_classic_title">
				<?php STM_LMS_Templates::show_lms_template( 'global/completed_label', array( 'course_id' => get_the_ID() ) ); ?>
				<div class="inner">
					<div class="title">
						<h1 class="stm_lms_course__title"><?php the_title(); ?></h1>
					</div>
					<div class="price">
						<?php STM_LMS_Templates::show_lms_template( 'global/expired_course', array( 'course_id' => get_the_ID() ) ); ?>
						<?php
						if ( ! $is_course_coming_soon || $coming_soon_show_price ) {
							STM_LMS_Templates::show_lms_template( 'global/buy-button/mixed', array( 'course_id' => get_the_ID() ) );
						}
						?>
						<?php
						if ( ! $is_course_coming_soon || $coming_soon_show_details ) {
							STM_LMS_Templates::show_lms_template(
								'course/classic/parts/panel_info/rate',
								array(
									'course_id' => get_the_ID(),
								)
							);
						}
						?>
					</div>
				</div>
			</div>

		</div>

		<div class="col-md-3 classic-col-md-3">

			<?php STM_LMS_Templates::show_lms_template( 'course/classic/sidebar' ); ?>

		</div>

		<div class="col-md-9">
			<?php
			STM_LMS_Templates::show_lms_template(
				'global/coming_soon',
				array(
					'course_id' => get_the_ID(),
					'mode'      => 'course',
				),
			);
			?>

			<div class="stm_lms_course__image">
				<?php the_post_thumbnail( 'img-870-440' ); ?>
			</div>

			<?php STM_LMS_Templates::show_lms_template( 'course/classic/parts/tabs' ); ?>

			<?php
			if ( STM_LMS_Options::get_option( 'enable_related_courses', false ) ) {
				STM_LMS_Templates::show_lms_template( 'course/parts/related' );
			}
			?>
		</div>

	</div>
	<?php STM_LMS_Templates::show_lms_template( 'course/sticky/panel' ); ?>
<?php } ?>
