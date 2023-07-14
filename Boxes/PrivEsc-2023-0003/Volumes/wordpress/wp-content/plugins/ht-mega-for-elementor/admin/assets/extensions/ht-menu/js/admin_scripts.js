(function($){
"use strict";

    $('.htmegamenu-menu-settings-save').on( 'click', function(){
        
        var spinner = $(this).parent().find('.spinner');
        spinner.addClass('loading');
        save_menu_options( $(this) );

    });

    function save_menu_options( that ){
        var parent = that.parents("#HT_Mega_Menu_meta_box"),
            settings = {
                'enable_menu': ( parent.find("#htmegamenu-menu-metabox-input-is-enabled").is(':checked') === true ) ? 'on' : 'off'
            };
            
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action          : "HT_Mega_Menu_Panels_ajax_requests",
                sub_action      : "save_menu_options",
                settings        : settings,
                menu_id         : $("#htmegamenu-metabox-input-menu-id").val()
            },
            cache: false,
            success: function(response) {
                that.parent().find('.spinner').removeClass('loading');
            }
        });

    }

    //Initiate Color Picker
    $('.htmega-color-picker-field').wpColorPicker();

    // Menu Icon Builder
    $(document).ready(function () {
        var $el = $(document).find('.edit-menu-item-menuicon');
        htmega_menu_init_fontpicker($el);
        $('body').on('click', '.menu-item', function(){
            var $element = $(this).find('.edit-menu-item-menuicon');
            htmega_menu_init_fontpicker($element);
        });
    });

    function htmega_menu_init_fontpicker($el) {
        $el.fontIconPicker({
            source: htmegaIconsSet,
            emptyIcon: true,
            hasSearch: true,
            theme: 'fip-bootstrap'
        });

        $('.submit-add-to-menu').on('click', function(){
            $el.fontIconPicker({
                source: htmegaIconsSet,
                emptyIcon: true,
                hasSearch: true,
                theme: 'fip-bootstrap'
            });
        })
    }


    
})(jQuery);