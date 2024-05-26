<?php
defined('ABSPATH') || die();

class HashFormHelper {

    public static function get_fields_array($form_id) {
        $fields = HashFormFields::get_form_fields($form_id);

        $values['fields'] = array();

        if (empty($fields))
            return $values;

        foreach ((array) $fields as $field) {
            $field_array = HashFormFields::covert_field_obj_to_array($field);
            $values['fields'][] = $field_array;
        }

        $form_options_defaults = self::get_form_options_default();

        return array_merge($form_options_defaults, $values);
    }

    /* Sanitizes value and returns param value */

    public static function get_var($param, $sanitize = 'sanitize_text_field', $default = '') {
        $value = (($_GET && isset($_GET[$param])) ? wp_unslash($_GET[$param]) : $default);
        return self::sanitize_value($sanitize, $value);
    }

    public static function get_post($param, $sanitize = 'sanitize_text_field', $default = '', $sanitize_array = array()) {
        $value = (isset($_POST[$param]) ? wp_unslash($_POST[$param]) : $default);
        if (!empty($sanitize_array) && is_array($value)) {
            return self::sanitize_array($value, $sanitize_array);
        }
        return self::sanitize_value($sanitize, $value);
    }

    public static function sanitize_value($sanitize, &$value) {
        if (!empty($sanitize)) {
            if (is_array($value)) {
                $temp_values = $value;
                foreach ($temp_values as $k => $v) {
                    $value[$k] = self::sanitize_value($sanitize, $value[$k]);
                }
            } else {
                $value = call_user_func($sanitize, ($value ? htmlspecialchars_decode($value) : ''));
            }
        }

        return $value;
    }

    public static function get_unique_key($table_name, $column_name, $limit = 6) {
        $values = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
        $count = strlen($values);
        $count--;
        $key = '';
        for ($x = 1; $x <= $limit; $x++) {
            $rand_var = rand(0, $count);
            $key .= substr($values, $rand_var, 1);
        }

        $key = strtolower($key);
        $existing_keys = self::check_table_keys($table_name, $column_name);

        if (in_array($key, $existing_keys)) {
            self::get_unique_key($table_name, $column_name, $limit = 6);
        }

        return $key;
    }

    public static function check_table_keys($table_name, $column_name) {
        global $wpdb;
        $tbl_name = $wpdb->prefix . $table_name;
        $query = $wpdb->prepare("SELECT {$column_name} FROM {$tbl_name} WHERE id!=%d", 0);
        $results = $wpdb->get_results($query, ARRAY_A);
        return array_column($results, $column_name);
    }

    public static function is_admin_page($page = 'hashform') {
        $get_page = self::get_var('page', 'sanitize_title');
        if (is_admin() && $get_page === $page) {
            return true;
        }

        return false;
    }

    public static function is_preview_page() {
        $action = self::get_var('action', 'sanitize_title');
        return (is_admin() && ( $action == 'hashform_preview'));
    }

    public static function is_form_builder_page() {
        $action = self::get_var('hashform_action', 'sanitize_title');
        $builder_actions = self::get_form_builder_actions();
        return self::is_admin_page('hashform') && ( in_array($action, $builder_actions) );
    }

    public static function is_form_listing_page() {
        if (!self::is_admin_page('hashform')) {
            return false;
        }

        $action = self::get_var('hashform_action', 'sanitize_title');
        $builder_actions = self::get_form_builder_actions();
        return !$action || in_array($action, $builder_actions);
    }

    public static function get_form_builder_actions() {
        return array('edit', 'settings', 'style');
    }

    public static function start_field_array($field) {
        return array(
            'id' => $field->id,
            'default_value' => $field->default_value,
            'name' => $field->name,
            'description' => $field->description,
            'options' => $field->options,
            'required' => $field->required,
            'field_key' => $field->field_key,
            'field_order' => $field->field_order,
            'form_id' => $field->form_id,
        );
    }

