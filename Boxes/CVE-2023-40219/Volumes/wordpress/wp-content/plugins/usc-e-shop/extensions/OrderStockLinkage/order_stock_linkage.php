<?php
/************************************************************
* The stock linkage by updating order data
*
*************************************************************/

class USCES_STOCK_LINKAGE
{
	public static $opts;

	public function __construct(){
	
		self::initialize_data();
	
		if( is_admin() ){
		
			add_action( 'usces_action_admin_system_extentions', array( $this, 'setting_form') );
			add_action( 'init', array( $this, 'save_data') );

			if( self::$opts['orderedit_flag'] ){
				add_action( 'usces_action_update_orderdata', array( $this, 'update_order'), 10, 5 );
				add_action( 'usces_action_del_orderdata', array( $this, 'del_order'), 10, 2 );
				add_filter( 'usces_filter_add_ordercart', array( $this, 'ajax_add_ordercart_item'), 10, 3 );
				add_action( 'usces_admin_delete_orderrow', array( $this, 'delete_ordercart_item'), 10, 3 );
			}
			if( self::$opts['collective_flag'] ){
				add_action( 'usces_action_collective_order_status_each', array( $this, 'collective_update_order'), 10, 3 );
				add_action('usces_action_collective_order_delete_each', array( $this, 'collective_del_order'), 10, 2 );
			}
		}
	}

	/**********************************************
	* Initialize
	* Modified:2 Nov.2015
	***********************************************/
	public function initialize_data(){
		global $usces;
		$options = get_option('usces_ex');
		$options['system']['stocklink']['orderedit_flag'] = !isset($options['system']['stocklink']['orderedit_flag']) ? 1 : (int)$options['system']['stocklink']['orderedit_flag'];
		$options['system']['stocklink']['collective_flag'] = !isset($options['system']['stocklink']['collective_flag']) ? 1 : (int)$options['system']['stocklink']['collective_flag'];
		update_option( 'usces_ex', $options );
		self::$opts = $options['system']['stocklink'];
	}

	/**********************************************
	* save option data
	* Modified:10 Oct.2015
	***********************************************/
	public function save_data(){
		global $usces;
		if(isset($_POST['usces_stocklink_option_update'])) {

			check_admin_referer( 'admin_system', 'wc_nonce' );

			self::$opts['orderedit_flag'] = isset($_POST['stocklink_orderedit_flag']) ? (int)$_POST['stocklink_orderedit_flag'] : 1;
			self::$opts['collective_flag'] = isset($_POST['stocklink_collective_flag']) ? (int)$_POST['stocklink_collective_flag'] : 1;

			$options = get_option('usces_ex');
			$options['system']['stocklink'] = self::$opts;
			update_option('usces_ex', $options);
		}
	}	
	/**********************************************
	* setting_form
	* Modified:10 Oct.2015
	***********************************************/
	public function setting_form(){
		$status = (self::$opts['orderedit_flag'] || self::$opts['collective_flag']) ? '<span class="running">' . __('Running', 'usces') . '</span>' : '<span class="stopped">' . __('Stopped', 'usces') . '</span>';
?>
	<form action="" method="post" name="option_form" id="stocklink_form">
	<div class="postbox">
		<div class="postbox-header">
			<h2><span><?php _e('Stock Linkage OrderData','usces'); ?></span><?php wel_esc_script_e( $status ); ?></h2>
			<div class="handle-actions"><button type="button" class="handlediv" id="stocklink"><span class="screen-reader-text"><?php echo esc_html( sprintf( __( 'Toggle panel: %s' ), __( 'Stock Linkage OrderData', 'usces' ) ) ); ?></span><span class="toggle-indicator"></span></button></div>
		</div>
		<div class="inside">
		<table class="form_table">
			<tr height="35">
				<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_stocklink_orderedit_flag');"><?php _e('Linkage Order Upadate', 'usces'); ?></a></th>
				<td width="10"><input name="stocklink_orderedit_flag" id="stocklink_orderedit_flag0" type="radio" value="0"<?php if(self::$opts['orderedit_flag'] === 0) echo 'checked="checked"'; ?> /></td><td width="100"><label for="stocklink_orderedit_flag0"><?php _e('disable', 'usces'); ?></label></td>
				<td width="10"><input name="stocklink_orderedit_flag" id="stocklink_orderedit_flag1" type="radio" value="1"<?php if(self::$opts['orderedit_flag'] === 1) echo 'checked="checked"'; ?> /></td><td width="100"><label for="stocklink_orderedit_flag1"><?php _e('enable', 'usces'); ?></label></td>
				<td><div id="ex_stocklink_orderedit_flag" class="explanation"><?php _e("", 'usces'); ?></div></td>
			</tr>
			<tr height="35">
				<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_stocklink_collective_flag');"><?php _e('Linkage Order Collective Upadate', 'usces'); ?></a></th>
				<td width="10"><input name="stocklink_collective_flag" id="stocklink_collective_flag0" type="radio" value="0"<?php if(self::$opts['collective_flag'] === 0) echo 'checked="checked"'; ?> /></td><td width="100"><label for="stocklink_collective_flag0"><?php _e('disable', 'usces'); ?></label></td>
				<td width="10"><input name="stocklink_collective_flag" id="stocklink_collective_flag1" type="radio" value="1"<?php if(self::$opts['collective_flag'] === 1) echo 'checked="checked"'; ?> /></td><td width="100"><label for="stocklink_collective_flag1"><?php _e('enable', 'usces'); ?></label></td>
				<td><div id="ex_stocklink_collective_flag" class="explanation"><?php _e("", 'usces'); ?></div></td>
			</tr>
		</table>
		<hr />
		<input name="usces_stocklink_option_update" type="submit" class="button button-primary" value="<?php _e('change decision','usces'); ?>" />
		</div>
	</div><!--postbox-->
	<?php wp_nonce_field( 'admin_system', 'wc_nonce' ); ?>
	</form>
<?php
	}

