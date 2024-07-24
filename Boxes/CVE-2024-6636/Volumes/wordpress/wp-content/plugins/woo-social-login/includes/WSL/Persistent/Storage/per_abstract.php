<?php

namespace WSL\PersistentStorage\Storage;

/**
 * StorageAbstract Class
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
abstract class StorageAbstract {

    protected $data = array();
    protected $session_id = null;

    /**
     * Set the key and value to the storage
     *
     * @package  WooCommerce - Social Login
     * @since 1.0.0
     */
    public function set($str_key, $str_value) {

        $this->load(true);

        $this->data[$str_key] = $str_value;

        $this->store();
    }


    /**
     * Get the value from the storage
     *
     * @package  WooCommerce - Social Login
     * @since 1.0.0
     */
    public function get($str_key) {
        
        $this->load();

        if (isset($this->data[$str_key])) {
            return $this->data[$str_key];
        }

        return null;
    }

    /**
     * Delete the key and value from storage
     *
     * @package  WooCommerce - Social Login
     * @since 1.0.0
     */
    public function delete($str_key) {
        $this->load();

        if (isset($this->data[$str_key])) {
            unset($this->data[$str_key]);
            $this->store();
        }
    }


    /**
     * CLear the storage
     *
     * @package  WooCommerce - Social Login
     * @since 1.0.0
     */
    public function clear() {
        $this->data = array();
        $this->store();
    }


    /**
     * Intialize the storage and load the data0
     *
     * @package  WooCommerce - Social Login
     * @since 1.0.0
     */
    protected function load( $create_session = false) {

        static $is_loaded = false;

        if (!$is_loaded) {

            $data = maybe_unserialize(get_site_transient($this->session_id));

            if (is_array($data)) {
                $this->data = $data;
            }

            $is_loaded = true;
        }
    }


    /**
     * Store all storage data to the transient
     *
     * @package  WooCommerce - Social Login
     * @since 1.0.0
     */
    private function store() {
        if ( empty($this->data) ) {
            delete_site_transient($this->session_id);
        } else {
            set_site_transient($this->session_id, $this->data, 3600);
        }
    }

   /**
     * Transfer storage data to the new storage
     *
     * @package  WooCommerce - Social Login
     * @since 1.0.0
     */
    public function transferData($storage) {
        $this->data = $storage->data;
        $this->store();

        $storage->clear();
    }
}