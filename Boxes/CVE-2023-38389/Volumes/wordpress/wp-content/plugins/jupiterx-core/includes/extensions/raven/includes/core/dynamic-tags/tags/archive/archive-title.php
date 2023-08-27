<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Archive;

use Elementor\Core\DynamicTags\Tag as Tag;
use JupiterX_Core\Raven\Utils;

defined( 'ABSPATH' ) || die();

class Archive_Title extends Tag {
	public function get_name() {
		return 'archive-title';
	}

	public function get_title() {
		return esc_html__( 'Archive Title', 'jupiterx-core' );
	}

	public function get_group() {
		return 'archive';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	public function render() {
		$include_context = 'yes' === $this->get_settings( 'include_context' );

		$page_title = Utils::get_page_title( $include_context );

		echo wp_kses_post( $page_title );
	}

	protected function register_controls() {
		$this->add_control(
			'include_context',
			[
				'label' => esc_html__( 'Include Context', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
			]
		);
	}
}
