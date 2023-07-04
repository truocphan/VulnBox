<?php
// to save positions of apps in DB
function mo_openid_sso_sort_action() {
	$nonce = sanitize_text_field( $_POST['mo_openid_sso_sort_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-sso-sort' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		if ( current_user_can( 'administrator' ) ) {
			$app_sequence = array_map( 'sanitize_text_field', $_POST['sequence'] );
			$app_pos      = '';
			$flag         = 0;
			foreach ( $app_sequence as $app_position ) {
				if ( $flag == 0 ) {
					$app_pos = $app_position;
					$flag++;
				} else {
					$app_pos .= '#' . $app_position;
				}
			}
			update_option( 'app_pos', $app_pos );
			wp_send_json( 'hello_sort' );
		}
	}
}

// To enable and disable apps
function mo_openid_sso_enable_app() {
	$nonce = sanitize_text_field( $_POST['mo_openid_sso_enable_app_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-sso-enable-app' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		if ( current_user_can( 'administrator' ) ) {
			$enable_app = sanitize_text_field( $_POST['enabled'] );
			if ( $enable_app == 'true' ) {
				update_option( 'mo_openid_' . sanitize_text_field( $_POST['app_name'] ) . '_enable', 1 );
			} elseif ( $enable_app == 'false' ) {
				update_option( 'mo_openid_' . sanitize_text_field( $_POST['app_name'] ) . '_enable', 0 );
			}
		}
	}
}

// to load instructions of custom app
function mo_openid_app_instructions_action() {
	$nonce = sanitize_text_field( $_POST['mo_openid_app_instructions_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-app-instructions' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		if ( current_user_can( 'administrator' ) ) {
			$social_app   = sanitize_text_field( $_POST['app_name'] );
			$instructions = PLUGIN_URL . $social_app . '.png##';
			$appslist     = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
			if ( $appslist != '' ) {
				if ( ! isset( $appslist[ $social_app ]['clientid'] ) ) {
					$instructions .= '##';
				} else {
					$instructions .= $appslist[ $social_app ]['clientid'] . '##';
				}
				if ( ! isset( $appslist[ $social_app ]['clientsecret'] ) ) {
					$instructions .= '##';
				} else {
					$instructions .= $appslist[ $social_app ]['clientsecret'] . '##';
				}
			} else {
				$instructions .= '####';
			}
			if ( get_option( 'mo_openid_enable_custom_app_' . $social_app ) ) {
				$instructions .= 'custom##';
			}
			// elseif (mo_openid_is_customer_registered()&& get_option('mo_openid_'.$social_app.'_enable'))
			elseif ( get_option( 'mo_openid_' . $social_app . '_enable' ) ) {
				$instructions .= 'default##';
			} else {
				$instructions .= '0##';
			}
			if ( get_option( 'mo_openid_' . $social_app . '_enable' ) ) {
				$instructions .= '1##';
			} else {
				$instructions .= '0##';
			}
			$name = plugin_dir_path( dirname( __DIR__ ) );
			$name = substr( $name, 0, strlen( $name ) - 1 ) . '//social_apps//' . $social_app . '.php';
			require $name;
			$mo_appname = 'mo_' . $social_app;
			$social_app = new $mo_appname();
			if ( ! isset( $appslist[ sanitize_text_field( $_POST['app_name'] ) ]['scope'] ) ) {
				$instructions .= $social_app->scope . '##';
			} else {
				$instructions .= $appslist[ sanitize_text_field( $_POST['app_name'] ) ]['scope'] . '##';
			}
			if ( isset( $social_app->video_url ) ) {
				$instructions .= $social_app->video_url . '##';
			} else {
				$instructions .= '##';
			}
			$instructions .= $social_app->instructions;
			wp_send_json( $instructions );
		}
	}
}

function mo_openid_capp_details_action() {
	$nonce = sanitize_text_field( $_POST['mo_openid_capp_details_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-capp-details' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		if ( current_user_can( 'administrator' ) ) {
			$clientid     = stripslashes( sanitize_text_field( $_POST['app_id'] ) );
			$clientsecret = stripslashes( sanitize_text_field( $_POST['app_secret'] ) );
			$scope        = stripslashes( sanitize_text_field( $_POST['app_scope'] ) );
			$appname      = stripslashes( sanitize_text_field( $_POST['app_name'] ) );
			if ( get_option( 'mo_openid_apps_list' ) ) {
				$appslist = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
			} else {
				$appslist = array();
			}
			$newapp = array();
			foreach ( $appslist as $key => $currentapp ) {
				if ( $appname == $key ) {
					$newapp = $currentapp;
					break;
				}
			}
			$newapp['clientid']     = $clientid;
			$newapp['clientsecret'] = $clientsecret;
			$newapp['scope']        = $scope;
			if ( $appname == 'facebook' ) {
				$newapp['resolution'] = get_option( 'facebook_profile_pic_resolution' );
			}
			$appslist[ $appname ] = $newapp;
			update_option( 'mo_openid_apps_list', maybe_serialize( $appslist ) );
			update_option( 'mo_openid_enable_custom_app_' . $appname, '1' );
			update_option( 'mo_openid_' . $appname . '_enable', 1 );
		}
	}
}

function mo_openid_capp_delete() {

	$nonce = sanitize_text_field( $_POST['mo_openid_capp_delete_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-capp-delete' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		if ( current_user_can( 'administrator' ) ) {
			$appname  = stripslashes( sanitize_text_field( $_POST['app_name'] ) );
			$appslist = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
			$status   = get_option( 'mo_openid_enable_custom_app_' . $appname );
			foreach ( $appslist as $key => $app ) {
				if ( $appname == $key ) {
					unset( $appslist[ $key ] );
				}
			}
			if ( ! empty( $appslist ) ) {
				update_option( 'mo_openid_apps_list', maybe_serialize( $appslist ) );
			} else {
				delete_option( 'mo_openid_apps_list' );
			}
			update_option( 'mo_openid_enable_custom_app_' . $appname, '0' );
			wp_send_json( array( 'status' => $status ) );
		}
	}
}

function mo_disable_app() {
	$nonce = sanitize_text_field( $_POST['mo_openid_disable_app_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-disable-app-nonce' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		if ( current_user_can( 'administrator' ) ) {
			$appname = sanitize_text_field( $_POST['app_name'] );
			update_option( 'mo_openid_enable_custom_app_' . $appname, 0 );
			update_option( 'mo_openid_' . $appname . '_enable', 0 );
		}
	}
}

function mo_openid_test_configuration_update_action() {
	$nonce = sanitize_text_field( $_POST['mo_openid_test_configuration_update_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-test-configuration-update-nonce' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		if ( current_user_can( 'administrator' ) ) {
			update_option( 'mo_openid_test_configuration', 1 );
		}
	}
}

function attribute_url() {
	$url = home_url();
	return $url;
}

function custom_app_enable_change_update() {
	$nonce = sanitize_text_field( $_POST['mo_openid_custom_app_enable_change_update_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-custom-app-enable-change-nonce' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		if ( current_user_can( 'administrator' ) ) {
			$appname = stripslashes( sanitize_text_field( $_POST['appname'] ) );
			if ( sanitize_text_field( $_POST['custom_app_enable_change'] ) ) {   // set default app
				if ( mo_openid_is_customer_registered() ) {
					update_option( 'mo_openid_' . $appname . '_enable', sanitize_text_field( $_POST['custom_app_enable_change'] ) );
					update_option( 'mo_openid_enable_custom_app_' . $appname, 0 );
					wp_send_json( array( 'status' => 'true' ) );
				} else {
					// wp_send_json(["status" => 'false']);
					wp_send_json( array( 'status' => 'true' ) );
				}
			} else {
				if ( get_option( 'mo_openid_apps_list' ) ) {
					$appslist = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
					if ( ! empty( $appslist[ $appname ]['clientid'] ) && ! empty( $appslist[ $appname ]['clientsecret'] ) ) {
						update_option( 'mo_openid_enable_custom_app_' . $appname, 1 );
						wp_send_json( array( 'status' => 'Turned_Off' ) );
					} else {
						update_option( 'mo_openid_enable_custom_app_' . $appname, 0 );
						wp_send_json( array( 'status' => 'No_cust_app' ) );
					}
				} else {
					wp_send_json( array( 'status' => 'No_cust_app' ) );
				}
			}
		}
	}
}

function mo_register_customer_toggle_update() {
	$nonce = sanitize_text_field( $_POST['mo_openid_customer_toggle_update_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-customer-toggle-update-nonce' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		if ( current_user_can( 'administrator' ) ) {
			if ( mo_openid_is_customer_registered() ) {
				$appname = stripslashes( sanitize_text_field( $_POST['appname'] ) );
				if ( isset( $appname ) ) {
					update_option( 'mo_openid_enable_custom_app_' . $appname, 0 );
				}
				wp_send_json( array( 'status' => true ) );
			} else {
				wp_send_json( array( 'status' => false ) );
			}
		}
	}
}

function mo_openid_check_capp_enable() {
	$nonce = sanitize_text_field( $_POST['mo_openid_check_capp_enable_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-check-capp-enable-nonce' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		if ( current_user_can( 'administrator' ) ) {
			if ( get_option( 'mo_openid_apps_list' ) ) {
				$appslist = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
				$appname  = stripslashes( sanitize_text_field( $_POST['app_name'] ) );
				if ( ! empty( $appslist[ $appname ]['clientid'] ) && ! empty( $appslist[ $appname ]['clientsecret'] ) ) {
					wp_send_json( array( 'status' => true ) );
				} else {
					wp_send_json( array( 'status' => false ) );
				}
			} else {
				wp_send_json( array( 'status' => false ) );
			}
		}
	}
}
