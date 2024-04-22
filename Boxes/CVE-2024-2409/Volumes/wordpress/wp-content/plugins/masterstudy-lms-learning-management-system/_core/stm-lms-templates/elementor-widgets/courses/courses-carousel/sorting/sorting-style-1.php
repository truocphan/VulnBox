<ul class="ms_lms_courses_carousel__sorting style_1">
	<?php
	if ( ! empty( $sorting_data['sort_options_by_cat'] ) ) {
		?>
		<li>
			<span data-id="all" class="ms_lms_courses_carousel__sorting_button active">
				<?php echo esc_html__( 'All', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
		</li>
		<?php
	}
	foreach ( $sorting_data['sort_options'] as $option => $label ) {
		?>
		<li>
			<span data-id="<?php echo esc_attr( $option ); ?>" class="ms_lms_courses_carousel__sorting_button <?php echo esc_attr( ( $option === $sorting_data['sort_by'] ) ? 'active' : '' ); ?>">
				<?php echo esc_html( $label ); ?>
			</span>
		</li>
	<?php } ?>
</ul>
