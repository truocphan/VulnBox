<?php
stm_lms_module_styles( 'searchbox', $style );
if ( empty( $css_class ) ) {
	$css_class = '';
}
?>
<div class="stm_searchbox <?php echo esc_attr( $css_class . ' ' . $style ); ?>">

	<?php

	if ( class_exists( 'STM_LMS_Course' ) ) :
		wp_enqueue_script( 'vue-resource.js' );
		stm_lms_module_styles( 'vue-autocomplete', 'vue2-autocomplete' );
		wp_enqueue_script( 'vue2-autocomplete' );
		stm_lms_module_scripts( 'courses_search', 'courses_search' );
		?>

		<script>
			var stm_lms_search_value = '<?php echo ( ! empty( $_GET['search'] ) ) ? sanitize_text_field( $_GET['search'] ) : '';  // phpcs:ignore ?>';
		</script>

		<div class="stm_lms_courses_search vue_is_disabled" id="stm_lms_courses_search"
			v-bind:class="{'is_vue_loaded' : vue_loaded}">
			{{ search }}
			<a v-bind:href="'<?php echo esc_url( STM_LMS_Course::courses_page_url() ); ?>?search=' + url"
				class="stm_lms_courses_search__button sbc">
				<?php if ( 'style_1' === $style ) : ?>
					<i class="lnricons-magnifier"></i>
				<?php else : ?>
					<span
						class="heading_font"><?php esc_html_e( 'Find Course', 'masterstudy-lms-learning-management-system' ); ?></span>
				<?php endif; ?>
			</a>
			<autocomplete
				name="search"
				placeholder="<?php esc_attr_e( 'Search courses...', 'masterstudy-lms-learning-management-system' ); ?>"
				url="<?php echo esc_url( rest_url( 'stm-lms/v1/courses', 'json' ) ); ?>"
				param="search"
				anchor="value"
				label="label"
				:on-select="searchCourse"
				:on-input="searching"
				:on-ajax-loaded="loaded"
				:debounce="1000"
				model="search">
			</autocomplete>
		</div>

	<?php endif; ?>
</div>
