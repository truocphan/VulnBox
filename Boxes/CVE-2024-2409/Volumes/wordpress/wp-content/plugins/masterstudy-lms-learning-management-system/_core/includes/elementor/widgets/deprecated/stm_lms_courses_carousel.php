<?php
/**
 * @file
 * Description of what this module (or file) is doing.
 */

namespace StmLmsElementor\Widgets;

use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class StmLmsCoursesCarousel extends Widget_Base {

	use \MsLmsAddOverlay;

	public function get_name() {
		return 'stm_lms_courses_carousel';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 *
	 * @access public
	 */
	public function get_title() {
		return __( 'Courses Carousel', 'masterstudy-lms-learning-management-system' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.0.0
	 *
	 * @access public
	 */
	public function get_icon() {
		return 'stmlms-courses-carousel-old lms-icon';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since  1.0.0
	 *
	 * @access public
	 */
	public function get_categories() {
		return array( 'stm_lms_old' );
	}

	/**
	 * Register controls for Elementor.
	 *
	 * @since 1.0.0
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'masterstudy-lms-learning-management-system' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'name'        => 'title',
				'label'       => __( 'Title', 'masterstudy-lms-learning-management-system' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'name'        => 'title_color',
				'label'       => __( 'Title color', 'masterstudy-lms-learning-management-system' ),
				'type'        => Controls_Manager::COLOR,
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} .stm_lms_courses_carousel__top h3 ' => 'color: {{VALUE}}',
					'{{WRAPPER}} .stm_lms_courses_carousel__top .h4' => 'color: {{VALUE}}',
					'{{WRAPPER}} .stm_lms_courses_carousel__top .h4:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .stm_lms_courses_carousel__buttons .stm_lms_courses_carousel__button i:before' => 'border-color:  {{VALUE}}',
					'{{WRAPPER}} .stm_lms_courses_carousel__buttons .stm_lms_courses_carousel__button_next i:before' => 'border-color: {{VALUE}} ',
				),
			)
		);

		$this->add_control(
			'query',
			array(
				'name'        => 'query',
				'label'       => __( 'Sort', 'masterstudy-lms-learning-management-system' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'none'    => __( 'None', 'masterstudy-lms-learning-management-system' ),
					'popular' => __( 'Popular', 'masterstudy-lms-learning-management-system' ),
					'free'    => __( 'Free', 'masterstudy-lms-learning-management-system' ),
					'rating'  => __( 'Rating', 'masterstudy-lms-learning-management-system' ),
				),
				'default'     => 'none',
			)
		);

		$this->add_control(
			'prev_next',
			array(
				'name'        => 'prev_next',
				'label'       => __( 'Prev/Next Buttons', 'masterstudy-lms-learning-management-system' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'enable'  => __( 'Enable', 'masterstudy-lms-learning-management-system' ),
					'disable' => __( 'Disable', 'masterstudy-lms-learning-management-system' ),
				),
				'default'     => 'enable',
			)
		);
		$this->add_control(
			'view_all_btn_hide_control',
			array(
				'name'        => 'view_all_btn_hide_control',
				'label'       => __( 'View All', 'masterstudy-lms-learning-management-system' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'enable'  => __( 'Enable', 'masterstudy-lms-learning-management-system' ),
					'disable' => __( 'Disable', 'masterstudy-lms-learning-management-system' ),
				),
				'default'     => 'enable',
			)
		);

		$this->add_control(
			'prev_next_style',
			array(
				'name'        => 'prev_next_style',
				'label'       => __( 'Prev/Next Buttons Style', 'masterstudy-lms-learning-management-system' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'style_1' => __( 'Style 1', 'masterstudy-lms-learning-management-system' ),
					'style_2' => __( 'Style 2', 'masterstudy-lms-learning-management-system' ),
				),
				'default'     => 'style_1',
				'condition'   => array(
					'prev_next' => 'enable',
				),
			)
		);

		$this->add_control(
			'remove_border',
			array(
				'name'        => 'remove_border',
				'label'       => __( 'Remove border', 'masterstudy-lms-learning-management-system' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'enable'  => __( 'Enable', 'masterstudy-lms-learning-management-system' ),
					'disable' => __( 'Disable', 'masterstudy-lms-learning-management-system' ),
				),
				'default'     => 'disable',
			)
		);

		$this->add_control(
			'show_categories',
			array(
				'name'        => 'show_categories',
				'label'       => __( 'Show categories', 'masterstudy-lms-learning-management-system' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'enable'  => __( 'Enable', 'masterstudy-lms-learning-management-system' ),
					'disable' => __( 'Disable', 'masterstudy-lms-learning-management-system' ),
				),
				'default'     => 'disable',
			)
		);

		$this->add_control(
			'pagination',
			array(
				'name'        => 'pagination',
				'label'       => __( 'Pagination', 'masterstudy-lms-learning-management-system' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'enable'  => __( 'Enable', 'masterstudy-lms-learning-management-system' ),
					'disable' => __( 'Disable', 'masterstudy-lms-learning-management-system' ),
				),
				'default'     => 'disable',
			)
		);

		$this->add_control(
			'course_card_style',
			array(
				'name'        => 'course_card_style',
				'label'       => __( 'Course Card Style', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'style_1' => __( 'Default', 'masterstudy-lms-learning-management-system' ),
					'style_2' => __( 'Price on Hover', 'masterstudy-lms-learning-management-system' ),
					'style_3' => __( 'Scale on Hover', 'masterstudy-lms-learning-management-system' ),
				),
				'default'     => 'style_1',
			)
		);

		$this->add_control(
			'image_size',
			array(
				'name'        => 'image_size',
				'label'       => __( 'Image size (Ex. : 200x100)', 'masterstudy-lms-learning-management-system' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '300x225',
			)
		);

		$this->add_control(
			'img_container_height',
			array(
				'name'        => 'img_container_height',
				'label'       => __( 'Image Container Height', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'label_block' => true,
				'default'     => 160,
			)
		);

		$this->add_control(
			'per_row',
			array(
				'name'        => 'per_row',
				'label'       => __( 'Courses per row', 'masterstudy-lms-learning-management-system' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => true,
				'default'     => 6,
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'name'        => 'Posts per page',
				'label'       => __( 'Courses per carousel', 'masterstudy-lms-learning-management-system' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => true,
				'default'     => 12,
			)
		);

		$this->add_control(
			'taxonomy',
			array(
				'name'        => 'taxonomy',
				'label'       => __( 'Select taxonomy', 'masterstudy-lms-learning-management-system' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => stm_lms_elementor_autocomplete_terms( 'stm_lms_course_taxonomy' ),
				'condition'   => array(
					'show_categories' => 'enable',
				),
			)
		);

		$this->add_control(
			'taxonomy_default',
			array(
				'name'        => 'taxonomy_default',
				'label'       => __( 'Show Courses From categories:', 'masterstudy-lms-learning-management-system' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => stm_lms_elementor_autocomplete_terms( 'stm_lms_course_taxonomy' ),
				'condition'   => array(
					'show_categories' => 'disable',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => esc_html__( 'Title', 'masterstudy-lms-learning-management-system' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .stm_lms_courses_carousel__top h3',
			)
		);

		$this->add_responsive_control(
			'align_title',
			array(
				'label'     => esc_html__( 'Alignment', 'elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_courses_carousel__top' => 'justify-content: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_view_all_style',
			array(
				'label' => esc_html__( 'View all', 'masterstudy-lms-learning-management-system' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_view_all',
				'selector' => '{{WRAPPER}} .stm_lms_courses_carousel__top',
			)
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_view_course_card_border',
			array(
				'label' => esc_html__( 'Course Card', 'masterstudy-lms-learning-management-system' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'border',
				'selector' => '{{WRAPPER}} .stm_lms_courses_carousel .owl-stage .owl-item .stm_lms_courses__single__inner',
			)
		);
		$this->add_control(
			'course_card_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_courses_carousel .owl-stage .owl-item .stm_lms_courses__single__inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm_lms_courses_carousel_wrapper .stm_lms_courses__single .stm_lms_courses__single--image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0',
				),
				'default'    => array(
					'top'    => '10',
					'right'  => '10',
					'bottom' => '10',
					'left'   => '10',
					'unit'   => 'px',
				),
			)
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_buttons_styles',
			array(
				'label' => esc_html__( 'Prev/Next buttons', 'masterstudy-lms-learning-management-system' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'buttons_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_courses_carousel__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '0',
					'left'   => '0',
					'unit'   => 'px',
				),
			)
		);
		$this->add_control(
			'prev_button_padding',
			array(
				'label'      => esc_html__( 'Prev button padding', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_courses_carousel__button_prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'    => '0',
					'right'  => '2',
					'bottom' => '0',
					'left'   => '0',
					'unit'   => 'px',
				),
			)
		);
		$this->add_control(
			'next_button_padding',
			array(
				'label'      => esc_html__( 'Next button padding', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_courses_carousel__button_next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '0',
					'left'   => '2',
					'unit'   => 'px',
				),
			)
		);
		$this->add_control(
			'buttons_margin',
			array(
				'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_courses_carousel__button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '0',
					'left'   => '0',
					'unit'   => 'px',
				),
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings     = $this->get_settings_for_display();
		$atts         = array(
			'css'                       => '',
			'title_color'               => ! empty( $settings['title_color'] ) ? $settings['title_color'] : '',
			'title'                     => ! empty( $settings['title'] ) ? $settings['title'] : '',
			'query'                     => ! empty( $settings['query'] ) ? $settings['query'] : 'none',
			'prev_next'                 => ! empty( $settings['prev_next'] ) ? $settings['prev_next'] : 'enable',
			'view_all_btn_hide_control' => ! empty( $settings['view_all_btn_hide_control'] ) ? $settings['view_all_btn_hide_control'] : 'enable',
			'prev_next_style'           => ! empty( $settings['prev_next_style'] ) ? $settings['prev_next_style'] : 'style_1',
			'per_row'                   => ! empty( $settings['per_row'] ) ? $settings['per_row'] : 6,
			'posts_per_page'            => ! empty( $settings['posts_per_page'] ) ? $settings['posts_per_page'] : 12,
			'pagination'                => ! empty( $settings['pagination'] ) ? $settings['pagination'] : 'disable',
			'taxonomy'                  => ! empty( $settings['taxonomy'] ) && is_array( $settings['taxonomy'] ) ? implode( ',', $settings['taxonomy'] ) : array(),
			'taxonomy_default'          => ! empty( $settings['taxonomy_default'] ) && is_array( $settings['taxonomy_default'] ) ? implode( ',', $settings['taxonomy_default'] ) : array(),
			'image_size'                => ! empty( $settings['image_size'] ) ? $settings['image_size'] : '',
			'show_categories'           => ! empty( $settings['show_categories'] ) ? $settings['show_categories'] : 'disable',
			'course_card_style'         => ! empty( $settings['course_card_style'] ) ? $settings['course_card_style'] : 'style_1',
			'img_container_height'      => ! empty( $settings['img_container_height'] ) ? $settings['img_container_height'] : '',
		);
		$uniq         = stm_lms_create_unique_id( $atts );
		$atts['uniq'] = $uniq;
		if ( Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_courses_widget_overlay();
		}
		\STM_LMS_Templates::show_lms_template( 'shortcodes/stm_lms_courses_carousel', $atts );
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {

	}
}
