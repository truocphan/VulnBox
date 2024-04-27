/**
 * PPOM Conditional Version 2
 * More Fast and Optimized
 * April, 2020 in LockedDown (CORVID-19)
 * */

var ppom_hidden_fields = [];

jQuery(function($) {

    setTimeout(function() {
        $('form.cart').find('select option:selected, input[type="radio"]:checked, input[type="checkbox"]:checked').each(function(i, field) {

            if ($(field).closest('div.ppom-field-wrapper').hasClass('ppom-c-hide')) return;

            const data_name = $(field).data('data_name');
            ppom_check_conditions(data_name, function(element_dataname, event_type) {
            // console.log(data_name, event_type);
                $.event.trigger({
                    type: event_type,
                    field: element_dataname,
                    time: new Date()
                });
            });
        });

        $('form.cart').find('div.ppom-c-show').each(function(i, field) {

            const data_name = $(field).data('data_name');
            ppom_check_conditions(data_name, function(element_dataname, event_type) {
                $.event.trigger({
                    type: event_type,
                    field: element_dataname,
                    time: new Date()
                });
            });
        });
        
        $('form.cart').find('div.ppom-c-hide').each(function(i, field) {
            const data_name = $(field).data('data_name');
            $.event.trigger({
                    type: 'ppom_field_hidden',
                    field: data_name,
                    time: new Date()
                });
        });

    }, 100);

    // $('form.cart').on('change', 'select, input[type="radio"], input[type="checkbox"]', function(ev) {

    $(".ppom-wrapper").on('change', 'select,input:radio,input:checkbox', function(e) {

        let value = null;
        if (($(this).is(':radio') || $(this).is(':checkbox'))) {
            value = this.checked ? $(this).val() : null;
        }
        else {

            value = $(this).val();
        }

        const data_name = $(this).data('data_name');
        // console.log("Checking condition for ", data_name);

        ppom_check_conditions(data_name, function(element_dataname, event_type) {
            // console.log(`${element_dataname} ===> ${event_type}`);
            $.event.trigger({
                type: event_type,
                field: element_dataname,
                time: new Date()
            });
        });
    });

    $(document).on('ppom_hidden_fields_updated', function(e) {
        ppom_fields_hidden_conditionally();

        // $("#conditionally_hidden").val(ppom_hidden_fields);
        // console.log(` hiddend field updated ==> ${e.field}`);
        // $("#conditionally_hidden").val(ppom_hidden_fields);
        // ppom_update_option_prices();
    });


    $(document).on('ppom_field_hidden', function(e) {
        
        // console.log(e.field)

        var element_type = ppom_get_field_type_by_id(e.field);
        switch (element_type) {

            case 'select':
                $('select[name="ppom[fields][' + e.field + ']"]').val('');
                break;

            case 'multiple_select':

                var selector = $('select[name="ppom[fields][' + e.field + '][]"]');
                var selected_value = selector.val();
                var selected_options = selector.find('option:selected');

                jQuery.each(selected_options, function(index, default_selected) {

                    var option_id = jQuery(default_selected).attr('data-option_id');
                    var the_id = 'ppom-multipleselect-' + e.field + '-' + option_id;

                    $("#" + the_id).remove();
                });

                if (selected_value) {

                    $('select[name="ppom[fields][' + e.field + '][]"]').val(null).trigger("change");
                }

                break;

            case 'checkbox':
                $('input[name="ppom[fields][' + e.field + '][]"]').prop('checked', false);
                break;
                
            case 'radio':
                $('input[name="ppom[fields][' + e.field + ']"]').prop('checked', false);
                break;

            case 'file':
                $('#filelist-' + e.field).find('.u_i_c_box').remove();
                break;

            case 'palettes':
            case 'image':
                $('input[name="ppom[fields][' + e.field + '][]"]').prop('checked', false);
                break;

            case 'imageselect':
                var the_id = 'ppom-imageselect' + e.field;
                $("#" + the_id).remove();
                break;

            case 'quantityoption':
                $('#' + e.field).val('');
                var the_id = 'ppom-quantityoption-rm' + e.field;
                $("#" + the_id).remove();
                break;

            case 'pricematrix':
                $(`input[data-dataname="ppom[fields][${e.field}]"]`).removeClass('active');
                break;
                
            case 'quantities':
                $(`input[name^="ppom[fields][${e.field}]"]`).val('');
                break;


            default:
                // Reset text/textarea/date/email etc types
                $('#' + e.field).val('');
                break;
        }

        $.event.trigger({
            type: "ppom_hidden_fields_updated",
            field: e.field,
            time: new Date()
        });

        ppom_check_conditions(e.field, function(element_dataname, event_type) {
            // console.log(`${element_dataname} ===> ${event_type}`);
            $.event.trigger({
                type: event_type,
                field: element_dataname,
                time: new Date()
            });
        });
    });

    /*$(document).on('ppom_field_shown', function(e) {

        console.log(`shown event ${e.field}`);
        ppom_check_conditions(e.field);
    });*/

    $(document).on('ppom_field_shown', function(e) {

        ppom_fields_hidden_conditionally();
        
        // Set checked/selected again
        ppom_set_default_option(e.field);

        ppom_check_conditions(e.field, function(element_dataname, event_type) {
            // console.log(`${element_dataname} ===> ${event_type}`);
            $.event.trigger({
                type: event_type,
                field: element_dataname,
                time: new Date()
            });
        });


        var field_meta = ppom_get_field_meta_by_id(e.field);

        // Apply FileAPI to DOM
        // PPOM version 22.0 has issue, commenting it so far by Najeeb April 4, 2021
        // if (field_meta.type === 'file' || field_meta.type === 'cropper') {
        //     ppom_setup_file_upload_input(field_meta);
        // }

        // Price Matrix
        if (field_meta.type == 'pricematrix') {
            // Resettin
            $(".ppom_pricematrix").removeClass('active');

            // Set Active
            var classname = "." + field_meta.data_name;
            // console.log(field_meta.data_name, jQuery(`input[data-dataname="ppom[fields][${field_meta.data_name}]"]`));
            jQuery(`input[data-dataname="ppom[fields][${field_meta.data_name}]"]`).addClass('active')
            // $(classname).find('.ppom_pricematrix').addClass('active')
        }

        //Imageselect (Image dropdown)
        if (field_meta.type === 'imageselect') {

            var dd_selector = 'ppom_imageselect_' + field_meta.data_name;
            var ddData = $('#' + dd_selector).data('ddslick');
            var image_replace = $('#' + dd_selector).attr('data-enable-rpimg');
            ppom_create_hidden_input(ddData);
            ppom_update_option_prices();
            setTimeout(function() {
                ppom_image_selection(ddData, image_replace);
            }, 100);
            // $('#'+dd_selector).ddslick('select', {index: 0 });
        }


        // Multiple Select Addon
        if (field_meta.type === 'multiple_select') {

            var selector = jQuery('select[name="ppom[fields][' + field_meta.data_name + '][]"]');
            var selected_value = selector.val();
            var default_value = field_meta.selected;

            if (selected_value === null && default_value) {

                var selected_opt_arr = default_value.split(',');

                selector.val(selected_opt_arr).trigger('change');

                var selected_options = selector.find('option:selected');
                jQuery.each(selected_options, function(index, default_selected) {

                    var option_id = jQuery(default_selected).attr('data-option_id');
                    var option_label = jQuery(default_selected).attr('data-optionlabel');
                    var option_price = jQuery(default_selected).attr('data-optionprice');

                    ppom_multiple_select_create_hidden_input(field_meta.data_name, option_id, option_price, option_label, field_meta.title);
                });
            }
        }

    });

    ppom_fields_hidden_conditionally();

});

