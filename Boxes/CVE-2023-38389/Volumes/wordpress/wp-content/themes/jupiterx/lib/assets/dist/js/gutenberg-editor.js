'use strict';

(function ($) {
  var fullWidthBlockStyles = '.wp-block { max-width: 100%; }';
  var mainWidthBlockStyles = '<style id="jupiterx-gutenberg-main-width-styles"> .wp-block { max-width:' + jupiterx_gutenberg_width["main"] + '; } </style>';
  var pageTemplate = $('.editor-page-attributes__template select').val();

  $(document).ready(function () {
    $('head').append(mainWidthBlockStyles);
    $('head').append('<style id="jupiterx-gutenberg-dynamic-width-style"></style>');

    if (typeof jupiterxUtils.helpLinks === 'undefined' || jupiterxUtils.helpLinks) {
      setTimeout(addHelpLink, 2000);
      $(window).on('resize', addHelpLink);
      $("body").on('DOMSubtreeModified', ".components-panel", addHelpLink);
    }
  });

  $(document).on('change', '.editor-page-attributes__template select', function () {
    pageTemplate = $(this).val();
    if ('full-width.php' === pageTemplate) {
      $('#jupiterx-gutenberg-dynamic-width-style').html(fullWidthBlockStyles);
    } else {
      $('#jupiterx-gutenberg-dynamic-width-style').html('.wp-block { max-width:' + (jupiterx_gutenberg_width[$('#acf-field_jupiterx_post_main_layout').val()] || "") + '; }');
    }
  });

  $(document).on('change', '#acf-field_jupiterx_post_main_layout', function () {
    if ('full-width.php' === pageTemplate) {
      $('#jupiterx-gutenberg-dynamic-width-style').html(fullWidthBlockStyles);
    } else {
      $('#jupiterx-gutenberg-dynamic-width-style').html('.wp-block { max-width:' + jupiterx_gutenberg_width[$(this).val()] + '; }');
    }
  });

  function addHelpLink() {
    var pageTemplateMetaWrapper = $('.editor-page-attributes__template');
    var addedHelpLink = $('.jupiterx-template-help-link');
    if (pageTemplateMetaWrapper.length && addedHelpLink.length < 1) {
      pageTemplateMetaWrapper.prepend('<a href="https://themes.artbees.net/docs/setting-page-template/" target="_blank" class="jupiterx-template-help-link">Help</a>');
    }
  }
})(jQuery);