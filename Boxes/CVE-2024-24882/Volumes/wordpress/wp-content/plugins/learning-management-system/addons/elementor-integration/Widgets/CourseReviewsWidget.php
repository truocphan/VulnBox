<?php
/**
 * Masteriyo course reviews elementor widget class.
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
 * Masteriyo course reviews elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */
class CourseReviewsWidget extends WidgetBase {

	/**
	 * Get widget name.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_name() {
		return 'masteriyo-course-reviews';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Course Reviews', 'masteriyo' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.6.12
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'masteriyo-course-reviews-widget-icon';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.6.12
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'reviews', 'rating', 'comments' );
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
			'reviews_styles',
			array(
				'label' => __( 'Reviews', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'reviews_',
			'.course-reviews',
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
				'label' => __( 'Average Rating Section', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'reviews_average_rating_section_',
			'.course-reviews .masteriyo-stab--treviews .masteriyo-stab-rs',
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
				'label' => __( 'Average Rating Section > Icons', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'reviews_average_rating_section_icons_color',
			array(
				'label'     => __( 'Icon Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .course-reviews .masteriyo-stab--treviews .masteriyo-stab-rs svg' => 'fill: {{VALUE}} !important;',
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
					'{{WRAPPER}} .course-reviews .masteriyo-stab--treviews .masteriyo-stab-rs svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .course-reviews .masteriyo-stab--treviews .masteriyo-stab-rs svg:not(:first-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_text_region_style_controls(
			'reviews_average_rating_section_icons_',
			'.course-reviews .masteriyo-stab--treviews .masteriyo-stab-rs .masteriyo-rstar',
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
				'label' => __( 'Average Rating Section > Text', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'reviews_average_rating_section_text_',
			'.course-reviews .masteriyo-stab--treviews .masteriyo-stab-rs .masteriyo-rnumber',
			array(
				'disable_align' => true,
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'reviews_rating_count_text_styles',
			array(
				'label' => __( 'Rating Count Text', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'reviews_rating_count_text_',
			'.course-reviews .masteriyo-stab--turating',
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
			'.course-reviews .masteriyo--title',
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
			'.course-reviews .masteriyo-submit-review-form',
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

		if ( $course->is_review_allowed() ) {
			$reviews_and_replies = masteriyo_get_course_reviews_and_replies( $course );

			masteriyo_get_template(
				'single-course/reviews.php',
				array(
					'course'         => $course,
					'course_reviews' => $reviews_and_replies['reviews'],
					'replies'        => $reviews_and_replies['replies'],
					'is_hidden'      => false,
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

		if ( $course->is_review_allowed() ) {
			$reviews_and_replies = masteriyo_get_course_reviews_and_replies( $course );

			masteriyo_get_template(
				'single-course/reviews.php',
				array(
					'course'         => $course,
					'course_reviews' => $reviews_and_replies['reviews'],
					'replies'        => $reviews_and_replies['replies'],
					'is_hidden'      => false,
				)
			);
		}
	}
}
