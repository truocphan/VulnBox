<?php
/**
 * Masteriyo course categories list elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */

namespace Masteriyo\Addons\ElementorIntegration\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Masteriyo\Addons\ElementorIntegration\WidgetBase;
use Masteriyo\Taxonomy\Taxonomy;

defined( 'ABSPATH' ) || exit;

/**
 * Masteriyo course categories list elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */
class CourseCategoriesWidget extends WidgetBase {

	/**
	 * Get widget name.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_name() {
		return 'masteriyo-course-categories';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Course Categories', 'masteriyo' );
	}

	/**
	 * Get icon class for the widget.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'masteriyo-course-categories-widget-icon';
	}

	/**
	 * Register controls for configuring widget content.
	 *
	 * @since 1.6.12
	 */
	protected function register_content_controls() {
		$this->register_general_content_controls_section();
		$this->register_sorting_controls_section();
	}

	/**
	 * Register general content controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_general_content_controls_section() {
		$this->start_controls_section(
			'general',
			array(
				'label' => __( 'General', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'per_page',
			array(
				'label'   => __( 'No. of Categories', 'masteriyo' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
				'default' => 12,
			)
		);

		$this->add_control(
			'columns_per_row',
			array(
				'label'   => __( 'Columns', 'masteriyo' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 4,
				'step'    => 1,
				'default' => 3,
			)
		);

		$this->add_control(
			'include_sub_categories',
			array(
				'label'        => __( 'Include Sub-Categories', 'masteriyo' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Include', 'masteriyo' ),
				'label_off'    => __( 'Exclude', 'masteriyo' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'divider_1',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_on_off_switch_control(
			'show_thumbnail',
			__( 'Thumbnail', 'masteriyo' ),
			array(),
			array(
				'{{WRAPPER}} .masteriyo-category-card__image' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_category_details',
			__( 'Details', 'masteriyo' ),
			array(),
			array(
				'{{WRAPPER}} .masteriyo-category-card__detail' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_category_title',
			__( 'Title', 'masteriyo' ),
			array(
				'condition' => array(
					'show_category_details' => 'yes',
				),
			),
			array(
				'{{WRAPPER}} .masteriyo-category-card__title' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_courses_count',
			__( 'Courses Count', 'masteriyo' ),
			array(
				'condition' => array(
					'show_category_details' => 'yes',
				),
			),
			array(
				'{{WRAPPER}} .masteriyo-category-card__courses' => 'display: none !important;',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register sorting controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_sorting_controls_section() {
		$this->start_controls_section(
			'sorting',
			array(
				'label' => __( 'Sorting', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'order_by',
			array(
				'label'   => __( 'Order By', 'masteriyo' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'name'  => __( 'Title', 'masteriyo' ),
					'count' => __( 'Courses Count', 'masteriyo' ),
				),
				'default' => 'name',
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => __( 'Order', 'masteriyo' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'ASC'  => __( 'Ascending', 'masteriyo' ),
					'DESC' => __( 'Descending', 'masteriyo' ),
				),
				'default' => 'ASC',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register controls for customizing widget styles.
	 *
	 * @since 1.6.12
	 */
	protected function register_style_controls() {
		$this->register_layout_style_section();
		$this->register_card_styles_section();
		$this->register_thumbnail_styles_section();
		$this->register_title_styles_section();
		$this->register_courses_count_styles_section();
	}

