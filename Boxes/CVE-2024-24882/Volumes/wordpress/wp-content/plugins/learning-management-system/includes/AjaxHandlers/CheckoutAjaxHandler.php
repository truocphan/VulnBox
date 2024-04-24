<?php
/**
 * Checkout Ajax handler.
 *
 * @since 1.4.3
 * @package Masteriyo\AjaxHandlers
 */

namespace Masteriyo\AjaxHandlers;

use Masteriyo\Abstracts\AjaxHandler;

/**
 * Checkout ajax handler.
 */
class CheckoutAjaxHandler extends AjaxHandler {

	/**
	 * Checkout ajax action.
	 *
	 * @since 1.4.3
	 * @var string
	 */
	public $action = 'masteriyo_checkout';

	/**
	 * Process checkout ajax request.
	 *
	 * @since 1.4.3
	 */
	public function register() {
		add_action( "wp_ajax_{$this->action}", array( $this, 'checkout' ) );
		add_action( "wp_ajax_nopriv_{$this->action}", array( $this, 'checkout' ) );
	}

	/**
	 * Process ajax checkout form.
	 *
	 * @since 1.4.3
	 */
	public function checkout() {
		masteriyo_maybe_define_constant( 'MASTERIYO_CHECKOUT', true );
		masteriyo( 'checkout' )->process_checkout();
		wp_die( 0 );
	}
}
