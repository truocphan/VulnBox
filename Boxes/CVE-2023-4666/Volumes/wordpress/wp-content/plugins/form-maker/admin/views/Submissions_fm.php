<?php
/**
 * Class FMViewSubmissions
 */
class  FMViewSubmissions_fm extends FMAdminView {

  private $model;
  private $fm_nonce = null;
  public function __construct( $model = array() ) {
    $this->fm_nonce = wp_create_nonce('fm_ajax_nonce');
    $this->model = $model;
    wp_enqueue_style('thickbox');
    $fm_settings = WDFMInstance(self::PLUGIN)->fm_settings;
    if ( $fm_settings['fm_developer_mode'] ) {
      wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-tables');
      wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-jquery-ui');
      wp_enqueue_style('jquery.fancybox');
    }
    wp_enqueue_script('thickbox');
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-progressbar');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('jquery-ui-widget');
    wp_enqueue_script('jquery-ui-slider');
    wp_enqueue_script('jquery-ui-spinner');
    wp_enqueue_script('jquery-ui-mouse');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker');
    if ( function_exists('wp_add_inline_script') ) { // Since Wordpress 4.5.0
      wp_add_inline_script('jquery-ui-datepicker', WDW_FM_Library(self::PLUGIN)->localize_ui_datepicker());
    }
    else {
      echo '<script>' . WDW_FM_Library(self::PLUGIN)->localize_ui_datepicker() . '</script>';
    }

	if ( $fm_settings['fm_developer_mode'] ) {
		wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-admin');
		wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-manage');
		wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-submissions');
		wp_enqueue_script('jquery.fancybox.pack');
	}
	else {
		wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-submission');
		wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-submission');
	}
  }

	/**
	* Forms page.
	*
	* @param $params
	*/
	public function forms( $params = array() ) {
	  // TODO: Change this function to standard.
	  echo $this->topbar();

		$id 		= $params['id'];
		$page		= $params['page'];
		$page_title = $params['page_title'];
		$page_url 	= $params['page_url'];
		$forms 	  	= $params['forms'];
		$order_by  	= esc_attr($params['order_by']);
		$asc_or_desc = $params['asc_or_desc'];
		echo '<div class="wrap">';
		echo $this->title(array(
                        'title' => $page_title,
                        'title_class' => 'wd-header',
                      ));
		?>
		<br>
		<div class="tablenav top">
			<div class="alignleft">
				<?php echo $this->select_form( array('id' => $id, 'forms' => $forms, 'page' => $page, 'page_url' => $page_url, 'order_by' => $order_by, 'asc_or_desc' => $asc_or_desc) ); ?>
			</div>
			<div class="fm-export-tools">
				<?php $blocked_ips_link = add_query_arg(array( 'page' => 'blocked_ips' . WDFMInstance(self::PLUGIN)->menu_postfix ), $page_url); ?>
				<a class="button" href="<?php echo $blocked_ips_link; ?>" target="_blank"><?php echo _e('Blocked IPs', WDFMInstance(self::PLUGIN)->prefix);?></a>
			</div>
		</div>
		<?php if( !$id ) { ?>
			<table class="wp-list-table widefat fixed striped posts">
				<body id="the-list">
					<tr class="no-items">
						<td class="colspanchange fm-column-not-hide" colspan="0"><?php _e('Please select a form to view submissions', WDFMInstance(self::PLUGIN)->prefix); ?></td>
					</tr>
				</body>
			</table>
		<?php }
		echo '</div>';
	}

  /**
   * Display page.
   *
   * @param array $params
   */
	public function display( $params = array() ) {
		$id = $params['id'];
		$page = $params['page'];
		$page_url = $params['page_url'];
		$page_number = $params['page_number'];
		$order_by = esc_attr($params['order_by']);
		$asc_or_desc = $params['asc_or_desc'];
		$pagination_url_args = $params['pagination_url_args'];
		ob_start();
		$unexpected_error_message = sprintf( __('An unexpected error occurred while exporting data. %s Please contact 10Web Customer Care. %s', WDFMInstance(self::PLUGIN)->prefix), '<a href="https://help.10web.io/hc/en-us/requests/new?utm_source=form_maker&utm_medium=free_plugin" target="_blank">','</a>');
		echo '<div class="fm-unexpected_error_message fm-hide">' . WDW_FM_Library(self::PLUGIN)->message_id('', $unexpected_error_message, 'error') . '</div>';
		echo $this->body($params);
		// Pass the content to form.
		$action = add_query_arg( array_merge(
									array('page' => $page, 'task' => 'display', 'current_id' => $id, 'paged' => $page_number ),
									$pagination_url_args
								), $page_url);
		$form_attr = array(
			// 'id' => WDFMInstance(self::PLUGIN)->prefix . '_submissions',
			'id' => 'admin_form',
			'name' => WDFMInstance(self::PLUGIN)->prefix . '_submissions',
			'class' => WDFMInstance(self::PLUGIN)->prefix . '_submissions fm-table-submissions wd-form',
			'action' => $action
		);
		echo $this->form(ob_get_clean(), $form_attr);
	}

