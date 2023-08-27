<?php

namespace JupiterX_Core\Raven\Modules\Preview_Settings;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;
use Elementor\Plugin;
use JupiterX_Core\Raven\Controls\Query as Control_Query;
use Elementor\Icons_Manager;

/**
 * Handle Elementor editor preview settings.
 *
 * @since 2.5.0
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
class Module extends Module_Base {

	/**
	 * Construct.
	 *
	 * @since 2.5.0
	 */
	public function __construct() {
		// Deactivate if Elementor pro is activated.
		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			return;
		}

		parent::__construct();

		add_action( 'elementor/documents/register_controls', [ $this, 'manage_preview_settings_controls' ] );

		add_action( 'elementor/frontend/before_enqueue_styles', [ $this, 'enqueue_shim_js' ] );

		add_action( 'elementor/dynamic_tags/before_render', [ $this, 'modify_dynamic_tags_before_rendering' ], 999 );
		add_action( 'elementor/dynamic_tags/after_render', [ $this, 'revert_dynamic_tags_to_default' ] );

		// Single modification.
		add_action( 'elementor/editor/init', [ $this, 'modify_single_query' ] );
		add_action( 'elementor/ajax/register_actions', [ $this, 'modify_single_query' ] );
		add_action( 'elementor/widget/before_render_content', [ $this, 'modify_widget_before_rendering' ] );

		// Let's make sure our feature bypass jetPopup conflict. priority must be less than 11, since jetPopup has 11.
		if ( class_exists( 'Jet_Popup' ) ) {
			add_action( 'elementor/frontend/widget/before_render', [ $this, 'modify_widget_before_rendering' ], 10 );
		}

		// Archive modification.
		add_action( 'elementor/editor/init', [ $this, 'modify_archive_query_args' ], 99 );
		add_action( 'elementor/ajax/register_actions', [ $this, 'modify_archive_query_args' ] );
		add_action( 'elementor/widget/before_render_content', [ $this, 'modify_archive_query_args' ], 99 );

		add_action( 'elementor/frontend/after_render', [ $this, 'reset_query_after_archive_simulation' ], 10, 1 );
	}

	/**
	 * Manage each template type of editor templates for simulation.
	 *
	 * @since 2.5.0
	 */
	public function manage_preview_settings_controls( $parent ) {
		$post_id                = $parent->get_main_id();
		$settings               = [];
		$settings['main_id']    = $post_id;
		$settings['jx_type']    = get_post_meta( $post_id, 'jx-layout-type', true );
		$settings['type']       = get_post_meta( $post_id, '_elementor_template_type', true );
		$settings['preview']    = get_post_meta( $post_id, '_elementor_page_settings', true );
		$settings['post_types'] = $this->available_post_types();

		$this->register_preview_settings_controls( $parent, $settings );
	}

	/**
	 * Integration with font awesome v4.
	 *
	 * @since 2.5.0
	 */
	public function enqueue_shim_js() {
		if ( 'yes' === get_option( 'elementor_load_fa4_shim' ) ) {
			return;
		}

		$id = get_the_ID();

		if ( ! metadata_exists( 'post', $id, 'jx-layout-type' ) ) {
			return;
		}

		Icons_Manager::enqueue_shim();
	}

	/**
	 * Simulate single section.
	 *
	 * @since 2.5.0
	 */
	public function modify_single_query() {
		$id = get_the_ID();

		if ( wp_doing_ajax() ) {
			$id = filter_var( $_REQUEST['editor_post_id'], FILTER_SANITIZE_NUMBER_INT ); //phpcs:ignore
		}

		$settings = get_post_meta( $id, '_elementor_page_settings', true );

		if ( empty( $settings ) ) {
			return;
		}

		if ( ! is_array( $settings ) ) {
			$settings = [];
		}

		// By default this raven-post control does not save post post-type. we set it if post_includes is present.
		$settings = $this->filter_settings( $settings );

		if ( ! array_key_exists( 'preview_settings_jx_post_type', $settings ) ) {
			return;
		}

		$post_type = $settings['preview_settings_jx_post_type'];

		if ( 'disable' === $post_type || ! array_key_exists( "preview_settings_jx_{$post_type}_includes", $settings ) ) {
			return;
		}

		$id = (int) $settings[ "preview_settings_jx_{$post_type}_includes" ];

		if ( empty( $id ) || empty( $post_type ) ) {
			return;
		}

		if ( 'product' === $post_type ) {
			wc()->frontend_includes();
		}

		$query = [
			'p'         => $id,
			'post_type' => $post_type,
		];

		Plugin::$instance->db->switch_to_query( $query, $force_global_post = true );
	}

	/**
	 * Before widget rendering content.
	 *
	 * @param object $element element
	 * @since 2.6.4
	 */
	public function modify_widget_before_rendering( $element ) {
		$preview    = filter_input( INPUT_GET, 'preview', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$preview_id = filter_input( INPUT_GET, 'preview_id', FILTER_SANITIZE_NUMBER_INT );

		if ( empty( $preview ) || empty( $preview_id ) ) {
			return;
		}

		$settings = get_post_meta( $preview_id, '_elementor_page_settings', true );

		if ( empty( $settings ) ) {
			return;
		}

		if ( ! is_array( $settings ) ) {
			$settings = [];
		}

		if (
			! array_key_exists( 'preview_settings_jx_post_type', $settings ) &&
			! array_key_exists( 'preview_settings_jx_post_includes', $settings )
		) {
			return;
		}

		$settings = $this->filter_settings( $settings );

		if ( empty( $settings['preview_settings_jx_post_type'] ) ) {
			return;
		}

		$post_type = $settings['preview_settings_jx_post_type'];

		if ( ! array_key_exists( "preview_settings_jx_{$post_type}_includes", $settings ) ) {
			return;
		}

		$id = (int) $settings[ "preview_settings_jx_{$post_type}_includes" ];

		if ( empty( $id ) || empty( $post_type ) ) {
			return;
		}

		$GLOBALS['post'] = get_post( $id ); // phpcs:ignore

		$this->apply_required_hooks( $id, $element );
	}

	/**
	 * Simulate archive section.
	 *
	 * @since 2.5.0
	 */
	public function modify_archive_query_args( $internal = false ) {
		// Let's disable it in layout builder preview for archive.
		if ( isset( $_GET['jupiterx-layout-builder-type'] ) ) { // phpcs:ignore
			return;
		}

		$id       = get_the_ID();
		$settings = get_post_meta( $id, '_elementor_page_settings', true );
		$layout   = get_post_meta( $id, 'jx-layout-type', true );

		if (
			! is_array( $settings )
			|| ! array_key_exists( 'preview_settings_jx_post_type', $settings )
			|| empty( $settings['preview_settings_jx_post_type'] )
			|| 'disable' === $settings['preview_settings_jx_post_type']
			|| strpos( $settings['preview_settings_jx_post_type'], '/' ) === false
		) {
			return;
		}

		global $wp_query;

		$archive    = $settings['preview_settings_jx_post_type'];
		$_archive   = $archive;
		$archive    = explode( '/', $archive );
		$type       = $archive[0];
		$value      = $archive[1];
		$query_vars = $wp_query->query;

		if ( empty( $type ) || empty( $value ) ) {
			return;
		}

		switch ( $type ) {
			case 'archive':
				switch ( $value ) {
					case 'recent':
						$query_vars['post_type'] = 'post';
						$wp_query->is_archive    = true;
						break;
					case 'date':
						$query_vars['post_type'] = 'post';
						$query_vars['year']      = gmdate( 'Y' );
						break;
					case 'author':
						$query_vars['author']    = $settings['preview_settings_jx_author'];
						$query_vars['post_type'] = 'post';
						break;
					case 'search':
						$query_vars['s']         = $settings['preview_settings_jx_search'];
						$query_vars['post_type'] = 'any';
						break;
				}

				break;
			case 'taxonomy':
				if ( empty( $settings[ "preview_settings_jx_{$_archive}" ] ) ) {
					$settings[ "preview_settings_jx_{$_archive}" ] = '';
				}

				$value = $settings[ "preview_settings_jx_{$_archive}" ];
				$term  = get_term( $value );

				if ( $term && ! is_wp_error( $term ) ) {
					$query_vars['tax_query'] = [
						[
							'taxonomy' => $term->taxonomy,
							'terms' => [ $value ],
							'field' => 'id',
						],
					];
				}
		}

		unset( $query_vars['p'] );

		$query_vars['posts_per_page'] = 8;

		if ( 'product-archive' === $layout ) {
			$query_vars['post_type'] = 'product';
		}

		if ( true === $internal ) {
			return $query_vars;
		}

		if ( 'elementor/widget/before_render_content' === current_action() ) {
			unset( $query_vars['preview'] );
			unset( $query_vars['name'] );

			add_filter( 'jupiterx-raven-posts-query-arguments', function() use ( $query_vars ) {
				return $query_vars;
			} );

			return;
		}

		query_posts( $query_vars ); //phpcs:ignore
	}

	/**
	 * Reset query after simulation in frontend.
	 *
	 * @since 2.5.0
	 */
	public function reset_query_after_archive_simulation( $element ) {
		$preview = filter_input( INPUT_GET, 'elementor_library', FILTER_SANITIZE_NUMBER_INT );

		if ( empty( $preview ) || 'raven-posts' !== $element->get_name() ) {
			return;
		}

		wp_reset_query(); // phpcs:ignore
	}

	/**
	 * Adds preview settings control to elementor settings page.
	 *
	 * @param object $parent parent class.
	 * @param array  $settings preview settings.
	 * @since 2.5.0
	 */
	public function register_preview_settings_controls( $parent, $settings ) {
		$parent->start_controls_section(
			'preview_settings_jx',
			[
				'label' => esc_html__( 'Preview Settings', 'jupiterx-core' ),
				'tab' => 'settings',
			]
		);

		$parent->add_group_control(
			'raven-posts',
			[
				'name' => 'preview_settings_jx',
				'exclude' => [ 'authors', 'taxonomies', 'ignore_sticky_posts' ],
				'fields_options' => $this->raven_post_arguments( $settings ),
			]
		);

		$this->taxonomies_without_archive( $parent );

		$parent->add_control(
			'preview_settings_jx_search',
			[
				'label' => esc_html__( 'Search Term', 'jupiterx-core' ),
				'type' => 'text',
				'condition' => [
					'preview_settings_jx_post_type' => 'archive/search',
				],
			]
		);

		$parent->add_control(
			'preview_settings_jx_author',
			[
				'label' => esc_html__( 'Author', 'jupiterx-core' ),
				'type' => 'raven_query',
				'query' => [
					'source'   => Control_Query::QUERY_SOURCE_AUTHOR,
				],
				'label_block' => true,
				'condition' => [
					'preview_settings_jx_post_type' => 'archive/author',
				],
			]
		);

		$parent->add_control(
			'apply_preview_jx',
			[
				'type' => 'button',
				'label' => esc_html__( 'Apply & Preview', 'jupiterx-core' ),
				'label_block' => true,
				'show_label' => false,
				'text' => esc_html__( 'Apply & Preview', 'jupiterx-core' ),
				'separator' => 'none',
				'classes' => 'elementor-run-jx-preview',
				'event' => 'jupiterXApplyPreview',
			]
		);

		$parent->end_controls_section();
	}

	/**
	 * Filter preview settings.
	 *
	 * @param array $settings preview settings.
	 * @since 2.5.0
	 */
	private function filter_settings( $settings ) {
		if (
			( ! array_key_exists( 'preview_settings_jx_post_type', $settings ) || empty( $settings['preview_settings_jx_post_type'] ) )
			&& ! empty( $settings['preview_settings_jx_post_includes'] )
		) {
			$settings['preview_settings_jx_post_type'] = 'post';
		}

		return $settings;
	}

	/**
	 * Modify elementor dynamic tags based on preview settings.
	 *
	 * @since 2.5.0
	 */
	public function modify_dynamic_tags_before_rendering() {
		$post_id  = get_the_ID();
		$settings = [];
		$settings = get_post_meta( $post_id, '_elementor_page_settings', true );

		if ( empty( $settings ) ) {
			return;
		}

		$settings = $this->filter_settings( $settings );

		if ( empty( $settings['preview_settings_jx_post_type'] ) ) {
			return;
		}

		$post_type = $settings['preview_settings_jx_post_type'];

		if ( strpos( $post_type, '/' ) !== false ) {
			$query_vars = $this->modify_archive_query_args( true );
			Plugin::$instance->db->switch_to_query( $query_vars, true );
			return;
		}

		if ( empty( $settings[ "preview_settings_jx_{$post_type}_includes" ] ) ) {
			return;
		}

		$post_id = (int) $settings[ "preview_settings_jx_{$post_type}_includes" ];

		Plugin::$instance->db->switch_to_post( $post_id );
	}

	/**
	 * Revert back dynamic tags in elementor to default.
	 *
	 * @since 2.5.0
	 */
	public function revert_dynamic_tags_to_default() {
		Plugin::$instance->db->restore_current_post();
		Plugin::$instance->db->restore_current_query();
	}

	/**
	 * Customize raven post control arguments.
	 *
	 * @param array $settings preview settings
	 * @return array
	 * @since 2.5.0
	 */
	private function raven_post_arguments( $settings ) {
		$post_types          = $this->available_post_types();
		$taxonomies          = $this->available_taxonomies( $post_types );
		$fields              = [];
		$fields['post_type'] = [
			'label_block' => true,
			'label'       => esc_html__( 'Preview Dynamic Content as', 'jupiterx-core' ),
		];

		// Single templates options.
		if ( 'single' === $settings['type'] || 'product' === $settings['jx_type'] ) {
			$fields['post_type']['options'] = $post_types;
			$fields['post_type']['default'] = 'post';
		}

		// Archive templates options.
		if ( 'archive' === $settings['type'] || 'product-archive' === $settings['jx_type'] ) {
			$fields['post_type']['options'] = $taxonomies;
			$fields['post_type']['default'] = 'archive/recent_posts';
		}

		// Section templates options.
		$general = [ 'header', 'footer', 'page-title-bar', 'section' ];

		if (
			in_array( $settings['type'], $general, true )
			&& 'product' !== $settings['jx_type']
			&& 'product-archive' !== $settings['jx_type']
		) {
			$fields['post_type']['groups'] = $this->available_groups( $post_types, $taxonomies );

			unset( $fields['post_type']['groups']['archive']['options']['disable'] );
			unset( $fields['post_type']['groups']['single']['options'][''] );
		}

		foreach ( $post_types as $key => $value ) {
			$fields[ $key . '_includes' ] = [
				'multiple'    => false,
				'label_block' => true,
			];
		}

		return $fields;
	}

	/**
	 * Create group for third templates like section.
	 *
	 * @param array $post_types available post types.
	 * @param array $taxonomies available taxonomies.
	 * @since 2.5.0
	 */
	private function available_groups( $post_types, $taxonomies ) {
		unset( $post_types['disable'] );

		return [
			''        => esc_html__( 'Select one', 'jupiterx-core' ),
			'archive' => [
				'label'   => esc_html__( 'Archive', 'jupiterx-core' ),
				'options' => $taxonomies,
			],
			'single' => [
				'label'   => esc_html__( 'Single', 'jupiterx-core' ),
				'options' => $post_types,
			],
		];
	}

	/**
	 * WordPress post types.
	 *
	 * @return array
	 * @since 2.5.0
	 */
	private function available_post_types() {
		$layout   = get_post_meta( get_the_ID(), 'jx-layout-type', true );
		$excluded = [ 'jupiterx-codes', 'sellkit_step', 'jupiterx-fonts', 'jupiterx-icons' ];
		$list     = [];
		$args     = [
			'show_in_nav_menus' => true,
		];

		$post_types = get_post_types( $args, 'objects', 'and' );

		$list['disable'] = esc_html__( 'Disable Preview Settings', 'jupiterx-core' );
		$list['post']    = esc_html__( 'Post', 'jupiterx-core' );
		$list['page']    = esc_html__( 'Page', 'jupiterx-core' );

		foreach ( $post_types as $post_type ) {
			if ( in_array( $post_type->name, $excluded, true ) ) {
				continue;
			}

			$list[ $post_type->name ] = $post_type->label;
		}

		// For archive we disable product post type.
		if ( 'archive' === $layout ) {
			unset( $list['product'] );
		}

		// For product archive we just display product post type.
		if ( 'product-archive' === $layout ) {
			$list = [
				'product' => esc_html__( 'Products', 'jupiterx-core' ),
			];
		}

		// For single product just product and disable.
		if ( 'product' === $layout ) {
			$list = [
				'disable' => esc_html__( 'Disable Preview Settings', 'jupiterx-core' ),
				'product' => esc_html__( 'Products', 'jupiterx-core' ),
			];
		}

		return $list;
	}

	/**
	 * WordPress taxonomies
	 *
	 * @param array $post_types WordPress post types.
	 * @return array
	 * @since 2.5.0
	 */
	private function available_taxonomies( $post_types ) {
		$layout = get_post_meta( get_the_ID(), 'jx-layout-type', true );

		foreach ( $post_types as $post_type => $label ) {
			if ( 'disable' === $post_type ) {
				continue;
			}

			$post_type_object = get_post_type_object( $post_type );

			if ( $post_type_object->has_archive ) {
				/* Translators: 1: post type archive label  */
				$post_type_archives[ 'post_type_archive/' . $post_type ] = sprintf( esc_html__( '%s Archive', 'jupiterx-core' ), $post_type_object->label );
			}

			$post_type_taxonomies = get_object_taxonomies( $post_type, 'objects' );

			$post_type_taxonomies = wp_filter_object_list( $post_type_taxonomies, [
				'public' => true,
				'show_in_nav_menus' => true,
			] );

			foreach ( $post_type_taxonomies as $slug => $object ) {
				/* Translators: 1: taxonomy label  */
				$taxonomies[ 'taxonomy/' . $slug ] = sprintf( esc_html__( '%s Archive', 'jupiterx-core' ), ucwords( $object->label ) );
			}
		}

		$options = [
			'disable'        => esc_html__( 'Disable Preview', 'jupiterx-core' ),
			'archive/recent' => esc_html__( 'Recent Posts', 'jupiterx-core' ),
			'archive/date'   => esc_html__( 'Date Archive', 'jupiterx-core' ),
			'archive/author' => esc_html__( 'Author Archive', 'jupiterx-core' ),
			'archive/search' => esc_html__( 'Search Results', 'jupiterx-core' ),
		];

		// For product archive we just display product post type.
		if ( 'product-archive' === $layout ) {
			$options = [
				'disable'        => esc_html__( 'Disable Preview', 'jupiterx-core' ),
				'archive/search' => esc_html__( 'Search Results', 'jupiterx-core' ),
			];
		}

		// real : $options += $taxonomies + $post_type_archives; post type archive excluded.
		$options += $taxonomies;

		return $options;
	}

	/**
	 * Getting available taxonomies only.
	 *
	 * @param object $parent parent class.
	 * @since 2.5.0
	 */
	private function taxonomies_without_archive( $parent ) {
		$post_types = $this->available_post_types();
		$taxonomies = [];

		foreach ( $post_types as $post_type => $label ) {
			if ( 'disable' === $post_type ) {
				continue;
			}

			$post_type_taxonomies = get_object_taxonomies( $post_type, 'objects' );

			$post_type_taxonomies = wp_filter_object_list( $post_type_taxonomies, [
				'public' => true,
				'show_in_nav_menus' => true,
			] );

			foreach ( $post_type_taxonomies as $slug => $object ) {
				$taxonomies[] = 'taxonomy/' . $slug;
			}
		}

		$taxonomies = array_unique( $taxonomies );

		foreach ( $taxonomies as $taxonomy ) {
			$shortened = str_replace( 'taxonomy/', '', $taxonomy );

			$parent->add_control(
				"preview_settings_jx_{$taxonomy}",
				[
					'label' => esc_html__( 'Taxonomy', 'jupiterx-core' ),
					'type' => 'raven_query',
					'query' => [
						'source'   => Control_Query::QUERY_SOURCE_TAX,
						'taxonomy' => $shortened,
					],
					'label_block' => true,
					'condition' => [
						'preview_settings_jx_post_type' => $taxonomy,
					],
				]
			);
		}
	}

	/**
	 * In the preview mode we apply some hooks, to make sure everything works.
	 *
	 * @param int    $id selected preview page id.
	 * @param object $element current element.
	 * @since 2.6.4
	 */
	private function apply_required_hooks( $id, $element ) {
		if ( 'raven-post-title' === $element->get_name() ) {
			add_filter( 'jupiterx_preview_settings_integration_post_title', function() use ( $id ) {
				return get_the_title( $id );
			} );
		}
	}
}
