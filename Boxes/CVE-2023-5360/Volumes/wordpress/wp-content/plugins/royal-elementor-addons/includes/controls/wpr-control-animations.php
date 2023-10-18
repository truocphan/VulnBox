<?php
namespace WprAddons\Includes\Controls;

use Elementor\Base_Data_Control;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}



/**
* Animation Control for Elements.
*
* A base control for creating entrance animation control. Displays a select box
* with the available entrance animation effects @see WPR_Control_Animations::get_animations() .
*/
class WPR_Control_Animations extends Base_Data_Control {

	/**
	* List of animations.
	*/
	private static $_animations;

	/**
	* Get control type.
	*/
	public function get_type() {
		return 'wpr-animations';
	}

	/**
	* Get animations list.
	* Retrieve the list of all the available animations.
	*/
	public static function get_animations() {

		if ( is_null( self::$_animations ) ) {
			self::$_animations = [
				'Fade' => [
					'fade-in' => 'Fade In',
					'fade-out' => 'Fade Out',
				],
				'Slide' => [
					'pro-sltp' => 'Top (Pro)',
					'pro-slrt' => 'Right (Pro)',
					'pro-slxrt' => 'X Right (Pro)',
					'pro-slbt' => 'Bottom (Pro)',
					'pro-sllt' => 'Left (Pro)',
					'pro-slxlt' => 'X Left (Pro)',
				],
				'Skew' => [
					'pro-sktp' => 'Top (Pro)',
					'pro-skrt' => 'Right (Pro)',
					'pro-skbt' => 'Bottom (Pro)',
					'pro-sklt' => 'Left (Pro)',
				],
				'Scale' => [
					'pro-scup' => 'Up (Pro)',
					'pro-scdn' => 'Down (Pro)',
				],
				'Roll' => [
					'pro-rllt' => 'Left (Pro)',
					'pro-rlrt' => 'Right (Pro)',
				],
			];
		}

		if ( wpr_fs()->can_use_premium_code() && defined('WPR_ADDONS_PRO_VERSION') ) {
			self::$_animations = \WprAddonsPro\Includes\Controls\WPR_Control_Animations_Pro::wpr_animations();
		}

		return self::$_animations;
	}

	/**
	* Render animations control template.
	*
	* Used to generate the control HTML in the editor using Underscore JS
	* template. The variables for the class are available using `data` JS
	* object.
	*/
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<select id="<?php echo esc_attr($control_uid); ?>" data-setting="{{ data.name }}">
					<option value="none"><?php echo esc_html__( 'None', 'wpr-addons' ); ?></option>
					<?php foreach ( self::get_animations() as $animations_group_name => $animations_group ) : ?>
						<optgroup label="<?php echo esc_attr($animations_group_name); ?>">
							<?php foreach ( $animations_group as $animation_name => $animation_title ) : ?>
								<option value="<?php echo esc_attr($animation_name); ?>"><?php echo esc_html($animation_title); ?></option>
							<?php endforeach; ?>
						</optgroup>
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


/**
* Animation Control for Overlays.
*
* A base control for creating entrance animation control. Displays a select box
* with the available entrance animation effects @see WPR_Control_Animations::get_animations() .
*/
class WPR_Control_Animations_Alt extends WPR_Control_Animations {

	/**
	* Get control type.
	*/
	public function get_type() {
		return 'wpr-animations-alt';
	}

