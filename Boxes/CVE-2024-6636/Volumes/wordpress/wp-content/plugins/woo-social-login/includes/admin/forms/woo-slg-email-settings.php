<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Settings Page Email Tab
 * 
 * The code for the plugins settings page Email tab
 * 
 * @package WooCommerce - Social Login
 * @since 1.8.2
 */

// Get email options 
$woo_slg_email_login_options = array(
    'woo_slg_enable_email',
    'woo_slg_enable_email_varification',
    'woo_slg_mail_subject',
    'woo_slg_mail_content',
    'woo_slg_enable_email_otp_varification',
    'woo_slg_mail_otp_subject',
    'woo_slg_mail_otp_content',
    'woo_slg_login_email_heading',
    'woo_slg_login_email_placeholder',
    'woo_slg_login_btn_text',
    'woo_slg_login_email_seprater_text',
    'woo_slg_login_email_position'
);

$positions = array('top' => esc_html__('Above Social buttons', 'wooslg'),
    'bottom' => esc_html__('Below Social buttons', 'wooslg'),
);

// Get option value
foreach ($woo_slg_email_login_options as $woo_slg_option_key) {
    $$woo_slg_option_key = get_option($woo_slg_option_key);
}

$woo_slg_login_email_position = !empty($woo_slg_login_email_position) ? $woo_slg_login_email_position : 'top'; 

$email_style = "";
if($woo_slg_enable_email == 'yes'){
	$email_style = 'style="display:block"';
}

?>

