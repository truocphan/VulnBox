'use strict';

(function ($) {

  var $nav = $('.jupiterx-nav'),
      $content = $('.jupiterx-content'),
      $window = $(window),
      pages = {},
      ajaxSend = void 0,
      moveNextPage = void 0,
      initEvents = void 0;

  /**
   * AJAX call wrapper.
   */
  ajaxSend = function ajaxSend(action, options) {
    if (_.isObject(action)) {
      options = _.extend(action, {});
      action = options.data.action;
    }

    options = _.defaults(options || {}, {
      type: 'POST',
      url: _wpUtilSettings.ajax.url
    });

    options.data = _.extend(options.data || {}, {
      action: 'jupiterx_setup_wizard_' + action
    });

    return $.ajax(options);
  };

  /**
   * Move next page via AJAX call.
   */
  moveNextPage = function moveNextPage() {
    ajaxSend({
      data: {
        'action': 'next_page'
      },
      success: function success(res) {
        var data = res.data;

        $content.hide().attr('class', 'jupiterx-content').addClass('jupiterx-' + data.page).html(data.html).fadeIn(500);

        $nav.trigger('next.owl.carousel');

        // Trigger events.
        $window.trigger('content-loaded', data.page);
      }
    });
  };

  /**
   * Common events for each pages.
   */
  initEvents = function initEvents() {
    $content.find('.jupiterx-next').on('click', function (event) {
      event.preventDefault();
      $(this).button('loading');
      moveNextPage();
    });
  };

  // Update button state.
  $.fn.button = function (state, option) {
    this.each(function () {
      var $this = $(this);

      switch (state) {
        case 'loading':
          $this.attr('disabled', 'disabled');

          // Toggle to remove icon.
          if (!option) {
            $this.append('<i class="fa fa-circle-notch fa-spin"></i>');
          }
          break;

        case 'default':
          $this.removeAttr('disabled').find('.fa').remove();
          break;
      }
    });
  };

  // Prepend an alert box to the element.
  $.fn.alert = function (options) {
    this.each(function () {
      var $node = $(this),
          template = wp.template('jupiterx-alert'),
          $alert = $(template(options)),
          offset = 30;

      $node.find('.alert').remove();

      $node.prepend($alert);

      $window.scrollTop($alert.position().top - offset);
    });

    return this;
  };

  // Add UI click prevention.
  $.fn.blockUi = function () {
    this.each(function () {
      $(this).addClass('jupiterx-block-ui');
    });

    return this;
  };

  // Remove UI click prevention.
  $.fn.unblockUi = function () {
    this.each(function () {
      $(this).removeClass('jupiterx-block-ui');
    });

    return this;
  };

  $.fn.overlaySpinner = function () {
    this.each(function () {
      var $this = $(this);
      $this.addClass('jupiterx-overlay-spinner').append(wp.template('jupiterx-spinner')());
    });

    return this;
  };

  $.fn.removeOverlaySpinner = function () {
    this.each(function () {
      var $this = $(this);
      $this.removeClass('jupiterx-overlay-spinner').find('.jupiterx-spinner-container').remove();
    });

    return this;
  };

  /**
   * Install API activation page.
   */
  pages['api-activation'] = {
    /**
     * Initialize events.
     */
    init: function init() {
      var $form = $content.find('.jupiterx-form'),
          $button = $form.find('button.jupiterx-activate'),
          $input = $form.find('input[type=text]');

      $button.on('click', function (event) {
        event.preventDefault();

        var req = ajaxSend({
          data: {
            'action': 'activate_api',
            'api_key': $input.val()
          },
          beforeSend: function beforeSend() {
            $button.button('loading');
          }
        });

        req.success(function (res) {
          var data = res.data;

          if (data.status) {
            moveNextPage();
          } else {
            $button.button('default');

            $form.alert({
              message: data.message,
              type: 'danger'
            });
          }
        });
      });
    }

    /**
     * Install plugins page.
     */
  };pages['plugins'] = {
    /**
     * Initialize events.
     */
    init: function init() {
      var $form = $content.find('.jupiterx-form'),
          $plugins = $form.find('.jupiterx-plugins-list'),
          $button = $form.find('button.btn');

      $button.on('click', function (event) {
        event.preventDefault();

        var $checkbox = $form.find('input[type=checkbox]:checked'),
            plugins = [],
            req = void 0;

        $checkbox.each(function () {
          plugins.push($(this).val());
        });

        req = ajaxSend({
          data: {
            'action': 'install_plugins',
            'plugins': plugins
          },
          beforeSend: function beforeSend() {
            $button.button('loading');
            $plugins.blockUi();
          }
        });

        req.success(function (res) {

          if (res.hasOwnProperty('install')) {
            $.ajax({
              type: "POST",
              url: res['url'],
              data: res['install'],
              success: function success(res) {

                req = ajaxSend({
                  data: {
                    'action': 'activate_plugins',
                    'plugins': plugins
                  }
                });

                req.success(function (res) {
                  if (res.hasOwnProperty('activate')) {
                    $.ajax({
                      type: "POST",
                      url: res['url'],
                      data: res['activate'],
                      success: function success(res) {
                        moveNextPage();
                      }
                    });
                  }
                });
              }
            });
          } else {

            req = ajaxSend({
              data: {
                'action': 'activate_plugins',
                'plugins': plugins
              }
            });

            req.success(function (res) {
              if (res.hasOwnProperty('activate')) {
                $.ajax({
                  type: "POST",
                  url: res['url'],
                  data: res['activate'],
                  success: function success(res) {
                    moveNextPage();
                  }
                });
              }
            });
          }
        });
      });
    }

    /**
     * Install templates page.
     */
  };pages['templates'] = {
    /**
     * Initialize events.
     */
    init: function init() {
      if (jupiterx.templates) {
        jupiterx.templates.init();
      }

      $window.on('template-installed', function () {
        moveNextPage();
      });
    }

    // Headings carousel.
  };$nav.owlCarousel({
    center: true,
    items: 3,
    loop: false,
    dots: false,
    nav: false,
    mouseDrag: false,
    touchDrag: false,
    pullDrag: false,
    startPosition: jupiterxWizardSettings.currentPageIndex
  });

  // Initialize events.
  $window.on('content-loaded', function (event, page) {
    initEvents();

    if (page && pages[page]) {
      if (_.isFunction(pages[page].init)) {
        pages[page].init();
      }
    }
  });

  // Trigger events for current page viewing.
  $window.trigger('content-loaded', jupiterxWizardSettings.currentPage);
})(jQuery);