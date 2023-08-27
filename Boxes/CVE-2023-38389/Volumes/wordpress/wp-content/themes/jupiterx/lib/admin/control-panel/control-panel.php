<?php
/**
 * This class handles init of Control Panel.
 *
 * @since 1.0.0
 *
 * @package JupiterX\Framework\Admin\Control_Panel
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load old control panel based on Jupiter X Core version and the constant.
$jupiterx_core_version = '';

if ( function_exists( 'jupiterx_core' ) ) {
	$jupiterx_core_version = jupiterx_core()->version();
}

if ( version_compare( $jupiterx_core_version, '1.18.0', '>=' ) && ! defined( 'JUPITERX_OLD_CONTROL_PANEL' ) ) {
	return;
}

/**
 * Init class.
 *
 * @since 1.0.0
 *
 * @package JupiterX\Framework\Admin\Control_Panel
 */
final class JupiterX_Control_Panel {

	/**
	 * The single instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @var JupiterX_Control_Panel
	 */
	protected static $instance = null;

	/**
	 * Returns JupiterX_Control_Panel instance.
	 *
	 * @since 1.0.0
	 *
	 * @return JupiterX_Control_Panel
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->define_constants();

		if ( $this->is_control_panel() || wp_doing_ajax() ) {
			$this->init();
		}
	}

	/**
	 * Define constants.
	 *
	 * @since 1.0.0
	 */
	public function define_constants() {
		$this->define( 'JUPITERX_CONTROL_PANEL_HOME', true );
		$this->define( 'JUPITERX_CONTROL_PANEL_PLUGINS', true );
		$this->define( 'JUPITERX_CONTROL_PANEL_TEMPLATES', true );
		$this->define( 'JUPITERX_CONTROL_PANEL_IMAGE_SIZES', true );
		$this->define( 'JUPITERX_CONTROL_PANEL_SYSTEM_STATUS', true );
		$this->define( 'JUPITERX_CONTROL_PANEL_UPDATES', true );
		$this->define( 'JUPITERX_CONTROL_PANEL_SETTINGS', true );
		$this->define( 'JUPITERX_CONTROL_PANEL_SUPPORT', false );
		define( 'JUPITERX_CONTROL_PANEL_API_V1_URL', 'https://artbees.net/api/v1/' );
		define( 'JUPITERX_CONTROL_PANEL_API_V2_URL', 'https://artbees.net/api/v2/' );
		define( 'JUPITERX_CONTROL_PANEL_PATH', JUPITERX_ADMIN_PATH . 'control-panel/' );
		define( 'JUPITERX_CONTROL_PANEL_URL', JUPITERX_ADMIN_URL . 'control-panel/' );
		define( 'JUPITERX_CONTROL_PANEL_ASSETS_PATH', JUPITERX_CONTROL_PANEL_PATH . 'assets/' );
		define( 'JUPITERX_CONTROL_PANEL_ASSETS_URL', JUPITERX_CONTROL_PANEL_URL . 'assets/' );
	}

	/**
	 * Safely define a constant.
	 *
	 * @since 1.0.4
	 *
	 * @param string $name Constant name.
	 * @param mixed $value Define value.
	 */
	public function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Init control panel.
	 *
	 * Only init the control panel when the visiting page is control panel or currently doing ajax.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		require_once JUPITERX_CONTROL_PANEL_PATH . 'includes/js-messages.php';
		require_once JUPITERX_CONTROL_PANEL_PATH . 'includes/class-activate-theme.php';
		require_once JUPITERX_CONTROL_PANEL_PATH . 'includes/class-downgrade-theme.php';

		/**
		 * Hook for control panel init.
		 *
		 * @since 1.3.0
		 */
		do_action( 'jupiterx_control_panel_init' );

