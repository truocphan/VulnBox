<?php
/**
 * Blocks rest API controller.
 *
 * @since 1.3.0
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Helper\Permission;

class BlocksController extends CrudController {
	/**
	 * Endpoint namespace.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	protected $rest_base = 'blocks';

	/**
	 * Object type.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	protected $object_type = 'block';

	/**
	 * If object is hierarchical.
	 *
	 * @since 1.3.0
	 *
	 * @var bool
	 */
	protected $hierarchical = true;

	/**
	 * Permission class.
	 *
	 * @since 1.3.0
	 *
	 * @var Masteriyo\Helper\Permission;
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 1.3.0
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission = null ) {
		$this->permission = $permission;
	}

	/**
	 * Register routes.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/save_css',
			array(
				'args' => array(
					'postId' => array(
						'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
						'type'        => 'integer',
						'required'    => true,
					),
					'css'    => array(
						'description' => __( 'Generated CSS for the post/page.', 'masteriyo' ),
						'type'        => 'string',
					),
				),
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'save_css' ),
					'permission_callback' => array( $this, 'save_css_permissions_check' ),
				),
			)
		);
	}

	/**
	 * Check if a given request has access to start quiz.
	 *
	 * @since 1.3.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function save_css_permissions_check( $request ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_save_css',
				__( 'Sorry, you are not allowed to update blocks CSS.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Collect data after starting quiz.
	 *
	 * @since 1.3.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 */
	public function save_css( $request ) {
		$post    = $request->get_params();
		$css     = (string) $post['css'];
		$post_id = absint( $post['postId'] );

		if ( $post_id ) {
			/**
			 * Filters blocks CSS before saving.
			 *
			 * @since 1.3.0
			 *
			 * @param string $css Blocks CSS.
			 * @param integer $post_id Post ID.
			 */
			$css = apply_filters( 'masteriyo_before_save_blocks_css', $css, $post_id );

			update_post_meta( $post_id, '_masteriyo_css', $css );

			$response = rest_ensure_response(
				array(
					'success' => true,
				)
			);
		} else {
			$response = rest_ensure_response(
				array(
					'success' => false,
					'message' => __( 'Invalid post ID', 'masteriyo' ),
				)
			);
		}

		/**
		 * Filters response data of save-blocks API.
		 *
		 * @since 1.3.0
		 *
		 * @param WP_REST_Response|WP_Error $response The response data of save-blocks API.
		 */
		return apply_filters( 'masteriyo_save_css_rest_response', $response );
	}
}
