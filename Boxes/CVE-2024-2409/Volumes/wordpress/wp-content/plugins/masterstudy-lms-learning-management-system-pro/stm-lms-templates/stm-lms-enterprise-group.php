<?php
/**
 * @var $group_id
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly ?>

<?php
get_header();
stm_lms_register_style( 'enterprise_group' );
stm_lms_register_script( 'enterprise-group', array( 'vue.js', 'vue-resource.js' ) );
wp_localize_script(
	'stm-lms-enterprise-group',
	'stm_lms_group',
	array(
		'id'        => $group_id,
		'translate' => array(
			'admin_notice'  => esc_html__( 'You wont be able to manage group anymore. Proceed with caution!', 'masterstudy-lms-learning-management-system-pro' ),
			'remove_notice' => esc_html__( 'Do you really want to delete this user from group?', 'masterstudy-lms-learning-management-system-pro' ),
		),
	)
);
do_action( 'stm_lms_template_main' );

/*Check GRoup admin*/
$lms_current_user = STM_LMS_User::get_current_user();
if ( empty( $current_user ) ) {
	die;
}
$is_admin = STM_LMS_Enterprise_Courses::is_group_admin( $lms_current_user['id'], $group_id );

if ( ! $is_admin ) {
	STM_LMS_User::js_redirect( get_site_url() );
}
?>

<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>

	<div class="stm-lms-wrapper stm-lms-wrapper--group user-account-page">
		<div class="container">
			<?php do_action( 'stm_lms_admin_after_wrapper_start', STM_LMS_User::get_current_user() ); ?>
			<div class="stm-lms-wrapper--group_header">

				<a href="<?php echo esc_url( ms_plugin_user_account_url( 'enterprise-groups' ) ); ?>">
					<i class="lnricons-arrow-left"></i>
					<?php esc_html_e( 'Back to groups', 'masterstudy-lms-learning-management-system-pro' ); ?>
				</a>

				<h2><?php printf( esc_html__( 'Group "%s"', 'masterstudy-lms-learning-management-system-pro' ), esc_html( get_the_title( $group_id ) ) ); ?></h2>

			</div>

			<div id="stm_lms_enterprise_group" v-bind:class="{'loading': loading}">
				<?php STM_LMS_Templates::show_lms_template( 'enterprise_groups/group' ); ?>
			</div>

		</div>
	</div>

<?php get_footer(); ?>
