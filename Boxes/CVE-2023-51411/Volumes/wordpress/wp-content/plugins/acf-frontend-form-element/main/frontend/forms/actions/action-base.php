<?php
namespace Frontend_Admin\Classes;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

abstract class ActionBase {
	abstract public function get_name();

	public function show_in_tab() {
		 return true;
	}

	abstract public function get_label();

	public function run( $settings ) {
		return $settings;
	}

	public function add_field_options( $widget, $field, $label, $options ) {
		return;
	}

	public function action_controls( $widget, $step = false ) {
		 return;
	}

	public function get_valid_defaults( $default_fields, $form_id ) {
		$fields = array();
		$i      = 0;
		foreach ( $default_fields as $default ) {
			$label    = ucwords( str_replace( '_', ' ', $default ) );
			$fields[] = acf_update_field(
				array(
					'ID'         => 0,
					'parent'     => $form_id,
					'key'        => 'field_' . uniqid(),
					'label'      => __( $label, 'acf-frontend-form-element' ),
					'name'       => $default,
					'type'       => $default,
					'menu_order' => $i,
				)
			);
			$i++;
		}
		return $fields;
	}

	public function save_form_data( $data_id, $values ) {
		acf_set_form_data( 'post_id', $data_id );
		if ( ! acf_allow_unfiltered_html() ) {
			$values = wp_kses_post_deep( $values );
		}
		acf_update_values( $values, $data_id );

	}

	abstract public function register_settings_section( $widget );

}
