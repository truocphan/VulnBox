<?php
/**
 * Handles database for restoring or creating backups.
 *
 * @package JupiterX_Core\Control_Panel\Database_Manager
 *
 * @since 1.9.0
 *
 * @todo Clean up.
 *
 * phpcs:ignoreFile
 * @SuppressWarnings(PHPMD)
 */

if ( ! class_exists( 'JupiterX_Core_Control_Panel_Database_Manager' ) ) {
	/**
	 * Database manager class.
	 *
	 * @since 1.9.0
	 */
	class JupiterX_Core_Control_Panel_Database_Manager {

		/**
		 * @var object
		 */
		public $errors;

		/**
		 * @var string
		 */
		private $basedir;

		/**
		 * @var string
		 */
		private $backup_dir;

		/**
		 * @var string
		 */
		private $dir_prefix;

		/**
		 * @var string
		 */
		private $baseurl;

		/**
		 * @var string
		 */
		private $backup_url;

		/**
		 * @var object
		 */
		private $jupiterx_filesystem;

		/*====================== MAIN SECTION ============================*/
		/**
		 * The class constructor
		 *
		 * @param $dir_prefix
		 */
		public function __construct( $dir_prefix = 'jupiterx_backups' ) {
			$this->errors     = new WP_Error();
			$this->dir_prefix = $dir_prefix;
			$this->init();
		}

		/**
		 * Initialize the FS
		 *
		 * @var boolean
		 */
		private function init() {

			$wp_upload_dir = wp_upload_dir();

			if ( ! empty( $wp_upload_dir['error'] ) ) {
				$this->errors->add( 'unable_to_create_upload_directory', $wp_upload_dir['error'] );

				return false;
			}

			$this->basedir = $wp_upload_dir['basedir'];

			$this->baseurl = $wp_upload_dir['baseurl'];

			$this->jupiterx_filesystem = new JupiterX_Core_Control_Panel_Filesystem(
				[
					'context' => $this->basedir,
				]
			);

			if ( $this->jupiterx_filesystem->get_error_code() ) {
				$this->errors->add( $this->jupiterx_filesystem->get_error_code(), $this->jupiterx_filesystem->get_error_message() );

				return false;
			}

			$this->backup_dir = trailingslashit( $this->basedir ) . $this->dir_prefix;

			$this->backup_url = trailingslashit( $this->baseurl ) . $this->dir_prefix;

			// Create index html file
			if ( ! $this->jupiterx_filesystem->exists( trailingslashit( $this->backup_dir ) . 'index.html' ) ) {
				$this->jupiterx_filesystem->touch( trailingslashit( $this->backup_dir ) . 'index.html' );
			}

			if ( ! $this->jupiterx_filesystem->exists( trailingslashit( $this->backup_dir ) . '.htaccess' ) ) {
				$htaccess_content = '<IfModule mod_rewrite.c>' . "\n" .
					'RewriteEngine On' . "\n" .
					'RewriteCond %{REQUEST_FILENAME} ^.*(sql|zip)$' . "\n" .
					'RewriteRule . - [R=403,L]' . "\n" .
					'</IfModule>' . "\n";
				$this->jupiterx_filesystem->put_contents( trailingslashit( $this->backup_dir ) . '.htaccess', $htaccess_content );
			}

			return true;
		}

		/**
		 * Backup current site database
		 *
		 * @return boolean
		 */
		public function backup_db() {
			// Do not execute if there was as error in initialization
			if ( is_wp_error( $this->errors ) && $this->errors->get_error_code() ) {
				return false;
			}

			$file_name = 'dump-' . current_time( 'timestamp' ) . '-' . md5( uniqid() );

			$dump_file_name = $file_name . '.sql';
			$dump_file_path = $this->get_backup_dir( $dump_file_name );

			$zip_file_name = $file_name . '.zip';
			$zip_file_path = $this->get_backup_dir( $zip_file_name );

			$dumped = $this->dump_db( $dump_file_path );

			if ( ! $dumped ) {
				$this->errors->add( 'can_not_create_backup_db_file', __( 'Can not create backup db file.', 'jupiterx-core' ) );

				return false;
			}

			if ( $this->jupiterx_filesystem->zip(
				[
					$dump_file_name => $dump_file_path,
				], $zip_file_path
			) ) {
				$this->jupiterx_filesystem->delete( $dump_file_path );
			}

			return true;
		}

		/**
		 * Backup current site media records
		 *
		 * @return boolean
		 */
		public function backup_media_records() {
			// Do not execute if there was as error in initialization
			if ( is_wp_error( $this->errors ) && $this->errors->get_error_code() ) {
				return false;
			}

			$file_name = 'media-' . current_time( 'timestamp' ) . '-' . md5( uniqid() );

			$dump_file_name = $file_name . '.sql';
			$dump_file_path = $this->get_backup_dir( $dump_file_name );

			$dumped = $this->dump_media_records( $dump_file_path );

			if ( ! $dumped ) {
				$this->errors->add( 'can_not_create_media_records_backup_db_file', __( 'Can not create media records backup file.', 'jupiterx-core' ) );

				return false;
			}

			return true;
		}

		/**
		 * Restore current site database
		 *
		 * @return boolean
		 */
		public function restore_latest_db() {
			// Do not execute if there was as error in initialization
			if ( is_wp_error( $this->errors ) && $this->errors->get_error_code() ) {
				return false;
			}

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			global $wpdb;

			$wpdb->suppress_errors = false;
			$wpdb->show_errors     = false;

			/* BEGIN: Get the list of backup files and sort them by created date */
			$list_of_backups = $this->list_of_backups();

			/* BEGIN: Get the lastest backup file by date */
			$latest_backup_file = end( $list_of_backups );

			$regExp = '/dump-(\d+)-(.*)\.(zip|sql)/';

			if ( preg_match( $regExp, $latest_backup_file['full_path'], $matches ) ) {
				if ( 'zip' == $matches[3] ) {
					$unzipfile = $this->jupiterx_filesystem->unzip( $latest_backup_file['full_path'], $this->get_backup_dir() );

					if ( ! $unzipfile ) {
						$this->errors->add( 'error_unzipping_backup_file', __( 'There was an error unzipping the backup file.', 'jupiterx-core' ) );

						return false;
					} else if ( is_wp_error( $unzipfile ) ) {
						$this->errors->add( $unzipfile->get_error_code(), $unzipfile->get_error_message() );

						return false;
					}

					$database_sql_file = $this->get_backup_dir( basename( $latest_backup_file['full_path'], '.zip' ) . '.sql' );
				} else {
					$database_sql_file = $latest_backup_file['full_path'];
				}
			} else {
				$this->errors->add( 'invalid_backup_file_type', __( 'Invalid backup file.', 'jupiterx-core' ) );

				return false;
			}

			/* Check if sql backup file exists and readable */
			if ( ! $this->jupiterx_filesystem->exists( $database_sql_file ) || ! $this->jupiterx_filesystem->is_readable( $database_sql_file ) ) {
				$this->errors->add( 'backup_file_is_not_exists_or_readable', __( 'The backup file is not exists or not readable.', 'jupiterx-core' ) );

				return false;
			}

			/* Define DB Name and error message */
			$database_name = DB_NAME;

			/* BEGIN: Create the Database */
			$sql = "CREATE DATABASE IF NOT EXISTS `$database_name`";
			$wpdb->query( $sql );

			if ( ! empty( $wpdb->last_error ) ) {
				$this->errors->add( 'wpdb_last_error', $wpdb->last_error );

				return false;
			}

			/* BEGIN: Retrieve All Tables from the Database */
			$tables = $this->get_tables();

			/* BEGIN: Drop All Tables from the Database */
			foreach ( $tables as $table ) {
				$wpdb->query( "DROP TABLE IF EXISTS `$database_name`.`$table`" );
			}

			$sql_query = '';

			$file_contents = explode( "\n", $this->jupiterx_filesystem->get_contents( $database_sql_file ) );

			if ( ! empty( $file_contents ) ) {
				foreach	( $file_contents as $line ) {
					// Skip it if it's a comment or empty line
					if ( empty( $line ) || $line === "\n" || substr( $line, 0, 2 ) == '--' ) {
						continue;
					}

					// Contcat the sql query string
					$sql_query .= $line . "\n";

					// If it has a semicolon at the end, it's the end of the query
					if ( substr( trim( $line ), -1, 1 ) == ';' ) {
						if ( strpos( $sql_query, 'CREATE TABLE' ) ) {
							// Run drop table query
							$drop_table_legth     = strpos( $sql_query, ' (', 0 );
							$drop_table           = substr( $sql_query, 0, $drop_table_legth );
							$drop_table_if_exists = str_replace( 'CREATE TABLE', 'DROP TABLE IF EXISTS `' . $database_name . '`.', $drop_table );
							$wpdb->query( $drop_table_if_exists );

							// Run create table query
							dbDelta( $sql_query );
						} else {
							// Run insert record query
							$wpdb->query( $sql_query );
						}

						$sql_query = '';
					}
				}
			}

			if ( ! empty( $wpdb->last_error ) ) {
				$this->errors->add( 'wpdb_last_error', $wpdb->last_error );

				return false;
			}

			// Delete others backup files.
			$list_of_backups = $this->list_of_backups();

			if ( ! empty( $list_of_backups ) && is_array( $list_of_backups ) ) {
				foreach ( $list_of_backups as $list_of_backup ) {
					$this->jupiterx_filesystem->delete( $list_of_backup['full_path'] );
				}
			}

			return true;
		}

		/**
		 * restore current site media records
		 *
		 * @return boolean
		 */
		public function restore_media_records() {
			// Do not execute if there was as error in initialization
			if ( is_wp_error( $this->errors ) && $this->errors->get_error_code() ) {
				return false;
			}

			global $wpdb;

			$wpdb->suppress_errors = false;
			$wpdb->show_errors     = false;

			$list_of_backups = $this->list_of_backups( 'media', 'sql' );

			if ( empty( $list_of_backups ) ) {
				return true;
			}

			$latest_backup = end( $list_of_backups );

			if ( ! isset( $latest_backup['full_path'] ) || ! $this->jupiterx_filesystem->exists( $latest_backup['full_path'] ) || ! $this->jupiterx_filesystem->is_readable( $latest_backup['full_path'] ) ) {
				$this->errors->add( 'media_records_backup_not_exists_or_readable', __( 'Media records backup file is not exists or not readable', 'jupiterx-core' ) );

				return false;
			}

			$sql_query = '';

			$file_contents = explode( "\n", $this->jupiterx_filesystem->get_contents( $latest_backup['full_path'] ) );

			if ( ! empty( $file_contents ) ) {
				$max_id = ( $wpdb->get_var( "SELECT MAX(ID) as id FROM $wpdb->posts" ) + 1 );

				foreach	( $file_contents as $line ) {
					// Skip it if it's empty line
					if ( empty( $line ) || $line === "\n" ) {
						continue;
					}

					// If it has a semicolon at the end, it's the end of the query
					if ( substr( trim( $line ), -1, 1 ) == ';' ) {
						// Replace with new POST ID
						$sql_query = str_replace( [ 'increament_id', 'meta_id' ], [ $max_id, 'NULL' ], $line );

						// Run insert record query
						$wpdb->query( $sql_query );
					}

					if ( 0 === strpos( $line, '---END-QUERY---' ) ) {
						$max_id = ( $wpdb->get_var( "SELECT MAX(ID) as id FROM $wpdb->posts" ) + 1 );
					}
				}
			}

			if ( ! empty( $wpdb->last_error ) ) {
				$this->errors->add( 'wpdb_last_error', $wpdb->last_error );

				return false;
			}

			$this->jupiterx_filesystem->delete( $latest_backup['full_path'] );

			return true;
		}


		/**
		 * Get current backups data stored
		 *
		 * @return array
		 */
		public function is_restore_db() {
			/* BEGIN: Get the list of backup files and sort them by created date */
			$list_of_backups = $this->list_of_backups();

			$result = [
				'list_of_backups'    => $list_of_backups,
				'latest_backup_file' => end( $list_of_backups ),
			];

			return $result;
		}

		/*====================== HELPERS ============================*/

		/**
		 * Get all errors
		 *
		 * @return object
		 */
		public function get_errors() {
			return $this->errors;
		}

		/**
		 * Get error code
		 *
		 * @return string
		 */
		public function get_error_code() {
			return is_wp_error( $this->errors ) && $this->errors->get_error_code() ? $this->errors->get_error_code() : false;
		}

		/**
		 * Get error message
		 *
		 * @return string
		 */
		public function get_error_message() {
			return is_wp_error( $this->errors ) && $this->errors->get_error_code() ? $this->errors->get_error_message() : false;
		}

		/**
		 * Get backup directory
		 *
		 * @param  $append
		 * @return string
		 */
		public function get_backup_dir( $append = '' ) {
			if ( ! empty( $append ) ) {
				return trailingslashit( $this->backup_dir ) . ltrim( $append, '/' );
			} else {
				return $this->backup_dir;
			}
		}

		/**
		 * Get backup url
		 *
		 * @param  $append
		 * @return mixed
		 */
		public function get_backup_url( $append = '' ) {
			if ( ! empty( $append ) ) {
				return trailingslashit( $this->backup_url ) . ltrim( $append, '/' );
			} else {
				return $this->backup_url;
			}
		}

		/**
		 * Get list of avalibale backups
		 *
		 * @param  $prefix
		 * @param  $file_ext
		 * @return array
		 */
		public function list_of_backups( $prefix = 'dump', $file_ext = 'zip,sql' ) {
			$backup_list = [];

			$files = glob( $this->get_backup_dir( $prefix . '-*.{' . $file_ext . '}' ), GLOB_BRACE );

			if ( $files ) {
				ksort( $files );
				$file_exts = explode( ',', $file_ext );
				$regExp    = '/' . $prefix . '-(\d+)-(.*)\.(' . implode( '|', $file_exts ) . ')/';
				foreach ( $files as $file ) {
					if ( preg_match( $regExp, $file, $matches ) ) {
						$backup_list[] = [
							'full_path'    => $this->get_backup_dir( $matches[0] ),
							'full_url'     => $this->get_backup_url( $matches[0] ),
							'name'         => $matches[0],
							'ext'          => $matches[3],
							'created_date' => date( 'Y-m-d H:i:s', $matches[1] ),
						];
					}
				}
			}

			return $backup_list;
		}

		/*====================== HELPERS ABOUT DUMPING ============================*/

		/**
		 * Get database tables for current site
		 *
		 * @author Sofyan Sitorus <sofyan@artbees.net>
		 */
		public function get_tables() {
			global $wpdb;
			$exclude_tables = [
				$wpdb->base_prefix . 'users',
				$wpdb->base_prefix . 'usermeta',
				$wpdb->prefix . 'woocommerce_sessions',
				$wpdb->prefix . 'woocommerce_attribute_taxonomies',
			];
			$multi_site_tables = [
				$wpdb->base_prefix . 'blogs',
				$wpdb->base_prefix . 'blog_versions',
				$wpdb->base_prefix . 'signups',
				$wpdb->base_prefix . 'site',
				$wpdb->base_prefix . 'sitemeta',
				$wpdb->base_prefix . 'sitecategories',
				$wpdb->base_prefix . 'registration_log',
			];
			$current_site_tables = [];
			$current_blog_id     = get_current_blog_id();
			$tables              = $wpdb->get_results( 'SHOW FULL TABLES', ARRAY_N );
			foreach ( $tables as $table ) {
				if ( isset( $table[1] ) && 'VIEW' == $table[1] ) {
					continue;
				}

				if ( in_array( $table[0], $exclude_tables, true ) ) {
					continue;
				}

				if ( is_multisite() ) {
					if ( in_array( $table[0], $multi_site_tables, true ) ) {
						continue;
					}

					if ( is_main_site( $current_blog_id ) ) {
						$regex = '/^' . $wpdb->prefix . '([0-9])+/i';
						if ( preg_match( $regex, $table[0] ) ) {
							continue;
						}
					}

					if ( 0 === strpos( $table[0], $wpdb->prefix ) ) {
						$current_site_tables[] = $table[0];
					}
				} else {
					$current_site_tables[] = $table[0];
				}
			}

			return $current_site_tables;
		}

		/**
		 * Export current site data to local disk
		 *
		 * @param  $dump_file_path
		 * @return boolean
		 */
		private function dump_db( $dump_file_path ) {
			global $wpdb;

			$is_success = $this->jupiterx_filesystem->put_contents( $dump_file_path, '', 0777 );

			if ( $is_success ) {
				/* BEGIN : Prevent saving backup plugin settings in the database dump */
				$options_backup  = get_option( 'wp_db_backup_backups' );
				$settings_backup = get_option( 'wp_db_backup_options' );
				delete_option( 'wp_db_backup_backups' );
				delete_option( 'wp_db_backup_options' );
				/* END : Prevent saving backup plugin settings in the database dump */

				$tables_exclude = get_option( 'wp_db_exclude_table' );
				$tables         = $this->get_tables();

				if ( $tables ) {
					$output = '';

					foreach ( $tables as $table ) {
						if ( empty( $tables_exclude ) || ( ! ( in_array( $table, $tables_exclude, true ) ) ) ) {
							// Create table SQL syntax
							$create_table = $wpdb->get_row( 'SHOW CREATE TABLE ' . $table, ARRAY_N );
							$output .= "\n\n" . $create_table[1] . ";\n\n";

							// Insert records SQL syntax
							$result       = $wpdb->get_results( "SELECT * FROM {$table}", ARRAY_N );
							$result_count = count( $result );

							if ( $result ) {
								for ( $i = 0; $i < $result_count; $i++ ) {
									$row = $result[ $i ];

									$output .= 'INSERT INTO ' . $table . ' VALUES(';

									$row = $this->real_escape_row( $wpdb, $row );

									$output .= '"' . implode( '","', $row ) . '"';

									$output .= ");\n";
								}
							}

							$output .= "\n";
						}
					}

					$this->jupiterx_filesystem->put_contents( $dump_file_path, $output );

					$wpdb->flush();
				}

				/* BEGIN : Prevent saving backup plugin settings in the database dump */
				add_option( 'wp_db_backup_backups', $options_backup );
				add_option( 'wp_db_backup_options', $settings_backup );
				/* END : Prevent saving backup plugin settings in the database dump */

				return $this->jupiterx_filesystem->chmod( $dump_file_path, 0664 );
			}

			return $is_success;
		}

		/**
		 * Export current site media record to local disk
		 *
		 * @param  $dump_file_path
		 * @return boolean
		 */
		private function dump_media_records( $dump_file_path ) {
			global $wpdb;

			$result = $wpdb->get_results( "SELECT * FROM $wpdb->posts where post_type='attachment'", ARRAY_N );

			$is_success = $this->jupiterx_filesystem->put_contents( $dump_file_path, '', 0777 );

			if ( $is_success ) {
				$result = $wpdb->get_results( "SELECT * FROM $wpdb->posts where post_type='attachment'", ARRAY_N );

				// Insert media records SQL syntax
				if ( $result ) {
					$result_count = count( $result );

					$output = '';

					for ( $i = 0; $i < $result_count; $i++ ) {
						$row = $result[ $i ];

						$output .= 'INSERT INTO ' . $wpdb->posts . ' VALUES(';

						$row[0] = 'increament_id';

						$row = $this->real_escape_row( $wpdb, $row );

						$output .= '"' . implode( '","', $row ) . '"';

						$output .= ');' . "\n\n";

						$wpdb->flush();

						$postmeta_result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->postmeta where post_id = %s", $result[$i][0] ), ARRAY_N );

						// Insert media meta records SQL syntax
						if ( $postmeta_result ) {
							$postmeta_result_count = count( $postmeta_result );

							for ( $j = 0; $j < $postmeta_result_count; $j++ ) {
								$postmeta_row = $postmeta_result[ $j ];

								$output .= 'INSERT INTO ' . $wpdb->postmeta . ' VALUES(';

								$postmeta_row[0] = 'meta_id';

								$postmeta_row[1] = 'increament_id';

								$postmeta_row = $this->real_escape_row( $wpdb, $postmeta_row );

								$output .= '"' . implode( '","', $postmeta_row ) . '"';

								$output .= ');' . "\n";
							}

							$output .= "\n\n" . '---END-QUERY---' . "\n\n";
						}

						$output .= "\n\n\n";
					}

					$this->jupiterx_filesystem->put_contents( $dump_file_path, $output );
				}

				return $this->jupiterx_filesystem->chmod( $dump_file_path, 0664 );
			}

			return $is_success;
		}

		/**
		 * Escape row data without placeholder escapes.
		 *
		 * 1.16.0
		 *
		 * @access protected
		 *
		 * @param wpdb $wpdb WP db manager.
		 * @param array $row Table row data.
		 * @return array
		 */
		protected function real_escape_row( $wpdb, $row ) {
			return array_map( function ( $value ) use ( $wpdb ) {
				$value = $wpdb->_real_escape( $value );

				return $wpdb->remove_placeholder_escape( $value );
			}, $row );
		}
	}
}
