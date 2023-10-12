<?php

/**
 * Class FMViewFMShortocde
 */
class FMViewFMShortocde extends FMAdminView {
  /**
   * FMViewFMShortocde constructor.
   */
  public function __construct() {
    wp_print_scripts(WDFMInstance(self::PLUGIN)->handle_prefix . '-shortcode' . WDFMInstance(self::PLUGIN)->menu_postfix);

    wp_print_styles('wp-admin');
    wp_print_styles('buttons');

    wp_print_styles(WDFMInstance(self::PLUGIN)->handle_prefix . '-tables');

    if (!WDFMInstance(self::PLUGIN)->is_free) {
      wp_print_scripts('jquery-ui-datepicker');
      wp_print_styles(WDFMInstance(self::PLUGIN)->handle_prefix . '-jquery-ui');
    }
    else {
      wp_print_styles( WDFMInstance(self::PLUGIN)->handle_prefix . '-topbar' );
      wp_print_styles( WDFMInstance(self::PLUGIN)->handle_prefix . '-roboto' );
    }
  }

  /**
   * Insert form.
   *
   * @param array $forms
   */
  public function forms( $forms = array() ) {
    ?>
    <body class="wp-core-ui" data-width="400" data-height="140">
      <div class="wd-table">
        <div class="wd-table-col">
          <div class="wd-box-content wd-box-content-shortcode">
            <span class="wd-group">
              <select name="form_maker_id">
                <option value="0"><?php _e('-Select a Form-', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                <?php
                if ( $forms ) {
                  foreach ( $forms as $form ) {
                    ?>
                <option value="<?php echo $form->id; ?>" <?php if (!$form->published) { echo 'disabled="disabled"';}  ?>><?php echo $form->title . ($form->published ? '' : ' - ' . __('Unpublished', WDFMInstance(self::PLUGIN)->prefix)); ?></option>
                    <?php
                  }
                }
                ?>
              </select>
            </span>
            <span class="wd-group wd-right">
              <input class="wd-button button-primary" type="button" name="insert" value="<?php _e('Insert', WDFMInstance(self::PLUGIN)->prefix); ?>" onclick="insert_shortcode('form')" />
            </span>
          </div>
        </div>
      </div>
    </body>
    <?php
    die();
  }

  /**
   * Insert submissions.
   *
   * @param array $forms
   */
  public function submissions( $forms = array() ) {
    ?>
    <body class="wp-core-ui" data-width="520" data-height="570" <?php echo (WDFMInstance(self::PLUGIN)->is_free ? 'style="overflow: hidden;"' : ''); ?>>
      <?php
      if ( WDFMInstance(self::PLUGIN)->is_free ) {
        echo $this->free_message(__('Front end submissions are available in Premium version', WDFMInstance(self::PLUGIN)->prefix));
        ?>
        <div class="wd-fixed-conteiner"></div>
        <?php
      }
      ?>
      <div class="wd-table">
        <div class="wd-table-col">
          <div class="wd-box-content wd-box-content-shortcode">
            <span class="wd-group">
              <select name="form_maker_id">
                <option value="0"><?php _e('-Select a Form-', WDFMInstance(self::PLUGIN)->prefix); ?></option>
                <?php
                if ( $forms ) {
                  foreach ( $forms as $form ) {
                    ?>
                    <option value="<?php echo $form->id; ?>" <?php if (!$form->published) { echo 'disabled="disabled"';}  ?>><?php echo $form->title . ($form->published ? '' : ' - ' . __('Unpublished', WDFMInstance(self::PLUGIN)->prefix)); ?></option>
                    <?php
                  }
                }
                ?>
              </select>
            </span>
            <span class="wd-group">
              <label class="wd-label" for="public_key"><?php _e('Select Date Range', WDFMInstance(self::PLUGIN)->prefix); ?></label>
              <label for="startdate"><?php _e('From', WDFMInstance(self::PLUGIN)->prefix); ?>:</label>
              <input class="initial-width wd-datepicker" type="text" name="startdate" id="startdate" size="10" maxlength="10" value="" />
              <label for="enddate"><?php _e('To', WDFMInstance(self::PLUGIN)->prefix); ?>:</label>
              <input class="initial-width wd-datepicker" type="text" name="enddate" id="enddate" size="10" maxlength="10" value="" />
            </span>
          </div>
        </div>
      </div>
      <div class="wd-table">
        <div class="wd-table-col wd-table-col-50 wd-table-col-left">
          <div class="wd-box-content wd-box-content-shortcode">
            <span class="wd-group">
              <label class="wd-label"><?php _e('Select fields', WDFMInstance(self::PLUGIN)->prefix); ?></label>
              <ul>
                <li>
                  <input type="checkbox" checked="checked" id="submit_date" name="submit_date" value="submit_date" />
                  <label for="submit_date"><?php _e('Submit Date', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </li>
                <li>
                  <input type="checkbox" checked="checked" id="submitter_ip" name="submitter_ip" value="submitter_ip" />
                  <label for="submitter_ip"><?php _e('Submitter\'s IP Address', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </li>
                <li>
                  <input type="checkbox" checked="checked" id="username" name="username" value="username" />
                  <label for="username"><?php _e('Submitter\'s Username', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </li>
                <li>
                  <input type="checkbox" checked="checked" id="useremail" name="useremail" value="useremail" />
                  <label for="useremail"><?php _e('Submitter\'s Email Address', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </li>
                <li>
                  <input type="checkbox" checked="checked" id="form_fields" name="form_fields" value="form_fields" />
                  <label for="form_fields"><?php _e('Form Fields', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </li>
                <p class="description">
                  <?php _e('You can hide specific form fields from Form General Options.', WDFMInstance(self::PLUGIN)->prefix); ?>
                </p>
              </ul>
            </span>
            <span class="wd-group">
              <label class="wd-label"><?php _e('Export to', WDFMInstance(self::PLUGIN)->prefix); ?></label>
              <ul>
                <li>
                  <input type="checkbox" checked="checked" id="csv" name="csv" value="csv" />
                  <label for="csv"><?php _e('CSV', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </li>
                <li>
                  <input type="checkbox" checked="checked" id="xml" name="xml" value="xml" />
                  <label for="xml"><?php _e('XML', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </li>
              </ul>
            </span>
          </div>
        </div>
        <div class="wd-table-col wd-table-col-50 wd-table-col-right">
          <div class="wd-box-content wd-box-content-shortcode">
            <span class="wd-group">
              <label class="wd-label"><?php _e('Show', WDFMInstance(self::PLUGIN)->prefix); ?></label>
              <ul>
                <li>
                  <input type="checkbox" checked="checked" id="title" name="title" value="title" />
                  <label for="title"><?php _e('Title', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </li>
                <li>
                  <input type="checkbox" checked="checked" id="search" name="search" value="search" />
                  <label for="search"><?php _e('Search', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </li>
                <li>
                  <input type="checkbox" checked="checked" id="ordering" name="ordering" value="ordering" />
                  <label for="ordering"><?php _e('Ordering', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </li>
                <li>
                  <input type="checkbox" checked="checked" id="entries" name="entries" value="entries" />
                  <label for="entries"><?php _e('Entries', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </li>
                <li>
                  <input type="checkbox" checked="checked" id="views" name="views" value="views" />
                  <label for="views"><?php _e('Views', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </li>
                <li>
                  <input type="checkbox" checked="checked" id="conversion_rate" name="conversion_rate" value="conversion_rate" />
                  <label for="conversion_rate"><?php _e('Conversion Rate', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </li>
                <li>
                  <input type="checkbox" checked="checked" id="pagination" name="pagination" value="pagination" />
                  <label for="pagination"><?php _e('Pagination', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </li>
                <li>
                  <input type="checkbox" checked="checked" id="stats" name="stats" value="stats" />
                  <label for="stats"><?php _e('Statistics', WDFMInstance(self::PLUGIN)->prefix); ?></label>
                </li>
              </ul>
            </span>
          </div>
        </div>
      </div>
      <div class="wd-table">
        <div class="wd-table-col">
          <div class="wd-box-content wd-box-content-shortcode">
            <span class="wd-group wd-right">
              <input class="wd-button button-primary" type="button" name="insert" value="<?php _e('Insert', WDFMInstance(self::PLUGIN)->prefix); ?>" onclick="insert_shortcode('submissions')" />
            </span>
          </div>
        </div>
      </div>
    </body>
    <?php
    die();
  }
}
