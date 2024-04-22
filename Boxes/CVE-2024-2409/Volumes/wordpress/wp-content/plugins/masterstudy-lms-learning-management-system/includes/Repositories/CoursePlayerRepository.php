<?php

namespace MasterStudy\Lms\Repositories;

use MasterStudy\Lms\Plugin\PostType;

final class CoursePlayerRepository {
	public const CONTENT_TYPES = array(
		'stm-lessons'      => 'lesson',
		'stm-quizzes'      => 'quiz',
		'stm-assignments'  => 'assignments',
		'stm-google-meets' => 'google_meet',
	);

	public function get_main_data( string $page_path, int $lesson_id ): array {
		$course           = get_page_by_path( $page_path, OBJECT, PostType::COURSE );
		$post_id          = apply_filters( 'wpml_object_id', $course->ID, 'post' ) ?? $course->ID;
		$user             = \STM_LMS_User::get_current_user();
		$settings         = get_option( 'stm_lms_settings' );
		$lesson_post_type = get_post_type( $lesson_id );
		$curriculum       = ( new CurriculumRepository() )->get_curriculum( $post_id, true );
		$course_materials = array_reduce(
			$curriculum,
			function ( $carry, $section ) {
				return array_merge( $carry, $section['materials'] ?? array() );
			},
			array()
		);
		$material_ids     = array_column( $course_materials, 'post_id' );

		$data = array(
			'post_id'                  => $post_id,
			'item_id'                  => $lesson_id,
			'curriculum'               => $curriculum,
			'material_ids'             => $material_ids,
			'lesson_post_type'         => $lesson_post_type,
			'content_type'             => self::CONTENT_TYPES[ $lesson_post_type ] ?? $lesson_post_type,
			'stm_lms_question_sidebar' => apply_filters( 'stm_lms_show_question_sidebar', true ),
			'course_title'             => $course->post_title,
			'user'                     => $user,
			'has_access'               => \STM_LMS_User::has_course_access( $post_id, $lesson_id ),
			'has_preview'              => \STM_LMS_Lesson::lesson_has_preview( $lesson_id ),
			'is_trial_course'          => get_post_meta( $post_id, 'shareware', true ),
			'trial_lesson_count'       => 0,
			'has_trial_access'         => false,
			'is_enrolled'              => false,
			'user_page_url'            => \STM_LMS_User::user_page_url(),
			'course_url'               => get_permalink( $post_id ),
			'lesson_completed'         => false,
			'lesson_lock_before_start' => false,
			'lesson_locked_by_drip'    => false,
			'is_scorm_course'          => false,
			'last_lesson'              => ! empty( $material_ids ) ? end( $material_ids ) : 0,
			'settings'                 => $settings,
			'theme_fonts'              => $settings['course_player_theme_fonts'] ?? false,
			'discussions_sidebar'      => $settings['course_player_discussions_sidebar'] ?? true,
			'dark_mode'                => $settings['course_player_theme_mode'] ?? false,
		);

		$lesson_types_labels       = $this->get_lesson_labels();
		$data['lesson_type']       = 'lesson' === $data['content_type']
			? get_post_meta( $lesson_id, 'type', true )
			: $data['content_type'];
		$data['lesson_type_label'] = $lesson_types_labels[ $data['lesson_type'] ] ?? '';

		if ( is_user_logged_in() ) {
			$user_mode = get_user_meta( $user['id'], 'masterstudy_course_player_theme_mode', true );
			if ( ! empty( $user_mode ) ) {
				$data['dark_mode'] = $user_mode;
			}

			$data['is_enrolled'] = \STM_LMS_Course::get_user_course( $user['id'], $post_id );

			if ( PostType::QUIZ === $lesson_post_type ) {
				$data['lesson_completed'] = \STM_LMS_Quiz::quiz_passed( $lesson_id, $user['id'] ) ? 'completed' : '';
			} else {
				$data['lesson_completed'] = \STM_LMS_Lesson::is_lesson_completed( $user['id'], $post_id, $lesson_id ) ? 'completed' : '';
			}
		}

		if ( class_exists( '\STM_LMS_Sequential_Drip_Content' ) ) {
			$drip_settings = \STM_LMS_Sequential_Drip_Content::stm_lms_get_settings();

			if ( ! empty( $drip_settings['lock_before_start'] ) && ! \STM_LMS_Sequential_Drip_Content::is_lesson_started( $lesson_id, $post_id ) ) {
				$data['lesson_lock_before_start'] = true;
			}

			if ( \STM_LMS_Sequential_Drip_Content::lesson_is_locked( $post_id, $lesson_id ) ) {
				$data['lesson_locked_by_drip'] = true;
			}
		}

		if ( class_exists( '\STM_LMS_Scorm_Packages' ) ) {
			$data['is_scorm_course'] = \STM_LMS_Scorm_Packages::is_scorm_course( $post_id );
		}

		if ( ! empty( $data['is_trial_course'] ) && 'on' === $data['is_trial_course'] ) {
			$data['course_materials']   = $course_materials;
			$data['shareware_settings'] = get_option( 'stm_lms_shareware_settings' );
			$data['trial_lesson_count'] = $data['shareware_settings']['shareware_count'] ?? 0;
			$data['trial_lessons']      = array_filter(
				$data['course_materials'],
				function ( $lesson ) use ( $data ) {
					return ( $data['trial_lesson_count'] >= $lesson['order'] && $lesson['post_id'] === $data['item_id'] );
				}
			);

			if ( ! empty( $data['trial_lessons'] ) ) {
				$data['has_trial_access'] = true;
			}
		}

		return $data;
	}

