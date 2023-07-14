<?php

class HTMega_Menu {

	function __construct() {
		// add custom menu fields to menu
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'add_custom_fields_meta' ) );

	} // end constructor
	
	/**
	 * Add custom fields to $item nav object
	 * in order to be used in custom Walker
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function add_custom_fields_meta( $menu_item ) {

		// Get Menu Item Custom Data
		
      $menu_item->menutag         = get_post_meta( $menu_item->ID, '_menu_item_menutag', true );
      $menu_item->menutagcolor    = get_post_meta( $menu_item->ID, '_menu_item_menutagcolor', true );
	   $menu_item->menutagbgcolor = get_post_meta( $menu_item->ID, '_menu_item_menutagbgcolor', true );
	   $menu_item->menuposition   = get_post_meta( $menu_item->ID, '_menu_item_menuposition', true );
      $menu_item->ficon           = get_post_meta( $menu_item->ID, '_menu_item_ficon', true );
	   $menu_item->ficoncolor     = get_post_meta( $menu_item->ID, '_menu_item_ficoncolor', true );
	   $menu_item->megamenu       = get_post_meta( $menu_item->ID, '_menu_item_megamenu', true );
      $menu_item->template        = get_post_meta( $menu_item->ID, '_menu_item_template', true );
	   $menu_item->menuwidth      = get_post_meta( $menu_item->ID, '_menu_item_menuwidth', true );
	   $menu_item->disablet       = get_post_meta( $menu_item->ID, '_menu_item_disablet', true );
	   return $menu_item;
	}
      
}

// instantiate plugin's class
$GLOBALS['HTMega_Menu'] = new HTMega_Menu();
require HTMEGA_ADDONS_PL_PATH . 'extensions/ht-menu/menu/free-menu/htmenu_walker.php';
require HTMEGA_ADDONS_PL_PATH . 'extensions/ht-menu/menu/free-menu/menu_term.php';