<?php
/**
 *
 */

namespace Masteriyo\Addons\RevenueSharing\Repository;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Repository\AbstractRepository;
use Masteriyo\Enums\OrderStatus;
use Masteriyo\PostType\PostType;

class EarningRepository extends AbstractRepository {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 1.6.14
	 *
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'user_id'               => '_user_id',
		'course_id'             => '_course_id',
		'order_id'              => '_order_id',
		'order_status'          => '_order_status',
		'total_amount'          => '_total_amount',
		'grand_total_amount'    => '_grand_total_amount',
		'commission_type'       => '_commission_type',
		'instructor_rate'       => '_instructor_rate',
		'instructor_amount'     => '_instructor_amount',
		'admin_rate'            => '_admin_rate',
		'admin_amount'          => '_admin_amount',
		'deductible_fee_type'   => '_deductible_fee_type',
		'deductible_fee_name'   => '_deductible_fee_name',
		'deductible_fee_amount' => '_deductible_fee_amount',
	);

	/**
	 * Create earning.
	 *
	 * @since 1.6.14
	 * @param \Masteriyo\Addons\RevenueSharing\Models\Earning $earning
	 */
	public function create( &$earning ) {
		if ( ! $earning->get_date_created() ) {
			$earning->set_date_created( time() );
		}

		$course = masteriyo_get_course( $earning->get_course_id() );

		$id = wp_insert_post(
			apply_filters(
				'masteriyo_new_earning_data',
				array(
					'post_type'     => PostType::EARNING,
					'post_status'   => $earning->get_status( 'edit' ) ? $earning->get_status( 'edit' ) : OrderStatus::PENDING,
					'post_title'    => $this->get_earning_title(),
					'post_author'   => $course ? $course->get_author_id() : 1,
					'ping_status'   => 'closed',
					'post_date'     => gmdate( 'Y-m-d H:i:s', $earning->get_date_created( 'edit' )->getOffsetTimestamp() ),
					'post_date_gmt' => gmdate( 'Y-m-d H:i:s', $earning->get_date_created( 'edit' )->getOffsetTimestamp() ),
				)
			)
		);

		if ( ! is_wp_error( $id ) ) {
			$earning->set_id( $id );
			$this->update_post_meta( $earning, true );

			$earning->save_meta_data();
			$earning->apply_changes();

			/**
			 * Fires after creating a earning.
			 *
			 * @since 1.6.14
			 *
			 * @param \Masteriyo\Addons\RevenueSharing\Models\Earning $object The earning object.
			 * @param integer $id The earning ID.
			 */
			do_action( 'masteriyo_new_earning', $earning, $id );
		}
	}

	/**
	 * Read earning.
	 *
	 * @since 1.6.14
	 * @param \Masteriyo\Addons\RevenueSharing\Models\Earning $earning
	 * @throws \Exception If invalid earning item.
	 */
	public function read( &$earning ) {
		$earning_obj = get_post( $earning->get_id() );

		if ( ! $earning->get_id() || ! $earning_obj || $earning->get_post_type() !== $earning_obj->post_type ) {
			throw new \Exception( __( 'Invalid Wishlist Item.', 'masteriyo' ) );
		}

		$earning->set_props(
			array(
				'date_created' => $earning_obj->post_date_gmt,
				'status'       => $earning_obj->post_status,
			)
		);

		$this->read_metadata( $earning );
		$earning->set_object_read( true );
	}

	/**
	 * Update a earning in the database.
	 *
	 * @since 1.6.14
	 *
	 * @param \Masteriyo\Addons\RevenueSharing\Models\Earning $earning
	 */
	public function update( &$earning ) {
		$changes = $earning->get_changes();

		$post_data_keys = array(
			'status',
			'date_modified',
			'user_id',
		);

		if ( array_intersect( $post_data_keys, array_keys( $changes ) ) ) {
			$post_data = array(
				'post_author' => $earning->get_user_id( 'edit' ),
				'post_type'   => PostType::EARNING,
				'post_status' => $earning->get_status( 'edit' ),
			);

			if ( doing_action( 'save_post' ) ) {
				// TODO Abstract the $wpdb WordPress class.
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $post_data, array( 'ID' => $earning->get_id() ) );
				clean_post_cache( $earning->get_id() );
			} else {
				wp_update_post( array_merge( array( 'ID' => $earning->get_id() ), $post_data ) );
			}

			$earning->read_meta_data( true );
		} else { // Only update post modified time to record this save event.
			$GLOBALS['wpdb']->update(
				$GLOBALS['wpdb']->posts,
				array(
					'post_modified'     => current_time( 'mysql' ),
					'post_modified_gmt' => current_time( 'mysql', true ),
				),
				array(
					'ID' => $earning->get_id(),
				)
			);
			clean_post_cache( $earning->get_id() );
		}

		$this->update_post_meta( $earning );

		$earning->apply_changes();

		/**
		 * Fires after updating a earning in database.
		 *
		 * @since 1.6.14
		 *
		 * @param integer $id The course ID.
		 * @param \Masteriyo\Addons\RevenueSharing\Models\earning $object The new course object.
		 */
		do_action( 'masteriyo_update_earning', $earning->get_id(), $earning );
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
	 * Fetch earnings.
	 *
	 * @since 1.6.14
	 *
	 * @param array $query_vars Query vars.
	 *
	 * @return object|\Masteriyo\Addons\RevenueSharing\Models\Earning[]
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
			$earnings = $query->posts;
		} else {
			$earnings = array_filter( array_map( 'masteriyo_get_earning', $query->posts ) );
		}

		if ( isset( $query_vars['paginate'] ) && $query_vars['paginate'] ) {
			$earnings = (object) array(
				'zooms'         => $earnings,
				'total'         => $query->found_posts,
				'max_num_pages' => $query->max_num_pages,
			);
		}

		return $earnings;
	}

	/**
	 * Get wp query args.
	 *
	 * @since 1.6.14
	 *
	 * @param array $query_vars Query vars.
	 *
	 * @return array
	 */
	public function get_wp_query_args( $query_vars ) {
		$wp_query_args              = parent::get_wp_query_args( $query_vars );
		$wp_query_args['post_type'] = PostType::EARNING;

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

		if ( isset( $wp_query_vars['meta_query'] ) ) {
			$wp_query_args['meta_query'][] = array( 'relation' => 'AND' );
		}

		if ( ! isset( $query_vars['paginate'] ) || ! $query_vars['paginate'] ) {
			$wp_query_args['no_found_rows'] = true;
		}

		/**
		 * Filters WP Query args for earning post type query.
		 *
		 * @since 1.6.14
		 *
		 * @param array $wp_query_args WP Query args.
		 * @param array $query_vars Query vars.
		 * @param \Masteriyo\Addons\RevenueSharing\Repository\EarningRepository $this Earning repository object.
		 */
		return apply_filters( 'masteriyo_data_store_cpt_get_earnings_query', $wp_query_args, $query_vars, $this );
	}

	/**
	 * Get a title for the earning.
	 *
	 * @since 1.6.14
	 *
	 * @return string
	 */
	protected function get_earning_title() {
		// phpcs:enable
		/* translators: %s: Earning date */
		return sprintf( __( 'Earning &ndash; %s', 'masteriyo' ), strftime( _x( '%1$b %2$d, %Y @ %I:%M %p', 'Earning date parsed by strftime', 'masteriyo' ) ) );
		// phpcs:disable
	}
}
