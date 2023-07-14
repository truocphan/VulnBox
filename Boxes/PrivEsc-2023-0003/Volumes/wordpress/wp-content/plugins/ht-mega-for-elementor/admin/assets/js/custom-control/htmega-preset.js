"use strict";
!(function ($, elementor) {

    var postSelectControl = elementor.modules.controls.BaseData.extend({ 

        onReady: function() {
            this.control_select = this.$el.find('.preset-select');
            this.save_input = this.$el.find('.preset-selection-save-value');
            window.htPresetDesigns = window.htPresetDesigns || {};
            this.fetchPresets();
            this.control_select.on( 'change', (e) => {
                this.onPesetChange(e.currentTarget.value);
            });
        },
        isPresetControl: function (){
            return -1 !== this.getHtWidgetName().indexOf("htmega") || 'section-title-addons' === this.getHtWidgetName();
        },
        getHtWidgetName: function () {
            return this.container.settings.get("widgetType");
        },
        getPresets: function (){
            return !_.isUndefined(window.htPresetDesigns) ? window.htPresetDesigns[this.getHtWidgetName()] : {};
        },
        setPresets: function ( presetData ){
            window.htPresetDesigns[this.getHtWidgetName()] = JSON.parse(presetData);
        },
        isFetchedPreset: function () {
            return !_.isUndefined(window.htPresetDesigns[this.getHtWidgetName()]);
        },
        fetchPresets: function(){
            var t = this;
            if( this.isPresetControl() && !this.isFetchedPreset() && this.getHtWidgetName() ){
                $.get(
                htpreset.ajaxUrl,
                {
                    action: "htmega_preset_design",
                    widget: this.getHtWidgetName(),
                    nonce: htpreset.nonce
                }).done( function (e) {
                    if(e.success){
                        t.setPresets(e.data);
                    }
                });
            }
        },
        onPesetChange: function (presetValue) {
            var p = this.getPresets();
            if(!_.isUndefined(p[presetValue])){
                this.applyPreset(p[presetValue]);
            }
            this.saveValue();
        },
        applyPreset: function(p){
            var e = this.container.settings.controls,
                o = this,
                t = {};
            _.each(e, function(cv, ck){
                var cs;
                if(o.model.get("name") !== ck){
                    if(!_.isUndefined(p[ck])){
                        if(cv.is_repeater){
                            cs = o.container.settings.get(ck).clone();
                            cs.each(function (ep, i){
                                if(!_.isUndefined(p[ck][i])){
                                    _.each(ep.controls, function (ek, tk) {
                                        cs.at(i).set(tk, p[ck][i][tk]);
                                    });
                                }
                            });
                            t[ck] = cs;
                        }else{
                            t[ck] = p[ck];
                        }
                    }
                }
            });
            this.container.settings.setExternalChange(t);
        },
        saveValue: function() {
            this.setValue(this.control_select.val());
        }
    
    });

    elementor.addControlView( 'htmega-preset-select', postSelectControl );

})(window.jQuery, window.elementor);