	/***********************************
	* order edit
	***********************************/
	public function update_order( $new_order, $old_status, $old_order, $new_carts, $old_carts ){
		global $usces;

		if( // If the status change was not
			(!$usces->is_status( 'adminorder', $old_order->order_status ) 
			&& !$usces->is_status( 'estimate', $old_order->order_status ) 
			&& !$usces->is_status( 'cancel', $old_order->order_status ) 
			&& !$usces->is_status( 'adminorder', $new_order->order_status ) 
			&& !$usces->is_status( 'estimate', $new_order->order_status ) 
			&& !$usces->is_status( 'cancel', $new_order->order_status )) 
			|| ($usces->is_status( 'adminorder', $old_order->order_status ) 
			&& !$usces->is_status( 'cancel', $old_order->order_status ) 
			&& $usces->is_status( 'adminorder', $new_order->order_status ) 
			&& !$usces->is_status( 'cancel', $new_order->order_status )) 
		){
			foreach( $old_carts as $ocart ){
				$zaikonum = $usces->getItemZaikoNum( $ocart['post_id'], $ocart['sku_code'] );
				if( WCUtils::is_blank($zaikonum) )
					continue;
					
				$stock_id = $usces->getItemZaikoStatusId($ocart['post_id'], $ocart['sku_code']);
				$itemOrderAcceptable = $usces->getItemOrderAcceptable( $ocart['post_id'] );
				foreach( $new_carts as $ncart ){
					if( ($ocart['post_id'] == $ncart['post_id']) && ($ocart['sku_code'] == $ncart['sku_code']) ){
						$fluctuation = $ncart['quantity'] - $ocart['quantity'];
						$value = $zaikonum - $fluctuation;
						if( $itemOrderAcceptable != 1 ) {
							if( 0 >= $value ){
								$value = 0;
								if( 1 >= $stock_id ){
									usces_update_sku( $ocart['post_id'], $ocart['sku_code'], 'stock', 2 );
								}
							}else{
								if( $zaikonum == 0 && 2 <= $stock_id ){
									usces_update_sku( $ocart['post_id'], $ocart['sku_code'], 'stock', 0 );
								}
							}
						}
						usces_update_sku( $ocart['post_id'], $ocart['sku_code'], 'stocknum', $value );
					}
				}
			}
		
		}elseif( // It has been canceled
			(!$usces->is_status( 'adminorder', $old_order->order_status ) 
			&& !$usces->is_status( 'estimate', $old_order->order_status ) 
			&& !$usces->is_status( 'cancel', $old_order->order_status ) 
			&& !$usces->is_status( 'adminorder', $new_order->order_status ) 
			&& !$usces->is_status( 'estimate', $new_order->order_status ) 
			&& $usces->is_status( 'cancel', $new_order->order_status )) 
			|| ($usces->is_status( 'adminorder', $old_order->order_status ) 
			&& !$usces->is_status( 'cancel', $old_order->order_status ) 
			&& $usces->is_status( 'adminorder', $new_order->order_status ) 
			&& $usces->is_status( 'cancel', $new_order->order_status )) 
		){
			foreach( $old_carts as $ocart ){
				$zaikonum = $usces->getItemZaikoNum( $ocart['post_id'], $ocart['sku_code'] );
				if( WCUtils::is_blank($zaikonum) )
					continue;
					
				$stock_id = $usces->getItemZaikoStatusId($ocart['post_id'], $ocart['sku_code']);
				$itemOrderAcceptable = $usces->getItemOrderAcceptable( $ocart['post_id'] );
				$value = $zaikonum + $ocart['quantity'];
				if( $itemOrderAcceptable != 1 ) {
					if( WCUtils::is_zero($zaikonum) && 2 <= $stock_id ){
						usces_update_sku( $ocart['post_id'], $ocart['sku_code'], 'stock', 0 );
					}
				}
				usces_update_sku( $ocart['post_id'], $ocart['sku_code'], 'stocknum', $value );
			}
		
		}elseif( // From cancellation to Enable
			(!$usces->is_status( 'adminorder', $old_order->order_status ) 
			&& !$usces->is_status( 'estimate', $old_order->order_status ) 
			&& $usces->is_status( 'cancel', $old_order->order_status ) 
			&& !$usces->is_status( 'adminorder', $new_order->order_status ) 
			&& !$usces->is_status( 'estimate', $new_order->order_status ) 
			&& !$usces->is_status( 'cancel', $new_order->order_status )) 
			|| ($usces->is_status( 'adminorder', $old_order->order_status ) 
			&& $usces->is_status( 'cancel', $old_order->order_status ) 
			&& $usces->is_status( 'adminorder', $new_order->order_status ) 
			&& !$usces->is_status( 'cancel', $new_order->order_status )) 
		){
			foreach( $new_carts as $ncart ){
				$zaikonum = $usces->getItemZaikoNum( $ncart['post_id'], $ncart['sku_code'] );
				if( WCUtils::is_blank($zaikonum) )
					continue;
					
				$stock_id = $usces->getItemZaikoStatusId($ncart['post_id'], $ncart['sku_code']);
				$itemOrderAcceptable = $usces->getItemOrderAcceptable( $ncart['post_id'] );
				$value = $zaikonum - $ncart['quantity'];
				if( $itemOrderAcceptable != 1 ) {
					if( 0 >= $value ){
						if( 1 >= $stock_id ){
							usces_update_sku( $ncart['post_id'], $ncart['sku_code'], 'stock', 2 );
						}
						$value = 0;
					}
				}
				usces_update_sku( $ncart['post_id'], $ncart['sku_code'], 'stocknum', $value );
			}
		
		}elseif( // From Estimate to Adminorder
			$usces->is_status( 'estimate', $old_order->order_status ) 
			&& $usces->is_status( 'adminorder', $new_order->order_status ) 
			&& !$usces->is_status( 'cancel', $new_order->order_status ) 
		){
			foreach( $new_carts as $ncart ){
				$zaikonum = $usces->getItemZaikoNum( $ncart['post_id'], $ncart['sku_code'] );
				if( WCUtils::is_blank($zaikonum) )
					continue;
					
				$stock_id = $usces->getItemZaikoStatusId($ncart['post_id'], $ncart['sku_code']);
				$itemOrderAcceptable = $usces->getItemOrderAcceptable( $ncart['post_id'] );
				$value = $zaikonum - $ncart['quantity'];
				if( $itemOrderAcceptable != 1 ) {
					if( 0 >= $value ){
						if( 1 >= $stock_id ){
							usces_update_sku( $ncart['post_id'], $ncart['sku_code'], 'stock', 2 );
						}
						$value = 0;
					}
				}
				usces_update_sku( $ncart['post_id'], $ncart['sku_code'], 'stocknum', $value );
			}
		}
	}

