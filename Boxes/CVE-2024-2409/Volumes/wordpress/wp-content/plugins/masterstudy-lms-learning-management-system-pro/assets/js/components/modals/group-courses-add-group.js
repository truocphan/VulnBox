(function ($) {
  let $body = $('body');
  let emails = [];

  /*Open window for add group*/
  $body.on('click', '.masterstudy-group-courses__create-group', function (e) {
    e.preventDefault();

    const $group_title = $body.find('.masterstudy-group-courses-modal__header-title');
    const $group_title_data = $group_title.attr('data-second-text');

    if ($group_title_data !== undefined) {
      $group_title.html($group_title_data);
      $body.find('.masterstudy-group-courses__name').hide();
    }

    $body.find('.masterstudy-group-courses-modal__header-title-back').css('display', 'flex');

    const $elements = $('.masterstudy-group-courses__start, .masterstudy-group-courses__list, .masterstudy-group-courses__actions');

    if ($elements.length > 0) {
      $elements.hide();
    }

    listEmails();

    $('.masterstudy-group-courses__addition').toggleClass('active');

    const $additionListEmails = $('.masterstudy-group-courses__addition-list_emails');
    if ($additionListEmails.children().length < 2) {
      $('.masterstudy-group-courses__addition-list_title').children().removeClass('warning');
    }
  });

  $body.on('click', '.masterstudy-group-courses-modal__header-title-back', function (e) {
    const $group_title = $body.find('.masterstudy-group-courses-modal__header-title');
    const $group_title_data = $group_title.attr('data-default-text');

    if ($group_title_data !== undefined) {
      $group_title.html($group_title_data);
      $body.find('.masterstudy-group-courses__name').show();
    }

    $body.find('.masterstudy-group-courses-modal__header-title-back').hide();

    const $listElement = $(this).parents().find('.masterstudy-group-courses__list');
    const $groupCoursesStart = $('.masterstudy-group-courses__start');
    const $groupCoursesActions = $body.find('.masterstudy-group-courses__actions');

    if ($(this).hasClass('has-group')) {
      $groupCoursesStart.hide();
      $listElement.show();
      $groupCoursesActions.show();
    } else {
      $groupCoursesStart.show();
      $groupCoursesActions.hide();
      $listElement.hide();
    }

    $body.find('.masterstudy-group-courses__error > div').hide();
    $body.find('.masterstudy-group-courses__addition-list input').removeClass('invalid-email');
    $body.find('.masterstudy-group-courses__addition').removeClass('active');
  });
  /*End open window for add group*/

  /*Add group email*/
  let $group_email = $('#masterstudy-group-courses__group-email');

  $body.on('click', '.masterstudy-group-courses__addition-list_add_email', function () {
    const $group_email = $('#masterstudy-group-courses__group-email');
    const email = $group_email.val();

    if (!email.length) {
      $group_email.addClass('invalid-email');
    }

    if (validEmail(email)) {
      $group_email.removeClass('invalid-email');
    } else {
      $body.find('.masterstudy-group-courses__error-user-email').fadeIn();
      $group_email.addClass('invalid-email');
    }

    if (!validEmail(email) || emails.includes(email)) {
      return true;
    }

    const $additionList = $body.find('.masterstudy-group-courses__addition-list');
    const $additionListEmails = $additionList.find('.masterstudy-group-courses__addition-list_emails');
    const maxGroup = $additionList.data('max-group') - 1 || 4;

    if ($additionListEmails.children().length > maxGroup) {
      $body.find('.masterstudy-group-courses__error-limit-email').fadeIn();
      return true;
    } else {
      $group_email.removeClass('invalid-email');
    }

    emails.push(email);
    listEmails();
    $group_email.val('');
  });

  $body.on('keyup change', '#masterstudy-group-courses__group-email', function (e) {
    const $this = $group_email = $(this);
    const email = $this.val();

    if (e.key === 'Enter') {
      const $additionListAddEmail = $('.masterstudy-group-courses__addition-list_add_email');
      $additionListAddEmail.trigger('click');
    }

    const $group_error = $body.find('.masterstudy-group-courses__error');
    $group_error.find('.masterstudy-group-courses__error-user-email').hide();
  });

  $body.on('click', '.masterstudy-group-courses__addition-list_emails-item_close', function () {
    const $emailItem = $(this).parent();
    const $emailSpan = $emailItem.find('span');

    const email = $emailSpan.text();
    const index = emails.indexOf(email);

    emails.splice(index, 1);
    $emailItem.remove();

    $body.find('.masterstudy-group-courses__error-limit-email').hide();
  });
  /*End add group email*/

  /*Add group*/
  $body.on('click', '.masterstudy-group-courses__add-group', function (e) {
    e.preventDefault();

    const $group_name = $body.find('#masterstudy-group-courses__group-name');
    const $group_name_val = $group_name.val();
    $body.find('.masterstudy-group-courses-modal__header-title-back').addClass('has-group');

    if (!$group_name_val.length) {
      $group_name.addClass('invalid-email');
      $body.find('.masterstudy-group-courses__error div').hide();
      $body.find('.masterstudy-group-courses__error-group-name').fadeIn();
      return false;
    } else {
      $group_name.removeClass('invalid-email');
    }

    const $group_email = $body.find('#masterstudy-group-courses__group-email');
    const email = $group_email.val();

    if (emails.length !== 0) {
      const $additionListAddEmail = $('.masterstudy-group-courses-modal__header-title-back');
      $additionListAddEmail.trigger('click');

      let data = {
        title: $group_name.val(),
        emails: emails,
      };

      $.ajax({
        url: stm_lms_ajaxurl + '?action=stm_lms_add_enterprise_group&nonce=' + stm_lms_nonces['stm_lms_add_enterprise_group'],
        type: 'POST',
        data: JSON.stringify(data),
        dataType: 'json',
        contentType: "application/json",
        beforeSend: function () {
          $body.find('.masterstudy-group-courses__start').hide();
          $body.find('.masterstudy-group-courses__list').show();
          $body.find('.masterstudy-group-courses__list-loading').show();
          $body.find('.masterstudy-group-courses__list-loading .masterstudy-loader').show();
          $body.find('.masterstudy-group-courses__actions').hide();
          $body.find('.masterstudy-group-courses__error > div').hide();
          $body.find('.masterstudy-group-courses__addition-list input').removeClass('invalid-email');
          $body.find('.masterstudy-group-courses__error div').hide();
        },
        success: function (data) {
          $('.masterstudy-group-courses__addition').removeClass('active');

          let $list = $body.find('.masterstudy-group-courses__list');

          const totalEmails = data.group.emails.length;
          const $listItem = $body.find('.masterstudy-group-courses__addition-list_emails');
          const dataMember = totalEmails > 1 ? $listItem.attr('data-members') : $listItem.attr('data-member');
          const member = totalEmails + dataMember;

          $list.append('<div class="masterstudy-group-courses__list-item" data-masterstudy-group-courses-group-id="' + data.group.post_id + '"><div class="masterstudy-group-courses__list-item_title"><div class="masterstudy-group-courses__list-item_checkbox"></div>' + data.group.title + '</div><div class="masterstudy-group-courses__list-item_members">' + member + '</div></div>');

          $body.find('.masterstudy-group-courses__list-loading').hide();
          $body.find('.masterstudy-group-courses__list-loading .masterstudy-loader').hide();
          $body.find('.masterstudy-group-courses__actions').show();
          $body.find('#masterstudy-group-courses__group-name').val('');
          $body.find('#masterstudy-group-courses__group-email').val('');
          emails = [];
        }
      });
    } else {
      $group_email.addClass('invalid-email');
      $body.find('.masterstudy-group-courses__error div').hide();
      $body.find('.masterstudy-group-courses__error-user-email').fadeIn();
    }
  });

  $body.on('keyup change', '#masterstudy-group-courses__group-name', function (e) {
    $(this).removeClass('invalid-email');
    $body.find('.masterstudy-group-courses__error-group-name').hide();
  });
  /*End add group*/

  function listEmails() {
    let $group_emails = $('.masterstudy-group-courses__addition-list_emails');
    $group_emails.html('');
    emails.forEach(function (value, index) {
      $group_emails.append("<div class='masterstudy-group-courses__addition-list_emails-item'><span data-index='" + index + "'>" + value + "</span><span class='stmlms-close masterstudy-group-courses__addition-list_emails-item_close'></span></div>");
    });
  }

  function validEmail(email) {
    let result = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return result.test(email);
  }
})(jQuery);
