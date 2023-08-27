<?php
/**
 * This class is a common class for all the controls.
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
 * Control base class.
 *
 * This is a special section for rendering tabs and child popups controls inside the Popup section.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Base_Control extends WP_Customize_Control {

	/**
	 * Column size of the control.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $column = '12';

	/**
	 * Empty label.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $label_empty = false;

	/**
	 * Used to automatically generate all CSS output.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $output = [];

	/**
	 * Whitelisting the "required" argument.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $required = [];

	/**
	 * Attributes for the control wrapper.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $control_attrs = [];

	/**
	 * Responsive control.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $responsive = false;

	/**
	 * Jupiter X params.
	 *
	 * @since 1.17.0
	 *
	 * @var array
	 */
	public $jupiterx = [];

	public $box = '';

	public $label_block = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['default'] = $this->setting->default;

		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		}

		// Add this to whitelist `active_callback` from Kirki.
		$this->json['required'] = $this->required;

		// Output.
		$this->json['output'] = $this->output;

		// Responsive.
		$this->json['responsive'] = (bool) $this->responsive;

		// Value.
		$this->json['value'] = $this->value();

		// Choices.
		$this->json['choices'] = $this->choices;

		// The link.
		$this->json['link'] = $this->get_link();

		// The ID.
		$this->json['id'] = $this->id;

		// Empty label.
		$this->json['labelEmpty'] = $this->label_empty;

		// Input attributes.
		$this->json['inputAttrs'] = '';

		foreach ( $this->input_attrs as $attr => $value ) {
			$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
		}

		// Control wrapper attributes.
		$this->json['controlAttrs'] = '';

		foreach ( $this->control_attrs as $attr => $value ) {
			$this->json['controlAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
		}

		// Jupiter X.
		$this->json['jupiterx']    = $this->jupiterx;
		$this->json['box']         = $this->box;
		$this->json['label_block'] = $this->label_block;
	}

	/**
	 * Renders the control wrapper and calls $this->render_content() for the internals.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$id     = 'customize-control-' . str_replace( [ '[', ']' ], [ '-', '' ], $this->id );
		$class  = sprintf( 'customize-control customize-control-%2$s', $this->column, $this->type );
		$class .= $this->label_block ? ' control-label-block' : '';

		if ( $this->responsive ) {
			$class .= ' customize-controls-list customize-control-responsive';
		}

		if ( 'jupiterx-choose' === $this->type ) {
			$class .= $this->inline ? ' jupiterx-choose-inline' : '';
		}

		printf( '<li id="%s" class="%s" data-box="%s">', esc_attr( $id ), esc_attr( $class ), esc_attr( $this->box ) . '-' . esc_attr( $this->section ) );
		$this->render_content();
		echo '</li>';
	}

	/**
	 * Render the control's content.
	 *
	 * Allows the content to be overridden without having to rewrite the wrapper in `$this::render()`.
	 *
	 * Supports basic input types `text`, `checkbox`, `textarea`, `radio`, `select` and `dropdown-pages`.
	 * Additional input types such as `email`, `url`, `number`, `hidden` and `date` are supported implicitly.
	 *
	 * Control content can alternately be rendered in JS. See WP_Customize_Control::print_template().
	 *
	 * @since 1.0.0
	 */
	protected function render_content() {}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * Preferably not to overwrite this function, use the `control_template` function
	 * to render the control template. Overwriting this only allows for some special
	 * occasion or if your control doesn't need to render the label and description.
	 *
	 * @since 1.0.0
	 */
	protected function content_template() {
		?>
		<# viewport = [ 'global' ] #>
		<# if ( data.responsive ) { #>
			<#
			viewport = [ 'desktop', 'tablet', 'mobile' ]
			responsive = {
				link: data.link,
				id: data.id,
				property: ! _.isUndefined( data.property ) ? data.property : '',
				value: data.value || {}
			}
			#>
			<div class="jupiterx-responsive-control">
		<# } #>
		<# if ( data.label ) { #>
			<label for="{{data.id}}" class="customize-control-title">{{ data.label }}</label>
		<# } else if ( data.labelEmpty ) { #>
			<span class="customize-control-title">&nbsp;</span>
		<# } #>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{ data.description }}</span>
		<# } #>
		<# _.each( viewport, function ( device ) { #>
			<# if ( data.responsive ) { #>
				<#
				data.link = responsive.link + ' data-setting-viewport-link="' + device + '"'
				data.id = responsive.id + '_' + device
				data.value = responsive.value[device] || ''
				#>
				<div class="jupiterx-viewport jupiterx-viewport-{{ device }}">
			<# } #>
			<?php $this->control_template(); ?>
			<# if ( data.responsive ) { #>
				</div>
			<# } #>
		<# } ) #>
		<# if ( data.responsive ) { #>
			<div class="jupiterx-responsive-switcher">
				<div class="jupiterx-responsive-switcher-buttons">
					<# _.each( viewport, function ( device ) { #>
						<a class="jupiterx-responsive-switcher-button jupiterx-responsive-switcher-{{ device }}" data-device="{{ device }}">
							<span class="jupiterx-responsive-switcher-icon">
								<svg><use xlink:href="<?php echo esc_url( JupiterX_Customizer_Utils::get_assets_url() ); ?>/img/customizer-icons.svg#device-{{ device }}"></use></svg>
							</span>
							<span class="jupiterx-responsive-switcher-label">
								{{ device }}
							</span>
						</a>
					<# } ) #>
				</div>
			</div>
			</div>
		<# } #>
		<?php
	}

	/**
	 * An Underscore (JS) template for control wrapper.
	 *
	 * Use to create the control template.
	 *
	 * @since 1.0.0
	 */
	protected function control_template() {}
}
