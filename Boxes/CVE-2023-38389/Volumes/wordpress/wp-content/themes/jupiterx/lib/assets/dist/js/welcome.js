'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

(function ($, wp) {
  var Welcome = function () {
    function Welcome() {
      _classCallCheck(this, Welcome);

      this.setState();
      this.setElements();
      this.fetchInactiveRequiredPlugins();
      this.events();
    }

    _createClass(Welcome, [{
      key: 'setState',
      value: function setState() {
        this.state = {
          bulkActions: [],
          plugins: []
        };
      }
    }, {
      key: 'setElements',
      value: function setElements() {
        this.el = {
          $pluginInactivatedList: $('.jupiterx-welcome-rplugins-failed'),
          $pluginList: $('.jupiterx-welcome-rplugins-list'),
          $progress: $('.jupiterx-welcome-rplugins-progress'),
          $error: $('.jupiterx-welcome-rplugins-error'),
          $pluginAction: $('.jupiterx-welcome-rplugins-action'),
          $actionBtn: $('.jupiterx-welcome-rplugins-action-btn')
        };
      }
    }, {
      key: 'events',
      value: function events() {
        var _this = this;

        $(document).on('click', '.jupiterx-welcome-rplugins-action-btn', function () {
          return _this.installPlugins();
        });
      }
    }, {
      key: 'installPlugins',
      value: function installPlugins() {
        var _this2 = this;

        var bulkActions = this.state.bulkActions;

        bulkActions = this.getCheckedPlugins(bulkActions, 'install');

        this.el.$actionBtn.addClass('installing').attr('disabled', 'disabled').find('span:last-child').text(jupiterxWelcome.i18n.installText + '...');

        $.ajax({
          url: bulkActions.install_required_plugins.url,
          method: 'POST',
          data: bulkActions.install_required_plugins,
          success: function success() {
            _this2.el.$actionBtn.removeClass('installing').removeAttr('disabled').find('span:last-child').text(jupiterxWelcome.i18n.defaultText);
            _this2.activatePlugins();
          },
          error: function error() {
            _this2.el.$actionBtn.removeClass('installing').removeAttr('disabled').find('span:last-child').text(jupiterxWelcome.i18n.defaultText);

            _this2.el.$pluginInactivatedList.find('.welcome-plugin-error div').append(jupiterxWelcome.i18n.failedInstallText).append(jupiterxWelcome.i18n.failedActionLinks);

            _this2.el.$pluginInactivatedList.find('.welcome-plugin-error').show();

            _this2.el.$pluginInactivatedList.show();
          }
        });
      }
    }, {
      key: 'activatePlugins',
      value: function activatePlugins() {
        var _this3 = this;

        var bulkActions = this.state.bulkActions;

        bulkActions = this.getCheckedPlugins(bulkActions, 'active');

        this.el.$actionBtn.addClass('activating').attr('disabled', 'disabled').find('span:last-child').text(jupiterxWelcome.i18n.activateText + '...');

        $.ajax({
          url: bulkActions.activate_required_plugins.url,
          method: 'POST',
          data: bulkActions.activate_required_plugins,
          success: function success() {
            _this3.el.$actionBtn.removeClass('activating').removeAttr('disabled').find('span:last-child').text(jupiterxWelcome.i18n.redirecting + '...');

            window.location.href = jupiterxWelcome.controlPanelUrl;
          },
          error: function error() {
            _this3.el.$actionBtn.removeClass('activating').removeAttr('disabled').find('span:last-child').text(jupiterxWelcome.i18n.defaultText);

            _this3.el.$pluginInactivatedList.find('.welcome-plugin-error div').append(jupiterxWelcome.i18n.failedActivateText).append(jupiterxWelcome.i18n.failedActionLinks);

            _this3.el.$pluginInactivatedList.find('.welcome-plugin-error').show();

            _this3.el.$pluginInactivatedList.show();
          }
        });
      }
    }, {
      key: 'fetchInactiveRequiredPlugins',
      value: function fetchInactiveRequiredPlugins() {
        var _this4 = this;

        // Run only if the page is welcome page.
        if (this.el.$pluginList.length === 0) return;

        this.toggleProgress(true);

        wp.ajax.post('jupiterx_get_plugins', {
          '_ajax_nonce': this.el.$pluginList.data('nonce')
        }).done(function (data) {
          if (!data || !data.bulk_actions || data.bulk_actions.length === 0) {
            _this4.toggleProgress(false);
            _this4.togglePlugins(false);
            _this4.toggleError(true);

            return;
          }

          if (!data || !data.plugins || data.plugins.length === 0) {
            _this4.toggleProgress(false);
            _this4.togglePlugins(false);
            _this4.toggleError(true);

            return;
          }

          _this4.state.bulkActions = data.bulk_actions;

          _this4.insertPlugins(data.plugins);

          _this4.toggleProgress(false);
          _this4.toggleError(false);
          _this4.togglePlugins(true);
        }).fail(function () {
          _this4.toggleProgress(false);
          _this4.togglePlugins(false);
          _this4.toggleError(true);
        });
      }
    }, {
      key: 'insertPlugins',
      value: function insertPlugins(plugins) {
        this.el.$pluginList.html('');

        var _iteratorNormalCompletion = true;
        var _didIteratorError = false;
        var _iteratorError = undefined;

        try {
          for (var _iterator = plugins[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
            var plugin = _step.value;

            if (!jupiterxWelcome.isPremium && ['sellkit', 'sellkit-pro'].includes(plugin.slug)) {
              return;
            }

            this.el.$pluginList.append(this.preparePluginTemplate(plugin));
          }
        } catch (err) {
          _didIteratorError = true;
          _iteratorError = err;
        } finally {
          try {
            if (!_iteratorNormalCompletion && _iterator.return) {
              _iterator.return();
            }
          } finally {
            if (_didIteratorError) {
              throw _iteratorError;
            }
          }
        }
      }
    }, {
      key: 'toggleError',
      value: function toggleError(show) {
        if (show) {
          this.el.$error.css('display', 'flex');

          return;
        }

        this.el.$error.css('display', 'none');
      }
    }, {
      key: 'toggleProgress',
      value: function toggleProgress(show) {
        if (show) {
          this.el.$progress.css('display', 'flex');

          return;
        }

        this.el.$progress.css('display', 'none');
      }
    }, {
      key: 'togglePlugins',
      value: function togglePlugins(show) {
        if (show) {
          this.el.$pluginList.css('display', 'flex');
          this.el.$pluginAction.css('display', 'flex');

          return;
        }

        this.el.$pluginList.css('display', 'none');
        this.el.$pluginAction.css('display', 'none');
      }
    }, {
      key: 'preparePluginTemplate',
      value: function preparePluginTemplate(plugin) {
        var jupiterxCoreClass = 'jupiterx-welcome-plugin-' + plugin.slug;

        return '\n        <div class="jupiterx-welcome-rplugins-list-item ' + jupiterxCoreClass + '">\n          <div class="jupiterx-welcome-rplugins-list-item-header">\n            <img\n              src="' + plugin.img_url + '"\n              alt="' + plugin.name + '" />\n            <label class="jupiterx-welcome-rplugins-toggle">\n              <input type="checkbox" data-basename="' + plugin.basename + '" data-slug="' + plugin.slug + '" checked>\n              <span></span>\n            </label>\n          </div>\n          <div class="jupiterx-welcome-rplugins-list-item-body">\n            <div class="jupiterx-welcome-rplugins-list-item-title">\n              ' + plugin.name + '\n            </div>\n            <div class="jupiterx-welcome-rplugins-list-item-desc">\n              ' + plugin.desc + '\n            </div>\n          </div>\n        </div>\n      ';
      }
    }, {
      key: 'getCheckedPlugins',
      value: function getCheckedPlugins(bulkActions, $action) {
        var checkedPlugins = $('.jupiterx-welcome-rplugins-toggle input[type=checkbox]:checked');
        var finalPlugins = [];

        $.each(checkedPlugins, function (index, checked) {
          if ('install' === $action) {
            finalPlugins.push($(checked).data('slug'));
          }

          if ('active' === $action) {
            finalPlugins.push($(checked).data('basename'));
          }
        });

        if ('install' === $action) {
          if (!finalPlugins.includes('sellkit-pro')) {
            finalPlugins = finalPlugins.filter(function (item) {
              return item !== 'sellkit';
            });
          }

          bulkActions.install_required_plugins.plugin = finalPlugins;
        }

        if ('active' === $action) {
          if (!finalPlugins.includes('sellkit-pro/sellkit-pro.php')) {
            finalPlugins = finalPlugins.filter(function (item) {
              return item !== 'sellkit/sellkit.php';
            });
          }

          bulkActions.activate_required_plugins.checked = finalPlugins;
        }

        return bulkActions;
      }
    }]);

    return Welcome;
  }();

  new Welcome();
})(jQuery, wp);