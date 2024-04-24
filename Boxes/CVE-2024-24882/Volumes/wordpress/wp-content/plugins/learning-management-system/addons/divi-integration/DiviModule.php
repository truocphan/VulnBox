<?php
/**
 * Base class for a Divi module.
 *
 * @since 1.6.13
 */

namespace Masteriyo\Addons\DiviIntegration;

use Masteriyo\Query\CourseCategoryQuery;
use Masteriyo\Roles;
use Masteriyo\Taxonomy\Taxonomy;

defined( 'ABSPATH' ) || exit;

/**
 * Base class for a Divi module.
 *
 * @since 1.6.13
 */
abstract class DiviModule extends \ET_Builder_Module {

	/**
	 * Content tab slug.
	 *
	 * @since 1.6.13
	 *
	 * @var string
	 */
	const CONTENT_TAB = 'general';

	/**
	 * Design tab slug.
	 *
	 * @since 1.6.13
	 *
	 * @var string
	 */
	const DESIGN_TAB = 'advanced';

	/**
	 * CSS selector for the wrapper of the module.
	 *
	 * @since 1.6.13
	 *
	 * @var string
	 */
	const WRAPPER_SELECTOR = '%%order_class%%';

	/**
	 * Visual Builder support.
	 *
	 * @since 1.6.13
	 *
	 * @var string
	 */
	public $vb_support = 'on';

	/**
	 * Module Credits (Appears at the bottom of the module settings accordions).
	 *
	 * @since 1.6.13
	 *
	 * @var array
	 */
	protected $module_credits = array(
		'author'     => 'Masteriyo',
		'author_uri' => 'https://masteriyo.com/',
	);

	/**
	 * List of controls to allow module customization.
	 *
	 * @since 1.6.13
	 *
	 * @var array
	 */
	protected $setting_controls = array();

	/**
	 * Style templates for the module.
	 *
	 * @since 1.6.13
	 *
	 * @var array
	 */
	protected $style_templates = array();

	/**
	 * Marked special settings that has a different value format.
	 *
	 * @since 1.6.13
	 *
	 * @var array
	 */
	protected $special_settings = array();

	/**
	 * Get the fields/controls configurations.
	 *
	 * @since 1.6.13
	 *
	 * @return array
	 */
	public function get_fields() {
		return $this->setting_controls;
	}

	/**
	 * Get instructors options.
	 *
	 * @since 1.6.13
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
	 * @since 1.6.13
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
	 * @since 1.6.13
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
	 * Get all the course difficulty slugs.
	 *
	 * @since 1.6.13
	 *
	 * @return string[]
	 */
	public function get_all_difficulty_slugs() {
		$difficulties = $this->get_all_difficulties();
		$slugs        = array_map(
			function( $difficulty ) {
				return $difficulty->get_slug();
			},
			$difficulties
		);

		return $slugs;
	}

	/**
	 * Add a section to the Content tab.
	 *
	 * @since 1.6.13
	 *
	 * @param string $slug
	 * @param string $label
	 */
	public function add_section_to_content_tab( $slug, $label ) {
		$this->add_section( self::CONTENT_TAB, $slug, $label );
	}

	/**
	 * Add a section to the Design tab.
	 *
	 * @since 1.6.13
	 *
	 * @param string $slug
	 * @param string $label
	 */
	public function add_section_to_design_tab( $slug, $label ) {
		$this->add_section( self::DESIGN_TAB, $slug, $label );
	}

	/**
	 * Add a section to a specific tab.
	 *
	 * Tab slugs: Content Tab = `general`, Design Tab = `advanced`
	 *
	 * @since 1.6.13
	 *
	 * @param string $tab Tab slugs: Content Tab = `general`, Design Tab = `advanced`
	 * @param string $slug
	 * @param string $label
	 */
	public function add_section( $tab, $slug, $label ) {
		if ( empty( $this->settings_modal_toggles[ $tab ] ) ) {
			$this->settings_modal_toggles[ $tab ] = array( 'toggles' => array() );
		}

		if ( empty( $this->settings_modal_toggles[ $tab ]['toggles'] ) ) {
			$this->settings_modal_toggles[ $tab ]['toggles'] = array();
		}

		$this->settings_modal_toggles[ $tab ]['toggles'][ $slug ] = $label;
	}