    public static function show_search_box($atts) {
        $defaults = array(
            'placeholder' => '',
            'tosearch' => '',
            'text' => esc_html__('Search', 'hash-form'),
            'input_id' => '',
        );
        $atts = array_merge($defaults, $atts);
        $class = 'hf-search-fields-input';
        $input_id = $atts['input_id'] . '-search-input';
        ?>
        <div class="hf-search-fields">
            <span class="mdi mdi-magnify"></span>
            <input type="search" id="<?php echo esc_attr($input_id); ?>" name="s" value="<?php _admin_search_query(); ?>" placeholder="<?php echo esc_attr($atts['placeholder']); ?>" class="<?php echo esc_attr($class); ?>" data-tosearch="<?php echo esc_attr($atts['tosearch']); ?>" <?php if (!empty($atts['tosearch'])) { ?> autocomplete="off"<?php } ?> />
            <?php if (empty($atts['tosearch'])) submit_button($atts['text'], 'button-secondary', '', false, array('id' => 'search-submit')); ?>
        </div>
        <?php
    }

    public static function convert_date_format($date) {
        $timestamp = strtotime($date);

        $new_date = date('Y/m/d', $timestamp);
        $new_time = date('g:i a', $timestamp);

        return $new_date . ' ' . esc_html__('at', 'hash-form') . ' ' . $new_time;
    }

    public static function parse_json_array($array = array()) {
        $array = json_decode($array, true);
        $fields = array();
        foreach ($array as $val) {
            $name = $val['name'];
            $value = $val['value'];
            if (strpos($name, '[]') !== false) {
                $fields[str_replace('[]', '', $name)][] = $value;
            } else if (strpos($name, '[') !== false) {
                $ids = explode('[', str_replace(']', '', $name));
                $count = count($ids);

                switch ($count):
                    case 1:
                        $fields[$ids[0]] = $value;
                        break;
                    case 2:
                        $fields[$ids[0]][$ids[1]] = $value;
                        break;
                    case 3:
                        $fields[$ids[0]][$ids[1]][$ids[2]] = $value;
                        break;
                    case 4:
                        $fields[$ids[0]][$ids[1]][$ids[2]][$ids[3]] = $value;
                        break;
                    case 5:
                        $fields[$ids[0]][$ids[1]][$ids[2]][$ids[3]][$ids[4]] = $value;
                        break;
                endswitch;
            }else {
                $fields[$name] = $value;
            }
        }
        return $fields;
    }

    public static function process_form_array($form) {
        if (!$form) {
            return;
        }

        $new_values = array(
            'id' => $form->id,
            'form_key' => $form->form_key,
            'name' => $form->name,
            'description' => $form->description,
            'status' => $form->status,
        );

        if (is_array($form->options)) {
            $form_options = wp_parse_args($form->options, self::get_form_options_default());

            foreach ($form_options as $opt => $value) {
                $new_values[$opt] = $value;
            }
        }

        return $new_values;
    }

    public static function recursive_parse_args($args, $defaults) {
        $new_args = (array) $defaults;
        foreach ($args as $key => $value) {
            if (is_array($value) && isset($new_args[$key])) {
                $new_args[$key] = self::recursive_parse_args($value, $new_args[$key]);
            } else {
                $new_args[$key] = $value;
            }
        }
        return $new_args;
    }

    public static function get_form_options_checkbox_settings() {
        return array(
            'show_title' => 'off',
            'show_description' => 'off',
        );
    }

    public static function get_form_settings_checkbox_settings() {
        return array(
            'enable_ar' => 'off',
        );
    }

    public static function get_form_options_default() {
        return array(
            'show_title' => 'on',
            'show_description' => 'off',
            'title' => '',
            'description' => '',
            'submit_value' => esc_html__('Submit', 'hash-form'),
            'form_css_class' => '',
            'submit_btn_css_class' => '',
            'submit_btn_alignment' => 'left',
        );
    }

    public static function get_form_settings_default($name = '') {
        return array(
            'email_to' => '[admin_email]',
            'email_from' => '[admin_email]',
            'reply_to_email' => '',
            'email_from_name' => get_bloginfo('name'),
            'email_subject' => esc_html__('New Entry: ', 'hash-form') . esc_html($name),
            'email_message' => '#form_details',
            'enable_ar' => 'off',
            'from_ar' => '[admin_email]',
            'from_ar_name' => get_bloginfo('name'),
            'reply_to_ar' => '',
            'email_subject_ar' => esc_html__('Entry Submitted: ', 'hash-form') . esc_html($name),
            'email_message_ar' => esc_html__('Thank you for sending email. We will get back to you as soon as possible.', 'hash-form'),
            'confirmation_type' => 'show_message',
            'confirmation_message' => esc_html__('Form Submitted Successfully', 'hash-form'),
            'error_message' => esc_html__('Sorry, An error Occurred! Your form cannot be submitted.', 'hash-form'),
            'show_page_id' => '',
            'redirect_url_page' => '',
        );
    }

