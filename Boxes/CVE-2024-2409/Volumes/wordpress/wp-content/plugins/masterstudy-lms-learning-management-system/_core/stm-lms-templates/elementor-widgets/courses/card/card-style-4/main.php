<?php
foreach ( $courses as $course ) {
	$course = STM_LMS_Courses::get_course_submetas( $course, $course_image_size );
	?>
	<div class="ms_lms_courses_card_item <?php echo esc_attr( $featured ?? '' ); ?> <?php echo esc_attr( ( 'courses-carousel' === $widget_type ) ? 'swiper-slide' : '' ); ?>">
		<div class="ms_lms_courses_card_item_wrapper">
			<?php if ( ! empty( $course['featured'] ) ) { ?>
				<div class="ms_lms_courses_card_item_featured <?php echo esc_attr( ( ! empty( $card_data['featured_position'] ) ) ? $card_data['featured_position'] : '' ); ?>">
					<span><?php echo esc_html__( 'Featured', 'masterstudy-lms-learning-management-system' ); ?></span>
				</div>
				<?php
			}
			if ( ! empty( $course['current_status'] ) ) {
				?>
				<div class="ms_lms_courses_card_item_status <?php echo esc_attr( ( ! empty( $card_data['status_presets'] ) ) ? $card_data['status_presets'] : '' ); ?> <?php echo esc_attr( ( ! empty( $card_data['status_position'] ) ) ? $card_data['status_position'] : '' ); ?> <?php echo esc_attr( ( ! empty( $course['current_status']['status'] ) ) ? $course['current_status']['status'] : '' ); ?>">
					<span><?php echo esc_html( $course['current_status']['label'] ); ?></span>
				</div>
			<?php } ?>
			<img src="<?php echo esc_url( get_the_post_thumbnail_url( $course['id'], 'img-540-700' ) ); ?>" class="ms_lms_courses_card_item_image">
			<div class="ms_lms_courses_card_item_info">
				<?php if ( ! empty( $card_data['show_category'] ) && ! empty( $course['terms'] ) ) { ?>
					<span class="ms_lms_courses_card_item_info_category">
						<a href="<?php echo esc_url( STM_LMS_Course::courses_page_url() . '?terms[]=' . $course['terms']->term_id . '&category[]=' . $course['terms']->term_id ); ?>">
							<?php echo esc_html( $course['terms']->name ); ?>
						</a>
					</span>
				<?php } ?>
					<a href="<?php echo esc_url( $course['url'] ); ?>" class="ms_lms_courses_card_item_info_title">
						<h3><?php echo esc_html( $course['post_title'] ); ?></h3>
					</a>
				<?php
				if ( ! empty( $card_data['show_progress'] && $course['progress'] > 0 ) ) {
					STM_LMS_Templates::show_lms_template(
						'elementor-widgets/courses/card/global/progress-bar',
						array(
							'course'    => $course,
							'card_data' => $card_data,
						)
					);
				}
				if ( ! empty( $card_data['show_price'] ) ) {
					STM_LMS_Templates::show_lms_template(
						"elementor-widgets/courses/card/{$course_card_presets}/price",
						array(
							'card_data' => $card_data,
							'course'    => $course,
						)
					);
				}
				?>
				<div class="ms_lms_courses_card_item_info_wrapper">
					<?php
					if ( ! empty( $card_data['show_excerpt'] ) && ! empty( $course['post_excerpt'] ) ) {
						?>
						<div class="ms_lms_courses_card_item_info_excerpt">
							<?php echo wp_kses_post( stm_lms_minimize_word( strip_shortcodes( $course['post_excerpt'] ), 95, '...' ) ); ?>
						</div>
						<?php
					}
					if ( ! empty( $card_data['show_rating'] ) || ! empty( $card_data['show_wishlist'] ) ) {
						?>
						<div class="ms_lms_courses_card_item_info_rating_wrapper">
							<?php
							if ( ! empty( $card_data['show_rating'] ) ) {
								STM_LMS_Templates::show_lms_template(
									'elementor-widgets/courses/card/global/rating',
									array(
										'card_data' => $card_data,
										'course'    => $course,
									)
								);
							}
							if ( ! empty( $card_data['show_wishlist'] ) ) {
								?>
								<div class="ms_lms_courses_card_item_info_wishlist">
									<?php STM_LMS_Templates::show_lms_template( 'global/wish-list', array( 'course_id' => $course['id'] ) ); ?>
								</div>
							<?php } ?>
						</div>
						<?php
					}
					if ( ! empty( $card_data['show_slots'] ) && ! ( 'empty' === $meta_slots['card_slot_1'] && 'empty' === $meta_slots['card_slot_2'] ) ) {
						?>
						<div class="ms_lms_courses_card_item_info_meta">
							<?php
							if ( 'empty' !== $meta_slots['card_slot_1'] ) {
								STM_LMS_Templates::show_lms_template(
									'elementor-widgets/courses/card/global/meta-slot/main',
									array(
										'meta_slot' => $meta_slots['card_slot_1'],
										'course'    => $course,
									)
								);
							}
							if ( 'empty' !== $meta_slots['card_slot_1'] && 'empty' !== $meta_slots['card_slot_2'] ) {
								?>
								<span class="ms_lms_courses_card_item_info_meta_divider"></span>
								<?php
							}
							if ( 'empty' !== $meta_slots['card_slot_2'] ) {
								STM_LMS_Templates::show_lms_template(
									'elementor-widgets/courses/card/global/meta-slot/main',
									array(
										'meta_slot' => $meta_slots['card_slot_2'],
										'course'    => $course,
									)
								);
							}
							?>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
