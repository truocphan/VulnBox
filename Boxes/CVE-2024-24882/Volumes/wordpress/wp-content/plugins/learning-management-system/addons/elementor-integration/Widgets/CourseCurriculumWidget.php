<?php
/**
 * Masteriyo course curriculum elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */

namespace Masteriyo\Addons\ElementorIntegration\Widgets;

use Elementor\Controls_Manager;
use Masteriyo\Addons\ElementorIntegration\Helper;
use Masteriyo\Addons\ElementorIntegration\WidgetBase;

defined( 'ABSPATH' ) || exit;

/**
 * Masteriyo course curriculum elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */
class CourseCurriculumWidget extends WidgetBase {

	/**
	 * Get widget name.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_name() {
		return 'masteriyo-course-curriculum';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Course Curriculum', 'masteriyo' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.6.12
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'masteriyo-course-curriculum-widget-icon';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.6.12
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'contents', 'curriculum', 'lessons', 'quizzes', 'sections' );
	}

	/**
	 * Register controls configuring widget content.
	 *
	 * @since 1.6.12
	 */
	protected function register_content_controls() {}

	/**
	 * Register controls for customizing widget styles.
	 *
	 * @since 1.6.12
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'curriculum_styles',
			array(
				'label' => __( 'Curriculum', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'curriculum_',
			'.course-curriculum',
			array(
				'disable_align'       => true,
				'disable_typography'  => true,
				'disable_text_color'  => true,
				'disable_text_shadow' => true,
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'curriculum_info_section_styles',
			array(
				'label' => __( 'Info Section', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'curriculum_info_section_',
			'.course-curriculum .masteriyo-stab--shortinfo',
			array(
				'disable_align'       => true,
				'disable_typography'  => true,
				'disable_text_color'  => true,
				'disable_text_shadow' => true,
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'curriculum_info_section_title_styles',
			array(
				'label' => __( 'Info Section > Title', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'curriculum_info_section_title_',
			'.course-curriculum .masteriyo-stab--shortinfo .title',
			array()
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'curriculum_info_section_details_styles',
			array(
				'label' => __( 'Info Section > Details', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'curriculum_info_section_details_',
			'.course-curriculum .masteriyo-stab--shortinfo .masteriyo-shortinfo-wrap',
			array(
				'disable_align' => true,
				array(),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'curriculum_expand_button_styles',
			array(
				'label' => __( 'Info Section > Expand Button', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'curriculum_expand_button_',
			'.course-curriculum .masteriyo-stab--shortinfo .masteriyo-expand-collapse-all',
			array(
				'disable_align' => true,
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'list_container_styles',
			array(
				'label' => __( 'List Container', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'list_container_',
			'.course-curriculum .masteriyo-stab--citems',
			array(
				'disable_align'       => true,
				'disable_typography'  => true,
				'disable_text_color'  => true,
				'disable_text_shadow' => true,
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Render heading widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.6.12
	 */
	protected function content_template() {
		$course = Helper::get_elementor_preview_course();

		if ( ! $course ) {
			return;
		}

		if ( $course->get_show_curriculum() || masteriyo_can_start_course( $course ) ) {
			$sections = masteriyo_get_course_structure( $course->get_id() );

			masteriyo_get_template(
				'single-course/curriculum.php',
				array(
					'course'    => $course,
					'sections'  => $sections,
					'is_hidden' => false,
				)
			);
		}
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * @since 1.6.12
	 */
	protected function render() {
		$course = $this->get_course_to_render();

		if ( ! $course ) {
			return;
		}

		if ( $course->get_show_curriculum() || masteriyo_can_start_course( $course ) ) {
			$sections = masteriyo_get_course_structure( $course->get_id() );

			masteriyo_get_template(
				'single-course/curriculum.php',
				array(
					'course'    => $course,
					'sections'  => $sections,
					'is_hidden' => false,
				)
			);
		}
	}
}
