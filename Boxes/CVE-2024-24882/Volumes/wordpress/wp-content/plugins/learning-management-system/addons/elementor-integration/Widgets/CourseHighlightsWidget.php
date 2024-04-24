<?php
/**
 * Masteriyo course highlights elementor widget class.
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
 * Masteriyo course highlights elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */
class CourseHighlightsWidget extends WidgetBase {

	/**
	 * Get widget name.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_name() {
		return 'masteriyo-course-highlights';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Course Highlights', 'masteriyo' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.6.12
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'masteriyo-course-highlights-widget-icon';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.6.12
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'description', 'highlights' );
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
			'highlights_styles_section',
			array(
				'label' => esc_html__( 'Highlights', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'default_styles',
			array(
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'yes',
				'selectors' => array(
					'{{WRAPPER}} .title' => 'display: none;',
				),
			)
		);

		$this->add_text_region_style_controls(
			'highlights_',
			'.masteriyo-course--content__description',
			array(
				'custom_selectors'   => array(
					'text_color'       => '{{WRAPPER}} *',
					'hover_text_color' => '{{WRAPPER}} .masteriyo-course--content__description:hover *',
					'typography'       => '{{WRAPPER}} *',
					'hover_typography' => '{{WRAPPER}} .masteriyo-course--content__description:hover *',
				),
				'normal_state_start' => function() {
					$this->add_control(
						'spacing',
						array(
							'label'      => __( 'Spacing', 'masteriyo' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => array( 'px' ),
							'range'      => array(
								'px' => array(
									'min' => 0,
									'max' => 300,
								),
							),
							'selectors'  => array(
								'{{WRAPPER}} .masteriyo-course--content__description li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
							),
						)
					);
				},
				'hover_state_start'  => function() {
					$this->add_control(
						'hover_spacing',
						array(
							'label'      => __( 'Spacing', 'masteriyo' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => array( 'px' ),
							'range'      => array(
								'px' => array(
									'min' => 0,
									'max' => 300,
								),
							),
							'selectors'  => array(
								'{{WRAPPER}} .masteriyo-course--content__description:hover li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
							),
						)
					);
				},
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

		masteriyo_single_course_highlights( $course );
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * @since 1.6.12
	 */
	protected function render() {
		$course = $this->get_course_to_render();

		if ( $course ) {
			masteriyo_single_course_highlights( $course );
		}
	}
}
