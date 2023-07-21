<?php
$dbhandler = new PM_DBhandler;
$pmrequests = new PM_request;
$pm_sanitizer = new PM_sanitizer();
$textdomain = $this->profile_magic;
$path =  plugin_dir_url(__FILE__);
$gid = filter_input(INPUT_GET, 'gid');
if(!isset($gid))
{
    if(isset($content['id']))
    {
        $gid = $content['id'];
    }
    
    if(isset($content['gid']))
    {
        $gid = $content['gid'];
    }
    
}
$rmformid = $pmrequests->pm_check_if_group_associate_with_rm_form($gid);
if($rmformid!=0 && class_exists('Registration_Magic'))
{
    echo do_shortcode('[RM_Form id="'.esc_html($rmformid).'"]' );
}
else
{
if(isset($content['type']) && ($content['type']=='multipage' || $content['type']=='paged'))
{
    $type = 'reg-form-multipage-tpl';
}
else 
{
    $type = 'reg-form-tpl';
}
$check = $pmrequests->pm_check_field_exist($gid,'user_email',true);
$meta_query_array = $pmrequests->pm_get_user_meta_query(array('gid'=>$gid));
$user_query = $dbhandler->pm_get_all_users_ajax('',$meta_query_array);
$total_users_in_group = $user_query->get_total();
$limit = $dbhandler->get_value('GROUPS','group_limit',$gid);
$req_obj = $pm_sanitizer->sanitize($_REQUEST);
$post_obj = $pm_sanitizer->sanitize($_POST);
$is_group_limit = $dbhandler->get_value('GROUPS','is_group_limit',$gid);
if(isset($req_obj["action"]) && $req_obj["action"]!='process')
{
	do_action('profile_magic_before_registration_form',$post_obj,$req_obj,$gid,$textdomain);
	return false;
}

if(is_user_logged_in())
{
    $exclude = "and field_type not in('user_name','user_email','user_avatar','user_pass','confirm_pass','paragraph','heading','read_only')";
}
else
{
    $exclude = "and field_type not in ('read_only')";
}
$fields =  $dbhandler->get_all_result('FIELDS', $column = '*',array('associate_group'=>$gid,'show_in_signup_form'=>1),'results',0,false, $sort_by = 'ordering',false,$exclude);
if(isset($post_obj['reg_form_submit']) || isset($post_obj['pm_payment_method']))
{
	$errors = '';
	if($pmrequests->profile_magic_show_captcha('pm_enable_recaptcha_in_reg'))
	{
		$response = isset( $post_obj['g-recaptcha-response'] ) ? sanitize_text_field( $post_obj['g-recaptcha-response'] ) : '';
		$remote_ip = filter_var($_SERVER["REMOTE_ADDR"], FILTER_VALIDATE_IP);
		$check_captcha = $pmrequests->profile_magic_captcha_verification($response,$remote_ip);
	}
	else
	{
		$check_captcha=true;
	}
	
	if($check_captcha==true)
	{
		$errors = $pmrequests->profile_magic_frontend_server_validation($post_obj,$_FILES,$_SERVER,$fields,$textdomain);
		if(empty($errors))
		{
                    if(is_user_logged_in())
                    {
                        
                        $current_user_id = get_current_user_id();
                        $pmrequests->pm_update_user_custom_fields_data($post_obj,$_FILES,$_SERVER,$gid,$fields,$current_user_id);
                        $group_type = $pmrequests->profile_magic_get_group_type($gid);
                        $is_paid_group = $pmrequests->profile_magic_check_paid_group($gid);
                        do_action('profile_magic_join_group_registration_process',$post_obj,$gid,$current_user_id);
                        if($is_paid_group=="0")
                        {
                            $pmrequests->profile_magic_join_group_fun($current_user_id,$gid,$group_type);
                        }
                        do_action('profile_magic_join_paid_group_process',$post_obj,$gid,$current_user_id);
                    }
                    else
                    {
			$user_id = $pmrequests->profile_magic_frontend_registration_request($post_obj,$_FILES,$_SERVER,$gid,$fields);
			
			do_action('profile_magic_registration_process',$post_obj,$_FILES,$_SERVER,$gid,$fields,$user_id,$textdomain);
			
                        if(!isset($post_obj['action']) && $dbhandler->get_value('GROUPS','show_success_message',$gid)==1)
                        {
                            echo wp_kses_post($dbhandler->get_value('GROUPS','success_message',$gid));
                        }
                
			if($pmrequests->pm_get_user_redirect($gid)!='')
			{
				header('refresh: 5; url='.$pmrequests->pm_get_user_redirect($gid));	
			}
                    }
			
		}
		else
		{
			foreach($errors as $error)
			{
				echo wp_kses_post($error).'<br />';	
			}
		}
	}
	else
	{
		$errors = esc_html__('captcha failed','profilegrid-user-profiles-groups-and-communities');	
	}
}
else
{
	
	if($check==false)
	{
		$message =  esc_html__('Require Useremail field','profilegrid-user-profiles-groups-and-communities');
	}
	elseif($is_group_limit==1)
	{
		if($limit > $total_users_in_group)
		{
			$message ='';	
		}
		else
		{
			$message  = $dbhandler->get_value('GROUPS','group_limit_message',$gid);
		}
	}elseif($pmrequests->profile_magic_check_paid_group($gid)>0)
        {
            $message = apply_filters( 'profile_magic_check_payment_config','');
            if($message == 'disabled')
            {
                $message = esc_html__('Payment system is not configured to accept payments. Please configure at least one payment processor for this to work.','profilegrid-user-profiles-groups-and-communities');
            }
        }
	else
	{
		$message = '';
	}
	
	if($message!='')
	{
		echo wp_kses_post($message);	
	}
	else
	{
		$this->profile_magic_get_pm_theme_tmpl($type,$gid,$fields);
	}
}
if(isset($content['type']) && ($content['type']=='paged'|| $content['type']=='multipage') && !isset($post_obj['reg_form_submit']) && !is_user_logged_in())
{
    wp_enqueue_script( 'profile-magic-multistep-form', plugin_dir_url( __FILE__ ) . '../js/profile-magic-multistep-form.js');
    
}
}
?>
