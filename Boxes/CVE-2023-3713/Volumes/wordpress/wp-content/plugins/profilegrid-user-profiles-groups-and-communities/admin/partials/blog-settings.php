<?php
$dbhandler  = new PM_DBhandler();
$textdomain = $this->profile_magic;
$pmrequests = new PM_request();
$path       =  plugin_dir_url( __FILE__ );
$identifier = 'SETTINGS';
if ( filter_input( INPUT_POST, 'submit_settings' ) ) {
	$retrieved_nonce = filter_input( INPUT_POST, '_wpnonce' );
	if ( !wp_verify_nonce( $retrieved_nonce, 'save_blog_settings' ) ) {
		die( esc_html__( 'Failed security check', 'profilegrid-user-profiles-groups-and-communities' ) );
    }
	$exclude = array( '_wpnonce', '_wp_http_referer', 'submit_settings' );
	if ( !isset( $_POST['pm_blog_editor'] ) ) {
		$_POST['pm_blog_editor'] = 0;
    }
	if ( !isset( $_POST['pm_blog_feature_image'] ) ) {
		$_POST['pm_blog_feature_image'] = 0;
	}
	if ( !isset( $_POST['pm_blog_tags'] ) ) {
		$_POST['pm_blog_tags'] = 0;
	}
	if ( !isset( $_POST['pm_blog_privacy_level'] ) ) {
		$_POST['pm_blog_privacy_level'] = 0;
	}
	if ( !isset( $_POST['pm_enable_blog'] ) ) {
		$_POST['pm_enable_blog'] = 0;
	}
	if ( !isset( $_POST['pm_blog_notification_user'] ) ) {
		$_POST['pm_blog_notification_user'] = 0;
	}
	if ( !isset( $_POST['pm_blog_notification_admin'] ) ) {
		$_POST['pm_blog_notification_admin'] = 0;
	}
	$post = $pmrequests->sanitize_request( $_POST, $identifier, $exclude );
	if ( $post!=false ) {
		foreach ( $post as $key=>$value ) {
			$dbhandler->update_global_option_value( $key, $value );
		}
	}

	wp_safe_redirect( esc_url_raw( 'admin.php?page=pm_settings' ) );
	exit;
}
?>

