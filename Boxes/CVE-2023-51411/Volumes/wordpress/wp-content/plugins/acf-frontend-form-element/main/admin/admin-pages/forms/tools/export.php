<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'FEA_Form_Export' ) ) :

	class FEA_Form_Export extends FEA_Admin_Tool {


		/**
		 *
		 *
		 * @var string View context
		 */
		var $view = '';


		/**
		 *
		 *
		 * @var array Export data
		 */
		var $json = '';


		/**
		 *  initialize
		 *
		 *  This function will initialize the admin tool
		 *
		 * @date  10/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return n/a
		 */

		function initialize() {
			// vars
			$this->name  = 'export';
			$this->title = __( 'Export Admin Forms', 'acf-frontend-form-element' );

			// active
			if ( $this->is_active() ) {
				$this->title .= ' - ' . __( 'Generate PHP', 'acf-frontend-form-element' );
			}

		}


		/**
		 *  submit
		 *
		 *  This function will run when the tool's form has been submit
		 *
		 * @date  10/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return n/a
		 */

		function submit() {
			// vars
			$action = acf_maybe_get_POST( 'action' );

			// download
			if ( $action === 'download' ) {

				$this->submit_download();

				// generate
			} elseif ( $action === 'generate' ) {

				$this->submit_generate();

			}

		}


		/**
		 *  submit_download
		 *
		 *  description
		 *
		 * @date  17/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return n/a
		 */

		function submit_download() {
			// vars
			$json = $this->get_selected();

			// validate
			if ( $json === false ) {
				return acf_add_admin_notice( __( 'No forms selected', 'acf-frontend-form-element' ), 'warning' );
			}

			// headers
			$file_name = 'fea-export-' . gmdate( 'Y-m-d' ) . '.json';
			header( 'Content-Description: File Transfer' );
			header( "Content-Disposition: attachment; filename={$file_name}" );
			header( 'Content-Type: application/json; charset=utf-8' );

			// return
			echo json_encode( $json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
			die;

		}


		/**
		 *  submit_generate
		 *
		 *  description
		 *
		 * @date  17/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return n/a
		 */

		function submit_generate() {
			// vars
			$keys = $this->get_selected_keys();

			// validate
			if ( ! $keys ) {
				return acf_add_admin_notice( __( 'No forms selected', 'acf-frontend-form-element' ), 'warning' );
			}

			// url
			$url = add_query_arg( 'keys', implode( '+', $keys ), $this->get_url() );

			// redirect
			wp_redirect( $url );
			exit;

		}


		/**
		 *  load
		 *
		 *  description
		 *
		 * @date  21/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return n/a
		 */

		function load() {
			// active
			if ( $this->is_active() ) {

				// get selected keys
				$selected = $this->get_selected_keys();

				// add notice
				if ( $selected ) {
					$count = count( $selected );
					$text  = sprintf( _n( 'Exported 1 field group.', 'Exported %s forms.', $count, 'acf-frontend-form-element' ), $count );
					acf_add_admin_notice( $text, 'success' );
				}
			}

		}


		/**
		 *  html
		 *
		 *  This function will output the metabox HTML
		 *
		 * @date  10/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return n/a
		 */

		function html() {		
			$this->html_archive();
		}


		/**
		 *  html_field_selection
		 *
		 *  description
		 *
		 * @date  24/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return n/a
		 */

		function html_field_selection() {
			// vars
			$choices  = array();
			$selected = $this->get_selected_keys();
			$forms    = feadmin_form_choices();

			// loop
			if ( $forms ) {
				foreach ( $forms as $id => $form ) {
					$form_key = get_post_meta( $id, 'form_key', 1 );
					if ( ! $form_key ) {
						$form_key = uniqid( 'form_' );
						update_post_meta( $id, 'form_key', $form_key );
					}

					$choices[ $form_key ] = $form;
				}
			}

			// render
			acf_render_field_wrap(
				array(
					'label'   => __( 'Select Forms', 'acf-frontend-form-element' ),
					'type'    => 'checkbox',
					'name'    => 'keys',
					'prefix'  => false,
					'value'   => $selected,
					'toggle'  => true,
					'choices' => $choices,
				)
			);

		}


		/**
		 *  html_panel_selection
		 *
		 *  description
		 *
		 * @date  21/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return n/a
		 */

		function html_panel_selection() {
			?>
		<div class="acf-panel acf-panel-selection">
			<h3 class="acf-panel-title"><?php esc_html_e( 'Select Forms', 'acf-frontend-form-element' ); ?> <i class="dashicons dashicons-arrow-right"></i></h3>
			<div class="acf-panel-inside">
			<?php $this->html_field_selection(); ?>
			</div>
		</div>
			<?php

		}


		/**
		 *  html_panel_settings
		 *
		 *  description
		 *
		 * @date  21/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return n/a
		 */

		function html_panel_settings() {
			?>
		<div class="acf-panel acf-panel-settings">
			<h3 class="acf-panel-title"><?php esc_html_e( 'Settings', 'acf-frontend-form-element' ); ?> <i class="dashicons dashicons-arrow-right"></i></h3>
			<div class="acf-panel-inside">
			<?php

			/*
			acf_render_field_wrap(array(
			'label'     => __('Empty settings', 'acf-frontend-form-element'),
			'type'      => 'select',
			'name'      => 'minimal',
			'prefix'    => false,
			'value'     => '',
			'choices'   => array(
			'all'       => __('Include all settings', 'acf-frontend-form-element'),
			'minimal'   => __('Ignore empty settings', 'acf-frontend-form-element'),
			)
			));
			*/

			?>
			</div>
		</div>
			<?php

		}


		/**
		 *  html_archive
		 *
		 *  description
		 *
		 * @date  20/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return n/a
		 */

		function html_archive() {
			?>
		<p><?php esc_html_e( 'Select the forms you would like to export and then select your export method. Use the download button to export to a .json file which you can then import to another WP installation.', 'acf-frontend-form-element' ); ?></p>
		<div class="acf-fields">
			<?php $this->html_field_selection(); ?>
		</div>
		<p class="acf-submit">
			<button type="submit" name="action" class="button button-primary" value="download"><?php esc_html_e( 'Export File', 'acf-frontend-form-element' ); ?></button>
		</p>
			<?php

		}


		/**
		 *  get_selected_keys
		 *
		 *  This function will return an array of field group keys that have been selected
		 *
		 * @date  20/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return n/a
		 */

		function get_selected_keys() {
			// check $_POST
			if ( $keys = acf_maybe_get_POST( 'keys' ) ) {
				return (array) $keys;
			}

			// check $_GET
			if ( $keys = acf_maybe_get_GET( 'keys' ) ) {
				$keys = str_replace( ' ', '+', $keys );
				return explode( '+', $keys );
			}

			// return
			return false;

		}


		/**
		 *  get_selected
		 *
		 *  This function will return the JSON data for given $_POST args
		 *
		 * @date  17/10/17
		 * @since 5.6.3
		 *
		 * @param  n/a
		 * @return array
		 */

		function get_selected() {
			// vars
			$selected = $this->get_selected_keys();
			$json     = array();

			// bail early if no keys
			if ( ! $selected ) {
				return false;
			}

			// construct JSON
			foreach ( $selected as $key ) {

				// load field group
				$form = fea_instance()->form_display->get_form( $key, true );

				// validate field group
				if ( empty( $form ) ) {
					continue;
				}

				// prepare for export
				$form = acf_prepare_field_group_for_export( $form );

				// add to json array
				$json[] = $form;

			}

			// return
			return $json;

		}
	}

	// initialize
	fea_instance()->admin_tools->register_tool( 'FEA_Form_Export' );

endif; // class_exists check

?>
