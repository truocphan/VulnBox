<?php

namespace MasterStudy\Lms\Repositories;

final class PricingRepository {
	private const CASTS = array(
		'single_sale'            => 'bool',
		'price'                  => 'numeric',
		'sale_price'             => 'numeric',
		'points_price'           => 'numeric',
		'enterprise_price'       => 'numeric',
		'not_membership'         => 'bool',
		'affiliate_course'       => 'bool',
		'sale_price_dates_start' => 'numeric',
		'sale_price_dates_end'   => 'numeric',
		'price_info'             => 'string',
	);

	private const DEFAULTS = array(
		'single_sale'            => false,
		'price'                  => null,
		'sale_price'             => null,
		'sale_price_dates_start' => null,
		'sale_price_dates_end'   => null,
		'points_price'           => null,
		'enterprise_price'       => null,
		'not_membership'         => true,
		'affiliate_course'       => false,
		'affiliate_course_text'  => null,
		'affiliate_course_link'  => null,
		'price_info'             => '',
	);

	public function get( int $course_id ): array {
		$meta = get_post_meta( $course_id );

		$pricing = self::DEFAULTS;

		foreach ( $pricing as $key => $default ) {
			$pricing[ $key ] = $this->cast( $key, $meta[ $key ][0] ?? $default );
		}

		return $pricing;
	}

	public function save( int $course_id, array $pricing ): void {
		foreach ( $pricing as $key => $value ) {
			if ( isset( self::CASTS[ $key ] ) && 'bool' === self::CASTS[ $key ] ) {
				$value = $value ? 'on' : '';
			} else {
				$value = (string) $value;
			}

			update_post_meta( $course_id, $key, $value );
		}

		// todo: Remove all dependencies from "not_single_sale" during refactoring
		update_post_meta( $course_id, 'not_single_sale', $pricing['single_sale'] ? '' : 'on' );
		do_action( 'masterstudy_lms_course_price_updated', $course_id );
	}

	private function cast( $key, $value ) {
		if ( null === $value ) {
			return null;
		}

		$type = self::CASTS[ $key ] ?? '';
		switch ( $type ) {
			case 'bool':
				return 'on' === $value;
			case 'numeric':
				return '' !== $value ? (float) $value : null;
			case 'string':
				return $value;
			default:
				return '' !== $value ? $value : null;
		}
	}
}
