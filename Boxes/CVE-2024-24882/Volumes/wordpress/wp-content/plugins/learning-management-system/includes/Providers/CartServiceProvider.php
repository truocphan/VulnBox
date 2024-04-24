<?php
/**
 * Cart model service provider.
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\Cart\Cart;
use Masteriyo\Cart\Fees;
use Masteriyo\Cart\Totals;

class CartServiceProvider extends AbstractServiceProvider {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $provides = array(
		'cart',
		'cart.fees',
		'cart.totals',
		'\Masteriyo\Cart\Cart',
		'\Masteriyo\Cart\Fees',
		'\Masteriyo\Cart\Totals',
	);

	/**
	 * This is where the magic happens, within the method you can
	* access the container and register or retrieve anything
	* that you need to, but remember, every alias registered
	* within this method must be declared in the `$provides` array.
	*
	* @since 1.0.0
	*/
	public function register() {
		$this->getContainer()->add( 'cart.fees', Fees::class );

		$this->getContainer()->add( 'cart.totals', Totals::class );

		$this->getContainer()->add( 'cart', Cart::class, true )
			->addArgument( 'session' )
			->addArgument( 'notice' )
			->addArgument( 'cart.fees' );

	}
}
