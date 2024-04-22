<?php
/**
 * @var $assignment_id
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();

$lms_current_user = STM_LMS_User::get_current_user();

stm_lms_register_style( 'instructor_assignments' );
stm_lms_register_style( 'enrolled_assignments' );
stm_lms_register_script( 'enrolled_assignments', array( 'vue.js', 'vue-resource.js' ) );
wp_localize_script(
	'stm-lms-enrolled_assignments',
	'stm_lms_enrolled_assignments',
	array(
		'statuses'    => array(
			array(
				'id'    => 'pending',
				'title' => esc_html__( 'Pending', 'masterstudy-lms-learning-management-system-pro' ),
			),
			array(
				'id'    => 'not_passed',
				'title' => esc_html__( 'Declined', 'masterstudy-lms-learning-management-system-pro' ),
			),
			array(
				'id'    => 'passed',
				'title' => esc_html__( 'Approved', 'masterstudy-lms-learning-management-system-pro' ),
			),
		),
		'assignments' => STM_LMS_User_Assignment::my_assignments( $lms_current_user['id'] ),
	)
);

do_action( 'stm_lms_template_main' );
?>
<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>

	<div class="stm-lms-wrapper stm-lms-wrapper--assignments user-account-page">

		<div class="container">

			<?php do_action( 'stm_lms_admin_after_wrapper_start', $lms_current_user ); ?>

			<div id="stm_lms_user_assignment">
				<?php STM_LMS_Templates::show_lms_template( 'account/private/parts/assignments/main' ); ?>
			</div>

		</div>

	</div>

<?php get_footer(); ?>