    public static function get_form_styles_default() {
        return array(
            'form_style' => '',
        );
    }

    public static function get_form_options_sanitize_rules() {
        return array(
            'show_title' => 'hashform_sanitize_checkbox',
            'show_description' => 'hashform_sanitize_checkbox',
            'title' => 'sanitize_text_field',
            'description' => 'sanitize_text_field',
            'submit_value' => 'sanitize_text_field',
            'form_css_class' => 'sanitize_text_field',
            'submit_btn_css_class' => 'sanitize_text_field',
            'submit_btn_alignment' => 'sanitize_text_field',
        );
    }

    public static function get_form_settings_sanitize_rules() {
        return array(
            'email_to' => 'sanitize_text_field',
            'email_from' => 'sanitize_text_field',
            'reply_to_email' => 'sanitize_text_field',
            'email_from_name' => 'sanitize_text_field',
            'email_subject' => 'sanitize_text_field',
            'email_message' => 'sanitize_text_field',
            'enable_ar' => 'hashform_sanitize_checkbox',
            'from_ar' => 'sanitize_text_field',
            'from_ar_name' => 'sanitize_text_field',
            'reply_to_ar' => 'sanitize_text_field',
            'email_subject_ar' => 'sanitize_text_field',
            'email_message_ar' => 'sanitize_text_field',
            'confirmation_type' => 'sanitize_text_field',
            'confirmation_message' => 'sanitize_text_field',
            'error_message' => 'sanitize_text_field',
            'show_page_id' => 'sanitize_text_field',
            'redirect_url_page' => 'sanitize_url',
            'condition_action' => array(
                'sanitize_text_field'
            ),
            'compare_from' => array(
                'sanitize_text_field'
            ),
            'compare_to' => array(
                'sanitize_text_field'
            ),
            'compare_condition' => array(
                'sanitize_text_field'
            ),
            'compare_value' => array(
                'sanitize_text_field'
            )
        );
    }

    public static function get_form_styles_sanitize_rules() {
        return array(
            'form_style' => 'sanitize_text_field',
            'form_style_template' => 'absint'
        );
    }

    public static function get_form_fields_default() {
        return array(
            'field_order' => 0,
            'field_key' => '',
            'required' => false,
            'type' => '',
            'description' => '',
            'options' => '',
            'name' => '',
        );
    }

