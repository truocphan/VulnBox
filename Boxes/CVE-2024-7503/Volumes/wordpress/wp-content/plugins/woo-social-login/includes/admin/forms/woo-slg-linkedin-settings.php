<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Settings Page LinkedIn Tab
 * 
 * The code for the plugins settings page linkedin tab
 * 
 * @package WooCommerce - Social Login
 * @since 1.6.4
 */

// Set option for default value
$woo_slg_li_icon_text = (!empty($woo_slg_li_icon_text)) ? $woo_slg_li_icon_text : esc_html__( 'Sign in with LinkedIn', 'wooslg' ) ;
$woo_slg_li_link_icon_text = (!empty($woo_slg_li_link_icon_text)) ? $woo_slg_li_link_icon_text : esc_html__( 'Link your account to LinkedIn', 'wooslg' ) ;
if( $woo_slg_auth_type_linkedin == 'graph' ){ 
	$class_linkedin = 'graph_show';
}else{
	$class_linkedin = 'graph_hide';
}

$linkedin_style = "";
if($woo_slg_enable_linkedin == 'yes'){
	$linkedin_style = 'style="display:block"';
}

?>
<!-- beginning of the linkedin settings meta box -->
<div id="woo-slg-linkedin" class="post-box-container">
	<div class="metabox-holder">
		<div class="meta-box-sortables ui-sortable">
			<div id="linkedin" class="postbox">
				<div class="handlediv" title="<?php esc_html_e( 'Click to toggle', 'wooslg' ); ?>"><br /></div>
					
					<div class="woo-slg-social-icon-text-wrap">						
						<!-- linkedin settings box title -->
						<h3 class="hndle">
							<img src="<?php echo esc_url(WOO_SLG_IMG_URL).'/tab-icon/linkedin.svg'; ?>" alt="<?php esc_html_e('LinkedIn','wooslg');?>"/>
							<span class="woo-slg-vertical-top"><?php esc_html_e( 'LinkedIn Settings', 'wooslg' ); ?></span>
						</h3>
					</div>
					
					<div class="inside">

					<table class="form-table">
						<tbody>
							
							<?php
								// do action for add setting before linkedin settings
								do_action( 'woo_slg_before_linkedin_setting', $woo_slg_options );
							?>

							<tr>
								<th scope="row">
									<label for="woo_slg_enable_linkedin"><?php esc_html_e( 'Enable Login : ', 'wooslg' ); ?></label>
								</th>
								<td>
								<div class="d-flex-wrap fb-avatra">
									<label for="woo_slg_enable_linkedin" class="toggle-switch">	
										<input type="checkbox" id="woo_slg_enable_linkedin" name="woo_slg_enable_linkedin" value="1" <?php echo ($woo_slg_enable_linkedin=='yes') ? 'checked="checked"' : ''; ?>/>
										<span class="slider"></span>
									</label>
									<p><?php echo esc_html__( 'Check this box, if you want to enable LinkedIn social login registration.', 'wooslg' ); ?></p>
								</div>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="form-table linkedin_section_hide" <?php echo $linkedin_style; ?>>
						<tbody>
							<tr>
								<th scope="row">
									<label ><?php esc_html_e( 'Select App Method : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<ul class="app_facebooktoggle">
										<li>
											<input type="radio" id="woo_slg_auth_type_app_linkedin" class="linkedin_auth_type" name="woo_slg_auth_type_linkedin" value="app" <?php echo ($woo_slg_auth_type_linkedin=='app') ? 'checked="checked"' : ''; ?>/> 
											<label for="woo_slg_auth_type_app_linkedin">
												<div class="type_app_facebook">
													<div class="wooslg-custom-tip">
														<img src="<?php echo esc_url(WOO_SLG_IMG_URL).'/i.png'; ?>" class="i-img">
														<span><?php echo esc_html__( 'Select this option to use default app created by us.', 'wooslg' ); ?></span>
													</div>
													<div class="type_app_facebook-wrap">
														<img src="<?php echo esc_url(WOO_SLG_IMG_URL).'/Use-Custom-App.png'; ?>">
													</div>
													<h4><?php echo esc_html__( 'Use Default App', 'wooslg' ); ?></h4>
												</div>
											</label>
										</li>
										<li>
											<input type="radio" id="woo_slg_auth_type_graph_linkedin" class="linkedin_auth_type" name="woo_slg_auth_type_linkedin" value="graph" <?php echo ($woo_slg_auth_type_linkedin=='graph') ? 'checked="checked"' : ''; ?>/> 
											<label for="woo_slg_auth_type_graph_linkedin">
												<div class="type_app_facebook">
													<div class="wooslg-custom-tip">
														<img src="<?php echo esc_url(WOO_SLG_IMG_URL).'/i.png'; ?>" class="i-img">
														<span><?php echo esc_html__( 'Select this option to use custom app created by you.', 'wooslg' ); ?></span>
													</div>
													<div class="type_app_facebook-wrap">
														<img src="<?php echo esc_url(WOO_SLG_IMG_URL).'/Use-Default-App.png'; ?>">
													</div>
													<h4><?php echo esc_html__( 'Use Custom App', 'wooslg' ); ?></h4>
												</div>
											</label>
										</li>
									</ul>
								</td>
							</tr>

							<tr valign="top" class="linkedin_graph <?php esc_attr_e( $class_linkedin ) ?>">
								<td colspan="2">
								<div class="woo-slg-doc info"><ul><li><?php echo esc_html__( 'Before you can start using LinkedIn for the social login, you need to create a LinkedIn Application. You can get a step by step tutorial on how to create LinkedIn Application on our', 'wooslg' ) . ' <a target="_blank" href="' . esc_url('https://docs.wpwebelite.com/social-network-integration/linkedin/') . '">' . esc_html__( 'Documentation', 'wooslg' ) . '</a>'; ?></li></ul></div>
								</td>
							</tr>
							
							<?php
								//check WooCommerce is activated or not
								if( class_exists( 'Woocommerce' ) ) {
								
									$woo_slg_li_link_type = array(
										'signin' 		=> esc_html__('Sign In','wooslg'),
										'openid' 		=> esc_html__('OpenID','wooslg')
									);
								}
							?>

							<tr valign="top" class="linkedin_graph <?php esc_attr_e( $class_linkedin ) ?>">
								<th scope="row">
									<label for="woo_slg_li_app_id"><?php esc_html_e( 'Additional Product Access Option : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<select name="woo_slg_li_enable_type" id="woo_slg_li_enable_type" class="wslg-select" data-width="350px">
										<?php foreach ( $woo_slg_li_link_type as $key => $option ) { ?>
											<option value="<?php echo $key; ?>" <?php selected( $woo_slg_li_enable_type, $key ); ?>>
												<?php esc_html_e( $option ); ?>
											</option>
										<?php } ?>										
									</select>
									<p class="description"><?php echo esc_html__('Select OpenID Connect option for newly created APP. SignIn option is for backward compatibility for old APP users only.','wooslg'); ?></b></p>
								</td>
							</tr>

							<tr valign="top" class="linkedin_graph <?php esc_attr_e( $class_linkedin ) ?>">
								<th scope="row">
									<label for="woo_slg_li_app_id"><?php esc_html_e( 'Enter App ID/API Key : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_li_app_id" type="text" class="regular-text" name="woo_slg_li_app_id" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_li_app_id ); ?>" />
									<p class="description"><?php echo esc_html__( 'Enter LinkedIn App ID/API Key.', 'wooslg'); ?></p>
								</td>
							</tr>
							
							<tr valign="top" class="linkedin_graph <?php esc_attr_e( $class_linkedin ) ?>">
								<th scope="row">
									<label for="woo_slg_li_app_secret"><?php esc_html_e( 'Enter App Secret : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_li_app_secret" type="text" class="regular-text" name="woo_slg_li_app_secret" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_li_app_secret ); ?>" />
									<p class="description"><?php echo esc_html__( 'Enter LinkedIn App Secret.', 'wooslg'); ?></p>
								</td>
							</tr>

							<tr valign="top" class="linkedin_graph <?php esc_attr_e( $class_linkedin ) ?>">
								<th scope="row">
									<label for="woo_slg_li_redirect_url"><?php echo esc_html__( 'Callback URL : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<span class="description  redirect-url"><?php echo '<code>'. WOO_SLG_LI_REDIRECT_URL.'</code>'; ?></span>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="woo_slg_enable_li_avatar"><?php esc_html_e( 'Enable LinkedIn Avatar:', 'wooslg' ); ?></label>
								</th>
								<td>
								<div class="d-flex-wrap fb-avatra">
									<label for="woo_slg_enable_li_avatar" class="toggle-switch">
										<input type="checkbox" id="woo_slg_enable_li_avatar" name="woo_slg_enable_li_avatar" value="1" <?php echo ($woo_slg_enable_li_avatar=='yes') ? 'checked="checked"' : ''; ?>/>
										<span class="slider"></span>
									</label>
									<p><?php echo esc_html__( 'Check this box, if you want to use LinkedIn profile pictures as avatars.', 'wooslg' ); ?></p>
								</div>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_li_icon_text"><?php esc_html_e( 'Enter Custom Text : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_li_icon_text" type="text" class="regular-text woo_slg_social_btn_text" name="woo_slg_li_icon_text" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_li_icon_text ); ?>" />
									<p class="description"><?php echo esc_html__( 'If you want to use your own LinkedIn Text, Enter here.', 'wooslg' ); ?></p>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_li_link_icon_text"><?php esc_html_e( 'Enter Custom Link Text : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_li_link_icon_text" type="text" class="regular-text woo_slg_social_btn_text" name="woo_slg_li_link_icon_text" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_li_link_icon_text ); ?>" />
									<p class="description"><?php echo esc_html__( 'If you want to use your own LinkedIn Link Text, enter here.', 'wooslg' ); ?></p>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_li_icon_url"><?php esc_html_e( 'Add Custom Icon : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_li_icon_url" type="text" class="woo_slg_social_btn_image regular-text" name="woo_slg_li_icon_url" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_li_icon_url ); ?>" />
									<input type="button" class="woo-slg-upload-file-button button-secondary" value="<?php esc_html_e( 'Upload Image', 'wooslg' );?>"/>
									<p class="description"><?php echo esc_html__( 'If you want to use your own LinkedIn Icon, upload one here.', 'wooslg' ); ?></p>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_li_link_icon_url"><?php esc_html_e( 'Add Custom Link Icon : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_li_link_icon_url" type="text" class="woo_slg_social_btn_image regular-text" name="woo_slg_li_link_icon_url" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_li_link_icon_url ); ?>" />
									<input type="button" class="woo-slg-upload-file-button button-secondary" value="<?php esc_html_e( 'Upload Image', 'wooslg' );?>"/>
									<p class="description"><?php echo esc_html__( 'If you want to use your own LinkedIn Link Icon, upload one here.', 'wooslg' ); ?></p>
								</td>
							</tr>
							<!-- LinkedIn Settings End -->
						</tbody>
					</table>
					<table class="form-table">
						<tbody>
							<!-- Page Settings End -->

							<?php
								// do action for add setting after linkedin settings
								do_action( 'woo_slg_after_linkedin_setting', $woo_slg_options );
							?>
							
							<tr>
								<td colspan="2"><?php
									echo apply_filters ( 'woo_slg_settings_submit_button', '<input class="button-primary woo-slg-save-btn" type="submit" name="woo-slg-set-submit" value="'.esc_html__( 'Save Changes','wooslg' ).'" />' );?>
								</td>
							</tr>
						</tbody>
					</table>
				</div><!-- .inside -->
			</div><!-- #linkedin -->
		</div><!-- .meta-box-sortables ui-sortable -->
	</div><!-- .metabox-holder -->
</div><!-- #woo-slg-linkedin -->