'use strict';

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

(function ($) {

  /**
   * SearchFilters class.
   */
  var SearchFilters = function () {
    function SearchFilters(props) {
      _classCallCheck(this, SearchFilters);

      var element = props.element,
          updateResults = props.updateResults;

      this.element = element;
      this.filters = this.element.find('.filter-field');
      this.contentFilter = this.filters.filter('[name=s]');
      this.window = $(window);
      this.body = $('body');
      this.updateResults = updateResults;
      this.desktop = 768;
      this.init();
      this.events();
    }

    _createClass(SearchFilters, [{
      key: 'init',
      value: function init() {
        var self = this;
        var element = self.element,
            filters = self.filters;

        var select = element.find('.jupiterx-templates-select-field');

        filters.filter(':not([type=text])').on('change', _.debounce(self.update.bind(self), 300));

        // Text events.
        filters.filter('[type=text]').on('keyup', _.debounce(self.update.bind(self), 300));

        select.each(function (i, node) {
          new CustomSelect($(node));
        });
      }
    }, {
      key: 'events',
      value: function events() {
        var self = this;
        var header = $('.jupiterx-header');

        if (header.length) {
          var settings = header.data('jupiterx-settings');
          var behavior = settings.behavior,
              position = settings.position;


          if (behavior === 'fixed' && position === 'top') {
            self.fixed();
            self.window.resize(self.fixed.bind(self));
          } else if (behavior === 'sticky') {
            self.sticky();
            self.window.scroll(self.sticky.bind(self));
          }
        }
      }
    }, {
      key: 'fixed',
      value: function fixed() {
        var self = this;
        var element = self.element,
            body = self.body,
            desktop = self.desktop;


        if (self.window.width() >= desktop) {
          var header = $('.jupiterx-header');
          var offset = body.offset();
          var height = body.hasClass('admin-bar') ? offset.top + header.outerHeight() : header.outerHeight();
          element.css('top', height);
        } else {
          element.css('top', '');
        }
      }
    }, {
      key: 'sticky',
      value: function sticky() {
        var self = this;
        var element = self.element,
            body = self.body,
            desktop = self.desktop;

        var header = $('.jupiterx-header');
        var settings = header.data('jupiterx-settings');
        var scrollOffset = settings.offset;
        var scrollTop = self.window.scrollTop();

        if (self.window.width() < desktop) {
          element.removeClass('sticked');
          element.css('top', '');
        } else if (!element.hasClass('sticked') && scrollTop > scrollOffset && body.hasClass('jupiterx-header-sticked')) {
          var offset = body.offset();
          var height = body.hasClass('admin-bar') ? offset.top + header.outerHeight() : header.outerHeight();
          element.addClass('sticked');
          element.css('top', height);
        } else if (element.hasClass('sticked') && scrollTop <= scrollOffset) {
          element.removeClass('sticked');
          element.css('top', '');
        }
      }
    }, {
      key: 'getFilters',
      value: function getFilters() {
        var filters = {};

        this.filters.each(function (i, filter) {
          filter = $(filter);

          // Only save filters with value.
          var val = filter.val();
          if (val) {
            filters[filter.attr('name')] = val;
          }
        });

        return filters;
      }
    }, {
      key: 'update',
      value: function update() {
        this.updateResults(this.getFilters());
      }
    }, {
      key: 'clearFilters',
      value: function clearFilters() {
        // Exclude important fields.
        var filters = this.filters.filter(':not([name=product_id]):not([name=posts_per_page]):not([name=s])');
        filters.each(function (i, filter) {
          filter = $(filter);
          if (filter.val()) {
            filter.val('').trigger('change', {
              clearFilters: true
            });
          }
        });
      }
    }]);

    return SearchFilters;
  }();

  /**
   * CustomSelect class.
   */


  var CustomSelect = function () {
    function CustomSelect(node) {
      _classCallCheck(this, CustomSelect);

      this.element = $(node);
      this.window = $(window);
      this.checkboxes = this.element.find('input[type=checkbox]');
      this.menu = this.element.find('.dropdown-menu');
      this.values = this.element.find('.selected-values');
      this.labels = this.element.find('.selected-labels');
      this.clear = this.element.find('.clear-selected');
      this.options = {};
      this.desktop = 768;

      // Set initial selected values.
      var val = this.values.val();
      this.selected = val ? val.split(',') : [];

      this.init();
    }

    _createClass(CustomSelect, [{
      key: 'init',
      value: function init() {
        var self = this;
        var element = self.element,
            window = self.window,
            checkboxes = self.checkboxes,
            clear = self.clear,
            values = self.values;

        var toggle = element.find('.dropdown-toggle');

        checkboxes.each(function (i, option) {
          option = $(option);
          self.options[option.val()] = option.data('label');
        });

        checkboxes.on('change', function () {
          var option = $(this);
          self.update(option.val(), option.is(':checked'));
        });

        clear.on('click', function (event) {
          event.preventDefault();
          event.stopPropagation();
          self.clearSelected();
        });

        values.on('change', function (event, data) {
          if (data && data.clearFilters) {
            self.clearSelected();
          }
        });

        // Toggle menu.
        toggle.on('click', function (event) {
          event.preventDefault();
          self.expand();
        });

        // Close on click outside.
        $(document).on('click', function (event) {
          // For desktop behavior only.
          if (window.width() >= self.desktop) {
            if (!element.is(event.target) && element.has(event.target).length === 0) {
              self.collapse();
            }
          }
        });

        // Prevent dropdown from closing.
        element.on('click', '.dropdown-menu', function (event) {
          event.stopPropagation();
        });
      }
    }, {
      key: 'update',
      value: function update(value, checked) {
        var _this = this;

        // Add or remove value from selected stack.
        if (checked) {
          this.selected.push(value);
        } else {
          this.selected.splice(this.selected.indexOf(value), 1);
        }

        // Set value.
        this.values.val(this.selected).trigger('change');

        // Set labels.
        var labels = this.selected.map(function (key) {
          return _this.options[key];
        });
        labels = labels.length ? labels.join(', ') : jupiterxTemplates.i18n.all;
        this.labels.text(labels);

        // Hide or show clear button.
        if (this.selected.length) {
          this.clear.addClass('show');
        } else {
          this.clear.removeClass('show');
        }
      }
    }, {
      key: 'clearSelected',
      value: function clearSelected() {
        if (this.selected.length) {
          this.selected = [];
          this.values.val(this.selected).trigger('change');
          this.labels.text(jupiterxTemplates.i18n.all);
          this.clear.removeClass('show');

          // Force uncheck.
          this.checkboxes.each(function (i, option) {
            $(option).attr('checked', false);
          });
        }
      }
    }, {
      key: 'expand',
      value: function expand() {
        var element = this.element,
            window = this.window,
            menu = this.menu;

        var classname = window.width() <= 767 ? 'show-mobile' : 'show';
        var hasClass = !(element.hasClass(classname) || menu.hasClass(classname));

        // Container and menu class.
        element.toggleClass(classname, hasClass);
        menu.toggleClass(classname, hasClass);
      }
    }, {
      key: 'collapse',
      value: function collapse() {
        this.element.removeClass('show');
        this.menu.removeClass('show');
      }
    }]);

    return CustomSelect;
  }();

  /**
   * SearchResults class.
   */


  var SearchResults = function () {
    function SearchResults(props) {
      _classCallCheck(this, SearchResults);

      var node = props.node,
          getFilters = props.getFilters,
          getPagination = props.getPagination,
          customImport = props.customImport;

      this.element = $(node);
      this.window = $(window);
      this.document = $(document);
      this.getFilters = getFilters;
      this.getPagination = getPagination;
      this.isCustomImport = customImport;
      this.jqxhr = null;
      this.page = 1;
      this.ready = true;
      this.maxed = false;
      this.cache = {};
      this.events();
    }

    _createClass(SearchResults, [{
      key: 'events',
      value: function events() {
        switch (this.getPagination()) {
          case 'load_more':
            this.document.on('click', '.jupiterx-templates-loadmore button', this.more.bind(this));break;
          default:
            this.window.on('scroll', _.throttle(this.scroll.bind(this), 50));break;
        }
      }
    }, {
      key: 'scroll',
      value: function scroll() {
        if (this.ready && this.check()) {
          this.load(false);
        }
      }
    }, {
      key: 'more',
      value: function more() {
        if (this.ready) {
          this.load(false);
        }
      }
    }, {
      key: 'reload',
      value: function reload(callback) {
        this.load(true, callback);
      }
    }, {
      key: 'load',
      value: function load(reload, callback) {
        var self = this;

        // Short circuit.
        if (!reload && self.maxed) {
          return;
        }

        var filters = self.getFilters();

        // Prevent multiple loading.
        self.ready = false;

        // Remove preloader.
        self.element.children('.jupiterx-templates-placeholder, .jupiterx-templates-loader, .jupiterx-templates-empty, .jupiterx-templates-loadmore').remove();

        // Handle correct settings.
        if (reload) {
          self.element.children('.jupiterx-templates-template').remove();
          self.page = 1;
          self.maxed = false;
          self.placeholder(filters);
        } else {
          self.element.append($(self.loader()));
        }

        // Get the current filters.
        filters['page'] = self.page;
        Object.keys(filters).forEach(function (key) {
          if (!filters[key]) {
            delete filters[key];
          }
        });

        // Abort previous XHR.
        if (self.jqxhr && self.jqxhr.abort) {
          self.jqxhr.abort();
        }

        // Create XHR.
        self.jqxhr = $.ajax({
          type: 'POST',
          url: _wpUtilSettings.ajax.url,
          data: {
            action: 'jupiterx_api',
            nonce: jupiterxTemplates.nonce,
            method: 'get_templates',
            filters: filters
          }
        });

        self.jqxhr.done(function (res) {
          // Remove recently added preloader.
          self.element.children('.jupiterx-templates-placeholder, .jupiterx-templates-loader').remove();

          if (res.posts && res.posts.length) {
            // Reached the maximum posts.
            if (res.posts.length < filters.posts_per_page) {
              self.maxed = true;
            }

            res.posts.forEach(function (post) {
              self.element.append(self.card(post, res));
            });
          } else {
            // Assumes it reached maximum posts to load.
            self.maxed = true;

            // Empty element.
            if (reload) {
              self.element.append($(self.empty()));
            }
          }

          // Ready for next.
          self.done(res, filters);
          self.page++;
          self.ready = true;

          // Do callback.
          if (typeof callback === 'function') {
            callback(res);
          }
        });
      }
    }, {
      key: 'done',
      value: function done(res, filters) {
        var self = this;

        this.initTemplateInfoPopover(res);

        if (!self.maxed && self.getPagination() === 'load_more') {
          self.element.append($(self.loadmore()));
        }
      }

      // Reference: https://stackoverflow.com/questions/17104265/caching-a-jquery-ajax-response-in-javascript-browser
      // Todo : localCache() {}

    }, {
      key: 'check',
      value: function check() {
        var container = this.element.offset();

        // If the container can't be found, stop otherwise errors result
        if ('object' !== (typeof container === 'undefined' ? 'undefined' : _typeof(container))) {
          return false;
        }

        // Returns true when scroll reaches before bottom of the element.
        var scroll = this.window.scrollTop() + this.window.height();
        var threshold = this.element.outerHeight(false) + container.top - this.window.height() * 2;

        return scroll > threshold;
      }
    }, {
      key: 'empty',
      value: function empty() {
        return '\n      <div class="jupiterx-templates-empty">\n        <svg class="sad-tear" aria-hidden="true" focusable="false" width="50" height="50" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512">\n          <path fill="currentColor" d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm0 448c-110.3 0-200-89.7-200-200S137.7 56 248 56s200 89.7 200 200-89.7 200-200 200zm8-152c-13.2 0-24 10.8-24 24s10.8 24 24 24c23.8 0 46.3 10.5 61.6 28.8 8.1 9.8 23.2 11.9 33.8 3.1 10.2-8.5 11.6-23.6 3.1-33.8C330 320.8 294.1 304 256 304zm-88-64c17.7 0 32-14.3 32-32s-14.3-32-32-32-32 14.3-32 32 14.3 32 32 32zm160-64c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32zm-165.6 98.8C151 290.1 126 325.4 126 342.9c0 22.7 18.8 41.1 42 41.1s42-18.4 42-41.1c0-17.5-25-52.8-36.4-68.1-2.8-3.7-8.4-3.7-11.2 0z"></path>\n        </svg>\n        <p class="empty-title">' + jupiterxTemplates.i18n.empty + '</p>\n        <p class="empty-info">' + jupiterxTemplates.i18n.emptyInfo + '</p>\n      </div>\n      ';
      }
    }, {
      key: 'placeholder',
      value: function placeholder(filters) {
        var posts = filters.posts_per_page || 12;

        // Create fake post alike.
        var placeholder = '\n      <div class="jupiterx-templates-template jupiterx-templates-placeholder">\n        <div class="jupiterx-card">\n          <span class="jupiterx-card-img-top"></span>\n          <div class="jupiterx-card-body">\n            <span class="jupiterx-card-title"></span>\n            <span class="import-template"></span>\n          </div>\n        </div>\n      </div>\n      ';

        for (var i = 0; i < posts; i++) {
          this.element.append($(placeholder));
        }
      }
    }, {
      key: 'loader',
      value: function loader() {
        return '\n      <div class="jupiterx-templates-loader">\n        <span class="loader-round"></span>\n      </div>\n      ';
      }
    }, {
      key: 'loadmore',
      value: function loadmore() {
        return '\n      <div class="jupiterx-templates-loadmore">\n        <button class="button">' + jupiterxTemplates.i18n.loadMore + '</button>\n      </div>\n      ';
      }
    }, {
      key: 'card',
      value: function card(post, res) {
        var self = this;
        var is_pro = res.is_pro;
        var isCustomImport = self.isCustomImport;
        var demo_url = post.demo_url,
            featured_image = post.featured_image,
            free_template = post.free_template,
            list_of_used_plugins = post.list_of_used_plugins;
        var _jupiterxTemplates = jupiterxTemplates,
            i18n = _jupiterxTemplates.i18n,
            isPremium = _jupiterxTemplates.isPremium,
            proBadgeUrl = _jupiterxTemplates.proBadgeUrl,
            upgradeLink = _jupiterxTemplates.upgradeLink;
        var title = post.title,
            slug = post.slug,
            psd = post.psd,
            is_sketch = post.is_sketch;

        // Clean namings.

        title = title.replace(' Jupiterx', '');
        slug = slug.replace('-jupiterx', '');

        var template = $('\n        <div class="jupiterx-templates-template">\n          <div class="jupiterx-card">\n            ' + (!is_pro && free_template !== 'true' ? '<img class="jupiterx-pro-badge" src="' + proBadgeUrl + '" />' : '') + '\n            <span class="jupiterx-card-img-top" style="background-image: url(' + featured_image + ');"></span>\n            <div class="jupiterx-card-body">\n              <h4 class="jupiterx-card-title">' + title + '</h4>\n              ' + (isPremium && is_pro || free_template === 'true' ? '<button class="btn btn-primary import-template">' + i18n.import + '</button>' : '') + '\n              <a class="btn btn-outline-secondary preview-template" target="_blank" href="' + demo_url + '">' + i18n.preview + '</a>\n              ' + (psd && is_pro ? '<button class="btn btn-outline-primary psd-link">.psd</button>' : '') + '\n              ' + (is_sketch && is_sketch.toString() === 'true' && is_pro ? '<button class="btn btn-outline-primary sketch-link">.sketch</button>' : '') + '\n              ' + (list_of_used_plugins && list_of_used_plugins.length > 0 ? '<span class="jupiterx-icon-info-circle jupiterx-template-info" data-toggle="popover" data-placement="top" data-id="' + post.id + '"></span>' : '') + '\n            </div>\n          </div>\n        </div>\n      ');

        template.on('click', '.import-template', function (event) {
          event.preventDefault();

          if (!isCustomImport) {
            self.import(post);
          } else {
            self.importCustom(post);
          }
        });

        template.on('click', '.psd-link', function (event) {
          event.preventDefault();

          $.ajax({
            type: 'POST',
            url: _wpUtilSettings.ajax.url,
            data: {
              action: 'jupiterx_api',
              nonce: jupiterxTemplates.nonce,
              method: 'get_template_psd',
              template_name: slug
            },
            success: function success(_ref) {
              var status = _ref.status,
                  data = _ref.data;

              if (status && data.psd_link) {
                top.location.href = data.psd_link;
              }
            }
          });
        });

        template.on('click', '.sketch-link', function (event) {
          event.preventDefault();

          $.ajax({
            type: 'POST',
            url: _wpUtilSettings.ajax.url,
            data: {
              action: 'jupiterx_api',
              nonce: jupiterxTemplates.nonce,
              method: 'get_template_sketch',
              template_name: slug
            },
            success: function success(_ref2) {
              var status = _ref2.status,
                  data = _ref2.data;

              if (status && data.sketch_link) {
                top.location.href = data.sketch_link;
              }
            }
          });
        });

        return template;
      }
    }, {
      key: 'import',
      value: function _import(post) {
        var _jupiterxTemplates2 = jupiterxTemplates,
            i18n = _jupiterxTemplates2.i18n;


        jupiterx_modal({
          type: 'warning',
          title: i18n.installTitle,
          text: i18n.installText.replace('{template}', post.title),
          confirmButtonText: i18n.confirm,
          cancelButtonText: i18n.cancel,
          showCancelButton: true,
          showConfirmButton: true,
          onConfirm: function onConfirm() {
            jupiterx_modal({
              type: 'warning',
              title: i18n.mediaTitle,
              text: i18n.mediaText,
              confirmButtonText: i18n.mediaConfirm,
              cancelButtonText: i18n.mediaCancel,
              showCancelButton: true,
              showConfirmButton: true,
              onConfirm: function onConfirm() {
                new TemplateInstall({
                  media: false,
                  data: post,
                  partial: false
                });
              },
              onCancel: function onCancel() {
                new TemplateInstall({
                  media: true,
                  data: post,
                  partial: false
                });
              }
            });
          }
        });
      }
    }, {
      key: 'importCustom',
      value: function importCustom(post) {
        var _jupiterxTemplates3 = jupiterxTemplates,
            i18n = _jupiterxTemplates3.i18n;

        var template = $('\n        <h6>' + i18n.customTitle + '</h6>\n        <ul class="import-types">\n          <li>\n            <div class="custom-control custom-radio">\n              <input type="radio" class="custom-control-input" name="templates-import-type" id="templates-complete-import" checked>\n              <label class="custom-control-label" for="templates-complete-import">\n                <strong>' + i18n.completeImportTitle + '</strong>\n              </label>\n            </div>\n            <p>' + i18n.completeImportText + '</p>\n          </li>\n          <li>\n            <div class="custom-control custom-radio">\n              <input type="radio" class="custom-control-input" name="templates-import-type" id="templates-partial-import" ' + (jupiterxTemplates.template ? 'checked' : '') + '>\n              <label class="custom-control-label" for="templates-partial-import">\n                <strong>' + i18n.partialImportTitle + '</strong>\n              </label>\n            </div>\n            <p>' + i18n.partialImportText + '</p>\n          </li>\n        </ul>\n        <hr>\n        <div class="custom-control custom-checkbox">\n          <input type="checkbox" class="custom-control-input" name="templates-include-media" id="templates-include-media" checked>\n          <label class="custom-control-label" for="templates-include-media">' + i18n.customMediaText + '</label>\n        </div>\n      ');

        jupiterx_modal({
          modalCustomClass: 'jupiterx-templates-custom-install-modal',
          title: i18n.installTitle,
          text: template,
          type: 'warning',
          confirmButtonText: i18n.install,
          cancelButtonText: i18n.discard,
          showCancelButton: true,
          showConfirmButton: true,
          showCloseButton: true,
          showLearnmoreButton: false,
          onConfirm: function onConfirm() {
            var media = $('input#templates-include-media').prop('checked');
            var partial = $('input#templates-partial-import').prop('checked');
            var text = (!partial ? '' + i18n.completeImportWarning : '') + ' ' + i18n.askContinue;

            jupiterx_modal({
              title: i18n.installTitle,
              text: text,
              type: 'warning',
              confirmButtonText: i18n.yes,
              cancelButtonText: i18n.cancel,
              showCancelButton: true,
              showConfirmButton: true,
              showCloseButton: true,
              showLearnmoreButton: false,
              onConfirm: function onConfirm() {
                new TemplateInstall({
                  media: media,
                  data: post,
                  partial: partial
                });
              }
            });
          }
        });
      }
    }, {
      key: 'initTemplateInfoPopover',
      value: function initTemplateInfoPopover(res) {
        if (!res) {
          return;
        }

        $('.jupiterx-template-info').popover({
          trigger: 'focus hover',
          container: '.jupiterx-cp-install-template',
          html: true,
          content: function content() {
            var post = _.findWhere(res.posts, { id: parseInt($(this).data('id')) });
            var plugins = post && post.list_of_used_plugins ? post.list_of_used_plugins : [];

            return '\n            <div class="jupiterx-template-info-details">\n              <strong>' + jupiterxTemplates.i18n.plugins_used + ':</strong>\n              <span>' + plugins.join(', ') + '</span>\n            </div>\n          ';
          }
        });
      }
    }]);

    return SearchResults;
  }();

  /**
   * TemplateInstall class
   */


  var TemplateInstall = function () {
    function TemplateInstall(props) {
      _classCallCheck(this, TemplateInstall);

      var media = props.media,
          data = props.data,
          partial = props.partial;

      this.modal = null;
      this.media = media;
      this.data = data;
      this.partial = partial;
      this.current = 0;
      this.actions = this.getActions(partial);
      this.run();
    }

    _createClass(TemplateInstall, [{
      key: 'getActions',
      value: function getActions(partial) {
        if (partial) {
          return ['preparation', 'upload', 'unzip', 'validate', 'install_plugins', 'theme_content', 'activate_plugins', 'plugins_content', 'finalize'];
        }

        return ['preparation', 'backup_db', 'backup_media_records', 'reset_db', 'upload', 'unzip', 'validate', 'install_plugins', 'custom_tables', 'theme_content', 'activate_plugins', 'setup_pages', 'plugins_content', 'settings', 'menu_locations', 'theme_widget', 'restore_media_records', 'finalize'];
      }
    }, {
      key: 'run',
      value: function run() {
        var self = this;
        var actions = self.actions,
            media = self.media,
            partial = self.partial,
            data = self.data;


        if ('theme_content' === actions[self.current]) {
          self.importContent();
          return;
        }

        $.ajax({
          type: 'POST',
          url: _wpUtilSettings.ajax.url,
          timeout: 0,
          data: {
            action: 'jupiterx_api',
            method: 'import_template',
            type: actions[self.current],
            import_media: media,
            template_id: data.id,
            template_name: data.title,
            partial_import: partial
          },
          beforeSend: function beforeSend() {
            if (!self.modal && !self.modalProgress) {
              self.modal = self.progress();
            }
          },
          success: function success(res) {
            var status = res.status,
                message = res.message;

            if (!status) {
              self.error(message);
              return;
            }

            // Additional request before proceeding to next step.
            if ('install_plugins' === actions[self.current] && res.install) {
              $.ajax({
                type: 'POST',
                url: res['url'],
                data: res['install'],
                success: function success() {
                  self.message(message);
                  self.current++;
                  self.updateProgressBar();
                  self.run();
                }
              });
              return;
            }

            self.message(message);
            self.current++;
            self.updateProgressBar();

            if (self.current < actions.length) {
              self.run();
            } else {
              self.modal = null;
              self.done();
            }
          },
          error: function error() {
            self.error();
          }
        });
      }
    }, {
      key: 'importContent',
      value: function importContent() {
        var self = this;
        var _jupiterxTemplates4 = jupiterxTemplates,
            adminAjaxUrl = _jupiterxTemplates4.adminAjaxUrl;
        var _jupiterxTemplates5 = jupiterxTemplates,
            nonce = _jupiterxTemplates5.nonce;
        var media = self.media,
            partial = self.partial,
            data = self.data;

        var url = new URL(adminAjaxUrl);
        url.searchParams.append('action', 'jupiterx_api');
        url.searchParams.append('nonce', nonce);
        url.searchParams.append('method', 'import_template_content');
        url.searchParams.append('import_media', media);
        url.searchParams.append('template_id', data.id);
        url.searchParams.append('template_name', data.title);
        url.searchParams.append('partial_import', partial);

        // Create event.
        var event = new EventSource(url.href);

        // On success.
        event.addEventListener('message', function (res) {
          var data = JSON.parse(res.data);
          var message = '';
          if (data.message) {
            message = data.message;
          }

          if (!data.error && data.status) {
            self.message();
            self.current++;
            self.updateProgressBar();
            self.run();
          } else {
            self.error(message);
          }

          event.close();
        });
      }
    }, {
      key: 'progress',
      value: function progress() {
        var partial = this.partial;
        var _jupiterxTemplates6 = jupiterxTemplates,
            i18n = _jupiterxTemplates6.i18n;

        var html = $('\n        <div class="jupiterx-modal-header">\n          <h3 class="jupiterx-modal-title">' + i18n.progressTitle + '</h3>\n        </div>\n        <div class="jupiterx-modal-desc">\n          <ul class="jupiterx-modal-step-list">\n            ' + (!partial ? '<li class="step-backup">' + i18n.progressBackup + ' <span class="result-message"></span></li>' : '') + '\n            <li class="step-package">' + i18n.progressPackage + ' <span class="result-message"></span></li>\n            <li class="step-plugins">' + i18n.progressPlugins + ' <span class="result-message"></span></li>\n            <li class="step-install">' + i18n.progressInstall + ' <span class="result-message"></span></li>\n          </ul>\n        </div>\n      ');

        jupiterx_modal({
          html: html,
          showProgress: true,
          progress: '0%',
          showCloseButton: false,
          showConfirmButton: false,
          closeOnOutsideClick: false
        });

        return html;
      }
    }, {
      key: 'message',
      value: function message(_message) {
        var self = this;
        if (!self.modal instanceof jQuery) {
          return;
        }

        var modal = self.modal,
            actions = self.actions,
            current = self.current;

        var backup = modal.find('.step-backup .result-message');
        var upload = modal.find('.step-package .result-message');
        var plugins = modal.find('.step-plugins .result-message');
        var install = modal.find('.step-install .result-message');

        switch (actions[current]) {
          case 'backup_db':
          case 'backup_media_records':
            backup.text(_message);
            break;
          case 'reset_db':
            backup.text(_message).parent().addClass('step-done');
            break;
          case 'upload':
          case 'unzip':
            upload.text(_message);
            break;
          case 'validate':
            upload.text(_message).parent().addClass('step-done');
            break;
          case 'install_plugins':
            plugins.text(_message).parent().addClass('step-done');
            break;
          case 'activate_plugins':
            install.text(_message);
            break;
          case 'custom_tables':
          case 'theme_content':
          case 'menu_locations':
          case 'setup_pages':
          case 'plugins_content':
          case 'theme_widget':
          case 'restore_media_records':
            install.text(_message);
            break;
          case 'finalize':
            install.text(_message).parent().addClass('step-done');
            break;
        }
      }
    }, {
      key: 'done',
      value: function done() {
        var self = this;
        var _jupiterxTemplates7 = jupiterxTemplates,
            i18n = _jupiterxTemplates7.i18n;


        jupiterx_modal({
          title: i18n.completedTitle,
          text: i18n.completedText,
          type: 'success',
          showCancelButton: false,
          showConfirmButton: true,
          showCloseButton: false,
          showLearnmoreButton: false,
          closeOnOutsideClick: false,
          onConfirm: function onConfirm() {
            jupiterxTemplates.template = self.data.id;

            $(window).trigger('template-installed', {
              title: self.data.title,
              id: self.data.id,
              partial: self.partial,
              media: self.media
            });
          }
        });
      }
    }, {
      key: 'error',
      value: function error(message) {
        var _jupiterxTemplates8 = jupiterxTemplates,
            i18n = _jupiterxTemplates8.i18n;


        jupiterx_modal({
          title: i18n.errorTitle,
          text: message || i18n.errorText,
          type: 'error',
          showCancelButton: false,
          showConfirmButton: true,
          showCloseButton: false,
          showLearnmoreButton: false,
          closeOnOutsideClick: false
        });
      }
    }, {
      key: 'updateProgressBar',
      value: function updateProgressBar() {
        var progress = this.current / this.actions.length * 100;
        progress = progress.toFixed(2);

        jupiterx_modal.update({ progress: progress + '%' });
      }
    }]);

    return TemplateInstall;
  }();

  /**
   * TemplatesSearch class.
   */


  var TemplatesSearch = function () {
    function TemplatesSearch(_ref3) {
      var node = _ref3.node,
          customImport = _ref3.customImport;

      _classCallCheck(this, TemplatesSearch);

      this.element = $(node);
      this.window = $(window);
      this.html = $('html');
      this.body = $('body');
      this.count = this.element.find('.jupiterx-templates-toggle-filters .filters-count');
      this.foundPosts = this.element.find('.jupiterx-templates-toggle-filters .found-posts');
      this.pagination = this.element.data('pagination');
      this.customImport = customImport;
      this.filters = {};
      this.components = {};
      this.init();
      this.events();
    }

    _createClass(TemplatesSearch, [{
      key: 'init',
      value: function init() {
        var self = this;
        var element = this.element,
            components = this.components;

        // Define as loaded.

        element.data('loaded', true);

        components.filters = new SearchFilters({
          element: element.find('.jupiterx-templates-filters-container'),
          updateResults: self.updateResults.bind(this)
        });

        components.results = new SearchResults({
          node: element.find('.jupiterx-templates-results'),
          getFilters: self.getFilters.bind(this),
          getPagination: self.getPagination.bind(this),
          customImport: self.customImport
        });

        // Show initial results.
        self.updateResults(components.filters.getFilters());
      }
    }, {
      key: 'events',
      value: function events() {
        var self = this;
        var element = self.element,
            components = self.components;

        var search = element.find('.jupiterx-templates-toggle-filters .jupiterx-templates-search-field');
        var toggle = this.element.find('.jupiterx-templates-toggle-filters .toggle-button');
        var close = this.element.find('.jupiterx-templates-header .close-button');
        var clearFilters = this.element.find('.jupiterx-templates-header .clear-filters');
        var KEY_ENTER = 13;

        search.each(function (i, node) {
          var search = $(node);
          var input = search.find('input');
          var clear = search.find('.clear-button');

          // Update search text.
          var update = function update() {
            var filter = components.filters.contentFilter;
            filter.val(input.val());
            filter.trigger('keyup');
          };

          input.on('keyup', function (event) {
            // Show/hide clear.
            if (input.val()) {
              clear.show();
            } else {
              clear.hide();
            }

            if (event.keyCode === KEY_ENTER) {
              event.preventDefault();
              update();
            }
          });

          // Update changes.
          search.on('click', '.search-button', function () {
            update();
          });

          // Clear text.
          clear.on('click', function () {
            clear.hide();
            input.val('');
            update();
          });
        });

        toggle.on('click', function (event) {
          event.preventDefault();
          self.showHide(true);
        });

        close.on('click', function (event) {
          event.preventDefault();
          self.showHide(false);
        });

        clearFilters.on('click', function () {
          components.filters.clearFilters();
        });
      }
    }, {
      key: 'updateResults',
      value: function updateResults(filters) {
        var self = this;
        var element = self.element,
            foundPosts = self.foundPosts,
            count = self.count,
            components = self.components;

        self.filters = _extends({}, filters);

        // Filters with excluded keys.
        var exclude = ['s', 'posts_per_page', 'product_id'];
        for (var key in filters) {
          if (exclude.indexOf(key) >= 0) {
            delete filters[key];
          }
        }

        // Add loading class, show filters count, fix scrolling.
        element.addClass('is-loading');
        count.text(Object.keys(filters).length || 0);
        self.scroll();

        // Start loading results and run callback after.
        components.results.reload(function (res) {
          element.removeClass('is-loading');
          foundPosts.text(res.found_posts || 0);
        });
      }
    }, {
      key: 'getFilters',
      value: function getFilters() {
        return _extends({}, this.filters);
      }
    }, {
      key: 'scroll',
      value: function scroll() {
        var element = this.element,
            window = this.window,
            html = this.html,
            body = this.body;

        // Element container top.

        var top = element.offset().top;

        // Correct space when viewing page with admin bar.
        if (body.hasClass('admin-bar')) {
          var offset = html.css('margin-top');
          top -= parseFloat(offset);
        }

        if (window.scrollTop() > top) {
          window.scrollTop(top);
        }
      }
    }, {
      key: 'showHide',
      value: function showHide(toggle) {
        var components = this.components,
            body = this.body;

        components.filters.element.toggleClass('show', toggle);
        body.toggleClass('jupiterx-templates-block-scroll', toggle);
      }
    }, {
      key: 'getPagination',
      value: function getPagination() {
        return this.pagination;
      }
    }]);

    return TemplatesSearch;
  }();

  var init = function init() {
    var props = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var customImport = props.customImport;


    $('.jupiterx-templates-search').each(function (i, node) {
      if (!$(node).data('loaded')) {
        new TemplatesSearch({
          node: node,
          customImport: customImport
        });
      }
    });
  };

  window.jupiterx = jQuery.extend({}, window.jupiterx, {
    templates: {
      installedTemplate: jupiterxTemplates.installedTemplate || null,
      init: init
    }
  });
})(jQuery);