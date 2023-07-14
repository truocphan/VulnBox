<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

if ( !class_exists( '\HTMegaOpt\Admin\Options_Field'  ) ) {
    require_once HTMEGAOPT_INCLUDES . '/classes/Admin/Options_field.php';
}

class HTMega_Widgets_Control{

    private static $instance = null;
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct(){
        // Register custom category
        add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );
        // Add Plugin actions
        // Init Widgets
        if ( htmega_is_elementor_version( '>=', '3.5.0' ) ) {
            add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
        }else{
            add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
        }
        // Add custom control
        add_action( 'elementor/controls/register', [ $this, 'initiliaze_custom_control' ] );

    }

    public function initiliaze_custom_control($controls_manager){
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'admin/include/custom-control/preset-select.php' ) ) {
            $controls_manager->register( new \HtMega\Preset\Preset_Select());
        }
    }
    // Add custom category.
    public function add_category( $elements_manager ) {
        $elements_manager->add_category(
            'htmega-addons',
            [
                'title' => __( 'HTMega Addons', 'htmega-addons' ),
                'icon' => 'fa fa-snowflake',
            ]
        );
    }
    public function init_widgets(){
            
        $widget_list = $this->get_widget_list();
        $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
        //Get registered settings
        $settings  = \HTMegaOpt\Admin\Options_Field::instance()->get_registered_settings();

        foreach($widget_list as $option_key => $option){

            $option_tab = $option['option-tab'];
            
            $settings_ids = array_column($settings[$option_tab], 'default','id');
            $default_status =  ( array_key_exists($option_key, $settings_ids) ) ? $settings_ids[$option_key] : 'on';

            $widget_path = $option['is_pro'] ? HTMEGA_ADDONS_PL_PATH_PRO : HTMEGA_ADDONS_PL_PATH;

            if(strpos($option['title'], ' ') !== false){
                $widget_file_name = strtolower(str_replace(' ', '_', $option['title']));
                $widget_class = $option['is_pro'] ? 'HTMegaPro\Elementor\Widget\HTMega_'. str_replace(' ', '_', $option['title']).'_Element' : "\Elementor\HTMega_Elementor_Widget_" . str_replace(' ', '_', $option['title']);
            }else{
                $widget_file_name = strtolower($option['title']);
                $widget_class =$option['is_pro'] ? 'HTMegaPro\Elementor\Widget\HTMega_'. $option['title'] .'_Element' : "\Elementor\HTMega_Elementor_Widget_" . $option['title'];
            }
           
            if(isset($option['third-party-resource'])){
                $widget_status = is_plugin_active($option['third-party-resource']) && ( htmega_get_option( $option_key, $option['option-tab'], $default_status ) === 'on' ) && file_exists( $widget_path.'includes/widgets/htmega_'.$widget_file_name.'.php' ) ? true : false ;
            }else{
                $widget_status = ( htmega_get_option( $option_key, $option['option-tab'], $default_status ) === 'on' ) && file_exists( $widget_path.'includes/widgets/htmega_'.$widget_file_name.'.php' ) ? true : false ;
            }

            if ( $widget_status ){
                if( is_plugin_active('htmega-pro/htmega_pro.php') && file_exists( HTMEGA_ADDONS_PL_PATH_PRO.'includes/widgets/htmega_'.$widget_file_name.'.php' ) ) {
                    require_once HTMEGA_ADDONS_PL_PATH_PRO.'includes/widgets/htmega_'.$widget_file_name.'.php';
                } else {
                    require_once $widget_path.'includes/widgets/htmega_'.$widget_file_name.'.php';
                }

                if ( htmega_is_elementor_version( '>=', '3.5.0' ) ){
                    $widgets_manager->register( new $widget_class() );
                }else{
                    $widgets_manager->register_widget_type( new $widget_class() );
                }
                
            }

        }
    }
    
    private function get_widget_list(){

        $widget_list =[
            'accordion'=> [
                'title' => 'Accordion',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'animatesectiontitle'=> [
                'title' => 'Animated Heading',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'addbanner' => [
                'title' => 'Add Banner',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'specialadsbanner' =>[
                'title' => 'Special day Banner',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'blockquote' =>[
                'title' => 'Blockquote',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'brandlogo' =>[
                'title' => 'Brand',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'businesshours' =>[
                'title' => 'Business Hours',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'button' =>[
                'title' => 'Button',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'calltoaction' =>[
                'title' => 'Call To Action',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'carousel' =>[
                'title' => 'Carousel',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'countdown' =>[
                'title' => 'Countdown',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'counter' =>[
                'title' => 'Counter',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'customevent' =>[
                'title' => 'Custom_Event',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'dualbutton' =>[
                'title' => 'Double Button',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'dropcaps' =>[
                'title' => 'Dropcaps',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'flipbox' =>[
                'title' => 'Flip Box',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'galleryjustify' =>[
                'title' => 'Gallery Justify',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'googlemap' =>[
                'title' => 'GoogleMap',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'imagecomparison' =>[
                'title' => 'Image Comparison',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'imagegrid' =>[
                'title' => 'Image Grid',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'imagemagnifier' =>[
                'title' => 'Image Magnifier',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'imagemarker' =>[
                'title' => 'ImageMarker',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'imagemasonry' =>[
                'title' => 'Image_Masonry',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'inlinemenu' =>[
                'title' => 'InlineMenu',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'instagram' =>[
                'title' => 'Instagram',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'lightbox' =>[
                'title' => 'Lightbox',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'modal' =>[
                'title' => 'Modal',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'newtsicker' =>[
                'title' => 'Newsticker',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'notify' =>[
                'title' => 'Notify',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'offcanvas' =>[
                'title' => 'Offcanvas',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'panelslider' =>[
                'title' => 'Panel Slider',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'popover' =>[
                'title' => 'Popover',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'postcarousel' =>[
                'title' => 'Post Carousel',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'postgrid' =>[
                'title' => 'PostGrid',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'postgridtab' =>[
                'title' => 'Post Grid Tab',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'postslider' =>[
                'title' => 'Post Slider',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'pricinglistview' =>[
                'title' => 'Pricing List View',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'pricingtable' =>[
                'title' => 'Pricing Table',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'progressbar' =>[
                'title' => 'Progress Bar',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'scrollimage' =>[
                'title' => 'Scroll Image',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'scrollnavigation' =>[
                'title' => 'Scroll Navigation',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'search' =>[
                'title' => 'Search',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'sectiontitle' =>[
                'title' => 'Section_Title',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'service' =>[
                'title' => 'Service',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'singlepost' =>[
                'title' => 'SinglePost',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'thumbgallery' =>[
                'title' => 'Slider Thumb Gallery',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'socialshere' =>[
                'title' => 'SocialShere',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'switcher' =>[
                'title' => 'Switcher',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'tabs' =>[
                'title' => 'Tabs',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'datatable' =>[
                'title' => 'Data Table',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'teammember' =>[
                'title' => 'TeamMember',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'testimonial' =>[
                'title' => 'Testimonial',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'testimonialgrid' =>[
                'title' => 'Testimonial Grid',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'toggle' =>[
                'title' => 'Toggle',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'tooltip' =>[
                'title' => 'Tooltip',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'twitterfeed' =>[
                'title' => 'Twitter_Feed',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'userloginform' =>[
                'title' => 'User Login Form',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'userregisterform' =>[
                'title' => 'User Register Form',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'verticletimeline' =>[
                'title' => 'Verticle Time Line',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'videoplayer' =>[
                'title' => 'VideoPlayer',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'workingprocess' =>[
                'title' => 'Working Process',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'errorcontent' =>[
                'title' => 'ErrorContent',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'template_selector' =>[
                'title' => 'Template Selector',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],
            'weather' =>[
                'title' => 'Weather',
                'option-tab'=>'htmega_element_tabs', 
                'is_pro'   => false,
            ],

            'bbpress' => [   
                'title' => 'Bbpress',
                'option-tab'=> 'htmega_thirdparty_element_tabs', 
                'third-party-resource' => 'bbpress/bbpress.php',
                'is_pro'=>false 
            ],

            'bookedcalender' => [   
                'title' => 'Booked Calendar',
                'option-tab'=> 'htmega_thirdparty_element_tabs', 
                'third-party-resource' => 'booked/booked.php',
                'is_pro'=>false 
            ],

            'buddypress' => [   
                'title' => 'Buddy Press',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'buddypress/bp-loader.php', 
                'is_pro'=>false 
            ],

            'calderaform' => [   
                'title' => 'Caldera Form',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'caldera-forms/caldera-core.php', 
                'is_pro'=>false 
            ],

            'contactform' => [   
                'title' => 'Contact Form Seven',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'contact-form-7/wp-contact-form-7.php', 
                'is_pro'=>false 
            ],

            'downloadmonitor' => [   
                'title' => 'Download Monitor',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'download-monitor/download-monitor.php', 
                'is_pro'=>false 
            ],

            'easydigitaldownload' => [   
                'title' => 'Easy Digital Download',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'easy-digital-downloads/easy-digital-downloads.php', 
                'is_pro'=>false 
            ],

            'gravityforms' => [   
                'title' => 'Gravity Forms',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'gravityforms/gravityforms.php', 
                'is_pro'=>false 
            ],

            'instragramfeed' => [   
                'title' => 'Instragram Feed',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'instagram-feed/instagram-feed.php', 
                'is_pro'=>false 
            ],

            'jobmanager' => [   
                'title' => 'Job Manager',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'wp-job-manager/wp-job-manager.php', 
                'is_pro'=>false 
            ],
            'layerslider' => [   
                'title' => 'Layer Slider',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'LayerSlider/layerslider.php', 
                'is_pro'=>false 
            ],

            'mailchimpwp' => [   
                'title' => 'Mailchimp Wp',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'mailchimp-for-wp/mailchimp-for-wp.php', 
                'is_pro'=>false 
            ],

            'ninjaform' => [   
                'title' => 'Ninja Form',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'ninja-forms/ninja-forms.php', 
                'is_pro'=>false 
            ],

           'quforms' => [   
                'title' => 'QUforms',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'quform/quform.php', 
                'is_pro'=>false 
            ],

            'wpforms' => [   
                'title' => 'WPforms',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'wpforms-lite/wpforms.php', 
                'is_pro'=>false 
            ],

            'revolution' => [   
                'title' => 'Revolution Slider',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'revslider/revslider.php', 
                'is_pro'=>false 
            ],

            'tablepress' => [   
                'title' => 'Tablepress',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'tablepress/tablepress.php', 
                'is_pro'=>false 
            ],

            'wcaddtocart' => [   
                'title' => 'WC Add to Cart',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'woocommerce/woocommerce.php', 
                'is_pro'=>false 
            ],

            'categories' => [   
                'title' => 'WC Categories',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'woocommerce/woocommerce.php', 
                'is_pro'=>false 
            ],

            'wcpages' => [   
                'title' => 'WC Element Pages',
                'option-tab'=> 'htmega_thirdparty_element_tabs',
                'third-party-resource' => 'woocommerce/woocommerce.php', 
                'is_pro'=>false 
            ],
    
        ];

        return apply_filters( 'htmega_widget_list', $widget_list );
    }

}
HTMega_Widgets_Control::instance();