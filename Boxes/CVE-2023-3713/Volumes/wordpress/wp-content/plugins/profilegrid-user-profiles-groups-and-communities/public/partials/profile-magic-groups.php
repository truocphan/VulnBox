<?php
$dbhandler = new PM_DBhandler;
$textdomain = $this->profile_magic;
$identifier = 'GROUPS';
$path =  plugin_dir_url(__FILE__);
$pagenum = filter_input(INPUT_GET, 'pagenum');
$pagenum = isset($pagenum) ? absint($pagenum) : 1;
$limit = 10; // number of rows in page
$offset = ( $pagenum - 1 ) * $limit;
if(isset($content) && !empty($content['ids']))
{
    $additional = ' id in('.$content['ids'].')';
}
else
{
    $additional ='';
}
$tg = $dbhandler->get_all_result($identifier,'*',1,'results',0,false,null,false,$additional);
if(!empty($tg))
{
    $total_groups = count($tg);
}
else
{
    $total_groups = 0;
}

$num_of_pages = ceil( $total_groups/$limit);
$pagination = $dbhandler->pm_get_pagination($num_of_pages,$pagenum);

$groups = $dbhandler->get_all_result($identifier,'*',1,'results', $offset, $limit,null,false,$additional);
if(!empty($groups))
{
        $themepath = $this->profile_magic_get_pm_theme('groups-tpl');
	include $themepath;
}
else
{
    echo '<div class="pg-alert-warning pg-alert-info">'. esc_html__( 'There are no user groups available right now. If you are admin, you can create one from dashboard and start signing up users.','profilegrid-user-profiles-groups-and-communities' ).'</div>'; 
}
?>