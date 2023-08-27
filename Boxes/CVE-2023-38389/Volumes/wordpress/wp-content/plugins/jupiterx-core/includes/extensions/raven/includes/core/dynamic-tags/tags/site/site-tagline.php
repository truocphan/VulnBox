<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Site;

use Elementor\Core\DynamicTags\Tag;

defined( 'ABSPATH' ) || die();

class Site_Tagline extends Tag {
	public function get_name() {
		return 'site-tagline';
	}

	public function get_title() {
		return esc_html__( 'Site Tagline', 'jupiterx-core' );
	}

	public function get_group() {
		return 'site';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	public function render() {
		echo wp_kses_post( get_bloginfo( 'description' ) );
	}
}
