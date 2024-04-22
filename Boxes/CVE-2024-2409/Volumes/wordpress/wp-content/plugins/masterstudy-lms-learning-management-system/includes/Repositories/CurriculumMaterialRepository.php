<?php

namespace MasterStudy\Lms\Repositories;

use MasterStudy\Lms\Database\CurriculumMaterial;

final class CurriculumMaterialRepository extends CurriculumRepository {
	/**
	 * @return false|CurriculumMaterial
	 */
	public function find( int $id ) {
		return ( new CurriculumMaterial() )->find_one( $id );
	}

	public function find_by_post( int $post_id ) {
		return ( new CurriculumMaterial() )->query()
			->where( 'post_id', $post_id )
			->find();
	}

	/**
	 * @return false|CurriculumMaterial
	 */
	public function find_by_course_lesson( int $course_id, int $post_id ) {
		$section_ids = ( new CurriculumSectionRepository() )->get_course_section_ids( $course_id );
		$materials   = ( new CurriculumMaterial() )->query()
			->where_in( 'section_id', $section_ids )
			->where( 'post_id', $post_id )
			->find();

		return ! empty( $materials ) ? reset( $materials ) : false;
	}

	public function count_by_section( int $section_id ): int {
		return ( new CurriculumMaterial() )->query()
			->where( 'section_id', $section_id )
			->find( true );
	}

	public function count_by_type( array $section_ids, string $post_type ): int {
		return ! empty( $section_ids )
			? ( new CurriculumMaterial() )->query()
				->where_in( 'section_id', $section_ids )
				->where( 'post_type', $post_type )
				->find( true )
			: 0;
	}

	public function get_course_materials( int $course_id, bool $only_ids = true ): array {
		$section_ids = ( new CurriculumSectionRepository() )->get_course_section_ids( $course_id );
		$order_ids   = implode( ',', $section_ids );
		$materials   = ! empty( $section_ids )
			? ( new CurriculumMaterial() )->query()
				->select( $only_ids ? 'post_id' : '*' )
				->where_in( 'section_id', $section_ids )
				->sort_by( "FIELD(section_id,{$order_ids}), `order`" )
				->find( false, 'ARRAY' )
			: array();

		return $only_ids ? array_column( $materials, 'post_id' ) : $materials;
	}

	public function get_section_materials( array $section_ids ): array {
		return ! empty( $section_ids )
			? ( new CurriculumMaterial() )->join_post_title()
				->where_in( 'materials.section_id', $section_ids )
				->find()
			: array();
	}

	public function create( array $data ): CurriculumMaterial {
		$material             = new CurriculumMaterial();
		$material->post_title = get_the_title( $data['post_id'] );
		$material->post_id    = $data['post_id'];
		$material->post_type  = get_post_type( $data['post_id'] );
		$material->section_id = $data['section_id'];
		$material->order      = $data['order'] ?? $this->count_by_section( $data['section_id'] ) + 1;
		$material->save();

		return $material;
	}

	public function save( array $data ) {
		$material = $this->find( $data['id'] );

		if ( ! empty( $material ) ) {
			$this->reorder(
				new CurriculumMaterial(),
				$material,
				( $data['section_id'] === $material->section_id ) ? $data['order'] : null
			);

			$material->order = $data['order'];

			if ( $data['section_id'] !== $material->section_id ) {
				$material->section_id = $data['section_id'];

				$this->reorder( new CurriculumMaterial(), $material, null, true );
			}

			$material->save();

			return $material;
		}

		return false;
	}

	public function delete( int $id ): bool {
		$material = $this->find( $id );

		if ( ! empty( $material ) ) {
			$this->reorder( new CurriculumMaterial(), $material );

			return $material->delete();
		}

		return false;
	}

	public function import( array $data ): array {
		$materials = array();
		$order     = $this->count_by_section( $data['section_id'] );

		foreach ( $data['material_ids'] as $material_id ) {
			$materials[] = $this->create(
				array(
					'post_id'    => $material_id,
					'section_id' => $data['section_id'],
					'order'      => ++$order,
				)
			);
		}

		return $materials;
	}
}
