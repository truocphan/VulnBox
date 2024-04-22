<?php
//Meeting Front Form
$user_id              = get_current_user_id();
$frontend_gm_settings = get_user_meta( $user_id, 'frontend_instructor_google_meet_settings', true );
?>
<div class="gm-modal-abs-wrapper" id="create-meeting-mw-id">
	<div class="google-meeting-modal-wrapper fade-in">
		<form action="" type="post">
			<div class="create-meeting-mw">
				<div class="gm-modal-header">
					<p class="gm-modal-title">
						<?php echo esc_html__( 'Add a new meeting', 'masterstudy-lms-learning-management-system-pro' ); ?>
					</p>
					<a href="#" class="gm-modal-close">
						<i class="fas fa-times"></i>
					</a>
				</div>
				<div class="gm-modal-content">

					<div class="gm-modal-full">
						<p><?php echo esc_html__( 'Meeting name', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
						<input type="text" name="meeting-name" id="front-meeting-name" class="lms-gm-validation-input">
						<p class="gm-validation-error-message"><?php echo esc_html__( 'This is required field', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
					</div>
					<div class="gm-modal-full">
						<p><?php echo esc_html__( 'Meeting Summary', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
						<textarea name="stm_gma_summary" id="front-meeting-summary" cols="3" rows="3"  class="lms-gm-validation-input"></textarea>
						<p class="gm-validation-error-message text-area-message"><?php echo esc_html__( 'This is required field', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
					</div>
					<div class="gm-modal-full gm-modal-field">
						<div>
							<p><?php echo esc_html__( 'Start date', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
							<input type="datetime-local" name="meeting-start-date" id="front-meeting-start-date" class="lms-gm-validation-input">
							<p class="gm-validation-error-message"><?php echo esc_html__( 'This is required field', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
						</div>
						<div>
							<p><?php echo esc_html__( 'End date', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
							<input type="datetime-local" name="meeting-end-date" id="front-meeting-end-date" class="lms-gm-validation-input">
							<p class="gm-validation-error-message"><?php echo esc_html__( 'This is required field', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
						</div>
					</div>
					<div class="gm-modal-full">
						<div class="gm-modal-field">
							<p><?php echo esc_html__( 'Timezone', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
							<select name="stm_gma_timezone" class="select-meeting-form disable-select"
									id="front-meeting-timezone">
								<?php foreach ( $timezones as $timezone => $label ) { ?>
									<option value="<?php echo esc_attr( $timezone ); ?>" name="stm_gma_timezone"
										<?php
										if ( ( isset( $frontend_gm_settings['timezone'] ) ? $frontend_gm_settings['timezone'] : 'UTC' ) === $timezone ) {
											echo esc_html__( 'selected', 'masterstudy-lms-learning-management-system-pro' );
										}
										?>
									><?php echo esc_html( $label ); ?></option>
								<?php } ?>
							</select>

						</div>
					</div>

				</div>
				<div class="gm-modal-actions">
					<button class="btn button-save create-meeting-mw-save"><?php echo esc_html__( 'Save Changes', 'masterstudy-lms-learning-management-system-pro' ); ?></button>
					<a href="" class="btn button-cancel"><?php echo esc_html__( 'Cancel', 'masterstudy-lms-learning-management-system-pro' ); ?></a>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="gm-modal-abs-wrapper" id="delete-meeting-mw-id">
	<div class="google-meeting-modal-wrapper fade-in">
		<div class="delete-meeting-mw">
			<div class="gm-modal-header">
				<p class="gm-modal-title">
					<?php echo esc_html__( 'Confirm delete', 'masterstudy-lms-learning-management-system-pro' ); ?>
				</p>
				<a href="#" class="gm-modal-close">
					<i class="fas fa-times"></i>
				</a>
			</div>
			<div class="gm-modal-content">

				<div class="gm-modal-full delete-meeting-modal-wrapper">
					<p class="delete-subtitle"><?php echo esc_html__( 'Do you really want to delete this Google Meet ? This process cannot be undone.', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
				</div>
				<div class="gm-modal-full">
					<p class="delete-meeting-details"><?php echo esc_html__( 'Meeting Details:', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
				</div>
				<div class="gm-modal-full meet-delete-data">
					<p></p>
					<!--There will be message from Ajax Response-->
				</div>

			</div>

			<div class="gm-modal-actions">
				<a class="btn button-save danger meet-delete-btn-cl"><?php echo esc_html__( 'Delete', 'masterstudy-lms-learning-management-system-pro' ); ?></a>
				<a class="btn button-cancel"><?php echo esc_html__( 'Cancel', 'masterstudy-lms-learning-management-system-pro' ); ?></a>
			</div>
		</div>
	</div>
</div>
