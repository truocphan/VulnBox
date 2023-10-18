<?php
namespace WprAddons;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

final class Manager {

	public function __construct() {
    	$modules = Utilities::get_available_modules( Utilities::get_registered_modules() );

    	if ( empty(Utilities::get_available_modules( Utilities::get_registered_modules() )) && false === get_option('wpr-element-toggle-all') ) {
    		$modules = Utilities::get_registered_modules();
    	}

		foreach ( $modules as $data ) {
			$module = $data[0];

			$class_name = str_replace( '-', ' ', $module );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = __NAMESPACE__ .'\\Modules\\'. $class_name .'\Module';
			
			$class_name::instance();
		}

		// Theme Builder Modules
		$theme_builder_modules = Utilities::get_available_modules( Utilities::get_theme_builder_modules() );

    	if ( empty(Utilities::get_available_modules( Utilities::get_theme_builder_modules() )) && false === get_option('wpr-element-toggle-all') ) {
    		$theme_builder_modules = Utilities::get_theme_builder_modules();
    	}

		foreach ( $theme_builder_modules as $data ) {
			$module = $data[0];

			$class_name = str_replace( '-', ' ', $module );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = __NAMESPACE__ .'\\Modules\\ThemeBuilder\\'. $class_name .'\Module';
			
			$class_name::instance();
		}

		// Woocommerce Builder Modules
		if ( class_exists( 'woocommerce' ) ) {
			$woocommerce_builder_modules = Utilities::get_available_modules( Utilities::get_woocommerce_builder_modules() );
	
			if ( empty(Utilities::get_available_modules( Utilities::get_woocommerce_builder_modules() )) && false === get_option('wpr-element-toggle-all') ) {
				$woocommerce_builder_modules = Utilities::get_woocommerce_builder_modules();
			}
	

			foreach ( $woocommerce_builder_modules as $data ) {
				$module = $data[0];

				$class_name = str_replace( '-', ' ', $module );
				$class_name = str_replace( ' ', '', ucwords( $class_name ) );
				$class_name = __NAMESPACE__ . '\\Modules\\ThemeBuilder\\Woocommerce\\' . $class_name . '\Module';
				
				$class_name::instance();
			}
		}
	}
	
}