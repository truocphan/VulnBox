<?php
/**
 * Add form time field.
 *
 * @package JupiterX_Core\Raven
 * @since 1.2.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Fields;

use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

/**
 * Time Field.
 *
 * Initializing the time field by extending field base abstract class.
 *
 * @since 1.2.0
 */
class Time extends Field_Base {

	public function get_type() {
		if ( $this->field['native_html5'] ) {
			return 'time';
		}

		return 'text';
	}

	public function get_class() {
		return 'raven-field flatpickr';
	}

	public function get_style_depends() {
		return [ 'flatpickr' ];
	}

	public function get_script_depends() {
		return [ 'flatpickr' ];
	}

	private function get_min_time() {
		$attr = 'data-min-date';
		$min  = empty( $this->field['min_time'] ) ? '' : $this->field['min_time'];

		if ( $this->field['native_html5'] ) {
			$attr = 'min';
		}

		return $attr . '="' . $min . '"';
	}

	private function get_max_time() {
		$attr = 'data-max-date';
		$max  = empty( $this->field['max_time'] ) ? '' : $this->field['max_time'];

		if ( $this->field['native_html5'] ) {
			$attr = 'max';
		}

		return $attr . '="' . $max . '"';
	}

	public function render_content() {
		?>
		<input
			oninput="onInvalidRavenFormField(event)"
			oninvalid="onInvalidRavenFormField(event)"
			<?php echo $this->widget->get_render_attribute_string( 'field-' . $this->get_id() ); ?>
			data-enable-time="true"
			data-no-calendar="true"
			data-time_24hr="true"
			<?php echo $this->get_min_time(); ?>
			<?php echo $this->get_max_time(); ?>>
		<?php
	}

	public function update_controls( $widget ) {
		$control_data = Elementor::$instance->controls_manager->get_control_from_stack(
			$widget->get_unique_name(),
			'fields'
		);

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$field_controls = [
			'min_time' => [
				'name' => 'min_time',
				'label' => __( 'Min Time', 'jupiterx-core' ),
				'type' => 'date_time',
				'picker_options' => [
					'enableTime' => true,
					'noCalendar' => true,
					'time_24hr' => true,
				],
				'label_block' => false,
				'condition' => [
					'type' => 'time',
				],
			],
			'max_time' => [
				'name' => 'max_time',
				'label' => __( 'Max Time', 'jupiterx-core' ),
				'type' => 'date_time',
				'picker_options' => [
					'enableTime' => true,
					'noCalendar' => true,
					'time_24hr' => true,
				],
				'label_block' => false,
				'condition' => [
					'type' => 'time',
				],
			],
		];

		$control_data['fields'] = $this->inject_field_controls( $control_data['fields'], $field_controls );
		$widget->update_control( 'fields', $control_data );
	}
}