	/**
	 * Register layout controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_layout_style_section() {
		$this->start_controls_section(
			'layout',
			array(
				'label' => __( 'Layout', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'columns_gap',
			array(
				'label'     => __( 'Columns Gap (px)', 'masteriyo' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-col' => 'padding-left: calc( {{VALUE}}px / 2 ) !important; padding-right: calc( {{VALUE}}px / 2 ) !important;',
					'{{WRAPPER}} .masteriyo-course-categories' => 'margin-left: calc( -{{VALUE}}px / 2 ) !important; margin-right: calc( -{{VALUE}}px / 2 ) !important;',
				),
			)
		);

		$this->add_responsive_control(
			'rows_gap',
			array(
				'label'     => __( 'Rows Gap (px)', 'masteriyo' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-col' => 'padding-top: calc( {{VALUE}}px / 2 ) !important; padding-bottom: calc( {{VALUE}}px / 2 ) !important;',
					'{{WRAPPER}} .masteriyo-course-categories' => 'margin-top: calc( -{{VALUE}}px / 2 ) !important; margin-bottom: calc( -{{VALUE}}px / 2 ) !important;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register card style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_card_styles_section() {
		$this->start_controls_section(
			'card_style',
			array(
				'label' => __( 'Card', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'card_style_tabs' );

		// Normal state styles.
		$this->start_controls_tab(
			'card_normal_state_style_tab',
			array(
				'label' => __( 'Normal', 'masteriyo' ),
			)
		);

		$this->add_control(
			'card_background_color',
			array(
				'label'     => __( 'Background Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-category-card' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_border_styles_popover_toggle',
			array(
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label'        => esc_html__( 'Border', 'masteriyo' ),
				'label_off'    => esc_html__( 'Default', 'masteriyo' ),
				'label_on'     => esc_html__( 'Custom', 'masteriyo' ),
				'return_value' => 'yes',
			)
		);

		$this->start_popover();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border_styles',
				'label'    => __( 'Border', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-category-card',
			)
		);

		$this->add_control(
			'card_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-category-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_box_shadow',
				'label'    => __( 'Box Shadow', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-category-card',
			)
		);

		$this->end_controls_tab();

		// Hover state styles.
		$this->start_controls_tab(
			'card_hover_state_style_tab',
			array(
				'label' => __( 'Hover', 'masteriyo' ),
			)
		);

		$this->add_control(
			'card_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-category-card:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_hover_border_styles_popover_toggle',
			array(
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label'        => esc_html__( 'Border', 'masteriyo' ),
				'label_off'    => esc_html__( 'Default', 'masteriyo' ),
				'label_on'     => esc_html__( 'Custom', 'masteriyo' ),
				'return_value' => 'yes',
			)
		);

		$this->start_popover();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_hover_border_styles',
				'label'    => __( 'Border', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-category-card:hover',
			)
		);

		$this->add_control(
			'card_hover_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-category-card:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_hover_box_shadow',
				'label'    => __( 'Box Shadow', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-category-card:hover',
			)
		);

		$this->add_control(
			'card_hover_animation',
			array(
				'label' => __( 'Hover Animation', 'masteriyo' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	/**
	 * Register thumbnail style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_thumbnail_styles_section() {
		$this->start_controls_section(
			'thumbnail_styling_controls',
			array(
				'label' => __( 'Thumbnail', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs(
			'thumbnail_styling_tabs'
		);

		// Normal state styles.
		$this->start_controls_tab(
			'thumbnail_normal_state_styling_tab',
			array(
				'label' => __( 'Normal', 'masteriyo' ),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'label'    => __( 'CSS Filters', 'masteriyo' ),
				'name'     => 'thumbnail_css_filter',
				'selector' => '{{WRAPPER}} .masteriyo-category-card__image img',
			)
		);

		$this->end_controls_tab();

		// Normal state styles.
		$this->start_controls_tab(
			'thumbnail_hover_state_styling_tab',
			array(
				'label' => __( 'Hover', 'masteriyo' ),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'label'    => __( 'CSS Filters', 'masteriyo' ),
				'name'     => 'thumbnail_hover_css_filter',
				'selector' => '{{WRAPPER}} .masteriyo-category-card .masteriyo-category-card__image img:hover',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	/**
	 * Register category title style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_title_styles_section() {
		$this->start_controls_section(
			'category_title_section',
			array(
				'label' => __( 'Title', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'category_title_text_align',
			array(
				'label'     => esc_html__( 'Alignment', 'masteriyo' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'masteriyo' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'masteriyo' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'masteriyo' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-category-card__title' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'category_title_typography',
				'selector' => '{{WRAPPER}} .masteriyo-category-card__title.masteriyo-category-card__title.masteriyo-category-card__title.masteriyo-category-card__title',
			)
		);

		$this->add_control(
			'category_title_text_color',
			array(
				'label'     => __( 'Text Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-category-card__title a' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'category_title_background_color',
			array(
				'label'     => __( 'Background Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-category-card__title' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'popover-toggle_category_title_border',
			array(
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label'        => esc_html__( 'Border', 'masteriyo' ),
				'label_off'    => esc_html__( 'Default', 'masteriyo' ),
				'label_on'     => esc_html__( 'Custom', 'masteriyo' ),
				'return_value' => 'yes',
			)
		);

		$this->start_popover();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'category_title_border',
				'label'    => __( 'Border', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-category-card__title',
			)
		);

		$this->add_control(
			'category_title_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-category-card__title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_popover();

		$this->add_control(
			'category_title_padding',
			array(
				'label'      => __( 'Padding', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-category-card__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'category_title_margin',
			array(
				'label'      => __( 'Margin', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-category-card__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Register courses count style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_courses_count_styles_section() {
		$this->start_controls_section(
			'courses_count_section',
			array(
				'label' => __( 'Courses Count', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'courses_count_text_align',
			array(
				'label'     => esc_html__( 'Alignment', 'masteriyo' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'masteriyo' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'masteriyo' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'masteriyo' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-category-card__courses' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'courses_count_typography',
				'selector' => '{{WRAPPER}} .masteriyo-category-card__courses',
			)
		);

		$this->add_control(
			'courses_count_text_color',
			array(
				'label'     => __( 'Text Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-category-card__courses' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'courses_count_background_color',
			array(
				'label'     => __( 'Background Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-category-card__courses' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'courses_count_border_styles_popover',
			array(
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label'        => esc_html__( 'Border', 'masteriyo' ),
				'label_off'    => esc_html__( 'Default', 'masteriyo' ),
				'label_on'     => esc_html__( 'Custom', 'masteriyo' ),
				'return_value' => 'yes',
			)
		);

		$this->start_popover();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'courses_count_border_styles',
				'label'    => __( 'Border', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-category-card__courses',
			)
		);

		$this->add_control(
			'courses_count_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-category-card__courses' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_popover();

		$this->add_control(
			'courses_count_padding',
			array(
				'label'      => __( 'Padding', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-category-card__courses' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'courses_count_margin',
			array(
				'label'      => __( 'Margin', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-category-card__courses' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render HTML for frontend.
	 *
	 * @since 1.6.12
	 */
	protected function render() {
		$settings               = $this->get_settings();
		$limit                  = max( absint( $settings['per_page'] ), 1 );
		$columns                = max( absint( $settings['columns_per_row'] ), 1 );
		$attrs                  = array();
		$include_sub_categories = masteriyo_string_to_bool( $settings['include_sub_categories'] );
		$hide_courses_count     = ! masteriyo_string_to_bool( $settings['show_courses_count'] );
		$args                   = array(
			'taxonomy'   => Taxonomy::COURSE_CATEGORY,
			'order'      => masteriyo_array_get( $settings, 'order', 'ASC' ),
			'orderby'    => masteriyo_array_get( $settings, 'order_by', 'name' ),
			'number'     => $limit,
			'hide_empty' => false,
		);

		if ( ! masteriyo_string_to_bool( $include_sub_categories ) ) {
			$args['parent'] = 0;
		}

		$query      = new \WP_Term_Query();
		$result     = $query->query( $args );
		$categories = array_filter( array_map( 'masteriyo_get_course_cat', $result ) );

		$attrs['count']                  = $limit;
		$attrs['columns']                = $columns;
		$attrs['categories']             = $categories;
		$attrs['hide_courses_count']     = $hide_courses_count;
		$attrs['include_sub_categories'] = $include_sub_categories;

		if ( ! empty( $settings['card_hover_animation'] ) ) {
			$attrs['card_class'] = sprintf( 'elementor-animation-%s', $settings['card_hover_animation'] );
		}

		printf( '<div class="masteriyo">' );
		masteriyo_get_template( 'shortcodes/course-categories/list.php', $attrs );
		echo '</div>';
	}
}
