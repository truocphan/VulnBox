<?php
/**
 * Setting Repository
 */

namespace Masteriyo\Repository;

use Masteriyo\Constants;
use Masteriyo\Database\Model;
use Masteriyo\Models\Setting;

class SettingRepository extends AbstractRepository implements RepositoryInterface {
	/**
	 * Create a setting in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Setting $setting Setting object.
	 */
	public function create( Model &$setting ) {
		$posted_setting = $setting->get_data();
		$setting_in_db  = get_option( 'masteriyo_settings', array() );

		$posted_setting = $this->clean_setting( $posted_setting );
		$setting_in_db  = $this->clean_setting( $setting_in_db );

		// if courses permalink / slugs changed then update masteriyo_flush_rewrite_rules.
		$should_update_permalink = false;
		foreach ( $posted_setting['advance']['permalinks'] as $permalink => $value ) {
			if ( ! isset( $setting_in_db['advance']['permalinks'][ $permalink ] ) ) {
				$should_update_permalink = true;
				break;
			}

			if ( $value !== $setting_in_db['advance']['permalinks'][ $permalink ] ) {
				$should_update_permalink = true;
				break;
			}
		}

		if ( $should_update_permalink ) {
			update_option( 'masteriyo_flush_rewrite_rules', 'yes' );
		}

		$setting_in_db = wp_parse_args( $posted_setting, $setting_in_db );

		$setting->reset();
		$setting->set_data( $setting_in_db );

		update_option( 'masteriyo_settings', $setting->get_data() );

		/**
		 * Fires after creating a setting.
		 *
		 * @since 1.0.0
		 *
		 * @param \Masteriyo\Models\Setting $object The setting object.
		 */
		do_action( 'masteriyo_new_setting', $setting );
	}

	/**
	 * Read a setting.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Setting $setting Course object.
	 * @param mixed $default Default value.
	 *
	 * @throws Exception If invalid setting.
	 */
	public function read( Model &$setting, $default = null ) {
		global $wpdb;

		$setting_in_db = get_option( 'masteriyo_settings', array() );
		$setting_in_db = masteriyo_parse_args( $setting_in_db, $setting->get_data() );
		$setting_in_db = $this->clean_setting( $setting_in_db );

		$setting->set_data( $setting_in_db );

		$this->process_setting( $setting );

		$setting->set_object_read( true );

		/**
		 * Fires after reading setting from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id ID.
		 * @param \Masteriyo\Models\Setting $object The setting object.
		 */
		do_action( 'masteriyo_setting_read', $setting->get_id(), $setting );
	}

	/**
	 * Update a setting in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $setting Setting object.
	 *
	 * @return void
	 */
	public function update( Model &$setting ) {
		return new \WP_Error(
			'invalid-method',
			// translators: %s: Class method name.
			sprintf( __( "Method '%s' not implemented.", 'masteriyo' ), __METHOD__ ),
			array( 'status' => 405 )
		);
	}

	/**
	 * Delete a setting from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Setting $setting Setting object.
	 * @param array $args   Array of args to pass.alert-danger
	 */
	public function delete( Model &$setting, $args = array() ) {
		$setting_data = $setting->get_data();
		update_option( 'masteriyo_settings', $setting_data );

		/**
		 * Fires after resetting setting from database.
		 *
		 * @since 1.0.0
		 *
		 * @param \Masteriyo\Models\Setting $object The setting object.
		 */
		do_action( 'masteriyo_reset_setting', $setting );
	}

	/**
	 * Process setting.
	 *
	 * @since 1.0.0
	 *
	 * @param  \Masteriyo\Models\Setting Setting object.
	 * @return void
	 */
	protected function process_setting( &$setting ) {
		if ( Constants::get( 'MASTERIYO_TEMPLATE_DEBUG_MODE' ) ) {
			$setting->set( 'advance.debug.template_debug', Constants::get( 'MASTERIYO_TEMPLATE_DEBUG_MODE' ) );
		}

		if ( Constants::get( 'MASTERIYO_DEBUG' ) ) {
			$setting->set( 'advance.debug.debug', Constants::get( 'MASTERIYO_DEBUG' ) );
		}
	}

	/**
	 * Clean setting and store only which are in the $data of the Setting model.
	 *
	 * @since 1.4.2
	 *
	 * @param array $setting Setting array.
	 */
	protected function clean_setting( $setting ) {
		$setting_dot_arr = masteriyo_array_dot( $setting );

		// Default $data array.
		$setting_object          = masteriyo( 'setting' );
		$default_setting_dot_arr = masteriyo_array_dot( $setting_object->get_data() );

		$setting_dot_arr = array_filter(
			$setting_dot_arr,
			function ( $key ) use ( $default_setting_dot_arr ) {
				return isset( $default_setting_dot_arr[ $key ] );
			},
			ARRAY_FILTER_USE_KEY
		);

		return masteriyo_array_undot( $setting_dot_arr );
	}
}
