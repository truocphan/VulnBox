<?php
/**
 * UserCSVExporter class.
 *
 * This class extends the CSVExporter class to provide functionality for
 * exporting user data to a CSV file.
 *
 * @since 1.6.13
 *
 * @package Masteriyo\Exporter
 */

namespace Masteriyo\Exporter;

use Masteriyo\Abstracts\CSVExporter;

class UserCSVExporter extends CSVExporter {

	/**
	 * The data to be exported
	 *
	 * @since 1.6.13
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * Constructor
	 *
	 * @since 1.6.13
	 *
	 * @param array $data The data to be exported
	 */
	public function __construct( array $data ) {
		$this->data = $data;
	}

	/**
	 * Extracts the data for the CSV file.
	 *
	 * @since 1.6.13
	 *
	 * @return array The data extracted for the CSV file.
	 */
	protected function extract_data_for_csv() {

		if ( empty( $this->data ) ) {
			return array();
		}

		$first_item = reset( $this->data );

		if ( ! $first_item ) {
			return array();
		}

		$header = array_keys( $first_item );

		// Convert header values to uppercase.
		$header = array_map(
			function( $item ) {
				return strtoupper( str_replace( '_', ' ', $item ) );
			},
			$header
		);

		$csv_data[] = $header;

		foreach ( $this->data as $user_data ) {
			$csv_data[] = array_values( $user_data );
		}

		return $csv_data;
	}
}
