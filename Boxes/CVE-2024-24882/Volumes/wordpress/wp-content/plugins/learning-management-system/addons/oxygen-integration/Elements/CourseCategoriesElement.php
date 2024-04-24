<?php
/**
 * Masteriyo course categories Oxygen element class.
 *
 * @since 1.6.16
 */

namespace Masteriyo\Addons\OxygenIntegration\Elements;

use Masteriyo\Addons\OxygenIntegration\OxygenElement;
use Masteriyo\Taxonomy\Taxonomy;

/**
 * Masteriyo course categories Oxygen element class.
 *
 * @since 1.6.16
 */
class CourseCategoriesElement extends OxygenElement {

	/**
	 * Returns element name.
	 *
	 * @since 1.6.16
	 *
	 * @return string
	 */
	public function name() {
		return 'Course Categories';
	}

	/**
	 * Returns element slug.
	 *
	 * @since 1.6.16
	 *
	 * @return string
	 */
	public function slug() {
		return 'masteriyo-course-categories';
	}

	/**
	 * Returns icon URL.
	 *
	 * @since 1.6.16
	 *
	 * @return string
	 */
	public function icon() {
		return plugin_dir_url( MASTERIYO_PLUGIN_FILE ) . 'addons/oxygen-integration/svg/course-categories-element-icon.svg';
	}

