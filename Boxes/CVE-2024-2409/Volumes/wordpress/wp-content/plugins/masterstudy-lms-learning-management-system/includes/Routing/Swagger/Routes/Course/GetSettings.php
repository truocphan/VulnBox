<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course;

use MasterStudy\Lms\Routing\Swagger\Fields\Category;
use MasterStudy\Lms\Routing\Swagger\Fields\CourseStatus;
use MasterStudy\Lms\Routing\Swagger\Fields\CustomFields;
use MasterStudy\Lms\Routing\Swagger\Fields\FileMaterial;
use MasterStudy\Lms\Routing\Swagger\Fields\Level;
use MasterStudy\Lms\Routing\Swagger\Fields\PostStatus;
use MasterStudy\Lms\Routing\Swagger\Fields\User;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class GetSettings extends Route implements RequestInterface, ResponseInterface {

	public function request(): array {
		return array();
	}

	public function response(): array {
		return array(
			'categories'     => Category::as_array(),
			'certificates'   => array(
				'type'  => 'array',
				'items' => array(
					'type'       => 'object',
					'properties' => array(
						'id'    => array(
							'type' => 'integer',
						),
						'label' => array(
							'type' => 'string',
						),
					),
				),
			),
			'course'         => array(
				'type'       => 'object',
				'properties' => array(
					'access_status'                   => PostStatus::as_response(),
					'category'                        => Category::as_list(),
					'certificate_id'                  => array(
						'nullable' => true,
						'type'     => 'integer',
					),
					'co_instructor'                   => array_merge(
						User::as_object(),
						array(
							'nullable' => true,
						)
					),
					'content'                         => array(
						'type' => 'string',
					),
					'current_students'                => array(
						'type'    => 'integer',
						'minimum' => 0,
					),
					'duration_info'                   => array(
						'type'        => 'string',
						'description' => 'Duration info',
						'example'     => '10 hours',
					),
					'end_time'                        => array(
						'type'        => 'integer',
						'minimum'     => 0,
						'description' => 'Course expiration (days)',
					),
					'excerpt'                         => array(
						'type' => 'string',
					),
					'expiration'                      => array(
						'type'        => 'boolean',
						'description' => 'Time limit',
					),
					'coming_soon_show_course_price'   => array(
						'type'        => 'boolean',
						'description' => 'Show or hide upcoming course price',
					),
					'coming_soon_show_course_details' => array(
						'type'        => 'boolean',
						'description' => 'Show or hide upcoming course author, category and rating',
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
					'files'                           => FileMaterial::as_array(),
					'id'                              => array(
						'type' => 'integer',
					),
					'image'                           => array(
						'type'       => 'object',
						'properties' => array(
							'id'    => array(
								'type' => 'integer',
							),
							'title' => array(
								'type' => 'string',
							),
							'type'  => array(
								'type'        => 'string',
								'description' => 'File mime type',
								'example'     => 'image/jpeg',
							),
							'url'   => array(
								'type'   => 'string',
								'format' => 'uri',
							),
						),
					),
					'is_featured'                     => array(
						'type'        => 'boolean',
						'description' => 'Featured Course',
					),
					'owner'                           => User::as_object(),
					'level'                           => array(
						'type'     => 'string',
						'nullable' => true,
					),
					'prerequisites'                   => array(
						'type'       => 'object',
						'properties' => array(
							'courses'       => array(
								'type'  => 'array',
								'items' => array(
									'type'       => 'object',
									'properties' => array(
										'id'    => array(
											'type' => 'integer',
										),
										'title' => array(
											'type' => 'string',
										),
									),
								),
							),
							'passing_level' => array(
								'type' => 'float',
							),
						),
					),
					'shareware'                       => array(
						'type'        => 'boolean',
						'description' => 'Trial Course',
					),
					'slug'                            => array(
						'type' => 'string',
					),
					'status'                          => CourseStatus::as_response(),
					'status_date_end'                 => array(
						'type'     => 'integer',
						'nullable' => true,
					),
					'status_date_start'               => array(
						'type'     => 'integer',
						'nullable' => true,
					),
					'title'                           => array(
						'type' => 'string',
					),
					'video_duration'                  => array(
						'type'        => 'string',
						'description' => 'Video Duration',
						'example'     => '10 hours',
					),
					'views'                           => array(
						'type'    => 'integer',
						'minimum' => 0,
					),
					'access_duration'                 => array(
						'type'        => 'string',
						'description' => 'Access Duration',
						'nullable'    => true,
					),
					'access_devices'                  => array(
						'type'        => 'string',
						'description' => 'Access Device Types',
						'nullable'    => true,
					),
					'certificate_info'                => array(
						'type'        => 'string',
						'description' => 'Certificate Info',
						'nullable'    => true,
					),
				),
			),
			'levels'         => Level::as_array(),
			'featured_quota' => array(
				'type'        => 'integer',
				'description' => 'Featured Courses remained Quote',
			),
			'custom_fields'  => CustomFields::as_array(),
		);
	}

	public function get_summary(): string {
		return 'Course settings';
	}

	public function get_description(): string {
		return 'Returns course settings with additional info about available certificates, statuses etc.';
	}
}
