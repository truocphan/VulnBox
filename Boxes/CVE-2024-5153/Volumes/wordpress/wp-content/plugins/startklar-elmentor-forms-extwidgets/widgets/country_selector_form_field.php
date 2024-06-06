<?php

namespace StartklarElmentorFormsExtWidgets;

use Elementor\Controls_Manager;
use ElementorPro\Modules\Forms\Classes;
use ElementorPro\Modules\Forms\Fields\Field_Base;
use ElementorPro\Plugin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class StartklarCountruySelectorFormField extends Field_Base
{
    public function __construct()
    {
        parent::__construct();

        add_action('wp_footer', [$this, 'drawStartklarJsScript']);
        add_action('wp_head', [$this, 'getPageHeadersData']);
        add_action('elementor/element/form/section_form_style/after_section_end', [$this, 'add_control_section_to_form'], 10, 2);
        wp_enqueue_script('select2_min', plugin_dir_url(__DIR__) . 'assets/country_selector/select2.min.js', array('jquery'), false, true);
        wp_enqueue_style("startklar_select2_styles", plugin_dir_url(__DIR__) . "assets/country_selector/select2.min.css");
        load_theme_textdomain('startklar-elmentor-forms-extwidgets', __DIR__ . '/../lang');
    }

    public function get_type()
    {
        return 'phone_number_prefix_selector_form_field';
    }

    public function get_name()
    {
        return __('Phone number prefix', 'startklar-elmentor-forms-extwidgets');
    }

    public function add_control_section_to_form($element, $args)
    {
        $element->start_controls_section(
            'dce_section_signature_buttons_style',
            [
                'label' => __('Phone number prefix selector', 'startklar-elmentor-forms-extwidgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $element->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'dce_typography_signature',
                'label' => __('Typography', 'startklar-elmentor-forms-extwidgets'),
                'selector' => '{{WRAPPER}} .select2-container .select2-selection, .select2-container .select2-results__option',
            ]
        );

        $element->add_control(
            'dce_background_color_signature',
            [
                'label' => __('Background Color', 'startklar-elmentor-forms-extwidgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .select2-container .select2-selection, .select2-container .select2-dropdown' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $element->add_control(
            'dce_text_color_signature',
            [
                'label' => __('Text Color', 'startklar-elmentor-forms-extwidgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .select2-container .select2-selection, .select2-container .select2-results__option' => 'color: {{VALUE}};',
                ],
            ]
        );

        $element->add_control(
            'dce_hide_scrollbar_signature',
            [
                'label' => __('Hide Scrollbar', 'startklar-elmentor-forms-extwidgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'startklar-elmentor-forms-extwidgets'),
                'label_off' => __('No', 'startklar-elmentor-forms-extwidgets'),
                'return_value' => 'Yes',
                'default' => 'No',
                'selectors' => [
                    '{{WRAPPER}}.select2-container .select2-selection, .select2-container--default .select2-results>.select2-results__options' => 'scrollbar-width: none; -ms-overflow-style: none; -webkit-overflow-scrolling: touch; overflow-x: hidden;',
                ],
            ]
        );

        $element->add_control(
            'dce_use_with_popup',
            [
                'label' => __('Use with popup', 'startklar-elmentor-forms-extwidgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'startklar-elmentor-forms-extwidgets'),
                'label_off' => __('No', 'startklar-elmentor-forms-extwidgets'),
                'return_value' => 'Yes',
                'default' => 'No',
                'selectors' => [
                    '{{WRAPPER}} .select2-container .select2-selection, .select2-container .select2-dropdown' => 'z-index:19999999999!important;',
                ],
            ]
        );

        $element->end_controls_section();
    }

    public function update_controls($widget)
    {
        $elementor = Plugin::elementor();
        $control_data = $elementor->controls_manager->get_control_from_stack($widget->get_unique_name(), 'form_fields');

        if (is_wp_error($control_data)) {
            return;
        }

        $content = file_get_contents(__DIR__ . '/../assets/country_selector/countries_arr.json');
        $country_arr = json_decode($content, true);

        $default_value_options = ["" => ""];

        foreach ($country_arr as $country) {
            if (!empty($country['phone_code'])) {
                $t_val = "(" . $country['phone_code'] . ") ";
                $country_name = $country['country_name_en'];
                $default_value_options[$country_name] = $t_val . __($country['country_name_en'],
                        "startklar-elmentor-forms-extwidgets");
            }
        }
        $temp = 0;

        $field_controls = [
            'phone_numb_format' => [
                'name' => 'phone_numb_format',
                'label' => esc_html__('Format +XX or 00XX', 'startklar-elmentor-forms-extwidgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'condition' => ['field_type' => $this->get_type()],
                'default' => '',
                'options' => [
                    '' => esc_html__('(+XX)', 'startklar-elmentor-forms-extwidgets'),
                    'clean_format' => esc_html__('+XX', 'startklar-elmentor-forms-extwidgets'),
                    'old_format' => esc_html__('(00XX)', 'startklar-elmentor-forms-extwidgets'),
                ],

                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
            ],


            'display_composition' => [
                'name' => 'display_composition',
                'label' => esc_html__('Display Composition', 'startklar-elmentor-forms-extwidgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'condition' => ['field_type' => $this->get_type()],
                'default' => 'name_flag',
                'options' => [
                    'name_flag' => esc_html__('name and flag', 'startklar-elmentor-forms-extwidgets'),
                    'flag_only' => esc_html__('flag only', 'startklar-elmentor-forms-extwidgets'),
                    'name_only' => esc_html__('name only', 'startklar-elmentor-forms-extwidgets'),
                    'code only' => esc_html__('code only', 'startklar-elmentor-forms-extwidgets'),
                ],

                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
            ],

            'default_value' => [
                'name' => 'default_value',
                'label' => esc_html__('Default Value', 'startklar-elmentor-forms-extwidgets'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => false,
                'condition' => ['field_type' => $this->get_type()],
                'default' => null,
                'options' => $default_value_options,
                'tab' => 'advanced',
                'inner_tab' => 'form_fields_advanced_tab',
                'tabs_wrapper' => 'form_fields_tabs',
            ],
        ];

        $control_data['fields'] = $this->inject_field_controls($control_data['fields'], $field_controls);
        $widget->update_control('form_fields', $control_data);
    }

    public function render($item, $item_index, $form)
    {
        $widget_styles = "";
        $all_settings = $form->get_settings_for_display();
        $settings = $all_settings["form_fields"][$item_index];
        $default_code = "";
        if (!empty($settings["default_value"])) {
            if (isset($settings["phone_numb_format"]) && $settings["phone_numb_format"] == "old_format") {
                $settings["default_value"] = preg_replace("/\(\+([0-9\s]+?)\)/i", "(00$1)", $settings["default_value"]);
            }
            if (isset($settings["phone_numb_format"]) && $settings["phone_numb_format"] == "clean_format") {
                $settings["default_value"] = preg_replace("/\(\+([0-9\s]+?)\)/i", "+$1", $settings["default_value"]);
            }
            $default_code = " data-default_val = '" . $settings["default_value"] . "' ";
        }

        $whitelist = array('phone_numb_format', 'display_composition');
        $settings = array_intersect_key($settings, array_flip($whitelist));
        $rend_html = '<select class="startklar_country_selector" name="form_fields[' . $item['custom_id'] . ']" data-options=\'' . json_encode($settings) . '\' ' . $default_code . '>';
        $rend_html .= '</select>';

        $widget_styles = self::generateCorrectiveStyles($all_settings);

        //FIXME: for some reason style tag does not get appended for the second selector.
        if (!empty($widget_styles)) {
            $rend_html .= '<style>' . $widget_styles . '</style>';
        }

        echo $rend_html;
    }

    public function getPageHeadersData()
    {
        load_theme_textdomain('startklar-elmentor-forms-extwidgets', __DIR__ . '/../languages');

        $rend_html = '<option selected value></option>';
        $content = file_get_contents(__DIR__ . '/../assets/country_selector/countries_arr.json');
        $country_arr = json_decode($content, true);
        $selected_country = false;

        if (!empty($item["field_value"])) {
            if (preg_match("/(\(.*\))?(.+)/ism", $item["field_value"], $matches)) {
                $selected_country = trim($matches[2]);
            }
        }

        foreach ($country_arr as $country) {
            $temp = "";

            if (!empty($country['phone_code'])) {
                $temp = "(" . $country['phone_code'] . ") ";
            }

            $t_val = $temp . "<span class='country_name'>" . __($country['country_name_en'], "startklar-elmentor-forms-extwidgets") . "</span>";

            $selected = '';

            if ($selected_country == $country['country_name_en']) {
                $selected = 'selected="selected"';
            }

            $icon_code = "";

            if (!empty($country['icon'])) {
                $icon_code = '" data-icon="' . plugin_dir_url(__DIR__) . 'assets/country_selector' . $country['icon'];
            }

            $rend_html .= '<option  data-country_en="' . esc_html($country['country_name_en']) . '" 
                value="' . $temp . $icon_code . '" ' . $selected . '>' . esc_html($t_val) . '</option>';
        }

        $rend_html = str_replace(["`", "�"], ["\`", "\�"], $rend_html);

        echo <<<EOT
        <script>
            window.phone_number_prefix_selector_options = `{$rend_html}`;
        </script>
        <style>
            .select2-container--default .select2-selection--single { border: 1px solid #69727d; border-radius: 3px;}
            .select2-container .select2-selection img,
            .select2-container .select2-results__option img { width: 50px; vertical-align: middle; padding: 0px;  -webkit-box-shadow: 2px 2px 7px 1px rgba(34, 60, 80, 0.2);
                            -moz-box-shadow: 2px 2px 7px 1px rgba(34, 60, 80, 0.2); box-shadow: 2px 2px 7px 1px rgba(34, 60, 80, 0.2); margin: 0px 12px 0 0;}
            .select2-container .selection  { padding: 0px; display: inherit;    width: 100%; } 
            .select2-container .select2-selection,
            .select2-container .select2-results__option{  padding: 10px 6px;}
            select.startklar_country_selector { width: 100%; } 
            .elementor-field-type-country_selector_form_field { display: block; }
            .select2.select2-container--default .select2-selection--single .select2-selection__arrow b {
                    border-color: #c7c7c7 transparent transparent transparent; border-style: solid; border-width: 16px 10px 0 10px;
                    height: 0; left: 50%;  margin-left: -22px; margin-top: 3px; position: absolute;  top: 50%;  width: 0; }
            .select2.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
                    border-color: transparent transparent #c7c7c7 transparent;   border-width: 0 10px 16px 10px;}
            .select2-selection--single   #select2-startklar_country_selector-container { height: 34px; } 
            .select2.select2-container--default .select2-search--dropdown .select2-search__field { margin:0; }
            .select2 .select2-selection.select2-selection--single { padding: 5px 0 0 0;  height: 48px;}  
            .select2-container > .select2-dropdown { width: 370px !important; }
            .select2-container .select2-dropdown.hide_counties_names .select2-results__option span.country_name { display: none; }
            .select2-container--default .select2-selection--single .select2-selection__rendered { all: unset !important;}
            .select2 .select2-selection.select2-selection--single { height: 100%; display: flex; align-items: center; padding: 0; }
            .select2.select2-container .select2-selection .select2-selection__rendered span {  padding-left: 14px; }
        </style>
EOT;
    }

    public function drawStartklarJsScript()
    {
        $site_abs_url = get_site_url();
        ?>
        <script>
            testjstartklarCountrySelectorQueryExist();

            function testjstartklarCountrySelectorQueryExist() {
                if (window.jQuery) {
                    jQuery(window).on('elementor/frontend/init', function () {
                        if (typeof (elementor) !== "undefined") {
                            const filter_name = `elementor_pro/forms/content_template/field/phone_number_prefix_selector_form_field`;
                            elementor.hooks.addFilter(filter_name, function (inputField, item, i, settings) {
                                const widget_id = item._id;

                                let setting = "";
                                settings.form_fields.forEach(sett_obj => {
                                    if (sett_obj._id === widget_id) {
                                        setting = sett_obj;
                                    }
                                });

                                let ret_str;

                                if (setting && (typeof (setting.display_composition) !== "undefined")) {
                                    const json_text = JSON.stringify({
                                        display_composition: setting.display_composition,
                                        phone_numb_format: setting.phone_numb_format
                                    });
                                    ret_str = `<select class="startklar_country_selector" name="${item.custom_id}"  data-options="` + json_text + `"></select>`;
                                } else {
                                    ret_str = `<select class="startklar_country_selector" name="${item.custom_id}"></select>`;
                                }

                                return ret_str;
                            }, 10, 4);
                        }

                        elementorFrontend.hooks.addAction('frontend/element_ready/form.default', function (element) {
                            let t_selector_options = "";
                            const phone_prefix_selectors = document.querySelectorAll('.startklar_country_selector');
                            const selectors = jQuery(element).find('.startklar_country_selector');

                            if (phone_prefix_selectors.length) {
                                if (typeof (window.phone_number_prefix_selector_options) !== "undefined") {
                                    const setting = jQuery(".startklar_country_selector").data("options");

                                    if (typeof (setting.phone_numb_format) !== "undefined" && setting.phone_numb_format === "old_format") {
                                        t_selector_options = window.phone_number_prefix_selector_options.replace(/\(\+([0-9\s]+?)\)/gi, '(00$1)');
                                    } else if (typeof (setting.phone_numb_format) !== "undefined" && setting.phone_numb_format === "clean_format") {
                                        t_selector_options = window.phone_number_prefix_selector_options.replace(/\(\+([0-9\s]+?)\)/gi, '+$1');
                                    } else {
                                        t_selector_options = window.phone_number_prefix_selector_options;
                                    }

                                    if (typeof (setting.display_composition) !== "undefined" && (setting.display_composition.toLowerCase().indexOf("flag") === -1)) {
                                        t_selector_options = t_selector_options.replace(/data-icon="[^"]*"/gi, '');
                                    }

                                    if (typeof (setting.display_composition) !== "undefined" && (setting.display_composition.toLowerCase().indexOf("name") === -1)) {
                                        jQuery(".startklar_country_selector").addClass("hide_counties_names");

                                    }
                                }

                                jQuery(".startklar_country_selector").html(t_selector_options);

                                jQuery('.startklar_country_selector').select2({
                                    templateSelection: startklarCountrySelectorformatText,
                                    templateResult: startklarCountrySelectorRsltformatText,
                                    selectionTitleAttribute: false
                                });

                                clearSelectTitle();

                                jQuery(document).on('change.select2', () => {
                                    clearSelectTitle();
                                });

                                jQuery('.select2-selection.select2-selection--single').addClass("elementor-field-textual");
                                jQuery('.select2-selection.select2-selection--single').addClass("elementor-field");

                                jQuery(document).on('select2:open', () => {
                                    document.querySelector('.select2-search__field').focus();
                                    if (jQuery(".startklar_country_selector.hide_counties_names").length) {
                                        jQuery(".select2-container .select2-dropdown").addClass("hide_counties_names");
                                    }
                                });

                                const p_form = jQuery(".startklar_country_selector").closest("form");

                                if (typeof p_form !== "undefined") {
                                    jQuery(p_form).on('submit_success', function () {
                                        jQuery('.startklar_country_selector', this).val('').trigger("change");
                                    });
                                }
                            }

                            window.addEventListener('load', function (event) {
                                selectors.each(function (index, selector) {
                                    const default_val = jQuery(selector).data("default_val");

                                    if (default_val) {
                                        selectOptionByCountry(default_val, selector);
                                    } else {
                                        getDetectedCountry().then(function (country_en) {
                                            selectOptionByCountry(country_en, selector);
                                        });
                                    }

                                    const parent = selector.parentElement;

                                    if (isNextFieldPhoneNumberField(parent)) {
                                        const phoneField = parent.nextElementSibling.children[0]
                                        phoneField.addEventListener('input', function() {
                                            debouncedHandleAutocomplete(selector);
                                        });
                                    }

                                });
                            });

                        });
                        
                        function isNextFieldPhoneNumberField(prefixField) {
                            const nextField = prefixField.nextElementSibling;

                            return nextField && nextField.classList.contains('elementor-field-type-tel');
                        }

                        function containsPotentialPrefix(phoneNumberField) {
                            const phoneNumberValue = phoneNumberField.value;

                            const potentialPrefixPatterns = [
                                /\(\+\d+\)/, // Matches patterns like "(+380)"
                                /\+\d+\s/    // Matches patterns like "+345 "
                            ];

                            return potentialPrefixPatterns.some(function(pattern) {
                                return pattern.test(phoneNumberValue);
                            });
                        }

                        function debounce(func, delay) {
                            let timeoutId;

                            return function() {
                                clearTimeout(timeoutId);

                                timeoutId = setTimeout(() => {
                                    func.apply(this, arguments);
                                }, delay);
                            };
                        }

                        const debouncedHandleAutocomplete = debounce(function(prefixField) {
                            const phoneNumberField = prefixField.parentElement.nextElementSibling.children[0];

                            if (containsPotentialPrefix(phoneNumberField)) {
                                const phoneNumberValue = phoneNumberField.value;
                                const potentialPrefix = phoneNumberValue.match(/\(\+\d+\)|\+\d+\s/);
                                const cleanedPrefix = potentialPrefix[0].replace("(", "").replace(")", " ");

                                if (cleanedPrefix) {
                                    const prefixOption = prefixField.querySelector('option[value="' + cleanedPrefix + '"]');

                                    if (prefixOption) {
                                        jQuery(prefixOption).prop('selected', true).parent().trigger('change');
                                    }

                                    phoneNumberField.value = phoneNumberValue.replace(potentialPrefix[0], '');
                                }
                            }

                        }, 300);
                    });

                } else {
                    setTimeout(testjstartklarCountrySelectorQueryExist, 100);
                }
            }

            function startklarCountrySelectorformatText(icon) {
                let str = "";

                if (typeof icon.element !== "undefined") {
                    const phone_code = /\+\d+/g.exec(icon.text);
                    const icon_src = jQuery(icon.element).data('icon');
                    let icon_code = '';

                    if (typeof icon_src !== "undefined" && icon_src.length) {
                        icon_code = '<img src="' + icon_src + '">';
                    }

                    if (typeof phone_code !== "undefined" && phone_code != null && phone_code.length) {
                        str = '<span>' + icon_code + phone_code[0] + '<span>';
                    }
                }
                return jQuery(str);
            }

            function startklarCountrySelectorRsltformatText(icon) {
                let str;

                if (typeof icon.element !== "undefined") {
                    const icon_src = jQuery(icon.element).data('icon');
                    let icon_code = '';

                    if (typeof icon_src !== "undefined" && icon_src.length) {
                        icon_code = '<img src="' + icon_src + '">';
                    }
                    str = '<span>' + icon_code + icon.text + '</span>';
                }

                return jQuery(str);
            }

            function getDetectedCountry() {
                return new Promise((resolve, reject) => {
                    jQuery.post("<?php echo $site_abs_url; ?>/wp-admin/admin-ajax.php?action=startklar_country_selector_process", function (data) {
                        const data_country = data["country"];
                        let country_en = null;

                        if (typeof data_country !== "undefined") {
                            jQuery('.startklar_country_selector > option').each(function () {
                                const optionCountry = jQuery(this).data('country_en');
                                if (optionCountry === data_country) {
                                    country_en = optionCountry;
                                    return false;
                                }
                            });
                        }

                        if (country_en !== null) {
                            resolve(country_en);
                        } else {
                            reject("Country code not found");
                        }
                    }, "json");
                });
            }

            function selectOptionByCountry(countryName, selector) {
                jQuery(selector).find('option').each(function () {
                    const optionCountry = jQuery(this).data('country_en');
                    if (optionCountry === countryName) {
                        jQuery(this).prop('selected', true).parent().trigger('change');

                        return false;
                    }
                });
            }

            function clearSelectTitle() {
                const select_items = jQuery(".select2.select2-container  .select2-selection.select2-selection--single > .select2-selection__rendered");

                if (select_items.length > 0) {
                    jQuery(select_items).each(function (index) {
                        const select_itm = jQuery(this);
                        const title = select_itm.attr("title");

                        if (typeof title !== 'undefined' && title) {
                            const rslt = title.match(/([^<]+)<span[^>]*>([^<]+)<\/span>/ism);

                            if (rslt !== null) {
                                const new_title = rslt[1] + " " + rslt[2];
                                select_itm.attr("title", new_title);
                            }
                        }
                    });
                }
            }
        </script>
        <?php
    }

    static function generateCorrectiveStyles($settings)
    {
        $widget_styles = "";


        if (!empty($settings["field_typography_font_weight"])) {
            $widget_styles .= "\n.select2.select2-container  .select2-selection .select2-selection__rendered span { font-weight: " .
                $settings["field_typography_font_weight"] . " ;} ";
        }

        if (!empty($settings["html_typography_line_height"])) {
            $widget_styles .= "\n.select2.select2-container  .select2-selection .select2-selection__rendered span { line-height: " .
                $settings["html_typography_line_height"]["size"] . $settings["html_typography_line_height"]["unit"] . " ;} ";
        }

        if (!empty($settings["dce_hide_scrollbar_signature"])) {
            $widget_styles .= "\n.select2-container--default .select2-results>.select2-results__options { scrollbar-width: none; -ms-overflow-style: none; -webkit-overflow-scrolling: touch; overflow-x: hidden; } ";
        }

        if (!empty($settings["dce_use_with_popup"])) {
            $widget_styles .= "\n.select2-container .select2-selection, .select2-container .select2-dropdown { z-index:19999999999!important; } ";
        }

        return $widget_styles;
    }
}