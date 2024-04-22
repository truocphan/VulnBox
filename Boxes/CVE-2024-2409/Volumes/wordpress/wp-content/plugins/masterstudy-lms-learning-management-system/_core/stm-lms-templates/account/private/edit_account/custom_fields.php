<?php
$forms        = get_option( 'stm_lms_form_builder_forms', array() );
$profile_form = array();

if ( class_exists( 'STM_LMS_Form_Builder' ) && ! empty( $forms ) && is_array( $forms ) ) {
	foreach ( $forms as $form ) {
		if ( 'profile_form' === $form['slug'] ) {
			$profile_form = $form['fields'];
		}
	}
}

if ( empty( $profile_form ) ) {
	return;
}
?>
<script>
	window.profileForm = <?php echo wp_json_encode( $profile_form ); ?>;
</script>
<div class="row">
	<?php
	$user_meta = get_user_meta( get_current_user_id() );
	foreach ( $profile_form as $field ) {
		$field['value'] = ! empty( $user_meta[ $field['id'] ][0] ) ? $user_meta[ $field['id'] ][0] : '';
		?>
		<div class="col-md-12">
			<div class="form-group">
				<?php
				$field['type'] = in_array( $field['type'], array( 'tel', 'text', 'email' ), true ) ? 'text' : $field['type'];
				if ( 'file' === $field['type'] ) {
					$field['extensions'] = ! empty( $field['extensions'] ) ? $field['extensions'] : '.png, .jpg, .jpeg, .mp4, .pdf';
					$attachment_id       = attachment_url_to_postid( $field['value'] );
					$attachment          = ! empty( $attachment_id ) ? get_post( $attachment_id ) : '';
					STM_LMS_Templates::show_lms_template(
						'components/form-builder-fields/file',
						array(
							'data'                   => $field,
							'attachments'            => array( $attachment ),
							'allowed_extensions'     => explode( ', ', $field['extensions'] ),
							'files_limit'            => '',
							'allowed_filesize'       => '',
							'allowed_filesize_label' => '',
							'readonly'               => false,
							'multiple'               => false,
							'dark_mode'              => false,
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
		</div>
		<?php
	}
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
	?>
</div>
