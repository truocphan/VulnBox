<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Site;

use Elementor\Core\DynamicTags\Tag as Tag;

defined( 'ABSPATH' ) || die();

class Shortcode extends Tag {
	public function get_name() {
		return 'shortcode';
	}

	public function get_title() {
		return esc_html__( 'Shortcode', 'jupiterx-core' );
	}

	public function get_group() {
		return 'site';
	}

	public function get_categories() {
		return [
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY,
		];
	}

	protected function register_controls() {
		$this->add_control(
			'shortcode',
			[
				'label' => esc_html__( 'Shortcode', 'jupiterx-core' ),
				'type'  => 'textarea',
			]
		);
	}

	public function get_shortcode() {
		$settings = $this->get_settings();

		if ( empty( $settings['shortcode'] ) ) {
			return '';
		}

		return $settings['shortcode'];
	}

	public function render() {
		$shortcode = $this->get_shortcode();

		if ( empty( $shortcode ) ) {
			return;
		}

		echo do_shortcode( $shortcode );
	}
}
