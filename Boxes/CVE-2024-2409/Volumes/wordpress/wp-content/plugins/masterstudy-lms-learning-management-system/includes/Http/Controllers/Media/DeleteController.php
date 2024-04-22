<?php

namespace MasterStudy\Lms\Http\Controllers\Media;

class DeleteController {
	public function __invoke( int $media_id ) {
		if ( ! current_user_can( 'administrator' ) && get_current_user_id() !== intval( get_post_field( 'post_author', $media_id ) ) ) {
			return new \WP_REST_Response(
				array(
					'error_code' => 'delete_media_access_error',
					'message'    => esc_html__( 'You do not have a permission to delete media files', 'masterstudy-lms-learning-management-system' ),
				),
				403
			);
		}

		return new \WP_REST_Response(
			array(
				'status' => (bool) wp_delete_attachment( $media_id ),
			)
		);
	}
}
