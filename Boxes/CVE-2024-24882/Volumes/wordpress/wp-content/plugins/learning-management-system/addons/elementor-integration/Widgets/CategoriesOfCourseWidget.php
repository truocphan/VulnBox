<?php
/**
 * Masteriyo course categories elementor widget class.
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
 * Masteriyo course categories elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */
class CategoriesOfCourseWidget extends WidgetBase {

	/**
	 * Get widget name.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_name() {
		return 'masteriyo-categories-of-course';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Categories of Course', 'masteriyo' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.6.12
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'masteriyo-categories-of-course-widget-icon';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.6.12
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'category', 'categories' );
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
			'categories_styles_section',
			array(
				'label' => esc_html__( 'Categories', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_text_region_style_controls(
			'categories_',
			'.masteriyo-course--content__category',
			array(
				'disable_align'       => true,
				'disable_typography'  => true,
				'disable_text_color'  => true,
				'disable_text_shadow' => true,
				'custom_selectors'    => array(
					'text_color'       => '{{WRAPPER}} *',
					'hover_text_color' => '{{WRAPPER}} .masteriyo-course--content__category:hover *',
					'typography'       => '{{WRAPPER}} *',
					'hover_typography' => '{{WRAPPER}} .masteriyo-course--content__category:hover *',
				),
				'normal_state_start'  => function() {
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
								'{{WRAPPER}} .masteriyo-course--content__category .masteriyo-course--content__category-items:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}} !important;',
							),
						)
					);
				},
				'hover_state_start'   => function() {
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
								'{{WRAPPER}} .masteriyo-course--content__category:hover .masteriyo-course--content__category-items:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}} !important;',
							),
						)
					);
				},
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'categories_item_styles_section',
			array(
				'label' => esc_html__( 'Item', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_text_region_style_controls(
			'categories_item_',
			'.masteriyo-course--content__category .masteriyo-course--content__category-items',
			array(
				'disable_align' => true,
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

		masteriyo_single_course_categories( $course );
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * @since 1.6.12
	 */
	protected function render() {
		$course = $this->get_course_to_render();

		if ( $course ) {
			masteriyo_single_course_categories( $course );
		}
	}
}
