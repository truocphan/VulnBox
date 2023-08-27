<?php
namespace JupiterX_Core\Raven\Modules\Search_Form\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Modules\Search_Form\Skins;

defined( 'ABSPATH' ) || die();

class Search_Form extends Base_Widget {

	protected $_has_template_content = false;

	protected function register_skins() {
		$this->add_skin( new Skins\Classic( $this ) );
		$this->add_skin( new Skins\Full( $this ) );
	}

	public function get_name() {
		return 'raven-search-form';
	}

	public function get_title() {
		return __( 'Search Form', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-search';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'placeholder',
			[
				'label' => __( 'Placeholder', 'jupiterx-core' ),
				'type' => 'text',
				'default' => 'Search...',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'icon_new',
			[
				'label' => __( 'Choose Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'fa-solid',
				],
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

	public function form_home_url() {
		$form_url = home_url( '/' );

		if ( ! function_exists( 'pll_the_languages' ) ) {
			return $form_url;
		}

		$polylang_options = get_option( 'polylang' );

		if ( empty( $polylang_config ) && pll_current_language() !== pll_default_language() ) {
			$form_url = home_url( pll_current_language() );
		}

		return $form_url;
	}

	protected function render() {}
}
