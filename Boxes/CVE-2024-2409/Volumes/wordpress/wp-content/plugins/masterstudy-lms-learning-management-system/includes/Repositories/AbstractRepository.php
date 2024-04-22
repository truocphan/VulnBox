<?php

namespace MasterStudy\Lms\Repositories;

use RuntimeException;
use WP_Post;
use WP_Term;

abstract class AbstractRepository {

	/**
	 * Map of fields to post fields.
	 *
	 * @var array<string, string>
	 */
	protected static array $fields_post_map = array();

	/**
	 * Map of fields to post meta fields.
	 *
	 * @var array<string, string>
	 */
	protected static array $fields_meta_map = array();

	/**
	 * Map of fields to post taxonomies.
	 *
	 * @var array<string, string>
	 */
	protected static array $fields_taxonomy_map = array();

	/**
	 * Casts for fields.
	 *
	 * @var array<string, string>
	 */
	protected static array $casts = array();

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected static string $post_type;

	public function create( array $data ): int {
		$post                = $this->map_data( static::$fields_post_map, $data );
		$post['ID']          = 0;
		$post['post_type']   = static::$post_type;
		$post['post_status'] = 'publish';

		$id = wp_insert_post( $post, true );

		if ( is_wp_error( $id ) ) {
			throw new RuntimeException( $id->get_error_message() );
		}

		if ( $id ) {
			$this->update_meta( $id, $data );
			$this->update_taxonomy( $id, $data );
		}

		return $id;
	}

	public function update( int $post_id, array $data ): void {
		$post       = $this->map_data( static::$fields_post_map, $data );
		$post['ID'] = $post_id;

		$id = wp_update_post( $post, true );

		if ( is_wp_error( $id ) ) {
			throw new RuntimeException( $id->get_error_message() );
		}

		$this->update_meta( $post_id, $data );
		$this->update_taxonomy( $post_id, $data );
	}

	public function get( $post_id ): ?array {
		$post = $this->get_post( $post_id );

		if ( null === $post ) {
			return null;
		}

		$post = $this->map_post( $post );

		foreach ( static::$fields_meta_map as $field => $meta ) {
			$post[ $field ] = $this->cast( $field, get_post_meta( $post_id, $meta, true ) );
		}

		foreach ( static::$fields_taxonomy_map as $field => $taxonomy ) {
			$terms = wp_get_post_terms( $post_id, $taxonomy );

			if ( is_wp_error( $terms ) ) {
				throw new RuntimeException( $terms->get_error_message() );
			}

			$post[ $field ] = array_map(
				function ( WP_Term $term ) {
					return array(
						'id'     => $term->term_id,
						'name'   => $term->name,
						'parent' => $term->parent,
					);
				},
				$terms
			);
		}

		$post['id'] = $post_id;
		return $post;
	}

	public function exists( $post_id ): bool {
		return null !== $this->get_post( $post_id );
	}

	public function delete( $post_id ): void {
		$result = wp_delete_post( $post_id );

		if ( false === $result ) {
			throw new RuntimeException( 'Failed to delete post' );
		}
	}

	protected function get_post( $post_id ): ?WP_Post {
		$post = get_post( $post_id );

		if ( $post instanceof WP_Post && static::$post_type === $post->post_type ) {
			return $post;
		}

		return null;
	}

	protected function map_data( array $map, array $data ): array {
		$post_data = array();

		foreach ( $map as $data_key => $post_key ) {
			if ( array_key_exists( $data_key, $data ) ) {
				$post_data[ $post_key ] = apply_filters( 'masterstudy_lms_map_api_data', $data[ $data_key ], $post_key );
			}
		}

		return $post_data;
	}


	protected function update_meta( $id, $data ): void {
		foreach ( static::$fields_meta_map as $field => $meta ) {
			if ( array_key_exists( $field, $data ) ) {
				update_post_meta( $id, $meta, $this->convert_to_meta( $field, $data[ $field ] ) );
			}
		}
	}

	/**
	 * @return mixed
	 */
	protected function convert_to_meta( $field, $value ) {
		switch ( static::$casts[ $field ] ?? '' ) {
			case 'bool':
				return true === $value ? 'on' : '';
			default:
				return apply_filters( 'masterstudy_lms_map_api_data', $value, $field );
		}
	}

	protected function map_post( WP_Post $post ): array {
		$post_array = array();

		foreach ( static::$fields_post_map as $data_key => $post_key ) {
			$post_array[ $data_key ] = $post->$post_key;
		}

		return $post_array;
	}

	/**
	 * @return mixed
	 */
	protected function cast( $field, $value ) {
		switch ( static::$casts[ $field ] ?? '' ) {
			case 'bool':
				return 'on' === $value;
			case 'int':
				return (int) $value;
			case 'float':
				return (float) $value;
			case 'list':
				return '' === $value ? array() : $value;
			case 'nullable':
				return '' === $value ? null : $value;
			default:
				return $value;
		}
	}

	protected function update_taxonomy( $id, $data ): void {
		foreach ( static::$fields_taxonomy_map as $field => $taxonomy ) {
			if ( ! array_key_exists( $field, $data ) ) {
				continue;
			}
			$terms = array_map(
				function ( $term ) {
					if ( is_int( $term ) ) {
						return $term;
					}

					if ( isset( $term['id'] ) ) {
						return $term['id'];
					}

					if ( isset( $term['term_id'] ) ) {
						return $term['term_id'];
					}

					throw new RuntimeException( 'Invalid term' );
				},
				$data[ $field ]
			);
			wp_set_post_terms( $id, $terms, $taxonomy );
		}
	}
}
