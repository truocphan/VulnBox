<?php
/**
 * @var boolean $dark_mode
 *
 * data-masterstudy-modal="masterstudy-enterprise-modal" - js trigger
 */

$dark_mode = $dark_mode ?? false;

if ( class_exists( 'STM_LMS_Form_Builder' ) ) {
	$additional_fields = STM_LMS_Form_Builder::get_form_fields( 'enterprise_form' );
}

wp_enqueue_style( 'masterstudy-enterprise-modal' );
wp_enqueue_script( 'masterstudy-modals' );
wp_localize_script(
	'masterstudy-modals',
	'enterprise_modal_data',
	array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'stm_lms_enterprise' ),
	)
);
?>
<script>
	var masterstudy_enterprise_fields;
	if (typeof authorization_data === 'undefined') {
		masterstudy_enterprise_fields = <?php echo wp_json_encode( $additional_fields ?? array() ); ?>;
	}
</script>

<div class="masterstudy-enterprise-modal <?php echo esc_attr( $dark_mode ? 'masterstudy-enterprise-modal_dark-mode' : '' ); ?>" style="opacity:0">
	<div class="masterstudy-enterprise-modal__wrapper">
		<div class="masterstudy-enterprise-modal__container">
			<div class="masterstudy-enterprise-modal__header">
				<span class="masterstudy-enterprise-modal__header-title">
					<?php echo esc_html__( 'Have a question?', 'masterstudy-lms-learning-management-system' ); ?>
				</span>
				<span class="masterstudy-enterprise-modal__header-close"></span>
			</div>
			<div class="masterstudy-enterprise-modal__close"></div>
			<div class="masterstudy-enterprise-modal__form">
				<div class="masterstudy-enterprise-modal__form-wrapper">
					<?php
					if ( ! empty( $additional_fields ) ) {
						foreach ( $additional_fields as $field ) {
							$field['type'] = in_array( $field['type'], array( 'tel', 'text', 'email' ), true ) ? 'text' : $field['type'];
							?>
							<div class="masterstudy-enterprise-modal__form-field">
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
						<div class="masterstudy-enterprise-modal__form-field">
							<input type="text" name="enterprise_name" placeholder="<?php esc_html_e( 'Enter your name', 'masterstudy-lms-learning-management-system' ); ?>" class="masterstudy-enterprise-modal__form-input">
						</div>
						<div class="masterstudy-enterprise-modal__form-field">
							<input type="text" name="enterprise_email" placeholder="<?php esc_html_e( 'Enter Your Email', 'masterstudy-lms-learning-management-system' ); ?>" class="masterstudy-enterprise-modal__form-input">
						</div>
						<div class="masterstudy-enterprise-modal__form-field">
							<textarea name="enterprise_text" placeholder="<?php esc_html_e( 'Enter Your Message', 'masterstudy-lms-learning-management-system' ); ?>" rows="6" class="masterstudy-enterprise-modal__form-textarea"></textarea>
						</div>
						<?php
					}
					if ( ! empty( $additional_fields ) ) {
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
			<div class="masterstudy-enterprise-modal__actions">
				<?php
				STM_LMS_Templates::show_lms_template(
					'components/button',
					array(
						'id'    => 'masterstudy-enterprise-modal-confirm',
						'title' => __( 'Send enquiry', 'masterstudy-lms-learning-management-system' ),
						'link'  => '#',
						'style' => 'primary',
						'size'  => 'sm',
					)
				);
				?>
			</div>
			<div class="masterstudy-enterprise-modal__success">
				<div class="masterstudy-enterprise-modal__success-icon-wrapper">
					<span class="masterstudy-enterprise-modal__success-icon"></span>
				</div>
				<span class="masterstudy-enterprise-modal__success-title">
					<?php echo esc_html__( 'Message sent', 'masterstudy-lms-learning-management-system' ); ?>
				</span>
				<?php
				STM_LMS_Templates::show_lms_template(
					'components/button',
					array(
						'id'    => 'masterstudy-enterprise-modal-close-button',
						'title' => __( 'Close', 'masterstudy-lms-learning-management-system' ),
						'link'  => '#',
						'style' => 'primary',
						'size'  => 'sm',
					)
				);
				?>
			</div>
		</div>
	</div>
</div>
