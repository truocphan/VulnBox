<?php
/**
 * Adds media control.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Controls;

use Elementor\Base_Data_Control;

defined( 'ABSPATH' ) || die();

class Checkbox extends Base_Data_Control {

	/**
	 * Get select2 control type.
	 *
	 * Retrieve the control type, in this case `select2`.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'raven_checkbox';
	}

	/**
	 * Render select2 control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.3.0
	 * @access public
	 */
	public function content_template() {
		?>
		<div class="raven-control-field">
			<# if ( data.label ) { #>
				<div class="elementor-control-title">{{{ data.label }}}</div>
			<# } #>
			<div class="raven-control-checkbox-wrapper">
				<#
				var currentValues = data.controlValue
				if ( ! _.isArray(data.controlValue) ) {
					currentValues = data.controlValue.split(',')
				}
				_.each( data.options, function( option_title, option_value ) {
					var checked = ''
					if ( Object.values(currentValues).indexOf(option_value) !== -1 ) {
						checked = 'checked';
					}
					#>
					<span>
						<input id="raven-control-checkbox-{{ data._cid }}-{{ option_value }}" type="checkbox" class="raven-control-checkbox" value="{{option_value}}" {{checked}} />
						<label for="raven-control-checkbox-{{ data._cid }}-{{ option_value }}">{{{ option_title }}}</label>
					</span>
				<# } ); #>
				<input type="hidden" data-setting="{{data.name}}" value="">
			</div>
			<div class="elementor-control-field-description">{{data.description}}</div>
		</div>
		<?php
	}
}
