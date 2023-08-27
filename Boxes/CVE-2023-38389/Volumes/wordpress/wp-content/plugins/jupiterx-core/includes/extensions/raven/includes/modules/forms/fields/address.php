<?php
/**
 * Add form address field.
 *
 * @package JupiterX_Core\Raven
 * @since 1.3.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Fields;

use Elementor\Plugin as Elementor;
use Elementor\Settings;

defined( 'ABSPATH' ) || die();

/**
 * Address Field.
 *
 * Initializing the address field by extending field base abstract class.
 *
 * @since 1.3.0
 */
class Address extends Field_Base {

	/**
	 * Get field type.
	 *
	 * Retrieve the field type.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return string Field type.
	 */
	public function get_type() {
		return 'text';
	}

	/**
	 * Render content.
	 *
	 * Render the field content.
	 *
	 * @since 1.3.0
	 * @access public
	 */
	public function render_content() {
		?>
		<input
			oninput="onInvalidRavenFormField(event)"
			oninvalid="onInvalidRavenFormField(event)"
			<?php echo $this->widget->get_render_attribute_string( 'field-' . $this->get_id() ); ?>>
		<?php
	}

	/**
	 * Update controls.
	 *
	 * Add controls in form fields.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @param object $widget Widget instance.
	 */
	public function update_controls( $widget ) {
		$control_data = Elementor::$instance->controls_manager->get_control_from_stack(
			$widget->get_unique_name(),
			'fields'
		);

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$field_controls = [
			'address_google_api_key_help' => [
				'name' => 'address_google_api_key_help',
				'type' => 'raw_html',
				'raw' => sprintf(
					/* translators: %s: Settings page URL */
					__( '<small>Set your Google API key in <a target="_blank" href="%s">JupiterX Settings <i class="fa fa-external-link-square"></i></a></small>.', 'jupiterx-core' ),
					Settings::get_url() . '#tab-raven'
				),
				'condition' => [
					'type' => 'address',
				],
			],
		];

		$control_data['fields'] = $this->inject_field_controls( $control_data['fields'], $field_controls, 'type' );
		$widget->update_control( 'fields', $control_data );
	}
}
