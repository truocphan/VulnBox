<?php
/**
 * This class handles init of Setup Wizard.
 *
 * @since 1.0.0
 *
 * @package JupiterX\Framework\Admin\Setup_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Init class.
 *
 * @since 1.0.0
 *
 * @package JupiterX\Framework\Admin\Setup_Wizard
 */
final class JupiterX_Setup_Wizard {

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
	 * List of pages.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $pages_list = [];

	/**
	 * List of protected pages.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $protected_pages = [];

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->pages_list = [
			'intro'          => __( 'Hello', 'jupiterx' ),
			'plugins'        => __( 'Plugins', 'jupiterx' ),
			'templates'      => __( 'Template', 'jupiterx' ),
			'completed'      => __( 'All Done!', 'jupiterx' ),
		];

		if ( jupiterx_is_premium() ) {
			$this->pages_list = [
				'intro'          => __( 'Hello', 'jupiterx' ),
				'api-activation' => __( 'Activate', 'jupiterx' ),
				'plugins'        => __( 'Plugins', 'jupiterx' ),
				'templates'      => __( 'Template', 'jupiterx' ),
				'completed'      => __( 'All Done!', 'jupiterx' ),
			];
		}

		$this->protected_pages = [
			'plugins',
			'templates',
			'completed',
		];

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		add_action( 'init', [ $this, 'includes' ] );

		if ( isset( $_GET['page'] ) && 'jupiterx-setup-wizard' === $_GET['page'] ) { // phpcs:ignore
			add_action( 'admin_menu', [ $this, 'admin_menus' ] );
			add_action( 'admin_init', [ $this, 'init' ] );
			add_action( 'jupiterx_print_templates', [ $this, 'render_templates' ] );
			add_filter( 'wp_title', [ $this, 'page_title' ] );
		} elseif ( ! $this->is_notice_hidden() && ! jupiterx_control_panel()->is_control_panel() ) {
			add_action( 'admin_enqueue_scripts', [ $this, 'notice_scripts' ] );
			add_action( 'admin_notices', [ $this, 'admin_notice' ] );
		}
	}

	/**
	 * JupiterX Setup wizard page title
	 *
	 * @since 1.3.0
	 *
	 * @param string $title Page title.
	 * @return string
	 */
	public function page_title( $title ) {
		if ( 'jupiterx-setup-wizard' === jupiterx_get( 'page' ) ) {
			return __( 'Jupiter X &rsaquo; Setup Wizard', 'jupiterx' );
		}

		return $title;
	}

	/**
	 * Register page.
	 *
	 * @since 1.0.0
	 */
	public function admin_menus() {
		add_theme_page( '', '', 'manage_options', 'jupiterx-setup-wizard', '' );
	}

	/**
	 * Initialize page.
	 *
	 * @since 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.ExitExpression)
	 */
	public function init() {
		$this->enqueue_scripts();
		$this->render_header();
		$this->render_main();
		$this->render_footer();
		die();
	}

