<?php
namespace JupiterX_Core\Raven\Modules\Post_Navigation\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Utils;
use Elementor\Plugin as Elementor;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Post_Navigation extends Base_Widget {
	public function get_name() {
		return 'raven-post-navigation';
	}

	public function get_title() {
		return esc_html__( 'Post Navigation', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-post-navigation';
	}

	protected function register_controls() {
		$this->register_section_content();
		$this->register_section_style_container();
		$this->register_section_style_post_title();
		$this->register_section_style_label();
		$this->register_section_style_next_prev_icon();
		$this->register_section_style_links_to_posts();
		$this->register_section_style_thumbnail();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_section_content() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'navigation_type',
			[
				'label' => esc_html__( 'Navigation Type', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'standard',
				'options' => [
					'standard' => esc_html__( 'Standard', 'jupiterx-core' ),
					'image-box' => esc_html__( 'Image Box', 'jupiterx-core' ),
				],
				'prefix_class' => 'raven-post-navigation-type-',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'next_only',
			[
				'label'        => esc_html__( 'Show Next Only', 'jupiterx-core' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off'    => esc_html__( 'Hide', 'jupiterx-core' ),
				'return_value' => 'enabled',
				'default'      => '',
				'prefix_class' => 'raven-post-navigation-next-only-',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'post_thumbnail',
			[
				'label'        => esc_html__( 'Post Thubmnail', 'jupiterx-core' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off'    => esc_html__( 'Hide', 'jupiterx-core' ),
				'return_value' => 'true',
				'default'      => 'true',
				'render_type' => 'template',
				'condition' => [
					'navigation_type' => 'standard',
				],
			]
		);

		$this->add_control(
			'featured_image',
			[
				'label'        => esc_html__( 'Featured Image', 'jupiterx-core' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off'    => esc_html__( 'Hide', 'jupiterx-core' ),
				'return_value' => 'true',
				'default'      => 'true',
				'render_type' => 'template',
				'condition' => [
					'navigation_type' => 'image-box',
				],
			]
		);

		$this->add_control(
			'post_title',
			[
				'label'        => esc_html__( 'Post Title', 'jupiterx-core' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off'    => esc_html__( 'Hide', 'jupiterx-core' ),
				'return_value' => 'true',
				'default'      => 'true',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'show_label',
			[
				'label'        => esc_html__( 'Label', 'jupiterx-core' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off'    => esc_html__( 'Hide', 'jupiterx-core' ),
				'return_value' => 'true',
				'default'      => 'true',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'prev_label',
			[
				'label' => esc_html__( 'Previous Label', 'jupiterx-core' ),
				'default' => esc_html__( 'Previous', 'jupiterx-core' ),
				'type' => 'text',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'show_label' => 'true',
					'next_only' => '',
				],
			]
		);

		$this->add_control(
			'next_label',
			[
				'label' => esc_html__( 'Next Label', 'jupiterx-core' ),
				'default' => esc_html__( 'Next', 'jupiterx-core' ),
				'type' => 'text',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'show_label' => 'true',
				],
			]
		);

		$this->add_control(
			'show_next_prev_icon',
			[
				'label'        => esc_html__( 'Next / Previous Icon', 'jupiterx-core' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off'    => esc_html__( 'Hide', 'jupiterx-core' ),
				'return_value' => 'true',
				'default'      => 'true',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'previous_icon',
			[
				'label'       => esc_html__( 'Previous Icon', 'jupiterx-core' ),
				'type'        => 'icons',
				'label_block' => false,
				'skin'        => 'inline',
				'default' => [
					'value' => 'fas fa-arrow-left',
					'library' => 'fa-solid',
				],
				'condition' => [
					'show_next_prev_icon' => 'true',
					'next_only' => '',
				],
			]
		);

		$this->add_control(
			'next_icon',
			[
				'label'       => esc_html__( 'Next Icon', 'jupiterx-core' ),
				'type'        => 'icons',
				'label_block' => false,
				'skin'        => 'inline',
				'default' => [
					'value' => 'fas fa-arrow-right',
					'library' => 'fa-solid',
				],
				'condition' => [
					'show_next_prev_icon' => 'true',
				],
			]
		);

		$this->add_control(
			'link_all_posts',
			[
				'label' => esc_html__( 'Link to All Posts', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '',
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'icon' => esc_html__( 'Icon', 'jupiterx-core' ),
					'text' => esc_html__( 'Text', 'jupiterx-core' ),
				],
				'prefix_class' => 'post-navigation-link-posts-',
				'condition' => [
					'navigation_type' => 'standard',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'link_all_posts_icon',
			[
				'label'       => esc_html__( 'Link to All Posts Icon', 'jupiterx-core' ),
				'type'        => 'icons',
				'label_block' => false,
				'skin'        => 'inline',
				'default'     => [
					'value' => 'fas fa-bars',
					'library' => 'fa-solid',
				],
				'condition' => [
					'link_all_posts' => 'icon',
					'navigation_type' => 'standard',
				],
			]
		);

		$this->add_control(
			'link_all_posts_text',
			[
				'label' => esc_html__( 'Link to All Posts Text', 'jupiterx-core' ),
				'type' => 'text',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'link_all_posts' => 'text',
					'navigation_type' => 'standard',
				],
			]
		);

		$this->add_control(
			'link_all_posts_url',
			[
				'label' => esc_html__( 'Link to All Posts URL', 'jupiterx-core' ),
				'type' => 'url',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'link_all_posts!' => '',
					'navigation_type' => 'standard',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'slide' => esc_html__( 'Slide', 'jupiterx-core' ),
				],
				'default' => '',
				'prefix_class' => 'post-navigation-hover-animation-',
				'condition' => [
					'navigation_type' => 'standard',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'hover_animation_featured_image',
			[
				'label' => esc_html__( 'Hover Animation', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'zoom' => esc_html__( 'Zoom', 'jupiterx-core' ),
					'zoom-move' => esc_html__( 'Zoom & Move', 'jupiterx-core' ),
				],
				'default' => '',
				'prefix_class' => 'post-navigation-hover-animation-',
				'frontend_available' => true,
				'condition' => [
					'navigation_type' => 'image-box',
					'featured_image' => 'true',
				],
				'render_type' => 'template',
			]
		);

		// Filter out post type without taxonomies.
		$post_type_options    = [];
		$post_type_taxonomies = [];

		foreach ( Utils::get_public_post_types() as $post_type => $post_type_label ) {
			$taxonomies = Utils::get_taxonomies( [ 'object_type' => $post_type ], false );
			if ( empty( $taxonomies ) ) {
				continue;
			}

			$post_type_options[ $post_type ]    = $post_type_label;
			$post_type_taxonomies[ $post_type ] = [];

			foreach ( $taxonomies as $taxonomy ) {
				$post_type_taxonomies[ $post_type ][ $taxonomy->name ] = $taxonomy->label;
			}
		}

		$this->add_control(
			'in_same_term',
			[
				'label' => esc_html__( 'In Same Term', 'jupiterx-core' ),
				'type' => 'select2',
				'options' => $post_type_options,
				'default' => '',
				'multiple' => true,
				'label_block' => true,
				'description' => esc_html__( 'Indicates whether next post must be within the same taxonomy term as the current post, this lets you set a taxonomy per each post type', 'jupiterx-core' ),
			]
		);

		foreach ( $post_type_options as $post_type => $post_type_label ) {
			$this->add_control(
				$post_type . '_taxonomy',
				[
					'label' => $post_type_label . ' ' . esc_html__( 'Taxonomy', 'jupiterx-core' ),
					'type' => 'select',
					'options' => $post_type_taxonomies[ $post_type ],
					'default' => '',
					'condition' => [
						'in_same_term' => $post_type,
					],
				]
			);
		}

		$this->end_controls_section();
	}

	protected function register_section_style_container() {
		$this->start_controls_section(
			'section_style_container',
			[
				'label' => esc_html__( 'Container', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'navigation_type' => 'image-box',
				],
			]
		);

		$this->add_responsive_control(
			'container_height',
			[
				'label' => esc_html__( 'Height', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 180,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 180,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 180,
				],
				'range' => [
					'px' => [
						'max' => 500,
					],
					'em' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.raven-post-navigation-type-image-box .raven-post-navigation a' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs( 'container_tabs' );

		$this->start_controls_tab(
			'container_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'container_overlay_normal',
			[
				'label' => esc_html__( 'Overlay Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-overlay' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'container_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'container_overlay_hover',
			[
				'label' => esc_html__( 'Overlay Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-overlay:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.post-navigation-hover-animation-zoom-move .raven-post-navigation-content-wrapper:hover .raven-post-navigation-overlay' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => '54',
					'right' => '36',
					'bottom' => '54',
					'left' => '36',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-raven-post-navigation .raven-post-navigation-next-previous-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}
	protected function register_section_style_post_title() {
		$this->start_controls_section(
			'section_style_post_title',
			[
				'label' => esc_html__( 'Post Title', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'post_title' => 'true',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'post_title_typography',
				'selector' => '{{WRAPPER}} .raven-post-navigation-title',
			]
		);

		$this->start_controls_tabs( 'post_title_tabs' );

		$this->start_controls_tab(
			'post_title_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'post_title_color_normal',
			[
				'label' => esc_html__( 'Title Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'post_title_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'post_title_color_hover',
			[
				'label' => esc_html__( 'Title Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-content-wrapper:hover .raven-post-navigation-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'post_title_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => '5',
					'right' => '10',
					'bottom' => '0',
					'left' => '10',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_section_style_label() {
		$this->start_controls_section(
			'section_style_label',
			[
				'label' => esc_html__( 'Label', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'show_label' => 'true',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .raven-post-navigation-label',
			]
		);

		$this->start_controls_tabs( 'label_tabs' );

		$this->start_controls_tab(
			'label_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'label_color_normal',
			[
				'label' => esc_html__( 'Title Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'label_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'label_color_hover',
			[
				'label' => esc_html__( 'Title Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-content-wrapper:hover .raven-post-navigation-label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'label_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => '0',
					'right' => '10',
					'bottom' => '0',
					'left' => '10',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_section_style_next_prev_icon() {
		$this->start_controls_section(
			'section_style_next_prev_icon',
			[
				'label' => esc_html__( 'Next / Previous Icon', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'show_next_prev_icon' => 'true',
				],
			]
		);

		$this->start_controls_tabs( 'next_prev_icon_tabs' );

		$this->start_controls_tab(
			'next_prev_icon_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'next_prev_icon_color_normal',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-arrow' => 'color: {{VALUE}};fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'next_prev_icon_width_normal',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 19,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 19,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 19,
				],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-arrow i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-post-navigation-arrow svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'next_prev_icon_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'next_prev_icon_color_hover',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-content-wrapper:hover .raven-post-navigation-arrow' => 'color: {{VALUE}};fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'next_prev_icon_width_hover',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-content-wrapper:hover .raven-post-navigation-arrow i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-post-navigation-content-wrapper:hover .raven-post-navigation-arrow svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'next_prev_icon_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => '0',
					'right' => '15',
					'bottom' => '0',
					'left' => '15',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-arrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_section_style_links_to_posts() {
		$this->start_controls_section(
			'section_style_link_to_posts',
			[
				'label' => esc_html__( 'Link to Posts', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'link_all_posts!' => '',
					'navigation_type' => 'standard',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'link_to_posts_typography',
				'selector' => '{{WRAPPER}} .raven-post-navigation-all-posts-text',
				'condition' => [
					'link_all_posts' => 'text',
				],
			]
		);

		$this->start_controls_tabs( 'link_to_posts_tabs' );

		$this->start_controls_tab(
			'link_to_posts_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'link_to_posts_color_normal',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-all-posts' => 'color: {{VALUE}};fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'link_to_posts_width_normal',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 19,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-all-posts-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-post-navigation-all-posts-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'link_all_posts' => 'icon',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_to_posts_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'link_to_posts_color_hover',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-all-posts:hover' => 'color: {{VALUE}};fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'link_to_posts_width_hover',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-all-posts-icon:hover i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-post-navigation-all-posts-icon:hover svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'link_all_posts' => 'icon',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'link_to_posts_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => '0',
					'right' => '5',
					'bottom' => '0',
					'left' => '5',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-all-posts' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_section_style_thumbnail() {
		$this->start_controls_section(
			'section_style_thumbnail',
			[
				'label' => esc_html__( 'Thumbnail', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'post_thumbnail' => 'true',
					'navigation_type' => 'standard',
				],
			]
		);

		$this->add_responsive_control(
			'thumbnail_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-thumbnail img' => 'min-width: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'thumbnail_border',
				'selector' => '{{WRAPPER}} .raven-post-navigation-thumbnail img',
			]
		);

		$this->add_control(
			'thumbnail_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-post-navigation-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .raven-post-navigation-thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function get_step_icon_render_string( $icon_data ) {
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

	protected function render_thumbnail( $settings, $type, $next_post, $prev_post ) {
		if ( empty( $settings['post_thumbnail'] ) ) {
			return;
		}

		$thumbnail = '';

		if (
			! empty( $prev_post ) &&
			! empty( get_the_post_thumbnail( $prev_post->ID, 'large' ) ) &&
			'previous' === $type
		) {
			$thumbnail = '<span class="raven-post-navigation-prev-thumbnail raven-post-navigation-thumbnail">' . get_the_post_thumbnail( $prev_post->ID, 'large' ) . '</span>';
		}

		if (
			! empty( $next_post ) &&
			! empty( get_the_post_thumbnail( $next_post->ID, 'large' ) ) &&
			'next' === $type
		) {
			$thumbnail = '<span class="raven-post-navigation-next-thumbnail raven-post-navigation-thumbnail">' . get_the_post_thumbnail( $next_post->ID, 'large' ) . '</span>';
		}

		return $thumbnail;
	}

	protected function render_featured_image( $settings, $type, $next_post, $prev_post ) {
		if ( empty( $settings['featured_image'] ) ) {
			return;
		}

		$featured_image = '';

		if (
			! empty( $prev_post ) &&
			! empty( get_the_post_thumbnail_url( $prev_post->ID, 'large' ) ) &&
			'previous' === $type
		) {
			$featured_image = '<div class="raven-post-navigation-prev-featured-image raven-post-navigation-featured-image" style="background-image: url(' . get_the_post_thumbnail_url( $prev_post->ID, 'large' ) . ')"></div>';
		}

		if (
			! empty( $next_post ) &&
			! empty( get_the_post_thumbnail_url( $next_post->ID, 'large' ) ) &&
			'next' === $type
		) {
			$featured_image = '<div class="raven-post-navigation-next-featured-image raven-post-navigation-featured-image" style="background-image: url(' . get_the_post_thumbnail_url( $next_post->ID, 'large' ) . ')"></div>';
		}

		return $featured_image;
	}

	protected function render_title( $settings, $type, $next_post, $prev_post ) {
		if ( empty( $settings['post_title'] ) ) {
			return;
		}

		$title = '';

		if (
			! empty( $prev_post ) &&
			! empty( get_the_title( $prev_post->ID ) ) &&
			'previous' === $type
		) {
			$title = '<span class="raven-post-navigation-prev-title raven-post-navigation-title">' . get_the_title( $prev_post->ID ) . '</span>';
		}

		if (
			! empty( $next_post ) &&
			! empty( get_the_title( $next_post->ID ) ) &&
			'next' === $type
		) {
			$title = '<span class="raven-post-navigation-next-title raven-post-navigation-title">' . get_the_title( $next_post->ID ) . '</span>';

		}

		return $title;
	}

	protected function render_label( $settings, $type, $next_post, $prev_post ) {
		if ( empty( $settings['show_label'] ) ) {
			return;
		}

		$label = '';

		if (
			! empty( $prev_post ) &&
			! empty( $settings['prev_label'] ) &&
			'previous' === $type
		) {
			$label = '<span class="raven-post-navigation-prev-label raven-post-navigation-label">' . esc_html( $settings['prev_label'] ) . '</span>';
		}

		if (
			! empty( $next_post ) &&
			! empty( $settings['next_label'] ) &&
			'next' === $type
		) {
			$label = '<span class="raven-post-navigation-next-label raven-post-navigation-label">' . esc_html( $settings['next_label'] ) . ' </span>';
		}

		return $label;
	}

	protected function render_arrow( $settings, $type, $next_post, $prev_post ) {
		if ( empty( $settings['show_next_prev_icon'] ) ) {
			return;
		}

		$arrow = '';

		if (
			! empty( $prev_post ) &&
			! empty( $settings['previous_icon'] ) &&
			'previous' === $type
		) {
			$arrow = '<span class="raven-post-navigation-prev-arrow raven-post-navigation-arrow">' . $this->get_step_icon_render_string( $settings['previous_icon'] ) . '</span>';
		}

		if (
			! empty( $next_post ) &&
			! empty( $settings['next_icon'] ) &&
			'next' === $type
		) {
			$arrow = '<span class="raven-post-navigation-next-arrow raven-post-navigation-arrow">' . $this->get_step_icon_render_string( $settings['next_icon'] ) . '</span>';
		}

		return $arrow;
	}

	protected function render_post( $type, $settings, $next_post, $prev_post ) {
		if ( 'standard' === $settings['navigation_type'] ) {
			return sprintf(
				'<div class="raven-post-navigation-%1$s-post raven-post-navigation-content-wrapper">%2$s<div class="raven-post-navigation-%1$s-post-content raven-post-navigation-next-previous-content">%3$s <div class="raven-post-navigation-title-wrapper">%4$s %5$s</div></div></div>',
				$type,
				$this->render_arrow( $settings, $type, $next_post, $prev_post ),
				$this->render_thumbnail( $settings, $type, $next_post, $prev_post ),
				$this->render_label( $settings, $type, $next_post, $prev_post ),
				$this->render_title( $settings, $type, $next_post, $prev_post )
			);
		}

		$featured_image = $this->render_featured_image( $settings, $type, $next_post, $prev_post );

		return sprintf(
			'<div class="raven-post-navigation-%1$s-post raven-post-navigation-content-wrapper">%2$s<div class="raven-post-navigation-overlay"><div class="raven-post-navigation-%1$s-post-content raven-post-navigation-next-previous-content">%3$s <div class="raven-post-navigation-title-wrapper">%4$s %5$s</div></div></div></div>',
			$type,
			$featured_image,
			$this->render_arrow( $settings, $type, $next_post, $prev_post ),
			$this->render_label( $settings, $type, $next_post, $prev_post ),
			$this->render_title( $settings, $type, $next_post, $prev_post )
		);
	}

	protected function render_all_posts( $settings ) {
		if ( empty( $settings['link_all_posts'] ) ) {
			return;
		}

		$all_posts = '';

		if ( 'icon' === $settings['link_all_posts'] && ! empty( $settings['link_all_posts_icon'] ) ) {
			$all_posts = '<span class="raven-post-navigation-all-posts raven-post-navigation-all-posts-icon">' . $this->get_step_icon_render_string( $settings['link_all_posts_icon'] ) . '</span>';
		}

		if ( 'text' === $settings['link_all_posts'] && ! empty( $settings['link_all_posts_text'] ) ) {
			$all_posts = '<span class="raven-post-navigation-all-posts raven-post-navigation-all-posts-text">' . esc_html( $settings['link_all_posts_text'] ) . '</span>';
		}

		$this->add_render_attribute( 'all-posts-wrapper', 'class', 'raven-post-navigation-all-posts-wrapper' );

		$all_posts_tag = 'div';

		if ( ! empty( $settings['link_all_posts_url']['url'] ) ) {
			$all_posts_tag = 'a';

			$this->add_render_attribute( 'all-posts-wrapper', 'href', esc_url( $settings['link_all_posts_url']['url'] ) );

			$this->render_link_properties( $this, $settings['link_all_posts_url'], 'all-posts-wrapper' );
		}

		?>
		<<?php echo $all_posts_tag . ' ' . $this->get_render_attribute_string( 'all-posts-wrapper' ); ?>>
			<?php echo $all_posts; ?>
		</<?php echo $all_posts_tag; ?>>
		<?php
	}

	protected function set_post_navigation_to_default() {
		if (
			! function_exists( 'jupiterx_add_smart_action' ) ||
			! function_exists( 'jupiterx_previous_post_link' ) ||
			! function_exists( 'jupiterx_next_post_link' )
		) {
			return;
		}

		jupiterx_add_smart_action( 'previous_post_link', 'jupiterx_previous_post_link', 10, 4 );
		jupiterx_add_smart_action( 'next_post_link', 'jupiterx_next_post_link', 10, 4 );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'wrapper',
			'class',
			[
				'raven-post-navigation',
			]
		);

		$in_same_term = false;
		$taxonomy     = 'category';
		$post_type    = get_post_type( get_queried_object_id() );

		if ( ! empty( $settings['in_same_term'] ) && is_array( $settings['in_same_term'] ) && in_array( $post_type, $settings['in_same_term'], true ) ) {
			if ( isset( $settings[ $post_type . '_taxonomy' ] ) ) {
				$in_same_term = true;
				$taxonomy     = $settings[ $post_type . '_taxonomy' ];
			}
		}

		$next_post = get_next_post( $in_same_term, '', $taxonomy );
		$prev_post = get_previous_post( $in_same_term, '', $taxonomy );

		if ( function_exists( 'jupiterx_elementor_modify_post_navigation' ) ) {
			jupiterx_elementor_modify_post_navigation();
		}
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php
				if ( empty( $settings['next_only'] ) ) {
					previous_post_link( '%link', $this->render_post( 'previous', $settings, $next_post, $prev_post ), $in_same_term, '', $taxonomy );
				}

				$this->render_all_posts( $settings );
				next_post_link( '%link', $this->render_post( 'next', $settings, $next_post, $prev_post ), $in_same_term, '', $taxonomy );
			?>
		</div>
		<?php
		$this->set_post_navigation_to_default();
	}
}
