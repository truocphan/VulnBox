<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Lesson;

use MasterStudy\Lms\Routing\Swagger\Fields\LessonType;
use MasterStudy\Lms\Routing\Swagger\Fields\LessonVideoType;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class Update extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'type'                               => array_merge(
				LessonType::as_response(),
				array(
					'required' => true,
				)
			),
			'title'                              => array(
				'required' => true,
				'type'     => 'string',
			),
			'duration'                           => array(
				'type' => 'string',
			),
			'preview'                            => array(
				'type' => 'boolean',
			),
			'content'                            => array(
				'type'        => 'string',
				'description' => 'Required if lesson type text',
			),
			'excerpt'                            => array(
				'type'        => 'string',
				'description' => 'Frontend Description',
			),
			'video_type'                         => array_merge(
				LessonVideoType::as_response(),
				array(
					'description' => 'Required if lesson type video',
				)
			),
			'embed_ctx'                          => array(
				'type'        => 'string',
				'description' => 'For video type embed',
			),
			'external_url'                       => array(
				'type'        => 'string',
				'description' => 'For video type external link',
			),
			'presto_player_idx'                  => array(
				'type'        => 'integer',
				'description' => 'For video type presto',
			),
			'shortcode'                          => array(
				'type'        => 'string',
				'description' => 'For video type shortcode',
			),
			'youtube_url'                        => array(
				'type'        => 'string',
				'description' => 'For video type youtube',
			),
			'video'                              => array(
				'type'        => 'integer',
				'description' => 'Attachment id. For video type html',
			),
			'video_poster'                       => array(
				'type'        => 'integer',
				'description' => 'Attachment id. For video type html, ext_link',
			),
			'video_width'                        => array(
				'type'        => 'integer',
				'description' => 'For video type html',
			),
			'vimeo_url'                          => array(
				'type'        => 'string',
				'description' => 'For video type vimeo',
			),
			'files'                              => array(
				'type'        => 'array',
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'id'    => array(
							'description' => 'Attachment ID',
							'required'    => true,
							'type'        => 'integer',
						),
						'label' => array(
							'required' => true,
							'type'     => 'string',
						),
					),
				),
				'description' => 'List of files',
			),
			'custom_fields'                      => array(
				'type'        => 'object',
				'properties'  => array(
					'custom-field-key' => array(
						'description' => 'Custom Field key & value',
						'type'        => 'custom-field-value',
					),
				),
				'description' => 'List of Custom Fields',
			),
			'lock_from_start'                    => array(
				'type'        => 'boolean',
				'description' => 'Available if drip content addon enabled',
			),
			'start_date'                         => array(
				'type'        => 'integer',
				'description' => 'Available if drip content addon enabled',
			),
			'start_time'                         => array(
				'type'        => 'string',
				'description' => 'Available if drip content addon enabled',
				'example'     => '14:00',
			),
			'lock_start_days'                    => array(
				'type'        => 'integer',
				'description' => 'Available if drip content addon enabled',
			),
			'stream_url'                         => array(
				'type'        => 'string',
				'description' => 'URL to stream. Required if lesson type stream',
			),
			'stream_start_date'                  => array(
				'type'        => 'integer',
				'description' => 'For lesson type stream and stream start timestamp is not set',
			),
			'stream_start_time'                  => array(
				'type'        => 'string',
				'description' => 'For lesson type stream and stream start timestamp is not set',
			),
			'stream_start_timestamp'             => array(
				'type'        => 'integer',
				'description' => 'UTC timestamp. For lesson type stream and stream start date/time is not set',
			),
			'stream_end_date'                    => array(
				'type'        => 'integer',
				'description' => 'For lesson type stream and stream end date/time is not set',
			),
			'stream_end_time'                    => array(
				'type'        => 'string',
				'description' => 'For lesson type stream and stream end date/time is not set',
			),
			'stream_end_timestamp'               => array(
				'type'        => 'integer',
				'description' => 'UTC timestamp. For lesson type stream and stream end date/time is not set',
			),
			'zoom_conference_start_date'         => array(
				'type'        => 'integer',
				'nullable'    => true,
				'description' => 'UTC timestamp. For lesson type zoom conference and start timestamp is not set',
			),
			'zoom_conference_start_time'         => array(
				'type'        => 'string',
				'nullable'    => true,
				'description' => 'For lesson type zoom conference and start timestamp is not set',
			),
			'zoom_conference_start_timestamp'    => array(
				'type'        => 'integer',
				'nullable'    => true,
				'description' => 'UTC timestamp. For lesson type zoom conference and start date/time is not set',
			),
			'zoom_conference_timezone'           => array(
				'type'        => 'string',
				'description' => 'For lesson type zoom conference',
			),
			'zoom_conference_password'           => array(
				'type'        => 'string',
				'description' => 'Required if lesson type zoom conference',
			),
			'zoom_conference_join_before_host'   => array(
				'type'        => 'boolean',
				'description' => 'For lesson type zoom conference',
			),
			'zoom_conference_host_video'         => array(
				'type'        => 'boolean',
				'description' => 'For lesson type zoom conference',
			),
			'zoom_conference_participants_video' => array(
				'type'        => 'boolean',
				'description' => 'For lesson type zoom conference',
			),
			'zoom_conference_mute_participants'  => array(
				'type'        => 'boolean',
				'description' => 'For lesson type zoom conference',
			),
			'zoom_conference_enforce_login'      => array(
				'type'        => 'boolean',
				'description' => 'For lesson type zoom conference',
			),
		);
	}

	public function response(): array {
		return array(
			'status' => 'ok',
		);
	}

	public function get_summary(): string {
		return 'Update a Lesson';
	}

	public function get_description(): string {
		return 'Update a Lesson';
	}
}
