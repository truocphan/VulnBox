import StringUtil from "../util/string-util";

export default class CSSGenerator {
    constructor($) {
        this.$ = $;
    }

    generateCss( $parent, widgetId)  {
        const $ = this.$;
        const breakpointTablet = $('[data-piotnet-widget-breakpoint-tablet]').val();
        const breakpointMobile = $('[data-piotnet-widget-breakpoint-mobile]').val();
        let css = '';

        $parent.find('[data-piotnetforms-settings-field-css]').each(function(){
            let value;
            if ($(this).attr('data-piotnet-control-dimensions') !== undefined) {
                value = {};
                value.unit = $(this).find('[data-piotnet-control-dimensions="unit"]').val();
                value.top = $(this).find('[data-piotnet-control-dimensions="top"]').val();
                value.right = $(this).find('[data-piotnet-control-dimensions="right"]').val();
                value.bottom = $(this).find('[data-piotnet-control-dimensions="bottom"]').val();
                value.left = $(this).find('[data-piotnet-control-dimensions="left"]').val();

                if (value.top === '' && value.right === '' && value.bottom === '' && value.left === '') {
                    value = '';
                }
            } else if ($(this).attr('data-piotnet-control-slider') !== undefined) {
                value = {};
                value.unit = $(this).find('[data-piotnet-control-slider="unit"]').val();
                value.size = $(this).find('[data-piotnet-control-slider-wrapper].active').find('[data-piotnet-control-slider]').val();

                if (!value.size || value.size === '') {
                    value = '';
                }
            } else if($(this).attr('data-piotnet-control-boxshadow') !== undefined){
                value = "";
                const horizontal = $(this).find('[data-piotnet-control-boxshadow-settings="horizontal"]').val();
                const vertical = $(this).find('[data-piotnet-control-boxshadow-settings="vertical"]').val();
                const blur = $(this).find('[data-piotnet-control-boxshadow-settings="blur"]').val();
                const spread = $(this).find('[data-piotnet-control-boxshadow-settings="spread"]').val();
                const color = $(this).find('[data-piotnet-control-boxshadow-settings="color"]').val();

                const isEmpty = StringUtil.isEmpty(horizontal)
                    && StringUtil.isEmpty(vertical)
                    && StringUtil.isEmpty(blur)
                    && StringUtil.isEmpty(spread)
                    && StringUtil.isEmpty(color);

                if (!isEmpty) {
                    value += horizontal + 'px ' + vertical + 'px ' + blur + 'px ' + spread + 'px ' + color;
                }
            } else if($(this).attr('data-piotnet-control-media-wrapper') !== undefined){
                value = $(this).find('[data-piotnet-control-media="url"]').val();
            } else {
                value = $(this).val();
            }

            if (value !== '' && $(this).closest('.hidden').length === 0) {

                let cssStr = '';

                let responsive = true;

                if (typeof value === 'object') {
                    if (value.top !== undefined) {
                        if (value.top === '' && value.right === '' && value.bottom === '' && value.left === '') {
                            responsive = false;
                        } else {
                            if (value.top === '') {
                                value.top = 0;
                            }
                            if (value.right === '') {
                                value.right = 0;
                            }
                            if (value.bottom === '') {
                                value.bottom = 0;
                            }
                            if (value.left === '') {
                                value.left = 0;
                            }
                        }
                    }

                    if (value.size !== undefined) {
                        if (value.size === '') {
                            responsive = false;
                        }
                    }
                }

                if (responsive) {
                    if ($(this).attr('data-piotnet-widget-responsive-tablet') !== undefined) {
                        cssStr += '@media (max-width:' + breakpointTablet + ') {';
                    }

                    if ($(this).attr('data-piotnet-widget-responsive-mobile') !== undefined) {
                        cssStr += '@media (max-width:' + breakpointMobile + ') {';
                    }
                }

                if ( ( $(this).attr('data-piotnet-widget-responsive-tablet') === undefined && $(this).attr('data-piotnet-widget-responsive-mobile') === undefined ) || ( ( $(this).attr('data-piotnet-widget-responsive-tablet') !== undefined || $(this).attr('data-piotnet-widget-responsive-mobile') !== undefined ) && responsive ) ) {

                    cssStr += $(this).attr('data-piotnetforms-settings-field-css');
                    cssStr = StringUtil.replaceAll(cssStr, '{{WRAPPER}}', '#piotnetforms .' + widgetId);
                    cssStr = StringUtil.replaceAll(cssStr, '{{VALUE}}', value);
                    cssStr = StringUtil.replaceAllBackSlash(cssStr);

                    if (cssStr.includes('{{CURRENT_ITEM}}')) {
                        const repeaterId = $(this).closest('[data-piotnet-control-repeater-item]').find('[name="repeater_id"]').val();
                        cssStr = StringUtil.replaceAll(cssStr, '{{CURRENT_ITEM}}', '.piotnetforms-repeater-item-' + repeaterId);
                    }

                    if (typeof value === 'object') {

                        cssStr = StringUtil.replaceAll(cssStr, '{{TOP}}', value.top);
                        cssStr = StringUtil.replaceAll(cssStr, '{{RIGHT}}', value.right);
                        cssStr = StringUtil.replaceAll(cssStr, '{{BOTTOM}}', value.bottom);
                        cssStr = StringUtil.replaceAll(cssStr, '{{LEFT}}', value.left);

                        cssStr = StringUtil.replaceAll(cssStr, '{{UNIT}}', value.unit);
                        cssStr = StringUtil.replaceAll(cssStr, '{{SIZE}}', value.size);
                    }

                    cssStr = StringUtil.replaceAll(cssStr, '{"', '');
                    cssStr = StringUtil.replaceAll(cssStr, '":"', '{');
                    cssStr = StringUtil.replaceAll(cssStr, '","','}');
                    cssStr = StringUtil.replaceAll(cssStr, '"}', '}');

                }

                if (responsive) {
                    if ($(this).attr('data-piotnet-widget-responsive-tablet') !== undefined || $(this).attr('data-piotnet-widget-responsive-mobile') !== undefined) {
                        cssStr += '}';
                    }
                }

                css += cssStr;
            }
        });

        return css;
    }
}
