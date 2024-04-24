<?php
/**
 * Create user items table.
 *
 * @since 1.3.4
 */

use Masteriyo\Database\Migration;

/**
 * Create user items table.
 */
class CreateUserItemsTable extends Migration {
	/**
	 * Run the migration.
	 *
	 * @since 1.3.4
	 */
	public function up() {
		$sql = "CREATE TABLE {$this->prefix}masteriyo_user_items (
			id BIGINT UNSIGNED AUTO_INCREMENT,
			user_id CHAR(32) NOT NULL,
			item_id BIGINT UNSIGNED NOT NULL,
			item_type VARCHAR(255) NOT NULL DEFAULT '',
			status VARCHAR(255) NOT NULL DEFAULT '',
			parent_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
			date_start DATETIME DEFAULT '0000-00-00 00:00:00',
			date_modified DATETIME DEFAULT '0000-00-00 00:00:00',
			date_end DATETIME DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY  (id),
			KEY user_id (user_id),
			KEY item_id (item_id),
			KEY parent_id (parent_id),
			KEY status (status(191)),
			KEY item_type (item_type(191)),
			KEY date_start (date_start),
			KEY date_modified (date_modified),
			KEY date_end (date_end)
		) $this->charset_collate;";

		dbDelta( $sql );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @since 1.3.4
	 */
	public function down() {
		$this->connection->query( "DROP TABLE IF EXISTS {$this->prefix}masteriyo_user_items;" );
	}
}
