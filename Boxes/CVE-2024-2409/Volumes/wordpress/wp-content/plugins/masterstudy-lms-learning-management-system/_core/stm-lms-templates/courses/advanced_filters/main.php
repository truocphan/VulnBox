<?php
$filters = array(
	'category',
	'subcategory',
	'status',
	'levels',
	'rating',
	'instructor',
	'availability',
	'price',
);
?>

<div class="stm_lms_courses__archive_filter">

	<a href="#" class="btn btn-default stm_lms_courses__archive_filter_toggle">
		<?php esc_html_e( 'Filters', 'masterstudy-lms-learning-management-system' ); ?>
	</a>

	<form id="stm_filter_form" action="<?php echo get_permalink( STM_LMS_Options::courses_page() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" method="get">

		<div class="stm_lms_courses__archive_filters">

			<?php
			foreach ( $filters as $filter ) :

				if ( ! STM_LMS_Options::get_option( "enable_courses_filter_{$filter}", '' ) ) {
					continue;
				}

				STM_LMS_Templates::show_lms_template( "courses/advanced_filters/filters/{$filter}", array( 'category' => $category ?? '' ) );

			endforeach;
			?>

			<div class="stm_lms_courses__filter_actions">
				<input type="submit"
					class="heading_font"
					value="<?php esc_attr_e( 'Show Results', 'masterstudy-lms-learning-management-system' ); ?>">
				<a href="<?php echo get_permalink( STM_LMS_Options::courses_page() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"
					class="stm_lms_courses__filter_reset">
					<i class="lnr lnr-undo"></i>
					<span><?php esc_html_e( 'Reset all', 'masterstudy-lms-learning-management-system' ); ?></span>
				</a>
			</div>
		</div>
		<input type="hidden" name="search" value=""/>
		<input type="hidden" name="is_lms_filter" value="1"/>
	</form>
</div>
