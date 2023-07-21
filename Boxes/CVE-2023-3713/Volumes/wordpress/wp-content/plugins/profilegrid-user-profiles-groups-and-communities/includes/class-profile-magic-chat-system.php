<?php
class ProfileMagic_Chat {

	public function pm_messenger_notification_extra_data() {
		$dbhandler               = new PM_DBhandler();
		$pmrequests              = new PM_request();
		$current_user            = wp_get_current_user();
		$uid                     = $current_user->ID;
		$threads                 = $pmrequests->pm_get_user_all_threads( $uid );
		$extra_notification_data = array();
		$thread_count            = 0;
		$i                       = 0;
		if ( ! empty( $threads ) ) {
			foreach ( $threads as $thread ) {
				$unread_message_count = $pmrequests->get_unread_msg_count( $thread->t_id );
				if ( ! empty( $unread_message_count ) ) {
					$thread_count = $thread_count + 1;
				}

				if ( $i == 0 ) {
					if ( $thread->r_id == $uid ) {
						$rid = $thread->s_id;
					} else {
						$rid = $thread->r_id;
					}
					$extra_notification_data['last_thread']       = $thread->t_id;
					$extra_notification_data['rid']               = $rid;
					$extra_notification_data['last_thread_count'] = $unread_message_count;
				}
				$i++;

			}
		}
		$extra_notification_data['unread_threads'] = $thread_count;
		return wp_json_encode( $extra_notification_data );

	}

