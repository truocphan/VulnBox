<?php
/**
 * Quiz attempt model service provider.
 *
 * @since 1.3.2
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\Models\QuizAttempt;
use Masteriyo\Repository\QuizAttemptRepository;
use Masteriyo\RestApi\Controllers\Version1\QuizAttemptsController;

class QuizAttemptServiceProvider extends AbstractServiceProvider {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.3.2
	 *
	 * @var array
	 */
	protected $provides = array(
		'quiz-attempt',
		'quiz-attempt.store',
		'quiz-attempt.rest',
		'\Masteriyo\RestApi\Controllers\Version1\QuizAttemptsController',
	);

	/**
	 * This is where the magic happens, within the method you can
	 * access the container and register or retrieve anything
	 * that you need to, but remember, every alias registered
	 * within this method must be declared in the `$provides` array.
	 *
	 * @since 1.3.2
	 */
	public function register() {
		$this->getContainer()->add( 'quiz-attempt.store', QuizAttemptRepository::class );

		$this->getContainer()->add( 'quiz-attempt.rest', QuizAttemptsController::class )
		->addArgument( 'permission' );

		$this->getContainer()->add( '\Masteriyo\RestApi\Controllers\Version1\QuizAttemptsController' )
		->addArgument( 'permission' );

		$this->getContainer()->add( 'quiz-attempt', QuizAttempt::class )
		->addArgument( 'quiz-attempt.store' );
	}
}
