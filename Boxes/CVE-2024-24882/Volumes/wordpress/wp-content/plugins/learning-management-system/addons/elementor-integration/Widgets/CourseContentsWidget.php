<?php
/**
 * Masteriyo course contents elementor widget class.
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
 * Masteriyo course contents elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */
class CourseContentsWidget extends WidgetBase {

	/**
	 * Get widget name.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_name() {
		return 'masteriyo-course-contents';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Course Contents', 'masteriyo' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.6.12
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'masteriyo-course-contents-widget-icon';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.6.12
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'contents', 'overview', 'description', 'curriculum', 'lessons', 'quizzes', 'sections', 'reviews' );
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
		$this->register_container_styles_section();
		$this->register_tabs_container_styles_section();
		$this->register_overview_styles_section();
		$this->register_curriculum_styles_section();
		$this->register_reviews_styles_section();
	}

	/**
	 * Register container style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_container_styles_section() {
		$this->start_controls_section(
			'container_styles',
			array(
				'label' => __( 'Container', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'overflow',
			array(
				'label'     => esc_html__( 'Overflow', 'masteriyo' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'auto',
				'options'   => array(
					'auto'    => esc_html__( 'Auto', 'masteriyo' ),
					'visible' => esc_html__( 'Visible', 'masteriyo' ),
					'hidden'  => esc_html__( 'Hidden', 'masteriyo' ),
					'scroll'  => esc_html__( 'Scroll', 'masteriyo' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-single-course--main__content' => 'overflow: {{VALUE}};',
				),
			)
		);

		$this->add_text_region_style_controls(
			'container_',
			'.masteriyo-single-course--main__content',
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
	 * Register tabs container style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_tabs_container_styles_section() {
		$this->start_controls_section(
			'tabs_container_styles',
			array(
				'label' => __( 'Tabs Container', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'tabs_container_',
			'.masteriyo-single-course--main__content .masteriyo-stab',
			array(
				'disable_align'       => true,
				'disable_typography'  => true,
				'disable_text_color'  => true,
				'disable_text_shadow' => true,
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'tab_styles',
			array(
				'label' => __( 'Tab', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'tab_',
			'.masteriyo-single-course--main__content .masteriyo-stab .masteriyo-tab',
			array(
				'disable_align' => true,
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'active_tab_styles',
			array(
				'label' => __( 'Active Tab', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'active_tab_',
			'.masteriyo-single-course--main__content .masteriyo-stab .masteriyo-tab.active-tab',
			array(
				'disable_align' => true,
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Register overview style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_overview_styles_section() {
		$this->start_controls_section(
			'overview_styles',
			array(
				'label' => __( 'Overview', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'overview_',
			'.masteriyo-single-course--main__content .tab-content.course-overview',
			array()
		);
		$this->end_controls_section();
	}

	/**
	 * Register curriculum style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_curriculum_styles_section() {
		$this->start_controls_section(
			'curriculum_styles',
			array(
				'label' => __( 'Curriculum', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'curriculum_',
			'.masteriyo-single-course--main__content .tab-content.course-curriculum',
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
				'label' => __( 'Curriculum > Info Section', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'curriculum_info_section_',
			'.masteriyo-single-course--main__content .tab-content.course-curriculum .masteriyo-stab--shortinfo',
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
				'label' => __( 'Curriculum > Info Section > Title', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'curriculum_info_section_title_',
			'.masteriyo-single-course--main__content .tab-content.course-curriculum .masteriyo-stab--shortinfo .title',
			array()
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'curriculum_info_section_details_styles',
			array(
				'label' => __( 'Curriculum > Info Section > Details', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'curriculum_info_section_details_',
			'.masteriyo-single-course--main__content .tab-content.course-curriculum .masteriyo-stab--shortinfo .masteriyo-shortinfo-wrap',
			array(
				'disable_align' => true,
				array(),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'curriculum_expand_button_styles',
			array(
				'label' => __( 'Curriculum > Info Section > Expand Button', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'curriculum_expand_button_',
			'.masteriyo-single-course--main__content .tab-content.course-curriculum .masteriyo-stab--shortinfo .masteriyo-expand-collapse-all',
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
			'.masteriyo-single-course--main__content .tab-content.course-curriculum .masteriyo-stab--citems',
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
	 * Register reviews style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_reviews_styles_section() {
		$this->start_controls_section(
			'reviews_styles',
			array(
				'label' => __( 'Reviews', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'reviews_',
			'.masteriyo-single-course--main__content .tab-content.course-reviews',
			array(
				'disable_align'       => true,
				'disable_typography'  => true,
				'disable_text_color'  => true,
				'disable_text_shadow' => true,
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'reviews_average_rating_section_styles',
			array(
				'label' => __( 'Reviews > Average Rating Section', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'reviews_average_rating_section_',
			'.masteriyo-single-course--main__content .tab-content.course-reviews .masteriyo-stab--treviews .masteriyo-stab-rs',
			array(
				'disable_align'       => true,
				'disable_typography'  => true,
				'disable_text_color'  => true,
				'disable_text_shadow' => true,
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'reviews_average_rating_section_icons_styles',
			array(
				'label' => __( 'Reviews > Average Rating Section > Icons', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'reviews_average_rating_section_icons_color',
			array(
				'label'     => __( 'Icon Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tab-content.course-reviews .masteriyo-stab--treviews .masteriyo-stab-rs svg' => 'fill: {{VALUE}} !important;',
				),
			)
		);
		$this->add_responsive_control(
			'reviews_average_rating_section_icons_size',
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
					'{{WRAPPER}} .tab-content.course-reviews .masteriyo-stab--treviews .masteriyo-stab-rs svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'reviews_average_rating_section_icons_spacing',
			array(
				'label'      => __( 'Spacing', 'masteriyo' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .tab-content.course-reviews .masteriyo-stab--treviews .masteriyo-stab-rs svg:not(:first-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_text_region_style_controls(
			'reviews_average_rating_section_icons_',
			'.masteriyo-single-course--main__content .tab-content.course-reviews .masteriyo-stab--treviews .masteriyo-stab-rs .masteriyo-rstar',
			array(
				'disable_align'       => true,
				'disable_typography'  => true,
				'disable_text_color'  => true,
				'disable_text_shadow' => true,
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'reviews_average_rating_section_text_styles',
			array(
				'label' => __( 'Reviews > Average Rating Section > Text', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'reviews_average_rating_section_text_',
			'.masteriyo-single-course--main__content .tab-content.course-reviews .masteriyo-stab--treviews .masteriyo-stab-rs .masteriyo-rnumber',
			array(
				'disable_align' => true,
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'reviews_rating_count_text_styles',
			array(
				'label' => __( 'Reviews > Rating Count Text', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'reviews_rating_count_text_',
			'.masteriyo-single-course--main__content .tab-content.course-reviews .masteriyo-stab--turating',
			array()
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'reviews_form_title_styles',
			array(
				'label' => __( 'Reviews Form Title', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'reviews_form_title_',
			'.masteriyo-single-course--main__content .tab-content.course-reviews .masteriyo--title',
			array(
				'disable_align' => true,
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'reviews_form_styles',
			array(
				'label' => __( 'Reviews Form', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'reviews_form_',
			'.masteriyo-single-course--main__content .tab-content.course-reviews .masteriyo-submit-review-form',
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

		masteriyo_template_single_course_main_content( $course );
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

		masteriyo_template_single_course_main_content( $course );
	}
}
