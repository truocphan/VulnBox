<?php
/**
 * Quiz model service provider.
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\Models\Quiz;
use Masteriyo\Repository\QuizRepository;
use Masteriyo\RestApi\Controllers\Version1\QuizesController;

class QuizServiceProvider extends AbstractServiceProvider {
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
		'quiz',
		'quiz.store',
		'quiz.rest',
		'mto-quiz',
		'mto-quiz.store',
		'mto-quiz.rest',
		'\Masteriyo\RestApi\Controllers\Version1\QuizesController',
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
		$this->getContainer()->add( 'quiz.store', QuizRepository::class );

		$this->getContainer()->add( 'quiz.rest', QuizesController::class )
			->addArgument( 'permission' );

		$this->getContainer()->add( '\Masteriyo\RestApi\Controllers\Version1\QuizesController' )
			->addArgument( 'permission' );

		$this->getContainer()->add( 'quiz', Quiz::class )
			->addArgument( 'quiz.store' );

		// Register based on post type.
		$this->getContainer()->add( 'mto-quiz.store', QuizRepository::class );

		$this->getContainer()->add( 'mto-quiz.rest', QuizesController::class )
			->addArgument( 'permission' );

		$this->getContainer()->add( 'mto-quiz', Quiz::class )
			->addArgument( 'mto-quiz.store' );

	}
}
