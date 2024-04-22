<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //Exit if accessed directly
do_action( 'stm_lms_template_main' );
?>

<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>

<div class="stm-lms-wrapper">
	<div class="container">
		<?php STM_LMS_Templates::show_lms_template( 'checkout/cart' ); ?>
	</div>
</div>
