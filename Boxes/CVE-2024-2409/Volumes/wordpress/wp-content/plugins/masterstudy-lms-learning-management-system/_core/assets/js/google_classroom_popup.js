"use strict";

/**
 * @var google_classroom_data
 */

(function ($) {
  $(document).ready(function () {
    new Vue({
      el: '#google_classroom_popup',
      data: function data() {
        return {
          show_popup: false,
          auditories: google_classroom_popup['auditory'],
          bg: google_classroom_popup['bg'],
          search: ''
        };
      },
      methods: {},
      computed: {
        auditoriesList: function auditoriesList() {
          var _this = this;
          return this.auditories.filter(function (auditory) {
            return auditory.name.toLowerCase().includes(_this.search.toLowerCase());
          });
        }
      },
      mounted: function mounted() {
        var vm = this;
        setTimeout(function () {
          vm.$set(vm, 'show_popup', true);
        }, 300);
      },
      watch: {
        show_popup: function show_popup(show) {
          Vue.nextTick(function () {
            if (show) {
              var $ = jQuery;
              $('html, body').css({
                overflow: 'hidden'
              });
              $('.google_classroom_popup__auditories_wrapper').mCustomScrollbar();
            } else {
              var $ = jQuery;
              $('html, body').css({
                overflow: 'visible'
              });
              jQuery.cookie('google_classroom_popup', 'viewed', {
                path: '/'
              });
            }
          });
        }
      }
    });
  });
})(jQuery);