<?php

namespace MasterStudy\Lms\Repositories;

use MasterStudy\Lms\Enums\LessonVideoType;
use MasterStudy\Lms\Enums\QuestionType;
use MasterStudy\Lms\Plugin\PostType;
use MasterStudy\Lms\Plugin\Taxonomy;
use MasterStudy\Lms\Utility\Traits\VideoTrait;

final class QuestionRepository extends AbstractRepository {
	protected static string $post_type = PostType::QUESTION;

	use VideoTrait;

	protected static array $fields_meta_map = array(
		'answers'           => 'answers',
		'explanation'       => 'question_explanation',
		'image'             => 'image',
		'hint'              => 'question_hint',
		'type'              => 'type',
		'view_type'         => 'question_view_type',
		'embed_ctx'         => 'question_embed_ctx', // @TODO move to filter
		'external_url'      => 'question_ext_link_url',
		'video_poster'      => 'question_video_poster',
		'video'             => 'question_video',
		'video_type'        => 'video_type',
		'presto_player_idx' => 'presto_player_idx',
		'shortcode'         => 'question_shortcode',
		'vimeo_url'         => 'question_vimeo_url',
		'youtube_url'       => 'question_youtube_url',
	);

	protected static array $fields_post_map = array(
		'question' => 'post_title',
		'content'  => 'content',
	);

	protected static array $fields_taxonomy_map = array(
		'categories' => Taxonomy::QUESTION_CATEGORY,
	);

	protected static array $casts = array(
		'answers' => 'list',
		'image'   => 'nullable',
	);

	public function get( $post_id ): ?array {
		$post = parent::get( $post_id );

		if ( null === $post ) {
			return null;
		}

		$meta = get_post_meta( $post['id'] );

		$post = $this->hydrate_video( $post, $meta, 'stm-questions' );

		return apply_filters( 'masterstudy_lms_question_hydrate', $post, array() );
	}

	public function get_all( array $questions ) {
		$list_types = array( QuestionType::SINGLE_CHOICE, QuestionType::MULTI_CHOICE, QuestionType::IMAGE_MATCH );
		$questions  = array_map(
			function ( $question ) use ( $list_types ) {
				$question = $this->get( $question );

				if ( isset( $question['type'] ) && empty( $question['type'] ) ) {
					$question['type'] = QuestionType::SINGLE_CHOICE;
				}

				if ( empty( $question['view_type'] ) && in_array( $question['type'] ?? '', $list_types, true ) ) {
					$question['view_type'] = 'list';
				}

				return $question;
			},
			$questions
		);

		return array_filter( $questions );
	}

	public function create( array $data ): int {
		$data = $this->resolve_bank_categories( $data );
		return parent::create( $data );
	}

	public function update( int $question_id, array $data ): void {
		$data = $this->resolve_bank_categories( $data );
		parent::update( $question_id, $data );
	}

	private function resolve_bank_categories( array $data ) {
		if ( QuestionType::QUESTION_BANK !== $data['type'] || empty( $data['categories'][0] ) ) {
			return $data;
		}

		$categories = (array) $data['categories'];
		if ( ! is_numeric( $categories[0] ) ) {
			return $data;
		}

		$terms = get_terms(
			array(
				'taxonomy' => Taxonomy::QUESTION_CATEGORY,
				'include'  => wp_parse_id_list( $categories ),
			)
		);

		$data['answers'][0]['categories'] = array_map(
			function ( \WP_Term $term ) {
				return $term->to_array();
			},
			$terms
		);

		$data['categories'] = array();

		return $data;
	}

	public static function fill_the_gap_output_data( array $data, bool $show_answers ): array {
		$data = array(
			'id'                       => $data['id'],
			'user_answer'              => ! empty( $data['last_answers']['user_answer'] ) ? explode( ',', $data['last_answers']['user_answer'] ) : array(),
			'text'                     => $data['answers'][0]['text'],
			'matches'                  => stm_lms_get_string_between( $data['answers'][0]['text'], '|', '|' ),
			'answer_field'             => array(),
			'correct_answer'           => array(),
			'correct_user_answer'      => array(),
			'show_correct_user_answer' => array(),
			'is_correct'               => $data['is_correct'],
		);

		if ( ! empty( $data['matches'] ) ) {
			$data_question = array_map(
				function ( $answer ) {
					return "|{$answer['answer']}|";
				},
				$data['matches']
			);

			foreach ( $data_question as $match_index => $match ) {
				$width                                = 'width: ' . ( strlen( $match ) * 8 + 16 ) . 'px';
				$name                                 = "{$data['id']}[{$match_index}]";
				$data['answer_field'][ $match_index ] = "<input type='text' name='{$name}' style='{$width}' />";
			}

			if ( $show_answers ) {
				foreach ( $data['matches'] as $match_index => $match ) {
					$match_index                         = (int) $match_index;
					$match_answer                        = stripslashes( rawurldecode( $match['answer'] ) );
					$data['user_answer'][ $match_index ] = isset( $data['user_answer'][ $match_index ] )
						? stripslashes( rawurldecode( $data['user_answer'][ $match_index ] ) )
						: null;

					$correct = ( isset( $data['user_answer'][ $match_index ] ) && strtolower( $match_answer ) === strtolower( $data['user_answer'][ $match_index ] ) || $data['is_correct'] )
						? 'masterstudy-course-player-fill-the-gap__check-correct'
						: 'masterstudy-course-player-fill-the-gap__check-incorrect';

					$data['correct_answer'][ $match_index ]           = "{$correct}";
					$data['correct_user_answer'][ $match_index ]      = $data['is_correct'] ? $match['answer'] : "{$data['user_answer'][ $match_index ]}";
					$data['show_correct_user_answer'][ $match_index ] = "{$match_answer}";
				}
			}
		}

		return apply_filters( 'masterstudy_lms_fill_gap_question_output_data', $data );
	}

}
