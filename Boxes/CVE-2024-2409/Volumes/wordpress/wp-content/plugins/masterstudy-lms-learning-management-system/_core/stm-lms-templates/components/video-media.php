<?php
/**
 * Video Media type component
 * @var array $lesson - $lesson array
 * @var array $id - $lesson array
 * @package masterstudy
 */

wp_enqueue_style( 'masterstudy-course-player-lesson-video' );
wp_enqueue_script( 'masterstudy-course-player-lesson-video' );
?>

<div class="masterstudy-course-player-lesson-video">
	<?php
	if ( 'embed' === $lesson['video_type'] && ! empty( $lesson['embed_ctx'] ) ) {
		?>
		<div class="masterstudy-course-player-lesson-video__embed-wrapper">
			<?php echo wp_kses( htmlspecialchars_decode( $lesson['embed_ctx'] ), stm_lms_allowed_html() ); ?>
		</div>
		<?php
	} elseif ( in_array( $lesson['video_type'], array( 'html', 'ext_link' ), true ) ) {
		$uploaded_video = $lesson['external_url'] ?? '';
		$video_format   = 'mp4';

		if ( 'html' === $lesson['video_type'] ) {
			$uploaded_video = $lesson['video']['url'] ?? '';
			$video_format   = explode( '.', $uploaded_video );
			$video_format   = strtolower( end( $video_format ) );
			$video_width    = ! empty( $lesson['video_width'] ) ? "max-width: {$lesson['video_width']}px" : '';
		}

		if ( strpos( $uploaded_video, 'embed' ) ) {
			?>
			<embed src="<?php echo esc_url( $uploaded_video ); ?>">
			<?php
		} else {
			?>
			<div class="masterstudy-course-player-lesson-video__wrapper">
				<span class="masterstudy-course-player-lesson-video__play-button"></span>
				<video id="masterstudy-course-player-lesson-video" data-id="<?php echo esc_attr( $id ); ?>"
					poster="<?php echo esc_url( $lesson['video_poster']['url'] ?? '' ); ?>" controls
					controlsList="nodownload"
					style="<?php echo esc_attr( ! empty( $video_width ) ? $video_width : '' ); ?>">
					<source src="<?php echo esc_url( $uploaded_video ); ?>"
						type='video/<?php echo esc_attr( $video_format ); ?>'>
				</video>
			</div>
			<?php
		}
	} elseif ( in_array( $lesson['video_type'], array( 'youtube', 'vimeo' ), true ) ) {
		$video_id = 'youtube' === $lesson['video_type'] ? ms_plugin_get_youtube_id( $lesson['youtube_url'] ) : ms_plugin_get_vimeo_id( $lesson['vimeo_url'] );
		?>
		<iframe src="<?php // phpcs:disable
		echo esc_attr(
			'youtube' === $lesson['video_type']
				? "https://www.youtube.com/embed/{$video_id}?&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
				: "https://player.vimeo.com/video/{$video_id}?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media"
		);
		?>" frameborder="0" allowfullscreen allowtransparency allow="autoplay"></iframe>
		<?php // phpcs:enable
	} elseif ( in_array( $lesson['video_type'], array( 'presto_player', 'shortcode' ), true ) ) {
		echo 'presto_player' === $lesson['video_type'] && ! empty( $lesson['presto_player_idx'] ) ? do_shortcode( '[presto_player id="' . esc_attr( $lesson['presto_player_idx'] ) . '"]' ) : do_shortcode( $lesson['shortcode'] );
	}
	?>
</div>
