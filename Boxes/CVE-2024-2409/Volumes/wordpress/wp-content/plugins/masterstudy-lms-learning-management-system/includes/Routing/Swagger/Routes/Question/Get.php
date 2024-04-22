<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Question;

use MasterStudy\Lms\Routing\Swagger\Fields\Category;
use MasterStudy\Lms\Routing\Swagger\Fields\QuestionType;
use MasterStudy\Lms\Routing\Swagger\Fields\QuestionView;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class Get extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array();
	}

	public function response(): array {
		return array(
			'question' => array(
				'type'       => 'object',
				'properties' => array(
					'id'          => array(
						'type'        => 'integer',
						'description' => 'Question ID',
					),
					'answers'     => array(
						'type'  => 'array',
						'items' => array(
							'type'       => 'object',
							'properties' => array(
								'isTrue'         => array(
									'type'        => 'integer',
									'description' => 'Mark correct answer. Used in single choice, multiple choice, true/false',
								),
								'question'       => array(
									'type'        => 'string',
									'description' => 'Question text. Used in item match, image match',
								),
								'text'           => array(
									'type'        => 'string',
									'description' => 'Answer text. Used in single choice, multiple choice, true/false, fill the gap, keywords',
								),
								'text_image'     => array(
									'type'        => 'object',
									'description' => 'Answer image. Used  single choice, multiple choice, image match',
									'properties'  => array(
										'id'  => array(
											'type' => 'integer',
										),
										'url' => array(
											'type' => 'string',
										),
									),
								),
								'question_image' => array(
									'type'        => 'object',
									'description' => 'Used image match',
									'properties'  => array(
										'id'  => array(
											'type' => 'integer',
										),
										'url' => array(
											'type' => 'string',
										),
									),
								),
								'number'         => array(
									'type'        => 'integer',
									'description' => 'Question bank number of questions',
								),
								'categories'     => array(
									'type'        => 'array',
									'description' => 'Question bank categories of questions',
									'items'       => array(
										'type'       => 'object',
										'properties' => array(
											'term_id'     => array(
												'type' => 'integer',
											),
											'name'        => array(
												'type' => 'string',
											),
											'slug'        => array(
												'type' => 'string',
											),
											'term_group'  => array(
												'type' => 'integer',
											),
											'term_taxonomy_id' => array(
												'type' => 'integer',
											),
											'taxonomy'    => array(
												'type' => 'string',
											),
											'description' => array(
												'type' => 'string',
											),
											'parent'      => array(
												'type' => 'integer',
											),
											'count'       => array(
												'type' => 'integer',
											),
										),
									),
								),
							),
						),
					),
					'categories'  => array(
						'type'  => 'array',
						'items' => Category::as_object(),
					),
					'explanation' => array(
						'type' => 'string',
					),
					'hint'        => array(
						'type' => 'string',
					),
					'image'       => array(
						'type'       => 'object',
						'nullable'   => true,
						'properties' => array(
							'id'  => array(
								'type' => 'integer',
							),
							'url' => array(
								'type' => 'string',
							),
						),
					),
					'question'    => array(
						'type' => 'string',
					),
					'type'        => QuestionType::as_response(),
					'view_type'   => QuestionView::as_response(),
				),
			),
		);
	}

	public function get_summary(): string {
		return 'Returns question';
	}

	public function get_description(): string {
		return 'Returns question';
	}
}
