<?php

/**
 * [htmega_get_elementor] Get elementor instance
 * @return [\Elementor\Plugin]
 */
function htmega_get_elementor() {
    return \Elementor\Plugin::instance();
}

/**
 * [htmega_get_elementor_option]
 * @param  [string] $key Option Key
 * @param  [int] $post_id page id
 * @return [string] custom value
 */
function htmega_get_elementor_option( $key, $post_id ){
    // Get the page settings manager
    $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

    // Get the settings model for current post
    $page_settings_model = $page_settings_manager->get_model( $post_id );

    // Retrieve value
    $elget_value = $page_settings_model->get_settings( $key );
    return $elget_value;
}


/**
* Elementor Version check
* Return boolean value
*/
function htmega_is_elementor_version( $operator = '<', $version = '2.6.0' ) {
    return defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, $version, $operator );
}

// Compatibility with elementor version 3.6.1
function htmega_widget_register_manager($widget_class){
    $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
    
    if ( htmega_is_elementor_version( '>=', '3.5.0' ) ){
        $widgets_manager->register( $widget_class );
    }else{
        $widgets_manager->register_widget_type( $widget_class );
    }
}

/*
 * Plugisn Options value
 * return on/off
 */
if( !function_exists('htmega_get_option') ){
    function htmega_get_option( $option, $section, $default = '' ){
        $options = get_option( $section );
        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }
        return $default;
    }
}

/*
 * Elementor Templates List
 * return array
 */
if( !function_exists('htmega_elementor_template') ){
    function htmega_elementor_template( $args = [] ) {
        if( class_exists('\Elementor\Plugin') ){
            $template_instance = \Elementor\Plugin::instance()->templates_manager->get_source( 'local' );
            
            $defaults = [
                'post_type' => 'elementor_library',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order' => 'ASC',
                'meta_query' => [
                    [
                        'key' => '_elementor_template_type',
                        'value' => $template_instance::get_template_types()
                    ],
                ],
            ];
            $query_args = wp_parse_args( $args, $defaults );

            $templates_query = new \WP_Query( $query_args );

            $templates = [];
            if ( $templates_query->have_posts() ) {
                $templates = [ '0' => __( 'Select Template', 'htmega-addons' ) ];
                foreach ( $templates_query->get_posts() as $post ) {
                    $templates[$post->ID] = $post->post_title . '(' . $template_instance::get_template_type( $post->ID ). ')';
                }
            }else{
                $templates = [ '0' => __( 'Do not Saved Templates.', 'htmega-addons' ) ];
            }

            wp_reset_query();

            return $templates;

        }else{
            return array( '0' => __( 'Do not Saved Templates.', 'htmega-addons' ) );
        }
    }
}

/*
 * Elementor Setting page value
 * return $elget_value
 */
if( !function_exists('htmega_get_elementor_setting') ){
    function htmega_get_elementor_setting( $key, $post_id ){
        // Get the page settings manager
        $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

        // Get the settings model for current post
        $page_settings_model = $page_settings_manager->get_model( $post_id );

        // Retrieve value
        $elget_value = $page_settings_model->get_settings( $key );
        return $elget_value;
    }
}


/*
 * Sidebar Widgets List
 * return array
 */
if( !function_exists('htmega_sidebar_options') ){
    function htmega_sidebar_options() {
        global $wp_registered_sidebars;
        $sidebar_options = array();

        if ( ! $wp_registered_sidebars ) {
            $sidebar_options['0'] = __( 'No sidebars were found', 'htmega-addons' );
        } else {
            $sidebar_options['0'] = __( 'Select Sidebar', 'htmega-addons' );
            foreach ( $wp_registered_sidebars as $sidebar_id => $sidebar ) {
                $sidebar_options[ $sidebar_id ] = $sidebar['name'];
            }
        }
        return $sidebar_options;
    }
}

/*
 * Get Taxonomy
 * return array
 */
if( !function_exists('htmega_get_taxonomies') ){
    function htmega_get_taxonomies( $htmega_texonomy = 'category' ){
        $terms = get_terms( array(
            'taxonomy' => $htmega_texonomy,
            'hide_empty' => true,
        ));
        $options = array();
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
            foreach ( $terms as $term ) {
                $options[ $term->slug ] = $term->name;
            }
            return $options;
        }
    }
}

