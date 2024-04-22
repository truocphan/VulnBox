<?php

namespace MasterStudy\Lms\Pro\addons\media_library\Http\Controllers;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Pro\addons\media_library\MediaStorage;

final class DeleteController {
	public function __invoke( int $id ) {
		$media_storage = new MediaStorage();

		$attachment = $media_storage->get_by_id( $id );

		if ( ! $attachment ) {
			return WpResponseFactory::not_found();
		}

		if ( ! current_user_can( 'administrator' ) && get_current_user_id() !== (int) $attachment->post_author ) {
			return WpResponseFactory::forbidden();
		}

		$media_storage->delete( $id );

		return WpResponseFactory::ok();
	}
}
