<?php

namespace MasterStudy\Lms\Validation;

/**
 * Inspired by Laravel Validation
 */
class ConditionalRules {

	protected $condition;

	protected $rules;

	/**
	 * @param bool|callable $condition
	 * @param string $rules
	 */
	public function __construct( $condition, $rules ) {
		$this->condition = $condition;
		$this->rules     = $rules;
	}

	public function passes( array $data = array() ) {
		$condition = $this->condition;

		if ( is_callable( $condition ) ) {
			$condition = $condition( $data );
		}

		return $condition;
	}

	public function get_rules() {
		return $this->rules;
	}
}
