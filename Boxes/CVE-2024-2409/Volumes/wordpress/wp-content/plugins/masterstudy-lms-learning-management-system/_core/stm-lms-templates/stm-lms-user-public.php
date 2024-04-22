<?php
/**
 * @var $lms_user_id
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //Exit if accessed directly
$lms_current_user = STM_LMS_User::get_current_user( $lms_user_id, false, true );
if ( empty( $lms_current_user['id'] ) ) {
	require_once( get_404_template() );
	die;
};

$tpl = 'account/public/main';

get_header();
stm_lms_register_style( 'user' );
do_action( 'stm_lms_template_main' );

stm_lms_register_style( 'account/v1/user' );
stm_lms_register_script( 'account/v1/user' );

?>
<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>
<div class="stm-lms-wrapper">
	<div class="container">
		<?php
		if ( ! empty( $tpl ) ) {
			STM_LMS_Templates::show_lms_template( $tpl, array( 'current_user' => $lms_current_user ) );
		}
		?>
	</div>
</div>

<?php get_footer(); ?>
