<?php
function stm_lms_settings_general_section() {
	return array(
		'name'   => esc_html__( 'General', 'masterstudy-lms-learning-management-system' ),
		'label'  => esc_html__( 'General Settings', 'masterstudy-lms-learning-management-system' ),
		'icon'   => 'fas fa-sliders-h',
		'fields' => array(
			/*GROUP STARTED*/
			'main_color'            => array(
				'group'       => 'started',
				'type'        => 'color',
				'label'       => esc_html__( 'Main color', 'masterstudy-lms-learning-management-system' ),
				'columns'     => '33',
				'group_title' => esc_html__( 'Colors', 'masterstudy-lms-learning-management-system' ),
			),
			'secondary_color'       => array(
				'group'   => 'ended',
				'type'    => 'color',
				'label'   => esc_html__( 'Secondary color', 'masterstudy-lms-learning-management-system' ),
				'columns' => '33',
			),
			/*GROUP ENDED*/

			/*GROUP STARTED*/
			'accent_color'          => array(
				'group'       => 'started',
				'type'        => 'color',
				'value'       => 'rgba(34,122,255,1)',
				'label'       => esc_html__( 'Accent', 'masterstudy-lms-learning-management-system' ),
				'columns'     => '33',
				'group_title' => esc_html__( 'Base colors*', 'masterstudy-lms-learning-management-system' ),
			),
			'danger_color'          => array(
				'type'    => 'color',
				'value'   => 'rgba(255,57,69,1)',
				'label'   => esc_html__( 'Danger', 'masterstudy-lms-learning-management-system' ),
				'columns' => '33',
			),
			'warning_color'         => array(
				'type'    => 'color',
				'value'   => 'rgba(255,168,0,1)',
				'label'   => esc_html__( 'Warning', 'masterstudy-lms-learning-management-system' ),
				'columns' => '33',
			),
			'success_color'         => array(
				'type'    => 'color',
				'value'   => 'rgba(97,204,47,1)',
				'label'   => esc_html__( 'Success', 'masterstudy-lms-learning-management-system' ),
				'columns' => '33',
			),
			'base_colors_info'      => array(
				'group'       => 'ended',
				'type'        => 'notification_message',
				'description' => esc_html__( '* These colors will be applied to Course Player pages, Authorization pages and popups. In future updates, they will be applied to all pages and Accent color will replace Main color.', 'masterstudy-lms-learning-management-system' ),
			),
			/*GROUP ENDED*/

			/*GROUP STARTED*/
			'currency_symbol'       => array(
				'group'       => 'started',
				'type'        => 'text',
				'label'       => esc_html__( 'Currency symbol', 'masterstudy-lms-learning-management-system' ),
				'columns'     => '50',
				'group_title' => esc_html__( 'Currency', 'masterstudy-lms-learning-management-system' ),
				'description' => esc_html__( 'Put the currency symbol to be shown on the catalog and to get payments in.', 'masterstudy-lms-learning-management-system' ),
			),
			'currency_position'     => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Currency position', 'masterstudy-lms-learning-management-system' ),
				'value'       => 'left',
				'options'     => array(
					'left'  => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
					'right' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
				),
				'columns'     => '50',
				'description' => esc_html__( 'Choose the position to place the currency symbol', 'masterstudy-lms-learning-management-system' ),
			),
			'currency_thousands'    => array(
				'type'        => 'text',
				'label'       => esc_html__( 'Thousands separator', 'masterstudy-lms-learning-management-system' ),
				'value'       => ',',
				'columns'     => '33',
				'description' => esc_html__( 'Put the symbol to separate groups of thousands', 'masterstudy-lms-learning-management-system' ),
			),
			'currency_decimals'     => array(
				'type'        => 'text',
				'label'       => esc_html__( 'Decimals separator', 'masterstudy-lms-learning-management-system' ),
				'value'       => '.',
				'columns'     => '33',
				'description' => esc_html__( 'Put the symbol to indicate decimals, e.g. 12.45', 'masterstudy-lms-learning-management-system' ),
			),
			'decimals_num'          => array(
				'group'       => 'ended',
				'type'        => 'number',
				'label'       => esc_html__( 'Number of fractional numbers allowed', 'masterstudy-lms-learning-management-system' ),
				'value'       => 2,
				'columns'     => '33',
				'description' => esc_html__( 'Define the number of fractional numbers allowed after the decimal symbol, e.g. 2 for 7.49', 'masterstudy-lms-learning-management-system' ),
			),
			/*GROUP ENDED*/
			'wocommerce_checkout'   => array(
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Enable WooCommerce Checkout', 'masterstudy-lms-learning-management-system' ),
				'hint'    => esc_html__( 'Note, you need to install WooCommerce, and set Cart and Checkout Pages', 'masterstudy-lms-learning-management-system' ),
				'pro'     => true,
				'pro_url' => admin_url( 'admin.php?page=stm-lms-go-pro&source=wocommerce-checkout-settings' ),
			),
			'guest_checkout'        => array(
				'type'  => 'checkbox',
				'label' => esc_html__( 'Enable Guest Checkout', 'masterstudy-lms-learning-management-system' ),
			),
			'pro_banner_woo'        => array(
				'type'  => 'pro_banner',
				'label' => esc_html__( 'Woocommerce Checkout', 'masterstudy-lms-learning-management-system' ),
				'img'   => STM_LMS_URL . 'assets/img/pro-features/woocommerce-checkout.png',
				'desc'  => esc_html__( 'Upgrade to Pro now and streamline your checkout process to boost your online course sales.', 'masterstudy-lms-learning-management-system' ),
				'value' => STM_LMS_Helpers::is_pro() ? '' : 'pro_banner',
			),
			'guest_checkout_notice' => array(
				'type'         => 'notice_banner',
				'label'        => esc_html__( 'Required to enable guest checkout in WooCommerce', 'masterstudy-lms-learning-management-system' ),
				'dependency'   => array(
					array(
						'key'   => 'wocommerce_checkout',
						'value' => 'not_empty',
					),
					array(
						'key'   => 'guest_checkout',
						'value' => 'not_empty',
					),
				),
				'dependencies' => '&&',
			),
			'author_fee'            => array(
				'type'        => 'number',
				'label'       => esc_html__( 'Instructor earnings (%)', 'masterstudy-lms-learning-management-system' ),
				'value'       => '10',
				'pro'         => true,
				'pro_url'     => admin_url( 'admin.php?page=stm-lms-go-pro&source=instructor-earnings-settings' ),
				'description' => esc_html__( 'Put the percentage instructors will get from sales', 'masterstudy-lms-learning-management-system' ),
			),
			'courses_featured_num'  => array(
				'type'    => 'number',
				'label'   => esc_html__( 'Number of featured courses', 'masterstudy-lms-learning-management-system' ),
				'value'   => 1,
				'pro'     => true,
				'pro_url' => admin_url( 'admin.php?page=stm-lms-go-pro' ),
			),
			'deny_instructor_admin' => array(
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Restrict instructors from accessing the admin panel', 'masterstudy-lms-learning-management-system' ),
				'description' => esc_html__( 'Enable this to restrict access for instructors to the admin panel. They will be redirected to their account pages.', 'masterstudy-lms-learning-management-system' ),
			),
			'ms_plugin_preloader'   => array(
				'type'  => 'checkbox',
				'label' => esc_html__( 'Loading animation', 'masterstudy-lms-learning-management-system' ),
				'value' => false,
			),
		),
	);
}
