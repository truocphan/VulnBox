<?php
namespace WprAddons\Modules\FormBuilder\Widgets;

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Form_Builder extends Widget_Base {
	
	public function get_name() {
		return 'wpr-form-builder';
	}

	public function get_title() {
		return esc_html__( 'Form Builder', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-form-horizontal';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'cf7', 'contact form 7', 'caldera forms', 'ninja forms', 'wpforms', 'wp forms', 'email', 'mail' ];
	}

	public function get_style_depends() {
		return [ 'wpr-loading-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-forms-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	// Allow overwrite the control_id with a prefix, @see Email2
	protected function get_control_id( $control_id ) {
		return $control_id;
	}

	public function get_label() {
		return esc_html__( 'Email', 'wpr-addons' );
	}

	public static function get_site_domain() {
		return str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
	}

	public function submit_action_args() {
		$actions_options = [
			'email' => 'Email',
			'redirect' => 'Redirect',
			'pro-sb' => 'Submission (Pro)',
			'pro-mch' => 'Mailchimp (Pro)',
			'pro-wh' => 'Webhook (Pro)'
		];

		return $actions_options;
	}

	public function register_settings_section_submissions( $widget ) {
		$widget->start_controls_section(
			$this->get_control_id( 'section_submissions' ),
			[
				'label' => esc_html__( 'Submissions', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'submit_actions' => 'submissions',
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'submissions_action_message' ),
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => sprintf(
					__(
						'View Submissions in Royal Addons > <a href="%s" target="_blank">Submissions</a>',
						'wpr-addons'
					),
					self_admin_url( 'edit.php?post_type=wpr_submissions' )
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$widget->end_controls_section();
	}

	public function register_settings_section_webhook( $widget ) {
		$widget->start_controls_section(
			$this->get_control_id( 'section_webhook' ),
			[
				'label' => esc_html__( 'Webhook', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'submit_actions' => 'webhook',
				],
			]
		);

		$widget->add_control(
			'webhook_url',
			[
				'label' => esc_html__( 'Webhook URL', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'https://your-webhook-url.com', 'wpr-addons' ),
				'ai' => [
					'active' => false,
				],
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'separator' => 'before',
				'description' => esc_html__( 'Enter the webhook URL (e.g. Zapier) that will receive the submitted data.', 'wpr-addons' ),
				'render_type' => 'none',
			]
		);

		$widget->end_controls_section();
	}

	public function register_settings_section_email( $widget ) {
		$widget->start_controls_section(
			$this->get_control_id( 'section_email' ),
			[
				'label' => $this->get_label(),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'submit_actions' => 'email',
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_to' ),
			[
				'label' => esc_html__( 'To', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => get_option( 'admin_email' ),
				'label_block' => true,
				'title' => esc_html__( 'Separate emails with commas', 'wpr-addons' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		// maybe esc_html not necessary
		/* translators: %s: Site title. */
		$default_message = sprintf( esc_html__( 'New message from %s', 'wpr-addons' ), get_option( 'blogname' ) );

		$widget->add_control(
			$this->get_control_id( 'email_subject' ),
			[
				'label' => esc_html__( 'Subject', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_message,
				'placeholder' => $default_message,
				'label_block' => true,
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_content' ),
			[
				'label' => esc_html__( 'Message', 'wpr-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '[all-fields]',
				'placeholder' => '[all-fields]',
				'description' => sprintf(
					esc_html__( 'By default, form sends all fields. To modify this behaviour, copy the shortcode you wish from fields and paste it instead of %s.', 'wpr-addons' ),
					'<code>[all-fields]</code>'
				),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$site_domain = $this->get_site_domain();

		$widget->add_control(
			$this->get_control_id( 'email_from' ),
			[
				'label' => esc_html__( 'From Email', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'email@' . $site_domain,
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_from_name' ),
			[
				'label' => esc_html__( 'From Name', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => get_bloginfo( 'name' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_reply_to' ),
			[
				'label' => esc_html__( 'Reply To', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'email@' . $site_domain,
				'render_type' => 'none'
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_to_cc' ),
			[
				'label' => esc_html__( 'Cc', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => esc_html__( 'Separate emails with commas', 'wpr-addons' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_to_bcc' ),
			[
				'label' => esc_html__( 'Bcc', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => esc_html__( 'Separate emails with commas', 'wpr-addons' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'form_metadata' ),
			[
				'label' => esc_html__( 'Meta Data', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => true,
				'separator' => 'before',
				'default' => [
					'date',
					'time',
					'credit'
				],
				'options' => [
					'date' => esc_html__( 'Date', 'wpr-addons' ),
					'time' => esc_html__( 'Time', 'wpr-addons' ),
					'page_url' => esc_html__( 'Page URL', 'wpr-addons' ),
					'page_title' => esc_html__( 'Page Title', 'wpr-addons' ),
					'user_agent' => esc_html__( 'User Agent', 'wpr-addons' ),
					'remote_ip' => esc_html__( 'Remote IP', 'wpr-addons' ),
					'credit' => esc_html__( 'Credit', 'wpr-addons' ),
				],
				'render_type' => 'none',
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_content_type' ),
			[
				'label' => esc_html__( 'Send As', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'html',
				'render_type' => 'none',
				'options' => [
					'html' => esc_html__( 'HTML', 'wpr-addons' ),
					'plain' => esc_html__( 'Plain', 'wpr-addons' ),
				],
			]
		);

		$widget->end_controls_section();
	}

	public function register_settings_section_redirect( $widget ) {
		$widget->start_controls_section(
			'section_redirect',
			[
				'label' => esc_html__( 'Redirect', 'wpr-addons' ),
				'condition' => [
					'submit_actions' => 'redirect',
				],
			]
		);

		$widget->add_control(
			'redirect_to',
			[
				'label' => esc_html__( 'Redirect To', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'wpr-addons' ),
				'label_block' => true
			]
		);

		$widget->end_controls_section();
	}

	public function register_settings_section_mailchimp() {

		// Tab: Content ==============
		// Section: Settings ----------
		$this->start_controls_section(
			'section_mailchimp',
			[
				'label' => esc_html__( 'Mailchimp', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'submit_actions' => 'mailchimp'
				]
			]
		);

		$this->add_control(
			'maichimp_audience',
			[
				'label' => esc_html__( 'Select Audience', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'def',
				// 'render_type' => 'template',
				'options' => Utilities::get_mailchimp_lists(),
			]
		);

		// If we build it, needs further logic
		$this->add_control(
			'mailchimp_groups',
			[
				'label' => esc_html__( 'Groups', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => Utilities::get_mailchimp_groups(),
				// 'render_type' => 'template',
				'label_block' => true,
			]
		);

		if ( '' == get_option('wpr_mailchimp_api_key') ) {
			$this->add_control(
				'mailchimp_key_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf( __( 'Navigate to <strong><a href="%s" target="_blank">Dashboard > %s > Integrations</a></strong> to set up <strong>MailChimp API Key</strong>.', 'wpr-addons' ), admin_url( 'admin.php?page=wpr-addons&tab=wpr_tab_settings' ), Utilities::get_plugin_name() ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->add_control(
			'mailchimp_fields',
			[
				'label' => esc_html__( 'Fields', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'email_field',
			[
				'label' => esc_html__( 'Email', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => []
			]
		);

		$this->add_control(
			'first_name_field',
			[
				'label' => esc_html__( 'First Name', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => []
			]
		);

		$this->add_control(
			'last_name_field',
			[
				'label' => esc_html__( 'Last Name', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => []
			]
		);

		// Doesn't work even in other plugins that I've checked
		// $this->add_control(
		// 	'address_field',
		// 	[
		// 		'label' => esc_html__( 'Address', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SELECT,
		// 		'options' => []
		// 	]
		// );

		$this->add_control(
			'phone_field',
			[
				'label' => esc_html__( 'Phone', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => []
			]
		);

		$this->add_control(
			'birthday_field',
			[
				'label' => esc_html__( 'Birthday', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => []
			]
		);

		$this->end_controls_section();

	}
	
	protected function register_controls() {

		$this->start_controls_section(
			'section_form_fields',
			[
				'label' => esc_html__( 'Fields', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$repeater = new Repeater();

		$field_types = [
			'text' => esc_html__( 'Text', 'wpr-addons' ),
			'textarea' => esc_html__( 'Textarea', 'wpr-addons' ),
			'email' => esc_html__( 'Email', 'wpr-addons' ),
			'url' => esc_html__( 'URL', 'wpr-addons' ),
			'number' => esc_html__( 'Number', 'wpr-addons' ),
			'tel' => esc_html__( 'Tel', 'wpr-addons' ),
			'radio' => esc_html__( 'Radio', 'wpr-addons' ),
			'select' => esc_html__( 'Select', 'wpr-addons' ),
			'checkbox' => esc_html__( 'Checkbox', 'wpr-addons' ),
			'date' => esc_html__( 'Date', 'wpr-addons' ),
			'time' => esc_html__( 'Time', 'wpr-addons' ),
			'upload' => esc_html__( 'File Upload', 'wpr-addons' ),
			'password' => esc_html__( 'Password', 'wpr-addons' ),
			'html' => esc_html__( 'HTML', 'wpr-addons' ),
			'recaptcha-v3' => esc_html__( 'reCAPTCHA V3', 'wpr-addons'),
			'hidden' => esc_html__( 'Hidden', 'wpr-addons' ),
			'step' => esc_html__( 'Step', 'wpr-addons' ),
		];

		$repeater->add_control(
			'field_type',
			[
				'label' => esc_html__( 'Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'text',
			]
		);
		
		$repeater->add_control(
			'field_step_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => esc_html__('Step should be a First element of fields group. Ex: Step 1 followed by Field 1, Field 2. Step 2 followed by Field 3, Field 4.', 'wpr-addons'),
				'content_classes' => 'elementor-panel-alert',
				'condition' => [
					'field_type' => 'step'
				]
			]
		);

		if ( '' == get_option('wpr_recaptcha_v3_site_key') ) {
			$repeater->add_control(
				'recaptcha_key_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf( __( 'Navigate to <strong><a href="%s" target="_blank">Dashboard > %s > Integrations</a></strong> to set up <strong>reCaptcha Site Key</strong>.', 'wpr-addons' ), admin_url( 'admin.php?page=wpr-addons&tab=wpr_tab_settings' ), Utilities::get_plugin_name() ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
					'condition' => [
						'field_type' => 'recaptcha-v3'
					]
				]
			);
		}

		$repeater->add_control(
			'field_label',
			[
				'label' => esc_html__( 'Label', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'field_sub_label',
			[
				'label' => esc_html__( 'Sub Label', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'field_type' => 'step'
				]
			]
		);

		$repeater->add_control(
			'previous_button_text',
			[
				'label' => esc_html__( 'Previous Button', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Previous',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'field_type' => 'step'
				]
			]
		);

		$repeater->add_control(
			'next_button_text',
			[
				'label' => esc_html__( 'Next Button', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Next',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'field_type' => 'step'
				]
			]
		);

		$repeater->add_control(
			'step_icon',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'separator' => 'before',
				'default' => [
					'value' => 'far fa-edit',
					'library' => 'regular'
				],
				'condition' => [
					'field_type' => 'step'
				]
			]
		);

		$repeater->add_control(
			'placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'tel',
								'text',
								'email',
								'textarea',
								'number',
								'url',
								'password',
							],
						],
					],
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'field_value',
			[
				'label' => esc_html__( 'Default Value', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'text',
								'email',
								'textarea',
								'url',
								'tel',
								'radio',
								'select',
								'number',
								'date',
								'time',
								'hidden',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_id',
			[
				'label' => esc_html__( 'ID', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'description' => esc_html__( 'Element ID should be unique and not used elsewhere in this widget.', 'wpr-addons' ),
				'default' => '',
				'render_type' => 'none',
				'required' => true,
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$shortcode_value = '{{ view.container.settings.get( \'field_id\' ) }}';
		
		$repeater->add_control(
			'shortcode',
			[
				'label' => esc_html__( 'Shortcode', 'wpr-addons' ),
				'type' => Controls_Manager::RAW_HTML,
				'classes' => 'forms-field-shortcode',
				'raw' => '<input class="wpr-form-field-shortcode" value=\'[id="' . $shortcode_value . '"]\' readonly />'
			]
		);

		$repeater->add_control(
			'required',
			[
				'label' => esc_html__( 'Required', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => '!in',
							'value' => [
								'recaptcha',
								'recaptcha-v3',
								'hidden',
								'html',
								'step',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'allow_multiple_upload',
			[
				'label' => esc_html__( 'Multiple', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'field_type' => 'upload'
				],
			]
		);

		$max_file_size = wp_max_upload_size() / pow( 1024, 2 ); //MB

		$repeater->add_control(
			'file_size',
			[
				'label' => esc_html__( 'File Size (MB)', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => $max_file_size,
				'step' => 1,
				'description' => esc_html__( 'Max upload size allowed is '. $max_file_size .'MB. Please contact your hosting to increase it.', 'wpr-addons' ),
				'condition' => [
					'field_type' => 'upload'
				]
			]
		);

		$repeater->add_control(
			'file_types',
			[
				'label' => esc_html__( 'File Types', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter the comma separated file types to allow.', 'wpr-addons' ),
				'condition' => [
					'field_type' => 'upload',
				]
			]
		);

		$repeater->add_control(
			'field_options',
			[
				'label' => esc_html__( 'Options', 'wpr-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'description' => esc_html__( 'Insert options in separate lines. For different label/values separate them with a pipe char ("|"). Like: First Option|f_option', 'wpr-addons' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'select',
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'allow_multiple',
			[
				'label' => esc_html__( 'Multiple Selection', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'select',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'select_size',
			[
				'label' => esc_html__( 'Rows', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 2,
				'step' => 1,
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'select',
						],
						[
							'name' => 'allow_multiple',
							'value' => 'true',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'inline_list',
			[
				'label' => esc_html__( 'Inline List', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'wpr-inline-sub-group',
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_html',
			[
				'label' => esc_html__( 'HTML', 'wpr-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'html',
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'width',
			[
				'label' => esc_html__( 'Column Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}%;',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => '!in',
							'value' => [
								'hidden',
								'recaptcha',
								'recaptcha-v3',
								'step',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'rows',
			[
				'label' => esc_html__( 'Rows', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 7,
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'textarea',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_size', [
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => esc_html__( 'Normal', 'wpr-addons' ),
					'compact' => esc_html__( 'Compact', 'wpr-addons' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_style',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light' => esc_html__( 'Light', 'wpr-addons' ),
					'dark' => esc_html__( 'Dark', 'wpr-addons' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha',
						],
					],
				],
			]
		);

		// $repeater->add_control(
		// 	'recaptcha_badge', [
		// 		'label' => esc_html__( 'Badge', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SELECT,
		// 		'default' => 'bottomright',
		// 		'options' => [
		// 			'bottomright' => esc_html__( 'Bottom Right', 'wpr-addons' ),
		// 			'bottomleft' => esc_html__( 'Bottom Left', 'wpr-addons' ),
		// 			'inline' => esc_html__( 'Inline', 'wpr-addons' ),
		// 		],
		// 		'description' => esc_html__( 'To view the validation badge, switch to preview mode', 'wpr-addons' ),
		// 		'conditions' => [
		// 			'terms' => [
		// 				[
		// 					'name' => 'field_type',
		// 					'value' => 'recaptcha-v3',
		// 				],
		// 			],
		// 		],
		// 	]
		// );

		$repeater->add_control(
			'css_classes',
			[
				'label' => esc_html__( 'CSS Classes', 'wpr-addons' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => '',
				'title' => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'form_fields',
			[
				// 'type' => Fields_Repeater::CONTROL_TYPE,
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'field_id' => 'name',
						'field_type' => 'text',
						'field_label' => esc_html__( 'Name', 'wpr-addons' ),
						'placeholder' => esc_html__( 'Name', 'wpr-addons' ),
						'width' => '100',
						'dynamic' => [
							'active' => true,
						],
					],
					[
						'field_id' => 'email',
						'field_type' => 'email',
						'required' => 'true',
						'field_label' => esc_html__( 'Email', 'wpr-addons' ),
						'placeholder' => esc_html__( 'Email', 'wpr-addons' ),
						'width' => '100',
					],
					[
						'field_id' => 'message',
						'field_type' => 'textarea',
						'field_label' => esc_html__( 'Message', 'wpr-addons' ),
						'placeholder' => esc_html__( 'Message', 'wpr-addons' ),
						'width' => '100',
					],
				],
				'title_field' => '{{{ field_label }}}',
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'fields_to_show_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => __('More than 3 Fields (Excluding Steps) are<br>available in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-form-builder-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>'),
					'content_classes' => 'wpr-pro-notice'
				]
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'section_buttons',
			[
				'label' => esc_html__( 'Buttons', 'wpr-addons' ),
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label' => esc_html__( 'Column Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-field-group.wpr-form-field-type-submit' => 'width: {{SIZE}}%;',
					'{{WRAPPER}} .wpr-stp-btns-wrap' => 'width: {{SIZE}}%;'
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'buttons_width',
			[
				'label' => esc_html__( 'Step Buttons Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],				
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-step-prev' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-step-next' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-step-tab .wpr-button' => 'width: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors_dictionary' => [
					'left' => 'margin-left: 0; margin-right: auto;',
					'center' => 'margin-left: auto; margin-right: auto;',
					'right' => 'margin-left: auto; margin-right: 0;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-stp-btns-wrap' => '{{VALUE}}',
					'{{WRAPPER}} .wpr-step-tab:first-of-type .wpr-step-next' => '{{VALUE}}',
				],
			]
		);

		// $this->add_control(
		// 	'heading_steps_buttons',
		// 	[
		// 		'label' => esc_html__( 'Step Buttons', 'wpr-addons' ),
		// 		'type' => Controls_Manager::HEADING,
		// 		'separator' => 'before',
		// 	]
		// );

		// $this->add_control(
		// 	'step_next_label',
		// 	[
		// 		'label' => esc_html__( 'Next', 'wpr-addons' ),
		// 		'type' => Controls_Manager::TEXT,
		// 		'dynamic' => [
		// 			'active' => true,
		// 		],
		// 		'frontend_available' => true,
		// 		'render_type' => 'none',
		// 		'default' => esc_html__( 'Next', 'wpr-addons' ),
		// 		'placeholder' => esc_html__( 'Next', 'wpr-addons' ),
		// 	]
		// );

		// $this->add_control(
		// 	'step_previous_label',
		// 	[
		// 		'label' => esc_html__( 'Previous', 'wpr-addons' ),
		// 		'type' => Controls_Manager::TEXT,
		// 		'dynamic' => [
		// 			'active' => true,
		// 		],
		// 		'frontend_available' => true,
		// 		'render_type' => 'none',
		// 		'default' => esc_html__( 'Previous', 'wpr-addons' ),
		// 		'placeholder' => esc_html__( 'Previous', 'wpr-addons' ),
		// 	]
		// );

		$this->add_control(
			'heading_submit_button',
			[
				'label' => esc_html__( 'Submit Button', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Submit', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Send', 'wpr-addons' ),
				'placeholder' => esc_html__( 'Send', 'wpr-addons' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'selected_button_icon',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'button_icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Before', 'wpr-addons' ),
					'right' => esc_html__( 'After', 'wpr-addons' ),
				],
				'condition' => [
					'selected_button_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'button_icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'selected_button_icon[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button .wpr-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-button .wpr-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_css_id',
			[
				'label' => esc_html__( 'Button ID', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'wpr-addons' ),
				'description' => esc_html__( 'Element ID should be unique and not used elsewhere in this widget', 'wpr-addons' ),
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();

		
		$this->start_controls_section(
			'section_form_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT
			]
		);

		$this->add_control(
			'form_name',
			[
				'label' => esc_html__( 'Form Name', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'New Form', 'wpr-addons' ),
				'placeholder' => esc_html__( 'Form Name', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'form_id',
			[
				'label' => esc_html__( 'Form ID', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => 'form_id',
				'description' => esc_html__( 'Form ID should be unique and shouldn\'t contain spaces', 'wpr-addons' ),
				'separator' => 'after',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'success_message',
			[
				'label' => esc_html__( 'Success Message', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('Submission successful', 'wpr-addons'),
				'placeholder' => esc_html__('Submission successful', 'wpr-addons'),
				'label_block' => true,
				'frontend_available' => true,
				// 'condition' => [
				// 	'custom_messages!' => '',
				// ],
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'error_message',
			[
				'label' => esc_html__( 'Error Message', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('Submission failed', 'wpr-addons'),
				'placeholder' => esc_html__('Submission failed', 'wpr-addons'),
				'label_block' => true,
				'frontend_available' => true,
				// 'condition' => [
				// 	'custom_messages!' => '',
				// ],
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label' => esc_html__( 'Show Field Labels', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'wpr-addons' ),
				'label_off' => esc_html__( 'Hide', 'wpr-addons' ),
				'return_value' => 'true',
				'default' => 'true',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_placeholders',
			[
				'label' => esc_html__( 'Show Placeholders', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'wpr-addons' ),
				'label_off' => esc_html__( 'Hide', 'wpr-addons' ),
				'return_value' => 'true',
				'default' => 'true'
			]
		);

		$this->add_control(
			'label_position',
			[
				'label' => esc_html__( 'Label Position', 'wpr-addons' ),
				'type' => Controls_Manager::HIDDEN,
				'options' => [
					'above' => esc_html__( 'Above', 'wpr-addons' ),
					'inline' => esc_html__( 'Inline', 'wpr-addons' ),
				],
				'default' => 'above',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'mark_required',
			[
				'label' => esc_html__( 'Show Required Mark', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'wpr-addons' ),
				'label_off' => esc_html__( 'Hide', 'wpr-addons' ),
				'default' => '',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_integration',
			[
				'label' => esc_html__( 'Actions', 'wpr-addons' ),
			]
		);
		// $actions = Module::instance()->actions_registrar->get();

		$default_submit_actions = [ 'email' ];

		$this->add_control(
			'submit_actions',
			[
				'label' => esc_html__( 'Add Action', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $this->submit_action_args(),
				'render_type' => 'none',
				'label_block' => true,
				'default' => $default_submit_actions,
				'description' => esc_html__( 'Select actions to be executed following a user\'s form submission (e.g., send an email notification). Upon choosing an action, its settings will appear below.', 'wpr-addons' ),
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'submit_actions_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => __('<strong>Submission</strong> and <strong>Mailchimp</strong> actions are only available <br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-form-builder-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>'),
					'content_classes' => 'wpr-pro-notice'
				]
			);
		}
		
		$this->end_controls_section();

		$this->register_settings_section_submissions($this);

		$this->register_settings_section_email($this);

		$this->register_settings_section_webhook($this);

		$this->register_settings_section_redirect($this);

		$this->register_settings_section_mailchimp();

		$this->start_controls_section(
			'section_form_step_settings',
			[
				'label' => esc_html__( 'Steps', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT
			]
		);

		$this->add_control(
			'step_type',
			[
				'label' => esc_html__( 'Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'frontend_available' => true,
				'render_type' => 'template',
				'options' => [
					'none' => 'None',
					'text' => 'Label',
					'icon' => 'Icon',
					'number' => 'Number',
					'progress_bar' => 'Progress Bar',
					'number_text' => 'Number & Label',
					'icon_text' => 'Icon & Label',
				],
				'prefix_class' => 'wpr-step-type-',
				'default' => 'number_text'
			]
		);

		$this->add_control(
			'step_content_layout',
			[
				'label' => esc_html__( 'Content Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'frontend_available' => true,
				'options' => [
					'horizontal' => 'Horizontal',
					'vertical' => 'Vertical',
				],
				'default' => 'vertical',
				'prefix_class' => 'wpr-step-content-layout-',
				'condition' => [
					'step_type!' => ['progress_bar', 'none']
				]
			]
		);

		$this->add_control(
			'show_separator',
			[
				'label' => esc_html__( 'Separator', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'step_type!' => 'progress_bar'
				]
			]
		);

		$this->add_responsive_control(
			'step_box_align',
			[
				'label' => esc_html__( 'Box Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}}.wpr-step-content-layout-vertical .wpr-step' => 'align-items: {{VALUE}}',
					'{{WRAPPER}}.wpr-step-content-layout-horizontal .wpr-step' => 'justify-content: {{VALUE}}'
				],
				'condition' => [
					'step_type!' => ['progress_bar', 'none']
				]
			]
		);

		$this->add_responsive_control(
			'step_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .wpr-step' => 'text-align: {{VALUE}}'
				],
				'condition' => [
					'step_type!' => ['progress_bar', 'none']
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_style',
			[
				'label' => esc_html__( 'Form', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => esc_html__( 'Label Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .wpr-field-group > label, {{WRAPPER}} .wpr-field-sub-group label' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'mark_required_color',
			[
				'label' => esc_html__( 'Mark Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#CB3030',
				'selectors' => [
					'{{WRAPPER}} .wpr-required-mark .wpr-form-field-label:after' => 'color: {{COLOR}};',
				],
				'condition' => [
					'mark_required' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .wpr-field-group > label'
			]
		);

		$this->add_control(
			'label_spacing',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'body.rtl {{WRAPPER}} .wpr-labels-inline .wpr-field-group > label' => 'padding-left: {{SIZE}}{{UNIT}};',
					// for the label position = inline option
					'body:not(.rtl) {{WRAPPER}} .wpr-labels-inline .wpr-field-group > label' => 'padding-right: {{SIZE}}{{UNIT}};',
					// for the label position = inline option
					'body {{WRAPPER}} .wpr-labels-above .wpr-field-group > label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					// for the label position = above option
				],
			]
		);

		$this->add_control(
			'heading_label',
			[
				'label' => esc_html__( 'Inputs', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'column_gap',
			[
				'label' => esc_html__( 'Horizontal Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-field-group' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .wpr-step-wrap' => 'padding-left: calc( -{{SIZE}}{{UNIT}}/2 ); padding-right: calc( -{{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .wpr-stp-btns-wrap' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .wpr-form-fields-wrap' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => esc_html__( 'Vertical Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-field-group:not(.wpr-stp-btns-wrap)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-field-group.recaptcha-v3-bottomleft, {{WRAPPER}} .wpr-field-group.recaptcha-v3-bottomright' => 'margin-bottom: 0;',
				],
			]
		);

		$this->add_responsive_control(
			'labels_align',
			[
				'label' => esc_html__( 'Align Labels', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .wpr-field-group:not(.wpr-stp-btns-wrap)' => 'justify-content: {{VALUE}}'
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			[
				'label' => esc_html__( 'Field', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_fields_style' );

		$this->start_controls_tab(
			'tab_fields_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .wpr-field-group .wpr-form-field' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-field-group input[type="radio"] + label' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-field-group input[type="checkbox"] + label' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-field-group:not(.wpr-form-field-type-upload) .wpr-form-field:not(.wpr-select-wrap)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-field-group .wpr-select-wrap select' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'field_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-field-group:not(.wpr-form-field-type-upload) .wpr-form-field:not(.wpr-select-wrap)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-field-group .wpr-select-wrap select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-field-group .wpr-select-wrap::before' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'field_typography',
				'selector' => '{{WRAPPER}} .wpr-field-group .wpr-form-field, {{WRAPPER}} .wpr-field-sub-group label'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_fields_focus',
			[
				'label' => esc_html__( 'Focus', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'field_text_color_focus',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .wpr-field-group .wpr-form-field:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-field-group input[type="radio"]:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-field-group input[type="checkbox"]:focus' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'field_background_color_focus',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-field-group:not(.wpr-form-field-type-upload) .wpr-form-field:not(.wpr-select-wrap):focus' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-field-group .wpr-select-wrap select:focus' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'field_border_color_focus',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-field-group:not(.wpr-form-field-type-upload) .wpr-form-field:not(.wpr-select-wrap):focus' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-field-group .wpr-select-wrap select:focus' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-field-group .wpr-select-wrap:focus-within::before' => 'color: {{VALUE}};',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_fields_error',
			[
				'label' => esc_html__( 'Error', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'field_text_color_error',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#CB3030',
				'selectors' => [
					'{{WRAPPER}} .wpr-field-group .wpr-form-field.wpr-form-error' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-field-group input[type="radio"].wpr-form-error' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-field-group input[type="checkbox"].wpr-form-error' => 'color: {{VALUE}};',
					// '{{WRAPPER}} .wpr-field-group .wpr-form-field-label' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'field_background_color_error',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-field-group:not(.wpr-form-field-type-upload) .wpr-form-field:not(.wpr-select-wrap).wpr-form-error' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-field-group .wpr-select-wrap select.wpr-form-error' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'field_border_color_error',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#CB3030',
				'selectors' => [
					'{{WRAPPER}} .wpr-field-group:not(.wpr-form-field-type-upload) .wpr-form-field:not(.wpr-select-wrap).wpr-form-error' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-field-group .wpr-select-wrap select.wpr-form-error' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-field-group .wpr-select-wrap.wpr-form-error-wrap::before' => 'color: {{VALUE}};',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'field_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'placeholder' => '1',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-field-group:not(.wpr-form-field-type-upload) .wpr-form-field:not(.wpr-select-wrap)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-field-group .wpr-select-wrap select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'field_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-field-group:not(.wpr-form-field-type-upload) .wpr-form-field:not(.wpr-select-wrap)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-field-group .wpr-select-wrap select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'field_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 6,
					'right' => 5,
					'bottom' => 7,
					'left' => 10,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-field-group:not(.wpr-form-field-type-upload) .wpr-form-field:not(.wpr-select-wrap)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-field-group .wpr-select-wrap select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-field-group input[type="date"]::before' => 'right: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-field-group input[type="time"]::before' => 'right: {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'radio_and_checkbox_distance',
			[
				'label' => esc_html__( 'Radio & Checkbox', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'radion_&_checkbox_padding',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-form-field-option' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'radion_&_checkbox_gutter',
			[
				'label' => esc_html__( 'Inner Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-form-field-option label' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-custom-styles-yes .wpr-form-field-option label:before' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Checkboxes -------
		$this->start_controls_section(
			'section_style_checkbox_radio',
			[
				'label' => esc_html__( 'Checkbox & Radio', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'checkbox_radio_custom',
			[
				'label' => esc_html__( 'Use Custom Styles', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'wpr-custom-styles-'
			]
		);

		$this->add_control(
			'checkbox_radio_static_color',
			[
				'label' => esc_html__( 'Static Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-form-field-type-checkbox .wpr-form-field-option label:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-form-field-type-radio .wpr-form-field-option label:before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'checkbox_radio_custom' => 'yes'
				]
			]
		);

		$this->add_control(
			'checkbox_radio_active_color',
			[
				'label' => esc_html__( 'Active Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-form-field-type-checkbox .wpr-form-field-option label:before' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-form-field-type-radio .wpr-form-field-option label:before' => 'color: {{VALUE}}',
				],
				'condition' => [
					'checkbox_radio_custom' => 'yes'
				]
			]
		);

		$this->add_control(
			'checkbox_radio_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-form-field-type-checkbox .wpr-form-field-option label:before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-form-field-type-radio .wpr-form-field-option label:before' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'checkbox_radio_custom' => 'yes'
				]
			]
		);

		$this->add_control(
			'checkbox_radio_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-form-field-type-checkbox .wpr-form-field-option label:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} / 1.3);',
					'{{WRAPPER}} .wpr-form-field-type-radio .wpr-form-field-option label:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} / 1.3);',
					'{{WRAPPER}} .wpr-form-field-type-checkbox input' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-form-field-type-radio input' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before',
				'condition' => [
					'checkbox_radio_custom' => 'yes'
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Buttons', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'heading_next_submit_button',
			[
				'label' => esc_html__( 'Submit Button, Next Button', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-next' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-button[type="submit"]' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-next' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-button[type="submit"]' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-double-bounce .wpr-child' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .wpr-button[type="submit"] svg *' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-prev' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-step-next' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-button[type="submit"]' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'heading_previous_button',
			[
				'label' => esc_html__( 'Previous Button', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'previous_button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-prev' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'previous_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-prev' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'previous_button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-prev' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .wpr-button, {{WRAPPER}} .wpr-step-prev, {{WRAPPER}} .wpr-step-next',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'heading_next_submit_button_hover',
			[
				'label' => esc_html__( 'Next & Submit Button', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-next:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-button[type="submit"]:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-next:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-button[type="submit"]:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-button[type="submit"]:hover svg *' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-next:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-button[type="submit"]:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'heading_previous_button_hover',
			[
				'label' => esc_html__( 'Previous Button', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'previous_button_background_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-prev:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'previous_button_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-prev:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'previous_button_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-prev:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		// $this->add_control(
		// 	'button_hover_animation',
		// 	[
		// 		'label' => esc_html__( 'Animation', 'wpr-addons' ),
		// 		'type' => Controls_Manager::HOVER_ANIMATION,
		// 	]
		// );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .wpr-button, {{WRAPPER}} .wpr-step-prev, {{WRAPPER}} .wpr-step-next',
				'exclude' => [
					'color',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-step-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-step-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label' => esc_html__( 'Text Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-step-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-step-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_step_style',
			[
				'label' => esc_html__( 'Step', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_step_style' );

		$this->start_controls_tab(
			'tab_step_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'main_label_color',
			[
				'label' => esc_html__( 'Label Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-main-label' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'sub_label_color',
			[
				'label' => esc_html__( 'Sub Label Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-sub-label' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'step_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-step' => 'background-color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'step_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .wpr-step' => 'border-color: {{VALUE}};'
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_step_active',
			[
				'label' => esc_html__( 'Active', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'main_label_color_active',
			[
				'label' => esc_html__( 'Label Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .wpr-step.wpr-step-active .wpr-step-main-label' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'sub_label_color_active',
			[
				'label' => esc_html__( 'Sub Label Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .wpr-step.wpr-step-active .wpr-step-sub-label' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'step_bg_color_active',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-step.wpr-step-active' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'step_border_color_active',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-step.wpr-step-active' => 'border-color: {{VALUE}};'
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_step_finished',
			[
				'label' => esc_html__( 'Finished', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'main_label_color_finish',
			[
				'label' => esc_html__( 'Label Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .wpr-step.wpr-step-finish .wpr-step-main-label' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'sub_label_color_finish',
			[
				'label' => esc_html__( 'Sub Label Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .wpr-step.wpr-step-finish .wpr-step-sub-label' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'step_bg_color_finish',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-step.wpr-step-finish' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'step_border_color_finish',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-step.wpr-step-finish' => 'border-color: {{VALUE}};'
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'step_wrap_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-step-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'step_wrap_gutter',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 5
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-step-sep' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .wpr-separator-off .wpr-step:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}}'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'step_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-step' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'step_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-step' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'step_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'step_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-step' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'step_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'unit' => 'px',
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					// '{{WRAPPER}}.wpr-step-content-layout-horizontal .wpr-step' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-step' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}' => '--wpr-steps-padding: {{TOP}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'step_inner_styles',
			[
				'label' => esc_html__( 'Step Indicator', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'tabs_step_inner_style' );

		$this->start_controls_tab(
			'tab_step_inner_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'step_inner_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-content i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-step-content' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'step_inner_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-content' => 'background-color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'step_inner_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-content' => 'border-color: {{VALUE}};'
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_step_inner_active',
			[
				'label' => esc_html__( 'Active', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'step_inner_color_active',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-active .wpr-step-content i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-step-active .wpr-step-content' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'step_inner_bg_color_active',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .wpr-step.wpr-step-active .wpr-step-content' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'step_inner_border_color_active',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .wpr-step.wpr-step-active .wpr-step-content' => 'border-color: {{VALUE}};'
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_step_inner_finish',
			[
				'label' => esc_html__( 'Finish', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'step_inner_color_finish',
			[
				'label' => esc_html__( 'Color (Labels, Icon, Number)', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-finish .wpr-step-content i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-step-finish .wpr-step-content' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'step_inner_bg_color_finish',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .wpr-step.wpr-step-finish .wpr-step-content' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'step_inner_border_color_finish',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .wpr-step.wpr-step-finish .wpr-step-content' => 'border-color: {{VALUE}};'
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'step_inner_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-content' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'step_inner_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-step-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'step_inner_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'step_inner_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-step-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		// $this->add_control(
		// 	'step_inner_padding',
		// 	[
		// 		'label' => esc_html__( 'Padding', 'wpr-addons' ),
		// 		'type' => Controls_Manager::DIMENSIONS,
		// 		'size_units' => [ 'px', 'em', '%' ],
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'top' => 10,
		// 			'right' => 10,
		// 			'bottom' => 10,
		// 			'left' => 10,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .wpr-step-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		// 		],
		// 	]
		// );

		$this->add_responsive_control(
			'step_inner_padding',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--wpr-steps-indicator-padding: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'step_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-step' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'step_type' => ['icon', 'icon_text']
				]
			]
		); 

		$this->add_responsive_control(
			'step_label_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-step-content-layout-horizontal .wpr-step-label' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-step-content-layout-vertical .wpr-step-label' => 'margin-top: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before',
				'condition' => [
					'step_type' => ['number_text', 'icon_text']
				]
			]
		);

		$this->add_control(
			'step_divider',
			[
				'label' => esc_html__( 'Divider', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'step_type!' => 'progress_bar'
				]
			]
		);

		$this->add_control(
			'step_progressbar',
			[
				'label' => esc_html__( 'Progressbar', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'step_type' => 'progress_bar'
				]
			]
		);

		$this->add_control(
			'step_divider_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222333',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-sep' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-step-progress' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'step_progress_text_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-progress-fill' => 'color: {{VALUE}};',
				],
				'condition' => [
					'step_type' => 'progress_bar'
				]
			]
		);

		$this->add_control(
			'step_progress_fill_color',
			[
				'label' => esc_html__( 'Fill Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .wpr-step-progress-fill' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'step_type' => 'progress_bar'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'step_percent_typo',
				'selector' => '{{WRAPPER}} .wpr-step-progress-fill',
				'condition' => [
					'step_type' => 'progress_bar'
				]
			]
		);

		$this->add_responsive_control(
			'step_divider_height',
			[
				'label' => esc_html__( 'Divider Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--wpr-steps-divider-width: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'step_type!' => 'progress_bar'
				]
			]
		);

		$this->add_responsive_control(
			'step_progress_text_distance',
			[
				'label' => esc_html__( 'Text Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-step-progress-fill' => 'padding-right: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'step_type' => 'progress_bar'
				]
			]
		);

		$this->add_responsive_control(
			'step_progress_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-step-progress' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-step-progress-fill' => 'border-radius: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'step_type' => 'progress_bar'
				]
			]
		);

		$this->add_control(
			'step_main_label',
			[
				'label' => esc_html__( 'Main Label', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'main_label_typography',
				'selector' => '{{WRAPPER}} .wpr-step-main-label',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'step_sub_label',
			[
				'label' => esc_html__( 'Sub Label', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sub_label_typography',
				'selector' => '{{WRAPPER}} .wpr-step-sub-label',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'step_number_heading',
			[
				'label' => esc_html__( 'Number', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'step_type' => ['number', 'number_text']
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'step_number',
				'selector' => '{{WRAPPER}} .wpr-step-number',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				],
				'condition' => [
					'step_type' => ['number', 'number_text']
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_results_style',
			[
				'label' => esc_html__( 'Results', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'results_typography',
				'selector' => '{{WRAPPER}} .wpr-submit-success, {{WRAPPER}} .wpr-submit-error',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'success_result_color',
			[
				'label' => esc_html__( 'Success Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#30CBCB',
				'selectors' => [
					'{{WRAPPER}} .wpr-submit-success' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'error_message_color',
			[
				'label' => esc_html__( 'Error Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#CB3030',
				'selectors' => [
					'{{WRAPPER}} .wpr-submit-error' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'finish_message_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .wpr-submit-success' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .wpr-submit-error' => 'text-align: {{VALUE}}'
				],
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'wpr-form-builder', [
			'Unlimited number of fields',
			'Submission action',
			'Mailchimp action',
			'Webhook action'
		] );
    }

	private function add_required_attribute( $element ) {
		$this->add_render_attribute( $element, 'required', 'required' );
		$this->add_render_attribute( $element, 'aria-required', 'true' );
	}

	public function get_attribute_name( $item ) {
		return "form_fields[{$item['field_id']}]";
	}

	public function get_attribute_id( $item ) {
		//  $id_suffix = !empty($item['field_id']) ? $item['field_id'] : $item['field_type'];
		 $id_suffix = !empty($item['field_id']) ? $item['field_id'] : $item['_id'];
		return 'form-field-' . $id_suffix;
	}

	protected function make_textarea_field( $item, $item_index ) {
		$this->add_render_attribute( 'textarea' . $item_index, [
			'class' => [
				'wpr-form-field-textual',
				'wpr-form-field',
				esc_attr( $item['css_classes'] )
			],
			'name' => $this->get_attribute_name( $item ),
			'id' => $this->get_attribute_id( $item ),
			'rows' => $item['rows'],
		] );

		if ( 'true' == $this->get_settings_for_display()['show_placeholders'] && $item['placeholder'] ) {
			$this->add_render_attribute( 'textarea' . $item_index, 'placeholder', $item['placeholder'] );
		}

		if ( $item['required'] ) {
			$this->add_required_attribute( 'textarea' . $item_index );
		}

		$value = empty( $item['field_value'] ) ? '' : $item['field_value'];

		return '<textarea ' . $this->get_render_attribute_string( 'textarea' . $item_index ) . '>' . $value . '</textarea>';
	}

	protected function make_select_field( $item, $i ) {
		$this->add_render_attribute(
			[
				'select-wrapper' . $i => [
					'class' => [
						'wpr-form-field',
						'wpr-select-wrap',
						'remove-before',
						esc_attr( $item['css_classes'] ),
					],
				],
				'select' . $i => [
					'name' => $this->get_attribute_name( $item ) . ( ! empty( $item['allow_multiple'] ) ? '[]' : '' ),
					'id' => $this->get_attribute_id( $item ),
					'class' => [
						'wpr-form-field-textual'
					],
				],
			]
		);

		if ( $item['required'] ) {
			$this->add_required_attribute( 'select' . $i );
		}

		if ( $item['allow_multiple'] ) {
			$this->add_render_attribute( 'select' . $i, 'multiple' );
			if ( ! empty( $item['select_size'] ) ) {
				$this->add_render_attribute( 'select' . $i, 'size', $item['select_size'] );
			}
		}

		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );

		if ( ! $options ) {
			return '';
		}

		ob_start();
		?>
		<div <?php $this->print_render_attribute_string( 'select-wrapper' . $i ); ?>>
			<select <?php $this->print_render_attribute_string( 'select' . $i ); ?>>

				<?php
				foreach ( $options as $key => $option ) :
					$option_id = $item['field_id'] . $key;
					$option_value = esc_attr( $option );
					$option_label = esc_html( $option );

					if ( false !== strpos( $option, '|' ) ) {
						list( $label, $value ) = explode( '|', $option );
						$option_value = esc_attr( $value );
						$option_label = esc_html( $label );
					}

					$this->add_render_attribute( $option_id, 'value', $option_value );

					// Support multiple selected values
					if ( ! empty( $item['field_value'] ) && in_array( $option_value, explode( ',', $item['field_value'] ) ) ) {
						$this->add_render_attribute( $option_id, 'selected', 'selected' );
					} ?>
					<option <?php $this->print_render_attribute_string( $option_id ); ?>>
					<?php
						// PHPCS - $option_label is already escaped
						echo $option_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php

		$select = ob_get_clean();
		return $select;
	}

	protected function make_radio_checkbox_field( $item, $item_index, $type ) {
		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );
		$html = '';
		if ( $options ) {
			$html .= '<div class="wpr-field-sub-group ' . esc_attr( $item['css_classes'] ) . ' ' . $item['inline_list'] . '">';
			foreach ( $options as $key => $option ) {
				$element_id = ($item['field_id'] ? $item['field_id'] : $item['field_type']) . $key;
				$html_id = $this->get_attribute_id( $item ) . '-' . $key;
				$option_label = $option;
				$option_value = $option;

				if ( false !== strpos( $option, '|' ) ) {
					list( $option_label, $option_value ) = explode( '|', $option );
				}

				$this->add_render_attribute(
					$element_id,
					[
						'type' => $type,
						'value' => $option_value,
						'id' => $html_id,
						'name' => $this->get_attribute_name( $item ) . ( ( 'checkbox' === $type && count( $options ) > 1 ) ? '[]' : '' ),
					]
				);

				if ( ! empty( $item['field_value'] ) && $option_value === $item['field_value'] ) {
					$this->add_render_attribute( $element_id, 'checked', 'checked' );
				}

				if ( $item['required'] && ('radio' === $type || 'checkbox' === $type) ) {
					$this->add_required_attribute( $element_id );
				}

				$html .= '<span class="wpr-form-field-option" data-key="form-field-'. $item['field_id'] .'"><input ' . $this->get_render_attribute_string( $element_id ) . '> <label for="' . $html_id . '">'. $option_label .'</label></span>';
			}
			$html .= '</div>';
		}

		return $html;
	}	
	
	protected function form_fields_render_attributes( $i, $instance, $item ) {
		$this->add_render_attribute(
			[
				'field-group' . $i => [
					'class' => [
						'wpr-form-field-type-' . $item['field_type'],
						'wpr-field-group',
						'wpr-column',
						'wpr-field-group-' . $item['field_id'],
					],
				],
				'input' . $i => [
					'type' => ('acceptance' === $item['field_type']) ? 'checkbox' : (('upload' === $item['field_type']) ? 'file' :  $item['field_type']),
					'name' => $this->get_attribute_name( $item ),
					'id' => $this->get_attribute_id( $item ),
					'class' => [
						'wpr-form-field',
						empty( $item['css_classes'] ) ? '' : esc_attr( $item['css_classes'] ),
					],
				],
				'label' . $i => [
					'for' => $this->get_attribute_id( $item ),
					'class' => 'wpr-form-field-label',
				],
			]
		);

		if ( empty( $item['width'] ) ) {
			$item['width'] = '100';
		}

		// $this->add_render_attribute( 'field-group' . $i, 'class', 'wpr-col-' . $item['width'] );

		// if ( ! empty( $item['width_tablet'] ) ) {
		// 	$this->add_render_attribute( 'field-group' . $i, 'class', 'wpr-md-' . $item['width_tablet'] );
		// }

		if ( $item['allow_multiple'] ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'wpr-form-field-type-' . $item['field_type'] . '-multiple' );
		}

		// if ( ! empty( $item['width_mobile'] ) ) {
		// 	$this->add_render_attribute( 'field-group' . $i, 'class', 'wpr-sm-' . $item['width_mobile'] );
		// }

		
		
		$this->add_render_attribute( 'field-group' . $i, 'class', 'elementor-repeater-item-'. esc_attr($item['_id']) );

		// Allow zero as placeholder.
		if ( 'true' == $instance['show_placeholders'] && ! Utils::is_empty( $item['placeholder'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'placeholder', $item['placeholder'] );
		}

		if ( ! empty( $item['field_value'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'value', $item['field_value'] );
		}

		if ( ! $instance['show_labels'] ) {
			$this->add_render_attribute( 'label' . $i, 'class', 'wpr-hidden-element' );
		}

		if ( ! empty( $item['required'] ) ) {
			$class = 'wpr-form-field-required';
			if ( ! empty( $instance['mark_required'] ) ) {
				$class .= ' wpr-required-mark';
			}
			$this->add_render_attribute( 'field-group' . $i, 'class', $class );
			$this->add_required_attribute( 'input' . $i );
		}
	}

	private function render_form_icon( $settings ) { ?>
		<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
			<?php Icons_Manager::render_icon( $settings['selected_button_icon'], [ 'aria-hidden' => 'true' ] );  ?>
			<?php if ( empty( $instance['button_text'] ) ) : ?>
				<span class="wpr-hidden-element"><?php echo esc_html__( 'Submit', 'wpr-addons' ); ?></span>
			<?php endif; ?>
		</span>
	<?php }

	public function render_submit_button($instance) {
		// echo '<button type="submit" '.  $this->get_render_attribute_string( 'button' ) .'>';
		// 	echo '<span '. $this->get_render_attribute_string( 'content-wrapper' ) .'>';
		// 		if ( ! empty( $instance['button_icon'] ) || ! empty( $instance['selected_button_icon'] ) ) :
		// 			echo '<span '. $this->get_render_attribute_string( 'icon-align' ) .'>';
		// 				if ( empty( $instance['button_text'] ) ) :
		// 					// remove class if possible
		// 					echo '<span  class="wpr-hidden-element">'. esc_html__( 'Submit', 'wpr-addons' ) .'</span>';
		// 				endif;
		// 			echo '</span>';
		// 		endif;
		// 		if ( ! empty( $instance['button_text'] ) ) :
		// 			echo '<span> '. $this->print_unescaped_setting( 'button_text' ) .'</span>';
		// 		endif;
		// 	echo '</span>';
		// 	echo '<div class="wpr-double-bounce wpr-loader-hidden">';
		// 		echo '<div class="wpr-child wpr-double-bounce1"></div>';
		// 		echo '<div class="wpr-child wpr-double-bounce2"></div>';
		// 	echo '</div>';
		// echo '</button>';
		?>
			<button type="submit" <?php echo $this->get_render_attribute_string( 'button' ); ?>>
				<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
					<?php if ( !empty( $instance['selected_button_icon'] ) && 'left' === $instance['button_icon_align'] ) : ?>
						<?php $this->render_form_icon($instance); ?>
					<?php endif; ?>
					<?php if ( ! empty( $instance['button_text'] ) ) : ?>
						<span><?php echo $this->print_unescaped_setting( 'button_text' ); ?></span>
					<?php endif; ?>
					<?php if ( !empty( $instance['selected_button_icon'] ) && 'right' === $instance['button_icon_align'] ) : ?>
						<?php $this->render_form_icon($instance); ?>
					<?php endif; ?>
				</span>	
				<div class="wpr-double-bounce wpr-loader-hidden">
					<div class="wpr-child wpr-double-bounce1"></div>
					<div class="wpr-child wpr-double-bounce2"></div>
				</div>
			</button>
		<?php 
	}

	protected function render() {
		global $post;
		$instance = $this->get_settings_for_display();
		
		$form_fields_length = sizeof($instance['form_fields']);
		$thisId = $this->get_id();

		update_option('wpr_email_content_type_'. $this->get_id(), $instance['email_content_type']);
		update_option('wpr_email_to_'. $this->get_id(), $instance['email_to']);
		update_option('wpr_email_subject_'. $this->get_id(), $instance['email_subject']);
		update_option('wpr_email_fields_'. $this->get_id(), $instance['email_content']);
		update_option('wpr_cc_header_'. $this->get_id(), $instance['email_to_cc']);
		update_option('wpr_bcc_header_'. $this->get_id(), $instance['email_to_bcc']);
		update_option('wpr_email_from_'. $this->get_id(), $instance['email_from']);
		update_option('wpr_email_from_name_'. $this->get_id(), $instance['email_from_name']);
		update_option('wpr_reply_to_'. $this->get_id(), $instance['email_reply_to']);
		update_option('wpr_meta_keys_'. $this->get_id(), $instance['form_metadata']);
		update_option('wpr_referrer_'. $this->get_id(), home_url( $_SERVER['REQUEST_URI'] ));
		update_option('wpr_referrer_title_'. $this->get_id(), get_the_title($post->ID));
		update_option('wpr_webhook_url_'. $this->get_id(), $instance['webhook_url']);

		$emailField      = isset($instance['email_field']) ? $instance['email_field'] : '';
		$firstNameField  = isset($instance['first_name_field']) ? $instance['first_name_field'] : '';
		$lastNameField   = isset($instance['last_name_field']) ? $instance['last_name_field'] : '';
		$addressField    = isset($instance['address_field']) ? $instance['address_field'] : '';
		$phoneField      = isset($instance['phone_field']) ? $instance['phone_field'] : '';
		$birthdayField   = isset($instance['birthday_field']) ? $instance['birthday_field'] : '';
		$groupId 	     = isset($instance['mailchimp_groups']) ? $instance['mailchimp_groups'] : '';

		$fieldsArray = [
			'email_field' => $emailField,
			'first_name_field' => $firstNameField,
			'last_name_field' => $lastNameField,
			'address_field' => $addressField,
			'phone_field' => $phoneField,
			'birthday_field' => $birthdayField,
			'group_id' =>  $groupId
		];

		$submit_actions = array_filter($instance['submit_actions'], function($value) {
			return $value !== 'pro-sb' && $value !== 'pro-mch' && $value !== 'pro-wh';
		});
		$submit_actions = array_values($submit_actions);

		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'wpr-form-fields-wrap',
						'wpr-labels-' . $instance['label_position'],
					],
				],
				'submit-group' => [
					'class' => [
						'wpr-field-group',
						'wpr-stp-btns-wrap',
						'wpr-column',
						'wpr-form-field-type-submit',
					],
					'data-actions' => [
						json_encode($submit_actions)
					],
					'data-redirect-url' => [
						in_array('redirect', $submit_actions) ? $instance['redirect_to'] : ''
					],
					'data-mailchimp-fields' => [
						json_encode($fieldsArray)
					],
					'data-list-id'=> [
						isset($instance['maichimp_audience']) ? esc_attr($instance['maichimp_audience']) : ''
					]
				],
				'button' => [
					'class' => 'wpr-button',
				],
				'icon-align' => [
					'class' => [
						empty( $instance['button_icon_align'] ) ? '' :
							'wpr-align-icon-' . $instance['button_icon_align'],
						'elementor-button-icon',
					],
				],
			]
		);

		if ( ! empty( $instance['form_id'] ) ) {
			$this->add_render_attribute( 'form', 'id', $instance['form_id'] );
		}

		if ( ! empty( $instance['form_name'] ) ) {
			$this->add_render_attribute( 'form', 'name', $instance['form_name'] );
		}

		$this->add_render_attribute( 'form', 'page', get_post()->post_title );
		$this->add_render_attribute( 'form', 'page_id', get_post()->ID );

		if ( ! empty( $instance['button_css_id'] ) ) {
			$this->add_render_attribute( 'button', 'id', $instance['button_css_id'] );
		}

		$referer_title = trim( wp_title( '', false ) );

		if ( ! $referer_title && is_home() ) {
			$referer_title = get_option( 'blogname' );
		}

		?>
		<form class="wpr-form" method="post" <?php echo $this->get_render_attribute_string( 'form' ); ?> novalidate>
			<input type="hidden" name="post_id" value="<?php // PHPCS - the method Utils::get_current_post_id is safe.
				echo get_the_ID(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"/>
			<input type="hidden" name="form_id" value="<?php echo esc_attr( $this->get_id() ); ?>"/>
			<input type="hidden" name="referer_title" value="<?php echo esc_attr( $referer_title ); ?>" />

			<?php if ( is_singular() ) {
				// `queried_id` may be different from `post_id` on Single theme builder templates.
				?>
				<input type="hidden" name="queried_id" value="<?php echo get_the_ID(); ?>"/>
			<?php } 
			
				$step_count1 = 0;
				$step_exists = '';
				$step_icon = [];
				$step_label = [];
				$step_sub_label = [];

				foreach ( $instance['form_fields'] as $key => $value ) {
					if ( 'step' === $value['field_type'] ) {
						$step_exists = 'exists';
						$step_count1++;
						
						ob_start();
							\Elementor\Icons_Manager::render_icon( $value['step_icon'], [ 'aria-hidden' => 'true' ] );
						$step_icon[] = ob_get_clean();

						$step_label[] = '<span class="wpr-step-main-label">'. $value['field_label'] .'</span>';

						$step_sub_label[] = '<span class="wpr-step-sub-label">'. $value['field_sub_label'] .'</span>';
					}
				}
				
				// Circles which indicates the steps of the form:
				$step_wrap_class  = 'yes' !== $instance['show_separator'] ? 'wpr-step-wrap wpr-separator-off' : 'wpr-step-wrap';
				
				echo '<div class="'. $step_wrap_class .'">';
					if ( 'progress_bar' == $instance['step_type'] ) {
						echo '<div class="wpr-step-progress">';
							echo '<div class="wpr-step-progress-fill"></div>';
						echo '</div>';
					} else {
						$i = 0;

						while ( $i < $step_count1 ) :

							if ( 'none' == $instance['step_type'] ) {
								$step_html = '<span class="wpr-step"></span>';
							} else if ( 'text' == $instance['step_type'] ) {
								$step_html = '<span class="wpr-step">'. $step_label[$i] . $step_sub_label[$i] .'</span>';
							} else if ( 'icon' == $instance['step_type'] ) {
								$step_html = '<span class="wpr-step"><span class="wpr-step-content">'. $step_icon[$i] .'</span></span>';
							} else if ( 'number' == $instance['step_type'] ) {
								$step_html = '<span class="wpr-step"><span class="wpr-step-content"><span class="wpr-step-number">'. ($i + 1) .'</span></span></span>';
							} else if ( 'number_text' == $instance['step_type'] ) {
								$step_html = '<span class="wpr-step"><span class="wpr-step-content"><span class="wpr-step-number">'. ($i + 1) .'</span></span><span class="wpr-step-label">'. $step_label[$i] . $step_sub_label[$i] .'</span></span>';
							} else if ( 'icon_text' == $instance['step_type'] ) {
								$step_html = '<span class="wpr-step"><span class="wpr-step-content">'. $step_icon[$i] .'</span><span class="wpr-step-label">'. $step_label[$i] . $step_sub_label[$i] .'</span></span>';
							}

							echo $step_html;
							// echo '<span class="wpr-step">'. $step_html .'</span>';

							if ( 'yes' == $instance['show_separator'] ) {
								echo '<span class="wpr-step-sep"></span>';
							}

							$i++; 
						endwhile;
					}
				echo '</div>';
			?>

			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<?php

				$step_count = 0;
				$field_count = 0;
				foreach ( $instance['form_fields'] as $item_index => $item ) :
					if ( 'step' !== $item['field_type'] ) {
						$field_count++;
						if ( !wpr_fs()->can_use_premium_code() && 3 < $field_count  ) {
							continue;
						}
					}

					$this->form_fields_render_attributes( $item_index, $instance, $item );

					$print_label = ! in_array( $item['field_type'], [ 'hidden', 'html', 'step' ], true );

					
					if ( 'step' === $item['field_type'] )  {
						if ( 0 === $step_count ) {
							echo '<div class="wpr-step-tab wpr-step-tab-hidden">';
						} else {
								echo '<div class="wpr-stp-btns-wrap">';
									echo '<button type="button" class="wpr-step-prev">'. $item['previous_button_text'] .'</button>';
									echo '<button type="button" class="wpr-step-next">'. $item['next_button_text'] .'</button>';
								echo '</div>';
							echo '</div>';
							echo '<div class="wpr-step-tab wpr-step-tab-hidden">';
						}
						$step_count++;
					}

					?>
					<div <?php $this->print_render_attribute_string( 'field-group' . $item_index ); ?>>
						<?php
						if ( $print_label && $item['field_label'] ) {
							?>
								<label <?php echo $this->get_render_attribute_string( 'label' . $item_index ); ?>>
									<?php // PHPCS - the variable $item['field_label'] is safe.
									echo $item['field_label']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</label>
							<?php
						}

						switch ( $item['field_type'] ) :
							case 'html':
								echo do_shortcode( $item['field_html'] );
								break;
							case 'textarea':
								// PHPCS - the method make_textarea_field is safe.
								echo $this->make_textarea_field( $item, $item_index ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								break;

							case 'select':
								// PHPCS - the method make_select_field is safe.
								echo $this->make_select_field( $item, $item_index ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								break;

							case 'radio':
							case 'checkbox':
								// PHPCS - the method make_radio_checkbox_field is safe.
								echo $this->make_radio_checkbox_field( $item, $item_index, $item['field_type'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								break;
							case 'recaptcha-v3':
								echo '<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" data-site-key="'. get_option('wpr_recaptcha_v3_site_key') .'" />';
							case 'text':
							case 'email':
							case 'url':
							case 'tel':
							case 'password':
							case 'hidden':
							case 'search':
							case 'number':
							case 'date':
							case 'time':
								$this->add_render_attribute( 'input' . $item_index, 'class', 'wpr-form-field-textual' );
								echo '<input size="1 "'. $this->get_render_attribute_string( 'input' . $item_index ) .'>';
								break;
							case 'upload':
								if ( 'yes' === $item['allow_multiple_upload'] ) {
									$this->add_render_attribute( 'input' . $item_index, 'multiple', 'multiple' );
								}

								if ( !empty( $item['file_size'] ) ) {
									$this->add_render_attribute(
										'input' . $item_index,
										[
											'data-maxfs' => $item['file_size'],  //MB
											'data-maxfs-notice' => esc_html__( 'File size is more than allowed.', 'wpr-addons' ),
										]
									);
								}

								if ( !empty( $item['file_types'] )) {
									$this->add_render_attribute(
										'input' . $item_index,
										[
											'data-allft' => $item['file_types']
										]
									);
								}

								echo '<input size="1 "'. $this->get_render_attribute_string( 'input' . $item_index ) .'>';
								break;
							case 'step':
								echo '<input type="hidden" class="wpr-step-input" id=form-field-'. $item['field_id'] .' value='. $item['field_label'] .'>';
								break;
							default:
								$field_type = $item['field_type'];
						endswitch;
						?>
					</div>
				<?php 
				endforeach;
				
				if ( 'exists' === $step_exists ) {
						echo '<div '. $this->get_render_attribute_string( 'submit-group' ) .'>';
							if ( 2 <= $step_count ) {
								echo '<button type="button" class="wpr-step-prev">Previous</button>';
							}

							echo $this->render_submit_button($instance);

						echo '</div>';
					echo '</div>';
				} else {
					echo '<div '. $this->get_render_attribute_string( 'submit-group' ) .'>';

						$this->render_submit_button($instance); 

					echo '</div>';
				} ?>
				
			</div>
		</form>
	  <?php
	}
}