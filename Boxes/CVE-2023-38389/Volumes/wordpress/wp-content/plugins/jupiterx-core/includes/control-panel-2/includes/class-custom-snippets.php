<?php

/**
 * Handles custom snippets functionality in control panel.
 *
 * @package JupiterX_Core\Control_Panel_2\Custom_Snippets
 *
 * @since 2.0.0
 */
class JupiterX_Core_Control_Panel_Custom_Snippets {

	private static $instance = null;

	/**
	 * Instance of class
	 *
	 * @return object
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'wp_ajax_jupiterx_custom_snippets', [ $this, 'handle_ajax' ] );
		add_action( 'wp_ajax_jupiterx_custom_snippets_get_posts', [ $this, 'get_posts' ] );
	}

	/**
	 * Locations ( hooks ) to perform custom codes functionality.
	 * This will apply in localized script. and will be displayed in custom codes new post drop down
	 *
	 * @return array
	 * @since 2.0.0
	 */
	public static function snippet_locations() {
		return [
			/* translators: %s tag name. */
			'jupiterx_head_prepend_markup'   => sprintf( __( 'After %s', 'jupiterx-core' ), '<head>' ),
			/* translators: %s tag name. */
			'wp_head'                        => sprintf( __( 'Before %s', 'jupiterx-core' ), '</head>' ),
			/* translators: %s tag name. */
			'wp_body_open'                   => sprintf( __( 'After %s', 'jupiterx-core' ), '<body>' ),
			/* translators: %s tag name. */
			'wp_footer'                      => sprintf( __( 'Before %s', 'jupiterx-core' ), '</body>' ),
			/* translators: %s tag name. */
			'jupiterx_footer_prepend_markup' => sprintf( __( 'After %s', 'jupiterx-core' ), '<footer>' ),
			/* translators: %s tag name. */
			'jupiterx_footer_append_markup'  => sprintf( __( 'Before %s', 'jupiterx-core' ), '</footer>' ),
		];
	}

	/**
	 * Handle ajax requests.
	 * Gets Ajax call sub_action parameter and call a function based on parameter value.
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public function handle_ajax() {
		check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

		$action = filter_input( INPUT_POST, 'sub_action', FILTER_SANITIZE_STRING );

		if ( ! empty( $action ) && method_exists( $this, $action ) ) {
			call_user_func( [ $this, $action ] );
		}
	}

	/**
	 * Gets Custom codes posts
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public function get_posts() {
		check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

		$post_type      = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_STRING );
		$paged          = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT );
		$condition_meta = JupiterX_Core_Condition_Manager::JUPITERX_CONDITIONS_COMPONENT_META_NAME;

		/**
		 * Filter List Table query arguments.
		 *
		 * @since 2.0.0
		 *
		 * @param array $args The query arguments.
		 */
		$args = apply_filters( "jupiterx_custom_snippet_list_table_{$post_type}_args", [
			'post_type' => $post_type,
			'paged' => $paged,
			'posts_per_page' => 20,
		] );

		$query = new \WP_Query( $args );

		/**
		 * Filter List Table query posts.
		 *
		 * @since 2.0.0
		 *
		 * @param array $args The taxonomy arguments.
		 */
		$posts = apply_filters( "jupiterx_custom_snippet_list_table_{$post_type}_posts", $query->posts );

		/**
		 * Filter List Table columns.
		 *
		 * @since 2.0.0
		 *
		 * @param array $args The columns headings and values.
		 */
		$columns = apply_filters( "jupiterx_custom_snippet_list_table_{$post_type}_columns", [
			'labels' => [
				__( 'Hooked on', 'jupiterx-core' ),
				__( 'Priority', 'jupiterx-core' ),
				__( 'Author', 'jupiterx-core' ),
				__( 'Created on', 'jupiterx-core' ),
			],
			'values' => [
				'',
			],
		], $posts );

		$locations = $this->snippet_locations();

		// columns value
		foreach ( $posts as $key => $post ) {
			$columns['values'][ "post_{$post->ID}" ] = [
				$locations[ get_post_meta( $post->ID, 'jupiterx_location', true ) ],
				get_field( 'jupiterx_priority', $post->ID ),
				get_the_author_meta( 'user_login', get_post_field( 'post_author', $post->ID ) ),
				get_the_time( 'Y-m-d', $post->ID ),
			];

			$posts[ $key ]->location   = get_post_meta( $post->ID, 'jupiterx_location', true );
			$posts[ $key ]->priority   = get_post_meta( $post->ID, 'jupiterx_priority', true );
			$posts[ $key ]->conditions = get_post_meta( $post->ID, $condition_meta, true );
			$posts[ $key ]->userUrl    = get_edit_user_link( get_the_author_meta( 'ID', get_post_field( 'post_author', $post->ID ) ) );
		}

