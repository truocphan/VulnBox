<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} // Exit if accessed directly ?>

<?php
do_action( 'stm_lms_before_user_header' );
do_action( 'stm_lms_template_main' );

$current_user = STM_LMS_User::get_current_user( '', true, true );
$tpl          = 'account/private/main';

stm_lms_register_style( 'user' );

if ( function_exists( 'vc_asset_url' ) ) {
	wp_enqueue_style( 'stm_lms_wpb_front_css', vc_asset_url( 'css/js_composer.min.css' ), array(), time() );
}
?>
<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>
<div class="stm-lms-wrapper stm-lms-wrapper-user user-account-page">

	<?php do_action( 'stm_lms_admin_after_wrapper_start', $current_user ); ?>

	<?php STM_LMS_Templates::show_lms_template( 'account/private/parts/become_instructor_info', compact( 'current_user' ) ); ?>
	<div class="container">
		<?php
		if ( ! empty( $tpl ) ) {
			STM_LMS_Templates::show_lms_template( $tpl, compact( 'current_user' ) );}
		?>
	</div>
</div>
