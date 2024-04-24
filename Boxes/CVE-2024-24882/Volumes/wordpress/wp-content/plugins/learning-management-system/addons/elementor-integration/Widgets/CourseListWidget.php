<?php
/**
 * Masteriyo course list elementor widget class.
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
use Elementor\Group_Control_Text_Shadow;
use Masteriyo\Addons\ElementorIntegration\Helper;
use Masteriyo\Addons\ElementorIntegration\WidgetBase;
use Masteriyo\Enums\PostStatus;
use Masteriyo\PostType\PostType;
use Masteriyo\Taxonomy\Taxonomy;

defined( 'ABSPATH' ) || exit;

/**
 * Masteriyo course list elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */
class CourseListWidget extends WidgetBase {

	/**
	 * Get widget name.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_name() {
		return 'masteriyo-course-list';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Course List', 'masteriyo' );
	}

	/**
	 * Get icon class for the widget.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'masteriyo-course-list-widget-icon';
	}

	/**
	 * Register controls for configuring widget content.
	 *
	 * @since 1.6.12
	 */
	protected function register_content_controls() {
		$this->register_general_content_controls_section();
		$this->register_filter_controls_section();
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
			'source',
			array(
				'label'    => __( 'Source', 'masteriyo' ),
				'type'     => Controls_Manager::SELECT,
				'multiple' => true,
				'options'  => array(
					''        => 'All Courses',
					'related' => 'Related Courses',
				),
			)
		);

