<?php
/**
 * Queries service provider.
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\Query\CourseQuestionAnswerQuery;
use Masteriyo\Query\CourseReviewQuery;
use Masteriyo\Query\QuizReviewQuery;
use Masteriyo\Query\LessonQuery;
use Masteriyo\Query\OrderItemQuery;
use Masteriyo\Query\OrderQuery;
use Masteriyo\Query\QuizQuery;
use Masteriyo\Query\QuestionQuery;
use Masteriyo\Query\SectionQuery;

class QueriesServiceProvider extends AbstractServiceProvider {
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
		'query.sections',
		'query.lessons',
		'query.quizes',
		'query.questions',
		'query.orders',
		'query.order-items',
		'query.course-reviews',
		'query.course-qas',
		'query.quizes-reviews',

		'\Masteriyo\Query\SectionQuery',
		'\Masteriyo\Query\LessonQuery',
		'\Masteriyo\Query\QuizQuery',
		'\Masteriyo\Query\QuestionQuery',
		'\Masteriyo\Query\OrderQuery',
		'\Masteriyo\Query\OrderItemQuery',
		'\Masteriyo\Query\CourseReviewQuery',
		'\Masteriyo\Query\QuizReviewQuery',
		'\Masteriyo\Query\CourseQuestionAnswerQuery',
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
		$this->getContainer()->add( 'query.sections', SectionQuery::class );
		$this->getContainer()->add( 'query.lessons', LessonQuery::class );
		$this->getContainer()->add( 'query.quizes', QuizQuery::class );
		$this->getContainer()->add( 'query.questions', QuestionQuery::class );
		$this->getContainer()->add( 'query.orders', OrderQuery::class );
		$this->getContainer()->add( 'query.order-items', OrderItemQuery::class );
		$this->getContainer()->add( 'query.course-reviews', CourseReviewQuery::class );
		$this->getContainer()->add( 'query.quizes-reviews', QuizReviewQuery::class );
		$this->getContainer()->add( 'query.course-qas', CourseQuestionAnswerQuery::class );

		$this->getContainer()->add( '\Masteriyo\Query\SectionQuery' );
		$this->getContainer()->add( '\Masteriyo\Query\LessonQuery' );
		$this->getContainer()->add( '\Masteriyo\Query\QuizQuery' );
		$this->getContainer()->add( '\Masteriyo\Query\QuestionQuery' );
		$this->getContainer()->add( '\Masteriyo\Query\OrderQuery' );
		$this->getContainer()->add( '\Masteriyo\Query\OrderItemQuery' );
		$this->getContainer()->add( '\Masteriyo\Query\CourseReviewQuery' );
		$this->getContainer()->add( '\Masteriyo\Query\QuizReviewQuery' );

		$this->getContainer()->add( '\Masteriyo\Query\CourseQuestionAnswerQuery' );
	}
}
