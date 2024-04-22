"use strict";

(function ($) {
  $(document).ready(function () {
    $('.stm_lms_courses_search').each(function () {
      var $this = $(this);
      new Vue({
        el: $(this)[0],
        data: function data() {
          return {
            vue_loaded: true,
            loading: false,
            search: '',
            url: ''
          };
        },
        mounted: function mounted() {
          Vue.nextTick(function () {
            $('.stm_lms_categories_dropdown').removeClass('vue_is_disabled');
          });
          this.url = stm_lms_search_value;
        },
        components: {
          autocomplete: Vue2Autocomplete["default"]
        },
        methods: {
          "goto": function goto() {
            console.log('goto');
          },
          searchCourse: function searchCourse(obj) {
            window.location.href = obj.url;
          },
          searching: function searching(value) {
            $this.addClass('loading');
            this.url = value;
          },
          loaded: function loaded() {
            $this.removeClass('loading');
          }
        }
      });
    });
  });
})(jQuery);