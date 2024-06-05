<?php

if( !function_exists( 'xoo_framework_includes' ) ){

	if( !defined( 'XOO_FW_DIR' ) ){
		define( 'XOO_FW_DIR' , __DIR__ );
	}

	function xoo_framework_includes(){
		require_once __DIR__.'/class-xoo-helper.php';
		require_once __DIR__.'/class-xoo-exception.php';
	}

	xoo_framework_includes();

}

if (!function_exists('array_is_list')) {
    function array_is_list(array $arr)
    {
        if ($arr === []) {
            return true;
        }
        return array_keys($arr) === range(0, count($arr) - 1);
    }
}

if ( ! function_exists( 'xoo_recursive_parse_args' ) ) {
	function xoo_recursive_parse_args( $args, $defaults ) {
		$new_args = (array) $defaults;

		foreach ( $args as $key => $value ) {
			if ( is_array( $value ) && isset( $new_args[ $key ] ) && !array_is_list( $value ) ) {
				$new_args[ $key ] = xoo_recursive_parse_args( $value, $new_args[ $key ] );
			}
			else {
				$new_args[ $key ] = $value;
			}
		}

		return $new_args;
    }
}