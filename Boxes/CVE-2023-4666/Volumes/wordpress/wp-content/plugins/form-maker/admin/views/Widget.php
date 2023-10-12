<?php

/**
 * Class FMViewWidget
 */
class FMViewWidget {

  /**
   * Widget.
   *
   * @param array $args
   * @param array $instance
   * @param string $execute
   */
  function widget( $args = array(), $instance = array(), $execute = '' ) {
    extract($args);
    // Before widget.
    echo $before_widget;
    // Title of widget.
    if ( $instance['title'] ) {
      echo $before_title . $instance['title'] . $after_title;
    }
    // Widget output.
    echo $execute;
    // After widget.
    echo $after_widget;
  }

  /**
   * Form.
   * @param $instance
   * @param $ids_FM
   * @param $id_title
   * @param $name_title
   * @param $id_form_id
   * @param $name_form_id
   */
  function form( $instance = array(), $ids_FM = array(), $id_title = 0, $name_title = '', $id_form_id = 0, $name_form_id = 0 ) {
    $defaults = array(
      'title' => '',
      'form_id' => 0,
    );
    $instance = wp_parse_args((array) $instance, $defaults);
    ?>
    <p>
      <label for="<?php echo $id_title; ?>">Title:</label>
      <input class="widefat" id="<?php echo $id_title; ?>" name="<?php echo $name_title; ?>" type="text" value="<?php echo $instance['title']; ?>" />
      <label for="<?php echo $id_form_id; ?>">Select a form:</label>
      <select class="widefat" name="<?php echo $name_form_id; ?>" id="<?php echo $id_form_id; ?>">
        <option style="text-align:center" value="0">- Select a Form -</option>
        <?php
        $ids_Form_Maker = $ids_FM;
        foreach ( $ids_Form_Maker as $arr_Form_Maker ) {
          ?>
          <option value="<?php echo $arr_Form_Maker->id; ?>" <?php if ( $arr_Form_Maker->id == $instance['form_id'] ) {
            echo "SELECTED";
          } ?>><?php echo $arr_Form_Maker->title; ?></option>
        <?php } ?>
      </select>
    </p>
    <?php
  }
}
