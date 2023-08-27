<?php
/**
 * Add form file upload field.
 *
 * @package JupiterX_Core\Raven
 * @since 1.20.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Fields;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Modules\Forms\Module;
/**
 * File upload Field.
 *
 * Initializing the radio field by extending field base abstract class.
 *
 * @since 1.20.0
 */
class File extends Field_Base {

	private static $fixed_files_indices = false;

	/**
	 * Get field type.
	 *
	 * Retrieve the field type.
	 *
	 * @since 1.20.0
	 * @access public
	 *
	 * @return string Field type.
	 */
	public function get_type() {
		return 'file';
	}

	/**
	 * Render content.
	 *
	 * Render the field content.
	 *
	 * @since 1.20.0
	 * @access public
	 */
	public function render_content() {
		$field = $this->field;

		if ( ! empty( $field['allow_multiple_upload'] ) ) {
			$this->widget->add_render_attribute( 'field-' . $this->get_id(), [ 'multiple' => 'multiple' ] );
		}

		if ( ! empty( $field['allow_multiple_upload'] ) ) {
			$this->widget->remove_render_attribute( 'field-' . $this->get_id(), 'name' );
			$this->widget->add_render_attribute( 'field-' . $this->get_id(), [ 'name' => 'fields[' . $this->get_id() . '][]' ] );
		}

		?>
		<div class="raven-field-subgroup <?php echo esc_attr( $field['inline_list'] ); ?>">
			<span class="raven-field-option">
				<input
					oninput="onInvalidRavenFormField(event)"
					oninvalid="onInvalidRavenFormField(event)"
					type="file"
					<?php echo $this->widget->get_render_attribute_string( 'field-' . $this->get_id() ); ?>
				>
			</span>
		</div>
		<?php
	}

