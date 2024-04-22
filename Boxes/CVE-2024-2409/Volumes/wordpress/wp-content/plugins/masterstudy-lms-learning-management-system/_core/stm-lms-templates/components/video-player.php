<?php
/**
 * Video player component
 *
 * @var string|int $id  - video id
 * @var string $src - video source
 *
 * @package masterstudy
 */

$src = $src ?? '';

wp_enqueue_style( 'masterstudy-video-player' );
?>
<video src="<?php echo esc_url( $src ); ?>" class="masterstudy-video__player <?php echo esc_html( ! empty( $hidden ) ? 'masterstudy-video__player--hidden' : '' ); ?>" data-id="<?php echo esc_html( $id ?? '' ); ?>" controls>
	<source src="<?php echo esc_url( $src ); ?>" type="video/mp4">
	<source src="<?php echo esc_url( $src ); ?>" type="video/webm">
</video>
