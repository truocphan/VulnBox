<?php
function mo_openid_customize_icons() {
	$default_color = array(
		'facebook'      => '#1877F2',
		'google'        => '#DB4437',
		'vkontakte'     => '#466482',
		'twitter'       => '#2795e9',
		'digg'          => '#000000',
		'yahoo'         => '#430297',
		'yandex'        => '#2795e9',
		'linkedin'      => '#007bb6',
		'pocket'        => '#ee4056',
		'print'         => '#ee4056',
		'whatsapp'      => '#25D366',
		'mail'          => '#787878',
		'amazon'        => '#ff9900',
		'paypal'        => '#0d127a',
		'stumble'       => '#f74425',
		'salesforce'    => '#1ab7ea',
		'apple'         => '#000000',
		'steam'         => '#000000',
		'wordpress'     => '#587ea3',
		'pinterest'     => '#cb2027',
		'spotify'       => '#19bf61',
		'tumblr'        => '#2c4762',
		'twitch'        => '#720e9e',
		'github'        => '#000000',
		'dribbble'      => '#ee66aa',
		'flickr'        => '#ff0084',
		'stackexchange' => '#0000ff',
		'snapchat'      => '#fffc00',
		'reddit'        => '#ff4301',
		'odnoklassniki' => '#f97400',
		'foursquare'    => '#f94877',
		'wechat'        => '#00c300',
		'vimeo'         => '#1ab7ea',
		'line'          => '#00c300',
		'hubspot'       => '#fa7820',
		'discord'       => '#7289da',
		'meetup'        => '#e51937',
		'stackexchange' => '#0000FF',
		'wiebo'         => '#df2029',
		'kakao'         => '#ffe812',
		'livejournal'   => '#3c1361',
		'naver'         => '#3EAF0E',
		'teamsnap'      => '#ff9a1a',
	);

	?>
<!--    <link rel="stylesheet" type="text/css">-->
	<form id="customize_text" name="customize_text" method="post" action="">
	<input type="hidden" name="option" value="mo_openid_enable_customize_text" />
	<input type="hidden" name="mo_openid_enable_customize_text_nonce"
		   value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-enable-customize-text-nonce' ) ); ?>"/>

	<div class="mo_openid_table_layout">
		<table style="width: 100%;">
			<tr>
				<td>

		<div style="width: 100%;">
			<table style="width: 100%">
				<tr>
					<td>
						<h3><?php echo esc_attr( mo_sl( 'Customize Sharing Icons' ) ); ?></h3>
						<p style="font-size: 17px"><?php echo esc_attr( mo_sl( 'Customize shape, size and background for sharing icons' ) ); ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<table style="width=100%">
							<tr >
								<td class="mo_openid_fix_fontsize_semiheading" style="width: 30%"><?php echo esc_attr( mo_sl( 'Shape' ) ); ?></td>
								<td class="mo_openid_fix_fontsize_semiheading" style="width: 30%"><?php echo esc_attr( mo_sl( 'Theme' ) ); ?></td>
								<td class="mo_openid_fix_fontsize_semiheading" style="width: 25%"><?php echo esc_attr( mo_sl( 'Space between Icons' ) ); ?></td>
								<td class="mo_openid_fix_fontsize_semiheading" style="width: 30%"><?php echo esc_attr( mo_sl( 'Size of Icons' ) ); ?></td>
							</tr>

							<tr>
								<td style=" width=25%">
<!--                                    <select id="shape" onchange="shape_change('shape'); checkShareButton();moSharePreview(setSizeOfIcons() ,document.getElementById('shape').value,setShareCustomTheme(),'2B41FF',document.getElementById('mo_share_icon_space').value,document.getElementById('mo_share_icon_custom_boundary').value)" name="mo_openid_share_shape">-->


									<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Round' ) ); ?>
										<input type="radio" id="mo_openid_share_theme_circle"  name="mo_openid_share_theme" value="circle" onclick="tempHorShape = 'circle';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, 'circle', setCustomTheme(), document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value, document.getElementById('mo_sharing_icon_custom_font').value)" <?php checked( get_option( 'mo_openid_share_theme' ) == 'circle' ); ?> />
									<span class="mo-openid-radio-checkmark"></span></label>

									<br>

									<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Rounded Edges' ) ); ?>
										<input type="radio"   name="mo_openid_share_theme"  value="oval" onclick="tempHorShape = 'oval';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, 'oval', setCustomTheme(), document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value)"  <?php checked( get_option( 'mo_openid_share_theme' ) == 'oval' ); ?> /></br>
										<span class="mo-openid-radio-checkmark"></span></label>

									<br>
									<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Square' ) ); ?>
										<input type="radio"   name="mo_openid_share_theme" value="square" onclick="tempHorShape = 'square';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(), setCustomTheme(), document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value)" <?php checked( get_option( 'mo_openid_share_theme' ) == 'square' ); ?> />
										<span class="mo-openid-radio-checkmark"></span></label>
								<br>
<!--                                    </select>-->
								</td>
								<td style=" width=30%">


									<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Default' ) ); ?>
										<input type="radio" id="mo_openid_default_background_radio"  name="mo_openid_share_custom_theme" value="default" onclick="tempHorTheme = 'default';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(), 'default', document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value, document.getElementById('mo_sharing_icon_custom_font').value)"
											<?php checked( get_option( 'mo_openid_share_custom_theme' ) == 'default' ); ?> /><br>
										<span class="mo-openid-radio-checkmark"></span></label>





								 <br>


									<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Custom background' ) ); ?>*
										<input type="radio" id="mo_openid_custom_background_radio"  name="mo_openid_share_custom_theme" value="custom" onclick="tempHorTheme = 'custom';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(),'custom',document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value)"
											<?php checked( get_option( 'mo_openid_share_custom_theme' ) == 'custom' ); ?> /><br>
										<span class="mo-openid-radio-checkmark"></span></label>


									<input id="mo_sharing_icon_custom_color" name="mo_sharing_icon_custom_color" class="color" value="<?php echo esc_attr( get_option( 'mo_sharing_icon_custom_color' ) ); ?>" onchange="moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(),setCustomTheme(),document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value,document.getElementById('mo_sharing_icon_custom_font').value)" ><br>
								 <br>


									<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'No background' ) ); ?>*
										<input type="radio" id="mo_openid_no_background_radio"  name="mo_openid_share_custom_theme" value="customFont" onclick="tempHorTheme = 'customFont';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(),'customFont',document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value,document.getElementById('mo_sharing_icon_custom_font').value)"  <?php checked( get_option( 'mo_openid_share_custom_theme' ) == 'customFont' ); ?> /><br>
										<span class="mo-openid-radio-checkmark"></span></label>


									<input id="mo_sharing_icon_custom_font" name="mo_sharing_icon_custom_font"  class="color" value="<?php echo esc_attr( get_option( 'mo_sharing_icon_custom_font' ) ); ?>" onchange="moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(),setCustomTheme(),document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value,document.getElementById('mo_sharing_icon_custom_font').value,document.getElementById('mo_sharing_icon_custom_font').value)" /><br>

								</td>
								<td style=" width=25%">
								<!-- Size between icons buttons-->
									<input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 16%;margin-bottom: 3px" onkeyup="moSharingSpaceValidate(this)" id="mo_sharing_icon_space" name="mo_sharing_icon_space" type="text" value="<?php echo esc_attr( get_option( 'mo_sharing_icon_space' ) ); ?>" />
									<input id="mo_sharing_space_plus" type="button" value="+" onmouseup="moSharingPreview('horizontal',document.getElementById('mo_sharing_icon_size').value ,setTheme(),setCustomTheme(),document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value)" />
									<input id="mo_sharing_space_minus" type="button" value="-" onmouseup="moSharingPreview('horizontal',document.getElementById('mo_sharing_icon_size').value ,setTheme(),setCustomTheme(),document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value)" />
								</td>

								<td style=" width=20%" >
									<input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 16%;margin-bottom: 3px"id="mo_sharing_icon_size" onkeyup="moSharingSizeValidate(this)" name="mo_sharing_icon_custom_size" type="text" value="<?php echo esc_attr( get_option( 'mo_sharing_icon_custom_size' ) ); ?>" >

									<input id="mo_sharing_size_plus" type="button" value="+" onmouseup="tempHorSize = document.getElementById('mo_sharing_icon_size').value;moSharingPreview('horizontal',document.getElementById('mo_sharing_icon_size').value , setTheme(), setCustomTheme(), document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value,document.getElementById('mo_sharing_icon_custom_font').value)" >

									<input id="mo_sharing_size_minus" type="button" value="-" onmouseup="tempHorSize = document.getElementById('mo_sharing_icon_size').value;moSharingPreview('horizontal',document.getElementById('mo_sharing_icon_size').value ,setTheme(), setCustomTheme(), document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value, document.getElementById('mo_sharing_icon_custom_font').value)" >
								</br>
								</td>
							</tr>

							<tr>
							   <td colspan="4" class="mo_openid_note_style" style="width: 328%; padding: 10px;"><b>*<?php echo esc_attr( mo_sl( 'NOTE' ) ); ?>:</b> <?php echo esc_attr( mo_sl( 'Custom background: This will change the background color of share icons' ) ); ?>.<br> <?php echo esc_attr( mo_sl( 'No background:This will remove the background color of share icons' ) ); ?>.</td>
							</tr>

							<tr>
								<td colspan="4">
									<br><b><?php echo esc_attr( mo_sl( 'Preview' ) ); ?> : </b><br/>
									<div>
										<?php
										$share_app = get_option( 'share_app' );
										$share_app = explode( '#', $share_app );
										foreach ( $share_app as $active_app ) {

											if ( get_option( 'mo_openid_' . $active_app . '_share_enable' ) ) {

												$icon = $active_app;
												if ( $active_app == 'vkontakte' ) {
													$icon = 'vk';
												}
												if ( $active_app == 'mail' ) {
													$icon = 'envelope';
												}
												if ( $active_app == 'pocket' ) {
													$icon = 'get-pocket';
												}
												if ( $active_app == 'print' ) {
													$icon = 'file-alt';
												}
												if ( $active_app == 'stumble' ) {
													$icon = 'stumbleupon';
												}
												if ( $active_app == 'pocket' || $active_app == 'stumble' ) {

													?>
													<i class="mo_sharing_icon_preview fab fa-<?php echo esc_attr( $icon ); ?>" style="display: none;text-align:center;background: <?php echo esc_attr( $default_color[ $active_app ] ); ?>;color: #ffffff"
													   id="mo_sharing_icon_preview_<?php echo esc_attr( $active_app ); ?>"></i>
														<i class="mo_custom_sharing_icon_preview fab fa-<?php echo esc_attr( $icon ); ?>"
														   id="mo_custom_sharing_icon_preview_<?php echo esc_attr( $active_app ); ?>"
														   style="color:#ffffff;text-align:center;margin-top:5px;"></i>
														<i class="mo_custom_sharing_icon_font_preview fab fa-<?php echo esc_attr( $icon ); ?>"
														   id="mo_custom_sharing_icon_font_preview_<?php echo esc_attr( $active_app ); ?>"
														   style="text-align:center;margin-top:5px;"></i>

														<?php
												} elseif ( $active_app == 'print' || $active_app == 'mail' ) {

													?>
													<i class="mo_sharing_icon_preview far fa-<?php echo esc_attr( $icon ); ?>" style="display: none;text-align:center;background: <?php echo esc_attr( $default_color[ $active_app ] ); ?>;color: #ffffff"
													   id="mo_sharing_icon_preview_<?php echo esc_attr( $active_app ); ?>"></i>
													<i class="mo_custom_sharing_icon_preview far fa-<?php echo esc_attr( $icon ); ?>"
													   id="mo_custom_sharing_icon_preview_<?php echo esc_attr( $active_app ); ?>"
													   style="color:#ffffff;text-align:center;margin-top:5px;"></i>
													<i class="mo_custom_sharing_icon_font_preview far fa-<?php echo esc_attr( $icon ); ?>"
													   id="mo_custom_sharing_icon_font_preview_<?php echo esc_attr( $active_app ); ?>"
													   style="text-align:center;margin-top:5px;"></i>


													<?php
												} elseif ( $active_app == 'vkontakte' ) {
													?>
														<i class="mo_sharing_icon_preview fab fa-vk" style="display: none;text-align:center;background: <?php echo esc_attr( $default_color[ $active_app ] ); ?>;color: #ffffff;"
														   id="mo_sharing_icon_preview_<?php echo esc_attr( $active_app ); ?>"></i>

														<i class="mo_custom_sharing_icon_preview fab fa-<?php echo esc_attr( $icon ); ?>"
														   id="mo_custom_sharing_icon_preview_<?php echo esc_attr( $icon ); ?>"
														   style="color:#ffffff;text-align:center;margin-top:5px;"></i>


														<i class="mo_custom_sharing_icon_font_preview fab fa-<?php echo esc_attr( $icon ); ?>"
														   id="mo_custom_sharing_icon_font_preview_<?php echo esc_attr( $icon ); ?>"
														   style="text-align:center;margin-top:5px;"></i>

														<?php
												} else {

													?>
														<i class="mo_sharing_icon_preview fab fa-<?php echo esc_attr( $icon ); ?>" style="display: none;text-align:center;background: <?php echo esc_attr( $default_color[ $icon ] ); ?>;color: #ffffff"
														   id="mo_sharing_icon_preview_<?php echo esc_attr( $active_app ); ?>"></i>
														<i class="mo_custom_sharing_icon_preview fab fa-<?php echo esc_attr( $active_app ); ?>"
														   id="mo_custom_sharing_icon_preview_<?php echo esc_attr( $active_app ); ?>"
														   style="color:#ffffff;text-align:center;margin-top:5px;"></i>

														<i class="mo_custom_sharing_icon_font_preview fab fa-<?php echo esc_attr( $active_app ); ?>"
														   id="mo_custom_sharing_icon_font_preview_<?php echo esc_attr( $active_app ); ?>"
														   style="text-align:center;margin-top:5px;"></i>

														<?php
												}
											}
										}

										?>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>


			<br>
			<h3><?php echo esc_attr( mo_sl( 'Customize Text For Social Share Icons' ) ); ?></h3>
			<style>
				.share_text{
					font-size: 15px;

				}
			</style>
			<hr>
					<b class="share_text"><?php echo esc_attr( mo_sl( 'Select color for share heading' ) ); ?>:</b>
			<input id="mo_openid_table_textbox" style="width:135px;margin-left: 17.5%" name="mo_openid_share_widget_customize_text_color" class="color" value="<?php echo esc_attr( get_option( 'mo_openid_share_widget_customize_text_color' ) ); ?>" >
			<br><br>
					<b class="share_text" ><?php echo esc_attr( mo_sl( 'Enter text to show above share widget' ) ); ?>:</b>
			<input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 40%;margin-left: 10%" type="text" name="mo_openid_share_widget_customize_text" value="<?php echo esc_attr( get_option( 'mo_openid_share_widget_customize_text' ) ); ?>"  />
			<br><br>
					<b class="share_text" ><?php echo esc_attr( mo_sl( 'Enter your twitter Username (without @)' ) ); ?>:</b>
			<input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 40%;margin-left: 8.5%" type="text" name="mo_openid_share_twitter_username" value="<?php echo esc_attr( get_option( 'mo_openid_share_twitter_username' ) ); ?>"  />
			<br><br>

					<b class="share_text"><?php echo esc_attr( mo_sl( 'Enter the Email subject (email share)' ) ); ?>:</b>
			<input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 40%;margin-left: 12%" type="text" name="mo_openid_share_email_subject" value="<?php echo esc_attr( get_option( 'mo_openid_share_email_subject' ) ); ?>"  />
			<br><br>

					<b class="share_text"><?php echo esc_attr( mo_sl( 'Enter the Email body (add ##url## to place the URL)' ) ); ?>:</b>
			<input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 40%;margin-left: 2%" type="text" name="mo_openid_share_email_body" value="<?php echo esc_attr( get_option( 'mo_openid_share_email_body' ) ); ?>"  />


			<br><br>

					<b><input type="submit" name="submit" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?>" style="width:150px;background-color:#0867b2;color:white;box-shadow:none;text-shadow: none;"  class="button button-primary button-large" /></b>


			</td>
			</tr>

		</div>

			</table>
		</div>
	</form>
	<script>
		var tempHorSize = '<?php echo esc_attr( get_option( 'mo_sharing_icon_custom_size' ) ); ?>';
		var tempHorShape = '<?php echo esc_attr( get_option( 'mo_openid_share_theme' ) ); ?>';
		var tempHorTheme = '<?php echo esc_attr( get_option( 'mo_openid_share_custom_theme' ) ); ?>';
		var tempbackColor = '<?php echo esc_attr( get_option( 'mo_sharing_icon_custom_color' ) ); ?>';
		var tempHorSpace = '<?php echo esc_attr( get_option( 'mo_sharing_icon_space' ) ); ?>';
		var tempHorFontColor = '<?php echo esc_attr( get_option( 'mo_sharing_icon_custom_font' ) ); ?>';
		function moSharingIncrement(e,t,r,a,i){
			var h,s,c=!1,_=a;s=function(){
				"add"==t&&r.value<60?r.value++:"subtract"==t&&r.value>10&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}

		moSharingIncrement(document.getElementById('mo_sharing_size_plus'), "add", document.getElementById('mo_sharing_icon_size'), 300, 0.7);
		moSharingIncrement(document.getElementById('mo_sharing_size_minus'), "subtract", document.getElementById('mo_sharing_icon_size'), 300, 0.7);

		function moSharingSpaceIncrement(e,t,r,a,i){
			var h,s,c=!1,_=a;s=function(){
				"add"==t&&r.value<50?r.value++:"subtract"==t&&r.value>0&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}
		moSharingSpaceIncrement(document.getElementById('mo_sharing_space_plus'), "add", document.getElementById('mo_sharing_icon_space'), 300, 0.7);
		moSharingSpaceIncrement(document.getElementById('mo_sharing_space_minus'), "subtract", document.getElementById('mo_sharing_icon_space'), 300, 0.7);


		function setTheme(){return jQuery('input[name=mo_openid_share_theme]:checked', '#customize_text').val();}
		function setCustomTheme(){
			return jQuery('input[name=mo_openid_share_custom_theme]:checked', '#customize_text').val();}
	</script>

	<script type="text/javascript">

		var selectedApps = [];



		function moSharingPreview(e,t,r,w,h,n,x){


			if("default"==w){
				var a="mo_sharing_icon_preview";
				jQuery('.mo_sharing_icon_preview').show();
				jQuery('.mo_custom_sharing_icon_preview').hide();
				jQuery('.mo_custom_sharing_icon_font_preview').hide();
				jQuery("."+a).css({height:t-8,width:t});
				jQuery("."+a).css("font-size",(t-16)+"px");
				jQuery("."+a).css("margin-left",(n-4)+"px");
				jQuery("."+a).css("padding-top","8px");


				if(r=="circle"){
					jQuery("."+a).css("borderRadius","999px");
				}else if(r=="oval"){
					jQuery("."+a).css("borderRadius","5px");
				}else if(r=="square"){
					jQuery("."+a).css("borderRadius","0px");
				}

			}
			else if(w == "custom"){
				var a="mo_custom_sharing_icon_preview";
				jQuery('.mo_sharing_icon_preview').hide();
				jQuery('.mo_custom_sharing_icon_font_preview').hide();
				jQuery('.mo_custom_sharing_icon_preview').show();
				jQuery("."+a).css("background","#"+h);
				jQuery("."+a).css("padding-top","8px");
				jQuery("."+a).css({height:t-8,width:t});
				jQuery("."+a).css("font-size",(t-16)+"px");

				if(r=="circle"){
					jQuery("."+a).css("borderRadius","999px");
				}else if(r=="oval"){
					jQuery("."+a).css("borderRadius","5px");
				}else if(r=="square"){
					jQuery("."+a).css("borderRadius","0px");
				}

				jQuery("."+a).css("margin-left",(n-4)+"px");
			}

			else if("customFont"==w){

				var a="mo_custom_sharing_icon_font_preview";
				jQuery('.mo_sharing_icon_preview').hide();
				jQuery('.mo_custom_sharing_icon_preview').hide();
				jQuery('.mo_custom_sharing_icon_font_preview').show();
				jQuery("."+a).css("font-size",t+"px");
				jQuery('.mo_custom_sharing_icon_font_preview').css("color","#"+x);
				jQuery("."+a).css("margin-left",(n-4)+"px");

				if(r=="circle"){
					jQuery("."+a).css("borderRadius","999px");

				}else if(r=="oval"){
					jQuery("."+a).css("borderRadius","5px");
				}else if(r=="square"){
					jQuery("."+a).css("borderRadius","0px");
				}

			}
			//addSelectedApps();



		}
		moSharingPreview('horizontal',tempHorSize,tempHorShape,tempHorTheme,tempbackColor,tempHorSpace,tempHorFontColor);

		function addSelectedApps(e,app_name) {
			var radio = "<?php echo esc_attr( get_option( 'mo_openid_share_custom_theme' ) ); ?>";
			var flag = jQuery("#sel_apps .app_enable:checked").length + 1;
			var value_of_app_default= ("#mo_sharing_icon_preview_" + app_name);
			var value_of_app_custom=("#mo_custom_sharing_icon_preview_"+app_name);
			var value_of_app_no=("#mo_custom_sharing_icon_font_preview_"+app_name);


			if (e.checked==true) {
				flag++;
				if (radio== 'default') {

					jQuery(value_of_app_default).show();
				}
				if (radio== 'custom') {

					jQuery(value_of_app_custom).show();
				}
				if (radio=='customFont'){

					jQuery(value_of_app_no).show();
					// jQuery(value_of_app_default).hide();
					// jQuery(value_of_app_custom).hide();


				}
			} else if (e.checked==false) {

				flag--;
				jQuery(value_of_app_default).hide();
				jQuery(value_of_app_custom).hide();
				jQuery(value_of_app_no).hide();
			}

			if (flag) {
				jQuery("#no_apps_text").hide();
			} else {
				jQuery("#no_apps_text").show();
			}

		}


	</script>
	<script>
		//to set heading name
		jQuery('#mo_openid_page_heading').text('<?php echo esc_attr( mo_sl( 'Customize text for Social Share Icons' ) ); ?>');
	</script>

	<?php
}
?>
