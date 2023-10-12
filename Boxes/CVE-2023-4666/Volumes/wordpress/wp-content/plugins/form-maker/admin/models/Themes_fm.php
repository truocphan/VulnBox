<?php

class FMModelThemes_fm extends FMAdminModel {
  /**
   * Get rows data.
   *
   * @param  $params
   *
   * @return array $rows
   */
  public function get_rows_data( $params = array() ) {
    global $wpdb;
    $order = $params['order'];
    $orderby = $params['orderby'];
    $page_per = $params['items_per_page'];
    $page_num = $params['page_num'];
    $search = $params['search'];
    $query = 'SELECT * FROM `' . $wpdb->prefix . 'formmaker_themes` AS `t`';
    if ( $search ) {
      $query .= $wpdb->prepare('WHERE `t`.`title` LIKE %s', '%' . $search . '%');
    }
    $query .= ' ORDER BY `t`.`' . $orderby . '` ' . $order;
    $query .= ' LIMIT ' . $page_num . ',' . $page_per;
    $rows = $wpdb->get_results($query);

    return $rows;
  }

  /**
   * Get row data.
   *
   * @param int $id
   * @param $reset
   * @return stdClass
   */
  public function get_row_data( $id = 0 , $reset = false ) {
    global $wpdb;
    if ( $id != 0 ) {
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'formmaker_themes WHERE id=%d', $id));
      if ( $reset ) {
        if ( !$row->default ) {
          $row_id = $row->id;
          $row_title = $row->title;
          $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'formmaker_themes WHERE default=%d', 1));
          $row->id = $row_id;
          $row->title = $row_title;
          $row->default = FALSE;
          $row->version = 2;
        }
        else {
          $row->css = '';
        }
      }
    }
    else {
      $row = new stdClass();
      $row->id = 0;
      $row->title = '';
      $row->css = '{"GPFontFamily":"tahoma","AGPWidth":"100","AGPSPWidth":"30","AGPPadding":"","AGPMargin":"0 auto","AGPBorderColor":"#ffffff","AGPBorderType":"solid","AGPBorderWidth":"1","AGPBorderRadius":"0","AGPBoxShadow":"","HPAlign":"top","HPBGColor":"#96afab","HPWidth":"100","HTPWidth":"40","HPPadding":"10px 0","HPMargin":"","HPTextAlign":"center","HPBorderColor":"#b7b7b7","HPBorderType":"solid","HPBorderWidth":"1","HPBorderRadius":"0","HTPFontSize":"24","HTPWeight":"normal","HTPColor":"#ffffff","HDPFontSize":"15","HDPColor":"#607370","HIPAlign":"top","HIPWidth":"80","HIPHeight":"","GPBGColor":"#ededed","GPFontSize":"16","GPFontWeight":"normal","GPWidth":"100","GTPWidth":"60","GPAlign":"center","GPBackground":"","GPBackgroundRepeat":"no-repeat","GPBGPosition1":"","GPBGPosition2":"","GPBGSize1":"","GPBGSize2":"","GPColor":"#607370","GPPadding":"10px","GPMargin":"","GPBorderColor":"#ffffff","GPBorderType":"solid","GPBorderWidth":"1","GPBorderRadius":"0","GPMLFontSize":"14","GPMLFontWeight":"normal","GPMLColor":"#868686","GPMLPadding":"0px 5px 0px 0px","GPMLMargin":"0px","SEPBGColor":"#ededed","SEPPadding":"","SEPMargin":"","COPPadding":"15px 20px","COPMargin":"0px","FPWidth":"70","FPPadding":"15px 0 0 0","FPMargin":"0 auto","IPHeight":"26","IPFontSize":"14","IPFontWeight":"normal","IPBGColor":"#ffffff","IPColor":"#868686","IPPadding":"0px 5px","IPMargin":"0px","IPBorderTop":"top","IPBorderRight":"right","IPBorderBottom":"bottom","IPBorderLeft":"left","IPBorderColor":"#dfdfdf","IPBorderType":"solid","IPBorderWidth":"1","IPBorderRadius":"0","IPBoxShadow":"","SBPAppearance":"none","SBPBackground":"images/themes/drop-downs/2.png","SBPBGRepeat":"no-repeat","SBPBGPos1":"95%","SBPBGPos2":"50%","SBPBGSize1":"8%","SBPBGSize2":"32%","SCPBGColor":"#ffffff","SCPWidth":"16","SCPHeight":"16","SCPBorderTop":"top","SCPBorderRight":"right","SCPBorderBottom":"bottom","SCPBorderLeft":"left","SCPBorderColor":"#868686","SCPBorderType":"solid","SCPBorderWidth":"1","SCPMargin":"0px 3px","SCPBorderRadius":"15","SCPBoxShadow":"","SCCPBGColor":"#868686","SCCPWidth":"6","SCCPHeight":"6","SCCPMargin":"5","SCCPBorderRadius":"10","MCPBGColor":"#ffffff","MCPWidth":"16","MCPHeight":"16","MCPBorderTop":"top","MCPBorderRight":"right","MCPBorderBottom":"bottom","MCPBorderLeft":"left","MCPBorderColor":"#868686","MCPBorderType":"solid","MCPBorderWidth":"1","MCPMargin":"0px 3px","MCPBorderRadius":"0","MCPBoxShadow":"","MCCPBGColor":"","MCCPBackground":"images/themes/checkboxes/1.png","MCCPBGRepeat":"no-repeat","MCCPBGPos1":"","MCCPBGPos2":"","MCCPWidth":"16","MCCPHeight":"16","MCCPMargin":"0","MCCPBorderRadius":"0","SPAlign":"left","SPBGColor":"#e74c3c","SPWidth":"","SPHeight":"","SPFontSize":"16","SPFontWeight":"normal","SPColor":"#ffffff","SPPadding":"5px 8px","SPMargin":"0 15px 0 0","SPBorderTop":"top","SPBorderRight":"right","SPBorderBottom":"bottom","SPBorderLeft":"left","SPBorderColor":"#e74c3c","SPBorderType":"solid","SPBorderWidth":"1","SPBorderRadius":"0","SPBoxShadow":"","SHPBGColor":"#701e16","SHPColor":"#ffffff","SHPBorderTop":"top","SHPBorderRight":"right","SHPBorderBottom":"bottom","SHPBorderLeft":"left","SHPBorderColor":"#701e16","SHPBorderType":"solid","SHPBorderWidth":"1","BPBGColor":"#96afab","BPWidth":"","BPHeight":"","BPFontSize":"16","BPFontWeight":"normal","BPColor":"#ffffff","BPPadding":"5px 8px","BPMargin":"0 15px 0 0","BPBorderTop":"top","BPBorderRight":"right","BPBorderBottom":"bottom","BPBorderLeft":"left","BPBorderColor":"#8a8a8a","BPBorderType":"solid","BPBorderWidth":"1","BPBorderRadius":"0","BPBoxShadow":"","BHPBGColor":"#5a7784","BHPColor":"#ffffff","BHPBorderTop":"top","BHPBorderRight":"right","BHPBorderBottom":"bottom","BHPBorderLeft":"left","BHPBorderColor":"#5a7784","BHPBorderType":"solid","BHPBorderWidth":"1","PSAPBGColor":"#e74c3c","PSAPFontSize":"16","PSAPFontWeight":"normal","PSAPColor":"#ffffff","PSAPHeight":"","PSAPLineHeight":"","PSAPPadding":"6px","PSAPMargin":"0 1px 0 0 ","PSAPBorderTop":"top","PSAPBorderRight":"right","PSAPBorderBottom":"bottom","PSAPBorderLeft":"left","PSAPBorderColor":"#e74c3c","PSAPBorderType":"solid","PSAPBorderWidth":"2","PSAPBorderRadius":"0","PSDPBGColor":"#ededed","PSDPFontSize":"14","PSDPFontWeight":"normal","PSDPColor":"#737373","PSDPHeight":"","PSDPLineHeight":"","PSDPPadding":"3px 5px","PSDPMargin":"0 1px 0 0 ","PSDPBorderTop":"top","PSDPBorderRight":"right","PSDPBorderBottom":"bottom","PSDPBorderLeft":"left","PSDPBorderColor":"#ededed","PSDPBorderType":"solid","PSDPBorderWidth":"2","PSDPBorderRadius":"0","PSAPAlign":"right","PSAPWidth":"","PPAPWidth":"100%","NBPBGColor":"","NBPWidth":"","NBPHeight":"","NBPLineHeight":"","NBPColor":"#607370","NBPPadding":"4px 10px","NBPMargin":"0px","NBPBorderColor":"#777777","NBPBorderType":"solid","NBPBorderWidth":"1","NBPBorderRadius":"0","NBPBoxShadow":"","NBHPBGColor":"","NBHPColor":"#96afab","NBHPBorderColor":"#787878","NBHPBorderType":"solid","NBHPBorderWidth":"1","PBPBGColor":"","PBPWidth":"100","PBPHeight":"","PBPLineHeight":"","PBPColor":"#607370","PBPPadding":"","PBPMargin":"0px","PBPBorderColor":"#777777","PBPBorderType":"solid","PBPBorderWidth":"1","PBPBorderRadius":"0","PBPBoxShadow":"","PBHPBGColor":"","PBHPColor":"#96afab","PBHPBorderColor":"#787878","PBHPBorderType":"solid","PBHPBorderWidth":"1","CBPPosition":"absolute","CBPTop":"10px","CBPRight":"10px","CBPBottom":"","CBPLeft":"","CBPBGColor":"","CBPFontSize":"20","CBPFontWeight":"normal","CBPColor":"#777777","CBPPadding":"0px","CBPMargin":"0px","CBPBorderColor":"#ffffff","CBPBorderType":"solid","CBPBorderWidth":"1","CBPBorderRadius":"0","CBHPBGColor":"","CBHPColor":"#e74c3c","CBHPBorderColor":"#737373","CBHPBorderType":"solid","CBHPBorderWidth":"1","MBPBGColor":"#96afab","MBPFontSize":"17","MBPFontWeight":"normal","MBPColor":"#ffffff","MBPTextAlign":"center","MBPPadding":"10px","MBPMargin":"","MBPBorderTop":"top","MBPBorderRight":"right","MBPBorderBottom":"bottom","MBPBorderLeft":"left","MBPBorderColor":"#96afab","MBPBorderType":"solid","MBPBorderWidth":"2","MBPBorderRadius":"0","MBHPBGColor":"#96afab","MBHPColor":"#607370","MBHPBorderTop":"top","MBHPBorderRight":"right","MBHPBorderBottom":"bottom","MBHPBorderLeft":"left","MBHPBorderColor":"#96afab","MBHPBorderType":"solid","MBHPBorderWidth":"2","OPDeInputColor":"#afafaf","OPFontStyle":"normal","OPRColor":"","OPFBgUrl":"images/themes/file-uploads/2.png","OPFBGRepeat":"no-repeat","OPFPos1":"0%","OPFPos2":"10%","OPGWidth":"100","CUPCSS":""}';
      $row->default = 0;
      $row->version = 2;
    }
    if ( $row !== NULL ) {
      $params_decoded = json_decode(html_entity_decode($row->css));
      if ( $params_decoded != NULL ) {
        $row->css = $params_decoded;
      }
      else {
        $row->css = array( "CUPCSS" => $row->css );
      }
    }
    return $row;
  }

  /**
   *  Return total count of themes.
   *
   * @param string $search
   *
   * @return null|string
   */
  public function total( $search = '' ) {
    global $wpdb;
    $query = 'SELECT COUNT(*) FROM `' . $wpdb->prefix . 'formmaker_themes`';
    if ( $search ) {
      $query .= $wpdb->prepare('WHERE `title` LIKE %s', '%' . $search . '%');
    }
    $total = $wpdb->get_var($query);

    return $total;
  }

  /**
   * Delete row(s) from db.
   *
   * @param array $params
   * params = [selection, table, where, order_by, limit]
   *
   * @return array
   */
  public function delete_rows( $params = array() ) {
    global $wpdb;
    $query = 'DELETE FROM ' . $wpdb->prefix . $params['table'];
    if ( isset($params['where']) ) {
      $where = $params['where'];
      $query .= ' WHERE ' . $where;
    }
    if ( isset($params['order_by']) ) {
      $query .= ' ' . $params['order_by'];
    }
    if ( isset($params['limit']) ) {
      $query .= ' ' . $params['limit'];
    }

    return $wpdb->query($query);
  }

  /**
   * Get row(s) from db.
   *
   * @param string $get_type
   * @param array  $params
   * params = [selection, table, where, order_by, limit]
   *
   * @return array
   */
  public function select_rows( $get_type = '', $params = array() ) {
    global $wpdb;
    $query = "SELECT " . $params['selection'] . " FROM " . $wpdb->prefix . $params['table'];
    if ( isset($params['where']) ) {
      $query .= " WHERE " . $params['where'];
    }
    if ( isset($params['order_by']) ) {
      $query .= " " . $params['order_by'];
    }
    if ( isset($params['limit']) ) {
      $query .= " " . $params['limit'];
    }
    if ( $get_type == "get_col" ) {
      return $wpdb->get_col($query);
    }
    elseif ( $get_type == "get_var" ) {
      return $wpdb->get_var($query);
    }

    return $wpdb->get_row($query);
  }

  /**
   * Get request value.
   *
   * @param string $table
   * @param array  $data
   *
   * @return array
   */
  public function insert_data_to_db( $table = '', $data = array() ) {
    global $wpdb;
    $query = $wpdb->insert($wpdb->prefix . $table, $data);
    $wpdb->show_errors();

    return $query;
  }

  /**
   * @params array $params
   *
   * @param array $params
   * @return bool
   */
  public function insert_theme( $params = array() ) {
    global $wpdb;

    $wpdb->insert($wpdb->prefix . 'formmaker_themes', $params);
    return $wpdb->insert_id;
  }

  /**
   * Updating formmaker theme
   *
   * @params array $params
   * @params array $where
   *
   * @return bool
   */
  public function update_formmaker_themes( $params = array(), $where = array() ) {
    global $wpdb;
    return $wpdb->update($wpdb->prefix . 'formmaker_themes', $params, $where);
  }

  /**
   * Getting version of themes
   *
   * @params int $id
   *
   * @return string
   */
  public function get_theme_version( $id = 0 ) {
    global $wpdb;
    return $wpdb->get_var($wpdb->prepare("SELECT version FROM " . $wpdb->prefix . "formmaker_themes WHERE id=%d", $id));
  }

  /**
   * Getting default column
   *
   * @params int $id
   *
   * @return string
   */
  public function get_default( $id = 0 ) {
    global $wpdb;
    return $wpdb->get_var($wpdb->prepare('SELECT `default` FROM ' . $wpdb->prefix . 'formmaker_themes WHERE id=%d', $id));
  }
}