<div class="uimagic">
  <form name="pm_user_settings" id="pm_user_settings" method="post">
    <!-----Dialogue Box Starts----->
    <div class="content">
      <div class="uimheader">
        <?php esc_html_e( 'Blog Settings', 'profilegrid-user-profiles-groups-and-communities' ); ?>
      </div>
     
      <div class="uimsubheader">
        <?php
		//Show subheadings or message or notice
		?>
      </div>
    
        <div class="uimrow">
        <div class="uimfield">
          <?php esc_html_e( 'Enable Blog', 'profilegrid-user-profiles-groups-and-communities' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_enable_blog" id="pm_enable_blog" type="checkbox" <?php checked( $dbhandler->get_global_option_value( 'pm_enable_blog', '1' ), '1' ); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_blog_html')" />
          <label for="pm_enable_blog"></label>
        </div>
        <div class="uimnote"><?php esc_html_e( 'Turn on social blogging for your users. Make sure you have a page with User Blog shortcode for users to submit posts.', 'profilegrid-user-profiles-groups-and-communities' ); ?><a target="_blank" href="https://profilegrid.co/documentation/user-blogs/"><?php esc_html_e( 'More', 'profilegrid-user-profiles-groups-and-communities' ); ?></a></div>
      </div>
        
    <div class="childfieldsrow" id="pm_blog_html" style=" 
    <?php
    if ( $dbhandler->get_global_option_value( 'pm_enable_blog', '1' )==1 ) {
		echo 'display:block;';
	} else {
		echo 'display:none;';}
	?>
    ">  
        <div class="uimrow">
            <div class="uimfield">
              <?php esc_html_e( 'Fetch Posts From', 'profilegrid-user-profiles-groups-and-communities' ); ?>
            </div>
            <div class="uiminput">
              <select name="pm_blog_post_from" id="pm_blog_post_from">
                <option value="profilegrid_blogs" <?php selected( $dbhandler->get_global_option_value( 'pm_blog_post_from', 'both' ), 'profilegrid_blogs' ); ?>><?php esc_html_e( 'User Blogs', 'profilegrid-user-profiles-groups-and-communities' ); ?> </option>
                <option value="post" <?php selected( $dbhandler->get_global_option_value( 'pm_blog_post_from', 'both' ), 'post' ); ?>><?php esc_html_e( 'Posts', 'profilegrid-user-profiles-groups-and-communities' ); ?> </option>
                <option value="both" <?php selected( $dbhandler->get_global_option_value( 'pm_blog_post_from', 'both' ), 'both' ); ?>><?php esc_html_e( 'Both', 'profilegrid-user-profiles-groups-and-communities' ); ?> </option>
              </select>
            </div>
            <div class="uimnote"><?php esc_html_e( 'Select from where you wish to fetch user authored posts. You can use WordPress default Posts, ProfileGrid User Posts system or a combination of both.', 'profilegrid-user-profiles-groups-and-communities' ); ?></div>
         </div>
        
        <div class="uimrow">
            <div class="uimfield">
              <?php esc_html_e( 'Default Blog post Status', 'profilegrid-user-profiles-groups-and-communities' ); ?>
            </div>
            <div class="uiminput">
              <select name="pm_blog_status" id="pm_blog_status">
                <option value="publish" <?php selected( $dbhandler->get_global_option_value( 'pm_blog_status', 'pending' ), 'publish' ); ?>><?php esc_html_e( 'Published', 'profilegrid-user-profiles-groups-and-communities' ); ?> </option>
                <option value="pending" <?php selected( $dbhandler->get_global_option_value( 'pm_blog_status', 'pending' ), 'pending' ); ?>><?php esc_html_e( 'Pending', 'profilegrid-user-profiles-groups-and-communities' ); ?> </option>
                <option value="draft" <?php selected( $dbhandler->get_global_option_value( 'pm_blog_status', 'pending' ), 'draft' ); ?>><?php esc_html_e( 'Draft', 'profilegrid-user-profiles-groups-and-communities' ); ?> </option>
              </select>
            </div>
            <div class="uimnote"><?php esc_html_e( 'Status of the blog post after user submits it. You can allow it to be automatically approved or save it as Pending for moderation.', 'profilegrid-user-profiles-groups-and-communities' ); ?></div>
         </div>
      
        <div class="uimrow">
        <div class="uimfield">
          <?php esc_html_e( 'Feature Image', 'profilegrid-user-profiles-groups-and-communities' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_blog_feature_image" id="pm_blog_feature_image" type="checkbox" <?php checked( $dbhandler->get_global_option_value( 'pm_blog_feature_image', '0' ), '1' ); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_blog_feature_image"></label>
        </div>
        <div class="uimnote"><?php esc_html_e( 'Turn on to allow users to add featured image to their post. A featured image is displayed prominently above the blog post.', 'profilegrid-user-profiles-groups-and-communities' ); ?></div>
      </div>
        
        <div class="uimrow">
        <div class="uimfield">
          <?php esc_html_e( 'Tags', 'profilegrid-user-profiles-groups-and-communities' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_blog_tags" id="pm_blog_tags" type="checkbox" <?php checked( $dbhandler->get_global_option_value( 'pm_blog_tags', '0' ), '1' ); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_blog_tags"></label>
        </div>
        <div class="uimnote"><?php esc_html_e( 'Turn on to allow users to add tags to their posts.', 'profilegrid-user-profiles-groups-and-communities' ); ?></div>
      </div>
        
        <div class="uimrow">
        <div class="uimfield">
          <?php esc_html_e( 'Use Tinymce Editor', 'profilegrid-user-profiles-groups-and-communities' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_blog_editor" id="pm_blog_editor" type="checkbox" <?php checked( $dbhandler->get_global_option_value( 'pm_blog_editor', '0' ), '1' ); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_blog_editor"></label>
        </div>
        <div class="uimnote"><?php esc_html_e( "Turn it on to allow users to use WordPress' rich text editor for post formatting. Keep it off if you only wish to allow users to post content in plain text.", 'profilegrid-user-profiles-groups-and-communities' ); ?></div>
      </div>
        
        <div class="uimrow">
        <div class="uimfield">
          <?php esc_html_e( 'Enable content Privacy', 'profilegrid-user-profiles-groups-and-communities' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_blog_privacy_level" id="pm_blog_privacy_level" type="checkbox" <?php checked( $dbhandler->get_global_option_value( 'pm_blog_privacy_level', '0' ), '1' ); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_blog_privacy_level"></label>
        </div>
        <div class="uimnote"><?php esc_html_e( 'Turning this on will ask users to set privacy level for their blog post while submitting it.', 'profilegrid-user-profiles-groups-and-communities' ); ?></div>
      </div>
        
        <div class="uimrow">
        <div class="uimfield">
          <?php esc_html_e( 'Notify Users', 'profilegrid-user-profiles-groups-and-communities' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_blog_notification_user" id="pm_blog_notification_user" type="checkbox" <?php checked( $dbhandler->get_global_option_value( 'pm_blog_notification_user', '0' ), '1' ); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_blog_notification_user"></label>
        </div>
        <div class="uimnote"><?php esc_html_e( 'Send an email notifying users when their blog post is published successfully. The email content can be modified from Email Templates section.', 'profilegrid-user-profiles-groups-and-communities' ); ?></div>
      </div>
        
        <div class="uimrow">
        <div class="uimfield">
          <?php esc_html_e( 'Notify Admin', 'profilegrid-user-profiles-groups-and-communities' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_blog_notification_admin" id="pm_blog_notification_admin" type="checkbox" <?php checked( $dbhandler->get_global_option_value( 'pm_blog_notification_admin', '0' ), '1' ); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_notify_admin_html')" />
          <label for="pm_blog_notification_admin"></label>
        </div>
        <div class="uimnote"><?php esc_html_e( 'Send an email notifying admin when a user submits new blog post.', 'profilegrid-user-profiles-groups-and-communities' ); ?></div>
      </div>
        
       <div class="childfieldsrow" id="pm_notify_admin_html" style=" 
       <?php
		if ( $dbhandler->get_global_option_value( 'pm_blog_notification_admin', 0 )==1 ) {
			echo 'display:block;';
		} else {
			echo 'display:none;';}
		?>
        ">
            
                <div class="uimrow">
                    <div class="uimfield">
                      <?php esc_html_e( 'Email Subject', 'profilegrid-user-profiles-groups-and-communities' ); ?>
                    </div>
                    <div class="uiminput">
                        <input type="text" name="pm_blog_notification_email_subject" id="pm_blog_notification_email_subject" value="<?php echo esc_attr( $dbhandler->get_global_option_value( 'pm_blog_notification_email_subject', __( 'New User Blog Post Submitted', 'profilegrid-user-profiles-groups-and-communities' ) ) ); ?>" />
                      
                    </div>
                    <div class="uimnote"><?php esc_html_e( 'Subject of the email sent to the admin.', 'profilegrid-user-profiles-groups-and-communities' ); ?></div>
                 </div>
                <?php
                $settings   = array(
					'wpautop'           => false,
					'media_buttons'     => true,
                    'textarea_name'     => 'pm_blog_notification_email_body',
                    'textarea_rows'     => 20,
                    'tabindex'          => '',
                    'tabfocus_elements' => ':prev,:next',
                    'editor_css'        => '',
                    'editor_class'      => '',
                    'teeny'             => false,
                    'dfw'               => false,
                    'tinymce'           => true, // <-----
                    'quicktags'         => true,
                );
                $poststatus = $dbhandler->get_global_option_value( 'pm_blog_status', 'pending' );
                if ( $poststatus=='publish' ) {
                     $review_body = $dbhandler->get_global_option_value( 'pm_blog_notification_email_body', wp_kses_post( 'Hello, <br /> {{user_login}} from {{group_name}} has published a new post titled {{post_name}}. You can view the post by {{post_link}}', 'profilegrid-user-profiles-groups-and-communities' ) );
                } else {
                    $review_body = $dbhandler->get_global_option_value( 'pm_blog_notification_email_body', wp_kses_post( 'Hello, <br /> {{user_login}} from {{group_name}} has submitted a new post titled {{post_name}} and is pending approval. You can moderate the post by {{edit_post_link}}', 'profilegrid-user-profiles-groups-and-communities' ) );
                }
                ?>
	    
                <div class="uimrow">
                    <div class="uimfield">
                      <?php esc_html_e( 'Email Content', 'profilegrid-user-profiles-groups-and-communities' ); ?>
                    </div>
                    <div class="uiminput">
                        <?php wp_editor( $review_body, 'pm_blog_notification_email_body', $settings ); ?>
                    </div>
                    <div class="uimnote"><?php esc_html_e( 'Content of the email sent to the admin.', 'profilegrid-user-profiles-groups-and-communities' ); ?></div>
                 </div>
                
                
            </div>   
        
    </div>
      <div class="buttonarea"> 
          <a href="admin.php?page=pm_settings">
        <div class="cancel">&#8592; &nbsp;
          <?php esc_html_e( 'Cancel', 'profilegrid-user-profiles-groups-and-communities' ); ?>
        </div>
        </a>
        <?php wp_nonce_field( 'save_blog_settings' ); ?>
        <input type="submit" value="<?php esc_attr_e( 'Save', 'profilegrid-user-profiles-groups-and-communities' ); ?>" name="submit_settings" id="submit_settings" />
        <div class="all_error_text" style="display:none;"></div>
      </div>
    </div>
   
  </form>
</div>
