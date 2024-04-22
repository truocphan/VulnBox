<?php

namespace MasterStudy\Lms\Pro\addons\prerequisite;

use MasterStudy\Lms\Models\Course;
use MasterStudy\Lms\Plugin;

class Prerequisite implements Plugin\Addon {

	public function get_name(): string {
		return Plugin\Addons::PREREQUISITE;
	}

	public function register( Plugin $plugin ): void {
		add_filter(
			'masterstudy_lms_course_hydrate',
			function ( Course $course ) {
				$repository = new PrerequisiteRepository();

				$course->prerequisites = $repository->get( $course->id );

				return $course;
			}
		);

		$plugin->get_router()->load_routes( __DIR__ . '/routes.php' );
	}
}
