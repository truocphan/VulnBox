<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Settings Page Google+ Tab
 * 
 * The code for the plugins settings page google plus tab
 * 
 * @package WooCommerce - Social Login
 * @since 1.6.4
 */

// Set option for default value
$woo_slg_gp_icon_text = (!empty($woo_slg_gp_icon_text)) ? $woo_slg_gp_icon_text : esc_html__( 'Sign in with Google', 'wooslg' ) ;
$woo_slg_gp_link_icon_text = (!empty($woo_slg_gp_link_icon_text)) ? $woo_slg_gp_link_icon_text : esc_html__( 'Link your account to Google', 'wooslg' ) ;
if( $woo_slg_auth_type_google == 'graph' ){ 
	$class_google = 'graph_show';
}else{
	$class_google = 'graph_hide';
}

$googleplus_style = "";
if($woo_slg_enable_googleplus == 'yes'){
	$googleplus_style = 'style="display:block"';
}

?>

<!-- beginning of the google_plus settings meta box -->
<div id="woo-slg-google-plus" class="post-box-container">
	<div class="metabox-holder">
		<div class="meta-box-sortables ui-sortable">
			<div id="google_plus" class="postbox">
				<div class="handlediv" title="<?php esc_html_e( 'Click to toggle', 'wooslg' ); ?>"><br /></div>
					
					<div class="woo-slg-social-icon-text-wrap">						
						<!-- google_plus settings box title -->
						<h3 class="hndle">
							<img src="<?php echo esc_url(WOO_SLG_IMG_URL).'/tab-icon/google.svg'; ?>" alt="<?php esc_html_e('Google','wooslg');?>"/>
							<span class="woo-slg-vertical-top"><?php esc_html_e( 'Google Settings', 'wooslg' ); ?></span>
						</h3>
					</div>

					<div class="inside">
					
					<table class="form-table">
						<tbody>
							
							<?php
								// do action for add setting before google plus settings
								do_action( 'woo_slg_before_google_plus_setting', $woo_slg_options );
							?>
							
							<tr>
								<th scope="row">
									<label for="woo_slg_enable_googleplus"><?php esc_html_e( 'Enable Login : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<div class="d-flex-wrap fb-avatra">
										<label for="woo_slg_enable_googleplus" class="toggle-switch">
											<input type="checkbox" id="woo_slg_enable_googleplus" name="woo_slg_enable_googleplus" value="1" <?php echo ($woo_slg_enable_googleplus=='yes') ? 'checked="checked"' : ''; ?>/>
											<span class="slider"></span>
										</label>
										<p><?php echo esc_html__( 'Check this box, if you want to enable google social login registration.', 'wooslg' ); ?></p>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="form-table googleplus_section_hide" <?php echo $googleplus_style; ?>>
						<tbody>
							<tr>
								<th scope="row">
									<label ><?php esc_html_e( 'Select App Method : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<ul class="app_facebooktoggle">
										<li>
											<input type="radio" id="woo_slg_auth_type_app_google" class="google_auth_type" name="woo_slg_auth_type_google" value="app" <?php echo ($woo_slg_auth_type_google=='app') ? 'checked="checked"' : ''; ?>/> 
											<label for="woo_slg_auth_type_app_google">
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
											<input type="radio" id="woo_slg_auth_type_graph_google" class="google_auth_type" name="woo_slg_auth_type_google" value="graph" <?php echo ($woo_slg_auth_type_google=='graph') ? 'checked="checked"' : ''; ?>/> 
											<label for="woo_slg_auth_type_graph_google">
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
							
							<tr valign="top" class="google_graph <?php esc_attr_e( $class_google ) ?>">
								<td colspan="2">
								<div class="woo-slg-doc info"><ul><li><?php echo esc_html__( 'Before you can start using Google for the social login, you need to create a Google Application. You can get a step by step tutorial on how to create Google Application on our', 'wooslg' ) . ' <a target="_blank" href="'. esc_url('https://docs.wpwebelite.com/social-network-integration/google/').'">' . esc_html__( 'Documentation', 'wooslg' ) . '</a>'; ?></li></ul></div>
								</td>
							</tr>

							<tr valign="top" class="google_graph <?php esc_attr_e( $class_google ) ?>">
								<th scope="row">
									<label for="woo_slg_gp_client_id"><?php esc_html_e( 'Enter Client ID : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_gp_client_id" type="text" class="regular-text" name="woo_slg_gp_client_id" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_gp_client_id ); ?>" />
									<p class="description"><?php echo esc_html__( 'Enter Google Client ID.', 'wooslg'); ?></p>
								</td>
							</tr>
							
							<tr valign="top" class="google_graph <?php esc_attr_e( $class_google ) ?>">
								<th scope="row">
									<label for="woo_slg_gp_redirect_url"><?php echo esc_html__( 'Google JavaScript origins URL : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<span class="description"><?php echo '<code>'.WOO_SLG_GP_REDIRECT_URL.'</code>'; ?></span>
								</td>
							</tr>
							
							<tr class="google_graph <?php esc_attr_e( $class_google ) ?>">
								<td colspan="4">
									<p class="woo-slg-error-box"><?php echo sprintf(esc_html__( 'Note: Before using the Google social login, update the %s Authorized JavaScript origins %s in Google App setting, as mentioned in the document', 'wooslg' ), "<strong>", "</strong>"); ?> <a href="<?php echo esc_url('https://docs.wpwebelite.com/social-network-integration/google/#google-javascript-origins'); ?>" target="_blank"><?php echo esc_html__( 'here', 'wooslg' ); ?></a>.</p>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="woo_slg_enable_gp_avatar"><?php esc_html_e( 'Enable Google Avatar : ', 'wooslg' ); ?></label>
								</th>
								<td>
								<div class="d-flex-wrap fb-avatra">
								<label for="woo_slg_enable_gp_avatar" class="toggle-switch">
									<input type="checkbox" id="woo_slg_enable_gp_avatar" name="woo_slg_enable_gp_avatar" value="1" <?php echo ($woo_slg_enable_gp_avatar=='yes') ? 'checked="checked"' : ''; ?>/>
									<span class="slider"></span>
								</label>
								<p><?php echo esc_html__( 'Check this box, if you want to use Google profile pictures as avatars.', 'wooslg' ); ?></p>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_gp_icon_text"><?php esc_html_e( 'Add Custom Text : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_gp_icon_text" type="text" class="regular-text woo_slg_social_btn_text" name="woo_slg_gp_icon_text" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_gp_icon_text ); ?>" />
									<p class="description"><?php echo esc_html__( 'If you want to use your own Google Text, Enter here.', 'wooslg' ); ?></p>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_gp_link_icon_text"><?php esc_html_e( 'Add Custom Link Text : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_gp_link_icon_text" type="text" class="regular-text woo_slg_social_btn_text" name="woo_slg_gp_link_icon_text" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_gp_link_icon_text ); ?>" />
									<p class="description"><?php echo esc_html__( 'If you want to use your own Google Link Text, enter here.', 'wooslg' ); ?></p>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_gp_icon_url"><?php esc_html_e( 'Add Custom Icon : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_gp_icon_url" type="text" class="woo_slg_social_btn_image regular-text" name="woo_slg_gp_icon_url" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_gp_icon_url ); ?>" />
									<input type="button" class="woo-slg-upload-file-button button-secondary" value="<?php esc_html_e( 'Upload Image', 'wooslg' );?>"/>
									<p class="description"><?php echo esc_html__( 'If you want to use your own Google Icon, upload one here.', 'wooslg' ); ?></p>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_gp_link_icon_url"><?php esc_html_e( 'Add Custom Link Icon : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_gp_link_icon_url" type="text" class="woo_slg_social_btn_image regular-text" name="woo_slg_gp_link_icon_url" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_gp_link_icon_url ); ?>" />
									<input type="button" class="woo-slg-upload-file-button button-secondary" value="<?php esc_html_e( 'Upload Image', 'wooslg' );?>"/>
									<p class="description"><?php echo esc_html__( 'If you want to use your own Google Link Icon, upload one here.', 'wooslg' ); ?></p>
								</td>
							</tr>
							<!-- Google+ Settings End -->
						</tbody>
					</table>
					<table class="form-table">
						<tbody>
							<!-- Page Settings End -->

							<?php
								// do action for add setting after google plus settings
								do_action( 'woo_slg_after_google_plus_setting', $woo_slg_options );
							?>
							
							<tr>
								<td colspan="2"><?php
									echo apply_filters ( 'woo_slg_settings_submit_button', '<input class="button-primary woo-slg-save-btn" type="submit" name="woo-slg-set-submit" value="'.esc_html__( 'Save Changes','wooslg' ).'" />' );?>
								</td>
							</tr>
						</tbody>
					</table>
				</div><!-- .inside -->
			</div><!-- #google_plus -->
		</div><!-- .meta-box-sortables ui-sortable -->
	</div><!-- .metabox-holder -->
</div><!-- #woo-slg-google-plus -->