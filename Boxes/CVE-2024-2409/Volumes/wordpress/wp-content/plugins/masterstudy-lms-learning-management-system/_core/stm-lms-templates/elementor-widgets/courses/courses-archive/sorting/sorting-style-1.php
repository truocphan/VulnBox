<ul class="ms_lms_courses_archive__sorting style_1">
	<?php foreach ( $sorting_data['sort_options'] as $option => $label ) { ?>
		<li>
			<span data-id="<?php echo esc_attr( $option ); ?>" class="ms_lms_courses_archive__sorting_button <?php echo esc_attr( ( $option === $sorting_data['sort_by'] ) ? 'active' : '' ); ?>">
				<?php echo esc_html( $label ); ?>
			</span>
		</li>
	<?php } ?>
</ul>