	/**
	 * Common includes.
	 *
	 * @since 1.0.0
	 */
	public function includes() {
		require_once JUPITERX_ADMIN_PATH . 'setup-wizard/includes/ajax.php';
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.0.0
	 */
	private function enqueue_scripts() {
		wp_enqueue_style( 'jupiterx-templates' );
		wp_enqueue_script( 'jupiterx-templates' );

		wp_register_style( 'jupiterx-admin-icons', JUPITERX_ASSETS_URL . 'dist/css/icons-admin.css', [], JUPITERX_VERSION );
		wp_register_style( 'jupiterx-fontawesome', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css', [], '5.5.0' );
		wp_register_style( 'jupiterx-modal', JUPITERX_ASSETS_URL . 'dist/css/jupiterx-modal' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );
		wp_register_style( 'jupiterx-owl-carousel', JUPITERX_ADMIN_URL . 'setup-wizard/assets/lib/owl-carousel/owl.carousel' . JUPITERX_MIN_CSS . '.css', [], '2.3.4' );
		wp_register_style( 'jupiterx-common', JUPITERX_ASSETS_URL . 'dist/css/common' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );
		wp_enqueue_style( 'jupiterx-setup-wizard', JUPITERX_ASSETS_URL . 'dist/css/setup-wizard' . JUPITERX_MIN_CSS . '.css', [ 'jupiterx-admin-icons', 'jupiterx-fontawesome', 'common', 'jupiterx-modal', 'jupiterx-owl-carousel', 'jupiterx-common' ], JUPITERX_VERSION );

		wp_register_script( 'jupiterx-popper', JUPITERX_ADMIN_URL . 'control-panel/assets/lib/popper/popper' . JUPITERX_MIN_JS . '.js', [ 'jquery' ], JUPITERX_VERSION, true );
		wp_register_script( 'jupiterx-bootstrap', JUPITERX_ADMIN_URL . 'control-panel/assets/lib/bootstrap/bootstrap' . JUPITERX_MIN_JS . '.js', [ 'jquery', 'jupiterx-popper' ], JUPITERX_VERSION, true );
		wp_register_script( 'jupiterx-gsap', JUPITERX_ADMIN_URL . 'control-panel/assets/lib/gsap/gsap' . JUPITERX_MIN_JS . '.js', [], JUPITERX_VERSION, true );
		wp_register_script( 'jupiterx-modal', JUPITERX_ASSETS_URL . 'dist/js/jupiterx-modal' . JUPITERX_MIN_JS . '.js', [ 'jquery', 'jupiterx-gsap' ], JUPITERX_VERSION, true );
		wp_register_script( 'jupiterx-owl-carousel', JUPITERX_ADMIN_URL . 'setup-wizard/assets/lib/owl-carousel/owl.carousel' . JUPITERX_MIN_JS . '.js', [ 'jquery' ], '2.3.4', true );
		wp_register_script( 'jupiterx-common', JUPITERX_ASSETS_URL . 'dist/js/common' . JUPITERX_MIN_JS . '.js', [ 'wp-util' ], JUPITERX_VERSION, true );
		wp_enqueue_script( 'jupiterx-setup-wizard', JUPITERX_ASSETS_URL . 'dist/js/setup-wizard' . JUPITERX_MIN_JS . '.js', [ 'jquery', 'wp-util', 'jupiterx-bootstrap', 'jupiterx-modal', 'imagesloaded', 'jupiterx-owl-carousel', 'jupiterx-common' ], JUPITERX_VERSION, true );

		wp_localize_script(
			'jupiterx-setup-wizard',
			'jupiterxWizardSettings',
			[
				'pagesList'        => array_keys( $this->pages_list ),
				'currentPage'      => $this->get_current_page(),
				'currentPageIndex' => array_search( $this->get_current_page(), array_keys( $this->pages_list ), true ),
				'i18n'             => [
					'confirm'              => __( 'Confirm', 'jupiterx' ),
					'cancel'               => __( 'Cancel', 'jupiterx' ),
					'installTemplateTitle' => __( 'Important Notice', 'jupiterx' ),
					'installTemplate'      => __( 'You are about to install <strong>{template}</strong> template. Installing a new template will remove all current data on your website. Are you sure you want to proceed?', 'jupiterx' ),
					'importMediaTitle'     => __( 'Include Images and Videos?', 'jupiterx' ),
					'importMedia'          => sprintf(
						/* translators: Learn more URL */
						__( 'Would you like to import images and videos as preview? <br> Notice that all images are <strong>strictly copyrighted</strong> and you need to acquire the license in case you want to use them on your project. <a href="%s" target="_blank">Learn More</a>', 'jupiterx' ),
						'https://themes.artbees.net/docs/installing-a-template'
					),
					'mediaConfirm'         => __( 'Do not include', 'jupiterx' ),
					'mediaCancel'          => __( 'Include', 'jupiterx' ),
				],
				'proBadgeUrl' => jupiterx_get_pro_badge_url(),
			]
		);

		if ( jupiterx_is_premium() || function_exists( 'jupiterx_pro' ) ) {
			wp_add_inline_script( 'jupiterx-common', 'var jupiterxPremium = true;', 'before' );
			wp_add_inline_script( 'jupiterx-common', 'var jupiterXControlPanelURL = "' . esc_url( admin_url( 'admin.php?page=jupiterx' ) ) . '";', 'before' );
		}
	}

	/**
	 * Enqueue scripts for notices.
	 *
	 * @since 1.0.0
	 */
	public function notice_scripts() {
		wp_enqueue_script( 'jupiterx-setup-wizard-message-box', JUPITERX_ASSETS_URL . 'dist/js/setup-wizard-message-box' . JUPITERX_MIN_JS . '.js', [ 'jquery' ], JUPITERX_VERSION, true );
	}

	/**
	 * Print admin notice.
	 *
	 * @since 1.0.0
	 */
	public function admin_notice() {
		?>
		<div class="notice notice-info jupiterx-setup-wizard-message-box">
			<p><strong><?php esc_html_e( 'Jupiter X', 'jupiterx' ); ?></strong> &ndash; <?php esc_html_e( 'This wizard helps you configure your new website quick and easy.', 'jupiterx' ); ?></p>
			<p>
				<a href="<?php echo esc_url( $this->get_url() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" class="button button-primary"><?php esc_html_e( 'Run Setup Wizard', 'jupiterx' ); ?></a>
				<button class="button button-secondary discard"><?php esc_html_e( 'Discard', 'jupiterx' ); ?></button>
			</p>
		</div>
		<?php
	}

	/**
	 * Page header.
	 *
	 * @since 1.0.0
	 */
	private function render_header() {
		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width" />
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title><?php wp_title(); ?></title>
			<?php do_action( 'admin_print_styles' ); ?>
			<?php do_action( 'admin_head' ); ?>
		</head>
		<body class="wp-core-ui">
		<?php
	}

	/**
	 * Page main.
	 *
	 * @since 1.0.0
	 */
	private function render_main() {
		?>
		<div class="jupiterx jupiterx-setup-wizard">
			<div class="jupiterx-logo">
				<img src="<?php echo esc_url( JUPITERX_ADMIN_URL . 'assets/images/star-logo.png' ); ?>" width="112" height="105" alt="<?php esc_html_e( 'Jupiter  X', 'jupiterx' ); ?>" />
			</div>
			<div class="jupiterx-nav owl-carousel">
				<?php foreach ( $this->pages_list as $id => $title ) { ?>
					<div class="jupiterx-nav-item">
						<span class="h2"><?php echo esc_html( $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</div>
				<?php } ?>
			</div>
			<div class="jupiterx-content jupiterx-<?php echo esc_attr( $this->get_current_page() ); ?>">
				<?php $this->render_content(); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Page footer.
	 *
	 * @since 1.0.0
	 */
	private function render_footer() {
		?>
			<?php do_action( 'jupiterx_print_templates' ); ?>
			<?php wp_print_scripts(); ?>
		</body>
		</html>
		<?php
	}

	/**
	 * Render JS templates.
	 *
	 * @since 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	public function render_templates() {
		?>
		<script type="text/html" id="tmpl-jupiterx-alert">
			<div class="alert alert-{{{ data.type }}} fade show" role="alert">
				{{{ data.message }}}
			</div>
		</script>
		<script type="text/html" id="tmpl-jupiterx-template">
			<div class="jupiterx-template">
				<div class="jupiterx-card">
					<?php if ( ! jupiterx_is_pro() ) : ?>
						<# if ( data.free_template !== '1' ) { #>
							<img class="jupiterx-pro-badge" src="{{{jupiterxWizardSettings.proBadgeUrl}}}" />
						<# } #>
					<?php endif; ?>
					<figure class="jupiterx-card-img-top">
						<img class="jupiterx-card-img-top" src="{{{ data.img_url }}}" alt="{{{ data.name }}}">
					</figure>
					<div class="jupiterx-card-body">
						<h4 class="jupiterx-card-title">{{{ data.nameClean }}}</h4>
						<?php if ( jupiterx_is_premium() ) { ?>
							<?php if ( jupiterx_is_pro() ) { ?>
								<button class="btn btn-primary mr-1 jupiterx-template-import"><?php esc_html_e( 'Import', 'jupiterx' ); ?></button>
							<?php } else { ?>
								<button class="btn btn-primary mr-1 jupiterx-upgrade-modal-trigger" data-upgrade-link="<?php echo esc_url( jupiterx_upgrade_link( 'templates' ) ); ?>"><?php esc_html_e( 'Import', 'jupiterx' ); ?></button>
							<?php } ?>
						<?php } else { ?>
							<# if ( data.free_template === '1' ) { #>
								<button class="btn btn-primary mr-1 jupiterx-template-import"><?php esc_html_e( 'Import', 'jupiterx' ); ?></button>
							<# } else { #>
								<a class="btn btn-primary mr-2 jupiterx-cp-template-item-btn disabled" href="#"><?php esc_html_e( 'Import', 'jupiterx' ); ?></a>
							<# } #>
						<?php } ?>
						<a class="btn btn-outline-secondary mr-1" target="_blank" href="<?php echo esc_url( 'https://jupiterx.artbees.net/' ); ?>{{{ data.slugClean }}}"><?php esc_html_e( 'Preview', 'jupiterx' ); ?></a>
						<button class="btn btn-outline-primary mr-1 jupiterx-template-psd-link"><?php esc_html_e( '.psd', 'jupiterx' ); ?></button>
					</div>
				</div>
			</div>
		</script>
		<script type="text/html" id="tmpl-jupiterx-spinner">
			<div class="jupiterx-spinner-container">
				<svg class="jupiterx-spinner" width="50px" height="50px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
					<circle class="jupiterx-spinner-path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
				</svg>
			</div>
		</script>
		<?php
	}

	/**
	 * Render page content.
	 *
	 * @since 1.0.0
	 *
	 * @param string $page_id Page id or file name.
	 */
	public function render_content( $page_id = null ) {
		if ( empty( $page_id ) ) {
			$page_id = $this->get_current_page();
		}

		$template = sprintf( '%1$s/setup-wizard/views/%2$s.php', JUPITERX_ADMIN_PATH, $page_id );

		if ( file_exists( $template ) ) {
			require_once $template;
		}
	}

	/**
	 * Get current page.
	 *
	 * @since 1.0.0
	 *
	 * @return string Current page to show.
	 */
	public function get_current_page() {
		$page_id = jupiterx_get_option( 'setup_wizard_current_page' );

		if ( empty( $page_id ) || ! array_key_exists( $page_id, $this->pages_list ) ) {
			reset( $this->pages_list );
			$page_id = key( $this->pages_list );
		}

		$api_key = jupiterx_get_option( 'api_key' );

		return $page_id;
	}

	/**
	 * Get next page.
	 *
	 * @since 1.0.0
	 *
	 * @return string Next page.
	 */
	public function get_next_page() {
		$page_id = $this->get_current_page();

		$pages = array_keys( $this->pages_list );

		// Return first index if nothing is found.
		$index = array_search( $page_id, $pages, true );

		if ( false === $index ) {
			return $pages[0];
		}

		$index++;

		// Use the current param id if index exceeded.
		if ( $index >= count( $pages ) ) {
			return $page_id;
		}

		$api_key = jupiterx_get_option( 'api_key' );

		return $pages[ $index ];
	}

	/**
	 * Get plugins list from Artbees API.
	 *
	 * @since 1.0.0
	 *
	 * @return array Plugins list.
	 */
	public function get_plugins_list() {

		$data = [
			'timeout'     => 10,
			'httpversion' => '1.1',
			'headers'     => [
				'theme-name'   => 'JupiterX',
				'from'         => 0,
				'count'        => 0,
				'list-of-attr' => wp_json_encode( [
					'slug',
					'basename',
					'version',
					'name',
					'desc',
					'img_url',
					'required',
					'pro',
				] ),
			],
		];

		$post = wp_remote_get( 'https://artbees.net/api/v2/tools/plugin-custom-list', $data );

		$response = json_decode( wp_remote_retrieve_body( $post ) );

		return $response->data;
	}

	/**
	 * Get templates categories from Artbees API.
	 *
	 * @since 1.0.0
	 *
	 * @return array Templates categories.
	 */
	public function get_templates_categories() {
		$api_key = jupiterx_get_option( 'api_key' );

		if ( empty( $api_key ) ) {
			return [];
		}

		$data = [
			'timeout'     => 10,
			'httpversion' => '1.1',
			'headers'     => [
				'apikey' => $api_key,
				'domain' => wp_unslash( $_SERVER['SERVER_NAME'] ), // phpcs:ignore
			],
		];

		$post = wp_remote_get( 'https://artbees.net/api/v2/theme/template-categories', $data );

		$response = json_decode( wp_remote_retrieve_body( $post ) );

		return $response->data;
	}

	/**
	 * Get setup wizard page url.
	 *
	 * @since 1.0.0
	 *
	 * @return string Page url.
	 */
	public function get_url() {
		return esc_url( admin_url( 'themes.php?page=jupiterx-setup-wizard' ) );
	}

	/**
	 * Check notice status.
	 *
	 * @return boolean Notice status.
	 */
	public function is_notice_hidden() {
		if ( ! current_user_can( 'manage_options' ) || ! empty( jupiterx_get_option( 'template_installed_id' ) ) ) {
			return true;
		}

		$hide_notice = jupiterx_get_option( 'setup_wizard_hide_notice', false );

		return (bool) $hide_notice;
	}
}

/**
 * Create single instance and globalize.
 *
 * @since 1.0.0
 *
 * @return JupiterX_Setup_Wizard
 */
function jupiterx_setup_wizard() {
	return JupiterX_Setup_Wizard::get_instance();
}

jupiterx_setup_wizard();
