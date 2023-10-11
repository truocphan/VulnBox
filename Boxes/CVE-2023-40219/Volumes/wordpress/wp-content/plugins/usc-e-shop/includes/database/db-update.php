<?php
/**
 * Welcart item base class
 *
 * @package  Welcart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Database check.
 *
 * Database update check for each version.
 *
 * @since 2.6
 */
function wel_db_check() {

	if ( ! is_admin() ) {
		return;
	}

	global $usces;

	$update_history = get_option( 'usces_db_version' );
	$action_status  = '';
	$first_install  = wel_is_first_install();

	if ( version_compare( USCES_VERSION, '2.7-beta', '>=' ) ) {
		$action_status = '2.7';
		$usces->create_table();
		if ( ! isset( $update_history[ $action_status ] ) ) {
			if ( $first_install ) {
				$update_history[ $action_status ] = 1;
			} else {
				$update_history[ $action_status ] = 0;
			}
			update_option( 'usces_db_version', $update_history );
		}
	};

	if ( version_compare( USCES_VERSION, '2.6-beta', '>=' ) ) {
		$action_status = '2.6';
		if ( ! isset( $update_history[ $action_status ] ) ) {
			if ( $first_install ) {
				$update_history[ $action_status ] = 1;
			} else {
				$update_history[ $action_status ] = 0;
			}
			update_option( 'usces_db_version', $update_history );
		}
	};
	/*
	if ( version_compare( USCES_VERSION, '2.0-beta', '>=' ) ) {
		$action_status = '2.0';
		if ( ! isset( $update_history[ $action_status ] ) ) {
			$update_history[ $action_status ] = 0;
			update_option( 'usces_db_version', $update_history );
		}
	};
	*/

	$notice_flag = false;
	foreach ( $update_history as $v => $f ) {

		$function_name  = 'wel_update_db_';
		$function_name .= str_replace( '.', '_', $v );
		if ( function_exists( $function_name ) && 0 === (int) $f ) {
			$notice_flag = true;
			break;
		}
	}
	if ( $notice_flag ) {
		add_action( 'admin_notices', 'wel_db_notice' );
	}
}

/**
 * Database update notification.
 *
 * @since 2.6
 */
function wel_db_notice() {
	global $current_screen;
	$update_history = get_option( 'usces_db_version' );
	$update_number  = 0;
	foreach ( $update_history as $key => $flag ) {
		$function_name  = 'wel_update_db_';
		$function_name .= str_replace( '.', '_', $key );
		if ( function_exists( $function_name ) && 0 === (int) $flag ) {
			$update_number++;
		}
	}

	if ( isset( $current_screen->base ) && 'toplevel_page_usc-e-shop/usc-e-shop' === $current_screen->base ) {
		$action = filter_input( INPUT_GET, 'wel_action', FILTER_SANITIZE_STRING, FILTER_REQUIRE_SCALAR );
		if ( 'update_db' === $action ) {
			return;
		}
	}

	$class    = 'notice notice-warning';
	$message1 = __( 'Shop data needs to be updated.', 'usces' );
	$message2 = __( 'Be sure to back up your database before updating.', 'usces' ) . '[Welcart]';
	$message3 = __( 'Make an update', 'usces' );
	// translators: %s: Number of updates.
	$message4  = sprintf( _n( 'There is %s update.', 'There are %s updates.', $update_number, 'usces' ), $update_number );
	$url       = USCES_ADMIN_URL . '?page=' . rawurlencode( 'usc-e-shop/usc-e-shop.php' ) . '&wel_action=update_db';
	$nonce_url = wp_nonce_url( $url, 'wel_update_database', '_welnonce' );

	printf( '<div class="%1$s"><p>%2$s<br>%3$s</p><p><a class="button" href="%6$s">%4$s</a> ( %5$s )</p></div>', esc_attr( $class ), esc_html( $message1 ), esc_html( $message2 ), esc_html( $message3 ), esc_html( $message4 ), esc_url( $nonce_url ) );
}

/**
 * Check if the database needs to be updated.
 *
 * @since 2.6
 * @return boolean Returns true if necessary.
 */
