"use strict";

new Vue({
  el: '#stm_lms_form_builder',
  data: function data() {
    return {
      globalForms: stm_lms_forms,
      loading: false,
      currentForm: '',
      forms: [],
      currentField: '',
      requiredFields: [],
      openSelect: false
    };
  },
  mounted: function mounted() {
    this.getForms();
    this.currentForm = this.globalForms.currentForm;
    this.requiredFields = this.globalForms.required_fields;
  },
  methods: {
    toggleSelect: function toggleSelect() {
      this.openSelect = !this.openSelect;
    },
    documentClick: function documentClick(e) {
      var el = this.$refs.dropdownMenu;
      var target = e.target;
      if (el !== target && !el.contains(target)) {
        this.openSelect = false;
      }
    },
    getForms: function getForms() {
      var forms = JSON.parse(JSON.stringify(this.globalForms.forms));
      this.$set(this, 'forms', forms);
    },
    cloneField: function cloneField(field) {
      var id = this.generateID();
      if (field.type === 'radio' || field.type === 'select' || field.type === 'checkbox') {
        this.$set(field, 'choices', ['', '', '']);
      }
      this.$set(field, 'id', id);
      return field;
    },
    generateID: function generateID() {
      return Math.random().toString(36).substr(2);
    },
    duplicateField: function duplicateField(field) {
      var newField = Object.assign({}, field);
      newField.id = this.generateID();
      var fields = this.forms[this.currentForm]['fields'];
      fields.push(newField);
      this.$set(this.forms[this.currentForm], 'fields', fields);
      this.$set(this, 'currentField', fields.length - 1);
    },
    deleteField: function deleteField(field_index) {
      this.forms[this.currentForm]['fields'].splice(field_index, 1);
      this.currentField = '';
    },
    changeCurrentField: function changeCurrentField(index) {
      this.currentField = index;
    },
    afterDrag: function afterDrag(field) {
      if (typeof field.newIndex !== 'undefined') {
        this.$set(this, 'currentField', field.newIndex);
      }
    },
    saveForms: function saveForms() {
      var _this = this;
      _this.loading = true;
      var data = {
        requiredFields: _this.requiredFields,
        forms: _this.forms
      };
      _this.$http.post("".concat(stm_lms_ajaxurl, "?action=stm_lms_save_forms&nonce=").concat(stm_lms_nonces['stm_lms_save_forms']), data).then(function (r) {
        _this.loading = false;
      });
    }
  },
  created: function created() {
    document.addEventListener('click', this.documentClick);
  },
  destroyed: function destroyed() {
    document.removeEventListener('click', this.documentClick);
  }
});