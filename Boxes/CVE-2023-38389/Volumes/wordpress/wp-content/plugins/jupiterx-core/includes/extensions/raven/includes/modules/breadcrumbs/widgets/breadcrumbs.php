<?php
namespace JupiterX_Core\Raven\Modules\Breadcrumbs\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Plugin as Elementor;
use JupiterX_Core\Raven\Utils;
use Elementor\Utils as ElementorUtils;

defined( 'ABSPATH' ) || die();

/**
 * Temporary supressed.
 *
 * @SuppressWarnings(ExcessiveClassComplexity)
 */

class Breadcrumbs extends Base_Widget {

	public function get_name() {
		return 'raven-breadcrumbs';
	}

	public function get_title() {
		return __( 'Breadcrumbs', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-breadcrumbs';
	}

	protected function register_controls() {
		$this->register_section_content();
		$this->register_section_breadcrumbs();
	}

	private function register_section_content() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'jupiterx-core' ),
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => Utils::get_direction( 'left' ),
				'prefix_class' => 'elementor%s-align-',
				'options' => [
					'flex-start' => [
						'title' => is_rtl() ? __( 'Right', 'jupiterx-core' ) : __( 'Left', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-text-align-right' : 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => is_rtl() ? __( 'Left', 'jupiterx-core' ) : __( 'Right', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-text-align-left' : 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .breadcrumb' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} #breadcrumbs' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label' => __( 'HTML Tag', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'ol',
				'options' => [
					'ol' => __( 'Default', 'jupiterx-core' ),
					'p' => 'p',
					'div' => 'div',
					'nav' => 'nav',
					'span' => 'span',
				],
			]
		);

		$this->add_control(
			'breadcrumbs_type',
			[
				'label' => __( 'Breadcrumbs Type', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'jupiterx-core' ),
					'yoast' => 'Yoast SEO',
					'navxt' => 'Breadcrumb NavXT',
				],
			]
		);

		if ( ! function_exists( 'bcn_display' ) ) {
			$this->add_control(
				'html_navxt_deactivated_alert',
				[
					'raw' => __( 'The Breadcrumb NavXT is not installed. To use this option please install the Breadcrumb NavXT.', 'jupiterx-core' ),
					'type' => 'raw_html',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
					'condition' => [
						'breadcrumbs_type' => 'navxt',
					],
				]
			);
		}

		if ( ! $this->is_yoast_plugin_installed() ) {
			$this->add_control(
				'html_yoast_deactivate_alert',
				[
					'raw' => __( 'Yoast SEO plugin is not installed. To use this option please install the Yoast SEO Plugin.', 'jupiterx-core' ),
					'type' => 'raw_html',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
					'condition' => [
						'breadcrumbs_type' => 'yoast',
					],
				]
			);
		}

		if ( $this->is_yoast_plugin_installed() && ! $this->is_yoast_breadcrumbs_enabled() ) {
			$this->add_control(
				'html_yoast_disabled_alert',
				[
					'raw' => __( 'Breadcrumbs are disabled in the Yoast SEO', 'jupiterx-core' ) . ' ' . sprintf( '<a href="%s" target="_blank">%s</a>', admin_url( 'admin.php?page=wpseo_titles#top#breadcrumbs' ), __( 'Breadcrumbs Panel', 'jupiterx-core' ) ),
					'type' => 'raw_html',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
					'condition' => [
						'breadcrumbs_type' => 'yoast',
					],
				]
			);
		}