	/***********************************
	* order collective change
	***********************************/
	public function collective_update_order( $id, $statusstr, $old_status ){
		global $usces;
		
		$action = isset($_REQUEST['change']['word']) ? $_REQUEST['change']['word'] : '';
		switch( $action ){
			case 'adminorder':
				if( 
					$usces->is_status( 'estimate', $old_status ) 
					&& !$usces->is_status( 'cancel', $old_status ) 
				){
					$carts = usces_get_ordercartdata( $id );
					foreach( $carts as $cart ){
						$zaikonum = $usces->getItemZaikoNum( $cart['post_id'], $cart['sku_code'] );
						if( WCUtils::is_blank($zaikonum) )
							continue;
							
						$stock_id = $usces->getItemZaikoStatusId($cart['post_id'], $cart['sku_code']);
						$itemOrderAcceptable = $usces->getItemOrderAcceptable( $cart['post_id'] );
						$value = $zaikonum - $cart['quantity'];
						if( $itemOrderAcceptable != 1 ) {
							if( 0 >= $value ){
								if( 1 >= $stock_id ){
									usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stock', 2 );
								}
								$value = 0;
							}
						}
						usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stocknum', $value );
					}
					
				}
				break;
				
			case 'estimate':
				if( 
					$usces->is_status( 'adminorder', $old_status ) 
					&& !$usces->is_status( 'cancel', $old_status ) 
				){
					$carts = usces_get_ordercartdata( $id );
					foreach( $carts as $cart ){
						$zaikonum = $usces->getItemZaikoNum( $cart['post_id'], $cart['sku_code'] );
						if( WCUtils::is_blank($zaikonum) )
							continue;
							
						$stock_id = $usces->getItemZaikoStatusId($cart['post_id'], $cart['sku_code']);
						$itemOrderAcceptable = $usces->getItemOrderAcceptable( $cart['post_id'] );
						$value = $zaikonum + $cart['quantity'];
						if( $itemOrderAcceptable != 1 ) {
							if( WCUtils::is_zero($zaikonum) && 2 <= $stock_id ){
								usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stock', 0 );
							}
						}
						usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stocknum', $value );
					}
					
				}elseif( 
					!$usces->is_status( 'adminorder', $old_status ) 
					&& !$usces->is_status( 'estimate', $old_status ) 
					&& !$usces->is_status( 'completion', $old_status ) 
					&& !$usces->is_status( 'cancel', $old_status ) 
				){
					$carts = usces_get_ordercartdata( $id );
					foreach( $carts as $cart ){
						$zaikonum = $usces->getItemZaikoNum( $cart['post_id'], $cart['sku_code'] );
						if( WCUtils::is_blank($zaikonum) )
							continue;
							
						$stock_id = $usces->getItemZaikoStatusId($cart['post_id'], $cart['sku_code']);
						$itemOrderAcceptable = $usces->getItemOrderAcceptable( $cart['post_id'] );
						$value = $zaikonum + $cart['quantity'];
						if( $itemOrderAcceptable != 1 ) {
							if( WCUtils::is_zero($zaikonum) && 2 <= $stock_id ){
								usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stock', 0 );
							}
						}
						usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stocknum', $value );
					}
					
				}
				break;
				
			case 'cancel':
				if( 
					$usces->is_status( 'adminorder', $old_status ) 
					&& !$usces->is_status( 'cancel', $old_status ) 
				){
					$carts = usces_get_ordercartdata( $id );
					foreach( $carts as $cart ){
						$zaikonum = $usces->getItemZaikoNum( $cart['post_id'], $cart['sku_code'] );
						if( WCUtils::is_blank($zaikonum) )
							continue;
							
						$stock_id = $usces->getItemZaikoStatusId($cart['post_id'], $cart['sku_code']);
						$itemOrderAcceptable = $usces->getItemOrderAcceptable( $cart['post_id'] );
						$value = $zaikonum + $cart['quantity'];
						if( $itemOrderAcceptable != 1 ) {
							if( WCUtils::is_zero($zaikonum) && 2 <= $stock_id ){
								usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stock', 0 );
							}
						}
						usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stocknum', $value );
					}
					
				}elseif( 
					!$usces->is_status( 'adminorder', $old_status ) 
					&& !$usces->is_status( 'estimate', $old_status ) 
					&& !$usces->is_status( 'cancel', $old_status ) 
				){
					$carts = usces_get_ordercartdata( $id );
					foreach( $carts as $cart ){
						$zaikonum = $usces->getItemZaikoNum( $cart['post_id'], $cart['sku_code'] );
						if( WCUtils::is_blank($zaikonum) )
							continue;
							
						$stock_id = $usces->getItemZaikoStatusId($cart['post_id'], $cart['sku_code']);
						$itemOrderAcceptable = $usces->getItemOrderAcceptable( $cart['post_id'] );
						$value = $zaikonum + $cart['quantity'];
						if( $itemOrderAcceptable != 1 ) {
							if( WCUtils::is_zero($zaikonum) && 2 <= $stock_id ){
								usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stock', 0 );
							}
						}
						usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stocknum', $value );
					}
					
				}
				break;
				
			case 'duringorder':
			case 'completion':
			case 'new':
			case 'neworder':
				if( 
					$usces->is_status( 'adminorder', $old_status ) 
					&& $usces->is_status( 'cancel', $old_status ) 
				){
					$carts = usces_get_ordercartdata( $id );
					foreach( $carts as $cart ){
						$zaikonum = $usces->getItemZaikoNum( $cart['post_id'], $cart['sku_code'] );
						if( WCUtils::is_blank($zaikonum) )
							continue;
							
						$stock_id = $usces->getItemZaikoStatusId($cart['post_id'], $cart['sku_code']);
						$itemOrderAcceptable = $usces->getItemOrderAcceptable( $cart['post_id'] );
						$value = $zaikonum - $cart['quantity'];
						if( $itemOrderAcceptable != 1 ) {
							if( 0 >= $value ){
								if( 1 >= $stock_id ){
									usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stock', 2 );
								}
								$value = 0;
							}
						}
						usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stocknum', $value );
					}
					
				}elseif( 
					!$usces->is_status( 'adminorder', $old_status ) 
					&& !$usces->is_status( 'estimate', $old_status ) 
					&& $usces->is_status( 'cancel', $old_status ) 
				){
					$carts = usces_get_ordercartdata( $id );
					foreach( $carts as $cart ){
						$zaikonum = $usces->getItemZaikoNum( $cart['post_id'], $cart['sku_code'] );
						if( WCUtils::is_blank($zaikonum) )
							continue;
							
						$stock_id = $usces->getItemZaikoStatusId($cart['post_id'], $cart['sku_code']);
						$itemOrderAcceptable = $usces->getItemOrderAcceptable( $cart['post_id'] );
						$value = $zaikonum - $cart['quantity'];
						if( $itemOrderAcceptable != 1 ) {
							if( 0 >= $value ){
								if( 1 >= $stock_id ){
									usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stock', 2 );
								}
								$value = 0;
							}
						}
						usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stocknum', $value );
					}
					
				}
				break;
		}
	}

