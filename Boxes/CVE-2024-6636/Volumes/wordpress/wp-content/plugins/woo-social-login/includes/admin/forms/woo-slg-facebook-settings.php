<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Settings Page Facebook Tab
 * 
 * The code for the plugins settings page facebook tab
 * 
 * @package WooCommerce - Social Login
 * @since 1.6.4
 */

// Assign Some Variable
$select_fblanguage = array( 
	'en_US' => esc_html__( 'English', 'wooslg' ),
	'af_ZA' => esc_html__( 'Afrikaans', 'wooslg' ),
	'sq_AL' => esc_html__( 'Albanian', 'wooslg' ),
	'ar_AR' => esc_html__( 'Arabic', 'wooslg' ),
	'hy_AM' => esc_html__( 'Armenian', 'wooslg' ),
	'eu_ES' => esc_html__( 'Basque', 'wooslg' ),
	'be_BY' => esc_html__( 'Belarusian', 'wooslg' ),
	'bn_IN' => esc_html__( 'Bengali', 'wooslg' ),
	'bs_BA' => esc_html__( 'Bosanski', 'wooslg' ),
	'bg_BG' => esc_html__( 'Bulgarian', 'wooslg' ),
	'ca_ES' => esc_html__( 'Catalan', 'wooslg' ),
	'zh_CN' => esc_html__( 'Chinese', 'wooslg' ),
	'cs_CZ' => esc_html__( 'Czech', 'wooslg' ),
	'da_DK' => esc_html__( 'Danish', 'wooslg' ),
	'fy_NL' => esc_html__( 'Dutch', 'wooslg' ),
	'eo_EO' => esc_html__( 'Esperanto', 'wooslg' ),
	'et_EE' => esc_html__( 'Estonian', 'wooslg' ),
	'et_EE' => esc_html__( 'Estonian', 'wooslg' ),
	'fi_FI' => esc_html__( 'Finnish', 'wooslg' ),
	'fo_FO' => esc_html__( 'Faroese', 'wooslg' ),
	'tl_PH' => esc_html__( 'Filipino', 'wooslg' ),
	'fr_FR' => esc_html__( 'French', 'wooslg' ),
	'gl_ES' => esc_html__( 'Galician', 'wooslg' ),
	'ka_GE' => esc_html__( 'Georgian', 'wooslg' ),
	'de_DE' => esc_html__( 'German', 'wooslg' ),
	'el_GR' => esc_html__( 'Greek', 'wooslg' ),
	'he_IL' => esc_html__( 'Hebrew', 'wooslg' ),
	'hi_IN' => esc_html__( 'Hindi', 'wooslg' ),
	'hr_HR' => esc_html__( 'Hrvatski', 'wooslg' ),
	'hu_HU' => esc_html__( 'Hungarian', 'wooslg' ),
	'is_IS' => esc_html__( 'Icelandic', 'wooslg' ),
	'id_ID' => esc_html__( 'Indonesian', 'wooslg' ),
	'ga_IE' => esc_html__( 'Irish', 'wooslg' ),
	'it_IT' => esc_html__( 'Italian', 'wooslg' ),
	'ja_JP' => esc_html__( 'Japanese', 'wooslg' ),
	'ko_KR' => esc_html__( 'Korean', 'wooslg' ),
	'ku_TR' => esc_html__( 'Kurdish', 'wooslg' ),
	'la_VA' => esc_html__( 'Latin', 'wooslg' ),
	'lv_LV' => esc_html__( 'Latvian', 'wooslg' ),
	'fb_LT' => esc_html__( 'Leet Speak', 'wooslg' ),
	'lt_LT' => esc_html__( 'Lithuanian', 'wooslg' ),
	'mk_MK' => esc_html__( 'Macedonian', 'wooslg' ),
	'ms_MY' => esc_html__( 'Malay', 'wooslg' ),
	'ml_IN' => esc_html__( 'Malayalam', 'wooslg' ),
	'nl_NL' => esc_html__( 'Nederlands', 'wooslg' ),
	'ne_NP' => esc_html__( 'Nepali', 'wooslg' ),
	'nb_NO' => esc_html__( 'Norwegian', 'wooslg' ),
	'ps_AF' => esc_html__( 'Pashto', 'wooslg' ),
	'fa_IR' => esc_html__( 'Persian', 'wooslg' ),
	'pl_PL' => esc_html__( 'Polish', 'wooslg' ),
	'pt_PT' => esc_html__( 'Portugese', 'wooslg' ),
	'pt_BR' => esc_html__( 'Portuguese (Brazil)', 'wooslg' ),
	'pa_IN' => esc_html__( 'Punjabi', 'wooslg' ),
	'ro_RO' => esc_html__( 'Romanian', 'wooslg' ),
	'ru_RU' => esc_html__( 'Russian', 'wooslg' ),
	'sk_SK' => esc_html__( 'Slovak', 'wooslg' ),
	'sl_SI' => esc_html__( 'Slovenian', 'wooslg' ),
	'es_LA' => esc_html__( 'Spanish', 'wooslg' ),
	'sr_RS' => esc_html__( 'Srpski', 'wooslg' ),
	'sw_KE' => esc_html__( 'Swahili', 'wooslg' ),
	'sv_SE' => esc_html__( 'Swedish', 'wooslg' ),
	'ta_IN' => esc_html__( 'Tamil', 'wooslg' ),
	'te_IN' => esc_html__( 'Telugu', 'wooslg' ),
	'th_TH' => esc_html__( 'Thai', 'wooslg' ),
	'tr_TR' => esc_html__( 'Turkish', 'wooslg' ),
	'uk_UA' => esc_html__( 'Ukrainian', 'wooslg' ),
	'vi_VN' => esc_html__( 'Vietnamese', 'wooslg' ),
	'cy_GB' => esc_html__( 'Welsh', 'wooslg' ),
	'zh_TW' => esc_html__( 'Traditional Chinese Language', 'wooslg' )  );

