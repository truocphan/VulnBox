<?php
namespace JupiterX_Core\Raven\Modules\Categories\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Modules\Categories\Skins;

defined( 'ABSPATH' ) || die();

class Categories extends Base_Widget {

	protected $_has_template_content = false;

	public function get_name() {
		return 'raven-categories';
	}

	public function get_title() {
		return __( 'Categories', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-categories';
	}

	public function get_script_depends() {
		return [ 'imagesloaded', 'jupiterx-core-raven-savvior' ];
	}

	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Outer_Content( $this ) );
		$this->add_skin( new Skins\Skin_Inner_Content( $this ) );
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_filter_controls();
	}

	private function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'source',
			[
				'label' => __( 'Source', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'blog',
				'options' => [
					'blog' => __( 'Blog', 'jupiterx-core' ),
					'portfolio' => __( 'Portfolio', 'jupiterx-core' ),
					'product' => __( 'Shop', 'jupiterx-core' ),
				],
				'frontend_available' => 'true',
			]
		);

		$this->add_control(
			'specific_categories',
			[
				'label' => __( 'Specific Categories', 'jupiterx-core' ),
				'type' => 'select2',
				'multiple' => true,
				'options' => [],
				'label_block' => true,
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

	private function register_filter_controls() {
		$this->start_controls_section(
			'section_filter',
			[
				'label' => __( 'Filter', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'exclude',
			[
				'label' => __( 'Exclude', 'jupiterx-core' ),
				'type' => 'select2',
				'multiple' => true,
				'options' => [],
				'label_block' => true,
			]
		);

		$this->end_controls_section();
	}
}
