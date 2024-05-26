<?php
defined('ABSPATH') || die();

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

class HashFormElement extends Widget_Base {

    public function get_name() {
        return 'Hash Form';
    }

    public function get_title() {
        return esc_html__('Hash Form', 'hash-form');
    }

    public function get_icon() {
        return 'hfi hfi-form';
    }

    public function get_categories() {
        return array('basic');
    }

    public function get_keywords() {
        return array('Form', 'Hash Form', 'Hash');
    }

    protected function register_controls() {

        $this->start_controls_section(
                'section_title', [
            'label' => esc_html__('Form', 'hash-form'),
            'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );

        $this->add_control(
                'hf_form_id', [
            'label' => esc_html__('Select Form', 'hash-form'),
            'type' => Controls_Manager::SELECT2,
            'options' => HashFormHelper::get_all_forms_list_options(),
            'multiple' => false,
            'label_block' => true,
            'separator' => 'after'
                ]
        );

        $this->add_control(
                'new_form', [
            'type' => Controls_Manager::RAW_HTML,
            'raw' => sprintf(
                    wp_kses(esc_html__('To Create New Form', 'hash-form') . ' <a href="%s" target="_blank">' . esc_html__('Cick Here', 'hash-form') . '</a>', [
                'b' => [],
                'br' => [],
                'a' => [
                    'href' => [],
                    'target' => [],
                ],
                    ]), esc_url(add_query_arg('page', 'hashform', admin_url('admin.php')))
            )
                ]
        );

        $this->end_controls_section();
    }

    public function render() {
        $settings = $this->get_settings_for_display();

        if (isset($settings['hf_form_id']) && !empty($settings['hf_form_id']) && (HashFormListing::get_status($settings['hf_form_id']) == 'published')) {
            echo do_shortcode('[hashform id="' . $settings['hf_form_id'] . '"]');
        } elseif ($this->elementor()->editor->is_edit_mode()) {
            ?>
            <p><?php echo esc_html__('Please select a Form', 'hash-form'); ?></p>
            <?php
        }
    }

    protected function elementor() {
        return Plugin::$instance;
    }

}
