<?php
namespace JupiterX_Core\Raven\Modules\Posts\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Utils;
use JupiterX_Core\Raven\Modules\Posts\Classes\Base_Widget;
use JupiterX_Core\Raven\Modules\Posts\Post\Skins;
use JupiterX_Core\Raven\Controls\Query as Control_Query;

class Posts extends Base_Widget {

	protected $archive_query;

	public function get_name() {
		return 'raven-posts';
	}

	public function get_title() {
		return esc_html__( 'Posts', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-posts';
	}

	public function get_script_depends() {
		return [
			'imagesloaded',
			'jupiterx-core-raven-savvior',
			'jupiterx-core-raven-object-fit',
		];
	}

	public function get_style_depends() {
		return [ 'dashicons' ];
	}

	protected function register_skins() {
		$this->add_skin( new Skins\Classic( $this ) );
		$this->add_skin( new Skins\Cover( $this ) );
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_settings_controls();
		$this->register_sort_filter_controls();
	}

	protected function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'is_archive_template', [
				'label'              => esc_html__( 'Is Archive Template', 'jupiterx-core' ),
				'type'               => 'switcher',
				'label_on'           => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off'          => esc_html__( 'No', 'jupiterx-core' ),
				'return_value'       => 'true',
				'default'            => '',
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			'raven-posts',
			[
				'name' => 'query',
				'condition'    => [
					'is_archive_template' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->update_control(
			'_skin',
			[
				'frontend_available' => 'true',
			]
		);
	}

	protected function register_settings_controls() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'query_posts_per_page',
			[
				'label' => __( 'Posts Per Page', 'jupiterx-core' ),
				'description' => __( 'Use -1 to show all posts.', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 6,
				'min' => -1,
				'max' => 50,
				'frontend_available' => true,
				'condition'    => [
					'is_archive_template' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_sort_filter_controls() {
		$this->start_controls_section(
			'section_sort_filter',
			[
				'label' => __( 'Sort & Filter', 'jupiterx-core' ),
				'condition'    => [
					'is_archive_template' => '',
				],
			]
		);

		$this->add_control(
			'query_orderby',
			[
				'label' => __( 'Order By', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'date',
				'options' => [
					'date' => __( 'Date', 'jupiterx-core' ),
					'title' => __( 'Title', 'jupiterx-core' ),
					'menu_order' => __( 'Menu Order', 'jupiterx-core' ),
					'rand' => __( 'Random', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'query_order',
			[
				'label' => __( 'Order', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'DESC',
				'options' => [
					'ASC' => __( 'ASC', 'jupiterx-core' ),
					'DESC' => __( 'DESC', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'query_offset',
			[
				'label' => __( 'Offset', 'jupiterx-core' ),
				'description' => __( 'Use this setting to skip over posts (e.g. \'4\' to skip over 4 posts).', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 0,
				'min' => 0,
				'max' => 100,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'query_excludes',
			[
				'label' => __( 'Excludes', 'jupiterx-core' ),
				'type' => 'select2',
				'multiple' => true,
				'label_block' => true,
				'default' => [ 'current_post' ],
				'options' => [
					'current_post' => __( 'Current Post', 'jupiterx-core' ),
					'manual_selection' => __( 'Manual Selection', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'query_excludes_ids',
			[
				'label' => __( 'Search & Select', 'jupiterx-core' ),
				'type' => 'raven_query',
				'options' => [],
				'label_block' => true,
				'multiple' => true,
				'condition' => [
					'query_excludes' => 'manual_selection',
				],
				'query' => [
					'source' => Control_Query::QUERY_SOURCE_POST,
					'control_query' => [
						'post_type' => 'query_post_type',
					],
				],
			]
		);

		$this->end_controls_section();
	}

	public function get_query_posts() {
		$settings            = $this->get_settings();
		$args                = Utils::get_query_args( $settings );
		$is_archive_template = ! empty( $settings['is_archive_template'] );

		$lang = filter_input( INPUT_POST, 'lang' );

		if (
			function_exists( 'pll_the_languages' ) &&
			! empty( $lang ) &&
			pll_default_language() !== $lang
		) {
			$args['lang'] = $lang;
		}

		if ( $is_archive_template ) {
			global $wp_query;

			$args = $wp_query->query_vars;

			if ( $this->archive_query ) {
				$args = $this->archive_query;
			}
		}

		$skin = $this->get_current_skin();

		if ( $skin ) {
			$show_pagination = $skin->get_control_id( 'show_pagination' );

			// Disable found rows when pagination is disabled.
			if ( ! $settings[ $show_pagination ] ) {
				$args['no_found_rows'] = true;
			}
		}

		add_action( 'pre_get_posts', [ $this, 'sticky_posts' ], 20 );

		$args = apply_filters( 'jupiterx-raven-posts-query-arguments', $args );

		$new_query = new \WP_Query( $args );

		remove_action( 'pre_get_posts', [ $this, 'sticky_posts' ], 20 );

		return $new_query;
	}

	public function ajax_get_queried_posts( $archive_query ) {
		$this->archive_query = $archive_query;

		$skin = $this->get_current_skin();

		if ( ! $skin ) {
			return;
		}

		$skin->set_parent( $this );

		$queried_posts = $skin->get_queried_posts();

		return $queried_posts;
	}

	public function sticky_posts( $query ) {
		// Hack to make sticky posts work on preview.
		if ( ! $query->get( 'ignore_sticky_posts' ) ) {
			$query->is_home = true;
		}
	}

	protected function render() {}
}
