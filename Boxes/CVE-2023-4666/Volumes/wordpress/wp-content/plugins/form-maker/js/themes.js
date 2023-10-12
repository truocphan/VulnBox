jQuery(function() {
  jQuery(".fm-themes-tabs li a").on("click", function(){
    jQuery(".fm-themes-tabs-container .fm-themes-container").addClass('fm-hide');
    jQuery(".fm-themes-tabs li a").removeClass("fm-theme-active-tab");
    jQuery("#"+jQuery(this).attr("id")+'-content').removeClass('fm-hide');
    jQuery(this).addClass("fm-theme-active-tab");
    jQuery("#active_tab").val(jQuery(this).attr("id"));
    return false;
  });
  jQuery('.color').spectrum({
    showAlpha: true,
    showInput: true,
    showSelectionPalette: true,
    preferredFormat: "hex",
    allowEmpty: true,
    move: function(color){
      jQuery(this).val(color);
      jQuery(this).trigger("change");
    },
    change: function(color){
      jQuery(this).val(color);
      jQuery(this).trigger("change");
    }
  });
  jQuery('.fm-preview-form').show();
});

function fm_theme_submit_button(form_id, version) {
  var all_params = '';
  if (version == 1) {
    all_params = jQuery('textarea[name=CUPCSS]').serializeObject();
  }
  else {
    all_params = jQuery('#' + form_id).serializeObject();
  }
  jQuery('#params').val(JSON.stringify(all_params).replace(plugin_url, '[SITE_ROOT]'));
  return true;
}