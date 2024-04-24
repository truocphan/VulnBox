<?php
/**
 * Earning model.
 *
 * @since 1.6.14
 *
 * @package Masteriyo\Addons\RevenueSharing\Models
 */

namespace Masteriyo\Addons\RevenueSharing\Models;

use Masteriyo\Addons\RevenueSharing\Repository\EarningRepository;
use Masteriyo\Database\Model;

defined( 'ABSPATH' ) || exit;

/**
 * Earning model class.
 *
 * @since 1.6.14
 */
class Earning extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 1.6.14
	 *
	 * @var string
	 */
	protected $object_type = 'earning';

	/**
	 * Post type.
	 *
	 * @since 1.6.14
	 *
	 * @var string
	 */
	protected $post_type = 'mto-earning';

	/**
	 * Cache group.
	 *
	 * @since 1.6.14
	 *
	 * @var string
	 */
	protected $cache_group = 'earnings';

	/**
	 * Stores earning data.
	 *
	 * @since 1.6.14
	 *
	 * @var array
	 */
	protected $data = array(
		'user_id'               => 0,
		'course_id'             => 0,
		'order_id'              => 0,
		'status'                => '',
		'total_amount'          => 0,
		'grand_total_amount'    => 0,
		'instructor_rate'       => 70,
		'admin_rate'            => 30,
		'admin_amount'          => 0,
		'instructor_amount'     => 0,
		'date_created'          => null,
		'commission_type'       => 'percentage',
		'deductible_fee_type'   => 'percentage',
		'deductible_fee_amount' => 0,
		'deductible_fee_name'   => '',
	);

	/**
	 * Constructor.
	 *
	 * @since 1.6.14
	 *
	 * @param EarningRepository $course_repository Course Repository,
	 */
	public function __construct( EarningRepository $earning_repository ) {
		$this->repository = $earning_repository;
	}

	/**
	 * Get post type.
	 *
	 * @since 1.6.14
	 *
	 * @return string
	 */
	public function get_post_type() {
		return $this->post_type;
	}

	/**
	 * Get object type.
	 *
	 * @since 1.6.14
	 *
	 * @return string
	 */
	public function get_object_type() {
		return $this->object_type;
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
	 * Get user id.
	 *
	 * @param string $context View or Edit.
	 * @return int
	 */
	public function get_user_id( $context = 'view' ) {
		return $this->get_prop( 'user_id', $context );
	}

	/**
	 * Get course id.
	 *
	 * @param string $context View or Edit.
	 * @return int
	 */
	public function get_course_id( $context = 'view' ) {
		return $this->get_prop( 'course_id', $context );
	}

	/**
	 * Get order id.
	 *
	 * @since 1.6.14
	 *
	 * @param string $context View or Edit.
	 * @return int
	 */
	public function get_order_id( $context = 'view' ) {
		return $this->get_prop( 'order_id', $context );
	}

	/**
	 * Get course price.
	 *
	 * @since 1.6.14
	 *
	 * @param string $context View or Edit.
	 * @return float
	 */
	public function get_total_amount( $context = 'view' ) {
		return $this->get_prop( 'total_amount', $context );
	}

	/**
	 * Get course total price before fee deduction.
	 *
	 * @since 1.6.14
	 *
	 * @param string $context View or Edit.
	 * @return float
	 */
	public function grand_total_amount( $context = 'view' ) {
		return $this->get_prop( 'grand_total_amount', $context );
	}

	/**
	 * Get commission type.
	 *
	 * @since 1.6.14
	 *
	 * @param string $context View or Edit.
	 * @return string
	 */
	public function get_commission_type( $context = 'view' ) {
		return $this->get_prop( 'commission_type', $context );
	}

	/**
	 * Get instructor rate.
	 *
	 * @since 1.6.14
	 *
	 * @param string $context View or Edit.
	 * @return float
	 */
	public function get_instructor_rate( $context = 'view' ) {
		return $this->get_prop( 'instructor_rate', $context );
	}

	/**
	 * Get instructor amount.
	 *
	 * @since 1.6.14
	 *
	 * @param string $context View or Edit.
	 * @return float
	 */
	public function get_instructor_amount( $context = 'view' ) {
		return $this->get_prop( 'instructor_amount', $context );
	}


	/**
	 * Get admin rate.
	 *
	 * @since 1.6.14
	 *
	 * @param string $context View or Edit.
	 * @return float
	 */
	public function get_admin_rate( $context = 'view' ) {
		return $this->get_prop( 'admin_rate', $context );
	}

	/**
	 * Get admin amount.
	 *
	 * @since 1.6.14
	 *
	 * @param string $context View or Edit.
	 * @return float
	 */
	public function get_admin_amount( $context = 'view' ) {
		return $this->get_prop( 'admin_amount', $context );
	}

	/**
	 * Get deductible fee type.
	 *
	 * @since 1.6.14
	 *
	 * @param string $context View or Edit.
	 * @return float
	 */
	public function get_deductible_fee_type( $context = 'view' ) {
		return $this->get_prop( 'deductible_fee_type', $context );
	}

	/**
	 * Get deductible fee type.
	 *
	 * @since 1.6.14
	 *
	 * @param string $context View or Edit.
	 * @return string
	 */
	public function get_deductible_fee_amount( $context = 'view' ) {
		return $this->get_prop( 'deductible_fee_amount', $context );
	}

	/**
	 * Get deductible fee name.
	 *
	 * @since 1.6.14
	 *
	 * @param string $context View or Edit.
	 * @return string
	 */
	public function get_deductible_fee_name( $context = 'view' ) {
		return $this->get_prop( 'deductible_fee_name', $context );
	}

	/**
	 * Set earning created date.
	 *
	 * @since 1.6.14
	 *
	 * @param string $context View or Edit.
	 * @return \Masteriyo\DateTime|null
	 */
	public function get_date_created( $context = 'view' ) {
		return $this->get_prop( 'date_created', $context );
	}

	/**
	 * Set user id.
	 *
	 * @since 1.6.14
	 *
	 * @param int $user_id User id.
	 */
	public function set_user_id( $user_id ) {
		$this->set_prop( 'user_id', absint( $user_id ) );
	}

	/**
	 * Set course id.
	 *
	 * @since 1.6.14
	 *
	 * @param int $course_id Course id.
	 */
	public function set_course_id( $course_id ) {
		$this->set_prop( 'course_id', absint( $course_id ) );
	}

	/**
	 * Set order id.
	 *
	 * @since 1.6.14
	 *
	 * @param int $order_id Order id.
	 */
	public function set_order_id( $order_id ) {
		$this->set_prop( 'order_id', absint( $order_id ) );
	}

	/**
	 * Set total amount after fee deduction.
	 *
	 * @since 1.6.14
	 *
	 * @param float $total_amount Course price after fee deduction.
	 */
	public function set_total_amount( $total_amount ) {
		$this->set_prop( 'total_amount', masteriyo_format_decimal( $total_amount ) );
	}

	/**
	 * Set grand total amount without fee deduction.
	 *
	 * @since 1.6.14
	 *
	 * @param float $grand_total_amount Course total price without fee deduction.
	 */
	public function set_grand_total_amount( $grand_total_amount ) {
		$this->set_prop( 'grand_total_amount', masteriyo_format_decimal( $grand_total_amount ) );
	}

	/**
	 * Set commission type.
	 *
	 * @since 1.6.14
	 *
	 * @param string $commission_type Commission type.
	 */
	public function set_commission_type( $commission_type ) {
		$this->set_prop( 'commission_type', $commission_type );
	}

	/**
	 * Set instructor rate.
	 *
	 * @since 1.6.14
	 *
	 * @param float $instructor_rate Instructor rate.
	 */
	public function set_instructor_rate( $instructor_rate ) {
		$this->set_prop( 'instructor_rate', masteriyo_format_decimal( $instructor_rate ) );
	}

	/**
	 * Set admin rate.
	 *
	 * @since 1.6.14
	 *
	 * @param float $admin_rate Admin rate.
	 */
	public function set_admin_rate( $admin_rate ) {
		$this->set_prop( 'admin_rate', masteriyo_format_decimal( $admin_rate ) );
	}

	/**
	 * Set admin amount.
	 *
	 * @since 1.6.14
	 *
	 * @param float $admin_amount Admin amount.
	 */
	public function set_admin_amount( $admin_amount ) {
		$this->set_prop( 'admin_amount', masteriyo_format_decimal( $admin_amount ) );
	}

	/**
	 * Set instructor amount.
	 *
	 * @since 1.6.14
	 *
	 * @param float $instructor_amount Admin amount.
	 */
	public function set_instructor_amount( $instructor_amount ) {
		$this->set_prop( 'instructor_amount', masteriyo_format_decimal( $instructor_amount ) );
	}

	/**
	 * Set deductible fee type.
	 *
	 * @since 1.6.14
	 *
	 * @param string $deductible_fee_type Deductible fee type.
	 */
	public function set_deductible_fee_type( $deductible_fee_type ) {
		$this->set_prop( 'deductible_fee_type', $deductible_fee_type );
	}

	/**
	 * Set deductible fee amount.
	 *
	 * @since 1.6.14
	 *
	 * @param float $deductible_fee_amount Deductible fee amount.
	 */
	public function set_deductible_fee_amount( $deductible_fee_amount ) {
		$this->set_prop( 'deductible_fee_amount', masteriyo_format_decimal( $deductible_fee_amount ) );
	}

	/**
	 * Set deductible fee name.
	 *
	 * @since 1.6.14
	 *
	 * @param string $deductible_fee_name Deductible fee name.
	 */
	public function set_deductible_fee_name( $deductible_fee_name ) {
		$this->set_prop( 'deductible_fee_name', $deductible_fee_name );
	}

	/**
	 * Set date created.
	 *
	 * @since 1.6.14
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_created( $date = null ) {
		$this->set_date_prop( 'date_created', $date );
	}

	/**
	 * Set status.
	 *
	 * @param string $status Status.
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', $status );
	}
}
