<?php
/**
 * Class CSVExporter
 *
 * Abstract class that provides functionality for exporting data to a CSV file.
 *
 * @since 1.6.13
 *
 * @package Masteriyo\Abstracts
 */

namespace Masteriyo\Abstracts;

abstract class CSVExporter {

	/**
	 * @var string $filename The name of the CSV file to be exported.
	 *
	 * @since 1.6.13
	 */
	protected $filename = 'masteriyo-users-export.csv';

	/**
	 * @var string $delimiter The delimiter used in the CSV file.
	 *
	 * @since 1.6.13
	 */
	protected $delimiter = ',';

	/**
	 * Initiates the process of exporting data to a CSV file.
	 *
	 * This method:
	 * 1. Increases the memory limit for the export process.
	 * 2. Removes old export files related to the current user.
	 * 3. Extracts data intended for the CSV.
	 * 4. Writes the extracted data to a CSV file.
	 *
	 * @since 1.6.13
	 *
	 * @return array|null Returns an array containing the 'filepath', 'filename',
	 *                    and 'download_url' of the CSV file if successful.
	 *                    Returns null if the process fails or if there's no data to export.
	 */
	public function export() {
		wp_raise_memory_limit( 'admin' );

		if ( ! $this->remove_old_export_file() ) {
			return null;
		}

		$data = $this->extract_data_for_csv();

		if ( ! empty( $data ) ) {
			return $this->write_data_to_csv( $data );
		}

		return null;
	}

	/**
	 * The method that extracts data for the CSV file.
	 *
	 * It must be implemented in a child class.
	 *
	 * @since 1.6.13
	 *
	 * @return array The data extracted for the CSV file.
	 */
	abstract protected function extract_data_for_csv();

	/**
	 * Set the filename for the CSV file.
	 *
	 * @since 1.6.13
	 *
	 * @param string $filename The name of the file.
	 */
	public function set_filename( $filename ) {
		$this->filename = $filename;
	}

	/**
	 * Set the delimiter for the CSV file.
	 *
	 * @since 1.6.13
	 *
	 * @param string $delimiter The delimiter for the CSV file.
	 */
	public function set_delimiter( $delimiter ) {
		$this->delimiter = $delimiter;
	}

	/**
	 * Writes the provided data array to a CSV file.
	 *
	 * The function writes the data array to a CSV file using the WordPress filesystem API.
	 * After writing, it sets the appropriate permissions for the file.
	 * The function then returns information about the CSV file.
	 *
	 * @since 1.6.13
	 *
	 * @param array $data The data to be written to the CSV file.
	 * @return array|null Returns an array containing the 'filepath', 'filename',
	 *                    and 'download_url' of the CSV file if successful.
	 *                    Returns null if the process fails.
	 */
	protected function write_data_to_csv( $data ) {
		list( $filesystem, $export_folder ) = masteriyo_get_filesystem_and_folder();

		if ( ! $filesystem ) {
			return null;
		}

		$export_file_info = $this->create_export_file( $filesystem, $export_folder );

		if ( ! $export_file_info ) {
			return null;
		}

		$filepath = $export_file_info['filepath'];

		$content = '';
		foreach ( $data as $row ) {
			$formatted_row = array_map( array( $this, 'format_value_for_csv' ), $row );
			$line          = implode( $this->delimiter, $formatted_row ) . "\n";
			$content      .= $line;
		}

		$filesystem->put_contents( $filepath, $content, FILE_APPEND );

		$filesystem->chmod( $filepath, 0644 );

		return $export_file_info;
	}

	/**
	 * Creates a new file for exporting data.
	 *
	 * @since 1.6.13
	 *
	 * @param object $filesystem The WordPress filesystem object.
	 * @param string $export_folder The path to the export folder.
	 *
	 * @return array|null The information of the exported file or null if the process fails.
	 */
	protected function create_export_file( $filesystem, $export_folder ) {
		$filename = sprintf( 'masteriyo-export-users-%s-%s.csv', get_current_user_id(), gmdate( 'Y-m-d-H-i-s' ) );
		$filepath = trailingslashit( $export_folder ) . $filename;

		if ( ! $filesystem->touch( $filepath ) ) {
			return null;
		}

		return array(
			'filepath'     => $filepath,
			'filename'     => $filename,
			'download_url' => trailingslashit( wp_upload_dir()['baseurl'] ) . 'masteriyo/' . $filename,
		);
	}

	/**
	 * Removes old export file.
	 *
	 * @since 1.6.13
	 *
	 * @return bool True if the old export file was successfully removed or false otherwise.
	 */
	protected function remove_old_export_file() {
		list( $filesystem, $export_folder ) = masteriyo_get_filesystem_and_folder();

		if ( ! $filesystem ) {
			return false;
		}

		$export_files = $filesystem->dirlist( $export_folder );
		$prefix       = sprintf( 'masteriyo-export-users-%s-', get_current_user_id() );

		foreach ( $export_files as $file ) {
			if ( strpos( $file['name'], $prefix ) === 0 ) {
				$filesystem->delete( trailingslashit( $export_folder ) . $file['name'] );
			}
		}

		return true;
	}

	/**
	 * Formats value for CSV output.
	 *
	 * @since 1.6.13
	 *
	 * @param mixed $value The value to be formatted.
	 * @return string The formatted value.
	 */
	protected function format_value_for_csv( $value ) {
		if ( is_array( $value ) ) {
			return implode( ',', $value );
		} elseif ( $value instanceof \DateTime ) {
			return $value->format( 'Y-m-d H:i:s' );
		}
		return (string) $value;
	}
}
