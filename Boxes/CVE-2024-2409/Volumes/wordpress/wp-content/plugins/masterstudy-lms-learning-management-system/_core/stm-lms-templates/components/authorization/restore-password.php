<div class="masterstudy-authorization__restore">
	<div class="masterstudy-authorization__restore-header">
		<span class="masterstudy-authorization__restore-header-back"></span>
		<span class="masterstudy-authorization__restore-header-title">
			<?php echo esc_html__( 'Restore password', 'masterstudy-lms-learning-management-system' ); ?>
		</span>
	</div>
	<div id="masterstudy-authorization-form-restore" class="masterstudy-authorization__form">
		<div class="masterstudy-authorization__form-wrapper">
			<div class="masterstudy-authorization__form-field">
				<input type="text" name="restore_user_login" class="masterstudy-authorization__form-input" placeholder="<?php echo esc_html__( 'Enter your email', 'masterstudy-lms-learning-management-system' ); ?>">
			</div>
		</div>
	</div>
	<div class="masterstudy-authorization__actions">
		<?php
		STM_LMS_Templates::show_lms_template(
			'components/button',
			array(
				'id'    => 'masterstudy-authorization-restore-button',
				'title' => __( 'Send reset link', 'masterstudy-lms-learning-management-system' ),
				'link'  => '#',
				'style' => 'primary',
				'size'  => 'sm',
			)
		);
		?>
	</div>
</div>
