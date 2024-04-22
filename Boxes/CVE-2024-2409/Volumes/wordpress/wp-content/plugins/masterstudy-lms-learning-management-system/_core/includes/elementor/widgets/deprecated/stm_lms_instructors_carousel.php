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
class StmLmsInstructorsCarousel extends Widget_Base {


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
		return 'stm_lms_instructors_carousel';
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
		return __( 'Instructors Carousel', 'masterstudy-lms-learning-management-system' );
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
		return 'stmlms-instructors-carousel-old lms-icon';
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
			'title',
			array(
				'name'        => 'title',
				'label'       => __( 'Title', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$this->add_control(
			'limit',
			array(
				'name'        => 'limit',
				'label'       => __( 'Limit', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'label_block' => true,
				'default'     => 10,
			)
		);

		$this->add_control(
			'per_row',
			array(
				'name'        => 'per_row',
				'label'       => __( 'Per row', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'label_block' => true,
				'default'     => 6,
			)
		);

		$this->add_control(
			'per_row_md',
			array(
				'name'        => 'per_row_md',
				'label'       => __( 'Per row on Notebook', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'label_block' => true,
				'default'     => 4,
			)
		);

		$this->add_control(
			'per_row_sm',
			array(
				'name'        => 'per_row_sm',
				'label'       => __( 'Per row on Tablet', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'label_block' => true,
				'default'     => 2,
			)
		);

		$this->add_control(
			'per_row_xs',
			array(
				'name'        => 'per_row_xs',
				'label'       => __( 'Per row on Mobile', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'label_block' => true,
				'default'     => 1,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'name'        => 'title_color',
				'label'       => __( 'Title color', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'label_block' => true,
			)
		);

		$this->add_control(
			'style',
			array(
				'name'        => 'style',
				'label'       => __( 'Style', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'style_1' => __( 'Style 1', 'masterstudy-lms-learning-management-system' ),
					'style_2' => __( 'Style 2', 'masterstudy-lms-learning-management-system' ),
				),
				'default'     => 'style_1',
			)
		);

		$this->add_control(
			'sort',
			array(
				'name'        => 'sort',
				'label'       => __( 'Sort By', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'default' => __( 'Default', 'masterstudy-lms-learning-management-system' ),
					'rating'  => __( 'Rating', 'masterstudy-lms-learning-management-system' ),
				),
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
		$settings = $this->get_settings_for_display();
		$atts     = array(
			'css'         => '',
			'title'       => ! empty( $settings['title'] ) ? $settings['title'] : '',
			'per_row'     => ! empty( $settings['per_row'] ) ? $settings['per_row'] : '',
			'per_row_md'  => ! empty( $settings['per_row_md'] ) ? $settings['per_row_md'] : '',
			'limit'       => ! empty( $settings['limit'] ) ? $settings['limit'] : 10,
			'per_row_sm'  => ! empty( $settings['per_row_sm'] ) ? $settings['per_row_sm'] : '',
			'per_row_xs'  => ! empty( $settings['per_row_xs'] ) ? $settings['per_row_xs'] : '',
			'title_color' => ! empty( $settings['title_color'] ) ? $settings['title_color'] : '',
			'style'       => ! empty( $settings['style'] ) ? $settings['style'] : 'style_1',
			'sort'        => ! empty( $settings['sort'] ) ? $settings['sort'] : '',
			'prev_next'   => ! empty( $settings['prev_next'] ) ? $settings['prev_next'] : '',
			'pagination'  => ! empty( $settings['pagination'] ) ? $settings['pagination'] : '',
		);

		if ( 'default' === $atts['sort'] ) {
			$atts['sort'] = '';
		}
		\STM_LMS_Templates::show_lms_template( 'shortcodes/stm_lms_instructors_carousel', $atts );
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



