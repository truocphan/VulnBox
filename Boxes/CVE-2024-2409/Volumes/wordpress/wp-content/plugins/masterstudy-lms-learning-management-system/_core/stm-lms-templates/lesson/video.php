<?php
/**
 *
 * @var $id
 */

$lesson_video_type    = get_post_meta( $id, 'video_type', true );
$lesson_pp_idx        = get_post_meta( $id, 'presto_player_idx', true );
$lesson_embed_ctx     = get_post_meta( $id, 'lesson_embed_ctx', true );
$lesson_ext_link_url  = get_post_meta( $id, 'lesson_ext_link_url', true );
$lesson_youtube_url   = get_post_meta( $id, 'lesson_youtube_url', true );
$lesson_vimeo_url     = get_post_meta( $id, 'lesson_vimeo_url', true );
$lesson_video_poster  = get_post_meta( $id, 'lesson_video_poster', true );
$lesson_video_url     = get_post_meta( $id, 'lesson_video_url', true );
$lesson_video         = get_post_meta( $id, 'lesson_video', true );
$lesson_video_width   = get_post_meta( $id, 'lesson_video_width', true );
$lesson_shortcode     = get_post_meta( $id, 'lesson_shortcode', true );
$allowed_sources      = array_keys( ms_plugin_video_sources() );
$lesson_video_classes = '';

$poster = in_array( $lesson_video_type, array( 'html', 'ext_link' ), true ) && ! empty( $lesson_video_poster )
	? stm_lms_get_image_url( $lesson_video_poster )
	: '';

if ( empty( $lesson_video_type ) && ! empty( $lesson_video ) ) {
	$lesson_video_type = 'html';
}
if ( strpos( $lesson_ext_link_url ?? '', 'embed' ) !== false ) {
	$lesson_video_classes = $lesson_video_classes . 'embed_ext ';
}
if ( strpos( $lesson_embed_ctx ?? '', 'embed' ) !== false ) {
	$lesson_video_classes = $lesson_video_classes . 'embed_video ';
}
if ( empty( $poster ) ) {
	$lesson_video_classes = $lesson_video_classes . 'visible ';
}

?>

<div class="stm_lms_video__iframe stm_lms_video <?php echo esc_attr( $lesson_video_classes ); ?>" style="<?php echo esc_attr( empty( $poster ) ? '' : 'background: url("' . esc_url( $poster ) . '")' ); ?>">
	<?php if ( ! empty( $poster ) ) : ?>
		<i class="stm_lms_play"></i>
		<?php
	endif;
	if ( 'embed' === $lesson_video_type && ! empty( $lesson_embed_ctx ) ) {
		$allowed_tags = stm_lms_allowed_html();
		echo wp_kses( htmlspecialchars_decode( $lesson_embed_ctx ), $allowed_tags );
	} elseif ( in_array( $lesson_video_type, array( 'html', 'ext_link' ), true ) ) {
		$uploaded_video = $lesson_ext_link_url;
		$video_type     = 'mp4';

		if ( 'html' === $lesson_video_type ) {
			$uploaded_video = wp_get_attachment_url( $lesson_video );
			$video_type     = explode( '.', $uploaded_video );
			$video_type     = strtolower( end( $video_type ) );
		}

		if ( ! empty( $uploaded_video ) ) {
			if ( strpos( $uploaded_video, 'embed' ) ) {
				?>
				<div id="stm_lms_video" class="embed-url">
					<embed src="<?php echo esc_url( $uploaded_video ); ?>">
				</div>
				<?php
			} else {
				?>
				<video id="stm_lms_video" class="video-js" data-id="<?php echo esc_attr( $id ); ?>" poster="<?php echo esc_url( $poster ); ?>" controls style="<?php echo esc_attr( 'html' === $lesson_video_type && ! empty( $lesson_video_width ) ? 'max-width: ' . $lesson_video_width . 'px' : '' ); ?>">
					<source src="<?php echo esc_url( $uploaded_video ); ?>" type='video/<?php echo esc_attr( $video_type ); ?>'>
				</video>
				<?php
			}
		}
	} elseif ( in_array( $lesson_video_type, array( 'youtube', 'vimeo' ), true ) ) {
		$video_idx = 'youtube' === $lesson_video_type ? ms_plugin_get_youtube_id( $lesson_youtube_url ) : ms_plugin_get_vimeo_id( $lesson_vimeo_url );
		$youtube   = 'https://www.youtube.com/embed/' . $video_idx . '?&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1';
		$vimeo     = 'https://player.vimeo.com/video/' . $video_idx . '?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media';

		if ( $video_idx ) {
			?>
			<iframe src="<?php echo esc_attr( 'youtube' === $lesson_video_type ? $youtube : $vimeo ); ?>" allowfullscreen allowtransparency allow="autoplay"></iframe>
			<?php
		}
	} elseif ( in_array( $lesson_video_type, array( 'presto_player', 'shortcode' ), true ) ) {
		echo 'presto_player' === $lesson_video_type && ! empty( $lesson_pp_idx ) ? do_shortcode( '[presto_player id="' . esc_attr( $lesson_pp_idx ) . '"]' ) : do_shortcode( $lesson_shortcode );
	}
	?>
</div>
