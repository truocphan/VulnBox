<?php
/**
 * Helper functions related to webhooks.
 *
 * @since 1.6.9
 */

use Masteriyo\Abstracts\Listener;
use Masteriyo\Listeners\Webhook\CourseCompletedListener;
use Masteriyo\Models\Webhook;
use Masteriyo\Listeners\Webhook\NewEnrollmentListener;
use Masteriyo\Listeners\Webhook\QuizPublishedListener;
use Masteriyo\Listeners\Webhook\CoursePublishedListener;
use Masteriyo\Listeners\Webhook\LessonPublishedListener;
use Masteriyo\Listeners\Webhook\NewCourseReviewListener;
use Masteriyo\Listeners\Webhook\LessonCompletedListener;
use Masteriyo\Listeners\Webhook\NewCourseQuestionListener;
use Masteriyo\Listeners\Webhook\NewCourseQuestionReplyListener;
use Masteriyo\Listeners\Webhook\NewStudentRegistrationListener;
use Masteriyo\Listeners\Webhook\NewInstructorRegistrationListener;
use Masteriyo\Listeners\Webhook\QuizAttemptStatusChangeListener;
use Masteriyo\Listeners\Webhook\QuizCompletedListener;

/**
 * Get webhook.
 *
 * @since 1.6.9
 *
 * @param int|\Masteriyo\Models\Webhook|\WP_Post $webhook webhook id or webhook Model or Post.
 *
 * @return \Masteriyo\Models\Webhook|null
 */
function masteriyo_get_webhook( $webhook ) {
	$webhook_obj   = masteriyo( 'webhook' );
	$webhook_store = masteriyo( 'webhook.store' );

	if ( $webhook instanceof Webhook ) {
		$id = $webhook->get_id();
	} elseif ( $webhook instanceof \WP_Post ) {
		$id = $webhook->ID;
	} else {
		$id = absint( $webhook );
	}

	try {
		$id = absint( $id );
		$webhook_obj->set_id( $id );
		$webhook_store->read( $webhook_obj );
	} catch ( \Exception $e ) {
		return null;
	}

	/**
	 * Filters webhook object.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\Webhook $webhook_obj webhook object.
	 * @param int|\Masteriyo\Models\Webhook|\WP_Post $webhook webhook id or webhook Model or Post.
	 */
	return apply_filters( 'masteriyo_get_webhook', $webhook_obj, $webhook );
}

/**
 * Get the list of available webhook events.
 *
 * @since 1.6.9
 *
 * @return array
 */
function masteriyo_get_webhook_listeners() {
	/**
	 * Filters list of classes that handles webhook events. The classes must extend the `Masteriyo\Abstracts\Listener` abstract class.
	 *
	 * @since 1.6.9
	 *
	 * @param string[] $classes Classes that extend the `Masteriyo\Abstracts\Listener` abstract class.
	 */
	$listeners = array_unique(
		apply_filters(
			'masteriyo_webhook_listeners',
			array(
				CoursePublishedListener::class,
				LessonPublishedListener::class,
				QuizPublishedListener::class,
				NewStudentRegistrationListener::class,
				NewInstructorRegistrationListener::class,
				NewEnrollmentListener::class,
				CourseCompletedListener::class,
				LessonCompletedListener::class,
				QuizAttemptStatusChangeListener::class,
				QuizCompletedListener::class,
				NewCourseReviewListener::class,
				NewCourseQuestionListener::class,
				NewCourseQuestionReplyListener::class,
			)
		)
	);

	$listeners = array_filter(
		$listeners,
		function( $listener ) {
			return class_exists( $listener ) && is_subclass_of( $listener, Listener::class );
		}
	);

	$listeners = array_map(
		function( $listener ) {
			return new $listener();
		},
		$listeners
	);

	$listeners = array_filter(
		$listeners,
		function( $listener ) {
			return ! empty( trim( $listener->get_name() ) );
		}
	);

	$names = array_map(
		function( $listener ) {
			return $listener->get_name();
		},
		$listeners
	);

	return array_combine( $names, $listeners );
}

/**
 * Generate signature.
 *
 * @since 1.6.9
 *
 * @param array $webhook
 * @param array $payload
 * @return string
 */
