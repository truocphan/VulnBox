<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Comment;

use Elementor\Core\DynamicTags\Data_Tag;

defined( 'ABSPATH' ) || die();

class Comments_URL extends Data_Tag {

	public function get_name() {
		return 'comments-url';
	}

	public function get_title() {
		return esc_html__( 'Comments URL', 'jupiterx-core' );
	}

	public function get_group() {
		return 'comment';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::URL_CATEGORY ];
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function get_value( array $options = [] ) {
		return get_comments_link();
	}
}
