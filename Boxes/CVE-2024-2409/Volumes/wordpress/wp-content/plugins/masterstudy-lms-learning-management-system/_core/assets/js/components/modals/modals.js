"use strict";

function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
(function ($) {
  $(document).ready(function () {
    var all_classes = ['masterstudy-become-instructor-modal', 'masterstudy-enterprise-modal'];
    var parent_classes = all_classes.filter(function (className) {
      return $(".".concat(className)).length > 0;
    });
    parent_classes.forEach(function (parent_class) {
      setTimeout(function () {
        $(".".concat(parent_class)).removeAttr('style');
      }, 1000);
      $(".".concat(parent_class)).on('click', function (event) {
        if (event.target === this) {
          $(this).removeClass("".concat(parent_class, "_open"));
          $('body').removeClass("".concat(parent_class, "-body-hidden"));
        }
      });
      $("[data-masterstudy-modal=\"".concat(parent_class, "\"]")).on('click', function (e) {
        e.preventDefault();
        $(".".concat(parent_class, ":first")).addClass("".concat(parent_class, "_open"));
        $('body').addClass("".concat(parent_class, "-body-hidden"));
      });
      $(".".concat(parent_class, "__close, [data-id=\"").concat(parent_class, "-close-button\"]")).click(function (e) {
        e.preventDefault();
        $(this).closest(".".concat(parent_class)).removeClass("".concat(parent_class, "_open"));
        $('body').removeClass("".concat(parent_class, "-body-hidden"));
      });
      $(".".concat(parent_class, " input, .").concat(parent_class, " textarea")).on('input', function () {
        $(this).parent().find(".".concat(parent_class, "__form-field-error")).remove();
        $(this).parent().removeClass("".concat(parent_class, "__form-field_has-error"));
      });
      if ('masterstudy-become-instructor-modal' === parent_class && instructor_modal_data.submission_status) {
        $(".".concat(parent_class)).find('.masterstudy-become-instructor-modal__form').addClass('masterstudy-become-instructor-modal__form_hide');
        $(".".concat(parent_class)).find('.masterstudy-become-instructor-modal__actions').addClass('masterstudy-become-instructor-modal__actions_hide');
        $(".".concat(parent_class)).find('.masterstudy-become-instructor-modal__success').addClass('masterstudy-become-instructor-modal__success_show');
      }
    });
    $('[data-id="masterstudy-become-instructor-modal-confirm"]').click(function (e) {
      e.preventDefault();
      var request_data = {
        'fields_type': 'default',
        'fields': {
          'degree': $('input[name="degree"]').length ? $('input[name="degree"]').val() : '',
          'expertize': $('input[name="expertize"]').length ? $('input[name="expertize"]').val() : ''
        }
      };
      if (masterstudy_become_instructor_fields.length > 0) {
        processFields(masterstudy_become_instructor_fields);
        request_data['fields'] = masterstudy_become_instructor_fields;
        request_data['fields_type'] = 'custom';
      }
      send_request(request_data, 'masterstudy-become-instructor-modal');
    });
    $('[data-id="masterstudy-enterprise-modal-confirm"]').click(function (e) {
      e.preventDefault();
      var request_data = {
        'fields_type': 'default',
        'fields': {
          'enterprise_name': $('input[name="enterprise_name"]').length ? $('input[name="enterprise_name"]').val() : '',
          'enterprise_email': $('input[name="enterprise_email"]').length ? $('input[name="enterprise_email"]').val() : '',
          'enterprise_text': $('textarea[name="enterprise_text"]').length ? $('textarea[name="enterprise_text"]').val() : ''
        }
      };
      if (masterstudy_enterprise_fields.length > 0) {
        processFields(masterstudy_enterprise_fields);
        request_data['fields'] = masterstudy_enterprise_fields;
        request_data['fields_type'] = 'custom';
      }
      send_request(request_data, 'masterstudy-enterprise-modal');
    });
    function processFields(fields) {
      if (fields.length > 0) {
        var _iterator = _createForOfIteratorHelper(fields),
          _step;
        try {
          var _loop = function _loop() {
            var field = _step.value;
            if (!field.required) {
              field.required = '';
            }
            if (field.type === 'checkbox') {
              var checkedValues = [];
              $("[name=\"".concat(field.slug, "\"]")).each(function () {
                if ($(this).next().hasClass('masterstudy-form-builder__checkbox-wrapper_checked')) {
                  checkedValues.push($(this).val());
                }
              });
              field.value = checkedValues.join(',');
            } else if (field.type === 'radio') {
              $("[name=\"".concat(field.slug, "\"]")).each(function () {
                if ($(this).next().hasClass('masterstudy-form-builder__radio-wrapper_checked')) {
                  field.value = $(this).val();
                }
              });
            } else if (field.type === 'file') {
              field.value = field.value ? field.value : '';
            } else {
              field.value = $("[name=\"".concat(field.slug, "\"]")).val();
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
    function send_request(request_data, modal_type) {
      var action = modal_type === 'masterstudy-become-instructor-modal' ? 'stm_lms_become_instructor' : 'stm_lms_enterprise',
        nonce = modal_type === 'masterstudy-become-instructor-modal' ? instructor_modal_data.nonce : enterprise_modal_data.nonce,
        ajax_url = modal_type === 'masterstudy-become-instructor-modal' ? instructor_modal_data.ajax_url : enterprise_modal_data.ajax_url;
      $.ajax({
        url: "".concat(ajax_url, "?action=").concat(action, "&nonce=").concat(nonce),
        method: 'POST',
        data: request_data,
        beforeSend: function beforeSend() {
          $("[data-id=\"".concat(modal_type, "-confirm\"]")).addClass('masterstudy-button_loading');
        },
        success: function success(data) {
          $("[data-id=\"".concat(modal_type, "-confirm\"]")).removeClass('masterstudy-button_loading');
          if (data.status === 'error') {
            error_handling(data.errors, modal_type);
            return;
          }
          if (data.status === 'success') {
            if (modal_type === 'masterstudy-become-instructor-modal') {
              instructor_modal_data.instructor_premoderation ? open_success_message(modal_type) : location.reload();
            } else {
              open_success_message(modal_type);
            }
          }
        }
      });
    }
    function open_success_message(modal_type) {
      $(".".concat(modal_type, "__form")).addClass("".concat(modal_type, "__form_hide"));
      $(".".concat(modal_type, "__actions")).addClass("".concat(modal_type, "__actions_hide"));
      $(".".concat(modal_type, "__success")).addClass("".concat(modal_type, "__success_show"));
    }
    function error_handling(errors, modal_type) {
      errors.forEach(function (error) {
        var inputField = $("[name=\"".concat(error.field, "\"]")),
          html = "<span data-error-id=\"".concat(error.id, "\" class=\"").concat(modal_type, "__form-field-error\">").concat(error.text, "</span>");
        if (inputField.length > 0) {
          if (inputField.parent().hasClass('masterstudy-form-builder__checkbox')) {
            var container = inputField.closest('.masterstudy-form-builder__checkbox-group');
            var block = container.find('.masterstudy-form-builder__checkbox-description');
            $(container).parent().addClass("".concat(modal_type, "__form-field_has-error"));
            if (container.find("span[data-error-id=\"".concat(error.id, "\"]")).length === 0) {
              block.length > 0 ? $(html).insertBefore(block) : inputField.closest('.masterstudy-form-builder__checkbox-group').append(html);
            }
          } else if (inputField.parent().parent().hasClass('masterstudy-form-builder-file-upload')) {
            var _container = inputField.closest('.masterstudy-form-builder-file-upload');
            $(_container).parent().addClass("".concat(modal_type, "__form-field_has-error"));
            if ($(_container).find("span[data-error-id=\"".concat(error.id, "\"]")).length === 0) {
              $(_container).find('.masterstudy-form-builder-file-upload__field').after(html);
            }
          } else {
            inputField.parent().addClass("".concat(modal_type, "__form-field_has-error"));
            if (inputField.parent().find("span[data-error-id=\"".concat(error.id, "\"]")).length === 0) {
              inputField.after(html);
            }
          }
        }
      });
      var modalWrapper = $(".".concat(modal_type, "__container")),
        firstErrorField = $(".".concat(modal_type, "__form-field_has-error")).first();
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
  });
})(jQuery);