<?php
$dbhandler  = new PM_DBhandler();
$pmrequests = new PM_request();
$pmemails   = new PM_Emails();
$textdomain = $this->profile_magic;
$path       = plugin_dir_url( __FILE__ );

$pagenum          = filter_input( INPUT_GET, 'pagenum' );
$gid              = filter_input( INPUT_GET, 'gid' );
$field_identifier = 'FIELDS';
$group_identifier = 'GROUPS';
$current_user     = wp_get_current_user();
$pagenum          = isset( $pagenum ) ? absint( $pagenum ) : 1;
$limit            = 10; // number of rows in page
$offset           = ( $pagenum - 1 ) * $limit;
if ( filter_input( INPUT_GET, 'deactivate' ) ) {
	$retrieved_nonce = filter_input( INPUT_GET, '_wpnonce' );
	if ( ! wp_verify_nonce( $retrieved_nonce, 'pg_user_manager' ) ) {
		die( esc_html__( 'Failed security check', 'profilegrid-user-profiles-groups-and-communities' ) );
	}

	$selected = filter_input( INPUT_GET, 'selected', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	if ( isset( $selected ) ) :
		foreach ( $selected as $uid ) {
			update_user_meta( $uid, 'rm_user_status', '1' );
				do_action( 'pg_user_suspended', $uid );
			$ugids             = get_user_meta( $uid, 'pm_group', true );
				$ugid          = $pmrequests->pg_filter_users_group_ids( $ugids );
				$primary_group = $pmrequests->pg_get_primary_group_id( $ugid );
			$pmemails->pm_send_group_based_notification( $primary_group, $uid, 'on_user_deactivate' );
		}
	endif;
}

if ( filter_input( INPUT_GET, 'activate' ) ) {
	$retrieved_nonce = filter_input( INPUT_GET, '_wpnonce' );
	if ( ! wp_verify_nonce( $retrieved_nonce, 'pg_user_manager' ) ) {
		die( esc_html__( 'Failed security check', 'profilegrid-user-profiles-groups-and-communities' ) );
	}

	$selected = filter_input( INPUT_GET, 'selected', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	if ( isset( $selected ) ) :
		foreach ( $selected as $uid ) {
			update_user_meta( $uid, 'rm_user_status', '0' );
			$ugids             = get_user_meta( $uid, 'pm_group', true );
				$ugid          = $pmrequests->pg_filter_users_group_ids( $ugids );
				$primary_group = $pmrequests->pg_get_primary_group_id( $ugid );
			$pmemails->pm_send_group_based_notification( $primary_group, $uid, 'on_user_activate' );
		}
	endif;
}

if ( filter_input( INPUT_GET, 'delete' ) ) {
	$retrieved_nonce = filter_input( INPUT_GET, '_wpnonce' );
	if ( ! wp_verify_nonce( $retrieved_nonce, 'pg_user_manager' ) ) {
		die( esc_html__( 'Failed security check', 'profilegrid-user-profiles-groups-and-communities' ) );
	}

	$selected = filter_input( INPUT_GET, 'selected', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	foreach ( $selected as $uid ) {
		wp_delete_user( $uid );
	}
}

if ( filter_input( INPUT_GET, 'move' ) ) {
	$retrieved_nonce   = filter_input( INPUT_GET, '_wpnonce' );
        $pm_move_group = filter_input( INPUT_GET, 'pm_group' );
	if ( ! wp_verify_nonce( $retrieved_nonce, 'pg_user_manager' ) ) {
		die( esc_html__( 'Failed security check', 'profilegrid-user-profiles-groups-and-communities' ) );
	}

	$selected = filter_input( INPUT_GET, 'selected', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	foreach ( $selected as $uid ) {

            $pmrequests->profile_magic_join_group_fun( $uid, $pm_move_group, 'open' );
	}
}

do_action( 'profilegrid_dashboard_user_manager_action_area' );

if ( filter_input( INPUT_GET, 'reset' ) ) {
	$retrieved_nonce = filter_input( INPUT_GET, '_wpnonce' );
	if ( ! wp_verify_nonce( $retrieved_nonce, 'pg_user_manager' ) ) {
		die( esc_html__( 'Failed security check', 'profilegrid-user-profiles-groups-and-communities' ) );
	}

	wp_safe_redirect( esc_url_raw( 'admin.php?page=pm_user_manager' ) );
	exit;
}

$query_args = $pmrequests->pm_get_user_meta_query( filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING ) );
//print_r($meta_query_array);die;
$meta_query_array = array( 'relation' => 'OR', $query_args );
$date_query = $pmrequests->pm_get_user_date_query( filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING ) );

if ( isset( $_GET['search'] ) ) {
	$search = filter_input( INPUT_GET, 'search', FILTER_SANITIZE_STRING );
} else {
	$search = '';
}

$groups       = $dbhandler->get_all_result( 'GROUPS', array( 'id', 'group_name' ) );
$user_query   = $dbhandler->pm_get_all_users_ajax( $search, $meta_query_array, '', $offset, $limit, 'ASC', 'ID', array(), $date_query );
$total_users  = $user_query->get_total();
$users        = $user_query->get_results();
$num_of_pages = ceil( $total_users / $limit );
$pagination   = $dbhandler->pm_get_pagination( $num_of_pages, $pagenum );
?>

<div class="pmagic"> 
  
  <!-----Operationsbar Starts----->
  <form name="user_manager" id="user_manager" action="" method="get">
  <input type="hidden" name="page" value="pm_user_manager" />
  <input type="hidden" id="pagenum" name="pagenum" value="1" />
  <div class="operationsbar">
	<div class="pmtitle">
	  <?php esc_html_e( 'Members', 'profilegrid-user-profiles-groups-and-communities' ); ?>
	</div>
   
	<div class="nav">
	  <ul>
		<li><a href="user-new.php"><?php esc_html_e( 'New User', 'profilegrid-user-profiles-groups-and-communities' ); ?></a></li>
		<li class="pm_action_button"><input type="submit" name="deactivate" value="<?php esc_attr_e( 'Deactivate', 'profilegrid-user-profiles-groups-and-communities' ); ?>" /></li>
		<li class="pm_action_button"><input type="submit" name="activate" value="<?php esc_attr_e( 'Activate', 'profilegrid-user-profiles-groups-and-communities' ); ?>" /></li>
		<li class="pm_action_button"><input type="button" name="delete" value="<?php esc_attr_e( 'Delete', 'profilegrid-user-profiles-groups-and-communities' ); ?>" onclick="jQuery('.pm-delete-to-group').css('visibility', 'visible');" /></a></li>
		<li class="pm_action_button"><input type="button" name="move" value="<?php esc_attr_e( 'Assign Group', 'profilegrid-user-profiles-groups-and-communities' ); ?>" onclick="jQuery('.pm-move-to-group').css('visibility', 'visible');" /></a></li>
		<?php do_action( 'profilegrid_dashboard_members_top_menus' ); ?>
		<li><a href="https://profilegrid.co/documentation/users-profiles/" target="_blank"><?php esc_html_e( 'Help', 'profilegrid-user-profiles-groups-and-communities' ); ?></a></li>
		<li class="pm-form-toggle">
			<select name="gid" id="gid" onChange="jQuery('#pagenum').val(1);submit()">
			<option value=""><?php esc_html_e( 'Select A Group', 'profilegrid-user-profiles-groups-and-communities' ); ?></option>
			<?php
			foreach ( $groups as $group ) {
				?>
			<option value="<?php echo esc_attr( $group->id ); ?>" 
									  <?php
										if ( ! empty( $gid ) ) {
											selected( $gid, $group->id );}
										?>
			><?php echo esc_html( $group->group_name ); ?></option>
				<?php
			}
			?>
			<option value="0"><?php esc_html_e( 'None', 'profilegrid-user-profiles-groups-and-communities' ); ?></option>
		  </select>
		</li>
	  </ul>      
	</div>
  </div>
  <div class="pm-popup pm-move-to-group pm-popup-height-auto" >
	   <div class="pm-popup-header">
			<div class="pm-popup-title"><?php esc_html_e( 'Assign to group', 'profilegrid-user-profiles-groups-and-communities' ); ?>   </div>
				<img class="pm-popup-close" src="<?php echo esc_url( $path . '/images/close-pm.png' ); ?>">
	   </div>
		
	  <div class="pm-popup-field-name" style="padding:15px;" >
				<select name="pm_group" id="gid" >
					   <option value=""><?php esc_html_e( 'Select A Group', 'profilegrid-user-profiles-groups-and-communities' ); ?></option>
					   <?php
						foreach ( $groups as $group ) {
							?>
					   <option value="<?php echo esc_attr( $group->id ); ?>" 
												 <?php
													if ( ! empty( $gid ) ) {
														selected( $gid, $group->id );}
													?>
						><?php echo esc_html( $group->group_name ); ?></option>
							<?php
						}
						?>
					 </select>
			   <input type="submit" name="move" value="<?php esc_attr_e( 'Assign', 'profilegrid-user-profiles-groups-and-communities' ); ?>"style="padding-left:20px;"/>

			   <div class="pg-uim-notice-wrap pg-assign-user-group-waring"><div class="pg-uim-notice"> <?php esc_html_e( 'You are adding this user(s) to new group. All data associated with profile fields of old group will be merged and the user will have to edit and fill profile fields associated with the new group.', 'profilegrid-user-profiles-groups-and-communities' ); ?></div></div>
		
		   </div>
	</div>
  
  <div class="pm-popup pm-delete-to-group pm-popup-height-auto" >
	   <div class="pm-popup-header">
			<div class="pm-popup-title"><?php esc_html_e( 'Please Confirm', 'profilegrid-user-profiles-groups-and-communities' ); ?>   </div>
				<img class="pm-popup-close" src="<?php echo esc_url( $path . '/images/close-pm.png' ); ?>">
	   </div>
		
	  <div class="pm-popup-field-name" style="padding:15px;" >
			<p class="pm-warning"> <?php esc_html_e( 'You are about to remove selected user(s) from their respective groups and delete their user accounts. This action is irreversible. Please confirm to proceed.', 'profilegrid-user-profiles-groups-and-communities' ); ?></p>
	  </div>
	  <div class="modal-footer">
				 <input type="button" id="cancel-delete" class="pm-popup-close" value="<?php esc_attr_e( 'Cancel', 'profilegrid-user-profiles-groups-and-communities' ); ?> " />
				 <input type="submit" name="delete" value="<?php esc_attr_e( 'Confirm', 'profilegrid-user-profiles-groups-and-communities' ); ?>" />
			</div>
	</div>
  <!--------Operationsbar Ends-----> 
  
  <!-------Contentarea Starts-----> 
  
  <!----Table Wrapper---->
  
  <div class="pmagic-table pg-user_manager-table"> 
	
	<!----Sidebar---->
	
	<div class="sidebar">
	  <div class="sb-filter"><?php esc_html_e( 'Search', 'profilegrid-user-profiles-groups-and-communities' ); ?>
		<input type="text" class="sb-search" name="search" id="search" value="<?php
		if ( isset( $_GET['search'] ) ) {
			echo filter_input( INPUT_GET, 'search', FILTER_SANITIZE_STRING );}
		?>">
	  </div>
	  <?php if ( isset( $_GET['search'] ) && $_GET['search'] != '' ) : ?>
	  <div class="sb-search-keyword" id="search_keyword"><?php echo filter_input( INPUT_GET, 'search', FILTER_SANITIZE_STRING ); ?> <span onclick="show_hide_search_text()">x</span></div>
	  <?php endif; ?>
	  <div class="sb-filter"> <?php esc_html_e( 'Time', 'profilegrid-user-profiles-groups-and-communities' ); ?>
	  <div class="filter-row">
              <input type="radio" class="sel_pm_user_time" name="time" value="all" checked onclick="pm_show_hide(this,'','datehtml')" 
		  <?php
			if ( isset( $_GET['time'] ) && $_GET['time'] == 'all' ) {
				echo 'checked="checked"';}
			?>
			 >
		  <?php esc_html_e( 'All', 'profilegrid-user-profiles-groups-and-communities' ); ?> </div>
		<div class="filter-row">
		  <input type="radio" class="sel_pm_user_time" name="time" value="today" onclick="pm_show_hide(this,'','datehtml')" 
		  <?php
			if ( isset( $_GET['time'] ) && $_GET['time'] == 'today' ) {
				echo 'checked="checked"';}
			?>
			 >
		  <?php esc_html_e( 'Today', 'profilegrid-user-profiles-groups-and-communities' ); ?> </div>
		<div class="filter-row">
		  <input type="radio" class="sel_pm_user_time" name="time" value="yesterday" onclick="pm_show_hide(this,'','datehtml')" 
		  <?php
			if ( isset( $_GET['time'] ) && $_GET['time'] == 'yesterday' ) {
				echo 'checked="checked"';}
			?>
			>
		  <?php esc_html_e( 'Yesterday', 'profilegrid-user-profiles-groups-and-communities' ); ?> </div>
		<div class="filter-row">
		  <input type="radio" class="sel_pm_user_time" name="time" value="this_week" onclick="pm_show_hide(this,'','datehtml')" 
		  <?php
			if ( isset( $_GET['time'] ) && $_GET['time'] == 'this_week' ) {
				echo 'checked="checked"';}
			?>
			>
		  <?php esc_html_e( 'This Week', 'profilegrid-user-profiles-groups-and-communities' ); ?> </div>
		<div class="filter-row">
		  <input type="radio" class="sel_pm_user_time" name="time" value="last_week" onclick="pm_show_hide(this,'','datehtml')" 
		  <?php
			if ( isset( $_GET['time'] ) && $_GET['time'] == 'last_week' ) {
				echo 'checked="checked"';}
			?>
			>
		  <?php esc_html_e( 'Last Week', 'profilegrid-user-profiles-groups-and-communities' ); ?> </div>
		<div class="filter-row">
		  <input type="radio" class="sel_pm_user_time" name="time" value="this_month" onclick="pm_show_hide(this,'','datehtml')" 
		  <?php
			if ( isset( $_GET['time'] ) && $_GET['time'] == 'this_month' ) {
				echo 'checked="checked"';}
			?>
			>
		  <?php esc_html_e( 'This Month', 'profilegrid-user-profiles-groups-and-communities' ); ?> </div>
		<div class="filter-row">
		  <input type="radio" class="sel_pm_user_time" name="time" value="this_year" onclick="pm_show_hide(this,'','datehtml')" 
		  <?php
			if ( isset( $_GET['time'] ) && $_GET['time'] == 'this_year' ) {
				echo 'checked="checked"';}
			?>
			>
		  <?php esc_html_e( 'This Year', 'profilegrid-user-profiles-groups-and-communities' ); ?> </div>
		<div class="filter-row">
		  <input type="radio" name="time" value="specific" onclick="pm_show_hide(this,'datehtml')" 
		  <?php
			if ( isset( $_GET['time'] ) && $_GET['time'] == 'specific' ) {
				echo 'checked="checked"';}
			?>
			>
		  <?php esc_html_e( 'Specific Period', 'profilegrid-user-profiles-groups-and-communities' ); ?> </div>
		  <div id="datehtml" style=" 
		  <?php
			if ( isset( $_GET['time'] ) && $_GET['time'] == 'specific' ) {
				echo 'display:block';
			} else {
				echo 'display:none;';
			}
			?>
			">
		<div class="filter-row" id="">
	   <?php esc_html_e( 'Start Date', 'profilegrid-user-profiles-groups-and-communities' ); ?>
		  <input type="text" class="sb-search pm_calendar" name="start_date" value="<?php
			if ( isset( $_GET['start_date'] ) ) {
				echo filter_input( INPUT_GET, 'start_date', FILTER_SANITIZE_STRING );}
			?>">
		</div>
		<div class="filter-row">
		<?php esc_html_e( 'End Date', 'profilegrid-user-profiles-groups-and-communities' ); ?>
		  <input type="text" class="sb-search pm_calendar" name="end_date" value="<?php
			if ( isset( $_GET['end_date'] ) ) {
				echo filter_input( INPUT_GET, 'end_date', FILTER_SANITIZE_STRING );}
			?>">
		</div>
		</div>
		  </div>
		
		
	  <div class="sb-filter"> <?php esc_html_e( 'Status', 'profilegrid-user-profiles-groups-and-communities' ); ?>
	   <div class="filter-row">
		  <input type="radio" class="sel_pm_user_status" name="status" value="all" 
		  <?php
			if ( isset( $_GET['status'] ) && $_GET['status'] == 'all' ) {
				echo 'checked="checked"';}
			?>
			>
		  <?php esc_html_e( 'All', 'profilegrid-user-profiles-groups-and-communities' ); ?> </div>
		<div class="filter-row">
		  <input type="radio" class="sel_pm_user_status" name="status" value="0" 
		  <?php
			if ( isset( $_GET['status'] ) && $_GET['status'] == '0' ) {
				echo 'checked="checked"';}
			?>
			>
		  <?php esc_html_e( 'Active', 'profilegrid-user-profiles-groups-and-communities' ); ?> </div>
		<div class="filter-row">
		  <input type="radio" class="sel_pm_user_status" name="status" value="1" 
		  <?php
			if ( isset( $_GET['status'] ) && $_GET['status'] == '1' ) {
				echo 'checked="checked"';}
			?>
			>
		  <?php esc_html_e( 'Inactive', 'profilegrid-user-profiles-groups-and-communities' ); ?> </div>
	  </div>
	   
	   
	  <?php do_action( 'pg_social_filter' ); ?>
	   
	   
	  <div class="sb-filter"> <?php esc_html_e( 'Match Field', 'profilegrid-user-profiles-groups-and-communities' ); ?>
		<div class="filter-row">
		<?php
		$fields = $dbhandler->get_all_result( 'FIELDS' );
		echo '<select name="match_field" id="match_field" class="sb-search">';
		foreach ( $fields as $field ) {
					$exclude = array( 'file', 'user_avatar', 'heading', 'paragraph', 'confirm_pass', 'user_pass' );
			if ( ! in_array( $field->field_type, $exclude ) ) {
				echo '<option value="' . esc_attr( $field->field_key ) . '">' . esc_html( $field->field_name ) . '</option>';
			}
		}
		echo '</select>';
		?>
		</div>
		<div class="filter-row">
		  <input type="text" class="sb-search" name="field_value" value="">
		</div>
		<div class="filter-row">
		  <input type="submit" name="result" value="<?php esc_attr_e( 'Search', 'profilegrid-user-profiles-groups-and-communities' ); ?>">
		  <input type="submit" name="reset" value="<?php esc_attr_e( 'Reset', 'profilegrid-user-profiles-groups-and-communities' ); ?>">
		</div>
	  </div>
	</div>
	<table>
	  <tr>
		<th><input type="checkbox" id="selectall" class="css-checkbox " name="selectall"/></th>
		<th><?php esc_html_e( 'Image', 'profilegrid-user-profiles-groups-and-communities' ); ?></th>
		<th><?php esc_html_e( 'Display Name', 'profilegrid-user-profiles-groups-and-communities' ); ?></th>
		<th><?php esc_html_e( 'User Email', 'profilegrid-user-profiles-groups-and-communities' ); ?></th>
		<th><?php esc_html_e( 'Status', 'profilegrid-user-profiles-groups-and-communities' ); ?></th>
		<th><?php esc_html_e( 'Action', 'profilegrid-user-profiles-groups-and-communities' ); ?></th>
	  </tr>
	  <?php
		if ( ! empty( $users ) ) {
			foreach ( $users as $entry ) {
					$avatar     = get_avatar( $entry->user_email, 30, '', false, array( 'force_display' => true ) );
					$userstatus = get_user_meta( $entry->ID, 'rm_user_status', true );
				if ( $entry->ID == $current_user->ID ) {
									$class = 'pm_current_user';
									$attr  = 'disabled="disabled"';
				} else {
										$attr  = '';
										$class = 'pm_selectable';
				}
				?>
		  <tr class="<?php echo esc_attr( $class ); ?>">
			<td><input type="checkbox" name="selected[]" value="<?php echo esc_attr( $entry->ID ); ?>" <?php echo esc_attr( $attr ); ?> /></td>
			<td><div class="tableimg"> <a href="admin.php?page=pm_profile_view&id=<?php echo esc_attr( $entry->ID ); ?>"><?php echo wp_kses_post( $avatar ); ?></a> </div></td>
			<td><?php echo esc_html( $entry->display_name ); ?></td>
			<td><?php echo esc_html( $entry->user_email ); ?></td>
				<?php
				if ( $pmrequests->profile_magic_get_user_field_value( $entry->ID, 'rm_user_status' ) == '' || $pmrequests->profile_magic_get_user_field_value( $entry->ID, 'rm_user_status' ) == null ) {
					$userstatus = 0;
				} else {
					$userstatus = $pmrequests->profile_magic_get_user_field_value( $entry->ID, 'rm_user_status' );
				}
				?>
			<td><?php echo esc_html( ( $userstatus == 1 ) ? __( 'Inactive', 'profilegrid-user-profiles-groups-and-communities' ) : __( 'Active', 'profilegrid-user-profiles-groups-and-communities' ) ); ?></td>
			<td><a href="admin.php?page=pm_profile_view&id=<?php echo esc_attr( $entry->ID ); ?>"><?php esc_html_e( 'View', 'profilegrid-user-profiles-groups-and-communities' ); ?></a></td>
		  </tr>
				<?php
			}
		} else {
			echo '<tr><td></td><td>';
			 esc_html_e( 'No user matches your search.', 'profilegrid-user-profiles-groups-and-communities' );
			echo '<td><td></td><td></td><td></td><td></td></tr>';
		}
		?>
	</table>
  </div>
  <?php echo wp_kses_post( $pagination ); ?>
  <?php wp_nonce_field( 'pg_user_manager' ); ?>
  </form>
</div>
