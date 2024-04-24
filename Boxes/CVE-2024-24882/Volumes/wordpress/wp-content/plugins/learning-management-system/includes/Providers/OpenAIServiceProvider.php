<?php
/**
 * OpenAI service provider.
 *
 * @since 1.6.15
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\RestApi\Controllers\Version1\OpenAIController;

/**
 * OpenAI service provider.
 *
 * @since 1.6.15
 */
class OpenAIServiceProvider extends AbstractServiceProvider {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.6.15
	 *
	 * @var array
	 */
	protected $provides = array(
		'openai',
		'openai.rest',
		'\Masteriyo\RestApi\Controllers\Version1\OpenAIController',
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

		$this->getContainer()->add( 'openai.rest', OpenAIController::class )
			->addArgument( 'permission' );

		$this->getContainer()->add( '\Masteriyo\RestApi\Controllers\Version1\OpenAIController' )
			->addArgument( 'permission' );
	}

	/**
	 * This method is called after all service providers are registered.
	 *
	 * @since 1.6.15
	 */
	public function boot() {
	}
}
