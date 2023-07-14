<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

require_once( HTMEGA_ADDONS_PL_PATH.'admin/include/settings-panel/settings-panel.php' );

class HTMega_Admin_Settings {

    function __construct() {
        HTMegaOpt_Base::init();
    }


}

new HTMega_Admin_Settings();