	/**
	 * Validate.
	 *
	 * Check the field based on specific validation rules.
	 *
	 * @since 1.20.0
	 * @access public
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param array $field The field data.
	 */
	public static function validate( $ajax_handler, $field ) {
		if ( ! isset( $_FILES['fields'][ $field['_id'] ] ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$fields = $_FILES['fields'];

		self::fix_file_indices();

		$record_field = $fields[ $field['_id'] ];

		$upload_errors = [
			UPLOAD_ERR_OK => __( 'There is no error, the file uploaded with success.', 'jupiterx-core' ),
			/* translators: 1: upload_max_filesize, 2: php.ini */
			UPLOAD_ERR_INI_SIZE => sprintf( __( 'The uploaded file exceeds the %1$s directive in %2$s.', 'jupiterx-core' ), 'upload_max_filesize', 'php.ini' ),
			/* translators: %s: MAX_FILE_SIZE */
			UPLOAD_ERR_FORM_SIZE => sprintf( __( 'The uploaded file exceeds the %s directive that was specified in the HTML form.', 'jupiterx-core' ), 'MAX_FILE_SIZE' ),
			UPLOAD_ERR_PARTIAL => __( 'The uploaded file was only partially uploaded.', 'jupiterx-core' ),
			UPLOAD_ERR_NO_FILE => __( 'No file was uploaded.', 'jupiterx-core' ),
			UPLOAD_ERR_NO_TMP_DIR => __( 'Missing a temporary folder.', 'jupiterx-core' ),
			UPLOAD_ERR_CANT_WRITE => __( 'Failed to write file to disk.', 'jupiterx-core' ),
			/* translators: %s: phpinfo() */
			UPLOAD_ERR_EXTENSION => sprintf( __( 'A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with %s may help.', 'jupiterx-core' ), 'phpinfo()' ),
		];

		if ( ! empty( $field['max_files'] ) ) {
			if ( count( $record_field ) > $field['max_files'] ) {
				$error_message = sprintf(
				/* translators: %d: The number of allowed files. */
					_n( 'You can upload only %d file.', 'You can upload up to %d files.', intval( $field['max_files'] ), 'jupiterx-core' ),
					intval( $field['max_files'] )
				);

				$ajax_handler
					->add_response( 'errors', $error_message, $field['_id'] )
					->set_success( false );

				return;
			}
		}

		foreach ( $record_field as $index => $file ) {
			// not uploaded
			if ( UPLOAD_ERR_NO_FILE === $file['error'] ) {
				return;
			}

			// Has any error with upload the file?
			if ( $file['error'] > UPLOAD_ERR_OK ) {
				$ajax_handler
					->add_response( 'errors', $upload_errors[ $file['error'] ], $field['_id'] )
					->set_success( false );

				return;
			}

			// valid file type?
			if ( ! self::is_file_type_valid( $field, $file ) ) {
				$error_message = __( 'This file type is not allowed.', 'jupiterx-core' );

				$ajax_handler
					->add_response( 'errors', $error_message, $field['_id'] )
					->set_success( false );
			}

			// allowed file size?
			if ( ! self::is_file_size_valid( $field, $file ) ) {
				$error_message = __( 'This file size is not allowed.', 'jupiterx-core' );

				$ajax_handler
					->add_response( 'errors', $error_message, $field['_id'] )
					->set_success( false );
			}
		}
	}


	/**
	 * Validate uploaded file size against allowed file size
	 *
	 * @param array $field
	 * @param       $file
	 *
	 * @return bool
	 * @sine 1.20.0
	 */
	private static function is_file_size_valid( $field, $file ) {
		$allowed_size = ( ! empty( $field['file_sizes'] ) ) ? $field['file_sizes'] : wp_max_upload_size() / pow( 1024, 2 );
		// File size validation
		$file_size_meta   = $allowed_size * pow( 1024, 2 );
		$upload_file_size = $file['size'];

		return ( $upload_file_size < $file_size_meta );
	}

	/**
	 * Validates uploaded file type against allowed file types
	 *
	 * @param array $field
	 * @param       $file
	 *
	 * @return bool
	 * @sine 1.20.0
	 */
	private static function is_file_type_valid( $field, $file ) {
		// File type validation
		if ( empty( $field['file_types'] ) ) {
			$field['file_types'] = 'jpg,jpeg,png,gif,pdf,doc,docx,ppt,pptx,odt,avi,ogg,m4a,mov,mp3,mp4,mpg,wav,wmv';
		}

		$file_extension  = pathinfo( $file['name'], PATHINFO_EXTENSION );
		$file_types_meta = explode( ',', $field['file_types'] );
		$file_types_meta = array_map( 'trim', $file_types_meta );
		$file_types_meta = array_map( 'strtolower', $file_types_meta );
		$file_extension  = strtolower( $file_extension );

		return ( in_array( $file_extension, $file_types_meta, true ) &&
			! in_array( $file_extension, self::get_blacklist_file_ext(), true ) );
	}

	/**
	 * A set of black listed file extensions
	 *
	 * @return array
	 * @sine 1.20.0
	 */
	private static function get_blacklist_file_ext() {
		static $blacklist = false;
		if ( ! $blacklist ) {
			$blacklist = [ 'php', 'php3', 'php4', 'php5', 'php6', 'phps', 'php7', 'phtml', 'shtml', 'pht', 'swf', 'html', 'asp', 'aspx', 'cmd', 'csh', 'bat', 'htm', 'hta', 'jar', 'exe', 'com', 'js', 'lnk', 'htaccess', 'htpasswd', 'phtml', 'ps1', 'ps2', 'py', 'rb', 'tmp', 'cgi' ];

			/**
			 * Forms file types black list.
			 *
			 * Filters the black list of  file types that won’t be uploaded using the forms.
			 *
			 * @since 1.0.0
			 *
			 * @param array $blacklist A black list of file types.
			 */
			$blacklist = apply_filters( 'elementor_pro/forms/filetypes/blacklist', $blacklist );
		}

		return $blacklist;
	}

	/**
	 * Fix multiple files upload indices in global $_FILES array
	 *
	 * @sine 1.20.0
	 * @return  array|void
	 */
	private static function fix_file_indices() {
		if ( self::$fixed_files_indices ) {
			return;
		}
		// a mapping of $_FILES indices for validity checking
		$names = [
			'name',
			'type',
			'tmp_name',
			'error',
			'size',
		];

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$files = isset( $_FILES['fields'] ) ? $_FILES['fields'] : false;

		if ( ! $files ) {
			return;
		}

		// iterate over each uploaded file
		foreach ( $files as $key => $part ) {
			$key = (string) $key;
			if ( in_array( $key, $names, true ) && is_array( $part ) ) {
				foreach ( $part as $position => $value ) {
					if ( is_array( $value ) ) {
						foreach ( $value as $index => $inner_val ) {
							$files[ $position ][ $index ][ $key ] = $inner_val;
						}
					} else {
						$files[ $position ][0][ $key ] = $value;
					}
				}
				// remove old key reference
				unset( $files[ $key ] );
			}
		}
		$_FILES['fields']          = $files;
		self::$fixed_files_indices = true;
	}

	/**
	 * Validate required.
	 *
	 * Check if field is required.
	 *
	 * @since 1.20.0
	 * @access public
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param object $field The field data.
	 */
	public static function validate_required( $ajax_handler, $field ) {
		self::fix_file_indices();

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$fields = isset( $_FILES['fields'] ) ? $_FILES['fields'] : false;

		if ( ! $fields ) {
			return;
		}

		$record_field = $fields[ $field['_id'] ];

		if ( ! empty( $field['required'] ) && UPLOAD_ERR_NO_FILE === $record_field[0]['error'] ) {
			$error = Module::$messages['required'];
		}

		if ( empty( $error ) ) {
			return;
		}

		$ajax_handler
			->add_response( 'errors', $error, $field['_id'] )
			->set_success( false );
	}
}