function wel_need_to_update_db() {
	$update_history = get_option( 'usces_db_version' );
	if ( in_array( 0, $update_history, true ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Database update process.
 *
 * @since 2.6
 */
function wel_db_update_ajax() {
	define( 'USCES_DB_UP_INTERBAL', 10 );

	$update_history = get_option( 'usces_db_version' );
	ksort( $update_history );

	foreach ( $update_history as $version => $flag ) {
		$function_name = 'wel_update_db_';
		if ( 0 === $flag ) {
			$function_name .= str_replace( '.', '_', $version );
			$function_name( $version );
			break;
		}
	}
}

/**
 * Database update 2.0.
 *
 * @since  2.6
 * @param string $version Version namber.
 */
function wel_update_db_2_0( $version ) {
	global $wpdb, $usces;

	/**
	 * Preparation process.
	 */
	$log       = '';
	$total_num = 0;
	$comp_num  = 0;
	$err_num   = 0;
	$line_num  = 0;
	$file_info = 'バージョン2.0の更新';
	// translators: %s: Version number.
	$log     .= sprintf( __( 'Version %s update started', 'usces' ), $version ) . "\n";
	$progress = array(
		'info'     => $file_info,
		'status'   => __( 'processing', 'usces' ),
		'i'        => $line_num,
		'all'      => $total_num,
		'log'      => $log,
	);
	wel_record_progress( $progress );

	/**
	 * Update process.
	 */
	$target = $wpdb->get_col(
		$wpdb->prepare( "SELECT meta_id FROM $wpdb->postmeta WHERE meta_key = %s", '_itemPicts' )
	);
	if ( is_array( $target ) ) {
		$total_num = count( $target );
	} else {
		$total_num = 0;
	}

	if ( 0 < $total_num ) {

		for ( $line_num = 0; $line_num < $total_num; $line_num++ ) {

			$meta_id = $target[ $line_num ];

			$res = $wpdb->query(
				$wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_id = %d", $meta_id )
			);
			if ( false === $res ) {
				$err_num++;
			} else {
				$comp_num++;
			}

			if ( 0 === ( $line_num % 20 ) ) {
				$progress = array(
					'info'     => $file_info,
					'status'   => __( 'processing', 'usces' ),
					// translators: %1$s: Number of successes. %2$s: Number of errors.
					'progress' => sprintf( __( 'Successful %1$s lines, Failed %2$s lines.', 'usces' ), $comp_num, $err_num ),
					'i'        => ( $line_num + 1 ),
					'all'      => $total_num,
				);
				wel_record_progress( $progress );
			}
		}

		/**
		 * Final processing.
		 */
		$update_history             = get_option( 'usces_db_version' );
		$update_history[ $version ] = 1;
		update_option( 'usces_db_version', $update_history );

		sleep( 2 );
		$log     .= __( 'Completion', 'usces' ) . "\n";
		$progress = array(
			'info'     => $file_info,
			'status'   => __( 'End', 'usces' ),
			// translators: %1$s: Number of successes. %2$s: Number of errors.
			'progress' => sprintf( __( 'Successful %1$s lines, Failed %2$s lines.', 'usces' ), $comp_num, $err_num ),
			'log'      => $log,
			'i'        => $line_num,
			'all'      => $total_num,
			'flag'     => 'complete',
		);
		wel_record_progress( $progress );
		die( wp_json_encode( $progress ) );

	} else {

		/**
		 * Final processing.
		 */
		$update_history             = get_option( 'usces_db_version' );
		$update_history[ $version ] = 1;
		update_option( 'usces_db_version', $update_history );

		sleep( 2 );
		$log     .= __( 'There was no data to update.', 'usces' ) . "\n";
		$progress = array(
			'info'     => $file_info,
			'status'   => __( 'End', 'usces' ),
			// translators: %1$s: Number of successes. %2$s: Number of errors.
			'progress' => sprintf( __( 'Successful %1$s lines, Failed %2$s lines.', 'usces' ), $comp_num, $err_num ),
			'log'      => $log,
			'i'        => $line_num,
			'all'      => $total_num,
			'flag'     => 'complete',
		);
		wel_record_progress( $progress );
		die( wp_json_encode( $progress ) );
	}
}

/**
 * Database update 2.6.
 *
 * @since  2.6
 * @param string $version Version namber.
 */
function wel_update_db_2_6( $version ) {
	global $wpdb, $usces;

	$time_start = filter_input( INPUT_POST, 'time_start', FILTER_DEFAULT, array( 'options' => array( 'default' => 0 ) ) );
	if ( 0 === (int) $time_start ) {
		$time_start = microtime( true );
	}

	/**
	 * Preparation process.
	 */
	$log         = '';
	$total_num   = 0;
	$work_number = filter_input( INPUT_POST, 'work_number', FILTER_VALIDATE_INT, array( 'options' => array( 'default' => 0 ) ) );
	$comp_num    = filter_input( INPUT_POST, 'comp_num', FILTER_VALIDATE_INT, array( 'options' => array( 'default' => 0 ) ) );
	$err_num     = filter_input( INPUT_POST, 'err_num', FILTER_VALIDATE_INT, array( 'options' => array( 'default' => 0 ) ) );
	$line_num    = 0;
	$file_info   = 'バージョン2.6の更新<br>画像の情報を新様式に整理します。<br>商品点数、商品画像が多い場合は処理に時間がかかります。<p>終了するまで他の操作はせず、そのままお待ちください。</p>';
	// translators: %s: Version number.
	$log     .= sprintf( __( 'Version %s update started', 'usces' ), $version ) . "\n";
	$progress = array(
		'info'     => $file_info,
		'status'   => __( 'processing', 'usces' ),
		'i'        => $line_num,
		'all'      => $total_num,
		'log'      => $log,
	);
	//wel_record_progress( $progress );

	/**
	 * Update process.
	 */
	$target = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT ID FROM $wpdb->posts 
			WHERE (post_status = %s OR post_status = %s OR post_status = %s OR post_status = %s OR post_status LIKE %s ) 
			AND post_type = %s AND post_mime_type = %s",
			'publish',
			'private',
			'future',
			'pending',
			'%draft%',
			'post',
			'item'
		)
	);

	if ( is_array( $target ) ) {
		$total_num = count( $target );
	} else {
		$total_num = 0;
	}

	if ( 0 < $total_num ) {

		for ( $line_num = $work_number; $line_num < $total_num; $line_num++ ) {

			$post_id = $target[ $line_num ];
			$cache   = false;
			wel_make_item_picts( $post_id, $cache );

			$comp_num++;
			$memory = (int) ( memory_get_peak_usage() / ( 1024 * 1024 ) );
			$time   = (int) ( microtime( true ) - $time_start );
			if ( 0 === ( $line_num % 100 ) ) {
				$file_info2  = '<br>[メモリ最大使用量]：' . $memory . 'MB';
				$file_info2 .= '<br>[経過時間]：' . $time . '秒';
				$progress = array(
					'info'     => $file_info . $file_info2,
					'status'   => __( 'processing', 'usces' ),
					// translators: %1$s: Number of successes. %2$s: Number of errors.
					'progress' => sprintf( __( 'Successful %1$s lines, Failed %2$s lines.', 'usces' ), $comp_num, $err_num ),
					'i'        => ( $line_num + 1 ),
					'all'      => $total_num,
				);
				wel_record_progress( $progress );
			}

			if ( 80 < $memory ) {
				$con = array(
					'work_number' => $line_num + 1,
					'comp_num'    => $comp_num,
					'err_num'     => $err_num,
					'time_start'  => $time_start,
					'flag'        => 'continue',
				);
				die( wp_json_encode( $con ) );
			}
		}

		/**
		 * Final processing.
		 */
		$update_history             = get_option( 'usces_db_version' );
		$update_history[ $version ] = 1;
		update_option( 'usces_db_version', $update_history );

		sleep( 2 );
		$memory      = (int) ( memory_get_peak_usage() / ( 1024 * 1024 ) );
		$time        = (int) ( microtime( true ) - $time_start );
		$file_info2  = '<br>[メモリ最大使用量]：' . $memory . 'MB';
		$file_info2 .= '<br>[経過時間]：' . $time . '秒';
		$file_info   = 'バージョン2.6の更新<br>画像の情報を新様式に整理します。<br>商品点数、商品画像が多い場合は処理に時間がかかります。<p>処理が完了しました。</p>';
		$file_info  .= $file_info2;
		$log        .= __( 'Completion', 'usces' ) . "\n";
		$progress    = array(
			'info'     => $file_info,
			'status'   => __( 'End', 'usces' ),
			// translators: %1$s: Number of successes. %2$s: Number of errors.
			'progress' => sprintf( __( 'Successful %1$s lines, Failed %2$s lines.', 'usces' ), $comp_num, $err_num ),
			'log'      => $log,
			'i'        => $line_num,
			'all'      => $total_num,
			'flag'     => 'complete',
		);
		wel_record_progress( $progress );
		die( wp_json_encode( $progress ) );

	} else {
		/**
		 * Final processing.
		 */
		$update_history             = get_option( 'usces_db_version' );
		$update_history[ $version ] = 1;
		update_option( 'usces_db_version', $update_history );

		sleep( 2 );
		$memory      = (int) ( memory_get_peak_usage() / ( 1024 * 1024 ) );
		$time        = (int) ( microtime( true ) - $time_start );
		$file_info2  = '<br>[メモリ最大使用量]：' . $memory . 'MB';
		$file_info2 .= '<br>[経過時間]：' . $time . '秒';
		$file_info   = 'バージョン2.6の更新<br>画像の情報を新様式に整理します。<br>商品点数、商品画像が多い場合は処理に時間がかかります。<p>処理が完了しました。</p>';
		$file_info  .= $file_info2;
		$log        .= __( 'There was no data to update.', 'usces' ) . "\n";
		$progress    = array(
			'info'     => $file_info,
			'status'   => __( 'End', 'usces' ),
			// translators: %1$s: Number of successes. %2$s: Number of errors.
			'progress' => sprintf( __( 'Successful %1$s lines, Failed %2$s lines.', 'usces' ), $comp_num, $err_num ),
			'log'      => $log,
			'i'        => $line_num,
			'all'      => $total_num,
			'flag'     => 'complete',
		);
		wel_record_progress( $progress );
		die( wp_json_encode( $progress ) );
	}
}

/**
 * Make Image post meta.
 *
 * @param integer $post_id item post id.
 * @param boolean $cache Switch of cache.
 */
function wel_make_item_picts( $post_id, $cache = true ) {
	$meta_key     = '_itemPicts';
	$item_picts   = usces_get_post_meta( $post_id, $meta_key, $cache );
	$arr_pict_id  = array();
	if ( is_array( $item_picts ) && 0 === count( $item_picts ) ) {
		// sync value init image from the old rule.
		$item_code = get_post_meta( $post_id, '_itemCode', true );
		if ( ! empty( $item_code ) ) {
			global $usces, $wpdb;
			// get main pict id.
			$main_pict_id = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'attachment' LIMIT 1",
					$item_code
				)
			);
			if ( false !== $main_pict_id && 0 < $main_pict_id ) {
				$arr_pict_id[] = $main_pict_id;
			}
			// get sub pict id.
			if ( ! $usces->options['system']['subimage_rule'] ) {
				$codestr = $wpdb->esc_like( $item_code ) . '-%';
				$query   = $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title LIKE %s AND post_title <> %s AND post_type = 'attachment' ORDER BY post_title", $codestr, $item_code );
			} else {
				$codestr  = $wpdb->esc_like( $item_code ) . '--%';
				$codestr2 = $wpdb->esc_like( $item_code ) . '\_\_%';
				$query    = $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE (post_title LIKE %s OR post_title LIKE %s) AND post_type = 'attachment' ORDER BY post_title", $codestr, $codestr2 );
			}
			$sub_pict_ids = $wpdb->get_col( $query );
			if ( $sub_pict_ids && is_array( $sub_pict_ids ) ) {
				$arr_pict_id = array_merge( $arr_pict_id, $sub_pict_ids );
			}
		}
		// save to db.
		$arr_pict_id_db = array();
		foreach ( $arr_pict_id as $pict_id ) {
			if ( 0 < $pict_id ) {
				$arr_pict_id_db[] = $pict_id;
			}
		}
		if ( 0 < count( $arr_pict_id_db ) ) {
			$arr_pict_id_db = array_unique( $arr_pict_id_db );
			$pict_ids       = implode( ';', $arr_pict_id_db );
		} else {
			$pict_ids = null;
		}
		update_post_meta( $post_id, $meta_key, $pict_ids );
	}
}

