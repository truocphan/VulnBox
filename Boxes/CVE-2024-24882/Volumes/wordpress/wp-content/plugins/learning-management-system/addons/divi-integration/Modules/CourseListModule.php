<?php
/**
 * Course List Divi Module.
 *
 * @since 1.6.13
 */

namespace Masteriyo\Addons\DiviIntegration\Modules;

use Masteriyo\Addons\DiviIntegration\DiviModule;
use Masteriyo\Enums\PostStatus;
use Masteriyo\PostType\PostType;
use Masteriyo\Taxonomy\Taxonomy;

defined( 'ABSPATH' ) || exit;

/**
 * Course List Divi Module.
 *
 * @since 1.6.13
 */
class CourseListModule extends DiviModule {

	/**
	 * Module slug.
	 *
	 * @since 1.6.13
	 *
	 * @var string
	 */
	public $slug = 'masteriyo_course_list';

	/**
	 * Module properties initialization.
	 *
	 * @since 1.6.13
	 */
	public function init() {
		$this->name            = esc_html__( 'Masteriyo Course List', 'masteriyo' );
		$this->icon_path       = MASTERIYO_DIVI_INTEGRATION_DIR . '/svg/course-list-module-icon.svg';
		$this->advanced_fields = array(
			'button'         => false,
			'margin_padding' => array(),
			'background'     => false,
			'filters'        => array(
				'default'              => false,
				'child_filters_target' => array(
					'css'         => array(
						'main'  => '%%order_class%% .masteriyo-feature-img img',
						'hover' => '%%order_class%% .masteriyo-feature-img img',
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
		$this->add_section_to_content_tab( 'filter', esc_html__( 'Filter', 'masteriyo' ) );
		$this->add_section_to_content_tab( 'sorting', esc_html__( 'Sorting', 'masteriyo' ) );

		$this->add_section_to_design_tab( 'layout', esc_html__( 'Layout', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'card', esc_html__( 'Card', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'thumbnail', esc_html__( 'Thumbnail', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'difficulty_badge', esc_html__( 'Difficulty Badge', 'masteriyo' ) );

		foreach ( $this->get_all_difficulties() as $difficulty ) {
			$slug  = 'difficulty_badge_' . $difficulty->get_slug() . '_';
			$label = esc_html__( 'Difficulty Badge', 'masteriyo' ) . ' (' . $difficulty->get_name() . ')';

			$this->add_section_to_design_tab( $slug, $label );
		}

		$this->add_section_to_design_tab( 'categories', esc_html__( 'Categories', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'categories_item', esc_html__( 'Categories Item', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'title', esc_html__( 'Title', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'author', esc_html__( 'Author', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'author_avatar', esc_html__( 'Author Avatar', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'author_name', esc_html__( 'Author Name', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'rating', esc_html__( 'Rating', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'description', esc_html__( 'Highlights / Description', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'metadata', esc_html__( 'Meta Data', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'footer', esc_html__( 'Footer', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'price', esc_html__( 'Price', 'masteriyo' ) );
		$this->add_section_to_design_tab( 'enroll_button', esc_html__( 'Enroll Button', 'masteriyo' ) );

		// Content Tab.
		$this->init_content_controls_of_general_section();
		$this->init_ui_toggle_controls();
		$this->init_content_controls_of_filter_section();
		$this->init_content_controls_of_sorting_section();

		// Design Tab.
		$this->init_layout_style_controls();
		$this->init_card_style_controls();
		$this->init_difficulty_badge_style_controls();
		$this->init_categories_style_controls();
		$this->init_title_style_controls();
		$this->init_author_style_controls();
		$this->init_rating_style_controls();
		$this->init_description_style_controls();
		$this->init_metadata_style_controls();
		$this->init_footer_style_controls();
		$this->init_price_style_controls();
		$this->init_enroll_button_style_controls();

		// Computed controls.
		$this->add_computed_control(
			'__rendered_course_list',
			'get_rendered_course_list',
			array(
				'per_page',
				'columns',
				'order',
				'include_categories',
				'exclude_categories',
				'include_instructors',
				'exclude_instructors',
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
				'label'       => esc_html__( 'No. of Courses', 'masteriyo' ),
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
			'.masteriyo-course--img-wrap'
		);
		$this->add_show_hide_control(
			'show_difficulty_badge',
			esc_html__( 'Difficulty Badge', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.difficulty-badge',
			array(
				'show_if' => array(
					'show_thumbnail' => 'on',
				),
			)
		);
		$this->add_show_hide_control(
			'show_categories',
			esc_html__( 'Categories', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-course--content__category',
			array()
		);
		$this->add_show_hide_control(
			'show_course_title',
			esc_html__( 'Course Title', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-course--content__title',
			array()
		);
		$this->add_show_hide_control(
			'show_author',
			esc_html__( 'Author', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-course-author',
			array()
		);
		$this->add_show_hide_control(
			'show_author_avatar',
			esc_html__( 'Avatar of Author', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-course-author img',
			array(
				'show_if' => array(
					'show_author' => 'on',
				),
			)
		);
		$this->add_show_hide_control(
			'show_author_name',
			esc_html__( 'Name of Author', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-course-author .masteriyo-course-author--name',
			array(
				'show_if' => array(
					'show_author' => 'on',
				),
			)
		);
		$this->add_show_hide_control(
			'show_rating',
			esc_html__( 'Rating', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-rating',
			array()
		);
		$this->add_show_hide_control(
			'show_description',
			esc_html__( 'Highlights / Description', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-course--content__description',
			array()
		);
		$this->add_show_hide_control(
			'show_metadata',
			esc_html__( 'Meta Data', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-course--content__stats',
			array(
				'description' => esc_html__( 'Show/hide the section containing information on number of students, course hours etc.', 'masteriyo' ),
			)
		);
		$this->add_show_hide_control(
			'show_course_duration',
			esc_html__( 'Course Duration', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-course-stats-duration',
			array(
				'show_if' => array(
					'show_metadata' => 'on',
				),
			)
		);
		$this->add_show_hide_control(
			'show_students_count',
			esc_html__( 'Students Count', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-course-stats-students',
			array(
				'show_if' => array(
					'show_metadata' => 'on',
				),
			)
		);
		$this->add_show_hide_control(
			'show_lessons_count',
			esc_html__( 'Lessons Count', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-course-stats-curriculum',
			array(
				'show_if' => array(
					'show_metadata' => 'on',
				),
			)
		);
		$this->add_show_hide_control(
			'show_footer',
			esc_html__( 'Footer', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-course-card-footer',
			array()
		);
		$this->add_show_hide_control(
			'show_price',
			esc_html__( 'Price', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-course-price',
			array(
				'show_if' => array(
					'show_footer' => 'on',
				),
			)
		);
		$this->add_show_hide_control(
			'show_enroll_button',
			esc_html__( 'Enroll Button', 'masteriyo' ),
			self::CONTENT_TAB,
			'general',
			'.masteriyo-enroll-btn',
			array(
				'show_if' => array(
					'show_footer' => 'on',
				),
			)
		);
	}

	/**
	 * Initialize content controls of filter section.
	 *
	 * @since 1.6.13
	 */
	protected function init_content_controls_of_filter_section() {
		$categories_options  = $this->get_categories_options();
		$instructors_options = $this->get_instructors_options();

		$this->add_control(
			'include_categories',
			array(
				'label'           => esc_html__( 'Include Categories', 'masteriyo' ),
				'type'            => 'multiple_checkboxes',
				'multi_selection' => true,
				'options'         => $categories_options,
				'tab_slug'        => 'general',
				'toggle_slug'     => 'filter',
			)
		);
		$this->add_control(
			'include_instructors',
			array(
				'label'       => esc_html__( 'Include Instructors', 'masteriyo' ),
				'type'        => 'multiple_checkboxes',
				'options'     => $instructors_options,
				'tab_slug'    => self::CONTENT_TAB,
				'toggle_slug' => 'filter',
			)
		);
		$this->add_control(
			'exclude_categories',
			array(
				'label'       => esc_html__( 'Exclude Categories', 'masteriyo' ),
				'type'        => 'multiple_checkboxes',
				'options'     => $categories_options,
				'tab_slug'    => self::CONTENT_TAB,
				'toggle_slug' => 'filter',
			)
		);
		$this->add_control(
			'exclude_instructors',
			array(
				'label'       => esc_html__( 'Exclude Instructors', 'masteriyo' ),
				'type'        => 'multiple_checkboxes',
				'options'     => $instructors_options,
				'tab_slug'    => self::CONTENT_TAB,
				'toggle_slug' => 'filter',
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
					'date'   => esc_html__( 'Date', 'masteriyo' ),
					'title'  => esc_html__( 'Title', 'masteriyo' ),
					'price'  => esc_html__( 'Price', 'masteriyo' ),
					'rating' => esc_html__( 'Rating', 'masteriyo' ),
				),
				'default'     => 'date',
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
				'default'     => 'DESC',
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
				'.masteriyo-col'             => 'padding-left: calc( {{columns_gap}} / 2 ) !important; padding-right: calc( {{columns_gap}} / 2 ) !important;',
				'.masteriyo-courses-wrapper' => 'margin-left: calc( -{{columns_gap}} / 2 ) !important; margin-right: calc( -{{columns_gap}} / 2 ) !important;',
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
				'.masteriyo-col'             => 'padding-top: calc( {{rows_gap}} / 2 ) !important; padding-bottom: calc( {{rows_gap}} / 2 ) !important;',
				'.masteriyo-courses-wrapper' => 'margin-top: calc( -{{rows_gap}} / 2 ) !important; margin-bottom: calc( -{{rows_gap}} / 2 ) !important;',
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
			'.masteriyo-course--card',
			array(
				'disable_fonts_control' => true,
			)
		);
	}

	/**
	 * Initialize difficulty badge style controls.
	 *
	 * @since 1.6.13
	 */
	protected function init_difficulty_badge_style_controls() {
		$this->add_range_control(
			'difficulty_badge_vertical_position',
			self::DESIGN_TAB,
			'difficulty_badge',
			array(
				'.difficulty-badge' => 'top: {{difficulty_badge_vertical_position}} !important;',
			),
			array(
				'label' => esc_html__( 'Vertical Position', 'masteriyo' ),
				'min'   => 0,
				'max'   => 300,
				'step'  => 1,
			)
		);

		$this->add_range_control(
			'difficulty_badge_horizontal_position',
			self::DESIGN_TAB,
			'difficulty_badge',
			array(
				'.difficulty-badge' => 'left: {{difficulty_badge_horizontal_position}} !important;',
			),
			array(
				'label' => esc_html__( 'Horizontal Position', 'masteriyo' ),
				'min'   => 0,
				'max'   => 300,
				'step'  => 1,
			)
		);

		$this->add_text_region_style_controls(
			'difficulty_badge_',
			self::DESIGN_TAB,
			'difficulty_badge',
			'.difficulty-badge .masteriyo-badge'
		);

		foreach ( $this->get_all_difficulties() as $difficulty ) {
			$key      = 'difficulty_badge_' . $difficulty->get_slug() . '_';
			$selector = sprintf( '.difficulty-badge.%s .masteriyo-badge', $difficulty->get_slug() );

			$this->add_text_region_style_controls( $key . '_', self::DESIGN_TAB, $key, $selector );
		}
	}

	/**
	 * Initialize categories style controls.
	 *
	 * @since 1.6.13
	 */
	protected function init_categories_style_controls() {
		$this->add_range_control(
			'categories_spacing',
			self::DESIGN_TAB,
			'categories',
			array(
				'.masteriyo-course--content__category-items'             => 'margin: 0 !important;',
				'.masteriyo-course--content__category .masteriyo-course--content__category-items:not(:first-child)' => 'margin-left: {{categories_spacing}} !important;',
			),
			array(
				'label' => esc_html__( 'Spacing', 'masteriyo' ),
				'min'   => 0,
				'max'   => 50,
				'step'  => 1,
			)
		);

		$this->add_text_region_style_controls(
			'categories_',
			self::DESIGN_TAB,
			'categories',
			'.masteriyo-course--content__category',
			array(
				'disable_fonts_control' => true,
			)
		);

		$this->add_text_region_style_controls(
			'categories_item_',
			self::DESIGN_TAB,
			'categories_item',
			'.masteriyo-course--content__category .masteriyo-course--content__category-items',
			array()
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
			'.masteriyo-course--content__title',
			array(
				'custom_selectors' => array(
					'fonts_control' => '.masteriyo-course--content__title a',
				),
			)
		);
	}

	/**
	 * Initialize author style controls.
	 *
	 * @since 1.6.13
	 */
	protected function init_author_style_controls() {
		$this->add_text_region_style_controls(
			'author_',
			self::DESIGN_TAB,
			'author',
			'.masteriyo-course-author',
			array(
				'disable_fonts_control' => true,
			)
		);

		$this->add_range_control(
			'author_avatar_size',
			self::DESIGN_TAB,
			'author_avatar',
			array(
				'.masteriyo-course-author img' => 'width: {{author_avatar_size}} !important; height: {{author_avatar_size}} !important;',
			),
			array(
				'label' => esc_html__( 'Size', 'masteriyo' ),
				'min'   => 15,
				'max'   => 200,
				'step'  => 1,
			)
		);

		$this->add_text_region_style_controls(
			'author_avatar_',
			self::DESIGN_TAB,
			'author_avatar',
			'.masteriyo-course-author img',
			array()
		);

		$this->add_text_region_style_controls(
			'author_name_',
			self::DESIGN_TAB,
			'author_name',
			'.masteriyo-course-author--name',
			array()
		);
	}

	/**
	 * Initialize rating style controls.
	 *
	 * @since 1.6.13
	 */
	protected function init_rating_style_controls() {
		$this->add_range_control(
			'rating_icon_size',
			self::DESIGN_TAB,
			'rating',
			array(
				'.masteriyo-rating svg' => 'width: {{rating_icon_size}}; height: {{rating_icon_size}};',
			),
			array(
				'label' => esc_html__( 'Icon Size', 'masteriyo' ),
				'min'   => 0,
				'max'   => 100,
				'step'  => 1,
			)
		);

		$this->add_color_control(
			'rating_icon_color',
			self::DESIGN_TAB,
			'rating',
			'.masteriyo-rating svg',
			array(
				'label'     => __( 'Icon Color', 'masteriyo' ),
				'css'       => 'fill: {{rating_icon_color}};',
				'hover_css' => 'fill: {{rating_icon_color__hover}};',
			)
		);

		$this->add_range_control(
			'rating_icon_spacing',
			self::DESIGN_TAB,
			'rating',
			array(
				'.masteriyo-rating svg:not(:first-child)' => 'margin-left: {{rating_icon_spacing}} !important;',
			),
			array(
				'label' => esc_html__( 'Spacing', 'masteriyo' ),
				'min'   => 0,
				'max'   => 100,
				'step'  => 1,
			)
		);

		$this->add_text_region_style_controls(
			'rating_',
			self::DESIGN_TAB,
			'rating',
			'.masteriyo-course--content__rt .masteriyo-rating',
			array(
				'disable_fonts_control' => true,
			)
		);

		$this->add_text_region_style_controls(
			'rating_text_',
			self::DESIGN_TAB,
			'rating_text',
			'.masteriyo-rating',
			array()
		);
	}

	/**
	 * Initialize description style controls.
	 *
	 * @since 1.6.13
	 */
	protected function init_description_style_controls() {
		$this->add_text_region_style_controls(
			'description_',
			self::DESIGN_TAB,
			'description',
			'.masteriyo-course--content__description',
			array()
		);

		$this->add_range_control(
			'highlights_spacing',
			self::DESIGN_TAB,
			'description',
			array(
				'.masteriyo-course--content__description ul li:not(:last-child)' => 'margin-bottom: {{highlights_spacing}} !important;',
			),
			array(
				'label' => esc_html__( 'Highlights Spacing', 'masteriyo' ),
				'min'   => 0,
				'max'   => 100,
				'step'  => 1,
			)
		);
	}

	/**
	 * Initialize metadata style controls.
	 *
	 * @since 1.6.13
	 */
	protected function init_metadata_style_controls() {
		$this->add_range_control(
			'metadata_icon_size',
			self::DESIGN_TAB,
			'metadata',
			array(
				'.masteriyo-course--content__stats svg' => 'width: {{metadata_icon_size}}; height: {{metadata_icon_size}};',
			),
			array(
				'label' => esc_html__( 'Icon Size', 'masteriyo' ),
				'min'   => 0,
				'max'   => 200,
				'step'  => 1,
			)
		);

		$this->add_color_control(
			'metadata_icon_color',
			self::DESIGN_TAB,
			'metadata',
			'.masteriyo-course--content__stats svg',
			array(
				'label'     => __( 'Icon Color', 'masteriyo' ),
				'css'       => 'fill: {{metadata_icon_color}} !important;',
				'hover_css' => 'fill: {{metadata_icon_color__hover}} !important;',
			)
		);

		$this->add_text_region_style_controls(
			'metadata_',
			self::DESIGN_TAB,
			'metadata',
			'.masteriyo-course--content__stats',
			array(
				'custom_selectors' => array(
					'fonts_control' => '.masteriyo-course--content__stats span',
				),
			)
		);
	}

	/**
	 * Initialize footer style controls.
	 *
	 * @since 1.6.13
	 */
	protected function init_footer_style_controls() {
		$this->add_text_region_style_controls(
			'footer_',
			self::DESIGN_TAB,
			'footer',
			'.masteriyo-course-card-footer',
			array(
				'disable_fonts_control' => true,
			)
		);
	}

	/**
	 * Initialize price style controls.
	 *
	 * @since 1.6.13
	 */
	protected function init_price_style_controls() {
		$this->add_text_region_style_controls(
			'price_',
			self::DESIGN_TAB,
			'price',
			'.masteriyo-course-price .current-amount',
			array()
		);
	}

	/**
	 * Initialize enroll button style controls.
	 *
	 * @since 1.6.13
	 */
	protected function init_enroll_button_style_controls() {
		$this->add_text_region_style_controls(
			'enroll_button_',
			self::DESIGN_TAB,
			'enroll_button',
			'.masteriyo-enroll-btn',
			array()
		);
	}

	/**
	 * Get rendered course list.
	 *
	 * @since 1.6.13
	 *
	 * @return string
	 */
	public static function get_rendered_course_list( $props = array() ) {
		$args = array(
			'post_type'      => PostType::COURSE,
			'post_status'    => PostStatus::PUBLISH,
			'posts_per_page' => ! empty( $props['per_page'] ) ? absint( $props['per_page'] ) : masteriyo_get_setting( 'course_archive.display.per_page' ),
			'paged'          => 1,
			'order'          => empty( $props['order'] ) ? 'DESC' : strtoupper( $props['order'] ),
			'orderby'        => 'date',
			'tax_query'      => array(
				'relation' => 'AND',
			),
			'meta_query'     => array(
				'relation' => 'AND',
			),
		);

		if ( ! empty( $props['include_instructors'] ) ) {
			$args['author__in'] = $props['include_instructors'];
		}

		if ( ! empty( $props['exclude_instructors'] ) ) {
			$args['author__not_in'] = $props['exclude_instructors'];
		}

		if ( ! empty( $props['include_categories'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => Taxonomy::COURSE_CATEGORY,
				'terms'    => $props['include_categories'],
				'field'    => 'term_id',
				'operator' => 'IN',
			);
		}

		if ( ! empty( $props['exclude_categories'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => Taxonomy::COURSE_CATEGORY,
				'terms'    => $props['exclude_categories'],
				'field'    => 'term_id',
				'operator' => 'NOT IN',
			);
		}

		if ( ! empty( $props['orderby'] ) ) {
			$orderby = strtoupper( $props['orderby'] );
			$order   = empty( $props['order'] ) ? 'DESC' : strtoupper( $props['order'] );

			switch ( $orderby ) {
				case 'date':
					$args['orderby'] = 'date';
					$args['order']   = ( 'ASC' === $order ) ? 'ASC' : 'DESC';
					break;

				case 'price':
					$args['orderby']  = 'meta_value_num';
					$args['meta_key'] = '_price';
					$args['order']    = ( 'DESC' === $order ) ? 'DESC' : 'ASC';
					break;

				case 'title':
					$args['orderby'] = 'title';
					$args['order']   = ( 'DESC' === $order ) ? 'DESC' : 'ASC';
					break;

				case 'rating':
					$args['orderby']  = 'meta_value_num';
					$args['meta_key'] = '_average_rating';
					$args['order']    = ( 'ASC' === $order ) ? 'ASC' : 'DESC';
					break;
			}
		}

		/**
		 * Filters the prepared query args for the course list query in Course List Divi module.
		 *
		 * @since 1.6.13
		 *
		 * @param array $args
		 */
		$args    = apply_filters( 'masteriyo_course_list_module_prepare_query_args', $args );
		$query   = new \WP_Query( $args );
		$courses = array_filter( array_map( 'masteriyo_get_course', $query->posts ) );
		$columns = empty( $props['columns'] ) ? masteriyo_get_setting( 'course_archive.display.per_row' ) : absint( $props['columns'] );

		masteriyo_set_loop_prop( 'columns', $columns );

		\ob_start();

		if ( count( $courses ) > 0 ) {
			$original_course = isset( $GLOBALS['course'] ) ? $GLOBALS['course'] : null;

			/**
			 * Fires before course loop in Course List Divi module.
			 *
			 * @since 1.6.13
			 *
			 * @param \Masteriyo\Models\Course[] $courses The courses objects.
			 */
			do_action( 'masteriyo_before_courses_loop', $courses );

			masteriyo_course_loop_start();

			foreach ( $courses as $course ) {
				$GLOBALS['course'] = $course;
				\masteriyo_get_template_part( 'content', 'course' );
			}

			$GLOBALS['course'] = $original_course;

			masteriyo_course_loop_end();

			/**
			 * Fires after course loop in Course List Divi module.
			 *
			 * @since 1.6.13
			 *
			 * @param \Masteriyo\Models\Course[] $courses The courses objects.
			 */
			do_action( 'masteriyo_after_courses_loop', $courses );

			masteriyo_reset_loop();
		} else {
			/**
			 * Fires when there is no course to display.
			 *
			 * @since 1.6.13
			 */
			do_action( 'masteriyo_no_courses_found' );
		}

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

		return $this->_render_module_wrapper( static::get_rendered_course_list( $this->props ), $render_slug );
	}
}
