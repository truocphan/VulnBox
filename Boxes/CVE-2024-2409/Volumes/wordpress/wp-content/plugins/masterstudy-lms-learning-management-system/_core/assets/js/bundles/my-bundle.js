"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
(function ($) {
  $(document).ready(function () {
    new Vue({
      el: '#stm_lms_my_bundle',
      data: function data() {
        return {
          courses: stm_lms_my_bundle_courses['list']['posts'],
          bundle_courses: [],
          bundle_name: '',
          bundle_description: '',
          bundle_price: '',
          bundle_image_id: '',
          bundle_limit: stm_lms_my_bundle_courses['bundle_limit'],
          bundle_upload_image: '',
          select_course_search: '',
          select_course_open: false,
          status: '',
          message: '',
          loading: false
        };
      },
      mounted: function mounted() {
        var vm = this;
        var data = stm_lms_my_bundle['data'];
        if (typeof data !== 'undefined') {
          if (typeof data.post_title !== 'undefined') vm.$set(vm, 'bundle_name', data.post_title);
          if (typeof data.post_content !== 'undefined') vm.$set(vm, 'bundle_description', data.post_content);
          if (typeof data.bundle_price !== 'undefined') vm.$set(vm, 'bundle_price', data.bundle_price);
          if (typeof data.bundle_courses !== 'undefined') vm.$set(vm, 'bundle_courses', data.bundle_courses);
          if (typeof data.bundle_image_id !== 'undefined') vm.$set(vm, 'bundle_image_id', data.bundle_image_id);
        }
        Vue.nextTick(function () {
          if (typeof tinyMCE !== 'undefined') {
            var editor = tinyMCE.get(stm_lms_my_bundle_courses['editor_id']);
            if (editor !== null) {
              vm.$set(vm, 'bundle_description', editor.getContent());
              editor.on('keyup', function (e) {
                vm.$set(vm, 'bundle_description', editor.getContent());
              });
            } else {
              var $textarea = $('#' + stm_lms_my_bundle_courses['editor_id']);
              vm.$set(vm, 'bundle_description', $textarea.val());
              $textarea.on('change keyup paste', function () {
                vm.$set(vm, 'bundle_description', $textarea.val());
              });
            }
          }
        });
      },
      methods: {
        alreadyAdded: function alreadyAdded(course) {
          var vm = this;
          var inBundle = false;
          vm.bundle_courses.forEach(function (value) {
            if (value.id === course.id) inBundle = true;
          });
          return inBundle;
        },
        addCourseInBundle: function addCourseInBundle(course) {
          if (parseFloat(this.bundle_limit) <= this.bundle_courses.length) {
            alert('You reached maximum courses in bundle.');
            return false;
          }
          this.bundle_courses.push(course);
        },
        saveBundle: function saveBundle() {
          var vm = this;
          var name = vm.bundle_name;
          var courses = vm.bundleCoursesIds;
          var price = vm.bundle_price;
          var description = vm.bundle_description;
          vm.$set(vm, 'status', '');
          vm.$set(vm, 'message', '');
          var formData = new FormData();
          formData.append('id', stm_lms_my_bundle_courses['bundle_id']);
          formData.append('name', name);
          formData.append('courses', courses);
          formData.append('price', price);
          formData.append('description', description);
          formData.append('file', vm.bundle_upload_image);
          vm.loading = true;
          vm.$http.post(stm_lms_ajaxurl + '?action=stm_lms_save_bundle', formData).then(function (res) {
            res = res.body;
            vm.loading = false;
            var message = '';
            if (_typeof(res.message) === 'object') {
              for (var key in res.message) {
                message += res.message[key] + '<br>';
              }
            } else {
              message = res.message;
            }
            vm.$set(vm, 'status', res.status);
            vm.$set(vm, 'message', message);
            if (typeof res.url !== 'undefined') window.location.href = res.url;
          });
        },
        previewFiles: function previewFiles() {
          var vm = this;
          if (typeof this.$refs.bundleImage.files[0] !== 'undefined') {
            vm.$set(vm, 'bundle_upload_image', this.$refs.bundleImage.files[0]);
          }
        }
      },
      computed: {
        filteredList: function filteredList() {
          var _this = this;
          return this.courses.filter(function (course) {
            return course.title.toLowerCase().includes(_this.select_course_search.toLowerCase());
          });
        },
        totalPrice: function totalPrice() {
          var vm = this;
          var price = 0;
          vm.bundle_courses.forEach(function (value) {
            if (value['simple_price'] !== '') {
              price += parseFloat(value['simple_price']);
            }
          });
          return stm_lms_price_format(Math.round(price * 100) / 100);
        },
        bundleCoursesIds: function bundleCoursesIds() {
          var vm = this;
          var ids = [];
          vm.bundle_courses.forEach(function (value) {
            ids.push(value.id);
          });
          return ids;
        }
      }
    });
  });
})(jQuery);