	public function pm_messenger_search_threads( $search = '' ) {
		$dbhandler   = new PM_DBhandler();
		$pmrequests  = new PM_request();
		$identifier  = 'MSG_CONVERSATION';
		$identifier2 = 'MSG_THREADS';
		$where       = 1;
		$tid         = array();
		$additional  = "content LIKE '%" . $search . "%'";
		$uid         = get_current_user_id();
		$messages    = $dbhandler->get_all_result( $identifier, $column = '*', $where, 'results', 0, false, 'timestamp', true, $additional );

		foreach ( $messages as $message ) {
			$tid[] = $message->t_id;
		}
		$thred_id_array = array_unique( $tid );

		if ( ! empty( $thred_id_array ) ) {
			$thread_ids          = implode( ',', $thred_id_array );
			$where2              = array( 'status' => 2 );
			$additional          = "AND t_id in($thread_ids) AND (s_id = $uid OR r_id = $uid)";
			$threads             = $dbhandler->get_all_result( $identifier2, '*', $where2, 'results', 0, false, 'timestamp', true, $additional );
			$unread_thread_count = 0;
			$return              = '';
			$active_tid          = $tid;
			$count               = 1;
			if ( ! empty( $threads ) ) {
				foreach ( $threads as $thread ) {
					if ( ! empty( $thread->title ) && $thread->title == $uid ) {
						continue;
					}
					$active_class = '';
					$active       = 'false';
					if ( $uid == $thread->s_id ) {
						$other_uid = $thread->r_id;
					} else {
						$other_uid = $thread->s_id;
					}

					if ( get_user_by( 'ID', $other_uid ) == false ) {
						continue;
					}

					$tid     = $thread->t_id;
					$lastmsg = $pmrequests->get_message_of_thread( $tid, 1, 0, true );
					if ( ! empty( $lastmsg ) ) {
						$last_message = nl2br( $lastmsg[0]->content );
						$last_msgid   = $lastmsg[0]->m_id;
						$last_message = mb_strimwidth( $last_message, 0, 30, '...' );
					} else {
						$last_message = '';
						$last_msgid   = '';
					}

					$profile_url                    = $pmrequests->pm_get_user_profile_url( $other_uid );
					$other_user_info['profile_url'] = $profile_url;
					$other_user_info['avatar']      = get_avatar(
						$other_uid,
						50,
						'',
						false,
						array(
							'class'         => 'pm-user-profile',
							'force_display' => true,
						)
					);
					$other_user_info['name']        = $pmrequests->pm_get_display_name( $other_uid, true );
					$thread_timestamp               = human_time_diff( strtotime( $thread->timestamp ), time() );

					$thread_timestamp     = $thread_timestamp . __( ' ago', 'profilegrid-user-profiles-groups-and-communities' );
					$thread_status        = $thread->status;
					$unread_visual        = '';
					$unread_message_count = $pmrequests->get_unread_msg_count( $tid );
					$read_unread_button   = '';
					if ( ! empty( $unread_message_count ) ) {
						$unread_visual      = '<div class="pg-unread-count">' . $unread_message_count . '</div>';
						$read_unread_button = '<div class="pg-msg-conversation-unread" onclick="event.stopPropagation();pg_msg_read_messages(this,' . $tid . ')"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M18.83 7h-2.6L10.5 4 4 7.4V17c-1.1 0-2-.9-2-2V7.17c0-.53.32-1.09.8-1.34L10.5 2l7.54 3.83c.43.23.73.7.79 1.17zM20 8H7c-1.1 0-2 .9-2 2v9c0 1.1.9 2 2 2h13c1.1 0 2-.9 2-2v-9c0-1.1-.9-2-2-2zm0 3.67L13.5 15 7 11.67V10l6.5 3.33L20 10v1.67z"/></svg></div>';
					} else {
						$read_unread_button = '<div class="pg-msg-conversation-read" onclick="event.stopPropagation();pg_msg_unread_messages(this,' . $tid . ')"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg></div>';
					}

					if ( $active_tid != '' ) {
						if ( $tid == $active_tid ) {
							$active       = 'true';
							$active_class = 'active';
						} else {
							$active_class = '';
							$active       = 'false';
						}
					} else {
						if ( $count == 1 ) {
							$active_class = 'active';
							$active       = 'true';
							$count++;
						} else {
							$active_class = '';
							$active       = 'false';
						}
					}
					$login_status = ( $pmrequests->pm_get_user_online_status( $other_uid ) == 1 ? 'pm-online' : 'pm-offline' );

					 $return .= ' <div id="pg-msg-thread-' . $tid . '" data-thread="' . $tid . '" class="pg-msg-conversation-list ' . $active_class . '" onclick="pg_show_msg_panel(' . $uid . ',' . $other_uid . ',' . $tid . ')">' . $other_user_info['avatar'] . '<div class="' . $login_status . '"></div><div class="pg-msg-conversation-info">
                        <div class="pg-list-user-img-wrap">
                          <div class="pg-msg-thread-user">' . $other_user_info['name'] . '</div>
                          <div class="pg-msg-thread-time">' . $thread_timestamp . '</div>
                          
                          <div class="pg-msg-conversation-action">' .
							$read_unread_button . '
                            <div class="pg-msg-conversation-delete" onclick="event.stopPropagation();pg_msg_delete_thread_confirmbox(' . $tid . ',' . $uid . ',' . $last_msgid . ')"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"/></svg></div>

                          </div>
                          ' . $unread_visual . '
                        </div>
                      <span class="pg-thread-msg">' . stripslashes( wp_strip_all_tags( $last_message ) ) . '</span>
                    </div>
                  </div>';

				}
			} else {
				$return = '<div class="pg-no-thread">' . __( 'You have no conversations yet.', 'profilegrid-user-profiles-groups-and-communities' ) . '</div>';
			}
			if ( ! isset( $return ) || trim( $return ) == '' ) {
				 $return = '<div class="pg-no-thread">' . __( 'You have no conversations yet.', 'profilegrid-user-profiles-groups-and-communities' ) . '</div>';
			}
		} else {
			$return = '<div class="pg-no-thread">' . __( 'No any message found with this search.', 'profilegrid-user-profiles-groups-and-communities' ) . '</div>';
		}

		return $return;

	}

