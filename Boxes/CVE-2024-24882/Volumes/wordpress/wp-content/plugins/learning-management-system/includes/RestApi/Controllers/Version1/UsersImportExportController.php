<?php
/**
 * Users import/export controller class.
 *
 * @since 1.6.13
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Exporter\UserCSVExporter;
use Masteriyo\Helper\Permission;
use Masteriyo\Importer\CourseImporter;
use Masteriyo\Importer\UserCSVImporter;
use Masteriyo\Roles;

/**
 * UsersImportExportController class.
 *
 * @since 1.6.13
 */
class UsersImportExportController extends RestController {

	/**
	 * Endpoint namespace.
	 *
	 * @since 1.6.13
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @since 1.6.13
	 * @var string
	 */
	protected $rest_base = 'users';

	/**
	 * Permission class.
	 *
	 * @since 1.6.13
	 *
	 * @var \Masteriyo\Helper\Permission;
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 1.6.13
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission ) {
		$this->permission = $permission;
	}

	/**
	 * Register routes.
	 *
	 * @since 1.6.13
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/export',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'export_items' ),
				'permission_callback' => array( $this, 'import_items_permission_check' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/import',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'import_items' ),
				'permission_callback' => array( $this, 'import_items_permission_check' ),
			)
		);
	}

	/**
	 * Export items.
	 *
	 * @since  1.6.13
	 *
	 * @param  \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function export_items( \WP_REST_Request $request ) {
		$users = $this->fetch_users();

		if ( is_wp_error( $users ) ) {
			return $users;
		}

		$data = array_map( array( $this, 'prepare_user_data' ), $users );
		$data = array_filter( $data );

		if ( empty( $data ) ) {
			return new \WP_Error( 'no_users_data', 'No user data to export.', array( 'status' => 404 ) );
		}

		return $this->export_to_csv( $data );
	}

	/**
	 * Import items.
	 *
	 * @since 1.6.13
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function import_items( \WP_REST_Request $request ) {
		$file = $this->get_import_file( $request->get_file_params() );

		if ( is_wp_error( $file ) ) {
			return $file;
		}

		$importer = new UserCSVImporter( $file );

		return $importer->import();
	}

	/**
	 * Parse Import file.
	 *
	 * @since 1.6.13
	 * @param array $files $_FILES array for a given file.
	 * @return string|\WP_Error File path on success and WP_Error on failure.
	 */
	protected function get_import_file( $files ) {
		if ( ! isset( $files['file']['tmp_name'] ) ) {
			return new \WP_Error(
				'rest_upload_no_data',
				__( 'No data supplied.', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		if (
			! isset( $files['file']['name'] ) ||
			'csv' !== pathinfo( $files['file']['name'], PATHINFO_EXTENSION )
		) {
			return new \WP_Error(
				'invalid_file_ext',
				__( 'Invalid file type for import.', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		return $files['file']['tmp_name'];
	}

	/**
	 * Check if a given request has access to import items.
	 *
	 * @since 1.6.13
	 *
	 * @param  \WP_REST_Request $request Full details about the request.
	 * @return \WP_Error|boolean
	 */
	public function import_items_permission_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( ! $this->permission->rest_check_users_manipulation_permissions( 'create' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you are not allowed to create resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Fetch all users.
	 *
	 * @since  1.6.13
	 *
	 * @return array|\WP_Error Returns an array of users or WP_Error if no users found.
	 */
	private function fetch_users() {
		$users = get_users();

		if ( empty( $users ) ) {
			return new \WP_Error( 'no_users', 'No users found.', array( 'status' => 404 ) );
		}

		return $users;
	}

	/**
	 * Prepare the user data for export.
	 *
	 * @since  1.6.13
	 *
	 * @param  WP_User $user WordPress user object.
	 *
	 * @return array|null Returns an associative array with the user data, or null if user is an admin or an error.
	 */
	private function prepare_user_data( $user ) {
		$masteriyo_user = masteriyo_get_user( $user->ID );

		if ( ! is_null( $masteriyo_user ) && ! is_wp_error( $masteriyo_user ) && ! $masteriyo_user->has_roles( Roles::ADMIN ) ) {
			return array(
				'id'                   => $masteriyo_user->get_id(),
				'username'             => $masteriyo_user->get_username(),
				'nicename'             => $masteriyo_user->get_nicename(),
				'email'                => $masteriyo_user->get_email(),
				'url'                  => $masteriyo_user->get_url(),
				'date_created'         => $masteriyo_user->get_date_created(),
				'status'               => $masteriyo_user->get_status(),
				'display_name'         => $masteriyo_user->get_display_name(),
				'nickname'             => $masteriyo_user->get_nickname(),
				'first_name'           => $masteriyo_user->get_first_name(),
				'last_name'            => $masteriyo_user->get_last_name(),
				'description'          => $masteriyo_user->get_description(),
				'locale'               => $masteriyo_user->get_locale(),
				'roles'                => $masteriyo_user->get_roles(),
				'profile_image_id'     => $masteriyo_user->get_profile_image_id(),
				'billing_first_name'   => $masteriyo_user->get_billing_first_name(),
				'billing_last_name'    => $masteriyo_user->get_billing_last_name(),
				'billing_company_name' => $masteriyo_user->get_billing_company_name(),
				'billing_company_id'   => $masteriyo_user->get_billing_company_id(),
				'billing_address_1'    => $masteriyo_user->get_billing_address_1(),
				'billing_address_2'    => $masteriyo_user->get_billing_address_2(),
				'billing_city'         => $masteriyo_user->get_billing_city(),
				'billing_postcode'     => $masteriyo_user->get_billing_postcode(),
				'billing_country'      => $masteriyo_user->get_billing_country(),
				'billing_state'        => $masteriyo_user->get_billing_state(),
				'billing_email'        => $masteriyo_user->get_billing_email(),
				'billing_phone'        => $masteriyo_user->get_billing_phone(),
			);
		}

		return null;
	}

	/**
	 * Export the user data to CSV.
	 *
	 * @since  1.6.13
	 *
	 * @param  array $data An array of user data.
	 *
	 * @return \WP_Error|\WP_REST_Response Returns WP_REST_Response on success, or WP_Error on failure.
	 */
	private function export_to_csv( $data ) {
		$exporter = new UserCSVExporter( $data );
		$data     = $exporter->export();

		if ( ! $data ) {
			return new \WP_Error( 'users_csv_export_failure', 'Something went wrong while exporting users.', array( 'status' => 500 ) );
		}

		return rest_ensure_response( $data );
	}
}
