<?php
/**
 * Template library templates
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<script type="text/template" id="tmpl-htmenubutton">
    <span class="htmega-menu-trigger" data-item-id="{{ data.id }}" data-item-depth="{{ data.depth }}"><span class="dashicons dashicons-admin-generic"></span>{{{ data.label }}}</span>
</script>
<script type="text/template" id="tmpl-htmenupopup">
    <div class="htmega-menu-popup" id="htmega-popup-{{ data.id }}" data-id="{{ data.id }}" data-depth="{{ data.depth }}">
        <span class="htmega-menu-popup-close"></span>

        <div class="htmega-menu-popup-content">

            <span class="htmega-menu-popup-close-btn">&#10005;</span>

            <form class="htmega-menu-data" id="htmega-menu-form-{{ data.id }}">

                <# 
                    if( data.depth > 0 ){
                        active = 'active';
                    }else{
                        active = '';
                    }

                    icon_string = data.content['menu-item-ficon-'+data.id];
                    icon_prefix = icon_string.substring(0,3);
                    icon_values = icon_string.replace( icon_prefix, icon_prefix+" ");

                #>

                

                <!-- Tab Menu Area Start -->
                <ul class="htmega-menu-popup-tab-menu">
                    <# if( data.depth === 0 ){ #>
                    <li class="htmega-menu-popup-tab-list-item">
                        <a href="javascript:void();" data-target="htmega-menu-popup-tab-settings"><?php esc_html_e('Settings','htmega-addons'); ?></a>
                    </li>
                    <li class="htmega-menu-popup-tab-list-item">
                        <a class="active" href="javascript:void();" data-target="htmega-menu-popup-tab-content"><?php esc_html_e( 'Content', 'htmega-addons' ); ?></a>
                    </li>
                    <# } #>
                    <li class="htmega-menu-popup-tab-list-item">
                        <a href="javascript:void();" data-target="htmega-menu-popup-tab-icon"><?php esc_html_e('Icon','htmega-addons');?></a>
                    </li>
                    <li class="htmega-menu-popup-tab-list-item">
                        <a class="{{ active }}" href="javascript:void();" data-target="htmega-menu-popup-tab-badges"><?php esc_html_e('Badges','htmega-addons');?></a>
                    </li>
                </ul>
                <!-- Tab Menu Area End -->

                <!-- Tab Menu Content Area Start -->
                <div class="htmega-menu-popup-tab-content">
                    <# if( data.depth === 0 ){ #>

                    <!-- Settings Tab Field Area Start -->
                    <div class="htmega-menu-popup-tab-pane" data-id="htmega-menu-popup-tab-settings">
                        <ul>
                            <li>
                                <label for="menu-item-menuwidth-{{ data.id }}"><?php esc_html_e('Menu Width','htmega-addons'); ?></label>
                                <input type="text" id="menu-item-menuwidth-{{ data.id }}" name="menu-item-menuwidth-{{ data.id }}" class="widefat" value="{{ data.content['menu-item-menuwidth-'+data.id] }}">
                            </li>

                            <li>
                                <label for="menu-item-menuposition-{{ data.id }}"><?php esc_html_e('SubMenu Position','htmega-addons'); ?></label>
                                <input type="text" id="menu-item-menuposition-{{ data.id }}" name="menu-item-menuposition-{{ data.id }}" class="widefat" value="{{ data.content['menu-item-menuposition-'+data.id] }}">
                            </li>
                        </ul>
                    </div>
                    <!-- Settings Tab Field Area End -->   
                    <!-- Content Tab Field Area Start -->
                    <div class="htmega-menu-popup-tab-pane active" data-id="htmega-menu-popup-tab-content">
                        <ul>
                            <li>
                                <label for="menu-item-template-{{ data.id }}"><?php esc_html_e('Menu Template','htmega-addons');?></label>
                                <select id="menu-item-template-{{ data.id }}" class="widefat" name="menu-item-template-{{ data.id }}">
                                    <# 
                                        _.each( data.templatelist, function( tilte, key ) {

                                            menu_template = data.content['menu-item-template-'+data.id];

                                            if( key === menu_template ){
                                                #><option value="{{ key }}" selected>{{{ tilte }}}</option><#
                                            }else{
                                                #><option value="{{ key }}">{{{ tilte }}}</option><#
                                            }

                                        } );
                                    #>
                                </select>
                            </li>
                        </ul>
                    </div>
                    <!-- Content Tab Field Area End -->

                    <# } #>

                     <!-- Icon Tab Field Area Start -->
                    <div class="htmega-menu-popup-tab-pane" data-id="htmega-menu-popup-tab-icon">
                        <ul>
                            <li class="htmegamenu-pro">
                                <label for="menu-item-ficon-{{ data.id }}">
                                    <?php esc_html_e('Icon','htmega-addons'); ?>
                                    <span class="htmenu-pro-badge"><?php esc_html_e('( Pro )','htmega-addons'); ?></span>
                                </label>
                                <input type="text" id="menu-item-ficon-{{ data.id }}" name="menu-item-ficon-{{ data.id }}" class="widefat htmega-menu-icon" value="{{ icon_values }}">
                            </li>

                            <li class="htmegamenu-pro">
                                <label for="menu-item-ficoncolor-{{ data.id }}">
                                    <?php esc_html_e('Color','htmega-addons'); ?>
                                    <span class="htmenu-pro-badge"><?php esc_html_e('( Pro )','htmega-addons'); ?></span>
                            </label>
                                <input type="text" id="menu-item-ficoncolor-{{ data.id }}" name="menu-item-ficoncolor-{{ data.id }}" class="widefat htmega-color-picker-field" value="#{{ data.content['menu-item-ficoncolor-'+data.id] }}">
                            </li>
                        </ul>
                    </div>
                    <!-- Icon Tab Field Area End -->

                      <!-- Badges Tab Field Area Start -->
                    <div class="htmega-menu-popup-tab-pane {{ active }}" data-id="htmega-menu-popup-tab-badges">
                        <ul>
                            <li class="htmegamenu-pro">
                                <label for="menu-item-menutag-{{ data.id }}">
                                    <?php esc_html_e('Menu Badge','htmega-addons'); ?>
                                    <span class="htmenu-pro-badge"><?php esc_html_e('( Pro )','htmega-addons'); ?></span>
                                </label>
                                <input type="text" id="menu-item-menutag-{{ data.id }}" name="menu-item-menutag-{{ data.id }}" class="widefat" value="{{ data.content['menu-item-menutag-'+data.id] }}">
                            </li>
                            <li class="htmegamenu-pro">
                                <label for="menu-item-menutagcolor-{{ data.id }}">
                                    <?php esc_html_e('Color','htmega-addons'); ?>
                                    <span class="htmenu-pro-badge"><?php esc_html_e('(Pro)','htmega-addons'); ?></span>
                                </label>
                                <input type="text" id="menu-item-menutagcolor-{{ data.id }}" name="menu-item-menutagcolor-{{ data.id }}" class="widefat htmega-color-picker-field" value="#{{ data.content['menu-item-menutagcolor-'+data.id] }}">
                            </li>

                            <li class="htmegamenu-pro">
                                <label for="menu-item-badge-color-type-{{ data.id }}">
                                    <?php esc_html_e('Background','htmega-addons');?>
                                    <span class="htmenu-pro-badge"><?php esc_html_e('( Pro )','htmega-addons'); ?></span>
                                </label>
                                <select id="menu-item-badge-color-type-{{ data.id }}" class="widefat htmenu-bg-type" name="menu-item-badge-color-type-{{ data.id }}">
                                    <# 
                                        backgrount_type = {
                                            'default': 'Classic',
                                            'gradient': 'Gradient',
                                        };
                                        _.each( backgrount_type, function( tilte, key ) {

                                            bg_type = data.content['menu-item-badge-color-type-'+data.id];

                                            if( key === bg_type ){
                                                #><option value="{{ key }}" selected>{{{ tilte }}}</option><#
                                            }else{
                                                #><option value="{{ key }}">{{{ tilte }}}</option><#
                                            }

                                        } );
                                    #>
                                </select>
                            </li>

                            <# 
                                filed_style = '';
                                if( data.content['menu-item-badge-color-type-'+data.id] !== 'gradient' ){
                                    filed_style = 'border-width: 0;';
                                    filed_style_two = 'display: none;';
                                }else{
                                    filed_style_two = 'display: flex;';
                                }
                            #>

                            <li class="htmenu-default-field htmegamenu-pro" style="{{ filed_style }}">
                                <label for="menu-item-menutagbgcolor-{{ data.id }}">
                                    <?php esc_html_e('Background Color','htmega-addons'); ?>
                                    <span class="htmenu-pro-badge"><?php esc_html_e('( Pro )','htmega-addons'); ?></span>
                                </label>
                                <input type="text" id="menu-item-menutagbgcolor-{{ data.id }}" name="menu-item-menutagbgcolor-{{ data.id }}" class="widefat htmega-color-picker-field" value="#{{ data.content['menu-item-menutagbgcolor-'+data.id] }}">
                            </li>
                            <li class="htmenu-gradient-field" style="{{ filed_style_two }}">
                                <label for="menu-item-badge-bg-two-{{ data.id }}"><?php esc_html_e('Background Second Color','htmega-addons'); ?></label>
                                <input type="text" id="menu-item-badge-bg-two-{{ data.id }}" name="menu-item-badge-bg-two-{{ data.id }}" class="widefat htmega-color-picker-field" value="#{{ data.content['menu-item-badge-bg-two-'+data.id] }}">
                            </li>

                        </ul>
                    </div>
                    <!-- Badges tab Field Area End -->

                    <div class="htmega-menu-save-btn-area">
                        <button data-id="{{ data.id }}" class="htmega-menu-submit-btn button button-primary disabled" type="submit" disabled="disabled"><?php esc_html_e( 'All Data Saved', 'htmega-addons' ); ?></button>
                    </div>

                </div>
                <!-- Tab Menu Content Area End -->

            </form>

        </div>

    </div>
</script>