		// Send response.
		wp_send_json_success( [
			'posts'         => $posts,
			'max_num_pages' => $query->max_num_pages,
			'columns'       => $columns,
		] );
	}

	/**
	 * Create and update post by ajax
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public function save_post() {
		$post       = filter_input( INPUT_POST, 'post', FILTER_DEFAULT, FILTER_FORCE_ARRAY );
		$conditions = filter_input( INPUT_POST, 'conditions', FILTER_DEFAULT, FILTER_FORCE_ARRAY );

		if ( empty( $conditions ) ) {
			wp_send_json_error( __( 'Please set one condition at least.', 'jupiterx-core' ) );
		}

		if ( empty( $post['custom_snippets_post_title'] ) ) {
			wp_send_json_error( __( 'Name of the custom snippet can not be empty.', 'jupiterx-core' ) );
		}

		$condition_meta = JupiterX_Core_Condition_Manager::JUPITERX_CONDITIONS_COMPONENT_META_NAME;
		$status         = 'publish';

		if ( 'false' === $post['custom_snippets_post_status'] ) {
			$status = 'draft';
		}

		$post_data = [
			'post_title'   => wp_strip_all_tags( $post['custom_snippets_post_title'] ),
			'post_content' => $post['custom_snippets_post_content'],
			'post_status'  => $status,
			'post_type'    => 'jupiterx-codes',
			'meta_input'   => [
				'jupiterx_location' => $post['custom_snippets_post_location'],
				'jupiterx_priority' => $post['custom_snippets_post_priority'],
				$condition_meta     => $conditions,
			],
		];

		// Check if it's update query or create
		if ( '' !== $post['custom_snippets_submit_mode'] ) {
			$post_data['ID'] = $post['custom_snippets_submit_mode'];
		}

		$result = wp_insert_post( $post_data );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( $result->get_error_message() );
		}

		// Also add post id to jupiterx option that holds post ids with conditions.
		JupiterX_Core_Condition_Manager::get_instance()->add_posts_id_with_conditions( $result, $conditions );

		wp_send_json_success();
	}

	/**
	 * Change post status
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public function change_status() {
		$post   = filter_input( INPUT_POST, 'post', FILTER_DEFAULT, FILTER_FORCE_ARRAY );
		$update = 'publish';

		if ( 'publish' === $post['post_status'] ) {
			$update = 'draft';
		}

		$my_post = [
			'ID'            => $post['ID'],
			'post_status'   => $update,
		];
		$result  = wp_update_post( $my_post );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( $result->get_error_message() );
		}

		wp_send_json_success();
	}

	/**
	 * Remove post by ajax.
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public function remove_post() {
		$post   = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT );
		$result = $this->delete_post( $post );

		if ( false === $result || null === $result ) {
			wp_send_json_error();
		}

		wp_send_json_success();
	}

	/**
	 * Delete a post.
	 *
	 * @param int $id
	 * @return void
	 * @since 2.0.0
	 */
	private function delete_post( $id ) {
		$result = wp_delete_post( $id, true );
		return $result;
	}

	public function bulk_action() {
		$posts  = filter_input( INPUT_POST, 'posts', FILTER_DEFAULT, FILTER_FORCE_ARRAY );
		$action = filter_input( INPUT_POST, 'bulk_action', FILTER_SANITIZE_STRING );
		global $wpdb;

		if ( 'remove' === $action ) {
			foreach ( $posts as $post ) {
				$this->delete_post( $post );
			}
		}

		$string_posts = implode( ',', array_map( 'intval', $posts ) );

		//phpcs:disable
		if ( 'inactive' === $action ) {
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE $wpdb->posts set post_status='draft' WHERE ID IN ( $string_posts )"
				)
			);
		}

		if ( 'active' === $action ) {
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE $wpdb->posts set post_status='publish' WHERE ID IN ( $string_posts )"
				)
			);
		}
		//phpcs:enable

		wp_send_json_success();
	}
}
JupiterX_Core_Control_Panel_Custom_Snippets::get_instance();
