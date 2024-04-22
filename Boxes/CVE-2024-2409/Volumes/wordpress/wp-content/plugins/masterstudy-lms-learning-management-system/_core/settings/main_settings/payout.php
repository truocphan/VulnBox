<?php

function stm_lms_settings_payout_section() {
	$payouts = array(
		'name'   => esc_html__( 'Payout', 'masterstudy-lms-learning-management-system' ),
		'label'  => esc_html__( 'Payout Settings', 'masterstudy-lms-learning-management-system' ),
		'icon'   => 'fas fa-hand-holding-usd',
		'fields' => array(
			'pro_banner' => array(
				'type'  => 'pro_banner',
				'label' => esc_html__( 'Payouts', 'masterstudy-lms-learning-management-system' ),
				'img'   => STM_LMS_URL . 'assets/img/pro-features/payouts.png',
				'desc'  => esc_html__( 'Make paying instructors easier with automated payouts to ensure timely and hassle-free earnings.', 'masterstudy-lms-learning-management-system' ),
				'hint'  => esc_html__( 'Automate', 'masterstudy-lms-learning-management-system' ),
			),
		),
	);

	if ( STM_LMS_Helpers::is_pro() ) {
		$payouts['fields']['payout'] = array(
			'pro'     => true,
			'pro_url' => admin_url( 'admin.php?page=stm-lms-go-pro' ),
			'type'    => 'payout',
			'label'   => esc_html__( 'Masterstudy LMS PRO Payout', 'masterstudy-lms-learning-management-system' ),
		);
	}

	return $payouts;
}
