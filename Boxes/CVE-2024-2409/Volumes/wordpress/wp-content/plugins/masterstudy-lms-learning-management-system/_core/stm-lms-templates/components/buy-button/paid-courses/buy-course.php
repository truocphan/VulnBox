<?php
/**
 * @var array $attributes
 * @var int $price
 * @var int $sale_price
 * @var bool $sale_price_active
 */
?>
<a href="#" <?php echo wp_kses_post( implode( ' ', $attributes ) ); ?>>
	<span class="masterstudy-buy-button__title"><?php esc_html_e( 'Get course', 'masterstudy-lms-learning-management-system' ); ?></span>
	<?php if ( ! empty( $price ) || ! empty( $sale_price ) ) : ?>
		<span class="masterstudy-buy-button__separator"></span>
		<span class="masterstudy-buy-button__price<?php echo ( ! empty( $sale_price ) && ! empty( $sale_price_active ) ) ? ' has_sale' : ''; ?>">
		<?php if ( ! empty( $sale_price ) && ! empty( $sale_price_active ) ) : ?>
			<span class="masterstudy-buy-button__price_sale"><?php echo wp_kses_post( STM_LMS_Helpers::display_price( $sale_price ) ); ?></span>
			<?php
		endif;
		if ( ! empty( $price ) ) :
			?>
			<span class="masterstudy-buy-button__price_regular"><?php echo wp_kses_post( STM_LMS_Helpers::display_price( $price ) ); ?></span>
		<?php endif; ?>
	</span>
	<?php endif; ?>
</a>
