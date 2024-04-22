<div class="ms_lms_courses_archive__sorting style_3">
	<span class="ms_lms_courses_archive__sorting_select_label"><?php esc_html_e( 'Sort by', 'masterstudy-lms-learning-management-system' ); ?>:</span>
	<select name="ms_lms_courses_archive__sorting_select" class="ms_lms_courses_archive__sorting_select">
		<?php foreach ( $sorting_data['sort_options'] as $option => $label ) { ?>
			<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $option, $sorting_data['sort_by'] ); ?>>
				<?php echo esc_html( $label ); ?>
			</option>
		<?php } ?>
	</select>
</div>
