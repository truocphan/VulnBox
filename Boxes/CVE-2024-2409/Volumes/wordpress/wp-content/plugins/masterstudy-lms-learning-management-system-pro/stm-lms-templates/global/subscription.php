<?php
stm_lms_register_style( 'membership' );

$sub = STM_LMS_Subscriptions::user_subscriptions();

if ( ! empty( $sub->course_number ) ) :
	$sub->course_id = get_the_ID();

	$sub_info = array(
		'course_id'     => get_the_ID(),
		'name'          => $sub->name,
		'course_number' => $sub->course_number,
		'used_quotas'   => $sub->used_quotas,
		'quotas_left'   => $sub->quotas_left,
	);
	?>
	<span class="or heading_font"><?php esc_html_e( '- Or -', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
	<button type="button"
			data-lms-params='<?php echo wp_json_encode( $sub_info ); ?>'
			class="btn btn-default btn-outline"
			data-target=".stm-lms-use-subscription"
			id="stm_lms_buy_button_subscription"
			data-lms-modal="use_subscription">
		<span><?php esc_html_e( 'Enroll with Membership', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
	</button>
<?php else : ?>
	<span class="or heading_font"><?php esc_html_e( '- Or -', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
	<a href="<?php echo esc_url( STM_LMS_Subscriptions::level_url() ); ?>" id="stm_lms_buy_button_subscription" class="btn btn-default btn-subscription btn-outline">
		<span><?php esc_html_e( 'Enroll with Membership', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
	</a>
<?php endif; ?>
