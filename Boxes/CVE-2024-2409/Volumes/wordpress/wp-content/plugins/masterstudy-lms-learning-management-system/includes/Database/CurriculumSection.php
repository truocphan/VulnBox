<?php

namespace MasterStudy\Lms\Database;

class CurriculumSection extends AbstractQuery {
	public ?int $id = null;
	public int $course_id;
	public string $title;
	public int $order;

	protected string $sort_by = 'order';

	protected array $fillable = array(
		'id',
		'course_id',
		'title',
		'order',
	);

	public function get_table(): string {
		global $wpdb;

		return $wpdb->prefix . 'stm_lms_curriculum_sections';
	}
}
