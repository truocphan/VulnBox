"use strict";

(function ($) {
  var $select_category, $toggler, select_value;
  /*SHOW/HIDE settings*/
  $(document).ready(function () {
    $select_category = $('select[name="stm_lms_course_private_category[]"]');
    $toggler = $('.toggle_all_category_courses');
    $select_category.on('change', function () {
      show_hide_toggler();
    });
    show_hide_toggler();
    $(document.body).on('click', 'input[name="save"]', function (e) {
      if ($select_category.val().length == 0) {
        e.preventDefault();
        $('.stm_membership_notice').css('display', 'block');
      }
    });
  });
  function show_hide_toggler() {
    select_value = $select_category.val();
    var select_label = $select_category.find('option:selected').text();
    if (select_value.length !== 0) {
      $toggler.show();
      $('.toggle_all_category_courses__label strong').html(select_label);
      $('.stm_membership_notice').css('display', 'none');
    } else {
      $toggler.hide();
      $('.toggle_all_category_courses__label strong').html('');
    }
  }
  new Vue({
    el: '#toggle_all_category_courses',
    data: function data() {
      return {
        inProgress: false,
        current_course: ''
      };
    },
    methods: {
      toggle: function toggle(action) {
        var _this = this;
        this.inProgress = true;
        _this.$http.get(stm_lms_ajaxurl + '?action=stm_lms_toggle_buying&m=' + action + '&nonce=' + stm_lms_nonces['stm_lms_toggle_buying'] + '&c=' + select_value).then(function (r) {
          r = r.body;
          if (r.next) {
            _this.toggle(action);
            _this.current_course = r.message;
          } else {
            _this.inProgress = false;
            _this.current_course = '';
          }
        });
      }
    }
  });
})(jQuery);