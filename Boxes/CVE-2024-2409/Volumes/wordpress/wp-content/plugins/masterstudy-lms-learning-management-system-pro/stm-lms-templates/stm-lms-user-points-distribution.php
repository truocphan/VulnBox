<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly ?>

<?php
get_header();

do_action( 'stm_lms_template_main' );
?>

<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>

	<div class="stm-lms-wrapper stm-lms-wrapper--assignments user-account-page">

		<div class="container">

			<?php do_action( 'stm_lms_admin_after_wrapper_start', STM_LMS_User::get_current_user() ); ?>

			<div class="stm-lms-wrapper--gradebook_header">

				<a href="<?php echo esc_url( STM_LMS_User::user_page_url() ); ?>">
					<i class="lnricons-arrow-left"></i>
					<?php esc_html_e( 'Back to Account', 'masterstudy-lms-learning-management-system-pro' ); ?>
				</a>

			</div>

			<div id="stm_lms_user_assignment">
				<?php STM_LMS_Templates::show_lms_template( 'points/distribution' ); ?>
			</div>

		</div>

	</div>

<?php get_footer(); ?>
