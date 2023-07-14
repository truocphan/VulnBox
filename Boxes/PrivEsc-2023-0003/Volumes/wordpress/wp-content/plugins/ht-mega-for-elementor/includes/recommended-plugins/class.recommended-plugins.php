<?php
namespace Hasthemes\HTMega_Builder;

/**
 * Recommended Plugins handlers class
 * @version 1.0.2
 */
class HTRP_Recommended_Plugins {

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [$plugins_allowedtags] allow tag
     * @var array
     */
    public $plugins_allowedtags = array(
        'a'       => array(
            'href'   => array(),
            'title'  => array(),
            'target' => array(),
        ),
        'abbr'    => array( 'title' => array() ),
        'acronym' => array( 'title' => array() ),
        'code'    => array(),
        'pre'     => array(),
        'em'      => array(),
        'strong'  => array(),
        'ul'      => array(),
        'ol'      => array(),
        'li'      => array(),
        'p'       => array(),
        'br'      => array(),
    );

    /**
     * Veriable Initialize
     */
    public $text_domain = '';
    public $parent_menu_slug = '';
    public $menu_label = '';
    public $menu_page_slug = '';
    public $menu_capability = '';
    public $priority = '';
    public $hook_suffix = '';
    public $assets_url = '';
    public $tab_list = [];
    protected $nonce;

