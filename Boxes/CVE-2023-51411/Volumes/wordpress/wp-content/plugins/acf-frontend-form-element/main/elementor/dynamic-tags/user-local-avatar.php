<?php
namespace Frontend_Admin\DynamicTags;

use Frontend_Admin\Plugin;
use ElementorPro\Modules\DynamicTags\Module;
use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Data_Tag;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class User_Local_Avatar_Tag extends Data_Tag {


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
		return 'local-avatar';
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
		return __( 'Local Avatar', 'acf-frontend-form-element' );
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
		return 'frontend-admin-user-data';
	}

	/**
	 * Get Categories
	 *
	 * Returns an array of tag categories
	 *
	 * @since  2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY );
	}

	protected function register_controls() {
		$this->add_control(
			'fallback',
			array(
				'label' => __( 'Fallback', 'acf-frontend-form-element' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);
	}

	public function get_local_avatar_field() {
		$meta_key = get_option( 'local_avatar' );

		if ( ! empty( $meta_key ) ) {

			$field = get_field_object( $meta_key, 'user_' . get_current_user_id() );

			return array( $field, $meta_key );
		}

		return array();
	}

	/**
	 * Render
	 *
	 * Prints out the value of the Dynamic tag
	 *
	 * @since  2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function get_value( array $options = array() ) {
		 $image_data = array(
			 'id'  => null,
			 'url' => '',
		 );

		 list( $field, $meta_key ) = $this->get_local_avatar_field();

		 if ( $field && is_array( $field ) ) {
			 $field['return_format'] = isset( $field['save_format'] ) ? $field['save_format'] : $field['return_format'];
			 switch ( $field['return_format'] ) {
				 case 'object':
				 case 'array':
					 $value = $field['value'];
					 break;
				 case 'url':
					 $value = array(
						 'id'  => 0,
						 'url' => $field['value'],
					 );
					 break;
				 case 'id':
					 $src   = wp_get_attachment_image_src( $field['value'], $field['preview_size'] );
					 $value = array(
						 'id'  => $field['value'],
						 'url' => $src[0],
					 );
					 break;
			 }
		 }

		 if ( ! isset( $value ) ) {
			 // Field settings has been deleted or not available.
			 $value = get_field( $meta_key );
		 }

		 if ( empty( $value ) && $this->get_settings( 'fallback' ) ) {
			 $value = $this->get_settings( 'fallback' );
		 }

		 if ( ! empty( $value ) && is_array( $value ) ) {
			 $image_data['id']  = $value['id'];
			 $image_data['url'] = $value['url'];
		 }

		 return $image_data;
	}
}
