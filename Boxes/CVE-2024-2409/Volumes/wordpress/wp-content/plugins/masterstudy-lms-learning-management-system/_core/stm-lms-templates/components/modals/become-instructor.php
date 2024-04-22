<?php
/**
 * @var boolean $dark_mode
 *
 * data-masterstudy-modal="masterstudy-become-instructor-modal" - js trigger
 */

if ( class_exists( 'STM_LMS_Form_Builder' ) ) {
	$additional_fields = STM_LMS_Form_Builder::register_form_fields();
}
$dark_mode                            = $dark_mode ?? false;
$submission_status                    = get_user_meta( get_current_user_id(), 'submission_status', true );
$settings                             = get_option( 'stm_lms_settings' );
$settings['instructor_premoderation'] = $settings['instructor_premoderation'] ?? true;

wp_enqueue_style( 'masterstudy-become-instructor-modal' );
wp_enqueue_script( 'masterstudy-modals' );
wp_localize_script(
	'masterstudy-modals',
	'instructor_modal_data',
	array(
		'ajax_url'                 => admin_url( 'admin-ajax.php' ),
		'nonce'                    => wp_create_nonce( 'stm_lms_become_instructor' ),
		'instructor_premoderation' => $settings['instructor_premoderation'],
		'submission_status'        => 'pending' === $submission_status,
	)
);
?>
<script>
	var masterstudy_become_instructor_fields;
	if (typeof masterstudy_become_instructor_fields === 'undefined') {
		masterstudy_become_instructor_fields = <?php echo wp_json_encode( $additional_fields['become_instructor'] ?? array() ); ?>;
	}
</script>

<div class="masterstudy-become-instructor-modal <?php echo esc_attr( $dark_mode ? 'masterstudy-become-instructor-modal_dark-mode' : '' ); ?>" style="opacity:0">
	<div class="masterstudy-become-instructor-modal__wrapper">
		<div class="masterstudy-become-instructor-modal__container">
			<div class="masterstudy-become-instructor-modal__header">
				<span class="masterstudy-become-instructor-modal__header-title">
					<?php echo esc_html__( 'Become an instructor', 'masterstudy-lms-learning-management-system' ); ?>
				</span>
				<span class="masterstudy-become-instructor-modal__header-close"></span>
			</div>
			<div class="masterstudy-become-instructor-modal__close"></div>
			<div class="masterstudy-become-instructor-modal__form">
				<div class="masterstudy-become-instructor-modal__form-wrapper">
					<?php
					if ( ! empty( $additional_fields['become_instructor'] ) ) {
						foreach ( $additional_fields['become_instructor'] as $field ) {
							$field['type'] = in_array( $field['type'], array( 'tel', 'text', 'email' ), true ) ? 'text' : $field['type'];
							?>
							<div class="masterstudy-become-instructor-modal__form-field">
								<?php
								if ( 'file' === $field['type'] ) {
									$field['extensions'] = ! empty( $field['extensions'] ) ? $field['extensions'] : '.png, .jpg, .jpeg, .mp4, .pdf';
									STM_LMS_Templates::show_lms_template(
										'components/form-builder-fields/file',
										array(
											'data'        => $field,
											'attachments' => array(),
											'allowed_extensions' => explode( ', ', $field['extensions'] ),
											'files_limit' => '',
											'allowed_filesize' => '',
											'allowed_filesize_label' => '',
											'readonly'    => false,
											'multiple'    => false,
											'dark_mode'   => $dark_mode,
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
					} else {
						?>
						<div class="masterstudy-become-instructor-modal__form-field">
							<input type="text" name="degree" placeholder="<?php esc_html_e( 'Enter degree', 'masterstudy-lms-learning-management-system' ); ?>" class="masterstudy-become-instructor-modal__form-input">
						</div>
						<div class="masterstudy-become-instructor-modal__form-field">
							<input type="text" name="expertize" placeholder="<?php esc_html_e( 'Enter expertise', 'masterstudy-lms-learning-management-system' ); ?>" class="masterstudy-become-instructor-modal__form-input">
						</div>
						<?php
					}
					if ( ! empty( $additional_fields['become_instructor'] ) ) {
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
								'dark_mode'           => false,
							)
						);
					}
					?>
				</div>
			</div>
			<div class="masterstudy-become-instructor-modal__actions">
				<?php
				STM_LMS_Templates::show_lms_template(
					'components/button',
					array(
						'id'    => 'masterstudy-become-instructor-modal-confirm',
						'title' => __( 'Send application', 'masterstudy-lms-learning-management-system' ),
						'link'  => '#',
						'style' => 'primary',
						'size'  => 'sm',
					)
				);
				?>
			</div>
			<?php if ( $settings['instructor_premoderation'] ) { ?>
				<div class="masterstudy-become-instructor-modal__success">
					<div class="masterstudy-become-instructor-modal__success-icon-wrapper">
						<span class="masterstudy-become-instructor-modal__success-icon"></span>
					</div>
					<span class="masterstudy-become-instructor-modal__success-title">
						<?php echo esc_html__( 'Your Application is under submission', 'masterstudy-lms-learning-management-system' ); ?>
					</span>
					<?php
					STM_LMS_Templates::show_lms_template(
						'components/button',
						array(
							'id'    => 'masterstudy-become-instructor-modal-close-button',
							'title' => __( 'Close', 'masterstudy-lms-learning-management-system' ),
							'link'  => '#',
							'style' => 'primary',
							'size'  => 'sm',
						)
					);
					?>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
