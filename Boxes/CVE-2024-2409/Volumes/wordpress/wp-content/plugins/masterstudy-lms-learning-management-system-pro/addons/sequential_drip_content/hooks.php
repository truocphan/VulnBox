<?php

use MasterStudy\Lms\Plugin\Addons;
use MasterStudy\Lms\Pro\addons\sequential_drip_content\DripContent;
use MasterStudy\Lms\Routing\Swagger\Fields\Addon;

add_filter(
	'masterstudy_lms_lesson_validation_rules',
	function ( array $rules ) {
		$settings = get_option( DripContent::OPTION_SETTINGS_KEY, array() );

		if ( empty( $settings['lock_before_start'] ) ) {
			return $rules;
		}

		return array_merge(
			$rules,
			array(
				'lock_from_start' => 'required|boolean',
				'start_date'      => 'nullable|integer',
				'start_time'      => 'nullable|time',
				'lock_start_days' => 'required_if_accepted,lock_from_start|integer|min,1',

			)
		);
	}
);

add_filter(
	'masterstudy_lms_lesson_hydrate',
	function ( $lesson, $meta ) {
		$lesson['lock_from_start'] = (bool) ( $meta['lesson_lock_from_start'][0] ?? false );
		$lesson['lock_start_days'] = empty( $meta['lesson_lock_start_days'][0] ) ? null : (int) $meta['lesson_lock_start_days'][0];

		return $lesson;
	},
	10,
	2
);

add_filter(
	'masterstudy_lms_lesson_fields_meta_mapping',
	function ( $mapping ) {
		return array_merge(
			$mapping,
			array(
				'lock_from_start' => 'lesson_lock_from_start',
				'start_date'      => 'lesson_start_date',
				'start_time'      => 'lesson_start_time',
				'lock_start_days' => 'lesson_lock_start_days',
			)
		);
	}
);

add_filter(
	'masterstudy_lms_course_options',
	function ( $options ) {
		$settings = get_option( DripContent::OPTION_SETTINGS_KEY, array() );

		$options[ Addons::DRIP_CONTENT ] = array(
			'locked'            => empty( $settings['locked'] ) ? false : (bool) $settings['locked'],
			'lock_before_start' => empty( $settings['lock_before_start'] ) ? false : (bool) $settings['lock_before_start'],
		);
		return $options;
	}
);
