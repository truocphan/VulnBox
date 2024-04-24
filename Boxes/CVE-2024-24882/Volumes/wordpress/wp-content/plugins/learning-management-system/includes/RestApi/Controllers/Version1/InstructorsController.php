<?php
/**
 * Instructor controller class.
 *
 * @since 1.3.0
 *
 * @package Masteriyo\RestApi\Controllers\Version1;
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Enums\UserStatus;
use Masteriyo\Helper\Permission;
use Masteriyo\RestApi\Controllers\Version1\UsersController;

/**
 * Instructor controller class.
 */
class InstructorsController extends UsersController {
	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'users/instructors';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $object_type = 'instructor';

	/**
	 * If object is hierarchical.
	 *
	 * @var bool
	 */
	protected $hierarchical = false;

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
	 * @param Permission $permission Permission instance.
	 */
	public function __construct( Permission $permission = null ) {
		$this->permission = $permission;
	}

	/**
	 * Get the query params for collections of attachments.
	 *
	 * @since 1.3.0
	 * @deprecated 1.5.0
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		unset( $params['roles'] );

		$params['approved'] = array(
			'description'       => __( 'Whether the instructor is approved or not.', 'masteriyo' ),
			'type'              => 'boolean',
			'validate_callback' => 'rest_validate_request_arg',
		);

		/**
		 * Filters instructors collection query params.
		 *
		 * @since 1.3.0
		 *
		 * @param array $params The collection query params.
		 */
		return apply_filters( 'masteriyo_instructor_collection_params', $params );
	}

	/**
	 * Get the User's schema, conforming to JSON Schema.
	 *
	 * @since 1.3.0
	 * @deprecated 1.5.0
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = parent::get_item_schema();

		unset( $schema['properties']['roles'] );

		$schema['properties'] = array_merge(
			$schema['properties'],
			array(
				'approved' => array(
					'description' => __( 'Instructor approved.', 'masteriyo' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
				),
			)
		);

		return $schema;
	}

	/**
	 * Prepare objects query.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @since  1.3.0
	 * @deprecated 1.5.0
	 *
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args         = parent::prepare_objects_query( $request );
		$args['role'] = array( 'masteriyo_instructor' );

		if ( isset( $request['approved'] ) ) {
			if ( true === $request['approved'] ) {
				$args['user_status'] = UserStatus::ACTIVE;
			} else {
				$args['user_status'] = UserStatus::INACTIVE;
			}
		}

		return $args;
	}

	/**
	 * Get object.
	 *
	 * @since 1.3.0
	 *
	 * @param  int|WP_user|Model $object Object ID or WP_user or Model.
	 *
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $object ) {
		try {
			if ( is_int( $object ) ) {
				$id = $object;
			} else {
				$id = is_a( $object, '\WP_User' ) ? $object->ID : $object->get_id();
			}
			$instructor = masteriyo( 'instructor' );
			$instructor->set_id( $id );
			$instructor_repo = masteriyo( 'user.store' );
			$instructor_repo->read( $instructor );
		} catch ( \Exception $e ) {
			return false;
		}

		return $instructor;
	}

	/**
	 * Get instructor data.
	 *
	 * @since 1.3.0
	 *
	 * @param Instructor   $instructor instructor instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_user_data( $instructor, $context = 'view' ) {
		$data = parent::get_user_data( $instructor, $context );

		return $data;
	}

	/**
	 * Prepare a single user object for create or update.
	 *
	 * @since 1.3.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Masteriyo\Database\Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id         = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$instructor = masteriyo( 'instructor' );

		if ( 0 !== $id ) {
			$instructor->set_id( $id );
			$instructor_repo = masteriyo( 'user.store' );
			$instructor_repo->read( $instructor );
		}

		// User's username.
		if ( isset( $request['username'] ) ) {
			$instructor->set_username( $request['username'] );
		}

		// User's password.
		if ( isset( $request['password'] ) ) {
			$instructor->set_password( $request['password'] );
		}

		// User's nicename.
		if ( isset( $request['nicename'] ) ) {
			$instructor->set_nicename( $request['nicename'] );
		}

		// User's email.
		if ( isset( $request['email'] ) ) {
			$instructor->set_email( $request['email'] );
		}

		// User's url.
		if ( isset( $request['url'] ) ) {
			$instructor->set_url( $request['url'] );
		}

		// User's activation_key.
		if ( isset( $request['activation_key'] ) ) {
			$instructor->set_activation_key( $request['activation_key'] );
		}

		// User's status.
		if ( isset( $request['status'] ) ) {
			$instructor->set_status( $request['status'] );
		}

		// User's display_name.
		if ( isset( $request['display_name'] ) ) {
			$instructor->set_display_name( $request['display_name'] );
		}

		// User's nickname.
		if ( isset( $request['nickname'] ) ) {
			$instructor->set_nickname( $request['nickname'] );
		}

		// User's first_name.
		if ( isset( $request['first_name'] ) ) {
			$instructor->set_first_name( $request['first_name'] );
		}

		// User's last_name.
		if ( isset( $request['last_name'] ) ) {
			$instructor->set_last_name( $request['last_name'] );
		}

		// User's description.
		if ( isset( $request['description'] ) ) {
			$instructor->set_description( $request['description'] );
		}

		// User's rich_editing.
		if ( isset( $request['rich_editing'] ) ) {
			$instructor->set_rich_editing( $request['rich_editing'] );
		}

		// User's syntax_highlighting.
		if ( isset( $request['syntax_highlighting'] ) ) {
			$instructor->set_syntax_highlighting( $request['syntax_highlighting'] );
		}

		// User's comment_shortcuts.
		if ( isset( $request['comment_shortcuts'] ) ) {
			$instructor->set_comment_shortcuts( $request['comment_shortcuts'] );
		}

		// User's use_ssl.
		if ( isset( $request['use_ssl'] ) ) {
			$instructor->set_use_ssl( $request['use_ssl'] );
		}

		// User's show_admin_bar_front.
		if ( isset( $request['show_admin_bar_front'] ) ) {
			$instructor->set_show_admin_bar_front( $request['show_admin_bar_front'] );
		}

		// User's locale.
		if ( isset( $request['locale'] ) ) {
			$instructor->set_locale( $request['locale'] );
		}

		// User's role.
		if ( isset( $request['role'] ) ) {
			$instructor->set_roles( $request['role'] );
		}

		// User billing details.
		if ( isset( $request['billing']['first_name'] ) ) {
			$instructor->set_billing_first_name( $request['billing']['first_name'] );
		}

		if ( isset( $request['billing']['last_name'] ) ) {
			$instructor->set_billing_last_name( $request['billing']['last_name'] );
		}

		if ( isset( $request['billing']['company'] ) ) {
			$instructor->set_billing_company( $request['billing']['company'] );
		}

		if ( isset( $request['billing']['address_1'] ) ) {
			$instructor->set_billing_address_1( $request['billing']['address_1'] );
		}

		if ( isset( $request['billing']['address_2'] ) ) {
			$instructor->set_billing_address_2( $request['billing']['address_2'] );
		}

		if ( isset( $request['billing']['city'] ) ) {
			$instructor->set_billing_city( $request['billing']['city'] );
		}

		if ( isset( $request['billing']['postcode'] ) ) {
			$instructor->set_billing_postcode( $request['billing']['postcode'] );
		}

		if ( isset( $request['billing']['country'] ) ) {
			$instructor->set_billing_country( $request['billing']['country'] );
		}

		if ( isset( $request['billing']['state'] ) ) {
			$instructor->set_billing_state( $request['billing']['state'] );
		}

		if ( isset( $request['billing']['email'] ) ) {
			$instructor->set_billing_email( $request['billing']['email'] );
		}

		if ( isset( $request['billing']['phone'] ) ) {
			$instructor->set_billing_phone( $request['billing']['phone'] );
		}

		// Allow set meta_data.
		if ( isset( $request['meta_data'] ) && is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$instructor->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
			}
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @since 1.0.0
		 *
		 * @param Masteriyo\Database\Model $instructor Instructor object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $instructor, $request, $creating );
	}
}
