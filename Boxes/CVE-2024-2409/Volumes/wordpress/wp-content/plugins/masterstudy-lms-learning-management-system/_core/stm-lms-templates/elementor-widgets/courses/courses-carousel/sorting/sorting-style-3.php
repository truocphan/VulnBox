<div class="ms_lms_courses_carousel__sorting style_3">
	<span class="ms_lms_courses_carousel__sorting_select_label"><?php esc_html_e( 'Sort by', 'masterstudy-lms-learning-management-system' ); ?>:</span>
	<select name="ms_lms_courses_carousel__sorting_select" class="ms_lms_courses_carousel__sorting_select">
		<?php
		if ( ! empty( $sorting_data['sort_options_by_cat'] ) ) {
			?>
			<option value="all">
				<?php echo esc_html__( 'All', 'masterstudy-lms-learning-management-system' ); ?>
			</option>
			<?php
		}
		foreach ( $sorting_data['sort_options'] as $option => $label ) {
			?>
			<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $option, $sorting_data['sort_by'] ); ?>>
				<?php echo esc_html( $label ); ?>
			</option>
		<?php } ?>
	</select>
</div>
