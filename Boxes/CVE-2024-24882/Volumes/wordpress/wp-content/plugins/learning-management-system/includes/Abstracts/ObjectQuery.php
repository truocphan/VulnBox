<?php
/**
 * Query abstraction layer functionality.
 *
 * @since 1.0.0
 *
 * @package  Masteriyo\Abstracts
 */

namespace Masteriyo\Abstracts;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract Object Query Class
 *
 * Extended by classes to provide a query abstraction layer for safe object searching.
 *
 * @version  1.0.0
 * @package  Masteriyo\Abstracts
 */
abstract class ObjectQuery {
	/**
	 * The amount of rows for the current query.
	 *
	 * @since 1.3.1
	 * @var int
	 */
	public $rows_count = 0;

	/**
	 * The amount of found rows for the current query.
	 *
	 * If limit clause was not used, equals $rows_count.
	 *
	 * @since 1.3.1
	 * @var int
	 */
	public $found_rows = 0;

	/**
	 * Stores query data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $query_vars = array();

	/**
	 * Create a new query.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Criteria to query on in a format similar to WP_Query.
	 */
	public function __construct( $args = array() ) {
		$this->query_vars = wp_parse_args( $args, $this->get_default_query_vars() );

		$this->parse_query_vars();
	}

	/**
	 * Get the current query vars.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_query_vars() {
		return $this->query_vars;
	}

	/**
	 * Get the value of a query variable.
	 *
	 * @since 1.0.0
	 *
	 * @param string $query_var Query variable to get value for.
	 * @param mixed  $default Default value if query variable is not set.
	 * @return mixed Query variable value if set, otherwise default.
	 */
	public function get( $query_var, $default = '' ) {
		if ( isset( $this->query_vars[ $query_var ] ) ) {
			return $this->query_vars[ $query_var ];
		}
		return $default;
	}

	/**
	 * Set a query variable.
	 *
	 * @since 1.0.0
	 *
	 * @param string $query_var Query variable to set.
	 * @param mixed  $value Value to set for query variable.
	 */
	public function set( $query_var, $value ) {
		$this->query_vars[ $query_var ] = $value;
	}

	/**
	 * Set a query variables.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_vars Query variables to set.
	 */
	public function set_args( $query_vars ) {
		foreach ( $query_vars as $query_var => $value ) {
			$this->set( $query_var, $value );
		}
		return $this;
	}

	/**
	 * Get the default allowed query vars.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {

		return array(
			'name'           => '',
			'parent'         => '',
			'parent_exclude' => '',
			'include'        => '',
			'exclude'        => '',
			'per_page'       => get_option( 'posts_per_page' ),
			'limit'          => get_option( 'posts_per_page' ),
			'page'           => 1,
			'offset'         => 0,
			'paginate'       => false,
			'order'          => 'DESC',
			'orderby'        => 'date',
			'return'         => 'objects',
		);
	}

	/**
	 * Parse query vars.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function parse_query_vars() {
		// Override this function in child class to map the query vars.
	}
}
