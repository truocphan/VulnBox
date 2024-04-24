<?php
/**
 * Withdraw model.
 *
 * @since 1.6.14
 *
 * @package Masteriyo\Addons\RevenueSharing\Models
 */

namespace Masteriyo\Addons\RevenueSharing\Models;

use Masteriyo\Addons\RevenueSharing\Repository\WithdrawRepository;
use Masteriyo\Database\Model;
use Masteriyo\PostType\PostType;

defined( 'ABSPATH' ) || exit;

/**
 * Withdraw model class.
 *
 * @since 1.6.14
 */
class Withdraw extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 1.6.14
	 *
	 * @var string
	 */
	protected $object_type = 'withdraw';

	/**
	 * Post type.
	 *
	 * @since 1.6.14
	 *
	 * @var string
	 */
	protected $post_type = PostType::WITHDRAW;

	/**
	 * Cache group.
	 *
	 * @since 1.6.14
	 *
	 * @var string
	 */
	protected $cache_group = 'withdraws';

	/**
	 * Stores withdraw data.
	 *
	 * @since 1.6.14
	 *
	 * @var array
	 */
	protected $data = array(
		'user_id'          => 0,
		'withdraw_amount'  => 0,
		'withdraw_method'  => null,
		'status'           => '',
		'rejection_detail' => null,
		'date_created'     => null,
		'date_modified'    => null,
	);

	/**
	 * Constructor.
	 *
	 * @since 1.6.14
	 *
	 * @param WithdrawRepository $withdraw_repository Withdraw Repository.
	 */
	public function __construct( WithdrawRepository $withdraw_repository ) {
		$this->repository = $withdraw_repository;
	}

	/**
	 * Get post type.
	 *
	 * @param string $context
	 *
	 * @return string
	 */
	public function get_post_type() {
		return $this->post_type;
	}

	/**
	 * Get object type.
	 *
	 * @param string $context
	 *
	 * @return string
	 */
	public function get_object_type() {
		return $this->object_type;
	}


	/**
	 * Get user id.
	 *
	 * @param string $context
	 *
	 * @return int
	 */
	public function get_user_id( $context = 'view' ) {
		return $this->get_prop( 'user_id', $context );
	}

	/**
	 * Get the withdrawer.
	 *
	 * @since 1.6.14
	 *
	 * @return \Masteriyo\Models\User|null
	 */
	public function get_withdrawer() {
		return $this->get_user_id() ? masteriyo_get_user( $this->get_user_id() ) : null;
	}


	/**
	 * Get withdraw amount.
	 *
	 * @param string $context
	 *
	 * @return float
	 */
	public function get_withdraw_amount( $context = 'view' ) {
		return $this->get_prop( 'withdraw_amount', $context );
	}


	/**
	 * Get withdraw method.
	 *
	 * @param string $context
	 *
	 * @return mixed
	 */
	public function get_withdraw_method( $context = 'view' ) {
		return $this->get_prop( 'withdraw_method', $context );
	}


	/**
	 * Get status.
	 *
	 * @param string $context
	 *
	 * @return string
	 */
	public function get_status( $context = 'view' ) {
		return $this->get_prop( 'status', $context );
	}

	/**
	 * Get date modified.
	 *
	 * @param string $context
	 *
	 * @return \Masteriyo\DateTime|null
	 */
	public function get_date_modified( $context = 'view' ) {
		return $this->get_prop( 'date_modified', $context );
	}

	/**
	 * Get withdraw created date.
	 *
	 * @since 1.6.14
	 *
	 * @return \Masteriyo\DateTime|null
	 */
	public function get_date_created( $context = 'view' ) {
		return $this->get_prop( 'date_created', $context );
	}

	/**
	* Get rejection detail.
	*
	* @since 1.6.14
	*
	* @return \Masteriyo\DateTime|null
	*/
	public function get_rejection_detail( $context = 'view' ) {
		return $this->get_prop( 'rejection_detail', $context );
	}

	/**
	 * Set user id.
	 *
	 * @param int $user_id User id.
	 */
	public function set_user_id( $user_id ) {
		$this->set_prop( 'user_id', absint( $user_id ) );
	}

	/**
	 * Set user info.
	 *
	 * @param string $user_info User info.
	 */
	public function set_user_info( $user_info ) {
		$this->set_prop( 'user_info', $user_info );
	}

	/**
	 * Set withdraw amount.
	 *
	 * @param float $amount
	 */
	public function set_withdraw_amount( $withdraw_amount ) {
		$this->set_prop( 'withdraw_amount', masteriyo_format_decimal( $withdraw_amount ) );
	}

	/**
	 * Set withdraw method.
	 *
	 * @param mixed $withdraw_method Withdraw method.
	 */
	public function set_withdraw_method( $withdraw_method ) {
		$this->set_prop( 'withdraw_method', $withdraw_method );
	}

	/**
	 * Set status.
	 *
	 * @param string $status Status.
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', $status );
	}

	/**
	 * Set date modified.
	 *
	 * @param string|int $date Date.
	 */
	public function set_date_modified( $date = null ) {
		$this->set_date_prop( 'date_modified', $date );
	}

	/**
	 * Set date created.
	 *
	 * @param string|int $date Date.
	 */
	public function set_date_created( $date = null ) {
		$this->set_date_prop( 'date_created', $date );
	}

	/**
	 * Set rejection detail.
	 *
	 * @param mixed $detail Detail.
	 */
	public function set_rejection_detail( $detail ) {
		$this->set_prop( 'rejection_detail', $detail );
	}
}
