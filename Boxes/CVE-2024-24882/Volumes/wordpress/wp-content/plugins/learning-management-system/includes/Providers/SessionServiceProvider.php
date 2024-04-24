<?php
/**
 * Session service provider.
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\Session\Session;
use Masteriyo\Repository\SessionRepository;


class SessionServiceProvider extends AbstractServiceProvider {
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
		'session',
		'session.store',
		'\Masteriyo\Session\Session',
		'\Masteriyo\Repository\SessionRepository',
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
		$this->getContainer()
			->add( 'session.store', SessionRepository::class );

		$this->getContainer()
			->add( 'session', Session::class, true )
			->addArgument( 'session.store' );
	}
}
