;(function($){
  elementor.hooks.addAction("panel/open_editor/widget/htmega-imagemarker-addons",function(panel,model,view){
    $("input:hidden[value='marker_style_selector']").parents('.elementor-control').prev().find('select').on('change',function(){
      if('6'==$(this).val()){
        $("input:hidden[value='items_hidden_selector']").parents(".elementor-control").prev().show();
      }else{
        $("input:hidden[value='items_hidden_selector']").parents(".elementor-control").prev().hide();
      }
    });
    
    if('6'==$("input:hidden[value='marker_style_selector']").parents('.elementor-control').prev().find('select').val()){
      $("input:hidden[value='items_hidden_selector']").parents(".elementor-control").prev().show();
    }else{
      $("input:hidden[value='items_hidden_selector']").parents(".elementor-control").prev().hide();
    }

  })
})(jQuery);