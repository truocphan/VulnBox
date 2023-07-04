<?php

function mo_openid_customise_social_icons() {
	?><br/>
	<form id="form-apps" name="form-apps" method="post" action="">
		<input type="hidden" name="option" value="mo_openid_customise_social_icons" />
		<input type="hidden" name="mo_openid_customise_social_icons_nonce" value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-customise-social-icons-nonce' ) ); ?>"/>

		<div style="min-height: 608px" class="mo_openid_table_layout">
			<div style="float: left;width: 55%;">
				<div style="width: 100%;">
					<div style="float:left;width:50%;">
						<label style="font-size: 1.2em;"><b><?php echo esc_attr( mo_sl( 'Shape' ) ); ?></b><br></label>
						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Round' ) ); ?>
							<input type="radio" id="mo_openid_login_shape_round"  name="mo_openid_login_theme" value="circle" onclick="shape_change();checkLoginButton();moLoginPreview(document.getElementById('mo_login_icon_size').value ,'circle',setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value,document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())" <?php checked( get_option( 'mo_openid_login_theme' ) == 'circle' ); ?> /><br>
							<span class="mo-openid-radio-checkmark"></span></label>
						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Rounded Edges' ) ); ?>
							<input type="radio" id="mo_openid_login_shape_rounded_edges" name="mo_openid_login_theme" value="oval" onclick="shape_change();checkLoginButton();moLoginPreview(document.getElementById('mo_login_icon_size').value ,'oval',setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value,document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())"  <?php checked( get_option( 'mo_openid_login_theme' ) == 'oval' ); ?> /><br>
							<span class="mo-openid-radio-checkmark"></span></label>

						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Square' ) ); ?>
							<input type="radio" id="mo_openid_login_shape_square" name="mo_openid_login_theme" value="square" onclick="shape_change();checkLoginButton();moLoginPreview(document.getElementById('mo_login_icon_size').value ,'square',setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value,document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())" <?php checked( get_option( 'mo_openid_login_theme' ) == 'square' ); ?> /><br>
							<span class="mo-openid-radio-checkmark"></span></label>
						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Long Button' ) ); ?>
							<input type="radio" id="mo_openid_login_shape_longbutton" name="mo_openid_login_theme" value="longbutton" onclick="shape_change();checkLoginButton();moLoginPreview(document.getElementById('mo_login_icon_width').value ,'longbutton',setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value,document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())" <?php checked( get_option( 'mo_openid_login_theme' ) == 'longbutton' ); ?> /><br>
							<span class="mo-openid-radio-checkmark"></span></label>
						<hr>
						<label style="font-size: 1.2em;"><b><?php echo esc_attr( mo_sl( 'Effects' ) ); ?></b><br></label>
						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'No Effect' ) ); ?>
							<input type="radio" id="mo_openid_button_theme_effect_no" name="mo_openid_button_theme_effect" value="noeffect" onclick="shape_change();checkLoginButton();moLoginPreview(setSizeOfIcons() ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value,document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,'noeffect')" <?php checked( get_option( 'mo_openid_button_theme_effect' ) == 'noeffect' ); ?>/><br>
							<span class="mo-openid-radio-checkmark"></span></label>
						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Transform Effect' ) ); ?>
							<input type="radio" id="mo_openid_button_theme_effect_transform" name="mo_openid_button_theme_effect" value="transform" onclick="shape_change();checkLoginButton();moLoginPreview(setSizeOfIcons(), setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value,document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,'transform')"  <?php checked( get_option( 'mo_openid_button_theme_effect' ) == 'transform' ); ?>/><br>
							<span class="mo-openid-radio-checkmark" ></span></label>

					</div>
					<div style="float: right; width: 50%;">
						<label style="font-size: 1.2em;"><b><?php echo esc_attr( mo_sl( 'Theme' ) ); ?></b><br></label>
						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Default' ) ); ?>
							<input type="radio" id="mo_openid_default_background_radio" name="mo_openid_login_custom_theme"
								   value="default" onclick="checkLoginButton();moLoginPreview(setSizeOfIcons(), setLoginTheme(),'default',document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value,document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())"
								<?php checked( get_option( 'mo_openid_login_custom_theme' ) == 'default' ); ?> /><br>
							<span class="mo-openid-radio-checkmark"></span></label>


						<label for="mo_openid_white_background_radio" class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'White Background' ) ); ?>
							<input type="radio" id="mo_openid_white_background_radio"  name="mo_openid_login_custom_theme"
								   value="white" onclick="checkLoginButton();moLoginPreview(setSizeOfIcons(), setLoginTheme(),'white',document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value,document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())"
								<?php checked( get_option( 'mo_openid_login_custom_theme' ) == 'white' ); ?> /><br>
							<span class="mo-openid-radio-checkmark"></span></label>

						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Custom background*' ) ); ?>
							<input type="radio" id="mo_openid_custom_background_radio"  name="mo_openid_login_custom_theme" value="custom" onclick="checkLoginButton();moLoginPreview(setSizeOfIcons(), setLoginTheme(),'custom',document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value,document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())"
								<?php checked( get_option( 'mo_openid_login_custom_theme' ) == 'custom' ); ?> /><br>
							<span class="mo-openid-radio-checkmark"></span></label>

						<input id="mo_login_icon_custom_color" style="width:135px;margin-bottom: 5px;" name="mo_login_icon_custom_color"  class="color" value="<?php echo esc_attr( get_option( 'mo_login_icon_custom_color' ) ); ?>" onchange="moLoginPreview(setSizeOfIcons(), setLoginTheme(),'custom',document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value,'',document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())" >


						<label for="mo_openid_hover_radio" class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Hover' ) ); ?>
							<input type="radio" id="mo_openid_hover_radio"  name="mo_openid_login_custom_theme"
								   value="hover" onclick="checkLoginButton();moLoginPreview(setSizeOfIcons(), setLoginTheme(),'hover',document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value,document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())"
								<?php checked( get_option( 'mo_openid_login_custom_theme' ) == 'hover' ); ?> /><br>
							<span class="mo-openid-radio-checkmark"></span></label>


						<label for="mo_openid_custom_hover_radio" class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Custom Hover' ) ); ?>
							<input type="radio" id="mo_openid_custom_hover_radio"  name="mo_openid_login_custom_theme"
								   value="custom_hover" onclick="checkLoginButton();moLoginPreview(setSizeOfIcons(), setLoginTheme(),'custom_hover',document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value,document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())"
								<?php checked( get_option( 'mo_openid_login_custom_theme' ) == 'custom_hover' ); ?> /><br>
							<span class="mo-openid-radio-checkmark"></span></label>
						<input id="mo_login_icon_custom_hover_color" style="width:135px;margin-bottom: 5px;" name="mo_login_icon_custom_hover_color"  class="color" value="<?php echo esc_attr( get_option( 'mo_login_icon_custom_hover_color' ) ); ?>" onchange="moLoginPreview(setSizeOfIcons(), setLoginTheme(),'custom_hover',document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value,'',document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())" />


						<label for="mo_openid_custom_smart_radio" class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Smart' ) ); ?>
							<input type="radio" id="mo_openid_custom_smart_radio"  name="mo_openid_login_custom_theme"
								   value="smart" onclick="checkLoginButton();moLoginPreview(setSizeOfIcons(), setLoginTheme(),'smart',document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value,document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())"
								<?php checked( get_option( 'mo_openid_login_custom_theme' ) == 'smart' ); ?> /><br>
							<span class="mo-openid-radio-checkmark"></span></label>
						<input id="mo_login_icon_custom_smart_color1" style="width:66px;margin-bottom: 5px;display: inline-block;" name="mo_login_icon_custom_smart_color1"  class="color" value="<?php echo esc_attr( get_option( 'mo_login_icon_custom_smart_color1' ) ); ?>" onchange="moLoginPreview(setSizeOfIcons(), setLoginTheme(),'smart',document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value,'',document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())" />
						<input id="mo_login_icon_custom_smart_color2" style="width:66px;margin-bottom: 5px;" name="mo_login_icon_custom_smart_color2"  class="color" value="<?php echo esc_attr( get_option( 'mo_login_icon_custom_smart_color2' ) ); ?>" onchange="moLoginPreview(setSizeOfIcons(), setLoginTheme(),'smart',document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value,'',document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())" />

					</div>
				</div>
				<div style="width: 85%; float: left" class="mo_openid_note_style"><strong><?php echo esc_attr( mo_sl( '*Custom background:' ) ); ?></strong> <?php echo esc_attr( mo_sl( 'This will change the background color of login icons' ) ); ?>.</div>

				<div style="width: 100%; float: left">
					<div style="float:left;width:50%; margin-top: 5%">
						<label style="font-size: 1.2em;"><b><?php echo esc_attr( mo_sl( 'Size of Icons' ) ); ?></b></label>
						<div id="long_button_size">
							<?php echo esc_attr( mo_sl( 'Width:' ) ); ?> &nbsp;<input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 16%;margin-bottom: 3px" id="mo_login_icon_width" onkeyup="moLoginWidthValidate(this)" name="mo_login_icon_custom_width" type="text" value="<?php echo esc_attr( get_option( 'mo_login_icon_custom_width' ) ); ?>" >
							<input id="mo_login_width_plus" type="button" value="+" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" >
							<input id="mo_login_width_minus" type="button" value="-" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" ><br/>
							<?php echo esc_attr( mo_sl( 'Height:' ) ); ?>&nbsp;<input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 16%;margin-bottom: 3px" id="mo_login_icon_height" onkeyup="moLoginHeightValidate(this)" name="mo_login_icon_custom_height" type="text" value="<?php echo esc_attr( get_option( 'mo_login_icon_custom_height' ) ); ?>" >
							<input id="mo_login_height_plus" type="button" value="+" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" >
							<input id="mo_login_height_minus" type="button" value="-" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" ><br/>
							<?php echo esc_attr( mo_sl( 'Curve:' ) ); ?>&nbsp;&nbsp;<input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 16%;margin-bottom: 3px" id="mo_login_icon_custom_boundary" onkeyup="moLoginBoundaryValidate(this)" name="mo_login_icon_custom_boundary" type="text" value="<?php echo esc_attr( get_option( 'mo_login_icon_custom_boundary' ) ); ?>" />
							<input id="mo_login_boundary_plus" type="button" value="+" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" />
							<input id="mo_login_boundary_minus" type="button" value="-" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" />
						</div>
						<div id="other_button_size">
							<input style="width:50px" id="mo_login_icon_size" onkeyup="moLoginSizeValidate(this)" name="mo_login_icon_custom_size" type="text" value="<?php echo esc_attr( get_option( 'mo_login_icon_custom_size' ) ); ?>">
							<input id="mo_login_size_plus" type="button" value="+" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_size').value ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value,'',document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())" />
							<input id="mo_login_size_minus" type="button" value="-" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_size').value ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value,'',document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())" />
						</div>
					</div>
					<div style="width: 50%;float: right;margin-top: 5%">
						<label style="font-size: 1.2em;"><b><?php echo esc_attr( mo_sl( 'Space between Icons' ) ); ?></b></label><br/>
						<input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 16%;margin-bottom: 3px" onkeyup="moLoginSpaceValidate(this)" id="mo_login_icon_space" name="mo_login_icon_space" type="text" value="<?php echo esc_attr( get_option( 'mo_login_icon_space' ) ); ?>"/>
						<input id="mo_login_space_plus" type="button" value="+" onmouseup="moLoginPreview(setSizeOfIcons() ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value,'',document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())" />
						<input id="mo_login_space_minus" type="button" value="-" onmouseup="moLoginPreview(setSizeOfIcons()  ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value,'',document.getElementById('mo_login_icon_custom_hover_color').value,document.getElementById('mo_login_icon_custom_smart_color1').value,document.getElementById('mo_login_icon_custom_smart_color2').value,setLoginEffect())" />
					</div>


				</div>

				<div style="width: 100%; float: left">
					<br><hr>
					<div style="width: 100%">
						<label class="mo_openid_checkbox_container"><?php echo esc_attr( mo_sl( 'Load Fontawesome files' ) ); ?>
							<input  type="checkbox" id="mo_fonawesome_load" name="mo_openid_fonawesome_load" value="1" <?php checked( get_option( 'mo_openid_fonawesome_load' ) == 1 ); ?> /><br>
							<span class="mo_openid_checkbox_checkmark"></span>
						</label>

						<label class="mo_openid_checkbox_container"><?php echo esc_attr( mo_sl( 'Load bootstrap files' ) ); ?>
							<input  type="checkbox" id="mo_bootstrap_load" name="mo_openid_bootstrap_load" value="1" <?php checked( get_option( 'mo_openid_bootstrap_load' ) == 1 ); ?> /><br>
							<span class="mo_openid_checkbox_checkmark"></span>
						</label>
						<h4 style="font-size: 1.2em"><?php echo esc_attr( mo_sl( 'Customize Text For Social Login Buttons / Icons' ) ); ?></h4>

						<div style="width: 40%; float: left">
							<label class="mo_openid_fix_fontsize"><?php echo esc_attr( mo_sl( 'Select color for customize text:' ) ); ?></label>
						</div>
						<div style="width: 60%; float: right;">
							<input id="mo_openid_table_textbox" name="mo_login_openid_login_widget_customize_textcolor"  class="color" value="<?php echo esc_attr( get_option( 'mo_login_openid_login_widget_customize_textcolor' ) ); ?>" >
						</div>
					</div>
					<div style="width: 100%; margin-top: 12%">
						<div style="width: 100%;">
							<label class="mo_openid_fix_fontsize"><?php echo esc_attr( mo_sl( 'Enter text to show above login widget:' ) ); ?></label>
							<input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2" type="text" name="mo_openid_login_widget_customize_text" value="<?php echo esc_attr( get_option( 'mo_openid_login_widget_customize_text' ) ); ?>" />
						</div>
					</div>
					<div style="width: 100%; margin-top: 2%" id="long_button_text">
						<div style="width: 100%; float: left">
							<label class="mo_openid_fix_fontsize"><?php echo esc_attr( mo_sl( 'Enter text to show on your login buttons:' ) ); ?></label>
							<input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2" type="text" name="mo_openid_login_button_customize_text" value="<?php echo esc_attr( get_option( 'mo_openid_login_button_customize_text' ) ); ?>"  />
						</div>
					</div>
				</div>
				<div style="width: 100%; float: left">
					<br><hr>
					<h4 style="font-size: 1.2em"><?php echo esc_attr( mo_sl( 'Customize Text to show user after Login' ) ); ?></h4>
					<div style="width: 100%;">
						<div style="width: 100%; float: left">
							<label class="mo_openid_fix_fontsize"><?php echo esc_attr( mo_sl( 'Enter text to show before the logout link. Use ##username## to display current username:' ) ); ?></label> <br>
							<input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2" type="text" name="mo_openid_login_widget_customize_logout_name_text"  value="<?php echo esc_attr( get_option( 'mo_openid_login_widget_customize_logout_name_text' ) ); ?>" />
						</div>
					</div>
					<div style="width: 100%; margin-top: 15%" id="long_button_text">
						<br>
						<div style="width: 100%; float: left">
							<label class="mo_openid_fix_fontsize"><?php echo esc_attr( mo_sl( 'Enter text to show as logout link:' ) ); ?></label>
							<input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2" type="text" name="mo_openid_login_widget_customize_logout_text" value="<?php echo esc_attr( get_option( 'mo_openid_login_widget_customize_logout_text' ) ); ?>"  />
						</div>
					</div>
					<br/>
					<div style="margin-top: 8%; margin-bottom: 2%">
						<b><input type="submit" name="submit" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?>" style="width:150px;background-color:#0867b2;color:white;box-shadow:none;text-shadow: none;"  class="button button-primary button-large" /></b>
					</div>
				</div>
			</div>
			<div style="display: inline-block;float:left; width: 42%">
			<center>
			<div style="border: 1px solid black; min-width: 200px; min-height: 200px; padding-top: 8%; padding-bottom: 5%">
				<b>Preview : </b><br/>
				<div style="padding-bottom: 1%; padding-top: 3%">
					<?php
					$default_color = array(
						'facebook'      => '#1877F2',
						'google'        => '#DB4437',
						'vkontakte'     => '#466482',
						'twitter'       => '#2795e9',
						'yahoo'         => '#430297',
						'yandex'        => '#2795e9',
						'linkedin'      => '#007bb6',
						'amazon'        => '#ff9900',
						'paypal'        => '#0d127a',
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
						'stackexchange' => '0000ff',
						'snapchat'      => '#fffc00',
						'reddit'        => '#ff4301',
						'odnoklassniki' => '#f97400',
						'foursquare'    => '#f94877',
						'wechat'        => '#00c300',
						'vimeo'         => '#1ab7ea',
						'line'          => '#00c300',
						'hubspot'       => '#fa7820',
						'trello'        => '#0079bf',
						'discord'       => '#7289da',
						'meetup'        => '#e51937',
						'stackexchange' => '#0000FF',
						'wiebo'         => '#df2029',
						'gitlab'        => '#30353e',
						'slack'         => '#4c154d',
						'dropbox'       => '#0061ff',
						'mailru'        => '#0000FF',
						'kakao'         => '#ffe812',
					);

					$app_pos   = get_option( 'app_pos' );
					$app_pos   = explode( '#', $app_pos );
					$count_app = 0;
					foreach ( $app_pos as $active_app ) {
						if ( get_option( 'mo_openid_' . $active_app . '_enable' ) ) {
							$class_app = $active_app;
							$icon      = $active_app;
							if ( $active_app == 'vkontakte' ) {
								$class_app = 'vk';
								$icon      = 'vk';
							} elseif ( $active_app == 'salesforce' ) {
								$class_app = 'vimeo';
								$icon      = 'salesforce';
							}
							if ( $active_app == 'google' ) {
								$count_app++;
								?>
								<i class="mo_login_icon_preview <?php echo esc_attr( $extra ); ?> fab fa-<?php echo esc_attr( $active_app ); ?>" id="mo_login_icon_preview_facebook" style="background:<?php echo esc_attr( $default_color[ $active_app ] ); ?> !important;text-align:center;margin-top:5px;color: white" ></i>
								<a style="background: rgb(255,255,255)!important; background:linear-gradient(90deg, rgba(255,255,255,1) 38px, rgb(79, 113, 232) 5%) !important;border-color: rgba(79, 113, 232, 1);border-bottom-width: thin;" id="mo_login_button_preview_google" class="mo_btn mo_btn-block mo_btn-defaulttheme mo_btn-social mo_btn-google mo_btn-custom-size mo_login_button mo_btn-<?php echo esc_attr( $active_app ); ?> <?php echo esc_attr( $extra ); ?>"> <img class="fa" src="<?php echo esc_url( PLUGIN_URL . '/g.png' ); ?>"> <span><?php echo esc_html( get_option( 'mo_openid_login_button_customize_text' ) ); ?> Google</span></a>

								<i class="mo_white_login_icon_preview  fab fa-google" id="mo_white_login_icon_preview_google"  style="color:<?php echo esc_attr( $default_color[ $active_app ] ); ?>;text-align:center;margin-top:5px;"></i>
								<a id="mo_white_login_button_preview_google" class="mo_btn mo_btn-block mo_openid_mo_btn-whitetheme  mo_btn-social mo_btn-custom-size "> <img class="fa" src="<?php echo esc_url( PLUGIN_URL . '/g.png' ); ?>">
																																																		 <?php
																																																			echo esc_html( get_option( 'mo_openid_login_button_customize_text' ) );
																																																			?>
									 Google</a>

								<div style="display: inline-block" id="mo_hover_div_google" class="mo_hover_div" onmouseover="mo_hover_on(this,'<?php echo esc_attr( $default_color[ $active_app ] ); ?>','<?php echo esc_attr( $active_app ); ?>')" onmouseout="mo_hover_off(this,'<?php echo esc_attr( $default_color[ $active_app ] ); ?>','<?php echo esc_attr( $active_app ); ?>')">
									<i class="mo_hover_login_icon_preview fab fa-<?php echo esc_attr( $icon ); ?> " id="mo_hover_login_icon_preview_<?php echo esc_attr( $active_app ); ?>"   style="color:<?php echo esc_attr( $default_color[ $active_app ] ); ?>;text-align:center;margin-top:5px;"></i>
									<a id="mo_hover_login_button_preview_<?php echo esc_attr( $active_app ); ?>" class="mo_btn mo_btn-block mo_openid_mo_btn-hovertheme   mo_btn-social mo_btn-custom-size "> <i id="mo_hover_long_button_<?php echo esc_attr( $active_app ); ?>" style="color:<?php echo esc_attr( $default_color[ $active_app ] ); ?>;" class="fab fa-<?php echo esc_attr( $icon ); ?> mofab"></i>
																					<?php
																					echo esc_html( get_option( 'mo_openid_login_button_customize_text' ) );
																					?>
										 <?php echo esc_attr( $active_app ); ?></a>
								</div>

								<div style="display: inline-block" id="mo_customhover_div_google" class="mo_customhover_div" onmouseover="mo_custom_hover_on(this,'<?php echo esc_attr( $active_app ); ?>')" onmouseout="mo_custom_hover_off(this,'<?php echo esc_attr( $active_app ); ?>')" >
									<i class="mo_custom_hover_login_icon_preview fab fa-<?php echo esc_attr( $icon ); ?> " id="mo_custom_hover_login_icon_preview_<?php echo esc_attr( $active_app ); ?>"   style="color:<?php echo esc_attr( get_option( 'mo_login_icon_custom_hover_color' ) ); ?>;text-align:center;"></i>
									<a id="mo_custom_hover_login_button_preview_<?php echo esc_attr( $active_app ); ?>" class="mo_btn mo_btn-block mo_openid_mo_btn-customhovertheme   mo_btn-social mo_btn-custom-size " style="color:#<?php echo esc_attr( get_option( 'mo_login_icon_custom_hover_color' ) ); ?>;border-color:#<?php echo esc_attr( get_option( 'mo_login_icon_custom_hover_color' ) ); ?>;"> <i id="mo_hover_long_button_<?php echo esc_attr( $active_app ); ?>" style="color:<?php echo esc_attr( get_option( 'mo_login_icon_custom_hover_color' ) ); ?>;text-align:center;" class="fab mofab fa-<?php echo esc_attr( $icon ); ?>"></i>
																						   <?php
																							echo esc_html( get_option( 'mo_openid_login_button_customize_text' ) );
																							?>
										 <?php echo esc_attr( $active_app ); ?></a>
								</div>

								<i class="mo_custom_smart_login_icon_preview fab fa-google mo_button_smart_i " id="mo_custom_smart_login_icon_preview_google"  style="background:linear-gradient(90deg,#<?php echo esc_attr( get_option( 'mo_login_icon_custom_smart_color1' ) ); ?>,#<?php echo esc_attr( get_option( 'mo_login_icon_custom_smart_color2' ) ); ?>);text-align:center;margin-top:5px;color:#FFFFFF"></i>
								<a id="mo_custom_smart_login_button_preview_google" class="mo_btn_smart mo_btn-block   mo_btn-social mo_btn-custom-size mo_openid_mo_btn-smarttheme  mo_btn-social mo_btn-custom-size " style="background:linear-gradient(90deg,#<?php echo esc_attr( get_option( 'mo_login_icon_custom_smart_color1' ) ); ?>,#<?php echo esc_attr( get_option( 'mo_login_icon_custom_smart_color2' ) ); ?>);text-align:center;margin-top:5px;"> <i style="color:#FFFFFF" class="fab fa-google mofab"></i>
																																																																			<?php
																																																																			echo esc_html( get_option( 'mo_openid_login_button_customize_text' ) );
																																																																			?>
									 <?php echo esc_attr( $active_app ); ?></a>


								<i class="mo_custom_login_icon_preview fab fa-google" id="mo_custom_login_icon_preview_google"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
								<a id="mo_custom_login_button_preview_google" class="mo_btn mo_btn-block mo_openid_mo_btn-customtheme mo_btn-social mo_btn-custom-size " style="color: #FFFFFF"> <i class="fab fa-google mofab"></i>
								<?php
									echo esc_html( get_option( 'mo_openid_login_button_customize_text' ) );
								?>
									 Google</a>


								<?php
							} else {
								$count_app++;
								?>
								<i class="mo_login_icon_preview <?php echo esc_attr( $extra ); ?> fab fa-<?php echo esc_attr( $icon ); ?>" id="mo_login_icon_preview_facebook" style="background:<?php echo esc_attr( $default_color[ $active_app ] ); ?>;text-align:center;margin-top:5px;color: #FFFFFF" ></i>
								<a id="mo_login_button_preview_<?php echo esc_attr( $active_app ); ?>" class="mo_btn mo_btn-block mo_btn-defaulttheme mo_btn-social mo_btn-custom-size mo_login_button mo_btn-<?php echo esc_attr( $icon ); ?> <?php echo esc_attr( $extra ); ?>"> <i class="fab fa-<?php echo esc_attr( $icon ); ?> mo_login_button mofab"></i>
																		  <?php
																			echo esc_html( get_option( 'mo_openid_login_button_customize_text' ) );
																			?>
									 <?php echo esc_attr( $active_app ); ?></a>

								<i class="mo_white_login_icon_preview fab fa-<?php echo esc_attr( $icon ); ?> " id="mo_white_login_icon_preview_<?php echo esc_attr( $active_app ); ?>"  style="color:<?php echo esc_attr( $default_color[ $active_app ] ); ?>;text-align:center;margin-top:5px;"></i>
								<a id="mo_white_login_button_preview_<?php echo esc_attr( $active_app ); ?>" class="mo_btn mo_btn-block mo_openid_mo_btn-whitetheme   mo_btn-social mo_btn-custom-size "> <i style="color:<?php echo esc_attr( $default_color[ $active_app ] ); ?>;" class="fab fa-<?php echo esc_attr( $icon ); ?> mofab"></i>
																				<?php
																				echo esc_html( get_option( 'mo_openid_login_button_customize_text' ) );
																				?>
									 <?php echo esc_attr( $active_app ); ?></a>

								<div style="display: inline-block" class="mo_hover_div" id="mo_hover_div" onmouseover="mo_hover_on(this,'<?php echo esc_attr( $default_color[ $active_app ] ); ?>','<?php echo esc_attr( $active_app ); ?>')" onmouseout="mo_hover_off(this,'<?php echo esc_attr( $default_color[ $active_app ] ); ?>','<?php echo esc_attr( $active_app ); ?>')">
									<i class="mo_hover_login_icon_preview fab fa-<?php echo esc_attr( $icon ); ?> " id="mo_hover_login_icon_preview_<?php echo esc_attr( $active_app ); ?>"   style="color:<?php echo esc_attr( $default_color[ $active_app ] ); ?>;text-align:center;margin-top:5px;"></i>
									<a id="mo_hover_login_button_preview_<?php echo esc_attr( $active_app ); ?>" class="mo_btn mo_btn-block mo_openid_mo_btn-hovertheme   mo_btn-social mo_btn-custom-size "> <i id="mo_hover_long_button_<?php echo esc_attr( $active_app ); ?>" style="color:<?php echo esc_attr( $default_color[ $active_app ] ); ?>;" class="fab fa-<?php echo esc_attr( $icon ); ?>  mofab"></i>
																					<?php
																					echo esc_html( get_option( 'mo_openid_login_button_customize_text' ) );
																					?>
										 <?php echo esc_attr( $active_app ); ?></a>
								</div>

								<div style="display: inline-block" class="mo_customhover_div" id="mo_customhover_div_<?php echo esc_attr( $active_app ); ?>" onmouseover="mo_custom_hover_on(this,'<?php echo esc_attr( $active_app ); ?>')" onmouseout="mo_custom_hover_off(this,'<?php echo esc_attr( $active_app ); ?>')" >
									<i class="mo_custom_hover_login_icon_preview fab fa-<?php echo esc_attr( $icon ); ?> " id="mo_custom_hover_login_icon_preview_<?php echo esc_attr( $active_app ); ?>"   style="color:#<?php echo esc_attr( get_option( 'mo_login_icon_custom_hover_color' ) ); ?>;text-align:center;"></i>
									<a id="mo_custom_hover_login_button_preview_<?php echo esc_attr( $active_app ); ?>" class="mo_btn mo_btn-block mo_openid_mo_btn-customhovertheme   mo_btn-social mo_btn-custom-size " style="color:#<?php echo esc_attr( get_option( 'mo_login_icon_custom_hover_color' ) ); ?>;border-color:#<?php echo esc_attr( get_option( 'mo_login_icon_custom_hover_color' ) ); ?>;"> <i id="mo_hover_long_button_<?php echo esc_attr( $active_app ); ?>" style="text-align:center;" class="fab fa-<?php echo esc_attr( $icon ); ?>  mofab"></i>
																						   <?php
																							echo esc_html( get_option( 'mo_openid_login_button_customize_text' ) );
																							?>
										 <?php echo esc_attr( $active_app ); ?></a>
								</div>

								<i class="mo_custom_smart_login_icon_preview fab fa-<?php echo esc_attr( $icon ); ?>  " id="mo_custom_smart_login_icon_preview_<?php echo esc_attr( $active_app ); ?>"  style="background:linear-gradient(90deg,#<?php echo esc_attr( get_option( 'mo_login_icon_custom_smart_color1' ) ); ?>,#<?php echo esc_attr( get_option( 'mo_login_icon_custom_smart_color2' ) ); ?>);text-align:center;margin-top:5px;color:#FFFFFF"></i>
								<a id="mo_custom_smart_login_button_preview_<?php echo esc_attr( $active_app ); ?>" class=" mo_btn-block   mo_btn-social mo_btn-custom-size mo_openid_mo_btn-smarttheme  mo_btn-social mo_btn-custom-size " style="background:linear-gradient(90deg,#<?php echo esc_attr( get_option( 'mo_login_icon_custom_smart_color1' ) ); ?>,#<?php echo esc_attr( get_option( 'mo_login_icon_custom_smart_color2' ) ); ?>);text-align:center;margin-top:5px;"> <i style="color:#FFFFFF" class="fab fa-<?php echo esc_attr( $icon ); ?> mofab"></i>
																					   <?php
																						echo esc_html( get_option( 'mo_openid_login_button_customize_text' ) );
																						?>
									 <?php echo esc_attr( $active_app ); ?></a>


								<i class="mo_custom_login_icon_preview fab fa-<?php echo esc_attr( $icon ); ?>  " id="mo_custom_login_icon_preview_<?php echo esc_attr( $active_app ); ?>"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
								<a id="mo_custom_login_button_preview_<?php echo esc_attr( $active_app ); ?>" class="mo_btn mo_btn-block mo_openid_mo_btn-customtheme mo_btn-social mo_btn-custom-size " style="color: #FFFFFF"> <i class="fab fa-<?php echo esc_attr( $icon ); ?> mofab"></i>
																				 <?php
																					echo esc_html( get_option( 'mo_openid_login_button_customize_text' ) );
																					?>
									 <?php echo esc_attr( $active_app ); ?></a>
								<?php
							}
						}
					}
					if ( $count_app == 0 ) {
						?>
						<p>No activated apps found.</p>
						<?php
					}
					?>
				</div>
			</div><br/>
				<div style="border: 1px solid"><br>
					<b style="font-size: 17px;"><?php echo esc_attr( mo_sl( 'Custom CSS' ) ); ?> <a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a></b>
						<textarea disabled type="text" id="mo_openid_custom_css" style="resize: vertical; width:400px; height:180px;  margin:5% auto;" rows="6" name="mo_openid_custom_css"></textarea><br/><b>Example CSS:</b>
						<p>NOTE: Please keep the class name same as example CSS.</p>
						<pre>
	 .mo_login_button {
	 width:100%;
	 height:50px;
	 padding-top:15px;
	 padding-bottom:15px;
	 margin-bottom:-1px;
	 border-radius:4px;
	 background: #7272dc;
	 text-align:center;
	 font-size:16px;
	 color:#fff;
	 margin-bottom:5px;
 }
							</pre>
				</div>
			</center>
			</div>
		</div>
	</form>
	<script>

		//to set heading name

		function mo_hover_on(x,y,z){
			var check=jQuery('input[name=mo_openid_login_theme]:checked', '#form-apps').val();
			if(check == 'longbutton'){
				var q = "mo_hover_login_button_preview_".concat(z);
				var r = "mo_hover_long_button_".concat(z);
				document.getElementById(q).style.borderColor= y;
				document.getElementById(q).style.background = y;
				document.getElementById(q).style.color= 'white';
				document.getElementById(r).style.color= 'white';
			} else {
				var p = "mo_hover_login_icon_preview_".concat(z);
				document.getElementById(p).style.background = y;
				document.getElementById(p).style.color = 'white';
				document.getElementById(p).style.borderColor = y;
			}

		}

		function mo_hover_off(x,y,z){
			var check=jQuery('input[name=mo_openid_login_theme]:checked', '#form-apps').val();
			if(check == 'longbutton'){
				var q = "mo_hover_login_button_preview_".concat(z);
				var r = "mo_hover_long_button_".concat(z);
				document.getElementById(q).style.borderColor= 'black';
				document.getElementById(q).style.background = 'white';
				document.getElementById(q).style.color= 'black';
				document.getElementById(r).style.color= y;
			}else {
				var p = "mo_hover_login_icon_preview_".concat(z);
				document.getElementById(p).style.background = 'white';
				document.getElementById(p).style.color = y;
				document.getElementById(p).style.borderColor = 'black';
			}
		}
		function mo_custom_hover_on(x,z) {
			var check=jQuery('input[name=mo_openid_login_theme]:checked', '#form-apps').val();
			var color = document.getElementById('mo_login_icon_custom_hover_color').value;
			if(check == 'longbutton') {
				var ch_q = "mo_custom_hover_login_button_preview_".concat(z);
				var ch_r = "mo__custom_hover_long_button_".concat(z);
				document.getElementById(ch_q).style.borderColor= '#'+color;
				document.getElementById(ch_q).style.background = '#'+color;
				document.getElementById(ch_q).style.color= 'white';
				document.getElementById(ch_r).style.color= 'white';
			}else{
				var ch_p = "mo_custom_hover_login_icon_preview_".concat(z);
				document.getElementById(ch_p).style.background = '#'+ color;
				document.getElementById(ch_p).style.color= 'white';
				document.getElementById(ch_p).style.borderColor=  '#'+color;
			}

		}

		function mo_custom_hover_off(x,z) {
			var check=jQuery('input[name=mo_openid_login_theme]:checked', '#form-apps').val();
			var color = document.getElementById('mo_login_icon_custom_hover_color').value;
			if(check == 'longbutton') {
				var ch_q = "mo_custom_hover_login_button_preview_".concat(z);
				var ch_r = "mo__custom_hover_long_button_".concat(z);
				document.getElementById(ch_q).style.borderColor= '#'+color;
				document.getElementById(ch_q).style.background = 'white';
				document.getElementById(ch_q).style.color= '#'+color;
				document.getElementById(ch_r).style.color= '#'+color;
			}else{
				var ch_p = "mo_custom_hover_login_icon_preview_".concat(z);
				document.getElementById(ch_p).style.background = 'white';
				document.getElementById(ch_p).style.color='#'+color;
				document.getElementById(ch_p).style.borderColor= '#'+color;
			}
		}

		window.onload=shape_change();
		function shape_change() {
			var y = document.getElementById('mo_openid_login_shape_longbutton').checked;
			if(y!=true) {
				jQuery('#long_button_size').hide();
				jQuery('#long_button_text').hide();
				jQuery('#other_button_size').show();
			}
			else {
				jQuery('#long_button_size').show();
				jQuery('#long_button_text').show();
				jQuery('#other_button_size').hide();
			}
		}

		var tempHorSize = '<?php echo esc_attr( get_option( 'mo_login_icon_custom_size' ) ); ?>';
		var tempHorTheme = '<?php echo esc_attr( get_option( 'mo_openid_login_theme' ) ); ?>';
		var tempHorCustomTheme = '<?php echo esc_attr( get_option( 'mo_openid_login_custom_theme' ) ); ?>';
		var tempHorCustomColor = '<?php echo esc_attr( get_option( 'mo_login_icon_custom_color' ) ); ?>';
		var tempHorSpace = '<?php echo esc_attr( get_option( 'mo_login_icon_space' ) ); ?>';
		var tempHorHeight = '<?php echo esc_attr( get_option( 'mo_login_icon_custom_height' ) ); ?>';
		var tempHorBoundary='<?php echo esc_attr( get_option( 'mo_login_icon_custom_boundary' ) ); ?>';
		var tempcustomhovercolor= '<?php echo esc_attr( get_option( 'mo_login_icon_custom_hover_color' ) ); ?>';
		var tempcustomSmartcolor1= '<?php echo esc_attr( get_option( 'mo_login_icon_custom_smart_color1' ) ); ?>';
		var tempcustomSmartcolor2= '<?php echo esc_attr( get_option( 'mo_login_icon_custom_smart_color2' ) ); ?>';
		var tempAllEffect= '<?php echo esc_attr( get_option( 'mo_openid_button_theme_effect' ) ); ?>';

		function moLoginSpaceValidate(e){
			var t=parseInt(e.value.trim());t>60?e.value=60:0>t&&(e.value=0)
		}

		function moLoginWidthValidate(e){
			var t=parseInt(e.value.trim());t>1000?e.value=1000:140>t&&(e.value=140)
		}
		function moLoginHeightValidate(e){
			var t=parseInt(e.value.trim());t>50?e.value=50:35>t&&(e.value=35)
		}
		function moLoginIncrement(e,t,r,a,i){
			var h,s,c=!1,_=a;s=function(){
				"add"==t&&r.value<60?r.value++:"subtract"==t&&r.value>20&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}

		function moLoginSpaceIncrement(e,t,r,a,i){
			var h,s,c=!1,_=a;s=function(){
				"add"==t&&r.value<60?r.value++:"subtract"==t&&r.value>0&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}

		function moLoginWidthIncrement(e,t,r,a,i){
			var h,s,c=!1,_=a;s=function(){
				"add"==t&&r.value<1000?r.value++:"subtract"==t&&r.value>140&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}

		function moLoginHeightIncrement(e,t,r,a,i){
			var h,s,c=!1,_=a;s=function(){
				"add"==t&&r.value<50?r.value++:"subtract"==t&&r.value>35&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}

		function moLoginBoundaryIncrement(e,t,r,a,i){
			var h,s,c=!1,_=a;s=function(){
				"add"==t&&r.value<25?r.value++:"subtract"==t&&r.value>0&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}

		moLoginIncrement(document.getElementById('mo_login_size_plus'), "add", document.getElementById('mo_login_icon_size'), 300, 0.7);
		moLoginIncrement(document.getElementById('mo_login_size_minus'), "subtract", document.getElementById('mo_login_icon_size'), 300, 0.7);

		moLoginIncrement(document.getElementById('mo_login_size_plus'), "add", document.getElementById('mo_login_icon_size'), 300, 0.7);
		moLoginIncrement(document.getElementById('mo_login_size_minus'), "subtract", document.getElementById('mo_login_icon_size'), 300, 0.7);

		moLoginSpaceIncrement(document.getElementById('mo_login_space_plus'), "add", document.getElementById('mo_login_icon_space'), 300, 0.7);
		moLoginSpaceIncrement(document.getElementById('mo_login_space_minus'), "subtract", document.getElementById('mo_login_icon_space'), 300, 0.7);

		moLoginWidthIncrement(document.getElementById('mo_login_width_plus'), "add", document.getElementById('mo_login_icon_width'), 300, 0.7);
		moLoginWidthIncrement(document.getElementById('mo_login_width_minus'), "subtract", document.getElementById('mo_login_icon_width'), 300, 0.7);

		moLoginHeightIncrement(document.getElementById('mo_login_height_plus'), "add", document.getElementById('mo_login_icon_height'), 300, 0.7);
		moLoginHeightIncrement(document.getElementById('mo_login_height_minus'), "subtract", document.getElementById('mo_login_icon_height'), 300, 0.7);

		moLoginBoundaryIncrement(document.getElementById('mo_login_boundary_plus'), "add", document.getElementById('mo_login_icon_custom_boundary'), 300, 0.7);
		moLoginBoundaryIncrement(document.getElementById('mo_login_boundary_minus'), "subtract", document.getElementById('mo_login_icon_custom_boundary'), 300, 0.7);

		function setLoginTheme(){return jQuery('input[name=mo_openid_login_theme]:checked', '#form-apps').val();}
		function setLoginEffect(){return jQuery('input[name=mo_openid_button_theme_effect]:checked', '#form-apps').val();}
		function setLoginCustomTheme(){return jQuery('input[name=mo_openid_login_custom_theme]:checked', '#form-apps').val();}
		function setSizeOfIcons(){
			if((jQuery('input[name=mo_openid_login_theme]:checked', '#form-apps').val()) == 'longbutton'){
				return document.getElementById('mo_login_icon_width').value;
			}else{
				return document.getElementById('mo_login_icon_size').value;
			}
		}
		moLoginPreview(setSizeOfIcons(),tempHorTheme,tempHorCustomTheme,tempHorCustomColor,tempHorSpace,tempHorHeight,tempHorBoundary,tempcustomhovercolor,tempcustomSmartcolor1,tempcustomSmartcolor2,tempAllEffect);

		function moLoginPreview(t,r,l,p,n,h,k,x,y,z,b){
			// alert('t='+t+', r='+r+', l='+l+', p='+p+', n='+n+', h='+h+', k='+k+', x='+x+', y='+y+', z='+z+', b='+b);
			if(l == 'default'){
				if(r == 'longbutton'){
					var a = "mo_btn-defaulttheme";
					jQuery("."+a).css("width",t+"px");
					jQuery("."+a).each(function () {
						jQuery(this).css("margin-top", "5px");
						jQuery(this).css("padding-top",(h-29)+"px");
						jQuery(this).css("padding-bottom",(h-29)+"px");
						jQuery(this).css("margin-bottom",(n-5)+"px");
						jQuery(this).css("border-radius",k+"px");
					});
					jQuery(".mofab").css("padding-top",(h-35)+"px");
					if(b == "transform"){
						jQuery("."+a).addClass("mo_btn_transform");
					}else {
						jQuery("."+a).removeClass("mo_btn_transform");
					}
				}else{
					var a="mo_login_icon_preview";
					jQuery("."+a).css("cursor","pointer");
					jQuery("."+a).css({height:t-8,width:t});
					jQuery("."+a).css("padding-top","8px");
					jQuery("."+a).css("margin-left",(n-4)+"px");

					if(r=="circle"){
						jQuery("."+a).css("borderRadius","999px");
					}else if(r=="oval"){
						jQuery("."+a).css("borderRadius","5px");
					}else if(r=="square"){
						jQuery("."+a).css("borderRadius","0px");
					}
					jQuery("."+a).css("font-size",(t-16)+"px");
					jQuery("#disq").css({height:t-21});
					jQuery("#kaka").css({height:t-21});
					jQuery("#lines").css({height:t-22});
					jQuery("#meetups").css({height:t-21});
					jQuery("#navers").css({height:t-21});
					jQuery("#teamsnaps").css({height:t-21});
					jQuery("#livejournals").css({height:t-21});
					jQuery("#wiebos").css({height:t-21});
					jQuery("#hubs3").css({height:t-21});
					jQuery("#mailrus").css({height:t-17});
					if(b == 'transform'){
						jQuery("."+a).addClass("mo_btn_transform_i");
					}else{
						jQuery("."+a).removeClass("mo_btn_transform_i");
					}

				}
			}
			else if(l == 'white'){
				p = 'ffffff';
				if(r == 'longbutton'){
					var a = "mo_openid_mo_btn-whitetheme";
					jQuery("."+a).css("margin-top", "5px");
					jQuery("."+a).css("width",(t)+"px");
					jQuery("."+a).css("padding-top",(h-29)+"px");
					jQuery("."+a).css("padding-bottom",(h-29)+"px");
					jQuery("."+a).css("border-color", "#000000");
					jQuery("."+a).css("color", "#000000");
					jQuery(".fa").css("padding-top",(h-35)+"px");
					jQuery("."+a).css("margin-bottom",(n-5)+"px");
					jQuery("."+a).css("background","#"+p);
					jQuery("."+a).css("border-radius",k+"px");
					 if(b == "transform"){
						jQuery("."+a).addClass("mo_btn_transform");
					}else {
						jQuery("."+a).removeClass("mo_btn_transform");
					}
				}else{
					var a = "mo_white_login_icon_preview";
					jQuery("."+a).css("cursor","pointer");
					jQuery("."+a).css({"height":t-8,width:t});
					jQuery("."+a).css("padding-top","8px");
					jQuery("."+a).css("font-size",(t-14)+"px");
					jQuery("."+a).css("border-color","black");
					jQuery("."+a).css("border-style","solid");
					jQuery("."+a).css("border-width","1px");
					jQuery("."+a).css("margin-left",(n-4)+"px");

					if(r=="circle"){
						jQuery("."+a).css("borderRadius","999px");
					}else if(r=="oval"){
						jQuery("."+a).css("borderRadius","5px");
					}else if(r=="square"){
						jQuery("."+a).css("borderRadius","0px");
					}
					if(b == 'transform'){
						jQuery("."+a).addClass("mo_btn_transform_i");
					}else{
						jQuery("."+a).removeClass("mo_btn_transform_i");
					}
				}
			}

			else if(l == 'hover'){
				p = 'ffffff';
				if(r == 'longbutton'){
					var a = "mo_openid_mo_btn-hovertheme";
					jQuery("."+a).css("margin-top", "5px");
					jQuery("."+a).css("width",(t)+"px");
					jQuery("."+a).css("padding-top",(h-29)+"px");
					jQuery("."+a).css("padding-bottom",(h-29)+"px");
					jQuery("."+a).css("border-color", "#000000");
					jQuery("."+a).css("color", "#000000");
					jQuery(".fa").css("padding-top",(h-35)+"px");
					jQuery("."+a).css("margin-bottom",(n-5)+"px");
					jQuery("."+a).css("background","#"+p);
					jQuery("."+a).css("border-radius",k+"px");
					// jQuery(".mo_hover_div").css("display","block");
					jQuery(".mofab").css("padding-top",(h-35)+"px");
					 if(b == "transform"){
						jQuery("."+a).addClass("mo_btn_transform");
					}else {
						jQuery("."+a).removeClass("mo_btn_transform");
					}

				}else{
					var a = "mo_hover_login_icon_preview";
					jQuery("."+a).css("cursor","pointer");
					jQuery("."+a).css({"height":t-8,width:t});
					jQuery("."+a).css("padding-top","8px");
					jQuery("."+a).css("font-size",(t-14)+"px");
					jQuery("."+a).css("border-color","black");
					jQuery("."+a).css("border-style","solid");
					jQuery("."+a).css("border-width","1px");
					jQuery("."+a).css("margin-left",(n-4)+"px");
					// jQuery(".mo_hover_div").css("display","inline-block");

					if(r=="circle"){
						jQuery("."+a).css("borderRadius","999px");
					}else if(r=="oval"){
						jQuery("."+a).css("borderRadius","5px");
					}else if(r=="square"){
						jQuery("."+a).css("borderRadius","0px");
					}
					if(b == 'transform'){
						jQuery("."+a).addClass("mo_btn_transform_i");
					}else{
						jQuery("."+a).removeClass("mo_btn_transform_i");
					}
				}
			}

			else if(l == 'custom_hover'){
				if(r == 'longbutton'){
					var a = "mo_openid_mo_btn-customhovertheme";
					jQuery("."+a).css("margin-top", "5px");
					jQuery("."+a).css("width",(t)+"px");
					jQuery("."+a).css("padding-top",(h-29)+"px");
					jQuery("."+a).css("padding-bottom",(h-29)+"px");
					jQuery("."+a).css("border-color", "#"+x);
					// jQuery("."+a).css("color", "#000000");
					jQuery(".fa").css("padding-top",(h-35)+"px");
					jQuery("."+a).css("margin-bottom",(n-5)+"px");
					jQuery("."+a).css("color","#"+x);
					jQuery("."+a).css("border-radius",k+"px");
					// jQuery(".mo_customhover_div").css("display","block");
					jQuery(".mofab").css("padding-top",(h-35)+"px");
					if(b == "transform"){
						jQuery("."+a).addClass("mo_btn_transform");
					}else {
						jQuery("."+a).removeClass("mo_btn_transform");
					}

				}else{
					var a = "mo_custom_hover_login_icon_preview";
					jQuery("."+a).css("margin-top", "5px");
					jQuery("."+a).css("cursor","pointer");
					jQuery("."+a).css({"height":t-8,width:t});
					jQuery("."+a).css("padding-top","8px");
					jQuery("."+a).css("font-size",(t-16)+"px");
					jQuery("."+a).css("border-color","#"+x);
					jQuery("."+a).css("border-style","solid");
					jQuery("."+a).css("border-width","1px");
					jQuery("."+a).css("margin-left",(n-4)+"px");
					jQuery("."+a).css("color","#"+x);
					// jQuery(".mo_customhover_div").css("display","inline-block");

					if(r=="circle"){
						jQuery("."+a).css("borderRadius","999px");
					}else if(r=="oval"){
						jQuery("."+a).css("borderRadius","5px");
					}else if(r=="square"){
						jQuery("."+a).css("borderRadius","0px");
					}
					if(b == 'transform'){
						jQuery("."+a).addClass("mo_btn_transform_i");
					}else{
						jQuery("."+a).removeClass("mo_btn_transform_i");
					}
				}
			}

			else if(l == 'smart'){
				if(r == 'longbutton'){
					var a = "mo_openid_mo_btn-smarttheme";
					jQuery("."+a).css("margin-top", "5px");
					jQuery("."+a).css("width",(t)+"px");
					jQuery("."+a).css("padding-top",(h-29)+"px");
					jQuery("."+a).css("padding-bottom",(h-29)+"px");
					jQuery("."+a).css("color", "#ffffff");
					jQuery(".fa").css("padding-top",(h-35)+"px");
					jQuery("."+a).css("margin-bottom",(n-5)+"px");
					jQuery("."+a).css("background", "linear-gradient(45deg,#"+y+",#"+z+")");
					jQuery("."+a).css("border-radius",k+"px");
					jQuery(".mofab").css("padding-top",(h-35)+"px");
					 if(b == "transform"){
						jQuery("."+a).addClass("mo_btn_transform");
					}else {
						jQuery("."+a).removeClass("mo_btn_transform");
					}
				}else{
					var a = "mo_custom_smart_login_icon_preview";
					jQuery("."+a).css("cursor","pointer");
					jQuery("."+a).css({"height":t-8,width:t});
					jQuery("."+a).css("padding-top","8px");
					jQuery("."+a).css("font-size",(t-14)+"px");
					jQuery("."+a).css("border-style","solid");
					jQuery("."+a).css("border-width","1px");
					jQuery("."+a).css("background", "linear-gradient(90deg,#"+y+",#"+z+")");
					jQuery("."+a).css("margin-left",(n-4)+"px");

					if(r=="circle"){
						jQuery("."+a).css("borderRadius","999px");
					}else if(r=="oval"){
						jQuery("."+a).css("borderRadius","5px");
					}else if(r=="square"){
						jQuery("."+a).css("borderRadius","0px");
					}
					if(b == 'transform'){
						jQuery("."+a).addClass("mo_btn_transform_i");
					}else{
						jQuery("."+a).removeClass("mo_btn_transform_i");
					}
				}
			}

			else if(l == 'custom'){
				if(r == 'longbutton'){
					var a = "mo_openid_mo_btn-customtheme";
					jQuery("."+a).css("margin-top", "5px");
					jQuery("."+a).css("width",(t)+"px");
					jQuery("."+a).css("padding-top",(h-29)+"px");
					jQuery("."+a).css("padding-bottom",(h-29)+"px");
					jQuery(".fa").css("padding-top",(h-35)+"px");
					jQuery("."+a).css("margin-bottom",(n-5)+"px");
					jQuery("."+a).css("background","#"+p);
					jQuery("."+a).css("border-radius",k+"px");
					 if(b == "transform"){
						jQuery("."+a).addClass("mo_btn_transform");
					}else {
						jQuery("."+a).removeClass("mo_btn_transform");
					}
				}else{
					var a="mo_custom_login_icon_preview";
					jQuery("."+a).css("cursor","pointer");
					jQuery("."+a).css({height:t-8,width:t});
					jQuery("."+a).css("padding-top","8px");
					jQuery("."+a).css("margin-left",(n-4)+"px");

					if(r=="circle"){
						jQuery("."+a).css("borderRadius","999px");
					}else if(r=="oval"){
						jQuery("."+a).css("borderRadius","5px");
					}else if(r=="square"){
						jQuery("."+a).css("borderRadius","0px");
					}
					jQuery("."+a).css("background","#"+p);
					jQuery("."+a).css("font-size",(t-16)+"px");
					jQuery("#disq").css({height:t-21});
					jQuery("#kaka").css({height:t-21});
					jQuery("#lines").css({height:t-22});
					jQuery("#meetups").css({height:t-21});
					jQuery("#yandexs").css({height:t-21});
					jQuery("#discords").css({height:t-21});
					jQuery("#navers").css({height:t-21});
					jQuery("#teamsnaps").css({height:t-21});
					jQuery("#livejournals").css({height:t-21});
					jQuery("#wiebos").css({height:t-21});
					jQuery("#hubs3").css({height:t-21});
					jQuery("#mailrus").css({height:t-21});
					if(b == 'transform'){
						jQuery("."+a).addClass("mo_btn_transform_i");
					}else{
						jQuery("."+a).removeClass("mo_btn_transform_i");
					}
				}
			}
			previewLoginIcons();
		}

		function checkLoginButton(){
			if(jQuery('input[name=mo_openid_login_theme]:checked', '#form-apps').val() == 'longbutton') {
				if(setLoginCustomTheme() == 'default'){
					jQuery(".mo_login_icon_preview").hide();
					jQuery(".mo_custom_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-customtheme").hide();
					jQuery(".mo_btn-defaulttheme").show();
					jQuery(".mo_white_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-whitetheme").hide();
					jQuery(".mo_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-hovertheme").hide();
					jQuery(".mo_custom_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-customhovertheme").hide();
					jQuery(".mo_custom_smart_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-smarttheme").hide();
				}

				else if(setLoginCustomTheme() == 'white'){
					jQuery(".mo_login_icon_preview").hide();
					jQuery(".mo_custom_login_icon_preview").hide();
					jQuery(".mo_btn-defaulttheme").hide();
					jQuery(".mo_openid_mo_btn-customtheme").hide();
					jQuery(".mo_white_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-whitetheme").show();
					jQuery(".mo_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-hovertheme").hide();
					jQuery(".mo_custom_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-customhovertheme").hide();
					jQuery(".mo_custom_smart_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-smarttheme").hide();
				}

				else if(setLoginCustomTheme() == 'hover'){
					jQuery(".mo_login_icon_preview").hide();
					jQuery(".mo_custom_login_icon_preview").hide();
					jQuery(".mo_btn-defaulttheme").hide();
					jQuery(".mo_openid_mo_btn-customtheme").hide();
					jQuery(".mo_white_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-whitetheme").hide();
					jQuery(".mo_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-hovertheme").show();
					jQuery(".mo_custom_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-customhovertheme").hide();
					jQuery(".mo_custom_smart_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-smarttheme").hide();
				}

				else if(setLoginCustomTheme() == 'custom_hover'){
					jQuery(".mo_login_icon_preview").hide();
					jQuery(".mo_custom_login_icon_preview").hide();
					jQuery(".mo_btn-defaulttheme").hide();
					jQuery(".mo_openid_mo_btn-customtheme").hide();
					jQuery(".mo_white_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-whitetheme").hide();
					jQuery(".mo_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-hovertheme").hide();
					jQuery(".mo_custom_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-customhovertheme").show();
					jQuery(".mo_custom_smart_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-smarttheme").hide();
				}

				else if(setLoginCustomTheme() == 'smart'){
					jQuery(".mo_login_icon_preview").hide();
					jQuery(".mo_custom_login_icon_preview").hide();
					jQuery(".mo_btn-defaulttheme").hide();
					jQuery(".mo_openid_mo_btn-customtheme").hide();
					jQuery(".mo_white_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-whitetheme").hide();
					jQuery(".mo_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-hovertheme").hide();
					jQuery(".mo_custom_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-customhovertheme").hide();
					jQuery(".mo_custom_smart_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-smarttheme").show();
				}

				else if(setLoginCustomTheme() == 'custom'){
					jQuery(".mo_login_icon_preview").hide();
					jQuery(".mo_custom_login_icon_preview").hide();
					jQuery(".mo_btn-defaulttheme").hide();
					jQuery(".mo_openid_mo_btn-customtheme").show();
					jQuery(".mo_white_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-whitetheme").hide();
					jQuery(".mo_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-hovertheme").hide();
					jQuery(".mo_custom_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-customhovertheme").hide();
					jQuery(".mo_custom_smart_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-smarttheme").hide();
				}
			}
			else {
				if(setLoginCustomTheme() == 'default'){
					jQuery(".mo_login_icon_preview").show();
					jQuery(".mo_btn-defaulttheme").hide();
					jQuery(".mo_openid_mo_btn-customtheme").hide();
					jQuery(".mo_custom_login_icon_preview").hide();
					jQuery(".mo_white_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-whitetheme").hide();
					jQuery(".mo_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-hovertheme").hide();
					jQuery(".mo_custom_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-customhovertheme").hide();
					jQuery(".mo_custom_smart_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-smarttheme").hide();
				}

				else if(setLoginCustomTheme() == 'white'){
					jQuery(".mo_login_icon_preview").hide();
					jQuery(".mo_custom_login_icon_preview").hide();
					jQuery(".mo_btn-defaulttheme").hide();
					jQuery(".mo_openid_mo_btn-customtheme").hide();
					jQuery(".mo_white_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-whitetheme").hide();
					jQuery(".mo_white_login_icon_preview").show();
					jQuery(".mo_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-hovertheme").hide();
					jQuery(".mo_custom_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-customhovertheme").hide();
					jQuery(".mo_custom_smart_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-smarttheme").hide();

				}

				else if(setLoginCustomTheme() == 'hover'){
					jQuery(".mo_login_icon_preview").hide();
					jQuery(".mo_custom_login_icon_preview").hide();
					jQuery(".mo_btn-defaulttheme").hide();
					jQuery(".mo_openid_mo_btn-customtheme").hide();
					jQuery(".mo_white_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-whitetheme").hide();
					jQuery(".mo_white_login_icon_preview").hide();
					jQuery(".mo_hover_login_icon_preview").show();
					jQuery(".mo_openid_mo_btn-hovertheme").hide();
					jQuery(".mo_custom_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-customhovertheme").hide();
					jQuery(".mo_custom_smart_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-smarttheme").hide();

				}

				else if(setLoginCustomTheme() == 'custom_hover'){
					jQuery(".mo_login_icon_preview").hide();
					jQuery(".mo_custom_login_icon_preview").hide();
					jQuery(".mo_btn-defaulttheme").hide();
					jQuery(".mo_openid_mo_btn-customtheme").hide();
					jQuery(".mo_white_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-whitetheme").hide();
					jQuery(".mo_white_login_icon_preview").hide();
					jQuery(".mo_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-hovertheme").hide();
					jQuery(".mo_custom_hover_login_icon_preview").show();
					jQuery(".mo_openid_mo_btn-customhovertheme").hide();
					jQuery(".mo_custom_smart_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-smarttheme").hide();

				}

				else if(setLoginCustomTheme() == 'smart'){
					jQuery(".mo_login_icon_preview").hide();
					jQuery(".mo_custom_login_icon_preview").hide();
					jQuery(".mo_btn-defaulttheme").hide();
					jQuery(".mo_openid_mo_btn-customtheme").hide();
					jQuery(".mo_white_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-whitetheme").hide();
					jQuery(".mo_white_login_icon_preview").hide();
					jQuery(".mo_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-hovertheme").hide();
					jQuery(".mo_custom_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-customhovertheme").hide();
					jQuery(".mo_custom_smart_login_icon_preview").show();
					jQuery(".mo_openid_mo_btn-smarttheme").hide();

				}

				else if(setLoginCustomTheme() == 'custom'){
					jQuery(".mo_login_icon_preview").hide();
					jQuery(".mo_custom_login_icon_preview").show();
					jQuery(".mo_btn-defaulttheme").hide();
					jQuery(".mo_openid_mo_btn-customtheme").hide();
					jQuery(".mo_white_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-whitetheme").hide();
					jQuery(".mo_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-hovertheme").hide();
					jQuery(".mo_custom_hover_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-customhovertheme").hide();
					jQuery(".mo_custom_smart_login_icon_preview").hide();
					jQuery(".mo_openid_mo_btn-smarttheme").hide();
				}
			}
			previewLoginIcons();
		}

		function previewLoginIcons() {
			var apps;
			apps='<?php echo esc_attr( get_option( 'app_pos' ) ); ?>';
			apps=apps.split('#');
			for(i=0;i<apps.length;i++){
				if(jQuery('input[name=mo_openid_login_custom_theme]:checked', '#form-apps').val() == 'default')
				{
					if(jQuery('input[name=mo_openid_login_theme]:checked', '#form-apps').val() =='longbutton')
						jQuery("#mo_login_button_preview_"+apps[i]).show();
					else
						jQuery("#mo_login_icon_preview_"+apps[i]).show();
				}

				else if(jQuery('input[name=mo_openid_login_custom_theme]:checked', '#form-apps').val() == 'white')
				{
					if(jQuery('input[name=mo_openid_login_theme]:checked', '#form-apps').val() == 'longbutton')
						jQuery("#mo_white_login_button_preview_"+apps[i]).show();
					else
						jQuery("#mo_white_login_icon_preview_"+apps[i]).show();
				}


				else if(jQuery('input[name=mo_openid_login_custom_theme]:checked', '#form-apps').val() == 'hover')
				{
					if(jQuery('input[name=mo_openid_login_theme]:checked', '#form-apps').val() == 'longbutton')
						jQuery("#mo_hover_login_button_preview_"+apps[i]).show();
					else
						jQuery("#mo_hover_login_icon_preview_"+apps[i]).show();
				}



				else if(jQuery('input[name=mo_openid_login_custom_theme]:checked', '#form-apps').val() == 'custom_hover')
				{
					if(jQuery('input[name=mo_openid_login_theme]:checked', '#form-apps').val() == 'longbutton')
						jQuery("#mo_custom_hover_login_button_preview_"+apps[i]).show();
					else
						jQuery("#mo_custom_hover_login_icon_preview_"+apps[i]).show();
				}

				else if(jQuery('input[name=mo_openid_login_custom_theme]:checked', '#form-apps').val() == 'custom')
				{
					if(jQuery('input[name=mo_openid_login_theme]:checked', '#form-apps').val() == 'longbutton')
						jQuery("#mo_custom_login_button_preview_"+apps[i]).show();
					else
						jQuery("#mo_custom_login_icon_preview_"+apps[i]).show();
				}
			}
		}
		checkLoginButton();
	</script>
	<?php
}
