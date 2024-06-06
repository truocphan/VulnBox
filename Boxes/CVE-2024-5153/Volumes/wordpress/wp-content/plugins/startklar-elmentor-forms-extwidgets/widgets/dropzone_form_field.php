<?php

namespace StartklarElmentorFormsExtWidgets;

use Elementor\Controls_Manager;
use ElementorPro\Modules\Forms\Classes;
use ElementorPro\Plugin;

if (!defined('ABSPATH')) {
    exit;
}

class StartklarDropzoneFormField extends \ElementorPro\Modules\Forms\Fields\Field_Base
{
    public $settings_names = ["files_amount", 'allowed_file_types_for_upload', 'maximum_upload_file', 'path_type', 'button_message'];

    public function __construct()
    {
        parent::__construct();

        load_theme_textdomain('startklar-elmentor-forms-extwidgets', __DIR__ . '/../lang');
        add_filter("elementor_pro/forms/sanitize/drop_zone_form_field", [$this, 'sanitize_field'], 10, 2);
        add_action('wp_footer', [$this, 'drawDZJsScript']);
        add_action('wp_head', [$this, 'addDropzoneStyles']);
        add_action('elementor/preview/init', [$this, 'editor_inline_JS']);
        wp_enqueue_script('drop_zone', plugin_dir_url(__DIR__) . 'assets/dropzone/dropzone.min.js', array('jquery'), false, true);
        add_action('wp_footer', function () { ?>
            <script>Dropzone.autoDiscover = false; </script> <?php }, 1000);
        parent::__construct();
    }

    public function get_type()
    {
        return 'drop_zone_form_field';
    }

    public function get_name()
    {
        return __('DropZone', 'startklar-elmentor-forms-extwidgets');
    }

