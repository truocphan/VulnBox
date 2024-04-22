"use strict";

(function ($) {
  var notifyAlertButton = $('.coming-soon-notify-alert');
  var notifyContainer = $('.coming-soon-notify-container');
  var notifyModalWrapper = $('.coming-soon-modal-abs-wrapper');
  var notifyMeBtn = $('.coming-soon-notify-me');
  notifyAlertButton.on('click', function (e) {
    $(this).toggleClass('notify-me');
    notifyMeBtn.prop('disabled', false);
    if (!stm_coming_soon_ajax_variable.is_logged) {
      notifyContainer.css('display', notifyContainer.css('display') === 'none' ? 'flex' : 'none');
    }
    $.ajax({
      type: 'POST',
      url: stm_coming_soon_ajax_variable.url,
      data: {
        action: 'coming_soon_notify_me',
        email: '',
        nonce: stm_coming_soon_ajax_variable.nonce,
        id: stm_coming_soon_ajax_variable.course_id
      },
      success: function success(response) {
        if (response.success) {
          notifyAlertButton.addClass('added-email');
          notifyModalWrapper.css('display', 'block');
          $('.coming-soon-modal-wrapper h2').html(response.title);
          $('.coming-soon-modal-wrapper p').html(response.description);
        }
      },
      error: function error(xhr, status, _error) {
        console.error(_error);
      }
    });
  });
  $('.coming-soon-notify-container input').on('input', function () {
    $(this).css('border-color', '#f0f2f5');
  });
  notifyMeBtn.click(function () {
    var email = $('.coming-soon-notify-container input').val();
    if (!isValidEmail(email)) {
      notifyContainer.addClass('validation-error');
      $('.coming-soon-notify-container input').css('border-color', 'red');
      console.error('Please enter a valid email address.');
      return;
    }
    $('.coming-soon-notify-container input').css('border-color', '#f0f2f5');
    notifyContainer.removeClass('validation-error');
    $(this).prop('disabled', true);
    $.ajax({
      type: 'POST',
      url: stm_coming_soon_ajax_variable.url,
      data: {
        action: 'coming_soon_notify_me',
        email: email,
        nonce: stm_coming_soon_ajax_variable.nonce,
        id: stm_coming_soon_ajax_variable.course_id
      },
      success: function success(response) {
        if (response.success) {
          notifyAlertButton.addClass('added-email');
          notifyModalWrapper.css('display', 'block');
          $('.coming-soon-modal-wrapper h2').html(response.title);
          $('.coming-soon-modal-wrapper p').html(response.description);
        }
      },
      error: function error(xhr, status, _error2) {
        console.error(_error2);
      }
    });
  });
  $('.coming-soon-modal-close, .coming-soon-btn').click(function () {
    $('.coming-soon-modal-abs-wrapper, .coming-soon-notify-container').css('display', 'none');
  });
  function isValidEmail(email) {
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return emailPattern.test(email);
  }
  $('.stm-curriculum-item .stm-curriculum-item__preview').css('display', 'none');
})(jQuery);