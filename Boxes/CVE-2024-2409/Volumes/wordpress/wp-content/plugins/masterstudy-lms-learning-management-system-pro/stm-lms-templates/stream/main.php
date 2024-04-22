<?php
/**
 * @var $post_id
 * @var $item_id
 */

$url              = get_post_meta( $item_id, 'lesson_stream_url', true );
$stream_started   = STM_LMS_Live_Streams::is_stream_started( $item_id );
$video_idx        = apply_filters( 'ms_plugin_get_youtube_idx', $url );
$youtube_chat_url = 'https://www.youtube.com/live_chat?v=' . $video_idx . '&embed_domain=' . str_replace( 'www.', '', $_SERVER['SERVER_NAME'] );
$youtube_url      = 'https://www.youtube.com/embed/' . $video_idx . '?&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1';

$q = new WP_Query(
	array(
		'posts_per_page' => 1,
		'post_type'      => 'stm-lessons',
		'post__in'       => array( $item_id ),
	)
);

stm_lms_register_script( 'lessons-stream', array( 'jquery-ui-resizable' ) );
stm_lms_register_script( 'lessons' );

if ( $q->have_posts() ) :
	while ( $q->have_posts() ) :
		$q->the_post();
		$the_content = get_the_content();
	endwhile;
	wp_reset_postdata();
endif;
?>
	<script>
		var cf7_custom_image = '<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/img/';
		var daysStr = '<?php esc_html_e( 'Days', 'masterstudy' ); ?>';
		var hoursStr = '<?php esc_html_e( 'Hours', 'masterstudy' ); ?>';
		var minutesStr = '<?php esc_html_e( 'Minutes', 'masterstudy' ); ?>';
		var secondsStr = '<?php esc_html_e( 'Seconds', 'masterstudy' ); ?>';
	</script>
<?php if ( $stream_started ) : ?>
	<h3 class="stm_lms_stream_lesson__title"><?php echo wp_kses_post( get_the_title( $item_id ) ); ?></h3>

	<div class="stm_lms_stream_lesson">
		<div class="left">
			<div class="stm_lms_stream_lesson__video">
				<iframe src="<?php echo esc_attr( $youtube_url ); ?>" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
		</div>

		<?php if ( ! empty( $the_content ) || ! empty( $youtube_chat_url ) ) : ?>
			<div class="right">
				<div class="right_inner">
					<?php if ( ! empty( $the_content ) ) : ?>
						<div class="stm_lms_stream_lesson__content">
							<div class="stm_lms_stream_lesson__content_inner">
								<?php echo wp_kses_post( $the_content ); ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $youtube_chat_url ) ) : ?>
						<div class="stm_lms_stream_lesson__chat">
							<iframe src="<?php echo esc_attr( $youtube_chat_url ); ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<?php
else :
	wp_enqueue_script( 'jquery.countdown' );

	stm_lms_register_style( 'countdown/style_1' );
	?>
	<div class="container stream-starts-soon">
		<h3 class="text-center">
			<?php
				printf(
					/* translators: %s Title */
					esc_html__( '%s starts in', 'masterstudy-lms-learning-management-system-pro' ),
					esc_html( get_the_title( $item_id ) )
				);
			?>
		</h3>

		<div class="stm_countdown text-center" data-timer="<?php echo esc_attr( STM_LMS_Live_Streams::stream_start_time( $item_id ) * 1000 ); ?>" id="countdown_<?php echo esc_attr( $item_id ); ?>"></div>
	</div>
	<?php
endif;
?>
<div class="container">
	<div class="col-12">
		<?php
			// files hidden in main content by 'show_item_content' hook
			// but we need render lesson materials
			STM_LMS_Templates::show_lms_template( 'lesson/files', compact( 'item_id' ) );
		?>
	</div>
</div>
