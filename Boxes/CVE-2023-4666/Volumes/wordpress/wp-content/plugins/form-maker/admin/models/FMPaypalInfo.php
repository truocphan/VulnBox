<?php

/**
 * Class FMModelPaypal_info
 */
class FMModelPaypal_info extends FMAdminModel {
  /**
   * Get form session.
   *
   * @param int $id
   *
   * @return object $row
   */
  public function get_form_session( $id = 0 ) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'formmaker_sessions WHERE group_id=%d', $id));
    $stripe_transaction = $this->get_stripe_transaction_id($id);
    if ( !empty($stripe_transaction) ) {
      $row->transaction_id = $stripe_transaction->transaction_id;
    }

    return $row;
  }

  /**
   * Get stripe transaction id.
   *
   * @param int $id
   *
   * @return array|object|void|null
   */
  public function get_stripe_transaction_id( $id = 0 ) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT element_value AS transaction_id FROM ' . $wpdb->prefix . 'formmaker_submits WHERE element_label = %s AND group_id=%d', 'stripeToken', $id));

    return $row;
  }
}
