<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Author;

use Elementor\Core\DynamicTags\Data_Tag;

defined( 'ABSPATH' ) || die();

class Author_Profile_Picture extends Data_Tag {

	public function get_name() {
		return 'author-profile-picture';
	}

	public function get_title() {
		return esc_html__( 'Author Profile Picture', 'jupiterx-core' );
	}

	public function get_group() {
		return 'author';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ];
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function get_value( array $options = [] ) {
		global $authordata;

		if ( isset( $authordata->ID ) ) {
			return [
				'id'  => '',
				'url' => get_avatar_url( (int) get_the_author_meta( 'ID' ) ),
			];
		}

		$author = get_userdata( get_post()->post_author );

		return [
			'id'  => '',
			'url' => get_avatar_url( $author ),
		];
	}
}
