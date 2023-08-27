<?php

namespace JupiterX_Core\Raven\Modules\Elementor_Ads;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;
use Elementor\Plugin;
use Elementor\Utils as ElementorUtils;
use Elementor\Core\Kits\Documents\Kit;
use JupiterX_Core\Raven\Plugin as Raven;

/**
 * Handle elementor ads and hide or disable them.
 * Some of features will be disabled if Elementor pro is activated.
 *
 * @since 2.5.0
 */
class Module extends Module_Base {
	/**
	 * Class construct.
	 *
	 * @since 2.5.0
	 */
	public function __construct() {
		$this->actions();
	}

	/**
	 * Actions.
	 * Runs when Elementor pro is not activated.
	 *
	 * @since 2.5.0
	 */
	private function actions() {
		if ( function_exists( 'elementor_pro_load_plugin' ) ) {
			return;
		}

		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'elementor_editor_assets' ] );
		add_action( 'elementor/preview/enqueue_scripts', [ $this, 'preview_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_assets' ] );
		add_action( 'elementor/app/init', [ $this, 'admin_assets' ], 9999 );

		add_action( 'elementor/admin/menu/after_register', [ $this, 'remove_elementor_pro_related_menu_items' ], 10, 1 );

		add_filter( 'plugin_action_links_' . ELEMENTOR_PLUGIN_BASE, [ $this, 'remove_go_pro_from_plugin_page' ], 999, 1 );

		add_filter( 'elementor/frontend/admin_bar/settings', [ $this, 'remove_theme_builder_from_admin_bar' ], 99 );

		update_option( '_elementor_editor_upgrade_notice_dismissed', strtotime( '+365 day', time() ) );
	}

	/**
	 * Remove theme builder link from admin bar.
	 *
	 * @since 2.5.0
	 */
	public function remove_theme_builder_from_admin_bar( $config ) {
		foreach ( $config['elementor_edit_page']['children'] as $key => $value ) {
			if ( 'elementor_app_site_editor' === $value['id'] ) {
				unset( $config['elementor_edit_page']['children'][ $key ] );
			}
		}

		return $config;
	}

	/**
	 * Remove Elementor pro menus.
	 *
	 * @since 2.5.0
	 */
	public function remove_elementor_pro_related_menu_items( $class ) {
		$to_delete = [
			'go_elementor_pro',
			'elementor_custom_custom_code',
			'elementor_custom_icons',
			'elementor_custom_fonts',
			'e-form-submissions',
			'popup_templates',
			'go_knowledge_base_site',
		];

		foreach ( $class->get_all() as $item_slug => $item ) {
			if ( in_array( $item_slug, $to_delete, true ) ) {
				remove_submenu_page( $item->get_parent_slug(), $item_slug );
			}
		}
	}

	/**
	 * Required editor assets.
	 *
	 * @since 2.5.0
	 */
	public function elementor_editor_assets() {
		$suffix = ElementorUtils::is_script_debug() ? '' : '.min';

		wp_enqueue_style(
			'jx-free-elementor-integrate-style',
			jupiterx_core()->plugin_url() . '/includes/extensions/raven/assets/css/elementor-ads' . $suffix . '.css',
			[],
			jupiterx_core()->version()
		);

		$option = Raven::get_modules();

		if ( in_array( 'global-widget', $option, true ) ) {
			return;
		}

		wp_add_inline_style( 'jx-free-elementor-integrate-style', '.elementor-panel-navigation > [data-tab="global"] { display:none !important; }' );
	}

	/**
	 * Preview script.
	 *
	 * @since 2.5.0
	 */
	public function preview_scripts() {
		$suffix = ElementorUtils::is_script_debug() ? '' : '.min';

		wp_enqueue_script(
			'jx-free-elementor-integrate-preview-js',
			jupiterx_core()->plugin_url() . '/includes/extensions/raven/assets/js/elementor-ads' . $suffix . '.js',
			[ 'jquery' ],
			jupiterx_core()->version(),
			true
		);
	}

	/**
	 * Admin assets.
	 *
	 * @since 2.5.0
	 */
	public function admin_assets() {
		$suffix = ElementorUtils::is_script_debug() ? '' : '.min';

		wp_enqueue_script(
			'jx-free-elementor-integrate-admin-js',
			jupiterx_core()->plugin_url() . 'includes/extensions/raven/assets/js/elementor-ads' . $suffix . '.js',
			[ 'jquery' ],
			jupiterx_core()->version(),
			true
		);

		wp_enqueue_style(
			'jx-free-elementor-integrate-style-admin',
			jupiterx_core()->plugin_url() . '/includes/extensions/raven/assets/css/elementor-ads' . $suffix . '.css',
			[],
			jupiterx_core()->version()
		);
	}

	/**
	 * Unset go pro link.
	 *
	 * @param array $links
	 * @since 2.5.0
	 * @return array
	 */
	public function remove_go_pro_from_plugin_page( $links ) {
		unset( $links['go_pro'] );
		return $links;
	}
}