	public function hydrate_materials( $materials ) : array {
		$lesson_types_labels = $this->get_lesson_labels();

		if ( ! empty( $materials ) ) {
			return array_map(
				function( $material ) use ( $lesson_types_labels ) {
					$material['post_id']                  = apply_filters( 'wpml_object_id', $material['post_id'], 'post' ) ?? $material['post_id'];
					$material['lesson_type']              = ! empty( $material['lesson_type'] ) ? $material['lesson_type'] : 'text';
					$material['lesson_lock_before_start'] = false;
					$material['lesson_locked_by_drip']    = false;

					if ( PostType::QUIZ === $material['post_type'] ) {
						$material['icon']            = 'quiz';
						$material['questions']       = get_post_meta( $material['post_id'], 'questions', true );
						$material['questions_array'] = ! empty( $material['questions'] ) ? explode( ',', $material['questions'] ) : '';
						$material['label']           = $lesson_types_labels[ self::CONTENT_TYPES[ $material['post_type'] ] ];
					} else {
						$material['icon']     = $material['lesson_type'];
						$material['meta']     = '';
						$material['duration'] = get_post_meta( $material['post_id'], 'duration', true );
						$material['label']    = $lesson_types_labels[ $material['lesson_type'] ];
						if ( PostType::ASSIGNMENT === $material['post_type'] ) {
							$material['icon']  = 'assignments';
							$material['meta']  = '';
							$material['label'] = $lesson_types_labels[ self::CONTENT_TYPES[ $material['post_type'] ] ];
						} elseif ( PostType::GOOGLE_MEET === $material['post_type'] ) {
							$material['icon']  = 'google-meet';
							$material['label'] = $lesson_types_labels[ self::CONTENT_TYPES[ $material['post_type'] ] ];
						}
					}

					return $material;
				},
				$materials
			);
		}

		return array();
	}

	public function get_lesson_labels(): array {
		return array(
			'text'            => esc_html__( 'Text lesson', 'masterstudy-lms-learning-management-system' ),
			'video'           => esc_html__( 'Video lesson', 'masterstudy-lms-learning-management-system' ),
			'quiz'            => esc_html__( 'Quiz', 'masterstudy-lms-learning-management-system' ),
			'assignments'     => esc_html__( 'Assignment', 'masterstudy-lms-learning-management-system' ),
			'stream'          => esc_html__( 'Stream lesson', 'masterstudy-lms-learning-management-system' ),
			'zoom_conference' => esc_html__( 'Zoom lesson', 'masterstudy-lms-learning-management-system' ),
			'google_meet'     => esc_html__( 'Google Meet webinar', 'masterstudy-lms-learning-management-system' ),
		);
	}

