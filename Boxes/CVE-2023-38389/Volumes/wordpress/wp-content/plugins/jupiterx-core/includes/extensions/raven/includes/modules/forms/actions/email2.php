<?php

/**
 * Add form Email 2 action.
 *
 * @package JupiterX_Core\Raven
 * @since 2.5.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Actions;

defined( 'ABSPATH' ) || die();

/**
 * Email 2 Action.
 *
 * Initializing the email 2 action by extending email action.
 *
 * @since 2.5.0
 */
class Email2 extends Email {

	/**
	 * Get name.
	 *
	 * @since 2.5.0
	 * @access public
	 */
	public function get_name() {
		return 'email2';
	}

	/**
	 * Get title.
	 *
	 * @since 2.5.0
	 * @access public
	 */
	public function get_title() {
		return __( 'Email 2', 'jupiterx-core' );
	}

	/**
	 * Is private.
	 *
	 * @since 2.5.0
	 * @access public
	 */
	public function is_private() {
		return false;
	}

	/**
	 * Get email action number.
	 * This number is only used to distinguish between "Email" and "Email 2" actions
	 *
	 * @since 2.5.0
	 * @access public
	 */
	protected function get_action_id() {
		return '2';
	}
}
