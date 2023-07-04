<?php

function mo_openid_share_cnt() {
	?>
	<form id="share_cnt" name="share_cnt" method="post" action="">
		<input type="hidden" name="option" value="mo_openid_share_cnt" />
		<input type="hidden" name="mo_openid_share_cnt_nonce"
			   value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-share-cnt-nonce' ) ); ?>"/>



		<div class="mo_openid_table_layout"><table style="width:100%">

				<tr>
					<td>

						<p class="mo_openid_note_style" style="font-size: 15px;"><?php echo esc_attr( mo_sl( 'Share counts are supported for <b>Facebook</b> If you are Enabling share counts then it will give you the exact counts of share that is done by that particular social media.' ) ); ?><br><br><?php echo esc_attr( mo_sl( ' <b>Vkontakte</b>, <b>Stumble Upon</b>, <b>Buffer</b>, <b>Pinterest</b> and <b>Reddit</b> are supported in Paid Version' ) ); ?>.<a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a>.</p>

						<br>
						<label class="mo_openid_checkbox_container"> <b style="font-size: 14px;"><?php echo esc_attr( mo_sl( 'Enable share counts' ) ); ?></b>
							<input type="checkbox" id="share_count" name="mo_openid_share_count" value="1" <?php checked( get_option( 'mo_openid_share_count' ) == 1 ); ?> />
							<span class="mo_openid_checkbox_checkmark"></span>
						</label>
						<br>
						<input type="button" id="delete_count_transient" value="<?php echo esc_attr( mo_sl( 'Delete Count Session' ) ); ?>" name="delete_count_transient" style="width:150px; margin-bottom: 0px" onclick="delete_transit()" class="button button-primary button-large"/>
						<br><br>
					</td>
				</tr>
				<script>
					function delete_transit() {
						var a = "
						<?php
						delete_transient( 'facebook' );
						?>
							 ";
						location.reload();
					}
				</script>
				<tr>
					<td style="width: 100%; ">
						<div class="mo_openid_sharecount " >
							<ul class="mo_openid_share_count_icon">
								<li>
									<i style="height: 42px;width: 50px;font-size: 2.5em;color: #FFFFFF;background: #1877F2;text-align: center;padding-top: 8px" class=" fab fa-facebook mo_sharing_icon_preview" id="mo_sharing_icon_preview_count_facebook" ></i>
									<span style='margin-left : <?php echo esc_attr( get_option( 'mo_sharing_icon_space' ) ); ?>px !important'>12k</span>
								</li>
							</ul>
							<div style="font-size: 14px;">
								<b><?php echo esc_attr( mo_sl( 'Facebook Access Token' ) ); ?></b> <input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 50%" type="text" name="mo_openid_Facebook_share_count_api" value="<?php echo esc_attr( get_option( 'mo_openid_Facebook_share_count_api' ) ); ?>"><br/>
								<p><b><?php echo esc_attr( mo_sl( 'Instructions to configure Facebook Share Counts' ) ); ?>:</b></p>
								<ol>
									<li><?php echo esc_attr( mo_sl( 'Go to' ) ); ?> <a href="https://developers.facebook.com/tools/explorer/" target="_blank">https://developers.facebook.com/tools/explorer/</a>. <?php echo esc_attr( mo_sl( 'Sign in with your Facebook account' ) ); ?>.
									</li>
									<li>
									<?php
									echo esc_attr(
										mo_sl(
											'Use an existing app if you already have one or create a new facebook
                                        app by clicking on '
										)
									);
									?>
										<b><?php echo esc_attr( mo_sl( 'Create App' ) ); ?></b>. <?php echo esc_attr( mo_sl( 'Go to' ) ); ?> <b><?php echo esc_attr( mo_sl( 'My Apps' ) ); ?></b> 
				<?php
										echo esc_attr(
											mo_sl(
												'and
                                        select'
											)
										);
				?>
 <b><?php echo esc_attr( mo_sl( 'Create App' ) ); ?></b>.
				 <?php
										echo esc_attr(
											mo_sl(
												' Enter Display Name i.e App Name and click
                                        on'
											)
										);
					?>
 <b><?php echo esc_attr( mo_sl( 'Create App ID' ) ); ?></b>.
									</li>
									<li><?php echo esc_attr( mo_sl( 'Go to' ) ); ?> <b><?php echo esc_attr( mo_sl( 'Tools' ) ); ?></b><?php echo esc_attr( mo_sl( 'select' ) ); ?> <b><?php echo esc_attr( mo_sl( 'Graph API Explorer' ) ); ?></b> <?php echo esc_attr( mo_sl( 'and click on' ) ); ?> <b>
												   <?php
													echo esc_attr(
														mo_sl(
															'Get
                                            Access Token'
														)
													);
													?>
										</b>.
									</li>
									<li><?php echo esc_attr( mo_sl( 'Now, go to' ) ); ?> <a href="https://developers.facebook.com/tools/accesstoken" target="_blank"><?php echo esc_attr( mo_sl( 'Access Token Tool' ) ); ?></a> <?php echo esc_attr( mo_sl( 'and press' ) ); ?> <b><?php echo esc_attr( mo_sl( 'Debug' ) ); ?></b> <?php echo esc_attr( mo_sl( 'option at right side for the ' ) ); ?><b><?php echo esc_attr( mo_sl( 'User Token' ) ); ?></b></li>
									<li><?php echo esc_attr( mo_sl( 'Now copy the' ) ); ?> <b><?php echo esc_attr( mo_sl( 'Access Token' ) ); ?></b> 
												   <?php
													echo esc_attr(
														mo_sl(
															'and paste it in the above field and
                                        click on'
														)
													);
													?>
										 <b><?php echo esc_attr( mo_sl( 'save' ) ); ?></b>.
									</li>
								</ol>
								<p class="mo_openid_note_style"><span style="color: red">*</span><strong>
								<?php
								echo esc_attr(
									mo_sl(
										'According to the new updates of
                                        Facebook API it will expires after every 60 days. So to avoid any
                                        issues update it again before 60 days'
									)
								);
								?>
																											.</strong></p>
							</div>
						</div>

					</td>
				</tr>
				<tr>
					<td><br/><b><input type="submit" name="submit" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?>" style="width:150px;background-color:#0867b2;color:white;box-shadow:none;text-shadow: none;"  class="button button-primary button-large" />
						</b>
					</td>
				</tr>
			</table>
		</div>
	</form>

	<script>
		function show_disable_options3(click_id){

			var span = jQuery('#' + click_id).find('span').attr('class');
			if (span.includes('dashicons-arrow-up')){
				jQuery('#mo_openid_h3_collapse3').find('span').removeClass( "dashicons-arrow-up" );
				jQuery('#mo_openid_h3_collapse3').find('span').addClass( "dashicons-arrow-down" );
			}
			else if(span.includes('dashicons-arrow-down')) {
				jQuery('#mo_openid_h3_collapse3').find('span').removeClass( "dashicons-arrow-down" );
				jQuery('#mo_openid_h3_collapse3').find('span').addClass( "dashicons-arrow-up" );

			}
			jQuery("#mo_openid_collapse3").slideToggle(400);

		}
	</script>
	<script>
		//to set heading name
		jQuery('#mo_openid_page_heading').text('<?php echo esc_attr( mo_sl( 'Share Counts' ) ); ?>');
	</script>

	<?php
}
?>
