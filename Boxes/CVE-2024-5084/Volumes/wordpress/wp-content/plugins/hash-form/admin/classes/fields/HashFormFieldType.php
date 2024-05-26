<?php
defined('ABSPATH') || die();

abstract class HashFormFieldType {

    protected $field;
    protected $field_id = 0;
    protected $type;

    public function __construct($field = 0, $type = '') {
        $this->field = $field;
        $this->set_type($type);
        $this->set_field_id();
    }

    public function get_field() {
        return $this->field;
    }

    protected function set_type($type) {
        if (empty($this->type)) {
            $this->type = $this->get_field_column('type');

            if (empty($this->type) && !empty($type))
                $this->type = $type;
        }
    }

    protected function set_field_id() {
        if (empty($this->get_field()))
            return;

        $field = $this->get_field();

        if (is_array($field)) {
            $this->field_id = isset($field['id']) ? $field['id'] : 0;
        } else if (is_object($field) && property_exists($field, 'id')) {
            $this->field_id = $field->id;
        } elseif (is_numeric($field)) {
            $this->field_id = $field;
        }
    }

    public function get_field_column($column) {
        $field_val = '';
        if (is_object($this->field)) {
            $field_val = $this->field->{$column};
        } elseif (is_array($this->field) && isset($this->field[$column])) {
            $field_val = $this->field[$column];
        }
        return $field_val;
    }

    /* Form builder FrontEnd each elements */

