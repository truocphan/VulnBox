<?php

namespace MasterStudy\Lms\Database;

class CurriculumMaterial extends AbstractQuery {
	public ?int $id = null;
	public int $post_id;
	public string $post_type;
	public int $section_id;
	public int $order;

	protected string $sort_by = 'order';

	protected array $fillable = array(
		'id',
		'post_id',
		'post_type',
		'section_id',
		'order',
	);

	public function get_table(): string {
		global $wpdb;

		return $wpdb->prefix . 'stm_lms_curriculum_materials';
	}

	public function join_post_title(): Query {
		global $wpdb;

		return $this->query()
			->select( 'materials.*, posts.`post_title`, postmeta.`meta_value` as `lesson_type`' )
			->asTable( 'materials' )
			->join(
				sprintf(
					'left join %sposts as posts on (posts.ID = materials.post_id)',
					$wpdb->prefix
				)
			)
			->join(
				sprintf(
					"left join %spostmeta as postmeta on (postmeta.post_id = materials.post_id AND postmeta.meta_key = 'type')",
					$wpdb->prefix
				)
			)
			->group_by( 'materials.id' );
	}
}
