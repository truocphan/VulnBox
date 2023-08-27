<?php
namespace JupiterX_Core\Raven\Modules\Table_Of_Contents\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Plugin;

class Table_Of_Contents extends Base_Widget {

	public function get_name() {
		return 'raven-table-of-contents';
	}

	public function get_title() {
		return esc_html__( 'Table of Contents', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-table-of-contents';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'contents',
			[
				'label' => esc_html__( 'Table of Contents', 'jupiterx-core' ),
			]
		);

		$this->table_of_contents();

		$this->end_controls_section();

		$this->start_controls_section(
			'additional_contents',
			[
				'label' => esc_html__( 'Additional Options', 'jupiterx-core' ),
			]
		);

		$this->additional_options();

		$this->end_controls_section();

		$this->start_controls_section(
			'box_styles',
			[
				'label' => esc_html__( 'Box', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->box_style_controls();

		$this->end_controls_section();

		$this->start_controls_section(
			'header_styles',
			[
				'label' => esc_html__( 'Header', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->header_style_controls();

		$this->end_controls_section();

		$this->start_controls_section(
			'list_styles',
			[
				'label' => esc_html__( 'List', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->list_style_controls();

		$this->end_controls_section();
	}

	private function table_of_contents() {
		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'default' => esc_html__( 'Table of Contents', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'header_html_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
				],
				'default' => 'h4',
			]
		);

		$this->start_controls_tabs(
			'include_exclude',
			[
				'separator' => 'before',
			]
		);

		$this->start_controls_tab(
			'include',
			[
				'label' => esc_html__( 'Include', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'headings_by_tags',
			[
				'label' => esc_html__( 'Anchors By Tags', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [ 'h2', 'h3', 'h4', 'h5', 'h6' ],
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'label_block' => true,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'container',
			[
				'label' => esc_html__( 'Container', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => esc_html__( 'This control confines the Table of Contents to heading elements under a specific container', 'jupiterx-core' ),
				'frontend_available' => true,
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'exclude',
			[
				'label' => esc_html__( 'Exclude', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'exclude_headings_by_selector',
			[
				'label' => esc_html__( 'Anchors By Selector', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'description' => esc_html__( 'CSS selectors, in a comma-separated list', 'jupiterx-core' ),
				'default' => [],
				'label_block' => true,
				'frontend_available' => true,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'marker_view',
			[
				'label' => esc_html__( 'Marker View', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'numbers',
				'options' => [
					'numbers' => esc_html__( 'Numbers', 'jupiterx-core' ),
					'bullets' => esc_html__( 'Bullets', 'jupiterx-core' ),
				],
				'separator' => 'before',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'list_icon',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-circle',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'circle',
						'dot-circle',
						'square-full',
					],
					'fa-regular' => [
						'circle',
						'dot-circle',
						'square-full',
					],
				],
				'condition' => [
					'marker_view' => 'bullets',
				],
				'skin' => 'inline',
				'label_block' => false,
				'exclude_inline_options' => [ 'svg' ],
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);
	}

	private function additional_options() {
		$this->add_control(
			'word_wrap',
			[
				'label' => esc_html__( 'Word Wrap', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'minimize_box',
			[
				'label' => esc_html__( 'Minimize Box', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'expand_icon',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-down',
					'library' => 'fa-solid',
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
				'condition' => [
					'minimize_box' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'collapse_icon',
			[
				'label' => esc_html__( 'Minimize Icon', 'jupiterx-core' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-up',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'chevron-up',
						'angle-up',
						'angle-double-up',
						'caret-up',
						'caret-square-up',
					],
					'fa-regular' => [
						'caret-square-up',
					],
				],
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'minimize_box' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();

		$minimized_on_options = [];

		foreach ( $breakpoints as $breakpoint_key => $breakpoint ) {
			// This feature is meant for mobile screens.
			if ( 'widescreen' === $breakpoint_key ) {
				continue;
			}

			$minimized_on_options[ $breakpoint_key ] = sprintf(
				/* translators: 1: `<` character, 2: Breakpoint value. */
				esc_html__( '%1$s (%2$s %3$dpx)', 'jupiterx-core' ),
				$breakpoint->get_label(),
				'<',
				$breakpoint->get_value()
			);
		}

		$minimized_on_options['desktop'] = esc_html__( 'Desktop (or smaller)', 'jupiterx-core' );

		$this->add_control(
			'minimized_on',
			[
				'label' => esc_html__( 'Minimized On', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'tablet',
				'options' => $minimized_on_options,
				'condition' => [
					'minimize_box!' => '',
				],
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'hierarchical_view',
			[
				'label' => esc_html__( 'Hierarchical View', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'collapse_subitems',
			[
				'label' => esc_html__( 'Collapse Subitems', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'The "Collapse" option should only be used if the Table of Contents is made sticky', 'jupiterx-core' ),
				'condition' => [
					'hierarchical_view' => 'yes',
				],
				'frontend_available' => true,
			]
		);
	}

	private function box_style_controls() {
		$this->add_control(
			'background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-widget' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-widget' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-table-of-contents-header' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'loader_color',
			[
				'label' => esc_html__( 'Loader Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-loader' => 'color: {{VALUE}}; fill: {{VALUE}};',
					'{{WRAPPER}} .jupiterx-table-of-contents-loader > path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'border_width',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-widget' => 'border-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-widget' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'min_height',
			[
				'label' => esc_html__( 'Min Height', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-widget' => 'min-height: {{SIZE}}{{UNIT}}',
				],
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .jupiterx-table-of-contents-widget',
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-widget > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	private function header_style_controls() {
		$this->add_control(
			'header_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-header' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'header_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-native-exclude' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'header_typography',
				'selector' => '{{WRAPPER}} .jupiterx-table-of-contents-native-exclude',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'toggle_button_color',
			[
				'label' => esc_html__( 'Icon Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'minimize_box' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--toggle-button-color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-table-of-contents-expand-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-table-of-contents-collapse-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-table-of-contents-expand-icon > path' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-table-of-contents-collapse-icon > path' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'header_separator_width',
			[
				'label' => esc_html__( 'Separator Width', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-header' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'header_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-widget .jupiterx-table-of-contents-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	private function list_style_controls() {
		$this->add_responsive_control(
			'list_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-widget .jupiterx-table-of-contents-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'max_height',
			[
				'label' => esc_html__( 'Max Height', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-body' => 'max-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'list_typography',
				'selector' => '{{WRAPPER}} .jupiterx-table-of-contents-body .jupiterx-table-of-contents-item-wrapper > div',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'list_indent',
			[
				'label' => esc_html__( 'Indent', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'unit' => 'em',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--list-indent-value: {{SIZE}}{{UNIT}}',
				],
				'frontend_available' => true,
			]
		);

		$this->start_controls_tabs( 'item_text_style' );

		$this->start_controls_tab( 'normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'item_text_color_normal',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-item-link' => 'color: {{VALUE}} !important',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_control(
			'item_text_underline_normal',
			[
				'label' => esc_html__( 'Underline', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'underline',
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-item-link' => 'text-decoration: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'item_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-item-link:hover' => 'color: {{VALUE}} !important',
				],
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
			]
		);

		$this->add_control(
			'item_text_underline_hover',
			[
				'label' => esc_html__( 'Underline', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'underline',
				'default' => 'underline',
				'selectors' => [
					'{{WRAPPER}}' => '--hover-text-decoration: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'active',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'item_text_color_active',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-item-link-active' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'item_text_underline_active',
			[
				'label' => esc_html__( 'Underline', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'underline',
				'selectors' => [
					'{{WRAPPER}}' => '--active-text-decoration: underline',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_marker',
			[
				'label' => esc_html__( 'Marker', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'marker_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-item-wrapper span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-table-of-contents-item-wrapper span > svg > path' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-table-of-contents-item-wrapper > div:before' => 'color: {{VALUE}} !important',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_responsive_control(
			'marker_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-table-of-contents-item-wrapper span' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .jupiterx-table-of-contents-item-wrapper span > svg' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .jupiterx-table-of-contents-item-wrapper:not(.wrapper-with-no-number) > div::before' => 'font-size: {{SIZE}}{{UNIT}} !important',
				],
			]
		);
	}

	private function render_icon_customized( $attributes, $icon_data ) {
		$icon = Icons_Manager::try_get_icon_html( $icon_data, $attributes );

		if ( is_array( $icon_data['value'] ) ) {
			$icon = str_replace( '<svg', '<svg class="' . $attributes['class'] . '"', Icons_Manager::try_get_icon_html( $icon_data ) );
		}

		echo $icon;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$maximize = false;

		$classes = [ 'jupiterx-table-of-contents-body' ];

		if ( isset( $settings['hierarchical_view'] ) && 'yes' === $settings['hierarchical_view'] ) {
			$classes[] = 'jupiterx-table-of-contents-list-ordered';
		} else {
			$classes[] = 'jupiterx-table-of-contents-list-default';
		}

		$this->add_render_attribute( 'body', 'class', $classes );

		$header_classes = [ 'jupiterx-table-of-contents-header' ];

		if ( isset( $settings['minimize_box'] ) && 'yes' === $settings['minimize_box'] ) {
			$header_classes[] = 'jupiterx-toc-body-maximized';
			$maximize         = true;
		}

		$this->add_render_attribute( 'header', 'class', $header_classes );
		?>

		<div class="jupiterx-table-of-contents-widget">
			<div <?php echo $this->get_render_attribute_string( 'header' ); ?> >
				<<?php echo esc_html( $settings['header_html_tag'] ); ?> class="jupiterx-table-of-contents-native-exclude">
					<?php echo esc_html( $settings['title'] ); ?>
				</<?php echo esc_html( $settings['header_html_tag'] ); ?>>
			</div>
			<div <?php echo $this->get_render_attribute_string( 'body' ); ?>></div>
		</div>
		<div class="jupiterx-table-of-contents-hidden-section" >
			<div class="jupiterx-table-of-contents-loader-icon">
				<?php
					Icons_Manager::render_icon(
						[
							'library' => 'eicons',
							'value' => 'eicon-loading',
						],
						[
							'class' => [
								'jupiterx-table-of-contents-loader',
								'eicon-animation-spin',
							],
							'aria-hidden' => 'true',
						]
					);
				?>
			</div>
			<div class="jupiterx-table-of-contents-list-icon-wrapper">
				<?php
					if ( ! empty( $settings['list_icon'] ) ) {
						$attributes = [
							'aria-hidden' => 'true',
							'class'       => 'jupiterx-table-of-contents-list-icon',
						];

						$this->render_icon_customized( $attributes, $settings['list_icon'] );
					}
				?>
			</div>
			<div class="jupiterx-table-of-contents-list-icon-expand">
				<?php
					if ( ! empty( $settings['expand_icon'] ) ) {
						$attributes = [
							'aria-hidden' => 'true',
							'class'       => 'jupiterx-table-of-contents-expand-icon',
						];

						$this->render_icon_customized( $attributes, $settings['expand_icon'] );
					}
				?>
			</div>
			<div class="jupiterx-table-of-contents-list-icon-collapse">
				<?php
					if ( ! empty( $settings['collapse_icon'] ) ) {
						$attributes = [
							'aria-hidden' => 'true',
							'class'       => 'jupiterx-table-of-contents-collapse-icon',
						];

						$this->render_icon_customized( $attributes, $settings['collapse_icon'] );
					}
				?>
			</div>
		</div>
		<?php
	}
}
