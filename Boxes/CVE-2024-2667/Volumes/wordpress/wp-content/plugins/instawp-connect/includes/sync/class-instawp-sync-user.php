<?php

defined( 'ABSPATH' ) || exit;

class InstaWP_Sync_User {

    public function __construct() {
	    // User actions
	    add_action( 'user_register', array( $this, 'user_register' ), 10, 2 );
	    add_action( 'delete_user', array( $this, 'delete_user' ), 10, 3 );
	    add_action( 'profile_update', array( $this, 'profile_update' ) );

		// Process event
	    add_filter( 'INSTAWP_CONNECT/Filters/process_two_way_sync', array( $this, 'parse_event' ), 10, 2 );
    }

	/**
	 * Function for `user_register` action-hook.
	 *
	 * @param int   $user_id  User ID.
	 * @param array $userdata The raw array of data passed to wp_insert_user().
	 *
	 * @return void
	 */
	public function user_register( $user_id, $userdata ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'user' ) ) {
			return;
		}

		if ( empty( $userdata ) ) {
			return;
		}

		$event_name   = __( 'New user registered', 'instawp-connect' );
		$user         = get_user_by( 'id', $user_id );
		$reference_id = InstaWP_Sync_Helpers::get_user_reference_id( $user_id );

		$userdata['user_registered']     = $user->data->user_registered;
		$userdata['user_activation_key'] = $user->data->user_activation_key;

		$details = array(
			'user_data'    => $userdata,
			'user_meta'    => get_user_meta( $user_id ),
			'user_roles'   => get_userdata( $user_id )->roles,
			'reference_id' => $reference_id,
			'db_prefix'    => InstaWP_Sync_DB::prefix(),
		);

		InstaWP_Sync_DB::insert_update_event( $event_name, 'user_register', 'users', $reference_id, $userdata['user_login'], $details );
	}

	/**
	 * Function for `delete_user` action-hook.
	 *
	 * @param int      $id       ID of the user to delete.
	 * @param int|null $reassign ID of the user to reassign posts and links to.
	 * @param WP_User  $user     WP_User object of the user to delete.
	 *
	 * @return void
	 */
	public function delete_user( $id, $reassign, $user ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'user' ) ) {
			return;
		}

		$reassign_details = null;
		if ( $reassign ) {
			$reassign_user    = get_user_by( 'id', $reassign );
			$reassign_details = [
				'user_data'    => $reassign_user->data,
				'reference_id' => InstaWP_Sync_Helpers::get_user_reference_id( $reassign )
			];
		}

		$event_name   = __( 'User deleted', 'instawp-connect' );
		$reference_id = InstaWP_Sync_Helpers::get_user_reference_id( $id );
		$title        = $user->data->user_login;
		$details      = array(
			'user_data'    => get_userdata( $id ),
			'reassign'     => $reassign_details,
			'reference_id' => $reference_id,
		);

		InstaWP_Sync_DB::insert_update_event( $event_name, 'delete_user', 'users', $reference_id, $title, $details );
	}

	/**
	 * Function for `profile_update` action-hook.
	 *
	 * @param int $user_id  User ID.
	 *
	 * @return void
	 */
	public function profile_update( $user_id ) {
		if ( ! isset( $_POST['submit'] ) || ! InstaWP_Sync_Helpers::can_sync( 'user' ) ) {
			return;
		}

		$user_data    = get_user_by( 'id', $user_id );
		$event_name   = __( 'User updated', 'instawp-connect' );
		$reference_id = InstaWP_Sync_Helpers::get_user_reference_id( $user_id );

		$details = array(
			'user_data'    => $user_data->data,
			'user_meta'    => get_user_meta( $user_id ),
			'user_roles'   => $user_data->roles,
			'reference_id' => $reference_id,
			'db_prefix'    => InstaWP_Sync_DB::prefix(),
		);

		error_log(print_r($details,1));

		InstaWP_Sync_DB::insert_update_event( $event_name, 'profile_update', 'users', $reference_id, $user_data->data->user_login, $details );
	}

	public function parse_event( $response, $v ) {
		if ( $v->event_type !== 'users' ) {
			return $response;
		}

		$details          = InstaWP_Sync_Helpers::object_to_array( $v->details );
		$user_data        = isset( $details['user_data'] ) ? $details['user_data'] : array();
		$user_meta        = isset( $details['user_meta'] ) ? $details['user_meta'] : array();
		$source_db_prefix = isset( $details['db_prefix'] ) ? $details['db_prefix'] : '';
		$user_roles       = isset( $details['user_roles'] ) ? $details['user_roles'] : array();
		$reference_id     = isset( $details['reference_id'] ) ? $details['reference_id'] : '';
		$user_table       = InstaWP_Sync_DB::prefix() . 'users';
		$log_data         = array();
		$user_id          = $this->get_user_id( $reference_id, $user_data['user_email'] );

		if ( $v->event_slug === 'user_register' && ! empty( $user_data ) ) {
			$user_id = wp_insert_user( $user_data );

			if ( is_wp_error( $user_id ) ) {
				$log_data[ $v->id ] = $user_id->get_error_message();
			} else {
				$this->manage_user_meta( $user_meta, $user_id, $source_db_prefix );
			}
		}

		if ( $v->event_slug === 'profile_update' ) {
			$user_pass = $user_data['user_pass'];
			unset( $user_data['user_pass'] );

			if ( $user_id ) {
				$user_data['ID'] = $user_id;
				$user_id         = wp_update_user( $user_data );

				if ( is_wp_error( $user_id ) ) {
					$log_data[ $v->id ] = $user_id->get_error_message();
				} else {
					InstaWP_Sync_DB::update( $user_table, array( 'user_pass' => $user_pass ), array( 'ID' => $user_id ) );
					$this->manage_user_meta( $user_meta, $user_id, $source_db_prefix );

					if ( ! empty( $user_roles ) ) {
						$user = get_user_by('id', $user_id );

						foreach ( $user_roles as $role ) {
							$user->add_role( $role );
						}
					}
				}
			} else {
				$log_data[ $v->id ] = esc_html__( 'User not found for update operation.', 'instawp-connect' );
			}
		}

		if ( $v->event_slug === 'delete_user' ) {
			if ( $user_id ) {
				$reassign_user_id = null;
				if ( ! empty( $details['reassign']['reference_id'] ) && ! empty( $details['reassign']['user_data']['user_email'] ) ) {
					$reassign_user_id = $this->get_user_id( $details['reassign']['reference_id'], $details['reassign']['user_data']['user_email'] );
				}
				wp_delete_user( $user_id, $reassign_user_id );
			} else {
				$log_data[ $v->id ] = esc_html__( 'User not found for delete operation.', 'instawp-connect' );
			}
		}

		return InstaWP_Sync_Helpers::sync_response( $v, $log_data );
	}

	public function manage_user_meta( $user_meta, $user_id, $source_db_prefix ) {
		if ( ! empty( $user_meta ) && is_array( $user_meta ) ) {
			foreach ( $user_meta as $key => $value ) {
				$key = str_replace( $source_db_prefix, InstaWP_Sync_DB::wpdb()->prefix, $key );

				update_user_meta( $user_id, $key, maybe_unserialize( reset( $value ) ) );
			}
		}
	}

	public function get_user_id( $reference_id, $email ) {
		$get_users_by_reference_id = get_users( array(
			'meta_key'   => 'instawp_event_user_sync_reference_id',
			'meta_value' => $reference_id,
			'fields'     => 'ID'
		) );
		$user_id = ! empty( $get_users_by_reference_id[0] ) ? $get_users_by_reference_id[0] : null;

		if ( empty( $user_id ) ) {
			$user    = get_user_by( 'email', $email );
			$user_id = $user ? $user->data->ID : null;
		}

		return $user_id;
	}
}

new InstaWP_Sync_User();