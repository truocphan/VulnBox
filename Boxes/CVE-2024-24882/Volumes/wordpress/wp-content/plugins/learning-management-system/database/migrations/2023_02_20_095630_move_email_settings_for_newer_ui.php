<?php
/**
 * Migration class template used by the wp cli to create migration classes.
 *
 * @since 1.5.35
 */

use Masteriyo\Database\Migration;

class MoveEmailSettingsForNewerUi extends Migration {
	/**
	 * Run the migration.
	 *
	 * @since 1.5.35
	 */
	public function up() {
		$settings       = get_option( 'masteriyo_settings' );
		$is_new_setting = masteriyo_array_get( $settings, 'emails.admin.new_order.enable', null );

		if ( null !== $is_new_setting ) {
			return;
		}

		$new_settings = array(
			'admin'      => array(
				'new_order' => array(
					'enable' => masteriyo_array_get( $settings, 'emails.new_order.enable', true ),
				),
			),
			'instructor' => array(
				'instructor_registration' => array(
					'enable' => masteriyo_array_get( $settings, 'emails.become_an_instructor.enable', true ),
				),
			),
			'student'    => array(
				'student_registration' => array(
					'enable' => true,
				),
				'completed_order'      => array(
					'enable' => masteriyo_array_get( $settings, 'emails.completed_order.enable', true ),
				),
				'onhold_order'         => array(
					'enable' => masteriyo_array_get( $settings, 'emails.onhold_order.enable', true ),
				),
				'cancelled_order'      => array(
					'enable' => masteriyo_array_get( $settings, 'emails.cancelled_order.enable', true ),
				),
			),
		);

		masteriyo_set_setting( 'emails', $new_settings );
		$settings = get_option( 'masteriyo_settings' );
		$a        = 1;
	}

	/**
	 * Reverse the migrations.
	 *
	 * @since 1.5.35
	 */
	public function down() {
		$settings       = get_option( 'masteriyo_settings' );
		$is_old_setting = masteriyo_array_get( $settings, 'emails.new_order.enable', null );

		if ( null !== $is_old_setting ) {
			return;
		}

		$new_settings = array(
			'new_order'            => array(
				'enable' => masteriyo_array_get( $settings, 'emails.admin.new_order.enable', true ),
			),
			'completed_order'      => array(
				'enable' => masteriyo_array_get( $settings, 'emails.student.completed_order.enable', true ),
			),
			'onhold_order'         => array(
				'enable' => masteriyo_array_get( $settings, 'emails.student.onhold_order.enable', true ),
			),
			'cancelled_order'      => array(
				'enable' => masteriyo_array_get( $settings, 'emails.student.cancelled_order.enable', true ),
			),
			'become_an_instructor' => array(
				'enable' => masteriyo_array_get( $settings, 'emails.instructor.instructor_registration.enable', true ),
			),
		);

		masteriyo_set_setting( 'emails', $new_settings );
	}
}