    public static function get_countries() {
        $countries = array(
            esc_html__('Afghanistan', 'hash-form'),
            esc_html__('Aland Islands', 'hash-form'),
            esc_html__('Albania', 'hash-form'),
            esc_html__('Algeria', 'hash-form'),
            esc_html__('American Samoa', 'hash-form'),
            esc_html__('Andorra', 'hash-form'),
            esc_html__('Angola', 'hash-form'),
            esc_html__('Anguilla', 'hash-form'),
            esc_html__('Antarctica', 'hash-form'),
            esc_html__('Antigua and Barbuda', 'hash-form'),
            esc_html__('Argentina', 'hash-form'),
            esc_html__('Armenia', 'hash-form'),
            esc_html__('Aruba', 'hash-form'),
            esc_html__('Australia', 'hash-form'),
            esc_html__('Austria', 'hash-form'),
            esc_html__('Azerbaijan', 'hash-form'),
            esc_html__('Bahamas', 'hash-form'),
            esc_html__('Bahrain', 'hash-form'),
            esc_html__('Bangladesh', 'hash-form'),
            esc_html__('Barbados', 'hash-form'),
            esc_html__('Belarus', 'hash-form'),
            esc_html__('Belgium', 'hash-form'),
            esc_html__('Belize', 'hash-form'),
            esc_html__('Benin', 'hash-form'),
            esc_html__('Bermuda', 'hash-form'),
            esc_html__('Bhutan', 'hash-form'),
            esc_html__('Bolivia', 'hash-form'),
            esc_html__('Bonaire, Sint Eustatius and Saba', 'hash-form'),
            esc_html__('Bosnia and Herzegovina', 'hash-form'),
            esc_html__('Botswana', 'hash-form'),
            esc_html__('Bouvet Island', 'hash-form'),
            esc_html__('Brazil', 'hash-form'),
            esc_html__('British Indian Ocean Territory', 'hash-form'),
            esc_html__('Brunei', 'hash-form'),
            esc_html__('Bulgaria', 'hash-form'),
            esc_html__('Burkina Faso', 'hash-form'),
            esc_html__('Burundi', 'hash-form'),
            esc_html__('Cambodia', 'hash-form'),
            esc_html__('Cameroon', 'hash-form'),
            esc_html__('Canada', 'hash-form'),
            esc_html__('Cape Verde', 'hash-form'),
            esc_html__('Cayman Islands', 'hash-form'),
            esc_html__('Central African Republic', 'hash-form'),
            esc_html__('Chad', 'hash-form'),
            esc_html__('Chile', 'hash-form'),
            esc_html__('China', 'hash-form'),
            esc_html__('Christmas Island', 'hash-form'),
            esc_html__('Cocos (Keeling) Islands', 'hash-form'),
            esc_html__('Colombia', 'hash-form'),
            esc_html__('Comoros', 'hash-form'),
            esc_html__('Congo', 'hash-form'),
            esc_html__('Cook Islands', 'hash-form'),
            esc_html__('Costa Rica', 'hash-form'),
            esc_html__('C&ocirc;te d\'Ivoire', 'hash-form'),
            esc_html__('Croatia', 'hash-form'),
            esc_html__('Cuba', 'hash-form'),
            esc_html__('Curacao', 'hash-form'),
            esc_html__('Cyprus', 'hash-form'),
            esc_html__('Czech Republic', 'hash-form'),
            esc_html__('Denmark', 'hash-form'),
            esc_html__('Djibouti', 'hash-form'),
            esc_html__('Dominica', 'hash-form'),
            esc_html__('Dominican Republic', 'hash-form'),
            esc_html__('East Timor', 'hash-form'),
            esc_html__('Ecuador', 'hash-form'),
            esc_html__('Egypt', 'hash-form'),
            esc_html__('El Salvador', 'hash-form'),
            esc_html__('Equatorial Guinea', 'hash-form'),
            esc_html__('Eritrea', 'hash-form'),
            esc_html__('Estonia', 'hash-form'),
            esc_html__('Ethiopia', 'hash-form'),
            esc_html__('Falkland Islands (Malvinas)', 'hash-form'),
            esc_html__('Faroe Islands', 'hash-form'),
            esc_html__('Fiji', 'hash-form'),
            esc_html__('Finland', 'hash-form'),
            esc_html__('France', 'hash-form'),
            esc_html__('French Guiana', 'hash-form'),
            esc_html__('French Polynesia', 'hash-form'),
            esc_html__('French Southern Territories', 'hash-form'),
            esc_html__('Gabon', 'hash-form'),
            esc_html__('Gambia', 'hash-form'),
            esc_html__('Georgia', 'hash-form'),
            esc_html__('Germany', 'hash-form'),
            esc_html__('Ghana', 'hash-form'),
            esc_html__('Gibraltar', 'hash-form'),
            esc_html__('Greece', 'hash-form'),
            esc_html__('Greenland', 'hash-form'),
            esc_html__('Grenada', 'hash-form'),
            esc_html__('Guadeloupe', 'hash-form'),
            esc_html__('Guam', 'hash-form'),
            esc_html__('Guatemala', 'hash-form'),
            esc_html__('Guernsey', 'hash-form'),
            esc_html__('Guinea', 'hash-form'),
            esc_html__('Guinea-Bissau', 'hash-form'),
            esc_html__('Guyana', 'hash-form'),
            esc_html__('Haiti', 'hash-form'),
            esc_html__('Heard Island and McDonald Islands', 'hash-form'),
            esc_html__('Holy See', 'hash-form'),
            esc_html__('Honduras', 'hash-form'),
            esc_html__('Hong Kong', 'hash-form'),
            esc_html__('Hungary', 'hash-form'),
            esc_html__('Iceland', 'hash-form'),
            esc_html__('India', 'hash-form'),
            esc_html__('Indonesia', 'hash-form'),
            esc_html__('Iran', 'hash-form'),
            esc_html__('Iraq', 'hash-form'),
            esc_html__('Ireland', 'hash-form'),
            esc_html__('Israel', 'hash-form'),
            esc_html__('Isle of Man', 'hash-form'),
            esc_html__('Italy', 'hash-form'),
            esc_html__('Jamaica', 'hash-form'),
            esc_html__('Japan', 'hash-form'),
            esc_html__('Jersey', 'hash-form'),
            esc_html__('Jordan', 'hash-form'),
            esc_html__('Kazakhstan', 'hash-form'),
            esc_html__('Kenya', 'hash-form'),
            esc_html__('Kiribati', 'hash-form'),
            esc_html__('North Korea', 'hash-form'),
            esc_html__('South Korea', 'hash-form'),
            esc_html__('Kosovo', 'hash-form'),
            esc_html__('Kuwait', 'hash-form'),
            esc_html__('Kyrgyzstan', 'hash-form'),
            esc_html__('Laos', 'hash-form'),
            esc_html__('Latvia', 'hash-form'),
            esc_html__('Lebanon', 'hash-form'),
            esc_html__('Lesotho', 'hash-form'),
            esc_html__('Liberia', 'hash-form'),
            esc_html__('Libya', 'hash-form'),
            esc_html__('Liechtenstein', 'hash-form'),
            esc_html__('Lithuania', 'hash-form'),
            esc_html__('Luxembourg', 'hash-form'),
            esc_html__('Macao', 'hash-form'),
            esc_html__('Macedonia', 'hash-form'),
            esc_html__('Madagascar', 'hash-form'),
            esc_html__('Malawi', 'hash-form'),
            esc_html__('Malaysia', 'hash-form'),
            esc_html__('Maldives', 'hash-form'),
            esc_html__('Mali', 'hash-form'),
            esc_html__('Malta', 'hash-form'),
            esc_html__('Marshall Islands', 'hash-form'),
            esc_html__('Martinique', 'hash-form'),
            esc_html__('Mauritania', 'hash-form'),
            esc_html__('Mauritius', 'hash-form'),
            esc_html__('Mayotte', 'hash-form'),
            esc_html__('Mexico', 'hash-form'),
            esc_html__('Micronesia', 'hash-form'),
            esc_html__('Moldova', 'hash-form'),
            esc_html__('Monaco', 'hash-form'),
            esc_html__('Mongolia', 'hash-form'),
            esc_html__('Montenegro', 'hash-form'),
            esc_html__('Montserrat', 'hash-form'),
            esc_html__('Morocco', 'hash-form'),
            esc_html__('Mozambique', 'hash-form'),
            esc_html__('Myanmar', 'hash-form'),
            esc_html__('Namibia', 'hash-form'),
            esc_html__('Nauru', 'hash-form'),
            esc_html__('Nepal', 'hash-form'),
            esc_html__('Netherlands', 'hash-form'),
            esc_html__('New Caledonia', 'hash-form'),
            esc_html__('New Zealand', 'hash-form'),
            esc_html__('Nicaragua', 'hash-form'),
            esc_html__('Niger', 'hash-form'),
            esc_html__('Nigeria', 'hash-form'),
            esc_html__('Niue', 'hash-form'),
            esc_html__('Norfolk Island', 'hash-form'),
            esc_html__('Northern Mariana Islands', 'hash-form'),
            esc_html__('Norway', 'hash-form'),
            esc_html__('Oman', 'hash-form'),
            esc_html__('Pakistan', 'hash-form'),
            esc_html__('Palau', 'hash-form'),
            esc_html__('Palestine', 'hash-form'),
            esc_html__('Panama', 'hash-form'),
            esc_html__('Papua New Guinea', 'hash-form'),
            esc_html__('Paraguay', 'hash-form'),
            esc_html__('Peru', 'hash-form'),
            esc_html__('Philippines', 'hash-form'),
            esc_html__('Pitcairn', 'hash-form'),
            esc_html__('Poland', 'hash-form'),
            esc_html__('Portugal', 'hash-form'),
            esc_html__('Puerto Rico', 'hash-form'),
            esc_html__('Qatar', 'hash-form'),
            esc_html__('Reunion', 'hash-form'),
            esc_html__('Romania', 'hash-form'),
            esc_html__('Russia', 'hash-form'),
            esc_html__('Rwanda', 'hash-form'),
            esc_html__('Saint Barthelemy', 'hash-form'),
            esc_html__('Saint Helena, Ascension and Tristan da Cunha', 'hash-form'),
            esc_html__('Saint Kitts and Nevis', 'hash-form'),
            esc_html__('Saint Lucia', 'hash-form'),
            esc_html__('Saint Martin (French part)', 'hash-form'),
            esc_html__('Saint Pierre and Miquelon', 'hash-form'),
            esc_html__('Saint Vincent and the Grenadines', 'hash-form'),
            esc_html__('Samoa', 'hash-form'),
            esc_html__('San Marino', 'hash-form'),
            esc_html__('Sao Tome and Principe', 'hash-form'),
            esc_html__('Saudi Arabia', 'hash-form'),
            esc_html__('Senegal', 'hash-form'),
            esc_html__('Serbia', 'hash-form'),
            esc_html__('Seychelles', 'hash-form'),
            esc_html__('Sierra Leone', 'hash-form'),
            esc_html__('Singapore', 'hash-form'),
            esc_html__('Sint Maarten (Dutch part)', 'hash-form'),
            esc_html__('Slovakia', 'hash-form'),
            esc_html__('Slovenia', 'hash-form'),
            esc_html__('Solomon Islands', 'hash-form'),
            esc_html__('Somalia', 'hash-form'),
            esc_html__('South Africa', 'hash-form'),
            esc_html__('South Georgia and the South Sandwich Islands', 'hash-form'),
            esc_html__('South Sudan', 'hash-form'),
            esc_html__('Spain', 'hash-form'),
            esc_html__('Sri Lanka', 'hash-form'),
            esc_html__('Sudan', 'hash-form'),
            esc_html__('Suriname', 'hash-form'),
            esc_html__('Svalbard and Jan Mayen', 'hash-form'),
            esc_html__('Swaziland', 'hash-form'),
            esc_html__('Sweden', 'hash-form'),
            esc_html__('Switzerland', 'hash-form'),
            esc_html__('Syria', 'hash-form'),
            esc_html__('Taiwan', 'hash-form'),
            esc_html__('Tajikistan', 'hash-form'),
            esc_html__('Tanzania', 'hash-form'),
            esc_html__('Thailand', 'hash-form'),
            esc_html__('Timor-Leste', 'hash-form'),
            esc_html__('Togo', 'hash-form'),
            esc_html__('Tokelau', 'hash-form'),
            esc_html__('Tonga', 'hash-form'),
            esc_html__('Trinidad and Tobago', 'hash-form'),
            esc_html__('Tunisia', 'hash-form'),
            esc_html__('Turkey', 'hash-form'),
            esc_html__('Turkmenistan', 'hash-form'),
            esc_html__('Turks and Caicos Islands', 'hash-form'),
            esc_html__('Tuvalu', 'hash-form'),
            esc_html__('Uganda', 'hash-form'),
            esc_html__('Ukraine', 'hash-form'),
            esc_html__('United Arab Emirates', 'hash-form'),
            esc_html__('United Kingdom', 'hash-form'),
            esc_html__('United States', 'hash-form'),
            esc_html__('United States Minor Outlying Islands', 'hash-form'),
            esc_html__('Uruguay', 'hash-form'),
            esc_html__('Uzbekistan', 'hash-form'),
            esc_html__('Vanuatu', 'hash-form'),
            esc_html__('Vatican City', 'hash-form'),
            esc_html__('Venezuela', 'hash-form'),
            esc_html__('Vietnam', 'hash-form'),
            esc_html__('Virgin Islands, British', 'hash-form'),
            esc_html__('Virgin Islands, U.S.', 'hash-form'),
            esc_html__('Wallis and Futuna', 'hash-form'),
            esc_html__('Western Sahara', 'hash-form'),
            esc_html__('Yemen', 'hash-form'),
            esc_html__('Zambia', 'hash-form'),
            esc_html__('Zimbabwe', 'hash-form'),
        );

        sort($countries, SORT_LOCALE_STRING);
        return $countries;
    }

