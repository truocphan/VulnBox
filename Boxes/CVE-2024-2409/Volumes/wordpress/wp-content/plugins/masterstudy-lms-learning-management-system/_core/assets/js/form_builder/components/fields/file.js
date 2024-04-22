"use strict";

Vue.component('stm_lms_forms_file', {
  props: ['field_data'],
  data: function data() {
    return {
      data: {}
    };
  },
  mounted: function mounted() {
    this.data = Object.assign({}, this.field_data);
  },
  template: "\n        <div>\n            <div class=\"file-wrap\">\n                <span class=\"file-browse\">Browse...</span>           \n                <span v-html=\"data.placeholder ? data.placeholder : 'No file selected'\" class=\"file-placeholder\"></span>      \n                <i class=\"fas fa-paperclip\"></i>     \n            </div>\n        </div>\n    ",
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