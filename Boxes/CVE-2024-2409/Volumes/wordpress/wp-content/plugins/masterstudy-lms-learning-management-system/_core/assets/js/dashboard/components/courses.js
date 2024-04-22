"use strict";

/**
 *
 * @var stm_lms_ajaxurl
 */

stm_lms_components['courses'] = {
  template: '#stm-lms-dashboard-courses',
  components: {
    back: stm_lms_components['back']
  },
  data: function data() {
    return {
      loading: false,
      courses: [],
      pages: 1,
      page: 1,
      per_page: 1
    };
  },
  mounted: function mounted() {
    this.getCourses();
  },
  methods: {
    getCourses: function getCourses() {
      var _this = this;
      _this.loading = true;
      var url = stm_lms_ajaxurl + '?action=stm_lms_dashboard_get_courses_list';
      url += '&offset=' + (_this.page - 1);
      url += '&nonce=' + stm_lms_nonces['stm_lms_dashboard_get_courses_list'];
      _this.$http.get(url).then(function (response) {
        response = response.body;
        _this.loading = false;
        _this.$set(_this, 'courses', response.posts);
        _this.$set(_this, 'pages', response.pages);
        _this.$set(_this, 'per_page', response.per_page);
      });
    },
    switchPage: function switchPage(page) {
      this.$set(this, 'page', page);
      this.getCourses();
    }
  }
};