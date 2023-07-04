<?php
function mo_openid_login_shortcodes() {
	?>
	<div class="mo_openid_table_layout">
		<table>
			<tr>
				<td>
					<div id="mo_openid_login_shortcode" style="font-size:13px !important">
						<h4><?php echo esc_attr( mo_sl( 'Use social login Shortcode in the content of required page/post where you want to display Social Login Icons.' ) ); ?></h4>
						<h3><?php echo esc_attr( mo_sl( 'Default Social Login Shortcode' ) ); ?></h3>
						<?php echo esc_attr( mo_sl( 'This will display Social Login Icons with same default settings' ) ); ?><br/>
						<code id='1'>[miniorange_social_login]</code><i style= "width: 11px;height: 9px;padding-left:2px;padding-top:3px" class="far fa-fw fa-lg fa-copy mo_copy mo_copytooltip" onclick="copyToClipboard(this, '#1', '#shortcode_url_copy')"><span id="shortcode_url_copy" class="mo_copytooltiptext">Copy to Clipboard</span></i><hr>

						<h3><?php echo esc_attr( mo_sl( 'For Icons' ) ); ?></h3>
						<?php echo esc_attr( mo_sl( 'You can use  different attribute to customize social login icons. All attributes are optional except view.' ) ); ?><br/><br/>
						<b><?php echo esc_attr( mo_sl( 'Square Shape Example' ) ); ?>:</b> <code id='2'>[miniorange_social_login shape="square" theme="default" space="4" size="35"]</code><i style= "width: 11px;height: 9px;padding-left:2px;padding-top:3px" class="far fa-fw fa-lg fa-copy mo_copy mo_copytooltip" onclick="copyToClipboard(this, '#2', '#shortcode_url1_copy')"><span id="shortcode_url1_copy" class="mo_copytooltiptext">Copy to Clipboard</span></i><br/><br/>
						<a class=" login-button" rel="nofollow" title=" Login with Facebook"><i class="fab fa-facebook login-button square" style="width:35px !important;height: 30px !important;margin-left: 4px !important;font-size: 1.8em;text-align: center;background: #1877F2;color: #FFFFFF;padding-top: 8px;"></i></a>
						<a class=" login-button" rel="nofollow" title=" Login with Google"><i class="fab fa-google login-button square" style="width:35px !important;height: 30px !important;margin-left: 4px !important;font-size: 1.8em;text-align: center;background: #DB4437;color: #FFFFFF;padding-top: 8px;"></i></a>
						<a class=" login-button" rel="nofollow" title=" Login with Vkontakte"><i class="fab fa-vk login-button square" style="width:35px !important;height: 30px !important;margin-left: 4px !important;font-size: 1.8em;text-align: center;background: #466482;color: #FFFFFF;padding-top: 8px;"></i></a>
						<a class=" login-button" rel="nofollow" title=" Login with LinkedIn"><i class="fab fa-linkedin login-button square" style="width:35px !important;height: 30px !important;margin-left: 4px !important;font-size: 1.8em;text-align: center;background: #007bb6;color: #FFFFFF;padding-top: 8px;"></i></a>
						<a class=" login-button" rel="nofollow" title=" Login with instagram"><i class="fab fa-instagram login-button square" style="width:35px !important;height: 30px !important;margin-left: 4px !important;font-size: 1.8em;text-align: center;background: #3f729b;color: #FFFFFF;padding-top: 8px;"></i></a><br/><br/>

						<b><?php echo esc_attr( mo_sl( 'Round Shape Example' ) ); ?>:</b> <code id='3'>[miniorange_social_login shape="round" theme="default" space="4" size="35"]</code><i style= "width: 11px;height: 9px;padding-left:2px;padding-top:3px" class="far fa-fw fa-lg fa-copy mo_copy mo_copytooltip" onclick="copyToClipboard(this, '#3', '#shortcode_url2_copy')"><span id="shortcode_url2_copy" class="mo_copytooltiptext">Copy to Clipboard</span></i><br/><br/>
						<a class=" login-button" rel="nofollow" title=" Login with Facebook"><i class="fab fa-facebook login-button round" style="width:35px !important;height: 28px !important;margin-left: 4px !important;font-size: 1.8em;text-align: center;background: #1877F2;color: #FFFFFF;padding-top: 6px;"></i></a>
						<a class=" login-button" rel="nofollow" title=" Login with Google"><i class="fab fa-google login-button round" style="width:35px !important;height: 28px !important;margin-left: 4px !important;font-size: 1.8em;text-align: center;background: #DB4437;color: #FFFFFF;padding-top: 6px;"></i></a>
						<a class=" login-button" rel="nofollow" title=" Login with Vkontakte"><i class="fab fa-vk login-button round" style="width:35px !important;height: 28px !important;margin-left: 4px !important;font-size: 1.8em;text-align: center;background: #466482;color: #FFFFFF;padding-top: 6px;"></i></a>
						<a class=" login-button" rel="nofollow" title=" Login with LinkedIn"><i class="fab fa-linkedin login-button round" style="width:35px !important;height: 28px !important;margin-left: 4px !important;font-size: 1.8em;text-align: center;background: #007bb6;color: #FFFFFF;padding-top: 6px;"></i></a>
						<a class=" login-button" rel="nofollow" title=" Login with instagram"><i class="fab fa-instagram login-button round" style="width:35px !important;height: 28px !important;margin-left: 4px !important;font-size: 1.8em;text-align: center;background: #3f729b;color: #FFFFFF;padding-top: 6px;"></i></a><br/><br/>
						<hr>
						<h3><?php echo esc_attr( mo_sl( 'For Long-Buttons' ) ); ?></h3>
						<?php echo esc_attr( mo_sl( 'You can use different attribute to customize social login buttons. All attributes are optional' ) ); ?>.<br/><br/>
						<b><?php echo esc_attr( mo_sl( 'Vertical View Example' ) ); ?>:<br/></b><code id='4'>[miniorange_social_login shape="longbuttonwithtext" theme="default" space="8" width="180" height="35" color="000000"]</code><i style= "width: 11px;height: 9px;padding-left:2px;padding-top:3px" class="far fa-fw fa-lg fa-copy mo_copy mo_copytooltip" onclick="copyToClipboard(this, '#4', '#shortcode_url3_copy')"><span id="shortcode_url3_copy" class="mo_copytooltiptext">Copy to Clipboard</span></i><br/><br/>
						<a rel="nofollow" style="margin-left: 8px !important;width: 180px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 3px !important;border-radius: 4px !important;" class="mo_btn mo_btn-mo mo_btn-block mo_btn-social mo_btn-facebook mo_btn-custom-dec login-button"> <svg xmlns="http://www.w3.org/2000/svg" style="padding-top:5px;border-right:none;margin-left: 2%;"><path fill="#fff" d="M22.688 0H1.323C.589 0 0 .589 0 1.322v21.356C0 23.41.59 24 1.323 24h11.505v-9.289H9.693V11.09h3.124V8.422c0-3.1 1.89-4.789 4.658-4.789 1.322 0 2.467.1 2.8.145v3.244h-1.922c-1.5 0-1.801.711-1.801 1.767V11.1h3.59l-.466 3.622h-3.113V24h6.114c.734 0 1.323-.589 1.323-1.322V1.322A1.302 1.302 0 0 0 22.688 0z"></path></svg>Login with Facebook</a>
						<a rel="nofollow" style="margin-left: 8px !important;background: rgb(255,255,255)!important; background:linear-gradient(90deg, rgba(255,255,255,1) 38px, rgb(79, 113, 232) 5%) !important;width: 180px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 3px !important;border-radius: 4px !important;border-color: rgba(79, 113, 232, 1);border-bottom-width: thin;" class="mo_btn mo_btn-mo mo_btn-block mo_btn-social mo_btn-google mo_btn-custom-dec login-button"> <i style="padding-top:6px !important;border-right:none;" class="fa"><svg xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><path fill="#4285F4" fill-rule="nonzero" d="M20.64 12.2045c0-.6381-.0573-1.2518-.1636-1.8409H12v3.4814h4.8436c-.2086 1.125-.8427 2.0782-1.7959 2.7164v2.2581h2.9087c1.7018-1.5668 2.6836-3.874 2.6836-6.615z"></path><path fill="#34A853" fill-rule="nonzero" d="M12 21c2.43 0 4.4673-.806 5.9564-2.1805l-2.9087-2.2581c-.8059.54-1.8368.859-3.0477.859-2.344 0-4.3282-1.5831-5.036-3.7104H3.9574v2.3318C5.4382 18.9832 8.4818 21 12 21z"></path><path fill="#FBBC05" fill-rule="nonzero" d="M6.964 13.71c-.18-.54-.2822-1.1168-.2822-1.71s.1023-1.17.2823-1.71V7.9582H3.9573A8.9965 8.9965 0 0 0 3 12c0 1.4523.3477 2.8268.9573 4.0418L6.964 13.71z"></path><path fill="#EA4335" fill-rule="nonzero" d="M12 6.5795c1.3214 0 2.5077.4541 3.4405 1.346l2.5813-2.5814C16.4632 3.8918 14.426 3 12 3 8.4818 3 5.4382 5.0168 3.9573 7.9582L6.964 10.29C7.6718 8.1627 9.6559 6.5795 12 6.5795z"></path><path d="M3 3h18v18H3z"></path></g></svg></i><span style="color:#ffffff;">Login with Google</span></a>
						<a rel="nofollow" style="margin-left: 8px !important;width: 180px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 3px !important;border-radius: 4px !important;" class="mo_btn mo_btn-mo mo_btn-block mo_btn-social mo_btn-vk mo_btn-custom-dec login-button"> <i style="padding-top:0px !important" class="fab fa-vk"></i>Login with Vkontakte</a><br/>

						<b><?php echo esc_attr( mo_sl( 'Horizontal View Example' ) ); ?>:</b><code id='5'>[miniorange_social_login shape="longbuttonwithtext" view="horizontal" appcnt="3" theme="default" space="35" width="180" height="35" color="000000"]</code><i style= "width: 11px;height: 9px;padding-left:2px;padding-top:3px" class="far fa-fw fa-lg fa-copy mo_copy mo_copytooltip" onclick="copyToClipboard(this, '#5', '#shortcode_url4_copy')"><span id="shortcode_url4_copy" class="mo_copytooltiptext">Copy to Clipboard</span></i><br/><br/>
						<a rel="nofollow" style="margin-left: 35px !important;width: 180px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 30px !important;border-radius: 4px !important;" class="mo_btn mo_btn-mo mo_btn-block-inline mo_btn-social mo_btn-facebook mo_btn-custom-dec login-button"> <svg xmlns="http://www.w3.org/2000/svg" style="padding-top:5px;border-right:none;margin-left: 2%;"><path fill="#fff" d="M22.688 0H1.323C.589 0 0 .589 0 1.322v21.356C0 23.41.59 24 1.323 24h11.505v-9.289H9.693V11.09h3.124V8.422c0-3.1 1.89-4.789 4.658-4.789 1.322 0 2.467.1 2.8.145v3.244h-1.922c-1.5 0-1.801.711-1.801 1.767V11.1h3.59l-.466 3.622h-3.113V24h6.114c.734 0 1.323-.589 1.323-1.322V1.322A1.302 1.302 0 0 0 22.688 0z"></path></svg>Login with Facebook</a>
						<a rel="nofollow" style="margin-left: 35px !important;background: rgb(255,255,255)!important; background:linear-gradient(90deg, rgba(255,255,255,1) 38px, rgb(79, 113, 232) 5%) !important;width: 180px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 30px !important;border-radius: 4px !important;border-color: rgba(79, 113, 232, 1);border-bottom-width: thin;" class="mo_btn mo_btn-mo mo_btn-block-inline mo_btn-social mo_btn-google mo_btn-custom-dec login-button"> <i style="padding-top:6px !important;border-right:none;" class="fa"><svg xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><path fill="#4285F4" fill-rule="nonzero" d="M20.64 12.2045c0-.6381-.0573-1.2518-.1636-1.8409H12v3.4814h4.8436c-.2086 1.125-.8427 2.0782-1.7959 2.7164v2.2581h2.9087c1.7018-1.5668 2.6836-3.874 2.6836-6.615z"></path><path fill="#34A853" fill-rule="nonzero" d="M12 21c2.43 0 4.4673-.806 5.9564-2.1805l-2.9087-2.2581c-.8059.54-1.8368.859-3.0477.859-2.344 0-4.3282-1.5831-5.036-3.7104H3.9574v2.3318C5.4382 18.9832 8.4818 21 12 21z"></path><path fill="#FBBC05" fill-rule="nonzero" d="M6.964 13.71c-.18-.54-.2822-1.1168-.2822-1.71s.1023-1.17.2823-1.71V7.9582H3.9573A8.9965 8.9965 0 0 0 3 12c0 1.4523.3477 2.8268.9573 4.0418L6.964 13.71z"></path><path fill="#EA4335" fill-rule="nonzero" d="M12 6.5795c1.3214 0 2.5077.4541 3.4405 1.346l2.5813-2.5814C16.4632 3.8918 14.426 3 12 3 8.4818 3 5.4382 5.0168 3.9573 7.9582L6.964 10.29C7.6718 8.1627 9.6559 6.5795 12 6.5795z"></path><path d="M3 3h18v18H3z"></path></g></svg></i><span style="color:#ffffff;">Login with Google</span></a>
						<a rel="nofollow" style="margin-left: 35px !important;width: 180px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 30px !important;border-radius: 4px !important;" class="mo_btn mo_btn-mo mo_btn-block-inline mo_btn-social mo_btn-vk mo_btn-custom-dec login-button"> <i style="padding-top:0px !important" class="fab fa-vk"></i>Login with Vkontakte</a><br/>
						<b><?php echo esc_attr( mo_sl( 'Note:' ) ); ?></b> <?php echo esc_attr( mo_sl( 'By default Long-Button view is' ) ); ?> <b><?php echo esc_attr( mo_sl( 'Vertical' ) ); ?></b><hr>

						<h3><?php echo esc_attr( mo_sl( 'For Selected Applications' ) ); ?></h3>
					   <?php echo esc_attr( mo_sl( 'If you want to show selected applications without setting up default settings then use this shortcode. You can use different attribute to customize social login buttons. All attributes are optional' ) ); ?>.<br/><br/>
						<b><?php echo esc_attr( mo_sl( 'Example' ) ); ?>:</b><code id='6'>[miniorange_social_login apps="vk,google,fb" shape="longbuttonwithtext" view="horizontal" theme="default" space="10" width="200" height="35" color="000000"]</code><i style= "width: 11px;height: 9px;padding-left:2px;padding-top:3px" class="far fa-fw fa-lg fa-copy mo_copy mo_copytooltip" onclick="copyToClipboard(this, '#6', '#shortcode_url5_copy')"><span id="shortcode_url5_copy" class="mo_copytooltiptext">Copy to Clipboard</span></i><br/><br/>
						<a rel="nofollow" style="margin-left: 10px !important;width: 200px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 5px !important;border-radius: 4px !important;" class="mo_btn mo_btn-mo mo_btn-block-inline mo_btn-social mo_btn-vk mo_btn-custom-dec login-button"> <i style="padding-top:0px !important" class="fab fa-vk"></i>Login with Vkontakte</a>
						<a rel="nofollow" style="margin-left: 10px !important;background: rgb(255,255,255)!important; background:linear-gradient(90deg, rgba(255,255,255,1) 38px, rgb(79, 113, 232) 5%) !important;width: 200px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 5px !important;border-radius: 4px !important;border-color: rgba(79, 113, 232, 1);border-bottom-width: thin;" class="mo_btn mo_btn-mo mo_btn-block-inline mo_btn-social mo_btn-google mo_btn-custom-dec login-button"> <i style="padding-top:6px !important;border-right:none;" class="fa"><svg xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><path fill="#4285F4" fill-rule="nonzero" d="M20.64 12.2045c0-.6381-.0573-1.2518-.1636-1.8409H12v3.4814h4.8436c-.2086 1.125-.8427 2.0782-1.7959 2.7164v2.2581h2.9087c1.7018-1.5668 2.6836-3.874 2.6836-6.615z"></path><path fill="#34A853" fill-rule="nonzero" d="M12 21c2.43 0 4.4673-.806 5.9564-2.1805l-2.9087-2.2581c-.8059.54-1.8368.859-3.0477.859-2.344 0-4.3282-1.5831-5.036-3.7104H3.9574v2.3318C5.4382 18.9832 8.4818 21 12 21z"></path><path fill="#FBBC05" fill-rule="nonzero" d="M6.964 13.71c-.18-.54-.2822-1.1168-.2822-1.71s.1023-1.17.2823-1.71V7.9582H3.9573A8.9965 8.9965 0 0 0 3 12c0 1.4523.3477 2.8268.9573 4.0418L6.964 13.71z"></path><path fill="#EA4335" fill-rule="nonzero" d="M12 6.5795c1.3214 0 2.5077.4541 3.4405 1.346l2.5813-2.5814C16.4632 3.8918 14.426 3 12 3 8.4818 3 5.4382 5.0168 3.9573 7.9582L6.964 10.29C7.6718 8.1627 9.6559 6.5795 12 6.5795z"></path><path d="M3 3h18v18H3z"></path></g></svg></i><span style="color:#ffffff;">Login with Google</span></a>
						<a rel="nofollow" style="margin-left: 10px !important;width: 200px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 5px !important;border-radius: 4px !important;" class="mo_btn mo_btn-mo mo_btn-block-inline mo_btn-social mo_btn-facebook mo_btn-custom-dec login-button""> <svg xmlns="http://www.w3.org/2000/svg" style="padding-top:5px;border-right:none;margin-left: 2%;"><path fill="#fff" d="M22.688 0H1.323C.589 0 0 .589 0 1.322v21.356C0 23.41.59 24 1.323 24h11.505v-9.289H9.693V11.09h3.124V8.422c0-3.1 1.89-4.789 4.658-4.789 1.322 0 2.467.1 2.8.145v3.244h-1.922c-1.5 0-1.801.711-1.801 1.767V11.1h3.59l-.466 3.622h-3.113V24h6.114c.734 0 1.323-.589 1.323-1.322V1.322A1.302 1.302 0 0 0 22.688 0z"></path></svg>Login with Facebook</a>
						<br/><br/>
						<table style="border-collapse: collapse;width:90%">
							<tr>
								<td ><table style="margin-left: 25%; width: 100%" id="mo_shortcode_table">
										<tr>
											<td><b>Name</b></td>
											<td ><b>Value</b></td>
										</tr>
										<tr>
											<td ><b>Facebook</b></td>
											<td >fb</td>
										</tr>
										<tr>
											<td ><b>Google</b></td>
											<td >google</td>
										</tr>
										<tr>
											<td ><b>Vkontakte</b></td>
											<td>vk</td>
										</tr>
										<tr>
											<td><b>Twitter</b></td>
											<td>twitter</td>
										</tr>
										<tr>
											<td><b>Instagram</b></td>
											<td>insta</td>
										</tr>
									</table></td><td style="width:10%"></td>
								<td ><table style="margin-left: 25%;width: 80%" id="mo_shortcode_table" >
										<tr>
											<td ><b>Name</b></td>
											<td ><b>Value</b></td>
										</tr>

										<tr>
											<td><b>LinkedIn</b></td>
											<td>linkin</td>
										</tr>


										<tr>
											<td ><b>Amazon</b></td>
											<td >amazon</td>
										</tr>
										<tr>
											<td ><b>Salesforce</b></td>
											<td >sforce</td>
										</tr>
										<tr>
											<td ><b>Windows Live</b></td>
											<td >wlive</td>
										</tr>
										<tr>
											<td ><b>Yahoo</b></td>
											<td >yahoo</td>
										</tr>
									</table></td><td style="width:10%"></td>

						</table><br/>
						<div class="mo_openid_note_style">
								<b><?php echo esc_attr( mo_sl( 'Note' ) ); ?>:</b>
								<?php echo esc_attr( mo_sl( '1. If you are not registered with miniOrange and ' ) ); ?><b><?php echo esc_attr( mo_sl( 'Custom App' ) ); ?></b><?php echo esc_attr( mo_sl( 'is also not set up then please register with us first or set up' ) ); ?> <b><?php echo esc_attr( mo_sl( 'Custom App' ) ); ?></b> <?php echo esc_attr( mo_sl( 'otherwise the buttons will be' ) ); ?> <b><?php echo esc_attr( mo_sl( 'disabled' ) ); ?></b><br/>
								<?php echo esc_attr( mo_sl( '2. If Facebook is selected then please set up ' ) ); ?><b><?php echo esc_attr( mo_sl( 'Custom App' ) ); ?></b> <?php echo esc_attr( mo_sl( 'first otherwise the buttons will be ' ) ); ?><b><?php echo esc_attr( mo_sl( 'disabled' ) ); ?></b>
								<br/>
						</div><br>




						<body>

						<table style="margin-left: 8%" id="mo_shortcode_table">
							<tr>
								<th colspan="2" style="align-content: center"><?php echo esc_attr( mo_sl( 'Available values for attributes' ) ); ?></th>
							</tr>

							<tr>
								<td><b><?php echo esc_attr( mo_sl( 'view' ) ); ?></b></td>
								<td><?php echo esc_attr( mo_sl( 'horizontal, vertical' ) ); ?></td>

							</tr>
							<tr>
								<td><b><?php echo esc_attr( mo_sl( 'shape' ) ); ?></b></td>
								<td><?php echo esc_attr( mo_sl( 'round, roundededges, square, longbuttonwithtext' ) ); ?></td>

							</tr>
							<tr>
								<td><b><?php echo esc_attr( mo_sl( 'theme' ) ); ?></b></td>
								<td><?php echo esc_attr( mo_sl( 'default, custombackground' ) ); ?></td>

							</tr>
							<tr>
								<td><b><?php echo esc_attr( mo_sl( 'size' ) ); ?></b></td>
								<td><?php echo esc_attr( mo_sl( 'Any value between 20 to 100' ) ); ?></td>

							</tr>
							<tr>
								<td><b><?php echo esc_attr( mo_sl( 'space' ) ); ?></b></td>
								<td><?php echo esc_attr( mo_sl( 'Any value between 0 to 100' ) ); ?></td>

							</tr>
							<tr>
								<td><b><?php echo esc_attr( mo_sl( 'width' ) ); ?></b></td>
								<td><?php echo esc_attr( mo_sl( 'Any value between 200 to 1000' ) ); ?></td>

							</tr>
							<tr>
								<td><b><?php echo esc_attr( mo_sl( 'height' ) ); ?></b></td>
								<td><?php echo esc_attr( mo_sl( 'Any value between 35 to 50' ) ); ?></td>

							</tr>
							<tr>
								<td><b><?php echo esc_attr( mo_sl( 'color' ) ); ?></b></td>
								<td><?php echo esc_attr( mo_sl( 'Enter color code for example' ) ); ?>, FFFFFF</td>

							</tr>
							<tr>
								<td><b><?php echo esc_attr( mo_sl( 'heading' ) ); ?></b></td>
								<td><?php echo esc_attr( mo_sl( 'Enter custom heading' ) ); ?></td>

							</tr>
							<tr>
								<td><b><?php echo esc_attr( mo_sl( 'appcnt' ) ); ?></b></td>
								<td><?php echo esc_attr( mo_sl( 'Any value for number of icons in one row' ) ); ?></td>

							</tr>
						</table>

						</body>
					</div>
					<br/><hr><br/>
					<h3><?php echo esc_attr( mo_sl( 'Shortcode in php file' ) ); ?></h3>
					<?php echo esc_attr( mo_sl( 'You can use shortcode in PHP file as following: ' ) ); ?>
					<code>&lt;&#63;<?php echo esc_attr( apply_shortcodes( 'SHORTCODE' ) ); ?> /&#63;&gt;</code><br/><br/>
					<?php echo esc_attr( mo_sl( 'Replace SHORTCODE in above code with the required shortcode like <code>[miniorange_social_login theme="default"]</code>, so the final code looks like following :' ) ); ?><br/><br/>
					<code id='7'>&lt;&#63;php echo apply_shortcodes('[miniorange_social_login theme="default"]') &#63;&gt;</code><i style= "width: 11px;height: 9px;padding-left:2px;padding-top:3px" class="far fa-fw fa-lg fa-copy mo_copy mo_copytooltip" onclick="copyToClipboard(this, '#7', '#shortcode_url33_copy')"><span id="shortcode_url33_copy" class="mo_copytooltiptext">Copy to Clipboard</span></i><br/><br/>
					<br/><hr><br/>
					<h3><?php echo esc_attr( mo_sl( 'Shortcode for logout' ) ); ?></h3>
					<?php echo esc_attr( mo_sl( 'You can use the following shortcode for Logout: ' ) ); ?>
					<code id='8'>[miniorange_social_login_logout]</code><i style= "width: 11px;height: 9px;padding-left:2px;padding-top:3px" class="far fa-fw fa-lg fa-copy mo_copy mo_copytooltip" onclick="copyToClipboard(this, '#8', '#shortcode_url34_copy')"><span id="shortcode_url34_copy" class="mo_copytooltiptext">Copy to Clipboard</span></i><br/><br/>
				</td>
			</tr>
		</table>
	</div>
	<script>
		//to set heading name
		function copyToClipboard(copyButton, element, copyelement) {
			var temp = jQuery("<input>");
			jQuery("body").append(temp);
			temp.val(jQuery(element).text()).select();
			document.execCommand("copy");
			temp.remove();
			jQuery(copyelement).text("Copied");
			jQuery(copyButton).mouseout(function(){
				jQuery(copyelement).text("Copy to Clipboard");
			});
		}
	</script>
	<?php
}
