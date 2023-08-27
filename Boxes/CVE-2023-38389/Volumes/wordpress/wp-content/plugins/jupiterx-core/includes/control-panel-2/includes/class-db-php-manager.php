<?php
/**
 * Handle database management using PHP.
 *
 * @package JupiterX_Core\Control_Panel\PHP_Database_Manager
 *
 * @since 1.11.0
 *
 * @todo Clean up.
 *
 * phpcs:ignoreFile
 */

/**
 * PHP database manager.
 *
 * @since 1.11.0
 *
 * @SuppressWarnings(PHPMD)
 */
class JupiterX_Core_Control_Panel_PHP_DB_Manager {

	/**
	 * Set DB max time execution.
	 */
	const DB_MAX_TIME = 5000;

	/**
	 * Custom table prefix.
	 */
	const TABLE_PREFIX = 'jx_';

	/**
	 * Construct class.
	 */
	public function __constructor() {}

	/**
	 * Dump database tables.
	 *
	 * @param string $file File path.
	 *
	 * @return boolean|string Dump success.
	 */
	public function dump_tables( $file, $pick_tables = [] ) {
		try {
			if ( empty( $pick_tables ) ) {
				return true;
			}

			global $wpdb;

			// Set DB wait time out.
			$wpdb->query( 'SET session wait_timeout = ' . self::DB_MAX_TIME );

			// Attempt to create file.
			if ( ( $handle = fopen( $file, 'w+' ) ) === false ) {
				throw new Exception( esc_html__( 'Can\'t open: ', 'jupiterx-core' ) . $file );
			}

			// Get tables.
			$tables      = $wpdb->get_col( "SHOW FULL TABLES WHERE Table_Type != 'VIEW'" );
			$pick_tables = count( $pick_tables ) ? $pick_tables : null;
			$query_limit = 100;

			// Filter tables to dump.
			if ( is_array( $pick_tables ) ) {
				foreach ( $tables as $key => $table ) {
					if ( ! in_array( $tables[ $key ], $pick_tables ) ) {
						unset( $tables[ $key ] );
					}
				}
			}

			// Add file headers.
			$sql_header = "/* JUPITERX-TABLES (PHP DUMP) MYSQL SCRIPT CREATED ON : " . @date( "Y-m-d H:i:s" ) . " */\n\n";
			$sql_header .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";
			fwrite( $handle, $sql_header );

			// All tables must be created before inserts due to foreign key constraints.
			foreach ( $tables as $table ) {
				$count            = 1;
				$rewrite_table    = str_replace( $this->get_table_prefix(), self::TABLE_PREFIX, $table, $count );

				// Drop table.
				$drop_table_query = 'DROP TABLE IF EXISTS `' . $rewrite_table . '`';
				fwrite( $handle, "/* DROP TABLE: {$rewrite_table} */\n" );
				fwrite( $handle, "{$drop_table_query};\n\n" );

				// Create table.
				$create             = $wpdb->get_row( "SHOW CREATE TABLE `{$table}`", ARRAY_N );
				$create_table_query = str_replace( 'CREATE TABLE `' . $table . '`', 'CREATE TABLE `' . $rewrite_table . '`', $create[1], $count );
				$create_table_query = trim( preg_replace( '/\s+/', ' ', $create_table_query ) );
				fwrite( $handle, "/* CREATE TABLE: {$rewrite_table} */\n" );
				fwrite( $handle, "{$create_table_query};\n\n" );
			}

			// Create insert in 100 row increments to better handle memory.
			foreach ( $tables as $table ) {
				$count         = 1;
				$row_count     = $wpdb->get_var( "SELECT Count(*) FROM `{$table}`" );
				$rewrite_table = str_replace( $this->get_table_prefix(), self::TABLE_PREFIX, $table, $count );

				if ( $row_count > $query_limit ) {
					$row_count = ceil( $row_count / $query_limit );
				} else if ( $row_count > 0 ) {
					$row_count = 1;
				}

				if ( $row_count >= 1 ) {
					fwrite( $handle, "/* INSERT TABLE DATA: {$rewrite_table} */\n" );
				}

				for ( $i = 0; $i < $row_count; $i++ ) {
					$sql   = '';
					$limit = $i * $query_limit;
					$query = "SELECT * FROM `{$table}` LIMIT {$limit}, {$query_limit}";
					$rows  = $wpdb->get_results( $query, ARRAY_A );

					if ( '' !== $wpdb->last_error ) {
						throw new Exception( esc_html__( 'Please contact your database administrator to fix the error. Error: ', 'jupiterx-core' ) . $wpdb->last_error );
						return;
					}

					if ( is_array( $rows ) ) {
						foreach ( $rows as $row ) {
							$sql         .= "INSERT INTO `{$rewrite_table}` VALUES(";
							$num_values  = count( $row );
							$num_counter = 1;

							foreach ( $row as $value ) {
								if ( is_null( $value ) || ! isset( $value ) ) {
									( $num_values == $num_counter ) ? $sql .= 'NULL' : $sql .= 'NULL, ';
								} else {
									( $num_values == $num_counter )
										? $sql .= '"' . $this->esc_sql( $value, true ) . '"'
										: $sql .= '"' . $this->esc_sql( $value, true ) . '", ';
								}
								$num_counter++;
							}
							$sql .= ");\n";
						}
						fwrite( $handle, $sql );
					}
				}

				$sql  = null;
				$rows = null;
				fwrite( $handle, "\n" );
			}

			$sql_footer = "SET FOREIGN_KEY_CHECKS = 1;\n";
			fwrite( $handle, $sql_footer );
			$wpdb->flush();
			fclose( $handle );
			return true;
		} catch ( Exception $e ) {
			return $e->getMessage();
		}
	}

