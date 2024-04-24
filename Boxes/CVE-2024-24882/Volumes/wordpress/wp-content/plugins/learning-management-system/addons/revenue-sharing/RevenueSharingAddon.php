<?php
/**
 * Revenue sharing addon.
 *
 * @since 1.6.14
 *
 * @package Masteriyo\Addons\RevenueSharing
 */

namespace Masteriyo\Addons\RevenueSharing;

use Masteriyo\Addons\RevenueSharing\PostType\Earning;
use Masteriyo\Addons\RevenueSharing\PostType\Withdraw;
use Masteriyo\Addons\RevenueSharing\Controllers\WithdrawsController;
use Masteriyo\Addons\RevenueSharing\Query\EarningQuery;
use Masteriyo\PostType\PostType;
use Masteriyo\Pro\Addons;

defined( 'ABSPATH' ) || exit;

/**
 * Revenue sharing addon class.
 *
 * @since 1.6.14
 */
class RevenueSharingAddon {

	/**
	 * Setting object.
	 *
	 * @since 1.6.14
	 *
	 * @var Setting
	 */
	public $setting = null;

	/**
	 * Constructor.
	 *
	 * @since 1.6.14
	 *
	 * @param Setting $setting Setting object.
	 */
	public function __construct( Setting $setting ) {
		$this->setting = $setting;
	}

	/**
	 * Init addon.
	 *
	 * @since 1.6.14
	 */
	public function init() {
		$this->setting->init();
		$this->init_hooks();
	}

	/**
	 * Init hooks.
	 *
	 * @since 1.6.14
	 */
	public function init_hooks() {
		add_filter( 'masteriyo_rest_api_get_rest_namespaces', array( $this, 'register_rest_namespaces' ) );
		add_filter( 'masteriyo_register_post_types', array( $this, 'register_post_types' ) );

		if ( $this->is_revenue_sharing_enabled() ) {
			add_filter( 'masteriyo_admin_submenus', array( $this, 'add_withdraw_submenu' ) );
			add_action( 'masteriyo_new_order', array( $this, 'create_earning' ), 10, 2 );
			add_action( 'masteriyo_order_status_changed', array( $this, 'update_earnings' ), 10, 3 );
			add_filter( 'masteriyo_localized_public_scripts', array( $this, 'localize_public_scripts' ) );
			add_filter( 'masteriyo_rest_pre_insert_user_object', array( $this, 'save_user_withdraw_data' ), 10, 3 );
			add_filter( 'masteriyo_rest_response_user_data', array( $this, 'append_withdraw_data_to_response' ), 10, 2 );
			add_filter( 'masteriyo_localized_admin_scripts', array( $this, 'localize_admin_scripts' ) );
		}
	}

	/**
	 * Localize admin scripts.
	 *
	 * @since 1.6.14
	 * @param array $scripts Scripts.
	 * @return array
	 */
	public function localize_admin_scripts( $scripts ) {
		$scripts['backend']['data']['can_manage_withdraws'] = masteriyo_bool_to_string( current_user_can( 'manage_withdraws' ) );
		return $scripts;
	}

	/**
	 * Is revenue sharing enabled.
	 *
	 * @since 1.6.14
	 * @return bool
	 */
	protected function is_revenue_sharing_enabled() {
		return $this->setting->get( 'enable', false );
	}

	/**
	 * Add public script data.
	 *
	 * @since 1.6.14
	 *
	 * @param array $script_data Script data.
	 * @return array
	 */
	public function localize_public_scripts( $script_data ) {
		$script_data['account']['data']['withdraw_methods']           = $this->setting->get( 'withdraw.methods' );
		$script_data['account']['data']['is_revenue_sharing_enabled'] = masteriyo_bool_to_string(
			( new Addons() )->is_active( 'revenue-sharing' ) && $this->is_revenue_sharing_enabled()
		);

		return $script_data;
	}

	/**
	 * Register namespaces.
	 *
	 * @since 1.6.14
	 *
	 * @param array $namespaces Rest namespaces.
	 * @return array
	 */
	public function register_rest_namespaces( $namespaces ) {
		$namespaces['masteriyo/v1']['withdraws'] = WithdrawsController::class;
		return $namespaces;
	}

	/**
	 * Add withdraw submenu.
	 *
	 * @since 1.6.14
	 *
	 * @param array $submenus Submenus.
	 */
	public function add_withdraw_submenu( $submenus ) {
		$submenus['withdraws'] = array(
			'page_title' => __( 'Withdraws', 'masteriyo' ),
			'menu_title' => __( 'Withdraws', 'masteriyo' ),
			'capability' => 'manage_withdraws',
			'position'   => 72,
		);

		return $submenus;
	}