    public function update_controls($widget)
    {
        $elementor = Plugin::elementor();
        $control_data = $elementor->controls_manager->get_control_from_stack($widget->get_unique_name(), 'form_fields');
        if (is_wp_error($control_data)) {
            return;
        }
        $field_controls = [
            'files_amount' => [
                'name' => 'files_amount',
                'label' => __('Files amount', 'startklar-elmentor-forms-extwidgets'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 1,
                'tabs_wrapper' => 'form_fields_tabs',
                'dynamic' => ['active' => \false],
                'condition' => ['field_type' => $this->get_type()]
            ],

            'allowed_file_types_for_upload' => [
                'name' => 'allowed_file_types_for_upload',
                'label' => esc_html__('Allowed file types for upload (e.g. *.jpg, *.pdf)', 'startklar-elmentor-forms-extwidgets'),
                'type' => Controls_Manager::TEXT,
                'condition' => ['field_type' => $this->get_type()],
                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
                'inner_tab' => 'form_fields_content_tab',
                'default' => "*.jpg, *.pdf",
            ],

            'maximum_upload_file' => [
                'name' => 'maximum_upload_file',
                'label' => esc_html__('Maximum upload file size (e.g. 2.5M)', 'startklar-elmentor-forms-extwidgets'),
                'type' => Controls_Manager::TEXT,
                'condition' => ['field_type' => $this->get_type()],
                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
                'inner_tab' => 'form_fields_content_tab',
                'default' => "10M",
            ],


            'path_type' => [
                'name' => 'path_type',
                'label' => esc_html__('Forms data path type (output forms data)', 'startklar-elmentor-forms-extwidgets'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'url' => 'Url',
                    'file_name_only' => 'File name only',
                    'abs_path' => 'Absolute path'
                ],
                'toggle' => \false, 'default' => 'ajax',

                'condition' => ['field_type' => $this->get_type()],
                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
                'inner_tab' => 'form_fields_content_tab',
                'default' => "",
            ],

            'button_message' => [
                'name' => 'button_message',
                'label' => esc_html__('Button Message', 'startklar-elmentor-forms-extwidgets'),
                'type' => Controls_Manager::TEXT,
                'condition' => ['field_type' => $this->get_type()],
                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
                'inner_tab' => 'form_fields_content_tab',
                'default' => "",
            ]

        ];
        $control_data['fields'] = $this->inject_field_controls($control_data['fields'], $field_controls);
        $widget->update_control('form_fields', $control_data);
    }

    /**
     * @param      $item
     * @param      $item_index
     * @param Form $form
     */
    public function render($item, $item_index, $form)
    {
        $settings = $form->get_settings_for_display();
        $settings = $settings["form_fields"][$item_index];

        foreach ($item as $index => $it) {
        }
        $serialize_arr = [];
        foreach ($this->settings_names as $indx => $setting_name) {
            if (!empty($settings[$setting_name])) {
                switch ($setting_name) {
                    case "allowed_file_types_for_upload":
                        $temp = str_replace(";", ",", $settings[$setting_name]);
                        $temp_arr = explode(",", $temp);
                        foreach ($temp_arr as $index => $element) {
                            $element = strtolower($element);
                            $element = trim($element, " .*");
                            if (strpos($element, ".") !== 0) {
                                $temp_arr[$index] = "." . $element;
                            }
                        }
                        $serialize_arr[$setting_name] = implode(", ", $temp_arr);
                        break;
                    case "maximum_upload_file":
                        $temp = trim($settings[$setting_name]);
                        if (preg_match("/([0-9,\.]+)\s*([km]?)/ism", $temp, $matches)) {
                            $temp = str_replace(",", ".", $matches[1]);
                            $temp = (float)$temp;
                            $mult = strtolower($matches[2]);

                            switch ($mult) {
                                case "m":
                                    $temp = $temp * 1; //MBytes
                                    break;
                                case "k":
                                    $temp = $temp / 1000;// kBytes
                                    break;
                                default:
                                    $temp = $temp / 1000000; // bytes
                                    break;
                            }
                            $temp = round($temp, 3);
                            $serialize_arr[$setting_name] = $temp;
                        }
                        break;
                    default:
                        $serialize_arr[$setting_name] = $settings[$setting_name];
                        break;
                }
            }
        }

        $dropzone_hash = substr(md5(time()), 0, 10);
        $random_number = mt_rand(10000, 99999); // Generating a random 5-digit number
        $dropzone_hash .= $random_number; // Concatenating the random number to the hash
        $dropzone_hash = $item['custom_id'];
        $serialize_arr["dropzone_hash"] = $dropzone_hash;
        $serialize_arr = json_encode($serialize_arr);

        $user = wp_get_current_user();

        if (in_array('administrator', $user->roles)) {
            $admin_mode = 1;
        }
        $user_id = $user->ID;
        $form->add_render_attribute('signature-wrapper' . $item_index,
            'id', 'dce-signature-wrapper-' . $form->get_attribute_name($item));

        echo <<<EOT
        <div class="wrap_dropzone_container">
            <div class="dropzone dropzone_container" data-serialize-arr='{$serialize_arr}'></div>
            <input class="dropzone_hash"  type="hidden" name="form_fields[{$item['custom_id']}]" value='{$serialize_arr}'></input>
        </div>
EOT;
    }

    public function addDropzoneStyles()
    {
        echo <<<EOT
        <style>
            .dropzone_container { width:100%;  padding: 0; 
                    box-shadow: inset 0 0 0 1px rgb(0 0 0 / 3%), inset 0px 4px 3px -2px rgb(0 0 0 / 7%);}
            .dropzone_container {  background: white;  border-radius: 5px;    border: 2px dashed rgb(0, 135, 247);  border-image: none;  
                    margin-left: auto;    margin-right: auto;}
            .dropzone_container .dz-message .dz-button { padding: 10px;   color: #d9d9d9;  font-weight: 600;}
            .dropzone_container .dz-preview.dz-image-preview {    background: #fff;   -webkit-box-shadow: 4px 4px 8px 0px rgb(34 60 80 / 20%); 
                -moz-box-shadow: 4px 4px 8px 0px rgba(34, 60, 80, 0.2);     box-shadow: 4px 4px 8px 0px rgb(34 60 80 / 20%);  border-radius: 20px;}
            .elementor-field-group.elementor-column .dropzone_container {  background: white;  border-radius: 5px;   border: 2px dashed rgb(0, 135, 247);  border-image: none;
                        margin-left: auto;   margin-right: auto;  width: 100%;  padding: 0;
                        box-shadow: inset 0 0 0 1px rgb(0 0 0 / 3%), inset 0px 4px 3px -2px rgb(0 0 0 / 7%); min-height: 150px;}
            .dropzone_container.dz-clickable,  .dropzone.dz-clickable {    cursor: pointer;}
            .wrap_dropzone_container {  width: 100%; } 
           
           .elementor-field-group.elementor-column .dropzone_container .dz-message .dz-button {  padding: 20px; color: #d9d9d9; font-weight: 600;  border-radius: 5px; color: #0087F7;
                                        box-shadow: inset 0 0 0 1px rgb(0 0 0 / 3%), inset 0px 4px 3px -2px rgb(0 0 0 / 7%); cursor: pointer; background: none;}
           .elementor-field-group.elementor-column  .dropzone_container .dz-message  {  text-align: center;    font-size: 14px;    line-height: 14px;    margin-top: 33px; position: relative; }
           .elementor-field-group.elementor-column  .dropzone_container  .dz-message button:hover:after { width: 100%; }
           .elementor-field-group.elementor-column  .dropzone_container  .dz-message button { position: relative; font-size: 15px; font-family: arial; }
           .elementor-field-group.elementor-column  .dropzone_container  .dz-message button:after { content: ""; position: absolute; left: 0; top: 0; height: 100%; 
                    width: 0;background: rgba(0,0,0,.05); z-index: 1; transition: width .2s ease-in-out;}
                    
           .elementor-field-group.elementor-column  .dropzone .dz-preview .dz-remove { text-decoration: none; }
           
           .dz-preview   .dz-remove {height: 32px; width: 32px; margin: 0 auto; position: absolute; bottom: -28px; left: calc(50% - 15px); z-index: 10000; cursor:pointer !important;}
            .dz-preview   .dz-remove i {position: absolute; left: 0px; top: 0px; width: 20px; height: 20px;opacity: 0.5; cursor:pointer !important;}
           .dz-preview   .dz-remove i:hover { opacity: 1;}
           .dz-preview   .dz-remove i:before, .dz-preview .dz-remove i:after { position: absolute;  left: 15px;  content: ' ';  height: 20px;  width: 2px;  background-color: #333;}
           .dz-preview   .dz-remove i:before {  transform: rotate(45deg);}
           .dz-preview   .dz-remove i:after {  transform: rotate(-45deg);}

        </style>
EOT;
    }

    public function editor_inline_JS()
    {
        add_action('wp_footer', function () {
            $this->drawDZJsScript();
        });
    }

    public function sanitize_field($value, $field)
    {
        $ret_arr = [];
        $user = wp_get_current_user();

        if (!isset($user) || !is_object($user) || !is_a($user, 'WP_User') || !$user->ID) {
            $user_id = 0;
        } else {
            $user_id = $user->ID;
        }

        if (in_array('administrator', $user->roles)) {
            $admin_mode = 1;
        }


        $options = json_decode($value, true);

        if (!empty($options["dropzone_hash"])) {
            $dropzone_hash = $options["dropzone_hash"];
        } else {
            if (is_string($value) && !empty($value)) {
                $dropzone_hash = $value;
            }
        }

        $uploads_dir_info = wp_upload_dir();
        $target_folder = $uploads_dir_info['basedir'] . "/elementor/forms/" . $user_id . "/temp/" . $options["dropzone_hash"] . "/*";
        $files = glob($target_folder);

        if (count($files)) {
            foreach ($files as $file) {
                $save_path = "";
                $file_name = basename($file);
                $encoded_filename = rawurlencode($file_name);
                $rel_path = "/elementor/forms/" . $user_id . "/" . $encoded_filename;
                $new_path = $uploads_dir_info['basedir'] . $rel_path;
                $add_suffix = 1;

                while (file_exists($new_path)) {
                    $path_parts = pathinfo($new_path);

                    if (preg_match("/.+(\(\d+\))$/ism", $path_parts['filename'], $matches)) {
                        $path_parts['filename'] = str_replace($matches[1], "", $path_parts['filename']);
                    }
                    $new_path = $path_parts['dirname'] . "/" . $path_parts['filename'] . "(" . $add_suffix . ")." . $path_parts['extension'];
                    $rel_path = dirname($rel_path) . "/" . basename($new_path);
                    $add_suffix++;
                }

                copy($file, $new_path);

                $temp = 0;

                if (!empty($options["path_type"]) && in_array($options["path_type"], ["url", "file_name_only", "abs_path"])) {
                    $path_type = $options["path_type"];
                } else {
                    $path_type = "url";
                }

                switch ($path_type) {
                    case "file_name_only":
                        $save_path = basename($new_path);
                        break;

                    case "abs_path":
                        $save_path = $new_path;
                        break;

                    case "url":
                    default:
                        $save_path = $uploads_dir_info['baseurl'] . $rel_path;
                        break;
                }

                $ret_arr[] = $save_path;
            }

            $temp_folder = $uploads_dir_info['basedir'] . "/elementor/forms/" . $user_id . "/temp/" . $options["dropzone_hash"];
            require_once(ABSPATH . "wp-admin/includes/class-wp-filesystem-base.php");
            require_once(ABSPATH . "wp-admin/includes/class-wp-filesystem-direct.php");
            $WP_Filesystem_Direct = new \WP_Filesystem_Direct(null);
            @$WP_Filesystem_Direct->delete($temp_folder, true);
        }

        return implode(", ", $ret_arr);
    }

    public function drawDZJsScript()
    {
        ?>
        <script>
            testjStartklarDropZoneJQueryExist();

            function testjStartklarDropZoneJQueryExist() {
                if (window.jQuery) {
                    jQuery(window).on('elementor/frontend/init', function () {
                        if (typeof (elementor) !== "undefined") {
                            elementor.hooks.addFilter('elementor_pro/forms/content_template/field/drop_zone_form_field', function (inputField, item, i, settings) {
                                const itemClasses = item.css_classes;
                                const fieldName = 'form_field_';
                                const dropzone_hash = MD5(Date.now()).substring(0, 10);
                                let button_message;

                                if (typeof (item["button_message"]) == "undefined" || !item["button_message"]) {
                                    button_message = "Drop files here to upload";
                                } else {
                                    button_message = item["button_message"];
                                }

                                button_message = ' data-serialize-arr=\'{"button_message":"' + button_message + '"}\' ';
                                const ret_str = `
                            <div class="wrap_dropzone_container">
                                <div class="dropzone dropzone_container" ` + button_message + ` ></div>
                                <input class="dropzone_hash"  type="hidden" name="${fieldName}" value="${dropzone_hash}"></input>
                            </div>`;

                                return ret_str;
                            }, 10, 4);
                        }

                        elementorFrontend.hooks.addAction('frontend/element_ready/form.default', function () {
                            window.loop_cntr = 0;

                            jQuery('head').append('<link rel="stylesheet" href="<?php echo plugin_dir_url(__DIR__) ?>assets/dropzone/dropzone.min.css" type="text/css" />');
                            Dropzone.autoDiscover = false;
                            searchDropZoneContainer();
                        });
                    });
                } else {
                    setTimeout(testjStartklarDropZoneJQueryExist, 100);
                }
            }

            function searchDropZoneContainer() {
                if (window.loop_cntr > 100) {
                    return;
                }

                if (jQuery(".dropzone_container").length) {
                    jQuery(".dropzone_container").each(function (index) {
                        if (!jQuery(this).hasClass("dz-clickable")) {
                            let settings = jQuery(this).data("serializeArr");
                            const dropzoneHash = settings.dropzone_hash;

                            const default_settings = {
                                allowed_file_types_for_upload: "*.jpg, *.pdf",
                                files_amount: 1,
                                maximum_upload_file: "10",
                                uploaded_files_storage_path: "",
                                button_message: "Ziehen Sie Ihre Dateien hierher<br/> und legen Sie sie dort ab oder klicken Sie,<br/> um eine Datei auszuwählen"
                            }
                            settings = Object.assign(default_settings, settings);

                            jQuery(this).dropzone({
                                url: "<?php  echo get_site_url(); ?>/wp-admin/admin-ajax.php?action=startklar_drop_zone_upload_process",
                                addRemoveLinks: true,
                                dictDefaultMessage: settings["button_message"],
                                dictRemoveFile: '<i class="dashicons dashicons-no"></i>',
                                dictCancelUpload: '<i class="dashicons dashicons-no"></i>',
                                init: function () {
                                    dzClosure = this;
                                    this.on("sending", function (file, xhr, formData) {
                                        function appendUniqueHash() {
                                            let hashKey;
                                            do {
                                                const randomSuffix = Math.floor(Math.random() * 1000);
                                                hashKey = `hash_${randomSuffix}`;
                                            } while (formData.has(hashKey));
                                            formData.append(hashKey, dropzoneHash);
                                        }

                                        appendUniqueHash();
                                    });
                                },
                                removedfile: function (file) {
                                    const fileName = file.name;
                                    const input_val = jQuery(this.element).closest("form").find("input.dropzone_hash").val();
                                    const json_obj = JSON.parse(input_val);
                                    const hash = json_obj["dropzone_hash"];

                                    jQuery.post("<?php  echo get_site_url(); ?>/wp-admin/admin-ajax.php?action=startklar_drop_zone_upload_process",
                                        {mode: "remove", hash: hash, fileName: fileName},
                                        function (data) {
                                        }
                                    );

                                    if (file.previewElement != null && file.previewElement.parentNode != null) {
                                        file.previewElement.parentNode.removeChild(file.previewElement);
                                    }

                                    return this._updateMaxFilesReachedClass();
                                },

                                maxFilesize: settings["maximum_upload_file"],
                                maxFiles: settings["files_amount"],
                                acceptedFiles: settings["allowed_file_types_for_upload"]
                            });
                        }

                        const p_form = this.closest("form");
                        if (typeof p_form !== "undefined") {
                            jQuery(p_form).on('submit_success', function () {
                                jQuery(this).find(".dropzone_container").each(function (index, element) {
                                    const objDZ = Dropzone.forElement(this);
                                    objDZ.emit("reset");
                                    objDZ.removeAllFiles(true);
                                })
                            });
                        }
                    });
                } else {
                    setTimeout(searchDropZoneContainer, 100);
                    window.loop_cntr++;
                }
            }

            const MD5 = function (d) {
                result = M(V(Y(X(d), 8 * d.length)));
                return result.toLowerCase()
            };

            function M(d) {
                for (var _, m = "0123456789ABCDEF", f = "", r = 0; r < d.length; r++) _ = d.charCodeAt(r), f += m.charAt(_ >>> 4 & 15) + m.charAt(15 & _);
                return f
            }

            function X(d) {
                for (var _ = Array(d.length >> 2), m = 0; m < _.length; m++) _[m] = 0;
                for (m = 0; m < 8 * d.length; m += 8) _[m >> 5] |= (255 & d.charCodeAt(m / 8)) << m % 32;
                return _
            }

            function V(d) {
                for (var _ = "", m = 0; m < 32 * d.length; m += 8) _ += String.fromCharCode(d[m >> 5] >>> m % 32 & 255);
                return _
            }

            function Y(d, _) {
                d[_ >> 5] |= 128 << _ % 32, d[14 + (_ + 64 >>> 9 << 4)] = _;
                for (var m = 1732584193, f = -271733879, r = -1732584194, i = 271733878, n = 0; n < d.length; n += 16) {
                    var h = m, t = f, g = r, e = i;
                    f = md5_ii(f = md5_ii(f = md5_ii(f = md5_ii(f = md5_hh(f = md5_hh(f = md5_hh(f = md5_hh(f = md5_gg(f = md5_gg(f = md5_gg(f = md5_gg(f = md5_ff(f = md5_ff(f = md5_ff(f = md5_ff(f, r = md5_ff(r, i = md5_ff(i, m = md5_ff(m, f, r, i, d[n + 0], 7, -680876936), f, r, d[n + 1], 12, -389564586), m, f, d[n + 2], 17, 606105819), i, m, d[n + 3], 22, -1044525330), r = md5_ff(r, i = md5_ff(i, m = md5_ff(m, f, r, i, d[n + 4], 7, -176418897), f, r, d[n + 5], 12, 1200080426), m, f, d[n + 6], 17, -1473231341), i, m, d[n + 7], 22, -45705983), r = md5_ff(r, i = md5_ff(i, m = md5_ff(m, f, r, i, d[n + 8], 7, 1770035416), f, r, d[n + 9], 12, -1958414417), m, f, d[n + 10], 17, -42063), i, m, d[n + 11], 22, -1990404162), r = md5_ff(r, i = md5_ff(i, m = md5_ff(m, f, r, i, d[n + 12], 7, 1804603682), f, r, d[n + 13], 12, -40341101), m, f, d[n + 14], 17, -1502002290), i, m, d[n + 15], 22, 1236535329), r = md5_gg(r, i = md5_gg(i, m = md5_gg(m, f, r, i, d[n + 1], 5, -165796510), f, r, d[n + 6], 9, -1069501632), m, f, d[n + 11], 14, 643717713), i, m, d[n + 0], 20, -373897302), r = md5_gg(r, i = md5_gg(i, m = md5_gg(m, f, r, i, d[n + 5], 5, -701558691), f, r, d[n + 10], 9, 38016083), m, f, d[n + 15], 14, -660478335), i, m, d[n + 4], 20, -405537848), r = md5_gg(r, i = md5_gg(i, m = md5_gg(m, f, r, i, d[n + 9], 5, 568446438), f, r, d[n + 14], 9, -1019803690), m, f, d[n + 3], 14, -187363961), i, m, d[n + 8], 20, 1163531501), r = md5_gg(r, i = md5_gg(i, m = md5_gg(m, f, r, i, d[n + 13], 5, -1444681467), f, r, d[n + 2], 9, -51403784), m, f, d[n + 7], 14, 1735328473), i, m, d[n + 12], 20, -1926607734), r = md5_hh(r, i = md5_hh(i, m = md5_hh(m, f, r, i, d[n + 5], 4, -378558), f, r, d[n + 8], 11, -2022574463), m, f, d[n + 11], 16, 1839030562), i, m, d[n + 14], 23, -35309556), r = md5_hh(r, i = md5_hh(i, m = md5_hh(m, f, r, i, d[n + 1], 4, -1530992060), f, r, d[n + 4], 11, 1272893353), m, f, d[n + 7], 16, -155497632), i, m, d[n + 10], 23, -1094730640), r = md5_hh(r, i = md5_hh(i, m = md5_hh(m, f, r, i, d[n + 13], 4, 681279174), f, r, d[n + 0], 11, -358537222), m, f, d[n + 3], 16, -722521979), i, m, d[n + 6], 23, 76029189), r = md5_hh(r, i = md5_hh(i, m = md5_hh(m, f, r, i, d[n + 9], 4, -640364487), f, r, d[n + 12], 11, -421815835), m, f, d[n + 15], 16, 530742520), i, m, d[n + 2], 23, -995338651), r = md5_ii(r, i = md5_ii(i, m = md5_ii(m, f, r, i, d[n + 0], 6, -198630844), f, r, d[n + 7], 10, 1126891415), m, f, d[n + 14], 15, -1416354905), i, m, d[n + 5], 21, -57434055), r = md5_ii(r, i = md5_ii(i, m = md5_ii(m, f, r, i, d[n + 12], 6, 1700485571), f, r, d[n + 3], 10, -1894986606), m, f, d[n + 10], 15, -1051523), i, m, d[n + 1], 21, -2054922799), r = md5_ii(r, i = md5_ii(i, m = md5_ii(m, f, r, i, d[n + 8], 6, 1873313359), f, r, d[n + 15], 10, -30611744), m, f, d[n + 6], 15, -1560198380), i, m, d[n + 13], 21, 1309151649), r = md5_ii(r, i = md5_ii(i, m = md5_ii(m, f, r, i, d[n + 4], 6, -145523070), f, r, d[n + 11], 10, -1120210379), m, f, d[n + 2], 15, 718787259), i, m, d[n + 9], 21, -343485551), m = safe_add(m, h), f = safe_add(f, t), r = safe_add(r, g), i = safe_add(i, e)
                }
                return Array(m, f, r, i)
            }

            function md5_cmn(d, _, m, f, r, i) {
                return safe_add(bit_rol(safe_add(safe_add(_, d), safe_add(f, i)), r), m)
            }

            function md5_ff(d, _, m, f, r, i, n) {
                return md5_cmn(_ & m | ~_ & f, d, _, r, i, n)
            }

            function md5_gg(d, _, m, f, r, i, n) {
                return md5_cmn(_ & f | m & ~f, d, _, r, i, n)
            }

            function md5_hh(d, _, m, f, r, i, n) {
                return md5_cmn(_ ^ m ^ f, d, _, r, i, n)
            }

            function md5_ii(d, _, m, f, r, i, n) {
                return md5_cmn(m ^ (_ | ~f), d, _, r, i, n)
            }

            function safe_add(d, _) {
                var m = (65535 & d) + (65535 & _);
                return (d >> 16) + (_ >> 16) + (m >> 16) << 16 | 65535 & m
            }

            function bit_rol(d, _) {
                return d << _ | d >>> 32 - _
            }
        </script>
        <?php
    }

    /**
     * creates array of upload sizes based on server limits
     * to use in the file_sizes control
     * @return array
     */
    private function get_upload_file_size_options()
    {
        $max_file_size = wp_max_upload_size() / pow(1024, 2); //MB

        $sizes = [];

        for ($file_size = 1; $file_size <= $max_file_size; $file_size++) {
            $sizes[$file_size] = $file_size . 'MB';
        }

        return $sizes;
    }
}