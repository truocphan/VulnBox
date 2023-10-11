<?php
/**
 * Welcart member class
 *
 * @package  Welcart
 */

namespace Welcart;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Member class
 *
 * The Welcart item class handles individual member data.
 *
 * @since 2.2.2
 */
class MemberData {

	/**
	 * ID for this object.
	 *
	 * @since 2.2.2
	 * @var int
	 */
	protected $id = 0;

	/**
	 * Core data for this object. Name value pairs (name + default value).
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $data = array();

	/**
	 * Meta data for this object. Name value pairs (name + default value).
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $meta = array();

	/**
	 * Extra data for this object. Name value pairs (name + default value).
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $extra_data = array();

	/**
	 * Get the Member if ID is passed, otherwise data is false.
	 * This class should not be instantiated.
	 * The wc_get_member() function should be used instead.
	 *
	 * @param int $member_id ID of the member.
	 */
	public function __construct( $member_id = 0 ) {
		if ( is_numeric( $member_id ) && $member_id > 0 ) {
			$this->set_id( $member_id );
		} else {
			$this->set_id( 0 );
		}

		$this->set_data( $this->id );
	}

	/**
	 * Set ID.
	 *
	 * @param int $id ID.
	 */
	public function set_id( $id ) {
		$this->id = absint( $id );
	}

	/**
	 * Set data.
	 *
	 * @since  2.2.2
	 * @param int $member_id Memner id.
	 */
	public function set_data( $member_id ) {
		global $wpdb;

		if ( 0 === $member_id ) {
			return false;
		}

		// core data.
		$member_cache_key = 'wel_member_data_' . $member_id;
		$_data            = wp_cache_get( $member_cache_key );
		if ( false === $_data ) {
			$table = usces_get_tablename( 'usces_member' );
			$_data = $wpdb->get_row(
				$wpdb->prepare( "SELECT * FROM {$table} WHERE ID = %d", $member_id )
			);
			if ( null !== $_data ) {
				wp_cache_set( $member_cache_key, $_data );
			}
		}

		if ( null === $_data ) {
			return false;
		}

		$this->data = array(
			'ID'           => $_data->ID,
			'registered'   => $_data->mem_registered,
			'mailaddress'  => $_data->mem_email,
			'point'        => $_data->mem_point,
			'name1'        => $_data->mem_name1,
			'name2'        => $_data->mem_name2,
			'name3'        => $_data->mem_name3,
			'name4'        => $_data->mem_name4,
			'zipcode'      => $_data->mem_zip,
			'address1'     => $_data->mem_address1,
			'address2'     => $_data->mem_address2,
			'address3'     => $_data->mem_address3,
			'tel'          => $_data->mem_tel,
			'fax'          => $_data->mem_fax,
			'pref'         => $_data->mem_pref,
			'status'       => $_data->mem_status,
		);

		// meta data.
		$member_meta_cache_key = 'wel_member_meta_' . $member_id;

		$_meta = wp_cache_get( $member_meta_cache_key );
		if ( false === $_meta ) {
			$table = usces_get_tablename( 'usces_member_meta' );
			$query = $wpdb->prepare( "SELECT meta_key, meta_value FROM {$table} WHERE member_id = %d", $member_id );
			$_meta = $wpdb->get_results( $query );
			if ( null !== $_meta ) {
				wp_cache_set( $member_meta_cache_key, $_meta );
			}
		}

		foreach ( $_meta as $meta ) {
			$this->meta[ $meta->meta_key ] = maybe_unserialize( $meta->meta_value );
		}

		$this->data = array_merge( $this->data, $this->meta );
	}

	/**
	 * Returns all data for this object.
	 *
	 * @since  2.2.2
	 * @return array
	 */
	public function get_data() {
		if ( ! isset( $this->data['ID'] ) ) {
			return false;
		} else {
			return array_merge( $this->data, $this->extra_data );
		}
	}

	/**
	 * Returns member's ID by email.
	 *
	 * @since  2.2.2
	 * @param string $email Member's email address.
	 * @return int ID
	 */
	public function get_id_by_email( $email ) {
		global $wpdb;

		$cache_key = 'wel_member_id_by_email_' . $email;

		$id = wp_cache_get( $cache_key );
		if ( false === $id ) {
			$table = usces_get_tablename( 'usces_member' );
			$id    = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT `ID` FROM {$table} WHERE mem_email = %s",
					$email
				)
			);
			if ( null !== $id ) {
				wp_cache_set( $cache_key, $id );
			}
		}

		if ( null === $id ) {
			return false;
		} else {
			return $id;
		}
	}

}
