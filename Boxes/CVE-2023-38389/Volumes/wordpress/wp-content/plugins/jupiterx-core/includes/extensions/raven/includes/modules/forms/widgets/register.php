<?php

namespace JupiterX_Core\Raven\Modules\Forms\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Modules\Forms\Module;
use JupiterX_Core\Raven\Utils;
use JupiterX_Core\Raven\Modules\Forms\Widgets\Form;
use Elementor\Plugin as Elementor;

/**
 * Register widget class.
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 *
 * @since 2.0.0
 */
class Register extends Form {
	public function get_name() {
		return 'raven-register';
	}

	public function get_title() {
		return __( 'Register', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-register';
	}

	private function maps() {
		return [
			'user_email'    => __( 'Email', 'jupiterx-core' ),
			'first_name'    => __( 'First name', 'jupiterx-core' ),
			'last_name'     => __( 'Last name', 'jupiterx-core' ),
			'full_name'     => __( 'Full name', 'jupiterx-core' ),
			'phone'         => __( 'Phone number', 'jupiterx-core' ),
			'user_password' => __( 'Password', 'jupiterx-core' ),
			'custom_meta'   => __( 'Custom user meta', 'jupiterx-core' ),
			'newsletter'    => __( 'Newsletter Opt-in check', 'jupiterx-core' ),
		];
	}

	protected function register_controls() {
		$this->register_section_form_fields();
		$this->register_section_settings();
		$this->register_section_submit_button();
		$this->register_custom_messages();
		$this->update_section_submit_button();
		// Styles.
		$this->register_section_general();
		$this->register_section_label();
		$this->register_section_field();
		$this->register_section_select();
		$this->register_section_checkbox();
		$this->register_section_radio();
		$this->register_section_button();
		$this->register_message_style();
	}

	private function register_section_form_fields() {
		$this->start_controls_section(
			'section_form_fields',
			[
				'label' => __( 'Form Fields', 'jupiterx-core' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'type',
			[
				'label' => __( 'Type', 'jupiterx-core' ),
				'type' => 'select',
				'options' => Module::get_field_types(),
				'default' => 'text',
			]
		);

		$repeater->add_control(
			'map_to',
			[
				'label' => __( 'Map to', 'jupiterx-core' ),
				'type' => 'select',
				'options' => $this->maps(),
				'condition' => [
					'type!' => [ 'recaptcha', 'recaptcha_v3' ],
				],
			]
		);

		$repeater->add_control(
			'meta_id',
			[
				'label' => __( 'Meta ID', 'jupiterx-core' ),
				'type' => 'text',
				'placeholder' => 'e.g. _user_preferences',
				'condition' => [
					'map_to' => 'custom_meta',
				],
			]
		);

		$repeater->add_control(
			'label',
			[
				'label' => __( 'Label', 'jupiterx-core' ),
				'type' => 'text',
				'condition' => [
					'map_to!' => 'newsletter',
				],
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
							],
						],
						[
							'name' => 'map_to',
							'operator' => '!in',
							'value' => [
								'newsletter',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'newsletter_top_raw',
			[
				'type' => 'raw_html',
				/* translators: %1$s : open tag %2$s: close tag */
				'raw' => sprintf( __( '%1$s Please set `Type` field to `Acceptance` %2$s', 'jupiterx-core' ), '<span style="color:red">', '</span>' ),
				'content_classes' => 'elementor-control-field-description elementor-alert-warning',
				'condition' => [
					'map_to' => 'newsletter',
					'type!' => 'acceptance',
				],
			]
		);

		$repeater->add_control(
			'acceptance_text',
			[
				'label' => __( 'Text', 'jupiterx-core' ),
				'type' => 'textarea',
				'rows' => 10,
				'default' => __( 'I’d like to subscribe to {Site Name} newsletter to get product updates & news and more.', 'jupiterx-core' ),
				'placeholder' => __( 'Type your text here', 'jupiterx-core' ),
				'description' => __( '{Site Name} Will be converted to your site name.', 'jupiterx-core' ),
				'condition' => [
					'type' => 'acceptance',
				],
			]
		);

		$repeater->add_control(
			'checked_by_default',
			[
				'label' => __( 'Checked by default', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => true,
				'default' => 'no',
				'condition' => [
					'map_to' => 'newsletter',
					'type' => 'acceptance',
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
						[
							'name' => 'map_to',
							'operator' => '!in',
							'value' => [
								'newsletter',
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
							],
						],
						[
							'name' => 'map_to',
							'operator' => '!in',
							'value' => [
								'newsletter',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'confirm_password',
			[
				'label' => __( 'Confirm Password?', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'true',
				'condition' => [
					'map_to' => 'user_password',
				],
			]
		);

		$repeater->add_control(
			'confirm_password_label',
			[
				'label' => esc_html__( 'Confirm Password Label', 'jupiterx-core' ),
				'type' => 'text',
				'label_block' => true,
				'default' => esc_html__( 'Confirm password', 'jupiterx-core' ),
				'condition' => [
					'map_to'           => 'user_password',
					'confirm_password' => 'true',
				],
			]
		);

		$repeater->add_control(
			'confirm_password_placeholder',
			[
				'label' => esc_html__( 'Confirm Password Placeholder', 'jupiterx-core' ),
				'type' => 'text',
				'label_block' => true,
				'default' => esc_html__( 'Confirm password', 'jupiterx-core' ),
				'condition' => [
					'map_to'           => 'user_password',
					'confirm_password' => 'true',
				],
			]
		);

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
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_theme',
			[
				'name' => 'recaptcha_theme',
				'label' => __( 'Theme', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'light',
				'options' => [
					'light' => __( 'Light', 'jupiterx-core' ),
					'dark' => __( 'Dark', 'jupiterx-core' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => 'in',
							'value' => [
								'recaptcha',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_size',
			[
				'name' => 'recaptcha_size',
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'normal',
				'options' => [
					'normal' => __( 'Normal', 'jupiterx-core' ),
					'compact' => __( 'Compact', 'jupiterx-core' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => 'in',
							'value' => [
								'recaptcha',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_badge',
			[
				'name' => 'recaptcha_badge',
				'label' => esc_html__( 'Badge', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'inline',
				'options' => [
					'inline' => esc_html__( 'Inline', 'jupiterx-core' ),
					'bottomright' => esc_html__( 'Bottom Right', 'jupiterx-core' ),
					'bottomleft' => esc_html__( 'Bottom Left', 'jupiterx-core' ),
				],
				'condition' => [
					'type' => 'recaptcha_v3',
				],
			]
		);

		$repeater->add_control(
			'field_value',
			[
				'label' => __( 'Default Value', 'jupiterx-core' ),
				'type' => 'text',
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => 'in',
							'value' => [
								'hidden',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'email_note',
			[
				'type' => 'raw_html',
				'raw' => __( 'Note that you can not remove this field, This filed will update username and email address user fields.', 'jupiterx-core' ),
				'content_classes' => 'elementor-control-field-description',
				'condition' => [
					'map_to' => 'user_email',
				],
			]
		);

		$repeater->add_control(
			'full_name_note',
			[
				'type' => 'raw_html',
				'raw' => __( 'This Field will automatically split Full Name and update First Name, Last Name. It will also update Display Name and Nickname fields too.', 'jupiterx-core' ),
				'content_classes' => 'elementor-control-field-description',
				'condition' => [
					'map_to' => 'full_name',
				],
			]
		);

		$repeater->add_control(
			'newsletter_consent_note',
			[
				'type' => 'raw_html',
				'raw' => __( 'This field will let you conditionally send form data to third party subscription tools.', 'jupiterx-core' ),
				'content_classes' => 'elementor-control-field-description',
				'condition' => [
					'map_to' => 'newsletter',
				],
			]
		);

		$this->add_control(
			'fields',
			[
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'frontend_available' => true,
				'default' => [
					[
						'type' => 'email',
						'map_to' => 'user_email',
						'label' => __( 'Email', 'jupiterx-core' ),
						'required' => 'true',
					],
					[
						'type' => 'text',
						'label' => __( 'Full Name', 'jupiterx-core' ),
						'required' => 'true',
						'map_to' => 'full_name',
					],
					[
						'type' => 'password',
						'label' => __( 'Password', 'jupiterx-core' ),
						'map_to' => 'user_password',
						'confirm_password' => 'true',
						'required' => 'true',
					],
					[
						'type' => 'recaptcha',
						'label' => __( 'reCaptcha', 'jupiterx-core' ),
					],
				],
				'title_field' => '{{{ label }}}',
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

		$this->add_control(
			'hidden_actions',
			[
				'label' => __( 'Actions', 'jupiterx-core' ),
				'type' => 'text',
				'classes' => 'elementor-control-type-hidden',
				'default' => [ 'register' ],
			]
		);

		$this->end_controls_section();
	}

	private function register_custom_messages() {
		$this->start_controls_section(
			'register_custom_messages',
			[
				'label' => __( 'Custom Messages', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'enable_custom_messages',
			[
				'label' => __( 'Custom Messages', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => __( 'Enable', 'jupiterx-core' ),
				'label_off' => __( 'Disable', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'custom_message_success',
			[
				'label' => __( 'Success', 'jupiterx-core' ),
				'type' => 'text',
				'default' => __( 'You have been successfully registered.', 'jupiterx-core' ),
				'condition' => [
					'enable_custom_messages' => 'yes',
				],
			]
		);

		$this->add_control(
			'custom_message_email_exist',
			[
				'label' => __( 'Emails Already Exist', 'jupiterx-core' ),
				'type' => 'text',
				'default' => __( 'An account is already registered with this email address. Please sign in to access your existing account.', 'jupiterx-core' ),
				'condition' => [
					'enable_custom_messages' => 'yes',
				],
			]
		);

		$this->add_control(
			'custom_message_error_not_same_password',
			[
				'label' => __( 'Password And Confirm Password Is Not Same', 'jupiterx-core' ),
				'type' => 'text',
				'default' => __( 'Your password and confirmation password do not match.', 'jupiterx-core' ),
				'label_block' => true,
				'condition' => [
					'enable_custom_messages' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	private function update_section_submit_button() {
		$this->update_control( 'submit_button_text', [
			'default' => __( 'Register', 'jupiterx-core' ),
		] );
	}

	protected function render() {
		if ( is_user_logged_in() && ! current_user_can( 'administrator' ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		$fields   = $settings['fields'];

		$this->add_render_attribute( 'form', [
			'class'  => 'raven-form raven-flex raven-flex-wrap raven-flex-bottom',
			'method' => 'post',
		] );

		$this->add_render_attribute(
			'submit-button',
			'class',
			'raven-field-group raven-field-type-submit-button elementor-column elementor-col-' . $settings['submit_button_width']
		);

		if ( ! empty( $settings['submit_button_width_tablet'] ) ) {
			$this->add_render_attribute(
				'submit-button',
				'class',
				'elementor-md-' . $settings['submit_button_width_tablet']
			);
		}

		if ( ! empty( $settings['submit_button_width_mobile'] ) ) {
			$this->add_render_attribute(
				'submit-button',
				'class',
				'elementor-sm-' . $settings['submit_button_width_mobile']
			);
		}

		if ( $settings['hover_effect'] ) {
			$this->add_render_attribute(
				'submit-button',
				'class',
				'elementor-animation-' . $settings['hover_effect']
			);
		}

		?>
		<form <?php echo $this->get_render_attribute_string( 'form' ); ?>>
			<input type="hidden" name="post_id" value="<?php echo Utils::get_current_post_id(); ?>" />
			<input type="hidden" name="form_id" value="<?php echo $this->get_id(); ?>" />
			<?php
				$maps        = [];
				$unique_maps = $this->maps();
				foreach ( $fields as $key => $field ) {
					if ( in_array( $field['map_to'], $maps, true ) ) {
						?>
							<div class="raven-flex-wrap raven-field-group elementor-alert elementor-alert-info elementor-column elementor-col-100">
								<?php
									/* translators: %s: map field */
									echo sprintf( __( 'Just one field can be mapped to %s, please remove one.', 'jupiterx-core' ), $unique_maps[ $field['map_to'] ] );
									?>
							</div>
						<?php
						continue;
					}

					// Convert {Site Name} to site title.
					if ( $field['acceptance_text'] ) {
						$site_title               = get_bloginfo( 'name' );
						$field['acceptance_text'] = str_replace( '{Site Name}', $site_title, $field['acceptance_text'] );
					}

					if ( 'newsletter' === $field['map_to'] ) {
						$field['_id'] = 'register_acceptance';
					}

					Module::render_field( $this, $field );
					$this->extra_fields( $field, $settings );

					if ( 'custom_meta' !== $field['map_to'] ) {
						$maps[] = $field['map_to'];
					}
				}
			?>
			<div <?php echo $this->get_render_attribute_string( 'submit-button' ); ?>>
				<button type="submit" class="raven-submit-button">
					<?php Elementor::$instance->icons_manager->render_icon( $settings['submit_button_icon_new'], [ 'aria-hidden' => 'true' ] ); ?>
					<span><?php echo $settings['submit_button_text']; ?></span>
				</button>
			</div>
		</form>
		<?php if ( current_user_can( 'administrator' ) ) : ?>
			<div class="elementor-alert elementor-alert-danger">
				<?php echo __( 'This element is hidden for logged-in users and visible only to logged-out users and also administrator for demo purposes.', 'jupiterx-core' ); ?>
			</div>
		<?php endif;
	}

	private function extra_fields( $field, $settings ) {
		if ( 'user_password' === $field['map_to'] && 'true' === $field['confirm_password'] ) {
			$class = 'raven-flex-wrap raven-field-required raven-field-type-text raven-field-group elementor-column elementor-col-' . $field['width'];
			?>
				<div class="<?php echo $class; ?>" >
					<?php if ( 'yes' === $settings['label'] ) : ?>
					<label class="raven-field-label"><?php echo esc_html( $field['confirm_password_label'] ); ?> </label>
					<?php endif; ?>
					<input
						required="required"
						class="raven-field"
						type="password"
						name="confirm-password"
						data-parent="form-field-<?php echo esc_attr( $field['_id'] ); ?>"
						placeholder="<?php echo esc_attr( $field['confirm_password_placeholder'] ); ?>"
					>
				</div>
			<?php
		}
	}
}
