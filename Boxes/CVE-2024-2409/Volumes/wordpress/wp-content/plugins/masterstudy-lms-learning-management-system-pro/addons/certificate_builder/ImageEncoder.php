<?php

namespace MasterStudy\Lms\Pro\addons\certificate_builder;

class ImageEncoder {

	/**
	 * @param non-empty-string $file_path
	 * @return string|false
	 */
	public static function to_base64( string $file_path ) {

		if ( file_exists( $file_path ) ) {
			$type = pathinfo( $file_path, PATHINFO_EXTENSION );
			// phpcs:disable WordPress.PHP.DiscouragedPHPFunctions, WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			return 'data:image/' . $type . ';base64,' . base64_encode( file_get_contents( $file_path ) );
			// phpcs:enable WordPress.PHP.DiscouragedPHPFunctions, WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		}

		return false;
	}
}
