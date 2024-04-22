<?php

use stmLms\Libraries\Paypal\PayPal;

$stm_paypal_settings_data = [];
$stm_paypal_settings_data['modes'] = PayPal::getMode();
$stm_paypal_settings_data['mode_selected'] = (isset($data['mode'])) ? $data['mode'] : PayPal::MODE_SANDBOX;

$data = $paypal->getData();

if (!isset($data['client_id']))
    $data['client_id'] = "";

if (!isset($data['client_secret']))
    $data['client_secret'] = "";

if (!isset($data['webhook_id']))
    $data['webhook_id'] = "";
?>

<h3><?php esc_html_e("PayPal Settings", "masterstudy-lms-learning-management-system") ?></h3>

<?php if (!stm_lms_is_https()): ?>
    <div class="notice notice-error">
        <p>
            <strong><?php esc_html_e('Subscription Paypal payment gateway requires full SSL support and enforcement during Checkout. Only test mode will work until this is solved.') ?></strong>
        </p>
    </div>
    <br>
<?php endif; ?>

<div class="stm-lms-settings data-undefined wpcfto-settings-payments">
    <div class="stm_metaboxes_grid">
        <div class="stm_metaboxes_grid__inner">
            <div class="container">
                <div class="wpcfto-tab active">
                    <div class="container container-constructed">
                        <div class="row">
                            <div class="column">
                                <form method="post">
                                    <div class="column-1 ">
                                        <label><?php esc_html_e("Client ID", "masterstudy-lms-learning-management-system") ?></label>
                                        <input type="text" name="StmPaypal[client_id]"
                                               value="<?php echo stm_lms_filtered_output($data['client_id']); ?>"
                                               placeholder="<?php esc_html_e("Client ID", "masterstudy-lms-learning-management-system") ?>">
                                        <p class="description">You need <a
                                                    href="https://developer.paypal.com/developer/applications"
                                                    target="_blank">Paypal Client ID</a> credentials.</p>
                                    </div>
                                    <div class="column-1 ">
                                        <label><?php esc_html_e("Client Secret", "masterstudy-lms-learning-management-system") ?></label>
                                        <input type="text" name="StmPaypal[client_secret]"
                                               value="<?php echo stm_lms_filtered_output($data['client_secret']); ?>"
                                               placeholder="<?php esc_html_e("Client Secret", "masterstudy-lms-learning-management-system") ?>"/>
                                        <p class="description">You need <a
                                                    href="https://developer.paypal.com/developer/applications"
                                                    target="_blank">Client Secret</a> credentials.</p>
                                    </div>
                                    <div class="column-1 ">
                                        <label><?php esc_html_e("Webhook ID", "masterstudy-lms-learning-management-system") ?></label>
                                        <input type="text" name="StmPaypal[webhook_id]"
                                               value="<?php echo stm_lms_filtered_output($data['webhook_id']); ?>"
                                               placeholder="<?php esc_html_e("Webhook ID", "masterstudy-lms-learning-management-system") ?>">
                                        <p class="description">You need <a
                                                    href="https://developer.paypal.com/developer/applications"
                                                    target="_blank"><?php esc_html_e("Webhook ID", "masterstudy-lms-learning-management-system") ?></a>
                                            credentials.</p>
                                    </div>
                                    <div class="column-1 ">
                                        <label><?php esc_html_e("Mode", "masterstudy-lms-learning-management-system") ?></label>
                                        <select name="StmPaypal[mode]" class="form-control">
                                            <?php foreach ($paypal->getMode() as $key => $mode): ?>
                                                <option <?php echo (isset($data['mode']) AND $data['mode'] == $key) ? "selected" : null; ?>
                                                        value="<?php echo stm_lms_filtered_output($key); ?>"><?php echo stm_lms_filtered_output($mode); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <p class="description">You need <a
                                                    href="https://www.udemy.com/user/edit-api-clients/"
                                                    target="_blank"><?php esc_html_e("Mode", "masterstudy-lms-learning-management-system") ?></a>
                                            credentials.</p>
                                    </div>

                                    <div class="column-1 ">
                                        <label><?php esc_html_e("Currency", "masterstudy-lms-learning-management-system") ?></label>
                                        <select name="StmPaypal[currency]" class="form-control">
                                            <?php foreach (PayPal::getCurrencies() as $key => $currency): ?>
                                                <option <?php echo (isset($data['currency']) AND $data['currency'] == $key) ? "selected" : null; ?>
                                                        value="<?php echo stm_lms_filtered_output($key); ?>"><?php echo stm_lms_filtered_output($currency); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <p class="description">You need <a
                                                    href="https://www.udemy.com/user/edit-api-clients/"
                                                    target="_blank"><?php esc_html_e("Mode", "masterstudy-lms-learning-management-system") ?></a>
                                            credentials.</p>
                                    </div>

                                    <div class="column-1">
                                        <label>
                                            <input style="margin: 0 5px 1px 0px;padding: 0!important;" type="checkbox"
                                                   name="StmPaypal[verifying_webhooks]" <?php echo ($data['verifying_webhooks'] == 1) ? "checked" : null; ?>
                                                   value="1">
                                            <?php esc_html_e("Verifying webhooks", "masterstudy-lms-learning-management-system") ?>
                                        </label>
                                    </div>
                                    <br>
                                    <div class="column-1 ">
                                        <label class="col-xs-12 col-sm-2 "><?php esc_html_e("Web hook url", "masterstudy-lms-learning-management-system") ?></label>
                                        <div class="col-xs-12 col-sm-6">
                                            <strong><?php echo \stmLms\Libraries\Paypal\WebHook::getWebHookUrl() ?></strong>
                                        </div>
                                    </div>
                                    <div class="stm_metaboxes_grid stm_metaboxes_grid_btn">
                                        <div class="stm_metaboxes_grid__inner">
                                            <button class="button load_button">
                                                <span><?php esc_html_e("Save Settings", "masterstudy-lms-learning-management-system") ?></span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>