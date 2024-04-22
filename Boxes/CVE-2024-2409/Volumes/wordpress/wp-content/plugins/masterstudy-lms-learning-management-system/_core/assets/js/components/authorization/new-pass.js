"use strict";

(function ($) {
  $(document).ready(function () {
    $('.masterstudy-authorization').each(function () {
      var formContainer = $(this);
      formContainer.find('.masterstudy-authorization__form-show-pass').click(function () {
        $(this).toggleClass('masterstudy-authorization__form-show-pass_open');
        var field = $(this).parent().find('input');
        field.attr('type', field.attr('type') === 'password' ? 'text' : 'password');
      });
      formContainer.find('[data-id="masterstudy-authorization-new-pass-button"]').click(function (e) {
        e.preventDefault();
        if (new_pass_data.token.length > 0) {
          var data = {
            'token': new_pass_data.token,
            'new_password': $('input[name="user_new_password"]').val(),
            'repeat_password': $('input[name="user_repeat_new_password"]').val()
          };
          var url = new_pass_data.ajax_url + '?action=stm_lms_restore_password&nonce=' + new_pass_data.nonce;
          formContainer.find('[data-id="masterstudy-authorization-new-pass-button"]').addClass('masterstudy-button_loading');
          fetch(url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
          }).then(function (response) {
            if (response.ok) {
              return response.json();
            }
            throw new Error('not ok');
          }).then(function (data) {
            formContainer.find('[data-id="masterstudy-authorization-new-pass-button"]').removeClass('masterstudy-button_loading');
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
              location.reload();
            }
          });
        }
      });
    });
  });
})(jQuery);