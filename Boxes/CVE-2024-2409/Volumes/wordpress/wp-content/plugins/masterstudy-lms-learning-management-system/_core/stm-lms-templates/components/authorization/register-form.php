<?php
/**
 * @var array $default_fields
 * @var array $additional_fields
 * @var array $instructor_fields
 * @var boolean $only_for_instructor
 * @var boolean $separate_instructor_registration
 * @var boolean $disable_instructor
 * @var boolean $is_logged_in
 * @var boolean $dark_mode
 */
?>

<div id="masterstudy-authorization-form-register" class="masterstudy-authorization__form">
	<div class="masterstudy-authorization__form-wrapper">
		<?php if ( ! $is_logged_in ) { ?>
			<div class="masterstudy-authorization__form-field">
				<input type="text" name="register_user_email" class="masterstudy-authorization__form-input" placeholder="<?php echo esc_html__( 'Enter your email', 'masterstudy-lms-learning-management-system' ); ?>">
			</div>
			<div class="masterstudy-authorization__form-field">
				<input type="text" name="register_user_login" class="masterstudy-authorization__form-input" placeholder="<?php echo esc_html__( 'Enter username', 'masterstudy-lms-learning-management-system' ); ?>">
			</div>
			<div class="masterstudy-authorization__form-field">
				<input type="password" name="register_user_password" class="masterstudy-authorization__form-input masterstudy-authorization__form-input_pass" placeholder="<?php echo esc_html__( 'Enter password', 'masterstudy-lms-learning-management-system' ); ?>">
				<span class="masterstudy-authorization__form-show-pass"></span>
				<span class="masterstudy-authorization__form-explain-pass">
					<?php echo esc_html__( 'The password must have a minimum of 8 characters of numbers and letters, contain at least 1 capital letter, and should not exceed 20 characters', 'masterstudy-lms-learning-management-system' ); ?>
				</span>
			</div>
			<div class="masterstudy-authorization__form-field">
				<input type="password" name="register_user_password_re" class="masterstudy-authorization__form-input masterstudy-authorization__form-input_pass" placeholder="<?php echo esc_html__( 'Repeat password', 'masterstudy-lms-learning-management-system' ); ?>">
				<span class="masterstudy-authorization__form-show-pass"></span>
			</div>
			<?php
			if ( ! empty( $default_fields ) ) {
				foreach ( $default_fields as $index => $field ) {
					if ( 'description' === $index ) {
						?>
						<div class="masterstudy-authorization__form-field">
							<textarea name="<?php echo esc_attr( $index ); ?>" class="masterstudy-authorization__form-textarea" placeholder="<?php echo esc_html( $field['placeholder'] ); ?>"></textarea>
						</div>
						<?php
					} else {
						?>
						<div class="masterstudy-authorization__form-field">
							<input type="text" name="<?php echo esc_attr( $index ); ?>" class="masterstudy-authorization__form-input" placeholder="<?php echo esc_html( $field['placeholder'] ); ?>">
						</div>
						<?php
					}
				}
			}
			if ( ! empty( $additional_fields ) ) {
				foreach ( $additional_fields as $field ) {
					$field['type'] = in_array( $field['type'], array( 'tel', 'text', 'email' ), true ) ? 'text' : $field['type'];
					?>
					<div class="masterstudy-authorization__form-field">
						<?php
						if ( 'file' === $field['type'] ) {
							$field['extensions'] = ! empty( $field['extensions'] ) ? $field['extensions'] : '.png, .jpg, .jpeg, .mp4, .pdf';
							STM_LMS_Templates::show_lms_template(
								'components/form-builder-fields/file',
								array(
									'data'               => $field,
									'attachments'        => array(),
									'allowed_extensions' => explode( ', ', $field['extensions'] ),
									'files_limit'        => '',
									'allowed_filesize'   => '',
									'allowed_filesize_label' => '',
									'readonly'           => false,
									'multiple'           => false,
									'dark_mode'          => $dark_mode,
								)
							);
						} else {
							STM_LMS_Templates::show_lms_template(
								'components/form-builder-fields/' . $field['type'],
								array(
									'data' => $field,
								)
							);
						}
						?>
					</div>
					<?php
				}
			}
		}
		if ( ! $disable_instructor ) {
			if ( ! $only_for_instructor && ! $separate_instructor_registration ) {
				?>
				<div class="masterstudy-authorization__instructor">
					<div class="masterstudy-authorization__checkbox">
						<input type="checkbox" name="be_instructor" id="masterstudy-authorization-instructor"/>
						<span class="masterstudy-authorization__checkbox-wrapper"></span>
					</div>
					<span class="masterstudy-authorization__instructor-text">
						<?php echo esc_html__( 'I want to sign up as instructor', 'masterstudy-lms-learning-management-system' ); ?>
					</span>
				</div>
				<?php
			}
			if ( ! empty( $instructor_fields ) ) {
				?>
				<div class="masterstudy-authorization__instructor-container <?php echo esc_attr( $only_for_instructor ? 'masterstudy-authorization__instructor-container_open' : '' ); ?>">
					<?php
					foreach ( $instructor_fields as $field ) {
						$field['type'] = in_array( $field['type'], array( 'tel', 'text', 'email' ), true ) ? 'text' : $field['type'];
						?>
						<div class="masterstudy-authorization__form-field">
							<?php
							if ( 'file' === $field['type'] ) {
								$field['extensions'] = ! empty( $field['extensions'] ) ? $field['extensions'] : '.png, .jpg, .jpeg, .mp4, .pdf';
								STM_LMS_Templates::show_lms_template(
									'components/form-builder-fields/file',
									array(
										'data'             => $field,
										'attachments'      => array(),
										'allowed_extensions' => explode( ', ', $field['extensions'] ),
										'files_limit'      => '',
										'allowed_filesize' => '',
										'allowed_filesize_label' => '',
										'readonly'         => false,
										'multiple'         => false,
										'dark_mode'        => $dark_mode,
									)
								);
							} else {
								STM_LMS_Templates::show_lms_template(
									'components/form-builder-fields/' . $field['type'],
									array(
										'data' => $field,
									)
								);
							}
							?>
						</div>
					<?php } ?>
				</div>
				<?php
			} else {
				?>
				<div class="masterstudy-authorization__instructor-container <?php echo esc_attr( $only_for_instructor ? 'masterstudy-authorization__instructor-container_open' : '' ); ?>">
					<div class="masterstudy-authorization__form-field">
						<input type="text" name="degree" class="masterstudy-authorization__form-input" placeholder="<?php echo esc_html__( 'Enter degree', 'masterstudy-lms-learning-management-system' ); ?>">
					</div>
					<div class="masterstudy-authorization__form-field">
						<input type="text" name="expertize" class="masterstudy-authorization__form-input" placeholder="<?php echo esc_html__( 'Enter expertize', 'masterstudy-lms-learning-management-system' ); ?>">
					</div>
				</div>
				<?php
			}
		}
		if ( ! empty( $additional_fields ) || ! empty( $instructor_fields ) ) {
			STM_LMS_Templates::show_lms_template(
				'components/alert',
				array(
					'id'                  => 'form_builder_file_alert',
					'title'               => esc_html__( 'Delete file', 'masterstudy-lms-learning-management-system' ),
					'text'                => esc_html__( 'Are you sure you want to delete this file?', 'masterstudy-lms-learning-management-system' ),
					'submit_button_text'  => esc_html__( 'Delete', 'masterstudy-lms-learning-management-system' ),
					'cancel_button_text'  => esc_html__( 'Cancel', 'masterstudy-lms-learning-management-system' ),
					'submit_button_style' => 'danger',
					'cancel_button_style' => 'tertiary',
					'dark_mode'           => $dark_mode,
				)
			);
		}
		?>
	</div>
</div>
