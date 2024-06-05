<?php

//Get tempalte
if( !function_exists( 'xoo_aff_get_template' ) ){
	function xoo_aff_get_template ( $template_name, $path = '', $args = array(), $return = false ) {

	    $located = xoo_aff_locate_template ( $template_name, $path );

	    if ( $args && is_array ( $args ) ) {
	        extract ( $args );
	    }

	    if ( $return ) {
	        ob_start ();
	    }

	    // include file located
	    if ( file_exists ( $located ) ) {
	        include ( $located );
	    }

	    if ( $return ) {
	        return ob_get_clean ();
	    }


	}
}

//Locate template
if( !function_exists( 'xoo_aff_locate_template' ) ){
	function xoo_aff_locate_template ( $template_name, $template_path ) {

	    // Look within passed path within the theme - this is priority.
	    $located = '';

		$template_names = array(
			'templates/' . $template_name,
			$template_name,
		);

		foreach ( (array) $template_names as $template_name ) {
	        if ( !$template_name )
	            continue;
	        if ( file_exists('STYLESHEETPATH '. '/' . $template_name)) {
	            $located = 'STYLESHEETPATH' . '/' . $template_name;
	            break;
	        } elseif ( file_exists('TEMPLATEPATH' . '/' . $template_name) ) {
	            $located = 'TEMPLATEPATH' . '/' . $template_name;
	            break;
	        } elseif ( file_exists( ABSPATH . WPINC . '/theme-compat/' . $template_name ) ) {
	            $located = ABSPATH . WPINC . '/theme-compat/' . $template_name;
	            break;
	        }
	    }
	 
	    if ( '' != $located )
	        load_template( $located, $require_once );
	 
	    $template =  $located;

		//Check woocommerce directory for older version
		if( !$template && class_exists( 'woocommerce' ) ){
			if( file_exists( WC()->plugin_path() . '/templates/' . $template_name ) ){
				$template = WC()->plugin_path() . '/templates/' . $template_name;
			}
		}

		if( !$template & file_exists( dirname( XOO_AFF_DIR ). '/templates/' . $template_name ) ){
			$template = dirname( XOO_AFF_DIR ). '/templates/' . $template_name;
		}

	    if ( ! $template ) {
	        $template = trailingslashit( $template_path ) . $template_name;
	    }

	    return $template;
	}
}

if( !function_exists( 'xff_sanitize_text' ) ){
	function xff_sanitize_text( $var ){
		if ( is_array( $var ) ) {
			return array_map( 'xff_sanitize_text', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}
}


if( !function_exists( 'xff_wp_kses_post' ) ){
	function xff_wp_kses_post( $var ){
		if ( is_array( $var ) ) {
			return array_map( 'xff_wp_kses_post', $var );
		} else {
			return is_scalar( $var ) ? wp_kses_post( $var ) : $var;
		}
	}
}

?>