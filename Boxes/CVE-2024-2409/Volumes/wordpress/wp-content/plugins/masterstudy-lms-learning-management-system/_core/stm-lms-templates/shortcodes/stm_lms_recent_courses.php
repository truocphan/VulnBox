<?php
$style = 'style_1';

if ( ! empty( $course_card_style ) ) {
	$style = esc_attr( $course_card_style );
}

$base_color = stm_option( 'secondary_color', '#48a7d4' );
stm_lms_module_styles( 'recent_courses', $style, array() );
stm_lms_module_scripts( 'recent_courses', 'style_1' );
stm_lms_module_scripts( 'image_container', 'card_image' );

$per_row = ( ! empty( $per_row ) ) ? esc_attr( $per_row ) : 6;

if ( class_exists( 'STM_LMS_Templates' ) ) :

	$terms = get_terms(
		'stm_lms_course_taxonomy',
		array(
			'hide_empty' => true,
			'parent'     => 0,
		)
	);

	$args = array(
		'per_row'      => $per_row,
		'include_link' => true,
		'style'        => $style,
	);

	if ( ! empty( $image_size ) ) {
		$args['image_size'] = esc_attr( $image_size );
	}

	if ( ! empty( $course_card_info ) ) {
		$args['course_card_info'] = esc_attr( $course_card_info );
	}

	if ( ! empty( $img_container_height ) ) {
		$args['img_container_height'] = esc_attr( $img_container_height );
	}

	if ( ! empty( $course_card_style ) ) {
		$args['course_card_style'] = esc_attr( $course_card_style );
	}

	if ( ! empty( $posts_per_page ) ) {
		$args['posts_per_page'] = esc_attr( $posts_per_page );
	}

	$nav_color = 'secondary_color';
	if ( 'style_2' === $style ) {
		$nav_color = 'primary_color';
	}
	?>
	<div class="stm_lms_recent_courses"
			data-offset="1"
			data-template="courses/grid"
			data-args='<?php echo wp_json_encode( $args ); ?>'>

		<div class="stm_lms_recent_courses__terms heading_font">
			<div class="stm_lms_recent_courses__term <?php echo esc_attr( $nav_color ); ?> active">
				<?php esc_html_e( 'All Categories', 'masterstudy-lms-learning-management-system' ); ?>
			</div>
			<?php foreach ( $terms as $term ) : ?>
				<div class="stm_lms_recent_courses__term <?php echo esc_attr( $nav_color ); ?>" data-term="<?php echo intval( $term->term_id ); ?>">
					<?php echo wp_kses_post( $term->name ); ?>
				</div>
			<?php endforeach; ?>
		</div>


		<?php STM_LMS_Templates::show_lms_template( 'courses/grid', array( 'args' => $args ) ); ?>

		<div class="stm_lms_recent_courses__all text-center">
			<a href="<?php echo esc_url( STM_LMS_Course::courses_page_url() ); ?>" class="btn btn-default" target="_blank">
				<?php esc_html_e( 'Show all', 'masterstudy-lms-learning-management-system' ); ?>
			</a>
		</div>

	</div>
	<?php
endif;
