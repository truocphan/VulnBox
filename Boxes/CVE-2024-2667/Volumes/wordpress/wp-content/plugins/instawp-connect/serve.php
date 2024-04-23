<?php
set_time_limit( 0 );
error_reporting( 0 );

$migrate_key   = isset( $_POST['migrate_key'] ) ? $_POST['migrate_key'] : '';
$api_signature = isset( $_POST['api_signature'] ) ? $_POST['api_signature'] : '';

if ( empty( $migrate_key ) ) {
	header( 'x-iwp-status: false' );
	header( 'x-iwp-message: Invalid migrate key.' );
	die();
}

function get_wp_root_directory( $find_with_files = 'wp-load.php', $find_with_dir = '' ) {

	$is_find_root_dir = true;
	$root_path        = '';
	$searching_tier   = 10;

	if ( ! empty( $find_with_files ) ) {
		$level            = 0;
		$root_path_dir    = __DIR__;
		$root_path        = __DIR__;
		$is_find_root_dir = true;

		while ( ! file_exists( $root_path . DIRECTORY_SEPARATOR . $find_with_files ) ) {
			++$level ;

			$path_parts = explode( DIRECTORY_SEPARATOR, $root_path );
			array_pop( $path_parts ); // Remove the last directory
			$root_path = implode( DIRECTORY_SEPARATOR, $path_parts );

			if ( $level > $searching_tier ) {
				$is_find_root_dir = false;
				break;
			}
		}
	}

	if ( ! empty( $find_with_dir ) ) {
		$level            = 0;
		$root_path_dir    = __DIR__;
		$root_path        = __DIR__;
		$is_find_root_dir = true;
		while ( ! is_dir( $root_path . DIRECTORY_SEPARATOR . $find_with_dir ) ) {
			++$level ;
			$path_parts = explode( DIRECTORY_SEPARATOR, $root_path );
			array_pop( $path_parts ); // Remove the last directory
			$root_path = implode( DIRECTORY_SEPARATOR, $path_parts );

			if ( $level > $searching_tier ) {
				$is_find_root_dir = false;
				break;
			}
		}
	}

	return array(
		'status'    => $is_find_root_dir,
		'root_path' => $root_path,
	);
}

$root_dir_data = get_wp_root_directory();
$root_dir_find = isset( $root_dir_data['status'] ) ? $root_dir_data['status'] : false;
$root_dir_path = isset( $root_dir_data['root_path'] ) ? $root_dir_data['root_path'] : '';

if ( ! $root_dir_find ) {
	$root_dir_data = get_wp_root_directory( '', 'flywheel-config' );
	$root_dir_find = isset( $root_dir_data['status'] ) ? $root_dir_data['status'] : false;
	$root_dir_path = isset( $root_dir_data['root_path'] ) ? $root_dir_data['root_path'] : '';
}

if ( ! $root_dir_find ) {
	header( 'x-iwp-status: false' );
	header( 'x-iwp-message: Could not find wp-config.php in the parent directories.' );
	echo "Could not find wp-config.php in the parent directories.";
	exit( 2 );
}

defined( 'CHUNK_SIZE' ) | define( 'CHUNK_SIZE', 2 * 1024 * 1024 );
defined( 'BATCH_ZIP_SIZE' ) | define( 'BATCH_ZIP_SIZE', 50 );
defined( 'MAX_ZIP_SIZE' ) | define( 'MAX_ZIP_SIZE', 1024 * 1024 ); //1mb
defined( 'CHUNK_DB_SIZE' ) | define( 'CHUNK_DB_SIZE', 100 );
defined( 'BATCH_SIZE' ) | define( 'BATCH_SIZE', 100 );
defined( 'WP_ROOT' ) | define( 'WP_ROOT', $root_dir_path );
defined( 'INSTAWP_BACKUP_DIR' ) | define( 'INSTAWP_BACKUP_DIR', WP_ROOT . DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'instawpbackups' . DIRECTORY_SEPARATOR );

$iwpdb_main_path = WP_ROOT . '/wp-content/plugins/instawp-connect/includes/class-instawp-iwpdb.php';
$iwpdb_git_path  = WP_ROOT . '/wp-content/plugins/instawp-connect-main/includes/class-instawp-iwpdb.php';

