<?php
/**
 * @var $assignment_id
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( empty( $assignment_id ) ) {
	require_once get_404_template();
	die;
}

get_header();

stm_lms_register_style( 'user_assignment' );

do_action( 'stm_lms_template_main' );
?>

<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>

	<div class="stm-lms-wrapper stm-lms-wrapper--assignments user-account-page">

		<div class="container">

			<?php do_action( 'stm_lms_admin_after_wrapper_start', STM_LMS_User::get_current_user() ); ?>

			<div id="stm_lms_user_assignment">
				<?php
				STM_LMS_Templates::show_lms_template(
					'account/private/instructor_parts/user_assignments/main',
					compact( 'assignment_id' )
				);
				?>
			</div>

		</div>

	</div>

<?php get_footer(); ?>
