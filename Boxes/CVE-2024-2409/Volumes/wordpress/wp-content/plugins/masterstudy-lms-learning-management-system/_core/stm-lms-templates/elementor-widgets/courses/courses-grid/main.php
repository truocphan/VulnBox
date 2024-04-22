<div class="ms_lms_courses_grid">
	<?php if ( ! empty( $show_header ) ) { ?>
		<div class="ms_lms_courses_grid__title <?php echo esc_attr( ! empty( $header_presets ) ? $header_presets : '' ); ?>">
			<h2>
				<?php echo esc_html( $title_text ); ?>
			</h2>
			<?php
			if ( ! empty( $show_sorting ) && ! empty( $sorting_data['sort_options'] ) ) {
				STM_LMS_Templates::show_lms_template(
					"elementor-widgets/courses/courses-grid/sorting/{$sort_presets}",
					array(
						'sorting_data' => $sorting_data,
					)
				);
			}
			?>
		</div>
	<?php } ?>
	<div class="ms_lms_courses_grid__content <?php echo esc_attr( ( ! empty( $header_presets ) ) ? " title_{$header_presets}" : '' ); ?>">
		<div class="ms_lms_courses_card_wrapper">
			<div class="ms_lms_courses_card <?php echo ( ! empty( $course_card_presets ) ) ? esc_attr( $course_card_presets ) : ''; ?>">
				<?php
				if ( ! empty( $featured_courses ) && is_array( $featured_courses ) ) {
					STM_LMS_Templates::show_lms_template(
						"elementor-widgets/courses/card/{$course_card_presets}/main",
						array(
							'courses'             => $featured_courses,
							'course_image_size'   => $course_image_size,
							'meta_slots'          => $meta_slots,
							'card_data'           => $card_data,
							'popup_data'          => $popup_data,
							'course_card_presets' => $course_card_presets,
							'featured'            => 'featured',
							'widget_type'         => $widget_type,
						)
					);
				}
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
				}
				?>
			</div>
			<div class="ms_lms_courses_grid__pagination_wrapper">
				<?php
				if ( ! empty( $show_pagination ) && $pagination_data['total_pages'] > 1 ) {
					STM_LMS_Templates::show_lms_template(
						"elementor-widgets/courses/courses-grid/pagination/{$pagination_presets}",
						array(
							'pagination_data' => $pagination_data,
						)
					);
				}
				?>
			</div>
		</div>
	</div>
</div>