    public function get_frontend_html() {
        $field = $this->get_field();
        $display = $this->display_field_settings();
        $settings = HashFormSettings::get_settings();
        ?>

        <div class="hf-field-container" style="<?php echo esc_attr($this->container_inner_style()); ?>">
            <?php if ($display['label'] && !empty(trim($field['name'])) && (!($field['type'] == 'captcha' && $settings['re_type'] === 'v3'))) { ?>
                <label class="hf-field-label <?php echo (!$field['name'] || ((isset($field['hide_label']) && $field['hide_label']))) ? 'hf-hidden' : ''; ?>">
                    <?php echo esc_html($field['name']); ?>
                    <?php if (!!$field['required']) { ?>
                        <span class="hf-field-required" aria-hidden="true">
                            <?php echo esc_html($field['required_indicator']); ?>
                        </span>
                    <?php } ?>
                </label>
            <?php } ?>
            <div class="hf-field-content">
                <?php
                $this->input_html();

                if (isset($display['description']) && $display['description'] && !empty(trim($field['description']))) {
                    ?>
                    <div class="hf-field-desc">
                        <?php echo esc_html($field['description']); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
    }

    private function container_classes_array() {
        $global_settings = HashFormSettings::get_settings();
        $field = $this->get_field();
        $container_class = array();
        $container_class[] = ($field['required'] != '0') ? 'hf-form-field-required' : '';
        $container_class[] = 'hashform-field-type-' . esc_attr($field['type']);
        $container_class[] = ($field['type'] == 'captcha' && $global_settings['re_type'] == 'v3' && !is_admin()) ? 'hf-recaptcha-v3 hf-hidden' : '';

        if (in_array($field['type'], array('heading', 'paragraph'))) {
            $text_alignment = isset($field['text_alignment']) && $field['text_alignment'] ? $field['text_alignment'] : 'inline';
            $container_class[] = 'hf-text-alignment-' . trim($text_alignment);
        }

        if (in_array($field['type'], array('separator', 'image', 'heading', 'paragraph', 'html'))) {
            $field_alignment = isset($field['field_alignment']) ? $field['field_alignment'] : 'left';
            $container_class[] = 'hf-field-alignment-' . esc_attr($field_alignment);
        }

        if (!in_array($field['type'], array('separator', 'image', 'heading', 'paragraph', 'html'))) {
            $label_position = isset($field['label_position']) && $field['label_position'] ? $field['label_position'] : 'top';
            $label_alignment = isset($field['label_alignment']) && $field['label_alignment'] ? $field['label_alignment'] : 'left';
            $hide_label = isset($field['hide_label']) && $field['hide_label'] ? $field['hide_label'] : '';
            $container_class[] = 'hf-label-position-' . trim($label_position);
            $container_class[] = 'hf-label-alignment-' . trim($label_alignment);
            $container_class[] = $field['classes'] ? esc_attr($field['classes']) : '';

            if ($field['type'] === 'radio' || $field['type'] === 'checkbox' || $field['type'] === 'image_select') {
                $options_layout = isset($field['options_layout']) && $field['options_layout'] ? $field['options_layout'] : 'inline';
                $container_class[] = 'hf-options-layout-' . trim($options_layout);
            }

            if ($field['type'] === 'select') {
                $container_class[] = isset($field['auto_width']) && $field['auto_width'] == 'on' ? 'hf-auto-width' : '';
            }
        }

        if (isset($field['grid_id']) && $field['grid_id']) {
            $container_class[] = trim($field['grid_id']);
        }
        return array_filter($container_class);
    }

    private function container_inner_style() {
        $field = $this->get_field();
        $field_max_width = isset($field['field_max_width']) ? esc_attr($field['field_max_width']) : '';
        $field_max_width_unit = isset($field['field_max_width_unit']) ? esc_attr($field['field_max_width_unit']) : '%';
        $inline_style = $field_max_width ? ('--hf-width:' . esc_attr($field_max_width) . esc_attr($field_max_width_unit) . ';') : '';
        if ($field['type'] == 'image_select') {
            $image_max_width = isset($field['image_max_width']) ? esc_attr($field['image_max_width']) : '';
            $image_max_width_unit = isset($field['image_max_width_unit']) ? esc_attr($field['image_max_width_unit']) : '%';
            $inline_style .= $image_max_width ? '--hf-image-width: ' . esc_attr($image_max_width) . esc_attr($image_max_width_unit) : '';
        }
        return $inline_style;
    }

    protected function input_html() {
        ?>
        [input]
        <?php
    }

    /* Form builder AdminEnd each elements */

    public function load_single_field() {
        $field = $this->get_field();
        $classes = $this->container_classes_array();
        $new_classes = array('hf-editor-form-field', 'hf-editor-field-box', 'hf-editor-field-elements', 'ui-state-default', 'widgets-holder-wrap');
        $classes = array_merge($new_classes, $classes);
        $classes[] = 'hf-editor-field-type-' . $this->type;

        $field_max_width = isset($field['field_max_width']) ? esc_attr($field['field_max_width']) : '';
        $field_max_width_unit = isset($field['field_max_width_unit']) ? esc_attr($field['field_max_width_unit']) : '%';
        if ($field['type'] == 'image_select') {
            $image_max_width = isset($field['image_max_width']) ? $field['image_max_width'] : '';
            $image_max_width_unit = isset($field['image_max_width_unit']) ? esc_attr($field['image_max_width_unit']) : '%';
        }
        ?>
        <li id="hf-editor-field-id-<?php echo esc_attr($field['id']); ?>" class="<?php echo esc_attr(implode(' ', $classes)); ?>" data-fid="<?php echo esc_attr($field['id']); ?>" data-formid="<?php echo esc_attr('divider' === $field['type'] ? esc_attr($field['form_select']) : esc_attr($field['form_id']) ); ?>" data-type="<?php echo esc_attr($field['type']); ?>">

            <div id="hf-editor-field-container-<?php echo esc_attr($field['id']); ?>" class="hf-editor-field-container" style="<?php echo ($field_max_width ? ('--hf-width:' . esc_attr($field_max_width) . esc_attr($field_max_width_unit) . ';') : ''); ?><?php echo ((isset($image_max_width) && $image_max_width) ? '--hf-image-width: ' . esc_attr($image_max_width) . esc_attr($image_max_width_unit) : ''); ?>">
                <div class="hf-editor-action-buttons">
                    <a href="#" class="hf-editor-move-action" title="<?php esc_attr_e('Move Field', 'hash-form'); ?>" data-container="body" aria-label="<?php esc_attr_e('Move Field', 'hash-form'); ?>"><span class="mdi mdi-cursor-move"></span></a>
                    <a href="#" class="hf-editor-delete-action" title="<?php esc_attr_e('Delete', 'hash-form'); ?>" data-container="body" aria-label="<?php esc_attr_e('Delete', 'hash-form'); ?>" data-deletefield="<?php echo esc_attr($field['id']); ?>"><span class="mdi mdi-trash-can-outline"></span></a>
                </div>

                <?php $this->get_builder_html(); ?>
            </div>

            <?php
            $this->load_single_field_settings();
            ?>
        </li>
        <?php
    }

    public function get_builder_html() {
        $field = $this->get_field();
        $display = $this->display_field_settings();
        $id = $field['id'];

        if ($display['label']) {
            ?>
            <label class="hf-editor-field-label hf-label-show-hide <?php echo (!$field['name'] || ((isset($field['hide_label']) && $field['hide_label']))) ? 'hf-hidden' : ''; ?> ">
                <span id="hf-editor-field-label-text-<?php echo esc_attr($id); ?>" class="hf-editor-field-label-text">
                    <?php echo esc_html($field['name']); ?>
                </span>

                <span id="hf-editor-field-required-<?php echo esc_attr($id); ?>" class="hf-field-required<?php echo (!$field['required'] ? ' hf-hidden' : ''); ?>">
                    <?php echo esc_html($field['required_indicator']); ?>
                </span>
            </label>
        <?php } ?>

        <div class="hf-editor-field-content">
            <div class="hf-editor-field-elements">
                <?php $this->input_html(); ?>
            </div>

            <?php
            if (isset($display['description']) && $display['description']) {
                ?>
                <div class="hf-field-desc" id="hf-field-desc-<?php echo esc_attr($id); ?>">
                    <?php echo esc_html($field['description']); ?>
                </div>
            <?php } ?>
        </div>
        <?php
    }

    protected function html_name($name = '') {
        $prefix = empty($name) ? 'item_meta' : $name;
        return $prefix . '[' . $this->get_field_column('id') . ']';
    }

    protected function html_id($plus = '') {
        return 'hf-field-' . $this->get_field_column('field_key') . $plus;
    }

    public function display_field_settings() {
        $default_settings = $this->default_display_field_settings();
        $field_type_settings = $this->field_settings_for_type();
        return wp_parse_args($field_type_settings, $default_settings);
    }

    protected function default_display_field_settings() {
        return array(
            'id' => true,
            'name' => true,
            'value' => true,
            'label' => true,
            'max' => false,
            'invalid' => false,
            'clear_on_focus' => false,
            'classes' => false,
            'range' => false,
            'format' => false,
            'max_width' => true,
            'field_alignment' => false,
            'rows' => false,
            'min_time' => false,
            'max_time' => false,
            'date_format' => false,
            'required' => true,
            'content' => false,
            'css' => true,
            'auto_width' => false,
            'default' => true,
            'description' => true,
            'image_max_width' => false
        );
    }

    protected function field_attrs() {
        $attrs = array();
        $display = $this->display_field_settings();

        if (isset($display['id']) && $display['id']) {
            $default_attrs['id'] = $this->html_id();
        }

        if (isset($display['value']) && $display['value']) {
            $default_attrs['value'] = $this->prepare_esc_value();
        }

        if (isset($display['name']) && $display['name']) {
            $default_attrs['name'] = $this->html_name();
        }

        if (isset($display['clear_on_focus']) && $display['clear_on_focus'] && $this->get_field_column('placeholder')) {
            $default_attrs['placeholder'] = $this->get_field_column('placeholder');
        }

        if (isset($display['range']) && $display['range']) {
            $default_attrs['min'] = is_numeric($this->get_field_column('minnum')) ? $this->get_field_column('minnum') : 0;
            $default_attrs['max'] = is_numeric($this->get_field_column('maxnum')) ? $this->get_field_column('maxnum') : 9999999;
            $default_attrs['step'] = is_numeric($this->get_field_column('step')) ? $this->get_field_column('step') : 1;
        }

        if (isset($display['max']) && $display['max']) {
            $default_attrs['maxlength'] = is_numeric($this->get_field_column('max')) ? $this->get_field_column('max') : '';
        }

        $default_attrs = array_merge($default_attrs, $this->extra_field_attrs());

        foreach ($default_attrs as $key => $value) {
            $attrs[] = esc_attr($key) . '="' . esc_attr($value) . '"';
        }

        echo wp_kses_post(implode(' ', $attrs));
    }

    protected function extra_field_attrs() {
        return array();
    }

    protected function field_settings_for_type() {
        return array();
    }

    protected function load_single_field_settings() {
        $field = $this->get_field();
        $display = $this->display_field_settings();
        $field_type = $field['type'];
        $field_id = $field['id'];
        $all_field_types = HashFormFields::field_selection();
        $type_name = $all_field_types[$field_type]['name'];

        include( HASHFORM_PATH . 'admin/classes/fields/settings.php' );
    }

    /* Extra Options */

    public function show_primary_options() {
        
    }

    public function show_field_choices() {
        $field = $this->get_field();
        $this->field_choices_heading();
        ?>
        <div class="hf-form-row">
            <?php
            if ($field['type'] != 'image_select') {
                ?>
                <span class="hf-bulk-edit-link">
                    <a href="#" class="hf-bulk-edit-link">
                        <?php esc_html_e('Bulk Edit Options', 'hash-form'); ?>
                    </a>
                </span>
            <?php } ?>

            <ul id="hf-field-options-<?php echo esc_attr($field['id']); ?>" class="hf-option-list" data-key="<?php echo esc_attr($field['field_key']); ?>">
                <?php
                $this->show_single_option();
                ?>
            </ul>

            <div class="hf-option-add-list">
                <a href="javascript:void(0);" data-opttype="single" class="hf-add-option">
                    <span class="mdi mdi-plus"></span>
                    <?php esc_html_e('Add Option', 'hash-form'); ?>
                </a>
            </div>
        </div>
        <?php
    }

    public function show_single_option() {
        $field = $this->get_field();
        if (!is_array($field['options']))
            return;
        $html_id = $this->html_id();
        $this->hidden_field_option();
        foreach ($field['options'] as $opt_key => $opt) {
            $field_val = $opt['label'];
            $default_value = (array) $field['default_value'];
            $checked = in_array($field_val, $default_value) ? 'checked' : '';
            require( HASHFORM_PATH . 'admin/classes/fields/single-option.php' );
        }
    }

    protected function hidden_field_option() {
        $field = $this->get_field();
        $ajax_action = get_post('action', 'sanitize_text_field');
        if ($ajax_action === 'hashform_import_options')
            return;
        $opt_key = '000';
        $opt = esc_html__('New Option', 'hash-form');

        $html_id = $this->html_id();
        $field_val = $opt = esc_html__('New Option', 'hash-form');

        $checked = false;

        require( HASHFORM_PATH . 'admin/classes/fields/single-option.php' );
    }

    protected function field_choices_heading() {
        $field = $this->get_field();
        ?>
        <h4 class="hf-field-heading">
            <?php
            printf(esc_html__('%s Options', 'hash-form'), esc_html($field['name']));
            ?>
        </h4>
        <?php
    }

    /* Combo Options */

    protected function show_after_default() {
        
    }

    public function get_default_field_options() {
        $opts = array(
            'grid_id' => '',
            'label_position' => '',
            'label_alignment' => '',
            'hide_label' => '',
            'heading_type' => '',
            'text_alignment' => '',
            'content' => '',
            'select_option_type' => 'radio',
            'image_size' => '',
            'image_id' => '',
            'spacer_height' => '50',
            'step' => '1',
            'min_time' => '00:00',
            'max_time' => '23:59',
            'date_format' => 'MM dd, yy',
            'border_style' => 'solid',
            'border_width' => '2',
            'minnum' => '1',
            'maxnum' => '10',
            'classes' => '',
            'auto_width' => 'off',
            'placeholder' => '',
            'format' => '',
            'required_indicator' => '*',
            'options_layout' => 'inline',
            'field_max_width' => '',
            'field_max_width_unit' => '%',
            'image_max_width' => '100',
            'image_max_width_unit' => '%',
            'field_alignment' => 'left',
            'blank' => esc_html__('This field is required.', 'hash-form'),
            'invalid' => esc_html__('This field is invalid.', 'hash-form'),
            'rows' => '10',
            'max' => '',
            'disable' => array(
                'line1' => '',
                'line2' => '',
                'city' => '',
                'state' => '',
                'zip' => '',
                'country' => ''
            )
        );
        $field_opts = $this->extra_field_default_opts();
        $opts = array_merge($opts, $field_opts);
        return $opts;
    }

    protected function extra_field_default_opts() {
        return array();
    }

    /* Front End Display */

    public function show_field() {
        $this->load_field_scripts();
        $field = $this->get_field();
        $classes = $this->container_classes_array();
        $classes[] = 'hf-form-field';
        ?>
        <div id="hf-field-container-<?php echo esc_attr($field['id']); ?>" class="<?php echo esc_attr(implode(' ', $classes)); ?>">
            <?php $this->get_frontend_html(); ?>
        </div>
        <?php
    }

    protected function load_field_scripts() {
        
    }

    protected function prepare_esc_value() {
        $field = $this->get_field();
        $value = isset($field['default_value']) ? $field['default_value'] : '';
        if (is_array($value)) {
            $value = implode(', ', $value);
        }

        if (strpos($value, '&lt;') !== false)
            $value = htmlentities($value);
        return $value;
    }

    protected function add_min_max() {
        $field = $this->field();
        $min = $field['minnum'];
        $max = $field['maxnum'];
        $step = $field['step'];

        if (!is_numeric($min))
            $min = 0;

        if (!is_numeric($max))
            $max = 9999999;

        if (!is_numeric($step) && $step !== 'any')
            $step = 1;

        $input_html .= ' min="' . esc_attr($min) . '" max="' . esc_attr($max) . '" step="' . esc_attr($step) . '"';
    }

    public function validate($args) {
        return array();
    }

    public function set_value_before_save($value) {
        return $value;
    }

    public function sanitize_value(&$value) {
        return HashFormHelper::sanitize_value('sanitize_text_field', $value);
    }

    public function get_new_field_defaults() {
        return array(
            'name' => $this->get_new_field_name(),
            'description' => '',
            'type' => $this->type,
            'default_value' => '',
            'required' => false,
            'field_options' => $this->get_default_field_options(),
            'options' => array(
                array(
                    'label' => esc_html__('Option 1', 'hash-form'),
                ),
                array(
                    'label' => esc_html__('Option 2', 'hash-form'),
                ),
                array(
                    'label' => esc_html__('Option 3', 'hash-form'),
                )
            ),
        );
    }

    protected function get_new_field_name() {
        $name = esc_html__('Untitled', 'hash-form');
        $fields = HashFormFields::field_selection();
        if (isset($fields[$this->type])) {
            $name = is_array($fields[$this->type]) ? $fields[$this->type]['name'] : $fields[$this->type];
        }
        return $name;
    }

}
