<?php
/**
 * Add form hidden field.
 *
 * @package JupiterX_Core\Raven
 * @since 1.20.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Fields;

defined( 'ABSPATH' ) || die();

/**
 * Hidden Field.
 *
 * Initializing the hidden field by extending field base abstract class.
 *
 * @since 1.20.0
 */
class Hidden extends Field_Base {

	/**
	 * Get field type.
	 *
	 * Retrieve the field type.
	 *
	 * @since 1.20.0
	 * @access public
	 *
	 * @return string Field type.
	 */
	public function get_type() {
		return 'hidden';
	}

	/**
	 * Render content.
	 *
	 * Render the field content.
	 *
	 * @since 1.20.0
	 * @access public
	 */
	public function render_content() {
		$field    = $this->field;
		$field_id = $this->get_id();

		$this->widget->add_render_attribute(
			$field_id,
			[
				'type' => 'hidden',
				'id' => 'field-' . $field_id,
				'name' => 'fields[' . $field_id . ']',
				'value' => $field['field_value'],
			]
		);
		?>
		<input
			<?php echo $this->widget->get_render_attribute_string( $field_id ); ?>>
		<?php
	}
}
