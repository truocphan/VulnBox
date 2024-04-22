"use strict";

(function ($) {
  $(document).ready(function () {
    $('.stm_lms_settings_button').on('click', function () {
      $('.stm-lms-user_edit_profile_btn').click();
    });
    $('.stm-lms-user-avatar-edit .delete_avatar').on('click', function () {
      var $this = $(this);
      var $parent = $this.closest('.stm-lms-user-avatar-edit');
      $parent.addClass('loading-avatar');
      var formData = new FormData();
      formData.append('action', 'stm_lms_delete_avatar');
      formData.append('nonce', stm_lms_nonces['stm_lms_delete_avatar']);
      $this.remove();
      $.ajax({
        url: stm_lms_ajaxurl,
        type: 'POST',
        data: formData,
        processData: false,
        // tell jQuery not to process the data
        contentType: false,
        // tell jQuery not to set contentType
        success: function success(data) {
          $parent.removeClass('loading-avatar');
          if (data.file) {
            var $avatar_img = $parent.find('img');
            $avatar_img.remove();
            $parent.find('.stm-lms-user_avatar').append(data.file);
            /*Set float menu image*/
            float_menu_image();
          }
        }
      });
    });
    function float_menu_image() {
      var $float_menu = $('.stm_lms_user_float_menu__user');
      if ($float_menu.length) {
        $float_menu.find('img').attr('src', $('.stm-lms-user_avatar').find('img').attr('src'));
      }
    }
    $('.stm-lms-user-avatar-edit input').on('change', function () {
      var $this = $(this);
      var files = $this[0].files;
      var $parent = $this.closest('.stm-lms-user-avatar-edit');
      $parent.addClass('loading-avatar');
      if (files.length) {
        var file = files[0];
        var formData = new FormData();
        formData.append('file', file);
        formData.append('action', 'stm_lms_change_avatar');
        formData.append('nonce', stm_lms_nonces['stm_lms_change_avatar']);
        $.ajax({
          url: stm_lms_ajaxurl,
          type: 'POST',
          data: formData,
          processData: false,
          // tell jQuery not to process the data
          contentType: false,
          // tell jQuery not to set contentType
          success: function success(data) {
            $parent.removeClass('loading-avatar');
            if (data.file) {
              $parent.find('img').attr('src', data.file);
              float_menu_image();
            }
          }
        });
      }
    });
    $('[data-container]').on('click', function (e) {
      e.preventDefault();
      var $default_container = $('[data-container-open=".stm_lms_private_information"]');
      var $container = $('[data-container-open="' + $(this).attr('data-container') + '"]');
      var container_visible = $container.is(':visible');

      /*Close all*/
      $('[data-container]').removeClass('active');
      $('[data-container-open]').slideUp();

      /*Open Current*/
      if (!container_visible) {
        $(this).addClass('active');
        $container.slideDown();
      } else {
        $default_container.slideDown();
      }
    });
    $('body').addClass('stm_lms_chat_page');
    new Vue({
      el: '#stm_lms_edit_account',
      data: function data() {
        return {
          data: stm_lms_edit_account_info,
          loading: false,
          message: '',
          status: 'error',
          additionalFields: [],
          new_visible: false,
          re_type_visible: false
        };
      },
      mounted: function mounted() {
        if (typeof window.profileForm !== 'undefined') {
          this.additionalFields = window.profileForm;
        }
      },
      methods: {
        processFields: function processFields(fields) {
          var _this = this;
          if (Object.keys(fields).length > 0) {
            var _loop = function _loop(fieldName) {
              if (fields.hasOwnProperty(fieldName)) {
                var additionalField = _this.additionalFields.find(function (af) {
                  return af.id === fieldName;
                });
                if (additionalField) {
                  if (additionalField.type === 'checkbox') {
                    var checkedValues = [];
                    $("[name=\"".concat(additionalField.slug, "\"]")).each(function () {
                      if ($(this).next().hasClass('masterstudy-form-builder__checkbox-wrapper_checked')) {
                        checkedValues.push($(this).val());
                      }
                    });
                    fields[fieldName] = checkedValues.join(',');
                  } else if (additionalField.type === 'radio') {
                    $("[name=\"".concat(additionalField.slug, "\"]")).each(function () {
                      if ($(this).next().hasClass('masterstudy-form-builder__radio-wrapper_checked')) {
                        fields[fieldName] = $(this).val();
                      }
                    });
                  } else if (additionalField.type === 'file') {
                    fields[fieldName] = $("[name=\"".concat(additionalField.slug, "\"]")).attr('data-url') || '';
                  } else {
                    fields[fieldName] = $("[name=\"".concat(additionalField.slug, "\"]")).val();
                  }
                }
              }
            };
            for (var fieldName in fields) {
              _loop(fieldName);
            }
          }
        },
        saveUserInfo: function saveUserInfo() {
          var vm = this;
          var data = {};
          var meta = vm.data.meta;
          this.processFields(vm.data.meta);
          Object.keys(meta).forEach(function (key) {
            vm.$set(data, key, meta[key]);
          });
          var url = stm_lms_ajaxurl + '?action=stm_lms_save_user_info&nonce=' + stm_lms_nonces['stm_lms_save_user_info'];
          vm.loading = true;
          vm.message = vm.status = '';
          this.$http.post(url, data).then(function (response) {
            vm.loading = false;
            vm.message = response.body['message'];
            vm.status = response.body['status'];
            if (response.body['relogin']) {
              window.location.href = response.body['relogin'];
            }

            // update Data
            var data_fields = {
              'bio': '',
              'facebook': 'href',
              'twitter': 'href',
              'google-plus': 'href',
              'position': '',
              'first_name': '',
              'instagram': 'href'
            };
            for (var k in data_fields) {
              if (data_fields.hasOwnProperty(k)) {
                if (data_fields[k]) {
                  $('.stm_lms_update_field__' + k).attr(data_fields[k], vm.data['meta'][k]);
                } else {
                  $('.stm_lms_update_field__' + k).text(vm.data['meta'][k]);
                }
              }
            }
          });
        },
        inputType: function inputType(variable) {
          return !this[variable] ? 'password' : 'text';
        }
      }
    });
  });
})(jQuery);