<?php
$price             = get_post_meta( $course_id, 'price', true );
$sale_price        = STM_LMS_Course::get_sale_price( $course_id );
$not_in_membership = get_post_meta( $course_id, 'not_membership', true );
$btn_class         = array( 'btn btn-default btn_big' );

if ( empty( $price ) && ! empty( $sale_price ) ) {
	$price      = $sale_price;
	$sale_price = '';
}

if ( ! empty( $price ) && ! empty( $sale_price ) ) {
	$tmp_price  = $sale_price;
	$sale_price = $price;
	$price      = $tmp_price;
}

if ( ! empty( $sale_price ) || ! empty( $price ) ) {
	$btn_class[] = 'heading_font';
}

if ( is_user_logged_in() ) {
	$attributes = array(
		'data-buy-course="' . intval( $course_id ) . '"',
	);
} else {
	$attributes = array(
		'data-authorization-modal="login"',
	);
}
?>

<a href="#" id="stm_lms_buy_button"
		<?php echo implode( ' ', $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		class="<?php echo esc_attr( implode( ' ', $btn_class ) ); ?>">
	<span>
		<?php esc_html_e( 'Get now', 'masterstudy-lms-learning-management-system-pro' ); ?>
	</span>

	<?php if ( ! empty( $price ) || ! empty( $sale_price ) ) : ?>
		<div class="btn-prices">

			<?php if ( ! empty( $sale_price ) ) : ?>
				<label class="sale_price"><?php echo STM_LMS_Helpers::display_price( $sale_price ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
			<?php endif; ?>

			<?php if ( ! empty( $price ) ) : ?>
				<label class="price"><?php echo STM_LMS_Helpers::display_price( $price ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
			<?php endif; ?>

		</div>
	<?php endif; ?>
</a>
