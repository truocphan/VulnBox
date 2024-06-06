<?php
namespace StartklarElmentorFormsExtWidgets;
use Elementor\Controls_Manager;
use ElementorPro\Modules\Forms\Classes;
use ElementorPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class StartklarHoneyPotFormField
 * @package StartklarElmentorFormsExtWidgets
 */
class StartklarHoneyPotFormField extends \ElementorPro\Modules\Forms\Fields\Field_Base
{

    public $settings_names =  ["fields_names"];


    /**
     * StartklarHoneyPotFormField constructor.
     */
    public function __construct() {
        parent::__construct();
        load_theme_textdomain( 'startklar-elmentor-forms-extwidgets', __DIR__. '/../lang' );
        add_action( 'wp_footer', [ $this, 'drawStartklarHoneyPotJsScript' ] );
        add_action( 'wp_head', [ $this, 'StartklarHoneyPotStyles']);
        parent::__construct();
    }


    /**
     * @return string
     */
    public function get_type()
    {
        return 'startklar_hp_form_field';
    }


    /**
     * @return string|void
     */
    public function get_name()
    {
        return __('Startklar HoneyPot', 'startklar-elmentor-forms-extwidgets');
    }


    /**
     * @param $item
     * @param $item_index
     * @param $form
     */
    public function render($item, $item_index, $form ) {

        $settings = $form->get_settings_for_display();
        $settings = $settings["form_fields"][$item_index];

        echo <<<EOT
        
        <input type="text" value="" required="required"  name="form_fields[{$item['custom_id']}]"  />
EOT;


	}


    /**
     *
     */
    public function StartklarHoneyPotStyles(){
        echo <<<EOT
        <style>
            .elementor-field-type-startklar_hp_form_field { height: 1px !important; width: 1px !important; position: relative; overflow: hidden;
                        padding: 0px !important; margin: 0px !important;}  

        </style>
EOT;
    }


    /**
     * @param $field
     * @param Classes\Form_Record $record
     * @param Classes\Ajax_Handler $ajax_handler
     */
    public function validation($field, Classes\Form_Record $record, Classes\Ajax_Handler $ajax_handler  ) {
        $id = $field['id'];
        if ( isset($field['raw_value']) &&  !empty($field['raw_value'])) {
            $ajax_handler->add_error($id, __('This signature field is required.', 'startklar-elmentor-forms-extwidgets'));
        }else{
            //If success - remove the field form list (don't send it in emails and etc )
            $record->remove_field( $field['id'] );
        }
    }



    public function drawStartklarHoneyPotJsScript(){
        ?>
        <script>
            testStartklarHoneyPotJQueryExist();
            function testStartklarHoneyPotJQueryExist() {
                if (window.jQuery) {
                    jQuery('.elementor-field-type-startklar_hp_form_field  input').removeAttr('required');
                }else{
                    setTimeout(testStartklarHoneyPotJQueryExist, 100);
                }
            }
        </script> <?php

    }
}