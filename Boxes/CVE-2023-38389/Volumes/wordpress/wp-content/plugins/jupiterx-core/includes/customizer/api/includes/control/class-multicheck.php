<?php
/**
 * Handles multicheck control class.
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
 * Multicheck control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_Multicheck extends JupiterX_Customizer_Base_Control {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-multicheck';

	/**
	 * Choices via icons.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $icon_choices = [];

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['iconChoices'] = $this->icon_choices;
	}

	/**
	 * An Underscore (JS) template for control wrapper.
	 *
	 * Use to create the control template.
	 *
	 * @since 1.0.0
	 */
	protected function control_template() {
		?>
		<div class="jupiterx-control jupiterx-multicheck-control">
			<# if ( ! _.isEmpty( data.iconChoices ) ) { #>
				<div class="jupiterx-multicheck-control-icon-items">
					<# _.each( data.iconChoices, function( icon, key ) { #>
						<div class="jupiterx-multicheck-control-icon-item">
							<input class="jupiterx-multicheck-control-checkbox" {{{ data.inputAttrs }}} type="checkbox" value="{{ key }}" id="{{ data.id }}-{{ key }}" <# if ( data.value.indexOf( key ) >= 0 ) { #> checked <# } #>>
							<label class="jupiterx-multicheck-control-icon-label" for="{{ data.id }}-{{ key }}"><svg><use xlink:href="<?php echo esc_url( JupiterX_Customizer_Utils::get_assets_url() ); ?>/img/customizer-icons.svg#{{ icon }}"></use></svg></label>
						</div>
					<# } ) #>
				</div>
			<# } #>
			<# if ( ! _.isEmpty( data.choices ) ) { #>
				<div class="jupiterx-multicheck-control-items">
					<# _.each( data.choices, function( label, key ) { #>
						<div class="jupiterx-multicheck-control-item">
							<input class="jupiterx-multicheck-control-checkbox" {{{ data.inputAttrs }}} type="checkbox" value="{{ key }}" id="{{ data.id }}-{{ key }}" <# if ( data.value.indexOf( key ) >= 0 ) { #> checked <# } #>>
							<label class="jupiterx-multicheck-control-label" for="{{ data.id }}-{{ key }}"><span class="jupiterx-multicheck-control-box"><span class="jupiterx-multicheck-control-handler"></span></span> {{ label }}</label>
						</div>
					<# } ) #>
				</div>
			<# } #>
			<input type="hidden" value="{{ data.value }}" {{{ data.link }}}>
		</div>
		<?php
	}

	/**
	 * Format CSS value from theme mod array value.
	 *
	 * @since 1.0.0
	 *
	 * @param array $value The field's value.
	 *
	 * @return array The formatted properties.
	 */
	public static function format_properties( $value ) {
		$vars = [];

		foreach ( $value as $key ) {
			$vars[ $key ] = 'true';
		}

		return $vars;
	}
}
