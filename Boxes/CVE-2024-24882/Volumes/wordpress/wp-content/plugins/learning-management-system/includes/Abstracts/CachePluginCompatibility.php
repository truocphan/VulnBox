<?php
/**
 * Abstract Cache plugin compatibility.
 *
 * Every class which indents to support compatible with Cache Plugin should extends from it.
 *
 * @since 1.5.36
 * @package Masteriyo\Abstracts
 */

namespace Masteriyo\Abstracts;

/**
 * Abstract cache plugin compatibility.
 */
abstract class CachePluginCompatibility {

	/**
	 * Cache plugin slug.
	 *
	 * @since 1.5.36
	 *
	 * @var string
	 */
	protected $plugin = '';

	/**
	 * Initialize.
	 *
	 * @since 1.5.36
	 * @return void
	 */
	public function init() {
		add_action( 'wp', array( $this, 'do_not_cache_page' ) );
	}

	/**
	 * Callback for do not cache page.
	 *
	 * @since 1.5.36
	 */
	public function do_not_cache_page() {
		// Bail early if the associated cache plugin is not active.
		if ( ! $this->is_plugin_active() ) {
			return;
		}

		if ( masteriyo_is_checkout_page() ) {
			$this->do_not_cache();
		}

		if ( masteriyo_is_lost_password_page() ) {
			$this->do_not_cache();
		}

		if ( masteriyo_is_signin_page() ) {
			$this->do_not_cache();
		}

		if ( masteriyo_is_signup_page() ) {
			$this->do_not_cache();
		}

		if ( masteriyo_is_instructor_registration_page() ) {
			$this->do_not_cache();
		}
	}

	/**
	 * Return true if the plugin is active.
	 *
	 * @since 1.5.36
	 *
	 * @return boolean
	 */
	protected function is_plugin_active() {
		return function_exists( 'is_plugin_active' ) && is_plugin_active( $this->plugin );
	}

	/**
	 * Do not page.
	 *
	 * @since 1.5.36
	 */
	abstract public function do_not_cache();
}
