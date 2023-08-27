'use strict';

jQuery(document).ready(function () {
  var jxTexts = jupiterx_activation_popup;

  jupiterx_modal({
    title: jxTexts.important_note,
    text: '<span class="jupiterx-onboarding-inner-title"> ' + jxTexts.jupiter_usage_title + ' </span>\n    <span class="jupiterx-onboarding-inner-message"> ' + jxTexts.jupiter_usage_message + ' </span>\n    <span class="jupiterx-onboarding-inner-learn-more"><a href="https://themes.artbees.net/docs/updating-the-theme/upgrading-jupiter-theme-to-version-x" target="_blank" class="jupiterx-icon-question-circle" title="' + jxTexts.learn_more_link_title + '"> ' + jxTexts.learn_more + ' </a></span>',
    type: 'warning',
    showCancelButton: true,
    cancelButtonIcon: 'arrow-left',
    cancelButtonText: jxTexts.take_me_back,
    showConfirmButton: true,
    confirmButtonText: jxTexts.fresh_start,
    confirmButtonIcon: 'rocket',
    showCloseButton: true,
    showLearnmoreButton: false,
    showProgress: false,
    onCancel: function onCancel() {
      if (jxTexts.jupiter_installed == true) {
        jupiterx_modal({
          title: jxTexts.activate_jupiter,
          text: jxTexts.activate_jupiter_message,
          type: 'info',
          showCancelButton: true,
          showConfirmButton: true,
          confirmButtonText: jxTexts.activate_jupiter_btn,
          cancelButtonText: jxTexts.discard,
          showCloseButton: true,
          showLearnmoreButton: false,
          showProgress: false,
          onConfirm: function onConfirm() {
            window.location = jQuery('#jupiter-name + .theme-actions > .button.activate').attr('href') || window.location;
          }
        });
      } else {
        jupiterx_modal({
          title: jxTexts.install_activate_jupiter,
          text: '<img src="' + jxTexts.images_url + 'how-install-jupiter.gif">\n          <span class="jupiterx-onboarding-list"><span class="jupiterx-onboarding-list-num">1</span><span>' + jxTexts.install_activate_jupiter_message_1 + '</span></span>\n          <span class="jupiterx-onboarding-list"><span class="jupiterx-onboarding-list-num">2</span><span>' + jxTexts.install_activate_jupiter_message_2 + '</span></span>',
          type: 'info',
          showCancelButton: false,
          showConfirmButton: true,
          confirmButtonText: jxTexts.done,
          showCloseButton: true,
          showLearnmoreButton: false,
          showProgress: false
        });
      }
    }
  });
});