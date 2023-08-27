<?php
/**
 * Handles radio image control class.
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
 * Radio image control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_Radio_Image extends JupiterX_Customizer_Base_Control {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-radio-image';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		foreach ( $this->choices as $key => $choice ) {
			// Transform label.
			if ( is_string( $choice ) ) {
				$this->json['choices'][ $key ] = [ 'name' => $choice ];
				continue;
			}
		}
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
		<div class="jupiterx-control jupiterx-radio-image-control">
			<div class="jupiterx-radio-image-control-buttons">
				<# _.each( data.choices, function( image, key ) { #>
					<# if ( ! image.pro ) { #><input class="jupiterx-radio-image-control-radio" {{{ data.inputAttrs }}} type="radio" value="{{ key }}" name="{{ data.id }}" id="{{ data.id }}-{{ key }}" {{{ data.link }}} <# if ( key === data.value ) { #> checked <# } #>><# } #>
					<label
						class="jupiterx-radio-image-control-button <# if ( image.pro ) { #>pro-preview<# } #>"
						for="{{ data.id }}-{{ key }}"
						<# if ( image.pro ) { #>data-preview="{{ image.preview }}"<# } #>
					>
						<svg class="jupiterx-radio-image-control-image"><use xlink:href="<?php echo esc_url( JupiterX_Customizer_Utils::get_assets_url() ); ?>/img/customizer-icons.svg#{{ image.name }}"></use></svg>
						<# if ( image.pro ) { #><svg class="jupiterx-control-pro-badge"><use xlink:href="<?php echo esc_url( jupiterx_core_get_pro_badge() ); ?>"><use></svg><# } #>
					</label>
				<# } ) #>
			</div>
		</div>
		<?php
	}
}
