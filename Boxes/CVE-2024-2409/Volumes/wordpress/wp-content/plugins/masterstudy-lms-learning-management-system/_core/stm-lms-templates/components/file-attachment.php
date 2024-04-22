<?php
/**
 * File attachment component.
 *
 * @var array $attachments
 * @var boolean $dark_mode
 * @var boolean $deletable
 * @var boolean $download
 *
 * masterstudy-file-attachment_dark-mode - for dark mode
 *
 * @package masterstudy
 */

wp_enqueue_style( 'masterstudy-file-attachment' );

$download  = isset( $download ) ? (bool) $download : true;
$deletable = isset( $deletable ) ? (bool) $deletable : false;
$dark_mode = isset( $dark_mode ) ? (bool) $dark_mode : false;

$online_play_formats = array(
	'pdf',
	'video',
	'audio',
	'img',
);
if ( ! empty( $attachments ) ) {
	foreach ( $attachments as $attachment ) {
		$file = ms_plugin_attachment_data( $attachment );
		?>
		<div class="masterstudy-file-attachment <?php echo esc_attr( $dark_mode ? 'masterstudy-file-attachment_dark-mode' : '' ); ?>">
			<div class="masterstudy-file-attachment__info">
				<?php
				if ( 'img' === $file['current_format'] ) {
					$attachment_image = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' );
					?>
					<img src="<?php echo esc_url( $attachment_image[0] ); ?>" class="masterstudy-file-attachment__image masterstudy-file-attachment__image_preview">
					<?php
				} else {
					?>
					<img src="<?php echo esc_url( STM_LMS_URL . "/assets/icons/files/new/{$file['current_format']}.svg" ); ?>" class="masterstudy-file-attachment__image">
					<?php
				}
				?>
				<div class="masterstudy-file-attachment__wrapper">
					<?php if ( in_array( $file['current_format'], $online_play_formats, true ) ) { ?>
						<div class="masterstudy-file-attachment__title-wrapper">
							<a href="<?php echo esc_url( $file['url'] ); ?>" target="_blank" class="masterstudy-file-attachment__title">
								<?php echo esc_html( $file['file_title'] ); ?>
							</a>
						</div>
					<?php } else { ?>
						<span class="masterstudy-file-attachment__title">
							<?php echo esc_html( $file['file_title'] ); ?>
						</span>
					<?php } ?>
					<span class="masterstudy-file-attachment__size"><?php echo esc_html( $file['filesize'] . ' ' . $file['filesize_label'] ); ?></span>
					<?php if ( $download ) : ?>
					<a class="masterstudy-file-attachment__link" href="<?php echo esc_url( $file['url'] ); ?>" target="_blank" download>
						<?php echo esc_html__( 'Download', 'masterstudy-lms-learning-management-system' ); ?>
					</a>
					<?php endif; ?>

					<?php if ( $deletable ) : ?>
					<a class="masterstudy-file-attachment__delete" href="#" data-id="<?php echo esc_attr( $file['file_id'] ); ?>"></a>
					<?php endif; ?>
				</div>

			</div>
			<?php
			if ( 'audio' === $file['current_format'] ) {
				STM_LMS_Templates::show_lms_template(
					'components/audio-player',
					array(
						'preloader' => false,
						'src'       => $file['url'],
						'dark_mode' => $dark_mode,
					)
				);
			}
			if ( 'video' === $file['current_format'] ) {
				STM_LMS_Templates::show_lms_template(
					'components/video-player',
					array(
						'src' => $file['url'],
					)
				);
			}
			?>
		</div>
		<?php
	}
}
