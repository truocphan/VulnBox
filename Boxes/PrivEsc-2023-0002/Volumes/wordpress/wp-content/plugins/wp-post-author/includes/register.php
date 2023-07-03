<?php 

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );  // prevent direct access
class AwpaRegister{

    const PAYPAL_ID = 'paypal_id'; 
    const PAYPAL_SANDBOX ="true";
    const PAYPAL_CURRENCY= 'US';
    

    public function __construct(){
        add_action("wp_ajax_awapa_register_users", [$this, "awapa_register_users"]);
        add_action("wp_ajax_nopriv_awapa_register_users", [$this,"awapa_register_users"]);
    }

    function awapa_register_users(){
        $postdata = array();
        parse_str($_POST['userData'], $postdata);

        if(PAYPAL_SANDBOX == true){
           $url =  "https://www.sandbox.paypal.com/cgi-bin/webscr";
        }else{
        $url ="https://www.paypal.com/cgi-bin/webscr";
        }

        
        $user_login =stripcslashes($postdata['user_login']);
        $user_nickname  = $postdata['nickname'];
        $user_email = stripcslashes($postdata['email']);
        $user_password = $postdata['password'];
        $user_first_name = $postdata['first_name'];
        $user_last_name = $postdata['last_name'];
        $user_description = $postdata['description'];
        $messages = '<ul>';
        $error_state = false;
        $user_data = array(
            'user_login' => $user_login,
            'user_email' => $user_email,
            'user_pass' => $user_password,
            'user_nicename' => $user_nickname,
            'display_name' => $user_first_name,
            
            );
            $user_id = wp_insert_user($user_data);
            if(!is_email($user_email)){
                $error_state = true;
                $messages .='<li> Email is empty or invalid.</li>';
                return wp_send_json([
                    'messages' => $messages,
                    'status' => 'failed'
                ]);
            }
            
            if (!is_wp_error($user_id)) {
                
                $messages .='<li>we have Created an account for you.</li>';
                return wp_send_json([
                    'messages' => $messages,
                    'status' => 'success'
                ]);
            
            } else {
            

                if (isset($user_id->errors['empty_user_login'])) {
                    $error_state = true;
                    $messages .= '<li>Cannot create a user with an empty login name.</li>';
                
                } 
                
                else if (isset($user_id->errors['existing_user_email'])) {
                    $error_state = true;
                    $messages .= '<li>Sorry, that email address is already used!</li>';
                    
                } 
                elseif (isset($user_id->errors['existing_user_login'])) {
                    $error_state = true;
                    $messages .= '<li>Sorry, that username already exists!</li>';
                } else {
                    $error_state = true;
                    $messages .= '<li>Error Occured please fill up the sign up form carefully.</li>';   
                
                }
            }

            $messages .='</ul>';

            if($error_state){
                return wp_send_json([
                    'messages' => $messages,
                    'status' => 'failed'
                ]);
            }
    exit;
    }
}


// $plugin = new AwpaRegister();