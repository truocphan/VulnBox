<div class="ms_lms_courses_card_item_popup">
	<?php if ( ! ( empty( $popup_data['popup_show_author_image'] ) && empty( $popup_data['popup_show_author_name'] ) ) ) { ?>
		<div class="ms_lms_courses_card_item_popup_author">
			<?php
			if ( ! empty( $popup_data['popup_show_author_image'] ) ) {
				?>
				<img src="<?php echo esc_url( $course['author_info']['avatar_url'] ); ?>">
				<?php
			}
			if ( ! empty( $popup_data['popup_show_author_name'] ) ) {
				?>
				<span class="ms_lms_courses_card_item_popup_author_name"><?php echo esc_html( $course['author_info']['login'] ); ?></span>
			<?php } ?>
		</div>
	<?php } ?>
	<a href="<?php echo esc_url( $course['url'] ); ?>" class="ms_lms_courses_card_item_popup_title">
		<h3><?php echo esc_html( $course['post_title'] ); ?></h3>
	</a>
	<?php if ( ! empty( $popup_data['popup_show_excerpt'] ) && ! empty( $course['post_excerpt'] ) ) { ?>
		<div class="ms_lms_courses_card_item_popup_excerpt">
			<?php echo wp_kses_post( stm_lms_minimize_word( strip_shortcodes( $course['post_excerpt'] ), 130, '...' ) ); ?>
		</div>
		<?php
	}
	if ( ! empty( $popup_data['popup_show_slots'] ) && ! ( 'empty' === $meta_slots['popup_slot_1'] && 'empty' === $meta_slots['popup_slot_2'] && 'empty' === $meta_slots['popup_slot_3'] ) ) {
		?>
		<div class="ms_lms_courses_card_item_popup_meta">
			<?php
			if ( 'empty' !== $meta_slots['popup_slot_1'] ) {
				STM_LMS_Templates::show_lms_template(
					'elementor-widgets/courses/card/global/meta-slot/main',
					array(
						'meta_slot' => $meta_slots['popup_slot_1'],
						'course'    => $course,
					)
				);
			}
			if ( 'empty' !== $meta_slots['popup_slot_2'] ) {
				STM_LMS_Templates::show_lms_template(
					'elementor-widgets/courses/card/global/meta-slot/main',
					array(
						'meta_slot' => $meta_slots['popup_slot_2'],
						'course'    => $course,
					)
				);
			}
			if ( 'empty' !== $meta_slots['popup_slot_3'] ) {
				STM_LMS_Templates::show_lms_template(
					'elementor-widgets/courses/card/global/meta-slot/main',
					array(
						'meta_slot' => $meta_slots['popup_slot_3'],
						'course'    => $course,
					)
				);
			}
			?>
		</div>
	<?php } ?>
	<div class="ms_lms_courses_card_item_popup_button_wrapper">
		<a href="<?php echo esc_url( $course['url'] ); ?>" class="ms_lms_courses_card_item_popup_button">
			<span><?php esc_html_e( 'Preview this course', 'masterstudy-lms-learning-management-system' ); ?></span>
			<?php if ( $course['is_trial'] ) : ?>
			<small><?php esc_html_e( 'Free Lesson(s) Offer', 'masterstudy-lms-learning-management-system' ); ?></small>
			<?php endif; ?>
		</a>

		<?php if ( ( ! empty( $popup_data['popup_show_wishlist'] ) || ! empty( $popup_data['popup_show_price'] ) ) ) : ?>
			<div class="ms_lms_courses_card_item_popup_bottom_wrapper<?php echo esc_attr( ( empty( $popup_data['popup_show_wishlist'] ) && ! empty( $popup_data['popup_show_price'] ) ? ' price-only' : '' ) ); ?>">
				<?php
				if ( ( ! empty( $popup_data['popup_show_wishlist'] ) ) ) {
					if ( ! empty( $popup_data['popup_show_wishlist'] ) ) {
						?>
							<div class="ms_lms_courses_card_item_popup_wishlist">
							<?php STM_LMS_Templates::show_lms_template( 'global/wish-list', array( 'course_id' => $course['id'] ) ); ?>
							</div>
							<?php
					}
				}

				if ( ( ! empty( $popup_data['popup_show_price'] ) ) && ! STM_LMS_Helpers::masterstudy_lms_is_course_coming_soon( $course['id'] ) ) {
					STM_LMS_Templates::show_lms_template(
						'elementor-widgets/courses/card/global/popup-price',
						array(
							'popup_data' => $popup_data,
							'course'     => $course,
						)
					);
				}
				?>
			</div>
		<?php endif; ?>
	</div>
</div>
