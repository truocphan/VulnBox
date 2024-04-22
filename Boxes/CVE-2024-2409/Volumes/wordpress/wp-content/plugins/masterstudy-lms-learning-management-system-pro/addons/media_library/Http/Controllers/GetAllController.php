<?php

namespace MasterStudy\Lms\Pro\addons\media_library\Http\Controllers;

use MasterStudy\Lms\Pro\addons\media_library\Http\Serializer\AttachmentSerializer;
use MasterStudy\Lms\Pro\addons\media_library\MediaStorage;
use WP_REST_Request;
use WP_REST_Response;

final class GetAllController {
	public function __invoke( WP_REST_Request $request ) {
		$media_storage = new MediaStorage();

		$args = $request->get_query_params();

		if ( ! current_user_can( 'administrator' ) ) {
			$args['author'] = get_current_user_id();
		}

		$attachments = $media_storage->get( $args );
		$serializer  = new AttachmentSerializer();
		$result      = array(
			'count' => $media_storage->count(),
			'files' => $serializer->collectionToArray( $attachments ),
		);

		return new WP_REST_Response( $result );
	}
}