	/***********************************
	* order delete
	***********************************/
	public function del_order( $order_data, $args ){
		global $usces;
		extract($args);//$ID,$point,$res
		if(
			!$usces->is_status( 'adminorder', $order_data->order_status ) 
			&& !$usces->is_status( 'estimate', $order_data->order_status ) 
			&& !$usces->is_status( 'cancel', $order_data->order_status ) 
		){
			$carts = usces_get_ordercartdata( $ID );
			foreach( $carts as $cart ){
				$zaikonum = $usces->getItemZaikoNum( $cart['post_id'], $cart['sku_code'] );
				if( WCUtils::is_blank($zaikonum) )
					continue;
					
				$stock_id = $usces->getItemZaikoStatusId($cart['post_id'], $cart['sku_code']);
				$itemOrderAcceptable = $usces->getItemOrderAcceptable( $cart['post_id'] );
				$value = $zaikonum + $cart['quantity'];
				if( $itemOrderAcceptable != 1 ) {
					if( WCUtils::is_zero($zaikonum) && 2 <= $stock_id ){
						usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stock', 0 );
					}
				}
				usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stocknum', $value );
			}
		
		}elseif(
			$usces->is_status( 'adminorder', $order_data->order_status ) 
			&& !$usces->is_status( 'estimate', $order_data->order_status ) 
			&& !$usces->is_status( 'cancel', $order_data->order_status ) 
		){
			$carts = usces_get_ordercartdata( $ID );
			foreach( $carts as $cart ){
				$zaikonum = $usces->getItemZaikoNum( $cart['post_id'], $cart['sku_code'] );
				if( WCUtils::is_blank($zaikonum) )
					continue;
					
				$stock_id = $usces->getItemZaikoStatusId($cart['post_id'], $cart['sku_code']);
				$itemOrderAcceptable = $usces->getItemOrderAcceptable( $cart['post_id'] );
				$value = $zaikonum + $cart['quantity'];
				if( $itemOrderAcceptable != 1 ) {
					if( WCUtils::is_zero($zaikonum) && 2 <= $stock_id ){
						usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stock', 0 );
					}
				}
				usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stocknum', $value );
			}
		
		}
	}

