<?php
/**
 * @var $args
 */

if ( empty( $args ) ) {
	$args = array();
}

if ( empty( $query_result ) ) {
	/** Call query if data not set */
	if ( ! empty( $_GET['search'] ) ) {
		$args['s'] = sanitize_text_field( $_GET['search'] );
	}
	$default_args       = array(
		'post_type'      => 'stm-courses',
		'posts_per_page' => STM_LMS_Options::get_option( 'courses_per_page', get_option( 'posts_per_page' ) ),
	);
	$args['image_size'] = ! empty( $args['image_size'] ) ? $args['image_size'] : '300x225';
	$args               = wp_parse_args( $args, $default_args );
	$q                  = new WP_Query( $args );

} else {
	$q = $query_result;
}

$per_row = ( ! empty( $args['per_row'] ) ) ? $args['per_row'] : STM_LMS_Options::get_option( 'courses_per_row', 3 );
$class   = ( ! empty( $args['class'] ) ) ? $args['class'] : '';

if ( ! empty( $args['course_card_info'] ) ) {
	$course_view = 'stm_lms_courses__grid_' . $args['course_card_info'];
} else {
	$course_view = 'stm_lms_courses__grid_' . STM_LMS_Options::get_option( 'course_card_view', 'center' );
}

if ( ! empty( $args['course_card_style'] ) ) {
	$course_style = $args['course_card_style'];
} else {
	$course_style = STM_LMS_Options::get_option( 'course_card_style', 'style_1' );
}

stm_lms_register_style( 'courses' );
stm_lms_register_style( "courses/{$course_style}" );

if ( $q->have_posts() ) :

	do_action( 'stm_lms_courses_have_posts', $args, $q );

	if ( empty( $args['isAjax'] ) ) : ?>
		<?php
		if ( ! empty( $args['is_featured'] ) ) :
			$link = add_query_arg(
				array(
					'status[]'      => 'featured',
					'only_featured' => '1',
				)
			);
			?>
			<div class="featured-head">
				<h3>
					<?php esc_html_e( 'Featured Courses', 'masterstudy-lms-learning-management-system' ); ?>
				</h3>
				<a href="<?php echo esc_attr( $link ); ?>">
				<i class="fas fa-arrow-right"></i>
				<span>
					<?php esc_html_e( 'Show all', 'masterstudy-lms-learning-management-system' ); ?>
				</span>
				</a>
			</div>
		<?php endif; ?>
		<div class="stm_lms_courses__grid stm_lms_courses__grid_<?php echo esc_attr( $per_row . ' ' . $course_view ); ?> <?php echo esc_attr( $class ); ?> stm_lms_courses__grid_found_<?php echo intval( $q->found_posts ); ?>"
		data-pages="<?php echo esc_attr( ceil( $q->found_posts / $args['posts_per_page'] ) ); ?>">
	<?php endif; ?>

	<?php
	while ( $q->have_posts() ) :
		global $post;
		$vars = array();
		$q->the_post();
		$vars['id']         = get_the_ID();
		$vars['price']      = get_post_meta( $vars['id'], 'price', true );
		$vars['sale_price'] = STM_LMS_Course::get_sale_price( $vars['id'] );

		$id         = $vars['id'];
		$price      = $vars['price'];
		$sale_price = $vars['sale_price'];

		if ( empty( $price ) && ! empty( $sale_price ) ) {
			$vars['price']      = $sale_price;
			$vars['sale_price'] = '';
		}

		$vars['has_sale_price'] = ! empty( $sale_price ) ? 'has-sale' : 'no-sale';
		$vars['author_id']      = $post->post_author;
		$vars['style']          = $course_style;
		$vars['featured']       = get_post_meta( $id, 'featured', true );

		if ( ! empty( $args['image_size'] ) ) {
			$vars['image_size'] = $args['image_size'];
		}

		if ( ! empty( $args['img_container_height'] ) ) {
			$vars['img_container_height'] = $args['img_container_height'];
		}

		STM_LMS_Templates::show_lms_template( "courses/styles/{$course_style}", $vars );

	endwhile;
	?>
	<?php if ( empty( $args['isAjax'] ) ) : ?>
	</div>
<?php endif; ?>
<?php elseif ( empty( $args['is_featured'] ) ) : ?>
	<?php
		stm_lms_register_style( 'wishlist' );
		stm_lms_register_script( 'wishlist' );
	?>
	<?php if ( empty( $args['isAjax'] ) ) : ?>
		<div class="stm_lms_courses__grid stm_lms_courses__grid_<?php echo esc_attr( $per_row . ' ' . $course_view ); ?> <?php echo esc_attr( $class ); ?>"
		data-pages="<?php echo esc_attr( ceil( $q->found_posts / $args['posts_per_page'] ) ); ?>">
	<?php endif; ?>

	<div class="stm-lms-course-no-result">
		<div class="no-found">
			<div class="no-found-background">
				<span class="no-result-icon">
				</span>
			</div>
			<div class="no-found-icon">
				<i class="stmlms-not_found_courses"> </i>
			</div>
		</div>
		<p>
			<?php esc_html_e( 'No courses found.', 'masterstudy-lms-learning-management-system' ); ?>
		</p>
		<a href="<?php echo esc_attr( get_permalink( STM_LMS_Options::courses_page() ) ); ?>" class="stm_lms_courses__filter_reset">
			<i class="fa fa-refresh"></i>
			<span><?php esc_html_e( 'Reset all', 'masterstudy-lms-learning-management-system' ); ?></span>
		</a>
	</div>
	<?php if ( empty( $args['isAjax'] ) ) : ?>
		</div>
	<?php endif; ?>
<?php endif;
wp_reset_postdata();
?>
