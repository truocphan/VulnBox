<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} // Exit if accessed directly ?>

<?php
get_header();
stm_lms_register_style( 'enterprise_groups' );
stm_lms_register_script( 'enterprise-groups', array( 'vue.js', 'vue-resource.js' ) );
wp_localize_script(
	'stm-lms-enterprise-groups',
	'stm_lms_groups',
	array(
		'limit'        => STM_LMS_Enterprise_Courses::get_group_common_limit(),
		'translations' => array(
			'group_limit' => esc_html__( 'Group Limit:', 'masterstudy-lms-learning-management-system-pro' ),
		),
	)
);
do_action( 'stm_lms_template_main' );
?>

<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>

	<div class="stm-lms-wrapper stm-lms-wrapper--gradebook user-account-page">

		<div id="stm_lms_enterprise_groups" v-bind:class="{'loading': loading}">

			<div class="container">

				<?php do_action( 'stm_lms_admin_after_wrapper_start', STM_LMS_User::get_current_user() ); ?>

				<div class="row">

					<div class="col-sm-6">
						<?php STM_LMS_Templates::show_lms_template( 'enterprise_groups/groups' ); ?>
					</div>

					<div class="col-sm-6">
						<?php STM_LMS_Templates::show_lms_template( 'enterprise_groups/edit_group' ); ?>
					</div>

				</div>

			</div>

		</div>

		<?php do_action( 'stm_lms_after_groups_end' ); ?>

	</div>

<?php get_footer(); ?>
