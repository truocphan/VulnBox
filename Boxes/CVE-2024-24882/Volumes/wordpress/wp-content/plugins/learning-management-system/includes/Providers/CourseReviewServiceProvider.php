<?php
/**
 * Course review model service provider.
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Enums\CommentType;
use Masteriyo\PostType\PostType;
use Masteriyo\Models\CourseReview;
use Masteriyo\Repository\CourseReviewRepository;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\RestApi\Controllers\Version1\CourseReviewsController;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use PHP_CodeSniffer\Tokenizers\Comment;

class CourseReviewServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.5.43
	 *
	 * @var array
	 */
	protected $provides = array(
		'course_review',
		'course_review.store',
		'course_review.rest',
		'\Masteriyo\RestApi\Controllers\Version1\CourseReviewsController',
	);

	/**
	 * This is where the magic happens, within the method you can
	 * access the container and register or retrieve anything
	 * that you need to, but remember, every alias registered
	 * within this method must be declared in the `$provides` array.
	 *
	 * @since 1.5.43
	 */
	public function register() {
		$this->getContainer()->add( 'course_review.store', CourseReviewRepository::class );

		$this->getContainer()->add( 'course_review.rest', CourseReviewsController::class )
		->addArgument( 'permission' );

		$this->getContainer()->add( '\Masteriyo\RestApi\Controllers\Version1\CourseReviewsController' )
		->addArgument( 'permission' );

		$this->getContainer()->add( 'course_review', CourseReview::class )
		->addArgument( 'course_review.store' );
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
	 * @since 1.5.43
	 */
	public function boot() {
		add_filter( 'comments_open', array( $this, 'comments_open' ), 10, 2 );
		add_action( 'comment_moderation_recipients', array( $this, 'comment_moderation_recipients' ), 10, 2 );
		add_action( 'wp_update_comment_count', array( $this, 'wp_update_comment_count' ) );
		add_filter( 'get_avatar_comment_types', array( $this, 'add_avatar_for_review_comment_type' ) );
		add_action( 'parse_comment_query', array( $this, 'remove_course_review_from_query' ) );
	}

	/**
	 * See if comments are open.
	 *
	 * @since 1.5.43
	 *
	 * @param  bool $open    Whether the current post is open for comments.
	 * @param  int  $post_id Post ID.
	 *
	 * @return bool
	 */
	public function comments_open( $open, $post_id ) {
		if ( PostType::COURSE === get_post_type( $post_id ) ) {
			$open = false;
		}
		return $open;
	}

	/**
	 * Ensure course average rating and review count is kept up to date.
	 *
	 * @since 1.5.43
	 *
	 * @param int $post
	 */
	public function wp_update_comment_count( $post ) {
		if ( PostType::COURSE === get_post_type( $post ) ) {
			$this->update_course_review_stats( $post );
		}
	}

	/**
	 * Update average rating and review counts of course.
	 *
	 * @since 1.5.43
	 *
	 * @param int|string|\WP_Post|\Masteriyo\Models\Course $course
	 */
	public function update_course_review_stats( $course ) {
		$course = masteriyo_get_course( $course );

		if ( is_null( $course ) ) {
			return;
		}

		$course->set_rating_counts( $this->get_rating_counts( $course ) );
		$course->set_average_rating( $this->get_average_rating( $course ) );
		$course->set_review_count( $this->get_review_count( $course ) );
		$course->save();
	}

	/**
	 * Make sure WP displays avatars for comments with the `course_review` type.
	 *
	 * @since 1.5.43
	 *
	 * @param  array $comment_types Comment types.
	 *
	 * @return array
	 */
	public function add_avatar_for_review_comment_type( $comment_types ) {
		return array_merge( $comment_types, array( CommentType::COURSE_REVIEW ) );
	}

	/**
	 * Modify recipient of review email.
	 *
	 * @since 1.5.43
	 *
	 * @param array $emails     Emails.
	 * @param int   $comment_id Comment ID.
	 *
	 * @return array
	 */
	public function comment_moderation_recipients( $emails, $comment_id ) {
		$comment = get_comment( $comment_id );

		if ( $comment && PostType::COURSE === get_post_type( $comment->comment_post_ID ) ) {
			$emails = array( get_option( 'admin_email' ) );
		}

		return $emails;
	}

	/**
	 * Get course rating for a course. Please note this is not cached.
	 *
	 * @since 1.5.43
	 *
	 * @param \Masteriyo\Models\Course $course course instance.
	 *
	 * @return float
	 */
	public function get_average_rating( $course ) {
		global $wpdb;

		$count = $course->get_rating_count();

		if ( $count ) {
			$ratings = $wpdb->get_var(
				$wpdb->prepare(
					"
					SELECT SUM(comment_karma) FROM $wpdb->comments
					WHERE comment_post_ID = %d
					AND comment_approved = '1'
					AND comment_type = 'mto_course_review'
					AND comment_parent = 0
					",
					$course->get_id()
				)
			);
			$average = number_format( $ratings / $count, 2, '.', '' );
		} else {
			$average = 0;
		}

		return $average;
	}

	/**
	 * Get course review count for a course (not replies). Please note this is not cached.
	 *
	 * @since 1.5.43
	 *
	 * @param \Masteriyo\Models\Course $course course instance.
	 *
	 * @return int
	 */
	public function get_review_count( $course ) {
		global $wpdb;

		$count = $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT COUNT(*) FROM $wpdb->comments
				WHERE comment_parent = 0
				AND comment_post_ID = %d
				AND comment_approved = '1'
				AND comment_type = 'mto_course_review'
				",
				$course->get_id()
			)
		);

		return $count;
	}

	/**
	 * Get course rating count for a course. Please note this is not cached.
	 *
	 * @since 1.5.43
	 *
	 * @param \Masteriyo\Models\Course $course course instance.
	 *
	 * @return int[]
	 */
	public function get_rating_counts( $course ) {
		global $wpdb;

		$counts     = array();
		$raw_counts = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT comment_karma, COUNT( * ) as rating_count FROM $wpdb->comments
				WHERE comment_post_ID = %d
				AND comment_approved = '1'
				AND comment_karma > 0
				AND comment_type = 'mto_course_review'
				GROUP BY comment_karma
				",
				$course->get_id()
			)
		);

		foreach ( $raw_counts as $count ) {
			$counts[ $count->comment_karma ] = absint( $count->rating_count ); // WPCS: slow query ok.
		}

		return $counts;
	}

	/**
	 * Remove the course review from the comments query.
	 *
	 * @since 1.5.43
	 *
	 * @param \WP_Comment_Query $query
	 */
	public function remove_course_review_from_query( $query ) {
		// Bail early if  global pagenow is not set or isn't admin dashboard.
		if ( ! isset( $GLOBALS['pagenow'] ) || ! is_admin() ) {
			return;
		}

		// Bail if the page is not wp comments list page or dashboard.
		if ( ! in_array( $GLOBALS['pagenow'], array( 'edit-comments.php', 'index.php' ), true ) ) {
			return;
		}

		if ( ! isset( $query->query_vars['type__not_in'] ) ) {
			$query->query_vars['type__not_in'] = array();
		}

		$query->query_vars['type__not_in'] = (array) $query->query_vars['type__not_in'];
		$query->query_vars['type__not_in'] = array_unique( array_merge( $query->query_vars['type__not_in'], array( CommentType::COURSE_REVIEW ) ) );
	}
}
