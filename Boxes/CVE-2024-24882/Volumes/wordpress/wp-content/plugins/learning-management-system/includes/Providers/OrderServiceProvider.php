<?php
/**
 * Order model service provider.
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use Masteriyo\OrderNotes;
use Masteriyo\Models\Order\Order;
use Masteriyo\Models\Order\OrderItem;
use Masteriyo\Repository\OrderRepository;
use Masteriyo\Models\Order\OrderItemCourse;
use Masteriyo\Repository\OrderItemRepository;
use Masteriyo\Repository\OrderItemCourseRepository;
use Masteriyo\RestApi\Controllers\Version1\OrdersController;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\RestApi\Controllers\Version1\OrderItemsController;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Masteriyo\Enums\CommentType;
use Masteriyo\PostType\PostType;

class OrderServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface {
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
		'order',
		'order.store',
		'order.rest',
		'\Masteriyo\RestApi\Controllers\Version1\OrdersController',
		'order-item',
		'order-item.store',
		'order-item.rest',
		'\Masteriyo\RestApi\Controllers\Version1\OrderItemsController',
		'order-item.course',
		'order-item.course.store',
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
		$this->getContainer()->add( 'order.store', OrderRepository::class );

		$this->getContainer()->add( 'order.rest', OrdersController::class )
			->addArgument( 'permission' );

		$this->getContainer()->add( '\Masteriyo\RestApi\Controllers\Version1\OrdersController' )
			->addArgument( 'permission' );

		$this->getContainer()->add( 'order', Order::class )
			->addArgument( 'order.store' );

		$this->getContainer()->add( 'order-item.store', OrderItemRepository::class );

		$this->getContainer()->add( 'order-item.course.store', OrderItemCourseRepository::class );

		$this->getContainer()->add( 'order-item.rest', OrderItemsController::class )
			->addArgument( 'permission' );

		$this->getContainer()->add( '\Masteriyo\RestApi\Controllers\Version1\OrderItemsController' )
			->addArgument( 'permission' );

		$this->getContainer()->add( 'order-item', OrderItem::class )
			->addArgument( 'order-item.store' );

		$this->getContainer()->add( 'order-item.course', OrderItemCourse::class )
			->addArgument( 'order-item.course.store' );

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
		add_action( 'parse_comment_query', array( $this, 'remove_order_note_from_query' ) );
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
		if ( PostType::ORDER === get_post_type( $post_id ) ) {
			$open = false;
		}
		return $open;
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

		if ( $comment && PostType::ORDER === get_post_type( $comment->comment_post_ID ) ) {
			$emails = array( get_option( 'admin_email' ) );
		}

		return $emails;
	}

	/**
	 * Remove the course review from the comments query.
	 *
	 * @since 1.5.43
	 *
	 * @param \WP_Comment_Query $query
	 */
	public function remove_order_note_from_query( $query ) {
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
		$query->query_vars['type__not_in'] = array_unique( array_merge( $query->query_vars['type__not_in'], array( CommentType::ORDER_NOTE ) ) );
	}
}
