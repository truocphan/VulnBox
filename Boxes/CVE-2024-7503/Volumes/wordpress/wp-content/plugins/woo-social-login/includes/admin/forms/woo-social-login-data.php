<?php
/**
 * Social Login Data
 * 
 * Handles to show social login data
 * on the page
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */

	// Define global variable
	global $woo_slg_model, $woo_slg_options;
	
	//social model class
	$model = $woo_slg_model;
?>

<!-- beginning of the Statistics area -->
<div class="wrap">
	
	<?php woo_slg_header_menu(); ?>
	<!-- wpweb logo -->
	<div class="sub-header">
	<div class="logo-header-wrap">
		<img src="<?php echo esc_url(WOO_SLG_IMG_URL) . '/wpweb-logo.svg'; ?>" class="woo-slg-wpweb-logo" alt="<?php esc_html_e( 'WP Web Logo', 'wooslg' );?>" />
		
		<div class="woo-slg-settings-title"><?php esc_html_e( 'Social Login - Statistics', 'wooslg' ); ?></div>
	</div>
	<div class="woo-slg-top-error-wrap"><h2></h2></div>
		<?php
		//save order of social networks
		if( isset( $_POST['woo-slg-settings-social-submit'] ) && $_POST['woo-slg-settings-social-submit'] == esc_html__('Save Changes','wooslg') ) {
			
			$woo_social_order = isset( $_POST['social_order'] ) ? $model->woo_slg_escape_slashes_deep($_POST['social_order']) : array();			
			
			update_option( 'woo_social_order', $woo_social_order );		

			//Update global variable when update settings
			$woo_slg_options = woo_slg_global_settings();		
			
			echo '<div id="message" class="updated fade below-h2">
						<p><strong>'.esc_html__( 'Changes Saved.','wooslg').'</strong></p>
				</div>';
		} ?>
	</div>
	<div class="statistics-main">
		<div class="box-block">
			<form action="" method="POST">
				<div class="order-change flex-wrap">
					<div class="left-side">
						<h3><?php esc_html_e( 'Drag to Change Order', 'wooslg' );?></h3>
						<p><?php esc_html_e('Drag and drop the below providers to control their display order.', 'wooslg'); ?></p>
					</div>
					<?php
					//do action to add social table after
					do_action( 'woo_slg_social_data_table_after' );
					
					echo apply_filters ( 
						'woo_slg_social_submit_button', 
						'<input type="submit" id="woo-slg-settings-social-submit" name="woo-slg-settings-social-submit" class="woo-slg-social-submit button-primary" value="'.esc_html__('Save Changes','wooslg').'" />'
					); ?>
				</div>
				<div class="widefat_table">
					<table class="wp-list-table woo-slg-sortable widefat">
						<thead>
							<tr>
								<th></th>
								<th width="1%" class="woo-slg-social-none"><?php esc_html_e('Change Order', 'wooslg'); ?></th>
								<?php
									//do action to add header before
									do_action( 'woo_slg_social_table_header_change_order_before' );
								?>
								<th class="col-network column-primary"><?php esc_html_e( 'Network', 'wooslg');?></th>
								<?php
									//do action to add social table header network after
									do_action( 'woo_slg_social_table_header_network_before' );
								?>
								<th class="col-register-count"><?php esc_html_e( 'Register Count', 'wooslg');?></th>
								<?php
									//do action to add social table header after
									do_action( 'woo_slg_social_table_header_register_count_before' );
								?>
								<th class="col-status"><?php esc_html_e( 'Status', 'wooslg');?></th>
								<?php
									//do action to add social table header status after
									do_action( 'woo_slg_social_table_header_status_before' );
								?>
								<th class="col-setting"><?php esc_html_e( 'Action', 'wooslg');?></th>
								<?php
									//do action to add social table header settings after
									do_action( 'woo_slg_social_table_header_settings_after' );
								?>
							</tr>
						</thead>
						<tbody>
							<?php
								//do action to add social table content before
								do_action( 'woo_slg_social_table_content_before' );
								
								//get all social networks
								$allnetworks = woo_slg_get_sorted_social_network();
								
								//register user count
								$regusers = array();
								
								foreach ( $allnetworks as $key => $value ) {

									$countargs = array( 
														'getcount' =>	'1',
														'network'	=>	$key 
													);
									$regusers[$key]['count'] = $model->woo_slg_social_get_users( $countargs );
									$regusers[$key]['label'] = $value;

									$is_network_activated = get_option('woo_slg_enable_'.esc_attr($key) );

									$settings_url_key = $key;

									$redirect_uri = add_query_arg(	
										array(
												'page' 		=> 'woo-social-settings',
												'tab'		=> $settings_url_key
										), esc_html( admin_url( 'admin.php' ) )
									);

									$drag_class = 'can-drag';
									$email_style = '';
									
									if( $value == 'Email'){
										$drag_class = '';
										$email_style = ' align="center"';
									}
							?>
								<tr class="<?php print $drag_class;?>">
									<?php
										//do action to add social table data before
										do_action( 'woo_slg_social_table_data_before', $key, $value );
									?>
									<td class="woo-slg-table-col"><img class='woo-slg-drag-drop-menu' src="<?php echo esc_url(WOO_SLG_IMG_URL).'/backend/menu.png'; ?>" title="<?php esc_html_e('Drag & Drop', 'wooslg'); ?>"></td>
									<td width="1%" class="woo-slg-social-none">
										<input type="hidden" name="social_order[]" value="<?php echo $key;?>" />
									</td>
									<?php
										//do action to add social icon after
										do_action( 'woo_slg_social_table_data_icon_after', $key, $value );
									?>
									<td class="woo-slg-netowrk-<?php echo $key; ?> woo-slg-table-col column-primary" data-colname="<?php esc_html_e( 'Network', 'wooslg');?>"><img src="<?php echo esc_url(WOO_SLG_IMG_URL).'/backend/images/'.esc_attr($key).'.svg';?>" alt="<?php echo $value;?>" /> <span><?php echo $value;?></span>
										<a href="<?php echo esc_url($redirect_uri); ?>" class="button woo-slg-show-on-small"><?php esc_html_e('Settings', 'wooslg'); ?></a>
										
										<button type="button" class="toggle-row"><span class="screen-reader-text"><?php esc_html_e( 'Show more details', 'wooslg');?></span></button>
									</td>
									<?php
										//do action to add social table data network
										do_action( 'woo_slg_social_table_data_network_after', $key, $value );
									?>
									<td class="woo-slg-table-col reg-count-label" data-colname="<?php esc_html_e( 'Register Count', 'wooslg');?>"><?php echo $regusers[$key]['count'];?></td>
									<?php
										//do action to add social table data reg count after
										do_action( 'woo_slg_social_table_data_reg_count_after', $key, $value );
									?>
									<td class="woo-slg-table-col" data-colname="<?php esc_html_e( 'Status', 'wooslg');?>">
										<?php
											// Show tick if network is activated else show -
											if(!empty($is_network_activated) && $is_network_activated == 'yes') {
												echo "<img class='woo-slg-network-activated' src='" . esc_url(WOO_SLG_IMG_URL).'/backend/check-mark.svg' . "' alt='" . esc_attr($value) . "' />";
											} else {
												esc_html_e('-', 'wooslg');
											}
										?>
									</td>
									
									<?php
									//do action to add social table header status after
									do_action( 'woo_slg_social_table_data_status_after' ); ?>

									<td class="woo-slg-table-col-settings woo-slg-hide-on-small" <?php print $email_style;?>><a href="<?php echo esc_url($redirect_uri); ?>" class="button"><?php esc_html_e('Settings', 'wooslg'); ?></a></td>
									
									<?php
									//do action to add social table data reg count after
									do_action( 'woo_slg_social_table_data_reg_settings_after', $key, $value ); ?>
								</tr>
							<?php	
							}
								
						//do action to add social table content after
						do_action( 'woo_slg_social_table_content_after' ); ?>

						</tbody>					
					</table>	
				</div>			
			</form> 
		</div>
		<div class="box-block"> 
			<div class="graph-title"><h3><?php esc_html_e( 'Social Networks Registeration Pie Graph', 'wooslg' );?></h3> </div>
			<?php
			$colors = array(
				'facebook'		=>	'A200C2',
				'twitter'		=>	'46c0FB',
				'googleplus'	=>	'0083A8',
				'linkedin'		=>	'4E6CF7',
				'yahoo'			=>	'4863AE',
				'foursquare'	=>	'44A8E0',
				'vk'			=>	'4A63A3',
				'email'			=>  '55be8a',
				'line'			=>	'00b900',
				'apple'			=>	'332636',
			);

			//applying filter for chart color
			$colors = apply_filters( 'woo_slg_social_chart_colors', $colors );

			foreach( $regusers as $key => $val ){
				if( $val['count']== 0 ){
					unset( $regusers[$key] );
					unset( $colors[$key] );
				}
			}

				$datarows = array();

				if( !empty($regusers) ){
				foreach( $regusers as $key => $val ){
					$count = $val['count'];
					$datarows[] = array( $val['label'],$count);
				}
			}

			wp_localize_script('woo-slg-admin-chart-data', 'WOOSlgChart', array(
				'datarows' => $datarows,				
			)); ?>
	
			<div id="woo_slg_social_chart_element" class="woo-slg-social-chart-container"></div>
		</div>
	</div>
	
<!--.woo-slg-social-chart-container-->
	
</div><!--wrap-->