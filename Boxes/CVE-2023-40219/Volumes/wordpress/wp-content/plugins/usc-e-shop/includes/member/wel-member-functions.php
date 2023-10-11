<?php
/**
 * Welcart Member Functions
 *
 * Functions for Member related.
 *
 * @package Welcart
 */

defined( 'ABSPATH' ) || exit;

/**
 * Function to get member login status.
 *
 * @since 2.2.2
 *
 * @return boolean If the customer is logged in, it returns true.
 */
function wel_is_logged_in() {
	global $usces;

	if ( false === $usces->is_member_logged_in() ) {
		$res = false;
	} else {
		$res = true;
	}
	return $res;
}

/**
 * Function to get member data.
 *
 * @since 2.2.2
 *
 * @param int $member_id ID of the member.
 * @return array All data of the memner. If the corresponding member does not exist with the member_id, it returns false.
 */
function wel_get_member( $member_id ) {
	$WelMember = new Welcart\MemberData( $member_id );
	return $WelMember->get_data();
}

/**
 * Function to get member's ID logged in.
 *
 * @since 2.2.2
 *
 * @return int member_id. If the member is not logged in, it returns false.
 */
function wel_logged_in_id() {
	global $usces;

	$usces->get_current_member();
	return $usces->current_member['id'];
}

/**
 * Function to get member data by email.
 *
 * @since 2.2.2
 * @param string $email Memebr's email.
 * @return array All data of the memner. If the corresponding member does not exist with the email, it returns false.
 */
function wel_get_member_by_email( $email ) {
	$WelMember = new Welcart\MemberData();
	$member_id = $WelMember->get_id_by_email( $email );
	$WelMember->set_data( $member_id );
	return $WelMember->get_data();
}
