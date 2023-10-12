<?php
class FMViewThemes_fm extends FMAdminView {
  /**
   * FMViewThemes_fm constructor.
   */
  public function __construct() {
    $fm_settings = WDFMInstance(self::PLUGIN)->fm_settings;
    if ( $fm_settings['fm_developer_mode'] ) {
      wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-tables');
      wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-admin');
    }
    else {
      if ( WDW_FM_Library(self::PLUGIN)->get('task','','sanitize_text_field') != 'edit' ) {
        wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-styles');
        wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-scripts');
      }
    }
  }

  /**
   * Display page.
   *
   * @param $params
   */
  public function display( $params = array() ) {
    ob_start();
    echo $this->body($params);
    // Pass the content to form.
    $form_attr = array(
      'id' => WDFMInstance(self::PLUGIN)->prefix . '_themes',
      'name' => WDFMInstance(self::PLUGIN)->prefix . '_themes',
      'class' => WDFMInstance(self::PLUGIN)->prefix . '_themes wd-form',
      'action' => add_query_arg(array( 'page' => 'themes' . WDFMInstance(self::PLUGIN)->menu_postfix ), 'admin.php'),
    );
    echo $this->form(ob_get_clean(), $form_attr);
  }

  /**
   * Generate page body.
   *
   * @param $params
   * @return string Body html.
   */
  public function body( $params = array() ) {
    $order = $params['order'];
    $orderby = $params['orderby'];
    $actions = $params['actions'];
    $page = $params['page'];
    $total = $params['total'];
    $items_per_page = $params['items_per_page'];
    $rows_data = $params['rows_data'];
    $page_url = add_query_arg(array(
                                'page' => $page,
                                WDFMInstance(self::PLUGIN)->nonce => wp_create_nonce(WDFMInstance(self::PLUGIN)->nonce),
                              ), admin_url('admin.php'));
    echo $this->title(array(
                        'title' => $params['page_title'],
                        'title_class' => 'wd-header',
                        'add_new_button' => array(
                        'href' => add_query_arg(array( 'page' => $page, 'task' => 'edit' ), admin_url('admin.php')),
                      ),
                    ));
    echo $this->search();
    ?>
    <div class="tablenav top">
      <?php
      echo $this->bulk_actions($actions);
      echo $this->pagination($page_url, $total, $items_per_page);
      ?>
    </div>
    <table class="adminlist table table-striped wp-list-table widefat fixed pages">
      <thead>
        <tr>
          <td id="cb" class="column-cb check-column">
            <label class="screen-reader-text" for="cb-select-all-1"><?php _e('Select all', WDFMInstance(self::PLUGIN)->prefix); ?></label>
            <input id="check_all" type="checkbox" />
          </td>
          <?php echo WDW_FM_Library(self::PLUGIN)->ordering('title', $orderby, $order, __('Title', WDFMInstance(self::PLUGIN)->prefix), $page_url, 'column-primary'); ?>
          <?php echo WDW_FM_Library(self::PLUGIN)->ordering('default', $orderby, $order, __('Default', WDFMInstance(self::PLUGIN)->prefix), $page_url, 'column-primary'); ?>
        </tr>
      </thead>
      <tbody>
      <?php
      if ( $rows_data ) {
        foreach ( $rows_data as $row_data ) {
          $alternate = (!isset($alternate) || $alternate == '') ? 'class="alternate"' : '';

          $edit_url = add_query_arg(array( 'page' => $page, 'task' => 'edit', 'current_id' => $row_data->id ), admin_url('admin.php'));
          $duplicate_url = add_query_arg(array( 'task' => 'duplicate', 'current_id' => $row_data->id ), $page_url);
          $delete_url = add_query_arg(array( 'task' => 'delete', 'current_id' => $row_data->id ), $page_url);
          $default_url = add_query_arg(array( 'task' => 'setdefault', 'current_id' => $row_data->id ), $page_url);
          ?>
          <tr id="tr_<?php echo $row_data->id; ?>" <?php echo $alternate; ?>>
            <th class="check-column">
              <input id="check_<?php echo $row_data->id; ?>" name="check[<?php echo $row_data->id; ?>]" type="checkbox" />
            </th>
            <td class="column-primary" data-colname="<?php _e('Title', WDFMInstance(self::PLUGIN)->prefix); ?>">
              <strong>
                <a href="<?php echo $edit_url; ?>"><?php echo $row_data->title; ?></a>
              </strong>
              <div class="row-actions">
                <span><a href="<?php echo $edit_url; ?>"><?php _e('Edit', WDFMInstance(self::PLUGIN)->prefix); ?></a> |</span>
                <span><a href="<?php echo $duplicate_url; ?>"><?php _e('Duplicate', WDFMInstance(self::PLUGIN)->prefix); ?></a> |</span>
                <span class="trash"><a onclick="if (!confirm('<?php echo addslashes(__('Do you want to delete selected item?', WDFMInstance(self::PLUGIN)->prefix)); ?>')) {return false;}" href="<?php echo $delete_url; ?>"><?php _e('Delete', WDFMInstance(self::PLUGIN)->prefix); ?></a></span>
              </div>
              <button class="toggle-row" type="button">
                <span class="screen-reader-text"><?php _e('Show more details', WDFMInstance(self::PLUGIN)->prefix); ?></span>
              </button>
            </td>
            <td class="col_default" data-colname="<?php _e('Default', WDFMInstance(self::PLUGIN)->prefix); ?>">
              <?php
              $default = ($row_data->default) ? 1 : 0;
              $default_image = ($row_data->default) ? 'default' : 'notdefault';
              if (!$default) {
                ?>
              <a href="<?php echo $default_url ?>">
                <?php
              }
              ?>
                <img src="<?php echo WDFMInstance(self::PLUGIN)->plugin_url . '/images/' . $default_image . '.png?ver=' . WDFMInstance(self::PLUGIN)->plugin_version . ''; ?>" />
              <?php
              if ($default) {
                ?>
              </a>
                <?php
              }
              ?>
            </td>
          </tr>
          <?php
        }
      }
      else {
        echo WDW_FM_Library(self::PLUGIN)->no_items('themes');
      }
      ?>
      </tbody>
    </table>
    <?php
  }

  /**
   * Edit page.
   *
   * @param $params
   */
  public function edit( $params = array() ) {
    ob_start();
    $buttons = array(
      'save' => array(
        'title' => __('Save', WDFMInstance(self::PLUGIN)->prefix),
        'value' => 'save',
        'onclick' => 'if (fm_check_required(\'title\', \'' . __('Title', WDFMInstance(self::PLUGIN)->prefix) . '\') || !fm_theme_submit_button(\'' . WDFMInstance(self::PLUGIN)->prefix . '_themes\', ' . $params['row']->version . ')) {return false;}; fm_set_input_value(\'task\', \'apply\');',
        'class' => 'button-primary',
      ),
    );
    echo $this->buttons($buttons);
    echo $this->title(array(
      'title' => __('Title: ', WDFMInstance(self::PLUGIN)->prefix),
      'title_name' => 'title',
      'title_id' => 'title',
      'title_value' => $params['page_title'],
      'title_class' => 'wd-header',
      'add_new_button' => FALSE,
    ));
    echo $this->edit_body($params);
    // Pass the content to form.
    $form_attr = array(
      'id' => WDFMInstance(self::PLUGIN)->prefix . '_themes',
      'current_id' => $params['id'],
      'name' => WDFMInstance(self::PLUGIN)->prefix . '_themes',
      'class' => WDFMInstance(self::PLUGIN)->prefix . '_themes wd-form',
      'action' => add_query_arg(array( 'page' => 'themes' . WDFMInstance(self::PLUGIN)->menu_postfix ), 'admin.php'),
    );
    echo $this->form(ob_get_clean(), $form_attr);
  }

