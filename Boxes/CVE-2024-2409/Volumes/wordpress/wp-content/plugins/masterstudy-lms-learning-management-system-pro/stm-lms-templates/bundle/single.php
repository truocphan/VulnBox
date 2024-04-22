<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
stm_lms_register_style( 'course' );
stm_lms_register_style( 'bundles/single' );
do_action( 'stm_lms_single_bundle_start', get_the_ID() );
?>

<div class="row">

	<div class="col-md-9">

		<h1 class="stm_lms_course__title"><?php the_title(); ?></h1>

		<?php STM_LMS_Templates::show_lms_template( 'bundle/parts/panel_info' ); ?>

		<?php STM_LMS_Templates::show_lms_template( 'bundle/parts/courses' ); ?>

		<?php STM_LMS_Templates::show_lms_template( 'bundle/parts/description' ); ?>

	</div>

	<div class="col-md-3">

		<?php STM_LMS_Templates::show_lms_template( 'bundle/sidebar' ); ?>

	</div>

</div>
