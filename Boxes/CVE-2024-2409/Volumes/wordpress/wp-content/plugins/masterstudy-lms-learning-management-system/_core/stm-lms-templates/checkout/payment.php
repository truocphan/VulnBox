<?php if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly ?>

<?php
/**
 * @var $user_id
 * @var $total
 */

?>

<div id="stm_lms_checkout">
    <div class="stm_lms_checkout__payment clearfix">
        <h3><?php printf(esc_html__('Total: %s', 'masterstudy-lms-learning-management-system'), STM_LMS_Helpers::display_price($total)); ?></h3>
		<?php STM_LMS_Templates::show_lms_template('checkout/payment_methods', compact('user_id', 'total')); ?>
        <a href="#"
           @click.prevent="purchase_courses()"
           class="btn btn-default stm_lms_pay_button"
           v-bind:class="{'loading' : loading}">
            <span><?php esc_html_e('Purchase', 'masterstudy-lms-learning-management-system') ?></span>
        </a>

        <transition name="slide-fade">
            <div class="stm-lms-message" v-bind:class="status" v-if="message">
                {{ message }}
            </div>
        </transition>

    </div>
</div>