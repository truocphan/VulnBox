<?php
namespace JupiterX_Core\Raven\Modules\Forms\Widgets;

use Elementor\Controls_Manager;
use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Modules\Forms\Module;
use JupiterX_Core\Raven\Utils;
use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
class Form extends Base_Widget {

	public function get_name() {
		return 'raven-form';
	}

	public function get_title() {
		return __( 'Form', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-form';
	}

	protected function register_controls() {
		// Content Tab.
		$this->register_section_form_fields();
		$this->register_section_submit_button();
		$this->register_section_settings();
		$this->register_section_steps();
		$this->register_section_messages();

		// Style Tab.
		$this->register_section_general();
		$this->register_section_label();
		$this->register_section_field();
		$this->register_section_select();
		$this->register_section_checkbox();
		$this->register_section_radio();
		$this->register_section_button();
		$this->register_message_style();
		$this->register_section_steps_indicator();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	private function register_section_form_fields() {

		$this->start_controls_section(
			'section_form_fields',
			[
				'label' => __( 'Form Fields', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'form_name',
			[
				'label' => __( 'Form', 'jupiterx-core' ),
				'type' => 'text',
				'default' => 'New form',
				'placeholder' => __( 'Enter your form name', 'jupiterx-core' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->start_controls_tabs( 'form_fields_tabs' );

		$repeater->start_controls_tab(
			'form_fields_content_tab',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
			]
		);

		$repeater->add_control(
			'type',
			[
				'label' => __( 'Type', 'jupiterx-core' ),
				'type' => 'select',
				'options' => array_merge(
					Module::get_field_types(),
					[
						'step' => esc_html__( 'Step', 'jupiterx-core' ),
					]
				),
				'default' => 'text',
			]
		);

		$repeater->add_control(
			'label',
			[
				'label' => __( 'Label', 'jupiterx-core' ),
				'type' => 'text',
			]
		);

		$repeater->add_control(
			'placeholder',
			[
				'label' => __( 'Placeholder', 'jupiterx-core' ),
				'type' => 'text',
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => '!in',
							'value' => [
								'acceptance',
								'recaptcha',
								'recaptcha_v3',
								'checkbox',
								'radio',
								'select',
								'file',
								'hidden',
								'step',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_options',
			[
				'name' => 'field_options',
				'label' => __( 'Options', 'jupiterx-core' ),
				'type' => 'textarea',
				'default' => '',
				'description' => __( 'Enter each option in a separate line. To differentiate between label and value, separate them with a pipe char ("|"). For example: First Name|f_name', 'jupiterx-core' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
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
			'inline_list',
			[
				'name' => 'inline_list',
				'label' => __( 'Inline List', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'raven-subgroup-inline',
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
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
			'native_html5',
			[
				'label' => __( 'Native HTML5', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'true',
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => 'in',
							'value' => [ 'date', 'time' ],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'multiple_selection',
			[
				'label' => __( 'Multiple Selection', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'true',
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => 'in',
							'value' => [
								'select',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'rows',
			[
				'label' => __( 'Rows', 'jupiterx-core' ),
				'name' => 'rows',
				'type' => 'number',
				'default' => 5,
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => 'in',
							'value' => [
								'textarea',
								'select',
							],
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'type',
									'operator' => '===',
									'value' => 'textarea',
								],
								[
									'name' => 'multiple_selection',
									'operator' => '===',
									'value' => 'true',
								],
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'required',
			[
				'label' => __( 'Required', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'true',
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => '!in',
							'value' => [
								'hidden',
								'recaptcha',
								'recaptcha_v3',
								'step',
							],
						],
					],
				],
			]
		);

		$this->add_intelligent_tel_controls( $repeater );

		$this->upload_field_controls( $repeater );

		$this->step_field_controls( $repeater );

		$repeater->add_responsive_control(
			'width',
			[
				'label' => __( 'Column Width', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => __( 'Default', 'jupiterx-core' ),
					'100' => '100%',
					'80' => '80%',
					'75' => '75%',
					'66' => '66%',
					'60' => '60%',
					'50' => '50%',
					'40' => '40%',
					'33' => '33%',
					'25' => '25%',
					'20' => '20%',
				],
				'default' => '100',
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => '!in',
							'value' => [
								'recaptcha',
								'recaptcha_v3',
								'hidden',
								'step',
							],
						],
					],
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'form_fields_advanced_tab',
			[
				'label' => esc_html__( 'Advanced', 'jupiterx-core' ),
			]
		);

		$repeater->add_control(
			'field_value',
			[
				'label' => esc_html__( 'Default Value', 'jupiterx-core' ),
				'type' => 'text',
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => '!in',
							'value' => [
								'acceptance',
								'recaptcha',
								'recaptcha_v3',
								'checkbox',
								'radio',
								'file',
								'step',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_custom_id',
			[
				'label' => esc_html__( 'ID', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere in this form. This field allows `A-z 0-9` & underscore chars without spaces.', 'jupiterx-core' ),
				'render_type' => 'none',
				'required' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$shortcode_template = '{{ view.container.settings.get( \'field_custom_id\' ) }}';

		$repeater->add_control(
			'shortcode',
			[
				'label' => esc_html__( 'Shortcode', 'jupiterx-core' ),
				'type' => Controls_Manager::RAW_HTML,
				'classes' => 'forms-field-shortcode',
				'raw' => '<input class="elementor-form-field-shortcode" value=\'[field id="' . $shortcode_template . '"]\' readonly />',
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'fields',
			[
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'label' => 'Name',
						'field_custom_id' => 'name',
						'type' => 'text',
						'placeholder' => 'Name',
					],
					[
						'label' => 'Email',
						'field_custom_id' => 'email',
						'type' => 'email',
						'placeholder' => 'Email',
						'required' => 'true',
					],
					[
						'label' => 'Message',
						'field_custom_id' => 'message',
						'type' => 'textarea',
						'placeholder' => 'Message',
					],
				],
				'frontend_available' => true,
				'title_field' => '{{{ label }}}',
			]
		);

		$this->end_controls_section();
	}

	private function add_intelligent_tel_controls( $repeater ) {
		$repeater->add_control(
			'iti_tel',
			[
				'label'      => esc_html__( 'Intelligent', 'jupiterx-core' ),
				'type'       => 'popover_toggle',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'type',
							'operator' => 'in',
							'value'    => [
								'tel',
							],
						],
						[
							'name'     => 'required',
							'operator' => '===',
							'value'    => 'true',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'iti_tel_tel_type',
			[
				'label'       => esc_html__( 'Type', 'jupiterx-core' ),
				'type'        => 'select',
				'default'     => 'all',
				'options'     => [
					'all' => esc_html__( 'All', 'jupiterx-core' ),
					'0'   => esc_html__( 'Fixed Line', 'jupiterx-core' ),
					'1'   => esc_html__( 'Mobile', 'jupiterx-core' ),
					'2'   => esc_html__( 'Fixed Line or Mobile', 'jupiterx-core' ),
					'3'   => esc_html__( 'Toll Free', 'jupiterx-core' ),
					'4'   => esc_html__( 'Premium Rate', 'jupiterx-core' ),
					'5'   => esc_html__( 'Shared Cost', 'jupiterx-core' ),
					'6'   => esc_html__( 'VOIP', 'jupiterx-core' ),
					'7'   => esc_html__( 'Personal Number', 'jupiterx-core' ),
					'8'   => esc_html__( 'Pager', 'jupiterx-core' ),
					'9'   => esc_html__( 'UAN', 'jupiterx-core' ),
					'10'  => esc_html__( 'Voicemail', 'jupiterx-core' ),
				],
				'popover'     => [ 'start' => true ],
				'render_type' => 'template',
				'condition'   => [
					'iti_tel' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'iti_tel_require_area',
			[
				'label'     => esc_html__( 'Require Area Code', 'jupiterx-core' ),
				'type'      => 'switcher',
				'default'     => 'yes',
				'condition' => [
					'iti_tel' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'iti_tel_allow_dropdown',
			[
				'label'       => esc_html__( 'Allow Dropdown', 'jupiterx-core' ),
				'type'        => 'switcher',
				'default'     => 'yes',
				'render_type' => 'template',
				'condition'   => [
					'iti_tel' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'iti_tel_country_include',
			[
				'label'       => esc_html__( 'Only Include Countries', 'jupiterx-core' ),
				'type'        => 'select2',
				'options'     => [],
				'description' => esc_html__( 'Leave empty to include all countries.', 'jupiterx-core' ),
				'label_block' => true,
				'multiple'    => true,
				'render_type' => 'template',
				'condition'   => [
					'iti_tel' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'iti_tel_ip_detect',
			[
				'label'       => esc_html__( 'Auto Detect by IP', 'jupiterx-core' ),
				'type'        => 'switcher',
				'default'     => 'yes',
				'render_type' => 'template',
				'conditions'  => [
					'terms' => [
						[
							'name' => 'iti_tel',
							'operator' => '===',
							'value' => 'yes',
						],
						[
							'name' => 'iti_tel_allow_dropdown',
							'operator' => '===',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'iti_tel_internationalize',
			[
				'label'       => esc_html__( 'Internationalize', 'jupiterx-core' ),
				'type'        => 'switcher',
				'popover'     => [ 'end' => true ],
				'render_type' => 'template',
				'description' => esc_html__( 'Convert entered national numbers to international format on form submit.', 'jupiterx-core' ),
				'condition'   => [
					'iti_tel' => 'yes',
				],
			]
		);
	}

	private function upload_field_controls( $repeater ) {
		$repeater->add_control(
			'file_sizes',
			[
				'label' => __( 'Max size', 'jupiterx-core' ),
				'type' => 'select',
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => 'in',
							'value' => [
								'file',
							],
						],
					],
				],
				'options' => $this->get_upload_file_size_options(),
				'description' => __( 'If you need to increase max upload size please contact your hosting.', 'jupiterx-core' ),
			]
		);

		$repeater->add_control(
			'file_types',
			[
				'label' => __( 'Allowed File Types', 'jupiterx-core' ),
				'type' => 'text',
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => 'in',
							'value' => [
								'file',
							],
						],
					],
				],
				'description' => __( 'Enter the allowed file types, separated by a comma (jpg, gif, pdf, etc).', 'jupiterx-core' ),
			]
		);

		$repeater->add_control(
			'allow_multiple_upload',
			[
				'label' => __( 'Multiple Files', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'true',
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => 'in',
							'value' => [
								'file',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'max_files',
			[
				'label' => __( 'Max Files', 'jupiterx-core' ),
				'type' => 'number',
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => 'in',
							'value' => [
								'file',
							],
						],
						[
							'name' => 'allow_multiple_upload',
							'operator' => '===',
							'value' => 'true',
						],
					],
				],
			]
		);
	}

	private function step_field_controls( $repeater ) {
		$repeater->add_control(
			'step_previous_button',
			[
				'label' => esc_html__( 'Previous Button', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'Back', 'jupiterx-core' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => '===',
							'value' => 'step',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'step_next_button',
			[
				'label' => esc_html__( 'Next Button', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'Proceed', 'jupiterx-core' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => '===',
							'value' => 'step',
						],
					],
				],
			]
		);

		// Step Icon.
		$repeater->add_control(
			'step_icon',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'separator' => 'before',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => '===',
							'value' => 'step',
						],
					],
				],
				'recommended' => [
					'fa-solid' => [
						'chevron-down',
						'angle-down',
						'angle-double-down',
						'caret-down',
						'caret-square-down',
					],
					'fa-regular' => [
						'caret-square-down',
					],
				],
				'skin' => 'inline',
				'label_block' => false,
			]
		);
	}

	public function register_section_submit_button() {
		$this->start_controls_section(
			'section_submit_button',
			[
				'label' => 'raven-form' === $this->get_name() ? esc_html__( 'Buttons', 'jupiterx-core' ) : esc_html__( 'Submit Button', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'submit_button_text',
			[
				'label' => __( 'Text', 'jupiterx-core' ),
				'type' => 'text',
				'default' => __( 'Send', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'submit_button_icon_new',
			[
				'label' => esc_html__( 'Submit Button Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'fa4compatibility' => 'submit_button_icon',
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$this->add_responsive_control(
			'submit_button_width',
			[
				'label' => __( 'Column Width', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => __( 'Default', 'jupiterx-core' ),
					'100' => '100%',
					'80' => '80%',
					'75' => '75%',
					'66' => '66%',
					'60' => '60%',
					'50' => '50%',
					'40' => '40%',
					'33' => '33%',
					'25' => '25%',
					'20' => '20%',
				],
				'default' => '100',
			]
		);

		$this->add_control(
			'hover_effect',
			[
				'label' => __( 'Hover Effects', 'jupiterx-core' ),
				'type' => 'raven_hover_effect',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_settings() {

		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'label',
			[
				'label' => __( 'Label', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'required_mark',
			[
				'label' => __( 'Required Mark', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'actions',
			[
				'label' => __( 'Add Action', 'jupiterx-core' ),
				'type' => 'select2',
				'multiple' => true,
				'options' => Module::get_action_types(),
				'label_block' => true,
				'render_type' => 'ui',
			]
		);

		$this->end_controls_section();
	}

	public function register_section_steps() {
		$this->start_controls_section(
			'section_steps',
			[
				'label' => esc_html__( 'Steps Settings', 'jupiterx-core' ),
			]
		);

		// Steps Type.
		$this->add_control(
			'steps_type',
			[
				'label' => esc_html__( 'Type', 'jupiterx-core' ),
				'type' => 'select',
				'frontend_available' => true,
				'options' => [
					'none'         => esc_html__( 'None', 'jupiterx-core' ),
					'label'        => esc_html__( 'Text', 'jupiterx-core' ),
					'icon'         => esc_html__( 'Icon', 'jupiterx-core' ),
					'number'       => esc_html__( 'Number', 'jupiterx-core' ),
					'progress'     => esc_html__( 'Progress Bar', 'jupiterx-core' ),
					'label_number' => esc_html__( 'Number & Text', 'jupiterx-core' ),
					'label_icon'   => esc_html__( 'Icon & Text', 'jupiterx-core' ),
				],
				'default' => 'number',
			]
		);

		// Steps Icon Shape <<None/Circle/Square/Rounded>>.
		$this->add_control(
			'steps_icon_shape',
			[
				'label' => esc_html__( 'Shape', 'jupiterx-core' ),
				'type' => 'select',
				'frontend_available' => true,
				'options' => [
					'circle' => 'Circle',
					'square' => 'Square',
					'rounded' => 'Rounded',
					'none' => 'None',
				],
				'default' => 'circle',
				'conditions' => [
					'terms' => [
						[
							'name' => 'steps_type',
							'operator' => 'in',
							'value' => [
								'number',
								'icon',
								'label_number',
								'label_icon',
							],
						],
					],
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_messages() {
		$this->start_controls_section(
			'section_messages',
			[
				'label' => __( 'Feedback Messages', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'messages_custom',
			[
				'label' => __( 'Custom Messages', 'jupiterx-core' ),
				'type' => 'switcher',
				'render_type' => 'ui',
			]
		);

		$this->add_control(
			'messages_success',
			[
				'label' => __( 'Success Message', 'jupiterx-core' ),
				'type' => 'text',
				'default' => Module::$messages['success'],
				'label_block' => true,
				'render_type' => 'ui',
				'condition' => [
					'messages_custom' => 'yes',
				],
			]
		);

		$this->add_control(
			'messages_error',
			[
				'label' => __( 'Error Message', 'jupiterx-core' ),
				'type' => 'text',
				'default' => Module::$messages['error'],
				'label_block' => true,
				'render_type' => 'ui',
				'condition' => [
					'messages_custom' => 'yes',
				],
			]
		);

		$this->add_control(
			'messages_required',
			[
				'label' => __( 'Required Message', 'jupiterx-core' ),
				'type' => 'text',
				'default' => Module::$messages['required'],
				'label_block' => true,
				'render_type' => 'ui',
				'condition' => [
					'messages_custom' => 'yes',
				],
			]
		);

		$this->add_control(
			'messages_subscriber',
			[
				'label' => __( 'Subscriber Already Exists Message', 'jupiterx-core' ),
				'type' => 'text',
				'default' => Module::$messages['subscriber'],
				'label_block' => true,
				'render_type' => 'ui',
				'condition' => [
					'messages_custom' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	public function register_section_general() {
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => __( 'General', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'general_column_spacing',
			[
				'label' => __( 'Column Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 7,
				],
				'range' => [
					'px' => [
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-field-group' => 'padding-left: calc( {{SIZE}}{{UNIT}} / 2 );padding-right: calc( {{SIZE}}{{UNIT}} / 2 );',
					'{{WRAPPER}} .raven-form' => 'margin-left: calc( -{{SIZE}}{{UNIT}} / 2 );margin-right: calc( -{{SIZE}}{{UNIT}} / 2 );',
					'{{WRAPPER}} .raven-form__indicators' => 'padding-left: calc( {{SIZE}}{{UNIT}} / 2 );padding-right: calc( {{SIZE}}{{UNIT}} / 2 );',
				],
			]
		);

		$this->add_responsive_control(
			'general_row_spacing',
			[
				'label' => __( 'Row Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function register_section_label() {
		$this->start_controls_section(
			'section_style_label',
			[
				'label' => __( 'Label', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .raven-field-label',
				'scheme' => '3',
			]
		);

		$this->add_responsive_control(
			'label_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-field-group > .raven-field-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function register_section_field() {
		$this->start_controls_section(
			'section_style_field',
			[
				'label' => __( 'Field', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);
		$this->start_controls_tabs( 'field_tabs_state' );

		$this->start_controls_tab(
			'field_tab_state_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'field_tab_background_color_normal',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'field_tab_border_normal',
				'selector' => '{{WRAPPER}} .raven-field',
			]
		);

		$this->add_responsive_control(
			'field_tab_border_radius_normal',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iti__flag-container .iti__selected-flag' => 'border-radius: {{TOP}}{{UNIT}} 0 0 {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'field_tab_box_shadow_normal',
				'exclude' => [
					'box_shadow_position',
				],
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-field',
			]
		);

		$this->add_control(
			'field_tab_placeholder_heading_normal',
			[
				'type' => 'heading',
				'label' => __( 'Placeholder', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'field_tab_color_placeholder',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-field::-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-field::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'field_tab_typography_placeholder',
				'selector' => '{{WRAPPER}} .raven-field::placeholder',
				'scheme' => '3',
			]
		);

		$this->add_control(
			'field_tab_value_heading_normal',
			[
				'type' => 'heading',
				'label' => __( 'Value', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'field_tab_color_value',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'field_tab_typography_value',
				'selector' => '{{WRAPPER}} .raven-field',
				'scheme' => '3',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'field_tab_state_focus',
			[
				'label' => __( 'Focus', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'field_tab_background_color_focus',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'field_tab_border_focus',
				'selector' => '{{WRAPPER}} .raven-field:focus',
			]
		);

		$this->add_responsive_control(
			'field_tab_border_radius_focus',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-field:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'field_tab_box_shadow_focus',
				'exclude' => [
					'box_shadow_position',
				],
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-field:focus',
			]
		);

		$this->add_control(
			'field_tab_placeholder_heading_focus',
			[
				'type' => 'heading',
				'label' => __( 'Placeholder', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'field_tab_color_placeholder_foucus',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field:focus::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-field:focus::-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-field:focus::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'field_tab_typography_placeholder_foucs',
				'selector' => '{{WRAPPER}} .raven-field:focus::placeholder',
				'scheme' => '3',
			]
		);

		$this->add_control(
			'field_tab_value_heading_foucs',
			[
				'type' => 'heading',
				'label' => __( 'Value', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'field_tab_color_value_foucs',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'field_tab_typography_value_foucs',
				'selector' => '{{WRAPPER}} .raven-field:focus',
				'scheme' => '3',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'field_padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .raven-field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function register_section_select() {
		$this->start_controls_section(
			'section_style_select',
			[
				'label' => __( 'Select', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'select_arrow_icon_new',
			[
				'label' => __( 'Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'fa4compatibility' => 'select_arrow_icon',
				'default' => 'fa fa-angle-down',
				'default' => [
					'value' => 'fas fa-angle-down',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'select_arrow_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field-select-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-field-select-arrow > svg, {{WRAPPER}} svg.raven-field-select-arrow' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'select_arrow_size',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => '20',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-field-select-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-field-select-arrow > svg, {{WRAPPER}} svg.raven-field-select-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'select_arrow_vertical_offset',
			[
				'label' => __( 'Vertical Offset', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%', 'vm' ],
				'selectors' => [
					'{{WRAPPER}} .raven-field-select-arrow' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'select_arrow_horizontal_offset',
			[
				'label' => __( 'Horizontal Offset', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => '13',
					'unit' => 'px',
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-field-select-arrow' => is_rtl() ? 'left: {{SIZE}}{{UNIT}};' : 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function register_section_checkbox() {
		$section_title = apply_filters( 'jupiterx-widgets-form-section-style-checkbox-title', esc_html__( 'Checkbox', 'jupiterx-core' ) );

		$this->start_controls_section(
			'section_style_checkbox',
			[
				'label' => $section_title,
				'tab' => 'style',
			]
		);

		remove_all_filters( 'jupiterx-widgets-form-section-style-checkbox-title' );

		$this->add_responsive_control(
			'checkbox_size',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup .raven-field-label' => 'padding-left: calc({{SIZE}}{{UNIT}} + 8px);line-height: calc({{SIZE}}{{UNIT}} + 2px);',
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup .raven-field-label:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup .raven-field-label:after' => 'width: calc({{SIZE}}{{UNIT}} - 8px); height: calc({{SIZE}}{{UNIT}} - 8px);',
					'{{WRAPPER}} .raven-field-type-acceptance .raven-field-subgroup .raven-field-label' => 'padding-left: calc({{SIZE}}{{UNIT}} + 8px);line-height: calc({{SIZE}}{{UNIT}} + 2px);',
					'{{WRAPPER}} .raven-field-type-acceptance .raven-field-subgroup .raven-field-label:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-field-type-acceptance .raven-field-subgroup .raven-field-label:after' => 'width: calc({{SIZE}}{{UNIT}} - 8px); height: calc({{SIZE}}{{UNIT}} - 8px);',
				],
			]
		);

		$this->add_control(
			'checkbox_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup:not(.raven-login-forget-password-wrapper) .raven-field-label' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-field-type-acceptance .raven-field-subgroup .raven-field-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'checkbox_typography',
				'selector' => '{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup:not(.raven-login-forget-password-wrapper) .raven-field-label,{{WRAPPER}} .raven-field-type-acceptance .raven-field-subgroup .raven-field-label',
				'scheme' => '3',
			]
		);

		$this->add_responsive_control(
			'checkbox_spacing_between',
			[
				'label' => __( 'Spacing Between', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-option .raven-field-label' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-field-type-acceptance .raven-field-option .raven-field-label' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-login-remember-me-wrapper label' => is_rtl() ? 'padding-right: calc({{SIZE}}{{UNIT}} + 20px) !important;' : 'padding-left: calc({{SIZE}}{{UNIT}} + 20px) !important;',
				],
			]
		);

		$this->add_responsive_control(
			'checkbox_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup:not(.raven-login-forget-password-wrapper)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .raven-field-type-acceptance .raven-field-subgroup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'checkbox_tabs_state' );

		$this->start_controls_tab(
			'checkbox_tab_state_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'checkbox_tab_background_color_normal',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup .raven-field-label:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .raven-field-type-acceptance .raven-field-subgroup .raven-field-label:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'checkbox_tab_border_normal',
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-form .raven-field-option-checkbox .raven-field + label:before',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'checkbox_tab_box_shadow_normal',
				'selector' => '{{WRAPPER}} .raven-form .raven-field-option-checkbox .raven-field + label:before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'checkbox_tab_state_checked',
			[
				'label' => __( 'Checked', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'checkbox_tab_background_color_checked',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup .raven-field-label:after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .raven-field-type-acceptance .raven-field-subgroup .raven-field-label:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'checkbox_tab_border_checked',
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-form .raven-field-option-checkbox .raven-field:checked + label:before',

			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'checkbox_tab_box_shadow_checked',
				'selector' => '{{WRAPPER}} .raven-form .raven-field-option-checkbox .raven-field:checked + label:before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'checkbox_separator',
			[
				'type' => 'divider',
			]
		);

		$this->add_control(
			'checkbox_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup .raven-field-label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup .raven-field-label:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .raven-field-type-acceptance .raven-field-subgroup .raven-field-label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .raven-field-type-acceptance .raven-field-subgroup .raven-field-label:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function register_section_radio() {
		$this->start_controls_section(
			'section_style_radio',
			[
				'label' => __( 'Radio', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'radio_size',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label' => 'padding-left: calc({{SIZE}}{{UNIT}} + 8px);line-height: calc({{SIZE}}{{UNIT}} + 2px);',
					'{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label:after' => 'width: calc({{SIZE}}{{UNIT}} - 8px); height: calc({{SIZE}}{{UNIT}} - 8px);',
				],
			]
		);

		$this->add_control(
			'radio_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'radio_typography',
				'selector' => '{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label',
				'scheme' => '3',
			]
		);

		$this->add_responsive_control(
			'radio_spacing_between',
			[
				'label' => __( 'Spacing Between', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-radio .raven-field-option' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'radio_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'radio_tabs_state' );

		$this->start_controls_tab(
			'radio_tab_state_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'radio_tab_background_color_normal',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'radio_tab_border_normal',
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label:before',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'radio_tab_box_shadow_normal',
				'selector' => '{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label:before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'radio_tab_state_checked',
			[
				'label' => __( 'Checked', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'radio_tab_background_color_checked',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'radio_tab_border_checked',
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-field-type-radio .raven-field:checked ~ .raven-field-label:before',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'radio_tab_box_shadow_checked',
				'selector' => '{{WRAPPER}} .raven-field-type-radio .raven-field:checked ~ .raven-field-label:before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/** * @SuppressWarnings(PHPMD) */
	public function register_section_button() {
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Buttons', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label' => __( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_height',
			[
				'label' => __( 'Height', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'default' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
					'unit' => 'px',
					'isLinked' => true,
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label'  => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'spaced',
				'prefix_class' => 'raven%s-form-button-align-',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
					'spaced' => [
						'title' => esc_html__( 'Spaced', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
			]
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_tab_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'button_tab_typography_normal',
				'selector' => '{{WRAPPER}} .raven-submit-button, {{WRAPPER}} .raven-submit-button > span',
				'scheme' => '3',
			]
		);

		$this->add_control(
			'button_tab_color_normal',
			[
				'label' => esc_html__( 'Next & Submit Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button:not(.step-button-prev)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_tab_color_normal_prev',
			[
				'label' => esc_html__( 'Previous Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button.step-button-prev' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'button_tab_background_normal',
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-submit-button:not(.step-button-prev)',
			]
		);

		$this->update_control(
			'button_tab_background_normal_background',
			[
				'label' => esc_html__( 'Next & Submit Background', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'button_tab_background_normal_prev',
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-submit-button.step-button-prev',
			]
		);

		$this->update_control(
			'button_tab_background_normal_prev_background',
			[
				'label' => esc_html__( 'Previous Background', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'button_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .raven-submit-button',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'unit'   => 'px',
							'top'    => '1',
							'left'   => '1',
							'right'  => '1',
							'bottom' => '1',
						],
					],
					'color' => [
						'default' => '#2ecc71',
					],
				],
			]
		);

		$this->add_responsive_control(
			'button_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'button_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-submit-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_tab_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'button_tab_typography_hover',
				'selector' => '{{WRAPPER}} .raven-submit-button:hover, {{WRAPPER}} .raven-submit-button:hover span',
				'scheme' => '3',
			]
		);

		$this->add_control(
			'button_tab_color_hover',
			[
				'label' => esc_html__( 'Next & Submit Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button:not(.step-button-prev):hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_tab_color_hover_prev',
			[
				'label' => esc_html__( 'Previous Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button.step-button-prev:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'button_tab_background_hover',
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-submit-button:not(.step-button-prev):hover',
			]
		);

		$this->update_control(
			'button_tab_background_hover_background',
			[
				'label' => esc_html__( 'Next & Submit Background', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'button_tab_background_hover_prev',
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-submit-button.step-button-prev:hover',
			]
		);

		$this->update_control(
			'button_tab_background_hover_prev_background',
			[
				'label' => esc_html__( 'Previous Background', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'button_border_heading_hover',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'button_border_hover',
				'selector' => '{{WRAPPER}} .raven-submit-button:hover',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'unit'   => 'px',
							'top'    => '1',
							'left'   => '1',
							'right'  => '1',
							'bottom' => '1',
						],
					],
					'color' => [
						'default' => '#2ecc71',
					],
				],
			]
		);

		$this->add_responsive_control(
			'button_radius_hover',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'button_box_shadow_hover',
				'exclude' => [
					'box_shadow_position',
				],
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-submit-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_icon_heading',
			[
				'label' => __( 'Icon', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'submit_button_icon_new[value]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'button_icon_size',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 200,
					],
				],
				'condition' => [
					'submit_button_icon_new[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-submit-button svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_icon_space_between',
			[
				'label' => __( 'Space Between', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'condition' => [
					'submit_button_icon_new[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}}.raven-form-button-icon-left .raven-submit-button i, {{WRAPPER}}.raven-form-button-icon-left .raven-submit-button svg' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.raven-form-button-icon-right .raven-submit-button i, {{WRAPPER}}.raven-form-button-icon-right .raven-submit-button svg' => 'margin-left: {{SIZE}}{{UNIT}};',

				],
			]
		);

		$this->add_control(
			'button_icon_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'toggle' => false,
				'default' => 'left',
				'prefix_class' => 'raven-form-button-icon-',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'condition' => [
					'submit_button_icon_new[value]!' => '',
				],
			]
		);

		$this->start_controls_tabs( 'button_icon_tabs' );

		$this->start_controls_tab(
			'button_icon_tabs_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
				'condition' => [
					'submit_button_icon_new[library]!' => [ '', 'svg' ],
				],
			]
		);

		$this->add_control(
			'button_tab_icon_color_normal',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-submit-button svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'submit_button_icon_new[library]!' => [ '', 'svg' ],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_icon_tabs_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
				'condition' => [
					'submit_button_icon_new[library]!' => [ '', 'svg' ],
				],
			]
		);

		$this->add_control(
			'button_tab_icon_color_hover',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-submit-button:hover svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'submit_button_icon_new[library]!' => [ '', 'svg' ],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function register_message_style() {

		$this->start_controls_section(
			'message_text_style',
			[
				'label' => __( 'Messages', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'message_text_typography',
				'selectors' => [
					'{{WRAPPER}} .raven-form-response',
					'{{WRAPPER}} .raven-form .raven-form-text',
				],
				'scheme' => '3',
			]
		);

		$this->add_control(
			'seccess_message_color',
			[
				'label' => __( 'Success Message Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-form-success .raven-form-response' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'error_message_color',
			[
				'label' => __( 'Error Message Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-form-error .raven-form-response' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'inline_message_color',
			[
				'label' => __( 'Inline Message Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-form .raven-form-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/** * @SuppressWarnings(PHPMD) */
	public function register_section_steps_indicator() {
		$this->start_controls_section(
			'section_style_steps_indicator',
			[
				'label' => esc_html__( 'Steps', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'steps_type!' => 'none',
				],
			]
		);

		// Typography.
		$this->add_group_control(
			'typography',
			[
				'name' => 'steps_typography',
				'selector' => '{{WRAPPER}} .raven-form__indicators__indicator, {{WRAPPER}} .raven-form__indicators__indicator__label',
				'conditions' => [
					'terms' => [
						[
							'name' => 'steps_type',
							'operator' => '!in',
							'value' => [
								'icon',
								'progress',
							],
						],
					],
				],
			]
		);

		// Gap(spacing).
		$this->add_responsive_control(
			'steps_gap',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--raven-form-steps-indicators-spacing: {{SIZE}}{{UNIT}}',
				],
			]
		);

		// Icon size.
		$this->add_responsive_control(
			'steps_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px' ],
				'conditions' => [
					'terms' => [
						[
							'name' => 'steps_type',
							'operator' => 'in',
							'value' => [
								'icon',
								'label_icon',
							],
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--raven-form-steps-indicator-icon-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		// Padding.
		$this->add_responsive_control(
			'steps_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 30,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}}' => '--raven-form-steps-indicator-padding: {{SIZE}}{{UNIT}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'steps_type',
							'operator' => '!in',
							'value' => [
								'label',
								'progress',
							],
						],
					],
				],
			]
		);

		// TAB START ► Steps State → <<INACTIVE/ACTIVE/COMPLETED>>.
		$this->start_controls_tabs(
			'steps_state',
			[
				'conditions' => [
					'terms' => [
						[
							'name' => 'steps_type',
							'operator' => '!in',
							'value' => [
								'progress',
							],
						],
					],
				],
			]
		);

		// TAB ► INACTIVE.
		$this->start_controls_tab(
			'tab_steps_state_inactive',
			[
				'label' => esc_html__( 'Inactive', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'step_inactive_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--raven-form-steps-indicator-inactive-primary-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'step_inactive_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}' => '--raven-form-steps-indicator-inactive-secondary-color: {{VALUE}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'steps_type',
							'operator' => 'in',
							'value' => [
								'icon',
								'number',
								'label_icon',
								'label_number',
							],
						],
					],
				],
			]
		);

		$this->end_controls_tab();

		// TAB ► ACTIVE.
		$this->start_controls_tab(
			'tab_steps_state_active',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'step_active_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--raven-form-steps-indicator-active-primary-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'step_active_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}' => '--raven-form-steps-indicator-active-secondary-color: {{VALUE}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'steps_type',
							'operator' => 'in',
							'value' => [
								'icon',
								'number',
								'label_icon',
								'label_number',
							],
						],
					],
				],
			]
		);

		$this->end_controls_tab();

		// TAB ► COMPLETED.
		$this->start_controls_tab(
			'tab_steps_state_completed',
			[
				'label' => esc_html__( 'Completed', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'step_completed_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--raven-form-steps-indicator-completed-primary-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'step_completed_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#ffffff',
				'condition' => [
					'steps_icon_shape!' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--raven-form-steps-indicator-completed-secondary-color: {{VALUE}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'steps_type',
							'operator' => 'in',
							'value' => [
								'icon',
								'number',
								'label_icon',
								'label_number',
							],
						],
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'step_divider_color',
			[
				'label' => esc_html__( 'Divider Color', 'jupiterx-core' ),
				'type' => 'color',
				'separator' => 'before',
				'condition' => [
					'steps_type!' => 'progress',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--raven-form-steps-divider-color: {{VALUE}};',
				],
			]
		);

		// Divider Width.
		$this->add_responsive_control(
			'step_divider_width',
			[
				'label' => esc_html__( 'Divider Width', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 1,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px' ],
				'condition' => [
					'steps_type!' => 'progress',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--raven-form-steps-divider-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		// Divider Gap.
		$this->add_responsive_control(
			'step_divider_gap',
			[
				'label' => esc_html__( 'Divider Gap', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%' ],
				'condition' => [
					'steps_type!' => 'progress',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--raven-form-steps-divider-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		// Progress Bar Color.
		$this->add_control(
			'step_progress_bar_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#2ecc71',
				'condition' => [
					'steps_type' => 'progress',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--raven-form-steps-indicator-progress-color: {{VALUE}};',
				],
			]
		);

		// Progress Bar Background Color.
		$this->add_control(
			'step_progress_bar_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#7a7a7a',
				'condition' => [
					'steps_type' => 'progress',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--raven-form-steps-indicator-progress-background-color: {{VALUE}};',
				],
			]
		);

		// Progress Bar Height.
		$this->add_responsive_control(
			'step_progress_bar_height',
			[
				'label' => esc_html__( 'Height', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px' ],
				'condition' => [
					'steps_type' => 'progress',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--raven-form-steps-indicator-progress-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		// Progress Bar Border Radius.
		$this->add_control(
			'step_progress_bar_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px' ],
				'condition' => [
					'steps_type' => 'progress',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--raven-form-steps-indicator-progress-border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		// Progress Bar Percentage Heading.
		$this->add_control(
			'step_progress_bar_percentage_heading',
			[
				'label' => esc_html__( 'Percentage', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'steps_type' => 'progress',
				],
			]
		);

		// Progress Bar Percentage Typography.
		$this->add_group_control(
			'typography',
			[
				'name' => 'step_progress_bar_percentage__typography',
				'selector' => '{{WRAPPER}} .raven-form__indicators__indicator__progress__meter',
				'condition' => [
					'steps_type' => 'progress',
				],
			]
		);

		// Progress Bar Percentage Color.
		$this->add_control(
			'step_progress_bar_percentage_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'steps_type' => 'progress',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--raven-form-steps-indicator-progress-meter-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/** * @SuppressWarnings(PHPMD) */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$fields   = $settings['fields'];
		$steps    = $this->get_step_fields( $fields );
		$is_step  = 0 !== count( $steps );

		$this->add_form_attributes( $settings, $is_step );
		$this->add_button_wrapper_attributes( $settings );

		?>
		<form <?php echo $this->get_render_attribute_string( 'form' ); ?>>
			<input type="hidden" name="post_id" value="<?php echo Utils::get_current_post_id(); ?>" />
			<input type="hidden" name="form_id" value="<?php echo $this->get_id(); ?>" />
			<?php

			// Steps disabled scenario (seperated for backward compatibilty).
			if ( ! $is_step ) {
				foreach ( $fields as $field ) {
					if ( 'step' !== $field['type'] ) {
						Module::render_field( $this, $field );
					}
				}

				?>
					<div <?php echo $this->get_render_attribute_string( 'button-wrapper' ); ?>>
							<?php
								$this->render_submit_button( $settings );
							?>
					</div>
				</form>

				<?php
				if ( $this->has_address_field( $fields ) ) {
					$this->autocomplete_address_fields();
				}

				return;
			}

			// Steps enabled scenario.
			$this->render_step_indicator( $steps );

			foreach ( $steps as $step_key => $step ) {

				$this->add_render_attribute(
					'fields-step-wrapper-' . $step_key,
					[
						'class' => 'raven-flex raven-flex-wrap raven-flex-bottom fields-step-wrapper' . ( $step_key > 0 ? ' elementor-hidden' : '' ),
						'data-step-id' => $step_key,
					]
				);

				?>
				<div <?php echo $this->get_render_attribute_string( 'fields-step-wrapper-' . $step_key ); ?>>
				<?php

				$is_last_step = count( $steps ) - 1 === $step_key;
				$start_field  = $step['pos'] + 1;
				$end_field    = $is_last_step ? count( $fields ) : $steps[ $step_key + 1 ]['pos'];

				for ( $i = $start_field; $i < $end_field; $i++ ) {
					if ( isset( $fields[ $i ] ) ) {
						Module::render_field( $this, $fields[ $i ] );
					}
				}

				$single_button_attr = 0 === $step_key ? 'single-button' : '';
				?>
						<div <?php echo $this->get_render_attribute_string( 'button-wrapper' ) . $single_button_attr; ?>>
						<?php
							$this->render_step_buttons( $steps, $step_key, $settings['hover_effect'] );

							if ( $is_last_step ) {
								$this->render_submit_button( $settings );
							}
						?>
						</div>
					</div>
				<?php
			}
		?>
		</form>

		<?php
		if ( $this->has_address_field( $fields ) ) {
			$this->autocomplete_address_fields();
		}
	}

	protected function autocomplete_address_fields() {
		$google_api_key = get_option( 'elementor_raven_google_api_key' );

		if ( empty( $google_api_key ) ) {
			return;
		}
		// phpcs:disable WordPress.WP.EnqueuedResources
		?>
		<script>
			function initRavenAddressFieldsAutocomplete() {
				var addressFields =  document.querySelectorAll('.raven-form input[data-type="address"]')
				for (var i = 0; i < addressFields.length; i++) {
					var autocomplete = new google.maps.places.Autocomplete(addressFields.item(i), {types: ['geocode']});
					autocomplete.setFields(['address_component']);
				}
			}
		</script>
		<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_api_key; ?>&libraries=places&callback=initRavenAddressFieldsAutocomplete" async defer></script>
		<?php
		// phpcs:enable WordPress.WP.EnqueuedResources
	}

	protected function has_address_field( $fields ) {
		foreach ( $fields as $field ) {
			if ( 'address' === $field['type'] ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Renders submit button of the form.
	 *
	 * @param Array $settings widget settings
	 * @since 2.5.0
	 */
	private function render_submit_button( $settings ) {
		$this->add_render_attribute(
			'submit-button', [
				'class' => 'raven-submit-button' . ( $settings['hover_effect'] ? ' elementor-animation-' . $settings['hover_effect'] : '' ),
				'type'  => 'submit',
			]
		);

		?>
		<button <?php echo $this->get_render_attribute_string( 'submit-button' ); ?>>
			<?php $this->render_submit_icon(); ?>
			<span><?php echo esc_html( $settings['submit_button_text'] ); ?></span>
		</button>
		<?php
}

	protected function render_submit_icon() {
		$settings          = $this->get_active_settings();
		$migration_allowed = Elementor::$instance->icons_manager->is_migration_allowed();
		$migrated          = isset( $settings['__fa4_migrated']['submit_button_icon_new'] );
		$is_new            = empty( $settings['submit_button_icon'] ) && $migration_allowed;

		if ( ! empty( $settings['submit_button_icon'] ) || ! empty( $settings['submit_button_icon_new']['value'] ) ) :
			if ( ! empty( $settings['submit_button_icon_new']['value'] || $is_new || $migrated ) ) {
				Elementor::$instance->icons_manager->render_icon( $settings['submit_button_icon_new'], [ 'aria-hidden' => 'true' ] );
			} else {
				?>
			<i class="<?php echo esc_attr( $settings['submit_button_icon'] ); ?>" aria-hidden="true"></i>
				<?php
			}
		endif;
	}

	/**
	 * Creates array of upload sizes based on server limits
	 * to use in the file_sizes control
	 *
	 * @since 1.20.0
	 * @access private
	 *
	 * @return array
	 */
	private function get_upload_file_size_options() {
		$max_file_size = wp_max_upload_size() / pow( 1024, 2 ); //MB

		$sizes = [];

		for ( $file_size = 1; $file_size <= $max_file_size; $file_size++ ) {
			$sizes[ $file_size ] = $file_size . 'MB';
		}

		return $sizes;
	}

	/**
	 * Adds attributes required to render the form in render() function.
	 *
	 * @param Array $settings widget settings.
	 * @param bool $is_step_form pass true of it is a multi-step form.
	 * @since 2.5.0
	 */
	private function add_form_attributes( $settings, $is_step_form ) {
		$this->add_render_attribute( 'form', [
			'class' => 'raven-form raven-flex raven-flex-wrap raven-flex-bottom',
			'method' => 'post',
			'name' => $settings['form_name'],
		] );

		if ( empty( $settings['required_mark'] ) ) {
			$this->add_render_attribute(
				'form',
				'class',
				'raven-hide-required-mark'
			);
		}

		if ( $is_step_form ) {
			$this->add_render_attribute( 'form', 'data-step' );
		}
	}

	/**
	 * Adds attributes required to render wrapper of buttons section inside form in render() function.
	 *
	 * @param Array $settings widget settings.
	 * @since 2.5.0
	 */
	private function add_button_wrapper_attributes( $settings ) {
		$this->add_render_attribute(
			'button-wrapper',
			'class',
			'raven-field-group raven-field-type-submit-button elementor-column elementor-col-' . $settings['submit_button_width']
		);

		if ( ! empty( $settings['submit_button_width_tablet'] ) ) {
			$this->add_render_attribute(
				'button-wrapper',
				'class',
				'elementor-md-' . $settings['submit_button_width_tablet']
			);
		}

		if ( ! empty( $settings['submit_button_width_mobile'] ) ) {
			$this->add_render_attribute(
				'button-wrapper',
				'class',
				'elementor-sm-' . $settings['submit_button_width_mobile']
			);
		}
	}

	/**
	 * Retrieves settings of form fields whose type is "step".
	 *
	 * @param Array $fields form fields settings.
	 * @return Array steps settings
	 * @since 2.5.0
	 */
	private function get_step_fields( $fields ) {
		$steps   = [];
		$counter = 0;

		foreach ( $fields as $field ) {
			if ( 'step' === $field['type'] ) {
				$field['pos'] = $counter;
				$steps[]      = $field;
			}

			$counter++;
		}

		return $steps;
	}

	/**
	 * Renders steps indicator of a multi-step form.
	 *
	 * @param array $steps steps settings.
	 * @since 2.5.0
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	private function render_step_indicator( $steps ) {
		$step_type  = $this->get_settings_for_display()['steps_type'];
		$step_shape = $this->get_settings_for_display()['steps_icon_shape'];

		if ( 'none' === $step_type ) {
			return;
		}

		// Outermost wrapper.
		$this->add_render_attribute(
			'step-indicators-wrapper',
			'class',
			'raven-form__indicators raven-form__indicators--type-' . $step_type
		);

		// Separator.
		$this->add_render_attribute(
			'step-indicators-separator',
			'class',
			'raven-form__indicators__indicator__separator'
		);

		// Indicator activity status wrapper.
		$this->add_render_attribute(
			'step-indicators-active',
			'class',
			'raven-form__indicators__indicator raven-form__indicators__indicator--state-active'
		);

		$this->add_render_attribute(
			'step-indicators-inactive',
			'class',
			'raven-form__indicators__indicator raven-form__indicators__indicator--state-inactive'
		);

		// Indicator mode (type and shape) wrapper.
		foreach ( [ 'label', 'number', 'icon', 'progress' ] as $type ) {
			$this->add_render_attribute(
				'step-indicators-' . $type,
				'class',
				'raven-form__indicators__indicator__' . $type
			);
		}

		foreach ( [ 'number', 'icon' ] as $type ) {
			$this->add_render_attribute(
				'step-indicators-' . $type,
				'class',
				'raven-form__indicators__indicator--shape-' . $step_shape
			);
		}

		// Indicator progress meter.
		$this->add_render_attribute(
			'step-indicators-progress-meter',
			'class',
			'raven-form__indicators__indicator__progress__meter'
		);

		// Rendering.
		?>
		<div <?php echo $this->get_render_attribute_string( 'step-indicators-wrapper' ); ?>>
			<?php
				// Rendering of progress type (distinguished from other types).
				if ( 'progress' === $step_type ) {
					?>
						<div <?php echo $this->get_render_attribute_string( 'step-indicators-progress' ); ?>>
							<div <?php echo $this->get_render_attribute_string( 'step-indicators-progress-meter' ); ?>>
								<?php echo ( round( 100 / count( $steps ) ) ); ?>%
							</div>
						</div>
					</div>
					<?php
					return;
				}

				// Rendering of other indicator types.
				foreach ( $steps as $key => $step ) {
					$status_attr = $this->get_render_attribute_string( 0 === $key ? 'step-indicators-active' : 'step-indicators-inactive' );

					switch ( $step_type ) {
						case 'label':
							?>
							<div <?php echo $status_attr; ?>>
								<label <?php echo $this->get_render_attribute_string( 'step-indicators-label' ); ?>>
									<?php echo $step['label']; ?>
								</label>
							</div>
							<?php
							break;

						case 'number':
							?>
							<div <?php echo $status_attr; ?>>
								<div <?php echo $this->get_render_attribute_string( 'step-indicators-number' ); ?>>
									<?php echo ( $key + 1 ); ?>
								</div>
							</div>
							<?php
							break;

						case 'label_number':
							?>
							<div <?php echo $status_attr; ?>>
								<div <?php echo $this->get_render_attribute_string( 'step-indicators-number' ); ?>>
									<?php echo ( $key + 1 ); ?>
								</div>
								<label <?php echo $this->get_render_attribute_string( 'step-indicators-label' ); ?>>
									<?php echo $step['label']; ?>
								</label>
							</div>
							<?php
							break;

						case 'icon':
							?>
							<div <?php echo $status_attr; ?>>
								<div <?php echo $this->get_render_attribute_string( 'step-indicators-icon' ); ?>>
									<?php echo $this->get_step_icon_render_string( $step['step_icon'] ); ?>
								</div>
							</div>
							<?php
							break;

						case 'label_icon':
							?>
							<div <?php echo $status_attr; ?>>
								<div <?php echo $this->get_render_attribute_string( 'step-indicators-icon' ); ?>>
									<?php echo $this->get_step_icon_render_string( $step['step_icon'] ); ?>
								</div>
								<label <?php echo $this->get_render_attribute_string( 'step-indicators-label' ); ?>>
									<?php echo $step['label']; ?>
								</label>
							</div>
							<?php
							break;
						}

					// Render separator after indicator for all but the last step.
					if ( $key < count( $steps ) - 1 ) {
						?>
						<div <?php echo $this->get_render_attribute_string( 'step-indicators-separator' ); ?>></div>
						<?php
					}
				}
			?>
		</div>

		<?php
		return true;
	}

	/**
	 * Renders Next and Previous buttons of a multi-step form.
	 *
	 * @param Array $step steps settings.
	 * @param int $step_key step number on a zero basis.
	 * @param string $hover_effect type of hover effect.
	 * @since 2.5.0
	 */
	private function render_step_buttons( $steps, $step_key, $hover_effect ) {
		$next_label     = esc_html( $steps[ $step_key ]['step_next_button'] );
		$previous_label = esc_html( $steps[ $step_key ]['step_previous_button'] );

		$this->add_render_attribute(
			'step_buttons-next-' . $step_key, [
				'type' => 'button',
				'class' => 'raven-submit-button step-button-next' . ( $hover_effect ? ' elementor-animation-' . $hover_effect : '' ),
				'data-step-key' => $step_key,
			]
		);

		$this->add_render_attribute(
			'step_buttons-previous-' . $step_key, [
				'type' => 'button',
				'class' => 'raven-submit-button step-button-prev' . ( $hover_effect ? ' elementor-animation-' . $hover_effect : '' ),
				'data-step-key' => $step_key,
			]
		);

		// Rendering.
		if ( 0 !== $step_key ) {
			?>
			<button <?php echo $this->get_render_attribute_string( 'step_buttons-previous-' . $step_key ); ?>>
				<?php echo $previous_label; ?>
			</button>
			<?php
		}

		if ( $step_key < count( $steps ) - 1 ) {
			?>
			<button <?php echo $this->get_render_attribute_string( 'step_buttons-next-' . $step_key ); ?>>
				<?php echo $next_label; ?>
			</button>
			<?php
		}
	}

	/**
	 * Retrieve render string of the step icon for it's indicator.
	 *
	 * @param Array $icon_data includes [library] and [value].
	 * @return string
	 * @since 2.5.0
	 */
	private function get_step_icon_render_string( $icon_data ) {
		$font_icon    = '';
		$icon_fa      = '';
		$icon_svg_url = '';

		if ( Elementor::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) && $icon_data['value'] ) {
			if ( 'svg' === $icon_data['library'] ) {
				$font_icon = \Elementor\Icons_Manager::render_uploaded_svg_icon( $icon_data['value'] );
			} else {
				$font_icon = \Elementor\Icons_Manager::render_font_icon( $icon_data );
			}
		}

		if ( 'svg' !== $icon_data['library'] && $icon_data['value'] ) {
			$icon_fa = $icon_data['value'];
		}

		if ( 'svg' === $icon_data['library'] && $icon_data['value'] ) {
			$icon_svg_url = $icon_data['value']['url'];
		}

		// ► Process scenarios ◄

		// 1: if font icon is available, it's preferred.
		if ( ! empty( $font_icon ) ) {
			return $font_icon;
		}

		// 2: Otherwise, when the user has used font awesome option.
		if ( ! empty( $icon_fa ) ) {
			return '<i class="' . esc_attr( $icon_fa ) . '"></i>';
		}

		// 3: Otherwise, when the user has used upload svg option.
		return '<object type="image/svg+xml" data="' . esc_attr( $icon_svg_url ) . '"></object>';
	}
}
