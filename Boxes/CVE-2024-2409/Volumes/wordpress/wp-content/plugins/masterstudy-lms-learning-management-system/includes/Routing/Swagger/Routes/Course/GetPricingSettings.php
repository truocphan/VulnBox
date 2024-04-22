<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course;

use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class GetPricingSettings extends Route implements RequestInterface, ResponseInterface {

	public function request(): array {
		return array();
	}

	public function response(): array {
		return array(
			'pricing' => array(
				'type'       => 'object',
				'properties' => array(
					'single_sale'            => array(
						'type'        => 'boolean',
						'description' => 'One-time purchase flag',
					),
					'price'                  => array(
						'type'   => 'number',
						'format' => 'float',
					),
					'sale_price'             => array(
						'type'   => 'number',
						'format' => 'float',
					),
					'sale_price_dates_end'   => array(
						'type'        => 'integer',
						'description' => 'Timestamp for sale end date',
					),
					'sale_price_dates_start' => array(
						'type'        => 'integer',
						'description' => 'Timestamp for sale start date',
					),
					'enterprise_price'       => array(
						'type'   => 'number',
						'format' => 'float',
					),
					'not_membership'         => array(
						'type' => 'boolean',
					),
					'affiliate_course'       => array(
						'type' => 'boolean',
					),
					'affiliate_course_text'  => array(
						'type' => 'string',
					),
					'affiliate_course_link'  => array(
						'type' => 'string',
					),
					'price_info'             => array(
						'type' => 'string',
					),
				),
			),
		);
	}

	public function get_summary(): string {
		return 'Get course pricing settings';
	}

	public function get_description(): string {
		return 'Returns course pricing settings';
	}
}
