"use strict";

Vue.component('stm-fields-sidebar', {
  data: function data() {
    return {
      fields: []
    };
  },
  mounted: function mounted() {
    this.fields = stm_lms_form_fields;
  },
  methods: {
    getFields: function getFields() {}
  }
});