<?php

class FMElementor extends \Elementor\Widget_Base {
  /**
   * PLUGIN = 2 points to Contact Form Maker
   */
  const PLUGIN = 1;

  /**
   * Get widget name.
   *
   * @return string Widget name.
   */
  public function get_name() {
    return 'fm-elementor';
  }

  /**
   * Get widget title.
   *
   * @return string Widget title.
   */
  public function get_title() {
    return __('Form Maker', WDFMInstance(self::PLUGIN)->prefix);
  }

  /**
   * Get widget icon.
   *
   * @return string Widget icon.
   */
  public function get_icon() {
    return 'twbb-form-maker twbb-widget-icon';
  }

  /**
   * Get widget categories.
   *
   * @return array Widget categories.
   */
  public function get_categories() {
    return [ 'tenweb-plugins-widgets' ];
  }

  /**
   * Register widget controls.
   */
  protected function register_controls() {
    $this->start_controls_section(
      'general',
      [
        'label' => __('Form', WDFMInstance(self::PLUGIN)->prefix),
      ]
    );
    if ( $this->get_id() !== null ) {
      $settings = $this->get_init_settings();
    }
    $fm_edit_link = add_query_arg(array( 'page' => 'manage_fm' ), admin_url('admin.php'));
    if ( isset($settings) && isset($settings["form_id"]) && intval($settings["form_id"]) > 0 ) {
      $fm_id = intval($settings["form_id"]);
      $fm_edit_link = add_query_arg(array( 'page' => 'manage_fm', 'task'=>'edit', 'current_id'=>$fm_id ), admin_url('admin.php'));
    }
    $this->add_control(
      'form_id',
      [
        'label_block' => TRUE,
        'show_label' => FALSE,
        'description' => __('Select the form to display.', WDFMInstance(self::PLUGIN)->prefix) . ' <a target="_blank" href="' . $fm_edit_link . '">' . __('Edit form', WDFMInstance(self::PLUGIN)->prefix) . '</a>',
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 0,
        'options' => WDW_FM_Library(self::PLUGIN)->get_forms(),
      ]
    );

    $this->end_controls_section();
  }

  /**
   * Render widget output on the frontend.
   */
  protected function render() {
    if ( class_exists("\Elementor\Core\Schemes\Typography")
      && method_exists("\Elementor\Core\Schemes\Typography", "get_scheme_value")
      && class_exists("\Elementor\Core\Schemes\Color")
      && method_exists("\Elementor\Core\Schemes\Color", "get_scheme")
    ) {
      $font_class = new \Elementor\Core\Schemes\Typography();
      $font = $font_class->get_scheme_value();
      $color_class = new \Elementor\Core\Schemes\Color();
      $color = $color_class->get_scheme();
      if ( isset($font[3]) && isset($font[3]["font_family"]) && isset($font[3]["font_weight"]) && isset($color[3]) && isset($color[3]["value"]) ) {
        echo '<style>.elementor-widget-container .fm-form-container, .elementor-widget-container .fm-form-container label, .elementor-widget-container .fm-form-container input, .elementor-widget-container .fm-form-container textarea, .elementor-widget-container .fm-form-container select, .elementor-widget-container .fm-form-container button, .elementor-widget-container .fm-form-container  .fm-message { font-family: ' . $font[3]["font_family"] . '; font-weight: ' . $font[3]["font_weight"] . '; color: ' . $color[3]["value"] . '}</style>';
      }
    }
    $settings = $this->get_settings_for_display();

    echo WDFMInstance(self::PLUGIN)->fm_shortcode(array('id' => $settings['form_id']));
  }
}

\Elementor\Plugin::instance()->widgets_manager->register(new FMElementor());