/*
 * Get Post Type
 * return array
 */
if( !function_exists('htmega_get_post_types') ){
    function htmega_get_post_types( $args = [] ) {
        $post_type_args = [
            'show_in_nav_menus' => true,
        ];
        if ( ! empty( $args['post_type'] ) ) {
            $post_type_args['name'] = $args['post_type'];
        }
        $_post_types = get_post_types( $post_type_args , 'objects' );

        $post_types  = [];
        if( !empty( $args['defaultadd'] ) ){
            $post_types[ strtolower($args['defaultadd']) ] = ucfirst($args['defaultadd']);
        }
        foreach ( $_post_types as $post_type => $object ) {
            $post_types[ $post_type ] = $object->label;
        }
        return $post_types;
    }
}

/*
 * HTML Tag list
 * return array
 */
if( !function_exists('htmega_html_tag_lists') ){
    function htmega_html_tag_lists() {
        $html_tag_list = [
            'h1'   => __( 'H1', 'htmega-addons' ),
            'h2'   => __( 'H2', 'htmega-addons' ),
            'h3'   => __( 'H3', 'htmega-addons' ),
            'h4'   => __( 'H4', 'htmega-addons' ),
            'h5'   => __( 'H5', 'htmega-addons' ),
            'h6'   => __( 'H6', 'htmega-addons' ),
            'p'    => __( 'p', 'htmega-addons' ),
            'div'  => __( 'div', 'htmega-addons' ),
            'span' => __( 'span', 'htmega-addons' ),
        ];
        return $html_tag_list;
    }
}

/*
 * HTML Tag Validation
 * return strig
 */
function htmega_validate_html_tag( $tag ) {
    $allowed_html_tags = [
        'article',
        'aside',
        'footer',
        'header',
        'section',
        'nav',
        'main',
        'div',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
        'p',
        'span',
    ];
    return in_array( strtolower( $tag ), $allowed_html_tags ) ? $tag : 'div';
}

/*
 * Custom Pagination
 */
if( !function_exists('htmega_custom_pagination') ){
    function htmega_custom_pagination( $totalpage ){
        $big = 999999999;
        echo '<div class="htbuilder-pagination">';
            echo paginate_links( array(
                'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format' => '?paged=%#%',
                'current' => max( 1, get_query_var('paged') ),
                'total' => $totalpage,
                'prev_text' => '&larr;', 
                'next_text' => '&rarr;', 
                'type'      => 'list', 
                'end_size'  => 3, 
                'mid_size'  => 3
            ) );
        echo '</div>';
    }
}

/*
 * Contact form list
 * return array
 */
if( !function_exists('htmega_contact_form_seven') ){
    function htmega_contact_form_seven(){
        $countactform = array();
        $htmega_forms_args = array( 'posts_per_page' => -1, 'post_type'=> 'wpcf7_contact_form' );
        $htmega_forms = get_posts( $htmega_forms_args );

        if( $htmega_forms ){
            foreach ( $htmega_forms as $htmega_form ){
                $countactform[$htmega_form->ID] = $htmega_form->post_title;
            }
        }else{
            $countactform[ esc_html__( 'No contact form found', 'htmega-addons' ) ] = 0;
        }
        return $countactform;
    }
}


/*
 * All Post Name
 * return array
 */
if( !function_exists('htmega_post_name') ){
    function htmega_post_name ( $post_type = 'post', $limit = 'default' ){
        if( $limit === 'default' ){
            $limit = htmega_get_option( 'loadpostlimit', 'htmega_general_tabs', '20' );
        }
        $options = array();
        $options = ['0' => esc_html__( 'None', 'htmega-addons' )];
        $wh_post = array( 'posts_per_page' => $limit, 'post_type'=> $post_type );
        $wh_post_terms = get_posts( $wh_post );
        if ( ! empty( $wh_post_terms ) && ! is_wp_error( $wh_post_terms ) ){
            foreach ( $wh_post_terms as $term ) {
                $options[ $term->ID ] = $term->post_title;
            }
            return $options;
        }
    }
}

