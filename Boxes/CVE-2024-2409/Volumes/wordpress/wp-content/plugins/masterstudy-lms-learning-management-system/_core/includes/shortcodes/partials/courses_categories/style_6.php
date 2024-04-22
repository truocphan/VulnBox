<?php
$css_class = ( isset( $css_class ) && ! empty( trim( $css_class ) ) ) ? $css_class : '';
if ( empty( $taxonomy ) ) {
	$taxonomy = 'get_default';
}

if ( ! empty( $taxonomy ) ) :
	if ( 'get_default' === $taxonomy ) {
		$terms     = array();
		$terms_all = stm_lms_get_terms_with_meta( 'course_icon' );
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
		<div class="stm_lms_courses_categories <?php echo esc_attr( $style . $css_class ); ?>">

			<?php
			foreach ( $terms as $key => $term ) :
				$term = get_term_by(
					'id',
					$term,
					'stm_lms_course_taxonomy'
				);
				if ( empty( $term ) || is_wp_error( $term ) ) {
					continue;
				}

				$term_icon = get_term_meta( $term->term_id, 'course_icon', true );
				$term_icon = ( ! empty( $term_icon ) ) ? $term_icon : 'no-icon';

				$term_color = get_term_meta( $term->term_id, 'course_color', true );
				$term_color = ( ! empty( $term_color ) ) ? $term_color : '#1ec1d9';
				?>
				<a href="<?php echo esc_url( STM_LMS_Course::courses_page_url() . '?terms[]=' . $term->term_id . '&category[]=' . $term->term_id ); ?>>" title="<?php echo esc_attr( $term->name ); ?>" class="stm_lms_courses_category no_deco" style="background-color: <?php echo esc_attr( $term_color ); ?>">
					<i class="<?php echo esc_html( $term_icon ); ?>"></i>
					<h4><?php echo esc_html( $term->name ); ?></h4>
				</a>

			<?php endforeach; ?>
		</div>
	<?php endif; ?>
<?php endif; ?>
