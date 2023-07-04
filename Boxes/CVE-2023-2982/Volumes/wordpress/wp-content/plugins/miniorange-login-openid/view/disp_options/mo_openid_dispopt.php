<?php
function mo_openid_disp_opt() {     ?>
	<form id="display" name="display" method="post" action="">
		<input type="hidden" name="option" value="mo_openid_enable_display" />
		<input type="hidden" name="mo_openid_enable_display_nonce" value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-enable-display-nonce' ) ); ?>"/>
		<div class="mo_openid_table_layout" id="mo_openid_disp_opt_tour"><br/>
			<div style="width:40%; background:white; float:left; border: 1px transparent;">
				<b style="font-size: 17px"><?php echo esc_attr( mo_sl( 'Select the options where you want to display the social login icons' ) ); ?></b><br><br>
				<label class="mo_openid_checkbox_container"><?php echo esc_attr( mo_sl( 'Default Login Form [wp-admin]' ) ); ?>
					<input  type="checkbox" id="default_login_enable" name="mo_openid_default_login_enable" value="1" <?php checked( get_option( 'mo_openid_default_login_enable' ) == 1 ); ?> /><br>
					<span class="mo_openid_checkbox_checkmark"></span>
				</label>

				<label class="mo_openid_checkbox_container"><?php echo esc_attr( mo_sl( 'Default Registration Form' ) ); ?>

					<input type="checkbox" id="default_register_enable" name="mo_openid_default_register_enable" value="1" <?php checked( get_option( 'mo_openid_default_register_enable' ) == 1 ); ?> /><br>
					<span class="mo_openid_checkbox_checkmark"></span>
				</label>

				<label class="mo_openid_checkbox_container"><?php echo esc_attr( mo_sl( 'Comment Form' ) ); ?>
					<input type="checkbox" id="default_comment_enable" name="mo_openid_default_comment_enable" value="1" <?php checked( get_option( 'mo_openid_default_comment_enable' ) == 1 ); ?> /><br>
					<span class="mo_openid_checkbox_checkmark"></span>
				</label>
				<label class="mo_openid_note_style" style="cursor: auto"><?php echo esc_attr( mo_sl( "Don't find your login page in above options use" ) ); ?> <code id='1'>[miniorange_social_login]</code><i style= "width: 11px;height: 9px;padding-left:2px;padding-top:3px" class="far fa-fw fa-lg fa-copy mo_copy mo_copytooltip" onclick="copyToClipboard(this, '#1', '#shortcode_url_copy')"><span id="shortcode_url_copy" class="mo_copytooltiptext">Copy to Clipboard</span></i><?php echo esc_attr( mo_sl( 'to display social icons or' ) ); ?> <a style="cursor: pointer" onclick="mo_openid_support_form('')"><?php echo esc_attr( mo_sl( 'Contact Us' ) ); ?></a></label>
				<br/><br/>
				<b style="font-size:17px;"><?php echo esc_attr( mo_sl( 'Ultimate Member display options' ) ); ?> </b><a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a><br><br>

				<label class="mo_openid_checkbox_container_disable">
					<input disabled type="checkbox" id="ultimate_before_login_form" /><?php echo esc_attr( mo_sl( 'Before Ultimate Member Login Form Fields' ) ); ?>
					<span class="mo_openid_checkbox_checkmark"></span>
				</label>
				<label class="mo_openid_checkbox_container_disable">
					<input disabled type="checkbox" id="ultimate_after_login_form" /><?php echo esc_attr( mo_sl( 'After Ultimate Member Login Form Fields' ) ); ?>
					<span class="mo_openid_checkbox_checkmark"></span>
				</label>
				<label class="mo_openid_checkbox_container_disable">
					<input disabled type="checkbox" id="ultimate_center_form" /><?php echo esc_attr( mo_sl( "After 'Register button' of Ultimate Member Form" ) ); ?>
					<span class="mo_openid_checkbox_checkmark"></span>
				</label>
				<label class="mo_openid_checkbox_container_disable">
					<input disabled type="checkbox" id="ultimate_register_form_start" /><?php echo esc_attr( mo_sl( 'Before Ultimate Member Registration Form Fields' ) ); ?>
					<span class="mo_openid_checkbox_checkmark"></span>
				</label>
				<label class="mo_openid_checkbox_container_disable">
					<input disabled type="checkbox" id="ultimate_register_form_end" /><?php echo esc_attr( mo_sl( 'After Ultimate Member Registration Form Fields' ) ); ?>
					<span class="mo_openid_checkbox_checkmark"></span>
				</label>
				<br/><br/>
				<b style="font-size:17px;"><?php echo esc_attr( mo_sl( 'MemberPress display options' ) ); ?> </b><a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a><br><br>

				<label class="mo_openid_checkbox_container_disable">
					<input disabled type="checkbox"/><?php echo esc_attr( mo_sl( 'After MemberPress Login Form' ) ); ?>
					<span class="mo_openid_checkbox_checkmark"></span>
				</label>
				<label class="mo_openid_checkbox_container_disable">
					<input disabled type="checkbox"/><?php echo esc_attr( mo_sl( 'After MemberPress Account Form' ) ); ?>
					<span class="mo_openid_checkbox_checkmark"></span>
				</label>
				<label class="mo_openid_checkbox_container_disable">
					<input disabled type="checkbox"/><?php echo esc_attr( mo_sl( 'After MemberPress Checkout Form' ) ); ?>
					<span class="mo_openid_checkbox_checkmark"></span>
				</label>
			</div>
			<div style="width:50%; background:white; float:right; border: 1px transparent;">
				<b style="font-size:17px;"><?php echo esc_attr( mo_sl( 'Woocommerce display options' ) ); ?></b><br><br><br>

				<label class="mo_openid_checkbox_container">
					<input type="checkbox" id="woocommerce_before_login_form" name="mo_openid_woocommerce_before_login_form" value="1" <?php checked( get_option( 'mo_openid_woocommerce_before_login_form' ) == 1 ); ?> /><?php echo esc_attr( mo_sl( 'Before WooCommerce Login Form' ) ); ?>
					<span class="mo_openid_checkbox_checkmark"></span>
				</label>
				<label class="mo_openid_checkbox_container">
					<input type="checkbox" id="woocommerce_center_login_form" name="mo_openid_woocommerce_center_login_form" value="1" <?php checked( get_option( 'mo_openid_woocommerce_center_login_form' ) == 1 ); ?> /><?php echo esc_attr( mo_sl( "Before 'Remember Me' of WooCommerce Login Form" ) ); ?>
					<span class="mo_openid_checkbox_checkmark"></span>
				</label>
				<label class="mo_openid_checkbox_container_disable"><?php echo esc_attr( mo_sl( 'After WooCommerce Login Form' ) ); ?><a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a>
					<input type="checkbox"  /><br>
					<span class="mo_openid_checkbox_checkmark_disable"></span>
				</label>

				<label class="mo_openid_checkbox_container_disable"><?php echo esc_attr( mo_sl( 'Before WooCommerce Registration Form' ) ); ?><a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a>
					<input type="checkbox"  /><br>
					<span class="mo_openid_checkbox_checkmark_disable"></span>
				</label>
				<label class="mo_openid_checkbox_container_disable"><?php echo esc_attr( mo_sl( "Before 'Register button' of WooCommerce Registration Form" ) ); ?><a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a>
					<input type="checkbox"  /><br>
					<span class="mo_openid_checkbox_checkmark_disable"></span>
				</label>

				<label class="mo_openid_checkbox_container_disable"><?php echo esc_attr( mo_sl( 'After WooCommerce Registration Form' ) ); ?><a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a>
					<input type="checkbox"  /><br>
					<span class="mo_openid_checkbox_checkmark_disable"></span>
				</label>

				<label class="mo_openid_checkbox_container_disable"><?php echo esc_attr( mo_sl( 'Before WooCommerce Checkout Form' ) ); ?><a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a>
					<input type="checkbox"  /><br>
					<span class="mo_openid_checkbox_checkmark_disable"></span>
				</label>

				<label class="mo_openid_checkbox_container_disable"><?php echo esc_attr( mo_sl( 'After WooCommerce Checkout Form' ) ); ?><a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a>
					<input type="checkbox"  /><br>
					<span class="mo_openid_checkbox_checkmark_disable"></span>
				</label>
				<br/>
				<b style="font-size: 17px;"><?php echo esc_attr( mo_sl( 'BuddyPress / BuddyBoss display options' ) ); ?> <a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a></b><br><br>

				<label class="mo_openid_checkbox_container_disable"><?php echo esc_attr( mo_sl( 'Before BuddyPress / BuddyBoss Registration Form' ) ); ?>
					<input type="checkbox"  /><br>
					<span class="mo_openid_checkbox_checkmark_disable"></span>
				</label>

				<label class="mo_openid_checkbox_container_disable"><?php echo esc_attr( mo_sl( 'Before BuddyPress Account Details' ) ); ?>
					<input type="checkbox"  /><br>
					<span class="mo_openid_checkbox_checkmark_disable"></span>
				</label>

				<label class="mo_openid_checkbox_container_disable"><?php echo esc_attr( mo_sl( 'After BuddyPress / BuddyBoss Registration Form' ) ); ?>
					<input type="checkbox"  /><br><br>
					<span class="mo_openid_checkbox_checkmark_disable"></span>
				</label>
				<br/>
				<b style="font-size: 17px;"><?php echo esc_attr( mo_sl( 'Paid Memberships Pro display options' ) ); ?> <a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a></b><br><br>

				<label class="mo_openid_checkbox_container_disable"><?php echo esc_attr( mo_sl( 'Before Paid Memberships Pro Checkout Page Submit Button' ) ); ?>
					<input type="checkbox"  /><br>
					<span class="mo_openid_checkbox_checkmark_disable"></span>
				</label>

				<label class="mo_openid_checkbox_container_disable"><?php echo esc_attr( mo_sl( 'After Paid Memberships Pro Checkout Page Level Cost' ) ); ?>
					<input type="checkbox"  /><br>
					<span class="mo_openid_checkbox_checkmark_disable"></span>
				</label>

				<label class="mo_openid_checkbox_container_disable"><?php echo esc_attr( mo_sl( 'After Paid Memberships Pro Checkout Page Username' ) ); ?>
					<input type="checkbox"  /><br><br>
					<span class="mo_openid_checkbox_checkmark_disable"></span>
				</label>

			</div>
			<div style="height:available;display: inline; width:100%; background:white; float:right; border: 1px transparent; padding-bottom: 10px;" >
				<label class="mo_openid_checkbox_container"><?php echo esc_attr( mo_sl( 'Display miniOrange logo with social login icons on selected form' ) ); ?>
					<input type="checkbox" id="moopenid_logo_check" name="moopenid_logo_check" value="1"  <?php checked( get_option( 'moopenid_logo_check' ) == 1 ); ?> />
					<span class="mo_openid_checkbox_checkmark"></span>
				</label>

			<br/><b style="padding: 10px"><input type="submit" name="submit" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?>" style="width:150px;background-color:#0867b2;color:white;box-shadow:none;text-shadow: none;"  class="button button-primary button-large" /></b>

			   <br><br> <label style="cursor: auto" class="mo_openid_note_style"> <a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo esc_attr( mo_sl( 'These features are available in premium version only. To know more about the premium plugin ' ) ); ?><a href="https://plugins.miniorange.com/social-login-social-sharing "><?php echo esc_attr( mo_sl( 'click here' ) ); ?></a>.</label>
				<h3  id="mo_openid_show_add_login_icons" onclick="show_license_options1(this.id)"><a id="openid_login_shortcode_title"  aria-expanded="false" ><span class="dashicons dashicons-arrow-down " ></span><?php echo esc_attr( mo_sl( 'Add Login Icons' ) ); ?></a></h3>
				<div id="openid_login_shortcode" style="font-size:13px !important">
					<ol>
						<p><?php echo esc_attr( mo_sl( 'You can add login icons in the following areas from' ) ); ?> <strong><?php echo esc_attr( mo_sl( 'Display Options' ) ); ?></strong>.<?php echo esc_attr( mo_sl( 'For other areas(widget areas), use Login widget' ) ); ?>.</p>
						<ol>
							<li><?php echo esc_attr( mo_sl( 'Default Login Form: This option places login icons below the default login form on wp-login' ) ); ?>.</li>
							<li><?php echo esc_attr( mo_sl( 'Default Registration Form: This option places login icons below the default registration form' ) ); ?>.</li>

							<li><?php echo esc_attr( mo_sl( 'Comment Form: This option places login icons above the comment section of all your posts' ) ); ?>.</li>
						</ol>
					</ol>
				</div>

				<h3 id="mo_openid_show_add_login_icons1" onclick="show_add_login_icons1(this.id)"><a id="openid_sharing_shortcode_title"  ><span class="dashicons dashicons-arrow-down " ></span><?php echo esc_attr( mo_sl( 'Add Login Icons as Widget' ) ); ?></a></h3>
				<div  id="openid_sharing_shortcode" style="font-size:13px !important">
					<ol>
						<li>
						<?php
						echo esc_attr(
							mo_sl(
								'Go to Widgets. Among the available widgets you
                            will find miniOrange Social Login Widget, drag it to the widget area where
                            you want it to appear'
							)
						);
						?>
							.</li>
						<li><?php echo esc_attr( mo_sl( 'Now logout and go to your site. You will see Social Login icon for which you enabled login.' ) ); ?></li>
						<li><?php echo esc_attr( mo_sl( 'Click that app icon and login with your existing app account to WordPress' ) ); ?>.</li>
					</ol>
				</div>

				<h3 id="mo_openid_show_add_login_icons2" onclick="show_add_login_icons2(this.id)"><a   id="openid_comments_shortcode_title"  ><span class="dashicons dashicons-arrow-down" ></span><?php echo esc_attr( mo_sl( 'Using Shortcode' ) ); ?></a></h3>
				<div  id="openid_comments_shortcode" style="font-size:13px !important">
					<ol>
						<p><?php echo esc_attr( mo_sl( "You can use this shortcode <code id='2'>[miniorange_social_login]</code><i style= \"width: 11px;height: 9px;padding-left:2px;padding-top:3px\" class=\"far fa-fw fa-lg fa-copy mo_copy mo_copytooltip\" onclick=\"copyToClipboard(this, '#2', '#shortcode_url2_copy')\"><span id=\"shortcode_url2_copy\" class=\"mo_copytooltiptext\">Copy to Clipboard</span></i> to display social icons on any login page, post, popup and PHP pages." ) ); ?></p>
						<p><?php echo esc_attr( mo_sl( '* Detailed information about how to use shortcode is given in <a href=' . site_url() . '/wp-admin/admin.php?page=mo_openid_general_settings&tab=shortcodes>Shortcode</a> tab' ) ); ?></p>
					</ol>
				</div>
			</div>
		</div>
	</form>
	<script>
		//to set heading name
		function show_license_options1(click_id){
			var span = jQuery('#' + click_id).find('span').attr('class');
			if (span.includes('dashicons-arrow-right')){
				jQuery('#mo_openid_show_add_login_icons').find('span').removeClass( "dashicons-arrow-right" );
				jQuery('#mo_openid_show_add_login_icons').find('span').addClass( "dashicons-arrow-down" );
			}
			else if(span.includes('dashicons-arrow-down')) {
				jQuery('#mo_openid_show_add_login_icons').find('span').removeClass( "dashicons-arrow-down" );
				jQuery('#mo_openid_show_add_login_icons').find('span').addClass( "dashicons-arrow-right" );
			}
			jQuery("#mo_openid_licence1").slideToggle(400);
		}

		function show_add_login_icons1(click_id){
			var span = jQuery('#' + click_id).find('span').attr('class');
			if (span.includes('dashicons-arrow-right')){
				jQuery('#mo_openid_show_add_login_icons1').find('span').removeClass( "dashicons-arrow-right" );
				jQuery('#mo_openid_show_add_login_icons1').find('span').addClass( "dashicons-arrow-down" );
			}
			else if(span.includes('dashicons-arrow-down')) {
				jQuery('#mo_openid_show_add_login_icons1').find('span').removeClass( "dashicons-arrow-down" );
				jQuery('#mo_openid_show_add_login_icons1').find('span').addClass( "dashicons-arrow-right" );
			}
		}

		function show_add_login_icons2(click_id){
			var span = jQuery('#' + click_id).find('span').attr('class');
			if (span.includes('dashicons-arrow-right')){

				jQuery('#mo_openid_show_add_login_icons2').find('span').removeClass( "dashicons-arrow-right" );
				jQuery('#mo_openid_show_add_login_icons2').find('span').addClass( "dashicons-arrow-down" );
			}
			else if(span.includes('dashicons-arrow-down')) {
				jQuery('#mo_openid_show_add_login_icons2').find('span').removeClass( "dashicons-arrow-down" );
				jQuery('#mo_openid_show_add_login_icons2').find('span').addClass( "dashicons-arrow-right" );
			}
		}
	</script>
	<?php
}
