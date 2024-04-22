<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course\Curriculum;

use MasterStudy\Lms\Routing\Swagger\Fields\Post;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class ImportSearch extends Route implements RequestInterface, ResponseInterface {
	public function request(): array {
		return array(
			'search' => array(
				'type'     => 'string',
				'nullable' => true,
			),
			'type'   => array(
				'type'        => 'string',
				'description' => 'Post Type slug',
				'enum'        => array( 'stm-lessons', 'stm-quizzes', 'stm-assignments', 'stm-google-meets' ),
				'nullable'    => true,

			),
		);
	}

	public function response(): array {
		return array(
			'results' => Post::as_array(),
		);
	}

	public function get_summary(): string {
		return 'List & Search Curriculum Materials';
	}

	public function get_description(): string {
		return 'List & Search - Lessons, Quizzes, and Assignments.';
	}
}
