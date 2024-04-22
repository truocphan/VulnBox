<?php stm_lms_register_style( 'courses_filters' ); ?>
<?php stm_lms_register_script( 'courses_filters', array( 'stm-lms-courses' ) ); ?>

<div class="courses_filters">
	<div class="courses_filters__title">
		<h1><?php esc_html_e( 'Courses', 'masterstudy-lms-learning-management-system' ); ?></h1>
	</div>
	<div class="courses_filters__activities">

		<div class="lms_courses search">
			<?php STM_LMS_Templates::show_lms_template( 'courses/advanced_filters/filters/search' ); ?>
		</div>

		<div class="lms_courses sort">
			<div class="stm_lms_courses_grid__sort">
				<span class="sort_label heading_font"><?php esc_html_e( 'Sort By:', 'masterstudy-lms-learning-management-system' ); ?></span>
				<select class="no-search">
					<option value="date_high"><?php esc_html_e( 'Release date (newest first)', 'masterstudy-lms-learning-management-system' ); ?></option>
					<option value="date_low"><?php esc_html_e( 'Release date (oldest first)', 'masterstudy-lms-learning-management-system' ); ?></option>
					<option value="price_high"><?php esc_html_e( 'Price high', 'masterstudy-lms-learning-management-system' ); ?></option>
					<option value="price_low"><?php esc_html_e( 'Price low', 'masterstudy-lms-learning-management-system' ); ?></option>
					<option value="rating"><?php esc_html_e( 'Overall Rating', 'masterstudy-lms-learning-management-system' ); ?></option>
					<option value="popular"><?php esc_html_e( 'Popular (most viewed)', 'masterstudy-lms-learning-management-system' ); ?></option>
				</select>
			</div>
			<div class="courses_filters__switcher">
				<i class="lnricons-icons2 grid_view stc active" data-view="grid"></i>
				<i class="lnricons-list list_view stc" data-view="list"></i>
			</div>
		</div>

	</div>
</div>
