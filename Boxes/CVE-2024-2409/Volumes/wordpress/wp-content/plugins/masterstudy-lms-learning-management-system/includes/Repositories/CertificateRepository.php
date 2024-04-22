<?php

namespace MasterStudy\Lms\Repositories;

use MasterStudy\Lms\Plugin\PostType;

final class CertificateRepository {
	public function get_all(): array {
		return get_posts(
			array(
				'post_status' => 'publish',
				'post_type'   => PostType::CERTIFICATE,
				'numberposts' => -1,
			)
		);
	}
}
