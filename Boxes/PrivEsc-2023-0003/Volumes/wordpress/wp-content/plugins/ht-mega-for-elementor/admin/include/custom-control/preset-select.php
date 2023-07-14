<?php

namespace HtMega\Preset;

class Preset_Select extends \Elementor\Base_Data_Control {

    public function get_type() {
        return 'htmega-preset-select';
    }

    public function enqueue()
    {
        wp_register_script( 'htmega_preset_select', HTMEGA_ADDONS_PL_URL . 'admin/assets/js/custom-control/htmega-preset.js', ['jquery'], HTMEGA_VERSION, true );
        wp_enqueue_script( 'htmega_preset_select' );

        wp_localize_script(
            'htmega_preset_select',
            'htpreset',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce('htmega_preset_select')
            ]
        );
    }

    public function get_default_value() {
		return '1';
	}

    public function content_template() {
        $control_uid = $this->get_control_uid();
        ?>  
            <div class="elementor-label-inline">
                <div class="elementor-control-content">
                    <div class="elementor-control-field">
                        <label class="elementor-control-title">{{{ data.label }}}</label>
                        <div class="elementor-control-input-wrapper elementor-control-unit-5">
                            <select id="<?php echo $control_uid; ?>" class="preset-select" style="width:100%" data-setting="{{ data.name }}">
                                <# _.each( data.options, function( val, key ) { 
                                    if(key === data.controlValue){
                                #>
                                    <option value="{{{ key }}}" selected>{{{ val }}}</option>
                                <#}else{ #>
                                    <option value="{{{ key }}}">{{{ val }}}</option>   
                                <# } } ); #>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        <?php

    }

}