<?php
function usces_states_form_js(){
	global $usces;
	
	$js = '';
	if( $usces->use_js 
			&& ((  (is_page(USCES_MEMBER_NUMBER) || $usces->is_member_page($_SERVER['REQUEST_URI'])) && ((true === $usces->is_member_logged_in() && WCUtils::is_blank($usces->page)) || 'member' == $usces->page || 'editmemberform' == $usces->page || 'newmemberform' == $usces->page)  )
			|| (  (is_page(USCES_CART_NUMBER) || $usces->is_cart_page($_SERVER['REQUEST_URI'])) && ('customer' == $usces->page || 'delivery' == $usces->page)  ) 
			)) {
			
		$js .= '<script type="text/javascript">
		(function($) {
		uscesForm = {
			settings: {
				url: uscesL10n.ajaxurl,
				type: "POST",
				cache: false
			},
			
			changeStates : function( country, type ) {
	
				var s = this.settings;
				s.url = "' . USCES_SSL_URL . '/";
				s.data = "usces_ajax_action=change_states&country=" + country;
				$.ajax( s ).done(function( data ){
					if( "error" == data ){
						alert("error");
					}else{
						$("select#" + type + "_pref").html( data );
						if( customercountry == country && "customer" == type ){
							$("#" + type + "_pref").prop({selectedIndex:customerstate});
						}else if( deliverycountry == country && "delivery" == type ){
							$("#" + type + "_pref").prop({selectedIndex:deliverystate});
						}else if( customercountry == country && "member" == type ){
							$("#" + type + "_pref").prop({selectedIndex:customerstate});
						}
					}
				}).fail(function( msg ){
					alert("error");
				});
				return false;
			}
		};';
		
		if( 'customer' == $usces->page ){
	
			$js .= 'var customerstate = $("#customer_pref").get(0).selectedIndex;
			var customercountry = $("#customer_country").val();
			var deliverystate = "";
			var deliverycountry = "";
			var memberstate = "";
			var membercountry = "";
			$("#customer_country").change(function () {
				var country = $("#customer_country option:selected").val();
				uscesForm.changeStates( country, "customer" ); 
			});';
			
		}elseif( 'delivery' == $usces->page ){
			
			$js .= 'var customerstate = "";
			var customercountry = "";
			var deliverystate = $("#delivery_pref").get(0).selectedIndex;
			var deliverycountry = $("#delivery_country").val();
			var memberstate = "";
			var membercountry = "";
			$("#delivery_country").change(function () {
				var country = $("#delivery_country option:selected").val();
				uscesForm.changeStates( country, "delivery" ); 
			});';
			
		}elseif( (true === $usces->is_member_logged_in() && WCUtils::is_blank($usces->page)) || (true === $usces->is_member_logged_in() && 'member' == $usces->page) || 'editmemberform' == $usces->page || 'newmemberform' == $usces->page ){
			
			$js .= 'var customerstate = "";
			var customercountry = "";
			var deliverystate = "";
			var deliverycountry = "";
			var memberstate = $("#member_pref").get(0).selectedIndex;
			var membercountry = $("#member_country").val();
			$("#member_country").change(function () {
				var country = $("#member_country option:selected").val();
				uscesForm.changeStates( country, "member" ); 
			});';
		}
		$js .= '})(jQuery);
			</script>';
	}
	
	echo apply_filters('usces_filter_states_form_js', $js);
}

function usces_get_pointreduction($currency){
	global $usces, $usces_settings;

	$form = $usces_settings['currency'][$currency];
	if( 2 == $form[1] ){
		$reduction = 0.01;
	}else{
		$reduction = 1;
	}
	$reduction = apply_filters('usces_filter_pointreduction', $reduction);
	return $reduction;
}

function admin_prodauct_current_screen(){
	global $current_screen, $post, $typenow;

	$wp_version = get_bloginfo('version');
	if (version_compare($wp_version, '3.4-beta3', '<'))
		return;


	if ( !(isset($_GET['page']) && (('usces_itemedit' == $_GET['page'] && isset($_GET['action'])) || 'usces_itemnew' == $_GET['page'])) )
		return;
	
	$typenow = 'product';

	if ( isset( $_GET['post'] ) )
		$post_id = $post_ID = (int) $_GET['post'];
	elseif ( isset( $_POST['post_ID'] ) )
		$post_id = $post_ID = (int) $_POST['post_ID'];
	else
		$post_id = $post_ID = 0;

	$post_type = 'post';
	$post_type_object = get_post_type_object( $post_type );

	if ( $post_id ) {
		$product = wel_get_product( $post_id );
		$post    = $product['_pst'];
	}else{
		$post = get_default_post_to_edit( $post_type, true );
		$post_ID = $post->ID;
	}

	require_once(USCES_PLUGIN_DIR.'/includes/meta-boxes.php');

	add_meta_box('submitdiv', __('Publish'), 'usces_post_submit_meta_box', $post_type, 'side', 'core');
	if( NEW_PRODUCT_IMAGE_REGISTER::$opts['switch_flag'] ){
		add_meta_box( 'item-main-pict', __( 'Item image', 'usces' ), 'wel_post_item_pict_box_html', $post_type, 'side', 'high' );
	} else {
		add_meta_box('item-main-pict', __('Item image', 'usces'), 'post_item_pict_box', $post_type, 'side', 'high');
	}

	// all taxonomies
	foreach ( get_object_taxonomies($post_type) as $tax_name ) {
		$taxonomy = get_taxonomy($tax_name);
		if ( ! $taxonomy->show_ui )
			continue;
	
		$label = $taxonomy->labels->name;
	
		if ( !is_taxonomy_hierarchical($tax_name) )
			add_meta_box('tagsdiv-' . $tax_name, $label, 'usces_post_tags_meta_box', $post_type, 'side', 'core');
		else
			add_meta_box($tax_name . 'div', $label, 'usces_post_categories_meta_box', $post_type, 'side', 'core', array( 'taxonomy' => $tax_name, 'descendants_and_self' => USCES_ITEM_CAT_PARENT_ID ));
	}
	
	if ( post_type_supports($post_type, 'page-attributes') )
		add_meta_box('pageparentdiv', 'page' == $post_type ? __('Page Attributes') : __('Attributes'), 'usces_page_attributes_meta_box', $post_type, 'side', 'core');
	
	if ( current_theme_supports( 'post-thumbnails', $post_type ) && post_type_supports($post_type, 'thumbnail') )
		add_meta_box('postimagediv', __( 'Featured image', 'usces' ), 'usces_post_thumbnail_meta_box', $post_type, 'side', 'low');
	
	if ( post_type_supports($post_type, 'excerpt') )
		add_meta_box('postexcerpt', __('Excerpt'), 'usces_post_excerpt_meta_box', $post_type, 'normal', 'core');
	
	if ( post_type_supports($post_type, 'trackbacks') )
		add_meta_box('trackbacksdiv', __('Send Trackbacks'), 'usces_post_trackback_meta_box', $post_type, 'normal', 'core');
	
	if ( post_type_supports($post_type, 'custom-fields') )
		add_meta_box('postcustom', __('Custom Fields'), 'usces_post_custom_meta_box', $post_type, 'normal', 'core');

	if ( post_type_supports($post_type, 'comments') )
		add_meta_box('commentstatusdiv', __('Discussion'), 'usces_post_comment_status_meta_box', $post_type, 'normal', 'core');
	
	if ( (isset($post->post_status) && ('publish' == $post->post_status || 'private' == $post->post_status) ) && post_type_supports($post_type, 'comments') )
		add_meta_box('commentsdiv', __('Comments'), 'usces_post_comment_meta_box', $post_type, 'normal', 'core');
	
	if ( !( (isset( $post->post_status ) && 'pending' == $post->post_status) && !current_user_can( $post_type_object->cap->publish_posts ) ) )
		add_meta_box('slugdiv', __('Slug'), 'usces_post_slug_meta_box', $post_type, 'normal', 'core');
	
	if ( post_type_supports($post_type, 'author') ) {
		if ( version_compare($wp_version, '3.1', '>=') ){
			if ( is_super_admin() || current_user_can( $post_type_object->cap->edit_others_posts ) )
				add_meta_box('authordiv', __('Author'), 'usces_post_author_meta_box', $post_type, 'normal', 'core');
		}else{
			$authors = get_editable_user_ids( $current_user->id ); // TODO: ROLE SYSTEM
			if ( isset($post->post_author) && $post->post_author && !in_array($post->post_author, $authors) )
				$authors[] = $post->post_author;
			if ( ( $authors && count( $authors ) > 1 ) || is_super_admin() )
				add_meta_box('authordiv', __('Author'), 'usces_post_author_meta_box', $post_type, 'normal', 'core');
		}
	}
	
	if ( post_type_supports($post_type, 'revisions') && 0 < $post_ID && wp_get_post_revisions( $post_ID ) )
		add_meta_box('revisionsdiv', __('Revisions'), 'usces_post_revisions_meta_box', $post_type, 'normal', 'core');



	$current_screen->base = $post_type;
	$current_screen->id = $post_type;
	$current_screen->post_type = $post_type;

}

function admin_prodauct_header(){

	$wp_version = get_bloginfo('version');
	if (version_compare($wp_version, '3.4-beta3', '<'))
		return;
	
	if ( isset( $_REQUEST['action'] ) && 'edit' === $_REQUEST['action'] ) {
	
		$suport_display = '<p>'.__('Product registration documentation','usces').'<br /><a href="http://www.welcart.com/documents/manual-2/%E6%96%B0%E8%A6%8F%E5%95%86%E5%93%81%E8%BF%BD%E5%8A%A0" target="_new">'.__('Product editing screen','usces').'</a></p>';
	
		get_current_screen()->add_help_tab( array(
			'id'      => 'suport-display',
			'title'   => 'Documents',
			'content' => $suport_display,
		) );
	}
}

function admin_new_prodauct_header(){

	$wp_version = get_bloginfo('version');
	if (version_compare($wp_version, '3.4-beta3', '<'))
		return;
	
	$customize_display = '<p>' . __('The title field and the big Post Editing Area are fixed in place, but you can reposition all the other boxes using drag and drop, and can minimize or expand them by clicking the title bar of each box. Use the Screen Options tab to unhide more boxes (Excerpt, Send Trackbacks, Custom Fields, Discussion, Slug, Author) or to choose a 1- or 2-column layout for this screen.') . '</p>';

	get_current_screen()->add_help_tab( array(
		'id'      => 'customize-display',
		'title'   => __('Customizing This Display'),
		'content' => $customize_display,
	) );
}

function usces_clear_quickcharge( $id ) {
	global $wpdb;
	$table_name = usces_get_tablename( 'usces_member_meta' );
	$query = $wpdb->prepare( "DELETE FROM $table_name WHERE meta_key = %s", $id );
	$res = $wpdb->query( $query );

	return $res;
}

function usces_admin_action_status( $status = '', $message = '' ) {
	global $usces;
	if( empty($status) ) {
		$status = $usces->action_status;
		$usces->action_status = 'none';
	}
	if( empty($message) ) {
		$message = $usces->action_message;
		$usces->action_message = '';
	}
	$class = '';
	if( $status == 'success' ) {
		$class = 'updated';
	} elseif( $status == 'caution' ) {
		$class = 'update-nag';
	} elseif( $status == 'error' ) {
		$class = 'error';
	}
	if( '' != $class ) {
?>
<div id="usces_admin_status">
	<div id="usces_action_status" class="<?php echo esc_attr( $class ); ?> notice is-dismissible">
		<p><strong><?php wel_esc_script_e( $message ); ?></strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'usces' ); ?></span></button>
	</div>
</div>
<?php
	} else {
?>
<div id="usces_admin_status"></div>
<?php
	}
}

function usces_get_admin_script_message() {
	$mes_str = "";
	$message = array();
	$message[] = __( 'Please enter the option name.', 'usces' );//0
	$message[] = __( 'The same option name exists.', 'usces' );//1
	$message[] = __( 'Please enter the select value.', 'usces' );//2
	$message[] = __( 'Leave the select value blank for text and textarea.', 'usces' );//3
	$message[] = __( 'Please enter the SKU code value.', 'usces' );//4
	$message[] = __( 'Please enter the sale price value.', 'usces' );//5
	$message[] = __( 'Enter the SKU code in single-byte alphanumeric characters (including-_).', 'usces' );//6
	$message[] = __( 'Enter the normal price numerically.', 'usces' );//7
	$message[] = __( 'Enter the sale price numerically.', 'usces' );//8
	$message[] = __( 'Enter the stock numerically.', 'usces' );//9
	$message[] = __( 'The same SKU code exists.', 'usces' );//10
	$message[] = __( 'Please enter the payment method name value.', 'usces' );//11
	$message[] = __( 'Please select the payment type.', 'usces' );//12
	$message[] = __( 'Please enter the value for the payment module.', 'usces' );//13
	$message[] = __( 'The same payment method name exists.', 'usces' );//14
	$message[] = __( 'Chose the %s', 'usces' );//15
	$message[] = __( 'Input the %s', 'usces' );//16
	$message[] = __( 'Please enter a numeric value.', 'usces' );//17
	$message[] = __( 'Delete', 'usces' );//18
	$message[] = __( 'Dismiss this notice.', 'usces' );//19
	$message[] = __( ' - ', 'usces' );//20
	$message[] = __( 'Enter the field key in single-byte alphanumeric characters (including-_).', 'usces' );//21
	$message[] = __( 'Please enter the field name.', 'usces' );//22
	$message[] = __( 'The same value as the field key exists.', 'usces' );//23
	$message[] = __( 'The same value as the field name exists.', 'usces' );//24
	$message[] = __( 'Delete SKU (********). Are you sure?', 'usces' );//25
	$message = apply_filters( 'usces_filter_admin_script_message', $message );
	foreach( (array)$message as $key => $mes ) {
		$mes_str .= "'".$mes."',";
	}
	$mes_str = rtrim( $mes_str, "," );
	return $mes_str;
}

function usces_admin_orderlist_show_wc_trans_id() {

	$list_option = get_option( 'usces_orderlist_option' );
	$wc_trans_id = ( isset( $list_option['view_column']['wc_trans_id'] ) ) ? $list_option['view_column']['wc_trans_id'] : 0;
	if( empty( $wc_trans_id ) ) {
		$list_option['view_column']['wc_trans_id'] = 1;
		update_option( 'usces_orderlist_option', $list_option );
	}
}

function usces_admin_print_footer_scripts() {
	global $usces;

	$admin_page = ( isset( $_GET['page'] ) ) ? wp_unslash( $_GET['page'] ) : '';
	switch ( $admin_page ) :
		/* メール設定 */
		case 'usces_mail':
			$email_attach_feature = ( isset( $usces->options['email_attach_feature'] ) ) ? $usces->options['email_attach_feature'] : '0';
			?>
<script type="text/javascript">
jQuery( document ).ready( function( $ ) {
	var email_attach_feature = "<?php echo esc_js( $email_attach_feature ); ?>";
	if ( "1" == email_attach_feature ) {
		$( ".email_attach_feature" ).css( "display", "" );
	} else {
		$( ".email_attach_feature" ).css( "display", "none" );
	}
	$( document ).on( "change", "input[name='email_attach_feature']", function() {
		if ( "1" == $( this ).val() ) {
			$( ".email_attach_feature" ).css( "display", "" );
		} else {
			$( ".email_attach_feature" ).css( "display", "none" );
		}
	});
});
</script>
			<?php
		break;
	endswitch;
}

/**
 * Mail data.
 *
 * @return array
 */
function usces_mail_data() {
	global $usces;

	$options             = get_option( 'usces' );
	$mail_data_title     = $options['mail_data']['title'];
	$mail_data_header    = $options['mail_data']['header'];
	$mail_data_footer    = $options['mail_data']['footer'];
	foreach ( $mail_data_title as $mail => $value ) {
		if ( empty( $value ) && isset( $options['mail_default']['title'][ $mail ] ) ) {
			$mail_data_title[ $mail ] = $options['mail_default']['title'][ $mail ];
		}
	}
	foreach ( $mail_data_header as $mail => $value ) {
		if ( empty( $value ) && isset( $options['mail_default']['header'][ $mail ] ) ) {
			$mail_data_header[ $mail ] = $options['mail_default']['header'][ $mail ];
		}
	}
	foreach ( $mail_data_footer as $mail => $value ) {
		if ( empty( $value ) && isset( $options['mail_default']['footer'][ $mail ] ) ) {
			$mail_data_footer[ $mail ] = $options['mail_default']['footer'][ $mail ];
		}
	}
	$mail_thankyou       = get_option( 'usces_mail_thankyou', array( 'title' => $mail_data_title['thankyou'], 'header' => $mail_data_header['thankyou'], 'footer' => $mail_data_footer['thankyou'] ) );
	$mail_order          = get_option( 'usces_mail_order', array( 'title' => $mail_data_title['order'], 'header' => $mail_data_header['order'], 'footer' => $mail_data_footer['order'] ) );
	$mail_inquiry        = get_option( 'usces_mail_inquiry', array( 'title' => $mail_data_title['inquiry'], 'header' => $mail_data_header['inquiry'], 'footer' => $mail_data_footer['inquiry'] ) );
	$mail_membercomp     = get_option( 'usces_mail_membercomp', array( 'title' => $mail_data_title['membercomp'], 'header' => $mail_data_header['membercomp'], 'footer' => $mail_data_footer['membercomp'] ) );
	$mail_completionmail = get_option( 'usces_mail_completionmail', array( 'title' => $mail_data_title['completionmail'], 'header' => $mail_data_header['completionmail'], 'footer' => $mail_data_footer['completionmail'] ) );
	$mail_ordermail      = get_option( 'usces_mail_ordermail', array( 'title' => $mail_data_title['ordermail'], 'header' => $mail_data_header['ordermail'], 'footer' => $mail_data_footer['ordermail'] ) );
	$mail_changemail     = get_option( 'usces_mail_changemail', array( 'title' => $mail_data_title['changemail'], 'header' => $mail_data_header['changemail'], 'footer' => $mail_data_footer['changemail'] ) );
	$mail_receiptmail    = get_option( 'usces_mail_receiptmail', array( 'title' => $mail_data_title['receiptmail'], 'header' => $mail_data_header['receiptmail'], 'footer' => $mail_data_footer['receiptmail'] ) );
	$mail_mitumorimail   = get_option( 'usces_mail_mitumorimail', array( 'title' => $mail_data_title['mitumorimail'], 'header' => $mail_data_header['mitumorimail'], 'footer' => $mail_data_footer['mitumorimail'] ) );
	$mail_cancelmail     = get_option( 'usces_mail_cancelmail', array( 'title' => $mail_data_title['cancelmail'], 'header' => $mail_data_header['cancelmail'], 'footer' => $mail_data_footer['cancelmail'] ) );
	$mail_othermail      = get_option( 'usces_mail_othermail', array( 'title' => $mail_data_title['othermail'], 'header' => $mail_data_header['othermail'], 'footer' => $mail_data_footer['othermail'] ) );

	$mail_data           = array();
	$mail_data['title']  = array(
		'thankyou'       => $mail_thankyou['title'],
		'order'          => $mail_order['title'],
		'inquiry'        => $mail_inquiry['title'],
		'membercomp'     => $mail_membercomp['title'],
		'completionmail' => $mail_completionmail['title'],
		'ordermail'      => $mail_ordermail['title'],
		'changemail'     => $mail_changemail['title'],
		'receiptmail'    => $mail_receiptmail['title'],
		'mitumorimail'   => $mail_mitumorimail['title'],
		'cancelmail'     => $mail_cancelmail['title'],
		'othermail'      => $mail_othermail['title'],
	);
	$mail_data['header'] = array(
		'thankyou'       => $mail_thankyou['header'],
		'order'          => $mail_order['header'],
		'inquiry'        => $mail_inquiry['header'],
		'membercomp'     => $mail_membercomp['header'],
		'completionmail' => $mail_completionmail['header'],
		'ordermail'      => $mail_ordermail['header'],
		'changemail'     => $mail_changemail['header'],
		'receiptmail'    => $mail_receiptmail['header'],
		'mitumorimail'   => $mail_mitumorimail['header'],
		'cancelmail'     => $mail_cancelmail['header'],
		'othermail'      => $mail_othermail['header'],
	);
	$mail_data['footer'] = array(
		'thankyou'       => $mail_thankyou['footer'],
		'order'          => $mail_order['footer'],
		'inquiry'        => $mail_inquiry['footer'],
		'membercomp'     => $mail_membercomp['footer'],
		'completionmail' => $mail_completionmail['footer'],
		'ordermail'      => $mail_ordermail['footer'],
		'changemail'     => $mail_changemail['footer'],
		'receiptmail'    => $mail_receiptmail['footer'],
		'mitumorimail'   => $mail_mitumorimail['footer'],
		'cancelmail'     => $mail_cancelmail['footer'],
		'othermail'      => $mail_othermail['footer'],
	);
	return apply_filters( 'usces_filter_mail_data', $mail_data, $mail_data_title, $mail_data_header, $mail_data_footer );
}

/**
 * Hide the none item categories in the "Item category" tab of the category post box.
 * Implementation of the "admin_print_footer_scripts" action hook.
 *
 * @return void
 */
function usces_hide_none_item_cat() {
	$current_page        = isset( $_GET['page'] ) ? $_GET['page'] : '';
	$is_new_or_edit_item = in_array( $current_page, array( 'usces_itemnew', 'usces_itemedit' ), true );
	if ( $is_new_or_edit_item ) :
		$item_cats             = get_term_children( USCES_ITEM_CAT_PARENT_ID, 'category' );
		$item_cat_checkbox_ids = array( '#category-' . USCES_ITEM_CAT_PARENT_ID );
		foreach ( $item_cats as $item_cat_id ) {
			$item_cat_checkbox_ids[] = '#category-' . $item_cat_id;
		}
		$item_cat_checkbox_ids_str = implode( ',', $item_cat_checkbox_ids );
		?>
<script>
	(function($) {
		$('#category-all ul#categorychecklist li[id*="category-"]').not('<?php wel_esc_script_e( $item_cat_checkbox_ids_str ); ?>').hide();
	})(jQuery);
</script>
		<?php
	endif;
}

/**
 * Whether Welcart was installed for the first time.
 *
 * @return boolean
 */
function wel_is_first_install() {
	global $wpdb;

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

	if ( is_array( $target ) && 0 < count( $target ) ) {
		return false;
	} else {
		return true;
	}

}