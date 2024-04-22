<?php
require_once STM_ADMIN_MAILCHIMP_INTEGRATION_PATH . '/classes/STMMailChimpIntegration.php';

class STMMailChimpBase {

	public static $pluginTitle = '';
	public static $pluginSlug = '';
	private static $currentUser = [];


	public static function init( $pluginData ) {
		global $pagenow;

		self::$currentUser = self::getCurrentUser();

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		/** check data  */
		if ( ! isset( $pluginData['plugin_name'] ) || empty( $pluginData['plugin_name'] )
			 || ! isset( $pluginData['plugin_title'] ) || empty( $pluginData['plugin_title'] ) ) {
			return;
		}

		if ( $pluginData['is_pro'] === true ) {
			return;
		}

		self::initData( $pluginData );

		$mailchimpIntegrationData = self::getMailchimpIntegrationData();

		/** if not opted in
		 *  - show notice for not plugin page
		 *  - show opt in link for plugin page
		 */
		$memberKey = self::memberKeyIfExist( $mailchimpIntegrationData );

		if ( $memberKey === false ||
			 ( $memberKey !== false && ( ( $mailchimpIntegrationData[ $memberKey ]['allowed'] == false
										   && $mailchimpIntegrationData[ $memberKey ]['opt_out'] == false ) || $mailchimpIntegrationData[ $memberKey ]['opt_out'] == true ) ) ) {

			/** action to opt in */
			add_action( 'wp_ajax_stm_mailchimp_integration_add_' . $pluginData['plugin_name'] . '/' . $pluginData['plugin_name'] . '.php', [
				self::class,
				'addMailchimpMember'
			], $priority = 10, $accepted_args = 1 );
			add_action( 'wp_ajax_stm_mailchimp_integration_add_' . $pluginData['plugin_name'], [
				self::class,
				'addMailchimpMember'
			], $priority = 10, $accepted_args = 1 );

			/** action to remove notice temporary */
			add_action( 'wp_ajax_stm_mailchimp_integration_not_allowed_' . $pluginData['plugin_name'], [
				self::class,
				'notAllowedByUser'
			], $priority = 10, $accepted_args = 1 );
			add_filter( 'plugin_action_links_' . $pluginData['plugin_name'] . '/' . $pluginData['plugin_name'] . '.php', array(
				self::class,
				'addOptInPluginActionLink'
			), 10, 2 );
			apply_filters( 'plugin_action_links_' . $pluginData['plugin_name'] . '/' . $pluginData['plugin_name'] . '.php', [], $pluginData['plugin_name'] );

			/** show notice just if user not clicked on 'No thanks' button */
			if ( $memberKey === false && 'plugins.php' !== $pagenow ) {
				self::showOptInAdminNotices();
			}
		}

		/** if opted in show opt out link on plugin page */
		if ( $memberKey !== false && $mailchimpIntegrationData[ $memberKey ]['opt_out'] === false
			 && $mailchimpIntegrationData[ $memberKey ]['allowed'] === true ) {
			add_action( 'wp_ajax_stm_mailchimp_integration_remove_' . $pluginData['plugin_name'] . '/' . $pluginData['plugin_name'] . '.php', [
				self::class,
				'rmMailchimpMember'
			], $priority = 10, $accepted_args = 1 );
			add_filter( 'plugin_action_links_' . $pluginData['plugin_name'] . '/' . $pluginData['plugin_name'] . '.php', array(
				self::class,
				'addOptOutPluginActionLink'
			), 10, 2 );
			apply_filters( 'plugin_action_links_' . $pluginData['plugin_name'] . '/' . $pluginData['plugin_name'] . '.php', [], $pluginData['plugin_name'] );
		}
	}

	/** get current logged in user key from option */
	public static function memberKeyIfExist( $integrationData ) {
		if ( $integrationData === false || $integrationData === null ) {
			return false;
		}
		$key = array_search( self::$currentUser->data->user_email, array_column( $integrationData, 'email' ) );

		return $key;
	}

