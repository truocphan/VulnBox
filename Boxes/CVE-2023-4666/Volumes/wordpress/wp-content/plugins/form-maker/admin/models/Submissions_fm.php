<?php

/**
 * Class FMModelSubmissions_fm
 */
class FMModelSubmissions_fm extends FMAdminModel {

  public function blocked_ips() {
    global $wpdb;
    $ips = $wpdb->get_col('SELECT ip FROM ' . $wpdb->prefix . 'formmaker_blocked');

    return $ips;
  }

	/**
	* Get all forms.
	*
	* @return object $forms
	*/
	public function get_forms() {
		global $wpdb;
		$query = "SELECT `id`, `title`, `published`, `form_fields`, `form_options` FROM " . $wpdb->prefix . "formmaker " . (!WDFMInstance(self::PLUGIN)->is_free ? '' : ' WHERE id' . (WDFMInstance(self::PLUGIN)->is_free == 1 ? ' NOT ' : ' ') . 'IN (' . (get_option('contact_form_forms', '') != '' ? get_option('contact_form_forms') : 0) . ')') . " ORDER BY `title`";
		$results = $wpdb->get_results($query);
		foreach ($results as $key => $result) {
		  $results[$key] = WDW_FM_Library::convert_json_options_to_old($result, 'form_options');
    }
		$forms = array();
		if( !empty($results) ) {
			foreach($results as $row) {
				$forms[$row->id] = $row;
			}
		}
		return $forms;
	}

	/**
	* Get form.
	*
	* @param int $id
	*
	* @return object $form
	*/
	public function get_form( $id = 0 ) {
		global $wpdb;
		$query = 'SELECT `id`, `title` FROM ' . $wpdb->prefix .'formmaker WHERE id = ' . $id ;
		$form = $wpdb->get_row( $query );
		return $form;
	}

	/**
	* Get subs count.
	*
	* @param int $id
	* @return int $count
	*/
	public function get_subs_count( $id = 0 ) {
		global $wpdb;
		$query = $wpdb->prepare("SELECT distinct group_id FROM " . $wpdb->prefix . "formmaker_submits where form_id=%d", $id);
		$group_id_s = $wpdb->get_col($query);
		$count = count($group_id_s);
		return $count;
	}

	/**
	* Get statistics.
	*
	* @param int $id
	* @return object $statistics
	*/
	public function get_statistics( $id = 0, $save_db = 1 ) {
		global $wpdb;
		$statistics = array();

    $submission_count_query = "SELECT count(DISTINCT group_id) FROM " . $wpdb->prefix . "formmaker_submits WHERE form_id=%d";
    $submission_count = $wpdb->get_var( $wpdb->prepare( $submission_count_query, $id ) );
    if ( $save_db == 2 ) {
      $in_progress_count_query = $submission_count_query . " AND (element_value = 'In progress' or element_value = 'Pending')";

      $in_progress_count = $wpdb->get_var( $wpdb->prepare( $in_progress_count_query, $id ) );
      $submission_count = intval($submission_count) - intval($in_progress_count);
    }

		$statistics["total_entries"] = $submission_count;

		$query = $wpdb->prepare('SELECT `views` FROM ' . $wpdb->prefix . 'formmaker_views WHERE form_id=%d', $id);
		$statistics["total_views"] = $wpdb->get_var($query);
		if ( $statistics["total_views"] ) {
		  $statistics["conversion_rate"] = round((($statistics["total_entries"] / $statistics["total_views"]) * 100), 2) . '%';
		}
		else {
		  $statistics["conversion_rate"] = '0%';
		}
		return $statistics;
	}

	/*
	* Get labels parameters.
	*
	* @param int $form_id
	* @param int $page_num
	* @param int $per_num
	* @return array $labels_parameters
	*/

