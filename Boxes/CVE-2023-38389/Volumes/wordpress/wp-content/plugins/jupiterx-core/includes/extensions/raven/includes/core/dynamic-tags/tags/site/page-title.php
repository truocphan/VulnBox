<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Site;

use Elementor\Core\DynamicTags\Tag;
use JupiterX_Core\Raven\Utils;

defined( 'ABSPATH' ) || die();

class Page_Title extends Tag {
	public function get_name() {
		return 'page-title';
	}

	public function get_title() {
		return esc_html__( 'Page Title', 'jupiterx-core' );
	}

	public function get_group() {
		return 'site';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	protected function register_controls() {
		$this->add_control(
			'include_context',
			[
				'label' => esc_html__( 'Include Context', 'jupiterx-core' ),
				'type'  => 'switcher',
			]
		);

		$this->add_control(
			'show_home_title',
			[
				'label' => esc_html__( 'Show Home Title', 'jupiterx-core' ),
				'type'  => 'switcher',
			]
		);
	}

	public function render() {
		if ( is_home() && 'yes' !== $this->get_settings( 'show_home_title' ) ) {
			return;
		}

		if ( \Elementor\Plugin::$instance->common ) {
			$current_action_data = \Elementor\Plugin::$instance->common->get_component( 'ajax' )->get_current_action_data();

			if ( $current_action_data && 'render_tags' === $current_action_data['action'] ) {
				// Override the global $post for the render.
				query_posts( [ // phpcs:ignore WordPress.WP.DiscouragedFunctions.query_posts_query_posts
					'p'         => get_the_ID(),
					'post_type' => 'any',
				] );
			}
		}

		$include_context = 'yes' === $this->get_settings( 'include_context' );

		$title = Utils::get_page_title( $include_context );

		echo wp_kses_post( $title );
	}
}