	/**
	* Generate page body.
	*
  * @param array $params
	* @return string Body html.
	*/
	public function body( $params = array() ) {
		global $wpdb;
		$form_id  = $params['id'];
		$page 	  = $params['page'];
		$page_url = $params['page_url'];
		$page_title = $params['page_title'];
		$pagination_url = $params['pagination_url'];
		$page_number = $params['page_number'];
		$page_per_num = $params['page_per_num'];

		$forms 	  	= $params['forms'];
		$statistics = $params['statistics'];
		$actions  	= $params['actions'];
		$blocked_ips = $params['blocked_ips'];

		$sorted_labels_id 	= $params['sorted_labels_id'];

		$sorted_label_types = $params['sorted_label_types'];
		$sorted_label_names = $params['sorted_label_names'];
		$sorted_label_names_original = $params['sorted_label_names_original'];
		$label_name_ids = $params['label_name_ids'];

		$where_choices 	= $params['where_choices'];
		$searched_ids 	= $params['searched_ids'];
		$order_by 		= esc_attr($params['order_by']);
		$asc_or_desc 	= $params['asc_or_desc'];

		$lists 		= $params['lists'];
		$style_id 	= $params['style_id'];
		$style_date = $params['style_date'];
		$style_ip 	= $params['style_ip'];

		$style_username 	= $params['style_username'];
		$style_useremail 	= $params['style_useremail'];
		$style_payment_info = $params['style_payment_info'];

		$oder_class_default = $params['oder_class_default'];
		$oder_class 		= $params['oder_class'];
		$m = $params['m'];

		$fm_settings = $params['fm_settings'];
		$group_id_s = $params['group_id_s'];
		$groupids   = $params['groupids'];
		$is_search 	= $params['is_search'];
		$is_stats 	= $params['is_stats'];

		$rows 		= $params['rows'];
		$rows_data 	= $params['rows_data'];
		$subs_count = $params['subs_count'];
		$pdf_data   = $params['pdf_data'];

    $savedb = isset($forms[$form_id]->savedb) ? $forms[$form_id]->savedb : 1;

		$verified_emails = array();

    echo $this->title(array(
			'title' => $page_title,
			'title_class' => 'wd-header',
		));

	?>
	<br>
	<div>
		<?php echo $this->select_form( array('id' => $form_id, 'forms' => $forms, 'page' => $page, 'page_url' => $page_url, 'order_by' => $order_by, 'asc_or_desc' => $asc_or_desc) ); ?>
		<div class="fm-reports">
			<div class="fm-tools-button">
				<div class="fm-total_entries"><?php echo $statistics["total_entries"]; ?></div>
				<?php echo _e('Entries', WDFMInstance(self::PLUGIN)->prefix);?>
			</div>
			<div class="fm-tools-button">
				<div class="fm-total_rate"><?php echo $statistics["conversion_rate"]; ?></div>
				<?php echo _e('Conversion Rate', WDFMInstance(self::PLUGIN)->prefix);?>
			</div>
			<div class="fm-tools-button">
				<div class="fm-total_views"><?php echo $statistics["total_views"] ? $statistics["total_views"] : 0; ?></div>
				<?php echo _e('Views', WDFMInstance(self::PLUGIN)->prefix);?>
			</div>
		</div>
		<div class="fm-export-tools">
			<?php
        $blocked_ips_link = add_query_arg(array( 'page' => 'blocked_ips' . WDFMInstance(self::PLUGIN)->menu_postfix ), $page_url);
        $xmllibactive = true;
        if( !class_exists("SimpleXMLElement") ) {
          $xmllibactive = false;
        }
        $msg = __('Form maker Export will not work correctly, as XML PHP extension is disabled on your website. Please contact your hosting provider and ask them to enable it.', WDFMInstance(self::PLUGIN)->prefix);

      ?>
			<a class="button" href="<?php echo $blocked_ips_link; ?>" target="_blank"><?php echo _e('Blocked IPs', WDFMInstance(self::PLUGIN)->prefix);?></a>
			<button class="button" onclick="export_submissions('csv', 0); return false;"><?php echo _e('Export to CSV', WDFMInstance(self::PLUGIN)->prefix);?></button>
			<button class="button" onclick="<?php if( $xmllibactive ) { ?> export_submissions('xml', 0); <?php } else { ?> alert('<?php echo $msg ?>'); <?php } ?> return false;"><?php echo _e('Export to XML', WDFMInstance(self::PLUGIN)->prefix);?></button>
		</div>
	</div>
	<div class="tablenav top">
	<?php
		echo $this->bulk_actions($actions);
		?>
		<div class="alignleft actions fm-bulk-actions">
			<input type="button" class="button action" onclick="toggleChBDiv(true); return false;" value="<?php echo _e('Add/Remove Columns', WDFMInstance(self::PLUGIN)->prefix);?>">
			<input type="hidden" name="hide_label_list" value="">
			<button type="button" class="button action" onclick="show_hide_filter(); return false;"><span class="show-filter <?php echo !($is_search) ? '' : 'hide'?>"><?php echo  __('Show Filters', WDFMInstance(self::PLUGIN)->prefix); ?></span> <span class="hide-filter <?php echo !($is_search) ? 'hide' : 'show'?>" ><?php echo __('Hide Filters', WDFMInstance(self::PLUGIN)->prefix);?></span></button>
			<span class="search_reset_button <?php echo ($is_search) ? '' : 'hide'; ?>">
				<input type="button" class="button action" onclick="fm_form_submit(event, 'admin_form'); return false;" value="<?php echo _e('Search', WDFMInstance(self::PLUGIN)->prefix);?>">
				<input type="button" class="button action <?php echo ($is_search) ? '' : 'hide'; ?>" onclick="remove_all(); fm_set_input_value('order_by', 'group_id'); fm_set_input_value('asc_or_desc', 'desc'); fm_form_submit(event, 'admin_form'); return false;" value="<?php echo _e('Reset', WDFMInstance(self::PLUGIN)->prefix);?>">
			</span>
		</div>
		<?php echo $this->pagination($pagination_url, $subs_count, $page_per_num); ?>
	</div>
	<div style="width: 100%;">
		<div class="table-wrapper-1 <?php echo ($m == 0) ? 'no-scroll' : ''; ?>">
			<div class="table-scroll-1"></div>
		</div>
		<div class="table-wrapper-2 <?php echo ($m == 0) ? 'no-scroll' : ''; ?>">
			<div class="table-scroll-2 submit_content" id="fm-scroll">
				<table id="fm-submission-lists" class="adminlist table table-striped wp-list-table widefat fixed pages">
				<thead>
				<tr class="fm_table_head">
					<td id="cb" class="column-cb check-column fm-column-not-hide">
						<label class="screen-reader-text" for="cb-select-all-1"><?php _e('Select all', WDFMInstance(self::PLUGIN)->prefix);?></label>
						<input type="checkbox" id="check_all">
					</td>
					<th id="submitid_fc" class="<?php if ( $order_by == "group_id" ) {
							echo $oder_class;
						}
						else {
							echo $oder_class_default;
						} ?> submitid_fc col_id" <?php echo $style_id; ?> data-colname="<?php _e('ID', WDFMInstance(self::PLUGIN)->prefix);?>">
						<a href="" class="sub_id" onclick="fm_set_input_value('order_by', 'group_id');
							fm_set_input_value('asc_or_desc', '<?php echo(($order_by == 'group_id' && $asc_or_desc == 'asc') ? 'desc' : 'asc'); ?>');
							fm_form_submit(event, 'admin_form')">
							<span><?php echo _e('ID', WDFMInstance(self::PLUGIN)->prefix);?></span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th id="submitsubmitdate_fc" class="column-primary col-submit-date fm-column-not-hide <?php if ( $order_by == "date" ) {
							echo $oder_class;
						  }
						  else {
							echo $oder_class_default;
						  } ?>" <?php echo $style_date; ?> data-colname="<?php _e('Submit date', WDFMInstance(self::PLUGIN)->prefix);?>">
						<a href="" onclick="fm_set_input_value('order_by', 'date');
						  fm_set_input_value('asc_or_desc', '<?php echo(($order_by == 'date' && $asc_or_desc == 'asc') ? 'desc' : 'asc'); ?>');
						  fm_form_submit(event, 'admin_form')">
						  <span><?php _e('Submit date', WDFMInstance(self::PLUGIN)->prefix);?></span>
						  <span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" id="submitterip_fc" class="submitterip_fc <?php if ( $order_by == "ip" ) {
						echo $oder_class;
					  }
					  else {
						echo $oder_class_default;
					  } ?>" <?php echo $style_ip; ?> data-colname="<?php _e('Submitter\'s IP', WDFMInstance(self::PLUGIN)->prefix);?>">
						<a href="" onclick="fm_set_input_value('order_by', 'ip');
						  fm_set_input_value('asc_or_desc', '<?php echo(($order_by == 'ip' && $asc_or_desc == 'asc') ? 'desc' : 'asc'); ?>');
						  fm_form_submit(event, 'admin_form')">
						  <span><?php _e('Submitter\'s IP', WDFMInstance(self::PLUGIN)->prefix);?></span>
						  <span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" id="submitterusername_fc" class="submitterusername_fc <?php if ( $order_by == "display_name" ) {
                        echo $oder_class;
                      }
                      else {
                        echo $oder_class_default;
                      } ?>" <?php echo $style_username; ?> data-colname="<?php _e('Submitter\'s Username', WDFMInstance(self::PLUGIN)->prefix);?>">
					<a href="" onclick="fm_set_input_value('order_by', 'display_name');
					  fm_set_input_value('asc_or_desc', '<?php echo(($order_by == 'display_name' && $asc_or_desc == 'asc') ? 'desc' : 'asc'); ?>');
					  fm_form_submit(event, 'admin_form')">
					  <span><?php _e('Submitter\'s Username', WDFMInstance(self::PLUGIN)->prefix);?></span>
					  <span class="sorting-indicator"></span>
					</a>
				    </th>
					<th scope="col" id="submitteremail_fc" class="submitteremail_fc <?php if ( $order_by == "user_email" ) {
						echo $oder_class;
					  }
					  else {
						echo $oder_class_default;
					  } ?>" <?php echo $style_useremail; ?> data-colname="<?php _e('Submitter\'s Email Address', WDFMInstance(self::PLUGIN)->prefix);?>">
						<a href="" onclick="fm_set_input_value('order_by', 'user_email');
						  fm_set_input_value('asc_or_desc', '<?php echo(($order_by == 'user_email' && $asc_or_desc == 'asc') ? 'desc' : 'asc'); ?>');
						  fm_form_submit(event, 'admin_form')">
						  <span><?php _e('Submitter\'s Email Address', WDFMInstance(self::PLUGIN)->prefix);?></span>
						  <span class="sorting-indicator"></span>
						</a>
					</th>
          <?php
          if( !empty($params['webhook_data']) && gettype($params['webhook_data']) == 'array' ) {
            ?>
            <th scope="col" id="webhook_fc" class="webhook_fc sortable">
              <span><?php _e('Webhook Status', WDFMInstance(self::PLUGIN)->prefix);?></span>
            </th>
            <?php
          }
					  $stripe_paypal = false;
					  for ( $i = 0; $i < count($sorted_label_names); $i++ ) {
						$styleStr = $this->model->hide_or_not($lists['hide_label_list'], $sorted_labels_id[$i]);
						$field_title = $this->model->get_type_address($sorted_label_types[$i], $sorted_label_names_original[$i]);
						$textdata = $this->gen_shorter_text($field_title, 42);

						if ( $sorted_label_types[$i] == 'type_paypal_payment_status' || $sorted_label_types[$i] == 'type_stripe' ) {
							$stripe_paypal = true;
							if ( $sorted_label_types[$i] != 'type_stripe' ) {
							?>
							<th id="<?php echo $sorted_labels_id[$i] . '_fc'; ?>" class="table_large_col <?php echo $sorted_labels_id[$i] . '_fc ';
							if ( $order_by == $sorted_labels_id[$i] . "_field" ) {
							  echo $oder_class . '"';
							}
							else {
								echo $oder_class_default . '"';
							} ?>" <?php echo $styleStr; ?> data-colname="<?php echo $field_title; ?>">
							  <a href="" onclick="fm_set_input_value('order_by', '<?php echo $sorted_labels_id[$i] . '_field'; ?>'); fm_set_input_value('asc_or_desc', '<?php echo(($order_by == $sorted_labels_id[$i] . '_field' && $asc_or_desc == 'asc') ? 'desc' : 'asc'); ?>'); fm_form_submit(event, 'admin_form')">
								<span><?php echo $textdata['text']; ?></span>
								<span class="sorting-indicator"></span>
							  </a>
							</th>
							<?php
							}
						}
            //remove followed types columns from submissions
            elseif ($sorted_label_types[$i] == 'type_user_email' || $sorted_label_types[$i] == 'type_user_email_verify' ) {
              continue;
            } else {
						?>
						  <th <?php echo $styleStr; ?> id="<?php echo $sorted_labels_id[$i] . '_fc'; ?>" class="<?php echo ($sorted_label_types[$i] == 'type_mark_map' || $sorted_label_types[$i] == 'type_matrix') ? 'table_large_col ' : '';
							  echo $sorted_labels_id[$i] . '_fc ';
							  if ( $order_by == $sorted_labels_id[$i] . "_field" ) {
								echo $oder_class . '"';
							  }
							  else {
								echo $oder_class_default . '"';
							  } ?>" data-colname="<?php echo $field_title; ?>">
							<a href="" onclick="fm_set_input_value('order_by', '<?php echo $sorted_labels_id[$i] . '_field'; ?>'); fm_set_input_value('asc_or_desc', '<?php echo(($order_by == $sorted_labels_id[$i] . '_field' && $asc_or_desc == 'asc') ? 'desc' : 'asc'); ?>'); fm_form_submit(event, 'admin_form')">
							  <span <?php if($textdata['status']) { ?> class="fm_masterTooltip" title="<?php echo $field_title; ?>" <?php } ?> ><?php echo $textdata['text']; ?></span>
							  <span class="sorting-indicator"></span>
							</a>
						  </th>
						  <?php
						}
					}
					if( $stripe_paypal ) { ?>
						<th id="payment_info_fc" class="column-author payment_info_fc" <?php echo $style_payment_info; ?> data-colname="<?php _e('Payment Info', WDFMInstance(self::PLUGIN)->prefix); ?>"><?php _e('Payment Info', WDFMInstance(self::PLUGIN)->prefix); ?></th>
					<?php  } ?>
				</tr>
				<tr id="fm-fields-filter" style="display: none;">
					<th class="fm-column-not-hide"></th>
					<th class="submitid_fc" <?php echo $style_id; ?> >
						<input type="text" name="id_search" id="id_search" value="<?php echo esc_html($lists['id_search']); ?>" style="width:30px" />
						<input type="hidden" name="fm_is_search" id="fm_is_search" value="0" />
					</th>
					<th class="submitdate_fc fm-column-not-hide" <?php echo $style_date; ?>>
						<table align="center" style="margin:unset" class="simple_table">
						  <tr class="simple_table">
							<td class="simple_table fm-column-not-hide" style="text-align: left;">From:</td>
							<td  class="simple_table fm-column-not-hide" style="text-align: center;">
							  <input class="inputbox" type="text" name="startdate" id="startdate" size="10" maxlength="10" value="<?php echo esc_html($lists['startdate']); ?>" />
							</td>
						  </tr>
						  <tr class="simple_table">
							<td class="simple_table fm-column-not-hide" style="text-align: left;">To:</td>
							<td class="simple_table fm-column-not-hide" style="text-align: center;">
							  <input class="inputbox" type="text" name="enddate" id="enddate" size="10" maxlength="10" value="<?php echo esc_html($lists['enddate']); ?>" />
							</td>
						  </tr>
						</table>
					</th>
					<th class="submitterip_fc" <?php echo $style_ip; ?>>
						<input type="text" name="ip_search" id="ip_search" value="<?php echo esc_html($lists['ip_search']); ?>" />
					</th>
					<th class="submitterusername_fc" <?php echo $style_username; ?>>
						<input type="text" name="username_search" id="username_search" value="<?php echo esc_html($lists['username_search']); ?>" />
					</th>
					<th class="submitteremail_fc" <?php echo $style_useremail; ?>>
						<input type="text" name="useremail_search" id="useremail_search" value="<?php echo esc_html($lists['useremail_search']); ?>" />
					</th>
					<?php
						$column_count = 0;
						for ( $i = 0; $i < count($sorted_label_names); $i++ ) {
              //remove input from search row
              if ( $sorted_label_types[$i] == 'type_stripe' || $sorted_label_types[$i] == 'type_user_email' || $sorted_label_types[$i] == 'type_user_email_verify' ) {
                continue;
              }
							$styleStr = $this->model->hide_or_not($lists['hide_label_list'], $sorted_labels_id[$i]);
							if ( !$is_search ) {
							  if ( $lists[$form_id . '_' . $sorted_labels_id[$i] . '_search'] || isset($lists[$form_id . '_' . $sorted_labels_id[$i] . '_search_verified']) ) {
								  $is_search = TRUE;
							  }
							}
							switch ( $sorted_label_types[$i] ) {
							  case 'type_mark_map': ?>
								<th class="table_large_col <?php echo $sorted_labels_id[$i]; ?>_fc" <?php echo $styleStr; ?>></th>
								<?php
                  break;
							  case 'type_paypal_payment_status': ?>
								<th class="table_large_col <?php echo $sorted_labels_id[$i]; ?>_fc" <?php echo $styleStr; ?>>
								  <select style="font-size: 11px; margin: 0; padding: 0; height: inherit;" name="<?php echo $form_id . '_' . $sorted_labels_id[$i]; ?>_search" id="<?php echo $form_id . '_' . $sorted_labels_id[$i]; ?>_search" value="<?php echo $lists[$form_id . '_' . $sorted_labels_id[$i] . '_search']; ?>">
									<option value=""></option>
									<option value="canceled">Canceled</option>
									<option value="cleared">Cleared</option>
									<option value="cleared by payment review">Cleared by payment review</option>
									<option value="completed">Completed</option>
									<option value="denied">Denied</option>
									<option value="failed">Failed</option>
									<option value="held">Held</option>
									<option value="in progress">In progress</option>
									<option value="on hold">On hold</option>
									<option value="paid">Paid</option>
									<option value="partially refunded">Partially refunded</option>
									<option value="pending verification">Pending verification</option>
									<option value="placed">Placed</option>
									<option value="processing">Processing</option>
									<option value="refunded">Refunded</option>
									<option value="refused">Refused</option>
									<option value="removed">Removed</option>
									<option value="returned">Returned</option>
									<option value="reversed">Reversed</option>
									<option value="temporary hold">Temporary hold</option>
									<option value="unclaimed">Unclaimed</option>
								  </select>
								  <script>
									var element = document.getElementById('<?php echo $form_id . '_' . $sorted_labels_id[$i]; ?>_search');
									element.value = '<?php echo $lists[$form_id . '_' . $sorted_labels_id[$i] . '_search']; ?>';
								  </script>
								</th>
								<th class="table_large_col payment_info_fc" <?php echo $style_payment_info; ?>>&nbsp;</th>
								<?php
							  break;
                case 'type_submitter_mail':
                  $query = $wpdb->prepare('SELECT id FROM ' . $wpdb->prefix . 'formmaker_submits WHERE form_id =%d AND element_label="verifyInfo" AND element_value="verified**%d"', $form_id, $sorted_labels_id[$i]);
                  $is_verified_exist = $wpdb->get_var($query);
                  ?>
                  <th class="<?php echo $sorted_labels_id[$i]; ?>_fc" <?php echo $styleStr; ?>>
                    <div>
                    <input name="<?php echo $form_id . '_' . $sorted_labels_id[$i] . '_search'; ?>" id="<?php echo $form_id . '_' . $sorted_labels_id[$i] . '_search'; ?>" type="text" value="<?php echo $lists[$form_id . '_' . $sorted_labels_id[$i] . '_search']; ?>">
                    <?php if ( $is_verified_exist ) { ?>
                      <label for="<?php echo $form_id . '_' . $sorted_labels_id[$i] . '_search_verified'; ?>">Verified</label>
                      <input name="<?php echo $form_id . '_' . $sorted_labels_id[$i] . '_search_verified'; ?>" id="<?php echo $form_id . '_' . $sorted_labels_id[$i] . '_search_verified'; ?>" type="checkbox" <?php if ( isset($lists[$form_id . '_' . $sorted_labels_id[$i] . '_search_verified']) ) {
                      echo "checked='checked'";
                      } ?> onChange="this.form.submit();">
                    <?php } ?>
                    </div>
                  </th>
                  <?php
                  break;
                default: ?>
								<th class="<?php echo $sorted_labels_id[$i]; ?>_fc" <?php echo $styleStr; ?>>
								  <input name="<?php echo $form_id . '_' . $sorted_labels_id[$i] . '_search'; ?>" id="<?php echo $form_id . '_' . $sorted_labels_id[$i] . '_search'; ?>" type="text" value="<?php echo stripslashes( htmlspecialchars( urldecode($lists[$form_id . '_' . $sorted_labels_id[$i] . '_search'] ) ) ); ?>">
								</th>
								<?php
							break;
							}
            }
						$column_count = $i;
					?>
				</tr>
				</thead>
				<tbody>
				<?php
				$k = 0;
				if( !empty($group_id_s) ) {
          $data_array = array();
          for ( $i = 0; $i < count( $sorted_label_types ); $i++ ) {
            $data_array[ $sorted_labels_id[ $i ] ] = $sorted_label_types[ $i ];
          }
          for ( $www = 0, $qqq = count( $group_id_s ); $www < $qqq; $www ++ ) {
            $i           = $group_id_s[ $www ];
            $alternate   = ( ! isset( $alternate ) || $alternate == 'alternate' ) ? '' : 'alternate';
            $temp        = $this->model->array_for_group_id( $group_id_s[ $www ], $rows );
            $data        = $temp[0];
            $user_email = '';
            $userinfo = get_userdata($data->user_id_wd);
            for ( $i = 0; $i < count($temp); $i++ ) {
              if ( $temp[$i]->element_label === 'user_email' ) {
                $user_email = $temp[$i]->element_value;
                break;
              }
            }
            if ( $user_email ) {
              $useremail = $user_email;
            }
            else {
              if ( $userinfo ) {
                $useremail = $userinfo->user_email;
              } else {
                $useremail = '';
              }
            }
            $username = $userinfo ? $userinfo->display_name : "";
        		?>
						<tr id="tr_<?php echo $data->group_id; ?>" class="<?php echo $alternate; ?>">
							<th class="check-column fm-column-not-hide">
								<input type="checkbox" id="check_<?php echo $data->group_id; ?>" name="check[<?php echo $data->group_id; ?>]">
							</th>
							<td id="submitid_fc" class="submitid_fc col-id" data-colname="<?php _e('ID', WDFMInstance(self::PLUGIN)->prefix);?>" <?php echo $style_id; ?>>
								<a href="" onclick="fm_set_input_value('task', 'edit'); fm_set_input_value('current_id',<?php echo $data->group_id; ?>); fm_form_submit(event, 'admin_form');"><?php echo $data->group_id; ?></a>
							</td>
							<td class="column-primary col-submit-date fm-column-not-hide" data-colname="<?php _e('Submit date', WDFMInstance(self::PLUGIN)->prefix);?>" <?php echo $style_date; ?>>
								<?php
									$view_url 		= add_query_arg( array(
																  'action'	 => 'FormMakerSubmits' . WDFMInstance(self::PLUGIN)->plugin_postfix,
																  'group_id' => $data->group_id,
																  'form_id'  => $form_id,
																  'width'	=> '600',
																  'height' 	=> '500',
																  'nonce' => $this->fm_nonce,
																  'TB_iframe' => '1',
																), admin_url('admin-ajax.php'));

									$edit_url		= add_query_arg( array( 'task' => 'edit', 'current_id' => $data->group_id, 'form_id' => $form_id, 'paged' => $page_number, WDFMInstance(self::PLUGIN)->nonce => wp_create_nonce(WDFMInstance(self::PLUGIN)->nonce) ), $pagination_url );
									$delete_url 	= add_query_arg( array( 'task' => 'delete', 'current_id' => $data->group_id, 'form_id' => $form_id, 'paged' => $page_number, WDFMInstance(self::PLUGIN)->nonce => wp_create_nonce(WDFMInstance(self::PLUGIN)->nonce) ), $pagination_url );
									$block_url 		= add_query_arg( array( 'task' => 'block_ip', 'current_id' => $data->group_id, 'form_id' => $form_id, 'paged' => $page_number, WDFMInstance(self::PLUGIN)->nonce => wp_create_nonce(WDFMInstance(self::PLUGIN)->nonce) ), $pagination_url );
									$unblock_url 	= add_query_arg( array( 'task' => 'unblock_ip', 'current_id' => $data->group_id, 'form_id' => $form_id, 'paged' => $page_number, WDFMInstance(self::PLUGIN)->nonce => wp_create_nonce(WDFMInstance(self::PLUGIN)->nonce) ), $pagination_url );

									$ip_infoin_popup_url = add_query_arg( array(
																	   'action' => 'FormMakerIpinfoinPopup' . WDFMInstance(self::PLUGIN)->plugin_postfix,
																	   'data_ip' => $data->ip,
																	   'width' => '450',
																	   'height' => '300',
                                                                        'nonce' => $this->fm_nonce,
																	   'TB_iframe' => '1',
																	 ), admin_url('admin-ajax.php'));

								?>
								<p><strong><a href="<?php echo $edit_url; ?>" target="_blank"><?php echo get_date_from_gmt( $data->date ); ?></a></strong></p>
								<div class="row-actions">
									<span><a href="<?php echo $view_url; ?>" class="thickbox thickbox-preview" title="<?php _e("View submission", WDFMInstance(self::PLUGIN)->prefix); ?>"><?php _e('View', WDFMInstance(self::PLUGIN)->prefix); ?></a> |</span>
									<span><a href="<?php echo $edit_url; ?>" target="_blank" title="<?php _e("Edit submission", WDFMInstance(self::PLUGIN)->prefix); ?>"><?php _e('Edit', WDFMInstance(self::PLUGIN)->prefix); ?></a> |</span>
									<?php if ( $pdf_data && isset($pdf_data[$group_id_s[$www]]) ){ ?>
										<span class="no-wrap"><a href="<?php echo site_url() . '/' . $pdf_data[$group_id_s[$www]]; ?>" download ><?php _e('Download PDF', WDFMInstance(self::PLUGIN)->prefix); ?></a> |</span>
									<?php } ?>
									<span class="trash"><a href="<?php echo $delete_url; ?>" title="<?php _e("Edit submission", WDFMInstance(self::PLUGIN)->prefix); ?>" onclick="if (!confirm('<?php echo addslashes(__('Do you want to delete selected item?', WDFMInstance(self::PLUGIN)->prefix)); ?>')) {return false;}"><?php _e('Delete', WDFMInstance(self::PLUGIN)->prefix); ?></a></span>
								</div>
								<button class="toggle-row" type="button"><span class="screen-reader-text"><?php _e("Show more details", WDFMInstance(self::PLUGIN)->prefix); ?></span></button>
							</td>
							<td id="submitterip_fc" class="submitterip_fc sub-align" <?php echo $style_ip; ?> data-colname="<?php _e('Show submitter information', WDFMInstance(self::PLUGIN)->prefix);?>">
								<?php if ( $data->ip ) { ?>
								<p><a class="thickbox-preview" href="<?php echo $ip_infoin_popup_url; ?>" title="<?php _e("Show submitter information", WDFMInstance(self::PLUGIN)->prefix); ?>" <?php echo (!in_array($data->ip, $blocked_ips)) ? '' : 'style="color: #FF0000;"'; ?>><?php echo $data->ip; ?></a></p>
												<div class="row-actions">
												<?php if( !in_array($data->ip, $blocked_ips) ){ ?>
													<span><a href="<?php echo $block_url; ?>" title="<?php _e("Block IP", WDFMInstance(self::PLUGIN)->prefix); ?>"><?php _e('Block IP', WDFMInstance(self::PLUGIN)->prefix); ?></a></span>
													<?php } else { ?>
													<span><a href="<?php echo $unblock_url; ?>" title="<?php _e("Unblock IP ", WDFMInstance(self::PLUGIN)->prefix); ?>"><?php _e('Unblock IP', WDFMInstance(self::PLUGIN)->prefix); ?></a></span>
												<?php } ?>
												</div>
								<?php } ?>
							</td>
							<td id="submitterusername_fc" class="table_large_col submitterusername_fc sub-align" <?php echo $style_username; ?> data-colname="<?php _e('Submitter\'s Username', WDFMInstance(self::PLUGIN)->prefix);?>">
								<p><?php echo $username; ?></p>
							</td>
							<td id="submitteremail_fc" class="table_large_col submitteremail_fc sub-align" <?php echo $style_useremail; ?> data-colname="<?php _e('Submitter\'s Email Address', WDFMInstance(self::PLUGIN)->prefix);?>">
								<p><?php echo $useremail; ?></p>
							</td>
						<?php
						if ( !empty($params['webhook_data']) && gettype($params['webhook_data']) == 'array' ) {
						?>
							<td id="webhook_fc" class="table_large_col webhook_fc sub-align" data-colname="<?php _e('Webhook Status', WDFMInstance(self::PLUGIN)->prefix);?>">
									<?php
									if( isset($params['webhook_data'][$data->group_id]) && $params['webhook_data'][$data->group_id] == '1' ) {
										echo '<div class="fm_wh_status_row"><p>' .  __('Successfully Sent', WDFMInstance(self::PLUGIN)->prefix). '</p></div>';
									} elseif ( isset($params['webhook_data'][$data->group_id]) && $params['webhook_data'][$data->group_id] == '2' ) {
					  echo '<div class="fm_wh_status_row"><p>' .  __('Unpublished', WDFMInstance(self::PLUGIN)->prefix). '</p></div>';
				  }
									elseif ( isset($params['webhook_data'][$data->group_id]) && !$params['webhook_data'][$data->group_id] ) {
									?>
										<div class="fm_wh_status_row">
											<p><?php echo __('Failed', WDFMInstance(self::PLUGIN)->prefix); ?></p>
											<p>|</p>
											<p>
												<a href="#" onclick="fm_wh_resend(this); return false" data-form_id = "<?php echo $form_id; ?>" data-group_id = "<?php echo $data->group_id; ?>" data-formtitle = "<?php echo $params['forms'][$form_id]->title; ?>">
													<?php _e('Resend', WDFMInstance(self::PLUGIN)->prefix); ?>
													<img src="<?php echo WDFMInstance(self::PLUGIN)->plugin_url ?>/images/arrow_right.svg" alt="">
												</a>
											</p>
											<span class="fm_wh_spinner hidden"></span>
										</div>
									<?php
									}
									?>
							</td>
						<?php
						}
						for ( $h = 0; $h < $m; $h++ ) {
							$ispaypal = false;
              if ( $sorted_label_types[$h] == 'type_stripe' || $sorted_label_types[$h] == 'type_user_email' || $sorted_label_types[$h] == 'type_user_email_verify' ) {
                continue;
              }
							$not_label = TRUE;
							for ( $g = 0; $g < count($temp); $g++ ) {
								if ( !isset($sorted_label_types[$g]) ) {
							      continue;
								}
								$styleStr = $this->model->hide_or_not($lists['hide_label_list'], $sorted_labels_id[$h]);
								$temp[$g]->element_value = $sorted_label_types[$g] != 'type_file_upload' ? esc_html($temp[$g]->element_value) : $temp[$g]->element_value;
								if ( $temp[$g]->element_label == $sorted_labels_id[$h] ) {
								  if ( strpos($temp[$g]->element_value, "***map***") ) {
										$map_params = explode('***map***', $temp[$g]->element_value);
										?>
										<td id="<?php echo $sorted_labels_id[$h]; ?>_fc" class="table_large_col <?php echo $sorted_labels_id[$h]; ?>_fc sub-align" <?php echo $styleStr; ?> data-colname="<?php _e('Show on Map', WDFMInstance(self::PLUGIN)->prefix);?>">
											<a class="thickbox-preview" href="<?php echo add_query_arg(array(
																										 'action' => 'FormMakerMapEditinPopup' . WDFMInstance(self::PLUGIN)->plugin_postfix,
																										 'long' => $map_params[0],
																										 'lat' => $map_params[1],
																										 'width' => '620',
																										 'height' => '550',
																										 'nonce' => $this->fm_nonce,
																										 'TB_iframe' => '1',
																									 ), admin_url('admin-ajax.php')); ?>" title="<?php _e("Show on Map", WDFMInstance(self::PLUGIN)->prefix); ?>"><?php _e("Show on Map", WDFMInstance(self::PLUGIN)->prefix); ?></a>
										</td>
									<?php
								  }
								  elseif ( strpos($temp[$g]->element_value, "*@@url@@*") ) {
									?>
										<td id="<?php echo $sorted_labels_id[$h]; ?>_fc" class="<?php echo $sorted_labels_id[$h]; ?>_fc sub-align" <?php echo $styleStr; ?> data-colname="<?php _e('URL', WDFMInstance(self::PLUGIN)->prefix);?>">
											<?php
											$new_files = explode("*@@url@@*", $temp[$g]->element_value);
											foreach ( $new_files as $new_file ) {
												if ( $new_file ) {
													$new_filename = explode('/', $new_file);
													$new_filename = $new_filename[count($new_filename) - 1];
													$get_file_type = strstr($new_filename,  ".");
													if ( $get_file_type == ".jpg" || $get_file_type == ".jpeg" || $get_file_type == ".png" || $get_file_type == ".bmp" || $get_file_type == ".svg" ) {
														$fm_fancybox_class = "fm_fancybox";
													} else {
														$fm_fancybox_class = "";
													} ?>
													<a target="_blank" class="<?php echo $fm_fancybox_class;?>" rel="group_<?php echo $www; ?>" href="<?php echo $new_file; ?>"><?php echo $new_filename; ?></a>
													<br />
													<?php
												}
											}
											?>
										</td>
									<?php
								  }
								  elseif ( strpos($temp[$g]->element_value, "***star_rating***") ) {
										$view_star_rating_array = $this->model->view_for_star_rating($temp[$g]->element_value, $temp[$g]->element_label);
										$stars = $view_star_rating_array[0];
										?>
										<td id="<?php echo $sorted_labels_id[$h]; ?>_fc" align="center" class="<?php echo $sorted_labels_id[$h]; ?>_fc sub-align" <?php echo $styleStr; ?> data-colname="<?php _e('Star rating', WDFMInstance(self::PLUGIN)->prefix);?>"><?php echo $stars; ?></td>
									<?php
								  }
								  elseif ( strpos($temp[$g]->element_value, "***matrix***") ) {
									?>
									<td id="<?php echo $sorted_labels_id[$h]; ?>_fc" class="table_large_col <?php echo $sorted_labels_id[$h]; ?>_fc sub-align" <?php echo $styleStr; ?> data-colname="<?php _e('Matrix', WDFMInstance(self::PLUGIN)->prefix);?>">
									  <a class="thickbox-preview" href="<?php echo add_query_arg(array(
																								   'action' => 'show_matrix' . WDFMInstance(self::PLUGIN)->plugin_postfix,
																								   'matrix_params' => str_replace('#', '%23', urlencode($temp[$g]->element_value)),
																								   'width' => '620',
																								   'height' => '550',
                                                   'nonce' => $this->fm_nonce,
																								   'TB_iframe' => '1',
																								 ), admin_url('admin-ajax.php')); ?>" title="<?php _e("Show Matrix", WDFMInstance(self::PLUGIN)->prefix); ?>"><?php _e("Show Matrix", WDFMInstance(self::PLUGIN)->prefix); ?></a>
									</td>
									<?php
								  }
								  elseif ( strpos($temp[$g]->element_value, "@@@") !== FALSE || $temp[$g]->element_value == "@@@" || $temp[$g]->element_value == "@@@@@@@@@" ) {
									?>
										<td id="<?php echo $sorted_labels_id[$h]; ?>_fc" class="<?php echo $sorted_labels_id[$h]; ?>_fc sub-align" <?php echo $styleStr; ?> data-colname="<?php echo !empty($label_name_ids[$sorted_labels_id[$h]]) ? $label_name_ids[$sorted_labels_id[$h]] : ''; ?>">
											<p><?php echo str_replace("@@@", " ", $temp[$g]->element_value); ?></p>
										</td>
									<?php
								  }
								  elseif ( strpos($temp[$g]->element_value, "***grading***") ) {
										$view_grading_array = $this->model->view_for_grading($temp[$g]->element_value);
										$items = $view_grading_array[0];
										?>
										<td id="<?php echo $sorted_labels_id[$h]; ?>_fc" class="<?php echo $sorted_labels_id[$h]; ?>_fc sub-align" <?php echo $styleStr; ?> data-colname="<?php _e('Grading', WDFMInstance(self::PLUGIN)->prefix);?>">
											<p><?php echo $items; ?></p>
										</td>
									<?php
								  }
								  else {
										// check is paypal status
										if ( $sorted_labels_id[$h] == 0 && !empty($temp[$g]->element_value) ) {
											$ispaypal = true;
										}
										if ( strpos($temp[$g]->element_value, "***quantity***") ) {
											$temp[$g]->element_value = str_replace("***quantity***", " ", $temp[$g]->element_value);
										}
										if ( strpos($temp[$g]->element_value, "***property***") ) {
											$temp[$g]->element_value = str_replace("***property***", " ", $temp[$g]->element_value);
										}
										if ( $sorted_label_types[$h] == "type_submitter_mail" ) {
                      $query = $wpdb->prepare('SELECT id FROM ' . $wpdb->prefix . 'formmaker_submits WHERE form_id =%d AND group_id=%d AND element_value="verified**%d"', $form_id, $data->group_id, $sorted_labels_id[$h]);
											$isverified = $wpdb->get_var($query);
											if ( $isverified ) {
												if ( !isset($verified_emails[$sorted_labels_id[$h]]) ) {
													$verified_emails[$sorted_labels_id[$h]] = array();
												}
                        $verified_emails[$sorted_labels_id[$h]][] = $data->group_id;
												?>
												<td class="<?php echo $sorted_labels_id[$h]; ?>_fc sub-align" id="<?php echo $sorted_labels_id[$h]; ?>_fc" <?php echo $styleStr; ?> data-colname="<?php echo !empty($label_name_ids[$sorted_labels_id[$h]]) ? $label_name_ids[$sorted_labels_id[$h]] : ''; ?>">
													<p><?php echo $temp[$g]->element_value; ?>
														<div style="color:#2DA068;">( <?php _e("Verified", WDFMInstance(self::PLUGIN)->prefix); ?> <img src="<?php echo WDFMInstance(self::PLUGIN)->plugin_url . '/images/verified.png'; ?>" /> )</div>
													</p>
												</td>
											<?php
											}
											else { ?>
												<td id="<?php echo $sorted_labels_id[$h]; ?>_fc" class="<?php echo $sorted_labels_id[$h]; ?>_fc sub-align" <?php echo $styleStr; ?> data-colname="<?php echo !empty($label_name_ids[$sorted_labels_id[$h]]) ? $label_name_ids[$sorted_labels_id[$h]] : ''; ?>">
													<p><?php  echo $temp[$g]->element_value; ?></p>
												</td>
											<?php
											}
										}
										elseif ( $sorted_label_types[$h] == "type_password" ) {
											$element_value = '***';
											?>
											<td id="<?php echo $sorted_labels_id[$h]; ?>_fc" class="<?php echo $sorted_labels_id[$h]; ?>_fc sub-align" <?php echo $styleStr; ?> data-colname="<?php echo !empty($label_name_ids[$sorted_labels_id[$h]]) ? $label_name_ids[$sorted_labels_id[$h]] : ''; ?>">
											<p><?php echo $element_value; ?></p>
											</td>
											<?php
										}
										else {
											$element_value = str_replace("***br***", '<br>', $temp[$g]->element_value );
                      if ( $sorted_label_types[$h] == "type_textarea" ) {
                        $element_value = esc_html( strip_tags( html_entity_decode($element_value) ) );
                      }
											$textdata = $this->gen_shorter_text($element_value, 100);
											$status_column_width = ( $sorted_label_types[$h] == 'type_paypal_payment_status' ) ? '300px' : '';

											/* Don't show payments with not succeeded status in Submissions table, when "After payment has been successfully completed." option is enabled */
											if ( $textdata['text'] != "succeeded" && $textdata['text'] != "requires_capture" && $textdata['text'] != "Completed"  ) {
												$check_payment_status = 'data-status = "0"';
											}
											else {
												$check_payment_status = 'data-status = "1"';
											}
											?>
											<td id="<?php echo $sorted_labels_id[$h]; ?>_fc" class="<?php echo $sorted_labels_id[$h]; ?>_fc sub-align" <?php echo $styleStr; ?> data-colname="<?php echo !empty($label_name_ids[$sorted_labels_id[$h]]) ? $label_name_ids[$sorted_labels_id[$h]] : ''; ?>" <?php echo ($savedb == 2 &&  $sorted_label_types[$h] == "type_paypal_payment_status") ? $check_payment_status : ""; ?> style="width:<?php echo $status_column_width; ?>; max-width:<?php echo $status_column_width; ?>;">
												<?php if ( $sorted_label_types[$h] == 'type_signature' ) { ?>
													<img src="<?php echo $textdata['text']; ?>" style="width:50px; border: 1px solid #ddd;"/>
												<?php
												}
												elseif ( $sorted_label_types[$h] == 'type_hidden' ) {
												  echo html_entity_decode($element_value);
												}
												else {
												  /* Check for Stripe case */
													if ( $sorted_label_types[$h] == "type_paypal_payment_status" && $textdata['text'] == "requires_capture" ) {
                          ?>
                              <button class="wd-button button-primary" onclick="change_stripe_status(this); return false;"><?php _e('Capture', WDFMInstance(self::PLUGIN)->prefix); ?></button><img src="<?php echo WDFM()->plugin_url ?>/images/loading.gif" class="fm-capture-loading fm-hidden">
													<?php
													} else { ?>
															<p><?php echo $textdata['text']; ?></p>
														<?php
													}
												}
												?>
											</td>
											<?php
										}
								  }
								  $not_label = FALSE;
								}
							}
							if ( $not_label ) { ?>
								<td id="<?php echo $sorted_labels_id[$h]; ?>_fc" class="<?php echo $sorted_labels_id[$h]; ?>_fc sub-align" <?php echo $styleStr; ?>>
								  <p>&nbsp;</p>
								</td>
								<?php
              }
						}
						if ( $ispaypal ) { ?>
							<td id="payment_info_fc" class="payment_info_fc sub-align" <?php echo $style_payment_info; ?> data-colname="<?php _e('Payment information', WDFMInstance(self::PLUGIN)->prefix); ?>">
                <a class="thickbox-preview" href="<?php echo add_query_arg(array(
                                                                             'action' => 'paypal_info' . WDFMInstance(self::PLUGIN)->plugin_postfix,
                                                                             'id' => $data->group_id,
                                                                             'width' => '600',
                                                                             'height' => '500',
                                                                             'nonce' => $this->fm_nonce,
                                                                             'TB_iframe' => '1',
                                                                           ), admin_url('admin-ajax.php')); ?>"
                   title="<?php _e("Payment information", WDFMInstance(self::PLUGIN)->prefix); ?>">
                  <img src="<?php echo WDFMInstance(self::PLUGIN)->plugin_url . '/images/info.png'; ?>"/>
                </a>
							</td>
						<?php
						}
						?>
					  </tr>
					  <?php
					  $k = 1 - $k;
					}
				}
				else {
					echo WDW_FM_Library(self::PLUGIN)->no_items('submissions');
				}
				?>
				</tbody>
			  </table>
			</div>
		</div>
	</div>
	<div class="tablenav bottom">
	<?php echo $this->pagination($pagination_url, $subs_count, $page_per_num); ?>
	</div>
	<?php
	if ( $sorted_label_types ) {
		foreach ( $sorted_label_types as $key => $sorted_label_type ) {
			if ( $this->model->check_radio_type($sorted_label_type) ) {
				$is_stats = TRUE;
				break;
			}
		}
		if ( $is_stats ) {
			$ajax_nonce = wp_create_nonce( WDFMInstance(self::PLUGIN)->nonce );
			?>
			<div class="fm-statistics">
				<h2><?php echo _e('Statistics', WDFMInstance(self::PLUGIN)->prefix);?></h2>
				<table class="stats">
					<tr>
						<td colspan="3">
							<select id="sorted_label_key">
								<option value=""><?php echo _e('- Select -', WDFMInstance(self::PLUGIN)->prefix);?></option>
								<?php
								foreach ( $sorted_label_types as $key => $sorted_label_type ) {
									if ( $sorted_label_type == "type_checkbox" || $sorted_label_type == "type_radio" || $sorted_label_type == "type_own_select" || $sorted_label_type == "type_country" || $sorted_label_type == "type_paypal_select" || $sorted_label_type == "type_paypal_radio" || $sorted_label_type == "type_paypal_checkbox" || $sorted_label_type == "type_paypal_shipping" ) {
										?>
										<option value="<?php echo $key; ?>"><?php echo $sorted_label_names_original[$key]; ?></option>
										<?php
									}
								}
								?>
							</select>
							<p class="fm_error_sorted_label_key"><?php echo _e('Please select the field!', WDFMInstance(self::PLUGIN)->prefix);?></p>
						</td>
					</tr>
					<tr>
						<td>
							<label style="margin-left: 7px;"><?php echo _e('Date From:', WDFMInstance(self::PLUGIN)->prefix);?></label>
						</td>
						<td>
							<input class="inputbox" type="text" name="startstats" id="startstats" size="9" maxlength="9" />
							<?php echo _e('To:', WDFMInstance(self::PLUGIN)->prefix);?>
		<input class="inputbox" type="text" name="endstats" id="endstats" size="9" maxlength="9" />
						</td>
						<td>
							<span class="fm-div_stats-loading spinner"></span>
							<button class="button" onclick="show_stats(); return false;"><?php _e('Show', WDFMInstance(self::PLUGIN)->prefix); ?></button>
						</td>
					</tr>
				</table>
				<div id="div_stats"></div>
			</div>
			<script>
	show_stats_url = "<?php echo add_query_arg( array('action' => 'get_stats' . WDFMInstance(self::PLUGIN)->plugin_postfix, 'task' => 'show_stats', 'nonce' => $this->fm_nonce, 'current_id' => $form_id ), $page_url); ?>";
</script>
			<?php
		}
	}
	?>
	<div class="fm_modal">
		<div id="fm-progressbar">
			<div class="fm-progress-label">Loading...</div>
		</div>
    </div>
	<div class="export_progress">
		<span class="exp_count"><?php echo $subs_count; ?></span> left from <?php echo $subs_count; ?>
    </div>
  <div id="sbox-overlay" onclick="toggleChBDiv(false);"></div>
	<script>
		<?php
			if ( isset($sorted_label_names) ) {
			  $templabels = array_merge(array(
										  'submitid',
										  'submitterip',
										  'submitterusername',
										  'submitteremail',
										), $sorted_labels_id);
			  $sorted_label_names_for_check = array_merge(array(
															'ID',
															"Submitter's IP",
															"Submitter's Username",
															"Submitter's Email Address",
														  ), $sorted_label_names_original);
			}
			else {
			  $templabels = array(
				'submitid',
				'submitterip',
				'submitterusername',
				'submitteremail',
			  );
			  $sorted_label_names_for_check = array(
				'ID',
				"Submitter's IP",
				'Submitter\'s Username',
				'Submitter\'s Email Address',
			  );
			}
		?>
		function clickLabChBAll( ChBAll ) {
			ChBAll.classList.remove("fm-remove_before");
			if ( ChBAll.checked ) {
				document.forms.admin_form.hide_label_list.value = '';
				var ChBDivInputs = document.getElementById('ChBDiv').getElementsByTagName('input');
				for (var i = 1, input; input = ChBDivInputs[i++]; ) {
					if ( input.id != 'ChBAll') {
						input.checked = ChBAll.checked;
					}
				}
			}
			else {
			  document.forms.admin_form.hide_label_list.value = '@<?php echo implode('@@', $templabels) ?>@' + '@payment_info@';
			  for (i = 0; i <= ChBAll.form.length; i++) {
				if (typeof(ChBAll.form[i]) != "undefined") {
				  if (ChBAll.form[i].type == "checkbox") {
						ChBAll.form[i].checked = false;
				  }
				}
			  }
      }
			renderColumns();
		}
	</script>
	<div id="ChBDiv">
		<p class="add-col-header"><?php _e('Select columns', WDFMInstance(self::PLUGIN)->prefix); ?></p>
		<div class="fm_check_labels">
			<input type="checkbox" <?php echo ( !$lists['hide_label_list'] ) ? 'checked="checked"' : ''; ?> onclick="clickLabChBAll(this)" id="ChBAll" />
			<label for="ChBAll"><?php _e('All', WDFMInstance(self::PLUGIN)->prefix); ?></label>
		</div>
		<?php foreach ( $templabels as $key => $curlabel ) {
      //remove user_email and verifyInfo from add/remove column list
      if ( $curlabel == 'user_email' || $curlabel == 'verifyInfo' ) {
        continue;
      }
			if ( strpos($lists['hide_label_list'], '@' . $curlabel . '@') === FALSE ) {
			?>
			<div class="fm_check_labels">
			  <input type="checkbox" checked="checked" onclick="clickLabChB('<?php echo $curlabel; ?>', this)" id="fm_check_id_<?php echo $curlabel; ?>" />
			  <label for="fm_check_id_<?php echo $curlabel; ?>"> <?php echo stripslashes($sorted_label_names_for_check[$key]); ?></label>
			</div>
		<?php }
		else {
		?>
			<div class="fm_check_labels">
			  <input type="checkbox" onclick="clickLabChB('<?php echo $curlabel; ?>', this)" id="fm_check_id_<?php echo $curlabel; ?>" />
			  <label for="fm_check_id_<?php echo $curlabel; ?>"> <?php echo stripslashes($sorted_label_names_for_check[$key]); ?></label>
			</div>
        <?php
      }
    }
    $ispaypal = FALSE;
    for ( $i = 0; $i < count($sorted_label_names); $i++ ) {
      if ( $sorted_label_types[$i] == 'type_paypal_payment_status' || $sorted_label_types[$i] == 'type_stripe' ) {
        $ispaypal = TRUE;
      }
    }
    if ( $ispaypal ) {
      ?>
      <div class="fm_check_labels">
        <input type="checkbox" onclick="clickLabChB('payment_info', this)" id="fm_check_payment_info" <?php echo (strpos($lists['hide_label_list'], '@payment_info@') === FALSE) ? 'checked="checked"' : ''; ?> />
        <label for="fm_check_payment_info"><?php _e('Payment info', WDFMInstance(self::PLUGIN)->prefix); ?></label>
      </div>
      <?php
    }
    if ( !empty($params['webhook_data']) && gettype($params['webhook_data']) == 'array' ) {
      ?>
      <div class="fm_check_labels">
        <input type="checkbox" onclick="clickLabChB('webhook', this)" id="fm_check_webhook" <?php echo (strpos($lists['hide_label_list'], '@webhook@') === FALSE) ? 'checked="checked"' : ''; ?> />
        <label for="fm_check_webhooko"><?php _e('Webhook Status', WDFMInstance(self::PLUGIN)->prefix); ?></label>
      </div>

      <?php
    }

    ?>
    <div class="done-cont">
      <button onclick="toggleChBDiv(false); return false;" class="button button-primary"><?php _e('Done', WDFMInstance(self::PLUGIN)->prefix); ?></button>
    </div>
  </div>
	<script type="text/javascript">
		var index = 1;
		var minLimit = 1;
		var total_count = '<?php echo $subs_count; ?>';
		var page_num_update = false;
		var page_num = '<?php echo !empty($fm_settings['ajax_export_per_page']) ? $fm_settings['ajax_export_per_page'] : 1000; ?>';
		var groupids = <?php echo json_encode($groupids);?>;
		var groupids_string = '';

		/**
		 * Export submissions.
		 *
		 * @param string type
		 * @param int    limitstart
		 * @param null   ids
		 */
		function export_submissions(type, limitstart, ids) {
			var verified_emails = jQuery('#verified_emails').val();
			if ( limitstart == 0) {
				jQuery('.fm_modal').show();
				loading_progress(total_count, limitstart);
				if( typeof ids == "undefined" || ids === null ) {
					 var ids = groupids.slice(limitstart, page_num);
					 groupids_string = ids.toString();
				}
			}
			jQuery.ajax({
			  type: "POST",
			  url: "<?php echo add_query_arg(array(
											   'form_id' => $form_id,
											   'send_header' => 0,
                                               'nonce' => $this->fm_nonce,
											 ), admin_url('admin-ajax.php')); ?>&action=generete_" + type + "<?php echo WDFMInstance(self::PLUGIN)->plugin_postfix; ?>&limitstart=" + limitstart,
			  data: {
					page_num: page_num,
					page_num_update: page_num_update,
					groupids: groupids_string,
					verified_emails: verified_emails
			  },
			  beforeSend: function () {
				if (  parseInt(page_num) <= total_count ) {
					jQuery('.fm_modal').show();
				}
			  },
			  success: function (response, textStatus, xhr) {
				if ( limitstart < total_count ) {
					  limitstart = limitstart + parseInt(page_num);
					  ++index;
					  if ( index <= parseInt(total_count) ) {
						  end = index*parseInt(page_num);
						  start = end - parseInt(page_num);
						  ids = groupids.slice(start, end);
						  groupids_string = ids.toString();
					  }
					  if ( limitstart >= total_count ) {
						ids = '';
						index = 1;
						start = parseInt(page_num);
					  }
					  if ( ids ) {
						 export_submissions(type, limitstart, ids);
					  }
					  else {
						ajax_export_end(total_count, type, limitstart);
					  }
					  loading_progress(total_count, limitstart);
					}
					else {
						ajax_export_end(total_count, type, limitstart);
					}
			  },
			  error: function (xhr, ajaxOptions, thrownError) {
					limitstart = 0;
					index = 1;
					page_num = parseInt(page_num / 	2);
					if ( page_num >= minLimit ) {
						page_num_update = true;
						export_submissions(type, 0, null);
					}
					if ( page_num < minLimit ) {
						page_num = parseInt(page_num * 2);
						page_num_update = false;
						jQuery('.fm_modal').hide();
						jQuery('.fm-unexpected_error_message').show();
						return false;
					}
			  }
			});
		}

		/**
		 * Ajax export end.
		 *
		 * @param int total_count
		 * @param string type
		 * @param int limitstart
		 */
		function ajax_export_end(total_count, type, limitstart) {
			jQuery('.fm_modal').hide();
			jQuery('.fm-unexpected_error_message').hide();
			loading_progress(total_count, 0);

			window.location = "<?php echo add_query_arg(array(
													'form_id' => $form_id,
													'send_header' => 1,
													'nonce' => $this->fm_nonce,
												  ), admin_url('admin-ajax.php')); ?>&action=generete_" + type + "<?php echo WDFMInstance(self::PLUGIN)->plugin_postfix; ?>&limitstart=" + limitstart;

		}

		/**
		 * Loading progress.
		 *
		 * @param int count
		 * @param int start
		 */
		function loading_progress(count, start) {
			var text = 'Loading ...';
			var progressbar = jQuery("#fm-progressbar");
			var progressLabel = jQuery(".fm-progress-label");
				progressbar.progressbar({
					max: count
				});
				if ( start != 0 ) {
					var interval = Math.round( (progressbar.progressbar("value") * 100) / parseInt(count) );
					text = interval + ' %';
				}
				progressbar.progressbar("value", start);
				progressLabel.text(text);
				progressbarValue = progressbar.find(".fm-progress-label");
				progressbarValue.css({
					"color": '#444',
				});
		}

		function remove_all() {
			if (document.getElementById('startdate')) {
			  document.getElementById('startdate').value = '';
			}
			if (document.getElementById('enddate')) {
			  document.getElementById('enddate').value = '';
			}
			if (document.getElementById('id_search')) {
			  document.getElementById('id_search').value = '';
			}
			if (document.getElementById('ip_search')) {
			  document.getElementById('ip_search').value = '';
			}
			if (document.getElementById('username_search')) {
			  document.getElementById('username_search').value = '';
			}
			if (document.getElementById('useremail_search')) {
			  document.getElementById('useremail_search').value = '';
			}
			<?php
			foreach( $sorted_label_types as $slt_index => $slt_val ) {
            if ($slt_val != "type_mark_map" && $slt_val != "type_stripe") {
				?>
					document.getElementById('<?php echo $form_id . '_' . $sorted_labels_id[$slt_index] . '_search'; ?>').value = '';
				<?php
				}
				if ($slt_val == "type_submitter_mail") {
				?>
					if (document.getElementById('<?php echo $form_id . '_' . $sorted_labels_id[$slt_index] . '_search_verified'; ?>')) {
					  document.getElementById('<?php echo $form_id . '_' . $sorted_labels_id[$slt_index] . '_search_verified'; ?>').checked = false;
					}
				<?php
				}
			}
			?>
		}

		<?php if ( $is_search ) { ?>
			document.getElementById('fm-fields-filter').style.display = '';
		<?php } ?>
	</script>
	<input id="form_id" name="form_id" type="hidden" value="<?php echo $form_id; ?>">
	<input type="hidden" name="asc_or_desc" id="asc_or_desc" value="<?php echo $asc_or_desc; ?>"/>
	<input type="hidden" name="order_by" id="order_by" value="<?php echo esc_attr($order_by); ?>"/>
	<?php
	}

  /**
   * Show stats.
   *
   * @param array $params
   * @return string
   */
  public function show_stats( $params = array() ) {
	ob_start();
    $sorted_label_name_original = $params['sorted_label_name_original'];
    $choices_labels = $params['choices_labels'];
    $choices_count = $params['choices_count'];
    $all = $params['all'];
    $unanswered = $params['unanswered'];
    $colors = $params['colors'];
    $choices_colors = $params['choices_colors'];
    ?>
    <br />
    <div class="field-label"><?php echo stripslashes($sorted_label_name_original); ?></div>
    <table class="adminlist">
      <thead>
        <tr>
          <th width="20%"><?php _e('Choices', WDFMInstance(self::PLUGIN)->prefix); ?></th>
          <th><?php _e('Percentage', WDFMInstance(self::PLUGIN)->prefix); ?></th>
          <th class="fm-statCount"><?php _e('Count', WDFMInstance(self::PLUGIN)->prefix); ?></th>
        </tr>
      </thead>
      <?php
      $k = 0;
      if ( !empty($choices_labels) ) {
        foreach ( $choices_labels as $key => $choices_label ) {
          if ( strpos($choices_label, "***quantity***") ) {
          $choices_label = str_replace("***quantity***", " ", $choices_label);
        }
        if ( strpos($choices_label, "***property***") ) {
          $choices_label = str_replace("***property***", " ", $choices_label);
        }
        ?>
        <tr>
          <td class="label<?php echo $k; ?>"><?php echo str_replace("***br***", '<br>', $choices_label) ?></td>
          <td>
            <div class="fm-bordered-progress bordered" style="width:<?php echo ($choices_count[$key] / ($all - $unanswered)) * 100; ?>%; background-color:<?php echo $colors[$key % 2]; ?>;"></div>
            <div <?php echo($choices_count[$key] / ($all - $unanswered) != 1 ? 'class="fm-bordered-progress bordered' . $k . '"' : "") ?> style="width:<?php echo 100 - ($choices_count[$key] / ($all - $unanswered)) * 100; ?>%;"><span class="fm-bordered-progress-percent"><?php echo round( ($choices_count[$key] / ($all - $unanswered)) * 100 , 2 ); ?> %</span></div>
          </td>
          <td>
            <div>
              <div class="fm-bordered-count-arrow"style="border-right:8px solid <?php echo $choices_colors[$key % 2]; ?>;"></div>
              <div class="fm-bordered-count" style="background-color:<?php echo $choices_colors[$key % 2]; ?>;">
                <?php echo $choices_count[$key] ?>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="3">
          </td>
        </tr>
          <?php
          $k = 1 - $k;
        }
      }
      if ( $unanswered ) {
        ?>
        <tr>
          <td colspan="2" style="text-align:right; color: #000;"><?php _e('Unanswered', WDFMInstance(self::PLUGIN)->prefix); ?></td>
          <td><strong style="margin-left:10px;"><?php echo $unanswered; ?></strong></td>
        </tr>
        <?php
      }
      ?>
      <tr>
        <td colspan="2" style="text-align:right; color: #000;"><strong><?php _e('Total', WDFMInstance(self::PLUGIN)->prefix); ?></strong></td>
        <td><strong style="margin-left:10px;"><?php echo $all; ?></strong></td>
      </tr>
    </table>
    <?php
	return ob_get_clean();
    die();
  }

  /**
   * Edit page.
   *
   * @param array $params
   */
  public function edit( $params = array() ) {
    // TODO: Change this function to standard.
    echo $this->topbar();

    $form = $params['form'];
    $current_id = $params['current_id'];
    $rows = $params['rows'];
    $labels_id = $params['labels_id'];
    $labels_name = $params['labels_name'];
    $labels_type = $params['labels_type'];
    $username = $params['username'];
    $useremail = $params['useremail'];
    ?>
    <div class="wrap">
      <?php
      // Generate message container by message id or directly by message.
      $message_id = WDW_FM_Library(self::PLUGIN)->get('message', 0, 'intval');
      $message = WDW_FM_Library(self::PLUGIN)->get('msg');
      echo WDW_FM_Library(self::PLUGIN)->message_id($message_id, $message);
      ?>
      <form action="admin.php?page=submissions<?php echo WDFMInstance(self::PLUGIN)->menu_postfix; ?>" method="post" id="adminForm" name="adminForm"  class="form_maker_submissions_edit wd-form">
        <div class="wd-page-title wd-header">
          <h1 class="wp-heading-inline"><?php _e('Edit Submission', WDFMInstance(self::PLUGIN)->prefix); ?></h1>
        </div>
        <div class="wd-buttons">
          <button class="button button-primary button-large" onclick="fm_set_input_value('task', 'save'); fm_set_input_value('current_id', <?php echo $current_id; ?>); fm_form_submit(event, 'adminForm');"><?php _e('Save', WDFMInstance(self::PLUGIN)->prefix); ?></button>
        </div>
        <div class="wd-table">
          <div class="wd-table-col wd-table-col-50 wd-table-col-left">
            <div class="wd-box-section">
              <div class="wd-box-content non-editable">
                <div class="wd-group">
                  <label class="wd-label" for="id"><?php _e('Form', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <span><?php echo $form->title; ?></span>
                </div>
                <div class="wd-group">
                  <label class="wd-label" for="id"><?php _e('ID', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <span><?php echo $rows[0]->group_id; ?></span>
                </div>
                <div class="wd-group">
                  <label class="wd-label" for="id"><?php _e('Date', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <span><?php echo get_date_from_gmt( $rows[0]->date ); ?></span>
                </div>
                <div class="wd-group">
                  <label class="wd-label" for="id"><?php _e('IP', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <span><?php echo $rows[0]->ip; ?></span>
                </div>
                <div class="wd-group">
                  <label class="wd-label" for="id"><?php _e('Submitter\'s Username', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <span><?php echo $username; ?></span>
                </div>
                <div class="wd-group">
                  <label class="wd-label" for="id"><?php _e('Submitter\'s Email Address', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                  <span><?php echo $useremail; ?></span>
                </div>
              </div>
            </div>
          </div>
          <div class="wd-table-col wd-table-col-50 wd-table-col-right">
            <div class="wd-box-section">
              <div class="wd-box-content">
                <?php
                  foreach ( $labels_id as $key => $label_id ) {
                    if ( $this->model->check_type_for_edit_function($labels_type[$key]) ) {
                      $element_value = $this->model->check_for_submited_label($rows, $label_id);
                      if ( $element_value == 'continue' ) {
                        continue;
                      }
                      switch ( $labels_type[$key] ) {
                        case 'type_checkbox': {
                          $choices = explode('***br***', $element_value);
                          $choices = array_slice($choices, 0, count($choices) - 1);
                          ?>
                          <div class="wd-group">
                            <label class="wd-label"><?php echo $labels_name[$key]; ?></label>
                            <?php foreach ( $choices as $choice_key => $choice ) { ?>
                              <input type="text" name="submission_<?php echo $label_id . '_' . $choice_key; ?>"
                                     id="submission_<?php echo $label_id . '_' . $choice_key; ?>"
                                     value="<?php echo $choice; ?>" size="80"/>
                            <?php } ?>
                          </div>
                          <?php
                          break;
                        }
                        case 'type_paypal_payment_status': {
                          ?>
                          <div class="wd-group">
                            <label class="wd-label"><?php echo $labels_name[$key]; ?></label>
                            <select name="submission_0" id="submission_0">
                              <option value=""></option>
                              <option
                                value="Canceled"><?php echo _e('Canceled', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="Cleared"><?php echo _e('Cleared', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="Cleared by payment review"><?php echo _e('Cleared by payment review', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="Completed"><?php echo _e('Completed', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="Denied"><?php echo _e('Denied', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="Failed"><?php echo _e('Failed', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option value="Held"><?php echo _e('Held', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="In progress"><?php echo _e('In progress', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="On hold"><?php echo _e('On hold', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option value="Paid"><?php echo _e('Paid', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="Partially refunded"><?php echo _e('Partially refunded', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="Pending verification"><?php echo _e('Pending verification', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="Placed"><?php echo _e('Placed', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="Processing"><?php echo _e('Processing', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="Refunded"><?php echo _e('Refunded', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="Refused"><?php echo _e('Refused', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="Removed"><?php echo _e('Removed', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="Returned"><?php echo _e('Returned', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="Reversed"><?php echo _e('Reversed', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="Temporary hold"><?php echo _e('Temporary hold', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                              <option
                                value="Unclaimed"><?php echo _e('Unclaimed', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                            </select>
                            <script>
                              var element = document.getElementById("submission_0");
                              element.value = "<?php echo $element_value; ?>";
                            </script>
                          </div>
                          <?php
                          break;
                        }
                        case 'type_star_rating': {
                          $star_rating_array = $this->model->images_for_star_rating($element_value, $label_id);
                          $edit_stars = $star_rating_array[0];
                          $stars_value = $star_rating_array[1];
                          ?>
                          <div class="wd-group">
                            <label class="wd-label"><?php echo $labels_name[$key]; ?></label>
                            <?php echo $edit_stars; ?>
                            <input type="hidden" id="<?php echo $label_id; ?>_star_amountform_id_temp"
                                   name="<?php echo $label_id; ?>_star_amountform_id_temp"
                                   value="<?php echo $stars_value[0]; ?>">
                            <input type="hidden" name="<?php echo $label_id; ?>_star_colorform_id_temp"
                                   id="<?php echo $label_id; ?>_star_colorform_id_temp"
                                   value="<?php echo $stars_value[1]; ?>">
                            <input type="hidden" id="<?php echo $label_id; ?>_selected_star_amountform_id_temp"
                                   name="<?php echo $label_id; ?>_selected_star_amountform_id_temp"
                                   value="<?php echo $stars_value[0]; ?>">
                            <input type="hidden" name="submission_<?php echo $label_id; ?>"
                                   id="submission_<?php echo $label_id; ?>" value="<?php echo $element_value; ?>"
                                   size="80"/>
                          </div>
                          <?php
                          break;
                        }
                        case "type_scale_rating": {
                          $scale_rating_array = $this->model->params_for_scale_rating($element_value, $label_id);
                          $scale = $scale_rating_array[0];
                          $scale_radio = $scale_rating_array[1];
                          $checked = $scale_rating_array[2];
                          ?>
                          <div class="wd-group">
                            <label class="wd-label"><?php echo $labels_name[$key]; ?></label>
                            <?php echo $scale; ?>
                            <input type="hidden" id="<?php echo $label_id; ?>_scale_checkedform_id_temp"
                                   name="<?php echo $label_id; ?>_scale_checkedform_id_temp"
                                   value="<?php echo $scale_radio[1]; ?>">
                            <input type="hidden" name="submission_<?php echo $label_id; ?>"
                                   id="submission_<?php echo $label_id; ?>" value="<?php echo $element_value; ?>"
                                   size="80"/>
                          </div>
                          <?php
                          break;
                        }
                        case 'type_range': {
                          $range = $this->model->params_for_type_range($element_value, $label_id);
                          ?>
                          <div class="wd-group type_range">
                            <label class="wd-label"><?php echo $labels_name[$key]; ?></label>
                            <?php echo $range; ?>
                            <input type="hidden" name="submission_<?php echo $label_id; ?>"
                                   id="submission_<?php echo $label_id; ?>" value="<?php echo $element_value; ?>"
                                   size="80"/>
                          </div>
                          <?php
                          break;
                        }
                        case 'type_spinner': {
                          ?>
                          <div class="wd-group">
                            <label class="wd-label"><?php echo $labels_name[$key]; ?></label>
                            <input type="text" name="submission_<?php echo $label_id; ?>"
                                   id="submission_<?php echo $label_id; ?>"
                                   value="<?php echo str_replace("*@@url@@*", '', $element_value); ?>" size="20"/>
                          </div>
                          <?php
                          break;
                        }
                        case 'type_grading': {
                          $type_grading_array = $this->model->params_for_type_grading($element_value, $label_id);
                          $garding = $type_grading_array[0];
                          $garding_value = $type_grading_array[1];
                          $sum = $type_grading_array[2];
                          $items_count = $type_grading_array[3];
                          $element_value1 = $type_grading_array[4];
                          ?>
                          <div class="wd-group type_grading">
                            <label class="wd-label"><?php echo $labels_name[$key]; ?></label>
                            <?php echo $garding; ?>
                            <p style="padding-left: 80px;">
                              <span id="<?php echo $label_id; ?>_grading_sumform_id_temp"><?php echo $sum; ?></span> /
                              <span
                                id="<?php echo $label_id; ?>_grading_totalform_id_temp"><?php echo $garding_value[$items_count]; ?></span>
                              <span id="<?php echo $label_id; ?>_text_elementform_id_temp"></span>
                            </p>
                            <input type="hidden" id="<?php echo $label_id; ?>_element_valueform_id_temp"
                                   name="<?php echo $label_id; ?>_element_valueform_id_temp"
                                   value="<?php echo $element_value1; ?>"/>
                            <input type="hidden" id="<?php echo $label_id; ?>_grading_totalform_id_temp"
                                   name="<?php echo $label_id; ?>_grading_totalform_id_temp"
                                   value="<?php echo $garding_value[$items_count]; ?>"/>
                            <input type="hidden" name="submission_<?php echo $label_id; ?>"
                                   id="submission_<?php echo $label_id; ?>" value="<?php echo $element_value; ?>"
                                   size="80"/>
                          </div>
                          <?php
                          break;
                        }
                        case 'type_matrix': {
                          $type_matrix_array = $this->model->params_for_type_matrix($element_value, $label_id);
                          $matrix = $type_matrix_array[0];
                          $new_filename = $type_matrix_array[1];
                          ?>
                          <div class="wd-group type_matrix">
                            <label class="wd-label"><?php echo $labels_name[$key]; ?></label>
                            <input type="hidden" id="<?php echo $label_id; ?>_matrixform_id_temp"
                                   name="<?php echo $label_id; ?>_matrixform_id_temp" value="<?php echo $new_filename; ?>">
                            <?php echo $matrix; ?>
                            <input type="hidden" name="submission_<?php echo $label_id; ?>"
                                   id="submission_<?php echo $label_id; ?>" value="<?php echo $element_value; ?>"
                                   size="80"/>
                          </div>
                          <?php
                          break;
                        }
                        case 'type_signature': {
                          ?>
                          <div class="wd-group">
                            <label class="wd-label" for="submission_<?php echo $label_id; ?>"><?php echo $labels_name[$key]; ?></label>
                            <img src="<?php echo $element_value; ?>" style="width:200px; border: 1px solid #ddd;" />
                          </div>
                          <?php
                          break;
                        }
                        default: {
                          if ( strpos($element_value, "@@@") !== FALSE ) {
                            $element_value = str_replace("@@@", " ", $element_value);
                          }
                          ?>
                          <div class="wd-group">
                            <label class="wd-label" for="submission_<?php echo $label_id; ?>"><?php echo $labels_name[$key]; ?></label>
                            <textarea name="submission_<?php echo $label_id; ?>" id="submission_<?php echo $label_id; ?>"
                                      value="<?php echo str_replace("*@@url@@*", '', $element_value); ?>"
                                      style="min-height:100px;"><?php echo str_replace("*@@url@@*", '', $element_value); ?></textarea>
                          </div>
                          <?php
                          break;
                        }
                      }
                    }
                  }
                  ?>
              </div>
            </div>
          </div>
        </div>
        <?php wp_nonce_field(WDFMInstance(self::PLUGIN)->nonce, WDFMInstance(self::PLUGIN)->nonce); ?>
        <input type="hidden" name="option" value="com_formmaker" />
        <input type="hidden" id="current_id" name="current_id" value="<?php echo $rows[0]->group_id; ?>" />
        <input type="hidden" name="form_id" value="<?php echo $rows[0]->form_id; ?>" />
        <input type="hidden" name="date" value="<?php echo get_date_from_gmt( $rows[0]->date ); ?>" />
        <input type="hidden" name="ip" value="<?php echo $rows[0]->ip; ?>" />
        <input type="hidden" id="task" name="task" value="" />
        <input type="hidden" value="<?php echo WDFMInstance(self::PLUGIN)->plugin_url; ?>" id="form_plugins_url" />
      </form>
    </div>
    <?php
  }

	/**
	* Print saelect form.
    *
	* @param array $params
	*/
	private function select_form( $params = array() ) {
		if ( !empty($params['forms']) ) {
			$page = $params['page'];
			$page_url = $params['page_url'];
			$order_by = esc_attr($params['order_by']);
			$asc_or_desc = $params['asc_or_desc'];
			?>
			<select name="form_id" id="form_id">
				<option value="0"><?php _e('- Select a form -', WDFMInstance(self::PLUGIN)->prefix); ?></option>
				<?php
					foreach ( $params['forms'] as $form ) {
						$selected = ( $form->id == $params['id'] ) ? 'selected="selected"' : '';
						$show_url = add_query_arg( array( 'page' => $page, 'task' => 'display', 'current_id' => $form->id, 'order_by' => $order_by, 'asc_or_desc' => $asc_or_desc ), admin_url('admin.php'));
						echo '<option value="' . $form->id . '" ' . $selected . ' data-submission-href="' . $show_url . '">' . $form->title . '</option>';
					}
				?>
			</select>
			<script>
				jQuery(function () {
					jQuery( '#form_id' ).on("change", function () {
						if (jQuery(this).val() > 0) {
							var href = jQuery( '#form_id option:selected' ).attr( 'data-submission-href' );
							location.href = href;
						}
					});
				});
			</script>
			<?php
		}
	}

  /**
   * Generate short text.
   *
   * @param string $text
   * @param int $chars_limit
   *
   * @return array.
   */
  public function gen_shorter_text( $text = '', $chars_limit = 0 ) {
    $data = array();
    // Check if length is larger than the character limit.
    if ( strlen($text) > $chars_limit && $text == strip_tags($text) ) {
      // If so, cut the string at the character limit.
      $new_text = mb_substr( $text, 0, $chars_limit,"utf-8" );
      // Trim off white space.
      $new_text = trim($new_text);
      // Add at end of text ...
      $data['status'] = TRUE;
      $data['text'] = $new_text . "...";
    }
    // If not just return the text and status as is.
    else {
      $data['status'] = FALSE;
      $data['text'] = $text;
    }
    return $data;
  }
}