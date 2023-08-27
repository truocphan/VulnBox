<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Post;

use Elementor\Core\DynamicTags\Tag as Tag;

defined( 'ABSPATH' ) || die();

class Post_ID extends Tag {
	public function get_name() {
		return 'post-id';
	}

	public function get_title() {
		return esc_html__( 'Post ID', 'jupiterx-core' );
	}

	public function get_group() {
		return 'post';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	public function render() {
		echo get_the_ID();
	}
}
