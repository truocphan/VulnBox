<?php
stm_lms_module_styles( 'featured_teacher', 'style_1' );
stm_lms_module_scripts( 'image_container', 'card_image' );

$posts_per_page = ( ! empty( $posts_per_page ) ) ? $posts_per_page : 4;
$posts_per_row  = ( ! empty( $posts_per_row ) ) ? $posts_per_row : 4;
$css_class      = ( ! empty( $css_class ) ) ? $css_class : '';

if ( empty( $instructor ) ) {
	$super_admins = get_super_admins();
	if ( ! empty( $super_admins[0] ) ) {
		$super_admin = get_user_by( 'login', $super_admins[0] );
		$instructor  = $super_admin->ID ?? 0;
	}
}

$image = ( ! empty( $image ) ) ? stm_lms_get_VC_attachment_img_safe( $image, 'full', 'full', true ) : '';

if ( ! empty( $instructor ) ) :
	$instructor_data = STM_LMS_User::get_current_user( esc_attr( $instructor ) );
	$args            = array(
		'per_row'        => $posts_per_row,
		'posts_per_page' => $posts_per_page,
		'author'         => esc_attr( $instructor ),
	);

	if ( ! empty( $course_card_style ) ) {
		$args['course_card_style'] = esc_attr( $course_card_style );
	}

	if ( ! empty( $course_card_info ) ) {
		$args['course_card_info'] = esc_attr( $course_card_info );
	}

	if ( ! empty( $img_container_height ) ) {
		$args['img_container_height'] = esc_attr( $img_container_height );
	}

	if ( ! empty( $image_size ) ) {
		$args['image_size'] = esc_attr( $image_size );
	}
	?>

	<div class="stm_lms_featured_teacher 
	<?php
			echo esc_attr( $css_class );
			echo esc_attr( " stm_lms_featured_teacher_image_{$instructor}" );
	?>
	"
			style="background-image: url('<?php echo esc_url( apply_filters( "stm_lms_featured_teacher_image_{$instructor}", $image ) ); ?>')">

		<div class="stm_lms_featured_teacher_content">

			<div class="stm_lms_featured_teacher_content__text">

				<a href="<?php echo esc_url( STM_LMS_User::user_public_page_url( $instructor ) ); ?>" class="btn btn-default">
					<?php esc_html_e( 'Teacher of month', 'masterstudy-lms-learning-management-system' ); ?>
				</a>

				<h2><?php echo wp_kses_post( $instructor_data['login'] ); ?></h2>

				<?php if ( ! empty( $position ) ) : ?>
					<div class="stm_lms_featured_teacher_content__position">
						<h4><?php echo esc_attr( $position ); ?></h4>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $bio ) ) : ?>
					<div class="stm_lms_featured_teacher_content__bio">
						<?php echo wp_kses_post( $bio ); ?>
					</div>
				<?php endif; ?>

			</div>

		</div>

		<div class="stm_lms_featured_teacher_courses">
			<h4><?php esc_html_e( 'Teacher Courses:', 'masterstudy-lms-learning-management-system' ); ?></h4>
			<?php STM_LMS_Templates::show_lms_template( 'courses/grid', array( 'args' => $args ) ); ?>
		</div>

		<?php if ( ! empty( $instructor_btn_text ) ) : ?>
			<div class="featured-teacher-all">
				<a href="<?php echo esc_url( STM_LMS_Instructor::user_public_page_url( $instructor ) ); ?>" class="btn btn-default">
					<?php echo wp_kses_post( $instructor_btn_text ); ?>
				</a>
			</div>
		<?php endif; ?>

	</div>
<?php endif; ?>
