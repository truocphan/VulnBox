<?php
/**
 * Newsletter data.
 */

// If this file is accessed directly, exit.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class.
 */
if ( ! class_exists( 'HTMega_Newsletter_Data' ) ) {
    class HTMega_Newsletter_Data {

        /**
         * Instance.
         */
        private static $_instance = null;

		/**
		 * Get instance.
		 */
		public static function get_instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

        /**
         * Constructor.
         */
        private function __construct() {
            add_action( 'wp_ajax_htmega_newsletter_subscribe', array( $this, 'process_data' ) );
            add_action( 'wp_ajax_nopriv_htmega_newsletter_subscribe', array( $this, 'process_data' ) );
        }

        /**
         * Process data.
         */
        public function process_data() {
            $email = ( isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '' );

            $response = array();

            if ( is_email( $email ) ) {
                $data = $this->prepare_data( $email );

                if ( ! empty( $data ) ) {
                    $request = $this->send_request( $data );

                    if ( ! is_wp_error( $request ) ) {
                        $response = array(
                            'status' => 'success',
                            'message' => esc_html__( 'Successfully subscribed.', 'htmega-addons' ),
                        );

                        update_option( 'htmega_newsletter_subscribed', true );
                    } else {
                        $response = array(
                            'status' => 'error',
                            'message' => esc_html__( 'Something went wrong.', 'htmega-addons' ),
                            'request' => $request,
                        );
                    }
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => esc_html__( 'Invalid data.', 'htmega-addons' ),
                    );
                }
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => esc_html__( 'Invalid email.', 'htmega-addons' ),
                );
            }

            wp_send_json( $response );
        }

        /**
         * Prepare data.
         */
        private function prepare_data( $email = '' ) {
            if ( ! function_exists( 'is_plugin_active' ) || ! function_exists( 'get_plugins' ) || ! function_exists( 'get_plugin_data' ) ) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }

            $plugins = get_plugins();

            $project_pro = 'htmega-pro/htmega_pro.php';
            $project_pro_data = ( ( isset( $plugins[ $project_pro ] ) && is_array( $plugins[ $project_pro ] ) ) ? $plugins[ $project_pro ] : array() );

            $project_pro_active = ( ( true === is_plugin_active( $project_pro ) ) ? 'yes' : 'no' );
            $project_pro_installed = ( isset( $plugins[ $project_pro ] ) ? 'yes' : 'no' );
            $project_pro_version = ( isset( $project_pro_data['Version'] ) ? sanitize_text_field( $project_pro_data['Version'] ) : '' );

            $user_data = get_user_by( 'email', $email );

            $user_first_name = '';
            $user_last_name = '';
            $user_nicename = '';
            $user_display_name = '';
            $user_full_name = '';

            if ( ! empty( $user_data ) ) {
                $user_first_name = ( isset( $user_data->first_name ) ? trim( $user_data->first_name ) : '' );
                $user_last_name = ( isset( $user_data->last_name ) ? trim( $user_data->last_name ) : '' );
                $user_nicename = ( isset( $user_data->user_nicename ) ? trim( $user_data->user_nicename ) : '' );
                $user_display_name = ( isset( $user_data->display_name ) ? trim( $user_data->display_name ) : '' );

                if ( empty( $user_first_name ) ) {
                    if ( ! empty( $user_last_name ) ) {
                        $user_first_name = $user_last_name;
                        $user_last_name = '';
                    } elseif ( ! empty( $user_nicename ) ) {
                        $user_first_name = $user_nicename;
                    } elseif ( ! empty( $user_display_name ) ) {
                        $user_first_name = $user_display_name;
                    }
                }

                if ( ! empty( $user_first_name ) && ! empty( $user_last_name ) ) {
                    $user_full_name = sprintf( '%1$s %2$s', $user_first_name, $user_last_name );
                } elseif ( ! empty( $user_first_name ) ) {
                    $user_full_name = $user_first_name;
                }
            }

            $hash = md5( current_time( 'U', true ) );

            $project = array(
                'name'          => 'HT Mega',
                'type'          => 'wordpress-plugin',
                'version'       => HTMEGA_VERSION,
                'pro_active'    => $project_pro_active,
                'pro_installed' => $project_pro_installed,
                'pro_version'   => $project_pro_version,
            );

            $data = array(
                'hash'       => $hash,
                'project'    => $project,
                'subscriber' => array(
                    'email' => $email,
                    'first_name' => $user_first_name,
                    'last_name' => $user_last_name,
                    'full_name' => $user_full_name,
                ),
            );

            return $data;
        }

        /**
         * Send request.
         */
        private function send_request( $data = array() ) {
            $data_center = 'https://connect.pabbly.com/workflow/sendwebhookdata/IjU3NjIwNTY5MDYzMzA0MzA1MjY4NTUzMCI_3D_pc';
            $headers = array( 'Content-Type' => 'application/json', 'Accept' => 'application/json' );
            $body = wp_json_encode( $data );

            $response = wp_remote_post( $data_center, array(
                'method'      => 'POST',
                'timeout'     => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => false,
                'headers'     => $headers,
                'body'        => $body,
                'cookies'     => array(),
            ) );

            return $response;
        }

    }

    // Returns the instance.
    HTMega_Newsletter_Data::get_instance();
}