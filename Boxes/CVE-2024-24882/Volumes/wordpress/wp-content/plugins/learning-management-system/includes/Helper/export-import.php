<?php
/**
 * Export/Import helper functions.
 *
 * @since 1.6.13
 * @package Masteriyo\Helper
 */


if ( ! function_exists( 'masteriyo_get_filesystem_and_folder' ) ) {
	/**
	 * A helper method to reduce code repetition.
	 * Gets the filesystem and the export folder.
	 *
	 * @return array The filesystem and the export folder.
	 */
	function masteriyo_get_filesystem_and_folder() {
		$filesystem    = masteriyo_get_filesystem();
		$upload_dir    = wp_upload_dir();
		$export_folder = $upload_dir['basedir'] . '/masteriyo';

		if ( $filesystem && ! $filesystem->is_dir( $export_folder ) ) {
			$filesystem->mkdir( $export_folder );
		}

		return array( $filesystem, $export_folder );
	}
}