	/**
	 * Import to database.
	 *
	 * @return boolean|string Import success.
	 */
	public function import_tables( $file ) {
		try {
			if ( ! file_exists( $file ) ) {
				throw new Exception( esc_html__( 'File not exist: ', 'jupiterx-core' ) . $file );
			}

			// Attempt to read file.
			if ( ( $handle = fopen( $file, 'r' ) ) === false ) {
				throw new Exception( esc_html__( 'Can\'t read: ', 'jupiterx-core' ) . $file );
			}

			global $wpdb;

			while ( ! feof( $handle ) ) {
				$line = fgets( $handle );

				// Set allowed queries to run.
				if ( preg_match( '/^\s*(?:SET.?|DROP TABLE.?|CREATE TABLE.?|INSERT INTO.?)\s+/is', $line ) ) {
					$line = $this->replace_table_prefix( $line );

					$wpdb->query( $line );
					if ( '' !== $wpdb->last_error ) {
						error_log( $wpdb->last_error );
					}
				}
			}

			$wpdb->flush();
			fclose( $handle );
			return true;
		} catch ( Exception $e ) {
			return $e->getMessage();
		}
	}

	/**
	 * Get table prefix.
	 *
	 * @return string Table prefix.
	 */
	public function get_table_prefix() {
		global $wpdb;

		return $wpdb->prefix;
	}

	/**
	 * Escape SQL.
	 *
	 * @see https://make.wordpress.org/core/2017/10/31/changed-behaviour-of-esc_sql-in-wordpress-4-8-3
	 *
	 * @param string  $data               Data.
	 * @param boolean $placeholder_escape Escape placeholder.
	 *
	 * @return string Escaped data.
	 */
	private function esc_sql( $data, $placeholder_escape = false ) {
		global $wpdb;

		if ( $placeholder_escape ) {
			return $wpdb->remove_placeholder_escape( @esc_sql( $data ) );
		} else {
			return @esc_sql( $data );
		}
	}

	/**
	 * Replace SQL line table prefix.
	 *
	 * @param string $line File line.
	 *
	 * @return string Updated line.
	 */
	private function replace_table_prefix( $line ) {
		$count = 1;

		if ( preg_match( '/CREATE TABLE `(.*?)`/', $line, $matches ) ) {
			$rewrite_table = str_replace( self::TABLE_PREFIX, $this->get_table_prefix(), $matches[1], $count );
			$line          = str_replace( 'CREATE TABLE `' . $matches[1] . '`', 'CREATE TABLE `' . $rewrite_table . '`', $line, $count );
		} elseif ( preg_match( '/INSERT INTO `(.*?)`/', $line, $matches ) ) {
			$rewrite_table = str_replace( self::TABLE_PREFIX, $this->get_table_prefix(), $matches[1], $count );
			$line          = str_replace( 'INSERT INTO `' . $matches[1] . '`', 'INSERT INTO `' . $rewrite_table . '`', $line, $count );
		} else if ( preg_match( '/DROP TABLE IF EXISTS `(.*?)`/', $line, $matches ) ) {
			$rewrite_table = str_replace( self::TABLE_PREFIX, $this->get_table_prefix(), $matches[1], $count );
			$line          = str_replace( 'DROP TABLE IF EXISTS `' . $matches[1] . '`', 'DROP TABLE IF EXISTS `' . $rewrite_table . '`', $line, $count );
		}

		return $line;
	}
}