	/**
	 * Add customization controls for the element.
	 *
	 * @since 1.6.16
	 */
	public function controls() {
		$this->add_general_controls_section();
		$this->add_sorting_controls_section();
		$this->add_layout_style_controls();
		$this->add_card_style_controls();
		$this->add_details_section_style_controls();
		$this->add_title_style_controls();
		$this->add_courses_count_style_controls();
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

		$general_section->addOptionControl(
			array(
				'type'    => 'dropdown',
				'slug'    => 'include_sub_categories',
				'name'    => __( 'Include Sub-Categories', 'masteriyo' ),
				'default' => 'yes',
			)
		)
		->setValue( array( 'yes', 'no' ) )
		->rebuildElementOnChange();

		$this->add_separator_in_section( $general_section );

		$this->add_toggle_control(
			$general_section,
			'show_thumbnail',
			__( 'Show Thumbnail', 'masteriyo' ),
			array(
				'selector' => '.masteriyo-category-card__image',
			)
		);
		$this->add_toggle_control(
			$general_section,
			'show_details',
			__( 'Show Details', 'masteriyo' ),
			array(
				'selector' => '.masteriyo-category-card__detail',
			)
		);
		$this->add_toggle_control(
			$general_section,
			'show_title',
			__( 'Show Title', 'masteriyo' ),
			array(
				'condition' => 'show_details=yes',
				'selector'  => '.masteriyo-category-card__title',
			)
		);
		$this->add_toggle_control(
			$general_section,
			'show_courses_count',
			__( 'Show Courses Count', 'masteriyo' ),
			array(
				'condition' => 'show_details=yes',
				'selector'  => '.masteriyo-category-card__courses',
			)
		);
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
				'default' => 'name',
			)
		);
		$option->setValue( array( 'name', 'count' ) );
		$option->rebuildElementOnChange();

		$option = $sorting_section->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Order', 'masteriyo' ),
				'slug'    => 'order',
				'default' => 'ASC',
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
			'.masteriyo-category-card',
			$this
		);

		$card_styles_section->addStyleControl(
			array(
				'name'         => __( 'Background Color', 'masteriyo' ),
				'selector'     => '.masteriyo-category-card',
				'property'     => 'background-color',
				'control_type' => 'colorpicker',
			)
		);

		$card_styles_section->addPreset(
			'padding',
			'card_padding',
			__( 'Padding', 'masteriyo' ),
			'.masteriyo-category-card'
		);

		$card_styles_section->boxShadowSection(
			__( 'Box Shadow', 'masteriyo' ),
			'.masteriyo-category-card',
			$this
		);
	}

	/**
	 * Add style controls for details section.
	 *
	 * @since 1.6.16
	 */
	protected function add_details_section_style_controls() {
		$details_section_styles_section = $this->addControlSection(
			'details_section_styles_section',
			__( 'Details Section Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$this->add_container_style_controls_in_section(
			$details_section_styles_section,
			'details_section',
			'.masteriyo-category-card__detail'
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
			'.masteriyo-category-card__title',
			array(
				'selectors' => array(
					'text_color' => '.masteriyo-category-card__title, .masteriyo-category-card__title a',
				),
			)
		);
	}

	/**
	 * Add style controls for courses count.
	 *
	 * @since 1.6.16
	 */
	protected function add_courses_count_style_controls() {
		$courses_count_styles_section = $this->addControlSection(
			'courses_count_styles_section',
			__( 'Courses Count Styles', 'masteriyo' ),
			'assets/icon.png',
			$this
		);

		$this->add_text_region_style_controls_in_section(
			$courses_count_styles_section,
			'courses_count',
			'.masteriyo-category-card__courses'
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
			$css .= $wrapper_selector . ' .masteriyo-course-categories { margin-left: calc( -' . $options['columns_gap'] . 'px / 2 ) !important; margin-right: calc( -' . $options['columns_gap'] . 'px / 2 ) !important; }';
		}

		if ( isset( $options['rows_gap'] ) && is_numeric( $options['rows_gap'] ) ) {
			$css .= $wrapper_selector . ' .masteriyo-col { padding-top: calc( ' . $options['rows_gap'] . 'px / 2 ) !important; padding-bottom: calc( ' . $options['rows_gap'] . 'px / 2 ) !important; }';
			$css .= $wrapper_selector . ' .masteriyo-course-categories { margin-top: calc( -' . $options['rows_gap'] . 'px / 2 ) !important; margin-bottom: calc( -' . $options['rows_gap'] . 'px / 2 ) !important; }';
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
		$settings               = wp_parse_args(
			$options,
			array(
				'per_page'               => 6,
				'columns'                => 3,
				'order'                  => 'ASC',
				'order_by'               => 'name',
				'include_sub_categories' => 'yes',
				'show_courses_count'     => 'yes',
			)
		);
		$limit                  = max( absint( $settings['per_page'] ), 1 );
		$columns                = max( absint( $settings['columns'] ), 1 );
		$attrs                  = array();
		$include_sub_categories = empty( $settings['include_sub_categories'] ) || 'yes' === $settings['include_sub_categories'];
		$hide_courses_count     = ! ( empty( $settings['show_courses_count'] ) || 'yes' === $settings['show_courses_count'] );
		$args                   = array(
			'taxonomy'   => Taxonomy::COURSE_CATEGORY,
			'order'      => masteriyo_array_get( $settings, 'order', 'ASC' ),
			'orderby'    => masteriyo_array_get( $settings, 'order_by', 'name' ),
			'number'     => $limit,
			'hide_empty' => false,
		);

		if ( ! masteriyo_string_to_bool( $include_sub_categories ) ) {
			$args['parent'] = 0;
		}

		$query      = new \WP_Term_Query();
		$result     = $query->query( $args );
		$categories = array_filter( array_map( 'masteriyo_get_course_cat', $result ) );

		$attrs['count']                  = $limit;
		$attrs['columns']                = $columns;
		$attrs['categories']             = $categories;
		$attrs['hide_courses_count']     = $hide_courses_count;
		$attrs['include_sub_categories'] = $include_sub_categories;

		if ( ! empty( $settings['card_hover_animation'] ) ) {
			$attrs['card_class'] = sprintf( 'elementor-animation-%s', $settings['card_hover_animation'] );
		}

		printf( '<div class="masteriyo">' );
		masteriyo_get_template( 'shortcodes/course-categories/list.php', $attrs );
		echo '</div>';
	}
}
