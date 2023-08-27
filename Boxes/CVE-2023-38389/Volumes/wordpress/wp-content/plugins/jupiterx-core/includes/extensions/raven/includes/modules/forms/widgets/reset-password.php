<?php

namespace JupiterX_Core\Raven\Modules\Forms\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Modules\Forms\Module;
use JupiterX_Core\Raven\Utils;
use JupiterX_Core\Raven\Modules\Forms\Widgets\Form;
use Elementor\Plugin as Elementor;

/**
 * Reset password widget class.
 *
 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @since 2.0.0
 */
class Reset_Password extends Form {

	public function get_name() {
		return 'raven-reset-password';
	}

	public function get_title() {
		return __( 'Reset Password', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-reset-password';
	}

	protected function register_controls() {
		$this->register_section_form_fields();
		$this->register_section_submit_button();
		$this->update_section_submit_button();
		$this->register_section_settings();
		$this->register_messages_section_controls();
		$this->register_section_general();
		$this->register_section_label();
		$this->register_section_field();
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
			'enable',
			[
				'label' => __( 'Enable', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'true',
				'frontend_available' => true,
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => 'in',
							'value' => [
								'recaptcha',
								'recaptcha_v3',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'_enable',
			[
				'type' => 'text',
				'classes' => 'elementor-control-type-hidden',
			]
		);

		$repeater->add_control(
			'type',
			[
				'label' => __( 'Type', 'jupiterx-core' ),
				'type' => 'select',
				'options' => Module::get_field_types( 'reset-password' ),
				'default' => 'text',
				'classes' => 'elementor-control-type-hidden',
			]
		);

		$repeater->add_control(
			'label',
			[
				'label' => __( 'Label', 'jupiterx-core' ),
				'type' => 'text',
				'condition' => [
					'type' => 'email',
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
								'recaptcha',
								'recaptcha_v3',
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
						[
							'name' => 'enable',
							'value' => 'true',
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
						[
							'name' => 'enable',
							'value' => 'true',
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
								'recaptcha',
								'recaptcha_v3',
							],
						],
					],
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
				'label' => __( 'Badge', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'inline',
				'options' => [
					'inline' => __( 'Inline', 'jupiterx-core' ),
					'bottomright' => __( 'Bottom Right', 'jupiterx-core' ),
					'bottomleft' => __( 'Bottom Left', 'jupiterx-core' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'value' => 'recaptcha_v3',
						],
						[
							'name' => 'enable',
							'value' => 'true',
						],
					],
				],
			]
		);

		$this->add_control(
			'fields',
			[
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'label' => 'Email',
						'type' => 'email',
						'placeholder' => 'Email',
						'required' => 'true',
						'_enable' => 'true',
					],
					[
						'label' => 'reCaptcha',
						'type' => 'recaptcha',
						'_enable' => 'false',
					],
					[
						'label' => 'reCaptcha V3',
						'type' => 'recaptcha_v3',
						'_enable' => 'false',
					],
				],
				'item_actions' => [
					'add' => false,
					'duplicate' => false,
					'remove' => false,
					'sort' => false,
				],
				'frontend_available' => true,
				'title_field' => '{{{ label }}}',
			]
		);

		$this->end_controls_section();
	}

	private function update_section_submit_button() {
		$this->update_control( 'submit_button_text', [
			'default' => __( 'Reset Password', 'jupiterx-core' ),
		] );
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
				'label' => __( 'Show Label', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on'  => __( 'Yes', 'jupiterx-core' ),
				'label_off' => __( 'No', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'required_mark',
			[
				'label' => __( 'Required Mark', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on'  => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'actions',
			[
				'label' => __( 'Actions', 'jupiterx-core' ),
				'type' => 'text',
				'classes' => 'elementor-control-type-hidden',
				'default' => [ 'reset_password' ],
			]
		);

		$this->end_controls_section();
	}

	private function register_messages_section_controls() {
		$this->start_controls_section(
			'messages',
			[
				'label' => __( 'Messages', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'messages_success',
			[
				'label' => __( 'Success Message', 'jupiterx-core' ),
				'type' => 'textarea',
				'default' => __( 'Please check your email and click the provided link to finish resetting your password.', 'jupiterx-core' ),
			]
		);

		$this->end_controls_section();
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

		if ( empty( $settings['required_mark'] ) ) {
			$this->add_render_attribute(
				'form',
				'class',
				'raven-hide-required-mark'
			);
		}

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
			<?php foreach ( $fields as $field ) {
				if (
					strpos( $field['type'], 'recaptcha' ) !== false &&
					empty( $field['enable'] )
				) {
					continue;
				}

				Module::render_field( $this, $field );
			} ?>
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
}
