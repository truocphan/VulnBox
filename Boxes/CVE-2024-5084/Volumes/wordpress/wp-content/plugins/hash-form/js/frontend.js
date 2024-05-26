jQuery(function ($) {

    'use strict';

    $(document).on('submit.hashform-form', '.hashform-form', function (e) {
        e.preventDefault();
        var form = $(this);

        if (form.find('button.hf-submit-button').hasClass('hf-button-loading')) {
            return;
        } else {
            form.find('button.hf-submit-button').addClass('hf-button-loading');
        }

        const siteKey = $('.g-recaptcha').attr('data-sitekey');

        const isV3 = $('.g-recaptcha').attr('data-size') == "invisible";
        isV3 && grecaptcha.ready(function () {
            grecaptcha.execute(siteKey, {action: 'hashform'}).then(function (token) {
                form.append('<input type="hidden" id="recaptcha_token" value="' + token + '">');
            });
        });

        $('.hf-error-msg').remove();
        $('.hf-success-msg').remove();
        $('.hf-failed-msg').remove();
        $(document).find('.hashform-error-container').removeClass('hashform-error-container');

        setTimeout(() => {
            var data = form.serializeArray();

            if (isV3) {
                const reCaptchaTokenValue = $(document).find('#recaptcha_token').val();
                $(document).find('#recaptcha_token').remove();
                data.forEach(function (item) {
                    if (item.name === 'g-recaptcha-response') {
                        item.value = item.value ? item.value : reCaptchaTokenValue;
                    }
                });
            }

            jQuery.ajax({
                type: 'POST',
                url: hashform_vars.ajaxurl,
                dataType: 'json',
                data: {
                    action: 'hashform_process_entry',
                    data: $.param(data)
                },
                success: function (response) {
                    form.find('button.hf-submit-button').removeClass('hf-button-loading');
                    if (response.status == "redirect") {
                        window.location.replace(response.message);
                    } else if (response.status == "success") {
                        form.trigger("reset");
                        form.find('.hf-star-rating').removeClass('hf-star-checked');
                        form.find('.hashform-range-input-selector').each(function () {
                            var newSlider = $(this);
                            var sliderValue = newSlider.val();
                            var sliderMinValue = parseFloat(newSlider.attr('min'));
                            var sliderMaxValue = parseFloat(newSlider.attr('max'));
                            var sliderStepValue = parseFloat(newSlider.attr('step'));
                            newSlider.prev('.hashform-range-slider').slider({
                                value: sliderValue,
                                min: sliderMinValue,
                                max: sliderMaxValue,
                                step: sliderStepValue,
                                range: 'min',
                                slide: function (e, ui) {
                                    $(this).next().val(ui.value);
                                }
                            });
                        });
                        $('body').find('.hf-preview-remove').trigger('click');
                        form.append('<span class="hf-success-msg">' + response.message + '</span>');
                    } else if (response.status == "failed") {
                        form.append('<span class="hf-failed-msg">' + response.message + '</span>');
                    } else {
                        $.each(response.message, function (key, value) {
                            const errorFieldId = key.replace("field", "");
                            $('#' + 'hf-field-container-' + errorFieldId).addClass('hashform-error-container').append('<span class="hf-error-msg">' + value + '</span>');
                        });

                        const firstError = Object.keys(response.message)[0];
                        const subFieldIndex = firstError.indexOf('-');
                        var firstErrorItem;

                        if (subFieldIndex > 0) {
                            const errorFieldId = firstError.substr(0, subFieldIndex).replace("field", "");
                            const subField = firstError.substr(subFieldIndex + 1, firstError.length);
                            firstErrorItem = $('#' + 'hf-subfield-container-' + subField + '-' + errorFieldId);
                        } else {
                            const errorFieldId = firstError.replace("field", "");
                            firstErrorItem = $('#' + 'hf-field-container-' + errorFieldId);
                        }

                        $('html, body').animate({
                            scrollTop: firstErrorItem.offset().top - 300
                        }, 300);
                    }
                }
            });
        }, 1000);
    });

    $(document).find(".hashform-field-type-spinner .hf-quantity .mdi-plus").click(function () {
        const parent = $(this).closest('.hashform-field-type-spinner');
        const numberInput = parent.find('input');
        const max = numberInput.attr('max');
        const numberInputVal = Number(numberInput.val());
        numberInput.val(numberInputVal < max ? numberInputVal + 1 : max);
    });

    $(document).find(".hashform-field-type-spinner .hf-quantity .mdi-minus").click(function () {
        const parent = $(this).closest('.hashform-field-type-spinner');
        const numberInput = parent.find('input');
        const min = numberInput.attr('min');
        const numberInputVal = Number(numberInput.val());
        numberInput.val(numberInputVal > min ? numberInputVal - 1 : min);
    });

    // Range JS
    $('.hashform-range-input-selector').each(function () {
        var newSlider = $(this);
        var sliderValue = newSlider.val();
        var sliderMinValue = parseFloat(newSlider.attr('min'));
        var sliderMaxValue = parseFloat(newSlider.attr('max'));
        var sliderStepValue = parseFloat(newSlider.attr('step'));

        newSlider.prev('.hashform-range-slider').slider({
            value: sliderValue,
            min: sliderMinValue,
            max: sliderMaxValue,
            step: sliderStepValue,
            range: 'min',
            slide: function (e, ui) {
                $(this).next().val(ui.value);
            }
        });
    });

    // Update slider if the input field loses focus as it's most likely changed
    $('.hashform-range-input-selector').blur(function () {
        var resetValue = isNaN($(this).val()) ? '' : $(this).val();

        if (resetValue) {
            var sliderMinValue = parseFloat($(this).attr('min'));
            var sliderMaxValue = parseFloat($(this).attr('max'));
            // Make sure our manual input value doesn't exceed the minimum & maxmium values
            if (resetValue < sliderMinValue) {
                resetValue = sliderMinValue;
                $(this).val(resetValue);
            }
            if (resetValue > sliderMaxValue) {
                resetValue = sliderMaxValue;
                $(this).val(resetValue);
            }
        }
        $(this).val(resetValue);
        $(this).prev('.hashform-range-slider').slider('value', resetValue);
    });

    function hoverStars() {
        $(this).prevAll('.hf-star-rating').addBack().addClass('hf-star-hovered');
        $(this).nextAll('.hf-star-rating').addClass('hf-star-non-hovered');
    }

    function unhoverStars() {
        $(this).closest('.hashform-star-group').find('.hf-star-rating').removeClass('hf-star-hovered hf-star-non-hovered');
    }

    function loadStars() {
        $(this).closest('.hashform-star-group').find('.hf-star-rating').removeClass('hf-star-checked');
        $(this).parent('.hf-star-rating').prevAll('.hf-star-rating').addBack().addClass('hf-star-checked');
    }

    $(document).on('click', '.hashform-star-group input', loadStars);
    $(document).on('mouseenter', '.hashform-star-group .hf-star-rating:not(.hf-star-rating-readonly)', hoverStars);
    $(document).on('mouseleave', '.hashform-star-group .hf-star-rating:not(.hf-star-rating-readonly)', unhoverStars);

    $('.hashform-field-type-date input').each(function () {
        const $this = $(this);
        const dtFormat = $this.attr('data-format');
        const dtVal = $this.val();
        if(dtVal) {
            var date = new Date(dtVal);
            $this.val(date == 'Invalid Date' ? '' : moment(date).format(dtFormat.replace("dd", "DD").replace("MM", "MMMM").replace("mm", "MM")));
        }
        $this.datepicker({
            changeMonth: true,
            dateFormat: dtFormat,
        });
    })

    $('.hashform-field-type-time').each(function () {
        var timePickerWrap = $(this).find('.hf-timepicker');
        var timePickerValueInput = $(this).find('.hf-output');
        timePickerWrap.timepicker({
            'showDuration': false,
            'timeFormat': 'g:ia',
        });
    })

    function arrayValsCompare(compareValue, arrayVals, condition) {
        var retCase = false;
        switch (condition) {
            case 'equal':
                if($.inArray(compareValue, arrayVals) !== -1) {
                    retCase = true;
                }
                break;

            case 'less_than':
                retCase = arrayVals.length > 0 ? true : false;
                $.each(arrayVals, function(index, val) {
                    if (compareValue <= val) {
                        retCase = false;
                        return false;
                    }
                })
                break;

            case 'less_than_or_equal':
                retCase = arrayVals.length > 0 ? true : false;
                $.each(arrayVals, function(index, val) {
                    if (compareValue < val) {
                        retCase = false;
                        return false;
                    }
                })
                break;

            case 'greater_than':
                retCase = arrayVals.length > 0 ? true : false;
                $.each(arrayVals, function(index, val) {
                    if (compareValue >= val) {
                        retCase = false;
                        return false;
                    }
                })
                break;

            case 'greater_than_or_equal':
                console.log(arrayVals);
                console.log(arrayVals.length);
                retCase = arrayVals.length > 0 ? true : false;
                $.each(arrayVals, function(index, val) {
                    if (compareValue > val) {
                        retCase = false;
                        return false;
                    }
                })
                break;

            case 'is_like':
                $.each(arrayVals, function(index, val) {
                    if (val.indexOf(compareValue) >= 0) {
                        retCase = true;
                    }
                })
                break;
        }
        return retCase;
    }

    $('.hashform-form-conditions').each(function () {
        const $this = $(this);
        const parentForm = $this.closest('form');
        const conditions = JSON.parse($this.val());
        $.each(conditions, function (index, val) {
            var conditionTrigger = parentForm.find('[name="item_meta[' + val.compare_to + ']');
            var isArrayVals = false;
            const actionField = parentForm.find('#hf-field-container-' + val.compare_from);
            const compareCondition = val.compare_condition;
            const compareValue = val.compare_value;
            const conditionAction = val.condition_action;

            if (!(conditionTrigger.length > 0)) {
                conditionTrigger = parentForm.find('[name="item_meta[' + val.compare_to + '][]');
                isArrayVals = true;
            }

            conditionTrigger.on('change', function () {
                var value = $(this).val();
                var selector = $(this);
                var arrayVals = [];
                if (isArrayVals) {
                    arrayVals = conditionTrigger.map(function () {
                        return $(this).is(':checked') ? $(this).val() : null;
                    }).toArray();
                }

                if ($(this).attr('type') && $(this).attr('type') == 'checkbox') {
                    if (!$(this).is(':checked')) {
                        value = '';
                    }
                }

                switch (compareCondition) {
                    case 'equal':
                        if (isArrayVals ? arrayValsCompare(compareValue, arrayVals, 'equal') : (value == compareValue)) {
                            if (actionField.length) {
                                if (conditionAction == 'show') {
                                    actionField.show();
                                } else {
                                    actionField.hide();
                                }
                            }

                        } else {
                            if (actionField.length) {
                                if (conditionAction == 'show') {
                                    actionField.hide();
                                } else {
                                    actionField.show();
                                }
                            }
                        }
                        break;

                    case 'not_equal':
                        if (!(isArrayVals ? arrayValsCompare(compareValue, arrayVals, 'equal') : (value == compareValue))) {
                            if (actionField.length) {
                                if (conditionAction == 'show') {
                                    actionField.show();
                                } else {
                                    actionField.hide();
                                }
                            }

                        } else {
                            if (actionField.length) {
                                if (conditionAction == 'show') {
                                    actionField.hide();
                                } else {
                                    actionField.show();
                                }
                            }
                        }
                        break;

                    case 'less_than':
                        value = (value == '') ? 0 : parseInt(value);
                        if (isArrayVals ? arrayValsCompare(compareValue, arrayVals, 'less_than') : (value < compareValue)) {
                            if (actionField.length) {
                                if (conditionAction == 'show') {
                                    actionField.show();
                                } else {
                                    actionField.hide();
                                }
                            }

                        } else {
                            if (actionField.length) {
                                if (conditionAction == 'show') {
                                    actionField.hide();
                                } else {
                                    actionField.show();
                                }
                            }
                        }
                        break;

                    case 'less_than_or_equal':
                        value = (value == '') ? 0 : parseInt(value);
                        if (isArrayVals ? arrayValsCompare(compareValue, arrayVals, 'less_than_or_equal') : (value <= compareValue)) {
                            if (actionField.length) {
                                if (conditionAction == 'show') {
                                    actionField.show();
                                } else {
                                    actionField.hide();
                                }
                            }

                        } else {
                            if (actionField.length) {
                                if (conditionAction == 'show') {
                                    actionField.hide();
                                } else {
                                    actionField.show();
                                }
                            }
                        }
                        break;

                    case 'greater_than':
                        value = (value == '') ? 0 : parseInt(value);
                        if (isArrayVals ? arrayValsCompare(compareValue, arrayVals, 'greater_than') : (value > compareValue)) {
                            if (actionField.length) {
                                if (conditionAction == 'show') {
                                    actionField.show();
                                } else {
                                    actionField.hide();
                                }
                            }

                        } else {
                            if (actionField.length) {
                                if (conditionAction == 'show') {
                                    actionField.hide();
                                } else {
                                    actionField.show();
                                }
                            }
                        }
                        break;

                    case 'greater_than_or_equal':
                        value = (value == '') ? 0 : parseInt(value);
                        if (isArrayVals ? arrayValsCompare(compareValue, arrayVals, 'greater_than_or_equal') : (value >= compareValue)) {
                            if (actionField.length) {
                                if (conditionAction == 'show') {
                                    actionField.show();
                                } else {
                                    actionField.hide();
                                }
                            }

                        } else {
                            if (actionField.length) {
                                if (conditionAction == 'show') {
                                    actionField.hide();
                                } else {
                                    actionField.show();
                                }
                            }
                        }
                        break;

                    case 'is_like':
                        if (isArrayVals ? arrayValsCompare(compareValue, arrayVals, 'is_like') : (value.indexOf(compareValue) >= 0)) {
                            if (actionField.length) {
                                if (conditionAction == 'show') {
                                    actionField.show();
                                } else {
                                    actionField.hide();
                                }
                            }

                        } else {
                            if (actionField.length) {
                                if (conditionAction == 'show') {
                                    actionField.hide();
                                } else {
                                    actionField.show();
                                }
                            }
                        }
                        break;

                    case 'is_not_like':
                        if (!(isArrayVals ? arrayValsCompare(compareValue, arrayVals, 'is_like') : (value.indexOf(compareValue) >= 0))) {
                            if (actionField.length) {
                                if (conditionAction == 'show') {
                                    actionField.show();
                                } else {
                                    actionField.hide();
                                }
                            }

                        } else {
                            if (actionField.length) {
                                if (conditionAction == 'show') {
                                    actionField.hide();
                                } else {
                                    actionField.show();
                                }
                            }
                        }
                        break;
                }
            }).trigger('change');
        });
    })

    $(".hf-field-content input, .hf-field-content select, .hf-field-content textarea").on('focus', function () {
        $(this).parent().addClass('hf-field-focussed');
    }).on('focusout', function () {
        $(this).parent().removeClass('hf-field-focussed');
    })

    var upload_counter = 0;
    var uploader = {};
    $('.hf-file-uploader').each(function () {
        upload_counter++;
        var attr_element_id = $(this).attr('id'),
                size = $(this).attr('data-max-upload-size'),
                limit_flag = 0,
                selector = $(this),
                uploader_label = $(this).attr('data-upload-label'),
                multiple_upload = ($(this).attr('data-multiple-uploads') == 'true') ? true : false,
                upload_limit = $(this).attr('data-multiple-uploads-limit'),
                upload_limit_message = $(this).attr('data-multiple-uploads-error-message'),
                extensions = $(this).attr('data-extensions'),
                extension_error_message = $(this).attr('data-extensions-error-message'),
                extensions_array = extensions.split(',');

        upload_limit = upload_limit < 1 ? 1 : upload_limit;

        uploader['uploader' + upload_counter] = new qq.FileUploader({
            element: document.getElementById(attr_element_id),
            action: hashform_vars.ajaxurl,
            params: {
                action: 'hashform_file_upload_action',
                file_uploader_nonce: hashform_vars.ajax_nounce,
                allowedExtensions: extensions_array,
                sizeLimit: size,
            },
            allowedExtensions: extensions_array,
            sizeLimit: size,
            minSizeLimit: 50,
            uploadButtonText: uploader_label,

            onSubmit: function (id, fileName) {
                if (multiple_upload == true && upload_limit != -1) {
                    var limit_counter = selector.parent().find('.hf-multiple-upload-limit').val();
                    limit_counter++;
                    selector.parent().find('.hf-multiple-upload-limit').val(limit_counter);
                    if (limit_counter > upload_limit) {
                        if (limit_flag == 0) {
                            upload_limit_message = (upload_limit_message != '') ? upload_limit_message : 'Maximum number of files allowed is ' + upload_limit;
                            selector.parent().find('.hf-error').html(upload_limit_message);
                            limit_flag = 1;
                        }

                        selector.parent().find('.hf-multiple-upload-limit').val(upload_limit);
                        return false;
                    }
                }
            },

            onProgress: function (id, fileName, loaded, total) {},

            onComplete: function (id, fileName, responseJSON) {

                if (responseJSON.success) {

                    $('#' + attr_element_id).closest('.hf-file-uploader-wrapper').find('.hf-error').html('');
                    var extension_array = fileName.split('.');
                    var extension = extension_array.pop();

                    if (extension == 'jpg' || extension == 'jpeg' || extension == 'png' || extension == 'gif' || extension == 'JPG' || extension == 'JPEG' || extension == 'PNG' || extension == 'GIF') {
                        var preview_img = responseJSON.url;
                    }

                    var preview_html = '<div class="hf-prev-holder" id="hf-uploaded-' + id + '">';
                    if (preview_img) {
                        preview_html += '<img src="' + preview_img + '" />';
                    }
                    preview_html += '<span class="hf-prev-name">' + fileName + '</span></div>';

                    if (multiple_upload) {
                        var url = responseJSON.url;
                        var added_url = $('#' + attr_element_id).closest('.hf-file-uploader-wrapper').find('.hf-uploaded-files').val();
                        if (added_url == '') {
                            added_url = url;
                        } else {
                            var added_url_array = added_url.split(',');
                            added_url_array.push(url);
                            added_url = added_url_array.join();
                        }

                        $('#' + attr_element_id).closest('.hf-file-uploader-wrapper').find('.hf-uploaded-files').val(added_url);
                        $('#' + attr_element_id).closest('.hf-file-uploader-wrapper').find('.hf-file-preview').append(preview_html);

                    } else {
                        $('#' + attr_element_id).closest('.hf-file-uploader-wrapper').find('.hf-uploaded-files').val(responseJSON.url);
                        $('#' + attr_element_id).closest('.hf-file-uploader-wrapper').find('.hf-file-preview').html(preview_html);
                    }

                } else {
                    console.log(responseJSON);
                }
            },

            onCancel: function (id, fileName) {},
            onError: function (id, fileName, xhr) {},

            messages: {
                typeError: extension_error_message,
                sizeError: "{file} is too large, maximum file size is {sizeLimit}.",
                minSizeError: "{file} is too small, minimum file size is {minSizeLimit}.",
                emptyError: "{file} is empty, please select files again without it.",
                onLeave: "The files are being uploaded, if you leave now the upload will be cancelled."
            },

            showMessage: function (message) {
                alert(message);
            },

            multiple: multiple_upload
        });

    });


    $('body').on('click', '.hf-preview-remove', function () {
        const selector = $(this);
        $.ajax({
            url: hashform_vars.ajaxurl,
            data: 'action=hashform_file_delete_action&path=' + selector.data('path') + '&_wpnonce=' + hashform_vars.ajax_nounce,
            type: 'post',
            success: function (res) {
                if (res == 'success') {
                    var parent_wrapper = selector.closest('.hf-file-uploader-wrapper')
                    var prev_url = parent_wrapper.find('.hf-uploaded-files').val();
                    var new_url = prev_url.replace(selector.data('url'), '');
                    new_url = new_url.replace(',,', ',');
                    parent_wrapper.find('.hf-uploaded-files').val(new_url);

                    var limit_counter = parent_wrapper.find('.hf-multiple-upload-limit').val();
                    limit_counter--;
                    limit_counter = (limit_counter < 0) ? 0 : limit_counter;
                    parent_wrapper.find('.hf-multiple-upload-limit').val(limit_counter);

                    selector.parent().fadeOut('1500', function () {
                        selector.parent().remove();
                        parent_wrapper.find('#' + selector.attr('data-remove-id')).remove();
                    });
                }
            }
        });
    });

});