<!-- beginning of the Email login settings meta box -->
<div id="woo-slg-email" class="post-box-container">
    <div class="metabox-holder">
        <div class="meta-box-sortables ui-sortable">
            <div id="email-login" class="postbox-wrap">
                                
                <div class="inside">
                    <div class="woo-slg-social-icon-text-wrap">                    
                        <!-- Email login settings box title -->
                        <h3 class="hndle">
                            <img src="<?php echo esc_url(WOO_SLG_IMG_URL).'/tab-icon/email.svg'; ?>" alt="<?php esc_html_e('Email','wooslg');?>"/>
                            <span class="woo-slg-vertical-top"><?php esc_html_e('Login With Email Settings', 'wooslg'); ?></span>
                        </h3>
                    </div>
                    <table class="form-table">
                        <tbody>

                            <?php
                            // do action for add setting before email login settings
                            do_action('woo_slg_before_email_login_setting');
                            ?>

                            <tr>
                                <th scope="row">
                                    <label for="woo_slg_enable_email"><?php esc_html_e('Enable Login : ', 'wooslg'); ?></label>
                                </th>
                                <td>
                                 <div class="d-flex-wrap fb-avatra">
                                 <label for="woo_slg_enable_email" class="toggle-switch">
                                    <input type="checkbox" id="woo_slg_enable_email" name="woo_slg_enable_email" value="1" <?php echo ($woo_slg_enable_email == 'yes') ? 'checked="checked"' : ''; ?>/>
                                    <span class="slider"></span>
                                    </label>
                                    <p><?php echo esc_html__('Check this box, if you want to enable sign in / sign up with email only.', 'wooslg'); ?></p>
                                 </div>
                                </td>
                            </tr>
                        </tbody>
					</table>
					<table class="form-table email_section_hide" <?php echo $email_style; ?>>
						<tbody>
                            <tr>
                                <th scope="row">
                                    <label for="woo_slg_enable_email_varification"><?php esc_html_e('Enable Confirmation Email : ', 'wooslg'); ?></label>
                                </th>
                                <td>
                                <div class="d-flex-wrap fb-avatra">
                                <label for="woo_slg_enable_email_varification" class="toggle-switch">
                                    <input type="checkbox" id="woo_slg_enable_email_varification" name="woo_slg_enable_email_varification" value="1" <?php echo ($woo_slg_enable_email_varification == 'yes') ? 'checked="checked"' : ''; ?>/>
                                    <span class="slider"></span>
                                    </label>
                                    <p><?php echo esc_html__('Check this box, if you want to send confirmation email to user when they signup with email only.', 'wooslg'); ?></p>
                                </div>
                                </td>
                            </tr>
                            
                            <tr class="show_hide<?php echo ($woo_slg_enable_email_varification == 'yes') ? ' woo_slg_mail_settings_show_rows' : ' woo_slg_mail_settings_hide_rows'; ?>">
                                <th scope="row">
                                    <label for="woo_slg_mail_subject"><?php esc_html_e('Confirmation Email subject : ', 'wooslg'); ?></label>
                                </th>
                                <td>
                                    <input id="woo_slg_mail_subject" type="text" class="regular-text" name="woo_slg_mail_subject" value="<?php echo $woo_slg_model->woo_slg_escape_attr($woo_slg_mail_subject); ?>" placeholder="Activate your Account" />
                                    <p class="description"><?php echo sprintf(esc_html__('%s Enter the email subject. %s', 'wooslg'), '<desc>', '</desc>'); ?></p>
                                </td>
                            </tr>
                            <tr class="show_hide<?php echo ($woo_slg_enable_email_varification == 'yes') ? ' woo_slg_mail_settings_show_rows' : ' woo_slg_mail_settings_hide_rows'; ?>">
                                <th scope="row">
                                    <label for="woo_slg_mail_content"><?php esc_html_e('Confirmation Email body : ', 'wooslg'); ?></label>
                                </th>
                                <td>
                                    <?php
                                    wp_editor( stripslashes($woo_slg_mail_content), 'woo_slg_mail_content', array('wpautop' => true) ); ?>
                                    <p class="description" id="email_description_for_body"><?php echo sprintf(esc_html__('%s Enter the content for email body. %s {verify_link} %s - This tag used to create verify link. %s', 'wooslg'), '<desc>', '<br/><b><code>', '</code></b>', '</desc>'); ?></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div><!-- .inside -->

                <div class="inside email_section_hide" <?php echo $email_style; ?>">
                    <div class="woo-slg-social-icon-text-wrap">                    
                        <!-- Email login settings box title -->
                        <h3 class="hndle">
                            <span class="woo-slg-vertical-top"><?php esc_html_e('Configuration for OTP Confirmation Email', 'wooslg'); ?></span>
                        </h3>
                        <p><?php esc_html_e('Configure the settings for sending OTP verification emails to users upon each login with email.', 'wooslg'); ?></p>
                    </div>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="woo_slg_mail_otp_subject"><?php esc_html_e('Confirmation OTP Email subject : ', 'wooslg'); ?></label>
                                </th>
                                <td>
                                    <input id="woo_slg_mail_otp_subject" type="text" class="regular-text" name="woo_slg_mail_otp_subject" value="<?php echo $woo_slg_model->woo_slg_escape_attr($woo_slg_mail_otp_subject); ?>" placeholder="Activate your Account" />
                                    <br><p class="description"><?php echo sprintf(esc_html__('%s Enter the OTP email subject. %s {otp} %s - This tag used to generate OTP verify. %s {site_title} %s- This tag used for display site title. %s', 'wooslg'), '<desc>', '<br/><code>', '</code>','<br /><code>','</code>', '</desc>'); ?></p>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">
                                    <label for="woo_slg_mail_otp_content"><?php esc_html_e('Confirmation OTP Email body : ', 'wooslg'); ?></label>
                                </th>
                                <td>
                                    <?php
                                    wp_editor( stripslashes($woo_slg_mail_otp_content), 'woo_slg_mail_otp_content', array('wpautop' => true) ); ?>

                                    <p class="description" id="email_description_for_body_otp"><?php echo sprintf(esc_html__('%s Enter the content for OTP email body. %s {otp} %s - This tag used to generate OTP verify. %s {site_title} %s- This tag used for display site title. %s', 'wooslg'), '<desc>', '<br/><code>', '</code>','<br /><code>','</code>', '</desc>'); ?></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div><!-- .inside -->
                <div class="inside email_section_hide" <?php echo $email_style; ?>>
                    <div class="woo-slg-social-icon-text-wrap">                    
                        <!-- Email login settings box title -->
                        <h3 class="hndle">
                            <span class="woo-slg-vertical-top"><?php esc_html_e('Display Settings', 'wooslg'); ?></span>
                        </h3>
                    </div>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="woo_slg_login_email_position"><?php esc_html_e('Position : ', 'wooslg'); ?></label>
                                </th>
                                <td>
                                    <select class="woo_slg_login_email_position wslg-select" name="woo_slg_login_email_position" data-width="350px">
                                        <?php foreach ($positions as $key => $position) { ?>
                                            <option value="<?php print $key; ?>" <?php selected($woo_slg_login_email_position, $key, true); ?>><?php print $position; ?></option>
                                        <?php } ?>
                                    </select>
                                    <p class="description"><?php echo esc_html__('Select the postion where you want to display the login with email form.', 'wooslg'); ?></p>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">
                                    <label for="woo_slg_login_email_heading"><?php esc_html_e('Heading Title : ', 'wooslg'); ?></label>
                                </th>
                                <td>
                                    <input id="woo_slg_login_email_heading" type="text" class="regular-text" name="woo_slg_login_email_heading" value="<?php echo $woo_slg_model->woo_slg_escape_attr($woo_slg_login_email_heading); ?>" />
                                    <p class="description"><?php echo esc_html__('Enter the title for login with email.', 'wooslg'); ?></p>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">
                                    <label for="woo_slg_login_email_placeholder"><?php esc_html_e('Placeholder Text : ', 'wooslg'); ?></label>
                                </th>
                                <td>
                                    <input id="woo_slg_login_email_placeholder" type="text" class="regular-text" name="woo_slg_login_email_placeholder" value="<?php echo $woo_slg_model->woo_slg_escape_attr($woo_slg_login_email_placeholder); ?>" />
                                    <p class="description"><?php echo esc_html__('Enter the text for email placeholder.', 'wooslg'); ?></p>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">
                                    <label for="woo_slg_login_btn_text"><?php esc_html_e('Button Text : ', 'wooslg'); ?></label>
                                </th>
                                <td>
                                    <input id="woo_slg_login_btn_text" type="text" class="regular-text" name="woo_slg_login_btn_text" value="<?php echo $woo_slg_model->woo_slg_escape_attr($woo_slg_login_btn_text); ?>" />
                                    <p class="description"><?php echo esc_html__('Enter the text for submit button.', 'wooslg'); ?></p>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">
                                    <label for="woo_slg_login_email_seprater_text"><?php esc_html_e('Seprater Text : ', 'wooslg'); ?></label>
                                </th>
                                <td>
                                    <input id="woo_slg_login_email_seprater_text" type="text" class="regular-text" name="woo_slg_login_email_seprater_text" value="<?php echo $woo_slg_model->woo_slg_escape_attr($woo_slg_login_email_seprater_text); ?>" />
                                    <p class="description"><?php echo esc_html__('Enter the text for seprater line.', 'wooslg'); ?></p>
                                </td>
                            </tr>
                        </tbody>
					</table>
                </div><!-- .inside -->
                <div class="inside" style="margin-top:-20px;">
                    <table class="form-table">
                        <tbody>
                            <!-- Page Settings End --><?php
                            // do action for add setting after email settings
                            do_action('woo_slg_after_email_login_setting');
                            ?>
                            <tr>
                                <td colspan="2"><?php echo apply_filters('woo_slg_settings_submit_button', '<input class="button-primary woo-slg-save-btn" type="submit" name="woo-slg-set-submit" value="' . esc_html__('Save Changes', 'wooslg') . '" />'); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><!-- #email -->
        </div><!-- .meta-box-sortables ui-sortable -->
    </div><!-- .metabox-holder -->
</div><!-- #woo-slg-email -->