    public static function get_ages() {
        return array(
            esc_html__('Under 18', 'hash-form'),
            esc_html__('18-24', 'hash-form'),
            esc_html__('25-34', 'hash-form'),
            esc_html__('35-44', 'hash-form'),
            esc_html__('45-54', 'hash-form'),
            esc_html__('55-64', 'hash-form'),
            esc_html__('65 or Above', 'hash-form'),
            esc_html__('Prefer Not to Answer', 'hash-form'),
        );
    }

    public static function get_satisfaction() {
        return array(
            esc_html__('Very Unsatisfied', 'hash-form'),
            esc_html__('Unsatisfied', 'hash-form'),
            esc_html__('Neutral', 'hash-form'),
            esc_html__('Satisfied', 'hash-form'),
            esc_html__('Very Satisfied', 'hash-form'),
            esc_html__('N/A', 'hash-form'),
        );
    }

    public static function get_agreement() {
        return array(
            esc_html__('Strongly Disagree', 'hash-form'),
            esc_html__('Disagree', 'hash-form'),
            esc_html__('Neutral', 'hash-form'),
            esc_html__('Agree', 'hash-form'),
            esc_html__('Strongly Agree', 'hash-form'),
            esc_html__('N/A', 'hash-form'),
        );
    }

    public static function get_likely() {
        return array(
            esc_html__('Extremely Unlikely', 'hash-form'),
            esc_html__('Unlikely', 'hash-form'),
            esc_html__('Neutral', 'hash-form'),
            esc_html__('Likely', 'hash-form'),
            esc_html__('Extremely Likely', 'hash-form'),
            esc_html__('N/A', 'hash-form'),
        );
    }

