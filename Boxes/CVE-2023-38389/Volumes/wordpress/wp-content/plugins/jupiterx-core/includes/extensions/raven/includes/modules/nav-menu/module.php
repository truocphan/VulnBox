<?php
namespace JupiterX_Core\Raven\Modules\Nav_Menu;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		add_filter( 'jupiterx_core_raven_nav_menu_item_title', [ $this, 'apply_menu_icons' ], 10, 3 );
	}

	public function get_widgets() {
		return [ 'nav-menu' ];
	}

	/**
	* Apply menu icon to the navigation.
	*
	* @since 1.7.0
	*
	* This function adds icon menu to navigation elements.
	* This function works when the menu icons plugin is installed.
	*
	* @SuppressWarnings(PHPMD)
	*/
	public function apply_menu_icons( $title, $item, $menu_settings ) {

		if ( ! function_exists( 'kucrut_register_sdk' ) ) {
			return $title;
		}

		$files = kucrut_register_sdk( [] );

		if ( empty( $files ) ) {
			return $title;
		}

		$menu_icons_plugin_path = plugin_dir_path( $files[0] );

		if ( ! file_exists( $menu_icons_plugin_path . '/includes/meta.php' ) ) {
			return $title;
		}

		if ( ! file_exists( $menu_icons_plugin_path . '/includes/front.php' ) ) {
			return $title;
		}

		require_once $menu_icons_plugin_path . '/includes/meta.php';
		require_once $menu_icons_plugin_path . '/includes/front.php';

		$meta = \Menu_Icons_Meta::get( $item->ID );

		\Menu_Icons_Front_End::init();

		$icon = \Menu_Icons_Front_End::get_icon( $meta );

		if ( false !== strpos( $title, '_mi' ) ) {
			return $title;
		}

		if ( empty( $icon ) ) {
			return $title;
		}

		if ( 'after' === $meta['position'] ) {
			return $title . ' ' . $icon;
		}

		return $icon . ' ' . $title;
	}
}
