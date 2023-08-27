<?php
/**
 * Class for providing data the WordPress' Site Health debug information.
 *
 * @package JupiterX_Core\Admin
 *
 * @since 1.18.0
 */

/**
 * Site Health class.
 *
 * @since 1.18.0
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class JupiterX_Core_Site_Health {

	/**
	 * Site health class instance.
	 *
	 * @since 1.18.0
	 *
	 * @var JupiterX_Core_Site_Health|null
	 */
	private static $instance = null;

	/**
	 * Return an instance of the JupiterX_Core_Site_Health class, or create one if none exist yet.
	 *
	 * @since 1.18.0
	 *
	 * @return JupiterX_Core_Site_Health|null
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new JupiterX_Core_Site_Health();
		}

		return self::$instance;
	}

	/**
	 * Construct class.
	 *
	 * @since 1.18.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_jupiterx_system_status', [ $this, 'system_status' ] );
		add_filter( 'debug_information', [ $this, 'debug_information' ] );
		add_filter( 'site_status_tests', [ $this, 'site_status_tests' ] );
	}

	/**
	 * Get site status using WP_Site_Health class.
	 *
	 * @since 1.18.0
	 *
	 * @return void
	 *
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function system_status() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have access to this section.', 'jupiterx-core' );
		}

		$health_check_js_variables = [
			'site_status' => [
				'direct' => [],
				'async'  => [],
				'issues' => [
					'good'        => 0,
					'recommended' => 0,
					'critical'    => 0,
				],
			],
		];

		$issue_counts = get_transient( 'health-check-site-status-result' );

		jupiterx_log(
			'[Control Panel > Dashboard > Site Health] To get site health issues, the following data is expected to be an object.',
			$issue_counts
		);

		if ( false !== $issue_counts ) {
			$issue_counts = json_decode( $issue_counts );

			$health_check_js_variables['site_status']['issues'] = $issue_counts;
		}

		$core_current_version = get_bloginfo( 'version' );

		if ( version_compare( $core_current_version, '5.2', '<' ) ) {
			jupiterx_log(
				'[Control Panel > Dashboard > Site Health] To show site health issues, WordPress needs to be updated.',
				$core_current_version
			);

			$health_check_js_variables['site_status']['direct'][] = [
				'label'       => sprintf(
					// translators: %s Site's current WordPress version
					__( 'WordPress version (%s) is outdated', 'jupiterx-core' ),
					$core_current_version
				),
				'status'      => 'critical',
				'badge'       => [
					'label' => 'Performance',
					'color' => 'blue',
				],
				'actions'     => sprintf(
					'<a href="%s">%s</a>',
					esc_url( admin_url( 'update-core.php' ) ),
					__( 'Update core', 'jupiterx-core' )
				),
				'description' => '',
				'test'        => 'wordpress_version',
			];

			wp_send_json_success( $health_check_js_variables );
		}

		if ( ! class_exists( 'WP_Site_Health' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-site-health.php';
		}

		$site_health = new WP_Site_Health();

		// Start running tests.
		$tests = $site_health::get_tests();

		jupiterx_log(
			'[Control Panel > Dashboard > Site Health] To show site health issues, the following data is expected to be an array.',
			$tests
		);

		// Don't run https test on localhost.
		if ( 'localhost' === preg_replace( '|https?://|', '', get_site_url() ) ) {
			unset( $tests['direct']['https_status'] );
		}

		foreach ( $tests['direct'] as $test ) {
			if ( is_string( $test['test'] ) ) {
				$test_function = sprintf(
					'get_test_%s',
					$test['test']
				);

				if ( method_exists( $site_health, $test_function ) && is_callable( [ $site_health, $test_function ] ) ) {
					$health_check_js_variables['site_status']['direct'][] = $this->perform_test( [ $site_health, $test_function ] );
					continue;
				}
			}

			if ( is_callable( $test['test'] ) ) {
				$health_check_js_variables['site_status']['direct'][] = $this->perform_test( $test['test'] );
			}
		}

		foreach ( $tests['async'] as $test ) {
			if ( is_string( $test['test'] ) ) {
				$health_check_js_variables['site_status']['async'][] = [
					'test'      => $test['test'],
					'completed' => false,
				];
			}
		}

		wp_send_json_success( $health_check_js_variables );
	}

	/**
	 * Run a Site Health test directly.
	 *
	 * @since 1.18.0
	 *
	 * @param array $callback Callback method for test.
	 *
	 * @return mixed|void
	 */
	private function perform_test( $callback ) {
		// Borrowed filter from WP_Site_Health::perform_test().
		return apply_filters( 'site_status_test_result', call_user_func( $callback ) );
	}

	/**
	 * Add debug data to WP_Debug_Data class.
	 *
	 * @since 1.18.0
	 *
	 * @param array $info WP debug data for the site.
	 *
	 * @return array Filters debug data.
	 *
	 * @SuppressWarnings(PHPMD)
	 */
	public function debug_information( $info ) {
		// Compose common internationalized values.
		$value = [
			'yes'      => __( 'Yes', 'jupiterx-core' ),
			'no'       => __( 'No', 'jupiterx-core' ),
			'enabled'  => __( 'Enabled', 'jupiterx-core' ),
			'disabled' => __( 'Disabled', 'jupiterx-core' ),
		];

		// WordPress section.
		$info['wp-core']['fields']['content_url'] = [
			'label' => __( 'Content URL', 'jupiterx-core' ),
			'value' => WP_CONTENT_URL,
		];

		$upload_dir = wp_get_upload_dir();

		$info['wp-core']['fields']['upload_url'] = [
			'label' => __( 'Upload URL', 'jupiterx-core' ),
			'value' => $upload_dir['baseurl'],
		];
		$info['wp-core']['fields']['front_page'] = [
			'label' => __( 'Front page display', 'jupiterx-core' ),
			'value' => get_option( 'show_on_front' ),
		];

		// Server section.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		$is_localhost = ( '127.0.0.1' === $_SERVER['REMOTE_ADDR'] || 'localhost' === $_SERVER['REMOTE_ADDR'] || '::1' === $_SERVER['REMOTE_ADDR'] );

		$info['wp-server']['fields']['localhost'] = [
			'label' => __( 'Is local environment?', 'jupiterx-core' ),
			'value' => $is_localhost ? $value['yes'] : $value['no'],
		];

		$info['wp-server']['fields']['php_errors'] = [
			'label' => __( 'PHP display errors', 'jupiterx-core' ),
			'value' => ini_get( 'display_errors' ) ? $value['yes'] : $value['no'],
		];

		$info['wp-server']['fields']['fsockopen'] = [
			'label' => __( 'Is fsockopen available?', 'jupiterx-core' ),
			'value' => function_exists( 'fsockopen' ) ? $value['yes'] : $value['no'],
		];

		$info['wp-server']['fields']['php_gzopen'] = [
			'label' => __( 'Is gzipopen available?', 'jupiterx-core' ),
			'value' => is_callable( 'gzopen' ) ? $value['yes'] : $value['no'],
		];

		$info['wp-server']['fields']['php_xml'] = [
			'label' => __( 'PHP XML ', 'jupiterx-core' ),
			'value' => function_exists( 'xml_parse' ) ? $value['enabled'] : $value['disabled'],
		];

		$simplexml_loaded = class_exists( 'SimpleXMLElement' ) && function_exists( 'simplexml_load_string' );

		$info['wp-server']['fields']['php_simplexml'] = [
			'label' => __( 'SimpleXML', 'jupiterx-core' ),
			'value' => $simplexml_loaded ? $value['enabled'] : $value['disabled'],
		];

		$mbstring_loaded = extension_loaded( 'mbstring' ) && function_exists( 'mb_eregi' ) && function_exists( 'mb_ereg_match' );

		$info['wp-server']['fields']['php_mbstring'] = [
			'label' => __( 'MBString', 'jupiterx-core' ),
			'value' => $mbstring_loaded ? $value['enabled'] : $value['disabled'],
		];

		$info['wp-server']['fields']['php_soapclient'] = [
			'label' => __( 'SoapClient', 'jupiterx-core' ),
			'value' => class_exists( 'SoapClient' ) ? $value['enabled'] : $value['disabled'],
		];

		$info['wp-server']['fields']['php_domdocument'] = [
			'label' => __( 'DOMDocument', 'jupiterx-core' ),
			'value' => class_exists( 'DOMDocument' ) ? $value['enabled'] : $value['disabled'],
		];

		$info['wp-server']['fields']['php_ziparchive'] = [
			'label' => __( 'ZipArchive', 'jupiterx-core' ),
			'value' => class_exists( 'ZipArchive' ) ? $value['enabled'] : $value['disabled'],
		];

		$info['wp-server']['fields']['php_iconv'] = [
			'label' => __( 'Iconv', 'jupiterx-core' ),
			'value' => class_exists( 'Iconv' ) ? $value['enabled'] : $value['disabled'],
		];

		if ( defined( 'WP_HTTP_BLOCK_EXTERNAL' ) ) {
			$block_external = __( 'HTTP requests have been blocked by the WP_HTTP_BLOCK_EXTERNAL constant, with no allowed hosts.', 'jupiterx-core' );

			if ( defined( 'WP_ACCESSIBLE_HOSTS' ) ) {
				$allowed_hosts = explode( ',', WP_ACCESSIBLE_HOSTS );
			}

			if ( isset( $allowed_hosts ) && count( $allowed_hosts ) > 0 ) {
				$block_external = sprintf(
					/* translators: 1: Allowed hosts */
					esc_html__( 'HTTP requests have been blocked by the WP_HTTP_BLOCK_EXTERNAL constant, with some hosts whitelisted: %s.', 'jupiterx-core' ),
					implode( ',', $allowed_hosts )
				);
			}
		}

		$info['wp-server']['fields']['http_requests'] = [
			'label' => __( 'HTTP Requests', 'jupiterx-core' ),
			'value' => isset( $block_external ) ? $block_external : __( 'Accessible', 'jupiterx-core' ),
		];

		$artbees_dotnet = wp_remote_get( 'https://artbees.net', [ 'timeout' => 10 ] );

		if ( ! is_wp_error( $artbees_dotnet ) ) {
			$info['wp-server']['fields']['artbees_communication'] = [
				'label' => __( 'Communication with Artbees' ),
				'value' => __( 'artbees.net is reachable' ),
				'debug' => 'true',
			];
		} else {
			$info['wp-server']['fields']['artbees_communication'] = [
				'label' => __( 'Communication with artbees.net' ),
				'value' => sprintf(
					/* translators: 1: The IP address artbees.net resolves to. 2: The error returned by the lookup. */
					__( 'Unable to reach Artbees at %1$s: %2$s' ),
					gethostbyname( 'artbees.net' ),
					$artbees_dotnet->get_error_message()
				),
				'debug' => $artbees_dotnet->get_error_message(),
			];
		}

		// Database section.
		$tables = $this->get_tables_sizes();

		foreach ( $tables as $table ) {
			$info['wp-database']['fields'][ $table['name'] ] = [
				'label' => esc_html( $table['name'] ),
				'value' => $table['size'] . ' MB',
			];
		}

		// Browser section.
		if ( ! class_exists( 'Browser' ) ) {
			jupiterx_core()->load_files( [
				'control-panel-2/includes/class-browser',
			] );
		}

		$browser = new \Browser();

		$info['browser'] = [
			'label'  => __( 'Browser', 'jupiterx-core' ),
			'fields' => [
				'browser'    => [
					'label' => __( 'Browser', 'jupiterx-core' ),
					'value' => $browser->getBrowser(),
				],
				'user_agent' => [
					'label' => __( 'User agent', 'jupiterx-core' ),
					'value' => $browser->getUserAgent(),
				],
				'version'    => [
					'label' => __( 'Version', 'jupiterx-core' ),
					'value' => $browser->getVersion(),
				],
				'platform'   => [
					'label' => __( 'Platform', 'jupiterx-core' ),
					'value' => $browser->getPlatform(),
				],
			],
		];

		return $info;
	}

	/**
	 * Get database tables sizes.
	 *
	 * @since 1.18.0
	 *
	 * @return array Tables list with name and size.
	 */
	public function get_tables_sizes() {
		global $wpdb;

		$tables = [
			'options',
			'links',
			'commentmeta',
			'term_relationships',
			'postmeta',
			'posts',
			'term_taxonomy',
			'terms',
			'comments',
			'termmeta',
			'usermeta',
			'users',
		];
		if ( is_multisite() ) {
			if ( ! is_super_admin() ) {
				// Omit usermeta and users tables.
				$tables = array_splice( $tables, -2 );
			}
			$tables[] = 'blogs';
			$tables[] = 'blogs_versions ';
			$tables[] = 'registration_log';
			$tables[] = 'signups';
			$tables[] = 'site';
			$tables[] = 'sitemeta';
		}

		foreach ( $tables as $key => $table ) {
			$tables[ $key ] = $wpdb->prefix . $table;
		}

		$names = "'" . implode( "','", $tables ) . "'";
		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared
		$query = $wpdb->prepare( "
			SELECT TABLE_NAME AS `name`, ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024),2) AS `size`
			FROM information_schema.TABLES
			WHERE TABLE_SCHEMA = %s AND TABLE_NAME IN ($names)
		", $wpdb->dbname );
		// phpcs:enable

		// phpcs:ignore
		return $wpdb->get_results( $query, ARRAY_A );
	}

	/**
	 * Get site status.
	 *
	 * @since 1.18.0
	 *
	 * @return array Site status tests.
	 */
	public function site_status_tests( $tests ) {
		$tests['direct'] = array_merge( $tests['direct'], [
			'jupiterx_core_php_memory_limit' => [
				'label' => __( 'PHP Memory Limit is sufficient', 'jupiterx-core' ),
				'test'  => [ $this, 'get_test_php_memory_limit' ],
			],
			'jupiterx_core_php_modules' => [
				'label' => __( 'Required and recommended PHP modules are installed', 'jupiterx-core' ),
				'test'  => [ $this, 'get_test_php_modules' ],
			],
		] );

		return $tests;
	}

	/**
	 * Test PHP memory limit.
	 *
	 * @since 1.18.0
	 *
	 * @return array PHP memory limit result.
	 */
	public function get_test_php_memory_limit() {
		$result = [
			'label'       => __( 'PHP memory limit is sufficient', 'jupiterx-core' ),
			'status'      => 'good',
			'badge'       => [
				'label' => 'Jupiter X',
				'color' => 'blue',
			],
			'description' => sprintf(
				'<p>%s</p>',
				__( 'PHP memory limit is the maximum amount of memory in bytes that a script is allowed to allocate. At least 128M is required for the theme and 256M is recommended if you have enabled bunch of plugins to your site.', 'jupiterx-core' )
			),
			'actions'     => sprintf(
				'<a href="%s" target="_blank" rel="noopener noreferrer">%s <span aria-hidden="true" class="dashicons dashicons-external"></span></a>',
				'https://themes.artbees.net/docs/jupiter-x-server-requirements/',
				__( 'Learn more about Jupiter X server requirements', 'jupiterx-core' )
			),
			'test'        => 'jupiterx_core_php_memory_limit',
		];

		$memory_limit = ini_get( 'memory_limit' );
		if ( defined( 'WP_MEMORY_LIMIT' ) ) {
			$memory_limit = WP_MEMORY_LIMIT;
		}

		if ( preg_match( '/^(\d+)(.)$/', $memory_limit, $matches ) ) {
			if ( 'M' === $matches[2] ) {
				$memory_limit = $matches[1] * MB_IN_BYTES;
			} elseif ( 'K' === $matches[2] ) {
				$memory_limit = $matches[1] * KB_IN_BYTES;
			}
		}

		if ( $memory_limit < ( 128 * MB_IN_BYTES ) ) {
			$result['status'] = 'critical';
			$result['label']  = __( 'Your site has insufficient PHP memory limit', 'jupiterx-core' );

			if ( defined( 'WP_MEMORY_LIMIT' ) ) {
				$result['description'] .= '<p>';
				$result['description'] .= sprintf(
					// translators: %s Value of site's WP_MEMORY_LIMIT
					__( 'The <code>WP_MEMORY_LIMIT</code> with defined value of %s has been added to this website\'s configuration file or defined as default by WordPress.', 'jupiterx-core' ),
					WP_MEMORY_LIMIT
				);
				$result['description'] .= '</p>';
			}
		}

		return $result;
	}

	/**
	 * Test PHP modules.
	 *
	 * @since 1.18.0
	 *
	 * @return array PHP modules result.
	 *
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function get_test_php_modules() {
		$result = [
			'label'       => __( 'Required and recommended PHP modules are installed', 'jupiterx-core' ),
			'status'      => 'good',
			'badge'       => [
				'label' => 'Jupiter X',
				'color' => 'blue',
			],
			'description' => sprintf(
				'<p>%s</p>',
				__( 'PHP modules perform most of the tasks on the server that make your site run. Any changes to these must be made by your server administrator.', 'jupiterx-core' )
			),
			'actions'     => sprintf(
				'<a href="%s" target="_blank" rel="noopener noreferrer">%s <span aria-hidden="true" class="dashicons dashicons-external"></span></a>',
				'https://themes.artbees.net/docs/jupiter-x-server-requirements/',
				__( 'Learn more about Jupiter X server requirements', 'jupiterx-core' )
			),
			'test'        => 'jupiterx_core_php_modules',
		];

		$modules = [
			'curl'        => [
				'function' => 'curl_version',
				'required' => true,
			],
			'mbstring'    => [
				'function' => 'mb_check_encoding',
				'required' => true,
			],
			'libxml'     => [
				'extension' => 'libxml',
				'required'  => true,
			],
			'xmlreader'   => [
				'extension'    => 'xmlreader',
				'required'     => true,
				'fallback_for' => 'mod_xml',
			],
			'simplexml'   => [
				'extension'    => 'simplexml',
				'required'     => true,
				'fallback_for' => 'mod_xml',
			],
			'xml'         => [
				'function' => 'xml_parse',
				'required' => true,
			],
			'fsockopen'   => [
				'function' => 'fsockopen',
				'required' => true,
			],
			'soap'        => [
				'class'    => 'SoapClient',
				'required' => true,
			],
			'domdocument' => [
				'class'    => 'DOMDocument',
				'required' => true,
			],
			'zip'         => [
				'class'    => 'ZipArchive',
				'required' => true,
			],
			'zlib'        => [
				'extension'    => 'zlib',
				'required'     => true,
				'fallback_for' => 'zip',
			],
			'gzip'        => [
				'function' => 'gzopen',
				'required' => false,
			],
			'iconv'       => [
				'function' => 'iconv',
				'required' => true,
			],
			'openssl'     => [
				'function' => 'openssl_encrypt',
				'required' => true,
			],
			'imagick'     => [
				'extension' => 'imagick',
				'required'  => true,
			],
			'gd'          => [
				'extension'    => 'gd',
				'required'     => true,
				'fallback_for' => 'imagick',
			],
		];

		$failures = [];

		foreach ( $modules as $library => $module ) {
			$extension  = ( isset( $module['extension'] ) ? $module['extension'] : null );
			$function   = ( isset( $module['function'] ) ? $module['function'] : null );
			$constant   = ( isset( $module['constant'] ) ? $module['constant'] : null );
			$class_name = ( isset( $module['class'] ) ? $module['class'] : null );

			// If this module is a fallback for another function, check if that other function passed.
			if ( isset( $module['fallback_for'] ) ) {
				/*
				 * If that other function has a failure, mark this module as required for normal operations.
				 * If that other function hasn't failed, skip this test as it's only a fallback.
				 */
				if ( isset( $failures[ $module['fallback_for'] ] ) ) {
					$module['required'] = true;
				} else {
					continue;
				}
			}

			if ( ! $this->test_php_extension_availability( $extension, $function, $constant, $class_name ) && ( ! isset( $module['php_bundled_version'] ) || version_compare( PHP_VERSION, $module['php_bundled_version'], '<' ) ) ) {
				if ( $module['required'] ) {
					$result['status'] = 'critical';

					$class         = 'error';
					$screen_reader = __( 'Error', 'jupiterx-core' );
					$message       = sprintf(
						/* translators: %s: The module name. */
						__( 'The required module, %s, is not installed, or has been disabled.', 'jupiterx-core' ),
						$library
					);
				} else {
					$class         = 'warning';
					$screen_reader = __( 'Warning', 'jupiterx-core' );
					$message       = sprintf(
						/* translators: %s: The module name. */
						__( 'The optional module, %s, is not installed, or has been disabled.', 'jupiterx-core' ),
						$library
					);
				}

				if ( ! $module['required'] && 'good' === $result['status'] ) {
					$result['status'] = 'recommended';
				}

				$failures[ $library ] = "<span class='dashicons $class'><span class='screen-reader-text'>$screen_reader</span></span> $message";
			}
		}

		if ( ! empty( $failures ) ) {
			$output = '<ul>';

			foreach ( $failures as $failure ) {
				$output .= sprintf(
					'<li>%s</li>',
					$failure
				);
			}

			$output .= '</ul>';
		}

		if ( 'good' !== $result['status'] ) {
			if ( 'critical' === $result['status'] ) {
				$result['label'] = __( 'Required PHP modules are missing', 'jupiterx-core' );
			} else {
				$result['label'] = __( 'Recommended PHP modules are missing', 'jupiterx-core' );
			}

			$result['description'] .= sprintf(
				'<p>%s</p>',
				$output
			);
		}

		return $result;
	}

	/**
	 * Check if the passed extension or function are available.
	 *
	 * Make the check for available PHP modules into a simple boolean operator for a cleaner test runner.
	 *
	 * @since 1.18.0
	 *
	 * @param string $extension Optional. The extension name to test. Default null.
	 * @param string $function  Optional. The function name to test. Default null.
	 * @param string $constant  Optional. The constant name to test for. Default null.
	 * @param string $class     Optional. The class name to test for. Default null.
	 *
	 * @return bool Whether or not the extension and function are available.
	 *
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	private function test_php_extension_availability( $extension = null, $function = null, $constant = null, $class = null ) {
		// If no extension or function is passed, claim to fail testing, as we have nothing to test against.
		if ( ! $extension && ! $function && ! $constant && ! $class ) {
			return false;
		}

		if ( $extension && ! extension_loaded( $extension ) ) {
			return false;
		}
		if ( $function && ! function_exists( $function ) ) {
			return false;
		}
		if ( $constant && ! defined( $constant ) ) {
			return false;
		}
		if ( $class && ! class_exists( $class ) ) {
			return false;
		}

		return true;
	}
}

new JupiterX_Core_Site_Health();
