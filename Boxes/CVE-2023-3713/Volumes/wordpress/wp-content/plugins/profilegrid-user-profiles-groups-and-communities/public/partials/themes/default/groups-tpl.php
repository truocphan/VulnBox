<?php $dbhandler = new PM_DBhandler;
$pmrequests = new PM_request;
$pm_default_groups_sorting = $dbhandler->get_global_option_value('pm_default_groups_sorting','newest');
$limit = $dbhandler->get_global_option_value('pm_default_no_of_groups','10');
if(isset($content['sortby']))
{
    $pm_default_groups_sorting = $content['sortby'];
}

if(isset($content['view']))
{
    $pm_default_view = $content['view'];
}
else
{
    $pm_default_view = 'grid';
}

if(isset($content['sorting_dropdown']))
{
    $pm_show_sorting_dropdown = $content['sorting_dropdown'];
}
else
{
    $pm_show_sorting_dropdown = true;
}

if(isset($content['view_icon']))
{
    $pm_show_view_icon = $content['view_icon'];
}
else
{
    $pm_show_view_icon = true;
}

if(isset($content['search_box']))
{
    $pm_show_search_box = $content['search_box'];
}
else
{
    $pm_show_search_box = true;
}


?>
<div class="pmagic">
  <div class="pm-group-container pm-dbfl">
      <?php if($pm_show_sorting_dropdown || $pm_show_view_icon || $pm_show_search_box ): ?>
       <div class="pg-group-filters-head pm-dbfl pm-bg">
        <?php if($pm_show_sorting_dropdown):?>
        <div class="pg-group-filter-ls pg-members-sortby pm-difl ">
            <div class="pg-sortby-alpha pm-difl">
               
            <span class="pg-sort-dropdown pm-border pm-difl">
                <select class="pg-custom-select" name="group_sort_by" id="group_sort_by" onchange="pm_get_all_groups(1)">
                    <option value="newest" <?php selected('newest',$pm_default_groups_sorting);?>><?php esc_html_e('Newest', 'profilegrid-user-profiles-groups-and-communities'); ?></option>
                    <option value="oldest" <?php selected('oldest',$pm_default_groups_sorting);?>><?php esc_html_e('Oldest', 'profilegrid-user-profiles-groups-and-communities'); ?></option>
                    <option value="name_asc" <?php selected('name_asc',$pm_default_groups_sorting);?>><?php esc_html_e('Alphabetical (A-Z)', 'profilegrid-user-profiles-groups-and-communities'); ?></option>
                    <option value="name_desc" <?php selected('name_desc',$pm_default_groups_sorting);?>><?php esc_html_e('Alphabetical (Z-A)', 'profilegrid-user-profiles-groups-and-communities'); ?></option>
                </select>
            </span>
                </div>
            
        </div>
      <?php endif; ?>
            
        <div class="pg-group-filter-rs pm-difr">
            <?php if($pm_show_view_icon):?>
           <div class="pm-difl pg-members-sortby">
                <span class="pg-sort-view">
                    <span><input type="radio" id="pg_grid_view" name="pg_groups_view" class="" value="grid" checked="checked" onclick="pm_get_all_groups(1)"/>
                    <label for="pg_grid_view" class="pg-select-list-view"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><path d="M4 11h5V5H4v6zm0 7h5v-6H4v6zm6 0h5v-6h-5v6zm6 0h5v-6h-5v6zm-6-7h5V5h-5v6zm6-6v6h5V5h-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg></label>
                    </span>
                    <span> <input type="radio" id="pg_list_view" name="pg_groups_view" class="" value="list" onclick="pm_get_all_groups(1)" />
                    <label for="pg_list_view"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><path d="M4 14h4v-4H4v4zm0 5h4v-4H4v4zM4 9h4V5H4v4zm5 5h12v-4H9v4zm0 5h12v-4H9v4zM9 5v4h12V5H9z"/><path d="M0 0h24v24H0z" fill="none"/></svg></label>
                    </span>
                </span>

            </div>
            <?php endif;?>
            <?php if($pm_show_search_box):?>
            <div class="pg-group-search pm-difl"><input type="text" name="group_search" id="group_search" placeholder="<?php esc_attr_e('Search', 'profilegrid-user-profiles-groups-and-communities'); ?>" onkeyup="pm_get_all_groups(1)"></div>
            <?php endif;?>
        </div>
           
    </div>   
      <?php endif;?>
      
   <div class="pm-all-group-container pm-dbfl">
    <?php
    $pmrequests->pm_get_all_groups_data($pm_default_view,1,$limit,$pm_default_groups_sorting);
    ?>
</div>

  </div>
</div>
