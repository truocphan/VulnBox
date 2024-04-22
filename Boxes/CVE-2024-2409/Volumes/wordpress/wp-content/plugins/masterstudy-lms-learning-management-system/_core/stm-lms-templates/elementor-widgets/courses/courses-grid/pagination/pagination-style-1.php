<?php if ( $pagination_data['offset'] < $pagination_data['total_posts'] ) { ?>
	<div class="ms_lms_courses_grid__load-more">
		<button class="ms_lms_courses_grid__load-more-button" data-offset="<?php echo esc_attr( $pagination_data['offset'] ); ?>">
			<?php echo esc_html_e( 'Load More', 'masterstudy-lms-learning-management-system' ); ?>
		</button>
	</div>
<?php } ?>
