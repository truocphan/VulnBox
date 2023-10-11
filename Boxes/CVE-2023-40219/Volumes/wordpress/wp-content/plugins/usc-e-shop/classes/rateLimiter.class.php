<?php
/**
 * Rate Limiter Class.
 *
 * @package Welcart
 */
class RateLimiter {

	var $folder_log_path;
	var $login_failed_log_path;
	var $ip_blocked_path;
	var $monitoring; /* minutes */
	var $num_of_errors;
	var $rejection_time; /* minutes */
	var $status;

	/**
	 * Construct.
	 */
	public function __construct() {

		$this->folder_log_path       = USCES_WP_CONTENT_DIR . '/uploads/usces_logs/';
		$this->login_failed_log_path = $this->folder_log_path . 'member_login_failed.log';
		$this->ip_blocked_path       = $this->folder_log_path . 'ip_addresses_blocked.log';

		// get brute force config.
		$options              = get_option( 'usces_ex' );
		$this->monitoring     = ( ! isset( $options['system']['brute_force']['monitoring_span'] ) ) ? 5 : (int) $options['system']['brute_force']['monitoring_span'];
		$this->num_of_errors  = ( ! isset( $options['system']['brute_force']['num_of_errors'] ) ) ? 3 : (int) $options['system']['brute_force']['num_of_errors'];
		$this->rejection_time = ( ! isset( $options['system']['brute_force']['rejection_time'] ) ) ? 10 : (int) $options['system']['brute_force']['rejection_time'];
		$this->status         = ( ! isset( $options['system']['brute_force']['status'] ) ) ? 0 : (int) $options['system']['brute_force']['status'];

		if ( $this->status ) {
			$this->initLogsFolder();
		}
	}

	/**
	 * Check block IP
	 *
	 * @return bool
	 */
	public function checkBlockIP() {
		if ( $this->status ) {
			try {
				$ip         = $_SERVER['REMOTE_ADDR'];
				$data       = $this->getLoginFailedDataByIP( $ip );
				$ip_blocked = $this->getIpAddressesBlocked();

				if ( isset( $ip_blocked[ $ip ] ) && ( strtotime( "-{$this->rejection_time} minutes" ) < $ip_blocked[ $ip ] ) ) {
					$this->saveLoginFailed();
					return true;
				}

				if ( count( $data ) ) {
					$count = 0;
					foreach ( $data as $key => $value ) {
						if ( strtotime( "-{$this->monitoring} minutes" ) < $key ) {
							$count += $value;
						}
					}
					if ( $count >= $this->num_of_errors ) {
						$ip_blocked[ $ip ] = strtotime( 'now' );
						file_put_contents( $this->ip_blocked_path, json_encode( $ip_blocked ) );
						return true;
					}
				}
			} catch ( Throwable $exception ) {

			}
		}
		return false;
	}

	/**
	 * Save login failed
	 */
	public function saveLoginFailed() {
		global $wp_query;

		try {
			if ( $this->status ) {
				$ip = $_SERVER['REMOTE_ADDR'];

				$content = $this->getLoginFailedData();
				$row     = $this->getLoginFailedDataByIP( $ip );

				$row[ strtotime( 'now' ) ] = ( isset( $row[ strtotime( 'now' ) ] ) ) ? ( $row[ strtotime( 'now' ) ] + 1 ) : 1;

				if ( count( $row ) > $this->num_of_errors ) {
					unset( $row[ array_key_first( $row ) ] );
				}
				$content[ $ip ] = $row;
				file_put_contents( $this->login_failed_log_path, json_encode( $content ) );

				$number_of_login_fail = 0;
				foreach ( $content[ $ip ] as $key => $value ) {
					if ( strtotime( "-{$this->monitoring} minutes" ) < $key ) {
						$number_of_login_fail++;
					}
				}

				if ( $number_of_login_fail >= $this->num_of_errors ) {
					$ip_blocked        = $this->getIpAddressesBlocked();
					$ip_blocked[ $ip ] = strtotime( 'now' );
					file_put_contents( $this->ip_blocked_path, json_encode( $ip_blocked ) );

					$wp_query->set_403();
					status_header( 403 );
					exit();
				}
			}
		} catch ( Throwable $exception ) {

		}
	}

	/**
	 * Clear login failed
	 */
	public function clear_login_failed() {
		try {
			if ( $this->status ) {
				$ip      = $_SERVER['REMOTE_ADDR'];
				$content = $this->getLoginFailedData();
				unset( $content[ $ip ] );
				file_put_contents( $this->login_failed_log_path, json_encode( $content ) );
			}
		} catch ( Throwable $exception ) {

		}
	}

	/**
	 * Get login failed data
	 *
	 * @return array
	 */
	public function getLoginFailedData() {
		if ( usces_is_reserved_file( $this->login_failed_log_path, 1 ) ) {
			$res = json_decode( file_get_contents( $this->login_failed_log_path ), true );
		} else {
			$res = array();
		}
		return $res;
	}

	/**
	 * Get login failed data by IP
	 *
	 * @param string $ip IP Address.
	 * @return array
	 */
	public function getLoginFailedDataByIP( $ip ) {
		$content = $this->getLoginFailedData();
		return ( isset( $content[ $ip ] ) ) ? $content[ $ip ] : array();
	}

	/**
	 * Get IP Addresses blocked
	 *
	 * @return array
	 */
	public function getIpAddressesBlocked() {
		if ( usces_is_reserved_file( $this->ip_blocked_path, 1 ) ) {
			$res = json_decode( file_get_contents( $this->ip_blocked_path ), true );
		} else {
			$res = array();
		}
		return $res;
	}

	/**
	 * Init logs folder
	 */
	public function initLogsFolder() {
		if ( ! is_dir( $this->folder_log_path ) ) {
			mkdir( $this->folder_log_path, 0775 );
		}
	}
}