	public function get_quiz_data( int $quiz_id ): array {
		$quiz = ( new QuizRepository() )->get( $quiz_id );

		if ( ! $quiz ) {
			return array();
		}

		ob_start();
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo apply_filters( 'the_content', $quiz['content'] );
		$content = str_replace( '../../', site_url() . '/', ob_get_clean() );

		$data = array_merge(
			$quiz,
			array(
				'content'        => $content,
				'user'           => \STM_LMS_User::get_current_user(),
				'question_banks' => array(),
				'quiz_style'     => \STM_LMS_Quiz::get_style( $quiz_id ),
				'duration'       => \STM_LMS_Quiz::get_quiz_duration( $quiz_id ),
				'duration_value' => $quiz['duration'],
			)
		);

		$data['last_quiz']    = ! empty( $data['user']['id'] )
			? \STM_LMS_Helpers::simplify_db_array( stm_lms_get_user_last_quiz( $data['user']['id'], $quiz_id, array( 'progress' ) ) )
			: '';
		$data['progress']     = $data['last_quiz']['progress'] ?? 0;
		$data['passed']       = $data['progress'] >= $data['passing_grade'] && ! empty( $data['progress'] );
		$data['emoji_type']   = $data['progress'] < $data['passing_grade'] ? 'assignments_quiz_failed_emoji' : 'assignments_quiz_passed_emoji';
		$data['show_emoji']   = \STM_LMS_Options::get_option( 'assignments_quiz_result_emoji_show', true ) ?? false;
		$data['emoji_name']   = \STM_LMS_Options::get_option( $data['emoji_type'] );
		$data['show_answers'] = \STM_LMS_Quiz::quiz_passed( $quiz_id ) || ( ! empty( $data['last_quiz'] ) && $quiz['correct_answer'] );

		if ( ! empty( $quiz['questions'] ) ) {
			if ( ! empty( $data['random_questions'] ) ) {
				shuffle( $quiz['questions'] );
			}

			$data['questions'] = ( new QuestionRepository() )->get_all( $quiz['questions'] );

			if ( ! empty( $data['questions'] ) ) {
				$data['questions_quantity'] = count( $data['questions'] );
				$data['questions_for_nav']  = count( $data['questions'] );
				$data['quiz_info']          = stm_lms_get_user_quizzes( $data['user']['id'], $quiz_id );

				foreach ( $data['questions']  as &$question ) {
					$question['title']   = $question['question'];
					$question['content'] = str_replace( '../../', site_url() . '/', stm_lms_filtered_output( $question['content'] ) );

					if ( 'question_bank' === $question['type'] ) {
						if ( ! empty( $question['answers'][0]['categories'] ) && ! empty( $question['answers'][0]['number'] ) ) {
							$bank_args = array(
								'post_type'      => 'stm-questions',
								'posts_per_page' => $question['answers'][0]['number'],
								'post__not_in'   => $quiz['questions'],
								'meta_query'     => array(
									array(
										'key'     => 'type',
										'value'   => 'question_bank',
										'compare' => '!=',
									),
								),
								'tax_query'      => array(
									array(
										'taxonomy' => 'stm_lms_question_taxonomy',
										'field'    => 'slug',
										'terms'    => wp_list_pluck( $question['answers'][0]['categories'], 'slug' ),
									),
								),
							);

							if ( ! empty( $quiz['random_questions'] ) ) {
								$bank_args['orderby'] = 'rand';
							}

							$bank_data = new \WP_Query( $bank_args );
						}

						$data['question_banks'][ $question['id'] ] = $bank_data ?? array();

						if ( ! empty( $data['question_banks'] ) ) {
							$data['questions_for_nav'] += $data['question_banks'][ $question['id'] ]->found_posts > $question['answers'][0]['number']
								? $question['answers'][0]['number'] - 1
								: $data['question_banks'][ $question['id'] ]->found_posts - 1;
						}
					}
				}

				if ( ! empty( $data['quiz_info'] ) ) {
					$last_quiz_info = end( $data['quiz_info'] );
					$sequence       = json_decode( $last_quiz_info['sequency'], true );

					if ( ! empty( $sequence ) && is_array( $sequence ) ) {
						$iteration = 0;

						foreach ( $sequence as $sequence_question ) {
							if ( is_array( $sequence_question ) ) {
								$data['questions_quantity'] += count( $sequence_question );
							}

							$iteration++;
						}

						$data['questions_quantity'] -= $iteration;
					}
				}

				$data['last_answers'] = \STM_LMS_Helpers::set_value_as_key(
					stm_lms_get_quiz_latest_answers(
						$data['user']['id'],
						$quiz_id,
						$data['questions_quantity'],
						array(
							'question_id',
							'user_answer',
							'correct_answer',
						)
					),
					'question_id'
				);
			}
		}

		return $data;
	}
}
