var hashFormAdmin = hashFormAdmin || {};

(function ($) {
    'use strict';
    let $buildForm = $('#hf-fields-form'),
            $formMeta = $('#hf-meta-form'),
            $formSettings = $('#hf-settings-form'),
            $styleSettings = $('#hf-style-form'),
            copyHelper = false,
            fieldsUpdated = 0;
    var isCheckedField = false;

    hashFormAdmin = {
        init: function () {
            if ($formSettings.length > 0) {
                this.initFormSettings();

            } else if ($styleSettings.length > 0) {
                this.initStyleSettings();

            } else if ($buildForm.length > 0) {
                $('.hashform-ajax-udpate-button').on('click', hashFormAdmin.submitBuild);

            } else {
                this.initOtherSettings();
            }

            hashFormAdmin.liveChanges();

            hashFormAdmin.setupFieldOptionSorting($('.hf-option-list'));

            hashFormAdmin.initBulkOptionsOverlay();

            hashFormAdmin.initNewFormModal();


            $(document).find('.hf-color-picker').wpColorPicker();

            $(document).on('click', '#hf-fields-tabs a', hashFormAdmin.clickNewTab);
            $(document).on('input', '.hf-search-fields-input', hashFormAdmin.searchContent);
            $(document).on('click', '.hf-settings-tab a', hashFormAdmin.clickNewTabSettings);

            /* Image */
            $(document).on('click', '.hf-image-preview .hf-choose-image', hashFormAdmin.addImage);
            $(document).on('click', '.hf-image-preview .hf-remove-image', hashFormAdmin.removeImage);

            /* Add field attr to form in Settings page */
            $(document).on('click', '.hf-add-field-attr-to-form li', hashFormAdmin.addFieldAttrToForm);

            /* Open/Close embed popup */
            $(document).on('click', '.hf-embed-button', function () {
                $('#hf-shortcode-form-modal').addClass('hf-open');
            });

            $(document).on('click', '.hashform-close-form-modal', function () {
                $('#hf-shortcode-form-modal').removeClass('hf-open');
            });

            $('.hf-add-more-condition').on('click', hashFormAdmin.addConditionRepeaterBlock);
            $(document).on('click', '.hf-condition-remove', hashFormAdmin.removeConditionRepeaterBlock);

            $(document).on('change', '.hf-fields-type-time .default-value-field', hashFormAdmin.addTimeDefaultValue);
            $(document).on('change', '.hf-fields-type-time .min-value-field, .hf-fields-type-time .max-value-field, .hf-fields-type-time .hf-default-value-field', hashFormAdmin.validateTimeValue);

            $('.hf-fields-type-date .hf-default-value-field').datepicker({
                changeMonth: true,
            });

            document.addEventListener(
                "hashform_added_field", (e) => {
                    if (e.hfType == 'date') {
                        $(document).find('.hf-fields-type-date .hf-default-value-field').datepicker({
                            changeMonth: true,
                        });
                    }
                }, false,
            );
        },

        clickNewTab: function () {
            var href = $(this).attr('href'),
                    $link = $(this);
            if (typeof href === 'undefined') {
                return false;
            }

            $link.closest('li').addClass('hf-active-tab').siblings('li').removeClass('hf-active-tab');
            $link.closest('.hf-fields-container').find('.ht-fields-panel').hide();
            $(href).show();
            return false;
        },

        searchContent: function () {
            var i,
                    searchText = $(this).val().toLowerCase(),
                    toSearch = $(this).attr('data-tosearch'),
                    $items = $('.' + toSearch);

            $items.each(function () {
                if ($(this).attr('id').indexOf(searchText) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        },

        clickNewTabSettings: function () {
            var id = this.getAttribute('href'),
                    $link = $(this);

            if (typeof id === 'undefined') {
                return false;
            }

            $link.closest('li').addClass('hf-active').siblings('li').removeClass('hf-active');
            $(id).removeClass('hf-hidden').siblings().addClass('hf-hidden');
            return false;
        },

        addImage: function (e) {
            e.preventDefault();
            const imagePreview = $(this).closest('.hf-image-preview');
            const fileFrame = wp.media({
                multiple: false,
                library: {
                    type: ['image']
                }
            });

            fileFrame.on('select', function () {
                const attachment = fileFrame.state().get('selection').first().toJSON();
                imagePreview.find('img').attr('src', attachment.url);
                imagePreview.find('input.hf-image-id').val(attachment.id);
                imagePreview.find('.hf-image-preview-wrap').removeClass('hf-hidden');
                imagePreview.find('.hf-choose-image').addClass('hf-hidden');

                const frontImagePreview = imagePreview.find('input.hf-image-id').attr('id');
                $('.' + frontImagePreview).append('<img src="' + attachment.url + '"/>');
                $('.' + frontImagePreview).find('.hf-no-image-field').addClass('hf-hidden');
            });
            fileFrame.open();
        },

        removeImage: function (e) {
            const imagePreview = $(this).closest('.hf-image-preview');
            e.preventDefault();
            imagePreview.find('img').attr('src', '');
            imagePreview.find('.hf-image-preview-wrap').addClass('hf-hidden');
            imagePreview.find('.hf-choose-image').removeClass('hf-hidden');
            imagePreview.find('input.hf-image-id').val('');

            const frontImagePreview = imagePreview.find('input.hf-image-id').attr('id');
            $('.' + frontImagePreview).find('.hf-no-image-field').removeClass('hf-hidden');
            $('.' + frontImagePreview).find('img').remove();
        },

        addFieldAttrToForm: function (e) {
            const fieldId = $(this).attr('data-value');
            const inputChange = $(this).closest('.hf-form-row').find('input');
            const textAreaChange = $(this).closest('.hf-form-row').find('textarea');

            if (fieldId && inputChange.length > 0) {
                inputChange.val(inputChange.val() + ' ' + fieldId);
            }

            if (fieldId && textAreaChange.length > 0) {
                textAreaChange.val(textAreaChange.val() + ' ' + fieldId);
            }
        },

        submitBuild: function (e) {
            e.preventDefault();
            var $thisEle = this;
            hashFormAdmin.preFormSave(this);
            var hashform_fields = JSON.stringify($buildForm.serializeArray());
            var hashform_settings = JSON.stringify($formMeta.serializeArray());

            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'hashform_update_form',
                    hashform_fields: hashform_fields,
                    hashform_settings: hashform_settings,
                    nonce: hashform_backend_js.nonce
                },
                success: function (msg) {
                    hashFormAdmin.afterFormSave($thisEle);
                    var $postStuff = document.getElementById('hf-form-panel');
                    var $html = document.createElement('div');
                    $html.setAttribute('class', 'hf-updated-info');
                    $html.innerHTML = msg;
                    $postStuff.insertBefore($html, $postStuff.firstChild);
                }
            });
        },

        addImageToOption: function (e) {
            e.preventDefault();
            const imagePreview = e.target.closest('li');
            const fileFrame = wp.media({
                multiple: false,
                library: {
                    type: ['image']
                }
            });

            fileFrame.on('select', function () {
                const attachment = fileFrame.state().get('selection').first().toJSON();
                const $imagePreview = $(imagePreview);
                $imagePreview.find('.hf-is-image-holder').html('<img src="' + attachment.url + '"/>');
                $imagePreview.find('.hf-is-image-preview-box').addClass('hf-image-added');

                $imagePreview.find('input.hf-image-id').val(attachment.id).trigger('change');
                var fieldId = $imagePreview.closest('.hf-fields-settings').data('fid');
                hashFormAdmin.resetDisplayedOpts(fieldId);
            });
            fileFrame.open();
        },

        removeImageFromOption: function (e) {
            var $this = $(this),
                    previewWrapper = $this.closest('li');
            e.preventDefault();
            e.stopPropagation();

            previewWrapper.find('.hf-is-image-holder').html('');
            previewWrapper.find('.hf-is-image-preview-box').removeClass('hf-image-added');
            previewWrapper.find('input.hf-image-id').val('').trigger('change');
            var fieldId = previewWrapper.closest('.hf-fields-settings').data('fid');
            hashFormAdmin.resetDisplayedOpts(fieldId);
        },

        liveChanges: function () {
            $('#hf-meta-panel').on('input', '[data-changeme]', hashFormAdmin.liveChangesInput);
            $('#hf-meta-panel').on('change', 'select[name="submit_btn_alignment"]', hashFormAdmin.liveChangeButtonPosition);

            $buildForm.on('input, change', '[data-changeme]', hashFormAdmin.liveChangesInput);

            $buildForm.on('click', 'input.hf-form-field-required', hashFormAdmin.markRequired);

            $buildForm.on('click', '.hf-add-option', hashFormAdmin.addFieldOption);
            $buildForm.on('input', '.hf-single-option input[type="text"]', hashFormAdmin.resetOptOnChange);
            $buildForm.on('mousedown', '.hf-single-option input[type=radio]', hashFormAdmin.maybeUncheckRadio);
            $buildForm.on('click', '.hf-single-option .hf-choice-input', hashFormAdmin.resetOptOnChange);
            $buildForm.on('change', '.hf-image-id', hashFormAdmin.resetOptOnChange);

            $buildForm.on('click', '.hf-single-option a[data-removeid]', hashFormAdmin.deleteFieldOption);

            $buildForm.on('click', '.hf-is-image-preview-box', hashFormAdmin.addImageToOption);
            $buildForm.on('click', '.hf-is-remove-image', hashFormAdmin.removeImageFromOption);

            $buildForm.on('input', '[data-changeheight]', hashFormAdmin.liveChangeHeight);
            $buildForm.on('input', '[data-changerows]', hashFormAdmin.liveChangeRows);
            $buildForm.on('input', '[data-changestars]', hashFormAdmin.liveChangeStars);

            $buildForm.on('change', 'select[name^="field_options[label_position"]', hashFormAdmin.liveChangeLabelPosition);
            $buildForm.on('change', 'select[name^="field_options[label_alignment"]', hashFormAdmin.liveChangeLabelAlignment);

            $buildForm.on('change', 'select[name^="field_options[options_layout"]', hashFormAdmin.liveChangeOptionsLayout);
            $buildForm.on('change', 'select[name^="field_options[heading_type"]', hashFormAdmin.liveChangeHeadingType);
            $buildForm.on('change', 'select[name^="field_options[text_alignment"]', hashFormAdmin.liveChangeTextAlignment);
            $buildForm.on('change', 'select.hf-select-image-type', hashFormAdmin.liveChangeSelectImageType);

            $buildForm.on('change', '[data-changebordertype]', hashFormAdmin.liveChangeBorderType);
            $buildForm.on('input', '[data-changeborderwidth]', hashFormAdmin.liveChangeBorderWidth);

            $buildForm.on('input', 'input[name^="field_options[field_max_width"]', hashFormAdmin.liveChangeFieldMaxWidth);
            $buildForm.on('change', 'select[name^="field_options[field_max_width_unit"]', hashFormAdmin.liveChangeFieldMaxWidth);

            $buildForm.on('input', 'input[name^="field_options[image_max_width"]', hashFormAdmin.liveChangeImageMaxWidth);
            $buildForm.on('change', 'select[name^="field_options[image_max_width_unit"]', hashFormAdmin.liveChangeImageMaxWidth);

            $buildForm.on('change click', '[data-disablefield]', hashFormAdmin.liveChangeAddressFields);

            $buildForm.on('change click', 'input[name^="field_options[auto_width"]', hashFormAdmin.liveChangeAutoWidth);

            $buildForm.on('change', 'select[name^="field_options[field_alignment"]', hashFormAdmin.liveChangeFieldAlignment);

            $buildForm.on('change', '[data-row-show-hide]', hashFormAdmin.liveChangeHideShowRow);
            $buildForm.on('input', '[data-label-show-hide]', hashFormAdmin.liveChangeHideShowLabel);
            $buildForm.on('change', '[data-label-show-hide-checkbox]', hashFormAdmin.liveChangeHideShowLabelCheckbox);
        },

        liveChangesInput: function () {
            var option,
                    newValue = this.value,
                    changes = document.getElementById(this.getAttribute('data-changeme')),
                    att = this.getAttribute('data-changeatt'),
                    fieldAttrType = this.getAttribute('type'),
                    parentField = $(changes).closest('.hf-editor-form-field');

            if (att == 'value' && fieldAttrType == "email") {
                $(this).closest('div').find('.hf-error').remove();
                if (newValue && !hashFormAdmin.isEmail(newValue)) {
                    $(this).closest('div').append('<p class="hf-error">Invalid Email Value</p>');
                }
            }

            if (att == 'value' && parentField.attr('data-type') == 'url') {
                $(this).closest('div').find('.hf-error').remove();
                if (newValue && !hashFormAdmin.isUrl(newValue)) {
                    $(this).closest('div').append('<p class="hf-error">Invalid Website/URL Value. Please add full URL value</p>');
                }
            }

            if (parentField.attr('data-type') == 'range_slider') {
                setTimeout(function () {
                    var newSlider = parentField.find('.hashform-range-input-selector');
                    var sliderValue = newSlider.val();
                    var sliderMinValue = parseFloat(newSlider.attr('min'));
                    var sliderMaxValue = parseFloat(newSlider.attr('max'));
                    var sliderStepValue = parseFloat(newSlider.attr('step'));
                    sliderValue = sliderValue < sliderMinValue ? sliderMinValue : sliderValue;
                    sliderValue = sliderValue > sliderMaxValue ? sliderMaxValue : sliderValue;
                    var remainder = sliderValue % sliderStepValue;
                    sliderValue = sliderValue - remainder;
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
                }, 100)
            }

            if (changes === null) {
                return;
            }

            if (att !== null) {
                if (changes.tagName === 'SELECT' && att === 'placeholder') {
                    option = changes.options[0];
                    if (option.value === '') {
                        option.innerHTML = newValue;
                    } else {
                        // Create a placeholder option if there are no blank values.
                        hashFormAdmin.addBlankSelectOption(changes, newValue);
                    }
                } else if (att === 'class') {
                    hashFormAdmin.changeFieldClass(changes, this);
                } else {
                    if ('TEXTAREA' === changes.nodeName && att == 'value') {
                        changes.innerHTML = newValue;
                    } else {
                        changes.setAttribute(att, newValue);
                    }
                }
            } else if (changes.id.indexOf('setup-message') === 0) {
                if (newValue !== '') {
                    changes.innerHTML = '<input type="text" value="" disabled />';
                }
            } else {
                changes.innerHTML = newValue;

                if ('TEXTAREA' === changes.nodeName && changes.classList.contains('wp-editor-area')) {
                    $(changes).trigger('change');
                }

                if (changes.classList.contains('hf-form-label') && 'break' === changes.nextElementSibling.getAttribute('data-type')) {
                    changes.nextElementSibling.querySelector('.hf-editor-submit-button').textContent = newValue;
                }
            }
        },

        liveChangeButtonPosition: function (e) {
            $('.hf-editor-submit-button-wrap').removeClass('hf-submit-btn-align-left hf-submit-btn-align-right hf-submit-btn-align-center').addClass('hf-submit-btn-align-' + e.target.value);
        },

        markRequired: function () {
            var thisid = this.id.replace('hf-', ''),
                    fieldId = thisid.replace('req-field-', ''),
                    checked = this.checked,
                    label = $('#hf-editor-field-required-' + fieldId);

            hashFormAdmin.toggleValidationBox(checked, '.hf-required-detail-' + fieldId);

            if (checked) {
                var $reqBox = $('input[name="field_options[required_indicator_' + fieldId + ']"]');
                if ($reqBox.val() === '') {
                    $reqBox.val('*');
                }
                label.removeClass('hf-hidden');
            } else {
                label.addClass('hf-hidden');
            }
        },

        //Add new option or "Other" option to radio/checkbox/dropdown
        addFieldOption: function () {
            /*jshint validthis:true */
            var fieldId = $(this).closest('.hf-fields-settings').data('fid'),
                    newOption = $('#hf-field-options-' + fieldId + ' .hf-option-template').prop('outerHTML'),
                    optType = $(this).data('opttype'),
                    optKey = 0,
                    oldKey = '000',
                    lastKey = hashFormAdmin.getHighestOptKey(fieldId);

            if (lastKey !== oldKey) {
                optKey = lastKey + 1;
            }

            //Update hidden field
            if (optType === 'other') {
                document.getElementById('other_input_' + fieldId).value = 1;

                //Hide "Add Other" option now if this is radio field
                var ftype = $(this).data('ftype');
                if (ftype === 'radio' || ftype === 'select') {
                    $(this).fadeOut('slow');
                }

                var data = {
                    action: 'hf-add-field_option',
                    field_id: fieldId,
                    opt_key: optKey,
                    opt_type: optType,
                    nonce: hashform_backend_js.nonce
                };

                jQuery.post(ajaxurl, data, function (msg) {
                    $('#hf-field-options-' + fieldId).append(msg);
                    hashFormAdmin.resetDisplayedOpts(fieldId);
                });

            } else {
                newOption = newOption.replace(new RegExp('optkey="' + oldKey + '"', 'g'), 'optkey="' + optKey + '"');
                newOption = newOption.replace(new RegExp('-' + oldKey + '_', 'g'), '-' + optKey + '_');
                newOption = newOption.replace(new RegExp('-' + oldKey + '"', 'g'), '-' + optKey + '"');
                newOption = newOption.replace(new RegExp('\\[' + oldKey + '\\]', 'g'), '[' + optKey + ']');
                newOption = newOption.replace('hf-hidden hf-option-template', '');
                newOption = {newOption};

                $('#hf-field-options-' + fieldId).append(newOption.newOption);
                hashFormAdmin.resetDisplayedOpts(fieldId);
            }
        },

        resetOptOnChange: function () {
            var field, thisOpt;
            var check = $(this);

            field = hashFormAdmin.getFieldKeyFromOpt(this);
            if (!field) {
                return;
            }

            thisOpt = $(this).closest('li');
            hashFormAdmin.resetSingleOpt(field.fieldId, field.fieldKey, thisOpt);

            setTimeout(function () {
                check.next('input').trigger('change');
            }, 100);
        },

        maybeUncheckRadio: function () {
            var $self, uncheck, unbind, up;

            $self = $(this);
            if ($self.is(':checked')) {
                uncheck = function () {
                    setTimeout(function () {
                        $self.prop('checked', false);
                    }, 0);
                };

                unbind = function () {
                    $self.off('mouseup', up);
                };

                up = function () {
                    uncheck();
                    unbind();
                };

                $self.on('mouseup', up);
                $self.one('mouseout', unbind);
            } else {
                $self.closest('li').siblings().find('.hf-choice-input').prop('checked', false);
            }
        },

        deleteFieldOption: function () {
            var otherInput,
                    parentLi = this.closest('li'),
                    parentUl = parentLi.parentNode,
                    fieldId = this.getAttribute('data-fid');

            $(parentLi).fadeOut('slow', function () {
                $(parentLi).remove();
                var hasOther = $(parentUl).find('.hashform_other_option');
                if (hasOther.length < 1) {
                    otherInput = document.getElementById('other_input_' + fieldId);
                    if (otherInput !== null) {
                        otherInput.value = 0;
                    }
                    $('#other_button_' + fieldId).fadeIn('slow');
                }
                hashFormAdmin.resetDisplayedOpts(fieldId);
            });
        },

        liveChangeHeight: function () {
            var newValue = this.value,
                    changes = document.getElementById(this.getAttribute('data-changeheight'));

            if (changes === null) {
                return;
            }

            $(changes).css("height", newValue);
        },

        liveChangeRows: function () {
            var newValue = this.value,
                    changes = document.getElementById(this.getAttribute('data-changerows'));

            if (changes === null) {
                return;
            }

            $(changes).attr("rows", newValue);
        },

        liveChangeStars: function () {
            var newValue = this.value,
                    stars = '',
                    changes = document.getElementById(this.getAttribute('data-changestars'));

            if (changes === null) {
                return;
            }

            for (var i = 0; i < newValue; i++) {
                stars = stars + '<label class="hf-star-rating"><input type="radio"><span class="mdi mdi-star-outline"></span></label>';
            }
            $(changes).html(stars);
        },

        liveChangeLabelPosition: function (e) {
            const fieldId = $(this).closest('.hf-fields-settings').data('fid');
            $('#hf-editor-field-id-' + fieldId).removeClass('hf-label-position-top').removeClass('hf-label-position-left').removeClass('hf-label-position-right').removeClass('hf-label-position-hide').addClass('hf-label-position-' + e.target.value);
        },

        liveChangeLabelAlignment: function (e) {
            const fieldId = $(this).closest('.hf-fields-settings').data('fid');
            $('#hf-editor-field-id-' + fieldId).removeClass('hf-label-alignment-left').removeClass('hf-label-alignment-right').removeClass('hf-label-alignment-center').addClass('hf-label-alignment-' + e.target.value);
        },

        liveChangeOptionsLayout: function (e) {
            const fieldId = $(this).closest('.hf-fields-settings').data('fid');
            $('#hf-editor-field-id-' + fieldId).removeClass('hf-options-layout-inline').removeClass('hf-options-layout-1').removeClass('hf-options-layout-2').removeClass('hf-options-layout-3').removeClass('hf-options-layout-4').removeClass('hf-options-layout-5').removeClass('hf-options-layout-6').addClass('hf-options-layout-' + e.target.value);
        },

        liveChangeHeadingType: function (e) {
            const fieldId = $(this).closest('.hf-fields-settings').data('fid');
            $('#hf-field-' + fieldId).replaceWith(function () {
                return '<' + e.target.value + ' id="' + 'hf-field-' + fieldId + '">' + $(this).html() + '</' + e.target.value + '>';
            });
        },

        liveChangeTextAlignment: function (e) {
            const fieldId = $(this).closest('.hf-fields-settings').data('fid');
            $('#hf-editor-field-id-' + fieldId).removeClass('hf-text-alignment-left').removeClass('hf-text-alignment-right').removeClass('hf-text-alignment-center').addClass('hf-text-alignment-' + e.target.value);
        },

        liveChangeSelectImageType: function () {
            var option = $(this).val();
            var id = $(this).attr('data-is-id');
            $('#hf-field-options-' + id).find('.hf-choice-input').prop('checked', false);
            $('#hf-editor-field-container-' + id).find('input').prop('checked', false);
            $('#hf-field-options-' + id).find('.hf-choice-input').attr('type', option);
            $('#hf-editor-field-container-' + id).find('input').attr('type', option);
        },

        liveChangeBorderType: function (e) {
            $('#' + this.getAttribute('data-changebordertype')).css("border-bottom-style", this.value);
        },

        liveChangeBorderWidth: function (e) {
            $('#' + this.getAttribute('data-changeborderwidth')).css("border-bottom-width", this.value + 'px');
        },

        liveChangeFieldMaxWidth: function () {
            const settings = $(this).closest('.hf-fields-settings');
            const fieldId = settings.data('fid');
            const fieldMaxWidth = settings.find('input[name^="field_options[field_max_width"]').val();
            const fieldMaxWidthUnit = settings.find('select[name^="field_options[field_max_width_unit"]').val();
            if (parseInt(fieldMaxWidth) > 0) {
                $('#hf-editor-field-container-' + fieldId).css('--hf-width', parseInt(fieldMaxWidth) + fieldMaxWidthUnit);
            } else {
                $('#hf-editor-field-container-' + fieldId).prop('style').removeProperty('--hf-width');
            }
        },

        liveChangeImageMaxWidth: function () {
            const settings = $(this).closest('.hf-fields-settings');
            const fieldId = settings.data('fid');
            const imageMaxWidth = settings.find('input[name^="field_options[image_max_width"]').val();
            const imageMaxWidthUnit = settings.find('select[name^="field_options[image_max_width_unit"]').val();
            if (parseInt(imageMaxWidth) > 0) {
                $('#hf-editor-field-container-' + fieldId).css('--hf-image-width', parseInt(imageMaxWidth) + imageMaxWidthUnit);
            } else {
                $('#hf-editor-field-container-' + fieldId).prop('style').removeProperty('--hf-image-width');
            }
        },

        liveChangeAddressFields: function () {
            const disableField = $(this).attr('data-disablefield');
            if ($(this).is(":checked")) {
                $(document).find('#' + disableField).addClass('hf-hidden');
            } else {
                $(document).find('#' + disableField).removeClass('hf-hidden');
            }
        },

        liveChangeAutoWidth: function (e) {
            const fieldId = $(this).closest('.hf-fields-settings').data('fid');
            if ($(this).is(":checked")) {
                $('#hf-editor-field-id-' + fieldId).addClass('hf-auto-width');
            } else {
                $('#hf-editor-field-id-' + fieldId).removeClass('hf-auto-width');
            }
        },

        liveChangeFieldAlignment: function (e) {
            const fieldId = $(this).closest('.hf-fields-settings').data('fid');
            $('#hf-editor-field-id-' + fieldId).removeClass('hf-field-alignment-left').removeClass('hf-field-alignment-right').removeClass('hf-field-alignment-center').addClass('hf-field-alignment-' + e.target.value);
        },

        initFormSettings: function () {
            $('.hashform-ajax-udpate-button').on('click', hashFormAdmin.submitSettingsBuild);
            $('.hf-multiple-rows').on('click', '.hf-add-email', function () {
                $(this).closest('.hf-multiple-rows').find('.hf-multiple-email').append('<div class="hf-email-row"><input type="email" name="email_to[]" value=""/><span class="mdi mdi-trash-can-outline hf-delete-email-row"></span></div>');
            })
            $(document).on('click', '.hf-multiple-rows .hf-delete-email-row', function () {
                $(this).closest('.hf-email-row').remove();
            })
        },

        addConditionRepeaterBlock: async function (e) {
            e.preventDefault();
            const parentBlock = $(this).closest('.hf-form-row');
            const parentRepeaterBlock = parentBlock.find('.hf-condition-repeater-blocks');
            await $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    action: 'hashform_add_more_condition_block',
                    form_id: $("#form_id").val()
                },
                success: function (msg) {
                    parentRepeaterBlock.append(msg);
                }
            })
        },

        removeConditionRepeaterBlock: function () {
            const parentBlock = $(this).closest('.hf-condition-repeater-block');
            parentBlock.remove();
        },

        submitSettingsBuild: function (e) {
            e.preventDefault();
            var $thisEle = this;
            hashFormAdmin.preFormSave(this);
            var v = JSON.stringify($formSettings.serializeArray());
            $('#hashform_compact_fields').val(v);
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'hashform_save_form_settings',
                    hashform_compact_fields: v,
                    nonce: hashform_backend_js.nonce
                },
                success: function (msg) {
                    hashFormAdmin.afterFormSave($thisEle);
                    var $postStuff = document.getElementById('hf-form-panel');
                    var $html = document.createElement('div');
                    $html.setAttribute('class', 'hf-updated-info');
                    $html.innerHTML = msg;
                    $postStuff.insertBefore($html, $postStuff.firstChild);
                }
            });
        },

        initStyleSettings: function () {
            $('.hashform-ajax-udpate-button').on('click', hashFormAdmin.submitStylesBuild);
            $('#hf-form-style-template').on('change', function (e) {
                e.preventDefault();
                const templateID = $(this).val();
                var style = '';
                if (templateID) {
                    style = $(document).find('option[value="' + templateID + '"]').attr('data-style');
                }
                $('style.hf-style-content').text(style);
            });
            $('#hf-form-style-select').on('change', function (e) {
                e.preventDefault();
                const styleClass = $(this).find(":selected").val();
                $(document).find('form.hashform-form').removeClass('hf-form-no-style').removeClass('hf-form-default-style').removeClass('hf-form-custom-style').addClass('hf-form-' + styleClass);
            });
        },

        submitStylesBuild: function (e) {
            e.preventDefault();
            var $thisEle = this;
            hashFormAdmin.preFormSave(this);
            var v = JSON.stringify($styleSettings.serializeArray());
            $('#hashform_compact_fields').val(v);
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'hashform_save_form_style',
                    'hashform_compact_fields': v,
                    nonce: hashform_backend_js.nonce
                },
                success: function (msg) {
                    hashFormAdmin.afterFormSave($thisEle);
                    var $postStuff = document.getElementById('hf-form-panel');
                    var $html = document.createElement('div');
                    $html.setAttribute('class', 'hf-updated-info');
                    $html.innerHTML = msg;
                    $postStuff.insertBefore($html, $postStuff.firstChild);
                }
            });
        },

        initOtherSettings: function () {
            $(document).on('click', '#hf-test-email-button', function (e) {
                e.preventDefault();
                const testEmailButton = $(this);
                const testEmail = $(document).find('#hf-test-email').val();
                $(document).find('.hf-error').remove();
                if (!hashFormAdmin.isEmail(testEmail)) {
                    testEmailButton.closest('.hf-grid-3').append('<div class="hf-error">Invalid Email</div>');
                    return;
                }
                testEmailButton.addClass('hf-loading-button');
                var emailTemplate = $('#hf-settings-email-template').val();
                $('.hf-test-email-notice').html('');
                jQuery.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: 'hashform_test_email_template',
                        email_template: emailTemplate,
                        test_email: testEmail,
                        nonce: hashform_backend_js.nonce
                    },
                    success: function (res) {
                        testEmailButton.removeClass('hf-loading-button');
                        const response = JSON.parse(res);
                        if (response.success) {
                            testEmailButton.closest('.hf-settings-row').find('.hf-test-email-notice').html('<div class="hf-success">' + response.message + '</div>');
                        } else {
                            testEmailButton.closest('.hf-settings-row').find('.hf-test-email-notice').html('<div class="hf-error">' + response.message + '</div>');
                        }
                    }
                });
            })
        },

        isEmail: function (email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        },

        isUrl: function (url) {
            var regex = /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
            return regex.test(url);
        },

        setupFieldOptionSorting: function (sort) {
            var opts = {
                items: 'li',
                axis: 'y',
                opacity: 0.65,
                forcePlaceholderSize: false,
                handle: '.hf-drag',
                helper: function (e, li) {
                    if (li.find('input[type="radio"]:checked, input[type="checkbox"]:checked').length > 0) {
                        isCheckedField = true;
                    }
                    copyHelper = li.clone().insertAfter(li);
                    return li.clone();
                },
                stop: function (e, ui) {
                    copyHelper && copyHelper.remove();
                    var fieldId = ui.item.attr('id').replace('hf-option-list-', '').replace('-' + ui.item.data('optkey'), '');
                    hashFormAdmin.resetDisplayedOpts(fieldId);
                    var uiSortField = ui.item.find('input[type="radio"], input[type="checkbox"]');

                    if (isCheckedField) {
                        uiSortField.prop('checked', true);
                        ui.item.find('input[type="radio"]').trigger('click');
                        isCheckedField = false;
                    }
                }
            };
            $(sort).sortable(opts);
        },

        getFieldKeyFromOpt: function (object) {
            var allOpts, fieldId, fieldKey;

            allOpts = $(object).closest('.hf-option-list');
            if (!allOpts.length) {
                return false;
            }

            fieldId = allOpts.attr('id').replace('hf-field-options-', '');
            fieldKey = allOpts.data('key');

            return {
                fieldId: fieldId,
                fieldKey: fieldKey
            };
        },

        usingSeparateValues: function (fieldId) {
            var field = document.getElementById('separate_value_' + fieldId);
            if (field === null) {
                return false;
            } else {
                return field.checked;
            }
        },

        resetSingleOpt: function (fieldId, fieldKey, thisOpt) {
            var saved, text, defaultVal, previewInput,
                    optKey = thisOpt.data('optkey'),
                    separateValues = hashFormAdmin.usingSeparateValues(fieldId),
                    single = $('label[for="field_' + fieldKey + '-' + optKey + '"]'),
                    baseName = 'field_options[options_' + fieldId + '][' + optKey + ']',
                    label = $('input[name="' + baseName + '[label]"]');

            if (single.length < 1) {
                hashFormAdmin.resetDisplayedOpts(fieldId);

                // Set the default value.
                defaultVal = thisOpt.find('input[name^="default_value_"]');
                if (defaultVal.is(':checked') && label.length > 0) {
                    $('select[name^="item_meta[' + fieldId + ']"]').val(label.val());
                }
                return;
            }

            previewInput = single.children('input');

            if (label.length < 1) {
                // Check for other label.
                label = $('input[name="' + baseName + '"]');
                saved = label.val();
            } else if (separateValues) {
                saved = $('input[name="' + baseName + '[value]"]').val();
            } else {
                saved = label.val();
            }

            if (label.length < 1) {
                return;
            }

            // Set the displayed value.
            text = single[0].childNodes;
            text[ text.length - 1 ].nodeValue = ' ' + label.val();
            previewInput.closest('.hf-choice').find('.hf-field-is-label').text(saved);

            // Set saved value.
            previewInput.val(saved);

            // Set the default value.
            defaultVal = thisOpt.find('input[name^="default_value_"]');
            previewInput.prop('checked', defaultVal.is(':checked') ? true : false);
        },

        resetDisplayedOpts: function (fieldId) {
            var i, opts, type, placeholder, fieldInfo,
                    input = $('[name^="item_meta[' + fieldId + ']"]');

            if (input.length < 1) {
                return;
            }

            if (input.is('select')) {
                const selectedValDefault = input.val();
                placeholder = document.getElementById('hf-placeholder-' + fieldId);

                if (placeholder !== null && placeholder.value === '') {
                    hashFormAdmin.fillDropdownOpts(input[0], {sourceID: fieldId});
                } else {
                    hashFormAdmin.fillDropdownOpts(input[0], {
                        sourceID: fieldId,
                        placeholder: placeholder.value
                    });
                }

                if ($('[name^="item_meta[' + fieldId + ']"]').length > 0 && $('[name^="item_meta[' + fieldId + ']"]')[0].contains(selectedValDefault)) {
                    $('[name^="item_meta[' + fieldId + ']"]').val(selectedValDefault);
                }
            } else {
                opts = hashFormAdmin.getMultipleOpts(fieldId);
                type = input.attr('type');
                $('#hf-editor-field-container-' + fieldId + ' .hf-choice-container').html('');
                fieldInfo = hashFormAdmin.getFieldKeyFromOpt($('#hf-option-list-' + fieldId + '-000'));

                var container = $('#hf-editor-field-container-' + fieldId + ' .hf-choice-container');

                for (i = 0; i < opts.length; i++) {
                    container.append(hashFormAdmin.addRadioCheckboxOpt(type, opts[ i ], fieldId, fieldInfo.fieldKey));
                }
            }

            hashFormAdmin.adjustConditionalLogicOptionOrders(fieldId);
        },

        fillDropdownOpts: function (field, atts) {
            if (field === null) {
                return;
            }
            var sourceID = atts.sourceID,
                    placeholder = atts.placeholder,
                    showOther = atts.other;

            hashFormAdmin.removeDropdownOpts(field);
            var opts = hashFormAdmin.getMultipleOpts(sourceID),
                    hasPlaceholder = (typeof placeholder !== 'undefined');

            for (var i = 0; i < opts.length; i++) {
                var label = opts[ i ].label,
                        isOther = opts[ i ].key.indexOf('other') !== -1;

                if (hasPlaceholder && label !== '') {
                    hashFormAdmin.addBlankSelectOption(field, placeholder);
                } else if (hasPlaceholder) {
                    label = placeholder;
                }
                hasPlaceholder = false;

                if (!isOther || showOther) {
                    var opt = document.createElement('option');
                    opt.value = opts[ i ].saved;
                    opt.innerHTML = label;
                    field.appendChild(opt);
                }
            }
        },

        addRadioCheckboxOpt: function (type, opt, fieldId, fieldKey) {
            var single,
                    id = 'hf-field-' + fieldKey + '-' + opt.key;

            single = '<div class="hf-choice hf-' + type + '" id="hf-' + type + '-' + fieldId + '-' + opt.key + '"><label for="' + id +
                    '"><input type="' + type +
                    '" name="item_meta[' + fieldId + ']' + (type === 'checkbox' ? '[]' : '') +
                    '" value="' + opt.saved + '" id="' + id + '"' + (opt.checked ? ' checked="checked"' : '') + '> ' + opt.label + '</label>' +
                    '</div>';

            return single;
        },

        adjustConditionalLogicOptionOrders: function (fieldId) {
            var row, rowIndex, opts, logicId, valueSelect, rowOptions, expectedOrder, optionLength, optionIndex, expectedOption, optionMatch,
                    rows = document.getElementById('hf-wrap').querySelectorAll('.hashform_logic_row'),
                    rowLength = rows.length,
                    fieldOptions = hashFormAdmin.getFieldOptions(fieldId),
                    optionLength = fieldOptions.length;

            for (rowIndex = 0; rowIndex < rowLength; rowIndex++) {
                row = rows[ rowIndex ];
                opts = row.querySelector('.hashform_logic_field_opts');

                if (opts.value != fieldId) {
                    continue;
                }

                logicId = row.id.split('_')[ 2 ];
                valueSelect = row.querySelector('select[name="field_options[hide_opt_' + logicId + '][]"]');

                for (optionIndex = optionLength - 1; optionIndex >= 0; optionIndex--) {
                    expectedOption = fieldOptions[ optionIndex ];
                    optionMatch = valueSelect.querySelector('option[value="' + expectedOption + '"]');

                    if (optionMatch === null) {
                        optionMatch = document.createElement('option');
                        optionMatch.setAttribute('value', expectedOption);
                        optionMatch.textContent = expectedOption;
                    }

                    valueSelect.prepend(optionMatch);
                }

                optionMatch = valueSelect.querySelector('option[value=""]');
                if (optionMatch !== null) {
                    valueSelect.prepend(optionMatch);
                }
            }
        },

        initBulkOptionsOverlay: function () {
            var $info = hashFormAdmin.initModal('#hf-bulk-edit-modal', '700px');
            if ($info === false)
                return;
            $('.hf-insert-preset').on('click', function (event) {
                var opts = JSON.parse(this.getAttribute('data-opts'));
                event.preventDefault();
                document.getElementById('hf-bulk-options').value = opts.join('\n');
                return false;
            });

            $buildForm.on('click', 'a.hf-bulk-edit-link', function (event) {
                event.preventDefault();
                var i, key, label,
                        content = '',
                        optList,
                        opts,
                        fieldId = $(this).closest('[data-fid]').data('fid'),
                        separate = hashFormAdmin.usingSeparateValues(fieldId);

                optList = document.getElementById('hf-field-options-' + fieldId);
                if (!optList)
                    return;

                opts = optList.getElementsByTagName('li');
                document.getElementById('bulk-field-id').value = fieldId;

                for (i = 0; i < opts.length; i++) {
                    key = opts[i].getAttribute('data-optkey');
                    if (key !== '000') {
                        label = document.getElementsByName('field_options[options_' + fieldId + '][' + key + '][label]')[0];
                        if (typeof label !== 'undefined') {
                            content += label.value;
                            if (separate) {
                                content += '|' + document.getElementsByName('field_options[options_' + fieldId + '][' + key + '][value]')[0].value;
                            }
                            content += '\r\n';
                        }
                    }

                    if (i >= opts.length - 1) {
                        document.getElementById('hf-bulk-options').value = content;
                    }
                }
                $info.dialog('open');
                return false;
            });

            $('#hf-update-bulk-options').on('click', function () {
                var fieldId = document.getElementById('bulk-field-id').value;
                var optionType = document.getElementById('bulk-option-type').value;
                if (optionType)
                    return;
                this.classList.add('hf-loading-button');
                var separate = hashFormAdmin.usingSeparateValues(fieldId),
                        action = 'hashform_import_options';
                jQuery.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: action,
                        field_id: fieldId,
                        opts: document.getElementById('hf-bulk-options').value,
                        separate: separate,
                        nonce: hashform_backend_js.nonce
                    },
                    success: function (html) {
                        document.getElementById('hf-field-options-' + fieldId).innerHTML = html;
                        hashFormAdmin.resetDisplayedOpts(fieldId);
                        if (typeof $info !== 'undefined') {
                            $info.dialog('close');
                            document.getElementById('hf-update-bulk-options').classList.remove('hf-loading-button');
                        }
                    }
                });
            });
        },

        initModal: function (id, width) {
            const $info = $(id);
            if (!$info.length)
                return false;
            if (typeof width === 'undefined')
                width = '550px';
            const dialogArgs = {
                dialogClass: 'hf-dialog',
                modal: true,
                autoOpen: false,
                closeOnEscape: true,
                width: width,
                resizable: false,
                draggable: false,
                open: function () {
                    $('.ui-dialog-titlebar').addClass('hf-hidden').removeClass('ui-helper-clearfix');
                    $('#wpwrap').addClass('hashform_overlay');
                    $('.hf-dialog').removeClass('ui-widget ui-widget-content ui-corner-all');
                    $info.removeClass('ui-dialog-content ui-widget-content');
                    hashFormAdmin.bindClickForDialogClose($info);
                },
                close: function () {
                    $('#wpwrap').removeClass('hashform_overlay');
                    $('.spinner').css('visibility', 'hidden');

                    this.removeAttribute('data-option-type');
                    const optionType = document.getElementById('bulk-option-type');
                    if (optionType) {
                        optionType.value = '';
                    }
                }
            };
            $info.dialog(dialogArgs);
            return $info;
        },

        initNewFormModal: function () {
            $(document).on('click', '.hf-trigger-modal', () => {
                $('#hf-add-form-modal').addClass('hf-open');
            });

            $(document).on('click', '.hashform-close-form-modal', () => {
                $('#hf-add-form-modal').removeClass('hf-open');
            });

            $(document).on('submit', '#hf-add-template', function (event) {
                event.preventDefault();
                const addTemplateButton = $(this).closest('#hf-add-template').find('button');
                if (!addTemplateButton.hasClass('hashform-updating')) {
                    var template_name = $(this).closest('#hf-add-template').find('input[name=template_name]').val();
                    addTemplateButton.addClass('hashform-updating');
                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: {
                            action: 'hashform_create_form',
                            name: template_name,
                            nonce: hashform_backend_js.nonce
                        },
                        success: function (response) {
                            const res = JSON.parse(response)
                            if (typeof res.redirect !== 'undefined') {
                                const redirect = res.redirect;
                                window.location = redirect;
                            }
                        }
                    });
                }
            });
        },

        preFormSave: function (b) {
            hashFormBuilder.removeWPUnload();
            if ($('form.inplace_form').length) {
                $('.inplace_save, .postbox').trigger('click');
            }

            if (b.classList.contains('hashform-ajax-udpate-button')) {
                b.classList.add('hashform-updating');
            } else {
                b.classList.add('hashform_loading_button');
            }
            b.setAttribute('aria-busy', 'true');
        },

        afterFormSave: function (button) {
            button.classList.remove('hashform-updating');
            button.classList.remove('hashform_loading_button');
            hashFormBuilder.resetOptionTextDetails();
            fieldsUpdated = 0;
            button.setAttribute('aria-busy', 'false');

            setTimeout(function () {
                $('.hf-updated-info').fadeOut('slow', function () {
                    this.parentNode.removeChild(this);
                });
            }, 5000);
        },

        toggleValidationBox: function (hasValue, messageClass) {
            var $msg = $(messageClass);
            if (hasValue) {
                $msg.removeClass('hf-hidden');
                $msg.closest('.hf-form-container').find('.hf-validation-header').removeClass('hf-hidden');
            } else {
                $msg.addClass('hf-hidden');
                $msg.closest('.hf-form-container').find('.hf-validation-header').addClass('hf-hidden');
            }
        },

        addTimeDefaultValue: function () {
            const that = $(this);
            if (that.val() && !that.val().match(/^(2[0-3]|[01][0-9]):[0-5][0-9]$/)) {
                that.val('00:00');
            }
            const fieldId = that.closest('.hf-fields-settings').data('fid');
            const [hourString, minute] = that.val().split(":");
            const hour = +hourString % 24;
            $('#hf-editor-field-container-' + fieldId + ' .hf-timepicker').val(minute && (hour % 12 || 12) + ':' + minute + (hour < 12 ? "am" : "pm"));
        },

        validateTimeValue: function () {
            const that = $(this);
            if (that.val() && !that.val().match(/^(2[0-3]|[01][0-9]):[0-5][0-9]$/)) {
                that.val('00:00');
            }
            that.trigger('input');
        },

        removeDropdownOpts: function (field) {
            var i;
            if (typeof field.options === 'undefined') {
                return;
            }

            for (i = field.options.length - 1; i >= 0; i--) {
                field.remove(i);
            }
        },

        getMultipleOpts: function (fieldId) {
            var i, saved, labelName, label, key, optObj,
                    image, savedLabel, input, field, checkbox, fieldType,
                    checked = false,
                    opts = [],
                    imageUrl = '',
                    hasImageOptions = document.getElementsByName('field_options[select_option_type_' + fieldId + ']').length > 0,
                    optVals = $('input[name^="field_options[options_' + fieldId + ']"]'),
                    separateValues = hashFormAdmin.usingSeparateValues(fieldId);

            for (i = 0; i < optVals.length; i++) {
                if (optVals[ i ].name.indexOf('[000]') > 0 || optVals[ i ].name.indexOf('[value]') > 0 || optVals[ i ].name.indexOf('[image_id]') > 0 || optVals[ i ].name.indexOf('[price]') > 0) {
                    continue;
                }
                saved = optVals[ i ].value;
                label = saved;
                key = optVals[ i ].name.replace('field_options[options_' + fieldId + '][', '').replace('[label]', '').replace(']', '');

                if (separateValues) {
                    labelName = optVals[ i ].name.replace('[label]', '[value]');
                    saved = $('input[name="' + labelName + '"]').val();
                }

                checked = hashFormBuilder.getChecked(optVals[ i ].getAttribute('class'));

                if (hasImageOptions) {
                    imageUrl = hashFormBuilder.getImageUrlFromInput(optVals[i]);
                    fieldType = document.getElementsByName('field_options[select_option_type_' + fieldId + ']').value;
                    label = hashFormBuilder.getImageLabel(label, false, imageUrl, fieldType);
                }

                optObj = {
                    saved: saved,
                    label: label,
                    checked: checked,
                    key: key
                };
                opts.push(optObj);
            }
            return opts;
        },

        getFieldOptions: function (fieldId) {
            var index, input, li,
                    listItems = document.getElementById('hf-field-options-' + fieldId).querySelectorAll('.hashform_single_option'),
                    options = [],
                    length = listItems.length;
            for (index = 0; index < length; index++) {
                li = listItems[ index ];

                if (li.classList.contains('hf-hidden')) {
                    continue;
                }

                input = li.querySelector('.field_' + fieldId + '_option');
                options.push(input.value);
            }
            return options;
        },

        getHighestOptKey: function (fieldId) {
            var i = 0,
                    optKey = 0,
                    opts = $('#hf-field-options-' + fieldId + ' li'),
                    lastKey = 0;

            for (i; i < opts.length; i++) {
                optKey = opts[i].getAttribute('data-optkey');
                if (opts.length === 1) {
                    return optKey;
                }
                if (optKey !== '000') {
                    optKey = optKey.replace('other_', '');
                    optKey = parseInt(optKey, 10);
                }

                if (!isNaN(lastKey) && (optKey > lastKey || lastKey === '000')) {
                    lastKey = optKey;
                }
            }
            return lastKey;
        },

        liveChangeHideShowRow: function () {
            const that = $(this),
                    parentRow = that.closest('.hf-form-container');
            var val = that.val();
            parentRow.find('.hf-row-show-hide').addClass('hf-hidden');
            var valArray = val.split('_');
            $.each(valArray, function (index, value) {
                parentRow.find('.hf-row-show-hide.hf-sub-field-' + value).removeClass('hf-hidden');
            });
        },

        liveChangeHideShowLabel: function () {
            const that = $(this);
            var val = that.val();
            const parentFieldSetting = $(this).closest('.hf-fields-settings'),
                    fieldId = parentFieldSetting.data('fid'),
                    fieldLabel = $('#hf-editor-field-id-' + fieldId).find('label.hf-label-show-hide');

            if (!val || (parentFieldSetting.find('[data-label-show-hide-checkbox]').is(':checked'))) {
                fieldLabel.addClass('hf-hidden');
            } else {
                fieldLabel.removeClass('hf-hidden');
            }
        },

        liveChangeHideShowLabelCheckbox: function () {
            const that = $(this);
            const parentFieldSetting = $(this).closest('.hf-fields-settings'),
                    fieldId = parentFieldSetting.data('fid'),
                    fieldLabel = $('#hf-editor-field-id-' + fieldId).find('label.hf-label-show-hide');

            if (that.is(':checked') || !parentFieldSetting.find('[data-label-show-hide]').val()) {
                fieldLabel.addClass('hf-hidden');
            } else {
                fieldLabel.removeClass('hf-hidden');
            }
        },

    };

    $(function () {
        hashFormAdmin.init();
    });
})(jQuery);


HTMLSelectElement.prototype.contains = function(value) {
    for (var i = 0, l = this.options.length; i < l; i++) {
        if (this.options[i].value == value) {
            return true;
        }
    }
    return false;
}