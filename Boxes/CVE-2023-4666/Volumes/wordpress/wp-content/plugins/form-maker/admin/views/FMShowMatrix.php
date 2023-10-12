<?php

/**
 * Class FMViewShow_matrix
 */
class FMViewShow_matrix extends FMAdminView {
  /**
   * Display.
   *
   * @param array $params
   */
  public function display( $params = array() ) {
    $matrix_params = WDW_FM_Library(self::PLUGIN)->get( 'matrix_params', 0, 'esc_html' );
    $new_filename = str_replace("***matrix***", '', $matrix_params);
    $new_filename = explode('***', $matrix_params);
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
      $aaa = array();
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
                <td style="text-align:center"><input type="checkbox" <?php echo $checked; ?> disabled /></td>
                <?php
                $var_checkbox++;
              }
            }
            else {
              if ( $mat_params[$mat_rows + $mat_columns + 2] == "text" ) {
                for ( $l = 1; $l <= $mat_columns; $l++ ) {
                  $checked = $mat_params[$mat_rows + $mat_columns + 2 + $var_checkbox];
                  ?>
                  <td style="text-align:center"><input type="text" value="<?php echo $checked; ?>" disabled /></td>
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
    <?php
    die();
  }
}