	public function pm_messenger_show_threads( $tid = '' ) {
		$pmrequests          = new PM_request();
		$current_user        = wp_get_current_user();
		$uid                 = $current_user->ID;
		$threads             = $pmrequests->pm_get_user_all_threads( $uid );
		$unread_thread_count = 0;
		$return              = '';
		$active_tid          = $tid;
		$count               = 1;
		if ( ! empty( $threads ) ) {
			foreach ( $threads as $thread ) {
				if ( ! empty( $thread->title ) && $thread->title == $uid ) {
					continue;
				}
				$active_class = '';
				$active       = 'false';
				if ( $uid == $thread->s_id ) {
					$other_uid = $thread->r_id;
				} else {
					$other_uid = $thread->s_id;
				}

				if ( get_user_by( 'ID', $other_uid ) == false ) {
					continue;
				}

				$tid     = $thread->t_id;
				$lastmsg = $pmrequests->get_message_of_thread( $tid, 1, 0, true );
				if ( ! empty( $lastmsg ) ) {
					$last_message = nl2br( $lastmsg[0]->content );
					$last_msgid   = $lastmsg[0]->m_id;
					$last_message = mb_strimwidth( $last_message, 0, 30, '...' );
				} else {
					$last_message = '';
					$last_msgid   = '';
				}

				$profile_url                    = $pmrequests->pm_get_user_profile_url( $other_uid );
				$other_user_info['profile_url'] = $profile_url;
				$other_user_info['avatar']      = get_avatar(
					$other_uid,
					50,
					'',
					false,
					array(
						'class'         => 'pm-user-profile',
						'force_display' => true,
					)
				);
				$other_user_info['name']        = $pmrequests->pm_get_display_name( $other_uid, true );
				$thread_timestamp               = human_time_diff( strtotime( $thread->timestamp ), time() );

				$thread_timestamp     = $thread_timestamp . __( ' ago', 'profilegrid-user-profiles-groups-and-communities' );
				$thread_status        = $thread->status;
				$unread_visual        = '';
				$unread_message_count = $pmrequests->get_unread_msg_count( $tid );
				$read_unread_button   = '';
				if ( ! empty( $unread_message_count ) ) {
					$unread_visual      = '<div class="pg-unread-count">' . $unread_message_count . '</div>';
					$read_unread_button = '<div class="pg-msg-conversation-unread" onclick="event.stopPropagation();pg_msg_read_messages(this,' . $tid . ')"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M18.83 7h-2.6L10.5 4 4 7.4V17c-1.1 0-2-.9-2-2V7.17c0-.53.32-1.09.8-1.34L10.5 2l7.54 3.83c.43.23.73.7.79 1.17zM20 8H7c-1.1 0-2 .9-2 2v9c0 1.1.9 2 2 2h13c1.1 0 2-.9 2-2v-9c0-1.1-.9-2-2-2zm0 3.67L13.5 15 7 11.67V10l6.5 3.33L20 10v1.67z"/></svg></div>';
				} else {
					$read_unread_button = '<div class="pg-msg-conversation-read" onclick="event.stopPropagation();pg_msg_unread_messages(this,' . $tid . ')"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg></div>';
				}

				if ( $active_tid != '' ) {
					if ( $tid == $active_tid ) {
						$active       = 'true';
						$active_class = 'active';
					} else {
						$active_class = '';
						$active       = 'false';
					}
				} else {
					if ( $count == 1 ) {
						$active_class = 'active';
						$active       = 'true';
						$count++;
					} else {
						$active_class = '';
						$active       = 'false';
					}
				}
				$login_status = ( $pmrequests->pm_get_user_online_status( $other_uid ) == 1 ? 'pg-msg-online' : 'pg-msg-offline' );

				 $return .= ' <div id="pg-msg-thread-' . $tid . '" data-thread="' . $tid . '" class="pg-msg-conversation-list ' . $active_class . '" onclick="pg_show_msg_panel(' . $uid . ',' . $other_uid . ',' . $tid . ')">' . $other_user_info['avatar'] . '<div class="pg-user-status ' . $login_status . '"></div><div class="pg-msg-conversation-info">
                    <div class="pg-list-user-img-wrap">
                      <div class="pg-msg-thread-user">' . $other_user_info['name'] . '</div>
                      <div class="pg-msg-thread-time">' . $thread_timestamp . '</div>                    
                      <div class="pg-msg-conversation-action">' .
						$read_unread_button . '
                        <div class="pg-msg-conversation-delete" onclick="event.stopPropagation();pg_msg_delete_thread_confirmbox(' . $tid . ',' . $uid . ',' . $last_msgid . ')"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg></div>
  
                      </div>
                    
                    </div>
                    <div class="pg-thread-notification">
                  <div class="pg-thread-msg">' . stripslashes( wp_strip_all_tags( $last_message ) ) . '</div>
                    ' . $unread_visual . '
                    </div>    
                </div>
              </div>';

			}
		} else {
			$return = '<div class="pg-no-thread">' . __( 'You have no conversations yet.', 'profilegrid-user-profiles-groups-and-communities' ) . '</div>';
		}
		if ( ! isset( $return ) || trim( $return ) == '' ) {
			 $return = '<div class="pg-no-thread">' . __( 'You have no conversations yet.', 'profilegrid-user-profiles-groups-and-communities' ) . '</div>';
		}

		return $return;

	}

