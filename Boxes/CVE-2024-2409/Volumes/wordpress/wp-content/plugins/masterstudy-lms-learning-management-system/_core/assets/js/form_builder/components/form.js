"use strict";

Vue.component('stm-fields-form', {
  data: function data() {
    return {
      fields: ['text', 'number', 'image', 'textarea']
    };
  },
  mounted: function mounted() {},
  template: "<div id=\"form_fields\">\n        <div class=\"form_field\" v-for=\"field in fields\">\n            <div class=\"field\">{{field}}</div>\n        </div>\n    </div>",
  methods: {}
});