	/**
	* Render animations control template.
	*
	* Used to generate the control HTML in the editor using Underscore JS
	* template. The variables for the class are available using `data` JS
	* object.
	*/
	public function content_template() {
		$animations = self::get_animations();
		$control_uid = $this->get_control_uid();

		// Remove Extra
		unset($animations['Slide']['slide-x-right']);
		unset($animations['Slide']['slide-x-left']);
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<select id="<?php echo esc_attr($control_uid); ?>" data-setting="{{ data.name }}">
					<option value="none"><?php echo esc_html__( 'None', 'wpr-addons' ); ?></option>
					<?php foreach ( $animations as $animations_group_name => $animations_group ) : ?>
						<optgroup label="<?php echo esc_attr($animations_group_name); ?>">
							<?php foreach ( $animations_group as $animation_name => $animation_title ) : ?>
								<option value="<?php echo esc_attr($animation_name); ?>"><?php echo esc_html($animation_title); ?></option>
							<?php endforeach; ?>
						</optgroup>
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


/**
* Animation Control for Buttons.
*
* A base control for creating button animation control. Displays a select box
* with the available button animation effects @see WPR_Control_Button_Animations::get_animations() .
*/
class WPR_Control_Button_Animations extends Base_Data_Control {

	/**
	* List of animations.
	*/
	private static $_animations;

	/**
	* Get control type.
	*/
	public function get_type() {
		return 'wpr-button-animations';
	}

	/**
	* Get animations list.
	* Retrieve the list of all the available animations.
	*/
	public static function get_animations() {
		if ( is_null( self::$_animations ) ) {
			self::$_animations = [
				'Animations' => [
					'wpr-button-none' => esc_html__( 'None', 'wpr-addons' ),
					'pro-wnt' => esc_html__( 'Winona + Text (Pro)', 'wpr-addons' ),
					'pro-rlt' => esc_html__( 'Ray Left + Text (Pro)', 'wpr-addons' ),
					'pro-rrt' => esc_html__( 'Ray Right + Text (Pro)', 'wpr-addons' ),
					'wpr-button-wayra-left' => esc_html__( 'Wayra Left', 'wpr-addons' ),
					'wpr-button-wayra-right' => esc_html__( 'Wayra Right', 'wpr-addons' ),
					'wpr-button-isi-left' => esc_html__( 'Isi Left', 'wpr-addons' ),
					'wpr-button-isi-right' => esc_html__( 'Isi Right', 'wpr-addons' ),
					'wpr-button-aylen' => esc_html__( 'Aylen', 'wpr-addons' ),
					'wpr-button-antiman' => esc_html__( 'Antiman', 'wpr-addons' ),
				],
				'2D Animations' => [
					'elementor-animation-grow' => esc_html__( 'Grow', 'wpr-addons' ),
					'elementor-animation-shrink' => esc_html__( 'Shrink', 'wpr-addons' ),
					'elementor-animation-pulse' => esc_html__( 'Pulse', 'wpr-addons' ),
					'elementor-animation-pulse-grow' => esc_html__( 'Pulse Grow', 'wpr-addons' ),
					'elementor-animation-pulse-shrink' => esc_html__( 'Pulse Shrink', 'wpr-addons' ),
					'elementor-animation-push' => esc_html__( 'Push', 'wpr-addons' ),
					'elementor-animation-pop' => esc_html__( 'Pop', 'wpr-addons' ),
					'elementor-animation-bounce-in' => esc_html__( 'Bounce In', 'wpr-addons' ),
					'elementor-animation-bounce-out' => esc_html__( 'Bounce Out', 'wpr-addons' ),
					'elementor-animation-rotate' => esc_html__( 'Rotate', 'wpr-addons' ),
					'elementor-animation-grow-rotate' => esc_html__( 'Grow Rotate', 'wpr-addons' ),
					'elementor-animation-float' => esc_html__( 'Float', 'wpr-addons' ),
					'elementor-animation-sink' => esc_html__( 'Sink', 'wpr-addons' ),
					'elementor-animation-bob' => esc_html__( 'Bob', 'wpr-addons' ),
					'elementor-animation-hang' => esc_html__( 'Hang', 'wpr-addons' ),
					'elementor-animation-skew' => esc_html__( 'Skew', 'wpr-addons' ),
					'elementor-animation-skew-forward' => esc_html__( 'Skew Forward', 'wpr-addons' ),
					'elementor-animation-skew-backward' => esc_html__( 'Skew Backward', 'wpr-addons' ),
					'elementor-animation-wobble-horizontal' => esc_html__( 'Wobble Horizontal', 'wpr-addons' ),
					'elementor-animation-wobble-vertical' => esc_html__( 'Wobble Vertical', 'wpr-addons' ),
					'elementor-animation-wobble-to-bottom-right' => esc_html__( 'Wobble To Bottom Right', 'wpr-addons' ),
					'elementor-animation-wobble-to-top-right' => esc_html__( 'Wobble To Top Right', 'wpr-addons' ),
					'elementor-animation-wobble-top' => esc_html__( 'Wobble Top', 'wpr-addons' ),
					'elementor-animation-wobble-bottom' => esc_html__( 'Wobble Bottom', 'wpr-addons' ),
					'elementor-animation-wobble-skew' => esc_html__( 'Wobble Skew', 'wpr-addons' ),
					'elementor-animation-buzz' => esc_html__( 'Buzz', 'wpr-addons' ),
					'elementor-animation-buzz-out' => esc_html__( 'Buzz Out', 'wpr-addons' ),
					'elementor-animation-forward' => esc_html__( 'Forward', 'wpr-addons' ),
					'elementor-animation-backward' => esc_html__( 'Backward', 'wpr-addons' ),
				],
				'Background Animations' => [
					'wpr-button-back-pulse' => esc_html__( 'Back Pulse', 'wpr-addons' ),
					'wpr-button-sweep-to-right' => esc_html__( 'Sweep To Right', 'wpr-addons' ),
					'wpr-button-sweep-to-left' => esc_html__( 'Sweep To Left', 'wpr-addons' ),
					'wpr-button-sweep-to-bottom' => esc_html__( 'Sweep To Bottom', 'wpr-addons' ),
					'wpr-button-sweep-to-top' => esc_html__( 'Sweep To top', 'wpr-addons' ),
					'wpr-button-bounce-to-right' => esc_html__( 'Bounce To Right', 'wpr-addons' ),
					'wpr-button-bounce-to-left' => esc_html__( 'Bounce To Left', 'wpr-addons' ),
					'wpr-button-bounce-to-bottom' => esc_html__( 'Bounce To Bottom', 'wpr-addons' ),
					'wpr-button-bounce-to-top' => esc_html__( 'Bounce To Top', 'wpr-addons' ),
					'wpr-button-radial-out' => esc_html__( 'Radial Out', 'wpr-addons' ),
					'wpr-button-radial-in' => esc_html__( 'Radial In', 'wpr-addons' ),
					'wpr-button-rectangle-in' => esc_html__( 'Rectangle In', 'wpr-addons' ),
					'wpr-button-rectangle-out' => esc_html__( 'Rectangle Out', 'wpr-addons' ),
					'wpr-button-shutter-in-horizontal' => esc_html__( 'Shutter In Horizontal', 'wpr-addons' ),
					'wpr-button-shutter-out-horizontal' => esc_html__( 'Shutter Out Horizontal', 'wpr-addons' ),
					'wpr-button-shutter-in-vertical' => esc_html__( 'Shutter In Vertical', 'wpr-addons' ),
					'wpr-button-shutter-out-vertical' => esc_html__( 'Shutter Out Vertical', 'wpr-addons' ),
					'wpr-button-underline-from-left' => esc_html__( 'Underline From Left', 'wpr-addons' ),
					'wpr-button-underline-from-center' => esc_html__( 'Underline From Center', 'wpr-addons' ),
					'wpr-button-underline-from-right' => esc_html__( 'Underline From Right', 'wpr-addons' ),
					'wpr-button-underline-reveal' => esc_html__( 'Underline Reveal', 'wpr-addons' ),
					'wpr-button-overline-reveal' => esc_html__( 'Overline Reveal', 'wpr-addons' ),
					'wpr-button-overline-from-left' => esc_html__( 'Overline From Left', 'wpr-addons' ),
					'wpr-button-overline-from-center' => esc_html__( 'Overline From Center', 'wpr-addons' ),
					'wpr-button-overline-from-right' => esc_html__( 'Overline From Right', 'wpr-addons' ),
				]
			];
		}

		if ( wpr_fs()->can_use_premium_code() && defined('WPR_ADDONS_PRO_VERSION') ) {
			self::$_animations = \WprAddonsPro\Includes\Controls\WPR_Control_Animations_Pro::wpr_button_animations();
		}

		return self::$_animations;
	}

	/**
	* Render animations control template.
	*
	* Used to generate the control HTML in the editor using Underscore JS
	* template. The variables for the class are available using `data` JS
	* object.
	*/
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<select id="<?php echo esc_attr($control_uid); ?>" data-setting="{{ data.name }}">
					<?php foreach ( self::get_animations() as $animations_group_name => $animations_group ) : ?>
						<optgroup label="<?php echo esc_attr($animations_group_name); ?>">
							<?php foreach ( $animations_group as $animation_name => $animation_title ) : ?>
								<option value="<?php echo esc_attr($animation_name); ?>"><?php echo esc_html($animation_title); ?></option>
							<?php endforeach; ?>
						</optgroup>
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