<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} // Exit if accessed directly ?>

<?php
get_header();

$lms_current_user = STM_LMS_User::get_current_user( '', true, true );

$tpl = 'account/private/chat/main';

stm_lms_register_style( 'user' );
do_action( 'stm_lms_template_main' );

?>
<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>
	<div class="stm-lms-wrapper user-account-page">
		<div class="container">

			<?php do_action( 'stm_lms_admin_after_wrapper_start', $lms_current_user ); ?>

			<?php
			STM_LMS_Templates::show_lms_template(
				'account/private/parts/top_info',
				array(
					'title' => esc_html__( 'My Messages', 'masterstudy-lms-learning-management-system' ),
				)
			);
			?>

			<?php
			if ( ! empty( $tpl ) ) {
				STM_LMS_Templates::show_lms_template( $tpl, array( 'current_user' => $lms_current_user ) );}
			?>
		</div>
	</div>

<?php get_footer(); ?>
