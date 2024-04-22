<?php
/**
 * @var $current_user_id
 * @var $instructor_id
 */

if ( class_exists( 'STM_LMS_Form_Builder' ) ) :
	$additional_fields = STM_LMS_Form_Builder::register_form_fields( false );
	if ( ! empty( $additional_fields['register'] ) ) :
		$user_fields = array();
		$user_meta   = get_user_meta( $instructor_id );
		foreach ( $additional_fields['register'] as $field ) {
			if ( empty( $field['public'] ) ) {
				continue;
			}
			$meta = ! empty( $user_meta[ $field['id'] ][0] ) ? $user_meta[ $field['id'] ][0] : '';
			if ( empty( $meta ) ) {
				continue;
			}
			$label         = ! empty( $field['label'] ) ? $field['label'] : $field['field_name'];
			$user_fields[] = array(
				'label' => $label,
				'value' => $meta,
				'type'  => $field['type'],
			);
		}
		if ( ! empty( $user_fields ) ) : ?>
			<div class="profile_additional_fields">
				<h3><?php esc_html_e( 'Additional information', 'masterstudy-lms-learning-management-system-pro' ); ?></h3>
				<div class="additional-fields">
					<?php foreach ( $user_fields as $field ) : ?>
						<ul class="additional-field">
							<li>
								<span class="field-label"><?php echo esc_html( $field['label'] ); ?> : </span>
								<?php if ( 'file' === $field['type'] ) : ?>
									<a href="<?php echo esc_url( $field['value'] ); ?>" class="field-value"><?php echo esc_html( basename( $field['value'] ) ); ?></a>
								<?php else : ?>
									<span class="field-value"><?php echo esc_html( $field['value'] ); ?></span>
								<?php endif; ?>
							</li>
						</ul>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
		endif;
	endif;
endif;
