<?php
/**
 * Handles choose control class.
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
 * Choose control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_Choose extends JupiterX_Customizer_Base_Input_Group {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-choose';

	/**
	 * Choose multiple.
	 *
	 * @since 1.0.0
	 *
	 * @var boolean
	 */
	public $multiple = false;

	/**
	 * Choose inline style.
	 *
	 * @since 2.0.0
	 *
	 * @var boolean
	 */
	public $inline = false;

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
				$this->json['choices'][ $key ] = [ 'label' => $choice ];
				continue;
			}

			// Icon choices.
			if ( isset( $choice['icon'] ) && ! empty( $choice['icon'] ) ) {
				$this->json['choices'][ $key ]['icon'] = $choice['icon'];
			}
		}

		$this->json['multiple'] = $this->multiple;
		$this->json['inline']   = $this->inline;
	}

	/**
	 * An Underscore (JS) template for control field.
	 *
	 * @since 1.0.0
	 */
	protected function group_field_template() {
		?>
		<#
		inlineClass = data.inline ? 'jupiterx-choose-control-buttons-inline' : '';
		#>
			<div class="jupiterx-choose-control-buttons {{ inlineClass }}">
				<# if ( data.multiple ) { #>
					<# _.each( data.choices, function( choice, key ) { #>
						<input class="jupiterx-choose-control-radio" {{{ data.inputAttrs }}} type="checkbox" value="{{ key }}" name="{{ data.id }}" id="{{ data.id }}-{{ key }}" <# if ( data.value.indexOf( key ) >= 0 ) { #> checked <# } #>>
						<label class="jupiterx-choose-control-button jupiterx-choose-control-{{ ( choice.icon ) ? 'icon' : 'label' }}" for="{{ data.id }}-{{ key }}">
							<# if ( choice.icon ) { #><svg><use xlink:href="<?php echo esc_url( JupiterX_Customizer_Utils::get_assets_url() ); ?>/img/customizer-icons.svg#{{ choice.icon }}"></use></svg><# } else { #>{{ choice.label }}<# } #>
							<# if ( choice.pro ) { #><svg class="jupiterx-control-pro-badge"><use xlink:href="<?php echo esc_url( jupiterx_core_get_pro_badge() ); ?>"><use></svg><# } #>
						</label>
					<# } ) #>
					<input type="hidden" value="{{ data.value }}" {{{ data.link }}}>
				<# } else { #>
					<# _.each( data.choices, function( choice, key ) { #>
						<input class="jupiterx-choose-control-radio" {{{ data.inputAttrs }}} type="radio" value="{{ key }}" name="{{ data.id }}" id="{{ data.id }}-{{ key }}" {{{ data.link }}} <# if ( key === data.value ) { #> checked <# } #>>
						<label class="jupiterx-choose-control-button jupiterx-choose-control-{{ ( choice.icon ) ? 'icon' : 'label' }}" for="{{ data.id }}-{{ key }}">
							<# if ( choice.icon ) { #><svg><use xlink:href="<?php echo esc_url( JupiterX_Customizer_Utils::get_assets_url() ); ?>/img/customizer-icons.svg#{{ choice.icon }}"></use></svg><# } else { #>{{ choice.label }}<# } #>
							<# if ( choice.pro ) { #><svg class="jupiterx-control-pro-badge"><use xlink:href="<?php echo esc_url( jupiterx_core_get_pro_badge() ); ?>"><use></svg><# } #>
						</label>
					<# } ) #>
				<# } #>
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
	 * @return array The formatted value.
	 */
	public static function format_value( $value ) {
		if ( ! is_rtl() ) {
			return $value;
		}

		if ( 'right' === $value ) {
			return 'left';
		}

		if ( 'left' === $value ) {
			return 'right';
		}

		return $value;
	}
}
