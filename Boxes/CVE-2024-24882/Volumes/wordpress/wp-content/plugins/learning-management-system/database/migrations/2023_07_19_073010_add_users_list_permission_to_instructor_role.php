<?php
/**
 * Add users list permission to instructor role back.
 *
 * @since 1.6.9
 */

use Masteriyo\Roles;
use Masteriyo\Database\Migration;

class AddUsersListPermissionToInstructorRole extends Migration {
	/**
	 * Run the migration.
	 *
	 * @since 1.6.9
	 */
	public function up() {
		$instructor = get_role( Roles::INSTRUCTOR );
		if ( $instructor ) {
			$instructor->add_cap( 'read_users' );
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @since 1.6.9
	 */
	public function down() {
		$instructor = get_role( Roles::INSTRUCTOR );
		if ( $instructor ) {
			$instructor->remove_cap( 'read_users' );
		}
	}
}
