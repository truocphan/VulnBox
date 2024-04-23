<?php

class IWPDB {

	/**
	 * @var mysqli
	 */
	public $conn = null;
	public $last_error = '';
	private $migrate_key = '';
	private $options_data = array();

	private static $_table_option = 'iwp_options';

	public function __construct( $key ) {
		$this->migrate_key = $key;

		$this->set_options_data();
		$this->connect_database();
		$this->create_require_tables();
	}

	public function db_get_option( $option_name, $default_value = '' ) {

		$option_data = $this->get_row( self::$_table_option, array( 'option_name' => $option_name ) );

		return isset( $option_data['option_value'] ) ? $option_data['option_value'] : $default_value;
	}

	public function db_update_option( $option_name, $option_value = '' ) {

		$option_data = $this->get_row( self::$_table_option, array( 'option_name' => $option_name ) );

		if ( empty( $option_data ) || ! $option_data ) {
			return $this->insert( self::$_table_option, array(
				'option_name'  => "'{$option_name}'",
				'option_value' => "'{$option_value}'",
			) );
		}

		return $this->update( self::$_table_option, array( 'option_value' => $option_value ), array( 'option_name' => $option_name ) );
	}

	public function insert( $table_name, $data = array() ) {

		$column_names  = implode( ',', array_keys( $data ) );
		$column_values = implode( ',', array_values( $data ) );

		$insert_res = $this->query( "INSERT INTO {$table_name} ({$column_names}) VALUES ({$column_values})" );

		if ( $insert_res ) {
			return true;
		}

		return false;
	}

	public function update( $table_name, $data = array(), $where_array = array() ) {

		$set_arr = array();

		foreach ( $data as $key => $val ) {
			$set_arr[] = "`$key` = '$val'";
		}

		$set_str   = implode( ',', $set_arr );
		$query_res = $this->query( "UPDATE {$table_name} SET {$set_str} WHERE {$this->build_where_clauses($where_array)}" );

		if ( $query_res ) {
			return true;
		}

		return false;
	}

	public function get_row( $table_name, $where_array = array() ) {

		$fetch_row_res = $this->query( "SELECT * FROM {$table_name} WHERE {$this->build_where_clauses($where_array)} LIMIT 1" );

		$this->fetch_rows( $fetch_row_res, $result );

		if ( isset( $result[0] ) ) {
			return $result[0];
		}

		return array();
	}

	public function get_rows( $table_name, $where_array = array() ) {
		/**
		 * @todo implement latter
		 */
		$query_res = $this->query( "SELECT * FROM {$table_name} WHERE {$this->build_where_clauses($where_array)}" );

		$this->fetch_rows( $query_res, $results );

		return $results;
	}

	public function fetch_rows( mysqli_result $mysqli_result, &$rows ) {
		while ( $row = $mysqli_result->fetch_assoc() ) {
			$rows[] = $row;
		}
	}

	public function query_count( $table_name, $where_array = array() ) {
		$query_count_res = $this->query( "SELECT count(*) as count FROM {$table_name} WHERE {$this->build_where_clauses($where_array)}" );

		if ( ! $query_count_res ) {
			return 0;
		}

		$query_count_array = $query_count_res->fetch_array();

		return isset( $query_count_array['count'] ) ? $query_count_array['count'] : 0;
	}

	public function query( $str_query = '' ) {

		try {
			$query_result = $this->conn->query( $str_query );
		} catch ( Exception $e ) {
			$this->last_error = $e->getMessage();
		}

		if ( $query_result instanceof mysqli_result ) {
			return $query_result;
		}

		return false;
	}

	public function create_require_tables() {
		$this->query( "CREATE TABLE IF NOT EXISTS iwp_files_sent (id INT AUTO_INCREMENT PRIMARY KEY, filepath TEXT, filepath_hash CHAR(64) UNIQUE, sent INT DEFAULT 0, size INT)" );
		$this->query( "CREATE TABLE IF NOT EXISTS iwp_db_sent (id INT AUTO_INCREMENT PRIMARY KEY, table_name TEXT, table_name_hash CHAR(64) UNIQUE, `offset` INT DEFAULT 0, rows_total INT DEFAULT 0, completed INT DEFAULT 0);" );
		$this->query( "CREATE TABLE IF NOT EXISTS iwp_options (id INT AUTO_INCREMENT PRIMARY KEY, option_name CHAR(64), option_value CHAR(64));" );
	}