	/**
	 * Retrieves an option value for option name `stm_mailchimp_integration_member_data`.
	 * @return mixed Value of the option.
	 */
	public static function getMailchimpIntegrationData() {
		$integration = get_option( 'stm_mailchimp_integration_member_data_' . self::$pluginSlug, '' );

        return is_null( $integration ) ? null : unserialize( $integration );
	}

	/**
	 * Adds a new option for option name `stm_mailchimp_integration_member_data`.
	 * @return bool True if the option was added, false otherwise.
	 */
	private static function mailchimpAddIntegrationData( $memberId ) {
		$result          = false;
		$integrationData = self::getMailchimpIntegrationData();

		$memberKey = self::memberKeyIfExist( $integrationData );

		/** create row if not exist  */
		if ( $memberKey === false ) {
			$integrationData[] = [
				'email'      => self::$currentUser->data->user_email,
				'member_id'  => $memberId,
				'allowed'    => true,
				'opt_out'    => false,
				'created_at' => wp_date( 'Y-m-d H:i:s' ),
			];
			$result            = add_option( 'stm_mailchimp_integration_member_data_' . self::$pluginSlug, serialize( $integrationData ) );

		} else {

			/** update row if exist  ( possible if user earlier clicked on 'no thanks' ) */
			$integrationData[ $memberKey ]['member_id'] = $memberId;
			$integrationData[ $memberKey ]['allowed']   = true;
			$integrationData[ $memberKey ]['opt_out']   = false;
			$result                                     = update_option( 'stm_mailchimp_integration_member_data_' . self::$pluginSlug, serialize( $integrationData ) );
		}

		return $result;
	}

	/**
	 * Removes user data from option name `stm_mailchimp_integration_member_data`.
	 * @return bool True if the option was updated, false otherwise.
	 */
	private static function mailchimpDeleteIntegrationData() {
		$result          = false;
		$integrationData = self::getMailchimpIntegrationData();

		$memberKey = self::memberKeyIfExist( $integrationData );

		if ( $memberKey !== false ) {
			$integrationData[ $memberKey ]['opt_out'] = true;
			$result                                   = update_option( 'stm_mailchimp_integration_member_data_' . self::$pluginSlug, serialize( $integrationData ) );
		}

		return $result;
	}

	/** if user click on 'No thanks' button  */
	public static function notAllowedByUser() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$integrationData = self::getMailchimpIntegrationData();

		if ( self::memberKeyIfExist( $integrationData ) === false ) {

			$integrationData[] = [
				'email'      => self::$currentUser->data->user_email,
				'member_id'  => null,
				'allowed'    => false,
				'opt_out'    => false,
				'created_at' => wp_date( 'Y-m-d H:i:s' ),
			];

			add_option( 'stm_mailchimp_integration_member_data_' . self::$pluginSlug, serialize( $integrationData ) );

		}

