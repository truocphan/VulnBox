<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Lesson;

use MasterStudy\Lms\Routing\Swagger\Fields\Comment;
use MasterStudy\Lms\Routing\Swagger\Fields\CustomFields;
use MasterStudy\Lms\Routing\Swagger\Fields\FileMaterial;
use MasterStudy\Lms\Routing\Swagger\Fields\LessonType;
use MasterStudy\Lms\Routing\Swagger\Fields\LessonVideoType;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class Get extends Route implements RequestInterface, ResponseInterface {
	public function response(): array {
		return array(
			'lesson'        => array(
				'type'       => 'object',
				'properties' => array(
					'type'                               => LessonType::as_response(),
					'title'                              => array(
						'type' => 'string',
					),
					'duration'                           => array(
						'type' => 'string',
					),
					'preview'                            => array(
						'type'        => 'boolean',
						'description' => 'Lesson preview (Lesson will be available to everyone)',
					),
					'content'                            => array(
						'type' => 'string',
					),
					'excerpt'                            => array(
						'type'        => 'string',
						'description' => 'Frontend Description',
					),
					'files'                              => FileMaterial::as_array(),
					'video_type'                         => LessonVideoType::as_response(),
					'embed_ctx'                          => array(
						'type'        => 'string',
						'description' => 'Available for video type embed',
					),
					'external_url'                       => array(
						'type'        => 'string',
						'description' => 'Available for  video type external link',
					),
					'presto_player_idx'                  => array(
						'type'        => 'integer',
						'description' => 'Available for video type presto',
					),
					'shortcode'                          => array(
						'type'        => 'string',
						'description' => 'Available for video type shortcode',
					),
					'youtube_url'                        => array(
						'type'        => 'string',
						'description' => 'Available for video type youtube',
					),
					'video'                              => array(
						'type'        => 'object',
						'properties'  => array(
							'id'    => array(
								'type' => 'integer',
							),
							'title' => array(
								'type' => 'string',
							),
							'type'  => array(
								'type'        => 'string',
								'description' => 'File mime type',
								'example'     => 'video/mp4',
							),
							'url'   => array(
								'type'   => 'string',
								'format' => 'uri',
							),
						),
						'description' => 'Video Attachment. Available for video type html',
					),
					'video_poster'                       => array(
						'type'        => 'object',
						'properties'  => array(
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
						'description' => 'Video Poster Attachment. Available for video type html, ext_link',
					),
					'video_width'                        => array(
						'type'        => 'integer',
						'description' => 'Available for video type html',
					),
					'vimeo_url'                          => array(
						'type'        => 'string',
						'description' => 'Available for video type vimeo',
					),
					'lock_from_start'                    => array(
						'type'        => 'boolean',
						'description' => 'Unlock the lesson after a certain time after the purchase. Available if drip content addon enabled',
					),
					'start_date'                         => array(
						'type'        => 'integer',
						'description' => 'Lesson Start Date. Available if drip content addon enabled',
					),
					'start_time'                         => array(
						'type'        => 'string',
						'description' => 'Lesson Start Time. Available if drip content addon enabled',
					),
					'lock_start_days'                    => array(
						'type'        => 'integer',
						'description' => 'Unlock lesson after purchase (days). Available if drip content addon enabled',
					),
					'stream_url'                         => array(
						'type'        => 'string',
						'description' => 'URL to stream. Available for lesson type stream',
					),
					'stream_start_date'                  => array(
						'type'        => 'integer',
						'description' => 'Available for lesson type stream',
					),
					'stream_start_time'                  => array(
						'type'        => 'string',
						'description' => 'Available for lesson type stream',
					),
					'stream_start_timestamp'             => array(
						'type'        => 'integer',
						'description' => 'UTC timestamp. Available for lesson type stream',
					),
					'stream_end_date'                    => array(
						'type'        => 'integer',
						'description' => 'Available for lesson type stream',
					),
					'stream_end_time'                    => array(
						'type'        => 'string',
						'description' => 'Available for lesson type stream',
					),
					'stream_end_timestamp'               => array(
						'type'        => 'integer',
						'description' => 'UTC timestamp. Available for lesson type stream',
					),
					'zoom_conference_start_date'         => array(
						'type'        => 'integer',
						'nullable'    => true,
						'description' => 'UTC timestamp. Available for lesson type zoom conference',
					),
					'zoom_conference_start_time'         => array(
						'type'        => 'string',
						'nullable'    => true,
						'description' => 'Available for lesson type zoom conference',
					),
					'zoom_conference_timezone'           => array(
						'type'        => 'string',
						'description' => 'Available for lesson type zoom conference',
					),
					'zoom_conference_password'           => array(
						'type'        => 'string',
						'description' => 'Available if lesson type zoom conference',
					),
					'zoom_conference_join_before_host'   => array(
						'type'        => 'boolean',
						'description' => 'Available for lesson type zoom conference',
					),
					'zoom_conference_host_video'         => array(
						'type'        => 'boolean',
						'description' => 'Available for lesson type zoom conference',
					),
					'zoom_conference_participants_video' => array(
						'type'        => 'boolean',
						'description' => 'Available for lesson type zoom conference',
					),
					'zoom_conference_mute_participants'  => array(
						'type'        => 'boolean',
						'description' => 'Available for lesson type zoom conference',
					),
					'zoom_conference_enforce_login'      => array(
						'type'        => 'boolean',
						'description' => 'Available for lesson type zoom conference',
					),
				),
			),
			'comments'      => Comment::as_array(),
			'custom_fields' => CustomFields::as_array(),
		);
	}

	public function request(): array {
		return array();
	}

	public function get_summary(): string {
		return 'Lesson data';
	}

	public function get_description(): string {
		return 'Returns a lesson data';
	}
}
