<?php
/**
 * Add Ajax Handler.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Classes;

use Elementor\Plugin as Elementor;
use JupiterX_Core\Raven\Modules\Forms\Module;
use JupiterX_Core\Raven\Modules\Forms\Actions;

defined( 'ABSPATH' ) || die();

/**
 * Ajax Handler.
 *
 * Initializing the ajax handler class for handling form ajax requests.
 *
 * @since 1.0.0
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Ajax_Handler {

	/**
	 * Response.
	 *
	 * Holds all the responses.
	 *
	 * @access private
	 *
	 * @var array
	 */
	public $response = [
		'message' => [],
		'errors' => [],
		'admin_errors' => [],
	];

	/**
	 * Form.
	 *
	 * Holds the form settings.
	 *
	 * @access private
	 *
	 * @var array
	 */
	public $form;

	/**
	 * Record.
	 *
	 * Holds a record of a form.
	 *
	 * @access private
	 *
	 * @var array
	 */
	public $record;

	/**
	 * Is success.
	 *
	 * Holds the reponse state.
	 *
	 * @access private
	 *
	 * @var array
	 */
	public $is_success = true;

	/**
	 * Holds the uploaded files.
	 *
	 * @access public
	 *
	 * @var array
	 */
	public $uploaded_files = [];

	/**
	 * Ajax handler constructor.
	 *
	 * Initializing the ajax handler class by hooking in ajax actions.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'wp_ajax_raven_form_frontend', [ $this, 'handle_frontend' ] );
		add_action( 'wp_ajax_nopriv_raven_form_frontend', [ $this, 'handle_frontend' ] );
		add_action( 'wp_ajax_raven_form_editor', [ $this, 'handle_editor' ] );
	}

	/**
	 * Handle frontend requests.
	 *
	 * Handle the form submit in frontend.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function handle_frontend() {
		$post_id      = filter_input( INPUT_POST, 'post_id' );
		$form_id      = filter_input( INPUT_POST, 'form_id' );
		$this->record = $_POST; // @codingStandardsIgnoreLine

		// Convert array data to string. Used for checkbox.
		foreach ( $this->record['fields'] as $_id => $field ) {
			if ( is_array( $field ) ) {
				$this->record['fields'][ $_id ] = implode( ', ', $field );
			}
		}

		$form_meta              = Elementor::$instance->documents->get( $post_id )->get_elements_data();
		$this->form             = Module::find_element_recursive( $form_meta, $form_id );
		$this->form['settings'] = Elementor::$instance->elements_manager->create_element_instance( $this->form )->get_settings_for_display();

		$this
			->clear_step_fields()
			->set_custom_messages()
			->validate_form()
			->validate_fields()
			->upload_files()
			->run_actions()
			->send_response();
	}

	/**
	 * Clear fields with type "step" from form fields
	 * as it's not needed in form processing and causes error.
	 *
	 * @since 2.5.0
	 */
	private function clear_step_fields() {
		foreach ( $this->form['settings']['fields'] as $id => $field ) {
			if ( isset( $field['type'] ) && 'step' === $field['type'] ) {
				unset( $this->form['settings']['fields'][ $id ] );
			}
		}

		return $this;
	}

	/**
	 * Handle editor requests.
	 *
	 * Handle the form requests in editor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function handle_editor() {
		$nonce_valid = check_ajax_referer( 'jupiterx_control_panel', 'nonce', false );

		if ( false === $nonce_valid ) {
			$this->set_success( false )->send_response();
			return;
		}

		$action  = filter_input( INPUT_POST, 'service' );
		$request = filter_input( INPUT_POST, 'request' );
		$params  = filter_input( INPUT_POST, 'params', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		$class_name = 'JupiterX_Core\Raven\Modules\Forms\Actions\\' . ucfirst( $action );
		call_user_func( [ $class_name, $request ], $this, empty( $params ) ? [] : $params );

		$this->send_response();
	}

	/**
	 * Set success.
	 *
	 * Set form state to success/error.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param boolean $bool True or false.
	 */
	public function set_success( $bool ) {
		$this->is_success = $bool;
		return $this;
	}

	/**
	 * Validate form.
	 *
	 * Validate the form based on form ID.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_custom_messages() {
		$form = $this->form;

		if ( ! $form ) {
			return $this;
		}

		if ( empty( $form['settings']['messages_custom'] ) ) {
			return $this;
		}

		Module::$messages = [
			'success' => $form['settings']['messages_success'],
			'error' => $form['settings']['messages_error'],
			'required' => $form['settings']['messages_required'],
			'subscriber' => $form['settings']['messages_subscriber'],
		];

		return $this;
	}

	/**
	 * Validate form.
	 *
	 * Validate the form based on form ID.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	private function validate_form() {
		if ( $this->form ) {
			return $this;
		}

		$this
			->add_response( 'message', __( 'There\'s something wrong. The form is not valid.', 'jupiterx-core' ) )
			->set_success( false )
			->send_response();

		return $this;
	}

	/**
	 * Validate form fields.
	 *
	 * Validate form fields based on the settings.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	private function validate_fields() {
		$form_fields = $this->form['settings']['fields'];

		foreach ( $form_fields as $field ) {
			if (
				( isset( $field['_enable'] ) && 'false' === $field['_enable'] ) &&
				empty( $field['enable'] )
			) {
				continue;
			}

			$field['type'] = empty( $field['type'] ) ? 'text' : $field['type'];
			$class_name    = 'JupiterX_Core\Raven\Modules\Forms\Fields\\' . ucfirst( $field['type'] );

			$class_name::validate_required( $this, $field );
			$class_name::validate( $this, $field );
		}

		if ( ! empty( $this->response['errors'] ) ) {
			$this->send_response();
		}

		return $this;
	}

	/**
	 * Run actions.
	 *
	 * Run all the specified actions.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function run_actions() {
		$actions        = $this->form['settings']['actions'];
		$hidden_actions = '';

		if ( isset( $this->form['settings']['hidden_actions'] ) ) {
			$hidden_actions = $this->form['settings']['hidden_actions'];
		}

		if ( ! is_array( $actions ) ) {
			$actions = [];
		}

		// Join hidden actions to main action.
		if ( ! empty( $hidden_actions ) ) {
			foreach ( $hidden_actions as $action ) {
				array_unshift( $actions, $action );
			}

			// In register widget if user does not want to subscribe in newsletter, remove related actions.
			$register = 'JupiterX_Core\Raven\Modules\Forms\Actions\Register';
			$actions  = $register::exclude_third_party_subscription( $actions, $this );
		}

		if ( empty( $actions ) ) {
			return $this;
		}

		foreach ( $actions as $action ) {
			$class_name = Module::$action_types[ $action ];

			$class_name::run( $this );
		}

		return $this;
	}

	/**
	 * Add response.
	 *
	 * Add response to ajax response.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $type Response type.
	 * @param string $text Response text.
	 * @param string $text_key Response text key.
	 */
	public function add_response( $type, $text = '', $text_key = '' ) {
		if ( ! empty( $text_key ) ) {
			$this->response[ $type ][ $text_key ] = $text;
			return $this;
		}

		$this->response[ $type ][] = $text;
		return $this;
	}

	/**
	 * Send response.
	 *
	 * Send success/fail response.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function send_response() {
		if ( ! current_user_can( 'administrator' ) ) {
			unset( $this->response['admin_errors'] );
		}

		if ( $this->is_success ) {
			if ( empty( $this->response['message'] ) ) {
				$this->add_response( 'message', Module::$messages['success'] );
			}

			wp_send_json_success( $this->response );
		}

		if ( ! empty( $this->response['errors'] ) ) {
			$this->add_response( 'message', Module::$messages['error'] );
		}

		wp_send_json_error( $this->response );
	}

	/**
	 * Upload all the form files.
	 *
	 * @return $this
	 * @since 1.20.0
	 */
	public function upload_files() {
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$fields = isset( $_FILES['fields'] ) ? $_FILES['fields'] : false;

		if ( ! $fields ) {
			return $this;
		}

		foreach ( $fields as $id => $field ) {
			if ( empty( $field ) ) {
				continue;
			}

			foreach ( $field as $index => $file ) {
				if ( UPLOAD_ERR_NO_FILE === $file['error'] ) {
					continue;
				}

				$uploads_dir    = $this->get_ensure_upload_dir();
				$file_extension = pathinfo( $file['name'], PATHINFO_EXTENSION );
				$filename       = uniqid() . '.' . $file_extension;
				$filename       = wp_unique_filename( $uploads_dir, $filename );
				$new_file       = trailingslashit( $uploads_dir ) . $filename;

				if ( ! is_dir( $uploads_dir ) || ! is_writable( $uploads_dir ) ) {
					$this
						->add_response( 'errors', __( 'Upload directory is not writable or does not exist.', 'jupiterx-core' ), $field['_id'] )
						->set_success( false );
					return $this;
				}

				$move_new_file = @move_uploaded_file( $file['tmp_name'], $new_file );

				if ( false === $move_new_file ) {
					$this
						->add_response( 'errors', __( 'There was an error while trying to upload your file.', 'jupiterx-core' ), $field['_id'] )
						->set_success( false );
					return $this;
				}

				// Set correct file permissions.
				@chmod( $new_file, 0644 );

				$this->uploaded_files = array_merge( $this->uploaded_files, [ $this->get_file_url( $filename ) ] );
			}

			$this->record['fields'][ $id ] = implode( ', ', $this->uploaded_files );
		}

		return $this;
	}

	/**
	 * Gets the path to uploaded file.
	 *
	 * @return string
	 * @since 1.20.0
	 */
	private function get_upload_dir() {
		$wp_upload_dir = wp_upload_dir();
		$path          = $wp_upload_dir['basedir'] . '/jupiterx/forms';

		/**
		 * Upload file path.
		 *
		 * Filters the path to a file uploaded using jupiterx forms.
		 *
		 * @since 1.20.0
		 *
		 * @param string $url File URL.
		 */
		$path = apply_filters( 'jupiterx_forms_upload_path', $path );

		return $path;
	}

	/**
	 * This function returns the uploads folder after making sure
	 * it is created and has protection files
	 *
	 * @return string
	 * @since 1.20.0
	 */
	private function get_ensure_upload_dir() {
		$path = $this->get_upload_dir();

		if ( file_exists( $path . '/index.php' ) ) {
			return $path;
		}

		wp_mkdir_p( $path );

		$files = [
			[
				'file' => 'index.php',
				'content' => [
					'<?php',
					'// Silence is golden.',
				],
			],
			[
				'file' => '.htaccess',
				'content' => [
					'Options -Indexes',
					'<ifModule mod_headers.c>',
					'	<Files *.*>',
					'       Header set Content-Disposition attachment',
					'	</Files>',
					'</IfModule>',
				],
			],
		];

		foreach ( $files as $file ) {
			if ( ! file_exists( trailingslashit( $path ) . $file['file'] ) ) {
				$content = implode( PHP_EOL, $file['content'] );

				$this->get_wp_filesystem_direct()->put_contents( trailingslashit( $path ) . $file['file'], $content );
			}
		}

		return $path;
	}

	/**
	 * Gets the URL to uploaded file.
	 *
	 * @param $file_name
	 *
	 * @return string
	 */
	private function get_file_url( $file_name ) {
		$wp_upload_dir = wp_upload_dir();
		$url           = $wp_upload_dir['baseurl'] . '/jupiterx/forms/' . $file_name;

		return $url;
	}

	/**
	 * Gets file system.
	 *
	 * @return object
	 */
	public function get_wp_filesystem_direct() {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
		}

		return $wp_filesystem;
	}
}
