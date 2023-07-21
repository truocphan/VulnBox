<?php
$dbhandler = new PM_DBhandler;
$pmrequests = new PM_request;
$current_user = wp_get_current_user();
$uploads =  wp_upload_dir();
$pm_sanitizer = new PM_sanitizer();
$post      = $pm_sanitizer->sanitize( $_POST );
$filefield = $_FILES['coverimg'];
$allowed_ext ='jpg|jpeg|png|gif';
$targ_w = $targ_h = 150;
$jpeg_quality = intval($dbhandler->get_global_option_value('pg_image_quality','90'));
 switch($post['cover_status']) {
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
        if (is_numeric($jpeg_quality)) 
        {
            $image->set_quality(intval($jpeg_quality));
        }
        
        $image->save( $uploads['path']. '/'.$basename );
        
        update_user_meta($post['user_id'],$post['user_meta'],$post['attachment_id']);
        do_action('pm_update_cover_image',$post['user_id']);
        echo "<img id='coverphotofinal' file-name='".esc_attr($basename)."' src='".esc_url($image_attribute[0])."' class='preview'/>";
    }
 else {
         echo wp_kses_post($image->get_error_message());
    }
    die;
  break;
  default:
        
    if($post['user_id']==$current_user->ID)
    {
        $minimum_width = trim($dbhandler->get_global_option_value('pg_cover_photo_minimum_width','DEFAULT'));
        $minimum_height = 300;
        $maximum_size = trim($dbhandler->get_global_option_value('pg_cover_image_max_file_size',''));
        $minimum_require = array();
        if($minimum_width=='' || $minimum_width=='DEFAULT')
        {
            $minimum_require[0]=$post['cover_minwidth'];
        }
        else
        {
            $minimum_require[0]=$minimum_width;
        }
        $minimum_require[1] = $minimum_height;
        
        if($maximum_size!='')
        {
            $minimum_require[2] = $maximum_size;
        }
        
        $attachment_id = $pmrequests->make_upload_and_get_attached_id($filefield,$allowed_ext,$minimum_require);
        if(is_numeric($attachment_id))
        {
            $image_attribute = wp_get_attachment_image_src($attachment_id,'full');
            $image_newpath = get_attached_file($attachment_id);
            
            echo "<img id='coverimage' file-name='". esc_attr(basename($image_attribute[0]))."' src='".esc_url($image_attribute[0])."' class='preview'/>";
            echo "<input type='hidden' name='covertruewidth' id='covertruewidth' value='".esc_attr($image_attribute[1])."' />";
            echo "<input type='hidden' name='covertrueheight' id='covertrueheight' value='".esc_attr($image_attribute[2])."' />";
            echo "<input type='hidden' name='cover_attachment_id' id='cover_attachment_id' value='".esc_attr($attachment_id)."' />";
            echo "<input type='hidden' name='coverfullpath' id='coverfullpath' value='".$image_newpath."' />";
            echo "<input type='hidden' name='pg_cover_image_error' id='pg_cover_image_error' value='0' />";
            
        }
        else
        {
           echo '<p class="pm-popup-error" style="display:block;">'.esc_html($attachment_id).'</p>'; 
           echo "<input type='hidden' name='pg_cover_image_error' id='pg_cover_image_error' value='1' />";
        }
        

    }
    die;

 }
?>