/**
* Blog page return true
*/
if( !function_exists('htmega_builder_is_blog_page') ){
    function htmega_builder_is_blog_page() {
        global $post;
        //Post type must be 'post'.
        $post_type = get_post_type( $post );
        return (
            ( is_home() || is_archive() )
            && ( $post_type == 'post')
        ) ? true : false ;
    }
}

/**
 * Get all menu list
 * return array
 */
if( !function_exists('htmega_get_all_create_menus') ){
    function htmega_get_all_create_menus() {
        $raw_menus = wp_get_nav_menus();
        $menus     = wp_list_pluck( $raw_menus, 'name', 'term_id' );
        $parent    = isset( $_GET['parent_menu'] ) ? absint( $_GET['parent_menu'] ) : 0;
        if ( 0 < $parent && isset( $menus[ $parent ] ) ) {
            unset( $menus[ $parent ] );
        }
        return $menus;
    }
}

/*
 * Caldera Form
 * @return array
 */
if( !function_exists('htmega_caldera_forms_options') ){
    function htmega_caldera_forms_options() {
        if ( class_exists( 'Caldera_Forms' ) ) {
            $caldera_forms = Caldera_Forms_Forms::get_forms( true, true );
            $form_options  = ['0' => esc_html__( 'Select Form', 'htmega-addons' )];
            $form          = array();
            if ( ! empty( $caldera_forms ) && ! is_wp_error( $caldera_forms ) ) {
                foreach ( $caldera_forms as $form ) {
                    if ( isset($form['ID']) and isset($form['name'])) {
                        $form_options[$form['ID']] = $form['name'];
                    }   
                }
            }
        } else {
            $form_options = ['0' => esc_html__( 'Form Not Found!', 'htmega-addons' ) ];
        }
        return $form_options;
    }
}

/*
 * Check user Login and call this function
 */
global $user;
if ( empty( $user->ID ) ) {
    add_action('elementor/init', 'htmega_ajax_login_init' );
    add_action( 'elementor/init', 'htmega_ajax_register_init' );
}

/*
 * wp_ajax_nopriv Function
 */
function htmega_ajax_login_init() {
    add_action( 'wp_ajax_nopriv_htmega_ajax_login', 'htmega_ajax_login' );
}

/*
 * ajax login
 */
function htmega_ajax_login(){
    check_ajax_referer( 'ajax-login-nonce', 'security' );
    $user_data = array();
    $user_data['user_login'] = !empty( $_POST['username'] ) ? $_POST['username']: "";
    $user_data['user_password'] = !empty( $_POST['password'] ) ? $_POST['password']: "";
    $user_data['remember'] = true;
    $user_signon = wp_signon( $user_data, false );

    $messages = !empty( $_POST['messages'] ) ? $_POST['messages']: "";
    if( $messages ){
        $messages = json_decode( stripslashes( $messages ), true );
    }

    if ( is_wp_error($user_signon) ){

        $invalid_info = !empty( $messages['invalid_info'] ) ? esc_html( $messages['invalid_info'] ) : esc_html__('Invalid username or password!', 'htmega-addons');
        echo json_encode( [ 'loggeauth'=>false, 'message'=> $invalid_info ] );
    } else {
        $success_msg = !empty( $messages['success_msg'] ) ? esc_html( $messages['success_msg'] ) : esc_html__('Login Successfully', 'htmega-addons');
        echo json_encode( [ 'loggeauth'=>true, 'message'=> $success_msg ] );
    }
    wp_die();
}

/*
 * wp_ajax_nopriv Register Function
 */
function htmega_ajax_register_init() {
    add_action( 'wp_ajax_nopriv_htmega_ajax_register', 'htmega_ajax_register' );
}

