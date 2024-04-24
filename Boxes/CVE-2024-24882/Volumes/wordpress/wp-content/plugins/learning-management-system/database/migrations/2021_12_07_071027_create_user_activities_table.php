<?php
/**
 * Create user activities table.
 *
 * @since 1.3.4
 */

use Masteriyo\Database\Migration;

/**
 * User activities table.
 *
 * @since 1.3.4
 */
class CreateUserActivitiesTable extends Migration {
	/**
	 * Run the migration.
	 */
	public function up() {
		$sql = "CREATE TABLE {$this->prefix}masteriyo_user_activities (
			id BIGINT UNSIGNED AUTO_INCREMENT,
			user_id CHAR(32) NOT NULL,
			item_id BIGINT UNSIGNED NOT NULL DEFAULT '0',
			activity_type VARCHAR(20) DEFAULT NULL,
			activity_status VARCHAR(20) DEFAULT NULL,
			parent_id BIGINT UNSIGNED NOT NULL DEFAULT '0',
			created_at DATETIME DEFAULT '0000-00-00 00:00:00',
			modified_at DATETIME DEFAULT '0000-00-00 00:00:00',
			completed_at DATETIME DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY  (id),
			KEY user_id (user_id),
			KEY item_id (item_id),
			KEY parent_id (parent_id),
			KEY activity_type (activity_type),
			KEY activity_status (activity_status),
			KEY created_at (created_at),
			KEY modified_at (modified_at),
			KEY completed_at (completed_at)
		) $this->charset_collate;";

		dbDelta( $sql );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @since 1.3.4
	 */
	public function down() {
		$this->connection->query( "DROP TABLE IF EXISTS {$this->prefix}masteriyo_user_activities;" );
	}
}
