<?php 
$dbhandler = new PM_DBhandler;
$pmrequests = new PM_request;
$pm_default_user_sorting = $dbhandler->get_global_option_value('pm_default_user_sorting','oldest_first');
$pm_show_search_area = $dbhandler->get_global_option_value('pm_show_search_bar','1');
$pm_show_advance_search_button = $dbhandler->get_global_option_value('pm_show_advance_search_button','1');
$pm_show_search_reset_button = $dbhandler->get_global_option_value('pm_show_search_reset_button','1');
$pm_show_search_sortby = $dbhandler->get_global_option_value('pm_show_search_sortby','1');
?>
<div class="pmagic">
    <div class="pm-users-search-page pm-dbfl">
        <div class="pm-user-search pm-dbfl">
            <form name="pm-search-form" id="pm-advance-search-form" method="post" class="pm-dbfl" >
                <?php if($pm_show_search_area==1):?>
                <div class="pm-search-box-wrap pm-dbfl">
                <div class="pm-search-box pm-dbfl pm-pad10">
                    <?php if (get_the_ID()): ?>
                        <input type="hidden" name="page_id" value="<?php echo get_the_ID(); ?>" />
                    <?php endif; ?>
                    <input id='pagenum' type="hidden" name="pagenum" value="1" />

                    <input type="hidden" name="status" value="0" />  
                    <input type="hidden" name="action" value='pm_advance_user_search' />
                    <input type="text" class="pm-search-input pm-advances-search-text pm-difl" name="pm_search" 
                           onkeyup="pm_advance_user_search('')"   value="<?php if (isset($_GET['pm_search'])) echo esc_attr( filter_input(INPUT_GET, 'pm_search', FILTER_SANITIZE_STRING) ); ?>"> 
                    <?php if($pm_show_search_sortby==1):?>   
                    <div class="pg-users-sorting-ls pg-members-sortby pm-difl ">
            <div class="pg-sortby-alpha pm-difl">
                <span class="pg-users-sorting-title pm-difl"><?php esc_html_e("Sort by","profilegrid-user-profiles-groups-and-communities");?></span>
            <span class="pg-sort-dropdown pm-border pm-difl">
                <select class="pg-custom-select" name="member_sort_by" id="member_sort_by" onchange="pm_advance_user_search('')">
                    <option value="oldest_first" <?php selected('oldest_first',$pm_default_user_sorting);?>><?php esc_html_e('Oldest', 'profilegrid-user-profiles-groups-and-communities'); ?></option>
                    <option value="latest_first"  <?php selected('latest_first',$pm_default_user_sorting);?>><?php esc_html_e('Newest', 'profilegrid-user-profiles-groups-and-communities'); ?></option>
                    <option value="first_name_asc"  <?php selected('first_name_asc',$pm_default_user_sorting);?>><?php esc_html_e('First Name Alphabetically A - Z', 'profilegrid-user-profiles-groups-and-communities'); ?></option>
                    <option value="first_name_desc"  <?php selected('first_name_desc',$pm_default_user_sorting);?>><?php esc_html_e('First Name Alphabetically Z - A', 'profilegrid-user-profiles-groups-and-communities'); ?></option>
                    <option value="last_name_asc"  <?php selected('last_name_asc',$pm_default_user_sorting);?>><?php esc_html_e('Last Name Alphabetically A - Z', 'profilegrid-user-profiles-groups-and-communities'); ?></option>
                    <option value="last_name_desc"  <?php selected('last_name_desc',$pm_default_user_sorting);?>><?php esc_html_e('Last Name Alphabetically Z- A', 'profilegrid-user-profiles-groups-and-communities'); ?></option>

                </select>
            </span>
                </div>
            
        </div>     
                    <?php endif;?>
                </div>

                </div>
                <div class="pm-adv-search-button">
                    <?php if($pm_show_search_reset_button==1):?>
                  <!-- <input type="submit" form='unknown' id="reset_btn" name="pm_reset" class="pm-search-submit pm-difl"  value="<?php esc_attr_e('Reset','profilegrid-user-profiles-groups-and-communities'); ?>" /> -->
                    <a href="#" id="reset_btn" class="pm-search-reset pm-difl"><?php esc_html_e('Reset','profilegrid-user-profiles-groups-and-communities'); ?></a>
                    <?php endif;?>
                    <?php if($pm_show_advance_search_button==1):?>
                    <div id="advance_search_option" class="pm-search-submit pm-difl" title="<?php esc_attr_e('More Filters','profilegrid-user-profiles-groups-and-communities'); ?>"><?php esc_html_e('More Filters','profilegrid-user-profiles-groups-and-communities'); ?><svg xmlns="http://www.w3.org/2000/svg" class="pg-search-filter-up" height="24" width="24"><path d="M12 15.375 6 9.375 7.4 7.975 12 12.575 16.6 7.975 18 9.375Z"/></svg><svg xmlns="http://www.w3.org/2000/svg" class="pg-search-filter-down" style="display:none" height="24" width="24"><path d="M7.4 15.375 6 13.975 12 7.975 18 13.975 16.6 15.375 12 10.775Z"/></svg></div>
                  <!-- <input type="submit" form='unknown' id="advance_search_option" name="advance_search_button" class="pm-search-submit pm-difl"  value="<?php esc_attr_e('Advance','profilegrid-user-profiles-groups-and-communities'); ?>" />-->
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <?php if (get_the_ID()): ?>
                        <input type="hidden" name="page_id" value="<?php echo get_the_ID(); ?>" />
                    <?php endif; ?>
                    <input id='pagenum' type="hidden" name="pagenum" value="1" />

                    <input type="hidden" name="status" value="0" />  
                    <input type="hidden" name="action" value='pm_advance_user_search' />
                <?php endif; ?>
                
                <div class="pm-dbfl pm-border-bt" id="advance_search_pane">
                <div class="pm-search-box pm-border">
                    <select name="gid" class="pm-search-input" id="advance_search_group" onchange="pm_change_search_field(this.value)" >
                        <option value=""><?php esc_html_e('Select A Group','profilegrid-user-profiles-groups-and-communities'); ?></option>
                        <?php
                        foreach ($groups as $group) {
                            ?>
                            <option value="<?php echo esc_attr($group->id); ?>" <?php if (!empty($gid)) selected($gid, $group->id); ?>>
                                <?php echo esc_html($group->group_name); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                  <?php do_action('profilegrid_pm_search_filters_html');?>
                    
                    <ul class="pm-filters" id="advance_seach_ul"><?php
                    $fields = $dbhandler->get_all_result('FIELDS');
                    foreach ($fields as $field) {
                        $gid = $field->associate_group;
                        $is_group_exist = $pmrequests->pg_check_if_group_exist($gid);
                        if(!$is_group_exist)
                        {
                            continue;
                        }
                        
                        
                        if ($field->field_options != "")
                            $field_options = maybe_unserialize($field->field_options);
                        $exclude = array('file', 'user_avatar', 'heading', 'paragraph', 'confirm_pass', 'user_pass');
                        if (!in_array($field->field_type, $exclude)) {

                            if (isset($field_options['display_on_search']) && ($field_options['display_on_search'] == 1)) {
                                
                                 if(isset($field_options['admin_only']) && $field_options['admin_only']=="1" && !is_super_admin() )
                                {
                                   continue;
                                }
                                ?>
                        <li class="pm-filter-item"><input type="checkbox" name="match_fields" onclick="pm_advance_user_search()"  value="<?php echo esc_attr($field->field_key); ?>" ><span class="pm-filter-value"><?php esc_html_e($field->field_name,'profilegrid-user-profiles-groups-and-communities'); ?></span></li>


                                <?php
                            }
                        }
                    }
                    ?>
                    </ul>
                </div>
                     
        <div id="pm_result_pane" >
            
        </div>
            </form>
        </div>

    </div>
</div>
