<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'FEA_Form_Import' ) ) :

	class FEA_Form_Import extends FEA_Admin_Tool {



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
			$this->name  = 'import';
			$this->title = __( 'Import Admin Forms', 'acf-frontend-form-element' );
			$this->icon  = 'dashicons-upload';

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
			?>
		<p><?php esc_html_e( 'Select the JSON file you would like to import. When you click the import button below, your forms be imported.', 'acf-frontend-form-element' ); ?></p>
		<div class="acf-fields">
			<?php

			acf_render_field_wrap(
				array(
					'label'    => __( 'Select File', 'acf-frontend-form-element' ),
					'type'     => 'file',
					'name'     => 'acf_import_file',
					'value'    => false,
					'uploader' => 'basic',
				)
			);

			?>
		</div>
		<p class="acf-submit">
			<input type="submit" class="button button-primary" value="<?php esc_html_e( 'Import File', 'acf-frontend-form-element' ); ?>" />
		</p>
			<?php

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
			// Check file size.
			if ( empty( $_FILES['acf_import_file']['size'] ) ) {
				return acf_add_admin_notice( __( 'No file selected', 'acf-frontend-form-element' ), 'warning' );
			}

			// Get file data.
			$file = $_FILES['acf_import_file'];

			// Check errors.
			if ( $file['error'] ) {
				return acf_add_admin_notice( __( 'Error uploading file. Please try again', 'acf-frontend-form-element' ), 'warning' );
			}

			// Check file type.
			if ( pathinfo( $file['name'], PATHINFO_EXTENSION ) !== 'json' ) {
				return acf_add_admin_notice( __( 'Incorrect file type', 'acf-frontend-form-element' ), 'warning' );
			}

			// Read JSON.
			$json = file_get_contents( $file['tmp_name'] );
			$json = json_decode( $json, true );

			// Check if empty.
			if ( ! $json || ! is_array( $json ) ) {
				return acf_add_admin_notice( __( 'Import file empty', 'acf-frontend-form-element' ), 'warning' );
			}

			// Ensure $json is an array of groups.
			if ( isset( $json['id'] ) ) {
				$json = array( $json );
			}

			// Remeber imported form ids.
			$ids = array();

			// Loop over json
			foreach ( $json as $form ) {

				// Search database for existing form.
				$post = fea_instance()->form_display->get_form( $form['id'] );
				if ( $post ) {
					$form['ID'] = $post['ID'];
				}

				// Import form.
				$form = $this->import_form( $form );

				// append message
				$ids[] = $form['ID'];
			}

			// Count number of imported forms.
			$total = count( $ids );

			// Generate text.
			$text = sprintf( _n( 'Imported 1 form', 'Imported %s forms', $total, 'acf-frontend-form-element' ), $total );

			// Add links to text.
			$links = array();
			foreach ( $ids as $id ) {
				$links[] = '<a href="' . get_edit_post_link( $id ) . '">' . get_the_title( $id ) . '</a>';
			}
			$text .= ' ' . implode( ', ', $links );

			// Add notice
			acf_add_admin_notice( $text, 'success' );
		}

		function import_form( $form ) {
			 // Disable filters to ensure data is not modified by local, clone, etc.
			$filters = acf_disable_filters();
			/*
			// Validate form (ensures all settings exist).
			$form = acf_get_valid_form( $form );

			// Prepare group for import (modifies settings).
			$form = acf_prepare_form_for_import( $form );
			*/
			$fields = acf_prepare_fields_for_import( $form['fields'] );

			// Stores a map of field "key" => "ID".
			$ids = array();

			// If the form has an ID, review and delete stale fields in the database.
			if ( isset( $form['ID'] ) ) {

				// Load database fields.
				$db_fields = acf_prepare_fields_for_import( acf_get_fields( $form['ID'] ) );

				// Generate map of "index" => "key" data.
				$keys = wp_list_pluck( $fields, 'key' );

				// Loop over db fields and delete those who don't exist in $new_fields.
				foreach ( $db_fields as $field ) {

					// Add field data to $ids map.
					$ids[ $field['key'] ] = $field['ID'];

					// Delete field if not in $keys.
					if ( ! in_array( $field['key'], $keys ) ) {
						acf_delete_field( $field['ID'] );
					}
				}
			}

			// When importing a new form, insert a temporary post and set the form's ID.
			// This allows fields to be updated before the form (form ID is needed for field parent setting).
			if ( empty( $form['ID'] ) ) {
				$form['ID'] = wp_insert_post(
					array(
						'post_type'  => 'admin_form',
						'post_title' => $form['title'],
					)
				);
				update_post_meta( $form['ID'], 'admin_form_type', 'general' );
				update_post_meta( $form['ID'], 'form_key', $form['id'] );
			}

			// Add form data to $ids map.
			$ids[ $form['id'] ] = $form['ID'];

			// Loop over and add fields.
			if ( $fields ) {
				foreach ( $fields as $field ) {
					$field['parent'] = $form['ID'];
					// Replace any "key" references with "ID".
					if ( isset( $ids[ $field['key'] ] ) ) {
						$field['ID'] = $ids[ $field['key'] ];
					}
					if ( isset( $ids[ $field['parent'] ] ) ) {
						$field['parent'] = $ids[ $field['parent'] ];
					}

					// Save field.
					$field = acf_update_field( $field );

					// Add field data to $ids map for children.
					$ids[ $field['key'] ] = $field['ID'];
				}
			}

			// Enable filters again.
			acf_enable_filters( $filters );

			/**
			 * Fires immediately after a form has been imported.
			 *
			 * @date  12/02/2014
			 * @since 5.0.0
			 *
			 * @param array $form The form array.
			 */
			do_action( 'frontend_admin/import_form', $form );

			// return new form.
			return $form;
		}
	}

	// initialize
	fea_instance()->admin_tools->register_tool( 'FEA_Form_Import' );

endif; // class_exists check

?>
