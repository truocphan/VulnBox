import StringUtil from '../util/string-util';

function onlyUnique(value, index, self) {
    return self.indexOf(value) === index;
}

function setValue(schema, pList, value) {
    const len = pList.length;
    for(let i = 0; i < len-1; i++) {
        const elem = pList[i];
        if( !schema[elem] ) {
            if (typeof pList[i + 1] === 'number') {
                schema[elem] = [];
            } else {
                schema[elem] = {};
            }
        }
        schema = schema[elem];
    }

    if (value !== null) {
        schema[pList[len-1]] = value;
    } else {
        delete schema[pList[len-1]];
    }
}

export default class SettingGenerator {
    constructor($) {
        this.$ = $;
    }

    generateSettings( $parent ) {
        const $ = this.$;
        const postID = $('[data-piotnet-widget-post-id]').val();

        const widget_id = $parent.attr('data-piotnetforms-widget-controls');
        const data_piotnet_editor_widgets_item = $('[data-piotnetforms-preview-iframe]').contents().find('[data-piotnet-editor-widgets-item-id="' + widget_id + '"]').attr('data-piotnet-editor-widgets-item');
        const widget_info = JSON.parse(data_piotnet_editor_widgets_item);

        const fieldsObj = {"postID": postID, "fields": {}, 'type': widget_info['type']};

        const $fieldGroup = $parent.find('[data-piotnet-control]');

        return this.generateSettingsField( $fieldGroup, fieldsObj );
    }

    generateSettingsField( $fieldGroup, fieldsObj ) {
        const $ = this.$;

        $fieldGroup.each( function() {
            if ($(this).hasClass('hidden') || $(this).closest('.hidden').length > 0) {
                return;
            }

            const $field = $(this).find('[data-piotnetforms-settings-field]:not([data-piotnetforms-settings-not-field])');
            const is_repeater_field = $field.closest('[data-piotnet-control-repeater-item]').length > 0;

            let name = '';
            let value = '';
            let is_empty_value = true;

            if ($field.attr('data-piotnet-control-dimensions') !== undefined) {
                const dimensions = {};
                const $dimensions_selector = $field.closest('[data-piotnet-control-dimensions-name]').find('[data-piotnet-control-dimensions]');

                $dimensions_selector.each( function() {
                    let dimension_value = '';
                    if ($(this).attr('type') === 'checkbox' && $(this).prop("checked") === true) {
                        dimension_value = $(this).attr('value');
                    } else {
                        dimension_value = $(this).val();
                    }
                    const dimension_key = $(this).attr('data-piotnet-control-dimensions');
                    dimensions[dimension_key] = dimension_value;
                });

                is_empty_value = StringUtil.isEmpty(dimensions.top) && StringUtil.isEmpty(dimensions.right) && StringUtil.isEmpty(dimensions.bottom) && StringUtil.isEmpty(dimensions.left);
                value = is_empty_value ? null : dimensions;
                name = $field.closest('[data-piotnet-control-dimensions-name]').attr('data-piotnet-control-dimensions-name');
            } else if ($field.attr('data-piotnet-control-slider') !== undefined) {
                const $slider = $field.closest('[data-piotnet-control-slider-name]');

                const unit = $slider.find('[data-piotnet-control-unit]').val();
                const size = $slider.find('[data-piotnet-control-slider-unit="' + unit + '"]').find('[data-piotnet-control-slider]').val();

                is_empty_value = StringUtil.isEmpty(size);
                value = is_empty_value ? null : {unit, size};
                name = $slider.attr('data-piotnet-control-slider-name');
            } else if ($field.attr('data-piotnet-control-media') !== undefined) {
                const $media = $field.closest('[data-piotnet-control-media-wrapper]');

                const id = $media.find('[data-piotnet-control-media="id"]').val();
                const url = $media.find('[data-piotnet-control-media="url"]').val();

                is_empty_value = StringUtil.isEmpty(url);
                value = is_empty_value ? null : {id, url};
                name = $media.attr('data-piotnet-control-name');
            } else if ($field.attr('data-piotnet-control-gallery') !== undefined) {
                const $gallery = $field.closest('[data-piotnet-control-gallery-wrapper]');
                const gallery = [];
                const $galleryList = $gallery.find('[data-piotnet-control-gallery-item]');

                $galleryList.each(function(){
                    var galleryItem = {};
                    galleryItem.id = $(this).find('[data-piotnet-control-gallery="id"]').val();
                    galleryItem.url = $(this).find('[data-piotnet-control-gallery="url"]').val();

                    // TODO check empty value for each;
                    gallery.push(galleryItem);
                });

                is_empty_value = gallery.length === 0;
                value = is_empty_value ? null : gallery;
                name = $gallery.attr('data-piotnet-control-name');
            } else if ($field.attr('data-piotnet-control-boxshadow-settings') !== undefined) {
                const $boxShadow = $field.closest('[data-piotnet-control-boxshadow]');

                const horizontal = $boxShadow.find('[data-piotnet-control-boxshadow-settings="horizontal"]').val();
                const vertical = $boxShadow.find('[data-piotnet-control-boxshadow-settings="vertical"]').val();
                const blur = $boxShadow.find('[data-piotnet-control-boxshadow-settings="blur"]').val();
                const spread = $boxShadow.find('[data-piotnet-control-boxshadow-settings="spread"]').val();
                const color = $boxShadow.find('[data-piotnet-control-boxshadow-settings="color"]').val();

                is_empty_value = StringUtil.isEmpty(horizontal) && StringUtil.isEmpty(vertical)
                value = is_empty_value ? null : { horizontal, vertical, blur, spread, color };
                name = $boxShadow.attr('data-piotnet-control-boxshadow-name');
            } else {
                if ($field.attr('type') === 'checkbox') {
                    if ($field.prop("checked") === true) {
                        value = $field.attr('value');
                    } else {
                        value = '';
                    }
                } else {
                    value = $field.val();
                }

                if (value !== undefined && value !== null) {
                    if (!Array.isArray(value)) {
                        value = StringUtil.replaceAll(value, '"', '\"');
                        value = StringUtil.replaceAll(value, "'", "\'");
                    }
                }
                is_empty_value = StringUtil.isEmpty(value);
                value = is_empty_value ? null : value;
                name = $field.attr('name');
            }

            if ($field.closest('[data-piotnet-control-typography-wrapper]').length > 0) {
                const $typography = $field.closest('[data-piotnet-control-typography-wrapper]');
                let fontUrl = 'https://fonts.googleapis.com/css2?family=';
                const $fontFamily = $typography.find('[name*="_font_family"]');
                let fontFamily = $fontFamily.val();
                let fontControlName = $fontFamily.attr('name');

                if (fontFamily && fontFamily !== '') {
                    fontFamily = fontFamily.split(',');
                    fontFamily =  StringUtil.replaceAll(fontFamily[0], "'", '');
                    fontFamily =  StringUtil.replaceAll(fontFamily, " ", '+');
                    fontUrl += fontFamily + ':';
                    var fontWeight = $typography.find('[name*="_font_weight"]').val();
                    var fontStyle = $typography.find('[name*="_font_style"]').val();

                    if (fontStyle === 'italic' || fontStyle === 'oblique') {
                        fontUrl += 'ital,wght@1,';
                    } else {
                        fontUrl += 'wght@';
                    }

                    fontUrl += fontWeight + '&display=swap';

                    if (!fieldsObj['fonts']) {
                        fieldsObj['fonts'] = {};
                    }

                    const $previewHead = $('[data-piotnetforms-preview-iframe]').contents().find('head');
                    if ($previewHead.find('link[href="' + fontUrl + '"]').length === 0) {
                        $previewHead.append('<link href="' + fontUrl + '" rel="stylesheet">');
                    }
                    fieldsObj['fonts'][fontControlName] = fontUrl;
                } else if (fieldsObj['fonts']){
                    delete fieldsObj['fonts'][fontControlName];
                }
            }

            if (is_repeater_field) {
                let $repeater_items = $field.parents('[data-piotnet-control-repeater-item]'),
                    levels = [];

                if ($field.closest('[data-piotnet-control-repeater-item]').css('display') !== 'none') {
                    $repeater_items.each(function(){
                        if ($(this).css('display') !== 'none') {
                            levels.push($(this).index() - 1);
                            const $repeater_list = $(this).closest('[data-piotnet-control-repeater-list]');
                            levels.push($repeater_list.attr('data-piotnet-control-repeater-list'));
                        }
                    });
                }

                if (levels.length > 0) {
                    levels = levels.reverse()
                    levels.push(name);
                    setValue(fieldsObj['fields'], levels, value);
                }
            } else {
                if (is_empty_value) {
                    delete fieldsObj['fields'][name];
                } else {
                    fieldsObj['fields'][name] = value;
                }
            }
        });

        return fieldsObj;
    }

