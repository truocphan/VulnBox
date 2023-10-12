<?php

/**
 * Class FMModelFormMakerSubmits
 */
class FMModelFormMakerSubmits extends FMAdminModel {

  /**
   * Get from label order.
   *
   * @param int $form_id
   * @return mixed
   */
  public function get_from_label_order( $form_id = 0 ) {
    global $wpdb;
    $label_order = $wpdb->get_var($wpdb->prepare('SELECT `label_order` FROM `' . $wpdb->prefix . 'formmaker` WHERE id=%d', $form_id));

    return $label_order;
  }

  /**
   * Get submissions.
   *
   * @param int $group_id
   * @return mixed
   */
  public function get_submissions( $group_id = 0 ) {
    global $wpdb;
    $row = $wpdb->get_results($wpdb->prepare('SELECT * FROM `' . $wpdb->prefix . 'formmaker_submits` WHERE group_id=%d', $group_id));

    return $row;
  }
}
