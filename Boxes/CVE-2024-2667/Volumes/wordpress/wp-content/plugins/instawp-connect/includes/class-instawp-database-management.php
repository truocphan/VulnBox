<?php

use InstaWP\Connect\Helpers\DatabaseManager;

if ( ! defined( 'INSTAWP_PLUGIN_DIR' ) ) {
	die;
}

if ( ! class_exists( 'InstaWP_Database_Management' ) ) {
	class InstaWP_Database_Management {

		protected static $_instance = null;
		private $database_manager;

		/**
		 * @return InstaWP_Database_Management
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function __construct() {
			$this->database_manager = new \InstaWP\Connect\Helpers\DatabaseManager();

			add_action( 'init', array( $this, 'add_endpoint' ) );
			add_action( 'template_redirect', array( $this, 'redirect' ) );
			add_action( DatabaseManager::$action, array( $this, 'clean' ) );
			add_action( 'admin_post_instawp-database-manager-auto-login', array( $this, 'auto_login' ) );
			add_action( 'admin_post_nopriv_instawp-database-manager-auto-login', array( $this, 'auto_login' ) );
			add_action( 'update_option_instawp_rm_database_manager', array( $this, 'clean' ) );
			add_filter( 'query_vars', array( $this, 'query_vars' ), 99 );
			add_filter( 'template_include', array( $this, 'load_template' ), 999 );
		}

		public function add_endpoint() {
			add_rewrite_endpoint( DatabaseManager::$query_var, EP_ROOT | EP_PAGES );
		}

		public function redirect() {
			$template_name = get_query_var( DatabaseManager::$query_var, false );
			if ( $template_name && ! $this->get_template() ) {
				wp_safe_redirect( home_url() );
				exit();
			}
		}

		public function clean() {
			$this->database_manager->clean();
		}

		public function auto_login() {
			$template = ! empty( $_GET['template'] ) ? sanitize_text_field( wp_unslash( $_GET['template'] ) ) : '';
			if ( ! $template ) {
				wp_die( esc_html__( 'Database Manager file not found!', 'instawp-connect' ) );
			}

			$token = get_transient( 'instawp_database_manager_login_token' );
			if ( empty( $_GET['token'] ) || ! $token ) {
				wp_die( esc_html__( 'Auto Login token expired or missing!', 'instawp-connect' ) );
			}

			if ( ! hash_equals( sanitize_text_field( wp_unslash( $_GET['token'] ) ), hash( 'sha256', $token ) ) ) {
				wp_die( esc_html__( 'InstaWP Database Manager: Token mismatch or not valid!', 'instawp-connect' ) );
			}

			$database_manager_url = DatabaseManager::get_database_manager_url( base64_decode( $template ) );
			ob_start() ?>

            <form id="instawp-auto-login" action="<?php echo esc_url( $database_manager_url ); ?>" method="POST">
                <input type="hidden" name="auth[driver]" required="required" value="server">
                <!--			<input type="hidden" name="auth[server]" required="required" value="--><?php //echo esc_attr( DB_HOST ); ?><!--">-->
                <input type="hidden" name="auth[username]" required="required" value="<?php echo esc_attr( DB_USER ); ?>">
                <input type="hidden" name="auth[password]" required="required" value="<?php echo esc_attr( DB_PASSWORD ); ?>">
                <input type="hidden" name="auth[db]" required="required" value="<?php echo esc_attr( DB_NAME ); ?>">
                <input type="hidden" name="auth[permanent]" required="required" value="1">
            </form>
            <script type="text/javascript">
                window.onload = function () {
                    setTimeout(function () {
                        document.getElementById('instawp-auto-login').submit();
                    }, 3000);
                }
            </script>

			<?php
			$fields = ob_get_clean();
			InstaWP_Tools::auto_login_page( $fields, $database_manager_url, __( 'InstaWP Database Manager', 'instawp-connect' ) );
		}

		public function query_vars( $query_vars ) {
			if ( ! in_array( DatabaseManager::$query_var, $query_vars, true ) ) {
				$query_vars[] = DatabaseManager::$query_var;
			}

			return $query_vars;
		}

		public function load_template( $template ) {
			return $this->get_template( $template );
		}

		private function get_template( $template = false ) {
			$template_name = get_query_var( DatabaseManager::$query_var );
			$template_path = DatabaseManager::get_file_path( $template_name );
			$loader_path   = INSTAWP_PLUGIN_DIR . '/includes/database-manager/loader.php';

			if ( file_exists( $template_path ) && file_exists( $loader_path ) ) {
				$template = $loader_path;
			}

			return $template;
		}
	}
}

InstaWP_Database_Management::instance();