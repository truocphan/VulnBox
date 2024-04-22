<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //Exit if accessed directly ?>

<?php
get_header();

if ( ! is_user_logged_in() ) {
	STM_LMS_User::js_redirect( STM_LMS_User::login_page_url() );
}
?>

<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>

<div class="stm-lms-wrapper stm-lms-wrapper--assignments user-account-page">

	<div class="container">

		<?php do_action( 'stm_lms_admin_after_wrapper_start', STM_LMS_User::get_current_user() ); ?>

		<div id="stm_lms_instructor_assignments">
			<?php STM_LMS_Templates::show_lms_template( 'account/private/instructor_parts/assignments/grid' ); ?>
		</div>

	</div>

</div>

<?php get_footer(); ?>