	/***********************************
	* order collective delete
	***********************************/
	public function collective_del_order( $id, $order_res ){
		global $usces;

		if(
			!$usces->is_status( 'adminorder', $order_res['order_status'] ) 
			&& !$usces->is_status( 'estimate', $order_res['order_status'] ) 
			&& !$usces->is_status( 'cancel', $order_res['order_status'] ) 
		){
			$carts = usces_get_ordercartdata( $id );
			foreach( $carts as $cart ){
				$zaikonum = $usces->getItemZaikoNum( $cart['post_id'], $cart['sku_code'] );
				if( WCUtils::is_blank($zaikonum) )
					continue;
					
				$stock_id = $usces->getItemZaikoStatusId($cart['post_id'], $cart['sku_code']);
				$itemOrderAcceptable = $usces->getItemOrderAcceptable( $cart['post_id'] );
				$value = $zaikonum + $cart['quantity'];
				if( $itemOrderAcceptable != 1 ) {
					if( WCUtils::is_zero($zaikonum) && 2 <= $stock_id ){
						usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stock', 0 );
					}
				}
				usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stocknum', $value );
			}
		
		}elseif(
			$usces->is_status( 'adminorder', $order_res['order_status'] ) 
			&& !$usces->is_status( 'estimate', $order_res['order_status'] ) 
			&& !$usces->is_status( 'cancel', $order_res['order_status'] ) 
		){
			$carts = usces_get_ordercartdata( $id );
			foreach( $carts as $cart ){
				$zaikonum = $usces->getItemZaikoNum( $cart['post_id'], $cart['sku_code'] );
				if( WCUtils::is_blank($zaikonum) )
					continue;
					
				$stock_id = $usces->getItemZaikoStatusId($cart['post_id'], $cart['sku_code']);
				$itemOrderAcceptable = $usces->getItemOrderAcceptable( $cart['post_id'] );
				$value = $zaikonum + $cart['quantity'];
				if( $itemOrderAcceptable != 1 ) {
					if( WCUtils::is_zero($zaikonum) && 2 <= $stock_id ){
						usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stock', 0 );
					}
				}
				usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stocknum', $value );
			}
		
		}
	}

