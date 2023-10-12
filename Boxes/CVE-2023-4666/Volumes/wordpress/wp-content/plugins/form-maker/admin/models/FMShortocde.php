<?php

/**
 * Class FMModelFMShortocde
 */
class FMModelFMShortocde extends FMAdminModel {

  /**
   * Get forms.
   *
   * @return array $rows
   */
  public function get_form_data() {
    global $wpdb;
    $rows = $wpdb->get_results("SELECT * FROM `" . $wpdb->prefix . "formmaker`" . (!WDFMInstance(self::PLUGIN)->is_free ? '' : ' WHERE id' . (WDFMInstance(self::PLUGIN)->is_free == 1 ? ' NOT ' : ' ') . 'IN (' . (get_option('contact_form_forms', '') != '' ? get_option('contact_form_forms') : 0) . ') ORDER BY `title`'));

    return $rows;
  }
}
