"use strict";

Vue.component('stm_lms_forms_email', {
  props: ['field_data'],
  data: function data() {
    return {
      data: {}
    };
  },
  mounted: function mounted() {
    this.data = Object.assign({}, this.field_data);
  },
  template: "\n        <div>\n            <input type=\"email\" v-model=\"data['value']\" :placeholder=\"typeof data.placeholder !== 'undefined' ? data.placeholder : ''\" />\n        </div>\n    ",
  methods: {},
  watch: {
    data: {
      deep: true,
      handler: function handler() {
        this.$emit('get-field', this.data);
      }
    }
  }
});