if ( file_exists( $iwpdb_main_path ) && is_readable( $iwpdb_main_path ) ) {
	require_once( $iwpdb_main_path );
} elseif ( file_exists( $iwpdb_git_path ) && is_readable( $iwpdb_main_path ) ) {
	require_once( $iwpdb_git_path );
} else {
	header( 'x-iwp-status: false' );
	header( 'x-iwp-message: Could not find class-instawp-iwpdb in the plugin directory.' );
	header( 'x-iwp-root-path: ' . WP_ROOT );
	echo "Could not find class-instawp-iwpdb in the plugin directory.";
	exit( 2 );
}

global $tracking_db;

try {
	$tracking_db = new IWPDB( $migrate_key );
} catch ( Exception $e ) {
	header( 'x-iwp-status: false' );
	header( 'x-iwp-message: Database connection error. Actual error: ' . $e->getMessage() );
	die();
}

if ( ! $tracking_db ) {
	header( 'x-iwp-status: false' );
	header( 'x-iwp-message: Could not find tracking database.' );
	die();
}

$db_api_signature = $tracking_db->get_option( 'api_signature' );

if ( $db_api_signature !== $api_signature ) {
	header( 'x-iwp-status: false' );
	header( 'x-iwp-message: Mismatched api signature. Signature in db is: ' . $db_api_signature );
	die();
}

