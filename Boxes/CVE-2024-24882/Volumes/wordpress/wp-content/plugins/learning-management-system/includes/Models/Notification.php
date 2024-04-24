<?php
/**
 * Notification model.
 *
 * @since 1.4.1
 *
 * @package Masteriyo\Models;
 */

namespace Masteriyo\Models;

use Masteriyo\Database\Model;
use Masteriyo\Repository\RepositoryInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Notification model (post type).
 *
 * @since 1.4.1
 */
class Notification extends Model {
	/**
	 * This is the name of this object type.
	 *
	 * @since 1.4.1
	 *
	 * @var string
	 */
	protected $object_type = 'notification';

	/**
	 * Post type.
	 *
	 * @since 1.4.1
	 *
	 * @var string
	 */
	protected $post_type = 'notification';

	/**
	 * Cache group.
	 *
	 * @since 1.4.1
	 *
	 * @var string
	 */
	protected $cache_group = 'notifications';

	/**
	 * Stores notification data.
	 *
	 * @since 1.4.1
	 *
	 * @var array
	 */
	protected $data = array(
		'id'          => 0,
		'title'       => '',
		'description' => '',
		'user_id'     => 0,
		'created_by'  => 0,
		'status'      => '',
		'type'        => '',
		'topic_url'   => '',
		'post_id'     => '',
		'created_at'  => null,
		'modified_at' => null,
		'expire_at'   => null,
	);

	/**
	 * Constructor.
	 *
	 * @since 1.4.1
	 *
	 * @param RepositoryInterface $notification_repository Notification Repository,
	 */
	public function __construct( RepositoryInterface $notification_repository ) {
		$this->repository = $notification_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Non CRUD Getters
	|--------------------------------------------------------------------------
	*/
	public function get_table_name() {
		global $wpdb;

		return "{$wpdb->prefix}masteriyo_notifications";
	}



	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get notification title.
	 *
	 * @since 1.4.1
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_title( $context = 'view' ) {
		return $this->get_prop( 'title', $context );
	}

	/**
	 * Get notification description.
	 *
	 * @since 1.4.1
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_description( $context = 'view' ) {
		return $this->get_prop( 'description', $context );
	}

	/**
	 * Get notification user id.
	 *
	 * @since 1.4.1
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return integer
	 */
	public function get_user_id( $context = 'view' ) {
		return $this->get_prop( 'user_id', $context );
	}

	/**
	 * Get notification created by.
	 *
	 * @since 1.4.1
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return integer
	 */
	public function get_created_by( $context = 'view' ) {
		return $this->get_prop( 'created_by', $context );
	}

	/**
	 * Get notification created by.
	 *
	 * @since 1.7.1
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return integer
	 */
	public function get_course_id( $context = 'view' ) {
		return $this->get_prop( 'course_id', $context );
	}

	/**
	 * Get notification status.
	 *
	 * @since 1.4.1
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_status( $context = 'view' ) {
		return $this->get_prop( 'status', $context );
	}

	/**
	 * Get notification type.
	 *
	 * @since 1.4.1
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_type( $context = 'view' ) {
		return $this->get_prop( 'type', $context );
	}

	/**
	 * Get notification created at.
	 *
	 * @since  1.7.1
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_created_at( $context = 'view' ) {
		return $this->get_prop( 'created_at', $context );
	}

	/**
	 * Get notification modified at.
	 *
	 * @since  1.7.1
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_modified_at( $context = 'view' ) {
		return $this->get_prop( 'date_modified', $context );
	}

	/**
	 * Get notification expiry at.
	 *
	 * @since  1.7.1
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_expire_at( $context = 'view' ) {
		return $this->get_prop( 'date_end', $context );
	}

	/**
	 * Get notification modified at.
	 *
	 * @since  1.7.1
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_topic_url( $context = 'view' ) {
		return $this->get_prop( 'topic_url', $context );
	}

	/**
	 * Get notification modified at.
	 *
	 * @since  1.7.1
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_post_id( $context = 'view' ) {
		return $this->get_prop( 'post_id', $context );
	}


	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set notification title.
	 *
	 * @since 1.4.1
	 *
	 * @param string $title Notification title.
	 */
	public function set_title( $title ) {
		$this->set_prop( 'title', $title );
	}

	/**
	 * Set notification description.
	 *
	 * @since 1.4.1
	 *
	 * @param string $description Notification description.
	 */
	public function set_description( $description ) {
		$this->set_prop( 'description', $description );
	}

	/**
	 * Set notification user id.
	 *
	 * @since 1.4.1
	 *
	 * @param string $user_id Notification user id.
	 */
	public function set_user_id( $user_id ) {
		$this->set_prop( 'user_id', absint( $user_id ) );
	}

	/**
	 * Set notification created by.
	 *
	 * @since 1.4.1
	 *
	 * @param string $created_by Notification created by user.
	 */
	public function set_created_by( $created_by ) {
		$this->set_prop( 'created_by', absint( $created_by ) );
	}

	/**
	 * Set notification status.
	 *
	 * @since 1.4.1
	 *
	 * @param string $status Notification status.
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', $status );
	}

	/**
	 * Set notification type.
	 *
	 * @since 1.4.1
	 *
	 * @param string $type Notification type.
	 */
	public function set_type( $type ) {
		$this->set_prop( 'type', $type );
	}

	/**
	 * Set notification created at.
	 *
	 * @since 1.7.1
	 *
	 * @param string $created_at Notification created at.
	 */
	public function set_created_at( $created_at ) {
		$this->set_date_prop( 'created_at', $created_at );
	}

	/**
	 * Set notification modified at.
	 *
	 * @since 1.7.1
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_modified_at( $modified_at ) {
		$this->set_date_prop( 'modified_at', $modified_at );
	}

	/**
	 * Set notification expiry at.
	 *
	 * @since 1.7.1
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_expire_at( $expire_at ) {
		$this->set_date_prop( 'expire_at', $expire_at );
	}

		/**
	 * Set notification expiry at.
	 *
	 * @since 1.7.1
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_topic_url( $topic_url ) {
		$this->set_prop( 'topic_url', $topic_url );
	}

		/**
	 * Set notification expiry at.
	 *
	 * @since 1.7.1
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_post_id( $post_id ) {
		$this->set_prop( 'post_id', $post_id );
	}
}