function ppom_check_conditions(data_name, callback) {

    let is_matched = false;
    const ppom_type = jQuery(`.ppom-input[data-data_name="${data_name}"]`).data('type');
    let event_type, element_data_name;
    const field_val = ppom_get_element_value(data_name);
    // console.log('data_name',data_name);
    jQuery(`div.ppom-cond-${data_name}`).each(function() {
        // return this.data('cond-val1').match(/\w*-Back/); 
        // console.log(jQuery(this));
        const total_cond = parseInt(jQuery(this).data('cond-total'));
        const binding = jQuery(this).data(`cond-bind`);
        const visibility = jQuery(this).data(`cond-visibility`);
        element_data_name = jQuery(this).data('data_name');

        let matched = 0;
        var matched_conditions = [];
        for (var t = 1; t <= total_cond; t++) {

            const cond_element = jQuery(this).data(`cond-input${t}`);
            const cond_val = jQuery(this).data(`cond-val${t}`).toString();
            const operator = jQuery(this).data(`cond-operator${t}`);

            // const field_val = ppom_get_field_type(field_obj);
            if (cond_element !== data_name) continue;
            is_matched = ppom_compare_values(field_val, cond_val, operator);
            // console.log(`${data_name} TRIGGERS :: ${t} ***** ${element_data_name} ==> field value ${field_val} || cond_valu ${cond_val} || operator ${operator} || Binding ${binding} is_matched=>${is_matched}`);
            // console.log(field_val,cond_val);
            matched = is_matched ? ++matched : matched;
            matched_conditions[element_data_name] = matched;
            
            event_type = visibility === 'hide' ? 'ppom_field_hidden' : 'ppom_field_shown';
            // console.log(`${t} ***** ${element_data_name} total_cond ${total_cond} == matched ${matched} ==> ${matched_conditions[element_data_name]} ==> visibility ${event_type}`);

            if ( (matched_conditions[element_data_name] > 0 && binding === 'Any') ||
                 (matched_conditions[element_data_name] == total_cond && binding === 'All')
                ) {
                
                if( visibility === 'hide' ){
                    jQuery(this).addClass(`ppom-locked-${data_name} ppom-c-hide`).removeClass('ppom-c-show');
                }else{
                    jQuery(this).removeClass(`ppom-locked-${data_name} ppom-c-hide`);   
                }
                if (typeof callback == "function")
                    callback(element_data_name, event_type);
                // return is_matched;

                
            }
            else if ( ! is_matched ) {
                
                if( visibility === 'hide' ){
                    event_type = 'ppom_field_shown';
                    jQuery(this).removeClass(`ppom-locked-${data_name} ppom-c-hide`);   
                }else{
                    event_type = 'ppom_field_hidden';
                    jQuery(this).addClass(`ppom-locked-${data_name} ppom-c-hide`);
                }

                if (typeof callback == "function")
                    callback(element_data_name, event_type);
            } else {
                
                jQuery(this).removeClass(`ppom-locked-${data_name} ppom-c-hide`);
                // console.log('event_type', event_type);
                if (typeof callback == "function")
                    callback(element_data_name, event_type);
            }
        }

        // return is_matched;
        // return jQuery(this).data('cond-val1') === jQuery(this).val();
    });
}

