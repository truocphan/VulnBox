<?php
/**
 * @codingStandardsIgnoreFile
 */

namespace JupiterX_Core\Raven\Modules\Posts\Carousel\Actions;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Modules\Posts\Classes\Post_Base;

class Post_Cover extends Post_Base {

	private $conditions            = [];
	private $hover_conditions      = [];
	private $condition_static_part = [];

	protected function register_action_hooks() {
		add_action( 'elementor/element/raven-posts-carousel/section_sort_filter/after_section_end', [ $this, 'register_action_controls' ] );
	}

	public function register_action_controls( \Elementor\Widget_Base $widget ) {
		$this->skin = $widget->get_skin( 'cover' );

		$this->register_controls();
		$this->inject_controls();
		$this->update_controls();
	}

	protected function register_controls() {
		$this->conditions = [
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
							'name' => '_skin',
							'operator' => '===',
							'value' => 'classic',
						],
					],
				],
			],
		];

		$this->hover_conditions = [
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

		$this->condition_static_part = [
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
		];

		$this->register_container_controls();
		$this->register_image_controls();
		$this->register_carousel_overlay_controls();
		$this->register_icons_controls();
		$this->register_title_controls();
		$this->register_meta_controls();
		$this->register_excerpt_controls();
		$this->register_author_apotlight_style();
		$this->register_button_controls();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_container_controls() {
		$this->skin->start_controls_section(
			'section_container',
			[
				'label' => esc_html__( 'Block', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->skin->start_controls_tabs( 'tabs_post' );

		$this->skin->start_controls_tab(
			'tab_post_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->skin->add_group_control(
			'raven-background',
			[
				'name' => 'post_background',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Background Color Type', 'jupiterx-core' ),
					],
					'color' => [
						'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-post',
			]
		);

		$this->skin->add_control(
			'post_border_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
			]
		);

		$this->skin->add_control(
			'post_border_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
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
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-post',
			]
		);

		$this->skin->add_control(
			'post_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
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

		$this->skin->add_responsive_control(
			'post_block_padding',
			[
				'label' => esc_html__( 'Block Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .raven-post-wrapper .raven-post-inside' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_control(
			'block_content_heading',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
				'type' => 'heading',
			]
		);

		$this->skin->add_responsive_control(
			'post_align',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => '',
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
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->skin->add_control(
			'columns_vertical_align',
			[
				'label' => esc_html__( 'Vertical Position', 'jupiterx-core' ),
				'type' => 'choose',
				'label_block' => false,
				'default' => '',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-inside' => 'align-items: {{VALUE}};',
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'condition' => [
					$this->skin->get_control_id( 'show_image' ) => 'yes',
				],
			]
		);

		$this->skin->add_responsive_control(
			'post_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->start_controls_tab(
			'tab_post_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->skin->add_group_control(
			'raven-background',
			[
				'name' => 'hover_post_background',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Background Color Type', 'jupiterx-core' ),
					],
					'color' => [
						'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-post:hover',
			]
		);

		$this->skin->add_control(
			'hover_post_border_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
			]
		);

		$this->skin->add_control(
			'hover_post_border_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->skin->get_control_id( 'hover_post_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->skin->add_group_control(
			'border',
			[
				'name' => 'hover_post_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-post:hover',
			]
		);

		$this->skin->add_control(
			'hover_post_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
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
				'name' => 'hover_post_box_shadow',
				'selector' => '{{WRAPPER}} .raven-post:hover',
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->end_controls_tabs();

		$this->skin->end_controls_section();
	}

	protected function register_image_controls() {
		$this->skin->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Featured Image', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					$this->skin->get_control_id( 'show_image' ) => 'yes',
				],
			]
		);

		$this->skin->add_control(
			'post_image_background_position',
			[
				'label' => esc_html__( 'Background Position', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'center center',
				'options' => [
					'center center' => esc_html__( 'Center Center', 'jupiterx-core' ),
					'center left' => esc_html__( 'Center Left', 'jupiterx-core' ),
					'center right' => esc_html__( 'Center Right', 'jupiterx-core' ),
					'top center' => esc_html__( 'Top Center', 'jupiterx-core' ),
					'top left' => esc_html__( 'Top Left', 'jupiterx-core' ),
					'top right' => esc_html__( 'Top Right', 'jupiterx-core' ),
					'bottom center' => esc_html__( 'Bottom Center', 'jupiterx-core' ),
					'bottom left' => esc_html__( 'Bottom Left', 'jupiterx-core' ),
					'bottom right' => esc_html__( 'Bottom Right', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .raven-image-fit img' => '-o-object-position: {{VALUE}}; object-position: {{VALUE}};',
				],
			]
		);

		$this->skin->add_control(
			'post_image_background_size',
			[
				'label' => esc_html__( 'Background Size', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'cover',
				'options' => [
					'auto' => esc_html__( 'Auto', 'jupiterx-core' ),
					'cover' => esc_html__( 'Cover', 'jupiterx-core' ),
					'contain' => esc_html__( 'Contain', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .raven-image-fit img' => '-o-object-fit: {{VALUE}}; object-fit: {{VALUE}};',
				],
			]
		);

		$this->skin->start_controls_tabs( 'tabs_post_image' );

		$this->skin->start_controls_tab(
			'tab_post_image_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->skin->add_responsive_control(
			'post_image_opacity',
			[
				'label' => esc_html__( 'Opacity', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-image img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->start_controls_tab(
			'tab_post_image_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->skin->add_responsive_control(
			'hover_post_image_opacity',
			[
				'label' => esc_html__( 'Opacity', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-image:hover img' => 'opacity: {{SIZE}};',
					'{{WRAPPER}} .raven-post-inside:hover .raven-post-image img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->skin->end_controls_tab();

		$this->skin->end_controls_tabs();

		$this->skin->end_controls_section();
	}

	/**
	 * Register settings controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_settings_controls() {
		$this->skin->start_injection( [
			'at' => 'before',
			'of' => $this->skin->get_control_id( 'show_arrows' ),
		] );

		$this->skin->add_control(
			'elements_heading',
			[
				'label' => esc_html__( 'Elements', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'conditions' => $this->hover_conditions,
			]
		);

		$this->skin->add_control(
			'show_image',
			[
				'label' => esc_html__( 'Featured Image', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'conditions' => $this->hover_conditions,
			]
		);

		$this->skin->add_control(
			'show_title',
			[
				'label' => esc_html__( 'Post Title', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'conditions' => $this->hover_conditions,
			]
		);

		$this->skin->add_control(
			'show_date',
			[
				'label' => esc_html__( 'Date', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'conditions' => $this->hover_conditions,
			]
		);

		$this->skin->add_control(
			'date_type',
			[
				'label' => esc_html__( 'Date Type', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'published',
				'options' => [
					'published'  => esc_html__( 'Published Date', 'jupiterx-core' ),
					'last_modified'   => esc_html__( 'Last Modified Date', 'jupiterx-core' ),
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => '_skin',
							'operator' => '===',
							'value' => $this->skin->get_id(),
						],
						[
							'name' => $this->skin->get_control_id( 'show_date' ),
							'operator' => '===',
							'value' => 'yes',
						],
						$this->condition_static_part,
					],
				],
			]
		);

		$this->skin->add_control(
			'date_format',
			[
				'label' => esc_html__( 'Date Format', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '1',
				'options' => [
					'1'  => esc_html__( 'March 6, 2023', 'jupiterx-core' ),
					'2'   => esc_html__( 'March 23rd, 2023', 'jupiterx-core' ),
					'3'   => esc_html__( 'Mar 6, 2023', 'jupiterx-core' ),
					'4'   => esc_html__( '2023/03/23', 'jupiterx-core' ),
					'5'   => esc_html__( '23/03/2023', 'jupiterx-core' ),
					'6'   => esc_html__( '23.03.2023', 'jupiterx-core' ),
					'7'   => esc_html__( '03.23.2023', 'jupiterx-core' ),
					'custom'   => esc_html__( 'Custom', 'jupiterx-core' ),
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => '_skin',
							'operator' => '===',
							'value' => $this->skin->get_id(),
						],
						[
							'name' => $this->skin->get_control_id( 'show_date' ),
							'operator' => '===',
							'value' => 'yes',
						],
						$this->condition_static_part,
					],
				],
			]
		);

		$this->skin->add_control(
			'custom_format',
			[
				'label' => esc_html__( 'Custom Format', 'jupiterx-core' ),
				'type' => 'text',
				'default' => __( 'F j,Y', 'jupiterx-core' ),
				'description' => sprintf(
					/* translators: %1$s: open anchor tag, %2$s: close anchor tag. */
					esc_html__( 'Refer to PHP date formats %1$s here %2$s.', 'jupiterx-core' ),
					'<a href="https://wordpress.org/support/article/formatting-date-and-time/" target="_blank">',
					'</a>'
				),
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => '_skin',
							'operator' => '===',
							'value' => $this->skin->get_id(),
						],
						[
							'name' => $this->skin->get_control_id( 'show_date' ),
							'operator' => '===',
							'value' => 'yes',
						],
						[
							'name' => $this->skin->get_control_id( 'date_format' ),
							'operator' => '===',
							'value' => 'custom',
						],
						$this->condition_static_part,
					],
				],
			]
		);

		$this->skin->add_control(
			'date_divider',
			[
				'type' => 'divider',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => '_skin',
							'operator' => '===',
							'value' => $this->skin->get_id(),
						],
						[
							'name' => $this->skin->get_control_id( 'show_date' ),
							'operator' => '===',
							'value' => 'yes',
						],
						$this->condition_static_part,
					],
				],
			]
		);

		$this->skin->add_control(
			'show_author',
			[
				'label' => esc_html__( 'Author', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'conditions' => $this->hover_conditions,
			]
		);

		$this->skin->add_control(
			'show_categories',
			[
				'label' => esc_html__( 'Categories', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'conditions' => $this->hover_conditions,
			]
		);

		$this->skin->add_control(
			'show_tags',
			[
				'label' => esc_html__( 'Tags', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'conditions' => $this->hover_conditions,
			]
		);

		$this->skin->add_control(
			'tags_divider',
			[
				'type' => 'divider',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => '_skin',
							'operator' => '===',
							'value' => $this->skin->get_id(),
						],
						[
							'name' => $this->skin->get_control_id( 'show_tags' ),
							'operator' => '===',
							'value' => 'yes',
						],
						$this->condition_static_part,
					],
				],
			]
		);

		$this->skin->add_control(
			'show_comments',
			[
				'label' => esc_html__( 'Comments', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'conditions' => $this->hover_conditions,
			]
		);

		$this->skin->add_control(
			'show_excerpt',
			[
				'label' => esc_html__( 'Excerpt', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'conditions' => $this->hover_conditions,
			]
		);

		$this->skin->add_control(
			'show_button',
			[
				'label' => esc_html__( 'CTA Button', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'conditions' => $this->hover_conditions,
			]
		);

		$this->skin->end_injection();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function inject_controls() {
		$this->skin->start_injection( [
			'at' => 'after',
			'of' => $this->skin->get_control_id( 'slides_scroll' ),
		] );

		$this->register_image_size_control();

		$this->skin->end_injection();

		$this->register_settings_controls();

		$this->skin->start_injection( [
			'at' => 'after',
			'of' => $this->skin->get_control_id( 'pagination_type' ),
		] );

		$this->skin->add_control(
			'post_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'jupiterx-core' ),
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
				'conditions' => $this->hover_conditions,
			]
		);

		$this->skin->add_control(
			'meta_position',
			[
				'label' => esc_html__( 'Meta Position', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'after_title',
				'options' => [
					'before_title'  => esc_html__( 'Before Title', 'jupiterx-core' ),
					'after_title'   => esc_html__( 'After Title', 'jupiterx-core' ),
					'after_excerpt' => esc_html__( 'After Excerpt', 'jupiterx-core' ),
				],
				'conditions' => $this->conditions,
			]
		);

		$this->skin->end_injection();

		$this->skin->start_injection( [
			'at' => 'after',
			'of' => $this->skin->get_control_id( 'section_container' ),
		] );

		$this->skin->add_responsive_control(
			'parent_padding',
			[
				'label' => esc_html__( 'Parent Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'render_type' => 'template',
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .swiper-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .swiper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_responsive_control(
			'columns_height',
			[
				'label' => esc_html__( 'Height', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'vh', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
					'rem' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->skin->add_responsive_control(
			'columns_space_between',
			[
				'label' => esc_html__( 'Space Between', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'frontend_available' => true,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'desktop_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 15,
					'unit' => 'px',
				],
			]
		);

		$this->skin->end_injection();

		$this->skin->start_injection( [
			'type' => 'section',
			'at' => 'end',
			'of' => 'section_layout',
		] );

		$this->skin->add_control(
			'post_hover_effect',
			[
				'label' => esc_html__( 'Block Hover', 'jupiterx-core' ),
				'type' => 'raven_hover_effect',
			]
		);

		$this->skin->add_control(
			'post_image_hover_effect',
			[
				'label' => esc_html__( 'Featured Image Hover', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '',
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'zoom-move' => esc_html__( 'Zoom & Move', 'jupiterx-core' ),
					'slide-right' => esc_html__( 'Slide Right', 'jupiterx-core' ),
					'slide-down' => esc_html__( 'Slide Down', 'jupiterx-core' ),
					'scale-down' => esc_html__( 'Scale Down', 'jupiterx-core' ),
					'scale-up' => esc_html__( 'Scale Up', 'jupiterx-core' ),
					'blur' => __( 'Blur', 'jupiterx-core' ),
					'grayscale-reverse' => esc_html__( 'Grayscale to Color', 'jupiterx-core' ),
					'grayscale' => esc_html__( 'Color to Grayscale', 'jupiterx-core' ),
				],
				'render_type' => 'template',
				'frontend_available' => true,
				'prefix_class' => 'raven-hover-',
			]
		);

		$this->skin->add_control(
			'load_effect',
			[
				'label' => esc_html__( 'Load Effect', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '',
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'fade-in' => esc_html__( 'Fade In', 'jupiterx-core' ),
					'slide-down' => esc_html__( 'Slide Down', 'jupiterx-core' ),
					'slide-up' => esc_html__( 'Slide Up', 'jupiterx-core' ),
					'slide-right' => esc_html__( 'Slide Left', 'jupiterx-core' ),
					'slide-left' => esc_html__( 'Slide Right', 'jupiterx-core' ),
					'scale-up' => esc_html__( 'Scale Up', 'jupiterx-core' ),
					'scale-down' => esc_html__( 'Scale Down', 'jupiterx-core' ),
				],
				'frontend_available' => true,
			]
		);

		$this->skin->add_control(
			'author_spotlight',
			[
				'label' => esc_html__( 'Author Spotlight', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => '',
				'conditions' => $this->hover_conditions,
			]
		);

		$this->skin->add_control(
			'show_overlay',
			[
				'label' => esc_html__( 'Show Overlay Content on Hover', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'condition' => [
					'query_post_type' => 'portfolio',
				],
			]
		);

		$this->skin->add_control(
			'link_feature_image_to_permalink',
			[
				'label' => esc_html__( 'Link feature image to post', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => '',
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'condition' => [
					$this->skin->get_control_id( 'show_overlay' ) => 'yes',
					'query_post_type' => 'portfolio',
				],
			]
		);

		$this->skin->add_control(
			'show_quick_view',
			[
				'label' => esc_html__( 'Quick View', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
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
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
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
				'label' => esc_html__( 'Permalink', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
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
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
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

		$this->skin->end_injection();

		$this->skin->start_injection( [
			'at' => 'after',
			'of' => $this->skin->get_control_id( 'section_title' ),
		] );

		$this->skin->add_responsive_control(
			'post_title_max_width',
			[
				'label' => esc_html__( 'Max Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-title' => 'max-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->skin->end_injection();
	}

	protected function update_controls() {

		$this->skin->update_control(
			'show_image',
			[
				'default' => 'yes',
			]
		);
	}

	public function render_post( $instance ) {
		$this->skin = $instance;

		$show_image       = $this->skin->get_instance_value( 'show_image' );
		$hover_effect     = $this->skin->get_instance_value( 'post_hover_effect' );
		$post_classes     = [ 'raven-post' ];
		$content          = ! $this->has_overlay() || 'cover' !== $this->skin->get_id();
		$loaded_animation = $this->skin->get_instance_value( 'load_effect' );


		if ( 'yes' === $show_image ) {
			$post_classes[] = 'raven-post-inside';
		}

		if ( ! empty( $hover_effect ) ) {
			$post_classes[] = 'elementor-animation-' . $hover_effect;
		}

		if ( ! empty( $loaded_animation ) ) {
			$post_classes[] = 'raven-posts-carousel-load-effect raven-post-carousel-effect-' . $loaded_animation;
		}

		?>

		<div class="swiper-slide">
			<div class="raven-post-wrapper">
				<div class="<?php echo esc_attr( implode( ' ', $post_classes ) ); ?>">
					<?php
					$this->render_image();

					if ( $content ) {
						?>
						<div class="raven-post-content">
							<?php
							$this->render_ordered_content();
							$this->render_button();
							$this->render_author_spotlight();
							?>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}

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

		$featured_image_hover = $this->skin->get_instance_value( 'post_image_hover_effect' );

		$image_html = \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings );
		$image_src  = \Elementor\Group_Control_Image_Size::get_attachment_image_src( get_post_thumbnail_id(), 'image', $settings );

		if ( empty( $image_html ) ) {
			return;
		}

		$classes = [
			'raven-post-image',
			'raven-image-fit'
		];

		$html_tag = $this->disabled_overlay() ? 'a' : 'div';

		$zoom       = '';
		$attributes = '';

		if ( 'zoom-move' === $featured_image_hover ) {
			$zoom = "<div class='raven-posts-carousel-zoom-move-wrapper' style='background-image: url( $image_src )'></div>";
			$attributes = 'data-href=' . get_permalink();
		}

		if ( 'a' === $html_tag ) {
			$attributes .= ' href=' . get_permalink();
		}

		?>
		<div class="raven-post-image-wrap">
			<?php
			printf(
				'<%1$s class="%2$s" %3$s>%4$s %5$s<span class="raven-post-image-overlay">%6$s</span></%1$s>',
				$html_tag,
				implode( ' ', $classes ),
				esc_attr( $attributes ),
				$image_html,
				$zoom,
				$this->get_render_overlay()
			);
			?>
		</div>
		<?php
	}
}
