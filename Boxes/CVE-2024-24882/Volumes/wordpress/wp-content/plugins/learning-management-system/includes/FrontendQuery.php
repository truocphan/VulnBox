<?php
/**
 * Contains the query functions for Masteriyo which alter the front-end post queries and loops
 *
 * @version 1.0.0
 * @package Masteriyo\Classes
 */

namespace Masteriyo;

use Masteriyo\Notice;

defined( 'ABSPATH' ) || exit;

/**
 * Query Class.
 */
class FrontendQuery {

	/**
	 * Query vars to add to wp.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $query_vars = array();

	/**
	 * Reference to the main course query on the page.
	 *
	 * @since 1.0.0
	 *
	 * @var WP_Query
	 */
	private $course_query;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {
		$this->init_hooks();
		$this->init_query_vars();
	}

	/**
	 * Get query vars.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_query_vars() {
		/**
		 * Filters the query vars.
		 *
		 * @since 1.0.0
		 *
		 * @param array $query_vars The query vars.
		 */
		return apply_filters( 'masteriyo_get_query_vars', $this->query_vars );
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'add_endpoints' ) );

		if ( ! is_admin() ) {
			add_action( 'wp_loaded', array( $this, 'get_errors' ), 20 );
			add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );
			add_action( 'parse_request', array( $this, 'parse_request' ), 0 );
			add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ), PHP_INT_MAX - 10 );
			add_filter( 'get_pagenum_link', array( $this, 'remove_add_to_cart_pagination' ), 10, 1 );
		}

		$this->init_query_vars();
	}

	/**
	 * Get any errors from querystring.
	 *
	 * @since 1.0.0
	 */
	public function get_errors() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$error = ! empty( $_GET['masteriyo_error'] ) ? sanitize_text_field( wp_unslash( $_GET['masteriyo_error'] ) ) : '';

		if ( $error && ! masteriyo_notice_exists( $error, 'error' ) ) {
			masteriyo_add_notice( $error, Notice::ERROR );
		}
	}

	/**
	 * Endpoint mask describing the places the endpoint should be added.
	 *
	 * @since 1.0.0
	 * @return int
	 */
	public function get_endpoints_mask() {
		if ( 'page' === get_option( 'show_on_front' ) ) {
			$page_on_front    = get_option( 'page_on_front' );
			$account_page_id  = masteriyo_get_setting( 'general.pages.account_page_id' );
			$checkout_page_id = masteriyo_get_setting( 'general.pages.checkout_page_id' );

			if ( in_array( $page_on_front, array( $account_page_id, $checkout_page_id, 7 ), true ) ) {
				return EP_ROOT | EP_PAGES;
			}
		}

		return EP_PAGES;
	}

	/**
	 * Add query vars.
	 *
	 * @since 1.0.0
	 *
	 * @param array $vars Query vars.
	 * @return array
	 */
	public function add_query_vars( $vars ) {
		foreach ( $this->get_query_vars() as $key => $var ) {
			$vars[] = $key;
		}

		return $vars;
	}

	/**
	 * Parse the request and look for query vars - endpoints may not be supported.
	 *
	 * @since 1.0.0
	 */
	public function parse_request() {
		global $wp;

		$query_vars = $this->get_query_vars();

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		// Map query vars to their keys, or get them if endpoints are not supported.
		foreach ( $this->get_query_vars() as $key => $var ) {
			if ( isset( $_GET[ $var ] ) ) {
				$wp->query_vars[ $key ] = sanitize_text_field( wp_unslash( $_GET[ $var ] ) );
			} elseif ( isset( $wp->query_vars[ $var ] ) ) {
				$wp->query_vars[ $key ] = $wp->query_vars[ $var ];
			}
		}
		// phpcs:enable WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Add endpoints for query vars.
	 *
	 * @since 1.0.0
	 */
	public function add_endpoints() {
		$mask = $this->get_endpoints_mask();

		foreach ( $this->get_query_vars() as $key => $var ) {
			if ( ! empty( $var ) ) {
				add_rewrite_endpoint( $var, $mask );
			}
		}
	}

	/**
	 * Init query vars by loading options.
	 *r
	 * @since 1.0.0
	 */
	public function init_query_vars() {
		// Query vars to add to WP.
		$this->query_vars = array_merge(
			array(
				// Checkout actions.
				'order-pay'       => masteriyo_get_setting( 'advance.checkout.pay' ),
				'order-received'  => masteriyo_get_setting( 'advance.checkout.order_received' ),

				// Account actions.
				'edit-address'    => masteriyo_get_setting( 'advance.account.edit_address' ),
				'payment-methods' => masteriyo_get_setting( 'advance.account.payment_methods' ),
			),
			masteriyo_get_account_endpoints()
		);
	}

	/**
	 * Get page title for an endpoint.
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint Endpoint key.
	 * @param string $action Optional action or variation within the endpoint.
	 *
	 * @return string The page title.
	 */
	public function get_endpoint_title( $endpoint, $action = '' ) {
		global $wp;

		switch ( $endpoint ) {
			case 'order-pay':
				$title = __( 'Pay for order', 'masteriyo' );
				break;
			case 'order-received':
				$title = __( 'Order received', 'masteriyo' );
				break;
			case 'orders':
				if ( ! empty( $wp->query_vars['orders'] ) ) {
					/* translators: %s: page */
					$title = sprintf( __( 'Orders (page %d)', 'masteriyo' ), intval( $wp->query_vars['orders'] ) );
				} else {
					$title = __( 'Orders', 'masteriyo' );
				}
				break;
			case 'view-order':
				$order = masteriyo_get_order( $wp->query_vars['view-order'] );
				/* translators: %s: order number */
				$title = ( $order ) ? sprintf( __( 'Order #%s', 'masteriyo' ), $order->get_order_number() ) : '';
				break;
			case 'downloads':
				$title = __( 'Downloads', 'masteriyo' );
				break;
			case 'edit-account':
				$title = __( 'Account details', 'masteriyo' );
				break;
			case 'edit-address':
				$title = __( 'Addresses', 'masteriyo' );
				break;
			case 'payment-methods':
				$title = __( 'Payment methods', 'masteriyo' );
				break;
			case 'add-payment-method':
				$title = __( 'Add payment method', 'masteriyo' );
				break;
			case 'lost-password':
				if ( in_array( $action, array( 'rp', 'resetpass', 'newaccount' ), true ) ) {
					$title = __( 'Set password', 'masteriyo' );
				} else {
					$title = __( 'Lost password', 'masteriyo' );
				}
				break;
			default:
				$title = '';
				break;
		}

		/**
		 * Filters the page title used for my-account endpoints.
		 *
		 * @since 1.0.0
		 *
		 * @param string $title Default title.
		 * @param string $endpoint Endpoint key.
		 * @param string $action Optional action or variation within the endpoint.
		 */
		return apply_filters( 'masteriyo_endpoint_' . $endpoint . '_title', $title, $endpoint, $action );
	}

	/**
	 * Hook into pre_get_posts to do the main course query.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Query $q Query instance.
	 */
	public function pre_get_posts( $q ) {
		// We only want to affect the main query.
		if ( ! $q->is_main_query() ) {
			return;
		}

		// Fixes for queries on homepages.
		if ( $this->is_showing_page_on_front( $q ) ) {

			// Fix for endpoints on the homepage.
			if ( ! $this->page_on_front_is( $q->get( 'page_id' ) ) ) {
				$_query = wp_parse_args( $q->query );
				if ( ! empty( $_query ) && array_intersect( array_keys( $_query ), array_keys( $this->get_query_vars() ) ) ) {
					$q->is_page     = true;
					$q->is_home     = false;
					$q->is_singular = true;
					$q->set( 'page_id', (int) get_option( 'page_on_front' ) );
					add_filter( 'redirect_canonical', '__return_false' );
				}
			}

			// When orderby is set, WordPress shows posts on the front-page. Get around that here.
			if ( $this->page_on_front_is( masteriyo_get_page_id( 'courses' ) ) ) {
				$_query = wp_parse_args( $q->query );
				if ( empty( $_query ) || ! array_diff( array_keys( $_query ), array( 'preview', 'page', 'paged', 'cpage', 'orderby' ) ) ) {
					$q->set( 'page_id', (int) get_option( 'page_on_front' ) );
					$q->is_page = true;
					$q->is_home = false;

					// WP supporting themes show post type archive.
					if ( current_theme_supports( 'masteriyo' ) ) {
						$q->set( 'post_type', 'mto-course' );
					} else {
						$q->is_singular = true;
					}
				}
			} elseif ( ! empty( $_GET['orderby'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$q->set( 'page_id', (int) get_option( 'page_on_front' ) );
				$q->is_page     = true;
				$q->is_home     = false;
				$q->is_singular = true;
			}
		}

		// Fix course feeds.
		if ( $q->is_feed() && $q->is_post_type_archive( 'mto-course' ) ) {
			$q->is_comment_feed = false;
		}

		// Special check for courses with the COURSE POST TYPE ARCHIVE on front.
		if ( current_theme_supports( 'masteriyo' ) && $q->is_page() && 'page' === get_option( 'show_on_front' ) && absint( $q->get( 'page_id' ) ) === masteriyo_get_page_id( 'courses' ) ) {
			// This is a front-page courses.
			$q->set( 'post_type', 'mto-course' );
			$q->set( 'page_id', '' );

			if ( isset( $q->query['paged'] ) ) {
				$q->set( 'paged', $q->query['paged'] );
			}

			// Define a variable so we know this is the front page courses later on.
			masteriyo_maybe_define_constant( 'COURSES_IS_ON_FRONT', true );

			// Get the actual WP page to avoid errors and let us use is_front_page().
			// This is hacky but works. Awaiting https://core.trac.wordpress.org/ticket/21096.
			global $wp_post_types;

			$courses_page = get_post( masteriyo_get_page_id( 'courses' ) );

			$wp_post_types['mto-course']->ID         = $courses_page->ID;
			$wp_post_types['mto-course']->post_title = $courses_page->post_title;
			$wp_post_types['mto-course']->post_name  = $courses_page->post_name;
			$wp_post_types['mto-course']->post_type  = $courses_page->post_type;
			$wp_post_types['mto-course']->ancestors  = get_ancestors( $courses_page->ID, $courses_page->post_type );

			// Fix conditional Functions like is_front_page.
			$q->is_singular          = false;
			$q->is_post_type_archive = true;
			$q->is_archive           = true;
			$q->is_page              = true;

			// Remove post type archive name from front page title tag.
			add_filter( 'post_type_archive_title', '__return_empty_string', 5 );

			// Fix WP SEO.
			if ( class_exists( 'WPSEO_Meta' ) ) {
				add_filter( 'wpseo_metadesc', array( $this, 'wpseo_metadesc' ) );
				add_filter( 'wpseo_metakey', array( $this, 'wpseo_metakey' ) );
			}
		} elseif ( ! $q->is_post_type_archive( 'mto-course' ) && ! $q->is_tax( get_object_taxonomies( 'mto-course' ) ) ) {
			// Only apply to course categories, the course post archive, the courses page, course tags, and course attribute taxonomies.
			return;
		}

		$this->course_query( $q );
	}

	/**
	 * Query the courses, applying sorting/ordering etc.
	 * This applies to the main WordPress loop.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Query $q Query instance.
	 */
	public function course_query( $q ) {
		if ( ! is_feed() ) {
			$ordering = $this->get_catalog_ordering_args();
			$q->set( 'orderby', $ordering['orderby'] );
			$q->set( 'order', $ordering['order'] );

			if ( isset( $ordering['meta_key'] ) ) {
				$q->set( 'meta_key', $ordering['meta_key'] );
			}
		}

		// Query vars that affect posts shown.
		$q->set( 'meta_query', $this->get_meta_query( $q->get( 'meta_query' ), true ) );
		$q->set( 'tax_query', $this->get_tax_query( $q->get( 'tax_query' ), true ) );
		$q->set( 'masteriyo_query', 'course_query' );

		/**
		 * Filters post__in value for courses loop.
		 *
		 * @since 1.0.0
		 *
		 * @param array $post__in Course IDs.
		 */
		$post__in = (array) apply_filters( 'loop_courses_post_in', array() );

		$q->set( 'post__in', array_unique( $post__in ) );

		/**
		 * Filters posts_per_page value for courses loop.
		 *
		 * @since 1.0.0
		 *
		 * @param array $posts_per_page Number of courses per page.
		 */
		$posts_per_page = apply_filters(
			'loop_courses_per_page',
			masteriyo_get_default_course_rows_per_page()
		);
		$posts_per_page = absint( $posts_per_page );

		// Work out how many courses to query.
		$q->set(
			'posts_per_page',
			$posts_per_page ? $posts_per_page : $q->get( 'posts_per_page' )
		);

		// Store reference to this query.
		$this->course_query = $q;

		// Additional hooks to change WP Query.
		// add_filter( 'posts_clauses', array( $this, 'price_filter_post_clauses' ), 10, 2 );
		add_filter( 'the_posts', array( $this, 'handle_get_posts' ), 10, 2 );

		/**
		 * Fires after setting up frontend query object.
		 *
		 * @since 1.0.0
		 *
		 * @param \WP_Query $query Query object.
		 */
		do_action( 'masteriyo_course_query', $q );
	}

	/**
	 * Handler for the 'the_posts' WP filter.
	 *
	 * @since 1.0.0
	 *
	 * @param array    $posts Posts from WP Query.
	 * @param WP_Query $query Current query.
	 *
	 * @return array
	 */
	public function handle_get_posts( $posts, $query ) {
		if ( 'course_query' !== $query->get( 'masteriyo_query' ) ) {
			return $posts;
		}
		$this->remove_course_query_filters( $posts );
		return $posts;
	}

	/**
	 * Pre_get_posts above may adjust the main query to add Masteriyo logic. When this query is done, we need to ensure
	 * all custom filters are removed.
	 *
	 * This is done here during the_posts filter. The input is not changed.
	 *
	 * @param array $posts Posts from WP Query.
	 * @return array
	 */
	public function remove_course_query_filters( $posts ) {
		return $posts;
	}

	/**
	 * Are we currently on the front page?
	 *
	 * @since 1.0.0
	 * @param WP_Query $q Query instance.
	 * @return bool
	 */
	private function is_showing_page_on_front( $q ) {
		return ( $q->is_home() && ! $q->is_posts_page ) && 'page' === get_option( 'show_on_front' );
	}

	/**
	 * Returns an array of arguments for ordering courses based on the selected values.
	 *
	 * @since 1.0.0
	 *
	 * @param string $orderby Order by param.
	 * @param string $order Order param.
	 * @return array
	 */
	public function get_catalog_ordering_args( $orderby = '', $order = '' ) {
		// Get ordering from query string unless defined.
		if ( ! $orderby ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$orderby_value = isset( $_GET['orderby'] ) ? masteriyo_clean( (string) wp_unslash( $_GET['orderby'] ) ) : masteriyo_clean( get_query_var( 'orderby' ) );

			if ( ! $orderby_value ) {
				if ( is_search() ) {
					$orderby_value = 'relevance';
				} else {
					/**
					 * Filters the order by value.
					 *
					 * @since 1.0.0
					 *
					 * @param string $order_by Property to order by.
					 */
					$orderby_value = apply_filters( 'masteriyo_default_catalog_orderby', get_option( 'masteriyo_default_catalog_orderby', 'date' ) );
				}
			}

			// Get order + orderby args from string.
			$orderby_value = is_array( $orderby_value ) ? $orderby_value : explode( '-', $orderby_value );
			$orderby       = esc_attr( $orderby_value[0] );
			$order         = ! empty( $orderby_value[1] ) ? $orderby_value[1] : $order;
		}

		// Convert to correct format.
		$orderby = strtolower( is_array( $orderby ) ? (string) current( $orderby ) : (string) $orderby );
		$order   = strtoupper( is_array( $order ) ? (string) current( $order ) : (string) $order );
		$args    = array(
			'orderby'  => $orderby,
			'order'    => ( 'DESC' === $order ) ? 'DESC' : 'ASC',
			'meta_key' => '', // @codingStandardsIgnoreLine
		);

		switch ( $orderby ) {
			case 'id':
				$args['orderby'] = 'ID';
				break;
			case 'menu_order':
				$args['orderby'] = 'menu_order title';
				break;
			case 'title':
				$args['orderby'] = 'title';
				$args['order']   = ( 'DESC' === $order ) ? 'DESC' : 'ASC';
				break;
			case 'relevance':
				$args['orderby'] = 'relevance';
				$args['order']   = 'DESC';
				break;
			case 'rand':
				$args['orderby'] = 'rand'; // @codingStandardsIgnoreLine
				break;
			case 'date':
				$args['orderby'] = 'date ID';
				$args['order']   = ( 'ASC' === $order ) ? 'ASC' : 'DESC';
				break;
			case 'price':
				$callback = 'DESC' === $order ? 'order_by_price_desc_post_clauses' : 'order_by_price_asc_post_clauses';
				add_filter( 'posts_clauses', array( $this, $callback ) );
				break;
			case 'popularity':
				add_filter( 'posts_clauses', array( $this, 'order_by_popularity_post_clauses' ) );
				break;
			case 'rating':
				add_filter( 'posts_clauses', array( $this, 'order_by_rating_post_clauses' ) );
				break;
		}

		/**
		 * Filters the ordering args for loop.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args The ordering arguments.
		 * @param string $orderby The property to order by.
		 * @param string $order The order direction.
		 */
		return apply_filters( 'masteriyo_get_catalog_ordering_args', $args, $orderby, $order );
	}

	/**
	 * Appends meta queries to an array.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $meta_query Meta query.
	 * @param  bool  $main_query If is main query.
	 * @return array
	 */
	public function get_meta_query( $meta_query = array(), $main_query = false ) {
		if ( ! is_array( $meta_query ) ) {
			$meta_query = array();
		}

		/**
		 * Filters the meta query args for course query.
		 *
		 * @since 1.0.0
		 *
		 * @param array $meta_query Meta query args.
		 */
		return array_filter( apply_filters( 'masteriyo_course_query_meta_query', $meta_query ) );
	}

	/**
	 * Appends tax queries to an array.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $tax_query  Tax query.
	 * @param  bool  $main_query If is main query.
	 * @return array
	 */
	public function get_tax_query( $tax_query = array(), $main_query = false ) {
		if ( ! is_array( $tax_query ) ) {
			$tax_query = array(
				'relation' => 'AND',
			);
		}

		$course_visibility_terms  = \masteriyo_get_course_visibility_term_ids();
		$course_visibility_not_in = array( is_search() && $main_query ? $course_visibility_terms['exclude-from-search'] : $course_visibility_terms['exclude-from-catalog'] );

		// Hide out of stock courses.
		if ( 'yes' === get_option( 'masteriyo_hide_out_of_stock_items' ) ) {
			$course_visibility_not_in[] = $course_visibility_terms['outofstock'];
		}

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		// Filter by rating.
		if ( isset( $_GET['rating_filter'] ) ) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$rating_filter = array_filter( array_map( 'absint', explode( ',', wp_unslash( $_GET['rating_filter'] ) ) ) );
			$rating_terms  = array();
			for ( $i = 1; $i <= 5; $i ++ ) {
				if ( in_array( $i, $rating_filter, true ) && isset( $course_visibility_terms[ 'rated-' . $i ] ) ) {
					$rating_terms[] = $course_visibility_terms[ 'rated-' . $i ];
				}
			}
			if ( ! empty( $rating_terms ) ) {
				$tax_query[] = array(
					'taxonomy'      => 'course_visibility',
					'field'         => 'term_taxonomy_id',
					'terms'         => $rating_terms,
					'operator'      => 'IN',
					'rating_filter' => true,
				);
			}
		}
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		if ( ! empty( $course_visibility_not_in ) ) {
			$tax_query[] = array(
				'taxonomy' => 'course_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $course_visibility_not_in,
				'operator' => 'NOT IN',
			);
		}

		/**
		 * Filters tax query args.
		 *
		 * @since 1.0.0
		 *
		 * @param array $tax_query Tax query args.
		 */
		return array_filter( apply_filters( 'masteriyo_course_query_tax_query', $tax_query ) );
	}

	/**
	 * Get the main query which course queries ran against.
	 *
	 * @return WP_Query
	 */
	public function get_main_query() {
		return $this->course_query;
	}

	/**
	 * Remove the add-to-cart param from pagination urls.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url URL.
	 * @return string
	 */
	public function remove_add_to_cart_pagination( $url ) {
		return remove_query_arg( 'add-to-cart', $url );
	}

	/**
	 * Is the front page a page we define?
	 *
	 * @since 1.4.5
	 *
	 * @param int $page_id Page ID.
	 * @return bool
	 */
	private function page_on_front_is( $page_id ) {
		return absint( get_option( 'page_on_front' ) ) === absint( $page_id );
	}
}
