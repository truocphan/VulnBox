<?php
/**
 * Handles Elementor template control class.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor template control class.
 *
 * @since 1.1.0
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_Template extends JupiterX_Customizer_Base_Control {

	/**
	 * Control's type.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-template';

	/**
	 * Control's select field placeholder.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	public $placeholder = '';

	/**
	 * Type of template to create.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	public $template_type = '';

	/**
	 * Show pro badge if locked.
	 *
	 * @since 1.11.0
	 *
	 * @var boolean
	 */
	public $locked = false;

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.1.0
	 */
	public function to_json() {
		parent::to_json();

		// Select field placeholder.
		$this->json['placeholder'] = $this->placeholder;

		// Template type.
		$this->json['templateType'] = $this->template_type ? $this->template_type : 'post';

		$this->json['locked'] = $this->locked;
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @since 1.1.0
	 */
	protected function content_template() {
		?>
		<# if ( data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>
		<div class="jupiterx-control jupiterx-template-control">
			<div class="jupiterx-select-control">
				<select class="jupiterx-select-control" value="{{ data.value }}" id="{{ data.id }}" {{{ data.link }}}></select>
			</div>
			<span class="jupiterx-text-separator">
				<?php esc_html_e( 'OR', 'jupiterx-core' ); ?>
			</span>
			<div class="jupiterx-template-button-wrapper">
				<button type="button" class="jupiterx-button jupiterx-edit">
					<?php esc_html_e( 'Edit', 'jupiterx-core' ); ?>
					<# if ( data.locked ) { #><svg class="jupiterx-control-pro-badge"><use xlink:href="<?php echo esc_url( jupiterx_core_get_pro_badge() ); ?>"><use></svg><# } #>
				</button>
				<button type="button" class="jupiterx-button jupiterx-add">
					<?php esc_html_e( 'New', 'jupiterx-core' ); ?>
					<# if ( data.locked ) { #><svg class="jupiterx-control-pro-badge"><use xlink:href="<?php echo esc_url( jupiterx_core_get_pro_badge() ); ?>"><use></svg><# } #>
				</button>
			</div>
		</div>
		<?php
	}
}
