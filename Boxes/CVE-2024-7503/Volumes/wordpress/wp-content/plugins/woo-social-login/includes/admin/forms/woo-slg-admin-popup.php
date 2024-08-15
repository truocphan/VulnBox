<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Shortocde UI
 *
 * This is the code for the pop up editor, which shows up when an user clicks
 * on the woo social login icon within the WordPress editor.
 *
 * @package WooCommerce - Social Login
 * @since 1.1.1
 * 
 **/

// Assign some variable
$networks = woo_slg_social_networks();
$networks['email'] = esc_html__('Login with email', 'wooslg');
?>

<!--.woo-slg-popup-content-->
<div class="woo-slg-popup-content">

	<!--.woo-slg-popup-header-->
	<div class="woo-slg-header">
		<div class="woo-slg-header-title"><?php esc_html_e( 'Add a Social Login Shortcode', 'wooslg' );?></div>
		<div class="woo-slg-popup-close"><a href="javascript:void(0);" class="woo-slg-close-button"><img src="<?php echo esc_url(WOO_SLG_IMG_URL);?>/tb-close.png" alt="<?php esc_html_e( 'Close', 'wooslg' );?>" /></a></div>
	</div>
	
	<!--.woo-slg-shortcodes-options-->
	<div class="woo-slg-popup">
		<div id="woo_slg_login_options" class="woo-slg-shortcodes-options">
		
			<table class="form-table">
				<tbody>

					<tr>
						<th scope="row">
							<label for="woo_slg_title"><?php esc_html_e( 'Social Login Title : ', 'wooslg' );?></label>		
						</th>
						<td>
							<input type="text" id="woo_slg_title" class="regular-text" value="<?php esc_html_e( 'Prefer to Login with Social Media', 'wooslg' );?>" /> 
							<span class="description"><?php esc_html_e( 'Enter a social login title.', 'wooslg' );?></span>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="woo_slg_title"><?php esc_html_e( 'Social Networks : ', 'wooslg' );?></label>		
						</th>
						<td>
							<select id="woo-slg-selected-networks"  class="wslg-select" multiple="" data-width="85%">
								<?php
								foreach ( $networks as $key => $network ) {
									echo '<option value="'.esc_attr($key).'">'.esc_html($network).'</option>';
								} ?>
							</select>
							<span class="description"><?php esc_html_e( 'Select social networks you want to show. Leave it empty to display all enable social networks.', 'wooslg' );?></span>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="woo_slg_redirect_url"><?php esc_html_e( 'Redirect URL : ', 'wooslg' );?></label>		
						</th>
						<td>
							<input type="text" id="woo_slg_redirect_url" class="regular-text" value="" placeholder="<?php print site_url();?>" /><br/>
							<span class="description"><?php esc_html_e( 'Enter a redirect URL for users after they login with social media. The URL must start with', 'wooslg' ); 
							    print ' http:// or https://'?></span>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="woo_slg_show_on_page"><?php esc_html_e( 'Show Only on Page / Post : ', 'wooslg' );?></label>		
						</th>
						<td>
							<input type="checkbox" id="woo_slg_show_on_page" value="1" /><br />
							<span class="description"><?php esc_html_e( 'Check this box if you want to show social login buttons only on inner page of posts and pages.', 'wooslg' );?></span>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="woo_slg_enable_expand_collapse"><?php esc_html_e( 'Expand/Collapse Buttons : ', 'wooslg' );?></label>
						</th>
						<td>
							<select id="woo_slg_enable_expand_collapse" name="woo_slg_enable_expand_collapse">
								<option value=""><?php esc_html_e('None','wooslg');?></option>
								<option value="collapse"><?php esc_html_e('Collapse','wooslg');?></option>
								<option value="expand"><?php esc_html_e('Expand','wooslg');?></option>
							</select>
							<span class="description"><?php esc_html_e( 'Here you can select how to show the social login buttons.', 'wooslg' );?></span>
						</td>
					</tr>

				</tbody>
			</table>
			
		</div><!--woo_slg_login_options-->
		
		<div id="woo_slg_insert_container" >
			<input type="button" class="button-secondary" id="woo_slg_insert_shortcode" value="<?php esc_html_e( 'Insert Shortcode', 'wooslg' ); ?>">
		</div>
		
	</div><!--.woo-slg-popup-->
	
</div><!--.woo-slg-popup-content-->
<div class="woo-slg-popup-overlay"></div>