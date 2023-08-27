<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Author;

use Elementor\Core\DynamicTags\Data_Tag;

defined( 'ABSPATH' ) || die();

class Author_URL extends Data_Tag {

	public function get_name() {
		return 'author-url';
	}

	public function get_group() {
		return 'author';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::URL_CATEGORY ];
	}

	public function get_title() {
		return esc_html__( 'Author URL', 'jupiterx-core' );
	}

	public function get_panel_template_setting_key() {
		return 'url';
	}

	protected function register_controls() {
		$this->add_control(
			'url',
			[
				'label'   => esc_html__( 'URL', 'jupiterx-core' ),
				'type'    => 'select',
				'default' => 'archive',
				'options' => [
					'archive' => esc_html__( 'Author Archive', 'jupiterx-core' ),
					'website' => esc_html__( 'Author Website', 'jupiterx-core' ),
				],
			]
		);
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function get_value( array $options = [] ) {
		if ( 'archive' === $this->get_settings( 'url' ) ) {
			global $authordata;

			if ( $authordata ) {
				return get_author_posts_url( $authordata->ID, $authordata->user_nicename );
			}
		}

		return get_the_author_meta( 'url' );

	}
}