	/***********************************
	* add ordercart item
	***********************************/
	public function ajax_add_ordercart_item( $res, $order_id, $cart_id ){
		global $usces;
		$cart = usces_get_ordercartdata_row( $cart_id );
		$order = $usces->get_order_data( $order_id, 'direct' );
		$newstock = '';
		if(
			!$usces->is_status( 'estimate', $order['order_status'] ) 
			&& !$usces->is_status( 'cancel', $order['order_status'] ) 
		){
			$zaikonum = $usces->getItemZaikoNum( $cart['post_id'], $cart['sku_code'] );
			if( WCUtils::is_blank($zaikonum) )
				return $res;
				
			$stock_id = $usces->getItemZaikoStatusId($cart['post_id'], $cart['sku_code']);
			$itemOrderAcceptable = $usces->getItemOrderAcceptable( $cart['post_id'] );
			$value = $zaikonum - $cart['quantity'];
			if( $itemOrderAcceptable != 1 ) {
				if( 0 >= $value ){
					if( 1 >= $stock_id ){
						usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stock', 2 );
					}
					$value = 0;
				}
			}
			usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stocknum', $value );
		}
		return $res;
	}

	/***********************************
	* delete ordercart item
	***********************************/
	public function delete_ordercart_item( $del_cart_id, $ID, $old_cart ){
		global $usces;
		$cart = usces_get_ordercartdata_row( $del_cart_id );
		$order = $usces->get_order_data( $ID, 'direct' );
		if(
			!$usces->is_status( 'estimate', $order['order_status'] ) 
			&& !$usces->is_status( 'cancel', $order['order_status'] ) 
		){
			$zaikonum = $usces->getItemZaikoNum( $cart['post_id'], $cart['sku_code'] );
			if( ! WCUtils::is_blank($zaikonum) ){
				$stock_id = $usces->getItemZaikoStatusId($cart['post_id'], $cart['sku_code']);
				$itemOrderAcceptable = $usces->getItemOrderAcceptable( $cart['post_id'] );
				$value = $zaikonum + $cart['quantity'];
				if( $itemOrderAcceptable != 1 ) {
					if( WCUtils::is_zero($zaikonum) && 2 <= $stock_id ){
						usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stock', 0 );
					}
				}
				usces_update_sku( $cart['post_id'], $cart['sku_code'], 'stocknum', $value );
			}
		}
	}

}

