<?php

namespace MasterStudy\Lms\Repositories;

use MasterStudy\Lms\Plugin\PostType;
use RuntimeException;
use WP_Post;

class QuizRepository {
	private static $fields_meta_map = array(
		'correct_answer'   => 'correct_answer',
		'duration'         => 'duration',
		'duration_measure' => 'duration_measure',
		'excerpt'          => 'lesson_excerpt',
		'passing_grade'    => 'passing_grade',
		'random_questions' => 'random_questions',
		're_take_cut'      => 're_take_cut',
		'style'            => 'quiz_style',
	);

	private static $casts = array(
		'correct_answer'   => 'bool',
		'duration'         => 'int',
		'passing_grade'    => 'float',
		'random_questions' => 'bool',
		're_take_cut'      => 'float',
	);

	public function create( array $data ): int {
		$post_data = array(
			'id'           => 0,
			'post_title'   => $data['title'] ?? '',
			'post_content' => apply_filters( 'masterstudy_lms_map_api_data', $data['content'] ?? '', 'post_content' ),
			'post_status'  => 'publish',
			'post_type'    => PostType::QUIZ,
		);

		$id = wp_insert_post( $post_data, true );

		if ( is_wp_error( $id ) ) {
			throw new RuntimeException( $id->get_error_message() );
		}

		if ( $id ) {
			$this->update_meta( $id, $data );

			do_action( 'masterstudy_lms_save_quiz', $id, $data );
		}

		return $id;
	}

	public function update( $quiz_id, $data ): void {
		$post_data = array(
			'ID'           => $quiz_id,
			'post_title'   => $data['title'] ?? '',
			'post_content' => apply_filters( 'masterstudy_lms_map_api_data', $data['content'] ?? '', 'post_content' ),
		);

		$id = wp_update_post( $post_data, true );

		if ( is_wp_error( $id ) ) {
			throw new RuntimeException( $id->get_error_message() );
		}

		$this->update_meta( $quiz_id, $data );

		do_action( 'masterstudy_lms_save_quiz', $quiz_id, $data );
	}

	public function get( $quiz_id ): ?array {
		$quiz = get_post( $quiz_id );

		if ( null === $quiz || PostType::QUIZ !== $quiz->post_type ) {
			return null;
		}

		$quiz = $this->convert_to_array( $quiz );

		foreach ( self::$fields_meta_map as $field => $meta ) {
			$quiz[ $field ] = $this->cast( $field, get_post_meta( $quiz_id, $meta, true ) );
		}

		if ( empty( $quiz['style'] ) ) {
			$quiz['style'] = 'default';
		}

		$quiz['questions'] = explode( ',', get_post_meta( $quiz_id, 'questions', true ) );

		return $quiz;
	}

	public function exists( $quiz_id ): bool {
		return null !== $this->get( $quiz_id );
	}

	public function delete( $quiz_id ): void {
		$result = wp_delete_post( $quiz_id );

		if ( false === $result ) {
			throw new RuntimeException( 'Failed to delete quiz' );
		}
	}

	public function update_questions( $quiz_id, array $questions ): void {
		$questions = implode( ',', $questions );
		update_post_meta( $quiz_id, 'questions', $questions );
	}

	private function update_meta( $id, $data ): void {
		foreach ( self::$fields_meta_map as $field => $meta ) {
			if ( isset( $data[ $field ] ) ) {
				update_post_meta( $id, $meta, $this->convert_to_meta( $field, $data[ $field ] ) );
			}
		}
		if ( isset( $data['questions'] ) ) {
			$this->update_questions( $id, (array) $data['questions'] ?? array() );
		}
	}

	/**
	 * @return mixed
	 */
	private function convert_to_meta( $field, $value ) {
		switch ( self::$casts[ $field ] ?? '' ) {
			case 'bool':
				return true === $value ? 'on' : '';
			default:
				return $value;
		}
	}

	private function convert_to_array( WP_Post $quiz ): array {
		return array(
			'id'      => $quiz->ID,
			'title'   => $quiz->post_title,
			'content' => $quiz->post_content,
		);
	}

	/**
	 * @return mixed
	 */
	private function cast( $field, $value ) {
		switch ( self::$casts[ $field ] ?? '' ) {
			case 'bool':
				return 'on' === $value;
			case 'int':
				return (int) $value;
			case 'float':
				return (float) $value;
			default:
				return $value;
		}
	}
}
