<?php

namespace MasterStudy\Lms\Pro\addons\certificate_builder;

class DemoImporter {
	public function import(): void {
		$is_imported = get_option( 'stm_lms_certificates_imported', '' );

		if ( ! empty( $is_imported ) ) {
			return;
		}

		$logo_id  = $this->upload_image( STM_LMS_PRO_ADDONS . '/certificate_builder/assets/images/logo.png' );
		$sign_id  = $this->upload_image( STM_LMS_PRO_ADDONS . '/certificate_builder/assets/images/sign.png' );
		$logo_url = wp_get_attachment_url( $logo_id );
		$sign_url = wp_get_attachment_url( $sign_id );
		$demos    = array(
			array(
				'title'        => sanitize_text_field( 'Demo 1' ),
				'orientation'  => 'landscape',
				'category'     => 'entire_site',
				'fields'       => '[{"type":"image","content":"' . $logo_url . '","x":"411","y":"58","w":"79","h":"72","styles":{"fontSize":"14px","fontFamily":"Montserrat","color":{"hex":"#000"},"textAlign":"left","textDecoration":"","fontStyle":"","fontWeight":""},"imageId":"1404"},{"type":"text","content":"CERTIFICATE","x":"0","y":"149","w":"900","h":"71","styles":{"fontSize":"60px","fontFamily":"Montserrat","color":{"hex":"#000"},"textAlign":"center","textDecoration":"","fontStyle":"","fontWeight":"true"}},{"type":"student_name","content":"-Student Name-","x":"0","y":"242","w":"900","h":"50","styles":{"fontSize":"28px","fontFamily":"Montserrat","color":{"hex":"#000"},"textAlign":"center","textDecoration":"","fontStyle":"","fontWeight":"true"}},{"type":"course_name","content":"-Course Name-","x":"203","y":"380","w":"494","h":"50","styles":{"fontSize":"20px","fontFamily":"Montserrat","color":{"hex":"#000"},"textAlign":"center","textDecoration":"","fontStyle":"","fontWeight":""}},{"type":"text","content":"Lorem ipsum dolor sit amet,  tempor incididunt ut labore et dolore. Successfully completed courses in:","x":"194","y":"312","w":"512","h":"50","styles":{"fontSize":"14px","fontFamily":"Montserrat","color":{"hex":"#000"},"textAlign":"center","textDecoration":"","fontStyle":"","fontWeight":""}},{"type":"text","content":"Instructor","x":"201","y":"482","w":"150","h":"27","styles":{"fontSize":"14px","fontFamily":"Montserrat","color":{"hex":"#000"},"textAlign":"left","textDecoration":"","fontStyle":"","fontWeight":""}},{"type":"author","content":"-Instructor-","x":"201","y":"464","w":"250","h":"23","styles":{"fontSize":"14px","fontFamily":"Montserrat","color":{"hex":"#000"},"textAlign":"left","textDecoration":"","fontStyle":"","fontWeight":"true"}},{"type":"image","content":"' . $sign_url . '","x":"497","y":"430","w":"107","h":"107","styles":{"fontSize":"14px","fontFamily":"Montserrat","color":{"hex":"#000"},"textAlign":"left","textDecoration":"","fontStyle":"","fontWeight":""},"imageId":"1405"}]',
				'thumbnail_id' => $this->upload_image( STM_LMS_PRO_ADDONS . '/certificate_builder/assets/images/demo-1.png' ),
			),
			array(
				'title'        => sanitize_text_field( 'Demo 2' ),
				'orientation'  => 'portrait',
				'category'     => '',
				'fields'       => '[{"type":"text","content":"CERTIFICATE","x":"0","y":"212","w":"600","h":"80","styles":{"fontSize":"60px","fontFamily":"Montserrat","color":{"hex":"#000"},"textAlign":"center","textDecoration":"","fontStyle":"","fontWeight":"true"}},{"type":"image","content":"' . $logo_url . '","x":"260","y":"75","w":"90","h":"83","styles":{"fontSize":"14px","fontFamily":"Montserrat","color":{"hex":"#000"},"textAlign":"left","textDecoration":"","fontStyle":"","fontWeight":""},"imageId":"1404"},{"type":"student_name","content":"-Student Name-","x":"0","y":"322","w":"600","h":"50","styles":{"fontSize":"32px","fontFamily":"Montserrat","color":{"hex":"#000"},"textAlign":"center","textDecoration":"","fontStyle":"","fontWeight":""}},{"type":"text","content":"Lorem ipsum dolor sit amet,  tempor incididunt ut labore et dolore. Successfully completed courses in:","x":"92","y":"387","w":"430","h":"50","styles":{"fontSize":"14px","fontFamily":"Montserrat","color":{"hex":"#000"},"textAlign":"center","textDecoration":"","fontStyle":"","fontWeight":""}},{"type":"course_name","content":"-Course Name-","x":"163","y":"445","w":"286","h":"50","styles":{"fontSize":"24px","fontFamily":"Montserrat","color":{"hex":"#000"},"textAlign":"center","textDecoration":"","fontStyle":"","fontWeight":""}},{"type":"text","content":"Istructor","x":"126","y":"576","w":"150","h":"33","styles":{"fontSize":"14px","fontFamily":"Montserrat","color":{"hex":"#000"},"textAlign":"left","textDecoration":"","fontStyle":"","fontWeight":""}},{"type":"author","content":"-Instructor-","x":"126","y":"553","w":"223","h":"34","styles":{"fontSize":"14px","fontFamily":"Montserrat","color":{"hex":"#000"},"textAlign":"left","textDecoration":"","fontStyle":"","fontWeight":"true"}},{"type":"image","content":"' . $sign_url . '","x":"345","y":"522","w":"132","h":"138","styles":{"fontSize":"14px","fontFamily":"Montserrat","color":{"hex":"#000"},"textAlign":"left","textDecoration":"","fontStyle":"","fontWeight":""},"imageId":"1405"}]',
				'thumbnail_id' => $this->upload_image( STM_LMS_PRO_ADDONS . '/certificate_builder/assets/images/demo-2.png' ),
			),
		);

		$repository = new CertificateRepository();
		foreach ( $demos as $demo ) {
			$repository->create( wp_slash( $demo ) );
		}

		update_option( 'stm_lms_certificates_imported', '1' );
	}

	protected function upload_image( $path = '' ): int {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$upload_dir = wp_upload_dir();
		$image_data = $wp_filesystem->get_contents( $path );
		$filename   = basename( $path );

		if ( wp_mkdir_p( $upload_dir['path'] ) ) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}
		$wp_filesystem->put_contents( $file, $image_data, FS_CHMOD_FILE );

		$wp_filetype = wp_check_filetype( $filename, null );

		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attach_id = wp_insert_attachment( $attachment, $file );

		if ( is_wp_error( $attach_id ) ) {
			return 0;
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';

		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

		wp_update_attachment_metadata( $attach_id, $attach_data );

		return $attach_id;
	}
}
