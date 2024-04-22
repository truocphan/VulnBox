<?php
/**
 * @var $course_id
 */
stm_lms_register_script( 'buy-button' );

$has_course      = STM_LMS_User::has_course_access( $course_id );
$course_price    = STM_LMS_Course::get_course_price( $course_id );
$only_membership = get_post_meta( $course_id, 'not_single_sale', true );
?>

<div class="stm-lms-buy-buttons">
	<?php if ( $has_course || ( empty( $course_price ) && 'on' !== $only_membership ) ) : ?>

		<?php
		$user = STM_LMS_User::get_current_user();
		if ( empty( $user['id'] ) ) :
			?>
			<a href="#"
			class="btn btn-default"
			data-authorization-modal="login"
			id="stm_lms_buy_button">
				<span><?php esc_html_e( 'Enroll course', 'masterstudy-lms-learning-management-system' ); ?></span>
			</a>
			<?php
		else :
			$user_id        = $user['id'];
			$course         = STM_LMS_Helpers::simplify_db_array( stm_lms_get_user_course( $user_id, $course_id, array( 'current_lesson_id' ) ) );
			$current_lesson = ( ! empty( $course['current_lesson_id'] ) ) ? $course['current_lesson_id'] : '';
			$lesson_url     = STM_LMS_Course::item_url( $course_id, $current_lesson );
			?>
			<a href="<?php echo esc_url( $lesson_url ); ?>" class="btn btn-default">
				<span><?php esc_html_e( 'Start course', 'masterstudy-lms-learning-management-system' ); ?></span>
			</a>

		<?php endif; ?>

		<?php
	else :
		$price             = get_post_meta( $course_id, 'price', true );
		$sale_price        = STM_LMS_Course::get_sale_price( $course_id );
		$not_in_membership = get_post_meta( $course_id, 'not_membership', true );
		$btn_class         = array( 'btn btn-default' );
		$not_salebale      = get_post_meta( $course_id, 'not_single_sale', true );

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
			$btn_class[] = 'btn_big heading_font';
		}

		if ( is_user_logged_in() ) {
			$attributes = array(
				'data-buy-course="' . intval( $course_id ) . '"',
			);
		} else {
			$attributes = apply_filters(
				'stm_lms_buy_button_auth',
				array(
					'data-authorization-modal="login"',
				),
				$course_id
			);
		}

		if ( ! $not_salebale ) {
			?>
			<a href="#" id="stm_lms_buy_button" <?php echo wp_kses_post( implode( ' ', $attributes ) ); ?>
			class="<?php echo esc_attr( implode( ' ', $btn_class ) ); ?>">
				<span>
					<?php esc_html_e( 'Get course', 'masterstudy-lms-learning-management-system' ); ?>
				</span>

				<?php if ( ! empty( $price ) || ! empty( $sale_price ) ) : ?>
					<div class="btn-prices">
						<?php if ( ! empty( $sale_price ) ) : ?>
							<label class="sale_price"><?php echo wp_kses_post( STM_LMS_Helpers::display_price( $sale_price ) ); ?></label>
						<?php endif; ?>

						<?php if ( ! empty( $price ) ) : ?>
							<label class="price"><?php echo wp_kses_post( STM_LMS_Helpers::display_price( $price ) ); ?></label>
						<?php endif; ?>

					</div>
				<?php endif; ?>
			</a>
		<?php } ?>
		<?php
		if ( empty( $not_in_membership ) && STM_LMS_Subscriptions::subscription_enabled() && STM_LMS_Course::course_in_plan( $course_id ) ) :
			stm_lms_register_style( 'membership' );
			$sub = STM_LMS_Subscriptions::user_subscriptions();
			if ( ! empty( $sub->course_number ) ) :
				$sub->course_id = get_the_ID();

				$sub_info = array(
					'course_id'     => get_the_ID(),
					'name'          => $sub->name,
					'course_number' => $sub->course_number,
					'used_quotas'   => $sub->used_quotas,
					'quotas_left'   => $sub->quotas_left,
				);

				if ( ! $not_salebale ) {
					?>
				<span class="or heading_font"><?php esc_html_e( '- Or -', 'masterstudy-lms-learning-management-system' ); ?></span>
				<?php } ?>
				<button type="button"
						id="stm_lms_buy_button_subscription"
						data-lms-params='<?php echo wp_json_encode( $sub_info ); ?>'
						class="btn btn-default btn-outline"
						data-target=".stm-lms-use-subscription"
						data-lms-modal="use_subscription">
					<span><?php esc_html_e( 'Enroll with Membership', 'masterstudy-lms-learning-management-system' ); ?></span>
				</button>
				<?php
				else :
					if ( ! $not_salebale ) {
						?>
						<span class="or heading_font"><?php esc_html_e( '- Or -', 'masterstudy-lms-learning-management-system' ); ?></span>
					<?php } ?>
				<a href="<?php echo esc_url( STM_LMS_Subscriptions::level_url() ); ?>"
				id="stm_lms_buy_button_subscription"
				class="btn btn-default btn-subscription btn-outline">
					<span><?php esc_html_e( 'Enroll with Membership', 'masterstudy-lms-learning-management-system' ); ?></span>
				</a>
			<?php endif; ?>
		<?php endif; ?>

	<?php endif; ?>

</div>
