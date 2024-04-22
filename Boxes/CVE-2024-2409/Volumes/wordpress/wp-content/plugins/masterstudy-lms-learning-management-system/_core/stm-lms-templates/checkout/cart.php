<?php if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly ?>

<?php
stm_lms_register_style('cart');
wp_enqueue_script('vue-resource.js');
stm_lms_register_script('cart');

$user = STM_LMS_User::get_current_user();
if(empty($user['id'])) {
    if(STM_LMS_Guest_Checkout::guest_enabled()) {
        STM_LMS_Templates::show_lms_template('checkout/guest-checkout');
    } else {
        STM_LMS_Templates::show_lms_template('checkout/not-logged-in');
    }
} else {
	$user_id = $user['id'];
	STM_LMS_Templates::show_lms_template('checkout/items', compact('user_id'));
}