<?php
defined('ABSPATH') || die();

$captcha_lang = array('en' => 'English', 'af' => 'Afrikaans', 'am' => 'Amharic', 'ar' => 'Arabic', 'hy' => 'Armenian', 'az' => 'Azerbaijani', 'eu' => 'Basque', 'bn' => 'Bengali', 'bg' => 'Bulgarian', 'ca' => 'Catalan', 'zh-HK' => 'Chinese Hong Kong', 'zh-CN' => 'Chinese Simplified', 'zh-TW' => 'Chinese Traditional', 'hr' => 'Croatian', 'cs' => 'Czech', 'da' => 'Danish', 'nl' => 'Dutch', 'en-GB' => 'English/UK', 'et' => 'Estonian', 'fa' => 'Farsi/Persian', 'fil' => 'Filipino', 'fi' => 'Finnish', 'fr' => 'French', 'fr-CA' => 'French/Canadian', 'gl' => 'Galician', 'ka' => 'Georgian', 'de' => 'German', 'de-AT' => 'German/Austria', 'de-CH' => 'German/Switzerland', 'el' => 'Greek', 'gu' => 'Gujarati', 'he' => 'Hebrew', 'iw' => 'Hebrew', 'hi' => 'Hindi', 'hu' => 'Hungarian', 'is' => 'Icelandic', 'id' => 'Indonesian', 'it' => 'Italian', 'ja' => 'Japanese', 'kn' => 'Kannada', 'ko' => 'Korean', 'lo' => 'Laothian', 'lv' => 'Latvian', 'lt' => 'Lithuanian', 'ml' => 'Malayalam', 'ms' => 'Malaysian', 'mr' => 'Marathi', 'no' => 'Norwegian', 'pl' => 'Polish', 'pt' => 'Portuguese', 'pt-BR' => 'Portuguese/Brazilian', 'pt-PT' => 'Portuguese/Portugal', 'ro' => 'Romanian', 'ru' => 'Russian', 'sr' => 'Serbian', 'si' => 'Sinhalese', 'sk' => 'Slovak', 'sl' => 'Slovenian', 'es' => 'Spanish', 'es-419' => 'Spanish/Latin America', 'sw' => 'Swahili', 'sv' => 'Swedish', 'ta' => 'Tamil', 'te' => 'Telugu', 'th' => 'Thai', 'tr' => 'Turkish', 'uk' => 'Ukrainian', 'ur' => 'Urdu', 'vi' => 'Vietnamese', 'zu' => 'Zulu');
?>
<p><?php printf(esc_html__('%1$s requires a Site and Secret keys. Sign up for a %2$sfree %1$s key%3$s.', 'hash-form'), esc_html('reCAPTCHA'), '<a href="' . esc_url('https://www.google.com/recaptcha/') . '" target="_blank">', '</a>'); ?></p>
<p><?php printf(esc_html__('Tutorial to %1$sGenerate Site and Secret Keys%2$s', 'hash-form'), '<a href="https://hashthemes.com/articles/generate-site-key-and-secret-key-from-google-recaptcha/" target="_blank">', '</a>'); ?></p>

<div class="hf-form-container hf-grid-container">
    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('reCAPTCHA Type', 'hash-form'); ?></label>
        <select name="hashform_settings[re_type]" id="hf-re-type" data-condition="toggle">
            <option value="v2" <?php selected($settings['re_type'], 'v2'); ?>><?php esc_html_e('Checkbox (V2)', 'hash-form'); ?></option>
            <option value="v3" <?php selected($settings['re_type'], 'v3'); ?>><?php esc_html_e('v3', 'hash-form'); ?></option>
        </select>
    </div>

    <div class="hf-settings-row" data-condition-toggle="hf-re-type" data-condition-val="v2">
        <label class="hf-setting-label"><?php esc_html_e('v2 Site Key', 'hash-form'); ?></label>
        <input type="text" name="hashform_settings[pubkey_v2]" value="<?php echo esc_attr($settings['pubkey_v2']); ?>" />
    </div>

    <div class="hf-settings-row" data-condition-toggle="hf-re-type" data-condition-val="v2">
        <label class="hf-setting-label"><?php esc_html_e('v2 Secret Key', 'hash-form'); ?></label>
        <input type="text" name="hashform_settings[privkey_v2]" value="<?php echo esc_attr($settings['privkey_v2']); ?>" />
    </div>

    <div class="hf-settings-row" data-condition-toggle="hf-re-type" data-condition-val="v3">
        <label class="hf-setting-label"><?php esc_html_e('v3 Site Key', 'hash-form'); ?></label>
        <input type="text" name="hashform_settings[pubkey_v3]" value="<?php echo esc_attr($settings['pubkey_v3']); ?>" />
    </div>

    <div class="hf-settings-row" data-condition-toggle="hf-re-type" data-condition-val="v3">
        <label class="hf-setting-label"><?php esc_html_e('v3 Secret Key', 'hash-form'); ?></label>
        <input type="text" name="hashform_settings[privkey_v3]" value="<?php echo esc_attr($settings['privkey_v3']); ?>" />
    </div>

    <div class="hf-settings-row">
        <label class="hf-setting-label"><?php esc_html_e('reCAPTCHA Language', 'hash-form'); ?></label>
        <select name="hashform_settings[re_lang]">
            <option value="" <?php selected($settings['re_lang'], ''); ?>><?php esc_html_e('Browser Default', 'hash-form'); ?></option>
            <?php foreach ($captcha_lang as $lang => $lang_name) { ?>
                <option value="<?php echo esc_attr($lang); ?>" <?php selected($settings['re_lang'], $lang); ?>><?php echo esc_html($lang_name); ?></option>
            <?php } ?>
        </select>
    </div>

    <div id="hf-captcha-threshold-container" class="hf-settings-row" data-condition-toggle="hf-re-type" data-condition-val="v3">
        <label class="hf-setting-label"><?php esc_html_e('reCAPTCHA Threshold', 'hash-form'); ?></label>
        <p class="hf-description"><?php esc_html_e('A score of 0 is likely to be a bot and a score of 1 is likely not a bot. Setting a lower threshold will allow more bots, but it will also stop fewer real users.', 'hash-form'); ?></p>
        <div class="hf-grid-container">
            <div class="hf-setting-fields hashform-range-slider-wrap hf-grid-3">
                <div class="hashform-range-slider"></div>
                <input id="hf-re-threshold" class="hashform-range-input-selector" type="number" name="hashform_settings[re_threshold]" value="<?php echo esc_attr($settings['re_threshold']); ?>" min="0" max="1" step="0.1">
            </div>
        </div>
    </div>
</div>
