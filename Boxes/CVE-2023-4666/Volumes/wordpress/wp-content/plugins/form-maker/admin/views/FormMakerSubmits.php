<?php

/**
 * Class FMViewFormMakerSubmits
 */
class FMViewFormMakerSubmits extends FMAdminView {

  /**
   * Display.
   *
   * @param array $params
   */
  public function display( $params = array() ) {
    if ( isset($_GET['form_id']) && isset($_GET['group_id']) ) {
      $rows = $params['rows'];
      $labels_id = $params['labels_id'];
      $labels_name = $params['labels_name'];
      $labels_type = $params['labels_type'];
      ?>
      <style>
        table.submit_table {
          font-family: verdana, arial, sans-serif;
          border-width: 1px;
          border-color: #999999;
          border-collapse: collapse;
        }

        table.submit_table td {
          padding: 6px;
          border: 1px solid #fff;
          font-size: 13px;
          max-width:288px;
          word-break: break-all;
        }

        .field_label {
          background: #E4E4E4;
          font-weight: bold;
        }

        .field_value {
          background: #f0f0ee;
        }
      </style>
      <table class="submit_table">
        <tr>
          <td class="field_label">ID:</td>
          <td class="field_value"><?php echo $rows[0]->group_id; ?></td>
        </tr>
        <tr>
          <td class="field_label"><?php echo __("Date", "form_maker"); ?>:</td>
          <td class="field_value"><?php echo get_date_from_gmt( $rows[0]->date ); ?></td>
        </tr>
        <tr>
          <td class="field_label">IP:</td>
          <td class="field_value"><?php echo $rows[0]->ip; ?></td>
        </tr>
        <?php
        foreach ( $labels_id as $key => $label_id ) {
          if ( $labels_type[$key] != '' and $labels_type[$key] != 'type_editor' and $labels_type[$key] != 'type_submit_reset' and $labels_type[$key] != 'type_map' and $labels_type[$key] != 'type_captcha' ) {
            $element_value = '';
            foreach ( $rows as $row ) {
              if ( $row->element_label == $label_id ) {
                $element_value = $row->element_value;
                break;
              }
              else {
                $element_value = 'element_valueelement_valueelement_value';
              }
            }
            if ( $labels_type[$key] == "type_name" ) {
              $element_value = str_replace("@@@", " ", $element_value);
            }
            if ( $labels_type[$key] == "type_file_upload" ) {
              $element_value = str_replace("*@@url@@*", " ", $element_value);
            }
            if ( $element_value == "element_valueelement_valueelement_value" ) {
              continue;
            }
            ?>
            <tr>
              <td class="field_label"><?php echo $labels_name[$key]; ?></td>
              <?php
              if ( $labels_type[$key] == 'type_signature' ) {
                ?>
                <td class="field_value"><img src="<?php echo $element_value; ?>" style="width:50px; border: 1px solid #ddd;"></td>
                <?php
              }
              else if( $labels_type[$key] == 'type_textarea' ) {
                ?>
                <td class="field_value"><?php echo str_replace("***br***", '<br>', wpautop(esc_html($element_value))); ?></td>
                <?php
              }
              else if ( $labels_type[$key] != 'type_matrix' ) {
                ?>
                <td class="field_value"><?php echo wpautop(str_replace("***br***", '<br>', wpautop(esc_html($element_value)))); ?></td>
                <?php
              }
              else { ?>
                <td class="field_value">
                  <?php
                  $new_filename = str_replace("***matrix***", '', $element_value);
                  $new_filename = explode('***', $element_value);
                  $mat_params = array_slice($new_filename, 0, count($new_filename) - 1);
                  $mat_rows = $mat_params[0];
                  $mat_columns = $mat_params[$mat_rows + 1];
                  ?>
                  <table style="margin: 0 auto;">
                    <tr>
                      <td></td>
                      <?php
                      for ( $k = 1; $k <= $mat_columns; $k++ ) {
                        ?>
                        <td style="background-color: #BBBBBB; padding: 5px;"><?php echo $mat_params[$mat_rows + 1 + $k]; ?></td>
                        <?php
                      }
                      ?>
                    </tr>
                    <?php
                    $aaa = Array();
                    $var_checkbox = 1;
                    for ( $k = 1; $k <= $mat_rows; $k++ ) {
                      ?>
                      <tr>
                        <td style="background-color: #BBBBBB; padding: 5px; "><?php echo $mat_params[$k]; ?></td>
                        <?php
                        if ( $mat_params[$mat_rows + $mat_columns + 2] == "radio" ) {
                          if ( $mat_params[$mat_rows + $mat_columns + 2 + $k] == 0 ) {
                            $checked = 0;
                            $aaa[1] = "";
                          }
                          else {
                            $aaa = explode("_", $mat_params[$mat_rows + $mat_columns + 2 + $k]);
                          }
                          for ( $l = 1; $l <= $mat_columns; $l++ ) {
                            if ( $aaa[1] == $l ) {
                              $checked = "checked";
                            }
                            else {
                              $checked = "";
                            }
                            ?>
                            <td style="text-align: center;"><input type="radio" <?php echo $checked; ?> disabled /></td>
                            <?php
                          }
                        }
                        else {
                          if ( $mat_params[$mat_rows + $mat_columns + 2] == "checkbox" ) {
                            for ( $l = 1; $l <= $mat_columns; $l++ ) {
                              if ( $mat_params[$mat_rows + $mat_columns + 2 + $var_checkbox] == "1" ) {
                                $checked = "checked";
                              }
                              else {
                                $checked = "";
                              }
                              ?>
                              <td style="text-align:center"><input type="checkbox" <?php echo $checked; ?> disabled />
                              </td>
                              <?php
                              $var_checkbox++;
                            }
                          }
                          else {
                            if ( $mat_params[$mat_rows + $mat_columns + 2] == "text" ) {
                              for ( $l = 1; $l <= $mat_columns; $l++ ) {
                                $checked = $mat_params[$mat_rows + $mat_columns + 2 + $var_checkbox];
                                ?>
                                <td style="text-align:center">
                                  <input type="text" value="<?php echo $checked; ?>" disabled /></td>
                                <?php
                                $var_checkbox++;
                              }
                            }
                            else {
                              for ( $l = 1; $l <= $mat_columns; $l++ ) {
                                $checked = $mat_params[$mat_rows + $mat_columns + 2 + $var_checkbox];
                                ?>
                                <td style="text-align: center;"><?php echo $checked; ?></td>
                                <?php
                                $var_checkbox++;
                              }
                            }
                          }
                        }
                        ?>
                      </tr>
                      <?php
                    }
                    ?>
                  </table>
                </td>
              <?php } ?>
            </tr>
            <?php
          }
        }
        ?>
      </table>
      <?php
    }
    die();
  }
}
