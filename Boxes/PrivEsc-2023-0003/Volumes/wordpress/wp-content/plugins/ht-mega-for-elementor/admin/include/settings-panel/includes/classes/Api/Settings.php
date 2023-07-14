<?php
namespace HTMegaOpt\Api;

use WP_REST_Controller;
use HTMegaOpt\SanitizeTrail\Sanitize_Trait;

if ( !class_exists( '\HTMegaOpt\Admin\Options_Field'  ) ) {
    require_once HTMEGAOPT_INCLUDES . '/classes/Admin/Options_field.php';
}

/**
 * REST_API Handler
 */
class Settings extends WP_REST_Controller {

    use Sanitize_Trait;

    protected $namespace;
    protected $rest_base;
    protected $slug;
    protected $errors;

    /**
	 * All registered settings.
	 *
	 * @var array
	 */
	protected $settings;

    /**
     * [__construct Settings constructor]
     */
    public function __construct() {
        $this->slug      = 'htmega_';
        $this->namespace = 'htmegaopt/v1';
        $this->rest_base = 'settings';
        $this->errors    = new \WP_Error();
        $this->settings  = \HTMegaOpt\Admin\Options_Field::instance()->get_registered_settings();

        add_filter( $this->slug . '_settings_sanitize', [ $this, 'sanitize_settings' ], 3, 10 );

    }

    /**
     * Register the routes
     *
     * @return void
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/'.$this->rest_base,
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_items' ],
                    'permission_callback' => [ $this, 'permissions_check' ],
                    'args'                => $this->get_collection_params(),
                ],

                [
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'create_items' ],
                    'permission_callback' => [ $this, 'permissions_check' ],
                    'args'                => $this->get_collection_params(),
                ]
            ]
        );

    }

    /**
     * Checks if a given request has access to read the items.
     *
     * @param  WP_REST_Request $request Full details about the request.
     *
     * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
     */
    public function permissions_check( $request ) {

        if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error( 'rest_forbidden', 'HTMEGA OPT: Permission Denied.', [ 'status' => 401 ] );
		}

		return true;
    }

    /**
     * Retrieves the query params for the items collection.
     *
     * @return array Collection parameters.
     */
    public function get_collection_params() {
        return [];
    }

    /**
     * Retrieves a collection of items.
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function get_items( $request ) {
        $items = [];

        $section = (string) $request['section'];
        if( !empty( $section ) ){
            $items = get_option( $section, true );
        }
        
        $response = rest_ensure_response( $items );
        return $response;
    }

    /**
     * Create item response
     */
    public function create_items( $request ) {

        if ( ! wp_verify_nonce( $request['settings']['verifynonce'], 'htmegaopt_verifynonce' ) ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $section            = ( !empty( $request['section'] ) ? sanitize_text_field( $request['section'] ) : '' );
        $settings_received  = ( !empty( $request['settings'] ) ? htmegaopt_data_clean( $request['settings'] ) : '' );
        $settings_reset     = ( !empty( $request['reset'] ) ? rest_sanitize_boolean( $request['reset'] ) : '' );

        // Data reset
        if( $settings_reset == true ){
            $reseted = delete_option( $section );
            return rest_ensure_response( $reseted );
        }

        if( empty( $section ) || empty( $settings_received ) ){
            return;
        }

        $get_settings = $this->settings[$section];
        $data_to_save = [];

        if ( is_array( $get_settings ) && ! empty( $get_settings ) ) {
			foreach ( $get_settings as $setting ) {

                // Skip if no setting type.
                if ( ! $setting['type'] ) {
                    continue;
                }

                // Skip if setting type is html.
                if ( $setting['type'] === 'html' ) {
                    continue;
                }

                // Skip if setting field is pro.
                if ( isset( $setting['is_pro'] ) && $setting['is_pro'] ) {
                    continue;
                }

                // Skip if the ID doesn't exist in the data received.
                if ( ! array_key_exists( $setting['id'], $settings_received ) ) {
                    continue;
                }

                // Sanitize the input.
                $setting_type = $setting['type'];
                $output       = apply_filters( $this->slug . '_settings_sanitize', $settings_received[ $setting['id'] ], $this->errors, $setting );
                $output       = apply_filters( $this->slug . '_settings_sanitize_' . $setting['id'], $output, $this->errors, $setting );

                if ( $setting_type == 'checkbox' && $output == false ) {
                    continue;
                }

                // Add the option to the list of ones that we need to save.
                if ( ! empty( $output ) && ! is_wp_error( $output ) ) {
                    $data_to_save[ $setting['id'] ] = $output;
                }

            }
        }

        if ( ! empty( $this->errors->get_error_codes() ) ) {
			return new \WP_REST_Response( $this->errors, 422 );
		}

		update_option( $section, $data_to_save );

		return rest_ensure_response( $data_to_save );
        
    }

    /**
     * Sanitize callback for Settings Data
     *
     * @return mixed
     */
    public function sanitize_settings( $setting_value, $errors, $setting ){

        if ( ! empty( $setting['sanitize_callback'] ) && is_callable( $setting['sanitize_callback'] ) ) {
            $setting_value = call_user_func( $setting['sanitize_callback'], $setting_value );
        } else {
            $setting_value = $this->default_sanitizer( $setting_value, $errors, $setting );
        }

        return $setting_value;

    }

    /**
     * If no Sanitize callback function from option field.
     *
     * @return mixed
     */
    public function default_sanitizer( $setting_value, $errors, $setting ){

        switch ( $setting['type'] ) {
            case 'text':
            case 'radio':
            case 'select':
                $finalvalue = $this->sanitize_text_field( $setting_value, $errors, $setting );
                break;

            case 'textarea':
                $finalvalue = $this->sanitize_textarea_field( $setting_value, $errors, $setting );
                break;

            case 'checkbox':
            case 'switcher':
            case 'element':
                $finalvalue = $this->sanitize_checkbox_field( $setting_value, $errors, $setting );
                break;

            case 'multiselect':
            case 'multicheckbox':
                $finalvalue = $this->sanitize_multiple_field( $setting_value, $errors, $setting );
                break;

            case 'file':
                $finalvalue = $this->sanitize_file_field( $setting_value, $errors, $setting );
                break;
            
            default:
                $finalvalue = sanitize_text_field( $setting_value );
                break;
        }

        return $finalvalue;

    }

}