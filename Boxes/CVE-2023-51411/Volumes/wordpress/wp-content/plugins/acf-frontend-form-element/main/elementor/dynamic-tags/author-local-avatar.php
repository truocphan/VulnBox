<?php
namespace Frontend_Admin\DynamicTags;

use Frontend_Admin\Plugin;
use ElementorPro\Modules\DynamicTags\Module;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Author_Local_Avatar_Tag extends User_Local_Avatar_Tag {


	/**
	 * Get Name
	 *
	 * Returns the Name of the tag
	 *
	 * @since  2.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_name() {
		return 'author-local-avatar';
	}

	/**
	 * Get Title
	 *
	 * Returns the title of the Tag
	 *
	 * @since  2.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Author Local Avatar', 'acf-frontend-form-element' );
	}

	/**
	 * Get Group
	 *
	 * Returns the Group of the tag
	 *
	 * @since  2.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_group() {
		return 'author';
	}

	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY );
	}

	public function get_local_avatar_field() {
		$meta_key = get_option( 'local_avatar' );

		if ( ! empty( $meta_key ) ) {

			$field = get_field_object( $meta_key, 'user_' . (int) get_the_author_meta( 'ID' ) );

			return array( $field, $meta_key );
		}

		return array();
	}

}
