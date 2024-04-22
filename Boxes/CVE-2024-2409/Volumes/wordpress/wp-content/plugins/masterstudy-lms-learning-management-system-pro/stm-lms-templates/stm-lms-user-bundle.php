<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly ?>

<?php
get_header();

do_action( 'stm_lms_template_main' );

$bundle_id = ( ! empty( $bundle_id ) ) ? intval( $bundle_id ) : '';

$tpl = ( STM_LMS_Instructor::is_instructor() ) ? 'instructor_' : '';

stm_lms_register_style( 'user' );
stm_lms_register_style( 'edit_account' );

if ( function_exists( 'vc_asset_url' ) ) {
	wp_enqueue_style( 'stm_lms_wpb_front_css', vc_asset_url( 'css/js_composer.min.css' ), array(), time() );
}

$lms_user = STM_LMS_User::get_current_user();

?>

<div class="container">

	<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>

	<div class="stm-lms-wrapper stm-lms-wrapper--assignments user-account-page">

		<?php do_action( 'stm_lms_admin_after_wrapper_start', $lms_user ); ?>

		<?php STM_LMS_Templates::show_lms_template( 'bundles/my-bundle', compact( 'bundle_id' ) ); ?>


	</div>

</div>

<?php get_footer(); ?>
