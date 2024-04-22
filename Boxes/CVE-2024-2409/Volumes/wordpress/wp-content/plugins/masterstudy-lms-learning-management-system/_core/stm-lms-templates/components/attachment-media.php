<?php
/**
 * @var int $assignment_id
 * @var bool $dark_mode
 * @var bool $instructor_review
 */

$assignment_settings = get_option( 'stm_lms_assignments_settings' );
$is_audio_allowed    = $assignment_settings['assignments_allow_audio_recording'] ?? false;
$is_video_allowed    = $assignment_settings['assignments_allow_video_recording'] ?? false;
$is_files_allowed    = $assignment_settings['assignments_allow_upload_attachments'] ?? false;
$audio_max_size      = $assignment_settings['assignments_audio_recording_maxsize'] ?? '';
$video_max_size      = $assignment_settings['assignments_video_recording_maxsize'] ?? '';
$files_max_size      = $assignment_settings['max_file_size'] ?? '';
$files_max_number    = $assignment_settings['max_files'] ?? '';
$files_extension     = $assignment_settings['files_ext'] ?? '';

wp_enqueue_style( 'masterstudy-attachment-media' );
wp_enqueue_script( 'masterstudy-attachment-media' );
wp_localize_script(
	'masterstudy-attachment-media',
	'media_data',
	array(
		'assignment_id'    => $assignment_id,
		'is_review'        => $instructor_review,
		'audio_max_size'   => $audio_max_size,
		'video_max_size'   => $video_max_size,
		'files_max_size'   => $files_max_size,
		'files_max_number' => $files_max_number,
		'files_extensions' => $files_extension,
		'dark_mode'        => $dark_mode,
		'message'          => array(
			'success' => array(
				'text' => esc_html__( 'Your review has been submitted successfully.', 'masterstudy-lms-learning-management-system' ),
			),
			'error'   => array(
				'title' => esc_html__( 'Error', 'masterstudy-lms-learning-management-system' ),
				'text'  => esc_html__( 'Oops, something went wrong. Please try again later.', 'masterstudy-lms-learning-management-system' ),
			),
			'file'    => array(
				'extension'    => esc_html__( 'Not allowed file extension.', 'masterstudy-lms-learning-management-system' ),
				'size_error'   => esc_html__( 'File is too big.', 'masterstudy-lms-learning-management-system' ),
				'number_error' => esc_html__( 'File limit exceeded.', 'masterstudy-lms-learning-management-system' ),
			),
			'audio'   => array(
				'permission' => esc_html__( 'Please grant access to your microphone in your browser and reload the page', 'masterstudy-lms-learning-management-system' ),
				'size_error' => esc_html__( 'Audio file is too big.', 'masterstudy-lms-learning-management-system' ),
				'download'   => esc_html__( 'Download audio file.', 'masterstudy-lms-learning-management-system' ),
			),
			'video'   => array(
				'permission' => esc_html__( 'Please grant access to your camera and microphone in your browser and reload the page', 'masterstudy-lms-learning-management-system' ),
				'size_error' => esc_html__( 'Video file is too big.', 'masterstudy-lms-learning-management-system' ),
				'download'   => esc_html__( 'Download video file.', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
?>

<div class="masterstudy-attachment-media <?php echo esc_attr( $dark_mode ? 'masterstudy-attachment-media_dark-mode' : '' ); ?>">
	<div class="masterstudy-attachment-media__materials">
		<?php
		if ( $is_files_allowed ) {
			$meta_name   = $instructor_review ? 'instructor_attachments' : 'student_attachments';
			$attachments = STM_LMS_Assignments::get_draft_attachments( $assignment_id, $meta_name );

			STM_LMS_Templates::show_lms_template(
				'components/file-attachment',
				array(
					'attachments' => $attachments,
					'download'    => false,
					'deletable'   => true,
					'dark_mode'   => $dark_mode,
				)
			);
		}
		?>
	</div>
	<div class="masterstudy-attachment-media__container">
		<input type="file" class="masterstudy-file-upload__input" multiple/>
		<?php
		if ( $is_audio_allowed ) {
			STM_LMS_Templates::show_lms_template(
				'components/audio-recorder',
				array(
					'preloader' => false,
				)
			);
		}
		if ( $is_video_allowed ) {
			STM_LMS_Templates::show_lms_template(
				'components/video-recorder',
				array(
					'preloader' => false,
				)
			);
		}
		STM_LMS_Templates::show_lms_template(
			'components/loader',
			array(
				'is_local'  => true,
				'bordered'  => true,
				'dark_mode' => $dark_mode,
			)
		);
		STM_LMS_Templates::show_lms_template(
			'components/progress',
			array(
				'title'     => esc_html__( 'Processing', 'masterstudy-lms-learning-management-system' ),
				'progress'  => 0,
				'dark_mode' => $dark_mode,
				'is_hidden' => true,
			)
		);
		STM_LMS_Templates::show_lms_template(
			'components/message',
			array(
				'id'          => 'message-box',
				'title'       => '',
				'text'        => esc_html__( 'Your review has been submitted successfully.', 'masterstudy-lms-learning-management-system' ),
				'bg'          => 'success',
				'color'       => 'success',
				'link_url'    => '#',
				'icon'        => 'check',
				'show_header' => true,
				'is_vertical' => true,
			)
		);
		if ( $is_files_allowed ) {
			STM_LMS_Templates::show_lms_template(
				'components/alert',
				array(
					'id'                  => 'assignment_file_alert',
					'title'               => esc_html__( 'Delete file', 'masterstudy-lms-learning-management-system' ),
					'text'                => esc_html__( 'Are you sure you want to delete this file?', 'masterstudy-lms-learning-management-system' ),
					'submit_button_text'  => esc_html__( 'Delete', 'masterstudy-lms-learning-management-system' ),
					'cancel_button_text'  => esc_html__( 'Cancel', 'masterstudy-lms-learning-management-system' ),
					'submit_button_style' => 'danger',
					'cancel_button_style' => 'tertiary',
					'dark_mode'           => $dark_mode,
				)
			);
		}
		?>
	</div>
	<div class="masterstudy-attachment-media__actions">
		<?php
		if ( $is_files_allowed ) {
			STM_LMS_Templates::show_lms_template(
				'components/button',
				array(
					'id'            => 'masterstudy-file-upload-field',
					'title'         => esc_html__( 'Attach file', 'masterstudy-lms-learning-management-system' ),
					'link'          => '#',
					'icon_position' => 'left',
					'icon_name'     => 'plus',
					'style'         => 'tertiary',
					'size'          => 'sm',
				)
			);
		}
		if ( $is_audio_allowed ) {
			STM_LMS_Templates::show_lms_template(
				'components/button',
				array(
					'id'            => 'masterstudy-audio-recorder',
					'title'         => esc_html__( 'Record audio', 'masterstudy-lms-learning-management-system' ),
					'link'          => '#',
					'icon_position' => 'left',
					'icon_name'     => 'mic',
					'style'         => 'tertiary',
					'size'          => 'sm',
				)
			);
		}
		if ( $is_video_allowed ) {
			STM_LMS_Templates::show_lms_template(
				'components/button',
				array(
					'id'            => 'masterstudy-video-recorder',
					'title'         => esc_html__( 'Record video', 'masterstudy-lms-learning-management-system' ),
					'link'          => '#',
					'icon_position' => 'left',
					'icon_name'     => 'camera',
					'style'         => 'tertiary',
					'size'          => 'sm',
				)
			);
		}
		?>
		<div class="masterstudy-file-attachment<?php echo esc_attr( $dark_mode ? ' masterstudy-file-attachment_dark-mode' : '' ); ?>" data-id="masterstudy-file-attachment__template">
			<div class="masterstudy-file-attachment__info">
				<img src="" class="masterstudy-file-attachment__image masterstudy-file-attachment__image_preview">
				<div class="masterstudy-file-attachment__wrapper">
					<div class="masterstudy-file-attachment__title-wrapper">
						<span class="masterstudy-file-attachment__title"></span>
					</div>
					<span class="masterstudy-file-attachment__size"></span>
					<a class="masterstudy-file-attachment__delete" href="#" data-id=""></a>
				</div>
			</div>
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/audio-player',
				array(
					'hidden'    => true,
					'dark_mode' => $dark_mode,
				)
			);
			STM_LMS_Templates::show_lms_template(
				'components/video-player',
				array(
					'hidden' => true,
				)
			);
			?>
		</div>
	</div>
</div>
