<?php

namespace MasterStudy\Lms\Routing\Swagger;

interface RequestInterface {
	/**
	 * Response Schema Properties
	 */
	public function request(): array;
}
