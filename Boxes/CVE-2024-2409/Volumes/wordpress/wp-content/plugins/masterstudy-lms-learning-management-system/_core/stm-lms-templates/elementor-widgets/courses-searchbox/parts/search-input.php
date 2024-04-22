<div class="ms_lms_course_search_box__search_input <?php echo ( ! empty( $parent_terms ) ) ? 'has_categories' : ''; ?> <?php echo ( ! empty( $presets ) ) ? esc_attr( $presets ) : 'search_button_inside'; ?>">
	{{ search }}
	<autocomplete
		name="search"
		placeholder="<?php echo esc_attr( $search_placeholder ); ?>"
		url="<?php echo esc_url( rest_url( 'stm-lms/v1/courses', 'json' ) ); ?>"
		param="search"
		anchor="value"
		label="label"
		:on-select="searchCourse"
		:on-input="searching"
		:debounce="1000"
		model="search">
	</autocomplete>
	<a :href="'<?php echo esc_url( STM_LMS_Course::courses_page_url() ); ?>?search=' + url"
		class="ms_lms_course_search_box__search_input_button">
		<i class="lnricons-magnifier"></i>
	</a>
</div>
