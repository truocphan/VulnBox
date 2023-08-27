<?php
/**
 * Class for extending acf_fields and adding new control as 'widget_area'.
 *
 * @link https://www.advancedcustomfields.com/resources/creating-a-new-field-type/
 *
 * @package JupiterX\Framework\Admin\Custom_Fields
 *
 * @since 1.0.0
 */

/**
 * The widget_area Custom-field class
 *
 * @since   1.0.0
 *
 * @package JupiterX\Framework\Admin\Custom_Fields
 */
class JupiterX_Field_Widget_Area extends acf_field {

	/**
	 * Class constructor that sets needed properties for class
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {

		// name (string) Single word, no spaces. Underscores allowed.
		$this->name = 'widget_area';

		// label (string) Multiple words, can include spaces, visible when selecting a field type.
		$this->label = __( 'Widget Area', 'jupiterx' );

		// category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME.
		$this->category = 'relational';

		// defaults (array) Array of default settings which are merged into the field object. These are used later in settings.
		$this->defaults = array(
			'default_value' => '',
		);

		// l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via: var message = acf._e('widget_area', 'error');.
		$this->l10n = array(
			'error' => __( 'Error! Please select a valid widget area.', 'jupiterx' ),
		);

		parent::__construct();

	}

	/**
	 * Create extra options for your field. This is rendered when editing a field.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $field - an array holding all the field's data.
	 *
	 * @return void
	 */
	public function render_field_settings( $field ) {
		acf_render_field_setting( $field, array(
			'label'        => __( 'Default Value', 'jupiterx' ),
			'instructions' => __( 'Enter default widget area slug', 'jupiterx' ),
			'name'         => 'default_value',
			'type'         => 'text',
		));
	}

	/**
	 * Create the HTML interface for your field
	 *
	 * @since 1.0.0
	 *
	 * @param  $field (array) the $field being rendered.
	 *
	 * @return void
	 */
	public function render_field( $field ) {
		global $wp_registered_sidebars;

		echo wp_kses( sprintf( '<select id="%d" class="%s" name="%s">', esc_attr( $field['id'] ), esc_attr( $field['class'] ), esc_attr( $field['name'] ) ), [
			'select' => [
				'id' => [],
				'class' => [],
				'name' => [],
			],
		] );

		// Initial value of global.
		$selected = selected( $field['value'], 'global' );
		echo wp_kses( sprintf( '<option value="%1$s" %3$s>%2$s</option>', 'global', esc_html__( 'Global', 'jupiterx' ), esc_attr( $selected ) ), [
			'option' => [
				'value' => [],
				'selected' => [],
			],
		] );

		// Options for list of sidebars available.
		foreach ( $wp_registered_sidebars as $widget_area_key => $widget_area_properties ) {
			$selected = selected( $field['value'], $widget_area_key );
			echo wp_kses( sprintf( '<option value="%1$s" %3$s>%2$s</option>', esc_attr( $widget_area_key ), esc_attr( $widget_area_properties['name'] ), esc_html( $selected ) ), [
				'option' => [
					'value' => [],
					'selected' => [],
				],
			]);
		}

		echo wp_kses( '</select>', [ 'select' => [] ] );
	}

}

new JupiterX_Field_Widget_Area();
