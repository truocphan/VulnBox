<?php
if ( empty( $taxonomy ) ) {
	$taxonomy = 'get_default';
}

if ( ! empty( $taxonomy ) ) :
	$base_color = STM_LMS_Options::get_option( 'main_color', '#385bce' );

	stm_lms_module_styles(
		'course_category',
		$style,
		array(),
		".stm_lms_courses_category a:hover h4 {color: {$base_color}}"
	);

	if ( 'get_default' === $taxonomy ) {
		$terms     = array();
		$terms_all = stm_lms_get_lms_terms_with_meta( 'course_image' );
		if ( ! empty( $terms_all ) ) {
			foreach ( $terms_all as $term ) {
				$meta_value = get_term_meta( $term->term_id, 'course_image', true );
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
				$class = ( ! $key ) ? 'wide' : 'default';
				if ( 2 !== $key ) :
					?>
				<div class="stm_lms_courses_category stm_lms_courses_category__<?php echo esc_attr( $key ); ?> stm_lms_courses_category_<?php echo esc_attr( $class ); ?>">
			<?php endif; ?>

				<a href="<?php echo esc_url( STM_LMS_Course::courses_page_url() . '?terms[]=' . $term->term_id . '&category[]=' . $term->term_id ); ?>"
					title="<?php echo esc_attr( $term->name ); ?>"
					class="no_deco <?php echo esc_attr( $class ); ?>">

					<?php
					$term_image       = wpcfto_get_term_meta_text( $term->term_id, 'course_image' );
					$big_image        = ( 'style_2' === $style ) ? '770x340' : '770x375';
					$image_dimensions = ( 0 === $key ) ? $big_image : '370x155';
					if ( ! empty( $term_image ) ) {
						$image = stm_lms_get_vc_attachment_img_safe( $term_image, $image_dimensions );
					} else {
						$image_dimensions = explode( 'x', $image_dimensions );
						$image            = '<div class="stm_lms_courses_categories__holder" style="width: ' . $image_dimensions[0] . 'px;"></div>';
					}
					?>

					<div class="stm_lms_courses_category__image">
						<?php echo wp_kses( html_entity_decode( $image ), stm_lms_allowed_html() ); ?>
					</div>

					<div class="stm_lms_courses_category__info">
						<h4><?php echo esc_attr( $term->name ); ?></h4>
						<span>
							<?php
							printf(
								/* translators: %s: nubmer */
								esc_html__( '%s Courses', 'masterstudy-lms-learning-management-system' ),
								esc_html( $term->count )
							);
							?>
						</span>
					</div>
				</a>

				<?php if ( 1 !== $key ) : ?>
				</div>
			<?php endif; ?>

			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php
endif;
