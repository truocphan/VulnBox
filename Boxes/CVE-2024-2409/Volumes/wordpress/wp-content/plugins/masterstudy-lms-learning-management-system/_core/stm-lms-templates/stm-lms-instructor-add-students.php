<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly ?>

<?php
get_header();

do_action( 'stm_lms_template_main' );

$style = STM_LMS_Options::get_option( 'profile_style', 'default' );
?>

<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>

	<div class="stm-lms-wrapper stm-lms-wrapper--assignments user-account-page">

		<div class="container">

			<?php do_action( 'stm_lms_admin_after_wrapper_start', STM_LMS_User::get_current_user() ); ?>

			<div id="stm_lms_instructor_adding_students">
				<?php STM_LMS_Templates::show_lms_template( 'account/private/add_student/main' ); ?>
			</div>

		</div>

	</div>

<?php get_footer(); ?>
