<?php
/**
 * @var int $post_id
 * @var int $item_id
 * @var string $lesson_type
 * @var boolean $dark_mode
 */

use MasterStudy\Lms\Plugin\PostType;

wp_enqueue_style( 'video.js' );
wp_enqueue_style( 'masterstudy-course-player-lesson' );
wp_enqueue_script( 'masterstudy-course-player-lesson' );

if ( function_exists( 'vc_asset_url' ) ) {
	wp_enqueue_style( 'stm_lms_wpb_front_css' );
}

if ( class_exists( 'Ultimate_VC_Addons' ) ) {
	STM_LMS_Lesson::aio_front_scripts();
}

global $post;

$post = get_post( $item_id );

if ( $post instanceof \WP_Post && PostType::LESSON === $post->post_type ) {
	setup_postdata( $post );
	?>
	<div class="masterstudy-course-player-lesson">
		<?php
		if ( 'video' === $lesson_type ) {
			STM_LMS_Templates::show_lms_template( 'course-player/content/lesson/video', array( 'id' => $item_id ) );
		}

		ob_start();

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo apply_filters( 'the_content', $post->post_content );

		$content = ob_get_clean();
		$content = str_replace( '../../', site_url() . '/', $content );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo stm_lms_filtered_output( $content );
		?>
	</div>
	<span class="masterstudy-course-player-lesson__submit-trigger"></span>
	<?php
	wp_reset_postdata();
}
