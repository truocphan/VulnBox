<div class="ms_lms_course_search_box__categories_dropdown_parents">
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
		?>
		<div class="ms_lms_course_search_box__categories_dropdown_parent" data-id="<?php echo esc_attr( $term->term_id ); ?>">
			<?php if ( ! empty( $child_terms ) ) { ?>
				<div class="ms_lms_course_search_box__categories_dropdown_parent_wrapper">
			<?php } ?>
					<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="ms_lms_course_search_box__categories_dropdown_parent_link">
						<?php echo esc_html( $term->name ); ?>
					</a>
			<?php
			if ( ! empty( $child_terms ) ) {
				?>
					<i class="lnr lnr-chevron-down mobile_chevron"></i>
				</div>
				<div class="ms_lms_course_search_box__categories_dropdown_mobile_childs closed">
					<?php
					foreach ( $child_terms as $child_term ) {
						?>
						<div class="ms_lms_course_search_box__categories_dropdown_mobile_child">
							<a href="<?php echo esc_url( get_term_link( $child_term ) ); ?>" class="ms_lms_course_search_box__categories_dropdown_child_link">
								<?php echo esc_html( $child_term->name ); ?>
							</a>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
</div>