function ppom_get_input_dom_type(data_name) {

    // const field_obj = jQuery(`input[name="ppom[fields][${data_name}]"], input[name="ppom[fields][${data_name}[]]"], select[name="ppom[fields][${data_name}]"]`);
    const field_obj = jQuery(`.ppom-input[data-data_name="${data_name}"]`);
    const ppom_type = field_obj.closest('.ppom-field-wrapper').data('type');
    return ppom_type;
}

function ppom_get_element_value(data_name) {

    const ppom_type = ppom_get_input_dom_type(data_name);
    let element_value = '';
    var value_found_cb = [];
    
    switch (ppom_type) {
        case 'switcher':
        case 'radio':
            element_value = jQuery(`.ppom-input[data-data_name="${data_name}"]:checked`).val();
            break;
        case 'palettes':
        case 'checkbox':
            jQuery('input[name="ppom[fields][' + data_name + '][]"]:checked').each(function(i) {
                value_found_cb[i] = jQuery(this).val();
            });
            break;
        case 'image':
            element_value = jQuery(`.ppom-input[data-data_name="${data_name}"]:checked`).data('label');
            break;
        case 'imageselect':
            element_value = jQuery(`.ppom-input[data-data_name="${data_name}"]:checked`).data('label');
            break;
        case 'fixedprice':
            var render_type = jQuery(`.ppom-input-${data_name}`).attr('data-input');
            if( render_type == 'radio' ){
                element_value = jQuery(`.ppom-input[data-data_name="${data_name}"]:checked`).val();
            }else{
                element_value = jQuery(`.ppom-input[data-data_name="${data_name}"]`).val();
            }
            break;

        default:
            element_value = jQuery(`.ppom-input[data-data_name="${data_name}"]`).val();
    }
    
    if (ppom_type === 'checkbox' || ppom_type === 'palettes') {
    // console.log(value_found_cb);
        return value_found_cb;
    }

    return element_value;
}

