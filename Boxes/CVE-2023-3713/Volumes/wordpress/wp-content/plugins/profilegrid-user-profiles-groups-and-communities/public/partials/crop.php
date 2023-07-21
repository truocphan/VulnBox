<?php
$dbhandler = new PM_DBhandler;
$pmrequests = new PM_request;
$current_user = wp_get_current_user();
$uploads =  wp_upload_dir();
$pm_sanitizer = new PM_sanitizer();
$post      = $pm_sanitizer->sanitize( $_POST );
$filefield = $_FILES['photoimg'];
$allowed_ext ='jpg|jpeg|png|gif';
$targ_w = $targ_h = 150;
$jpeg_quality = intval($dbhandler->get_global_option_value('pg_image_quality','90'));
 switch($post['status']) {
  case 'cancel' :
      $delete = wp_delete_attachment( $post['attachment_id'],true );
      print_r($delete);
    die;
  break;
  
  case 'save' :
     
    $image = wp_get_image_editor( $post['fullpath'] );
       $image_attribute = wp_get_attachment_image_src($post['attachment_id'],'full');
      $basename = basename($post['fullpath']);
    if ( ! is_wp_error( $image ) ) {
        $image->crop( $post['x'], $post['y'], $post['w'], $post['h'], $post['w'], $post['h'], false );
        $image->resize( $post['w'], $post['h'], array($post['x'], $post['y']) );
        if($post['user_meta']=='pm_user_avatar')
        {
            $image_attribute = wp_get_attachment_image_src($post['attachment_id'],array(150,150));
            $basename = basename($image_attribute[0]);
        }
        if (is_numeric($jpeg_quality)) 
        {
            $image->set_quality(intval($jpeg_quality));
        }
        
        $image->save( $uploads['path']. '/'.$basename );
        update_user_meta($post['user_id'],$post['user_meta'],$post['attachment_id']);
        do_action('pm_update_profile_image',$post['user_id']);
        echo "<img id='photofinal' file-name='".esc_attr($basename)."' src='".esc_url($image_attribute[0])."' class='preview'/>";
    }
    else {
         echo wp_kses_post($image->get_error_message());
    }
    die;
  break;
  default:
       
    if($post['user_id']==$current_user->ID)
    {
        $minimum_require = $pmrequests->pm_get_minimum_requirement_user_avatar();
        
        $attachment_id = $pmrequests->make_upload_and_get_attached_id($filefield,$allowed_ext,$minimum_require);
        if(is_numeric($attachment_id))
        {
        $image_attribute = wp_get_attachment_image_src($attachment_id,'full');
        $image_newpath = get_attached_file($attachment_id);
        
        echo "<img id='photo' file-name='".esc_attr( basename($image_attribute[0]))."' src='".esc_url($image_attribute[0])."' class='preview'/>";
        echo "<input type='hidden' name='truewidth' id='truewidth' value='".esc_attr($image_attribute[1])."' />";
        echo "<input type='hidden' name='trueheight' id='trueheight' value='".esc_attr($image_attribute[2])."' />";
        echo "<input type='hidden' name='attachment_id' id='attachment_id' value='".esc_attr($attachment_id)."' />";
        echo "<input type='hidden' name='fullpath' id='fullpath' value='". $image_newpath."' />";
         echo "<input type='hidden' name='pg_profile_image_error' id='pg_profile_image_error' value='0' />";
        }
        else
        {
            echo '<p class="pm-popup-error" style="display:block;">'.esc_html($attachment_id).'</p>';
            echo "<input type='hidden' name='pg_profile_image_error' id='pg_profile_image_error' value='1' />";
        }
        
       

    }
    die;

 }
?>
