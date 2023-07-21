<?php

/* 
 * Customize the list of groups in your widget area
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Profilegrid_Groups_Menu' ) ) :
    
class Profilegrid_Groups_Menu extends WP_Widget {
    
    /*
     *  registers basic widget information.
     */
    public function __construct() {
        $widget_options = array(
           'classname' => 'pg_groups_menu',
          'description' => esc_html__('List ProfileGrid Groups','profilegrid-user-profiles-groups-and-communities'),
        );
        parent::__construct( 'pg_groups_menu', esc_html__('ProfileGrid Groups Menu','profilegrid-user-profiles-groups-and-communities'), $widget_options );
    }
    
    /*
     * used to add setting fields to the widget which will be displayed in the WordPress admin area.
     */
    public function form($instance)
    {
        $dbhandler = new PM_DBhandler;
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        
        $group_menu = get_option('pg_group_menu');
        $group_list = maybe_unserialize(get_option('pg_group_list'));
        $pg_group_icon = get_option('pg_group_icon');
        
        if($group_menu)
        {
            $changed_list = array();
            foreach($group_menu as $key=>$val)
            {               
                $single_group =  $dbhandler->get_all_result('GROUPS',array('id','group_name'), array('id'=>$val), $result_type = 'results', $offset = 0, $limit = false, $sort_by = null, $descending = false, $additional='', $output='ARRAY_A', $distinct = false);
                if(!empty($single_group)):
                $changed_list[$key] = array('id'=> $single_group[0]['id'],'group_name'=>$single_group[0]['group_name']);
                endif;
                
            }
            $group_menu = $changed_list;
        }
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:','profilegrid-user-profiles-groups-and-communities');?>
                <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr( $title ); ?>" />
            </label>
        </p>
        <p>
            <label><?php esc_html_e('Display Group Icon/Badge:','profilegrid-user-profiles-groups-and-communities');?></label>
            <input type="checkbox" <?php checked( $pg_group_icon, 'yes'); ?> id="pg_group_icon" name="pg_group_icon"/>
        </p>
        <ul class="pm_sortable_groups">
        <?php
        if (!empty($group_menu)):
            foreach ($group_menu as $group):
        ?>
            <li id="<?php echo esc_attr($group['id']); ?>">
                <div class="pm-custom-field-page-slab-widget pm-dbfl">
                    <div class="pg-widget-drag-handle"><span class="dashicons dashicons-menu"></span></div>
                    <div class="pm-group-buttons"><input type="checkbox" <?php checked(in_array($group['id'], $group_list) , 1 ); ?> name="group-lists" value="<?php echo esc_attr($group['id']); ?>"/></div>
                    <div class="pm-group-info"><?php echo esc_html($group['group_name']); ?></div>
                </div>
            </li>
        <?php
            endforeach;
        else:
        ?>
            <li>
                <div class="pm-slab"><?php esc_html_e("You haven't created any Profile Groups yet.",'profilegrid-user-profiles-groups-and-communities'); ?></div>
            </li>
        <?php
        endif;
        ?>
        </ul>
        <script type="text/javascript">
        jQuery(function() {
    jQuery('.pm_sortable_groups').sortable({
            axis: 'y',
            opacity: 0.7,
            handle: '.pg-widget-drag-handle',
            update: function (event, ui) {
                            var group_sortable = jQuery(this).sortable('toArray').toString();
                            var data = {
                            'action': 'pm_set_group_order',
                            'list_order': group_sortable
                    };
                    jQuery.post(pm_ajax_object.ajax_url, data, function(response) {});
            }
    });
});

jQuery(function() {
    var list = new Array();
    jQuery(".widget-liquid-right input:checkbox[id='pg_group_icon']").change(function(){
        if(this.checked)
        {
            var icon = 'yes';
        }
        else
        {
            var icon = 'no';
        }
        var data = {
                        'action': 'pm_set_group_items',
                        'icon': icon
                   };
        jQuery.post(pm_ajax_object.ajax_url, data, function(response) {});
    });
    jQuery(".widget-liquid-right ul.pm_sortable_groups li .pm-group-buttons input[type=checkbox]").each(function(){
        if (this.checked) {
                var id = jQuery(this).val();
                list.push(id);
        } 
        jQuery(this).change(function() {
            if (this.checked) {
                var id = jQuery(this).val();
                list.push(id);
                var items = list.toString();
                var data = {
                                  'action': 'pm_set_group_items',
                                  'list_items': items
                          };
                jQuery.post(pm_ajax_object.ajax_url, data, function(response) {});
            } 
            else{
                var id = jQuery(this).val();
                var i = list.indexOf(id);
                list.splice(i, 1);
                var items = list.toString();
               var data = {
                              'action': 'pm_set_group_items',
                              'list_items': items
                      };
                jQuery.post(pm_ajax_object.ajax_url, data, function(response) {});
            }
        }); 
    });
});
        </script>
        <?php 
    
    }
    
    /*
     * used to view to frontend 
     */
    
    public function widget($args,$instance)
    {
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;   
        if(isset($instance['title']))
        {
            $title = apply_filters( 'widget_title', $instance['title'] );
        }
        else
        {
            $title = '';
        }
        $group_menu = get_option('pg_group_menu');
        $group_list = maybe_unserialize(get_option('pg_group_list'));
        $pg_group_icon = get_option('pg_group_icon');
        $path = plugins_url( '/images/widget-default-group.png', __FILE__ );
        $pg_group_list = array();
        foreach($group_menu as $key=>$val)
        {               
            $single_group =  $dbhandler->get_all_result('GROUPS',array('id','group_name','group_icon'), array('id'=>$val), $result_type = 'results', $offset = 0, $limit = false, $sort_by = null, $descending = false, $additional='', $output='ARRAY_A', $distinct = false);
            if(!empty($single_group)):
            $pg_group_list[$key] = (object) array('id'=> $single_group[0]['id'],'group_name'=>$single_group[0]['group_name'],'group_icon'=>$single_group[0]['group_icon']);
            endif;
        }
        $groups = (object)$pg_group_list;
        $group_url  = $pmrequests->profile_magic_get_frontend_url('pm_group_page','');
        
        // before and after widget arguments are defined by themes
        echo wp_kses_post($args['before_widget']);
        if ( ! empty( $title ) )
        echo wp_kses_post($args['before_title'] . $title . $args['after_title']); 
        // This is where you run the code and display the output
        ?>
        <?php
        if (!empty($groups)):
            foreach ($groups as $group):
            if(in_array($group->id, $group_list)):
                $group_url  = $pmrequests->profile_magic_get_frontend_url('pm_group_page','',$group->id);
                //$group_url = add_query_arg( 'gid',$group->id, $group_url );
            
        ?>
            <a href="<?php echo esc_url($group_url); ?>" class="pm-dbfl">
                <div class="pg-group-menu-slab pm-dbfl" >
                    <?php if ($pg_group_icon == 'yes'): ?>
                        <div class="pg-group-menu-img pm-difl">
                           <?php echo wp_kses_post($pmrequests->profile_magic_get_group_icon($group,'',$path)); ?>
                        </div>
                    <?php endif; ?>
                    <div class="pg-group-menu-name pm-difl">
                         <?php echo wp_kses_post($group->group_name); ?>
                    </div>
                </div>
            </a>
        <?php
                endif;
            endforeach;
        else:
        ?>
            <div class="pm-slab"><?php esc_html_e("You haven't created any Profile Groups yet.",'profilegrid-user-profiles-groups-and-communities'); ?></div>
        <?php
        endif;
        
        echo wp_kses_post($args['after_widget']);
    }

    /*
     * Update the information in the WordPress database      * 
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
        $instance['icon'] = $new_instance['icon'];
        
        return $instance;
    }
}
endif;

