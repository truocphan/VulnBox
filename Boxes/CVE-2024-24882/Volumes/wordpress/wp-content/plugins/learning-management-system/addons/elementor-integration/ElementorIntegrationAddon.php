<?php
/**
 * Masteriyo elementor integration addon setup.
 *
 * @package Masteriyo\Addons\ElementorIntegration
 *
 * @since 1.6.12
 */
namespace Masteriyo\Addons\ElementorIntegration;

use Masteriyo\Addons\ElementorIntegration\DocumentTypes\CourseArchivePageDocumentType;
use Masteriyo\Addons\ElementorIntegration\DocumentTypes\SingleCoursePageDocumentType;
use Masteriyo\Addons\ElementorIntegration\Widgets\CategoriesOfCourseWidget;
use Masteriyo\Addons\ElementorIntegration\Widgets\CourseArchivePaginationWidget;
use Masteriyo\Addons\ElementorIntegration\Widgets\CourseArchiveViewModeWidget;
use Masteriyo\Addons\ElementorIntegration\Widgets\CourseAuthorWidget;
use Masteriyo\Addons\ElementorIntegration\Widgets\CourseCategoriesWidget;
use Masteriyo\Addons\ElementorIntegration\Widgets\CourseContentsWidget;
use Masteriyo\Addons\ElementorIntegration\Widgets\CourseCurriculumWidget;
use Masteriyo\Addons\ElementorIntegration\Widgets\CourseEnrollButtonWidget;
use Masteriyo\Addons\ElementorIntegration\Widgets\CourseFeaturedImageWidget;
use Masteriyo\Addons\ElementorIntegration\Widgets\CourseHighlightsWidget;
use Masteriyo\Addons\ElementorIntegration\Widgets\CourseListWidget;
use Masteriyo\Addons\ElementorIntegration\Widgets\CourseOverviewWidget;
use Masteriyo\Addons\ElementorIntegration\Widgets\CoursePriceWidget;
use Masteriyo\Addons\ElementorIntegration\Widgets\CourseRatingWidget;
use Masteriyo\Addons\ElementorIntegration\Widgets\CourseReviewsWidget;
use Masteriyo\Addons\ElementorIntegration\Widgets\CourseSearchFormWidget;
use Masteriyo\Addons\ElementorIntegration\Widgets\CourseStatsWidget;
use Masteriyo\Addons\ElementorIntegration\Widgets\CourseTitleWidget;
use Masteriyo\Enums\PostStatus;

defined( 'ABSPATH' ) || exit;

/**
 * Main Masteriyo elementor integration class.
 *
 * @class Masteriyo\Addons\ElementorIntegration\ElementorIntegrationAddon
 */
class ElementorIntegrationAddon {

