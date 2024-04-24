<?php
/**
 * User query class that extends WP_User_Query class.
 *
 * @package Masteriyo\Query
 *
 * @since 1.5.4
 */

namespace Masteriyo\Query;

defined( 'ABSPATH' ) || exit;

class WPUserQuery extends \WP_User_Query {

	/**
	 * Prepares the query variables.
	 *
	 * @since 1.5.4
	 *
	 * @param array $query WP_User_Query args.
	 */
	public function prepare_query( $query = array() ) {
		parent::prepare_query( $query );

		if ( isset( $query['user_status'] ) ) {
			$this->query_where .= ' AND user_status = ' . absint( $query['user_status'] );
		}
	}
}
