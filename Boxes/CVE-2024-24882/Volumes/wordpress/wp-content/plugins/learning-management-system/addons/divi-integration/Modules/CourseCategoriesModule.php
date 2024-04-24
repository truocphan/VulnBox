<?php
/**
 * Course Categories Divi Module.
 *
 * @since 1.6.13
 */

namespace Masteriyo\Addons\DiviIntegration\Modules;

use Masteriyo\Addons\DiviIntegration\DiviModule;
use Masteriyo\Taxonomy\Taxonomy;

defined( 'ABSPATH' ) || exit;

/**
 * Course Categories Divi Module.
 *
 * @since 1.6.13
 */
class CourseCategoriesModule extends DiviModule {

	/**
	 * Module slug.
	 *
	 * @since 1.6.13
	 *
	 * @var string
	 */
	public $slug = 'masteriyo_course_categories';

	/**
	 * Module properties initialization.
	 *
	 * @since 1.6.13
	 */
	public function init() {
		$this->name            = esc_html__( 'Masteriyo Course Categories', 'masteriyo' );
		$this->icon_path       = MASTERIYO_DIVI_INTEGRATION_DIR . '/svg/course-categories-module-icon.svg';
		$this->advanced_fields = array(
			'button'         => false,
			'margin_padding' => array(),
			'background'     => false,
			'filters'        => array(
				'default'              => false,
				'child_filters_target' => array(
					'css'         => array(
						'main'  => '%%order_class%% .masteriyo-category-card__image img',
						'hover' => '%%order_class%% .masteriyo-category-card__image img',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'thumbnail',
				),
			),
			'text'           => false,
			'max_width'      => false,
			'transform'      => false,
		);

		// Register sections in all the tabs.
		$this->add_section_to_content_tab( 'general', esc_html__( 'General', 'masteriyo' ) );
		$this->add_section_to_content_tab( 'sorting', esc_html__( 'Sorting', 'masteriyo' ) );

		$this->add_section_to_design_tab( 'layout', esc_html__( 'Layout', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'card', esc_html__( 'Card', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'thumbnail', esc_html__( 'Thumbnail', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'title', esc_html__( 'Title', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'courses_count', esc_html__( 'Courses Count', 'masteriyo' ) );

		// Content Tab.
		$this->init_content_controls_of_general_section();
		$this->init_ui_toggle_controls();
		$this->init_content_controls_of_sorting_section();

		// Design Tab.
		$this->init_layout_style_controls();
		$this->init_card_style_controls();
		$this->init_title_style_controls();
		$this->init_courses_count_style_controls();

		// Computed controls.
		$this->add_computed_control(
			'__rendered_course_categories',
			'get_rendered_course_categories',
			array(
				'per_page',
				'columns',
				'order',
				'include_sub_categories',
				'order_by',
				'order',
			)
		);
	}

	/**
	 * Initialize content controls of general section.
	 *
	 * @since 1.6.13
	 */
	protected function init_content_controls_of_general_section() {
		$this->add_control(
			'per_page',
			array(
				'label'       => esc_html__( 'No. of Categories', 'masteriyo' ),
				'type'        => 'number',
				'default'     => 12,
				'tab_slug'    => self::CONTENT_TAB,
				'toggle_slug' => 'general',
			)
		);
		$this->add_control(
			'columns',
			array(
				'label'       => esc_html__( 'Columns', 'masteriyo' ),
				'type'        => 'number',
				'default'     => 3,
				'tab_slug'    => self::CONTENT_TAB,
				'toggle_slug' => 'general',
			)
		);
		$this->add_show_hide_control(
			'include_sub_categories',
			esc_html__( 'Include Sub-Categories', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-course--img-wrap',
			array(
				'options' => array(
					'on'  => esc_html__( 'Include', 'masteriyo' ),
					'off' => esc_html__( 'Exclude', 'masteriyo' ),
				),
			)
		);
	}

	/**
	 * Initialize controls to show/hide UI elements.
	 *
	 * @since 1.6.13
	 */
	protected function init_ui_toggle_controls() {
		$this->add_show_hide_control(
			'show_thumbnail',
			esc_html__( 'Thumbnail', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-category-card__image'
		);
		$this->add_show_hide_control(
			'show_details',
			esc_html__( 'Details', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-category-card__detail',
			array()
		);
		$this->add_show_hide_control(
			'show_title',
			esc_html__( 'Title', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-category-card__title',
			array(
				'show_if' => array(
					'show_details' => 'on',
				),
			)
		);
		$this->add_show_hide_control(
			'show_courses_count',
			esc_html__( 'Courses Count', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-category-card__courses',
			array(
				'show_if' => array(
					'show_details' => 'on',
				),
			)
		);
	}

	/**
	 * Initialize content controls of sorting section.
	 *
	 * @since 1.6.13
	 */
	protected function init_content_controls_of_sorting_section() {
		$this->add_control(
			'order_by',
			array(
				'label'       => esc_html__( 'Order By', 'masteriyo' ),
				'type'        => 'select',
				'options'     => array(
					'name'  => esc_html__( 'Title', 'masteriyo' ),
					'count' => esc_html__( 'Courses Count', 'masteriyo' ),
				),
				'default'     => 'title',
				'tab_slug'    => self::CONTENT_TAB,
				'toggle_slug' => 'sorting',
			)
		);
		$this->add_control(
			'order',
			array(
				'label'       => esc_html__( 'Order', 'masteriyo' ),
				'type'        => 'select',
				'options'     => array(
					'ASC'  => esc_html__( 'ASC', 'masteriyo' ),
					'DESC' => esc_html__( 'DESC', 'masteriyo' ),
				),
				'default'     => 'ASC',
				'tab_slug'    => self::CONTENT_TAB,
				'toggle_slug' => 'sorting',
			)
		);
	}

	/**
	 * Initialize layout style controls.
	 *
	 * @since 1.6.13
	 */
	protected function init_layout_style_controls() {
		$this->add_range_control(
			'columns_gap',
			self::DESIGN_TAB,
			'layout',
			array(
				'.masteriyo-col'               => 'padding-left: calc( {{columns_gap}} / 2 ) !important; padding-right: calc( {{columns_gap}} / 2 ) !important;',
				'.masteriyo-course-categories' => 'margin-left: calc( -{{columns_gap}} / 2 ) !important; margin-right: calc( -{{columns_gap}} / 2 ) !important;',
			),
			array(
				'label' => esc_html__( 'Columns Gap', 'masteriyo' ),
				'min'   => 0,
				'max'   => 200,
				'step'  => 1,
			)
		);
		$this->add_range_control(
			'rows_gap',
			self::DESIGN_TAB,
			'layout',
			array(
				'.masteriyo-col'               => 'padding-top: calc( {{rows_gap}} / 2 ) !important; padding-bottom: calc( {{rows_gap}} / 2 ) !important;',
				'.masteriyo-course-categories' => 'margin-top: calc( -{{rows_gap}} / 2 ) !important; margin-bottom: calc( -{{rows_gap}} / 2 ) !important;',
			),
			array(
				'label' => esc_html__( 'Rows Gap', 'masteriyo' ),
				'min'   => 0,
				'max'   => 200,
				'step'  => 1,
			)
		);
	}

	/**
	 * Initialize card style controls.
	 *
	 * @since 1.6.13
	 */
	protected function init_card_style_controls() {
		$this->add_text_region_style_controls(
			'card_',
			self::DESIGN_TAB,
			'card',
			'.masteriyo-category-card',
			array(
				'disable_fonts_control' => true,
			)
		);
	}

	/**
	 * Initialize title style controls.
	 *
	 * @since 1.6.13
	 */
	protected function init_title_style_controls() {
		$this->add_text_region_style_controls(
			'title_',
			self::DESIGN_TAB,
			'title',
			'.masteriyo-category-card__title',
			array(
				'custom_selectors' => array(
					'fonts_control' => '.masteriyo-category-card__title a',
				),
			)
		);
	}

	/**
	 * Initialize courses_count style controls.
	 *
	 * @since 1.6.13
	 */
	protected function init_courses_count_style_controls() {
		$this->add_text_region_style_controls(
			'courses_count_',
			self::DESIGN_TAB,
			'courses_count',
			'.masteriyo-category-card__courses',
			array()
		);
	}

	/**
	 * Get rendered course categories list.
	 *
	 * @since 1.6.13
	 *
	 * @return string
	 */
	public static function get_rendered_course_categories( $props = array() ) {
		$props                  = wp_parse_args(
			$props,
			array(
				'per_page'               => 12,
				'columns'                => 3,
				'include_sub_categories' => true,
				'show_courses_count'     => true,
				'order'                  => 'ASC',
				'order_by'               => 'name',
			)
		);
		$limit                  = max( absint( $props['per_page'] ), 1 );
		$columns                = max( absint( $props['columns'] ), 1 );
		$attrs                  = array();
		$include_sub_categories = masteriyo_string_to_bool( $props['include_sub_categories'] );
		$hide_courses_count     = ! masteriyo_string_to_bool( $props['show_courses_count'] );
		$args                   = array(
			'taxonomy'   => Taxonomy::COURSE_CATEGORY,
			'order'      => masteriyo_array_get( $props, 'order', 'ASC' ),
			'orderby'    => masteriyo_array_get( $props, 'order_by', 'name' ),
			'number'     => $limit,
			'hide_empty' => false,
		);

		if ( ! masteriyo_string_to_bool( $include_sub_categories ) ) {
			$args['parent'] = 0;
		}

		/**
		 * Filters the prepared query args for the course categories query in Course Categories Divi module.
		 *
		 * @since 1.6.13
		 *
		 * @param array $args
		 */
		$args = apply_filters( 'masteriyo_course_categories_module_prepare_query_args', $args );

		$query      = new \WP_Term_Query();
		$result     = $query->query( $args );
		$categories = array_filter( array_map( 'masteriyo_get_course_cat', $result ) );

		$attrs['count']                  = $limit;
		$attrs['columns']                = $columns;
		$attrs['categories']             = $categories;
		$attrs['hide_courses_count']     = $hide_courses_count;
		$attrs['include_sub_categories'] = $include_sub_categories;

		ob_start();

		printf( '<div class="masteriyo">' );
		masteriyo_get_template( 'shortcodes/course-categories/list.php', $attrs );
		echo '</div>';

		return \ob_get_clean();
	}

	/**
	 * Render module in the frontend. Must return the output HTML instead of doing 'print', 'echo' etc.
	 *
	 * @since 1.6.13
	 *
	 * @param array  $unprocessed_props List of unprocessed attributes.
	 * @param string $content Content being processed.
	 * @param string $render_slug Slug of module that is used for rendering output.
	 *
	 * @return string
	 */
	public function render( $unprocessed_props, $content, $render_slug ) {
		$this->generate_module_styles( $render_slug );

		return $this->_render_module_wrapper( static::get_rendered_course_categories( $this->props ), $render_slug );
	}
}
