<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Settings Page Twitter Tab
 * 
 * The code for the plugins settings page twitter tab
 * 
 * @package WooCommerce - Social Login
 * @since 1.6.4
 */

// Get twitter options 
$woo_slg_twitter_options = array(
	'woo_slg_enable_twitter',
	'woo_slg_tw_consumer_key',
	'woo_slg_tw_consumer_secret',
	'woo_slg_enable_tw_avatar',
	'woo_slg_tw_icon_text',
	'woo_slg_tw_link_icon_text',
	'woo_slg_tw_icon_url',
	'woo_slg_tw_link_icon_url'
);

// Get option value
foreach ($woo_slg_twitter_options as $woo_slg_option_key) {
	$$woo_slg_option_key = get_option( $woo_slg_option_key );
}

// Set option for default value
$woo_slg_tw_icon_text = (!empty($woo_slg_tw_icon_text)) ? $woo_slg_tw_icon_text : esc_html__( 'Sign in with Twitter', 'wooslg' ) ;
$woo_slg_tw_link_icon_text = (!empty($woo_slg_tw_link_icon_text)) ? $woo_slg_tw_link_icon_text : esc_html__( 'Link your account to Twitter', 'wooslg' ) ;
if( $woo_slg_auth_type_twitter == 'graph' ){ 
	$class_tw = 'graph_show';
}else{
	$class_tw = 'graph_hide';
}

$twitter_style = "";
if($woo_slg_enable_twitter == 'yes'){
	$twitter_style = 'style="display:block"';
}

?>