		$this->end_controls_section();
	}

	private function register_section_breadcrumbs() {
		$this->start_controls_section(
			'section_breadcrumbs',
			[
				'label' => __( 'Breadcrumbs', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#a5a5a5',
				'selectors' => [
					'{{WRAPPER}}, {{WRAPPER}} .breadcrumb-item.active span, {{WRAPPER}} span.current-item' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-breadcrumbs-yoast .breadcrumb_last' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-breadcrumbs-navxt span.current-item' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'title_typography',
				'scheme' => '1',
				'selector' => '{{WRAPPER}},{{WRAPPER}} .breadcrumb,{{WRAPPER}} #breadcrumbs, {{WRAPPER}} span, {{WRAPPER}} li',
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => __( 'Separator Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#bfbfbf',
				'selectors' => [
					'{{WRAPPER}} .breadcrumb-item + .breadcrumb-item::before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-breadcrumbs-yoast #breadcrumbs' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-breadcrumbs-navxt .breadcrumb' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_content' );

		$this->start_controls_tab(
			'tab_content_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'link_color_normal',
			[
				'label' => __( 'Link Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} a, {{WRAPPER}} a *' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_content_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'link_color_hover',
			[
				'label' => __( 'Link Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} a:hover, {{WRAPPER}} a:hover *' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings         = $this->get_settings_for_display();
		$breadcrumbs_type = $settings['breadcrumbs_type'];
		$html_tag         = $this->get_html_tag();

		$this->add_render_attribute(
			'wrapper',
			'class',
			[
				'raven-breadcrumbs',
				'raven-breadcrumbs-' . $settings['breadcrumbs_type'],
			]
		);

		echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>';
		if ( 'navxt' === $breadcrumbs_type ) {
			$this->navtx_breadcrumbs( $html_tag );
		} elseif ( 'yoast' === $breadcrumbs_type ) {
			$this->yoast_breadcrumbs( $html_tag );
		} else {
			$this->raven_breadcrumbs( $html_tag );
		}
		echo '</div>';
	}

	private function get_html_tag() {
		$html_tag = $this->get_settings( 'html_tag' );

		if ( empty( $html_tag ) ) {
			$html_tag = 'p';
		}

		return $html_tag;
	}

	/**
	 * Echo the breadcrumb.
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 *
	 * @since 1.6.0
	 *
	 * @return void
	 */

	private function raven_breadcrumbs( $html_tag = 'ol' ) {
		if ( is_home() || is_front_page() ) {
			return;
		}

		$inner_tag = 'li';

		if ( 'ol' !== $html_tag ) {
			$inner_tag = 'span';
		}

		global $post;

		$post_type                 = get_post_type();
		$breadcrumbs               = array();
		$breadcrumbs[ home_url() ] = __( 'Home', 'jupiterx-core' );

		// Custom post type.
		if ( ! in_array( $post_type, array( 'page', 'attachment', 'post' ), true ) && ! is_404() && ! is_author() ) {

			$post_type_object       = get_post_type_object( $post_type );
			$post_type_archive_link = get_post_type_archive_link( $post_type );

			if ( $post_type_object && $post_type_archive_link ) {
				$breadcrumbs[ $post_type_archive_link ] = $post_type_object->labels->name;
			}
		}

		// Single posts.
		if ( is_single() && 'post' === $post_type ) {

			$categories = get_the_category( $post->ID );

			foreach ( array_slice( $categories, 0, 2 ) as $category ) {
				$breadcrumbs[ get_category_link( $category->term_id ) ] = $category->name;
			}

			if ( count( $categories ) > 2 ) {
				$breadcrumbs['#'] = '...';
			}

			$breadcrumbs[] = get_the_title();
		} elseif ( is_singular() && ! is_home() && ! is_front_page() ) { // Pages/custom post type.
			$current_page = array( $post );

			// Get the parent pages of the current page if they exist.
			if ( isset( $current_page[0]->post_parent ) ) {
				while ( $current_page[0]->post_parent ) {
					array_unshift( $current_page, get_post( $current_page[0]->post_parent ) );
				}
			}

			// Add returned pages to breadcrumbs.
			foreach ( $current_page as $page ) {
				$breadcrumbs[ get_permalink( $page->ID ) ] = $page->post_title;
			}
		} elseif ( is_category() ) { // Categories.
			$breadcrumbs[] = single_cat_title( '', false );
		} elseif ( is_tax() ) { // Taxonomies.
			$current_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

			$ancestors = array_reverse( get_ancestors( $current_term->term_id, get_query_var( 'taxonomy' ) ) );

			foreach ( $ancestors as $ancestor ) {

				$ancestor = get_term( $ancestor, get_query_var( 'taxonomy' ) );

				$breadcrumbs[ get_term_link( $ancestor->slug, get_query_var( 'taxonomy' ) ) ] = $ancestor->name;
			}

			$breadcrumbs[] = $current_term->name;
		} elseif ( is_search() ) { // Searches.
			$breadcrumbs[] = __( 'Search results for:', 'jupiterx-core' ) . ' ' . get_search_query();
		} elseif ( is_author() ) { // Author archives.
			$author        = get_queried_object();
			$breadcrumbs[] = __( 'Author Archives:', 'jupiterx-core' ) . ' ' . $author->display_name;
		} elseif ( is_tag() ) {// Tag archives.
			$breadcrumbs[] = __( 'Tag Archives:', 'jupiterx-core' ) . ' ' . single_tag_title( '', false );
		} elseif ( is_date() ) { // Date archives.
			$breadcrumbs[] = __( 'Archives:', 'jupiterx-core' ) . ' ' . get_the_time( 'F Y' );
		} elseif ( is_404() ) { // 404.
			$breadcrumbs[] = __( '404', 'jupiterx-core' );
		}

		/**
		 * Filter the breadcrumb.
		 *
		 * @since 1.6.0
		 *
		 * @param array $breadcrumbs Breadcrumb items.
		 */
		$breadcrumbs = apply_filters_deprecated( 'raven_breadcrumb', [ $breadcrumbs ], '1.18.0', 'jupiterx_core_raven_breadcrumb' );

		/**
		 * Filter the breadcrumb.
		 *
		 * @since 1.18.0
		 *
		 * @param array $breadcrumbs Breadcrumb items.
		 */
		$breadcrumbs = apply_filters( 'jupiterx_core_raven_breadcrumb', $breadcrumbs );

		$html_tag  = ElementorUtils::validate_html_tag( $html_tag );
		$inner_tag = ElementorUtils::validate_html_tag( $inner_tag );

		// Open breadcrumb.
		echo '<' . $html_tag . ' class="breadcrumb">';
			$count = 0;

		foreach ( $breadcrumbs as $breadcrumb_url => $breadcrumb ) {

			// Breadcrumb items.
			if ( count( $breadcrumbs ) - 1 !== $count ) { ?>
				<<?php echo $inner_tag; ?> class="breadcrumb-item"><a href="<?php echo esc_url( $breadcrumb_url ); ?>"><span><?php echo esc_html( $breadcrumb ); ?></span></a>
				</<?php echo $inner_tag; ?>>
				<?php
			} else { // Active.
				?>
				<<?php echo $inner_tag; ?> class="breadcrumb-item active" aria-current="page"><span><?php echo esc_html( $breadcrumb ); ?></span></<?php echo $inner_tag; ?>>

				<?php
			}

			$count++;
		}
		// Close breadcrumb.
		echo '</' . $html_tag . '>';
	}

	private function navtx_breadcrumbs( $html_tag ) {
		$html_tag = ElementorUtils::validate_html_tag( $html_tag );

		/**
		 * Add support for Breadcrumb NavXT plugin.
		 */
		if ( function_exists( 'bcn_display' ) ) {

			echo '<' . $html_tag . ' class="breadcrumb" typeof="BreadcrumbList" vocab="https://schema.org/">';

				bcn_display();

			echo '</' . $html_tag . '>';

			return;
		}
	}

	private function is_yoast_breadcrumbs_enabled() {
		$breadcrumbs_enabled = current_theme_supports( 'yoast-seo-breadcrumbs' );

		if ( ! $breadcrumbs_enabled ) {
			$wpseo_titles = get_option( 'wpseo_titles', [] );

			if ( empty( $wpseo_titles ) ) {
				return false;
			}

			return ! empty( $wpseo_titles['breadcrumbs-enable'] );
		}

		return $breadcrumbs_enabled;
	}

	private function is_yoast_plugin_installed() {
		return function_exists( 'yoast_breadcrumb' );
	}

	private function yoast_breadcrumbs( $html_tag ) {
		if ( ! $this->is_yoast_plugin_installed() || ! $this->is_yoast_breadcrumbs_enabled() ) {
			return;
		}

		$html_tag = ElementorUtils::validate_html_tag( $html_tag );

		yoast_breadcrumb( '<' . $html_tag . ' id="breadcrumbs">', '</' . $html_tag . '>' );
	}

}