	public function pm_messenger_show_messages_old( $tid, $loadnum, $timezone = 0 ) {
		$pmrequests   = new PM_request();
		$current_user = wp_get_current_user();
		$cur_uid      = $current_user->ID;
		// $t_status = isset($t_status) ? $t_status : 0;
		$loadnum         = isset( $loadnum ) ? absint( $loadnum ) : 1;
		$limit           = 10; // number of rows in page
		$time_conversion = isset( $timezone ) ? $timezone * 60 : 0;
		$offset          = ( $loadnum - 1 ) * $limit;
		$descending      = true;
		$messages        = $pmrequests->get_message_of_thread( $tid, $limit, $offset, $descending );

		$return = '';
		if ( ! empty( $messages ) ) {
			$messages = array_reverse( $messages );
			if ( count( $messages ) == $limit ) {
				$return .= "<button id=\"load_more_message\" pagenum=\"$loadnum\" >" . __( 'Load More', 'profilegrid-user-profiles-groups-and-communities' ) . '</button>';
			}
			foreach ( $messages as $message ) {

					$uid         = $message->s_id;
					$read_status = '';
				if ( $uid == $cur_uid ) {
					$align = 'pm_msg_rf';
					if ( $message->status == 1 ) {
						$read_status = 'read';
					} else {
						$read_status = 'unread';
					}
				} else {
					$align = 'pm_msg_lf';
				}
					$last_message  = nl2br( $message->content );
					$profile_url   = $pmrequests->pm_get_user_profile_url( $uid );
					$date          = mysql2date( 'd M,g:i A', gmdate( 'Y-m-d H:i:s', ( strtotime( $message->timestamp ) ) - $time_conversion ) );
					$msg_timestamp = human_time_diff( strtotime( $message->timestamp ), current_time( 'timestamp' ) );
				if ( $msg_timestamp == '1 min' ) {
					$msg_timestamp = __( 'just now', 'profilegrid-user-profiles-groups-and-communities' );
				}
					$other_user_info['avatar'] = get_avatar(
						$uid,
						50,
						'',
						false,
						array(
							'class'         => 'pm-user-profile',
							'force_display' => true,
						)
					);
					$return                   .= "<div id=\"$message->m_id\" class=\"$align\" > " .
							'<a href="' . $profile_url . '">' . $other_user_info['avatar'] . '</a>'
							. '<div class="pm-user-description-row pm-dbfl pm-border">' . stripslashes( $last_message ) . "</div><div class=\"pm-message-thread-time\">$date</div></div>";

			}
		}
		return $return;

	}

	public function pm_messenger_show_messages( $tid, $loadnum, $timezone = 0, $search = '' ) {
		$pmrequests   = new PM_request();
		$current_user = wp_get_current_user();
		$cur_uid      = $current_user->ID;
		$path         = plugins_url( '../public/partials/images/typing_image.gif', __FILE__ );
		$loadnum      = isset( $loadnum ) ? absint( $loadnum ) : 1;
		if ( $search == '' ) {
			$limit = 10; // number of rows in page
		} else {
			$limit = false;
		}

		$time_conversion = isset( $timezone ) ? $timezone * 60 : 0;
		$offset          = ( $loadnum - 1 ) * $limit;
		$descending      = true;
		$messages        = $pmrequests->get_message_of_thread( $tid, $limit, $offset, $descending, $search );

		$return = '';
		if ( ! empty( $messages ) ) {
			$messages = array_reverse( $messages );
			if ( count( $messages ) == $limit ) {
				$return .= "<button id=\"load_more_message\" pagenum=\"$loadnum\" >" . __( 'Load More', 'profilegrid-user-profiles-groups-and-communities' ) . '</button>';
			}
			foreach ( $messages as $message ) {

					$uid         = $message->s_id;
					$read_status = '';
				if ( $uid == $cur_uid ) {
					$align = 'pg-user-self-msg';
					if ( $message->status == 1 ) {
						$read_status = 'read';
					} else {
						$read_status = 'unread';
					}
				} else {
					$align = '';
				}
					$last_message  = nl2br( $message->content );
					$date          = mysql2date( 'd M,g:i A', gmdate( 'Y-m-d H:i:s', ( strtotime( $message->timestamp ) ) - $time_conversion ) );
					$msg_timestamp = human_time_diff( strtotime( $message->timestamp ), current_time( 'timestamp' ) );
				if ( $msg_timestamp == '1 min' ) {
					$msg_timestamp = __( 'just now', 'profilegrid-user-profiles-groups-and-communities' );
				}

					$return .= '<div id="pg-msg_id_' . $message->m_id . '" class="pg-message-list ' . $align . '">';
				if ( $uid == $cur_uid ) {
					$return .= '<div class="pg-message-action" ><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                       <div class="pg-message-action-wrap"> <ul><li onclick="pg_msg_edit(' . $message->m_id . ')">Edit</li><li  onclick="pg_msg_delete(' . $message->m_id . ')">Delete</li></ul></div>
                       </div>';
				}
				$return .= get_avatar(
					$uid,
					50,
					'',
					false,
					array(
						'class'         => 'pm-user-profile',
						'force_display' => true,
					)
				) . '
                <div class="pg-message-box pm-border">
                  ' . stripslashes( $last_message ) . '
                </div>
                <div class="pg-msg-thread-time">' . $date . '</div>
              </div>';

			}
		}
		$pmrequests->update_message_status_to_read( $tid );
		$return .= '<div id="typing_on" class="pm-user-description-row pm-dbfl">
    <div class="pm-typing-inner" style="display:none;">
                    <img height="9px" width="40px" src="' . $path . '"/>
                </div>
            </div>';
		return $return;

	}

