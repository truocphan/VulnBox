'use strict';

// ---------------------------------
// ---------- Jupiter Modal ----------
// ---------------------------------
// This plugin is used to output messages in Jupiter WordPress Theme's Admin Panel
// ------------------------
;
(function ($, window, document) {
    var pluginName = 'jupiterx_modal';
    // Create the plugin constructor
    function Modal(element, options) {
        /*
            Provide local access to the DOM node(s) that called the plugin,
            as well local access to the plugin name and default options.
        */
        this.element = element;
        this.body = document.body;
        this.$modal = '';
        this.$overlay = '';
        this.isModalOpen = false;
        this._name = pluginName;
        this._defaults = $.fn.jupiterx_modal.defaults;
        /*
            Abracadabra!
        */
        this.init(options);
    }
    // Avoid Plugin.prototype conflicts
    $.extend(Modal.prototype, {
        // Init logic
        init: function init(options) {
            /*
                Extending options & defaults
            */
            this.options = $.extend({}, this._defaults, options);
            if (this.isModalOpen) {
                this.templateInit();
                this.cacheElements();
                this.open();
            } else {
                this.disableScroll();
                this.templateInit();
                this.showOverlay();
                this.cacheElements();
                this.open();
            }
        },
        // Disable document scrolling
        disableScroll: function disableScroll() {
            $('body').addClass('jupiterx-modal-active');
        },
        // Enable document scrolling
        enableScroll: function enableScroll() {
            $('body').removeClass('jupiterx-modal-active');
        },
        // Show modal overlay
        showOverlay: function showOverlay() {
            var isOverlayCreated = this.$overlay.length;
            if (isOverlayCreated) {
                TweenLite.to(this.$overlay, 0.1, {
                    css: {
                        opacity: 0.5,
                        display: 'block'
                    },
                    ease: Power1.easeOut,
                    delay: 0
                });
            } else {
                this.$overlay = $('<div class="jupiterx-modal-overlay"></div>');
                $(this.body).append(this.$overlay);
                TweenLite.to(this.$overlay, 0.1, {
                    css: {
                        opacity: 0.5,
                        display: 'block'
                    },
                    ease: Power1.easeOut,
                    delay: 0
                });
            }
        },
        // Hide modal overlay
        hideOverlay: function hideOverlay() {
            TweenLite.to(this.$overlay, 0.1, {
                css: {
                    opacity: 0,
                    display: 'none'
                },
                ease: Power1.easeOut,
                delay: 0
            });
        },
        // Initilize Template
        templateInit: function templateInit() {
            this.bindEvents(this.templateBuilder());
        },
        // Build modal templates based on options
        templateBuilder: function templateBuilder() {
            var options = this.options;
            var html = '';
            var typeClass = options.type ? 'jupiterx-modal--' + options.type : '';
            var modalCustomClass = options.modalCustomClass ? ' ' + options.modalCustomClass : '';
            html += '<div id="jupiterx-modal" class="jupiterx jupiterx-modal ' + typeClass + modalCustomClass + '">';
            if (options.showProgress) {
                html += '<div class="progress mt-3 mr-3 ml-3">';
                html += '<div class="progress-bar jupiterx-modal-progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="' + options.progress + '" style="width:' + options.progress + '"></div>';
                html += '</div>';
            }
            html += '<div class="jupiterx-modal-content">';
            if (options.html) {
                html += !options.html instanceof jQuery ? options.html : '';
            } else {
                html += '<div class="jupiterx-modal-header">';
                if (options.showCloseButton) {
                    html += '<button type="button" class="jupiterx-modal-close"><span>×</span></button>';
                }
                html += typeClass ? '<span class="jupiterx-modal-icon"></span>' : '';
                html += !typeClass && options.icon ? '<span class="jupiterx-modal-icon-custom ' + options.icon + '"></span>' : '';
                html += '<h3 class="jupiterx-modal-title">' + options.title + '</h3>';
                html += '</div>';
                html += '<div class="jupiterx-modal-desc">';
                html += typeof options.text === 'string' ? options.text : '';
                html += '</div>';
            }
            if (options.footerHTML) {
                html += '<div class="jupiterx-modal-footer">';
                html += !options.footerHTML instanceof jQuery ? options.footerHTML : '';
                html += '</div>';
            } else if (options.showLearnmoreButton || options.showConfirmButton || options.showCancelButton) {
                html += '<div class="jupiterx-modal-footer">';
                if (options.showConfirmButton) {
                    html += '<button type="button" class="btn btn-primary js__modal-btn-confirm jupiterx-icon-' + options.confirmButtonIcon + '">' + options.confirmButtonText + '</button>';
                }
                if (options.showCancelButton) {
                    html += '<button type="button" class="btn btn-secondary js__modal-btn-cancel jupiterx-icon-' + options.cancelButtonIcon + '">' + options.cancelButtonText + '</button>';
                }
                if (options.showLearnmoreButton) {
                    var learnmoreLabel = 'More Help';
                    if (options.learnmoreLabel) {
                        learnmoreLabel = options.learnmoreLabel;
                    }
                    var learnmoreTarget = '';
                    if (options.learnmoreTarget) {
                        learnmoreTarget = 'target="' + options.learnmoreTarget + '"';
                    }
                    html += '<a ' + learnmoreTarget + ' href="' + options.learnmoreButton + '" class="jupiterx-modal-readmore-btn">' + learnmoreLabel + '</a>';
                }
                html += '</div>';
            }

            html += '</div>';
            html += '</div>';
            var $html = $(html);
            if (options.text && options.text instanceof jQuery) {
                $html.find('.jupiterx-modal-desc').prepend(options.text);
            }
            if (options.html && options.html instanceof jQuery) {
                $html.find('.jupiterx-modal-content').prepend(options.html);
            }
            if (options.footerHTML && options.footerHTML instanceof jQuery) {
                $html.find('.jupiterx-modal-footer').prepend(options.footerHTML);
            }
            return $html;
        },
        // Open modal
        open: function open() {
            var $new_modal = this.$modal;
            var $modal = $(this.body).children('#jupiterx-modal');
            var isModalAdded = $modal.length;
            if (isModalAdded && this.isModalOpen) {
                TweenLite.to($new_modal, 0, {
                    css: {
                        opacity: 1
                    },
                    ease: Power1.easeOut,
                    delay: 0
                });
                $modal.replaceWith($new_modal);
            } else if (isModalAdded && !this.isModalOpen) {
                $modal.replaceWith($new_modal);
                TweenLite.to($new_modal, 0, {
                    css: {
                        opacity: 0,
                        y: '30'
                    },
                    ease: Power1.easeOut,
                    delay: 0
                });
                TweenLite.to($new_modal, 0.2, {
                    css: {
                        opacity: 1,
                        y: '0'
                    },
                    ease: Power4.easeInOut,
                    delay: 0.1
                });
            } else {
                $(this.body).append($new_modal);
                TweenLite.to($new_modal, 0, {
                    css: {
                        opacity: 0,
                        y: '30'
                    },
                    ease: Power1.easeOut,
                    delay: 0
                });
                TweenLite.to($new_modal, 0.2, {
                    css: {
                        opacity: 1,
                        y: '0'
                    },
                    ease: Power4.easeInOut,
                    delay: 0.1
                });
            }
            $new_modal.css({
                marginTop: function marginTop() {
                    return -$(this).outerHeight() / 2 + 'px';
                }
            });
            this.isModalOpen = true;
        },
        // Close modal
        close: function close(template) {
            this.enableScroll();
            this.hideOverlay();
            this.$modal.hide();
            this.isModalOpen = false;
        },
        // Bind events that trigger methods
        bindEvents: function bindEvents($template) {
            var plugin = this;
            var $modal = $template;
            var $closeBtn = $modal.find('.jupiterx-modal-close');
            var $confirmBtn = $modal.find('.js__modal-btn-confirm');
            var $cancelBtn = $modal.find('.js__modal-btn-cancel');
            // Close Button
            $closeBtn.on('click' + '.' + plugin._name, function (e) {
                e.preventDefault();
                plugin.close();
                plugin.onClose();
            });
            $confirmBtn.on('click' + '.' + plugin._name, function (e) {
                e.preventDefault();
                if (plugin.options.closeOnConfirm) {
                    plugin.close();
                }
                plugin.onConfirm();
            });
            $cancelBtn.on('click' + '.' + plugin._name, function (e) {
                e.preventDefault();
                if (plugin.options.closeOnCancel) {
                    plugin.close();
                }
                plugin.onCancel();
            });
            $(document).on('click' + '.' + plugin._name, '.jupiterx-modal-overlay', function (e) {
                if (plugin.options.closeOnOutsideClick) {
                    e.preventDefault();
                    plugin.close();
                    plugin.onOutside();
                }
            });
            this.$modal = $modal;
        },
        // Cache elements for update method
        cacheElements: function cacheElements() {
            var $modal = this.$modal;
            this.$progressBar = $modal.find('.jupiterx-modal-progress-bar');
            this.$title = $modal.find('.jupiterx-modal-title');
            this.$desc = $modal.find('.jupiterx-modal-desc');
        },
        // Update properties
        update: function update(updatedOptions) {
            var options = $.extend({}, this.options, updatedOptions);
            this.$progressBar.css('width', options.progress);
            this.$desc.html(options.desc);
        },
        // Create custom methods
        someOtherFunction: function someOtherFunction() {
            alert('I promise to do something cool!');
            this.callback();
        },
        onConfirm: function onConfirm() {
            var onConfirm = this.options.onConfirm;
            if (typeof onConfirm === 'function') {
                onConfirm.call(this.element);
            }
        },
        onCancel: function onCancel() {
            var onCancel = this.options.onCancel;
            if (typeof onCancel === 'function') {
                onCancel.call(this.element);
            }
        },
        onClose: function onClose() {
            var onClose = this.options.onClose;
            if (typeof onClose === 'function') {
                onClose.call(this.element);
            }
        },
        onOutside: function onOutside() {
            var onOutside = this.options.onOutside;
            if (typeof onOutside === 'function') {
                onOutside.call(this.element);
            }
        },
        disableConfirmBtn: function disableConfirmBtn() {
            this.$modal.find('.js__modal-btn-confirm').attr('disabled', 'disabled');
        },
        enableConfirmBtn: function enableConfirmBtn() {
            this.$modal.find('.js__modal-btn-confirm').removeAttr('disabled');
        },
        hideProgressBar: function hideProgressBar() {
            this.$modal.find('.progress').hide();
        }
    });
    /*
        Create a lightweight plugin wrapper around the "Plugin" constructor,
        preventing against multiple instantiations.
    */
    $.fn.jupiterx_modal = function (options) {
        var pluginInstance = $.data(document.body, "plugin_" + pluginName);
        if (!pluginInstance) {
            /*
                Use "$.data" to save each instance of the plugin in case
                the user wants to modify it. Using "$.data" in this way
                ensures the data is removed when the DOM elements are
                removed via jQuery methods, as well as when the userleaves
                the page. It's a smart way to prevent memory leaks.
            */
            pluginInstance = $.data(document.body, "plugin_" + pluginName, new Modal(this, options));
        } else {
            pluginInstance.init(options);
        }
        /*
            "return this;" returns the original jQuery object. This allows
            additional jQuery methods to be chained.
        */
        return pluginInstance;
    };
    /*
        Attach the default plugin options directly to the plugin object. This
        allows users to override default plugin options globally, instead of
        passing the same option(s) every time the plugin is initialized.
         For example, the user could set the "property" value once for all
        instances of the plugin with
        "$.fn.pluginName.defaults.property = 'myValue';". Then, every time
        plugin is initialized, "property" will be set to "myValue".
    */
    $.fn.jupiterx_modal.defaults = {
        modalCustomClass: '',
        title: '',
        text: '',
        html: null,
        type: 'error',
        showCancelButton: false,
        showConfirmButton: true,
        showCloseButton: true,
        showLearnmoreButton: false,
        showProgress: false,
        progress: '0%',
        indefiniteProgress: false,
        confirmButtonText: 'OK',
        confirmButtonIcon: '',
        cancelButtonText: 'Cancel',
        cancelButtonIcon: '',
        learnmoreButton: '#',
        learnmoreLabel: 'More Help',
        learnmoreTarget: '',
        closeOnConfirm: true,
        closeOnCancel: true,
        closeOnOutsideClick: true,
        onConfirm: null,
        onCancel: null,
        onClose: null,
        onOutside: null
    };
})(jQuery, window, document);
var jupiterx_modal = function jupiterx_modal(options) {
    return jQuery(document.body).jupiterx_modal(options);
};
jupiterx_modal.update = function (update_obj) {
    var pluginInstance = jQuery.data(document.body, "plugin_jupiterx_modal");
    if (pluginInstance) {
        pluginInstance.update(update_obj);
    }
};
jupiterx_modal.disableConfirmBtn = function () {
    var pluginInstance = jQuery.data(document.body, "plugin_jupiterx_modal");
    if (pluginInstance) {
        pluginInstance.disableConfirmBtn();
    }
};
jupiterx_modal.enableConfirmBtn = function () {
    var pluginInstance = jQuery.data(document.body, "plugin_jupiterx_modal");
    if (pluginInstance) {
        pluginInstance.enableConfirmBtn();
    }
};
jupiterx_modal.hideProgressBar = function () {
    var pluginInstance = jQuery.data(document.body, "plugin_jupiterx_modal");
    if (pluginInstance) {
        pluginInstance.hideProgressBar();
    }
};