/*
* Ajax Register Call back
*/
function htmega_ajax_register() {

    $user_data = array(
        'user_login'    => !empty( $_POST['reg_name'] ) ? $_POST['reg_name']: "",
        'user_pass'     => !empty( $_POST['reg_password'] ) ? $_POST['reg_password']: "",
        'user_email'    => !empty( $_POST['reg_email'] ) ? $_POST['reg_email']: "",
        'user_url'      => !empty( $_POST['reg_website'] ) ? $_POST['reg_website']: "",
        'first_name'    => !empty( $_POST['reg_fname'] ) ? $_POST['reg_fname']: "",
        'last_name'     => !empty( $_POST['reg_lname'] ) ? $_POST['reg_lname']: "",
        'nickname'      => !empty( $_POST['reg_nickname'] ) ? $_POST['reg_nickname']: "",
        'description' => !empty( $_POST['reg_bio'] ) ? $_POST['reg_bio']  : "",
        'role'        => !empty( $_POST['reg_role'] ) ? $_POST['reg_role']: get_option( 'default_role' ),
    );
    $messages = !empty( $_POST['messages'] ) ? $_POST['messages']: "";
    if( $messages ){
        $messages = json_decode( stripslashes( $messages ), true );
    }
    

    if( htmega_validation_data( $user_data ) !== true ){
        echo htmega_validation_data( $user_data, $messages  );
    }else{
        $register_user = wp_insert_user( $user_data );

        if ( is_wp_error( $register_user ) ){
            $server_error_msg = !empty( $messages['server_error_msg'] ) ? esc_html( $messages['server_error_msg'] ) : esc_html__('Something is wrong please check again!', 'htmega-addons');
            echo json_encode( [ 'registerauth' =>false, 'message'=> $server_error_msg ] );
        } else {
            $success_msg = !empty( $messages['success_msg'] ) ? esc_html( $messages['success_msg'] ) : esc_html__('Successfully Register', 'htmega-addons');
            echo json_encode( [ 'registerauth' =>true, 'message'=> $success_msg ] );
        }
    }
    wp_die();

}

// Register Data Validation
function htmega_validation_data( $user_data = null, $messages = null ){

    if( empty( $user_data['user_login'] ) || empty( $_POST['reg_email'] ) || empty( $_POST['reg_password'] ) ){

        $required_msg = !empty( $messages['required_msg'] ) ? esc_html( $messages['required_msg'] ) : esc_html__('Username, Password and E-Mail are required', 'htmega-addons');
        return json_encode( [ 'registerauth' =>false, 'message'=> $required_msg ] );
    }
    if( !empty( $user_data['user_login'] ) ){

        if ( 4 > strlen( $user_data['user_login'] ) ) {
            $user_length_msg = !empty( $messages['user_length_msg'] ) ? esc_html( $messages['user_length_msg'] ) : esc_html__('Username too short. At least 4 characters is required', 'htmega-addons');

            return json_encode( [ 'registerauth' =>false, 'message'=> $user_length_msg ] );
        }

        if ( username_exists( $user_data['user_login'] ) ){
            $user_exists_msg = !empty( $messages['user_exists_msg'] ) ? esc_html( $messages['user_exists_msg'] ) : esc_html__('Sorry, that username already exists!', 'htmega-addons');
            
            return json_encode( [ 'registerauth' =>false, 'message'=> $user_exists_msg ] );
        }

        if ( !validate_username( $user_data['user_login'] ) ) {

            $user_invalid_msg = !empty( $messages['user_invalid_msg'] ) ? esc_html( $messages['user_invalid_msg'] ) : esc_html__('Sorry, the username you entered is not valid', 'htmega-addons');
            
            return json_encode( [ 'registerauth' =>false, 'message'=> $user_invalid_msg ] );
        }

    }
    if( !empty( $user_data['user_pass'] ) ){
        if ( 5 > strlen( $user_data['user_pass'] ) ) {

            $password_length_msg = !empty( $messages['password_length_msg'] ) ? esc_html( $messages['password_length_msg'] ) : esc_html__('Password length must be greater than 5', 'htmega-addons');
            
            return json_encode( [ 'registerauth' =>false, 'message'=> $password_length_msg ] );
        }
    }
    if( !empty( $user_data['user_email'] ) ){
        if ( !is_email( $user_data['user_email'] ) ) {

            $invalid_email_msg = !empty( $messages['invalid_email_msg'] ) ? esc_html( $messages['invalid_email_msg'] ) : esc_html__('Email is not valid', 'htmega-addons');
            
            return json_encode( [ 'registerauth' =>false, 'message'=> $invalid_email_msg ] );
        }
        if ( email_exists( $user_data['user_email'] ) ) {
            $email_exists_msg = !empty( $messages['email_exists_msg'] ) ? esc_html( $messages['email_exists_msg'] ) : esc_html__('Email Already in Use', 'htmega-addons');
            
            return json_encode( [ 'registerauth' =>false, 'message'=> $email_exists_msg ] );
        }
    }
    if( !empty( $user_data['user_url'] ) ){
        if ( !filter_var( $user_data['user_url'], FILTER_VALIDATE_URL ) ) {
            $invalid_url_msg = !empty( $messages['invalid_url_msg'] ) ? esc_html( $messages['invalid_url_msg'] ) : esc_html__('Website is not a valid URL', 'htmega-addons');
            
            return json_encode( [ 'registerauth' =>false, 'message'=> $invalid_url_msg ] );
        }
    }
    return true;

}

