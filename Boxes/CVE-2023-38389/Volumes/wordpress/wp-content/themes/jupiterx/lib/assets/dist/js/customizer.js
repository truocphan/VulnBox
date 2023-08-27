'use strict';

(function ($, wp, jupiterx) {

  var api = wp.customize;

  /**
   * JupiterX main object.
   */
  var JupiterX = api.JupiterX || {};

  api.JupiterX = JupiterX;

  var groupKeys = [];
  var defaultSectionKeys = [];
  var defaultPanelKeys = [];

  var appendContainer = void 0,
      activeElement = void 0,
      rootHeadContainers = void 0,
      rootNodes = [],
      wasReflowed = false;

  var templateDirectory = window.jupiterxCustomizer.themeBase;

  JupiterX.ContainerSection = api.Section.extend({

    initialize: function initialize(id, options) {
      this.boxes = {};
      this.tabs = {};
      this.tabsButton = null;
      this.activeTab = null;
      api.Section.prototype.initialize.apply(this, arguments);
    },

    attachEvents: function attachEvents() {
      var self = this;

      var params = self.params,
          toggleSection = void 0;

      toggleSection = function toggleSection() {

        self.boxEvents();

        if (!_.isEmpty(params.tabs)) {
          self.tabsEvents();
        } else {
          self.container.find('[data-tab]').addClass('visible');
        }

        return self.expanded() ? self.collapse() : self.expand();
      };

      var back = self.container.find('.customize-section-back');

      if (back) {
        back = back[0];
      }

      // back button event.
      function backEvent(event) {
        if (api.utils.isKeydownButNotEnterEvent(event)) {
          return;
        }

        if ($(event.currentTarget).parents('.customize-pane-child').hasClass('control-searching')) {
          $(event.currentTarget).parents('.customize-pane-child').removeClass('control-searching');
        }

        event.preventDefault();

        toggleSection();
      }

      back.addEventListener('click', function (event) {
        backEvent(event);
      });

      back.addEventListener('keydown', function (event) {
        backEvent(event);
      });

      var boxesTitle = self.container.find('.accordion-section-title');

      if (boxesTitle) {
        boxesTitle = boxesTitle[0];
      }

      // title button event.
      function boxTitleEvent(event) {
        if (api.utils.isKeydownButNotEnterEvent(event)) {
          return;
        }

        event.preventDefault();

        var childBoxId = $(event.currentTarget).parent().attr('aria-owns');
        var searchChildBox = document.getElementById(childBoxId).querySelector('#jupiterx-customizer-control-search');
        var childBox = document.getElementById(childBoxId).querySelector('.customize-jupiterx-container-section');

        if (document.getElementsByClassName('.controls-loader').length === 0) {
          var loader = document.createElement('sapn');

          loader.id = 'controls-loader-wrapper';
          loader.className = 'controls-loader';

          childBox.insertBefore(loader, childBox.firstChild);
        }

        childBox.offsetParent.classList.add('loading-status');

        setTimeout(function () {
          var controlLoader = document.getElementById('controls-loader-wrapper');
          controlLoader.remove();

          childBox.offsetParent.classList.remove('loading-status');

          var settings = document.querySelector('.jupiterx-child-box-settings:first-child');
          var any = document.querySelector('.jupiterx-child-box-any:first-child');
          settings.classList.add('open');
          var toggleIcon = settings.getElementsByClassName('toggle-icon')[0];
          toggleIcon.classList.remove('dashicons-arrow-right');
          toggleIcon.classList.add('dashicons-arrow-down');
          any.classList.add('open');
        }, 1000);

        var paneChild = document.querySelector('.customize-pane-child');
        paneChild.classList.remove('control-searching');

        searchChildBox.value = '';

        searchChildBox.addEventListener('search', function (event) {
          var keyword = event.target.value.toLowerCase();

          if (keyword.length == 0) {
            event.target.closest('.customize-pane-child').classList.remove('control-searching');
            event.target.closest('.customize-pane-child').querySelector('.jupiterx-child-box').classList.remove('open');
            event.target.closest('.customize-pane-child').querySelector('.jupiterx-child-box').classList.add('open');

            return;
          }
        });

        searchChildBox.addEventListener('keyup', function (event) {
          setTimeout(function () {
            var keyword = event.target.value.toLowerCase();

            if (keyword.length == 0) {
              event.target.closest('.customize-pane-child').classList.remove('control-searching');
              event.target.closest('.customize-pane-child').querySelector('.jupiterx-child-box').classList.remove('open');
              event.target.closest('.customize-pane-child').querySelector('.jupiterx-child-box').classList.add('open');

              return;
            }

            event.target.closest('.customize-pane-child').classList.add('control-searching');
            event.target.closest('.customize-pane-child').querySelector('.jupiterx-child-box').classList.remove('search-visible', 'open');

            if (keyword.length >= 1) {
              var titles = document.querySelectorAll('.customize-control-title, .jupiterx-child-box-title');

              $('.customize-control').removeClass('control-founded');
              $('.customize-control').parents('.customize-pane-child').removeClass('parent-control-founded');
              $('.customize-control').parents('.jupiterx-child-box').removeClass('search-visible');

              titles.forEach(function (element) {
                var val = String(element.childNodes[0].nodeValue).trim().toLowerCase();

                if (val.indexOf(keyword) >= 0) {
                  if ($(element).parents('.jupiterx-child-box').find('.jupiterx-child-box-content').children().css('display') === 'none' || $(element).parents('.customize-controls-list').css('display') === 'none' || $(element).parents('.customize-control').css('display') === 'none' || $(element).parents('.jupiterx-group-controls').parents('.customize-control').css('display') === 'none' || !$(element).parents('.customize-pane-child').hasClass('open')) {
                    return;
                  }

                  $(element).addClass('control-founded');
                  $(element).parents('.customize-control').addClass('parent-control-founded');

                  var _childBox = $(element).parents('.jupiterx-child-box');
                  _childBox.addClass('search-visible open');

                  if (_childBox.find('.toggle-icon').hasClass('dashicons-arrow-right')) {
                    _childBox.find('.toggle-icon').toggleClass('dashicons-arrow-right dashicons-arrow-down');
                  }
                }
              });
            }
          }, 1000);
        });

        toggleSection();
        var box = document.getElementById(childBoxId).querySelector('.jupiterx-child-box');
        api.trigger('jupiterx-box-toggle', box);
      }

      boxesTitle.addEventListener('click', function (event) {
        boxTitleEvent(event);
      });

      boxesTitle.addEventListener('keydown', function (event) {
        boxTitleEvent(event);
      });
    },

    tabsEvents: function tabsEvents() {
      var self = this,
          tabsId = Object.keys(self.params.tabs);

      // Create tabs button event.
      self.tabsButton = self.container.find('.jupiterx-container-tabs-button').each(function (i, button) {
        button.addEventListener('click', function (event) {
          event.preventDefault();
          self.openTab(tabsId[i]);
          if (i === 1) {
            self.checkDisableBox(event);
          }
        });
      });

      // Set open tab on popup initial open.
      self.expanded.bind(function () {
        if (!self.expanded()) {
          return;
        }

        if (!self.activeTab) {
          self.openTab(tabsId[0]);
          return;
        }

        self.openTab(self.activeTab);
      });

      // Declaratively store tabs.
      _.each(tabsId, function (tabId, i) {
        self.tabs[tabId] = {
          button: self.tabsButton[i],
          boxes: self.container.find('[data-tab=\'' + tabId + '\']')
        };
      });
    },

    checkDisableBox: function checkDisableBox(event) {
      var childBox = $(event.currentTarget).parents('.customize-pane-child').find('.customize-jupiterx-container-section');
      var paneChild = $(event.currentTarget).parents('.customize-pane-child');

      paneChild.find('#jupiterx-customizer-control-search').val('');
      $('.customize-pane-child').removeClass('control-searching');

      childBox.children('.jupiterx-child-box').each(function (i, box) {
        var boxCounter = 0;
        var proBox = 0;

        $(box).removeClass('child-box-disable');

        if ($(box).hasClass('search-visible')) {
          $(box).removeClass('open, search-visible');
        }

        if (!$(box).hasClass('visible')) {
          return;
        }

        $(box).find('.jupiterx-child-box-content').children('li.customize-control').each(function (i, control) {
          if ($(control).css('display') !== 'none') {
            return;
          }

          ++boxCounter;
        });

        $(box).find('.jupiterx-child-box-content').children('li.customize-control-jupiterx-pro-box').each(function (i, control) {
          ++proBox;
        });

        if ($(box).find('.jupiterx-child-box-content').children('li.customize-control').length == boxCounter) {
          $(box).addClass('child-box-disable');
        }

        if ($(box).find('.jupiterx-child-box-content').children('li.customize-control').length == proBox) {
          if ($(box).hasClass('child-box-probox-icon')) {
            return;
          }

          $(box).addClass('child-box-probox-icon');
          var $LockBadge = $('<span class="child-box-probox-wrapper"><svg><use xlink:href="' + (templateDirectory + 'customizer/img/customizer-icons.svg#lock-badge') + '"></use></svg></span>');
          $(box).find('.jupiterx-child-box-header').append($LockBadge);
        }
      });
    },

    boxEvents: function boxEvents() {
      $('.jupiterx-child-box-settings:first-child, .jupiterx-child-box-any:first-child').addClass('open');
      $('.jupiterx-child-box-settings:first-child, .jupiterx-child-box-any:first-child').find('.toggle-icon').toggleClass('dashicons-arrow-down dashicons-arrow-right');

      // Toggle box.
      $('.jupiterx-child-box-toggle').on('click', function (event) {
        event.preventDefault();
        var box = $(event.currentTarget).parents('.jupiterx-child-box');

        if (box.hasClass('open')) {
          box.removeClass('open');
          $(event.currentTarget).find('.toggle-icon').toggleClass('dashicons-arrow-down dashicons-arrow-right');
          return;
        }

        $('.jupiterx-child-box:not(.child-box-disable)').removeClass('open');

        if (!$('.jupiterx-child-box:not(.child-box-disable)').hasClass('open')) {
          $('.jupiterx-child-box:not(.child-box-disable)').find('.toggle-icon').removeClass('dashicons-arrow-down');
          $('.jupiterx-child-box:not(.child-box-disable)').find('.toggle-icon').addClass('dashicons-arrow-right');
        }

        box.toggleClass('open');
        box.find('.toggle-icon').toggleClass('dashicons-arrow-down dashicons-arrow-right');
      });
    },

    openTab: function openTab(tabId) {
      if (_.isUndefined(this.tabs[tabId])) {
        return;
      }

      var button = $(this.tabs[tabId].button);
      var boxes = $(this.tabs[tabId].boxes);

      this.activeTab = tabId;
      this.hideTabs();

      button.addClass('active');
      boxes.addClass('visible');
    },

    hideTabs: function hideTabs() {
      var buttons = this.tabsButton.filter('.active');

      buttons.removeClass('active');
      $('.jupiterx-child-box.visible').removeClass('visible');
    }

  });

  api.reflowPaneContents = _.bind(function () {
    if (wasReflowed) {
      return;
    }

    if (document.activeElement) {
      activeElement = $(document.activeElement);
    }

    // Sort the sections within each panel.
    api.panel.each(function (panel) {
      if ('themes' === panel.id) {
        return; // Don't reflow theme sections, as doing so moves them after the themes container.
      }

      var defaultWpPanels = ['widgets', 'nav_menus'];

      if (defaultWpPanels.includes(panel.id)) {
        panel.params.group = 'general_settings';
      }

      var defautPanelIconsList = [];

      defautPanelIconsList[panel.params.id] = panel.params.title.replace(/ /g, '-').toLowerCase();

      if (undefined != panel.params.title && defaultWpPanels.includes(panel.id)) {
        if (!defaultPanelKeys.includes(panel.params.title)) {
          var $defaultPanelIcon = $('<span class="accordion-section-title-icon"><svg><use xlink:href="' + (templateDirectory + 'customizer/img/customizer-section.svg#' + defautPanelIconsList[panel.params.id]) + '"></use></svg></span>');
          defaultPanelKeys.push(panel.params.title);
          $('#accordion-panel-' + panel.params.id + ' h3').append($defaultPanelIcon);
          $('#accordion-panel-' + panel.params.id + ' h3').addClass('jx-default-section-title');
        }
      }

      if (_.isUndefined(panel.params.group) && 'jupiterx_pages' === panel.params.id) {
        panel.params.group = 'third_party';
      }

      if (_.isUndefined(panel.params.group) && 'woocommerce' === panel.params.id) {
        panel.params.group = 'woocommerce';
      }

      if (undefined != panel.params.group) {
        panel.container.find('.control-section-nav_menu').addClass('assigned-to-menu-location');
        panel.container.first('li').attr('data-group', panel.params.group);

        if (!groupKeys.includes(panel.params.group)) {
          var $general = $('<div class="jupiterx-group-wrapper"><h4>' + panel.params.group.replace(/_/i, " ") + '</h4><div class="jupiterx-customizer-group group-' + panel.params.group + '"></div></div>');
          groupKeys.push(panel.params.group);
          $('#accordion-section-themes').parent().append($general);
        }
      }

      var sections = panel.sections(),
          sectionHeadContainers = _.pluck(sections, 'headContainer');
      rootNodes.push(panel);

      appendContainer = panel.contentContainer.is('ul') ? panel.contentContainer : panel.contentContainer.find('ul:first');

      if (!api.utils.areElementListsEqual(sectionHeadContainers, appendContainer.children('[id]'))) {
        _(sections).each(function (section) {
          appendContainer.append(section.headContainer);
        });
        wasReflowed = true;
      }
    });

    // Sort the controls within each section.
    api.section.each(function (section) {
      if (section.id == 'title_tagline') {
        section.params.group = 'general_settings';
      }

      if (section.id == 'static_front_page') {
        section.params.group = 'general_settings';
      }

      if (section.id == 'jupiterx_pro') {
        section.params.group = 'general_settings';
      }

      var jxWpSections = ['title_tagline', 'static_front_page', 'custom_css', 'cei-section'];

      var wcSections = ['woocommerce_store_notice', 'woocommerce_product_catalog', 'woocommerce_product_images', 'woocommerce_checkout'];

      if (wcSections.includes(section.id)) {
        section.params.group = 'woocommerce';
      }

      var defaultSectionList = [];

      defaultSectionList = defaultSectionList.concat(wcSections);

      defaultSectionList = defaultSectionList.concat(jxWpSections);

      if (undefined != section.params.group) {
        section.container.first('li').attr('data-group', section.params.group);
      }

      var controls = section.controls(),
          controlContainers = _.pluck(controls, 'container');

      if (!section.panel()) {
        rootNodes.push(section);
      }

      appendContainer = section.contentContainer.is('ul') ? section.contentContainer : section.contentContainer.find('ul:first');

      if (!api.utils.areElementListsEqual(controlContainers, appendContainer.children('[id]'))) {
        _(controls).each(function (control) {
          if (control.params.box) {
            $('#box-' + control.params.box + '-' + section.params.id + ' .jupiterx-child-box-content').append(control.container);
          } else {
            appendContainer.append(control.container);
          }
        });
        wasReflowed = true;
      }

      var defautSectionIconsList = [];

      defautSectionIconsList[section.params.id] = section.params.title.replace(/ /g, '-').toLowerCase();
      if (section.id == 'cei-section') {
        defautSectionIconsList[section.params.id] = section.params.title.replace('/', '-').toLowerCase();
      }

      if (undefined != section.params.title && defaultSectionList.includes(section.id)) {
        if (!defaultSectionKeys.includes(section.params.title)) {
          var $defaultSectionIcon = $('<span class="accordion-section-title-icon"><svg><use xlink:href="' + (templateDirectory + 'customizer/img/customizer-section.svg#' + defautSectionIconsList[section.params.id]) + '"></use></svg></span>');
          defaultSectionKeys.push(section.params.title);
          $('#accordion-section-' + section.params.id + ' h3').append($defaultSectionIcon);
          $('#accordion-section-' + section.params.id + ' h3').addClass('jx-default-section-title');
        }
      }
    });

    // Sort the root panels and sections.
    rootNodes.sort(api.utils.prioritySort);
    rootHeadContainers = _.pluck(rootNodes, 'headContainer');
    appendContainer = $('#customize-theme-controls .customize-pane-parent'); // @todo This should be defined elsewhere, and to be configurable.
    if (!api.utils.areElementListsEqual(rootHeadContainers, appendContainer.children())) {
      _(rootNodes).each(function (rootNode) {
        appendContainer.append(rootNode.headContainer);
      });
      wasReflowed = true;
    }

    // Now re-trigger the active Value callbacks so that the panels and sections can decide whether they can be rendered.
    api.panel.each(function (panel) {
      var value = panel.active();
      panel.active.callbacks.fireWith(panel.active, [value, value]);
    });
    api.section.each(function (section) {
      var value = section.active();
      section.active.callbacks.fireWith(section.active, [value, value]);
    });

    // Restore focus if there was a reflow and there was an active (focused) element.
    if (wasReflowed && activeElement) {
      activeElement.trigger('focus');
    }
    api.trigger('pane-contents-reflowed');
  }, api);

  api.bind('ready', function () {
    var $inputWrapper = $('<div class="jupiterx-customizer-search-wrapper"></div>');

    var $searchIcon = $('<svg><use xlink:href="' + (templateDirectory + 'customizer/img/customizer-icons.svg#search') + '"></use></svg>'),
        $searchInput = $('<input type="search" id="jupiterx-customizer-search" autocomplete="off" placeholder="Search Options">');

    $inputWrapper.append($searchInput);
    $inputWrapper.append($searchIcon);
    $('#accordion-section-themes').parent().append($inputWrapper);

    var groups = $('[data-group]');
    _.each(groups, function (group, i) {
      if (!groupKeys.includes(group.dataset.group)) {
        var $general = $('<div class="jupiterx-group-wrapper"><h4>' + group.dataset.group.replace(/_/i, " ") + '</h4><div class="jupiterx-customizer-group group-' + group.dataset.group + '"></div></div>');
        groupKeys.push(group.dataset.group);
        $('#accordion-section-themes').parent().append($general);
      }
    });

    $searchInput.on('keyup', function (e) {
      $('.search-visible-group').removeClass('search-visible-group');
      $('.search-visible-section').removeClass('search-visible-section');
      var keyword = e.currentTarget.value.toLowerCase();

      if (keyword.length >= 1) {
        $('.jupiterx-group-wrapper .accordion-section-title').each(function (i, el) {
          $('.customize-pane-parent').addClass('searching');

          if (el.childNodes.length < 4) {
            return;
          }

          var val = String(el.childNodes[0].nodeValue).trim().toLowerCase();
          var childValue = String(el.childNodes[2].nodeValue).trim().toLowerCase();

          if (val.indexOf(keyword) >= 0 || childValue.indexOf(keyword) >= 0) {
            $(el).parent().addClass('search-visible-section');
            $(el).parents('.jupiterx-group-wrapper').addClass('search-visible-group');
          }
        });
      } else {
        $('.customize-pane-parent').removeClass('searching');
      }
    });

    $searchInput.on('search', function (e) {
      if (e.currentTarget.value.length <= 1) {
        $('.customize-pane-parent').removeClass('searching');
      }
    });
  });

  api.bind('pane-contents-reflowed', function () {
    if ($('[data-group]').length) {
      var groups = $('[data-group]');

      _.each(groups, function (group, i) {
        var groupName = group.dataset.group;
        $(group).appendTo($('.group-' + groupName));
      });

      $('.customize-pane-parent > li.control-section:not([data-group])').appendTo($('.group-third_party'));
    }
  });

  /**
   * Fonts object.
   *
   * @since 1.0.0
   */
  var Fonts = {
    /**
     * Font stack used in Customizer preview.
     *
     * @since 1.0.0
     */
    stack: {},

    /**
       * Initialize fonts.
       *
       * @since 1.22.0
       *
       * @return {object,}
       */
    initialize: function initialize() {
      var self = this;

      // No need to run if we already have the fonts.
      if (!_.isEmpty(self.stack)) {
        return self.stack;
      }

      // Make an AJAX call to set the fonts object.
      jQuery.post(ajaxurl, {
        'action': 'jupiterx_get_fonts_detail',
        '_ajax_nonce': window.jupiterxCustomizer.nonce
      }, function (response) {
        var data = response.data,
            fonts = data.all_fonts;

        _.each(fonts, function (props, name) {
          var type = props.type || props,
              value = props.value || name;

          var newFont = {
            name: name,
            type: type,
            value: value
          };

          self.addToStack(newFont);
        });
      });
    },

    /**
     * Add to stack.
     *
     * @since 1.0.0
     *
     * @param {object} font - Font details.
     * @return {void}
     */
    addToStack: function addToStack(font) {
      if (!font.type && !font.name) {
        return;
      }

      if (_.isEmpty(this.stack[font.type])) {
        this.stack[font.type] = [];
      }

      if (!this.isFontExists(font.name)) {
        this.stack[font.type].push(font);
      }
    },

    /**
     * Check font existing to stack.
     *
     * @since 1.0.0
     *
     * @param {string} name - Font name.
     * @return {boolean}
     */
    isFontExists: function isFontExists(name) {
      return typeof _.findWhere(this.stack, { name: name }) !== 'undefined';
    },

    /**
     * Load font.
     *
     * @since 1.23.0
     *
     * @return void
     */
    loadWebFont: function loadWebFont(font) {
      var config = {},
          previewer = void 0,
          fontWeights = void 0;

      if (!font.type && !font.name) {
        return;
      }

      if (Fonts.isFontExists(font.name)) {
        return;
      }

      fontWeights = '100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';

      if (font.type === 'google') {
        config.google = {
          families: [font.name + ':' + fontWeights]
        };
      }

      previewer = $('#customize-preview iframe');
      if (previewer.attr('name')) {
        WebFont.load(_.extend(_.clone(config), {
          context: frames[previewer.attr('name')]
        }));
      }
    }
  };

  JupiterX.fonts = Fonts;
  Fonts.initialize();

  /**
   * Control components handle js plugins, events, and etc. Must code the 'ready' script properly in able for the
   * component to initialized correctly in a single or group control. It is important for a control that stores array or object
   * format values to have a hidden data container that binds in {{{ data.link }}}.
   *
   * @since 1.0.0
   */
  var Components = {
    /**
     * Color control component.
     */
    'jupiterx-color': {
      /**
       * Initialize behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var controls = this.container.find('.jupiterx-color-control'),
            params = _.defaults(this.params, {
          opacity: true
        });

        _.each(controls, function (control) {
          var container = $(control),
              field = container.find('.jupiterx-color-control-field'),
              correctColor = void 0,
              setColor = void 0;

          correctColor = function correctColor(color) {
            return color.getAlpha() < 1 ? color.toRgbString() : color.toHexString();
          };

          setColor = function setColor(color) {
            field.val(!_.isNull(color) ? correctColor(color) : '').trigger('change');
          };

          container.find('.jupiterx-color-control-field').spectrum({
            containerClassName: 'jupiterx-spectrum-container',
            replacerClassName: 'jupiterx-spectrum-replacer',
            preferredFormat: "hex6",
            showButtons: false,
            showInitial: true,
            showInput: true,
            showAlpha: params.opacity,
            allowEmpty: true,
            change: setColor,
            move: setColor
          });
        });
      }
    },

    /**
     * Image control component.
     */
    'jupiterx-image': {
      /**
       * Initialize behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var controls = this.container.find('.jupiterx-image-upload-control');

        _.each(controls, function (control) {
          var container = $(control),
              field = container.find('input[type=hidden]'),
              previewer = container.find('.jupiterx-image-upload-control-preview'),
              remover = container.find('.jupiterx-image-upload-control-remove'),
              uploadButton = container.find('.jupiterx-image-upload-control-add'),
              frame = void 0;

          uploadButton.on('click', function () {
            if (frame) {
              frame.open();
              return;
            }

            frame = wp.media({
              title: 'Insert Media',
              multiple: false
            });

            frame.on('select', function () {
              var attachment = frame.state().get('selection').first().toJSON();

              container.addClass('has-image');
              previewer.prop('src', attachment.url);
              field.val(attachment.url);
              field.trigger('change');
            });

            frame.open();
          });

          remover.on('click', function (e) {
            e.stopPropagation();
            container.removeClass('has-image');
            previewer.prop('src', '');
            field.val('');
            field.trigger('change');
          });
        });
      }
    },

    /**
     * RadioImage control component.
     */
    'jupiterx-radio-image': {
      /**
       * Initialize behaviors.
       *
       * @since 1.3.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var controls = this.container.find('.jupiterx-radio-image-control');

        _.each(controls, function (control) {
          var container = $(control);

          container.find('.pro-preview').on('click', function () {
            event.preventDefault();

            var $this = $(this),
                template = wp.template('customize-jupiterx-pro-preview-lightbox');

            $.featherlight({
              otherClose: '.jupiterx-pro-preview-back',
              variant: 'jupiterx-pro-preview-lightbox',
              html: template({
                preview: $this.data('preview')
              })
            });
          });
        });
      }
    },

    /**
     * Multicheck control component.
     */
    'jupiterx-multicheck': {
      /**
       * Initialize behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var controls = this.container.find('.jupiterx-multicheck-control');

        _.each(controls, function (control) {
          var container = $(control),
              inputElements = container.find('input[type=checkbox]'),
              hiddenField = container.find('input[type=hidden]');

          container.find('input[type=checkbox]').on('change', function () {
            var value = [];

            // Create array values.
            inputElements.filter(':checked').each(function (i, field) {
              value[i] = $(field).val();
            });

            // Store to hidden data container.
            hiddenField.val(value).trigger('change');
          });
        });
      },
      /**
       * Filter the data before save.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      filterData: function filterData(value) {
        if (!_.isString(value)) {
          return value;
        }

        return value.split(',');
      }
    },

    /**
     * Choose control component.
     */
    'jupiterx-choose': {
      /**
       * Initialize behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      ready: function ready() {
        if (!this.params.multiple) {
          return;
        }

        var controls = this.container.find('.jupiterx-choose-control');

        _.each(controls, function (control) {
          var container = $(control),
              inputElements = container.find('input[type=checkbox]'),
              hiddenField = container.find('input[type=hidden]');

          container.find('input[type=checkbox]').on('change', function () {
            var value = [];

            // Create array values.
            inputElements.filter(':checked').each(function (i, field) {
              value[i] = $(field).val();
            });

            // Store to hidden data container.
            hiddenField.val(value).trigger('change');
          });
        });
      },
      /**
       * Filter the data before save.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      filterData: function filterData(value) {
        return this.params.multiple && _.isString(value) ? value.split(',') : value;
      }
    },

    /**
     * Input control component.
     */
    'jupiterx-input': {
      /**
       * Initialize behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var controls = this.container.find('.jupiterx-input-control');

        _.each(controls, function (control) {
          var container = $(control),
              input = container.find('input.jupiterx-input-control-input'),
              hidden = container.find('input[type=hidden]'),
              ranges = container.find('.jupiterx-input-control-input-range');

          input.on('keyup blur change', function () {
            hidden.trigger('change');
          });

          var inputId = input.attr('id');
          var rangeId = ranges.attr('id');

          var inputField = document.getElementById(inputId);
          var rangeField = document.getElementById(rangeId);

          if (!inputField || !rangeField) {
            return;
          }

          rangeField.addEventListener('input', function (event) {
            inputField.value = event.target.value;
          });

          inputField.addEventListener('input', function (event) {
            rangeField.value = event.target.value;
          });

          rangeField.addEventListener('wheel', function (event) {
            inputField.value = event.target.value;
          });

          inputField.addEventListener('wheel', function (event) {
            rangeField.value = event.target.value;
          });
        });
      }
    },

    /**
     * Text control component.
     */
    'jupiterx-text': {
      /**
       * Initialize behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var controls = this.container.find('.jupiterx-input-control');

        _.each(controls, function (control) {
          var container = $(control),
              input = container.find('input.jupiterx-input-control-field'),
              ranges = container.find('.jupiterx-input-control-field-range');

          var inputId = input.attr('id');
          var rangeId = ranges.attr('id');

          var inputField = document.getElementById(inputId);
          var rangeField = document.getElementById(rangeId);

          if (!inputField || !rangeField) {
            return;
          }

          rangeField.addEventListener('input', function (event) {
            inputField.value = event.target.value;
          });

          inputField.addEventListener('input', function (event) {
            rangeField.value = event.target.value;
          });

          rangeField.addEventListener('wheel', function (event) {
            inputField.value = event.target.value;
          });

          inputField.addEventListener('wheel', function (event) {
            rangeField.value = event.target.value;
          });
        });
      }
    },

    /**
     * Font control component.
     */
    'jupiterx-font': {
      /**
       * Initialize behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var controls = this.container.find('.jupiterx-font-control');
        var select2Element = controls.find('.jupiterx-select-field');

        select2Element.select2({
          minimumResultsForSearch: 20,
          placeholder: 'Default',
          allowClear: true,
          containerCssClass: 'jupiterx-select2-container',
          dropdownCssClass: 'jupiterx-select2-dropdown jupiterx-select2-dropdown-wrapped',
          dropdownAutoWidth: true,
          dropdownParent: select2Element.parents('.customize-control:first')
        });

        select2Element.on('select2:select', function (event) {
          if ($(event.params.data.element) === 'undefined') {
            return;
          }

          var currentTarget = $(event.params.data.element),
              type = currentTarget.data('type'),
              value = currentTarget.val();

          var newFont = {
            name: value,
            type: type,
            value: value
          };

          Fonts.loadWebFont(newFont);
        });

        controls.find('.jupiterx-select-field').on('select2:open', function () {
          $('.select2-container').addClass('is_font_family_select');
          $('.select2-search__field').attr('placeholder', 'Search...');
        });
      }
    },

    /**
     * Template selector component.
     */

    'jupiterx-template': {
      /**
       * Initialize behaviors.
       *
       * @since 1.1.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var control = this,
            params = control.params,
            templateType = params.templateType,
            container = control.container,
            elementor = jupiterx.elementor,
            $control = container.find('.jupiterx-control'),
            $select = container.find('select'),
            $edit = container.find('.jupiterx-edit'),
            $add = container.find('.jupiterx-add');

        var value = params.value;

        var updateSelect = function updateSelect() {
          if (typeof jupiterx.elementor === 'undefined') {
            return;
          }

          elementor.getTemplates({
            data: {
              type: templateType
            },

            beforeSend: function beforeSend() {
              $select.empty();
              $control.addClass('jupiterx-loading');
              $select.append('<option value selected>Loading...</option>');
            },

            success: function success(templates) {
              $select.empty();
              $control.removeClass('jupiterx-loading');
              $control.toggleClass('jupiterx-has-value', value ? true : false);
              $select.append('<option value selected>' + params.placeholder + '</option>');

              _.each(templates, function (name, id) {
                var selected = parseInt(value) === parseInt(id) ? 'selected' : '';
                $select.append('<option ' + selected + ' value="' + id + '">' + name + '</option>');
              });

              $select.trigger('change');
            }
          });
        };

        var hasSelected = function hasSelected() {
          var hasValue = value ? true : false;
          $control.toggleClass('jupiterx-has-value', hasValue);
        };

        // On change template.
        $select.on('change', function () {
          value = $select.val();
          hasSelected();
        });

        if (window.jupiterxPremium) {
          // Template edit button event.
          $edit.on('click', function (event) {
            event.preventDefault();
            if (typeof jupiterx.elementor === 'undefined') {
              return;
            }

            // Prevent editing.
            if (!$select.val()) {
              return;
            }

            jupiterx.elementor.openEditor({
              action: 'edit',
              post: $select.val(),
              beforeClose: function beforeClose(contentWindow) {
                var status = contentWindow.elementor.channels.editor.request('status');
                if (status === false) {
                  wp.customize.previewer.refresh();
                } else if (status === true && !confirm('The changes you made will be lost if you leave this page.')) {
                  return false;
                }
              }
            });
          });

          // Template add button event.
          $add.on('click', function (event) {
            event.preventDefault();
            if (typeof jupiterx.elementor === 'undefined') {
              return;
            }

            jupiterx.elementor.openEditor({
              action: 'new',
              type: params.templateType,
              beforeClose: function beforeClose(contentWindow) {
                value = contentWindow.elementor.config.document.id;
                $select.val(contentWindow.elementor.config.document.id);
                updateSelect();
              }
            });
          });
        }

        updateSelect();
      }
    },

    /**
     * Select control component.
     */
    'jupiterx-select': {
      /**
       * Initialize behaviors.
       *
       * @since 1.20.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var control = this,
            params = control.params,
            $control = control.container,
            $select = $control.find('select');

        if (_.isEmpty(params.jupiterx) || _.isEmpty(params.jupiterx.select2)) {
          return;
        }

        // Get selected value label.
        if (params.value.length) {
          jQuery.ajax({
            url: ajaxurl,
            method: 'GET',
            data: {
              _ajax_nonce: window.jupiterxCustomizer.nonce,
              action: 'jupiterx_core_customizer_get_post_title',
              id: params.value
            }
          }).done(function (response) {
            var selectedOption = new Option(response.data, params.value, true, true);

            $select.append(selectedOption).trigger('change');
          }).fail(function () {
            console.log('error');
          });
        }

        // Select2
        $select.select2({
          minimumInputLength: 2,
          placeholder: params.placeholder,
          allowClear: true,
          containerCssClass: 'jupiterx-select2-secondary',
          dropdownCssClass: 'jupiterx-select2-secondary',
          width: '100%',
          ajax: {
            url: ajaxurl,
            delay: 250,
            data: function data(select2Params) {
              var query = {
                _ajax_nonce: window.jupiterxCustomizer.nonce,
                action: params.jupiterx.select2.action,
                post_type: params.jupiterx.select2.post_type,
                s: select2Params.term
              };

              return query;
            },
            processResults: function processResults(data) {
              return {
                results: data.data
              };
            },
            cache: true
          }
        });
      }
    }
  };

  JupiterX.components = Components;

  /**
   * Get control components.
   *
   * @since 1.0.0
   *
   * @param {string} component
   * @returns {mixed}
   */
  var getControlComponents = function getControlComponents(component) {
    if (_.isUndefined(Components[component])) {
      return;
    }

    return Components[component];
  };

  /**
   * Handles events and data control uniquely.
   *
   * @since 1.0.0
   */
  var uniqueComponents = {
    /**
     * Child popup control component.
     */
    'jupiterx-child-popup': {
      /**
       * Initialize behaviors.
       *
       * @since 1.0.0
       *
       * @returns {void}
       */
      ready: function ready() {
        var control = this,
            params = control.params,
            initialItems = void 0,
            updateItemsView = void 0;

        if (!_.isEmpty(params.bindItems)) {
          updateItemsView = function updateItemsView(to) {
            if (!_.isArray(to)) {
              return;
            }

            var display = to,
                items = control.container.find('.jupiterx-child-popup-control-item');

            items.each(function (i, element) {
              var item = $(element),
                  value = item.data('value'),
                  hidden = !_.contains(display, value);

              item.toggleClass('hidden', hidden);
            });
          };

          initialItems = wp.customize(params.bindItems).get();
          wp.customize(params.bindItems).bind(updateItemsView);
          updateItemsView(initialItems);
        }

        if (params.sortable) {
          control.container.find('.jupiterx-child-popup-control-items').sortable({
            stop: function stop() {
              var setting = [];

              control.container.find('.jupiterx-child-popup-control-item').each(function (i, item) {
                setting.push($(item).data('value'));
              });

              control.setting.set(setting);
            }
          });
        }

        if (!_.isEmpty(params.target)) {
          control.container.find('.jupiterx-button').on('click', function (event) {
            event.preventDefault();

            var button = $(this),
                childPopupId = button.data('id');

            wp.customize.section(params.target, function (target) {
              target.openChildPopup(childPopupId);
            });
          });
        }
      }
    }

    /**
     * Get unique components.
     *
     * @since 1.0.0
     *
     * @param {string} component
     * @returns {mixed}
     */
  };var getUniqueComponents = function getUniqueComponents(component) {
    if (_.isUndefined(uniqueComponents[component])) {
      return;
    }

    return uniqueComponents[component];
  };

  /**
   * Class for control's base.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.Control
   *
   * @constructor
   */
  JupiterX.Control = api.Control.extend({
    types: {
      JUPITERX_SELECT: 'jupiterx-select',
      JUPITERX_EXCEPTIONS: 'jupiterx-exceptions',
      JUPITERX_TEMPLATE: 'jupiterx-template'
    },

    defaultActiveArguments: { duration: 0, completeCallback: $.noop },

    /**
     * Initialize.
     *
     * @since 1.0.0
     *
     * @param {string} id      Unique identifier for the control instance.
     * @param {object} options Options hash for the control instance.
     * @returns {void}
     */
    initialize: function initialize(id, options) {
      var control = this;

      api.Control.prototype.initialize.call(control, id, options);

      // After the control is embedded on the page, invoke this method.
      control.deferred.embedded.done(function () {
        control.responsiveEvents();
        control.unitsEvents();
        control.unitInputValidate();
        control.stepizeInputs();
        control.actuallyReady();
      });
    },

    /**
     * Embed the control into the container document.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    embed: function embed() {
      var control = this,
          sectionId = control.section();

      if (!sectionId) {
        return;
      }

      wp.customize.section(sectionId, function (section) {
        section.expanded.bind(function (expanded) {
          api.bind('jupiterx-box-toggle', function (e) {
            control.actuallyEmbed();
          });
        });
      });
    },

    /**
     * Actually embed to delay control render.
     *
     * This function is called in Section.onChangeExpanded() so the control
     * will only get embedded when the Section is expanded.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    actuallyEmbed: function actuallyEmbed() {
      if ('resolved' === this.deferred.embedded.state()) {
        return;
      }

      this.renderContent();
      this.deferred.embedded.resolve();
    },

    /**
     * Link elements between settings and inputs.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    linkElements: function linkElements() {
      var control = this,
          nodes = void 0,
          radios = {},
          element = void 0;

      nodes = control.container.find('[data-customize-setting-link], [data-setting-property-link]');

      nodes.each(function () {
        var node = $(this),
            property = void 0,
            viewport = void 0,
            name = void 0;

        if (node.data('customizeSettingLinked')) {
          return;
        }

        node.data('customizeSettingLinked', true);

        if (node.is(':radio')) {
          name = node.prop('name');

          if (radios[name]) {
            return;
          }

          radios[name] = true;
          node = nodes.filter('[name="' + name + '"]');
        }

        property = node.data('settingPropertyLink');

        if (property) {
          element = new api.Element(node);
          element.bind(function (to, from) {
            return control.savePropertyValue(to, from, node);
          });
          control.elements[property] = [];
          control.elements[property].push(element);
          return;
        }

        element = new api.Element(node);
        element.bind(function (to, from) {
          return control.saveValue(to, from, node);
        });
        control.elements.push(element);
      });
    },

    /**
     * Attach responsive events.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    responsiveEvents: function responsiveEvents() {
      var control = this;

      control.container.find('.jupiterx-responsive-switcher a').on('click', function (event) {
        $(event.currentTarget).parent().toggleClass('open');
      });

      $(document).on("mouseup", function (event) {
        var switcher = control.container.find('.jupiterx-responsive-switcher a');

        if (!switcher.is(event.target) && !switcher.is(event.target) && switcher.has(event.target).length == 0) {
          $('.jupiterx-responsive-switcher a').parent().removeClass('open');
        }
      });

      control.container.find('.jupiterx-responsive-switcher a').on('click', function (event) {
        api.previewedDevice.set($(event.currentTarget).data('device'));
      });
    },

    /**
     * Events to trigger preview redirection.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    previewRedirectionEvents: function previewRedirectionEvents() {
      var control = this;

      $(document).on('change', control.selector + ' .jupiterx-renew-preview', function (e) {
        var options = {};

        options[e.target.getAttribute('data-customize-setting-link')] = e.target.value;

        if (control.params.type === control.types.JUPITERX_EXCEPTIONS) {
          options[control.id] = control.setting();
        }

        control.redirectOptionPreview(control.params.section, options);
      });
    },

    /**
     * Change preview URL to a new one based on option.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    redirectOptionPreview: function redirectOptionPreview(sectionId, options) {
      if (!window.jupiterxCustomizer) {
        return;
      }

      wp.ajax.post('jupiterx_core_customizer_preview_redirect_url', {
        _ajax_nonce: window.jupiterxCustomizer.customizer_preview_redirect_url_nonce,
        section: sectionId,
        options: JSON.stringify(options)
      }).done(function (response) {
        if (!response.redirectUrl) {
          return;
        }

        if (api.previewer.previewUrl.get() === response.redirectUrl) {
          api.previewer.refresh();

          return;
        }

        api.previewer.previewUrl.set(response.redirectUrl);
      }).fail(function () {
        api.previewer.refresh();
      });
    },

    /**
     * Attach unit selector events.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    unitsEvents: function unitsEvents() {
      var controls = this.container.find('.jupiterx-control-units-container');

      _.each(controls, function (control) {
        var container = $(control),
            field = container.find('input[type=hidden]'),
            unitSelector = container.find('.jupiterx-control-unit-selector'),
            selectedUnit = unitSelector.find('.selected-unit');

        if (selectedUnit[0].classList.contains('disabled')) {
          return;
        }

        if (unitSelector.children().length < 2) {
          unitSelector.addClass('jupiterx-control-unit-one');
        }

        unitSelector.on('click', 'li', function (e) {

          if (e.target.classList.contains('selected-unit')) {
            return;
          }

          unitSelector.find('.selected-unit').removeClass('selected-unit');
          $(e.target).addClass('selected-unit');

          var unit = e.target.innerText.toLowerCase();
          field.val(unit).trigger('change');

          var $inputs = container.parents('.jupiterx-control').find(unitSelector.data('inputs'));
          var $inputsRange = container.parents('.jupiterx-control').find('.jupiterx-input-control-input-range');
          var $lastInputeValue = $inputs.prop('max');
          'px' === unit ? $inputs.attr('step', 1) : $inputs.attr('step', .1);
          '%' === unit ? $inputsRange.prop('max', 100) : $inputsRange.prop('max', $lastInputeValue);
          $inputs.trigger('stepper.destroy', [unit]);
          $inputs.stepper({ decimals: 1, min: 0, max: 1000 });
        });
      });
    },

    /**
     * Change unit to none if input is not a numeric value.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    unitInputValidate: function unitInputValidate() {
      var controls = this.container.find('.jupiterx-input-control:not(.jupiterx-text-control)');

      _.each(controls, function (control) {
        var container = $(control),
            field = container.find('input[type=text]'),
            unitsContainer = container.find('.jupiterx-control-units-container'),
            selectedUnit = unitsContainer.find('.selected-unit'),
            initialUnit = selectedUnit.text();

        field.on('keyup focus blur', function () {
          var val = field.val();
          if (!isNaN(parseFloat(val)) && isFinite(val) || _.isEmpty(val)) {
            if ('-' === selectedUnit.text()) {
              selectedUnit.text(initialUnit);
            }
            return;
          }
          selectedUnit.text('-');
        });
      });
    },

    /**
     * Add Stepper to inputs
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    stepizeInputs: function stepizeInputs() {
      var controls = [];
      controls.push(this.container.find('input.jupiterx-input-control-input'));
      controls.push(this.container.filter('.customize-control-jupiterx-box-model').find('input.jupiterx-box-model-control-input'));

      _.each(controls, function (control) {
        control.stepper({
          decimals: 1,
          min: 0,
          max: 1000
        });
      });
    },

    /**
     * Save value generically.
     *
     * @since 1.0.0
     *
     * @param {mixed} to    New value of the control.
     * @param {mixed} from  Old value of the control.
     * @param {object} node Element that holds the value.
     * @returns {void}
     */
    saveValue: function saveValue(to, from, node) {
      var control = this,
          viewport = void 0,
          value = void 0,
          setting = void 0;

      if (to === from) {
        return;
      }

      if (!_.isUndefined(control.filterData)) {
        to = control.filterData(to);
      }

      viewport = node.data('settingViewportLink');
      value = to;

      if (viewport) {
        setting = control.setting.get();
        value = _.extend({}, setting);
        value[viewport] = to;
        control.setting.set(value);
        return;
      }

      control.setting.set(value);
    },

    /**
     * Save value as property from object.
     *
     * @since 1.0.0
     *
     * @param {mixed} to    New value of the control.
     * @param {mixed} from  Old value of the control.
     * @param {object} node Element that holds the value.
     * @returns {void}
     */
    savePropertyValue: function savePropertyValue(to, from, node) {
      var control = this,
          viewport = void 0,
          property = void 0,
          setting = void 0,
          value = void 0;

      if (to === from) {
        return;
      }

      if (!_.isUndefined(control.filterData)) {
        to = control.filterData(to);
      }

      viewport = node.data('settingViewportLink');
      property = node.data('settingPropertyLink');
      setting = control.setting.get();
      value = _.extend({}, setting);

      if (viewport) {
        value[viewport] = _.extend({}, setting[viewport]);
        value[viewport][property] = to;
        control.setting.set(value);
        return;
      }

      value[property] = to;
      control.setting.set(value);
    },

    /**
     * Additional behaviors.
     *
     * Runs after the controls is embedded.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    actuallyReady: function actuallyReady() {}
  });

  JupiterX.TemplateControl = JupiterX.Control.extend({
    /**
     * Add control template.
     *
     * The field param must have property `id` and `property` to work properly.
     *
     * @since 1.0.0
     *
     * @param {object} field     Field control arguments.
     * @param {jquery} container Container of the control where to append.
     * @returns {object}
     */
    addControl: function addControl(field, container) {
      var control = this,
          templateId = void 0,
          template = void 0,
          classes = void 0,
          content = void 0,
          component = void 0;

      // Format the template name.
      templateId = 'customize-control-' + field.type + '-content';

      // Nothing to do since template is not found.
      if (!document.getElementById('tmpl-' + templateId)) {
        return;
      }

      // Set control container.
      container = container || control.container.find('.jupiterx-group-controls');

      // Create field defaults args.
      field = _.defaults(field, {
        column: 12,
        cssClass: ''
      });

      // Create data link.
      field.link = 'data-customize-setting-link="' + field.id + '" ' + (field.link ? field.link : '');

      // Create string of input attributes.
      if (_.isObject(field.inputAttrs)) {
        field.inputAttrs = _.map(field.inputAttrs, function (value, attr) {
          return attr + '="' + value + '"';
        }).join(' ');
      }

      // Create string of control attributes.
      if (_.isObject(field.controlAttrs)) {
        field.controlAttrs = _.map(field.controlAttrs, function (value, attr) {
          return attr + '="' + value + '"';
        }).join(' ');
      }

      // Control classes.
      classes = _.compact(['jupiterx-col-' + field.column, 'customize-control customize-control-' + field.type, field.cssClass, field.property ? control.params.type + '-control-' + field.property.replace(/_/g, '-') : '', field.responsive ? 'customize-control-responsive' : '']);

      // Create template wrapper.
      content = $('<li></li>', {
        class: classes.join(' ')
      });

      // Append template to control container.
      template = wp.template(templateId);
      content.append(template(field));
      container.append(content);

      // Link elements by content.
      control.linkElements(field, content);

      // Create control events.
      component = Components[field.type];
      if (component && component.ready) {
        component.ready.call({ container: content, params: field });
      }

      return content;
    },

    /**
     * Link elements between settings and inputs.
     *
     * @since 1.0.0
     *
     * @param {object} params  Field parameters.
     * @param {jquery} content Holds the control elements.
     * @returns {void}
     */
    linkElements: function linkElements(params, content) {
      if (_.isEmpty(params) || _.isEmpty(content)) {
        return;
      }

      var control = this,
          nodes = void 0,
          radios = {},
          element = void 0,
          property = void 0;

      nodes = content.find('[data-customize-setting-link], [data-setting-property-link]');
      property = params.property;
      control.elements[property] = [];

      nodes.each(function () {
        var node = $(this),
            name = void 0;

        if (node.data('customizeSettingLinked')) {
          return;
        }

        node.data('customizeSettingLinked', true);

        if (node.is(':radio')) {
          name = node.prop('name');

          if (radios[name]) {
            return;
          }

          radios[name] = true;
          node = nodes.filter('[name="' + name + '"]');
        }

        element = new api.Element(node);
        control.elements[property].push(element);
        element.bind(function (to, from) {
          return control.saveValue(to, from, property, node, params);
        });
      });
    },

    /**
     * Save value behavior.
     *
     * @since 1.0.0
     *
     * @param {mixed}  to       New value of the control.
     * @param {mixed}  from     Old value of the control.
     * @param {string} property Base property name.
     * @param {object} node     Element that holds the value.
     * @param {object} params   Field params.
     * @returns {void}
     */
    saveValue: function saveValue(to, from, property, node, params) {
      var control = this,
          component = Components[params.type],
          trail = property,
          responsive = control.params.responsive,
          propertyLink = void 0,
          viewportLink = void 0,
          value = void 0;

      /**
       * Recursively search keys and apply value at the last key name.
       *
       * @param {string} path  String keys path format.
       * @param {mixed}  value New value.
       * @param {object} ref   Object reference from existing value.
       */
      var getObj = function getObj(path, value, ref) {
        var keys = path.split('.');
        ref = _.extend({}, ref);

        if (keys.length === 1) {
          ref[keys[0]] = value;
          return ref;
        } else {
          var current = keys.shift();
          ref[current] = getObj(keys.join('.'), value, ref[current]);
          return ref;
        }
      };

      // Call component data filter before save.
      if (component && component.filterData) {
        to = component.filterData.call({ params: params }, to);
      }

      propertyLink = node.data('settingPropertyLink');

      if (propertyLink) {
        trail = trail + '.' + propertyLink;
      }

      viewportLink = node.data('settingViewportLink');

      // If control is responsive and viewport is empty then set it to desktop.
      viewportLink = responsive && !viewportLink ? 'desktop' : viewportLink;

      if (viewportLink) {
        trail = viewportLink + '.' + trail;
      }

      value = _.extend({}, control.setting.get());
      value = getObj(trail, to, value);
      control.setting.set(value);
    }
  });

  /**
   * Class for Group control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.GroupControl
   *
   * @constructor
   */
  JupiterX.GroupControl = JupiterX.TemplateControl.extend({
    /**
     * Initialize.
     *
     * @since 1.0.0
     *
     * @param {string} id      Unique identifier for the control instance.
     * @param {object} options Options hash for the control instance.
     * @returns {void}
     */
    initialize: function initialize(id, options) {
      JupiterX.Control.prototype.initialize.call(this, id, options);
    },

    /**
     * Initialize behaviors.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    ready: function ready() {
      var control = this,
          params = control.params;

      // Append control.
      _.each(params.fields, function (field) {
        control.addControl(field);
      });
    }
  });

  /**
   * Class for Color control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.ColorControl
   *
   * @constructor
   */
  JupiterX.ColorControl = JupiterX.Control.extend(getControlComponents('jupiterx-color'));

  /**
   * Class for Image control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.ImageControl
   *
   * @constructor
   */
  JupiterX.ImageControl = JupiterX.Control.extend(getControlComponents('jupiterx-image'));

  /**
   * Class for RadioImage control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.RadioImageControl
   *
   * @constructor
   */
  JupiterX.RadioImageControl = JupiterX.Control.extend(getControlComponents('jupiterx-radio-image'));

  /**
  * Class for Child Popup control.
  *
  * @memberOf wp.customize
  * @alias wp.customize.JupiterX.ChildPopupControl
  *
  * @constructor
  */
  JupiterX.ChildPopupControl = JupiterX.Control.extend(getUniqueComponents('jupiterx-child-popup'));

  /**
   * Class for Popup control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.PopupControl
   *
   * @constructor
   */
  JupiterX.PopupControl = JupiterX.Control.extend(getUniqueComponents('jupiterx-popup'));

  /**
   * Class for Multicheck control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.MulticheckControl
   *
   * @constructor
   */
  JupiterX.MulticheckControl = JupiterX.Control.extend(getControlComponents('jupiterx-multicheck'));

  /**
   * Class for Choose control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.ChooseControl
   *
   * @constructor
   */
  JupiterX.ChooseControl = JupiterX.Control.extend(getControlComponents('jupiterx-choose'));

  /**
   * Class for Input control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.InputControl
   *
   * @constructor
   */
  JupiterX.InputControl = JupiterX.Control.extend(getControlComponents('jupiterx-input'));

  /**
   * Class for Font control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.FontControl
   *
   * @constructor
   */
  JupiterX.FontControl = JupiterX.Control.extend(getControlComponents('jupiterx-font'));

  /**
   * Class for Template control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.Template
   *
   * @constructor
   */
  JupiterX.Template = JupiterX.Control.extend(getControlComponents('jupiterx-template'));

  /**
   * Class for Select control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.SelectControl
   *
   * @constructor
   */
  JupiterX.SelectControl = JupiterX.Control.extend(getControlComponents('jupiterx-select'));

  /**
   * Class for Exceptions control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.ExceptionsControl
   *
   * @constructor
   */
  JupiterX.ExceptionsControl = JupiterX.TemplateControl.extend({
    /**
     * Initialize.
     *
     * @since 1.0.0
     *
     * @param {string} id      Unique identifier for the control instance.
     * @param {object} options Options hash for the control instance.
     * @returns {void}
     */
    initialize: function initialize(id, options) {
      this.controls = {};
      JupiterX.Control.prototype.initialize.call(this, id, options);
    },

    /**
     * Initialize behaviors.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    ready: function ready() {
      var control = this,
          params = control.params,
          value = control.setting.get();

      // Render current exceptions.
      _.each(value, function (data, id) {
        if (params.fields[id]) {
          control.addException({
            id: id,
            text: params.fields[id].label,
            value: data
          });
        }
      });
    },

    /**
     * Additional behaviors.
     *
     * Runs after the controls is embedded.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    actuallyReady: function actuallyReady() {
      var control = this,
          choices = _.keys(control.params.fields),
          fields = control.params.fields,
          container = control.container,
          addWrapper = container.find('.jupiterx-exceptions-control-add'),
          addSelect = addWrapper.find('.jupiterx-select-control-field'),
          removeOption = void 0,
          addOption = void 0,
          toggleOptions = void 0;

      removeOption = function removeOption(id) {
        addSelect.find('option[value=' + id + ']').remove();
        toggleOptions();
      };

      addOption = function addOption(id) {
        if (fields[id]) {
          addSelect.append(new Option(fields[id].label, id, false, false));
          toggleOptions();
        }
      };

      toggleOptions = function toggleOptions() {
        var options = addSelect.find('option');
        addWrapper.toggle(options.length > 0);
      };

      _.each(_.keys(control.setting.get()), function (key) {
        removeOption(key);
      });

      addSelect.select2({
        minimumResultsForSearch: -1,
        placeholder: 'Add New Exception',
        allowClear: true,
        containerCssClass: 'jupiterx-select2-container',
        dropdownCssClass: 'jupiterx-select2-dropdown',
        // dropdownParent: addWrapper,
        width: '100%'
      }).on('select2:select', function (event) {
        event.preventDefault();

        var data = event.params.data,
            value = control.setting.get();

        // Make sure key not exists yet.
        if (data && data.id && !value[data.id]) {
          control.exceptionInitialData(data);
          control.addException(data);
          removeOption(data.id);
        }

        // Reset to placeholder name.
        addSelect.val('').change();
      });

      addWrapper.find('.jupiterx-button').click(function (event) {
        event.preventDefault();
        addSelect.select2('open');
      });

      container.on('click', '.jupiterx-exceptions-control-remove', function (event) {
        event.preventDefault();
        if (!confirm('Are you sure you want to remove this exception?')) {
          return;
        }

        var element = $(event.currentTarget),
            id = element.data('id'),
            value = control.setting.get();

        if (id && value[id]) {
          control.removeException(id);
          element.closest('.jupiterx-exceptions-control-group').remove();
          addOption(id);
        }

        addSelect.val('').change();
      });

      addSelect.val('').change();

      control.setting.bind('change', function (to, from) {
        to = to || {};
        from = from || {};

        if (Object.keys(to).length === Object.keys(from).length) {
          return;
        }

        var options = {};
        options[control.id] = JSON.stringify(Object.keys(to));

        control.redirectOptionPreview(control.params.section, options);
      });
    },

    addException: function addException(data) {
      var control = this,
          params = control.params,
          container = control.container.find('.jupiterx-exceptions-control-items'),
          template = wp.template('customize-jupiterx-exceptions-control-group'),
          content = $(template(data)),
          controls = content.find('.jupiterx-group-controls');

      control.controls[data.id] = {};

      // Add each control.
      _.each(params.fields[data.id].options, function (field, property) {
        field.property = property;
        field.value = data.value ? data.value[property] : field.default;
        field.id = control.id + '_' + data.id + '_' + property;
        field.link = 'data-setting-property-link="' + data.id + '.' + property + '"';

        // Add control.
        control.controls[data.id][property] = control.addControl(field, controls);
      });

      if (control.id === 'jupiterx_title_bar_exceptions') {
        control.titleBarEvents(data.id);
        control.titleBarToggleFields(data.id);
      }

      // Finally append the controls container.
      container.append(content);
    },

    exceptionInitialData: function exceptionInitialData(data) {
      var control = this,
          params = control.params,
          id = data.id,
          value = void 0;


      value = _.extend({}, control.setting.get());
      value[id] = {};
      _.each(params.fields[id].options, function (field, property) {
        value[id][property] = field.default;
      });
      control.setting.set(value);
    },

    removeException: function removeException(id) {
      var control = this,
          value = void 0;

      value = _.extend({}, control.setting.get());
      delete value[id];
      control.setting.set(value);
    },

    titleBarEvents: function titleBarEvents(id) {
      var control = this,
          controls = control.controls;

      if (!controls[id]) {
        return;
      }

      if (controls[id].type) {
        controls[id].type.find('input[type=radio]').on('change', function () {
          control.titleBarToggleFields(id);
        });
      }
    },

    titleBarToggleFields: function titleBarToggleFields(id) {
      var control = this,
          value = control.setting.get();

      if (!value[id]) {
        return;
      }

      var controls = control.controls[id],
          isCustom = value[id].type === '_custom';

      var toggle = function toggle(property, _toggle) {
        if (controls[property]) {
          controls[property].toggleClass('hidden', _toggle);
        }
      };

      toggle('full_width', isCustom);
      toggle('elements', isCustom);
      toggle('title_tag', isCustom);
      toggle('pro_box', !isCustom);
      toggle('template', !isCustom);
    },

    /**
     * Save value behavior.
     *
     * @since 1.0.0
     *
     * @param {mixed}  to       New value of the control.
     * @param {mixed}  from     Old value of the control.
     * @param {string} property Base property name.
     * @param {object} node     Element that holds the value.
     * @param {object} params   Field params.
     * @returns {void}
     */
    saveValue: function saveValue(to, from, property, node, params) {
      var control = this,
          component = Components[params.type],
          propertyMap = void 0,
          setting = void 0,
          value = void 0;

      // Create control events.
      if (component && component.filterData) {
        to = component.filterData.call({ params: params }, to);
      }

      /**
       * Recursively search keys and apply value at the last key name.
       *
       * @param {string} map   String keys path format.
       * @param {mixed}  value New value.
       * @param {object} ref   Object reference from existing value.
       */
      var setObj = function setObj(map, value, ref) {
        var keys = map.split('.');
        ref = _.extend({}, ref);

        if (keys.length === 1) {
          ref[keys[0]] = value;
          return ref;
        } else {
          var current = keys.shift();
          ref[current] = setObj(keys.join('.'), value, ref[current]);
          return ref;
        }
      };

      propertyMap = node.data('settingPropertyLink');
      setting = control.setting.get();
      value = setObj(propertyMap, to, setting);
      control.setting.set(value);

      if (params.type !== control.types.JUPITERX_SELECT) {
        var options = {};
        options[params.id] = to;
        options[control.id] = control.setting();

        control.redirectOptionPreview(control.params.section, options);
      }
    }
  });

  /**
   * Class for Background control.
   *
   * @memberOf wp.customize
   * @alias wp.customize.JupiterX.BackgroundGroupControl
   *
   * @constructor
   */
  JupiterX.BackgroundGroupControl = JupiterX.GroupControl.extend({
    /**
     * Additional behaviors.
     *
     * Runs after the controls is embedded.
     *
     * @since 1.0.0
     *
     * @returns {void}
     */
    actuallyReady: function actuallyReady() {
      var control = this,
          setting = control.setting.get(),
          classicFields = control.container.find('.for-classic'),
          gradientFields = control.container.find('.for-gradient'),
          videoFields = control.container.find('.for-video'),
          initialType = !_.isUndefined(setting.type) ? setting.type : 'classic',
          toggleFields = void 0;

      if (control.params.responsive) {
        initialType = !_.isUndefined(setting.desktop) && !_.isUndefined(setting.desktop.type) ? setting.desktop.type : 'classic';
      }

      toggleFields = function toggleFields(type) {
        if (type) {
          classicFields.toggleClass('hidden', type !== 'classic' ? true : false);
          gradientFields.toggleClass('hidden', type !== 'gradient' ? true : false);
          videoFields.toggleClass('hidden', type !== 'video' ? true : false);
        }
      };

      _.each(control.elements.type, function (element) {
        element.bind(toggleFields);
      });

      toggleFields(initialType);
    }
  });

  // Add new sections to wp.customize.sectionConstructor.
  $.extend(api.sectionConstructor, {
    'kirki-popup': JupiterX.PopupSection,
    'kirki-pane': JupiterX.PaneSection,
    'kirki-jupiterx-link': JupiterX.LinkSection,
    'kirki-container': JupiterX.ContainerSection
  });

  // Add new controls to wp.customize.controlConstructor
  $.extend(api.controlConstructor, {
    'jupiterx-text': api.controlConstructor,
    'jupiterx-input': JupiterX.InputControl,
    'jupiterx-textarea': JupiterX.Control,
    'jupiterx-select': JupiterX.SelectControl,
    'jupiterx-toggle': JupiterX.Control,
    'jupiterx-choose': JupiterX.ChooseControl,
    'jupiterx-divider': JupiterX.Control,
    'jupiterx-label': JupiterX.Control,
    'jupiterx-position': JupiterX.Control,
    'jupiterx-radio-image': JupiterX.RadioImageControl,
    'jupiterx-multicheck': JupiterX.MulticheckControl,
    'jupiterx-color': JupiterX.ColorControl,
    'jupiterx-image': JupiterX.ImageControl,
    'jupiterx-child-popup': JupiterX.ChildPopupControl,
    'jupiterx-popup': JupiterX.PopupControl,
    'jupiterx-box-model': JupiterX.Control,
    'jupiterx-font': JupiterX.FontControl,
    'jupiterx-exceptions': JupiterX.ExceptionsControl,
    'jupiterx-template': JupiterX.Template,
    'jupiterx-pro-box': JupiterX.Control
  });

  // Add new group controls to wp.customize.controlConstructor
  $.extend(api.controlConstructor, {
    'jupiterx-box-shadow': JupiterX.GroupControl,
    'jupiterx-typography': JupiterX.GroupControl,
    'jupiterx-border': JupiterX.GroupControl,
    'jupiterx-background': JupiterX.BackgroundGroupControl
  });

  /**
   * Make custom css very high priority.
   */
  api.bind('ready', function () {
    if (api.panel('woocommerce')) {
      api.panel('woocommerce').priority(285);
    }
    api.section('custom_css').priority(1000);
  });

  // Append popup template.
  var customizeControls = $('#customize-controls'),
      popupContent = wp.template('customize-jupiterx-popup-content');

  customizeControls.append(popupContent());

  // Create draggable for popups.
  var popups = $('.jupiterx-popup');

  popups.draggable({
    containment: 'body',
    handle: '.jupiterx-popup-header, .jupiterx-child-popup-header'
  });
})(jQuery, wp, jupiterx);