function ppom_compare_values(v1, v2, operator) {

    let result = false;
    switch (operator) {
        case 'is':
            if( Array.isArray(v1) ) {
                result = jQuery.inArray(v2, v1) !== -1 ? true : false;
            }else{
                result = v1 === v2 ? true : false;
            }
            break;
        case 'not':
            result = v1 !== v2 ? true : false;
            break;

        case 'greater than':
            result = parseFloat(v1) > parseFloat(v2) ? true : false;
            break;
        case 'less than':
            result = parseFloat(v1) < parseFloat(v2) ? true : false;
            break;

        default:
            // code
    }

    // console.log(`matching ${v1} ${operator} ${v2}`);
    return result;
}

function ppom_set_default_option(field_id) {
    
    // get product id
    var product_id = ppom_input_vars.product_id;

    var field = ppom_get_field_meta_by_id(field_id);
    
    switch (field.type) {

        // Check if field is 
        case 'switcher':
        case 'radio':
            jQuery.each(field.options, function(label, options) {
                var opt_id = product_id + '-' + field.data_name + '-' + options.id;
                // console.log('optio nid ', opt_id);

                if (options.option == field.selected) {
                    jQuery("#" + opt_id).prop('checked', true).trigger('change');
                }
            });
        break;

        case 'select':
            jQuery("#" + field.data_name).val(field.selected);
            break;

        case 'image':
            jQuery.each(field.images, function(index, img) {

                if (img.title == field.selected) {
                    jQuery("#" + field.data_name + '-' + img.id).prop('checked', true);
                }
            });
            break;

        case 'checkbox':
            jQuery.each(field.options, function(label, options) {
                var opt_id = product_id + '-' + field.data_name + '-' + options.id;

                var default_checked = field.checked.split('\r\n');
                if (jQuery.inArray(options.option, default_checked) > -1) {
                    jQuery("#" + opt_id).prop('checked', true);

                }
            });
            break;
            
        case 'quantities':
            jQuery.each(field.options, function(label, options) {
                //console.log(options);
                if( options.default === '' ) return;
                var opt_id = product_id + '-' + field.data_name + '-' + options.id;
                jQuery("#" + opt_id).val(options.default).trigger('change');
                
            });
            break;

        case 'text':
        case 'date':
        case 'number':
            jQuery("#" + field.data_name).val(field.default_value);
            break;
    }
}

// Updating conditionally hidden fields
function ppom_fields_hidden_conditionally() {

    // Reset 
    ppom_hidden_fields = [];
    // jQuery(`.ppom-field-wrapper.ppom-c-hide`).filter(function() {

    //     const data_name = jQuery(this).data('data_name');
    //     jQuery(`#${data_name}`).prop('required', false);
    //     // console.log(data_name);
    //     ppom_hidden_fields.push(data_name);
    // });
    // console.log("Condionally Hidden", ppom_hidden_fields);
    // jQuery("#conditionally_hidden").val(ppom_hidden_fields);
    
    var datanames = jQuery(`.ppom-field-wrapper[class*="ppom-locked-"]`).map( (i,h) => ppom_hidden_fields.push(jQuery(h).data('data_name')) ); 
    jQuery("#conditionally_hidden").val(ppom_hidden_fields);
    // console.log(ppom_hidden_fields);
}