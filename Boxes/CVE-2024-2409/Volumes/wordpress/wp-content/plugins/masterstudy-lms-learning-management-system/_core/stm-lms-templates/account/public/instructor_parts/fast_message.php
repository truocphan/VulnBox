<?php
/**
 * @var $current_user
 */

if (is_user_logged_in()):
	wp_enqueue_script('vue.js');
	wp_enqueue_script('vue-resource.js');
	stm_lms_register_style('send_message');
	stm_lms_register_script('send_message'); ?>

	<br/>

    <div class="stm-lms-user_message_btn public_messages" id="stm_lms_send_fast_message">

        <a href="#" class="btn btn-default" @click.prevent="openForm()"><?php esc_html_e('Send Message', 'masterstudy-lms-learning-management-system'); ?></a>

        <div class="stm_lms_fast_message" v-bind:class="open">
            <div class="stm_lms_fast_message_to">
                <?php printf(__('To: <span>%s</span>', 'masterstudy-lms-learning-management-system'), $current_user['login']); ?>
            </div>

            <textarea v-model="message" placeholder="<?php esc_html_e('Message', 'masterstudy-lms-learning-management-system') ?>"></textarea>

            <div class="stm_lms_fast_message_btns">
                <a href="#"
                   class="btn btn-default"
                   v-bind:class="{'loading' : loading}"
                   @click.prevent="send_message('<?php echo intval($current_user['id']); ?>')">
                    <span><?php esc_html_e('Send', 'masterstudy-lms-learning-management-system'); ?></span>
                </a>
                <a href="#"
                   @click.prevent="closeForm()"
                   class="btn btn-default btn-cancel"><?php esc_html_e('Cancel', 'masterstudy-lms-learning-management-system'); ?></a>
            </div>

            <transition name="slide-fade">
                <div class="stm-lms-message" v-bind:class="status" v-if="response">
                    {{ response }}
                </div>
            </transition>
        </div>

    </div>

<?php endif; ?>
