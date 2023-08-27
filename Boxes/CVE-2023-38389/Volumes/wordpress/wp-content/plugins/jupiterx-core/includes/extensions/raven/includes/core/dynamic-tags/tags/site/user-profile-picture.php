<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Site;

use Elementor\Core\DynamicTags\Data_Tag;

defined( 'ABSPATH' ) || die();

class User_Profile_Picture extends Data_Tag {

	public function get_name() {
		return 'user-profile-picture';
	}

	public function get_title() {
		return esc_html__( 'User Profile Picture', 'jupiterx-core' );
	}

	public function get_group() {
		return 'site';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ];
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function get_value( array $options = [] ) {
		return [
			'id'  => '',
			'url' => get_avatar_url( get_current_user_id() ),
		];
	}
}
