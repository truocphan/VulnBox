<?php
/**
 * Import export controller class.
 *
 * @since 1.6.0
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Constants;
use Masteriyo\Enums\PostStatus;
use Masteriyo\Exporter\CourseExporter;
use Masteriyo\Helper\Permission;
use Masteriyo\Importer\CourseImporter;
use Masteriyo\PostType\PostType;

/**
 * CoursesImportExportController class.
 *
 * @since 1.6.0
 */
class CoursesImportExportController extends RestController {

	/**
	 * Endpoint namespace.
	 *
	 * @since 1.6.0
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @since 1.6.0
	 * @var string
	 */
	protected $rest_base = 'courses';

	/**
	 * Constructor.
	 *
	 * @since 1.6.0
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission ) {
		$this->permission = $permission;
	}

	/**
	 * Register routes.
	 *
	 * @since 1.6.0
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

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/import/sample-courses',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'import_sample_courses' ),
				'permission_callback' => array( $this, 'import_items_permission_check' ),
				'args'                => array(
					'status' => array(
						'enum'    => array( 'publish', 'draft' ),
						'default' => 'publish',
						'type'    => 'string',
					),
				),
			)
		);
	}

	/**
	 * Import items.
	 *
	 * @since 1.6.0
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function import_items( \WP_REST_Request $request ) {
		$file = $this->get_import_file( $request->get_file_params() );

		if ( is_wp_error( $file ) ) {
			return $file;
		}

		try {
			$importer = new CourseImporter();
			$importer->import( $file );
		} catch ( \Exception $e ) {
			return new \WP_Error(
				'import_failed',
				$e->getMessage()
			);
		}

		return new \WP_REST_Response(
			array(
				'message' => __( 'Import successful.', 'masteriyo' ),
			)
		);
	}

	/**
	 * Import items.
	 *
	 * @since 1.6.0
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function export_items( \WP_REST_Request $request ) {
		$exporter = new CourseExporter();
		$data     = $exporter->export();
		return rest_ensure_response( $data );
	}

	/**
	 * Import sample courses.
	 *
	 * @since 1.6.0
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function import_sample_courses( \WP_REST_Request $request ) {
		$status = $request->get_param( 'status' ) ?? PostStatus::PUBLISH;
		$file   = Constants::get( 'MASTERIYO_PLUGIN_DIR' ) . '/sample-data/courses.json';

		if ( ! file_exists( $file ) ) {
			return new \WP_Error(
				'masteriyo_rest_import_sample_courses_file_not_found',
				__( 'Sample courses file not found.', 'masteriyo' ),
				array( 'status' => 404 )
			);
		}

		try {
			$importer = new CourseImporter( $status );
			$importer->import( $file );
		} catch ( \Exception $e ) {
			return new \WP_Error(
				'masteriyo_rest_import_sample_courses_error',
				$e->getMessage()
			);
		}

		return new \WP_REST_Response(
			array(
				'message' => __( 'Sample courses installed.', 'masteriyo' ),
			)
		);
	}

	/**
	 * Parse Import file.
	 *
	 * @since 1.6.0
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
			'json' !== pathinfo( $files['file']['name'], PATHINFO_EXTENSION )
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
	 * @since 1.6.0
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

		$instructor = masteriyo_get_current_instructor();
		if ( $instructor && ! $instructor->is_active() ) {
			return new \WP_Error(
				'masteriyo_rest_user_not_approved',
				__( 'Sorry, you are not approved by the manager.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( ! $this->permission->rest_check_post_permissions( PostType::COURSE, 'create' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_import',
				__( 'Sorry, you are not allowed to import courses.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}
}
