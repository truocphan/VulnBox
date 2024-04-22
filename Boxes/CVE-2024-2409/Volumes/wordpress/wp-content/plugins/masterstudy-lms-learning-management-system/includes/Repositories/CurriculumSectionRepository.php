<?php

namespace MasterStudy\Lms\Repositories;

use MasterStudy\Lms\Database\CurriculumSection;

final class CurriculumSectionRepository extends CurriculumRepository {
	/**
	 * @return false|CurriculumSection
	 */
	public function find( int $id ) {
		return ( new CurriculumSection() )->find_one( $id );
	}

	/**
	 * @return array|object
	 */
	public function find_by_ids( array $ids ) {
		return ( new CurriculumSection() )->query()
			->where_in( 'id', $ids )
			->find();
	}

	/**
	 * @return array|int
	 */
	public function get_course_sections( int $course_id, bool $count = false ) {
		return ( new CurriculumSection() )->query()
			->where( 'course_id', $course_id )
			->find( $count );
	}

	public function get_course_section_ids( int $course_id ): array {
		$sections = ( new CurriculumSection() )->query()
			->select( 'id' )
			->where( 'course_id', $course_id )
			->find();

		return array_column( $sections, 'id' );
	}

	public function create( array $data ): CurriculumSection {
		$section            = new CurriculumSection();
		$section->title     = $data['title'];
		$section->course_id = $data['course_id'];
		$section->order     = $data['order'] ?? $this->get_course_sections( $data['course_id'], true ) + 1;
		$section->save();

		return $section;
	}

	public function save( array $data ) {
		$section = $this->find( $data['id'] );

		if ( ! empty( $section ) ) {
			if ( isset( $data['title'] ) ) {
				$section->title = $data['title'];

				do_action(
					'wpml_register_single_string',
					'masterstudy-lms-learning-management-system',
					"section_title_{$data['id']}",
					$data['title']
				);
			}

			if ( isset( $data['order'] ) && $data['order'] !== $section->order ) {
				$this->reorder( new CurriculumSection(), $section, $data['order'] );

				$section->order = $data['order'];
			}

			$section->save();

			return $section;
		}

		return false;
	}

	public function delete( int $id ): bool {
		$section = $this->find( $id );

		if ( ! empty( $section ) ) {
			$this->reorder( new CurriculumSection(), $section );

			return $this->delete_section( $section );
		}

		return false;
	}

	public function delete_section( CurriculumSection $section ): bool {
		$materials = ( new CurriculumMaterialRepository() )->get_section_materials(
			array( $section->id )
		);

		foreach ( $materials as $material ) {
			$material->delete();
		}

		return $section->delete();
	}

	public function delete_course_sections( int $course_id ): void {
		$sections = $this->get_course_sections( $course_id );

		foreach ( $sections as $section ) {
			$this->delete_section( $section );
		}
	}
}
