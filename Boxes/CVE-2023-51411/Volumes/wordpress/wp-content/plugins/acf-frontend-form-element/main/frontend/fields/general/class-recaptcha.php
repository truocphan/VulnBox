<?php
namespace Frontend_Admin\Field_Types;

/*
	Credit due to @hwk-fr
	Plugin Page: https://wordpress.org/plugins/acf-extended/

*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'recaptcha' ) ) :
	class recaptcha extends Field_Base {


		function initialize() {
			$site_key      = get_option( 'frontend_admin_google_recaptcha_site' );
			$secret_key    = get_option( 'frontend_admin_google_recaptcha_secret' );
			$site_key_v2   = get_option( 'frontend_admin_recaptcha_site_v2', $site_key );
			$secret_key_v2 = get_option( 'frontend_admin_recaptcha_secret_v2', $secret_key );
			$site_key_v3   = get_option( 'frontend_admin_recaptcha_site_v3', $site_key );
			$secret_key_v3 = get_option( 'frontend_admin_recaptcha_secret_v3', $secret_key );

			$this->name     = 'recaptcha';
			$this->label    = __( 'Google reCaptcha', 'acf-frontend-form-element' );
			$this->category = __( 'Security', 'acf-frontend-form-element' );
			$this->defaults = array(
				'required'      => 0,
				'disabled'      => 0,
				'readonly'      => 0,
				'version'       => 'v2',
				'v2_theme'      => 'light',
				'v2_size'       => 'normal',
				'v3_hide_logo'  => false,
				'site_key_v2'   => $site_key_v2,
				'secret_key_v2' => $secret_key_v2,
				'site_key_v3'   => $site_key_v3,
				'secret_key_v3' => $secret_key_v3,
			);

			add_action( 'frontend_admin/form_assets/type=recaptcha', array( $this, 'form_assets' ) );
		}

		function form_assets( $field ) {
			if ( ! empty( $field['version'] ) && $field['version'] === 'v3' ) {
				$global_site_key = get_option( 'frontend_admin_google_recaptcha_site' );
				$global_site_key = get_option( 'frontend_admin_recaptcha_site_v3', $global_site_key );

				if ( ! empty( $field['site_key'] ) ) {
					$site_key = $field['site_key'];
				}
				if ( ! empty( $field['site_key_v3'] ) ) {
					$site_key = $field['site_key_v3'];
				}
				if ( empty( $site_key ) ) {
					$site_key = $global_site_key;
				}

				?>            
			   <script src="https://www.google.com/recaptcha/api.js?render=<?php esc_attr_e( $site_key ); ?>" async defer></script>
							
				<?php
			}
		}


		function render_field_settings( $field ) {
			// Version
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Version', 'acf-frontend-form-element' ),
					'instructions' => __( 'Select the reCaptcha version', 'acf-frontend-form-element' ),
					'type'         => 'select',
					'name'         => 'version',
					'choices'      => array(
						'v2' => __( 'reCaptcha V2', 'acf-frontend-form-element' ),
						'v3' => __( 'reCaptcha V3', 'acf-frontend-form-element' ),
					),
				)
			);

			// V2 Theme
			acf_render_field_setting(
				$field,
				array(
					'label'             => __( 'Theme', 'acf-frontend-form-element' ),
					'instructions'      => __( 'Select the reCaptcha theme', 'acf-frontend-form-element' ),
					'type'              => 'select',
					'name'              => 'v2_theme',
					'choices'           => array(
						'light' => __( 'Light', 'acf-frontend-form-element' ),
						'dark'  => __( 'Dark', 'acf-frontend-form-element' ),
					),
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'version',
								'operator' => '==',
								'value'    => 'v2',
							),
						),
					),
				)
			);

			// V2 Size
			acf_render_field_setting(
				$field,
				array(
					'label'             => __( 'Size', 'acf-frontend-form-element' ),
					'instructions'      => __( 'Select the reCaptcha size', 'acf-frontend-form-element' ),
					'type'              => 'select',
					'name'              => 'v2_size',
					'choices'           => array(
						'normal'  => __( 'Normal', 'acf-frontend-form-element' ),
						'compact' => __( 'Compact', 'acf-frontend-form-element' ),
					),
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'version',
								'operator' => '==',
								'value'    => 'v2',
							),
						),
					),
				)
			);

			// V3 Hide Logo
			acf_render_field_setting(
				$field,
				array(
					'label'             => __( 'Hide logo', 'acf-frontend-form-element' ),
					'instructions'      => __( 'Hide the reCaptcha logo', 'acf-frontend-form-element' ),
					'type'              => 'true_false',
					'name'              => 'v3_hide_logo',
					'ui'                => true,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'version',
								'operator' => '==',
								'value'    => 'v3',
							),
						),
					),
				)
			);

			// Site Key
			acf_render_field_setting(
				$field,
				array(
					'label'             => __( 'Site key', 'acf-frontend-form-element' ),
					'instructions'      => __( 'Enter the site key. <a href="https://www.google.com/recaptcha/admin" target="_blank">reCaptcha API Admin</a>', 'acf-frontend-form-element' ),
					'type'              => 'text',
					'name'              => 'site_key_v2',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'version',
								'operator' => '==',
								'value'    => 'v2',
							),
						),
					),
				)
			);

			// Site Secret
			acf_render_field_setting(
				$field,
				array(
					'label'             => __( 'Secret key', 'acf-frontend-form-element' ),
					'instructions'      => __( 'Enter the secret key. <a href="https://www.google.com/recaptcha/admin" target="_blank">reCaptcha API Admin</a>', 'acf-frontend-form-element' ),
					'type'              => 'text',
					'name'              => 'secret_key_v2',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'version',
								'operator' => '==',
								'value'    => 'v2',
							),
						),
					),
				)
			);

			// Site Key
			acf_render_field_setting(
				$field,
				array(
					'label'             => __( 'Site key', 'acf-frontend-form-element' ),
					'instructions'      => __( 'Enter the site key. <a href="https://www.google.com/recaptcha/admin" target="_blank">reCaptcha API Admin</a>', 'acf-frontend-form-element' ),
					'type'              => 'text',
					'name'              => 'site_key_v3',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'version',
								'operator' => '==',
								'value'    => 'v3',
							),
						),
					),
				)
			);

			// Site Secret
			acf_render_field_setting(
				$field,
				array(
					'label'             => __( 'Secret key', 'acf-frontend-form-element' ),
					'instructions'      => __( 'Enter the secret key. <a href="https://www.google.com/recaptcha/admin" target="_blank">reCaptcha API Admin</a>', 'acf-frontend-form-element' ),
					'type'              => 'text',
					'name'              => 'secret_key_v3',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'version',
								'operator' => '==',
								'value'    => 'v3',
							),
						),
					),
				)
			);

		}


		function prepare_field( $field ) {
			if ( $field['version'] === 'v3' ) {

				$field['field_label_hide'] = 1;

			}

			return $field;

		}

		function render_field( $field ) {
			$form = $GLOBALS['admin_form'];
			if ( ! empty( $form['preview_mode'] ) ) {
				return;
			}

			// Version
			$field['version'] = $field['version'] ? $field['version'] : 'v3';

			$global_site_key = get_option( 'frontend_admin_google_recaptcha_site' );
			$global_site_key = get_option( 'frontend_admin_recaptcha_site_' . $field['version'], $global_site_key );

			if ( ! empty( $field['site_key'] ) ) {
				$site_key = $field['site_key'];
			}
			if ( ! empty( $field[ 'site_key_' . $field['version'] ] ) ) {
				$site_key = $field[ 'site_key_' . $field['version'] ];
			}
			if ( empty( $site_key ) ) {
				$site_key = $global_site_key;
			}

			// V2
			if ( $field['version'] === 'v2' ) {
				?>
			
				<?php

				$wrapper = array(
					'class'         => 'acf-input-wrap frontend-admin-recaptcha',
					'data-site-key' => $site_key,
					'data-version'  => 'v2',
					'data-size'     => $field['v2_size'],
					'data-theme'    => $field['v2_theme'],
				);

				$hidden_input = array(
					'id'   => $field['id'],
					'name' => $field['name'],
				);

				?>
				<div <?php acf_esc_attr_e( $wrapper ); ?>>
					<div></div>
					<?php acf_hidden_input( $hidden_input ); ?>                    
				</div>
				
				<script src="https://www.google.com/recaptcha/api.js?render=explicit&onload=<?php esc_attr_e( $field['key'] ); ?>Recaptcha" async defer></script>
				<script type="text/javascript">
				var <?php esc_html_e( $field['key'] ); ?>Recaptcha = function() {
				   acf.getField('<?php esc_html_e( $field['key'] ); ?>').onLoad();
				};
				</script>

				<?php
				return;

			}

			// V3
			elseif ( $field['version'] === 'v3' ) {

				$wrapper = array(
					'class'         => 'acf-input-wrap frontend-admin-recaptcha',
					'data-site-key' => $site_key,
					'data-version'  => 'v3',
				);

				$hidden_input = array(
					'id'   => $field['id'],
					'name' => $field['name'],
				);

				?>
				<div <?php acf_esc_attr_e( $wrapper ); ?>>
					
					<div></div>
					<?php acf_hidden_input( $hidden_input ); ?>
					
				</div>

				<?php if ( $field['v3_hide_logo'] ) { ?>
					<style>
					.grecaptcha-badge{
						display: none;
						visibility: hidden;
					}
					</style>
					<?php
				}

				return;

			}

		}

		function validate_value( $valid, $value, $field, $input ) {
			if ( ! $value && $field['version'] == 'v2' ) {
				return __( 'Please fill the captcha', 'acf-frontend-form-element' );
			}

			// Expired
			if ( $value === 'expired' ) {

				return __( 'reCaptcha has expired.' );

			}

			// Error
			elseif ( $value === 'error' ) {

				return __( 'An error has occured.' );

			}

			// Empty & Required
			if ( empty( $value ) && $field['required'] ) {

				return $valid;

			}

			// Success
			else {

				// Secret key
				$global_secret_key = get_option( 'frontend_admin_google_recaptcha_site' );
				$global_secret_key = get_option( 'frontend_admin_recaptcha_site_' . $field['version'], $global_secret_key );

				if ( ! empty( $field['secret_key'] ) ) {
					$secret_key = $field['secret_key'];
				}

				if ( ! empty( $field[ 'secret_key_' . $field['version'] ] ) ) {
					$secret_key = $field[ 'secret_key_' . $field['version'] ];
				}

				if ( empty( $secret_key ) ) {
					$secret_key = $global_secret_key;
				}

				return $this->validate_recaptcha( $value, $secret_key );

			}

			return __( 'Something went wrong. Recaptcha failed', 'acf-frontend-form-element' );

		}

		function validate_recaptcha( $response, $secret_key ) {
			 // Verifying the user's response (https://developers.google.com/recaptcha/docs/verify)
			$verifyURL = 'https://www.google.com/recaptcha/api/siteverify';

			// Collect and build POST data
			$post_data = http_build_query(
				array(
					'secret'   => $secret_key,
					'response' => $response,
					'remoteip' => ( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'] ),
				)
			);

			// Send data on the best possible way
			if ( function_exists( 'wp_remote_post' ) ) {
				$response = wp_remote_post(
					$verifyURL,
					array(
						'method'      => 'POST',
						'body'        => $post_data,
						'timeout'     => 60,
						'redirection' => 5,
						'blocking'    => true,
						'httpversion' => '1.0',
						'sslverify'   => false,
						'data_format' => 'body',
						'headers'     => array(
							'accept'       => 'application/json',
							'content-type' => 'application/x-www-form-urlencoded',
						),
					)
				);
				$response = wp_remote_retrieve_body( $response );
			} else {
				// If server not have active cURL module, use file_get_contents
				$opts     = array(
					'http' =>
											array(
												'method'  => 'POST',
												'header'  => 'Content-type: application/x-www-form-urlencoded',
												'content' => $post_data,
											),
				);
				$context  = stream_context_create( $opts );
				$response = file_get_contents( $verifyURL, false, $context );
			}

			// Verify all reponses and avoid PHP errors
			if ( $response ) {
				$result = json_decode( $response );
				if ( $result->success === true ) {
					return true;
				} else {
					return __( 'Something is wrong with the recaptcha.', 'acf-frontend-form-element' );
				}
			}
			// Dead end
			return false;
		}

		function update_value( $value, $post_id, $field ) {
			// Do not save field value
			return null;

		}

	}


endif;
