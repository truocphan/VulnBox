"use strict";

Vue.component('v-select', VueSelect.VueSelect);
Vue.component('stm-user-search', {
  data: function data() {
    return {
      options: [],
      timeOut: null
    };
  },
  mounted: function mounted() {},
  methods: {
    onSearch: function onSearch(search, loading) {
      var vm = this;
      clearTimeout(this.timeOut);
      this.timeOut = setTimeout(function () {
        loading(true);
        vm.search(loading, search, vm);
      }, 250);
    },
    search: function search(loading, _search, vm) {
      var formData = new FormData();
      formData.append('search', _search);
      vm.$http.post(stm_payout_url_data['url'] + '/stm-lms-user/search', formData, {
        headers: {
          'X-WP-Nonce': ms_lms_nonce
        }
      }).then(function (response) {
        loading(false);
        vm.options = response.body;
      });
    }
  },
  props: {
    user: {
      "default": {
        id: null,
        name: '',
        email: ''
      }
    }
  },
  watch: {
    user: {
      handler: function handler(val) {
        if (this.user != null) this.$emit('stm-user-search', this.user);
      },
      deep: true
    }
  }
});
document.addEventListener('DOMContentLoaded', function () {
  new Vue({
    el: '.stm-user-search-app',
    data: {
      user: null
    },
    methods: {
      selectUser: function selectUser(user) {
        this.user = user;
      }
    }
  });
});