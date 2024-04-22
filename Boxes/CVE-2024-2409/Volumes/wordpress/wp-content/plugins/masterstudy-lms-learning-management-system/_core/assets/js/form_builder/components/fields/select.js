"use strict";

Vue.component('stm_lms_forms_select', {
  props: ['field_data'],
  data: function data() {
    return {
      data: {}
    };
  },
  mounted: function mounted() {
    this.data = Object.assign({}, this.field_data);
  },
  template: "\n        <div>\n            <select v-model=\"data['value']\" >\n                <option v-if=\"typeof data.placeholder !== 'undefined'\" v-html=\"data.placeholder\"></option>\n                <option v-else>Select something</option>\n                <option v-if=\"typeof data.choices !== 'undefined' && choice\" v-for=\"choice in data.choices\" v-html=\"choice\"></option>\n            </select>\n        </div>\n    ",
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