<?php
function mo_openid_social_media_services() {
	?>
 <script>jQuery('#mo_openid_page_heading').text('<?php echo esc_attr( mo_sl( 'Social Media Service' ) ); ?>');
  var temp = jQuery("<a style=\"left: 1%; padding:4px; position: relative; text-decoration: none\" class=\"mo-openid-premium\" href=\"<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>\">PRO</a>");
					jQuery("#mo_openid_page_heading").append(temp);</script>
 <form id="social_media_services" name="social_media_services" method="post" action="">
		<input type="hidden" name="option" value="mo_openid_social_media_services" />
		<input type="hidden" name="mo_openid_social_media_services_nonce"
			   value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-social-media-services-nonce' ) ); ?>"/>
	<div style="height: auto; padding: 20px 20px 20px 20px; "><table style="width:60%">

		<label style="cursor: auto" class="mo_openid_note_style">&nbsp;&nbsp;&nbsp;<?php echo esc_attr( mo_sl( 'Enable this feature to users will get option for like, recommend and pin your website page on facebook and pinterest' ) ); ?>.</label>
		   <h2>Social Sharing Services <a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a></h2><br>
		 <tr>
			<td><input type="checkbox" class="app_enable" id="mo_openid_facebook_like" name="mo_openid_facebook_like" <?php checked( get_option( 'mo_openid_facebook_like' ) == 'on' ); ?> disabled><img src="<?php echo esc_url( PLUGIN_URL ) . 'share_icons/facebook_like.png'; ?>"
			></td>
			<td><input type="checkbox" id="mo_openid_facebook_recommend" name="mo_openid_facebook_recommend" <?php checked( get_option( 'mo_openid_facebook_recommend' ) == 'on' ); ?> disabled><img src="<?php echo esc_url( PLUGIN_URL ) . 'share_icons/facebook_recommend.png'; ?>" ></td>
			<td><input type="checkbox" id="mo_openid_pinterest_pin" name="mo_openid_pinterest_pin" <?php checked( get_option( 'mo_openid_pinterest_pin' ) == 'on' ); ?> disabled><img src="<?php echo esc_url( PLUGIN_URL ) . 'share_icons/pinterest_pin.png'; ?>" ></td>
		</tr></table>
		<p>Copy and paste this <b>[social-share-service]</b> shortcode in page for social sharing feature</p>
	</div>
	<div>
		<table style="width:100%; padding: 20px 20px 20px 20px;">
		 <tr><td>
			<label style="cursor: auto" class="mo_openid_note_style">&nbsp;&nbsp;&nbsp;<?php echo esc_attr( mo_sl( 'Enable this feature to to give user an option to E-amil subcribe on your website.' ) ); ?>.</label>
			<h2>E-mail Subcribe <a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a></h2>
			<input type="email" name="email" id="email" required placeholder="Enter your email address" disabled>
			<input class="mo_btn mo_btn-primary" value="Join Now" type="submit" id="submit" >
			<br><br>E-mail subscribe from <b>[mail-subcribe]</b>
		</td></tr>
		<tr><td><br/><br><b><input type="submit" name="submit" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?>" style="width:150px;background-color:#0867b2;color:white;box-shadow:none;text-shadow: none;"  class="button button-primary button-large" disabled/>
		</b></td></tr>
		</table>
	</div> 
</form>
	<?php
}

