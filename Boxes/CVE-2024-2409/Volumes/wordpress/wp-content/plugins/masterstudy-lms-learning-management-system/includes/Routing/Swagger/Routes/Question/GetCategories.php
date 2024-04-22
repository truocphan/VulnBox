<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Question;

use MasterStudy\Lms\Routing\Swagger\Fields\QuestionCategory;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class GetCategories extends Route implements ResponseInterface {
	public function response(): array {
		return array(
			'categories' => QuestionCategory::as_array(),
		);
	}

	public function get_summary(): string {
		return 'Get Question Categories';
	}

	public function get_description(): string {
		return 'Returns all Question Categories.';
	}
}
