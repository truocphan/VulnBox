<?php

stm_lms_module_styles( 'course_category', 'style_3' );

if ( empty( $taxonomy ) ) {
	$taxonomy = 'get_default';
}

if ( ! empty( $taxonomy ) ) :
	if ( 'get_default' === $taxonomy ) {
		$terms     = array();
		$terms_all = stm_lms_get_lms_terms_with_meta( 'course_icon' );
		if ( ! empty( $terms_all ) ) {
			foreach ( $terms_all as $term ) {
				$meta_value = get_term_meta( $term->term_id, 'course_icon', true );
				if ( ! empty( $meta_value ) ) {
					$terms[] = $term->term_id;
				}
			}
		}
	} else {
		$terms = explode( ',', str_replace( ' ', '', $taxonomy ) );
	}

	if ( ! empty( $terms ) && is_array( $terms ) ) : ?>
		<div class="stm_lms_courses_categories <?php echo esc_attr( $style ); ?>">

			<?php
			foreach ( $terms as $key => $term ) :
				$term = get_term_by( 'id', $term, 'stm_lms_course_taxonomy' );
				if ( empty( $term ) || is_wp_error( $term ) ) {
					continue;
				}
				$term_icon = get_term_meta( $term->term_id, 'course_icon', true );
				$term_icon = ( ! empty( $term_icon ) ) ? $term_icon : 'no-icon';
				?>
				<div class="stm_lms_courses_category">
					<a href="<?php echo esc_url( STM_LMS_Course::courses_page_url() . '?terms[]=' . $term->term_id . '&category[]=' . $term->term_id ); ?>"
						title="<?php echo esc_attr( $term->name ); ?>"
						class="no_deco sbc_h">
						<i class="<?php echo esc_attr( $term_icon ); ?>"></i>
						<h4><?php echo esc_attr( $term->name ); ?></h4>
					</a>
				</div>

			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php
endif;
