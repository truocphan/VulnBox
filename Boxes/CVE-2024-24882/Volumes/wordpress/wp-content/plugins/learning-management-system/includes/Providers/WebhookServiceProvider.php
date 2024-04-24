<?php
/**
 * Webhook service provider.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use Exception;
use Masteriyo\Models\Webhook;
use Masteriyo\Query\WebhookQuery;
use Masteriyo\Enums\WebhookStatus;
use Masteriyo\Jobs\WebhookDeliveryJob;
use Masteriyo\Repository\WebhookRepository;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\RestApi\Controllers\Version1\WebhooksController;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

/**
 * Webhook service provider.
 *
 * @since 1.6.9
 */
class WebhookServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.6.9
	 *
	 * @var array
	 */
	protected $provides = array(
		'webhook',
		'webhook.store',
		'webhook.rest',
		'mto-webhook',
		'mto-webhook.store',
		'mto-webhook.rest',
	);

	/**
	 * This is where the magic happens, within the method you can
	 * access the container and register or retrieve anything
	 * that you need to, but remember, every alias registered
	 * within this method must be declared in the `$provides` array.
	 *
	 * @since 1.6.9
	 */
	public function register() {
		$this->getContainer()->add( 'webhook.store', WebhookRepository::class );

		$this->getContainer()->add( 'webhook.rest', WebhooksController::class )
			->addArgument( 'permission' );
		$this->getContainer()->add( 'webhook', Webhook::class )
			->addArgument( 'webhook.store' );

		// Register based on post type.
		$this->getContainer()->add( 'mto-webhook.store', WebhookRepository::class );

		$this->getContainer()->add( 'mto-webhook.rest', WebhooksController::class )
			->addArgument( 'permission' );

		$this->getContainer()->add( 'mto-webhook', Webhook::class )
			->addArgument( 'mto-webhook.store' );
	}

	/**
	 * This method is called after all service providers are registered.
	 *
	 * @since 1.6.9
	 */
	public function boot() {
		add_action( 'init', array( $this, 'register_webhook_listeners' ) );
	}

	/**
	 * Initializes the webhook system by hooking up active webhooks etc.
	 *
	 * @since 1.6.9
	 */
	public function register_webhook_listeners() {
		if ( ! is_blog_installed() ) {
			return;
		}

		$query     = new WebhookQuery(
			array(
				'status'   => array( WebhookStatus::ACTIVE ),
				'paginate' => false,
				'per_page' => -1,
				'limit'    => -1,
			)
		);
		$webhooks  = $query->get_webhooks();
		$listeners = masteriyo_get_webhook_listeners();

		/**
		 * Filters boolean: True if action scheduler should be used for delivering webhooks. False otherwise.
		 *
		 * Default is False.
		 *
		 * @since 1.6.9
		 *
		 * @param boolean $queue Default is False.
		 */
		$queue = apply_filters( 'masteriyo_use_job_queue_for_webhooks', true );

		foreach ( $webhooks as $webhook ) {
			foreach ( $webhook->get_events() as $event_name ) {
				if ( ! isset( $listeners[ $event_name ] ) ) {
					continue;
				}

				$listener         = $listeners[ $event_name ];
				$deliver_callback = function( $webhook, $payload ) use ( $event_name ) {
					try {
						masteriyo_send_webhook( $event_name, $webhook, $payload );
					} catch ( Exception $e ) {
						error_log( 'Webhook: ' . $e->getMessage() );
					}
				};

				if ( $queue ) {
					$deliver_callback = function( $webhook, $payload ) use ( $event_name ) {
						as_enqueue_async_action(
							WebhookDeliveryJob::HOOK,
							array(
								'event_name' => $event_name,
								'webhook'    => $webhook,
								'payload'    => $payload,
							),
							'masteriyo-webhooks'
						);
					};
				}

				call_user_func( array( $listener, 'setup' ), $deliver_callback, $webhook );
			}
		}
	}
}
