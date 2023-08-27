<?php
/**
 * Add form checkbox field.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.4
 */

namespace JupiterX_Core\Raven\Modules\Forms\Fields;

defined( 'ABSPATH' ) || die();

use Elementor\Plugin as Elementor;

/**
 * Checkbox Field.
 *
 * Initializing the checkbox field by extending field base abstract class.
 *
 * @since 1.0.4
 */
class Checkbox extends Field_Base {

	/**
	 * Get field type.
	 *
	 * Retrieve the field type.
	 *
	 * @since 1.0.4
	 * @access public
	 *
	 * @return string Field type.
	 */
	public function get_type() {
		return 'checkbox';
	}

	/**
	 * Render content.
	 *
	 * Render the field content.
	 *
	 * @since 1.0.4
	 * @access public
	 */
	public function render_content() {
		$field   = $this->field;
		$options = preg_split( "/(\r\n|\n|\r)/", $field['field_options'], -1, PREG_SPLIT_NO_EMPTY );

		if ( empty( $options ) ) {
			return;
		}

		$html          = '<div class="raven-field-subgroup ' . $field['inline_list'] . '">';
		$random_string = $this->generate_random_string();

		foreach ( $options as $key => $option ) {
			$id           = $this->get_id();
			$element_id   = $id . $key;
			$html_id      = 'form-field-' . $id . '-' . $key . '-' . $random_string;
			$option_label = $option;
			$option_value = $option;

			if ( false !== strpos( $option, '|' ) ) {
				list( $option_label, $option_value ) = explode( '|', $option );
			}

			$this->widget->add_render_attribute(
				$element_id,
				[
					'type' => 'checkbox',
					'value' => $option_value,
					'id' => $html_id,
					'name' => "fields[{$id}]" . ( count( $options ) > 1 ? '[]' : '' ),
				]
			);

			$html .= '<span class="raven-field-option raven-field-option-checkbox"><input ' . $this->widget->get_render_attribute_string( $element_id ) . '  class="raven-field"> <label for="' . $html_id . '" class="raven-field-label">' . $option_label . '</label></span>';
		}

		$html .= '</div>';

		echo $html;
	}
}
