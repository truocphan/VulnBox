<?php

namespace Masteriyo\Database;

abstract class Migration {

	/**
	 * The name of the database connection to use.
	 *
	 * @var wpdb
	 */
	protected $connection;

	/**
	 * Database charset collation.
	 *
	 * @var string
	 */
	protected $charset_collate;

	/**
	 * Table prefix.
	 *
	 * @var string
	 */
	protected $prefix;

	/**
	 * Constructor.
	 *
	 */
	public function __construct() {
		global $wpdb;

		$this->connection      = $wpdb;
		$this->prefix          = $wpdb->prefix;
		$this->charset_collate = $this->get_collation();
	}

	/**
	 * Get
	 *
	 * @return void
	 */
	public function get_base_prefix() {

	}
	/**
	 * Get the migration connection name.
	 *
	 * @return wpdb
	 */
	public function get_connection() {
		return $this->connection;
	}

	/**
	 * Get database collation.
	 *
	 * @return string
	 */
	protected function get_collation() {
		if ( ! $this->connection->has_cap( 'collation' ) ) {
			return '';
		}

		return $this->connection->get_charset_collate();
	}

	/**
	 * Run the migration.
	 */
	abstract public function up();

	/**
	 * Reverse the migration
	 */
	abstract public function down();

}
