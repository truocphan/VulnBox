<?php
namespace JupiterX_Core\Raven\Modules\Image_Gallery;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;

class Module extends Module_Base {

	public function __construct() {
		add_action( 'elementor/element/image-gallery/section_gallery_images/before_section_end', [ $this, 'extend_settings' ], 10 );
	}

	public function extend_settings( $element ) {
		$element->add_group_control(
			'box-shadow',
			[
				'name'    => 'gallery_images_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .gallery-item img',
			]
		);
	}

}
