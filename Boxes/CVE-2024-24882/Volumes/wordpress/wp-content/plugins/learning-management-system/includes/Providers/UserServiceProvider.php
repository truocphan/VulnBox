<?php
/**
 * User model service provider.
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Models\User;
use Masteriyo\Models\Instructor;
use Masteriyo\Repository\UserRepository;
use Masteriyo\RestApi\Controllers\Version1\UsersController;
use League\Container\ServiceProvider\AbstractServiceProvider;

class UserServiceProvider extends AbstractServiceProvider {
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
		'user',
		'user.store',
		'user.rest',
		'\Masteriyo\RestApi\Controllers\Version1\UsersController',

		'instructor',
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
		$this->getContainer()->add( 'user.store', UserRepository::class );

		$this->getContainer()->add( 'user.rest', UsersController::class )
		->addArgument( 'permission' );

		$this->getContainer()->add( '\Masteriyo\RestApi\Controllers\Version1\UsersController' )
		->addArgument( 'permission' );

		$this->getContainer()->add( 'user', User::class )
		->addArgument( 'user.store' );

		$this->getContainer()->add( 'instructor', Instructor::class )
		->addArgument( 'user.store' );
	}
}
