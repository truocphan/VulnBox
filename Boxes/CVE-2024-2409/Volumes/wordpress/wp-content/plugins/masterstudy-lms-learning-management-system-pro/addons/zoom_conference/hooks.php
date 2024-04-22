<?php

use MasterStudy\Lms\Validation\ConditionalRules;

add_filter(
	'masterstudy_lms_lesson_types',
	function ( $types ) {
		if ( current_user_can( 'administrator' ) || ! empty( get_the_author_meta( 'stm_lms_zoom_host', get_current_user_id() ) ) ) {
			$types[] = 'zoom_conference';
		}

		return $types;
	}
);

add_filter(
	'masterstudy_lms_lesson_validation_rules',
	function ( $rules ) {
		$condition = function ( $data ) {
			return 'zoom_conference' === ( $data['type'] ?? null );
		};
		return array_merge(
			$rules,
			array(
				'zoom_conference_start_date'         => new ConditionalRules( $condition, 'nullable|integer' ),
				'zoom_conference_start_time'         => new ConditionalRules( $condition, 'nullable|time' ),
				'zoom_conference_start_timestamp'    => new ConditionalRules( $condition, 'nullable|integer' ),
				'zoom_conference_timezone'           => new ConditionalRules( $condition, 'nullable|contains_list,' . implode( ';', array_keys( stm_lms_get_timezone_options() ) ) ),
				'zoom_conference_password'           => new ConditionalRules( $condition, 'required|string' ),
				'zoom_conference_join_before_host'   => new ConditionalRules( $condition, 'boolean' ),
				'zoom_conference_host_video'         => new ConditionalRules( $condition, 'boolean' ),
				'zoom_conference_participants_video' => new ConditionalRules( $condition, 'boolean' ),
				'zoom_conference_mute_participants'  => new ConditionalRules( $condition, 'boolean' ),
				'zoom_conference_enforce_login'      => new ConditionalRules( $condition, 'boolean' ),
			)
		);
	}
);

add_filter(
	'masterstudy_lms_lesson_hydrate',
	function ( $lesson, $meta ) {
		if ( 'zoom_conference' === $lesson['type'] ) {
			$lesson['zoom_conference_start_date']         = ! empty( $meta['stream_start_date'][0] ) ? intval( $meta['stream_start_date'][0] ) : null;
			$lesson['zoom_conference_start_time']         = $meta['stream_start_time'][0] ?? null;
			$lesson['zoom_conference_timezone']           = $meta['timezone'][0] ?? null;
			$lesson['zoom_conference_password']           = $meta['stm_password'][0] ?? null;
			$lesson['zoom_conference_join_before_host']   = (bool) ( $meta['join_before_host'][0] ?? false );
			$lesson['zoom_conference_host_video']         = (bool) ( $meta['option_host_video'][0] ?? false );
			$lesson['zoom_conference_participants_video'] = (bool) ( $meta['option_participants_video'][0] ?? false );
			$lesson['zoom_conference_mute_participants']  = (bool) ( $meta['option_mute_participants'][0] ?? false );
			$lesson['zoom_conference_enforce_login']      = (bool) ( $meta['option_enforce_login'][0] ?? false );
		}

		return $lesson;
	},
	10,
	2
);

add_action(
	'masterstudy_lms_save_lesson',
	function ( $post_id, $data ) {
		if ( 'zoom_conference' !== ( $data['type'] ?? null ) ) {
			return;
		}
		if ( ! empty( $data['zoom_conference_start_timestamp'] ) ) {
			$data['zoom_conference_start_date'] = gmdate( 'Y-m-d', $data['zoom_conference_start_timestamp'] );
			$data['zoom_conference_start_time'] = gmdate( 'H:i', $data['zoom_conference_start_timestamp'] );
		}

		$map = array(
			'stream_start_date'         => 'zoom_conference_start_date',
			'stream_start_time'         => 'zoom_conference_start_time',
			'timezone'                  => 'zoom_conference_timezone',
			'stm_password'              => 'zoom_conference_password',
			'join_before_host'          => 'zoom_conference_join_before_host',
			'option_host_video'         => 'zoom_conference_host_video',
			'option_participants_video' => 'zoom_conference_participants_video',
			'option_mute_participants'  => 'zoom_conference_mute_participants',
			'option_enforce_login'      => 'zoom_conference_enforce_login',
		);

		foreach ( $map as $meta_key => $data_key ) {
			if ( isset( $data[ $data_key ] ) ) {
				update_post_meta( $post_id, $meta_key, $data[ $data_key ] );
				$data[ $meta_key ] = $data[ $data_key ];
			}
		}

		if ( ! isset( $data['lesson_excerpt'] ) ) {
			$data['lesson_excerpt'] = '';
		}
	},
	10,
	2
);
