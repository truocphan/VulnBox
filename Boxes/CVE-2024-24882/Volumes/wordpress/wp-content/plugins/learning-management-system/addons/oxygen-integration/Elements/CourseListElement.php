<?php
/**
 * Masteriyo course list Oxygen element class.
 *
 * @since 1.6.16
 */

namespace Masteriyo\Addons\OxygenIntegration\Elements;

use Masteriyo\Addons\OxygenIntegration\OxygenElement;
use Masteriyo\Enums\PostStatus;
use Masteriyo\PostType\PostType;
use Masteriyo\Taxonomy\Taxonomy;

/**
 * Masteriyo course list Oxygen element class.
 *
 * @since 1.6.16
 */
class CourseListElement extends OxygenElement {

	/**
	 * Returns element name.
	 *
	 * @since 1.6.16
	 *
	 * @return string
	 */
	public function name() {
		return 'Course List';
	}

	/**
	 * Returns element slug.
	 *
	 * @since 1.6.16
	 *
	 * @return string
	 */
	public function slug() {
		return 'masteriyo-course-list';
	}

	/**
	 * Returns icon URL.
	 *
	 * @since 1.6.16
	 *
	 * @return string
	 */
	public function icon() {
		return plugin_dir_url( MASTERIYO_PLUGIN_FILE ) . 'addons/oxygen-integration/svg/course-list-element-icon.svg';
	}

	/**
	 * Add customization controls for the element.
	 *
	 * @since 1.6.16
	 */
	public function controls() {
		$this->add_general_controls_section();
		$this->add_filter_controls_section();
		$this->add_sorting_controls_section();
		$this->add_layout_style_controls();
		$this->add_card_style_controls();
		$this->add_difficulty_badge_style_controls();
		$this->add_categories_section_style_controls();
		$this->add_category_item_style_controls();
		$this->add_title_style_controls();
		$this->add_author_section_style_controls();
		$this->add_author_avatar_style_controls();
		$this->add_author_name_style_controls();
		$this->add_rating_style_controls();
		$this->add_description_style_controls();
		$this->add_metadata_style_controls();
		$this->add_footer_section_style_controls();
		$this->add_price_style_controls();
		$this->add_enroll_button_style_controls();
		$this->add_enroll_button_hover_style_controls();
	}

