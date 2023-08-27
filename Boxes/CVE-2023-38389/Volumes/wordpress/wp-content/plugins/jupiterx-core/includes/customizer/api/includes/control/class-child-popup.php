<?php
/**
 * This class handles opening child popup pane.
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
 * Open  control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_Child_Popup extends JupiterX_Customizer_Base_Control {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-child-popup';

	/**
	 * Control's target section to open child pane.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $target = '';

	/**
	 * Control's binded items to display from existing setting.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $bind_items = '';

	/**
	 * Control's sortable enabler.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $sortable = false;

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['target']    = $this->target;
		$this->json['bindItems'] = $this->bind_items;
		$this->json['sortable']  = $this->sortable;

		foreach ( $this->choices as $key => $choice ) {
			// Transform label.
			if ( is_string( $choice ) ) {
				$this->json['choices'][ $key ] = [ 'label' => $choice ];
				continue;
			}
		}
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
		<#
		choices = _.keys( data.choices )
		items = ! _.isEmpty( data.value ) ? _.union( data.value, choices ) : choices
		#>
		<div class="jupiterx-control jupiterx-child-popup-control">
			<div class="jupiterx-child-popup-control-items">
				<# _.each( items, function( item ) { #>
					<# if ( ! _.isUndefined( data.choices[ item ] ) ) { #>
						<li class="jupiterx-child-popup-control-item" data-value="{{ item }}">
							<# if ( data.sortable ) { #><span class="jupiterx-child-popup-control-drag"><svg><use xlink:href="<?php echo esc_url( jupiterx_core_get_pro_badge() ); ?>/img/customizer-icons.svg#drag-drop"></use></svg></span><# } #>
							<span class="jupiterx-child-popup-control-label">{{ data.choices[ item ].label }}</span>
							<# if ( data.choices[ item ].pro ) { #><svg class="jupiterx-control-pro-badge"><use xlink:href="<?php echo esc_url( jupiterx_core_get_pro_badge() ); ?>"><use></svg><# } #>
						</li>
					<# } #>
				<# } ) #>
			</div>
		</div>
		<?php
	}
}
