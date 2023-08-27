<?php
/**
 * This class is responsible to managing all JupiterX plugins.
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 *
 * @package JupiterX_Core\Control_Panel_2
 */

class JupiterX_Core_Control_Panel_Install_Plugins {

	/**
	 * TGMPA Instance
	 *
	 * @var object
	 */
	protected $tgmpa;

	/**
	 * Artbees API.
	 *
	 * @var string
	 */
	protected $api_url = 'http://artbees.net/api/v2/';

	/**
	 * Class constructor.
	 *
	 * @since 1.18.0
	 */
	public function __construct() {
		if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
			return;
		}

		$menu_items_access = get_site_option( 'menu_items' );
		if ( is_multisite() && ! isset( $menu_items_access['plugins'] ) && ! current_user_can( 'manage_network_plugins' ) ) {
			return;
		}

		$this->tgmpa = isset( $GLOBALS['tgmpa'] ) ? $GLOBALS['tgmpa'] : TGM_Plugin_Activation::get_instance();

		add_action( 'wp_ajax_jupiterx_core_cp_get_plugins', [ $this, 'get_plugins_for_frontend' ] );

		add_action( 'wp_ajax_jupiterx_core_is_required_plugins_installed', [ $this, 'is_required_plugins_installed' ] );
	}

	/**
	 * Send a json list of plugins and their data and activation limit status for front-end usage.
	 *
	 * @since 1.18.0
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function get_plugins_for_frontend() {
		check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have access to this section.', 'jupiterx-core' );
		}

		$plugins = jupiterx_core_get_plugins_from_api();

		if ( is_wp_error( $plugins ) ) {
			wp_send_json_error( [ 'error' => $plugins->get_error_message() ] );
		}

		if ( isset( $plugins['raven'] ) ) {
			unset( $plugins['raven'] );
		}

		$bundled_plugins = [];

		if ( version_compare( JUPITERX_VERSION, '2.0.0', '>=' ) ) {
			$bundled_plugins['sellkit-pro'] = [
				'id' => 99999,
				'theme_name' => 'jupiterx',
				'name' => 'Sellkit Pro',
				'headline' => 'Funnel Builder and Checkout Optimizer for WooCommerce to sell more faster',
				'large_thumbnail' => trailingslashit( jupiterx_core()->plugin_assets_url() ) . 'images/control-panel/sellkit.png',
				'slug' => 'sellkit-pro',
				'is_callable' => 'Sellkit Pro',
				'file_path' => 'sellkit-pro/sellkit-pro.php',
				'basename' => 'sellkit-pro/sellkit-pro.php',
				'video' => '',
				'installed' => false,
				'active' => false,
				'network_active' => false,
				'install_disabled' => false,
				'label_type' => 'Optional',
				'is_pro' => true,
				'pro' => true,
				'required' => false,
				'recommended' => false,
				'version' => '',
				'desc' => '<ul><li>Advanced filters & variation swatches</li><li>Express checkout features</li><li>Smart sales funnels, bumps & upsells</li><li>Smart discounts & coupons</li><li>Checkout notices (FOMO,BOGO,...)</li></ul>',
				'source' => '',
				'install_url' => '',
				'activate_url' => '',
				'update_url' => '',
				'wp_activate_url' => jupiterx_core_get_wp_action_url( 'sellkit-pro/sellkit-pro.php', 'activate' ),
			];

			$bundled_plugins['sellkit'] = [
				'id' => 99998,
				'theme_name' => 'jupiterx',
				'name' => 'Sellkit',
				'slug' => 'sellkit',
				'is_callable' => 'Sellkit',
				'file_path' => 'sellkit/sellkit.php',
				'basename' => 'sellkit/sellkit.php',
				'video' => '',
				'installed' => false,
				'active' => false,
				'network_active' => false,
				'install_disabled' => false,
				'label_type' => 'Optional',
				'is_pro' => true,
				'pro' => true,
				'required' => false,
				'recommended' => false,
				'version' => '',
				'source' => 'wp-repo',
				'install_url' => '',
				'activate_url' => '',
				'update_url' => '',
				'wp_activate_url' => jupiterx_core_get_wp_action_url( 'sellkit/sellkit.php', 'activate' ),
			];

			$bundled_plugins['leadin'] = [
				'id' => 99996,
				'theme_name' => 'jupiterx',
				'name' => 'leadin',
				'headline' => 'Tools for marketing, sales & customer service',
				'slug' => 'leadin',
				'is_callable' => 'Leadin',
				'file_path' => 'leadin/leadin.php',
				'basename' => 'leadin/leadin.php',
				'video' => '',
				'installed' => false,
				'active' => false,
				'network_active' => false,
				'install_disabled' => false,
				'label_type' => 'Optional',
				'is_pro' => true,
				'pro' => true,
				'required' => false,
				'recommended' => false,
				'version' => '',
				'desc' => 'Capture, organize and egnage web visitors with free forms, live chat, CRM, email marketing, and analytics. Easy to use and no coding necessary. Built natively into WordPress.',
				'source' => 'wp-repo',
				'install_url' => '',
				'activate_url' => '',
				'update_url' => '',
				'wp_activate_url' => jupiterx_core_get_wp_action_url( 'leadin/leadin.php', 'activate' ),
			];

			$bundled_plugins['depicter'] = [
				'id' => 99993,
				'theme_name' => 'jupiterx',
				'name' => 'depicter',
				'headline' => 'Build amazing sliders with the help of Al',
				'slug' => 'depicter',
				'is_callable' => '',
				'file_path' => '',
				'basename' => '',
				'video' => '',
				'installed' => false,
				'active' => false,
				'network_active' => false,
				'install_disabled' => false,
				'label_type' => 'Optional',
				'is_pro' => true,
				'pro' => true,
				'required' => false,
				'recommended' => false,
				'version' => '',
				'desc' => 'Use the power of Al technology to create sliders for your website. Simply import one of 200+ interactive templates and customize them to make it yours, or use a powerful editor to create anything you want with ease.',
				'source' => 'wp-repo',
				'install_url' => '',
				'activate_url' => '',
				'update_url' => '',
				'wp_activate_url' => '',
			];
		}

		$plugins = array_merge( $bundled_plugins, $plugins );
		$plugins = jupiterx_core_update_plugins_status( $plugins );

		foreach ( $plugins as $plugin ) {
			switch ( $plugin['slug'] ) {
				case 'marketing-automation-and-personalization':
					$plugins['marketing-automation-and-personalization']['desc']     = '<ul><li>Cart recovery & retention emails</li><li>Upsell, cross-sell & newsletter emails</li><li>Lead-gen and exit popups</li><li>Personalized pages based on behavior & history</li><li>Alt to Klaviyo, ActiveCampaign, MailPoet</li></ul>';
					$plugins['marketing-automation-and-personalization']['headline'] = 'Grow your website with omni-channel marketing automation across web, popups & emails';
					break;
				case 'wunderwp':
					$plugins['wunderwp']['desc']     = '<ul><li>Apply preset styling to Elementor widgets</li><li>Beautify content with premade styles</li><li>Quickly build pages with premade templates</li></ul>';
					$plugins['wunderwp']['headline'] = 'Build Elementor pages quickly with reusable styles and templates for Elementor';
					break;
				case 'jet-elements':
					$plugins['jet-elements']['headline'] = 'Adds more widgets to Elementor page builder';
					break;
				case 'jet-popup':
					$plugins['jet-popup']['headline'] = 'Build popups for your Elementor website';
					break;
				case 'jet-smart-filters':
					$plugins['jet-smart-filters']['headline'] = 'Build extra filters for different post types';
					break;
				case 'jet-engine':
					$plugins['jet-engine']['headline'] = 'Build dynamic content in elementor';
					break;
				case 'jet-blog':
					$plugins['jet-blog']['headline'] = 'Create custom blog pages in Elementor';
					break;
				case 'jet-tabs':
					$plugins['jet-tabs']['headline'] = 'Tabs, toggels and accordion blocks for Elementor';
					break;
				case 'jet-woo-builder':
					$plugins['jet-woo-builder']['headline'] = 'Build custom products pages with Elementor';
					break;
				case 'jet-menu':
					$plugins['jet-menu']['headline'] = 'Build Mega Menu in Elementor';
					break;
				case 'jet-tricks':
					$plugins['jet-tricks']['headline'] = 'Visual effects in Elementor';
					break;
			}

			if ( empty( $plugin['file_path'] ) ) {
				$plugin['file_path'] = $plugin['basename'];
			}
		}

		return wp_send_json( [
			'plugins' => $plugins,
			'bulk_actions' => $this->get_plugin_bulk_actions( $plugins ),
		] );
	}

	/**
	 * Get Plugin Bulk Actions.
	 *
	 * @since 1.18.0
	 *
	 * @param array $plugins Plugins list.
	 *
	 * @return array
	 */
	public function get_plugin_bulk_actions( $plugins ) {
		return [
			'activate_required_plugins' => [
				'url' => admin_url( 'plugins.php' ),
				'action' => 'activate-selected',
				'action2' => -1,
				'_wpnonce' => wp_create_nonce( 'bulk-plugins' ),
				'checked' => $this->get_required_plugins_slug( $plugins, 'basename' ),
			],
			'install_required_plugins' => [
				'url' => admin_url( 'themes.php?page=tgmpa-install-plugins' ),
				'action' => 'tgmpa-bulk-install',
				'action2' => -1,
				'_wpnonce' => wp_create_nonce( 'bulk-plugins' ),
				'tgmpa-page' => 'tgmpa-install-plugins',
				'plugin' => $this->get_required_plugins_slug( $plugins, 'slug' ),
			],
		];
	}

	/**
	 * Get plugin slugs for bulk action.
	 *
	 * @since 1.18.0
	 *
	 * @param array $plugins Plugins list.
	 * @param string $field Plugin slug or basename.
	 *
	 * @return array
	 */
	private function get_required_plugins_slug( $plugins, $field ) {
		$slugs = [];

		if ( ! is_array( $plugins ) ) {
			return $slugs;
		}

		foreach ( $plugins as $plugin ) {
			if ( 'true' === $plugin['required'] ) {
				$slugs[] = $plugin[ $field ];
			}
		}

		return $slugs;
	}

	/**
	 * Check if required plugins is installed or not.
	 * Is used for dashboard welcome box , quick check links.
	 *
	 * @since 2.0.0
	 */
	public function is_required_plugins_installed() {
		check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have access to this section.', 'jupiterx-core' );
		}

		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			wp_send_json_error();
		}

		if ( ! class_exists( 'ACF' ) ) {
			wp_send_json_error();
		}

		wp_send_json_success();
	}
}

new JupiterX_Core_Control_Panel_Install_Plugins();
