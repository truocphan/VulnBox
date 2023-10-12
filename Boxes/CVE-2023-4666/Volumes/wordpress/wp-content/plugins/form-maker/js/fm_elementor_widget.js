jQuery(function () {
    elementor.hooks.addAction( 'panel/open_editor/widget/fm-elementor', function( panel, model, view ) {
        var fm_el = jQuery('select[data-setting="form_id"]',window.parent.document);
        fm_add_edit_link(fm_el);
    });
    jQuery('body').on('change', 'select[data-setting="form_id"]',window.parent.document, function (){
        fm_add_edit_link(jQuery(this));
    });
});

function fm_add_edit_link(el) {
        var fm_el = el;
        var fm_id = fm_el.val();
        var a_link = fm_el.closest('.elementor-control-content').find('.elementor-control-field-description').find('a');
        var new_link = 'admin.php?page=manage_fm';
        if(fm_id !== '0'){
            new_link = 'admin.php?page=manage_fm&task=edit&current_id='+fm_el.val();
        }
        a_link.attr( 'href', new_link);
}