if ( isset( $_REQUEST['serve_type'] ) && 'files' === $_REQUEST['serve_type'] ) {

	if ( ! function_exists( 'readfile_chunked' ) ) {
		function readfile_chunked( $filename, $retbytes = true ) {
			$cnt    = 0;
			$handle = fopen( $filename, 'rb' );

			if ( $handle === false ) {
				return false;
			}

			while ( ! feof( $handle ) ) {
				$buffer = fread( $handle, CHUNK_SIZE );
				echo $buffer;
				ob_flush();
				flush();

				if ( $retbytes ) {
					$cnt += strlen( $buffer );
				}
			}

			$status = fclose( $handle );

			if ( $retbytes && $status ) {
				return $cnt;
			}

			return $status;
		}
	}

	if ( ! function_exists( 'send_by_zip' ) ) {
		function send_by_zip( IWPDB $tracking_db, $unsentFiles = array(), $progress_percentage = '', $archiveType = 'ziparchive', $handle_config_separately = false ) {
			header( 'Content-Type: zip' );
			header( 'x-file-type: zip' );
			header( 'x-iwp-progress: ' . $progress_percentage );

			$tmpZip = tempnam( sys_get_temp_dir(), 'batchzip' );

			if ( $archiveType === 'ziparchive' ) {
				$archive = new ZipArchive();

				if ( $archive->open( $tmpZip, ZipArchive::OVERWRITE ) !== true ) {
					die( "Cannot open zip archive" );
				}
			} elseif ( $archiveType === 'phardata' ) {
				$tmpZip  .= '.zip';
				$archive = new PharData( $tmpZip );
			} else {
				die( "Invalid archive type" );
			}

			header( 'x-iwp-filename: ' . $tmpZip );

			foreach ( $unsentFiles as $file ) {
				$filePath         = isset( $file['filepath'] ) ? $file['filepath'] : '';
				$relativePath     = ltrim( str_replace( WP_ROOT, "", $filePath ), DIRECTORY_SEPARATOR );
				$filePath         = process_files( $tracking_db, $filePath, $relativePath );
				$file_fopen_check = fopen( $filePath, 'r' );
				$file_name        = basename( $filePath );

				if ( ! $file_fopen_check ) {
					error_log( 'Can not open file: ' . $filePath );
					continue;
				}

				fclose( $file_fopen_check );

				if ( ! is_readable( $filePath ) ) {
					error_log( 'Can not read file: ' . $filePath );
					continue;
				}

				if ( ! is_file( $filePath ) ) {
					error_log( 'Invalid file: ' . $filePath );
					continue;
				}

				if ( $handle_config_separately && $file_name === 'wp-config.php' ) {
					$relativePath = $file_name;
				}

				$added_to_zip = $archive->addFile( $filePath, $relativePath );

				if ( ! $added_to_zip ) {
					error_log( 'Could not add to zip. File: : ' . $filePath );
				}
			}

			try {
				if ( $archiveType === 'ziparchive' ) {
					$archive->close();
				}

				readfile_chunked( $tmpZip );
			} catch ( Exception $exception ) {
				header( 'x-iwp-status: false' );
				header( 'x-iwp-message: Error in reading file. Message - ' . $exception->getMessage() );
			}

			foreach ( $unsentFiles as $file ) {
				$tracking_db->update( 'iwp_files_sent', array( 'sent' => 1 ), array( 'id' => $file['id'] ) );
			}

			unlink( $tmpZip );
		}
	}

	if ( ! function_exists( 'search_and_comment_specific_line' ) ) {
		function search_and_comment_specific_line( $pattern, $file_contents ) {

			$matches = array();

			if ( preg_match_all( $pattern, $file_contents, $matches, PREG_OFFSET_CAPTURE ) ) {
				foreach ( $matches[0] as $match ) {
					$line_content  = strtok( substr( $file_contents, $match[1] ), "\n" );
					$file_contents = str_replace( $line_content, "// $line_content", $file_contents );
				}
			}

			return $file_contents;
		}
	}

	if ( ! function_exists( 'process_files' ) ) {
		function process_files( IWPDB $tracking_db, $filePath, $relativePath ) {
			$site_url         = $tracking_db->get_option( 'site_url' );
			$dest_url         = $tracking_db->get_option( 'dest_url' );
			$migrate_settings = $tracking_db->get_option( 'migrate_settings' );
			$options          = isset( $migrate_settings['options'] ) ? $migrate_settings['options'] : array();

			if ( basename( $relativePath ) === '.htaccess' ) {

				$content  = file_get_contents( $filePath );
				$tmp_file = tempnam( sys_get_temp_dir(), 'htaccess' );

				// RSSR Support
				$pattern = '/#Begin Really Simple SSL Redirect.*?#End Really Simple SSL Redirect/s';
				$content = preg_replace( $pattern, '', $content );

				// MalCare Support
				$pattern = '/#MalCare WAF.*?#END MalCare WAF/s';
				$content = preg_replace( $pattern, '', $content );

				// Comment any any php_value
				$content = preg_replace( '/^\s*php_value\s+/m', '# php_value ', $content );
				$content = preg_replace( '/^\s*php_flag\s+/m', '# php_flag ', $content );

				// Comment some unnecessary lines in htaccess
				$content = preg_replace( '/^(.*AuthGroupFile.*)$/m', '# $1', $content );
				$content = preg_replace( '/^(.*AuthUserFile.*)$/m', '# $1', $content );
				$content = preg_replace( '/^(.*AuthName.*)$/m', '# $1', $content );
				$content = preg_replace( '/^(.*ErrorDocument.*)$/m', '# $1', $content );


				if ( ! empty( $site_url ) && ! empty( $dest_url ) ) {
					$url_path = parse_url( $site_url, PHP_URL_PATH );

					if ( ! empty( $url_path ) && $url_path !== '/' ) {
						$content = str_replace( $url_path, '/', $content );
					}

					if ( in_array( 'skip_media_folder', $options ) ) {
						$htaccess_content = array(
							'## BEGIN InstaWP Connect',
							'<IfModule mod_rewrite.c>',
							'RewriteEngine On',
							'RewriteCond %{REQUEST_FILENAME} !-f',
							'RewriteRule ^wp-content/uploads/(.*)$ ' . $site_url . '/wp-content/uploads/$1 [R=301,L]',
							'</IfModule>',
							'## END InstaWP Connect',
						);
						$htaccess_content = implode( "\n", $htaccess_content );
						$content          = $htaccess_content . "\n\n" . $content;
					}
				}

				if ( file_put_contents( $tmp_file, $content ) ) {
					$filePath = $tmp_file;
				}
			} elseif ( $relativePath === 'wp-config.php' ) {
				$file_contents = file_get_contents( $filePath );
				$file_contents = str_replace( $site_url, $dest_url, $file_contents );

				// Flywheel support
				$file_contents = str_replace( "define('ABSPATH', dirname(__FILE__) . '/.wordpress/');", "define( 'ABSPATH', dirname( __FILE__ ) . '/' );", $file_contents );

				// Comment WP_SITEURL constant
				$file_contents = search_and_comment_specific_line( "/define\(\s*'WP_SITEURL'/", $file_contents );

				// Comment WP_HOME constant
				$file_contents = search_and_comment_specific_line( "/define\(\s*'WP_HOME'/", $file_contents );

				// Comment COOKIE_DOMAIN constant
				$file_contents = search_and_comment_specific_line( "/define\(\s*'COOKIE_DOMAIN'/", $file_contents );

				$tmp_file = tempnam( sys_get_temp_dir(), 'wp-config' );
				if ( file_put_contents( $tmp_file, $file_contents ) ) {
					$filePath = $tmp_file;
				}
			} elseif ( $relativePath === 'index.php' ) {
				$file_contents = file_get_contents( $filePath );
				$file_contents = str_replace( "/.wordpress/wp-blog-header.php", "/wp-blog-header.php", $file_contents );

				$tmp_file = tempnam( sys_get_temp_dir(), 'index' );
				if ( file_put_contents( $tmp_file, $file_contents ) ) {
					$filePath = $tmp_file;
				}
			}

			return $filePath;
		}
	}

	if ( ! function_exists( 'is_valid_file' ) ) {
		function is_valid_file( $filepath ) {
			$filename = basename( $filepath );

			return is_file( $filepath ) && is_readable( $filepath ) && ( preg_match( '/^[a-zA-Z0-9_.@\s-]+$/', $filename ) === 1 );
		}
	}

//  $total_files_path         = INSTAWP_BACKUP_DIR . '.total-files-' . $migrate_key;
	$migrate_settings         = $tracking_db->get_option( 'migrate_settings' );
	$excluded_paths           = isset( $migrate_settings['excluded_paths'] ) ? $migrate_settings['excluded_paths'] : array();
	$skip_folders             = array_merge( array( 'wp-content/cache', 'editor', 'wp-content/upgrade', 'wp-content/instawpbackups' ), $excluded_paths );
	$skip_folders             = array_unique( $skip_folders );
	$skip_files               = array();
	$config_file_path         = WP_ROOT . '/wp-config.php';
	$handle_config_separately = false;

	if ( ! file_exists( $config_file_path ) ) {
		$config_file_path = dirname( WP_ROOT ) . '/wp-config.php';

		if ( file_exists( $config_file_path ) ) {
			$handle_config_separately = true;
		} else {
			header( 'x-iwp-status: false' );
			header( 'x-iwp-message: WP Config file not found even in the one step above folder.' );
			die();
		}
	}

	$unsent_files_count  = $tracking_db->query_count( 'iwp_files_sent', array( 'sent' => '0' ) );
	$progress_percentage = 0;

	if ( $totalFiles = (int) $tracking_db->db_get_option( 'total_files', '0' ) ) {
		$total_files_count   = $tracking_db->query_count( 'iwp_files_sent' );
		$total_files_sent    = $total_files_count - $unsent_files_count;
		$progress_percentage = round( ( $total_files_sent / $totalFiles ) * 100, 2 );
	}

	if ( $unsent_files_count == 0 ) {

		$filter_directory = function ( SplFileInfo $file, $key, RecursiveDirectoryIterator $iterator ) use ( $skip_folders ) {

			$relative_path = ! empty( $iterator->getSubPath() ) ? $iterator->getSubPath() . '/' . $file->getBasename() : $file->getBasename();

			if ( in_array( $relative_path, $skip_folders ) ) {
				return false;
			}

			return ! in_array( $iterator->getSubPath(), $skip_folders );
		};
		$directory        = new RecursiveDirectoryIterator( WP_ROOT, RecursiveDirectoryIterator::SKIP_DOTS | RecursiveDirectoryIterator::FOLLOW_SYMLINKS );
		$iterator         = new RecursiveIteratorIterator( new RecursiveCallbackFilterIterator( $directory, $filter_directory ), RecursiveIteratorIterator::LEAVES_ONLY, RecursiveIteratorIterator::CATCH_GET_CHILD );

		// Get the current file index from the database or file
		$currentFileIndex = (int) $tracking_db->db_get_option( 'current_file_index', '0' );

		// Create a limited iterator to skip the files that are already indexed
		$limitedIterator = array();
		try {
			$limitedIterator = new LimitIterator( $iterator, $currentFileIndex, BATCH_SIZE );
		} catch ( Exception $e ) {
			header( 'x-iwp-status: false' );
			header( 'x-iwp-message: limitIterator error. Actual error: ' . $e->getMessage() );
			die();
		}

		$totalFiles = iterator_count( $iterator );
		$fileIndex  = 0;

		if ( $handle_config_separately ) {
			$totalFiles            += 1;
			$config_file_size      = filesize( $config_file_path );
			$config_file_path_hash = hash( 'sha256', $config_file_size );

			$tracking_db->insert( 'iwp_files_sent', array(
				'filepath'      => "'$config_file_path'",
				'filepath_hash' => "'$config_file_path_hash'",
				'sent'          => 0,
				'size'          => "'$config_file_size'",
			) );
		}

		$tracking_db->db_update_option( 'total_files', $totalFiles );

		foreach ( $limitedIterator as $file ) {
			$filepath = $file->getPathname();

			if ( ! is_valid_file( $filepath ) ) {
				continue;
			}

			$filesize      = $file->getSize();
			$filepath_hash = hash( 'sha256', $filepath );
			$currentDir    = str_replace( WP_ROOT . '/', '', $file->getPath() );
			$row           = $tracking_db->get_row( 'iwp_files_sent', array( 'filepath_hash' => $filepath_hash ) );

			if ( ! $row ) {
				try {
					$tracking_db->insert( 'iwp_files_sent', array(
						'filepath'      => "'$filepath'",
						'filepath_hash' => "'$filepath_hash'",
						'sent'          => 0,
						'size'          => "'$filesize'",
					) );
					++ $fileIndex;
				} catch ( Exception $e ) {
					header( 'x-iwp-status: false' );
					header( 'x-iwp-message: Insert to iwp_files_sent failed. Actual error: ' . $e->getMessage() );
					die();
				}
			} else {
				continue;
			}

			// If we have indexed enough files, break the loop
			if ( $fileIndex > BATCH_SIZE ) {
				break;
			}
		}

		$current_file_index = ( $currentFileIndex + BATCH_SIZE );
		$ret                = $tracking_db->db_update_option( 'current_file_index', $current_file_index );

		if ( $fileIndex == 0 ) {
			header( 'x-iwp-status: true' );
			header( 'x-iwp-transfer-complete: true' );
			header( 'x-iwp-message: No more files left to download as FileIndex is 0. current_file_index: ' . json_encode( $ret ) );
			exit;
		}

		$tracking_db->create_file_indexes( 'iwp_files_sent', array(
			'idx_sent'      => 'sent',
			'idx_file_size' => 'size',
		) );
	}

	//TODO: this query runs every time even if there are no files to zip, may be we can
	//cache the result in first time and don't run the query

	$is_archive_available = false;
	$unsentFiles          = array();

	if ( class_exists( 'ZipArchive' ) || class_exists( 'PharData' ) ) {
		$is_archive_available   = true;
		$unsent_files_query_res = $tracking_db->query( "SELECT id,filepath,size FROM iwp_files_sent WHERE sent = 0 and size < " . MAX_ZIP_SIZE . " ORDER by size LIMIT " . BATCH_ZIP_SIZE );

		$tracking_db->fetch_rows( $unsent_files_query_res, $unsentFiles );
	}

	if ( $is_archive_available && count( $unsentFiles ) > 0 ) {
		if ( class_exists( 'ZipArchive' ) ) {
			// ZipArchive is available
			send_by_zip( $tracking_db, $unsentFiles, $progress_percentage, 'ziparchive', $handle_config_separately );
		} elseif ( class_exists( 'PharData' ) ) {
			// PharData is available
			send_by_zip( $tracking_db, $unsentFiles, $progress_percentage, 'phardata', $handle_config_separately );
		} else {
			// Neither ZipArchive nor PharData is available
			die( "No archive library available!" );
		}
	} else {

		$row = $tracking_db->get_row( 'iwp_files_sent', array( 'sent' => '0' ) );

		if ( $row ) {
			$fileId       = $row['id'];
			$filePath     = $row['filepath'];
			$file_name    = basename( $filePath );
			$relativePath = ltrim( str_replace( WP_ROOT, "", $filePath ), DIRECTORY_SEPARATOR );
			$filePath     = process_files( $tracking_db, $filePath, $relativePath );

			if ( $handle_config_separately && $file_name === 'wp-config.php' ) {
				$relativePath = $file_name;
			}

			header( 'Content-Type: application/octet-stream' );
			header( 'x-file-relative-path: ' . $relativePath );
			header( 'x-iwp-progress: ' . $progress_percentage );
			header( 'x-file-type: single' );

			if ( file_exists( $filePath ) && is_file( $filePath ) ) {
				readfile_chunked( $filePath );
			}

			$tracking_db->update( 'iwp_files_sent', array( 'sent' => '1' ), array( 'id' => $fileId ) );
		} else {
			header( 'x-iwp-status: true' );
			header( 'x-iwp-transfer-complete: true' );
			header( 'x-iwp-message: No more files left to download according to iwp_files_sent table.' );
		}
	}
}

