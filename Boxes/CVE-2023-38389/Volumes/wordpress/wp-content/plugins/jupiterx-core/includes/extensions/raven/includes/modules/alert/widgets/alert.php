<?php
namespace JupiterX_Core\Raven\Modules\Alert\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

class Alert extends Base_Widget {

	public function get_name() {
		return 'raven-alert';
	}

	public function get_title() {
		return __( 'Alert', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-alert';
	}

	protected function register_controls() {
		$this->register_section_content();
		$this->register_section_settings();
		$this->register_section_alert();
		$this->register_section_title();
		$this->register_section_description();
		$this->register_section_icon();
	}

	private function register_section_content() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'alert_type',
			[
				'label' => __( 'Type', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'info',
				'options' => [
					'info' => __( 'Info', 'jupiterx-core' ),
					'success' => __( 'Success', 'jupiterx-core' ),
					'warning' => __( 'Warning', 'jupiterx-core' ),
					'danger' => __( 'Danger', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title & Description', 'jupiterx-core' ),
				'type' => 'text',
				'placeholder' => __( 'Enter your title', 'jupiterx-core' ),
				'default' => __( 'This is an Alert', 'jupiterx-core' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'description',
			[
				'label' => __( 'Content', 'jupiterx-core' ),
				'type' => 'wysiwyg',
				'placeholder' => __( 'Enter your description', 'jupiterx-core' ),
				'default' => __( 'I am a description. Click the edit button to change this text.', 'jupiterx-core' ),
				'separator' => 'none',
				'show_label' => false,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'icon_new',
			[
				'label' => __( 'Choose Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-info-circle',
					'library' => 'fa-solid',
				],
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
			'show_dismiss',
			[
				'label' => __( 'Dismiss Button', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'default' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_alert() {
		$this->start_controls_section(
			'section_alert',
			[
				'label' => __( 'Alert', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Background Color Type', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-alert',
			]
		);

		/**
		 * Use HIDDEN control to hack style.
		 *
		 * If background type is gradient, add `background-origin` property to fix the issue of border (dotted, dashed and etc).
		 *
		 * Proper code to use for Group_Control_Background. Unfortunately random bug encountered when using this code:
		 *
		 * 1. It can't read {{SELECTOR}} - though {{WRAPPER}} > .raven-alert would work
		 * 2. Javascript object style to print value is not working
		 *
		 * 'fields_options' => [
		 *  'gradient_position' => [
		 *   'selectors' => [
		 *    '{{SELECTOR}}' => 'background-origin: border-box; background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}});',
		 *   ],
		 *  ],
		 * ],
		 *
		 * This won't have any side effect in the future since we just added this to add new style property but with condition.
		 */
		$this->add_control(
			'background_origin',
			[
				'label' => __( 'View', 'jupiterx-core' ),
				'type' => 'hidden',
				'default' => 'border-box',
				'selectors' => [
					'{{WRAPPER}} .raven-alert' => 'background-origin: {{VALUE}};',
				],
				'condition' => [
					'background_background' => 'gradient',
				],
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => __( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 30,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-alert' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-alert' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => is_rtl() ? __( 'Right', 'jupiterx-core' ) : __( 'Left', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-text-align-right' : 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => is_rtl() ? __( 'Left', 'jupiterx-core' ) : __( 'Right', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-text-align-left' : 'eicon-text-align-right',
					],
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'right' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-flex' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-alert' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'border_border!' => '',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-alert',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-alert' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_title() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-alert-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'title_typography',
				'scheme' => '1',
				'selector' => '{{WRAPPER}} .raven-alert-title',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_description() {
		$this->start_controls_section(
			'section_description',
			[
				'label' => __( 'Description', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-alert-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'description_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .raven-alert-description',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_icon() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Icon', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'icon_new!' => '',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-alert-icon i, {{WRAPPER}} .raven-alert-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
				'condition' => [
					'icon_new[value]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'default' => [ '30px' ],
				'selectors' => [
					'{{WRAPPER}} .raven-alert-icon i' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-alert-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_new[value]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-alert-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'icon_new!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['title'] ) ) {
			return;
		}

		$this->add_render_attribute(
			'wrapper',
			'class',
			'raven-widget-wrapper raven-flex'
		);

		$this->add_render_attribute(
			'alert',
			'class',
			[
				'raven-alert',
				'raven-alert-' . $settings['alert_type'],
				'raven-flex raven-flex-top raven-flex-none',
			]
		);
		$this->add_render_attribute( 'alert', 'role', 'alert' );
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'alert' ); ?>>
				<?php
				$this->render_icon();
				$this->render_text();

				if ( 'yes' === $settings['show_dismiss'] ) :
					?>
					<button class="raven-alert-dismiss" type="button">
						<span aria-hidden="true">&times;</span>
						<span class="elementor-screen-only"><?php esc_html_e( 'Dismiss Alert', 'jupiterx-core' ); ?></span>
					</button>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	protected function render_icon() {
		$settings          = $this->get_settings();
		$migration_allowed = Elementor::$instance->icons_manager->is_migration_allowed();
		$migrated          = isset( $settings['__fa4_migrated']['icon_new'] );
		$is_new            = empty( $settings['icon'] ) && $migration_allowed;

		if ( empty( $settings['icon'] ) && empty( $settings['icon_new']['value'] ) ) {
			return;
		}
		?>
		<div class="raven-alert-icon">
		<?php
		if ( $is_new || $migrated ) :
			Elementor::$instance->icons_manager->render_icon( $settings['icon_new'], [ 'aria-hidden' => 'true' ] );
		else :
			?>
			<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
		<?php endif; ?>
		</div>
		<?php
	}

	protected function render_text() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'title', 'class', 'raven-alert-title' );
		$this->add_render_attribute( 'description', 'class', 'raven-alert-description' );

		$this->add_inline_editing_attributes( 'title', 'none' );
		$this->add_inline_editing_attributes( 'description', 'advanced' );
		?>
		<div class="raven-alert-content">
			<div <?php echo $this->get_render_attribute_string( 'title' ); ?>><?php echo $settings['title']; ?></div>
			<?php if ( ! empty( $settings['description'] ) ) { ?>
				<div <?php echo $this->get_render_attribute_string( 'description' ); ?>><?php echo $settings['description']; ?></div>
			<?php } ?>
		</div>
		<?php
	}

	protected function content_template() {
		?>
		<# if ( settings.title ) {
			var iconHTML = elementor.helpers.renderIcon( view, settings.icon_new, { 'aria-hidden': true }, 'i' , 'object' ),
				migrated = elementor.helpers.isIconMigrated( settings, 'icon_new' );
			view.addRenderAttribute(
				'wrapper',
				'class',
				'raven-widget-wrapper raven-flex'
			);

			view.addRenderAttribute( 'title', 'class', 'raven-alert-title' );
			view.addRenderAttribute( 'description', 'class', 'raven-alert-description' );

			view.addInlineEditingAttributes( 'title', 'none' );
			view.addInlineEditingAttributes( 'description', 'advanced' );
			#>
			<div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
				<div class="raven-alert raven-alert-{{ settings.alert_type }} raven-flex raven-flex-top raven-flex-none" role="alert">
					<# if ( settings.icon || settings.icon_new ) { #>
						<div class="raven-alert-icon">
						<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
							{{{ iconHTML.value }}}
						<# } else { #>
							<i class="{{ settings.icon }}" aria-hidden="true"></i>
						<# } #>
						</div>
					<# } #>
					<div class="raven-alert-content">
						<div {{{ view.getRenderAttributeString( 'title' ) }}}>{{{ settings.title }}}</div>
						<# if ( settings.description ) { #>
							<div {{{ view.getRenderAttributeString( 'description' ) }}}>{{{ settings.description }}}</div>
						<# } #>
					</div>
					<# if ( 'yes' === settings.show_dismiss ) { #>
						<button class="raven-alert-dismiss" type="button">
							<span aria-hidden="true">&times;</span>
							<span class="elementor-screen-only"><?php esc_html_e( 'Dismiss alert', 'jupiterx-core' ); ?></span>
						</button>
					<# } #>
				</div>
			</div>
		<# } #>
		<?php
	}
}
