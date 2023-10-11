<?php
class usc_e_shop
{

	var $page;   //page action
	var $cart;          //cart object
	var $use_ssl;       //ssl flag
	var $action, $action_status, $error_status;
	var $action_message, $error_message;
	var $itemskus, $itemsku, $current_itemsku, $itemopts, $itemopt, $current_itemopt, $item;
	var $zaiko_status, $payment_structure, $display_mode, $shipping_rule, $nonacting_settlements;
	var $member_status;
	var $options, $mail_para;
	var $login_mail, $current_member, $member_form;
	var $payment_results, $log_flg, $delim, $use_js;
	var $user_level;
	var $paypal;

	public $settlement;

	function __construct()
	{
		global $wpdb, $post, $usces_settings, $usces_states;

		usces_add_role();

		do_action('usces_construct');

		if ( is_admin() ){
		//	clean_term_cache( get_option('usces_item_cat_parent_id'), 'category' );
		}

		$locales = usces_locales();
		foreach($locales as $l){
			$usces_settings['language'][$l] = $l;
		}
		$usces_settings['language']['others'] = __('Follow config.php', 'usces');

		$this->options = get_option('usces');
		if(!isset($this->options['smtp_hostname']) || empty($this->options['smtp_hostname'])){ $this->options['smtp_hostname'] = 'localhost';}
		if(!isset($this->options['delivery_method']) || !is_array($this->options['delivery_method'])) $this->options['delivery_method'] = array();
		if(!isset($this->options['shipping_charge']) || !is_array($this->options['shipping_charge'])) $this->options['shipping_charge'] = array();
		if(!isset($this->options['membersystem_state'])) $this->options['membersystem_state'] = 'activate';
		if(!isset($this->options['membersystem_point'])) $this->options['membersystem_point'] = 'activate';
		if(!isset($this->options['use_ssl'])) $this->options['use_ssl'] = 0;
		if(!isset($this->options['point_coverage'])) $this->options['point_coverage'] = 0;
		if(!isset($this->options['use_javascript'])) $this->options['use_javascript'] = 1;
		if(!isset($this->options['privilege_discount'])) $this->options['privilege_discount'] = '';
		if(!isset($this->options['privilege_point'])) $this->options['privilege_point'] = '';
		if(!isset($this->options['campaign_privilege'])) $this->options['campaign_privilege'] = '';
		if(!isset($this->options['campaign_category'])) $this->options['campaign_category'] = 0;
		if(!isset($this->options['campaign_schedule']['start'])) $this->options['campaign_schedule']['start'] = array();
		if(!isset($this->options['campaign_schedule']['end'])) $this->options['campaign_schedule']['end'] = array();
		if(!isset($this->options['purchase_limit'])) $this->options['purchase_limit'] = '';
		if(!isset($this->options['point_rate'])) $this->options['point_rate'] = '';
		if(!isset($this->options['shipping_rule'])) $this->options['shipping_rule'] = '';
		if(!isset($this->options['company_name'])) $this->options['company_name'] = '';
		if(!isset($this->options['address1'])) $this->options['address1'] = '';
		if(!isset($this->options['address2'])) $this->options['address2'] = '';
		if(!isset($this->options['zip_code'])) $this->options['zip_code'] = '';
		if(!isset($this->options['tel_number'])) $this->options['tel_number'] = '';
		if(!isset($this->options['fax_number'])) $this->options['fax_number'] = '';
		if(!isset($this->options['order_mail'])) $this->options['order_mail'] = '';
		if(!isset($this->options['inquiry_mail'])) $this->options['inquiry_mail'] = '';
		if(!isset($this->options['sender_mail'])) $this->options['sender_mail'] = '';
		if(!isset($this->options['error_mail'])) $this->options['error_mail'] = '';
		if(!isset($this->options['copyright'])) $this->options['copyright'] = '';
		if(!isset($this->options['business_registration_number'])) $this->options['business_registration_number'] = '';
		if(!isset($this->options['postage_privilege'])) $this->options['postage_privilege'] = '';
		if(!isset($this->options['shipping_rule'])) $this->options['shipping_rule'] = '';
		if(!isset($this->options['tax_display'])) $this->options['tax_display'] = 'activate';
		if(!isset($this->options['applicable_taxrate']) || empty($this->options['applicable_taxrate'])) $this->options['applicable_taxrate'] = 'standard';
		if(!isset($this->options['tax_rate'])){
			$this->options['tax_rate'] = '';
			$this->options['tax_method'] = 'cutting';
			$this->options['tax_mode'] = 'include';
			$this->options['tax_target'] = 'products';
		}else{
			if(!isset($this->options['tax_mode'])) $this->options['tax_mode'] = empty($this->options['tax_rate']) ? 'include' : 'exclude';
			if(!isset($this->options['tax_target'])) $this->options['tax_target'] = 'all';
		}
		if(!isset($this->options['tax_rate_reduced'])) $this->options['tax_rate_reduced'] = '';
		if(!isset($this->options['transferee'])) $this->options['transferee'] = '';
		if(!isset($this->options['tatransfer_limit'])) $this->options['tatransfer_limit'] = '';
		if(!isset($this->options['membersystem_state'])) $this->options['membersystem_state'] = 'activate';
		if(!isset($this->options['membersystem_point'])) $this->options['membersystem_point'] = '';
		if(!isset($this->options['point_rate'])) $this->options['point_rate'] = '';
		if(!isset($this->options['start_point'])) $this->options['start_point'] = '';
		if(!isset($this->options['point_coverage'])) $this->options['point_coverage'] = 1;
		if(!isset($this->options['point_assign'])) $this->options['point_assign'] = 1;
		if(!isset($this->options['cod_type'])) $this->options['cod_type'] = 'fix';
		if ( ! isset( $this->options['fee_subject'] ) ) {
			$this->options['fee_subject'] = 'all';
		}
		if(!isset($this->options['address_search'])) $this->options['address_search'] = 'deactivate';
		if(!isset($this->options['newmem_admin_mail'])) $this->options['newmem_admin_mail'] = 0;
		if(!isset($this->options['updmem_admin_mail'])) $this->options['updmem_admin_mail'] = 0;
		if(!isset($this->options['updmem_customer_mail'])) $this->options['updmem_customer_mail'] = 0;
		if(!isset($this->options['delmem_admin_mail'])) $this->options['delmem_admin_mail'] = 1;
		if(!isset($this->options['delmem_customer_mail'])) $this->options['delmem_customer_mail'] = 1;
		if(!isset($this->options['put_customer_name'])) $this->options['put_customer_name'] = '0';
		if(!isset($this->options['email_attach_feature'])) $this->options['email_attach_feature'] = '0';
		if(!isset($this->options['email_attach_file_extension'])) $this->options['email_attach_file_extension'] = 'jpg,png,pdf';
		if(!isset($this->options['email_attach_file_size'])) $this->options['email_attach_file_size'] = 3;
		if(!isset($this->options['add_html_email_option'])) $this->options['add_html_email_option'] = 0;
		if(!isset($this->options['mail_data']['title'])) $this->options['mail_data']['title'] = array('thankyou'=>'','order'=>'','inquiry'=>'','returninq'=>'','membercomp'=>'','completionmail'=>'', 'ordermail'=>'','changemail'=>'','receiptmail'=>'','mitumorimail'=>'','cancelmail'=>'','othermail'=>'');
		if(!isset($this->options['mail_data']['header'])) $this->options['mail_data']['header'] = array('thankyou'=>'','order'=>'','inquiry'=>'','returninq'=>'','membercomp'=>'','completionmail'=>'', 'ordermail'=>'','changemail'=>'','receiptmail'=>'','mitumorimail'=>'','cancelmail'=>'','othermail'=>'');
		if(!isset($this->options['mail_data']['footer'])) $this->options['mail_data']['footer'] = array('thankyou'=>'','order'=>'','inquiry'=>'','returninq'=>'','membercomp'=>'','completionmail'=>'', 'ordermail'=>'','changemail'=>'','receiptmail'=>'','mitumorimail'=>'','cancelmail'=>'','othermail'=>'');
		if(!isset($this->options['cart_page_data']['header'])) $this->options['cart_page_data']['header'] = array('cart'=>'','customer'=>'','delivery'=>'','confirm'=>'','completion'=>'');
		if(!isset($this->options['cart_page_data']['footer'])) $this->options['cart_page_data']['footer'] = array('cart'=>'','customer'=>'','delivery'=>'','confirm'=>'','completion'=>'');
		if(!isset($this->options['cart_page_data']['confirm_notes'])) $this->options['cart_page_data']['confirm_notes'] = '';
		if(!isset($this->options['member_page_data']['header'])) $this->options['member_page_data']['header'] = array('login'=>'','newmember'=>'','newpass'=>'','changepass'=>'','memberinfo'=>'','completion'=>'');
		if(!isset($this->options['member_page_data']['footer'])) $this->options['member_page_data']['footer'] = array('login'=>'','newmember'=>'','newpass'=>'','changepass'=>'','memberinfo'=>'','completion'=>'');
		if(!isset($this->options['member_page_data']['agree_member_exp'])) $this->options['member_page_data']['agree_member_exp'] = '';
		if(!isset($this->options['member_page_data']['agree_member_cont'])) $this->options['member_page_data']['agree_member_cont'] = '';
		if(!isset($this->options['agree_member'])) $this->options['agree_member'] = '';
		if(!isset($this->options['shortest_delivery_time'])) $this->options['shortest_delivery_time'] = '0';
		if(!isset($this->options['delivery_after_days'])) $this->options['delivery_after_days'] = 15;
		if(!isset($this->options['delivery_days'])) $this->options['delivery_days'] = array();
		if(!isset($this->options['delivery_time_limit']['hour'])) $this->options['delivery_time_limit']['hour'] = '00';
		if(!isset($this->options['delivery_time_limit']['min'])) $this->options['delivery_time_limit']['min'] = '00';

		if(!isset($this->options['divide_item'])) $this->options['divide_item'] = 0;
		if(!isset($this->options['itemimg_anchor_rel'])) $this->options['itemimg_anchor_rel'] = '';
		if(!isset($this->options['fukugo_category_orderby'])) $this->options['fukugo_category_orderby'] = 'ID';
		if(!isset($this->options['fukugo_category_order'])) $this->options['fukugo_category_order'] = 'ASC';
		if(!isset($this->options['settlement_path'])) $this->options['settlement_path'] = USCES_PLUGIN_DIR . 'settlement/';
		if(!isset($this->options['logs_path'])) $this->options['logs_path'] = '';
		if(!isset($this->options['use_ssl'])) $this->options['use_ssl'] = 0;
		if(!isset($this->options['ssl_url'])) $this->options['ssl_url'] = '';
		if(!isset($this->options['ssl_url_admin'])) $this->options['ssl_url_admin'] = '';
		if(!isset($this->options['inquiry_id'])) $this->options['inquiry_id'] = '';
		if(!isset($this->options['system']['orderby_itemsku'])) $this->options['system']['orderby_itemsku'] = 0;
		if(!isset($this->options['system']['orderby_itemopt'])) $this->options['system']['orderby_itemopt'] = 0;
		if(!isset($this->options['system']['front_lang'])) $this->options['system']['front_lang'] = usces_get_local_language();
		if(!isset($this->options['system']['currency'])) $this->options['system']['currency'] = usces_get_base_country();
		if(!isset($this->options['system']['addressform'])) $this->options['system']['addressform'] = usces_get_local_addressform();
		if(!isset($this->options['system']['target_market'])) $this->options['system']['target_market'] = usces_get_local_target_market();
		if(!isset($this->options['system']['no_cart_css'])) $this->options['system']['no_cart_css'] = 0;
		if(!isset($this->options['system']['dec_orderID_flag'])) $this->options['system']['dec_orderID_flag'] = 0;
		if(!isset($this->options['system']['dec_orderID_prefix'])) $this->options['system']['dec_orderID_prefix'] = '';
		if(!isset($this->options['system']['dec_orderID_digit'])) $this->options['system']['dec_orderID_digit'] = 8;
		if(!isset($this->options['system']['subimage_rule'])) $this->options['system']['subimage_rule'] = 1;
		if(!isset($this->options['system']['pdf_delivery'])) $this->options['system']['pdf_delivery'] = 0;
		if(!isset($this->options['system']['member_pass_rule_min']) || empty($this->options['system']['member_pass_rule_min'])) $this->options['system']['member_pass_rule_min'] = 6;
		if(!isset($this->options['system']['member_pass_rule_max']) || empty($this->options['system']['member_pass_rule_max'])) $this->options['system']['member_pass_rule_max'] = 30;
		if(!isset($this->options['system']['member_pass_rule_upercase']) || empty($this->options['system']['member_pass_rule_upercase'])) $this->options['system']['member_pass_rule_upercase'] = false;
		if(!isset($this->options['system']['csv_encode_type'])) $this->options['system']['csv_encode_type'] = 0;
		if(!isset($this->options['system']['csv_category_format'])) $this->options['system']['csv_category_format'] = 0;
		if(!isset($this->options['system']['settlement_backup'])) $this->options['system']['settlement_backup'] = 0;
		if(!isset($this->options['system']['settlement_notice'])) $this->options['system']['settlement_notice'] = 0;
		$this->options['system']['base_country'] = usces_get_base_country();
		if(!isset($this->options['province'])) $this->options['province'][$this->options['system']['base_country']] = $usces_states[$this->options['system']['base_country']];
		if(!isset($this->options['system']['pointreduction'])) $this->options['system']['pointreduction'] = usces_get_pointreduction($this->options['system']['currency']);
		if(!isset($this->options['indi_item_name'])){
			$this->options['indi_item_name']['item_name'] = 1;
			$this->options['indi_item_name']['item_code'] = 1;
			$this->options['indi_item_name']['sku_name'] = 1;
			$this->options['indi_item_name']['sku_code'] = 1;
			$this->options['pos_item_name']['item_name'] = 1;
			$this->options['pos_item_name']['item_code'] = 2;
			$this->options['pos_item_name']['sku_name'] = 3;
			$this->options['pos_item_name']['sku_code'] = 4;
		}
		if( !isset($this->options['order_acceptable_label']) ) $this->options['order_acceptable_label'] = __('Order acceptable', 'usces');
		update_option('usces', $this->options);

		$this->check_display_mode();
		$this->error_message = '';
		$this->login_mail = '';
		$this->get_current_member();
		$this->page = '';
		$this->payment_results = array();
		$this->use_js = $this->options['use_javascript'];

		//admin_ssl options
		$this->use_ssl = $this->options['use_ssl'];

		define('USCES_CART_NUMBER', get_option('usces_cart_number'));
		define('USCES_MEMBER_NUMBER', get_option('usces_member_number'));

		if ( $this->use_ssl ) {
			$ssl_url = $this->options['ssl_url'];
			$ssl_url_admin = $this->options['ssl_url_admin'];
			if( $this->is_cart_or_member_page($_SERVER['REQUEST_URI']) || $this->is_inquiry_page($_SERVER['REQUEST_URI']) ){
				define('USCES_FRONT_PLUGIN_URL', $ssl_url_admin . '/wp-content/plugins/' . USCES_PLUGIN_FOLDER);
				define('USCES_COOKIEPATH', preg_replace('|https?://[^/]+|i', '', $ssl_url . '/' ) );
			}else{
				define('USCES_FRONT_PLUGIN_URL', USCES_WP_CONTENT_URL . '/plugins/' . USCES_PLUGIN_FOLDER);
				define('USCES_COOKIEPATH', COOKIEPATH);
			}
			define('USCES_SSL_URL', $ssl_url);
			define('USCES_SSL_URL_ADMIN', $ssl_url_admin);
		}else{
			define('USCES_FRONT_PLUGIN_URL', USCES_WP_CONTENT_URL . '/plugins/' . USCES_PLUGIN_FOLDER);
			define('USCES_SSL_URL', home_url() );
			define('USCES_SSL_URL_ADMIN', site_url());
			define('USCES_COOKIEPATH', COOKIEPATH);
		}
		define('USCES_ITEM_CAT_PARENT_ID', get_option('usces_item_cat_parent_id'));

		$this->nonacting_settlements = array('COD', 'installment', 'transferAdvance', 'transferDeferred');
		$this->zaiko_status = get_option('usces_zaiko_status');
		$this->member_status = get_option('usces_customer_status');
		$this->payment_structure = get_option('usces_payment_structure');
		$this->display_mode = get_option('usces_display_mode');
		define('USCES_MYSQL_VERSION', $wpdb->db_version());
		define('USCES_JP', ('ja' === get_locale() ? true : false));

		$this->settlement = new usces_settlement();

		$this->settlement_notice = get_option( 'usces_settlement_notice' );

		$this->usces_session_start();
	}

	function get_default_post_to_edit30( $post_type = 'post', $create_in_db = false ) {
		global $wpdb;

		$post_title = '';
		if ( !empty( $_REQUEST['post_title'] ) )
			$post_title = esc_html( stripslashes( $_REQUEST['post_title'] ));

		$post_content = '';
		if ( !empty( $_REQUEST['content'] ) )
			$post_content = esc_html( stripslashes( $_REQUEST['content'] ));

		$post_excerpt = '';
		if ( !empty( $_REQUEST['excerpt'] ) )
			$post_excerpt = esc_html( stripslashes( $_REQUEST['excerpt'] ));

		if ( $create_in_db ) {
			// Cleanup old auto-drafts more than 7 days old
			$old_posts = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_status = 'auto-draft' AND DATE_SUB( NOW(), INTERVAL 7 DAY ) > post_date" );
			foreach ( (array) $old_posts as $delete )
				wp_delete_post( $delete, true ); // Force delete
			$post = get_post( wp_insert_post( array( 'post_title' => __( 'Auto Draft' ), 'post_type' => $post_type, 'post_status' => 'auto-draft' ) ) );
		} else {
			$post->ID = 0;
			$post->post_author = '';
			$post->post_date = '';
			$post->post_date_gmt = '';
			$post->post_password = '';
			$post->post_type = $post_type;
			$post->post_status = 'draft';
			$post->to_ping = '';
			$post->pinged = '';
			$post->comment_status = get_option( 'default_comment_status' );
			$post->ping_status = get_option( 'default_ping_status' );
			$post->post_pingback = get_option( 'default_pingback_flag' );
			$post->post_category = get_option( 'default_category' );
			$post->page_template = 'default';
			$post->post_parent = 0;
			$post->menu_order = 0;
		}

		$post->post_content = apply_filters( 'default_content', $post_content, $post );
		$post->post_title   = apply_filters( 'default_title',   $post_title, $post   );
		$post->post_excerpt = apply_filters( 'default_excerpt', $post_excerpt, $post );
		$post->post_name = '';

		return $post;
	}

	function get_default_post_to_edit() {
		global $post;

		$post_title = '';
		if ( !empty( $_REQUEST['post_title'] ) )
			$post_title = esc_html( stripslashes( $_REQUEST['post_title'] ));

		$post_content = '';
		if ( !empty( $_REQUEST['content'] ) )
			$post_content = esc_html( stripslashes( $_REQUEST['content'] ));

		$post_excerpt = '';
		if ( !empty( $_REQUEST['excerpt'] ) )
			$post_excerpt = esc_html( stripslashes( $_REQUEST['excerpt'] ));

		$post->ID = 0;
		$post->post_name = '';
		$post->post_author = '';
		$post->post_date = '';
		$post->post_date_gmt = '';
		$post->post_password = '';
		$post->post_status = 'draft';
		$post->post_type = 'post';
		$post->to_ping = '';
		$post->pinged = '';
		$post->comment_status = get_option( 'default_comment_status' );
		$post->ping_status = get_option( 'default_ping_status' );
		$post->post_pingback = get_option( 'default_pingback_flag' );
		$post->post_category = get_option( 'default_category' );
		$post->post_content = apply_filters( 'default_content', $post_content);
		$post->post_title = apply_filters( 'default_title', $post_title );
		$post->post_excerpt = apply_filters( 'default_excerpt', $post_excerpt);
		$post->page_template = 'default';
		$post->post_parent = 0;
		$post->menu_order = 0;

		return $post;
	}
	function is_cart_or_member_page($link)
	{
		$search = array(('page_id='.USCES_CART_NUMBER), '/usces-cart', ('page_id='.USCES_MEMBER_NUMBER), '/usces-member');
		$flag = false;
		foreach($search as $value){
			$parts = array();
			if( false !== strpos($link, $value) ){
				if( $value == ('page_id='.USCES_CART_NUMBER) ||  $value == ('page_id='.USCES_MEMBER_NUMBER) ){
					$parts = parse_url($link);
					parse_str($parts['query'], $query);
					if( isset( $query['page_id'] ) && ( $query['page_id'] == USCES_CART_NUMBER || $query['page_id'] == USCES_MEMBER_NUMBER ) ){
						$flag = true;
					}
				}else{
					$flag = true;
				}
			}
		}
		return $flag;
	}

	function is_cart_page($link)
	{
		$search = array(('page_id='.USCES_CART_NUMBER), '/usces-cart' );
		$flag = false;
		foreach($search as $value){
			if( false !== strpos($link, $value) ){
				if( $value == ('page_id='.USCES_CART_NUMBER) ){
					$parts = parse_url($link);
					parse_str($parts['query'], $query);
					if( isset( $query['page_id'] ) && $query['page_id'] == USCES_CART_NUMBER ){
						$flag = true;
					}
				}else{
					$flag = true;
				}
			}
		}
		return $flag;
	}

	function is_member_page($link)
	{
		$search = array(('page_id='.USCES_MEMBER_NUMBER), '/usces-member' );
		$flag = false;
		foreach($search as $value){
			if( false !== strpos($link, $value) ){
				if( $value == ('page_id='.USCES_MEMBER_NUMBER) ){
					$parts = parse_url($link);
					parse_str($parts['query'], $query);
					if( $query['page_id'] == USCES_MEMBER_NUMBER ){
						$flag = true;
					}
				}else{
					$flag = true;
				}
			}
		}
		return $flag;
	}

	function is_inquiry_page($link)
	{
		if( empty($this->options['inquiry_id']) )
			return false;

		$search = array(('page_id='.$this->options['inquiry_id']), '/usces-inquiry' );
		$flag = false;
		foreach($search as $value){
			if( false !== strpos($link, $value) ){
				if( $value == ('page_id='.$this->options['inquiry_id']) ){
					$parts = parse_url($link);
					parse_str($parts['query'], $query);
					if( $query['page_id'] == $this->options['inquiry_id'] ){
						$flag = true;
					}
				}else{
					$flag = true;
				}
			}
		}
		return $flag;
	}

	function usces_ssl_page_link($link)
	{
		$parts = parse_url($link);

		if( isset($parts['query']) ){
			parse_str($parts['query'], $query);
		}

		if( false !== strpos($link, '/usces-cart') || (isset( $query['page_id']) && $query['page_id'] == USCES_CART_NUMBER) ){
			$link = USCES_CART_URL;

		}elseif( false !== strpos($link, '/usces-member') || (isset( $query['page_id']) && $query['page_id'] == USCES_MEMBER_NUMBER) ){
			$link = USCES_MEMBER_URL;

		}elseif( !empty($this->options['inquiry_id']) && (false !== strpos($link, '/usces-inquiry') || (isset( $query['page_id']) && $query['page_id'] == $this->options['inquiry_id'])) ){
			$link = USCES_INQUIRY_URL;

		}else{
			$link = str_replace('https://', 'http://', $link);
			$link = apply_filters('usces_ssl_page_link', $link);
		}

		return $link;
	}
	function usces_ssl_contents_link($link)
	{
		if( $this->is_cart_or_member_page($_SERVER['REQUEST_URI']) || $this->is_inquiry_page($_SERVER['REQUEST_URI'])){
			$req = explode('/wp-content/',$link);
			$link = USCES_SSL_URL_ADMIN . '/wp-content/' . $req[1];
		}else{
			$link = apply_filters('usces_ssl_contents_link', $link);
		}
		return $link;
	}

	function ssl_admin_ajax_url(){
		$path = '/wp-admin/admin-ajax.php';
		if( $this->use_ssl && ($this->is_cart_or_member_page($_SERVER['REQUEST_URI']) || $this->is_inquiry_page($_SERVER['REQUEST_URI'])) ){
			$link = USCES_SSL_URL_ADMIN . '/wp-admin/admin-ajax.php';
		}else{
			$link = site_url( $path );
		}
		$link = apply_filters('ssl_admin_ajax_url', $link);
		return $link;
	}

	function usces_ssl_attachment_link($link)
	{
		if( $this->is_cart_or_member_page($_SERVER['REQUEST_URI']) || $this->is_inquiry_page($_SERVER['REQUEST_URI']) ){
			$link = str_replace(site_url(), USCES_SSL_URL_ADMIN, $link);
		}else{
			$link = apply_filters('usces_ssl_attachment_link', $link);
		}
		return $link;
	}

	function usces_ssl_icon_dir_uri($uri)
	{
		if( $this->is_cart_or_member_page($_SERVER['REQUEST_URI']) || $this->is_inquiry_page($_SERVER['REQUEST_URI']) ){
			$uri = USCES_SSL_URL_ADMIN. '/' . WPINC . '/images/crystal';
		}else{
			$uri = apply_filters('usces_ssl_icon_dir_uri', $uri);
		}
		return $uri;
	}

	function usces_ssl_script_link($link)
	{
		if( $this->is_cart_or_member_page($_SERVER['REQUEST_URI']) || $this->is_inquiry_page($_SERVER['REQUEST_URI']) ){
			if(strpos($link, '/wp-content/') !== false){
				$req = explode('/wp-content/',$link, 2);
				$link = USCES_SSL_URL_ADMIN . '/wp-content/' . $req[1];
			}else if(strpos($link, '/wp-includes/') !== false){
				$req = explode('/wp-includes/',$link, 2);
				$link = USCES_SSL_URL_ADMIN . '/wp-includes/' . $req[1];
			}else if(strpos($link, '/wp-admin/') !== false){
				$req = explode('/wp-admin/',$link, 2);
				$link = USCES_SSL_URL_ADMIN . '/wp-admin/' . $req[1];
			}
		}else{
			$link = apply_filters('usces_ssl_script_link', $link);
		}
		return $link;
	}

	function set_action_status($status, $message)
	{
		$this->action_status = $status;
		$this->action_message = $message;
	}

	/******************************************************************************/
	function add_pages() {

		add_menu_page( 'Welcart Shop', 'Welcart Shop', 'level_2', USCES_PLUGIN_BASENAME, array($this, 'admin_top_page'), 'dashicons-cart', '3.0011060' );
		add_submenu_page(USCES_PLUGIN_BASENAME, __('Home','usces'), __('Home','usces'), 'level_2', USCES_PLUGIN_BASENAME, array($this, 'admin_top_page'));
		add_submenu_page(USCES_PLUGIN_BASENAME, __('Master Items','usces'), __('Master Items','usces'), 'level_2', 'usces_itemedit', array($this, 'item_master_page'));
		add_submenu_page(USCES_PLUGIN_BASENAME, __('Add New Item','usces'), __('Add New Item','usces'), 'level_2', 'usces_itemnew', array($this, 'item_master_page'));
		add_submenu_page(USCES_PLUGIN_BASENAME, __('General Setting','usces'), __('General Setting','usces'), 'level_6', 'usces_initial', array($this, 'admin_setup_page'));
		add_submenu_page(USCES_PLUGIN_BASENAME, __('Business Days Setting','usces'), __('Business Days Setting','usces'), 'level_6', 'usces_schedule', array($this, 'admin_schedule_page'));
		add_submenu_page(USCES_PLUGIN_BASENAME, __('Shipping Setting','usces'), __('Shipping Setting','usces'), 'level_6', 'usces_delivery', array($this, 'admin_delivery_page'));
		add_submenu_page(USCES_PLUGIN_BASENAME, __('E-mail Setting','usces'), __('E-mail Setting','usces'), 'level_6', 'usces_mail', array($this, 'admin_mail_page'));
		add_submenu_page(USCES_PLUGIN_BASENAME, __('Cart Page Setting','usces'), __('Cart Page Setting','usces'), 'level_6', 'usces_cart', array($this, 'admin_cart_page'));
		add_submenu_page(USCES_PLUGIN_BASENAME, __('Member Page Setting','usces'), __('Member Page Setting','usces'), 'level_6', 'usces_member', array($this, 'admin_member_page'));
		add_submenu_page(USCES_PLUGIN_BASENAME, __('System Setting','usces'), __('System Setting','usces'), 'administrator', 'usces_system', array($this, 'admin_system_page'));
		add_submenu_page(USCES_PLUGIN_BASENAME, __('Settlement Setting','usces'), __('Settlement Setting','usces'), 'administrator', 'usces_settlement', array($this, 'admin_settlement_page'));
		do_action('usces_action_shop_admin_menue');

		add_menu_page( 'Welcart Management', 'Welcart Management', 'level_5', 'usces_orderlist', array($this, 'order_list_page'), 'dashicons-cart', '3.0011070' );
		add_submenu_page('usces_orderlist', __('Order List','usces'), __('Order List','usces'), 'level_5', 'usces_orderlist', array($this, 'order_list_page'));
		add_submenu_page('usces_orderlist', __('New Order or Estimate','usces'), __('New Order or Estimate','usces'), 'level_5', 'usces_ordernew', array($this, 'order_list_page'));
		add_submenu_page('usces_orderlist', __('List of Members','usces'), __('List of Members','usces'), 'level_5', 'usces_memberlist', array($this, 'member_list_page'));
		add_submenu_page('usces_orderlist', __('New Membership Registration','usces'), __('New Membership Registration','usces'), 'level_5', 'usces_membernew', array($this, 'member_list_page'));
		Log_List_Table::add_submenu_page();
		do_action('usces_action_management_admin_menue');
	}


	/* Item Master Page */
	function item_master_page() {
		global $wpdb, $wp_locale;
		global $wp_query;

		if(empty($this->action_message) || WCUtils::is_blank($this->action_message) ) {
			$this->action_status = 'none';
			$this->action_message = '';
		}

		if($_REQUEST['page'] == 'usces_itemnew'){
			$action = 'new';
		}else{
			$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
		}
		do_action( 'usces_action_item_master_page', $action );
		switch ( $action ) {
			case 'dlitemlist':
				usces_download_item_list();
				break;
			case 'upload_register':
				require_once(USCES_PLUGIN_DIR . '/includes/usces_item_master_upload_register.php');
				break;
			case 'delete':
			case 'new':
			case 'editpost':
			case 'edit':
				global $current_user;
				require_once(USCES_PLUGIN_DIR . '/includes/usces_item_master_edit.php');
				break;
			default:
				require_once(USCES_PLUGIN_DIR . '/includes/usces_item_master_list.php');
				break;
		}
	}

	/* order list page */
	function order_list_page() {
		$order_edit_form = apply_filters( 'usces_admin_order_edit_form', USCES_PLUGIN_DIR . '/includes/order_edit_form.php' );
		$order_list = apply_filters( 'usces_admin_order_list', USCES_PLUGIN_DIR . '/includes/order_list.php' );

		if(empty($this->action_message) || WCUtils::is_blank($this->action_message) ) {
			$this->action_status = 'none';
			$this->action_message = '';
		}
		if($_REQUEST['page'] == 'usces_ordernew'){
			$order_action = 'new';
		}else{
			$order_action = isset($_REQUEST['order_action']) ? $_REQUEST['order_action'] : '';
		}
		do_action('usces_action_order_list_page', $order_action);
		Log_List_Table::add_order_meta_box( $order_action );
		switch ($order_action) {
			case 'dlproductlist':
				usces_download_product_list();
				break;
			case 'dlorderlist':
				usces_download_order_list();
				break;
			case 'dlsettlementerrorlog':
				usces_download_settlement_error_log();
				break;
			case 'editpost':
				check_admin_referer( 'order_edit', 'wc_nonce' );				
				$logger = Logger::start( $_REQUEST['order_id'], 'orderedit', 'update' );
				do_action('usces_pre_update_orderdata', $_REQUEST['order_id']);
				$res = usces_update_orderdata();
				if ( 1 === $res ) {
					$backtolist = ( empty($_POST['usces_referer']) || false !== strpos( $_POST['usces_referer'], 'order_action=editpost' ) ) ? admin_url('admin.php?page=usces_orderlist&returnList=1') : esc_url(stripslashes( $_POST['usces_referer'] ));
					$this->set_action_status('success', __('order date is updated','usces').' <a href="'.$backtolist.'">'.__('back to the summary','usces').'</a>');
				} elseif ( 0 === $res ) {
					$this->set_action_status('none', '');
				} else {
					$this->set_action_status('error', 'ERROR : '.__('failure in update','usces'));
				}
				$logger->flush();
				do_action('usces_after_update_orderdata', $_REQUEST['order_id'], $res);
				require_once($order_edit_form);
				break;
			case 'newpost':
				check_admin_referer( 'order_edit', 'wc_nonce' );
				do_action('usces_pre_new_orderdata');
				$res = usces_new_orderdata();
				if ( 1 === $res ) {
					$this->set_action_status('success', __('New date is add','usces'));
					Logger::start( $_REQUEST['order_id'], 'ordernew', 'create' )->flush();
				} elseif ( 0 === $res ) {
					$this->set_action_status('none', '');
				} else {
					$this->set_action_status('error', 'ERROR : '.__('failure in addition','usces'));
				}
				do_action('usces_after_new_orderdata', $res);
				$_REQUEST['order_action'] = 'edit';
				$order_action = $_REQUEST['order_action'];
				require_once($order_edit_form);
				break;
			case 'new':
			case 'edit':
				require_once($order_edit_form);
				break;
			case 'load_rich_editor': 
				wp_enqueue_style( 'colors' ); 
				require_once( USCES_PLUGIN_DIR . '/includes/order_detail_rich_editor.php' );
				break;
			case 'delete':
				check_admin_referer( 'order_list', 'wc_nonce' );
				do_action('usces_pre_delete_orderdata', $_REQUEST['order_id']);
				$logger = Logger::start( $_REQUEST['order_id'], 'orderlist', 'delete' );
				$res = usces_delete_orderdata();
				if ( 1 === $res ) {
					$this->set_action_status('success', __('the order date is deleted','usces'));
					$logger->flush();
				} elseif ( 0 === $res ) {
					$this->set_action_status('none', '');
				} else {
					$this->set_action_status('error', 'ERROR : '.__('failure in delete','usces'));
				}
				do_action('usces_after_delete_orderdata', $_REQUEST['order_id'], $res);
			default:
				require_once($order_list);
		}
	}

	/* member list page */
	function member_list_page() {
		$member_edit_form = apply_filters( 'usces_admin_member_edit_form', USCES_PLUGIN_DIR . '/includes/member_edit_form.php' );
		$member_list = apply_filters( 'usces_admin_member_list', USCES_PLUGIN_DIR . '/includes/member_list.php' );
		if(empty($this->action_message) || WCUtils::is_blank($this->action_message) ) {
			$this->action_status = 'none';
			$this->action_message = '';
		}
		if( $_REQUEST['page'] == 'usces_membernew' ){
			$member_action = isset($_REQUEST['member_action']) ? $_REQUEST['member_action'] : 'new';
		}else{
			$member_action = isset($_REQUEST['member_action']) ? $_REQUEST['member_action'] : '';
		}
		do_action( 'usces_action_member_list_page', $member_action );
		Log_List_Table::add_member_meta_box( $member_action );
		switch ($member_action) {
			case 'dlmemberlist':
				usces_download_member_list();
				break;
			case 'editpost':
				check_admin_referer( 'post_member', 'wc_nonce');
				$logger = Logger::start( $_REQUEST['member_id'], 'memberedit', 'update');
				$this->error_message = $this->admin_member_check();
				if( WCUtils::is_blank($this->error_message) ){
					$res = usces_update_memberdata();
					if ( 1 === $res ) {
						$this->set_action_status('success', __('Membership information is updated','usces'));
						$logger->flush();
					} elseif ( 0 === $res ) {
						$this->set_action_status('none', '');
					} else {
						$this->set_action_status('error', 'ERROR : '.__('failure in update','usces'));
					}
				}else{
						$this->set_action_status('error', 'ERROR : '.$this->error_message);
				}
				require_once($member_edit_form);
				break;
			case 'newpost':
				check_admin_referer( 'post_member', 'wc_nonce');
				$this->error_message = $this->admin_new_member_check();
				if( WCUtils::is_blank($this->error_message) ){
					$res = usces_new_memberdata();
					if ( 1 === $res ) {
						$this->set_action_status('success', __('New member registration is complete.', 'usces'));
						$_REQUEST['member_action'] = 'edit';
						$member_action = $_REQUEST['member_action'];
						Logger::start( $_REQUEST['member_id'], 'membernew', 'create' )->flush();
					} elseif ( 0 === $res ) {
						$this->set_action_status('none', '');
					} else {
						$this->set_action_status('error', 'ERROR : '.__('Failed to new member registration.','usces'));
					}
				} else {
					$this->set_action_status('error', $this->error_message);
					$member_action = 'new';
				}
				require_once($member_edit_form);
				break;
			case 'new':
			case 'edit':
				require_once($member_edit_form);
				break;
			case 'delete':
				check_admin_referer( 'delete_member', 'wc_nonce');
				if( !isset($_REQUEST['member_id'] ) || WCUtils::is_blank($_REQUEST['member_id']) ) {
					$this->set_action_status('error', 'ERROR : '.__('failure in delete','usces'));
				} else {
					$logger = Logger::start( $_REQUEST['member_id'], 'memberlist', 'delete' );
					$member_id = $_REQUEST['member_id'];
					$del = usces_delete_member_check( $member_id );
					if( $del ) {
						$res = usces_delete_memberdata( $member_id );
						if ( 1 === $res ) {
							$this->set_action_status('success', __('The member data is deleted','usces'));
						} elseif ( 0 === $res ) {
							$this->set_action_status('none', '');
						} else {
							$this->set_action_status('error', 'ERROR : '.__('failure in delete','usces'));
						}
						$logger->flush();
					} else {
						$this->set_action_status('error', 'ERROR : '.__('failure in delete','usces'));
					}
				}
			default:
				require_once($member_list);
		}

	}

	/* admin backup page */
	function admin_backup_page() {

		if(empty($this->action_message) || WCUtils::is_blank($this->action_message) ) {
			$this->action_status = 'none';
			$this->action_message = '';
		}
		require_once(USCES_PLUGIN_DIR . '/includes/admin_backup.php');

	}

	/* Shop Top Page */
	function admin_top_page() {
		$action = filter_input( INPUT_GET, 'wel_action', FILTER_SANITIZE_STRING, FILTER_REQUIRE_SCALAR );
		if ( ! wel_need_to_update_db() ) {
			$action = '';
		}
		switch ( $action ) {
			case 'update_db':
				check_admin_referer( 'wel_update_database', '_welnonce' );
				require_once( USCES_PLUGIN_DIR . '/includes/database/db-progress-screen.php' );
				break;
			default:
				$path = apply_filters( 'usces_filter_admin_top_page', USCES_PLUGIN_DIR . '/includes/admin_top.php' );
				require_once( $path );
		}
	}

	/* Shop Setup Page */
	function admin_setup_page() {
		$this->options = get_option('usces');
		if(isset($_POST['usces_option_update'])) {

			check_admin_referer('admin_setup', 'wc_nonce');

			$_POST = $this->stripslashes_deep_post($_POST);
			$this->options['display_mode'] = isset($_POST['display_mode']) ? trim($_POST['display_mode']) : '';
			$this->options['campaign_category'] = empty($_POST['cat']) ? USCES_ITEM_CAT_PARENT_ID : $_POST['cat'];
			$this->options['campaign_privilege'] = isset($_POST['cat_privilege']) ? trim($_POST['cat_privilege']) : '';
			$this->options['privilege_point'] = isset($_POST['point_num']) ? (int)$_POST['point_num'] : '';
			$this->options['privilege_discount'] = isset($_POST['discount_num']) ? (int)$_POST['discount_num'] : '';
			$this->options['company_name'] = isset($_POST['company_name']) ? trim($_POST['company_name']) : '';
			$this->options['zip_code'] = isset($_POST['zip_code']) ? trim($_POST['zip_code']) : '';
			$this->options['address1'] = isset($_POST['address1']) ? trim($_POST['address1']) : '';
			$this->options['address2'] = isset($_POST['address2']) ? trim($_POST['address2']) : '';
			$this->options['tel_number'] = isset($_POST['tel_number']) ? trim($_POST['tel_number']) : '';
			$this->options['fax_number'] = isset($_POST['fax_number']) ? trim($_POST['fax_number']) : '';
			$this->options['order_mail'] = isset($_POST['order_mail']) ? trim($_POST['order_mail']) : '';
			$this->options['inquiry_mail'] = isset($_POST['inquiry_mail']) ? trim($_POST['inquiry_mail']) : '';
			$this->options['sender_mail'] = isset($_POST['sender_mail']) ? trim($_POST['sender_mail']) : '';
			$this->options['error_mail'] = isset($_POST['error_mail']) ? trim($_POST['error_mail']) : '';
			$this->options['postage_privilege'] = isset($_POST['postage_privilege']) ? trim($_POST['postage_privilege']) : '';
			$this->options['purchase_limit'] = isset($_POST['purchase_limit']) ? trim($_POST['purchase_limit']) : '';
			$this->options['point_rate'] = isset($_POST['point_rate']) ? (int)$_POST['point_rate'] : 1;
			$this->options['start_point'] = isset($_POST['start_point']) ? (int)$_POST['start_point'] : '';
			$this->options['shipping_rule'] = isset($_POST['shipping_rule']) ? trim($_POST['shipping_rule']) : '';
			$this->options['tax_display'] = isset($_POST['tax_display']) ? trim($_POST['tax_display']) : 'activate';
			$this->options['tax_mode'] = isset($_POST['tax_mode']) ? trim($_POST['tax_mode']) : 'include';
			$this->options['tax_target'] = isset($_POST['tax_target']) ? trim($_POST['tax_target']) : 'products';
			$this->options['tax_rate'] = isset($_POST['tax_rate']) ? (int)$_POST['tax_rate'] : '';
			$this->options['tax_method'] = isset($_POST['tax_method']) ? trim($_POST['tax_method']) : '';
			$this->options['applicable_taxrate'] = ( isset( $_POST['applicable_taxrate'] ) ) ? $_POST['applicable_taxrate'] : 'standard';
			$this->options['tax_rate_reduced'] = ( isset( $_POST['tax_rate_reduced'] ) ) ? (int)$_POST['tax_rate_reduced'] : '';
			$this->options['cod_type'] = isset($this->options['cod_type']) ? $this->options['cod_type'] : 'fix';
			$this->options['fee_subject'] = ( isset( $_POST['fee_subject'] ) ) ? trim( $_POST['fee_subject'] ) : 'all';
			$this->options['transferee'] = isset($_POST['transferee']) ? trim($_POST['transferee']) : '';
			$this->options['tatransfer_limit'] = isset($_POST['tatransfer_limit']) ? trim($_POST['tatransfer_limit']) : '';
			$this->options['copyright'] = isset($_POST['copyright']) ? trim($_POST['copyright']) : '';
			$this->options['business_registration_number'] = ( isset( $_POST['business_registration_number'] ) ) ? trim( $_POST['business_registration_number'] ) : '';
			$this->options['membersystem_state'] = isset($_POST['membersystem_state']) ? trim($_POST['membersystem_state']) : '';
			$this->options['membersystem_point'] = isset($_POST['membersystem_point']) ? trim($_POST['membersystem_point']) : '';
			$this->options['point_coverage'] = isset($_POST['point_coverage']) ? (int)$_POST['point_coverage'] : 0;
			$this->options['point_assign'] = isset($_POST['point_assign']) ? (int)$_POST['point_assign'] : 1;
			$this->options['address_search'] = isset($_POST['address_search']) ? trim($_POST['address_search']) : 'deactivate';
			$this->options['stock_status_label'] = ( isset($_POST['stock_status_label']) ) ? $_POST['stock_status_label'] : array();
			$this->options['order_acceptable_label'] = ( isset($_POST['order_acceptable_label']) ) ? trim($_POST['order_acceptable_label']) : '';

			$this->options = apply_filters( 'usces_filter_admin_setup_options', $this->options );

			update_option('usces', $this->options);

			$this->action_status = 'success';
			$this->action_message = __('options are updated','usces');

		} else {
			$this->action_status = 'none';
			$this->action_message = '';
		}

		require_once(USCES_PLUGIN_DIR . '/includes/admin_setup.php');

	}

	/* Shop Schedule Page */
	function admin_schedule_page() {

		$this->options = get_option('usces');

		if(isset($_POST['usces_option_update'])) {

			check_admin_referer( 'admin_schedule', 'wc_nonce' );

			$_POST = $this->stripslashes_deep_post($_POST);

			$this->options['campaign_schedule'] = isset($_POST['campaign_schedule']) ? $_POST['campaign_schedule'] : '0';
			if(isset($_POST['business_days']))
				$this->options['business_days'] = $_POST['business_days'];

			update_option('usces', $this->options);

			do_action( 'usces_action_admin_schedule_update' );

			$this->action_status = 'success';
			$this->action_message = __('options are updated','usces');
		} else {
			$this->action_status = 'none';
			$this->action_message = '';
		}

		require_once(USCES_PLUGIN_DIR . '/includes/admin_schedule.php');

	}

	/* Shop Delivery Page */
	function admin_delivery_page() {

		$this->options = get_option('usces');

		if(isset($_POST['usces_option_update'])) {

			check_admin_referer( 'admin_delivery', 'wc_nonce' );

			$_POST = $this->stripslashes_deep_post($_POST);

			if(isset($_POST['delivery_time_limit'])) $this->options['delivery_time_limit'] = $_POST['delivery_time_limit'];
			if(isset($_POST['shortest_delivery_time'])) $this->options['shortest_delivery_time'] =  $_POST['shortest_delivery_time'];
			if(isset($_POST['delivery_after_days'])) $this->options['delivery_after_days'] =  $_POST['delivery_after_days'];
			$this->options = apply_filters('usces_filter_admin_delivery_options', $this->options );

			update_option('usces', $this->options);

			$this->action_status = 'success';
			$this->action_message = __('options are updated','usces');
		} else {
			$this->action_status = 'none';
			$this->action_message = '';
		}

		require_once(USCES_PLUGIN_DIR . '/includes/admin_delivery.php');

	}

	/* Shop Mail Page */
	function admin_mail_page() {
		global $allowedposttags;

		if ( isset( $_POST['usces_option_update'] ) ) {
			check_admin_referer( 'admin_mail', 'wc_nonce' );
			$_POST = $this->stripslashes_deep_post( $_POST );

			$mail_othermail = get_option( 'usces_mail_othermail' );
			foreach ( $_POST['title'] as $key => $value ) {
				$value = wel_esc_script( $value );
				if ( 'othermail' == $key ) {
					if ( WCUtils::is_blank( $value ) ) {
						if ( empty( $mail_othermail['title'] ) && isset( $this->options['mail_default']['title'][ $key ] ) ) {
							$mail_othermail['title'] = $this->options['mail_default']['title'][ $key ];
							$this->options['mail_data']['title'][ $key ] = $mail_othermail['title'];
						}
					} else {
						$mail_othermail['title'] = trim( $value );
						$this->options['mail_data']['title'][ $key ] = $mail_othermail['title'];
					}
				} else {
					if ( WCUtils::is_blank( $value ) ) {
						$this->options['mail_data']['title'][ $key ] = isset( $this->options['mail_default']['title'][ $key ] ) ? $this->options['mail_default']['title'][ $key ] : '';
					} else {
						$this->options['mail_data']['title'][ $key ] = trim( $value );
					}
				}
			}
			foreach ( $_POST['header'] as $key => $value ) {
				$value = wel_esc_script( $value );
				if ( 'othermail' == $key ) {
					if ( WCUtils::is_blank( $value ) ) {
						if ( empty( $mail_othermail['header'] ) && isset( $this->options['mail_default']['header'][ $key ] ) ) {
							$mail_othermail['header'] = $this->options['mail_default']['header'][ $key ];
							$this->options['mail_data']['header'][ $key ] = $mail_othermail['header'];
						}
					} else {
						$mail_othermail['header'] = $value;
						$this->options['mail_data']['header'][ $key ] = $mail_othermail['header'];
					}
				} else {
					if ( WCUtils::is_blank( $value ) ) {
						$this->options['mail_data']['header'][ $key ] = isset( $this->options['mail_default']['header'][ $key ] ) ? $this->options['mail_default']['header'][ $key ] : '';
					} else {
						$this->options['mail_data']['header'][ $key ] = $value;
					}
				}
			}
			foreach ( $_POST['footer'] as $key => $value ) {
				$value = wel_esc_script( $value );
				if ( 'othermail' == $key ) {
					if ( WCUtils::is_blank( $value ) ) {
						if ( empty( $mail_othermail['footer'] ) && isset( $this->options['mail_default']['footer'][ $key ] ) ) {
							$mail_othermail['footer'] = $this->options['mail_default']['footer'][ $key ];
							$this->options['mail_data']['footer'][ $key ] = $mail_othermail['footer'];
						}
					} else {
						$mail_othermail['footer'] = $value;
						$this->options['mail_data']['footer'][ $key ] = $mail_othermail['footer'];
					}
				} else {
					if ( WCUtils::is_blank( $value ) ) {
						$this->options['mail_data']['footer'][ $key ] = isset( $this->options['mail_default']['footer'][ $key ] ) ? $this->options['mail_default']['footer'][ $key ] : '';
					} else {
						$this->options['mail_data']['footer'][ $key ] = $value;
					}
				}
			}
			update_option( 'usces', $this->options );
			update_option( 'usces_mail_othermail', $mail_othermail );

			$this->action_status  = 'success';
			$this->action_message = __( 'options are updated', 'usces' );

		} elseif ( isset( $_POST['usces_option_update_top'] ) ) {
			check_admin_referer( 'admin_mail', 'wc_nonce' );
			$_POST = $this->stripslashes_deep_post( $_POST );

			$this->options['smtp_hostname']               = esc_html( trim( $_POST['smtp_hostname'] ) );
			$this->options['newmem_admin_mail']           = (int) $_POST['newmem_admin_mail'];
			$this->options['updmem_admin_mail']           = (int) $_POST['updmem_admin_mail'];
			$this->options['updmem_customer_mail']        = (int) $_POST['updmem_customer_mail'];
			$this->options['delmem_admin_mail']           = (int) $_POST['delmem_admin_mail'];
			$this->options['delmem_customer_mail']        = (int) $_POST['delmem_customer_mail'];
			$this->options['put_customer_name']           = (int) $_POST['put_customer_name'];
			$this->options['email_attach_feature']        = (int) $_POST['email_attach_feature'];
			$this->options['email_attach_file_extension'] = trim( wel_esc_script( $_POST['email_attach_file_extension'] ) );
			$this->options['email_attach_file_size']      = (int) $_POST['email_attach_file_size'];
			$this->options['add_html_email_option']       = (int) $_POST['add_html_email_option'];
			update_option( 'usces', $this->options );

			$this->action_status  = 'success';
			$this->action_message = __( 'options are updated', 'usces' );

		} elseif ( isset( $_POST['usces_option_update_auto'] ) ) {
			check_admin_referer( 'admin_mail', 'wc_nonce' );
			$_POST = $this->stripslashes_deep_post( $_POST );

			$mail_thankyou = get_option( 'usces_mail_thankyou' );
			if ( WCUtils::is_blank( $_POST['title']['thankyou'] ) ) {
				if ( empty( $mail_thankyou['title'] ) && isset( $this->options['mail_default']['title']['thankyou'] ) ) {
					$mail_thankyou['title'] = $this->options['mail_default']['title']['thankyou'];
					$this->options['mail_data']['title']['thankyou'] = $mail_thankyou['title'];
				}
			} else {
				$mail_thankyou['title'] = trim( wel_esc_script( $_POST['title']['thankyou'] ) );
				$this->options['mail_data']['title']['thankyou'] = $mail_thankyou['title'];
			}
			if ( WCUtils::is_blank( $_POST['header']['thankyou'] ) ) {
				if ( empty( $mail_thankyou['header'] ) && isset( $this->options['mail_default']['header']['thankyou'] ) ) {
					$mail_thankyou['header'] = $this->options['mail_default']['header']['thankyou'];
					$this->options['mail_data']['header']['thankyou'] = $mail_thankyou['header'];
				}
			} else {
				$mail_thankyou['header'] = trim( wel_esc_script( $_POST['header']['thankyou'] ) );
				$this->options['mail_data']['header']['thankyou'] = $mail_thankyou['header'];
			}
			if ( WCUtils::is_blank( $_POST['footer']['thankyou'] ) ) {
				if ( empty( $mail_thankyou['footer'] ) && isset( $this->options['mail_default']['footer']['thankyou'] ) ) {
					$mail_thankyou['footer'] = $this->options['mail_default']['footer']['thankyou'];
					$this->options['mail_data']['footer']['thankyou'] = $mail_thankyou['footer'];
				}
			} else {
				$mail_thankyou['footer'] = trim( wel_esc_script( $_POST['footer']['thankyou'] ) );
				$this->options['mail_data']['footer']['thankyou'] = $mail_thankyou['footer'];
			}
			update_option( 'usces_mail_thankyou', $mail_thankyou );

			$mail_order = get_option( 'usces_mail_order' );
			if ( WCUtils::is_blank( $_POST['title']['order'] ) ) {
				if ( empty( $mail_order['title'] ) && isset( $this->options['mail_default']['title']['order'] ) ) {
					$mail_order['title'] = $this->options['mail_default']['title']['order'];
					$this->options['mail_data']['title']['order'] = $mail_order['title'];
				}
			} else {
				$mail_order['title'] = trim( wel_esc_script( $_POST['title']['order'] ) );
				$this->options['mail_data']['title']['order'] = $mail_order['title'];
			}
			if ( WCUtils::is_blank( $_POST['header']['order'] ) ) {
				if ( empty( $mail_order['header'] ) && isset( $this->options['mail_default']['header']['order'] ) ) {
					$mail_order['header'] = $this->options['mail_default']['header']['order'];
					$this->options['mail_data']['header']['order'] = $mail_order['header'];
				}
			} else {
				$mail_order['header'] = trim( wel_esc_script( $_POST['header']['order'] ) );
				$this->options['mail_data']['header']['order'] = $mail_order['header'];
			}
			if ( WCUtils::is_blank( $_POST['footer']['order'] ) ) {
				if ( empty( $mail_order['footer'] ) && isset( $this->options['mail_default']['footer']['order'] ) ) {
					$mail_order['footer'] = $this->options['mail_default']['footer']['order'];
					$this->options['mail_data']['footer']['order'] = $mail_order['footer'];
				}
			} else {
				$mail_order['footer'] = trim( wel_esc_script( $_POST['footer']['order'] ) );
				$this->options['mail_data']['footer']['order'] = $mail_order['footer'];
			}
			update_option( 'usces_mail_order', $mail_order );

			$mail_inquiry = get_option( 'usces_mail_inquiry' );
			if ( WCUtils::is_blank( $_POST['title']['inquiry'] ) ) {
				if ( empty( $mail_inquiry['title'] ) && isset( $this->options['mail_default']['title']['inquiry'] ) ) {
					$mail_inquiry['title'] = $this->options['mail_default']['title']['inquiry'];
					$this->options['mail_data']['title']['inquiry'] = $mail_inquiry['title'];
				}
			} else {
				$mail_inquiry['title'] = trim( wel_esc_script( $_POST['title']['inquiry'] ) );
				$this->options['mail_data']['title']['inquiry'] = $mail_inquiry['title'];
			}
			if ( WCUtils::is_blank( $_POST['header']['inquiry'] ) ) {
				if ( empty( $mail_inquiry['header'] ) && isset( $this->options['mail_default']['header']['inquiry'] ) ) {
					$mail_inquiry['header'] = $this->options['mail_default']['header']['inquiry'];
					$this->options['mail_data']['header']['inquiry'] = $mail_inquiry['header'];
				}
			} else {
				$mail_inquiry['header'] = trim( wel_esc_script( $_POST['header']['inquiry'] ) );
				$this->options['mail_data']['header']['inquiry'] = $mail_inquiry['header'];
			}
			if ( WCUtils::is_blank( $_POST['footer']['inquiry'] ) ) {
				if ( empty( $mail_inquiry['footer'] ) && isset( $this->options['mail_default']['footer']['inquiry'] ) ) {
					$mail_inquiry['footer'] = $this->options['mail_default']['footer']['inquiry'];
					$this->options['mail_data']['footer']['inquiry'] = $mail_inquiry['footer'];
				}
			} else {
				$mail_inquiry['footer'] = trim( wel_esc_script( $_POST['footer']['inquiry'] ) );
				$this->options['mail_data']['footer']['inquiry'] = $mail_inquiry['footer'];
			}
			update_option( 'usces_mail_inquiry', $mail_inquiry );

			$mail_membercomp = get_option( 'usces_mail_membercomp' );
			if ( WCUtils::is_blank( $_POST['title']['membercomp'] ) ) {
				if ( empty( $mail_membercomp['title'] ) && isset( $this->options['mail_default']['title']['membercomp'] ) ) {
					$mail_membercomp['title'] = $this->options['mail_default']['title']['membercomp'];
					$this->options['mail_data']['title']['membercomp'] = $mail_membercomp['title'];
				}
			} else {
				$mail_membercomp['title'] = trim( wel_esc_script( $_POST['title']['membercomp'] ) );
				$this->options['mail_data']['title']['membercomp'] = $mail_membercomp['title'];
			}
			if ( WCUtils::is_blank( wel_esc_script( $_POST['header']['membercomp'] ) ) ) {
				if ( empty( $mail_membercomp['header'] ) && isset( $this->options['mail_default']['header']['membercomp'] ) ) {
					$mail_membercomp['header'] = $this->options['mail_default']['header']['membercomp'];
					$this->options['mail_data']['header']['membercomp'] = $mail_membercomp['header'];
				}
			} else {
				$mail_membercomp['header'] = trim( wel_esc_script( $_POST['header']['membercomp'] ) );
				$this->options['mail_data']['header']['membercomp'] = $mail_membercomp['header'];
			}
			if ( WCUtils::is_blank( $_POST['footer']['membercomp'] ) ) {
				if ( empty( $mail_membercomp['footer'] ) && isset( $this->options['mail_default']['footer']['membercomp'] ) ) {
					$mail_membercomp['footer'] = $this->options['mail_default']['footer']['membercomp'];
					$this->options['mail_data']['footer']['membercomp'] = $mail_membercomp['footer'];
				}
			} else {
				$mail_membercomp['footer'] = trim( wel_esc_script( $_POST['footer']['membercomp'] ) );
				$this->options['mail_data']['footer']['membercomp'] = $mail_membercomp['footer'];
			}
			update_option( 'usces_mail_membercomp', $mail_membercomp );

			$this->action_status  = 'success';
			$this->action_message = __( 'options are updated', 'usces' );

		} elseif ( isset( $_POST['usces_option_update_manual'] ) ) {
			check_admin_referer( 'admin_mail', 'wc_nonce' );
			$_POST = $this->stripslashes_deep_post( $_POST );

			$mail_completionmail = get_option( 'usces_mail_completionmail' );
			if ( WCUtils::is_blank( $_POST['title']['completionmail'] ) ) {
				if ( empty( $mail_completionmail['title'] ) && isset( $this->options['mail_default']['title']['completionmail'] ) ) {
					$mail_completionmail['title'] = $this->options['mail_default']['title']['completionmail'];
					$this->options['mail_data']['title']['completionmail'] = $mail_completionmail['title'];
				}
			} else {
				$mail_completionmail['title'] = trim( wel_esc_script( $_POST['title']['completionmail'] ) );
				$this->options['mail_data']['title']['completionmail'] = $mail_completionmail['title'];
			}
			if ( WCUtils::is_blank( $_POST['header']['completionmail'] ) ) {
				if ( empty( $mail_completionmail['header'] ) && isset( $this->options['mail_default']['header']['completionmail'] ) ) {
					$mail_completionmail['header'] = $this->options['mail_default']['header']['completionmail'];
					$this->options['mail_data']['header']['completionmail'] = $mail_completionmail['header'];
				}
			} else {
				$mail_completionmail['header'] = trim( wel_esc_script( $_POST['header']['completionmail'] ) );
				$this->options['mail_data']['header']['completionmail'] = $mail_completionmail['header'];
			}
			if ( WCUtils::is_blank( $_POST['footer']['completionmail'] ) ) {
				if ( empty( $mail_completionmail['footer'] ) && isset( $this->options['mail_default']['footer']['completionmail'] ) ) {
					$mail_completionmail['footer'] = $this->options['mail_default']['footer']['completionmail'];
					$this->options['mail_data']['footer']['completionmail'] = $mail_completionmail['footer'];
				}
			} else {
				$mail_completionmail['footer'] = trim( wel_esc_script( $_POST['footer']['completionmail'] ) );
				$this->options['mail_data']['footer']['completionmail'] = $mail_completionmail['footer'];
			}
			update_option( 'usces_mail_completionmail', $mail_completionmail );

			$mail_ordermail = get_option( 'usces_mail_ordermail' );
			if ( WCUtils::is_blank( $_POST['title']['ordermail'] ) ) {
				if ( empty( $mail_ordermail['title'] ) && isset( $this->options['mail_default']['title']['ordermail'] ) ) {
					$mail_ordermail['title'] = $this->options['mail_default']['title']['ordermail'];
					$this->options['mail_data']['title']['ordermail'] = $mail_ordermail['title'];
				}
			} else {
				$mail_ordermail['title'] = trim( wel_esc_script( $_POST['title']['ordermail'] ) );
				$this->options['mail_data']['title']['ordermail'] = $mail_ordermail['title'];
			}
			if ( WCUtils::is_blank( $_POST['header']['ordermail'] ) ) {
				if ( empty( $mail_ordermail['header'] ) && isset( $this->options['mail_default']['header']['ordermail'] ) ) {
					$mail_ordermail['header'] = $this->options['mail_default']['header']['ordermail'];
					$this->options['mail_data']['header']['ordermail'] = $mail_ordermail['header'];
				}
			} else {
				$mail_ordermail['header'] = trim( wel_esc_script( $_POST['header']['ordermail'] ) );
				$this->options['mail_data']['header']['ordermail'] = $mail_ordermail['header'];
			}
			if ( WCUtils::is_blank( $_POST['footer']['ordermail'] ) ) {
				if ( empty( $mail_ordermail['footer'] ) && isset( $this->options['mail_default']['footer']['ordermail'] ) ) {
					$mail_ordermail['footer'] = $this->options['mail_default']['footer']['ordermail'];
					$this->options['mail_data']['footer']['ordermail'] = $mail_ordermail['footer'];
				}
			} else {
				$mail_ordermail['footer'] = trim( wel_esc_script( $_POST['footer']['ordermail'] ) );
				$this->options['mail_data']['footer']['ordermail'] = $mail_ordermail['footer'];
			}
			update_option( 'usces_mail_ordermail', $mail_ordermail );

			$mail_changemail = get_option( 'usces_mail_changemail' );
			if ( WCUtils::is_blank( $_POST['title']['changemail'] ) ) {
				if ( empty( $mail_changemail['title'] ) && isset( $this->options['mail_default']['title']['changemail'] ) ) {
					$mail_changemail['title'] = $this->options['mail_default']['title']['changemail'];
					$this->options['mail_data']['title']['changemail'] = $mail_changemail['title'];
				}
			} else {
				$mail_changemail['title'] = trim( wel_esc_script( $_POST['title']['changemail'] ) );
				$this->options['mail_data']['title']['changemail'] = $mail_changemail['title'];
			}
			if ( WCUtils::is_blank( $_POST['header']['changemail'] ) ) {
				if ( empty( $mail_changemail['header'] ) && isset( $this->options['mail_default']['header']['changemail'] ) ) {
					$mail_changemail['header'] = $this->options['mail_default']['header']['changemail'];
					$this->options['mail_data']['header']['changemail'] = $mail_changemail['header'];
				}
			} else {
				$mail_changemail['header'] = trim( wel_esc_script( $_POST['header']['changemail'] ) );
				$this->options['mail_data']['header']['changemail'] = $mail_changemail['header'];
			}
			if ( WCUtils::is_blank( $_POST['footer']['changemail'] ) ) {
				if ( empty( $mail_changemail['footer'] ) && isset( $this->options['mail_default']['footer']['changemail'] ) ) {
					$mail_changemail['footer'] = $this->options['mail_default']['footer']['changemail'];
					$this->options['mail_data']['footer']['changemail'] = $mail_changemail['footer'];
				}
			} else {
				$mail_changemail['footer'] = trim( wel_esc_script( $_POST['footer']['changemail'] ) );
				$this->options['mail_data']['footer']['changemail'] = $mail_changemail['footer'];
			}
			update_option( 'usces_mail_changemail', $mail_changemail );

			$mail_receiptmail = get_option( 'usces_mail_receiptmail' );
			if ( WCUtils::is_blank( $_POST['title']['receiptmail'] ) ) {
				if ( empty( $mail_receiptmail['title'] ) && isset( $this->options['mail_default']['title']['receiptmail'] ) ) {
					$mail_receiptmail['title'] = $this->options['mail_default']['title']['receiptmail'];
					$this->options['mail_data']['title']['receiptmail'] = $mail_receiptmail['title'];
				}
			} else {
				$mail_receiptmail['title'] = trim( wel_esc_script( $_POST['title']['receiptmail'] ) );
				$this->options['mail_data']['title']['receiptmail'] = $mail_receiptmail['title'];
			}
			if ( WCUtils::is_blank( $_POST['header']['receiptmail'] ) ) {
				if ( empty( $mail_receiptmail['header'] ) && isset( $this->options['mail_default']['header']['receiptmail'] ) ) {
					$mail_receiptmail['header'] = $this->options['mail_default']['header']['receiptmail'];
					$this->options['mail_data']['header']['receiptmail'] = $mail_receiptmail['header'];
				}
			} else {
				$mail_receiptmail['header'] = trim( wel_esc_script( $_POST['header']['receiptmail'] ) );
				$this->options['mail_data']['header']['receiptmail'] = $mail_receiptmail['header'];
			}
			if ( WCUtils::is_blank( $_POST['footer']['receiptmail'] ) ) {
				if ( empty( $mail_receiptmail['footer'] ) && isset( $this->options['mail_default']['footer']['receiptmail'] ) ) {
					$mail_receiptmail['footer'] = $this->options['mail_default']['footer']['receiptmail'];
					$this->options['mail_data']['footer']['receiptmail'] = $mail_receiptmail['footer'];
				}
			} else {
				$mail_receiptmail['footer'] = trim( wel_esc_script( $_POST['footer']['receiptmail'] ) );
				$this->options['mail_data']['footer']['receiptmail'] = $mail_receiptmail['footer'];
			}
			update_option( 'usces_mail_receiptmail', $mail_receiptmail );

			$mail_mitumorimail = get_option( 'usces_mail_mitumorimail' );
			if ( WCUtils::is_blank( $_POST['title']['mitumorimail'] ) ) {
				if ( empty( $mail_mitumorimail['title'] ) && isset( $this->options['mail_default']['title']['mitumorimail'] ) ) {
					$mail_mitumorimail['title'] = $this->options['mail_default']['title']['mitumorimail'];
					$this->options['mail_data']['title']['mitumorimail'] = $mail_mitumorimail['title'];
				}
			} else {
				$mail_mitumorimail['title'] = trim( wel_esc_script( $_POST['title']['mitumorimail'] ) );
				$this->options['mail_data']['title']['mitumorimail'] = $mail_mitumorimail['title'];
			}
			if ( WCUtils::is_blank( $_POST['header']['mitumorimail'] ) ) {
				if ( empty( $mail_mitumorimail['header'] ) && isset( $this->options['mail_default']['header']['mitumorimail'] ) ) {
					$mail_mitumorimail['header'] = $this->options['mail_default']['header']['mitumorimail'];
					$this->options['mail_data']['header']['mitumorimail'] = $mail_mitumorimail['header'];
				}
			} else {
				$mail_mitumorimail['header'] = trim( wel_esc_script( $_POST['header']['mitumorimail'] ) );
				$this->options['mail_data']['header']['mitumorimail'] = $mail_mitumorimail['header'];
			}
			if ( WCUtils::is_blank( $_POST['footer']['mitumorimail'] ) ) {
				if ( empty( $mail_mitumorimail['footer'] ) && isset( $this->options['mail_default']['footer']['mitumorimail'] ) ) {
					$mail_mitumorimail['footer'] = $this->options['mail_default']['footer']['mitumorimail'];
					$this->options['mail_data']['footer']['mitumorimail'] = $mail_mitumorimail['footer'];
				}
			} else {
				$mail_mitumorimail['footer'] = trim( wel_esc_script( $_POST['footer']['mitumorimail'] ) );
				$this->options['mail_data']['footer']['mitumorimail'] = $mail_mitumorimail['footer'];
			}
			update_option( 'usces_mail_mitumorimail', $mail_mitumorimail );

			$mail_cancelmail = get_option( 'usces_mail_cancelmail' );
			if ( WCUtils::is_blank( $_POST['title']['cancelmail'] ) ) {
				if ( empty( $mail_cancelmail['title'] ) && isset( $this->options['mail_default']['title']['cancelmail'] ) ) {
					$mail_cancelmail['title'] = $this->options['mail_default']['title']['cancelmail'];
					$this->options['mail_data']['title']['cancelmail'] = $mail_cancelmail['title'];
				}
			} else {
				$mail_cancelmail['title'] = trim( wel_esc_script( $_POST['title']['cancelmail'] ) );
				$this->options['mail_data']['title']['cancelmail'] = $mail_cancelmail['title'];
			}
			if ( WCUtils::is_blank( $_POST['header']['cancelmail'] ) ) {
				if ( empty( $mail_cancelmail['header'] ) && isset( $this->options['mail_default']['header']['cancelmail'] ) ) {
					$mail_cancelmail['header'] = $this->options['mail_default']['header']['cancelmail'];
					$this->options['mail_data']['header']['cancelmail'] = $mail_cancelmail['header'];
				}
			} else {
				$mail_cancelmail['header'] = trim( wel_esc_script( $_POST['header']['cancelmail'] ) );
				$this->options['mail_data']['header']['cancelmail'] = $mail_cancelmail['header'];
			}
			if ( WCUtils::is_blank( $_POST['footer']['cancelmail'] ) ) {
				if ( empty( $mail_cancelmail['footer'] ) && isset( $this->options['mail_default']['footer']['cancelmail'] ) ) {
					$mail_cancelmail['footer'] = $this->options['mail_default']['footer']['cancelmail'];
					$this->options['mail_data']['footer']['cancelmail'] = $mail_cancelmail['footer'];
				}
			} else {
				$mail_cancelmail['footer'] = trim( wel_esc_script( $_POST['footer']['cancelmail'] ) );
				$this->options['mail_data']['footer']['cancelmail'] = $mail_cancelmail['footer'];
			}
			update_option( 'usces_mail_cancelmail', $mail_cancelmail );

			$this->action_status  = 'success';
			$this->action_message = __( 'options are updated', 'usces' );

		} else {
			$this->action_status  = 'none';
			$this->action_message = '';
		}

		do_action( 'usces_action_admin_mail_page' );
		
		if (isset($this->options['add_html_email_option']) && $this->options['add_html_email_option'] == 1) {
			wp_enqueue_script('jquery-ui-dialog');
		}

		require_once( USCES_PLUGIN_DIR . '/includes/admin_mail.php' );
	}

	/* Admin Cart Page */
	function admin_cart_page() {
		global $allowedposttags;
		$this->options = get_option('usces');

		if(isset($_POST['usces_option_update'])) {

			check_admin_referer( 'admin_cart', 'wc_nonce' );

			$_POST = $this->stripslashes_deep_post($_POST);

			foreach ( $this->options['indi_item_name'] as $key => $value ) {
				$this->options['indi_item_name'][$key] = isset($_POST['indication'][$key]) ? 1 : 0;
			}
			foreach ( $_POST['position'] as $key => $value ) {
				$this->options['pos_item_name'][$key] = $value;
			}
			foreach ( $_POST['header'] as $key => $value ) {
				$this->options['cart_page_data']['header'][$key] = wp_kses($value, $allowedposttags);
			}
			foreach ( $_POST['footer'] as $key => $value ) {
				$this->options['cart_page_data']['footer'][$key] = wp_kses($value, $allowedposttags);
			}
			if ( isset( $_POST['confirm_notes'] ) ) {
				$this->options['cart_page_data']['confirm_notes'] = wp_kses( $_POST['confirm_notes'], $allowedposttags );
			}
			update_option('usces', $this->options);

			$this->action_status = 'success';
			$this->action_message = __('options are updated','usces');

		} else {

			$this->action_status = 'none';
			$this->action_message = '';
		}

		require_once(USCES_PLUGIN_DIR . '/includes/admin_cart.php');
	}

	/* Admin Member Page */
	function admin_member_page() {
		global $allowedposttags;
		$this->options = get_option('usces');

		if(isset($_POST['usces_option_update'])) {

			check_admin_referer( 'admin_member', 'wc_nonce' );

			$_POST = $this->stripslashes_deep_post($_POST);

			foreach ( $_POST['header'] as $key => $value ) {
				$this->options['member_page_data']['header'][$key] = wp_kses($value, $allowedposttags);
			}
			foreach ( $_POST['footer'] as $key => $value ) {
				$this->options['member_page_data']['footer'][$key] = wp_kses($value, $allowedposttags);
			}
			$this->options['member_page_data']['agree_member_exp'] = isset($_POST['agree_member_exp']) ? $_POST['agree_member_exp']: '';
			$this->options['member_page_data']['agree_member_cont'] = isset($_POST['agree_member_cont']) ? $_POST['agree_member_cont']: '';

			$this->options['agree_member'] = isset($_POST['agree_member']) ? $_POST['agree_member']: 'deactivate';

			update_option('usces', $this->options);

			$this->action_status = 'success';
			$this->action_message = __('options are updated','usces');

		} else {
			$this->action_status = 'none';
			$this->action_message = '';
		}

		require_once(USCES_PLUGIN_DIR . '/includes/admin_member.php');
	}

	/* Admin System Page */
	function admin_system_page() {
		global $usces_states;
		$action_status = '';

		$this->options = get_option('usces');

		if(isset($_POST['usces_system_option_update'])) {

			check_admin_referer( 'admin_system', 'wc_nonce' );
			$_POST = $this->stripslashes_deep_post($_POST);

			$this->options['divide_item'] = isset($_POST['divide_item']) ? 1 : 0;
			$this->options['itemimg_anchor_rel'] = isset($_POST['itemimg_anchor_rel']) ? trim($_POST['itemimg_anchor_rel']) : '';
			$this->options['fukugo_category_orderby'] = isset($_POST['fukugo_category_orderby']) ? $_POST['fukugo_category_orderby'] : '';
			$this->options['fukugo_category_order'] = isset($_POST['fukugo_category_order']) ? $_POST['fukugo_category_order'] : '';
			$this->options['settlement_path'] = isset($_POST['settlement_path']) ? $_POST['settlement_path'] : '';
			if( WCUtils::is_blank($this->options['settlement_path']) ) $this->options['settlement_path'] = USCES_PLUGIN_DIR . 'settlement/';
			$sl = substr($this->options['settlement_path'], -1);
			if($sl != '/' && $sl != '\\') $this->options['settlement_path'] .= '/';
			$this->options['logs_path'] = isset($_POST['logs_path']) ? $_POST['logs_path'] : '';
			if( !WCUtils::is_blank($this->options['logs_path']) ){
				$sl = substr($this->options['logs_path'], -1);
				if($sl == '/' || $sl == '\\') $this->options['logs_path'] = substr($this->options['logs_path'], 0, -1);
			}
			$this->options['use_ssl'] = isset($_POST['use_ssl']) ? 1 : 0;
			$this->options['ssl_url'] = isset($_POST['ssl_url']) ? rtrim($_POST['ssl_url'], '/') : '';
			$this->options['ssl_url_admin'] = isset($_POST['ssl_url_admin']) ? rtrim($_POST['ssl_url_admin'], '/') : '';
			if( WCUtils::is_blank($this->options['ssl_url']) || WCUtils::is_blank($this->options['ssl_url_admin']) ) $this->options['use_ssl'] = 0;
			$this->options['inquiry_id'] = isset($_POST['inquiry_id']) ? esc_html(rtrim($_POST['inquiry_id'])) : '';
			$this->options['use_javascript'] = isset($_POST['use_javascript']) ? (int)$_POST['use_javascript'] : 1;

			$this->options['system']['no_cart_css'] = isset($_POST['no_cart_css']) ? 1 : 0;
			$this->options['system']['dec_orderID_flag'] = isset($_POST['dec_orderID_flag']) ? (int)$_POST['dec_orderID_flag'] : 0;
			$this->options['system']['dec_orderID_prefix'] = isset($_POST['dec_orderID_prefix']) ? esc_html(rtrim($_POST['dec_orderID_prefix'])) : '';

			if( isset($_POST['dec_orderID_digit']) ){
				$dec_orderID_digit = (int)rtrim($_POST['dec_orderID_digit']);
				if( 6 > $dec_orderID_digit ){
					$this->options['system']['dec_orderID_digit'] = 6;
				}else{
					$this->options['system']['dec_orderID_digit'] = $dec_orderID_digit;
				}
			}else{
				$this->options['system']['dec_orderID_digit'] = 6;
			}

			$this->options['system']['subimage_rule'] = isset($_POST['subimage_rule']) ? (int)$_POST['subimage_rule'] : 0;
			$this->options['system']['pdf_delivery'] = isset($_POST['pdf_delivery']) ? (int)$_POST['pdf_delivery'] : 0;
			$this->options['system']['member_pass_rule_min'] = isset($_POST['member_pass_rule_min']) ? (int)$_POST['member_pass_rule_min'] : 6;
			$this->options['system']['member_pass_rule_max'] = isset($_POST['member_pass_rule_max']) && !empty($_POST['member_pass_rule_max']) ? (int)$_POST['member_pass_rule_max'] : 30;
			$this->options['system']['member_pass_rule_upercase'] = isset($_POST['member_pass_rule_upercase']) ? 1 : 0;
			$this->options['system']['member_pass_rule_lowercase'] = isset($_POST['member_pass_rule_lowercase']) ? 1 : 0;
			$this->options['system']['member_pass_rule_digit'] = isset($_POST['member_pass_rule_digit']) ? 1 : 0;
			$this->options['system']['member_pass_rule_symbols'] = isset($_POST['member_pass_rule_symbols']) ? 1 : 0;
			$this->options['system']['member_pass_rule_number'] = isset($_POST['member_pass_rule_number']) ? 1 : 0;
			$this->options['system']['member_pass_rule_symbol'] = isset($_POST['member_pass_rule_symbol']) ? 1 : 0;
			$this->options['system']['csv_encode_type'] = isset($_POST['csv_encode_type']) ? (int)$_POST['csv_encode_type'] : 0;
			$this->options['system']['csv_category_format'] = ( isset($_POST['csv_category_format']) ) ? (int)$_POST['csv_category_format'] : 0;
			$this->options['system']['settlement_backup'] = ( isset($_POST['settlement_backup']) ) ? (int)$_POST['settlement_backup'] : 0;
			$this->options['system']['settlement_notice'] = ( isset($_POST['settlement_notice']) ) ? (int)$_POST['settlement_notice'] : 0;

			if($action_status != '') {
				$this->action_status = 'error';
				$this->action_message = __('Data have deficiency.','usces');
			} else {
				$this->action_status = 'success';
				$this->action_message = __('options are updated','usces');
			}

		} elseif(isset($_POST['usces_locale_option_update'])) {

			check_admin_referer( 'admin_system', 'wc_nonce' );
			$_POST = $this->stripslashes_deep_post($_POST);

			$this->options['system']['front_lang'] = (isset($_POST['front_lang']) && 'others' != $_POST['front_lang']) ? $_POST['front_lang'] : usces_get_local_language();
			$this->options['system']['currency'] = (isset($_POST['currency']) && 'others' != $_POST['currency']) ? $_POST['currency'] : usces_get_base_country();
			$this->options['system']['addressform'] = (isset($_POST['addressform']) ) ? $_POST['addressform'] : usces_get_local_addressform();
			$this->options['system']['target_market'] = (isset($_POST['target_market']) ) ? $_POST['target_market'] : usces_get_local_target_market();

			unset($this->options['province']);
			foreach((array)$this->options['system']['target_market'] as $target_market) {
				$province = array();
				if(!empty($_POST['province_'.$target_market])) {
					$_province = usces_change_line_break( $_POST['province_'.$target_market] );
					$temp_pref = explode("\n", $_province);
					$province[] = '-- Select --';
					foreach( $temp_pref as $pref ) {
						if( !WCUtils::is_blank($pref) )
							$province[] = trim($pref);
					}
					if( 1 == count($province) )
						$action_status = 'error';
				} else {
					if(isset($usces_states[$target_market]) && is_array($usces_states[$target_market])) {
						$province = $usces_states[$target_market];
					} else {
						$action_status = 'error';
					}
				}
				$this->options['province'][$target_market] = $province;
			}

			if($action_status != '') {
				$this->action_status = 'error';
				$this->action_message = __('Data have deficiency.','usces');
			} else {
				$this->action_status = 'success';
				$this->action_message = __('options are updated','usces');
			}

		} else {

			if( !isset($this->options['province']) || empty($this->options['province']) ){
				$this->options['province'][$this->options['system']['base_country']] = $usces_states[$this->options['system']['base_country']];
			}
			$this->action_status = 'none';
			$this->action_message = '';
		}

		if($action_status != 'error')
		update_option('usces', $this->options);

		require_once(USCES_PLUGIN_DIR . '/includes/admin_system.php');
	}

	/* Settlement Setting Page */
	function admin_settlement_page() {

		$this->action_status  = 'none';
		$this->action_message = '';

		$options = get_option( 'usces' );
		$mes     = '';

		if ( isset( $_POST['usces_option_update'] ) ) {

			check_admin_referer( 'admin_settlement', 'wc_nonce' );

			$_POST = $this->stripslashes_deep_post( $_POST );

			switch ( $_POST['acting'] ) {
				case 'settlement_selected':
					if ( isset( $_POST['settlement_selected'] ) ) {
						$settlement_selected = explode( ',', $_POST['settlement_selected'] );
						$payments            = usces_get_system_option( 'usces_payment_method', 'settlement' );
						$acting_payments     = array();
						foreach ( (array) $payments as $key => $payment ) {
							if ( 'activate' == $payment['use'] && false !== strpos( $key, 'acting_' ) ) {
								$acting = explode( '_', $key );
								if ( ! empty( $acting[1] ) && $acting[1] == 'paypal' ) {
									$acting_payments[] = $acting[1] . '_cp';
								} else {
									$acting_payments[] = apply_filters( 'usces_filter_acting_payment_slug', $acting[1], $acting );
								}
							}
						}
						$acting_payments      = array_unique( $acting_payments );
						$available_settlement = get_option( 'usces_available_settlement' );
						foreach ( (array) $acting_payments as $payment ) {
							if ( ! in_array( $payment, $settlement_selected ) ) {
								$settlement_name = $available_settlement[ $payment ];
								$mes            .= sprintf( __( '* %s can not be unselected. Please "delete" or "stop" the payment method.', 'usces' ), $settlement_name ) . '<br />';
							}
						}
						if ( WCUtils::is_blank( $mes ) ) {
							$unavailable_activate = 0;
							$unavailable_payments = apply_filters( 'usces_filter_unavailable_payments', get_option( 'usces_unavailable_settlement' ) );
							foreach ( (array) $unavailable_payments as $payment ) {
								if ( in_array( $payment, $settlement_selected ) ) {
									$unavailable_activate++;
								}
							}
							if ( 1 < $unavailable_activate ) {
								$mes .= __( '* Settlement that can not be used together is activated.', 'usces' ) . '<br />';
							}
						}

						if ( WCUtils::is_blank( $mes ) ) {
							update_option( 'usces_settlement_selected', $settlement_selected );
							$this->settlement->setup();
							$this->action_status  = 'success';
							$this->action_message = __( 'Updated.', 'usces' );
						} else {
							$this->action_status  = 'error';
							$this->action_message = __( 'Update failed.', 'usces' );
						}
					}
					break;
			}
			do_action( 'usces_action_admin_settlement_update', $mes );
		}

		$this->options = get_option( 'usces' );

		require_once( USCES_PLUGIN_DIR . '/includes/admin_settlement.php' );
	}

	/********************************************************************************/
	function selected( $selected, $current) {
		if ( $selected == $current)
			echo ' selected="selected"';
	}
	/********************************************************************************/

	function usces_session_start() {
		$options = get_option('usces');
		if( !isset($options['usces_key']) || empty($options['usces_key']) ){
			$options['usces_key'] =  uniqid('uk');
			update_option('usces', $options);
		}

		if(defined( 'USCES_KEY' )){
			if( is_admin() || preg_match('/\/wp-login\.php/', $_SERVER['REQUEST_URI']) ){
				session_name( 'adm'.USCES_KEY );
			}else{
				session_name( USCES_KEY );
			}
		}else{
			if( is_admin() || preg_match('/\/wp-login\.php/', $_SERVER['REQUEST_URI']) ){
				session_name( 'adm'.$options['usces_key'] );
			}else{
				session_name( $options['usces_key'] );
			}
		}


		if(isset($_GET['uscesid']) && !WCUtils::is_blank($_GET['uscesid'])) {
			$sessid = $_GET['uscesid'];
			$sessid = $this->uscesdc($sessid);
			session_id($sessid);
		}

		do_action( 'usces_action_session_start' );

		$httponly = true;
		$sitescheme = substr(get_option('siteurl'), 0, 5);
		$homescheme = substr(get_option('home'), 0, 5);
		if( $sitescheme == 'https' && $homescheme == 'https' ){
			$sslonly = true;
			$samesite = 'None';
		}else{
			$sslonly = false;
			$samesite = '';
		}

		if( version_compare( PHP_VERSION, '7.3.0', '>=' ) ){
			$cookie_options = array(
				'lifetime' => 0,
				'path' => USCES_COOKIEPATH,
				'domain' => '',
				'secure' => $sslonly,
				'httponly' => $httponly,
				'samesite' => $samesite
			);
			session_set_cookie_params( $cookie_options );
		}else{
			session_set_cookie_params( 0, USCES_COOKIEPATH, '', $sslonly, $httponly );
		}

		@session_start();

		if ( !isset($_SESSION['usces_member']) || $options['membersystem_state'] != 'activate' ){
			$_SESSION['usces_member'] = array();
		}

		if(!isset($_SESSION['usces_checked_business_days']))
			$this->update_business_days();
	}

	function usces_cookie() {
		if(is_admin()) return;

		$actionflag = false;
		$sess = NULL;
		$addr = NULL;
		$rckid = NULL;
		$none = NULL;
		$cookie = $this->get_cookie();

		if( isset($_GET['uscesid']) && !WCUtils::is_blank($_GET['uscesid']) ){
			$sessid = base64_decode(urldecode($_GET['uscesid']));
			list($sess, $addr, $rckid, $none) = explode('_', $sessid, 4);
		}
		if('acting' == $addr) return;

		if( apply_filters( 'usces_filter_cookie', false) ) return;

		//We need to consider.
		return;

		if( $this->use_ssl && ($this->is_cart_or_member_page($_SERVER['REQUEST_URI']) || $this->is_inquiry_page($_SERVER['REQUEST_URI']))){

			$refer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
			$sslid = isset($cookie['sslid']) ? $cookie['sslid'] : NULL;
			$option = get_option('usces');
			$parsed = parse_url(get_option('home'));
			$home = $parsed['host'] . (isset($parsed['path']) ? $parsed['path'] : '');
			$parsed = parse_url($option['ssl_url']);
			$sslhome = $parsed['host'] . (isset($parsed['path']) ? $parsed['path'] : '');

			if( empty($refer) || (false === strpos($refer, $home) && false === strpos($refer, $sslhome)) ){
				if( !empty($sslid) && !empty($rckid) && $sslid === $rckid ){
					$actionflag = true;
				}else{
					$actionflag = false;
				}
			}else{
				if( !empty($sslid) && $sslid !== $rckid ){
					$actionflag = false;
				}else{
					$actionflag = true;
				}
			}


			if( $actionflag ){
				$values = array(
							'id' => $rckid,
							'sslid' => $rckid,
							'name' => '',
							'rme' => ''
							);
				if( 'acting' !== $rckid ){
					$this->set_cookie($values);
				}
			}else{
				if( 'acting' !== $rckid ){
					unset($_SESSION['usces_member'], $_SESSION['usces_cart'], $_SESSION['usces_entry'] );
					wp_redirect( 'http://'.$home );
				}
			}
		}else{
			if( !isset($cookie['id']) || WCUtils::is_blank($cookie['id']) ) {
				$values = array(
							'id' => md5(uniqid(rand(), true)),
							'name' => '',
							'rme' => ''
							);
				$this->set_cookie($values);
				$_SESSION['usces_cookieid'] = $values['id'];
			} else {
				if( !isset($_SESSION['usces_cookieid']) || $_SESSION['usces_cookieid'] != $cookie['id'])
					$_SESSION['usces_cookieid'] = $cookie['id'];
			}

			$actionflag = true;
		}

	}

	function set_cookie($values, $key='usces_cookie'){
		if( !isset($_GET['uscesid']) || WCUtils::is_blank($_GET['uscesid'])) {
			session_regenerate_id( true );
		}
		$value = usces_serialize($values);
		$timeout = time()+7*86400;
		$timeout = apply_filters( 'usces_filter_set_cookie_timeout', $timeout, $values, $key);
		$domain = $_SERVER['SERVER_NAME'];

		$httponly = true;
		$sitescheme = substr(get_option('siteurl'), 0, 5);
		$homescheme = substr(get_option('home'), 0, 5);
		if( $sitescheme == 'https' && $homescheme == 'https' ){
			$sslonly = true;
			$samesite = 'None';
		}else{
			$sslonly = false;
			$samesite = '';
		}

		if (version_compare(PHP_VERSION, '7.3.0', '>=')) {
			$cookie_options = array(
				'expires' => $timeout,
				'path' => USCES_COOKIEPATH,
				'domain' => $domain,
				'secure' => $sslonly,
				'httponly' => $httponly,
				'samesite' => $samesite
			);
			$res = setcookie($key, $value, $cookie_options );
		}else{
			$res = setcookie($key, $value, $timeout, USCES_COOKIEPATH, $domain, true, true );
		}
	}

	function get_cookie($key='usces_cookie') {
		$values = isset($_COOKIE[$key]) ? usces_unserialize(stripslashes($_COOKIE[$key])) : NULL;
		return $values;
	}

	function get_access( $key, $type, $date ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "usces_access";

		$query = $wpdb->prepare("SELECT acc_value FROM $table_name WHERE acc_key = %s AND acc_type = %s AND acc_date = %s", $key, $type, $date);
		$value = $wpdb->get_var( $query );
		if( !$value ){
			$res = NULL;
		}else{
			$res = unserialize($value);
		}

		return $res;
	}

	function get_access_piriod( $key, $type, $startday, $endday ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "usces_access";
		$query = $wpdb->prepare("SELECT acc_type, acc_value, acc_date FROM $table_name WHERE acc_key = %s AND acc_type = %s AND (acc_date >= %s AND acc_date <= %s)", $key, $type, $startday, $endday);
		$res = $wpdb->get_results( $query, ARRAY_A );

		return $res;
	}

	function update_access( $array ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "usces_access";

		$query = $wpdb->prepare("SELECT ID FROM $table_name WHERE acc_key = %s AND acc_type = %s AND acc_date = %s", $array['acc_key'], $array['acc_type'], $array['acc_date']);
		$res = $wpdb->get_var( $query );
		if(empty($res)){
			$query = $wpdb->prepare("INSERT INTO $table_name (acc_key, acc_type, acc_value, acc_date) VALUES(%s, %s, %s, %s)", $array['acc_key'], $array['acc_type'], serialize($array['acc_value']), $array['acc_date']);
			$wpdb->query( $query );
		}else{
			$query = $wpdb->prepare("UPDATE $table_name SET acc_value = %s WHERE acc_key = %s AND acc_type = %s AND acc_date = %s", serialize($array['acc_value']), $array['acc_key'], $array['acc_type'], $array['acc_date']);
			$wpdb->query( $query );
		}
	}

	function get_uscesid( $flag = true) {

		$sessname = session_name();
		$sessid = session_id();
		$sessid = $this->uscescv($sessid, $flag);
		return $sessid;
	}

	function shop_head() {
		global $post;
		$this->item = $post;
		if( $this->is_cart_or_member_page($_SERVER['REQUEST_URI']) ){
			echo "<meta name='robots' content='noindex,nofollow' />\n";
		}
	}

	/************************************************
	* caught by wp_footer action hook
	************************************************/
	function shop_foot() {
		global $current_user;

		$item = $this->item;
		if( empty($item) ){
			$item = new stdClass;
			$item->ID = 0;
			$item->post_mime_type = '';
		}

		wp_get_current_user();

		//usces_cart.js is not used.
		if( $this->is_cart_or_member_page($_SERVER['REQUEST_URI']) || $this->is_inquiry_page($_SERVER['REQUEST_URI']) ){
			$javascript_url = USCES_FRONT_PLUGIN_URL . '/js/usces_cart.js';
		}else{
			$javascript_url = USCES_WP_CONTENT_URL . '/plugins/' . USCES_PLUGIN_FOLDER . '/js/usces_cart.js';
		}

		$this->previous_url = isset($_SESSION['usces_previous_url']) ? $_SESSION['usces_previous_url'] : get_home_url();

		//If the product object does't exists
		if( $this->use_js && empty($this->item) ) {
?>
	<script type='text/javascript'>
		uscesL10n = {
			<?php echo apply_filters('usces_filter_uscesL10n', NULL, $item->ID); ?>

			'ajaxurl': "<?php echo esc_url( $this->ssl_admin_ajax_url() ); ?>"
		}
	</script>
<?php

		//If the product object exists
		}elseif( $this->use_js && !empty($this->item) ){

			$ioptkeys = $this->get_itemOptionKey( $item->ID );
			$mes_opts_str = "";
			$key_opts_str = "";
			$opt_means = "";
			$opt_esse = "";
			if($ioptkeys){
				foreach($ioptkeys as $key => $value){
					$optValues = $this->get_itemOptions( $value, $item->ID );
					if ( false === $optValues ) {
						continue;
					}
					if($optValues['means'] < 2 || 3 == $optValues['means'] || 4 == $optValues['means'] ){
						$mes_opts_str .= "'" . sprintf(__("Chose the %s", 'usces'), esc_js( apply_filters( 'usces_filter_uscesL10n_option_name', $value, $optValues ) ) ) . "',";
					}else{
						$mes_opts_str .= "'" . sprintf(__("Input the %s", 'usces'), esc_js( apply_filters( 'usces_filter_uscesL10n_option_name', $value, $optValues ) ) ) . "',";
					}
					$key_opts_str .= "'" . urlencode(esc_js($value)) . "',";
					$opt_means .= "'" . esc_js($optValues['means']) . "',";
					$opt_esse .= "'" . esc_js($optValues['essential']) . "',";
				}
				$mes_opts_str = rtrim($mes_opts_str, ',');
				$key_opts_str = rtrim($key_opts_str, ',');
				$opt_means = rtrim($opt_means, ',');
				$opt_esse = rtrim($opt_esse, ',');
			}

			$product             = wel_get_product( $item->ID );
			$itemRestriction     = isset( $product['itemRestriction'] ) ? $product['itemRestriction'] : '';
			$wcpage              = usces_page_name('return');
			$nonce_action        = 'wc_confirm';
			$itemOrderAcceptable = $this->getItemOrderAcceptable( $item->ID );
?>
	<script type='text/javascript'>
		uscesL10n = {
			<?php echo apply_filters('usces_filter_uscesL10n', NULL, $item->ID); ?>

			'ajaxurl': "<?php echo esc_url( $this->ssl_admin_ajax_url() ); ?>",
			'loaderurl': "<?php echo USCES_PLUGIN_URL . 'images/loading.gif'; ?>",
			'post_id': "<?php echo esc_js( $item->ID ); ?>",
			'cart_number': "<?php echo get_option('usces_cart_number'); ?>",
			'is_cart_row': <?php echo ( (0 < $this->cart->num_row()) ? 'true' : 'false'); ?>,
			'opt_esse': new Array( <?php wel_esc_script_e( $opt_esse ); ?> ),
			'opt_means': new Array( <?php wel_esc_script_e( $opt_means ); ?> ),
			'mes_opts': new Array( <?php wel_esc_script_e( $mes_opts_str ); ?> ),
			'key_opts': new Array( <?php wel_esc_script_e( $key_opts_str ); ?> ),
			'previous_url': "<?php echo esc_url( $this->previous_url ); ?>",
			'itemRestriction': "<?php echo esc_js( $itemRestriction ); ?>",
			'itemOrderAcceptable': "<?php echo esc_js( $itemOrderAcceptable ); ?>",
			'uscespage': "<?php echo esc_js( $wcpage ); ?>",
			'uscesid': "<?php echo esc_js( $this->get_uscesid(false) ); ?>",
			'wc_nonce': "<?php echo wp_create_nonce($nonce_action); ?>"
		}
	</script>
	<script type='text/javascript' src='<?php echo esc_url( $javascript_url ); ?>'></script>
<?php
		}

		ob_start();

		//If it's Product Details page
		if( $this->use_js && (is_singular() && 'item' == $item->post_mime_type) ){
?>
	<script type='text/javascript'>
	(function($) {
	uscesCart = {
		intoCart : function (post_id, sku) {
			var zaikonum = $("[id='zaikonum["+post_id+"]["+sku+"]']").val();
			var zaiko = $("[id='zaiko["+post_id+"]["+sku+"]']").val();
			if( <?php echo apply_filters( 'usces_intoCart_zaiko_check_js', "( uscesL10n.itemOrderAcceptable != '1' && zaiko != '0' && zaiko != '1' ) || ( uscesL10n.itemOrderAcceptable != '1' && parseInt(zaikonum) == 0 )" ); ?> ){
				alert('<?php _e('temporaly out of stock now', 'usces'); ?>');
				return false;
			}

			var mes = '';
			if( $("[id='quant["+post_id+"]["+sku+"]']").length ){
				var quant = $("[id='quant["+post_id+"]["+sku+"]']").val();
				if( quant == '0' || quant == '' || !(uscesCart.isNum(quant))){
					mes += "<?php _e('enter the correct amount', 'usces'); ?>\n";
				}
				var checknum = '';
				var checkmode = '';
				if( parseInt(uscesL10n.itemRestriction) <= parseInt(zaikonum) && uscesL10n.itemRestriction != '' && uscesL10n.itemRestriction != '0' && zaikonum != '' ) {
					checknum = uscesL10n.itemRestriction;
					checkmode ='rest';
				} else if( uscesL10n.itemOrderAcceptable != '1' && parseInt(uscesL10n.itemRestriction) > parseInt(zaikonum) && uscesL10n.itemRestriction != '' && uscesL10n.itemRestriction != '0' && zaikonum != '' ) {
					checknum = zaikonum;
					checkmode ='zaiko';
				} else if( uscesL10n.itemOrderAcceptable != '1' && (uscesL10n.itemRestriction == '' || uscesL10n.itemRestriction == '0') && zaikonum != '' ) {
					checknum = zaikonum;
					checkmode ='zaiko';
				} else if( uscesL10n.itemRestriction != '' && uscesL10n.itemRestriction != '0' && ( zaikonum == '' || zaikonum == '0' || parseInt(uscesL10n.itemRestriction) > parseInt(zaikonum) ) ) {
					checknum = uscesL10n.itemRestriction;
					checkmode ='rest';
				}

				if( parseInt(quant) > parseInt(checknum) && checknum != '' ){
					if(checkmode == 'rest'){
						mes += <?php _e("'This article is limited by '+checknum+' at a time.'", 'usces'); ?>+"\n";
					}else{
						mes += <?php _e("'Stock is remainder '+checknum+'.'", 'usces'); ?>+"\n";
					}
				}
			}
			for(i=0; i<uscesL10n.key_opts.length; i++){
				if( uscesL10n.opt_esse[i] == '1' ){
					var skuob = $("[id='itemOption["+post_id+"]["+sku+"]["+uscesL10n.key_opts[i]+"]']");
					var itemOption = "itemOption["+post_id+"]["+sku+"]["+uscesL10n.key_opts[i]+"]";
					var opt_obj_radio = $(":radio[name*='"+itemOption+"']");
					var opt_obj_checkbox = $(":checkbox[name*='"+itemOption+"']:checked");

					if( uscesL10n.opt_means[i] == '3' ){

						if( !opt_obj_radio.is(':checked') ){
							mes += uscesL10n.mes_opts[i]+"\n";
						}

					}else if( uscesL10n.opt_means[i] == '4' ){

						if( !opt_obj_checkbox.length ){
							mes += uscesL10n.mes_opts[i]+"\n";
						}

					}else{

						if( skuob.length ){
							if( uscesL10n.opt_means[i] == 0 && skuob.val() == '#NONE#' ){
								mes += uscesL10n.mes_opts[i]+"\n";
							}else if( uscesL10n.opt_means[i] == 1 && ( skuob.val() == '' || skuob.val() == '#NONE#' ) ){
								mes += uscesL10n.mes_opts[i]+"\n";
							}else if( uscesL10n.opt_means[i] >= 2 && skuob.val() == '' ){
								mes += uscesL10n.mes_opts[i]+"\n";
							}
						}
					}
				}
			}

			<?php apply_filters( 'usces_filter_inCart_js_check', $item->ID ); //Unavailable ?>
			<?php do_action( 'usces_action_inCart_js_check', $item->ID ); ?>

			if( mes != '' ){
				alert( mes );
				return false;
			}else{
				<?php echo apply_filters('usces_filter_js_intoCart', "return true;\n", $item->ID, NULL); ?>
			}
		},

		isNum : function (num) {
			if (num.match(/[^0-9]/g)) {
				return false;
			}
			return true;
		}
	};
	})(jQuery);
	</script>
<?php
		//If it's Product Details page
		}elseif( $this->use_js && (is_page(USCES_CART_NUMBER) || $this->is_cart_page($_SERVER['REQUEST_URI'])) ){
?>
	<script type='text/javascript'>
	(function($) {
	uscesCart = {
		upCart : function () {

			var zaikoob = $("input[name*='zaikonum']");
			var quantob = $("input[name*='quant']");
			var postidob = $("input[name*='itempostid']");
			var skuob = $("input[name*='itemsku']");

			var zaikonum = '';
			var zaiko = '';
			var quant = '';
			var mes = '';
			var checknum = '';
			var post_id = '';
			var sku = '';
			var itemRestriction = '';
			var itemOrderAcceptable = '';

			var ct = zaikoob.length;
			for(var i=0; i< ct; i++){
				post_id = postidob[i].value;
				sku = skuob[i].value;
				itemRestriction = $("input[name='itemRestriction\[" + i + "\]']").val();
				itemOrderAcceptable = $("input[name='itemOrderAcceptable\[" + i + "\]']").val();
				zaikonum = $("input[name='zaikonum\[" + i + "\]\[" + post_id + "\]\[" + sku + "\]']").val();

				quant = $("*[name='quant\[" + i + "\]\[" + post_id + "\]\[" + sku + "\]']").val();
				if( $("*[name='quant\[" + i + "\]\[" + post_id + "\]\[" + sku + "\]']").length ){
					if( quant == '0' || quant == '' || !(uscesCart.isNum(quant))){
						mes += <?php _e("'enter the correct amount for the No.' + (i+1) + ' item'", 'usces'); ?>+"\n";
					}
					var checknum = '';
					var checkmode = '';
					if( parseInt(itemRestriction) <= parseInt(zaikonum) && itemRestriction != '' && itemRestriction != '0' && zaikonum != '' ) {
						checknum = itemRestriction;
						checkmode ='rest';
					} else if( itemOrderAcceptable != '1' && parseInt(itemRestriction) > parseInt(zaikonum) && itemRestriction != '' && itemRestriction != '0' && zaikonum != '' ) {
						checknum = zaikonum;
						checkmode ='zaiko';
					} else if( itemOrderAcceptable != '1' && (itemRestriction == '' || itemRestriction == '0') && zaikonum != '' ) {
						checknum = zaikonum;
						checkmode ='zaiko';
					} else if( itemRestriction != '' && itemRestriction != '0' && ( zaikonum == '' || zaikonum == '0' || parseInt(itemRestriction) > parseInt(zaikonum) ) ) {
						checknum = itemRestriction;
						checkmode ='rest';
					}
					if( parseInt(quant) > parseInt(checknum) && checknum != '' ){
						if(checkmode == 'rest'){
							mes += <?php _e("'This article is limited by '+checknum+' at a time for the No.' + (i+1) + ' item.'", 'usces'); ?>+"\n";
						}else{
							mes += <?php _e("'Stock of No.' + (i+1) + ' item is remainder '+checknum+'.'", 'usces'); ?>+"\n";
						}
					}
				}
			}

			<?php apply_filters( 'usces_filter_upCart_js_check', $item->ID ); //Unavailable ?>
			<?php do_action( 'usces_action_upCart_js_check', $item->ID ); ?>

			if( mes != '' ){
				alert( mes );
				return false;
			}else{
				<?php echo apply_filters('usces_filter_js_upCart', "return true;\n", $item->ID, NULL); ?>
			}
		},

		cartNext : function () {

			var zaikoob = $("input[name*='zaikonum']");
			var quantob = $("input[name*='quant']");
			var postidob = $("input[name*='itempostid']");
			var skuob = $("input[name*='itemsku']");

			var zaikonum = '';
			var zaiko = '';
			var quant = '';
			var mes = '';
			var checknum = '';
			var post_id = '';
			var sku = '';
			var itemRestriction = '';
			var itemOrderAcceptable = '';

			var ct = zaikoob.length;
			for(var i=0; i< ct; i++){
				post_id = postidob[i].value;
				sku = skuob[i].value;
				itemRestriction = $("input[name='itemRestriction\[" + i + "\]']").val();
				itemOrderAcceptable = $("input[name='itemOrderAcceptable\[" + i + "\]']").val();
				zaikonum = $("input[name='zaikonum\[" + i + "\]\[" + post_id + "\]\[" + sku + "\]']").val();

				quant = $("*[name='quant\[" + i + "\]\[" + post_id + "\]\[" + sku + "\]']").val();
				if( $("*[name='quant\[" + i + "\]\[" + post_id + "\]\[" + sku + "\]']").length ){
					if( quant == '0' || quant == '' || !(uscesCart.isNum(quant))){
						mes += <?php _e("'enter the correct amount for the No.' + (i+1) + ' item'", 'usces'); ?>+"\n";
					}
					var checknum = '';
					var checkmode = '';
					if( parseInt(itemRestriction) <= parseInt(zaikonum) && itemRestriction != '' && itemRestriction != '0' && zaikonum != '' ) {
						checknum = itemRestriction;
						checkmode ='rest';
					} else if( itemOrderAcceptable != '1' && parseInt(itemRestriction) > parseInt(zaikonum) && itemRestriction != '' && itemRestriction != '0' && zaikonum != '' ) {
						checknum = zaikonum;
						checkmode ='zaiko';
					} else if( itemOrderAcceptable != '1' && (itemRestriction == '' || itemRestriction == '0') && zaikonum != '' ) {
						checknum = zaikonum;
						checkmode ='zaiko';
					} else if( itemRestriction != '' && itemRestriction != '0' && ( zaikonum == '' || zaikonum == '0' || parseInt(itemRestriction) > parseInt(zaikonum) ) ) {
						checknum = itemRestriction;
						checkmode ='rest';
					}

					if( parseInt(quant) > parseInt(checknum) && checknum != '' ){
						if(checkmode == 'rest'){
							mes += <?php _e("'This article is limited by '+checknum+' at a time for the No.' + (i+1) + ' item.'", 'usces'); ?>+"\n";
						}else{
							mes += <?php _e("'Stock of No.' + (i+1) + ' item is remainder '+checknum+'.'", 'usces'); ?>+"\n";
						}
					}
				}
			}
			if( mes != '' ){
				alert( mes );
				return false;
			}else{
				return true;
			}
		},

		previousCart : function () {
			location.href = uscesL10n.previous_url;
		},

		settings: {
			url: uscesL10n.ajaxurl,
			type: 'POST',
			cache: false
		},

		changeStates : function( country ) {
			var s = this.settings;
			s.data = "action=change_states_ajax&country=" + country;
			$.ajax( s ).done(function(data, dataType){

				if( 'error' == data ){
					alert('error');
				}else{
					$("select#pref").html( data );
				}
			}).fail(function(msg){
				alert("error");
			});
			return false;
		},

		isNum : function (num) {
			if (num.match(/[^0-9]/g)) {
				return false;
			}
			return true;
		},
		purchase : 0
	};
	$("#country").change(function () {
		var country = $("#country option:selected").val();
		$("#newcharging_type option:selected").val()
		uscesCart.changeStates( country );
	});
	$("#purchase_form").submit(function () {
		if( 0 == uscesCart.purchase ){
			uscesCart.purchase = 1;
			return true;
		}else{
			$("#purchase_button").prop("disabled", true);
			$("#back_button").prop("disabled", true);
			return false;
		}
	});

	})(jQuery);
	</script>
<?php
		}

		usces_states_form_js();
		$js = apply_filters( 'usces_filter_shop_foot_js', ob_get_contents() );
		ob_end_clean();
		echo $js; // no escape due to script.

	}

	function admin_head() {
		global $wp_version;
		$wcex_str = '';
		$wcex = usces_get_wcex();
		foreach ( (array)$wcex as $key => $values ) {
			$wcex_str .= "'" . esc_js($key) . "-" . esc_js($values['version']) . "', ";
		}
		$wcex_str = rtrim($wcex_str, ', ');
		if ( version_compare($wp_version, '3.4', '>=') ){
			$theme_ob = wp_get_theme();
			$theme['Name'] = esc_js($theme_ob->get('Name'));
			$theme['Version'] = esc_js($theme_ob->get('Version'));
		}else{
			$theme = get_theme_data( get_stylesheet_directory().'/style.css' );
		}
		$message = usces_get_admin_script_message();
?>
		<link href="<?php echo USCES_PLUGIN_URL; ?>/css/admin_style.css" rel="stylesheet" type="text/css" media="all" />
		<script type='text/javascript'>
		/* <![CDATA[ */
			uscesL10n = {
				<?php echo apply_filters('usces_filter_admin_uscesL10n', NULL ); ?>
				'requestFile': "<?php echo site_url(); ?>/wp-admin/admin-ajax.php",
				'USCES_PLUGIN_URL': "<?php echo USCES_PLUGIN_URL; ?>",
				'version': "<?php echo USCES_VERSION; ?>",
				'wcid': "<?php echo get_option('usces_wcid'); ?>",
				'locale': '<?php echo get_locale(); ?>',
				'cart_number': "<?php echo get_option('usces_cart_number'); ?>",
				'purchase_limit': "<?php echo esc_js( $this->options['purchase_limit'] ); ?>",
				'point_rate': "<?php echo esc_js( $this->options['point_rate'] ); ?>",
				'shipping_rule': "<?php echo esc_js( $this->options['shipping_rule'] ); ?>",
				'theme': "<?php echo esc_html( $theme['Name'] . '-' . $theme['Version'] ); ?>",
				'wcex': new Array( <?php wel_esc_script_e( $wcex_str ); ?> ),
				'message': new Array( <?php wel_esc_script_e( $message ); ?> ),
				'now_loading': "<?php _e('now loading', 'usces'); ?>"
			};
		/* ]]> */
		</script>
		<script type='text/javascript' src='<?php echo USCES_PLUGIN_URL; ?>/js/usces_admin.js?ver=<?php echo USCES_VERSION; ?>'></script>
<?php
		if($this->action_status == 'edit' || $this->action_status == 'editpost'){
?>
			<link rel='stylesheet' href='<?php echo site_url(); ?>/wp-includes/js/thickbox/thickbox.css' type='text/css' media='all' />
<?php
		}
		if( isset($_REQUEST['page']) ){
			switch( $_REQUEST['page'] ){
				case 'usces_initial':
?>
					<script type='text/javascript'>
					/* <![CDATA[ */
						usces_ini = {
							'cod_type': "<?php if( 'change' == $this->options['cod_type'] ) {echo 'change';}else{echo 'fix';} ?>",
							'cod_type_fix': "<?php echo esc_js(__('Fixation C.O.D.', 'usces')); ?>",
							'cod_type_change': "<?php echo esc_js(__('Variable C.O.D.', 'usces')); ?>",
							'cod_unit': "<?php echo esc_js(__('dollars', 'usces')); ?>",
							'cod_failure': "<?php echo esc_js(__('failure in update', 'usces')); ?>",
							'cod_updated': "<?php echo esc_js(__('options are updated', 'usces')); ?>",
							'cod_limit': "<?php echo esc_js(__('A value of the amount of upper limit is incorrect.', 'usces')); ?>",
							'cod_label_close': "<?php echo esc_js(__('Close', 'usces')); ?>",
							'cod_label_update': "<?php echo esc_js(__('Update', 'usces')); ?>"
						};
/* ]]> */
					</script>
<?php
					break;
				case 'usces_itemnew':
				case 'usces_itemedit':
?>
					<style type="text/css">
					<!--
					#usces_mess {
						color: #FF0000;
						font-weight: bold;
					}
					-->
					</style>
<?php
					break;
			}
		}
?>
<?php
		if( is_admin() && ( (isset($_GET['order_action']) && 'newpost' == $_GET['order_action'])
							|| (isset($_GET['page']) && 'usces_ordernew' == $_GET['page'])
							|| (isset($_GET['order_action']) && 'edit' == $_GET['order_action'])
							|| (isset($_GET['order_action']) && 'editpost' == $_GET['order_action'])
							|| (isset($_GET['member_action']) && 'edit' == $_GET['member_action'])
							|| (isset($_GET['member_action']) && 'editpost' == $_GET['member_action'])) ) :
			switch( $_GET['page'] ){
				case 'usces_ordernew':
				case 'usces_orderlist':
					$admin_page = 'order';
					break;
				case 'usces_memberlist':
					$admin_page = 'member';
					break;
			}
?>
		<script type='text/javascript'>
		jQuery(function($) {
		uscesForm = {
			settings: {
				url: uscesL10n.requestFile,
				type: 'POST',
				cache: false
			},

			changeStates : function( country, type ) {
				var s = this.settings;
				s.data = "action=change_states_ajax&country=" + country;
				$.ajax( s ).done(function(data, dataType){
					if( 'error' == data ){
						alert('error');
					}else{
						$("select#" + type + "_pref").html( data );
						if( customercountry == country && 'customer' == type ){
							$("#" + type + "_pref").prop({selectedIndex:customerstate});
						}else if( deliverycountry == country && 'delivery' == type ){
							$("#" + type + "_pref").prop({selectedIndex:deliverystate});
						}else if( customercountry == country && 'member' == type ){
							$("#" + type + "_pref").prop({selectedIndex:customerstate});
						}
					}
				}).fail(function(msg){
					alert("error");
				});
				return false;
			},

			isNum : function (num) {
				if (num.match(/[^0-9]/g)) {
					return false;
				}
				return true;
			}
		};
<?php
		if( 'order' == $admin_page ){
?>
		if( undefined != $("#customer_pref").get(0) && undefined != $("#delivery_country").get(0) ) {
			var customerstate = $("#customer_pref").get(0).selectedIndex;
			var customercountry = $("#customer_country").val();
			var deliverystate = $("#delivery_pref").get(0).selectedIndex;
			var deliverycountry = $("#delivery_country").val();

			$("#customer_country").change(function () {
				var country = $("#customer_country option:selected").val();
				uscesForm.changeStates( country, 'customer' );
			});
			$("#delivery_country").change(function () {
				var country = $("#delivery_country option:selected").val();
				uscesForm.changeStates( country, 'delivery' );
			});
		}
<?php
		}else if( 'member' == $admin_page ){
?>
		if( undefined != $("#member_pref").get(0) ) {
			var customerstate = $("#member_pref").get(0).selectedIndex;
			var customercountry = $("#member_country").val();
			var deliverystate = '';
			var deliverycountry = '';

			$("#member_country").change(function () {
				var country = $("#member_country option:selected").val();
				uscesForm.changeStates( country, 'member' );
			});
		}
<?php
		}
?>
		});
		</script>
<?php
		endif;
}

	function main() {
		global $wpdb, $wp_locale, $wp_version, $post_ID;
		global $wp_query, $usces_action, $post, $action, $editing;

		update_option('usces_shipping_rule', apply_filters('usces_filter_shipping_rule', get_option('usces_shipping_rule')));
		$this->shipping_rule = get_option('usces_shipping_rule');

		if( !is_admin() ){
			$this->usces_cookie();
		}else{
			$this->user_level = usces_get_admin_user_level();
		}
		$this->make_url();


		add_filter('cron_schedules', 'usces_schedules_intervals');
		add_action( 'wp', 'usces_wevent');
		do_action('usces_main');
		$this->update_table();


		require_once(USCES_PLUGIN_DIR . '/classes/cart.class.php');
		$this->cart = new usces_cart();

		do_action('usces_after_cart_instant');

		if( isset($_REQUEST['page']) && $_REQUEST['page'] == 'usces_itemedit' && isset($_REQUEST['action']) && $_REQUEST['action'] == 'duplicate' ){
			$post_id = (int)$_GET['post'];
			$new_id = usces_item_duplicate($post_id);
			$ref = isset($_REQUEST['usces_referer']) ? urlencode(esc_url($_REQUEST['usces_referer'])) : '';
			$url = USCES_ADMIN_URL . '?page=usces_itemedit&action=edit&post=' . $new_id . '&usces_referer=' . $ref;
			wp_redirect($url);
			exit;
		}else if( isset($_REQUEST['page']) && $_REQUEST['page'] == 'usces_itemedit' && isset($_REQUEST['action']) && $_REQUEST['action'] == 'itemcsv' ){
		//	$filename = usces_item_uploadcsv();
		//	$mode = ( isset($_REQUEST['upload_mode']) ) ? $_REQUEST['upload_mode'] : 'all';
		//	$url = USCES_ADMIN_URL . '?page=usces_itemedit&usces_status=none&usces_message=&action=upload_register&mode=' . $mode . '&regfile=' . $filename;
		//	wp_redirect($url);
		//	exit;
		}

		$this->ad_controller();

		if( isset($_GET['page']) && $_GET['page'] == 'usces_itemnew'){
			$itemnew = 'new';
		}else{
			$itemnew = '';
		}

		wp_enqueue_script('jquery');

		if( is_admin() && isset($_REQUEST['page']) && ((isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit') || $itemnew == 'new' || (isset($_REQUEST['action']) && $_REQUEST['action'] == 'editpost'))) {

			if(isset($_REQUEST['action']) && $_REQUEST['action'] != 'editpost' && $itemnew == 'new'){
				if ( version_compare($wp_version, '3.0-beta', '>') ){
					if ( !isset($_GET['post_type']) )
						$post_type = 'post';
					elseif ( in_array( $_GET['post_type'], get_post_types( array('public' => true ) ) ) )
						$post_type = $_GET['post_type'];
					else
						wp_die( __('Invalid post type') );
					$post_type_object = get_post_type_object($post_type);
					$editing = true;
                    $post = $this->get_default_post_to_edit30( $post_type, true );
                    $post_ID = $post->ID;

				}else{
					$post = $this->get_default_post_to_edit();
				}
			}else{
				if ( version_compare($wp_version, '3.0-beta', '>') ){
					if ( isset($_GET['post']) )
						$post_id = (int) $_GET['post'];
					elseif ( isset($_POST['post_ID']) )
						$post_id = (int) $_POST['post_ID'];
					else
						$post_id = 0;
					$post_ID = $post_id;
					$post = null;
					$post_type_object = null;
					$post_type = null;
					if ( $post_id ) {
						$product = wel_get_product( $post_id );
						$post = $product['_pst'];
						if ( $post ) {
							$post_type_object = get_post_type_object($post->post_type);
							if ( $post_type_object ) {
								$post_type = $post->post_type;
								if( !isset($current_screen) ){
									$current_screen = new stdClass();
								}
								$current_screen->post_type = $post->post_type;
								$current_screen->id = $current_screen->post_type;
							}
						}
					} elseif ( isset($_POST['post_type']) ) {
						$post_type_object = get_post_type_object($_POST['post_type']);
						if ( $post_type_object ) {
							$post_type = $post_type_object->name;
							$current_screen->post_type = $post_type;
							$current_screen->id = $current_screen->post_type;
						}
					}


				} else {
					if ( isset( $_GET['post'] ) ) {
						$post_ID =  (int) $_GET['post'];
						$product = wel_get_product( $post_ID );
						$post    = $product['_pst'];
					}else{
						$post_ID =  isset( $_REQUEST['post_ID'] ) ? (int) $_REQUEST['post_ID'] : 0;
						if ( ! empty( $post_ID ) ) {
							$product = wel_get_product( $post_ID );
							$post    = $product['_pst'];
						}
					}
				}

			}
			$editing = true;
			wp_enqueue_script('autosave');
			wp_enqueue_script('post');
			add_thickbox();
			wp_enqueue_script('media-upload');
			wp_enqueue_script('word-count');
			wp_enqueue_script( 'admin-comments' );

			if ( version_compare($wp_version, '3.3', '<') )
				add_action( 'admin_print_footer_scripts', 'wp_tiny_mce', 25 );
			wp_enqueue_script('quicktags');

		}

		if( is_admin() && isset($_REQUEST['page']) ){

			wp_enqueue_script('jquery-color');

			switch( $_REQUEST['page'] ){

				case 'usces_initial':
					$js = USCES_FRONT_PLUGIN_URL.'/js/usces_initial.js';
					wp_enqueue_script('usces_initial.js', $js, array('jquery-ui-dialog', 'jquery-ui-sortable'));
					break;
				case 'usces_settlement':
					wp_enqueue_script('jquery-ui-tabs', array('jquery-ui-core'));
					$jquery_cookieUrl = USCES_FRONT_PLUGIN_URL.'/js/jquery/jquery.cookie.js';
					wp_enqueue_script( 'jquery-cookie', $jquery_cookieUrl, array('jquery') );
					$jquery_colorUrl = USCES_FRONT_PLUGIN_URL.'/js/jquery/color/jscolor.js';
					wp_enqueue_script( 'jquery-jscolor', $jquery_colorUrl, array('jquery-color') );
					wp_enqueue_script( 'jquery-ui-sortable' );
					wp_enqueue_script( 'jquery-ui-dialog' );
					break;
				case 'usces_cart':
					wp_enqueue_script('jquery-ui-tabs', array('jquery-ui-core'));
					$jquery_cookieUrl = USCES_FRONT_PLUGIN_URL.'/js/jquery/jquery.cookie.js';
					wp_enqueue_script( 'jquery-cookie', $jquery_cookieUrl, array('jquery') );
					break;
				case 'usces_member':
					wp_enqueue_script('jquery-ui-tabs', array('jquery-ui-core'));
					$jquery_cookieUrl = USCES_FRONT_PLUGIN_URL.'/js/jquery/jquery.cookie.js';
					wp_enqueue_script( 'jquery-cookie', $jquery_cookieUrl, array('jquery') );
					break;
				case 'usces_orderlist':
				case 'usces_ordernew':
					wp_enqueue_script( 'jquery-ui-datepicker' );
					wp_enqueue_script('jquery-ui-dialog');
					break;
				case 'usces_memberlist':
					wp_enqueue_script('jquery-ui-dialog');
					break;
				case 'usces_itemnew':
					wp_enqueue_script('jquery-ui-sortable');
					break;
				case 'usces_itemedit':
                    $jquery_cookieUrl = USCES_FRONT_PLUGIN_URL.'/js/jquery/jquery.cookie.js';
                    wp_enqueue_script( 'jquery-cookie', $jquery_cookieUrl, array('jquery') );
					if( isset($_REQUEST['action']) && 'upload_register' == $_REQUEST['action'] ){
						//$upload_registerUrl = USCES_FRONT_PLUGIN_URL . '/js/usces-item-upload.js';
						//wp_enqueue_script( 'upload_register', $upload_registerUrl, array( 'jquery' ), USCES_VERSION, true );
						//@ob_end_clean();
						//ob_start();
					}else{
						wp_enqueue_script('jquery-ui-sortable');
						wp_enqueue_script('jquery-ui-dialog');
					}
		
					break;
				case 'usces_delivery':
					wp_enqueue_script('jquery-ui-tabs', array('jquery-ui-core'));
					$jquery_cookieUrl = USCES_FRONT_PLUGIN_URL.'/js/jquery/jquery.cookie.js';
					wp_enqueue_script( 'jquery-cookie', $jquery_cookieUrl, array('jquery') );
					break;
				case 'usces_system':
					wp_enqueue_script('jquery-ui-tabs', array('jquery-ui-core'));
					$jquery_cookieUrl = USCES_FRONT_PLUGIN_URL.'/js/jquery/jquery.cookie.js';
					wp_enqueue_script( 'jquery-cookie', $jquery_cookieUrl, array('jquery') );
					break;
				case 'usces_mail':
					wp_enqueue_script( 'jquery-ui-tabs', array( 'jquery-ui-core' ) );
					$jquery_cookieUrl = USCES_FRONT_PLUGIN_URL . '/js/jquery/jquery.cookie.js';
					wp_enqueue_script( 'jquery-cookie', $jquery_cookieUrl, array( 'jquery' ) );
					break;
			}
		}

		if( isset($_REQUEST['order_action']) && $_REQUEST['order_action'] == 'pdfout' ){
			$oid = filter_input( INPUT_GET, 'order_id' );
			if ( ! is_user_logged_in() ) {
				$this->get_current_member();
				$mid = $this->current_member['id'];
				if ( 0 === (int) $mid || !$this->is_order( $mid, $oid ) ) {
					die('No permission');
				}
			}
			if( ( isset( $this->options['tax_display'] ) && 'activate' == $this->options['tax_display'] ) && usces_is_reduced_taxrate( $oid ) ) {
				require_once( apply_filters( 'usces_filter_orderpdf_path_ex', USCES_PLUGIN_DIR . '/includes/order_print_ex.php' ) );
			} else {
				require_once( apply_filters( 'usces_filter_orderpdf_path', USCES_PLUGIN_DIR . '/includes/order_print.php' ) );
			}
		}

		if( !empty( $this->settlement_notice ) &&
			( $this->options['system']['settlement_notice'] == 1 || defined( 'WCEX_DLSELLER' ) || defined( 'WCEX_AUTO_DELIVERY' ) ) ) {
			add_action( 'admin_notices', 'usces_display_settlement_notice' );
		}

		do_action( 'usces_after_main' );
	}

	function stripslashes_deep_post( $array ){
		$res = array();
		foreach( $array as $key => $value ){
			$key = stripslashes($key);
			if( is_array($value) ){
				$value = $this->stripslashes_deep_post( $value );
			}else{
				$value = usces_erase_emoji( stripslashes($value) );
			}
			$res[$key] = $value;
		}
		return $res;
	}

	function make_url(){

		$permalink_structure = get_option('permalink_structure');
		if($this->use_ssl) {
			if( $permalink_structure ){
				$this->delim = '&';
				$home_perse = parse_url(get_option('home'));
				$home_perse_path = isset($home_perse['path']) ? $home_perse['path'] : '';
				$home_path = $home_perse['host'].$home_perse_path;
				$ssl_perse = parse_url($this->options['ssl_url']);
				$ssl_perse_path = isset($ssl_perse['path']) ? $ssl_perse['path'] : '';
				$ssl_path = $ssl_perse['host'].$ssl_perse_path;
				if( $home_perse_path != $ssl_perse_path ){
					if( ! defined('USCES_CUSTOMER_URL') )
						define('USCES_CUSTOMER_URL', $this->options['ssl_url'] . '/index.php?page_id=' . USCES_CART_NUMBER . '&customerinfo=1&uscesid=' . $this->get_uscesid());
					if( ! defined('USCES_CART_URL') )
						define('USCES_CART_URL', $this->options['ssl_url'] . '/index.php?page_id=' . USCES_CART_NUMBER . '&uscesid=' . $this->get_uscesid());
					if( ! defined('USCES_LOSTMEMBERPASSWORD_URL') )
						define('USCES_LOSTMEMBERPASSWORD_URL', $this->options['ssl_url'] . '/index.php?page_id=' . USCES_MEMBER_NUMBER . '&uscesid=' . $this->get_uscesid() . '&usces_page=lostmemberpassword');
					if( ! defined('USCES_NEWMEMBER_URL') )
						define('USCES_NEWMEMBER_URL', $this->options['ssl_url'] . '/index.php?page_id=' . USCES_MEMBER_NUMBER . '&uscesid=' . $this->get_uscesid() . '&usces_page=newmember');
					if( ! defined('USCES_LOGIN_URL') )
						define('USCES_LOGIN_URL', $this->options['ssl_url'] . '/index.php?page_id=' . USCES_MEMBER_NUMBER . '&uscesid=' . $this->get_uscesid() . '&usces_page=login');
					if( ! defined('USCES_LOGOUT_URL') )
						define('USCES_LOGOUT_URL', $this->options['ssl_url'] . '/index.php?page_id=' . USCES_MEMBER_NUMBER . '&uscesid=' . $this->get_uscesid() . '&usces_page=logout');
					if( ! defined('USCES_MEMBER_URL') )
						define('USCES_MEMBER_URL', $this->options['ssl_url'] . '/index.php?page_id=' . USCES_MEMBER_NUMBER . '&uscesid=' . $this->get_uscesid());
					$inquiry_url = empty( $this->options['inquiry_id'] ) ? '' : $this->options['ssl_url'] . '/index.php?page_id=' . $this->options['inquiry_id'] . '&uscesid=' . $this->get_uscesid();
					if( ! defined('USCES_INQUIRY_URL') )
						define('USCES_INQUIRY_URL', $inquiry_url);
					if( ! defined('USCES_CART_NONSESSION_URL') )
						define('USCES_CART_NONSESSION_URL', $this->options['ssl_url'] . '/index.php?page_id=' . USCES_CART_NUMBER);
					if( ! defined('USCES_PAYPAL_NOTIFY_URL') )
						define('USCES_PAYPAL_NOTIFY_URL', $this->options['ssl_url'] . '/index.php?page_id=' . USCES_CART_NUMBER . '&acting=paypal_ipn&uscesid=' . $this->get_uscesid(false));
				}else{
					$ssl_plink_cart = str_replace('http://','https://', str_replace( $home_path, $ssl_path, get_page_link(USCES_CART_NUMBER) ));
					$ssl_plink_member = str_replace('http://','https://', str_replace( $home_path, $ssl_path, get_page_link(USCES_MEMBER_NUMBER) ));
					if( ! defined('USCES_CUSTOMER_URL') )
						define('USCES_CUSTOMER_URL', $ssl_plink_cart . '?uscesid=' . $this->get_uscesid() . '&customerinfo=1');
					if( ! defined('USCES_CART_URL') )
						define('USCES_CART_URL', $ssl_plink_cart . '?uscesid=' . $this->get_uscesid());
					if( ! defined('USCES_LOSTMEMBERPASSWORD_URL') )
						define('USCES_LOSTMEMBERPASSWORD_URL', $ssl_plink_member . '?uscesid=' . $this->get_uscesid() . '&usces_page=lostmemberpassword');
					if( ! defined('USCES_NEWMEMBER_URL') )
						define('USCES_NEWMEMBER_URL', $ssl_plink_member  . '?uscesid=' . $this->get_uscesid(). '&usces_page=newmember');
					if( ! defined('USCES_LOGIN_URL') )
						define('USCES_LOGIN_URL', $ssl_plink_member . '?uscesid=' . $this->get_uscesid() . '&usces_page=login');
					if( ! defined('USCES_LOGOUT_URL') )
						define('USCES_LOGOUT_URL', $ssl_plink_member . '?uscesid=' . $this->get_uscesid() . '&usces_page=logout');
					if( ! defined('USCES_MEMBER_URL') )
						define('USCES_MEMBER_URL', $ssl_plink_member . '?uscesid=' . $this->get_uscesid());
					if( !isset($this->options['inquiry_id']) || !( (int)$this->options['inquiry_id'] ) ){
						$inquiry_url = get_home_url();
					}else{
						$ssl_plink_inquiry = str_replace('http://','https://', str_replace( $home_path, $ssl_path, get_page_link($this->options['inquiry_id']) ));
						$inquiry_url = empty( $this->options['inquiry_id'] ) ? '' : $ssl_plink_inquiry . '?uscesid=' . $this->get_uscesid();
					}
					if( ! defined('USCES_INQUIRY_URL') )
						define('USCES_INQUIRY_URL', $inquiry_url);
					if( ! defined('USCES_CART_NONSESSION_URL') )
						define('USCES_CART_NONSESSION_URL', $ssl_plink_cart);
					if( ! defined('USCES_PAYPAL_NOTIFY_URL') )
						define('USCES_PAYPAL_NOTIFY_URL', $ssl_plink_cart . '?acting=paypal_ipn&uscesid=' . $this->get_uscesid(false));
				}
			}else{
				$this->delim = '&';
				if( ! defined('USCES_CUSTOMER_URL') )
					define('USCES_CUSTOMER_URL', $this->options['ssl_url'] . '/?page_id=' . USCES_CART_NUMBER . '&customerinfo=1&uscesid=' . $this->get_uscesid());
				if( ! defined('USCES_CART_URL') )
					define('USCES_CART_URL', $this->options['ssl_url'] . '/?page_id=' . USCES_CART_NUMBER . '&uscesid=' . $this->get_uscesid());
				if( ! defined('USCES_LOSTMEMBERPASSWORD_URL') )
					define('USCES_LOSTMEMBERPASSWORD_URL', $this->options['ssl_url'] . '/?page_id=' . USCES_MEMBER_NUMBER . '&uscesid=' . $this->get_uscesid() . '&usces_page=lostmemberpassword');
				if( ! defined('USCES_NEWMEMBER_URL') )
					define('USCES_NEWMEMBER_URL', $this->options['ssl_url'] . '/?page_id=' . USCES_MEMBER_NUMBER . '&uscesid=' . $this->get_uscesid() . '&usces_page=newmember');
				if( ! defined('USCES_LOGIN_URL') )
					define('USCES_LOGIN_URL', $this->options['ssl_url'] . '/?page_id=' . USCES_MEMBER_NUMBER . '&uscesid=' . $this->get_uscesid() . '&usces_page=login');
				if( ! defined('USCES_LOGOUT_URL') )
					define('USCES_LOGOUT_URL', $this->options['ssl_url'] . '/?page_id=' . USCES_MEMBER_NUMBER . '&uscesid=' . $this->get_uscesid() . '&usces_page=logout');
				if( ! defined('USCES_MEMBER_URL') )
					define('USCES_MEMBER_URL', $this->options['ssl_url'] . '/?page_id=' . USCES_MEMBER_NUMBER . '&uscesid=' . $this->get_uscesid());
				$inquiry_url = empty( $this->options['inquiry_id'] ) ? '' : $this->options['ssl_url'] . '/?page_id=' . $this->options['inquiry_id'] . '&uscesid=' . $this->get_uscesid();
				if( ! defined('USCES_INQUIRY_URL') )
					define('USCES_INQUIRY_URL', $inquiry_url);
				if( ! defined('USCES_CART_NONSESSION_URL') )
					define('USCES_CART_NONSESSION_URL', $this->options['ssl_url'] . '/?page_id=' . USCES_CART_NUMBER);
				if( ! defined('USCES_PAYPAL_NOTIFY_URL') )
					define('USCES_PAYPAL_NOTIFY_URL', $this->options['ssl_url'] . '/?page_id=' . USCES_CART_NUMBER . '&acting=paypal_ipn&uscesid=' . $this->get_uscesid(false));
			}
			if( !is_admin() ){
				add_filter('home_url', array($this, 'usces_ssl_page_link'));
				add_filter('wp_get_attachment_url', array($this, 'usces_ssl_attachment_link'));
				add_filter('icon_dir_uri', array($this, 'usces_ssl_icon_dir_uri'));
				add_filter('stylesheet_directory_uri', array($this, 'usces_ssl_contents_link'));
				add_filter('template_directory_uri', array($this, 'usces_ssl_contents_link'));
				add_filter('script_loader_src', array($this, 'usces_ssl_script_link'));
				add_filter('style_loader_src', array($this, 'usces_ssl_script_link'));
			}
		} else {
			if( $permalink_structure ){
				$this->delim = '?';
				if( ! defined('USCES_CUSTOMER_URL') )
					define('USCES_CUSTOMER_URL', get_page_link(USCES_CART_NUMBER) . '?customerinfo=1');
				if( ! defined('USCES_CART_URL') )
					define('USCES_CART_URL', get_page_link(USCES_CART_NUMBER));
				if( ! defined('USCES_LOSTMEMBERPASSWORD_URL') )
					define('USCES_LOSTMEMBERPASSWORD_URL', get_page_link(USCES_MEMBER_NUMBER) . '?usces_page=lostmemberpassword');
				if( ! defined('USCES_NEWMEMBER_URL') )
					define('USCES_NEWMEMBER_URL', get_page_link(USCES_MEMBER_NUMBER) . '?usces_page=newmember');
				if( ! defined('USCES_LOGIN_URL') )
					define('USCES_LOGIN_URL', get_page_link(USCES_MEMBER_NUMBER) . '?usces_page=login');
				if( ! defined('USCES_LOGOUT_URL') )
					define('USCES_LOGOUT_URL', get_page_link(USCES_MEMBER_NUMBER) . '?usces_page=logout');
				if( ! defined('USCES_MEMBER_URL') )
					define('USCES_MEMBER_URL', get_page_link(USCES_MEMBER_NUMBER));
				$inquiry_url = ( !isset( $this->options['inquiry_id'] ) || !( (int)$this->options['inquiry_id'] )) ? get_home_url() : get_page_link($this->options['inquiry_id']);
				if( ! defined('USCES_INQUIRY_URL') )
					define('USCES_INQUIRY_URL', $inquiry_url);
				if( ! defined('USCES_CART_NONSESSION_URL') )
					define('USCES_CART_NONSESSION_URL', get_page_link(USCES_CART_NUMBER));
				if( ! defined('USCES_PAYPAL_NOTIFY_URL') )
					define('USCES_PAYPAL_NOTIFY_URL', get_page_link(USCES_CART_NUMBER) . '?acting=paypal_ipn&uscesid=' . $this->get_uscesid(false));
			}else{
				$this->delim = '&';
				if( ! defined('USCES_CUSTOMER_URL') )
					define('USCES_CUSTOMER_URL', get_option('home') . '/?page_id=' . USCES_CART_NUMBER . '&customerinfo=1');
				if( ! defined('USCES_CART_URL') )
					define('USCES_CART_URL', get_option('home') . '/?page_id=' . USCES_CART_NUMBER);
				if( ! defined('USCES_LOSTMEMBERPASSWORD_URL') )
					define('USCES_LOSTMEMBERPASSWORD_URL', get_option('home') . '/?page_id=' . USCES_MEMBER_NUMBER . '&usces_page=lostmemberpassword');
				if( ! defined('USCES_NEWMEMBER_URL') )
					define('USCES_NEWMEMBER_URL', get_option('home') . '/?page_id=' . USCES_MEMBER_NUMBER . '&usces_page=newmember');
				if( ! defined('USCES_LOGIN_URL') )
					define('USCES_LOGIN_URL', get_option('home') . '/?page_id=' . USCES_MEMBER_NUMBER . '&usces_page=login');
				if( ! defined('USCES_LOGOUT_URL') )
					define('USCES_LOGOUT_URL', get_option('home') . '/?page_id=' . USCES_MEMBER_NUMBER . '&usces_page=logout');
				if( ! defined('USCES_CART_NONSESSION_URL') )
					define('USCES_CART_NONSESSION_URL', get_option('home') . '/?page_id=' . USCES_MEMBER_NUMBER . '&usces_page=logout');
				if( ! defined('USCES_MEMBER_URL') )
					define('USCES_MEMBER_URL', get_option('home') . '/?page_id=' . USCES_MEMBER_NUMBER);
				$inquiry_url = empty( $this->options['inquiry_id'] ) ? '' : get_option('home') . '/?page_id=' . $this->options['inquiry_id'];
				if( ! defined('USCES_INQUIRY_URL') )
					define('USCES_INQUIRY_URL', $inquiry_url);
				if( ! defined('USCES_CART_NONSESSION_URL') )
					define('USCES_CART_NONSESSION_URL', get_option('home') . '/?page_id=' . USCES_CART_NUMBER);
				if( ! defined('USCES_PAYPAL_NOTIFY_URL') )
					define('USCES_PAYPAL_NOTIFY_URL', get_option('home') . '/?page_id=' . USCES_CART_NUMBER . '&acting=paypal_ipn&uscesid=' . $this->get_uscesid(false));
			}
		}
	}

	function regist_action(){
		usces_register_action('inCart', 'post', 'inCart', NULL, 'inCart');
		usces_register_action('upButton', 'post', 'upButton', NULL, 'upButton');
		usces_register_action('delButton', 'post', 'delButton', NULL, 'delButton');
		usces_register_action('backCart', 'post', 'backCart', NULL, 'backCart');
		usces_register_action('customerinfo', 'request', 'customerinfo', NULL, 'customerinfo');
		usces_register_action('backCustomer', 'post', 'backCustomer', NULL, 'backCustomer');
		usces_register_action('customerlogin', 'post', 'customerlogin', NULL, 'customerlogin');
		usces_register_action('reganddeliveryinfo', 'post', 'reganddeliveryinfo', NULL, 'reganddeliveryinfo');
		usces_register_action('deliveryinfo', 'post', 'deliveryinfo', NULL, 'deliveryinfo');
		usces_register_action('backDelivery', 'request', 'backDelivery', NULL, 'backDelivery');
		usces_register_action('confirm', 'request', 'confirm', NULL, 'confirm');
		usces_register_action('use_point', 'post', 'use_point', NULL, 'use_point');
		usces_register_action('backConfirm', 'post', 'backConfirm', NULL, 'backConfirm');
		usces_register_action('purchase', 'request', 'purchase', NULL, 'purchase');
		usces_register_action('acting_return', 'request', 'acting_return', NULL, 'acting_return');
		usces_register_action('settlement_epsilon', 'request', 'settlement', 'epsilon', 'settlement_epsilon');
		usces_register_action('inquiry_button', 'post', 'inquiry_button', NULL, 'inquiry_button');
		usces_register_action('member_login', 'request', 'member_login', NULL, 'member_login_page');
		usces_register_action('regmember', 'request', 'regmember', NULL, 'regmember');
		usces_register_action('editmember', 'request', 'editmember', NULL, 'editmember');
		usces_register_action('deletemember', 'request', 'deletemember', NULL, 'deletemember');
		usces_register_action('page_login', 'get', 'usces_page', 'login', 'member_login_page');
		usces_register_action('page_logout', 'get', 'usces_page', 'logout', 'page_logout');
		usces_register_action('page_lostmemberpassword', 'get', 'usces_page', 'lostmemberpassword', 'page_lostmemberpassword');
		usces_register_action('lostpassword', 'request', 'lostpassword', NULL, 'lostpassword');
		usces_register_action('uscesmode_changepassword', 'request', 'uscesmode', 'changepassword', 'uscesmode_changepassword');
		usces_register_action('changepassword', 'request', 'changepassword', NULL, 'changepassword_page');
		usces_register_action('page_newmember', 'get', 'usces_page', 'newmember', 'page_newmember');
		usces_register_action('usces_export', 'post', 'usces_export', NULL, 'usces_export');
		usces_register_action('usces_import', 'post', 'usces_import', NULL, 'usces_import');
		usces_register_action('page_search_item', 'get', 'usces_page', 'search_item', 'page_search_item');
		usces_register_action('front_ajax', 'post', 'usces_ajax_action', NULL, 'front_ajax');
	}

	function ad_controller(){
		global $usces_action;
		ksort($usces_action);
		if($this->is_maintenance() and !is_user_logged_in()){
			$this->maintenance();
		}else{
			$action_array = array('inCart', 'upButton', 'delButton', 'backCart', 'customerinfo', 'backCustomer',
			'customerlogin', 'reganddeliveryinfo', 'deliveryinfo', 'backDelivery', 'confirm', 'use_point',
			'backConfirm', 'purchase', 'acting_return', 'settlement_epsilon', 'inquiry_button', 'member_login',
			'regmember', 'editmember', 'deletemember', 'page_login', 'page_logout', 'page_lostmemberpassword', 'lostpassword',
			'uscesmode_changepassword', 'changepassword', 'page_newmember', 'usces_export', 'usces_import',
			'page_search_item', 'front_ajax');

			$action_array = apply_filters('usces_filter_action_array', $action_array);

			$flg = 0;
			$res = true;
			foreach( $usces_action as $handle => $action ){
				extract($action);
				switch($type){
					case 'post':
						if( empty($value) ){
							if( isset($_POST[$key]) ){
								if(in_array($handle, $action_array)){
									$res = call_user_func(array($this, $function));
								}else{
									$res = call_user_func($function);
								}
								$flg = 1;
							}
						}else{
							if( isset($_POST[$key]) && $_POST[$key] == $value ){
								if(in_array($handle, $action_array)){
									$res = call_user_func(array($this, $function));
								}else{
									$res = call_user_func($function);
								}
								$flg = 1;
							}
						}
						break;
					case 'get':
						if( empty($value) ){
							if( isset($_GET[$key]) ){
								if(in_array($handle, $action_array)){
									$res = call_user_func(array($this, $function));
								}else{
									$res = call_user_func($function);
								}
								$flg = 1;
							}
						}else{
							if( isset($_GET[$key]) && $_GET[$key] == $value ){
								if(in_array($handle, $action_array)){
									$res = call_user_func(array($this, $function));
								}else{
									$res = call_user_func($function);
								}
								$flg = 1;
							}
						}
						break;
					case 'request':
						if( empty($value) ){
							if( isset($_REQUEST[$key]) ){
								if(in_array($handle, $action_array)){
									$res = call_user_func(array($this, $function));
								}else{
									$res = call_user_func($function);
								}
								$flg = 1;
							}
						}else{
							if( isset($_REQUEST[$key]) && $_REQUEST[$key] == $value ){
								if(in_array($handle, $action_array)){
									$res = call_user_func(array($this, $function));
								}else{
									$res = call_user_func($function);
								}
								$flg = 1;
							}
						}
						break;
				}
				if( ! $res ) break;
			}
			if( !$flg ) $this->default_page();
		}
	}

	//action function------------------------------------------------------------
	function front_ajax(){
		switch ($_POST['usces_ajax_action']){
			case 'change_states':
				change_states_ajax();
				break;
		}
		do_action('usces_front_ajax');
	}

	function maintenance(){
		$this->page = 'maintenance';
		add_action('the_post', array($this, 'action_cartFilter'));
	}

	function inCart(){
		global $wp_query;
		$this->page = 'cart';
		$this->incart_check();
		$this->cart->inCart();
		add_action('the_post', array($this, 'action_cartFilter'));
		add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_cart');
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function upButton(){
		global $wp_query;
		$this->page = 'cart';
		$this->cart->upCart();
		$this->error_message = $this->zaiko_check();
		add_action('the_post', array($this, 'action_cartFilter'));
		add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_cart');
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function delButton(){
		global $wp_query;
		$this->page = 'cart';
		$this->cart->del_row();
		add_action('the_post', array($this, 'action_cartFilter'));
		add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_cart');
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function backCart(){
		global $wp_query;
		$this->page = 'cart';
		add_action('the_post', array($this, 'action_cartFilter'));
		add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_cart');
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function customerinfo(){
		global $wp_query;
		if( false === $this->cart->num_row() ){
			header('location: ' . get_option('home'));
			exit;
		}
		do_action( 'usces_action_customerinfo' );
		$this->cart->entry();
		$this->error_message = $this->zaiko_check();
		$this->error_message = apply_filters( 'usces_filter_cart_check', $this->error_message );
		if( WCUtils::is_blank($this->error_message) ){
			if($this->is_member_logged_in()){
				$this->error_message = has_custom_customer_field_essential();
				$this->page = ( WCUtils::is_blank($this->error_message) ) ? 'delivery' : 'customer';
				add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_delivery');
			}else{
				$this->page = 'customer';
				add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_customer');
			}
		}else{
			$this->page = 'cart';
			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_cart');
		}
		if ( !$this->cart->is_order_condition() ) {
			$order_conditions = $this->get_condition();
			$this->cart->set_order_condition($order_conditions);
		}
		add_action('the_post', array($this, 'action_cartFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function backCustomer(){
		global $wp_query;
		if( false === $this->cart->num_row() ){
			header('location: ' . get_option('home'));
			exit;
		}
		$this->page = apply_filters( 'usces_filter_backCustomer_page', 'customer' );
		add_action('the_post', array($this, 'action_cartFilter'));
		add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_customer');
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function customerlogin(){
		global $wp_query;
		if( false === $this->cart->num_row() ){
			header('location: ' . get_option('home'));
			exit;
		}
		if($this->member_login() == 'member') {
			$this->cart->entry();
			// $this->error_message = has_custom_customer_field_essential();
			if( WCUtils::is_blank($this->error_message) ){
				$this->page = 'delivery';
				add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_delivery');
			}else{
				$this->page = 'customer';
				add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_customer');
			}
		} else {
			$this->cart->entry();
			$this->page = 'customer';
			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_customer');
		}
		add_action('the_post', array($this, 'action_cartFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function reganddeliveryinfo(){
		global $wp_query;
		if( false === $this->cart->num_row() ){
			header('location: ' . get_option('home'));
			exit;
		}

		$nonce = isset( $_REQUEST['wc_nonce'] ) ? $_REQUEST['wc_nonce'] : '';
		$noncekey = 'post_member' . $this->get_uscesid(false);
		if( !wp_verify_nonce( $nonce, $noncekey ) && !$this->is_member_logged_in() )
			die('Security check2');

		$check_verify_recaptcha = $this->verifyGoogleRecapcha();
		if ( $check_verify_recaptcha ) {
			$this->cart->entry();
			if ( empty( $_POST['member_regmode'] ) or $_POST['member_regmode'] != 'editmemberfromcart' ) {
				$_POST['member_regmode'] = 'newmemberfromcart';
			}
			$res = $this->regist_member();
		} else {
			// return back page register and show message.
			$this->error_message = __( 'Failed to register member. Please try again.', 'usces' );
			$res = trim( $_POST['member_regmode'] );
		}

		if( $res == 'newcompletion' ){
			$this->page = 'delivery';
			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_delivery');
		}elseif( $res == 'cartverifying' ){
			$this->page = 'cartverifying';
		}else{
			$this->page = 'customer';
			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_customer');
		}
		add_action('the_post', array($this, 'action_cartFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function deliveryinfo(){
		global $wp_query;
		if( false === $this->cart->num_row() ){
			header('location: ' . get_option('home'));
			exit;
		}
		$check_verify_recaptcha = $this->verifyGoogleRecapcha();
		if ( $check_verify_recaptcha ) {
			// continue process.
			$this->cart->entry();
			$this->error_message = $this->customer_check();
		} else {
			// return back page register and show message.
			$this->error_message = __( 'Could not send successfully. Please try again.', 'usces' );
		}

		if( WCUtils::is_blank($this->error_message) ){
			$this->page = 'delivery';
			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_delivery');
		}else{
			$this->page = 'customer';
			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_customer');
		}
		add_action('the_post', array($this, 'action_cartFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function backDelivery(){
		global $wp_query;
		if( false === $this->cart->num_row() ){
			header('location: ' . get_option('home'));
			exit;
		}
		$this->page = 'delivery';
		add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_delivery');
		add_action('the_post', array($this, 'action_cartFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function confirm(){
		global $wpdb, $wp_query;
		if( false === $this->cart->num_row() ){
			header('location: ' . get_option('home'));
			exit;
		}

		$this->cart->entry();
		$this->error_message = $this->zaiko_check();
		if( $this->error_message != '' ){
			$this->page = 'cart';
			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_cart');
			add_action('the_post', array($this, 'action_cartFilter'));
			add_action('template_redirect', array($this, 'template_redirect'));
			return;
		}

		$this->set_reserve_pre_order_id();
		if(isset($_POST['confirm'])){
			$this->error_message = $this->delivery_check();
		}
		$this->page = ( WCUtils::is_blank($this->error_message) ) ? 'confirm' : 'delivery';
		if( WCUtils::is_blank($this->error_message) ){
			if( usces_is_member_system() && usces_is_member_system_point() && $this->is_member_logged_in() ) {
				$member_table = usces_get_tablename( 'usces_member' );
				$query = $wpdb->prepare("SELECT mem_point FROM $member_table WHERE ID = %d", $_SESSION['usces_member']['ID']);
				$mem_point = $wpdb->get_var( $query );
				$_SESSION['usces_member']['point'] = $mem_point;
			}
			$this->page = 'confirm';
			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_confirm');
		}else{
			$this->page = apply_filters( 'usces_filter_delivery_check_error_page', 'delivery', $this->error_message );
			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_delivery');
		}
		add_action('the_post', array($this, 'action_cartFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function use_point(){
		global $wp_query, $usces;
		$noncekey = 'use_point' . $usces->get_uscesid(false);

		if( !isset($_REQUEST['wc_nonce']) || !wp_verify_nonce($_REQUEST['wc_nonce'], $noncekey) )
			die('Security check1');

		$this->error_message = $this->point_check( $this->cart->get_entry() );
		if( empty($this->error_message) )
			$this->cart->entry();
		$this->page = 'confirm';
		add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_confirm');
		add_action('the_post', array($this, 'action_cartFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function backConfirm(){
		global $wp_query;
		if( false === $this->cart->num_row() ){
			header('location: ' . get_option('home'));
			exit;
		}
		$this->page = 'confirm';
		add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_confirm');
		add_action('the_post', array($this, 'action_cartFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function purchase(){
		global $wp_query;
		do_action( 'usces_pre_purchase' );
		if( false === $this->cart->num_row() ){
			header('location: ' . get_option('home'));
			exit;
		}

		if( !apply_filters('usces_purchase_check', true) ) return;

		do_action('usces_purchase_validate');
		$entry = $this->cart->get_entry();
		$this->error_message = $this->zaiko_check();
		if( WCUtils::is_blank($this->error_message) && 0 < $this->cart->num_row()){
			$acting_status = '';
			$payments = $this->getPayments( $entry['order']['payment_name'] );
			if( substr($payments['settlement'], 0, 6) == 'acting' && $entry['order']['total_full_price'] > 0 ){
				$acting_flg = ( 'acting' == $payments['settlement'] ) ? $payments['module'] : $payments['settlement'];
				unset( $_POST['purchase'] );
				$post_query = '&'.http_build_query( $_POST );
				$acting_status = $this->acting_processing( $acting_flg, $post_query, $acting_status );
			}

			if($acting_status == 'error'){
				$this->page = 'error';
				add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_error');
			}else{
				$res = $this->order_processing();
				if( 'ordercompletion' == $res ){
					$this->page = 'ordercompletion';
					add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_ordercompletion');
				}else{
					$this->page = 'error';
					add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_error');
				}
			}
		}else{
			$this->page = 'cart';
			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_cart');
			if ( ! $this->is_cart_page( $_SERVER['REQUEST_URI'] ) ) {
				wp_redirect( USCES_CART_URL );
				exit;
			}
		}
		add_action('the_post', array($this, 'action_cartFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function acting_return(){
		global $wp_query;

		do_action( 'usces_pre_acting_return' );

		$entry = $this->cart->get_entry();
		if( isset($_GET['acting']) and 'anotherlane_card' != $_GET['acting'] ) {
			if( false === $this->cart->num_row() && ('paypal' != $_GET['acting'] && 1 !== (int)$_GET['acting_return']) ){
				header('location: ' . get_option('home'));
				exit;
			}
		}

		$this->payment_results = usces_check_acting_return();

		if(  isset($this->payment_results[0]) && $this->payment_results[0] === 'duplicate' ){

			header('location: ' . get_option('home'));
			exit;

		}else if( isset($this->payment_results[0]) && $this->payment_results[0] ){//result OK

			if( ! $this->payment_results['reg_order'] ){//without Registration Order
				$this->page = 'ordercompletion';
				add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_ordercompletion');

			}else{
				$res = $this->order_processing( $this->payment_results );

				if( 'ordercompletion' == $res ){
					$this->page = 'ordercompletion';
					add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_ordercompletion');
				}else{
					$this->page = 'error';
					add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_error');
				}
			}

		}else{//result NG
			$this->page = 'error';
			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_error');
		}

		add_action('the_post', array($this, 'action_cartFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function settlement_epsilon(){
		global $wp_query;
		require_once($this->options['settlement_path'] . 'epsilon.php');
	}

	function inquiry_button(){
		if( (isset($_POST['kakuninyou']) && empty($_POST['kakuninyou'])) && isset($_POST['inq_name']) && !WCUtils::is_blank($_POST['inq_name']) && isset($_POST['inq_mailaddress']) && is_email( trim($_POST['inq_mailaddress']) ) && !WCUtils::is_blank($_POST['inq_contents']) ){
			$res = $this->inquiry_processing();
		}else{
			$res = 'deficiency';
		}

		$this->page = $res;
	}

	function member_login_page(){
		global $wp_query;
        if(!class_exists('RateLimiter')){
            require plugin_dir_path(__FILE__).'rateLimiter.class.php';
        }
        $rateLimiter = new RateLimiter();
        if($rateLimiter->checkBlockIP()){
			$wp_query->set_403();
			status_header( 403 );
			exit();
        }

		if(isset($_GET['redirect_to'])){
			$_SESSION['redirect_to'] = $_GET['redirect_to'];
		}

		$res = $this->member_login();
		if( 'member' == $res ){
			$this->page = 'member';
			do_action('usces_action_member_logined');

			if(isset($_SESSION['redirect_to'])){
				if (wp_safe_redirect(esc_url($_SESSION['redirect_to']))) {
					unset($_SESSION['redirect_to']);
					exit;
				}
			}

			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_member');
		}elseif( 'login' == $res ){
			$this->page = 'login';
			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_login');
		}
		add_action('the_post', array($this, 'action_memberFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function regmember(){
		$nonce = isset( $_REQUEST['wc_nonce'] ) ? $_REQUEST['wc_nonce'] : '';
		$noncekey = 'post_member' . $this->get_uscesid(false);
		if( !wp_verify_nonce( $nonce, $noncekey ) )
			die('Security check2');

		$check_verify_recaptcha = $this->verifyGoogleRecapcha();
		if ( $check_verify_recaptcha ) {
			// continue register form.
			global $wp_query;
			$res = $this->regist_member();
		} else {
			// return back page register and show message.
			$this->error_message = __( 'Failed to register member. Please try again.', 'usces' );
			$res = trim( $_POST['member_regmode'] );
		}

		if( 'editmemberform' == $res ){
			$this->page = 'editmemberform';
			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_editmemberform');
		}elseif( 'newcompletion' == $res ){
			$this->page = 'newcompletion';
			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_newcompletion');
		}else{
			$this->page = $res;
		}
		add_action('the_post', array($this, 'action_memberFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function editmember(){
		$nonce = isset( $_REQUEST['wc_nonce'] ) ? $_REQUEST['wc_nonce'] : '';
		$noncekey = 'post_member' . $this->get_uscesid(false);
		if( !wp_verify_nonce( $nonce, $noncekey ) )
			die('Security check3');

		global $wp_query;
		$res = $this->regist_member();
		if( 'editmemberform' == $res ){
			$this->page = 'editmemberform';
			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_editmemberform');
		}elseif( 'newcompletion' == $res ){
			$this->page = 'newcompletion';
			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_newcompletion');
		}else{
			$this->page = $res;
		}
		add_action('the_post', array($this, 'action_memberFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function deletemember(){
		$nonce = isset( $_REQUEST['wc_nonce'] ) ? $_REQUEST['wc_nonce'] : '';
		$noncekey = 'post_member' . $this->get_uscesid(false);
		if( !wp_verify_nonce( $nonce, $noncekey ) )
			die('Security check4');

		$res = $this->delete_member();
		if( $res ){
			add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_deletemember');
			$this->member_logout();
		}else{
			$this->page = 'editmemberform';
			add_action('the_post', array($this, 'action_memberFilter'));
		}
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function page_logout(){
		global $wp_query;
		$this->member_logout();
		add_action('the_post', array($this, 'action_memberFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function page_lostmemberpassword(){
		global $wp_query;
		$this->page = 'lostmemberpassword';
		add_action('the_post', array($this, 'action_memberFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function lostpassword(){
		global $usces, $wp_query;

		$nonce = isset( $_REQUEST['wc_nonce'] ) ? $_REQUEST['wc_nonce'] : '';
		$noncekey = 'post_member' . $usces->get_uscesid(false);
		if ( ! wp_verify_nonce( $nonce, $noncekey ) ) {
			$wp_query->set_403();
			status_header( 403 );
			exit();
		}

		$this->error_message = $this->lostpass_mailaddcheck();
		if ( $this->error_message != '' ) {
			$this->page = 'lostmemberpassword';
		} else {
			$res = $this->lostmail();
			$this->page = $res;//'lostcompletion';
		}
		add_action('the_post', array($this, 'action_memberFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function uscesmode_changepassword(){
		global $wp_query;

		if( !isset( $_REQUEST['mem']) || !isset( $_REQUEST['key']) )
			die('Invalid request 1');

		$mem_mail = $_REQUEST['mem'];
		$lostkey = $_REQUEST['key'];
		$res = usces_check_lostkey($mem_mail, $lostkey);
		if( empty($res) )
			die('Invalid request 2');

		$this->page = 'changepassword';
		add_action('the_post', array($this, 'action_memberFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function changepassword_page(){
		global $usces;

		$nonce = isset( $_REQUEST['wc_nonce'] ) ? $_REQUEST['wc_nonce'] : '';
		$noncekey = 'post_member' . $usces->get_uscesid(false);
		if( !wp_verify_nonce( $nonce, $noncekey ) )
			die('Security check6');

		$lostmail = $_POST['mem'];
		$lostkey = $_POST['key'];
		$res = usces_check_lostkey($lostmail, $lostkey);
		if( empty($res) )
			die('Invalid request 7');

		global $wp_query;
		$this->error_message = $this->changepass_check();
		if ( $this->error_message != '' ) {
			$this->page = 'changepassword';
		} else {
			$res = $this->changepassword();
			$this->page = $res;//'changepasscompletion';
		}
		add_action('the_post', array($this, 'action_memberFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function page_newmember(){

		global $wp_query;
		$this->page = 'newmemberform';
		add_filter('yoast-ga-push-after-pageview', 'usces_trackPageview_newmemberform');
		add_action('the_post', array($this, 'action_memberFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function usces_export(){
		$this->export();
	}

	function usces_import(){
		$this->import();
	}

	function page_search_item(){
		global $wp_query;
		$this->page = 'search_item';
		add_action('the_post', array($this, 'action_cartFilter'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function default_page(){
		global $wp_query;
		add_action('the_post', array($this, 'goDefaultPage'));
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function verifyGoogleRecapcha(){
		//check google re-captcha v3
		$option = get_option('usces_ex');
		if ( isset( $option['system']['google_recaptcha']['status'] ) && $option['system']['google_recaptcha']['status'] && ! ( empty( $option['system']['google_recaptcha']['site_key'] ) ) && ! ( empty( $option['system']['google_recaptcha']['secret_key'] ) ) ) {
			$is_human = false;
			if ( isset( $_POST['recaptcha_response'] ) ) {
				$secret   = isset( $option['system']['google_recaptcha']['secret_key'] ) ? $option['system']['google_recaptcha']['secret_key'] : '';
				$token    = trim( $_POST['recaptcha_response'] );
				$is_human = $this->google_recaptcha_v3_response( $token, $secret );
			}
			return $is_human;
		}

		return true;
	}
	
	/**
	 * Handle check google recaptcha v3 response.
	 *
	 * @param string $token string token.
	 * @param string $secret string secret key config.
	 *
	 * @return boolean $is_human.
	 */
	function google_recaptcha_v3_response( $token, $secret ) {
		$is_human = false;
		if ( empty( $token ) || empty( $secret ) ) {
			return $is_human;
		}

		$endpoint = 'https://www.google.com/recaptcha/api/siteverify';
		$request  = array(
			'body' => array(
				'secret'   => $secret,
				'response' => $token,
			),
		);

		$response = wp_remote_post( esc_url_raw( $endpoint ), $request );

		if ( 200 != wp_remote_retrieve_response_code( $response ) ) {
			return $is_human;
		}

		$response_body = wp_remote_retrieve_body( $response );
		$response_body = json_decode( $response_body, true );

		$action = isset( $response_body['action'] ) ? $response_body['action'] : '';
		if ( ! $response_body['success'] || ! in_array( $action, array( 'create_new_member', 'customer_order_page', 'member_register_settlement', 'member_update_settlement' ) ) ) {
			return $is_human;
		}
		$score     = isset( $response_body['score'] ) ? $response_body['score'] : 0;
		$threshold = 0.50; // default usually 0.50.
		$is_human  = $threshold <= $score;

		return $is_human;
	}

	//--------------------------------------------------------------------------------------


	function goDefaultPage(){
		global $post;

		if( $post->ID == USCES_CART_NUMBER ) {

			$this->page = 'cart';
			add_filter('the_content', array($this, 'filter_cartContent'),20);

		}else if( $post->ID == USCES_MEMBER_NUMBER ) {

			$this->page = 'member';

			if( $this->is_member_logged_in() ) {
				$this->get_current_member();
				$this->set_member_session_data($this->current_member['id']);
				$this->page = 'member';
			}else{
				$this->page = 'login';
			}
			add_filter('the_content', array($this, 'filter_memberContent'),20);
			add_filter('the_title', array($this, 'filter_memberTitle'),20);

		}else if( !is_singular() ) {
			$this->page = 'wp_search';
			add_filter('the_excerpt', array($this, 'filter_cartContent'),20);
			add_filter('the_content', array($this, 'filter_cartContent'),20);

		}else{
			add_filter('the_content', array(&$this, 'filter_itemPage'));

		}
	}

	function template_redirect () {
		global $wpdb, $wp_version, $post, $usces_entries, $usces_carts, $usces_members, $usces_gp, $member_regmode;

		if( version_compare($wp_version, '4.4-beta', '>') && is_embed() )
			return;

		if ( $this->is_cart_page( $_SERVER['REQUEST_URI'] ) ) {
			if ( '' == $this->page && empty( $this->error_message ) ) {
				$this->error_message = $this->zaiko_check();
				if ( $this->error_message ) {
					$this->page = 'cart';
				}
			}
		}

		if( apply_filters('usces_action_template_redirect', false) ) return;//Deprecated
		if( apply_filters('usces_filter_template_redirect', false) ) return;

		$parent_path = get_template_directory() . '/wc_templates';
		$child_path = get_stylesheet_directory() . '/wc_templates';

		if ( is_single() && is_object( $post ) && 'item' === $post->post_mime_type ) {

			if( file_exists($child_path . '/wc_item_single.php') ){
				if( !post_password_required($post) ){
					include($child_path . '/wc_item_single.php');
					exit;
				}
			}elseif( file_exists($parent_path . '/wc_item_single.php') && !defined('USCES_PARENT_LOAD') ){
				if( !post_password_required($post) ){
					include($parent_path . '/wc_item_single.php');
					exit;
				}
			}

		}elseif( isset($_REQUEST['usces_page']) && ('search_item' == $_REQUEST['usces_page'] || 'usces_search' == $_REQUEST['usces_page']) && $this->is_cart_page($_SERVER['REQUEST_URI']) ){

			if( file_exists($child_path . '/wc_search_page.php') ){
				include($child_path . '/wc_search_page.php');
				exit;
			}elseif( file_exists($parent_path . '/wc_search_page.php') && !defined('USCES_PARENT_LOAD') ){
				include($parent_path . '/wc_search_page.php');
				exit;
			}

		}else if( $this->is_cart_page($_SERVER['REQUEST_URI']) ){

			switch( $this->page ){

				case 'customer':
					if( file_exists($child_path . '/cart/wc_customer_page.php') ){
						usces_get_entries();
						usces_get_member_regmode();
						include($child_path . '/cart/wc_customer_page.php');
						exit;
					}elseif( file_exists($parent_path . '/cart/wc_customer_page.php') && !defined('USCES_PARENT_LOAD') ){
						usces_get_entries();
						usces_get_member_regmode();
						include($parent_path . '/cart/wc_customer_page.php');
						exit;
					}
					break;

				case 'delivery':
					if( file_exists($child_path . '/cart/wc_delivery_page.php') ){
						usces_get_entries();
						usces_get_carts();
						include($child_path . '/cart/wc_delivery_page.php');
						exit;
					}elseif( file_exists($parent_path . '/cart/wc_delivery_page.php') && !defined('USCES_PARENT_LOAD') ){
						usces_get_entries();
						usces_get_carts();
						include($parent_path . '/cart/wc_delivery_page.php');
						exit;
					}
					break;

				case 'confirm':
					if( file_exists($child_path . '/cart/wc_confirm_page.php') ){
						usces_get_entries();
						usces_get_carts();
						usces_get_members();
						include($child_path . '/cart/wc_confirm_page.php');
						exit;
					}elseif( file_exists($parent_path . '/cart/wc_confirm_page.php') && !defined('USCES_PARENT_LOAD') ){
						usces_get_entries();
						usces_get_carts();
						usces_get_members();
						include($parent_path . '/cart/wc_confirm_page.php');
						exit;
					}
					break;

				case 'ordercompletion':
					if( file_exists($child_path . '/cart/wc_completion_page.php') ){
						usces_get_entries();
						usces_get_carts();
						include($child_path . '/cart/wc_completion_page.php');
						exit;
					}elseif( file_exists($parent_path . '/cart/wc_completion_page.php') && !defined('USCES_PARENT_LOAD') ){
						usces_get_entries();
						usces_get_carts();
						include($parent_path . '/cart/wc_completion_page.php');
						exit;
					}
					break;

				case 'error':
					if( file_exists($child_path . '/cart/wc_cart_error_page.php') ){
						include($child_path . '/cart/wc_cart_error_page.php');
						exit;
					}elseif( file_exists($parent_path . '/cart/wc_cart_error_page.php') && !defined('USCES_PARENT_LOAD') ){
						include($parent_path . '/cart/wc_cart_error_page.php');
						exit;
					}
					break;

				case 'cart':
				default:
					$this->page = 'cart';
					if( file_exists($child_path . '/cart/wc_cart_page.php') ){
						include($child_path . '/cart/wc_cart_page.php');
						exit;
					}elseif( file_exists($parent_path . '/cart/wc_cart_page.php') && !defined('USCES_PARENT_LOAD') ){
						include($parent_path . '/cart/wc_cart_page.php');
						exit;
					}

			}
		}else if($this->is_inquiry_page($_SERVER['REQUEST_URI']) ){

		}else if( $this->is_member_page($_SERVER['REQUEST_URI']) ){
			if($this->options['membersystem_state'] != 'activate')
				return;

			if( $this->is_member_logged_in() ) {

				if( isset($_SESSION['usces_member']) ){
					$member_table_name = usces_get_tablename( 'usces_member' );
					$this->get_current_member();
					$query = $wpdb->prepare("SELECT mem_point FROM $member_table_name WHERE ID = %d", $this->current_member['id']);
					$point = $wpdb->get_var( $query );
					$_SESSION['usces_member']['point'] = $point;
				}

				$member_regmode = 'editmemberform';
				if( file_exists($child_path . '/member/wc_member_page.php') ){
					include($child_path . '/member/wc_member_page.php');
					exit;
				}elseif( file_exists($parent_path . '/member/wc_member_page.php') && !defined('USCES_PARENT_LOAD') ){
					include($parent_path . '/member/wc_member_page.php');
					exit;
				}

			} else {

				switch( $this->page ){

					case 'login':
						if( file_exists($child_path . '/member/wc_login_page.php') ){
							include($child_path . '/member/wc_login_page.php');
							exit;
						}elseif( file_exists($parent_path . '/member/wc_login_page.php') && !defined('USCES_PARENT_LOAD') ){
							include($parent_path . '/member/wc_login_page.php');
							exit;
						}
						break;

					case 'newmemberform':
						if( file_exists($child_path . '/member/wc_new_member_page.php') ){
							$member_regmode = 'newmemberform';
							include($child_path . '/member/wc_new_member_page.php');
							exit;
						}elseif( file_exists($parent_path . '/member/wc_new_member_page.php') && !defined('USCES_PARENT_LOAD') ){
							$member_regmode = 'newmemberform';
							include($parent_path . '/member/wc_new_member_page.php');
							exit;
						}
						break;

					case 'lostmemberpassword':
						if( file_exists($child_path . '/member/wc_lostpassword_page.php') ){
							include($child_path . '/member/wc_lostpassword_page.php');
							exit;
						}elseif( file_exists($parent_path . '/member/wc_lostpassword_page.php') && !defined('USCES_PARENT_LOAD') ){
							include($parent_path . '/member/wc_lostpassword_page.php');
							exit;
						}
						break;

					case 'changepassword':
						if( file_exists($child_path . '/member/wc_changepassword_page.php') ){
							include($child_path . '/member/wc_changepassword_page.php');
							exit;
						}elseif( file_exists($parent_path . '/member/wc_changepassword_page.php') && !defined('USCES_PARENT_LOAD') ){
							include($parent_path . '/member/wc_changepassword_page.php');
							exit;
						}
						break;

					case 'newcompletion':
					case 'editcompletion':
					case 'lostcompletion':
					case 'changepasscompletion':
						if( file_exists($child_path . '/member/wc_member_completion_page.php') ){
							include($child_path . '/member/wc_member_completion_page.php');
							exit;
						}elseif( file_exists($parent_path . '/member/wc_member_completion_page.php') && !defined('USCES_PARENT_LOAD') ){
							include($parent_path . '/member/wc_member_completion_page.php');
							exit;
						}
						break;

					default:
						$this->page = 'login';
						if( file_exists($child_path . '/member/wc_login_page.php') ){
							include($child_path . '/member/wc_login_page.php');
							exit;
						}elseif( file_exists($parent_path . '/member/wc_login_page.php') && !defined('USCES_PARENT_LOAD') ){
							include($parent_path . '/member/wc_login_page.php');
							exit;
						}
				}
			}

		}else{

		}
	}

	function import() {
		$res = usces_import_xml();
		if ( $res === false ) :
			$this->action_status = 'error';
		else :
			$this->action_status = 'success';
			$this->action_message = __('Import is cmpleted', 'usces');
		endif;
	}

	function export() {
		$filename = 'usces.' . substr(get_date_from_gmt(gmdate('Y-m-d H:i:s', time())), 0, 10) . '.xml';

		header('Content-Description: File Transfer');
		header("Content-Disposition: attachment; filename=$filename");
		header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);

		usces_export_xml();
		die();

	}


	function changepassword() {
		global $wpdb;

		$lostmail = $_POST['mem'];
		$lost_key = $_POST['key'];

		$member_table = usces_get_tablename( 'usces_member' );
		//$hash = usces_get_hash(trim($_POST['loginpass1']));
		$salt = usces_get_salt( $lostmail );
		$hash = usces_get_hash( trim($_POST['loginpass1']), $salt );
		$query = $wpdb->prepare("UPDATE $member_table SET mem_pass = %s WHERE mem_email = %s",
						$hash, $lostmail);
		$res = $wpdb->query( $query );

		if ( $res === false ) {
			$this->error_message = __('Error: failure in updating password', 'usces');
			return 'login';
		}else {
			usces_remove_lostmail_key( $lostmail, $lost_key );
			return 'changepasscompletion';
		}

	}

	function lostmail() {
		$delim = apply_filters( 'usces_filter_delim', $this->delim );

		$lostmail = trim($_POST['loginmail']);
		$lost_key = usces_make_lost_key();
		usces_store_lostmail_key( $lostmail, $lost_key );

		$uri = USCES_MEMBER_URL . $delim . 'uscesmode=changepassword&mem=' . urlencode($lostmail) . '&key=' . urlencode($lost_key);

		$res = usces_lostmail($uri);
		return $res;

	}

	function regist_member() {
		global $wpdb;

		$_POST = $this->stripslashes_deep_post($_POST);

		$member = $this->get_member();
		$mode = $_POST['member_regmode'];
		$member_table = usces_get_tablename( 'usces_member' );
		$member_meta_table = usces_get_tablename( 'usces_member_meta' );

		$error_mes = ( $_POST['member_regmode'] == 'newmemberfromcart' or $_POST['member_regmode'] == 'editmemberfromcart' ) ? $this->member_check_fromcart() : $this->member_check();

		if ( $error_mes != '' ) {

			$this->error_message = $error_mes;
			return $mode;

		} elseif ( $_POST['member_regmode'] == 'editmemberform' ) {

			$this->get_current_member();
			$mem_id = $this->current_member['id'];

			//$query = $wpdb->prepare("SELECT ID FROM $member_table WHERE mem_email = %s", trim($_POST['member']['mailaddress1']));
			//$id = $wpdb->get_var( $query );
			$id = $this->check_member_email( $_POST['member']['mailaddress1'] );
			if ( !empty($id) and $id != $mem_id ) {
				//$this->error_message = __('This e-mail address has been already registered.', 'usces');
				$this->error_message = __( 'This e-mail address can not be registered.', 'usces' );
				return $mode;
			}

			do_action('usces_action_pre_edit_memberdata', $_POST['member'], $mem_id);

			$query = $wpdb->prepare("SELECT mem_pass FROM $member_table WHERE ID = %d", $mem_id);
			$pass = $wpdb->get_var( $query );
			//$password = ( !empty($_POST['member']['password1']) && trim($_POST['member']['password1']) == trim($_POST['member']['password2']) ) ? usces_get_hash(trim($_POST['member']['password1'])) : $pass;
			if( !empty($_POST['member']['password1']) && trim($_POST['member']['password1']) == trim($_POST['member']['password2']) ) {
				$salt = usces_get_salt( $mem_id );
				$password = usces_get_hash( trim($_POST['member']['password1']), $salt );
			} else {
				$password = $pass;
			}
			$name1 = ( isset($_POST['member']['name1']) ) ? trim($_POST['member']['name1']) : '';
			$name2 = ( isset($_POST['member']['name2']) ) ? trim($_POST['member']['name2']) : '';
			$name3 = ( isset($_POST['member']['name3']) ) ? trim($_POST['member']['name3']) : '';
			$name4 = ( isset($_POST['member']['name4']) ) ? trim($_POST['member']['name4']) : '';
			$zipcode = ( isset($_POST['member']['zipcode']) ) ? usces_convert_zipcode( trim( $_POST['member']['zipcode'] ) ) : '';
			$pref = ( isset($_POST['member']['pref']) ) ? trim($_POST['member']['pref']) : '';
			$address1 = ( isset($_POST['member']['address1']) ) ? trim($_POST['member']['address1']) : '';
			$address2 = ( isset($_POST['member']['address2']) ) ? trim($_POST['member']['address2']) : '';
			$address3 = ( isset($_POST['member']['address3']) ) ? trim($_POST['member']['address3']) : '';
			$tel = ( isset($_POST['member']['tel']) ) ? trim($_POST['member']['tel']) : '';
			$fax = ( isset($_POST['member']['fax']) ) ? trim($_POST['member']['fax']) : '';
			$country = ( isset($_POST['member']['country']) ) ? trim($_POST['member']['country']) : '';

			$query = $wpdb->prepare("UPDATE $member_table SET 
					mem_pass = %s, mem_name1 = %s, mem_name2 = %s, mem_name3 = %s, mem_name4 = %s, 
					mem_zip = %s, mem_pref = %s, mem_address1 = %s, mem_address2 = %s, 
					mem_address3 = %s, mem_tel = %s, mem_fax = %s, mem_email = %s WHERE ID = %d",
					$password,
					$name1,
					$name2,
					$name3,
					$name4,
					$zipcode,
					$pref,
					$address1,
					$address2,
					$address3,
					$tel,
					$fax,
					trim($_POST['member']['mailaddress1']),
					$mem_id
					);
			$res = $wpdb->query( $query );

			if( $res !== false ){
				$this->set_member_meta_value('customer_country', $country, $mem_id);
				$res = $this->reg_custom_member($mem_id);
				unset( $_SESSION['usces_entry']['custom_customer'] );
				$this->cart->entry();
				do_action('usces_action_edit_memberdata', $_POST['member'], $mem_id);
				$meta_keys = apply_filters( 'usces_filter_delete_member_pcid', "'remise_pcid', 'digitalcheck_ip_user_id'" );
				$query = $wpdb->prepare("DELETE FROM $member_meta_table WHERE member_id = %d AND meta_key IN( $meta_keys )",
						$mem_id
						);
				$res = $wpdb->query( $query );

				$user = $_POST['member'];
				$user['ID'] = $mem_id;
				usces_send_updmembermail( $user );

				$this->get_current_member();
				return 'editmemberform';

			} else {
				$this->error_message = __('Error:failure in update', 'usces');
				return $mode;
			}

		} elseif ( $_POST['member_regmode'] == 'newmemberform' ) {
			//$query = $wpdb->prepare("SELECT ID FROM $member_table WHERE mem_email = %s", trim($_POST['member']['mailaddress1']));
			//$id = $wpdb->get_var( $query );
			$id = $this->check_member_email( $_POST['member']['mailaddress1'] );
			if ( !empty($id) ) {
				//$this->error_message = __('This e-mail address has been already registered.', 'usces');
				$this->error_message = __( 'This e-mail address can not be registered.', 'usces' );
				return $mode;
			} else {
				$point = $this->options['start_point'];
				//$pass = usces_get_hash(trim($_POST['member']['password1']));
				$salt = usces_get_salt( '', 1 );
				$pass = usces_get_hash( trim($_POST['member']['password1']), $salt );
				$name1 = ( isset($_POST['member']['name1']) ) ? trim($_POST['member']['name1']) : '';
				$name2 = ( isset($_POST['member']['name2']) ) ? trim($_POST['member']['name2']) : '';
				$name3 = ( isset($_POST['member']['name3']) ) ? trim($_POST['member']['name3']) : '';
				$name4 = ( isset($_POST['member']['name4']) ) ? trim($_POST['member']['name4']) : '';
				$zipcode = ( isset($_POST['member']['zipcode']) ) ? usces_convert_zipcode( trim( $_POST['member']['zipcode'] ) ) : '';
				$pref = ( isset($_POST['member']['pref']) ) ? trim($_POST['member']['pref']) : '';
				$address1 = ( isset($_POST['member']['address1']) ) ? trim($_POST['member']['address1']) : '';
				$address2 = ( isset($_POST['member']['address2']) ) ? trim($_POST['member']['address2']) : '';
				$address3 = ( isset($_POST['member']['address3']) ) ? trim($_POST['member']['address3']) : '';
				$tel = ( isset($_POST['member']['tel']) ) ? trim($_POST['member']['tel']) : '';
				$fax = ( isset($_POST['member']['fax']) ) ? trim($_POST['member']['fax']) : '';
				$country = ( isset($_POST['member']['country']) ) ? trim($_POST['member']['country']) : '';

				$query = $wpdb->prepare("INSERT INTO $member_table 
						(mem_email, mem_pass, mem_status, mem_cookie, mem_point, 
						mem_name1, mem_name2, mem_name3, mem_name4, mem_zip, mem_pref, 
						mem_address1, mem_address2, mem_address3, mem_tel, mem_fax, 
						mem_delivery_flag, mem_delivery, mem_registered, mem_nicename) 
						VALUES (%s, %s, %d, %s, %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %s, %s, %s)",
						trim($_POST['member']['mailaddress1']),
						$pass,
						0,
						"",
						$point,
						$name1,
						$name2,
						$name3,
						$name4,
						$zipcode,
						$pref,
						$address1,
						$address2,
						$address3,
						$tel,
						$fax,
						'',
						'',
						get_date_from_gmt(gmdate('Y-m-d H:i:s', time())),
						'');
				$res = $wpdb->query( $query );

				if($res !== false) {
					$user = $_POST['member'];
					$user['ID'] = $wpdb->insert_id;
					$this->set_member_meta_value('customer_country', $country, $user['ID']);

					if( !empty($salt) ) {
						$this->set_member_meta_value( 'mem_salt', $salt, $user['ID'] );
					}

					$res = $this->reg_custom_member($user['ID']);

					if( apply_filters( 'usces_filter_veirfyemail_newmemberform', false, $user ) ){
						return 'memberverifying';
					}

					do_action('usces_action_member_registered', $_POST['member'], $user['ID']);
					unset( $_SESSION['usces_member'] );
					usces_send_regmembermail($user);
					return 'newcompletion';

				} else {
					$this->error_message = __('Error:failure in update', 'usces');
					return $mode;
				}
			}

		} elseif ( $_POST['member_regmode'] == 'newmemberfromcart' ) {

			//$query = $wpdb->prepare("SELECT ID FROM $member_table WHERE mem_email = %s", trim($_POST['customer']['mailaddress1']));
			//$id = $wpdb->get_var( $query );
			$id = $this->check_member_email( $_POST['customer']['mailaddress1'] );
			if ( !empty($id) ) {
				//$this->error_message = __('This e-mail address has been already registered.', 'usces');
				$this->error_message = __( 'This e-mail address can not be registered.', 'usces' );
				return $mode;
			} else {
				$point = $this->options['start_point'];
				//$pass = usces_get_hash(trim($_POST['customer']['password1']));
				$salt = usces_get_salt( '', 1 );
				$pass = usces_get_hash( trim($_POST['customer']['password1']), $salt );
				$name1 = ( isset($_POST['customer']['name1']) ) ? trim($_POST['customer']['name1']) : '';
				$name2 = ( isset($_POST['customer']['name2']) ) ? trim($_POST['customer']['name2']) : '';
				$name3 = ( isset($_POST['customer']['name3']) ) ? trim($_POST['customer']['name3']) : '';
				$name4 = ( isset($_POST['customer']['name4']) ) ? trim($_POST['customer']['name4']) : '';
				$zipcode = ( isset($_POST['customer']['zipcode']) ) ? usces_convert_zipcode( trim( $_POST['customer']['zipcode'] ) ) : '';
				$pref = ( isset($_POST['customer']['pref']) ) ? trim($_POST['customer']['pref']) : '';
				$address1 = ( isset($_POST['customer']['address1']) ) ? trim($_POST['customer']['address1']) : '';
				$address2 = ( isset($_POST['customer']['address2']) ) ? trim($_POST['customer']['address2']) : '';
				$address3 = ( isset($_POST['customer']['address3']) ) ? trim($_POST['customer']['address3']) : '';
				$tel = ( isset($_POST['customer']['tel']) ) ? trim($_POST['customer']['tel']) : '';
				$fax = ( isset($_POST['customer']['fax']) ) ? trim($_POST['customer']['fax']) : '';
				$country = ( isset($_POST['customer']['country']) ) ? trim($_POST['customer']['country']) : '';

				$query = $wpdb->prepare("INSERT INTO $member_table 
						(mem_email, mem_pass, mem_status, mem_cookie, mem_point, 
						mem_name1, mem_name2, mem_name3, mem_name4, mem_zip, mem_pref, 
						mem_address1, mem_address2, mem_address3, mem_tel, mem_fax, 
						mem_delivery_flag, mem_delivery, mem_registered, mem_nicename) 
						VALUES (%s, %s, %d, %s, %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %s, %s, %s)",
						trim($_POST['customer']['mailaddress1']),
						$pass,
						0,
						"",
						$point,
						$name1,
						$name2,
						$name3,
						$name4,
						$zipcode,
						$pref,
						$address1,
						$address2,
						$address3,
						$tel,
						$fax,
						'',
						'',
						get_date_from_gmt(gmdate('Y-m-d H:i:s', time())),
						'');
				$res = $wpdb->query( $query );

				if($res !== false) {
					$member_id = $wpdb->insert_id;
					$user = $_POST['customer'];
					$user['ID'] = $member_id;
					$this->set_member_meta_value('customer_country', $country, $member_id);

					if( !empty($salt) ) {
						$this->set_member_meta_value( 'mem_salt', $salt, $member_id );
					}

					$res = $this->reg_custom_member($member_id);

					if( apply_filters( 'usces_filter_veirfyemail_newmemberfromcart', false, $user ) ){
						return 'cartverifying';
					}

					do_action('usces_action_member_registered', $_POST['customer'], $member_id);
					usces_send_regmembermail($user);
					$_POST['loginmail'] = trim($_POST['customer']['mailaddress1']);
					$_POST['loginpass'] = trim($_POST['customer']['password1']);
					if( $this->member_login() == 'member' ){
						$_SESSION['usces_entry']['member_regmode'] = 'editmemberfromcart';
						return 'newcompletion';
					}

				} else {
					$this->error_message = __('Error:failure in update', 'usces');
					return $mode;
				}
			}

		} elseif ( $_POST['member_regmode'] == 'editmemberfromcart' ) {

			$this->get_current_member();
			$mem_id = $this->current_member['id'];

			//$query = $wpdb->prepare("SELECT ID FROM $member_table WHERE mem_email = %s", trim($_POST['customer']['mailaddress1']));
			//$id = $wpdb->get_var( $query );
			$id = $this->check_member_email( $_POST['customer']['mailaddress1'] );
			if ( !empty($id) and $id != $mem_id ) {
				//$this->error_message = __('This e-mail address has been already registered.', 'usces');
				$this->error_message = __( 'This e-mail address can not be registered.', 'usces' );
				return $mode;
			}

			do_action('usces_action_pre_edit_memberdata', $_POST['customer'], $mem_id);

			$query = $wpdb->prepare("SELECT mem_pass FROM $member_table WHERE ID = %d", $mem_id);
			$pass = $wpdb->get_var( $query );
			//$password = ( !empty($_POST['customer']['password1']) && trim($_POST['customer']['password1']) == trim($_POST['customer']['password2']) ) ? usces_get_hash(trim($_POST['customer']['password1'])) : $pass;
			if( !empty($_POST['customer']['password1']) && trim($_POST['customer']['password1']) == trim($_POST['customer']['password2']) ) {
				$salt = usces_get_salt( $mem_id );
				$password = usces_get_hash( trim($_POST['customer']['password1']), $salt );
			} else {
				$password = $pass;
			}
			$name1 = ( isset($_POST['customer']['name1']) ) ? trim($_POST['customer']['name1']) : '';
			$name2 = ( isset($_POST['customer']['name2']) ) ? trim($_POST['customer']['name2']) : '';
			$name3 = ( isset($_POST['customer']['name3']) ) ? trim($_POST['customer']['name3']) : '';
			$name4 = ( isset($_POST['customer']['name4']) ) ? trim($_POST['customer']['name4']) : '';
			$zipcode = ( isset($_POST['customer']['zipcode']) ) ? usces_convert_zipcode( trim( $_POST['customer']['zipcode'] ) ) : '';
			$pref = ( isset($_POST['customer']['pref']) ) ? trim($_POST['customer']['pref']) : '';
			$address1 = ( isset($_POST['customer']['address1']) ) ? trim($_POST['customer']['address1']) : '';
			$address2 = ( isset($_POST['customer']['address2']) ) ? trim($_POST['customer']['address2']) : '';
			$address3 = ( isset($_POST['customer']['address3']) ) ? trim($_POST['customer']['address3']) : '';
			$tel = ( isset($_POST['customer']['tel']) ) ? trim($_POST['customer']['tel']) : '';
			$fax = ( isset($_POST['customer']['fax']) ) ? trim($_POST['customer']['fax']) : '';
			$country = ( isset($_POST['customer']['country']) ) ? trim($_POST['customer']['country']) : '';

			$query = $wpdb->prepare("UPDATE $member_table SET 
					mem_pass = %s, mem_name1 = %s, mem_name2 = %s, mem_name3 = %s, mem_name4 = %s, 
					mem_zip = %s, mem_pref = %s, mem_address1 = %s, mem_address2 = %s, 
					mem_address3 = %s, mem_tel = %s, mem_fax = %s, mem_email = %s WHERE ID = %d",
					$password,
					$name1,
					$name2,
					$name3,
					$name4,
					$zipcode,
					$pref,
					$address1,
					$address2,
					$address3,
					$tel,
					$fax,
					trim($_POST['customer']['mailaddress1']),
					$mem_id
					);
			$res = $wpdb->query( $query );
			if( $res !== false ){
				$this->set_member_meta_value('customer_country', $country, $mem_id);
				$res = $this->reg_custom_member($mem_id);
				do_action('usces_action_edit_memberdata', $_POST['customer'], $mem_id);
				unset($_SESSION['usces_member']);
				$this->member_just_login(trim($_POST['customer']['mailaddress1']), trim($_POST['customer']['password1']));
				return 'newcompletion';

			} else {
				$this->error_message = __('Error:failure in update', 'usces');
				return $mode;
			}
		}
	}

	function delete_member() {
		$res = false;

		if( ! $this->is_member_logged_in() )
			return $res;
		$mem = $this->get_member();
		if( ! $mem['ID'] )
			return $res;

		$del = usces_delete_member_check_front( $mem['ID'] );
		if( $del ) {
			$res = usces_delete_memberdata( $mem['ID'] );
			if( $res ) {
				usces_send_delmembermail( $mem );
			}
		}

		return $res;
	}

	function is_member_logged_in( $id = false ) {
		if( $id === false ){
			if( !empty($_SESSION['usces_member']['ID']) )
				return true;
			else
				return false;
		}else{
			if( !empty($_SESSION['usces_member']['ID']) && $_SESSION['usces_member']['ID'] == $id )
				return true;
			else
				return false;
		}
	}

	function is_member($email) {
		global $wpdb;

		$member_table = usces_get_tablename( 'usces_member' );
		$query = $wpdb->prepare("SELECT mem_email FROM $member_table WHERE mem_email = %s", $email);
		$member = $wpdb->get_row( $query, ARRAY_A );
		if ( empty($member) ) {
			return false;
		}else{
			return true;
		}
	}

	function member_login() {
		global $wpdb;
		$_POST = $this->stripslashes_deep_post($_POST);

		$cookie = $this->get_cookie();
		$metatable_name = usces_get_tablename( 'usces_member_meta' );
		$member_table = usces_get_tablename( 'usces_member' );
        if(!class_exists('RateLimiter')){
            require plugin_dir_path(__FILE__).'rateLimiter.class.php';
        }
        $rateLimiter = new RateLimiter();

		if ( isset($cookie['rme']) && $cookie['rme'] == 'forever' && !isset($_POST['rememberme']) && !isset($_POST['loginmail'])) {

			$_forever = isset($cookie['name']) ? $cookie['name'] : '';
			if( $_forever ){
				$query = $wpdb->prepare("SELECT member_id FROM $metatable_name WHERE meta_value = %s AND meta_key = %s",
										$_forever, '_forever');
				$id = $wpdb->get_var($query);
			}else{
				$id = '';
			}

			if ( !$id ) {
				$cookie['name'] = '';
				$cookie['rme'] = '';
				$this->set_cookie($cookie);
                $rateLimiter->saveLoginFailed();
				return 'login';
			} else {
				$query = $wpdb->prepare("SELECT * FROM $member_table WHERE ID = %s", $id);
				$member = $wpdb->get_row( $query, ARRAY_A );
				if ( empty($member) ) {
                    $rateLimiter->saveLoginFailed();
					$this->error_message = __('<b>Error:</b> Your E-mail or password is incorrect.', 'usces');
					return 'login';
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
					$_SESSION['usces_member']['country'] = $this->get_member_meta_value('customer_country', $member['ID']);
					$_SESSION['usces_member']['status'] = $member['mem_status'];
					$this->set_session_custom_member($member['ID']);
					$this->get_current_member();

					do_action( 'usces_action_after_login' );
					$this->set_cookie($cookie);
					return apply_filters( 'usces_filter_member_login', 'member', $member );
				}
			}
		} else if ( isset($_POST['loginmail']) && WCUtils::is_blank($_POST['loginmail']) && isset($_POST['loginpass']) && WCUtils::is_blank($_POST['loginpass']) && isset($cookie['rme']) && $cookie['rme'] != 'forever' ) {
            $rateLimiter->saveLoginFailed();
		    return 'login';
		} else if ( isset($_POST['loginmail']) && WCUtils::is_blank($_POST['loginpass']) && isset($cookie['rme']) && $cookie['rme'] != 'forever' ) {
            $rateLimiter->saveLoginFailed();
		    $this->error_message = __('<b>Error:</b> Enter the password.', 'usces');
			return 'login';
		} else if ( !isset($_POST['loginmail']) ){
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) $rateLimiter->saveLoginFailed();
			return 'login';
		} else {

			if ( isset($_POST['loginmail']) ){
				$nonce = isset( $_REQUEST['wel_nonce'] ) ? $_REQUEST['wel_nonce'] : '';
				if( !$nonce ){
					$nonce = isset( $_REQUEST['wc_nonce'] ) ? $_REQUEST['wc_nonce'] : '';
				}
				$noncekey = 'post_member' . $this->get_uscesid(false);
				if( !wp_verify_nonce( $nonce, $noncekey ) && !$this->is_member_logged_in() ){
					$rateLimiter->saveLoginFailed();
					die('Security check4');
				}
			}

			$email = isset($_POST['loginmail']) ? trim($_POST['loginmail']) : '';
			//$pass = isset($_POST['loginpass']) ? usces_get_hash(trim($_POST['loginpass'])) : '';
			$salt = usces_get_salt( $email );
			$pass = usces_get_hash( trim($_POST['loginpass']), $salt );
			$member_table = usces_get_tablename( 'usces_member' );

			$query  = $wpdb->prepare( "SELECT * FROM $member_table WHERE mem_email = %s AND mem_pass = %s", $email, $pass );
			$query  = apply_filters( 'usces_filter_member_login_query', $query, $email, $pass );
			$member = $wpdb->get_row( $query, ARRAY_A );

            if ( empty($member) ) {
                $rateLimiter->saveLoginFailed();
                $this->current_member['email'] = htmlspecialchars($email);
                $this->error_message = __('<b>Error:</b> Your E-mail or password is incorrect.', 'usces');
                return 'login';
            } else {

                if( USCES_VERIFY_MEMBERS_EMAIL::$opts['switch_flag'] ){
                    $verify_flag = $this->get_member_meta_value('_verifying', $member['ID']);
                }else{
                    $verify_flag = '';
                }
                if ( !empty($verify_flag) ) {
                    $rateLimiter->saveLoginFailed();
                    $this->error_message = __('<b>Error:</b> Membership registration is not complete.', 'usces');
                    return 'login';
                }

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
                $_SESSION['usces_member']['country'] = $this->get_member_meta_value('customer_country', $member['ID']);
                $_SESSION['usces_member']['status'] = $member['mem_status'];
                $this->set_session_custom_member($member['ID']);
                $this->get_current_member();
                $rateLimiter->clear_login_failed();

                if( isset($_POST['rememberme']) ){
                    $_forever = $this->set_login_forever( $member['ID'] );
                    $cookie['name'] = $_forever;
                    $cookie['rme'] = 'forever';
                    $this->set_cookie($cookie);
                }else{
                    $cookie['name'] = '';
                    $cookie['rme'] = '';
                    $this->set_cookie($cookie);
                }

                do_action( 'usces_action_after_login' );
                return apply_filters( 'usces_filter_member_login', 'member', $member );
            }
		}
	}

	function set_login_forever($member_id) {
		if( !$member_id )
			return false;

		$_forever = wp_generate_password( 32, false, false );
		$this->set_member_meta_value('_forever', $_forever, $member_id);
		return $_forever;
	}

	function set_member_session_data($member_id) {
		global $wpdb;

		$member_table = usces_get_tablename( 'usces_member' );
		$query = $wpdb->prepare("SELECT * FROM $member_table WHERE ID = %s", $member_id);
		$member = $wpdb->get_row( $query, ARRAY_A );
		if ( !empty($member) ) {
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
			$_SESSION['usces_member']['country'] = $this->get_member_meta_value('customer_country', $member['ID']);
			$_SESSION['usces_member']['status'] = $member['mem_status'];
			$this->set_session_custom_member($member['ID']);
			$this->get_current_member();

		}
		return;
	}

	function member_just_login($email, $pass) {
		global $wpdb;
		//$pass = usces_get_hash($pass);
		$salt = usces_get_salt( $email );
		$pass = usces_get_hash( $pass, $salt );
		$member_table = usces_get_tablename( 'usces_member' );

		$query = $wpdb->prepare("SELECT * FROM $member_table WHERE mem_email = %s AND mem_pass = %s", $email, $pass);
		$member = $wpdb->get_row( $query, ARRAY_A );
		if ( empty($member) ) {
			$this->current_member['email'] = htmlspecialchars($email);
			$this->error_message = __('<b>Error:</b> Password is not correct.', 'usces');
			return 'login';
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
			$_SESSION['usces_member']['country'] = $this->get_member_meta_value('customer_country', $member['ID']);
			$_SESSION['usces_member']['status'] = $member['mem_status'];
			$this->set_session_custom_member($member['ID']);
			$this->get_current_member();

			do_action( 'usces_action_after_login' );
			return apply_filters( 'usces_filter_member_login', 'member', $member );
		}
	}

	function member_logout() {
		$cookie = $this->get_cookie();
		$cookie['name'] = '';
		$cookie['rme'] = '';
		$options = get_option('usces');

		$this->set_cookie($cookie);

		$fcookie = $this->get_cookie( $options['usces_key'] );

		unset($_SESSION['usces_member'], $_SESSION['usces_entry']);
		do_action('usces_action_member_logout');
	}

	function get_current_member() {

		if ( isset($_SESSION['usces_member']['ID']) ) {
			$this->current_member['id'] = $_SESSION['usces_member']['ID'];
			$this->current_member['name'] = usces_localized_name( $_SESSION['usces_member']['name1'], $_SESSION['usces_member']['name2'], 'return');
		} else {
			$this->current_member['id'] = 0;
			$this->current_member['name'] = __('guest', 'usces');
		}
	}

	function get_member() {
		$res = array(
					'ID' => '',
					'registered' => '',
					'mailaddress1' => '',
					'mailaddress2' => '',
					'password1' => '',
					'password2' => '',
					'point' => '',
					'name1' => '',
					'name2' => '',
					'name3' => '',
					'name4' => '',
					'zipcode' => '',
					'address1' => '',
					'address2' => '',
					'address3' => '',
					'tel' => '',
					'fax' => '',
					'country' => '',
					'pref' => '',
					'status' => ''
				 );
		if(!empty($_SESSION['usces_member'])) {
			foreach ( $_SESSION['usces_member'] as $key => $value ) {
				if(is_array($_SESSION['usces_member'][$key]))
					$res[$key] = stripslashes_deep($value);
				else
					$res[$key] = stripslashes($value);
			}
		}
		return $res;
	}

	function get_member_info( $mid ) {
		global $wpdb;
		$infos = array();
		$table = usces_get_tablename( 'usces_member' );
		$query = $wpdb->prepare("SELECT * FROM $table WHERE ID = %d", $mid);
		$datas = $wpdb->get_results( $query, ARRAY_A );
		if( $datas ) {
			$infos = $datas[0];

			$table = usces_get_tablename( 'usces_member_meta' );
			$query = $wpdb->prepare("SELECT meta_key, meta_value FROM $table WHERE member_id = %d", $mid);
			$metas = $wpdb->get_results( $query, ARRAY_A );

			foreach( $metas as $meta ){
				$infos[$meta['meta_key']] = maybe_unserialize($meta['meta_value']);
			}
		}
		return $infos;
	}

	function set_member_info( $data=array(), $mid = '') {
		global $wpdb;

		$table = usces_get_tablename( 'usces_member' );
		$res = $wpdb->update( $table, $data, array('ID'=>$mid), NULL, array('%d'));
		return $res;
	}

	function check_member_email( $email ) {
		global $wpdb;

		$member_table_name = usces_get_tablename( 'usces_member' );
		$query = $wpdb->prepare( "SELECT ID FROM $member_table_name WHERE mem_email = %s", trim( $email ) );
		$id = $wpdb->get_var( $query );
		return $id;
	}

	function is_order($mid, $oid) {
		global $wpdb;

		$mid = (int)$mid;
		$oid = (int)$oid;

		$table = $wpdb->prefix . "usces_order";
		$query = $wpdb->prepare("SELECT ID FROM $table WHERE ID = %d AND mem_id = %d", $oid, $mid);
		$mem_id = $wpdb->get_var( $query );
		if ( empty($mem_id) ) {
			return false;
		}else{
			return true;
		}
	}

	function is_purchased_item( $mid, $post_id, $sku = NULL, $order_id = NULL ) {
		global $wpdb;
		$res = false;

		if( empty( $order_id ) ) {
			$history = $this->get_member_history( $mid, true );
			foreach ( $history as $umhs ) {
				$cart = $umhs['cart'];
				$status = $umhs['order_status'];
				$cart_count = ( $cart && is_array( $cart ) ) ? count( $cart ) : 0;
				for($i=0; $i<$cart_count; $i++) {
					$cart_row = $cart[$i];
					$sku_code = urldecode($cart_row['sku']);
					if( empty($sku) ){
						if( $cart_row['post_id'] == $post_id && (false === strpos($status, 'noreceipt') && false === strpos($status, 'pending')) ){
							$res = true;
							break 2;
						}elseif( $cart_row['post_id'] == $post_id && (false !== strpos($status, 'noreceipt') || false !== strpos($status, 'pending')) ){
							$res = 'noreceipt';
							break 2;
						}
					}else{
						if( $cart_row['post_id'] == $post_id && $sku_code == $sku && (false === strpos($status, 'noreceipt') && false === strpos($status, 'pending')) ){
							$res = true;
							break 2;
						}elseif( $cart_row['post_id'] == $post_id && $sku_code == $sku && (false !== strpos($status, 'noreceipt') || false !== strpos($status, 'pending')) ){
							$res = 'noreceipt';
							break 2;
						}
					}
				}

			}
		} else {
			$order_table_name = $wpdb->prefix . 'usces_order';
			$query            = $wpdb->prepare( "SELECT order_status FROM $order_table_name WHERE ID = %d AND mem_id = %d",
				$order_id, $mid );
			$order            = $wpdb->get_row( $query );
			if ( is_null( $order ) ) { // 該当の受注データが一件も見つからなかった場合、購入していないと判断する
				$res = false;
			} else { // 該当の受注データが見つかった場合には受注ステータスを確認する処理を行う
				$status = $order->order_status;
				$cart   = usces_get_ordercartdata( $order_id );
				foreach ( $cart as $cart_row ) {
					$sku_code = urldecode( $cart_row['sku'] );
					if ( empty( $sku ) ) {
						if ( $cart_row['post_id'] == $post_id && ( false === strpos( $status,
									'noreceipt' ) && false === strpos( $status, 'pending' ) && false === strpos( $status, 'cancel' ) ) ) {
							$res = true;
							break;
						} elseif ( $cart_row['post_id'] == $post_id && ( false !== strpos( $status,
									'noreceipt' ) || false !== strpos( $status, 'pending' ) || false !== strpos( $status, 'cancel' )  ) ) {
							$res = 'noreceipt';
							break;
						}
					} else {
						if ( $cart_row['post_id'] == $post_id && $sku_code == $sku && ( false === strpos( $status,
									'noreceipt' ) && false === strpos( $status, 'pending' ) && false === strpos( $status, 'cancel' ) ) ) {
							$res = true;
							break;
						} elseif ( $cart_row['post_id'] == $post_id && $sku_code == $sku && ( false !== strpos( $status,
									'noreceipt' ) || false !== strpos( $status, 'pending' ) || false !== strpos( $status, 'cancel' ) ) ) {
							$res = 'noreceipt';
							break;
						}
					}
				}
			}
		}

		return apply_filters( 'usces_filter_is_purchased_item', $res, $mid, $post_id, $sku, $order_id );
	}

	function get_order_data($order_id, $mode = '' ) {
		global $wpdb;
		$order_table = $wpdb->prefix . "usces_order";

		$query = $wpdb->prepare("SELECT * FROM $order_table WHERE ID = %d", $order_id);

		if( 'direct' == $mode ){
			$value = $wpdb->get_row( $query, ARRAY_A );
			return $value;
		}

		$value = $wpdb->get_row( $query );

		if( $value == NULL ) {
			return false;
		}else{
			$res =array();
		}
		if(strpos($value->order_status, 'cancel') !== false || strpos($value->order_status, 'estimate') !== false){
			return false;
		}

		$total_price = $value->order_item_total_price - $value->order_usedpoint + $value->order_discount + $value->order_shipping_charge + $value->order_cod_fee + $value->order_tax;
		if( $total_price < 0 ) $total_price = 0;
		$res = array(
					'ID' => $value->ID,
					'mem_id' => $value->mem_id,
					'cart' => unserialize($value->order_cart),
					'condition' => unserialize($value->order_condition),
					'getpoint' => $value->order_getpoint,
					'usedpoint' => $value->order_usedpoint,
					'discount' => $value->order_discount,
					'payment_name' => $value->order_payment_name,
					'shipping_charge' => $value->order_shipping_charge,
					'cod_fee' => $value->order_cod_fee,
					'tax' => $value->order_tax,
					'end_price' => $total_price,
					'status' => $value->order_status,
					'date' => mysql2date(__('Y/m/d'), $value->order_date),
					'modified' => mysql2date(__('Y/m/d'), $value->order_modified)
					);

		return $res;
	}

	function get_orderIDs_by_postID($mem_id, $post_id) {
		global $wpdb;
		$order_table = $wpdb->prefix . "usces_order";

		$query = $wpdb->prepare("SELECT ID, order_cart, order_status FROM $order_table WHERE mem_id = %d ORDER BY order_modified DESC, order_date DESC", $mem_id);
		$rows = $wpdb->get_query( $query, ARRAY_A );

		if( $value == NULL ) {
			return false;
		}else{
			foreach($rows as $row){
				if(strpos($row['order_status'], 'cancel') !== false || strpos($row['order_status'], 'estimate') !== false){
					continue;
				}else{
					$carts = unserialize($row['order_cart']);
					foreach($carts as $cart){
						if( $post_id == $cart['post_id'] ){
							$res[] = $row['ID'];
							break;
						}
					}
				}
			}
		}
		return $res;
	}

	function incart_check() {
		$mes = array();

		$ids                 = array_keys($_POST['inCart']);
		$post_id             = $ids[0];
		$skus                = array_keys($_POST['inCart'][$post_id]);
		$sku                 = $skus[0];
		$sku_code            = urldecode($sku);
		$quant               = isset($_POST['quant'][$post_id][$sku]) ? (int)$_POST['quant'][$post_id][$sku] : 1;
		$stock               = $this->getItemZaikoNum($post_id, $sku);
		$zaiko_id            = (int)$this->getItemZaikoStatusId($post_id, $sku);
		$product             = wel_get_product( $post_id );
		$itemRestriction     = $product['itemRestriction'];
		$itemOrderAcceptable = $this->getItemOrderAcceptable( $post_id );

		if( 1 > $quant ){
			$mes[$post_id][$sku] = __('enter the correct amount', 'usces') . "<br />";
		}else if( $quant > (int)$itemRestriction && !WCUtils::is_blank($itemRestriction) && !WCUtils::is_zero($itemRestriction) ){
			$mes[$post_id][$sku] = sprintf(__("This article is limited by %d at a time.", 'usces'), $itemRestriction) . "<br />";
		}else if( $itemOrderAcceptable != 1 && $quant > (int)$stock && !WCUtils::is_blank($stock) ){
			$mes[$post_id][$sku] = __('Sorry, stock is insufficient.', 'usces') . ' ' . __('Current stock', 'usces') . $stock . "<br />";
		}else if( $itemOrderAcceptable != 1 && !$this->is_item_zaiko( $post_id, $sku_code ) ){
			$mes[$post_id][$sku] = __('Sorry, this item is sold out.', 'usces') . "<br />";
		} else {
			$mes[ $post_id ][ $sku ] = '';
		}

		$ioptkeys = $this->get_itemOptionKey( $post_id, true );
		if($ioptkeys){
			foreach($ioptkeys as $key => $value){
				$optValues = $this->get_itemOptions( urldecode($value), $post_id );
				if( 0 == $optValues['means'] ){ //case of select
					if( $optValues['essential'] && '#NONE#' == $_POST['itemOption'][$post_id][$sku][$value] ){
						$mes[$post_id][$sku] .= sprintf(__("Chose the %s", 'usces'), urldecode($value)) . "<br />";
					}
				}elseif( 1 == $optValues['means'] ){ //case of multiselect
					if( $optValues['essential'] ){
						$mselect = 0;
						foreach((array)$_POST['itemOption'][$post_id][$sku][$value] as $mvalue){
							if(!empty($mvalue) and '#NONE#' != $mvalue) $mselect++;
						}
						if( $mselect == 0 ){
							$mes[$post_id][$sku] .= sprintf(__("Chose the %s", 'usces'), urldecode($value)) . "<br />";
						}
					}
				}elseif( in_array( $optValues['means'], array( 3, 4 ) ) ){ //case of radio & checkbox
					if( $optValues['essential'] ){

						if( !isset( $_POST['itemOption'][$post_id][$sku][$value] ) ){
							$mes[$post_id][$sku] .= sprintf(__("Chose the %s", 'usces'), urldecode($value)) . "<br />";
						}
					}
				}else{ //case of text
					if( $optValues['essential'] && WCUtils::is_blank($_POST['itemOption'][$post_id][$sku][$value]) ){
						$mes[$post_id][$sku] .= sprintf(__("Input the %s", 'usces'), urldecode($value)) . "<br />";
					}
				}
			}
		}

		$mes = apply_filters('usces_filter_incart_check', $mes, $post_id, $sku);

		if( isset($mes[$post_id]) && is_array($mes[$post_id]) ){
			$rembr = array();
			foreach( $mes[$post_id] as $skukey => $skuvalue ){
				if ( ! empty( $skuvalue ) ) {
					$rembr[ $post_id ][ $skukey ] = rtrim( $skuvalue, "<br />" );
				}
			}
			$mes = $rembr;
		}

		if( !empty($mes) ){
			$_SESSION['usces_singleitem']['itemOption'] = isset($_POST['itemOption']) ? $_POST['itemOption'] : [];
			$_SESSION['usces_singleitem']['quant'] = isset($_POST['quant']) ? (int)$_POST['quant'] : 1;
			$_SESSION['usces_singleitem']['error_message'] = $mes;
			if( false === strpos($_POST['usces_referer'], 'http') ){
				$parse_url = parse_url(get_home_url());
				$port = '';
				if ( isset( $parse_url['port'] ) && ! empty( $parse_url['port'] ) ) {
					$port = ':' . $parse_url['port'];
				}
				header('location: ' . $parse_url['scheme'] . '://' . $parse_url['host'] . $port . esc_url($_POST['usces_referer']) . apply_filters('usces_filter_incart_redirect', '#cart_button', $post_id, $sku));
			}else{
				header('location: ' . esc_url($_POST['usces_referer']) . apply_filters('usces_filter_incart_redirect', '#cart_button', $post_id, $sku));
			}
			exit;
		}

		do_action('usces_action_incart_checked', $mes, $post_id, $sku);
	}

	function zaiko_check() {

		$logged_in_member = $this->get_member();
		if ( ! empty( $logged_in_member['ID'] ) ) {
			if ( ! $this->member_exists( $logged_in_member['ID'] ) ) {
				$mes                 = __( 'Your membership information could not be verified.', 'usces' );
				$this->error_message = $mes;
				$this->member_logout();
				return $mes;
			}
		}

		$mes    = '';
		$cart   = $this->cart->get_cart();
		$stocks = array();

		$cart_count = ( $cart && is_array( $cart ) ) ? count( $cart ) : 0;
		for($i=0; $i<$cart_count; $i++) {
			$cart_row = $cart[$i];
			$post_id = $cart_row['post_id'];
			$sku = $cart_row['sku'];
			$sku_code = urldecode($cart_row['sku']);

			$quant = ( isset($_POST['quant'][$i][$post_id][$sku]) ) ? trim($_POST['quant'][$i][$post_id][$sku]) : $cart_row['quantity'];
			$zaiko_id = (int)$this->getItemZaikoStatusId($post_id, $sku_code);
			$stock = $this->getItemZaikoNum($post_id, $sku_code);
			if( !isset($stocks[$post_id][$sku]) ){
				if( !WCUtils::is_blank($stock) ){
					$stocks[$post_id][$sku] = $stock;
				}else{
					$stocks[$post_id][$sku] = NULL;
				}
			}
			$checkstock             = $stocks[$post_id][$sku];
			$stocks[$post_id][$sku] = $stocks[$post_id][$sku] - $quant;
			$product                = wel_get_product( $post_id );
			$itemRestriction        = $product['itemRestriction'];
			$itemOrderAcceptable    = $this->getItemOrderAcceptable( $post_id );
			$post_status            = get_post_status( $post_id );

			if( 1 > (int)$quant ){
				$mes .= sprintf(__("Enter the correct amount for the No.%d item.", 'usces'), ($i+1)) . "<br />";
			}else if( !$this->is_item_zaiko( $post_id, $sku_code ) || ( $itemOrderAcceptable != 1 && WCUtils::is_zero($stock) ) || 'publish' != $post_status ){
				$mes .= sprintf(__('Sorry, No.%d item is sold out.', 'usces'), ($i+1)) . "<br />";
			}else if( $quant > (int)$itemRestriction && !WCUtils::is_blank($itemRestriction) && !WCUtils::is_zero($itemRestriction) ){
				$mes .= sprintf(__('This article is limited by %1$d at a time for the No.%2$d item.', 'usces'), $itemRestriction, ($i+1)) . "<br />";
			}else if( $itemOrderAcceptable != 1 && 0 > $stocks[$post_id][$sku] && !WCUtils::is_blank($stock) ){
				$mes .= sprintf(__('Stock of No.%1$d item is remainder %2$d.', 'usces'), ($i+1), $checkstock) . "<br />";
			}
		}
		$mes = apply_filters('usces_filter_zaiko_check', $mes, $cart);
		return $mes;
	}

	/**
	 * Check if the member exists in the database or not.
	 *
	 * @param integer $member_id The id of the member.
	 * @return boolean true/false
	 */
	function member_exists( $member_id ) {
		$member_info = $this->get_member_info( $member_id );
		if ( empty( $member_info ) ) {
			return false;
		} else {
			return true;
		}
	}

	function get_pwd_errors($password) {
		if(WCUtils::is_blank($password)) return __('Please enter a password.', 'usces') . "<br />";

		$ret = '';
		$system_option = $this->options['system'];
		$pwd_rule_min = $system_option['member_pass_rule_min'];
		$pwd_rule_max = empty($system_option['member_pass_rule_max']) ? 30 : $system_option['member_pass_rule_max'];
		if(strlen($password) < $pwd_rule_min || strlen($password) > $pwd_rule_max){
			if($pwd_rule_min === $pwd_rule_max){
				$rule = sprintf( __( "%s characters long", 'usces' ), $pwd_rule_min );
			}else{
				$rule = sprintf( __( '%1$s characters and no more than %2$s characters', 'usces' ), $pwd_rule_min, $pwd_rule_max );
			}
			$ret .= sprintf( __( "Password must be at least %s.", 'usces' ), $rule ) . "<br />";
		}
		if ( ! empty( $system_option['member_pass_rule_upercase'] ) && ! preg_match( '@[A-Z]@', $password ) ) {
			$ret .= __( "Password must contain at least one upper-case alphabetics character.", 'usces' ) . "<br />";
		}
		if ( ! empty( $system_option['member_pass_rule_lowercase'] ) && ! preg_match( '@[a-z]@', $password ) ) {
			$ret .= __( "Password must contain at least one lower-case alphabetics character.", 'usces' ) . "<br />";
		}
		if ( ! empty( $system_option['member_pass_rule_digit'] ) && ! preg_match( '@[0-9]@', $password ) ) {
			$ret .= __( "Password must contain at least one numeric character.", 'usces' ) . "<br />";
		}
		if ( ! empty( $system_option['member_pass_rule_symbol'] ) && ! preg_match( '@[\W]@', $password ) ) {
			$ret .= __( "Password must contain at least one symbolic character.", 'usces' ) . "<br />";
		}

		return $ret;
	}

	function member_check() {
		do_action( 'usces_action_before_member_check' );

		$mes = '';
		$usces_member_old = $_SESSION['usces_member'];
		foreach ( $_POST['member'] as $key => $vlue ) {
			if( 'password1' !== $key && 'password2' !== $key ){
				$_SESSION['usces_member'][$key] = trim($vlue);
			}
		}
		if( $_POST['member_regmode'] == 'newmemberform' || ($_POST['member_regmode'] === 'editmemberform' && !( WCUtils::is_blank($_POST['member']['password1']) && WCUtils::is_blank($_POST['member']['password2']))) ){
			$mes = $this->get_pwd_errors($_POST['member']['password1']);
		}

		if ( $_POST['member_regmode'] == 'editmemberform' ) {
			if ( trim($_POST['member']['password1']) != trim($_POST['member']['password2']) ) {
				$mes .= __('Password confirm does not match.', 'usces') . "<br />";
			}
			if ( !is_email($_POST['member']['mailaddress1']) || WCUtils::is_blank($_POST['member']['mailaddress1']) ) {
				$mes .= __('e-mail address is not correct', 'usces') . "<br />";
			} else {
				$this->get_current_member();
				$mem_id = $this->current_member['id'];
				$id = $this->check_member_email( $_POST['member']['mailaddress1'] );
				if( !empty( $id ) && $id != $mem_id ) {
					$mes .= __( 'This e-mail address can not be registered.', 'usces' ) . "<br />";
				}
			}
		} else if ( $_POST['member_regmode'] == 'newmemberform' ){
			if ( trim($_POST['member']['password1']) != trim($_POST['member']['password2']) ) {
				$mes .= __('Password confirm does not match.', 'usces') . "<br />";
			}
			if ( !is_email($_POST['member']['mailaddress1']) || WCUtils::is_blank($_POST['member']['mailaddress1']) || WCUtils::is_blank($_POST['member']['mailaddress2']) || trim($_POST['member']['mailaddress1']) != trim($_POST['member']['mailaddress2']) ) {
				$mes .= __('e-mail address is not correct', 'usces') . "<br />";
			} else {
				$id = $this->check_member_email( $_POST['member']['mailaddress1'] );
				if( !empty( $id ) ) {
					$mes .= __( 'This e-mail address can not be registered.', 'usces' ) . "<br />";
				}
			}
		}else{
			$mes .= __('ERROR: I was not able to complete collective operation', 'usces') . "<br />";
		}
		if ( WCUtils::is_blank($_POST["member"]["name1"]) ){
			$mes .= __('Name is not correct', 'usces') . "<br />";
		}
		$zip_check = false;
		$addressform = $this->options['system']['addressform'];
		$applyform = usces_get_apply_addressform($addressform);
		if ( $applyform === "JP") {
			if ( isset( $_POST["member"]["country"] ) ) {
				if ( $_POST["member"]["country"] === "JP") {
							$zip_check = true;
				}
			} else {
				$base = usces_get_base_country();
					if ( $base === "JP") {
						$zip_check = true;
					}
			}
		}
		$zip_check = apply_filters( 'usces_filter_zipcode_check', $zip_check );
		if (  WCUtils::is_blank($_POST["member"]["zipcode"]) ) {
			if ( usces_is_required_field('zipcode') ) {
				$mes .= __('postal code is not correct', 'usces') . "<br />";
			}
		} else {
			if ( $zip_check ) {
				if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST["member"]["zipcode"] ) ) {
					$_SESSION['usces_member']["zipcode"] = usces_convert_zipcode($_POST["member"]["zipcode"]);
				}
				if (!preg_match('/^(([0-9]{3}-[0-9]{4})|([0-9]{7}))$/', $_SESSION['usces_member']["zipcode"])) {
					$mes .= __('postal code is not correct', 'usces') . "<br />";
				}
			}
		}
		if ( $_POST["member"]["pref"] == __('-- Select --', 'usces_dual') && usces_is_required_field('states') ){
			$mes .= __('enter the prefecture', 'usces') . "<br />";
		}
		if ( WCUtils::is_blank($_POST["member"]["address1"]) && usces_is_required_field('address1') ){
			$mes .= __('enter the city name', 'usces') . "<br />";
		}
		if ( WCUtils::is_blank($_POST["member"]["address2"]) && usces_is_required_field('address2') ){
			$mes .= __('enter house numbers', 'usces') . "<br />";
		}
		if ( WCUtils::is_blank($_POST["member"]["tel"]) && usces_is_required_field('tel') ){
			$mes .= __('enter phone numbers', 'usces') . "<br />";
		}
		if( !WCUtils::is_blank($_POST['member']["tel"]) && preg_match("/[^\d\-+]/", trim($_POST["member"]["tel"])) && usces_is_required_field('tel') ){
			$mes .= __('Please input a phone number with a half size number.', 'usces') . "<br />";
		}

		if( $_POST['member_regmode'] !== 'editmemberform' && isset( $this->options['agree_member']) && 'activate' === $this->options['agree_member'] ){
			if( !isset($_POST['agree_member_check']) ){
				$mes .= __('Please accept the membership agreement.', 'usces') . "<br />";
			}
		}

		$mes = apply_filters('usces_filter_member_check', $mes);

		if ( $_POST['member_regmode'] == 'editmemberform' && '' != $mes ) {
			$_SESSION['usces_member'] = $usces_member_old;
		}

		return $mes;
	}

	function member_check_fromcart() {

		do_action( 'usces_action_before_member_check_fromcart' );

		$mes = $this->get_pwd_errors($_POST['customer']['password1']);

        if (trim($_POST['customer']['password1']) != trim($_POST['customer']['password2']) ){
            $mes .= __('Password confirm does not match.', 'usces') . "<br />";
        }
		if ( !is_email($_POST['customer']['mailaddress1']) || WCUtils::is_blank($_POST['customer']['mailaddress1']) || WCUtils::is_blank($_POST['customer']['mailaddress2']) || trim($_POST['customer']['mailaddress1']) != trim($_POST['customer']['mailaddress2']) ){
			$mes .= __('e-mail address is not correct', 'usces') . "<br />";
		}
		if ( WCUtils::is_blank($_POST["customer"]["name1"]) ){
			$mes .= __('Name is not correct', 'usces') . "<br />";
		}
		$zip_check = false;
		$addressform = $this->options['system']['addressform'];
		$applyform = usces_get_apply_addressform($addressform);
		if ( $applyform === "JP") {
			if ( isset( $_POST["customer"]["country"] ) ) {
				if ( $_POST["customer"]["country"] === "JP") {
					$zip_check = true;
				}
			} else {
				$base = usces_get_base_country();
				if ( $base === "JP") {
					$zip_check = true;
				}
			}
		}
		$zip_check = apply_filters( 'usces_filter_zipcode_check', $zip_check );
		if (  WCUtils::is_blank($_POST["customer"]["zipcode"]) ) {
			if ( usces_is_required_field('zipcode') ) {
				$mes .= __('postal code is not correct', 'usces') . "<br />";
			}
		} else {
			if ( $zip_check ) {
				if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST["customer"]["zipcode"] ) ) {
					$_SESSION['usces_entry']['customer']['zipcode'] = usces_convert_zipcode($_POST["customer"]["zipcode"]);
				}
				if (!preg_match('/^(([0-9]{3}-[0-9]{4})|([0-9]{7}))$/', $_SESSION['usces_entry']['customer']['zipcode'])) {
					$mes .= __('postal code is not correct', 'usces') . "<br />";
				}
			}
		}
		if ( ($_POST["customer"]["pref"] == __('-- Select --', 'usces') || $_POST["customer"]["pref"] == '-- Select --') && usces_is_required_field('states') ){
			$mes .= __('enter the prefecture', 'usces') . "<br />";
		}
		if ( WCUtils::is_blank($_POST["customer"]["address1"]) && usces_is_required_field('address1') ){
			$mes .= __('enter the city name', 'usces') . "<br />";
		}
		if ( WCUtils::is_blank($_POST["customer"]["address2"]) && usces_is_required_field('address2') ){
			$mes .= __('enter house numbers', 'usces') . "<br />";
		}
		if ( WCUtils::is_blank($_POST["customer"]["tel"]) && usces_is_required_field('tel') ){
			$mes .= __('enter phone numbers', 'usces') . "<br />";
		}
		if( !WCUtils::is_blank($_POST['customer']["tel"]) && preg_match("/[^\d\-+]/", trim($_POST["customer"]["tel"])) && usces_is_required_field('tel') ){
			$mes .= __('Please input a phone number with a half size number.', 'usces') . "<br />";
		}

		if( isset( $this->options['agree_member']) && 'activate' === $this->options['agree_member'] ){
			if( !isset($_POST['agree_member_check']) ){
				$mes .= __('Please accept the membership agreement.', 'usces') . "<br />";
			}
		}

		$mes = apply_filters('usces_filter_member_check_fromcart', $mes);

		return $mes;
	}

	function admin_member_check() {
		global $wpdb;
		$mes = '';
		if ( !is_email( trim($_POST['member']["email"]) ) ){
			$mes .= __('e-mail address is not correct', 'usces') . "<br />";
		}else{
			$member_table = usces_get_tablename( 'usces_member' );
			$mem_email = $wpdb->get_var( $wpdb->prepare("SELECT mem_email FROM $member_table WHERE ID = %d LIMIT 1", trim($_POST['member_id'])) );
			if( trim($_POST['member']["email"]) != $mem_email ){
				$mem_ID = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $member_table WHERE mem_email = %s LIMIT 1", trim($_POST['member']["email"])) );
				if( !empty($mem_ID) )
					$mes .= __('This e-mail address has been already registered.', 'usces') . "<br />";
			}
		}
		if ( WCUtils::is_blank($_POST['member']["name1"]) )
			$mes .= __('Name is not correct', 'usces') . "<br />";
		if( !WCUtils::is_blank($_POST['member']["tel"]) && preg_match("/[^\d\-+]/", trim($_POST["member"]["tel"])) )
			$mes .= __('Please input a phone number with a half size number.', 'usces') . "<br />";

		$mes = apply_filters('usces_filter_admin_member_check', $mes);

		return $mes;
	}

	function customer_check() {

		do_action( 'usces_action_before_customer_check' );

		$mes = '';
		if ( !is_email($_POST['customer']['mailaddress1']) || WCUtils::is_blank($_POST['customer']['mailaddress1']) || WCUtils::is_blank($_POST['customer']['mailaddress2']) || trim($_POST['customer']['mailaddress1']) != trim($_POST['customer']['mailaddress2']) ){
			$mes .= __('e-mail address is not correct', 'usces') . "<br />";
		}
		if ( WCUtils::is_blank($_POST["customer"]["name1"]) ){
			$mes .= __('Name is not correct', 'usces') . "<br />";
		}
		$zip_check = false;
		$addressform = $this->options['system']['addressform'];
		$applyform = usces_get_apply_addressform($addressform);
		if ( $applyform === "JP") {
			if ( isset( $_POST["customer"]["country"] ) ) {
				if ( $_POST["customer"]["country"] === "JP") {
					$zip_check = true;
				}
			} else {
				$base = usces_get_base_country();
				if ( $base === "JP") {
					$zip_check = true;
				}
			}
		}
		$zip_check = apply_filters( 'usces_filter_zipcode_check', $zip_check );
		if (  WCUtils::is_blank($_POST["customer"]["zipcode"]) ) {
			if ( usces_is_required_field('zipcode') ) {
				$mes .= __('postal code is not correct', 'usces') . "<br />";
			}
		} else {
			if ( $zip_check ) {
				if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST["customer"]["zipcode"] ) ) {
					$_SESSION['usces_entry']['customer']['zipcode'] = usces_convert_zipcode($_POST["customer"]["zipcode"]);
				}
				if (!preg_match('/^(([0-9]{3}-[0-9]{4})|([0-9]{7}))$/', $_SESSION['usces_entry']['customer']['zipcode'])) {
					$mes .= __('postal code is not correct', 'usces') . "<br />";
				}
			}
		}
		if ( ($_POST["customer"]["pref"] == __('-- Select --', 'usces') || $_POST["customer"]["pref"] == '-- Select --') && usces_is_required_field('states') ){
			$mes .= __('enter the prefecture', 'usces') . "<br />";
		}
		if ( WCUtils::is_blank($_POST["customer"]["address1"]) && usces_is_required_field('address1') ){
			$mes .= __('enter the city name', 'usces') . "<br />";
		}
		if ( WCUtils::is_blank($_POST["customer"]["address2"]) && usces_is_required_field('address2') ){
			$mes .= __('enter house numbers', 'usces') . "<br />";
		}
		if ( WCUtils::is_blank($_POST["customer"]["tel"]) && usces_is_required_field('tel') ){
			$mes .= __('enter phone numbers', 'usces') . "<br />";
		}
		if( !WCUtils::is_blank($_POST['customer']["tel"]) && preg_match("/[^\d\-+]/", trim($_POST["customer"]["tel"])) && usces_is_required_field('tel') ){
			$mes .= __('Please input a phone number with a half size number.', 'usces') . "<br />";
		}

		$mes = apply_filters('usces_filter_customer_check', $mes);

		return $mes;
	}

	function admin_new_member_check() {
		global $wpdb;

		$mes = $this->get_pwd_errors($_POST['member']['password']);

		if ( !is_email( trim($_POST['member']["email"]) ) ){
			$mes .= __('e-mail address is not correct', 'usces') . "<br />";
		}else{
			$member_table = usces_get_tablename( 'usces_member' );
			$mem_email = $wpdb->get_var( $wpdb->prepare("SELECT mem_email FROM $member_table WHERE ID = %d LIMIT 1", trim($_POST['member_id'])) );
			if( trim($_POST['member']["email"]) != $mem_email ){
				$mem_ID = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $member_table WHERE mem_email = %s LIMIT 1", trim($_POST['member']["email"])) );
				if( !empty($mem_ID) )
					$mes .= __('This e-mail address has been already registered.', 'usces') . "<br />";
			}
		}

		if ( WCUtils::is_blank($_POST['member']["name1"]) )
			$mes .= __('Name is not correct', 'usces') . "<br />";
		if( !WCUtils::is_blank($_POST['member']["tel"]) && preg_match("/[^\d\-+]/", trim($_POST["member"]["tel"])) )
			$mes .= __('Please input a phone number with a half size number.', 'usces') . "<br />";

		$mes = apply_filters('usces_filter_admin_member_check', $mes);

		return $mes;
	}

	function delivery_check() {
		global $usces_settings;

		do_action( 'usces_action_before_delivery_check' );

		$mes = '';
		if ( isset($_POST['delivery']['delivery_flag']) && 1 === (int) $_POST['delivery']['delivery_flag'] ) {
			if ( WCUtils::is_blank($_POST["delivery"]["name1"]) ){
				$mes .= __('Name is not correct', 'usces') . "<br />";
			}
			$zip_check = false;
			$addressform = $this->options['system']['addressform'];
			$applyform = usces_get_apply_addressform($addressform);
			if ( $applyform === "JP") {
				if ( isset( $_POST["delivery"]["country"] ) ) {
					if ( $_POST["delivery"]["country"] === "JP") {
						$zip_check = true;
					}
				} else {
					$base = usces_get_base_country();
					if ( $base === "JP") {
						$zip_check = true;
					}
				}
			}
			$zip_check = apply_filters( 'usces_filter_zipcode_check', $zip_check );
			if (  WCUtils::is_blank($_POST["delivery"]["zipcode"]) ) {
				if ( usces_is_required_field('zipcode') ) {
					$mes .= __('postal code is not correct', 'usces') . "<br />";
				}
			} else {
				if ( $zip_check ) {
					if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST["delivery"]["zipcode"] ) ) {
						$_SESSION['usces_entry']['delivery']['zipcode'] = usces_convert_zipcode($_POST["delivery"]["zipcode"]);
					}
					if (!preg_match('/^(([0-9]{3}-[0-9]{4})|([0-9]{7}))$/', $_SESSION['usces_entry']['delivery']['zipcode'])) {
						$mes .= __('postal code is not correct', 'usces') . "<br />";
					}
				}
			}
			if ( ($_POST["delivery"]["pref"] == __('-- Select --', 'usces') || $_POST["delivery"]["pref"] == '-- Select --') && usces_is_required_field('states') ){
				$mes .= __('enter the prefecture', 'usces') . "<br />";
			}
			if ( WCUtils::is_blank($_POST["delivery"]["address1"]) && usces_is_required_field('address1') ){
				$mes .= __('enter the city name', 'usces') . "<br />";
			}
			if ( WCUtils::is_blank($_POST["delivery"]["address2"]) && usces_is_required_field('address2') ){
				$mes .= __('enter house numbers', 'usces') . "<br />";
			}
			if ( WCUtils::is_blank($_POST["delivery"]["tel"]) && usces_is_required_field('tel') ){
				$mes .= __('enter phone numbers', 'usces') . "<br />";
			}
			if ( ! WCUtils::is_blank( $_POST['delivery']['tel'] ) && preg_match( "/[^\d\-+]/", trim( $_POST['delivery']['tel'] ) ) && usces_is_required_field( 'tel' ) ) {
				$mes .= __( 'Please input a phone number with a half size number.', 'usces' ) . '<br />';
			}
			$post_offer = filter_input( INPUT_POST, 'offer', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			if ( ! empty( $post_offer['delivery_date'] ) && usces_is_date( $post_offer['delivery_date'] ) && isset( $post_offer['delivery_method'] ) ) {
				$post_delivery     = filter_input( INPUT_POST, 'delivery', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
				$deli_method_index = $this->get_delivery_method_index( $post_offer['delivery_method'] );
				$delivery_days_id  = isset( $this->options['delivery_method'][ $deli_method_index ]['days'] ) ? $this->options['delivery_method'][ $deli_method_index ]['days'] : -1;
				if ( 0 <= $delivery_days_id ) {
					$delivery_days_list = $this->options['delivery_days'];
					$delivery_days      = 0;
					foreach ( (array) $delivery_days_list as $delivery_days_value ) {
						if ( (int) $delivery_days_value['id'] === (int) $delivery_days_id ) {
							$delivery_days = (int) $delivery_days_value[ $applyform ][ $post_delivery['pref'] ];
						}
					}
					$sendout       = usces_get_send_out_date();
					$sendout_date  = implode( '-', $sendout['sendout_date'] );
					$arrival_date  = new DateTime( $sendout_date );
					$arrival_date->modify( '+' . $delivery_days . ' days' );
					$offer_deli_date = new DateTime( $post_offer['delivery_date'] );
					if ( $offer_deli_date < $arrival_date ) {
						$mes .= __( 'Please re-specify the desired arrival date.', 'usces' ) . '<br />';
					}
				} else {
					$mes .= __( 'Please re-specify the desired arrival date.', 'usces' ) . '<br />';
				}
			}
		}
		if ( !isset($_POST['offer']['delivery_method']) || (empty($_POST['offer']['delivery_method']) && !WCUtils::is_zero($_POST['offer']['delivery_method'])) ) {
			$mes .= __('chose one from delivery method.', 'usces') . "<br />";
		} else {
			$d_method_index = $this->get_delivery_method_index((int)$_POST['offer']['delivery_method']);
			if( 0 > $d_method_index ) {
				$mes .= __('chose one from delivery method.', 'usces') . "<br />";
			} else {
				$country = $_SESSION['usces_entry']["delivery"]["country"];
				$current_locate = get_locale();
				$WPLANG = get_option( 'WPLANG', $current_locate );
				$local_country = $usces_settings['lungage2country'][$WPLANG];

				if($country == $local_country) {
					if($this->options['delivery_method'][$d_method_index]['intl'] == 1) {
						$mes .= __('Delivery method is incorrect. Can not specify an international flight.', 'usces') . "<br />";
					}
				} else {
					if( WCUtils::is_zero($this->options['delivery_method'][$d_method_index]['intl']) ) {
						$mes .= __('Delivery method is incorrect. Specify the international flights.', 'usces') . "<br />";
					}
				}
			}
		}
		if ( !isset($_POST['offer']['payment_name']) ){
			$mes .= __('chose one from payment options.', 'usces') . "<br />";
		}else{
			$payments = $this->getPayments($_POST['offer']['payment_name']);
			if('COD' == $payments['settlement']){
				$total_items_price = $this->get_total_price();
				$usces_entries = $this->cart->get_entry();
				$materials = array(
					'total_items_price' => $usces_entries['order']['total_items_price'],
					'discount' => ( isset($usces_entries['order']['discount']) ) ? $usces_entries['order']['discount'] : 0,
					'shipping_charge' => $usces_entries['order']['shipping_charge'],
					'cod_fee' => $usces_entries['order']['cod_fee'],
					'use_point' => ( isset($usces_entries['order']['use_point']) ) ? $usces_entries['order']['use_point'] : 0,
				);
				$tax = $this->getTax( $total_items_price, $materials );
				$total_items_price = $total_items_price + $tax;
				$cod_limit_amount = ( isset($this->options['cod_limit_amount']) && 0 < (int)$this->options['cod_limit_amount'] ) ? $this->options['cod_limit_amount'] : 0;
				if( 0 < $cod_limit_amount && $total_items_price > $cod_limit_amount )
					$mes .= sprintf(__('A total products amount of money surpasses the upper limit(%s) that I can purchase in C.O.D.', 'usces'), usces_crform($this->options['cod_limit_amount'], true, false, 'return')) . "<br />";
			}
		}
		if( isset($d_method_index) && isset($payments) ) {
			if($this->options['delivery_method'][$d_method_index]['nocod'] == 1) {
				if('COD' == $payments['settlement'])
					$mes .= __('COD is not available.', 'usces') . "<br />";
			}
		}
		$mes = apply_filters('usces_filter_delivery_check', $mes);
		return $mes;
	}

	function point_check( $entries ) {
		$member = $this->get_member();
		$this->set_cart_fees( $member, $entries );

		$mes = '';
		if( isset($_POST['offer']["usedpoint"]) ) {
			if ( WCUtils::is_blank($_POST['offer']["usedpoint"]) || !preg_match("/^[0-9]+$/", $_POST['offer']["usedpoint"]) || (int)$_POST['offer']["usedpoint"] < 0 ) {
				$mes .= __('Invalid value. Please enter in the numbers.', 'usces') . "<br />";

			} else {
				$payments = $this->getPayments( $entries['order']['payment_name'] );
				$usedpoint = (int)trim($_POST['offer']["usedpoint"]);
				if( $usedpoint > $member['point'] ) {
					$mes .= __('You have exceeded the maximum available.', 'usces').' '.__('Max','usces').' '.$member['point'].' '.__('points','usces')."<br />";

				} elseif( 'acting_paypal_ec' == $payments['settlement'] ) {
					$target_full_price = $entries['order']['total_items_price'] + $entries['order']['discount'] + $entries['order']['shipping_charge'] + $entries['order']['cod_fee'] + $entries['order']['tax'];
					$target_item_price = $entries['order']['total_items_price'] + $entries['order']['discount'];
					if( $this->options['point_coverage'] == 1 && $usedpoint >= $target_full_price ) {
						$target_item_price = $target_full_price;
					} elseif( $this->options['tax_target'] == 'products' ) {
						$target_item_price += $entries['order']['tax'];
					}
					if( $usedpoint > $target_item_price ) {
						$mes .= __("In the case of settlement method you choose, the upper limit of the point you'll find that will change. If you became a settlement error, please reduce the point that you want to use.", 'usces')."<br />";
					}

				} else {
					if( $this->options['point_coverage'] == 1 ) {
						$target_full_price = $entries['order']['total_items_price'] + $entries['order']['discount'] + $entries['order']['shipping_charge'] + $entries['order']['cod_fee'] + $entries['order']['tax'];
						if( $usedpoint > $target_full_price ) {
							$mes .= __('You have exceeded the maximum available.', 'usces').' '.__('Max','usces').' '.$target_full_price.' '.__('points','usces')."<br />";
						}
					} else {
						$target_item_price = $entries['order']['total_items_price'] + $entries['order']['discount'];
						if( $this->options['tax_target'] == 'products' ) $target_item_price += $entries['order']['tax'];
						if( $usedpoint > $target_item_price ) {
							$mes .= __('You have exceeded the maximum available.', 'usces').' '.__('Max','usces').' '.$target_item_price.' '.__('points','usces')."<br />";
						}
					}
				}
				$mes = apply_filters( 'usces_filter_point_check', $mes );
				if( '' != $mes ) {
					$_POST['offer']["usedpoint"] = 0;
					$array = array(
						'usedpoint' => 0
					);
					$this->cart->set_order_entry( $array );
				}
			}
		}
		$mes = apply_filters('usces_filter_point_check_last', $mes);
		return $mes;
	}

	function lostpass_mailaddcheck() {
		$mes = '';
		if ( !is_email($_POST['loginmail']) || WCUtils::is_blank($_POST['loginmail']) ) {
			$mes .= __('e-mail address is not correct', 'usces') . "<br />";
		}elseif( !$this->is_member($_POST['loginmail']) ){
			$mes .= __('It is the e-mail address that there is not.', 'usces') . "<br />";
		}

		return $mes;
	}

	function changepass_check() {
		$mes = $this->get_pwd_errors($_POST['loginpass1']);

        if (trim($_POST['loginpass1']) != trim($_POST['loginpass2']) ){
            $mes .= __('Password confirm does not match.', 'usces') . "<br />";
        }

		return $mes;
	}

	function get_page() {
		return $this->page;
	}

	function check_display_mode() {
		$options = get_option('usces');
		if( isset($options['display_mode']) && $options['display_mode'] == 'Maintenancemode' ) return;

		$start['hour'] = empty($options['campaign_schedule']['start']['hour']) ? 0 : $options['campaign_schedule']['start']['hour'];
		$start['min'] = empty($options['campaign_schedule']['start']['min']) ? 0 : $options['campaign_schedule']['start']['min'];
		$start['month'] = empty($options['campaign_schedule']['start']['month']) ? 0 : $options['campaign_schedule']['start']['month'];
		$start['day'] = empty($options['campaign_schedule']['start']['day']) ? 0 : $options['campaign_schedule']['start']['day'];
		$start['year'] = empty($options['campaign_schedule']['start']['year']) ? 0 : $options['campaign_schedule']['start']['year'];
		$end['hour'] = empty($options['campaign_schedule']['end']['hour']) ? 0 : $options['campaign_schedule']['end']['hour'];
		$end['min'] = empty($options['campaign_schedule']['end']['min']) ? 0 : $options['campaign_schedule']['end']['min'];
		$end['month'] = empty($options['campaign_schedule']['end']['month']) ? 0 : $options['campaign_schedule']['end']['month'];
		$end['day'] = empty($options['campaign_schedule']['end']['day']) ? 0 : $options['campaign_schedule']['end']['day'];
		$end['year'] = empty($options['campaign_schedule']['end']['year']) ? 0 : $options['campaign_schedule']['end']['year'];
		$starttime = mktime($start['hour'], $start['min'], 0, $start['month'], $start['day'], $start['year']);
		$endtime = mktime($end['hour'], $end['min'], 0, $end['month'], $end['day'], $end['year']);
		$current_time = current_time('timestamp');

		if( ($current_time >= $starttime) && ($current_time <= $endtime) )
			$options['display_mode'] = 'Promotionsale';
		else
			$options['display_mode'] = 'Usualsale';

		update_option('usces', $options);

	}

	function update_business_days() {
		$options = get_option('usces');
		$datetimestr = get_date_from_gmt(gmdate('Y-m-d H:i:s', time()));
		$dhour = (int)substr($datetimestr, 11, 2);
		$dminute = (int)substr($datetimestr, 14, 2);
		$dsecond = (int)substr($datetimestr, 17, 2);
		$dmonth = (int)substr($datetimestr, 5, 2);
		$dday = (int)substr($datetimestr, 8, 2);
		$dyear = (int)substr($datetimestr, 0, 4);
		$dtimestamp = mktime($dhour, $dminute, $dsecond, $dmonth, $dday, $dyear);
		$datenow = getdate($dtimestamp);
		list($year, $mon, $mday) = getBeforeMonth($datenow['year'], $datenow['mon'], 1, 1);

		if(isset($options['business_days'][$year][$mon][1]))
			unset($options['business_days'][$year][$mon]);

		for($i=0; $i<12; $i++){
			list($year, $mon, $mday) = getAfterMonth($datenow['year'], $datenow['mon'], 1, $i);
			$last = getLastDay($year, $mon);
			for($j=1; $j<=$last; $j++){
				if(!isset($options['business_days'][$year][$mon][$j]))
					$options['business_days'][$year][$mon][$j] = 1;
			}
		}
		update_option('usces', $options);
		$this->options = get_option('usces');
		$_SESSION['usces_checked_business_days'] = '';
	}

	function display_cart() {
		if($this->cart->num_row() > 0) {
			include (USCES_PLUGIN_DIR . '/includes/cart_table.php');
		} else {
			echo "<div class='no_cart'>" . __('There are no items in your cart.', 'usces') . "</div>\n";
		}
	}

	function display_cart_confirm() {
		if($this->cart->num_row() > 0) {
			include (USCES_PLUGIN_DIR . '/includes/cart_confirm.php');
		} else {
			echo "<div class='no_cart'>" . __('There are no items in your cart.', 'usces') . "</div>\n";
		}
	}

	function set_initial() {

		$this->set_default_page();
		$this->set_default_categories();
		$this->create_table();
		$this->update_table();
		$rets07 = usces_upgrade_07();
		if($rets07){
			$rets11 = usces_upgrade_11();
		}
		if($rets11){
			$rets14 = usces_upgrade_14();
		}
		if($rets14){
			$rets141 = usces_upgrade_141();
		}
		if($rets141){
			$rets143 = usces_upgrade_143();
		}
		$this->update_options();
        usces_schedule_event();
        do_action('usces_on_plugin_activate');
	}

	function deactivate() {
		wp_clear_scheduled_hook('wc_cron');
		wp_clear_scheduled_hook('wc_cron_w');
		usces_wcsite_deactivate();
	}

	function create_table() {
		global $wpdb;

		if ( ! empty($wpdb->charset) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty($wpdb->collate) )
			$charset_collate .= " COLLATE $wpdb->collate";

		$access_table = $wpdb->prefix . "usces_access";
		$member_table = $wpdb->prefix . "usces_member";
		$member_meta_table = $wpdb->prefix . "usces_member_meta";
		$order_table = $wpdb->prefix . "usces_order";
		$order_meta_table = $wpdb->prefix . "usces_order_meta";
		$ordercart_table = $wpdb->prefix . "usces_ordercart";
		$ordercart_meta_table = $wpdb->prefix . "usces_ordercart_meta";
		$log_table = $wpdb->prefix . "usces_log";
		$acting_log_table = $wpdb->prefix . "usces_acting_log";
		$item_table = $wpdb->prefix . "usces_item";
		$sku_table = $wpdb->prefix . "usces_skus";
		$opt_table = $wpdb->prefix . "usces_opts";
		$admin_log_table = $wpdb->prefix . "usces_admin_log";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		if($wpdb->get_var("show tables like '$access_table'") != $access_table) {

			$sql = "CREATE TABLE " . $access_table . " (
				ID BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT,
				acc_key VARCHAR( 50 ) NOT NULL ,
				acc_type VARCHAR( 50 ) NULL ,
				acc_value LONGTEXT NULL ,
				acc_date DATE NOT NULL DEFAULT '0000-00-00',
				acc_num1 INT( 11 ) NOT NULL DEFAULT 0,
				acc_num2 INT( 11 ) NOT NULL DEFAULT 0,
				acc_str1 VARCHAR( 200 ) NULL ,
				acc_str2 VARCHAR( 200 ) NULL ,
				PRIMARY KEY (`ID`),
				KEY acc_key ( acc_key ),  
				KEY acc_type ( acc_type ),  
				KEY acc_date ( acc_date ), 
				KEY acc_num1 ( acc_num1 ), 
				KEY acc_num2 ( acc_num2 )  
				) AUTO_INCREMENT=0 $charset_collate;";

			dbDelta($sql);
			add_option("usces_db_access", USCES_DB_ACCESS);
		}
		if($wpdb->get_var("show tables like '$member_table'") != $member_table) {

			$sql = "CREATE TABLE " . $member_table . " (
				ID BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT,
				mem_email VARCHAR( 100 ) NOT NULL ,
				mem_pass VARCHAR( 64 ) NOT NULL ,
				mem_status INT( 11 ) NOT NULL DEFAULT '0',
				mem_cookie VARCHAR( 13 ) NULL ,
				mem_point INT( 11 ) NOT NULL DEFAULT '0',
				mem_name1 VARCHAR( 100 ) NOT NULL ,
				mem_name2 VARCHAR( 100 ) NULL ,
				mem_name3 VARCHAR( 100 ) NULL ,
				mem_name4 VARCHAR( 100 ) NULL ,
				mem_zip VARCHAR( 50 ) NULL ,
				mem_pref VARCHAR( 100 ) NOT NULL ,
				mem_address1 VARCHAR( 100 ) NOT NULL ,
				mem_address2 VARCHAR( 100 ) NULL ,
				mem_address3 VARCHAR( 100 ) NULL ,
				mem_tel VARCHAR( 100 ) NOT NULL ,
				mem_fax VARCHAR( 100 ) NULL ,
				mem_delivery_flag TINYINT ( 1 ) NULL ,
				mem_delivery LONGTEXT,
				mem_registered DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				mem_nicename VARCHAR( 50 ) NULL ,
				PRIMARY KEY (`ID`),
				KEY mem_email ( mem_email ) ,  
				KEY mem_pass ( mem_pass )  
				) AUTO_INCREMENT=1000 $charset_collate;";

			dbDelta($sql);
			add_option("usces_db_member", USCES_DB_MEMBER);
		}
		if($wpdb->get_var("show tables like '$member_meta_table'") != $member_meta_table) {

			$sql = "CREATE TABLE " . $member_meta_table . " (
				mmeta_id bigint(20) NOT NULL auto_increment,
				member_id bigint(20) NOT NULL default '0',
				meta_key varchar(255) default NULL,
				meta_value longtext,
				PRIMARY KEY  (mmeta_id),
				KEY order_id (member_id),
				KEY meta_key (meta_key(191))
				) $charset_collate;";

			dbDelta($sql);
			add_option("usces_db_member_meta", USCES_DB_MEMBER_META);
		}
		if($wpdb->get_var("show tables like '$order_table'") != $order_table) {

			$sql = "CREATE TABLE " . $order_table . " (
				ID BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT,
				mem_id BIGINT( 20 ) UNSIGNED NULL ,
				order_email VARCHAR( 100 ) NOT NULL ,
				order_name1 VARCHAR( 100 ) NOT NULL ,
				order_name2 VARCHAR( 100 ) NULL ,
				order_name3 VARCHAR( 100 ) NULL ,
				order_name4 VARCHAR( 100 ) NULL ,
				order_zip VARCHAR( 50 ) NULL ,
				order_pref VARCHAR( 100 ) NOT NULL ,
				order_address1 VARCHAR( 100 ) NOT NULL ,
				order_address2 VARCHAR( 100 ) NULL ,
				order_address3 VARCHAR( 100 ) NULL ,
				order_tel VARCHAR( 100 ) NOT NULL ,
				order_fax VARCHAR( 100 ) NULL ,
				order_delivery LONGTEXT,
				order_cart LONGTEXT,
				order_note TEXT,
				order_delivery_time VARCHAR( 100 ) NOT NULL ,
				order_payment_name VARCHAR( 100 ) NOT NULL ,
				order_condition TEXT,
				order_item_total_price DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
				order_getpoint INT( 10 ) NOT NULL DEFAULT '0',
				order_usedpoint INT( 10 ) NOT NULL DEFAULT '0',
				order_discount DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
				order_shipping_charge DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
				order_cod_fee DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
				order_tax DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
				order_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				order_modified VARCHAR( 20 ) NULL ,
				order_status VARCHAR( 255 ) NULL ,
				order_check TEXT NULL ,
				order_delidue_date VARCHAR( 30 ) NULL ,
				order_delivery_method INT( 10 ) NOT NULL DEFAULT -1,
				order_delivery_date VARCHAR( 100 ) NULL,
				PRIMARY KEY (`ID`),
				KEY order_email ( order_email ) ,  
				KEY order_name1 ( order_name1 ) ,  
				KEY order_name2 ( order_name2 ) ,  
				KEY order_pref ( order_pref ) ,  
				KEY order_address1 ( order_address1 ) ,  
				KEY order_tel ( order_tel ) ,  
				KEY order_date ( order_date )  
				) AUTO_INCREMENT=1000 $charset_collate;";

			dbDelta($sql);
			add_option("usces_db_order", USCES_DB_ORDER);
		}
		if($wpdb->get_var("show tables like '$order_meta_table'") != $order_meta_table) {

			$sql = "CREATE TABLE " . $order_meta_table . " (
				ometa_id bigint(20) NOT NULL auto_increment,
				order_id bigint(20) NOT NULL default '0',
				meta_key varchar(255) default NULL,
				meta_value longtext,
				PRIMARY KEY  (ometa_id),
				KEY order_id (order_id),
				KEY meta_key (meta_key(191))
				) $charset_collate;";

			dbDelta($sql);
			add_option("usces_db_order_meta", USCES_DB_ORDER_META);
		}

		if($wpdb->get_var("show tables like '$ordercart_table'") != $ordercart_table) {

			$sql = "CREATE TABLE " . $ordercart_table . " (
				`cart_id` bigint( 20  )  unsigned NOT  NULL  AUTO_INCREMENT ,
				`order_id` bigint( 20  )  NOT  NULL ,
				`group_id` int( 3  )  NOT  NULL DEFAULT  '0',
				`row_index` int( 3  )  NOT  NULL ,
				`post_id` bigint( 20  )  NOT  NULL ,
				`item_code` varchar( 100  )  NOT  NULL ,
				`item_name` varchar( 250  )  NOT  NULL ,
				`cprice` decimal( 12, 0  )  DEFAULT NULL ,
				`sku_code` varchar( 100  )  NOT  NULL ,
				`sku_name` varchar( 250  )  DEFAULT NULL ,
				`price` decimal( 12, 0  )  NOT  NULL ,
				`quantity` float NOT  NULL ,
				`unit` varchar( 50  )  DEFAULT NULL ,
				`tax` decimal( 10, 0  )  DEFAULT NULL ,
				`destination_id` int( 10  )  DEFAULT NULL ,
				`cart_serial` text,
				PRIMARY  KEY (  `cart_id`  ) ,
				UNIQUE  KEY  `row` (  `row_index` ,  `destination_id` ,  `order_id`  ) ,
				KEY  `order_id` (  `order_id`  ) ,
				KEY  `post_id` (  `post_id`  ) ,
				KEY  `item_code` (  `item_code`  ) ,
				KEY  `item_name` (  `item_name`(191)  ) ,
				KEY  `sku_code` (  `sku_code`  ) ,
				KEY  `sku_name` (  `sku_name`(191)  ) 
				) AUTO_INCREMENT=1000 $charset_collate;";

			dbDelta($sql);
			add_option("usces_db_ordercart", USCES_DB_ORDERCART);
		}
		if($wpdb->get_var("show tables like '$ordercart_meta_table'") != $ordercart_meta_table) {

			$sql = "CREATE TABLE " . $ordercart_meta_table . " (
				`cartmeta_id` bigint( 20  )  NOT  NULL  AUTO_INCREMENT ,
				`cart_id` bigint( 20  )  NOT  NULL DEFAULT  '0',
				`meta_type` varchar( 100  )  NOT  NULL ,
				`meta_key` varchar( 255  )  DEFAULT NULL ,
				`meta_value` longtext,
				PRIMARY  KEY (  `cartmeta_id`  ) ,
				KEY  `cart_id` (  `cart_id`  ) ,
				KEY  `meta_key` (  `meta_key`(191)  ) 
				) $charset_collate;";

			dbDelta($sql);
			add_option("usces_db_ordercart_meta", USCES_DB_ORDERCART_META);
		}
		if($wpdb->get_var("show tables like '$log_table'") != $log_table) {

			$sql = "CREATE TABLE " . $log_table . " (
				ID BIGINT( 20 ) NOT NULL AUTO_INCREMENT,
				datetime DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				log LONGTEXT NULL,
				log_type VARCHAR( 100 ) DEFAULT NULL,
				log_key VARCHAR( 255 ) DEFAULT NULL,
				PRIMARY KEY ( ID ) ,
				KEY datetime ( datetime ),
				KEY log_type ( log_type ),
				KEY log_key ( log_key(191) )
				) $charset_collate;";

			dbDelta($sql);
			add_option("usces_db_log", USCES_DB_LOG);
		}
		if( $wpdb->get_var( "show tables like '$acting_log_table'" ) != $acting_log_table ) {
			$sql = "CREATE TABLE " . $acting_log_table . " (
				`ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
				`datetime` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				`log` LONGTEXT NULL,
				`acting` CHAR(32) DEFAULT NULL,
				`status` CHAR(32) DEFAULT NULL,
				`result` CHAR(32) DEFAULT NULL,
				`amount` DECIMAL( 10, 2 ) DEFAULT NULL,
				`order_id` BIGINT(20) NOT NULL,
				`tracking_id` CHAR(32) DEFAULT NULL,
				PRIMARY KEY (`ID`),
				KEY `datetime` (`datetime`),
				KEY `order_id` (`order_id`),
				KEY `tracking_id` (`tracking_id`),
				KEY `history_key` (`order_id`,`tracking_id`)
				) $charset_collate;";
			dbDelta( $sql );
			add_option( "usces_db_acting_log", USCES_DB_ACTING_LOG );
		}

		if ( $wpdb->get_var( "show tables like '{$item_table}'" ) !== $item_table ) {

			$sql = 'CREATE TABLE ' . $item_table . ' (
				post_id BIGINT( 20 ) UNSIGNED NOT NULL,
				itemCode VARCHAR( 200 ) NULL ,
				itemName VARCHAR( 250 ) NULL ,
				itemRestriction VARCHAR( 20 ) NULL ,
				itemPointrate VARCHAR( 20 ) NULL ,
				itemGpNum1 VARCHAR( 20 ) NULL ,
				itemGpNum2 VARCHAR( 20 ) NULL ,
				itemGpNum3 VARCHAR( 20 ) NULL ,
				itemGpDis1 VARCHAR( 20 ) NULL ,
				itemGpDis2 VARCHAR( 20 ) NULL ,
				itemGpDis3 VARCHAR( 20 ) NULL ,
				itemOrderAcceptable INT( 2 ) NULL ,
				itemShipping INT( 3 ) NULL ,
				itemDeliveryMethod LONGTEXT ,
				itemShippingCharge VARCHAR( 200 ) NULL ,
				itemIndividualSCharge VARCHAR( 20 ) NULL ,
				item_charging_type INT( 3 ) NULL ,
				item_division VARCHAR( 50 ) NULL ,
				dlseller_date VARCHAR( 100 ) NULL ,
				dlseller_file VARCHAR( 200 ) NULL ,
				dlseller_interval VARCHAR( 20 ) NULL ,
				dlseller_validity VARCHAR( 20 ) NULL ,
				dlseller_version VARCHAR( 100 ) NULL ,
				dlseller_author VARCHAR( 200 ) NULL ,
				dlseller_purchases VARCHAR( 20 ) NULL ,
				dlseller_downloads VARCHAR( 20 ) NULL ,
				item_chargingday VARCHAR( 20 ) NULL ,
				item_frequency VARCHAR( 20 ) NULL ,
				wcad_regular_unit VARCHAR( 20 ) NULL ,
				wcad_regular_interval INT( 10 ) NULL ,
				wcad_regular_frequency INT( 10 ) NULL ,
				select_sku_switch INT( 10 ) NULL ,
				select_sku_display INT( 10 ) NULL ,
				select_sku LONGTEXT ,
				atobarai_propriety INT( 5 ) NULL ,
				atodene_propriety INT( 5 ) NULL ,
				structuredDataSku VARCHAR( 200 ) NULL ,
				lower_limit INT( 10 ) NULL ,
				popularity INT( 10 ) NULL ,
				main_price DECIMAL( 10, 2 ) NULL ,
				itemPicts TEXT NULL ,
				itemAdvanced LONGTEXT NULL ,
				PRIMARY KEY (`post_id`),
				KEY itemCode ( itemCode ) ,  
				KEY itemName ( itemName )  
				) ' . $charset_collate . ';';

			dbDelta( $sql );
			add_option( "usces_db_item", USCES_DB_ITEM );
		}

		if ( $wpdb->get_var( "show tables like '{$sku_table}'" ) !== $sku_table ) {

			$sql = 'CREATE TABLE ' . $sku_table . ' (
				meta_id BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT,
				post_id BIGINT( 20 ) UNSIGNED NOT NULL ,
				code VARCHAR( 200 ) NULL ,
				name VARCHAR( 250 ) NULL ,
				cprice DECIMAL( 10, 2 ) NULL , 
				price DECIMAL( 10, 2 ) NULL , 
				unit VARCHAR( 100 ) NULL ,
				stocknum VARCHAR( 50 ) NULL ,
				stock INT( 5 ) NULL ,
				gp INT( 2 ) NULL ,
				taxrate VARCHAR( 20 ) NULL , 
				size VARCHAR( 20 ) NULL , 
				weight VARCHAR( 20 ) NULL , 
				pict_id VARCHAR( 20 ) NULL , 
				advance LONGTEXT ,
				paternkey VARCHAR( 100 ) NULL ,
				sort INT( 5 ) NULL ,
				PRIMARY KEY (`meta_id`),
				KEY post_id ( post_id ) ,  
				KEY code ( code ) ,  
				KEY name ( name ) ,  
				KEY price ( price ) ,  
				KEY paternkey ( paternkey )  
				) ' . $charset_collate . ';';

			dbDelta( $sql );
			add_option( "usces_db_skus", USCES_DB_SKUS );
		}

		if ( $wpdb->get_var( "show tables like '{$opt_table}'" ) !== $opt_table ) {

			$sql = 'CREATE TABLE ' . $opt_table . ' (
				meta_id BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT,
				post_id BIGINT( 20 ) UNSIGNED NOT NULL ,
				code VARCHAR( 200 ) NULL ,
				name VARCHAR( 250 ) NULL ,
				means INT( 3 ) NULL ,
				essential INT( 3 ) NULL ,
				value TEXT ,
				sort INT( 5 ) NULL ,
				PRIMARY KEY (`meta_id`),
				KEY post_id ( post_id ) ,  
				KEY code ( code ) ,  
				KEY name ( name ) 
				) ' . $charset_collate . ';';

			dbDelta( $sql );
			add_option( "usces_db_opts", USCES_DB_OPTS );
		}

		if ( $wpdb->get_var( "show tables like '{$admin_log_table}'" ) !== $admin_log_table ) {

			$sql = "CREATE TABLE {$admin_log_table} (
				`ID` int(11) NOT NULL AUTO_INCREMENT,
				`author` varchar(64) NOT NULL,
				`message` longtext NOT NULL,
				`screen` varchar(64) NOT NULL,
				`entity_id` varchar(64) DEFAULT NULL,
				`action` varchar(64) NOT NULL,
				`data` longblob DEFAULT NULL,
				`datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				 PRIMARY KEY (`ID`)
				) " . $charset_collate . ';';

			dbDelta( $sql );
			add_option( "usces_db_admin_log", USCES_DB_ADMIN_LOG );
		}

		do_action( 'usces_action_create_table' );
	}

	function update_table()
	{
		global $wpdb;
		$access_table = $wpdb->prefix . "usces_access";
		$member_table = $wpdb->prefix . "usces_member";
		$member_meta_table = $wpdb->prefix . "usces_member_meta";
		$order_table = $wpdb->prefix . "usces_order";
		$order_meta_table = $wpdb->prefix . "usces_order_meta";
		$ordercart_table = $wpdb->prefix . "usces_ordercart";
		$ordercart_meta_table = $wpdb->prefix . "usces_ordercart_meta";
		$log_table = $wpdb->prefix . "usces_log";
		$acting_log_table = $wpdb->prefix . "usces_acting_log";
		$item_table = $wpdb->prefix . "usces_item";
		$sku_table = $wpdb->prefix . "usces_skus";
		$opt_table = $wpdb->prefix . "usces_opts";
		$admin_log_table = $wpdb->prefix . "usces_admin_log";

		$access_ver = get_option( "usces_db_access" );
		$member_ver = get_option( "usces_db_member" );
		$member_meta_ver = get_option( "usces_db_member_meta" );
		$order_ver = get_option( "usces_db_order" );
		$order_meta_ver = get_option( "usces_db_order_meta" );
		$ordercart_ver = get_option( "usces_db_ordercart" );
		$ordercart_meta_ver = get_option( "usces_db_ordercart_meta" );
		$log_ver = get_option( "usces_db_log" );
		$acting_log_ver = get_option( "usces_db_acting_log" );
		$db_item = get_option( "usces_db_item" );
		$db_skus = get_option( "usces_db_skus" );
		$db_opts = get_option( "usces_db_opts" );
		$db_admin_log = get_option( "usces_db_admin_log" );

		if( $access_ver != USCES_DB_ACCESS ) {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			$sql = "CREATE TABLE " . $access_table . " (
				ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				acc_key VARCHAR(50) NOT NULL ,
				acc_type VARCHAR(50) NULL ,
				acc_value LONGTEXT NULL ,
				acc_date DATE NOT NULL DEFAULT '0000-00-00',
				acc_num1 INT(11) NOT NULL DEFAULT 0,
				acc_num2 INT(11) NOT NULL DEFAULT 0,
				acc_str1 VARCHAR(200) NULL ,
				acc_str2 VARCHAR(200) NULL ,
				PRIMARY KEY (`ID`),
				KEY acc_key (acc_key),  
				KEY acc_type (acc_type),  
				KEY acc_date (acc_date), 
				KEY acc_num1 (acc_num1), 
				KEY acc_num2 (acc_num2)  
				);";

			dbDelta($sql);
			update_option( "usces_db_access", USCES_DB_ACCESS );
		}
		if( $member_ver != USCES_DB_MEMBER ) {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			$sql = "CREATE TABLE " . $member_table . " (
				ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				mem_email VARCHAR(100) NOT NULL ,
				mem_pass VARCHAR(64) NOT NULL ,
				mem_status INT(11) NOT NULL DEFAULT '0',
				mem_cookie VARCHAR(13) NULL ,
				mem_point INT(11) NOT NULL DEFAULT '0',
				mem_name1 VARCHAR(100) NOT NULL ,
				mem_name2 VARCHAR(100) NULL ,
				mem_name3 VARCHAR(100) NULL ,
				mem_name4 VARCHAR(100) NULL ,
				mem_zip VARCHAR(50) NULL ,
				mem_pref VARCHAR(100) NOT NULL ,
				mem_address1 VARCHAR(100) NOT NULL ,
				mem_address2 VARCHAR(100) NULL ,
				mem_address3 VARCHAR(100) NULL ,
				mem_tel VARCHAR(100) NOT NULL ,
				mem_fax VARCHAR(100) NULL ,
				mem_delivery_flag TINYINT (1) NULL ,
				mem_delivery LONGTEXT,
				mem_registered DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				mem_nicename VARCHAR(50) NULL ,
				PRIMARY KEY (`ID`),
				KEY mem_email (mem_email) ,  
				KEY mem_pass (mem_pass)  
				);";

			dbDelta($sql);
			update_option( "usces_db_member", USCES_DB_MEMBER );
		}
		if( $member_meta_ver != USCES_DB_MEMBER_META ) {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			$sql = "CREATE TABLE " . $member_meta_table . " (
				mmeta_id bigint(20) NOT NULL auto_increment,
				member_id bigint(20) NOT NULL default '0',
				meta_key varchar(255) default NULL,
				meta_value longtext,
				PRIMARY KEY (`mmeta_id`),
				KEY order_id (member_id),
				KEY meta_key (meta_key(191))
				);";

			dbDelta($sql);
			update_option("usces_db_member_meta", USCES_DB_MEMBER_META);
		}
		if( $order_ver != USCES_DB_ORDER ) {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			$sql = "CREATE TABLE " . $order_table . " (
				ID bigint(20) unsigned NOT NULL auto_increment,
				mem_id BIGINT(20) UNSIGNED NULL,
				order_email VARCHAR(100) NOT NULL,
				order_name1 VARCHAR(100) NOT NULL,
				order_name2 VARCHAR(100) NULL,
				order_name3 VARCHAR(100) NULL,
				order_name4 VARCHAR(100) NULL,
				order_zip VARCHAR(50) NULL,
				order_pref VARCHAR(100) NOT NULL,
				order_address1 VARCHAR(100) NOT NULL,
				order_address2 VARCHAR(100) NULL,
				order_address3 VARCHAR(100) NULL,
				order_tel VARCHAR(100) NOT NULL,
				order_fax VARCHAR(100) NULL,
				order_delivery LONGTEXT,
				order_cart LONGTEXT,
				order_note TEXT,
				order_delivery_time VARCHAR(100) NOT NULL,
				order_payment_name VARCHAR(100) NOT NULL,
				order_condition TEXT,
				order_item_total_price DECIMAL(10,2) NOT NULL DEFAULT '0.00',
				order_getpoint INT(10) NOT NULL DEFAULT '0',
				order_usedpoint INT(10) NOT NULL DEFAULT '0',
				order_discount DECIMAL(10,2) NOT NULL DEFAULT '0.00',
				order_shipping_charge DECIMAL(10,2) NOT NULL DEFAULT '0.00',
				order_cod_fee DECIMAL(10,2) NOT NULL DEFAULT '0.00',
				order_tax DECIMAL(10,2) NOT NULL DEFAULT '0.00',
				order_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				order_modified VARCHAR(20) NULL,
				order_status VARCHAR(255) NULL,
				order_check TEXT NULL,
				order_delidue_date VARCHAR(30) NULL,
				order_delivery_method INT(10) NOT NULL DEFAULT -1,
				order_delivery_date VARCHAR(100) NULL,
				PRIMARY KEY (`ID`),
				KEY order_email (order_email),
				KEY order_name1 (order_name1),
				KEY order_name2 (order_name2),
				KEY order_pref (order_pref),
				KEY order_address1 (order_address1),
				KEY order_tel (order_tel),
				KEY order_date (order_date)
				);";

			dbDelta($sql);
			update_option("usces_db_order", USCES_DB_ORDER);
		}
		if( $order_meta_ver != USCES_DB_ORDER_META ) {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			$sql = "CREATE TABLE " . $order_meta_table . " (
				ometa_id bigint(20) NOT NULL auto_increment,
				order_id bigint(20) NOT NULL default '0',
				meta_key varchar(255) default NULL,
				meta_value longtext,
				PRIMARY KEY (`ometa_id`),
				KEY order_id (order_id),
				KEY meta_key (meta_key)
				);";

			dbDelta($sql);
			update_option("usces_db_order_meta", USCES_DB_ORDER_META);
		}
		if( $ordercart_ver != USCES_DB_ORDERCART ) {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			$sql = "CREATE TABLE " . $ordercart_table . " (
				cart_id bigint(20)  unsigned NOT  NULL  AUTO_INCREMENT ,
				order_id bigint(20)  NOT  NULL ,
				group_id int(3)  NOT  NULL DEFAULT  '0',
				row_index int(3)  NOT  NULL ,
				post_id bigint(20)  NOT  NULL ,
				item_code varchar(100)  NOT  NULL ,
				item_name varchar(250)  NOT  NULL ,
				cprice decimal(12,0)  DEFAULT NULL ,
				sku_code varchar(100)  NOT  NULL ,
				sku_name varchar(250)  DEFAULT NULL ,
				price decimal(12,0)  NOT  NULL ,
				quantity float NOT  NULL ,
				unit varchar(50)  DEFAULT NULL ,
				tax decimal(10,0)  DEFAULT NULL ,
				destination_id int(10)  DEFAULT NULL ,
				cart_serial text,
				UNIQUE  KEY  row (row_index,destination_id,order_id) ,
				PRIMARY KEY (`cart_id`),
				KEY  order_id (order_id) ,
				KEY  post_id (post_id) ,
				KEY  item_code (item_code) ,
				KEY  item_name (item_name(191)) ,
				KEY  sku_code (sku_code) ,
				KEY  sku_name (sku_name(191)) 
				);";

			dbDelta($sql);
			update_option("usces_db_ordercart", USCES_DB_ORDERCART);
		}
		if( $ordercart_meta_ver != USCES_DB_ORDERCART_META ) {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			$sql = "CREATE TABLE " . $ordercart_meta_table . " (
				cartmeta_id bigint(20)  NOT  NULL  AUTO_INCREMENT,
				cart_id bigint(20)  NOT  NULL DEFAULT '0',
				meta_type varchar(100)  NOT  NULL ,
				meta_key varchar(255)  DEFAULT NULL ,
				meta_value longtext,
				PRIMARY KEY (`cartmeta_id`),
				KEY  cart_id (cart_id) ,
				KEY  meta_key (meta_key(191)) 
				);";

			dbDelta($sql);
			update_option("usces_db_ordercart_meta", USCES_DB_ORDERCART_META);
		}
		if( $log_ver != USCES_DB_LOG ) {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			$sql = "CREATE TABLE " . $log_table . " (
				ID bigint(20) NOT NULL AUTO_INCREMENT,
				datetime DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				log LONGTEXT NULL,
				log_type VARCHAR(100) DEFAULT NULL,
				log_key VARCHAR(255) DEFAULT NULL,
				PRIMARY KEY (`ID`),
				KEY datetime (datetime),
				KEY log_type (log_type),
				KEY log_key (log_key(191))
				);";

			dbDelta($sql);
			update_option("usces_db_log", USCES_DB_LOG);
		}
		if( $acting_log_ver != USCES_DB_ACTING_LOG ) {
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$sql = "CREATE TABLE " . $acting_log_table . " (
				`ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
				`datetime` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				`log` LONGTEXT NULL,
				`acting` CHAR(32) DEFAULT NULL,
				`status` CHAR(32) DEFAULT NULL,
				`result` CHAR(32) DEFAULT NULL,
				`amount` DECIMAL( 10, 2 ) DEFAULT NULL,
				`order_id` BIGINT(20) NOT NULL,
				`tracking_id` CHAR(32) DEFAULT NULL,
				PRIMARY KEY (`ID`),
				KEY `datetime` (`datetime`),
				KEY `order_id` (`order_id`),
				KEY `tracking_id` (`tracking_id`),
				KEY `history_key` (`order_id`,`tracking_id`)
				);";
			dbDelta( $sql );
			update_option( "usces_db_acting_log", USCES_DB_ACTING_LOG );
		}

		if ( $db_item != USCES_DB_ITEM ) {
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$sql = 'CREATE TABLE ' . $item_table . ' (
				post_id BIGINT( 20 ) UNSIGNED NOT NULL ,
				itemCode VARCHAR( 200 ) NULL ,
				itemName VARCHAR( 250 ) NULL ,
				itemRestriction VARCHAR( 20 ) NULL ,
				itemPointrate VARCHAR( 20 ) NULL ,
				itemGpNum1 VARCHAR( 20 ) NULL ,
				itemGpNum2 VARCHAR( 20 ) NULL ,
				itemGpNum3 VARCHAR( 20 ) NULL ,
				itemGpDis1 VARCHAR( 20 ) NULL ,
				itemGpDis2 VARCHAR( 20 ) NULL ,
				itemGpDis3 VARCHAR( 20 ) NULL ,
				itemOrderAcceptable INT( 2 ) NULL ,
				itemShipping INT( 3 ) NULL ,
				itemDeliveryMethod LONGTEXT ,
				itemShippingCharge VARCHAR( 200 ) NULL ,
				itemIndividualSCharge VARCHAR( 20 ) NULL ,
				item_charging_type INT( 3 ) NULL ,
				item_division VARCHAR( 50 ) NULL ,
				dlseller_date VARCHAR( 100 ) NULL ,
				dlseller_file VARCHAR( 200 ) NULL ,
				dlseller_interval VARCHAR( 20 ) NULL ,
				dlseller_validity VARCHAR( 20 ) NULL ,
				dlseller_version VARCHAR( 100 ) NULL ,
				dlseller_author VARCHAR( 200 ) NULL ,
				dlseller_purchases VARCHAR( 20 ) NULL ,
				dlseller_downloads VARCHAR( 20 ) NULL ,
				item_chargingday VARCHAR( 20 ) NULL ,
				item_frequency VARCHAR( 20 ) NULL ,
				wcad_regular_unit VARCHAR( 20 ) NULL ,
				wcad_regular_interval INT( 10 ) NULL ,
				wcad_regular_frequency INT( 10 ) NULL ,
				select_sku_switch INT( 10 ) NULL ,
				select_sku_display INT( 10 ) NULL ,
				select_sku LONGTEXT ,
				atobarai_propriety INT( 5 ) NULL ,
				atodene_propriety INT( 5 ) NULL ,
				structuredDataSku VARCHAR( 200 ) NULL ,
				lower_limit INT( 10 ) NULL ,
				popularity INT( 10 ) NULL ,
				main_price DECIMAL( 10, 2 ) NULL ,
				itemPicts TEXT NULL ,
				itemAdvanced LONGTEXT NULL ,
				PRIMARY KEY (`post_id`),
				KEY itemCode ( itemCode ) ,  
				KEY itemName ( itemName )  
				);';

			dbDelta( $sql );
			update_option( "usces_db_item", USCES_DB_ITEM );
		}

		if ( $db_skus != USCES_DB_SKUS ) {
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$sql = 'CREATE TABLE ' . $sku_table . ' (
				meta_id BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
				post_id BIGINT( 20 ) UNSIGNED NOT NULL ,
				code VARCHAR( 200 ) NULL ,
				name VARCHAR( 250 ) NULL ,
				cprice DECIMAL( 10, 2 ) NULL , 
				price DECIMAL( 10, 2 ) NULL , 
				unit VARCHAR( 100 ) NULL ,
				stocknum VARCHAR( 50 ) NULL ,
				stock INT( 5 ) NULL ,
				gp INT( 2 ) NULL ,
				taxrate VARCHAR( 20 ) NULL , 
				size VARCHAR( 20 ) NULL , 
				weight VARCHAR( 20 ) NULL , 
				pict_id VARCHAR( 20 ) NULL , 
				advance LONGTEXT ,
				paternkey VARCHAR( 100 ) NULL ,
				sort INT( 5 ) NULL ,
				PRIMARY KEY (`meta_id`),
				KEY post_id ( post_id ) ,  
				KEY code ( code ) ,  
				KEY name ( name ) ,  
				KEY price ( price ) ,  
				KEY paternkey ( paternkey )  
				);';

			dbDelta( $sql );
			update_option( "usces_db_skus", USCES_DB_SKUS );
		}

		if ( $db_opts != USCES_DB_OPTS ) {
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$sql = 'CREATE TABLE ' . $opt_table . ' (
				meta_id BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
				post_id BIGINT( 20 ) UNSIGNED NOT NULL ,
				code VARCHAR( 200 ) NULL ,
				name VARCHAR( 250 ) NULL ,
				means INT( 3 ) NULL ,
				essential INT( 3 ) NULL ,
				value TEXT ,
				sort INT( 5 ) NULL ,
				PRIMARY KEY (`meta_id`),
				KEY post_id ( post_id ) ,  
				KEY code ( code ) ,  
				KEY name ( name ) 
				) ' . $charset_collate . ';';

			dbDelta( $sql );
			update_option( "usces_db_opts", USCES_DB_OPTS );
		}

		if ( $db_admin_log != USCES_DB_ADMIN_LOG ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			$sql = "CREATE TABLE {$admin_log_table} (
				`ID` int(11) NOT NULL AUTO_INCREMENT,
				`author` varchar(64) NOT NULL,
				`message` longtext NOT NULL,
				`screen` varchar(64) NOT NULL,
				`entity_id` varchar(64) DEFAULT NULL,
				`action` varchar(64) NOT NULL,
				`data` longblob DEFAULT NULL,
				`datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY (`ID`)
				) " . $charset_collate . ';';

			dbDelta( $sql );
			update_option( "usces_db_admin_log", USCES_DB_ADMIN_LOG );
		}
		do_action( 'usces_action_update_table' );
	}

	function dir_copy($source, $dest){
		if ($res = opendir($source)) {
			while (($file = readdir($res)) !== false) {
				$sorce_path = $source . '/' . $file;
				$dest_path = $dest . '/' . $file;
				$filetype = @filetype($sorce_path);
				if( $filetype == 'file' ) {
					copy($sorce_path, $dest_path);
				}elseif( $filetype == 'dir' && $file != '..' && $file != '.' ){
					mkdir($dest_path);
					$this->dir_copy($sorce_path, $dest_path);
				}
			}
			closedir($res);
		}
	}

	function set_default_page()
	{
		global $wpdb;

		$datetime = get_date_from_gmt(gmdate('Y-m-d H:i:s', time()));
		$datetime_gmt = gmdate('Y-m-d H:i:s', time());

		//cart_page
		$query = $wpdb->prepare("SELECT ID from $wpdb->posts where post_name = %s", USCES_CART_FOLDER);
		$cart_number = $wpdb->get_var( $query );
		if( $cart_number === NULL ) {
			$query = $wpdb->prepare("INSERT INTO $wpdb->posts 
				(post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, 
				comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, 
				post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count)
				VALUES (%d, %s, %s, %s, %s, %s, %s, 
				%s, %s, %s, %s, %s, %s, %s, %s, 
				%s, %d, %s, %d, %s, %s, %d)",
				1, $datetime, $datetime_gmt, '', __('Cart', 'usces'), '', 'publish',
				'closed', 'closed', '', USCES_CART_FOLDER, '', '', $datetime, $datetime_gmt,
				'', 0, '', 0, 'page', '', 0);
			$wpdb->query($query);
			$cart_number = $wpdb->insert_id;
			if( $cart_number !== NULL ) {
				$query = $wpdb->prepare("INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) VALUES (%d, %s, %s)",
					$cart_number, '_wp_page_template', 'uscescart.php');
				$wpdb->query($query);
			}
		}
		update_option('usces_cart_number', $cart_number);

		//member_page
		$query = $wpdb->prepare("SELECT ID from $wpdb->posts where post_name = %s", USCES_MEMBER_FOLDER);
		$member_number = $wpdb->get_var( $query );
		if( $member_number === NULL ) {
			$query = $wpdb->prepare("INSERT INTO $wpdb->posts 
				(post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, 
				comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, 
				post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count)
				VALUES (%d, %s, %s, %s, %s, %s, %s, 
				%s, %s, %s, %s, %s, %s, %s, %s, 
				%s, %d, %s, %d, %s, %s, %d)",
				1, $datetime, $datetime_gmt, '', __('Membership', 'usces'), '', 'publish',
				'closed', 'closed', '', USCES_MEMBER_FOLDER, '', '', $datetime, $datetime_gmt,
				'', 0, '', 0, 'page', '', 0);
			$wpdb->query($query);
			$member_number = $wpdb->insert_id;
			if( $member_number !== NULL ) {
				$query = $wpdb->prepare("INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) VALUES (%d, %s, %s)",
					$member_number, '_wp_page_template', 'uscesmember.php');
				$wpdb->query($query);
			}
		}
		update_option('usces_member_number', $member_number);

	}

	function set_default_categories()
	{
		global $wpdb;

		$item_category_slug = apply_filters( 'usces_item_category_slug', 'item' );

		$idObj = get_category_by_slug( $item_category_slug );
		if( empty($idObj) ) {
			$item_cat = array('cat_name' => __('Items', 'usces'), 'category_description' => '', 'category_nicename' => $item_category_slug, 'category_parent' => 0);
			$item_cat_id = wp_insert_category($item_cat);
			update_option('usces_item_cat_parent_id', $item_cat_id);
		}

		$idObj = get_category_by_slug('itemreco');
		if( empty($idObj) && isset($item_cat_id) ) {
			$itemreco_cat = array('cat_name' => __('Items recommended', 'usces'), 'category_description' => '', 'category_nicename' => 'itemreco', 'category_parent' => $item_cat_id);
			$itemreco_cat_id = wp_insert_category($itemreco_cat);
		}

		$idObj = get_category_by_slug('itemnew');
		if( empty($idObj) && isset($item_cat_id) ) {
			$itemnew_cat = array('cat_name' => __('New items', 'usces'), 'category_description' => '', 'category_nicename' => 'itemnew', 'category_parent' => $item_cat_id);
			$itemnew_cat_id = wp_insert_category($itemnew_cat);
		}

		$idObj = get_category_by_slug('itemgenre');
		if( empty($idObj) && isset($item_cat_id) ) {
			$itemgenre_cat = array('cat_name' => __('Item genre', 'usces'), 'category_description' => '', 'category_nicename' => 'itemgenre', 'category_parent' => $item_cat_id);
			$itemgenre_cat_id = wp_insert_category($itemgenre_cat);
		}
	}

	function update_options() {
		$target_market = $this->options['system']['target_market'];

		$update_shipping_charge = false;
		$shipping_charge = isset($this->options['shipping_charge']) ? $this->options['shipping_charge'] : array();
		$shipping_charge_count = ( $shipping_charge && is_array( $shipping_charge ) ) ? count( $shipping_charge ) : 0;
		foreach( (array)$target_market as $tm ) {
			for( $i = 0; $i < $shipping_charge_count; $i++ ) {
				if( isset($shipping_charge[$i]['country']) and $shipping_charge[$i]['country'] == $tm ) {
					foreach( $shipping_charge[$i]['value'] as $pref => $value ) {
						$shipping_charge[$i][$tm][$pref] = $value;
					}
					unset($shipping_charge[$i]['country']);
					unset($shipping_charge[$i]['value']);
					$update_shipping_charge = true;
				}
			}
		}
		if( $update_shipping_charge ) $this->options['shipping_charge'] = $shipping_charge;

		$update_delivery_days = false;
		$delivery_days = isset($this->options['delivery_days']) ? $this->options['delivery_days'] : array();
		$delivery_days_count = ( $delivery_days && is_array( $delivery_days ) ) ? count( $delivery_days ) : 0;
		foreach( (array)$target_market as $tm ) {
			for( $i = 0; $i < $delivery_days_count; $i++ ) {
				if( isset($delivery_days[$i]['country']) and $delivery_days[$i]['country'] == $tm ) {
					foreach( $delivery_days[$i]['value'] as $pref => $value ) {
						$delivery_days[$i][$tm][$pref] = $value;
					}
					unset($delivery_days[$i]['country']);
					unset($delivery_days[$i]['value']);
					$update_delivery_days = true;
				}
			}
		}
		if( $update_delivery_days ) $this->options['delivery_days'] = $delivery_days;

		$update_acting_settings_paydesign = false;
		if( isset($this->options['acting_settings']['digitalcheck']['card_activate']) and 'on' == $this->options['acting_settings']['digitalcheck']['card_activate'] ) {
			$pos = strpos( $this->options['acting_settings']['digitalcheck']['send_url_card'], 'paydesign' );
			if( $pos === false ) {
				$this->options['acting_settings']['digitalcheck']['send_url_card'] = "https://www.paydesign.jp/settle/settle3/bp3.dll";
				$this->payment_structure['acting_digitalcheck_card'] = 'カード決済（メタップスペイメント）';
				$update_acting_settings_paydesign = true;
			}
			if( isset($this->options['acting_settings']['digitalcheck']['card_user_id']) and 'on' == $this->options['acting_settings']['digitalcheck']['card_user_id'] ) {
				$pos = strpos( $this->options['acting_settings']['digitalcheck']['send_url_user_id'], 'paydesign' );
				if( $pos === false ) {
					$this->options['acting_settings']['digitalcheck']['send_url_user_id'] = "https://www.paydesign.jp/settle/settlex/credit2.dll";
					$update_acting_settings_paydesign = true;
				}
			}
		}
		if( isset($this->options['acting_settings']['digitalcheck']['conv_activate']) and 'on' == $this->options['acting_settings']['digitalcheck']['conv_activate'] ) {
			$pos = strpos( $this->options['acting_settings']['digitalcheck']['send_url_conv'], 'paydesign' );
			if( $pos === false ) {
				$this->options['acting_settings']['digitalcheck']['send_url_conv'] = "https://www.paydesign.jp/settle/settle3/bp3.dll";
				$this->payment_structure['acting_digitalcheck_conv'] = 'コンビニ決済（メタップスペイメント）';
				$update_acting_settings_paydesign = true;
			}
		}

		if( $update_shipping_charge or $update_delivery_days or $update_acting_settings_paydesign )
			update_option( 'usces', $this->options );

		if( $update_acting_settings_paydesign )
			update_option( 'usces_payment_structure', $this->payment_structure );
	}

	function get_item_cat_ids(){
		$ids = array();
		$args = array('child_of' => USCES_ITEM_CAT_PARENT_ID, 'hide_empty' => 0, 'hierarchical' => 0);
		$categories = get_categories( $args );
		foreach($categories as $category){
			$ids[] = $category->term_id;
		}
		return $ids;
	}

	function get_item_post_ids(){
		global $wpdb;
		$query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_mime_type = %s", 'item');
		$ids = $wpdb->get_col( $query );

		return $ids;
	}

	function get_item_cat_genre_ids( $post_id ){
		$ids = array();
		$all_ids = array();
		$genre = get_category_by_slug( 'itemgenre' );
		$genre_id = $genre->term_id;
		$args = array('child_of' => $genre_id, 'hide_empty' => 0, 'hierarchical' => 0);
		$categories = get_categories( $args );
		foreach($categories as $category){
			$ids[] = $category->term_id;
		}
		$allcats = get_the_category( $post_id );
		foreach($allcats as $cat){
			$all_ids[] = $cat->term_id;
		}
		$results = array_intersect($ids, $all_ids);

		return $results;
	}

	function set_item_mime($post_id, $str)
	{
		global $wpdb;
		if( WCUtils::is_blank($str) ) return;

		$query = $wpdb->prepare("UPDATE $wpdb->posts SET post_mime_type = %s WHERE ID = %s", $str, $post_id);
		$results = $wpdb->query( $query );
		return $results;
	}

	function isAdnminSSL()
	{
		$plugins = get_option('active_plugins');
		foreach($plugins as $plugin) {
			if( false !== strpos($plugin, USCES_ADMIN_SSL_BASE_NAME) )
				return true;
		}
		return false;
	}

	function getGuidTax() {
		if( isset($this->options['tax_display']) and 'deactivate' == $this->options['tax_display'] ) {
			$str = '';
		} else {
			$tax_rate = (int)$this->options['tax_rate'];

			if( isset($this->options['tax_mode']) ){
				if ( 'exclude' == $this->options['tax_mode'] )
					$str = '<em class="tax">'.__('(Excl. Tax)', 'usces').'</em>';
				else
					$str = '<em class="tax">'.__('(Incl. Tax)', 'usces').'</em>';
			}else{
				if ( 0 < $tax_rate )
					$str = '<em class="tax">'.__('(Excl. Tax)', 'usces').'</em>';
				else
					$str = '<em class="tax">'.__('(Incl. Tax)', 'usces').'</em>';
			}
			$str = apply_filters('usces_filter_tax_guid', $str, $tax_rate);
		}
		return $str;
	}

	function getItemCode( $post_id, $cache = true ) {
		$product  = wel_get_product( $post_id, $cache );
		$itemCode = isset( $product['itemCode'] ) ? $product['itemCode'] : null;
		return $itemCode;
	}

	function getItemName( $post_id, $cache = true ) {
		$product  = wel_get_product( $post_id, $cache );
		$itemName = isset( $product['itemName'] ) ? $product['itemName'] : null;
		return $itemName;
	}

	function getItemRestriction( $post_id, $cache = true ) {
		$product         = wel_get_product( $post_id, $cache );
		$itemRestriction = isset( $product['itemRestriction'] ) ? $product['itemRestriction'] : null;
		return $itemRestriction;
	}

	function getItemOrderAcceptable( $post_id, $cache = true ) {
		$product = wel_get_product( $post_id, $cache );
		$value   = ( ! empty( $product['itemOrderAcceptable'] ) ) ? (int) $product['itemOrderAcceptable'] : 0;
		return $value;
	}

	function getItemPointrate( $post_id, $cache = true ) {
		$product       = wel_get_product( $post_id, $cache );
		$itemPointrate = isset( $product['itemPointrate'] ) ? $product['itemPointrate'] : null;
		return $itemPointrate;
	}

	function getItemShipping( $post_id, $cache = true ) {
		$product      = wel_get_product( $post_id, $cache );
		$itemShipping = isset( $product['itemShipping'] ) ? $product['itemShipping'] : null;
		return $itemShipping;
	}

	function getItemShippingCharge( $post_id, $cache = true ) {
		$product = wel_get_product( $post_id, $cache );
		$itemShippingCharge = isset( $product['itemShippingCharge'] ) ? (float) $product['itemShippingCharge'] : null;
		return $itemShippingCharge;
	}

	function getItemDeliveryMethod( $post_id, $cache = true ) {
		$product = wel_get_product( $post_id, $cache );
		if ( empty( $product['itemDeliveryMethod'] ) ) {
			return array();
		} else {
			return $product['itemDeliveryMethod'];
		}
	}

	function getItemIndividualSCharge( $post_id, $cache = true ) {
		$product               = wel_get_product( $post_id, $cache );
		$itemIndividualSCharge = isset( $product['itemIndividualSCharge'] ) ? $product['itemIndividualSCharge'] : null;
		return $itemIndividualSCharge;
	}

	function getItemGpNum1( $post_id, $cache = true ) {
		$product    = wel_get_product( $post_id, $cache );
		$itemGpNum1 = isset( $product['itemGpNum1'] ) ? $product['itemGpNum1'] : null;
		return $itemGpNum1;
	}

	function getItemGpNum2( $post_id, $cache = true ) {
		$product    = wel_get_product( $post_id, $cache );
		$itemGpNum2 = isset( $product['itemGpNum2'] ) ? $product['itemGpNum2'] : null;
		return $itemGpNum2;
	}

	function getItemGpNum3( $post_id, $cache = true ) {
		$product    = wel_get_product( $post_id, $cache );
		$itemGpNum3 = isset( $product['itemGpNum3'] ) ? $product['itemGpNum3'] : null;
		return $itemGpNum3;
	}

	function getItemGpDis1( $post_id, $cache = true ) {
		$product    = wel_get_product( $post_id, $cache );
		$itemGpDis1 = isset( $product['itemGpDis1'] ) ? $product['itemGpDis1'] : null;
		return $itemGpDis1;
	}

	function getItemGpDis2( $post_id, $cache = true ) {
		$product    = wel_get_product( $post_id, $cache );
		$itemGpDis2 = isset( $product['itemGpDis2'] ) ? $product['itemGpDis2'] : null;
		return $itemGpDis2;
	}

	function getItemGpDis3( $post_id, $cache = true ) {
		$product    = wel_get_product( $post_id, $cache );
		$itemGpDis3 = isset( $product['itemGpDis3'] ) ? $product['itemGpDis3'] : null;
		return $itemGpDis3;
	}

	/**
	 * Unused function
	 */
	function getItemSku( $post_id, $index = '', $cache = true ) {
		$array = array();
		$skus = $this->get_skus( $post_id, 'sort', $cache );
		foreach((array)$skus as $sku){
			$array[] = $sku['code'];
		}
		if(!$array) return false;
		if($index == ''){
			return $array;
		}else if(isset($array[$index])){
			return $array[$index];
		}else{
			return false;
		}
	}

	function getItemPrice( $post_id, $skukey = '', $cache = true ) {
		$array = array();
		$skus  = wel_get_skus( $post_id, 'code', $cache );
		foreach ( (array) $skus as $key => $sku ) {
			$array[ $key ] = (float) str_replace( ',', '', $sku['price'] );
		}
		$array = apply_filters( 'usces_filter_get_item_price', $array, $post_id, $skukey, $skus );
		if ( ! $array ){
			return false;
		}
		if ( $skukey === '' ) {
			return $array;
		} elseif ( isset( $array[ $skukey ] ) ) {
			return $array[ $skukey ];
		} else {
			return false;
		}
	}

	function getItemDiscount( $post_id, $skukey = '', $cache = true ) {
		$display_mode = $this->options['display_mode'];
		$decimal      = $this->get_currency_decimal();
		$array        = array();

		$skus = wel_get_skus( $post_id, 'code', $cache );
		$discount = 0;
		foreach ( (array) $skus as $key => $sku ) {
			$price = (float) str_replace( ',', '', $sku['price'] );
			if ( $display_mode == 'Promotionsale' ) {
				if ( $this->options['campaign_privilege'] === 'discount' ){
					if ( 0 === (int) $this->options['campaign_category'] || in_category( (int) $this->options['campaign_category'], $post_id ) ) {
						$discount = (float) sprintf( '%.3f', $price * $this->options['privilege_discount'] / 100 );
					} else {
						$discount = 0;
					}
				} elseif ( $this->options['campaign_privilege'] === 'point' ) {
					$discount = 0;
				}
			}
			if ( 0 != $discount ) {
				if ( 0 == $decimal ) {
					$discount = ceil( $discount );
				} else {
					$decipad  = (int) str_pad( '1', $decimal + 1, '0', STR_PAD_RIGHT );
					$discount = ceil( $discount * $decipad ) / $decipad;
				}
			}
			$discount = apply_filters( 'usces_filter_getItemDiscount', $discount, $price, $post_id, $sku, $display_mode );
			$array[ $key ] = $discount;
		}
		if ( ! $array ) {
			return false;
		}
		if ( $skukey === '' ) {
			return $array;
		} elseif ( isset( $array[ $skukey ] ) ) {
			return $array[ $skukey ];
		} else {
			return false;
		}
	}

	function getItemZaiko( $post_id, $skukey = '', $cache = true ) {
		$itemOrderAcceptable = $this->getItemOrderAcceptable( $post_id );
		$array = array();

		$skus = wel_get_skus( $post_id, 'code', $cache );
		foreach ( (array) $skus as $key => $sku ) {
			$num = $sku['stock'];
			if ( $itemOrderAcceptable != 1 || WCUtils::is_blank($sku['stocknum'] ) ) {
				$array[ $key ] = $this->zaiko_status[ $num ];
			} else {
				if ( 2 > $num && 0 >= (int) $sku['stocknum'] ) {
					$array[ $key ] = ( ! empty( $this->options['order_acceptable_label'] ) ) ? $this->options['order_acceptable_label'] : __( 'Order acceptable', 'usces' );
				} else {
					$array[ $key ] = $this->zaiko_status[ $num ];
				}
			}
		}
		if ( ! $array ) {
			return false;
		}
		if ( $skukey === '' ) {
			return $array;
		} elseif ( isset( $array[ $skukey ] ) ) {
			return $array[ $skukey ];
		} else {
			return false;
		}
	}

	function getItemZaikoStatusId( $post_id, $skukey = '', $cache = true ) {
		$array = array();
		$skus  = wel_get_skus( $post_id, 'code', $cache );
		foreach ( (array) $skus as $key => $sku ) {
			$num           = $sku['stock'];
			$array[ $key ] = $num;
		}
		if ( ! $array ) {
			return false;
		}
		if ( $skukey === '' ) {
			return $array;
		} elseif ( isset( $array[ $skukey ] ) ) {
			return $array[ $skukey ];
		} else {
			return false;
		}
	}

	function updateItemZaiko( $post_id, $skucode, $value ) {
		$res = usces_update_sku( $post_id, $skucode, 'stock', $value );
		if( !$res ){
			return false;
		}else{
			return true;
		}
	}

	function getItemZaikoNum( $post_id, $skukey = '', $cache = true ) {
		$array = array();
		$skus  = wel_get_skus( $post_id, 'code', $cache );
		foreach ( (array) $skus as $key => $sku ) {
			$array[ $key ] = $sku['stocknum'];
		}
		if ( ! $array ) {
			return false;
		}
		if ( $skukey === '' ) {
			return $array;
		} elseif ( isset( $array[ $skukey ] ) ) {
			return $array[ $skukey ];
		} else {
			return false;
		}
	}

	function updateItemZaikoNum( $post_id, $skucode, $value ) {
		$res = usces_update_sku( $post_id, $skucode, 'stocknum', $value );
		if( !$res ){
			return false;
		}else{
			return true;
		}
	}

	function getItemDivision( $post_id, $cache = true ){
		if ( usces_is_item( $post_id ) ){
			$product       = wel_get_product( $post_id, $cache );
			$item_division = $product['item_division'];
			$division      = empty( $item_division ) ? 'shipped' : $item_division;
		} else {
			$division = NULL;
		}
		return $division;
	}

	function getItemChargingType( $post_id, $cart = array(), $cache = true ){
		if ( usces_is_item( $post_id ) ) {
			$product  = wel_get_product( $post_id, $cache );
			$charging = $product['item_charging_type'];
			if ( ! defined( 'WCEX_DLSELLER' ) && ! defined( 'WCEX_AUTO_DELIVERY' ) ){
				$charging = NULL;
			}
		} else {
			$charging = NULL;
		}
		switch ( $charging ) {
			case 0:
				$type = 'once';
				break;
			case 1:
				$type = 'continue';
				break;
			case 2:
				$type = 'regular';
				if ( ! empty( $cart ) ) {
					if ( empty( $cart['advance'] ) ) {
						$type = 'once';
					} else {
						if ( is_array( $cart['advance'] ) && array_key_exists( 'regular', $cart['advance'] ) ) {
							$regular = maybe_unserialize( $cart['advance']['regular'] );
						} else {
							$advance     = $this->cart->wc_unserialize( $cart['advance'] );
							$sku         = urldecode( $cart['sku'] );
							$sku_encoded = $cart['sku'];
							$regular     = $advance[ $post_id ][ $sku_encoded ]['regular'];
						}
						$unit     = isset( $regular['unit'] ) ? $regular['unit'] : '';
						$interval = isset( $regular['interval'] ) ? (int) $regular['interval'] : 0;
						/* Treated as normal billing */
						if ( empty($unit) || 1 > $interval ){
							$type = 'once';
						}
					}
				}
				break;
			default:
				$type = NULL;
		}
		return $type;
	}

	function getItemFrequency( $post_id, $cache = true ){
		$product = wel_get_product( $post_id, $cache );
		return $product['item_frequency'];
	}

	function getItemChargingDay( $post_id, $cache = true ){
		$product = wel_get_product( $post_id, $cache );
		$day     = (int) $product['item_chargingday'];

		$chargingday = empty( $day ) ? 1 : $day;
		return $chargingday;
	}

	function getItemSkuDisp( $post_id, $skukey = '', $cache = true ) {
		$array = array();
		$skus  = wel_get_skus( $post_id, 'code', $cache );
		foreach ( (array) $skus as $key => $sku ) {
			$array[ $key ] = $sku['name'];
		}
		if ( ! $array ) {
			return false;
		}
		if ( $skukey === '' ) {
			return $array;
		} elseif ( isset( $array[ $skukey ] ) ) {
			return $array[ $skukey ];
		} else {
			return false;
		}
	}

	function getItemSkuUnit( $post_id, $skukey = '', $cache = true ) {
		$array = array();
		$skus  = wel_get_skus( $post_id, 'code', $cache );
		foreach ( (array) $skus as $key => $sku ) {
			$array[ $key ] = $sku['unit'];
		}
		if ( ! $array ) {
			return false;
		}
		if ( $skukey === '' ) {
			return $array;
		} elseif ( isset( $array[ $skukey ] ) ) {
			return $array[ $skukey ];
		} else {
			return false;
		}
	}

	function getItemSkuAdvance( $post_id, $skukey = '', $cache = true ){
		$array = array();
		$skus  = wel_get_skus( $post_id, 'code', $cache );
		foreach ( (array) $skus as $key => $sku ) {
			$array[ $key ] = $sku['advance'];
		}
		if ( ! $array ) {
			return false;
		}
		if ( $skukey === '' ) {
			return $array;
		} elseif ( isset( $array[ $skukey ] ) ) {
			return $array[ $skukey ];
		} else {
			return false;
		}
	}

	function get_item( $post_id, $cache = true ) {
		return wel_get_product( $post_id, $cache );
	}

	function get_itemOptionKey( $post_id, $enc = false, $cache = true ) {
		$opts = usces_get_opts( $post_id, 'sort', $cache );
		if(empty($opts)) return;

		$res = array();
		foreach ( (array)$opts as $opt ) {
			if( $enc )
				$res[] = urlencode($opt['name']);
			else
				$res[] = $opt['name'];
		}
		return $res;
	}

	function get_itemOptions( $key, $post_id, $cache = true ) {
		$opts = wel_get_opts( $post_id, 'name', $cache );
		if ( ! isset( $opts[ $key ] ) ) {
			return false;
		} else {
			return $opts[ $key ];
		}
	}

	function get_postIDbyCode( $item_code, $cache = true ) {

		if ( null === $item_code ){
			return false;
		}

		$post_id = wel_get_id_by_item_code( $item_code, $cache );

		return $post_id;
	}

	function get_itemByCode( $item_code, $cache = true ) {

		if ( null === $item_code ){
			return false;
		}

		$product = wel_get_product_by_code( $item_code, $cache );

		return $product;
	}

	function get_pictids( $item_code, $cache = true ) {
		return wel_get_sub_pict_ids_by_code( $item_code, $cache );
	}

	function get_mainpictid( $item_code, $cache = true ) {
		return wel_get_main_pict_id_by_code( $item_code, $cache );
	}

	function get_subpictid( $sku_code, $cache = true ) {
		global $wpdb;
		if( empty($sku_code) )
			return 0;

		$query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'attachment' LIMIT 1", $sku_code);
		$id = $wpdb->get_var( $query );
		$id = apply_filters( 'usces_filter_get_subpictid', $id, $sku_code );
		return $id;
	}

	function get_skus( $post_id, $keyflag = 'sort', $cache = true ) {

		if ( null === $post_id ){
			return false;
		}

		$skus = wel_get_skus( $post_id, $keyflag, $cache );

		return $skus;
	}

	function is_item( $post ) {

		if( $post->post_mime_type == 'item' )
			return true;
		else
			return false;
	}

	function getItemIds( $end_type ) {
		global $wpdb;

		$cache_key = 'wel_item_ids_' . $end_type;

		$ids = wp_cache_get( $cache_key );
		if ( false === $ids ) {

			if( 'front' == $end_type ) {
				$query = $wpdb->prepare("SELECT ID  FROM $wpdb->posts WHERE post_status = %s AND post_mime_type = %s", 'publish', 'item');
			}
			if( 'back' == $end_type ) {
				$query = $wpdb->prepare("SELECT ID  FROM $wpdb->posts WHERE post_mime_type = %s", 'item');
			}
			$ids = $wpdb->get_col( $query );

			if ( null !== $ids ) {
				wp_cache_set( $cache_key, $ids );
			}
		}

		if ( null === $ids ) {
			return array();
		} else {
			return $ids;
		}
	}

	function getNotItemIds() {
		global $wpdb;
		$query = $wpdb->prepare("SELECT ID  FROM $wpdb->posts WHERE post_status = %s AND post_mime_type <> %s", 'publish', 'item');
		$ids = $wpdb->get_col( $query );
		if( empty($ids) ) $ids = array();
		return $ids;
	}

	function getPaymentMethod( $name ) {
		$res = array();
		$payments = $this->options['payment_method'];
		foreach ( (array)$payments as $payment ) {
			if($name = $payment['name']) {
				$res = $payment;
				break;
			}
		}
		return $res;
	}

	function order_processing( $results = array() ) {
		do_action('usces_pre_reg_orderdata');
		$acting = isset($_REQUEST['acting']) ? $_REQUEST['acting'] : '';
		global $usces;
		$usces_entries = $this->cart->get_entry();
		$payments = $this->getPayments($usces_entries['order']['payment_name']);
		$nonacting_settlements = apply_filters( 'usces_filter_nonacting_settlements', $this->nonacting_settlements );

		$res = usces_check_acting_return_duplicate( $results );
		if($res != NULL && !in_array( $payments['settlement'], $nonacting_settlements)) {
			usces_log('order processing duplicate : acting='.$acting.', order_id='.$res, 'acting_transaction.log');
			return 'ordercompletion';
		}
		if(isset($_REQUEST['acting']) && ('jpayment_card' == $_REQUEST['acting'] || 'jpayment_conv' == $_REQUEST['acting'] || 'jpayment_bank' == $_REQUEST['acting'])) {
			usces_log($_REQUEST['acting'].' transaction : '.$_REQUEST['gid'], 'acting_transaction.log');//OK
		}
		if(isset($_REQUEST['acting']) && ('paypal_ec' == $_REQUEST['acting'])) {
			if( !usces_paypal_doecp( $results ) )
				return 'error';
		}
		$order_id = usces_reg_orderdata( $results );
		do_action('usces_post_reg_orderdata', $order_id, $results);

		if ( $order_id ) {
			//mail(function.php)
			$mail_res = usces_send_ordermail( $order_id );
			return 'ordercompletion';

		} else {
			return 'error';
		}

	}

	function acting_processing( $acting_flg, $post_query, $acting_status ) {
		global $wpdb;
		$entry = $this->cart->get_entry();
		$delim = apply_filters( 'usces_filter_delim', $this->delim );
		$acting_flg = trim($acting_flg);

		if( empty($acting_flg) ) return 'error';

		if($acting_flg == 'paypal.php'){

		}else if($acting_flg == 'epsilon.php'){
			if( !file_exists($this->options['settlement_path'] . $acting_flg) )
				return 'error';

			if ( $this->use_ssl ) {
				$redirect = str_replace('http://', 'https://', USCES_CART_URL);
			}else{
				$redirect = USCES_CART_URL;
			}
			usces_log('epsilon card entry data (acting_processing) : '.print_r($entry, true), 'acting_transaction.log');
			$post_query .= '&settlement=epsilon&redirect_url=' . urlencode($redirect);
			$post_query = $delim . ltrim($post_query, '&');
			header("location: " . $redirect . $post_query);
			exit;

		} else if( $acting_flg == 'acting_paypal_ec' ) {
			$acting_opts = $this->options['acting_settings']['paypal'];
			$addroverride = '1';
			if( isset( $_POST['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'] ) ) {
				if( 'US' == $_POST['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'] || 'CA' == $_POST['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'] ) $addroverride = '0';
			} else {
				$addroverride = '0';
			}
			$rand = ( isset( $_POST['PAYMENTREQUEST_0_CUSTOM'] ) ) ? $_POST['PAYMENTREQUEST_0_CUSTOM'] : '(empty key)';

			$nvpstr  = $post_query;
			$nvpstr .= '&ADDROVERRIDE='.$addroverride;
			$nvpstr .= '&PAYMENTREQUEST_0_PAYMENTACTION='.apply_filters( 'usces_filter_paypal_ec_paymentaction', 'Sale' );

			//The returnURL is the location where buyers return to when a payment has been succesfully authorized.
			$nvpstr .= '&RETURNURL='.urlencode( USCES_CART_URL.$delim.'acting=paypal_ec&acting_return=1' );

			//The cancelURL is the location buyers are sent to when they hit the cancel button during authorization of payment during the PayPal flow
			$cancelurl = urlencode( USCES_CART_URL.$delim.'confirm=1' );
			$pos = strpos( $post_query, 'paypal_from_cart' );
			if( false !== $pos ) {
				$cancelurl = urlencode( USCES_CART_URL );
			}
			$nvpstr .= '&CANCELURL='.apply_filters( 'usces_filter_paypal_ec_cancelurl', $cancelurl, $post_query );

			$nvpstr .= '&PAYMENTREQUEST_0_NOTIFYURL='.urlencode( USCES_PAYPAL_NOTIFY_URL );

			//Seamless checkout
			if( isset( $_SESSION['liwpp']['token'] ) && !empty( $_SESSION['liwpp']['token'] ) ) {
				$nvpstr .= '&IDENTITYACCESSTOKEN='.$_SESSION['liwpp']['token'];
			}

			$this->paypal->setMethod( 'SetExpressCheckout' );
			$this->paypal->setData( $nvpstr );
			$res = $this->paypal->doExpressCheckout();
			$resArray = $this->paypal->getResponse();
			$ack = strtoupper( $resArray["ACK"] );
			if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" ) {
				$token = urldecode( $resArray["TOKEN"] );
				$payPalURL = $acting_opts['paypal_url'].'?cmd=_express-checkout&token='.$token.'&useraction=commit';
				header( "Location: ".$payPalURL );

			} else {
				//Display a user friendly Error on the page using any of the following error information returned by PayPal
				$ErrorCode = urldecode( $resArray["L_ERRORCODE0"] );
				$ErrorShortMsg = urldecode( $resArray["L_SHORTMESSAGE0"] );
				$ErrorLongMsg = urldecode( $resArray["L_LONGMESSAGE0"] );
				$ErrorSeverityCode = urldecode( $resArray["L_SEVERITYCODE0"] );
				usces_log( 'PayPal : SetExpressCheckout API call failed. Error Code:['.$ErrorCode.'] Error Severity Code:['.$ErrorSeverityCode.'] Short Error Message:'.$ErrorShortMsg.' Detailed Error Message:'.$ErrorLongMsg, 'acting_transaction.log' );
				$log = array( 'acting'=>'paypal_ec', 'key'=>$rand, 'result'=>$ack, 'data'=>$resArray );
				usces_save_order_acting_error( $log );
				header( "Location: ".USCES_CART_URL.$delim.'acting=paypal_ec&acting_return=0' );
			}
			exit;

		} elseif( $acting_flg == 'acting_telecom_edy' ) {
			$table_meta_name = $wpdb->prefix."usces_order_meta";
			$value = array();
			$value['usces_cart'] = $_SESSION['usces_cart'];
			$value['usces_entry'] = $_SESSION['usces_entry'];
			$value['usces_member'] = $_SESSION['usces_member'];
			$mvalue = serialize( $value );
			$mquery = $wpdb->prepare( "INSERT INTO $table_meta_name (order_id, meta_key, meta_value) VALUES (%d, %s, %s)", $_POST['option'], $_POST['option'], $mvalue );
			$res = $wpdb->query( $mquery );

			unset( $_SESSION['usces_cart'] );
			unset( $_SESSION['usces_entry'] );

			$acting_opts = $this->options['acting_settings']['telecom'];
			header( "location: ".$acting_opts['send_url_edy'].'?acting=telecom_edy'.$post_query );
			exit;

		} else if( $acting_flg == 'acting_digitalcheck_card' ) {
			$acting_opts = $this->options['acting_settings']['digitalcheck'];
			$interface = parse_url($acting_opts['send_url_user_id']);
			$kakutei = ( empty($acting_opts['card_kakutei']) ) ? '0' : $acting_opts['card_kakutei'];

			$vars  = 'IP='.$acting_opts['card_ip'];
			$vars .= '&PASS='.$acting_opts['card_pass'];
			$vars .= '&IP_USER_ID='.$_POST['IP_USER_ID'];
			$vars .= '&SID='.$_POST['SID'];
			$vars .= '&STORE=51';
			$vars .= '&N1='.$_POST['N1'];
			$vars .= '&K1='.$_POST['K1'];
			$vars .= '&KAKUTEI='.$kakutei;
			$vars .= '&FUKA='.$acting_flg;

			$header  = "POST ".$interface['path']." HTTP/1.1\r\n";
			$header .= "Host: ".$_SERVER['HTTP_HOST']."\r\n";
			$header .= "User-Agent: PHP Script\r\n";
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header .= "Content-Length: ".strlen($vars)."\r\n";
			$header .= "Connection: close\r\n\r\n";
			$header .= $vars;
			$fp = @stream_socket_client( 'tlsv1.2://'.$interface['host'].':443', $errno, $errstr, 30 );
			if( !$fp ){
				usces_log( 'digitalcheck card : TLS(v1.2) Error', 'acting_transaction.log' );
				$fp = fsockopen('ssl://'.$interface['host'],443,$errno,$errstr,30);
				if( !$fp ){
					usces_log('digitalcheck card : SSL Error', 'acting_transaction.log');
					$log = array( 'acting'=>'digitalcheck_card', 'key'=>$_POST['SID'], 'result'=>'SSL/TLS ERROR ('.$errno.')', 'data'=>array($errstr) );
					usces_save_order_acting_error( $log );
					header( "location: ".USCES_CART_URL.$delim.'acting=digitalcheck_card&acting_return=0' );
				}
			}

			if( $fp ) {
				fwrite( $fp, $header );
				$page = '';
				while( !feof($fp) ) {
					$line = fgets( $fp, 1024 );
					if( strcmp($line, "\r\n") == 0 ) {
						$headerdone = true;
					} elseif( $headerdone ) {
						$page .= $line;
					}
				}
				fclose($fp);
				$lines = explode("\n", $page);
				if( false !== strpos( $lines[0], 'OK') ) {
					usces_log('digitalcheck card entry data (acting_processing) : '.print_r($entry, true), 'acting_transaction.log');
					$args = '&SID='.$_POST['SID'].'&FUKA='.$acting_flg;
					header( "location: ".USCES_CART_URL.$delim.'acting=digitalcheck_card&acting_return=1'.$args );
				} else {
					usces_log('digitalcheck card : Certification Error : '.$page, 'acting_transaction.log');
					$log = array( 'acting'=>'digitalcheck_card', 'key'=>$_POST['SID'], 'result'=>'CERTIFICATION ERROR', 'data'=>$lines );
					usces_save_order_acting_error( $log );
					header( "location: ".USCES_CART_URL.$delim.'acting=digitalcheck_card&acting_return=0' );
				}
			}
			exit;
		} else if( $acting_flg == 'acting_digitalcheck_conv' ) {
			if( isset($_REQUEST['STORE']) and '99' != $_REQUEST['STORE'] ) {
				$res = $this->order_processing();
				if( 'ordercompletion' == $res ) {
					$table_meta_name = $wpdb->prefix."usces_order_meta";
					$mquery = $wpdb->prepare("SELECT order_id FROM $table_meta_name WHERE meta_key = %s AND meta_value = %s", 'SID', $_REQUEST['SID'] );
					$order_id = $wpdb->get_var($mquery);
					if( $order_id ) {
						$data = array( "settltment_status" => __("Failure",'usces'), "settltment_errmsg" => __("Settlement was not completed.",'usces') );
						$this->set_order_meta_value( 'acting_digitalcheck_conv', serialize( $data ), $order_id );
						$this->set_order_meta_value( 'wc_trans_id', $_REQUEST['SID'], $order_id );
					}
					$this->cart->crear_cart();
					$acting_opts = $this->options['acting_settings']['digitalcheck'];
					header( "location: ".$acting_opts['send_url_conv'].'?acting=digitalcheck_conv'.$post_query );
					exit;
				} else {
					usces_log('digitalcheck conv : order processing error', 'acting_transaction.log');
					$log = array( 'acting'=>'digitalcheck_conv', 'key'=>$_REQUEST['SID'], 'result'=>'ORDER DATA REGISTERED ERROR', 'data'=>$_REQUEST );
					usces_save_order_acting_error( $log );
					header( "location: ".USCES_CART_URL.$delim.'acting=digitalcheck_conv&acting_return=0' );
				}
			} else {
				$acting_opts = $this->options['acting_settings']['digitalcheck'];
				header( "location: ".$acting_opts['send_url_conv'].'?acting=digitalcheck_conv'.$post_query );
				exit;
			}

		} else if( $acting_flg == 'acting_veritrans_card' or $acting_flg == 'acting_veritrans_conv' ) {
			$acting_opts = $this->options['acting_settings']['veritrans'];
			$acting = substr( $acting_flg, 7 );
			$dummy_payment_flag = ( 'public' == $acting_opts['ope'] ) ? '0' : '1';
			$order_id = isset( $_POST['ORDER_ID'] ) ? $_POST['ORDER_ID'] : '';
			$regist_url = ( defined('VERITRANS_SHA2_TEST') ) ? "https://sair.veritrans.co.jp/web/commodityRegist.action" : $acting_opts['regist_url'];
			$url = parse_url( $regist_url );
			$path = empty($url['path']) ? '/' : $url['path'];

			$postdata = $post_query;
			if( 'acting_veritrans_card' == $acting_flg ) {
				$card_capture_flag = ( 'capture' == $acting_opts['card_capture_flag'] ) ? '1' : '0';
				$postdata .= '&CARD_CAPTURE_FLAG='.$card_capture_flag;
			}
			if( 'acting_veritrans_conv' == $acting_flg ) {
				$postdata .= '&NAME1='.urlencode( mb_substr( mb_convert_kana( $entry['customer']['name1'], 'ASKV', 'UTF-8' ), 0, 10, 'UTF-8' ) );
				$postdata .= '&NAME2='.urlencode( mb_substr( mb_convert_kana( $entry['customer']['name2'], 'ASKV', 'UTF-8' ), 0, 10, 'UTF-8' ) );
				if( !empty($entry['customer']['name3']) ) {
					$kana1 = mb_substr( mb_convert_kana( $entry['customer']['name3'], 'ASKV', 'UTF-8' ), 0, 10, 'UTF-8' );
					mb_regex_encoding( 'UTF-8' );
					if( mb_ereg("^[ア-ン゛゜ァ-ォャ-ョー]+$", $kana1) )
						$postdata .= '&KANA1='.urlencode( $kana1 );
				}
				if( !empty($entry['customer']['name4']) ) {
					$kana2 = mb_substr( mb_convert_kana( $entry['customer']['name4'], 'ASKV', 'UTF-8' ), 0, 10, 'UTF-8' );
					mb_regex_encoding( 'UTF-8' );
					if( mb_ereg("^[ア-ン゛゜ァ-ォャ-ョー]+$", $kana2) )
						$postdata .= '&KANA2='.urlencode( $kana2 );
				}
				$postdata .= '&TELEPHONE_NO='.str_replace( '-', '', $entry['customer']['tel'] );
				if( 1 < (int)$acting_opts['conv_timelimit'] and (int)$acting_opts['conv_timelimit'] <= 60 ) {
					$timelimit = date( 'Ymd', strtotime('+'.$acting_opts['conv_timelimit'].' days') );
					$postdata .= '&TIMELIMIT_OF_PAYMENT='.$timelimit;
				}
			}
			if( 'on' == $acting_opts['mailaddress'] ) {
				$postdata .= '&MAILADDRESS='.$entry['customer']['mailaddress1'];
			}
			$postdata .= '&MERCHANT_ID='.$acting_opts['merchant_id'];
			$postdata .= '&SESSION_ID='.session_id();
			$postdata .= '&FINISH_PAYMENT_RETURN_URL='.urlencode( USCES_CART_URL.$delim.'acting='.$acting.'&acting_return=1&result=1' );
			$postdata .= '&UNFINISH_PAYMENT_RETURN_URL='.urlencode( USCES_CART_URL.$delim.'acting='.$acting.'&confirm=1' );
			$postdata .= '&ERROR_PAYMENT_RETURN_URL='.urlencode( USCES_CART_URL.$delim.'acting='.$acting.'&acting_return=0' );
			$postdata .= '&FINISH_PAYMENT_ACCESS_URL='.urlencode( USCES_CART_URL.$delim.'acting='.$acting );
			$postdata .= '&DUMMY_PAYMENT_FLAG='.$dummy_payment_flag;

			$postlength = strlen( $postdata );

			$request  = "POST ".$path." HTTP/1.1"."\r\n";
			$request .= "Host: ".$url['host']."\r\n";
			$request .= "User-Agent: HttpRequest Powered by ".phpversion()."\r\n";
			$request .= "Connection: close"."\r\n";
			$request .= "Accept-Language: ja"."\r\n";
			$request .= "Content-Type: application/x-www-form-urlencoded"."\r\n";
			$request .= "Content-Length: ".$postlength."\r\n\r\n";
			$request .= $postdata;

			$code = 0;
			$resBody = "";
			$con = @stream_socket_client( 'tlsv1.2://'.$url['host'].':443', $errno, $errstr, 30 );
			if( !$con ) {
				usces_log( 'Veritrans : TLS(v1.2) Error', 'acting_transaction.log' );
				$con = @fsockopen( 'ssl://'.$url['host'], 443, $errno, $errstr, 30 );
				if( !$con ) {
					usces_log( 'Veritrans : SSL Error', 'acting_transaction.log' );
					$log = array( 'acting'=>$acting, 'key'=>$order_id, 'result'=>'SSL/TLS ERROR ('.$errno.')', 'data'=>array($errstr) );
					usces_save_order_acting_error( $log );
					header( "location: ".USCES_CART_URL.$delim.'acting='.$acting_flg.'&acting_return=0' );
					exit;
				}
			}

			if( $con ) {
				$ret = fwrite( $con, $request );
				if( $ret == strlen($request) ) {
					$res = $this->readResponse( $con );
					$code = $res['Code'];
					$resBody = $res['Body'];

				} else {
					usces_log( 'Veritrans Write NG: Sent:'.strlen($request).' Send:'.$ret, "acting_transaction.log" );
				}
				fclose( $con );
			}

			// 正常終了（200 OK）が返ったか
			if( intval($code) == 200 ) {
				$merchantKey = null;
				$browserKey = null;
				$scd = null;
				$error_message = null;

				// レスポンスデータからマーチャントキー、ブラウザキーを取得
				$bodyLine = explode( "\n", $resBody );
				foreach( $bodyLine as $line ) {
					if( preg_match( '/^MERCHANT_ENCRYPTION_KEY=(.+)/', $line, $match ) ) {
						$merchantKey = $match[1];
					} elseif( preg_match( '/^BROWSER_ENCRYPTION_KEY=(.+)/', $line, $match ) ) {
						$browserKey = $match[1];
					} elseif( preg_match('/^SCD=(.+)/', $line, $match ) ) {
						$scd = $match[1];
					} elseif( preg_match( '/^ERROR_MESSAGE=(.+)/', $line, $match ) ) {
						$error_message = $match[1];
					}
				}

				// 両方取れたらOK。SCDはカードでセキュリティコードが入力された場合のみ
				if( !is_null($merchantKey) && !is_null($browserKey) ) {
					$getdata  = '?MERCHANT_ID='.$acting_opts['merchant_id'];
					$getdata .= '&ORDER_ID='.$order_id;
					$getdata .= '&BROWSER_ENCRYPTION_KEY='.urlencode($browserKey);
					$payment_url = ( defined('VERITRANS_SHA2_TEST') ) ? "https://sair.veritrans.co.jp/web/paymentStart.action" : $acting_opts['payment_url'];
					header( "location: ".$payment_url.$getdata );

				} else {
					if( !is_null($error_message) ) {
						usces_log( "Veritrans AWeb:".$error_message, "acting_transaction.log" );
						$log = array( 'acting'=>$acting, 'key'=>$order_id, 'result'=>$code, 'data'=>$bodyLine );
						usces_save_order_acting_error( $log );
					}
					header( "location: ".USCES_CART_URL.$delim.'acting='.$acting_flg.'&acting_return=0' );
				}

			} else {
				usces_log( "Veritrans Response NG: ".$resBody, "acting_transaction.log" );
				$bodyLine = explode( "\n", $resBody );
				$log = array( 'acting'=>$acting, 'key'=>$order_id, 'result'=>$code, 'data'=>$bodyLine );
				usces_save_order_acting_error( $log );
				header( "location: ".USCES_CART_URL.$delim.'acting='.$acting_flg.'&acting_return=0' );
			}
			exit;
		}
		do_action('usces_action_acting_processing', $acting_flg, $post_query);
		$acting_status = apply_filters( 'usces_filter_acting_processing', $acting_flg, $post_query, $acting_status );
		return $acting_status;
	}

	private function readResponse( $fp ) {
		$res = array( 'Status'=>'', 'Version'=>'', 'Code'=>0, 'Message'=>'', 'Headers'=>array(), 'Body'=>'' );

		// HTTPステータスの読み込み
		$line = $this->readLine( $fp );
		if( preg_match( '/^(HTTP\/1\.[0-9x]+)\s+([0-9]+)\s+(.+)/i', $line, $match ) == 0 ) {
			return $res;
		}
		$res['Status'] = $line;
		$res['Version'] = $match[1];
		$res['Code'] = $match[2];
		$res['Message'] = $match[3];

		// レスポンスヘッダの読み込み
		while( !feof($fp) ) {
			$line = $this->readLine( $fp );
			if( $line != '' ) {
				list( $hname, $hvalue ) = explode( ':', $line, 2 );
				$res['Headers'][strtolower($hname)] = ltrim($hvalue);
			} else {
				break;
			}
		}
		// レスポンスデータの読み込み
		while( !feof($fp) ) {
			$data = $this->readLine( $fp )."\n";
			if( '' == $data ) {
				break;
			}
			$res['Body'] .= $data;
		}
		return $res;
	}

	private function readLine( $fp ) {
		if( !$fp ) {
			return '';
		}
		// レスポンスデータを受信
		$line = null;
		while( !feof($fp) ) {
			$line .= @fgets( $fp, 4096 );
			if( substr($line, -1) == "\n" ) {
				return rtrim( $line, "\r\n" );
			}
		}
		return $line;
	}

	function inquiry_processing() {

		$mail_res = usces_send_inquirymail();

		if ( $mail_res )
			return 'inquiry_comp';
		else
			return 'inquiry_error';
	}

	function lastprocessing() {

		if ( $this->page == 'ordercompletion' )
			$this->cart->crear_cart();

		do_action( 'usces_action_lastprocessing' );

		unset($_SESSION['usces_singleitem']);

	}

	function is_item_zaiko( $post_id, $sku_code ) {
		$res        = false;
		$status_num = false;
		$zaiko_num  = false;
		if ( ! empty( $post_id ) ) {
			$sku_code            = (string) $sku_code;
			$status_num          = $this->getItemZaikoStatusId( $post_id, $sku_code );
			$zaiko_num           = $this->getItemZaikoNum( $post_id, $sku_code );
			$itemOrderAcceptable = $this->getItemOrderAcceptable( $post_id );
			if ( $itemOrderAcceptable != 1 ) {
				if ( false !== $zaiko_num
					&& ( 0 < (int) $zaiko_num || WCUtils::is_blank( $zaiko_num ) )
					&& false !== $status_num
					&& 2 > (int) $status_num ) {
					$res = true;
				} else {
					$res = false;
				}
			} else {
				if ( false !== $status_num
					&& 2 > (int) $status_num ) {
					$res = true;
				} else {
					$res = false;
				}
			}
		}
		return apply_filters( 'usces_is_item_zaiko', $res, $post_id, $sku_code, $status_num, $zaiko_num );
	}

	// function for the cart ***********************************************************
	function get_total_price( $cart = array() ) {
		if( empty($cart) )
			$cart = $this->cart->get_cart();

		$total_price = 0;

		if( !empty($cart) ) {
			$cart_count = ( is_array( $cart ) ) ? count( $cart ) : 0;
			for($i=0; $i<$cart_count; $i++) {
				$quantity = (float)$cart[$i]['quantity'];
				$skuPrice = (float)$cart[$i]['price'];

				$total_price += ($skuPrice * $quantity);
			}
		}
		return apply_filters( 'usces_filter_get_total_price', $total_price, $cart);
	}

	function get_total_quantity( $cart = array() ) {
		if( empty($cart) )
			$cart = $this->cart->get_cart();

		$total_quantity = 0;

		if( !empty($cart) ) {
			$cart_count = ( is_array( $cart ) ) ? count( $cart ) : 0;
			for($i=0; $i<$cart_count; $i++) {
				$total_quantity += (float)$cart[$i]['quantity'];
			}
		}
		return $total_quantity;
	}

	function get_order_point( $mem_id = '', $display_mode = '', $cart = array() ) {
		if( $mem_id == '' || $this->options['membersystem_state'] == 'deactivate' || $this->options['membersystem_point'] == 'deactivate') return 0;

		if ( empty($cart) )
			$cart = $this->cart->get_cart();

		if ( empty($display_mode) )
			$display_mode = $this->options['display_mode'];

		$point = 0;
		$total = $this->get_total_price( $cart );
		if ( $display_mode == 'Promotionsale' ) {
			if ( $this->options['campaign_privilege'] == 'discount' ) {
				foreach ( $cart as $rows ) {
					$cats = $this->get_post_term_ids($rows['post_id'], 'category');
					if ( ! in_array( $this->options['campaign_category'], $cats ) ) {
						$product = wel_get_product( $rows['post_id'] );
						$rate    = (float) $product['itemPointrate'];
						$price   = (float) $rows['price'] * (float) $rows['quantity'];
						$point   = (float) sprintf( '%.3f', $point + ( $price * $rate / 100 ) );
					}
				}
			} elseif ( $this->options['campaign_privilege'] == 'point' ) {
				foreach ( $cart as $rows ) {
					$product = wel_get_product( $rows['post_id'] );
					$rate    = (float) $product['itemPointrate'];
					$price   = (float)$rows['price'] * (float)$rows['quantity'];
					$cats    = $this->get_post_term_ids($rows['post_id'], 'category');
					if ( in_array( $this->options['campaign_category'], $cats ) ) {
						$point = (float) sprintf( '%.3f', $point + ( $price * $rate / 100 * (float) $this->options['privilege_point'] ) );
					} else {
						$point = (float) sprintf( '%.3f', $point + ( $price * $rate / 100 ) );
					}
				}
			}
		} else {
			foreach ( $cart as $rows ) {
				$product = wel_get_product( $rows['post_id'] );
				$rate    = (float) $product['itemPointrate'];
				$price   = (float) $rows['price'] * (float) $rows['quantity'];
				$point   = (float) sprintf( '%.3f', $point + ( $price * $rate / 100 ) );
			}
		}


		$entry = $this->cart->get_entry();
		$use_point = isset( $entry['order']['usedpoint'] ) ? (int)$entry['order']['usedpoint'] : 0;
		if( 0 < $use_point ) {
			$point = (float)sprintf('%.3f', $point - ( $point * $use_point / $total ) );
			$point = ceil( $point );
			if( 0 > $point ) {
				$point = 0;
			}
		}else{
			if( 0 < $point ) {
				$point = ceil( $point );
			}
		}

		return apply_filters( 'usces_filter_get_order_point', $point, $mem_id, $display_mode, $cart );
	}

	function get_order_discount( $display_mode = '', $cart = array() ) {
		if ( empty($cart) )
			$cart = $this->cart->get_cart();

		if ( empty($display_mode) )
			$display_mode = $this->options['display_mode'];

		$discount = 0;
		if ( $display_mode == 'Promotionsale' ) {
			if ( $this->options['campaign_privilege'] == 'discount' ){
				if( 0 === (int)$this->options['campaign_category'] ){
					$total = $this->get_total_price( $cart );
					$discount = (float)sprintf('%.3f', $total * (float)$this->options['privilege_discount'] / 100 );
				}else{
					foreach($cart as $cart_row){
						if( in_category((int)$this->options['campaign_category'], $cart_row['post_id']) ){
							$discount += (float)sprintf( '%.3f', (float)$cart_row['price'] * (float)$cart_row['quantity'] * (float)$this->options['privilege_discount'] / 100 );
						}
					}
				}
				if( 0 != $discount ) {
					$decimal = $this->get_currency_decimal();
					if( 0 == $decimal ) {
						$discount = ceil( $discount );
					} else {
						$decipad = (int)str_pad( '1', $decimal+1, '0', STR_PAD_RIGHT );
						$discount = ceil( $discount * $decipad ) / $decipad;
					}
					$discount = $discount * -1;
				}
			}else if ( $this->options['campaign_privilege'] == 'point' ){
				$discount = 0;
			}
		}
		$discount = apply_filters('usces_order_discount', $discount, $cart);
		return $discount;
	}

	function getShippingCharge( $pref, $cart = array(), $entry = array() ) {

		if( empty($cart) )
			$cart = $this->cart->get_cart();

		if( empty($entry) )
			$entry = $this->cart->get_entry();

		if( function_exists('dlseller_have_shipped') && !dlseller_have_shipped() ){
			$charge = 0;
			$charge = apply_filters('usces_filter_getShippingCharge', $charge, $cart, $entry);
			return $charge;
		}

		//配送方法ID
		$d_method_id = $entry['order']['delivery_method'];
		//配送方法index
		$d_method_index = $this->get_delivery_method_index($d_method_id);
		//送料ID
		$fixed_charge_id = ( isset($this->options['delivery_method'][$d_method_index]['charge']) ) ? $this->options['delivery_method'][$d_method_index]['charge'] : -1;
		$individual_quant = 0;
		$total_quant = 0;
		$charges = array();
		$individual_charges = array();
		$country = (isset($entry['delivery']['country']) && !empty($entry['delivery']['country'])) ? $entry['delivery']['country'] : $entry['customer']['country'];

		foreach ( $cart as $rows ) {

			if( -1 == $fixed_charge_id ){
				//商品送料ID
				$s_charge_id = $this->getItemShippingCharge($rows['post_id']);
				//商品送料index
				$s_charge_index = $this->get_shipping_charge_index($s_charge_id);
				$s_charge = isset($this->options['shipping_charge'][$s_charge_index][$country][$pref]) ? (float)$this->options['shipping_charge'][$s_charge_index][$country][$pref] : 0;
			}else{

				$s_charge_index = $this->get_shipping_charge_index($fixed_charge_id);
				$s_charge = isset($this->options['shipping_charge'][$s_charge_index][$country][$pref]) ? (float)$this->options['shipping_charge'][$s_charge_index][$country][$pref] : 0;
			}

			if($this->getItemIndividualSCharge($rows['post_id'])){
				$individual_quant += (float)$rows['quantity'];
				$individual_charges[] = (float)$rows['quantity'] * $s_charge;
			}else{
				$charges[] = $s_charge;
			}
			$total_quant += $rows['quantity'];
		}

		if( count($charges) > 0 ){
			rsort($charges);
			$max_charge = $charges[0];
			$charge = $max_charge + array_sum($individual_charges);
		}else{
			$charge = array_sum($individual_charges);
		}

		$charge = apply_filters('usces_filter_getShippingCharge', $charge, $cart, $entry);

		return $charge;
	}

	function getCODFee($payment_name, $amount_by_cod, $current_entries) {
		global $usces_entries;

		$payments = $this->getPayments($payment_name);
		if( !isset($payments['settlement']) || 'COD' != $payments['settlement'] ){
			$fee = 0;

		}else if( 'change' != $this->options['cod_type'] ){
			$fee = isset($this->options['cod_fee']) ? $this->options['cod_fee'] : 0;

		}else{
			$materials = array(
				'total_items_price' => $current_entries['order']['total_items_price'],
				'discount' => ( isset($current_entries['order']['discount']) ) ? $current_entries['order']['discount'] : 0,
				'shipping_charge' => ( isset($current_entries['order']['shipping_charge']) ) ? $current_entries['order']['shipping_charge'] : 0,
				'cod_fee' => 0,
				'use_point' => 0,
			);
			$price = $amount_by_cod + $this->getTax( $amount_by_cod, $materials );
			if( $price <= $this->options['cod_first_amount'] ){
				$fee = $this->options['cod_first_fee'];

			}else if( isset($this->options['cod_amounts']) ){
				$last = count( $this->options['cod_amounts'] ) - 1;
				if( $price > $this->options['cod_amounts'][$last] ){
					$fee = $this->options['cod_end_fee'];

				}else{
					$fee = 0;
					foreach( $this->options['cod_amounts'] as $key => $value ){
						if( $price <= $value ){
							$fee = $this->options['cod_fees'][$key];
							break;
						}
					}
				}
			}else{
				$fee = $this->options['cod_end_fee'];
			}
		}
		$fee = apply_filters('usces_filter_getCODFee', $fee, $payment_name, $amount_by_cod, $current_entries);
		return $fee;
	}

	function getTax( $total, $materials = array() ) {
		global $usces_settings;

		if( isset( $this->options['tax_display'] ) && 'deactivate' == $this->options['tax_display'] ) {
			return 0;
		}
		if( empty( $this->options['tax_rate'] ) ) {
			return 0;
		}
		//if( !empty( $materials ) ) {
		//	extract( $materials );//need( 'total_items_price', 'shipping_charge', 'discount', 'cod_fee', 'use_point' )
		//}

		if( $this->is_reduced_taxrate() ) {
			if( 'include' == $this->options['tax_mode'] ) {
				return 0;
			}
			if( !empty( $materials ) ) {
				extract( $materials );
				if( empty( $carts ) ) {
					$carts = array();
					$materials = compact( 'total_items_price', 'shipping_charge', 'discount', 'cod_fee', 'use_point', 'carts' );
				}
			}

			$usces_tax = Welcart_Tax::get_instance();
			$usces_tax->get_order_tax( $materials );
			$tax = apply_filters( 'usces_filter_getTax', $usces_tax->tax, $materials );

		} else {

			if( empty( $materials ) ) {

				if( 'include' == $this->options['tax_mode'] ) {
					$tax = (float)sprintf( '%.3f', (float)$total * (float)$this->options['tax_rate'] / ( 100 + (float)$this->options['tax_rate'] ) );
				} else {
					$tax = (float)sprintf( '%.3f', (float)$total * (float)$this->options['tax_rate'] / 100 );
				}

			} else {
				if( 'include' == $this->options['tax_mode'] ) {
					return 0;
				}

				extract( $materials );//need( 'total_items_price', 'shipping_charge', 'discount', 'cod_fee', 'use_point' )

				if( 1 == $this->options['point_coverage'] ) {
					if( 'products' == $this->options['tax_target'] ) {
						$total = (float)$total_items_price + (float)$discount;
					} else {
						$total = (float)$total_items_price + (float)$discount + (float)$shipping_charge + (float)$cod_fee;
					}
				} else {
					if( 'products' == $this->options['tax_target'] ) {
						$total = (float)$total_items_price + (float)$discount;
					} else {
						if( empty($use_point) ) $use_point = 0;
						$total = (float)$total_items_price + (float)$discount - (int)$use_point + (float)$shipping_charge + (float)$cod_fee;
					}
				}
				$total = apply_filters( 'usces_filter_getTax_total', $total, $materials );

				$tax = (float)sprintf( '%.3f', (float)$total * (float)$this->options['tax_rate'] / 100 );
			}

			$tax = usces_tax_rounding_off( $tax );
			$tax = apply_filters( 'usces_filter_getTax', $tax, $materials );
		}

		return $tax;
	}

	function set_cart_fees( $member, $entries ) {
		global $usces_entries;

		$carts = $this->cart->get_cart();
		$entries = $this->cart->get_entry();
		$total_items_price = $this->get_total_price();
		$entries['order']['total_items_price'] = $total_items_price;

		if( empty( $this->options['postage_privilege'] ) || $total_items_price < $this->options['postage_privilege'] ) {
			$shipping_charge = $this->getShippingCharge( $entries['delivery']['pref'], $carts, $entries );
		} else {
			$shipping_charge = 0;
		}
		$shipping_charge = apply_filters( 'usces_filter_set_cart_fees_shipping_charge', $shipping_charge, $carts, $entries );
		$entries['order']['shipping_charge'] = $shipping_charge;

		$payments = $this->getPayments( $entries['order']['payment_name'] );
		$use_point = ( isset( $entries['order']['usedpoint'] ) ) ? (int)$entries['order']['usedpoint'] : 0;
		if( $this->is_reduced_taxrate() ) {
			$usces_tax = Welcart_Tax::get_instance();
			$discount = $usces_tax->get_order_discount( $carts );
			$entries['order']['discount'] = $discount;
			$amount_by_cod = $total_items_price - $use_point + $discount;
			if ( 'all' == usces_is_fee_subject() ) {
				$amount_by_cod += $shipping_charge;
			}
			$amount_by_cod = apply_filters( 'usces_filter_set_cart_fees_amount_by_cod', $amount_by_cod, $entries, $total_items_price, $use_point, $discount, $shipping_charge );
			$cod_fee = $this->getCODFee( $entries['order']['payment_name'], $amount_by_cod, $entries );
			$cod_fee = apply_filters( 'usces_filter_set_cart_fees_cod', $cod_fee, $entries, $total_items_price, $use_point, $discount, $shipping_charge, $amount_by_cod );
			$entries['order']['cod_fee'] = $cod_fee;

			if( 'include' == $this->options['tax_mode'] ) {
				$tax = 0;
			} else {
				$materials = compact( 'member', 'entries', 'carts', 'total_items_price', 'shipping_charge', 'payments', 'discount', 'cod_fee', 'use_point' );
				$usces_tax->get_order_tax( $materials );
				$tax = apply_filters( 'usces_filter_getTax', $usces_tax->tax, $materials );
			}

			$total_price = $total_items_price - $use_point + $discount + $shipping_charge + $cod_fee;
			if( $total_price < 0 ) $total_price = 0;
			$total_price = apply_filters( 'usces_filter_set_cart_fees_total_price', $total_price, $total_items_price, $use_point, $discount, $shipping_charge, $cod_fee );

		} else {
			$discount = $this->get_order_discount( NULL, $carts );
			$amount_by_cod = $total_items_price - $use_point + $discount;
			if ( 'all' == usces_is_fee_subject() ) {
				$amount_by_cod += $shipping_charge;
			}
			$amount_by_cod = apply_filters( 'usces_filter_set_cart_fees_amount_by_cod', $amount_by_cod, $entries, $total_items_price, $use_point, $discount, $shipping_charge );
			$cod_fee = $this->getCODFee( $entries['order']['payment_name'], $amount_by_cod, $entries );
			$cod_fee = apply_filters( 'usces_filter_set_cart_fees_cod', $cod_fee, $entries, $total_items_price, $use_point, $discount, $shipping_charge, $amount_by_cod );
			$entries['order']['cod_fee'] = $cod_fee;

			$total_price = $total_items_price - $use_point + $discount + $shipping_charge + $cod_fee;
			if( $total_price < 0 ) $total_price = 0;
			$total_price = apply_filters( 'usces_filter_set_cart_fees_total_price', $total_price, $total_items_price, $use_point, $discount, $shipping_charge, $cod_fee );

			$materials = compact( 'member', 'entries', 'carts', 'total_items_price', 'shipping_charge', 'payments', 'discount', 'cod_fee', 'use_point' );
			$tax = $this->getTax( $total_price, $materials );
		}

		if( 'exclude' == $this->options['tax_mode'] ) {
			if( 0 < $use_point ) {
				$total_full_price = $total_items_price - $use_point + $discount + $shipping_charge + $cod_fee + $tax;
				if( $total_full_price < 0 ) $total_full_price = 0;
			} else {
				$total_full_price = $total_price + $tax;
			}
		} else {
			$total_full_price = $total_price;
		}

		$total_full_price = apply_filters( 'usces_filter_set_cart_fees_total_full_price', $total_full_price, $total_items_price, $use_point, $discount, $shipping_charge, $cod_fee );
		$entries['order']['total_full_price'] = $total_full_price;

		$get_point = $this->get_order_point( (isset($member['ID']) ? $member['ID'] : null) );

		$array = array(
				'total_items_price' => $total_items_price,
				'total_price' => $total_price,
				'total_full_price' => $total_full_price,
				'getpoint' => $get_point,
				'usedpoint' => $use_point,
				'discount' => $discount,
				'shipping_charge' => $shipping_charge,
				'cod_fee' => $cod_fee,
				'tax' => $tax
				);
		$this->cart->set_order_entry( $array );
		$usces_entries = $this->cart->get_entry();
	}

	function getPayments( $payment_name ) {
		$init = array(
			'id'          => null,
			'name'        => null,
			'explanation' => null,
			'settlement'  => null,
			'module'      => null,
			'sort'        => null,
			'use'         => null
		);

		if( '#none#' == $payment_name )
			return $init;

		$payments = usces_get_system_option( 'usces_payment_method', 'name' );
		if( isset($payments[$payment_name]) )
			return $payments[$payment_name];
		return $init;
	}

	function is_maintenance() {
		if ( $this->options['display_mode'] == 'Maintenancemode' )
			return true;
		else
			return false;
	}

	function get_member_history( $mem_id, $allow_filter = false ) {
		global $wpdb;
		$order_table = $wpdb->prefix . "usces_order";

		if ( is_user_logged_in() && is_admin() ) {

			$query   = $wpdb->prepare( "SELECT * FROM $order_table WHERE mem_id = %d ORDER BY order_date DESC", $mem_id );
			$query   = apply_filters( 'usces_filter_member_history_query', $query, $mem_id, $allow_filter );
			$results = $wpdb->get_results( $query );

		} elseif ( ! is_admin() ) {

			$cancel_query   = '%cancel%';
			$estimate_query = '%estimate%';
			$exclude_cancel = $this->usces_get_member_cookies( 'ord_ex_cancel' );
			if ( $allow_filter && 'on' === (string) $exclude_cancel ) {
				$condition = ' ( order_status NOT LIKE %s AND order_status NOT LIKE %s ) ';
			} else {
				$condition = ' ( order_status LIKE %s OR order_status NOT LIKE %s ) ';
			}

			$pur_date = $this->usces_get_member_cookies( 'pur-date' );
			if ( $allow_filter && $pur_date ) {
				$now         = current_time( 'timestamp' );
				$type_filter = 'date';
				if ( false !== strpos( $pur_date, 'y_' ) ) {
					$type_filter = 'year';
				} elseif ( false !== strpos( $pur_date, 'm_' ) ) {
					$type_filter = 'month';
				}
				if ( 'year' === $type_filter ) {
					$purchase_year = (int) str_replace( 'y_', '', $pur_date );
				} elseif ( 'month' === $type_filter ) {
					$month_ago                    = (int) str_replace( 'm_', '', $pur_date );
					$first_day_of_previous_months = strtotime( "first day of -{$month_ago} months", $now );
					$last_day_of_previous_months  = strtotime( "last day of -{$month_ago} months", $now );
					$purchase_date_from_string    = date( 'Y-m-d 00:00:00', $first_day_of_previous_months );
					$purchase_date_to_string      = date( 'Y-m-d 23:59:59', $last_day_of_previous_months );
				} else {
					$pur_date_val         = (int) str_replace( 'd_', '', $pur_date );
					$previous_date        = strtotime( "-{$pur_date_val} days", $now );
					$purchase_date_string = date( 'Y-m-d 00:00:00', $previous_date );
				}
				if ( 'year' === $type_filter ) {
					$query = $wpdb->prepare(
						"SELECT * FROM $order_table WHERE mem_id = %d AND YEAR(order_date) = %d AND {$condition} ORDER BY order_date DESC",
						$mem_id,
						$purchase_year,
						$cancel_query,
						$estimate_query
					);
				} elseif ( 'month' === $type_filter ) {
					$query = $wpdb->prepare(
						"SELECT * FROM $order_table WHERE mem_id = %d AND order_date >= %s AND order_date <= %s AND {$condition} ORDER BY order_date DESC",
						$mem_id,
						$purchase_date_from_string,
						$purchase_date_to_string,
						$cancel_query,
						$estimate_query
					);
				} else {
					$query = $wpdb->prepare(
						"SELECT * FROM $order_table WHERE mem_id = %d AND order_date >= %s AND {$condition} ORDER BY order_date DESC",
						$mem_id,
						$purchase_date_string,
						$cancel_query,
						$estimate_query
					);
				}
			} else {
				$query = $wpdb->prepare(
					"SELECT * FROM $order_table WHERE mem_id = %d AND {$condition} ORDER BY order_date DESC",
					$mem_id,
					$cancel_query,
					$estimate_query
				);
			}

			$query   = apply_filters( 'usces_filter_member_history_query_front', $query, $mem_id, $allow_filter );
			$results = $wpdb->get_results( $query );

		}

		$i   = 0;
		$res = array();

		foreach ( $results as $value ) {
			$cart              = usces_get_ordercartdata( $value->ID );
			$total_items_price = $this->get_total_price( $cart );

			$data = array(
				'ID'                         => $value->ID,
				'cart'                       => $cart,
				'condition'                  => unserialize( $value->order_condition ),
				'getpoint'                   => $value->order_getpoint,
				'usedpoint'                  => $value->order_usedpoint,
				'discount'                   => $value->order_discount,
				'shipping_charge'            => $value->order_shipping_charge,
				'payment_name'               => $value->order_payment_name,
				'cod_fee'                    => $value->order_cod_fee,
				'tax'                        => $value->order_tax,
				'total_items_price'          => $total_items_price,
				'order_status'               => $value->order_status,
				'date'                       => mysql2date( __( 'Y/m/d' ), $value->order_date ),
				'order_date'                 => $value->order_date,
				'order_delidue_date'         => $value->order_delidue_date,
				'order_delivery_method'      => $value->order_delivery_method,
				'order_delivery_method_name' => usces_delivery_method_name( $value->order_delivery_method, 'return' ),
				'order_delivery_date'        => $value->order_delivery_date,
				'order_modified'             => $value->order_modified,
			);
			$res[] = apply_filters( 'usces_filter_member_history_data', $data, $mem_id, $value, $cart, $total_items_price );
		}

		return $res;
	}

	function get_post_term_ids( $post_id, $taxonomy ){
		global $wpdb;
		$query = $wpdb->prepare("SELECT tt.term_id  FROM $wpdb->term_relationships AS `tr` 
									INNER JOIN $wpdb->term_taxonomy AS `tt` ON tt.term_taxonomy_id = tr.term_taxonomy_id 
									WHERE tt.taxonomy = %s AND tr.object_id = %d", $taxonomy, $post_id);
		$ids = $wpdb->get_col( $query );

		return $ids;

	}

	function get_tag_names($post_id) {
		global $wpdb;
		$tag = 'post_tag';
		$query = $wpdb->prepare("SELECT t.name  FROM $wpdb->term_relationships AS `tr` 
									INNER JOIN $wpdb->term_taxonomy AS `tt` ON tt.term_taxonomy_id = tr.term_taxonomy_id 
									INNER JOIN $wpdb->terms AS `t` ON t.term_id = tt.term_id 
									WHERE tt.taxonomy = %s AND tr.object_id = %d", $tag, $post_id);
		$names = $wpdb->get_col( $query );

		return apply_filters('usces_filter_get_tag_names', $names, $post_id);

	}

	function get_ID_byItemName( $item_code, $status = 'publish' ) {
		global $wpdb;

		$table = usces_get_tablename( 'usces_item' );
		$id = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT p.ID  FROM {$wpdb->posts} AS `p` 
				INNER JOIN {$table} AS `item` ON p.ID = item.post_id 
				WHERE p.post_status = %s AND item.itemCode = %s",
				$status,
				$item_code
			)
		);

		return $id;
	}

	function uscescv( $sessid, $flag ) {

		$chars = '';
		$i=0;
		$h=0;
		$usces_cookie = $this->get_cookie();
		if( isset($usces_cookie['id']) && !empty($usces_cookie['id']) ){
			$cid = $usces_cookie['id'];
		}elseif( isset($_SESSION['usces_cookieid']) && !empty($_SESSION['usces_cookieid']) ){
			$cid = $_SESSION['usces_cookieid'];
		}else{
			$cid = 0;
		}
		while($h<strlen($sessid)){
			if(0 == $i % 3){
				$chars .= substr($i, -1);
			}else{
				$chars .= substr($sessid, $h, 1);
				$h++;
			}
			$i++;
		}
		if( $flag ){
			$postfix = ( isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : 'REMOTE_ADDR';
			$postfix = apply_filters('usces_sessid_force', $postfix);
			$sessid = $chars . '_' . $postfix . '_' . $cid . '_A';
		}else{
			$sessid = $chars . '_' . apply_filters('usces_sessid_flag', 'acting') . '_' . $cid . '_A';
		}
		$sessid = urlencode(base64_encode($sessid));

		return $sessid;
	}

	function uscesdc( $sessid ) {
		$sessid = base64_decode(urldecode($sessid));
		list($sess, $addr, $cookieid, $none) = explode('_', $sessid, 4);
		$postfix = ( isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : 'REMOTE_ADDR';
		$postfix = apply_filters('usces_sessid_force', $postfix);
		if( 'acting' !== $addr && 'mobile' !== $addr && $postfix !== $addr ) {
			$sessid = '';
			return NULL;
		}
		$chars = '';
		$h=0;
		while($h<strlen($sess)){
			if(0 != $h % 3){
				$chars .= substr($sess, $h, 1);
			}
			$h++;
		}
		$sessid = $chars;

		return $sessid;

	}

	function get_visiter( $period ) {
		global $wpdb;
		$datestr = substr(get_date_from_gmt(gmdate('Y-m-d H:i:s', time())), 0, 10);
		$yearstr = substr($datestr, 0, 4);
		$monthstr = substr($datestr, 5, 2);
		$daystr = substr($datestr, 8, 2);
		if($period == 'today') {
			$date = $datestr;
			$today = $datestr;
		}else if($period == 'thismonth') {
			$date = date('Y-m-01');
			$today = $datestr;
		}else if($period == 'lastyear') {
			$date = date('Y-m-01', mktime(0, 0, 0, (int)$monthstr, 1, (int)$yearstr-1));
			$today = date('Y-m-01', mktime(0, 0, 0, (int)$monthstr, (int)$daystr, (int)$yearstr-1));
		}
		$table_name = $wpdb->prefix . 'usces_access';

		$query = $wpdb->prepare("SELECT SUM(acc_num1) AS `ct1`, SUM(acc_num2) AS `ct2` FROM $table_name WHERE acc_date >= %s AND acc_date <= %s", $date, $today);
		$res = $wpdb->get_row($query, ARRAY_A);

		if( $res == NULL )
			return 0;
		else
			return $res['ct1']+$res['ct2'];
	}

	function get_fvisiter( $period ) {
		global $wpdb;
		$datestr = substr(get_date_from_gmt(gmdate('Y-m-d H:i:s', time())), 0, 10);
		$yearstr = substr($datestr, 0, 4);
		$monthstr = substr($datestr, 5, 2);
		$daystr = substr($datestr, 8, 2);
		if($period == 'today') {
			$date = $datestr;
			$today = $datestr;
		}else if($period == 'thismonth') {
			$date = date('Y-m-01');
			$today = $datestr;
		}else if($period == 'lastyear') {
			$date = date('Y-m-01', mktime(0, 0, 0, (int)$monthstr, 1, (int)$yearstr-1));
			$today = date('Y-m-01', mktime(0, 0, 0, (int)$monthstr, (int)$daystr, (int)$yearstr-1));
		}
		$table_name = $wpdb->prefix . 'usces_access';

		$query = $wpdb->prepare("SELECT SUM(acc_num2) AS `ct` FROM $table_name WHERE acc_date >= %s AND acc_date <= %s", $date, $today);
		$res = $wpdb->get_var($query);

		if( $res == NULL )
			return 0;
		else
			return $res;
	}

	function get_order_num( $period ) {
		global $wpdb;
		$datestr = substr(get_date_from_gmt(gmdate('Y-m-d H:i:s', time())), 0, 10);
		$yearstr = substr($datestr, 0, 4);
		$monthstr = substr($datestr, 5, 2);
		$daystr = substr($datestr, 8, 2);
		if($period == 'today') {
			$date = date('Y-m-d 00:00:00', current_time('timestamp'));
			$today = date('Y-m-d 23:59:59', current_time('timestamp'));
		}else if($period == 'thismonth') {
			$date = date('Y-m-01 00:00:00', current_time('timestamp'));
			$today = date('Y-m-d 23:59:59', current_time('timestamp'));
		}else if($period == 'lastyear') {
			$date = date('Y-m-01 00:00:00', mktime(0, 0, 0, (int)$monthstr, 1, (int)$yearstr-1));
			$today = date('Y-m-d 23:59:59', mktime(0, 0, 0, (int)$monthstr+1, 0, (int)$yearstr-1));
		}
		$table_name = $wpdb->prefix . 'usces_order';

		$query = $wpdb->prepare("SELECT COUNT(ID) AS `ct` FROM $table_name WHERE order_date >= %s AND order_date <= %s AND 0 = LOCATE(%s, order_status) AND 0 = LOCATE(%s, order_status)", $date, $today, 'cancel', 'estimate');
		$res = $wpdb->get_var($query);

		if( $res == NULL )
			return 0;
		else
			return $res;
	}

	function get_order_amount( $period ) {
		global $wpdb;

		$cache_key = 'wel_order_amount_period_' . $period;

		$res = wp_cache_get( $cache_key );
		if ( false === $res ) {

			$datestr = substr(get_date_from_gmt(gmdate('Y-m-d H:i:s', time())), 0, 10);
			$yearstr = substr($datestr, 0, 4);
			$monthstr = substr($datestr, 5, 2);
			$daystr = substr($datestr, 8, 2);
			if($period == 'today') {
				$date = date('Y-m-d 00:00:00', current_time('timestamp'));
				$today = date('Y-m-d 23:59:59', current_time('timestamp'));
			}else if($period == 'thismonth') {
				$date = date('Y-m-01 00:00:00', current_time('timestamp'));
				$today = date('Y-m-d 23:59:59', current_time('timestamp'));
			}else if($period == 'lastyear') {
				$date = date('Y-m-01 00:00:00', mktime(0, 0, 0, (int)$monthstr, 1, (int)$yearstr-1));
				$today = date('Y-m-d 23:59:59', mktime(0, 0, 0, (int)$monthstr+1, 0, (int)$yearstr-1));
			}
			$table_name = $wpdb->prefix . 'usces_order';

			$query = $wpdb->prepare("SELECT 
										SUM(order_item_total_price) AS `price`, 
										SUM(order_usedpoint) AS `point`, 
										SUM(order_discount) AS `discount`, 
										SUM(order_shipping_charge) AS `shipping`, 
										SUM(order_cod_fee) AS `cod`, 
										SUM(order_tax) AS `tax` 
									FROM $table_name WHERE order_date >= %s AND order_date <= %s AND 0 = LOCATE(%s, order_status) AND 0 = LOCATE(%s, order_status)", $date, $today, 'cancel', 'estimate');
			$res = $wpdb->get_row($query, ARRAY_A);

			if ( null !== $res ) {
				wp_cache_set( $cache_key, $res );
			}
		}

		if( $res == NULL ){
			return 0;
		}else{
			return $res['price'] - $res['point'] + $res['discount'] + $res['shipping'] + $res['cod'] + $res['tax'];
		}
	}

	function is_status($need, $str){
		if( !is_string($str) || empty($str) ){
			$array = array();
		}else{
			$array = explode(',', $str);
		}
		return in_array($need, $array);
	}

	function make_status( $taio='', $receipt='', $admin='' ){
		$str = '';
		if($taio != '' && $taio != '#none#')
		 	$str .= $taio . ',';
		if($receipt != '' && $receipt != '#none#')
		 	$str .= $receipt . ',';
		if($admin != '' && $admin != '#none#')
		 	$str .= $admin . ',';
		return $str;
	}

	function get_memberid_by_email($email){
		global $wpdb;
		$table_name = usces_get_tablename( 'usces_member' );
		$query = $wpdb->prepare("SELECT ID FROM $table_name WHERE mem_email = %s", $email);
		$res = $wpdb->get_var($query);
		return $res;
	}

	function get_condition(){
		$order_conditions = array(
			'display_mode' => $this->options['display_mode'],
			'campaign_privilege' => $this->options['campaign_privilege'],
			'campaign_category' => $this->options['campaign_category'],
			'privilege_point' => $this->options['privilege_point'],
			'privilege_discount' => $this->options['privilege_discount'],
			'tax_display' => ( isset( $this->options['tax_display'] ) ) ? $this->options['tax_display'] : 'activate',
			'tax_mode' => $this->options['tax_mode'],
			'tax_target' => $this->options['tax_target'],
			'tax_rate' => $this->options['tax_rate'],
			'tax_method' => $this->options['tax_method'],
			'applicable_taxrate' => ( isset( $this->options['applicable_taxrate'] ) ) ? $this->options['applicable_taxrate'] : 'standard',
			'tax_rate_reduced' => ( isset( $this->options['tax_rate_reduced'] ) ) ? $this->options['tax_rate_reduced'] : $this->options['tax_rate'],
			'membersystem_state' => $this->options['membersystem_state'],
			'membersystem_point' => $this->options['membersystem_point'],
			'point_coverage' => $this->options['point_coverage'],
		);
		return $order_conditions;
	}

	function get_bestseller_ids( $days = "" ){
		global $wpdb;
		$datestr = substr(get_date_from_gmt(gmdate('Y-m-d H:i:s', time())), 0, 10);
		$yearstr = substr($datestr, 0, 4);
		$monthstr = substr($datestr, 5, 2);
		$daystr = substr($datestr, 8, 2);
		$res = array();
		$order_table_name = $wpdb->prefix . "usces_order";
		$where = "";
		if( empty($days) ){
			$days = 30;
		}
		$order_date = date('Y-m-d H:i:s', mktime(0, 0, 0, (int)$monthstr, ((int)$daystr-$days), (int)$yearstr));
		$where = " WHERE order_date >= '{$order_date}'";
		$query = "SELECT order_cart FROM {$order_table_name}" . $where;
		$dbres = $wpdb->get_col($query);
		if(!$dbres) return false;

		foreach((array)$dbres as $carts){
			$rows = unserialize($carts);
			foreach((array)$rows as $carts){
				if( 'publish' != get_post_status($carts['post_id']) )
					continue;

				$id = $carts['post_id'];
				$qu = $carts['quantity'];
				if(array_key_exists($id, $res)){
					$res[$id] = $res[$id] + $qu;
				}else{
					$res[$id] = $qu;
				}
			}
		}
		arsort($res);
		$results = array_keys($res);
		return $results;
	}

	function get_items_num(){
		global $wpdb;
		$res = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(ID) AS `ct` FROM {$wpdb->posts} 
				WHERE post_mime_type = %s AND post_type = %s AND post_status <> %s",
				'item',
				'post',
				'trash'
			)
		);

		return $res;
	}

	function is_gptekiyo( $post_id, $sku, $quant ) {
		$skus = $this->get_skus( $post_id, 'code' );
		if( !isset($skus[$sku]['gp']) || !$skus[$sku]['gp'] ) return false;

		$GpN1 = $this->getItemGpNum1($post_id);
		$GpN2 = $this->getItemGpNum2($post_id);
		$GpN3 = $this->getItemGpNum3($post_id);

		if( empty($GpN1) ) {

				return false;

		}else if( !empty($GpN1) && empty($GpN2) ) {

			if( $quant >= $GpN1 ) {
				return true;
			}else{
				return false;
			}

		}else if( !empty($GpN1) && !empty($GpN2) && empty($GpN3) ) {

			if( $quant >= $GpN2 ) {
				return true;
			}else if( $quant >= $GpN1 && $quant < $GpN2 ) {
				return true;
			}else{
				return false;
			}

		}else if( !empty($GpN1) && !empty($GpN2) && !empty($GpN3) ) {

			if( $quant >= $GpN3 ) {
				return true;
			}else if( $quant >= $GpN2 && $quant < $GpN3 ) {
				return true;
			}else if( $quant >= $GpN1 && $quant < $GpN2 ) {
				return true;
			}else{
				return false;
			}
		}
	}

	function get_available_delivery_method() {
		if($this->cart->num_row() > 0) {
			$cart = $this->cart->get_cart();
			$before_deli = array();
			$intersect = array();
			$integration = array();
			$temp = array();
			$in = 0;
			foreach($cart as $key => $row){
				$deli = $this->getItemDeliveryMethod($row['post_id']);
				if( empty($deli))
					continue;

				if( 0 === $in ){
					$intersect = $deli;
				}
				$intersect = array_intersect($deli, $intersect);
				$before_deli = $deli;
				foreach($deli as $value){
					$integration[] = $value;
				}
				$in++;
			}
			$integration = array_unique($integration);
			foreach($integration as $id){
				$index = $this->get_delivery_method_index($id);
				if( 0 <= $index ) {
					$temp[$index] = $id;
				}
			}
			ksort($temp);
			$force = array(array_shift($temp));

			if( empty($intersect) ){
				return $force;
			}else{
				return $intersect;
			}
		}
		return array();
	}

	function get_delivery_method_index($id) {
		$index = false;
		$delivery_method_count = ( $this->options['delivery_method'] && is_array( $this->options['delivery_method'] ) ) ? count( $this->options['delivery_method'] ) : 0;
		for($i=0; $i<$delivery_method_count; $i++){
			if( isset($this->options['delivery_method'][$i]['id']) && $this->options['delivery_method'][$i]['id'] === (int)$id ){
				$index = $i;
			}
		}
		if($index === false)
			return -1;
		else
			return $index;
	}

	function get_shipping_charge_index($id) {
		$index = false;
		$shipping_charge_count = ( $this->options['shipping_charge'] && is_array( $this->options['shipping_charge'] ) ) ? count( $this->options['shipping_charge'] ) : 0;
		for($i=0; $i<$shipping_charge_count; $i++){
			if( isset($this->options['shipping_charge'][$i]) && (int)$this->options['shipping_charge'][$i]['id'] == (int)$id ){
				$index = $i;
			}
		}
		if($index === false)
			return -1;
		else
			return $index;
	}

	function get_initial_data($xml){
		$buf = file_get_contents($xml);
		preg_match_all('@<page>.*?<post_title>(.*?)</post_title>.*?<post_status>(.*?)</post_status>.*?<post_name>(.*?)</post_name>.*?<post_content>(.*?)</post_content>.*?</page>@s', $buf, $match, PREG_SET_ORDER);
		return $match;
	}

	function getCurrencySymbol(){
		global $usces_settings;
		$cr = $this->options['system']['currency'];
		list($code, $decimal, $point, $seperator, $symbol) = $usces_settings['currency'][$cr];
		return $symbol;
	}

	function getCartItemName($post_id, $sku){
		$name_arr = array();
		$name_str = '';

		foreach($this->options['indi_item_name'] as $key => $value){
			if($value){
				$pos = (int)$this->options['pos_item_name'][$key];
				$ind = ($pos === 0) ? 'A' : $pos;
				switch($key){
					case 'item_name':
						$name_arr[$ind][$key] = $this->getItemName($post_id);
						break;
					case 'item_code':
						$name_arr[$ind][$key] = $this->getItemCode($post_id);
						break;
					case 'sku_name':
						$name_arr[$ind][$key] = $this->getItemSkuDisp($post_id, $sku);
						break;
					case 'sku_code':
						$name_arr[$ind][$key] = $sku;
						break;
				}
			}

		}
		ksort($name_arr);
		foreach($name_arr as $vals){
			foreach($vals as $key => $value){

				$name_str .= $value . ' ';
			}
		}

		$name_str = apply_filters('usces_admin_order_item_name_filter', $name_str, $post_id, $sku);

		return trim($name_str);
	}

	function getCartItemName_byOrder($cart_row){
		$name_arr = array();
		$name_str = '';

		foreach($this->options['indi_item_name'] as $key => $value){
			if($value){
				$pos = (int)$this->options['pos_item_name'][$key];
				$ind = ($pos === 0) ? 'A' : $pos;
				switch($key){
					case 'item_name':
						$name_arr[$ind][$key] = $cart_row['item_name'];
						break;
					case 'item_code':
						$name_arr[$ind][$key] = $cart_row['item_code'];
						break;
					case 'sku_name':
						$name_arr[$ind][$key] = $cart_row['sku_name'];
						break;
					case 'sku_code':
						$name_arr[$ind][$key] = $cart_row['sku_code'];
						break;
				}
			}

		}
		ksort($name_arr);
		foreach($name_arr as $vals){
			foreach($vals as $key => $value){

				$name_str .= $value . ' ';
			}
		}

		$name_str = apply_filters('usces_filter_item_mame_by_order', $name_str, $cart_row);

		return trim($name_str);
	}

	function set_reserve_pre_order_id(){
		$entry = $this->cart->get_entry();
		$id = ( isset($entry['reserve']['pre_order_id']) && !empty($entry['reserve']['pre_order_id']) ) ? $entry['reserve']['pre_order_id'] : uniqid('');
		$this->cart->set_pre_order_id($id);
	}

	function get_current_pre_order_id(){
		$entry = $this->cart->get_entry();
		$id = ( isset($entry['reserve']['pre_order_id']) && !empty($entry['reserve']['pre_order_id']) ) ? $entry['reserve']['pre_order_id'] : NULL;
		return $id;
	}

	function get_order_id_by_pre_order_id( $pre_order_id ) {
		global $wpdb;
		$query    = $wpdb->prepare( "SELECT order_id FROM {$wpdb->prefix}usces_order_meta WHERE meta_key = %s AND meta_value = %s", 'pre_order_id', $pre_order_id );
		$order_id = $wpdb->get_var( $query );
		return $order_id;
	}

	function get_reserve($order_id, $key){
		global $wpdb;
		$order_meta_table_name = $wpdb->prefix . "usces_order_meta";
		$query = $wpdb->prepare("SELECT meta_value FROM $order_meta_table_name WHERE order_id = %d AND meta_key = %s",
								$order_id, $key);
		$res = $wpdb->get_var($query);
		return $res;
	}

	function get_order_meta_value($key, $order_id) {
		global $wpdb;
		$order_meta_table_name = $wpdb->prefix . "usces_order_meta";
		$query = $wpdb->prepare("SELECT meta_value FROM $order_meta_table_name WHERE order_id = %d AND meta_key = %s",
								$order_id, $key);
		$res = $wpdb->get_var($query);
		return $res;
	}

	function set_order_meta_value($key, $meta_value, $order_id, $check=1) {
		global $wpdb;

		if( empty($order_id) ) return;

		$order_id = (int)$order_id;
		if( $check ){
			$order = $this->get_order_data($order_id, 'direct' );
			if ( null === $order ){
				return;
			}
		}

		$table_name = $wpdb->prefix . "usces_order_meta";
		$query = $wpdb->prepare("SELECT count(*) FROM $table_name WHERE order_id = %d AND meta_key = %s",
								$order_id, $key);
		$res = $wpdb->get_var($query);
		if(0 < $res) {
			$query = $wpdb->prepare("UPDATE $table_name SET meta_value = %s WHERE order_id = %d AND meta_key = %s",
									$meta_value,
									$order_id,
									$key
									);
			$res2 = $wpdb->query($query);
		} else {
			$query = $wpdb->prepare("INSERT INTO  $table_name (order_id, meta_key, meta_value) 
									VALUES(%d, %s, %s)",
									$order_id,
									$key,
									$meta_value
									);
			$res2 = $wpdb->query($query);
		}
		return $res2;
	}

	function del_order_meta( $key, $order_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix."usces_order_meta";
		$query = $wpdb->prepare( "DELETE FROM $table_name WHERE order_id = %d AND meta_key = %s", $order_id, $key );
		$res = $wpdb->query( $query );
		return $res;
	}

	function set_session_custom_member($member_id) {
		unset($_SESSION['usces_member']['custom_member']);
		$meta = usces_has_custom_field_meta('member');
		if(is_array($meta)) {
			$keys = array_keys($meta);
			foreach($keys as $key) {
				$csmb_key = 'csmb_'.$key;
				$_SESSION['usces_member']['custom_member'][$key] = maybe_unserialize($this->get_member_meta_value($csmb_key, $member_id));
			}
		}
	}

	function reg_custom_member($member_id) {

		$csmb_meta = usces_has_custom_field_meta( 'member' );
		if( is_array($csmb_meta) ) {
			foreach( $csmb_meta as $key => $entry ) {
				if( '4' == $entry['means'] ) {
					$this->del_member_meta( 'csmb_'.$key, $member_id );
				}
			}
		}
		if( !empty($_POST['custom_member']) ) {
			foreach( $_POST['custom_member'] as $key => $value ) {
				$csmb_key = 'csmb_'.$key;
				if( is_array($value) )
					 $value = serialize($value);
				$res = $this->set_member_meta_value($csmb_key, $value, $member_id);
				if(false === $res)
					return false;
			}
		}elseif( isset($_POST['custom_customer']) ){
			foreach( $_POST['custom_customer'] as $key => $value ) {
				$csmb_key = 'csmb_'.$key;
				if( is_array($value) )
					 $value = serialize($value);
				$res = $this->set_member_meta_value($csmb_key, $value, $member_id);
				if(false === $res)
					return false;
			}
		}
	}

	function save_order_acting_data($rand){
		global $wpdb;
		$data = serialize(array( 'cart' => $this->cart->get_cart(), 'entry' => $this->cart->get_entry() ));
		$table_name = $wpdb->prefix . "usces_access";
		$query = $wpdb->prepare("INSERT INTO  $table_name (acc_type, acc_str1, acc_date, acc_key, acc_value) 
								VALUES(%s, %s, now(), %s, %s)",
								'acting_data',
								$this->get_uscesid(false),
								$rand,
								$data
								);
		$res = $wpdb->query($query);
		return $res;
	}

	function set_member_meta_value($key, $meta_value, $member_id = ''){
		global $wpdb;

		if( WCUtils::is_blank($member_id) ) {
			if( !$this->is_member_logged_in() ) return;
			$member = $this->get_member();
			$member_id = $member['ID'];
		}

		$table_name = usces_get_tablename( 'usces_member_meta' );
		$query = $wpdb->prepare("SELECT count(*) FROM $table_name WHERE member_id = %d AND meta_key = %s",
								$member_id, $key);
		$res = $wpdb->get_var($query);
		if(0 < $res){
			$query = $wpdb->prepare("UPDATE $table_name SET meta_value = %s WHERE member_id = %d AND meta_key = %s",
									$meta_value,
									$member_id,
									$key
									);
			$res2 = $wpdb->query($query);
		}else{
			$query = $wpdb->prepare("INSERT INTO  $table_name (member_id, meta_key, meta_value) 
									VALUES(%d, %s, %s)",
									$member_id,
									$key,
									$meta_value
									);
			$res2 = $wpdb->query($query);
		}
		return $res2;
	}

	function get_member_meta_value($key, $member_id){
		global $wpdb;
		$table_name = usces_get_tablename( 'usces_member_meta' );
		$query = $wpdb->prepare("SELECT meta_value FROM $table_name WHERE member_id = %d AND meta_key = %s",
								$member_id, $key);
		$res = $wpdb->get_var($query);
		return $res;
	}

	function del_member_meta($key, $member_id){
		global $wpdb;
		$table_name = usces_get_tablename( 'usces_member_meta' );
		$query = $wpdb->prepare("DELETE FROM $table_name WHERE member_id = %d AND meta_key = %s",
								$member_id, $key);
		$res = $wpdb->query($query);
		return $res;
	}

	function get_member_meta($member_id){
		global $wpdb;
		$table_name = usces_get_tablename( 'usces_member_meta' );
		$query = $wpdb->prepare("SELECT * FROM $table_name WHERE member_id = %d AND meta_key <> 'customer_country' AND meta_key NOT LIKE %s", $member_id, 'csmb_%');
		$res = $wpdb->get_results($query, ARRAY_A);
		return $res;
	}

	function get_settle_info_field( $order_id ){
		global $wpdb;
		$fields = array();
		$table_name = $wpdb->prefix . "usces_order_meta";
		$meta_keys = apply_filters( 'usces_filter_settle_info_field_meta_keys', array( 'settlement_id', 'order_number', 'res_tracking_id', 'SID', 'TransactionId' ) );
		$query = $wpdb->prepare( "SELECT meta_key, meta_value FROM $table_name WHERE order_id = %d AND ( meta_key LIKE %s OR meta_key IN( %s ) )", $order_id, 'acting_%', implode( "','", $meta_keys ) );
		$query = stripslashes($query);
		$res = $wpdb->get_results($query, ARRAY_A);
		if( !$res )
			return $fields;

		foreach( $res as $value ){
			if( in_array($value['meta_key'], $meta_keys) ) {
				$meta_key = $value['meta_key'];
				if( 'settlement_id' == $meta_key ){
					$meta_values = maybe_unserialize($value['meta_value']);
					if( is_array($meta_values) ){
						foreach( $meta_values as $key => $meta_value ){
							$fields[$key] = $meta_value;
						}
					}else{
						$fields['settlement_id'] = $meta_values;
					}
				} else {
					$fields[$meta_key] = $value['meta_value'];
				}
			}elseif( 'acting_' == substr($value['meta_key'], 0, 7) ){
				$meta_values = usces_unserialize($value['meta_value']);
				if(is_array($meta_values)){
					foreach( $meta_values as $key => $meta_value ){
						$fields[$key] = $meta_value;
					}
				}
			}
		}
		return $fields;
	}

	function get_post_custom($post_id, $orderby='meta_id', $order='ASC'){
		global $wpdb;
		$table = $wpdb->prefix . "postmeta";
		$meta_list = $wpdb->get_results( $wpdb->prepare("SELECT meta_key, meta_value FROM $table WHERE post_id = %d ORDER BY $orderby $order",
			$post_id), ARRAY_A );

		if ( !empty($meta_list) ) {
			foreach ( $meta_list as $metarow) {
				$mkey = $metarow['meta_key'];
				$mval = $metarow['meta_value'];
				$res[$mkey][] = $mval;
			}
		}
		return $res;
	}

	function get_post_user_custom($post_id, $orderby='meta_id', $order='ASC'){
		global $wpdb;
		$res = array();
		$table = $wpdb->prefix . "postmeta";
		$meta_list = $wpdb->get_results( $wpdb->prepare("SELECT meta_key, meta_value FROM $table WHERE post_id = %d ORDER BY $orderby $order",
			$post_id), ARRAY_A );

		if ( !empty($meta_list) ) {
			foreach ( $meta_list as $metarow) {
				if( 0 === strpos($metarow['meta_key'], '_') )
					continue;

				$mkey = $metarow['meta_key'];
				$mval = $metarow['meta_value'];
				if( array_key_exists($mkey, $res) ){
					$cval = $res[$mkey];
					$cval = (array)$cval;
					$cval[] = $mval;
					$res[$mkey] = $cval;
				}else{
					$res[$mkey] = $mval;
				}
			}
		}
		$res = apply_filters( 'usces_filter_get_post_user_custom', $res, $meta_list, $post_id, $orderby, $order );
		return $res;
	}

	function get_currency($amount, $symbol_pre = false, $symbol_post = false, $seperator_flag = true ){
		global $usces_settings;
		$cr = $this->options['system']['currency'];
		list($code, $decimal, $point, $seperator, $symbol) = $usces_settings['currency'][$cr];
		if( !$seperator_flag ){
			$seperator = '';
		}
		$price = number_format((double)$amount, $decimal, $point, $seperator);

		if( $symbol_pre )
			$price = ( usces_is_entity($symbol) ? mb_convert_encoding($symbol, 'UTF-8', 'HTML-ENTITIES') : $symbol ) . $price;

		if( $symbol_post )
			$price = $price . __($code, 'usces');

		$price = apply_filters( 'usces_filter_get_currency', $price, $amount, $symbol_pre, $symbol_post, $seperator_flag );
		return $price;
	}

	function get_currency_code(){
		global $usces_settings;
		$cr = $this->options['system']['currency'];
		list($code, $decimal, $point, $seperator, $symbol) = $usces_settings['currency'][$cr];
		return $code;
	}

	function get_currency_decimal() {
		global $usces_settings;
		$cr = $this->options['system']['currency'];
		list( $code, $decimal, $point, $seperator, $symbol ) = $usces_settings['currency'][$cr];
		return $decimal;
	}

	function get_next_page_uri( $type, $current ){
		global $wpdb;

		$table = $wpdb->prefix . 'usces_' . $type;
		if(!isset($_COOKIE[$table])){
			return false;
		}

		$cookie = (isset($_COOKIE[$table])) ? json_decode(str_replace("\'","'",str_replace('\"','"', $_COOKIE[$table])),true) : [];
		if(!isset($cookie['currentPageIds'])){
			return false;
		}

		$ids = $cookie['currentPageIds'];
		foreach( $ids as $key => $id ){
			if( $id == $current ){
				$next = isset( $ids[($key + 1)] ) ? $ids[($key + 1)] : '';
				break;
			}
		}

		if( empty($next) ){
			$uri = '';
		}else{
			$uri = USCES_ADMIN_URL . "?page=usces_{$type}list&{$type}_action=edit&{$type}_id=" . $next;
		}

		return esc_url($uri);
	}

	function get_prev_page_uri( $type, $current ){
		global $wpdb;

		$table = $wpdb->prefix . 'usces_' . $type;
		if(!isset($_COOKIE[$table])){
			return false;
		}

		$cookie = (isset($_COOKIE[$table])) ? json_decode(str_replace("\'","'",str_replace('\"','"', $_COOKIE[$table])),true) : [];
		if(!isset($cookie['currentPageIds'])){
			return false;
		}

		$ids = $cookie['currentPageIds'];
		foreach( $ids as $key => $id ){
			if( $id == $current ){
				$prev = isset( $ids[($key - 1)] ) ? $ids[($key - 1)] : '';
				break;
			}
		}

		if( empty($prev) ){
			$uri = '';
		}else{
			$uri = USCES_ADMIN_URL . "?page=usces_{$type}list&{$type}_action=edit&{$type}_id=" . $prev;
		}

		return esc_url($uri);
	}

	function is_reduced_taxrate() {

		$reduced = ( isset( $this->options['applicable_taxrate'] ) && 'reduced' == $this->options['applicable_taxrate'] ) ? true : false;
		return $reduced;
	}

	//shortcode-----------------------------------------------------------------------------
	function sc_company_name() {
		return htmlspecialchars($this->options['company_name']);
	}
	function sc_zip_code() {
		return htmlspecialchars($this->options['zip_code']);
	}
	function sc_address1() {
		return htmlspecialchars($this->options['address1']);
	}
	function sc_address2() {
		return htmlspecialchars($this->options['address2']);
	}
	function sc_tel_number() {
		return htmlspecialchars($this->options['tel_number']);
	}
	function sc_fax_number() {
		return htmlspecialchars($this->options['fax_number']);
	}
	function sc_inquiry_mail() {
		return htmlspecialchars($this->options['inquiry_mail']);
	}
	function sc_payment() {
		$payments = usces_get_system_option( 'usces_payment_method', 'sort' );
		$htm = "<ul>\n";
		foreach ( (array)$payments as $payment ) {
			$htm .= "<li>" . htmlspecialchars($payment['name']) . "<br />\n";
			$htm .= nl2br(htmlspecialchars($payment['explanation'])) . "</li>\n";
		}
		$htm .= "</ul>\n";
		return $htm;
	}
	function sc_payment_title() {
		$payments = $this->options['payment_method'];
		$htm = "<ul>\n";
		foreach ( (array)$payments as $payment ) {
			$htm .= "<li>" . esc_html($payment['name']) . "</li>\n";
		}
		$htm .= "</ul>\n";
		return $htm;
	}
	function sc_cod_fee() {
		return number_format($this->options['cod_fee']);
	}
	function sc_start_point() {
		return number_format($this->options['start_point']);
	}
	function sc_postage_privilege() {
		if(empty($this->options['postage_privilege']))
			return;
		return number_format($this->options['postage_privilege']);
	}
	function sc_shipping_charge() {
		$entry = $this->cart->get_entry();
		$country = (isset($entry['delivery']['country']) && !empty($entry['delivery']['country'])) ? $entry['delivery']['country'] : $entry['customer']['country'];
		$arr = array();
		foreach ( (array)$this->options['shipping_charge'] as $charges ) {
			foreach ( (array)$charges[$country] as $value ) {
				$arr[] = $value;
			}
		}
		sort($arr);
		$min = $arr[0];
		rsort($arr);
		$max = $arr[0];
		if($min == $max){
			$res = number_format($min);
		}else{
			$res = number_format($min) . __(' - ', 'usces') . number_format($max);
		}
		return $res;
	}
	function sc_site_url() {
		return get_option('home');
	}
	function sc_button_to_cart($atts) {
		extract(shortcode_atts(array(
			'item' => '',
			'sku' => '',
			'value' => __('to the cart', 'usces'),
			'force' => 0,
			'quant' => 0,
			'opt' => 1,
		), $atts));


		$post_id  = $this->get_ID_byItemName( $item );
		$datas    = $this->get_skus( $post_id, 'code' );
		$zaikonum = ( isset( $datas[ $sku ]['stocknum'] ) ) ? $datas[ $sku ]['stocknum'] : null;
		$zaiko    = ( isset( $datas[ $sku ]['stock'] ) ) ? $datas[ $sku ]['stock'] : 2;
		$gptekiyo = ( isset( $datas[ $sku ]['gp'] ) ) ? $datas[ $sku ]['gp'] : null;
		$skuPrice = ( isset( $datas[ $sku ]['price'] ) ) ? $datas[ $sku ]['price'] : 0;
		$sku_enc  = urlencode( $sku );
		$options  = usces_get_opts($post_id, 'sort');
		$mats     = compact('item','sku','value','force','quant','post_id','datas','zaikonum','zaiko','gptekiyo','skuPrice','sku_enc');
		if( ! $this->is_item_zaiko( $post_id, $sku ) ){
			return '<div class="button_status">' . esc_html($this->zaiko_status[$zaiko]) . '</div>';
		}

		$html = "<form action=\"" . USCES_CART_URL . "\" method=\"post\">\n";
		$html .= "<input name=\"zaikonum[{$post_id}][{$sku_enc}]\" type=\"hidden\" id=\"zaikonum[{$post_id}][{$sku_enc}]\" value=\"" . esc_attr( $zaikonum ) . "\" />\n";
		$html .= "<input name=\"zaiko[{$post_id}][{$sku_enc}]\" type=\"hidden\" id=\"zaiko[{$post_id}][{$sku_enc}]\" value=\"" . esc_attr( $zaiko ) . "\" />\n";
		$html .= "<input name=\"gptekiyo[{$post_id}][{$sku_enc}]\" type=\"hidden\" id=\"gptekiyo[{$post_id}][{$sku_enc}]\" value=\"" . esc_attr( $gptekiyo ) . "\" />\n";
		$html .= "<input name=\"skuPrice[{$post_id}][{$sku_enc}]\" type=\"hidden\" id=\"skuPrice[{$post_id}][{$sku_enc}]\" value=\"" . esc_attr( $skuPrice ) . "\" />\n";
		if( 1 == $opt ){
			$html .= usces_item_option_fileds( $post_id, $sku, 1, 'return' );
		}elseif( 2 == $opt ){
			$html .= usces_item_option_fileds( $post_id, $sku, 0, 'return' );
		}
		if( $quant ){
			$quant_field = "<input name=\"quant[{$post_id}][" . $sku_enc . "]\" type=\"text\" id=\"quant[{$post_id}][" . $sku_enc . "]\" class=\"skuquantity\" value=\"\" onKeyDown=\"if (event.keyCode == 13) {return false;}\" />";
			$html .= apply_filters('usces_filter_sc_itemQuant', $quant_field, $mats);
		}
		$html .= "<input name=\"inCart[{$post_id}][{$sku_enc}]\" type=\"submit\" id=\"inCart[{$post_id}][{$sku_enc}]\" class=\"skubutton\" value=\"" . esc_attr( $value ) . "\" " . apply_filters('usces_filter_direct_intocart_button', NULL, $post_id, $sku, $force, $options) . " />";
		$html .= "<input name=\"usces_referer\" type=\"hidden\" value=\"" . esc_url($_SERVER['REQUEST_URI']) . "\" />\n";
		if( $force )
			$html .= "<input name=\"usces_force\" type=\"hidden\" value=\"incart\" />\n";
		$html = apply_filters('usces_filter_single_item_inform', $html);
		$html .= "</form>";
		$html .= '<div class="error_message">' . usces_singleitem_error_message($post_id, $sku, 'return') . '</div>'."\n";

		return $html;
	}

	function filter_itemPage($content){
		global $post;
		$html = '';

		if( ($post->post_mime_type != 'item' || !is_single()) ) return $content;
		if( post_password_required($post) ) return $content;

		$temp_path = apply_filters('usces_template_path_single_item', USCES_PLUGIN_DIR . '/templates/single_item.php');
		include( $temp_path );

		$content = apply_filters('usces_filter_itemPage', $html, $post->ID);

		return $content;
	}

	function filter_cartContent($content) {
		global $post;
		$html = '';
		switch($this->page){
			case 'cart':
				$temp_path = apply_filters('usces_template_path_cart', USCES_PLUGIN_DIR . '/templates/cart/cart.php');
				include( $temp_path );
				break;
			case 'customer':
				$temp_path = apply_filters('usces_template_path_customer', USCES_PLUGIN_DIR . '/templates/cart/customer_info.php');
				include( $temp_path );
				break;
			case 'delivery':
				$temp_path = apply_filters('usces_template_path_delivery', USCES_PLUGIN_DIR . '/templates/cart/delivery_info.php');
				include( $temp_path );
				break;
			case 'confirm':
				$temp_path = apply_filters('usces_template_path_confirm', USCES_PLUGIN_DIR . '/templates/cart/confirm.php');
				include( $temp_path );
				break;
			case 'ordercompletion':
				$temp_path = apply_filters('usces_template_path_ordercompletion', USCES_PLUGIN_DIR . '/templates/cart/completion.php');
				include( $temp_path );
				break;
			case 'cartverifying':
				$temp_path = apply_filters('usces_template_path_cartverifying', USCES_PLUGIN_DIR . '/templates/cart/verifying.php');
				include( $temp_path );
				break;
			case 'cartverified':
				$temp_path = apply_filters('usces_template_path_cartverified', USCES_PLUGIN_DIR . '/templates/cart/verified.php');
				include( $temp_path );
				break;
			case 'error':
				$temp_path = apply_filters('usces_template_path_carterror', USCES_PLUGIN_DIR . '/templates/cart/error.php');
				include( $temp_path );
				break;
			case 'maintenance':
				$temp_path = apply_filters('usces_template_path_maintenance', USCES_PLUGIN_DIR . '/templates/cart/maintenance.php');
				include( $temp_path );
				break;
			case 'search_item':
				$temp_path = apply_filters('usces_template_path_search_item', USCES_PLUGIN_DIR . '/templates/search_item.php');
				include( $temp_path );
				break;
			case 'wp_search':
				if($post->post_mime_type == 'item'){
					$temp_path = apply_filters('usces_template_path_wp_search', USCES_PLUGIN_DIR . '/templates/wp_search_item.php');
					include( $temp_path );
				}else{
					$html = $content;
				}
				break;
			default:
				$html = $content;
		}

		if( $this->use_ssl && ($this->is_cart_or_member_page($_SERVER['REQUEST_URI']) || $this->is_inquiry_page($_SERVER['REQUEST_URI'])) )
			$html = str_replace('src="'.site_url(), 'src="'.USCES_SSL_URL_ADMIN, $html);

		$html = apply_filters('usces_filter_cartContent', $html);

		$content = $html;

		remove_filter('the_title', array($this, 'filter_cartTitle'));

		return $content;
	}

	function filter_cartTitle($title) {
		if( is_admin() )
			return $title;

		if( $title == 'Cart' || $title == __('Cart', 'usces') ){
			switch($this->page){
				case 'cart':
					$newtitle = apply_filters('usces_filter_title_cart', __('In the cart', 'usces'));
					break;
				case 'customer':
					$newtitle = apply_filters('usces_filter_title_customer', __('Customer Information', 'usces'));
					break;
				case 'delivery':
					$newtitle = apply_filters('usces_filter_title_delivery', __('Shipping / Payment options', 'usces'));
					break;
				case 'confirm':
					$newtitle = apply_filters('usces_filter_title_confirm', __('Confirmation', 'usces'));
					break;
				case 'ordercompletion':
					$newtitle = apply_filters('usces_filter_title_ordercompletion', __('Completion', 'usces'));
					break;
				case 'error':
					$newtitle = apply_filters('usces_filter_title_carterror', __('Error', 'usces'));
					break;
				case 'cartverified':
					$newtitle = apply_filters('usces_filter_title_cartverified', __("Member registration complete", 'usces'));
					break;
				case 'search_item':
					$newtitle = apply_filters('usces_filter_title_search_item', __("'AND' search by categories", 'usces'));
					break;
				case 'maintenance':
					$newtitle = apply_filters('usces_filter_title_maintenance', __('Under Maintenance', 'usces'));
					break;
				case 'login':
					$newtitle = apply_filters('usces_filter_title_login', __('Log-in for members', 'usces'));
					break;
				default:
					$newtitle = apply_filters('usces_filter_title_cart_default', $title);
			}
		}else{
			$newtitle = $title;
		}

		$newtitle = apply_filters('usces_filter_cartTitle', $newtitle);
		return $newtitle;
	}

	function action_cartFilter(){
		add_filter('the_title', array($this, 'filter_cartTitle'),20);
		add_filter('the_content', array($this, 'filter_cartContent'),20);
	}

	function action_search_item(){
		include(TEMPLATEPATH . '/page.php');
		exit;
	}

	function filter_memberContent($content) {
		global $post;
		$html = '';

		if($this->options['membersystem_state'] == 'activate'){

			if( $this->is_member_logged_in() ) {

				$member_regmode = 'editmemberform';
				$temp_path = apply_filters('usces_template_path_member', USCES_PLUGIN_DIR . '/templates/member/member.php');
				include( $temp_path );

			} else {

				switch($this->page){
					case 'login':
						$temp_path = apply_filters('usces_template_path_login', USCES_PLUGIN_DIR . '/templates/member/login.php');
						include( $temp_path );
						break;
					case 'lostmemberpassword':
						$temp_path = apply_filters('usces_template_path_lostpassword', USCES_PLUGIN_DIR . '/templates/member/lostpassword.php');
						include( $temp_path );
						break;
					case 'changepassword':
						$temp_path = apply_filters('usces_template_path_changepassword', USCES_PLUGIN_DIR . '/templates/member/changepassword.php');
						include( $temp_path );
						break;
					case 'newcompletion':
					case 'editcompletion':
					case 'lostcompletion':
					case 'changepasscompletion':
						$temp_path = apply_filters('usces_template_path_membercompletion', USCES_PLUGIN_DIR . '/templates/member/completion.php');
						include( $temp_path );
						break;
					case 'memberverifying':
						$temp_path = apply_filters('usces_template_path_memberverifying', USCES_PLUGIN_DIR . '/templates/member/verifying.php');
						include( $temp_path );
						break;
//					case 'memberverified':
//						$temp_path = apply_filters('usces_template_path_memberverified', USCES_PLUGIN_DIR . '/templates/member/member_verified.php');
//						include( $temp_path );
//						break;
					case 'newmemberform':
						$member_form_title = apply_filters('usces_filter_title_newmemberform', __('New enrollment form', 'usces'));
						$member_regmode = 'newmemberform';
						$temp_path = apply_filters('usces_template_path_member_form', USCES_PLUGIN_DIR . '/templates/member/member_form.php');
						include( $temp_path );
						break;
					default:
						$temp_path = apply_filters('usces_template_path_login', USCES_PLUGIN_DIR . '/templates/member/login.php');
						include( $temp_path );
				}

			}
		}else{
			$html .= "<p>".__('Member Services is not running currently.','usces')."</p>";
		}

		$content = $html;

		remove_filter( 'the_title', array( $this, 'filter_memberTitle' ), 20 );

		return $content;
	}

	function filter_memberTitle($title) {
		if( is_admin() )
			return $title;

		if( $this->options['membersystem_state'] == 'activate' && $this->is_member_page($_SERVER['REQUEST_URI']) ){
			switch($this->page){
				case 'login':
					$newtitle = apply_filters('usces_filter_title_login', __('Log-in for members', 'usces'));
					break;
				case 'newmemberform':
					$newtitle = apply_filters('usces_filter_title_newmemberform', __('New enrollment form', 'usces'));
					break;
				case 'lostmemberpassword':
					$newtitle = apply_filters('usces_filter_title_lostmemberpassword', __('The new password acquisition', 'usces'));
					break;
				case 'changepassword':
					$newtitle = apply_filters('usces_filter_title_changepassword', __('Change password', 'usces'));
					break;
				case 'newcompletion':
				case 'editcompletion':
				case 'lostcompletion':
				case 'changepasscompletion':
					$newtitle = apply_filters('usces_filter_title_changepasscompletion', __('Completion', 'usces'));
					break;
				case 'memberverifying':
					$newtitle = apply_filters('usces_filter_title_memberverifying', __('Verifying', 'usces'));
					break;
				case 'error':
					$newtitle = apply_filters('usces_filter_title_membererror', __('Error', 'usces'));
					break;
				default:
					$newtitle = apply_filters('usces_filter_title_member_default', $title);
			}
		}else{
			$newtitle = $title;
		}

		$newtitle = apply_filters('usces_filter_memberTitle', $newtitle);
		return $newtitle;
	}

	function action_memberFilter(){
		add_filter('the_title', array($this, 'filter_memberTitle'),20);
		add_filter('the_content', array($this, 'filter_memberContent'),20);
	}

	function filter_usces_cart_css(){
		$path = get_stylesheet_directory_uri() . '/usces_cart.css';
		return $path;
	}

	function filter_divide_item(){
		global $wp_query;


		if( ($this->options['divide_item'] && !is_category() && !is_search() && !is_singular() && !is_admin()) ){
			$ids = $this->getItemIds( 'front' );
			if ( isset( $wp_query->query_vars['post__not_in'] ) ) {
				$wp_query->query_vars['post__not_in'] = $ids;
			}
		}
		if( is_admin() ){
			$ids = $this->getItemIds( 'back' );
			if ( isset( $wp_query->query_vars['post__not_in'] ) ) {
				$wp_query->query_vars['post__not_in'] = $ids;
			}
		}
		do_action( 'usces_action_divide_item');
	}

	function load_upload_template(){
		$post_id = $_POST['post_id'];
		$file = 'upload_template01.php';
		include(TEMPLATEPATH . '/' . $file);
		exit;
	}

	function filter_itemimg_anchor_rel($html){

		if( is_single() ){
			$str = ' rel="' . $this->options['itemimg_anchor_rel'] . '"';
		}else{
			$str = '';
		}
		return $html . $str;
	}

	function filter_permalink( $link ) {

		if(false !== strpos('?page_id=4', $link) || false !== strpos('?page_id=3', $link) || false !== strpos('usces-cart', $link) || false !== strpos('usces-member', $link) )
			$link = str_replace('http://', 'https://', $link);

		return $link;
	}

	function filter_cart_page_header($html){
		if( !empty($this->options['cart_page_data']['header']['cart']) ){
			$html = $this->options['cart_page_data']['header']['cart'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_cart_page_footer($html){
		if( !empty($this->options['cart_page_data']['footer']['cart']) ){
			$html = $this->options['cart_page_data']['footer']['cart'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_customer_page_header($html){
		if( !empty($this->options['cart_page_data']['header']['customer']) ){
			$html = $this->options['cart_page_data']['header']['customer'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_customer_page_footer($html){
		if( !empty($this->options['cart_page_data']['footer']['customer']) ){
			$html = $this->options['cart_page_data']['footer']['customer'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_delivery_page_header($html){
		if( !empty($this->options['cart_page_data']['header']['delivery']) ){
			$html = $this->options['cart_page_data']['header']['delivery'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_delivery_page_footer($html){
		if( !empty($this->options['cart_page_data']['footer']['delivery']) ){
			$html = $this->options['cart_page_data']['footer']['delivery'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_confirm_page_header($html){
		if( !empty($this->options['cart_page_data']['header']['confirm']) ){
			$html = $this->options['cart_page_data']['header']['confirm'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_confirm_page_footer($html){
		if( !empty($this->options['cart_page_data']['footer']['confirm']) ){
			$html = $this->options['cart_page_data']['footer']['confirm'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_cartcompletion_page_header($html){
		if( !empty($this->options['cart_page_data']['header']['completion']) ){
			$html = $this->options['cart_page_data']['header']['completion'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_cartcompletion_page_footer($html){
		if( !empty($this->options['cart_page_data']['footer']['completion']) ){
			$html = $this->options['cart_page_data']['footer']['completion'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_login_page_header($html){
		if( !empty($this->options['member_page_data']['header']['login']) ){
			$html = $this->options['member_page_data']['header']['login'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_login_page_footer($html){
		if( !empty($this->options['member_page_data']['footer']['login']) ){
			$html = $this->options['member_page_data']['footer']['login'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_newmember_page_header($html){
		if( !empty($this->options['member_page_data']['header']['newmember']) ){
			$html = $this->options['member_page_data']['header']['newmember'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_newmember_page_footer($html){
		if( !empty($this->options['member_page_data']['footer']['newmember']) ){
			$html = $this->options['member_page_data']['footer']['newmember'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_newpass_page_header($html){
		if( !empty($this->options['member_page_data']['header']['newpass']) ){
			$html = $this->options['member_page_data']['header']['newpass'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_newpass_page_footer($html){
		if( !empty($this->options['member_page_data']['footer']['newpass']) ){
			$html = $this->options['member_page_data']['footer']['newpass'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_changepass_page_header($html){
		if( !empty($this->options['member_page_data']['header']['changepass']) ){
			$html = $this->options['member_page_data']['header']['changepass'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_changepass_page_footer($html){
		if( !empty($this->options['member_page_data']['footer']['changepass']) ){
			$html = $this->options['member_page_data']['footer']['changepass'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_memberinfo_page_header($html){
		if( !empty($this->options['member_page_data']['header']['memberinfo']) ){
			$html = $this->options['member_page_data']['header']['memberinfo'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_memberinfo_page_footer($html){
		if( !empty($this->options['member_page_data']['footer']['memberinfo']) ){
			$html = $this->options['member_page_data']['footer']['memberinfo'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_membercompletion_page_header($html){
		if( !empty($this->options['member_page_data']['header']['completion']) ){
			$html = $this->options['member_page_data']['header']['completion'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function filter_membercompletion_page_footer($html){
		if( !empty($this->options['member_page_data']['footer']['completion']) ){
			$html = $this->options['member_page_data']['footer']['completion'];
		}
		return do_shortcode( stripslashes(nl2br($html)) );
	}

	function action_cart_page_header(){
		if( !empty($this->options['cart_page_data']['header']['cart']) ){
			$html = $this->options['cart_page_data']['header']['cart'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_cart_page_footer(){
		if( !empty($this->options['cart_page_data']['footer']['cart']) ){
			$html = $this->options['cart_page_data']['footer']['cart'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_customer_page_header(){
		if( !empty($this->options['cart_page_data']['header']['customer']) ){
			$html = $this->options['cart_page_data']['header']['customer'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_customer_page_footer(){
		if( !empty($this->options['cart_page_data']['footer']['customer']) ){
			$html = $this->options['cart_page_data']['footer']['customer'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_delivery_page_header(){
		if( !empty($this->options['cart_page_data']['header']['delivery']) ){
			$html = $this->options['cart_page_data']['header']['delivery'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_delivery_page_footer(){
		if( !empty($this->options['cart_page_data']['footer']['delivery']) ){
			$html = $this->options['cart_page_data']['footer']['delivery'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_confirm_page_header(){
		if( !empty($this->options['cart_page_data']['header']['confirm']) ){
			$html = $this->options['cart_page_data']['header']['confirm'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_confirm_page_footer(){
		if( !empty($this->options['cart_page_data']['footer']['confirm']) ){
			$html = $this->options['cart_page_data']['footer']['confirm'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_cartcompletion_page_header(){
		if( !empty($this->options['cart_page_data']['header']['completion']) ){
			$html = $this->options['cart_page_data']['header']['completion'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_cartcompletion_page_footer(){
		if( !empty($this->options['cart_page_data']['footer']['completion']) ){
			$html = $this->options['cart_page_data']['footer']['completion'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_login_page_header(){
		if( !empty($this->options['member_page_data']['header']['login']) ){
			$html = $this->options['member_page_data']['header']['login'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_login_page_footer(){
		if( !empty($this->options['member_page_data']['footer']['login']) ){
			$html = $this->options['member_page_data']['footer']['login'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_newmember_page_header(){
		if( !empty($this->options['member_page_data']['header']['newmember']) ){
			$html = $this->options['member_page_data']['header']['newmember'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_newmember_page_footer(){
		if( !empty($this->options['member_page_data']['footer']['newmember']) ){
			$html = $this->options['member_page_data']['footer']['newmember'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_newpass_page_header(){
		if( !empty($this->options['member_page_data']['header']['newpass']) ){
			$html = $this->options['member_page_data']['header']['newpass'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_newpass_page_footer(){
		if( !empty($this->options['member_page_data']['footer']['newpass']) ){
			$html = $this->options['member_page_data']['footer']['newpass'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_changepass_page_header(){
		if( !empty($this->options['member_page_data']['header']['changepass']) ){
			$html = $this->options['member_page_data']['header']['changepass'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_changepass_page_footer(){
		if( !empty($this->options['member_page_data']['footer']['changepass']) ){
			$html = $this->options['member_page_data']['footer']['changepass'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_memberinfo_page_header(){
		if( !empty($this->options['member_page_data']['header']['memberinfo']) ){
			$html = $this->options['member_page_data']['header']['memberinfo'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_memberinfo_page_footer(){
		if( !empty($this->options['member_page_data']['footer']['memberinfo']) ){
			$html = $this->options['member_page_data']['footer']['memberinfo'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_membercompletion_page_header(){
		if( !empty($this->options['member_page_data']['header']['completion']) ){
			$html = $this->options['member_page_data']['header']['completion'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function action_membercompletion_page_footer(){
		if( !empty($this->options['member_page_data']['footer']['completion']) ){
			$html = $this->options['member_page_data']['footer']['completion'];
			echo do_shortcode( stripslashes(nl2br($html)) );
		}
	}

	function filter_confirm_page_notes( $html ) {
		if ( ! empty( $this->options['cart_page_data']['confirm_notes'] ) ) {
			$html = '<div class="wc_confirm_notes_area">' . $this->options['cart_page_data']['confirm_notes'] . '</div>';
		}
		return stripslashes( nl2br( $html ) );
	}

	function action_confirm_page_notes() {
		if ( ! empty( $this->options['cart_page_data']['confirm_notes'] ) ) {
			$html = '<div class="wc_confirm_notes_area">' . $this->options['cart_page_data']['confirm_notes'] . '</div>';
			echo stripslashes( nl2br( $html ) );
		}
	}

	/**
	 * Get member cookies.
	 *
	 * @param string $key string cookies key.
	 *
	 * @return string
	 */
	function usces_get_member_cookies( $key ) {
		$result = '';
		switch ( $key ) {
			case 'ord_ex_cancel':
				$ex_cancel = filter_input( INPUT_GET, 'ord_ex_cancel' );
				if ( $ex_cancel ) {
					$result = ('on' === $ex_cancel) ? 'on' : 'off';
				} else {
					$cookie = $this->get_cookie( 'usces_front' );
					$result = isset( $cookie['ord_ex_cancel'] ) ? $cookie['ord_ex_cancel'] : 'on';
					$result = apply_filters( 'usces_filter_ord_ex_cancel_init', $result );
				}
				break;
			case 'pur-date':
				$result = filter_input( INPUT_GET, 'pur-date' );
				if ( null === $result ) {
					$cookie = $this->get_cookie( 'usces_front' );
					$result = isset( $cookie['usces_purdate'] ) ? $cookie['usces_purdate'] : 'd_30';
				}
				break;
		}
		return $result;
	}
}