if ( isset( $_REQUEST['serve_type'] ) && 'db' === $_REQUEST['serve_type'] ) {

	$migrate_settings = $tracking_db->get_option( 'migrate_settings' );
	$db_host          = $tracking_db->get_option( 'db_host' );
	$db_username      = $tracking_db->get_option( 'db_username' );
	$db_password      = $tracking_db->get_option( 'db_password' );
	$db_name          = $tracking_db->get_option( 'db_name' );

	if ( empty( $db_host ) || empty( $db_username ) || empty( $db_password ) || empty( $db_name ) ) {
		header( 'x-iwp-status: false' );
		header( 'x-iwp-message: Database information missing.' );
		die();
	}

	$excluded_tables       = isset( $migrate_settings['excluded_tables'] ) ? $migrate_settings['excluded_tables'] : array();
	$excluded_tables_rows  = isset( $migrate_settings['excluded_tables_rows'] ) ? $migrate_settings['excluded_tables_rows'] : array();
	$total_tracking_tables = $tracking_db->query_count( 'iwp_db_sent' );

	// Skip our files sent table
	if ( ! in_array( 'iwp_files_sent', $excluded_tables ) ) {
		$excluded_tables[] = 'iwp_files_sent';
	}

	// Skip our db sent table
	if ( ! in_array( 'iwp_db_sent', $excluded_tables ) ) {
		$excluded_tables[] = 'iwp_db_sent';
	}

	if ( $total_tracking_tables == 0 ) {
		foreach ( $tracking_db->get_all_tables() as $table_name => $rows_count ) {
			if ( ! in_array( $table_name, $excluded_tables ) ) {
				$table_name_hash = hash( 'sha256', $table_name );
				$tracking_db->insert( 'iwp_db_sent', array(
					'table_name'      => "'$table_name'",
					'table_name_hash' => "'$table_name_hash'",
					'rows_total'      => $rows_count,
				) );
			}
		}
	}

	$result = $tracking_db->get_row( 'iwp_db_sent', array( 'completed' => '0' ) );

	if ( empty( $result ) ) {
		header( 'x-iwp-status: true' );
		header( 'x-iwp-transfer-complete: true' );
		header( 'x-iwp-message: No more tables to process.' );
		die();
	}

	$curr_table_name = isset( $result['table_name'] ) ? $result['table_name'] : '';
	$offset          = isset( $result['offset'] ) ? $result['offset'] : '';
	$sqlStatements   = array();

	// Check if it's the first batch of rows for this table
	if ( $offset == 0 && $create_table_sql = $tracking_db->query( "SHOW CREATE TABLE `$curr_table_name`" ) ) {
		$createRow = $create_table_sql->fetch_assoc();
		echo $createRow['Create Table'] . ";\n\n";
	}

	if ( ! in_array( $curr_table_name, $excluded_tables ) ) {

		$where_clause = '1';

		if ( isset( $excluded_tables_rows[ $curr_table_name ] ) && is_array( $excluded_tables_rows[ $curr_table_name ] ) && ! empty( $excluded_tables_rows[ $curr_table_name ] ) ) {

			$where_clause_arr = array();

			foreach ( $excluded_tables_rows[ $curr_table_name ] as $excluded_info ) {

				$excluded_info_arr = explode( ':', $excluded_info );
				$column_name       = isset( $excluded_info_arr[0] ) ? $excluded_info_arr[0] : '';
				$column_value      = isset( $excluded_info_arr[1] ) ? $excluded_info_arr[1] : '';

				if ( ! empty( $column_name ) && ! empty( $column_value ) ) {
					$where_clause_arr[] = "{$column_name} != '{$column_value}'";
				}
			}

			$where_clause = implode( ' AND ', $where_clause_arr );
		}

		$result = $tracking_db->query( "SELECT * FROM `$curr_table_name` WHERE {$where_clause} LIMIT " . CHUNK_DB_SIZE . " OFFSET $offset" );

		if ( ! $result ) {
			header( 'x-iwp-status: false' );
			header( 'x-iwp-message: Database query error - ' . $tracking_db->last_error );
			die();
		}

		while ( $dataRow = $result->fetch_assoc() ) {
			$columns         = array_map( function ( $value ) {

				global $tracking_db;

				if ( is_array( $value ) && empty( $value ) ) {
					return array();
				} elseif ( is_string( $value ) && empty( $value ) ) {
					return '';
				}

				return $tracking_db->conn->real_escape_string( $value );
			}, array_keys( $dataRow ) );
			$values          = array_map( function ( $value ) {

				global $tracking_db;

				if ( is_numeric( $value ) ) {
					return $value;
				} elseif ( is_null( $value ) ) {
					return "NULL";
				} elseif ( is_array( $value ) && empty( $value ) ) {
					$value = array();
				} elseif ( is_string( $value ) ) {
					$value = $tracking_db->conn->real_escape_string( $value );
				}

				return "'" . $value . "'";
			}, array_values( $dataRow ) );
			$sql             = "INSERT IGNORE INTO `$curr_table_name` (`" . implode( "`, `", $columns ) . "`) VALUES (" . implode( ", ", $values ) . ");";
			$sqlStatements[] = $sql;
		}
	}

	$sql_statements_count = count( $sqlStatements );
	$curr_table_info      = $tracking_db->get_row( 'iwp_db_sent', array( 'table_name_hash' => hash( 'sha256', $curr_table_name ) ) );
	$offset               += $sql_statements_count;

	$all_tables     = $tracking_db->get_rows( 'iwp_db_sent' );
	$rows_total_all = 0;
	$finished_total = 0;

	foreach ( $all_tables as $table_data ) {
		$rows_total_all += isset( $table_data['rows_total'] ) ? $table_data['rows_total'] : 0;
		$finished_total += isset( $table_data['offset'] ) ? $table_data['offset'] : 0;
	}

	// Update the offset and rows_finished
	$tracking_db->update( 'iwp_db_sent', array( 'offset' => $offset ), array( 'table_name_hash' => hash( 'sha256', $curr_table_name ) ) );

	// Mark table as completed if all rows were fetched
	if ( count( $sqlStatements ) < CHUNK_DB_SIZE ) {
		$tracking_db->update( 'iwp_db_sent', array( 'completed' => '1' ), array( 'table_name_hash' => hash( 'sha256', $curr_table_name ) ) );
	}

	$completed_tables   = $tracking_db->query_count( 'iwp_db_sent', array( 'completed' => '1' ) );
	$tracking_progress  = $completed_tables === 0 || $total_tracking_tables === 0 ? 0 : number_format( ( $completed_tables * 100 ) / $total_tracking_tables, 2, '.', '' );
	$row_based_progress = number_format( $finished_total / $rows_total_all * 100, 2, '.', '' );
	$avg_progress       = round( ( (float) $row_based_progress + (float) $tracking_progress ) / 2 );

	header( "x-iwp-progress: $avg_progress" );

	echo implode( "\n", $sqlStatements );
}