<?php
/**
 * The file class that handles layout builder.
 *
 * @package JupiterX_Core\Control_Panel_2\Layout_Builder.
 *
 * @since 2.0.0
 */

use Elementor\Plugin;
use Elementor\DB;
use Elementor\Utils;
use JupiterX_Core\Condition\Conditions_Logic;

/**
 * Layout Builder class.
 *
 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 *
 * @since 2.0.0
 */
class JupiterX_Core_Control_Panel_Layout_Builder {

	/**
	 * Class instance.
	 *
	 * @since 2.0.0
	 *
	 * @var JupiterX_Core_Control_Panel_Layout_Builder Class instance.
	 */
	private static $instance = null;

	/**
	 * Get a class instance.
	 *
	 * @since 2.0.0
	 *
	 * @return JupiterX_Core_Control_Panel_Layout_Builder Class instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_jupiterx_layout_builder', [ $this, 'handle_ajax' ] );
	}

	public static function layout_templates() {
		$sections = [
			'header'          => esc_html__( 'Header', 'jupiterx-core' ),
			'page-title-bar'  => esc_html__( 'Page Title Bar', 'jupiterx-core' ),
			'footer'          => esc_html__( 'Footer', 'jupiterx-core' ),
			'single'          => esc_html__( 'Single', 'jupiterx-core' ),
			'archive'         => esc_html__( 'Archive', 'jupiterx-core' ),
		];

		if ( class_exists( 'woocommerce' ) ) {
			$sections['product']         = esc_html__( 'Product Single', 'jupiterx-core' );
			$sections['product-archive'] = esc_html__( 'Product Archive', 'jupiterx-core' );
		}

		return $sections;
	}

	/**
	 * Template types that are not supported with Elementor or Elementor free.
	 *
	 * @return array
	 * @since 2.0.0
	 */
	private function elementor_exceptions_type( $type ) {
		$exceptions = [
			'page-title-bar',
		];

		if ( in_array( $type, $exceptions, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Handle ajax request.
	 *
	 * @since 2.0.0
	 */
	public function handle_ajax() {
		check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have access to this section.', 'jupiterx-core' );
		}

		$action = filter_var( $_REQUEST['sub_action'], FILTER_SANITIZE_FULL_SPECIAL_CHARS ); // phpcs:ignore

		call_user_func( [ $this, $action ] );
	}

	/**
	 * Gets builder posts based on type parameter.
	 *
	 * @since 2.0.0
	 * @return array on success
	 */
	private function get_posts() {
		$paged     = filter_input( INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT );
		$type      = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$post_type = 'elementor_library';

		if ( class_exists( 'Jet_Woo_Builder' ) && ( 'product' === $type || 'product-archive' === $type ) ) {
			$post_type = [ 'jet-woo-builder', 'elementor_library' ];
		}

		$args = [
			'post_type'      => $post_type,
			'post_status'    => [ 'draft', 'publish' ],
			'posts_per_page' => 6,
			'paged'          => $paged,
			'meta_query'     => [ //phpcs:ignore
				[
					'key'   => '_elementor_template_type',
					'value' => $type,
				],
			],
		];

		if ( 'archive' === $type ) {
			$args = $this->manage_archive_args( $args );
		}

		// For woocommerce related sections.
		if ( 'product' === $type || 'product-archive' === $type ) {
			$args = $this->manage_get_woo_sections( $type, $args );
		}

		add_filter( 'posts_join', [ $this, 'replace_inner_with_straight_joins' ], 20 );

		$result = new WP_Query( $args );

		remove_filter( 'posts_join', [ $this, 'replace_inner_with_straight_joins' ], 20 );

		$posts = $result->posts;
		$max   = $result->max_num_pages;

		if ( empty( $posts ) ) {
			wp_send_json_error( __( 'No Post Found', 'jupiterx-core' ) );
		}

		// Attach necessary data to each post.
		foreach ( $posts as $key => $data ) {
			$data->preview_url = add_query_arg(
				[
					'preview-id' => $data->ID,
					'jupiterx-layout-builder-preview' => 'true',
					'jupiterx-layout-builder-type' => $type,
				],
				get_permalink( $data->ID )
			);
			$data->author_name = get_the_author_meta( 'display_name', $data->post_author ); // author full name
			$data->custom_date = get_the_date( 'M d, Y', $data->ID ); // UI date type
			$data->conditions  = get_post_meta( $data->ID, 'jupiterx-condition-rules', true ); // conditions array.

			$data->conditions_string = get_post_meta( $data->ID, 'jupiterx-condition-rules-string', true ); // conditions string

			if ( empty( $data->conditions_string ) ) {
				$data->conditions_string = esc_html__( 'No Instance', 'jupiterx-core' );
			}

			$editor_url = Plugin::$instance->documents->get( $data->ID )->get_edit_url(); // elementor editor
			$editor_url = add_query_arg( [
				'layout-builder' => 'true',
			], $editor_url );

			$data->editor_url = $editor_url;
		}

		$data = [
			'posts' => $posts,
			'max'   => $max,
		];

		wp_send_json_success( $data );
	}

	/**
	 * Remove post.
	 *
	 * @since 2.0.0
	 */
	private function remove_post() {
		$post_id = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT );

		$result = wp_delete_post( $post_id, true );

		if ( empty( $result ) ) {
			wp_send_json_error();
		}

		JupiterX_Core_Condition_Manager::get_instance()->add_posts_id_with_conditions( $post_id, [] );

		wp_send_json_success();
	}

	/**
	 * Rename post title.
	 *
	 * @since 2.0.0
	 */
	private function rename_post() {
		$post_id = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT );
		$title   = filter_input( INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		$args = [
			'ID'         => $post_id,
			'post_title' => $title,
		];

		$result = wp_update_post( $args );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( $result->get_error_messages() );
		}

		wp_send_json_success();
	}

