<?php
namespace HTMegaOpt;

/**
 * Scripts and Styles Class
 */
class Assets {

    function __construct() {
        if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', [ $this, 'register' ], 5 );
        }
    }

    /**
     * Register our app scripts and styles
     *
     * @return void
     */
    public function register() {
        $this->register_scripts( $this->get_scripts() );
        $this->register_styles( $this->get_styles() );
    }

    /**
     * Register scripts
     *
     * @param  array $scripts
     *
     * @return void
     */
    private function register_scripts( $scripts ) {
        foreach ( $scripts as $handle => $script ) {
            $deps      = isset( $script['deps'] ) ? $script['deps'] : false;
            $in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : false;
            $version   = isset( $script['version'] ) ? $script['version'] : '1.0.0';

            wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
        }
    }

    /**
     * Register styles
     *
     * @param  array $styles
     *
     * @return void
     */
    public function register_styles( $styles ) {
        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;

            wp_register_style( $handle, $style['src'], $deps, HTMEGA_VERSION );
        }
    }

    /**
     * Get all registered scripts
     *
     * @return array
     */
    public function get_scripts() {
        $prefix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.min' : '';

        $scripts = [
            'htmegaopt-runtime' => [
                'src'       => HTMEGAOPT_ASSETS . '/js/runtime'.$prefix.'.js',
                'version'   => HTMEGA_VERSION,
                'in_footer' => true
            ],
            'htmegaopt-vendor' => [
                'src'       => HTMEGAOPT_ASSETS . '/js/vendors'.$prefix.'.js',
                'version'   => HTMEGA_VERSION,
                'in_footer' => true
            ],
            'htmegaopt-admin' => [
                'src'       => HTMEGAOPT_ASSETS . '/js/admin'.$prefix.'.js',
                'deps'      => [ 'jquery', 'htmegaopt-vendor', 'htmegaopt-runtime' ],
                'version'   => HTMEGA_VERSION,
                'in_footer' => true
            ]
        ];

        return $scripts;
    }

    /**
     * Get registered styles
     *
     * @return array
     */
    public function get_styles() {

        $styles = [
            'htmegaopt-style' => [
                'src' =>  HTMEGAOPT_ASSETS . '/css/style.css'
            ],
            'htmegaopt-sweetalert2' => [
                'src' =>  HTMEGAOPT_ASSETS . '/css/sweetalert2.min.css'
            ],
            'htmegaopt-admin' => [
                'src' =>  HTMEGAOPT_ASSETS . '/css/admin.css'
            ]
        ];

        return $styles;
    }

}