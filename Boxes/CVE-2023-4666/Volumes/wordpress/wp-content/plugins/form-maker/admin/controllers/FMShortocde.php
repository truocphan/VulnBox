<?php

/**
 * Class FMControllerFMShortocde
 */
class FMControllerFMShortocde extends FMAdminController {

  private $model;
  private $view;

  /**
   * FMControllerFMShortocde constructor.
   */
  public function __construct() {
    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/admin/models/FMShortocde.php";
    $this->model = new FMModelFMShortocde();

    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/admin/views/FMShortocde.php";
    $this->view = new FMViewFMShortocde();
  }

  /**
   * Execute.
   */
  public function execute() {
    $task = WDW_FM_Library(self::PLUGIN)->get('task', 'forms', 'sanitize_key');
    $this->display($task);
  }

  /**
   * Display.
   *
   * @param string $task
   */
  public function display( $task = '' ) {
    // Get forms.
    $forms = $this->model->get_form_data();
    if ( method_exists($this->view, $task) ) {
      $this->view->$task($forms);
    }
    else {
      $this->view->forms($forms);
    }
  }
}
