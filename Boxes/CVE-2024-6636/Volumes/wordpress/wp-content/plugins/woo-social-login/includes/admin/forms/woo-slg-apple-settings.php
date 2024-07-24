<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Settings Page Apple Tab
 * 
 * The code for the plugins settings page apple tab
 * 
 * @package WooCommerce - Social Login
 * @since 1.6.4
 */

// Assign Some variable
$woo_slg_apple_icon_text = (!empty($woo_slg_apple_icon_text)) ? $woo_slg_apple_icon_text : esc_html__( 'Sign in with Apple', 'wooslg' ) ;
$woo_slg_apple_link_icon_text = (!empty($woo_slg_apple_link_icon_text)) ? $woo_slg_apple_link_icon_text : esc_html__( 'Link your account to Apple', 'wooslg' ) ;
$apple_style = "";
if($woo_slg_enable_apple == 'yes'){
	$apple_style = 'style="display:block"';
}

?>
<!-- beginning of the apple settings meta box -->
<div id="woo-slg-apple" class="post-box-container">
	<div class="metabox-holder">
		<div class="meta-box-sortables ui-sortable">
			<div id="apple" class="postbox">
				<div class="handlediv" title="<?php esc_html_e( 'Click to toggle', 'wooslg' ); ?>"><br /></div>

				<div class="woo-slg-social-icon-text-wrap">					
					<!-- apple settings box title -->
					<h3 class="hndle">
						<img src="<?php echo esc_url(WOO_SLG_IMG_URL).'/tab-icon/apple.svg'; ?>" alt="<?php esc_html_e('Apple','wooslg');?>"/>
						<span class="woo-slg-vertical-top"><?php esc_html_e( 'Apple Settings', 'wooslg' ); ?></span>
					</h3>
				</div>
				
				<div class="inside">
					<div class="woo-slg-error info"><ul><li><?php print esc_html__( 'Note: You should have Apple Developer Account ( Company/Business membership  ) to create an Apple app.', 'wooslg' ); ?></li></ul></div>

					<div class="woo-slg-doc info"><ul><li><?php echo esc_html__( 'Before you can start using Apple for the social login, you need to create a Apple Application. You can get a step by step tutorial on how to create Apple Application on our', 'wooslg' ) . ' <a target="_blank" href="' . esc_url('https://docs.wpwebelite.com/social-network-integration/apple/') . '">'. esc_html__( 'Documentation', 'wooslg' ). '</a>'; ?></li></ul></div>

					<table class="form-table">
						<tbody>
							<?php
								// do action for add setting before apple settings
								do_action( 'woo_slg_before_apple_setting', $woo_slg_options );
							?>							
							<tr>
								<th scope="row">
									<label for="woo_slg_enable_apple"><?php esc_html_e( 'Enable Login : ', 'wooslg' ); ?></label>
								</th>
								<td>
								<div class="d-flex-wrap fb-avatra">
								<label for="woo_slg_enable_apple" class="toggle-switch">
									<input type="checkbox" id="woo_slg_enable_apple" name="woo_slg_enable_apple" value="1" <?php echo ($woo_slg_enable_apple=='yes') ? 'checked="checked"' : ''; ?>/>
									<span class="slider"></span>
									</label>	
									<p><?php echo esc_html__( 'Check this box, if you want to enable apple social login registration.', 'wooslg' ); ?></p>
								</div>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="form-table apple_section_hide" <?php echo $apple_style; ?>>
						<tbody>
							
							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_apple_client_id"><?php esc_html_e( 'Enter Client ID : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_apple_client_id" type="text" class="regular-text" name="woo_slg_apple_client_id" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_apple_client_id ); ?>" />
									<p class="description"><?php echo esc_html__( 'Enter Apple Client ID.', 'wooslg'); ?></p>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label><?php echo esc_html__( 'Valid Redirect URL : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<span class="description redirect-url"><?php echo '<code>'.WOO_SLG_APPLE_REDIRECT_URL.'</code>'; ?></span>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_apple_icon_url"><?php esc_html_e( 'Add Custom  Icon : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_apple_icon_url" type="text" class="woo_slg_social_btn_image regular-text" name="woo_slg_apple_icon_url" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_apple_icon_url ); ?>" />
									<input type="button" class="woo-slg-upload-file-button button-secondary" value="<?php esc_html_e( 'Upload Image', 'wooslg' );?>"/>
									<p class="description"><?php echo esc_html__( 'If you want to use your own Apple Icon, upload one here.', 'wooslg' ); ?></p>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="woo_slg_apple_link_icon_url"><?php esc_html_e( 'Add Custom Link Icon : ', 'wooslg' ); ?></label>
								</th>
								<td>
									<input id="woo_slg_apple_link_icon_url" type="text" class="woo_slg_social_btn_image regular-text" name="woo_slg_apple_link_icon_url" value="<?php echo $woo_slg_model->woo_slg_escape_attr( $woo_slg_apple_link_icon_url ); ?>" />
									<input type="button" class="woo-slg-upload-file-button button-secondary" value="<?php esc_html_e( 'Upload Image', 'wooslg' );?>"/>
									<p class="description"><?php echo esc_html__( 'If you want to use your own Apple Link Icon, upload one here.', 'wooslg' ); ?></p>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="form-table">
						<tbody>
							<!-- Apple Settings End -->

							<!-- Page Settings End --><?php
							
							// do action for add setting after apple settings
							do_action( 'woo_slg_after_apple_setting', $woo_slg_options );
							
							?>
							<tr>
								<td colspan="2"><?php
								echo apply_filters ( 'woo_slg_settings_submit_button', '<input class="button-primary woo-slg-save-btn" type="submit" name="woo-slg-set-submit" value="'.esc_html__( 'Save Changes','wooslg' ).'" />' );?>
							</td>
						</tr>
					</tbody>
				</table>
			</div><!-- .inside -->
		</div><!-- #apple -->
	</div><!-- .meta-box-sortables ui-sortable -->
</div><!-- .metabox-holder -->
</div><!-- #woo-slg-apple -->