(function ($) {
    "use strict";

    var ajaxUrl = hashform_admin_js_obj.ajax_url;
    var adminNonce = hashform_admin_js_obj.ajax_nonce;

    $('.hf-color-picker').wpColorPicker({
        change: function (event, ui) {
            var element = $(event.target).closest('.wp-picker-input-wrap').find('.wp-color-picker');
            if (element) {
                setTimeout(function () {
                    element.trigger('change');
                }, 100);
            }
        },
        clear: function (event) {
            var element = $(event.target).closest('.wp-picker-input-wrap').find('.wp-color-picker');
            if (element) {
                setTimeout(function () {
                    element.trigger('change');
                }, 100);
            }
        }
    });

    // Call all the necessary functions for Icon Picker
    $('body').on('click', '.hf-icon-box-wrap .hf-icon-list li', function () {
        var icon_class = $(this).find('i').attr('class');
        $(this).closest('.hf-icon-box').find('.hf-icon-list li').removeClass('icon-active');
        $(this).addClass('icon-active');
        $(this).closest('.hf-icon-box').prev('.hf-selected-icon').children('i').attr('class', '').addClass(icon_class);
        $(this).closest('.hf-icon-box').next('input').val(icon_class).trigger('change');
        $(this).closest('.hf-icon-box').slideUp();
    });

    $('body').on('click', '.hf-icon-box-wrap .hf-selected-icon', function () {
        $(this).next().slideToggle();
    });

    $('body').on('change', '.hf-icon-box-wrap .hf-icon-search select', function () {
        var selected = $(this).val();
        $(this).parents('.hf-icon-box').find('.hf-icon-search-input').val('');
        $(this).parents('.hf-icon-box').children('.hf-icon-list').hide().removeClass('active');
        $(this).parents('.hf-icon-box').children('.' + selected).fadeIn().addClass('active');
        $(this).parents('.hf-icon-box').children('.' + selected).find('li').show();
    });

    $('body').on('keyup', '.hf-icon-box-wrap .hf-icon-search input', function (e) {
        var $input = $(this);
        var keyword = $input.val().toLowerCase();
        var search_criteria = $input.closest('.hf-icon-box').find('.hf-icon-list.active i');
        delay(function () {
            $(search_criteria).each(function () {
                if ($(this).attr('class').indexOf(keyword) > -1) {
                    $(this).parent().show();
                } else {
                    $(this).parent().hide();
                }
            });
        }, 500);
    });

    var delay = (function () {
        var timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

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
                $(this).next().val(ui.value).trigger('change');
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

    // Show/ Hide Single Page Options
    $(document).on('change', '.hf-typography-font-family', function () {
        var $this = $(this);
        var font_family = $(this).val();
        var standard_fonts = ['Default', 'Helvetica', 'Verdana', 'Arial', 'Times', 'Georgia', 'Courier', 'Trebuchet', 'Tahoma', 'Palatino'];
        if (!standard_fonts.includes(font_family)) {
            var fontId = $this.attr('id');
            var $fontId = $('link#' + fontId);

            if ($fontId.length > 0) {
                $fontId.remove();
            }
            $('head').append('<link rel="stylesheet" id="' + fontId + '" href="https://fonts.googleapis.com/css?family=' + font_family + ':100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&subset=latin,latin-ext&display=swap" type="text/css" media="all">');
        }
        $.ajax({
            url: ajaxUrl,
            data: {
                action: 'hashform_get_google_font_variants',
                font_family: font_family,
                wp_nonce: adminNonce
            },
            beforeSend: function () {
                $this.closest('.hf-typography-font-family-field').next('.hf-typography-font-style-field').addClass('hf-typography-loading');
            },
            success: function (response) {
                $this.closest('.hf-typography-font-family-field').next('.hf-typography-font-style-field').removeClass('hf-typography-loading');
                $this.closest('.hf-typography-font-family-field').next('.hf-typography-font-style-field').find('select').html(response).trigger("chosen:updated").trigger('change');
            }
        });
    });

    $('body').find(".hf-typography-fields select").chosen({width: "100%"});

    $('.hf-style-sidebar [name]').on('change', function () {
        var id = $(this).attr('id');
        if (id) {
            var to = $(this).val();
            var unit = $(this).attr('data-unit');
            unit = (unit === undefined) ? '' : unit;

            if ($(this).attr('data-style')) {
                var weight = to.replace(/\D/g, '');
                var eid = id.replace('style', 'weight');
                var css = '--' + eid + ':' + weight + ';';

                var style = to.replace(/\d+/g, '');
                if ('' == style) {
                    style = 'normal';
                }
                css += '--' + id + ':' + style + '}';
            } else {
                var css = '--' + id + ':' + to + unit + '}';
            }
            hfDynamicCss(id, css, to);
        }
    });

    $('body').on('click', '.hf-setting-tab li', function () {
        // Add and remove the class for active tab
        $(this).closest('.hf-tab-container').find('.hf-setting-tab li').removeClass('hf-tab-active');
        $(this).addClass('hf-tab-active');

        var selected_menu = $(this).attr('data-tab');

        $(this).closest('.hf-tab-container').find('.hf-tab-content').hide();

        // Display The Clicked Tab Content
        $(this).closest('.hf-tab-container').find('.' + selected_menu).show();


    });

    $('body').on('click', '.hf-settings-heading', function () {
        if ($(this).hasClass('hf-active'))
            return;
        $(this).siblings('.hf-form-settings').slideUp();
        $(this).siblings('.hf-settings-heading').removeClass('hf-active');
        $(this).addClass('hf-active');
        $(this).next('.hf-form-settings').slideToggle();
    });

    // Linked button
    $('.hf-linked').on('click', function () {
        $(this).closest('.hf-unit-fields').addClass('hf-not-linked');
    });

    // Unlinked button
    $('.hf-unlinked').on('click', function () {
        $(this).closest('.hf-unit-fields').removeClass('hf-not-linked');
    });

    // Values linked inputs
    $('.hf-unit-fields input').on('input', function () {
        var $val = $(this).val();
        $(this).closest('.hf-unit-fields:not(.hf-not-linked)').find('input').each(function (key, value) {
            $(this).val($val).change();
        });
    });

    $('#hf-template-preview-form-id').on('change', function () {
        const formId = $(this).val();
        const templateId = $('#post_ID').val();
        $('.hf-form-wrap').html('');
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action: 'hashform_template_style_preview',
                form_id: formId,
                template_id: templateId
            },
            dataType: "html",
            success: function (data) {
                if (formId) {
                    data = data.replace('hf-container-' + formId, 'hf-container-00');
                }
                $('.hf-form-wrap').html(data);
            }
        });
    })

    // Custom File Upload
    $(".hf-dropzone").change(function () {
        var $input = $(this);
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var htmlPreview = '<p>' + input.files[0].name + '</p>';
                var wrapperZone = $input.parent();
                var previewZone = $input.parent().parent().find('.hf-preview-zone');
                var boxZone = $input.closest('form').find('.hf-box-body');

                wrapperZone.removeClass('dragover');
                previewZone.removeClass('hidden');
                boxZone.empty();
                boxZone.append(htmlPreview);
            };

            reader.readAsDataURL(input.files[0]);
        }
    });

    $('.hf-dropzone-wrapper').on('dragover', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('dragover');
    });

    $('.hf-dropzone-wrapper').on('dragleave', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
    });

    $('.hf-remove-preview').on('click', function () {
        try {
            var boxZone = $(this).parents('.hf-preview-zone').find('.box-body');
            var previewZone = $(this).parents('.hf-preview-zone');
            var dropzone = $(this).parents('.hf-preview-zone').siblings('.hf-dropzone-wrapper').find('.hf-dropzone');
            boxZone.empty();
            previewZone.addClass('hidden');
            dropzone.wrap('<form>').closest('form').get(0).reset();
            dropzone.unwrap();
        } catch (err) {
            console.log(err)
        }

    });

    $('body').on('click', '#hf-copy-shortcode', function () {
        if ($(this).closest('#hf-add-template').hasClass('hf-success')) {
            return false;
        }
        var textToCopy = $(this).prev('input').val();
        var tempTextarea = $('<textarea>');
        var successDiv = $(this).closest('#hf-add-template');
        $('body').append(tempTextarea);
        tempTextarea.val(textToCopy).select();
        document.execCommand('copy');
        tempTextarea.remove();
        successDiv.addClass('hf-success');
        setTimeout(function () {
            successDiv.removeClass('hf-success');
        }, 3000)
    });

    $('.hf-activate-wp-mail-smtp-plugin').on('click', function (e) {
        e.preventDefault();
        var button = $(this);
        button.addClass('updating-message').html(hashform_admin_js_obj.activating_text);

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'hashform_activate_plugin',
                slug: 'wp-mail-smtp',
                file: 'wp_mail_smtp'
            },
        }).done(function (result) {
            var result = JSON.parse(result)
            if (result.success) {
                location.reload();
            } else {
                button.removeClass('updating-message').html(hashform_admin_js_obj.error);
            }

        });
    });

    $('.hf-install-wp-mail-smtp-plugin').on('click', function (e) {
        e.preventDefault();
        var button = $(this);

        button.addClass('updating-message').html(hashform_admin_js_obj.installing_text);

        wp.updates.installPlugin({
            slug: 'wp-mail-smtp'
        }).done(function () {
            button.html(hashform_admin_js_obj.activating_text);
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'hashform_activate_plugin',
                    slug: 'wp-mail-smtp',
                    file: 'wp_mail_smtp'
                },
            }).done(function (result) {
                var result = JSON.parse(result)
                if (result.success) {
                    location.reload();
                } else {
                    button.removeClass('updating-message').html(hashform_admin_js_obj.error);
                }

            });
        });
    });

    $(document).ready(function () {
        setTimeout(function () {
            jQuery('.hf-settings-updated').fadeOut('slow', function () {
                this.parentNode.removeChild(this);
            });
        }, 3000);
    });

    $(".hf-field-content input, .hf-field-content select, .hf-field-content textarea").on('focus', function () {
        $(this).parent().addClass('hf-field-focussed');
    }).on('focusout', function () {
        $(this).parent().removeClass('hf-field-focussed');
    })
})(jQuery);

function hfDynamicCss(control, style, val) {
    ctrlEscaped = control.replaceAll('(', '\\(').replaceAll(')', '\\)');
    jQuery('style.' + ctrlEscaped).remove();
    if (val) {
        //console.log(style);
        jQuery('head').append('<style class="' + control + '">body #hf-container-00{' + style + '}</style>');
    }
}