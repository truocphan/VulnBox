<?php
/**
 * This ongoing trait will have shared calculation logic between Totals classes.
 *
 * @package Masteriyo\Traits
 * @version 1.0.0
 */

namespace Masteriyo\Traits;

defined( 'ABSPATH' ) || exit;

/**
 * Trait ItemTotals.
 *
 * Right now this do not have much, but plan is to eventually move all shared calculation logic between Orders and Cart in this file.
 *
 * @since 1.0.0
 */
trait ItemTotals {

	/**
	 * Line items to calculate. Define in child class.
	 *
	 * @since 1.0.0
	 * @param string $field Field name to calculate upon.
	 *
	 * @return array having `total`|`subtotal` property.
	 */
	abstract protected function get_values_for_total( $field );

	/**
	 * Return rounded total based on settings. Will be used by Cart and Orders.
	 *
	 * @since 1.0.0
	 *
	 * @param array $values Values to round. Should be with precision.
	 *
	 * @return float|int Appropriately rounded value.
	 */
	public static function get_rounded_items_total( $values ) {
		return array_sum(
			array_map(
				array( self::class, 'round_item_subtotal' ),
				$values
			)
		);
	}

	/**
	 * Apply rounding to item subtotal before summing.
	 *
	 * @since 1.0.0
	 * @param float $value Item subtotal value.
	 * @return float
	 */
	public static function round_item_subtotal( $value ) {
		if ( ! self::round_at_subtotal() ) {
			$value = masteriyo_round( $value );
		}
		return $value;
	}

	/**
	 * Should always round at subtotal?
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	protected static function round_at_subtotal() {
		return 'yes' === get_option( 'masteriyo_tax_round_at_subtotal' );
	}
}
