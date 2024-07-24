<?php

namespace WSL\PersistentStorage\Storage;

/**
 * Session Class
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
class Session extends StorageAbstract {

    private $sessionName = 'SESSwooslg';

    public function __construct() {
        
        $this->set_session_name_for_storage();
    }

    public function set_session_name_for_storage(){

        if (class_exists('WpePlugin_common', false)) { // check if website hosted on wpengine server
            $this->sessionName = 'wordpress_wooslg';
        }

        if (defined('WOOSLG_SESSION_NAME')) { // if session name defined in constant
            $this->sessionName = WOOSLG_SESSION_NAME;
        }

        $this->sessionName = apply_filters('wooslg_session_name', $this->sessionName);
    }

    public function clear() {
        parent::clear();

        $this->destroy();
    }

    private function destroy() {
        $sessionID = $this->session_id;
        if ($sessionID) {
            $this->setCookie($sessionID, time() - YEAR_IN_SECONDS, apply_filters('wooslg_session_use_secure_cookie', false));

            add_action('shutdown', array( $this, 'destroy_site_transient' ));
        }
    }

    public function destroy_site_transient() {
        $sessionID = $this->session_id;
        if ($sessionID) {
            delete_site_transient('wooslg_' . $sessionID);
        }
    }

    protected function load($createSession = false) {
        
        static $isLoaded = false;

        if ($this->session_id === null) {
            if (isset($_COOKIE[$this->sessionName])) {
                $this->session_id = 'wooslg_persistent_' . md5(SECURE_AUTH_KEY . $_COOKIE[$this->sessionName]);
            } else if ($createSession) {
                $unique = uniqid('wooslg', true);

                $this->setCookie($unique, time() + DAY_IN_SECONDS, apply_filters('wooslg_session_use_secure_cookie', false));

                $this->session_id = 'wooslg_persistent_' . md5(SECURE_AUTH_KEY . $unique);

                $isLoaded = true;
            }
        }

        if (!$isLoaded) {
            if ($this->session_id !== null) {
                $data = maybe_unserialize(get_site_transient($this->session_id));
                if (is_array($data)) {
                    $this->data = $data;
                }
                $isLoaded = true;
            }
        }
    }

    private function setCookie($value, $expire, $secure = false) {

        setcookie($this->sessionName, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure);
        flush(); // clear the buffer data
    }
}