/*
 * Redirect 404 page select from plugins options
 */
if( !function_exists('htmega_redirect_404') ){
    function htmega_redirect_404() {
        $errorpage_id = htmega_get_option( 'errorpage','htmega_general_tabs' );
        if ( is_404() && !empty ( $errorpage_id ) ) {
            wp_redirect( esc_url( get_page_link( $errorpage_id ) ) ); die();
        }
    }
    add_action('template_redirect','htmega_redirect_404');
}


/*
 * All list of allowed html tags.
 *
 * @param string $tag_type Allowed levels are title and desc
 * @return array
 */
function htmega_get_html_allowed_tags($tag_type = 'title') {
	$accept_html_tags = [
        'span'   => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'strong' => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'br'     => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],        
		'b'      => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
        'sub'    => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'sup'    => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'i'      => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'u'      => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		's'      => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'em'     => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'del'    => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'ins'    => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],

		'code'   => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'mark'   => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'small'  => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'strike' => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'abbr'   => [
			'title' => [],
			'class' => [],
			'id'    => [],
			'style' => [],
		],
	];

	if ('desc' === $tag_type) {
		$desc_tags = [
            'h1' => [
                'class' => [],
                'id'    => [],
                'style' => [],
            ],
            'h2' => [
                'class' => [],
                'id'    => [],
                'style' => [],
            ],
            'h3' => [
                'class' => [],
                'id'    => [],
                'style' => [],
            ],
            'h4' => [
                'class' => [],
                'id'    => [],
                'style' => [],
            ],
            'h5' => [
                'class' => [],
                'id'    => [],
                'style' => [],
            ],
            'h6' => [
                'class' => [],
                'id'    => [],
                'style' => [],
            ],
            'p' => [
                'class' => [],
                'id'    => [],
                'style' => [],
            ],
			'a'       => [
				'href'  => [],
				'title' => [],
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'q'       => [
				'cite'  => [],
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'img'     => [
				'src'    => [],
				'alt'    => [],
				'height' => [],
				'width'  => [],
				'class'  => [],
				'id'     => [],
				'title'  => [],
				'style'  => [],
			],
			'dfn'     => [
				'title' => [],
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'time'    => [
				'datetime' => [],
				'class'    => [],
				'id'       => [],
				'style'    => [],
			],
			'cite'    => [
				'title' => [],
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'acronym' => [
				'title' => [],
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'hr'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
            'div' => [
                'class' => [],
                'id'    => [],
                'style' => []
            ],
           
            'button' => [
                'class' => [],
                'id'    => [],
                'style' => [],
            ],

		];

		$accept_html_tags = array_merge($accept_html_tags, $desc_tags);
	}

	return $accept_html_tags;
}
/*
 * Escaping function for allow html tags
 * Title escaping function
 */

function htmega_kses_title( $string = '' ) {
	return wp_kses($string, htmega_get_html_allowed_tags( 'title' ));
}


/*
 * Escaping function for allow html tags
 * Description escaping function
 */
function htmega_kses_desc( $string = '' ) {
	return wp_kses($string, htmega_get_html_allowed_tags( 'desc' ));
}

/**
 * To show allowed html tags in description
 */
function htmega_get_allowed_tag_desc( $tag_type = 'title' ) {
	if (!in_array( $tag_type, ['title', 'desc'] )) {
		$tag_type = 'title';
	}

	$tags_string = '<' . implode('>,<', array_keys(htmega_get_html_allowed_tags( $tag_type ))) . '>';
	return sprintf(__('This input field supports the following HTML tags: %1$s', 'htmega-addons'), '<code>' . esc_html($tags_string) . '</code>');
}


/**
 * Escaped title html tags
 *
 * @param string $tag input string of title tag
 * @return string $default default tag will be return during no matches
 */
if (!function_exists('htmega_escape_tags')) {
    function htmega_escape_tags($tag, $default = 'span', $extra = [])
    {

        $supports = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];

        $supports = array_merge($supports, $extra);

        if (!in_array($tag, $supports, true)) {
            return $default;
        }

        return $tag;
    }
}



/**
 * [htmega_get_page_list]
 * @param  string $post_type
 * @return [array]
 */
if( !function_exists('htmega_get_page_list') ){
function htmega_get_page_list( $post_type = 'page' ){
    $options = array();
    $options['0'] = __('Select','htmega-addons');
    $perpage = -1;
    $all_post = array( 'posts_per_page' => $perpage, 'post_type'=> $post_type );
    $post_terms = get_posts( $all_post );
    if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ){
        foreach ( $post_terms as $term ) {
            $options[ $term->ID ] = $term->post_title;
        }
        return $options;
    }
}
}


/*
 * Instagram feed list
 * return array
 */
if( !function_exists('htmega_instagram_feed_list') ){
    function htmega_instagram_feed_list(){
        global $wpdb;
        $table_name     =  esc_sql( $wpdb->prefix . 'sbi_sources' );
        $feeds_sql      = "SELECT username FROM $table_name WHERE %d";
        $feeds_query    = $wpdb->prepare( $feeds_sql, 1 );
        $get_feeds      = $wpdb->get_results( $feeds_query, ARRAY_A );
        $all_feeds      = array();
        if( !empty( $get_feeds ) ){
            foreach($get_feeds as $value){
                $all_feeds[$value['username']] = $value['username'];
            }
        }else{
            $all_feeds['blank'] = "connect instagram account from settings"; 
        }
        return $all_feeds;
    }
}
/*
 * All Taxonomie Category Load
 * return Array
*/
if( !function_exists('all_object_taxonomie_show_catagory') ){
    function all_object_taxonomie_show_catagory($taxonomieName){

        $allTaxonomie =  get_object_taxonomies($taxonomieName);
        if(isset($allTaxonomie['0'])){
            if($allTaxonomie['0'] == "product_type"){
                $allTaxonomie['0'] = 'product_cat';
            }
            return htmega_get_taxonomies($allTaxonomie['0']);
        }
    }
}

/**
 * Get all Authors List
 *
 * @return array
 */
if( !function_exists('htmega_get_authors_list') ){
    function htmega_get_authors_list() {
        $args = [
            'capability'          => [ 'edit_posts' ],
            'has_published_posts' => true,
            'fields'              => [
                'ID',
                'display_name',
            ],
        ];

        // Version check 5.9.
        if ( version_compare( $GLOBALS['wp_version'], '5.9-alpha', '<' ) ) {
            $args['who'] = 'authors';
            unset( $args['capability'] );
        }

        $authors = get_users( $args );

        if ( ! empty( $authors ) ) {
            return wp_list_pluck( $authors, 'display_name', 'ID' );
        }

        return [];
    }
}

/**
 * Get HT Mega Elementor section dashboard icon
 *
 * @return image
 */
if (!function_exists('htmega_get_elementor_section_icon')) {
	function htmega_get_elementor_section_icon() {
		return "<img class='ht-badge-icon' src='".HTMEGA_ADDONS_PL_URL."admin/assets/images/menu-icon-collerd.png' alt='".esc_attr('HT','htmega-addons')."'>";
	}
}

/**
 *  Elementor pro feature notice function
 *
 * @param [type] $repeater/ $this
 * @param [type] $condition_key
 * @param [type] $array_value
 * @param [type] $type Controls_Manager::RAW_HTML
 * @return HTML
 */
function htmega_pro_notice( $repeater,$condition_key, $array_value, $type ){

    $repeater->add_control(
        'update_pro'.$condition_key,
        [
            'type' => $type,
            'raw' => sprintf(
                __('Upgrade to pro version to use this feature %s Pro Version %s', 'htmega-addons'),
                '<strong><a href="https://wphtmega.com/pricing/" target="_blank">',
                '</a></strong>'),
            'content_classes' => 'htmega-addons-notice',
            'condition' => [
                $condition_key => $array_value,
            ]
        ]
    );
}