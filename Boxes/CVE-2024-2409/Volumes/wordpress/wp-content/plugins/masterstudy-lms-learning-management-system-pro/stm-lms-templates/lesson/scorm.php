<?php
/**
 * @var $post_id
 * @var $item_id
 */

stm_lms_register_style( 'scorm/scorm' );
wp_enqueue_script( 'lms-scorm-pipwerks', STM_LMS_URL . 'assets/vendors/scorm/pipwerks.js', '', stm_lms_custom_styles_v(), true );
wp_enqueue_script( 'lms-scorm-package', STM_LMS_URL . 'assets/vendors/scorm/scorm.js', '', stm_lms_custom_styles_v(), true );

$scorm_url  = STM_LMS_Scorm_Packages::get_iframe_url( $post_id );
$scorm_meta = STM_LMS_Scorm_Packages::get_scorm_meta( $post_id );

if ( ! empty( $scorm_url ) ) : ?>
	<div class="stm-lms-course__overlay"></div>

	<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>

	<div class="stm-lms-wrapper scorm">
		<iframe id="stm-lms-scorm-iframe"
				data-src="<?php echo esc_url( $scorm_url ); ?>"
				data-course-id="<?php echo esc_attr( $post_id ); ?>"
				data-scorm-version="<?php echo ( ! empty( $scorm_meta['scorm_version'] ) ) ? esc_attr( $scorm_meta['scorm_version'] ) : '1.2'; ?>"
		></iframe>
	</div>
	<?php
else :
	?>

	<?php
endif;
