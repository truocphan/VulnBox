<?php
/**
 * Helper functions for Revenue Sharing.
 *
 * @since 1.6.14
 */

defined( 'ABSPATH' ) || exit;

use Masteriyo\Addons\RevenueSharing\Enums\WithdrawStatus;
use Masteriyo\Enums\OrderStatus;
use Masteriyo\PostType\PostType;


if ( ! function_exists( 'masteriyo_get_earning_store' ) ) {
	/**
	 * Get earning store.
	 *
	 * @since 1.6.14
	 *
	 * @return \Masteriyo\Addons\RevenueSharing\Repository\EarningRepository
	 */
	function masteriyo_get_earning_store() {
		return masteriyo( 'earning.store' );
	}
}

if ( ! function_exists( 'masteriyo_create_earning_object' ) ) {
	/**
	 * Create an instance of Earning class.
	 *
	 * @since 1.6.14
	 *
	 * @return \Masteriyo\Addons\RevenueSharing\Models\Earning
	 */
	function masteriyo_create_earning_object() {
		return masteriyo( 'earning' );
	}
}

if ( ! function_exists( 'masteriyo_get_earning' ) ) {
	/**
	 * Get earning.
	 *
	 * @since 1.6.14
	 *
	 * @param int|\Masteriyo\Addons\RevenueSharing\Models\Earning|\WP_Post $earning ID or model object or WP_Post object.
	 *
	 * @return \Masteriyo\Addons\RevenueSharing\Models\Earning|null
	 */
	function masteriyo_get_earning( $earning ) {
		$earning_obj   = masteriyo_create_earning_object();
		$earning_store = masteriyo_get_earning_store();

		if ( is_a( $earning, \Masteriyo\Addons\RevenueSharing\Models\Earning::class ) ) {
			$id = $earning->get_id();
		} elseif ( is_a( $earning, \WP_Post::class ) ) {
			$id = $earning->ID;
		} else {
			$id = $earning;
		}

		try {
			$id = absint( $id );
			$earning_obj->set_id( $id );
			$earning_store->read( $earning_obj );
		} catch ( \Exception $e ) {
			return null;
		}

		/**
		 * Filters earning object.
		 *
		 * @since 1.6.14
		 *
		 * @param \Masteriyo\Addons\RevenueSharing\Models\Earning $earning_obj The earning object.
		 * @param int|\Masteriyo\Addons\RevenueSharing\Models\Earning|WP_Post $earning ID or model object or WP_Post object.
		 */
		return apply_filters( 'masteriyo_get_earning', $earning_obj, $earning );
	}
}

if ( ! function_exists( 'masteriyo_get_withdraw_store' ) ) {
	/**
	 * Get withdraw store.
	 *
	 * @since 1.6.14
	 *
	 * @return \Masteriyo\Addons\RevenueSharing\Repository\WithdrawRepository
	 */
	function masteriyo_get_withdraw_store() {
		return masteriyo( 'withdraw.store' );
	}
}

if ( ! function_exists( 'masteriyo_create_withdraw_object' ) ) {
	/**
	 * Create an instance of withdraw class.
	 *
	 * @since 1.6.14
	 *
	 * @return \Masteriyo\Addons\RevenueSharing\Models\Withdraw
	 */
	function masteriyo_create_withdraw_object() {
		return masteriyo( 'withdraw' );
	}
}

if ( ! function_exists( 'masteriyo_get_withdraw' ) ) {
	/**
	 * Get withdraw.
	 *
	 * @since 1.6.14
	 *
	 * @param int|\Masteriyo\Addons\RevenueSharing\Models\Withdraw|\WP_Post $withdraw ID or model object or WP_Post object.
	 *
	 * @return \Masteriyo\Addons\RevenueSharing\Models\Withdraw|null
	 */
	function masteriyo_get_withdraw( $withdraw ) {
		$withdraw_obj   = masteriyo_create_withdraw_object();
		$withdraw_store = masteriyo_get_withdraw_store();

		if ( is_a( $withdraw, \Masteriyo\Addons\RevenueSharing\Models\Withdraw::class ) ) {
			$id = $withdraw->get_id();
		} elseif ( is_a( $withdraw, \WP_Post::class ) ) {
			$id = $withdraw->ID;
		} else {
			$id = $withdraw;
		}

		try {
			$id = absint( $id );
			$withdraw_obj->set_id( $id );
			$withdraw_store->read( $withdraw_obj );
		} catch ( \Exception $e ) {
			return null;
		}

		/**
		 * Filters withdraw object.
		 *
		 * @since 1.6.14
		 *
		 * @param \Masteriyo\Addons\RevenueSharing\Models\Withdraw $withdraw_obj The withdraw object.
		 * @param int|\Masteriyo\Addons\RevenueSharing\Models\Withdraw|WP_Post $withdraw ID or model object or WP_Post object.
		 */
		return apply_filters( 'masteriyo_get_withdraw', $withdraw_obj, $withdraw );
	}
}

if ( ! function_exists( 'masteriyo_get_earning_summary' ) ) {
	/**
	 * Get earning summary by user.
	 *
	 * @since 1.6.14
	 *
	 * @param int $user_id User id.
	 * @return array
	 */
	function masteriyo_get_earning_summary( $user_id ) {
		global $wpdb;

		$maturity_period = masteriyo( 'addons.revenue-sharing' )->setting->get( 'withdraw.maturity_period', 7 );
		$earning_summary = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT
					IFNULL(
						SUM(CASE WHEN pm.meta_key = '_withdraw_amount' THEN pm.meta_value END), 0) AS withdrawn_amount,
					GREATEST(
						IFNULL(SUM(CASE WHEN pm.meta_key = '_instructor_amount' THEN pm.meta_value END), 0)
						-
						IFNULL(SUM(CASE WHEN pm.meta_key = '_withdraw_amount' THEN pm.meta_value END), 0), 0
					) AS available_amount,
					GREATEST(
						IFNULL(
							SUM(
								CASE WHEN pm.meta_key = '_instructor_amount' AND p.post_date <= DATE(
									DATE_SUB(NOW(), INTERVAL %d DAY)
								) THEN pm.meta_value END
							),
							0
						)
						-
						IFNULL(SUM(CASE WHEN pm.meta_key = '_withdraw_amount' THEN pm.meta_value END), 0), 0
					) AS withdrawable_amount
				FROM {$wpdb->prefix}postmeta AS pm
				INNER JOIN {$wpdb->prefix}posts AS p ON pm.post_id = p.ID
				WHERE (p.post_type = %s OR p.post_type = %s)
					AND p.post_author = %d
					AND (p.post_status = %s OR p.post_status = %s)",
				$maturity_period,
				PostType::EARNING,
				PostType::WITHDRAW,
				$user_id,
				OrderStatus::COMPLETED,
				WithdrawStatus::APPROVED
			),
			ARRAY_A
		);

		$price_args = array(
			'html'                 => false,
			'show_price_free_text' => false,
		);

		$earning_summary['available_amount_formatted']    = masteriyo_price( $earning_summary['available_amount'], $price_args );
		$earning_summary['withdrawable_amount_formatted'] = masteriyo_price( $earning_summary['withdrawable_amount'], $price_args );
		$earning_summary['withdrawn_amount_formatted']    = masteriyo_price( $earning_summary['withdrawn_amount'], $price_args );

		return $earning_summary;
	}
}
