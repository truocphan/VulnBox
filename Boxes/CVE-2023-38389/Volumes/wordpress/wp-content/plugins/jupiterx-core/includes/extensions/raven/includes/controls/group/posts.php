<?php
/**
 * Adds posts control.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Controls\Group;

use \Elementor\Group_Control_Base;
use JupiterX_Core\Raven\Controls\Query as Control_Query;

defined( 'ABSPATH' ) || die();

/**
 * Raven posts control.
 *
 * A base control for creating posts control. Use to build a WP_Query arguments.
 *
 * Creating new control in the editor (inside `Widget_Base::_register_controls()`
 * method):
 *
 *    $this->add_group_control(
 *        'raven-posts',
 *        [
 *            'name' => 'posts',
 *            'post_type' => [ 'post', 'product' ],
 *        ]
 *    );
 *
 * @since 1.0.0
 *
 * @param string $name           The field name.
 * @param array  $post_type      Optional. Define specific post type/s to use. Default
 *                               is an empty array, including all the post types.
 * @param array  $fields_options Optional. An array of arays contaning data that
 *                               overrides control settings. Default is an empty array.
 * @param string $separator      Optional. Set the position of the control separator.
 *                               Available values are 'default', 'before', 'after'
 *                               and 'none'. 'default' will position the separator
 *                               depending on the control type. 'before' / 'after'
 *                               will position the separator before/after the
 *                               control. 'none' will hide the separator. Default
 *                               is 'default'.
 */
class Posts extends Group_Control_Base {

	/**
	 * Fields.
	 *
	 * Holds all the posts control fields.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @static
	 *
	 * @var array Posts control fields.
	 */
	protected static $fields;

	/**
	 * Retrieve type.
	 *
	 * Get posts control type.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Control type.
	 */
	public static function get_type() {
		return 'raven-posts';
	}

	/**
	 * Get post types.
	 *
	 * Get post types for source. Filter post types using `name` key in $args variable.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @param  array $args Control arguments.
	 *
	 * @return array Filtered or non-filtered post types.
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	private static function get_post_types( $args ) {
		$post_types = [];

		$types_objects = [];

		$is_array = is_array( $args['post_type'] );

		$is_assoc = $is_array && array_values( $args['post_type'] ) !== $args['post_type'];

		// Get specific post type.
		if ( is_string( $args['post_type'] ) ) {
			$types_objects[] = get_post_type_object( $args['post_type'] );
		}

		// Get defined post types.
		if ( ! empty( $args['post_type'] ) && $is_array ) {
			$types_key = $is_assoc ? array_keys( $args['post_type'] ) : $args['post_type'];

			foreach ( $types_key as $type_name ) {
				$types_objects[] = get_post_type_object( $type_name );
			}
		}

		// A fallback for every failed conditions above.
		if ( 0 === count( $types_objects ) ) {
			$types_objects = get_post_types( [ 'show_in_nav_menus' => true ], 'objects' );
		}

		foreach ( $types_objects as $object ) {
			if ( ! is_null( $object ) ) {
				$post_types[ $object->name ] = $object->label;
			}
		}

		if ( $is_array && $is_assoc ) {
			$post_types = array_intersect_key( $args['post_type'], $post_types );
		}

		return $post_types;
	}

	/**
	 * Init fields.
	 *
	 * Initialize posts control fields.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Control fields.
	 */
	public function init_fields() {
		$fields = [];

		$fields['post_type'] = [
			'label' => _x( 'Source', 'Posts Group Control', 'jupiterx-core' ),
			'type' => 'select',
		];

		return $fields;
	}

	/**
	 * Prepare fields.
	 *
	 * Process posts control fields before adding them to `add_control()`.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param array $fields Posts control fields.
	 *
	 * @return array Processed fields.
	 */
	protected function prepare_fields( $fields ) {
		$args = $this->get_args();

		$post_types = self::get_post_types( $args );

		$excludes = [
			'jupiterx-codes',
			'jupiterx-fonts',
			'jupiterx-icons',
			'sellkit_step',
		];

		// Exclude Jupiter X and Sellkit post types.
		foreach ( $post_types as $key => $value ) {
			if ( ! in_array( $key, $excludes, true ) ) {
				continue;
			}

			unset( $post_types[ $key ] );
		}

		$fields['post_type'] = array_merge( $fields['post_type'], [
			'type' => 1 >= count( $post_types ) ? 'hidden' : 'select',
			'default' => key( $post_types ),
			'options' => $post_types,
		] );

		$supports = array_diff( [
			'ids',
			'taxonomies',
			'authors',
			'ignore_sticky_posts',
		], $args['exclude'] );

		$post_type_keys = array_keys( $post_types );

		foreach ( $supports as $support ) {
			$method_name = 'get_' . $support . '_fields';

			$fields = array_merge(
				$fields,
				$this->$method_name( $post_type_keys )
			);
		}

		return parent::prepare_fields( $fields );
	}

