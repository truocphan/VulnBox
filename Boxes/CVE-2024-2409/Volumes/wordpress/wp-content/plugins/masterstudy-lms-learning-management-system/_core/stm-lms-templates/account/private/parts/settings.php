<?php
/**
 * @var $lms_current_user
 */
?>
<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>
<div class="stm-lms-wrapper">

	<div class="container">

		<?php
		STM_LMS_Templates::show_lms_template(
			'account/private/parts/become_instructor_info',
			array(
				'current_user' => $lms_current_user,
			)
		);
		?>

		<?php do_action( 'stm_lms_admin_after_wrapper_start', STM_LMS_User::get_current_user() ); ?>

		<div class="row">

			<div class="col-md-3 col-sm-12">

				<?php STM_LMS_Templates::show_lms_template( 'account/private/parts/info', array( 'current_user' => $lms_current_user ) ); ?>

			</div>

			<div class="col-md-9 col-sm-12">

				<div class="stm_lms_private_information">

					<?php STM_LMS_Templates::show_lms_template( 'account/private/parts/top_info', array( 'current_user' => $lms_current_user ) ); ?>

				</div>

				<?php STM_LMS_Templates::show_lms_template( 'account/private/parts/edit_account', array( 'current_user' => $lms_current_user ) ); ?>
				<?php do_action( 'stm_lms_before_profile_buttons_all', $lms_current_user ); ?>

			</div>

		</div>

	</div>

</div>
