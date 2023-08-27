<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Site;

use Elementor\Core\DynamicTags\Data_Tag;

defined( 'ABSPATH' ) || die();

class Site_Logo extends Data_Tag {
	public function get_name() {
		return 'site-logo';
	}

	public function get_title() {
		return esc_html__( 'Site Logo', 'jupiterx-core' );
	}

	public function get_group() {
		return 'site';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ];
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function get_value( array $options = [] ) {
		$custom_logo_id = get_theme_mod( 'custom_logo' );

		$url = $custom_logo_id ? wp_get_attachment_image_src( $custom_logo_id, 'full' )[0] : \Elementor\Utils::get_placeholder_image_src();

		return [
			'id'  => $custom_logo_id,
			'url' => $url,
		];
	}
}
