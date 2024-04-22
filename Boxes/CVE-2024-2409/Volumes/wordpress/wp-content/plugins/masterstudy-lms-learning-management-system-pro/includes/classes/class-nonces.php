<?php

new STM_LMS_Pro_Nonces();

class STM_LMS_Pro_Nonces {
	public function __construct() {
		add_action( 'admin_head', array( $this, 'output_nonces' ) );
		add_action( 'wp_head', array( $this, 'output_nonces' ) );
	}

	public function output_nonces() {
		$nonces = array();

		foreach ( $this->nonces() as $nonce_name ) {
			$nonces[ $nonce_name ] = wp_create_nonce( $nonce_name );
		}
		?>
		<script>
			var stm_lms_pro_nonces = <?php echo wp_json_encode( $nonces ); ?>;
		</script>
		<?php
	}

	public function nonces() {
		return array(
			'stm_lms_pro_install_base',
			'stm_lms_pro_search_courses',
			'stm_lms_pro_udemy_import_courses',
			'stm_lms_pro_udemy_publish_course',
			'stm_lms_pro_udemy_import_curriculum',
			'stm_lms_pro_save_addons',
			'stm_lms_create_announcement',
			'stm_lms_pro_upload_image',
			'stm_lms_pro_get_image_data',
			'stm_lms_pro_save_quiz',
			'stm_lms_pro_save_lesson',
			'stm_lms_pro_save_front_course',
			'stm_lms_get_course_info',
			'stm_lms_get_course_students',

			/*Moved from free*/
			'stm_lms_change_post_status',

			/*Media Library*/
			'stm_lms_media_library_get_files',
			'stm_lms_media_library_get_file_by_id',
			'stm_lms_media_library_delete_file',
			'stm_lms_media_library_search_file',
		);
	}
}