$woo_slg_fb_icon_text = (!empty($woo_slg_fb_icon_text)) ? $woo_slg_fb_icon_text : esc_html__( 'Sign in with Facebook', 'wooslg' ) ;
$woo_slg_fb_link_icon_text = (!empty($woo_slg_fb_link_icon_text)) ? $woo_slg_fb_link_icon_text : esc_html__( 'Link your account to Facebook', 'wooslg' ) ;
if( $woo_slg_auth_type_facebook == 'graph' ){ 
	$class_fb = 'graph_show';
}else{
	$class_fb = 'graph_hide';
}

$facebook_style = "";
if($woo_slg_enable_facebook == 'yes'){
	$facebook_style = 'style="display:block"';
}
?>

<!-- beginning of the facebook settings meta box -->
<div id="woo-slg-facebook" class="post-box-container">
	<div class="metabox-holder">
		<div class="meta-box-sortables ui-sortable">
			<div id="facebook" class="postbox">
				<div class="handlediv" title="<?php esc_html_e( 'Click to toggle', 'wooslg' ); ?>"><br /></div>
					
					<div class="woo-slg-social-icon-text-wrap">
									
						<!-- facebook settings box title -->
						<h3 class="hndle">
							<img src="<?php echo esc_url(WOO_SLG_IMG_URL).'/tab-icon/facebook.svg'; ?>" alt="<?php esc_html_e('Facebook','wooslg');?>"/>		
							<span class="woo-slg-vertical-top"><?php esc_html_e( 'Facebook Settings', 'wooslg' ); ?></span>
						</h3>
					</div>

					<div class="inside">
					
					<table class="form-table">
						<tbody>
						
							<?php
								// do action for add setting before facebook settings
								do_action( 'woo_slg_before_facebook_setting', $woo_slg_options );	
							?>

							<tr>
								<th scope="row">
									<label for="woo_slg_enable_facebook"><?php esc_html_e( 'Enable Login : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<div class="d-flex-wrap fb-avatra">
									<label class="toggle-switch" for="woo_slg_enable_facebook">
									<input type="checkbox" id="woo_slg_enable_facebook" name="woo_slg_enable_facebook" value="1" <?php echo ($woo_slg_enable_facebook=='yes') ? 'checked="checked"' : ''; ?>/>
										<span class="slider"></span>
										
									</label>
									<p><?php echo esc_html__( 'Check this box, if you want to enable facebook social login registration.', 'wooslg' ); ?></p>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="form-table facebook_section_hide" <?php echo $facebook_style; ?>>
						<tbody>
							<tr>
								<th scope="row">
									<label ><?php esc_html_e( 'Select App Method : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<ul class="app_facebooktoggle">
									<li>
										<input type="radio" id="woo_slg_auth_type_app_facebook" class="fb_auth_type" name="woo_slg_auth_type_facebook" value="app" <?php echo ($woo_slg_auth_type_facebook=='app') ? 'checked="checked"' : ''; ?>/> 
										<label for="woo_slg_auth_type_app_facebook">
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
										<input type="radio" id="woo_slg_auth_type_graph_facebook" class="fb_auth_type" name="woo_slg_auth_type_facebook" value="graph" <?php echo ($woo_slg_auth_type_facebook=='graph') ? 'checked="checked"' : ''; ?>/> 
										<label for="woo_slg_auth_type_graph_facebook">
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

							<tr valign="top" class="fb_graph <?php esc_attr_e( $class_fb ) ?>">
								<td colspan="2">
								<div class="woo-slg-doc info"><ul><li><?php echo esc_html__( 'Before you can start using Facebook for the social login, you need to create a Facebook Application. You can get a step by step tutorial on how to create Facebook Application on our', 'wooslg' ) . ' <a target="_blank" href="' . esc_url('https://docs.wpwebelite.com/social-network-integration/facebook/') . '">'. esc_html__( 'Documentation', 'wooslg' ). '</a>'; ?></li></ul></div>
								</td>
							</tr>
							
							<tr valign="top" class="fb_graph <?php esc_attr_e( $class_fb ) ?>">
								<th scope="row">
									<label for="woo_slg_fb_app_id"><?php esc_html_e( 'Enter App ID/API Key : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_fb_app_id" type="text" class="regular-text" name="woo_slg_fb_app_id" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_fb_app_id ); ?>" />
									<p class="description"><?php echo esc_html__( 'Enter Facebook API Key.', 'wooslg'); ?></p>
								</td>
							</tr>
							
							<tr valign="top" class="fb_graph <?php esc_html_e( $class_fb ) ?>">
								<th scope="row">
									<label for="woo_slg_fb_app_secret"><?php esc_html_e( 'Enter App Secret : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_fb_app_secret" type="text" class="regular-text" name="woo_slg_fb_app_secret" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_fb_app_secret ); ?>" />
									<p class="description"><?php echo esc_html__( 'Enter Facebook App Secret.', 'wooslg'); ?></p>
								</td>
							</tr>

							<tr valign="top" class="fb_graph <?php esc_html_e( $class_fb ) ?>">
								<th scope="row">
									<label><?php echo esc_html__( 'Valid OAuth Redirect URL : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<div class="description redirect-url"><?php echo '<code>'.WOO_SLG_FB_REDIRECT_URL.'</code>'; ?></div>
								</td>
							</tr>

							<tr valign="top" class="fb_graph <?php esc_html_e( $class_fb ) ?>">
								<th scope="row">
									<label for="woo_slg_fb_language"><?php echo esc_html__( 'API Locale : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<select name="woo_slg_fb_language" id="woo_slg_fb_language" class="wslg-select" data-width="350px">
										<?php foreach ( $select_fblanguage as $key => $option ) { ?>
											<option value="<?php echo $key; ?>" <?php selected( $woo_slg_fb_language, $key ); ?>>
												<?php esc_html_e( $option ); ?>
											</option>
										<?php } ?>
									</select>
									<p class="description"><?php esc_html_e(  'Select the language for Facebook. With this option, you can explicitly tell which language you want to use for communicating with Facebook.', 'wooslg' ); ?></p>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="woo_slg_enable_fb_avatar"><?php esc_html_e( 'Enable Avatar : ', 'wooslg' ); ?></label>
								</th>
								<td>	 
								<div class="d-flex-wrap fb-avatra">
									<label class="toggle-switch"  for="woo_slg_enable_fb_avatar">
									<input type="checkbox" id="woo_slg_enable_fb_avatar" name="woo_slg_enable_fb_avatar" value="1" <?php echo ($woo_slg_enable_fb_avatar=='yes') ? 'checked="checked"' : ''; ?>/>
										<span class="slider"></span>
										
									</label>
									<p><?php echo esc_html__( 'Check this box, if you want to use Facebook profile pictures as avatars.', 'wooslg' ); ?></p>
								</div>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_fb_icon_url"><?php esc_html_e( 'Add Custom Icon : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_fb_icon_url" type="text" class="woo_slg_social_btn_image regular-text" name="woo_slg_fb_icon_url" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_fb_icon_url ); ?>" />
									<input type="button" class="woo-slg-upload-file-button button-secondary" value="<?php esc_html_e( 'Upload Image', 'wooslg' );?>"/>
									<p class="description"><?php echo esc_html__( 'If you want to use your own Facebook Icon, upload one here.', 'wooslg' ); ?></p>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_fb_link_icon_url"><?php esc_html_e( 'Add Custom Link Icon : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_fb_link_icon_url" type="text" class="woo_slg_social_btn_image regular-text" name="woo_slg_fb_link_icon_url" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_fb_link_icon_url ); ?>" />
									<input type="button" class="woo-slg-upload-file-button button-secondary" value="<?php esc_html_e( 'Upload Image', 'wooslg' );?>"/>
									<p class="description"><?php echo esc_html__( 'If you want to use your own Facebook Link Icon, upload one here.', 'wooslg' ); ?></p>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_fb_icon_text"><?php esc_html_e( 'Add Custom Text : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_fb_icon_text" type="text" class="regular-text woo_slg_social_btn_text" name="woo_slg_fb_icon_text" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_fb_icon_text ); ?>" />
									<p class="description"><?php echo esc_html__( 'If you want to use your own Facebook Text, Enter here.', 'wooslg' ); ?></p>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_fb_link_icon_text"><?php esc_html_e( 'Custom Link Text : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_fb_link_icon_text" type="text" class="regular-text woo_slg_social_btn_text" name="woo_slg_fb_link_icon_text" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_fb_link_icon_text ); ?>" />
									<p class="description"><?php echo esc_html__( 'If you want to use your own Facebook Link Text, enter here.', 'wooslg' ); ?></p>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="form-table">
						<tbody>
							<!-- Facebook Settings End -->
							<!-- Page Settings End -->
							<?php
								// do action for add setting after facebook settings
								do_action( 'woo_slg_after_facebook_setting', $woo_slg_options );
							?>
							<tr>
								<td colspan="2"><?php
									echo apply_filters ( 'woo_slg_settings_submit_button', '<input class="button-primary woo-slg-save-btn" type="submit" name="woo-slg-set-submit" value="'.esc_html__( 'Save Changes','wooslg' ).'" />' );?>
								</td>
							</tr>
						</tbody>
					</table>
				</div><!-- .inside -->
			</div><!-- #facebook -->
		</div><!-- .meta-box-sortables ui-sortable -->
	</div><!-- .metabox-holder -->
</div><!-- #woo-slg-facebook -->