	private function create_post() {
		$type      = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$title     = filter_input( INPUT_POST, 'post_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$post_type = 'elementor_library';

		if ( class_exists( 'Jet_Woo_Builder' ) && ( 'product' === $type || 'product-archive' === $type ) ) {
			$post_type = 'jet-woo-builder';
		}

		$args = [
			'post_type'   => $post_type,
			'post_title'  => $title,
			'post_status' => 'publish',
			'meta_input'  => [
				'_elementor_template_type' => $type,
				'_elementor_edit_mode'     => 'builder',
				'jx-layout-type'           => $type,
				'_wp_page_template'        => 'full-width.php',
			],
		];

		if ( 'product' === $type || 'product-archive' === $type ) {
			$args = $this->manage_create_woo_sections( $type, $args );
		}

		$id = wp_insert_post( $args );

		if ( empty( $title ) ) {
			$type  = str_replace( '-', ' ', $type );
			$title = 'Elementor ' . $type . ' #' . $id;

			$update = array(
				'ID'           => $id,
				'post_title'   => $title,
			);

			wp_update_post( $update );
		}

		if ( is_wp_error( $id ) ) {
			wp_send_json_error( $id->get_error_message() );
		}

		$editor_url = Plugin::$instance->documents->get( $id )->get_edit_url();

		$response = add_query_arg( [
			'layout-builder' => 'true',
		], $editor_url );

		wp_send_json_success( $response );
	}

	/**
	 * Manage archive section args.
	 *
	 * @param array $args query args.
	 * @since 2.0.5
	 * @return array
	 */
	private function manage_archive_args( $args ) {
		$args['meta_query']['relation'] = 'AND';
		$args['meta_query'][]           = [
			'relation' => 'OR',
			[
				'key'     => 'jx-layout-type',
				'compare' => 'NOT EXISTS',
			],
			[
				'key'   => 'jx-layout-type',
				'value' => 'archive',
			],
		];

		return $args;
	}

	/**
	 * Manage creating WooCommerce sections.
	 * Should be integrated with jet woo builder plugin too.
	 *
	 * @param string $type type of template.
	 * @param array  $args query arguments.
	 * @since 2.0.5
	 * @return array
	 */
	private function manage_create_woo_sections( $type, $args ) {
		$args['meta_input']['_elementor_template_type'] = $type;

		if ( 'product' === $type && class_exists( 'Jet_Woo_Builder' ) ) {
			$args['meta_input']['_elementor_template_type'] = 'jet-woo-builder';
		}

		if ( 'product-archive' === $type && class_exists( 'Jet_Woo_Builder' ) ) {
			$args['meta_input']['_elementor_template_type'] = 'jet-woo-builder-archive';
		}

		return $args;
	}

	/**
	 * Manage getting WooCommerce sections.
	 * Should be integrated with jet woo builder plugin too.
	 *
	 * @param string $type type of template.
	 * @param array  $args query arguments.
	 * @since 2.0.5
	 * @return array
	 */
	private function manage_get_woo_sections( $type, $args ) {
		$args['meta_query'][0]['value']   = [ $type ];
		$args['meta_query'][0]['compare'] = 'IN';
		$args['meta_query']['relation']   = 'AND';
		$args['meta_query'][]             = [
			'key'   => 'jx-layout-type',
			'value' => $type,
		];

		if ( class_exists( 'Jet_Woo_Builder' ) && 'product' === $type ) {
			$args['meta_query'][0]['value']   = [ 'product', 'jet-woo-builder' ];
			$args['meta_query'][0]['compare'] = 'IN';
			return $args;
		}

		if ( class_exists( 'Jet_Woo_Builder' ) && 'product-archive' === $type ) {
			$args['meta_query'][0]['value']   = [ 'product-archive', 'jet-woo-builder-archive' ];
			$args['meta_query'][0]['compare'] = 'IN';
			return $args;
		}

		return $args;
	}

	private function get_post_types() {
		$post_types = [];
		$args       = [
			'public'   => true,
			'_builtin' => false,
		];

		$types = get_post_types( $args, 'object', 'and' );

		foreach ( $types as $type ) {
			$post_types[ $type->name ] = $type->label;
		}

		// Add default WordPress post types.
		$post_types['post'] = get_post_type_object( 'post' )->label;
		$post_types['page'] = get_post_type_object( 'page' )->label;

		wp_send_json_success( $post_types );
	}

	private function get_post_archives() {
		$archives = [];
		$args     = [
			'public'   => true,
			'_builtin' => false,
		];

		$post_types = get_post_types( $args, 'object', 'and' );

		foreach ( $post_types as $post ) {
			// Escape post without archive.
			if ( false === $post->has_archive ) {
				continue;
			}

			$taxonomies = get_object_taxonomies( $post->name, 'object' );
			if ( empty( $taxonomies ) ) {
				continue;
			}

			foreach ( $taxonomies as $taxonomy ) {
				$archives[ $post->name ][ $taxonomy->name ] = $taxonomy->label;
			}
		}

		wp_send_json_success( $archives );
	}

	/**
	 * Export template using ajax.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function export() {
		$post_id = filter_input( INPUT_POST, 'post', FILTER_SANITIZE_NUMBER_INT );

		if ( empty( $post_id ) ) {
			wp_send_json_error( esc_html__( 'Something went wrong with template.', 'jupiterx-core' ) );
		}

		$document = Plugin::$instance->documents->get( $post_id );

		$template_data = $document->get_export_data();

		$export_data = [
			'content'       => $template_data['content'],
			'page_settings' => $template_data['settings'],
			'version'       => DB::DB_VERSION,
			'title'         => get_the_title( $post_id ),
			'type'          => get_post_meta( $post_id, '_elementor_template_type', true ),
			'conditions'    => get_post_meta( $post_id, 'jupiterx-condition-rules', true ),
			'page_template' => get_post_meta( $post_id, '_wp_page_template', true ),
		];

		wp_send_json_success( $export_data );
	}

	/**
	 * Import Template using ajax.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function import() {
		$files  = filter_var_array( $_FILES, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$json   = $files['file']['tmp_name'];
		$type   = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$ext    = filter_input( INPUT_POST, 'ext', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$result = true;

		if ( empty( $type ) || empty( $ext ) ) {
			wp_send_json_error( esc_html__( 'We could not find template type.', 'jupiterx-core' ) );
		}

		if ( empty( $json ) ) {
			wp_send_json_error( esc_html__( 'Failed to upload.', 'jupiterx-core' ) );
		}

		if ( 'json' === $ext ) {
			$result = $this->import_single_template( $json, $type );
		}

		if ( false === $result ) {
			wp_send_json_error( esc_html__( 'We could not import template', 'jupiterx-core' ) );
		}

		Plugin::$instance->uploads_manager->set_elementor_upload_state( true );

		if ( 'zip' === $ext ) {
			$extracted_files = Plugin::$instance->uploads_manager->extract_and_validate_zip( $json, [ 'json' ] );

			$i = 0;
			foreach ( $extracted_files['files'] as $path ) {
				$result = $this->import_single_template( $path, $type );

				if ( false === $result ) {
					$i++;
				}
			}
		}

		wp_send_json_success( esc_html__( 'Template imported successfully.', 'jupiterx-core' ) );
	}

	/**
	 * Import single template.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function import_single_template( $file_path, $type ) {
		$content_string = file_get_contents( $file_path ); // phpcs:disable
		$content_array  = json_decode( $content_string, true );

		if ( empty( $content_array ) ) {
			return false;
		}

		$content    = $content_array['content'];
		$settings   = $content_array['page_settings'];
		$title      = $content_array['title'];
		$conditions = $content_array['conditions'];

		if ( ! is_array( $content ) ) {
			return false;
		}

		$document = Plugin::$instance->documents->create(
			$type,
			[
				'post_title'  => $title,
				'post_status' => 'publish',
				'post_type'   => 'elementor_library',
			]
		);

		$content = $this->replace_elements_ids( $content );

		$document->save( [
			'elements' => $content,
			'settings' => $settings,
		] );

		Plugin::$instance->uploads_manager->remove_file_or_dir( $file_path );

		$id = $document->get_main_id();

		// Layout builder type.
		update_post_meta( $id, 'jx-layout-type', $type );

		// Update post template.
		$template = $content_array['page_template'];
		$allowed  = [ 'full-width.php', 'elementor_header_footer', 'elementor_canvas' ];

		if ( empty( $template ) || ! in_array( $template, $allowed, true ) ) {
			$template = 'full-width.php';
		}

		update_post_meta( $id, '_wp_page_template', $template );

		// Update conditions for template.
		update_post_meta( $id, 'jupiterx-condition-rules', $conditions );
		JupiterX_Core_Condition_Manager::get_instance()->add_posts_id_with_conditions( $id, $conditions );

		// Update condition string.
		jupiterx_core()->load_files(
			[
				'condition/classes/conditions-logic',
			]
		);

		$converter         = new Conditions_Logic();
		$conditions_string = $converter->manage_conditions_array( $conditions );
		update_post_meta( $id, JupiterX_Core_Condition_Manager::JUPITERX_CONDITIONS_COMPONENT_META_STRING, $conditions_string );

		return $id;
	}

	/**
	 * Replace Elementor id replacement.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	private function replace_elements_ids( $content ) {
		return Plugin::$instance->db->iterate_data( $content, function( $element ) {
			$element['id'] = Utils::generate_random_string();

			return $element;
		} );
	}

	/**
	 * Get layout builder header/footer settings.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function get_header_footer_settings() {
		$mode     = filter_input( INPUT_POST, 'mode', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$post     = filter_input( INPUT_POST, 'post', FILTER_SANITIZE_NUMBER_INT );
		$response = '';

		if ( 'footer' === $mode ) {
			$response = $this->get_footer_settings( $post );
		}

		if ( 'header' === $mode ) {
			$response = $this->get_header_settings( $post );
		}

		if ( empty( $response ) ) {
			$response = null;
		}

		wp_send_json_success( $response );
	}

	/**
	 * Save layout builder footer settings as post meta.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function set_footer_settings() {
		$post  = filter_input( INPUT_POST, 'post', FILTER_SANITIZE_NUMBER_INT );
		$value = filter_input( INPUT_POST, 'value', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		if ( empty( $post ) || empty( $value ) ) {
			wp_send_json_error();
		}

		update_post_meta( $post, 'jx-layout-builder-footer-behavior', $value );

		wp_send_json_success();
	}

	/**
	 * Save layout builder header settings as post meta.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function set_header_settings() {
		$post  = filter_input( INPUT_POST, 'post', FILTER_SANITIZE_NUMBER_INT );
		$value = filter_input( INPUT_POST, 'value', FILTER_DEFAULT, FILTER_FORCE_ARRAY );

		if ( empty( $post ) || empty( $value ) ) {
			wp_send_json_error();
		}

		update_post_meta( $post, 'jx-layout-builder-header-behavior', $value );

		wp_send_json_success( $value );
	}


	/**
	 * Get specific post meta value.
	 *
	 * @param int $post post id.
	 * @since 2.0.0
	 * @return string
	 */
	private function get_footer_settings( $post ) {
		$value = get_post_meta( $post, 'jx-layout-builder-footer-behavior', true );

		return $value;
	}

	/**
	 * Get specific post meta value.
	 *
	 * @param int $post post id.
	 * @since 2.0.0
	 * @return string
	 */
	private function get_header_settings( $post ) {
		$value = get_post_meta( $post, 'jx-layout-builder-header-behavior', true );

		return $value;
	}

	/**
	 * Gets Elementor templates for header sticky.
	 *
	 * @since 2.0.0
	 */
	private function get_sticky_header_templates() {
		$input = filter_input( INPUT_GET, 'input_value', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		if ( empty( $input ) ) {
			wp_send_json_error();
		}

		$filtered_templates = [];
		$args               = [
			'post_type' => 'elementor_library',
			'post_status' => 'publish',
			's' => $input,
			'posts_per_page' => 20,
			'meta_query'     => [ //phpcs:ignore
				[
					'key'     => '_elementor_template_type',
					'value'   => 'header',
				],
				[
					'key'     => 'jx-layout-builder-hidden-type',
					'value'   => 'sticky-header',
				],
			],
		];

		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$filtered_templates[] = [
					'label' => html_entity_decode( get_the_title() ),
					'value' => get_the_ID(),
				];
			}
		}

		wp_send_json_success( $filtered_templates );
	}