	/**
	 * Add a control.
	 *
	 * @since 1.6.13
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function add_control( $name, $args ) {
		if ( empty( $name ) || empty( $args ) || ! is_array( $args ) ) {
			return;
		}

		$this->setting_controls[ $name ] = $args;
	}

	/**
	 * Add a computed control.
	 *
	 * @since 1.6.13
	 *
	 * @param string $name
	 * @param string $callback_static_method
	 * @param array $dependency_props
	 */
	public function add_computed_control( $name, $callback_static_method, $dependency_props = array() ) {
		if ( empty( $name ) || empty( $callback_static_method ) || ! is_string( $callback_static_method ) ) {
			return;
		}

		$this->setting_controls[ $name ] = array(
			'type'                => 'computed',
			'computed_callback'   => array( static::class, $callback_static_method ),
			'computed_depends_on' => $dependency_props,
			'computed_minimum'    => $dependency_props,
		);
	}

	/**
	 * Add fonts control.
	 *
	 * @since 1.6.13
	 *
	 * @param string $name
	 * @param string $tab
	 * @param string $section
	 * @param string $selector
	 * @param true|string $hover_selector If true is passed, it will append ':hover' to the $selector. If string is passed, it's used as it is after prefixing wrapper placeholder.
	 */
	public function add_fonts_control( $name, $tab, $section, $selector, $hover_selector = '' ) {
		if ( empty( $name ) ) {
			return;
		}

		if ( ! isset( $this->advanced_fields['fonts'] ) ) {
			$this->advanced_fields['fonts'] = array();
		}

		if ( isset( $this->advanced_fields['fonts'][ $name ] ) ) {
			error_log( sprintf( 'Warning: Fonts control with the name "%s" is already registered in the %s module!', $name, $this->name ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}

		$args = array(
			'css'         => array(
				'main' => self::WRAPPER_SELECTOR . ' ' . $selector,
			),
			'tab_slug'    => $tab,
			'toggle_slug' => $section,
		);

		if ( true === $hover_selector ) {
			$args['css']['hover'] = self::WRAPPER_SELECTOR . ' ' . $selector . ':hover';
		} elseif ( is_string( $hover_selector ) && ! empty( $hover_selector ) ) {
			$args['css']['hover'] = self::WRAPPER_SELECTOR . ' ' . $hover_selector;
		}

		$this->advanced_fields['fonts'][ $name ] = $args;
	}

	/**
	 * Add borders control.
	 *
	 * @since 1.6.13
	 *
	 * @param string $name
	 * @param string $tab
	 * @param string $section
	 * @param string $selector
	 * @param true|string $hover_selector If true is passed, it will append ':hover' to the $selector. If string is passed, it's used as it is after prefixing wrapper placeholder.
	 */
	public function add_borders_control( $name, $tab, $section, $selector, $hover_selector = '' ) {
		if ( empty( $name ) ) {
			return;
		}

		if ( ! isset( $this->advanced_fields['borders'] ) ) {
			$this->advanced_fields['borders'] = array();
		}

		if ( isset( $this->advanced_fields['borders'][ $name ] ) ) {
			error_log( sprintf( 'Warning: Borders control with the name "%s" is already registered in the %s module!', $name, $this->name ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}

		$args = array(
			'css'         => array(
				'main'      => array(
					'border_radii'  => self::WRAPPER_SELECTOR . ' ' . $selector,
					'border_styles' => self::WRAPPER_SELECTOR . ' ' . $selector,
				),
				'important' => true,
			),
			'tab_slug'    => $tab,
			'toggle_slug' => $section,
		);

		if ( true === $hover_selector ) {
			$args['css']['hover'] = array(
				'border_radii'  => self::WRAPPER_SELECTOR . ' ' . $selector . ':hover',
				'border_styles' => self::WRAPPER_SELECTOR . ' ' . $selector . ':hover',
			);
		} elseif ( is_string( $hover_selector ) && ! empty( $hover_selector ) ) {
			$args['css']['hover'] = array(
				'border_radii'  => self::WRAPPER_SELECTOR . ' ' . $hover_selector,
				'border_styles' => self::WRAPPER_SELECTOR . ' ' . $hover_selector,
			);
		}

		$this->advanced_fields['borders'][ $name ] = $args;

		// Fix - Border style not being set by Divi when default value (i.e. 'solid') is selected.
		$this->add_style_template(
			array(
				'selector'    => self::WRAPPER_SELECTOR . ' ' . $selector,
				'declaration' => 'border-style: solid;',
			)
		);
	}

	/**
	 * Add box shadow control.
	 *
	 * @since 1.6.13
	 *
	 * @param string $name
	 * @param string $tab
	 * @param string $section
	 * @param string $selector
	 * @param true|string $hover_selector If true is passed, it will append ':hover' to the $selector. If string is passed, it's used as it is after prefixing wrapper placeholder.
	 */
	public function add_box_shadow_control( $name, $tab, $section, $selector, $hover_selector = '' ) {
		if ( empty( $name ) ) {
			return;
		}

		if ( ! isset( $this->advanced_fields['box_shadow'] ) ) {
			$this->advanced_fields['box_shadow'] = array();
		}

		if ( isset( $this->advanced_fields['box_shadow'][ $name ] ) ) {
			error_log( sprintf( 'Warning: Box shadow control with the name "%s" is already registered in the %s module!', $name, $this->name ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}

		$args = array(
			'css'         => array(
				'main' => self::WRAPPER_SELECTOR . ' ' . $selector,
			),
			'tab_slug'    => $tab,
			'toggle_slug' => $section,
		);

		if ( true === $hover_selector ) {
			$args['css']['hover'] = self::WRAPPER_SELECTOR . ' ' . $selector . ':hover';
		} elseif ( is_string( $hover_selector ) && ! empty( $hover_selector ) ) {
			$args['css']['hover'] = self::WRAPPER_SELECTOR . ' ' . $hover_selector;
		}

		$this->advanced_fields['box_shadow'][ $name ] = $args;
	}

	/**
	 * Add style controls for a text region.
	 *
	 * @since 1.6.13
	 *
	 * @param string $name_prefix
	 * @param string $tab
	 * @param string $section
	 * @param string $selector
	 * @param array $options
	 */
	protected function add_text_region_style_controls( $name_prefix, $tab, $section, $selector, $options = array() ) {
		$options                = wp_parse_args(
			$options,
			array(
				'custom_selectors'                 => array(),
				'custom_labels'                    => array(),
				'disable_fonts_control'            => false,
				'disable_borders_control'          => false,
				'disable_box_shadow_control'       => false,
				'disable_background_color_control' => false,
				'disable_padding_control'          => false,
				'disable_margin_control'           => false,
			)
		);
		$default_selector       = $selector;
		$default_hover_selector = true;
		$custom_selectors       = wp_parse_args(
			$options['custom_selectors'],
			array(
				'fonts_control'                  => $default_selector,
				'hover_fonts_control'            => $default_hover_selector,
				'borders_control'                => $default_selector,
				'hover_borders_control'          => $default_hover_selector,
				'box_shadow_control'             => $default_selector,
				'hover_box_shadow_control'       => $default_hover_selector,
				'background_color_control'       => $default_selector,
				'hover_background_color_control' => $default_hover_selector,
				'padding_control'                => $default_selector,
				'margin_control'                 => $default_selector,
			)
		);
		$custom_labels          = wp_parse_args(
			$options['custom_labels'],
			array(
				'background_color_control' => '',
			)
		);

		if ( ! $options['disable_fonts_control'] ) {
			$this->add_fonts_control(
				$name_prefix . 'fonts',
				$tab,
				$section,
				$custom_selectors['fonts_control'],
				$custom_selectors['hover_fonts_control']
			);
		}

		if ( ! $options['disable_borders_control'] ) {
			$this->add_borders_control(
				$name_prefix . 'borders',
				$tab,
				$section,
				$custom_selectors['borders_control'],
				$custom_selectors['hover_borders_control']
			);
		}

		if ( ! $options['disable_box_shadow_control'] ) {
			$this->add_box_shadow_control(
				$name_prefix . 'box_shadow',
				$tab,
				$section,
				$custom_selectors['box_shadow_control'],
				$custom_selectors['hover_box_shadow_control']
			);
		}

		if ( ! $options['disable_background_color_control'] ) {
			$this->add_color_control(
				$name_prefix . 'background_color',
				$tab,
				$section,
				$custom_selectors['background_color_control'],
				array(
					'label'          => empty( $custom_labels['background_color_control'] ) ? __( 'Background Color', 'masteriyo' ) : $custom_labels['background_color_control'],
					'hover_selector' => $custom_selectors['hover_background_color_control'],
					'css'            => 'background-color: {{' . $name_prefix . 'background_color}};',
					'hover_css'      => 'background-color: {{' . $name_prefix . 'background_color__hover}};',
				)
			);
		}

		if ( ! $options['disable_padding_control'] ) {
			$this->add_padding_control(
				$name_prefix . 'padding',
				$tab,
				$section,
				$custom_selectors['padding_control']
			);
		}

		if ( ! $options['disable_margin_control'] ) {
			$this->add_margin_control(
				$name_prefix . 'margin',
				$tab,
				$section,
				$custom_selectors['margin_control']
			);
		}
	}

	/**
	 * Add a color input control.
	 *
	 * @since 1.6.13
	 *
	 * @param string $name
	 * @param string $tab
	 * @param string $section
	 * @param string $selector
	 * @param array $options
	 */
	public function add_color_control( $name, $tab, $section, $selector, $options = array() ) {
		$options = wp_parse_args(
			$options,
			array(
				'label'          => esc_html__( 'Color', 'masteriyo' ),
				'enable_hover'   => true,
				'selector'       => self::WRAPPER_SELECTOR . ' ' . $selector,
				'hover_selector' => self::WRAPPER_SELECTOR . ' ' . $selector . ':hover',
				'css'            => '',
				'hover_css'      => '',
			)
		);
		$args    = array(
			'label'       => $options['label'],
			'type'        => 'color-alpha',
			'tab_slug'    => $tab,
			'toggle_slug' => $section,
		);

		if ( true === $options['enable_hover'] ) {
			$args['hover'] = 'tabs';
		}

		$this->setting_controls[ $name ] = $args;

		$this->add_style_template(
			array(
				'selector'    => $options['selector'],
				'declaration' => $options['css'],
				'dynamic'     => true,
			)
		);
		$this->add_style_template(
			array(
				'selector'    => $options['hover_selector'],
				'declaration' => $options['hover_css'],
				'dynamic'     => true,
			)
		);
	}

	/**
	 * Add a color input control.
	 *
	 * @since 1.6.13
	 *
	 * @param string $name
	 * @param string $tab
	 * @param string $section
	 * @param array $selectors
	 * @param array $options
	 */
	public function add_range_control( $name, $tab, $section, $selectors, $options = array() ) {
		$options = wp_parse_args(
			$options,
			array(
				'label'        => esc_html__( 'Range', 'masteriyo' ),
				'default_unit' => 'px',
				'min'          => '0',
				'max'          => '300',
				'step'         => '1',
				'css'          => '',
			)
		);
		$args    = array(
			'label'          => $options['label'],
			'type'           => 'range',
			'tab_slug'       => $tab,
			'toggle_slug'    => $section,
			'default_unit'   => $options['default_unit'],
			'default_unit'   => $options['default_unit'],
			'range_settings' => array(
				'min'  => $options['min'],
				'max'  => $options['max'],
				'step' => $options['step'],
			),
		);

		$this->setting_controls[ $name ] = $args;

		foreach ( $selectors as $selector => $css ) {
			$this->add_style_template(
				array(
					'selector'    => self::WRAPPER_SELECTOR . ' ' . $selector,
					'declaration' => $css,
					'dynamic'     => true,
				)
			);
		}
	}

	/**
	 * Add a padding input control.
	 *
	 * @since 1.6.13
	 *
	 * @param string $name
	 * @param string $tab
	 * @param string $section
	 * @param string $selector
	 * @param array $options
	 */
	public function add_padding_control( $name, $tab, $section, $selector, $options = array() ) {
		$options = wp_parse_args(
			$options,
			array(
				'label'      => esc_html__( 'Padding', 'masteriyo' ),
				'selector'   => self::WRAPPER_SELECTOR . ' ' . $selector,
				'responsive' => true,
			)
		);
		$args    = array(
			'label'          => $options['label'],
			'type'           => 'custom_padding',
			'tab_slug'       => $tab,
			'toggle_slug'    => $section,
			'mobile_options' => $options['responsive'],
		);

		$this->setting_controls[ $name ] = $args;

		$this->add_style_template(
			array(
				'selector'    => $options['selector'],
				'declaration' => sprintf(
					'padding-top: {{%s.TOP}} !important;' .
					'padding-right: {{%s.RIGHT}}!important;' .
					'padding-bottom: {{%s.BOTTOM}} !important;' .
					'padding-left: {{%s.LEFT}} !important;',
					$name,
					$name,
					$name,
					$name
				),
				'dynamic'     => true,
			)
		);

		$this->mark_special_setting_value( $name, 'padding' );
	}

	/**
	 * Add a margin input control.
	 *
	 * @since 1.6.13
	 *
	 * @param string $name
	 * @param string $tab
	 * @param string $section
	 * @param string $selector
	 * @param array $options
	 */
	public function add_margin_control( $name, $tab, $section, $selector, $options = array() ) {
		$options = wp_parse_args(
			$options,
			array(
				'label'      => esc_html__( 'Margin', 'masteriyo' ),
				'selector'   => self::WRAPPER_SELECTOR . ' ' . $selector,
				'responsive' => true,
			)
		);
		$args    = array(
			'label'          => $options['label'],
			'type'           => 'custom_margin',
			'tab_slug'       => $tab,
			'toggle_slug'    => $section,
			'mobile_options' => $options['responsive'],
		);

		$this->setting_controls[ $name ] = $args;

		$this->add_style_template(
			array(
				'selector'    => $options['selector'],
				'declaration' => sprintf(
					'margin-top: {{%s.TOP}} !important;' .
					'margin-right: {{%s.RIGHT}}!important;' .
					'margin-bottom: {{%s.BOTTOM}} !important;' .
					'margin-left: {{%s.LEFT}} !important;',
					$name,
					$name,
					$name,
					$name
				),
				'dynamic'     => true,
			)
		);

		$this->mark_special_setting_value( $name, 'margin' );
	}

	/**
	 * Add a show/hide toggle control.
	 *
	 * @since 1.6.13
	 *
	 * @param string $name
	 * @param string $label
	 * @param string $tab
	 * @param string $section
	 * @param string $selector
	 * @param array $options
	 */
	public function add_show_hide_control( $name, $label, $tab, $section, $selector, $options = array() ) {
		$options = wp_parse_args(
			$options,
			array(
				'label'       => $label,
				'default'     => 'on',
				'options'     => array(
					'on'  => esc_html__( 'Show', 'masteriyo' ),
					'off' => esc_html__( 'Hide', 'masteriyo' ),
				),
				'selector'    => self::WRAPPER_SELECTOR . ' ' . $selector,
				'show_if'     => array(),
				'description' => '',
			)
		);
		$args    = array(
			'label'       => $options['label'],
			'type'        => 'yes_no_button',
			'tab_slug'    => $tab,
			'toggle_slug' => $section,
			'show_if'     => $options['show_if'],
			'options'     => $options['options'],
			'default'     => $options['default'],
			'description' => $options['description'],
		);

		$this->setting_controls[ $name ] = $args;

		$this->add_style_template(
			array(
				'selector'    => $options['selector'],
				'declaration' => 'display: none !important;',
				'condition'   => array(
					'relation'   => 'OR',
					'conditions' => array(
						array(
							'setting_name' => $name,
							'compare'      => '=',
							'value'        => 'off',
						),
					),
				),
			)
		);
	}

	/**
	 * Add style template for this module.
	 *
	 *
	 * Format:
	 * [
	 *   selector => '%%order_class%% .some-selector',
	 *   declaration => 'css-property: value; ...',
	 * ]
	 *
	 * @since 1.6.13
	 *
	 * @param array $style_template
	 */
	public function add_style_template( $style_template ) {
		if ( ! is_array( $style_template ) || empty( $style_template ) ) {
			return;
		}

		$this->style_templates[] = $style_template;

		$add_scripts = function() use ( $style_template ) {
			$var                  = '_MASTERIYO_STYLE_TEMPLATES_["' . $this->slug . '"]';
			$create_template_list = sprintf( '%s = Array.isArray(%s) ? %s : [];', $var, $var, $var );
			$push_template        = sprintf( '%s.push(%s);', $var, wp_json_encode( $style_template ) );

			wp_add_inline_script( 'masteriyo-divi-integration', $create_template_list );
			wp_add_inline_script( 'masteriyo-divi-integration', $push_template );
		};

		if ( did_action( 'wp_enqueue_scripts' ) ) {
			$add_scripts();
		} else {
			add_action( 'wp_enqueue_scripts', $add_scripts, 999 );
		}
	}

	/**
	 * Mark a setting as special which has a different format of value.
	 * For example, padding and margin control values are strings that contains
	 * values of four sides separated by the pipe (|) character.
	 *
	 * @since 1.6.13
	 *
	 * @param string $setting_name
	 * @param string $type 'padding', 'margin', etc.
	 */
	public function mark_special_setting_value( $setting_name, $type ) {
		if ( empty( $setting_name ) ) {
			return;
		}

		$this->special_settings[ $setting_name ] = $type;

		$add_scripts = function() use ( $setting_name, $type ) {
			$var    = '_MASTERIYO_SPECIAL_SETTINGS_["' . $setting_name . '"]';
			$script = sprintf( '%s = "%s";', $var, $type );

			wp_add_inline_script( 'masteriyo-divi-integration', $script );
		};

		if ( did_action( 'wp_enqueue_scripts' ) ) {
			$add_scripts();
		} else {
			add_action( 'wp_enqueue_scripts', $add_scripts, 999 );
		}
	}

	/**
	 * Generate module styles for the frontend.
	 *
	 * @since 1.6.13
	 *
	 * @param string $render_slug
	 * @param array|null $props
	 */
	protected function generate_module_styles( $render_slug, $props = null ) {
		if ( ! is_array( $props ) ) {
			$props = $this->props;
		}
		$styles = $this->process_style_templates( $props );

		foreach ( $styles as $style ) {
			self::set_style( $render_slug, $style );
		}
	}

	/**
	 * Process style templates of the module and return an array of styles.
	 *
	 * @since 1.6.13
	 *
	 * @param array $props
	 *
	 * @return array
	 */
	protected function process_style_templates( $props ) {
		$style_templates  = $this->style_templates;
		$special_settings = $this->special_settings;

		foreach ( $special_settings as $setting_name => $type ) {
			if ( 'padding' === $type || 'margin' === $type ) {
				if ( ! isset( $props[ $setting_name ] ) ) {
					continue;
				}

				$value = $props[ $setting_name ];

				if ( is_string( $value ) && ! empty( $value ) ) {
					$split_values = explode( '|', $value );

					$props[ $setting_name . '.TOP' ]    = $split_values[0];
					$props[ $setting_name . '.RIGHT' ]  = $split_values[1];
					$props[ $setting_name . '.BOTTOM' ] = $split_values[2];
					$props[ $setting_name . '.LEFT' ]   = $split_values[3];
				}
			}
		}

		$styles = array();

		foreach ( $style_templates as $style ) {
			$prepared_style = array(
				'selector'    => $style['selector'],
				'declaration' => $style['declaration'],
			);

			if (
				! empty( $style['condition'] ) &&
				isset( $style['condition']['conditions'] ) &&
				is_array( $style['condition']['conditions'] )
			) {
				$conditions         = (array) $style['condition']['conditions'];
				$conditions_length  = count( $conditions );
				$relation           = empty( $style['condition']['relation'] ) ? 'OR' : $style['condition']['relation'];
				$relation           = strtoupper( $relation );
				$skip_this_template = false;

				for ( $i = 0; $i < $conditions_length; $i++ ) {
					if ( empty( $conditions[ $i ] ) ) {
						continue;
					}

					$condition     = $conditions[ $i ];
					$setting_name  = $condition['setting_name'];
					$compare       = $condition['compare'];
					$value         = $condition['value'];
					$condition_met = false;

					switch ( $compare ) {
						case '__empty__':
							$condition_met = empty( $props[ $setting_name ] );
							break;

						case '__not_empty__':
							$condition_met = ! empty( $props[ $setting_name ] );
							break;

						case '!=':
							$condition_met = $props[ $setting_name ] != $value;
							break;

						case '=':
						default:
							$condition_met = $props[ $setting_name ] == $value;
							break;
					}

					if ( 'AND' === $relation ) {
						if ( ! $condition_met ) {
							$skip_this_template = true;
							break;
						}
					} elseif ( 'OR' === $relation ) {
						if ( $condition_met ) {
							break;
						} elseif ( count( $conditions ) - 1 === $i ) {
							$skip_this_template = true;
							break;
						}
					}
				}

				if ( $skip_this_template ) {
					continue;
				}
			}

			if ( isset( $style['dynamic'] ) && $style['dynamic'] ) {
				if ( empty( $style['declaration'] ) || ! is_string( $style['declaration'] ) ) {
					continue;
				}

				preg_match_all( '/{{([A-Za-z\_\.]+)}}/', $style['declaration'], $matches, PREG_SET_ORDER );

				if ( is_array( $matches ) ) {
					foreach ( $matches as $match ) {
						$setting_name = trim( $match[1] );
						$value        = empty( $props[ $setting_name ] ) ? '' : $props[ $setting_name ];

						$prepared_style['declaration'] = str_replace( $match[0], $value, $prepared_style['declaration'] );
					}
				}

				$styles[] = $prepared_style;
			} else {
				$styles[] = $prepared_style;
			}
		}

		return $styles;
	}
}
