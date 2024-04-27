/**
 * PPOM Validate
 * @Since version 24.2
 * By: Najeeb Ahmad
 * Date: January 19, 2022
 **/
 
/*global jQuery ppom_get_element_value ppom_input_vars*/
 
const PPOM_Validate = {
 
    field_meta: ppom_input_vars.field_meta.filter(f => f.required === "on" || f.type === 'quantities' ),
    passed: true,
    $ppom_input: jQuery('.ppom-input.ppom-required'),
    $ppom_input_texts: jQuery('.ppom-input.ppom-required.text, .ppom-input.ppom-required.email, .ppom-input.ppom-required.number,.ppom-input.ppom-required.quantityoption,.ppom-measure-input'),
    
    init : async () => {
        
        jQuery(document).bind('ppom_uploaded_file_removed', async function(e) {
            PPOM_Validate.validate_data()
            .then(PPOM_Validate.enable_button, PPOM_Validate.disable_button);
        });
        
        jQuery(document).bind('ppom_field_shown', async function(e) {
            PPOM_Validate.validate_data()
            .then(PPOM_Validate.enable_button, PPOM_Validate.disable_button);
        });
        
        jQuery(document).bind('ppom_field_hidden', async function(e) {
            PPOM_Validate.validate_data()
            .then(PPOM_Validate.enable_button, PPOM_Validate.disable_button);
        });
        
        jQuery(document).on('change', PPOM_Validate.$ppom_input,  async function(e){
            PPOM_Validate.validate_data()
            .then(PPOM_Validate.enable_button, PPOM_Validate.disable_button);
        });
        
        // keyup events for texts input e.g: text,email,number
        PPOM_Validate.$ppom_input_texts.keyup(async function(e){
            PPOM_Validate.validate_data()
            .then(PPOM_Validate.enable_button, PPOM_Validate.disable_button);
        });
        
        PPOM_Validate.validate_data()
        .then(PPOM_Validate.enable_button, PPOM_Validate.disable_button);
        
    },
    
    validate_data: () => {
        
        return new Promise( function(resolve, reject){
            
            const invalid_fields = PPOM_Validate.field_meta.filter(f => !PPOM_Validate.field_has_valid_data(f) && !PPOM_Validate.is_field_hidden(f.data_name))
            const validate_result = invalid_fields.length > 0 ? false : true;
            //console.log(invalid_fields);
            
            return validate_result ? resolve() : reject(invalid_fields);
        });
    },
    
    is_field_hidden: (data_name) => {
        
        // console.log(data_name, jQuery(`.ppom-field-wrapper.${data_name}.ppom-c-hide`).length > 0);
       return jQuery(`.ppom-field-wrapper.${data_name}.ppom-c-hide`).length > 0;
    },
    
    /**
	 * When a variation is hidden.
	 */
	disable_button: (invalid_fields) => {
		const $form = jQuery('form.cart');
		$form
			.find( '.single_add_to_cart_button' )
			.removeClass( 'wc-variation-is-unavailable' )
			.addClass( 'disabled wc-variation-selection-needed' )
			.prop('disabled', true);
		$form
			.find( '.woocommerce-variation-add-to-cart' )
			.removeClass( 'woocommerce-variation-add-to-cart-enabled' )
			.addClass( 'woocommerce-variation-add-to-cart-disabled' )
			.prop('disabled', true);
			
	   // console.log('disabled', invalid_fields);
		PPOM_Validate.show_errors(invalid_fields);
	},
	
	enable_button: () => {
		const $form = jQuery('form.cart');
		$form
			.find( '.single_add_to_cart_button' )
			.removeClass( 'disabled wc-variation-selection-needed wc-variation-is-unavailable' )
			.prop('disabled', false);
		$form
			.find( '.woocommerce-variation-add-to-cart' )
			.removeClass( 'woocommerce-variation-add-to-cart-disabled' )
			.addClass( 'woocommerce-variation-add-to-cart-enabled' )
			.prop('disabled', false);
	    
	    //hide error
	    jQuery('#ppom-error-container').html('');
	},
	
	show_errors: (invalid_fields) => {
	    //console.log(invalid_fields);
	    const $container = jQuery('#ppom-error-container').html('');
	    const $ul_container = jQuery('<ul/>').addClass('woocommerce-error').appendTo($container);
	    invalid_fields.map(f => $ul_container.append( `<li>${PPOM_Validate.get_message(f)}</li>`) );
	},
	
	get_message(field_meta){
	    
	    return field_meta.error_message !== "" ? field_meta.error_message : `<b>${field_meta.title}</b> ${ppom_input_vars.validate_msg}`;
	},
	
	field_has_valid_data(field) {
        
        const data_name = field.data_name;
        var ppom_type = PPOM_Validate.get_input_dom_type(data_name);
        if( field.type === 'imageselect' || field.type === 'quantities' || field.type === 'fonts' ) {
            ppom_type = field.type;
        }
        
        let element_value = '';
        // console.log(field, ppom_type);
        
        switch (ppom_type) {
            case 'switcher':
            case 'radio':
                element_value = jQuery(`.ppom-input[data-data_name="${data_name}"]:checked`).length;
                return element_value !== 0;
                break;
            case 'palettes':
            case 'checkbox':
                element_value = jQuery('input[name="ppom[fields][' + data_name + '][]"]:checked').length;
                if( (field.min_checked && element_value < Number(field.min_checked) ) ||
                (field.max_checked && element_value > Number(field.max_checked) )
                ){
                    // console.log('no ok');
                    return false;
                }else{
                    return element_value !== 0;
                }
                break;
            case 'quantities':
                var all_quantities = jQuery(`input.ppom-quantity[data-data_name="${data_name}"]`);
                var total_q = 0;
                jQuery.each(all_quantities, function(i, item){
                    total_q += Number(jQuery(item).val());    
                });
                if( (field.min_qty && total_q < Number(field.min_qty) ) ||
                (field.max_qty && total_q > Number(field.max_qty) )
                ){
                    return false;
                }else{
                    return true;
                }
                    
            case 'image':
                element_value = jQuery(`.ppom-input[data-data_name="${data_name}"]:checked`).length;
                if( (field.min_checked && element_value < Number(field.min_checked) ) ||
                (field.max_checked && element_value > Number(field.max_checked) )
                ){
                    // console.log('no ok');
                    return false;
                }else{
                    return element_value !== 0;
                }
                break;
            case 'imageselect':
                element_value = jQuery(`.ppom-input[data-data_name="${data_name}"]:checked`).length;
                // element_value = 0;
                return element_value !== 0;
                break;
            case 'fonts':
                element_value = jQuery(`#ppom-font-picker-${data_name}`).data('fontfamily')
                // element_value = 0;
                return element_value !== undefined;
                break;
            case 'fixedprice':
                var render_type = jQuery(`.ppom-input-${data_name}`).attr('data-input');
                if( render_type == 'radio' ){
                    element_value = jQuery(`.ppom-input[data-data_name="${data_name}"]:checked`).length;
                }else{
                    element_value = jQuery(`.ppom-input[data-data_name="${data_name}"]`).val().length;
                }
                return element_value !== 0;
                break;
            case 'measure':
                element_value = jQuery(`#${data_name}`).val() != null ? jQuery(`#${data_name}`).val().length : 0;
                return element_value !== 0;
                break;
    
            default:
                element_value = jQuery(`.ppom-input[data-data_name="${data_name}"]`).val() != null ? jQuery(`.ppom-input[data-data_name="${data_name}"]`).val().length : 0;
                return element_value !== 0;
                break;
        }
        
    },
    
    
    text_events(e) {
        console.log(e.target)        
    },
    
    get_input_dom_type(data_name) {

        // const field_obj = jQuery(`input[name="ppom[fields][${data_name}]"], input[name="ppom[fields][${data_name}[]]"], select[name="ppom[fields][${data_name}]"]`);
        const field_obj = jQuery(`.ppom-input[data-data_name="${data_name}"]`);
        const ppom_type = field_obj.closest('.ppom-field-wrapper').data('type');
        return ppom_type;
    }
	
}

PPOM_Validate.init();