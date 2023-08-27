<?php
/**
 * Add form Reset Password action.
 *
 * @package JupiterX_Core\Raven
 * @since 2.0.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Actions;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Modules\Forms\Classes\Social_Login_Handler\{ Facebook, Google, Twitter };

/**
 * Social Login Action.
 *
 * Initializing the social login action by extending action base.
 *
 * @since 2.0.0
 */
class Social_Login extends Action_Base {
	public function __construct() {
		new Google();
		new Facebook();
		new Twitter();
	}

	/**
	 * Get name.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function get_name() {
		return 'social-login';
	}

	/**
	 * Get title.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function get_title() {
		return __( 'Social Login', 'jupiterx-core' );
	}

	/**
	 * Is private.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function is_private() {
		return true;
	}

	/**
	 * Update controls.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param object $widget Widget instance.
	 */
	public function update_controls( $widget ) {}

	/**
	 * Run action.
	 *
	 * Social Login.
	 *
	 * @since 2.0.0
	 * @access public
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 */
	public static function run( $ajax_handler ) {
		$social      = $ajax_handler->record['social_network'];
		$social_ajax = '\JupiterX_Core\Raven\Modules\Forms\Classes\Social_Login_Handler\\' . $social;
		$network     = new $social_ajax();

		$network->ajax_handler( $ajax_handler );
	}
}