function masteriyo_webhook_generate_signature( $webhook, $payload ) {
	/**
	 * Filters hash algorithm for generating webhook signature.
	 *
	 * @since 1.6.9
	 *
	 * @param string $hash_algo
	 * @param mixed $payload
	 * @param array $webhook
	 */
	$hash_algo = apply_filters( 'masteriyo_webhook_hash_algorithm', 'sha256', $payload, $webhook );

	$secret = $webhook['secret'] ?? '';

	return base64_encode( hash_hmac( $hash_algo, trim( wp_json_encode( $payload ) ), wp_specialchars_decode( $secret, ENT_QUOTES ), true ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
}

/**
 * Generate webhook delivery ID.
 *
 * @since 1.6.9
 *
 * @return string
 */
function masteriyo_webhook_generate_delivery_id( $webhook ) {
	$webhook_id = $webhook['id'] ?? 0;
	$salt       = wp_salt( 'auth' );

	return hash_hmac( 'sha256', $webhook_id . strtotime( 'now' ), $salt );
}

/**
 * Return webhook headers.
 *
 * @since 1.6.9
 *
 * @param string $event_name Event name.
 * @param array $webhook
 * @param array $payload
 * @return array
 */
function masteriyo_webhook_headers( $event_name, $webhook, $payload ) {
	$webhook_id = $webhook['id'] ?? null;

	$headers = array(
		'Content-Type'                    => 'application/json',
		'X-MASTERIYO-Webhook-Source'      => home_url( '/' ),
		'X-MASTERIYO-Webhook-Signature'   => masteriyo_webhook_generate_signature( $webhook, $payload ),
		'X-MASTERIYO-Webhook-ID'          => $webhook_id,
		'X-MASTERIYO-Webhook-Delivery-ID' => masteriyo_webhook_generate_delivery_id( $webhook ),
		'X-MASTERIYO-Webhook-EVENT'       => $event_name,
	);

	/**
	 * Filters webhook headers.
	 *
	 * @since 1.6.9
	 *
	 * @param array $headers Webhook HTTP headers.
	 * @param array $webhook Webhook Data.
	 * @param array $payload Webhook payload.
	 * @param string $event_name Event name.
	 */
	return apply_filters( 'masteriyo_webhook_headers', $headers, $webhook, $payload, $event_name );
}

/**
 * Send webhook payload.
 *
 * @since 1.6.9
 *
 * @param string $event_name Event name.
 * @param array $webhook Webhook.
 * @param mixed $payload Payload data to be sent.
 */
function masteriyo_send_webhook( $event_name, $webhook, $payload ) {
	$http_args = array(
		'method'      => 'POST',
		'timeout'     => MINUTE_IN_SECONDS,
		'redirection' => 0,
		'httpversion' => '1.0',
		'blocking'    => true,
		'user-agent'  => sprintf( 'Masteriyo/%s Hookshot (WordPress/%s)', MASTERIYO_VERSION, $GLOBALS['wp_version'] ),
		'body'        => trim( wp_json_encode( $payload ) ),
		'headers'     => masteriyo_webhook_headers( $event_name, $webhook, $payload ),
		'cookies'     => array(),
	);

	/**
	 * Filters HTTP arguments for a webhook.
	 *
	 * @since 1.6.9
	 *
	 * @param array $http_args
	 * @param mixed $payload
	 * @param \Masteriyo\Models\Webhook $webhook
	 */
	$http_args = apply_filters( 'masteriyo_webhook_http_args', $http_args, $payload, $webhook );

	$delivery_url = trim( $webhook['delivery_url'] ?? '' );

	if ( empty( $delivery_url ) ) {
		throw new Exception( __( 'Delivery URL not specified', 'masteriyo' ) );
	}

	if ( ! wp_http_validate_url( $delivery_url ) ) {
		throw new Exception( __( 'Invalid Delivery URL', 'masteriyo' ) );
	}

	$response = wp_safe_remote_request( $delivery_url, $http_args );

	/**
	 * Fires after webhook is delivered.
	 *
	 * @since 1.6.9
	 *
	 * @param array $http_args
	 * @param mixed $payload
	 * @param array $webhook
	 * @param array|\WP_Error $response
	 */
	do_action( 'masteriyo_after_webhook_delivery', $http_args, $payload, $webhook, $response );

	// Throw error if the delivery fails.
	if ( is_wp_error( $response ) ) {
		throw new Exception( $response->get_error_message(), $response->get_error_code() );
	}
}
