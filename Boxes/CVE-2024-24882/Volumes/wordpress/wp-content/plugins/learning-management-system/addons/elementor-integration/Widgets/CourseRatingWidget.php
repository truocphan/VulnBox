<?php
/**
 * Masteriyo course rating elementor widget class.
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
 * Masteriyo course rating elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */
class CourseRatingWidget extends WidgetBase {

	/**
	 * Get widget name.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_name() {
		return 'masteriyo-course-rating';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Course Rating', 'masteriyo' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.6.12
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'masteriyo-course-rating-widget-icon';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.6.12
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'rating', 'reviews' );
	}

	/**
	 * Register controls configuring widget content.
	 *
	 * @since 1.6.12
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'general',
			array(
				'label' => __( 'General', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_on_off_switch_control(
			'show_icons',
			__( 'Icons', 'masteriyo' ),
			array(),
			array(
				'{{WRAPPER}} .masteriyo-rating svg' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_text',
			__( 'Text', 'masteriyo' ),
			array(),
			array(
				'{{WRAPPER}} .masteriyo-rating .text' => 'display: none !important;',
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
		$this->register_rating_styles_section();
		$this->register_rating_icon_styles_section();
		$this->register_rating_text_styles_section();
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

		$this->add_responsive_control(
			'align',
			array(
				'label'     => esc_html__( 'Alignment', 'masteriyo' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'masteriyo' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'masteriyo' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'masteriyo' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-rating' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_text_region_style_controls(
			'rating_',
			'.masteriyo-rating',
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
	 * Register rating icon style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_rating_icon_styles_section() {
		$this->start_controls_section(
			'rating_icon_styles',
			array(
				'label' => __( 'Icon', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon Color', 'masteriyo' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-rating svg' => 'fill: {{VALUE}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
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
					'{{WRAPPER}} .masteriyo-rating svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'spacing',
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
					'{{WRAPPER}} .masteriyo-rating svg:not(:first-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register rating text style controls section.
	 *
	 * @since 1.6.12
	 */
	protected function register_rating_text_styles_section() {
		$this->start_controls_section(
			'rating_text_styles',
			array(
				'label' => __( 'Text', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'default_styles',
			array(
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'yes',
				'selectors' => array(
					'{{WRAPPER}} .text' => 'margin-left: 4px;',
				),
			)
		);

		$this->add_text_region_style_controls(
			'rating_text_',
			'.masteriyo-rating .text',
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

		if ( $course->is_review_allowed() ) : ?>
			<span class="masteriyo-icon-svg masteriyo-rating">
				<?php masteriyo_format_rating( $course->get_average_rating(), true ); ?> <span class="text"><?php echo esc_html( masteriyo_format_decimal( $course->get_average_rating(), 1, true ) ); ?> (<?php echo esc_html( $course->get_review_count() ); ?>)</span>
			</span>
			<?php
		endif;
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

		if ( $course->is_review_allowed() ) :
			?>
			<span class="masteriyo-icon-svg masteriyo-rating">
				<?php masteriyo_format_rating( $course->get_average_rating(), true ); ?> <span class="text"><?php echo esc_html( masteriyo_format_decimal( $course->get_average_rating(), 1, true ) ); ?> (<?php echo esc_html( $course->get_review_count() ); ?>)</span>
			</span>
			<?php
		endif;
	}
}
