<?php
/**
 * Base class for Oxygen builder element.
 *
 * @since 1.6.16
 */

namespace Masteriyo\Addons\OxygenIntegration;

use Masteriyo\Taxonomy\Taxonomy;

/**
 * Base class for Oxygen builder element.
 *
 * @since 1.6.16
 */
abstract class OxygenElement extends \OxyEl {

	/**
	 * Returns element category.
	 *
	 * @since 1.6.16
	 *
	 * @return string
	 */
	public function button_place() {
		return 'masteriyo::general';
	}

	/**
	 * Remove prefixes from option names.
	 *
	 * @since 1.6.16
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	public function clean_up_option_names( $options ) {
		$new_options = array();

		foreach ( $options as $key => $value ) {
			$prefix  = 'oxy-' . $this->slug() . '_';
			$new_key = $key;

			if ( str_starts_with( $key, $prefix ) ) {
				$new_key = preg_replace( '/^' . $prefix . '/', '', $key );
			}

			$new_options[ $new_key ] = $value;
		}

		return $new_options;
	}

	/**
	 * Add a toggle control in the given section.
	 *
	 * @since 1.6.16
	 *
	 * @param \OxygenElementControlsSection $section
	 * @param string $option_name
	 * @param string $option_label
	 * @param array $args
	 */
	public function add_toggle_control( $section, $option_name, $option_label, $args = array() ) {
		$args         = wp_parse_args(
			$args,
			array(
				'default_on' => true,
				'condition'  => null,
				'selector'   => '',
			)
		);
		$control_args = array(
			'type'    => 'dropdown',
			'name'    => $option_label,
			'slug'    => $option_name,
			'default' => $args['default_on'] ? 'yes' : 'no',
		);

		if ( ! empty( $args['condition'] ) ) {
			$control_args['condition'] = $args['condition'];
		}

		$option = $section->addOptionControl( $control_args );
		$option->setValue( array( 'yes', 'no' ) );

		if ( ! empty( $args['selector'] ) && is_string( $args['selector'] ) ) {
			$option->setValueCSS(
				array(
					'no' =>
					$args['selector'] . '{display: none;}',
				)
			);
		}
	}

	/**
	 * Get all the course difficulties.
	 *
	 * @since 1.6.16
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
	 * Add a separator / horizontal line in a section.
	 *
	 * @since 1.6.16
	 *
	 * @param \OxygenElementControlsSection $section
	 */
	protected function add_separator_in_section( $section ) {
		$section->addOptionControl(
			array(
				'name'  => '',
				'text'  => '',
				'class' => '',
				'slug'  => '',
				'type'  => 'label',
			)
		);
	}

	/**
	 * Add a text as a title in a section.
	 *
	 * @since 1.6.16
	 *
	 * @param \OxygenElementControlsSection $section
	 * @param string $title
	 */
	protected function add_title_in_section( $section, $title ) {
		$section->addOptionControl(
			array(
				'name'  => $title,
				'type'  => 'text',
				'text'  => '',
				'class' => '',
				'slug'  => '',
			)
		);
	}

	/**
	 * Add style controls for a container region.
	 *
	 * @since 1.6.16
	 *
	 * @param \OxygenElementControlsSection $section
	 * @param string $name
	 * @param string $selector
	 */
	public function add_container_style_controls_in_section( $section, $name, $selector ) {
		$section->borderSection(
			__( 'Border', 'masteriyo' ),
			$selector,
			$this
		);

		$section->addPreset(
			'padding',
			$name . '_padding',
			__( 'Padding', 'masteriyo' ),
			$selector
		);

		$section->addPreset(
			'margin',
			$name . '_margin',
			__( 'Margin', 'masteriyo' ),
			$selector
		);

		$section->addStyleControl(
			array(
				'name'         => __( 'Background Color', 'masteriyo' ),
				'selector'     => $selector,
				'property'     => 'background-color',
				'control_type' => 'colorpicker',
				'slug'         => $name . '_background_color',
			)
		);

		$section->boxShadowSection(
			__( 'Box Shadow', 'masteriyo' ),
			$selector,
			$this
		);
	}

	/**
	 * Add style controls for a text region.
	 *
	 * @since 1.6.16
	 *
	 * @param \OxygenElementControlsSection $section
	 * @param string $name
	 * @param string $selector
	 * @param array $args
	 */
	public function add_text_region_style_controls_in_section( $section, $name, $selector, $args = array() ) {
		$args      = wp_parse_args(
			$args,
			array(
				'hide_text_color' => false,
				'selectors'       => array(),
			)
		);
		$selectors = wp_parse_args(
			$args['selectors'],
			array(
				'text_color' => '',
				'typography' => $selector,
			)
		);

		$section->typographySection(
			__( 'Typography', 'masteriyo' ),
			$selectors['typography'],
			$this
		);

		$section->borderSection(
			__( 'Border', 'masteriyo' ),
			$selector,
			$this
		);

		$section->addPreset(
			'padding',
			$name . '_padding',
			__( 'Padding', 'masteriyo' ),
			$selector
		);

		$section->addPreset(
			'margin',
			$name . '_margin',
			__( 'Margin', 'masteriyo' ),
			$selector
		);

		if ( ! $args['hide_text_color'] && ! empty( $selectors['text_color'] ) ) {
			$section->addStyleControl(
				array(
					'name'         => __( 'Text Color', 'masteriyo' ),
					'selector'     => $selectors['text_color'],
					'property'     => 'color',
					'control_type' => 'colorpicker',
					'slug'         => $name . '_text_color',
				)
			);
		}

		$section->addStyleControl(
			array(
				'name'         => __( 'Background Color', 'masteriyo' ),
				'selector'     => $selector,
				'property'     => 'background-color',
				'control_type' => 'colorpicker',
				'slug'         => $name . '_background_color',
			)
		);

		$section->boxShadowSection(
			__( 'Box Shadow', 'masteriyo' ),
			$selector,
			$this
		);
	}
}