  public function get_labels_parameters( $form_id = 0, $page_num = 0, $per_num = 0 ) {
    global $wpdb;
    $labels = array();
    $labels_id = array();
    $form_labels = array();
    $sorted_labels_id = array();
    $label_names = array();
    $label_types = array();
    $sorted_label_types = array();
    $label_names_original = array();
    $labels_parameters = array();
    $join_query = array();
    $join_where = array();
    $join_verified = array();
    $rows_ord = array();
    $ver_email_keys_for_regex  = array();
    $join = '';
    $query = $wpdb->prepare("SELECT `group_id`,`element_value` FROM " . $wpdb->prefix . "formmaker_submits  WHERE `form_id`='%d' and `element_label` = 'verifyinfo' ", $form_id);
    $ver_emails_data = $wpdb->get_results($query);
    $ver_emails_array = array();
    if ( $ver_emails_data ) {
      foreach ( $ver_emails_data as $ver_email ) {
        $element_label_new = explode('verified**', $ver_email->element_value)[1];
        $query = $wpdb->prepare("SELECT `element_value` FROM " . $wpdb->prefix . "formmaker_submits  WHERE `form_id`='%d' AND `group_id` = '%d' AND `element_label` = '%s' ", $form_id, (int) $ver_email->group_id, $element_label_new);
        if ( !isset($ver_emails_array[$ver_email->group_id]) ) {
          $ver_emails_array[$ver_email->group_id] = array();
        }
        if ( !in_array($wpdb->get_var($query), $ver_emails_array[$ver_email->group_id]) ) {
          $ver_emails_array[$ver_email->group_id][] = $wpdb->get_var($query);
        }
      }
    }
    for ( $i = 0; $i < 9; $i++ ) {
      array_push($labels_parameters, NULL);
    }
    $sorted_label_names = array();
    $sorted_label_names_original = array();
    $where_labels = array();
    $where2 = array();
    $order_by = WDW_FM_Library(self::PLUGIN)->get('order_by', 'group_id');
    $asc_or_desc = (WDW_FM_Library(self::PLUGIN)->get('asc_or_desc', 'desc') == 'desc' ? 'desc' : 'asc');
    $lists['hide_label_list'] = WDW_FM_Library(self::PLUGIN)->get('hide_label_list');
    $lists['startdate'] = WDW_FM_Library(self::PLUGIN)->get('startdate');
    $lists['enddate'] = WDW_FM_Library(self::PLUGIN)->get('enddate');
    $lists['ip_search'] = WDW_FM_Library(self::PLUGIN)->get('ip_search');
    $lists['username_search'] = WDW_FM_Library(self::PLUGIN)->get('username_search');
    $lists['useremail_search'] = WDW_FM_Library(self::PLUGIN)->get('useremail_search');
    $lists['id_search'] = WDW_FM_Library(self::PLUGIN)->get('id_search');
    if ( $lists['ip_search'] ) {
      $where[] = 'ip LIKE "%' . $lists['ip_search'] . '%"';
    }
    if ( $lists['startdate'] != '' ) {
      $where[] = " `date`>='" . $lists['startdate'] . " 00:00:00' ";
    }
    if ( $lists['enddate'] != '' ) {
      $where[] = " `date`<='" . $lists['enddate'] . " 23:59:59' ";
    }
    if ( $lists['username_search'] ) {
      $where[] = 'user_id_wd IN (SELECT ID FROM ' . $wpdb->prefix . 'users WHERE display_name LIKE "%' . $lists['username_search'] . '%")';
    }
    if ( $lists['useremail_search'] ) {
      $where[] = 'user_id_wd IN (SELECT ID FROM ' . $wpdb->prefix . 'users WHERE user_email LIKE "%' . $lists['useremail_search'] . '%")';
    }
    if ( $lists['id_search'] ) {
      $where[] = 'group_id =' . (int) $lists['id_search'];
    }
    $where[] = 'form_id=' . $form_id . '';
    $where = (count($where) ? ' ' . implode(' AND ', $where) : '');
    if ( $order_by == 'group_id' or $order_by == 'date' or $order_by == 'ip' ) {
      $orderby = ' ORDER BY ' . $order_by . ' ' . $asc_or_desc . '';
    }
    elseif ( $order_by == 'display_name' or $order_by == 'user_email' ) {
      $orderby = ' ORDER BY (SELECT ' . $order_by . ' FROM ' . $wpdb->prefix . 'users WHERE ID=user_id_wd) ' . $asc_or_desc . '';
    }
    else {
      $orderby = "";
    }
    if ( $form_id ) {
      for ( $i = 0; $i < 9; $i++ ) {
        array_pop($labels_parameters);
      }
      $query = "SELECT distinct element_label FROM " . $wpdb->prefix . "formmaker_submits WHERE " . $where;
      $results = $wpdb->get_results($query);
      for ( $i = 0; $i < count($results); $i++ ) {
        array_push($labels, $results[$i]->element_label);
      }
      $form = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "formmaker WHERE id='%d'", $form_id));
      $form = WDW_FM_Library::convert_json_options_to_old( $form, 'form_options');
      if ( !empty($form->label_order) && strpos($form->label_order, 'type_submitter_mail') ) {
        $form->label_order = $form->label_order . 'user_email#**id**#user_email#**label**#type_user_email#****#';
        $form->label_order = $form->label_order . 'verifyInfo#**id**#verify_info#**label**#type_user_email_verify#****#';
      }
      if ( !empty($form->label_order) && strpos($form->label_order, 'type_paypal_') ) {
        $form->label_order = $form->label_order . "item_total#**id**#Item Total#**label**#type_paypal_payment_total#****#total#**id**#Total#**label**#type_paypal_payment_total#****#0#**id**#Payment Status#**label**#type_paypal_payment_status#****#";
      }
      if ( !empty($form->label_order) ) {
        $form_labels = explode('#****#', $form->label_order);
      }
      $form_labels = array_slice($form_labels, 0, count($form_labels) - 1);
      foreach ( $form_labels as $key => $form_label ) {
        $label_id = explode('#**id**#', $form_label);
        array_push($labels_id, $label_id[0]);
        $label_name_type = explode('#**label**#', $label_id[1]);
        array_push($label_names_original, $label_name_type[0]);
        $ptn = "/[^a-zA-Z0-9_]/";
        $rpltxt = "";
        $label_name = preg_replace($ptn, $rpltxt, $label_name_type[0]);
        array_push($label_names, $label_name);
        array_push($label_types, $label_name_type[1]);
      }
      foreach ( $labels_id as $key => $label_id ) {
        if ( in_array($label_id, $labels) ) {
          if ( !in_array($label_id, $sorted_labels_id) ) {
            array_push($sorted_labels_id, $label_id);
          }
          array_push($sorted_label_names, $label_names[$key]);
          array_push($sorted_label_types, $label_types[$key]);
          array_push($sorted_label_names_original, $label_names_original[$key]);
          $search_temp = '';
          $_search_key = $form_id . '_' . $label_id . '_search';
          if ( WDW_FM_Library(self::PLUGIN)->get($_search_key) ) {
            $search_temp = WDW_FM_Library(self::PLUGIN)->get($_search_key);
          }
          $search_temp = urldecode($search_temp);
          /* TODO conflict other DB version
            $search_temp = html_entity_decode($search_temp, ENT_QUOTES);
          */
          $lists[$form_id . '_' . $label_id . '_search'] = $search_temp;
          if ( $search_temp ) {
            $join_query[] = 'search';
            $join_where[] = array( 'label' => $label_id, 'search' => $search_temp );
          }
          $search_verified = '';
          if ( WDW_FM_Library(self::PLUGIN)->get($form_id . '_' . $label_id . '_search_verified') ) {
            $search_verified = WDW_FM_Library(self::PLUGIN)->get($form_id . '_' . $label_id . '_search_verified');
            $lists[$form_id . '_' . $label_id . '_search_verified'] = $search_verified;
          }
          if ( $search_verified ) {
            $join_query[] = 'search';
            $join_where[] = NULL;
            foreach (array_keys($ver_emails_array) as $key => $value) {
              $ver_email_keys_for_regex[$key] = '^' . $value . '$';
            }
            $join_verified[] = array(
              'label' => $label_id,
              'ver_search' => implode('|', $ver_email_keys_for_regex),
            );
          }
        }
      }
      if ( strpos($order_by, "_field") ) {
        if ( in_array(str_replace("_field", "", $order_by), $labels) ) {
          $join_query[] = 'sort';
          $join_where[] = array( 'label' => str_replace("_field", "", $order_by) );
        }
      }
      $cols = 'group_id';
      if ( $order_by == 'date' or $order_by == 'ip' ) {
        $cols = 'group_id, date, ip';
      }
      $ver_where = '';
      if ( !empty($join_verified) ) {
        foreach ( $join_verified as $key_ver => $join_ver ) {
          $ver_where .= '(element_label ="' . $join_ver['label'] . '" AND group_id REGEXP "' . $join_ver['ver_search'] . '" ) AND';
        }
      }
      switch ( count($join_query) ) {
        case 0:
          $join = 'SELECT distinct group_id FROM ' . $wpdb->prefix . 'formmaker_submits WHERE ' . $where;
          break;
        case 1:
          if ( $join_query[0] == 'sort' ) {
            $join = 'SELECT group_id FROM ' . $wpdb->prefix . 'formmaker_submits WHERE ' . $where . ' AND element_label="' . $join_where[0]['label'] . '" ';
            $join_count = 'SELECT count(group_id) FROM ' . $wpdb->prefix . 'formmaker_submits WHERE form_id="' . $form_id . '" AND element_label="' . $join_where[0]['label'] . '" ';
            $orderby = ' ORDER BY `element_value` ' . $asc_or_desc;
          }
          else {
            if ( isset($join_where[0]['search']) ) {
              $join = 'SELECT group_id FROM ' . $wpdb->prefix . 'formmaker_submits WHERE element_label="' . $join_where[0]['label'] . '" AND  (element_value LIKE "%' . $join_where[0]['search'] . '%" OR element_value LIKE "%' . str_replace(' ', '@@@', $join_where[0]['search']) . '%")  AND ' . $where;
            }
            else {
              $join = 'SELECT group_id FROM ' . $wpdb->prefix . 'formmaker_submits WHERE  ' . $ver_where . $where;
            }
          }
          break;
        default:
          if ( !empty($join_verified) ) {
            if ( isset($join_where[0]['search']) ) {
              $join = 'SELECT t.group_id from (SELECT t1.group_id from (SELECT ' . $cols . ' FROM ' . $wpdb->prefix . 'formmaker_submits WHERE (element_label="' . $join_where[0]['label'] . '" AND (element_value LIKE "%' . $join_where[0]['search'] . '%" OR element_value LIKE "%' . str_replace(' ', '@@@', $join_where[0]['search']) . '%")) AND ' . $where . ' ) as t1 JOIN (SELECT group_id FROM ' . $wpdb->prefix . 'formmaker_submits WHERE  ' . $ver_where . $where . ') as t2 ON t1.group_id = t2.group_id) as t ';
            }
            else {
              $join = 'SELECT t.group_id FROM (SELECT ' . $cols . '  FROM ' . $wpdb->prefix . 'formmaker_submits WHERE ' . $ver_where . $where . ') as t ';
            }
          }
          else {
            $join = 'SELECT t.group_id FROM (SELECT ' . $cols . '  FROM ' . $wpdb->prefix . 'formmaker_submits WHERE ' . $where . ' AND element_label="' . $join_where[0]['label'] . '" AND  (element_value LIKE "%' . $join_where[0]['search'] . '%" OR element_value LIKE "%' . str_replace(' ', '@@@', $join_where[0]['search']) . '%" )) as t ';
          }
          for ( $key = 1; $key < count($join_query); $key++ ) {
            if ( $join_query[$key] == 'sort' ) {
              if ( isset($join_where[$key]) ) {
                $join .= 'LEFT JOIN (SELECT group_id as group_id' . $key . ', element_value   FROM ' . $wpdb->prefix . 'formmaker_submits WHERE ' . $where . ' AND element_label="' . $join_where[$key]['label'] . '") as t' . $key . ' ON t' . $key . '.group_id' . $key . '=t.group_id ';
                $orderby = ' ORDER BY t' . $key . '.`element_value` ' . $asc_or_desc . '';
              }
            }
            else {
              if ( isset($join_where[$key]) ) {
                $join .= 'INNER JOIN (SELECT group_id as group_id' . $key . ' FROM ' . $wpdb->prefix . 'formmaker_submits WHERE ' . $where . ' AND element_label="' . $join_where[$key]['label'] . '" AND  (element_value LIKE "%' . $join_where[$key]['search'] . '%" OR element_value LIKE "%' . str_replace(' ', '@@@', $join_where[$key]['search']) . '%")) as t' . $key . ' ON t' . $key . '.group_id' . $key . '=t.group_id ';
              }
            }
          }
          break;
      }
      $pos = strpos($join, 'SELECT t.group_id');
      if ( $pos === FALSE ) {
        $query = str_replace(array(
                               'SELECT group_id',
                               'SELECT distinct group_id',
                             ), array( 'SELECT count(distinct group_id)', 'SELECT count(distinct group_id)' ), $join);
      }
      else {
        $query = str_replace('SELECT t.group_id', 'SELECT count(t.group_id)', $join);
      }
      $total = $wpdb->get_var($query);
      $query_sub_count = "SELECT count(distinct group_id) from " . $wpdb->prefix . "formmaker_submits";
      $sub_count = (int) $wpdb->get_var($query_sub_count);
      $query = $join . ' ' . $orderby . ' LIMIT ' . $page_num . ', ' . $per_num;
      $results = $wpdb->get_results($query);
      for ( $i = 0; $i < count($results); $i++ ) {
        array_push($rows_ord, $results[$i]->group_id);
      }
      $query1 = $join . ' ' . $orderby;
      $searched_group_ids = $wpdb->get_results($query1);
      $searched_ids = array();
      for ( $i = 0; $i < count($searched_group_ids); $i++ ) {
        array_push($searched_ids, $searched_group_ids[$i]->group_id);
      }
      $where2 = array();
      $where2[] = "group_id='0'";
      foreach ( $rows_ord as $rows_ordd ) {
        $where2[] = "group_id='" . $rows_ordd . "'";
      }
      $where2 = (count($where2) ? ' WHERE ' . implode(' OR ', $where2) . '' : '');
      $query = 'SELECT * FROM ' . $wpdb->prefix . 'formmaker_submits ' . $where2;
      $rows = $wpdb->get_results($query);
      $group_ids = $rows_ord;
      $lists['total'] = $total;
      $lists['limit'] = $per_num;
      $where_choices = $where;
      array_push($labels_parameters, $sorted_labels_id);
      array_push($labels_parameters, $sorted_label_types);
      array_push($labels_parameters, $lists);
      array_push($labels_parameters, $sorted_label_names);
      array_push($labels_parameters, $sorted_label_names_original);
      array_push($labels_parameters, $rows);
      array_push($labels_parameters, $group_ids);
      array_push($labels_parameters, $where_choices);
      array_push($labels_parameters, $searched_ids);
    }

    return $labels_parameters;
  }

  /**
   * Get type address.
   *
   * @param string $sorted_label_type
   * @param string $sorted_label_name_original
   * @return mixed|string
   */
  public function get_type_address( $sorted_label_type = '', $sorted_label_name_original = '' ) {
    if ( $sorted_label_type == 'type_address' ) {
      switch ( $sorted_label_name_original ) {
        case 'Street Line':
          $field_title = __('Street Address', 'form_maker');
          break;
        case 'Street Line2':
          $field_title = __('Street Address Line 2', 'form_maker');
          break;
        case 'City':
          $field_title = __('City', 'form_maker');
          break;
        case 'State':
          $field_title = __('State / Province / Region', 'form_maker');
          break;
        case 'Postal':
          $field_title = __('Postal / Zip Code', 'form_maker');
          break;
        case 'Country':
          $field_title = __('Country', 'form_maker');
          break;
        default :
          $field_title = stripslashes($sorted_label_name_original);
          break;
      }
    }
    else {
      $field_title = stripslashes($sorted_label_name_original);
    }

    return $field_title;
  }

  /**
   * Hide or not.
   *
   * @param string $hide_strings
   * @param string $hide_string
   * @return string
   */
  public function hide_or_not( $hide_strings = '', $hide_string = '' ) {
    if ( strpos($hide_string, '@') === FALSE ) {
      if ( strpos($hide_strings, '@' . $hide_string . '@') === FALSE ) {
        $style = '';
      }
      else {
        $style = 'style="display:none"';
      }
    }
    else {
      if ( strpos($hide_strings, $hide_string) === FALSE ) {
        $style = '';
      }
      else {
        $style = 'style="display:none"';
      }
    }

    return $style;
  }

  /**
   * Sort group ids.
   *
   * @param int $sorted_label_names_count
   * @param array $group_ids
   * @return array
   */
  public function sort_group_ids( $sorted_label_names_count = 0, $group_ids = array() ) {
    $count_label = $sorted_label_names_count;
    $group_id_s = array();
    $l = 0;
    if ( count($group_ids) > 0 and $count_label ) {
      for ( $i = 0; $i < count($group_ids); $i++ ) {
        if ( !in_array($group_ids[$i], $group_id_s) ) {
          array_push($group_id_s, $group_ids[$i]);
        }
      }
    }
    return $group_id_s;
  }

  /**
   * Array for group id.
   *
   * @param int $group
   * @param array $rows
   * @return array
   */
  public function array_for_group_id( $group = 0, $rows = array() ) {
    $i = $group;
    $count_rows = count($rows);
    $temp = array();
    for ( $j = 0; $j < $count_rows; $j++ ) {
      $row = $rows[$j];
      if ( $row->group_id == $i ) {
        array_push($temp, $row);
      }
    }

    return $temp;
  }

  /**
   * Sorted label type.
   *
   * @param string $sorted_label_type
   * @return bool
   */
  public function check_radio_type( $sorted_label_type = '' ) {
    if ( $sorted_label_type == "type_checkbox" || $sorted_label_type == "type_radio" || $sorted_label_type == "type_own_select" || $sorted_label_type == "type_country" || $sorted_label_type == "type_paypal_select" || $sorted_label_type == "type_paypal_radio" || $sorted_label_type == "type_paypal_checkbox" || $sorted_label_type == "type_paypal_shipping" ) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Statistic for radio.
   *
   * @param string $where_choices
   * @param string $sorted_label_id
   * @return array
   */
  public function statistic_for_radio( $where_choices = '', $sorted_label_id = '', $field = '' ) {
    global $wpdb;
    $choices_params = array();
    $query = "SELECT element_value FROM " . $wpdb->prefix . "formmaker_submits WHERE " . $where_choices . " AND element_label='" . $sorted_label_id . "'";
    $choices = $wpdb->get_results($query);
    $colors = array( '#5FE2FF', '#F9E89C' );
    $choices_colors = array( '#4EC0D9', '#DDCC7F' );
    $choices_labels = array();
    $choices_count = array();
    $all = count($choices);
    $choices_checkbox = array();
    $unanswered = 0;
    if( $field == "type_checkbox" ) {
      foreach ( $choices as $key => $choice ) {
        if ( $choice->element_value == '' ) {
          $unanswered++;
        } else {
          $values=explode('***br***',$choice->element_value);
          unset($values[count($values)-1]);
          $choices_checkbox = array_merge( $choices_checkbox, $values );
        }
      }
      foreach ( $choices_checkbox as $key => $choice ) {
        if ( !in_array($choice, $choices_labels) ) {
          array_push($choices_labels, $choice);
          array_push($choices_count, 0);
        }
        $choices_count[array_search($choice, $choices_labels)]++;
      }
      $all = count($choices_checkbox) + $unanswered;
    }
    else {
      foreach ( $choices as $key => $choice ) {
        if ( $choice->element_value == '' ) {
          $unanswered++;
        }
        else {
          if ( !in_array($choice->element_value, $choices_labels) ) {
            array_push($choices_labels, $choice->element_value);
            array_push($choices_count, 0);
          }
          $choices_count[array_search($choice->element_value, $choices_labels)]++;
        }
      }
    }
    array_multisort($choices_count, SORT_DESC, $choices_labels);
    array_push($choices_params, $choices_count);
    array_push($choices_params, $choices_labels);
    array_push($choices_params, $unanswered);
    array_push($choices_params, $all);
    array_push($choices_params, $colors);
    array_push($choices_params, $choices_colors);
    return $choices_params;
  }

  /**
   * Get data of group id.
   *
   * @param int $id
   * @return array
   */
  public function get_data_of_group_id( $id = 0 ) {
    global $wpdb;
    $query = "SELECT * FROM " . $wpdb->prefix . "formmaker_submits WHERE group_id=" . $id;
    $rows = $wpdb->get_results($query);
    $form = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "formmaker WHERE id=" . $rows[0]->form_id);
    $form = WDW_FM_Library::convert_json_options_to_old( $form, 'form_options');
    $params = array();
    $label_id = array();
    $label_order_original = array();
    $label_type = array();
    $ispaypal = strpos($form->label_order, 'type_paypal_');
    if ( $form->paypal_mode == 1 ) {
      if ( $ispaypal ) {
        $form->label_order = $form->label_order . "0#**id**#Payment Status#**label**#type_paypal_payment_status#****#";
      }
    }
    $label_all = explode('#****#', $form->label_order);
    $label_all = array_slice($label_all, 0, count($label_all) - 1);
    foreach ( $label_all as $key => $label_each ) {
      $label_id_each = explode('#**id**#', $label_each);
      array_push($label_id, $label_id_each[0]);
      $label_oder_each = explode('#**label**#', $label_id_each[1]);
      array_push($label_order_original, $label_oder_each[0]);
      array_push($label_type, $label_oder_each[1]);
    }
    /*$theme_id = $wpdb->get_var("SELECT theme FROM " . $wpdb->prefix . "formmaker WHERE id='" . $form->id . "'");*/
    $css = $wpdb->get_var("SELECT css FROM " . $wpdb->prefix . "formmaker_themes");
    array_push($params, $rows);
    array_push($params, $label_id);
    array_push($params, $label_order_original);
    array_push($params, $label_type);
    array_push($params, $ispaypal);
    array_push($params, $form);
    array_push($params, $css);

    return $params;
  }

  /**
   * Check type for edit function.
   *
   * @param string $label_type
   * @return bool
   */
  public function check_type_for_edit_function( $label_type = '' ) {
    if ( $label_type != 'type_editor' and $label_type != 'type_submit_reset' and $label_type != 'type_map' and $label_type != 'type_mark_map' and $label_type != 'type_captcha' and $label_type != 'type_recaptcha' and $label_type != 'type_button' ) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Check for submited label.
   *
   * @param array $rows
   * @param string $label_id
   * @return string
   */
  public function check_for_submited_label( $rows = array(), $label_id = '' ) {
    foreach ( $rows as $row ) {
      if ( $row->element_label == $label_id ) {
        $element_value = $row->element_value;
        break;
      }
      else {
        $element_value = 'continue';
      }
    }

    return esc_html( $element_value );
  }

  /*
  * Create array of group by key.
  *
  *	@param array 	$array
  * @param string $key
  *
  * @return array $data
  */
  public function _group_by( $array = array(), $key = '' ) {
		$data = array();
		foreach($array as $val) {
			$by_key = '';
			if( is_object($val) ) {
				$by_key = $val->$key;
			}
			if( is_array($val) ) {
				$by_key = $val[$key];
			}

			if( $by_key ){
				$data[$by_key][] = $val;
			}
		}
		return $data;
	}

  /**
   * View for star rating.
   *
   * @param string $element_value
   * @param string $element_label
   * @return array
   */
  public function view_for_star_rating( $element_value = '', $element_label = '' ) {
    $view_star_rating_array = array();
    $new_filename = str_replace("***star_rating***", '', $element_value);
    $stars = "";
    $new_filename = explode('***', $new_filename);
    for ( $j = 0; $j < $new_filename[1]; $j++ ) {
      $stars .= '<img id="' . $element_label . '_star_' . $j . '" src="' . WDFMInstance(self::PLUGIN)->plugin_url . '/images/star_' . $new_filename[2] . '.png?ver=' . WDFMInstance(self::PLUGIN)->plugin_version . '" /> ';
    }
    for ( $k = $new_filename[1]; $k < $new_filename[0]; $k++ ) {
      $stars .= '<img id="' . $element_label . '_star_' . $k . '" src="' . WDFMInstance(self::PLUGIN)->plugin_url . '/images/star.png?ver=' . WDFMInstance(self::PLUGIN)->plugin_version . '" /> ';
    }
    array_push($view_star_rating_array, $stars);

    return $view_star_rating_array;
  }

  /**
   * View for grading.
   *
   * @param string $element_value
   * @return array
   */
  public function view_for_grading( $element_value = '' ) {
    $view_grading_array = array();
    $new_filename = str_replace("***grading***", '', $element_value);
    $grading = explode(":", $new_filename);
    $items_count = sizeof($grading) - 1;
    $items = "";
    $total = 0;
    for ( $k = 0; $k < $items_count / 2; $k++ ) {
		$items .= $grading[$items_count / 2 + $k] . ": " . $grading[$k] . "</br>";
		$total += (is_numeric($grading[$k])) ? $grading[$k] : 0;
    }
    $items .= "Total: " . $total;
    array_push($view_grading_array, $items);

    return $view_grading_array;
  }

  /**
   * Images for star rating.
   *
   * @param string $element_value
   * @param string $label_id
   * @return array
   */
  public function images_for_star_rating( $element_value = '', $label_id = '' ) {
    $edit_stars = "";
    $star_rating_array = array();
    $element_value1 = str_replace("***star_rating***", '', $element_value);
    $stars_value = explode('/', $element_value1);
    for ( $j = 0; $j < $stars_value[0]; $j++ ) {
      $edit_stars .= '<img id="' . $label_id . '_star_' . $j . '" onclick="edit_star_rating(' . $j . ',' . $label_id . ')" src="' . WDFMInstance(self::PLUGIN)->plugin_url . '/images/star_yellow.png?ver=' . WDFMInstance(self::PLUGIN)->plugin_version . '" /> ';
    }
    for ( $k = $stars_value[0]; $k < $stars_value[1]; $k++ ) {
      $edit_stars .= '<img id="' . $label_id . '_star_' . $k . '" onclick="edit_star_rating(' . $k . ',' . $label_id . ')" src="' . WDFMInstance(self::PLUGIN)->plugin_url . '/images/star.png?ver=' . WDFMInstance(self::PLUGIN)->plugin_version . '" /> ';
    }
    array_push($star_rating_array, $edit_stars);
    array_push($star_rating_array, $stars_value);

    return $star_rating_array;
  }

  /**
   * Params for scale rating.
   *
   * @param string $element_value
   * @param string $label_id
   * @return array
   */
  public function params_for_scale_rating( $element_value = '', $label_id = '' ) {
    $scale_rating_array = array();
    $scale_radio = explode('/', $element_value);
    $scale_value = $scale_radio[0];
    $scale = '<table><tr>';
    for ( $k = 1; $k <= $scale_radio[1]; $k++ ) {
      $scale .= '<td style="text-align:center"><span>' . $k . '</span></td>';
    }
    $scale .= '<tr></tr>';
    for ( $l = 1; $l <= $scale_radio[1]; $l++ ) {
      if ( $l == $scale_radio[0] ) {
        $checked = "checked";
      }
      else {
        $checked = "";
      }
      $scale .= '<td><input type="radio" name = "' . $label_id . '_scale_rating_radio" id = "' . $label_id . '_scale_rating_radio_' . $l . '" value="' . $l . '" ' . $checked . ' onClick="edit_scale_rating(this.value,' . $label_id . ')" /></td>';
    }
    $scale .= '</tr></table>';
    array_push($scale_rating_array, $scale);
    array_push($scale_rating_array, $scale_radio);
    array_push($scale_rating_array, $checked);

    return $scale_rating_array;
  }

  /**
   * Params for type range.
   *
   * @param string $element_value
   * @param string $label_id
   * @return string
   */
  public function params_for_type_range( $element_value = '', $label_id = '' ) {
    $range_value = explode('-', $element_value);
    $range = '<input name="' . $label_id . '_element0"  id="' . $label_id . '_element0" type="text" value="' . $range_value[0] . '" onChange="edit_range(this.value,' . $label_id . ',0)" size="8"/> - <input name="' . $label_id . '_element1"  id="' . $label_id . '_element1" type="text" value="' . $range_value[1] . '" onChange="edit_range(this.value,' . $label_id . ',1)" size="8"/>';

    return $range;
  }

  /**
   * Params for type grading.
   *
   * @param string $element_value
   * @param string $label_id
   * @return array
   */
  public function params_for_type_grading( $element_value = '', $label_id = '' ) {
    $type_grading_array = array();
    $element_value1 = str_replace("***grading***", '', $element_value);
    $garding_value = explode(':', $element_value1);
    $items_count = sizeof($garding_value) - 1;
    $garding = '<div class="grading-inputs">';
    $sum = 0;
    for ( $k = 0; $k < $items_count / 2; $k++ ) {
      $garding .=  '<div><label for="' . $label_id . '_element' . $k . '">' . $garding_value[$items_count / 2 + $k] . '</label><input name="' . $label_id . '_element' . $k . '"  id="' . $label_id . '_element' . $k . '" type="text" value="' . $garding_value[$k] . '" onKeyUp="edit_grading(' . $label_id . ',' . $items_count . ')" size="5"/></div>';
	  $sum += (is_numeric($garding_value[$k])) ? $garding_value[$k] : 0;
    }
	$garding .='</div>';
    array_push($type_grading_array, $garding);
    array_push($type_grading_array, $garding_value);
    array_push($type_grading_array, $sum);
    array_push($type_grading_array, $items_count);
    array_push($type_grading_array, $element_value1);

    return $type_grading_array;
  }

  /**
   * Params for type matrix.
   *
   * @param string $element_value
   * @param string $label_id
   * @return array
   */
  public function params_for_type_matrix( $element_value = '', $label_id = '' ) {
    $type_matrix_array = array();
    $new_filename = str_replace("***matrix***", '', $element_value);
    $matrix_value = explode('***', $new_filename);
    $matrix_value = array_slice($matrix_value, 0, count($matrix_value) - 1);
    $mat_rows = $matrix_value[0];
    $mat_columns = $matrix_value[$mat_rows + 1];
    $matrix = "<table>";
    $matrix .= '<tr><td></td>';
    for ( $k = 1; $k <= $mat_columns; $k++ ) {
      $matrix .= '<td style="background-color:#BBBBBB; padding:5px; border:1px; ">' . $matrix_value[$mat_rows + 1 + $k] . '</td>';
    }
    $matrix .= '</tr>';
    $aaa = Array();
    $var_checkbox = 1;
    $selected_value = "";
    $selected_value_yes = "";
    $selected_value_no = "";
    for ( $k = 1; $k <= $mat_rows; $k++ ) {
      $matrix .= '<tr><td style="background-color:#BBBBBB; padding:5px; border:1px;">' . $matrix_value[$k] . '</td>';
      if ( $matrix_value[$mat_rows + $mat_columns + 2] == "radio" ) {
        if ( $matrix_value[$mat_rows + $mat_columns + 2 + $k] == 0 ) {
          $checked = "";
          $aaa[1] = "";
        }
        else {
          $aaa = explode("_", $matrix_value[$mat_rows + $mat_columns + 2 + $k]);
        }
        for ( $l = 1; $l <= $mat_columns; $l++ ) {
          if ( $aaa[1] == $l ) {
            $checked = 'checked';
          }
          else {
            $checked = "";
          }
          $index = "'" . $k . '_' . $l . "'";
          $matrix .= '<td style="text-align:center;"><input name="' . $label_id . '_input_elementform_id_temp' . $k . '"  id="' . $label_id . '_input_elementform_id_temp' . $k . '_' . $l . '" type="' . $matrix_value[$mat_rows + $mat_columns + 2] . '" ' . $checked . ' onClick="change_radio_values(' . $index . ',' . $label_id . ',' . $mat_rows . ',' . $mat_columns . ')"/></td>';
        }
      }
      else {
        if ( $matrix_value[$mat_rows + $mat_columns + 2] == "checkbox" ) {
          for ( $l = 1; $l <= $mat_columns; $l++ ) {
            if ( $matrix_value[$mat_rows + $mat_columns + 2 + $var_checkbox] == 1 ) {
              $checked = 'checked';
            }
            else {
              $checked = '';
            }
            $index = "'" . $k . '_' . $l . "'";
            $matrix .= '<td style="text-align:center;"><input name="' . $label_id . '_input_elementform_id_temp' . $k . '_' . $l . '"  id="' . $label_id . '_input_elementform_id_temp' . $k . '_' . $l . '" type="' . $matrix_value[$mat_rows + $mat_columns + 2] . '" ' . $checked . ' onClick="change_checkbox_values(' . $index . ',' . $label_id . ',' . $mat_rows . ',' . $mat_columns . ')"/></td>';
            $var_checkbox++;
          }
        }
        else {
          if ( $matrix_value[$mat_rows + $mat_columns + 2] == "text" ) {
            for ( $l = 1; $l <= $mat_columns; $l++ ) {
              $text_value = $matrix_value[$mat_rows + $mat_columns + 2 + $var_checkbox];
              $index = "'" . $k . '_' . $l . "'";
              $matrix .= '<td style="text-align:center;"><input name="' . $label_id . '_input_elementform_id_temp' . $k . '_' . $l . '"  id="' . $label_id . '_input_elementform_id_temp' . $k . '_' . $l . '" type="' . $matrix_value[$mat_rows + $mat_columns + 2] . '" value="' . $text_value . '" onKeyUp="change_text_values(' . $index . ',' . $label_id . ',' . $mat_rows . ',' . $mat_columns . ')"/></td>';
              $var_checkbox++;
            }
          }
          else {
            for ( $l = 1; $l <= $mat_columns; $l++ ) {
              $selected_text = $matrix_value[$mat_rows + $mat_columns + 2 + $var_checkbox];
              if ( $selected_text == 'yes' ) {
                $selected_value_yes = 'selected';
                $selected_value_no = '';
                $selected_value = '';
              }
              else {
                if ( $selected_text == 'no' ) {
                  $selected_value_yes = '';
                  $selected_value_no = 'selected';
                  $selected_value = '';
                }
                else {
                  $selected_value_yes = '';
                  $selected_value_no = '';
                  $selected_value = 'selected';
                }
              }
              $index = "'" . $k . '_' . $l . "'";
              $matrix .= '<td style="text-align:center;"><select name="' . $label_id . '_select_yes_noform_id_temp' . $k . '_' . $l . '"  id="' . $label_id . '_select_yes_noform_id_temp' . $k . '_' . $l . '" onChange="change_option_values(' . $index . ',' . $label_id . ',' . $mat_rows . ',' . $mat_columns . ')"><option value="" ' . $selected_value . '></option><option value="yes" ' . $selected_value_yes . ' >Yes</option><option value="no" ' . $selected_value_no . '>No</option></select></td>';
              $var_checkbox++;
            }
          }
        }
      }
      $matrix .= '</tr>';
    }
    $matrix .= '</table>';
    array_push($type_matrix_array, $matrix);
    array_push($type_matrix_array, $new_filename);

    return $type_matrix_array;
  }

  /**
   * select data from db for labels.
   *
   * @param string $db_info
   * @param string $label_column
   * @param string $table
   * @param string $where
   * @param string $order_by
   * @return mixed
   */
  public function select_data_from_db_for_labels( $db_info = '', $label_column = '', $table = '', $where = '', $order_by = '' ) {
    global $wpdb;
    $query = "SELECT `" . $label_column . "` FROM " . $table . $where . " ORDER BY " . $order_by;
    $db_info = trim($db_info, '[]');
    if ( $db_info ) {
      $temp = explode('@@@wdfhostwdf@@@', $db_info);
      $host = $temp[0];
      $temp = explode('@@@wdfportwdf@@@', $temp[1]);
      $port = $temp[0];
      if ($port) {
        $host .= ':' . $port;
      }
      $temp = explode('@@@wdfusernamewdf@@@', $temp[1]);
      $username = $temp[0];
      $temp = explode('@@@wdfpasswordwdf@@@', $temp[1]);
      $password = $temp[0];
      $temp = explode('@@@wdfdatabasewdf@@@', $temp[1]);
      $database = $temp[0];
      $wpdb_temp = new wpdb($username, $password, $database, $host);
      $choices_labels = $wpdb_temp->get_col($query);
    }
    else {
      $choices_labels = $wpdb->get_col($query);
    }

    return $choices_labels;
  }

  /**
   * Select data from db for values.
   *
   * @param string $db_info
   * @param string $value_column
   * @param string $table
   * @param string $where
   * @param string $order_by
   * @return mixed
   */
  public function select_data_from_db_for_values( $db_info = '', $value_column = '', $table = '', $where = '', $order_by = '' ) {
    global $wpdb;
    $query = "SELECT `" . $value_column . "` FROM " . $table . $where . " ORDER BY " . $order_by;
    $db_info = trim($db_info, '[]');
    if ( $db_info ) {
      $temp = explode('@@@wdfhostwdf@@@', $db_info);
      $host = $temp[0];
      $temp = explode('@@@wdfportwdf@@@', $temp[1]);
      $port = $temp[0];
      if ($port) {
        $host .= ':' . $port;
      }
      $temp = explode('@@@wdfusernamewdf@@@', $temp[1]);
      $username = $temp[0];
      $temp = explode('@@@wdfpasswordwdf@@@', $temp[1]);
      $password = $temp[0];
      $temp = explode('@@@wdfdatabasewdf@@@', $temp[1]);
      $database = $temp[0];
      $wpdb_temp = new wpdb($username, $password, $database, $host);
      $choices_values = $wpdb_temp->get_col($query);
    }
    else {
      $choices_values = $wpdb->get_col($query);
    }

    return $choices_values;
  }

  /**
   * Delete row.
   * @param int $id
   * @return mixed
   */
  public function delete_row( $id = 0 ) {
    global $wpdb;
    $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'formmaker_submits WHERE group_id=%d', $id);

    return $wpdb->query($query);
  }

  /**
   * Delete from session.
   *
   * @param int $form_id
   * @param int $id
   * @return mixed
   */
  public function delete_from_session( $form_id = 0, $id = 0 ) {
    global $wpdb;
    $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'formmaker_sessions WHERE form_id=%d AND group_id=%d', $form_id, $id);

    return $wpdb->query($query);
  }

  /**
   * Delete rows.
   * @param array $params
   * @return mixed
   */
  public function delete_rows( $params = array() ) {
    global $wpdb;
    $query = 'DELETE FROM ' . $wpdb->prefix . 'formmaker_submits WHERE group_id IN ( ' . $params . ' )';

    return $wpdb->query($query);
  }

  /**
   * Delete rows from session.
   *
   * @param int $form_id
   * @param string $cids
   * @return mixed
   */
  public function delete_rows_from_session( $form_id = 0, $cids = '' ) {
    global $wpdb;
    $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'formmaker_sessions WHERE form_id=%d AND group_id IN ( %s )', $form_id, $cids);

    return $wpdb->query($query);
  }

  /**
   * Get rows
   *
   * @params array $cids
   *
   * @return array
   */
  public function get_rows( $cids = array() ) {
    global $wpdb;
    $query = 'SELECT * FROM ' . $wpdb->prefix . 'formmaker_submits WHERE group_id IN ( ' . $cids . ' )';

    return $wpdb->get_results($query);
  }

  /**
   * Get ip
   *
   * @params string $ip
   *
   * @return string
   */
  public function get_ips( $ip = '' ) {
    global $wpdb;
	$q = $wpdb->prepare('SELECT ip FROM ' . $wpdb->prefix . 'formmaker_blocked WHERE ip=%s', $ip);
    return $wpdb->get_var($q);
  }

  /**
   * Insert ip
   *
   * @params array $params_set
   * @params array $params_type
   */
  public function set_ips( $params_set = array(), $params_type = array() ) {
    global $wpdb;
    return $wpdb->insert($wpdb->prefix . 'formmaker_blocked', $params_set, $params_type);
  }

  /**
   * Delete row by ip
   *
   * @params string $ips
   *
   * @return bool
   */
  public function delete_by_ip( $ip = '' ) {
    global $wpdb;
    return $wpdb->query( $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'formmaker_blocked WHERE ip=%s', $ip) );
  }

  /**
   * Get col.
   *
   * @param int $id
   * @return mixed
   */
  public function get_col( $id = 0 ) {
    global $wpdb;
    return $wpdb->get_var("SELECT form_id FROM " . $wpdb->prefix . "formmaker_submits WHERE group_id='" . $id . "'");
  }

  /**
   * Get id.
   *
   * @param $id
   * @param $label_id
   * @return mixed
   */
  public function get_id( $id = 0, $label_id = '' ) {
    global $wpdb;
    $query = "SELECT id FROM " . $wpdb->prefix . "formmaker_submits WHERE group_id='" . $id . "' AND element_label='" . $label_id . "'";

    return $wpdb->get_var($query);
  }

  /**
   * Update formmaker_submits
   *
   * @params array $params_set
   * @params array $params_where
   * @params array $params_set_type
   * @params array $params_where_type
   *
   * @return bool
   */
  public function update_fm_submits( $params_set = array(), $params_where = array(), $params_set_type = array(), $params_where_type = array() ) {
    global $wpdb;

    return $wpdb->update($wpdb->prefix . "formmaker_submits", $params_set, $params_where, $params_set_type, $params_where_type);
  }

  /**
   * insert to formmaker_submits
   *
   * @params array $params_set
   * @params array $params_set_type
   *
   * @return bool
   */
  public function insert_fm_submits( $params_set = array(), $params_set_type = array() ) {
    global $wpdb;

    return $wpdb->insert($wpdb->prefix . "formmaker_submits", $params_set, $params_set_type);
  }

  /**
   * Get all.
   *
   * @param int $id
   * @return mixed
   */
  public function get_all( $id = 0 ) {
    global $wpdb;
    $row = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "formmaker WHERE id='" . $id . "'");
    $row = WDW_FM_Library::convert_json_options_to_old( $row, 'form_options');
    return $row;
  }
}