	/**
	 * Initialize module.
	 *
	 * @since 1.6.12
	 */
	public function init() {
		$this->init_hooks();

		( new UseTemplateForMasteriyoAction() )->init();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.6.12
	 */
	public function init_hooks() {
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'add_additional_editor_css' ) );
		add_action( 'elementor/documents/register', array( $this, 'register_document_types' ) );
		add_action( 'manage_elementor_library_posts_custom_column', array( $this, 'render_document_type_column_info' ), 10, 2 );
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'enqueue_elementor_editor_scripts' ), 20 );
		add_action( 'elementor/editor/footer', array( $this, 'print_editor_views' ) );
		add_filter( 'masteriyo_localized_admin_scripts', array( $this, 'add_backend_script_data' ) );
		add_action( 'masteriyo_course_archive_page_custom_template_render', array( $this, 'render_course_archive_page_template' ), 10, 2 );
		add_action( 'masteriyo_single_course_page_custom_template_render', array( $this, 'render_single_course_page_template' ), 10, 2 );
		add_filter( 'post_row_actions', array( $this, 'add_use_template_for_masteriyo_action' ), 10, 2 );
		add_filter( 'display_post_states', array( $this, 'add_post_states' ), 10, 2 );
	}

	/**
	 * Register masteriyo category.
	 *
	 * @since 1.6.12
	 *
	 * @param \Elementor\Elements_Manager $elements_manager
	 */
	public function register_category( $elements_manager ) {
		$elements_manager->add_category(
			'masteriyo',
			array(
				'title' => __( 'Masteriyo LMS', 'masteriyo' ),
			)
		);
	}

	/**
	 * Register elementor widgets.
	 *
	 * @since 1.6.12
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager
	 */
	public function register_widgets( $widgets_manager ) {
		$widgets_manager->register( new CourseListWidget() );
		$widgets_manager->register( new CourseCategoriesWidget() );
		$widgets_manager->register( new CourseTitleWidget() );
		$widgets_manager->register( new CoursePriceWidget() );
		$widgets_manager->register( new CourseFeaturedImageWidget() );
		$widgets_manager->register( new CourseEnrollButtonWidget() );
		$widgets_manager->register( new CourseStatsWidget() );
		$widgets_manager->register( new CourseHighlightsWidget() );
		$widgets_manager->register( new CategoriesOfCourseWidget() );
		$widgets_manager->register( new CourseAuthorWidget() );
		$widgets_manager->register( new CourseRatingWidget() );
		$widgets_manager->register( new CourseContentsWidget() );
		$widgets_manager->register( new CourseOverviewWidget() );
		$widgets_manager->register( new CourseCurriculumWidget() );
		$widgets_manager->register( new CourseReviewsWidget() );
		$widgets_manager->register( new CourseArchivePaginationWidget() );
		$widgets_manager->register( new CourseSearchFormWidget() );
		$widgets_manager->register( new CourseArchiveViewModeWidget() );
	}

	/**
	 * Add additional CSS for the elementor editor.
	 *
	 * @since 1.6.12
	 */
	public function add_additional_editor_css() {
		$indent_css       = $this->generate_css_for_indent_control();
		$widget_icons_css = $this->generate_css_for_widget_icons();

		$css = "
			{$widget_icons_css}
			{$indent_css}
		";

		wp_add_inline_style( 'elementor-editor', $css );
	}

	/**
	 * Generate widget icons css.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	protected function generate_css_for_widget_icons() {
		$css   = '';
		$icons = array(
			array_merge(
				array(
					'class' => 'masteriyo-course-list-widget-icon',
				),
				Helper::get_widget_icon_urls( 'course-list-widget-icon' )
			),
			array_merge(
				array(
					'class' => 'masteriyo-course-categories-widget-icon',
				),
				Helper::get_widget_icon_urls( 'course-categories-widget-icon' )
			),
			array_merge(
				array(
					'class' => 'masteriyo-categories-of-course-widget-icon',
				),
				Helper::get_widget_icon_urls( 'categories-of-course-widget-icon' )
			),
			array_merge(
				array(
					'class' => 'masteriyo-course-archive-view-mode-widget-icon',
				),
				Helper::get_widget_icon_urls( 'course-archive-view-mode-widget-icon' )
			),
			array_merge(
				array(
					'class' => 'masteriyo-course-author-widget-icon',
				),
				Helper::get_widget_icon_urls( 'course-author-widget-icon' )
			),
			array_merge(
				array(
					'class' => 'masteriyo-course-contents-widget-icon',
				),
				Helper::get_widget_icon_urls( 'course-contents-widget-icon' )
			),
			array_merge(
				array(
					'class' => 'masteriyo-course-curriculum-widget-icon',
				),
				Helper::get_widget_icon_urls( 'course-curriculum-widget-icon' )
			),
			array_merge(
				array(
					'class' => 'masteriyo-course-enroll-button-widget-icon',
				),
				Helper::get_widget_icon_urls( 'course-enroll-button-widget-icon' )
			),
			array_merge(
				array(
					'class' => 'masteriyo-course-featured-image-widget-icon',
				),
				Helper::get_widget_icon_urls( 'course-featured-image-widget-icon' )
			),
			array_merge(
				array(
					'class' => 'masteriyo-course-highlights-widget-icon',
				),
				Helper::get_widget_icon_urls( 'course-highlights-widget-icon' )
			),
			array_merge(
				array(
					'class' => 'masteriyo-course-overview-widget-icon',
				),
				Helper::get_widget_icon_urls( 'course-overview-widget-icon' )
			),
			array_merge(
				array(
					'class' => 'masteriyo-course-pagination-widget-icon',
				),
				Helper::get_widget_icon_urls( 'course-pagination-widget-icon', 'stroke' )
			),
			array_merge(
				array(
					'class' => 'masteriyo-course-price-widget-icon',
				),
				Helper::get_widget_icon_urls( 'course-price-widget-icon' )
			),
			array_merge(
				array(
					'class' => 'masteriyo-course-rating-widget-icon',
				),
				Helper::get_widget_icon_urls( 'course-rating-widget-icon' )
			),
			array_merge(
				array(
					'class' => 'masteriyo-course-reviews-widget-icon',
				),
				Helper::get_widget_icon_urls( 'course-reviews-widget-icon' )
			),
			array_merge(
				array(
					'class' => 'masteriyo-course-search-form-widget-icon',
				),
				Helper::get_widget_icon_urls( 'course-search-form-widget-icon' )
			),
			array_merge(
				array(
					'class' => 'masteriyo-course-stats-widget-icon',
				),
				Helper::get_widget_icon_urls( 'course-stats-widget-icon' )
			),
			array_merge(
				array(
					'class' => 'masteriyo-course-title-widget-icon',
				),
				Helper::get_widget_icon_urls( 'course-title-widget-icon' )
			),
		);

		/**
		 * Filters Elementor widgets svg icons data. It will be used to generate CSS so that the icon class works on widgets.
		 *
		 * @since 1.6.12
		 *
		 * @param array $icons
		 */
		$icons = apply_filters( 'masteriyo_elementor_widgets_svg_icons_data', $icons );

		foreach ( $icons as $icon_data ) {
			$class                        = $icon_data['class'];
			$normal_state_icon            = $icon_data['normal_state_icon'];
			$hover_state_icon             = $icon_data['hover_state_icon'];
			$normal_state_dark_theme_icon = $icon_data['normal_state_dark_theme_icon'];
			$hover_state_dark_theme_icon  = $icon_data['hover_state_dark_theme_icon'];
			$css                         .= "
				.{$class}:before {
					content: '';
					background-image: url({$normal_state_icon});
					height: 28px;
					display: block;
					background-size: contain;
					background-repeat: no-repeat;
					background-position: center center;
				}
				.elementor-element:hover .{$class}:before {
					background-image: url({$hover_state_icon});
				}
				#elementor-navigator .{$class}:before {
					width: 11px;
					height: 11px;
				}

				@media (prefers-color-scheme: dark) {
					.{$class}:before {
						background-image: url({$normal_state_dark_theme_icon});
					}
					.elementor-element:hover .{$class}:before {
						background-image: url({$hover_state_dark_theme_icon});
					}
				}
			";
		}

		return $css;
	}

	/**
	 * Generate CSS for indent control.
	 *
	 * @since 1.6.12
	 *
	 * @return string.
	 */
	private function generate_css_for_indent_control() {
		/**
		 * Filters elementor widget control names that should be indented.
		 *
		 * @since 1.6.12
		 *
		 * @param string[] $indented_controls
		 */
		$indented_controls = apply_filters(
			'masteriyo_elementor_integration_indented_controls',
			array(
				// Course List widget.
				'show_difficulty_badge',
				'show_featured_ribbon',
				'show_author_avatar',
				'show_author_name',
				'show_course_duration',
				'show_students_count',
				'show_lessons_count',
				'show_price',
				'show_enroll_button',

				// Course Categories widget.
				'show_category_title',
				'show_courses_count',
			)
		);
		$indent_css        = '';

		foreach ( $indented_controls as $control_name ) {
			$indent_css .= sprintf( '.elementor-control-%s .elementor-control-title {margin-left:12px;}', $control_name );
		}

		return $indent_css;
	}

	/**
	 * Register Elementor document types.
	 *
	 * @since 1.6.12
	 *
	 * @param \Elementor\Core\Documents_Manager $documents_manager
	 */
	public function register_document_types( $documents_manager ) {
		$documents_manager->register_document_type( 'masteriyo-single-course-page', SingleCoursePageDocumentType::get_class_full_name() );
		$documents_manager->register_document_type( 'masteriyo-course-archive-page', CourseArchivePageDocumentType::get_class_full_name() );
	}

	/**
	 * Render document type information in documents list table.
	 *
	 * @since 1.6.12
	 *
	 * @param string $column_name
	 * @param integer $post_id
	 */
	public function render_document_type_column_info( $column_name, $post_id ) {
		if ( 'elementor_library_type' === $column_name ) {
			$document = \Elementor\Plugin::$instance->documents->get( $post_id );

			if ( $document && str_starts_with( $document->get_template_type(), 'masteriyo' ) ) {
				$document->print_admin_column_type();
			}
		}
	}

	/**
	 * Enqueue scripts for Elementor editor.
	 *
	 * @since 1.6.12
	 */
	public function enqueue_elementor_editor_scripts() {
		wp_enqueue_script(
			'masteriyo-elementor-editor',
			plugins_url( '/addons/elementor-integration/js/elementor-editor.js', MASTERIYO_PLUGIN_FILE ),
			array(
				'elementor-common',
			),
			MASTERIYO_VERSION,
			true
		);

		wp_localize_script(
			'masteriyo-elementor-editor',
			'_MASTERIYO_ELEMENTOR_EDITOR_',
			array(
				'page_templates'        => array(
					'single_course_page'  => Helper::get_single_course_page_default_layout_elementor_template(),
					'course_archive_page' => Helper::get_course_archive_page_default_layout_elementor_template(),
				),
				'library_btn_template'  => Helper::get_library_modal_open_btn_template(),
				'is_elementor_template' => get_post_type() === 'elementor_library',
			)
		);
	}

	/**
	 * Print the views for the Elementor editor.
	 *
	 * @since 1.6.12
	 */
	public function print_editor_views() {
		include __DIR__ . '/templates/editor-views.php';
	}

	/**
	 * Localize more data to the backend script.
	 *
	 * @since 1.6.12
	 *
	 * @param array $script_data
	 *
	 * @return array
	 */
	public function add_backend_script_data( $script_data ) {
		$script_data['backend']['data']['singleCourseTemplates']['elementor']       = Helper::get_elementor_templates( SingleCoursePageDocumentType::TYPE_SLUG );
		$script_data['backend']['data']['courseArchiveTemplates']['elementor']      = Helper::get_elementor_templates( CourseArchivePageDocumentType::TYPE_SLUG );
		$script_data['backend']['data']['add_new_course_archive_page_template_url'] = admin_url( 'edit.php?post_type=elementor_library&tabs_group&elementor_library_type=' . CourseArchivePageDocumentType::TYPE_SLUG );
		$script_data['backend']['data']['add_new_single_course_page_template_url']  = admin_url( 'edit.php?post_type=elementor_library&tabs_group&elementor_library_type=' . SingleCoursePageDocumentType::TYPE_SLUG );
		return $script_data;
	}

	/**
	 * Render custom template for the Course Archive page.
	 *
	 * @since 1.6.12
	 *
	 * @param string $template_source
	 * @param integer $template_id
	 */
	public function render_course_archive_page_template( $template_source, $template_id ) {
		if ( 'elementor' !== $template_source ) {
			return;
		}

		$frontend = new \Elementor\Frontend();

		printf( '<div class="masteriyo-course-list-display-section">' );
		echo $frontend->get_builder_content_for_display( $template_id );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( '</div>' );
	}

	/**
	 * Render custom template for the Single Course page.
	 *
	 * @since 1.6.12
	 *
	 * @param string $template_source
	 * @param integer $template_id
	 */
	public function render_single_course_page_template( $template_source, $template_id ) {
		if ( 'elementor' !== $template_source ) {
			return;
		}

		global $course;

		$frontend = new \Elementor\Frontend();

		printf( '<div id="%s" class="masteriyo-single-course">', esc_attr( $course ? 'course-' . $course->get_id() : '' ) );
		echo $frontend->get_builder_content_for_display( $template_id );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( '</div>' );
	}

	/**
	 * Add an action link for using the current template for Masteriyo in the appropriate place.
	 *
	 * @since 1.7.0
	 *
	 * @param array $actions An array of row action links.
	 * @param \WP_Post $post The post object.
	 *
	 * @return array
	 */
	public function add_use_template_for_masteriyo_action( $actions, $post ) {
		if ( PostStatus::PUBLISH !== $post->post_status ) {
			return $actions;
		}

		global $current_screen;

		if ( ! $current_screen ) {
			return $actions;
		}

		if ( 'edit' !== $current_screen->base || 'elementor_library' !== $current_screen->post_type ) {
			return $actions;
		}

		$document = \Elementor\Plugin::$instance->documents->get( $post->ID );

		if ( empty( $document ) ) {
			return $actions;
		}

		$template_type = $document ? $document->get_template_type() : '';
		$action_slug   = 'masteriyo-use-elementor-template-for-masteriyo';
		$label         = esc_html__( 'Use template for Masteriyo', 'masteriyo' );
		$url           = '';

		if ( SingleCoursePageDocumentType::TYPE_SLUG === $template_type ) {
			$url = add_query_arg(
				array(
					$action_slug    => '1',
					'template-type' => SingleCoursePageDocumentType::TYPE_SLUG,
					'template-id'   => $post->ID,
					'nonce'         => wp_create_nonce( $action_slug ),
				),
				home_url()
			);
		}

		if ( CourseArchivePageDocumentType::TYPE_SLUG === $template_type ) {
			$url = add_query_arg(
				array(
					$action_slug    => '1',
					'template-type' => CourseArchivePageDocumentType::TYPE_SLUG,
					'template-id'   => $post->ID,
					'nonce'         => wp_create_nonce( $action_slug ),
				),
				home_url()
			);
		}

		if ( ! empty( $url ) ) {
			$actions[ $action_slug ] = sprintf( '<a href="%1$s">%2$s</a>', $url, $label );
		}

		return $actions;
	}

	/**
	 * Add post states.
	 *
	 * @since 1.7.0
	 *
	 * @param array $post_states
	 * @param \WP_Post $post
	 *
	 * @return array
	 */
	public function add_post_states( $post_states, $post ) {
		if ( 'elementor_library' !== $post->post_type ) {
			return $post_states;
		}

		$document      = \Elementor\Plugin::$instance->documents->get( $post->ID );
		$template_type = $document ? $document->get_template_type() : '';

		if ( CourseArchivePageDocumentType::TYPE_SLUG === $template_type ) {
			if (
				masteriyo_string_to_bool( masteriyo_get_setting( 'course_archive.custom_template.enable' ) ) &&
				masteriyo_get_setting( 'course_archive.custom_template.template_source' ) === 'elementor' &&
				absint( masteriyo_get_setting( 'course_archive.custom_template.template_id' ) ) === absint( $post->ID )
			) {
				$post_states['masteriyo_used_template'] = __( 'Used by Masteriyo', 'masteriyo' );
			}
		} elseif ( SingleCoursePageDocumentType::TYPE_SLUG === $template_type ) {
			if (
				masteriyo_string_to_bool( masteriyo_get_setting( 'single_course.custom_template.enable' ) ) &&
				masteriyo_get_setting( 'single_course.custom_template.template_source' ) === 'elementor' &&
				absint( masteriyo_get_setting( 'single_course.custom_template.template_id' ) ) === absint( $post->ID )
			) {
				$post_states['masteriyo_used_template'] = __( 'Used by Masteriyo', 'masteriyo' );
			}
		}

		return $post_states;
	}
}
