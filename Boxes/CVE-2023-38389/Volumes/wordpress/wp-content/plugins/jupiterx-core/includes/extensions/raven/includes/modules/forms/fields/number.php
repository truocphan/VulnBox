<?php
/**
 * Add form number field.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Fields;

use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

/**
 * Number Field.
 *
 * Initializing the number field by extending text field.
 *
 * @since 1.0.0
 */
class Number extends Text {

	/**
	 * Update controls.
	 *
	 * Add Min and Max controls in form fields.
	 *
	 * @since 1.0.0
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
			'min' => [
				'name' => 'min',
				'label' => __( 'Min Value', 'jupiterx-core' ),
				'type' => 'number',
				'condition' => [
					'type' => 'number',
				],
			],
			'max' => [
				'name' => 'max',
				'label' => __( 'Max Value', 'jupiterx-core' ),
				'type' => 'number',
				'condition' => [
					'type' => 'number',
				],
			],
		];

		$control_data['fields'] = $this->inject_field_controls( $control_data['fields'], $field_controls );
		$widget->update_control( 'fields', $control_data );
	}

	/**
	 * Validate.
	 *
	 * Check the field based on specific validation rules.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param object $field The field data.
	 */
	public static function validate( $ajax_handler, $field ) {
		$record_field = $ajax_handler->record['fields'][ $field['_id'] ];

		if ( ! empty( $ajax_handler->response['errors'][ $field['_id'] ] ) ) {
			return;
		}

		if ( empty( $record_field ) ) {
			return;
		}

		if ( ! empty( $field['min'] ) && intval( $record_field ) < $field['min'] ) {
			$error = sprintf(
				/* translators: %s: Min value */
				__( 'The value should be bigger than %s', 'jupiterx-core' ),
				$field['min']
			);
		}

		if ( ! empty( $field['max'] ) && intval( $record_field ) > $field['max'] ) {
			$error = sprintf(
				/* translators: %s: Max value */
				__( 'The value should be smaller than %s', 'jupiterx-core' ),
				$field['max']
			);
		}

		if ( ! filter_var( $record_field, FILTER_VALIDATE_INT ) ) {
			$error = __( 'The value is not a valid number', 'jupiterx-core' );
		}

		if ( empty( $error ) ) {
			return;
		}

		$ajax_handler
			->add_response( 'errors', $error, $field['_id'] )
			->set_success( false );
	}

}
