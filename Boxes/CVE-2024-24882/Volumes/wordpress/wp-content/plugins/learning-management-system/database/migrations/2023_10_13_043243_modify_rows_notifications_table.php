<?php
/**
 * Notification table migration class.
 *
 * @since 1.7.1
 */

use Masteriyo\Database\Migration;

class ModifyRowsNotificationsTable extends Migration {
	/**
	 * Run the migration.
	 *
	 * @since 1.7.1
	 */
	public function up() {
		$sql = "ALTER TABLE {$this->prefix}masteriyo_notifications
    DROP COLUMN code,
    DROP COLUMN level,
    DROP COLUMN action_ok,
    DROP COLUMN action_cancel,
    DROP COLUMN action_1,
    DROP COLUMN action_2,
    DROP COLUMN action_3,
	ADD COLUMN topic_url varchar(250) DEFAULT ' ' AFTER type,
	ADD COLUMN post_id BIGINT UNSIGNED DEFAULT 0 AFTER topic_url;";

		$this->connection->query( $sql );
	}


	/**
	 * Reverse the migrations.
	 *
	 * @since 1.7.1
	 */
	public function down() {
		$this->connection->query( "DROP TABLE IF EXISTS {$this->prefix}masteriyo_notifications;" );
	}
}
