<?php
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

add_action( 'widgets_init', 'woo_slg_login_buttons_widget' );

/**
 * Register the Login Buttons Listing
 * 
 * Handles to register a widget
 * for showing active login buttons
 *
 * @package WooCommerce - Social Login
 * @since 1.1.0
 */
function woo_slg_login_buttons_widget() {
	register_widget( 'Woo_Slg_Login_Buttons' );
}

/**
 * WooCommerce WP Social Deals Widget Class. 
 *
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update for displaying submitted reviews.
 *
 * @package WooCommerce - Social Login
 * @since 1.1.0
 */ 
class Woo_Slg_Login_Buttons extends WP_Widget{

	var $model,$render;

	/**
	 * Widget setup.
	 */	
	function __construct() {	
		
		// Define global variable 
		global $woo_slg_model, $woo_slg_render;
		
		$this->model = $woo_slg_model;
		$this->render = $woo_slg_render;
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'woo-slg-login-buttons', 'description' => esc_html__('A social login widget.', 'wooslg') );

		/* Create the widget. */	
		parent::__construct( 'woo-slg-login-buttons', esc_html__('WooCommerce - Social Login', 'wooslg'), $widget_ops );
	}
	
	/**
	 * Outputs the content of the widget
	 * 
	 * Handles to show output of widget 
	 * at front side sidebar
	 * 
	 * @package WooCommerce - Social Login
 	 * @since 1.1.0
	 */
	function widget( $args, $instance ) {		
		
		// Define global variable 
		global $wpdb, $post, $woo_slg_options;
		
		extract( $args );
		
		$title = apply_filters( 'widget_title', $instance['title'] );

		//Get widget expand / collapse social login option
		$expand_collapse = isset( $instance['expand_collapse'] ) ? $instance['expand_collapse'] : '';
		
		if( ! is_user_logged_in() && woo_slg_check_social_enable() ) {			
			
	    	echo $before_widget;
	    
	    	// get redirect url from settings 
			$defaulturl = ! empty($woo_slg_options['woo_slg_redirect_url']) ? $woo_slg_options['woo_slg_redirect_url'] : woo_slg_get_current_page_url();
						
			//session create for redirect url 
			\WSL\PersistentStorage\WOOSLGPersistent::set( 'woo_slg_stcd_redirect_url_widget', $defaulturl );

			$expand_collapse_class	= '';
			$expand_collapse_enable = false;

			if( trim($expand_collapse) != '' ) {
				$expand_collapse_class	= $expand_collapse == "collapse" ? ' woo-slg-hide' : '';
				$expand_collapse_enable = true;
			}

			if( $expand_collapse_enable ) {

				echo '<p class="woo-slg-info">'. esc_html__($title, 'wooslg') . 
					' <a href="javascript:void(0);" class="woo-slg-show-social-login-widget">' . 
						esc_html__( 'Click here to login', 'wooslg' ) . 
					'</a>
				</p>';
				
				$expand_collapse_class	.= ' woo-slg-social-container-widget';
			}

	    	echo '<div class="woo-slg-social-container woo-slg-widget-content '. esc_attr( $expand_collapse_class ) .'">';
	    	
	    	//do action to add login with email section
			do_action( 'woo_slg_wrapper_login_with_email' );

	    	if( $expand_collapse_enable == false ) {
	        	echo $before_title . $title . $after_title;
	    	}

	        $this->render->woo_slg_social_login_inner_buttons();
	        
	        //do action to add login with email section
			do_action( 'woo_slg_wrapper_login_with_email_bottom' );

			//end container
	    	echo '</div><!--.woo-slg-widget-content-->';

			echo $after_widget;
		}
    }
	
	/**
	 * Updates the widget control options for the particular instance of the widget
	 *
	 * Handles to update widget data
	 * 
	 * @package WooCommerce - Social Login
 	 * @since 1.1.0 
	 *
	 */
	function update( $new_instance, $old_instance ) {
	
        $instance = $old_instance; 
		
		/* Set the instance to the new instance. */
		$instance = $new_instance;
		
		/* Input fields */
        $instance['title'] = strip_tags( $new_instance['title'] );

		/* update expand / collapse */
        $instance['expand_collapse'] = $new_instance['expand_collapse'];

        return $instance;
    }
	
	/**
	 * Displays the widget form in the admin panel
	 * Handles to show widget settings at backend
	 * 
	 * @package WooCommerce - Social Login
 	 * @since 1.1.0
	 * 
	 */
	function form( $instance ) {
	
		// Define global variable 
		global $woo_slg_options;
		
		// get title from settings
		$login_heading = isset($woo_slg_options['woo_slg_login_heading']) ? $woo_slg_options['woo_slg_login_heading'] : esc_html__('Prefer to Login with Social Media', 'wooslg');

		$defaults = array( 'title' => esc_html__($login_heading, 'wooslg'), 'expand_collapse' => '' );
		
        $instance = wp_parse_args( (array) $instance, $defaults );

        //Get selected expand / collapse
        $selected	= isset( $instance['expand_collapse'] ) ? $instance['expand_collapse'] : ''; ?>

		<p>			
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'wooslg'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>	
			<label for="<?php echo $this->get_field_id( 'expand_collapse' ); ?>"><?php esc_html_e( 'Expand/Collapse Social Login Buttons:', 'wooslg'); ?></label><br />
			<select id="<?php echo $this->get_field_id( 'expand_collapse' ); ?>" name="<?php echo $this->get_field_name( 'expand_collapse' ); ?>">
				<option value="" <?php echo selected( $selected, '' ); ?>><?php esc_html_e('None','wooslg'); ?></option>
				<option value="collapse" <?php echo selected( $selected, 'collapse' ); ?>><?php esc_html_e('Collapse','wooslg'); ?></option>
				<option value="expand" <?php echo selected( $selected, 'expand' ); ?>><?php esc_html_e('Expand','wooslg'); ?></option>
			</select>
		</p>

	<?php
	}
}