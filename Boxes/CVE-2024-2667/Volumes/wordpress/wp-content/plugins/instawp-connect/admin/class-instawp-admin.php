<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://instawp.com/
 * @since      1.0
 *
 * @package    instaWP
 * @subpackage instaWP/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    instaWP
 * @subpackage instaWP/admin
 * @author     instawp team
 */

if ( ! defined( 'INSTAWP_PLUGIN_DIR' ) ) {
	die;
}

class InstaWP_Admin {

	private $plugin_name;

	private $version;

	protected static $_is_waas_mode = false;

	protected static $_is_template_migration_mode = false;

	protected static $_assets_version = null;

	public function __construct( $plugin_name, $version ) {

		if ( defined( 'INSTAWP_CONNECT_MODE' ) && 'TEMPLATE_MIGRATE' == INSTAWP_CONNECT_MODE ) {
			self::$_is_template_migration_mode = true;
		}

		if ( defined( 'INSTAWP_CONNECT_MODE' ) && 'WAAS_GO_LIVE' == INSTAWP_CONNECT_MODE ) {
			self::$_is_waas_mode = true;
		}

		self::$_assets_version = defined( 'INSTAWP_DEBUG_LOG' ) && INSTAWP_DEBUG_LOG ? current_time( 'U' ) : INSTAWP_PLUGIN_VERSION;

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_action( 'admin_menu', array( $this, 'add_migrate_plugin_menu_items' ) );
		add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_button' ), 100 );

		if ( defined( 'INSTAWP_CONNECT_MODE' ) && in_array( INSTAWP_CONNECT_MODE, array( 'WAAS_GO_LIVE', 'TEMPLATE_MIGRATE' ) ) ) {
			add_filter( 'all_plugins', array( $this, 'handle_instawp_plugin_display' ) );
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function add_action_links( $links ) {

		$action_links = array(
			sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'tools.php?page=instawp' ) ), esc_html__( 'Create Staging', 'instawp-connect' ) ),
		);

		return array_merge( $action_links, $links );
	}

	function add_admin_bar_button( WP_Admin_Bar $admin_bar ) {

		if ( self::$_is_template_migration_mode ) {
			$admin_bar->add_menu(
				array(
					'id'     => 'instawp-template-migrate',
					'title'  => esc_html__( 'Migrate', 'instawp-connect' ),
					'href'   => admin_url( 'admin.php?page=instawp-template-migrate' ),
					'parent' => 'top-secondary',
				)
			);

			return;
		}

		if ( self::$_is_waas_mode ) {
			$admin_bar->add_menu(
				array(
					'id'     => 'instawp-go-live',
					'title'  => esc_html__( 'Go Live', 'instawp-connect' ),
					'href'   => defined( 'INSTAWP_CONNECT_WAAS_URL' ) && ! empty( INSTAWP_CONNECT_WAAS_URL ) ? INSTAWP_CONNECT_WAAS_URL : '#',
					'meta'   => array(
						'target' => '_blank',
					),
					'parent' => 'top-secondary',
				)
			);

			return;
		}
	}

	function handle_instawp_plugin_display( $plugins ) {

		if ( in_array( INSTAWP_PLUGIN_NAME, array_keys( $plugins ) ) ) {
			unset( $plugins[ INSTAWP_PLUGIN_NAME ] );
		}

		return $plugins;
	}

	function add_migrate_plugin_menu_items() {

		// Hosting migrate mode
		if ( self::$_is_template_migration_mode ) {
			add_menu_page(
				esc_html__( 'InstaWP - Migrate', 'instawp-connect' ),
				esc_html__( 'InstaWP - Migrate', 'instawp-connect' ),
				'administrator', 'instawp-template-migrate', array( $this, 'render_template_migrate_page' ), 2
			);
			remove_menu_page( 'instawp-template-migrate' );

			return;
		}

		// Go Live mode
		if ( self::$_is_waas_mode ) {
			return;
		}

		add_management_page(
			esc_html__( 'InstaWP', 'instawp-connect' ),
			esc_html__( 'InstaWP', 'instawp-connect' ),
			InstaWP_Setting::get_allowed_role(), 'instawp', array( $this, 'render_migrate_page' ), 1
		);
	}

	function render_template_migrate_page() {
		include INSTAWP_PLUGIN_DIR . '/migrate/templates/main-migrate.php';
	}

	function render_migrate_page() {
		include INSTAWP_PLUGIN_DIR . '/migrate/templates/main.php';
	}

	public function enqueue_scripts() {

		if ( isset( $_GET['page'] ) && in_array( sanitize_text_field( $_GET['page'] ), array( 'instawp', 'instawp-template-migrate' ) ) ) {

			wp_enqueue_style( 'instawp-hint', instaWP::get_asset_url( 'assets/css/hint.min.css' ) );
			wp_enqueue_style( 'instawp-tailwind', instaWP::get_asset_url( 'assets/css/tailwind.min.css' ), array(), self::$_assets_version );
			wp_enqueue_style( 'instawp-select2', instaWP::get_asset_url( 'admin/css/select2.min.css' ) );
			wp_enqueue_script( 'instawp-select2', instaWP::get_asset_url( 'admin/js/select2.min.js' ) );

			wp_enqueue_style( 'instawp-change-event', instaWP::get_asset_url( 'admin/css/instawp-change-event.css' ), array(), self::$_assets_version );
			wp_enqueue_script( 'instawp-change-event', instaWP::get_asset_url( 'admin/js/instawp-change-event.js' ), array( 'jquery' ), self::$_assets_version );
			wp_localize_script( 'instawp-change-event', 'instawp_tws', InstaWP_Tools::get_localize_data() );

			wp_enqueue_style( 'instawp-migrate', instaWP::get_asset_url( 'migrate/assets/css/style.css' ), array(), self::$_assets_version );
			wp_enqueue_script( 'instawp-migrate', instaWP::get_asset_url( 'assets/js/scripts.js' ), array(), self::$_assets_version );
			wp_localize_script( 'instawp-migrate', 'instawp_migrate', InstaWP_Tools::get_localize_data() );
		}

		wp_enqueue_style( 'instawp-common', instaWP::get_asset_url( 'assets/css/common.min.css' ) );
		wp_enqueue_script( 'instawp-common', instaWP::get_asset_url( 'assets/js/common.js' ), array(), self::$_assets_version );
		wp_localize_script( 'instawp-common', 'instawp_common', InstaWP_Tools::get_localize_data() );
	}
}