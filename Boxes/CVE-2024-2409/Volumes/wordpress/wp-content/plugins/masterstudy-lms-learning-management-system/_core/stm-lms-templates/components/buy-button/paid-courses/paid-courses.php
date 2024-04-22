<?php
/**
 * @var int $post_id
 * @var array $button_classes
 * @var bool $prerequisite_preview
 * @var mixed $only_membership
 */

$price             = get_post_meta( $post_id, 'price', true );
$sale_price        = get_post_meta( $post_id, 'sale_price', true );
$not_saleable      = get_post_meta( $post_id, 'not_single_sale', true );
$not_in_membership = get_post_meta( $post_id, 'not_membership', true );
$points_price      = get_post_meta( $post_id, 'points_price', true );
$show_buttons      = apply_filters( 'stm_lms_pro_show_button', true, $post_id );
$sale_price_active = STM_LMS_Helpers::is_sale_price_active( $post_id );

$subscription_enabled = ( empty( $not_in_membership ) && STM_LMS_Subscriptions::subscription_enabled() && STM_LMS_Course::course_in_plan( $post_id ) );
if ( $subscription_enabled ) {
	$plans_courses = STM_LMS_Course::course_in_plan( $post_id );
}

$dropdown_enabled = ( ! empty( $plans_courses ) && is_user_logged_in() || ! empty( $points_price ) && class_exists( 'STM_LMS_Point_System' ) );

$button_classes = array(
	implode( ' ', $button_classes ),
	( $dropdown_enabled ) ? 'masterstudy-buy-button_dropdown' : '',
);

if ( is_user_logged_in() && ! $only_membership ) {
	$attributes = array(
		'data-purchased-course="' . intval( $post_id ) . '"',
	);
} else {
	$attributes = apply_filters(
		'stm_lms_buy_button_auth',
		array(
			'data-authorization-modal="login"',
		),
		$post_id
	);
}

if ( $show_buttons ) :
	?>
	<div class="<?php echo esc_attr( implode( ' ', $button_classes ) ); ?>">
		<?php
		/* Displaying the payment button for course purchase */
		STM_LMS_Templates::show_lms_template(
			'components/buy-button/paid-courses/buy-course',
			array(
				'attributes'        => $attributes,
				'price'             => 'on' === $not_saleable ? '' : $price,
				'sale_price'        => 'on' === $not_saleable ? '' : $sale_price,
				'sale_price_active' => $sale_price_active,
			)
		);
		if ( $dropdown_enabled ) :
			?>
		<div class="masterstudy-buy-button_plans-dropdown">
			<?php
			/* Displaying the list of membership plans for Paid Memberships Pro payment */
			STM_LMS_Templates::show_lms_template(
				'components/buy-button/paid-courses/membership',
				array(
					'post_id'           => $post_id,
					'attributes'        => $attributes,
					'only_membership'   => $only_membership,
					'not_in_membership' => $not_in_membership,
				)
			);

			/* Displaying the button for the Point System addon */
			do_action( 'masterstudy_point_system', $post_id );
			?>
		</div>
		<?php endif; ?>
	</div>
	<?php
else :
	/* Displaying the button for the Prerequisites addon */
	do_action( 'masterstudy_prerequisite_button', $post_id, $prerequisite_preview );
endif;
