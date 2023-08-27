'use strict';

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

(function ($, jupiterx) {
  var SectionHome = function () {
    function SectionHome() {
      _classCallCheck(this, SectionHome);

      this.events();
    }

    _createClass(SectionHome, [{
      key: 'events',
      value: function events() {
        var popoverEvent = function popoverEvent() {
          $('[data-toggle="tooltip"]').tooltip();

          $('[data-toggle="popover"]').click(function (event) {
            event.preventDefault();
          });

          $('[data-toggle="popover"]').popover({
            trigger: 'focus',
            container: '.jupiterx.jupiterx-modal',
            html: true
          });
        };

        // Setup wizard notice.
        $('.jupiterx-setup-wizard-hide-notice').on('click', function (event) {
          event.preventDefault();
          $(this).attr('disabled', 'disabled');

          $.ajax({
            type: 'POST',
            url: _wpUtilSettings.ajax.url,
            data: {
              action: 'jupiterx_setup_wizard_hide_notice'
            },
            beforeSend: function beforeSend() {
              $('.jupiterx-setup-wizard-message').fadeOut(400);
            }
          });
        });

        // Switch license registration type.
        $(document).off().on('click', '#jupiterx-api-key-switch', function (event) {
          event.preventDefault();
          var $this = $(this);
          var modal = $('#jupiterx-modal');

          if ('api' === $this.data('activation-mode')) {
            modal.find('.jupiterx-purchase-code-mode-element').addClass('d-block').removeClass('d-none');
            modal.find('.jupiterx-api-mode-element').addClass('d-none').removeClass('d-block d-flex');
            modal.find('#jupiterx-cp-gdpr-option-wrapper, #jupiterx-cp-mailing-list-option-wrapper').addClass('d-flex').removeClass('d-none');
            $this.data('activation-mode', 'purchase-code');
            $this.text(jupiterx_cp_textdomain.license_manager_add_api);
            popoverEvent();
          } else {
            modal.find('.jupiterx-api-mode-element').addClass('d-block').removeClass('d-none');
            modal.find('.jupiterx-purchase-code-mode-element').addClass('d-none').removeClass('d-block d-flex');
            $this.data('activation-mode', 'api');
            $this.text(jupiterx_cp_textdomain.license_manager_insert_purchase_code);
            popoverEvent();
          }
        });

        // License registration.
        $('#js__regiser-api-key-btn').on('click', function (event) {
          var _jupiterx_modal;

          event.preventDefault();

          jupiterx_modal((_jupiterx_modal = {
            title: jupiterx_cp_textdomain.registering_theme,
            type: '',
            cancelButtonText: jupiterx_cp_textdomain.discard,
            showCancelButton: true,
            showConfirmButton: true,
            showCloseButton: true,
            confirmButtonText: jupiterx_cp_textdomain.submit
          }, _defineProperty(_jupiterx_modal, 'cancelButtonText', jupiterx_cp_textdomain.cancel), _defineProperty(_jupiterx_modal, 'closeOnConfirm', false), _defineProperty(_jupiterx_modal, 'text', $(wp.template('jupiterx-cp-registration')())), _defineProperty(_jupiterx_modal, 'onConfirm', function onConfirm() {
            if ('api' === $('#jupiterx-api-key-switch').data('activation-mode')) {
              var $api_key = $('#jupiterx-cp-register-api-input').val();

              if ($api_key.length === 0) {
                return false;
              }

              var data = {
                action: 'jupiterx_cp_register_revoke_api_action',
                method: 'register',
                api_key: $api_key,
                security: $('#security').val()
              };

              jupiterx_modal({
                type: '',
                title: jupiterx_cp_textdomain.license_manager_registration_title,
                text: jupiterx_cp_textdomain.wait_for_api_key_registered,
                cancelButtonText: jupiterx_cp_textdomain.discard,
                showCancelButton: false,
                showConfirmButton: false,
                showCloseButton: false,
                showLearnmoreButton: false,
                showProgress: true,
                indefiniteProgress: true,
                progress: '100%'
              });

              $.post(_wpUtilSettings.ajax.url, data, function (res) {
                res = JSON.parse(res);

                if (res.status === true) {
                  var _data = res.data || {};

                  if (_data.status) {
                    jupiterx_modal({
                      title: jupiterx_cp_textdomain.thanks_registering,
                      text: res.message,
                      type: 'success',
                      showCancelButton: false,
                      showConfirmButton: true,
                      showCloseButton: false,
                      showLearnmoreButton: false,
                      showProgress: false,
                      indefiniteProgress: true,
                      closeOnOutsideClick: false,
                      closeOnConfirm: false,
                      onConfirm: function onConfirm() {
                        window.location.reload();
                      }
                    });

                    $('.jupiterx-wrap').removeClass('jupiterx-call-to-register-product');
                    $('.get-api-key-form').addClass('d-none');
                    $('.remove-api-key-form').removeClass('d-none');

                    // jupiterx_reinit_events();
                  } else {
                    jupiterx_modal({
                      title: jupiterx_cp_textdomain.registeration_unsuccessful,
                      text: res.message,
                      type: 'error',
                      showCancelButton: false,
                      showConfirmButton: true,
                      showCloseButton: false,
                      showLearnmoreButton: false,
                      showProgress: false,
                      onConfirm: function onConfirm() {
                        $('#jupiterx-cp-register-api-input').val('');
                      }
                    });
                  }
                } else {
                  jupiterx_modal({
                    title: jupiterx_cp_textdomain.registeration_unsuccessful,
                    text: res.message,
                    type: 'error',
                    showCancelButton: false,
                    showConfirmButton: true,
                    showCloseButton: false,
                    showLearnmoreButton: false,
                    showProgress: false,
                    onConfirm: function onConfirm() {
                      $('#jupiterx-cp-register-api-input').val('');
                    }
                  });
                }
              });
            } else {
              var purchase_code = $('#jupiterx-cp-register-purchase-code-input').val();
              var email = $('#jupiterx-cp-register-email').val();

              if (purchase_code.length === 0 || email.length === 0) {
                return false;
              }

              if (!$('#jupiterx-cp-register-gdpr').prop('checked')) {
                $('#jupiterx-cp-register-gdpr').addClass('is-invalid');
                return false;
              }

              $('#jupiterx-cp-register-gdpr').removeClass('is-invalid');

              var _data2 = {
                action: 'jupiterx_register_license',
                purchase_code: purchase_code,
                nonce: $('#license-manager-nonce').val(),
                email: $('#jupiterx-cp-register-email').val(),
                accept_mail_list: $('#jupiterx-cp-register-mailing-list').prop('checked') ? 'on' : 'off'
              };

              jupiterx_modal({
                type: '',
                title: jupiterx_cp_textdomain.license_manager_registration_title,
                text: jupiterx_cp_textdomain.wait_for_api_key_registered,
                cancelButtonText: jupiterx_cp_textdomain.discard,
                showCancelButton: false,
                showConfirmButton: false,
                showCloseButton: false,
                showLearnmoreButton: false,
                showProgress: true,
                indefiniteProgress: true,
                progress: '100%'
              });

              $.post(_wpUtilSettings.ajax.url, _data2, function (res) {
                if ('valid_api' === res.data.code) {
                  // For supporters.
                  console.log('Validating API key ...');

                  var data = {
                    action: 'jupiterx_cp_register_revoke_api_action',
                    method: 'register',
                    api_key: purchase_code,
                    security: $('#security').val()
                  };

                  $.post(_wpUtilSettings.ajax.url, data, function (res) {
                    res = JSON.parse(res);

                    if (res.status === true) {
                      var _data3 = res.data || {};

                      if (_data3.status) {
                        jupiterx_modal({
                          title: jupiterx_cp_textdomain.thanks_registering,
                          text: res.message,
                          type: 'success',
                          showCancelButton: false,
                          showConfirmButton: true,
                          showCloseButton: false,
                          showLearnmoreButton: false,
                          showProgress: false,
                          indefiniteProgress: true,
                          closeOnOutsideClick: false,
                          closeOnConfirm: false,
                          onConfirm: function onConfirm() {
                            window.location.reload();
                          }
                        });

                        $('.jupiterx-wrap').removeClass('jupiterx-call-to-register-product');
                        $('.get-api-key-form').addClass('d-none');
                        $('.remove-api-key-form').removeClass('d-none');

                        // jupiterx_reinit_events();
                      } else {
                        jupiterx_modal({
                          title: jupiterx_cp_textdomain.registeration_unsuccessful,
                          text: res.message,
                          type: 'error',
                          showCancelButton: false,
                          showConfirmButton: true,
                          showCloseButton: false,
                          showLearnmoreButton: false,
                          showProgress: false,
                          onConfirm: function onConfirm() {
                            $('#jupiterx-cp-register-api-input').val('');
                          }
                        });
                      }
                    } else {
                      jupiterx_modal({
                        title: jupiterx_cp_textdomain.registeration_unsuccessful,
                        text: res.message,
                        type: 'error',
                        showCancelButton: false,
                        showConfirmButton: true,
                        showCloseButton: false,
                        showLearnmoreButton: false,
                        showProgress: false,
                        onConfirm: function onConfirm() {
                          $('#jupiterx-cp-register-api-input').val('');
                        }
                      });
                    }
                  });
                } else if (res.success === true) {
                  var _data4 = res.data || {};

                  if (_data4.status) {
                    jupiterx_modal({
                      title: jupiterx_cp_textdomain.thanks_registering,
                      text: _data4.message,
                      type: 'success',
                      showCancelButton: false,
                      showConfirmButton: true,
                      showCloseButton: false,
                      showLearnmoreButton: false,
                      showProgress: false,
                      indefiniteProgress: true,
                      closeOnOutsideClick: false,
                      closeOnConfirm: false,
                      onConfirm: function onConfirm() {
                        window.location.reload();
                      }
                    });

                    $('.jupiterx-wrap').removeClass('jupiterx-call-to-register-product');
                    $('.get-api-key-form').addClass('d-none');
                    $('.remove-api-key-form').removeClass('d-none');

                    // jupiterx_reinit_events();
                  } else {
                    jupiterx_modal({
                      title: jupiterx_cp_textdomain.registeration_unsuccessful,
                      text: res.message,
                      type: 'error',
                      showCancelButton: false,
                      showConfirmButton: true,
                      showCloseButton: false,
                      showLearnmoreButton: false,
                      showProgress: false,
                      onConfirm: function onConfirm() {
                        $('#jupiterx-cp-register-api-input').val('');
                        window.location.reload();
                      }
                    });
                  }
                } else {
                  jupiterx_modal({
                    title: jupiterx_cp_textdomain.registeration_unsuccessful,
                    text: res.data.message,
                    type: 'error',
                    showCancelButton: false,
                    showConfirmButton: true,
                    showCloseButton: false,
                    showLearnmoreButton: false,
                    showProgress: false,
                    onConfirm: function onConfirm() {
                      $('#jupiterx-cp-register-api-input').val('');
                    }
                  });
                }
              });
            }
          }), _jupiterx_modal));

          popoverEvent();
        });

        // License revoke.
        $('#js__revoke-api-key-btn').on('click', function (event) {
          event.preventDefault();
          var revokingMode = $(this).data('revoking-mode') || 'api';

          jupiterx_modal({
            title: jupiterx_cp_textdomain.revoke_API_key,
            text: jupiterx_cp_textdomain.you_are_about_to_remove_API_key,
            type: 'warning',
            showCancelButton: true,
            showConfirmButton: true,
            showLearnmoreButton: false,
            confirmButtonText: jupiterx_cp_textdomain.ok,
            cancelButtonText: jupiterx_cp_textdomain.cancel,
            closeOnConfirm: false,
            onConfirm: function onConfirm() {
              if ('api' === revokingMode) {
                var data = {
                  action: 'jupiterx_cp_register_revoke_api_action',
                  method: 'revoke',
                  security: $('#security').val()
                };

                $.post(_wpUtilSettings.ajax.url, data, function (res) {
                  res = JSON.parse(res);

                  if (res.status === true) {
                    window.location.reload();
                  }
                });
              } else {
                var data = {
                  action: 'jupiterx_revoke_license',
                  nonce: $('#license-manager-nonce').val()
                };

                jupiterx_modal({
                  type: '',
                  title: jupiterx_cp_textdomain.license_manager_revoking_title,
                  text: jupiterx_cp_textdomain.wait_for_api_key_revoke,
                  cancelButtonText: jupiterx_cp_textdomain.discard,
                  showCancelButton: false,
                  showConfirmButton: false,
                  showCloseButton: false,
                  showLearnmoreButton: false,
                  showProgress: true,
                  indefiniteProgress: true,
                  progress: '100%'
                });

                $.post(_wpUtilSettings.ajax.url, data, function (res) {
                  if (res.success === true) {
                    window.location.reload();
                  } else {
                    jupiterx_modal({
                      title: jupiterx_cp_textdomain.license_manager_revoking_error,
                      text: res.data.message,
                      type: 'error',
                      showCancelButton: false,
                      showConfirmButton: true,
                      showCloseButton: false,
                      showLearnmoreButton: false,
                      showProgress: false,
                      onConfirm: function onConfirm() {
                        window.location.reload();
                      }
                    });
                  }
                });
              }
            }
          });
        });
      }
    }]);

    return SectionHome;
  }();

  var SectionTemplates = function () {
    function SectionTemplates() {
      _classCallCheck(this, SectionTemplates);

      this.init();
      this.events();
    }

    _createClass(SectionTemplates, [{
      key: 'init',
      value: function init() {
        if (jupiterx.templates) {
          jupiterx.templates.init({
            customImport: true
          });
        }

        this.templateInstalled();
        this.restoreButton();
      }
    }, {
      key: 'events',
      value: function events() {
        var self = this;

        $(document).on('click', '#js__cp_template_uninstall', function (event) {
          event.preventDefault();
          var $this = $(this);

          jupiterx_modal({
            title: jupiterx_cp_textdomain.important_notice,
            text: jupiterx_cp_textdomain.uninstalling_template_will_remove_all_your_contents_and_settings,
            type: 'warning',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: jupiterx_cp_textdomain.yes_uninstall + $this.data('title'),
            showCloseButton: false,
            showLearnmoreButton: false,
            onConfirm: function onConfirm() {
              self.uninstallTemplate();
            }
          });
        });

        $(document).on('click', '#js__restore-template-btn', function (event) {
          event.preventDefault();
          self.restoreBackup();
        });

        $(window).on('template-installed', function (event, template) {
          if (!template.partial) {
            self.templateInstalled(template.title, template.id);
            self.restoreButton();
          }
        });
      }
    }, {
      key: 'uninstallTemplate',
      value: function uninstallTemplate() {
        jupiterx_modal({
          title: jupiterx_cp_textdomain.uninstalling_Template,
          text: jupiterx_cp_textdomain.please_wait_for_few_moments,
          type: '',
          showCancelButton: false,
          showConfirmButton: false,
          showCloseButton: false,
          showLearnmoreButton: false,
          showProgress: true,
          progress: '100%'
        });

        // requestsPending = 1;
        $.post(_wpUtilSettings.ajax.url, {
          action: 'abb_uninstall_template'
        }).done(function () {
          $('#js__installed-template-wrap').hide();

          jupiterx_modal({
            title: jupiterx_cp_textdomain.hooray,
            text: jupiterx_cp_textdomain.template_uninstalled,
            type: 'success',
            showCancelButton: false,
            showConfirmButton: true,
            showCloseButton: false,
            showLearnmoreButton: false
          });

          jupiterxTemplates.template = null;
          // requestsPending = false;
        }).fail(function (data) {
          console.log('Failed msg : ', data);
          // requestsPending = false;
        });
      }
    }, {
      key: 'templateInstalled',
      value: function templateInstalled(slug, id) {
        var self = this;
        var template = $('#js__installed-template');

        if (!template.length) {
          return;
        }

        if (!slug) {
          slug = template.data('installed-template');
        }

        if (!id) {
          id = template.data('installed-template-id');
        }

        if (slug <= 0 && id <= 0) {
          return;
        }

        var data = {
          action: 'abb_template_lazy_load',
          from: 0,
          count: 1,
          template_id: id,
          template_name: slug
        };

        $.post(_wpUtilSettings.ajax.url, data, function (res) {
          if (res.status === true && res.data.templates.length > 0) {
            $.each(res.data.templates, function (key, val) {
              $('#js__installed-template-wrap').show();
              template.attr('data-installed-template-id', val.id).attr('data-installed-template', val.slug).empty().append(self.templateUI(val));
            });
          }
        });
      }
    }, {
      key: 'templateUI',
      value: function templateUI(data) {
        return '\n        <div class="jupiterx-cp-template-item">\n          <div class="jupiterx-cp-template-item-inner jupiterx-card">\n            <figure class="jupiterx-cp-template-item-fig">\n              <img src="' + data.img_url + '" alt="' + data.name + '">\n            </figure>\n            <div class="jupiterx-cp-template-item-meta jupiterx-card-body">\n              <h4 class="jupiterx-cp-template-item-name text-truncate" title="' + data.name.replace(' Jupiterx', '') + '">' + data.name.replace(' Jupiterx', '') + '</h4>\n              <div class="jupiterx-cp-template-item-buttons ' + (data.psd_file ? ' has-psd' : '') + '">\n                <a id="js__cp_template_uninstall" class="btn btn-outline-danger mr-2 jupiterx-cp-template-item-btn" href="#" data-title="' + data.name.replace(' Jupiterx', '') + '" data-name="' + data.name + '" data-slug="' + data.slug + '" data-id="' + data.id + '">' + jupiterx_cp_textdomain.remove + '</a>\n                <a class="btn btn-outline-secondary mr-2 jupiterx-cp-template-item-btn" href="https://jupiterx.artbees.net/' + data.slug.replace('-jupiterx', '') + '" target="_blank">' + jupiterx_cp_textdomain.preview + '</a>\n              </div>\n            </div>\n          </div>\n        </div>\n      ';
      }
    }, {
      key: 'restoreButton',
      value: function restoreButton() {
        if (!jupiterxControlPanel.jupiterxCoreActive) {
          return;
        }

        var data = {
          action: 'abb_is_restore_db'
        };

        $.ajax({
          type: 'POST',
          url: _wpUtilSettings.ajax.url,
          data: data,
          dataType: 'json',
          success: function success(res) {
            var data = res.data;
            var backups = [];
            var latestBackup = null;
            var createdDate = '';

            if (data.hasOwnProperty('list_of_backups')) {
              backups = data.list_of_backups;

              if (backups === null) {
                console.log('List Of Backups is NULL!');
              } else if (backups.length === 0) {
                console.log('List Of Backups is EMPTY!');
              } else {
                latestBackup = data.latest_backup_file;
                createdDate = latestBackup.created_date;
                $('#js__backup-date').text(createdDate);
                $('#js__restore-template-wrap').addClass('is-active');
                console.log('Restore Buttons Created Successfully!');
              }
            } else {
              console.log('No backup files found!');
            }
          },
          error: function error(req, status, _error) {
            console.log('Fail: ', req);
          }
        });
      }
    }, {
      key: 'restoreBackup',
      value: function restoreBackup() {
        $.ajax({
          type: 'POST',
          url: _wpUtilSettings.ajax.url,
          data: {
            action: 'abb_is_restore_db'
          },
          dataType: 'json',
          success: function success(res) {
            var createdDate = res.data.latest_backup_file.created_date;

            jupiterx_modal({
              title: jupiterx_cp_textdomain.restore_settings,
              text: '<p>' + jupiterx_cp_textdomain.you_are_trying_to_restore_your_theme_settings_to_this_date + '<strong class=\'jupiterx-tooltip-restore--created-date\'>' + createdDate + '</strong>. ' + jupiterx_cp_textdomain.are_you_sure + '</p>',
              type: 'warning',
              showCancelButton: true,
              showConfirmButton: true,
              confirmButtonText: jupiterx_cp_textdomain.restore,
              showCloseButton: false,
              showLearnmoreButton: false,
              onConfirm: function onConfirm() {
                jupiterx_modal({
                  title: jupiterx_cp_textdomain.restoring_database,
                  text: jupiterx_cp_textdomain.please_wait_for_few_moments,
                  type: '',
                  showCancelButton: false,
                  showConfirmButton: false,
                  showCloseButton: false,
                  showLearnmoreButton: false,
                  progress: '100%',
                  showProgress: true,
                  indefiniteProgress: true
                });

                $.ajax({
                  type: "POST",
                  url: _wpUtilSettings.ajax.url,
                  data: {
                    action: 'abb_restore_latest_db'
                  },
                  dataType: "json",
                  success: function success(res) {
                    if (res.status) {
                      jupiterx_modal({
                        title: res.message,
                        text: jupiterx_cp_textdomain.restore_ok,
                        type: 'success',
                        showCancelButton: false,
                        showConfirmButton: true,
                        showCloseButton: false,
                        showLearnmoreButton: false,
                        showProgress: false,
                        indefiniteProgress: true,
                        confirmButtonText: jupiterx_cp_textdomain.reload_page,
                        onConfirm: function onConfirm() {
                          location.reload();
                        }
                      });
                    } else {
                      jupiterx_modal({
                        title: jupiterx_cp_textdomain.something_went_wrong,
                        text: res.message,
                        type: 'error',
                        showCancelButton: false,
                        showConfirmButton: true,
                        showLearnmoreButton: false
                      });
                    }
                  },

                  error: function error(req, status, _error2) {
                    console.log('Fail: ', req);
                  }
                });
              }
            });
          },
          error: function error(req, status, _error3) {
            console.log('Fail: ', req);
          }
        });
      }
    }]);

    return SectionTemplates;
  }();

  var SectionSettings = function () {
    function SectionSettings() {
      _classCallCheck(this, SectionSettings);

      this.whiteLabelModeChanged = false;
      this.events();
    }

    _createClass(SectionSettings, [{
      key: 'events',
      value: function events() {
        var self = this;

        $('.jupiterx-cp-settings-flush').on('click', function () {
          self.send('flush');
        });

        $('.jupiterx-cp-settings-form').on('submit', function (event) {
          event.preventDefault();
          var form = $(this);
          var fields = {};

          $.map(form.serializeArray(), function (v) {
            var name = v.name;
            var value = v.value;

            if (v.name.endsWith('[]')) {
              name = name.replace('[]', '');
              value = fields[name] || [];

              if (v.value) {
                value.push(v.value);
              }
            }

            fields[name] = value;
          });

          self.send('save', fields);
        });

        $('.jupiterx-image-uploader').each(function (i, node) {
          var element = $(node);
          var input = element.find('input');
          var del = element.find('.remove-button');
          var frame = wp.media({
            multiple: false,
            title: jupiterx_cp_textdomain.select_zip_file,
            button: {
              text: jupiterx_cp_textdomain.select
            }
          });

          frame.on('select', function () {
            var attachment = frame.state().get('selection').first().toJSON();
            input.val(attachment.url);
            element.addClass('has-image');
          });

          element.on('click', 'input, .upload-button', function () {
            event.preventDefault();
            if (frame) {
              frame.open();
              return;
            }

            frame.open();
          });

          del.on('click', function (event) {
            event.preventDefault();
            input.val('');
            element.removeClass('has-image');
          });
        });

        $('[data-for]').each(function (i, node) {
          var element = $(node);
          var input = $('input[type=checkbox][name=' + element.data('for') + ']');

          input.on('change', function () {
            element.toggleClass('hidden', !input.is(':checked'));
          });
        });

        $('#jupiterx-cp-settings-white-label-mode').on('change', function () {
          self.whiteLabelModeChanged = true;
        });
      }
    }, {
      key: 'send',
      value: function send(type) {
        var fields = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

        var self = this;
        var feedback = $('.jupiterx-cp-settings-' + type + '-feedback');
        var originalText = feedback.text();
        var revertFeedback = function revertFeedback() {
          setTimeout(function () {
            feedback.addClass('d-none text-muted').text(originalText);
          }, 3000);
        };

        // Show feedback.
        feedback.removeClass('d-none');

        wp.ajax.send('jupiterx_cp_settings', {
          data: {
            nonce: jupiterxControlPanel.nonce,
            type: type,
            fields: fields
          },
          success: function success(res) {
            feedback.removeClass('text-muted').addClass('text-success').text(res);
            revertFeedback();

            if (!fields) {
              return;
            }

            if (fields.jupiterx_white_label === '1' || self.whiteLabelModeChanged && fields.jupiterx_white_label === '0') {
              window.location.reload();
            }
          },
          error: function error(res) {
            feedback.removeClass('text-muted').addClass('text-danger').text(res);
            revertFeedback();
          }
        });
      }
    }]);

    return SectionSettings;
  }();

  var SectionSystemStatus = function () {
    function SectionSystemStatus() {
      _classCallCheck(this, SectionSystemStatus);

      this.events();
    }

    _createClass(SectionSystemStatus, [{
      key: 'events',
      value: function events() {
        var self = this;

        $('#jupiterx-mods-cleanup').on('click', function (event) {
          event.preventDefault();
          var $this = $(this);
          var data = {
            action: 'jupiterx_cp_cleanup_mods',
            nonce: $this.attr('data-nonce')
          };

          $this.replaceWith('\n          <span class="status-state">\n            <span class="jupiterx-cleanup-spinner spinner is-active"></span>\n          </span>\n        ');

          self.cleanupThemeMods(data);
        });

        $('.jupiterx-button--get-system-report').click(function () {
          var report = '';

          $('#jupiterx-cp-system-status thead, #jupiterx-cp-system-status tbody').each(function () {
            var $this = $(this);

            if ($this.is('thead')) {
              var label = $this.find('th:eq(0)').data('export-label') || $this.text();
              report = report + "\n### " + $.trim(label) + " ###\n\n";
            } else {
              $('tr', $this).each(function () {
                var $this = $(this);
                var label = $this.find('td:eq(0)').data('export-label') || $this.find('td:eq(0)').text();
                var name = $.trim(label).replace(/(<([^>]+)>)/ig, '');

                var value = $.trim($this.find('td:eq(2)').text().replace(/(\r\n\t|\n|\r|\t)/gm, ''));
                var valArr = value.split(', ');

                if (valArr.length > 1) {
                  var tempLine = '';

                  $.each(valArr, function (key, line) {
                    tempLine = tempLine + line + '\n';
                  });

                  value = tempLine;
                }

                report = report + '' + name + ': ' + value + "\n";
              });
            }
          });

          try {
            $('#jupiterx-textarea--get-system-report').slideDown();
            $('#jupiterx-textarea--get-system-report textarea').val(report).focus().select();
            return false;
          } catch (e) {
            console.log(e);
          }

          return false;
        });

        $('[data-jupiterx-ajax]').each(function () {
          var $this = $(this);
          var type = $this.data('jupiterxAjax');
          var feedbackIcon = $this.find('.status-state');
          var feedbackText = $this.find('.status-text');

          wp.ajax.send('jupiterx_cp_system_status', {
            data: {
              nonce: jupiterxControlPanel.nonce,
              type: type
            },
            success: function success() {
              feedbackIcon.html('<span class="status-invisible">True</span><span class="status-state status-true"></span>');
            },
            error: function error(res) {
              feedbackIcon.html('<span class="status-invisible">False</span><span class="status-state status-false"></span>');
              feedbackText.html(res);
            }
          });
        });
      }
    }, {
      key: 'cleanupThemeMods',
      value: function cleanupThemeMods(data) {
        var self = this;

        $.post(_wpUtilSettings.ajax.url, data, function (res) {
          if (res.success) {
            self.cleanupThemeMods(data);
          } else {
            $('.jupiterx-cleanup-spinner').replaceWith('\n            <span class="status-state">\n              <span class="status-invisible">True</span>\n              <span class="status-state status-true"></span>\n            </span>\n          ');
          }
        });
      }
    }]);

    return SectionSystemStatus;
  }();

  var SectionUpdates = function () {
    function SectionUpdates() {
      _classCallCheck(this, SectionUpdates);

      this.events();
    }

    _createClass(SectionUpdates, [{
      key: 'events',
      value: function events() {
        var self = this;

        $(document).on('click', '.js__cp_change_theme_version', this.updateTheme);
        $(document).on('click', '.release-download', this.releaseDownload);
      }
    }, {
      key: 'releaseDownload',
      value: function releaseDownload(event) {
        event.preventDefault();
        event.stopPropagation();

        var $this = $(this);
        var status = $this.attr('status') || 'active';

        if (status === 'active') {
          var releaseId = $this.data('release-id');
          var releasePackage = $this.data('release-package');
          var nonce = $this.data('nonce');

          $this.attr('status', 'deactive');

          setTimeout(function () {
            $this.attr('status', 'active');
          }, 9000);

          jQuery.ajax({
            url: _wpUtilSettings.ajax.url,
            type: 'POST',
            data: {
              security: nonce,
              release_id: releaseId,
              release_package: releasePackage,
              action: 'jupiterx_get_theme_release_package_url'
            },
            success: function success(res) {
              if (res.success) {
                top.location.href = res.data;
              }
            },
            error: function error(res) {
              console.log(res);
              alert('An error occurred.');
            }
          });
        }
      }
    }, {
      key: 'updateTheme',
      value: function updateTheme(event) {
        event.preventDefault();

        var $this = $(this);
        var releaseId = $this.data('release-id');
        var releaseVersion = $this.data('release-version');
        var nonce = $this.data('nonce');
        var feedback = $this.siblings('.jupiterx-cp-update-feedback');

        jupiterx_modal({
          title: jupiterx_cp_textdomain.please_note,
          text: jupiterx_cp_textdomain.any_customisation_you_have_made_to_theme_files_will_be_lost,
          type: 'warning',
          showCancelButton: true,
          showConfirmButton: true,
          confirmButtonText: jupiterx_cp_textdomain.agree,
          cancelButtonText: jupiterx_cp_textdomain.discard,
          showCloseButton: true,
          showLearnmoreButton: false,
          onConfirm: function onConfirm() {
            var errors = [{
              text: jupiterx_cp_textdomain.apikey_domain_match_error,
              helpLink: '<a href="https://themes.artbees.net/docs/updating-jupiter-x-theme-automatically/" target="_blank">' + jupiterx_cp_textdomain.learn_more + '</a>'
            }];

            var errorHelpLink = function errorHelpLink(text) {
              var error = _.findWhere(errors, { text: text });
              return error ? error.helpLink : '';
            };

            feedback.removeClass('d-none');
            $this.addClass('disabled loading');

            wp.ajax.send('jupiterx_modify_auto_update', {
              data: {
                security: nonce,
                release_id: releaseId,
                release_version: releaseVersion
              },
              success: function success() {
                wp.updates.ajax('update-theme', {
                  slug: 'jupiterx',
                  success: function success() {
                    feedback.removeClass('text-muted').addClass('text-success').text(jupiterx_cp_textdomain.theme_update_success);
                    $this.removeClass('disabled loading');
                    window.location.reload();
                  },
                  error: function error(response) {
                    var errorCode = response && response.errorCode ? response.errorCode : '';

                    if (errorCode === 'files_not_writable' || errorCode === 'remove_old_failed') {
                      feedback.removeClass('text-muted').addClass('text-danger').html(jupiterx_cp_textdomain.theme_update_failed_due_to_permission + ' <a href="https://themes.artbees.net/docs/template-cant-be-installed/" target="_blank">' + jupiterx_cp_textdomain.learn_more + '.</a>');
                    } else {
                      feedback.removeClass('text-muted').addClass('text-danger').text(jupiterx_cp_textdomain.theme_update_failed);
                    }

                    $this.removeClass('disabled loading');
                  }
                });
              },
              error: function error(res) {
                $this.removeClass('disabled loading');
                feedback.removeClass('text-muted').addClass('text-danger').html(res + ' ' + errorHelpLink(res));
              }
            });
          }
        });
      }
    }]);

    return SectionUpdates;
  }();

  var SectionImageSizes = function () {
    function SectionImageSizes() {
      _classCallCheck(this, SectionImageSizes);

      this.events();
    }

    _createClass(SectionImageSizes, [{
      key: 'events',
      value: function events() {
        var self = this;

        $('.js__cp-clist-add-item').on('click', function (event) {
          event.preventDefault();
          self.add();
        });

        $('.js__cp-clist-edit-item').on('click', function (event) {
          event.preventDefault();
          self.edit($(this));
        });

        $('.js__cp-clist-remove-item').on('click', function (event) {
          event.preventDefault();
          self.remove($(this));
        });

        $(document).on('blur', '.js__add-new-image-size input[type="number"]', function (event) {
          var value = new Number(event.target.value);

          if (isNaN(value) || value < parseInt(event.target.min)) {
            $(this).val('');
          }
        });
      }
    }, {
      key: 'add',
      value: function add() {
        var self = this;
        var html = '';
        html += '<div class="jupiterx-modal-header">';
        html += '<span class="jupiterx-modal-icon"></span>';
        html += '<h3 class="jupiterx-modal-title">' + jupiterx_cp_textdomain.add_image_size + '</h3>';
        html += '</div>';
        html += '<div class="jupiterx-modal-desc">';
        html += '<div class="form-group mb-3">';
        html += '<label><strong>' + jupiterx_cp_textdomain.image_size_name + '</strong></label>';
        html += '<input class="jupiterx-form-control" name="size_n" type="text" required />';
        html += '</div>';
        html += '<div class="form-row">';
        html += '<div class="form-group col-md-6">';
        html += '<label><strong>' + jupiterx_cp_textdomain.image_size_width + '</strong></label>';
        html += '<input class="jupiterx-form-control" min="1" name="size_w" step="1" type="number" required />';
        html += '</div>';
        html += '<div class="form-group col-md-6">';
        html += '<label><strong>' + jupiterx_cp_textdomain.image_size_height + '</strong></label>';
        html += '<input class="jupiterx-form-control" min="1" name="size_h" id="size_h" step="1" type="number" required />';
        html += '</div>';
        html += '</div>';
        html += '<div class="custom-control custom-checkbox form-group mb-3">';
        html += '<input type="checkbox" class="custom-control-input" id="size_c" name="size_c" checked="checked">';
        html += '<label class="custom-control-label" for="size_c"><strong>' + jupiterx_cp_textdomain.image_size_crop + '</strong></label>';
        html += '</div>';
        html += '</div>';

        var modal = jupiterx_modal({
          modalCustomClass: 'js__add-new-image-size',
          type: 'warning',
          html: $(html),
          showCloseButton: true,
          showConfirmButton: true,
          showCancelButton: true,
          closeOnOutsideClick: true,
          closeOnConfirm: false,
          confirmButtonText: jupiterx_cp_textdomain.save,
          cancelButtonText: jupiterx_cp_textdomain.discard,
          onConfirm: function onConfirm() {
            self.apply(false, modal);
          }
        });
      }
    }, {
      key: 'edit',
      value: function edit(element) {
        var self = this;
        var $this = element;
        var $this_size_item = $this.closest('.js__cp-image-size-item');
        var $this_box = $this.closest('.jupiterx-card-body');
        var $size_name = $this_box.find('[name=size_n]').val();
        var $size_width = $this_box.find('[name=size_w]').val();
        var $size_height = $this_box.find('[name=size_h]').val();
        var $size_crop = $this_box.find('[name=size_c]').val();
        $size_crop = $size_crop === 'on' ? 'checked="checked"' : false;

        var custom_html = '';
        custom_html += '<div class="jupiterx-modal-header">';
        custom_html += '<span class="jupiterx-modal-icon"></span>';
        custom_html += '<h3 class="jupiterx-modal-title">' + jupiterx_cp_textdomain.edit_image_size + '</h3>';
        custom_html += '</div>';
        custom_html += '<div class="jupiterx-modal-desc">';
        custom_html += '<div class="form-group mb-3">';
        custom_html += '<label><strong>' + jupiterx_cp_textdomain.image_size_name + '</strong></label>';
        custom_html += '<input class="jupiterx-form-control" name="size_n" type="text" value="' + $size_name + '" required />';
        custom_html += '</div>';
        custom_html += '<div class="form-row">';
        custom_html += '<div class="form-group col-md-6">';
        custom_html += '<label><strong>' + jupiterx_cp_textdomain.image_size_width + '</strong></label>';
        custom_html += '<input class="jupiterx-form-control" min="1" name="size_w" step="1" type="number"  value="' + $size_width + '" required />';
        custom_html += '</div>';
        custom_html += '<div class="form-group col-md-6">';
        custom_html += '<label><strong>' + jupiterx_cp_textdomain.image_size_height + '</strong></label>';
        custom_html += '<input class="jupiterx-form-control" min="1" name="size_h" id="size_h" step="1" type="number"  value="' + $size_height + '" required />';
        custom_html += '</div>';
        custom_html += '</div>';
        custom_html += '<div class="custom-control custom-checkbox form-group mb-3">';
        custom_html += '<input type="checkbox" class="custom-control-input" id="size_c" name="size_c" ' + $size_crop + '>';
        custom_html += '<label class="custom-control-label" for="size_c"><strong>' + jupiterx_cp_textdomain.image_size_crop + '</strong></label>';
        custom_html += '</div>';
        custom_html += '</div>';

        var modal = jupiterx_modal({
          modalCustomClass: 'js__add-new-image-size',
          type: 'warning',
          html: $(custom_html),
          showCloseButton: true,
          showConfirmButton: true,
          showCancelButton: true,
          closeOnOutsideClick: true,
          closeOnConfirm: false,
          confirmButtonText: jupiterx_cp_textdomain.save,
          cancelButtonText: jupiterx_cp_textdomain.discard,
          onConfirm: function onConfirm() {
            self.apply($this_size_item, modal);
          }
        });
      }
    }, {
      key: 'remove',
      value: function remove(element) {
        var self = this;
        var $this = element;

        jupiterx_modal({
          title: jupiterx_cp_textdomain.remove_image_size,
          text: jupiterx_cp_textdomain.are_you_sure_remove_image_size,
          type: 'warning',
          showCancelButton: true,
          showConfirmButton: true,
          showCloseButton: false,
          showLearnmoreButton: false,
          onConfirm: function onConfirm() {
            var $list_item = $this.closest('.jupiterx-img-size-item');
            $list_item.remove();
            self.save();
          }
        });
      }
    }, {
      key: 'apply',
      value: function apply(addSize, modal) {
        var self = this;
        var custom_html = '';
        var $modal = $('.js__add-new-image-size');
        var $size_name = $modal.find('[name=size_n]');
        var $size_width = $modal.find('[name=size_w]');
        var $size_height = $modal.find('[name=size_h]');
        var $size_name_val = $modal.find('[name=size_n]').val();
        var $size_width_val = $modal.find('[name=size_w]').val();
        var $size_height_val = $modal.find('[name=size_h]').val();
        var $size_crop = $modal.find('[name=size_c]:checked').val();

        $size_crop = $size_crop == 'on' ? 'on' : 'off';
        var crop_text = $size_crop == 'on' ? jupiterx_cp_textdomain.on : jupiterx_cp_textdomain.off;

        if ($size_name_val == '') {
          $size_name.addClass('is-invalid');
          return;
        } else {
          $size_name.removeClass('is-invalid');
        }

        if ($size_width_val == '') {
          $size_width.addClass('is-invalid');
          return;
        } else {
          $size_width.removeClass('is-invalid');
        }

        if ($size_height_val == '') {
          $size_height.addClass('is-invalid');
          return;
        } else {
          $size_height.removeClass('is-invalid');
        }

        custom_html += '<div class="jupiterx-img-size-item js__cp-image-size-item">';
        custom_html += '<div class="jupiterx-img-size-item-inner jupiterx-card">';
        custom_html += '<div class="jupiterx-card-body fetch-input-data">';
        custom_html += '<div class="js__size-name mb-3"><strong>' + jupiterx_cp_textdomain.size_name + ':</strong> ' + $size_name_val + '</div>';
        custom_html += '<div class="js__size-dimension mb-3"><strong>' + jupiterx_cp_textdomain.image_size + ':</strong> ' + $size_width_val + 'px ' + $size_height_val + 'px</div>';
        custom_html += '<div class="js__size-crop mb-3"><strong>' + jupiterx_cp_textdomain.crop + ':</strong><span> ' + crop_text + '</span></div>';
        custom_html += '<button type="button" class="btn btn-outline-success js__cp-clist-edit-item mr-1">' + jupiterx_cp_textdomain.edit + '</button>';
        custom_html += '<button type="button" class="btn btn-outline-danger js__cp-clist-remove-item">' + jupiterx_cp_textdomain.remove + '</button>';
        custom_html += '<input name="size_n" type="hidden" value="' + $size_name_val + '" />';
        custom_html += '<input name="size_w" type="hidden" value="' + $size_width_val + '" />';
        custom_html += '<input name="size_h" type="hidden" value="' + $size_height_val + '" />';
        custom_html += '<input name="size_c" type="hidden" value="' + $size_crop + '" />';
        custom_html += '</div>';
        custom_html += '</div>';

        if (addSize.length > 0) {
          addSize.after(custom_html);
          addSize.remove();
        } else {
          $('.js__jupiterx-img-size-list').append(custom_html);
        }

        modal.close();
        self.events();
        self.save();
      }
    }, {
      key: 'save',
      value: function save() {
        var $container = $('.js__jupiterx-img-size-list');
        var serialized = [];

        $container.find('.js__cp-image-size-item').each(function () {
          serialized.push($(this).find('.fetch-input-data input').serialize());
        });

        var savingImageSizes = jupiterx_modal({
          title: jupiterx_cp_textdomain.saving_image_size,
          text: jupiterx_cp_textdomain.wait_for_image_size_update,
          type: '',
          showCancelButton: false,
          showConfirmButton: false,
          showCloseButton: false,
          showLearnmoreButton: false,
          progress: '100%',
          showProgress: true,
          indefiniteProgress: true
        });

        jQuery.ajax({
          url: _wpUtilSettings.ajax.url,
          type: 'POST',
          data: {
            action: 'jupiterx_save_image_sizes',
            options: serialized,
            security: $('#security').val()
          },
          success: function success(res) {
            savingImageSizes.close();

            if (res != 1) {
              jupiterx_modal({
                title: jupiterx_cp_textdomain.something_went_wrong,
                text: jupiterx_cp_textdomain.image_sizes_could_not_be_stored,
                type: 'error',
                showCancelButton: false,
                showConfirmButton: true,
                showCloseButton: false,
                showLearnmoreButton: false
              });
            }
          },
          error: function error(res) {
            console.log(res);

            jupiterx_modal({
              type: 'error',
              title: jupiterx_cp_textdomain.error,
              text: res + ' ' + jupiterx_cp_textdomain.issue_persists,
              showCancelButton: false,
              showConfirmButton: true,
              showCloseButton: false,
              showLearnmoreButton: false,
              showProgress: false,
              closeOnConfirm: false,
              confirmButtonText: jupiterx_cp_textdomain.try_again,
              closeOnOutsideClick: false,
              onConfirm: function onConfirm() {
                window.location.reload();
              }
            });
          }
        });
      }
    }]);

    return SectionImageSizes;
  }();

  var SectionExportImport = function () {
    function SectionExportImport() {
      _classCallCheck(this, SectionExportImport);

      this.steps = [];
      this.modal = '';
      this.cancel = '';
      this.data = {};
      this.attachmentId = '';
      this.events();
    }

    _createClass(SectionExportImport, [{
      key: 'events',
      value: function events() {
        var self = this;

        $('.jupiterx-cp-export-form').on('submit', function (event) {
          event.preventDefault();
          self.export($(this));
        });

        $('.jupiterx-cp-import-btn').on('click', function (event) {
          event.preventDefault();
          self.import();
        });

        $('.jupiterx-cp-import-upload-btn').on('click', function (event) {
          event.preventDefault();
          self.upload(event);
        });
      }
    }, {
      key: 'export',
      value: function _export(element) {
        var self = this;
        self.steps = [];
        self.modal = '';
        self.cancel = '';

        var options = element.serializeArray();
        self.data.filename = options[0].value;

        // Remove filename from options.
        options = _.reject(options, function (option) {
          return option.name == 'filename';
        });

        // Convert options to a flat array.
        if (!self._mapOptions(options)) {
          return;
        }

        // Open the modal.
        self.modal = jupiterx_modal({
          type: false,
          title: jupiterx_cp_textdomain.exporting + ' <span class="cp-export-step">' + self.steps[1] + '</span>...',
          text: jupiterx_cp_textdomain.export_waiting,
          showCancelButton: true,
          showConfirmButton: false,
          showCloseButton: false,
          showLearnmoreButton: false,
          showProgress: true,
          progress: '100%',
          indefiniteProgress: true,
          cancelButtonText: jupiterx_cp_textdomain.discard,
          closeOnConfirm: false,
          closeOnOutsideClick: false,
          onCancel: function onCancel() {
            self.steps = [];
            self.cancel = true;
            self.send('Export', 'Discard');
            self.modal.close();
          }
        });

        // Init the first step.
        self.send('Export', _.first(self.steps));
      }
    }, {
      key: 'import',
      value: function _import() {
        var self = this;
        self.steps = [];
        self.modal = '';
        self.cancel = '';

        var attachmentId = $('.jupiterx-cp-import-wrap .jupiterx-form-control').data('id');

        // Return false if no package is selected.
        if ('undefined' === typeof attachmentId) {
          return false;
        }

        self.attachmentId = attachmentId;

        self.modal = jupiterx_modal({
          type: false,
          title: 'Import',
          text: '\n          ' + jupiterx_cp_textdomain.import_select_options + '\n          <form class="jupiterx-cp-import-form">\n            <label>\n              <input type="checkbox" name="check" value="Content" checked>\n              ' + jupiterx_cp_textdomain.site_content + '\n            </label>\n            <label>\n              <input type="checkbox" name="check" value="Widgets" checked>\n              ' + jupiterx_cp_textdomain.widgets + '\n            </label>\n            <label>\n              <input type="checkbox" name="check" value="Settings" checked>\n              ' + jupiterx_cp_textdomain.settings + '\n            </label>\n          </form>\n        ',
          showCancelButton: false,
          showConfirmButton: true,
          showCloseButton: true,
          showLearnmoreButton: false,
          showProgress: false,
          closeOnConfirm: false,
          confirmButtonText: jupiterx_cp_textdomain.import,
          onConfirm: function onConfirm() {
            var options = $('.jupiterx-cp-import-form').serializeArray();

            // Convert options to a flat array.
            if (!self._mapOptions(options)) {
              return;
            }

            jupiterx_modal({
              type: false,
              title: jupiterx_cp_textdomain.importing + ' <span class="cp-export-step">' + self.steps[1] + '</span>...',
              text: jupiterx_cp_textdomain.import_waiting,
              showCancelButton: true,
              showConfirmButton: false,
              showCloseButton: false,
              showLearnmoreButton: false,
              progress: '100%',
              showProgress: true,
              indefiniteProgress: true,
              cancelButtonText: jupiterx_cp_textdomain.discard,
              closeOnOutsideClick: false,
              closeOnConfirm: false,
              onCancel: function onCancel() {
                self.steps = [];
                self.cancel = true;
                self.send('Import', 'Discard');
                self.modal.close();
              }
            });

            // Init the first step.
            self.send('Import', _.first(self.steps));
          }
        });
      }
    }, {
      key: 'upload',
      value: function upload(event) {
        var frame = void 0;
        var $input = $(event.target).parents('.jupiterx-upload-wrap').find('input');

        if (frame) {
          frame.open();
          return;
        }

        frame = wp.media({
          multiple: false, // Set to true to allow multiple files to be selected
          title: jupiterx_cp_textdomain.select_zip_file,
          button: {
            text: jupiterx_cp_textdomain.select
          }
        });

        // When an image is selected in the media frame...
        frame.on('select', function () {
          var attachment = frame.state().get('selection').first().toJSON();
          $input.attr('data-id', attachment.id);
          $input.val(attachment.url);
        });

        frame.open();
      }
    }, {
      key: 'send',
      value: function send(type, step) {
        var self = this;

        wp.ajax.send('jupiterx_cp_export_import', {
          data: {
            nonce: jupiterxControlPanel.nonce,
            type: type,
            step: step,
            attachment_id: self.attachmentId,
            data: self.data
          },
          success: function success(res) {
            self.steps = _.without(self.steps, res.step);
            var firstStep = _.first(self.steps);

            // Open the download modal.
            if (!self.steps.length) {
              if (true === self.cancel) {
                return;
              }

              var confirmButtonText = 'Export' === type ? jupiterx_cp_textdomain.download : jupiterx_cp_textdomain.close;

              jupiterx_modal({
                type: 'success',
                title: type + ' ' + jupiterx_cp_textdomain.done,
                text: type + ' ' + jupiterx_cp_textdomain.successfully_finished,
                showCancelButton: false,
                showConfirmButton: true,
                showCloseButton: false,
                showLearnmoreButton: false,
                showProgress: false,
                closeOnConfirm: false,
                confirmButtonText: confirmButtonText,
                onConfirm: function onConfirm() {
                  if ('Export' === type) {
                    window.location.href = res.download_url;
                  }
                  self.modal.close();
                }
              });

              return;
            }

            // Update title in modal except Start one.
            if (res.step !== 'Start') {
              $('.cp-export-step').text(res.step);
            }

            // Init the next step.
            self.send(type, firstStep);
          },
          error: function error(res) {
            console.log(res);

            jupiterx_modal({
              type: 'error',
              title: jupiterx_cp_textdomain.error,
              text: res + ' ' + jupiterx_cp_textdomain.issue_persists,
              showCancelButton: false,
              showConfirmButton: true,
              showCloseButton: false,
              showLearnmoreButton: false,
              showProgress: false,
              closeOnConfirm: false,
              confirmButtonText: jupiterx_cp_textdomain.try_again,
              onConfirm: function onConfirm() {
                window.location.reload();
                self.modal.close();
              }
            });
          }
        });
      }
    }, {
      key: '_mapOptions',
      value: function _mapOptions(options) {
        var self = this;

        // Convert options to a flat array.
        _.map(options, function (option) {
          return self.steps.push(option.value);
        });

        // Return false if no option is selected.
        if (!self.steps.length) {
          return false;
        }

        self.steps.unshift('Start');
        self.steps.push('End');
        return true;
      }
    }]);

    return SectionExportImport;
  }();

  var SectionPlugins = function () {
    function SectionPlugins() {
      _classCallCheck(this, SectionPlugins);

      this.$element = $('.jupiterx-cp-plugins-list');

      if (this.$element.length) {
        this.$header = this.$element.find('.jupiterx-cp-plugins-header');
        this.$alertOptional = this.$element.find('.jupiterx-cp-plugins-alert-optional');
        this.$actionsRequired = this.$element.find('.jupiterx-cp-plugins-actions-required');
        this.$plugins = this.$element.find('#js__jupiterx-plugins');
        this.$pluginsRequired = this.$element.find('.jupiterx-cp-plugins-required');
        this.$pluginsOptional = this.$element.find('.jupiterx-cp-plugins-optional');
        this.isMultisite = document.body.classList.contains('multisite');
        this.plugins = [];
        this.limit = 0;
        this.events();
        this.init();

        if (this.isPlugin2()) {
          this.$plugins.removeClass('d-flex').addClass('d-none');
        }
      }
    }

    _createClass(SectionPlugins, [{
      key: 'init',
      value: function init() {
        var self = this;
        var localPlugins = localStorage.getItem('plugins');

        // Load from cache.
        if (!_.isEmpty(localPlugins)) {
          localPlugins = JSON.parse(localPlugins);

          var plugins = self.sortByLabel(localPlugins);
          self.plugins = [].concat(_toConsumableArray(plugins));
          self.limit = localStorage.getItem('limit');
          self.render();

          // Delete cache.
          localStorage.removeItem('plugins');
          localStorage.removeItem('limit');
        } else {
          $.post(_wpUtilSettings.ajax.url, { action: 'abb_get_plugins' }, function (res) {
            if (_.isEmpty(res.plugins)) {
              self.$plugins.html('');
              self.$header.after($('\n              <div class="jupiterx-cp-plugins-notices">\n                <div class="alert alert-danger">' + jupiterx_cp_textdomain.api_request_error + '</div>\n              </div>'));
              return;
            }

            var plugins = self.sortByLabel(res.plugins);
            self.plugins = [].concat(_toConsumableArray(plugins));
            self.limit = res.limit;
            self.render();
          });
        }
      }
    }, {
      key: 'updateState',
      value: function updateState(plugins) {
        var state = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
        var reload = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;

        var self = this;

        if (!_.isArray(plugins)) {
          plugins = [plugins];
        }

        // Reload page when using old version API.
        if (plugins.length && !self.isOldAPIVersion(plugins[0])) {
          location.reload();
          return;
        }

        // Update state.
        _.each(plugins, function (plugin) {
          var index = _.findIndex(self.plugins, function (record) {
            return record.slug === plugin.slug;
          });
          plugin = _extends({}, plugin, state);
          self.plugins[index] = _extends({}, plugin);
          self.plugins[index].html.attr('data-update', self.plugins[index].update_needed ? 'yes' : 'no');

          // Update version text.
          if (plugin.server_version && plugin.update_needed === false) {
            plugin.html.find('.item-version-tag').text(plugin.server_version);
            self.plugins[index].version = plugin.server_version;
          }

          if (plugin.pro === true) {
            plugin.html.find('.jupiterx-pro-badge').remove();
            plugin.html.find('.jupiterx-card').prepend('<img class="jupiterx-pro-badge" src="' + jupiterxControlPanel.proBadgeUrl + '" />');
          }

          if (!reload) {
            self.plugins[index].html.find('.btn').remove();
            self.plugins[index].html.find('.jupiterx-card-body').append(self.buttons(self.plugins[index]));
          }
        });

        // Re-render notices and filters.
        self.notices(reload);
        self.filters(reload);

        // Cache plugins first before reload.
        if (reload) {
          self.runCache();
          location.reload();
        }
      }
    }, {
      key: 'runCache',
      value: function runCache() {
        var plugins = this.plugins.map(function (plugin) {
          return _extends({}, plugin, { html: null });
        });
        localStorage.setItem('plugins', JSON.stringify(plugins));
        localStorage.setItem('limit', this.limit);
      }
    }, {
      key: 'events',
      value: function events() {
        var self = this;

        $(document).on('click', '.abb_plugin_activate', function (event) {
          event.preventDefault();
          self.handleActivate($(this));
        });

        $(document).on('click', '.abb_plugin_install', function (event) {
          event.preventDefault();
          self.handleInstall($(this));
        });

        $(document).on('click', '.abb_plugin_deactivate', function (event) {
          event.preventDefault();
          self.handleDeactivate($(this));
        });

        $(document).on('click', '.abb_plugin_delete', function (event) {
          event.preventDefault();
          self.handleDelete($(this));
        });

        $(document).on('click', '.abb_plugin_update', function (event) {
          event.preventDefault();
          self.handleUpdate($(this));
        });

        $(document).on('click', '.jupiterx-cp-plugins-filter > .btn', function (event) {
          event.preventDefault();
          self.handleFilter($(this));
        });

        $(document).on('click', '.jupiterx-cp-activate-plugins', function (event) {
          event.preventDefault();
          self.handleActivatePlugins($(this));
        });

        $(document).on('click', '.jupiterx-cp-update-plugins', function (event) {
          event.preventDefault();
          self.handleUpdatePlugins($(this));
        });
      }
    }, {
      key: 'render',
      value: function render() {
        var self = this;

        if (!this.isPlugin2()) {
          self.$notices = $('<div class="jupiterx-cp-plugins-notices">\n          <div class="alert alert-warning">' + jupiterx_cp_textdomain.plugins_notice + '</div>\n        </div>');
          self.$header.after(self.$notices);
        } else {
          this.$actionsRequired.append(self.$notices);
        }

        self.notices(false);
        self.$filters = $('.jupiterx-cp-plugins-filter');
        self.$filters.removeClass('disabled');
        self.$filters.find('.btn').removeAttr('disabled');
        self.filters();
        self.$plugins.html('');
        self.$pluginsRequired.html('');
        self.$pluginsOptional.html('');

        if (this.isPlugin2()) {
          // Required
          _.each(self.plugins, function (plugin, i) {
            if (plugin.required == 'false') {
              return;
            }

            var html = $(self.card(plugin));
            self.plugins[i].html = html;
            self.$pluginsRequired.append(html);
          });

          // Optional
          _.each(self.plugins, function (plugin, i) {
            if (plugin.required == 'true') {
              return;
            }

            var html = $(self.card(plugin));
            self.plugins[i].html = html;
            self.$pluginsOptional.append(html);
          });
        } else {
          _.each(self.plugins, function (plugin, i) {
            var html = $(self.card(plugin));
            self.plugins[i].html = html;
            self.$plugins.append(html);
          });
        }

        var activeFilter = localStorage.getItem('activeFilter');
        if (activeFilter) {
          self.$filters.find('.btn[data-filter=' + activeFilter + ']').click();
        }
      }
    }, {
      key: 'notices',
      value: function notices() {
        var reload = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

        if (reload) {
          return;
        }

        var self = this;

        // Hide notices on old core version users.
        if (self.plugins.length && !self.isOldAPIVersion(self.plugins[0])) {
          return;
        }

        if (this.isPlugin2()) {
          self.$notices = this.$actionsRequired;
        }

        var activate = _.filter(self.plugins, function (plugin) {
          return self.isActivateNeeded(plugin);
        });
        var update = _.filter(self.plugins, function (plugin) {
          return self.isUpdateNeeded(plugin);
        });

        // Show activate plugins button.
        self.$notices.find('.jupiterx-cp-activate-plugins').remove();
        if (activate.length) {
          self.$notices.append('<button type="button" class="jupiterx-cp-activate-plugins btn btn-primary">' + jupiterx_cp_textdomain.activate_required_plugins + '</button>');
        }

        // Show update plugins button.
        self.$notices.find('.jupiterx-cp-update-plugins').remove();
        if (update.length) {
          self.$notices.append('<button type="button" class="jupiterx-cp-update-plugins btn btn-warning ml-3">' + jupiterx_cp_textdomain.update_all_plugins + '</button>');
        }
      }
    }, {
      key: 'filters',
      value: function filters() {
        var reload = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

        if (reload) {
          return;
        }

        var self = this;
        var activeFilter = localStorage.getItem('activeFilter');
        var update = _.filter(self.plugins, function (plugin) {
          return self.isUpdateNeeded(plugin);
        });
        var html = '';

        // Show update button.
        self.$filters.find('.btn[data-filter=update]').remove();
        if (update.length) {
          html += '<button type="button" class="btn btn-outline-secondary" data-filter="update">' + jupiterx_cp_textdomain.updates_available + '</button>';
        }

        self.$filters.append(html);

        if (activeFilter === 'update' && !update.length) {
          $('.jupiterx-cp-plugins-filter > .btn[data-filter=all]').trigger('click');
        } else if (activeFilter === 'update' && update.length) {
          $('.jupiterx-cp-plugins-filter > .btn[data-filter=update]').trigger('click');
        }
      }
    }, {
      key: 'handleFilter',
      value: function handleFilter($this) {
        var actionFilter = $this.data('filter');

        localStorage.setItem('activeFilter', actionFilter);
        $('.jupiterx-cp-plugins-filter .btn').removeClass('btn-secondary').addClass('btn-outline-secondary');
        $this.removeClass('btn-outline-secondary').addClass('btn-secondary');

        if ('all' === actionFilter) {
          $('.jupiterx-cp-plugin-item').show();
          return;
        }

        if ('update' === actionFilter) {
          $('.jupiterx-cp-plugin-item').show();
          $('.jupiterx-cp-plugin-item').not('[data-update=yes]').hide();
          return;
        }

        $('.jupiterx-cp-plugin-item').show();
        $('.jupiterx-cp-plugin-item').not('[data-filter=' + actionFilter + ']').hide();
      }
    }, {
      key: 'sortByLabel',
      value: function sortByLabel(plugins) {
        var sort = [];

        // Transform to array.
        if (_.isObject(plugins)) {
          plugins = Object.keys(plugins).map(function (key) {
            return plugins[key];
          });
        }

        // Arrange indeces.
        var labels = ['required', 'recommended', 'optional'];
        labels.forEach(function (label) {
          plugins.forEach(function (plugin, index) {
            if (label === 'optional' && sort.indexOf(index) === -1 || sort.indexOf(index) === -1 && plugin[label] && plugin[label] !== 'false') {
              plugins[index].labeled_as = label;
              sort.push(index);
            }
          });
        });

        // Arrange data.
        sort = sort.map(function (index) {
          return plugins[index];
        });

        return sort;
      }
    }, {
      key: 'card',
      value: function card(plugin) {
        var self = this;
        var buttons = self.buttons(plugin);
        var dataFilter = plugin.active === true ? 'active' : 'inactive';
        var dataUpdate = self.isUpdateNeeded(plugin) ? 'yes' : 'no';

        return '\n        <div class="jupiterx-cp-plugin-item" data-filter="' + dataFilter + '" data-update="' + dataUpdate + '">\n          <div class="jupiterx-cp-plugin-item-inner jupiterx-card">\n            ' + (plugin.pro || self.isProPlugin(plugin) ? '<img class="jupiterx-pro-badge" src="' + jupiterxControlPanel.proBadgeUrl + '" />' : '') + '\n            <div class="jupiterx-card-body">\n              <figure class="jupiterx-cp-plugin-item-thumb">\n                <img src="' + plugin.img_url + '">\n              </figure>\n              <div class="jupiterx-cp-plugin-meta-wrapper">\n                <span class="jupiterx-cp-plugin-item-label label-' + plugin.labeled_as + '">' + jupiterx_cp_textdomain[plugin.labeled_as] + '</span>\n                <span class="jupiterx-cp-plugin-item-version">\n                  v<span class="item-version-tag">' + plugin.version + '</span>\n                </span>\n              </div>\n              <div class="jupiterx-cp-plugin-item-meta">\n                <div class="jupiterx-cp-plugin-item-name">' + plugin.name + '</div>\n                <div class="jupiterx-cp-plugin-item-desc">\n                  ' + plugin.desc + '\n                  ' + (plugin.more_link ? '<a href="' + plugin.more_link + '" target="_blank">' + jupiterx_cp_textdomain.learn_more + '</a>' : '') + '\n                </div>\n              </div>\n              ' + buttons + '\n            </div>\n          </div>\n        </div>\n      ';
      }
    }, {
      key: 'getActivationLimit',
      value: function getActivationLimit(plugin) {
        var self = this;
        var limits = (self.limit || '').split(',');
        if (limits.indexOf('num') > -1) {
          return true;
        }

        if (['layerslider', 'masterslider', 'revslider'].indexOf(plugin.slug) > -1 && limits.indexOf('sliders') > -1) {
          return true;
        }

        if (['jet-blog', 'jet-elements', 'jet-engine', 'jet-menu', 'jet-popup', 'jet-smart-filters', 'jet-tabs', 'jet-tricks', 'jet-woo-builder'].indexOf(plugin) > -1 && limits.indexOf('jet-plugins') > -1) {
          return true;
        }

        return false;
      }
    }, {
      key: 'buttons',
      value: function buttons(plugin) {
        var self = this;
        var isMultisite = self.isMultisite;

        var buttons = '';
        var limit = self.getActivationLimit(plugin);

        if (!plugin.installed) {
          if (plugin.pro || this.isProPlugin(plugin)) {
            // Display pro button.
            return '<a class="btn btn-sm jupiterx-btn-upgrade-pro jupiterx-icon-pro" href="' + jupiterx_cp_textdomain.upgrade_url + '" target="_blank">\n            ' + jupiterx_cp_textdomain.upgrade + '\n          </a>';
          }

          // Display install button.
          return '<a class="btn btn-sm btn-outline-success jupiterx-icon-plus-circle-solid abb_plugin_install ' + (plugin.install_disabled ? 'disabled' : '') + '" data-slug="' + plugin.slug + '" href="' + (plugin.install_url ? plugin.install_url : plugin.url) + '" data-name="' + plugin.name + '">\n          ' + jupiterx_cp_textdomain.install + '\n        </a>';
        }

        if (plugin.network_active) {
          // Display network activated.
          return '<span class="btn btn-sm network-active" href="#">\n          ' + jupiterx_cp_textdomain.network_active + '\n        </span>';
        }

        if (self.isUpdateNeeded(plugin)) {
          // Display update button, only for non-multisite.
          buttons += '<a class="btn btn-sm btn-warning abb_plugin_update" data-basename="' + plugin.basename + '" data-slug="' + plugin.slug + '" href="#" data-name="' + plugin.name + '">\n          ' + jupiterx_cp_textdomain.update + '\n        </a>';
        }

        if (plugin.active === true) {
          // Display deactivate buttons.
          buttons += '<a class="btn btn-sm btn-danger abb_plugin_deactivate" data-basename="' + plugin.basename + '" data-slug="' + plugin.slug + '" href="#" data-name="' + plugin.name + '">\n          ' + jupiterx_cp_textdomain.deactivate + '\n        </a>';
        } else {
          // Display activate button.
          buttons += '<a class="btn btn-sm btn-primary abb_plugin_activate" data-slug="' + plugin.slug + '" href="' + (plugin.activate_url ? plugin.activate_url : plugin.url) + '" data-name="' + plugin.name + '" data-limit="' + limit + '">\n          ' + jupiterx_cp_textdomain.activate + '\n        </a>';

          if (!isMultisite) {
            // Display delete button when plugin is not active, only for non-multisite.
            buttons += '<a class="btn btn-sm btn-outline-danger jupiterx-icon-times-circle abb_plugin_delete" data-basename="' + plugin.basename + '" data-slug="' + plugin.slug + '" href="#" data-name="' + plugin.name + '">\n            ' + jupiterx_cp_textdomain.delete + '\n          </a>';
          }
        }

        return buttons;
      }
    }, {
      key: 'handleInstall',
      value: function handleInstall($this) {
        var self = this;
        var name = $this.data('name');
        var slug = $this.data('slug');
        var url = $this.attr('href');
        var plugin = _.findWhere(self.plugins, { slug: slug });

        // Confirm install.
        jupiterx_modal({
          title: jupiterx_cp_textdomain.install_plugin,
          text: self.language(jupiterx_cp_textdomain.you_are_about_to_install, [name]),
          type: 'warning',
          showCancelButton: true,
          showConfirmButton: true,
          confirmButtonText: jupiterx_cp_textdomain.continue,
          showCloseButton: false,
          showLearnmoreButton: false,
          showProgress: false,
          closeOnOutsideClick: false,
          onConfirm: function onConfirm() {
            // Show install progress.
            jupiterx_modal({
              title: jupiterx_cp_textdomain.installing_plugin,
              text: jupiterx_cp_textdomain.wait_for_plugin_install,
              type: '',
              showCancelButton: false,
              showConfirmButton: false,
              showCloseButton: false,
              showLearnmoreButton: false,
              progress: '100%',
              showProgress: true,
              indefiniteProgress: true,
              closeOnOutsideClick: false
            });

            self.actionInstall({
              url: url,
              onSuccess: function onSuccess() {
                // On install success.
                jupiterx_modal({
                  title: jupiterx_cp_textdomain.plugin_is_successfully_installed,
                  text: self.language(jupiterx_cp_textdomain.plugin_installed_successfully_message, [name]),
                  type: 'success',
                  showCancelButton: false,
                  showConfirmButton: true,
                  showCloseButton: false,
                  showLearnmoreButton: false,
                  showProgress: false,
                  onConfirm: function onConfirm() {
                    self.updateState(plugin, {
                      installed: true,
                      active: false
                    });
                  }
                });

                var modal = $('#jupiterx-modal');

                modal.find('.js__modal-btn-confirm').addClass('btn-outline-primary').removeClass('btn-primary');

                modal.find('.jupiterx-modal-footer').append('<a class="btn btn-primary jupiterx-modal-activate-plugin abb_plugin_activate" data-slug="' + plugin.slug + '" href="' + plugin.activate_url + '" data-name="' + plugin.name + '">\n                  ' + jupiterx_cp_textdomain.activate + '\n                </a>');
              },
              onError: function onError() {
                // On install error.
                jupiterx_modal({
                  title: jupiterx_cp_textdomain.install_error,
                  text: jupiterx_cp_textdomain.install_plugin_failed,
                  type: 'error',
                  showCancelButton: false,
                  showConfirmButton: true,
                  showLearnmoreButton: false
                });
              }
            });
          }
        });
      }
    }, {
      key: 'handleDelete',
      value: function handleDelete($this) {
        var self = this;
        var name = $this.data('name');
        var slug = $this.data('slug');
        var plugin = _.findWhere(self.plugins, { slug: slug });

        // Confirm delete.
        jupiterx_modal({
          title: jupiterx_cp_textdomain.delete_plugin,
          text: self.language(jupiterx_cp_textdomain.you_are_about_to_delete, [name]),
          type: 'warning',
          showCancelButton: true,
          showConfirmButton: true,
          confirmButtonText: jupiterx_cp_textdomain.continue,
          showCloseButton: false,
          showLearnmoreButton: false,
          showProgress: false,
          closeOnOutsideClick: false,
          onConfirm: function onConfirm() {
            // Show delete progress.
            jupiterx_modal({
              title: jupiterx_cp_textdomain.deleting_plugin,
              text: jupiterx_cp_textdomain.wait_for_plugin_delete,
              type: '',
              showCancelButton: false,
              showConfirmButton: false,
              showCloseButton: false,
              showLearnmoreButton: false,
              progress: '100%',
              showProgress: true,
              indefiniteProgress: true
            });

            self.actionDelete({
              basename: plugin.basename,
              slug: slug,
              onSuccess: function onSuccess() {
                // On delete success.
                jupiterx_modal({
                  title: jupiterx_cp_textdomain.plugin_is_successfully_deleted,
                  text: self.language(jupiterx_cp_textdomain.plugin_deleted_successfully_message, [name]),
                  type: 'success',
                  showCancelButton: false,
                  showConfirmButton: true,
                  showCloseButton: false,
                  showLearnmoreButton: false,
                  showProgress: false,
                  closeOnOutsideClick: false,
                  onConfirm: function onConfirm() {
                    self.updateState(plugin, {
                      installed: false,
                      active: false,
                      update_needed: false,
                      pro: plugin.is_pro && !jupiterxControlPanel.isPro
                    });
                  }
                });
              }
            });
          }
        });
      }
    }, {
      key: 'handleActivate',
      value: function handleActivate($this) {
        var self = this;
        var name = $this.data('name');
        var url = $this.attr('href');
        var slug = $this.data('slug');
        var plugin = _.findWhere(self.plugins, { slug: slug });

        var activate = function activate() {
          // Show activate progress.
          jupiterx_modal({
            title: jupiterx_cp_textdomain.activating_plugin,
            text: jupiterx_cp_textdomain.wait_for_plugin_activation,
            type: '',
            showCancelButton: false,
            showConfirmButton: false,
            showCloseButton: false,
            showLearnmoreButton: false,
            progress: '100%',
            showProgress: true,
            indefiniteProgress: true,
            closeOnOutsideClick: false
          });

          self.actionActivate({
            url: url,
            slug: slug,
            onSuccess: function onSuccess() {
              // On activate success.
              jupiterx_modal({
                title: jupiterx_cp_textdomain.all_done,
                text: self.language(jupiterx_cp_textdomain.item_is_successfully_activated, [name]),
                type: 'success',
                showCancelButton: false,
                showConfirmButton: true,
                showCloseButton: false,
                showLearnmoreButton: false,
                showProgress: false,
                indefiniteProgress: true,
                closeOnOutsideClick: false,
                onConfirm: function onConfirm() {
                  self.updateState(plugin, {
                    installed: true,
                    active: true
                  }, true);
                }
              });
            },
            onError: function onError(text) {
              // On activate error.
              jupiterx_modal({
                title: jupiterx_cp_textdomain.activate_error,
                text: text,
                type: 'error',
                showCancelButton: false,
                showConfirmButton: true,
                showLearnmoreButton: false
              });
            }
          });
        };

        // Confirm activate.
        jupiterx_modal({
          title: jupiterx_cp_textdomain.activating_notice,
          text: self.language(jupiterx_cp_textdomain.are_you_sure_you_want_to_activate, [name]),
          type: 'warning',
          showCancelButton: true,
          showConfirmButton: true,
          confirmButtonText: jupiterx_cp_textdomain.continue,
          showCloseButton: false,
          showLearnmoreButton: false,
          showProgress: false,
          closeOnOutsideClick: false,
          onConfirm: function onConfirm() {
            if ($this.data('limit') === true) {
              // Show limit activate warning.
              jupiterx_modal({
                title: jupiterx_cp_textdomain.plugin_limit_warning,
                text: jupiterx_cp_textdomain.plugin_limit_warning_message + '<a href="https://themes.artbees.net/docs/why-should-i-keep-my-active-plugins-at-minimum" target="_blank" class="jupiterx-modal-learn-more jupiterx-icon-question-circle" title="' + jupiterx_cp_textdomain.learn_more + '">' + jupiterx_cp_textdomain.learn_more + '</a>',
                type: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: jupiterx_cp_textdomain.continue,
                showCloseButton: false,
                showLearnmoreButton: false,
                showProgress: false,
                closeOnOutsideClick: false,
                onConfirm: function onConfirm() {
                  activate();
                }
              });
            } else {
              activate();
            }
          }
        });
      }
    }, {
      key: 'handleDeactivate',
      value: function handleDeactivate($this) {
        var self = this;
        var name = $this.data('name');
        var slug = $this.data('slug');
        var plugin = _.findWhere(self.plugins, { slug: slug });

        // Confirm deactivate.
        jupiterx_modal({
          title: jupiterx_cp_textdomain.important_notice,
          text: self.language(jupiterx_cp_textdomain.are_you_sure_you_want_to_deactivate, [name]),
          type: 'warning',
          showCancelButton: true,
          showConfirmButton: true,
          confirmButtonText: jupiterx_cp_textdomain.continue,
          showCloseButton: false,
          showLearnmoreButton: false,
          showProgress: false,
          closeOnOutsideClick: false,
          onConfirm: function onConfirm() {
            // Show deactivate progress.
            jupiterx_modal({
              title: jupiterx_cp_textdomain.deactivating_plugin,
              text: jupiterx_cp_textdomain.wait_for_plugin_deactivation,
              type: '',
              showCancelButton: false,
              showConfirmButton: false,
              showCloseButton: false,
              showLearnmoreButton: false,
              progress: '100%',
              showProgress: true,
              indefiniteProgress: true
            });

            self.actionDeactivate({
              slug: slug,
              onSuccess: function onSuccess() {
                // On deactivate success.
                jupiterx_modal({
                  title: jupiterx_cp_textdomain.deactivating_notice,
                  text: self.language(jupiterx_cp_textdomain.plugin_deactivate_successfully, []),
                  type: 'success',
                  showCancelButton: false,
                  showConfirmButton: true,
                  showCloseButton: false,
                  showLearnmoreButton: false,
                  showProgress: false,
                  indefiniteProgress: false,
                  closeOnOutsideClick: false,
                  onConfirm: function onConfirm() {
                    self.updateState(plugin, {
                      installed: true,
                      active: false
                    }, true);
                  }
                });
              }
            });
          }
        });
      }
    }, {
      key: 'handleUpdate',
      value: function handleUpdate($this) {
        var self = this;
        var name = $this.data('name');
        var slug = $this.data('slug');
        var plugin = _.findWhere(self.plugins, { slug: slug });

        // Check plugin conficts.
        jupiterx_modal({
          title: jupiterx_cp_textdomain.update_plugin_checker_title,
          text: jupiterx_cp_textdomain.update_plugin_checker_progress,
          type: 'warning',
          showCancelButton: true,
          showCloseButton: false,
          showLearnmoreButton: false,
          showProgress: true,
          showConfirmButton: true,
          confirmButtonText: jupiterx_cp_textdomain.continue,
          closeOnOutsideClick: false,
          progress: '100%',
          onConfirm: function onConfirm() {
            // Confirm update.
            jupiterx_modal({
              title: jupiterx_cp_textdomain.update_plugin,
              text: self.language(jupiterx_cp_textdomain.you_are_about_to_update, [name]),
              type: 'warning',
              showCancelButton: true,
              showConfirmButton: true,
              confirmButtonText: jupiterx_cp_textdomain.continue,
              showCloseButton: false,
              showLearnmoreButton: false,
              showProgress: false,
              closeOnOutsideClick: false,
              onConfirm: function onConfirm() {
                // Show update progress.
                jupiterx_modal({
                  title: jupiterx_cp_textdomain.updating_plugin,
                  text: jupiterx_cp_textdomain.wait_for_plugin_update,
                  type: '',
                  showCancelButton: false,
                  showConfirmButton: false,
                  showCloseButton: false,
                  showLearnmoreButton: false,
                  progress: '100%',
                  showProgress: true,
                  indefiniteProgress: true
                });

                self.actionUpdate({
                  url: plugin.update_url,
                  onSuccess: function onSuccess() {
                    // Reactivate after update.
                    if (plugin.active) {
                      self.actionActivate({
                        url: plugin.activate_url ? plugin.activate_url : plugin.url, // Support auto activate on old core version user.
                        slug: plugin.slug,
                        onSuccess: function onSuccess() {
                          // On activate success.
                          jupiterx_modal({
                            title: jupiterx_cp_textdomain.plugin_is_successfully_updated,
                            text: self.language(jupiterx_cp_textdomain.plugin_updated_recent_version, [name]),
                            type: 'success',
                            showCancelButton: false,
                            showConfirmButton: true,
                            showCloseButton: false,
                            showLearnmoreButton: false,
                            showProgress: false,
                            closeOnOutsideClick: false,
                            onConfirm: function onConfirm() {
                              self.updateState(plugin, {
                                update_needed: false
                              });
                            }
                          });
                        },
                        onError: function onError(text) {
                          // On activate error.
                          jupiterx_modal({
                            title: jupiterx_cp_textdomain.activate_error,
                            text: text,
                            type: 'error',
                            showCancelButton: false,
                            showConfirmButton: true,
                            showLearnmoreButton: false
                          });
                        }
                      });
                    } else {
                      // On update success.
                      jupiterx_modal({
                        title: jupiterx_cp_textdomain.plugin_is_successfully_updated,
                        text: self.language(jupiterx_cp_textdomain.plugin_updated_recent_version, [name]),
                        type: 'success',
                        showCancelButton: false,
                        showConfirmButton: true,
                        showCloseButton: false,
                        showLearnmoreButton: false,
                        showProgress: false,
                        closeOnOutsideClick: false,
                        onConfirm: function onConfirm() {
                          self.updateState(plugin, {
                            update_needed: false
                          });
                        }
                      });
                    }
                  },
                  onError: function onError() {
                    // On update error.
                    jupiterx_modal({
                      title: jupiterx_cp_textdomain.update_error,
                      text: jupiterx_cp_textdomain.update_plugin_failed,
                      type: 'error',
                      showCancelButton: false,
                      showConfirmButton: true,
                      showLearnmoreButton: false
                    });
                  }
                });
              }
            });
          }
        });

        jupiterx_modal.disableConfirmBtn();

        self.updateChecker(slug);
      }
    }, {
      key: 'handleActivatePlugins',
      value: function handleActivatePlugins() {
        var self = this;
        var plugins = _.filter(self.plugins, function (plugin) {
          return self.isActivateNeeded(plugin);
        });

        // Confirm activate plugins.
        jupiterx_modal({
          title: jupiterx_cp_textdomain.activate_required_plugins,
          text: jupiterx_cp_textdomain.confirm_activate_plugins,
          type: 'warning',
          showCancelButton: true,
          showConfirmButton: true,
          confirmButtonText: jupiterx_cp_textdomain.continue,
          showCloseButton: false,
          showLearnmoreButton: false,
          showProgress: false,
          closeOnOutsideClick: false,
          onConfirm: function onConfirm() {
            var instance = 0;

            var modal = $('\n            <div class="jupiterx-modal-header">\n              <h3 class="jupiterx-modal-title">' + jupiterx_cp_textdomain.activating_plugins + '</h3>\n            </div>\n            <div class="jupiterx-modal-desc">\n              <ul class="jupiterx-modal-step-list jupiterx-plugins-step-list">\n                ' + _.map(plugins, function (plugin) {
              return '<li class="step-' + plugin.slug + '">' + plugin.name + ' <span class="result-message"></span></li>';
            }).join('') + '\n              </ul>\n            </div>\n          ');

            var run = function run() {
              if (instance >= plugins.length) {
                modal.find('.jupiterx-modal-title').before($('<span class="jupiterx-modal-icon"></span>'));
                modal.find('.jupiterx-modal-title').text(jupiterx_cp_textdomain.activating_plugins_successful);

                // On done.
                jupiterx_modal({
                  html: modal,
                  type: 'success',
                  showCloseButton: false,
                  showConfirmButton: true,
                  closeOnOutsideClick: false,
                  confirmButtonText: jupiterx_cp_textdomain.continue,
                  onConfirm: function onConfirm() {
                    self.updateState(plugins, {}, true);
                  }
                });

                return;
              }

              var plugin = plugins[instance];
              var step = modal.find('.step-' + plugin.slug);
              var result = step.find('.result-message');

              if (plugin.installed) {
                result.text(jupiterx_cp_textdomain.activating_plugin_progress);

                self.actionActivate({
                  url: plugin.activate_url,
                  slug: plugin.slug,
                  onSuccess: function onSuccess() {
                    plugin.installed = true;
                    plugin.active = true;
                    step.addClass('step-done');
                    result.text(jupiterx_cp_textdomain.completed);
                    instance++;
                    run();
                  },
                  onError: function onError(text) {
                    plugin.installed = true;
                    plugin.active = false;
                    step.addClass('step-error');
                    result.text(text);
                    instance++;
                    run();
                  }
                });
              } else {
                result.text(jupiterx_cp_textdomain.installing_plugin_progress);

                self.actionInstall({
                  url: plugin.install_url,
                  onSuccess: function onSuccess() {
                    plugin.installed = true;
                    plugin.active = false;
                    run();
                  },
                  onError: function onError() {
                    // Go to next plugin.
                    plugin.installed = false;
                    plugin.active = false;
                    step.addClass('step-error');
                    result.text(jupiterx_cp_textdomain.install_error);
                    instance++;
                    run();
                  }
                });
              }
            };

            // Show plugins progress.
            jupiterx_modal({
              html: modal,
              showProgress: true,
              progress: '100%',
              showCloseButton: false,
              showConfirmButton: true,
              closeOnOutsideClick: false,
              confirmButtonText: jupiterx_cp_textdomain.continue
            });
            jupiterx_modal.disableConfirmBtn();

            run();
          }
        });
      }
    }, {
      key: 'handleUpdatePlugins',
      value: function handleUpdatePlugins() {
        var self = this;
        var plugins = _.filter(self.plugins, function (plugin) {
          return self.isUpdateNeeded(plugin);
        });

        // Confirm activate plugins.
        jupiterx_modal({
          title: jupiterx_cp_textdomain.update_all_plugins,
          text: jupiterx_cp_textdomain.confirm_update_plugins,
          type: 'warning',
          showCancelButton: true,
          showConfirmButton: true,
          confirmButtonText: jupiterx_cp_textdomain.continue,
          showCloseButton: false,
          showLearnmoreButton: false,
          showProgress: false,
          closeOnOutsideClick: false,
          onConfirm: function onConfirm() {
            var instance = 0;

            var modal = $('\n            <div class="jupiterx-modal-header">\n              <h3 class="jupiterx-modal-title">' + jupiterx_cp_textdomain.updating_plugins + '</h3>\n            </div>\n            <div class="jupiterx-modal-desc">\n              <ul class="jupiterx-modal-step-list jupiterx-plugins-step-list">\n                ' + _.map(plugins, function (plugin) {
              return '<li class="step-' + plugin.slug + '">' + plugin.name + ' <span class="result-message"></span></li>';
            }).join('') + '\n              </ul>\n            </div>\n          ');

            var run = function run() {
              if (instance >= plugins.length) {
                modal.find('.jupiterx-modal-title').before($('<span class="jupiterx-modal-icon"></span>'));
                modal.find('.jupiterx-modal-title').text(jupiterx_cp_textdomain.updating_plugins_successful);

                // On done.
                jupiterx_modal({
                  html: modal,
                  type: 'success',
                  showCloseButton: false,
                  showConfirmButton: true,
                  closeOnOutsideClick: false,
                  confirmButtonText: jupiterx_cp_textdomain.continue,
                  onConfirm: function onConfirm() {
                    self.updateState(plugins);
                  }
                });

                return;
              }

              var plugin = plugins[instance];
              var step = modal.find('.step-' + plugin.slug);
              var result = step.find('.result-message');

              result.text(jupiterx_cp_textdomain.updating_plugin_progress);

              self.actionUpdate({
                url: plugin.update_url,
                onSuccess: function onSuccess() {
                  if (plugin.active === true) {
                    result.text(jupiterx_cp_textdomain.activating_plugin_progress);

                    self.actionActivate({
                      url: plugin.activate_url,
                      slug: plugin.slug,
                      onSuccess: function onSuccess() {
                        step.addClass('step-done');
                        result.text(jupiterx_cp_textdomain.completed);
                        instance++;
                        plugin.update_needed = false;
                        run();
                      },
                      onError: function onError(text) {
                        step.addClass('step-error');
                        result.text(text);
                        instance++;
                        run();
                      }
                    });
                  } else {
                    step.addClass('step-done');
                    result.text(jupiterx_cp_textdomain.completed);
                    instance++;
                    plugin.update_needed = false;
                    run();
                  }
                },
                onError: function onError() {
                  step.addClass('step-error');
                  result.text(jupiterx_cp_textdomain.update_error);
                  instance++;
                  run();
                }
              });
            };

            // Show plugins progress.
            jupiterx_modal({
              html: modal,
              showProgress: true,
              progress: '100%',
              showCloseButton: false,
              showConfirmButton: true,
              closeOnOutsideClick: false,
              confirmButtonText: jupiterx_cp_textdomain.continue
            });
            jupiterx_modal.disableConfirmBtn();

            run();
          }
        });
      }
    }, {
      key: 'updateChecker',
      value: function updateChecker(plugin) {
        plugin = _extends({}, plugin);
        delete plugin['html'];

        var conflictTemplate = function conflictTemplate(ths, conflicts) {
          var html = '<table class="jupiterx_update_plugin_conflicts_table">';
          html += '<thead>';
          html += '<tr>';
          html += _.map(ths, function (th) {
            return '<th><p>' + th + '</p></th>';
          }).join('');
          html += '</tr>';
          html += '</thead>';
          html += '<tbody>';
          html += _.map(conflicts, function (conflict) {
            return '<tr>' + '<td><p>' + conflict.name + '</p></td>' + '<td><p>' + conflict.min_version + '</p></td>' + '</tr>';
          }).join('');
          html += '</tbody>';
          html += '</table>';
          return html;
        };

        $.ajax({
          type: 'POST',
          url: _wpUtilSettings.ajax.url,
          data: {
            action: 'abb_update_plugin_checker',
            plugin: plugin
          },
          success: function success() {
            jupiterx_modal.enableConfirmBtn();
            jupiterx_modal.hideProgressBar();
            jupiterx_modal.update({ desc: jupiterx_cp_textdomain.update_plugin_checker_no_conflict });
          },
          error: function error(res) {
            jupiterx_modal.enableConfirmBtn();
            jupiterx_modal.hideProgressBar();

            var html = jupiterx_cp_textdomain.update_plugin_checker_warning;

            if (res.plugins && res.plugins.length > 0) {
              html += conflictTemplate([jupiterx_cp_textdomain.plugins, jupiterx_cp_textdomain.upgrade_to_version], res.plugins);
            }

            if (res.themes && res.themes.length > 0) {
              html += conflictTemplate([jupiterx_cp_textdomain.themes, jupiterx_cp_textdomain.upgrade_to_version], res.themes);
            }

            jupiterx_modal.update({ desc: html });
          }
        });
      }
    }, {
      key: 'actionInstall',
      value: function actionInstall(params) {
        var url = params.url,
            onSuccess = params.onSuccess,
            onError = params.onError;


        $.get({
          url: _.unescape(url),
          success: function success(res) {
            var error = $(res).find('.jupiterx-tgmpa-error');

            if (error.length) {
              if (onError) {
                onError(res);
              }
              return;
            }

            if (onSuccess) {
              onSuccess(res);
            }
          },
          error: function error(XMLHttpRequest, textStatus, errorThrown) {
            self.requestErrorHandler(XMLHttpRequest, textStatus, errorThrown);
          }
        });
      }
    }, {
      key: 'actionDelete',
      value: function actionDelete(params) {
        wp.updates.ajax('delete-plugin', {
          plugin: params.basename,
          slug: params.slug,
          success: function success(res) {
            if (params.onSuccess) {
              params.onSuccess(res);
            }
          },
          error: function error(res) {
            jupiterx_modal({
              title: jupiterx_cp_textdomain.something_went_wrong,
              text: res.errorMessage,
              type: 'error',
              showCancelButton: false,
              showConfirmButton: true,
              showLearnmoreButton: false
            });
          }
        });
      }
    }, {
      key: 'actionActivate',
      value: function actionActivate(params) {
        var url = params.url,
            onSuccess = params.onSuccess,
            onError = params.onError,
            slug = params.slug;


        $.get({
          url: _.unescape(url),
          success: function success(res) {
            var error = $(res).find('#message.error');

            if (error.length) {
              if (onError) {
                onError(error.text());
              }
              return;
            }

            // Elementor redirects to their getting started page after activate, to prevent issues on chain actions
            // we need to visit the redirect page first then continue doing actions.
            if (slug === 'elementor') {
              $.get({
                url: _.unescape(url),
                success: function success(res) {
                  if (onSuccess) {
                    onSuccess(res);
                  }
                }
              });
            } else if (onSuccess) {
              onSuccess(res);
            }
          },
          error: function error(XMLHttpRequest, textStatus, errorThrown) {
            self.requestErrorHandler(XMLHttpRequest, textStatus, errorThrown);
          }
        });
      }
    }, {
      key: 'actionDeactivate',
      value: function actionDeactivate(params) {
        $.post({
          url: _wpUtilSettings.ajax.url,
          data: {
            action: 'abb_deactivate_plugin',
            slug: params.slug
          },
          success: function success(res) {
            if (params.onSuccess) {
              params.onSuccess(res);
            }
          },
          error: function error(XMLHttpRequest, textStatus, errorThrown) {
            self.requestErrorHandler(XMLHttpRequest, textStatus, errorThrown);
          }
        });
      }
    }, {
      key: 'actionUpdate',
      value: function actionUpdate(params) {
        var url = params.url,
            onSuccess = params.onSuccess,
            onError = params.onError;


        $.ajax({
          type: 'GET',
          url: _.unescape(url),
          success: function success(res) {
            var error = $(res).find('.jupiterx-tgmpa-error');

            if (error.length) {
              if (onError) {
                onError(res);
              }
              return;
            }

            if (onSuccess) {
              onSuccess(res);
            }
          },
          error: function error(res) {
            jupiterx_modal({
              title: jupiterx_cp_textdomain.something_went_wrong,
              text: _.last(res.debug),
              type: 'error',
              showCancelButton: false,
              showConfirmButton: true,
              showLearnmoreButton: false
            });
          }
        });
      }
    }, {
      key: 'isProPlugin',
      value: function isProPlugin(plugin) {
        return !plugin.installed && plugin.is_pro && !jupiterxControlPanel.isPro;
      }
    }, {
      key: 'isActivateNeeded',
      value: function isActivateNeeded(plugin) {
        return plugin.labeled_as === 'required' && plugin.active === false && !plugin.pro;
      }
    }, {
      key: 'isUpdateNeeded',
      value: function isUpdateNeeded(plugin) {
        var isMultisite = this.isMultisite;


        if (isMultisite || !jupiterxControlPanel.isPro && plugin.is_pro) {
          return false;
        }

        return plugin.installed && plugin.update_needed === true && !plugin.pro;
      }
    }, {
      key: 'isOldAPIVersion',
      value: function isOldAPIVersion(plugin) {
        // If a user is using old version core plugin, they don't have `server_version` from each of the plugin response.
        return plugin.server_version;
      }

      // Check if plugin list 2 exists.

    }, {
      key: 'isPlugin2',
      value: function isPlugin2() {
        return this.$element.find('#js__jupiterx-plugins-2').length;
      }
    }, {
      key: 'language',
      value: function language(string, params) {
        if (typeof string === 'undefined' || string === '') {
          return;
        }

        var array_len = params.length;

        if (array_len < 1) {
          return string;
        }

        var indicator_len = (string.match(/{param}/g) || []).length;

        if (array_len === indicator_len) {
          $.each(params, function (key, val) {
            string = string.replace('{param}', val);
          });
          return string;
        }

        // Array len and indicator lengh is not same;
        console.log('Array len and indicator lengh is not same, Contact support with ID : (3-6H1T4I) .');
        return string;
      }
    }, {
      key: 'requestErrorHandler',
      value: function requestErrorHandler(XMLHttpRequest) {
        console.log(XMLHttpRequest);

        if (XMLHttpRequest.readyState === 4) {
          // HTTP error (can be checked by XMLHttpRequest.status and XMLHttpRequest.statusText)
          jupiterx_modal({
            title: jupiterx_cp_textdomain.something_went_wrong,
            text: XMLHttpRequest.status,
            type: 'error',
            showCancelButton: false,
            showConfirmButton: true,
            showLearnmoreButton: false
          });
        } else if (XMLHttpRequest.readyState === 0) {
          // Network error (i.e. connection refused, access denied due to CORS, etc.)
          jupiterx_modal({
            title: jupiterx_cp_textdomain.something_went_wrong,
            text: jupiterx_cp_textdomain.error_in_network_please_check_your_connection_and_try_again,
            type: 'error',
            showCancelButton: false,
            showConfirmButton: true,
            showLearnmoreButton: false
          });
        } else {
          jupiterx_modal({
            title: jupiterx_cp_textdomain.something_went_wrong,
            text: jupiterx_cp_textdomain.something_wierd_happened_please_try_again,
            type: 'error',
            showCancelButton: false,
            showConfirmButton: true,
            showLearnmoreButton: false
          });
        }
      }
    }]);

    return SectionPlugins;
  }();

  var ControlPanel = function () {
    function ControlPanel(_ref) {
      var node = _ref.node;

      _classCallCheck(this, ControlPanel);

      this.element = $(node);
      this.sidebar = this.element.find('.jupiterx-cp-sidebar');
      this.panes = this.element.find('.jupiterx-cp-panes');
      this.sections = {
        home: SectionHome,
        settings: SectionSettings,
        'install-templates': SectionTemplates,
        'system-status': SectionSystemStatus,
        'update-theme': SectionUpdates,
        'image-sizes': SectionImageSizes,
        'export-import': SectionExportImport,
        'install-plugins': SectionPlugins
      };
      this.init();
      this.events();
    }

    _createClass(ControlPanel, [{
      key: 'init',
      value: function init() {
        var hash = window.location.hash;
        var slug = hash.substring(1, hash.length);
        var _jupiterxControlPanel = jupiterxControlPanel,
            initialSection = _jupiterxControlPanel.initialSection;


        if (hash && slug && slug !== initialSection) {
          this.goTo(slug);
        } else if (initialSection) {
          this.sectionEvents(initialSection);
          this.commonEvents();
        }
      }
    }, {
      key: 'events',
      value: function events() {
        var self = this;
        var element = self.element;


        element.on('click', '.jupiterx-cp-sidebar-link', function (event) {
          event.preventDefault();

          var $this = $(this);
          var hash = $this.attr('href');
          var slug = hash.substring(1, hash.length);

          window.location.hash = hash;
          self.goTo(slug);
        });
      }
    }, {
      key: 'goTo',
      value: function goTo(slug) {
        var self = this;
        var sidebar = self.sidebar,
            panes = self.panes;

        panes.addClass('loading-pane');

        $.ajax({
          type: 'POST',
          url: _wpUtilSettings.ajax.url,
          data: {
            action: 'jupiterx_cp_load_pane_action',
            slug: slug
          },
          success: function success(res) {
            panes.empty();
            panes.append(res.data);
            panes.removeClass('loading-pane');

            sidebar.find('.jupiterx-is-active').removeClass('jupiterx-is-active');
            sidebar.find('[href=\'#' + slug + '\']').parent().addClass('jupiterx-is-active');
            self.sectionEvents(slug);
            self.commonEvents();
          }
        });
      }
    }, {
      key: 'sectionEvents',
      value: function sectionEvents(slug) {
        var sections = this.sections;


        if (sections[slug]) {
          new sections[slug]();
        }
      }
    }, {
      key: 'commonEvents',
      value: function commonEvents() {
        var self = this;

        $('.jupiterx-cpanel-link').on('click', function (event) {
          event.preventDefault();

          var $this = $(this);
          var hash = $this.attr('href');
          var slug = hash.substring(1, hash.length);

          window.location.hash = hash;
          self.goTo(slug);
        });

        $('.jupiterx-pro-badge').tooltip({
          title: jupiterx_cp_textdomain.pro_badge_tooltip_title,
          trigger: 'hover',
          container: '.jupiterx-wrap',
          template: '\n          <div class="tooltip jupiterx-pro-badge-tooltip" role="tooltip">\n            <div class="arrow"></div>\n            <div class="tooltip-inner"></div>\n          </div>\n        '
        });

        $('[data-toggle="popover"]').click(function (event) {
          event.preventDefault();
        });

        $('[data-toggle="popover"]').popover({
          trigger: 'hover',
          container: '.jupiterx.jupiterx-cp-wrap'
        });

        $('[data-toggle="tooltip"]').tooltip();
      }
    }]);

    return ControlPanel;
  }();

  $('.jupiterx-cp-wrap').each(function (i, node) {
    new ControlPanel({
      node: node
    });
  });
})(jQuery, jupiterx);