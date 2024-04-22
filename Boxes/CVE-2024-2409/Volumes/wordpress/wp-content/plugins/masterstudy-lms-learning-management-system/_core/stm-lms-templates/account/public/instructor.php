<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //Exit if accessed directly ?>

<?php
/**
 * @var $current_user
 */

stm_lms_register_style( 'user_info_top' );
stm_lms_register_style( 'edit_account' );
stm_lms_register_style( 'instructor/account' );

?>

<div class="stm-lms-public-instructor">

	<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>

	<?php
	STM_LMS_Templates::show_lms_template(
		'account/public/instructor_parts/top_info',
		array(
			'current_user' => $current_user,
			'socials'      => true,
		)
	);
	?>

	<?php STM_LMS_Templates::show_lms_template( 'account/public/instructor_parts/info', array( 'current_user' => $current_user ) ); ?>

	<?php STM_LMS_Templates::show_lms_template( 'account/public/instructor_parts/courses', array( 'current_user' => $current_user ) ); ?>

</div>
