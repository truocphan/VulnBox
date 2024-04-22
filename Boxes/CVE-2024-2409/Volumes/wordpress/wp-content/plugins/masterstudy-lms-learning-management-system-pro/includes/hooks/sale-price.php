<?php
add_filter( 'stm_lms_get_sale_price', 'stm_lms_get_sale_price_pro', 10, 2 );

function stm_lms_get_sale_price_pro( $sale_price, $course_id ) {
	$sale_start = get_post_meta( $course_id, 'sale_price_dates_start', true );
	$sale_end   = get_post_meta( $course_id, 'sale_price_dates_end', true );

	if ( empty( intval( $sale_start ) ) && empty( intval( $sale_end ) ) ) {
		return $sale_price;
	}

	$now = time() * 1000;
	return ( $now > $sale_start && $now < $sale_end ) ? $sale_price : 0;
}

add_filter( 'stm_lms_sale_price_meta', 'stm_lms_sale_price_meta_pro', 10, 3 );

function stm_lms_sale_price_meta_pro( $price, $meta, $default_price ) {
	if ( ! isset( $meta['sale_price_dates_start'] ) || ! isset( $meta['sale_price_dates_end'] ) ) {
		return $price;
	}

	if ( empty( intval( $meta['sale_price_dates_start'] ) ) && empty( intval( $meta['sale_price_dates_end'] ) ) ) {
		return $price;
	}

	$sale_start = $meta['sale_price_dates_start'];
	$sale_end   = $meta['sale_price_dates_end'];

	$now = time() * 1000;

	return ( $now > $sale_start && $now < $sale_end ) ? $price : $default_price;
}
