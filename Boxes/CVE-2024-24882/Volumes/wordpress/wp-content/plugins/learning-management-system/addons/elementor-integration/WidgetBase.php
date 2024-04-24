<?php
/**
 * Base class for Masteriyo elementor widget.
 *
 * @package Masteriyo\Addons\ElementorIntegration
 *
 * @since 1.6.12
 */

namespace Masteriyo\Addons\ElementorIntegration;

use Masteriyo\Query\CourseCategoryQuery;
use Masteriyo\Roles;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Masteriyo\Taxonomy\Taxonomy;

defined( 'ABSPATH' ) || exit;

/**
 * Base class for Masteriyo elementor widget.
 *
 * @package Masteriyo\Addons\ElementorIntegration
 *
 * @since 1.6.12
 */
abstract class WidgetBase extends Widget_Base {

	/**
	 * Get widget categories.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_categories() {
		return array( 'masteriyo' );
	}

	/**
	 * Overriding default function to add custom html class.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_html_wrapper_class() {
		$html_class  = parent::get_html_wrapper_class();
		$html_class .= " masteriyo-elementor-widget {$this->get_name()}-widget";
		return rtrim( $html_class );
	}

	/**
	 * Register widget controls.
	 *
	 * @since 1.6.12
	 */
	protected function register_controls() {
		/**
		 * Fires before registering elementor widget controls.
		 *
		 * @since 1.6.12
		 *
		 * @param \Masteriyo\Addons\ElementorIntegration\WidgetBase $widget
		 */
		do_action( 'masteriyo_elementor_integration_widget_before_register_controls', $this );

		$this->register_content_controls();
		$this->register_style_controls();

		/**
		 * Fires after registering elementor widget controls.
		 *
		 * @since 1.6.12
		 *
		 * @param \Masteriyo\Addons\ElementorIntegration\WidgetBase $widget
		 */
		do_action( 'masteriyo_elementor_integration_widget_after_register_controls', $this );
	}

	/**
	 * Register controls configuring widget content.
	 *
	 * @since 1.6.12
	 */
	abstract protected function register_content_controls();

	/**
	 * Register controls for customizing widget styles.
	 *
	 * @since 1.6.12
	 */
	abstract protected function register_style_controls();

	/**
	 * Add on/off switch control. It allows you to apply selectors when the switch control is off.
	 *
	 * @since 1.6.12
	 *
	 * @param string $name Control name.
	 * @param string $label Control label.
	 * @param array $args Arguments to be passed to the add_control method.
	 * @param array $off_state_selectors Selectors to apply when the control is in off state.
	 * @param array $options Optional. Control options. Default is an empty array.
	 */
	public function add_on_off_switch_control( $name, $label, $args = array(), $off_state_selectors = array(), $options = array() ) {
		$this->add_control(
			$name,
			wp_parse_args(
				$args,
				array(
					'label'        => $label,
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'masteriyo' ),
					'label_off'    => __( 'Hide', 'masteriyo' ),
					'default'      => 'yes',
					'return_value' => 'yes',
					'selectors'    => array( '' => '' ),
				)
			),
			$options
		);

