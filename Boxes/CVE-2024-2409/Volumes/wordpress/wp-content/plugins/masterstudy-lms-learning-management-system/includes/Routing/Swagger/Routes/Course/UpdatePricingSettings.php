<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course;

use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class UpdatePricingSettings extends Route implements RequestInterface, ResponseInterface {

	public function request(): array {
		return array(
			'single_sale'            => array(
				'type'        => 'boolean',
				'description' => 'One-time purchase flag',
				'required'    => true,
			),
			'price'                  => array(
				'type'        => 'number',
				'format'      => 'float',
				'description' => 'Required for one-time purchase',
			),
			'sale_price'             => array(
				'type'     => 'number',
				'format'   => 'float',
				'required' => false,
				'nullable' => true,
			),
			'sale_price_dates_start' => array(
				'type'        => 'integer',
				'description' => 'Timestamp for sale start date. Required with sale_price_dates_end',
				'nullable'    => true,
			),
			'sale_price_dates_end'   => array(
				'type'        => 'integer',
				'description' => 'Timestamp for sale end date. Required with sale_price_dates_start',
				'nullable'    => true,
			),
			'points_price'           => array(
				'type'     => 'integer',
				'required' => false,
			),
			'enterprise_price'       => array(
				'type'        => 'number',
				'format'      => 'float',
				'nullable'    => true,
				'description' => 'Required if enterprise price enabled',
			),
			'not_membership'         => array(
				'type'     => 'boolean',
				'required' => true,
			),
			'affiliate_course'       => array(
				'type'     => 'boolean',
				'required' => true,
			),
			'affiliate_course_text'  => array(
				'type'        => 'string',
				'nullable'    => true,
				'description' => 'Required for affiliate course',
			),
			'affiliate_course_link'  => array(
				'type'        => 'string',
				'nullable'    => true,
				'description' => 'Required for affiliate course',
			),
			'price_info'             => array(
				'type'     => 'string',
				'required' => false,
			),
		);
	}

	public function response(): array {
		return array();
	}

	public function get_summary(): string {
		return 'Update course pricing settings';
	}

	public function get_description(): string {
		return 'Updates course pricing settings';
	}
}