    generateWidgetsSettings(setting_widgets) {
        const $ = this.$;
        const widgetsSettings = [];

        $('[data-piotnetforms-preview-iframe]').contents().find('[data-piotnetforms-widget-preview] [data-piotnet-editor-widgets-item]').each(function() {
            const widgetId = $(this).attr('data-piotnet-editor-widgets-item-id');

            const widgetInformation = JSON.parse($(this).attr('data-piotnet-editor-widgets-item'));

            const setting_widget = setting_widgets[widgetId];

            let widget = {};
            if (setting_widget) {
                widget = {
                    id: widgetId,
                    name: widgetInformation.name,
                    class_name: widgetInformation.class_name,
                    settings: setting_widget['fields'],
                };

                if ('fonts' in setting_widget && Object.keys(setting_widget['fonts']).length > 0) {
                    widget['fonts'] = setting_widget['fonts'];
                }
            }

            if ($(this).attr('data-piotnet-editor-widgets-item') !== undefined) {
                let $widgetParents = $(this).parents('[data-piotnet-editor-widgets-item]'),
                    levels = [$(this).parent().children('[data-piotnet-editor-widgets-item]').index(this)];

                if ($widgetParents.length > 0) {
                    $widgetParents.each(function(){
                        levels.push('elements');
                        levels.push($(this).parent().children('[data-piotnet-editor-widgets-item]').index(this));
                    });

                    levels = levels.reverse();
                    setValue(widgetsSettings, levels, widget);
                } else {
                    widgetsSettings.push(widget);
                }
            }
        });

        return widgetsSettings;
    }

    removeRepeaterItem(settings, levels) {
        const len = levels.length;
        let sub_setting = settings;
        for(let i = 0; i < len-1; i++) {
            sub_setting = sub_setting[levels[i]];
        }
        const index = levels[len - 1];
        if (Array.isArray(sub_setting) && index <= sub_setting.length) {
            sub_setting.splice(index, 1);
        }
    }
}
