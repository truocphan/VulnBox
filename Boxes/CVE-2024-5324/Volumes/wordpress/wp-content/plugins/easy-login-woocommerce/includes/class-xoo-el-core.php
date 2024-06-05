<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Xoo_El_Core{

	private static $_instance = null;

	public $aff, $db_version;
	public $updatedFrom = false;

	public static function get_instance(){

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	public function __construct(){

		$this->db_version = get_option('xoo-el-version');

		if( defined( 'XOO_ML_VERSION' ) && version_compare( XOO_ML_VERSION , '1.3', '<=' ) ){
			add_action( 'admin_notices', array( $this, 'otp_login_update_notice' ) );
			return;
		}
		$this->define_constants();
		$this->includes();
		$this->hooks();
	}


	public function define_constants(){
		define( "XOO_EL_PATH", plugin_dir_path( XOO_EL_PLUGIN_FILE ) ); // Plugin path
		define( "XOO_EL_URL", untrailingslashit( plugins_url( '/', XOO_EL_PLUGIN_FILE ) ) ); // plugin url
		define( "XOO_EL_PLUGIN_BASENAME", plugin_basename( XOO_EL_PLUGIN_FILE ) );
		define( "XOO_EL_VERSION", "2.7.2" ); //Plugin version

	}


	public function includes(){

		//xootix framework
		require_once XOO_EL_PATH.'/includes/xoo-framework/xoo-framework.php';
		require_once XOO_EL_PATH.'/includes/class-xoo-el-helper.php';

		//Field framework
		require_once XOO_EL_PATH.'/xoo-form-fields-fw/xoo-aff.php';

		$this->aff = xoo_aff_fire( 'easy-login-woocommerce', 'xoo-el-fields' ); // start framework

		require_once XOO_EL_PATH.'includes/class-xoo-el-func.php';
		
		require_once XOO_EL_PATH.'includes/xoo-el-functions.php';

		require_once XOO_EL_PATH.'includes/class-xoo-el-fields.php';

		if($this->is_request('frontend')){

			require_once XOO_EL_PATH.'includes/class-xoo-el-frontend.php';
			require_once XOO_EL_PATH.'includes/class-xoo-el-form-handler.php';

		}

		if( $this->is_request('admin') || version_compare( $this->db_version, XOO_EL_VERSION, '<' ) ){
			require_once XOO_EL_PATH.'admin/class-xoo-el-aff-fields.php';
			require_once XOO_EL_PATH.'admin/class-xoo-el-admin-settings.php';
		}

	
		if ($this->is_request('admin')) {
			require_once XOO_EL_PATH.'admin/class-xoo-el-menu-settings.php';
			require_once XOO_EL_PATH.'admin/class-xoo-el-user-profile.php';
		}
	}


	public function hooks(){
		add_action( 'init', array( $this, 'on_install' ), 0 );
		add_action( 'admin_notices', array( $this, 'show_outdated_template_notice' ) );
		add_action( 'admin_head', array( $this, 'inline_styling' ) );
	}



	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}


	/**
	* On install
	*/
	public function on_install(){

		$version_option = 'xoo-el-version';
		$db_version 	= get_option( $version_option );

		//If first time install
		if( $db_version === false ){
			add_action( 'admin_notices', array( $this, 'admin_notice_on_install' ) );
		}

		if( $db_version ){

			if( version_compare( $db_version, '2.3', '<') ){
				//Map old values to new option
				$oldValues = (array) include XOO_EL_PATH.'/admin/views/oldtonew.php';
				foreach ( $oldValues as $keyData ) {
					$oldKeyValue = (array) get_option( $keyData['oldkey'] );
					$newKeyValue = (array) get_option( $keyData['newkey'] );

					if( $oldKeyValue === false ) continue;
					foreach ( $keyData['values'] as $oldsubkey => $newsubkey ) {
						if( !isset( $oldKeyValue[ $oldsubkey ] ) ) continue;
						$newKeyValue[ $newsubkey ] = $oldKeyValue[ $oldsubkey ];
					}
					update_option( $keyData['newkey'], $newKeyValue );
				}
			}


			if( version_compare( $db_version, '2.6', '<') ){

				$syOptions = (array) xoo_el_helper()->get_style_option();

				$syOptions['sy-head-img'] = '';
				$syOptions['sy-tab-padding'] = '12px 20px';
				$syOptions['sy-tab-fsize'] = '16';
				$syOptions['sy-popup-height-type'] = 'custom';

				update_option( 'xoo-el-sy-options', $syOptions );

				$glOptions = (array) xoo_el_helper()->get_general_option();

				$glOptions['m-nav-pattern'] = 'tabs';
				$glOptions['m-form-pattern'] = 'separate';

				update_option( 'xoo-el-gl-options', $glOptions );
			}

			if( version_compare( $db_version, '2.7', '<') ){

				$avOptions = (array) xoo_el_helper()->get_advanced_option();

				$avOptions['m-error-log'] = 'no';

				update_option( 'xoo-el-av-options', $glOptions );
			}

		}
		

		if( version_compare( $db_version, XOO_EL_VERSION, '<') ){

			xoo_el()->aff->fields->set_defaults();

			xoo_el_helper()->admin->auto_generate_settings();

			//Update to current version
			update_option( $version_option, XOO_EL_VERSION);

			xoo_el_helper()->update_theme_templates_data(); //get theme template data

			$this->updatedFrom = $db_version;
		}
	}


	public function otp_login_update_notice(){
		?>
		<div class="notice is-dismissible notice-warning" style="padding: 10px; font-weight: 600; font-size: 16px; line-height: 2">This version of login/signup popup is not compatible with the current version of OTP Login plugin. <br>Please update the OTP login plugin.</div>
		<?php
	}


	public function admin_notice_on_install(){
		?>
		<div class="notice notice-success is-dismissible xoo-el-admin-notice">
			<p>Start by adding Login/Registration links to your <a href="<?php echo esc_url( admin_url( 'nav-menus.php?xoo_el_nav=true' ) ); ?>">menu</a>.</p>
			<p>Check <a href="<?php echo esc_url( admin_url( 'admin.php?page=easy-login-woocommerce-settings' ) ); ?>">Settings & Shortcodes</a></p>
		</div>
		<?php
	}


	public function show_outdated_template_notice(){

		if( !xoo_el_helper()->admin->is_settings_page() ) return;

		$themeTemplatesData = xoo_el_helper()->get_theme_templates_data();
		if( empty( $themeTemplatesData ) || $themeTemplatesData['has_outdated'] !== 'yes' ) return;
		?>
		<div class="notice notice-success is-dismissible xoo-el-admin-notice">
		<p><?php printf( 'You have <a href="%1$s">outdated templates</a> in your theme which are no longer supported. Please see "info" tab for more info.', admin_url( 'admin.php?page=easy-login-woocommerce-settings' ) ); ?></p>
		</div>
		<?php
	}


	public function inline_styling(){
		?>
		<style type="text/css">
			.notice.xoo-el-admin-notice p {
			    font-size: 16px;
			}
			.notice.xoo-el-admin-notice{
			    border: 2px solid #007cba;
			}
		</style>
		<?php
	}


}


?>