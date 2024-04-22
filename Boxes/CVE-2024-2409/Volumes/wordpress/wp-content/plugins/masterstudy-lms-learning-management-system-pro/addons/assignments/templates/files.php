<?php

$attachments = STM_LMS_Assignments::uploaded_attachments( get_the_ID() );

stm_lms_register_style( 'assignment' );

if ( ! empty( $attachments ) ) : ?>

<div class="stm-lms-course__assignment">
	<div class="stm-lms-course__assignemnt-attachments__files">

		<?php
		foreach ( $attachments as $attachment ) :

			if ( empty( $attachment['data'] ) ) {
				continue;
			}
			$data = $attachment['data'];
			?>

			<div class="stm-lms-course__assignemnt-attachments__file loaded">

				<div class="file_uploaded_data">

					<span class="name"><?php echo esc_textarea( $data['name'] ); ?></span>

					<span class="right_part">

						<span class="actions">
							<a href="<?php echo esc_url( $data['link'] ); ?>" download="">
								<i class="fa fa-cloud-download-alt"></i>
							</a>
						</span>

					</span>

				</div>

			</div>

		<?php endforeach; ?>

	</div>
</div>

<?php else : ?>
	<p><?php esc_html_e( 'No attached files', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
<?php endif; ?>
