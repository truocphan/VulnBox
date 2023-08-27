<?php
/**
 * Handles box model control class.
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
 * Box model control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_Box_Model extends JupiterX_Customizer_Base_Control {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-box-model';

	/**
	 * Control's exclude box model parts.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $exclude = [];

	/**
	 * Control's disable box model parts.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $disable = [];

	/**
	 * Control's unit.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $units = [
		'px',
		'%',
		'em',
		'rem',
	];

	/**
	 * Control's global default unitt.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $default_unit = 'rem';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['exclude']      = $this->exclude;
		$this->json['disable']      = $this->disable;
		$this->json['units']        = $this->units;
		$this->json['default_unit'] = self::$default_unit;
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
		<#
		sides = [
			'top',
			'right',
			'bottom',
			'left'
		]
		properties = {
			margin: {
				title: '<?php esc_attr_e( 'Margin', 'jupiterx-core' ); ?>',
				min: -1000,
			},
			padding: {
				title: '<?php esc_attr_e( 'Padding', 'jupiterx-core' ); ?>',
				min: 0,
			}
		}
		units = data.units
		selectorClass = _.isArray( units ) && 1 === _.size( units ) ? 'disabled' : ''
		#>
		<div class="jupiterx-control jupiterx-box-model-control">
			<# _.each( properties, function ( props, key ) { #>
				<# if ( data.exclude.indexOf( key ) < 0 ) {
					unitValue = _.isEmpty( data.value[ key + '_unit' ] ) ?  data.default_unit : data.value[ key + '_unit' ]
					step = 'px' == unitValue ? 1 : .1 #>
					<div class="jupiterx-box-model-head">
						<label class="customize-control-title">{{ props.title }}</label>
						<div class="jupiterx-unit-selector-wrapper">
							<# selectedClass = '' #>
							<# unitValue = _.isEmpty( data.value[ key + '_unit' ] ) ?  data.default_unit : data.value[ key + '_unit' ] #>
							<div class="jupiterx-control-units-container">
								<input type="hidden" value="{{ unitValue }}" {{{ data.link }}} data-setting-property-link="{{key + '_unit'}}" />
								<ul class="jupiterx-control-unit-selector" data-inputs=".jupiterx-box-model-control-property-{{ key }} > input">
									<# _.each( units, function ( unit ) { #>
										<# if(unit === unitValue) {
											selectedClass = 'selected-unit'
										} #>
										<li class="jupiterx-control-unit {{ selectedClass }}">{{ unit }}</li>
									<# } ) #>
								</ul>
							</div>
						</div>
					</div>
					<div class="jupiterx-box-model-control-property jupiterx-box-model-control-property-{{ key }}">
						<ul class="jupiterx-box-model-wrapper">
							<# _.each( sides, function ( side ) {
								propertyName = key + '_' + side
								value = ! _.isUndefined( data.value[ propertyName ] ) ? data.value[ propertyName ] : ''
								disable = _.indexOf( data.disable, propertyName.replace( '_', '-' ) ) !== -1 ? 'disabled' : '' #>
									<li>
										<input class="jupiterx-box-model-control-input jupiterx-box-model-control-{{ side }}" min="{{ props.min }}" {{{ data.inputAttrs }}} type="number" value="{{ value }}" {{ disable }} step="{{ step }}"  placeholder="-" {{{ data.link }}} data-setting-property-link="{{ propertyName }}" />
										<span class="box-model-label">{{ side }}</span>
									</li>
							<# } ) #>
						</ul>
					</div>
				<# } #>
			<# } ) #>
		</div>
		<?php
	}

	/**
	 * Format CSS value from theme mod array value.
	 *
	 * @since 1.0.0
	 *
	 * @param array $value The field's value.
	 * @param array $args The field's arguments.
	 *
	 * @return array The formatted properties.
	 */
	public static function format_properties( $value, $args ) {
		$args = array_merge( [ 'exclude' => [] ], $args );

		$positions = [ 'top', 'right', 'bottom', 'left' ];

		$vars = [];

		if ( ! in_array( 'margin', $args['exclude'], true ) ) {
			$margin_unit = isset( $value['margin_unit'] ) ? $value['margin_unit'] : self::$default_unit;

			foreach ( $positions as $position ) {
				// Accepts non-numeric value such as 'auto'.
				if ( array_key_exists( 'margin_' . $position, $value ) ) {
					$property_value = $value[ 'margin_' . $position ];
					$unit           = is_numeric( $property_value ) && 0 !== $property_value ? $margin_unit : '';
					$position       = jupiterx_get_direction( $position );

					$vars[ 'margin-' . $position ] = $property_value . $unit;
				}
			}
		}

		if ( ! in_array( 'padding', $args['exclude'], true ) ) {
			$padding_unit = isset( $value['padding_unit'] ) ? $value['padding_unit'] : self::$default_unit;

			foreach ( $positions as $position ) {
				// Does not accept any value that is not numeric.
				if ( array_key_exists( 'padding_' . $position, $value ) && is_numeric( $value[ 'padding_' . $position ] ) ) {
					$property_value = $value[ 'padding_' . $position ];
					$unit           = 0 !== $property_value ? $padding_unit : '';
					$position       = jupiterx_get_direction( $position );

					$vars[ 'padding-' . $position ] = $property_value . $unit;
				}
			}
		}

		return $vars;
	}
}