	public function connect_database() {
		$db_host     = $this->get_option( 'db_host' );
		$db_username = $this->get_option( 'db_username' );
		$db_password = $this->get_option( 'db_password' );
		$db_name     = $this->get_option( 'db_name' );
		$mysqli      = new mysqli( $db_host, $db_username, $db_password, $db_name );

		if ( $mysqli->connect_error ) {
			$this->last_error = $mysqli->connect_error;
		}

		if ( $mysqli ) {
			mysqli_set_charset( $mysqli, "utf8" );

			$this->conn = $mysqli;
		}
	}

	public function set_options_data() {

		$options_data_filename = INSTAWP_BACKUP_DIR . 'options-' . $this->migrate_key . '.txt';

		if ( ! is_readable( $options_data_filename ) ) {
			return;
		}

		$options_data_encrypted = file_get_contents( $options_data_filename );

		if ( $options_data_encrypted ) {
			$options_data_decrypted = openssl_decrypt( $options_data_encrypted, 'AES-128-ECB', $this->migrate_key );
			$this->options_data     = json_decode( $options_data_decrypted, true );
		}
	}

	public function get_option( $option_name = '', $default = '' ) {
		return isset( $this->options_data[ $option_name ] ) ? $this->options_data[ $option_name ] : $default;
	}

	public function update_option( $option_name = '', $value = '' ) {

		if ( empty( $option_name || empty( $this->migrate_key ) ) ) {
			return false;
		}

		$this->options_data[ $option_name ] = $value;

		$options_data_str       = json_encode( $this->options_data );
		$options_data_encrypted = openssl_encrypt( $options_data_str, 'AES-128-ECB', $this->migrate_key );
		$options_data_filename  = INSTAWP_BACKUP_DIR . 'options-' . $this->migrate_key . '.txt';
		$options_data_stored    = file_put_contents( $options_data_filename, $options_data_encrypted );

		if ( ! $options_data_stored ) {
			return false;
		}

		return true;
	}

	private function index_exists( $indexName, $tableName ) {
		$query  = "SHOW INDEX FROM `$tableName` WHERE Key_name = '$indexName'";
		$result = $this->query( $query );

		return $result && $result->num_rows > 0;
	}

	public function create_file_indexes( $table_name, $indexes = array() ) {
		foreach ( $indexes as $indexName => $columnName ) {
			if ( ! $this->index_exists( $indexName, $table_name ) ) {
				$this->query( "CREATE INDEX `$indexName` ON `$table_name`(`$columnName`)" );
			}
		}
	}

	public function get_all_tables() {

		$all_tables      = array();
		$show_tables_res = $this->query( 'SHOW TABLES' );

		$this->fetch_rows( $show_tables_res, $tables );

		$tables = array_map( function ( $table_name ) {
			if ( is_array( $table_name ) ) {
				$table_name_arr = array_values( $table_name );

				return isset( $table_name_arr[0] ) ? $table_name_arr[0] : '';
			}

			return '';
		}, $tables );

		foreach ( $tables as $table_name ) {

			// remove our tracking tables
			if ( in_array( $table_name, array( 'iwp_db_sent', 'iwp_files_sent', 'iwp_options' ) ) ) {
				continue;
			}

			$row_count_res = $this->query( "SELECT COUNT(*) AS row_count FROM `$table_name`" );
			$row_count_row = $row_count_res->fetch_assoc();
			$row_count     = $row_count_row['row_count'];

			$all_tables[ $table_name ] = $row_count;
		}

		return $all_tables;
	}

	private function build_where_clauses( $where_arr = array() ) {
		$where_str     = '1';
		$where_strings = array();

		foreach ( $where_arr as $key => $value ) {
			$where_strings[] = "`{$key}` = '$value'";
		}

		if ( ! empty( $where_strings ) ) {
			$where_str = implode( ' AND ', $where_strings );
		}

		return $where_str;
	}

	public function __destruct() {
		$this->conn->close();
	}
}

