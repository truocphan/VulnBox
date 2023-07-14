<?php
// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Plugin Main Class
 */
final class HTMegaOpt_Base{

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = [];

    /**
     * Constructor for the HTMegaOpt_Base class
     *
     * Sets up all the appropriate hooks and actions
     */
    public function __construct(){

        $this->define_constants();
        $this->init_plugin();

    }

    /**
     * Initializes the HTMegaOpt_Base() class
     *
     * Checks for an existing HTMegaOpt_Base() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new HTMegaOpt_Base();
        }

        return $instance;
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }

    /**
     * Define the constants
     *
     * @return void
     */
    public function define_constants() {
        $this->define( 'HTMEGAOPT_FILE', __FILE__ );
        $this->define( 'HTMEGAOPT_PATH', dirname( HTMEGAOPT_FILE ) );
        $this->define( 'HTMEGAOPT_INCLUDES', HTMEGAOPT_PATH . '/includes' );
        $this->define( 'HTMEGAOPT_URL', plugins_url( '', HTMEGAOPT_FILE ) );
        $this->define( 'HTMEGAOPT_ASSETS', HTMEGAOPT_URL . '/assets' );
    }

    /**
     * Define constant if not already set
     *
     * @param  string $name
     * @param  string|bool $value
     * @return type
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Load the plugin after all plugis are loaded
     *
     * @return void
     */
    public function init_plugin() {
        $this->includes();
        $this->init_hooks();
    }

    public function includes() {
        require_once HTMEGAOPT_INCLUDES . '/helper-functions.php';
        require_once HTMEGAOPT_INCLUDES . '/classes/Assets.php';
        require_once HTMEGAOPT_INCLUDES . '/classes/Sanitize_Trait.php';

        if ( $this->is_request( 'admin' ) ) {
            require_once HTMEGAOPT_INCLUDES . '/classes/Admin.php';
        }

        require_once HTMEGAOPT_INCLUDES . '/classes/Api.php';
    }

    /**
     * Initialize the hooks
     *
     * @return void
     */
    public function init_hooks() {
        add_action( 'init', [ $this, 'init_classes' ] );
    }

     /**
     * Instantiate the required classes
     *
     * @return void
     */
    public function init_classes() {

        if ( $this->is_request( 'admin' ) ) {
            $this->container['admin'] = new HTMegaOpt\Admin();
        }

        $this->container['api'] = new HTMegaOpt\Api();
        $this->container['assets'] = new HTMegaOpt\Assets();
    }

    /**
     * What type of request is this?
     *
     * @param  string $type admin, ajax, cron or frontend.
     *
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();

            case 'ajax' :
                return defined( 'DOING_AJAX' );

            case 'rest' :
                return defined( 'REST_REQUEST' );

            case 'cron' :
                return defined( 'DOING_CRON' );

            case 'frontend' :
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }

}