<!-- beginning of the twitter settings meta box -->
<div id="woo-slg-twitter" class="post-box-container">
	<div class="metabox-holder">
		<div class="meta-box-sortables ui-sortable">
			<div id="twitter" class="postbox">
				<div class="handlediv" title="<?php esc_html_e( 'Click to toggle', 'wooslg' ); ?>"><br /></div>
					
					<div class="woo-slg-social-icon-text-wrap">						
						<!-- twitter settings box title -->
						<h3 class="hndle">
							<img src="<?php echo esc_url(WOO_SLG_IMG_URL).'/tab-icon/twitter.svg'; ?>" alt="<?php esc_html_e('Twitter','wooslg');?>"/>
							<span class="woo-slg-vertical-top"><?php esc_html_e( 'Twitter Settings', 'wooslg' ); ?></span>
						</h3>
					</div>
					
					<div class="inside">

					<table class="form-table">
						<tbody>
							
							<?php
								// do action for add setting before twitter settings
								do_action( 'woo_slg_before_twitter_setting' );
							?>
							
							<tr>
								<th scope="row">
									<label for="woo_slg_enable_twitter"><?php esc_html_e( 'Enable Login : ', 'wooslg' ); ?></label>
								</th>
								<td>
								<div class="d-flex-wrap fb-avatra">
									<label for="woo_slg_enable_twitter" class="toggle-switch">
										<input type="checkbox" id="woo_slg_enable_twitter" name="woo_slg_enable_twitter" value="1" <?php echo ($woo_slg_enable_twitter=='yes') ? 'checked="checked"' : ''; ?>/>
										<span class="slider"></span>
									</label>
									<p><?php echo esc_html__( 'Check this box, if you want to enable Twitter social login registration.', 'wooslg' ); ?></p>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="form-table twitter_section_hide" <?php echo $twitter_style; ?>>
						<tbody>
							<tr>
								<th scope="row">
									<label ><?php esc_html_e( 'Select App Method : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<ul class="app_facebooktoggle">
										<li>
											<input type="radio" id="woo_slg_auth_type_app_twitter" class="twitter_auth_type" name="woo_slg_auth_type_twitter" value="app" <?php echo ($woo_slg_auth_type_twitter=='app') ? 'checked="checked"' : ''; ?>/> 
											<label for="woo_slg_auth_type_app_twitter">
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
											<input type="radio" id="woo_slg_auth_type_graph_twitter" class="twitter_auth_type" name="woo_slg_auth_type_twitter" value="graph" <?php echo ($woo_slg_auth_type_twitter=='graph') ? 'checked="checked"' : ''; ?>/> 
											<label for="woo_slg_auth_type_graph_twitter">
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

							<tr valign="top" class="twitter_graph <?php esc_attr_e( $class_tw ) ?>">
								<td colspan="2">
								<div class="woo-slg-doc info"><ul><li><?php echo esc_html__( 'Before you can start using Twitter for the social login, you need to create a Twitter Application. You can get a step by step tutorial on how to create Twitter Application on our', 'wooslg' ) . ' <a target="_blank" href="' . esc_url('https://docs.wpwebelite.com/social-network-integration/twitter/') . '">' . esc_html__( 'Documentation', 'wooslg' ) . '</a>'; ?></li></ul></div>
								</td>
							</tr>

							<tr valign="top" class="twitter_graph <?php esc_attr_e( $class_tw ) ?>">
								<th scope="row">
									<label for="woo_slg_tw_consumer_key"><?php esc_html_e( 'Enter API Key : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_tw_consumer_key" type="text" class="regular-text" name="woo_slg_tw_consumer_key" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_tw_consumer_key ); ?>" />
									<p class="description"><?php echo esc_html__( 'Enter Twitter API Key.', 'wooslg'); ?></p>
								</td>
							</tr>
							
							<tr valign="top" class="twitter_graph <?php esc_attr_e( $class_tw ) ?>">
								<th scope="row">
									<label for="woo_slg_tw_consumer_secret"><?php esc_html_e( 'Enter API Secret : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_tw_consumer_secret" type="text" class="regular-text" name="woo_slg_tw_consumer_secret" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_tw_consumer_secret ); ?>" />
									<p class="description"><?php echo esc_html__( 'Enter Twitter API Secret.', 'wooslg'); ?></p>
								</td>
							</tr>

							<tr valign="top" class="twitter_graph <?php esc_attr_e( $class_tw ) ?>">
								<th scope="row">
									<label for="woo_slg_tw_redirect_url"><?php echo esc_html__( 'Callback URL : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<p class="description"><?php echo '<code>'.site_url().'</code>'; ?></p>
									<p class="description"><?php echo sprintf(esc_html__( 'Add your site url without %s slash(/) %s at the end of url.', 'wooslg' ), '<b>', '</b>'); ?></p>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="woo_slg_enable_tw_avatar"><?php esc_html_e( 'Enable Twitter Avatar : ', 'wooslg' ); ?></label>
								</th>
								<td>
								<div class="d-flex-wrap fb-avatra">
									<label for="woo_slg_enable_tw_avatar" class="toggle-switch">
										<input type="checkbox" id="woo_slg_enable_tw_avatar" name="woo_slg_enable_tw_avatar" value="1" <?php echo ($woo_slg_enable_tw_avatar=='yes') ? 'checked="checked"' : ''; ?>/>
										<span class="slider"></span>
									</label>
									<p><?php echo esc_html__( 'Check this box, if you want to use Twitter profile pictures as avatars.', 'wooslg' ); ?></p>
								</div>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_tw_icon_text"><?php esc_html_e( 'Enter Custom Text:', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_tw_icon_text" type="text" class="regular-text woo_slg_social_btn_text" name="woo_slg_tw_icon_text" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_tw_icon_text ); ?>" />
									<p class="description"><?php echo esc_html__( 'If you want to use your own Twitter Text, Enter here.', 'wooslg' ); ?></p>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_tw_link_icon_text"><?php esc_html_e( 'Enter Custom Link Text:', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_tw_link_icon_text" type="text" class="regular-text woo_slg_social_btn_text" name="woo_slg_tw_link_icon_text" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_tw_link_icon_text ); ?>" />
									<p class="description"><?php echo esc_html__( 'If you want to use your own Twitter Link Text, enter here.', 'wooslg' ); ?></p>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_tw_icon_url"><?php esc_html_e( 'Add Custom Icon : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_tw_icon_url" type="text" class="woo_slg_social_btn_image regular-text" name="woo_slg_tw_icon_url" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_tw_icon_url ); ?>" />
									<input type="button" class="woo-slg-upload-file-button button-secondary" value="<?php esc_html_e( 'Upload Image', 'wooslg' );?>"/>
									<p class="description"><?php echo esc_html__( 'If you want to use your own Twitter Icon, upload one here.', 'wooslg' ); ?></p>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_tw_link_icon_url"><?php esc_html_e( 'Add Custom Link Icon : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_tw_link_icon_url" type="text" class="woo_slg_social_btn_image regular-text" name="woo_slg_tw_link_icon_url" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_tw_link_icon_url ); ?>" />
									<input type="button" class="woo-slg-upload-file-button button-secondary" value="<?php esc_html_e( 'Upload Image', 'wooslg' );?>"/>
									<p class="description"><?php echo esc_html__( 'If you want to use your own Twitter Link Icon, upload one here.', 'wooslg' ); ?></p>
								</td>
							</tr>

							<!-- Twitter Settings End -->
						</tbody>
					</table>
					<table class="form-table">
						<tbody>
							<!-- Page Settings End --><?php
							
							// do action for add setting after twitter settings
							do_action( 'woo_slg_after_twitter_setting' );
							
							?>
							<tr>
								<td colspan="2"><?php
									echo apply_filters ( 'woo_slg_settings_submit_button', '<input class="button-primary woo-slg-save-btn" type="submit" name="woo-slg-set-submit" value="'.esc_html__( 'Save Changes','wooslg' ).'" />' );?>
								</td>
							</tr>
						</tbody>
					</table>
				</div><!-- .inside -->
			</div><!-- #twitter -->
		</div><!-- .meta-box-sortables ui-sortable -->
	</div><!-- .metabox-holder -->
</div><!-- #woo-slg-twitter -->