	public function pg_show_message_tab_html_old( $receiver_uid ) {
		 $pmrequests  = new PM_request();
		$current_user = wp_get_current_user();
		$return       = $this->pm_messenger_show_threads( '' );
		?>
		<div class="pm-group-view">
				<div class="pm-section pm-dbfl" > 
					<svg onclick="show_pg_section_left_panel()" class="pg-left-panel-icon" fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
			<path d="M15.41 16.09l-4.58-4.59 4.58-4.59L14 5.5l-6 6 6 6z"/>
			<path d="M0-.5h24v24H0z" fill="none"/>
		</svg>
					<div class="pm-section-left-panel pm-section-nav-vertical pm-difl " id="thread_pane">

						<div id="search">
										<input autocomplete="off" type="text" id="receipent_field"  value="<?php
										if ( isset( $receiver_user ) ) {
											echo esc_attr( '@' . $receiver_user['name'] );}
										?>" placeholder="@Username" style="min-width: 100%;" onkeyup="pm_get_rid_by_uname(this.value)"/>
								
								<div id="pm-autocomplete"></div>
								<div id="pm-username-error" class="pm-dbfl"></div>
						</div>
						<ul class="dbfl" id="threads_ul">
							<?php echo wp_kses_post( $return ); ?>
						</ul>
					</div>

					<div class="pm-section-right-panel">
					   <?php $this->pg_show_thread_message_panel( $receiver_uid ); ?>
						
					</div>

				</div>
		</div>
<script>
  jQuery("#message_display_area").scrollTop( jQuery("#message_display_area div:last").offset().top);
</script>
		<?php
	}

	public function pg_show_message_tab_html( $uid, $rid, $tid ) {
		$pmrequests   = new PM_request();
		$current_user = wp_get_current_user();
		$return       = $this->pm_messenger_show_threads( $tid );

		?>
		<div class="pg-message-box-container">
		  <div class="pg-message-box-sidebar">
		   <div class="pg-message-box-action">
                       <input value="" id="pg-msg-search-box" class="pg-msg-search" placeholder="<?php esc_attr_e( 'Search messages...', 'profilegrid-user-profiles-groups-and-communities' ); ?>" onkeyup="pg_search_threads(this.value)">
		   
				   <div class="pg-new-thread" title=" <?php esc_attr_e( 'Compose a new message', 'profilegrid-user-profiles-groups-and-communities' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#000" class="pg-new-thread" width="24" height="24" focusable="false" onclick="pg_show_new_thread()">
			<path d="M19 12h2v6a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3h6v2H6a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1zm4-8a2.91 2.91 0 01-.87 2l-8.94 9L7 17l2-6.14 9-9A3 3 0 0123 4zm-4 2.35L17.64 5l-7.22 7.22 1.35 1.34z"></path>
		  </svg></div>
				   <div class="pg-new-thread-action">
							   <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
							   <div class="pg-thread-action-controller" style="display:none">
								   <ul>
									   <li>
										   <a href="#">New conversation</a>
									   </li>
									   <li><a href="#">Manage conversations</a></li>
									   <li><a href="#">Delete conversation</a></li>
									   <li><a href="#">Set away message</a></li>

								   </ul>

							   </div>
								<div class="pg-thread-action-controller-overlay" style="display:none"></div>

						   </div>
					
		  </div>
			<div class="pg-msg-list-wrap">
			  
				<?php
				echo wp_kses_post( $return );
				?>
			</div>
			
		  </div>
		  <div id="pg-msg-thread-container" class="pg-msg-thread-container">
			   
			  <?php
				$this->pg_show_thread_message_panel( $uid, $rid, $tid );
				?>

		  </div>
		</div>

		<?php
	}



