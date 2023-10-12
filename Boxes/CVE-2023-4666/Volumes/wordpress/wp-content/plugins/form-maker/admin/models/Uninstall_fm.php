<?php

class FMModelUninstall_fm extends FMAdminModel {

  /**
   * Removes form's tables and options from the database.
   *
   * @param $tables array    array of tables
   * @param $options array    array of options
   */
  public function delete_db_tables( $tables, $options ) {
    global $wpdb;
    $remove_tables = FALSE;
    $contact_form_ids = get_option('contact_form_forms');
    if ( !WDFMInstance(self::PLUGIN)->is_free || $contact_form_ids == '' ) {
      // Form maker paid or there is no contact form maker forms.
      $remove_tables = TRUE;
    }
    else {
      // Form maker free.
      $forms_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'formmaker WHERE `id` NOT IN (' . $contact_form_ids . ')');
      // Contact form maker.
      $contact_forms_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'formmaker WHERE `id` IN (' . $contact_form_ids . ')');
      if ( (WDFMInstance(self::PLUGIN)->is_free == 1 && $contact_forms_count == 0)
        || (WDFMInstance(self::PLUGIN)->is_free == 2 && $forms_count == 0) ) {
        // Installed only Form maker free or only Contact form maker.
        $remove_tables = TRUE;
      }
    }
    if ( !$remove_tables ) {
      // Installed both Form maker free and Contact form maker.
      $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'formmaker WHERE `id`' . (WDFMInstance(self::PLUGIN)->is_free == 1 ? ' NOT ' : ' ') . 'IN (' . $contact_form_ids . ')');
      $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'formmaker_submits WHERE `form_id`' . (WDFMInstance(self::PLUGIN)->is_free == 1 ? ' NOT ' : ' ') . 'IN (' . $contact_form_ids . ')');
      $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'formmaker_views WHERE `form_id`' . (WDFMInstance(self::PLUGIN)->is_free == 1 ? ' NOT ' : ' ') . 'IN (' . $contact_form_ids . ')');
    }
    else {
      // Remove "form-maker" custom post.
      $wpdb->query('DELETE FROM `' . $wpdb->prefix . 'posts` WHERE `post_type` = "form-maker"');
      // Remove email verification custom post.
      $wpdb->query('DELETE FROM `' . $wpdb->prefix . 'posts` WHERE `post_type` = "' . ( (WDFMInstance(self::PLUGIN)->is_free == 2) ? 'cfmemailverification' : 'fmemailverification') . '"');

      foreach ($options as $option) {
        delete_option($option);
      }

      // Delete form js and css files.
      $wp_upload_dir = wp_upload_dir();
      $frontend_js = $wp_upload_dir['basedir'] . '/form-maker-frontend/js/';
      if ( is_dir($frontend_js) ) {
        $js_files = scandir($frontend_js);
        foreach ( $js_files as $js_file ) {
          if ( is_file($frontend_js . $js_file) ) {
            $filename = pathinfo($frontend_js . $js_file);
            if ( $filename['extension'] == 'js' ) {
              unlink($frontend_js . $js_file);
            }
          }
        }
      }
      $frontend_css = $wp_upload_dir['basedir'] . '/form-maker-frontend/css/';
      if ( is_dir($frontend_css) ) {
        $css_files = scandir($frontend_css);
        foreach ( $css_files as $css_file ) {
          if ( is_file($frontend_css . $css_file) ) {
            $filename = pathinfo($frontend_css . $css_file);
            if ( $filename['extension'] == 'css' ) {
              unlink($frontend_css . $css_file);
            }
          }
        }
      }
      foreach ($tables as $table) {
        $wpdb->query('DROP TABLE IF EXISTS ' . $table);
      }
    }
  }
}
