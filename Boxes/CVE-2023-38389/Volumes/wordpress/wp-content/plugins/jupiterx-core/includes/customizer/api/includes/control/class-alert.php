<?php
/**
 * Handles alert control class.
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
 * Alert control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_Alert extends JupiterX_Customizer_Base_Control {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-alert';

	/**
	 * Control's alert type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $jupiterx_type = 'warning';

	/**
	 * Control's alert url.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $jupiterx_url = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['jupiterxType'] = $this->jupiterx_type;
		$this->json['jupiterxUrl']  = $this->jupiterx_url;
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
		<# type = data.jupiterxType ? 'jupiterx-alert-control-' + data.jupiterxType : '' #>
		<div class="jupiterx-control jupiterx-alert-control {{ type }}" role="alert">
			<span class="dashicons dashicons-warning"></span>
			<span class="jupiterx-alert-control-text">
				{{{ data.label }}}
				<# if ( data.jupiterxUrl ) { #>
				<a class="jupiterx-alert-control-link" href="{{ data.jupiterxUrl }}" target="_blank">
					<?php esc_html_e( 'Learn more', 'jupiterx-core' ); ?>
				</a>
				<# } #>
			</span>
		</div>
		<?php
	}
}
