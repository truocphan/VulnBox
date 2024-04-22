<?php
/**
 * @var $id
 * @var $img_size
 */

$post_status        = STM_LMS_Course::get_post_status( $id );
$default_image_size = STM_LMS_Options::get_option( 'courses_image_size', '272x161' );
$img_size           = ( ! empty( $img_size ) ) ? $img_size : $default_image_size;

if ( ! empty( $img_container_height ) ) {
	$container_height     = preg_replace( '/[^0-9]/', '', $img_container_height );
	$img_container_height = ( is_admin() ? 'style=height:' : 'data-height=' ) . $container_height . 'px';
} else {
	$img_container_height = '';
}

$progress = 0;
if ( is_user_logged_in() ) {
	$my_progress = STM_LMS_Helpers::simplify_db_array( stm_lms_get_user_course( get_current_user_id(), $id, array( 'progress_percent' ) ) );
	if ( ! empty( $my_progress['progress_percent'] ) ) {
		$progress = $my_progress['progress_percent'];
	}

	if ( $progress > 100 ) {
		$progress = 100;
	}
}

?>

<div class="stm_lms_courses__single--image">

	<?php if ( ! empty( $progress ) ) : ?>
		<div class="stm_lms_courses__single--image__progress">
			<div class="stm_lms_courses__single--image__progress_bar"
				style="width : <?php echo esc_attr( $progress ); ?>%">
				<span class="stm_lms_courses__single--image__progress_label"><?php echo esc_html( "{$progress}%" ); ?></span>
			</div>
		</div>
	<?php endif; ?>
	<div class="featured-course-container">
		<?php if ( ! empty( $featured ) ) : ?>
			<div class="elab_is_featured_product"><?php esc_html_e( 'Featured', 'masterstudy-lms-learning-management-system' ); ?></div>
		<?php endif; ?>
	</div>
	<?php if ( ! empty( $post_status ) ) : ?>
		<div class="stm_lms_post_status heading_font <?php echo esc_html( sanitize_text_field( $post_status['status'] ) ); ?>">
			<?php echo esc_html( sanitize_text_field( $post_status['label'] ) ); ?>
		</div>
	<?php endif; ?>

	<a href="<?php the_permalink(); ?>"
	class="heading_font"
	data-preview="<?php esc_attr_e( 'Preview this course', 'masterstudy-lms-learning-management-system' ); ?>">
		<div class="stm_lms_courses__single--image__container" <?php echo esc_attr( $img_container_height ); ?>>
			<?php
			if ( function_exists( 'stm_get_VC_img' ) ) {
				echo ( stm_lms_lazyload_image( stm_get_VC_img( get_post_thumbnail_id(), $img_size ) ) ); //phpcs:ignore
			} else {
				the_post_thumbnail( $img_size );
			}
			?>
		</div>
	</a>

</div>
