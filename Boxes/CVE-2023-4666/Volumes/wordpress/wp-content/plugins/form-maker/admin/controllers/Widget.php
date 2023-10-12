<?php

/**
 * Class FMControllerWidget
 */
class FMControllerWidget extends WP_Widget {
  /**
   * PLUGIN = 2 points to Contact Form Maker
   */
  const PLUGIN = 1;

  private $view;
  private $model;

  public function __construct() {
    $widget_ops = array(
      'classname' => 'form_maker_widget',
      'description' => sprintf(__('Add %s widget.', WDFMInstance(self::PLUGIN)->prefix), WDFMInstance(self::PLUGIN)->nicename),
    );
    // Widget Control Settings.
    $control_ops = array( 'id_base' => 'form_maker_widget' );
    // Create the widget.
    parent::__construct('form_maker_widget', WDFMInstance(self::PLUGIN)->nicename, $widget_ops, $control_ops);
    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/admin/models/Widget.php";
    $this->model = new FMModelWidget();
    require_once WDFMInstance(self::PLUGIN)->plugin_dir . "/admin/views/Widget.php";
    $this->view = new FMViewWidget($this->model);
  }

  /**
   * Widget.
   *
   * @param array $args
   * @param array $instance
   */
  public function widget( $args = array(), $instance = array() ) {
    if( get_the_title() == 'Preview' && get_post_type() == 'form-maker' . WDFMInstance(self::PLUGIN)->plugin_postfix ) {
      return;
    }
    $contact_form_forms = explode(',', get_option('contact_form_forms'));

    $instance['title'] = isset($instance['title']) ? $instance['title'] : '';
    $instance['form_id'] = isset($instance['form_id']) ? $instance['form_id'] : 0;

    if ( !WDFMInstance(self::PLUGIN)->is_free || !in_array($instance['form_id'], $contact_form_forms) ) {
      if ( class_exists('WDFM') ) {
        require_once(WDFMInstance(self::PLUGIN)->plugin_dir . '/frontend/controllers/form_maker.php');
        $controller_class = 'FMControllerForm_maker';
      }
      else {
        return;
      }
    }
    else {
      if ( class_exists('WDCFM') ) {
        require_once(WDFMInstance(2)->plugin_dir . '/frontend/controllers/form_maker.php');
        $controller_class = 'FMControllerForm_maker_fmc';
      }
      else {
        return;
      }
    }
    $controller = new $controller_class();
    $execute = $controller->execute($instance['form_id']);
    $this->view->widget($args, $instance, $execute);
  }

  /**
   * Form.
   *
   * @param array $instance
   */
  public function form( $instance = array() ) {
    $ids_FM = $this->model->get_gallery_rows_data(); // ids_Form_Maker
    $this->view->form($instance, $ids_FM, parent::get_field_id('title'), parent::get_field_name('title'), parent::get_field_id('form_id'), parent::get_field_name('form_id'));
  }

  /**
   * Update.
   *
   * @param $new_instance
   * @param $old_instance
   * @return mixed
   */
  public function update( $new_instance = array(), $old_instance = array() ) {
    $instance['title'] = isset($new_instance['title']) ? $new_instance['title'] : '';
    $instance['form_id'] = isset($new_instance['form_id']) ? $new_instance['form_id'] : 0;

    return $instance;
  }
}
