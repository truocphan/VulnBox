<?php
/**
 * Presets Control.
 *
 * @package JupiterX_Core\Raven
 * @since 1.5.0
 */
namespace JupiterX_Core\Raven\Controls;

use Elementor\Base_Data_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Raven presets control.
 *
 * @since 1.5.0
 */
class Presets extends Base_Data_Control {

	/**
	 * Get presets control type.
	 *
	 * Retrieve the control type, in this case `raven_presets`.
	 *
	 * @since 1.5.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'raven_presets';
	}

	/**
	 * Render presets control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.5.0
	 * @access public
	 */
	public function content_template() {
		?>
		<div class="elementor-control-field raven-control-presets">
			<div class="raven-element-presets-wrapper">
				<# if (data.controlValue && data.controlValue.presets.length > 0 ) data.controlValue = window.ravenPresets && window.ravenPresets[data.controlValue.presets[0]['type']] && window.ravenPresets[data.controlValue.presets[0]['type']].length === data.controlValue.presets.length ? data.controlValue : {selectedId: null, presets: []}; #>

				<div class="raven-element-presets">
					<# if (!data.controlValue || data.controlValue && data.controlValue.presets.length === 0) { #>
						<div class="raven-element-presets-404">
							No presets found
						</div>
					<# } #>

					<div class="raven-element-presets-loading">
						Loading presets
						<span style="display:inline-flex" class="elementor-control-spinner"><span class="fa fa-spinner fa-spin"></span>&nbsp;</span>
					</div>

					<# if (data.controlValue) { #>
						<# _.each( data.controlValue.presets, function( preset ) { #>
							<div class="raven-element-presets-item {{{preset.id === data.controlValue.selectedId ? ' active' : ''}}}" data-preset-id='{{{preset.id}}}'>
								<i class="fa fa-check"></i>
								<# if (preset.thumbnail) { #>
									<img src="{{{preset.thumbnail}}}" alt="{{{preset.title}}}">
								<# } else { #>
									<span class="raven-element-presets-item-title">{{{preset.title}}}</span>
								<# } #>
							</div>
						<# } ); #>
					<# } #>
				</div>
			</div>
		</div>
		<?php
	}
}
