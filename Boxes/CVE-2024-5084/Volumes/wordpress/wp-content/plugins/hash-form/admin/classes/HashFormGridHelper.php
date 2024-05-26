<?php

defined('ABSPATH') || die();

class HashFormGridHelper {

    private $parent_li;
    private $current_list_size;
    private $current_field_count;
    private $field_layout_class;
    private $active_field_size;
    private $field;
    private $section_helper;
    private $nested;
    private $section_is_open = false;

    public function __construct($nested = false) {
        $this->parent_li = false;
        $this->current_list_size = 0;
        $this->current_field_count = 0;
        $this->nested = $nested;
    }

    public function set_field($field) {
        $this->field = $field;
        $this->field_layout_class = $this->get_field_layout_class();
        $this->active_field_size = $this->get_size_of_class($this->field_layout_class);
    }

    public function maybe_begin_field_wrapper() {
        if ($this->should_first_close_the_active_field_wrapper()) {
            $this->close_field_wrapper();
        }

        if (false === $this->parent_li) {
            $this->begin_field_wrapper();
        }

        if (!empty($this->section_helper) && $this->section_is_open) {
            $this->section_helper->maybe_begin_field_wrapper();
        }
    }

    private function maybe_close_section_helper() {
        if (empty($this->section_helper)) {
            return;
        }
        $this->section_helper->force_close_field_wrapper();
        $this->section_helper = null;
    }

    private function should_first_close_the_active_field_wrapper() {
        if (false === $this->parent_li || !empty($this->section_helper)) {
            return false;
        }
    }

    private function begin_field_wrapper() {
        echo '<li class="hf-editor-field-box"><ul class="hf-editor-grid-container hf-editor-sorting">';
        $this->parent_li = true;
        $this->current_list_size = 0;
        $this->current_field_count = 0;
    }

    public function sync_list_size() {
        if (!isset($this->field)) {
            return;
        }

        if (false !== $this->parent_li) {
            $this->current_field_count ++;
            $this->current_list_size += $this->active_field_size;
            if (12 === $this->current_list_size) {
                $this->close_field_wrapper();
            }
        }
    }

    public function force_close_field_wrapper() {
        if (false !== $this->parent_li) {
            $this->close_field_wrapper();
        }
    }

    private function close_field_wrapper() {
        $this->maybe_close_section_helper();
        echo '</ul></li>';
        $this->parent_li = false;
        $this->current_list_size = 0;
        $this->current_field_count = 0;
    }

    private static function get_grid_classes() {
        return array(
            'hf-grid-1',
            'hf-grid-2',
            'hf-grid-3',
            'hf-grid-4',
            'hf-grid-5',
            'hf-grid-6',
            'hf-grid-7',
            'hf-grid-8',
            'hf-grid-9',
            'hf-grid-10',
            'hf-grid-11',
            'hf-grid-12',
        );
    }

    private function get_field_layout_class() {
        $field = $this->field;

        if (empty($field['grid_id'])) {
            return '';
        }

        $grid_class = $field['grid_id'];
        $classes = self::get_grid_classes();

        if (in_array($grid_class, $classes)) {
            return $grid_class;
        }
        return '';
    }

    private static function get_size_of_class($class) {
        if (0 === strpos($class, 'hf-grid-')) {
            $substr = substr($class, 8);
            if (is_numeric($substr)) {
                return (int) $substr;
            }
        }
        return 12;
    }

}