		add_filter( 'getimagesize_mimes_to_exts', [ $this, 'mime_to_ext' ] );
		add_action( 'wp_ajax_jupiterx_cp_load_pane_action', [ $this, 'load_control_panel_pane' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
	}

	/**
	 * Check if its control panel is currently viewing page.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean Test currently viewing page.
	 */
	public function is_control_panel() {
		return (boolean) isset( $_GET['page'] ) && $_GET['page'] === JUPITERX_SLUG;
	}

	/**
	 * Load control panel styles and scripts.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_assets() {
		wp_enqueue_style( 'jupiterx-templates' );
		wp_enqueue_script( 'jupiterx-templates' );

		// Enqueue styles.
		wp_enqueue_style( 'jupiterx-help-links', JUPITERX_ASSETS_URL . 'dist/css/help-links' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );
		wp_enqueue_style( 'jupiterx-modal', JUPITERX_ASSETS_URL . 'dist/css/jupiterx-modal' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );
		wp_enqueue_style( 'jupiterx-control-panel', JUPITERX_ASSETS_URL . 'dist/css/control-panel' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );
		wp_enqueue_media();

		// Enqueue scripts.
		wp_enqueue_script( 'jupiterx-help-links', JUPITERX_ASSETS_URL . 'dist/js/help-links' . JUPITERX_MIN_JS . '.js', [], JUPITERX_VERSION, true );
		wp_enqueue_script( 'jupiterx-popper', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'lib/popper/popper' . JUPITERX_MIN_JS . '.js', [], '1.14.3', true );
		wp_enqueue_script( 'jupiterx-bootstrap', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'lib/bootstrap/bootstrap' . JUPITERX_MIN_JS . '.js', [], '4.1.2', true );
		wp_enqueue_script( 'jupiterx-gsap', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'lib/gsap/gsap' . JUPITERX_MIN_JS . '.js', [], '1.19.1', true );
		wp_enqueue_script( 'jupiterx-dynamicmaxheight', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'lib/dynamicmaxheight/dynamicmaxheight' . JUPITERX_MIN_JS . '.js', [], '0.0.3', true );
		wp_enqueue_script( 'jupiterx-modal', JUPITERX_ASSETS_URL . 'dist/js/jupiterx-modal' . JUPITERX_MIN_JS . '.js', [], JUPITERX_VERSION, true );
		wp_enqueue_script( 'jupiterx-control-panel', JUPITERX_ASSETS_URL . 'dist/js/control-panel' . JUPITERX_MIN_JS . '.js', [ 'jquery', 'wp-util', 'updates' ], JUPITERX_VERSION, true );

		// Localize scripts.
		$sections = $this->get_sections();

		// Get initial section.
		$initial_section = reset( $sections );

		if ( $initial_section ) {
			$initial_section = $initial_section['href'];
		}

		wp_localize_script( 'jupiterx-control-panel', 'jupiterx_cp_textdomain', jupiterx_adminpanel_textdomain() );
		wp_localize_script(
			'jupiterx-control-panel',
			'jupiterxControlPanel',
			[
				'initialSection'     => $initial_section,
				'nonce'              => wp_create_nonce( 'jupiterx_control_panel' ),
				'proBadgeUrl'        => jupiterx_get_pro_badge_url(),
				'isPro'              => jupiterx_is_pro(),
				'isPremium'          => jupiterx_is_premium(),
				'jupiterxCoreActive' => function_exists( 'jupiterx_core' ),
			]
		);
	}

	/**
	 * Map the "image/vnd.microsoft.icon" MIME type to the ico file extension, instead of
	 * modifying the expected MIME types of WordPress in the WordPress wp_get_mime_types()
	 * function.
	 *
	 * This is work-around for a bug in WordPress when the PHP version returns MIME
	 * type of "image/vnd.microsoft.icon" instead of "image/x-icon"
	 * that WordPress expects.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of image mime types and their matching extensions.
	 */
	public function mime_to_ext( $mimes_to_text ) {
		$mimes_to_text['image/vnd.microsoft.icon'] = 'ico';
		return $mimes_to_text;
	}

	/**
	 * Load the pane by the slug name.
	 *
	 * This function is called via admin-ajax.php.
	 *
	 * @since 1.0.0
	 */
	public function load_control_panel_pane() {
		if ( ! current_user_can( 'edit_others_posts' ) || ! current_user_can( 'edit_others_pages' ) ) {
			wp_send_json_error( 'You do not have access to this section', 'jupiterx' );
		}

		$slug = esc_attr( $_POST['slug'] );
		ob_start();
		$this->print_pane( $slug );
		$pane_html = ob_get_clean();
		wp_send_json_success( $pane_html );
		wp_die();
	}

	/**
	 * Print pane HTML by slug.
	 *
	 * @since 1.11.0
	 *
	 * @param string $slug Pane ID.
	 */
	public function print_pane( $slug = '' ) {
		$sections = $this->get_sections();

		$default = reset( $sections );

		if ( empty( $slug ) ) {
			$slug = $default['href'];
		}

		$file = JUPITERX_CONTROL_PANEL_PATH . "/views/panes/{$slug}.php";

		// String pattern replace.
		$slug = str_replace( '-', '_', $slug );

		// Get file location.
		$file = sanitize_file_name( apply_filters( "jupiterx_control_panel_pane_{$slug}", $file ) );

		if ( file_exists( $file ) ) {
			include_once $file;
			return;
		}
	}

	/**
	 * Get registered sections.
	 *
	 * @since 1.11.0
	 *
	 * @return array Registered sections.
	 */
	public function get_sections() {
		$sections = [
			'home' => [
				'title'     => __( 'Home' , 'jupiterx' ),
				'href'      => 'home',
				'condition' => defined( 'JUPITERX_CONTROL_PANEL_HOME' ) && JUPITERX_CONTROL_PANEL_HOME,
				'order'     => 10,
			],
			'plugins' => [
				'title'     => __( 'Plugins' , 'jupiterx' ),
				'href'      => 'install-plugins',
				'condition' => defined( 'JUPITERX_CONTROL_PANEL_PLUGINS' ) && JUPITERX_CONTROL_PANEL_PLUGINS,
				'order'     => 20,
			],
			'templates' => [
				'title'     => __( 'Templates' , 'jupiterx' ),
				'href'      => 'install-templates',
				'condition' => defined( 'JUPITERX_CONTROL_PANEL_TEMPLATES' ) && JUPITERX_CONTROL_PANEL_TEMPLATES,
				'order'     => 30,
			],
			'free_vs_pro' => [
				'title'     => __( 'Free vs Pro' , 'jupiterx' ),
				'href'      => 'free-vs-pro',
				'condition' => ! jupiterx_is_pro() && ! jupiterx_is_premium(),
				'order'     => 80,
			],
			'updates' => [
				'title'     => __( 'Updates' , 'jupiterx' ),
				'href'      => 'update-theme',
				'condition' => defined( 'JUPITERX_CONTROL_PANEL_UPDATES' ) && JUPITERX_CONTROL_PANEL_UPDATES,
				'order'     => 60,
			],
			'support' => [
				'title'     => __( 'Support' , 'jupiterx' ),
				'href'      => 'support',
				'condition' => defined( 'JUPITERX_CONTROL_PANEL_SUPPORT' ) && JUPITERX_CONTROL_PANEL_SUPPORT,
				'order'     => 90,
			],
		];

		$sections = apply_filters( 'jupiterx_control_panel_sections', $sections );

		// Sort based on orders.
		uasort( $sections, function( $first, $second ):int {
			return $first['order'] > $second['order'];
		} );

		return $sections;
	}
}

/**
 * Create single instance and globalize.
 *
 * @since 1.0.0
 *
 * @return JupiterX_Control_Panel
 */
function jupiterx_control_panel() {
	return JupiterX_Control_Panel::get_instance();
}

// Initialize control panel.
jupiterx_control_panel();
