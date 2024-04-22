<?php
/**
 * @var int $post_id
 * @var array $attributes
 * @var mixed $only_membership
 * @var mixed $not_in_membership
 */

wp_enqueue_style( 'masterstudy-membership-modal', STM_LMS_URL . 'assets/css/deprecated/membership.css', null, MS_LMS_VERSION );

global $pmpro_currency_symbol;

$subscription_enabled = ( empty( $not_in_membership ) && STM_LMS_Subscriptions::subscription_enabled() && STM_LMS_Course::course_in_plan( $post_id ) );
if ( $subscription_enabled ) {
	$plans_courses = STM_LMS_Course::course_in_plan( $post_id );
}

$subs = STM_LMS_Subscriptions::user_subscription_levels();
if ( ! $only_membership ) :
	?>
	<a href="#" <?php echo wp_kses_post( implode( ' ', apply_filters( 'stm_lms_buy_button_auth', $attributes, $post_id ) ) ); ?>>
		<span class="masterstudy-buy-button__title"><?php esc_html_e( 'One Time Payment', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
	</a>
	<?php
endif;

if ( $subscription_enabled && ! empty( $plans_courses ) ) :
	echo '<span class="masterstudy-buy-button_plans-dropdown__title">' . esc_html__( 'Available in this plans:', 'masterstudy-lms-learning-management-system-pro' ) . '</span>';

	$plans_post_ids   = wp_list_pluck( $plans_courses, 'id' );
	$plans_have_quota = false;
	$needs_approval   = false;

	foreach ( $subs as $sub ) {
		if ( ! in_array( $sub->ID, $plans_post_ids, true ) ) {
			continue;
		}

		if ( $sub->course_number > 0 ) {
			$plans_have_quota = true;
			$user_approval    = get_user_meta( get_current_user_id(), 'pmpro_approval_' . $sub->ID, true );

			if ( ! empty( $user_approval['status'] ) && in_array( $user_approval['status'], array( 'pending', 'denied' ), true ) ) {
				$needs_approval = true;
			}
		}
	}

	if ( $plans_have_quota ) :
		$subs_info = array();

		foreach ( $subs as $sub ) {
			if ( ! in_array( $sub->ID, $plans_post_ids, true ) ) {
				continue;
			}

			$subs_info[] = array(
				'id'            => $sub->subscription_id,
				'course_id'     => $post_id,
				'name'          => $sub->name,
				'course_number' => $sub->course_number,
				'used_quotas'   => $sub->used_quotas,
				'quotas_left'   => $sub->quotas_left,
			);
		}
		?>
		<button type="button" data-masterstudy-modal="masterstudy-membership-modal">
			<span class="masterstudy-buy-button__title"><?php esc_html_e( 'Enroll with Membership', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
		</button>
		<?php
		STM_LMS_Templates::show_lms_template(
			'components/modals/membership',
			array(
				'post_id'         => $post_id,
				'membership_list' => $subs_info,
			)
		);
	else :
		$buy_url = STM_LMS_Subscriptions::level_url();

		if ( ! empty( $plans_courses ) ) {
			foreach ( $plans_courses as $plan_course ) :
				$plan_course_limit = get_option( "stm_lms_course_number_{$plan_course->id}", 0 );

				if ( empty( $plan_course_limit ) ) {
					continue;
				}

				stm_lms_register_script( 'buy/plan_cookie', array( 'jquery.cookie' ), true );

				$buy_url = add_query_arg( 'level', $plan_course->id, STM_LMS_Subscriptions::checkout_url() );
				$period  = ( $plan_course->cycle_period ) ? $plan_course->cycle_period : $plan_course->expiration_period;
				?>
			<a href="<?php echo esc_url( $buy_url ); ?>" data-course-id="<?php echo esc_attr( $post_id ); ?>" class="masterstudy-membership">
				<span class="masterstudy-buy-button_plans-dropdown__label"><?php echo esc_html( $plan_course->name ); ?></span>
				<?php if ( '0' !== $plan_course->initial_payment && ! empty( $plan_course->initial_payment ) ) : ?>
				<span class="masterstudy-buy-button_plans-dropdown__price">
					<?php
					echo esc_attr( $pmpro_currency_symbol . $plan_course->initial_payment );
					if ( ! empty( $plan_course->cycle_period ) ) {
						echo '/' . esc_html( $plan_course->cycle_period );
					}
					?>
				</span>
				<?php endif; ?>
			</a>
				<?php
			endforeach;
		}
	endif;
endif;
