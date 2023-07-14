<?php
/**
 * [htmegaopt_data_clean] clean array data
 *
 * @param [array] $var
 * @return void
 */
function htmegaopt_data_clean( $var ) {
    if ( is_array( $var ) ) {
        return array_map( 'htmegaopt_data_clean', $var );
    } else {
        return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
    }
}

/**
 * Get Options Value
 *
 * @param [type] $key
 * @param [type] $section
 * @param boolean $default
 * @return void
 */
function htmegaopt_get_option( $key, $section, $default = false ){
    $options = get_option( $section );
    if ( isset( $options[$key] ) ) {
        $value = $options[$key];
    }else{
        $value = $default;
    }
    return apply_filters( 'htmegaopt' . '_get_option_' . $key, $value, $key, $default );
}

/**
 * Get Option value Section wise
 *
 * @param [array] $registered_settings
 * @return void
 */
function htmegaopt_get_options( $registered_settings = [] ) {
    if( ! is_array( $registered_settings ) ){
        return;
    }
    $settings = [];
    $options = [];
    foreach ( $registered_settings as $section_key => $setting_section ) {
        foreach ( $setting_section as $setting ) {
            $default                   = isset( $setting['std'] ) ? $setting['std'] : ( isset( $setting['default'] ) ? $setting['default'] : '' );
            $options[ $setting['id'] ] = htmegaopt_get_option( $setting['id'], $section_key, $default );
        }
        $settings[$section_key] = $options;
        $options = [];
    }
    return apply_filters( 'htmegaopt' . '_get_settings', $settings );

}