  /**
   * Generate edit page body.
   *
   * @param $params
   * @return string edit body html.
   */
	public function edit_body( $params = array() ) {
		$fm_settings = WDFMInstance(self::PLUGIN)->fm_settings;
		if ( $fm_settings['fm_developer_mode'] ) {
			wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-bootstrap');
			wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-tables');
			wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-colorpicker');
			wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-ng-js');
			wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-colorpicker');
			wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-themes');
			wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-theme-edit-ng');
		}
		else {
			wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-theme-edit');
			wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-ng-js');
			wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-theme-edit');
			wp_enqueue_script(WDFMInstance(self::PLUGIN)->handle_prefix . '-theme-edit-ng');
		}

		$row = $params['row'];
		$param_values = $params['param_values'];
		$fonts = $params['fonts'];

		wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-googlefonts', 'https://fonts.googleapis.com/css?family=' . $fonts . '&subset=greek,latin,greek-ext,vietnamese,cyrillic-ext,latin-ext,cyrillic&display=swap', null, null);

		$tabs = $params['tabs'];
		$all_params = $params['all_params'];

		$active_tab = WDW_FM_Library(self::PLUGIN)->get('active_tab', ($row->version == 1 ? 'custom_css' : 'global'), 'sanitize_text_field');
		$pagination = WDW_FM_Library(self::PLUGIN)->get('pagination', 'none', 'sanitize_text_field');
		?>
		<div ng-app="ThemeParams" class="fm-table">
			<div ng-controller="FMTheme">
			<input type="hidden" id="params" name="params" value="" />
			<input type="hidden" id="default" name="default" value="<?php echo $row->default; ?>" />
			<input type="hidden" name="active_tab" id="active_tab" value="<?php echo $active_tab; ?>" />
			<input type="hidden" name="version" id="version" value="<?php echo rand(); ?>" />
			<script>
				var DefaultVar = <?php echo ($row->default) ? 0 : 1; ?>
			</script>
			<div class="fm-themes fm-mailchimp container-fluid">
			  <div class="row">
				<div class="col-md-6 col-sm-5">
				  <div class="fm-themes-tabs col-md-12">
					<ul>
					  <?php
					  foreach($tabs as $tkey => $tab) {
						$active_class = $active_tab == $tkey ? "fm-theme-active-tab" : "";
							echo '<li><a id="'.$tkey.'" href="#" class="button '.$active_class . ($row->version == 1 && $tkey != 'custom_css' ? ' fm-disabled' : '') . '">'.$tab.'</a></li>';
					  }
					  ?>
					</ul>
					<div class="fm-clear"></div>
					<div class="fm-themes-tabs-container">
					  <?php
					  $k = 0;
					  foreach($all_params as $pkey => $params) {
						$show_hide_class = $active_tab == $pkey ? '' : 'fm-hide';
						echo '<div id="'.$pkey.'-content" class="fm-themes-container '.$show_hide_class.'">';
						if ($row->version == 1 && $pkey == 'custom_css') {
						  echo '<div class="error inline"><p>' . __('This theme is outdated. Theme Options are only available in new themes provided by Form Maker. You can use Custom CSS panel to edit form styling, or alternatively select a new theme for your form.', WDFMInstance(self::PLUGIN)->prefix) . '</p></div>';
						}
						  foreach($params as $param){
							if($param["type"] == 'panel') {
							  echo '<div class="'.$param["class"].'">';
							}
							if($param["type"] != 'panel' || ($param["type"] == 'panel' && $param["label"]) )
							  echo '<div class="fm-row">';
							if($param["type"] == 'panel' && $param["label"]) {
							  echo '<label class="'.$param["label_class"].'" >'.$param["label"].'</label>'.$param["after"];
							} else {
							  if($param["type"] == 'text') {
									echo '<label class="fm-label-text">'.$param["label"].'</label>
											<div class="fm-input-text-wrap fm-input-text-wrap-'.$param["name"].'">
												<input type="'.$param["type"].'" name="'.$param["name"].'" class="'.$param["class"].'" ng-model="'.$param["name"].'" ng-init="'.$param["name"].'=\''.$param["value"].'\'" value="' . $param["value"] . '" placeholder="'. (isset($param["placeholder"]) ? $param["placeholder"] : "") .'"  title="'.(isset($param["placeholder"]) ? $param["placeholder"] : "").'" />'. $param["after"] .
											'</div>';
							  }
							  else {
								if($param["type"] == '2text') {
								  echo '<label class="fm-label-2text">'.$param["label"].'</label>
								  <div class="'.$param["class"].'" style="display:inline-block; vertical-align: middle;">
									<div style="float:left;display:table-row;">
									  <span style="display:table-cell;">'.$param["before1"].'</span><input type="text" name="'.$param["name1"].'" ng-model="'.$param["name1"].'" ng-init="'.$param["name1"].'=\''.$param["value1"].'\'" value="'.$param["value1"].'" placeholder="'.(isset($param["placeholder"]) ? $param["placeholder"] : "").'"  title="'.(isset($param["placeholder"]) ? $param["placeholder"] : "").'" style="display:table-cell; "/>'.$param["after"].'
									</div>
									<div style="float:left;display:table-row;">
									  <span style="display:table-cell;">'.$param["before2"].'</span><input type="text" name="'.$param["name2"].'" class="'.$param["class"].'" ng-model="'.$param["name2"].'" ng-init="'.$param["name2"].'=\''.$param["value2"].'\'" value="'.$param["value2"].'" placeholder="'.(isset($param["placeholder"]) ? $param["placeholder"] : "").'" title="'.(isset($param["placeholder"]) ? $param["placeholder"] : "").'" style="display:table-cell; "/>'.$param["after"].'
									</div>
								  </div>';
								}
								else {
								  if($param["type"] == 'select') {
										echo '<label class="fm-label-select">'.$param["label"].'</label>
												<div class="fm-select-wrap">
												<select name="'.$param["name"].'" ng-model="'.$param["name"].'" ng-init="'.$param["name"].'=\''.$param["value"].'\'">';
													foreach($param["options"] as $option_key => $option) {
														echo '<option value="'.$option_key.'">'.$option.'</option>';
													}
												echo '</select>'.$param["after"].
											'</div>';
								  } else {
									if($param["type"] == 'label') {
									  echo '<label class="'.$param["class"].'" >'.$param["label"].'</label>'.$param["after"];
									} else {
									  if($param["type"] == 'checkbox') {
										echo '<label>'.$param["label"].'</label>
										  <div class="fm-btn-group">';
										foreach($param["options"] as $op_key => $option){
										  if ( $op_key == '' ) { continue; }
										  $init = isset($param_values->{$param["name"].ucfirst($op_key)}) ? 'true' : 'false';
										  echo '<div class="fm-ch-button">
											  <input type="checkbox" id="'.$param["name"].ucfirst($op_key).'" name="'.$param["name"].ucfirst($op_key).'" value="'.$op_key.'" ng-model="'.$param["name"].ucfirst($op_key).'" ng-checked="'.$param["name"].ucfirst($op_key).'" ng-init="'.$param["name"].ucfirst($op_key).'='.$init.'"><label for="'.$param["name"].ucfirst($op_key).'">'.$option.'</label>
											</div>';
										}
										echo '</div>';
									  } else {
									    if($param["type"] == 'radio'){
                        echo '<div class="fm_shake_row"><label>'.$param["label"].'</label>
                        <div class="fm-btn-group">';
                            $checked = (!isset($param_values->{$param["name"]}) || (isset($param_values->{$param["name"]}) && $param_values->{$param["name"]} == 'yes')) ? true : false;
                            $html = '<div class="fm-ch-select">';
                            $html .= '<input type="radio" '.(($checked)?"checked":"") .' id="'.$param["name"].'Yes" name="'.$param["name"].'" value="yes">';
                            $html .= '<label for="'.$param["name"].'Yes">'.__('Yes', WDFMInstance(self::PLUGIN)->prefix).'</label>';
                            $html .= '<input type="radio" '.((!$checked)?"checked":"") .' id="'.$param["name"].'No" name="'.$param["name"].'" value="no">';
                            $html .= '<label for="'.$param["name"].'No">'.__('No', WDFMInstance(self::PLUGIN)->prefix).'</label>';
                            $html .= '</div>';
                            echo $html;
                        echo '</div></div>';

                      } else {
                        if ( $param["type"] == 'hidden' ) {
                          echo '<input type="' . $param["type"] . '" />' . $param["after"];
                        } else {
                          if ( $param["type"] == 'textarea' ) {
                            echo '<label>' . $param["label"] . '</label>
                                <textarea name="' . $param["name"] . '" rows="5"  columns="10" style="vertical-align:middle;">' . $param["value"] . '</textarea>';
                          }
                        }
                      }
									  }
                    }
								  }
								}
							  }
							}
							if($param["type"] != 'panel' || ($param["type"] == 'panel' && $param["label"]) )
							  echo '</div>';
						  }
						echo '</div>';
					  } ?>
					</div>
					</div>
				  </div>
				</div>
				<div class="fm-preview-form col-md-6 col-sm-7" style="display:none;">
				  <div class="form-example-preview fm-sidebar col-md-12">
					<p><?php _e('Preview', WDFMInstance(self::PLUGIN)->prefix); ?></p>
					<div class="fm-row">
					  <label><?php _e('Pagination Type: ', WDFMInstance(self::PLUGIN)->prefix); ?></label>
					  <div class="pagination-type" ng-init="pagination='<?php echo $pagination; ?>'">
						<input type="radio" id="step" name="pagination-type" value="step" ng-model="pagination"/>
						<label for="step"><?php _e('Step', WDFMInstance(self::PLUGIN)->prefix); ?></label>
						<input type="radio" id="percentage" name="pagination-type" value="percentage" ng-model="pagination" />
						<label for="percentage"><?php _e('Percentage', WDFMInstance(self::PLUGIN)->prefix); ?></label>
						<input type="radio" id="none" name="pagination-type" value="none" ng-model="pagination" />
						<label for="none"><?php _e('None', WDFMInstance(self::PLUGIN)->prefix); ?></label>
					  </div>
					</div>
				  <div class="fm-clear"></div>
				  <br />
				  <div class="fm-content">
					<div class="fm-form-example form-embedded">
					  <div class="fm-form-pagination">
						<div class="fm-pages-steps" ng-show="pagination == 'step'">
						  <span class="active-step" ng-class="{borderRight : PSAPBorderRight, borderLeft : PSAPBorderLeft, borderBottom : PSAPBorderBottom, borderTop : PSAPBorderTop}">1(active)</span>
						  <span class="deactive-step" ng-class="{borderRight : PSDPBorderRight, borderLeft : PSDPBorderLeft, borderBottom : PSDPBorderBottom, borderTop : PSDPBorderTop}">2</span>
						</div>
						<div class="fm-pages-percentage" ng-show="pagination == 'percentage'">
						  <div class="deactive-percentage" ng-class="{borderRight : PSDPBorderRight, borderLeft : PSDPBorderLeft, borderBottom : PSDPBorderBottom, borderTop : PSDPBorderTop}">
							<div class="active-percentage" ng-class="{borderRight : PSAPBorderRight, borderLeft : PSAPBorderLeft, borderBottom : PSAPBorderBottom, borderTop : PSAPBorderTop}" style="width: 50%;">
							  <b class="wdform_percentage_text">50%</b>
							</div>
							<div class="wdform_percentage_arrow">
							</div>
						  </div>
						</div>
						<div>
						</div>
					  </div>

					  <div class="fm-form" ng-class="{borderRight : AGPBorderRight, borderLeft : AGPBorderLeft, borderBottom : AGPBorderBottom, borderTop : AGPBorderTop}">
						<div ng-show="HPAlign != 'bottom' && HPAlign != 'right'" ng-class="{borderRight : HPBorderRight, borderLeft : HPBorderLeft, borderBottom : HPBorderBottom, borderTop : HPBorderTop, alignLeft : HPAlign == 'left'}" class="fm-form-header">
						  <div ng-show="HIPAlign != 'bottom' && HIPAlign != 'right'" ng-class="{imageRight : HIPAlign == 'right', imageLeft :  HIPAlign == 'left', imageBottom : HIPAlign == 'bottom', imageTop :  HIPAlign == 'top'}" class="himage">
							<img src="<?php echo WDFMInstance(self::PLUGIN)->plugin_url; ?>/images/preview_header.png" />
						  </div>
						  <div ng-class="{imageRight : HIPAlign == 'right', imageLeft :  HIPAlign == 'left', imageBottom : HIPAlign == 'bottom', imageTop :  HIPAlign == 'top'}" class="htext">
							<div class="htitle"><?php _e('Subscribe to our newsletter ', WDFMInstance(self::PLUGIN)->prefix); ?></div>
							<div class="hdescription"><?php _e('Join our mailing list to receive the latest news from our team.', WDFMInstance(self::PLUGIN)->prefix); ?></div>
						  </div>
						  <div ng-show="HIPAlign == 'bottom' || HIPAlign == 'right'" ng-class="{imageRight : HIPAlign == 'right', imageLeft :  HIPAlign == 'left', imageBottom : HIPAlign == 'bottom', imageTop :  HIPAlign == 'top'}" class="himage">
							<img src="<?php echo WDFMInstance(self::PLUGIN)->plugin_url; ?>/images/preview_header.png" />
						  </div>
						</div>
						<div class="fm-form-content" ng-class="{isBG : GPBackground != '', borderRight : GPBorderRight, borderLeft : GPBorderLeft, borderBottom : GPBorderBottom, borderTop : GPBorderTop, alignLeft : HPAlign == 'left' || HPAlign == 'right'}">
						  <div class="container-fluid">
							<div class="embedded-form">
							  <div class="fm-section fm-{{GPAlign}}">
								<div class="fm-column">
								  <div class="fm-row">
									<div type="type_submitter_mail" class="wdform-field">
									  <div class="wdform-label-section" style="float: left; width: 90px;"><span class="wdform-label"><?php _e('E-mail:', WDFMInstance(self::PLUGIN)->prefix); ?></span><span class="wdform-required">*</span>
									  </div>
									  <div class="wdform-element-section" style="width: 150px;">
										<input type="text" value="example@example.com" style="width: 100%;" ng-class="{borderRight : IPBorderRight, borderLeft : IPBorderLeft, borderBottom : IPBorderBottom, borderTop : IPBorderTop}" />
									  </div>
									</div>
								  </div>
								  <div class="fm-row">
									<div type="type_country" class="wdform-field">
									  <div class="wdform-label-section" style="float: left; width: 90px;">
										<span class="wdform-label"><?php _e('Country:', WDFMInstance(self::PLUGIN)->prefix); ?></span>
									  </div>
									  <div class="wdform-element-section wdform_select" style=" width: 150px;">
										<select style="width: 100%;" ng-class="{isBG : SBPBackground != '', borderRight : IPBorderRight, borderLeft : IPBorderLeft, borderBottom : IPBorderBottom, borderTop : IPBorderTop}">
										  <option value="Armenia"><?php _e('Armenia', WDFMInstance(self::PLUGIN)->prefix); ?></option>
										</select>
									  </div>
									</div>
								  </div>

								  <div class="fm-row">
									<div type="type_radio" class="wdform-field">
									  <div class="wdform-label-section" style="float: left; width: 90px;">
										<span class="wdform-label"><?php _e('Radio:', WDFMInstance(self::PLUGIN)->prefix); ?></span>
									  </div>
									  <div class="wdform-element-section " style="display:table;">
										<div style="display: table-row; vertical-align:top">
										  <div style="display: table-cell;">
											<div class="radio-div check-rad">
											  <input type="radio" id="em-rad-op-1" value="option 1" ng-hide="{{DefaultVar}}">
											  <label for="em-rad-op-1" class="mini_label">
												<span ng-class="{borderRight : SCPBorderRight, borderLeft : SCPBorderLeft, borderBottom : SCPBorderBottom, borderTop : SCPBorderTop}"></span><?php _e('option 1', WDFMInstance(self::PLUGIN)->prefix); ?>
											  </label>
											</div>
										  </div>
										</div>
									  </div>
									</div>
								  </div>
								  <div class="fm-row">
									<div type="type_checkbox" class="wdform-field">
									  <div class="wdform-label-section" style="float: left; width: 90px;">
										<span class="wdform-label"><?php _e('Checkbox:', WDFMInstance(self::PLUGIN)->prefix); ?></span>
									  </div>
									  <div class="wdform-element-section" style="display: table;">
										<div style="display: table-row; vertical-align:top">
										  <div style="display: table-cell;">
											<div class="checkbox-div forlabs" ng-class="{isBG : MCCPBackground != ''}">
											  <input type="checkbox" id="em-ch-op-1" value="option 1" ng-hide="{{DefaultVar}}">
											  <label for="em-ch-op-1" class="mini_label"><span ng-class="{borderRight : MCPBorderRight, borderLeft : MCPBorderLeft, borderBottom : MCPBorderBottom, borderTop : MCPBorderTop}"></span><?php _e('option 1', WDFMInstance(self::PLUGIN)->prefix); ?></label>
											</div>
										  </div>
										</div>
									  </div>
									</div>
								  </div>
								  <div class="fm-row">
									<div type="type_submit_reset" class="wdform-field subscribe-reset">
									  <div class="wdform-label-section" style="display: table-cell;"></div>
									  <div class="wdform-element-section" style="display: table-cell;">
										<button type="button" class="button button-large fm-button-subscribe" ng-class="{borderRight : SPBorderRight, borderLeft : SPBorderLeft, borderBottom : SPBorderBottom, borderTop : SPBorderTop, borderHoverRight : SHPBorderRight, borderHoverLeft : SHPBorderLeft, borderHoverBottom : SHPBorderBottom, borderHoverTop : SHPBorderTop}" ><?php _e('Submit', WDFMInstance(self::PLUGIN)->prefix); ?></button>
										<button type="button" class="button button-large fm-button-reset" ng-class="{borderRight : BPBorderRight, borderLeft : BPBorderLeft, borderBottom : BPBorderBottom, borderTop : BPBorderTop, borderHoverRight : BHPBorderRight, borderHoverLeft : BHPBorderLeft, borderHoverBottom : BHPBorderBottom, borderHoverTop : BHPBorderTop}"><?php _e('Reset', WDFMInstance(self::PLUGIN)->prefix); ?></button>
									  </div>
									</div>
								  </div>
								  <div class="fm-clear"></div>
								</div>

							  </div>
							  <div class="fm-close-icon" ng-class="{borderRight : CBPBorderRight, borderLeft : CBPBorderLeft, borderBottom : CBPBorderBottom, borderTop : CBPBorderTop, borderHoverRight : CBHPBorderRight, borderHoverLeft : CBHPBorderLeft, borderHoverBottom : CBHPBorderBottom, borderHoverTop : CBHPBorderTop}">
								<span class="fm-close fm-ico-delete" ng-class="{borderRight : CBPBorderRight, borderLeft : CBPBorderLeft, borderBottom : CBPBorderBottom, borderTop : CBPBorderTop, borderHoverRight : CBHPBorderRight, borderHoverLeft : CBHPBorderLeft, borderHoverBottom : CBHPBorderBottom, borderHoverTop : CBHPBorderTop}"></span>
							  </div>
							  <div class="fm-footer" ng-show="pagination != 'none'">
								<div style="width: 100%;">
								  <div style="width: 100%; display: table;">
									<div style="display: table-row-group;">
									  <div  style="display: table-row;">
										<div  class="fm-previous-page" style="display: table-cell; width: 45%;">
										  <div class="fm-wdform-page-button" ng-class="{borderRight : PBPBorderRight, borderLeft : PBPBorderLeft, borderBottom : PBPBorderBottom, borderTop : PBPBorderTop,  borderHoverRight : PBHPBorderRight, borderHoverLeft : PBHPBorderLeft, borderHoverBottom : PBHPBorderBottom, borderHoverTop : PBHPBorderTop}"><span class="dashicons dashicons-arrow-left-alt2"></span><?php _e('Previous', WDFMInstance(self::PLUGIN)->prefix); ?></div>
										</div>
										<div class="page-numbers text-center" style="display: table-cell;">
										  <span>2/3</span>
										</div>
										<div class="fm-next-page" style="display: table-cell; width: 45%; text-align: right;">
										  <div class="fm-wdform-page-button" ng-class="{borderRight : NBPBorderRight, borderLeft : NBPBorderLeft, borderBottom : NBPBorderBottom, borderTop : NBPBorderTop, borderHoverRight : NBHPBorderRight, borderHoverLeft : NBHPBorderLeft, borderHoverBottom : NBHPBorderBottom, borderHoverTop : NBHPBorderTop}"><?php _e('Next', WDFMInstance(self::PLUGIN)->prefix); ?><span class="dashicons dashicons-arrow-right-alt2"></span></div>
										</div>
									  </div>
									</div>
								  </div>
								</div>
							  </div>
							</div>
						  </div>
						</div>
						<div ng-show="HPAlign == 'bottom' || HPAlign == 'right'" ng-class="{borderRight : HPBorderRight, borderLeft : HPBorderLeft, borderBottom : HPBorderBottom, borderTop : HPBorderTop, alignLeft : HPAlign == 'right'}" class="fm-form-header">
						  <div ng-show="HIPAlign != 'bottom' && HIPAlign != 'right'" ng-class="{imageRight : HIPAlign == 'right', imageLeft :  HIPAlign == 'left', imageBottom : HIPAlign == 'bottom', imageTop :  HIPAlign == 'top'}" class="himage">
							<img src="<?php echo WDFMInstance(self::PLUGIN)->plugin_url; ?>/images/preview_header.png" />
						  </div>
						  <div ng-class="{imageRight : HIPAlign == 'right', imageLeft :  HIPAlign == 'left', imageBottom : HIPAlign == 'bottom', imageTop :  HIPAlign == 'top'}" class="htext">
							<div class="htitle"><?php _e('Subscribe to our newsletter', WDFMInstance(self::PLUGIN)->prefix); ?></div>
							<div class="hdescription"><?php _e('Join our mailing list to receive the latest news from our team.', WDFMInstance(self::PLUGIN)->prefix); ?></div>
						  </div>
						  <div ng-show="HIPAlign == 'bottom' || HIPAlign == 'right'" ng-class="{imageRight : HIPAlign == 'right', imageLeft :  HIPAlign == 'left', imageBottom : HIPAlign == 'bottom', imageTop :  HIPAlign == 'top'}" class="himage">
							<img src="<?php echo WDFMInstance(self::PLUGIN)->plugin_url; ?>/images/preview_header.png" />
						  </div>
						</div>
					  </div>
					</div>
					<div class="fm-clear"></div>
				  </div>
				  </div>
				</div>
			  </div>
                <style>
                    .fm-form {
						background-color:{{GPBGColor}} !important;
						font-family:{{GPFontFamily}} !important;
						width:{{AGPWidth}}% !important;
						padding:{{AGPPadding}} !important;
						margin:{{AGPMargin}} !important;
						border-radius:{{AGPBorderRadius}}px !important;
						box-shadow:{{AGPBoxShadow}} !important;
						position: relative !important;
                    }
                    .fm-form-header.alignLeft,
                    .fm-form-content.alignLeft{
						border-radius:{{AGPBorderRadius}}px !important;
                    }
                    .fm-form.borderRight{
						border-right:{{AGPBorderWidth}}px {{AGPBorderType}} {{AGPBorderColor}} !important;
                    }
                    .fm-form.borderLeft{
						border-left:{{AGPBorderWidth}}px {{AGPBorderType}} {{AGPBorderColor}} !important;
                    }
                    .fm-form.borderTop{
						border-top:{{AGPBorderWidth}}px {{AGPBorderType}} {{AGPBorderColor}} !important;
                    }
                    .fm-form.borderBottom{
						border-bottom:{{AGPBorderWidth}}px {{AGPBorderType}} {{AGPBorderColor}} !important;
                    }
                    .fm-form-content{
						font-size:{{GPFontSize}}px !important;
						font-weight:{{GPFontWeight}} !important;
						width:{{GPWidth}}% !important;
						color:{{GPColor}} !important;
						padding:{{GPPadding}} !important;
						margin:{{GPMargin}} !important;
						border-radius:{{GPBorderRadius}}px !important;
                    }
                    .fm-form-content.isBG{
						background:url(<?php echo WDFMInstance(self::PLUGIN)->plugin_url; ?>/{{GPBackground}}) {{GPBackgroundRepeat}} {{GPBGPosition1}} {{GPBGPosition2}} !important;
						background-size: {{GPBGSize1}} {{GPPBGSize2}} !important;
                    }
                    .fm-form-content.borderRight{
						border-right:{{GPBorderWidth}}px {{GPBorderType}} {{GPBorderColor}} !important;
                    }
                    .fm-form-content.borderLeft{
						border-left:{{GPBorderWidth}}px {{GPBorderType}} {{GPBorderColor}} !important;
                    }
                    .fm-form-content.borderTop{
						border-top:{{GPBorderWidth}}px {{GPBorderType}} {{GPBorderColor}} !important;
                    }
                    .fm-form-content.borderBottom{
						border-bottom:{{GPBorderWidth}}px {{GPBorderType}} {{GPBorderColor}} !important;
                    }
                    .fm-form-content label{
						font-size:{{GPFontSize}}px !important;
                    }
                    .fm-form-content .fm-section{
						background-color:{{SEPBGColor}} !important;
						padding:{{SEPPadding}} !important;
						margin:{{SEPMargin}} !important;
                    }
                    .fm-form-content .fm-column{
						padding:{{COPPadding}} !important;
						margin:{{COPMargin}} !important;
                    }
                    .fm-form-content input[type="text"],
                    .fm-form-content input[type="number"],
                    .fm-form-content select {
						font-size:{{IPFontSize}}px !important;
						font-weight:{{IPFontWeight}} !important;
						height:{{IPHeight}}px !important;
						line-height:{{IPHeight}}px !important;
						background-color:{{IPBGColor}} !important;
						color:{{IPColor}} !important;
						padding:{{IPPadding}} !important;
						margin:{{IPMargin}} !important;
						border-radius:{{IPBorderRadius}}px !important;
						box-shadow:{{IPBoxShadow}} !important;
                    }
                    .fm-form-content input[type="text"].borderRight,
                    .fm-form-content select.borderRight{
						border-right:{{IPBorderWidth}}px {{IPBorderType}} {{IPBorderColor}} !important;
                    }
                    .fm-form-content input[type="text"].borderLeft,
                    .fm-form-content select.borderLeft{
						border-left:{{IPBorderWidth}}px {{IPBorderType}} {{IPBorderColor}} !important;
                    }
                    .fm-form-content input[type="text"].borderTop,
                    .fm-form-content select.borderTop{
						border-top:{{IPBorderWidth}}px {{IPBorderType}} {{IPBorderColor}} !important;
                    }
                    .fm-form-content input[type="text"].borderBottom,
                    .fm-form-content select.borderBottom{
						border-bottom:{{IPBorderWidth}}px {{IPBorderType}} {{IPBorderColor}} !important;
                    }
                    .fm-form-content select{
						appearance: {{SBPAppearance}} !important;
						-moz-appearance: {{SBPAppearance}} !important;
						-webkit-appearance: {{SBPAppearance}} !important;
						background:{{IPBGColor}} !important;
                    }

                    .fm-form-content select.isBG{
                      background-color: {{IPBGColor}} !important;
                      background-image: url(<?php echo WDFMInstance(self::PLUGIN)->plugin_url; ?>/{{SBPBackground}}) !important;
                      background-repeat: {{SBPBGRepeat}} !important;
                    }
                    .fm-form-example label.mini_label{
						font-size:{{GPMLFontSize}}px !important;
						font-weight:{{GPMLFontWeight}} !important;
						color:{{GPMLColor}} !important;
						padding:{{GPMLPadding}} !important;
						margin:{{GPMLMargin}} !important;
						width: initial !important;
                    }

                    .fm-button-reset {
                    background-color:{{BPBGColor}} !important;
                    color:{{BPColor}} !important;
                    height:{{BPHeight}}px !important;
                    width:{{BPWidth}}px !important;
                    margin:{{BPMargin}} !important;
                    padding:{{BPPadding}} !important;
                    box-shadow:{{BPBoxShadow}} !important;
                    border-radius:{{BPBorderRadius}}px !important;
                    outline: none !important;
                    }

                    .fm-button-reset.borderRight {
                    border-right:{{BPBorderWidth}}px {{BPBorderType}} {{BPBorderColor}} !important;
                    }

                    .fm-button-reset.borderLeft {
                    border-left:{{BPBorderWidth}}px {{BPBorderType}} {{BPBorderColor}} !important;
                    }

                    .fm-button-reset.borderTop {
                    border-top:{{BPBorderWidth}}px {{BPBorderType}} {{BPBorderColor}} !important;
                    }

                    .fm-button-reset.borderBottom {
                    border-bottom:{{BPBorderWidth}}px {{BPBorderType}} {{BPBorderColor}} !important;
                    }

                    .fm-button-reset:hover {
                    background-color:{{BHPBGColor}} !important;
                    color:{{BHPColor}} !important;
                    outline: none;
                    border: none !important;
                    }

                    .fm-button-reset.borderHoverRight:hover {
                    border-right:{{BHPBorderWidth}}px {{BHPBorderType}} {{BHPBorderColor}} !important;
                    }

                    .fm-button-reset.borderHoverLeft:hover {
                    border-left:{{BHPBorderWidth}}px {{BHPBorderType}} {{BHPBorderColor}} !important;
                    }

                    .fm-button-reset.borderHoverTop:hover {
                    border-top:{{BHPBorderWidth}}px {{BHPBorderType}} {{BHPBorderColor}} !important;
                    }

                    .fm-button-reset.borderHoverBottom:hover {
                    border-bottom:{{BHPBorderWidth}}px {{BHPBorderType}} {{BHPBorderColor}} !important;
                    }

                    .fm-form-content button,
                    .fm-wdform-page-button{
                    font-size: {{BPFontSize}}px !important;
                    font-weight: {{BPFontWeight}} !important;
                    }

                    .fm-previous-page .fm-wdform-page-button{
                    background-color:{{PBPBGColor}} !important;
                    color:{{PBPColor}} !important;
                    height:{{PBPHeight}}px !important;
                    line-height:{{PBPLineHeight}}px !important;
                    width:{{PBPWidth}}px !important;
                    margin:{{PBPMargin}} !important;
                    padding:{{PBPPadding}} !important;
                    border-radius:{{PBPBorderRadius}}px !important;
                    box-shadow:{{PBPBoxShadow}} !important;
                    outline: none !important;
                    }

                    .fm-previous-page .fm-wdform-page-button.borderRight {
                    border-right:{{PBPBorderWidth}}px {{PBPBorderType}} {{PBPBorderColor}} !important;
                    }

                    .fm-previous-page .fm-wdform-page-button.borderLeft {
                    border-left:{{PBPBorderWidth}}px {{PBPBorderType}} {{PBPBorderColor}} !important;
                    }

                    .fm-previous-page .fm-wdform-page-button.borderTop {
                    border-top:{{PBPBorderWidth}}px {{PBPBorderType}} {{PBPBorderColor}} !important;
                    }

                    .fm-previous-page .fm-wdform-page-button.borderBottom {
                    border-bottom:{{PBPBorderWidth}}px {{PBPBorderType}} {{PBPBorderColor}} !important;
                    }

                    .fm-previous-page .fm-wdform-page-button:hover {
                    background-color:{{PBHPBGColor}} !important;
                    color:{{PBHPColor}} !important;
                    }

                    .fm-previous-page .fm-wdform-page-button.borderHoverRight:hover {
                    border-right:{{PBHPBorderWidth}}px {{PBHPBorderType}} {{PBHPBorderColor}} !important;
                    }

                    .fm-previous-page .fm-wdform-page-button.borderHoverLeft:hover {
                    border-left:{{PBHPBorderWidth}}px {{PBHPBorderType}} {{PBHPBorderColor}} !important;
                    }

                    .fm-previous-page .fm-wdform-page-button.borderHoverTop:hover {
                    border-top:{{PBHPBorderWidth}}px {{PBHPBorderType}} {{PBHPBorderColor}} !important;
                    }

                    .fm-previous-page .fm-wdform-page-button.borderHoverBottom:hover {
                    border-bottom:{{PBHPBorderWidth}}px {{PBHPBorderType}} {{PBHPBorderColor}} !important;
                    }


                    .fm-next-page .fm-wdform-page-button{
                    background-color:{{NBPBGColor}} !important;
                    color:{{NBPColor}} !important;
                    height:{{NBPHeight}}px !important;
                    line-height:{{NBPLineHeight}}px !important;
                    width:{{NBPWidth}}px !important;
                    margin:{{NBPMargin}} !important;
                    padding:{{NBPPadding}} !important;
                    border-radius:{{NBPBorderRadius}}px !important;
                    box-shadow:{{NBPBoxShadow}} !important;
                    }

                    .fm-next-page .fm-wdform-page-button.borderRight {
                    border-right:{{NBPBorderWidth}}px {{NBPBorderType}} {{NBPBorderColor}} !important;
                    }

                    .fm-next-page .fm-wdform-page-button.borderLeft {
                    border-left:{{NBPBorderWidth}}px {{NBPBorderType}} {{NBPBorderColor}} !important;
                    }

                    .fm-next-page .fm-wdform-page-button.borderTop {
                    border-top:{{NBPBorderWidth}}px {{NBPBorderType}} {{NBPBorderColor}} !important;
                    }

                    .fm-next-page .fm-wdform-page-button.borderBottom {
                    border-bottom:{{NBPBorderWidth}}px {{NBPBorderType}} {{NBPBorderColor}} !important;
                    }

                    .fm-next-page .fm-wdform-page-button:hover {
                    background-color:{{NBHPBGColor}} !important;
                    color:{{NBHPColor}} !important;
                    outline: none !important;
                    }

                    .fm-next-page .fm-wdform-page-button.borderHoverRight:hover {
                    border-right:{{NBHPBorderWidth}}px {{NBHPBorderType}} {{NBHPBorderColor}} !important;
                    }

                    .fm-next-page .fm-wdform-page-button.borderHoverLeft:hover {
                    border-left:{{NBHPBorderWidth}}px {{NBHPBorderType}} {{NBHPBorderColor}} !important;
                    }

                    .fm-next-page .fm-wdform-page-button.borderHoverTop:hover {
                    border-top:{{NBHPBorderWidth}}px {{NBHPBorderType}} {{NBHPBorderColor}} !important;
                    }

                    .fm-next-page .fm-wdform-page-button.borderHoverBottom:hover {
                    border-bottom:{{NBHPBorderWidth}}px {{NBHPBorderType}} {{NBHPBorderColor}} !important;
                    }

                    .fm-button-subscribe {
                    background-color:{{SPBGColor}} !important;
                    font-size:{{SPFontSize}}px !important;
                    font-weight:{{SPFontWeight}} !important;
                    color:{{SPColor}} !important;
                    height:{{SPHeight}}px !important;
                    width:{{SPWidth}}px !important;
                    margin:{{SPMargin}} !important;
                    padding:{{SPPadding}} !important;
                    box-shadow:{{SPBoxShadow}} !important;
                    border-radius: {{SPBorderRadius}}px !important;
                    outline: none !important;
                    }

                    .fm-button-subscribe.borderRight {
                    border-right:{{SPBorderWidth}}px {{SPBorderType}} {{SPBorderColor}} !important;
                    }

                    .fm-button-subscribe.borderLeft {
                    border-left:{{SPBorderWidth}}px {{SPBorderType}} {{SPBorderColor}} !important;
                    }

                    .fm-button-subscribe.borderTop {
                    border-top:{{SPBorderWidth}}px {{SPBorderType}} {{SPBorderColor}} !important;
                    }

                    .fm-button-subscribe.borderBottom {
                    border-bottom:{{SPBorderWidth}}px {{SPBorderType}} {{SPBorderColor}} !important;
                    }

                    .fm-button-subscribe:hover {
                    background-color:{{SHPBGColor}} !important;
                    color:{{SHPColor}} !important;
                    outline: none !important;
                    border: none !important;
                    }

                    .fm-button-subscribe.borderHoverRight:hover {
                    border-right:{{SHPBorderWidth}}px {{SHPBorderType}} {{SHPBorderColor}} !important;
                    }

                    .fm-button-subscribe.borderHoverLeft:hover {
                    border-left:{{SHPBorderWidth}}px {{SHPBorderType}} {{SHPBorderColor}} !important;
                    }

                    .fm-button-subscribe.borderHoverTop:hover {
                    border-top:{{SHPBorderWidth}}px {{SHPBorderType}} {{SHPBorderColor}} !important;
                    }

                    .fm-button-subscribe.borderHoverBottom:hover {
                    border-bottom:{{SHPBorderWidth}}px {{SHPBorderType}} {{SHPBorderColor}} !important;
                    }

                    .radio-div label span {
                    height:{{SCPHeight}}px !important;
                    width:{{SCPWidth}}px !important;
                    background-color:{{SCPBGColor}} !important;
                    margin:{{SCPMargin}} !important;
                    box-shadow:{{SCPBoxShadow}} !important;
                    border-radius: {{SCPBorderRadius}}px !important;
                    border: none !important;
                    display: inline-block !important;
                    vertical-align: middle !important;
                    box-sizing: content-box !important;
                    }

                    .radio-div input[type='radio']:checked + label span:after {
                        content: '';
                    width:{{SCCPWidth}}px !important;
                    height:{{SCCPHeight}}px !important;
                    background:{{SCCPBGColor}} !important;
                    border-radius:{{SCCPBorderRadius}}px !important;
                    margin:{{SCCPMargin}}px !important;
                    display: block !important;
                    }

                    .radio-div label span.borderRight {
                    border-right:{{SCPBorderWidth}}px {{SCPBorderType}} {{SCPBorderColor}} !important;
                    }

                    .radio-div label span.borderLeft {
                    border-left:{{SCPBorderWidth}}px {{SCPBorderType}} {{SCPBorderColor}} !important;
                    }

                    .radio-div label span.borderTop {
                    border-top:{{SCPBorderWidth}}px {{SCPBorderType}} {{SCPBorderColor}} !important;
                    }

                    .radio-div label span.borderBottom {
                    border-bottom:{{SCPBorderWidth}}px {{SCPBorderType}} {{SCPBorderColor}} !important;
                    }

                    .checkbox-div label span {
                    height:{{MCPHeight}}px !important;
                    width:{{MCPWidth}}px !important;
                    background-color:{{MCPBGColor}} !important;
                    margin:{{MCPMargin}} !important;
                    box-shadow:{{MCPBoxShadow}} !important;
                    border-radius: {{MCPBorderRadius}}px !important;
                    border: none !important;
                    display: inline-block !important;
                    vertical-align: middle !important;
                    box-sizing: content-box !important;
                    }

                    .checkbox-div input[type='checkbox']:checked + label span:after {
                        content: '';
                    width:{{MCCPWidth}}px !important;
                    height:{{MCCPHeight}}px !important;
                    border-radius:{{MCCPBorderRadius}}px !important;
                    margin:{{MCCPMargin}}px !important;
                    display: block !important;
                    background:{{MCCPBGColor}} !important;
                    }

                    .checkbox-div.isBG input[type='checkbox']:checked + label span:after{
                    background:{{MCCPBGColor}} url(<?php echo WDFMInstance(self::PLUGIN)->plugin_url; ?>/{{MCCPBackground}}) {{MCCPBGRepeat}} {{MCCPBGPos1}} {{MCCPBGPos2}} !important;
                    }

                    .checkbox-div label span.borderRight {
                    border-right:{{MCPBorderWidth}}px {{MCPBorderType}} {{MCPBorderColor}} !important;
                    }

                    .checkbox-div label span.borderLeft {
                    border-left:{{MCPBorderWidth}}px {{MCPBorderType}} {{MCPBorderColor}} !important;
                    }

                    .checkbox-div label span.borderTop {
                    border-top:{{MCPBorderWidth}}px {{MCPBorderType}} {{MCPBorderColor}} !important;
                    }

                    .checkbox-div label span.borderBottom {
                    border-bottom:{{MCPBorderWidth}}px {{MCPBorderType}} {{MCPBorderColor}} !important;
                    }

                    .fm-form-pagination {
                    width:{{AGPWidth}}% !important;
                    margin:{{AGPMargin}} !important;
                    }

                    .fm-footer{
                    font-size:{{GPFontSize}}px !important;
                    font-weight:{{GPFontWeight}} !important;
                    width:{{FPWidth}}% !important;
                    padding:{{FPPadding}} !important;
                    margin:{{FPMargin}} !important;
                    color:{{GPColor}} !important;
                    clear: both !important;
                    }

                    .fm-pages-steps{
                    text-align: {{PSAPAlign}} !important;
                    }

                    .active-step{
                    background-color: {{PSAPBGColor}} !important;
                    font-size: {{PSAPFontSize}}px !important;
                    font-weight: {{PSAPFontWeight}} !important;
                    color: {{PSAPColor}} !important;
                    width: {{PSAPWidth}}px !important;
                    height: {{PSAPHeight}}px !important;
                    line-height: {{PSAPLineHeight}}px !important;
                    margin: {{PSAPMargin}} !important;
                    padding: {{PSAPPadding}} !important;
                    border-radius: {{PSAPBorderRadius}}px !important;

                    text-align: center !important;
                    display: inline-block !important;
                    cursor: pointer !important;
                    }

                    .active-step.borderRight {
                    border-right:{{PSAPBorderWidth}}px {{PSAPBorderType}} {{PSAPBorderColor}} !important;
                    }

                    .active-step.borderLeft {
                    border-left:{{PSAPBorderWidth}}px {{PSAPBorderType}} {{PSAPBorderColor}} !important;
                    }

                    .active-step.borderTop {
                    border-top:{{PSAPBorderWidth}}px {{PSAPBorderType}} {{PSAPBorderColor}} !important;
                    }

                    .active-step.borderBottom {
                    border-bottom:{{PSAPBorderWidth}}px {{PSAPBorderType}} {{PSAPBorderColor}} !important;
                    }

                    .deactive-step{
                    background-color: {{PSDPBGColor}} !important;
                    font-size: {{PSDPFontSize}}px !important;
                    font-weight: {{PSDPFontWeight}} !important;
                    color: {{PSDPColor}} !important;
                    width: {{PSAPWidth}}px !important;
                    height: {{PSDPHeight}}px !important;
                    line-height: {{PSDPLineHeight}}px !important;
                    margin: {{PSDPMargin}} !important;
                    padding: {{PSDPPadding}} !important;
                    border-radius: {{PSDPBorderRadius}}px !important;

                    text-align: center !important;
                    display: inline-block !important;
                    cursor: pointer !important;
                    }

                    .deactive-step.borderRight {
                    border-right:{{PSDPBorderWidth}}px {{PSDPBorderType}} {{PSDPBorderColor}} !important;
                    }

                    .deactive-step.borderLeft {
                    border-left:{{PSDPBorderWidth}}px {{PSDPBorderType}} {{PSDPBorderColor}} !important;
                    }

                    .deactive-step.borderTop {
                    border-top:{{PSDPBorderWidth}}px {{PSDPBorderType}} {{PSDPBorderColor}} !important;
                    }

                    .deactive-step.borderBottom {
                    border-bottom:{{PSDPBorderWidth}}px {{PSDPBorderType}} {{PSDPBorderColor}} !important;
                    }

                    .active-percentage {
                    background-color: {{PSAPBGColor}} !important;
                    font-size: {{PSAPFontSize}}px !important;
                    font-weight: {{PSAPFontWeight}} !important;
                    color: {{PSAPColor}} !important;
                    width: {{PSAPWidth}}px !important;
                    height: {{PSAPHeight}}px !important;
                    line-height: {{PSAPLineHeight}}px !important;
                    margin: {{PSAPMargin}} !important;
                    padding: {{PSAPPadding}} !important;
                    border-radius: {{PSAPBorderRadius}}px !important;

                    display: inline-block !important;
                    }

                    .active-percentage.borderRight {
                    border-right:{{PSAPBorderWidth}}px {{PSAPBorderType}} {{PSAPBorderColor}} !important;
                    }

                    .active-percentage.borderLeft {
                    border-left:{{PSAPBorderWidth}}px {{PSAPBorderType}} {{PSAPBorderColor}} !important;
                    }

                    .active-percentage.borderTop {
                    border-top:{{PSAPBorderWidth}}px {{PSAPBorderType}} {{PSAPBorderColor}} !important;
                    }

                    .active-percentage.borderBottom {
                    border-bottom:{{PSAPBorderWidth}}px {{PSAPBorderType}} {{PSAPBorderColor}} !important;
                    }

                    .deactive-percentage {
                    background-color: {{PSDPBGColor}} !important;
                    font-size: {{PSDPFontSize}}px !important;
                    font-weight: {{PSDPFontWeight}} !important;
                    color: {{PSDPColor}} !important;
                    width: {{PPAPWidth}} !important;
                    height: {{PSDPHeight}}px !important;
                    line-height: {{PSDPLineHeight}}px !important;
                    margin: {{PSDPMargin}} !important;
                    padding: {{PSDPPadding}} !important;
                    border-radius: {{PSDPBorderRadius}}px !important;

                    display: inline-block !important;
                    }

                    .deactive-percentage.borderRight {
                    border-right:{{PSDPBorderWidth}}px {{PSDPBorderType}} {{PSDPBorderColor}} !important;
                    }

                    .deactive-percentage.borderLeft {
                    border-left:{{PSDPBorderWidth}}px {{PSDPBorderType}} {{PSDPBorderColor}} !important;
                    }

                    .deactive-percentage.borderTop {
                    border-top:{{PSDPBorderWidth}}px {{PSDPBorderType}} {{PSDPBorderColor}} !important;
                    }

                    .deactive-percentage.borderBottom {
                    border-bottom:{{PSDPBorderWidth}}px {{PSDPBorderType}} {{PSDPBorderColor}} !important;
                    }

                    .fm-close-icon {
                    color: {{CBPColor}} !important;
                    font-size: {{CBPFontSize}}px !important;
                    font-weight: {{CBPFontWeight}} !important;
                    text-align: center !important;
                    }

                    .fm-close {
                    position: {{CBPPosition}} !important;
                    top: {{CBPTop}} !important;
                    right: {{CBPRight}} !important;
                    bottom: {{CBPBottom}} !important;
                    left: {{CBPLeft}} !important;
                    background-color: {{CBPBGColor}} !important;
                    padding: {{CBPPadding}} !important;
                    margin: {{CBPMargin}} !important;
                    border-radius: {{CBPBorderRadius}}px !important;
                    border: none !important;
                    cursor: pointer !important;
                    }

                    .fm-close.borderRight{
                    border-right:{{CBPBorderWidth}}px {{CBPBorderType}} {{CBPBorderColor}} !important;
                    }

                    .fm-close.borderLeft{
                    border-left:{{CBPBorderWidth}}px {{CBPBorderType}} {{CBPBorderColor}} !important;
                    }

                    .fm-close.borderTop{
                    border-top:{{CBPBorderWidth}}px {{CBPBorderType}} {{CBPBorderColor}} !important;
                    }

                    .fm-close.borderBottom {
                    border-bottom:{{CBPBorderWidth}}px {{CBPBorderType}} {{CBPBorderColor}} !important;
                    }

                    .fm-close:hover{
                    background-color:{{CBHPBGColor}} !important;
                    color:{{CBHPColor}} !important;
                    outline: none !important;
                    border: none !important;
                    }

                    .fm-close.borderHoverRight:hover {
                    border-right:{{CBHPBorderWidth}}px {{CBHPBorderType}} {{CBHPBorderColor}} !important;
                    }

                    .fm-close.borderHoverLeft:hover {
                    border-left:{{CBHPBorderWidth}}px {{CBHPBorderType}} {{CBHPBorderColor}} !important;
                    }

                    .fm-close.borderHoverTop:hover{
                    border-top:{{CBHPBorderWidth}}px {{CBHPBorderType}} {{CBHPBorderColor}} !important;
                    }

                    .fm-close.borderHoverBottom:hover {
                    border-bottom:{{CBHPBorderWidth}}px {{CBHPBorderType}} {{CBHPBorderColor}} !important;
                    }

                    .fm-form-header {
                    background-color:{{HPBGColor}} !important;
                    width:{{HPWidth}}% !important;
                    padding:{{HPPadding}} !important;
                    margin:{{HPMargin}} !important;
                    border-radius:{{HPBorderRadius}}px !important;
                    }

                    .fm-form-header .htitle {
                    font-size:{{HTPFontSize}}px !important;
                    color:{{HTPColor}} !important;
                    text-align:{{HPTextAlign}} !important;
                    padding: 10px 0 !important;
                    line-height:{{HTPFontSize}}px !important;
                    font-weight:{{HTPWeight}} !important;

                    }

                    .fm-form-header .himage img {
                    width:{{HIPWidth}}px !important;
                    height:{{HIPHeight}}px !important;
                    }

                    .fm-form-header .himage.imageTop,
                    .fm-form-header .himage.imageBottom{
                    text-align:{{HPTextAlign}} !important;
                    }


                    .fm-form-header .hdescription {
                    font-size:{{HDPFontSize}}px !important;
                    color:{{HDPColor}} !important;
                    text-align:{{HPTextAlign}} !important;
                    padding: 5px 0 !important;
                    }

                    .fm-form-header.borderRight{
                    border-right:{{HPBorderWidth}}px {{HPBorderType}} {{HPBorderColor}} !important;
                    }

                    .fm-form-header.borderLeft{
                    border-left:{{HPBorderWidth}}px {{HPBorderType}} {{HPBorderColor}} !important;
                    }

                    .fm-form-header.borderTop{
                    border-top:{{HPBorderWidth}}px {{HPBorderType}} {{HPBorderColor}} !important;
                    }

                    .fm-form-header.borderBottom{
                    border-bottom:{{HPBorderWidth}}px {{HPBorderType}} {{HPBorderColor}} !important;
                    }

                    .fm-form-header.alignLeft,
                    .fm-form-content.alignLeft {
                        display: table-cell !important;
                        vertical-align:middle !important;
                    }

                    .wdform-required {
                        color: {{OPRColor}} !important;
                    }
                    .subscribe-reset .wdform-element-section {
                        text-align: {{SPAlign}} !important;
                      margin-right:-15px !important;
                    }
                </style>
            </div>
			</div>
		</div>
		<?php
	}
}
