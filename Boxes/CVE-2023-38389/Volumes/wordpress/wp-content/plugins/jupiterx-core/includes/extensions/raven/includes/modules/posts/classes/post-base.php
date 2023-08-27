<?php
/**
 * Add Post Base.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Modules\Posts\Classes;

use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

/**
 * Post Base.
 *
 * An abstract base class to handle controls and render for the post type post.
 *
 * @since 1.0.0
 * @abstract
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
abstract class Post_Base extends Action_Base {

	/**
	 * Register image size control.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_image_size_control() {
		$this->skin->add_group_control(
			'image-size',
			[
				'name' => 'post_image_size',
				'default' => 'large',
			]
		);
	}

	/**
	 * Register settings controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_settings_controls() {

		$conditions = [
			'relation' => 'and',
			'terms' => [
				[
					'name' => '_skin',
					'operator' => '===',
					'value' => $this->skin->get_id(),
				],
				[
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'query_post_type',
							'operator' => '===',
							'value' => 'post',
						],
						[
							'name' => $this->skin->get_control_id( 'show_overlay' ),
							'operator' => '!==',
							'value' => 'yes',
						],
						[
							'name' => '_skin',
							'operator' => '===',
							'value' => 'classic',
						],
					],
				],
			],
		];

		$this->skin->add_control(
			'post_title_tag',
			[
				'label' => __( 'Title HTML Tag', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'h3',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
			]
		);

		$this->skin->add_control(
			'post_hover_effect',
			[
				'label' => __( 'Hover Effect', 'jupiterx-core' ),
				'type' => 'raven_hover_effect',
			]
		);

		$this->skin->add_control(
			'show_overlay',
			[
				'label' => __( 'Show Content on Hover', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => __( 'Yes', 'jupiterx-core' ),
				'label_off' => __( 'No', 'jupiterx-core' ),
				'condition' => [
					'query_post_type' => 'portfolio',
				],
			]
		);

		$this->skin->add_control(
			'link_feature_image_to_permalink',
			[
				'label' => __( 'Link feature image to post', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => '',
				'label_on' => __( 'Yes', 'jupiterx-core' ),
				'label_off' => __( 'No', 'jupiterx-core' ),
				'condition' => [
					$this->skin->get_control_id( 'show_overlay' ) => 'yes',
					'query_post_type' => 'portfolio',
				],
			]
		);

		$this->skin->add_control(
			'show_quick_view',
			[
				'label' => __( 'Quick View', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'condition' => [
					'query_post_type' => 'portfolio',
					$this->skin->get_control_id( 'show_overlay' ) => 'yes',
					$this->skin->get_control_id( 'link_feature_image_to_permalink' ) => '',
				],
			]
		);

		$this->skin->add_control(
			'overlay_quick_view_icon_new',
			[
				'label' => __( 'Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'fa4compatibility' => 'overlay_quick_view_icon',
				'default' => [
					'value' => 'fas fa-search-plus',
					'library' => 'fa-solid',
				],
				'condition' => [
					'query_post_type' => 'portfolio',
					$this->skin->get_control_id( 'show_overlay' ) => 'yes',
					$this->skin->get_control_id( 'show_quick_view' ) => 'yes',
					$this->skin->get_control_id( 'link_feature_image_to_permalink' ) => '',
				],
			]
		);

		$this->skin->add_control(
			'show_overlay_permalink',
			[
				'label' => __( 'Permalink', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'condition' => [
					'query_post_type' => 'portfolio',
					$this->skin->get_control_id( 'show_overlay' ) => 'yes',
					$this->skin->get_control_id( 'link_feature_image_to_permalink' ) => '',
				],
			]
		);

		$this->skin->add_control(
			'overlay_permalink_icon_new',
			[
				'label' => __( 'Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'fa4compatibility' => 'overlay_permalink_icon',
				'default' => [
					'value' => 'fas fa-link',
					'library' => 'fa-solid',
				],
				'condition' => [
					'query_post_type' => 'portfolio',
					$this->skin->get_control_id( 'show_overlay' ) => 'yes',
					$this->skin->get_control_id( 'show_overlay_permalink' ) => 'yes',
					$this->skin->get_control_id( 'link_feature_image_to_permalink' ) => '',
				],
			]
		);

		$this->skin->add_control(
			'show_image',
			[
				'label' => __( 'Featured Image', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'conditions' => $conditions,
			]
		);

		$this->skin->add_control(
			'show_title',
			[
				'label' => __( 'Post Title', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'conditions' => $conditions,
			]
		);

		$this->skin->add_control(
			'show_date',
			[
				'label' => __( 'Date', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'conditions' => $conditions,
			]
		);

		$this->skin->add_control(
			'show_author',
			[
				'label' => __( 'Author', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'conditions' => $conditions,
			]
		);

		$this->skin->add_control(
			'show_categories',
			[
				'label' => __( 'Categories', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'conditions' => $conditions,
			]
		);

		$this->skin->add_control(
			'show_tags',
			[
				'label' => __( 'Tags', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'conditions' => $conditions,
			]
		);

		$this->skin->add_control(
			'show_comments',
			[
				'label' => __( 'Comments', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'conditions' => $conditions,
			]
		);

		$this->skin->add_control(
			'meta_position',
			[
				'label' => __( 'Meta Position', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'after_title',
				'options' => [
					'before_title'  => __( 'Before Title', 'jupiterx-core' ),
					'after_title'   => __( 'After Title', 'jupiterx-core' ),
					'after_excerpt' => __( 'After Excerpt', 'jupiterx-core' ),
				],
				'conditions' => $conditions,
			]
		);

		$this->skin->add_control(
			'show_excerpt',
			[
				'label' => __( 'Excerpt', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'conditions' => $conditions,
			]
		);

		$this->skin->add_control(
			'show_button',
			[
				'label' => __( 'CTA Button', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'conditions' => $conditions,
			]
		);
	}

	/**
	 * Register title controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_container_controls() {
		$this->skin->start_controls_section(
			'section_container',
			[
				'label' => __( 'Container', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->skin->add_responsive_control(
			'post_padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_responsive_control(
			'post_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => '',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->skin->start_controls_tabs( 'tabs_post' );

		$this->skin->start_controls_tab(
			'tab_post_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->skin->add_group_control(
			'raven-background',
			[
				'name' => 'post_background',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Background Color Type', 'jupiterx-core' ),
					],
					'color' => [
						'label' => __( 'Background Color', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-post',
			]
		);

		$this->skin->add_control(
			'post_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->skin->add_control(
			'post_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->skin->get_control_id( 'post_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->skin->add_group_control(
			'border',
			[
				'name' => 'post_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-post',
			]
		);

		$this->skin->add_control(
			'post_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_group_control(
			'box-shadow',
			[
				'name' => 'post_box_shadow',
				'selector' => '{{WRAPPER}} .raven-post',
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->start_controls_tab(
			'tab_post_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->skin->add_group_control(
			'raven-background',
			[
				'name' => 'post_background_hover',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Background Color Type', 'jupiterx-core' ),
					],
					'color' => [
						'label' => __( 'Background Color', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-post:hover',
			]
		);

		$this->skin->add_control(
			'post_border_heading_hover',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->skin->add_control(
			'post_border_color_hover',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->skin->get_control_id( 'post_border_hover_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->skin->add_group_control(
			'border',
			[
				'name' => 'post_border_hover',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-post:hover',
			]
		);

		$this->skin->add_control(
			'post_border_radius_hover',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_group_control(
			'box-shadow',
			[
				'name' => 'post_box_shadow_hover',
				'selector' => '{{WRAPPER}} .raven-post:hover',
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->end_controls_tabs();

		$this->skin->end_controls_section();
	}

	/**
	 * Register image controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_image_controls() {
		$this->skin->start_controls_section(
			'section_image',
			[
				'label' => __( 'Featured Image', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					$this->skin->get_control_id( 'show_image' ) => 'yes',
				],
			]
		);

		$this->skin->add_responsive_control(
			'post_image_height',
			[
				'label' => __( 'Image Ratio', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => '0.63',
				],
				'tablet_default' => [
					'size' => '0.63',
				],
				'mobile_default' => [
					'size' => '0.63',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-image' => 'padding-bottom: calc( {{SIZE}} * 100% );',
				],
			]
		);

		$this->skin->add_responsive_control(
			'post_image_width',
			[
				'label' => __( 'Image Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'default' => [
					'size' => '100',
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => '100',
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => '100',
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post:not(.raven-post-inline) .raven-post-image, {{WRAPPER}} .raven-post-inline .raven-post-image-wrap' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_control(
			'post_image_position',
			[
				'label' => __( 'Image Position', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'top',
				'options' => [
					'left' => __( 'Left', 'jupiterx-core' ),
					'right' => __( 'Right', 'jupiterx-core' ),
					'top' => __( 'Top', 'jupiterx-core' ),
				],
				'condition' => [
					$this->skin->get_control_id( 'layout' ) => 'grid',
				],
			]
		);

		$this->skin->add_responsive_control(
			'post_image_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-post-image-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} [data-mirrored] .raven-post-inline-left .raven-post-image-wrap' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} [data-mirrored] .raven-post-inline-right .raven-post-image-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_control(
			'post_image_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'label_block' => false,
				'default' => '',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-image-wrap' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					$this->skin->get_control_id( 'post_image_position' ) => 'top',
				],
			]
		);

		$this->skin->add_control(
			'post_image_hover_effect',
			[
				'label' => __( 'Hover Effect', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '',
				'options' => [
					'' => __( 'None', 'jupiterx-core' ),
					'slide-right' => __( 'Slide Right', 'jupiterx-core' ),
					'slide-down' => __( 'Slide Down', 'jupiterx-core' ),
					'scale-down' => __( 'Scale Down', 'jupiterx-core' ),
					'scale-up' => __( 'Scale Up', 'jupiterx-core' ),
					'blur' => __( 'Blur', 'jupiterx-core' ),
					'grayscale-reverse' => __( 'Grayscale to Color', 'jupiterx-core' ),
					'grayscale' => __( 'Color to Grayscale', 'jupiterx-core' ),
				],
				'prefix_class' => 'raven-hover-',
			]
		);

		$this->skin->start_controls_tabs( 'tabs_post_image' );

		$this->skin->start_controls_tab(
			'tab_post_image_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->skin->add_responsive_control(
			'post_image_opacity',
			[
				'label' => __( 'Opacity', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => '1',
				],
				'tablet_default' => [
					'size' => '1',
				],
				'mobile_default' => [
					'size' => '1',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-image img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->skin->add_control(
			'post_image_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->skin->add_control(
			'post_image_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-post-image' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->skin->add_group_control(
			'border',
			[
				'name' => 'post_image_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-post-image',
			]
		);

		$this->skin->add_control(
			'post_image_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->start_controls_tab(
			'tab_post_image_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->skin->add_responsive_control(
			'hover_post_image_opacity',
			[
				'label' => __( 'Opacity', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => '1',
				],
				'tablet_default' => [
					'size' => '1',
				],
				'mobile_default' => [
					'size' => '1',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-image:hover img' => 'opacity: {{SIZE}};',
					'{{WRAPPER}} .raven-post-inside:hover .raven-post-image img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->skin->add_control(
			'post_image_border_heading_hover',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->skin->add_control(
			'post_image_border_color_hover',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-post-image:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->skin->add_group_control(
			'border',
			[
				'name' => 'post_image_border_hover',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-post-image:hover',
			]
		);

		$this->skin->add_control(
			'post_image_border_radius_hover',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-image:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->end_controls_tabs();

		$this->skin->end_controls_section();
	}

	/**
	 * Register overlay(hover) controls.
	 *
	 * @since 1.0.4
	 *
	 * @access protected
	 */
	protected function register_overlay_controls() {
		$this->skin->start_controls_section(
			'section_overlay',
			[
				'label' => __( 'Overlay (Hover)', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->skin->add_group_control(
			'raven-background',
			[
				'name' => 'post_image_overlay',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Color Type', 'jupiterx-core' ),
						'default' => 'classic',
					],
					'color' => [
						'label' => __( 'Color', 'jupiterx-core' ),
						'default' => 'rgba(0,0,0,0)',
					],
				],
				'selector' => '{{WRAPPER}} .raven-post .raven-post-image-overlay:hover, {{WRAPPER}} .raven-post-inside:hover .raven-post-image-overlay',
			]
		);

		$this->skin->end_controls_section();
	}

	/**
	 * Register icons controls.
	 *
	 * @since 1.0.4
	 *
	 * @access protected
	 */
	protected function register_icons_controls() {
		$this->skin->start_controls_section(
			'section_icons',
			[
				'label' => __( 'Icons', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'query_post_type' => 'portfolio',
					$this->skin->get_control_id( 'show_overlay' ) => 'yes',
				],
			]
		);

		$this->skin->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-overlay-icons i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-post-overlay-icons svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_responsive_control(
			'icon_space_between',
			[
				'label' => __( 'Space Between', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-overlay-icons a + a' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_responsive_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-post-overlay-icons i, {{WRAPPER}} .raven-post-overlay-icons svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_responsive_control(
			'icon_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'center',
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'center', 'jupiterx-core' ),
						'icon' => 'fa fa-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-overlay-icons' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->skin->start_controls_tabs( 'icon_tabs' );

		$this->skin->start_controls_tab(
			'icon_tabs_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->skin->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .raven-post-overlay-icons i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-post-overlay-icons svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->skin->add_group_control(
			'background',
			[
				'name' => 'icon_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-post-overlay-icons i, {{WRAPPER}} .raven-post-overlay-icons svg',
			]
		);

		$this->skin->add_control(
			'icon_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->skin->add_group_control(
			'border',
			[
				'name' => 'icon_border',
				'exclude' => [ 'color' ],
				'selector' => '{{WRAPPER}} .raven-post-overlay-icons i, {{WRAPPER}} .raven-post-overlay-icons svg',
			]
		);

		$this->skin->add_control(
			'icon_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->skin->get_control_id( 'icon_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-overlay-icons i' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .raven-post-overlay-icons svg' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->skin->add_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-post-overlay-icons i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .raven-post-overlay-icons svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_group_control(
			'box-shadow',
			[
				'name' => 'icon_box_shadow',
				'selector' => '{{WRAPPER}} .raven-post-overlay-icons i:not(:hover), {{WRAPPER}} .raven-post-overlay-icons svg:not(:hover)',
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->start_controls_tab(
			'icon_tabs_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->skin->add_control(
			'icon_color_hover',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .raven-post-overlay-icons i:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-post-overlay-icons svg:hover' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->skin->add_group_control(
			'background',
			[
				'name' => 'icon_background_hover',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-post-overlay-icons i:hover, {{WRAPPER}} .raven-post-overlay-icons svg:hover',
				'fields_options' => [
					'color' => [
						'selectors' => [
							'{{WRAPPER}} .raven-post-overlay-icons i:hover' => 'background-color: {{VALUE}}; background-image: none;',
							'{{WRAPPER}} .raven-post-overlay-icons svg:hover' => 'background-color: {{VALUE}}; background-image: none;',
						],
					],
				],
			]
		);

		$this->skin->add_control(
			'icon_border_heading_hover',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->skin->add_group_control(
			'border',
			[
				'name' => 'icon_border_hover',
				'exclude' => [ 'color' ],
				'selector' => '{{WRAPPER}} .raven-post-overlay-icons i:hover, {{WRAPPER}} .raven-post-overlay-icons svg:hover',
			]
		);

		$this->skin->add_control(
			'icon_border_color_hover',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->skin->get_control_id( 'icon_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-overlay-icons i:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .raven-post-overlay-icons svg:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->skin->add_control(
			'icon_border_radius_hover',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-post-overlay-icons i:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .raven-post-overlay-icons svg:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_group_control(
			'box-shadow',
			[
				'name' => 'icon_box_shadow_hover',
				'selector' => '{{WRAPPER}} .raven-post-overlay-icons i:hover, {{WRAPPER}} .raven-post-overlay-icons svg:hover',
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->end_controls_tabs();

		$this->skin->end_controls_section();
	}

	/**
	 * Register title controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_title_controls() {
		$this->skin->start_controls_section(
			'section_title',
			[
				'label' => __( 'Post Title', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					$this->skin->get_control_id( 'show_title' ) => 'yes',
				],
			]
		);

		$this->skin->add_group_control(
			'typography',
			[
				'name' => 'post_title_typography',
				'scheme' => '1',
				'selector' => '{{WRAPPER}} .raven-post-title, {{WRAPPER}} .raven-post-title a',
			]
		);

		$this->skin->add_responsive_control(
			'post_title_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_responsive_control(
			'post_title_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => '',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->skin->start_controls_tabs( 'tabs_post_title' );

		$this->skin->start_controls_tab(
			'tab_title_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->skin->add_control(
			'post_title_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-post-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-post-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->start_controls_tab(
			'tab_title_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->skin->add_control(
			'post_title_color_hover',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-post-title:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-post-title:hover a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->end_controls_tabs();

		$this->skin->end_controls_section();
	}

	/**
	 * Register meta controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_meta_controls() {
		// Manually add `_skin` test condition.
		$conditions = [
			'relation' => 'and',
			'terms' => [
				[
					'relation' => 'or',
					'terms' => [
						[
							'name' => $this->skin->get_control_id( 'show_categories' ),
							'operator' => '===',
							'value' => 'yes',
						],
						[
							'name' => $this->skin->get_control_id( 'show_tags' ),
							'operator' => '===',
							'value' => 'yes',
						],
						[
							'name' => $this->skin->get_control_id( 'show_author' ),
							'operator' => '===',
							'value' => 'yes',
						],
						[
							'name' => $this->skin->get_control_id( 'show_date' ),
							'operator' => '===',
							'value' => 'yes',
						],
						[
							'name' => $this->skin->get_control_id( 'show_comments' ),
							'operator' => '===',
							'value' => 'yes',
						],
					],
				],
				[
					'name' => '_skin',
					'operator' => '===',
					'value' => $this->skin->get_id(),
				],
			],
		];

		$this->skin->start_controls_section(
			'section_meta',
			[
				'label' => __( 'Meta', 'jupiterx-core' ),
				'tab' => 'style',
				'conditions' => $conditions,
			]
		);

		$this->skin->add_group_control(
			'typography',
			[
				'name' => 'post_meta_typography',
				'scheme' => '3',
				'conditions' => $conditions,
				'selector' => '{{WRAPPER}} .raven-post-meta',
			]
		);

		$this->skin->add_control(
			'post_meta_divider',
			[
				'label' => __( 'Meta Divider', 'jupiterx-core' ),
				'type' => 'text',
				'default' => '/',
				'conditions' => $conditions,
			]
		);

		$this->skin->add_responsive_control(
			'post_meta_divider_spacing',
			[
				'label' => __( 'Divider Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'conditions' => $conditions,
				'selectors' => [
					'{{WRAPPER}} .raven-post-meta-divider' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_responsive_control(
			'post_meta_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'conditions' => $conditions,
				'selectors' => [
					'{{WRAPPER}} .raven-post-meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_responsive_control(
			'post_meta_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => '',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'conditions' => $conditions,
				'selectors' => [
					'{{WRAPPER}} .raven-post-meta' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->skin->start_controls_tabs( 'tabs_post_meta' );
		$this->skin->start_controls_tab(
			'tab_post_meta_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->skin->add_control(
			'post_meta_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'conditions' => $conditions,
				'selectors' => [
					'{{WRAPPER}} .raven-post-meta' => 'color: {{VALUE}};',
				],
			]
		);

		$this->skin->add_control(
			'post_meta_links_color',
			[
				'label' => __( 'Links Color', 'jupiterx-core' ),
				'type' => 'color',
				'conditions' => $conditions,
				'selectors' => [
					'{{WRAPPER}} .raven-post-meta a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->start_controls_tab(
			'tab_post_meta_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->skin->add_control(
			'post_meta_color_hover',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'conditions' => $conditions,
				'selectors' => [
					'{{WRAPPER}} .raven-post-meta:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->skin->add_control(
			'post_meta_links_color_hover',
			[
				'label' => __( 'Links Color', 'jupiterx-core' ),
				'type' => 'color',
				'conditions' => $conditions,
				'selectors' => [
					'{{WRAPPER}} .raven-post-meta a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->end_controls_tabs();

		$this->skin->end_controls_section();
	}

	/**
	 * Register excerpt controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_excerpt_controls() {
		$this->skin->start_controls_section(
			'section_excerpt',
			[
				'label' => __( 'Excerpt', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					$this->skin->get_control_id( 'show_excerpt' ) => 'yes',
				],
			]
		);

		$this->skin->add_control(
			'excerpt_length',
			[
				'label' => __( 'Excerpt Length', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 150,
						'step' => 1,
					],
				],
			]
		);

		$this->skin->add_group_control(
			'typography',
			[
				'name' => 'post_excerpt_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .raven-post-excerpt',
			]
		);

		$this->skin->add_responsive_control(
			'post_excerpt_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-post-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_responsive_control(
			'post_excerpt_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => '',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-excerpt' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->skin->start_controls_tabs( 'tabs_post_excerpt' );

		$this->skin->start_controls_tab(
			'tab_post_excerpt_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->skin->add_control(
			'post_excerpt_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-post-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->start_controls_tab(
			'tab_post_excerpt_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->skin->add_control(
			'post_excerpt_color_hover',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-post-excerpt:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->end_controls_tabs();

		$this->skin->end_controls_section();
	}

	/**
	 * Register button controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_button_controls() {
		$this->skin->start_controls_section(
			'section_button',
			[
				'label' => __( 'CTA Button', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					$this->skin->get_control_id( 'show_button' ) => 'yes',
				],
			]
		);

		$this->skin->add_control(
			'post_button_text',
			[
				'label' => __( 'Text', 'jupiterx-core' ),
				'type' => 'text',
				'default' => __( 'Read More', 'jupiterx-core' ),
			]
		);

		$this->skin->add_responsive_control(
			'post_button_width',
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
					'{{WRAPPER}} .raven-post-button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_responsive_control(
			'post_button_height',
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
					'{{WRAPPER}} .raven-post-button' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_responsive_control(
			'post_button_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-post-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_responsive_control(
			'post_button_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'prefix_class' => 'raven%s-button-align-',
				'default' => '',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'jupiterx-core' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-read-more' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->skin->start_controls_tabs( 'tabs_button' );

		$this->skin->start_controls_tab(
			'tabs_button_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->skin->add_control(
			'post_button_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-post-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->skin->add_group_control(
			'typography',
			[
				'name' => 'post_button_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .raven-post-button',
			]
		);

		$this->skin->add_group_control(
			'raven-background',
			[
				'name' => 'post_button_background',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Background Color Type', 'jupiterx-core' ),
					],
					'color' => [
						'label' => __( 'Background Color', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-post-button',
			]
		);

		$this->skin->add_control(
			'post_button_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
			]
		);

		$this->skin->add_control(
			'post_button_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->skin->get_control_id( 'post_button_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->skin->add_group_control(
			'border',
			[
				'name' => 'post_button_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-post-button',
			]
		);

		$this->skin->add_control(
			'post_button_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_group_control(
			'box-shadow',
			[
				'name' => 'post_button_box_shadow',
				'selector' => '{{WRAPPER}} .raven-post-button',
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->start_controls_tab(
			'tabs_button_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->skin->add_control(
			'hover_post_button_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-post-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->skin->add_group_control(
			'typography',
			[
				'name' => 'hover_post_button_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .raven-post-button:hover',
			]
		);

		$this->skin->add_group_control(
			'raven-background',
			[
				'name' => 'hover_post_button_background',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Background Color Type', 'jupiterx-core' ),
					],
					'color' => [
						'label' => __( 'Background Color', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-post-button:hover',
			]
		);

		$this->skin->add_control(
			'hover_post_button_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
			]
		);

		$this->skin->add_control(
			'hover_post_button_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->skin->get_control_id( 'hover_post_button_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->skin->add_group_control(
			'border',
			[
				'name' => 'hover_post_button_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-post-button:hover',
			]
		);

		$this->skin->add_control(
			'hover_post_button_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_group_control(
			'box-shadow',
			[
				'name' => 'hover_post_button_box_shadow',
				'selector' => '{{WRAPPER}} .raven-post-button:hover',
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->end_controls_tabs();

		$this->skin->end_controls_section();
	}

	protected function register_carousel_overlay_controls() {
		$this->skin->start_controls_section(
			'section_overlay',
			[
				'label' => esc_html__( 'Overlay', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->skin->start_controls_tabs( 'overlay_tabs' );

		$this->skin->start_controls_tab(
			'overlay_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->skin->add_group_control(
			'raven-background',
			[
				'name' => 'posts_featured_image_overlay_normal',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Color Type', 'jupiterx-core' ),
						'default' => 'classic',
					],
					'color' => [
						'label' => esc_html__( 'Color', 'jupiterx-core' ),
						'default' => 'rgba(0,0,0,0)',
					],
				],
				'selector' => '{{WRAPPER}} .raven-posts-carousel .raven-post-image-overlay',
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->start_controls_tab(
			'overlay_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->skin->add_group_control(
			'raven-background',
			[
				'name' => 'posts_featured_image_overlay_hover',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Color Type', 'jupiterx-core' ),
						'default' => 'classic',
					],
					'color' => [
						'label' => esc_html__( 'Color', 'jupiterx-core' ),
						'default' => 'rgba(0,0,0,0)',
					],
				],
				'selector' => '{{WRAPPER}} .raven-posts-carousel .raven-post-image-overlay:hover, {{WRAPPER}} .raven-post-inside:hover .raven-post-image-overlay',
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->end_controls_tabs();

		$this->skin->add_control(
			'posts_featured_image_overlay_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 0.5,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-posts-carousel .raven-post-image-overlay' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->skin->end_controls_section();
	}

	protected function register_author_apotlight_style() {
		$this->skin->start_controls_section(
			'section_author_spotlight',
			[
				'label' => esc_html__( 'Author Spotlight', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					$this->skin->get_control_id( 'author_spotlight' ) => 'yes',
				],
			]
		);

		$this->skin->add_control(
			'author_spotlight_name_heading',
			[
				'label' => esc_html__( 'Name', 'jupiterx-core' ),
				'type' => 'heading',
			]
		);

		$this->skin->add_control(
			'author_spotlight_name_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .raven-post-author-spotlight' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-post-author-spotlight a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->skin->add_group_control(
			'typography',
			[
				'name' => 'author_spotlight_name_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .raven-post-author-spotlight a',
			]
		);

		$this->skin->add_responsive_control(
			'author_spotlight_name_gap',
			[
				'label' => esc_html__( 'Gap', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '18',
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => '18',
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => '18',
					'unit' => 'px',
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .raven-post-author-spotlight img' => 'margin-right: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .raven-post-author-spotlight img' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_responsive_control(
			'author_spotlight_name_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '20',
					'right' => '23',
					'bottom' => '20',
					'left' => '23',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-author-spotlight' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_control(
			'author_spotlight_image_heading',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->skin->add_responsive_control(
			'author_spotlight_image_width',
			[
				'label' => esc_html__( 'Image Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'default' => [
					'size' => '38',
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => '38',
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => '38',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-author-spotlight img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_control(
			'author_spotlight_divider_heading',
			[
				'label' => esc_html__( 'Divider', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->skin->add_control(
			'author_spotlight_divider_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#EEEEEE',
				'selectors' => [
					'{{WRAPPER}} .raven-post-author-spotlight' => 'border-top-color: {{VALUE}}',
				],
			]
		);

		$this->skin->add_control(
			'author_spotlight_divider',
			[
				'label' => esc_html__( 'Weight', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-author-spotlight' => 'border-top-width: {{SIZE}}px;',
				],
			]
		);

		$this->skin->end_controls_section();
	}

	/**
	 * Get render by stack.
	 *
	 * Use to get render in a stack list format.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param array $stack_list List of function names.
	 */
	protected function get_render_stack( $stack_list = [] ) {
		$stack_render = [];

		foreach ( $stack_list as $stack_item ) {
			$func_name = 'get_render_' . $stack_item;

			$to_render = $this->$func_name();

			if ( ! empty( $to_render ) ) {
				$stack_render[] = $to_render;
			}
		}

		return $stack_render;
	}

	/**
	 * Render the post.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $instance Instance of either Widget or Skin.
	 */
	public function render_post( $instance ) {
		$this->skin = $instance;

		$layout         = $this->skin->get_instance_value( 'layout' );
		$show_image     = $this->skin->get_instance_value( 'show_image' );
		$image_position = $this->skin->get_instance_value( 'post_image_position' );
		$hover_effect   = $this->skin->get_instance_value( 'post_hover_effect' );

		$item_classes = [
			'masonry' === $layout ? 'raven-masonry-item' : 'raven-grid-item',
			'raven-post-item',
		];

		$item_classes = array_merge( $item_classes, get_post_class() );

		$post_classes = [ 'raven-post' ];

		if ( 'grid' === $layout && 'yes' === $show_image && 'top' !== $image_position ) {
			$post_classes[] = 'raven-post-inline raven-post-inline-' . $image_position;
		}

		if ( ! empty( $hover_effect ) ) {
			$post_classes[] = 'elementor-animation-' . $hover_effect;
		}

		?>
		<div class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>">
			<div class="<?php echo esc_attr( implode( ' ', $post_classes ) ); ?>">
				<?php $this->render_image(); ?>
				<div class="raven-post-content">
					<?php
					$this->render_ordered_content();
					$this->render_button();
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Put meta on position based on settings.
	 *
	 * @since 1.0.4
	 *
	 * @return void
	 */
	protected function render_ordered_content() {
		$meta_position = $this->skin->get_instance_value( 'meta_position' );

		switch ( $meta_position ) {
			case 'before_title':
				$this->render_meta();
				$this->render_title();
				$this->render_excerpt();
				break;
			case 'after_excerpt':
				$this->render_title();
				$this->render_excerpt();
				$this->render_meta();
				break;
			default:
				$this->render_title();
				$this->render_meta();
				$this->render_excerpt();
				break;
		}
	}

	/**
	 * Render author spotlight.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	protected function render_author_spotlight() {
		if ( ! $this->skin->get_instance_value( 'author_spotlight' ) ) {
			return;
		}

		printf(
			'<div class="raven-post-author-spotlight"><a href="%3$s"><img src="%1$s" title="%2$s" alt="%2$s">%4$s %2$s</a></div>',
			esc_attr( get_avatar_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() ),
			esc_attr( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html__( 'By', 'jupiterx-core' )
		);
	}

	/**
	 * Is overlay enabled or not.
	 *
	 * @since 1.0.4
	 *
	 * @return boolean
	 */
	protected function has_overlay() {
		$show_overlay = $this->skin->get_instance_value( 'show_overlay' );

		if ( 'yes' === $show_overlay && 'portfolio' === get_post_type() ) {
			return true;
		}

		return false;
	}

	/**
	 * Is overlay disabled or not.
	 *
	 * @since 1.2.0
	 *
	 * @return boolean
	 */
	protected function disabled_overlay() {
		if ( 'portfolio' === get_post_type() ) {
			return $this->skin->get_instance_value( 'link_feature_image_to_permalink' ) === 'yes';
		}

		return false;
	}

	/**
	 * Render Overlay icons.
	 *
	 * @since 1.0.4
	 *
	 * @return void
	 */
	protected function get_render_overlay() {
		$show_quick_view     = $this->skin->get_instance_value( 'show_quick_view' );
		$show_permalink      = $this->skin->get_instance_value( 'show_overlay_permalink' );
		$quick_view_icon     = $this->skin->get_instance_value( 'overlay_quick_view_icon' );
		$quick_view_icon_new = $this->skin->get_instance_value( 'overlay_quick_view_icon_new' );
		$permalink_icon      = $this->skin->get_instance_value( 'overlay_permalink_icon' );
		$permalink_icon_new  = $this->skin->get_instance_value( 'overlay_permalink_icon_new' );

		if ( ! $this->has_overlay() || $this->disabled_overlay() ) {
			return;
		}
		ob_start();
		?>
		<span class="raven-post-overlay-icons" >
		<?php if ( 'yes' === $show_quick_view && ( ! empty( $quick_view_icon ) || ! empty( $quick_view_icon_new['value'] ) ) ) : ?>
			<a class="raven-overlay-icon raven-overlay-qucik-view-icon slick-slide-image" href="<?php the_post_thumbnail_url( 'full' ); ?>" data-elementor-lightbox-slideshow="<?php echo $this->skin->parent->get_id(); ?>">
				<?php $this->render_quick_view_icon(); ?>
			</a>
		<?php endif; ?>
		<?php if ( 'yes' === $show_permalink && ( ! empty( $permalink_icon ) || ! empty( $permalink_icon_new['value'] ) ) ) : ?>
		<a class="raven-overlay-icon raven-overlay-permalink-icon" href="<?php the_permalink(); ?>">
			<?php $this->render_permalink_icon(); ?>
		</a>
		<?php endif; ?>
		</span>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render the post image.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_image() {
		if ( ! $this->skin->get_instance_value( 'show_image' ) ) {
			return;
		}

		$settings = [
			'image_size' => $this->skin->get_instance_value( 'post_image_size_size' ),
			'image' => [
				'id' => get_post_thumbnail_id(),
			],
			'image_custom_dimension' => $this->skin->get_instance_value( 'post_image_size_custom_dimension' ),
		];

		$image_html = \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings );

		if ( empty( $image_html ) ) {
			return;
		}

		$classes = [
			'raven-post-image',
		];

		$html_tag = $this->has_overlay() && ! $this->disabled_overlay() ? 'span' : 'a';

		if ( 'grid' === $this->skin->get_instance_value( 'layout' ) || 'portfolio' === get_post_type() ) {
			$classes[] = 'raven-image-fit';
		}

		?>
		<div class="raven-post-image-wrap">
			<?php
			printf(
				'<%1$s class="%2$s" %3$s>%4$s <span class="raven-post-image-overlay">%5$s</span></%1$s>',
				$html_tag,
				implode( ' ', $classes ),
				( 'a' === $html_tag ) ? 'href="' . get_permalink() . '"' : '',
				$image_html,
				$this->get_render_overlay()
			);
			?>
		</div>
		<?php
	}

	/**
	 * Render the post title.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_title() {
		if ( ! $this->skin->get_instance_value( 'show_title' ) ) {
			return;
		}

		printf(
			'<%1$s class="raven-post-title"><a class="raven-post-title-link" href="%2$s">%3$s</a></%1$s>',
			$this->skin->get_instance_value( 'post_title_tag' ),
			get_permalink(),
			get_the_title()
		);
	}

	/**
	 * Render the post meta.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_meta() {
		// We can modify this to re-order the meta stack.
		$meta_list = [
			'date',
			'author',
			'categories',
			'tags',
			'comments',
		];

		$meta_stack = $this->get_render_stack( $meta_list );

		if ( empty( $meta_stack ) ) {
			return;
		}

		$meta_html = implode( $this->get_render_divider(), $meta_stack );

		?>
		<div class="raven-post-meta"><?php echo wp_kses_post( $meta_html ); ?></div>
		<?php
	}

	/**
	 * Render the post meta divider.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_render_divider() {
		if ( ! $this->skin->get_instance_value( 'post_meta_divider' ) ) {
			return PHP_EOL;
		}

		return PHP_EOL . '<span class="raven-post-meta-divider">' . $this->skin->get_instance_value( 'post_meta_divider' ) . '</span>' . PHP_EOL;
	}

	/**
	 * Render the post meta date.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_render_date() {
		if ( ! $this->skin->get_instance_value( 'show_date' ) ) {
			return;
		}

		$date_link = ( 'post' === get_post_type() ) ? get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) : get_permalink();

		if ( empty( $this->skin->get_instance_value( 'date_type' ) ) ) {
			return '<a class="raven-post-meta-item raven-post-date" href="' . esc_url( $date_link ) . '" rel="bookmark">' . esc_html( get_the_date() ) . '</a>';
		}

		$format_options = [
			'1' => 'F j, Y',
			'2' => 'F jS, Y',
			'3' => 'M j, Y',
			'4' => 'Y/m/d',
			'5' => 'd/m/Y',
			'6' => 'd.m.Y',
			'7' => 'm.d.Y',
		];

		$format = ! empty( $format_options[ $this->skin->get_instance_value( 'date_format' ) ] ) ? $format_options[ $this->skin->get_instance_value( 'date_format' ) ] : $this->skin->get_instance_value( 'custom_format' );

		$date = get_the_date( $format );

		if ( 'last_modified' === $this->skin->get_instance_value( 'date_type' ) ) {
			$date = get_the_modified_date( $format );
		}

		return '<a class="raven-post-meta-item raven-post-date" href="' . esc_url( $date_link ) . '" rel="bookmark">' . esc_html( $date ) . '</a>';
	}

	/**
	 * Render the post meta author.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_render_author() {
		if ( ! $this->skin->get_instance_value( 'show_author' ) ) {
			return;
		}

		$href = get_author_posts_url( get_the_author_meta( 'ID' ) );

		return '<a class="raven-post-meta-item raven-post-author" href="' . esc_url( $href ) . '">' . esc_html( get_the_author() ) . '</a>';
	}

	/**
	 * Render the post meta categories.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_render_categories() {
		$post_type = get_post_type();

		if ( ! in_array( $post_type, [ 'post', 'portfolio' ], true ) || ! $this->skin->get_instance_value( 'show_categories' ) ) {
			return;
		}

		$taxonomy = 'category';

		if ( 'portfolio' === $post_type ) {
			$taxonomy = 'portfolio_category';
		}

		$categories_list = get_the_term_list( get_the_ID(), $taxonomy, '', ', ', '' );

		if ( empty( $categories_list ) ) {
			return;
		}

		return sprintf( '<span class="raven-post-meta-item raven-post-categories">%1$s</span>', $categories_list );
	}

	/**
	 * Render the post meta tags.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_render_tags() {
		$post_type = get_post_type();

		if ( ! in_array( $post_type, [ 'post', 'portfolio' ], true ) || ! $this->skin->get_instance_value( 'show_tags' ) ) {
			return;
		}

		$taxonomy = 'post_tag';

		if ( 'portfolio' === $post_type ) {
			$taxonomy = 'portfolio_tag';
		}

		$tags_list = get_the_term_list( get_the_ID(), $taxonomy, '', ', ', '' );

		if ( empty( $tags_list ) ) {
			return;
		}

		return sprintf( '<span class="raven-post-meta-item raven-post-tags">%1$s</span>', $tags_list );
	}

	/**
	 * Render the post comments.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_render_comments() {
		if ( ! $this->skin->get_instance_value( 'show_comments' ) ) {
			return;
		}

		return '<a class="raven-post-meta-item raven-post-comments" href="' . esc_url( get_permalink() ) . '#comments" rel="bookmark">' . esc_html( get_comments_number_text() ) . '</a>';
	}

	/**
	 * Render the post excerpt.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_excerpt() {
		if ( ! $this->skin->get_instance_value( 'show_excerpt' ) ) {
			return;
		}

		?>
		<div class="raven-post-excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></div>
		<?php
	}

	/**
	 * Render the post button.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_button() {
		if ( ! $this->skin->get_instance_value( 'show_button' ) ) {
			return;
		}

		?>
		<div class="raven-post-read-more">
			<a class="raven-post-button" href="<?php the_permalink(); ?>"><span class="raven-post-button-text"><?php echo $this->skin->get_instance_value( 'post_button_text' ); ?></span></a>
		</div>
		<?php
	}

	/**
	 * Render the post render icon.
	 *
	 * Currently supports Dashicons from WordPress.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_render_icon() {
		if ( ! $this->skin->get_instance_value( 'show_image' ) ) {
			return;
		}

		$post_type = get_post_type_object( get_post_type() );

		if ( empty( $post_type ) ) {
			return;
		}

		$icon_class = [
			'raven-post-icon',
			'dashicons-before',
		];

		$icon_class[] = false !== strpos( $post_type->menu_icon, 'dashicon' ) ? $post_type->menu_icon : 'dashicons-admin-post';

		$icon_html = sprintf( '<span class="%1$s"></span>', implode( ' ', $icon_class ) );

		return $icon_html;
	}

	protected function render_quick_view_icon() {
		$quick_view_icon     = $this->skin->get_instance_value( 'overlay_quick_view_icon' );
		$quick_view_icon_new = $this->skin->get_instance_value( 'overlay_quick_view_icon_new' );

		if ( ! empty( $quick_view_icon_new['value'] ) ) {
			Elementor::$instance->icons_manager->render_icon( $quick_view_icon_new, [ 'aria-hidden' => 'true' ] );
		} else {
			?>
		<i class="<?php echo esc_attr( $quick_view_icon ); ?>" aria-hidden="true"></i>
			<?php
		}
	}

	protected function render_permalink_icon() {
		$permalink_icon     = $this->skin->get_instance_value( 'overlay_permalink_icon' );
		$permalink_icon_new = $this->skin->get_instance_value( 'overlay_permalink_icon_new' );

		if ( ! empty( $permalink_icon_new['value'] ) ) {
			Elementor::$instance->icons_manager->render_icon( $permalink_icon_new, [ 'aria-hidden' => 'true' ] );
		} else {
			?>
		<i class="<?php echo esc_attr( $permalink_icon ); ?>" aria-hidden="true"></i>
			<?php
		}
	}
}
