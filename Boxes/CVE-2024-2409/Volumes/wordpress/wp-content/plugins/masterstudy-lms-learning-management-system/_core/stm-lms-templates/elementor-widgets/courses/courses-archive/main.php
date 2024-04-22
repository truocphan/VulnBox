<div class="ms_lms_courses_archive">
	<?php if ( ! empty( $show_header ) ) { ?>
		<div class="ms_lms_courses_archive__title <?php echo esc_attr( ! empty( $header_presets ) ? $header_presets : '' ); ?>">
			<h2>
				<?php echo esc_html( $title_text ); ?>
			</h2>
			<?php
			if ( ! empty( $show_sorting ) && ! empty( $sorting_data['sort_options'] ) ) {
				STM_LMS_Templates::show_lms_template(
					"elementor-widgets/courses/courses-archive/sorting/{$sort_presets}",
					array(
						'sorting_data' => $sorting_data,
					)
				);
			}
			?>
		</div>
	<?php } ?>
	<div class="ms_lms_courses_archive__content <?php echo esc_attr( ( ( ! empty( $filter_data['filter_position'] ) ) ? $filter_data['filter_position'] : '' ) . ( ( ! empty( $header_presets ) ) ? " title_{$header_presets}" : '' ) ); ?>">
		<?php
		if ( ! empty( $show_filter ) && ! empty( $filter_data['filter_options'] ) ) {
			STM_LMS_Templates::show_lms_template(
				'elementor-widgets/courses/courses-archive/filter/main',
				array(
					'show_filter' => $show_filter,
					'filter_data' => $filter_data,
				)
			);
		}
		?>
		<div class="ms_lms_courses_card_wrapper">
			<?php if ( ! empty( $featured_courses ) && is_array( $featured_courses ) && ! empty( $show_featured_block ) ) { ?>
				<div class="ms_lms_courses_card featured <?php echo ( ! empty( $course_card_presets ) ) ? esc_attr( $course_card_presets ) : ''; ?>">
					<?php
					STM_LMS_Templates::show_lms_template(
						"elementor-widgets/courses/card/{$course_card_presets}/main",
						array(
							'courses'             => $featured_courses,
							'course_image_size'   => $course_image_size,
							'meta_slots'          => $meta_slots,
							'card_data'           => $card_data,
							'popup_data'          => $popup_data,
							'course_card_presets' => $course_card_presets,
							'widget_type'         => $widget_type,
						)
					);
					?>
				</div>
			<?php } ?>
			<div class="ms_lms_courses_card <?php echo ( ! empty( $course_card_presets ) ) ? esc_attr( $course_card_presets ) : ''; ?>">
				<?php
				if ( ! empty( $featured_courses ) && is_array( $featured_courses ) && empty( $show_featured_block ) ) {
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
				} else {
					STM_LMS_Templates::show_lms_template( 'elementor-widgets/courses/courses-archive/filter/no-results' );
				}
				?>
			</div>
			<div class="ms_lms_courses_archive__pagination_wrapper">
				<?php
				if ( ! empty( $show_pagination ) && $pagination_data['total_pages'] > 1 ) {
					STM_LMS_Templates::show_lms_template(
						"elementor-widgets/courses/courses-archive/pagination/{$pagination_presets}",
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
