<?php
/**
 * Add extra profile fields for users in admin
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}



/**
 * Xoo_El_User_Profile Class.
 */
class Xoo_El_User_Profile {

	public $userCols = array();

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'show_user_profile', array( $this, 'add_customer_meta_fields' ) );
		add_action( 'edit_user_profile', array( $this, 'add_customer_meta_fields' ) );

		add_action( 'personal_options_update', array( $this, 'save_customer_meta_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_customer_meta_fields' ) );

		add_filter( 'manage_users_columns', array( $this, 'add_columns' ) );
		add_filter( 'manage_users_custom_column', array( $this, 'columns_output' ), 11, 3 );
	}

	public function add_columns( $columns ){

		$fields = xoo_el_fields()->get_fields( 'register' );

		$fieldCols = array();

		foreach ( $fields as $field_id => $field ) {
			if( isset( $field['settings']['display_usercol'] ) && $field['settings']['display_usercol'] === 'yes' ){
				$label = xoo_el()->aff->fields->get_field_label( $field );
				$fieldCols[ $field_id ] = $label;
				$this->userCols[] = $field_id;
			}
		}

		$columns = array_merge( $columns, $fieldCols );


		
	    return $columns;
	}

	public function columns_output( $val, $column_name, $user_id ){

		if( in_array( $column_name , $this->userCols ) ){
			$val = xoo_el()->aff->fields->get_field_value_label( $column_name, get_user_meta( $user_id, $column_name, true ) );
		}

		return $val;
	}


	/**
	 * Show Address Fields on edit user pages.
	 *
	 * @param WP_User $user
	 */
	public function add_customer_meta_fields( $user ) {
	
		$fields = xoo_el_fields()->get_fields( 'register' );

		$skipFields = xoo_el_fields()->skipFields;

		foreach ( $skipFields as $field_id ) {
			unset( $fields[ $field_id ] );
		}

		$fields = apply_filters( 'xoo_el_user_profile_fields', $fields );


		if( empty( $fields ) ) return;

		?>


		<h2>Login/Signup Pop up fields</h2>

		<table class="form-table xoo-aff-form-table">

			<?php

			foreach ( $fields as $field_id => $field_data ) {

				$args = array(
					'value' => get_user_meta( $user->ID, $field_id, true ),
				);

				$args = xoo_el()->aff->fields->get_field_html_args( $field_id, $args );

				$label = xoo_el()->aff->fields->get_field_label( $field_data );

				unset( $args['label'] );

				echo '<tr>';

				echo '<th>'. esc_html( $label) .'</th>';

				echo '<td>';

				if( $field_data['input_type'] === 'states' ){
					$args['input_type'] = 'text';
					$args['description'] = 'Add state code';
				}

				xoo_el()->aff->fields->get_input_html( $field_id, $args );

				echo '</td>';

				echo '</tr>';
			}

			?>

		</table>

		<?php
		

	}


	/**
	 * Save Address Fields on edit user pages.
	 *
	 * @param int $user_id User ID of the user being saved
	 */
	public function save_customer_meta_fields( $user_id ) {

		$save_fields = xoo_el_fields()->get_fields( 'register' );
		if( empty( $save_fields ) ) return;

		foreach ( $save_fields as $field_id => $field_data ) {

			if( isset( $_POST[ $field_id ] ) ){
				if( is_array( $_POST[ $field_id ] ) ){
					$value = array_map( 'sanitize_text_field', $_POST[ $field_id ] );
				}
				else{
					$value = sanitize_text_field( $_POST[ $field_id ] );
				}
			}
			else{
				$value = '';
			}
			update_user_meta( $user_id, $field_id, $value );
		}
	}

	/**
	 * Get user meta for a given key, with fallbacks to core user info for pre-existing fields.
	 *
	 * @since 3.1.0
	 * @param int    $user_id User ID of the user being edited
	 * @param string $field_id     Key for user meta field
	 * @return string
	 */
	protected function get_user_meta( $user_id, $field_id ) {
		$value           = get_user_meta( $user_id, $field_id, true );
		$existing_fields = array( 'billing_first_name', 'billing_last_name' );
		if ( ! $value && in_array( $field_id, $existing_fields ) ) {
			$value = get_user_meta( $user_id, str_replace( 'billing_', '', $field_id ), true );
		} elseif ( ! $value && ( 'billing_email' === $field_id ) ) {
			$user  = get_userdata( $user_id );
			$value = $user->user_email;
		}

		return $value;
	}
}


return new Xoo_El_User_Profile();
