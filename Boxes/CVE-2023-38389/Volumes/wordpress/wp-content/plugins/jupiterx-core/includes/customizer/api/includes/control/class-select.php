<?php
/**
 * Handles select control class.
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
 * Select control class.
 *
 * This is a special section for rendering tabs and child popups controls inside the Popup section.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_Select extends JupiterX_Customizer_Base_Input_Group {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-select';

	/**
	 * Control's placeholder.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $placeholder = '';

	/**
	 * Delayed data loading for choices.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $load_choices = '';

	/**
	 * Live reload icons.
	 *
	 * @since 1.20.0
	 *
	 * @var string
	 */
	private $exclude_reload = [
		'font-weight',
		'text-decoration',
	];

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['placeholder'] = $this->placeholder;

		// Choices to load.
		$this->json['load_choices'] = $this->load_choices;

		// live reloading
		$this->json['exclude_reload'] = $this->require_reload();
	}

	/**
	 * Get selects with live reloading feature based ib their icons.
	 *
	 * @since 1.20.0
	 */
	public function require_reload() {
		if ( empty( $this->icon ) ) {
			return false;
		}

		return in_array( $this->icon, $this->exclude_reload, true );
	}

	/**
	 * An Underscore (JS) template for control field.
	 *
	 * @since 1.0.0
	 */
	protected function group_field_template() {
		?>
		<# if ( data.load_choices === 'widgets_area' ) { #>
			<# data.choices = <?php echo json_encode( JupiterX_Customizer_Utils::get_select_widgets_area() ); // @codingStandardsIgnoreLine ?> #>
		<# } #>

		<# classes = 'jupiterx-select-control-field';

		if ( data && ! data.exclude_reload ) {
			classes += ' jupiterx-renew-preview';
		}

		var isSelect2 = _.isEmpty( data.jupiterx ) || _.isEmpty( data.jupiterx.select2 )
		#>
		<select class="{{classes}}" {{{ data.inputAttrs }}} value="{{ data.value }}" id="{{ data.id }}" {{{ data.link }}}>
			<# if ( isSelect2 && ! _.isEmpty( data.placeholder ) ) { #>
				<option value="" <# if ( _.isEmpty( data.value ) ) { #> selected<# } #>>{{{ data.placeholder }}}</option>
			<# } #>
			<# _.each( data.choices, function( label, key ) { #>
				<# if ( ! _.isEmpty( key ) ) { #>
					<# selected = ( data.value === key ) #>
					<option value="{{ key }}" <# if ( selected ) { #> selected<# } #>>{{{ label }}}</option>
				<# }#>
			<# } ); #>
			<# if ( ! isSelect2 && ! _.isEmpty( data.value ) ) { #>
				<option selected><?php esc_html_e( 'Fetching...', 'jupiterx-core' ); ?></option>
			<# } #>
		</select>
		<?php
	}
}
