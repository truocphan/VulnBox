<?php

defined( 'ABSPATH' ) || exit;

class InstaWP_Sync_Option {

    public function __construct() {
	    // Update option
	    add_action( 'added_option', array( $this, 'added_option' ), 10, 2 );
	    add_action( 'updated_option', array( $this, 'updated_option' ), 10, 3 );
	    add_action( 'deleted_option', array( $this, 'deleted_option' ) );

	    // Process event
	    add_filter( 'INSTAWP_CONNECT/Filters/process_two_way_sync', array( $this, 'parse_event' ), 10, 2 );
    }

	public function added_option( $option, $value ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'option' ) ) {
			return;
		}

		if ( ! $this->is_protected_option( $option ) ) {
			$data = array(
				'name'  => $option,
				'value' => maybe_serialize( $value ),
			);
			InstaWP_Sync_DB::insert_update_event( __( 'Option added', 'instawp-connect' ), 'add_option', 'option', $option, ucfirst( str_replace( array( '-', '_' ), ' ', $option ) ), $data );
		}
	}

	public function updated_option( $option, $old_value, $value ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'option' ) ) {
			return;
		}

		if ( ! $this->is_protected_option( $option ) ) {
			$data = array(
				'name'  => $option,
				'value' => maybe_serialize( $value ),
			);
			InstaWP_Sync_DB::insert_update_event( __( 'Option updated', 'instawp-connect' ), 'update_option', 'option', $option, ucfirst( str_replace( array( '-', '_' ), ' ', $option ) ), $data );
		}
	}

	public function deleted_option( $option ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'option' ) ) {
			return;
		}

		if ( ! $this->is_protected_option( $option ) ) {
			InstaWP_Sync_DB::insert_update_event( __( 'Option deleted', 'instawp-connect' ), 'delete_option', 'option', $option, ucfirst( str_replace( array( '-', '_' ), ' ', $option ) ), $option );
		}
	}

	public function parse_event( $response, $v ) {
		if ( $v->event_type !== 'option' ) {
			return $response;
		}

		$data = InstaWP_Sync_Helpers::object_to_array( $v->details );

		// add or update option
		if ( in_array( $v->event_slug, array( 'add_option', 'update_option' ), true ) ) {
			InstaWP_Setting::update_option( $data['name'], maybe_unserialize( $data['value'] ) );
		}

		// delete option
		if ( $v->event_slug === 'delete_option' ) {
			delete_option( $data );
		}

		return InstaWP_Sync_Helpers::sync_response( $v );
	}

	private function is_protected_option( $option ) {
		$excluded_options = array( 'cron', 'instawp_api_options', 'siteurl', 'home', 'permalink_structure', 'rewrite_rules', 'recently_activated', 'active_plugins', 'theme_switched', 'sidebars_widgets', 'theme_switch_menu_locations', 'recovery_mode_email_last_sent', 'recovery_keys', 'auto_updater.lock', 'elementor_version', 'elementor_log' );

		if ( in_array( $option, $excluded_options, true )
			|| strpos( $option, '_transient' ) !== false
			|| strpos( $option, 'instawp' ) !== false
			|| strpos( $option, 'action_scheduler' ) !== false
		) {
			return true;
		}

		return false;
	}
}

new InstaWP_Sync_Option();