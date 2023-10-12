<?php

/**
 * Class FMControllerFormMakerSubmits
 */
class FMControllerFormMakerSubmits extends FMAdminController {

  private $model;
  private $view;

  public function __construct() {
    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/admin/models/FormMakerSubmits.php";
    $this->model = new FMModelFormMakerSubmits();
    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/admin/views/FormMakerSubmits.php";
    $this->view = new FMViewFormMakerSubmits();
  }

  public function execute() {
    $this->display();
  }

  public function display() {
    $params = array();
    $form_id = stripslashes(WDW_FM_Library(self::PLUGIN)->get( 'form_id','', 'intval' ) );
    $params['label_order'] = $this->model->get_from_label_order($form_id);
    $group_id = stripslashes( WDW_FM_Library(self::PLUGIN)->get( 'group_id', '', 'intval' ) );
    $params['rows'] = $this->model->get_submissions($group_id);
    $labels_id = array();
    $labels_name = array();
    $labels_type = array();
    $label_all = explode('#****#', $params['label_order']);
    $label_all = array_slice($label_all, 0, count($label_all) - 1);
    foreach ( $label_all as $key => $label_each ) {
      $label_id_each = explode('#**id**#', $label_each);
      array_push($labels_id, $label_id_each[0]);
      $label_oder_each = explode('#**label**#', $label_id_each[1]);
      array_push($labels_name, $label_oder_each[0]);
      array_push($labels_type, $label_oder_each[1]);
    }
    $params['labels_id'] = $labels_id;
    $params['labels_name'] = $labels_name;
    $params['labels_type'] = $labels_type;
    $this->view->display($params);
  }
}
