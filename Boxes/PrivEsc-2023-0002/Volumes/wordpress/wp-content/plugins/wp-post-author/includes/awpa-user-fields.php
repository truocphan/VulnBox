<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * WP Post Author
 *
 * Allows user to get WP Post Author.
 *
 * @class   WP_Post_Author_User_Fields
 */


class WP_Post_Author_User_Fields {

	/**
	 * Init and hook in the integration.
	 *
	 * @return void
	 */


	public function __construct() {
		$this->id                 = 'WP_Post_Author_User_Fields';
		$this->method_title       = __( 'WP Post Author User Fields', 'wp-post-author' );
		$this->method_description = __( 'WP Post Author User Fields', 'wp-post-author' );

        add_filter( 'user_contactmethods', array( $this, 'awpa_user_contact_fields') );	

	}

    public function awpa_user_contact_fields($contact_methods)
    {
        $contact_methods['awpa_contact_facebook'] = __('Facebook', 'wp-post-author');
        $contact_methods['awpa_contact_instagram'] = __('Instagram', 'wp-post-author');
        $contact_methods['awpa_contact_youtube'] = __('Youtube', 'wp-post-author');
        $contact_methods['awpa_contact_twitter'] = __('Twitter', 'wp-post-author');
        $contact_methods['awpa_contact_linkedin'] = __('LinkedIn', 'wp-post-author');

        return $contact_methods;
    }


}

$awpa_backend = new WP_Post_Author_User_Fields();