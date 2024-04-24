<?php
/**
 * Masteriyo course author elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */

namespace Masteriyo\Addons\ElementorIntegration\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Masteriyo\Addons\ElementorIntegration\Helper;
use Masteriyo\Addons\ElementorIntegration\WidgetBase;

defined( 'ABSPATH' ) || exit;

/**
 * Masteriyo course author elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */
class CourseAuthorWidget extends WidgetBase {

	/**
	 * Get widget name.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_name() {
		return 'masteriyo-course-author';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Course Author', 'masteriyo' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.6.12
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'masteriyo-course-author-widget-icon';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.6.12
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'author', 'instructor' );
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
			'show_avatar',
			__( 'Avatar', 'masteriyo' ),
			array(),
			array(
				'{{WRAPPER}} .masteriyo-course-author img' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_name',
			__( 'Name', 'masteriyo' ),
			array(),
			array(
				'{{WRAPPER}} .masteriyo-course-author .masteriyo-course-author--name' => 'display: none !important;',
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
		$this->register_author_styles_section();
		$this->register_author_avatar_styles_section();
		$this->register_author_name_styles_section();
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
			'default_styles',
			array(
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'yes',
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course-author' => 'display: block !important;',
					'{{WRAPPER}} .masteriyo-course-author a' => 'display: inline-flex;',
				),
			)
		);

		$this->add_responsive_control(
			'direction',
			array(
				'label'     => esc_html__( 'Direction', 'masteriyo' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'row',
				'options'   => array(
					'row'    => esc_html__( 'Row', 'masteriyo' ),
					'column' => esc_html__( 'Column', 'masteriyo' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course-author a' => 'flex-direction: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'vertical_align',
			array(
				'label'     => esc_html__( 'Vertical Alignment', 'masteriyo' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center',
				'options'   => array(
					''              => esc_html__( 'Default', 'masteriyo' ),
					'flex-start'    => esc_html__( 'Start', 'masteriyo' ),
					'center'        => esc_html__( 'Center', 'masteriyo' ),
					'flex-end'      => esc_html__( 'End', 'masteriyo' ),
					'space-between' => esc_html__( 'Space Between', 'masteriyo' ),
					'space-around'  => esc_html__( 'Space Around', 'masteriyo' ),
					'space-evenly'  => esc_html__( 'Space Evenly', 'masteriyo' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course-author a' => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'horizontal_align',
			array(
				'label'     => esc_html__( 'Horizontal Alignment', 'masteriyo' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''              => esc_html__( 'Default', 'masteriyo' ),
					'flex-start'    => esc_html__( 'Start', 'masteriyo' ),
					'center'        => esc_html__( 'Center', 'masteriyo' ),
					'flex-end'      => esc_html__( 'End', 'masteriyo' ),
					'space-between' => esc_html__( 'Space Between', 'masteriyo' ),
					'space-around'  => esc_html__( 'Space Around', 'masteriyo' ),
					'space-evenly'  => esc_html__( 'Space Evenly', 'masteriyo' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-course-author a' => 'justify-content: {{VALUE}};',
				),
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
			'author_name_styles_section',
			array(
				'label' => esc_html__( 'Author Name', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'author_name_',
			'.masteriyo-course-author .masteriyo-course-author--name',
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

		$author = masteriyo_get_user( $course->get_author_id() );

		if ( ! $author ) {
			return;
		}

		?>
		<div class="masteriyo-course-author">
			<a href="<?php echo esc_url( $author->get_course_archive_url() ); ?>">
				<img src="<?php echo esc_attr( $author->profile_image_url() ); ?>"
					alt="<?php echo esc_attr( $author->get_display_name() ); ?>"
					title="<?php echo esc_attr( $author->get_display_name() ); ?>"
				>
				<!-- Do not multiline below code, as it will create space around the display name. -->
				<span class="masteriyo-course-author--name"><?php echo esc_html( $author->get_display_name() ); ?></span>
			</a>
		</div>
		<?php
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

		$author = masteriyo_get_user( $course->get_author_id() );

		if ( ! $author ) {
			return;
		}

		?>
		<div class="masteriyo-course-author">
			<a href="<?php echo esc_url( $author->get_course_archive_url() ); ?>">
				<img src="<?php echo esc_attr( $author->profile_image_url() ); ?>"
					alt="<?php echo esc_attr( $author->get_display_name() ); ?>"
					title="<?php echo esc_attr( $author->get_display_name() ); ?>"
				>
				<!-- Do not multiline below code, as it will create space around the display name. -->
				<span class="masteriyo-course-author--name"><?php echo esc_html( $author->get_display_name() ); ?></span>
			</a>
		</div>
		<?php
	}
}
