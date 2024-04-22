"use strict";

(function ($) {
  var emails = [];
  var $body = $('body');
  $(document).ready(function () {
    var $price_btn = $('.buy-enterprise');
    var group_ids = [];
    $body.on('click', '.stm_lms_select_group', function () {
      var $this = $(this);
      $price_btn = $('.buy-enterprise');
      $this.toggleClass('active');
      calculatePrice();
    });
    function calculatePrice() {
      group_ids = [];
      var total = 0;
      $('.stm_lms_select_group').each(function () {
        var $this = $(this);
        if ($this.hasClass('active')) {
          total++;
          group_ids.push($this.attr('data-group-id'));
        }
      });
      var price = $price_btn.data('enterprise-price');
      $price_btn.find('span').html(stm_lms_price_format(price * total));
      disableBuy();
    }
    $body.on('click', '.buy-enterprise', function (e) {
      e.preventDefault();
      var $this = $(this);
      if (!group_ids.length) return false;
      $.ajax({
        url: stm_lms_ajaxurl,
        dataType: 'json',
        context: this,
        data: {
          action: 'stm_lms_add_to_cart_enterprise',
          groups: group_ids,
          course_id: $this.data('course-id'),
          nonce: stm_lms_nonces['stm_lms_add_to_cart_enterprise']
        },
        beforeSend: function beforeSend() {
          $this.addClass('loading');
        },
        complete: function complete(data) {
          data = data['responseJSON'];
          $this.removeClass('loading');
          if (data.redirect) {
            window.location.replace(data.cart_url);
          } else {
            $this.html(data.text).attr('href', data.cart_url).removeClass('buy-enterprise');
          }
        }
      });
    });
    function disableBuy() {
      var l = group_ids.length;
      if (l) {
        $price_btn.removeClass('disabled');
      } else {
        $price_btn.addClass('disabled');
      }
    }

    /*Create GRoup*/
    var $group_email = $('#group_email');
    $body.on('click', '.create_group', function (e) {
      e.preventDefault();
      $('.stm_lms_popup_create_group').toggleClass('active');
      if ($('.stm_lms_popup_create_group__inner').find('.group-emails').children().length < 2) {
        $('.stm_lms_popup_create_group__inner').find('.heading_font').children().removeClass('warning');
      }
    });
    $body.on('keydown change', '#group_name', function (e) {
      showButton();
    });
    $body.on('keyup change', '#group_email', function (e) {
      var $this = $group_email = $(this);
      var email = $this.val();
      if (validEmail(email)) $this.removeClass('invalid').addClass('valid');
      if (!validEmail(email)) $this.removeClass('valid').addClass('invalid');
      if (!email.length) $this.removeClass('invalid valid');
    });
    $body.on('click', '.add_email', function () {
      var email = $group_email.val();
      var maxGroup = $('.stm_lms_popup_create_group__inner').data('max-group') - 1;
      maxGroup = maxGroup > 0 ? maxGroup : 4;
      if (!validEmail(email) || emails.includes(email)) return true;
      if ($(this).parents('.stm_lms_popup_create_group__inner').find('.group-emails').children().length > maxGroup) {
        $(this).parents('.stm_lms_popup_create_group__inner').find('.heading_font').children().addClass('warning');
        return true;
      } else {
        $(this).parents('.stm_lms_popup_create_group__inner').find('.heading_font').children().removeClass('warning');
      }
      emails.push(email);
      $group_email.val('').removeClass('invalid valid');
      listEmails();
      showButton();
    });
    $body.on('click', '.lnricons-cross', function () {
      var email = $(this).parent().find('span').text();
      var index = emails.indexOf(email);
      emails.splice(index, 1);
      $(this).parent().remove();
      if (emails.length < 2) {
        $('.stm_lms_popup_create_group__inner').find('.heading_font').children().removeClass('warning');
      }
      if (emails.length < 1) {
        $('.btn-add-group').removeClass('activex');
      }
    });
    $body.on('click', '.btn-add-group', function (e) {
      e.preventDefault();
      if (!$(this).hasClass('activex')) return false;
      var $group_name = $body.find('#group_name');
      var $error = $body.find('.stm_lms_group_new_error');
      var $createWrapper = $body.find('.stm_lms_popup_create_group');
      var data = {
        title: $group_name.val(),
        emails: emails
      };
      $.ajax({
        url: stm_lms_ajaxurl + '?action=stm_lms_add_enterprise_group&nonce=' + stm_lms_nonces['stm_lms_add_enterprise_group'],
        type: 'POST',
        data: JSON.stringify(data),
        dataType: 'json',
        contentType: "application/json",
        beforeSend: function beforeSend() {
          $error.hide();
          $createWrapper.addClass('loading');
        },
        success: function success(data) {
          $createWrapper.removeClass('loading');
          if (data.message) $error.html(data.message).show();
          if (data.status === 'error') return false;
          $('.stm_lms_popup_create_group').removeClass('active');

          /*Add Group*/
          var $list = $body.find('.stm_lms_select_group__list');
          $list.append('<div class="stm_lms_select_group" data-group-id="' + data.group.post_id + '"><span>' + data.group.title + '</span></div>');
          resetForm();
          $body.find('.actions.no-groups').removeClass('no-groups');
          $body.find('.no-groups-message').remove();
        }
      });
    });
  });
  function resetForm() {
    emails = [];
    $body.find('#group_name').val('');
    listEmails();
    showButton();
  }
  function listEmails() {
    var $group_emails = $('.group-emails');
    $group_emails.html('');
    emails.forEach(function (value, index) {
      $group_emails.append("<div class='group-emails-container'><span data-index='" + index + "'>" + value + "</span><i class='lnricons-cross'></i></div>");
    });
  }
  function showButton() {
    var $btn = $body.find('.btn-add-group');
    var $group_name = $body.find('#group_name');
    if ($group_name.val().length && emails.length) {
      $btn.addClass('activex');
    } else {
      $btn.removeClass('activex');
    }
  }
  function validEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }
})(jQuery);