<?php
/**
 * Migration class template used by the wp cli to create migration classes.
 *
 * @since 1.6.8
 */

use Masteriyo\Roles;
use Masteriyo\Database\Migration;

class RemoveUsersListPermissionFromStudentAndInstrcutorRole extends Migration {
	/**
	 * Run the migration.
	 *
	 * @since 1.6.8
	 */
	public function up() {
		$student = get_role( Roles::STUDENT );
		if ( $student ) {
			$student->remove_cap( 'read_users' );
		}

		$instructor = get_role( Roles::INSTRUCTOR );
		if ( $instructor ) {
			$instructor->remove_cap( 'read_users' );
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @since 1.6.8
	 */
	public function down() {
		$student = get_role( Roles::STUDENT );
		if ( $student ) {
			$student->add_cap( 'read_users' );
		}

		$instructor = get_role( Roles::INSTRUCTOR );
		if ( $instructor ) {
			$instructor->add_cap( 'read_users' );
		}
	}
}
