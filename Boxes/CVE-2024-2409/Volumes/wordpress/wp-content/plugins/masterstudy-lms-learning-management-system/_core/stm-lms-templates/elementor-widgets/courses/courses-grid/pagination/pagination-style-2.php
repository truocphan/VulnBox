<nav class="ms_lms_courses_grid__pagination">
	<?php
	$paginate = paginate_links(
		array(
			'type'      => 'array',
			'base'      => '%_%',
			'format'    => '?page=%#%',
			'current'   => $pagination_data['current_page'],
			'total'     => $pagination_data['total_pages'],
			'mid_size'  => 2,
			'end_size'  => 1,
			'prev_text' => esc_html__( 'Prev', 'masterstudy-lms-learning-management-system' ),
			'next_text' => esc_html__( 'Next', 'masterstudy-lms-learning-management-system' ),
		),
	);
	if ( is_array( $paginate ) ) {
		?>
		<ul class="ms_lms_courses_grid__pagination_list">
		<?php foreach ( $paginate as $item ) { ?>
			<li class="ms_lms_courses_grid__pagination_list_item">
				<?php echo wp_kses_post( $item ); ?>
			</li>
		<?php } ?>
		</ul>
		<?php
	}
	?>
</nav>
