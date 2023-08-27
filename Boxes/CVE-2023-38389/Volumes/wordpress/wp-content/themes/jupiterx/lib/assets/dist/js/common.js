'use strict';

(function ($, wp) {

  window.jupiterx || {};

  /**
   * Modal for upgrading theme.
   *
   * Initialize a new instance of modal where users can activate and install Jupiter X Pro plugin.
   *
   * @since 1.3.0
   */
  function upgrade(url) {
    var $template = $(wp.template('jupiterx-upgrade')({ url: url })),
        $steps = $template.find('.jupiterx-upgrade-step'),
        $apiKey = $template.find('.jupiterx-upgrade-api-key'),
        $activateBtn = $template.find('.jupiterx-upgrade-activate'),
        maxStep = $steps.length,
        step = 0;

    function next() {
      $($steps[step]).toggleClass('active done');

      step++;

      if (step >= maxStep) {
        done();
        return;
      }

      $($steps[step]).addClass('active');
    }

    function activate() {
      $activateBtn.attr('disabled', 'disabled').append('<span class="jupiterx-spin jupiterx-icon-circle-notch"></span>');

      $.ajax({
        type: 'POST',
        url: wp.ajax.settings.url,
        data: {
          action: 'jupiterx_api',
          nonce: jupiterxUtils.nonce,
          method: 'activate',
          api_key: $apiKey.val()
        },
        success: function success(res) {
          var data = res.data || {};

          if (data.status) {
            $apiKey.removeClass('invalid').attr('disabled', 'disabled');

            $activateBtn.attr('disabled', 'disabled').find('.jupiterx-icon-circle-notch').remove();

            next();
            install();
          } else {
            $apiKey.addClass('invalid');

            $activateBtn.removeAttr('disabled').find('.jupiterx-icon-circle-notch').remove();
          }
        }
      });
    }

    function install() {
      var size = 40,
          progress = void 0;

      $template.find('.jupiterx-upgrade-install-progress').prepend(wp.template('jupiterx-progress-bar')());

      progress = setInterval(function () {
        if (size > 100) {
          clearTimeout(progress);
          return;
        }

        $template.find('.progress-bar').css('width', size + '%');

        size += 20;
      }, 3000);

      $.ajax({
        type: 'POST',
        url: wp.ajax.settings.url,
        data: {
          action: 'jupiterx_api',
          nonce: jupiterxUtils.nonce,
          method: 'install_plugins',
          plugins: ['jupiterx-pro']
        },
        success: function success(res) {
          var data = res.data || {};

          if (data.status) {
            next();
          }
        }
      });
    }

    function done() {
      var $footerHTML = $('\
        <div class="jupiterx-upgrade-footer">\
          <span class="jupiterx-upgrade-learn-more">\
            <i class="jupiterx-icon-external-link-alt"></i>\
            <a target="_blank" href="https://themes.artbees.net/docs/jupiter-x-pro">' + jupiterx_admin_textdomain.learn_pro_features + '</a>\
          </span>\
          <button class="btn btn-primary">' + jupiterx_admin_textdomain.done + '</button>\
        </div>\
      ');

      $footerHTML.find('button').click(function (event) {
        event.preventDefault();
        window.location = window.location.href.split('#')[0];
      });

      jupiterx_modal({
        modalCustomClass: 'jupiterx-modal-upgrade jupiterx-modal-upgrade-done',
        title: jupiterx_admin_textdomain.pro_upgrade_title,
        text: jupiterx_admin_textdomain.pro_upgrade_text,
        footerHTML: $footerHTML,
        showCloseButton: false,
        showCancelButton: false,
        closeOnOutsideClick: false,
        type: false,
        icon: 'jupiterx-icon-pro'
      });
    }

    $template.on('click', '.active .jupiterx-upgrade-buy-pro', function () {
      next();
    });

    $template.on('click', '.active .jupiterx-upgrade-activate', function (event) {
      event.preventDefault();
      activate();
    });

    jupiterx_modal({
      modalCustomClass: 'jupiterx-modal-upgrade',
      title: 'Upgrade Jupiter X',
      text: $template,
      showCancelButton: false,
      showConfirmButton: false,
      closeOnOutsideClick: false,
      type: false,
      icon: 'jupiterx-icon-pro'
    });
  }

  /**
   * Modal for activating api key.
   *
   * Initialize a new instance of modal where users can activate API.
   *
   * @since 1.3.0
   */
  function activateInit() {
    var $template = $(wp.template('jupiterx-activate')()),
        $apiKey = $template.find('.jupiterx-upgrade-api-key'),
        $activateBtn = $template.find('.jupiterx-upgrade-activate');

    var $footerHTML = $('\
      <div class="jupiterx-upgrade-footer">\
        <span class="jupiterx-upgrade-learn-more">\
          <i class="jupiterx-icon-external-link-alt"></i>\
          <a target="_blank" href="https://themes.artbees.net/docs/jupiter-x-pro">' + jupiterx_admin_textdomain.learn_pro_features + '</a>\
        </span>\
        <button class="btn btn-primary">Done</button>\
      </div>\
    ');

    $footerHTML.find('button').click(function (event) {
      event.preventDefault();
      window.location = window.location.href.split('#')[0];
    });

    function activate() {
      $activateBtn.attr('disabled', 'disabled').append('<span class="jupiterx-spin jupiterx-icon-circle-notch"></span>');

      $.ajax({
        type: 'POST',
        url: wp.ajax.settings.url,
        data: {
          action: 'jupiterx_api',
          nonce: jupiterxUtils.nonce,
          method: 'activate',
          api_key: $apiKey.val()
        },
        success: function success(res) {
          var data = res.data || {};

          function done() {
            jupiterx_modal({
              modalCustomClass: 'jupiterx-modal-upgrade jupiterx-modal-upgrade-done',
              title: jupiterx_admin_textdomain.activated_title,
              text: jupiterx_admin_textdomain.activated_text,
              footerHTML: $footerHTML,
              showCloseButton: false,
              showCancelButton: false,
              closeOnOutsideClick: false,
              type: false,
              icon: 'jupiterx-icon-check'
            });
          }

          function error() {
            jupiterx_modal({
              modalCustomClass: 'jupiterx-modal-upgrade jupiterx-modal-upgrade-done',
              title: jupiterx_admin_textdomain.register_fail_title,
              text: jupiterx_admin_textdomain.register_fail_text,
              footerHTML: $footerHTML,
              showCloseButton: false,
              showCancelButton: false,
              closeOnOutsideClick: false,
              type: false,
              icon: 'jupiterx-icon-times'
            });
          }

          if (data.status) {
            $apiKey.removeClass('invalid').attr('disabled', 'disabled');

            $activateBtn.attr('disabled', 'disabled').find('.jupiterx-icon-circle-notch').remove();

            $.ajax({
              type: 'POST',
              url: wp.ajax.settings.url,
              data: {
                action: 'jupiterx_api',
                nonce: jupiterxUtils.nonce,
                method: 'install_plugins',
                plugins: ['jupiterx-pro']
              },
              success: function success(res) {
                var data = res.data || {};

                if (data.status) {
                  done();
                } else {
                  error();
                }
              }
            });
          } else {
            $apiKey.addClass('invalid');

            $activateBtn.find('.jupiterx-icon-circle-notch').remove();

            error();
          }
        }
      });
    }

    $template.on('click', '.jupiterx-upgrade-activate', function (event) {
      event.preventDefault();
      activate();
    });

    jupiterx_modal({
      modalCustomClass: 'jupiterx-modal-upgrade',
      title: 'Activate Jupiter X',
      text: $template,
      showCancelButton: false,
      showConfirmButton: false,
      closeOnOutsideClick: false,
      type: false,
      icon: 'jupiterx-icon-key'
    });
  }

  /**
   * Modal for uninstalling Jupiter X Pro plugin.
   *
   * @since 1.6.0
   */
  function uninstallPro() {
    function uninstallNow() {
      var $template = $('<div></div>'),
          slug = 'jupiterx-pro',
          basename = 'jupiterx-pro/jupiterx-pro.php';

      $template.prepend(wp.template('jupiterx-progress-bar')()).find('.progress-bar').css('width', '100%');

      $.ajax({
        type: 'POST',
        url: wp.ajax.settings.url,
        data: {
          action: 'jupiterx_api',
          nonce: jupiterxUtils.nonce,
          method: 'deactivate_plugins',
          plugins: [basename]
        },
        success: function success() {
          wp.updates.ajax('delete-plugin', {
            plugin: basename,
            slug: slug,
            success: function success() {
              var $successFooter = $('\
                  <div class="jupiterx-upgrade-footer">\
                    <span class="jupiterx-upgrade-learn-more"></span>\
                    <button class="btn btn-primary">' + jupiterx_admin_textdomain.done + '</button>\
                  </div>\
                ');

              $successFooter.find('button').click(function (event) {
                event.preventDefault();
                window.location.reload(true);
              });

              jupiterx_modal({
                modalCustomClass: 'jupiterx-modal-upgrade',
                title: jupiterx_admin_textdomain.plugin_removed_title,
                text: jupiterx_admin_textdomain.plugin_removed_text,
                footerHTML: $successFooter,
                showCloseButton: false,
                showCancelButton: false,
                closeOnOutsideClick: false,
                type: 'success'
              });
            }
          });
        }
      });

      jupiterx_modal({
        modalCustomClass: 'jupiterx-modal-upgrade',
        title: jupiterx_admin_textdomain.uninstall_pro_title,
        text: $template,
        showCancelButton: false,
        showConfirmButton: false,
        closeOnOutsideClick: false,
        type: false
      });
    }

    var $uninstallFooter = $('<button class="btn btn-danger">Delete Jupiter X Pro Plugin</button>');

    $uninstallFooter.on('click', function (event) {
      event.preventDefault();
      uninstallNow();
    });

    jupiterx_modal({
      modalCustomClass: 'jupiterx-modal-upgrade jupiterx-modal-uninstall-pro',
      title: jupiterx_admin_textdomain.important_notice_title,
      text: jupiterx_admin_textdomain.important_notice_text,
      showCancelButton: false,
      showConfirmButton: false,
      closeOnOutsideClick: false,
      type: false,
      footerHTML: $uninstallFooter
    });
  }

  window.jupiterx = jQuery.extend({}, window.jupiterx, {
    upgrade: upgrade,
    activateInit: activateInit,
    uninstallPro: uninstallPro
  });

  $(document).on('click', '.jupiterx-upgrade-modal-trigger, #tgmpa-plugins a[href*="tgmpa-pro"]', function (event) {
    event.preventDefault();
    if (typeof jupiterxPremium !== 'undefined') {
      Object.assign(document.createElement('a'), { target: '_blank', href: jupiterXControlPanelURL }).click();
    } else {
      jupiterx.upgrade(event.target.getAttribute('data-upgrade-link'));
    }
  });

  $(document).on('mousedown', '.jupiterx-upgrade-modal-trigger, #tgmpa-plugins a[href*="tgmpa-pro"]', function () {
    $(this).attr('href', jupiterXControlPanelURL);
  });

  $(document).on('click', '.jupiterx-update-plugins-notice-button', function (event) {
    event.preventDefault();

    $(event.target).addClass('updating-message').text('Updating Plugins');
  });

  /**
   * Save custom widget area.
   */
  $(document).on('click', '#js__jupiterx-add-custom-widget-area', function (event) {
    event.preventDefault();

    var template = '<div class="form-group mb-3"> \
        <label><strong>Sidebar Name</strong></label> \
        <input class="jupiterx-form-control" name="jupiterx_sidebar_name" type="text" required /> \
      </div>';

    jupiterx_modal({
      modalCustomClass: 'jupiterx-modal-add-custom-widget-area',
      title: jupiterx_admin_textdomain.add_custom_sidebar_modal_title,
      text: template,
      confirmButtonText: jupiterx_admin_textdomain.add_custom_sidebar,
      closeOnOutsideClick: false,
      type: false
    });

    jupiterx_modal.disableConfirmBtn();

    $('.jupiterx-modal-add-custom-widget-area .js__modal-btn-confirm').off('click');

    $(document).on('keyup', '.jupiterx-modal-add-custom-widget-area input', function () {
      var $name = $('.jupiterx-modal-add-custom-widget-area input[name="jupiterx_sidebar_name"]');

      if (!$name || !$name.val().trim()) {
        jupiterx_modal.disableConfirmBtn();

        return;
      }

      jupiterx_modal.enableConfirmBtn();
    });

    $(document).on('click', '.jupiterx-modal-add-custom-widget-area .js__modal-btn-confirm', function (event) {
      event.preventDefault();

      jupiterx_modal.disableConfirmBtn();

      var $name = $('.jupiterx-modal-add-custom-widget-area input[name="jupiterx_sidebar_name"]');

      wp.ajax.post('jupiterx_add_custom_widget_area', {
        name: $name.val(),
        _ajax_nonce: $('#js__jupiterx-add-custom-widget-area').data('nonce')
      }).done(function () {
        window.location.reload();
      });
    });
  });

  /**
   * Delete custom widget area.
   */
  $(document).on('click', '.js__jupiterx-delete-custom-widget-area', function (event) {
    event.preventDefault();

    $(this).text(jupiterx_admin_textdomain.deleting + '...').attr('disabled', 'disbaled');

    wp.ajax.post('jupiterx_delete_custom_widget_area', {
      id: parseInt($(this).data('id')) - 1,
      nonce: jupiterxUtils.nonce
    }).done(function () {
      window.location.reload();
    });
  });

  /**
   * Insert delete button for custom widget area.
   */
  $.each($('[id^=jupiterx_custom_sidebar'), function () {
    var id = parseInt($(this).attr('id').replace('jupiterx_custom_sidebar_', ''));

    var button = '<div class="jupiterx-custom-widget-area-footer"> \
        <button data-id="' + id + '" class="button button-primary js__jupiterx-delete-custom-widget-area"> ' + jupiterx_admin_textdomain.delete_custom_sidebar + ' </button> \
      </div>';

    $(this).closest('.widgets-holder-wrap').append(button);
  });

  /**
   * Navigate feedback notification bar notice.
   */
  $('.jupiterx-feedback-notification-bar-notice-step button').on('click', function () {
    var $step = $(this).closest('.jupiterx-feedback-notification-bar-notice-step');
    var step = $(this).data('step');

    if (!step) {
      $('.jupiterx-feedback-notification-bar-notice').find('.notice-dismiss').trigger('click');

      return;
    }

    $step.addClass('hidden');

    $step.siblings('[data-step="' + step + '"]').removeClass('hidden');
  });

  /**
   * Dismiss feedback notification bar notice on close button click.
   */
  $(document).on('click', '.jupiterx-feedback-notification-bar-notice .notice-dismiss', function (event) {
    var nonce = $(this).closest('.jupiterx-feedback-notification-bar-notice').data('nonce');

    wp.ajax.post('jupiterx_dismiss_feedback_notification_bar_notice', {
      _ajax_nonce: nonce
    });
  });

  /**
   * Dismiss survey notification bar notice on close button click.
   */
  $(document).on('click', '.jupiterx-survey-notification-bar-notice .notice-dismiss', function (event) {
    var nonce = $(this).closest('.jupiterx-survey-notification-bar-notice').data('nonce');

    wp.ajax.post('jupiterx_dismiss_survey_notification_bar_notice', {
      _ajax_nonce: nonce
    });
  });

  /**
   * Dismiss survey notification bar notice on cta button click.
   */
  $(document).on('click', '.jupiterx-survey-notification-bar-notice-cta', function () {
    $('.jupiterx-survey-notification-bar-notice .notice-dismiss').trigger('click');
  });

  /**
   * Handle install sellkit notice.
   *
   * @since 2.0.6
   */
  $(document).on('click', '.jupiterx-notice-install-sellkit', function (event) {
    if ('#' !== $(this).attr('href')) {
      return;
    }

    var nonce = $(this).closest('.sellkit-notice-in-jupiterx').data('nonce'),
        button = event.currentTarget;

    event.preventDefault();

    $.ajax({
      type: 'POST',
      url: wp.ajax.settings.url,
      data: {
        action: 'jupiterx_install_sellkit_in_notice',
        nonce: nonce
      },
      beforeSend: function beforeSend() {
        button.innerHTML = 'Installing....';
      },
      success: function success() {
        button.innerHTML = 'Done';

        // Reload on success.
        window.location.reload();
      }
    });
  });

  /**
   * Handle dismiss sellkit notice.
   *
   * @since 2.0.6
   */
  $(document).on('click', '.jupiterx-dismiss-sellkit-notice', function (event) {
    var nonce = $(this).closest('.sellkit-notice-in-jupiterx').data('nonce');

    event.preventDefault();
    $(this).closest('.sellkit-notice-in-jupiterx').remove();

    wp.ajax.post('jupiterx_dismiss_sellkit_notice', {
      _ajax_nonce: nonce
    });
  });

  /**
   * Handle attache media.
   *
   * @since 2.5.0
   */
  $(document).on('click', '.jupiterx-attach-mp4', function (event) {
    event.preventDefault();

    var $button = $(this),
        mediaId = $button.data('media-id');

    wp.media.frames.original = wp.media.frame;
    wp.media.frames.original.close();
    wp.media.frames.original.state().deactivate();

    if (wp.media.frames.jupitrx_media) {
      wp.media.frames.jupitrx_media.media_thumbnail_id = mediaId;
      wp.media.frames.jupitrx_media.open();
      return;
    }

    // Redirect to WP media upload.
    wp.media.frames.jupitrx_media = wp.media({
      title: jupiterx_admin_textdomain.attachment_mp4_title,
      button: {
        text: jupiterx_admin_textdomain.attachment_mp4_text
      },
      library: {
        type: 'video/mp4'
      },
      multiple: false
    });

    wp.media.frames.jupitrx_media.on('select', function () {
      wp.media.frames.original.state().activate();
      wp.media.frames.original.open();
      wp.media.frame = wp.media.frames.original;

      if (wp.media.frames.original.state().get('selection')) {
        wp.media.frames.original.state().get('selection').add(wp.media.attachment(wp.media.frames.jupitrx_media.media_thumbnail_id));
      }

      var selected = wp.media.frames.jupitrx_media.state().get('selection'),
          id = wp.media.frames.jupitrx_media.media_thumbnail_id,
          mediaURL = $('#attachments-' + id + '-jupiterx_media_url');

      selected.map(function (current) {
        current = current.toJSON();
        mediaURL.val(current.url).change();

        return false;
      });
    });

    wp.media.frames.jupitrx_media.media_thumbnail_id = mediaId;
    wp.media.frames.jupitrx_media.open();

    return false;
  });
})(jQuery, wp);