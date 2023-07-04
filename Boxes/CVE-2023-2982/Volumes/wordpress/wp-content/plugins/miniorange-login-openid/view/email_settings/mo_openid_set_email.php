<?php
function mo_openid_email_notification() {
	?>
	<div class="mo_openid_table_layout">
		<br/><div>
			<form>
				<input type="hidden" name="option" value="mo_openid_email_setting" />
				<input type="hidden" name="mo_openid_enable_email_setting_nonce"
					   value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-enable-email-setting-nonce' ) ); ?>"/>
			<div class="mo_openid_highlight">
				<h3 style="margin-left: 1%;line-height: 210%;color: white;" id="mo_openid_send_email_admin_collapse" onclick="show_disable_send_email_admin(this.id)"><?php echo esc_attr( mo_sl( 'Send mail to Admin' ) ); ?><span class="dashicons dashicons-arrow-up toggle-div1" ></span></h3>
				<style>
					.toggle-div1{
						float:inherit;
						font-size:1.5em;
						padding-left:79%;
					}
				</style>
			</div><br/>
			<div id="mo_send_email_admin">
				<b class="mo_openid_note_style"><?php echo esc_attr( mo_sl( '*NOTE: This feature requires SMTP to be setup.' ) ); ?></b><br/>
				<label class="mo_openid_checkbox_container_disable"><b><?php echo esc_attr( mo_sl( 'Enable Email Notification to Admin - on User Registration*' ) ); ?></b>
					<input type="checkbox"  />
					<span class="mo_openid_checkbox_checkmark_disable"></span>
				</label>
				<b><?php echo esc_attr( mo_sl( 'If you want to send Email Notification to multiple admins, enter emails of all admins here:' ) ); ?></b><br><br><?php echo '(If left empty only administrator gets email)'; ?>
				<input type="text" disabled rows="2" placeholder="Emails should be separated by comma" class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 100%;padding: 1%;margin-top: 1%" ></input>
				<br />
				<br><b><?php echo esc_attr( mo_sl( 'Email Subject:' ) ); ?></b>
				<br><input type="text" rows="2" placeholder="Enter your subject line here" class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 100%;padding: 1%;margin-top: 1%" disabled></input>
				<br/><br>
				<?php
				$editor_height = array( 'editor_height' => 200 );
				$content       = get_option( 'mo_openid_registration_email_content' );
				$editor_id     = 'mo_openid_registration_email_content';
				wp_editor( $content, $editor_id, $editor_height );
				?>
			</div><br/>
			<div class="mo_openid_highlight">
				<h3 style="margin-left: 1%;line-height: 210%;color: white;" id="mo_openid_send_email_user_collapse" onclick="show_disable_send_email_user(this.id)"><?php echo esc_attr( mo_sl( 'Send mail to User' ) ); ?><span class="dashicons dashicons-arrow-up toggle-div1" ></span></h3>
				<style>
					.toggle-div1{
						float:inherit;
						font-size:1.5em;
						padding-left:79%;
					}
				</style>
			</div><br/>
			<div  id="mo_send_email_user">

				<b class="mo_openid_note_style"><?php echo esc_attr( mo_sl( '*NOTE: This feature requires SMTP to be setup' ) ); ?>.</b><br/>
				<label class="mo_openid_checkbox_container_disable"><b><?php echo esc_attr( mo_sl( 'Email Notification to User on User Registration' ) ); ?>*</b>
					<input type="checkbox"  />
					<span class="mo_openid_checkbox_checkmark_disable"></span>
				</label>

			  <b><?php echo esc_attr( mo_sl( 'Email Subject:' ) ); ?></b>
				<input type="text" rows="2" placeholder="Enter your subject line here" class="mo_openid_textfield_css" style="padding:1%;border: 1px solid ;border-color: #0867b2;width: 100%;margin-top: 1%" disabled ></input>
				<br/><br>
				<?php
				$editor_height = array( 'editor_height' => 300 );
				$content       = get_option( 'mo_openid_user_register_message' );
				$editor_id     = 'mo_openid_user_register_message';
				wp_editor( $content, $editor_id, $editor_height );
				?>
				<input disabled type="button" name="submit" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?>" style="width:100px;"  class="button button-primary button-large" />
				<br><br>
			</div>
			</form>
		</div>
	</div>
	<script>
		//to set heading name
		var temp = jQuery("<a style=\"left: 1%; padding:4px; position: relative; text-decoration: none\" class=\"mo-openid-premium\" href=\"<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>\">PRO</a>");

		var win_height = jQuery('#mo_openid_menu_height').height();
		//win_height=win_height+18;
		jQuery(".mo_container").css({height:win_height});
		function show_disable_send_email_admin(click_id){
			var span = jQuery('#' + click_id).find('span').attr('class');
			if (span.includes('dashicons-arrow-up')){
				jQuery('#mo_openid_send_email_admin_collapse').find('span').removeClass( "dashicons-arrow-up" );
				jQuery('#mo_openid_send_email_admin_collapse').find('span').addClass( "dashicons-arrow-down" );
			}
			else if(span.includes('dashicons-arrow-down')) {
				jQuery('#mo_openid_send_email_admin_collapse').find('span').removeClass( "dashicons-arrow-down" );
				jQuery('#mo_openid_send_email_admin_collapse').find('span').addClass( "dashicons-arrow-up" );
			}
			jQuery("#mo_send_email_admin").slideToggle(400);
		}

		function show_disable_send_email_user(click_id){
			var span = jQuery('#' + click_id).find('span').attr('class');
			if (span.includes('dashicons-arrow-up')){
				jQuery('#mo_openid_send_email_user_collapse').find('span').removeClass( "dashicons-arrow-up" );
				jQuery('#mo_openid_send_email_user_collapse').find('span').addClass( "dashicons-arrow-down" );
			}
			else if(span.includes('dashicons-arrow-down')) {
				jQuery('#mo_openid_send_email_user_collapse').find('span').removeClass( "dashicons-arrow-down" );
				jQuery('#mo_openid_send_email_user_collapse').find('span').addClass( "dashicons-arrow-up" );
			}
			jQuery("#mo_send_email_user").slideToggle(400);
		}
	</script>
	<?php
}
?>
