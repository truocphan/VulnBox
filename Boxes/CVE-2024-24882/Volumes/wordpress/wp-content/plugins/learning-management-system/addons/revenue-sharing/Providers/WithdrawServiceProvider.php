<?php
/**
 * Withdraw service provider.
 *
 * @since 1.6.14
 * @package \Masteriyo\Addons\RevenueSharing\Providers
 */

namespace Masteriyo\Addons\RevenueSharing\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Masteriyo\Addons\RevenueSharing\Enums\WithdrawStatus;
use Masteriyo\Addons\RevenueSharing\Controllers\WithdrawsController;
use Masteriyo\Addons\RevenueSharing\Models\Withdraw;
use Masteriyo\Addons\RevenueSharing\Repository\WithdrawRepository;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Withdraw service provider.
 *
 * @since 1.6.14
 */
class WithdrawServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.6.14
	 *
	 * @var array
	 */
	protected $provides = array(
		'withdraw',
		'withdraw.store',
		'withdraw.rest',
		WithdrawRepository::class,
	);

	/**
	 * This is where the magic happens, within the method you can
	 * access the container and register or retrieve anything
	 * that you need to, but remember, every alias registered
	 * within this method must be declared in the `$provides` array.
	 *
	 * @since 1.6.14
	 */
	public function register() {
		$this->getContainer()->add( 'withdraw.store', WithdrawRepository::class );
		$this->getContainer()->add( 'withdraw', Withdraw::class )
			->addArgument( 'withdraw.store' );
		$this->getContainer()->add( 'withdraw.rest', WithdrawsController::class )
			->addArgument( 'permission' );
	}

	/**
	 * In much the same way, this method has access to the container
	 * itself and can interact with it however you wish, the difference
	 * is that the boot method is invoked as soon as you register
	 * the service provider with the container meaning that everything
	 * in this method is eagerly loaded.
	 *
	 * If you wish to apply inflectors or register further service providers
	 * from this one, it must be from a bootable service provider like
	 * this one, otherwise they will be ignored.
	 *
	 * @since 1.6.14
	 */
	public function boot() {
		add_action( 'init', array( $this, 'register_withdraw_statuses' ), 0 );
	}

	/**
	 * Register withdraw statuses.
	 *
	 * @since 1.6.14
	 */
	public function register_withdraw_statuses() {
		foreach ( WithdrawStatus::list() as $status => $args ) {
			register_post_status( $status, $args );
		}
	}
}
