<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Settings Page GitHub Tab
 * 
 * The code for the plugins settings page github tab
 * 
 * @package WooCommerce - Social Login
 * @since 1.6.4
 */

// Set option for default value
$woo_slg_github_icon_text = (!empty($woo_slg_github_icon_text)) ? $woo_slg_github_icon_text : esc_html__( 'Sign in with GitHub', 'wooslg' ) ;
$woo_slg_github_link_icon_text = (!empty($woo_slg_github_link_icon_text)) ? $woo_slg_github_link_icon_text : esc_html__( 'Link your account to GitHub', 'wooslg' ) ;
if( $woo_slg_auth_type_github == 'graph' ){ 
	$class_github = 'graph_show';
}else{
	$class_github = 'graph_hide';
}

$github_style = "";
if($woo_slg_enable_github == 'yes'){
	$github_style = 'style="display:block"';
}

?>
<!-- beginning of the github settings meta box -->
<div id="woo-slg-github" class="post-box-container">
	<div class="metabox-holder">
		<div class="meta-box-sortables ui-sortable">
			<div id="github" class="postbox">
				<div class="handlediv" title="<?php esc_html_e( 'Click to toggle', 'wooslg' ); ?>"><br /></div>
					
					<div class="woo-slg-social-icon-text-wrap">						
						<!-- github settings box title -->
						<h3 class="hndle">
							<img src="<?php echo esc_url(WOO_SLG_IMG_URL).'/tab-icon/github.svg'; ?>" alt="<?php esc_html_e('Github','wooslg');?>"/>
							<span class="woo-slg-vertical-top"><?php esc_html_e( 'GitHub Settings', 'wooslg' ); ?></span>
						</h3>
					</div>
					
					<div class="inside">

						<div class="woo-slg-doc info github_graph <?php esc_attr_e( $class_github ) ?>"><ul><li><?php echo esc_html__( 'Before you can start using GitHub for the social login, you need to create a GitHub Application. You can get a step by step tutorial on how to create GitHub Application on our', 'wooslg' ) . ' <a target="_blank" href="' . esc_url('https://docs.wpwebelite.com/social-network-integration/github/') . '">' . esc_html__( 'Documentation', 'wooslg' ) . '</a>'; ?></li></ul></div>

					<table class="form-table">
						<tbody>
							
							<?php
								// do action for add setting before github settings
								do_action( 'woo_slg_before_github_setting', $woo_slg_options );
							?>

							<tr>
								<th scope="row">
									<label for="woo_slg_enable_github"><?php esc_html_e( 'Enable Login : ', 'wooslg' ); ?></label>
								</th>
								<td>
								<div class="d-flex-wrap fb-avatra">
									<label for="woo_slg_enable_github" class="toggle-switch">
										<input type="checkbox" id="woo_slg_enable_github" name="woo_slg_enable_github" value="1" <?php echo ($woo_slg_enable_github == 'yes') ? 'checked="checked"' : ''; ?>/>
										<span class="slider"></span>
									</label>
									<p><?php echo esc_html__( 'Check this box, if you want to enable GitHub social login registration.', 'wooslg' ); ?></p>
								</div>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="form-table github_section_hide" <?php echo $github_style; ?>>
						<tbody>
							<tr>
								<th scope="row">
									<label ><?php esc_html_e( 'Select App Method : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<ul class="app_facebooktoggle">
										<li>
											<input type="radio" id="woo_slg_auth_type_app_github" class="github_auth_type" name="woo_slg_auth_type_github" value="app" <?php echo ($woo_slg_auth_type_github=='app') ? 'checked="checked"' : ''; ?>/> 
											<label for="woo_slg_auth_type_app_github">
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
											<input type="radio" id="woo_slg_auth_type_graph_github" class="github_auth_type" name="woo_slg_auth_type_github" value="graph" <?php echo ($woo_slg_auth_type_github=='graph') ? 'checked="checked"' : ''; ?>/> 
											<label for="woo_slg_auth_type_graph_github">
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

							<tr valign="top" class="github_graph <?php esc_attr_e( $class_github ) ?>">
								<td colspan="2">
								<div class="woo-slg-doc info"><ul><li><?php echo esc_html__( 'Before you can start using GitHub for the social login, you need to create a GitHub Application. You can get a step by step tutorial on how to create GitHub Application on our', 'wooslg' ) . ' <a target="_blank" href="' . esc_url('https://docs.wpwebelite.com/social-network-integration/github/') . '">' . esc_html__( 'Documentation', 'wooslg' ) . '</a>'; ?></li></ul></div>
								</td>
							</tr>

							<tr valign="top" class="github_graph <?php esc_attr_e( $class_github ) ?>">
								<th scope="row">
									<label for="woo_slg_github_client_id"><?php esc_html_e( 'Enter Client ID : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_github_client_id" type="text" class="regular-text" name="woo_slg_github_client_id" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_github_client_id ); ?>" />
									<p class="description"><?php echo esc_html__( 'Enter GitHub Client ID.', 'wooslg'); ?></p>
								</td>
							</tr>
							
							<tr valign="top" class="github_graph <?php esc_attr_e( $class_github ) ?>">
								<th scope="row">
									<label for="woo_slg_github_client_secret"><?php esc_html_e( 'Enter Client Secret : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_github_client_secret" type="text" class="regular-text" name="woo_slg_github_client_secret" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_github_client_secret ); ?>" />
									<p class="description"><?php echo esc_html__( 'Enter GitHub Client Secret.', 'wooslg'); ?></p>
								</td>
							</tr>

							<tr valign="top" class="github_graph <?php esc_attr_e( $class_github ) ?>">
								<th scope="row">
									<label for="woo_slg_github_redirect_url"><?php echo esc_html__( 'Callback URL : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<span class="description"><?php echo '<code>'. WOO_SLG_GITHUB_REDIRECT_URL.'</code>'; ?></span>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="woo_slg_enable_github_avatar"><?php esc_html_e( 'Enable GitHub Avatar:', 'wooslg' ); ?></label>
								</th>
								<td>
								<div class="d-flex-wrap fb-avatra">
									<label for="woo_slg_enable_github_avatar" class="toggle-switch">	
									<input type="checkbox" id="woo_slg_enable_github_avatar" name="woo_slg_enable_github_avatar" value="1" <?php echo ($woo_slg_enable_github_avatar == 'yes') ? 'checked="checked"' : ''; ?>/>
									<span class="slider"></span>
									
									</label>
									 <p><?php echo esc_html__( 'Check this box, if you want to use GitHub profile pictures as avatars.', 'wooslg' ); ?></p>
								</div>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_github_icon_text"><?php esc_html_e( 'Enter Custom Text : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_github_icon_text" type="text" class="regular-text woo_slg_social_btn_text" name="woo_slg_github_icon_text" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_github_icon_text ); ?>" />
									<p class="description"><?php echo esc_html__( 'If you want to use your own GitHub Text, Enter here.', 'wooslg' ); ?></p>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_github_link_icon_text"><?php esc_html_e( 'Enter Custom Link Text : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_github_link_icon_text" type="text" class="regular-text woo_slg_social_btn_text" name="woo_slg_github_link_icon_text" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_github_link_icon_text ); ?>" />
									<p class="description"><?php echo esc_html__( 'If you want to use your own GitHub Link Text, enter here.', 'wooslg' ); ?></p>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_github_icon_url"><?php esc_html_e( 'Add Custom Icon : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_github_icon_url" type="text" class="woo_slg_social_btn_image regular-text" name="woo_slg_github_icon_url" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_github_icon_url ); ?>" />
									<input type="button" class="woo-slg-upload-file-button button-secondary" value="<?php esc_html_e( 'Upload Image', 'wooslg' );?>"/>
									<p class="description"><?php echo esc_html__( 'If you want to use your own GitHub Icon, upload one here.', 'wooslg' ); ?></p>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_github_link_icon_url"><?php esc_html_e( 'Add Custom Link Icon : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_github_link_icon_url" type="text" class="woo_slg_social_btn_image regular-text" name="woo_slg_github_link_icon_url" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_github_link_icon_url ); ?>" />
									<input type="button" class="woo-slg-upload-file-button button-secondary" value="<?php esc_html_e( 'Upload Image', 'wooslg' );?>"/>
									<p class="description"><?php echo esc_html__( 'If you want to use your own GitHub Link Icon, upload one here.', 'wooslg' ); ?></p>
								</td>
							</tr>
							<!-- GitHub Settings End -->
						</tbody>
					</table>
					<table class="form-table">
						<tbody>
							<!-- Page Settings End -->

							<?php
								// do action for add setting after github settings
								do_action( 'woo_slg_after_github_setting', $woo_slg_options );
							?>
							
							<tr>
								<td colspan="2"><?php
									echo apply_filters ( 'woo_slg_settings_submit_button', '<input class="button-primary woo-slg-save-btn" type="submit" name="woo-slg-set-submit" value="'.esc_html__( 'Save Changes','wooslg' ).'" />' );?>
								</td>
							</tr>
						</tbody>
					</table>
				</div><!-- .inside -->
			</div><!-- #github -->
		</div><!-- .meta-box-sortables ui-sortable -->
	</div><!-- .metabox-holder -->
</div><!-- #woo-slg-github -->