'use strict';

(function ($) {

  var utils = jupiterxElementorUtils;

  /**
   * Get templates.
   *
   * @param {object} options
   */
  function getTemplates(options) {
    wp.ajax.send('jupiterx_get_elementor_templates', options);
  }

  /**
   * Open Elementor editor on lightbox.
   *
   * @param {object} options
   */
  function openEditor(options) {
    options = $.extend({
      action: 'new',
      type: 'section',
      post: '',
      beforeClose: $.noop
    }, options || {});

    var _options = options,
        action = _options.action,
        type = _options.type,
        post = _options.post,
        beforeClose = _options.beforeClose;

    var open = function open() {
      var $content = this.$instance.find('.featherlight-content');

      if ($content.length) {
        $content.append(getPreloaderHTML());
      }
    };
    var close = function close() {
      var $iframe = this.$instance.find('iframe');
      if (!$iframe.length) {
        return;
      }

      var contentWindow = $iframe[0].contentWindow;
      if (!contentWindow.elementor) {
        return;
      }

      beforeClose(contentWindow);
    };
    var url = '';

    if (action === 'edit' && post) {
      url = utils.editUrl + '&post=' + post;
    } else {
      url = utils.newUrl + '&template_type=' + type;
    }

    $.featherlight({
      variant: 'jupiterx-elementor-editor-lightbox',
      iframe: url,
      beforeOpen: open,
      beforeClose: close
    });
  }

  /**
   * Preloader HTML for Elementor.
   */
  function getPreloaderHTML() {
    return $("\
      <div class='jupiterx-elementor-loading'>\
        <div class='jupiterx-elementor-loader-wrapper'>\
          <div class='jupiterx-elementor-loader'>\
            <div class='jupiterx-elementor-loader-boxes'>\
              <div class='jupiterx-elementor-loader-box'></div>\
              <div class='jupiterx-elementor-loader-box'></div>\
              <div class='jupiterx-elementor-loader-box'></div>\
              <div class='jupiterx-elementor-loader-box'></div>\
            </div>\
          </div>\
          <div class='jupiterx-elementor-loading-title'>\
            Loading\
          </div>\
        </div>\
      </div>\
    ");
  }

  window.jupiterx = window.jupiterx || {};

  // Elementor.
  window.jupiterx.elementor = {
    utils: utils,
    getTemplates: getTemplates,
    openEditor: openEditor
  };
})(jQuery);