	public function pg_show_thread_message_panel( $uid, $rid, $tid, $search = '' ) {
		$pmrequests   = new PM_request();
		$current_user = wp_get_current_user();
		$profile_url  = $pmrequests->pm_get_user_profile_url( $rid );
		$r_avatar     = get_avatar(
			$rid,
			50,
			'',
			false,
			array(
				'class'         => '',
				'force_display' => true,
			)
		);
		$r_name       =  wp_strip_all_tags( $pmrequests->pm_get_display_name( $rid, false ) );
		$path         = plugins_url( '../public/partials/images/typing_image.gif', __FILE__ );
                $getrid = filter_input( INPUT_GET, 'rid' );
		if ( ! isset( $getrid ) && $tid == 0 ) {
			$style  = 'display:none';
			$style2 = 'display:block';
		} else {
			$style  = 'display:flex';
			$style2 = 'display:none';
		}

		?>
        <div class="pg-msg-thread-header" style="<?php echo esc_attr($style); ?>">
            <?php echo wp_kses_post($r_avatar); ?>
            <div class="pg-msg-conversation-info">
                <span class="pg-msg-thread-user"><?php echo esc_html($r_name); ?></span>
                <span class="pg-msg-thread-time"></span>
            </div>
            <div class="pg-msg-thread-wrap">
                <div class="pg-new-thread" title=" <?php esc_attr_e( 'Compose a new message', 'profilegrid-user-profiles-groups-and-communities' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#000" class="pg-new-thread" width="24" height="24" focusable="false" onclick="pg_show_new_thread()">
			<path d="M19 12h2v6a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3h6v2H6a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1zm4-8a2.91 2.91 0 01-.87 2l-8.94 9L7 17l2-6.14 9-9A3 3 0 0123 4zm-4 2.35L17.64 5l-7.22 7.22 1.35 1.34z"></path>
                    </svg> <span onclick="pg_show_new_thread()"><?php esc_html_e('New', 'profilegrid-user-profiles-groups-and-communities'); ?></span>
                </div>
            <div class="pg-thread-open pg-thread-icon">
                <a href="javascript:;"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6-6-6z"/></svg></a>
            </div>
            </div>
            
        </div>

        <div id="pg-new-msg" style="<?php echo esc_attr($style2); ?>">
            <div class="pg-msg-conversation-info pg-msg-connection-head">
                <div class="pg-msg-connection-head-wrap">
                    <span class="pg-msg-conversation-title"><?php esc_html_e('New Message', 'profilegrid-user-profiles-groups-and-communities'); ?></span>
                    <span class="pg-thread-new-msg pg-thread-icon">
                        <a href="javascript:;"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6-6-6z"/></svg></a>
                    </span>
                </div>
                <div class="pg-msg-connections-type-head">
                    <input autocomplete="off" type="text" id="receipent_field" placeholder="<?php esc_attr_e('Type a name', 'profilegrid-user-profiles-groups-and-communities'); ?>" onkeyup="pg_start_new_thread()" />
                </div>
            </div>
        </div>

		  
			<div class="pg-users-search-list-wrap">
			 <?php echo $this->pm_messenger_show_messages( $tid, 1, 0, $search ); ?>
			</div>

			<div class="pg-message-footer">
				  <form id="chat_message_form" name="chat_message_form" onsubmit="pm_messenger_send_chat_message(event);">  
				
                                      <input id="pg_messaging_text" name="content" value="" type="text" data-placeholder="<?php printf( esc_html__( 'Send a message to %s', 'profilegrid-user-profiles-groups-and-communities' ), esc_html( $r_name ) ); ?>" /> 
					  
				  <button id="send_msg_btn" form="chat_message_form" type="submit" name="send">
					<svg width="100%" height="100%" viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" style="fill:#ccc">
	<g transform="matrix(1.05995e-15,17.3103,-17.3103,1.05995e-15,22248.8,-22939.9)">
		<path d="M1340,1256C1340,1256 1350.4,1279.2 1352.6,1284.1C1352.68,1284.28 1352.65,1284.49 1352.53,1284.65C1352.41,1284.81 1352.22,1284.89 1352.02,1284.86C1349.73,1284.54 1344.07,1283.75 1342.5,1283.53C1342.26,1283.5 1342.07,1283.3 1342.04,1283.06C1341.71,1280.61 1340,1268 1340,1268C1340,1268 1338.33,1280.61 1338.01,1283.06C1337.98,1283.31 1337.79,1283.5 1337.54,1283.53C1335.97,1283.75 1330.28,1284.54 1327.98,1284.86C1327.78,1284.89 1327.58,1284.81 1327.46,1284.65C1327.35,1284.49 1327.32,1284.28 1327.4,1284.1C1329.6,1279.2 1340,1256 1340,1256Z"/>
	</g>
	</svg>
				  </button>
				  <input type="hidden" id="receipent_field_rid" name="rid" value="<?php
					if ( isset( $rid ) ) {
						echo wp_kses_post( $rid );}
					?>"  />   
				<?php wp_nonce_field( 'pg_send_new_message' ); ?>
				   <input type="hidden" name="action" value='pm_messenger_send_new_message' /> 
					<input type="hidden" id="thread_hidden_field" name="tid" value="<?php echo esc_attr( $tid ); ?>"/>
                                        <input type="hidden" name="sid" value="<?php echo esc_attr( $uid ); ?>" /> 
				  <input type="hidden" name="new_thread" id="new_thread" value="0" />
				  <input type="hidden" name="mid" id="mid" value="" />
				  </form>
			</div>

		<?php
	}

