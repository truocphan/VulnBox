<?php

namespace MasterStudy\Lms\Database;

class Query {
	const ORDER_ASCENDING = 'ASC';

	const ORDER_DESCENDING = 'DESC';

	const OUTPUT_OBJECT = 'OBJECT';

	const OUTPUT_ARRAY = 'ARRAY';

	protected string $select = '*';

	protected string $asTable = '';

	protected int $limit = 0;

	protected int $offset = 0;

	protected array $where = array();

	protected string $join = '';

	protected string $sort_by = 'id';

	protected string $order = 'ASC';

	protected string $group_by = '';

	protected string $having = '';

	protected ?string $search_term = null;

	protected array $search_fields = array();

	protected string $model;

	protected string $primary_key;

	public function __construct( string $model ) {
		$this->model = $model;
	}

	public function __toString(): string {
		return $this->getSql( 'count' );
	}

	public function toString( $query_type = 'select' ) {
		return $this->getSql( $query_type );
	}

	/**
	 * Set the fields to include in the search.
	 */
	public function set_searchable_fields( array $fields ): void {
		$this->search_fields = $fields;
	}

	/**
	 * Set the primary key column.
	 */
	public function set_primary_key( string $primary_key ): void {
		$this->primary_key = $primary_key;
	}

	/**
	 * @return $this
	 */
	public function select( string $select ) {
		$this->select = $select;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function asTable( string $asTable ) {
		$this->asTable = $asTable;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function join( string $join ) {
		$this->join .= ' ' . $join;

		return $this;
	}

	/**
	 * Set the maximum number of results to return at once.
	 *
	 * @return $this
	 */
	public function limit( int $limit ) {
		$this->limit = $limit;

		return $this;
	}

	/**
	 * Set the offset to use when calculating results.
	 *
	 * @return $this
	 */
	public function offset( int $offset ) {
		$this->offset = $offset;

		return $this;
	}

	/**
	 * Set the column we should sort by.
	 *
	 * @return $this
	 */
	public function sort_by( string $sort_by ) {
		$this->sort_by = $sort_by;

		return $this;
	}

	/**
	 * Set the order we should sort by.
	 *
	 * @return $this
	 */
	public function order( string $order ) {
		$this->order = $order;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function group_by( string $group_by ) {
		$this->group_by = $group_by;

		return $this;
	}

	/**
	 * Add a `=` clause to the search query.
	 *
	 * @return $this
	 */
	public function where_raw( string $conditions ) {
		$this->where[] = array(
			'type'       => 'raw',
			'conditions' => $conditions,
		);

		return $this;
	}

	/**
	 * Add a `=` clause to the search query.
	 *
	 * @return $this
	 */
	public function where( string $column, string $value ) {
		$this->where[] = array(
			'type'   => 'where',
			'column' => $column,
			'value'  => $value,
		);

		return $this;
	}

	/**
	 * Add a `!=` clause to the search query.
	 *
	 * @return $this
	 */
	public function where_not( string $column, string $value ) {
		$this->where[] = array(
			'type'   => 'not',
			'column' => $column,
			'value'  => $value,
		);

		return $this;
	}

	/**
	 * Add a `LIKE` clause to the search query.
	 *
	 * @return $this
	 */
	public function where_like( string $column, string $value ) {
		$this->where[] = array(
			'type'   => 'like',
			'column' => $column,
			'value'  => $value,
		);

		return $this;
	}

	/**
	 * Add a `NOT LIKE` clause to the search query.
	 *
	 * @return $this
	 */
	public function where_not_like( string $column, string $value ) {
		$this->where[] = array(
			'type'   => 'not_like',
			'column' => $column,
			'value'  => $value,
		);

		return $this;
	}

	/**
	 * Add a `<` clause to the search query.
	 *
	 * @return $this
	 */
	public function where_lt( string $column, string $value ) {
		$this->where[] = array(
			'type'   => 'lt',
			'column' => $column,
			'value'  => $value,
		);

		return $this;
	}

	/**
	 * Add a `<=` clause to the search query.
	 *
	 * @return $this
	 */
	public function where_lte( string $column, string $value ) {
		$this->where[] = array(
			'type'   => 'lte',
			'column' => $column,
			'value'  => $value,
		);

		return $this;
	}

	/**
	 * Add a `>` clause to the search query.
	 *
	 * @return $this
	 */
	public function where_gt( string $column, string $value ) {
		$this->where[] = array(
			'type'   => 'gt',
			'column' => $column,
			'value'  => $value,
		);

		return $this;
	}

	/**
	 * Add a `>=` clause to the search query.=
	 *
	 * @return $this
	 */
	public function where_gte( string $column, string $value ) {
		$this->where[] = array(
			'type'   => 'gte',
			'column' => $column,
			'value'  => $value,
		);

		return $this;
	}

	/**
	 * Add a `BETWEEN` clause to the search query.
	 *
	 * @return $this
	 */
	public function where_between( string $column, array $values ) {
		$this->where[] = array(
			'type'   => 'between',
			'column' => $column,
			'value'  => $values,
		);

		return $this;
	}

	/**
	 * Add an `IN` clause to the search query.=
	 *
	 * @return $this
	 */
	public function where_in( string $column, array $in ) {
		$this->where[] = array(
			'type'   => 'in',
			'column' => $column,
			'value'  => $in,
		);

		return $this;
	}

	/**
	 * Add a `NOT IN` clause to the search query.
	 *
	 * @return $this
	 */
	public function where_not_in( string $column, array $not_in ) {
		$this->where[] = array(
			'type'   => 'not_in',
			'column' => $column,
			'value'  => $not_in,
		);

		return $this;
	}

	/**
	 * Add an OR statement to the where clause (e.g. (var = foo OR var = bar OR var = baz)).
	 *
	 * @return $this
	 */
	public function where_any( array $where ) {
		$this->where[] = array(
			'type'  => 'any',
			'where' => $where,
		);

		return $this;
	}

	/**
	 * Add an AND statement to the where clause (e.g. (var1 = foo AND var2 = bar AND var3 = baz)).
	 *
	 * @return $this
	 */
	public function where_all( array $where ) {
		$this->where[] = array(
			'type'  => 'all',
			'where' => $where,
		);

		return $this;
	}

	/**
	 * Get models where any of the designated fields match the given value.
	 *
	 * @return $this
	 */
	public function search( string $search_term ) {
		$this->search_term = $search_term;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function having( $having ) {
		$this->having = $having;

		return $this;
	}

	/**
	 * Runs the same query as find, but with no limit and don't retrieve the results, just the total items found.
	 */
	public function total_count(): int {
		return $this->find( true );
	}

	/**
	 * Compose & execute our query.
	 */
	public function findOne() {
		$models = $this->find();

		return $models[0] ?? false;
	}

	public function getSql( $query_type = 'select', $args = array() ) {
		$data         = $this->build();
		$sql          = '';
		$select_query = "FROM `{$data['table']}` \n {$data['asTable']} \n {$data['join']} \n {$data['where']}";
		$having_query = "\n {$data['group_by']} \n {$data['having']}";

		switch ( $query_type ) {
			case 'update':
				$set = implode(
					', ',
					array_map(
						function ( $v, $k ) {
							return sprintf( "%s='%s'", $k, $v );
						},
						$args,
						array_keys( $args )
					)
				);

				$sql = "UPDATE {$data['table']} {$data['asTable']} {$data['join']} SET {$set} {$data['where']}";
				break;
			case 'delete':
				$this->select = ( '*' !== $this->select ) ? $this->select : '';

				$sql = " DELETE {$this->select} $select_query";
				break;
			case 'count':
				$this->select = ( '*' === $this->select || empty( $this->select ) )
					? ' COUNT(*) '
					: " COUNT(*), {$this->select}";

				$sql = " SELECT {$this->select} {$select_query} {$having_query}";
				break;
			case 'select':
				$sql = " SELECT {$this->select} {$select_query} {$having_query} \n {$data['order']} \n {$data['limit']} \n {$data['offset']} ";
				break;
		}

		return apply_filters( 'ms_lms_query_get_sql', $sql, $this->model );
	}

	public function update( $args ) {
		global $wpdb;

		$this->build();

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		return $wpdb->get_results( $this->getSql( 'update', $args ) );
	}

	public function delete() {
		global $wpdb;

		$this->build();

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		return $wpdb->get_results( $this->getSql( 'delete' ) );
	}

	public function find( $only_count = false, $output = self::OUTPUT_OBJECT ) {
		global $wpdb;

		$model = new $this->model();

		$this->build();

		if ( $only_count ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			return (int) $wpdb->get_var( $this->getSql( 'count' ) );
		}

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$results = $wpdb->get_results( $this->getSql() );

		if ( $results ) {
			foreach ( $results as $index => $result ) {
				if ( self::OUTPUT_OBJECT === $output ) {
					$results[ $index ] = $model->create( (array) $result );
				}
				if ( self::OUTPUT_ARRAY === $output ) {
					$results[ $index ] = (array) $model->create( (array) $result );
				}
			}
		}

		return $results;
	}

	public function findByIndex( $key ) {
		global $wpdb;

		$this->build();

		$items = array();
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$results = $wpdb->get_results( $this->getSql( 'select' ) );

		if ( $results ) {
			foreach ( $results as $result ) {
				$items[ $result->$key ] = ( new $this->model() )->create( (array) $result );
			}
		}

		return $items;
	}

	public function build() {
		$table    = ( new $this->model() )->get_table();
		$join     = $this->join;
		$where    = '';
		$group_by = '';
		$having   = '';
		$limit    = '';
		$offset   = '';
		$asTable  = '';

		if ( $this->asTable ) {
			$asTable = " as {$this->asTable}";
		}

		if ( ! empty( $this->search_term ) ) {
			$where .= ' AND (';

			foreach ( $this->search_fields as $field ) {
				$where .= $field . ' LIKE "%' . esc_sql( $this->search_term ) . '%" OR ';
			}

			$where = substr( $where, 0, - 4 ) . ')';
		}

		foreach ( $this->where as $q ) {
			switch ( $q['type'] ) {
				case 'raw':
					$where .= " AND {$q['conditions']} ";
					break;
				case 'where':
					$where .= " AND `{$q['column']}`" . ' = "' . esc_sql( $q['value'] ) . '"';
					break;
				case 'not':
					$where .= " AND `{$q['column']}`" . ' != "' . esc_sql( $q['value'] ) . '"';
					break;
				case 'like':
					$where .= " AND `{$q['column']}`" . ' LIKE "' . esc_sql( $q['value'] ) . '"';
					break;
				case 'not_like':
					$where .= " AND `{$q['column']}`" . ' NOT LIKE "' . esc_sql( $q['value'] ) . '"';
					break;
				case 'lt':
					$where .= " AND `{$q['column']}`" . ' < "' . esc_sql( $q['value'] ) . '"';
					break;
				case 'lte':
					$where .= " AND `{$q['column']}`" . ' <= "' . esc_sql( $q['value'] ) . '"';
					break;
				case 'gt':
					$where .= " AND `{$q['column']}`" . ' > "' . esc_sql( $q['value'] ) . '"';
					break;
				case 'gte':
					$where .= " AND `{$q['column']}`" . ' >= "' . esc_sql( $q['value'] ) . '"';
					break;
				case 'between':
					$where .= " AND `{$q['column']}`" . ' BETWEEN ' . esc_sql( min( $q['value'] ) ) . ' AND ' . esc_sql( max( $q['value'] ) );
					break;
				case 'in':
				case 'not_in':
					if ( ! empty( $q['value'] ) ) {
						$clause = 'in' === $q['type'] ? 'IN' : 'NOT IN';
						$where .= " AND {$q['column']} {$clause} (";

						foreach ( $q['value'] as $value ) {
							$where .= '"' . esc_sql( $value ) . '",';
						}

						$where = substr( $where, 0, - 1 ) . ')';
					}
					break;
				case 'any':
					$where .= ' AND (';

					foreach ( $q['where'] as $column => $value ) {
						$where .= $column . ' = "' . esc_sql( $value ) . '" OR ';
					}

					$where = substr( $where, 0, - 5 ) . ')';
					break;
				case 'all':
					$where .= ' AND (';

					foreach ( $q['where'] as $column => $value ) {
						$where .= $column . ' = "' . esc_sql( $value ) . '" AND ';
					}

					$where = substr( $where, 0, - 5 ) . ')';
					break;
			}
		}

		// Finish where clause
		if ( ! empty( $where ) ) {
			$where = ' WHERE ' . substr( $where, 5 );
		}

		if ( strstr( $this->sort_by, '(' ) !== false && strstr( $this->sort_by, ')' ) !== false ) {
			// The sort column contains () so we assume its a function, therefore don't quote it
			$order = " ORDER BY {$this->sort_by} {$this->order}";
		} else {
			if ( false !== strpos( $this->sort_by, ',' ) ) {
				$sort_by = $this->sort_by;
			} else {
				$sort_by = ! empty( $this->asTable ) ? "{$this->asTable}.{$this->sort_by}" : "`{$this->sort_by}`";
			}
			$order = " ORDER BY {$sort_by} {$this->order}";
		}

		if ( ! empty( $this->group_by ) ) {
			$group_by = " GROUP BY {$this->group_by}";
		}

		if ( ! empty( $this->having ) ) {
			$having = " HAVING {$this->having}";
		}

		if ( $this->limit > 0 ) {
			$limit = " LIMIT {$this->limit}";
		}

		if ( $this->offset > 0 ) {
			$offset = " OFFSET {$this->offset}";
		}

		return array(
			'table'    => $table,
			'asTable'  => $asTable,
			'join'     => $join,
			'where'    => $where,
			'group_by' => $group_by,
			'order'    => $order,
			'having'   => $having,
			'limit'    => $limit,
			'offset'   => $offset,
		);
	}

}
