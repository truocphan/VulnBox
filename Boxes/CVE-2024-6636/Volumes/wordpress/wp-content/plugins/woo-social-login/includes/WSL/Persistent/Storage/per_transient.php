<?php

namespace WSL\PersistentStorage\Storage;

/**
 * Transient Class
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
class Transient extends StorageAbstract {

    public function __construct( $userid = false) {

		$this->intialize_transient_data( $userid );        
    }

    public function intialize_transient_data( $userid = false ){
    	
    	if ( $userid === false) {
            $userid = get_current_user_id();
        }

        $this->session_id = 'wooslg_persistent_' . $userid;
    }
}