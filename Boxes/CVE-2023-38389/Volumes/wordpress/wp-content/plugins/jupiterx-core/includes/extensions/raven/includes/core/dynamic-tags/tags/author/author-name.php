<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Author;

use Elementor\Core\DynamicTags\Tag as Tag;

defined( 'ABSPATH' ) || die();

class Author_Name extends Tag {

	public function get_name() {
		return 'author-name';
	}

	public function get_title() {
		return esc_html__( 'Author Name', 'jupiterx-core' );
	}

	public function get_group() {
		return 'author';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	public function render() {
		echo wp_kses_post( get_the_author() );
	}
}
