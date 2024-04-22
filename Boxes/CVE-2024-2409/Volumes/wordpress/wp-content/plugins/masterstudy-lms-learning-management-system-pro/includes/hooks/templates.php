<?php
add_action( 'stm_lms_template_file', 'stm_lms_template_file_pro', 10, 2 );

function stm_lms_template_file_pro( $path, $template ) {
	return file_exists( STM_LMS_PRO_PATH . $template ) ? STM_LMS_PRO_PATH : $path;
}
