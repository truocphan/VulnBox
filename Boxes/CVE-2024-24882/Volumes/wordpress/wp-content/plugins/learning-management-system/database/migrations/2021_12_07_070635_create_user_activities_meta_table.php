<?php
/**
 * Create user activities meta table.
 *
 * @since 1.3.4
 */

use Masteriyo\Database\Migration;

/**
 * Create user activities meta table.
 *
 * @since 1.3.4
 */
class CreateUserActivitiesMetaTable extends Migration {
	/**
	 * Run the migration.
	 *
	 * @since 1.3.4
	 */
	public function up() {
		$sql = "CREATE TABLE {$this->prefix}masteriyo_user_activitymeta (
			meta_id BIGINT UNSIGNED AUTO_INCREMENT,
			user_activity_id BIGINT UNSIGNED NOT NULL,
			meta_key VARCHAR(255) NOT NULL,
			meta_value LONGTEXT,
			PRIMARY KEY  (meta_id),
			KEY user_activity_id (user_activity_id),
			KEY meta_key (meta_key(191))
		) $this->charset_collate;";

		dbDelta( $sql );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @since 1.3.4
	 */
	public function down() {
		$this->connection->query( "DROP TABLE IF EXISTS {$this->prefix}masteriyo_user_activitymeta;" );
	}
}
