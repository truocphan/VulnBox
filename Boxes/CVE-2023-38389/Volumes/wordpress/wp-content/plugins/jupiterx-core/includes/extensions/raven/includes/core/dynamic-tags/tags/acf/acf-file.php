<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\ACF;

use JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\ACF\ACF_Image as ACF_Image;

defined( 'ABSPATH' ) || die();

class ACF_File extends ACF_Image {

	public function get_name() {
		return 'acf-file';
	}

	public function get_title() {
		return esc_html__( 'ACF File Field', 'jupiterx-core' );
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY ];
	}

	public function get_supported_fields() {
		return [
			'file',
		];
	}
}
