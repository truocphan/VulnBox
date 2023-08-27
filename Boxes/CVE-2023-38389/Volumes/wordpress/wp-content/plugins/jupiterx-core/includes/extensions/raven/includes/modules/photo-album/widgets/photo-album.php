<?php
namespace JupiterX_Core\Raven\Modules\Photo_Album\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Modules\Photo_Album\Skins;

defined( 'ABSPATH' ) || die();

class Photo_Album extends Base_Widget {

	protected $_has_template_content = false;

	public function get_name() {
		return 'raven-photo-album';
	}

	public function get_title() {
		return __( 'Photo Album', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-photo-album';
	}

	public function get_script_depends() {
		return [
			'imagesloaded',
			'jupiterx-core-raven-savvior',
			'jupiterx-core-raven-anime',
			'jupiterx-core-raven-stack-motion-effects',
		];
	}

	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Cover( $this ) );
		$this->add_skin( new Skins\Skin_Stack( $this ) );
	}

	protected function register_controls() {
		$this->register_content_controls();
	}

	private function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'jupiterx-core' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'images',
			[
				'type' => 'gallery',
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'jupiterx-core' ),
				'type' => 'text',
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'placeholder' => 'Title',
			]
		);

		$repeater->add_control(
			'description',
			[
				'type' => 'textarea',
				'dynamic' => [
					'active' => true,
				],
				'label' => __( 'Description', 'jupiterx-core' ),
				'label_block' => true,
				'placeholder' => 'Description',
			]
		);

		$repeater->add_control(
			'stack_color',
			[
				'type' => 'color',
				'label' => __( 'Stack Color', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'list',
			[
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
			]
		);

		$this->end_controls_section();

		$this->update_control(
			'_skin',
			[
				'frontend_available' => 'true',
			]
		);
	}
}
