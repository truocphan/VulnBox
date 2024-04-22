<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course;

use MasterStudy\Lms\Routing\Swagger\Fields\Category;
use MasterStudy\Lms\Routing\Swagger\Fields\Level;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class AddNew extends Route implements ResponseInterface {
	public function response(): array {
		return array(
			'categories'              => Category::as_array(),
			'levels'                  => Level::as_array(),
			'courses_url'             => array(
				'type'        => 'string',
				'format'      => 'uri',
				'description' => 'Courses page URL.',
			),
			'user_account_url'        => array(
				'type'        => 'string',
				'format'      => 'uri',
				'description' => 'User Account dashboard page URL.',
			),
			'dashboard_courses_url'   => array(
				'type'        => 'string',
				'format'      => 'uri',
				'description' => 'Dashboard > LMS > Courses URL.',
			),
			'max_upload_size'         => array(
				'type'        => 'string',
				'description' => 'Maximum media upload size. Human readable.',
				'exmaple'     => '8 MB',
			),
			'is_instructor'           => array(
				'type'        => 'boolean',
				'description' => 'Current user has instructor role.',
			),
			'create_category_allowed' => array(
				'type'        => 'boolean',
				'description' => 'Is an instructor allowed to create new categories.',
			),
		);
	}

	public function get_summary(): string {
		return 'New Course';
	}

	public function get_description(): string {
		return 'Returns Categories and Level for Create a Course form.';
	}
}
