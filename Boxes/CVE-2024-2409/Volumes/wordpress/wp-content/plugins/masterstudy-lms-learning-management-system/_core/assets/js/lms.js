"use strict";

(function ($) {
  document.addEventListener('lazybeforeunveil', function (e) {
    $(e.target).closest('.stm_lms_lazy_image').addClass('stm_lms_lazyloaded');
  });
  $(document).ready(function () {
    $('body').on('click', '#lesson_settings .mx-datepicker-popup', function (e) {
      e.preventDefault();
    });
    $('.stm_lms_log_in[data-lms-modal]').on('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      if ($('.stm-lms-wrapper__login').length) {
        $(this).removeAttr('data-lms-modal');
        $([document.documentElement, document.body]).animate({
          scrollTop: $(".stm-lms-wrapper__login").offset().top
        }, 300);
      }
    });
    $('body').on('click', '.modal-close', function (e) {
      e.preventDefault();
      $('.stm-lms-modal-login').modal('toggle');
    });
    var modal_body = [];
    $('[data-lms-modal]').on('click', function (e) {
      e.preventDefault();
      var modal_target = $(this).attr('data-target');
      if (!$(modal_target).length) {
        var modal = $(this).attr('data-lms-modal');
        var params = $(this).attr('data-lms-params');
        $.ajax({
          url: stm_lms_ajaxurl,
          dataType: 'json',
          context: this,
          data: {
            action: 'stm_lms_load_modal',
            modal: modal,
            nonce: stm_lms_nonces['load_modal'],
            params: params
          },
          beforeSend: function beforeSend() {
            $(this).addClass('loading');
          },
          complete: function complete(data) {
            var data = data['responseJSON'];
            $(this).addClass('modal-loaded');
            $(this).removeClass('loading');
            modal_body[modal_target] = $(data['modal']).appendTo('body');
            toggleModal(modal_target);
          }
        });
      } else {
        toggleModal(modal_target);
      }
    });
    function toggleModal(modal) {
      $(modal).modal('toggle');
    }
    $('[data-buy-course]').on('click', function (e) {
      var item_id = $(this).attr('data-buy-course');
      if (typeof item_id === 'undefined') {
        window.location = $(this).attr('href');
        return false;
      }
      $.ajax({
        url: stm_lms_ajaxurl,
        dataType: 'json',
        context: this,
        data: {
          action: 'stm_lms_add_to_cart',
          nonce: stm_lms_nonces['stm_lms_add_to_cart'],
          item_id: item_id
        },
        beforeSend: function beforeSend() {
          $(this).addClass('loading');
        },
        complete: function complete(data) {
          var data = data['responseJSON'];
          $(this).removeClass('loading');
          $(this).find('span').text(data['text']);
          if (data['cart_url']) {
            if (data['redirect']) window.location = data['cart_url'];
            $(this).attr('href', data['cart_url']).removeAttr('data-buy-course');
          }
        }
      });
      e.preventDefault();
    });
    $('[data-delete-course]').on('click', function (e) {
      e.preventDefault();
      var item_id = $(this).data('delete-course');
      var group_id = $(this).data('delete-enterprise');
      var guest = $(this).data('delete-guest');
      $.ajax({
        url: stm_lms_ajaxurl,
        dataType: 'json',
        context: this,
        data: {
          action: 'stm_lms_delete_from_cart',
          nonce: stm_lms_nonces['stm_lms_delete_from_cart'],
          item_id: item_id,
          group_id: group_id,
          guest: guest
        },
        beforeSend: function beforeSend() {
          $(this).addClass('loading');
        },
        complete: function complete(data) {
          $(this).removeClass('loading');
          $(this).closest('.item_can_hide').slideUp();
          if (guest) {
            $.removeCookie('stm_lms_notauth_cart', {
              path: '/'
            });
          }
          location.reload();
        }
      });
    });
    $('.stm_lms_logout').on('click', function (e) {
      e.preventDefault();
      $.ajax({
        url: stm_lms_ajaxurl,
        dataType: 'json',
        context: this,
        data: {
          action: 'stm_lms_logout',
          nonce: stm_lms_nonces['stm_lms_logout']
        },
        complete: function complete(data) {
          data = data['responseJSON'];
          window.location.href = data;
        }
      });
    });
    pmpro_mmu_checkbox();
    open_membership();
  });
  $(window).on('load', function () {
    pmpro_mmu_checkbox();
    $('.pmpro_level-select input').on('change', function () {
      pmpro_mmu_checkbox();
    });
  });
  function pmpro_mmu_checkbox() {
    var $levels = $('.pmpro_mmpu_level');
    if (!$levels.length) return false;
    $levels.each(function () {
      $level = $(this);
      $level_checkbox = $level.find('.pmpro_level-select');
      if ($level_checkbox.length) {
        if ($level_checkbox.hasClass('pmpro_level-select-selected')) {
          $level.addClass('active');
        } else {
          $level.removeClass('active');
        }
      }
    });
  }
  function open_membership() {
    var hash = window.location.hash;
    var $btn = $('[data-target=".stm-lms-use-subscription"]');
    if (hash === '#membership' && $btn.length) {
      $('.stm_lms_mixed_button').addClass('active');
      $btn.click();
    }
  }
  $(document).on('click', '.stm_lms_account_dropdown .dropdown-menu > li > a', function (e) {
    e.preventDefault();
    e.stopPropagation();
    window.location.href = $(this).attr('href');
  });
  $(document).on('click', '.stm_lms_account_dropdown button', function () {
    $('.caret').toggleClass('rotate');
  });
  $(document).on('click', '.dropdown-backdrop', function () {
    $('.caret').removeClass('rotate');
  });
  $(document).mouseup(function (e) {
    var container = $('.stm_lms_account_dropdown');
    if (container.has(e.target).length === 0) {
      $('.caret').removeClass('rotate');
    }
  });
})(jQuery);
function stm_lms_price_format(price) {
  price = stm_lms_price_format_number(price);
  price = stm_lms_vars['position'] === 'left' ? stm_lms_vars['symbol'] + price : price + stm_lms_vars['symbol'];
  return price;
}
function stm_lms_price_format_number(price) {
  return price.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1' + stm_lms_vars['currency_thousands']);
}
function stm_lms_print_message(data) {
  var message = JSON.stringify(data);
  try {
    lmsEvent.postMessage(message);
  } catch (err) {
    // console.log(err, message);
  }
}
function stmLmsExternalInitProgress() {
  var $ = jQuery;
  var $popup = $('.stm_lms_finish_score_popup');
  if ($popup.length) {
    stmLmsInitProgress();
    setTimeout(function () {
      $popup.addClass('active');
    }, 2000);
  }
}