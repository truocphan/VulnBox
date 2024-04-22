<?php
wp_enqueue_script('jquery.cookie');
stm_lms_register_script('account/v1/fast_login');
wp_localize_script('stm-lms-account/v1/fast_login', 'stm_lms_fast_login', array(
    'translations' => array(
        'sign_up' => esc_html__('Sign up', 'masterstudy-lms-learning-management-system'),
        'sign_in' => esc_html__('Sign in', 'masterstudy-lms-learning-management-system'),
    )
));

stm_lms_register_style('account/v1/fast_login');
?>

<div id="stm_lms_fast_login" v-bind:class="{'is_login' : login, 'loading' : loading}">
    <div class="stm_lms_fast_login">

        <div class="stm_lms_fast_login__head">

            <h3 v-html="translations.sign_up" v-if="!login"></h3>
            <h3 v-html="translations.sign_in" v-else></h3>

            <div class="already_in" v-if="!login">
                <?php esc_html_e('Already registered?', 'masterstudy-lms-learning-management-system'); ?>
                <a href="#" v-html="translations.sign_in" @click.prevent="login = true"></a>
            </div>

            <div class="already_in" v-else>
                <?php esc_html_e('Not a member?', 'masterstudy-lms-learning-management-system'); ?>
                <a href="#" v-html="translations.sign_up" @click.prevent="login = false"></a>
            </div>

        </div>

        <div class="stm_lms_fast_login__body">

            <div class="stm_lms_fast_login_input">
                <h4><?php esc_html_e('Email', 'masterstudy-lms-learning-management-system'); ?></h4>
                <input type="email"
                       v-model="email"
                       placeholder="<?php esc_html_e('Enter your email', 'masterstudy-lms-learning-management-system') ?>"/>
            </div>

            <div class="stm_lms_fast_login_input">
                <h4><?php esc_html_e('Password', 'masterstudy-lms-learning-management-system'); ?></h4>
                <input type="password"
                       v-model="password"
                       placeholder="<?php esc_html_e('Enter your password', 'masterstudy-lms-learning-management-system') ?>"/>
            </div>

            <div class="stm_lms_fast_login_submit">

                <a href="#" class="btn btn-default" v-if="!login" @click.prevent="register()">
                    <?php esc_html_e('Sign up', 'masterstudy-lms-learning-management-system'); ?>
                </a>

                <a href="#" class="btn btn-default" v-else @click.prevent="logIn()">
                    <?php esc_html_e('Sign in', 'masterstudy-lms-learning-management-system'); ?>
                </a>

            </div>

        </div>

        <div class="stm-lms-message" v-bind:class="status" v-if="message">
            {{ message }}
        </div>

    </div>
</div>