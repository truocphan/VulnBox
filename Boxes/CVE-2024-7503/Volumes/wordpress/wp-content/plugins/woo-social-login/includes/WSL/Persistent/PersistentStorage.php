<?php

namespace WSL\PersistentStorage;

use WSL\PersistentStorage\Storage\StorageAbstract;
use WSL\PersistentStorage\Storage\Transient;
use WSL\PersistentStorage\Storage\Session;

require_once dirname(__FILE__) . '/Storage/per_abstract.php';
require_once dirname(__FILE__) . '/Storage/per_session.php';
require_once dirname(__FILE__) . '/Storage/per_transient.php';

/**
 * WOOSLGPersistent Class
 * 
 * Handles to store persistant transient and session data
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */

class WOOSLGPersistent {

    private static $woo_slg_instance;
    private $storage;

    /**
     * Construct too hook for init and wp_login to set the objecct
     *
     * @package  WooCommerce - Social Login
     * @since 1.0.0
     */
    public function __construct() {

        self::$woo_slg_instance = $this;

        add_action('init', array( $this, 'initialize'), 0);

        add_action('wp_login', array( $this, 'transfer_sessiondata_to_user' ), 10, 2);

    }

    /**
     * Intialize the storage via wordpress transient or session
     *
     * @package  WooCommerce - Social Login
     * @since 1.0.0
     */
    public function initialize() {

        if ($this->storage === NULL) { // if storage is empty

            if (is_user_logged_in()) { // if user already login
                $this->storage = new Transient();
            } else { // if user not login
                ob_start();
                $this->storage = new Session();
            }
        }

    }

    /**
     * Handle to set the cookie data
     *
     * @package  WooCommerce - Social Login
     * @since 1.0.0
     */
    public static function set($storage_key, $storage_value ) {

        self::$woo_slg_instance->storage->set($storage_key, $storage_value);
    }


    /**
     * Handle to get the cookie data
     *
     * @package  WooCommerce - Social Login
     * @since 1.0.0
     */
    public static function get($storage_key) {

        return self::$woo_slg_instance->storage->get($storage_key);
    }


    /**
     * Handle to delete the cookie data
     *
     * @package  WooCommerce - Social Login
     * @since 1.0.0
     */
    public static function delete($storage_key) {
        self::$woo_slg_instance->storage->delete($storage_key);
    }

    /**
     * Transfer the session data to the user transient after login
     *
     * @package  WooCommerce - Social Login
     * @since 1.0.0
     */
    public function transfer_sessiondata_to_user( $user_login, $user = null) {

        if (!$user) { 
            $user = get_user_by('login', $user_login);
        }

        $newStorage = new Transient($user->ID);

        if ($this->storage !== NULL) { // if the storage is not nulled

            $newStorage->transferData($this->storage); // store data to user transient
        }

        $this->storage = $newStorage; // assign new storage to the older storage variable
    }
}


new WOOSLGPersistent(); // create an object of persistent data object