<?php
// Exit if accessed directly
if( ! defined('ABSPATH') ) exit;

/**
 * Enqueue the block's assets for the editor.
 */
function woo_social_login_editor_assets(){

	// Enqueue block editor scripts
	wp_register_script(
		'woo-social-login-block',
		plugins_url( 'block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ), 
		filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
	);

	// Enqueue block editor styles
    wp_enqueue_style(
        'woo-social-login-block',
        plugins_url( 'social-login-block.css', __FILE__ ),
        filemtime( plugin_dir_path( __FILE__ ) . 'social-login-block.css' ) 
    );
}

add_action( 'enqueue_block_editor_assets', 'woo_social_login_editor_assets' );

/**
 * Handle Woocommerce - Social Login Block Registering
 */
function woo_register_social_login_block() {

	if( function_exists('register_block_type') ) {
		
		$args = array(
			'editor_script'   => 'woo-social-login-block' ,
			'attributes'      => array(
	            'title'    => array(
	                'type'      => 'string',
	                'default'   => esc_html__('Prefer to Login with Social Media', 'wooslg'),
	            ),
	            'networks' => array(
	                'type'      => 'array',
	                'default'   => array(),
	                'items'   => array(
						'type' => 'string',
					)
	            ),
	            'redirect_url' => array(
	                'type'      => 'url',
	                'default'   => '',
	            ),
	            'showonpage' => array(
	                'type'      => 'boolean',
	                'default'   => false,
	            ),
	            'expand_collapse' => array(
	                'type'      => 'string',
	                'default'   => '',
	            ),
        	),
			'render_callback' => 'woo_render_block_social_login',
		);
    
		// register woocommerce social login custom block
		register_block_type( 'wpweb/woo-social-login-block', $args );
	}
    
}
add_action( 'init', 'woo_register_social_login_block' );


/**
 * Handle Woocommerce - Social Login Block Rendering
 */
function woo_render_block_social_login( $attributes ) {
	extract( $attributes );

	// Define global variables
    global $woo_slg_options, $woo_slg_render, $post;

    $content = '' ; 		
    $showblock = false;

    // check if user is not login or access via admin block
    if( !is_user_logged_in() || isset($_GET['context']) && $_GET['context'] == 'edit' ) {
	   $showblock = true;
    }

	$showbuttons = true;
	$is_login_with_email = true;

	$check_individual = empty( $attributes['networks'] ) ? true : false;
	
	// if show only on inners pages is set and current page is not inner page 
	if( !empty($showonpage) && ! is_singular() ) {
		
		$showbuttons = false; 
		
		if( isset($_GET['context']) && $_GET['context'] == 'edit' ) {
			$showbuttons = true; 
		}
	}
	
	//check show social buttFons or not
	if( $showbuttons ) {

		if( !empty($attributes['networks']) && is_array($attributes['networks']) ) {

			foreach( $attributes['networks'] as $network ) {
				if( !empty( $woo_slg_options['woo_slg_enable_'.esc_attr($network)] ) && $woo_slg_options['woo_slg_enable_'.esc_attr($network)] == 'yes' ){
					$check_individual = true;
				}
			}
		}

		//check user is logged in to site or not and any single social login button is enable or not
		if( $showblock && woo_slg_check_social_enable() && $check_individual ) {

			$attributes['networks'] = empty( $attributes['networks'] ) ? array() : $attributes['networks'];
			
			$woo_slg_shortcodes = new WOO_Slg_Shortcodes();
			$content = $woo_slg_shortcodes->woo_slg_social_login( $attributes, '');
			
		} else {
			
			if( isset($_GET['context']) && $_GET['context'] == 'edit' ) {
				ob_start();

				echo '<div class="woo_slg_no_content">Please make sure atleast one of the social network is enabled. <a href="admin.php?page=woo-social-settings">Click Here</a> to configure social networks.</div>';

				$content .= ob_get_clean();
			}
		}

	}
	return $content;
}