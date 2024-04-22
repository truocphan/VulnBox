<?php

/**
 * @var $text
 * @var $link
 */

$price      = get_post_meta( $course_id, 'price', true );
$sale_price = STM_LMS_Course::get_sale_price( $course_id );
$btn_class  = array();

if ( empty( $price ) && ! empty( $sale_price ) ) {
	$price      = $sale_price;
	$sale_price = '';
}

if ( ! empty( $price ) && ! empty( $sale_price ) ) {
	$tmp_price  = $sale_price;
	$sale_price = $price;
	$price      = $tmp_price;
	$saves      = round( 100 - ( $price / $sale_price * 100 ), 2 );
} else {
	$btn_class[] = 'no-price';
}

?>

<div class="stm_lms_udemy__affiliate_btn">

	<?php if ( ! empty( $price ) || ! empty( $sale_price ) ) : ?>
		<div class="btn-prices heading_font">

			<?php if ( ! empty( $price ) ) : ?>
				<label class="price"><?php echo STM_LMS_Helpers::display_price( $price ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
			<?php endif; ?>

			<?php if ( ! empty( $sale_price ) ) : ?>
				<label class="sale_price"><?php echo STM_LMS_Helpers::display_price( $sale_price ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
			<?php endif; ?>

			<?php if ( ! empty( $saves ) ) : ?>
				<label class="saves"><?php /* translators: %s Discount */ printf( esc_html__( '%s%% off', 'masterstudy-lms-learning-management-system-pro' ), esc_html( $saves ) ); ?></label>
			<?php endif; ?>

		</div>
	<?php endif; ?>

	<div class="stm-lms-buy-buttons">
		<a href="<?php echo esc_url( $link ); ?>"
			class="btn btn-default btn_big text-center <?php echo esc_attr( implode( ' ', $btn_class ) ); ?>"
			target="_blank">
			<span><?php echo sanitize_text_field( $text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		</a>
	</div>

</div>
