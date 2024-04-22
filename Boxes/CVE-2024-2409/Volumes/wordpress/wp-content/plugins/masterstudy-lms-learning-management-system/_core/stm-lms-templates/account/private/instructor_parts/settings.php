<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //Exit if accessed directly ?>

<?php
/**
 * @var $lms_current_user
 */

stm_lms_register_style( 'user_info_top' );
stm_lms_register_style( 'edit_account' );
stm_lms_register_style( 'instructor/account' );

?>

<div class="container">
	<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>
	<div class="stm-lms-wrapper">
		<div class="container">
			<div class="row">
				<?php do_action( 'stm_lms_admin_after_wrapper_start', STM_LMS_User::get_current_user() ); ?>

				<?php
				STM_LMS_Templates::show_lms_template(
					'account/private/instructor_parts/top_info',
					array(
						'current_user' => $lms_current_user,
						'socials'      => true,
					)
				);
				?>

				<div class="stm_lms_instructor_info">

					<div class="col-md-3 col-sm-12">
						<div class="stm_lms_instructor_edit_avatar lms_instructor_settings">

							<?php STM_LMS_Templates::show_lms_template( 'account/private/parts/avatar_edit', array( 'current_user' => $lms_current_user ) ); ?>
							<?php do_action( 'stm_lms_before_profile_buttons_all', $lms_current_user ); ?>

						</div>
					</div>
					<div class="col-md-9 col-sm-12">
						<div class="stm_lms_instructor_edit_settings">

							<?php STM_LMS_Templates::show_lms_template( 'account/private/parts/edit_account', array( 'current_user' => $lms_current_user ) ); ?>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