	/**
	 * Active Elementor filter import security option.
	 *
	 * @since 2.5.0
	 */
	private function active_elementor_import_security() {
		update_option( 'elementor_unfiltered_files_upload', 1 );

		wp_send_json_success();
	}

	/**
	 * Get layout builder condition dialog default options.
	 *
	 * @since 3.2.0
	 */
	private function get_default_options() {
		$types    = filter_input( INPUT_POST, 'post_types', FILTER_DEFAULT, FILTER_FORCE_ARRAY );
		$response = [];

		global $wpdb;

		foreach ( $types as $type ) {
			$posts = $wpdb->get_results( // phpcs:ignore
				$wpdb->prepare(
					"SELECT ID, post_title FROM $wpdb->posts
					WHERE `post_type` = %s
					AND `post_status` IN ( 'publish', 'private' )
					ORDER BY post_modified DESC LIMIT 50",
					$type,
				)
			);

			$items = [];

			foreach ( $posts as $post ) {
				$items[] = [
					'value' => $post->ID,
					'label' => $post->post_title,
					'link'  => get_permalink( $post->ID ),
				];
			}

			$response[ $type ] = $items;
		}

		wp_send_json_success( $response );
	}

	/**
	 * Replace inner join for get posts queries with straight join to improve the performance.
	 *
	 * @param string $join The join clause of the query.
	 * @since 2.7.0
	 */
	public function replace_inner_with_straight_joins( $join ) {
		global $wpdb;

		$default_query  = 'INNER JOIN ' . $wpdb->prefix;
		$replaced_query = 'STRAIGHT_JOIN ' . $wpdb->prefix;

		$join = str_replace( $default_query, $replaced_query, $join );

		return $join;
	}

