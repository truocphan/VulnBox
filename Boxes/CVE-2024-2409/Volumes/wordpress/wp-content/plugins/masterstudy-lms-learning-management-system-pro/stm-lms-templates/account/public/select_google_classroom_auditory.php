<?php

/**
 * @var $auditories
 */

?>

<div class="stm_lms_register_wrapper__actions">
	<div class="form-group">
		<label class="heading_font"><?php esc_html_e( 'Select your auditory', 'masterstudy-lms-learning-management-system-pro' ); ?></label>
		<select class="disable-select form-control" v-model="auditory">
			<option value=""><?php esc_html_e( 'Select auditory', 'masterstudy-lms-learning-management-system-pro' ); ?></option>
			<?php foreach ( $auditories as $auditory_value => $auditory_label ) : ?>
				<option :value="'<?php echo esc_attr( $auditory_value ); ?>'">
					<?php echo esc_html( $auditory_label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>
</div>
