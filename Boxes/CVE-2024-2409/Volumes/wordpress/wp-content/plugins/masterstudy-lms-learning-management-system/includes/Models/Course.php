<?php

namespace MasterStudy\Lms\Models;

use WP_User;

class Course {
	public string $access_status;
	public array $category = array();
	public ?int $certificate_id;
	public ?WP_User $co_instructor;
	public ?string $content;
	public ?int $current_students;
	public ?string $duration_info;
	public ?string $excerpt;

	/**
	 * Number of days for time limit
	 * @var int
	 */
	public int $end_time = 0;

	/**
	 * Has time limit
	 * @var bool
	 */
	public bool $expiration = false;
	public array $files     = array();
	public int $id;
	/**
	 * @var array{url: string, width: int, height: int, id: int}|null
	 */
	public ?array $image;
	public bool $is_featured;
	public ?string $level;
	public WP_User $owner;
	/**
	 * @var array{passing_level: int, courses: array<int>}
	 */
	public array $prerequisites = array(
		'courses'       => array(),
		'passing_level' => 0,
	);

	/**
	 * Has trial
	 * @var bool
	 */
	public bool $shareware = false;
	public string $slug;
	public ?string $status;

	public ?int $status_date_start;

	public ?int $status_date_end;

	public string $title;
	public ?string $video_duration;
	public ?int $views;
	public ?string $access_duration;
	public ?string $access_devices;
	public ?string $certificate_info;
}
