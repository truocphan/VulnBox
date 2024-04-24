<?php
/**
 * Webhook Repository.
 *
 * @since 1.6.9
 *
 * @package Masteriyo\Repository;
 */

namespace Masteriyo\Repository;

use Masteriyo\Database\Model;
use Masteriyo\Enums\WebhookStatus;
use Masteriyo\PostType\PostType;

/**
 * Webhook repository class.
 *
 * @since 1.6.9
 */
class WebhookRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 1.6.9
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'delivery_url' => '_delivery_url',
		'secret'       => '_secret',
		'events'       => '_events',
	);

	/**
	 * Create a webhook in the database.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\Webhook $webhook Webhook object.
	 */
	public function create( Model &$webhook ) {
		if ( ! $webhook->get_created_at( 'edit' ) ) {
			$webhook->set_created_at( time() );
		}

		if ( empty( $webhook->get_author_id() ) ) {
			$webhook->set_author_id( get_current_user_id() );
		}

		$id = wp_insert_post(
			/**
			 * Filters new webhook data before creating.
			 *
			 * @since 1.6.9
			 *
			 * @param array $data New webhook data.
			 * @param \Masteriyo\Models\Webhook $webhook Webhook object.
			 */
			apply_filters(
				'masteriyo_new_webhook_data',
				array(
					'post_type'      => PostType::WEBHOOK,
					'post_status'    => $webhook->get_status() ? $webhook->get_status() : WebhookStatus::INACTIVE,
					'post_author'    => $webhook->get_author_id( 'edit' ),
					'post_title'     => $webhook->get_name(),
					'post_content'   => $webhook->get_description(),
					'comment_status' => 'closed',
					'post_date'      => gmdate( 'Y-m-d H:i:s', $webhook->get_created_at( 'edit' )->getOffsetTimestamp() ),
					'post_date_gmt'  => gmdate( 'Y-m-d H:i:s', $webhook->get_created_at( 'edit' )->getTimestamp() ),
				),
				$webhook
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$webhook->set_id( $id );

			$this->update_post_meta( $webhook, true );
			// TODO Invalidate caches.

			$webhook->save_meta_data();
			$webhook->apply_changes();

			/**
			 * Fires after creating a webhook.
			 *
			 * @since 1.6.9
			 *
			 * @param integer $id The webhook ID.
			 * @param \Masteriyo\Models\Webhook $object The webhook object.
			 */
			do_action( 'masteriyo_new_webhook', $id, $webhook );
		}
	}

	/**
	 * Read a webhook.
	 *
	 * @since 1.6.9
	 *
	 * @throws Exception If invalid webhook.
	 *
	 * @param \Masteriyo\Models\Webhook $webhook Webhook object.
	 */
	public function read( Model &$webhook ) {
		$webhook_post = get_post( $webhook->get_id() );

		if ( ! $webhook->get_id() || ! $webhook_post || PostType::WEBHOOK !== $webhook_post->post_type ) {
			throw new \Exception( __( 'Invalid webhook.', 'masteriyo' ) );
		}

		$webhook->set_props(
			array(
				'name'        => $webhook_post->post_title,
				'created_at'  => $this->string_to_timestamp( $webhook_post->post_date_gmt ),
				'modified_at' => $this->string_to_timestamp( $webhook_post->post_modified_gmt ),
				'description' => $webhook_post->post_content,
				'author_id'   => $webhook_post->post_author,
				'status'      => $webhook_post->post_status,
			)
		);

		$this->read_webhook_data( $webhook );
		$this->read_extra_data( $webhook );
		$webhook->set_object_read( true );

		/**
		 * Fires after reading a webhook from database.
		 *
		 * @since 1.6.9
		 *
		 * @param integer $id The webhook ID.
		 * @param \Masteriyo\Models\Webhook $object The new webhook object.
		 */
		do_action( 'masteriyo_webhook_read', $webhook->get_id(), $webhook );
	}

	/**
	 * Update a webhook in the database.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\Webhook $webhook Webhook object.
	 */
	public function update( Model &$webhook ) {
		$changes = $webhook->get_changes();

		$post_data_keys = array(
			'description',
			'name',
			'status',
			'created_at',
			'modified_at',
			'author_id',
		);

		// Only update the post when the post data changes.
		if ( array_intersect( $post_data_keys, array_keys( $changes ) ) ) {
			$post_data = array(
				'post_content' => $webhook->get_description( 'edit' ),
				'post_title'   => $webhook->get_name( 'edit' ),
				'post_status'  => $webhook->get_status( 'edit' ) ? $webhook->get_status( 'edit' ) : WebhookStatus::INACTIVE,
				'post_author'  => $webhook->get_author_id( 'edit' ),
				'post_type'    => PostType::WEBHOOK,
			);

			/**
			 * When updating this object, to prevent infinite loops, use $wpdb
			 * to update data, since wp_update_post spawns more calls to the
			 * save_post action.
			 *
			 * This ensures hooks are fired by either WP itself (admin screen save),
			 * or an update purely from CRUD.
			 */
			if ( doing_action( 'save_post' ) ) {
				// TODO Abstract the $wpdb WordPress class.
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $post_data, array( 'ID' => $webhook->get_id() ) );
				clean_post_cache( $webhook->get_id() );
			} else {
				wp_update_post( array_merge( array( 'ID' => $webhook->get_id() ), $post_data ) );
			}

			$webhook->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.
		} else { // Only update post modified time to record this save event.
			$GLOBALS['wpdb']->update(
				$GLOBALS['wpdb']->posts,
				array(
					'post_modified'     => current_time( 'mysql' ),
					'post_modified_gmt' => current_time( 'mysql', true ),
				),
				array(
					'ID' => $webhook->get_id(),
				)
			);
			clean_post_cache( $webhook->get_id() );
		}

		$this->update_post_meta( $webhook );

		$webhook->apply_changes();

		/**
		 * Fires after updating a webhook in database.
		 *
		 * @since 1.6.9
		 *
		 * @param integer $id The webhook ID.
		 * @param \Masteriyo\Models\Webhook $object The new webhook object.
		 */
		do_action( 'masteriyo_update_webhook', $webhook->get_id(), $webhook );
	}

	/**
	 * Delete a webhook from the database.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\Webhook $webhook Webhook object.
	 * @param array $args   Array of args to pass.alert-danger
	 */
	public function delete( Model &$webhook, $args = array() ) {
		$id          = $webhook->get_id();
		$object_type = $webhook->get_object_type();
		$args        = array_merge(
			array(
				'force_delete' => false,
			),
			$args
		);

		if ( ! $id ) {
			return;
		}

		if ( $args['force_delete'] ) {
			/**
			 * Fires before deleting a webhook from database.
			 *
			 * @since 1.6.9
			 *
			 * @param integer $id The webhook ID.
			 * @param \Masteriyo\Models\Webhook $webhook The deleted webhook object.
			 */
			do_action( 'masteriyo_before_delete_' . $object_type, $id, $webhook );

			wp_delete_post( $id, true );
			$webhook->set_id( 0 );

			/**
			 * Fires after deleting a webhook from database.
			 *
			 * @since 1.6.9
			 *
			 * @param integer $id The webhook ID.
			 * @param \Masteriyo\Models\Webhook $webhook The deleted webhook object.
			 */
			do_action( 'masteriyo_after_delete_' . $object_type, $id, $webhook );
		} else {
			/**
			 * Fires before moving a webhook to trash in database.
			 *
			 * @since 1.6.9
			 *
			 * @param integer $id The webhook ID.
			 * @param \Masteriyo\Models\Webhook $webhook The trashed webhook object.
			 */
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $webhook );

			wp_trash_post( $id );
			$webhook->set_status( 'trash' );

			/**
			 * Fires after moving a webhook to trash in database.
			 *
			 * @since 1.6.9
			 *
			 * @param integer $id The webhook ID.
			 * @param \Masteriyo\Models\Webhook $webhook The trashed webhook object.
			 */
			do_action( 'masteriyo_after_trash_' . $object_type, $id, $webhook );
		}
	}

	/**
	 * Read webhook data. Can be overridden by child classes to load other props.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\Webhook $webhook webhook object.
	 */
	protected function read_webhook_data( &$webhook ) {
		$meta_values = $this->read_meta( $webhook );
		$set_props   = array();

		$meta_values = array_reduce(
			$meta_values,
			function( $result, $meta_value ) {
				$result[ $meta_value->key ][] = $meta_value->value;
				return $result;
			},
			array()
		);

		foreach ( $this->internal_meta_keys as $prop => $meta_key ) {
			$meta_value         = isset( $meta_values[ $meta_key ][0] ) ? $meta_values[ $meta_key ][0] : null;
			$set_props[ $prop ] = maybe_unserialize( $meta_value ); // get_post_meta only unserialize single values.
		}

		$webhook->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the webhook, like button text or webhook URL for external webhooks.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\Webhook $webhook webhook object.
	 */
	protected function read_extra_data( &$webhook ) {
		$meta_values = $this->read_meta( $webhook );

		foreach ( $webhook->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $webhook, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$webhook->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}

	/**
	 * Fetch webhooks.
	 *
	 * @since 1.6.9
	 *
	 * @param array $query_vars Query vars.
	 *
	 * @return mixed
	 */
	public function query( $query_vars ) {
		$args = $this->get_wp_query_args( $query_vars );

		if ( ! empty( $args['errors'] ) ) {
			$query = (object) array(
				'posts'         => array(),
				'found_posts'   => 0,
				'max_num_pages' => 0,
			);
		} else {
			$query = new \WP_Query( $args );
		}

		if ( isset( $query_vars['return'] ) && 'objects' === $query_vars['return'] && ! empty( $query->posts ) ) {
			// Prime caches before grabbing objects.
			update_post_caches( $query->posts, array( PostType::WEBHOOK ) );
		}

		$webhooks = ( isset( $query_vars['return'] ) && 'ids' === $query_vars['return'] ) ? $query->posts : array_filter( array_map( 'masteriyo_get_webhook', $query->posts ) );

		if ( isset( $query_vars['paginate'] ) && $query_vars['paginate'] ) {
			return (object) array(
				'webhooks'      => $webhooks,
				'total'         => $query->found_posts,
				'max_num_pages' => $query->max_num_pages,
			);
		}

		return $webhooks;
	}

	/**
	 * Get valid WP_Query args from a WebhookQuery's query variables.
	 *
	 * @since 1.6.9
	 * @param array $query_vars Query vars from a WebhookQuery.
	 * @return array
	 */
	protected function get_wp_query_args( $query_vars ) {
		// These queries cannot be auto-generated so we have to remove them and build them manually.
		$manual_queries = array();

		foreach ( $manual_queries as $key => $manual_query ) {
			if ( isset( $query_vars[ $key ] ) ) {
				$manual_queries[ $key ] = $query_vars[ $key ];
				unset( $query_vars[ $key ] );
			}
		}

		$wp_query_args = parent::get_wp_query_args( $query_vars );

		if ( ! isset( $wp_query_args['date_query'] ) ) {
			$wp_query_args['date_query'] = array();
		}
		if ( ! isset( $wp_query_args['meta_query'] ) ) {
			$wp_query_args['meta_query'] = array(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		}

		// Handle date queries.
		$date_queries = array(
			'created_at'  => 'post_date',
			'modified_at' => 'post_modified',
		);
		foreach ( $date_queries as $query_var_key => $db_key ) {
			if ( isset( $query_vars[ $query_var_key ] ) && '' !== $query_vars[ $query_var_key ] ) {

				// Remove any existing meta queries for the same keys to prevent conflicts.
				$existing_queries = wp_list_pluck( $wp_query_args['meta_query'], 'key', true );
				foreach ( $existing_queries as $query_index => $query_contents ) {
					unset( $wp_query_args['meta_query'][ $query_index ] );
				}

				$wp_query_args = $this->parse_date_for_wp_query( $query_vars[ $query_var_key ], $db_key, $wp_query_args );
			}
		}

		if ( isset( $wp_query_vars['meta_query'] ) ) {
			$wp_query_args['meta_query'][] = array( 'relation' => 'AND' );
		}

		// Handle paginate.
		if ( ! isset( $query_vars['paginate'] ) || ! $query_vars['paginate'] ) {
			$wp_query_args['no_found_rows'] = true;
		}

		// Handle orderby.
		if ( isset( $query_vars['orderby'] ) && 'include' === $query_vars['orderby'] ) {
			$wp_query_args['orderby'] = 'post__in';
		}

		/**
		 * Filters WP Query args for webhook post type query.
		 *
		 * @since 1.6.9
		 *
		 * @param array $wp_query_args WP Query args.
		 * @param array $query_vars Query vars.
		 * @param \Masteriyo\Repository\WebhookRepository $repository Webhook repository object.
		 */
		return apply_filters( 'masteriyo_webhook_data_store_cpt_get_webhooks_query', $wp_query_args, $query_vars, $this );
	}
}
