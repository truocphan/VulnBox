<?php

class HTMega_Menu_Term_Meta{

    /**
     * Singleton
     */
    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    private function __construct(){

        add_filter( 'wp_setup_nav_menu_item', [ $this, 'custom_nav_fields_data' ] );
        add_filter('wp_nav_menu_args', [ $this, '_change_nav_menu_args' ], PHP_INT_MAX);

    }

    public function custom_nav_fields_data( $menu_item ){

        // Get Menu Item Custom Data
        $item_settings = get_post_meta( $menu_item->ID, 'htmega_menu_settings', true );

        
        // Settings
        if( !empty( $item_settings['menu-item-menuwidth-'.$menu_item->ID] ) ){
            $menu_item->menuwidth = $item_settings['menu-item-menuwidth-'.$menu_item->ID];
        }
        
        if( !empty( $item_settings['menu-item-menuposition-'.$menu_item->ID] ) ){
            $menu_item->menuposition = $item_settings['menu-item-menuposition-'.$menu_item->ID];
        }
        if( !empty( $item_settings['menu-item-template-'.$menu_item->ID] ) ){
            $menu_item->template = $item_settings['menu-item-template-'.$menu_item->ID];
        }

        return $menu_item;
    }

    function _change_nav_menu_args( $args ){

        if ( is_admin() ) {
            return $args;
        }

        $current_theme_location = isset( $args['theme_location'] ) ? $args['theme_location'] : '';

        $locations = get_nav_menu_locations();

        // Menu ID
        if ( ! isset( $locations[ $current_theme_location ] ) ) return $args;     
        $menu_id = isset( $locations[ $current_theme_location ] ) ? $locations[ $current_theme_location ] : '';
        if ( ! $menu_id ) return $args;

        // Menu And Location
        if (!empty($locations[$args['theme_location']])) {
            $menu = wp_get_nav_menu_object($locations[$args['theme_location']]);
        } elseif (!empty($args['menu'])) {
            $menu = wp_get_nav_menu_object($args['menu']);
        } else {
            $menus = (array)wp_get_nav_menus();
            if ($menus) {
                foreach ($menus as $menu) {
                    $has_items = wp_get_nav_menu_items($menu->term_id, ['update_post_term_cache' => false]);
                    if ($has_items) break;
                }
            }
        }

        if (!isset($menu) || is_wp_error($menu) || !is_object($menu)) {
            return $args;
        }

        $settings = get_option( "ht_menu_options_" . $menu_id );
        if ( isset ( $settings['enable_menu'] ) && $settings['enable_menu'] == 'on' ) {

            $htmega_on_mobile = '<a href="#" class="htmobile-aside-button"><i class="fa fa-bars"></i></a>';
            $htmega_on_mobile_menu = '<div class="htmobile-menu-wrap"><a class="htmobile-aside-close"><i class="fa fa-times"></i></a><div class="htmobile-navigation"><ul id="%1$s" class="%2$s">%3$s</ul></div></div>';

            $items_wrap = '<div class="htmega-menu-area"><ul id="%1$s" class="%2$s">%3$s</ul>'.$htmega_on_mobile.'</div>'.$htmega_on_mobile_menu;

            $args = [
                'menu'            => $args['menu'],
                'menu_id'         => $args['menu_id'],
                'menu_class'      => 'htmega-megamenu',
                'theme_location'  => $args['theme_location'],
                'container'       => 'div',
                'container_id'    => $args['container_id'],
                'container_class' => 'htmega-menu-container',
                'fallback_cb'     => false,
                'before'          => $args['before'],
                'after'           => $args['after'],
                'link_before'     => $args['link_before'],
                'link_after'      => $args['link_after'],
                'echo'            => $args['echo'],
                'depth'           => $args['depth'],
                'items_wrap'      => $items_wrap,
                'item_spacing'    => isset($args['item_spacing']) ? $args['item_spacing'] : '',
                'walker'          => new HTMega_Menu_Nav_Walker()
            ];
            return apply_filters( 'htmegamenu_nav_menu_args', $args );

        }else{ return $args; }

    }
    public function get_menu_id( $location = null ) {
        $locations = get_nav_menu_locations();
        return isset( $locations[ $location ] ) ? $locations[ $location ] : false;
    }

}
HTMega_Menu_Term_Meta::instance();