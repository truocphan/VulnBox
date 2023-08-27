<?php
/**
 * Adds loop animation control.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Controls;

use Elementor\Base_Data_Control;

/**
 * Raven loop animation control.
 *
 * A base control for creating loop animation control. Displays a select box
 * with the available loop animation effects @see Control_Loop_Animation::get_animations()
 *
 * Creating new control in the editor (inside `Widget_Base::_register_controls()`
 * method):
 *
 *    $this->add_control(
 *        'loop_animation',
 *        [
 *            'label' => __( 'Loop Animation', 'plugin-domain' ),
 *            'type' => 'raven_loop_animation',
 *            'prefix_class' => 'raven-loop-animation-',
 *        ]
 *    );
 *
 * PHP usage (inside `Widget_Base::render()` method):
 *
 *    echo '<div class="' . $this->get_settings( 'loop_animation' ) . '"> ... </div>';
 *
 * JS usage (inside `Widget_Base::_content_template()` method):
 *
 *    <div class="{{ settings.loop_animation }}"> ... </div>
 *
 * @since 1.0.0
 *
 * @param string $label       Optional. The label that appears above of the
 *                            field. Default is empty.
 * @param string $description Optional. The description that appears below the
 *                            field. Default is empty.
 * @param string $default     Optional. The selected animation key. Default is
 *                            empty.
 * @param string $separator   Optional. Set the position of the control separator.
 *                            Available values are 'default', 'before', 'after'
 *                            and 'none'. 'default' will position the separator
 *                            depending on the control type. 'before' / 'after'
 *                            will position the separator before/after the
 *                            control. 'none' will hide the separator. Default
 *                            is 'default'.
 * @param bool   $show_label  Optional. Whether to display the label. Default is
 *                            true.
 * @param bool   $label_block Optional. Whether to display the label in a
 *                            separate line. Default is true.
 */
class Loop_Animation extends Base_Data_Control {

	/**
	 * Animations.
	 *
	 * Holds all the available loop animation effects of the control.
	 *
	 * @access private
	 * @static
	 *
	 * @var array
	 */
	private static $_animations;

	/**
	 * Retrieve loop animation control type.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'raven_loop_animation';
	}

	/**
	 * Retrieve animations.
	 *
	 * Get the available loop animation effects.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return array Available loop animation.
	 */
	public static function get_animations() {
		if ( is_null( self::$_animations ) ) {
			self::$_animations = [
				'bounce'     => 'Bounce',
				'flash'      => 'Flash',
				'pulse'      => 'Pulse',
				'rubberBand' => 'Rubber Band',
				'shake'      => 'Shake',
				'headShake'  => 'Head Shake',
				'swing'      => 'Swing',
				'tada'       => 'Tada',
				'wobble'     => 'Wobble',
				'jello'      => 'Jello',
			];
		}

		return self::$_animations;
	}

	/**
	 * Render loop animation control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<select id="<?php echo esc_attr( $control_uid ); ?>" data-setting="{{ data.name }}">
					<option value=""><?php esc_html_e( 'None', 'jupiterx-core' ); ?></option>
					<?php foreach ( self::get_animations() as $animation_name => $animation_title ) : ?>
						<option value="<?php echo esc_attr( $animation_name ); ?>"><?php echo esc_html( $animation_title ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
