<?php
namespace InstaWP\Connect\Helpers;

class WPConfig {

	public $file;
	public $data;
	public $is_cli;

	public $constants = [
		'WP_ENVIRONMENT_TYPE',
		'WP_DEVELOPMENT_MODE',
		'WP_DISABLE_FATAL_ERROR_HANDLER',
		'WP_DISABLE_ADMIN_EMAIL_VERIFY_SCREEN',
		'AUTOSAVE_INTERVAL',
		'WP_POST_REVISIONS',
		'MEDIA_TRASH',
		'EMPTY_TRASH_DAYS',
		'WP_MAIL_INTERVAL',
		'WP_MEMORY_LIMIT',
		'WP_MAX_MEMORY_LIMIT',
		'AUTOMATIC_UPDATER_DISABLED',
		'WP_AUTO_UPDATE_CORE',
		'CORE_UPGRADE_SKIP_NEW_BUNDLE',
		'WP_CACHE',
		'WP_DEBUG',
		'WP_DEBUG_LOG',
		'WP_DEBUG_DISPLAY',
		'WP_CONTENT_DIR',
		'WP_CONTENT_URL',
		'WP_PLUGIN_DIR',
		'WP_PLUGIN_URL',
		'UPLOADS',
		'AUTOSAVE_INTERVAL',
		'CONCATENATE_SCRIPTS',
	];

	public $blacklisted = [
		'DB_NAME',
		'DB_USER',
		'DB_PASSWORD',
		'DB_HOST',
		'DB_CHARSET',
		'DB_COLLATE',
		'AUTH_KEY',
		'SECURE_AUTH_KEY',
		'LOGGED_IN_KEY',
		'NONCE_KEY',
		'AUTH_SALT',
		'SECURE_AUTH_SALT',
		'LOGGED_IN_SALT',
		'NONCE_SALT',
	];

    public function __construct( array $constants = [], $is_cli = false ) {
		$file = ABSPATH . 'wp-config.php';
		if ( ! file_exists( $file ) ) {
			if ( @file_exists( dirname( ABSPATH ) . '/wp-config.php' ) ) {
				$file = dirname( ABSPATH ) . '/wp-config.php';
			}
		}

        $this->file   = $file;
		$this->data   = $constants;
		$this->is_cli = $is_cli;
    }

    public function fetch() {
        $constants = [];
		
		$this->data = array_filter( $this->data );
		if ( ! empty( $this->data ) ) {
			$this->constants = array_merge( $this->constants, $this->data );
		}

		if ( ! $this->is_cli ) {
			$constants = array_diff( $this->constants, $this->blacklisted );
		}
		
		try {
			$config  = new \WPConfigTransformer( $this->file );
			$results = [
				'wp-config'           => [],
				'wp-config-undefined' => [],
			];

			foreach ( $constants as $constant ) {
				if ( ! $this->is_cli && preg_match( '/[a-z]/', $constant ) ) {
					continue;
				}

				if ( $config->exists( 'constant', $constant ) ) {
					$value = trim( $config->get_value( 'constant', $constant ), "'" );
					if ( filter_var( $value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) !== null ) {
						$value = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
					} elseif ( filter_var( $value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE ) !== null ) {
						$value = intval( $value );
					}

					$results['wp-config'][ $constant ] = $value;
				} else {
					$results['wp-config-undefined'][ $constant ] = defined( $constant ) ? constant( $constant ) : '';
				}
			}
		} catch ( \Exception $e ) {
			$results = [
				'success' => false,
				'message' => $e->getMessage(),
			];
		}
		
        return $results;
    }

	public function update() {
        $args    = [
			'normalize' => true,
			'add'       => true,
		];
		$content = file_get_contents( $this->file );

		if ( false === strpos( $content, "/* That's all, stop editing!" ) ) {
			preg_match( '@\$table_prefix = (.*);@', $content, $matches );
			$args['anchor']    = isset( $matches[0] ) ? $matches[0] : '';
			$args['placement'] = 'after';
		}

		try {
			$config  = new \WPConfigTransformer( $this->file );
			$results = [ 'success' => true ];

			foreach ( $this->data as $key => $value ) {
				if ( empty( $key ) ) {
					continue;
				}

				if ( ! $this->is_cli && ( preg_match( '/[a-z]/', $key ) || in_array( $key, $this->blacklisted, true ) ) ) {
					continue;
				}

				if ( is_array( $value ) ) {
					if ( ! array_key_exists( 'value', $value ) ) {
						continue;
					}

					$params = [ 'separator', 'add' ];
					foreach ( $params as $param ) {
						if ( array_key_exists( $param, $value ) ) {
							$args[ $param ] = $value[ $param ];
						}
					}
					$args['raw'] = array_key_exists( 'raw', $value ) ? $value['raw'] : true;
					$value       = $value['value'];
				} elseif ( is_bool( $value ) ) {
					$value       = $value ? 'true' : 'false';
					$args['raw'] = true;
				} elseif ( is_numeric( $value ) ) {
					$value       = strval( $value );
					$args['raw'] = true;
				} elseif ( in_array( $value, [ 'true', 'false' ] ) ) {
					$value       = strval( $value );
					$args['raw'] = true;
				} else {
					$value       = sanitize_text_field( wp_unslash( $value ) );
					$args['raw'] = false;
				}

				$config->update( 'constant', $key, $value, $args );
			}
		} catch ( \Exception $e ) {
			$results = [
				'success' => false,
				'message' => $e->getMessage(),
			];
		}

        return $results;
    }

	public function delete() {
		$constants = array_filter( $this->data );
		if ( empty( $constants ) ) {
			return [
				'success' => false,
				'message' => esc_html( 'No constants provided!' ),
			];
		}

        try {
			$config  = new \WPConfigTransformer( $this->file );
			$results = [ 'success' => true ];

			foreach ( $constants as $constant ) {
				$config->remove( 'constant', $constant );
			}
		} catch ( \Exception $e ) {
			$results = [
				'success' => false,
				'message' => $e->getMessage(),
			];
		}

        return $results;
    }
}