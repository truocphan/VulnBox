<div class="ms_lms_course_search_box__categories_dropdown_childs_wrapper">
	<?php
	foreach ( $parent_terms as $term ) {
		$parent_id   = $term->term_id;
		$child_terms = get_terms(
			array(
				'taxonomy'   => 'stm_lms_course_taxonomy',
				'hide_empty' => false,
				'parent'     => $parent_id,
			)
		);
		if ( ! empty( $child_terms ) ) {
			?>
			<div class="ms_lms_course_search_box__categories_dropdown_childs" data-id="<?php echo esc_attr( $term->term_id ); ?>">
				<?php foreach ( $child_terms as $child_term ) { ?>
					<div class="ms_lms_course_search_box__categories_dropdown_child">
						<a href="<?php echo esc_url( get_term_link( $child_term ) ); ?>" class="ms_lms_course_search_box__categories_dropdown_child_link">
							<?php echo esc_html( $child_term->name ); ?>
						</a>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
	<?php } ?>
</div>
