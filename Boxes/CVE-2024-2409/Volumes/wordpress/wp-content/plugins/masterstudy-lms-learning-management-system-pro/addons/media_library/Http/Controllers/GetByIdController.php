<?php

namespace MasterStudy\Lms\Pro\addons\media_library\Http\Controllers;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Pro\addons\media_library\Http\Serializer\AttachmentSerializer;
use MasterStudy\Lms\Pro\addons\media_library\MediaStorage;
use WP_REST_Response;

final class GetByIdController {
	public function __invoke( int $id ): WP_REST_Response {
		$media_storage = new MediaStorage();

		$attachment = $media_storage->get_by_id( $id );

		if ( ! $attachment ) {
			return WpResponseFactory::not_found();
		}

		if ( ! current_user_can( 'administrator' ) && get_current_user_id() !== $attachment->post_author ) {
			return WpResponseFactory::forbidden();
		}

		$serializer = new AttachmentSerializer();

		return new WP_REST_Response(
			array(
				'file' => $serializer->toArray( $attachment ),
			)
		);
	}
}