	/**
	 * Check & retrieve posts that used a template as header, footer or page-title-bar.
	 *
	 * @since 3.2.0
	 */
	private function check_templates_meta_conditions() {
		$template_id = filter_input( INPUT_POST, 'template_id', FILTER_SANITIZE_NUMBER_INT );
		$type        = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$found_posts = [];
		$args        = [];

		if ( empty( $template_id ) || empty( $type ) ) {
			wp_send_json_error();
		}

		if ( 'header' === $type ) {
			$args = [
				'post_type' => 'any',
				'posts_per_page' => 30,
				'meta_query' => [
					'relation' => 'AND',
					[
						'key' => 'jupiterx_header_type',
						'value' => '_custom',
						'compare' => '='
					],
					[
						'key' => 'jupiterx_header_template',
						'value' => $template_id,
						'compare' => '='
					]
				]
			];
		}

		if ( 'footer' === $type ) {
			$args = [
				'post_type' => 'any',
				'posts_per_page' => 30,
				'meta_query' => [
					'relation' => 'AND',
					[
						'key' => 'jupiterx_footer_type',
						'value' => '_custom',
						'compare' => '='
					],
					[
						'key' => 'jupiterx_footer_template',
						'value' => $template_id,
						'compare' => '='
					]
				]
			];
		}

		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			$found_posts  = $query->get_posts();
			$default_name = 'jupiterx_' . $type . '_template';

			foreach ( $found_posts as $key => $data ) {
				$data->default_value = get_post_meta( $data->ID, $default_name, true );
				$data->permalink     = get_permalink( $data->ID );
				$data->edit_link     = get_edit_post_link( $data->ID );
			}
		}