		$this->add_control(
			'per_page',
			array(
				'label'   => __( 'No. of Courses', 'masteriyo' ),
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
				'{{WRAPPER}} .masteriyo-course--img-wrap' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_difficulty_badge',
			__( 'Difficulty Badge', 'masteriyo' ),
			array(
				'condition' => array(
					'show_thumbnail' => 'yes',
				),
			),
			array(
				'{{WRAPPER}} .difficulty-badge' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_categories',
			__( 'Categories', 'masteriyo' ),
			array(),
			array(
				'{{WRAPPER}} .masteriyo-course--content__category' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_course_title',
			__( 'Course Title', 'masteriyo' ),
			array(),
			array(
				'{{WRAPPER}} .masteriyo-course--content__title a' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_author',
			__( 'Author', 'masteriyo' ),
			array(),
			array(
				'{{WRAPPER}} .masteriyo-course-author' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_author_avatar',
			__( 'Avatar of Author', 'masteriyo' ),
			array(
				'condition' => array(
					'show_author' => 'yes',
				),
			),
			array(
				'{{WRAPPER}} .masteriyo-course-author img' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_author_name',
			__( 'Name of Author', 'masteriyo' ),
			array(
				'condition' => array(
					'show_author' => 'yes',
				),
			),
			array(
				'{{WRAPPER}} .masteriyo-course-author .masteriyo-course-author--name' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_rating',
			__( 'Rating', 'masteriyo' ),
			array(),
			array(
				'{{WRAPPER}} .masteriyo-rating' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_course_description',
			__( 'Highlights / Description', 'masteriyo' ),
			array(),
			array(
				'{{WRAPPER}} .masteriyo-course--content__description' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_metadata',
			__( 'Meta Data', 'masteriyo' ),
			array(
				'description' => __( 'Show/hide the section containing information on number of students, course hours etc.', 'masteriyo' ),
			),
			array(
				'{{WRAPPER}} .masteriyo-course--content__stats' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_course_duration',
			__( 'Course Duration', 'masteriyo' ),
			array(
				'condition' => array(
					'show_metadata' => 'yes',
				),
			),
			array(
				'{{WRAPPER}} .masteriyo-course-stats-duration' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_students_count',
			__( 'Students Count', 'masteriyo' ),
			array(
				'condition' => array(
					'show_metadata' => 'yes',
				),
			),
			array(
				'{{WRAPPER}} .masteriyo-course-stats-students' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_lessons_count',
			__( 'Lessons Count', 'masteriyo' ),
			array(
				'condition' => array(
					'show_metadata' => 'yes',
				),
			),
			array(
				'{{WRAPPER}} .masteriyo-course-stats-curriculum' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_card_footer',
			__( 'Footer', 'masteriyo' ),
			array(),
			array(
				'{{WRAPPER}} .masteriyo-course-card-footer' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_price',
			__( 'Price', 'masteriyo' ),
			array(
				'condition' => array(
					'show_card_footer' => 'yes',
				),
			),
			array(
				'{{WRAPPER}} .masteriyo-course-price' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_enroll_button',
			__( 'Enroll Button', 'masteriyo' ),
			array(
				'condition' => array(
					'show_card_footer' => 'yes',
				),
			),
			array(
				'{{WRAPPER}} .masteriyo-enroll-btn' => 'display: none !important;',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register filter controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_filter_controls_section() {
		$course_categories = $this->get_categories_options();
		$instructors       = $this->get_instructors_options();

		$this->start_controls_section(
			'filter',
			array(
				'label' => __( 'Filter', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->start_controls_tabs( 'filter_params' );

		// Include Tab
		$this->start_controls_tab(
			'parameter_inclusion_tab',
			array(
				'label' => __( 'Include', 'masteriyo' ),
			)
		);

		$this->add_control(
			'include_categories',
			array(
				'label'    => __( 'Categories', 'masteriyo' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'options'  => $course_categories,
				'default'  => array(),
			)
		);

		$this->add_control(
			'include_instructors',
			array(
				'label'    => __( 'Instructors', 'masteriyo' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'options'  => $instructors,
				'default'  => array(),
			)
		);

		$this->end_controls_tab();

		// Exclude Tab
		$this->start_controls_tab(
			'parameter_exclusion_tab',
			array(
				'label' => __( 'Exclude', 'masteriyo' ),
			)
		);

		$this->add_control(
			'exclude_categories',
			array(
				'label'    => __( 'Categories', 'masteriyo' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'options'  => $course_categories,
				'default'  => array(),
			)
		);

		$this->add_control(
			'exclude_instructors',
			array(
				'label'    => __( 'Instructors', 'masteriyo' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'options'  => $instructors,
				'default'  => array(),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
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
					'date'   => __( 'Date', 'masteriyo' ),
					'title'  => __( 'Title', 'masteriyo' ),
					'price'  => __( 'Price', 'masteriyo' ),
					'rating' => __( 'Rating', 'masteriyo' ),
				),
				'default' => 'date',
			)
		);

		$this->add_control(
			'sorting_order',
			array(
				'label'   => __( 'Order', 'masteriyo' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'ASC'  => __( 'Ascending', 'masteriyo' ),
					'DESC' => __( 'Descending', 'masteriyo' ),
				),
				'default' => 'DESC',
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
		$this->register_difficulty_badge_styles_section();
		$this->register_categories_styles_section();
		$this->register_title_styles_section();
		$this->register_author_styles_section();
		$this->register_author_avatar_styles_section();
		$this->register_author_name_styles_section();
		$this->register_rating_styles_section();
		$this->register_description_styles_section();
		$this->register_metadata_styles_section();
		$this->register_footer_styles_section();
		$this->register_price_styles_section();
		$this->register_enroll_button_styles_section();
	}

	/**
	 * Register layout style controls section.
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
					'{{WRAPPER}} .masteriyo-courses-wrapper' => 'margin-left: calc( -{{VALUE}}px / 2 ) !important; margin-right: calc( -{{VALUE}}px / 2 ) !important;',
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
					'{{WRAPPER}} .masteriyo-courses-wrapper' => 'margin-top: calc( -{{VALUE}}px / 2 ) !important; margin-bottom: calc( -{{VALUE}}px / 2 ) !important;',
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
					'{{WRAPPER}} .masteriyo-course--card' => 'background-color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .masteriyo-course--card',
			)
		);

		$this->add_control(
			'card_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_box_shadow',
				'label'    => __( 'Box Shadow', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-course--card',
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
					'{{WRAPPER}} .masteriyo-course--card:hover' => 'background-color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .masteriyo-course--card:hover',
			)
		);

		$this->add_control(
			'card_hover_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--card:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_hover_box_shadow',
				'label'    => __( 'Box Shadow', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-course--card:hover',
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
				'selector' => '{{WRAPPER}} .masteriyo-course--img-wrap img',
			)
		);

		$this->end_controls_tab();

		// Hover state styles.
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
				'selector' => '{{WRAPPER}} .masteriyo-course--card .masteriyo-course--img-wrap img:hover',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	/**
	 * Register difficulty badge style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_difficulty_badge_styles_section() {
		$difficulties = $this->get_all_difficulties();

		$this->start_controls_section(
			'difficulty_badge_controls_section',
			array(
				'label' => __( 'Difficulty Badge', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'difficulty_badge_typography',
				'selector' => '{{WRAPPER}} .difficulty-badge .masteriyo-badge',
			)
		);

		$this->add_control(
			'difficulty_badge_text_color_popover',
			array(
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label'        => esc_html__( 'Text Color', 'masteriyo' ),
				'label_off'    => esc_html__( 'Default', 'masteriyo' ),
				'label_on'     => esc_html__( 'Custom', 'masteriyo' ),
				'return_value' => 'yes',
			)
		);
		$this->start_popover();

		foreach ( $difficulties as $difficulty ) {
			$this->add_control(
				'difficulty_' . $difficulty->get_slug() . '_level_badge_text_color',
				array(
					'label'     => $difficulty->get_name(),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .difficulty-badge[data-id="' . $difficulty->get_id() . '"] .masteriyo-badge' => 'color:{{VALUE}};',
					),
				)
			);
		}
		$this->end_popover();

		$this->add_control(
			'difficulty_badge_background_color_popover',
			array(
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label'        => esc_html__( 'Background Color', 'masteriyo' ),
				'label_off'    => esc_html__( 'Default', 'masteriyo' ),
				'label_on'     => esc_html__( 'Custom', 'masteriyo' ),
				'return_value' => 'yes',
			)
		);
		$this->start_popover();

		foreach ( $difficulties as $difficulty ) {
			$this->add_control(
				'difficulty_' . $difficulty->get_slug() . '_level_badge_background_color',
				array(
					'label'     => $difficulty->get_name(),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .difficulty-badge[data-id="' . $difficulty->get_id() . '"] .masteriyo-badge' => 'background-color:{{VALUE}};',
					),
				)
			);
		}
		$this->end_popover();

		$this->add_control(
			'difficulty_badge_padding',
			array(
				'label'      => __( 'Padding', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .difficulty-badge .masteriyo-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'difficulty_badge_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .difficulty-badge .masteriyo-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'difficulty_badge_top_position',
			array(
				'label'      => __( 'Top', 'masteriyo' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .difficulty-badge' => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'difficulty_badge_left_position',
			array(
				'label'      => __( 'Left', 'masteriyo' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .difficulty-badge' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Register categories style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_categories_styles_section() {
		$this->start_controls_section(
			'categories_styles_section',
			array(
				'label' => __( 'Categories', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'categories_typography',
				'selector' => '{{WRAPPER}} .masteriyo-course--content__category .masteriyo-course--content__category-items',
			)
		);
		$this->add_control(
			'categories_text_color',
			array(
				'label'     => __( 'Text Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course--content__category .masteriyo-course--content__category-items' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'categories_spacing',
			array(
				'label'      => __( 'Spacing', 'masteriyo' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 2,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__category-items' => 'margin: 0 !important;',
					'{{WRAPPER}} .masteriyo-course--content__category .masteriyo-course--content__category-items:not(:first-child)' => 'margin-left: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->start_controls_tabs( 'categories_styles_tabs' );
		$this->start_controls_tab(
			'categories_container_styles_tab',
			array(
				'label' => __( 'Container', 'masteriyo' ),
			)
		);

		$this->add_control(
			'categories_container_background_color',
			array(
				'label'     => __( 'Background Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course--content__category' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'categories_container_border_styles_popover',
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
				'name'     => 'categories_container_border',
				'label'    => __( 'Border', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-course--content__category',
			)
		);
		$this->add_control(
			'categories_container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__category' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);
		$this->end_popover();
		$this->add_control(
			'categories_container_padding',
			array(
				'label'      => __( 'Padding', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);
		$this->add_control(
			'categories_container_margin',
			array(
				'label'      => __( 'Margin', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'categories_item_styles_tab',
			array(
				'label' => __( 'Item', 'masteriyo' ),
			)
		);

		$this->add_control(
			'categories_item_background_color',
			array(
				'label'     => __( 'Background Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course--content__category-items' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'categories_item_border_styles_popover',
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
				'name'     => 'categories_item_border',
				'label'    => __( 'Border', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-course--content__category-items',
			)
		);
		$this->add_control(
			'categories_item_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__category-items' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);
		$this->end_popover();
		$this->add_control(
			'categories_item_padding',
			array(
				'label'      => __( 'Padding', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__category-items' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register course title style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_title_styles_section() {
		$this->start_controls_section(
			'course_title_section',
			array(
				'label' => __( 'Title', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'course_title_text_align',
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
					'{{WRAPPER}} .masteriyo-course--content__title' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'course_title_typography',
				'selector' => '{{WRAPPER}} .masteriyo-course--content__title.masteriyo-course--content__title',
			)
		);

		$this->add_control(
			'course_title_text_color',
			array(
				'label'     => __( 'Text Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course--content__title a' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'course_title_background_color',
			array(
				'label'     => __( 'Background Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course--content__title' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'popover-toggle_course_title_border',
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
				'name'     => 'course_title_border',
				'label'    => __( 'Border', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-course--content__title',
			)
		);

		$this->add_control(
			'course_title_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_popover();

		$this->add_control(
			'course_title_padding',
			array(
				'label'      => __( 'Padding', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'course_title_margin',
			array(
				'label'      => __( 'Margin', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Register author style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_author_styles_section() {
		$this->start_controls_section(
			'author_styles',
			array(
				'label' => __( 'Author', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'author_background_color',
			array(
				'label'     => __( 'Background Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course-author' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'author_border_styles_popover_toggle',
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
				'name'     => 'author_border_styles',
				'label'    => __( 'Border', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-course-author',
			)
		);

		$this->add_control(
			'author_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course-author' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_popover();

		$this->add_responsive_control(
			'author_padding',
			array(
				'label'      => __( 'Padding', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course-author' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'author_margin',
			array(
				'label'      => __( 'Margin', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course-author' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register author avatar style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_author_avatar_styles_section() {
		$this->start_controls_section(
			'author_avatar_styles',
			array(
				'label' => __( 'Author Avatar', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'author_avatar_size',
			array(
				'label'      => __( 'Size', 'masteriyo' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 15,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course-author img' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'author_avatar_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course-author img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register author name style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_author_name_styles_section() {
		$this->start_controls_section(
			'author_name_section',
			array(
				'label' => __( 'Author Name', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'author_name_typography',
				'selector' => '{{WRAPPER}} .masteriyo-course-author--name',
			)
		);

		$this->add_control(
			'author_name_text_color',
			array(
				'label'     => __( 'Text Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course-author--name' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'author_name_background_color',
			array(
				'label'     => __( 'Background Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course-author--name' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'author_name_border_styles_popover',
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
				'name'     => 'author_name_border_styles',
				'label'    => __( 'Border', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-course-author--name',
			)
		);

		$this->add_control(
			'author_name_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course-author--name' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_popover();

		$this->add_control(
			'author_name_padding',
			array(
				'label'      => __( 'Padding', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course-author--name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'author_name_margin',
			array(
				'label'      => __( 'Margin', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course-author--name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register rating style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_rating_styles_section() {
		$this->start_controls_section(
			'rating_styles',
			array(
				'label' => __( 'Rating', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rating_background_color',
			array(
				'label'     => __( 'Background Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course--content__rt .masteriyo-rating' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'rating_border_styles_popover',
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
				'name'     => 'rating_border_styles',
				'label'    => __( 'Border', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-course--content__rt .masteriyo-rating',
			)
		);

		$this->add_control(
			'rating_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__rt .masteriyo-rating' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_popover();

		$this->add_control(
			'rating_padding',
			array(
				'label'      => __( 'Padding', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__rt .masteriyo-rating' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'rating_margin',
			array(
				'label'      => __( 'Margin', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__rt .masteriyo-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'rating_text_section_options_divider____2',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'rating_star_section_options',
			array(
				'label' => __( 'Star', 'masteriyo' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'star_color',
			array(
				'label'     => __( 'Star Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-rating svg' => 'fill: {{VALUE}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'star_size',
			array(
				'label'      => __( 'Star Size', 'masteriyo' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-rating svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'star_gap',
			array(
				'label'      => __( 'Gap', 'masteriyo' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-rating svg:not(:first-child)' => 'margin-left: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'rating_text_section_options_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'rating_text_section_options',
			array(
				'label' => __( 'Text', 'masteriyo' ),
				'type'  => Controls_Manager::HEADING,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'course_carouse_rating__typo',
				'label'    => __( 'Typography', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-rating',
			)
		);

		$this->add_control(
			'rating_text_color',
			array(
				'label'     => __( 'Text Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-rating' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Register description style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_description_styles_section() {
		$this->start_controls_section(
			'course_highlights_section',
			array(
				'label' => __( 'Highlights / Description', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .masteriyo-course--content__description',
			)
		);

		$this->add_control(
			'description_text_color',
			array(
				'label'     => __( 'Text Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course--content__description' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'description_background_color',
			array(
				'label'     => __( 'Background Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course--content__description' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'highlights_gap',
			array(
				'label'      => __( 'Highlights Gap', 'masteriyo' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__description ul li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'description_border_styles_popover',
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
				'name'     => 'description_border_styles',
				'label'    => __( 'Border', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-course--content__description',
			)
		);

		$this->add_control(
			'description_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__description' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_popover();

		$this->add_control(
			'description_padding',
			array(
				'label'      => __( 'Padding', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'description_margin',
			array(
				'label'      => __( 'Margin', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register metadata style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_metadata_styles_section() {
		$this->start_controls_section(
			'course_metadata_section',
			array(
				'label' => __( 'Metadata', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'metadata_typography',
				'selector' => '{{WRAPPER}} .masteriyo-course--content__stats span',
			)
		);

		$this->add_responsive_control(
			'metadata_icon_size',
			array(
				'label'      => __( 'Icon Size', 'masteriyo' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__stats svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'metadata_text_color',
			array(
				'label'     => __( 'Text Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course--content__stats svg' => 'fill: {{VALUE}} !important;',
					'{{WRAPPER}} .masteriyo-course--content__stats span' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'metadata_background_color',
			array(
				'label'     => __( 'Background Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course--content__stats' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'popover-toggle_metadata_border',
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
				'name'     => 'metadata_border',
				'label'    => __( 'Border', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-course--content__stats',
			)
		);

		$this->add_control(
			'metadata_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__stats' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_popover();

		$this->add_control(
			'metadata_padding',
			array(
				'label'      => __( 'Padding', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__stats' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'metadata_margin',
			array(
				'label'      => __( 'Margin', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course--content__stats' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register price style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_price_styles_section() {
		$this->start_controls_section(
			'price_styles',
			array(
				'label' => __( 'Price', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .masteriyo-course-price .current-amount',
			)
		);

		$this->add_control(
			'price_text_color',
			array(
				'label'     => __( 'Text Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course-price .current-amount' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'price_background_color',
			array(
				'label'     => __( 'Background Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course-price' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'price_border_styles_popover',
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
				'name'     => 'price_border_styles',
				'label'    => __( 'Border', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-course-price',
			)
		);

		$this->add_control(
			'price_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_popover();

		$this->add_control(
			'price_padding',
			array(
				'label'      => __( 'Padding', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'price_margin',
			array(
				'label'      => __( 'Margin', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register enroll button style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_enroll_button_styles_section() {
		$this->start_controls_section(
			'enroll_button_styles',
			array(
				'label' => __( 'Enroll Button', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'enroll_button_typography',
				'selector' => '{{WRAPPER}} .masteriyo-enroll-btn',
			)
		);

		$this->add_control(
			'enroll_button_padding',
			array(
				'label'      => __( 'Padding', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-enroll-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'enroll_button_margin',
			array(
				'label'      => __( 'Margin', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-enroll-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'enroll_button_styles_tabs_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->start_controls_tabs( 'enroll_button_states' );

		// Normal state styles.
		$this->start_controls_tab(
			'enroll_button_normal_state_style_tab',
			array(
				'label' => __( 'Normal', 'masteriyo' ),
			)
		);

		$this->add_control(
			'enroll_button_text_color',
			array(
				'label'     => __( 'Text Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-enroll-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'enroll_button_background_color',
			array(
				'label'     => __( 'Background Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-enroll-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'enroll_button_border_styles_popover',
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
				'name'     => 'enroll_button_border_styles',
				'label'    => __( 'Border', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-enroll-btn',
			)
		);

		$this->add_control(
			'enroll_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-enroll-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'enroll_button_text_shadow',
				'label'    => __( 'Text Shadow', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-enroll-btn',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'enroll_button_box_shadow',
				'label'    => __( 'Box Shadow', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-enroll-btn',
			)
		);

		$this->end_controls_tab();

		// Hover state styles.
		$this->start_controls_tab(
			'enroll_button_hover_state_style_tab',
			array(
				'label' => __( 'Hover', 'masteriyo' ),
			)
		);

		$this->add_control(
			'enroll_button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-enroll-btn:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'enroll_button_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-enroll-btn:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'enroll_button_hover_border_styles_popover',
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
				'name'     => 'enroll_button_hover_border_styles',
				'label'    => __( 'Border', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-enroll-btn:hover',
			)
		);

		$this->add_control(
			'enroll_button_hover_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-enroll-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'enroll_button_hover_text_shadow',
				'label'    => __( 'Text Shadow', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-enroll-btn:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'enroll_button_hover_box_shadow',
				'label'    => __( 'Box Shadow', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-enroll-btn:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register footer style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_footer_styles_section() {
		$this->start_controls_section(
			'footer_styles',
			array(
				'label' => __( 'Footer', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'footer_background_color',
			array(
				'label'     => __( 'Background Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course-card-footer' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'footer_border_styles_popover_toggle',
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
				'name'     => 'footer_border_styles',
				'label'    => __( 'Border', 'masteriyo' ),
				'selector' => '{{WRAPPER}} .masteriyo-course-card-footer',
			)
		);

		$this->add_control(
			'footer_border_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course-card-footer' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_popover();

		$this->add_responsive_control(
			'footer_padding',
			array(
				'label'      => __( 'Padding', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course-card-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'footer_margin',
			array(
				'label'      => __( 'Margin', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course-card-footer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		$course                  = isset( $GLOBALS['course'] ) ? $GLOBALS['course'] : null;
		$settings                = $this->get_settings();
		$is_related_course_query = isset( $settings['source'] ) && 'related' === $settings['source'];

		if ( $is_related_course_query ) {
			if ( Helper::is_elementor_editor() || Helper::is_elementor_preview() ) {
				$course = Helper::get_elementor_preview_course();
			}

			if ( empty( $course ) ) {
				return;
			}
		}

		$limit     = max( absint( $settings['per_page'] ), 1 );
		$columns   = max( absint( $settings['columns_per_row'] ), 1 );
		$tax_query = array(
			'relation' => 'AND',
		);

		if ( ! empty( $settings['include_categories'] ) ) {
			$tax_query[] = array(
				'taxonomy' => Taxonomy::COURSE_CATEGORY,
				'terms'    => $settings['include_categories'],
				'field'    => 'term_id',
				'operator' => 'IN',
			);
		}

		if ( ! empty( $settings['exclude_categories'] ) ) {
			$tax_query[] = array(
				'taxonomy' => Taxonomy::COURSE_CATEGORY,
				'terms'    => $settings['exclude_categories'],
				'field'    => 'term_id',
				'operator' => 'NOT IN',
			);
		}

		if ( $is_related_course_query ) {
			$tax_query[] = array(
				'taxonomy' => Taxonomy::COURSE_CATEGORY,
				'terms'    => $course ? $course->get_category_ids() : array(),
			);
		}

		$args = array(
			'post_type'      => PostType::COURSE,
			'status'         => array( PostStatus::PUBLISH ),
			'posts_per_page' => $limit,
			'order'          => 'DESC',
			'orderby'        => 'date',
			'tax_query'      => $tax_query,
			'post__not_in'   => $is_related_course_query ? array( $course->get_id() ) : array(),
		);

		if ( ! empty( $settings['include_instructors'] ) ) {
			$args['author__in'] = $settings['include_instructors'];
		}

		if ( ! empty( $settings['exclude_instructors'] ) ) {
			$args['author__not_in'] = $settings['exclude_instructors'];
		}

		$order = strtoupper( $settings['sorting_order'] );

		switch ( $settings['order_by'] ) {
			case 'date':
				$args['orderby'] = 'date';
				$args['order']   = ( 'ASC' === $order ) ? 'ASC' : 'DESC';
				break;

			case 'price':
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = '_price';
				$args['order']    = ( 'DESC' === $order ) ? 'DESC' : 'ASC';
				break;

			case 'title':
				$args['orderby'] = 'title';
				$args['order']   = ( 'DESC' === $order ) ? 'DESC' : 'ASC';
				break;

			case 'rating':
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = '_average_rating';
				$args['order']    = ( 'ASC' === $order ) ? 'ASC' : 'DESC';
				break;

			default:
				$args['orderby'] = 'date';
				$args['order']   = ( 'ASC' === $order ) ? 'ASC' : 'DESC';
				break;
		}

		$courses_query = new \WP_Query( $args );
		$courses       = array_filter( array_map( 'masteriyo_get_course', $courses_query->posts ) );

		printf( '<div class="masteriyo">' );
		masteriyo_set_loop_prop( 'columns', $columns );

		if ( count( $courses ) > 0 ) {
			$original_course = isset( $GLOBALS['course'] ) ? $GLOBALS['course'] : null;

			masteriyo_course_loop_start();

			foreach ( $courses as $course ) {
				$GLOBALS['course'] = $course;
				$card_class        = empty( $settings['card_hover_animation'] ) ? '' : sprintf( 'elementor-animation-%s', $settings['card_hover_animation'] );

				masteriyo_get_template(
					'content-course.php',
					array(
						'card_class' => $card_class,
					)
				);
			}

			$GLOBALS['course'] = $original_course;

			masteriyo_course_loop_end();
			masteriyo_reset_loop();
		}
		echo '</div>';
	}
}
