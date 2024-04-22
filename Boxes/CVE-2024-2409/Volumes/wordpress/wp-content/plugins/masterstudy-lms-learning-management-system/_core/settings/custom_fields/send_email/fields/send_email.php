<?php
/**
 * @var $field
 * @var $field_id
 * @var $field_value
 * @var $field_label
 * @var $field_name
 * @var $section_name
 *
 */

$field = "data['{$section_name}']['fields']['{$field_name}']";

wp_enqueue_style( 'stm-hidden-css', STM_LMS_URL . '/settings/custom_fields/send_email/components_css/send-email.css', null, get_bloginfo( 'version' ), 'all' );

include STM_LMS_PATH . '/settings/custom_fields/send_email/components_js/send_email.php';

?>

<div class="stm-lms-send_email-field">
	<send_email :fields="<?php echo esc_attr( $field ); ?>"
				:field_label="<?php echo esc_attr( $field_label ); ?>"
				:field_name="'<?php echo esc_attr( $field_name ); ?>'"
				:field_id="'<?php echo esc_attr( $field_id ); ?>'"
				:field_nonce="'<?php echo esc_attr( wp_create_nonce( 'stm_lms_send_test_email_ajax' ) ); ?>'"
				:field_ajax="'<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>'"
				:field_value="<?php echo esc_attr( $field_value ); ?>">
	</send_email>

</div>
