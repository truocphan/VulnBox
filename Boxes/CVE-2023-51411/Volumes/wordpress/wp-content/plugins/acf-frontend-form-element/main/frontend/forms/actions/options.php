<?php
namespace Frontend_Admin\Actions;

use Frontend_Admin\Plugin;
use Frontend_Admin\Classes\ActionBase;
use Frontend_Admin\Forms\Actions;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'ActionOptions' ) ) :

	class ActionOptions extends ActionBase {


		public function get_name() {
			return 'options';
		}

		public function show_in_tab() {
			 return false;
		}

		public function get_label() {
			return __( 'Options', 'acf-frontend-form-element' );
		}

		public function get_fields_display( $form_field, $local_field ) {
			switch ( $form_field['field_type'] ) {
				case 'site_title':
					$local_field['type'] = 'site_title';
					break;
				case 'site_tagline':
					$local_field['type'] = 'site_tagline';
					break;
				case 'site_logo':
					$local_field['type'] = 'site_logo';
					break;
			}
			return $local_field;
		}

		public function get_default_fields( $form ) {
			$default_fields = array(
				'site_title',
				'site_tagline',
				'site_logo',
				'submit_button',
			);
			return $this->get_valid_defaults( $default_fields, $form );
		}

		public function register_settings_section( $widget ) {
			return;
		}

		public function run( $form ) {
			$record = $form['record'];
			if ( ! empty( $record['fields']['admin_options'] ) ) {
				foreach ( $record['fields']['admin_options'] as $key => $field ) {
					if ( $key == 'frontend_admin_dashboard_slug' ) {
						   $previous = get_option( $key );
						if ( $previous == $field['_input'] ) {
							continue;
						} else {
							update_option( 'fea_flush_permalinks', 1 );
						}
					}
					update_option( $key, $field['_input'] );
				}
				do_action( 'frontend_admin/save_admin_options', $form );
			}
			if ( ! empty( $record['fields']['options'] ) ) {
				foreach ( $record['fields']['options'] as $option ) {
					if ( ! isset( $option['key'] ) ) {
						continue;
					}
					$field = acf_maybe_get_field( $option['key'] );

					if ( ! $field ) {
						if( isset( $form['fields'][$option['key']] ) ){
							$field = $form['fields'][$option['key']];
						}else{
							continue;
						}
					}

					acf_update_value( $option['_input'], 'options', $field );
				}
				do_action( 'frontend_admin/save_options', $form );
			}

			return $form;
		}

	}

	fea_instance()->local_actions['options'] = new ActionOptions();

endif;
