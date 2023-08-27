<?php
/**
 * Add Jupiter X admin options.
 *
 * @package JupiterX_Core\Admin
 *
 * @since 1.9.0
 */

add_filter( 'upload_mimes', 'jupiterx_add_extra_mime_types' );

if ( ! function_exists( 'jupiterx_add_extra_mime_types' ) ) {
	/**
	 * Add more mime type.
	 *
	 * @since 1.9.0
	 *
	 * @param array $mimes Current array of mime types..
	 *
	 * @return array Updated array of mime types.
	 */
	function jupiterx_add_extra_mime_types( $mimes ) {

		if ( ! empty( jupiterx_get_option( 'svg_support' ) ) ) {
			$mimes['svg'] = 'image/svg+xml';
		}

		$mimes['zip'] = 'application/zip';

		return $mimes;
	}
}

add_filter( 'wp_check_filetype_and_ext', 'jupiterx_fix_filetype_check', 10, 4 );
/**
 * Fix the mime type filtering issue.
 *
 * @since 1.9.0
 *
 * @param array  $data file data.
 * @param string $file Full path to the file.
 * @param string $filename The name of the file (may differ from $file due to $file being in a tmp.
 * @param array  $mimes Key is the file extension with value as the mime type.
 * @return array Filetype data.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function jupiterx_fix_filetype_check( $data, $file, $filename, $mimes ) {
	if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
		return $data;
	}

	$wp_filetype = wp_check_filetype( $filename, $mimes );

	if ( 'svg' === $wp_filetype['ext'] || 'svgz' === $wp_filetype['ext'] ) {
		$data['ext']  = $wp_filetype['ext'];
		$data['type'] = 'image/svg+xml';
	}

	return $data;
}