	/**
	 * Add general customization controls.
	 *
	 * @since 1.6.16
	 */
	protected function add_general_controls_section() {
		$general_section = $this->addControlSection(
			'general_section',
			__( 'General', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$option = $general_section->addOptionControl(
			array(
				'type'    => 'textfield',
				'name'    => __( 'Per Page', 'masteriyo' ),
				'slug'    => 'per_page',
				'default' => 12,
			)
		);
		$option->rebuildElementOnChange();

		$option = $general_section->addOptionControl(
			array(
				'type'    => 'textfield',
				'name'    => __( 'Columns', 'masteriyo' ),
				'slug'    => 'columns',
				'default' => 3,
			)
		);
		$option->rebuildElementOnChange();

		$this->add_separator_in_section( $general_section );

		$this->add_toggle_control(
			$general_section,
			'show_thumbnail',
			__( 'Show Thumbnail', 'masteriyo' ),
			array(
				'selector' => '.masteriyo-course--img-wrap',
			)
		);
		$this->add_toggle_control(
			$general_section,
			'show_difficulty_badge',
			__( 'Show Difficulty Badge', 'masteriyo' ),
			array(
				'condition' => 'show_thumbnail=yes',
				'selector'  => '.difficulty-badge',
			)
		);
		$this->add_toggle_control(
			$general_section,
			'show_categories',
			__( 'Show Categories', 'masteriyo' ),
			array(
				'selector' => '.masteriyo-course--content__category',
			)
		);

		$this->add_toggle_control(
			$general_section,
			'show_title',
			__( 'Show Title', 'masteriyo' ),
			array(
				'selector' => '.masteriyo-course--content__title a',
			)
		);
		$this->add_toggle_control(
			$general_section,
			'show_author',
			__( 'Show Author', 'masteriyo' ),
			array(
				'selector' => '.masteriyo-course-author',
			)
		);
		$this->add_toggle_control(
			$general_section,
			'show_author_avatar',
			__( 'Show Avatar of Author', 'masteriyo' ),
			array(
				'condition' => 'show_author=yes',
				'selector'  => '.masteriyo-course-author img',
			)
		);
		$this->add_toggle_control(
			$general_section,
			'show_author_name',
			__( 'Show Name of Author', 'masteriyo' ),
			array(
				'condition' => 'show_author=yes',
				'selector'  => '.masteriyo-course-author .masteriyo-course-author--name',
			)
		);
		$this->add_toggle_control(
			$general_section,
			'show_rating',
			__( 'Show Rating', 'masteriyo' ),
			array(
				'selector' => '.masteriyo-rating',
			)
		);
		$this->add_toggle_control(
			$general_section,
			'show_course_description',
			__( 'Highlights / Description', 'masteriyo' ),
			array(
				'selector' => '.masteriyo-course--content__description',
			)
		);
		$this->add_toggle_control(
			$general_section,
			'show_metadata',
			__( 'Meta Data', 'masteriyo' ),
			array(
				'selector' => '.masteriyo-course--content__stats',
			)
		);
		$this->add_toggle_control(
			$general_section,
			'show_course_duration',
			__( 'Course Duration', 'masteriyo' ),
			array(
				'condition' => 'show_metadata=yes',
				'selector'  => '.masteriyo-course-stats-duration',
			)
		);
		$this->add_toggle_control(
			$general_section,
			'show_students_count',
			__( 'Students Count', 'masteriyo' ),
			array(
				'condition' => 'show_metadata=yes',
				'selector'  => '.masteriyo-course-stats-students',
			)
		);
		$this->add_toggle_control(
			$general_section,
			'show_lessons_count',
			__( 'Lessons Count', 'masteriyo' ),
			array(
				'condition' => 'show_metadata=yes',
				'selector'  => '.masteriyo-course-stats-curriculum',
			)
		);
		$this->add_toggle_control(
			$general_section,
			'show_card_footer',
			__( 'Footer', 'masteriyo' ),
			array(
				'selector' => '.masteriyo-course-card-footer',
			)
		);
		$this->add_toggle_control(
			$general_section,
			'show_price',
			__( 'Price', 'masteriyo' ),
			array(
				'condition' => 'show_card_footer=yes',
				'selector'  => '.masteriyo-course-price',
			)
		);
		$this->add_toggle_control(
			$general_section,
			'show_enroll_button',
			__( 'Enroll Button', 'masteriyo' ),
			array(
				'condition' => 'show_card_footer=yes',
				'selector'  => '.masteriyo-enroll-btn',
			)
		);
	}

	/**
	 * Add filter controls.
	 *
	 * @since 1.6.16
	 */
	protected function add_filter_controls_section() {
		$filter_section = $this->addControlSection(
			'filter_section',
			__( 'Filter', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$filter_section->addOptionControl(
			array(
				'type' => 'textfield',
				'name' => __( 'Include Categories (IDs separated by comma)', 'masteriyo' ),
				'slug' => 'include_category_ids',
			)
		)
		->rebuildElementOnChange();

		$filter_section->addOptionControl(
			array(
				'type' => 'textfield',
				'name' => __( 'Exclude Categories (IDs separated by comma)', 'masteriyo' ),
				'slug' => 'exclude_category_ids',
			)
		)
		->rebuildElementOnChange();

		$filter_section->addOptionControl(
			array(
				'type' => 'textfield',
				'name' => __( 'Include Instructors (IDs separated by comma)', 'masteriyo' ),
				'slug' => 'include_instructor_ids',
			)
		)
		->rebuildElementOnChange();

		$filter_section->addOptionControl(
			array(
				'type' => 'textfield',
				'name' => __( 'Exclude Instructors (IDs separated by comma)', 'masteriyo' ),
				'slug' => 'exclude_instructor_ids',
			)
		)
		->rebuildElementOnChange();
	}

	/**
	 * Add sorting controls.
	 *
	 * @since 1.6.16
	 */
	protected function add_sorting_controls_section() {
		$sorting_section = $this->addControlSection(
			'sorting_section',
			__( 'Sorting', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$option = $sorting_section->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Order By', 'masteriyo' ),
				'slug'    => 'order_by',
				'default' => 'date',
			)
		);
		$option->setValue( array( 'date', 'title', 'price', 'rating' ) );
		$option->rebuildElementOnChange();

		$option = $sorting_section->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Order', 'masteriyo' ),
				'slug'    => 'order',
				'default' => 'DESC',
			)
		);
		$option->setValue( array( 'DESC', 'ASC' ) );
		$option->rebuildElementOnChange();
	}

	/**
	 * Add style controls for layout.
	 *
	 * @since 1.6.16
	 */
	protected function add_layout_style_controls() {
		$layout_styles_section = $this->addControlSection(
			'layout_styles_section',
			__( 'Layout Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$layout_styles_section->addStyleControl(
			array(
				'name'         => __( 'Columns Gap', 'masteriyo' ),
				'slug'         => 'columns_gap',
				'control_type' => 'measurebox',
				'property'     => '',
			)
		)
		->setUnits( 'px', 'px' )
		->rebuildElementOnChange();

		$layout_styles_section->addStyleControl(
			array(
				'name'         => __( 'Rows Gap', 'masteriyo' ),
				'slug'         => 'rows_gap',
				'control_type' => 'measurebox',
				'property'     => '',
			)
		)
		->setUnits( 'px', 'px' )
		->rebuildElementOnChange();
	}

	/**
	 * Add style controls for card.
	 *
	 * @since 1.6.16
	 */
	protected function add_card_style_controls() {
		$card_styles_section = $this->addControlSection(
			'card_styles_section',
			__( 'Card Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$card_styles_section->borderSection(
			__( 'Border', 'masteriyo' ),
			'.masteriyo-course--card',
			$this
		);

		$card_styles_section->addStyleControl(
			array(
				'name'         => __( 'Background Color', 'masteriyo' ),
				'selector'     => '.masteriyo-course--card',
				'property'     => 'background-color',
				'control_type' => 'colorpicker',
			)
		);

		$card_styles_section->addPreset(
			'padding',
			'card_padding',
			__( 'Padding', 'masteriyo' ),
			'.masteriyo-course--card'
		);

		$card_styles_section->boxShadowSection(
			__( 'Box Shadow', 'masteriyo' ),
			'.masteriyo-course--card',
			$this
		);
	}

	/**
	 * Add style controls for difficulty badge.
	 *
	 * @since 1.6.16
	 */
	protected function add_difficulty_badge_style_controls() {
		$difficulty_badge_styles_section = $this->addControlSection(
			'difficulty_badge_styles_section',
			__( 'Difficulty Badge Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$difficulty_badge_styles_section->typographySection(
			__( 'Typography', 'masteriyo' ),
			'.difficulty-badge .masteriyo-badge',
			$this
		);

		$difficulty_badge_styles_section->borderSection(
			__( 'Border', 'masteriyo' ),
			'.difficulty-badge .masteriyo-badge',
			$this
		);

		$difficulty_badge_styles_section->addPreset(
			'padding',
			'difficulty_badge_padding',
			__( 'Padding', 'masteriyo' ),
			'.difficulty-badge .masteriyo-badge'
		);

		$difficulty_badge_styles_section->addStyleControl(
			array(
				'name'         => __( 'Top', 'masteriyo' ),
				'selector'     => '.difficulty-badge',
				'property'     => 'top',
				'control_type' => 'slider-measurebox',
			)
		);

		$difficulty_badge_styles_section->addStyleControl(
			array(
				'name'         => __( 'Left', 'masteriyo' ),
				'selector'     => '.difficulty-badge',
				'property'     => 'left',
				'control_type' => 'slider-measurebox',
			)
		);

		$this->add_separator_in_section( $difficulty_badge_styles_section );

		$difficulties = $this->get_all_difficulties();

		foreach ( $difficulties as $difficulty ) {
			$difficulty_badge_styles_section->addStyleControl(
				array(
					'name'         => __( 'Color', 'masteriyo' ) . ' (' . $difficulty->get_name() . ')',
					'selector'     => '.difficulty-badge[data-id="' . $difficulty->get_id() . '"] .masteriyo-badge',
					'property'     => 'color',
					'control_type' => 'colorpicker',
					'slug'         => 'difficulty_' . $difficulty->get_slug() . '_level_badge_text_color',
				)
			);
		}

		$this->add_separator_in_section( $difficulty_badge_styles_section );

		foreach ( $difficulties as $difficulty ) {
			$difficulty_badge_styles_section->addStyleControl(
				array(
					'name'         => __( 'Background Color', 'masteriyo' ) . ' (' . $difficulty->get_name() . ')',
					'selector'     => '.difficulty-badge[data-id="' . $difficulty->get_id() . '"] .masteriyo-badge',
					'property'     => 'background-color',
					'control_type' => 'colorpicker',
					'slug'         => 'difficulty_' . $difficulty->get_slug() . '_level_badge_background_color',
				)
			);
		}

		$this->add_separator_in_section( $difficulty_badge_styles_section );

		foreach ( $difficulties as $difficulty ) {
			$difficulty_badge_styles_section->boxShadowSection(
				__( 'Box Shadow', 'masteriyo' ) . ' (' . $difficulty->get_name() . ')',
				'.difficulty-badge[data-id="' . $difficulty->get_id() . '"]',
				$this
			);
		}
	}

	/**
	 * Add style controls for categories section.
	 *
	 * @since 1.6.16
	 */
	protected function add_categories_section_style_controls() {
		$categories_section_styles_section = $this->addControlSection(
			'categories_section_styles_section',
			__( 'Categories Section Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$categories_section_styles_section->addStyleControl(
			array(
				'name'         => __( 'Gap', 'masteriyo' ),
				'selector'     => '.masteriyo-course--content__category .masteriyo-course--content__category-items:not(:first-child)',
				'property'     => 'margin-left',
				'control_type' => 'slider-measurebox',
			)
		)
		->setUnits( 'px', 'px' );

		$this->add_container_style_controls_in_section(
			$categories_section_styles_section,
			'categories_section',
			'.masteriyo-course--content__category'
		);
	}

	/**
	 * Add style controls for category item.
	 *
	 * @since 1.6.16
	 */
	protected function add_category_item_style_controls() {
		$category_item_styles_section = $this->addControlSection(
			'category_item_styles_section',
			__( 'Category Item Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$this->add_text_region_style_controls_in_section(
			$category_item_styles_section,
			'categories_item',
			'.masteriyo-course--content__category .masteriyo-course--content__category-items'
		);
	}

	/**
	 * Add style controls for title.
	 *
	 * @since 1.6.16
	 */
	protected function add_title_style_controls() {
		$title_styles_section = $this->addControlSection(
			'title_styles_section',
			__( 'Title Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$this->add_text_region_style_controls_in_section(
			$title_styles_section,
			'title',
			'.masteriyo-course--content__title',
			array(
				'selectors' => array(
					'text_color' => '.masteriyo-course--content__title a',
				),
			)
		);
	}

	/**
	 * Add style controls for author section.
	 *
	 * @since 1.6.16
	 */
	protected function add_author_section_style_controls() {
		$author_section_styles_section = $this->addControlSection(
			'author_section_styles_section',
			__( 'Author Section Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$this->add_container_style_controls_in_section(
			$author_section_styles_section,
			'author_section',
			'.masteriyo-course-author a'
		);
	}

	/**
	 * Add style controls for author avatar.
	 *
	 * @since 1.6.16
	 */
	protected function add_author_avatar_style_controls() {
		$author_avatar_styles_section = $this->addControlSection(
			'author_avatar_styles_section',
			__( 'Author Avatar Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$author_avatar_styles_section->addStyleControl(
			array(
				'name'         => __( 'Size', 'masteriyo' ),
				'selector'     => '.masteriyo-course-author img',
				'property'     => 'width|height',
				'control_type' => 'slider-measurebox',
			)
		)
		->setUnits( 'px', 'px' );

		$this->add_container_style_controls_in_section(
			$author_avatar_styles_section,
			'author_avatar',
			'.masteriyo-course-author img'
		);
	}

	/**
	 * Add style controls for author name.
	 *
	 * @since 1.6.16
	 */
	protected function add_author_name_style_controls() {
		$author_name_styles_section = $this->addControlSection(
			'author_name_styles_section',
			__( 'Author Name Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$this->add_text_region_style_controls_in_section(
			$author_name_styles_section,
			'author_name',
			'.masteriyo-course-author .masteriyo-course-author--name'
		);
	}

	/**
	 * Add style controls for course rating.
	 *
	 * @since 1.6.16
	 */
	protected function add_rating_style_controls() {
		$rating_styles_section = $this->addControlSection(
			'rating_styles_section',
			__( 'Rating Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$this->add_text_region_style_controls_in_section(
			$rating_styles_section,
			'rating',
			'.masteriyo-course--content__rt .masteriyo-rating'
		);

		$rating_styles_section->addStyleControl(
			array(
				'name'         => __( 'Icon Color', 'masteriyo' ),
				'selector'     => '.masteriyo-rating svg',
				'property'     => 'fill',
				'control_type' => 'colorpicker',
				'slug'         => 'rating_icon_color',
			)
		);

		$rating_styles_section->addStyleControl(
			array(
				'name'         => __( 'Icon Size', 'masteriyo' ),
				'selector'     => '.masteriyo-rating svg',
				'property'     => 'width|height',
				'control_type' => 'slider-measurebox',
			)
		)
		->setUnits( 'px', 'px' );

		$rating_styles_section->addStyleControl(
			array(
				'name'         => __( 'Icons Gap', 'masteriyo' ),
				'selector'     => '.masteriyo-rating svg:not(:first-child)',
				'property'     => 'margin-left',
				'control_type' => 'slider-measurebox',
			)
		)
		->setUnits( 'px', 'px' );
	}

	/**
	 * Add style controls for description or highlights.
	 *
	 * @since 1.6.16
	 */
	protected function add_description_style_controls() {
		$description_styles_section = $this->addControlSection(
			'description_styles_section',
			__( 'Description / Highlights Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$this->add_text_region_style_controls_in_section(
			$description_styles_section,
			'description',
			'.masteriyo-course--content__description'
		);

		$description_styles_section->addStyleControl(
			array(
				'name'         => __( 'Highlights Gap', 'masteriyo' ),
				'selector'     => '.masteriyo-course--content__description ul li:not(:last-child)',
				'property'     => 'margin-bottom',
				'control_type' => 'slider-measurebox',
			)
		)
		->setUnits( 'px', 'px' );
	}

	/**
	 * Add style controls for course metadata.
	 *
	 * @since 1.6.16
	 */
	protected function add_metadata_style_controls() {
		$metadata_styles_section = $this->addControlSection(
			'metadata_styles_section',
			__( 'Metadata Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$this->add_text_region_style_controls_in_section(
			$metadata_styles_section,
			'metadata',
			'.masteriyo-course--content__stats',
			array(
				'selectors' => array(
					'text_color' => '.masteriyo-course--content__stats span',
					'typography' => '.masteriyo-course--content__stats span',
				),
			)
		);

		$metadata_styles_section->addStyleControl(
			array(
				'name'         => __( 'Icons Size', 'masteriyo' ),
				'selector'     => '.masteriyo-course--content__stats svg',
				'property'     => 'width|height',
				'control_type' => 'slider-measurebox',
			)
		)
		->setUnits( 'px', 'px' );

		$metadata_styles_section->addStyleControl(
			array(
				'name'         => __( 'Icon Color', 'masteriyo' ),
				'selector'     => '.masteriyo-course--content__stats svg',
				'property'     => 'fill',
				'control_type' => 'colorpicker',
				'slug'         => 'metadata_icon_color',
			)
		);
	}

	/**
	 * Add style controls for footer section.
	 *
	 * @since 1.6.16
	 */
	protected function add_footer_section_style_controls() {
		$footer_section_styles_section = $this->addControlSection(
			'footer_section_styles_section',
			__( 'Footer Section Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$this->add_container_style_controls_in_section(
			$footer_section_styles_section,
			'footer_section',
			'.masteriyo-course-card-footer'
		);
	}

	/**
	 * Add style controls for price text.
	 *
	 * @since 1.6.16
	 */
	protected function add_price_style_controls() {
		$price_styles_section = $this->addControlSection(
			'price_styles_section',
			__( 'Price Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$this->add_text_region_style_controls_in_section(
			$price_styles_section,
			'price',
			'.masteriyo-course-price',
			array(
				'selectors' => array(
					'text_color' => '.masteriyo-course-price .current-amount',
					'typography' => '.masteriyo-course-price .current-amount',
				),
			)
		);
	}

	/**
	 * Add style controls for enroll button.
	 *
	 * @since 1.6.16
	 */
	protected function add_enroll_button_style_controls() {
		$enroll_button_styles_section = $this->addControlSection(
			'enroll_button_styles_section',
			__( 'Enroll Button Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$this->add_text_region_style_controls_in_section(
			$enroll_button_styles_section,
			'enroll_button',
			'.masteriyo-enroll-btn'
		);
	}

	/**
	 * Add style controls for enroll button in hover state.
	 *
	 * @since 1.6.16
	 */
	protected function add_enroll_button_hover_style_controls() {
		$enroll_button_hover_styles_section = $this->addControlSection(
			'enroll_button_hover_styles_section',
			__( 'Enroll Button Hover Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$this->add_text_region_style_controls_in_section(
			$enroll_button_hover_styles_section,
			'enroll_button_hover',
			'.masteriyo-enroll-btn:hover'
		);
	}

	/**
	 * Returns custom CSS for the element.
	 *
	 * @since 1.6.16
	 *
	 * @param array $options
	 * @param string $wrapper_selector
	 *
	 * @return string
	 */
	public function customCSS( $options, $wrapper_selector ) {
		$options = $this->clean_up_option_names( $options );
		$css     = '';

		if ( isset( $options['columns_gap'] ) && is_numeric( $options['columns_gap'] ) ) {
			$css .= $wrapper_selector . ' .masteriyo-col { padding-left: calc( ' . $options['columns_gap'] . 'px / 2 ) !important; padding-right: calc( ' . $options['columns_gap'] . 'px / 2 ) !important; }';
			$css .= $wrapper_selector . ' .masteriyo-courses-wrapper { margin-left: calc( -' . $options['columns_gap'] . 'px / 2 ) !important; margin-right: calc( -' . $options['columns_gap'] . 'px / 2 ) !important; }';
		}

		if ( isset( $options['rows_gap'] ) && is_numeric( $options['rows_gap'] ) ) {
			$css .= $wrapper_selector . ' .masteriyo-col { padding-top: calc( ' . $options['rows_gap'] . 'px / 2 ) !important; padding-bottom: calc( ' . $options['rows_gap'] . 'px / 2 ) !important; }';
			$css .= $wrapper_selector . ' .masteriyo-courses-wrapper { margin-top: calc( -' . $options['rows_gap'] . 'px / 2 ) !important; margin-bottom: calc( -' . $options['rows_gap'] . 'px / 2 ) !important; }';
		}

		return $css;
	}

	/**
	 * Render the element's UI by outputting HTML.
	 *
	 * @since 1.6.16
	 *
	 * @param array $options
	 * @param array $defaults
	 * @param mixed $content
	 */
	public function render( $options, $defaults, $content ) {
		$settings  = wp_parse_args(
			$options,
			array(
				'per_page'               => 6,
				'columns'                => 3,
				'order'                  => 'DESC',
				'order_by'               => 'date',
				'include_category_ids'   => '',
				'exclude_category_ids'   => '',
				'include_instructor_ids' => '',
				'exclude_instructor_ids' => '',
			)
		);
		$course    = isset( $GLOBALS['course'] ) ? $GLOBALS['course'] : null;
		$limit     = max( absint( $settings['per_page'] ), 1 );
		$columns   = max( absint( $settings['columns'] ), 1 );
		$tax_query = array(
			'relation' => 'AND',
		);

		if ( ! empty( $settings['include_category_ids'] ) ) {
			$ids         = array_map( 'absint', array_filter( explode( ',', $settings['include_category_ids'] ) ) );
			$tax_query[] = array(
				'taxonomy' => Taxonomy::COURSE_CATEGORY,
				'terms'    => $ids,
				'field'    => 'term_id',
				'operator' => 'IN',
			);
		}

		if ( ! empty( $settings['exclude_category_ids'] ) ) {
			$ids         = array_map( 'absint', array_filter( explode( ',', $settings['exclude_category_ids'] ) ) );
			$tax_query[] = array(
				'taxonomy' => Taxonomy::COURSE_CATEGORY,
				'terms'    => $ids,
				'field'    => 'term_id',
				'operator' => 'NOT IN',
			);
		}

		$args = array(
			'post_type'      => PostType::COURSE,
			'status'         => array( PostStatus::PUBLISH ),
			'posts_per_page' => $limit,
			'order'          => 'DESC',
			'orderby'        => 'date',
			'tax_query'      => $tax_query,
		);

		if ( ! empty( $settings['include_instructor_ids'] ) ) {
			$ids                = array_map( 'absint', array_filter( explode( ',', $settings['include_instructor_ids'] ) ) );
			$args['author__in'] = $ids;
		}

		if ( ! empty( $settings['exclude_instructor_ids'] ) ) {
			$ids                    = array_map( 'absint', array_filter( explode( ',', $settings['exclude_instructor_ids'] ) ) );
			$args['author__not_in'] = $ids;
		}

		$order = strtoupper( $settings['order'] );

		switch ( $settings['order_by'] ) {
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

			default:
				$args['orderby'] = 'date';
				$args['order']   = ( 'ASC' === $order ) ? 'ASC' : 'DESC';
				break;
		}

		$courses_query = new \WP_Query( $args );
		$courses       = array_filter( array_map( 'masteriyo_get_course', $courses_query->posts ) );

		printf( '<div class="masteriyo">' );
		masteriyo_set_loop_prop( 'columns', $columns );

		if ( count( $courses ) > 0 ) {
			$original_course = isset( $GLOBALS['course'] ) ? $GLOBALS['course'] : null;

			masteriyo_course_loop_start();

			foreach ( $courses as $course ) {
				$GLOBALS['course'] = $course;
				$card_class        = empty( $settings['card_hover_animation'] ) ? '' : sprintf( 'elementor-animation-%s', $settings['card_hover_animation'] );

				masteriyo_get_template(
					'content-course.php',
					array(
						'card_class' => $card_class,
					)
				);
			}

			$GLOBALS['course'] = $original_course;

			masteriyo_course_loop_end();
			masteriyo_reset_loop();
		}

		echo '</div>';
	}
}
