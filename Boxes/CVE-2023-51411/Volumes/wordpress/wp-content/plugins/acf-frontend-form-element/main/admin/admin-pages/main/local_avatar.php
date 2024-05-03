<?php
namespace Frontend_Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Local_Avatar_Settings {


	function frontend_admin_get_local_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
		$user = '';

		// Get user by id or email
		if ( is_numeric( $id_or_email ) ) {
			$id   = (int) $id_or_email;
			$user = get_user_by( 'id', $id );
		} elseif ( is_object( $id_or_email ) ) {
			if ( ! empty( $id_or_email->user_id ) ) {
				$id   = (int) $id_or_email->user_id;
				$user = get_user_by( 'id', $id );
			}
		} else {
			$user = get_user_by( 'email', $id_or_email );
		}
		if ( ! $user ) {
			return $avatar;
		}
		// Get the user id
		$user_id = $user->ID;

		$img_field_key = get_option( 'local_avatar' );

		if ( $img_field_key == 'none' ) {
			return $avatar;
		}

		// Get the file id
		$image_id = get_field_object( $img_field_key, 'user_' . $user_id );

		if ( ! $image_id ) {
			return $avatar;
		}

		if ( is_array( $image_id ) ) {
			$image_id = $image_id['ID'];
		}

		if ( filter_var( $image_id, FILTER_VALIDATE_URL ) ) {
			$avatar_url = $image_id;
		} else {
			$image_url = wp_get_attachment_image_src( $image_id, 'thumbnail' );
			if ( ! $image_url ) {
				return $avatar;
			}
			$avatar_url = $image_url[0];
		}

		// Get the img markup
		$avatar = '<img alt="' . $alt . '" src="' . $avatar_url . '" class="avatar avatar-' . $size . '" height="' . $size . '" width="' . $size . '"/>';

		// Return our new avatar
		return $avatar;
	}

	public function frontend_admin_hide_gravatar_field( $hook ) {
		if ( get_option( 'local_avatar' ) == 'none' ) {
			return;
		}
		echo '<style>
        tr.user-profile-picture{
            display: none;
        }
        </style>';
	}

	public function get_settings_fields( $field_keys ) {
		$default      = get_option( 'local_avatar' ) ? get_option( 'local_avatar' ) : 'none';
		$image_fields = feadmin_get_field_data( 'image', true );
		if ( $image_fields ) {
			$local_fields = array(
				'local_avatar' => array(
					'label'         => __( 'Avatar Field', 'acf-frontend-form-element' ),
					'type'          => 'select',
					'instructions'  => '',
					'required'      => 0,
					'wrapper'       => array(
						'width' => '30',
						'class' => '',
						'id'    => '',
					),
					'only_front'    => 0,
					'choices'       => $image_fields,
					'value'         => $default,
					'allow_null'    => 1,
					'ajax'          => 0,
					'return_format' => 'value',
					'placeholder'   => 'None',
				),
			);
		} else {
			$local_fields = array(
				'local_avatar' => array(
					'label'           => __( 'Avatar Field', 'acf-frontend-form-element' ),
					'message'         => '<h3>' . __( 'Please create an image type field within a form or a field group in order to proceed.', 'acf-frontend-form-element' ) . '</h3>',
					'type'            => 'message',
					'no_data_collect' => 1,
					'instructions'    => '',
				),
			);
		}

		return $local_fields;
	}

	public function __construct() {
		 add_filter( 'get_avatar', array( $this, 'frontend_admin_get_local_avatar' ), 10, 5 );
		add_action( 'admin_head', array( $this, 'frontend_admin_hide_gravatar_field' ) );
		add_filter( 'frontend_admin/local_avatar_fields', array( $this, 'get_settings_fields' ) );
	}

}
new Local_Avatar_Settings( $this );
