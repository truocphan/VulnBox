<?php
/**
 * Handles exceptions control class.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Exceptions control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_Exceptions extends JupiterX_Customizer_Base_Control {

	/**
	 * Fields for this control.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $fields = [];

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-exceptions';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		// Exceptions fields.
		$this->json['fields'] = $this->fields;
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @since 1.0.0
	 */
	protected function content_template() {
		?>
		<# if ( data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>
		<div class="jupiterx-control jupiterx-exceptions-control">
			<div class="jupiterx-exceptions-control-items"></div>
			<div class="jupiterx-row">
				<div class="jupiterx-control jupiterx-select-control jupiterx-select-control-plain jupiterx-exceptions-control-add">
					<button class="jupiterx-button"><i class="eicon-plus-circle" aria-hidden="true"></i> <?php esc_html_e( 'Add New Condition', 'jupiterx-core' ); ?></button>
					<select class="jupiterx-select-control-field">
						<# _.each( data.fields, function( field, key ) { #>
							<option value="{{ key }}">{{{ field.label }}}</option>
						<# } ); #>
					</select>
				</div>
			</div>
		</div>
		<?php
	}
}