		wp_reset_postdata();

		if ( empty( $found_posts ) ) {
			wp_send_json_error();
		}

		$args = [
			'post_type'      => 'elementor_library',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_query'     => [ //phpcs:ignore
				[
					'key'   => '_elementor_template_type',
					'value' => $type,
				],
			],
		];

		$query = new \WP_Query( $args );
		$query->set( 'replace_inner_join_with_straight_join', true );

		$templates = $query->get_posts();

		wp_send_json_success( [
			'posts'     => $found_posts,
			'templates' => $templates,
		] );
	}

	/**
	 * Changes post template part( header or footer ) id.
	 *
	 * @since 3.2.0
	 */
	private function change_post_template_part() {
		$post_id   = filter_input( INPUT_POST, 'post', FILTER_SANITIZE_NUMBER_INT );
		$template  = filter_input( INPUT_POST, 'template', FILTER_SANITIZE_NUMBER_INT );
		$type      = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$meta_name = 'jupiterx_footer_template';

		if ( 'header' === $type ) {
			$meta_name = 'jupiterx_header_template';
		}

		update_post_meta( $post_id, $meta_name, $template );

		wp_send_json_success();
	}

	/**
	 * Remove post template part options.
	 *
	 * @since 3.2.0
	 */
	private function remove_templates_meta_conditions() {
		$post_id            = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT );
		$type               = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$meta_name_template = 'jupiterx_footer_template';
		$meta_name_type     = 'jupiter_footer_type';

		if ( 'header' === $type ) {
			$meta_name_template = 'jupiterx_header_template';
			$meta_name_type     = 'jupiter_header_type';
		}

		update_post_meta( $post_id, $meta_name_template, '' );
		update_post_meta( $post_id, $meta_name_type, 'global' );

		wp_send_json_success();
	}
}

JupiterX_Core_Control_Panel_Layout_Builder::get_instance();
