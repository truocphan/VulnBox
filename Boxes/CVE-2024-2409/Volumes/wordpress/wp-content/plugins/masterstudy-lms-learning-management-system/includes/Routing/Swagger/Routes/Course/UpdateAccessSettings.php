<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course;

use MasterStudy\Lms\Routing\Swagger\Fields\PostStatus;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class UpdateAccessSettings extends Route implements RequestInterface, ResponseInterface {

	public function request(): array {
		return array(
			'expiration'                      => array(
				'type'     => 'boolean',
				'required' => true,
			),
			'coming_soon_show_course_price'   => array(
				'type'     => 'boolean',
				'required' => true,
			),
			'coming_soon_show_course_details' => array(
				'type'        => 'boolean',
				'description' => 'Show or hide course author, category and rating',
			),
			'coming_soon_date'                => array(
				'type'        => 'number',
				'description' => 'Upcoming course start date',
			),
			'coming_soon_email_notification'  => array(
				'type'        => 'boolean',
				'description' => 'Upcoming course email notification status',
			),
			'coming_soon_message'             => array(
				'type'        => 'string',
				'description' => 'Message text for upcoming course countdown',
			),
			'coming_soon_preordering'         => array(
				'type'        => 'boolean',
				'description' => 'Allowing preorder upcoming course',
			),
			'coming_soon_status'              => array(
				'type'        => 'boolean',
				'description' => 'Course upcoming status',
			),
			'coming_soon_time'                => array(
				'type'        => 'string',
				'description' => 'Upcoming course end time',
			),
			'end_time'                        => array(
				'type'        => 'integer',
				'nullable'    => true,
				'description' => 'Required if expiration is true.',
				'minimum'     => 1,
			),
			'shareware'                       => array(
				'type' => 'boolean',
			),
		);
	}

	public function response(): array {
		return array(
			'status' => array(
				'type'    => 'string',
				'example' => 'ok',
			),
		);
	}

	public function get_summary(): string {
		return 'Update course access settings';
	}

	public function get_description(): string {
		return 'Updates course access settings';
	}
}