    /**
     * [instance] Initializes a singleton instance
     * @return [Recommended_Plugins]
     */
    public static function instance( $args = [] ) {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self( $args );
        }
        return self::$_instance;
    }

    /**
     * [__construct] Class construct
     */
    function __construct( $args ) {

        // Initialize properties
        $this->text_domain       =  !empty( $args['text_domain'] ) ? $args['text_domain'] : 'htrp';
        $this->parent_menu_slug  =  !empty( $args['parent_menu_slug'] ) ? $args['parent_menu_slug'] : 'plugins.php';
        $this->menu_label        =  !empty( $args['menu_label'] ) ? $args['menu_label'] : esc_html__( 'Recommendations', 'htmega-addons' );
        $this->menu_capability   =  !empty( $args['menu_capability'] ) ? $args['menu_capability'] : 'manage_options';
        $this->menu_page_slug    =  !empty( $args['menu_page_slug'] ) ? $args['menu_page_slug'] : $this->text_domain . '_extensions';
        $this->priority          =  !empty( $args['priority'] ) ? $args['priority'] : 100;
        $this->hook_suffix       =  !empty( $args['hook_suffix'] ) ? $args['hook_suffix'] : '';
        $this->assets_url        =  !empty( $args['assets_url'] ) ? $args['assets_url'] : plugins_url( 'assets', __FILE__ );
        $this->tab_list          =  !empty( $args['tab_list'] ) ? $args['assets_url'] : [];
        $this->nonce             =  wp_create_nonce('htrp_nonce');

        
        add_action( 'admin_menu', [ $this, 'admin_menu' ], $this->priority );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );

        // Ajax Action
        add_action( 'wp_ajax_'.$this->text_domain.'_ajax_plugin_activation', [ $this, 'plugin_activation' ] );

    }

    /**
     * [admin_menu] Add Recommended Menu
     * @return [void]
     */
    public function admin_menu(){
        add_submenu_page(
            $this->parent_menu_slug, 
            $this->menu_label,
            $this->menu_label,
            $this->menu_capability, 
            $this->menu_page_slug, 
            [ $this, 'render_html' ] 
        );
    }

    /**
     * [enqueue_assets]
     * @param  [string] $hook_suffix Current page hook
     * @return [void] 
     */
    public function enqueue_assets( $hook_suffix ) {
        if( $this->hook_suffix ){
            if( $this->hook_suffix == $hook_suffix ){
                add_thickbox();
                wp_enqueue_script( 'htrp-plugin-install-manager', $this->assets_url . '/js/plugins_install_manager.js', array('jquery','wp-util', 'updates'), '1.0.0', true );
            }
        } else {
            add_thickbox();
            wp_enqueue_script( 'htrp-plugin-install-manager', $this->assets_url . '/js/plugins_install_manager.js', array('jquery','wp-util', 'updates'), '1.0.0', true );
        }

        $localize_vars['ajaxurl'] = admin_url('admin-ajax.php');
        $localize_vars['text_domain'] = sanitize_title_with_dashes( $this->text_domain );
        $localize_vars['buttontxt'] = array(
            'buynow'     => esc_html__( 'Buy Now', 'htmega-addons' ),
            'preview'    => esc_html__( 'Preview', 'htmega-addons' ),
            'installing' => esc_html__( 'Installing..', 'htmega-addons' ),
            'activating' => esc_html__( 'Activating..', 'htmega-addons' ),
            'active'     => esc_html__( 'Activated', 'htmega-addons' ),
        );
        $localize_vars['nonce'] = $this->nonce;
        wp_localize_script( 'htrp-plugin-install-manager', 'htrp_params', $localize_vars );

    }

    /**
     * [add_new_tab]
     * @param [void] set tab content
     */
    public function add_new_tab( $tab_list ){
        $this->tab_list[] = $tab_list;
    }

    /**
     * [render_html]
     * @return [void] Render HTML
     */
    public function render_html(){
        if ( ! function_exists('plugins_api') ){ include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' ); }

        $htplugins_plugin_list = $this->get_plugins();
        $palscode_plugin_list  = $this->get_plugins( 'moveaddons' );

        $plugin_list = array_merge( $htplugins_plugin_list, $palscode_plugin_list );

        $prepare_plugin = array();
        foreach ( $plugin_list as $plugin_key => $plugin ) {
            $prepare_plugin[$plugin['slug']] = $plugin;
        }

        ?>
            <div class="wrap">
                <h2><?php echo get_admin_page_title(); ?></h2>
                <style>
                    .htrp-admin-tab-pane{
                      display: none;
                    }
                    .htrp-admin-tab-pane.htrp-active{
                      display: block;
                    }
                    .htrp-extension-admin-tab-area .filter-links li>a:focus, .htrp-extension-admin-tab-area .filter-links li>a:hover {
                        color: inherit;
                        box-shadow: none;
                    }
                    .filter-links .htrp-active{
                        box-shadow: none;
                        border-bottom: 4px solid #646970;
                        color: #1d2327;
                    }
                    .downloaded-count{
                        display: block;
                        margin-top:5px;
                    }
                </style>

                <div class="htrp-extension-admin-tab-area wp-filter">
                    <ul class="htrp-admin-tabs filter-links">
                        <?php
                            foreach( $this->tab_list as $tab ){
                                $active_class = isset( $tab['active'] ) && $tab['active'] ? 'htrp-active' : '';
                                ?>
                                    <li>
                                        <a href="#<?php echo esc_attr( sanitize_title_with_dashes( $tab['title'] ) ) ?>" class="<?php echo esc_attr( $active_class ) ?>"><?php echo esc_html( $tab['title'] ) ?></a>
                                    </li>
                                <?php
                            }
                        ?>
                    </ul>
                </div>

                <?php
                    $plugins_type = '';
                    foreach( $this->tab_list as $tab ):

                        $active_class = isset( $tab['active'] ) && $tab['active'] ? 'htrp-active' : '';
                        $plugins      = $tab['plugins'];

                        echo '<div id="'.esc_attr( sanitize_title_with_dashes( $tab['title'] ) ).'" class="htrp-admin-tab-pane '.esc_attr( $active_class ).'">';
                            foreach( $plugins as $plugin ):

                                $data = array(
                                    'slug'      => isset( $plugin['slug'] ) ? $plugin['slug'] : '',
                                    'location'  => isset( $plugin['location'] ) ? $plugin['slug'].'/'.$plugin['location'] : '',
                                    'name'      => isset( $plugin['name'] ) ? $plugin['name'] : '',
                                );
                                $title = wp_kses( $plugin['name'], $this->plugins_allowedtags );

                                if( array_key_exists( $plugin['slug'], $prepare_plugin ) ){
                                    $plugins_type = 'free';
                                    $image_url    = $this->plugin_icon( $plugins_type, $prepare_plugin[$data['slug']]['icons'] );
                                    $description  = strip_tags( $prepare_plugin[$data['slug']]['description'] );
                                    $author_name  = wp_kses( $prepare_plugin[$data['slug']]['author'], $this->plugins_allowedtags );
                                    $details_link = self_admin_url('plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] .'&amp;TB_iframe=true&amp;width=772&amp;height=577');
                                    $target       = '_self';
                                    $modal_class  = 'class="thickbox open-plugin-details-modal"';

                                }else{
                                    $plugins_type = 'pro';
                                    $image_url     = $this->plugin_icon( $plugins_type, $plugin['slug'] );
                                    $description    = isset( $plugin['description'] ) ? $plugin['description'] : '';
                                    $author_name    = esc_html__( 'HasTheme', 'htmega-addons' );
                                    $author_link    = isset( $plugin['author_link'] ) ? $plugin['author_link'] : '';
                                    $details_link   = isset( $plugin['link'] ) ? $plugin['link'] : '';
                                    $button_text    = esc_html__('Buy Now', $this->text_domain );
                                    $button_classes = 'button button-primary';
                                    $target         = '_blank';
                                    $modal_class    = '';
                                }

                                if ( ! is_wp_error( $data ) ):

                                    // Installed but Inactive.
                                    if ( file_exists( WP_PLUGIN_DIR . '/' . $data['location'] ) && is_plugin_inactive( $data['location'] ) ) {

                                        $button_classes = 'button activate-now button-primary';
                                        $button_text    = esc_html__( 'Activate', 'htmega-addons' );

                                    // Not Installed.
                                    } elseif ( ! file_exists( WP_PLUGIN_DIR . '/' . $data['location'] ) ) {

                                        $button_classes = 'button install-now';
                                        $button_text    = esc_html__( 'Install Now', 'htmega-addons' );

                                    // Active.
                                    } else {
                                        $button_classes = 'button disabled';
                                        $button_text    = esc_html__( 'Activated', 'htmega-addons' );
                                    }

                                    ?>
                                    <div class="plugin-card htrp-plugin-<?php echo sanitize_html_class( $plugin['slug'] ); ?>">
                                        <div class="plugin-card-top">
                                            <div class="name column-name" style="margin-right: 0;">
                                                <h3>
                                                    <a href="<?php echo esc_url( $details_link ) ?>" target="<?php echo esc_attr( $target ) ?>" <?php echo $modal_class; ?>>
                                                        <?php echo esc_html( $title ) ?>
                                                        <img src="<?php echo esc_url( $image_url ) ?>" class="plugin-icon" alt="<?php echo esc_attr( $title ) ?>">
                                                    </a>
                                                </h3>
                                            </div>
                                            <div class="desc column-description" style="margin-right: 0;">
                                                <p><?php echo wp_trim_words( $description, 23, '....'); ?></p>
                                                <p class="authors">
                                                    <cite><?php echo esc_html__( 'By ', 'htmega-addons' ); ?>
                                                        <?php if( $plugins_type == 'free' ): ?>
                                                            <?php echo $author_name; ?>
                                                        <?php else: ?>
                                                            <a href="<?php echo esc_url( $author_link ); ?>"  target="_blank" ><?php echo $author_name; ?></a>
                                                        <?php endif; ?>
                                                    </cite>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="plugin-card-bottom">
                                            <div class="column-updated">
                                                <?php
                                                    if (! file_exists( WP_PLUGIN_DIR . '/' . $data['location'] ) && $plugins_type == 'pro' ) {
                                                        echo '<a class="button button-primary" href="'.esc_url( $details_link ).'" target="'.esc_attr( $target ).'">'.esc_html__( 'Buy Now', 'htmega-addons' ).'</a>';
                                                    }else{
                                                ?>
                                                    <button class="<?php echo $button_classes; ?>" data-pluginopt='<?php echo wp_json_encode( $data ); ?>'><?php echo $button_text; ?></button>
                                                    
                                                <?php } ?>
                                            </div>
                                            <div class="column-downloaded">
                                                <a href="<?php echo esc_url( $details_link ) ?>" target="<?php echo esc_attr( $target ) ?>" <?php echo $modal_class; ?>><?php echo esc_html__('More Details', 'htmega-addons') ?></a>
                                                <span class="downloaded-count">
                                                    <?php
                                                        if( $plugins_type == 'free' ){
                                                            /* translators: %s: Number of installations. */
                                                            printf( __( '%s Active Installations', 'htmega-addons' ), $this->active_install_count( $prepare_plugin[$data['slug']]['active_installs'] ) );
                                                        }
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                endif;
                            endforeach;
                        echo '</div>';

                    endforeach;
                ?>

            </div>
        <?php

    }

    /**
     * [get_plugins] Get plugin from wp.org API
     * @param  string $username wo.org username
     * @return [array] plugin list
     */
    public function get_plugins( $username = 'htplugins' ){
        $transient_var = 'htrp_htplugins_list_'.$username;
        $org_plugins_list = get_transient( $transient_var );

        if ( false === $org_plugins_list ) {
            $plugins_list_by_author = plugins_api( 'query_plugins', array( 'author' => $username, 'per_page' => 100 ) );
            set_transient( $transient_var, $plugins_list_by_author->plugins, 1 * WEEK_IN_SECONDS );
            $org_plugins_list = $plugins_list_by_author->plugins;
        }

        return $org_plugins_list;
    }

    /**
     * [plugin_icon] Generate plugin icon
     * @param  string $type plugin type
     * @param  [array|string] $icon
     * @return [URL] icon URL
     */
    public function plugin_icon( $type, $icon ){
        if( $type === 'free' ){
            if ( ! empty( $icon['svg'] ) ) {
                $plugin_icon_url = $icon['svg'];
            } elseif ( ! empty( $icon['2x'] ) ) {
                $plugin_icon_url = $icon['2x'];
            } elseif ( ! empty( $icon['1x'] ) ) {
                $plugin_icon_url = $icon['1x'];
            } else {
                $plugin_icon_url = $icon['default'];
            }
        }else{
            $plugin_icon_url = $this->assets_url .'/images/extensions/'.$icon.'.png';
        }

        return $plugin_icon_url;

    }

    /**
     * [active_install_count] Manage Active install count
     * @param  [int] $active_installs
     * @return [string]
     */
    public function active_install_count( $active_installs ){

        if ( $active_installs >= 1000000 ) {
            $active_installs_millions = floor( $active_installs / 1000000 );
            $active_installs_text     = sprintf(
                /* translators: %s: Number of millions. */
                _nx( '%s+ Million', '%s+ Million', $active_installs_millions, 'htmega-addons' ),
                number_format_i18n( $active_installs_millions )
            );
        } elseif ( 0 === $active_installs ) {
            $active_installs_text = _x( 'Less Than 10', 'htmega-addons' );
        } else {
            $active_installs_text = number_format_i18n( $active_installs ) . '+';
        }
        return $active_installs_text;

    }

    /**
     * [plugin_activation] Plugin activation ajax callable function
     * @return [JSON]
     */
    public function plugin_activation() {

        check_ajax_referer('htrp_nonce', 'nonce');

        if ( ! current_user_can( 'install_plugins' ) || ! isset( $_POST['location'] ) || ! $_POST['location'] ) {
            wp_send_json_error(
                array(
                    'success' => false,
                    'message' => esc_html__( 'Plugin Not Found', 'htmega-addons' ),
                )
            );
        }

        $plugin_location = ( isset( $_POST['location'] ) ) ? esc_attr( $_POST['location'] ) : '';
        $activate    = activate_plugin( $plugin_location, '', false, true );

        if ( is_wp_error( $activate ) ) {
            wp_send_json_error(
                array(
                    'success' => false,
                    'message' => $activate->get_error_message(),
                )
            );
        }

        wp_send_json_success(
            array(
                'success' => true,
                'message' => esc_html__( 'Plugin Successfully Activated', 'htmega-addons' ),
            )
        );

    }
}
