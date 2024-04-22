<?php

namespace MasterStudy\Lms\Repositories;

use MasterStudy\Lms\Http\Serializers\CurriculumMaterialSerializer;
use MasterStudy\Lms\Http\Serializers\CurriculumSectionSerializer;

class CurriculumRepository {
	/**
	 * @param object $db CurriculumSection|CurriculumMaterial
	 * @param object $item CurriculumSection|CurriculumMaterial
	 */
	public function reorder( $db, $item, ?int $new_oder = null, bool $added = false ): void {
		$decrease     = ! $added;
		$where_clause = isset( $item->course_id ) ? 'course_id' : 'section_id';
		$query        = $db->query()
			->where( $where_clause, $item->{$where_clause} )
			->where_not( 'id', $item->id );

		if ( ! empty( $new_oder ) ) {
			$decrease = $new_oder > $item->order;
			$query->where_between( 'order', array( $item->order, $new_oder ) );
		} else {
			$query->where_gte( 'order', $item->order );
		}

		$results = $query->find();

		foreach ( $results as $item ) {
			$item->order += $decrease ? -1 : 1;
			$item->save();
		}
	}

	public function get_curriculum( int $course_id, bool $joined = false ): array {
		$sections  = ( new CurriculumSectionSerializer() )->collectionToArray(
			( new CurriculumSectionRepository() )->get_course_sections( $course_id )
		);
		$materials = ! empty( $sections )
			? ( new CurriculumMaterialSerializer() )->collectionToArray(
				( new CurriculumMaterialRepository() )->get_section_materials( array_column( $sections, 'id' ) )
			)
			: array();

		if ( $joined ) {
			foreach ( $sections as &$section ) {
				$section['materials'] = array_values(
					array_filter(
						$materials,
						function ( $material ) use ( $section ) {
							return $material['section_id'] === $section['id'];
						}
					)
				);
			}

			return $sections;
		} else {
			return apply_filters(
				'masterstudy_lms_course_curriculum',
				compact( 'sections', 'materials' ),
				$course_id
			);
		}
	}

	public function get_lesson_course_ids( int $post_id ): array {
		$materials = ( new CurriculumMaterialRepository() )->find_by_post( $post_id );

		if ( ! empty( $materials ) ) {
			$sections = ( new CurriculumSectionRepository() )->find_by_ids(
				array_column( $materials, 'section_id' )
			);

			return ! empty( $sections )
				? array_unique( array_column( $sections, 'course_id' ) )
				: array();
		}

		return array();
	}

	public function duplicate_curriculum( int $course_id, int $new_course_id, string $target_lang = '' ): void {
		$curriculum_sections = ( new CurriculumRepository() )->get_curriculum( $course_id, true );

		if ( array_key_exists( 'sitepress', $GLOBALS ) && class_exists( 'SitePress' ) ) {
			global $sitepress;

			$wpml_sync_settings = $sitepress->get_setting( \WPML_Element_Sync_Settings_Factory::KEY_POST_SYNC_OPTION, array() );
		}

		foreach ( $curriculum_sections as $section ) {
			$section['course_id'] = $new_course_id;
			$section['title']     = apply_filters(
				'wpml_translate_single_string',
				$section['title'],
				'masterstudy-lms-learning-management-system',
				"section_title_{$section['id']}",
				$target_lang
			);

			$new_section = ( new CurriculumSectionRepository() )->create( $section );

			if ( ! empty( $new_section->id ) ) {
				foreach ( $section['materials'] as $material ) {
					$material['section_id'] = $new_section->id;

					if ( ! empty( $wpml_sync_settings ) ) {
						$return_original_if_missing = 1 !== intval( $wpml_sync_settings[ get_post_type( $material['post_id'] ) ] ?? 0 );
						$material['post_id']        = apply_filters( 'wpml_object_id', $material['post_id'], 'post', $return_original_if_missing, $target_lang );
					}

					if ( ! empty( $material['post_id'] ) ) {
						( new CurriculumMaterialRepository() )->create( $material );
					}
				}
			}
		}
	}
}