	public function pg_show_thread_message_panel_old( $rid ) {
		$pmrequests   = new PM_request();
		$current_user = wp_get_current_user();
		$uid          = $current_user->ID;

		if ( $rid == '' ) {
			$threads = $pmrequests->pm_get_user_all_threads( $uid, 1 );
			if ( ! empty( $threads ) ) {
				if ( $uid == $threads[0]->r_id ) {
					$rid = $threads[0]->s_id;
				} else {
					$rid = $threads[0]->r_id;
				}

				$tid = $threads[0]->t_id;
			}
		}
		if ( ! isset( $tid ) ) {
			$tid = $pmrequests->get_thread_id( $rid, $uid );
		}
		$profile_url = $pmrequests->pm_get_user_profile_url( $rid );
		$r_avatar    = get_avatar(
			$rid,
			50,
			'',
			false,
			array(
				'class'         => 'pm-user-profile',
				'force_display' => true,
			)
		);
		$r_name      = wp_strip_all_tags( $pmrequests->pm_get_display_name( $rid, false ) );
		$return      = $this->pm_messenger_show_messages( $tid, 1 );
		?>
		

			
 <div class="pm-blog-desc-wrap pm-difl pm-section-content pm-message-thread-section">
				<div id="pm-msg-overlay" class="pm-msg-overlay  
				<?php
				if ( ( $return == 'You have no conversations yet.' ) && ! isset( $receiver_user ) ) {
					echo 'pm-overlay-show1';}
				?>
				"> </div>
				<form id="chat_message_form" onsubmit="pm_messenger_send_chat_message(event);">  
				<input type="hidden" id="receipent_field_rid" name="rid" value="<?php
				if ( isset( $rid ) ) {
					echo wp_kses_post( $rid );}
				?>"  />   
				<div class="contact-profile" id="userSection">	
				<?php

					echo '<div class="pm-conversation-box-user pm-difl"><a href="' . esc_url( $profile_url ) . '">' . wp_kses_post( $r_avatar ) . '</a></div>';
					echo '<p>' . wp_kses_post( $r_name ) . '</p>';
				?>
										
				</div>
				
				
				<div id="message_display_area" class="pm-difl pm_full_width_profile"  style="min-height:200px;max-height:200px;max-width: 550px;overflow-y:auto;">
					<?php echo $return; ?>
				<?php $path = plugins_url( '../public/partials/images/typing_image.gif', __FILE__ ); ?>
				
				</div>
					
				<div id="typing_on"  class="pm-user-description-row pm-dbfl pm-border"><div class="pm-typing-inner"><img height="9px" width="40px" src="<?php echo esc_url( $path ); ?>"/></div></div>
			  
				<div class="pm-dbfl pm-chat-messenger-box">
                                    <?php wp_nonce_field( 'pg_send_new_message' ); ?>
					  <input type="hidden" name="action" value='pm_messenger_send_new_message' /> 
					<input type="hidden" id="thread_hidden_field" name="tid" value=""/>
					<div class="emoji-container">
						<div class="pm-messenger-user-profile-pic">
						<?php
						$avatar = get_avatar(
							$current_user->ID,
							50,
							'',
							false,
							array(
								'class'         => 'pm-user-profile',
								'force_display' => true,
							)
						);
																   echo wp_kses_post( $avatar );
						?>
						</div>
					<textarea id="messenger_textarea" data-emojiable="true"  name="content" style="min-width: 100%;height:100px;"
						
							   form="chat_message_form" placeholder="<?php esc_attr_e( 'Type your message..', 'profilegrid-user-profiles-groups-and-communities' ); ?>" ></textarea> 
					<input type="hidden" disabled  maxlength="4" size="4" value="1000" id="counter">
					<input type="hidden" name="sid" value="" />   
					<div class="pm-messenger-button">
						<label>
						  <input id="send_msg_btn" type="submit" name="send" value="<?php esc_attr_e( 'send', 'profilegrid-user-profiles-groups-and-communities' ); ?>"/>
					<svg width="100%" height="100%" viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" style="fill:#ccc">
	<g transform="matrix(1.05995e-15,17.3103,-17.3103,1.05995e-15,22248.8,-22939.9)">
		<path d="M1340,1256C1340,1256 1350.4,1279.2 1352.6,1284.1C1352.68,1284.28 1352.65,1284.49 1352.53,1284.65C1352.41,1284.81 1352.22,1284.89 1352.02,1284.86C1349.73,1284.54 1344.07,1283.75 1342.5,1283.53C1342.26,1283.5 1342.07,1283.3 1342.04,1283.06C1341.71,1280.61 1340,1268 1340,1268C1340,1268 1338.33,1280.61 1338.01,1283.06C1337.98,1283.31 1337.79,1283.5 1337.54,1283.53C1335.97,1283.75 1330.28,1284.54 1327.98,1284.86C1327.78,1284.89 1327.58,1284.81 1327.46,1284.65C1327.35,1284.49 1327.32,1284.28 1327.4,1284.1C1329.6,1279.2 1340,1256 1340,1256Z"/>
	</g>
	</svg>
						</label>      
					</div>
				</div>
					</div>
			</form>
				
				

		</div>

		<?php
	}

	public function pm_messenger_send_new_message( $rid, $content ) {
		$dbhandler    = new PM_DBhandler();
		$pmrequests   = new PM_request();
		$current_user = wp_get_current_user();
		$sid          = $current_user->ID;

			$is_msg_sent = $pmrequests->pm_create_message( $sid, $rid, $content );

		if ( !$is_msg_sent ) {
			$return = __( 'not sent', 'profilegrid-user-profiles-groups-and-communities' );
		}
		return $is_msg_sent;

	}

	public function pm_messenger_send_edit_message( $rid, $mid, $content ) {
		$pmrequests  = new PM_request();
		$is_msg_sent = $pmrequests->pm_edit_message( $rid, $mid, $content );
		return $is_msg_sent;

	}

	public function pm_messenger_delete_threads( $tid, $uid, $mid ) {
		$dbhandler    = new PM_DBhandler();
		$pmrequests   = new PM_request();
		$current_user = wp_get_current_user();
		$uid          = $current_user->ID;
		$pmrequests->update_message_status_to_read( $tid );
		$delete_thread = $pmrequests->delete_thread( $tid, $uid, $mid );
		if ( $delete_thread > 0 ) {
			return 'true';
		} else {
			return 'false';
		}

	}

}