    public static function get_importance() {
        return array(
            esc_html__('Not at all Important', 'hash-form'),
            esc_html__('Somewhat Important', 'hash-form'),
            esc_html__('Neutral', 'hash-form'),
            esc_html__('Important', 'hash-form'),
            esc_html__('Very Important', 'hash-form'),
            esc_html__('N/A', 'hash-form'),
        );
    }

    public static function get_options_presets() {
        return array(
            'hf-countries-opts' => array(
                'label' => esc_html__('Countries', 'hash-form'),
                'options' => self::get_countries()
            ),
            'hf-age-opts' => array(
                'label' => esc_html__('Age', 'hash-form'),
                'options' => self::get_ages()
            ),
            'hf-satisfaction-opts' => array(
                'label' => esc_html__('Satisfaction', 'hash-form'),
                'options' => self::get_satisfaction()
            ),
            'hf-importance-opts' => array(
                'label' => esc_html__('Importance', 'hash-form'),
                'options' => self::get_importance()
            ),
            'hf-agreement-opts' => array(
                'label' => esc_html__('Agreement', 'hash-form'),
                'options' => self::get_agreement()
            ),
            'hf-likely-opts' => array(
                'label' => esc_html__('Likely', 'hash-form'),
                'options' => self::get_likely()
            ),
        );
    }

