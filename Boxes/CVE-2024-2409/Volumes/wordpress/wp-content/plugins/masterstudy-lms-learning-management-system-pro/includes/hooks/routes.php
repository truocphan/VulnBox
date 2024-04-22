<?php

add_filter(
	'stm_lms_custom_routes_config',
	function ( $routes ) {
		$routes['user_url']['sub_pages']['gradebook'] = array(
			'template'         => 'stm-lms-gradebook',
			'protected'        => true,
			'instructors_only' => true,
			'url'              => 'gradebook',
		);

		$routes['user_url']['sub_pages']['enterprise_groups'] = array(
			'template'  => 'stm-lms-enterprise-groups',
			'protected' => true,
			'url'       => 'enterprise-groups',
			'sub_pages' => array(
				'enterprise_group' => array(
					'template'  => 'stm-lms-enterprise-group',
					'protected' => true,
					'var'       => 'group_id',
				),
			),
		);

		$routes['user_url']['sub_pages']['google_meets'] = array(
			'template'  => 'stm-lms-google-meets',
			'protected' => true,
			'url'       => 'google-meets',
		);

		$routes['user_url']['sub_pages']['assignments'] = array(
			'template'  => 'stm-lms-assignments',
			'protected' => true,
			'url'       => 'assignments',
			'sub_pages' => array(
				'assignment' => array(
					'template'  => 'stm-lms-assignment',
					'protected' => true,
					'var'       => 'assignment_id',
				),
			),
		);

		$routes['user_url']['sub_pages']['enrolled_assignments'] = array(
			'template'  => 'stm-lms-enrolled-assignments',
			'protected' => true,
			'url'       => 'enrolled-assignments',
		);

		$routes['user_url']['sub_pages']['user_assignment'] = array(
			'template'  => 'stm-lms-user-assignment',
			'protected' => true,
			'url'       => 'user-assignment',
			'sub_pages' => array(
				'assignment' => array(
					'template'  => 'stm-lms-user-assignment',
					'protected' => true,
					'var'       => 'assignment_id',
				),
			),
		);

		$routes['user_url']['sub_pages']['points_history'] = array(
			'template'  => 'stm-lms-user-points-history',
			'protected' => true,
			'url'       => 'points-history',
		);

		$routes['user_url']['sub_pages']['points_distribution'] = array(
			'template'  => 'stm-lms-user-points-distribution',
			'protected' => true,
			'url'       => 'points-distribution',
		);

		$routes['user_url']['sub_pages']['bundles'] = array(
			'template'  => 'stm-lms-user-bundles',
			'protected' => true,
			'url'       => 'bundles',
			'sub_pages' => array(
				'bundle' => array(
					'template'  => 'stm-lms-user-bundle',
					'protected' => true,
					'var'       => 'bundle_id',
				),
			),
		);

		$routes['user_url']['sub_pages']['payout_statistic'] = array(
			'template'  => 'stm-lms-payout-statistic',
			'protected' => true,
			'url'       => 'payout',
		);

		$routes['user_url']['sub_pages']['manage_google_meet'] = array(
			'template'         => 'course-builder',
			'protected'        => true,
			'instructors_only' => true,
			'url'              => 'edit-google-meet',
			'sub_pages'        => array(
				'edit_course' => array(
					'template'  => 'course-builder',
					'protected' => true,
					'var'       => 'google_meet_id',
				),
			),
		);

		return $routes;
	}
);
