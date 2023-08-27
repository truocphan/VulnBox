<?php

namespace JupiterX_Core\Raven\Modules\Content_Switch\Widgets;

use Elementor\Plugin;
use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Controls\Query as Control_Query;

defined( 'ABSPATH' ) || die();

class Content_Switch extends Base_Widget {

	public function get_name() {
		return 'raven-content-switch';
	}

	public function get_title() {
		return esc_html__( 'Content Switch', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-content-switch';
	}

	protected function register_controls() {
		$this->content_controls();
		$this->style_controls();
	}

	private function content_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Switcher', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'default_state',
			[
				'label' => esc_html__( 'Default state', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'primary',
				'options' => [
					'primary' => esc_html__( 'Primary', 'jupiterx-core' ),
					'secondary' => esc_html__( 'Secondary', 'jupiterx-core' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'primary_section_content',
			[
				'label' => esc_html__( 'Primary', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'primary_section_label',
			[
				'label' => esc_html__( 'Label', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'Annual', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'primary_content_type',
			[
				'label' => esc_html__( 'Content Type', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'content',
				'options' => [
					'content' => esc_html__( 'Content', 'jupiterx-core' ),
					'template' => esc_html__( 'Saved Template', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'primary_text_content',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
				'type' => 'wysiwyg',
				'default' => esc_html__( 'Primary Text Content', 'jupiterx-core' ),
				'label_block' => true,
				'dynamic' => [ 'active' => true ],
				'condition' => [
					'primary_content_type' => 'content',
				],
			]
		);

		$this->add_control(
			'primary_content_alignments',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justify', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'condition' => [
					'primary_content_type' => 'content',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-content-switch-monthly-text' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'template_id_primary',
			[
				'label' => esc_html__( 'Choose a template', 'jupiterx-core' ),
				'type' => 'raven_query',
				'options' => [],
				'label_block' => true,
				'multiple' => false,
				'query' => [
					'source' => Control_Query::QUERY_SOURCE_TEMPLATE,
					'template_types' => [
						'section',
					],
				],
				'default' => false,
				'condition' => [
					'primary_content_type' => 'template',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'secondary_section_content',
			[
				'label' => esc_html__( 'Secondary', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'secondary_section_label',
			[
				'label' => esc_html__( 'Label', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'Monthly', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'secondary_content_type',
			[
				'label' => esc_html__( 'Content Type', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'content',
				'options' => [
					'content' => esc_html__( 'Content', 'jupiterx-core' ),
					'template' => esc_html__( 'Saved Template', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'secondary_text_content',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
				'type' => 'wysiwyg',
				'default' => esc_html__( 'Secondary Text Content', 'jupiterx-core' ),
				'label_block' => true,
				'dynamic' => [ 'active' => true ],
				'condition' => [
					'secondary_content_type' => 'content',
				],
			]
		);

		$this->add_control(
			'secondary_content_alignments',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justify', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'condition' => [
					'secondary_content_type' => 'content',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-content-switch-yearly-text' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'template_id_secondary',
			[
				'label' => esc_html__( 'Choose a template', 'jupiterx-core' ),
				'type' => 'raven_query',
				'options' => [],
				'label_block' => true,
				'multiple' => false,
				'query' => [
					'source' => Control_Query::QUERY_SOURCE_TEMPLATE,
					'template_types' => [
						'section',
					],
				],
				'default' => false,
				'condition' => [
					'secondary_content_type' => 'template',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'display_options_section',
			[
				'label' => esc_html__( 'Display Options', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'display_options_animation',
			[
				'label' => esc_html__( 'Animation', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'none' => esc_html__( 'None', 'jupiterx-core' ),
					'opacity' => esc_html__( 'Fade', 'jupiterx-core' ),
					'fade' => esc_html__( 'Slide', 'jupiterx-core' ),
				],
				'default' => 'opacity',
			]
		);

		$this->add_control(
			'display_options_animation_direction',
			[
				'label' => esc_html__( 'Direction', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-arrow-down',
					],
					'left' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-arrow-left',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-arrow-up',
					],
					'right' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-arrow-right',
					],
				],
				'default' => 'top',
				'condition' => [
					'display_options_animation' => 'fade',
				],
			]
		);

		$this->end_controls_section();
	}

	private function style_controls() {
		$this->start_controls_section(
			'switch_styles',
			[
				'label' => esc_html__( 'Switch', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'switch_alignments',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .raven-content-toggle-switcher' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'switch_button_type',
			[
				'label' => esc_html__( 'Switch Style', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'rounded' => esc_html__( 'Rounded', 'jupiterx-core' ),
					'rectangle' => esc_html__( 'Rectangle', 'jupiterx-core' ),
				],
				'default' => 'rounded',
				'selector' => '{{WRAPPER}} .button raven-content-switch-button',
			]
		);

		$this->add_control(
			'switch_button_size',
			[
				'label' => esc_html__( 'Switch Size', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 66,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-content-switch-label' => 'width: {{SIZE}}{{UNIT}};height: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .raven-content-switch-input:checked + .raven-content-switch-input-control:before' => 'transform: translateX(calc(-{{SIZE}}{{UNIT}}/2));width: calc({{SIZE}}{{UNIT}}/2 - 8px);height: calc({{SIZE}}{{UNIT}}/2 - 8px);',
					'{{WRAPPER}} .raven-content-switch-input-control:before' => 'width: calc({{SIZE}}{{UNIT}}/2 - 8px);height: calc({{SIZE}}{{UNIT}}/2 - 8px);',
				],
			]
		);

		$this->start_controls_tabs( 'switch_button_tabs' );

		$this->start_controls_tab(
			'switch_button_primary',
			[
				'label' => esc_html__( 'Primary', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'switch_primary_controller_color',
			[
				'label' => esc_html__( 'Controller Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-content-switch-button.primary .raven-content-switch-input-control:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'switch_primary_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}}  .raven-content-switch-button.primary .raven-content-switch-input-control' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'switch_button_secondary',
			[
				'label' => esc_html__( 'Secondary', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'switch_secondary_controller_color',
			[
				'label' => esc_html__( 'Controller Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-content-switch-button.secondary .raven-content-switch-input-control:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'switch_secondary_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-content-switch-button.secondary .raven-content-switch-input-control' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'switch_label_styles',
			[
				'label' => esc_html__( 'Labels', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->start_controls_tabs( 'switch_label_tabs' );

		$this->start_controls_tab(
			'switch_label_primary_tab',
			[
				'label' => esc_html__( 'Primary', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'switch_label_primary_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-content-switch-primary-label h4' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'switch_label_primary_selected_text_color',
			[
				'label' => esc_html__( 'Selected Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-content-switch-primary-label.selected h4' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'switch_label_primary_typography',
				'scheme' => '2',
				'selector' => '{{WRAPPER}} .raven-content-switch-primary-label h4',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'switch_label_secondary_tab',
			[
				'label' => esc_html__( 'Secondary', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'switch_label_secondary_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-content-switch-secondary-label h4' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'switch_label_secondary_selected_text_color',
			[
				'label' => esc_html__( 'Selected Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-content-switch-secondary-label.selected h4' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'switch_label_secondary_typography',
				'scheme' => '2',
				'selector' => '{{WRAPPER}} .raven-content-switch-secondary-label h4',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'switch_spacing_styles',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'switch_horizontal_spacing',
			[
				'label' => esc_html__( 'Horizontal Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-content-switch-button' => 'margin: 0 {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'switch_vertical_spacing',
			[
				'label' => esc_html__( 'Vertical Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-content-toggle-switcher' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'jupiterx-content-switch-wrapper' );

		$animation = '';

		if ( 'opacity' === $settings['display_options_animation'] ) {
			$animation = 'opacity';
		}

		if ( 'fade' === $settings['display_options_animation'] ) {
			$animation = 'fade-' . $settings['display_options_animation_direction'];
		}

		$default_state = $settings['default_state'];

		$this->add_inline_editing_attributes( 'primary_section_label', 'basic' );

		$this->add_inline_editing_attributes( 'secondary_section_label', 'basic' );

		$this->add_inline_editing_attributes( 'primary_text_content', 'advanced' );

		$this->add_inline_editing_attributes( 'secondary_text_content', 'advanced' );

		$this->add_render_attribute( 'primary_text_content', 'class', 'raven-content-switch-monthly-text' );

		$this->add_render_attribute( 'secondary_text_content', 'class', 'raven-content-switch-yearly-text' );

		?>
		<div class="raven-content-switch-container" data-default_state="<?php echo $default_state; ?>">
			<?php $this->get_switcher_html( $settings ); ?>
			<div class="raven-content-switch-list <?php echo esc_attr( $animation ); ?>">
				<ul class="raven-content-switch-two-content">
					<li data-type="raven-content-switch-monthly"
						class="raven-content-switch-monthly raven-content-switch-is-<?php echo 'primary' === $default_state ? 'visible' : 'hidden'; ?>">
						<?php if ( 'content' === $settings['primary_content_type'] ) : ?>

							<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'primary_text_content' ) ); ?>>
								<?php echo $this->parse_text_editor( $settings['primary_text_content'] ); ?>
							</div>

						<?php elseif ( 'template' === $settings['primary_content_type'] ) :
							$primary_template = $settings['template_id_primary']; ?>

							<div class="raven-content-switch-first-content-item-wrapper">
								<?php $this->get_template_content( $primary_template ); ?>
							</div>

						<?php endif; ?>
					</li>

					<li data-type="raven-content-switch-yearly"
						class="raven-content-switch-yearly raven-content-switch-is-<?php echo 'secondary' === $default_state ? 'visible' : 'hidden'; ?>">
						<?php if ( 'content' === $settings['secondary_content_type'] ) : ?>

							<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'secondary_text_content' ) ); ?>>
								<?php echo $this->parse_text_editor( $settings['secondary_text_content'] ); ?>
							</div>

						<?php elseif ( 'template' === $settings['secondary_content_type'] ) :
							$second_template = $settings['template_id_secondary']; ?>

							<div class="raven-content-switch-second-content-item-wrapper">
								<?php $this->get_template_content( $second_template ); ?>
							</div>

						<?php endif; ?>
					</li>
				</ul>
			</div>
		</div>
		<?php
	}

	private function get_switcher_html( $settings ) {
		$default_state        = $settings['default_state'];
		$primary_is_default   = 'primary' === $default_state ? 'selected' : '';
		$secondary_is_default = 'secondary' === $default_state ? 'selected' : '';
		$input_active_normal  = 'primary' === $default_state ? 'raven-content-switch-input-active' : 'raven-content-switch-input-normal';
		$switch_button_type   = $settings['switch_button_type'];

		$this->add_render_attribute( 'raven_content_switch_input', 'class', 'raven-content-switch-input elementor-clickable' );
		$this->add_render_attribute( 'raven_content_switch_input', 'class', $input_active_normal );
		$this->add_render_attribute( 'raven_content_switch_button', 'class', $switch_button_type );
		$this->add_render_attribute( 'raven_content_switch_button', 'class', 'raven-content-switch-button' );
		$this->add_render_attribute( 'raven_content_switch_button', 'class', $default_state );
		?>
		<div class="raven-content-toggle-switcher">
			<div class="raven-content-switch-primary-label <?php echo $primary_is_default; ?>">
				<h4>
					<?php echo esc_html( $settings['primary_section_label'] ); ?>
				</h4>
			</div>
			<div <?php echo $this->get_render_attribute_string( 'raven_content_switch_button' ); ?>>
				<label class="raven-content-switch-label">
					<input type="checkbox" <?php echo $this->get_render_attribute_string( 'raven_content_switch_input' ); ?>>
					<span class="raven-content-switch-input-control elementor-clickable"></span>
				</label>
			</div>
			<div class="raven-content-switch-secondary-label <?php echo $secondary_is_default; ?>">
				<h4>
					<?php echo esc_html( $settings['secondary_section_label'] ); ?>
				</h4>
			</div>
		</div>
		<?php
	}

	/**
	 * Get template content from frontend of elementor.
	 *
	 * @param $id
	 *
	 * @return void
	 */
	public function get_template_content( $id ) {
		$frontend = Plugin::instance()->frontend;

		echo $frontend->get_builder_content_for_display( (int) $id, true );
	}
}