    public static function get_user_id_param($user_id) {
        if (!$user_id || is_numeric($user_id)) {
            return $user_id;
        }
        $user_id = sanitize_text_field($user_id);
        if ($user_id == 'current') {
            $user_id = get_current_user_id();
        } else {
            if (is_email($user_id)) {
                $user = get_user_by('email', $user_id);
            } else {
                $user = get_user_by('login', $user_id);
            }
            if ($user) {
                $user_id = $user->ID;
            }
            unset($user);
        }
        return $user_id;
    }

    public static function get_ip() {
        $ip = self::get_ip_address();
        return $ip;
    }

    public static function get_ip_address() {
        $ip_options = array('REMOTE_ADDR');
        $ip = '';

        foreach ($ip_options as $key) {
            if (!isset($_SERVER[$key])) {
                continue;
            }
            $key = self::get_server_value($key);
            foreach (explode(',', $key) as $ip) {
                $ip = trim($ip); // Just to be safe.
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return sanitize_text_field($ip);
                }
            }
        }
        return sanitize_text_field($ip);
    }

    public static function get_server_value($value) {
        return isset($_SERVER[$value]) ? sanitize_text_field(wp_strip_all_tags(wp_unslash($_SERVER[$value]))) : '';
    }

    public static function count_decimals($num) {
        if (!is_numeric($num)) {
            return false;
        }
        $num = (string) $num;
        $parts = explode('.', $num);
        if (1 === count($parts)) {
            return 0;
        }
        return strlen($parts[count($parts) - 1]);
    }

    public static function print_message() {
        if (isset($_SESSION['hashform_message'])) {
            ?>
            <div class="hf-settings-updated">
                <span class="mdi mdi-check-circle"></span>
                <?php
                echo esc_html(sanitize_text_field($_SESSION['hashform_message']));
                unset($_SESSION['hashform_message']);
                ?>
            </div>
            <?php
        }
    }

    public static function sanitize_array($array = array(), $sanitize_rule = array()) {
        $new_args = (array) $array;

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $new_args[$key] = self::sanitize_array($value, isset($sanitize_rule[$key]) ? $sanitize_rule[$key] : 'sanitize_text_field');
            } else {
                if (isset($sanitize_rule[$key]) && !empty($sanitize_rule[$key]) && function_exists($sanitize_rule[$key])) {
                    $sanitize_type = $sanitize_rule[$key];
                    $new_args[$key] = $sanitize_type($value);
                } else {
                    $new_args[$key] = sanitize_text_field($value);
                }
            }
        }

        return $new_args;
    }

    public static function get_field_options_sanitize_rules() {
        return array(
            'grid_id' => 'sanitize_text_field',
            'name' => 'sanitize_text_field',
            'label' => 'sanitize_text_field',
            'label_position' => 'sanitize_text_field',
            'label_alignment' => 'sanitize_text_field',
            'hide_label' => 'hashform_sanitize_checkbox_boolean',
            'heading_type' => 'sanitize_text_field',
            'text_alignment' => 'sanitize_text_field',
            'content' => 'sanitize_text_field',
            'select_option_type' => 'sanitize_text_field',
            'image_size' => 'sanitize_text_field',
            'image_id' => 'hashform_sanitize_number',
            'spacer_height' => 'hashform_sanitize_number',
            'step' => 'hashform_sanitize_float',
            'min_time' => 'sanitize_text_field',
            'max_time' => 'sanitize_text_field',
            'upload_label' => 'sanitize_text_field',
            'max_upload_size' => 'hashform_sanitize_number',
            'extensions' => 'hashform_sanitize_allowed_file_extensions',
            'extensions_error_message' => 'sanitize_text_field',
            'multiple_uploads' => 'sanitize_text_field',
            'multiple_uploads_limit' => 'hashform_sanitize_number',
            'multiple_uploads_error_message' => 'sanitize_text_field',
            'date_format' => 'sanitize_text_field',
            'border_style' => 'sanitize_text_field',
            'border_width' => 'hashform_sanitize_number',
            'minnum' => 'hashform_sanitize_float',
            'maxnum' => 'hashform_sanitize_float',
            'classes' => 'sanitize_text_field',
            'auto_width' => 'sanitize_text_field',
            'placeholder' => 'sanitize_text_field',
            'format' => 'sanitize_text_field',
            'required_indicator' => 'sanitize_text_field',
            'options_layout' => 'sanitize_text_field',
            'field_max_width' => 'hashform_sanitize_number',
            'field_max_width_unit' => 'sanitize_text_field',
            'image_max_width' => 'hashform_sanitize_number',
            'image_max_width_unit' => 'sanitize_text_field',
            'field_alignment' => 'sanitize_text_field',
            'blank' => 'sanitize_text_field',
            'invalid' => 'sanitize_text_field',
            'rows' => 'hashform_sanitize_number',
            'max' => 'hashform_sanitize_number',
            'disable' => array(
                'line1' => 'sanitize_text_field',
                'line2' => 'sanitize_text_field',
                'city' => 'sanitize_text_field',
                'state' => 'sanitize_text_field',
                'zip' => 'hashform_sanitize_number',
                'country' => 'sanitize_text_field'
            )
        );
    }

    public static function get_all_forms_list_options() {
        $all_forms = array();
        $forms = HashFormBuilder::get_all_forms();
        foreach ($forms as $form) {
            $all_forms[$form->id] = $form->name;
        }
        return $all_forms;
    }

    public static function getSalt() {
        $salt = get_option('_hashform_security_salt');
        if (!$salt) {
            $salt = wp_generate_password();
            update_option('_hashform_security_salt', $salt, 'no');
        }
        return $salt;
    }

    public static function encrypt($text) {
        $key = static::getSalt();
        $cipher = 'AES-128-CBC';
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($text, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
        return base64_encode($iv . $hmac . $ciphertext_raw);
    }

    public static function decrypt($text) {
        $key = static::getSalt();
        $c = base64_decode($text);
        $cipher = 'AES-128-CBC';
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($c, $ivlen + $sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);

        if (hash_equals($hmac, $calcmac)) {
            return $original_plaintext;
        }
    }

}
