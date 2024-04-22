"use strict";

function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
(function ($) {
  $(document).ready(function () {
    $('.masterstudy-authorization').each(function () {
      var formContainer = $(this);
      formContainer.find('[data-id="masterstudy-authorization-restore-button"]').click(function (e) {
        e.preventDefault();
        restore(formContainer);
      });
      formContainer.find('.masterstudy-authorization__form-input').keypress(function (e) {
        if (e.which === 13) {
          if ($(this).closest('#masterstudy-authorization-form-login').length > 0) {
            formContainer.find('[data-id="masterstudy-authorization-login-button"]').trigger('click');
          } else if ($(this).closest('#masterstudy-authorization-form-register').length > 0) {
            formContainer.find('[data-id="masterstudy-authorization-register-button"]').trigger('click');
          }
        }
      });
      formContainer.find('[data-id="masterstudy-authorization-login-button"]').click(function (e) {
        e.preventDefault();
        var login_data = {
          'user_login': formContainer.find('input[name="user_login"]').val(),
          'user_password': formContainer.find('input[name="user_password"]').val(),
          'remember': formContainer.find('#masterstudy-authorization-remember').prop('checked')
        };
        if (authorization_data.recaptcha_site_key && typeof grecaptcha !== 'undefined') {
          grecaptcha.ready(function () {
            grecaptcha.execute(authorization_data.recaptcha_site_key, {
              action: 'login'
            }).then(function (token) {
              login_data['recaptcha'] = token;
              login(login_data, authorization_data.only_for_instructor, formContainer);
            });
          });
        } else {
          login(login_data, authorization_data.only_for_instructor, formContainer);
        }
      });
      formContainer.find('[data-id="masterstudy-authorization-register-button"]').click(function (e) {
        e.preventDefault();
        if (Object.keys(authorization_data.default_fields).length > 0) {
          for (var fieldName in authorization_data.default_fields) {
            if (authorization_data.default_fields.hasOwnProperty(fieldName)) {
              authorization_data.default_fields[fieldName].value = formContainer.find("[name=\"".concat(fieldName, "\"]")).val();
            }
          }
        }
        if (authorization_data.additional_fields.length > 0) {
          processFields(authorization_data.additional_fields, formContainer);
        }
        if (authorization_data.instructor_fields.length > 0) {
          processFields(authorization_data.instructor_fields, formContainer);
        }
        var register_data = {
          'register_user_login': formContainer.find('input[name="register_user_login"]').val(),
          'register_user_email': formContainer.find('input[name="register_user_email"]').val(),
          'register_user_password': formContainer.find('input[name="register_user_password"]').val(),
          'register_user_password_re': formContainer.find('input[name="register_user_password_re"]').val(),
          'profile_default_fields_for_register': authorization_data.default_fields,
          'become_instructor': authorization_data.only_for_instructor ? true : formContainer.find('#masterstudy-authorization-instructor').length > 0 ? formContainer.find('#masterstudy-authorization-instructor').prop('checked') : false,
          'additional': authorization_data.additional_fields,
          'privacy_policy': formContainer.find('#masterstudy-authorization-gdbr').length > 0 ? formContainer.find('#masterstudy-authorization-gdbr').prop('checked') : true,
          'redirect_page': authorization_data.only_for_instructor ? authorization_data.user_account_page : window.location.href
        };
        register_data['additional_instructors'] = register_data['become_instructor'] ? authorization_data.instructor_fields : [];
        register_data['degree'] = formContainer.find('input[name="degree"]').length > 0 && register_data['become_instructor'] ? formContainer.find('input[name="degree"]').val() : '';
        register_data['expertize'] = formContainer.find('input[name="expertize"]').length > 0 && register_data['become_instructor'] ? formContainer.find('input[name="expertize"]').val() : '';
        if (authorization_data.recaptcha_site_key && typeof grecaptcha !== 'undefined') {
          grecaptcha.ready(function () {
            grecaptcha.execute(authorization_data.recaptcha_site_key, {
              action: 'register'
            }).then(function (token) {
              register_data['recaptcha'] = token;
              register(register_data, authorization_data.only_for_instructor, formContainer);
            });
          });
        } else {
          register(register_data, authorization_data.only_for_instructor, formContainer);
        }
      });
      formContainer.find('[data-id="masterstudy-authorization-instructor-confirm"]').click(function (e) {
        e.preventDefault();
        var request_data = {
          'fields_type': 'default',
          'fields': {
            'degree': formContainer.find('input[name="degree"]').length ? formContainer.find('input[name="degree"]').val() : '',
            'expertize': formContainer.find('input[name="expertize"]').length ? formContainer.find('input[name="expertize"]').val() : ''
          }
        };
        if (authorization_data.instructor_fields.length > 0) {
          processFields(authorization_data.instructor_fields, formContainer);
          request_data['fields'] = authorization_data.instructor_fields;
          request_data['fields_type'] = 'custom';
        }
        instructor_request(request_data, formContainer);
      });
    });
  });
  function processFields(fields, formContainer) {
    if (fields.length > 0) {
      var _iterator = _createForOfIteratorHelper(fields),
        _step;
      try {
        var _loop = function _loop() {
          var field = _step.value;
          if (field.type === 'checkbox') {
            var checkedValues = [];
            formContainer.find("[name=\"".concat(field.slug, "\"]")).each(function () {
              if ($(this).next().hasClass('masterstudy-form-builder__checkbox-wrapper_checked')) {
                checkedValues.push($(this).val());
              }
            });
            field.value = checkedValues.join(',');
          } else if (field.type === 'radio') {
            formContainer.find("[name=\"".concat(field.slug, "\"]")).each(function () {
              if ($(this).next().hasClass('masterstudy-form-builder__radio-wrapper_checked')) {
                field.value = $(this).val();
              }
            });
          } else if (field.type === 'file') {
            field.value = field.value ? field.value : '';
          } else {
            field.value = formContainer.find("[name=\"".concat(field.slug, "\"]")).val();
          }
        };
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          _loop();
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }
    }
  }
  function login(login_data, redirect, formContainer) {
    var url = authorization_data.ajax_url + '?action=stm_lms_login&nonce=' + authorization_data.login_nonce;
    formContainer.find('[data-id="masterstudy-authorization-login-button"]').addClass('masterstudy-button_loading');
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(login_data)
    }).then(function (response) {
      if (response.ok) {
        return response.json();
      }
      throw new Error('not ok');
    }).then(function (data) {
      formContainer.find('[data-id="masterstudy-authorization-login-button"]').removeClass('masterstudy-button_loading');
      if (data.status === 'error') {
        data.errors.forEach(function (error) {
          var inputField = formContainer.find("input[name=\"".concat(error.field, "\"]"));
          var html = "<span data-error-id=\"".concat(error.id, "\" class=\"masterstudy-authorization__form-field-error\">").concat(error.text, "</span>");
          if (inputField.length > 0) {
            inputField.parent().addClass('masterstudy-authorization__form-field_has-error');
            if (inputField.parent().find("span[data-error-id=\"".concat(error.id, "\"]")).length === 0) {
              inputField.after(html);
            }
          }
        });
        return;
      }
      if (data.status === 'success') {
        redirect ? window.location = data.user_page : location.reload();
      }
    });
  }
  function register(register_data, redirect, formContainer) {
    var url = authorization_data.ajax_url + '?action=stm_lms_register&nonce=' + authorization_data.register_nonce;
    formContainer.find('[data-id="masterstudy-authorization-register-button"]').addClass('masterstudy-button_loading');
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(register_data)
    }).then(function (response) {
      if (response.ok) {
        return response.json();
      }
      throw new Error('not ok');
    }).then(function (data) {
      formContainer.find('[data-id="masterstudy-authorization-register-button"]').removeClass('masterstudy-button_loading');
      if (data.status === 'error') {
        error_handling(data.errors, formContainer);
        return;
      }
      if (data.status === 'success') {
        if (authorization_data.email_confirmation) {
          open_confirmation_form(false, formContainer);
          return;
        }
        if (authorization_data.only_for_instructor && authorization_data.instructor_premoderation) {
          open_confirmation_form(true, formContainer);
          return;
        }
        redirect ? window.location = data.user_page : location.reload();
      }
    });
  }
  function instructor_request(request_data, formContainer) {
    $.ajax({
      url: authorization_data.ajax_url + '?action=stm_lms_become_instructor' + '&nonce=' + authorization_data.instructor_nonce,
      method: 'POST',
      data: request_data,
      beforeSend: function beforeSend() {
        formContainer.find('[data-id="masterstudy-authorization-instructor-confirm"]').addClass('masterstudy-button_loading');
      },
      success: function success(data) {
        formContainer.find('[data-id="masterstudy-authorization-instructor-confirm"]').removeClass('masterstudy-button_loading');
        if (data.status === 'error') {
          error_handling(data.errors, formContainer);
          return;
        }
        if (data.status === 'success') {
          if (authorization_data.only_for_instructor && authorization_data.instructor_premoderation) {
            open_confirmation_form(true, formContainer);
            return;
          }
          window.location = authorization_data.user_account_page;
        }
      }
    });
  }
  function error_handling(errors, formContainer) {
    errors.forEach(function (error) {
      var inputField = formContainer.find("[name=\"".concat(error.field, "\"]")),
        html = "<span data-error-id=\"".concat(error.id, "\" class=\"masterstudy-authorization__form-field-error\">").concat(error.text, "</span>");
      if (inputField.length > 0) {
        if (error.field === 'privacy_policy' || error.field === 'recaptcha') {
          if (formContainer.find('.masterstudy-authorization').find("span[data-error-id=\"".concat(error.id, "\"]")).length === 0) {
            var errorElement = $(html);
            errorElement.addClass("masterstudy-authorization__form-field-error_main masterstudy-authorization__form-field-error_".concat(error.field));
            formContainer.find('.masterstudy-authorization__actions').before(errorElement);
          }
        } else {
          if (inputField.parent().hasClass('masterstudy-form-builder__checkbox')) {
            var container = inputField.closest('.masterstudy-form-builder__checkbox-group');
            var block = container.find('.masterstudy-form-builder__checkbox-description');
            $(container).parent().addClass('masterstudy-authorization__form-field_has-error');
            if (container.find("span[data-error-id=\"".concat(error.id, "\"]")).length === 0) {
              block.length > 0 ? $(html).insertBefore(block) : inputField.closest('.masterstudy-form-builder__checkbox-group').append(html);
            }
          } else if (inputField.parent().parent().hasClass('masterstudy-form-builder-file-upload')) {
            var _container = inputField.closest('.masterstudy-form-builder-file-upload');
            $(_container).parent().addClass('masterstudy-authorization__form-field_has-error');
            if ($(_container).find("span[data-error-id=\"".concat(error.id, "\"]")).length === 0) {
              $(_container).find('.masterstudy-form-builder-file-upload__field').after(html);
            }
          } else {
            inputField.parent().addClass('masterstudy-authorization__form-field_has-error');
            if (inputField.parent().find("span[data-error-id=\"".concat(error.id, "\"]")).length === 0) {
              inputField.after(html);
            }
          }
        }
      }
    });
    var modalWrapper = formContainer.closest('.masterstudy-authorization-modal__container'),
      firstErrorField = formContainer.find('.masterstudy-authorization__form-field_has-error').first();
    if (firstErrorField.length > 0) {
      var topOffset = firstErrorField.offset().top;
      if (modalWrapper.length > 0) {
        topOffset -= modalWrapper.offset().top;
        var scrollTo = modalWrapper.scrollTop() + topOffset - 10;
        modalWrapper.animate({
          scrollTop: scrollTo
        }, 500);
      } else {
        if (!isElementVisible(firstErrorField[0])) {
          $('html, body').animate({
            scrollTop: topOffset - 10
          }, 500);
        }
      }
    }
  }
  function isElementVisible(element) {
    var rect = element.getBoundingClientRect();
    return rect.top >= 0 && rect.left >= 0 && rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && rect.right <= (window.innerWidth || document.documentElement.clientWidth);
  }
  function restore(formContainer) {
    var url = authorization_data.ajax_url + '?action=stm_lms_lost_password&nonce=' + authorization_data.restore_nonce,
      user_mail = formContainer.find('input[name="restore_user_login"]').val(),
      restore_data = JSON.stringify({
        'restore_user_login': user_mail
      });
    formContainer.find('[data-id="masterstudy-authorization-restore-button"]').addClass('masterstudy-button_loading');
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: restore_data
    }).then(function (response) {
      if (response.ok) {
        return response.json();
      }
      throw new Error('not ok');
    }).then(function (data) {
      formContainer.find('[data-id="masterstudy-authorization-restore-button"]').removeClass('masterstudy-button_loading');
      if (data.status === 'error') {
        data.errors.forEach(function (error) {
          var inputField = formContainer.find("input[name=\"".concat(error.field, "\"]"));
          var html = "<span data-error-id=\"".concat(error.id, "\" class=\"masterstudy-authorization__form-field-error\">").concat(error.text, "</span>");
          if (inputField.length > 0) {
            inputField.parent().addClass('masterstudy-authorization__form-field_has-error');
            if (inputField.parent().find("span[data-error-id=\"".concat(error.id, "\"]")).length === 0) {
              inputField.after(html);
            }
          }
        });
        return;
      }
      if (data.status === 'success') {
        formContainer.find('.masterstudy-authorization__send-mail-content-subtitle').text(function (index, oldText) {
          return oldText + user_mail;
        });
        formContainer.find('.masterstudy-authorization__restore').removeClass('masterstudy-authorization__restore_show');
        formContainer.find('#masterstudy-authorization-restore-pass').addClass('masterstudy-authorization__send-mail_show');
        formContainer.find('.masterstudy-authorization__switch').addClass('masterstudy-authorization__switch_hide');
      }
    });
  }
  function open_confirmation_form(for_instructor, formContainer) {
    formContainer.find('.masterstudy-authorization__wrapper').addClass('masterstudy-authorization__wrapper_hide');
    formContainer.find('.masterstudy-authorization__switch').addClass('masterstudy-authorization__switch_hide');
    if (for_instructor) {
      formContainer.find('.masterstudy-authorization__instructor-confirm').addClass('masterstudy-authorization__instructor-confirm_show');
    } else {
      formContainer.find('#masterstudy-authorization-confirm-email').addClass('masterstudy-authorization__send-mail_show');
    }
  }
})(jQuery);