<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * WP Post Author
 *
 * Allows user to get WP Post Author.
 *
 * @class   WP_Post_Author_Core
 */


class WP_Post_Author_Core {



	/**
	 * Init and hook in the integration.
	 *
	 * @return void
	 */


	public function __construct() {
		$this->id                 = 'WP_Post_Author_Core';
		$this->method_title       = __( 'WP Post Author Core', 'wp-post-author' );
		$this->method_description = __( 'WP Post Author Core', 'wp-post-author' );

        include_once 'awpa-backend.php';
        include_once 'awpa-functions.php';
        include_once 'awpa-shortcodes.php';
        include_once 'awpa-frontend.php';



    }



}

$awpa_frontend = new WP_Post_Author_Core();