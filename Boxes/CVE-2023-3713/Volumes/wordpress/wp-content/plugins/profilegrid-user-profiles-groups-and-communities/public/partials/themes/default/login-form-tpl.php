<?php $pmrequests = new PM_request;?>
<div class="pmagic">  
 <div class="pm-login-box pm-dbfl pm-border pm-radius5"> 
 <?php if(isset($pm_error) && $pm_error!='' && !is_user_logged_in()):?>
 <div class="pm-login-box-error pm-dbfl pm-pad10 pm-border-bt"><?php echo wp_kses_post($pm_error);?></div>
 <?php endif;?>
<?php 
if ( is_user_logged_in()) : ?>
	<?php
			$redirect_url = $pmrequests->profile_magic_get_frontend_url('pm_user_profile_page','');
	?> 
	  <div class="pm-login-header pm-dbfl pm-bg pm-pad10 pm-border-bt">
		  <h3><?php esc_html_e( 'You have successfully logged in.','profilegrid-user-profiles-groups-and-communities' );?></h3>
		  <p><?php esc_html_e('PROCEED TO','profilegrid-user-profiles-groups-and-communities');?></p>
	  </div>
	   <div class="pm-login-header-buttons pm-dbfl pm-pad10">
		   <div class="pm-center-button pm-difl pm-pad10"><a href="<?php echo esc_url($redirect_url);?>" class="pm_button"><?php esc_html_e('My Profile','profilegrid-user-profiles-groups-and-communities');?></a></div>
		   <div class="pm-center-button pm-difl pm-pad10"><a href="<?php echo esc_url(wp_logout_url( $pmrequests->profile_magic_get_frontend_url('pm_user_login_page',''))); ?>" class="pm_button"><?php esc_html_e('Logout','profilegrid-user-profiles-groups-and-communities');?></a></div>
	   </div>
	 <?php
else:
?>
		
 
<!-----Form Starts----->
  <form class="pmagic-form pm-dbfl pm-bg-lt" method="post" action="" id="pm_login_form" name="pm_login_form">
  <?php wp_nonce_field('pm_login_form'); ?>
            <input type="text" name="<?php echo esc_attr('user_login');?>" id="<?php echo esc_attr('user_login');?>" placeholder="<?php esc_attr_e('Email or Username','profilegrid-user-profiles-groups-and-communities');?>" required="required">
            <input type="password" name="<?php echo esc_attr('user_pass');?>" id="<?php echo esc_attr('user_pass');?>" placeholder="<?php esc_attr_e('Password','profilegrid-user-profiles-groups-and-communities');?>" required="required">
    
            <div class="pm-login-box-bottom-container pm-dbfl pm-bg pm-border">
                <input type="submit" value="<?php esc_attr_e('Login','profilegrid-user-profiles-groups-and-communities');?>" name="login_form_submit" class="pm-difl">
                <?php if($register_link):?>
                <a href="<?php echo esc_url($registration_url);?>" class="pm-difl pg-registration-button"><?php esc_html_e('Register','profilegrid-user-profiles-groups-and-communities');?> </a> 
                <?php endif; ?>
                <div class="pm-login-links-box pm-difr pm-pad10">
                <a href="<?php echo esc_url($forget_password_url);?>"><?php esc_html_e('Forgot Password?','profilegrid-user-profiles-groups-and-communities');?></a>
                </div>
            </div>
            
  </form>
  <?php endif;?>
   </div>
</div>
