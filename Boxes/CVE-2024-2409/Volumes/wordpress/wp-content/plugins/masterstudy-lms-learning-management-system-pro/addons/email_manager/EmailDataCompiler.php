<?php

namespace MasterStudy\Lms\Pro\addons\email_manager;

class EmailDataCompiler {
	/**
	 * @param array $data
	 */
	public static function compile( $data ): array {
		if ( ! isset( $data['vars'] ) || empty( $data['filter_name'] ) ) {
			return $data;
		}
		$vars        = $data['vars'];
		$filter_name = $data['filter_name'];

		$settings = EmailManagerSettings::get_all();

		if ( isset( $settings[ "{$filter_name}_subject" ] ) ) {
			$data['subject'] = $settings[ "{$filter_name}_subject" ];
		}

		if ( empty( $settings[ $filter_name ] ) ) {
			return $data;
		}

		$data['enabled'] = ( ! empty( $settings[ "{$filter_name}_enable" ] ) && $settings[ "{$filter_name}_enable" ] );

		$message = $settings[ $filter_name ];

		preg_match_all( '~\{\{\s*(.*?)\s*\}\}~', $message, $matches );
		if ( empty( $matches[0] ) || empty( $matches[1] ) ) {
			$data['message'] = $message;

			return $data;
		}

		$data['message'] = $message;

		foreach ( $matches[1] as $index => $match ) {
			if ( ! isset( $vars[ $match ] ) ) {
				continue;
			}

			$data['message'] =
				str_replace( $matches[0][ $index ], $vars[ $match ], $data['message'] );
		}

		return $data;
	}
}
