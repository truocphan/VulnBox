<?php

namespace StmLmsElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

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
class StmLmsSingleCourseCarousel extends Widget_Base {


	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'stm_lms_single_course_carousel';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Single Course Carousel', 'masterstudy-lms-learning-management-system' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-slider lms-icon';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'stm_lms_old' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_control(
			'query',
			array(
				'name'        => 'query',
				'label'       => __( 'Sort', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
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
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'enable'  => __( 'Enable', 'masterstudy-lms-learning-management-system' ),
					'disable' => __( 'Disable', 'masterstudy-lms-learning-management-system' ),
				),
				'default'     => 'enable',
			)
		);

		$this->add_control(
			'pagination',
			array(
				'name'        => 'pagination',
				'label'       => __( 'Pagination', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'enable'  => __( 'Enable', 'masterstudy-lms-learning-management-system' ),
					'disable' => __( 'Disable', 'masterstudy-lms-learning-management-system' ),
				),
				'default'     => 'disable',
			)
		);

		$this->add_control(
			'taxonomy',
			array(
				'name'        => 'taxonomy',
				'label'       => __( 'Select taxonomy', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => stm_lms_elementor_autocomplete_terms( 'stm_lms_course_taxonomy' ),
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
			'css'        => '',
			'query'      => ! empty( $settings['query'] ) ? $settings['query'] : 'none',
			'prev_next'  => ! empty( $settings['prev_next'] ) ? $settings['prev_next'] : 'enable',
			'pagination' => ! empty( $settings['pagination'] ) ? $settings['pagination'] : 'disable',
			'taxonomy'   => ! empty( $settings['taxonomy'] ) ? implode( ',', $settings['taxonomy'] ) : array(),
		);
		$uniq         = stm_lms_create_unique_id( $atts );
		$atts['uniq'] = $uniq;
		\STM_LMS_Templates::show_lms_template( 'shortcodes/stm_lms_single_course_carousel', $atts );
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



