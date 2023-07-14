<?php
namespace HTMegaOpt;

/**
 * Admin class
 */
class Admin {

    /**
     * Initialize the class
     */
    public function __construct() {
        $this->includes();
        $this->init();
    }

     /**
     * Include the controller classes
     *
     * @return void
     */
    private function includes() {
        if ( !class_exists( __NAMESPACE__ . '\Admin\Menu'  ) ) {
            require_once __DIR__ . '/Admin/Menu.php';
        }
        if ( !class_exists( __NAMESPACE__ . '\Admin\Options_Field'  ) ) {
            require_once __DIR__ . '/Admin/Options_field.php';
        }
    }

    /**
     * Admin Initilize
     *
     * @return void
     */
    public function init() {
        (new Admin\Menu())->init();
    }

}