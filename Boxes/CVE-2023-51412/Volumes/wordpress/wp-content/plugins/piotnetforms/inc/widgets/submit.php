<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Piotnetforms_Submit extends Base_Widget_Piotnetforms {

	protected $is_add_conditional_logic = false;
	
	public function get_type() {
		return 'submit';
	}

	public function get_class_name() {
		return 'Piotnetforms_Submit';
	}

	public function get_title() {
		return 'Submit';
	}

	public function get_icon() {
		return [
			'type' => 'image',
			'value' => plugin_dir_url( __FILE__ ) . '../../assets/icons/i-submit.svg',
		];
	}

	public function get_categories() {
		return [ 'piotnetforms' ];
	}

	public function get_keywords() {
		return [ 'text' ];
	}

	public static function get_button_sizes() {
		return [
			'xs' => __( 'Extra Small', 'piotnetforms' ),
			'sm' => __( 'Small', 'piotnetforms' ),
			'md' => __( 'Medium', 'piotnetforms' ),
			'lg' => __( 'Large', 'piotnetforms' ),
			'xl' => __( 'Extra Large', 'piotnetforms' ),
		];
	}
	public function register_controls() {
		$this->start_tab( 'settings', 'Settings' );

		$this->start_section( 'button_settings_section', 'Button' );
		$this->add_button_setting_controls();
		$this->start_section( 'action_after_submit_settings_section', 'Actions After Submit' );
		$this->add_action_after_submit();

		$this->start_section(
			'email_settings_section',
			'Email',
			[
				'condition' => [
					'submit_actions' => 'email',
				],
			]
		);
		$this->add_email_setting_controls();
		$this->start_section(
			'email_2_settings_section',
			'Email2',
			[
				'condition' => [
					'submit_actions' => 'email2',
				],
			]
		);
		$this->add_email_2_setting_controls();

		$this->start_section( 'form_options_settings_section', 'Custom Messages' );
		$this->form_options_setting_controls();

		//Tab Style
		$this->start_tab( 'style', 'Style' );
		$this->start_section( 'button_style_section', 'Button' );
		$this->add_button_style_controls();
		$this->start_section( 'message_style_section', 'Messages' );
		$this->add_message_style_controls();

		// $this->start_tab( 'style', 'Style' );
		// $this->start_section( 'text_styles_section', 'Style' );
		// $this->add_style_controls();

		$this->add_advanced_tab();

		return $this->structure;
	}

	private function add_button_setting_controls() {
		$this->add_control(
			'form_id',
			[
				'type'        => 'text',
				'description' => __( 'Enter the same form id for all fields in a form, with latin character and no space. E.g order_form', 'piotnetforms' ),
				'label'       => __( 'Form ID* (Required)', 'piotnetforms' ),
			]
		);
		$this->add_control(
			'remove_empty_form_input_fields',
			[
				'type'         => 'switch',
				'label'        => __( 'Remove Empty Form Input Fields', 'piotnetforms' ),
				'value'        => '',
				'label_on'     => 'Yes',
				'label_off'    => 'No',
				'return_value' => 'yes',
			]
		);
		// $this->add_control(
		// 	'button_type',
		// 	[
		// 		'label'   => __( 'Type', 'piotnetforms' ),
		// 		'type'    => 'select',
		// 		'value'   => '',
		// 		'options' => [
		// 			''        => __( 'Default', 'piotnetforms' ),
		// 			'info'    => __( 'Info', 'piotnetforms' ),
		// 			'success' => __( 'Success', 'piotnetforms' ),
		// 			'warning' => __( 'Warning', 'piotnetforms' ),
		// 			'danger'  => __( 'Danger', 'piotnetforms' ),
		// 		],
		// 	]
		// );
		$this->add_control(
			'text',
			[
				'label'       => __( 'Text', 'piotnetforms' ),
				'type'        => 'text',
				'value'       => __( 'Submit', 'piotnetforms' ),
			]
		);
		$this->add_responsive_control(
			'align',
			[
				'label'   => __( 'Alignment', 'piotnetforms' ),
				'label_block'    => true,
				'type'    => 'select',
				'options' => [
					''        => __( 'Default', 'piotnetforms' ),
					'left'    => __( 'Left', 'piotnetforms' ),
					'center'  => __( 'Center', 'piotnetforms' ),
					'right'   => __( 'Right', 'piotnetforms' ),
					'justify' => __( 'Justify', 'piotnetforms' ),
				],
				'prefix_class' => 'piotnetforms%s-align-',
				'default' => '',
				'render_type' => 'both',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				]
			]
		);
		// $this->add_control(
		// 	'size',
		// 	[
		// 		'label'   => __( 'Size', 'piotnetforms' ),
		// 		'type'    => 'select',
		// 		'value'   => 'sm',
		// 		'options' => self::get_button_sizes(),
		// 	]
		// );
		$this->add_control(
			'icon',
			[
				'label'          => __( 'Icon (Pro)', 'piotnetforms' ),
				'type'           => 'icon',
				'label_block'    => true,
				'value'          => '',
				'options_source' => 'fontawesome',
			]
		);
	}
	private function add_action_after_submit() {
		$actions         = [
			[
				'name'  => 'email',
				'label' => 'Email',
			],
			[
				'name'  => 'email2',
				'label' => 'Email 2',
			],
			[
				'name'  => 'booking',
				'label' => 'Booking (Pro)',
			],
			[
				'name'  => 'redirect',
				'label' => 'Redirect (Pro)',
			],
			[
				'name'  => 'register',
				'label' => 'Register (Pro)',
			],
			[
				'name'  => 'login',
				'label' => 'Login (Pro)',
			],
			[
				'name'  => 'update_user_profile',
				'label' => 'Update User Profile (Pro)',
			],
			[
				'name'  => 'webhook',
				'label' => 'Webhook (Pro)',
			],
			[
				'name'  => 'remote_request',
				'label' => 'Remote Request (Pro)',
			],
			// [
			// 	'name'  => 'popup',
			// 	'label' => 'Popup',
			// ],
			// [
			// 	'name'  => 'open_popup',
			// 	'label' => 'Open Popup',
			// ],
			// [
			// 	'name'  => 'close_popup',
			// 	'label' => 'Close Popup',
			// ],
			[
				'name'  => 'submit_post',
				'label' => 'Submit Post (Pro)',
			],
			[
				'name'  => 'woocommerce_add_to_cart',
				'label' => 'Woocommerce Add To Cart (Pro)',
			],
			[
				'name'  => 'mailchimp_v3',
				'label' => 'MailChimp (Pro)',
			],
			[
				'name'  => 'mailerlite',
				'label' => 'MailerLite (Pro)',
			],
			[
				'name'  => 'activecampaign',
				'label' => 'ActiveCampaign (Pro)',
			],
			[
				'name'  => 'pdfgenerator',
				'label' => 'PDF Generator (Pro)',
			],
			[
				'name'  => 'getresponse',
				'label' => 'Getresponse (Pro)',
			],
			[
				'name'  => 'mailpoet',
				'label' => 'Mailpoet (Pro)',
			],
			[
				'name'  => 'zohocrm',
				'label' => 'Zoho CRM (Pro)',
			],
		];
		$actions_options = [];

		foreach ( $actions as $action ) {
			$actions_options[ $action['name'] ] = $action['label'];
		}
		$this->add_control(
			'submit_actions',
			[
				'label'       => __( 'Add Action', 'piotnetforms' ),
				'type'        => 'select2',
				'multiple'    => true,
				'options'     => $actions_options,
				'label_block' => true,
				'value'       => [
					'email',
				],
				'description' => __( 'Add actions that will be performed after a visitor submits the form (e.g. send an email notification). Choosing an action will add its setting below.', 'piotnetforms' ),
			]
		);

	}

	private function add_style_controls() {
		$this->add_control(
			'text_color',
			[
				'type'      => 'color',
				'label'     => 'Text Color',
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_text_typography_controls(
			'text_typography',
			[
				'selectors' => '{{WRAPPER}}',
			]
		);
	}

	private function add_email_setting_controls() {
		$this->add_control(
			'submit_id_shortcode',
			[
				'label'   => __( 'Submit ID Shortcode', 'piotnetforms' ),
				'type'    => 'html',
				'classes' => 'forms-field-shortcode',
				'raw'     => '<input class="piotnetforms-field-shortcode" value="[submit_id]" readonly />',
			]
		);
		$this->add_control(
			'email_to',
			[
				'label'       => __( 'To', 'piotnetforms' ),
				'type'        => 'text',
				'default'     => get_option( 'admin_email' ),
				'placeholder' => get_option( 'admin_email' ),
				'label_block' => true,
				'title'       => __( 'Separate emails with commas', 'piotnetforms' ),
				'render_type' => 'none',
			]
		);
		/* translators: %s: Site title. */
		$default_message = sprintf( __( 'New message from "%s"', 'piotnetforms' ), get_option( 'blogname' ) );
		$this->add_control(
			'email_subject',
			[
				'label'       => __( 'Subject', 'piotnetforms' ),
				'type'        => 'text',
				'value'       => $default_message,
				'placeholder' => $default_message,
				'label_block' => true,
				'render_type' => 'none',
			]
		);
		$this->add_control(
			'email_content',
			[
				'label'       => __( 'Message', 'piotnetforms' ),
				'type'        => 'textarea',
				'value'       => '[all-fields]',
				'placeholder' => '[all-fields]',
				'description' => __( 'By default, all form fields are sent via shortcode: <code>[all-fields]</code>. Want to customize sent fields? Copy the shortcode that appears inside the field and paste it above. Enter this if you want to customize sent fields and remove line if field empty [field id="your_field_id"][remove_line_if_field_empty]', 'piotnetforms' ),
				'label_block' => true,
				'render_type' => 'none',
			]
		);
		// $site_domain = Utils::get_site_domain();
		// $site_domain = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
		// $this->add_control(
		// 	'email_from',
		// 	[
		// 		'label'       => __( 'From Email', 'piotnetforms' ),
		// 		'type'        => 'text',
		// 		'value'       => 'email@' . $site_domain,
		// 		'render_type' => 'none',
		// 	]
		// );
		$this->add_control(
			'email_from_name',
			[
				'label'       => __( 'From Name', 'piotnetforms' ),
				'type'        => 'text',
				'value'       => get_bloginfo( 'name' ),
				'render_type' => 'none',
			]
		);
		$this->add_control(
			'email_reply_to',
			[
				'label'       => __( 'Reply-To', 'piotnetforms' ),
				'type'        => 'text',
				'options'     => [
					'' => '',
				],
				'render_type' => 'none',
			]
		);
		$this->add_control(
			'email_to_cc',
			[
				'label'       => __( 'Cc', 'piotnetforms' ),
				'type'        => 'text',
				'value'       => '',
				'title'       => __( 'Separate emails with commas', 'piotnetforms' ),
				'render_type' => 'none',
			]
		);
		$this->add_control(
			'email_to_bcc',
			[
				'label'       => __( 'Bcc', 'piotnetforms' ),
				'type'        => 'text',
				'value'       => '',
				'title'       => __( 'Separate emails with commas', 'piotnetforms' ),
				'render_type' => 'none',
			]
		);
		$this->add_control(
			'form_metadata',
			[
				'label'       => __( 'Meta Data', 'piotnetforms' ),
				'type'        => 'select2',
				'label_block' => true,
				'value'       => [
					'date',
					'time',
					'page_url',
					'user_agent',
					'remote_ip',
				],
				'options'     => [
					'date'       => __( 'Date', 'piotnetforms' ),
					'time'       => __( 'Time', 'piotnetforms' ),
					'page_url'   => __( 'Page URL', 'piotnetforms' ),
					'user_agent' => __( 'User Agent', 'piotnetforms' ),
					'remote_ip'  => __( 'Remote IP', 'piotnetforms' ),
				],
				'render_type' => 'none',
			]
		);
	}
	private function add_email_2_setting_controls() {
		$this->add_control(
			'submit_id_shortcode_2',
			[
				'label'   => __( 'Submit ID Shortcode', 'piotnetforms' ),
				'type'    => 'html',
				'classes' => 'forms-field-shortcode',
				'raw'     => '<input class="piotnetforms-field-shortcode" value="[submit_id]" readonly />',
			]
		);
		$this->add_control(
			'email_to_2',
			[
				'label'       => __( 'To', 'piotnetforms' ),
				'type'        => 'text',
				'value'       => get_option( 'admin_email' ),
				'placeholder' => get_option( 'admin_email' ),
				'label_block' => true,
				'title'       => __( 'Separate emails with commas', 'piotnetforms' ),
				'render_type' => 'none',
			]
		);
		/* translators: %s: Site title. */
		$default_message = sprintf( __( 'New message from "%s"', 'piotnetforms' ), get_option( 'blogname' ) );
		$this->add_control(
			'email_subject_2',
			[
				'label'       => __( 'Subject', 'piotnetforms' ),
				'type'        => 'text',
				'value'       => $default_message,
				'placeholder' => $default_message,
				'label_block' => true,
				'render_type' => 'none',
			]
		);
		$this->add_control(
			'email_content_2',
			[
				'label'       => __( 'Message', 'piotnetforms' ),
				'type'        => 'textarea',
				'value'       => '[all-fields]',
				'placeholder' => '[all-fields]',
				'description' => __( 'By default, all form fields are sent via shortcode: <code>[all-fields]</code>. Want to customize sent fields? Copy the shortcode that appears inside the field and paste it above. Enter this if you want to customize sent fields and remove line if field empty [field id="your_field_id"][remove_line_if_field_empty]', 'piotnetforms' ),
				'label_block' => true,
				'render_type' => 'none',
			]
		);
		// $this->add_control(
		// 	'email_from_2',
		// 	[
		// 		'label'       => __( 'From Email', 'piotnetforms' ),
		// 		'type'        => 'text',
		// 		'value'       => 'email@' . $site_domain,
		// 		'render_type' => 'none',
		// 	]
		// );
		$this->add_control(
			'email_from_name_2',
			[
				'label'       => __( 'From Name', 'piotnetforms' ),
				'type'        => 'text',
				'value'       => get_bloginfo( 'name' ),
				'render_type' => 'none',
			]
		);
		$this->add_control(
			'email_reply_to_2',
			[
				'label'       => __( 'Reply-To', 'piotnetforms' ),
				'type'        => 'text',
				'options'     => [
					'' => '',
				],
				'render_type' => 'none',
			]
		);
		$this->add_control(
			'email_to_cc_2',
			[
				'label'       => __( 'Cc', 'piotnetforms' ),
				'type'        => 'text',
				'value'       => '',
				'title'       => __( 'Separate emails with commas', 'piotnetforms' ),
				'render_type' => 'none',
			]
		);
		$this->add_control(
			'email_to_bcc_2',
			[
				'label'       => __( 'Bcc', 'piotnetforms' ),
				'type'        => 'text',
				'value'       => '',
				'title'       => __( 'Separate emails with commas', 'piotnetforms' ),
				'render_type' => 'none',
			]
		);
	}

	private function form_options_setting_controls() {
		$this->add_control(
			'success_message',
			[
				'label'       => __( 'Success Message', 'piotnetforms' ),
				'type'        => 'text',
				'value'       => __( 'The form was sent successfully.', 'piotnetforms' ),
				'placeholder' => __( 'The form was sent successfully.', 'piotnetforms' ),
				'label_block' => true,
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'error_message',
			[
				'label'       => __( 'Error Message', 'piotnetforms' ),
				'type'        => 'text',
				'default'     => __( 'An error occured.', 'piotnetforms' ),
				'placeholder' => __( 'An error occured.', 'piotnetforms' ),
				'label_block' => true,
				'render_type' => 'none',
			]
		);
		$this->add_control(
			'required_field_message',
			[
				'label'       => __( 'Required Message', 'piotnetforms' ),
				'type'        => 'text',
				'value'       => __( 'This field is required.', 'piotnetforms' ),
				'placeholder' => __( 'This field is required.', 'piotnetforms' ),
				'label_block' => true,
				'render_type' => 'none',
			]
		);
		$this->add_control(
			'invalid_message',
			[
				'label'       => __( 'Invalid Message', 'piotnetforms' ),
				'type'        => 'text',
				'value'       => __( "There's something wrong. The form is invalid.", 'piotnetforms' ),
				'placeholder' => __( "There's something wrong. The form is invalid.", 'piotnetforms' ),
				'label_block' => true,
				'render_type' => 'none',
			]
		);
	}


	private function add_button_style_controls() {
		$this->add_text_typography_controls(
			'typography',
			[
				'selectors' => '{{WRAPPER}} a.piotnetforms-button, {{WRAPPER}} .piotnetforms-button',
			]
		);
		$this->add_control(
			'',
			[
				'type' => 'heading-tab',
				'tabs' => [
					[
						'name'   => 'submit_button_style_normal_tab',
						'title'  => __( 'NORMAL', 'piotnetforms' ),
						'active' => true,
					],
					[
						'name'  => 'submit_button_style_hover_tab',
						'title' => __( 'HOVER', 'piotnetforms' ),
					],
				],
			]
		);

		$normal_controls = $this->tab_button_style_controls(
			'style_normal',
			[
				'selectors' => '{{WRAPPER}} a.piotnetforms-button, {{WRAPPER}} .piotnetforms-button',
			]
		);
		$this->add_control(
			'submit_button_style_normal_tab',
			[
				'type'           => 'content-tab',
				'label'          => __( 'Normal', 'piotnetforms' ),
				'value'          => '',
				'active'         => true,
				'controls'       => $normal_controls,
				'controls_query' => '.piotnet-start-controls-tab',
			]
		);

		$hover_controls = $this->tab_button_style_controls(
			'style_hover',
			[
				'selectors' => '{{WRAPPER}} a.piotnetforms-button:hover, {{WRAPPER}} .piotnetforms-button:hover, {{WRAPPER}} a.piotnetforms-button:focus, {{WRAPPER}} .piotnetforms-button:focus',
			]
		);
		$this->add_control(
			'submit_button_style_hover_tab',
			[
				'type'           => 'content-tab',
				'label'          => __( 'Hover', 'piotnetforms' ),
				'value'          => '',
				'controls'       => $hover_controls,
				'controls_query' => '.piotnet-start-controls-tab',
			]
		);
	}
	private function tab_button_style_controls( string $name, $args = [] ) {
		$wrapper = isset( $args['selectors'] ) ? $args['selectors'] : '{{WRAPPER}}';
		$this->new_group_controls();
		$this->add_control(
			$name . 'button_text_color',
			[
				'label'     => __( 'Text Color', 'piotnetforms' ),
				'type'      => 'color',
				'value'     => '',
				'selectors' => [
					$wrapper => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			$name . 'background_color',
			[
				'label'     => __( 'Background Color', 'piotnetforms' ),
				'type'      => 'color',
				'selectors' => [
					$wrapper => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			$name.'_button_border_type',
			[
				'type'      => 'select',
				'label'     => __( 'Border Type', 'piotnetforms' ),
				'value'     => '',
				'options'   => [
					''       => 'None',
					'solid'  => 'Solid',
					'double' => 'Double',
					'dotted' => 'Dotted',
					'dashed' => 'Dashed',
					'groove' => 'Groove',
				],
				'selectors' => [
					$wrapper => 'border-style:{{VALUE}};',
				],
			]
		);
		$this->add_control(
			$name.'_button_border_color',
			[
				'type'        => 'color',
				'label'       => __( 'Border Color', 'piotnetforms' ),
				'value'       => '',
				'label_block' => true,
				'selectors'   => [
					$wrapper => 'border-color: {{VALUE}};',
				],
				'conditions'  => [
					[
						'name'     => $name.'_button_border_type',
						'operator' => '!=',
						'value'    => '',
					],
				],
			]
		);
		$this->add_responsive_control(
			$name.'_button_border_width',
			[
				'type'        => 'dimensions',
				'label'       => __( 'Border Width', 'piotnetforms' ),
				'value'       => [
					'unit'   => 'px',
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'label_block' => true,
				'size_units'  => [ 'px', '%', 'em' ],
				'selectors'   => [
					$wrapper => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions'  => [
					[
						'name'     => $name.'_button_border_type',
						'operator' => '!=',
						'value'    => '',
					],
				],
			]
		);

		$this->add_control(
			$name . 'border_radius',
			[
				'label'       => __( 'Border Radius', 'piotnetforms' ),
				'type'        => 'dimensions',
				'value'       => [
					'unit'   => 'px',
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'label_block' => true,
				'size_units'  => [ 'px', '%' ],
				'selectors'   => [
					$wrapper => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			$name . 'button_box_shadow',
			[
				'type'        => 'box-shadow',
				'label'       => __( 'Box Shadow', 'piotnetforms' ),
				'value'       => '',
				'label_block' => false,
				'render_type' => 'none',
				'selectors'   => [
					$wrapper => 'box-shadow: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			$name . 'text_padding',
			[
				'label'       => __( 'Padding', 'piotnetforms' ),
				'type'        => 'dimensions',
				'label_block' => false,
				'value'       => [
					'unit'   => 'px',
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					$wrapper => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		return $this->get_group_controls();
	}
	private function add_message_style_controls() {
		$this->add_text_typography_controls(
			'message_typography',
			[
				'selectors' => '{{WRAPPER}} .piotnetforms-message',
			]
		);
		$this->add_control(
			'success_message_color',
			[
				'label'     => __( 'Success Message Color', 'piotnetforms' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .piotnetforms-message.piotnetforms-message-success' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'error_message_color',
			[
				'label'     => __( 'Error Message Color', 'piotnetforms' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .piotnetforms-message.piotnetforms-message-danger' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'inline_message_color',
			[
				'label'     => __( 'Inline Message Color', 'piotnetforms' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .piotnetforms-message.piotnetforms-help-inline' => 'color: {{VALUE}};',
				],
			]
		);
	}
	public function render() {
		$settings = $this->settings;
		$editor = ( isset($_GET['action']) && $_GET['action'] == 'piotnetforms' ) ? true : false;

		$this->add_render_attribute( 'wrapper', 'class', 'piotnetforms-submit' );
		$this->add_render_attribute( 'wrapper', 'class', 'piotnetforms-button-wrapper' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'button', 'href', $settings['link']['url'] );
			$this->add_render_attribute( 'button', 'class', 'piotnetforms-button-link' );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'button', 'target', '_blank' );
			}

			if ( $settings['link']['nofollow'] ) {
				$this->add_render_attribute( 'button', 'rel', 'nofollow' );
			}
		}

		if (! empty( $settings['align_responsive_desktop'] )) {
			if ($settings['align_responsive_desktop'] == 'justify') {
				$this->add_render_attribute( 'button', 'class', 'piotnetforms-button--justify' );
			}
		}

		$this->add_render_attribute( 'button', 'class', 'piotnetforms-button' );
		$this->add_render_attribute( 'button', 'role', 'button' );

		$this->add_render_attribute( 'button', 'data-piotnetforms-required-text', $settings['required_field_message'] );

		if ( ! empty( $settings['button_css_id'] ) ) {
			$this->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
		}

		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'piotnetforms-size-' . $settings['size'] );
		}

		if ( $settings['form_id'] ) {
			$this->add_render_attribute( 'button', 'data-piotnetforms-submit-form-id', $settings['form_id'] );
		}

		if ( !empty(get_option('piotnetforms-recaptcha-site-key')) && !empty(get_option('piotnetforms-recaptcha-secret-key')) && !empty($settings['piotnetforms_recaptcha_enable']) ) {
			$this->add_render_attribute( 'button', 'data-piotnetforms-submit-recaptcha', esc_attr( get_option('piotnetforms-recaptcha-site-key') ) );
		}

		if (!empty($settings['piotnetforms_conditional_logic_form_list'])) {
			$list_conditional = $settings['piotnetforms_conditional_logic_form_list'];
			if( !empty($settings['piotnetforms_conditional_logic_form_enable']) && !empty($list_conditional[0]['piotnetforms_conditional_logic_form_if']) && !empty($list_conditional[0]['piotnetforms_conditional_logic_form_comparison_operators']) ) {
				$this->add_render_attribute( 'button', [
					'data-piotnetforms-conditional-logic' => str_replace('\"]','', str_replace('[field id=\"','', json_encode($list_conditional))),
					'data-piotnetforms-conditional-logic-speed' => $settings['piotnetforms_conditional_logic_form_speed'],
					'data-piotnetforms-conditional-logic-easing' => $settings['piotnetforms_conditional_logic_form_easing'],
				] );
			}
		}

		if (!empty($settings['form_abandonment_enable'])) {
			$this->add_render_attribute( 'wrapper', [
				'data-piotnetforms-abandonment' => '',
			] );
		}

		?>

		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>

		<?php

		if(in_array('update_user_profile', $settings['submit_actions'])) {
			if (is_user_logged_in()) {
				if (!empty($settings['update_user_meta_list'])) {
					$update_user_profile = array();
					$user_id = get_current_user_id();

					foreach ($settings['update_user_meta_list'] as $user_meta) {
						if (!empty($user_meta['update_user_meta']) && !empty($user_meta['update_user_meta_field_shortcode'])) {

							$user_meta_key = $user_meta['update_user_meta'];
							$user_meta_value = '';

							if ($user_meta['update_user_meta'] == 'meta' || $user_meta['update_user_meta'] == 'acf') {
								if (!empty($user_meta['update_user_meta_key'])) {
									$user_meta_key = $user_meta['update_user_meta_key'];

									if ($user_meta['update_user_meta'] == 'meta') {
										$user_meta_value = get_user_meta( $user_id, $user_meta_key, true );
									} else {
										$user_meta_value = get_field( $user_meta_key, 'user_' . $user_id );
									}
								}
							} else {
								$user_meta_value = get_user_meta( $user_id, $user_meta_key, true );
							}

							if ( $user_meta['update_user_meta'] == 'acf' ) {
								$meta_type = $user_meta['update_user_meta_type'];

								if ($meta_type == 'image') {
									if (!empty($user_meta_value)) {
										$user_meta_value = $user_meta_value['url'];
									}
								}

								if ($meta_type == 'gallery') {
									if (is_array($user_meta_value)) {
										$images = '';
										foreach ($user_meta_value as $item) {
											if (is_array($item)) {
												if (isset($item['url'])) {
													$images .= $item['url'] . ',';
												}
											}
										}
										$user_meta_value = rtrim($images, ',');
									}
								}
							}

							if ($user_meta_key != 'password') {
								$update_user_profile[] = array(
									'user_meta_key' => $user_meta_key,
									'user_meta_value' => $user_meta_value,
									'field_id' => $user_meta['update_user_meta_field_shortcode'],
								);
							}
						}
					}

					$this->add_render_attribute( 'button', [
						'data-piotnetforms-submit-update-user-profile' => str_replace('\"]','', str_replace('[field id=\"','', json_encode($update_user_profile))),
					] );
				}
			}
		}

		if( !empty($settings['paypal_enable']) && isset($settings['form_id'])) {
			$this->add_render_attribute( 'button', [
				'data-piotnetforms-paypal-submit' => '',
				'data-piotnetforms-paypal-submit-enable' => '',
			] );
		}

		if( !empty($settings['piotnetforms_stripe_enable']) ) {

			$this->add_render_attribute( 'button', [
				'data-piotnetforms-stripe-submit' => '',
			] );

			if( !empty($settings['piotnetforms_stripe_amount']) ) {
				$this->add_render_attribute( 'button', [
					'data-piotnetforms-stripe-amount' => $settings['piotnetforms_stripe_amount'],
				] );
			}

			if( !empty($settings['piotnetforms_stripe_currency']) ) {
				$this->add_render_attribute( 'button', [
					'data-piotnetforms-stripe-currency' => $settings['piotnetforms_stripe_currency'],
				] );
			}

			if( !empty($settings['piotnetforms_stripe_amount_field_enable']) && !empty($settings['piotnetforms_stripe_amount_field']) ) {
				$this->add_render_attribute( 'button', [
					'data-piotnetforms-stripe-amount-field' => $settings['piotnetforms_stripe_amount_field'],
				] );
			}

			if( !empty($settings['piotnetforms_stripe_customer_info_field']) ) {
				$this->add_render_attribute( 'button', [
					'data-piotnetforms-stripe-customer-info-field' => $settings['piotnetforms_stripe_customer_info_field'],
				] );
			}
		}

		if( !empty($settings['woocommerce_add_to_cart_product_id']) ) {

			$this->add_render_attribute( 'button', [
				'data-piotnetforms-woocommerce-product-id' => $settings['woocommerce_add_to_cart_product_id'],
			] );
		}

		if( !empty($_GET['edit']) ) {
			$post_id = intval($_GET['edit']);
			if( is_user_logged_in() && get_post($post_id) != null ) {
				if (current_user_can( 'edit_others_posts' ) || get_current_user_id() == get_post($post_id)->post_author) {
					$sp_post_id = get_post_meta($post_id,'_submit_post_id',true);
					$form_id = get_post_meta($post_id,'_submit_button_id',true);

					if (!empty($_GET['smpid'])) {
						$sp_post_id = sanitize_text_field( $_GET['smpid'] );
					}

					if (!empty($_GET['sm'])) {
						$form_id = sanitize_text_field( $_GET['sm'] );
					}

					$form = array();

                    $data     = json_decode( get_post_meta( $sp_post_id, '_piotnetforms_data', true ), true );
                    $form['settings'] = $data['widgets'][ $form_id ]['settings'];

					if ( !empty($form)) {
						$this->add_render_attribute( 'button', [
							'data-piotnetforms-submit-post-edit' => intval($post_id),
						] );

						$submit_post_id = $post_id;

						if (isset($form['settings']['submit_post_custom_fields_list'])) {

							$sp_custom_fields = $form['settings']['submit_post_custom_fields_list'];

							if (is_array($sp_custom_fields)) {
								foreach ($sp_custom_fields as $sp_custom_field) {
									if ( !empty( $sp_custom_field['submit_post_custom_field'] ) ) {
										$custom_field_value = '';
										$meta_type = $sp_custom_field['submit_post_custom_field_type'];

										if ($meta_type == 'repeater' && function_exists('update_field') && $form['settings']['submit_post_custom_field_source'] == 'acf_field') {
											$custom_field_value = get_field($sp_custom_field['submit_post_custom_field'], $submit_post_id);
											if (!empty($custom_field_value)) {
												array_walk($custom_field_value, function (& $item) {
													foreach ($item as $key => $value) {
														$field_object = get_field_object(acf_get_field_key( $key, sanitize_text_field($_GET['edit']) ));
														if (!empty($field_object)) {
															$field_type = $field_object['type'];

															$item_value = $value;

															if ($field_type == 'image') {
																if (!empty($item_value['url'])) {
																	$item_value = $item_value['url'];
																}
															}

															if ($field_type == 'gallery') {
																if (is_array($item_value)) {
																	$images = '';
																	foreach ($item_value as $itemx) {
																		if (is_array($itemx)) {
																			$images .= $itemx['url'] . ',';
																		}
																	}
																	$item_value = rtrim($images, ',');
																}
															}

															if ($field_type == 'select' || $field_type == 'checkbox') {
																if (is_array($item_value)) {
																	$value_string = '';
																	foreach ($item_value as $itemx) {
																		$value_string .= $itemx . ',';
																	}
																	$item_value = rtrim($value_string, ',');
																}
															}

															if ($field_type == 'date') {
																$time = strtotime( $item_value );
																$item_value = date(get_option( 'date_format' ),$time);
															}

															$item[$key] = $item_value;
														}
													}
												});

												?>
													<div data-piotnetforms-repeater-value data-piotnetforms-repeater-value-id="<?php echo $sp_custom_field['submit_post_custom_field']; ?>" data-piotnetforms-repeater-value-form-id="<?php echo $settings['form_id']; ?>" style="display: none;">
														<?php echo json_encode($custom_field_value); ?>
													</div>
												<?php
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		?>
		<input type="hidden" name="post_id" value="<?php echo $this->post_id; ?>" data-piotnetforms-hidden-form-id="<?php if ( $settings['form_id'] ) {echo $settings['form_id'];} ?>"/>
		<input type="hidden" name="form_id" value="<?php echo $this->get_id(); ?>" data-piotnetforms-hidden-form-id="<?php if ( $settings['form_id'] ) {echo $settings['form_id'];} ?>"/>
		<input type="hidden" name="remote_ip" value="<?php echo $this->get_client_ip(); ?>" data-piotnetforms-hidden-form-id="<?php if ( $settings['form_id'] ) {echo $settings['form_id'];} ?>"/>

		<?php if(in_array('redirect', $settings['submit_actions'])) : ?>
			<input type="hidden" name="redirect" value="<?php echo $settings['redirect_to']; ?>" data-piotnetforms-hidden-form-id="<?php if ( $settings['form_id'] ) {echo $settings['form_id'];} ?>"/>
		<?php endif; ?>

		<?php if(in_array('popup', $settings['submit_actions'])) : ?>
			<?php if(!empty( $settings['popup_action'] ) && !empty( $settings['popup_action_popup_id'] )) : ?>
				<a href="<?php echo $this->create_popup_url($settings['popup_action_popup_id'],$settings['popup_action']); ?>" data-piotnetforms-popup data-piotnetforms-hidden-form-id="<?php if ( $settings['form_id'] ) {echo $settings['form_id'];} ?>" style="display: none;"></a>
			<?php endif; ?>
		<?php endif; ?>

		<?php if(in_array('open_popup', $settings['submit_actions'])) : ?>
			<?php if(!empty( $settings['popup_action_popup_id_open'] )) : ?>
				<a href="<?php echo $this->create_popup_url($settings['popup_action_popup_id_open'],'open'); ?>" data-piotnetforms-popup-open data-piotnetforms-hidden-form-id="<?php if ( $settings['form_id'] ) {echo $settings['form_id'];} ?>" style="display: none;"></a>
			<?php endif; ?>
		<?php endif; ?>

		<?php if(in_array('close_popup', $settings['submit_actions'])) : ?>
			<?php if(!empty( $settings['popup_action_popup_id_close'] )) : ?>
				<a href="<?php echo $this->create_popup_url($settings['popup_action_popup_id_close'],'close'); ?>" data-piotnetforms-popup-close data-piotnetforms-hidden-form-id="<?php if ( $settings['form_id'] ) {echo $settings['form_id'];} ?>" style="display: none;"></a>
			<?php endif; ?>
		<?php endif; ?>

		<div <?php echo $this->get_render_attribute_string( 'button' ); ?>>
			<?php $this->render_text(); ?>
		</div>

		<div id="piotnetforms-trigger-success-<?php if ( $settings['form_id'] ) {echo $settings['form_id'];} ?>" data-piotnetforms-trigger-success="<?php if ( $settings['form_id'] ) {echo $settings['form_id'];} ?>" style="display: none"></div>
		<div id="piotnetforms-trigger-failed-<?php if ( $settings['form_id'] ) {echo $settings['form_id'];} ?>" data-piotnetforms-trigger-failed="<?php if ( $settings['form_id'] ) {echo $settings['form_id'];} ?>" style="display: none"></div>
		<div class="piotnetforms-alert piotnetforms-alert--mail">
			<div class="piotnetforms-message piotnetforms-message-success" role="alert" data-piotnetforms-message="<?php echo $settings['success_message']; ?>"><?php echo $settings['success_message']; ?></div>
			<div class="piotnetforms-message piotnetforms-message-danger" role="alert" data-piotnetforms-message="<?php echo $settings['error_message']; ?>"><?php echo $settings['error_message']; ?></div>
			<!-- <div class="piotnetforms-message piotnetforms-help-inline" role="alert">Server error. Form not sent.</div> -->
		</div>
		</div>
		<?php
	}

	public function live_preview() {
	?>
		<%	
			var s = data.widget_settings;
			var formId = s.form_id ? s.form_id : '';

			view.add_attribute( 'wrapper', 'class', 'piotnetforms-submit' );
			view.add_attribute( 'wrapper', 'class', 'piotnetforms-button-wrapper' );

			view.add_attribute( 'button', 'class', 'piotnetforms-button' );
			view.add_attribute( 'button', 'role', 'button' );
			view.add_attribute( 'button', 'data-piotnetforms-required-text', s['required_field_message'] );
			view.add_attribute( 'button', 'data-piotnetforms-submit-form-id', formId );

			if ( s['align_responsive_desktop'] ) {
				if ( s['align_responsive_desktop'] == 'justify') {
					view.add_attribute( 'button', 'class', 'piotnetforms-button--justify' );
				}
			}

			view.add_multi_attribute({
				'content-wrapper': {
					class: 'piotnetforms-button-content-wrapper',
				},
				'icon-align': {
					class: [
						'piotnetforms-button-icon',
						'piotnetforms-align-icon-' + s['icon_align'],
					],
				},
				text: {
					class: 'piotnetforms-button-text'
				}
			});
		%>
		<div <%= view.render_attributes('wrapper') %>>
			<div <%= view.render_attributes('button') %>>
				<span <%= view.render_attributes('content-wrapper') %>>
					<span <%= view.render_attributes('text') %>><%= s['text'] %></span>
				</span>
			</div>
		</div>
		<?php	
	}
	public function mailpoet_get_list() {
		$data = [];
		if ( class_exists( \MailPoet\API\API::class ) ) {
			$mailpoet_api = \MailPoet\API\API::MP( 'v1' );
			$lists        = $mailpoet_api->getLists();
			foreach ( $lists as $item ) {
				$data[ $item['id'] ] = $item['name'];
			}
		}
		return $data;
	}
	protected function get_client_ip() {
		$ipaddress = '';
		if ( getenv( 'HTTP_CLIENT_IP' ) ) {
			$ipaddress = getenv( 'HTTP_CLIENT_IP' );
		} elseif ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
			$ipaddress = getenv( 'HTTP_X_FORWARDED_FOR' );
		} elseif ( getenv( 'HTTP_X_FORWARDED' ) ) {
			$ipaddress = getenv( 'HTTP_X_FORWARDED' );
		} elseif ( getenv( 'HTTP_FORWARDED_FOR' ) ) {
			$ipaddress = getenv( 'HTTP_FORWARDED_FOR' );
		} elseif ( getenv( 'HTTP_FORWARDED' ) ) {
			$ipaddress = getenv( 'HTTP_FORWARDED' );
		} elseif ( getenv( 'REMOTE_ADDR' ) ) {
			$ipaddress = getenv( 'REMOTE_ADDR' );
		} else {
			$ipaddress = 'UNKNOWN';
		}
		return $ipaddress;
	}

	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @since 1.5.0
	 * @access protected
	 */
	protected function render_text() {
		$settings = $this->settings;

		$this->add_render_attribute(
			[
				'content-wrapper' => [
					'class' => 'piotnetforms-button-content-wrapper',
				],
				'icon-align'      => [
					'class' => [
						'piotnetforms-button-icon',
						'piotnetforms-align-icon-' . $settings['icon_align'],
					],
				],
				'text'            => [
					'class' => 'piotnetforms-button-text',
				],
			]
		);

		?>
		<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
			<span class="piotnetforms-button-text piotnetforms-spinner"><span class="icon-spinner-of-dots"></span></span>
			<span <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo $settings['text']; ?></span>
		</span>
		<?php
	}

	protected function create_list_exist( $repeater ) {
		$settings = $this->get_settings_for_display();

		// $repeater_terms = $repeater->get_controls();

		// if (!empty($settings['submit_post_term_slug']) && empty($repeater_terms)) {
		// 	$repeater_terms[0] = $settings['submit_post_term_slug'];
		// 	$repeater_terms[1] = $settings['submit_post_term'];
		// }

		return $settings;
	}

	public function add_wpml_support() {
		add_filter( 'wpml_piotnetforms_widgets_to_translate', [ $this, 'wpml_widgets_to_translate_filter' ] );
	}

	public function wpml_widgets_to_translate_filter( $widgets ) {
		$widgets[ $this->get_name() ] = [
			'conditions' => [ 'widgetType' => $this->get_name() ],
			'fields'     => [
				[
					'field'       => 'text',
					'type'        => __( 'Button Text', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_to',
					'type'        => __( 'Email To', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_subject',
					'type'        => __( 'Email Subject', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_content',
					'type'        => __( 'Email Content', 'piotnetforms' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'email_from',
					'type'        => __( 'Email From', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_from_name',
					'type'        => __( 'Email From Name', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_reply_to',
					'type'        => __( 'Email Reply To', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_to_cc',
					'type'        => __( 'Cc', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_to_bcc',
					'type'        => __( 'Bcc', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_to_2',
					'type'        => __( 'Email To 2', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_subject_2',
					'type'        => __( 'Email Subject 2', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_content_2',
					'type'        => __( 'Email Content 2', 'piotnetforms' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'email_from_2',
					'type'        => __( 'Email From 2', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_from_name_2',
					'type'        => __( 'Email From Name 2', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_reply_to_2',
					'type'        => __( 'Email Reply To 2', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_to_cc_2',
					'type'        => __( 'Cc 2', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_to_bcc_2',
					'type'        => __( 'Bcc 2', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'success_message',
					'type'        => __( 'Success Message', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'error_message',
					'type'        => __( 'Error Message', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'required_field_message',
					'type'        => __( 'Required Message', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'invalid_message',
					'type'        => __( 'Invalid Message', 'piotnetforms' ),
					'editor_type' => 'LINE',
				],
			],
		];

		return $widgets;
	}
}
