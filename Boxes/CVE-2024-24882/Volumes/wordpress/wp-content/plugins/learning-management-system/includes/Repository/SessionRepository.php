<?php
/**
 * Session Repository
 *
 * @since 1.0.0
 * @class Session
 * @package Masteriyo\Session
 */

namespace Masteriyo\Repository;

use Masteriyo\Database\Model;
use Masteriyo\MetaData;
use Masteriyo\Repository\RepositoryInterface;

defined( 'ABSPATH' ) || exit;

class SessionRepository implements RepositoryInterface {
	/**
	 * Create a session in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Session\Session $session session object.
	 */
	public function create( Model &$session ) {
		global $wpdb;

		if ( ! $session->get_user_agent( 'edit' ) ) {
			$session->set_user_agent( masteriyo_get_user_agent() );
		}

		$wpdb->replace(
			$session->get_table(),
			array(
				'session_key'    => $session->get_key(),
				'session_data'   => maybe_serialize( $session->all() ),
				'session_expiry' => $session->get_expiry(),
				'user_agent'     => $session->get_user_agent( 'edit ' ),
			),
			array( '%s', '%s', '%d', '%s' )
		);

		$session->set_id( $wpdb->insert_id );
		$session->set_object_read( true );
		$session->apply_changes();

		/**
		 * Fires after creating a session in database.
		 *
		 * @since 1.3.8
		 *
		 * @param integer $integer Session ID.
		 * @param \Masteriyo\Session\Session $session The session object.
		 */
		do_action( 'masteriyo_new_session', $session->get_id(), $session );
	}

	/**
	 * Delete a session from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Session\Session $session Session object.
	 * @param array $args   Array of args to pass.
	 */
	public function delete( Model &$session, $args = array() ) {
		global $wpdb;

		$id          = $session->get_id();
		$object_type = $session->get_object_type();

		if ( $session->get_id() ) {
			$session_table = $session->get_table();

			/**
			 * Before session delete action.
			 *
			 * @since 1.3.8
			 *
			 * @param integer $integer Session ID.
			 * @param \Masteriyo\Session\Session $session The session object.
			 */
			do_action( 'masteriyo_before_delete_' . $object_type, $id, $session );

			$wpdb->delete( $session_table, array( 'session_id' => $session->get_id() ) );

			/**
			 * After session delete action.
			 *
			 * @since 1.5.2
			 *
			 * @param integer $integer Session ID.
			 * @param \Masteriyo\Session\Session $session The session object.
			 */
			do_action( 'masteriyo_after_delete_' . $object_type, $id, $session );
		}
	}

	/**
	 * Read a session.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Session\Session $session Course object.
	 * @throws Exception If invalid session.
	 */
	public function read( Model &$session ) {
		global $wpdb;

		if ( ! empty( $session->get_key() ) ) {
			$result = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}masteriyo_sessions WHERE session_key = %s",
					$session->get_key()
				)
			);

			if ( ! is_null( $result ) ) {
				$session->set_props(
					array(
						'key'        => $result->session_key,
						'data'       => maybe_unserialize( $result->session_data ),
						'expiry'     => $result->session_expiry,
						'user_agent' => $result->user_agent,
					)
				);

				$session->set_id( $result->session_id );
				$session->set_object_read( true );
			}
		}

		/**
		 * Fires after reading a session object from database.
		 *
		 * @since 1.3.8
		 *
		 * @param integer $integer Session ID.
		 * @param \Masteriyo\Session\Session $session The session object.
		 */
		do_action( 'masteriyo_session_read', $session->get_id(), $session );
	}

	/**
	 * Update a session in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Session\Session $session Session object.
	 *
	 * @return void
	 */
	public function update( Model &$session ) {
		global $wpdb;

		if ( $session->is_dirty() ) {
			$wpdb->replace(
				$session->get_table(),
				array(
					'session_key'    => $session->get_key(),
					'session_data'   => maybe_serialize( $session->all() ),
					'session_expiry' => $session->get_expiry(),
					'user_agent'     => $session->get_user_agent( 'edit ' ),
				),
				array( '%s', '%s', '%d', '%s' )
			);
		}

		if ( $wpdb->insert_id ) {
			$session->apply_changes();
			$session->set_id( $wpdb->insert_id );
		}

		/**
		 * Fires after updating a session in database.
		 *
		 * @since 1.3.8
		 *
		 * @param integer $integer Session ID.
		 * @param \Masteriyo\Session\Session $session The session object.
		 */
		do_action( 'masteriyo_update_session', $session->get_id(), $session );
	}

	/**
	 * Delete session from the database by session key.
	 *
	 * @since 1.5.11
	 *
	 * @param string $key
	 */
	public function delete_by_key( $session ) {
		global $wpdb;

		$wpdb->delete(
			$session->get_table(),
			array(
				'session_key' => $session->get_key(),
			),
			array( '%s' )
		);
	}

	/**
	 * Returns an array of meta for an object.
	 *
	 * @since 1.0.0
	 *
	 * @param  Model  $model a Model object.
	 * @return MetaData[]
	 */
	public function read_meta( Model &$model ) {

	}

	/**
	 * Deletes meta based on meta ID.
	 *
	 * @since 1.0.0
	 *
	 * @param  Model  $model a Model object.
	 * @param  MetaData  $meta Meta object (containing at least ->id).
	 * @return array
	 */
	public function delete_meta( Model &$model, MetaData $meta ) {

	}

	/**
	 * Add new piece of meta.
	 *
	 * @since 1.0.0
	 *
	 * @param  Model  $model a Model object.
	 * @param  MetaData  $meta Meta object (containing ->key and ->value).
	 * @return int meta ID
	 */
	public function add_meta( Model &$model, MetaData $meta ) {

	}

	/**
	 * Update meta.
	 *
	 * @since 1.0.0
	 *
	 * @param  Model  $model a Model object.
	 * @param  MetaData  $meta Meta object (containing ->id, ->key and ->value).
	 */
	public function update_meta( Model &$model, MetaData $meta ) {

	}
}
