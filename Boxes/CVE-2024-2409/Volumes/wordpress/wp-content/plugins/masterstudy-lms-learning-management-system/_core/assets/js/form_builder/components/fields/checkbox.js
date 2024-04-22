"use strict";

Vue.component('stm_lms_forms_checkbox', {
  props: ['field_data'],
  data: function data() {
    return {
      data: {}
    };
  },
  mounted: function mounted() {
    this.data = Object.assign({}, this.field_data);
  },
  template: "\n        <div class=\"radio-wrap\">\n            <label v-if=\"typeof data['choices'] !== 'undefined'\" v-for=\"choice in data['choices']\" class=\"radio-label\">\n                <input v-if=\"choice\" type=\"checkbox\" />\n                {{choice}}\n            </label>            \n        </div>\n    ",
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