"use strict";

(function ($) {
  $(document).ready(function () {
    $(document).on('click', '[data-submenu="section_4_profile-menu-reordering"]', function () {
      $('.button_list_box:first-child a').trigger('click');
      if ($('#section_4-float_menu').is(':checked')) {
        $('[data-field="wpcfto_addon_option_sorting_the_menu"]').addClass('hidden');
        $('[data-field="wpcfto_addon_option_sorting_the_menu_student"]').addClass('hidden');
      } else {
        $('[data-field="wpcfto_addon_option_sorting_float_menu_main"]').addClass('hidden');
        $('[data-field="wpcfto_addon_option_sorting_float_menu_learning"]').addClass('hidden');
      }
      $("[data-id='dashboard']").removeClass('list-group-item').addClass('list-group-item-disabled');
      add_notice();
    });
    $(document).on('change', '.list-group', function () {
      setTimeout(add_notice, 500);
    });
    function add_notice() {
      var menu_elements = {
        '[data-id="dashboard"]': 'fa fa-lock',
        '[data-id="assignments"]': 'fa fa-exclamation-triangle',
        '[data-id="enrolled_courses"]': 'fa fa-exclamation-triangle',
        '[data-id="bundles"]': 'fa fa-exclamation-triangle',
        '[data-id="my_orders"]': 'fa fa-exclamation-triangle'
      };
      $.each(menu_elements, function (element, value) {
        $(element).each(function () {
          if ($(this).find('i').length <= 1) {
            $(this).append("<i class=\"".concat(value, "\"></i>"));
          }
        });
      });
    }
    $(document).on('click', '.button_list_box a', function (event) {
      event.preventDefault();
      $(this).addClass('active');
      $(this).parent().siblings().find('a').removeClass('active');
      $('[data-field^="wpcfto_addon_option_sorting_"]').addClass('hidden');
      var float_menu_enabled = $('#section_4-float_menu').is(':checked');
      if ($(this).parent().is(':first-child')) {
        $('[data-field="wpcfto_addon_option_sorting_the_menu"]').toggleClass('hidden', float_menu_enabled);
        $('[data-field="wpcfto_addon_option_sorting_float_menu_main"]').toggleClass('hidden', !float_menu_enabled);
      } else {
        $('[data-field="wpcfto_addon_option_sorting_the_menu_student"]').toggleClass('hidden', float_menu_enabled);
        $('[data-field="wpcfto_addon_option_sorting_float_menu_learning"]').toggleClass('hidden', !float_menu_enabled);
      }
    });
  });
})(jQuery);