/**
 * Database update 2.7.
 *
 * @since  2.7
 * @param string $version Version namber.
 */
function wel_update_db_2_7( $version ) {
	global $wpdb, $usces;

	set_time_limit( 0 );

	$usces->update_db_2_7 = 1;

	$time_start = filter_input( INPUT_POST, 'time_start', FILTER_DEFAULT, array( 'options' => array( 'default' => 0 ) ) );
	if ( 0 === (int) $time_start ) {
		$time_start = microtime( true );
	}
	$get_ini      = ini_get_all();
	$memory_limit = str_replace( 'M', '', $get_ini['memory_limit']['global_value'] );
	if ( false !== strpos( $memory_limit, 'G' ) ) {
		$memory_limit = str_replace( 'G', '', $memory_limit );
		$memory_limit = $memory_limit * 1024;
	}
	$mlmit = ceil( $memory_limit * 0.5 );
	if ( 150 < $mlmit ) {
		$mlmit = 150;
	}

	/**
	 * Preparation process.
	 */
	$log         = '';
	$total_num   = 0;
	$work_number = filter_input( INPUT_POST, 'work_number', FILTER_VALIDATE_INT, array( 'options' => array( 'default' => 0 ) ) );
	$comp_num    = filter_input( INPUT_POST, 'comp_num', FILTER_VALIDATE_INT, array( 'options' => array( 'default' => 0 ) ) );
	$err_num     = filter_input( INPUT_POST, 'err_num', FILTER_VALIDATE_INT, array( 'options' => array( 'default' => 0 ) ) );
	$line_num    = 0;
	$file_info   = 'バージョン2.7の更新<br>商品データを新様式に整理します。<br>商品点数が多い場合は処理に時間がかかります。<p>終了するまで他の操作はせず、そのままお待ちください。</p>';
	$file_info  .= '<p>PHP Memory Limit : ' . $memory_limit . 'M</p>';
	// translators: %s: Version number.
	$log     .= sprintf( __( 'Version %s update started', 'usces' ), $version ) . "\n";
	$progress = array(
		'info'     => $file_info,
		'status'   => __( 'processing', 'usces' ),
		'i'        => $line_num,
		'all'      => $total_num,
		'log'      => $log,
	);

	/**
	 * Update process.
	 */

	// Common Option data.
	$com_opt = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM {$wpdb->postmeta} WHERE `post_id` = %d AND `meta_key` = %s",
			USCES_CART_NUMBER,
			'_iopt_'
		)
	);
	foreach ( (array) $com_opt as $data_opt ) {
		wel_backup_remove_data( $data_opt );
		$meta_id = $data_opt->meta_id;
		$post_id = $data_opt->post_id;
		$opt     = unserialize( $data_opt->meta_value );
		$res     = wel_update_opt_data_by_id( $meta_id, $post_id, $opt );
	}

	// Start.
	$target = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT ID FROM $wpdb->posts 
			WHERE (post_status = %s OR post_status = %s OR post_status = %s OR post_status = %s OR post_status LIKE %s ) 
			AND post_type = %s AND post_mime_type = %s",
			'publish',
			'private',
			'future',
			'pending',
			'%draft%',
			'post',
			'item'
		)
	);
	if ( is_array( $target ) ) {
		$total_num = count( $target );
	} else {
		$total_num = 0;
	}

	if ( 0 < $total_num ) {

		$item_table = $wpdb->prefix . 'usces_item';
		$sku_table  = $wpdb->prefix . 'usces_skus';
		$opt_table  = $wpdb->prefix . 'usces_opts';

		for ( $line_num = $work_number; $line_num < $total_num; $line_num++ ) {

			$post_id = $target[ $line_num ];

			// Item data.
			$res_meta = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$wpdb->postmeta} WHERE `post_id` = %d",
					$post_id
				)
			);
			$item    = array();
			$WelItem = new Welcart\ItemData( $post_id, false );

			foreach ( $res_meta as $meta ) {

				$value_arr    = array();
				$reserved_key = ltrim( $meta->meta_key, '_' );

				if ( array_key_exists( $reserved_key, $WelItem->get_item_format() ) ) {

					wel_backup_remove_data( $meta );
					$item[ $reserved_key ] = maybe_unserialize( $meta->meta_value );
				}
			}

			if ( ! empty( $item ) ) {
				wel_update_item_data( $item, $post_id );
			}

			// SKU data.
			$res_sku = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$wpdb->postmeta} WHERE `post_id` = %d AND `meta_key` = %s",
					$post_id,
					'_isku_'
				)
			);
			foreach ( (array) $res_sku as $data_sku ) {
				wel_backup_remove_data( $data_sku );
				$meta_id = $data_sku->meta_id;
				$sku     = unserialize( $data_sku->meta_value );
				$res     = wel_update_sku_data_by_id( $meta_id, $post_id, $sku );
			}

			// Option data.
			$res_opt = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$wpdb->postmeta} WHERE `post_id` = %d AND `meta_key` = %s",
					$post_id,
					'_iopt_'
				)
			);
			foreach ( (array) $res_opt as $data_opt ) {
				wel_backup_remove_data( $data_opt );
				$meta_id = $data_opt->meta_id;
				$opt     = unserialize( $data_opt->meta_value );
				$res     = wel_update_opt_data_by_id( $meta_id, $post_id, $opt );
			}

			$comp_num++;
			$memory = (int) ( memory_get_peak_usage() / ( 1024 * 1024 ) );
			$time   = (int) ( microtime( true ) - $time_start );
			if ( 0 === ( $line_num % 200 ) ) {
				$file_info2  = '<br>[メモリ最大使用量]：' . $memory . 'MB';
				$file_info2 .= '<br>[経過時間]：' . $time . '秒';
				$progress = array(
					'info'     => $file_info . $file_info2,
					'status'   => __( 'processing', 'usces' ),
					// translators: %1$s: Number of successes. %2$s: Number of errors.
					'progress' => sprintf( __( 'Successful %1$s lines, Failed %2$s lines.', 'usces' ), $comp_num, $err_num ),
					'i'        => ( $line_num + 1 ),
					'all'      => $total_num,
				);
				wel_record_progress( $progress );
			}

			if ( $mlmit < $memory ) {
				$con = array(
					'work_number' => $line_num + 1,
					'comp_num'    => $comp_num,
					'err_num'     => $err_num,
					'time_start'  => $time_start,
					'flag'        => 'continue',
				);
				die( wp_json_encode( $con ) );
			}
		}

		$memory      = (int) ( memory_get_peak_usage() / ( 1024 * 1024 ) );
		$time        = (int) ( microtime( true ) - $time_start );
		$file_info2  = '<br>[メモリ最大使用量]：' . $memory . 'MB';
		$file_info2 .= '<br>[経過時間]：' . $time . '秒';
		$file_info   = 'Post Meta の削除中';
		$file_info  .= $file_info2;
		$log        .= 'Post Meta の削除中' . "\n";
		$progress    = array(
			'info'     => $file_info,
			'status'   => __( 'processing', 'usces' ),
			// translators: %1$s: Number of successes. %2$s: Number of errors.
			'progress' => sprintf( __( 'Successful %1$s lines, Failed %2$s lines.', 'usces' ), $comp_num, $err_num ),
			'log'      => $log,
			'i'        => $line_num,
			'all'      => $total_num,
		);
		wel_record_progress( $progress );

		$update_history             = get_option( 'usces_db_version' );
		$update_history[ $version ] = 1;
		update_option( 'usces_db_version', $update_history );

		$key_map = $WelItem->get_item_old_key();
		$format  = array( '%s' );
		foreach ( $key_map as $newkey => $oldkey ) {
			$where = array( 'meta_key' => $oldkey );
			$wpdb->delete( $wpdb->postmeta, $where, $format );
		}

		$where  = array( 'meta_key' => '_isku_' );
		$wpdb->delete( $wpdb->postmeta, $where, $format );

		$where  = array( 'meta_key' => '_iopt_' );
		$wpdb->delete( $wpdb->postmeta, $where, $format );

		$wpdb->query( "OPTIMIZE TABLE `{$wpdb->postmeta}`" );

		/**
		 * Final processing.
		 */

		$memory      = (int) ( memory_get_peak_usage() / ( 1024 * 1024 ) );
		$time        = (int) ( microtime( true ) - $time_start );
		$file_info2  = '<br>[メモリ最大使用量]：' . $memory . 'MB';
		$file_info2 .= '<br>[経過時間]：' . $time . '秒';
		$file_info   = '<p>処理が完了しました。</p>';
		$file_info  .= $file_info2;
		$log        .= '削除完了'. "\n";
		$progress    = array(
			'info'     => $file_info,
			'status'   => __( 'End', 'usces' ),
			// translators: %1$s: Number of successes. %2$s: Number of errors.
			'progress' => sprintf( __( 'Successful %1$s lines, Failed %2$s lines.', 'usces' ), $comp_num, $err_num ),
			'log'      => $log,
			'i'        => $line_num,
			'all'      => $total_num,
			'flag'     => 'complete',
		);
		wel_record_progress( $progress );
		die( wp_json_encode( $progress ) );

	} else {
		/**
		 * Final processing.
		 */
		$update_history             = get_option( 'usces_db_version' );
		$update_history[ $version ] = 1;
		update_option( 'usces_db_version', $update_history );

		sleep( 2 );
		$memory      = (int) ( memory_get_peak_usage() / ( 1024 * 1024 ) );
		$time        = (int) ( microtime( true ) - $time_start );
		$file_info2  = '<br>[メモリ最大使用量]：' . $memory . 'MB';
		$file_info2 .= '<br>[経過時間]：' . $time . '秒';
		$file_info   = 'バージョン2.7の更新<br>商品データを新様式に整理します。<br>商品点数が多い場合は処理に時間がかかります。<p>処理が完了しました。</p>';
		$file_info  .= $file_info2;
		$log        .= __( 'There was no data to update.', 'usces' ) . "\n";
		$progress    = array(
			'info'     => $file_info,
			'status'   => __( 'End', 'usces' ),
			// translators: %1$s: Number of successes. %2$s: Number of errors.
			'progress' => sprintf( __( 'Successful %1$s lines, Failed %2$s lines.', 'usces' ), $comp_num, $err_num ),
			'log'      => $log,
			'i'        => $line_num,
			'all'      => $total_num,
			'flag'     => 'complete',
		);
		wel_record_progress( $progress );

		unset( $usces->update_db_2_7 );

		die( wp_json_encode( $progress ) );
	}
}

