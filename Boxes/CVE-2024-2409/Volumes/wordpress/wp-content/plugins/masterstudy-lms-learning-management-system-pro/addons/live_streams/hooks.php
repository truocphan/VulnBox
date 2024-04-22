<?php

use MasterStudy\Lms\Validation\ConditionalRules;

add_filter(
	'masterstudy_lms_lesson_types',
	function ( $types ) {
		if ( apply_filters( 'stm_lms_live_stream_allowed', true ) ) {
			$types[] = 'stream';
		}

		return $types;
	}
);

add_filter(
	'masterstudy_lms_lesson_validation_rules',
	function ( $rules ) {
		$condition = function ( $data ) {
			return 'stream' === ( $data['type'] ?? null );
		};
		return array_merge(
			$rules,
			array(
				'stream_url'             => new ConditionalRules( $condition, 'required|url' ),
				'stream_start_date'      => new ConditionalRules( $condition, 'nullable|integer' ),
				'stream_start_time'      => new ConditionalRules( $condition, 'nullable|time' ),
				'stream_end_date'        => new ConditionalRules( $condition, 'nullable|integer' ),
				'stream_end_time'        => new ConditionalRules( $condition, 'nullable|time' ),
				/**
				 * @deprecated not used anymore will be removed in future
				 */
				'stream_start_timestamp' => new ConditionalRules( $condition, 'nullable|integer' ),
				'stream_end_timestamp'   => new ConditionalRules( $condition, 'nullable|integer' ),
			)
		);
	}
);

add_filter(
	'masterstudy_lms_lesson_hydrate',
	function ( $lesson, $meta ) {
		if ( 'stream' === $lesson['type'] ) {
			$lesson['stream_url']        = $meta['lesson_stream_url'][0] ?? null;
			$lesson['stream_start_date'] = (int) $meta['stream_start_date'][0] ?? null;
			$lesson['stream_start_time'] = $meta['stream_start_time'][0] ?? null;
			$lesson['stream_end_date']   = (int) $meta['stream_end_date'][0] ?? null;
			$lesson['stream_end_time']   = $meta['stream_end_time'][0] ?? null;

			/**
			 * @deprecated not used anymore will be removed in future
			 */
			$lesson['stream_start_timestamp'] = null;
			$lesson['stream_end_timestamp']   = null;
		}

		return $lesson;
	},
	10,
	2
);

add_action(
	'masterstudy_lms_save_lesson',
	function ( $post_id, $data ) {
		if ( 'stream' !== ( $data['type'] ?? null ) ) {
			return;
		}

		/**
		 * @deprecated timestamp fields not used anymore and will be removed in future
		 */
		if ( ! empty( $data['stream_start_timestamp'] ) ) {
			list ( $data['stream_start_date'], $data['stream_start_time'] ) = explode( ' ', gmdate( 'Y-m-d H:i', $data['stream_start_timestamp'] ) );
		}
		if ( ! empty( $data['stream_end_timestamp'] ) ) {
			list ( $data['stream_end_date'], $data['stream_end_time'] ) = explode( ' ', gmdate( 'Y-m-d H:i', $data['stream_end_timestamp'] ) );
		}

		$map = array(
			'stream_start_date' => 'stream_start_date',
			'stream_start_time' => 'stream_start_time',
			'stream_end_date'   => 'stream_end_date',
			'stream_end_time'   => 'stream_end_time',
			'lesson_stream_url' => 'stream_url',
		);

		foreach ( $map as $meta_key => $data_key ) {
			if ( array_key_exists( $data_key, $data ) ) {
				update_post_meta( $post_id, $meta_key, $data[ $data_key ] );
				$data[ $meta_key ] = $data[ $data_key ];
			}
		}
	},
	10,
	2
);
