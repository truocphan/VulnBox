<?php
/**
 * Create order items table.
 *
 * @since 1.3.4
 */

use Masteriyo\Database\Migration;

/**
 * Create order items table.
 *
 * @since 1.3.4
 */
class CreateOrderItemsTable extends Migration {
	/**
	 * Run the migration.
	 *
	 * @since 1.3.4
	 */
	public function up() {
		$sql = "CREATE TABLE {$this->prefix}masteriyo_order_items (
			order_item_id BIGINT UNSIGNED AUTO_INCREMENT,
			order_item_name TEXT NOT NULL,
			order_item_type  VARCHAR(200) NOT NULL,
			order_id BIGINT UNSIGNED NOT NULL,
			PRIMARY KEY  (order_item_id),
			KEY order_id (order_id),
			KEY order_item_type (order_item_type)
		) $this->charset_collate;";

		dbDelta( $sql );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @since 1.3.4
	 */
	public function down() {
		$this->connection->query( "DROP TABLE IF EXISTS {$this->prefix}masteriyo_order_items;" );   }
}
