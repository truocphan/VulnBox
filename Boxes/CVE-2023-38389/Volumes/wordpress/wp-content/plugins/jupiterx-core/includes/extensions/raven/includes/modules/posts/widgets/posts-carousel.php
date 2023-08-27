<?php
namespace JupiterX_Core\Raven\Modules\Posts\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Utils;
use JupiterX_Core\Raven\Modules\Posts\Classes\Base_Widget;
use JupiterX_Core\Raven\Modules\Posts\Module;
use JupiterX_Core\Raven\Modules\Posts\Carousel\Skins;
use JupiterX_Core\Raven\Controls\Query as Control_Query;

/**
 * Temporary suppressed.
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class Posts_Carousel extends Base_Widget {

	/**
	 * Archive page query.
	 *
	 * @since 3.0.0
	 */
	protected $archive_query;

	public function get_name() {
		return 'raven-posts-carousel';
	}

	public function get_title() {
		return esc_html__( 'Posts Carousel', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-posts-carousel';
	}

	public function get_script_depends() {
		return [ 'swiper', 'jupiterx-core-raven-object-fit', 'imagesloaded' ];
	}

	public function get_style_depends() {
		return [ 'dashicons', 'e-animations' ];
	}

	protected function register_skins() {
		$this->add_skin( new Skins\Classic( $this ) );
		$this->add_skin( new Skins\Cover( $this ) );
	}

	protected function register_controls() {
		$this->register_layout_controls();
		$this->register_settings_controls();
		$this->register_query_controls();
		$this->register_sort_filter_controls();
	}

	protected function register_layout_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'query_posts_per_page',
			[
				'label' => esc_html__( 'How many posts?', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 10,
				'min' => 1,
				'max' => 50,
				'frontend_available' => true,
				'condition'    => [
					'is_archive_template' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->update_control(
			'_skin',
			[
				'label' => esc_html__( 'Content Layout', 'jupiterx-core' ),
				'frontend_available' => 'true',
			]
		);
	}

	protected function register_settings_controls() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->end_controls_section();
	}

	protected function register_query_controls() {
		$this->start_controls_section(
			'section_query',
			[
				'label' => esc_html__( 'Query', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'is_archive_template', [
				'label'        => esc_html__( 'Is Archive Template', 'jupiterx-core' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off'    => esc_html__( 'No', 'jupiterx-core' ),
				'return_value' => 'true',
				'default'      => '',
			]
		);

		$this->add_group_control(
			'raven-posts',
			[
				'name' => 'query',
				'post_type' => Module::get_post_types(),
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
				'label' => esc_html__( 'Sort & Filter', 'jupiterx-core' ),
				'condition'    => [
					'is_archive_template' => '',
				],
			]
		);

		$this->add_control(
			'query_orderby',
			[
				'label' => esc_html__( 'Order By', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'date',
				'options' => [
					'date' => esc_html__( 'Date', 'jupiterx-core' ),
					'title' => esc_html__( 'Title', 'jupiterx-core' ),
					'menu_order' => esc_html__( 'Menu Order', 'jupiterx-core' ),
					'rand' => esc_html__( 'Random', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'query_order',
			[
				'label' => esc_html__( 'Order', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'DESC',
				'options' => [
					'ASC' => esc_html__( 'ASC', 'jupiterx-core' ),
					'DESC' => esc_html__( 'DESC', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'query_offset',
			[
				'label' => esc_html__( 'Offset', 'jupiterx-core' ),
				'description' => esc_html__( 'Use this setting to skip over posts (e.g. \'4\' to skip over 4 posts).', 'jupiterx-core' ),
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
				'label' => esc_html__( 'Excludes', 'jupiterx-core' ),
				'type' => 'select2',
				'multiple' => true,
				'label_block' => true,
				'default' => [ 'current_post' ],
				'options' => [
					'current_post' => esc_html__( 'Current Post', 'jupiterx-core' ),
					'manual_selection' => esc_html__( 'Manual Selection', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'query_excludes_ids',
			[
				'label' => esc_html__( 'Search & Select', 'jupiterx-core' ),
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

		if ( $is_archive_template ) {
			global $wp_query;

			$args = $wp_query->query_vars;

			if ( $this->archive_query ) {
				$args = $this->archive_query;
			}
		}

		// Don't need to collect total rows since this widget doesn't have query based pagination.
		$args['no_found_rows'] = true;

		add_action( 'pre_get_posts', [ $this, 'sticky_posts' ], 20 );

		$args = apply_filters( 'jupiterx-raven-posts-query-arguments', $args );

		$new_query = new \WP_Query( $args );

		remove_action( 'pre_get_posts', [ $this, 'sticky_posts' ], 20 );

		return $new_query;
	}

	public function sticky_posts( $query ) {
		// Hack to make sticky posts work on preview.
		if ( ! $query->get( 'ignore_sticky_posts' ) ) {
			$query->is_home = true;
		}
	}

	public static function excerpt_more() {
		return '';
	}

	protected function render() {}
}
