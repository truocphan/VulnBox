<?phpif ( ! defined( 'ABSPATH' ) ) {     exit; // Exit if accessed directly}
	add_action( 'woocommerce_login_form_end', 'psl_myaccount_login_form' );
	function psl_myaccount_login_form(){
	$setting_data=get_option('psl_social_plugin');
	extract($setting_data['facebook_details']);	extract($setting_data['change_text_details']);   
	extract($setting_data['google_plus_details']);		if($enable_facebook=='on'|| $enable_google_plus=='on' ){		?>				<p class="login-txt" ><?php echo $login_label ?></p>  		   <div id="logo-link">		  			<?php	if($enable_facebook=='on'){ ?>					
				   <img src='<?php if($fb_icon_url!=''){ echo $fb_icon_url;}else{ echo plugin_dir_url( __FILE__ )."images/facebook.png";}?>'   style="cursor:pointer;" onclick="facebook_login()"/>
				   <?php } if($enable_google_plus=='on'){?>
				   <img src='<?php if($google_icon_url!=''){echo $google_icon_url;}else{ echo plugin_dir_url( __FILE__ )."images/google-plus.png";}?>'   style="cursor:pointer;" onclick="google_login()"/>
					<?php } ?>			</div>							<?php 		}	}?>