<?php
/**
 * User UserCSVImporter class.
 *
 * This class extends the CSV Importer class to provide functionality for
 * exporting user data to a CSV file.
 *
 * @since 1.6.13
 *
 * @package Masteriyo\Importer
 */

namespace Masteriyo\Importer;

class UserCSVImporter {

	/**
	 * The file path of the CSV file to be imported.
	 *
	 * @since 1.6.13
	 *
	 * @var string
	 */
	protected $file_path;

	/**
	 * Constructor.
	 *
	 * @since 1.6.13
	 *
	 * @param string $file_path The file path of the CSV file to be imported.
	 */
	public function __construct( string $file_path ) {
		$this->file_path = $file_path;
	}

	/**
	 * Import the CSV data with batch processing.
	 *
	 * @since 1.6.13
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function import() {
		if ( ! file_exists( $this->file_path ) || ! is_readable( $this->file_path ) ) {
			return new \WP_Error( 'invalid_file', 'Invalid or unreadable CSV file.' );
		}

		$rows = $this->parse_csv_file();

		if ( is_wp_error( $rows ) ) {
			return $rows;
		}

		if ( empty( $rows ) ) {
			return new \WP_Error( 'invalid_csv_format', 'Invalid CSV format. Unable to process data.' );
		}

		$batch_size = 100;
		$total_rows = count( $rows );

		for ( $i = 0; $i < $total_rows; $i += $batch_size ) {
			$batch_rows = array_slice( $rows, $i, $batch_size );

			foreach ( $batch_rows as $row ) {
				try {
					$result = $this->create_user_from_csv_row( $row );
					if ( is_wp_error( $result ) ) {
						continue;
					}
				} catch ( \Exception $e ) {
					continue;
				}
			}
		}

		return new \WP_REST_Response(
			array(
				'message' => __( 'Import successful.', 'masteriyo' ),
			)
		);
	}

	/**
	 * Parse the CSV file and return its data as an array of associative arrays.
	 *
	 * @since 1.6.13
	 *
	 * @return array
	 */
	protected function parse_csv_file() {
		$header   = null;
		$csv_data = array();

		$wp_filesystem = masteriyo_get_filesystem();

		if ( ! $wp_filesystem || ! $wp_filesystem->exists( $this->file_path ) ) {
			return array(); // Return an empty array if the file doesn't exist.
		}

		$file_content = $wp_filesystem->get_contents( $this->file_path );
		$lines        = explode( "\n", $file_content );

		if ( $lines ) {
			$header = str_getcsv( array_shift( $lines ) );
			$header = array_map(
				function ( $item ) {
					return strtolower( str_replace( ' ', '_', $item ) );
				},
				$header
			);

			foreach ( $lines as $line ) {
				if ( empty( trim( $line ) ) ) {
					continue; // Skip empty lines.
				}
				$data = str_getcsv( $line );
				if ( count( $header ) === count( $data ) ) {
					$csv_data[] = array_combine( $header, $data );
				}
			}
		}

		return $csv_data;
	}



	/**
	 * Create a user from the CSV row data.
	 *
	 * @since 1.6.13
	 *
	 * @param array $row The CSV row data.
	 */
	protected function create_user_from_csv_row( $row ) {
		add_filter( 'masteriyo_registration_is_generate_password', '__return_true' );

		if ( empty( $row['email'] ) || empty( $row['username'] ) || empty( $row['roles'] ) ) {
			return new \WP_Error( 'missing_required_fields', 'Email, username or roles are missing.' );
		}

		if ( ! is_email( $row['email'] ) ) {
			return new \WP_Error( 'invalid_email', 'Invalid email provided.' );
		}

		// Check if the username already exists.
		if ( username_exists( $row['username'] ) ) {
			return new \WP_Error( 'username_exists', 'The username already exists.' );
		}

		// Check if the email already exists.
		if ( email_exists( $row['email'] ) ) {
				return new \WP_Error( 'email_exists', 'The email address already exists.' );
		}

		// Validate allowed fields.
		$allowed_fields = array(
			'username',
			'nicename',
			'email',
			'url',
			'date_created',
			'status',
			'display_name',
			'nickname',
			'first_name',
			'last_name',
			'description',
			'locale',
			'roles',
			'profile_image_id',
			'billing_first_name',
			'billing_last_name',
			'billing_company_name',
			'billing_company_id',
			'billing_address_1',
			'billing_address_2',
			'billing_city',
			'billing_postcode',
			'billing_country',
			'billing_state',
			'billing_email',
			'billing_phone',
		);

		// Sanitize data.
		$row['email']    = sanitize_email( $row['email'] );
		$row['username'] = sanitize_user( $row['username'] );
		$row['roles']    = sanitize_text_field( $row['roles'] );

		// Create the user.
		$user = masteriyo_create_new_user(
			$row['email'],
			$row['username'],
			'',
			$row['roles'],
			array(
				'first_name' => sanitize_text_field( $row['first_name'] ),
				'last_name'  => sanitize_text_field( $row['last_name'] ),
			)
		);

		if ( is_wp_error( $user ) ) {
			return $user;
		}

		// Set additional user data for valid keys only.
		foreach ( $row as $key => $value ) {
			if ( in_array( $key, array( 'id', 'email', 'username', 'role', 'first_name', 'last_name' ), true ) ) {
				continue;
			}

			if ( ! in_array( $key, $allowed_fields, true ) ) {
				continue; // Skip processing if the key is not allowed.
			}

			$method_name = 'set_' . $key;
			if ( is_callable( array( $user, $method_name ) ) ) {
				// Sanitize and set the value.
				$sanitized_value = $this->sanitize_user_data( $key, $value );
				$user->$method_name( $sanitized_value );
			}
		}

		$user->save();

		return $user;
	}


	/**
	 * Sanitize user data based on the field key.
	 *
	 * @since 1.6.13
	 *
	 * @param string $key   The field key.
	 * @param mixed  $value The value to be sanitized.
	 *
	 * @return mixed Sanitized value.
	 */
	protected function sanitize_user_data( $key, $value ) {
		if ( 'date_created' === $key && ! ( $value instanceof \DateTime ) ) {
			try {
				$value = new \DateTime( $value );
				return $value->format( 'Y-m-d H:i:s' );
			} catch ( \Exception $e ) {
				return '';
			}
		}

		return sanitize_text_field( $value );
	}

}
