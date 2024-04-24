<?php
/**
 * Withdraw repository.
 *
 * @since 1.6.14
 *
 * @package Masteriyo\Addons\RevenueSharing\Repository
 */

namespace Masteriyo\Addons\RevenueSharing\Repository;

defined( 'ABSPATH' ) || exit;

use Masteriyo\PostType\PostType;
use Masteriyo\Addons\RevenueSharing\Enums\WithdrawStatus;
use Masteriyo\Repository\AbstractRepository;

/**
 * Withdraw repository class.
 *
 * @since 1.6.14
 */
class WithdrawRepository extends AbstractRepository {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 1.6.14
	 *
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'withdraw_amount'  => '_withdraw_amount',
		'withdraw_method'  => '_withdraw_method',
		'rejection_detail' => '_rejection_detail',
	);

	/**
	 * Create withdraw.
	 *
	 * @since 1.6.14
	 * @param \Masteriyo\Addons\RevenueSharing\Models\Withdraw $withdraw
	 */
	public function create( &$withdraw ) {
		if ( ! $withdraw->get_date_created() ) {
			$withdraw->set_date_created( time() );
		}

		if ( empty( $withdraw->get_user_id( 'edit' ) ) ) {
			$withdraw->set_user_id( get_current_user_id() );
		}

		if ( ! $withdraw->get_withdraw_method( 'edit' ) ) {
			$user_id         = $withdraw->get_user_id( 'edit' );
			$withdraw_method = get_user_meta( $user_id, '_withdraw', true );

			$withdraw->set_withdraw_method( $withdraw_method );
		}

		$id = wp_insert_post(
			/**
			 * Filters new withdraw data before creating.
			 *
			 * @since 1.6.14
			 *
			 * @param array $data New withdraw data.
			 * @param Masteriyo\Addons\RevenueSharing\Models\Withdraw $withdraw Withdraw object.
			 */
			apply_filters(
				'masteriyo_new_withdraw_data',
				array(
					'post_type'     => PostType::WITHDRAW,
					'post_status'   => $withdraw->get_status( 'edit' ) ? $withdraw->get_status( 'edit' ) : WithdrawStatus::PENDING,
					'post_title'    => $this->get_withdraw_title(),
					'post_author'   => $withdraw->get_user_id( 'edit' ),
					'ping_status'   => 'closed',
					'post_date'     => gmdate( 'Y-m-d H:i:s', $withdraw->get_date_created( 'edit' )->getOffsetTimestamp() ),
					'post_date_gmt' => gmdate( 'Y-m-d H:i:s', $withdraw->get_date_created( 'edit' )->getOffsetTimestamp() ),
				)
			)
		);

		if ( ! is_wp_error( $id ) ) {
			$withdraw->set_id( $id );

			$this->update_post_meta( $withdraw, true );
			$withdraw->save_meta_data();
			$withdraw->apply_changes();

			/**
			 * Fires after creating a withdraw.
			 *
			 * @since 1.6.14
			 *
			 * @param \Masteriyo\Addons\RevenueSharing\Models\Withdraw $object The withdraw object.
			 * @param integer $id The withdraw ID.
			 */
			do_action( 'masteriyo_new_withdraw', $withdraw, $id );
		}
	}

	/**
	 * Read withdraw.
	 *
	 * @since 1.6.14
	 * @param \Masteriyo\Addons\RevenueSharing\Models\Withdraw $withdraw
	 * @throws \Exception If invalid withdraw.
	 */
	public function read( &$withdraw ) {
		$withdraw_post = get_post( $withdraw->get_id() );

		if ( ! $withdraw->get_id() || ! $withdraw_post || $withdraw->get_post_type() !== $withdraw_post->post_type ) {
			throw new \Exception( __( 'Invalid withdraw.', 'masteriyo' ) );
		}

		$withdraw->set_props(
			array(
				'date_created'  => $this->string_to_timestamp( $withdraw_post->post_date_gmt ) ? $this->string_to_timestamp( $withdraw_post->post_date_gmt ) : $this->string_to_timestamp( $withdraw_post->post_date ),
				'date_modified' => $this->string_to_timestamp( $withdraw_post->post_modified_gmt ) ? $this->string_to_timestamp( $withdraw_post->post_modified_gmt ) : $this->string_to_timestamp( $withdraw_post->post_modified ),
				'status'        => $withdraw_post->post_status,
				'user_id'       => $withdraw_post->post_author,
			)
		);

		$this->read_metadata( $withdraw );
		$withdraw->set_object_read( true );

		/**
		 * Fires after withdraw is read from database.
		 *
		 * @since 1.6.14
		 *
		 * @param int $id Withdraw ID.
		 * @param \Masteriyo\Addons\RevenueSharing\Models\Withdraw $withdraw Withdraw object.
		 */
		do_action( 'masteriyo_withdraw_read', $withdraw->get_id(), $withdraw );
	}

	/**
	 * Update a withdraw in the database.
	 *
	 * @since 1.6.14
	 *
	 * @param \Masteriyo\Addons\RevenueSharing\Models\Withdraw $withdraw
	 */
	public function update( &$withdraw ) {
		$changes = $withdraw->get_changes();

		$post_data_keys = array(
			'status',
			'date_modified',
		);

		if ( array_intersect( $post_data_keys, array_keys( $changes ) ) ) {
			$post_data = array(
				'post_status' => $withdraw->get_status( 'edit' ),
				'post_type'   => PostType::WITHDRAW,
			);

			if ( doing_action( 'save_post' ) ) {
				// TODO Abstract the $wpdb WordPress class.
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $post_data, array( 'ID' => $withdraw->get_id() ) );
				clean_post_cache( $withdraw->get_id() );
			} else {
				wp_update_post( array_merge( array( 'ID' => $withdraw->get_id() ), $post_data ) );
			}

			$withdraw->read_meta_data( true );
		} else { // Only update post modified time to record this save event.
			$GLOBALS['wpdb']->update(
				$GLOBALS['wpdb']->posts,
				array(
					'post_modified'     => current_time( 'mysql' ),
					'post_modified_gmt' => current_time( 'mysql', true ),
				),
				array(
					'ID' => $withdraw->get_id(),
				)
			);
			clean_post_cache( $withdraw->get_id() );
		}

		$this->update_post_meta( $withdraw );

		if ( isset( $changes['status'] ) ) {
			$from = $withdraw->get_status();
			$to   = $changes['status'];

			/**
			 * Fires after withdraw status is changed.
			 *
			 * @since 1.6.14
			 *
			 * @param integer $id The withdraw ID.
			 * @param \Masteriyo\Addons\RevenueSharing\Models\Withdraw $object The new course object.
			 */
			do_action( 'masteriyo_withdraw_status_' . $to, $withdraw->get_id(), $withdraw );

			/**
			 * Fires after withdraw status is changed.
			 *
			 * @since 1.6.14
			 *
			 * @param integer $id The withdraw ID.
			 * @param \Masteriyo\Addons\RevenueSharing\Models\Withdraw $object The new course object.
			 */
			do_action( 'masteriyo_withdraw_status_changed', $withdraw->get_id(), $from, $to, $withdraw );
		}

		$withdraw->apply_changes();

		/**
		 * Fires after updating a withdraw in database.
		 *
		 * @since 1.6.14
		 *
		 * @param integer $id The withdraw ID.
		 * @param \Masteriyo\Addons\RevenueSharing\Models\Withdraw $object The new course object.
		 */
		do_action( 'masteriyo_update_withdraw', $withdraw->get_id(), $withdraw );
	}

	/**
	 * Read metadata.
	 *
	 * @since 1.6.14
	 * @param \Masteriyo\Addons\RevenueSharing\Models\Earning $earning
	 */
	protected function read_metadata( &$earning ) {
		$meta_values = $this->read_meta( $earning );
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

		$earning->set_props( $set_props );
	}

	/**
	 * Fetch withdraws.
	 *
	 * @since 1.6.14
	 *
	 * @param array $query_vars Query vars.
	 * @return object|\Masteriyo\Addons\RevenueSharing\Models\Withdraw[]
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
			update_post_caches( $query->posts );
		}

		if ( isset( $query_vars['return'] ) && 'ids' === $query_vars['return'] ) {
			$withdraws = $query->posts;
		} else {
			$withdraws = array_filter( array_map( 'masteriyo_get_withdraw', $query->posts ) );
		}

		if ( isset( $query_vars['paginate'] ) && $query_vars['paginate'] ) {
			$withdraws = (object) array(
				'withdraws'     => $withdraws,
				'total'         => $query->found_posts,
				'max_num_pages' => $query->max_num_pages,
			);
		}

		return $withdraws;
	}

	public function get_wp_query_args( $query_vars ) {
		$wp_query_args              = parent::get_wp_query_args( $query_vars );
		$wp_query_args['post_type'] = 'mto-withdraw';

		if ( ! isset( $wp_query_args['date_query'] ) ) {
			$wp_query_args['date_query'] = array();
		}
		if ( ! isset( $wp_query_args['meta_query'] ) ) {
			$wp_query_args['meta_query'] = array();
		}

		$date_queries = array(
			'date_created'  => 'post_date',
			'date_modified' => 'post_modified',
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

		if ( ! isset( $query_vars['paginate'] ) || ! $query_vars['paginate'] ) {
			$wp_query_args['no_found_rows'] = true;
		}

		/**
		 * Filters WP Query args for withdraw post type query.
		 *
		 * @since 1.6.14
		 *
		 * @param array $wp_query_args WP Query args.
		 * @param array $query_vars Query vars.
		 * @param \Masteriyo\Addons\RevenueSharing\Repository\WithdrawRepository $this withdraw repository object.
		 */
		return apply_filters( 'masteriyo_data_store_cpt_get_withdraws_query', $wp_query_args, $query_vars, $this );
	}

	/**
	 * Read withdraw data.
	 *
	 * @since 1.6.14
	 *
	 * @param \Masteriyo\Addons\RevenueSharing\Models\Withdraw $withdraw
	 */
	protected function read_withdraw_data( &$withdraw ) {
		$meta_values = $this->read_meta( $withdraw );
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
			$set_props[ $prop ] = maybe_unserialize( $meta_value ); // get_post_meta only un-serializes single values.
		}

		$withdraw->set_props( $set_props );
	}

	/**
	 * Get a title for the withdraw.
	 *
	 * @since 1.6.14
	 *
	 * @return string
	 */
	protected function get_withdraw_title() {
		// phpcs:enable
		/* translators: %s: Withdraw date */
		return sprintf( __( 'Withdraw &ndash; %s', 'masteriyo' ), strftime( _x( '%1$b %2$d, %Y @ %I:%M %p', 'Withdraw date parsed by strftime', 'masteriyo' ) ) );
		// phpcs:disable
	}
}