/**
 * Record progress.
 *
 * @since  2.6
 * @param array $arr_content Content.
 */
function wel_record_progress( $arr_content ) {

	$upload_folder = WP_CONTENT_DIR . USCES_UPLOAD_TEMP . '/';
	$mkdir         = wp_mkdir_p( $upload_folder );
	$progress_file = $upload_folder . 'db-progress.txt';
	$log_file      = $upload_folder . 'db-log.txt';

	if ( $mkdir ) {

		if ( ( isset( $arr_content['status'] ) || isset( $arr_content['progress'] ) ) ) {
			file_put_contents( $progress_file, wp_json_encode( $arr_content ), LOCK_EX );
		}

		if ( isset( $arr_content['log'] ) ) {
			if ( 'clear' === $arr_content['log'] ) {
				file_put_contents( $log_file, '', LOCK_EX );
			} elseif ( isset( $arr_content['flag'] ) && 'complete' === $arr_content['flag'] ) {
				$add_text = $arr_content['log'];
				file_put_contents( $log_file, $add_text, LOCK_EX );
			}
		}
	}
}

/**
 * Backup DB.
 *
 * @since  2.7
 * @param int    $post_id Data.
 * @param string $key Data.
 * @param string $value Data.
 */
function wel_backup_remove_data( $db_data, $flag = null ) {

	$upload_folder = WP_CONTENT_DIR . USCES_UPLOAD_TEMP . '/';
	$mkdir         = wp_mkdir_p( $upload_folder );
	$today         = current_time( 'Ymd' );
	$backup_file   = $upload_folder . 'db-backup' . $today . '.csv';
	$data          = wp_json_encode( $db_data ) . "\n";

	if ( $mkdir ) {

		if ( 'clear' === $flag ) {
			file_put_contents( $backup_file, '', LOCK_EX );
		} else {
			file_put_contents( $backup_file, $data, FILE_APPEND );
		}

	} else {
		die( 'Not found the required working directory.' );
	}
}

/**
 * Check progress.
 *
 * @since  2.6
 */
function wel_check_progress_ajax() {
	if ( 4 > usces_get_admin_user_level() ) {
		die( 'user_level' );
	}

	$progressfile = filter_input( INPUT_POST, 'progressfile' );

	sleep( 1 );
	// Make sure the file is exist.
	if ( usces_is_reserved_file( $progressfile ) ) {
		// Get the content and echo it.
		$text = file_get_contents( $progressfile );
		die( $text );
	} else {
		die( "logfile dosn't exist" );
	}
	exit;
}
