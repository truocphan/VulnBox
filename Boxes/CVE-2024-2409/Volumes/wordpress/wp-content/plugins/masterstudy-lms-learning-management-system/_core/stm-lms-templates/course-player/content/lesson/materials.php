<?php

/**
 * @var array $attachments
 * @var boolean $dark_mode
 */

wp_enqueue_style( 'masterstudy-course-player-lesson-materials' );
wp_enqueue_script( 'masterstudy-course-player-lesson-materials' );
wp_localize_script(
	'masterstudy-course-player-lesson-materials',
	'materials',
	array(
		'attachments' => $attachments,
	)
);

if ( is_array( $attachments ) ) {
	?>
	<div class="masterstudy-course-player-lesson-materials">
		<h3 class="masterstudy-course-player-lesson-materials__title">
			<?php echo esc_html__( 'Lesson materials', 'masterstudy-lms-learning-management-system' ); ?>
		</h3>
		<?php
		STM_LMS_Templates::show_lms_template(
			'components/file-attachment',
			array(
				'attachments' => $attachments,
				'dark_mode'   => $dark_mode,
			)
		);
		if ( count( $attachments ) > 1 ) {
			?>
			<div class="masterstudy-course-player-lesson-materials__download-all">
				<span class="masterstudy-course-player-lesson-materials__quantity">
					<?php
					/* translators: %d number */
					echo sprintf( esc_html__( '%d items', 'masterstudy-lms-learning-management-system' ), count( $attachments ) );
					?>
				</span>
				<a href="#" class="masterstudy-course-player-lesson-materials__link">
					<?php echo esc_html__( 'Download all', 'masterstudy-lms-learning-management-system' ); ?>
				</a>
			</div>
		<?php } ?>
	</div>
	<?php
}