	/**
	 * Get post type IDs fields.
	 *
	 * @since 1.0.0
	 * @since 1.10.0 Use autocomplete.
	 * @access protected
	 *
	 * @param array $post_types All post types.
	 *
	 * @return array To embed fields.
	 */
	public function get_ids_fields( $post_types ) {
		$fields = [];

		foreach ( $post_types as $post_type ) {
			$fields[ $post_type . '_includes' ] = [
				'label'       => _x( 'Posts', 'Posts Group Control', 'jupiterx-core' ),
				'type'        => 'raven_query',
				'default'     => [],
				'multiple'    => true,
				'label_block' => true,
				'options'     => [],
				'condition'   => [
					'post_type' => $post_type,
				],
				'query' => [
					'source'    => Control_Query::QUERY_SOURCE_POST,
					'post_type' => $post_type,
				],
			];
		}

		return $fields;
	}

	/**
	 * Get taxonomies fields.
	 *
	 * @since 1.0.0
	 * @since 1.10.0 Use autocomplete.
	 * @access protected
	 *
	 * @param array $post_types All post types.
	 *
	 * @return array To embed fields.
	 */
	public function get_taxonomies_fields( $post_types ) {
		$taxonomies = get_taxonomies( [
			'show_in_nav_menus' => true,
		], 'objects' );

		$taxonomies = array_filter( $taxonomies, function( $value ) use ( $post_types ) {
			return count( array_intersect( $value->object_type, $post_types ) );
		} );

		$fields = [];

		foreach ( $taxonomies as $taxonomy => $taxonomy_object ) {
			$control_id = $taxonomy . '_ids';

			if ( class_exists( 'WpTesting_Facade' ) ) {
				unset( $taxonomy_object->object_type[1] );
			}

			$fields[ $control_id ] = [
				'label'       => $taxonomy_object->label,
				'type'        => 'raven_query',
				'default'     => [],
				'label_block' => true,
				'multiple'    => true,
				'options'     => [],
				'conditions' => [
					'terms' => [
						[
							'name' => 'post_type',
							'operator' => '==',
							'value' => $taxonomy_object->object_type,
						],
						[
							'name' => $taxonomy_object->object_type[0] . '_includes',
							'operator' => '==',
							'value' => '',
						],
					],
				],
				'query' => [
					'source'   => Control_Query::QUERY_SOURCE_TAX,
					'taxonomy' => $taxonomy,
				],
			];
		}

		return $fields;
	}

	/**
	 * Get authors field.
	 *
	 * @since 1.0.0
	 * @since 1.10.0 Use autocomplete.
	 * @access protected
	 *
	 * @param array $post_types All post types.
	 *
	 * @return array To embed fields.
	 */
	public function get_authors_fields() {
		$fields = [];

		$fields['authors'] = [
			'label'       => _x( 'Authors', 'Posts Group Control', 'jupiterx-core' ),
			'type'        => 'raven_query',
			'default'     => [],
			'multiple'    => true,
			'label_block' => true,
			'options'     => [],
			'query'       => [
				'source' => Control_Query::QUERY_SOURCE_AUTHOR,
			],
		];

		return $fields;
	}

	/**
	 * Get ignore post sticky field.
	 *
	 * @since 1.9.4
	 * @access protected
	 *
	 * @return array To embed fields.
	 */
	public function get_ignore_sticky_posts_fields() {
		$fields = [];

		$fields['ignore_sticky_posts'] = [
			'label'       => __( 'Ignore Sticky Posts', 'jupiterx-core' ),
			'type'        => 'switcher',
			'default'     => 'yes',
			'description' => __( 'Sticky-posts ordering is visible on frontend only', 'jupiterx-core' ),
			'condition'   => [
				'post_type' => 'post',
			],
		];

		return $fields;
	}

	/**
	 * Retrieve child default args.
	 *
	 * Get the default arguments for all the child controls for a specific group
	 * control.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Default arguments for all the child controls.
	 */
	protected function get_child_default_args() {
		return [
			'post_type' => [],
			'exclude' => [],
		];
	}

	/**
	 * Retrieve default options.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_default_options() {
		return [
			'popover' => false,
		];
	}
}
