<?php
/**
 * Add form select field.
 *
 * @package JupiterX_Core\Raven
 * @since 1.3.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Fields;

defined( 'ABSPATH' ) || die();

use Elementor\Plugin as Elementor;

/**
 * Select Field.
 *
 * Initializing the select field by extending field base abstract class.
 *
 * @since 1.3.0
 */
class Select extends Field_Base {

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
		return 'select';
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
		$field    = $this->field;
		$rows     = empty( $this->field['rows'] ) ? '' : 'size="' . $this->field['rows'] . '"';
		$multiple = empty( $this->field['multiple_selection'] ) ? '' : 'multiple';
		$options  = preg_split( '/\\r\\n|\\r|\\n/', $field['field_options'], -1, PREG_SPLIT_NO_EMPTY );

		if ( empty( $options ) ) {
			return;
		}

		if ( $multiple ) {
			$this->widget->set_render_attribute( 'field-' . $this->get_id(), 'name', 'fields[' . $this->get_id() . '][]' );
		}
		?>
		<div class="raven-field-subgroup">
		<?php
		if ( ! $multiple ) {
			$this->render_icon();
		}

		$html  = '<select oninput="onInvalidRavenFormField(event)" oninvalid="onInvalidRavenFormField(event)" ';
		$html .= $this->widget->get_render_attribute_string( 'field-' . $this->get_id() ) . $rows . $multiple . '>';

		foreach ( $options as $key => $option ) {
			$id           = $this->get_id();
			$option_id    = $id . $key;
			$option_label = $option;
			$option_value = $option;

			if ( false !== strpos( $option, '|' ) ) {
				list( $option_label, $option_value ) = explode( '|', $option );
			}

			$option_args = [ 'value' => $option_value ];

			if ( $this->get_value() === $option_value ) {
				$option_args['selected'] = 'selected';
			}

			$this->widget->add_render_attribute(
				$option_id,
				$option_args
			);

			$html .= '<option ' . $this->widget->get_render_attribute_string( $option_id ) . '>' . $option_label . '</option>';
		}

		$html .= '</select>';

		$html .= '</div>';

		echo $html;
	}

	protected function render_icon() {
		$settings          = $this->widget->get_active_settings();
		$migration_allowed = Elementor::$instance->icons_manager->is_migration_allowed();
		$migrated          = isset( $settings['__fa4_migrated']['select_arrow_icon_new'] );
		$is_new            = empty( $settings['select_arrow_icon'] ) && $migration_allowed;

		if ( $is_new || $migrated ) {
			if ( 'svg' === $settings['select_arrow_icon_new']['library'] ) {
				echo '<div class="raven-field-select-arrow">';
				Elementor::$instance->icons_manager->render_icon( $settings['select_arrow_icon_new'], [ 'aria-hidden' => 'true' ] );
				echo '</div>';
			} else {
				Elementor::$instance->icons_manager->render_icon(
					$settings['select_arrow_icon_new'],
					[
						'aria-hidden' => 'true',
						'class'       => 'raven-field-select-arrow',
					]
				);
			}
		} else {
			?>
			<i class="raven-field-select-arrow ' . $settings['select_arrow_icon'] . '"></i>';
			<?php
		}
	}
}
