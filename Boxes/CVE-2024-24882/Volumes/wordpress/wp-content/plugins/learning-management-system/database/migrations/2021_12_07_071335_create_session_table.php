<?php
/**
 * Create session table.
 *
 * @since 1.3.4
 */

use Masteriyo\Database\Migration;

/**
 * Create session table.
 *
 * @since 1.3.4
 */
class CreateSessionTable extends Migration {
	/**
	 * Run the migration.
	 *
	 * @since 1.3.4
	 */
	public function up() {
		$sql = "CREATE TABLE {$this->prefix}masteriyo_sessions (
			session_id BIGINT UNSIGNED AUTO_INCREMENT,
			session_key CHAR(32) NOT NULL,
			session_data LONGTEXT NOT NULL,
			session_expiry BIGINT UNSIGNED NOT NULL,
			user_agent LONGTEXT,
			PRIMARY KEY  (session_id),
			UNIQUE KEY session_key (session_key)
		) $this->charset_collate;";

		dbDelta( $sql );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @since 1.3.4
	 */
	public function down() {
		$this->connection->query( "DROP TABLE IF EXISTS {$this->prefix}masteriyo_sessions;" );
	}
}
