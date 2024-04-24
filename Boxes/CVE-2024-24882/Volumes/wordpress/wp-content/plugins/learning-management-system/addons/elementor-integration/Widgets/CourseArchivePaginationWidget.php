<?php
/**
 * Masteriyo course archive pagination elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */

namespace Masteriyo\Addons\ElementorIntegration\Widgets;

use Elementor\Controls_Manager;
use Masteriyo\Addons\ElementorIntegration\WidgetBase;
use Masteriyo\Enums\PostStatus;
use Masteriyo\PostType\PostType;

defined( 'ABSPATH' ) || exit;

/**
 * Masteriyo course archive pagination elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */
class CourseArchivePaginationWidget extends WidgetBase {

	/**
	 * Get widget name.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_name() {
		return 'masteriyo-course-archive-pagination';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Course Archive Pagination', 'masteriyo' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.6.12
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'masteriyo-course-pagination-widget-icon';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.6.12
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'pagination', 'page', 'numbers' );
	}

	/**
	 * Register controls configuring widget content.
	 *
	 * @since 1.6.12
	 */
	protected function register_content_controls() {}

	/**
	 * Register controls for customizing widget styles.
	 *
	 * @since 1.6.12
	 */
	protected function register_style_controls() {
		// Container styles.
		$this->start_controls_section(
			'container_styles_section',
			array(
				'label' => esc_html__( 'Container', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'container_',
			'.page-numbers',
			array(
				'disable_align'       => true,
				'disable_typography'  => true,
				'disable_text_color'  => true,
				'disable_text_shadow' => true,
			)
		);
		$this->end_controls_section();

		// Number box.
		$this->start_controls_section(
			'number_box_styles_section',
			array(
				'label' => esc_html__( 'Number Box', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'number_box_',
			'.page-numbers li',
			array(
				'disable_align'       => true,
				'disable_typography'  => true,
				'disable_text_color'  => true,
				'disable_text_shadow' => true,
			)
		);
		$this->end_controls_section();

		// Page number.
		$this->start_controls_section(
			'page_number_styles_section',
			array(
				'label' => esc_html__( 'Page Number', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'page_number_',
			'.page-numbers li a',
			array(
				'disable_align' => true,
			)
		);
		$this->end_controls_section();

		// Active Page number.
		$this->start_controls_section(
			'active_page_number_styles_section',
			array(
				'label' => esc_html__( 'Active Page Number', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'active_page_number_',
			'.page-numbers li .current',
			array(
				'disable_align' => true,
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Render heading widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.6.12
	 */
	protected function content_template() {
		$args  = array(
			'post_type'      => PostType::COURSE,
			'post_status'    => PostStatus::PUBLISH,
			'posts_per_page' => masteriyo_get_setting( 'course_archive.display.per_page' ),
			'paged'          => 1,
			'order'          => 'DESC',
			'orderby'        => 'date',
		);
		$query = new \WP_Query( $args );

		// Backup original query object.
		$old_query = $GLOBALS['wp_query'];

		// Switch to the given query object.
		$GLOBALS['wp_query'] = $query; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		// Generate pagination links with the new query object.
		masteriyo_archive_navigation();

		// Restore the origin query object.
		$GLOBALS['wp_query'] = $old_query; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * @since 1.6.12
	 */
	protected function render() {
		masteriyo_archive_navigation();
	}
}
