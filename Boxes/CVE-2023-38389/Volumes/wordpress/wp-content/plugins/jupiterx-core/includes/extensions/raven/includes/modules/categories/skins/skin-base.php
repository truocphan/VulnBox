<?php
namespace JupiterX_Core\Raven\Modules\Categories\Skins;

use JupiterX_Core\Raven\Utils;
use JupiterX_Core\Raven\Modules\Categories\Module;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || die();

abstract class Skin_Base extends Elementor_Skin_Base {

	private $post_type;

	private $terms;

	protected $term;

	protected function get_terms() {
		if ( 'product' === $this->post_type && ! class_exists( 'WooCommerce' ) ) {
			$this->terms = [];
			return $this;
		}

		$taxonomy = Module::get_taxonomy( $this->post_type );

		$args = [
			'taxonomy' => $taxonomy,
		];

		if ( 'yes' !== $this->get_instance_value( 'show_sub_categories' ) ) {
			$args['parent'] = 0;
		}

		$args['include'] = $this->parent->get_settings( 'specific_categories' );

		$args['exclude'] = $this->parent->get_settings( 'exclude' );

		$this->terms = get_terms( $args );

		return $this;
	}

	protected function _register_controls_actions() {
		add_action( 'elementor/element/raven-categories/section_content/after_section_end', [ $this, 'register_controls' ] );
	}

	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_settings_controls();
		$this->register_container_controls();
		$this->register_image_controls();
		$this->register_overlay_controls();
		$this->register_title_controls();
		$this->register_description_controls();
		$this->register_button_controls();
	}

	protected function register_settings_controls() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => __( 'Layout', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'grid',
				'options' => [
					'grid' => __( 'Grid', 'jupiterx-core' ),
					'masonry' => __( 'Masonry', 'jupiterx-core' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
					'1' => __( '1', 'jupiterx-core' ),
					'2' => __( '2', 'jupiterx-core' ),
					'3' => __( '3', 'jupiterx-core' ),
					'4' => __( '4', 'jupiterx-core' ),
					'5' => __( '5', 'jupiterx-core' ),
					'6' => __( '6', 'jupiterx-core' ),
				],
				'frontend_available' => true,
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .raven-categories-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
			]
		);

		$this->add_group_control(
			'image-size',
			[
				'name' => 'image',
				'default' => 'large',
			]
		);

		$this->add_control(
			'hover_effect',
			[
				'label' => __( 'Hover Effect', 'jupiterx-core' ),
				'type' => 'raven_hover_effect',
			]
		);

		$this->add_control(
			'show_sub_categories',
			[
				'label' => __( 'Sub Categories', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_image',
			[
				'label' => __( 'Featured Image', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => __( 'Title', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_description',
			[
				'label' => __( 'Description', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_button',
			[
				'label' => __( 'Button', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_container_controls() {
		$this->start_controls_section(
			'section_container',
			[
				'label' => __( 'Container', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'container_height',
			[
				'label' => __( 'Height', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-grid .raven-categories-item' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->parent->update_control(
			$this->get_control_id( 'container_height' ),
			[
				'condition' => [
					$this->get_control_id( 'layout' ) => 'grid',
					'_skin' => 'inner_content',
				],
			]
		);

		$this->add_responsive_control(
			'container_column_spacing',
			[
				'label' => __( 'Column Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .raven-categories-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-masonry' => 'margin-left: calc( -{{SIZE}}{{UNIT}} / 2 ); margin-right: calc( -{{SIZE}}{{UNIT}} / 2 );',
					'{{WRAPPER}} .raven-masonry-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} / 2 ); padding-right: calc( {{SIZE}}{{UNIT}} / 2 );',
				],
			]
		);

		$this->add_responsive_control(
			'container_row_spacing',
			[
				'label' => __( 'Row Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .raven-categories-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-masonry .raven-categories-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'container_align',
			[
				'label'  => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'center',
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
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-item' => 'text-align: {{VALUE}};',
				],
				'label_block' => false,
			]
		);

		$this->add_control(
			'container_vertical_align',
			[
				'label'  => __( 'Vertical Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'center',
				'options' => [
					'flex-start' => [
						'title' => __( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Middle', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'flex-end' => [
						'title' => __( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-content' => 'align-self: {{VALUE}};',
				],
				'label_block' => false,
			]
		);

		$this->parent->update_control(
			$this->get_control_id( 'container_vertical_align' ),
			[
				'condition' => [
					$this->get_control_id( 'layout' ) => 'grid',
					'_skin' => 'inner_content',
				],
			]
		);

		$this->start_controls_tabs( 'container_tabs' );

		$this->start_controls_tab(
			'container_tab_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'container_tab_background_normal',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Background Color Type', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-categories-item',
			]
		);

		$this->add_control(
			'container_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'container_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-categories-item' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'container_border!' ) => '',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'container',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-categories-item',
			]
		);

		$this->add_control(
			'container_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'container_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-categories-item',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'container_tab_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'container_tab_background_hover',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Background Color Type', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-categories-item:hover',
			]
		);

		$this->add_control(
			'container_border_heading_hover',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'container_border_color_hover',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-categories-item:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'container_border!' ) => '',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'container_hover',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-categories-item:hover',
			]
		);

		$this->add_control(
			'container_border_radius_hover',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'container_box_shadow_hover',
				'exclude' => [
					'box_shadow_position',
				],
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-categories-item:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_image_controls() {}

	protected function register_overlay_controls() {}

	protected function register_title_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'title_typography',
				'scheme' => '1',
				'selector' => '{{WRAPPER}} .raven-categories-title, {{WRAPPER}} .raven-categories-title a',
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => '',
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
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'title_tabs' );

		$this->start_controls_tab(
			'title_tab_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'title_tab_color_normal',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-categories-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_tab_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'title_tab_color_hover',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-categories-item:hover .raven-categories-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_description_controls() {
		$this->start_controls_section(
			'section_description',
			[
				'label' => __( 'Description', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'description_length',
			[
				'label' => __( 'Length', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 10,
						'step' => 1,
						'max' => 1000,
					],
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'description_typography',
				'scheme' => '1',
				'selector' => '{{WRAPPER}} .raven-categories-description',
			]
		);

		$this->add_responsive_control(
			'description_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'description_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => '',
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
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-description' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'description_tabs' );

		$this->start_controls_tab(
			'description_tab_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'description_tab_color_normal',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-categories-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'description_tab_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'description_tab_color_hover',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-categories-item:hover .raven-categories-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_button_controls() {
		$this->start_controls_section(
			'section_button',
			[
				'label' => __( 'Button', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'  => __( 'Text', 'jupiterx-core' ),
				'type' => 'text',
				'default' => __( 'View', 'jupiterx-core' ),
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label' => __( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_height',
			[
				'label' => __( 'Height', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-button' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-view' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_align',
			[
				'label'  => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'center',
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
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-view' => 'text-align: {{VALUE}};',
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

		$this->add_control(
			'button_tab_color_normal',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-categories-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'button_tab_typography_normal',
				'scheme' => '1',
				'selector' => '{{WRAPPER}} .raven-categories-button',
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'button_tab_background_normal',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Background Color Type', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-categories-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_tab_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'button_tab_color_hover',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-categories-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'button_tab_typography_hover',
				'scheme' => '1',
				'selector' => '{{WRAPPER}} .raven-categories-button:hover',
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'button_tab_background_hover',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Background Color Type', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-categories-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-categories-button' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'button_border_border!' ) => '',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'button_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-categories-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .raven-categories-button',
			]
		);

		$this->end_controls_section();
	}

	protected function render_image() {
		if ( ! $this->get_instance_value( 'show_image' ) ) {
			return;
		}

		$image_field = get_term_meta( $this->term->term_id, 'jupiterx_taxonomy_thumbnail_id', true );

		if ( ! empty( $image_field ) ) {
			$image_id = intval( $image_field );
		}

		if ( 'product' === $this->post_type ) {
			$image_id = get_term_meta( $this->term->term_id, 'thumbnail_id', true );
		}

		if ( empty( $image_id ) ) {
			return;
		}

		$settings = [
			'image' => [
				'id' => $image_id,
			],
			'image_size' => $this->get_instance_value( 'image_size' ),
		];

		$this->render_skin_image( $settings );
	}

	protected function render_title() {
		if ( ! $this->get_instance_value( 'show_title' ) ) {
			return;
		}

		printf(
			'<h3 class="raven-categories-title"><a href="%s">%s</a></h3>',
			get_term_link( $this->term->term_id ),
			$this->term->name
		);
	}

	protected function render_description() {
		if ( ! $this->get_instance_value( 'show_description' ) || empty( $this->term->description ) ) {
			return;
		}

		$description = $this->term->description;
		$length      = $this->get_instance_value( 'description_length' );

		if ( ! empty( $length['size'] ) ) {
			$description = mb_strimwidth( $description, 0, $length['size'], '...' );
		}

		printf( '<p class="raven-categories-description">%s</p>', $description );
	}

	protected function render_button() {
		if ( ! $this->get_instance_value( 'show_button' ) ) {
			return;
		}

		printf(
			'<div class="raven-categories-view"><a class="raven-categories-button" href="%s">%s</a></div>',
			get_term_link( $this->term->term_id ),
			$this->get_instance_value( 'button_text' )
		);
	}

	protected function render_item() {
		$classes      = [ 'raven-categories-item' ];
		$hover_effect = $this->get_instance_value( 'hover_effect' );

		if ( ! empty( $hover_effect ) ) {
			$classes[] = 'elementor-animation-' . $hover_effect;
		}
		?>
		<article <?php post_class( $classes ); ?>>
			<?php $this->render_image(); ?>
			<div class="raven-categories-content">
			<?php
				$this->render_title();
				$this->render_description();
				$this->render_button();
			?>
			</div>
		</article>
		<?php
	}

	protected function render_order_item() {
		$sort_number    = 0;
		$sortable_items = [];

		foreach ( $this->terms as $term ) {
			$this->term   = $term;
			$order_number = $sort_number;

			if ( 'portfolio_category' === $this->term->taxonomy || 'category' === $this->term->taxonomy ) {

				if (
					class_exists( 'acf' ) &&
					! empty( get_field( 'jupiterx_taxonomy_order_number', 'category_' . $this->term->term_id ) )
				) {
					$order_number = get_field( 'jupiterx_taxonomy_order_number', 'category_' . $this->term->term_id );
				}

				if ( array_key_exists( $order_number, $sortable_items ) ) {
					$order_number = $order_number + $sort_number;
				}
			}

			$sortable_items[ $order_number ] = $this->term;
			++$sort_number;

		}

		ksort( $sortable_items );

		return $sortable_items;
	}

	public function render() {
		$settings        = $this->parent->get_settings_for_display();
		$layout          = $this->get_instance_value( 'layout' );
		$this->post_type = $settings['source'];

		$this->get_terms();

		$this->parent->add_render_attribute(
			'wrapper',
			'class',
			[
				'raven-categories-' . $layout,
				'raven-categories-skin-' . $settings['_skin'],
			]
		);

		if ( 'masonry' === $layout ) {
			$masonry_columns = Utils::get_responsive_class(
				'raven-masonry%s-',
				$this->get_control_id( 'columns' ),
				$this->parent->get_settings_for_display()
			);

			$this->parent->add_render_attribute(
				'wrapper',
				'class',
				$masonry_columns
			);

			$this->parent->add_render_attribute(
				'wrapper', 'class', 'raven-masonry'
			);
		}
		?>
		<div <?php echo $this->parent->get_render_attribute_string( 'wrapper' ); ?>>
			<?php
			$this->terms = $this->render_order_item();

			foreach ( $this->terms as $term ) {
				$this->term = $term;
				if ( 'masonry' === $layout ) {
					echo '<div class="raven-masonry-item">';
				}

				$this->render_item();

				if ( 'masonry' === $layout ) {
					echo '</div>';
				}
			}
			?>
		</div>
		<?php
	}
}
