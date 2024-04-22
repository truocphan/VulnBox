<?php
/**
 * @var $bundle_id
 * @var $bundle_data
 */

wp_enqueue_editor();

$post_content = ( ! empty( $bundle_data->post_content ) ) ? $bundle_data->post_content : '';
?>

<div class="stm_lms_my_bundle__description">

	<h4 class="stm_lms_my_bundle__label">
		<?php esc_html_e( 'Bundle description', 'masterstudy-lms-learning-management-system-pro' ); ?>
	</h4>

	<textarea name="stm_lms_bundle_name_<?php echo esc_attr( $bundle_id ); ?>" id="stm_lms_bundle_name_<?php echo esc_attr( $bundle_id ); ?>"><?php echo wp_kses_post( $post_content ); ?></textarea>

</div>
