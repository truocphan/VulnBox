<?php
/**
 * Resource handler for User data.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Resources;

defined( 'ABSPATH' ) || exit;

/**
 * Resource handler for User data.
 *
 * @since 1.6.9
 */
class UserResource {

	/**
	 * Transform the resource into an array.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\User $user
	 *
	 * @return array<string, mixed>
	 */
	public static function to_array( $user, $context = 'view' ) {
		$data = array(
			'id'                   => $user->get_id(),
			'username'             => $user->get_username( $context ),
			'nicename'             => $user->get_nicename( $context ),
			'email'                => $user->get_email( $context ),
			'url'                  => $user->get_url( $context ),
			'date_created'         => masteriyo_rest_prepare_date_response( $user->get_date_created( $context ) ),
			'status'               => $user->get_status( $context ),
			'display_name'         => $user->get_display_name( $context ),
			'nickname'             => $user->get_nickname( $context ),
			'first_name'           => $user->get_first_name( $context ),
			'last_name'            => $user->get_last_name( $context ),
			'description'          => $user->get_description( $context ),
			'rich_editing'         => $user->get_rich_editing( $context ),
			'syntax_highlighting'  => $user->get_syntax_highlighting( $context ),
			'comment_shortcuts'    => $user->get_comment_shortcuts( $context ),
			'use_ssl'              => $user->get_use_ssl( $context ),
			'show_admin_bar_front' => $user->get_show_admin_bar_front( $context ),
			'locale'               => $user->get_locale( $context ),
			'roles'                => $user->get_roles( $context ),
			'profile_image'        => array(
				'id'  => $user->get_profile_image_id( $context ),
				'url' => $user->profile_image_url(),
			),
			'billing'              => array(
				'first_name'   => $user->get_billing_first_name( $context ),
				'last_name'    => $user->get_billing_last_name( $context ),
				'company_name' => $user->get_billing_company_name( $context ),
				'company_id'   => $user->get_billing_company_id( $context ),
				'address_1'    => $user->get_billing_address_1( $context ),
				'address_2'    => $user->get_billing_address_2( $context ),
				'city'         => $user->get_billing_city( $context ),
				'postcode'     => $user->get_billing_postcode( $context ),
				'country'      => $user->get_billing_country( $context ),
				'state'        => $user->get_billing_state( $context ),
				'country_name' => masteriyo( 'countries' )->get_country_from_code( $user->get_billing_country( $context ) ),
				'state_name'   => masteriyo( 'countries' )->get_state_from_code( $user->get_billing_country( $context ), $user->get_billing_state( $context ) ),
				'email'        => $user->get_billing_email( $context ),
				'phone'        => $user->get_billing_phone( $context ),
			),
			'avatar_url'           => $user->get_avatar_url(),
		);

		/**
		 * Filter user data array resource.
		 *
		 * @since 1.6.9
		 *
		 * @param array $data User data.
		 * @param \Masteriyo\Models\User $user User object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 */
		return apply_filters( 'masteriyo_user_resource_array', $data, $user, $context );
	}
}
