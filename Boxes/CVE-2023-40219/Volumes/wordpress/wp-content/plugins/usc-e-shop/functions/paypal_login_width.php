<?php
/***************************************************************************
 * Function: Run CURL
 * Description: Executes a CURL request
 * Parameters: url (string) - URL to make request to
 *             method (string) - HTTP transfer method
 *             headers - HTTP transfer headers
 *             postvals - post values
 **************************************************************************/
function usces_run_curl($url, $method = 'GET', $postvals = null){
    $ch = curl_init($url);
    
    //GET request: send headers and return data transfer
    if ($method == 'GET'){
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSLVERSION => 1 
        );
        curl_setopt_array($ch, $options);
    //POST / PUT request: send post object and return data transfer
    } else {
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_VERBOSE => 1,
            CURLOPT_POSTFIELDS => $postvals,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSLVERSION => 1 
        );
        curl_setopt_array($ch, $options);
    }
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

function usces_login_with_openid($email) {
	global $wpdb, $usces;
	
	$member_table = usces_get_tablename( 'usces_member' );
	$query = $wpdb->prepare("SELECT * FROM $member_table WHERE mem_email = %s LIMIT 1", $email);
	$member = $wpdb->get_row( $query, ARRAY_A );
	
	if ( empty($member) ) {
	
		return false;
		
	} else {
	
		$_SESSION['usces_member']['ID'] = $member['ID'];
		$_SESSION['usces_member']['mailaddress1'] = $member['mem_email'];
		$_SESSION['usces_member']['mailaddress2'] = $member['mem_email'];
		$_SESSION['usces_member']['point'] = $member['mem_point'];
		$_SESSION['usces_member']['name1'] = $member['mem_name1'];
		$_SESSION['usces_member']['name2'] = $member['mem_name2'];
		$_SESSION['usces_member']['name3'] = $member['mem_name3'];
		$_SESSION['usces_member']['name4'] = $member['mem_name4'];
		$_SESSION['usces_member']['zipcode'] = $member['mem_zip'];
		$_SESSION['usces_member']['pref'] = $member['mem_pref'];
		$_SESSION['usces_member']['address1'] = $member['mem_address1'];
		$_SESSION['usces_member']['address2'] = $member['mem_address2'];
		$_SESSION['usces_member']['address3'] = $member['mem_address3'];
		$_SESSION['usces_member']['tel'] = $member['mem_tel'];
		$_SESSION['usces_member']['fax'] = $member['mem_fax'];
		$_SESSION['usces_member']['delivery_flag'] = $member['mem_delivery_flag'];
		$_SESSION['usces_member']['delivery'] = !empty($member['mem_delivery']) ? unserialize($member['mem_delivery']) : '';
		$_SESSION['usces_member']['registered'] = $member['mem_registered'];
		$_SESSION['usces_member']['nicename'] = $member['mem_nicename'];
		$_SESSION['usces_member']['country'] = $usces->get_member_meta_value('customer_country', $member['ID']);
		$_SESSION['usces_member']['status'] = $member['mem_status'];
		$usces->set_session_custom_member($member['ID']);
		$usces->get_current_member();
		
		do_action( 'usces_action_after_login_with_paypal' );
		return apply_filters( 'usces_filter_login_with_paypal', $member['ID'], $member );
	}
}
