<?php

/**
 * Class FMControllerSelect_data_from_db
 */
class FMControllerSelect_data_from_db extends FMAdminController {
  /**
   * @var $model
   */
  private $model;
  /**
   * @var $view
   */
  private $view;

  public function __construct() {
    // Load FMModelSelect_data_from_db class.
    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/admin/models/FMSelectDataFromDb.php";
    $this->model = new FMModelSelect_data_from_db();
    // Load FMViewSelect_data_from_db class.
    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/admin/views/FMSelectDataFromDb.php";
    $this->view = new FMViewSelect_data_from_db();
  }

  /**
   * Execute.
   */
  public function execute() {
    $id = WDW_FM_Library(self::PLUGIN)->get('id', 0, 'intval');
    $form_id = WDW_FM_Library(self::PLUGIN)->get('form_id', 0, 'intval');
    $field_id = WDW_FM_Library(self::PLUGIN)->get('field_id', 0, 'intval');
    $value_disabled = WDW_FM_Library(self::PLUGIN)->get('value_disabled', 0);
    $field_type = WDW_FM_Library(self::PLUGIN)->get('field_type', '');
    $task = WDW_FM_Library(self::PLUGIN)->get('task', '', 'sanitize_key');
    if ( $task && method_exists($this, $task) ) {
      $this->$task( $form_id, $field_type = '' );
    }
    else {
      $this->display( $id, $form_id, $field_id, $field_type, $value_disabled );
    }
  }

  /**
   * Display.
   *
   * @param  int    $id
   * @param  int    $form_id
   * @param  int    $field_id
   * @param  string $field_type
   * @param  int    $value_disabled
   */
  public function display( $id = 0, $form_id = 0, $field_id = 0, $field_type = '', $value_disabled = 0 ) {
    // Set params for view.
    $params = array();
    $params['id'] = $id;
    $params['form_id'] = $form_id;
    $params['field_id'] = $field_id;
    $params['field_type'] = $field_type;
    $params['value_disabled'] = $value_disabled;
    $this->view->display($params);
  }

  /**
   * Data base tables.
   *
   * @param  int    $form_id
   * @param  string $field_type
   */
  public function db_tables( $form_id = 0, $field_type = '' ) {
    // Get tables.
    $tables = $this->model->get_tables();
    // Set params for view.
    $params = array();
    $params['form_id'] = intval($form_id);
    $params['field_type'] = $field_type;
    $params['tables'] = $tables;
    $this->view->db_tables($params);
  }

  public function db_table_struct_select( $form_id = 0, $field_type = '' ) {
    // Get labels by form id.
    $label = $this->model->get_labels($form_id);
    // Get table struct.
    $table_struct = $this->model->get_table_struct();
    // Set params for view.
    $params = array();
    $params['form_id'] = $form_id;
    $params['field_type'] = $field_type;
    $params['label'] = $label;
    $params['table_struct'] = $table_struct;
	// Set placeholders.
	$user_fields = array(
		"ip" => "Submitter's IP",
		"userid" => "User ID",
		"username" => "Username",
		"useremail" => "User Email"
	);
	$html_placeholders = '';
	foreach ( $user_fields as $user_key => $user_field ) {
		$html_placeholders .= '<a onclick="insert_placeholder(\'' . $user_key . '\'); jQuery(\'#fm-placeholder\').hide();" style="display:block; text-decoration:none;">' . $user_field . "</a>\r\n";
	}
    $params['html_placeholders'] = $html_placeholders;
    $this->view->db_table_struct_select($params);
  }
}
