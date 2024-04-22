<div class="ms_lms_courses_teacher">
	<div class="ms_lms_courses_teacher_wrapper">
		<?php if ( ! empty( $instructor ) ) {
			if ( $show_instructor_label && ! empty( $label ) ) {
				?>
				<a href="<?php echo esc_url( $instructor['url'] ); ?>" class="ms_lms_courses_teacher_label">
					<?php echo esc_html( $label ); ?>
				</a>
				<?php
			} if ( ! empty( $instructor['login'] ) ) {
				?>
				<a href="<?php echo esc_url( $instructor['url'] ); ?>" class="ms_lms_courses_teacher_name">
					<?php echo esc_html( $instructor['login'] ); ?>
				</a>
				<?php
			} if ( $show_instructor_position && ! empty( $instructor['meta']['position'] ) ) {
				?>
				<span class="ms_lms_courses_teacher_position">
					<?php echo esc_html( $instructor['meta']['position'] ); ?>
				</span>
				<?php
			} if ( $show_instructor_bio && ! empty( $instructor['meta']['description'] ) ) {
				?>
				<div class="ms_lms_courses_teacher_bio">
					<?php echo esc_html( $instructor['meta']['description'] ); ?>
				</div>
			<?php } ?>
				<span class="ms_lms_courses_teacher_courses">
					<?php echo esc_html__( 'Teacher courses:', 'masterstudy-lms-learning-management-system' ); ?>
				</span>
		<?php } ?>
	</div>
	<div class="ms_lms_courses_card <?php echo ( ! empty( $course_card_presets ) ) ? esc_attr( $course_card_presets ) : ''; ?>">
		<?php
		if ( ! empty( $courses ) && is_array( $courses ) ) {
			STM_LMS_Templates::show_lms_template(
				"elementor-widgets/courses/card/{$course_card_presets}/main",
				array(
					'courses'             => $courses,
					'course_image_size'   => $course_image_size,
					'meta_slots'          => $meta_slots,
					'card_data'           => $card_data,
					'popup_data'          => $popup_data,
					'course_card_presets' => $course_card_presets,
					'widget_type'         => $widget_type,
				)
			);
		} else {
			?>
			<span class="ms_lms_courses_teacher_no-courses">
				<?php echo esc_html__( 'No courses', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
			<?php
		}
		?>
	</div>
	<?php if ( ! empty( $show_view_all ) ) { ?>
		<a href="<?php echo esc_url( $view_all_url ); ?>" class="ms_lms_courses_teacher_view-all">
			<?php echo esc_html( $view_all_text ); ?>
		</a>
	<?php } ?>
</div>