	/**
	 * Register post types.
	 *
	 * @since 1.6.14
	 *
	 * @param array $post_types
	 * @return array
	 */
	public function register_post_types( $post_types ) {
		$post_types['earning']  = Earning::class;
		$post_types['withdraw'] = Withdraw::class;

		return $post_types;
	}

	/**
	 * Create earning.
	 *
	 * @param int $order_id Order ID.
	 * @param \Masteriyo\Models\Order\Order $order Order object.
	 */
	public function create_earning( $order_id, $order ) {
		$course = current( $order->get_items() )->get_course();

		if ( ! $course ) {
			return;
		}

		$earning = masteriyo_create_earning_object();

		$earning->set_status( $order->get_status() );
		$earning->set_course_id( $course->get_id() );
		$earning->set_order_id( $order_id );
		$earning->set_user_id( $course->get_author_id() );

		$admin_rate            = $this->setting->get( 'admin_rate' );
		$instructor_rate       = $this->setting->get( 'instructor_rate' );
		$deductible_fee        = $this->setting->get( 'deductible_fee.enable' );
		$deductible_fee_type   = $this->setting->get( 'deductible_fee.type' );
		$deductible_fee_amount = $this->setting->get( 'deductible_fee.amount', 0 );
		$deductible_fee_name   = $this->setting->get( 'deductible_fee.name' );

		$earning->set_deductible_fee_type( $deductible_fee_type );
		$earning->set_admin_rate( $admin_rate );
		$earning->set_instructor_rate( $instructor_rate );

		$total = floatval( $order->get_total() );

		if ( $deductible_fee ) {
			$earning->set_deductible_fee_name( $deductible_fee_name );
			$earning->set_deductible_fee_type( $deductible_fee_type );

			if ( $deductible_fee_amount ) {
				$earning->set_deductible_fee_amount( $deductible_fee_amount );
				$deductible_fee_amount = 'percentage' === $deductible_fee_type ? ( $total * ( $deductible_fee_amount / 100 ) ) : $deductible_fee_amount;
			}
		}

		$total_after_fee_deduction = $total - $deductible_fee_amount;

		$earning->set_grand_total_amount( $total );
		$earning->set_total_amount( $total_after_fee_deduction );
		$earning->set_deductible_fee_amount( $deductible_fee_amount );
		$earning->set_admin_amount( $total_after_fee_deduction * ( $admin_rate / 100 ) );
		$earning->set_instructor_amount( $total_after_fee_deduction * ( $instructor_rate / 100 ) );

		$earning->save();
	}

	/**
	 * Update earnings.
	 *
	 * @since 1.6.14
	 *
	 * @param int $order_id Order ID.
	 * @param string $old_status Old status.
	 * @param string $new_status New status.
	 */
	public function update_earnings( $order_id, $old_status, $new_status ) {
		$earnings = ( new EarningQuery(
			array(
				'post_type'   => PostType::EARNING,
				'post_status' => $old_status,
				'per_page'    => 1,
				'order_id'    => $order_id,
			)
		) )->get_earnings();

		if ( ! $earnings ) {
			return;
		}

		$earning = current( $earnings );

		$earning->set_status( $new_status );
		$earning->save();
	}

	/**
	 * @param \Masteriyo\Database\Model $user User object.
	 * @param \WP_REST_Request $request  Request object.
	 * @param bool $creating If is creating a new object.
	 */
	public function save_user_withdraw_data( $user, $request, $creating ) {
		if ( isset( $request['withdraw_method_preference'] ) ) {
			update_user_meta( $user->get_id(), '_withdraw_method_preference', $request['withdraw_method_preference'] );
		}
		return $user;
	}

	/**
	 * Append withdraw data to response.
	 *
	 * @since 1.6.14
	 *
	 * @param array $response The response object.
	 * @param \Masteriyo\Models\User $user User object .
	 */
	public function append_withdraw_data_to_response( $data, $user ) {
		$withdraw              = get_user_meta( $user->get_id(), '_withdraw_method_preference', true );
		$min_withdrawal_amount = $this->setting->get( 'withdraw.min_amount', 0 );

		$data['revenue_sharing'] = array(
			'withdraw_method_preference'        => $withdraw ? $withdraw : array(),
			'minimum_withdraw_amount'           => $min_withdrawal_amount,
			'minimum_withdraw_amount_formatted' => masteriyo_price(
				$min_withdrawal_amount,
				array(
					'html'                 => false,
					'show_price_free_text' => false,
				)
			),
		);

		$data['revenue_sharing'] += masteriyo_get_earning_summary( $user->get_id() );

		return $data;
	}
}
