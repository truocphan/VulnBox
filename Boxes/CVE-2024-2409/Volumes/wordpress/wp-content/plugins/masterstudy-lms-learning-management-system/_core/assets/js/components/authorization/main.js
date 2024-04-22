"use strict";

(function ($) {
  $(document).ready(function () {
    setTimeout(function () {
      $('.masterstudy-authorization-modal').removeAttr('style');
    }, 1000);
    $('[data-authorization-modal]').on('click', function (e) {
      e.preventDefault();
      var modalContainer = $('.masterstudy-authorization-modal');
      var formContainer = modalContainer.find('.masterstudy-authorization');
      $(this).attr('data-authorization-modal') === 'register' ? open_register_form(formContainer) : open_login_form(formContainer);
      modalContainer.addClass('masterstudy-authorization-modal_open');
      $('body').addClass('masterstudy-authorization-body-hidden');
    });
    $('.masterstudy-authorization-modal').on('click', function (event) {
      if (event.target === this) {
        $(this).removeClass('masterstudy-authorization-modal_open');
        $('body').removeClass('masterstudy-authorization-body-hidden');
      }
    });
    $('.masterstudy-authorization-modal__close, [data-id="masterstudy-authorization-close-button"]').click(function (e) {
      e.preventDefault();
      $(this).closest('.masterstudy-authorization-modal').removeClass('masterstudy-authorization-modal_open');
      $('body').removeClass('masterstudy-authorization-body-hidden');
      setTimeout(function () {
        var modal = $('[data-authorization-modal]'),
          modalContainer = $('.masterstudy-authorization-modal');
        if (modal.length > 0) {
          modal.attr('data-authorization-modal') === 'register' ? open_register_form(modalContainer) : open_login_form(modalContainer);
        } else {
          authorization_settings.register_mode ? open_register_form(modalContainer) : open_login_form(modalContainer);
        }
      }, 100);
    });
    $('.masterstudy-authorization').each(function () {
      var formContainer = $(this);
      formContainer.find('#masterstudy-authorization-sign-up').click(function (e) {
        e.preventDefault();
        open_register_form(formContainer);
        formContainer.find('#masterstudy-authorization-social-register').hide();
        formContainer.find('.masterstudy-authorization__separator').hide();
      });
      formContainer.find('#masterstudy-authorization-sign-in').click(function (e) {
        e.preventDefault();
        open_login_form(formContainer);
        formContainer.find('.masterstudy-authorization__separator').show();
      });
      formContainer.find('.masterstudy-authorization__switch-lost-pass').click(function () {
        $(this).addClass('masterstudy-authorization__switch-lost-pass_hide');
        formContainer.find('.masterstudy-authorization__wrapper').addClass('masterstudy-authorization__wrapper_hide');
        formContainer.find('.masterstudy-authorization__restore').addClass('masterstudy-authorization__restore_show');
      });
      formContainer.find('.masterstudy-authorization__restore-header-back').click(function () {
        formContainer.find('.masterstudy-authorization__switch-lost-pass').removeClass('masterstudy-authorization__switch-lost-pass_hide');
        formContainer.find('.masterstudy-authorization__wrapper').removeClass('masterstudy-authorization__wrapper_hide');
        formContainer.find('.masterstudy-authorization__restore').removeClass('masterstudy-authorization__restore_show');
      });
      formContainer.find('.masterstudy-authorization__form-show-pass').click(function () {
        $(this).toggleClass('masterstudy-authorization__form-show-pass_open');
        var field = $(this).parent().find('input');
        field.attr('type', field.attr('type') === 'password' ? 'text' : 'password');
      });
      formContainer.find('.masterstudy-authorization__checkbox-title').click(function () {
        $(this).parent().find('.masterstudy-authorization__checkbox-wrapper').trigger('click');
      });
      formContainer.find('.masterstudy-authorization__gdpr-text').click(function () {
        formContainer.find('.masterstudy-authorization__gdpr .masterstudy-authorization__checkbox-wrapper').trigger('click');
      });
      formContainer.find('.masterstudy-authorization__instructor-text').click(function () {
        formContainer.find('.masterstudy-authorization__instructor .masterstudy-authorization__checkbox-wrapper').trigger('click');
      });
      formContainer.find('.masterstudy-authorization__checkbox-wrapper').click(function () {
        $(this).toggleClass('masterstudy-authorization__checkbox-wrapper_checked');
        var input = $(this).prev();
        var container = input.closest('masterstudy-authorization__checkbox-group');
        input.prop('checked', !input.prop('checked'));
        if (input.attr('id') === 'masterstudy-authorization-gdbr') {
          formContainer.find('[data-error-id="policy"]').remove();
        }
        if (input.attr('id') === 'masterstudy-authorization-instructor') {
          formContainer.find('.masterstudy-authorization__instructor-container').toggleClass('masterstudy-authorization__instructor-container_open');
        }
        if (container.length > 0) {
          container.find('[data-error-id="required"]').remove();
        }
      });
      formContainer.find('input, textarea').on('input', function () {
        $(this).parent().find('.masterstudy-authorization__form-field-error').remove();
        $(this).parent().removeClass('masterstudy-authorization__form-field_has-error');
      });
      formContainer.find('.masterstudy-authorization__separator-signup').hide();
      formContainer.find('#masterstudy-authorization-social-register').hide();
    });
  });
  function open_register_form(formContainer) {
    setFormProperties('register', authorization_settings.titles.register, formContainer);
  }
  function open_login_form(formContainer) {
    setFormProperties('login', authorization_settings.titles.login, formContainer);
  }
  function setFormProperties(formType, titles, formContainer) {
    formContainer.removeClass(formType === 'register' ? 'masterstudy-authorization_login' : 'masterstudy-authorization_register').addClass("masterstudy-authorization_".concat(formType));
    if (!authorization_data.only_for_instructor) {
      formType === 'register' ? formContainer.find('.masterstudy-authorization__instructor-page').removeClass('masterstudy-authorization__instructor-page_hide') : formContainer.find('.masterstudy-authorization__instructor-page').addClass('masterstudy-authorization__instructor-page_hide');
    }
    formContainer.find('.masterstudy-authorization__switch-lost-pass').removeClass('masterstudy-authorization__switch-lost-pass_hide');
    formContainer.find('.masterstudy-authorization__wrapper').removeClass('masterstudy-authorization__wrapper_hide');
    formContainer.find('.masterstudy-authorization__restore').removeClass('masterstudy-authorization__restore_show');
    formContainer.find('#masterstudy-authorization-restore-pass').removeClass('masterstudy-authorization__send-mail_show');
    formContainer.find('#masterstudy-authorization-confirm-email').removeClass('masterstudy-authorization__send-mail_show');
    formContainer.find('.masterstudy-authorization__switch').removeClass('masterstudy-authorization__switch_hide');
    formContainer.find('.masterstudy-authorization__header-title').text(titles.main);
    formContainer.find('.masterstudy-authorization__separator-title').text(titles.separator);
    formContainer.find('.masterstudy-authorization__switch-account-title').text(titles.account);
    formContainer.find('.masterstudy-authorization__form-field-error_privacy_policy').toggleClass('masterstudy-authorization__form-field-error_hide');
  }
})(jQuery);