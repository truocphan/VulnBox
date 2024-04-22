<?php

/**
 * @var $title
 * @var $filename
 * @var $ext
 * @var $filesize
 * @var $filesize_label
 * @var $url
 */

if ( ! empty( $title ) &&
	! empty( $filename ) &&
	! empty( $ext ) &&
	! empty( $filesize ) &&
	! empty( $filesize_label ) &&
	! empty( $url ) ) :

	stm_lms_register_style( 'course/file' );

	$filesize = round( $filesize );
	?>


	<div class="stm_lms_downloadable_content">

		<div class="stm_lms_downloadable_content__inner">

			<div class="stm_lms_downloadable_content__icon stm_lms_downloadable_content__icon_<?php echo esc_attr( $ext ); ?>">
				<?php echo file_get_contents( STM_LMS_PATH . "/assets/icons/files/{$ext}.svg" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents, WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<div class="stm_lms_downloadable_content__row">

				<div class="stm_lms_downloadable_content__left">
					<h4 class="stm_lms_downloadable_content__title"><?php echo wp_kses_post( $title ); ?></h4>
					<!--<span class="stm_lms_downloadable_content__name"><?php /*echo wp_kses_post($filename); */ ?></span>-->
				</div>

				<div class="stm_lms_downloadable_content__right">
					<div class="stm_lms_downloadable_content__size heading_font">
						<span><?php esc_html_e( 'File size:', 'masterstudy-lms-learning-management-system' ); ?></span>
						<strong><?php echo wp_kses_post( "{$filesize} {$filesize_label}" ); ?></strong>
					</div>
					<a class="stm_lms_downloadable_content__url" href="<?php echo esc_url( $url ); ?>" target="_blank" download >
						<i class="lnricons lnricons-download2"></i>
					</a>
				</div>

			</div>

		</div>

	</div>

	<?php
endif;