		$this->add_control(
			$name . '_off_state_css',
			array(
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'off-state',
				'condition' => array(
					$name . '!' => 'yes',
				),
				'selectors' => $off_state_selectors,
			)
		);
	}

	/**
	 * Get instructors options.
	 *
	 * @since 1.6.12
	 *
	 * @return array
	 */
	public function get_instructors_options() {
		$args          = array(
			'role__in' => array( Roles::INSTRUCTOR, Roles::ADMIN ),
			'order'    => 'ASC',
			'orderby'  => 'display_name',
			'number'   => '',
		);
		$wp_user_query = new \WP_User_Query( $args );
		$authors       = $wp_user_query->get_results();

		return array_reduce(
			$authors,
			function( $options, $author ) {
				$options[ $author->ID ] = $author->display_name;
				return $options;
			},
			array()
		);
	}

	/**
	 * Get categories options.
	 *
	 * @since 1.6.12
	 *
	 * @return array
	 */
	public function get_categories_options() {
		$args       = array(
			'order'   => 'ASC',
			'orderby' => 'name',
			'number'  => '',
		);
		$query      = new CourseCategoryQuery( $args );
		$categories = $query->get_categories();

		return array_reduce(
			$categories,
			function( $options, $category ) {
				$options[ $category->get_id() ] = $category->get_name();
				return $options;
			},
			array()
		);
	}

	/**
	 * Get all the course difficulties.
	 *
	 * @since 1.6.12
	 *
	 * @return \Masteriyo\Models\CourseDifficulty[]
	 */
	protected function get_all_difficulties() {
		$args      = array(
			'taxonomy'   => Taxonomy::COURSE_DIFFICULTY,
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
			'number'     => '',
		);
		$the_query = new \WP_Term_Query( $args );

		return array_filter( array_map( 'masteriyo_get_course_difficulty', $the_query->get_terms() ) );
	}

	/**
	 * Get course to render.
	 *
	 * @since 1.6.12
	 *
	 * @return \Masteriyo\Models\Course|null
	 */
	protected function get_course_to_render() {
		$course = isset( $GLOBALS['course'] ) ? $GLOBALS['course'] : null;

		if ( Helper::is_elementor_editor() || Helper::is_elementor_preview() ) {
			$course = Helper::get_elementor_preview_course();
		}
		return $course;
	}

	/**
	 * Add style controls for a text region.
	 *
	 * @since 1.6.12
	 *
	 * @param string $name_prefix
	 * @param string $selector
	 * @param array $options
	 */
	protected function add_text_region_style_controls( $name_prefix = '', $selector = '', $options = array() ) {
		$options                = wp_parse_args(
			$options,
			array(
				'custom_selectors'         => array(),
				'normal_state_start'       => null,
				'normal_state_end'         => null,
				'hover_state_start'        => null,
				'hover_state_end'          => null,
				'disable_align'            => false,
				'disable_typography'       => false,
				'disable_text_color'       => false,
				'disable_background_color' => false,
				'disable_border'           => false,
				'disable_border_radius'    => false,
				'disable_padding'          => false,
				'disable_margin'           => false,
				'disable_text_shadow'      => false,
				'disable_box_shadow'       => false,
			)
		);
		$default_selector       = '{{WRAPPER}} ' . $selector;
		$default_hover_selector = '{{WRAPPER}} ' . $selector . ':hover';
		$custom_selectors       = wp_parse_args(
			$options['custom_selectors'],
			array(
				'text_align'             => $default_selector,
				'hover_text_align'       => $default_hover_selector,
				'typography'             => $default_selector,
				'hover_typography'       => $default_hover_selector,
				'text_color'             => $default_selector,
				'hover_text_color'       => $default_hover_selector,
				'background_color'       => $default_selector,
				'hover_background_color' => $default_hover_selector,
				'border'                 => $default_selector,
				'hover_border'           => $default_hover_selector,
				'border_radius'          => $default_selector,
				'hover_border_radius'    => $default_hover_selector,
				'padding'                => $default_selector,
				'hover_padding'          => $default_hover_selector,
				'margin'                 => $default_selector,
				'hover_margin'           => $default_hover_selector,
				'text_shadow'            => $default_selector,
				'hover_text_shadow'      => $default_hover_selector,
				'box_shadow'             => $default_selector,
				'hover_box_shadow'       => $default_hover_selector,
			)
		);

		$this->start_controls_tabs( $name_prefix . 'text_region_style_tabs' );

		// Normal state styles.
		$this->start_controls_tab(
			$name_prefix . 'text_region_normal_state_style_tab',
			array(
				'label' => __( 'Normal', 'masteriyo' ),
			)
		);

		if ( is_callable( $options['normal_state_start'] ) ) {
			call_user_func( $options['normal_state_start'] );
		}

		if ( ! $options['disable_align'] ) {
			$this->add_responsive_control(
				$name_prefix . 'text_align',
				array(
					'label'     => esc_html__( 'Alignment', 'masteriyo' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'left'   => array(
							'title' => esc_html__( 'Left', 'masteriyo' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'masteriyo' ),
							'icon'  => 'eicon-text-align-center',
						),
						'right'  => array(
							'title' => esc_html__( 'Right', 'masteriyo' ),
							'icon'  => 'eicon-text-align-right',
						),
					),
					'toggle'    => true,
					'selectors' => array(
						$custom_selectors['text_align'] => 'text-align: {{VALUE}};',
					),
				)
			);
		}

		if ( ! $options['disable_typography'] ) {
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => $name_prefix . 'typography',
					'selector' => $custom_selectors['typography'],
				)
			);
		}

		if ( ! $options['disable_text_color'] ) {
			$this->add_control(
				$name_prefix . 'text_color',
				array(
					'label'     => __( 'Text Color', 'masteriyo' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						$custom_selectors['text_color'] => 'color: {{VALUE}} !important;',
					),
				)
			);
		}

		if ( ! $options['disable_background_color'] ) {
			$this->add_control(
				$name_prefix . 'background_color',
				array(
					'label'     => __( 'Background Color', 'masteriyo' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						$custom_selectors['background_color'] => 'background-color: {{VALUE}} !important;',
					),
				)
			);
		}

		$this->add_control(
			$name_prefix . 'popover_toggle_for_border_styles',
			array(
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label'        => esc_html__( 'Border', 'masteriyo' ),
				'label_off'    => esc_html__( 'Default', 'masteriyo' ),
				'label_on'     => esc_html__( 'Custom', 'masteriyo' ),
				'return_value' => 'yes',
			)
		);

		$this->start_popover();

		if ( ! $options['disable_border'] ) {
			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => $name_prefix . 'border',
					'label'    => __( 'Border', 'masteriyo' ),
					'selector' => $custom_selectors['border'],
				)
			);
		}

		if ( ! $options['disable_border_radius'] ) {
			$this->add_control(
				$name_prefix . 'border_radius',
				array(
					'label'      => __( 'Border Radius', 'masteriyo' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						$custom_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					),
				)
			);
		}

		$this->end_popover();

		if ( ! $options['disable_padding'] ) {
			$this->add_control(
				$name_prefix . 'padding',
				array(
					'label'      => __( 'Padding', 'masteriyo' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						$custom_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					),
				)
			);
		}

		if ( ! $options['disable_margin'] ) {
			$this->add_control(
				$name_prefix . 'margin',
				array(
					'label'      => __( 'Margin', 'masteriyo' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						$custom_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					),
				)
			);
		}

		if ( ! $options['disable_text_shadow'] ) {
			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				array(
					'name'     => $name_prefix . 'text_shadow',
					'label'    => __( 'Text Shadow', 'masteriyo' ),
					'selector' => $custom_selectors['text_shadow'],
				)
			);
		}

		if ( ! $options['disable_box_shadow'] ) {
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => $name_prefix . 'box_shadow',
					'label'    => __( 'Box Shadow', 'masteriyo' ),
					'selector' => $custom_selectors['box_shadow'],
				)
			);
		}

		if ( is_callable( $options['normal_state_end'] ) ) {
			call_user_func( $options['normal_state_end'] );
		}

		$this->end_controls_tab();

		// Hover state styles.
		$this->start_controls_tab(
			$name_prefix . 'text_region_hover_state_style_tab',
			array(
				'label' => __( 'Hover', 'masteriyo' ),
			)
		);

		if ( is_callable( $options['hover_state_start'] ) ) {
			call_user_func( $options['hover_state_start'] );
		}

		if ( ! $options['disable_align'] ) {
			$this->add_responsive_control(
				$name_prefix . 'hover_text_align',
				array(
					'label'     => esc_html__( 'Alignment', 'masteriyo' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'left'   => array(
							'title' => esc_html__( 'Left', 'masteriyo' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'masteriyo' ),
							'icon'  => 'eicon-text-align-center',
						),
						'right'  => array(
							'title' => esc_html__( 'Right', 'masteriyo' ),
							'icon'  => 'eicon-text-align-right',
						),
					),
					'toggle'    => true,
					'selectors' => array(
						$custom_selectors['hover_text_align'] => 'text-align: {{VALUE}};',
					),
				)
			);
		}

		if ( ! $options['disable_typography'] ) {
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => $name_prefix . 'hover_typography',
					'selector' => $custom_selectors['hover_typography'],
				)
			);
		}

		if ( ! $options['disable_text_color'] ) {
			$this->add_control(
				$name_prefix . 'hover_text_color',
				array(
					'label'     => __( 'Text Color', 'masteriyo' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						$custom_selectors['hover_text_color'] => 'color: {{VALUE}} !important;',
					),
				)
			);
		}

		if ( ! $options['disable_background_color'] ) {
			$this->add_control(
				$name_prefix . 'hover_background_color',
				array(
					'label'     => __( 'Background Color', 'masteriyo' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						$custom_selectors['hover_background_color'] => 'background-color: {{VALUE}} !important;',
					),
				)
			);
		}

		$this->add_control(
			$name_prefix . 'hover_popover_toggle_for_border_styles',
			array(
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label'        => esc_html__( 'Border', 'masteriyo' ),
				'label_off'    => esc_html__( 'Default', 'masteriyo' ),
				'label_on'     => esc_html__( 'Custom', 'masteriyo' ),
				'return_value' => 'yes',
			)
		);

		$this->start_popover();

		if ( ! $options['disable_border'] ) {
			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => $name_prefix . 'hover_border',
					'label'    => __( 'Border', 'masteriyo' ),
					'selector' => $custom_selectors['hover_border'],
				)
			);
		}

		if ( ! $options['disable_border_radius'] ) {
			$this->add_control(
				$name_prefix . 'hover_border_radius',
				array(
					'label'      => __( 'Border Radius', 'masteriyo' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						$custom_selectors['hover_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					),
				)
			);
		}

		$this->end_popover();

		if ( ! $options['disable_padding'] ) {
			$this->add_control(
				$name_prefix . 'hover_padding',
				array(
					'label'      => __( 'Padding', 'masteriyo' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						$custom_selectors['hover_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					),
				)
			);
		}

		if ( ! $options['disable_margin'] ) {
			$this->add_control(
				$name_prefix . 'hover_margin',
				array(
					'label'      => __( 'Margin', 'masteriyo' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						$custom_selectors['hover_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					),
				)
			);
		}

		if ( ! $options['disable_text_shadow'] ) {
			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				array(
					'name'     => $name_prefix . 'hover_text_shadow',
					'label'    => __( 'Text Shadow', 'masteriyo' ),
					'selector' => $custom_selectors['hover_text_shadow'],
				)
			);
		}

		if ( ! $options['disable_box_shadow'] ) {
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => $name_prefix . 'hover_box_shadow',
					'label'    => __( 'Box Shadow', 'masteriyo' ),
					'selector' => $custom_selectors['box_shadow'],
				)
			);
		}

		if ( is_callable( $options['hover_state_end'] ) ) {
			call_user_func( $options['hover_state_end'] );
		}

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}
}
