<?php

function mo_openid_display_feedback_form(){

    if ( 'plugins.php' != basename(sanitize_text_field($_SERVER['PHP_SELF'])) ) {
        return;
    }
    wp_enqueue_style( 'wp-pointer' );
    wp_enqueue_script( 'wp-pointer' );
    wp_enqueue_script( 'utils' );
    wp_enqueue_style( 'mo_openid_plugins_page_style', plugins_url( 'includes/css/mo_openid_feedback.css', __FILE__ ) );

    ?>

    </head>
    <body>

    <!-- The Modal -->
    <div id="mo_openid_myModal" class="mo_openid_modal1">

        <!-- Modal content -->
        <div class="mo_openid_modal1-content">
            <span class="mo_openid_close">&times;</span>
            <h3>What Happened? </h3>
            <form name="f" method="post" action="" id="mo_openid_feedback">
                <input type="hidden"  name="option" value="mo_openid_feedback"/>
                <input type="hidden" name="mo_openid_feedback_nonce" value="<?php echo esc_attr(wp_create_nonce( 'mo-openid-feedback-nonce' )); ?>"/>
                <div>
                    <p style="margin-left:2%">

                        <?php
                        $deactivate_reasons = array(
                            "Facebook Login Error",
                            "Not Working",
                            "Login Icons are not displayed on Custom Registration/Login page",
                            "Confusing Interface",
                            "The plugin is great, but I need specific feature that you don't support",
                            "I dont want to register",
                            "Installing paid version",
                            "It's a temporary deactivation. I'm just debugging an issue",
                            "Other Reasons:",
                        );
                        $i=0;$p=0;
                        foreach ( $deactivate_reasons as $deactivate_reasons ) {?>

                    <div  class="radio" style="padding:1px;margin-left:2%">
                        <label style="font-weight:normal;font-size:14.6px" for="<?php echo esc_attr($deactivate_reasons); ?>">
                            <input type="radio" name="deactivate_plugin" value="<?php echo esc_attr($deactivate_reasons);?>" >
                            <?php echo esc_html($deactivate_reasons);?>
                        </label>
                    </div>
                    <span id="link_id_<?php echo esc_attr($p);esc_attr($p++);?>"></span>
                    <div id="text_<?php echo esc_attr($i);esc_attr($i++);?>"></div>
                    <?php }
                    if(get_option('mo_openid_admin_email'))
                        $email=get_option('mo_openid_admin_email');
                    else
                        $email = get_option('admin_email');
                    ?>
                    <br>
                    Email: <input type="text" id="mo_feedback_email" name="mo_feedback_email" value="<?php echo esc_attr($email)?>"/>
                    <br><br>
                    <div style="margin-left: 2%; width: 100%; margin-bottom: 5%; font-size: 15px;">
                        <input type="checkbox" name="get_reply" value="reply" checked>miniOrange representative will reach out to you at the email-address entered above.</input>
                    </div>
                    <div class="mo_openid_modal-footer" >
                        <?php
                        update_option( 'mo_openid_message',"ERROR_WHILE_SUBMITTING_QUERY");
                        mo_openid_show_success_message();
                        ?>
                        <input type="submit" name="submit" class="button button-primary button-large" value="Submit" />
                        <input type="submit" name="skip_reply" class="button button-primary button-large" style="width: 11%" value="skip">
                    </div>
                </div>
            </form>
            <form name="f" method="post" action="" id="mo_openid_feedback_form_close">
                <input type="hidden" name="mo_openid_option" value="mo_openid_skip_feedback"/>
                <input type="hidden" name="mo_openid_feedback_close_nonce"
                       value="<?php echo esc_attr(wp_create_nonce( 'mo-openid-feedback-close-nonce' )); ?>"/>
            </form>

        </div>

    </div>

    <script>
        function mo_openid_skip_feedback(){
            jQuery('#mo_openid_feedback_form_close').submit();
        }
        jQuery('a[aria-label="Deactivate Social Login, Social Sharing by miniOrange"]').click(function(){
            // Get the mo_openid_modal
            var mo_openid_modal = document.getElementById('mo_openid_myModal');

            // Get the button that opens the mo_openid_modal
            var mo_btn = document.getElementById("mymo_btn");

            // Get the <span> element that closes the mo_openid_modal
            var span = document.getElementsByClassName("mo_openid_close")[0];

            // When the user clicks the button, open the mo_openid_modal

            mo_openid_modal.style.display = "block";
            var i=0;
            var show_link='';

            jQuery('input:radio[name="deactivate_plugin"]').click(function () {
                var reason= jQuery(this).val();
                jQuery('#mo_openid_query_feedback').removeAttr('required');

                if(reason=='Facing issues During Registration'){
                    jQuery('#mo_openid_query_feedback').attr("placeholder", "Can you please describe the issue in detail?");
                    jQuery('#link_id').hide();
                }
                else if(reason=="Facebook Login Error"){
                    show_link='<p style="background-color:#feffb2;padding:5px 10px;">Sorry for your inconvenience. Facebook has blocked our default app so please configure your own custom app.'+'<br>'+'Follow <a href="https://plugins.miniorange.com/configure-facebook-social-login-in-wordpress" target="_blank"><b>Facebook Setup Link</b></a> | <a href="https://www.youtube.com/watch?v=1hsLaT54XYM" target="_blank"><b>Setup Video</b></a></p>';
                    add_text_box(0,"Please explain your issue in detail.");
                }
                else if(reason=="Not Working"){
                    add_text_box(1,"Please specify which functionality is not working.");
                }
                else if(reason=="Login Icons are not displayed on Custom Registration/Login page"){
                    show_link='<p style="background-color:#feffb2;padding:5px 10px;">Please add <b>[miniorange_social_login]</b> shortcode on custom registration/login page. For more information please refer to the following guide: <a href="\n' +
                        'https://plugins.miniorange.com/guide-to-display-social-login-icons-using-shortcode-in-wordpress" target="_blank"><b>Shortcode Guide</b></p>';
                    add_text_box(2,"If you are still facing the issue please elaborate here.");
                }
                else if(reason=="Confusing Interface"){
                    show_link='<p style="background-color:#feffb2;padding:5px 10px;">If you are facing issue while setting up the plugin. Please check out this ' + '<a href="https://youtu.be/ln17jan6t1Y" target="_blank"><b>Setup Video.</b></p>';
                    add_text_box(3,"Finding it confusing? Let us know so that we can improve the interface");
                }
                else if(reason=="Does not have the features I am looking for"){
                    add_text_box(4,"Let us know what feature are you looking for");
                }
                else if(reason=="I dont want to register"){
                    show_link='<p style="background-color:#feffb2;padding:5px 10px;"><a href="https://faq.miniorange.com/knowledgebase/why-should-i-register/" target="_blank"><b>Why should I register?</b></p>';
                    add_text_box(5,"");
                }
                else if(reason=="Installing paid version"){
                    add_text_box(6,"Can you please let us know which plan you have upgraded?");
                }
                else if(reason=="Other Reasons:"){
                    add_text_box(8,"Can you let us know the reason for deactivation?");
                }
                else if(reason=="Remove miniOrange Logo under Icons"){
                    show_link='<p style="background-color:#feffb2;padding:5px 10px;"><a href="\n' +
                        'https://plugins.miniorange.com/disable-branding-logo-under-wordpress-social-login-icons" target="_blank"><b>Please refer to the guide to remove the miniOrange Icon from the login page : <b>Guide</b></p>';
                    add_text_box(9,"");
                }
                else if(reason=="Not Receiving OTP During Registration"){
                    show_link='<p style="background-color:#feffb2;padding:5px 10px;">Please '+'<a href="https://www.miniorange.com/businessfreetrial" target="_blank"><b>click here</b></a>'+' to create an account.</p>';
                    add_text_box(10,"Can you please describe the issue in detail?");

                }
                else if(reason=="Bugs in the plugin"){
                    add_text_box(11,"Can you please let us know about the bug in detail?");
                }
            });

            function add_text_box(x,place_holder) {
                jQuery('#text_'+x).html('<textarea id="mo_openid_query_feedback" name="mo_openid_query_feedback"  rows="3" style="margin-left:3%" cols="50" placeholder="'+place_holder+'"></textarea>');

                for (i = 0; i <8 ; i++)
                {
                    if(x==i)
                    {
                        if(x==0|| x==2 ||x==3||x==5) {jQuery('#link_id_' + x).html(show_link);jQuery('#link_id_' + x).show();} else{jQuery('#link_id_'+i).hide();}
                    }
                    else{
                        jQuery('#link_id_'+i).hide();
                    }
                    if(i==6 && x==i){jQuery('#text_'+i).hide();jQuery('#link_id_'+i).hide();}
                    if(i==x&&x!=5)
                    {jQuery('#text_'+i).show();}
                    else
                    {jQuery('#text_'+i).hide();}
                }
            }


            // When the user clicks on <span> (x), mo_openid_close the mo_openid_modal
            span.onclick = function() {
                window.location.reload(true);
            }

            // When the user clicks anywhere outside of the mo_openid_modal, mo_openid_close it
            window.onclick = function(event) {
                if (event.target == mo_openid_modal) {
                    mo_openid_modal.style.display = "none";
                }
            }
            return false;
        });
    </script><?php
}