		header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
		exit;
	}

	/** add Member to mailchimp ( subscribe ) */
	public static function addMailchimpMember() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$locale   = get_user_locale( self::$currentUser->data->ID );
		$language = explode( '_', $locale )[0];
		$member   = [
			'action'   => 'add',
			'plugin'   => self::$pluginSlug,
			'email'    => self::$currentUser->data->user_email,
			'language' => $language,
			'ip'       => $_SERVER['SERVER_ADDR'],
			'name'     => self::$currentUser->data->display_name,
		];

		$memberId = STMMailChimpIntegration::addMember( $member );
		/** add member data to wp_options */
		self::mailchimpAddIntegrationData( $memberId );

		header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
		exit;
	}

	/** remove Member to mailchimp ( unsubscribe ) */
	public static function rmMailchimpMember() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$member = [
			'action' => 'remove',
			'plugin' => self::$pluginSlug,
			'email'  => self::$currentUser->data->user_email,
		];
		/** unsubscribe from mailchimp by email */
		STMMailChimpIntegration::deleteMember( $member );

		/** change data in wp_options */
		self::mailchimpDeleteIntegrationData();

		header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
		exit;
	}

	/** add plugin action link for opt in */
	public static function addOptInPluginActionLink( $links, $pluginSlug ) {
		if ( empty( $pluginSlug ) || is_array( $pluginSlug ) ) {
			return;
		}

		$optInUrl = admin_url( 'admin-ajax.php' ) . '?action=' . 'stm_mailchimp_integration_add_' . $pluginSlug;
		$link     = sprintf( '<a href="%s">%s</a>', $optInUrl, esc_html__( 'Opt In', $pluginSlug ) );

		return array_merge( $links, [ 'stm-mailchimp-optin_link_' . $pluginSlug => $link ] );
	}

	/** add plugin action link for opt out */
	public static function addOptOutPluginActionLink( $links, $pluginSlug ) {
		$optOutUrl = admin_url(
			'admin-ajax.php?' . http_build_query( array(
					'action' => 'stm_mailchimp_integration_remove_' . $pluginSlug
				)
			)
		);

		$link = sprintf( '<a href="%s">%s</a>', esc_attr( $optOutUrl ), esc_html__( 'Opt Out', $pluginSlug ) );

		return array_merge( $links, [ 'stm-mailchimp-optout_link_' . $pluginSlug => $link ] );
	}

	/** show notice */
	public static function showOptInAdminNotices() {
		$optInUrl      = admin_url(
			'admin-ajax.php?' . http_build_query( [ 'action' => 'stm_mailchimp_integration_add_' . self::$pluginSlug ] )
		);
		$notAllowedUrl = admin_url(
			'admin-ajax.php?' . http_build_query( [ 'action' => 'stm_mailchimp_integration_not_allowed_' . self::$pluginSlug ] )
		);

		$noticeText = sprintf( 'Want to help make %1$s even more awesome? Allow %1$s to collect non-sensitive diagnostic data and usage information.', self::$pluginTitle );

		$init_data = array(
			'notice_type'          => 'mailchimp-notice',
			'notice_logo'          => 'attent_blue_small.svg',
			'notice_title'         => $noticeText,
			'notice_btn_one'       => $optInUrl,
			'notice_btn_one_title' => __( 'Allow', self::$pluginSlug ),
			'notice_btn_two'       => $notAllowedUrl,
			'notice_btn_two_title' => __( 'No Thanks', self::$pluginSlug ),
		);

		stm_admin_notices_init( $init_data );
	}

	public static function initData( $pluginData ) {
		self::$pluginTitle = $pluginData['plugin_title'];
		self::$pluginSlug  = $pluginData['plugin_name'];
	}

	/**
	 * Get current logged in user
	 * @return WP_User Current WP_User instance.
	 */
	private static function getCurrentUser() {

		self::cookieConstants();

		return wp_get_current_user();
	}

	private static function cookieConstants() {
		if ( defined( 'LOGGED_IN_COOKIE' ) &&
			 ( defined( 'AUTH_COOKIE' ) || defined( 'SECURE_AUTH_COOKIE' ) )
		) {
			return;
		}

		if ( ! defined( 'COOKIEHASH' ) ) {
			if ( get_site_option( 'siteurl' ) ) {
				define( 'COOKIEHASH', md5( get_site_option( 'siteurl' ) ) );
			} else {
				define( 'COOKIEHASH', '' );
			}
		}

		if ( ! defined( 'LOGGED_IN_COOKIE' ) ) {
			define( 'LOGGED_IN_COOKIE', 'wordpress_logged_in_' . COOKIEHASH );
		}

		if ( ! defined( 'AUTH_COOKIE' ) ) {
			define( 'AUTH_COOKIE', 'wordpress_' . COOKIEHASH );
		}

		if ( ! defined( 'SECURE_AUTH_COOKIE' ) ) {
			define( 'SECURE_AUTH_COOKIE', 'wordpress_sec_' . COOKIEHASH );
		}

	}
}
